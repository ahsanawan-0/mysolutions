<?php
declare(strict_types=1);
/**
 * Home FAQ teaser (docs/08 §4 H10, PROMPTS P15): intro column (eyebrow, two-voice H2 "Questions, Answered", a line,
 * "View all FAQs" → /faq/) beside the C17 accordion of the `home`-tagged questions from app/data/faqs.php. The
 * answers are native <details>, so they are in the HTML and work without JS. Copy: app/data/home.php → faq.
 */
$f = home('faq');
$items = faqs('home');
?>
<section class="section faq" id="faq" aria-labelledby="faq-title">
  <div class="container faq__layout">
    <div class="faq__intro">
      <?= component('eyebrow', ['text' => $f['eyebrow']]) ?>
      <?= heading(2, $f['title'], $f['accent'], 'faq__title', 'faq-title', ['data-anim' => 'two-voice']) ?>
      <p class="faq__text" data-anim="fade-up"><?= e($f['text']) ?></p>
      <div data-anim="fade-up" data-delay="0.1">
        <?= component('button', ['label' => $f['button']['label'], 'href' => url($f['button']['url']), 'variant' => 'split', 'class' => 'btn--secondary']) ?>
      </div>
    </div>
    <div class="faq__list" data-anim="fade-up">
      <?= component('accordion', ['items' => $items, 'name' => 'home-faq', 'level' => 3, 'id_prefix' => 'faq']) ?>
    </div>
  </div>
</section>
