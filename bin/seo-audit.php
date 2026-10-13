#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * SEO audit: requests every published route from a running server and checks the rendered HTML.
 *   php bin/seo-audit.php                         (base http://localhost:8000, published pages only)
 *   php bin/seo-audit.php --base=https://staging.example --all   (--all also audits draft pages)
 *
 * Checks: exactly one <h1>; title, description, canonical, og:image, og:image:alt, twitter:card present;
 * JSON-LD parses; every <img> has alt, width and height; no href="#" dummy links.
 */

$_SERVER['REQUEST_URI'] = '/';
require dirname(__DIR__) . '/app/bootstrap.php';

use App\Page;

$base = 'http://localhost:8000';
$all = false;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--base=')) {
        $base = rtrim(substr($arg, 7), '/');
    }
    if ($arg === '--all') {
        $all = true;
    }
}

$routes = require base_path('app/routes.php');
$urls = [];
foreach ($routes as $path => $route) {
    if (isset($route['controller']) || str_contains($path, '{') || (!empty($route['local_only']) && !is_local())) {
        continue;
    }
    $page = Page::probe($route['template'], $route['params'] ?? [], $path);
    if ($page === null || $page['canonical'] === false) {
        continue;
    }
    if ($page['status'] === Page::PUBLISHED || $all) {
        $urls[] = $path;
    }
}

if ($urls === []) {
    echo "No published pages to audit yet (use --all to include drafts).\n";
    exit(0);
}

$failures = 0;
foreach ($urls as $path) {
    $url = $base . $path;
    $ch = curl_init($url);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => false, CURLOPT_TIMEOUT => 20, CURLOPT_USERAGENT => 'MySolutionz-SEO-Audit/1.0']);
    $html = (string) curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

    $issues = [];
    if ($status !== 200) {
        $issues[] = "HTTP {$status}";
    }
    if ($html !== '') {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();
        $xp = new DOMXPath($dom);

        $h1 = $xp->query('//h1')->length;
        if ($h1 !== 1) {
            $issues[] = "{$h1} <h1> elements (expected 1)";
        }
        $checks = [
            'title'          => '//head/title[normalize-space()]',
            'description'    => '//head/meta[@name="description"][@content!=""]',
            'canonical'      => '//head/link[@rel="canonical"][@href!=""]',
            'og:image'       => '//head/meta[@property="og:image"][@content!=""]',
            'og:image:alt'   => '//head/meta[@property="og:image:alt"][@content!=""]',
            'twitter:card'   => '//head/meta[@name="twitter:card"][@content!=""]',
        ];
        foreach ($checks as $label => $query) {
            if ($xp->query($query)->length === 0) {
                $issues[] = "missing {$label}";
            }
        }
        foreach ($xp->query('//script[@type="application/ld+json"]') as $script) {
            json_decode($script->textContent, true, 512);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $issues[] = 'JSON-LD does not parse: ' . json_last_error_msg();
            }
        }
        if ($xp->query('//script[@type="application/ld+json"]')->length === 0) {
            $issues[] = 'no JSON-LD';
        }
        foreach ($xp->query('//img') as $img) {
            $src = $img->getAttribute('src') ?: '(no src)';
            foreach (['alt', 'width', 'height'] as $attr) {
                if (!$img->hasAttribute($attr)) {
                    $issues[] = "<img {$src}> missing {$attr}";
                }
            }
        }
        $dummy = $xp->query('//a[@href="#"]')->length;
        if ($dummy > 0) {
            $issues[] = "{$dummy} dummy href=\"#\" link(s)";
        }
    }

    if ($issues === []) {
        echo "PASS  {$path}\n";
    } else {
        $failures++;
        echo "FAIL  {$path}\n";
        foreach ($issues as $issue) {
            echo "      - {$issue}\n";
        }
    }
}

printf("\n%d page(s) audited, %d failing.\n", count($urls), $failures);
exit($failures > 0 ? 1 : 0);
