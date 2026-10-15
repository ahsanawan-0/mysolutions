<?php
declare(strict_types=1);
/**
 * Service block `subs` (PROMPTS P20, docs/08 §7a item 4): one large glass card per sub-service of the category,
 * generated from app/data/services.php. Each card: category hex tile, H3 (the whole card is one link), the card text
 * (the client's tagline from `taglines`, else the sub-service summary), "Explore <name>", and a round arrow button in
 * a concave cut-out at the bottom-right (`.notch--br` on the surface; the button is a sibling outside the mask).
 * Motion: `stagger` on the grid, `tilt` on each card (desktop pointer only).
 * @var array $block  ['type' => 'subs', 'id', 'heading', 'accent'?, 'intro'?, 'taglines'? => [sub slug => text]]
 * @var array $ctx    The service category (services.php): subs, colour
 */
$id = (string) $block['id'];
$titleId = $id . '-title';
$subs = $ctx['subs'] ?? [];
$colour = $ctx['colour'] ?? 'bid';
$taglines = $block['taglines'] ?? [];
?>
<section class="svc-subs" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="svc-subs__bg" aria-hidden="true"></div>
  <div class="container">
    <header class="svc-subs__head">
      <?= heading(2, (string) $block['heading'], $block['accent'] ?? null, 'svc-subs__title', $titleId, ['data-anim' => 'two-voice']) ?>
      <?php if (!empty($block['intro'])): ?>
      <p class="svc-subs__intro" data-anim="fade-up"><?= rich($block['intro']) ?></p>
      <?php endif; ?>
    </header>

    <ul class="svc-subs__grid svc-subs__grid--<?= count($subs) ?>" role="list" data-anim="stagger">
      <?php foreach ($subs as $sub): ?>
      <li class="svc-subs__item">
        <article class="svc-card svc-card--<?= e($colour) ?>" data-anim="tilt" data-tilt="4">
          <div class="svc-card__surface notch notch--br">
            <?= component('hex-tile', ['icon' => $sub['icon'], 'category' => $colour, 'size' => 'lg', 'class' => 'svc-card__tile']) ?>
            <h3 class="svc-card__title"><a class="svc-card__link" href="<?= e(url($sub['url'])) ?>"><?= e($sub['name']) ?></a></h3>
            <p class="svc-card__text"><?= e($taglines[$sub['slug']] ?? $sub['summary']) ?></p>
            <span class="svc-card__more" aria-hidden="true">Explore <?= e($sub['name']) ?></span>
          </div>
          <span class="svc-card__arrow" aria-hidden="true"><?= icon('arrow-right', 'regular') ?></span>
        </article>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
