<?php
declare(strict_types=1);

/**
 * Insights categories (PROMPTS P25), in display order. A post names its category by slug (`category: compliance`).
 * Each category page /insights/category/<slug>/ uses: name (chips, cards, breadcrumbs), title + accent (the two-voice
 * H1, which reads as the category name), seo_title (30–65 chars), description (70–170 chars, unique: the meta
 * description and the hero lead). Optional `quote`: the contact form pre-fill for the CTAs on the category's articles
 * (P26; ['sector' => slug] or ['service' => slug], see quote_url()).
 */
return [
    'tender-writing-tips' => [
        'name'        => 'Tender Writing Tips',
        'title'       => 'Tender Writing',
        'accent'      => 'Tips',
        'seo_title'   => 'Tender Writing Tips and Bid Advice | My Solutionz',
        'description' => 'Practical tender writing tips: reading the specification, answering quality questions, evidencing social value and building a stronger bid.', // PROVISIONAL
    ],
    'procurement-updates' => [
        'name'        => 'Procurement Updates',
        'title'       => 'Procurement',
        'accent'      => 'Updates',
        'seo_title'   => 'UK Procurement Updates and Changes | My Solutionz',
        'description' => 'What changes in UK public procurement mean for suppliers, from the Procurement Act 2023 to the Central Digital Platform and new notices.', // PROVISIONAL
    ],
    'health-social-care' => [
        'name'        => 'Health & Social Care',
        'title'       => 'Health & Social Care',
        'accent'      => 'Insights',
        'seo_title'   => 'Health and Social Care Tender Insights | My Solutionz',
        'description' => 'Guidance for care providers bidding for health and social care contracts: tender vocabulary, quality questions, CQC evidence and TUPE.', // PROVISIONAL
        'quote'       => ['sector' => 'healthcare-social-care'],
    ],
    'compliance' => [
        'name'        => 'Compliance',
        'title'       => 'Compliance',
        'accent'      => 'Insights',
        'seo_title'   => 'Compliance Guidance and Insights | My Solutionz',
        'description' => 'Plain-English guidance on compliance for UK businesses: policies, CQC registration, carbon reduction plans and keeping records in order.', // PROVISIONAL
    ],
    'digital' => [
        'name'        => 'Digital',
        'title'       => 'Digital',
        'accent'      => 'Insights',
        'seo_title'   => 'Digital Presence Insights | My Solutionz',
        'description' => 'Ideas for a stronger digital presence: websites that win trust, social media, email marketing and SEO for service businesses in the UK.', // PROVISIONAL
    ],
];
