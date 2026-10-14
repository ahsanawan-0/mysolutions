<?php
declare(strict_types=1);
/**
 * Home page. P08: hero (templates/sections/home/hero.php, which renders the header's `hero` variant inside the
 * panel). P10–P15 built services, approach, the micro-CTA row, sectors, process, why, triage, testimonials (absent until
 * supplied) and the FAQ teaser; Insights + the final CTA (P16) are still placeholders. Order follows docs/08 §4 (H1–H13).
 * Copy: app/data/home.php.
 */
$page = [
    'title'          => 'Tender Writing and Bid Management Services UK | My Solutionz',
    'description'    => 'UK tender writing, bid management, compliance and digital presence services from My Solutionz, London. Evidence-led bids that help businesses win public-sector contracts.', // PROVISIONAL
    'canonical'      => '/',
    'status'         => 'draft',
    'body_class'     => 'page-home',
    'header'         => 'hero',
    'header_theme'   => 'dark',
    'header_overlay' => true,
    'primary_image'  => '/assets/og/default.jpg',
];

// id → [label, prompt] for sections not built yet, or a section template name once built
$sections = [
    'services'     => 'sections/home/services',
    'approach'     => 'sections/home/approach',
    'micro'        => 'sections/home/micro-cta',
    'sectors'      => 'sections/home/sectors',
    'process'      => 'sections/home/process',
    'why'          => 'sections/home/why',
    'triage'       => 'sections/home/triage',
    'testimonials' => 'sections/home/testimonials',   // renders nothing until app/data/testimonials.php has items
    'faq'          => 'sections/home/faq',
    'insights'     => ['Latest Insights', 'P16'],
];
?>
<?= render('sections/home/hero') ?>
<?php foreach ($sections as $id => $section): ?>
<?php if (is_string($section)): ?>
<?= render($section) ?>
<?php else: [$label, $prompt] = $section; ?>
<section class="section home-placeholder" id="<?= e($id) ?>" aria-label="<?= e($label) ?>">
  <div class="container"><p class="small">[[PENDING: <?= e($label) ?> section (<?= e($prompt) ?>)]]</p></div>
</section>
<?php endif; ?>
<?php endforeach; ?>
