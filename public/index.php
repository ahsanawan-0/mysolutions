<?php
declare(strict_types=1);

/**
 * Front controller. Every non-file request is rewritten here (.htaccess / nginx).
 */

// PHP's built-in server (php -S … public/index.php): let it serve real files itself.
if (PHP_SAPI === 'cli-server') {
    $requested = rawurldecode((string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
    $file = realpath(__DIR__ . $requested);
    if ($file !== false && is_file($file) && str_starts_with($file, realpath(__DIR__) . DIRECTORY_SEPARATOR)) {
        return false;
    }
}

require dirname(__DIR__) . '/app/bootstrap.php';

App\Router::dispatch();
