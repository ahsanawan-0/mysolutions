<?php
declare(strict_types=1);
/**
 * Service block `text` (PROMPTS P20/P21, docs/08 §7a): two-voice H2, the client's "Sub" line as a styled paragraph
 * (not an H3), the paragraphs (rich()), an optional split button. Variants:
 *  - default / mist  text column (max 68ch); with `image` a sticky notched photo on the right (`.notch--tr`, the
 *                    category hex tile sits in the cut-out), `img-reveal`; `mist` = the Mist background.
 *  - band            the category's "current standards / regulation" band: Navy 900, paragraphs in two columns
 *                    ≥ 1024, a visible "Last updated <date>" (`updated`, Y-m-d), `read-along` on the first paragraph.
 * @var array      $block  ['type' => 'text', 'id', 'heading', 'accent'?, 'sub'?, 'paras' => string[], 'image'? =>
 *                         ['key', 'alt'], 'variant'? => 'default'|'mist'|'band', 'updated'?, 'button'? => ['label', 'url']]
 * @var array|null $ctx    The service category (services.php): colour + icon for the hex tile in the notch
 */
$variant = in_array($block['variant'] ?? 'default', ['default', 'mist', 'band'], true) ? ($block['variant'] ?? 'default') : 'default';
$band = $variant === 'band';
$id = (string) $block['id'];
$titleId = $id . '-title';
$image = !$band ? ($block['image'] ?? null) : null;
$paras = $block['paras'] ?? [];
$updated = !empty($block['updated']) ? new DateTimeImmutable((string) $block['updated']) : null;
$classes = 'svc-text svc-text--' . $variant . ($image ? ' svc-text--with-image' : '') . ($band ? ' on-dark' : '');
?>
<section class="<?= e($classes) ?>" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="<?= $band ? 'dark' : 'light' ?>">
  <div class="container svc-text__grid">
    <div class="svc-text__main">
      <header class="svc-text__head">
        <?= heading(2, (string) $block['heading'], $block['accent'] ?? null, 'svc-text__title', $titleId, ['data-anim' => 'two-voice']) ?>
        <?php if (!empty($block['sub'])): ?>
        <p class="svc-sub" data-anim="fade-up"><?= e($block['sub']) ?></p>
        <?php endif; ?>
        <?php if ($updated): ?>
        <p class="svc-text__updated" data-anim="fade-up"><?= icon('calendar-check', 'light', ['size' => 18]) ?><span>Last updated <time datetime="<?= e($updated->format('Y-m-d')) ?>"><?= e($updated->format('j F Y')) ?></time></span></p>
        <?php endif; ?>
      </header>

      <div class="svc-text__copy">
        <?php foreach ($paras as $i => $para): ?>
        <p<?= $band && $i === 0 ? ' class="svc-text__lead" data-anim="read-along"' : ' data-anim="fade-up"' ?>><?= rich($para) ?></p>
        <?php endforeach; ?>
      </div>

      <?php if (!empty($block['button'])): ?>
      <div class="svc-text__actions" data-anim="fade-up">
        <?= component('button', ['label' => $block['button']['label'], 'href' => url($block['button']['url']), 'variant' => 'split', 'class' => $band ? 'btn--glass' : 'btn--secondary']) ?>
      </div>
      <?php endif; ?>
    </div>

    <?php if ($image): ?>
    <div class="svc-text__figure">
      <div class="svc-text__image notch notch--tr" data-anim="img-reveal">
        <?= img((string) $image['key'], ['alt' => (string) $image['alt'], 'ratio' => '4/5', 'sizes' => '(min-width: 1024px) 40vw, 100vw', 'cover' => true, 'pattern' => false]) ?>
      </div>
      <?php if (!empty($ctx['icon'])): ?>
      <span class="svc-text__tab" aria-hidden="true"><?= component('hex-tile', ['icon' => $ctx['icon'], 'category' => $ctx['colour'] ?? null]) ?></span>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
