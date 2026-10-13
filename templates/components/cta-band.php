<?php
declare(strict_types=1);
/**
 * C21 CTA band: Navy 900 → Azure 800 gradient, animated grid-wave layer, large faded logo mark at the right
 * (the brand mark SVG inlined; P06 drives data-anim="cta-mark" = docs/06 §6 S10 rise + rotate).
 * Default copy: app/data/ui.php → cta_band. Every key can be overridden per page.
 * @var string|null $eyebrow
 * @var string|null $heading        Line 1 of the two-voice heading
 * @var string|null $accent         Italic accent words (line 2)
 * @var string|null $text
 * @var array|null  $primary        [label, url]
 * @var array|null  $secondary      [label, url]; default = phone link. Pass false to hide
 * @var bool|null   $show_whatsapp  Adds a "Chat on WhatsApp" glass button
 * @var int|null    $level          Heading level (default 2)
 * @var string|null $class
 */
$d = ui('cta_band');
$eyebrow = $eyebrow ?? $d['eyebrow'];
$heading = $heading ?? $d['heading'];
$accent = $accent ?? $d['accent'];
$text = $text ?? $d['text'];
$primary = $primary ?? $d['primary'];
$secondary = $secondary ?? $d['secondary'];
$level = $level ?? 2;
$id = 'cta-' . substr(md5($heading . $accent), 0, 6);
$href = static fn (string $u): string => str_starts_with($u, '/') ? url($u) : $u;
?>
<section class="cta-band on-dark<?= isset($class) ? ' ' . e($class) : '' ?>" aria-labelledby="<?= e($id) ?>">
  <div class="cta-band__bg" aria-hidden="true">
    <?= component('grid-wave', ['class' => 'cta-band__wave', 'animated' => true, 'align' => 'bottom']) ?>
    <?= inline_svg('brand/logo-mark.svg', ['prefix' => 'cta', 'class' => 'cta-band__mark', 'title' => false, 'attrs' => ['data-anim' => 'cta-mark']]) ?>
  </div>
  <div class="container cta-band__inner">
    <div class="cta-band__copy">
      <?= component('eyebrow', ['text' => $eyebrow]) ?>
      <?= heading($level, $heading, $accent, 'cta-band__heading', $id) ?>
      <p class="cta-band__text"><?= e($text) ?></p>
    </div>
    <div class="cta-band__actions">
      <?= component('button', ['label' => $primary[0], 'href' => $href($primary[1])]) ?>
      <?php if ($secondary !== false): ?>
      <?= component('button', ['label' => $secondary[0], 'href' => $href($secondary[1]), 'variant' => 'glass', 'icon' => 'phone']) ?>
      <?php endif; ?>
      <?php if (!empty($show_whatsapp)): ?>
      <?= component('button', ['label' => $d['whatsapp'][0], 'href' => $d['whatsapp'][1], 'variant' => 'glass', 'icon' => 'whatsapp-logo', 'attrs' => ['rel' => 'noopener']]) ?>
      <?php endif; ?>
    </div>
  </div>
</section>
