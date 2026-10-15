<?php
declare(strict_types=1);
/**
 * Service block `list` (PROMPTS P20 for P21, e.g. "Our Tender & Bid Writing Services", "What Does Our Review Cover?"):
 * two-voice H2, "Sub" line, intro paragraphs, the icon cards (hex tile + H3 + text; 3 × 2 grid ≥ 1024), closing
 * paragraph. Motion: `stagger` on the grid, `tilt` on each card. Not used by the category pages; shown on
 * /styleguide/service-blocks/.
 * @var array      $block  ['type' => 'list', 'id', 'heading', 'accent'?, 'sub'?, 'paras'? => string[],
 *                         'items' => [['icon', 'title', 'text'], …], 'outro'?, 'variant'? => 'default'|'mist']
 * @var array|null $ctx    The service category: colour for the hex tiles
 */
$id = (string) $block['id'];
$titleId = $id . '-title';
$colour = $ctx['colour'] ?? 'bid';
$mist = ($block['variant'] ?? 'default') === 'mist';
?>
<section class="svc-list<?= $mist ? ' svc-list--mist' : '' ?>" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="container">
    <header class="svc-list__head">
      <?= heading(2, (string) $block['heading'], $block['accent'] ?? null, 'svc-list__title', $titleId, ['data-anim' => 'two-voice']) ?>
      <?php if (!empty($block['sub'])): ?>
      <p class="svc-sub" data-anim="fade-up"><?= e($block['sub']) ?></p>
      <?php endif; ?>
      <?php foreach ($block['paras'] ?? [] as $para): ?>
      <p class="svc-list__intro" data-anim="fade-up"><?= rich($para) ?></p>
      <?php endforeach; ?>
    </header>

    <ul class="svc-list__grid" role="list" data-anim="stagger">
      <?php foreach ($block['items'] ?? [] as $item): ?>
      <li class="svc-list__item">
        <div class="svc-list__card" data-anim="tilt" data-tilt="4">
          <?= component('hex-tile', ['icon' => $item['icon'], 'category' => $colour, 'class' => 'svc-list__tile']) ?>
          <h3 class="svc-list__item-title"><?= e($item['title']) ?></h3>
          <p class="svc-list__text"><?= rich($item['text']) ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>

    <?php if (!empty($block['outro'])): ?>
    <p class="svc-list__outro" data-anim="fade-up"><?= rich($block['outro']) ?></p>
    <?php endif; ?>
  </div>
</section>
