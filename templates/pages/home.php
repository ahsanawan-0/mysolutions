<?php
declare(strict_types=1);
/**
 * Home page. P08: hero (templates/sections/home/hero.php, which renders the header's `hero` variant inside the
 * panel). P11: "Our Services", P10: "Our Approach". The remaining sections (P12–P16) are anchored placeholders so the hero's
 * quick links already work. Section order follows docs/08 §4 (H1 hero → H2 services → H3 approach → H4 sectors …).
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
    'services' => 'sections/home/services',
    'approach' => 'sections/home/approach',
    'sectors'  => 'sections/home/sectors',
    'process'  => 'sections/home/process',
    'why'      => ['Why Work With Us', 'P14'],
    'faq'      => ['FAQ', 'P15'],
    'insights' => ['Latest Insights', 'P16'],
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
