<?php
declare(strict_types=1);

/**
 * Site-wide facts: NAP, company details, socials, hours, locale, SEO defaults.
 * Single source of truth: templates, schema, sitemaps and the footer all read from here via config().
 * Company facts are locked (docs/00-LOCKED-DECISIONS.md L05, L05b, L05c): never invent others.
 */
return [
    'name'           => 'My Solutionz',
    'legal_name'     => 'My Solutionz Ltd',
    'alternate_names' => ['MySolutionz', 'mysolutionz.co.uk'],
    'company_number' => '15677749',
    'registered_in'  => 'England and Wales',
    'founded'        => '2024-04-24',
    'tagline'        => 'Solutions Crafted for Success',
    'experience'     => 'over 10 years of business development experience',
    'description'    => 'My Solutionz is a London-based UK consultancy offering bid and proposal management (tender and bid writing, opportunity identification, expert review), compliance and virtual assistance (CQC registration, policy management, carbon reduction plans, data management, care plans) and digital presence services (website development, social media, email marketing, SEO and analytics) for businesses bidding for public-sector and private contracts.',

    'url'    => env('SITE_URL', 'https://mysolutionz.co.uk'),
    'production_url' => 'https://mysolutionz.co.uk',   // canonical host for sitemaps/legacy URLs regardless of environment
    'locale' => 'en-GB',

    'email'         => 'info@mysolutionz.co.uk',
    'phone_display' => '+44 7466 063564',
    'phone_e164'    => '+447466063564',
    'whatsapp'      => 'https://wa.me/447466063564',

    'address' => [
        'street'   => 'Unit A53, 4–6 Greatorex Street',
        'locality' => 'London',
        'postcode' => 'E1 5NF',
        'country'  => 'GB',
    ],
    'area_served' => 'GB',

    // [[PENDING: office hours]] — client to confirm (docs/15 P08). Provisional until then.
    'hours' => [
        'status'  => 'provisional', // PROVISIONAL
        'summary' => 'Monday to Friday, 09:00–17:30',
        'days'    => [
            // schema.org OpeningHoursSpecification-friendly
            ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], 'opens' => '09:00', 'closes' => '17:30'], // PROVISIONAL
        ],
        'closed'  => ['Saturday', 'Sunday'], // PROVISIONAL
        // E-mail is answered outside these hours; never claim 24/7 support (docs/13 §6 claims).
        'email_anytime' => true,
    ],

    'socials' => [
        'linkedin'  => 'https://www.linkedin.com/company/mysolutionz/',
        'instagram' => 'https://www.instagram.com/mysolutionz/',
        'facebook'  => 'https://www.facebook.com/profile.php?id=61558654196971',
    ],

    // ---- SEO / structured data (docs/10) ----
    'seo' => [
        'title_suffix'      => ' | My Solutionz',
        'default_robots'    => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
        'og_default'        => '/assets/og/default.jpg',   // 1200×630, scripts/og.mjs
        'og_default_alt'    => 'My Solutionz: Solutions Crafted for Success. Bid and proposal management, compliance and digital presence services, London',
        'logo'              => '/assets/brand/logo-512.png',   // 512px PNG on white (vertical lockup), npm run logo
        'theme_color'       => '#011B43',   // = --navy-900 in src/css/tokens.css (browser UI colour; not a component value)
        'organization_type' => 'Organization',   // 'LocalBusiness' only if the London office is confirmed as staffed (docs/15 P10)
        'legacy_sitemap'    => true,             // /legacy-sitemap.xml of old URLs; switch off ~6 weeks after launch
        'block_ai_training' => false,            // robots.txt: block GPTBot/ClaudeBot/CCBot/Google-Extended (client's business decision)
        'faq_schema'        => false,            // FAQPage markup is optional (Google dropped FAQ rich results, May 2026)
        'knows_about'       => [
            'Tender writing', 'Bid management', 'Public procurement', 'Procurement Act 2023',
            'CQC registration', 'Carbon reduction plans', 'Policy management',
            'Website development', 'Social media management', 'Email marketing', 'SEO',
        ],
    ],
];
