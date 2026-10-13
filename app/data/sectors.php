<?php
declare(strict_types=1);

/**
 * Sectors we serve (docs/09 §5). Single source of truth for routes, navigation and sector pages.
 * P04 extends the entries (menu lines); P22 adds the page content. 🟡 Final list once client content arrives.
 */
return [
    ['slug' => 'healthcare-social-care', 'name' => 'Healthcare & Social Care', 'icon' => 'first-aid-kit', 'summary' => 'Tender writing and compliance support for care providers, domiciliary care, supported living and NHS contracts.'], // PROVISIONAL
    ['slug' => 'transport',              'name' => 'Transport',                'icon' => 'bus',           'summary' => 'Bids for passenger transport, home-to-school, patient transport and logistics contracts.'], // PROVISIONAL
    ['slug' => 'cleaning',               'name' => 'Cleaning',                 'icon' => 'broom',         'summary' => 'Commercial and public-sector cleaning tenders, from schools to healthcare settings.'], // PROVISIONAL
    ['slug' => 'education-training',     'name' => 'Education & Training',     'icon' => 'graduation-cap','summary' => 'Training providers, apprenticeships and education-sector frameworks.'], // PROVISIONAL
    ['slug' => 'security',               'name' => 'Security',                 'icon' => 'shield-star',   'summary' => 'Manned guarding, monitoring and security services tenders.'], // PROVISIONAL
    ['slug' => 'facilities-management',  'name' => 'Facilities Management',    'icon' => 'buildings',     'summary' => 'Hard and soft FM contracts for public and private estates.'], // PROVISIONAL
    ['slug' => 'building-maintenance',   'name' => 'Building Maintenance',     'icon' => 'wrench',        'summary' => 'Repairs, maintenance and minor works frameworks for housing and public bodies.'], // PROVISIONAL
];
