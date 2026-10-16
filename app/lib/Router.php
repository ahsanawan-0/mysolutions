<?php
declare(strict_types=1);

namespace App;

/**
 * Request dispatcher, applied in this order:
 *  1. Normalise the path (lowercase, trailing slash for pages) — one 301 at most.
 *  2. app/redirects.php: old-site URLs → one-hop 301 or 410.
 *  3. app/routes.php: exact paths and {param} patterns → template or controller (`post` → controller for POST).
 *  4. Real 404.
 */
final class Router
{
    private const PARAM_PATTERN = '[a-z0-9-]+';

    public static function dispatch(): void
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $rawPath = rawurldecode((string) (parse_url($requestUri, PHP_URL_PATH) ?: '/'));
        $query = (string) (parse_url($requestUri, PHP_URL_QUERY) ?? '');

        // 1. Normalise (in memory first, so a legacy URL still reaches its target in ONE hop)
        $path = self::normalise($rawPath);

        // 2. Legacy redirects (301 / 410)
        $redirects = require base_path('app/redirects.php');
        $legacy = self::matchRedirect($path, $redirects) ?? self::matchRedirect($rawPath, $redirects);
        if ($legacy !== null) {
            if ($legacy['status'] === 410) {
                self::render('pages/410', [], 410);
            }
            self::redirect($legacy['target'], 301);
        }

        if ($path !== $rawPath) {
            self::redirect($path . ($query !== '' ? '?' . $query : ''), 301);
        }

        // 3. Routes
        $routes = require base_path('app/routes.php');
        $match = self::matchRoute($path, $routes);
        if ($match !== null) {
            [$route, $params] = $match;

            if (!empty($route['local_only']) && !is_local()) {
                self::render('pages/404', [], 404);
            }

            // A route may hand POST requests to a controller (P23: 'post' => [ContactController::class, 'submit'])
            if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($route['post'])) {
                [$class, $method] = $route['post'];
                (new $class())->$method($params, $route);
                exit;
            }

            if (isset($route['controller'])) {
                [$class, $method] = $route['controller'];
                (new $class())->$method($params, $route);
                exit;
            }

            $template = $route['template'];
            if (!is_file(templates_path($template))) {
                if (is_local()) {
                    self::render('pages/_stub', [
                        'route'    => $route,
                        'params'   => $params,
                        'path'     => $path,
                        'template' => $template,
                    ], 200);
                }
                self::render('pages/404', [], 404);
            }

            self::render($template, ['params' => $params, 'route' => $route], 200);
        }

        // 4. Not found
        self::render('pages/404', [], 404);
    }

    /**
     * Lowercase + trailing slash for page paths. File-like paths (/sitemap.xml, /robots.txt)
     * keep their form. Collapses duplicate slashes.
     */
    public static function normalise(string $path): string
    {
        $path = '/' . ltrim(preg_replace('#/{2,}#', '/', $path) ?? '/', '/');
        $path = mb_strtolower($path);

        $isFile = pathinfo($path, PATHINFO_EXTENSION) !== '';
        if (!$isFile && !str_ends_with($path, '/')) {
            $path .= '/';
        }
        return $path;
    }

    /** @return array{status:int,target:?string}|null */
    private static function matchRedirect(string $path, array $redirects): ?array
    {
        if (isset($redirects['exact'][$path])) {
            return ['status' => 301, 'target' => $redirects['exact'][$path]];
        }
        foreach ($redirects['rules'] as [$pattern, $status, $target]) {
            if (preg_match($pattern, $path, $m) === 1) {
                if ($status === 410) {
                    return ['status' => 410, 'target' => null];
                }
                // $target may use $1, $2 … from the pattern
                return ['status' => 301, 'target' => preg_replace($pattern, $target, $path)];
            }
        }
        return null;
    }

    /** @return array{0:array,1:array<string,string>}|null */
    public static function matchRoute(string $path, array $routes): ?array
    {
        if (isset($routes[$path])) {
            return [$routes[$path], $routes[$path]['params'] ?? []];
        }
        foreach ($routes as $pattern => $route) {
            if (!str_contains($pattern, '{')) {
                continue;
            }
            // Quote literal parts, then turn {param} placeholders into named groups
            $quoted = str_replace(['\{', '\}'], ['{', '}'], preg_quote($pattern, '#'));
            $regex = '#^' . preg_replace_callback(
                '/\{([a-z_]+)\}/',
                static fn (array $m): string => '(?P<' . $m[1] . '>' . ($m[1] === 'n' ? '[0-9]+' : self::PARAM_PATTERN) . ')',
                $quoted
            ) . '$#';

            if (preg_match($regex, $path, $m) !== 1) {
                continue;
            }
            $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
            if (isset($route['where']) && !self::constraintsPass($params, $route['where'])) {
                continue;
            }
            return [$route, array_merge($route['params'] ?? [], $params)];
        }
        return null;
    }

    private static function constraintsPass(array $params, array $where): bool
    {
        foreach ($where as $name => $allowed) {
            if (!isset($params[$name])) {
                return false;
            }
            if (is_array($allowed) && !in_array($params[$name], $allowed, true)) {
                return false;
            }
            if (is_string($allowed) && preg_match($allowed, $params[$name]) !== 1) {
                return false;
            }
        }
        return true;
    }

    public static function redirect(string $target, int $status): never
    {
        $location = str_starts_with($target, 'http') ? $target : url($target);
        http_response_code($status);
        header('Location: ' . $location);
        header('Cache-Control: ' . ($status === 301 ? 'public, max-age=31536000' : 'no-cache'));
        exit;
    }

    /**
     * Render a page template inside the base layout.
     * The page template sets `$page` (title, description, canonical, robots, …) and prints its body;
     * Seo::prepare() normalises and validates it before the layout renders the <head>.
     * Public so a controller can re-render its page (P23: the contact form with errors and the values kept).
     */
    public static function render(string $__template, array $__vars, int $__status): never
    {
        http_response_code($__status);
        header('Content-Type: text/html; charset=UTF-8');

        $page = [];
        extract($__vars, EXTR_SKIP);
        ob_start();
        include templates_path($__template);
        $content = (string) ob_get_clean();

        $page = Seo::prepare($page, current_path());

        if (($page['layout'] ?? 'base') !== false) {
            include templates_path('layouts/' . ($page['layout'] ?? 'base'));
        } else {
            echo $content;
        }
        exit;
    }
}
