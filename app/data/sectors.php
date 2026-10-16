<?php
declare(strict_types=1);

/**
 * Sectors we serve (docs/09 §5, PROMPTS P04). Single source of truth for routes, navigation and sector pages.
 * Access via sectors(). Page copy: app/data/sector-pages/<slug>.php (P22). 🟡 Final list once client content arrives.
 * Keys: slug, name, icon (Phosphor light), summary, status, image + image_alt (the sector photo: home cards, hub, sector
 * hero; AI-generated illustrative photography, sources in assets-src/images/), url.
 */
$sectors = [
    ['slug' => 'healthcare-social-care', 'name' => 'Healthcare & Social Care', 'icon' => 'first-aid-kit',  'summary' => 'Tenders for domiciliary care, supported living, residential and NHS-commissioned services.', 'status' => 'draft',
     'image' => 'sectors/healthcare-social-care-consultation', 'image_alt' => 'Care coordinator reviewing a folder with an older resident in a care home lounge'], // PROVISIONAL
    ['slug' => 'transport',              'name' => 'Transport',                'icon' => 'bus',            'summary' => 'Bids for passenger, patient and logistics transport contracts.',                          'status' => 'draft',
     'image' => 'sectors/transport-passenger-minibus', 'image_alt' => 'Driver beside an accessible passenger minibus outside a healthcare building'], // PROVISIONAL
    ['slug' => 'cleaning',               'name' => 'Cleaning',                 'icon' => 'broom',          'summary' => 'Commercial, healthcare and education cleaning contracts.',                               'status' => 'draft',
     'image' => 'sectors/commercial-office-cleaning', 'image_alt' => 'Commercial cleaner at work in an office corridor'], // PROVISIONAL
    ['slug' => 'education-training',     'name' => 'Education & Training',     'icon' => 'graduation-cap', 'summary' => 'Training provision, apprenticeships and education support services.',                   'status' => 'draft',
     'image' => 'sectors/education-adult-training-workshop', 'image_alt' => 'Trainer leading a workshop for adult learners'], // PROVISIONAL
    ['slug' => 'security',               'name' => 'Security',                 'icon' => 'shield-star',    'summary' => 'Manned guarding, mobile patrols and event security contracts.',                          'status' => 'draft',
     'image' => 'sectors/security-office-reception', 'image_alt' => 'Security officer monitoring a building entrance'], // PROVISIONAL
    ['slug' => 'facilities-management',  'name' => 'Facilities Management',    'icon' => 'buildings',      'summary' => 'Integrated and soft FM contracts across public and private estates.',                    'status' => 'draft',
     'image' => 'sectors/facilities-manager-plant-room', 'image_alt' => 'Facilities manager checking plant-room equipment with a tablet'], // PROVISIONAL
    ['slug' => 'building-maintenance',   'name' => 'Building Maintenance',     'icon' => 'wrench',         'summary' => 'Planned and reactive maintenance and repair contracts.',                                 'status' => 'draft',
     'image' => 'sectors/building-maintenance-engineer', 'image_alt' => 'Maintenance engineer checking a ventilation unit with a diagnostic device'], // PROVISIONAL
];

foreach ($sectors as &$sector) {
    $sector['url'] = '/sectors/' . $sector['slug'] . '/';
}
unset($sector);

return $sectors;
