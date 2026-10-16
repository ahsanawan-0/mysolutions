<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Csrf;
use App\Mailer;
use App\RateLimit;
use App\Router;
use App\Validator;

/**
 * POST /contact-us/ (PROMPTS P23, docs/11 §7, docs/13 §8): the enquiry / quote form.
 *  1. CSRF token missing or wrong → 400.
 *  2. Spam traps (honeypot `website` filled, or the signed `ts` render time missing, forged or < 3 s old) →
 *     silently "accepted": the same redirect as a real submission, nothing is sent.
 *  3. Rate limit: 5 enquiries per hour per IP (storage/cache/ratelimit/) → the form again with a friendly notice (429).
 *  4. Server-side validation → the form again with the values kept, an error summary and inline errors (422).
 *  5. Optional Cloudflare Turnstile (TURNSTILE_ENABLED=true; off by default; no reCAPTCHA).
 *  6. Enquiry to MAIL_TO_ADDRESS (Reply-To = the sender) + an auto-reply to the sender (App\Mailer: SMTP, or .eml
 *     files in storage/mail/ with MAIL_DRIVER=log) → PRG redirect (303) to /thank-you/ (P24).
 */
final class ContactController
{
    private const MAX_PER_HOUR = 5;
    private const MIN_SECONDS = 3;
    private const MESSAGE_MIN = 10;
    private const MESSAGE_MAX = 2000;

    public function submit(array $params, array $route): void
    {
        Csrf::startSession();
        $post = $_POST;

        if (!Csrf::check($post['_token'] ?? null)) {
            Router::render('pages/400', [], 400);
        }

        $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        $age = Csrf::stampAge($post['ts'] ?? null);
        if (Validator::input($post, 'website') !== '' || $age === null || $age < self::MIN_SECONDS) {
            RateLimit::hit('contact', $ip, 3600);
            Router::redirect('/thank-you/', 303);
        }

        [$values, $errors] = self::validate($post);

        if (RateLimit::tooMany('contact', $ip, self::MAX_PER_HOUR, 3600)) {
            self::form($values, [], self::fill((string) contact('form.notices.rate_limited')), 429);
        }

        if ($errors === [] && self::turnstileEnabled() && !self::turnstilePasses($post, $ip)) {
            $errors['turnstile'] = (string) contact('form.errors.turnstile');
        }
        if ($errors !== []) {
            self::form($values, $errors, null, 422);
        }

        if (!Mailer::send(self::enquiryMail($values))) {
            self::form($values, [], self::fill((string) contact('form.notices.send_failed')), 503);
        }
        Mailer::send(self::autoReply($values));   // a failed auto-reply is logged; the enquiry itself was delivered

        RateLimit::hit('contact', $ip, 3600);
        $_SESSION['enquiry_sent'] = time();         // P24: the thank-you page's one-time form_submit event
        unset($_SESSION['_csrf']);                  // a fresh token for the next form
        Router::redirect('/thank-you/', 303);
    }

    /** @return array{0: array<string, string|bool>, 1: array<string, string>} the cleaned values and the errors by field */
    public static function validate(array $post): array
    {
        $msg = contact('form.errors');
        $v = [
            'name'      => Validator::input($post, 'name'),
            'company'   => Validator::input($post, 'company'),
            'email'     => Validator::input($post, 'email'),
            'phone'     => Validator::input($post, 'phone'),
            'sector'    => Validator::input($post, 'sector'),
            'service'   => Validator::input($post, 'service'),
            'deadline'  => Validator::input($post, 'deadline'),
            'callback'  => Validator::input($post, 'callback'),
            'message'   => Validator::input($post, 'message', true),
            'marketing' => ($post['marketing'] ?? '') === '1',
        ];
        $e = [];

        if ($v['name'] === '') {
            $e['name'] = $msg['name_required'];
        } elseif (!Validator::length($v['name'], 1, 100)) {
            $e['name'] = $msg['name_length'];
        }
        if (!Validator::length($v['company'], 0, 150)) {
            $e['company'] = $msg['company_length'];
        }
        if ($v['email'] === '') {
            $e['email'] = $msg['email_required'];
        } elseif (!Validator::email($v['email'])) {
            $e['email'] = $msg['email_invalid'];
        }
        if ($v['phone'] !== '' && !Validator::phone($v['phone'])) {
            $e['phone'] = $msg['phone_invalid'];
        }
        if ($v['sector'] !== '' && !Validator::oneOf($v['sector'], self::sectorValues())) {
            $e['sector'] = $msg['sector_invalid'];
        }
        if ($v['service'] !== '' && !Validator::oneOf($v['service'], self::serviceValues())) {
            $e['service'] = $msg['service_invalid'];
        }
        if ($v['deadline'] !== '') {
            if (!Validator::date($v['deadline'])) {
                $e['deadline'] = $msg['deadline_invalid'];
            } elseif (!Validator::date($v['deadline'], date('Y-m-d'))) {
                $e['deadline'] = $msg['deadline_past'];
            }
        }
        if ($v['callback'] !== '' && !Validator::oneOf($v['callback'], array_keys(array_filter((array) contact('form.options.callback'), 'strlen', ARRAY_FILTER_USE_KEY)))) {
            $e['callback'] = $msg['callback_invalid'];
        }
        if ($v['message'] === '') {
            $e['message'] = $msg['message_required'];
        } elseif (mb_strlen($v['message']) < self::MESSAGE_MIN) {
            $e['message'] = $msg['message_short'];
        } elseif (mb_strlen($v['message']) > self::MESSAGE_MAX) {
            $e['message'] = $msg['message_long'];
        }
        return [$v, $e];
    }

