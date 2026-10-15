<?php
declare(strict_types=1);
/**
 * Home "Latest Insights" (docs/08 §4 H11, docs/04 §1.3, PROMPTS P16): two-voice H2, "View all insights" link and up
 * to three C10 blog cards from App\Blog::latest(3). With fewer than three posts a "More insights coming soon"
 * card (→ LinkedIn) fills the remaining columns. Copy: app/data/home.php → insights.
 */
$in = home('insights');
$posts = App\Blog::latest(3);
$span = max(1, 3 - count($posts));
?>
<section class="section insights" id="insights" aria-labelledby="insights-title" data-header-theme="light">
  <div class="container">
    <header class="insights__head">
      <div class="insights__intro">
        <?= component('eyebrow', ['text' => $in['eyebrow']]) ?>
        <?= heading(2, $in['title'], $in['accent'], 'insights__title', 'insights-title', ['data-anim' => 'two-voice']) ?>
      </div>
      <?= component('button', ['label' => $in['link']['label'], 'href' => url($in['link']['url']), 'variant' => 'link', 'class' => 'insights__all']) ?>
    </header>

    <div class="insights__grid" data-anim="stagger" data-stagger="0.1">
      <?php foreach ($posts as $post): ?>
      <?= component('card-blog', ['post' => $post]) ?>
      <?php endforeach; ?>
      <?php if (count($posts) < 3): ?>
      <div class="insights__soon insights__soon--span-<?= $span ?>">
        <?= component('card-blog', ['soon' => $in['soon'] + ['url' => config('socials.linkedin')]]) ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
