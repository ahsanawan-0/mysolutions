<?php
declare(strict_types=1);

/**
 * Services: 3 categories, 12 sub-services (docs/00 §K, docs/09 §4). Single source of truth for
 * routes, navigation, llms.txt, schema and the service pages. P04 extends each entry
 * (descriptions, mega-menu lines); P20/P21 add the full page content.
 *
 * Keys: slug, name, icon (Phosphor light), colour (category token), summary (// PROVISIONAL until final copy), subs[].
 */
return [
    [
        'slug'    => 'bid-proposal-management',
        'name'    => 'Bid & Proposal Management',
        'icon'    => 'kanban',
        'colour'  => 'bid',
        'summary' => 'End-to-end support to find, qualify and win public-sector and private contracts: tender and bid writing, opportunity identification and independent expert review.', // PROVISIONAL
        'subs'    => [
            ['slug' => 'tender-bid-writing',         'name' => 'Tender & Bid Writing',        'icon' => 'pen-nib',     'summary' => 'Evidence-led tender responses, quality questions and framework applications written to what evaluators score.'], // PROVISIONAL
            ['slug' => 'opportunity-identification', 'name' => 'Opportunity Identification',  'icon' => 'binoculars',  'summary' => 'Tender search, alerts and bid/no-bid assessment so you only pursue contracts you can win.'], // PROVISIONAL
            ['slug' => 'expert-review',              'name' => 'Expert Review',               'icon' => 'list-checks', 'summary' => 'Independent review and scoring of your draft bid before submission, with clear improvements.'], // PROVISIONAL
        ],
    ],
    [
        'slug'    => 'compliance-virtual-assistance',
        'name'    => 'Compliance & Virtual Assistance',
        'icon'    => 'seal-check',
        'colour'  => 'compliance',
        'summary' => 'Compliance and back-office support for regulated providers: CQC registration, policy management, carbon reduction plans, data management and care plans.', // PROVISIONAL
        'subs'    => [
            ['slug' => 'cqc-registration',      'name' => 'CQC Registration',          'icon' => 'shield-check',   'summary' => 'Support with CQC provider and registered manager applications, statements of purpose and interview preparation.'], // PROVISIONAL
            ['slug' => 'policy-management',     'name' => 'Policy Management',         'icon' => 'files',          'summary' => 'Policies and procedures written, reviewed and kept current with UK regulation.'], // PROVISIONAL
            ['slug' => 'carbon-reduction-plan', 'name' => 'Carbon Reduction Plan',     'icon' => 'leaf',           'summary' => 'PPN 006-compliant carbon reduction plans for public-sector tenders.'], // PROVISIONAL
            ['slug' => 'data-entry-management', 'name' => 'Data Entry & Management',   'icon' => 'database',       'summary' => 'Accurate, confidential data entry and ongoing data management by a UK virtual assistant.'], // PROVISIONAL
            ['slug' => 'care-plans',            'name' => 'Care Plans',                'icon' => 'clipboard-text', 'summary' => 'Person-centred care plans and templates that meet regulatory and commissioner expectations.'], // PROVISIONAL
        ],
    ],
    [
        'slug'    => 'digital-presence',
        'name'    => 'Digital Presence',
        'icon'    => 'devices',
        'colour'  => 'digital',
        'summary' => 'A credible digital presence that supports your bids and wins direct work: website development, social media management, email marketing, SEO and analytics.', // PROVISIONAL
        'subs'    => [
            ['slug' => 'website-development',     'name' => 'Website Development',     'icon' => 'browser',       'summary' => 'Fast, accessible websites for SMEs, built to convert and to support procurement due diligence.'], // PROVISIONAL
            ['slug' => 'social-media-management', 'name' => 'Social Media Management', 'icon' => 'share-network', 'summary' => 'Planned, on-brand social media activity that builds credibility with buyers and clients.'], // PROVISIONAL
            ['slug' => 'email-marketing',         'name' => 'Email Marketing',         'icon' => 'envelope-open', 'summary' => 'PECR-compliant email campaigns and newsletters that keep clients and prospects engaged.'], // PROVISIONAL
            ['slug' => 'seo-analytics',           'name' => 'SEO & Analytics',         'icon' => 'chart-line-up', 'summary' => 'Search visibility and clear reporting so you know what your website is delivering.'], // PROVISIONAL
        ],
    ],
];
