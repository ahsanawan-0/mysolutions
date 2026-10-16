<?php
declare(strict_types=1);

/**
 * Application bootstrap: paths, autoload, .env, environment, errors, locale, security headers.
 * Required once by public/index.php (and by bin/ scripts).
 */

define('BASE_PATH', dirname(__DIR__));

$autoload = BASE_PATH . '/vendor/autoload.php';
if (!is_file($autoload)) {
    http_response_code(500);
    exit('Dependencies missing: run `composer install` in the project root.');
}
require $autoload;

// .env (optional on servers that inject real environment variables)
if (is_file(BASE_PATH . '/.env')) {
    Dotenv\Dotenv::createImmutable(BASE_PATH)->safeLoad();
}

// Environment: local | staging | production (anything unknown is treated as production)
define('APP_ENV', app_env());

// Errors: show on local only; always log to storage/logs/app.log
error_reporting(E_ALL);
ini_set('display_errors', APP_ENV === 'local' ? '1' : '0');
ini_set('display_startup_errors', APP_ENV === 'local' ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', storage_path('logs/app.log'));

set_exception_handler(static function (Throwable $e): void {
    error_log(sprintf('[%s] %s in %s:%d', $e::class, $e->getMessage(), $e->getFile(), $e->getLine()));
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
    }
    if (APP_ENV === 'local') {
        echo '<pre style="white-space:pre-wrap">' . e($e::class . ': ' . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine() . "\n\n" . $e->getTraceAsString()) . '</pre>';
    } else {
        echo '<!doctype html><html lang="en-GB"><head><meta charset="utf-8"><title>Something went wrong | My Solutionz</title><meta name="robots" content="noindex"></head><body><h1>Something went wrong</h1><p>Please try again shortly or email <a href="mailto:info@mysolutionz.co.uk">info@mysolutionz.co.uk</a>.</p></body></html>';
    }
    exit;
});

date_default_timezone_set('Europe/London');
mb_internal_encoding('UTF-8');

/**
 * Basic security headers for every response. The full nonce-based CSP is added in P34.
 * On non-production hosts the whole site is kept out of search indexes.
 */
function send_security_headers(): void
{
    if (headers_sent()) {
        return;
    }
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()');
    header_remove('X-Powered-By');
    if (APP_ENV !== 'production') {
        header('X-Robots-Tag: noindex, nofollow');
    }
}

send_security_headers();
