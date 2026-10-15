<?php
declare(strict_types=1);
/**
 * Home page (docs/08 §4 H0–H13, docs/10 §4–§7). Sections live in templates/sections/home/*.php with their copy in
 * app/data/home.php: hero (P08/P09) → services (P11) → approach (P10) → micro-CTA → sectors (P12) → process (P13)
 * → why (P14) → triage, testimonials (absent until supplied), FAQ teaser (P15) → insights, final CTA (P16).
 * C29 dividers mark every light ↔ dark change; each section carries data-header-theme so the header adapts.
 * SEO (P16): the home graph is Organization + WebSite + WebPage with primaryImageOfPage (App\Schema::graph). The
 * photographic OG image (public/assets/og/home.jpg, a 1200×630 crop that `npm run og` makes from the client's hero
 * photo) is used when it exists; until then the default brand card. Everything marked PROVISIONAL is replaced in C01.
 */
$hero = home('hero');
$ogPhoto = is_file(public_path('assets/og/home.jpg')) ? '/assets/og/home.jpg' : null;
$page = [
    'title'          => 'Tender Writing & Bid Management Services UK | My Solutionz', // PROVISIONAL (58 chars)
    'description'    => 'Expert tender writing, bid management, compliance and digital support for UK businesses, backed by ' . config('experience') . '.', // PROVISIONAL
    'canonical'      => '/',
    'status'         => 'draft',           // published once the client approves the home copy (C01)
    'og_title'       => rtrim($hero['title'] . ' ' . $hero['accent'], '.'),   // "Tenders, Crafted for Success"
    'og_image'       => $ogPhoto,          // null → config('seo.og_default')
    'og_image_alt'   => $ogPhoto ? $hero['photo']['alt'] : null,
    'primary_image'  => $ogPhoto ?? config('seo.og_default'),
    'web_page_type'  => 'WebPage',
    'body_class'     => 'page-home',
    'header'         => 'hero',
    'header_theme'   => 'dark',
    'header_overlay' => true,
];

// Section templates in docs/08 §4 order; ['divider', [...]] entries are C29 dividers between a light and a dark section
$afterTriage = testimonials() ? 'ms-canvas' : 'ms-white';   // the testimonials section sits on the canvas, the FAQ on white
$sections = [
    'services'     => 'sections/home/services',
    'approach'     => 'sections/home/approach',
    'micro'        => 'sections/home/micro-cta',
    'sectors'      => 'sections/home/sectors',
    'd-process'    => ['divider', ['variant' => 'wave', 'from' => 'ms-canvas', 'to' => 'navy-900']],
    'process'      => 'sections/home/process',
    'd-why'        => ['divider', ['variant' => 'diagonal', 'from' => 'navy-900', 'to' => 'ms-canvas']],
    'why'          => 'sections/home/why',
    'd-triage'     => ['divider', ['variant' => 'diagonal', 'from' => 'ms-canvas', 'to' => 'navy-900', 'flip' => true]],
    'triage'       => 'sections/home/triage',
    'd-faq'        => ['divider', ['variant' => 'wave', 'from' => 'navy-900', 'to' => $afterTriage, 'flip' => true]],
    'testimonials' => 'sections/home/testimonials',   // renders nothing until app/data/testimonials.php has items
    'faq'          => 'sections/home/faq',
    'insights'     => 'sections/home/insights',
    'cta'          => 'sections/home/cta',
];
?>
<?= render('sections/home/hero') ?>
<?php foreach ($sections as $section): ?>
<?= is_string($section) ? render($section) : component($section[0], $section[1]) ?>
<?php endforeach; ?>
