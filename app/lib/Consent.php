<?php
declare(strict_types=1);

namespace App;

use App\Controllers\ContactController;

/**
 * Cookie consent (PROMPTS P28, docs/13 §2): reads app/config/cookies.php and answers what this deployment uses.
 *  - technologies(): the items in use (filtered by `when`), with `exempt` resolved;
 *  - categories(): the categories that have at least one technology (necessary always), each with its items;
 *  - needsBanner(): true when anything non-essential is configured (otherwise no banner, no consent script);
 *  - mode(): 'consent' (opt-in first) or 'notice' (only exempt analytics: information + an objection toggle);
 *  - client(): the settings consent.js needs (printed as JSON on the banner);
 *  - headScript(): the Google Consent Mode v2 default (all denied), printed in <head> before any Google tag (GA4).
 */
final class Consent
{
    private static ?array $config = null;

    public static function config(?string $key = null, mixed $default = null): mixed
    {
        self::$config ??= (array) require base_path('app/config/cookies.php');
        return $key === null ? self::$config : data_get(self::$config, $key, $default);
    }

    public static function provider(): string
    {
        $provider = (string) self::config('analytics_provider', 'none');
        // A provider without its ID (GA4 measurement ID / Plausible domain) cannot run: treat it as none
        return $provider !== 'none' && (string) self::config('analytics_id', '') === '' ? 'none' : $provider;
    }

    /** @return array<int, array<string, mixed>> */
    public static function technologies(): array
    {
        $active = ['ga4' => self::provider() === 'ga4', 'plausible' => self::provider() === 'plausible', 'turnstile' => ContactController::turnstileEnabled()];
        $stream = str_replace('G-', '', (string) self::config('analytics_id', ''));
        $out = [];
        foreach ((array) self::config('technologies', []) as $tech) {
            if (isset($tech['when']) && empty($active[$tech['when']])) {
                continue;
            }
            $tech['name'] = str_replace('{ga4_stream}', $stream !== '' ? $stream : '<ID>', (string) $tech['name']);
            $tech['exempt'] = $tech['exempt'] ?? (bool) self::config('analytics_exempt', false);
            $out[] = $tech;
        }
        return $out;
    }

    /** @return array<string, array<string, mixed>> category key → label, description, required, items */
    public static function categories(): array
    {
        $out = [];
        foreach ((array) self::config('categories', []) as $key => $category) {
            $items = array_values(array_filter(self::technologies(), static fn (array $t): bool => $t['category'] === $key));
            if ($items !== [] || !empty($category['required'])) {
                $out[$key] = $category + ['key' => $key, 'required' => false, 'items' => $items];
            }
        }
        return $out;
    }

    public static function needsBanner(): bool
    {
        foreach (self::technologies() as $tech) {
            if ($tech['category'] !== 'necessary') {
                return true;
            }
        }
        return false;
    }

    /** 'notice' when every non-essential technology is exempt (DUAA statistics), otherwise 'consent'. */
    public static function mode(): string
    {
        foreach (self::technologies() as $tech) {
            if ($tech['category'] !== 'necessary' && !$tech['exempt']) {
                return 'consent';
            }
        }
        return 'notice';
    }

    public static function client(): array
    {
        return [
            'version'   => (int) self::config('version', 1),
            'cookie'    => (string) self::config('cookie', 'ms_consent'),
            'maxAge'    => (int) self::config('max_age_days', 182),
            'mode'      => self::mode(),
            'provider'  => self::provider(),
            'id'        => (string) self::config('analytics_id', ''),
            'src'       => (string) self::config('plausible_src', ''),
            'marketing' => isset(self::categories()['marketing']),
        ];
    }

    /** Consent Mode v2 default, before any Google tag (docs/10 §7). Empty unless GA4 is configured. */
    public static function headScript(): string
    {
        if (self::provider() !== 'ga4') {
            return '';
        }
        return "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}"
            . "gtag('consent','default',{ad_storage:'denied',ad_user_data:'denied',ad_personalization:'denied',analytics_storage:'denied',wait_for_update:500});</script>";
    }
}
