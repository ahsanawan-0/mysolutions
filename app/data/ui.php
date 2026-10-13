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

    // Micro-CTA row (templates/components/micro-cta.php)
    'micro_cta' => [
        'text'  => 'Not sure which service you need?',
        'label' => "Let's talk",
        'url'   => '/contact-us/',
    ],

    // Footer (templates/partials/footer.php)
    'footer' => [
        'description' => 'Tender writing, bid management, compliance and digital support for UK businesses.', // PROVISIONAL
        'legal_links' => [
            ['Privacy', '/privacy-policy/'],
            ['Cookies', '/cookie-policy/'],
            // 'Cookie settings' is a <button data-cookie-settings> printed between these (P28 consent module)
            ['Terms', '/terms-and-conditions/'],
            ['Accessibility', '/accessibility/'],
            ['Sitemap', '/sitemap/'],
        ],
    ],
];
