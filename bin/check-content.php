#!/usr/bin/env php
<?php
declare(strict_types=1);

/**
 * Content checker (launch gate: zero results).
 *   php bin/check-content.php            report
 *   php bin/check-content.php --strict   exit 1 when anything is found
 *
 * Reports: [[PENDING, // PROVISIONAL and status => 'draft' markers; every route's (and, from P26, every Insights
 * article's) title/description lengths and duplicates; img() keys missing from the image manifest; pages without
 * breadcrumbs.
 */

$_SERVER['REQUEST_URI'] = '/';
require dirname(__DIR__) . '/app/bootstrap.php';

use App\Page;
use App\Seo;

$strict = in_array('--strict', $argv, true);
$found = 0;

$line = static fn (string $s = ''): string => $s . PHP_EOL;
$heading = static function (string $title): void {
    echo PHP_EOL, '== ', $title, ' ', str_repeat('=', max(0, 70 - strlen($title))), PHP_EOL;
};

// ---------- 1. Markers ----------------------------------------------------------------------------
$heading('Markers: [[PENDING · // PROVISIONAL · status => draft');
$scanDirs = ['app/config', 'app/data', 'templates', 'content', 'src'];   // content and templates, not the engine in app/lib
$exts = ['php', 'md', 'css', 'js', 'mjs', 'json'];
$patterns = [
    'PENDING'     => '/\[\[PENDING/',
    'PROVISIONAL' => '#//\s*PROVISIONAL#',
    'DRAFT'       => '/[\'"]status[\'"]\s*=>\s*[\'"]draft[\'"]|^status:\s*draft\s*$/m',
];
$markers = 0;
foreach ($scanDirs as $dir) {
    $root = base_path($dir);
    if (!is_dir($root)) {
        continue;
    }
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        if (!in_array(strtolower($file->getExtension()), $exts, true) || basename((string) $file) === 'check-content.php') {
            continue;
        }
        $rel = str_replace('\\', '/', substr((string) $file, strlen(BASE_PATH) + 1));
        foreach (file((string) $file) ?: [] as $i => $text) {
            if (preg_match('#^\s*(\*|/\*)#', $text) === 1) {
                continue;   // doc-block comments describe the markers; they aren't content
            }
            foreach ($patterns as $label => $regex) {
                if (preg_match($regex, $text) === 1) {
                    printf("  %-11s %s:%d  %s\n", $label, $rel, $i + 1, trim(mb_strimwidth(trim($text), 0, 90, '…')));
                    $markers++;
                }
            }
        }
    }
}
echo $markers === 0 ? "  none\n" : "  {$markers} marker(s)\n";
$found += $markers;

// ---------- 2. Routes: titles and descriptions ---------------------------------------------------
$heading('Routes: title / description lengths');
$routes = require base_path('app/routes.php');
$titles = [];
$descriptions = [];
$probed = [];
$routeIssues = 0;
// Insights articles (P26) share the /insights/{slug}/ route: probe each post through it
$checks = $routes;
foreach (App\Blog::all() as $post) {
    $checks[$post['url']] = ['template' => 'pages/insights/single', 'params' => ['slug' => $post['slug']], 'built_by' => 'P26'];
}
foreach ($checks as $path => $route) {
    if (isset($route['controller']) || str_contains($path, '{')) {
        continue;
    }
    $page = Page::probe($route['template'], $route['params'] ?? [], $path);
    if ($page === null) {
        printf("  %-55s no template yet (built by %s)\n", $path, $route['built_by'] ?? '?');
        $routeIssues++;
        continue;
    }
    $probed[$path] = $page;
    $problems = Seo::validate($page);
    $t = mb_strlen($page['title']);
    $d = mb_strlen($page['description']);
    $flag = $problems === [] ? 'ok ' : '!! ';
    printf("  %s%-52s %-9s title %2d  desc %3d%s\n", $flag, $path, $page['status'], $t, $d, $problems ? '  ' . implode('; ', $problems) : '');
    if ($problems !== []) {
        $routeIssues++;
    }
    if ($page['canonical'] !== false) {
        $titles[$page['title']][] = $path;
        $descriptions[$page['description']][] = $path;
    }
}
foreach (['title' => $titles, 'description' => $descriptions] as $kind => $map) {
    foreach ($map as $value => $paths) {
        if (count($paths) > 1 && $value !== '') {
            printf("  !! duplicate %s on %s: \"%s\"\n", $kind, implode(', ', $paths), mb_strimwidth($value, 0, 60, '…'));
            $routeIssues++;
        }
    }
}
$found += $routeIssues;

