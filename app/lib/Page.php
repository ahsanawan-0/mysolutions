<?php
declare(strict_types=1);

namespace App;

use Throwable;

/**
 * The page contract (docs/11 §4). Every template sets `$page`; this class fills the defaults
 * from config/site.php and normalises values so Seo, Schema and the sitemaps see one shape.
 *
 * Keys: title, description, canonical (path or absolute URL; false = intentionally none),
 * og_title, og_description, og_image, og_image_alt, og_type (website|article), robots,
 * status (published|draft), breadcrumbs ([[label, path|null], …]), schema (graph nodes),
 * published, modified (ISO 8601 / Y-m-d), body_class, js_modules, web_page_type
 * (WebPage|AboutPage|ContactPage|CollectionPage), primary_image (absolute URL or path).
 * Derived: path, indexable (bool), warnings (string[], added by Seo::prepare).
 */
final class Page
{
    public const PUBLISHED = 'published';
    public const DRAFT = 'draft';

    public static function normalise(array $page, ?string $path = null): array
    {
        $path ??= current_path();
        $suffix = (string) config('seo.title_suffix', ' | My Solutionz');

        $page += [
            'title'          => '',
            'description'    => '',
            'canonical'      => $path,
            'og_title'       => null,
            'og_description' => null,
            'og_image'       => null,
            'og_image_alt'   => null,
            'og_type'        => 'website',
            'robots'         => (string) config('seo.default_robots'),
            'status'         => self::DRAFT,
            'breadcrumbs'    => [],
            'schema'         => [],
            'published'      => null,
            'modified'       => null,
            'body_class'     => '',
            'js_modules'     => [],
            'web_page_type'  => 'WebPage',
            'primary_image'  => null,
            'path'           => $path,
        ];

        $page['status'] = $page['status'] === self::PUBLISHED ? self::PUBLISHED : self::DRAFT;
        $page['title'] = trim((string) $page['title']);
        $page['description'] = trim((string) $page['description']);

        // Canonical: absolute, query stripped, trailing slash for page paths (false = none, e.g. 404)
        if ($page['canonical'] !== false) {
            $canonical = (string) $page['canonical'];
            $canonical = preg_replace('/[?#].*$/', '', $canonical) ?? $canonical;
            $page['canonical'] = str_starts_with($canonical, 'http') ? $canonical : url($canonical);
        }

        // Open Graph defaults
        $page['og_title'] ??= str_ends_with($page['title'], $suffix)
            ? substr($page['title'], 0, -strlen($suffix))
            : $page['title'];
        $page['og_description'] ??= $page['description'];
        $page['og_image'] = self::absolute($page['og_image'] ?? (string) config('seo.og_default'));
        $page['og_image_alt'] ??= (string) config('seo.og_default_alt');
        $page['og_type'] = $page['og_type'] === 'article' ? 'article' : 'website';
        if ($page['primary_image'] !== null) {
            $page['primary_image'] = self::absolute((string) $page['primary_image']);
        }

        // Indexable = what the page would be in production (used by the sitemaps and checkers) …
        $page['indexable'] = $page['status'] === self::PUBLISHED
            && !str_contains((string) $page['robots'], 'noindex')
            && $page['canonical'] !== false;
        // … but draft pages and non-production environments are never actually indexable
        if ($page['status'] !== self::PUBLISHED || !is_production()) {
            $page['robots'] = 'noindex, follow';
        }

        $page['published'] = self::date($page['published']);
        $page['modified'] = self::date($page['modified']);

        return $page;
    }

    /** True when the page may appear in sitemaps: published, not noindex by its own choice, with a canonical. */
    public static function isIndexable(array $page): bool
    {
        return (bool) ($page['indexable'] ?? false);
    }

    /**
     * Load a template only to read its `$page` (output discarded). Used by the sitemaps and the
     * CLI checkers. Returns null when the template doesn't exist or fails.
     */
    public static function probe(string $template, array $params = [], ?string $path = null): ?array
    {
        $file = templates_path($template);
        if (!is_file($file)) {
            return null;
        }
        $route = ['template' => $template];
        $page = [];
        try {
            ob_start();
            (static function () use ($file, $params, $route, &$page): void {
                include $file;
            })();
        } catch (Throwable) {
            ob_end_clean();
            return null;
        }
        ob_end_clean();
        if ($page === []) {
            return null;
        }
        $page = self::normalise($page, $path);
        $page['template_mtime'] = (int) filemtime($file);
        return $page;
    }

    /** Normalise a date to ISO 8601 with the Europe/London offset; null when empty/invalid. */
    public static function date(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        $ts = is_int($value) ? $value : strtotime((string) $value);
        return $ts === false ? null : date('c', $ts);
    }

    public static function absolute(string $urlOrPath): string
    {
        return str_starts_with($urlOrPath, 'http') ? $urlOrPath : url($urlOrPath);
    }
}
