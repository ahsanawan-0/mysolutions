<?php
declare(strict_types=1);

namespace App;

/**
 * CSRF protection for the site's forms (PROMPTS P23, docs/11 §7). The token lives in a PHP session that is started
 * ONLY by the pages that render or handle a form (never site-wide): cookie `ms_session`, HttpOnly, SameSite=Lax,
 * Secure on HTTPS, session-only lifetime. A strictly necessary cookie (docs/13 §2), so no consent is needed.
 *
 *   Csrf::token()            → the token for the hidden `_token` field (starts the session)
 *   Csrf::check($submitted)  → constant-time comparison with the session token
 *   Csrf::stamp() / Csrf::stampAge($value) → the signed render time for the `ts` time-trap field
 */
final class Csrf
{
    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $https = (($_SERVER['HTTPS'] ?? '') !== '' && $_SERVER['HTTPS'] !== 'off')
            || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
        session_name('ms_session');
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => $https,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');
        session_start();
    }

    /**
     * Resume the form session only when the visitor already has one (P24: the thank-you page reads its one-time
     * `enquiry_sent` flag). Never starts a cookie for someone who has not used a form.
     */
    public static function resume(): bool
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return true;
        }
        if (!is_string($_COOKIE['ms_session'] ?? null) || PHP_SAPI === 'cli') {
            return false;
        }
        self::startSession();
        return true;
    }

    public static function token(): string
    {
        self::startSession();
        if (empty($_SESSION['_csrf']) || !is_string($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function check(mixed $submitted): bool
    {
        self::startSession();
        $token = $_SESSION['_csrf'] ?? null;
        return is_string($token) && $token !== '' && is_string($submitted) && hash_equals($token, $submitted);
    }

    /** The form's render time, signed with the session token so a bot cannot simply post an old timestamp. */
    public static function stamp(): string
    {
        $time = (string) time();
        return $time . '.' . hash_hmac('sha256', $time, self::token());
    }

    /** Seconds since the form was rendered, or null when the stamp is missing, forged or from another session. */
    public static function stampAge(mixed $value): ?int
    {
        if (!is_string($value) || preg_match('/^(\d{9,11})\.([a-f0-9]{64})$/', $value, $m) !== 1) {
            return null;
        }
        if (!hash_equals(hash_hmac('sha256', $m[1], self::token()), $m[2])) {
            return null;
        }
        return time() - (int) $m[1];
    }
}
