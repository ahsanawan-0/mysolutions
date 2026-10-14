<?php
declare(strict_types=1);

/**
 * Home page copy (PROMPTS P08–P16), read via home('hero'), home('hero.title'). Sections are keyed hero, services,
 * approach, sectors, process, why, triage, testimonials, faq, insights, cta; each section is filled by its prompt and replaced
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

    // P14 — "Why Work With Us" bento (docs/08 §4 H7). Titles verbatim from about-us-content.md §4 (client-approved);
    // the one-liners are condensed from the same copy. Counters are derived from data in the template (never typed).
    'why' => [
        'eyebrow' => 'Why My Solutionz',
        'title'   => 'Why Work',
        'accent'  => 'With Us',
        'lead'    => 'More than tender writers. A partner in your business growth.',
        'tiles'   => [
            ['title' => 'Business Development Experience That Matters',  'icon' => 'trend-up',           'text' => 'Over 10 years of business development experience behind every bid.'],
            ['title' => 'Expertise Across Multiple Industries',            'icon' => 'squares-four',       'text' => 'Healthcare, transport, cleaning, security, education and facilities management.'],
            ['title' => 'Your Business, Not a Generic Template',           'icon' => 'fingerprint',        'text' => 'Every response is built around your experience, people and evidence.'],
            ['title' => 'Focused on What Buyers Actually Evaluate',        'icon' => 'target',             'text' => 'We write to the specification, the evaluation criteria and the marking scheme.'],
            ['title' => 'Professional Support Without the Extra Pressure', 'icon' => 'hand-heart',         'text' => 'We manage the agreed writing and bid activities so your team can keep running the business.'],
            ['title' => 'Flexible Support That Works Around You',          'icon' => 'sliders-horizontal', 'text' => 'From a single tender to ongoing bid management.'],
            ['title' => 'Committed to Your Long-Term Success',             'icon' => 'handshake',          'text' => 'Lasting partnerships built on consistent quality and reliable communication.'],
        ],
        'stats' => [   // {years} / {sectors} / {services} are filled from config() and the data files
            'years'    => 'years of business development experience',   // L05c wording
            'sectors'  => 'sectors served',
            'services' => 'specialist services',
        ],
    ],

    // P13 — "Our Process, Step by Step" (docs/08 §4 H6): the five Our Approach steps condensed from
    // about-us-content.md §3 (client-approved headings; one-line summaries are ours). Images: process/step-0N(-detail).
    'process' => [
        'eyebrow' => 'How We Work',
        'title'   => 'Our Process,',
        'accent'  => 'Step by Step',
        'steps'   => [
            ['title' => 'Understanding Your Business',         'icon' => 'magnifying-glass', 'text' => 'We learn your services, operations, workforce and objectives to identify your competitive strengths.'],              // PROVISIONAL summary
            ['title' => 'Tender Analysis & Bid Strategy',      'icon' => 'strategy',         'text' => 'We review the ITT, specification and evaluation criteria, then plan a clear bid strategy.'],                           // PROVISIONAL summary
            ['title' => 'Evidence-Led Tender Writing',         'icon' => 'pen-nib',          'text' => 'Our writers build specific, persuasive responses around your evidence and the marking scheme.'],                     // PROVISIONAL summary
            ['title' => 'Quality Review & Compliance Checks',  'icon' => 'seal-check',       'text' => 'Every answer is checked against the specification, scoring criteria, word limits and required documents.'],          // PROVISIONAL summary
            ['title' => 'Final Submission & Ongoing Support',  'icon' => 'paper-plane-tilt', 'text' => 'We support your submission and stay with you for clarifications, feedback and your next opportunity.'],              // PROVISIONAL summary
        ],
        'cta' => ['label' => 'See our full approach', 'url' => '/about-us/#our-approach'],
    ],

    // P12 — "Sectors We Serve": pinned horizontal scroll (docs/08 §4 H5). Cards and URLs come from app/data/sectors.php.
    'sectors' => [
        'eyebrow' => 'Sectors',
        'title'   => 'Sectors We',
        'accent'  => 'Serve',
        // Client-approved copy (about-us-content.md §2 Our Story, verbatim)
        'text'    => 'Each sector presents different challenges, from regulatory compliance and workforce planning to service delivery, contract mobilisation and performance management. Understanding these differences is central to how we approach our work.',
        'link'    => ['label' => 'All sectors', 'url' => '/sectors/'],
        'images'  => [   // PROVISIONAL alt text; keys = sectors/<slug> (placeholders until the photos arrive)
            'healthcare-social-care' => 'Care worker talking with an older resident in a care home lounge',
            'transport'              => 'Patient transport vehicle outside a hospital entrance',
            'cleaning'               => 'Commercial cleaner at work in an office corridor',
            'education-training'     => 'Trainer leading a workshop for adult learners',
            'security'               => 'Security officer monitoring a building entrance',
            'facilities-management'  => 'Facilities manager inspecting plant equipment on a site walk',
            'building-maintenance'   => 'Maintenance engineer repairing a building system',
        ],
    ],

    // P11 — "Our Services": three stacking category cards (docs/08 §4 H2 🔒 first section after the hero).
    // Categories, sub-services and URLs come from app/data/services.php; only the section copy lives here.
    'services' => [
        'eyebrow' => 'Our Services',
        'title'   => 'Everything You Need to',
        'accent'  => 'Compete and Grow',
        'intro'   => 'Three specialist service areas, one dependable partner. Choose the support you need, from a single tender to ongoing compliance and digital support.', // PROVISIONAL
        'explore' => 'Explore {name}',          // button label; {name} = category name
        'count'   => ['one' => '{n} service', 'other' => '{n} services'],
        'images'  => [   // PROVISIONAL alt text; keys = services/<category-slug> (placeholders until the photos arrive)
            'bid-proposal-management'       => 'Bid manager planning a tender response with a client team',
            'compliance-virtual-assistance' => 'Compliance consultant checking policy documents with a care provider',
            'digital-presence'              => 'Designer reviewing a business website on a laptop and a phone',
        ],
    ],

    // P10 — "Our Approach to Winning Contracts" (docs/04 §1.2, docs/08 §4 H3). Paragraph = about-us-content.md §1
    // condensed; the hours card reads config('hours'); the experience claim follows L05c.
    'approach' => [
        'eyebrow' => 'About My Solutionz',
        'title'   => 'Our Approach to',
        'accent'  => 'Winning Contracts',
        'image'   => [
            'key' => 'home/approach-meeting',
            'alt' => 'Tender writing consultant reviewing a bid with a client team',
        ],
        'hours' => [
            'title'      => 'Office Hours',
            'closed'     => 'Closed',
            'email_lead' => 'Email us any time',   // the e-mail address itself comes from config('email')
        ],
        'number' => [
            'value'  => 10,
            'suffix' => '+',
            'sr'     => 'Over 10 years of business development experience',   // L05c wording
            'image'  => ['key' => 'home/approach-handshake', 'alt' => ''],      // fills the numerals; gradient until supplied
        ],
        'stack' => [
            'label'   => 'Strategic bids for sustainable growth',
            'avatars' => [   // sector hex icons in category tints (docs/09 icon map), never fake people
                ['icon' => 'first-aid-kit', 'category' => 'bid',        'alt' => 'Healthcare and social care'],
                ['icon' => 'bus',           'category' => 'compliance', 'alt' => 'Transport'],
                ['icon' => 'shield-star',   'category' => 'digital',    'alt' => 'Security'],
            ],
        ],
        'text'   => 'At My Solutionz, we help businesses compete for new opportunities through professional tender writing, bid management and business support services. We take the time to understand your services, strengths and objectives, then build responses that reflect what you can genuinely deliver.',
        'button' => ['label' => 'More About Us', 'url' => '/about-us/'],
    ],
    // P15 — "How Can We Help?" triage row (docs/08 §4 H8, docs/04 §8 strategicproposals triage): three question-led
    // glass cards on navy, each with one intent-led action. Phone comes from config().
    'triage' => [
        'eyebrow' => 'How Can We Help?',
        'cards' => [
            [
                'icon'   => 'clock',
                'title'  => 'Tender deadline coming up?',
                'text'   => 'Talk to us today about the opportunity and your deadline.',
                'label'  => 'Call now',
                'url'    => 'tel:',          // completed with config('phone_e164') in the template
                'style'  => 'primary',
            ],
            [
                'icon'   => 'compass',
                'title'  => 'Bidding for the first time?',
                'text'   => "We'll guide you through every document and step.",
                'label'  => 'Book a free consultation', // PROVISIONAL: free consultation to be confirmed by the client
                'url'    => '/contact-us/#quote',
                'style'  => 'split',
            ],
            [
                'icon'   => 'shield-check',
                'title'  => 'Need compliance or digital support?',
                'text'   => 'From CQC registration to websites and SEO.',
                'label'  => 'Explore services',
                'url'    => '/services/',
                'style'  => 'link',
            ],
        ],
    ],

    // P15 — Testimonials (docs/08 §4 H9): header only; the quotes live in app/data/testimonials.php and the section
    // is absent while that file is empty.
    'testimonials' => [
        'eyebrow' => 'Client Stories',
        'title'   => 'What Our Clients',
        'accent'  => 'Say',
    ],

    // P15 — FAQ teaser (docs/08 §4 H10): the `home`-tagged questions from app/data/faqs.php in an accordion.
    'faq' => [
        'eyebrow' => 'FAQ',
        'title'   => 'Questions,',
        'accent'  => 'Answered',
        'text'    => 'Straight answers to the questions we hear most from businesses bidding for contracts. Something else on your mind? Ask us.', // PROVISIONAL
        'button'  => ['label' => 'View all FAQs', 'url' => '/faq/'],
    ],

    // [[PENDING: insights + cta (P16)]]
    'insights' => [],
    'cta'      => [],
];
