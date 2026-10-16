<?php
declare(strict_types=1);

/**
 * Sectors hub /sectors/ (PROMPTS P22, docs/08 §8): hero-inner `columns` → the 7 sector cards (C36, a static grid) →
 * "Why sector knowledge matters" (the client-approved Our Story paragraphs, about-us-content.md §2, verbatim) → CTA.
 * The sectors (name, icon, summary, photo, URL) come from app/data/sectors.php. DRAFT until the hub copy is approved;
 * copy marked // PROVISIONAL is ours.
 */
return [
    'status' => 'draft',

    'seo' => [
        'title'       => 'Sectors We Serve: Tender Writing by Sector | My Solutionz', // PROVISIONAL
        'description' => 'Tender writing and bid support for healthcare and social care, transport, cleaning, education, security, facilities management and building maintenance.', // PROVISIONAL
        'og_title'    => 'Sectors We Serve',
    ],

    'hero' => [
        'eyebrow' => 'Sectors',
        'title'   => 'Sectors We',
        'accent'  => 'Serve',
        'lead'    => 'Tender writing and bid support shaped around how each sector works, from healthcare and social care to building maintenance.', // PROVISIONAL
        'ctas'    => [
            ['Get a Free Quote', '/contact-us/#quote'],
            ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone'],
        ],
        'images'  => [
            ['key' => 'sectors/healthcare-social-care-consultation', 'alt' => 'Care coordinator reviewing a folder with an older resident'],
            ['key' => 'sectors/transport-passenger-minibus', 'alt' => '', 'focus' => 'right'],
            ['key' => 'sectors/security-office-reception', 'alt' => ''],
        ],
        'labels'  => [ // PROVISIONAL
            ['text' => 'Health & Social Care', 'icon' => 'first-aid-kit'],
            ['text' => 'Facilities Management', 'icon' => 'buildings'],
            ['text' => 'Transport', 'icon' => 'bus'],
        ],
    ],

    'cards' => [
        'eyebrow' => 'Where we work',
        'heading' => 'Seven Sectors,',            // PROVISIONAL
        'accent'  => 'One Careful Approach',
        'intro'   => 'Choose your sector to see the challenges we help with, the services that fit and the buyers you are likely to meet.', // PROVISIONAL
    ],

    // Client-approved (about-us-content.md §2 Our Story, paragraphs 4 and 5, verbatim)
    'why' => [
        'eyebrow' => 'Sector knowledge',
        'heading' => 'Why Sector Knowledge',
        'accent'  => 'Matters',
        'paras'   => [
            'Over the years, we have worked across a range of industries, including healthcare and social care, transport, cleaning, education and training, security, facilities management and building maintenance. Each sector presents different challenges, from regulatory compliance and workforce planning to service delivery, contract mobilisation and performance management. Understanding these differences is central to how we approach our work.',
            "Our experience in healthcare and social care has given us particularly strong insight into complex, highly regulated procurement environments. At the same time, our work across other sectors has reinforced the importance of understanding each client's operations, commercial objectives and the specific expectations of the contracting authority.",
        ],
        'button'  => ['label' => 'More About Our Story', 'url' => '/about-us/#our-story'],
    ],
];
