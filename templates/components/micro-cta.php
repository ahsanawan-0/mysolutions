<?php
declare(strict_types=1);
/**
 * Micro-CTA row (docs/08 H4, docs/06 §6 S2): statement left · large arrow (data-anim="micro-arrow", points right,
 * P06 rotates it to point down and drops it as you scroll) · button right. Hairlines above and below.
 * Default copy: app/data/ui.php → micro_cta.
 * @var string|null $text
 * @var string|null $label   Button label
 * @var string|null $url     Button URL (site path or absolute)
 * @var bool|null   $dark    Render on a dark surface
 * @var string|null $class
 */
$d = ui('micro_cta');
$text = $text ?? $d['text'];
$label = $label ?? $d['label'];
$url = $url ?? $d['url'];
$classes = 'micro-cta' . (!empty($dark) ? ' micro-cta--dark on-dark' : '') . (isset($class) ? ' ' . $class : '');
?>
<div class="<?= e($classes) ?>">
  <p class="micro-cta__text"><?= e($text) ?></p>
  <span class="micro-cta__arrow" data-anim="micro-arrow" aria-hidden="true"><?= icon('arrow-right', 'regular', ['size' => 56]) ?></span>
  <div class="micro-cta__action">
    <?= component('button', ['label' => $label, 'href' => str_starts_with($url, '/') ? url($url) : $url, 'variant' => 'split', 'class' => !empty($dark) ? 'btn--glass' : null]) ?>
  </div>
</div>
