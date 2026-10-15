<?php
declare(strict_types=1);
/**
 * Home "How Can We Help?" triage row (docs/08 §4 H8, docs/04 §8 strategicproposals triage, PROMPTS P15): a navy band
 * with three question-led glass cards, each with one intent-led action (call, book, explore). The eyebrow is the
 * section's heading; cards `stagger` in and `tilt` under the pointer on desktop. Copy: app/data/home.php → triage.
 */
$t = home('triage');
?>
<section class="section triage on-dark" id="help" aria-labelledby="triage-title" data-header-theme="dark">
  <div class="triage__glow" aria-hidden="true"></div>
  <div class="container">
    <?= component('eyebrow', ['text' => $t['eyebrow'], 'tag' => 'h2', 'id' => 'triage-title', 'class' => 'triage__eyebrow']) ?>
    <ul class="triage__cards" data-anim="stagger" data-stagger="0.1" role="list">
      <?php foreach ($t['cards'] as $card): ?>
      <?php $href = $card['url'] === 'tel:' ? 'tel:' . config('phone_e164') : (str_starts_with($card['url'], '/') ? url($card['url']) : $card['url']); ?>
      <li class="triage__item">
        <article class="triage-card glass-thin-dark" data-anim="tilt" data-tilt="4">
          <?= component('hex-tile', ['icon' => $card['icon'], 'size' => 'sm']) ?>
          <div class="triage-card__body">
            <h3 class="triage-card__title"><?= e($card['title']) ?></h3>
            <p class="triage-card__text"><?= e($card['text']) ?></p>
          </div>
          <div class="triage-card__action">
            <?php if ($card['style'] === 'primary'): ?>
            <?= component('button', ['label' => $card['label'], 'href' => $href, 'icon' => 'phone']) ?>
            <?php elseif ($card['style'] === 'split'): ?>
            <?= component('button', ['label' => $card['label'], 'href' => $href, 'variant' => 'split', 'class' => 'btn--glass']) ?>
            <?php else: ?>
            <?= component('button', ['label' => $card['label'], 'href' => $href, 'variant' => 'link']) ?>
            <?php endif; ?>
          </div>
        </article>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
