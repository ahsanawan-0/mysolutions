<?php
declare(strict_types=1);

/**
 * Site-wide facts: NAP, company details, socials, hours, locale.
 * Single source of truth: templates, schema and the footer all read from here via config().
 * Company facts are locked (docs/00-LOCKED-DECISIONS.md L05, L05b, L05c): never invent others.
 */
return [
    'name'           => 'My Solutionz',
    'legal_name'     => 'My Solutionz Ltd',
    'company_number' => '15677749',
    'registered_in'  => 'England and Wales',
    'founded'        => '2024-04-24',
    'tagline'        => 'Solutions Crafted for Success',
    'experience'     => 'over 10 years of business development experience',

    'url'    => env('SITE_URL', 'https://mysolutionz.co.uk'),
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

    // [[PENDING: office hours]] — client to confirm (docs/15 P08). Provisional until then.
    'hours' => [
        'status'  => 'provisional', // PROVISIONAL
        'summary' => 'Monday to Friday, 09:00–17:30',
        'days'    => [
            // schema.org OpeningHoursSpecification-friendly
            ['days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], 'opens' => '09:00', 'closes' => '17:30'], // PROVISIONAL
        ],
    ],

    'socials' => [
        'linkedin'  => 'https://www.linkedin.com/company/mysolutionz/',
        'instagram' => 'https://www.instagram.com/mysolutionz/',
        'facebook'  => 'https://www.facebook.com/profile.php?id=61558654196971',
    ],
];