    /** @return string[] sector slugs + 'other' */
    public static function sectorValues(): array
    {
        return [...array_column(sectors(), 'slug'), 'other'];
    }

    /** @return string[] sub-service slugs (+ the category slugs, for the P24 pre-fill) */
    public static function serviceValues(): array
    {
        $values = [];
        foreach (services() as $category) {
            $values[] = $category['slug'];
            foreach ($category['subs'] as $sub) {
                $values[] = $sub['slug'];
            }
        }
        return $values;
    }

    /** Human-readable name for a service / sector slug (for the emails). */
    public static function label(string $kind, string $slug): string
    {
        if ($slug === '') {
            return '';
        }
        if ($kind === 'sector') {
            return $slug === 'other' ? (string) contact('form.options.sector_other') : (sector($slug)['name'] ?? $slug);
        }
        return service_sub_find($slug)['sub']['name'] ?? (service_category($slug)['name'] ?? $slug);
    }

    private static function form(array $values, array $errors, ?string $notice, int $status): never
    {
        Router::render('pages/contact', ['form' => ['values' => $values, 'errors' => $errors, 'notice' => $notice]], $status);
    }

    private static function fill(string $text): string
    {
        return strtr($text, ['{phone}' => (string) config('phone_display'), '{email}' => (string) config('email')]);
    }

    private static function enquiryMail(array $v): array
    {
        $t = contact('emails.enquiry');
        $service = self::label('service', (string) $v['service']) ?: $t['general'];
        $vars = ['v' => $v, 't' => $t, 'service' => $service, 'sector' => self::label('sector', (string) $v['sector'])];
        return [
            'kind'     => 'enquiry',
            'to'       => (string) env('MAIL_TO_ADDRESS', config('email')),
            'reply_to' => [$v['email'], $v['name']],
            'subject'  => strtr($t['subject'], ['{service}' => $service, '{name}' => $v['name']]),
            'html'     => render('emails/enquiry', $vars),
            'text'     => render('emails/enquiry-text', $vars),
        ];
    }

    private static function autoReply(array $v): array
    {
        $t = contact('emails.autoreply');
        $vars = ['v' => $v, 't' => $t, 'service' => self::label('service', (string) $v['service']), 'sector' => self::label('sector', (string) $v['sector'])];
        return [
            'kind'     => 'autoreply',
            'to'       => $v['email'],
            'to_name'  => $v['name'],
            'reply_to' => [(string) env('MAIL_TO_ADDRESS', config('email')), (string) config('name')],
            'subject'  => $t['subject'],
            'html'     => render('emails/autoreply', $vars),
            'text'     => render('emails/autoreply-text', $vars),
        ];
    }

    public static function turnstileEnabled(): bool
    {
        return filter_var(env('TURNSTILE_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN) && env('TURNSTILE_SECRET_KEY', '') !== '';
    }

    /** Cloudflare Turnstile server-side check (only when enabled; disclosed as strictly necessary security, docs/13 §2). */
    private static function turnstilePasses(array $post, string $ip): bool
    {
        $response = $post['cf-turnstile-response'] ?? '';
        if (!is_string($response) || $response === '') {
            return false;
        }
        $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query(['secret' => env('TURNSTILE_SECRET_KEY'), 'response' => $response, 'remoteip' => $ip]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 8,
        ]);
        $result = json_decode((string) curl_exec($ch), true);
        return is_array($result) && ($result['success'] ?? false) === true;
    }
}
