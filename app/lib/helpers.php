<?php
declare(strict_types=1);

/**
 * Global helper functions (autoloaded by Composer "files").
 * Templates use these; nothing else is global.
 */

/** Absolute path inside the project root. */
function base_path(string $path = ''): string
{
    return BASE_PATH . ($path === '' ? '' : '/' . ltrim($path, '/'));
}

/** Absolute path inside public/ (the web root). */
function public_path(string $path = ''): string
{
    return base_path('public' . ($path === '' ? '' : '/' . ltrim($path, '/')));
}

/** Absolute path to a template file: templates_path('pages/home') → …/templates/pages/home.php */
function templates_path(string $template): string
{
    return base_path('templates/' . ltrim($template, '/') . '.php');
}

/** Escape for HTML output. Every echo of dynamic data goes through this. */
function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

/** Read an environment variable (from .env or the real environment). */
function env(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return (string) $value;
}

/** Environment name: local | staging | production. */
function app_env(): string
{
    $env = env('APP_ENV', 'production');
    return in_array($env, ['local', 'staging', 'production'], true) ? $env : 'production';
}

function is_local(): bool
{
    return app_env() === 'local';
}

function is_production(): bool
{
    return app_env() === 'production';
}

/**
 * Site configuration from app/config/site.php, with dot access:
 * config('name'), config('address.postcode'), config('socials.linkedin').
 */
function config(string $key, mixed $default = null): mixed
{
    static $config = null;
    if ($config === null) {
        $config = require base_path('app/config/site.php');
    }
    $value = $config;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

/**
 * Absolute URL for a site path. Page paths get a trailing slash; file paths
 * (anything with an extension, e.g. /sitemap.xml) and hash/query parts are kept as given.
 * url('/about-us') → https://mysolutionz.co.uk/about-us/
 */
function url(string $path = '/'): string
{
    $base = rtrim((string) config('url'), '/');
    $path = '/' . ltrim($path, '/');

    [$pathOnly, $suffix] = preg_split('/(?=[?#])/', $path, 2) + [1 => ''];
    if ($pathOnly !== '/' && !str_ends_with($pathOnly, '/') && pathinfo($pathOnly, PATHINFO_EXTENSION) === '') {
        $pathOnly .= '/';
    }
    return $base . $pathOnly . $suffix;
}

/**
 * Root-relative URL for a static file in public/assets/, cache-busted with the file's mtime.
 * asset('og/home.jpg') → /assets/og/home.jpg?v=1760000000
 */
function asset(string $path): string
{
    $path = ltrim($path, '/');
    if (str_starts_with($path, 'assets/')) {
        $path = substr($path, 7);
    }
    $file = public_path('assets/' . $path);
    $version = is_file($file) ? (string) filemtime($file) : '0';
    return '/assets/' . $path . '?v=' . $version;
}

/**
 * Print the <link>/<script> tags for a Vite entry.
 * local + VITE_DEV=true → the Vite dev server (CSS is injected by @vite/client);
 * otherwise → hashed files from public/assets/build/manifest.json.
 */
function vite(string $entry = 'src/js/main.js'): string
{
    $devEnabled = is_local() && filter_var(env('VITE_DEV', 'false'), FILTER_VALIDATE_BOOLEAN);

    if ($devEnabled) {
        // The dev server serves under vite.config.js `base` ('/assets/build/'), same as the build output
        $server = 'http://localhost:5173/assets/build';
        return "<script type=\"module\" src=\"{$server}/@vite/client\"></script>\n"
            . "<script type=\"module\" src=\"{$server}/" . e($entry) . "\"></script>\n";
    }

    static $manifest = null;
    if ($manifest === null) {
        $file = public_path('assets/build/manifest.json');
        $manifest = is_file($file) ? (json_decode((string) file_get_contents($file), true) ?: []) : [];
    }

    if (!isset($manifest[$entry])) {
        return "<!-- vite: no build found for {$entry}. Run `npm run build` (or set VITE_DEV=true locally). -->\n";
    }

    $html = '';
    $chunk = $manifest[$entry];
    foreach ($chunk['css'] ?? [] as $css) {
        $html .= '<link rel="stylesheet" href="/assets/build/' . e($css) . "\">\n";
    }
    $html .= '<script type="module" src="/assets/build/' . e($chunk['file']) . "\"></script>\n";
    foreach ($chunk['imports'] ?? [] as $import) {
        if (isset($manifest[$import]['file'])) {
            $html .= '<link rel="modulepreload" href="/assets/build/' . e($manifest[$import]['file']) . "\">\n";
        }
    }
    return $html;
}

/** Current request path (decoded, no query string). */
function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return rawurldecode($path);
}

/**
 * True when $path is the current page (exact) or, for section roots like /services/,
 * an ancestor of it: used to mark the active nav item.
 */
function is_active(string $path, bool $exact = false): bool
{
    $current = current_path();
    $path = '/' . trim($path, '/') . '/';
    if ($path === '//') {
        $path = '/';
    }
    if ($exact || $path === '/') {
        return $current === $path;
    }
    return $current === $path || str_starts_with($current, $path);
}

/** Render a template (templates/<name>.php) to a string with $vars extracted into its scope. */
function render(string $template, array $vars = []): string
{
    $__file = templates_path($template);
    if (!is_file($__file)) {
        throw new RuntimeException("Template not found: {$template}");
    }
    extract($vars, EXTR_SKIP);
    ob_start();
    try {
        include $__file;
    } finally {
        $__html = ob_get_clean();
    }
    return (string) $__html;
}

/**
 * Inline SVG icon from the Phosphor sprite. Stub until P02 builds the sprite:
 * prints an empty, hidden placeholder so templates can already call icon().
 */
function icon(string $name, string $class = '', ?string $label = null): string
{
    $attrs = 'class="icon icon-' . e($name) . ($class !== '' ? ' ' . e($class) : '') . '"';
    $attrs .= $label === null ? ' aria-hidden="true" focusable="false"' : ' role="img" aria-label="' . e($label) . '"';
    return '<svg ' . $attrs . ' width="24" height="24"><use href="/assets/icons/sprite.svg#' . e($name) . '"></use></svg>';
}
