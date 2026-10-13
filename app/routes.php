<?php
declare(strict_types=1);

use App\Controllers\LlmsController;
use App\Controllers\RobotsController;
use App\Controllers\SitemapController;

/**
 * URL → template/controller map (docs/08 §1). Keys are exact paths or patterns with {param}.
 * Each route: template (templates/<template>.php) OR controller ([class, method]), built_by (the prompt
 * that builds it), optional params, where (param constraints), local_only, sitemap (pages|services|sectors|insights).
 * Until a template exists, the Router serves a dev stub on local and a 404 elsewhere.
 */

$services = services();
$sectors = sectors();

$routes = [
    '/'          => ['template' => 'pages/home',  'built_by' => 'P08–P16', 'sitemap' => 'pages'],
    '/about-us/' => ['template' => 'pages/about', 'built_by' => 'P18–P19', 'sitemap' => 'pages'],
    '/services/' => ['template' => 'pages/services/index', 'built_by' => 'P20', 'sitemap' => 'services'],
];

// 3 category landing pages + 12 sub-service pages (exact paths, generated from the locked structure)
foreach ($services as $category) {
    $routes["/services/{$category['slug']}/"] = [
        'template' => 'pages/services/category',
        'built_by' => 'P20',
        'params'   => ['category' => $category['slug']],
        'sitemap'  => 'services',
    ];
    foreach ($category['subs'] as $sub) {
        $routes["/services/{$category['slug']}/{$sub['slug']}/"] = [
            'template' => 'pages/services/single',
            'built_by' => 'P21',
            'params'   => ['category' => $category['slug'], 'sub' => $sub['slug']],
            'sitemap'  => 'services',
        ];
    }
}

$routes['/sectors/'] = ['template' => 'pages/sectors/index', 'built_by' => 'P22', 'sitemap' => 'sectors'];
foreach ($sectors as $sector) {
    $routes["/sectors/{$sector['slug']}/"] = [
        'template' => 'pages/sectors/single',
        'built_by' => 'P22',
        'params'   => ['slug' => $sector['slug']],
        'sitemap'  => 'sectors',
    ];
}

$routes += [
    '/insights/'                  => ['template' => 'pages/insights/index',    'built_by' => 'P25', 'sitemap' => 'insights'],
    '/insights/page/{n}/'         => ['template' => 'pages/insights/index',    'built_by' => 'P25', 'where' => ['n' => '/^[1-9][0-9]*$/']],
    '/insights/category/{slug}/'  => ['template' => 'pages/insights/category', 'built_by' => 'P25'],
    '/insights/feed.xml'          => ['template' => 'feeds/rss',               'built_by' => 'P25'],
    '/insights/{slug}/'           => ['template' => 'pages/insights/single',   'built_by' => 'P26'],

    '/faq/'        => ['template' => 'pages/faq',       'built_by' => 'P27', 'sitemap' => 'pages'],
    '/contact-us/' => ['template' => 'pages/contact',   'built_by' => 'P23', 'sitemap' => 'pages'],
    '/thank-you/'  => ['template' => 'pages/thank-you', 'built_by' => 'P24'],   // noindex: never in the sitemap

    '/privacy-policy/'       => ['template' => 'pages/legal/privacy-policy',       'built_by' => 'P28', 'sitemap' => 'pages'],
    '/cookie-policy/'        => ['template' => 'pages/legal/cookie-policy',        'built_by' => 'P28', 'sitemap' => 'pages'],
    '/terms-and-conditions/' => ['template' => 'pages/legal/terms-and-conditions', 'built_by' => 'P28', 'sitemap' => 'pages'],
    '/accessibility/'        => ['template' => 'pages/legal/accessibility',        'built_by' => 'P28', 'sitemap' => 'pages'],

    '/sitemap/'    => ['template' => 'pages/sitemap', 'built_by' => 'P29', 'sitemap' => 'pages'],

    // Machine files (P03 controllers)
    '/sitemap.xml'          => ['controller' => [SitemapController::class, 'index'],    'built_by' => 'P03'],
    '/sitemap-pages.xml'    => ['controller' => [SitemapController::class, 'pages'],    'built_by' => 'P03'],
    '/sitemap-services.xml' => ['controller' => [SitemapController::class, 'services'], 'built_by' => 'P03'],
    '/sitemap-sectors.xml'  => ['controller' => [SitemapController::class, 'sectors'],  'built_by' => 'P03'],
    '/sitemap-insights.xml' => ['controller' => [SitemapController::class, 'insights'], 'built_by' => 'P03'],
    '/legacy-sitemap.xml'   => ['controller' => [SitemapController::class, 'legacy'],   'built_by' => 'P03'],
    '/robots.txt'           => ['controller' => [RobotsController::class, 'show'],      'built_by' => 'P03'],
    '/llms.txt'             => ['controller' => [LlmsController::class, 'show'],        'built_by' => 'P03'],

    // Local-only developer pages
    '/styleguide/'        => ['template' => 'pages/styleguide/index',  'built_by' => 'P02', 'local_only' => true],
    '/styleguide/motion/' => ['template' => 'pages/styleguide/motion', 'built_by' => 'P06', 'local_only' => true],
];

// Any category/sub-service/sector slug not listed above falls through to 404 on purpose (no doorway pages).

return $routes;
