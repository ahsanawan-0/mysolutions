<?php
declare(strict_types=1);

/**
 * Compliance & Virtual Assistance, category landing page /services/compliance-virtual-assistance/ (PROMPTS P20).
 * DRAFT: the client's copy for this category has not arrived yet. The blocks follow the client's Category 1
 * structure (docs/website_content/services/01-bid-proposal-management.md), which is the shape we expect the copy in;
 * every block that still needs copy holds a [[PENDING: …]] marker and renders as a dashed "Content pending" block off
 * production (omitted on production). The hero lead is the category summary and the sub-service cards come from
 * app/data/services.php. See the Bid & Proposal file for the block format.
 */
$category = service_category('compliance-virtual-assistance');

return [
    'status' => 'draft',

    'seo' => [
        'title'          => 'Compliance & Virtual Assistance Services UK | My Solutionz',
        'description'    => '[[PENDING: meta description (140–160 characters) from the client copy]]',
        'og_title'       => 'Compliance & Virtual Assistance Services',
        'og_description' => $category['summary'],
        'service_type'   => 'Compliance and virtual assistance',
    ],

    'hero' => [
        'eyebrow' => 'Our Services',
        'title'   => 'Compliance &',
        'accent'  => 'Virtual Assistance',
        'lead'    => $category['summary'],
        'ctas'    => [
            ['Get a Free Quote', '/contact-us/?service=compliance-virtual-assistance#quote'],
            ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone'],
        ],
        'trust'   => 'Over 10 years of business development experience · CQC registration, policies and care plans · Healthcare, transport, cleaning, security, education and facilities management',
        'icon'    => 'seal-check',
        'images'  => [
            ['key' => 'sectors/healthcare-social-care-consultation', 'alt' => 'Care coordinator reviewing a folder with an older resident'],
            ['key' => 'home/hero-building', 'alt' => '', 'focus' => 'right'],
            ['key' => 'home/tender-consultancy-meeting', 'alt' => ''],
        ],
        'labels'  => [
            ['text' => 'CQC Registration', 'icon' => 'shield-check'],
            ['text' => 'Policies', 'icon' => 'files'],
            ['text' => 'Care Plans', 'icon' => 'clipboard-text'],
        ],
    ],

    'sections' => [
        ['type' => 'intro', 'paras' => ['[[PENDING: 2 intro paragraphs continuing the hero (who the category helps and how)]]']],
        [
            'type'    => 'text',
            'id'      => 'what-is-compliance-virtual-assistance',
            'heading' => 'What Is',
            'accent'  => 'Compliance & Virtual Assistance?',
            'paras'   => ['[[PENDING: "What is …?" direct-answer section: a quotable first-paragraph definition + 3–5 paragraphs]]'],
            'variant' => 'default',
        ],
        [
            'type'    => 'steps',
            'id'      => 'how-it-works',
            'heading' => 'How We',
            'accent'  => 'Support You',
            'paras'   => ['[[PENDING: process intro paragraphs]]'],
            'steps'   => '[[PENDING: 5–6 process steps (title + one line each)]]',
        ],
        [
            'type'    => 'subs',
            'id'      => 'our-services',
            'heading' => 'Our Compliance &',                   // PROVISIONAL
            'accent'  => 'Virtual Assistance Services',
            'intro'   => 'Choose the support you need, from a single policy update to ongoing compliance and administration.', // PROVISIONAL
        ],
        [
            'type'    => 'text',
            'id'      => 'why-choose',
            'heading' => 'Why Choose',
            'accent'  => 'Professional Support?',
            'paras'   => ['[[PENDING: "Why choose …" section (paragraphs on the benefits for the business)]]'],
            'button'  => ['label' => 'More About Our Approach', 'url' => '/about-us/#our-approach'],
            'variant' => 'mist',
        ],
        [
            'type'    => 'text',
            'id'      => 'current-standards',
            'heading' => 'Current Standards and',
            'accent'  => 'Regulation',
            'paras'   => ['[[PENDING: current standards / regulation band (e.g. CQC registration and the fundamental standards, UK GDPR for records), with the date the content was checked]]'],
            'variant' => 'band',
        ],
    ],

    'faq_tag' => 'compliance-virtual-assistance',
    'faq'     => [
        'eyebrow' => 'FAQs',
        'heading' => 'Compliance & Virtual Assistance',
        'accent'  => 'FAQs',
        'text'    => 'Straight answers to the questions businesses ask most about compliance and virtual assistance.', // PROVISIONAL
    ],

    'cta' => [],   // the default CTA band copy until the client's closing copy arrives
];
