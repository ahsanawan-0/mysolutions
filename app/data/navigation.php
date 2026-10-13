<?php
declare(strict_types=1);

/**
 * Primary navigation (docs/08 §2). `menu` = 'services' (mega menu from services()) or 'sectors' (dropdown from sectors()).
 * Access via navigation().
 */
return [
    ['label' => 'Home',     'url' => '/',            'exact' => true],
    ['label' => 'About',    'url' => '/about-us/'],
    ['label' => 'Services', 'url' => '/services/',   'menu' => 'services'],
    ['label' => 'Sectors',  'url' => '/sectors/',    'menu' => 'sectors'],
    ['label' => 'Insights', 'url' => '/insights/'],
    ['label' => 'Contact',  'url' => '/contact-us/'],
];
