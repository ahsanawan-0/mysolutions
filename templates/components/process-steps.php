<?php
declare(strict_types=1);
/**
 * C15 Process steps (docs/03 C15, docs/04 §3 "Sun Over" numbered list, PROMPTS P13): the 01–05 numbered list with a
 * vertical progress line beside two overlapping images and an icon chip that change with the active step.
 * `data-anim="pin-steps"`: ≥ 1024 the block pins for N × (60vh × data-pin-length) and the active step / panel switch
 * at progress thresholds while the line fills; below 1024 the list simply stacks and each step activates as it
 * passes 60 % of the viewport; under reduced motion and without JS every step is fully visible and the first panel
 * shows. Used by the home page (compact) and the About page (full, P19).
 *
 * @var string      $eyebrow
 * @var string      $heading   Grotesk part of the two-voice H2
 * @var string|null $accent    Italic accent words
 * @var string|null $id        id for the H2 (the section's aria-labelledby)
 * @var array<int, array{title: string, icon: string, text: string, detail?: string[], image?: string, image_alt?: string, detail_image?: string, detail_alt?: string}> $steps
 * @var string|null $variant   'compact' (default, one line per step) | 'full' (adds the detail paragraphs)
 * @var array|null  $cta       ['label' => …, 'url' => …] link under the list
 * @var string|null $class     Extra classes on the root (e.g. 'on-dark')
 * @var float|null  $pin_length  Scroll length per step as a fraction of the viewport (default 0.6)
 * @var string|null $art       'swirl' → the azure speed-line swirl (public/assets/img/art/swirl.svg, inlined) drawn
 *                             on scroll behind the content (docs/04 §6, P16)
 */
$variant = $variant ?? 'compact';
$id = $id ?? 'process-steps-title';
$pinLength = $pin_length ?? 0.6;
?>
<div class="process-steps process-steps--<?= e($variant) ?><?= isset($class) ? ' ' . e($class) : '' ?>" data-anim="pin-steps" data-pin-length="<?= e((string) $pinLength) ?>">
  <?php if (($art ?? null) === 'swirl'): ?>
  <div class="process-steps__art" aria-hidden="true"><?= inline_svg('img/art/swirl.svg', ['title' => false, 'attrs' => ['data-anim' => 'draw']]) ?></div>
  <?php endif; ?>
  <div class="process-steps__inner container">

    <div class="process-steps__copy">
      <?= component('eyebrow', ['text' => $eyebrow]) ?>
      <?= heading(2, $heading, $accent ?? null, 'process-steps__title', $id, ['data-anim' => 'two-voice']) ?>

      <ol class="process-steps__list">
        <span class="process-steps__line" aria-hidden="true"><span class="process-steps__fill" data-step-progress="y"></span></span>
        <?php foreach ($steps as $i => $step): ?>
        <li class="process-steps__step<?= $i === 0 ? ' is-active' : '' ?>" data-step>
          <span class="process-steps__n" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
          <div class="process-steps__body">
            <h3 class="process-steps__step-title"><?= e($step['title']) ?></h3>
            <p class="process-steps__text"><?= e($step['text']) ?></p>
            <?php if ($variant === 'full' && !empty($step['detail'])): ?>
            <?php foreach ((array) $step['detail'] as $para): ?>
            <p class="process-steps__detail"><?= e($para) ?></p>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>

      <?php if (!empty($cta)): ?>
      <?= component('button', ['label' => $cta['label'], 'href' => url($cta['url']), 'variant' => 'link', 'class' => 'process-steps__cta']) ?>
      <?php endif; ?>
    </div>

    <div class="process-steps__visual" aria-hidden="true">
      <?php foreach ($steps as $i => $step): ?>
      <div class="process-steps__panel<?= $i === 0 ? ' is-active' : '' ?>" data-step-panel>
        <div class="process-steps__img process-steps__img--a">
          <?= img($step['image'] ?? 'process/step-' . sprintf('%02d', $i + 1), ['alt' => '', 'decorative' => true, 'ratio' => '4/5', 'sizes' => '(min-width: 1024px) 30vw, 60vw', 'cover' => true, 'pattern' => false]) ?>
        </div>
        <div class="process-steps__img process-steps__img--b">
          <?= img($step['detail_image'] ?? 'process/step-' . sprintf('%02d', $i + 1) . '-detail', ['alt' => '', 'decorative' => true, 'ratio' => '1/1', 'sizes' => '(min-width: 1024px) 20vw, 40vw', 'cover' => true, 'pattern' => false]) ?>
        </div>
        <span class="process-steps__chip"><?= icon($step['icon'], 'light', ['size' => 28]) ?></span>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</div>
