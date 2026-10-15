<?php
declare(strict_types=1);

/**
 * Digital Presence, category landing page /services/digital-presence/ (PROMPTS P20).
 * DRAFT: the client's copy for this category has not arrived yet. The blocks follow the client's Category 1
 * structure (docs/website_content/services/01-bid-proposal-management.md), which is the shape we expect the copy in;
 * every block that still needs copy holds a [[PENDING: …]] marker and renders as a dashed "Content pending" block off
 * production (omitted on production). The hero lead is the category summary and the sub-service cards come from
 * app/data/services.php. See the Bid & Proposal file for the block format.
 */
$category = service_category('digital-presence');

return [
    'status' => 'draft',

    'seo' => [
        'title'          => 'Digital Presence Services for UK Businesses | My Solutionz',
        'description'    => '[[PENDING: meta description (140–160 characters) from the client copy]]',
        'og_title'       => 'Digital Presence Services',
        'og_description' => $category['summary'],
        'service_type'   => 'Digital marketing',
    ],

    'hero' => [
        'eyebrow' => 'Our Services',
        'title'   => 'Digital',
        'accent'  => 'Presence',
        'lead'    => $category['summary'],
        'ctas'    => [
            ['Get a Free Quote', '/contact-us/?service=digital-presence#quote'],
            ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone'],
        ],
        'trust'   => 'Over 10 years of business development experience · Websites, social media, email marketing and SEO · Support for UK businesses',
        'icon'    => 'devices',
        'images'  => [
            ['key' => 'services/digital-presence-web-designer', 'alt' => 'Designer reviewing a business website on a laptop and a phone'],
            ['key' => 'home/hero-building', 'alt' => '', 'focus' => 'right'],
            ['key' => 'home/tender-consultancy-meeting', 'alt' => ''],
        ],
        'labels'  => [
            ['text' => 'Websites', 'icon' => 'browser'],
            ['text' => 'Social Media', 'icon' => 'share-network'],
            ['text' => 'SEO & Analytics', 'icon' => 'chart-line-up'],
        ],
    ],

    'sections' => [
        ['type' => 'intro', 'paras' => ['[[PENDING: 2 intro paragraphs continuing the hero (who the category helps and how)]]']],
        [
            'type'    => 'text',
            'id'      => 'what-is-digital-presence',
            'heading' => 'What Is',
            'accent'  => 'Digital Presence?',
            'paras'   => ['[[PENDING: "What is …?" direct-answer section: a quotable first-paragraph definition + 3–5 paragraphs]]'],
            'variant' => 'default',
        ],
        [
            'type'    => 'steps',
            'id'      => 'how-it-works',
            'heading' => 'How We',
            'accent'  => 'Build Your Presence',
            'paras'   => ['[[PENDING: process intro paragraphs]]'],
            'steps'   => '[[PENDING: 5–6 process steps (title + one line each)]]',
        ],
        [
            'type'    => 'subs',
            'id'      => 'our-services',
            'heading' => 'Our Digital',                        // PROVISIONAL
            'accent'  => 'Presence Services',
            'intro'   => 'Choose the support you need, from a new website to ongoing social media, email and search.', // PROVISIONAL
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
            'paras'   => ['[[PENDING: current standards band (UK GDPR and PECR for email marketing and cookies, accessibility), with the date the content was checked]]'],
            'variant' => 'band',
        ],
    ],

    'faq_tag' => 'digital-presence',
    'faq'     => [
        'eyebrow' => 'FAQs',
        'heading' => 'Digital Presence',
        'accent'  => 'FAQs',
        'text'    => 'Straight answers to the questions businesses ask most about digital presence.', // PROVISIONAL
    ],

    'cta' => [],   // the default CTA band copy until the client's closing copy arrives
];
