<?php
declare(strict_types=1);
/**
 * Inner-hero styleguide (local only, noindex; PROMPTS P17): the C32 hero-inner component in all four variants,
 * plus the category variant with image columns as used by sub-service pages. Copy is the real page copy
 * (About, Bid & Proposal Management, Expert Review, the migrated article), so the demos double as P18–P26 previews.
 * Only the first demo carries the page's H1; the others use level 2 to keep one H1 on the page.
 */
$page = [
    'title'          => 'Inner heroes styleguide | My Solutionz (dev)',
    'description'    => 'Developer styleguide for the My Solutionz inner-page hero: columns, category, simple and article variants with breadcrumbs, labels and the glowing frame.',
    'canonical'      => '/styleguide/heroes/',
    'robots'         => 'noindex, nofollow',
    'status'         => 'draft',
    'breadcrumbs'    => [['Home', '/'], ['Styleguide', '/styleguide/'], ['Inner heroes', null]],
    'body_class'     => 'page-styleguide page-styleguide-heroes',
    'header_theme'   => 'dark',
    'header_overlay' => true,
];

$label = static fn (string $name, string $note): string =>
    '<div class="sg-heroes__label" data-header-theme="light"><div class="container">' . component('eyebrow', ['text' => 'Variant: ' . $name]) . '<p class="small">' . e($note) . '</p></div></div>';
$bidCrumbs = [['Home', '/'], ['Services', '/services/'], ['Bid & Proposal Management', '/services/bid-proposal-management/']];
?>
<?= component('hero-inner', [
    'variant'     => 'columns',
    'breadcrumbs' => [['Home', '/'], ['About Us', null]],
    'eyebrow'     => 'About Us',
    'title'       => 'About',
    'accent'      => 'My Solutionz',
    'lead'        => 'We help businesses compete for new opportunities through professional tender writing, bid management and business support services.',
    'ctas'        => [['Get a Free Quote', '/contact-us/#quote'], ['Our Services', '/services/']],
    'images'      => [
        ['key' => 'home/tender-consultancy-meeting', 'alt' => 'Tender writing consultant reviewing a bid with a client team'],
        ['key' => 'home/hero-building', 'alt' => '', 'focus' => 'right'],
        ['key' => 'sectors/healthcare-social-care-consultation', 'alt' => ''],
    ],
    'labels'      => [
        ['text' => 'Bid Strategy', 'icon' => 'strategy'],
        ['text' => 'Compliance', 'icon' => 'seal-check'],
        ['text' => 'Evidence-Led Writing', 'icon' => 'pen-nib'],
    ],
    'id'          => 'hero-columns',
]) ?>

<?= $label('category', 'Service category page (P20): category chip and glow, the framed hex tile, sub line and trust line.') ?>
<?= component('hero-inner', [
    'variant'     => 'category',
    'colour'      => 'bid',
    'breadcrumbs' => [['Home', '/'], ['Services', '/services/'], ['Bid & Proposal Management', null]],
    'eyebrow'     => 'Our Services',
    'title'       => 'Bid &',
    'accent'      => 'Proposal Management',
    'sub'         => 'Strategic Bid Management to Help Your Business Compete and Grow',
    'lead'        => 'Securing valuable contracts requires more than preparing a convincing proposal. It demands careful planning, a clear understanding of procurement requirements and effective coordination throughout the bidding process. Without the right expertise and resources, even experienced businesses can struggle to present their capabilities effectively within demanding submission deadlines.',
    'ctas'        => [['Get a Free Quote', '/contact-us/?service=bid-proposal-management#quote'], ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone']],
    'trust'       => 'Over 10 years of business development experience · Public and private sector bids · Healthcare, transport, cleaning, security, education and facilities management',
    'icon'        => 'kanban',
    'icon_label'  => 'Bid & Proposal Management',
    'id'          => 'hero-category',
    'level'       => 2,
]) ?>

<?= $label('category + columns', 'Sub-service page (P21): "Part of …" chip linking up, image columns with the framed tile floating over them.') ?>
<?= component('hero-inner', [
    'variant'     => 'category',
    'colour'      => 'bid',
    'breadcrumbs' => [...$bidCrumbs, ['Expert Review', null]],
    'parent'      => ['Bid & Proposal Management', '/services/bid-proposal-management/'],
    'title'       => 'Expert',
    'accent'      => 'Review',
    'sub'         => 'Expert Eyes. Stronger Bids. Better Opportunities to Compete.',
    'lead'        => 'Even a well-written tender can lose valuable marks if it overlooks evaluation requirements, lacks supporting evidence or fails to demonstrate the level of detail expected by the buyer. Before submitting an important bid, having an experienced professional review your responses can make a significant difference to their overall quality.',
    'ctas'        => [['Get Your Tender Expertly Reviewed', '/contact-us/?service=expert-review#quote'], ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone']],
    'images'      => [
        ['key' => 'home/tender-consultancy-meeting', 'alt' => 'Bid reviewer discussing a draft tender response with a client'],
        ['key' => 'sectors/facilities-manager-plant-room', 'alt' => ''],
        ['key' => 'home/hero-building', 'alt' => ''],
    ],
    'icon'        => 'list-checks',
    'icon_label'  => 'Expert Review',
    'id'          => 'hero-sub',
    'level'       => 2,
]) ?>

<?= $label('simple', 'Legal, FAQ and utility pages (P27–P29): light, compact, breadcrumbs + H1 + lead.') ?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => [['Home', '/'], ['Privacy Policy', null]],
    'title'       => 'Privacy',
    'accent'      => 'Policy',
    'lead'        => 'How My Solutionz Ltd collects, uses and protects personal data when you use this website or contact us.',
    'id'          => 'hero-simple',
    'level'       => 2,
]) ?>

<?= $label('article', 'Insights article (P26): category chip, H1, meta row and the featured image (priority, never clipped).') ?>
<?= component('hero-inner', [
    'variant'     => 'article',
    'breadcrumbs' => [['Home', '/'], ['Insights', '/insights/'], ['Tender Vocabulary Explained', null]],
    'chip'        => ['Tender Writing', null],
    'title'       => "What's in a Health and Social Care Tender?",
    'accent'      => 'Tender Vocabulary Explained',
    'lead'        => 'Confused by tender documents? A plain-English guide to the terms in a health and social care tender: ITT, service specification, SQ, social value and TUPE.',
    'meta'        => [
        ['icon' => 'calendar-blank', 'text' => '18 January 2026', 'datetime' => '2026-01-18'],
        ['icon' => 'clock', 'text' => '6 min read'],
        ['icon' => 'user', 'text' => 'My Solutionz Team'],
    ],
    'image'       => ['key' => 'sectors/healthcare-social-care-consultation', 'alt' => 'Care coordinator reviewing a folder with an older resident'],
    'id'          => 'hero-article',
    'level'       => 2,
]) ?>
