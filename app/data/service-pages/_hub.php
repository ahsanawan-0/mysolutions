<?php
declare(strict_types=1);

/**
 * Services hub /services/ (PROMPTS P20, docs/08 §6): hero-inner `columns` → the three category panels (with the
 * two-voice intro) → "How we work" (the five approach steps from home('process.steps'), a compact strip) → sectors
 * chip marquee → FAQ (faqs.php items tagged `services`) → CTA band. The categories, sub-services and URLs come from
 * app/data/services.php. Copy marked // PROVISIONAL is ours and awaits the client's approval.
 */
return [
    'status' => 'published',

    'seo' => [
        'title'       => 'Bid, Compliance & Digital Services UK | My Solutionz',
        'description' => 'Explore My Solutionz services: bid and proposal management, compliance and virtual assistance, and digital presence support for UK businesses.',
        'og_title'    => 'Our Services',
    ],

    'hero' => [
        'eyebrow' => 'Our Services',
        'title'   => 'Our',
        'accent'  => 'Services',
        'lead'    => 'Bid and proposal management, compliance and virtual assistance, and digital presence support for UK businesses.', // PROVISIONAL
        'ctas'    => [
            ['Get a Free Quote', '/contact-us/#quote'],
            ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone'],
        ],
        'images'  => [
            ['key' => 'home/tender-consultancy-meeting', 'alt' => 'Bid manager planning a tender response with a client team'],
            ['key' => 'sectors/healthcare-social-care-consultation', 'alt' => '', 'focus' => 'left'],
            ['key' => 'services/digital-presence-web-designer', 'alt' => ''],
        ],
        'labels'  => [
            ['text' => 'Bid & Proposals', 'icon' => 'kanban'],
            ['text' => 'Compliance', 'icon' => 'seal-check'],
            ['text' => 'Digital Presence', 'icon' => 'devices'],
        ],
    ],

    'categories' => [
        'eyebrow' => 'What we do',
        'heading' => 'Three Specialist Areas,',                // PROVISIONAL
        'accent'  => 'One Dependable Partner',
        'intro'   => 'Choose the support you need, from a single tender to ongoing compliance and digital support. Each area has its own specialists and its own services.', // PROVISIONAL
        'explore' => 'Explore {name}',                          // button label; {name} = category name
        'count'   => ['one' => '{n} service', 'other' => '{n} services'],
    ],

    'how' => [
        'eyebrow' => 'How we work',
        'heading' => 'A Clear Process for',                     // PROVISIONAL
        'accent'  => 'Every Project',
        'intro'   => 'Whichever service you choose, we follow the same five steps, from understanding your business to supporting you after submission.', // PROVISIONAL
        'link'    => ['label' => 'See our full approach', 'url' => '/about-us/#our-approach'],
    ],

    'sectors' => [
        'label' => 'Sectors we support',
        'link'  => ['label' => 'All sectors', 'url' => '/sectors/'],
    ],

    'faq' => [
        'eyebrow' => 'FAQs',
        'heading' => 'Questions About',
        'accent'  => 'Our Services',
        'text'    => 'Straight answers to the questions businesses ask most before working with us.', // PROVISIONAL
        'button'  => ['label' => 'View all FAQs', 'url' => '/faq/'],
    ],
];
