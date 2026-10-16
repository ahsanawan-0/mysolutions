<?php
declare(strict_types=1);
/**
 * Related services on a sub-service page (PROMPTS P21, docs/08 §7b item 8): the siblings + 1 cross-category service as
 * the P20 glass cards (hex tile in the service's category colour, H3 link over the whole card, summary, "Explore …",
 * corner-notch arrow). A cross-category card names its category. The template has already dropped draft services on
 * production. Copy: ui('related_services').
 * @var array       $items  [['sub' => services.php sub-service, 'category' => its category, 'cross' => bool], …]
 * @var string|null $id     Section id (default 'related-services')
 */
if (($items ?? []) === []) {
    return;
}
$id = $id ?? 'related-services';
$titleId = $id . '-title';
$t = ui('related_services');
?>
<section class="svc-subs svc-related" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="svc-subs__bg" aria-hidden="true"></div>
  <div class="container">
    <header class="svc-subs__head">
      <?= component('eyebrow', ['text' => $t['eyebrow']]) ?>
      <?= heading(2, $t['heading'], $t['accent'], 'svc-subs__title', $titleId, ['data-anim' => 'two-voice']) ?>
    </header>

    <ul class="svc-subs__grid svc-subs__grid--<?= count($items) ?>" role="list" data-anim="stagger">
      <?php foreach ($items as ['sub' => $sub, 'category' => $category, 'cross' => $cross]): ?>
      <li class="svc-subs__item">
        <article class="svc-card svc-card--<?= e($category['colour']) ?>" data-anim="tilt" data-tilt="4">
          <div class="svc-card__surface notch notch--br">
            <?= component('hex-tile', ['icon' => $sub['icon'], 'category' => $category['colour'], 'size' => 'lg', 'class' => 'svc-card__tile']) ?>
            <?php if ($cross): ?>
            <?= component('chip', ['label' => str_replace('{category}', $category['name'], $t['part_of']), 'variant' => $category['colour'], 'class' => 'svc-card__chip']) ?>
            <?php endif; ?>
            <h3 class="svc-card__title"><a class="svc-card__link" href="<?= e(url($sub['url'])) ?>"><?= e($sub['name']) ?></a></h3>
            <p class="svc-card__text"><?= e($sub['summary']) ?></p>
            <span class="svc-card__more" aria-hidden="true"><?= e(str_replace('{name}', $sub['name'], $t['explore'])) ?></span>
          </div>
          <span class="svc-card__arrow" aria-hidden="true"><?= icon('arrow-right', 'regular') ?></span>
        </article>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