// ---------- 2b. Sector pages: no doorway pages (docs/10 §3, P22) ----------------------------------
// Each sector page's body (the template output, without the shared CTA band, the "other sectors" row and pending
// placeholders) is cut into 5-word shingles; a pair sharing more than 50 % of the smaller page's shingles is flagged.
$heading('Sector pages: body text more than 50 % identical to another sector page');
$bodies = [];
foreach ($routes as $path => $route) {
    if (($route['template'] ?? '') !== 'pages/sectors/single') {
        continue;
    }
    $params = $route['params'] ?? [];
    ob_start();
    try {
        (static function () use ($route, $params): void {
            $page = [];
            include templates_path($route['template']);
        })();
    } catch (Throwable) {
        // a broken template is reported by the routes section
    }
    $html = (string) ob_get_clean();
    $html = preg_replace('#<section class="cta-band.*?</section>|<nav class="sector-others.*?</nav>|<section class="svc-pending.*?</section>|<script\b.*?</script>|<svg\b.*?</svg>#s', ' ', $html) ?? $html;
    $words = preg_split('/\W+/u', mb_strtolower(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5)), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $shingles = [];
    for ($i = 0, $n = count($words) - 4; $i < $n; $i++) {
        $shingles[implode(' ', array_slice($words, $i, 5))] = true;
    }
    $bodies[$path] = $shingles;
}
$doorways = 0;
$paths = array_keys($bodies);
foreach ($paths as $a => $pathA) {
    foreach (array_slice($paths, $a + 1) as $pathB) {
        $smaller = min(count($bodies[$pathA]), count($bodies[$pathB]));
        if ($smaller === 0) {
            continue;
        }
        $share = count(array_intersect_key($bodies[$pathA], $bodies[$pathB])) / $smaller;
        if ($share > 0.5) {
            printf("  !! %-36s ≈ %-36s %3d %% shared\n", $pathA, $pathB, (int) round($share * 100));
            $doorways++;
        }
    }
}
echo $doorways === 0 ? "  none\n" : "  {$doorways} near-duplicate pair(s) → each sector page needs its own challenges, buyers and copy (C04)\n";
$found += $doorways;

// ---------- 3. Images missing from the manifest --------------------------------------------------
$heading('Images: img() keys missing from public/assets/img/manifest.json');
$manifestFile = public_path('assets/img/manifest.json');
$manifest = is_file($manifestFile) ? (json_decode((string) file_get_contents($manifestFile), true) ?: []) : [];
$missing = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path('templates'), FilesystemIterator::SKIP_DOTS));
foreach ($it as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }
    $rel = str_replace('\\', '/', substr((string) $file, strlen(BASE_PATH) + 1));
    if (preg_match_all('/\bimg\(\s*[\'"]([^\'"]+)[\'"]/', (string) file_get_contents((string) $file), $mm)) {
        foreach ($mm[1] as $key) {
            if (!isset($manifest[$key])) {
                $missing[$key][] = $rel;
            }
        }
    }
}
// Also keys referenced from data files (incl. app/data/service-pages/, P20): 'image' / 'detail_image' => 'key' and
// image arrays ['key' => 'home/…', 'alt' => …]
$dataFiles = array_merge(glob(base_path('app/data/*.php')) ?: [], glob(base_path('app/data/*/*.php')) ?: []);
foreach ($dataFiles as $dataFile) {
    if (preg_match_all('/[\'"](?:image|detail_image|key)[\'"]\s*=>\s*[\'"]([a-z0-9-]+\/[a-z0-9\/-]+)[\'"]/', (string) file_get_contents($dataFile), $mm)) {
        foreach ($mm[1] as $key) {
            if (!isset($manifest[$key])) {
                $missing[$key][] = str_replace('\\', '/', substr($dataFile, strlen(BASE_PATH) + 1));
            }
        }
    }
}
foreach ($missing as $key => $files) {
    printf("  %-40s %s\n", $key, implode(', ', array_unique($files)));
}
echo $missing === [] ? "  none\n" : '  ' . count($missing) . " image key(s) → add the source to assets-src/images/ and run `npm run images`\n";
$found += count($missing);

// ---------- 4. Breadcrumbs ----------------------------------------------------------------------
$heading('Pages without breadcrumbs (except home)');
$noCrumbs = 0;
foreach ($probed as $path => $page) {
    if ($path !== '/' && $page['canonical'] !== false && $page['breadcrumbs'] === []) {
        echo "  {$path}\n";
        $noCrumbs++;
    }
}
echo $noCrumbs === 0 ? "  none\n" : '';
$found += $noCrumbs;

// ---------- Summary ----------------------------------------------------------------------------
$heading('Summary');
printf("  %d finding(s): %d marker(s), %d route issue(s), %d missing image(s), %d page(s) without breadcrumbs\n",
    $found, $markers, $routeIssues, count($missing), $noCrumbs);
echo $found === 0 ? "  Launch gate: PASS\n" : "  Launch gate: not yet (zero findings required)\n";

exit($strict && $found > 0 ? 1 : 0);
