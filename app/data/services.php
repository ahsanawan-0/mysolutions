<?php
declare(strict_types=1);

/**
 * Services: 3 categories, 12 sub-services (docs/00 §K, docs/09 §4, PROMPTS P04). Single source of truth for
 * routes, navigation, mega menu, llms.txt, schema and the service pages. Access via services(),
 * service_category($slug), service_sub($cat, $sub) (app/lib/helpers.php). P20/P21 add the full page content.
 *
 * Category keys: slug, name, short, colour (bid|compliance|digital), icon (Phosphor light), summary, status, url, subs[].
 * Sub keys: slug, name, icon, summary, status, url. Summaries marked // PROVISIONAL change when the content prompt runs.
 */
$categories = [
    [
        'slug'    => 'bid-proposal-management',
        'name'    => 'Bid & Proposal Management',
        'short'   => 'Bid & Proposals',
        'colour'  => 'bid',
        'icon'    => 'kanban',
        'summary' => 'From finding the right contracts to final submission, we manage your tenders so your team can focus on running the business.',
        'status'  => 'published',
        'subs'    => [
            // Summaries condensed from the client's page taglines (docs/website_content/services/01a–01c, 15 Oct 2026); P21 publishes the pages
            ['slug' => 'tender-bid-writing',         'name' => 'Tender & Bid Writing',       'icon' => 'pen-nib',     'summary' => 'Tender writing that gives you a competitive advantage.', 'status' => 'draft'], // PROVISIONAL
            ['slug' => 'opportunity-identification', 'name' => 'Opportunity Identification', 'icon' => 'binoculars',  'summary' => 'Find the right opportunities for your business.',        'status' => 'draft'], // PROVISIONAL
            ['slug' => 'expert-review',              'name' => 'Expert Review',              'icon' => 'list-checks', 'summary' => 'Expert eyes and a stronger bid before you submit.',      'status' => 'draft'], // PROVISIONAL
        ],
    ],
    [
        'slug'    => 'compliance-virtual-assistance',
        'name'    => 'Compliance & Virtual Assistance',
        'short'   => 'Compliance & VA',
        'colour'  => 'compliance',
        'icon'    => 'seal-check',
        'summary' => 'Practical compliance and administrative support, from CQC registration and policies to care plans and data management.', // PROVISIONAL
        'status'  => 'draft',
        'subs'    => [
            ['slug' => 'cqc-registration',      'name' => 'CQC Registration',        'icon' => 'shield-check',   'summary' => 'Guidance and documents for a smooth registration.', 'status' => 'draft'], // PROVISIONAL
            ['slug' => 'policy-management',     'name' => 'Policy Management',       'icon' => 'files',          'summary' => 'Clear, current policies that stand up to scrutiny.', 'status' => 'draft'], // PROVISIONAL
            ['slug' => 'carbon-reduction-plan', 'name' => 'Carbon Reduction Plan',   'icon' => 'leaf',           'summary' => 'Carbon Reduction Plans for public sector bids.',     'status' => 'draft'], // PROVISIONAL
            ['slug' => 'data-entry-management', 'name' => 'Data Entry & Management', 'icon' => 'database',       'summary' => 'Accurate records, organised and up to date.',        'status' => 'draft'], // PROVISIONAL
            ['slug' => 'care-plans',            'name' => 'Care Plans',              'icon' => 'clipboard-text', 'summary' => 'Person-centred care plans, professionally written.', 'status' => 'draft'], // PROVISIONAL
        ],
    ],
    [
        'slug'    => 'digital-presence',
        'name'    => 'Digital Presence',
        'short'   => 'Digital Presence',
        'colour'  => 'digital',
        'icon'    => 'devices',
        'summary' => 'Websites, social media, email marketing and search that help buyers and customers find and trust your business.', // PROVISIONAL
        'status'  => 'draft',
        'subs'    => [
            ['slug' => 'website-development',     'name' => 'Website Development',     'icon' => 'browser',       'summary' => 'Fast, modern websites that build trust.',        'status' => 'draft'], // PROVISIONAL
            ['slug' => 'social-media-management', 'name' => 'Social Media Management', 'icon' => 'share-network', 'summary' => 'A consistent, professional social presence.',    'status' => 'draft'], // PROVISIONAL
            ['slug' => 'email-marketing',         'name' => 'Email Marketing',         'icon' => 'envelope-open', 'summary' => 'Campaigns that keep you front of mind.',          'status' => 'draft'], // PROVISIONAL
            ['slug' => 'seo-analytics',           'name' => 'SEO & Analytics',         'icon' => 'chart-line-up', 'summary' => 'Be found online and measure what matters.',       'status' => 'draft'], // PROVISIONAL
        ],
    ],
];

// Derived URLs (never typed by hand elsewhere)
foreach ($categories as &$category) {
    $category['url'] = '/services/' . $category['slug'] . '/';
    foreach ($category['subs'] as &$sub) {
        $sub['url'] = $category['url'] . $sub['slug'] . '/';
        $sub['category'] = $category['slug'];
    }
    unset($sub);
}
unset($category);

return $categories;
