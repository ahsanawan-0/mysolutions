<?php
declare(strict_types=1);

/**
 * Site-wide UI copy for reusable components (PROMPTS P05). Read via ui('cta_band'), ui('micro_cta').
 * Default copy only: pages may override any key when they call the component.
 */
return [
    // C21 CTA band (templates/components/cta-band.php)
    'cta_band' => [
        'eyebrow'   => "Let's talk",
        'heading'   => 'Ready to Win Your',
        'accent'    => 'Next Contract?',
        'text'      => "Tell us about the opportunity and your deadline. We'll explain how we can help and give you a clear, no-obligation quote.",
        'primary'   => ['Get a Free Quote', '/contact-us/#quote'],
        'secondary' => ['Call ' . config('phone_display'), 'tel:' . config('phone_e164')],
        'whatsapp'  => ['Chat on WhatsApp', config('whatsapp')],
    ],

    // Sector page section headings (templates/pages/sectors/single.php, P22); {sector} = the sector name
    'sector_page' => [
        'challenges' => ['eyebrow' => 'The challenges', 'heading' => 'Challenges in', 'accent' => '{sector}'],
        'services'   => 'Services that fit this sector',
        'buyers'     => ['eyebrow' => 'Who buys', 'heading' => 'Typical Buyers and', 'accent' => 'Contract Types'],
        'faq'        => ['eyebrow' => 'FAQs', 'heading' => '{sector}', 'accent' => 'FAQs'],
        'other'      => 'Other sectors we serve',
    ],

    // Related services on the sub-service pages (templates/sections/service/related.php, P21)
    'related_services' => [
        'eyebrow' => 'Keep exploring',
        'heading' => 'Related',
        'accent'  => 'Services',
        'part_of' => 'Part of {category}',
        'explore' => 'Explore {name}',
    ],

    // Office Hours card (templates/components/hours-card.php); the hours themselves come from config('hours')
    'hours_card' => [
        'title'      => 'Office Hours',
        'closed'     => 'Closed',
        'email_lead' => 'Email us any time',   // the address itself comes from config('email')
    ],

    // Micro-CTA row (templates/components/micro-cta.php)
    'micro_cta' => [
        'text'  => 'Not sure which service you need?',
        'label' => "Let's talk",
        'url'   => '/contact-us/',
    ],

    // Footer (templates/partials/footer.php)
    'footer' => [
        'description' => 'Tender writing, bid management, compliance and digital support for UK businesses.', // PROVISIONAL
        'company_links' => [
            ['About us', '/about-us/'],
            ['Insights', '/insights/'],
            ['FAQ', '/faq/'],
            ['Contact', '/contact-us/'],
            ['Get a Free Quote', '/contact-us/#quote'],
        ],
        // Decorative word strip along the bottom edge of the footer panel
        'marquee' => ['Tender Writing', 'Bid Management', 'Compliance', 'Digital Presence', 'UK Businesses'],
        'legal_links' => [
            ['Privacy', '/privacy-policy/'],
            ['Cookies', '/cookie-policy/'],
            // 'Cookie settings' (cookie_settings below) is printed between these: a link to /cookie-policy/#cookie-settings
            // that opens the preferences dialog instead when the consent module runs (P28)
            ['Terms', '/terms-and-conditions/'],
            ['Accessibility', '/accessibility/'],
            ['Sitemap', '/sitemap/'],
        ],
        'cookie_settings' => 'Cookie settings',
    ],

    // Error pages (P29): 404 (templates/pages/404.php) and 410 (pages/410.php) share the helpful links
    // (templates/sections/error/links.php)
    'errors' => [
        'not_found' => [
            'seo_title'   => 'Sorry, that page was not found | My Solutionz',
            'description' => 'The page you were looking for could not be found. Find our services, the sectors we work in or how to get in touch.',
            'crumb'       => 'Page not found',
            'title'       => 'Page',
            'accent'      => 'Not Found',
            'lead'        => "The page you're looking for may have moved. These links might help:",
            'path'        => "We couldn't find {path} on this website.",
            'knock'       => 'Knock the logo over',
            'rebuild'     => 'Put the logo back together',
        ],
        'gone' => [
            'seo_title'   => 'This page has been removed | My Solutionz',
            'description' => 'The page you were looking for has been removed. Find our services, the sectors we work in or how to get in touch.',
            'crumb'       => 'Page removed',
            'title'       => 'This Page Has',
            'accent'      => 'Been Removed',
            'lead'        => 'The content that used to be here is no longer available. These links might help:',
        ],
        'links_title' => 'Helpful links',
        'links' => [
            'home'     => ['label' => 'Start again',   'title' => 'Home',        'text' => 'Back to the home page.', 'icon' => 'house'],
            'services' => ['label' => 'What we do',    'title' => 'Services',    'text' => 'All our services in one place.', 'icon' => 'squares-four'],
            'category' => ['label' => 'Service area'],   // + each category's name, summary and icon from services()
            'sectors'  => ['label' => 'Who we help',   'title' => 'Sectors',     'text' => 'The sectors we work in.', 'icon' => 'buildings'],
            'contact'  => ['label' => 'Get in touch',  'title' => 'Contact us',  'text' => 'Ask a question or get a free quote.', 'icon' => 'chats-circle'],
        ],
        'phone' => 'Prefer to talk? Call us on {phone}.',
    ],

    // HTML sitemap /sitemap/ (P29, templates/pages/sitemap.php)
    'sitemap' => [
        'seo_title'   => 'Website Sitemap and Page Directory | My Solutionz',
        'description' => 'Every page on the My Solutionz website in one place: our services, the sectors we work in, insights, contact details and legal information.',
        'title'       => 'Website',
        'accent'      => 'Sitemap',
        'lead'        => 'Every page on this website, grouped by topic.',
        'groups'      => ['main' => 'Main pages', 'services' => 'Services', 'sectors' => 'Sectors', 'insights' => 'Insights', 'legal' => 'Legal'],
        'draft'       => 'Draft',
        'draft_note'  => 'Pages marked "Draft" are listed here for review only: they are hidden on the live website until they are published.',
    ],

    // C24 consent banner + preferences dialog (templates/partials/cookie-banner.php, P28). Technologies and category
    // descriptions come from app/config/cookies.php.
    'consent' => [
        'label'   => 'Cookie choices',
        'title'   => 'Cookies on this website',
        // mode 'consent': analytics or marketing need an opt-in
        'text'    => "We use strictly necessary cookies to make this site work. With your permission, we'd also like to use analytics cookies to see how the site is used, so we can improve it. Nothing optional is set unless you accept.",
        // mode 'notice': only exempt statistics (DUAA 2025), on unless you object
        'notice'  => 'We count visits with privacy-friendly analytics that use no cookies and never identify you. You can opt out at any time.',
        'policy'  => 'Read our [cookie policy](/cookie-policy/).',
        'accept'  => 'Accept all',
        'reject'  => 'Reject all',
        'manage'  => 'Manage preferences',
        'ok'      => 'OK',
        'opt_out' => 'Opt out',
        'dialog'  => [
            'title'     => 'Cookie preferences',
            'intro'     => 'Choose which optional technologies we can use. You can change your mind at any time from "Cookie settings" at the bottom of every page.',
            'always'    => 'Always on',
            'on'        => 'On',
            'exempt'    => 'Privacy-friendly statistics: on unless you switch them off.',
            'details'   => 'What we use',
            'columns'   => ['Name', 'Provider', 'Purpose', 'Duration'],
            'save'      => 'Save preferences',
            'close'     => 'Close',
        ],
        'saved'   => 'Your cookie preferences have been saved.',
    ],
];
