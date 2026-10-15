<?php
declare(strict_types=1);
/**
 * Home testimonials (docs/08 §4 H9, PROMPTS P15): the C16 marquee of permissioned client quotes. The section is
 * absent from the page while app/data/testimonials.php is empty (docs/13 §7: genuine, attributable, permissioned
 * quotes only). Copy: app/data/home.php → testimonials.
 */
$items = testimonials();
if (!$items) return;
$t = home('testimonials');
?>
<section class="section testimonials-section" id="testimonials" aria-labelledby="testimonials-title" data-header-theme="light">
  <div class="container">
    <header class="testimonials-section__head">
      <?= component('eyebrow', ['text' => $t['eyebrow']]) ?>
      <?= heading(2, $t['title'], $t['accent'], 'testimonials-section__title', 'testimonials-title', ['data-anim' => 'two-voice']) ?>
    </header>
  </div>
  <?= component('testimonials', ['items' => $items]) ?>
</section>
