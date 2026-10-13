<?php
declare(strict_types=1);

/**
 * URL → template map (docs/08 §1). Keys are exact paths or patterns with {param}.
 * Each route: template (templates/<template>.php), built_by (the prompt that builds it),
 * optional params, where (param constraints), local_only.
 * Until a template exists, the Router serves a dev stub on local and a 404 elsewhere.
 */

$categories = [
    'bid-proposal-management'       => ['tender-bid-writing', 'opportunity-identification', 'expert-review'],
    'compliance-virtual-assistance' => ['cqc-registration', 'policy-management', 'carbon-reduction-plan', 'data-entry-management', 'care-plans'],
    'digital-presence'              => ['website-development', 'social-media-management', 'email-marketing', 'seo-analytics'],
];

$sectors = [
    'healthcare-social-care', 'transport', 'cleaning', 'education-training',
    'security', 'facilities-management', 'building-maintenance',
];

$routes = [
    '/'          => ['template' => 'pages/home',  'built_by' => 'P08–P16'],
    '/about-us/' => ['template' => 'pages/about', 'built_by' => 'P18–P19'],
    '/services/' => ['template' => 'pages/services/index', 'built_by' => 'P20'],
];

// 3 category landing pages + 12 sub-service pages (exact paths, generated from the locked structure)
foreach ($categories as $category => $subs) {
    $routes["/services/{$category}/"] = [
        'template' => 'pages/services/category',
        'built_by' => 'P20',
        'params'   => ['category' => $category],
    ];
    foreach ($subs as $sub) {
        $routes["/services/{$category}/{$sub}/"] = [
            'template' => 'pages/services/single',
            'built_by' => 'P21',
            'params'   => ['category' => $category, 'sub' => $sub],
        ];
    }
}

$routes['/sectors/'] = ['template' => 'pages/sectors/index', 'built_by' => 'P22'];
foreach ($sectors as $slug) {
    $routes["/sectors/{$slug}/"] = [
        'template' => 'pages/sectors/single',
        'built_by' => 'P22',
        'params'   => ['slug' => $slug],
    ];
}

$routes += [
    '/insights/'                  => ['template' => 'pages/insights/index',    'built_by' => 'P25'],
    '/insights/page/{n}/'         => ['template' => 'pages/insights/index',    'built_by' => 'P25', 'where' => ['n' => '/^[1-9][0-9]*$/']],
    '/insights/category/{slug}/'  => ['template' => 'pages/insights/category', 'built_by' => 'P25'],
    '/insights/feed.xml'          => ['template' => 'feeds/rss',               'built_by' => 'P25'],
    '/insights/{slug}/'           => ['template' => 'pages/insights/single',   'built_by' => 'P26'],

    '/faq/'        => ['template' => 'pages/faq',       'built_by' => 'P27'],
    '/contact-us/' => ['template' => 'pages/contact',   'built_by' => 'P23'],
    '/thank-you/'  => ['template' => 'pages/thank-you', 'built_by' => 'P24'],

    '/privacy-policy/'       => ['template' => 'pages/legal/privacy-policy',       'built_by' => 'P28'],
    '/cookie-policy/'        => ['template' => 'pages/legal/cookie-policy',        'built_by' => 'P28'],
    '/terms-and-conditions/' => ['template' => 'pages/legal/terms-and-conditions', 'built_by' => 'P28'],
    '/accessibility/'        => ['template' => 'pages/legal/accessibility',        'built_by' => 'P28'],

    '/sitemap/'    => ['template' => 'pages/sitemap',       'built_by' => 'P29'],
    '/sitemap.xml' => ['template' => 'machine/sitemap-xml', 'built_by' => 'P03'],
    '/robots.txt'  => ['template' => 'machine/robots-txt',  'built_by' => 'P03'],
    '/llms.txt'    => ['template' => 'machine/llms-txt',    'built_by' => 'P03'],

    // Local-only developer pages
    '/styleguide/'        => ['template' => 'pages/styleguide/index',  'built_by' => 'P02', 'local_only' => true],
    '/styleguide/motion/' => ['template' => 'pages/styleguide/motion', 'built_by' => 'P06', 'local_only' => true],
];

// Patterns for any category/sub-service/sector slug not listed above fall through to 404 on purpose
// (no doorway pages): the explicit entries are the only valid ones.

return $routes;
