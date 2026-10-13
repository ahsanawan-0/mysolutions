<?php
declare(strict_types=1);

/**
 * Sectors we serve (docs/09 §5, PROMPTS P04). Single source of truth for routes, navigation and sector pages.
 * Access via sectors(). P22 adds the page content. 🟡 Final list once client content arrives.
 * Keys: slug, name, icon (Phosphor light), summary, status, url.
 */
$sectors = [
    ['slug' => 'healthcare-social-care', 'name' => 'Healthcare & Social Care', 'icon' => 'first-aid-kit',  'summary' => 'Tenders for domiciliary care, supported living, residential and NHS-commissioned services.', 'status' => 'draft'], // PROVISIONAL
    ['slug' => 'transport',              'name' => 'Transport',                'icon' => 'bus',            'summary' => 'Bids for passenger, patient and logistics transport contracts.',                          'status' => 'draft'], // PROVISIONAL
    ['slug' => 'cleaning',               'name' => 'Cleaning',                 'icon' => 'broom',          'summary' => 'Commercial, healthcare and education cleaning contracts.',                               'status' => 'draft'], // PROVISIONAL
    ['slug' => 'education-training',     'name' => 'Education & Training',     'icon' => 'graduation-cap', 'summary' => 'Training provision, apprenticeships and education support services.',                   'status' => 'draft'], // PROVISIONAL
    ['slug' => 'security',               'name' => 'Security',                 'icon' => 'shield-star',    'summary' => 'Manned guarding, mobile patrols and event security contracts.',                          'status' => 'draft'], // PROVISIONAL
    ['slug' => 'facilities-management',  'name' => 'Facilities Management',    'icon' => 'buildings',      'summary' => 'Integrated and soft FM contracts across public and private estates.',                    'status' => 'draft'], // PROVISIONAL
    ['slug' => 'building-maintenance',   'name' => 'Building Maintenance',     'icon' => 'wrench',         'summary' => 'Planned and reactive maintenance and repair contracts.',                                 'status' => 'draft'], // PROVISIONAL
];

foreach ($sectors as &$sector) {
    $sector['url'] = '/sectors/' . $sector['slug'] . '/';
}
unset($sector);

return $sectors;
