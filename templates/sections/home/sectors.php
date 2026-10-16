<?php
declare(strict_types=1);
/**
 * Home "Sectors We Serve" (docs/08 §4 H5, docs/04 §1.0 Innovax sticky panels, PROMPTS P12): the intro panel stays
 * on the left while the seven sector cards slide past it. `data-anim="h-scroll"` pins the section ≥ 1024 and
 * translates `[data-h-track]` by its overflow against `[data-h-viewport]`, filling `[data-h-progress]`; below 1024
 * and under reduced motion the track is a native scroll-snap row (motion.css). Behind it on desktop: the faint
 * `nodes` network (src/js/modules/nodes.js, hidden below 1024 so the canvas stays paused there).
 * Each card is the C36 sector-card component (3:4 photo with `velocity-skew`, hex icon, H3 link over the card, summary,
 * arrow tile in a bottom-right cut-out). Copy: app/data/home.php → sectors. Cards, photos and URLs: app/data/sectors.php.
 */
$s = home('sectors');
$sectors = sectors();
?>
<section class="section sectors" id="sectors" data-anim="h-scroll" aria-labelledby="sectors-title" data-header-theme="light">
  <div class="sectors__bg nodes" data-module="nodes" data-count="40" data-link="150" aria-hidden="true"></div>

  <div class="sectors__layout">
    <div class="sectors__intro">
      <?= component('eyebrow', ['text' => $s['eyebrow']]) ?>
      <?= heading(2, $s['title'], $s['accent'], 'sectors__title', 'sectors-title', ['data-anim' => 'two-voice']) ?>
      <p class="sectors__text"><?= e($s['text']) ?></p>
      <?= component('button', ['label' => $s['link']['label'], 'href' => url($s['link']['url']), 'variant' => 'link', 'class' => 'sectors__all']) ?>
    </div>

    <div class="sectors__scroller" data-h-viewport>
      <div class="sectors__clip">
      <ul class="sectors__track" data-h-track role="list" aria-label="Sectors we serve">
        <?php foreach ($sectors as $sector): ?>
        <li class="sectors__item" data-h-item>
          <?= component('sector-card', ['sector' => $sector, 'skew' => true]) ?>
        </li>
        <?php endforeach; ?>
      </ul>
      </div>
      <div class="sectors__progress" aria-hidden="true"><span class="sectors__progress-bar" data-h-progress></span></div>
    </div>
  </div>
</section>
