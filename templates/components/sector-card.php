<?php
declare(strict_types=1);
/**
 * C36 Sector card (P12 card, extracted in P22 for the home sectors row and the /sectors/ hub): 3:4 photo with a bottom
 * gradient, hex icon, H3 whose link covers the whole card, summary, and the arrow tile in a bottom-right cut-out
 * (`.notch.notch--br`, a true mask). Styles: src/css/components/sector-card.css.
 * @var array       $sector  app/data/sectors.php entry (name, icon, summary, url, image, image_alt)
 * @var int|null    $level   Heading level (default 3)
 * @var string|null $sizes   <img> sizes (default '(min-width: 1024px) 320px, 76vw')
 * @var bool|null   $skew    Add the scroll-velocity skew (the home row; default false)
 * @var string|null $class
 */
$level = max(2, min(6, $level ?? 3));
$tag = 'h' . $level;
?>
<article class="sector-card on-dark<?= isset($class) ? ' ' . e($class) : '' ?>">
  <div class="sector-card__media notch notch--br"<?= !empty($skew) ? ' data-anim="velocity-skew"' : '' ?>>
    <?= img((string) ($sector['image'] ?? 'sectors/' . $sector['slug']), ['alt' => (string) ($sector['image_alt'] ?? $sector['name']), 'ratio' => '3/4', 'sizes' => $sizes ?? '(min-width: 1024px) 320px, 76vw', 'cover' => true, 'pattern' => false]) ?>
    <span class="sector-card__shade" aria-hidden="true"></span>
  </div>
  <div class="sector-card__body">
    <?= component('hex-tile', ['icon' => $sector['icon'], 'size' => 'sm']) ?>
    <<?= $tag ?> class="sector-card__title"><a class="sector-card__link" href="<?= e(url($sector['url'])) ?>"><?= e($sector['name']) ?></a></<?= $tag ?>>
    <p class="sector-card__summary"><?= e($sector['summary']) ?></p>
  </div>
  <span class="sector-card__arrow" aria-hidden="true"><?= icon('arrow-up-right', 'regular', ['size' => 22]) ?></span>
</article>
