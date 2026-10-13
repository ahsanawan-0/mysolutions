<?php
declare(strict_types=1);

namespace App;

use RuntimeException;

/**
 * Validates the page contract and renders the <head> (docs/10 §7) with the JSON-LD graph.
 * Published pages with a broken contract throw on local; draft pages collect warnings
 * that the draft ribbon shows on non-production environments.
 */
final class Seo
{
    public const TITLE_MIN = 30;
    public const TITLE_MAX = 65;
    public const DESC_MIN = 70;
    public const DESC_MAX = 170;

    /** Normalise + validate. Adds `warnings` (string[]) to the page. */
    public static function prepare(array $page, ?string $path = null): array
    {
        $page = Page::normalise($page, $path);
        $problems = self::validate($page);

        if ($problems !== [] && $page['status'] === Page::PUBLISHED && is_local()) {
            throw new RuntimeException('SEO contract failed for ' . $page['path'] . ":\n - " . implode("\n - ", $problems));
        }
        if ($problems !== []) {
            error_log('[seo] ' . $page['path'] . ': ' . implode(' | ', $problems));
        }
        $page['warnings'] = $problems;
        return $page;
    }

    /** @return string[] */
    public static function validate(array $page): array
    {
        $p = [];
        $titleLen = mb_strlen($page['title']);
        $descLen = mb_strlen($page['description']);
        $noindex = str_contains((string) $page['robots'], 'noindex');

        if ($page['title'] === '') {
            $p[] = 'title is missing';
        } elseif ($titleLen < self::TITLE_MIN || $titleLen > self::TITLE_MAX) {
            $p[] = "title is {$titleLen} chars (expected " . self::TITLE_MIN . '–' . self::TITLE_MAX . ')';
        }

        if ($page['description'] === '') {
            if (!($page['canonical'] === false && $noindex)) {   // error pages may omit it
                $p[] = 'description is missing';
            }
        } elseif ($descLen < self::DESC_MIN || $descLen > self::DESC_MAX) {
            $p[] = "description is {$descLen} chars (expected " . self::DESC_MIN . '–' . self::DESC_MAX . ')';
        }

        if ($page['canonical'] === false) {
            if (!$noindex) {
                $p[] = 'canonical is disabled but the page is indexable';
            }
        } else {
            $c = (string) $page['canonical'];
            if ($c === '') {
                $p[] = 'canonical is missing';
            } elseif (is_production()) {
                $isFile = pathinfo((string) parse_url($c, PHP_URL_PATH), PATHINFO_EXTENSION) !== '';
                if (!str_starts_with($c, 'https://') || str_contains($c, '?') || (!$isFile && !str_ends_with($c, '/'))) {
                    $p[] = "canonical must be absolute https with a trailing slash ({$c})";
                }
            }
        }

        if ($page['og_image_alt'] === '' || $page['og_image_alt'] === null) {
            $p[] = 'og_image_alt is missing';
        }
        if ($page['path'] !== '/' && $page['breadcrumbs'] === [] && $page['canonical'] !== false) {
            $p[] = 'breadcrumbs are missing';
        }
        if (str_contains($page['title'] . ' ' . $page['description'], '[[PENDING')) {
            $p[] = 'title/description still contain a [[PENDING]] marker';
        }
        return $p;
    }

    /** Render the <head> contents in the docs/10 §7 order. */
    public static function head(array $page): string
    {
        $jsonLd = Schema::json(Schema::graph($page));
        return render('partials/head-seo', ['page' => $page, 'jsonLd' => $jsonLd]);
    }
}
