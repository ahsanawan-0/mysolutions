<?php
declare(strict_types=1);
/**
 * C21 CTA band: Navy 900 → Azure 800 gradient, animated grid-wave layer, large faded logo mark at the right
 * (the brand mark SVG inlined; P06 drives data-anim="cta-mark" = docs/06 §6 S10 rise + rotate).
 * Default copy: app/data/ui.php → cta_band. Every key can be overridden per page.
 * @var string|null $eyebrow
 * @var string|null $heading        Line 1 of the two-voice heading
 * @var string|null $accent         Accent words (line 2, brand blue)
 * @var string|null $sub            Line under the heading (the client's closing sub-heading, P20)
 * @var string|array|null $text     One paragraph, or a list of paragraphs (P20: the client's closing copy; rich():
 *                                   **bold** and [internal links])
 * @var string|null $strong         Bold closing line under the paragraphs (P20)
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
$paras = is_array($text ?? null) ? $text : [$text ?? $d['text']];
$long = count($paras) > 1 || !empty($sub) || !empty($strong);   // the client's longer closing copy: wider column
$primary = $primary ?? $d['primary'];
$secondary = $secondary ?? $d['secondary'];
$level = $level ?? 2;
$id = 'cta-' . substr(md5($heading . $accent), 0, 6);
$href = static fn (string $u): string => str_starts_with($u, '/') ? url($u) : $u;
?>
<section class="cta-band on-dark<?= $long ? ' cta-band--long' : '' ?><?= isset($class) ? ' ' . e($class) : '' ?>" aria-labelledby="<?= e($id) ?>" data-header-theme="dark">
  <div class="cta-band__bg" aria-hidden="true">
    <?= component('grid-wave', ['class' => 'cta-band__wave', 'animated' => true, 'align' => 'bottom']) ?>
    <?= inline_svg('brand/logo-mark.svg', ['prefix' => 'cta', 'class' => 'cta-band__mark', 'title' => false, 'attrs' => ['data-anim' => 'cta-mark']]) ?>
  </div>
  <div class="container cta-band__inner">
    <div class="cta-band__copy">
      <?= component('eyebrow', ['text' => $eyebrow]) ?>
      <?= heading($level, $heading, $accent, 'cta-band__heading', $id) ?>
      <?php if (!empty($sub)): ?>
      <p class="cta-band__sub"><?= e($sub) ?></p>
      <?php endif; ?>
      <?php foreach ($paras as $para): ?>
      <p class="cta-band__text"><?= rich($para) ?></p>
      <?php endforeach; ?>
      <?php if (!empty($strong)): ?>
      <p class="cta-band__strong"><strong><?= e($strong) ?></strong></p>
      <?php endif; ?>
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
