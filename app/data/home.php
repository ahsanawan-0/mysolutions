<?php
declare(strict_types=1);

/**
 * Home page copy (PROMPTS P08–P16), read via home('hero'), home('hero.title'). Sections are keyed hero, services,
 * approach, sectors, process, why, triage, faq, insights, cta; each section is filled by its prompt and replaced
 * by the client's final copy in C01. Everything below is // PROVISIONAL until then.
 * Links use site paths (url() is applied in the templates); phone/e-mail come from config().
 */
return [
    'hero' => [
        'badge' => 'UK Bid & Proposal Management', // PROVISIONAL
        // H1, two lines; the second line is the italic accent (two-voice heading).
        // Alternatives kept for the client: B "Compete With" / "Confidence." · C "Your Next Contract" / "Starts Here."
        'title'   => 'Tenders, Crafted',  // PROVISIONAL
        'accent'  => 'for Success.',      // PROVISIONAL
        'lead'    => 'Expert tender writing, bid management, compliance and digital support for UK businesses, from your first tender to major frameworks.', // PROVISIONAL
        'quick_start' => [
            'sector_label'  => 'Your sector',
            'sector_icon'   => 'buildings',
            'sector_any'    => 'Any sector',       // empty value: nothing pre-selected on the contact form
            'sector_other'  => 'Other',
            'service_label' => 'Support needed',
            'service_icon'  => 'kanban',
            'service_any'   => 'Any service',
            'submit'        => 'Get Started',
            'action'        => '/contact-us/#quote',
        ],
        'links' => [
            ['Explore Services', '#services'],
            ['Sectors We Serve', '#sectors'],
        ],
        // Helper text, bottom-right: {phone} and {consult} are replaced by links in the template
        'helper' => [
            'text'          => 'Give us a call on {phone} and we can set you up, or {consult}.', // PROVISIONAL: client to confirm the free consultation
            'consult_label' => 'book a free consultation',
            'consult_url'   => '/contact-us/#quote',
        ],
        'tag' => 'Our Special Service', // PROVISIONAL: or "Our Core Services"
        // Mini card below the notch (P16 makes it the latest insight)
        'mini' => [
            'eyebrow' => 'Latest insight',
            'title'   => 'Health & Social Care Tender Vocabulary, Explained', // PROVISIONAL
            'button'  => 'Read',
            'url'     => '/insights/',
            'image'   => 'insights/health-social-care-tender-vocabulary',
            'alt'     => 'Care worker reviewing tender paperwork',
        ],
        // Sector chips below the notch (slugs from app/data/sectors.php); the full list loops as a marquee < 1024px
        'chips' => ['healthcare-social-care', 'transport', 'security', 'facilities-management'],
        'photo' => [
            'key' => 'home/hero-building',
            'alt' => 'Glass office building in the City of London at dusk',
        ],
    ],

    // [[PENDING: services (P11), approach (P10), sectors (P12), process (P13), why (P14), triage + faq (P15), insights + cta (P16)]]
    'services' => [],
    'approach' => [],
    'sectors'  => [],
    'process'  => [],
    'why'      => [],
    'triage'   => [],
    'faq'      => [],
    'insights' => [],
    'cta'      => [],
];
