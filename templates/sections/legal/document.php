<?php
declare(strict_types=1);
/**
 * A legal document (PROMPTS P28): hero-inner `simple` (breadcrumbs, two-voice H1, lead) → the long-form layout shared
 * with the Insights article (pages/article.css: the C41 contents sticky beside the 720px `.prose` body from 1100px,
 * a collapsible panel above it on smaller screens) with a visible "Last updated" date at the top of the text.
 * @var array $doc  App\Legal::load()
 */
$hasToc = count($doc['toc']) >= 2;
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => App\Legal::page($doc)['breadcrumbs'],
    'title'       => $doc['title'],
    'accent'      => $doc['accent'],
    'lead'        => $doc['lead'] !== '' ? $doc['lead'] : null,
]) ?>

<div class="article legal<?= $hasToc ? ' article--toc' : '' ?>">
  <div class="container article__grid">
    <?php if ($hasToc): ?>
    <div class="article__toc">
      <?= component('toc', ['items' => $doc['toc'], 'title' => 'On this page', 'label' => 'Contents']) ?>
    </div>
    <?php endif; ?>
    <div class="article__body prose" data-module="article">
      <p class="legal__updated"><?= icon('clock-counter-clockwise', 'regular', ['size' => 16]) ?><span>Last updated <time datetime="<?= e($doc['updated']) ?>"><?= e(App\Blog::displayDate($doc['updated'])) ?></time></span></p>
      <?= $doc['html'] ?>
    </div>
  </div>
</div>
