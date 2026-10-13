<?php
declare(strict_types=1);

/**
 * Legacy URL map from the old WordPress site (docs/10 §2, verified against the live
 * Yoast sitemaps on 13 October 2026). Checked by the Router BEFORE routing.
 *
 *  exact: old path → new path (one-hop 301). Paths are lowercase with trailing slash.
 *  rules: [regex, status, target]. status 301 uses target (may use $1…); status 410 has no target.
 *
 * Keep these for at least a year after launch (in practice permanently).
 */

$subService = [
    'tender-writing'          => '/services/bid-proposal-management/tender-bid-writing/',
    'tender-search'           => '/services/bid-proposal-management/opportunity-identification/',
    'tender-review'           => '/services/bid-proposal-management/expert-review/',
    'cqc-registration'        => '/services/compliance-virtual-assistance/cqc-registration/',
    'website-development'     => '/services/digital-presence/website-development/',
    'social-media-management' => '/services/digital-presence/social-media-management/',
];

$exact = [
    // Services hub
    '/our-services/' => '/services/',
    '/service/'      => '/services/',

    // Old blog post (kept and improved as an Insight) and its category
    '/whats-in-a-health-and-social-care-tender-tender-vocabulary-explained/' => '/insights/health-and-social-care-tender-vocabulary-explained/',
    '/category/health-care/' => '/insights/category/health-social-care/',

    // Feeds
    '/feed/'          => '/insights/feed.xml',
    '/comments/feed/' => '/insights/feed.xml',

    // Yoast sitemaps (names from the live sitemap index)
    '/sitemap_index.xml'            => '/sitemap.xml',
    '/page-sitemap.xml'             => '/sitemap.xml',
    '/post-sitemap.xml'             => '/sitemap.xml',
    '/pxl-template-sitemap.xml'     => '/sitemap.xml',
    '/service-sitemap.xml'          => '/sitemap.xml',
    '/category-sitemap.xml'         => '/sitemap.xml',
    '/post_tag-sitemap.xml'         => '/sitemap.xml',
    '/service-category-sitemap.xml' => '/sitemap.xml',
    '/author-sitemap.xml'           => '/sitemap.xml',
    '/sitemap.xsl'                  => '/sitemap.xml',

    // Already-redirecting aliases on the old site
    '/about/'   => '/about-us/',
    '/contact/' => '/contact-us/',

    // Duplicate of the home page
    '/page/2/' => '/',
];

// 6 old service pages + 5 old service-category archives → the matching new sub-service page
foreach ($subService as $slug => $new) {
    $exact["/service/{$slug}/"] = $new;
}
foreach (['tender-writing', 'tender-review', 'tender-search', 'website-development', 'social-media-management'] as $slug) {
    $exact["/service-category/{$slug}/"] = $subService[$slug];
}

$rules = [
    // Privacy policy PDF (upload path varies) → the HTML policy page
    ['#^/wp-content/uploads/.*privacy-poil?i?cy[^/]*\.pdf$#i', 301, '/privacy-policy/'],

    // Removed for good (410): theme templates, tags, off-topic post, empty archives, WordPress internals
    ['#^/pxl-template/#', 410, null],
    ['#^/tag/#', 410, null],
    ['#^/diabetes-prevention-and-management-#', 410, null],
    ['#^/author/developer/#', 410, null],
    ['#^/case-studies/#', 410, null],
    ['#^/careers/#', 410, null],
    ['#^/category/(uncategorized|testing-cat|post-schedules|new-testing-cat-from-zprohub)/#', 410, null],
    ['#^/attachment/#', 410, null],
    ['#^/wp-admin#', 410, null],
    ['#^/wp-login\.php#', 410, null],
    ['#^/wp-json#', 410, null],
    ['#^/xmlrpc\.php#', 410, null],
];

return ['exact' => $exact, 'rules' => $rules];
