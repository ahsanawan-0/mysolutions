<?php
declare(strict_types=1);
/**
 * About page /about-us/ (PROMPTS P18–P19, docs/08 §5 A0–A7, section order locked by docs/00 L17).
 * Copy: app/data/about.php (= docs/website_content/about-us-content.md, the client's final copy, verbatim).
 * A0 hero-inner `columns` (C32) → C34 sub-nav (lists only the sections rendered below) → sections in
 * templates/sections/about/*.php, each with the anchor id the sub-nav and other pages link to (/about-us/#our-story).
 * P18: About Us, Our Story. P19: Our Approach, Why Work With Us?, Our Mission & Vision, Team (absent while
 * app/data/team.php is empty), CTA band. The sub-nav lists the sections that are in about('nav').
 */
$seo = about('seo');
$hero = about('hero');
$heroPhoto = App\Image::src($hero['images'][0]['key'], 1200);
$page = [
    'title'          => $seo['title'],
    'description'    => $seo['description'],
    'canonical'      => '/about-us/',
    'status'         => 'published',
    'og_title'       => $seo['og_title'],
    'primary_image'  => $heroPhoto,
    'web_page_type'  => 'AboutPage',
    'breadcrumbs'    => [['Home', '/'], ['About Us', null]],
    'body_class'     => 'page-about',
    'header_theme'   => 'dark',
    'header_overlay' => true,
];

// Section id (anchor) => template, in the locked order
$sections = [
    'about-us'         => 'sections/about/intro',
    'our-story'        => 'sections/about/story',
    'our-approach'     => 'sections/about/approach',
    'why-work-with-us' => 'sections/about/why',
    'mission-vision'   => 'sections/about/mission',
    'team'             => 'sections/about/team',
    'contact'          => 'sections/about/cta',
];
?>
<?= component('hero-inner', [
    'variant'     => 'columns',
    'breadcrumbs' => $page['breadcrumbs'],
    'eyebrow'     => $hero['eyebrow'],
    'title'       => $hero['title'],
    'accent'      => $hero['accent'],
    'lead'        => $hero['lead'],
    'ctas'        => $hero['ctas'],
    'images'      => $hero['images'],
    'labels'      => $hero['labels'],
]) ?>

<?= component('subnav', ['items' => array_intersect_key(about('nav'), $sections), 'label' => 'About My Solutionz']) ?>

<?php foreach ($sections as $id => $template): ?>
<?= render($template, ['id' => $id]) ?>
<?php endforeach; ?>
