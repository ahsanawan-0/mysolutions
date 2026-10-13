<?php
declare(strict_types=1);
/**
 * Home page. P08: hero (templates/sections/home/hero.php, which renders the header's `hero` variant inside the
 * panel). The remaining sections (P10–P16) are anchored placeholders so the hero's quick links already work.
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

$placeholders = [
    'services' => ['Our Services', 'P11'],
    'approach' => ['Our Approach', 'P10'],
    'sectors'  => ['Sectors We Serve', 'P12'],
    'process'  => ['Our Process', 'P13'],
    'why'      => ['Why Work With Us', 'P14'],
    'faq'      => ['FAQ', 'P15'],
    'insights' => ['Latest Insights', 'P16'],
];
?>
<?= render('sections/home/hero') ?>
<?php foreach ($placeholders as $id => [$label, $prompt]): ?>
<section class="section home-placeholder" id="<?= e($id) ?>" aria-label="<?= e($label) ?>">
  <div class="container"><p class="small">[[PENDING: <?= e($label) ?> section (<?= e($prompt) ?>)]]</p></div>
</section>
<?php endforeach; ?>
