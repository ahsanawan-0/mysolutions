<?php
declare(strict_types=1);

/**
 * Sector page /sectors/healthcare-social-care/ (PROMPTS P22, docs/08 §8). DRAFT until the client's sector copy arrives (content-fill C04).
 * The sector itself (name, icon, summary, photo, URL) is app/data/sectors.php; the hero lead is its summary.
 * `how_we_help` is the client-approved Our Story text (about-us-content.md §2); everything else is a [[PENDING: …]]
 * marker (a dashed "Content pending" block off production, omitted on production) or // PROVISIONAL.
 * Keys: status, seo, hero, challenges (icon, title, text), how_we_help, services (sub-service slugs from services.php),
 * buyers (typical buyers / contract types), faq_tag, cta. bin/check-content.php flags sector pages whose body text is
 * more than 50 % identical to another sector page (docs/10 §3, no doorway pages).
 */
return [
    'status' => 'draft',

    'seo' => [
        'title'       => 'Health & Social Care Tender Writing | My Solutionz', // PROVISIONAL
        'description' => '[[PENDING: meta description (140–160 characters) from the client copy]]',
    ],

    'hero' => [
        'eyebrow' => 'Sectors We Serve',
        'accent'  => 'Tender Writing',
        'images'  => ['home/hero-building', 'home/tender-consultancy-meeting'],   // narrow columns beside the sector photo
        'labels'  => [ // PROVISIONAL
            ['text' => 'CQC Compliance', 'icon' => 'shield-check'],
            ['text' => 'Care Plans', 'icon' => 'clipboard-text'],
            ['text' => 'Tender Writing', 'icon' => 'pen-nib']
        ],
    ],

    'challenges' => '[[PENDING: 3–4 sector challenges (icon + title + text): regulation (CQC), workforce, mobilisation and performance in care contracts]]',

    'how_we_help' => [
        'heading' => 'How We',
        'accent'  => 'Help',
        'paras'   => [
            'Each sector presents different challenges, from regulatory compliance and workforce planning to service delivery, contract mobilisation and performance management. Understanding these differences is central to how we approach our work.', // client-approved (about-us-content.md §2)
            'Our experience in healthcare and social care has given us particularly strong insight into complex, highly regulated procurement environments.', // client-approved (about-us-content.md §2)
            'NHS healthcare services in England are commissioned under the Provider Selection Regime (PSR), which sets its own rules for choosing providers alongside the Procurement Act 2023.', // PROVISIONAL (fact to confirm with the client)
        ],
    ],

    // Sub-service slugs (app/data/services.php) shown as cards under "How we help"
    'services' => ['tender-bid-writing', 'cqc-registration', 'policy-management', 'care-plans'], // PROVISIONAL

    'buyers' => '[[PENDING: typical buyers and contract types, to confirm with the client (e.g. local authorities, integrated care boards (ICBs), NHS trusts; domiciliary care, supported living, residential care frameworks)]]',

    'faq_tag' => 'healthcare-social-care',
    'cta'     => [],   // the default CTA band copy, prefilled with ?sector=healthcare-social-care
];
