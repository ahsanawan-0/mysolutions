<?php
declare(strict_types=1);
/**
 * C37 Service card (P20 sub-service cards, P21 related services, P22 sector "How we help"): a frosted glass card with
 * the service's hex tile in its category colour, an H3 whose link covers the whole card, the card text, "Explore …"
 * and a round arrow button in a concave cut-out at the bottom-right (`.notch--br` on the surface; the button is a
 * sibling outside the mask). `tilt` on desktop pointers. Styles: src/css/pages/service.css (.svc-card).
 * @var array       $sub       services.php sub-service (name, icon, summary, url)
 * @var array       $category  its category (colour, name)
 * @var string|null $text      Card text (default the sub-service summary)
 * @var bool|null   $cross     Show "Part of <category>" (a card outside the page's own category)
 * @var int|null    $level     Heading level (default 3)
 */
$t = ui('related_services');
$level = max(2, min(6, $level ?? 3));
$tag = 'h' . $level;
$colour = $category['colour'] ?? 'bid';
?>
<article class="svc-card svc-card--<?= e($colour) ?>" data-anim="tilt" data-tilt="4">
  <div class="svc-card__surface notch notch--br">
    <?= component('hex-tile', ['icon' => $sub['icon'], 'category' => $colour, 'size' => 'lg', 'class' => 'svc-card__tile']) ?>
    <?php if (!empty($cross)): ?>
    <?= component('chip', ['label' => str_replace('{category}', $category['name'], $t['part_of']), 'variant' => $colour, 'class' => 'svc-card__chip']) ?>
    <?php endif; ?>
    <<?= $tag ?> class="svc-card__title"><a class="svc-card__link" href="<?= e(url($sub['url'])) ?>"><?= e($sub['name']) ?></a></<?= $tag ?>>
    <p class="svc-card__text"><?= e($text ?? $sub['summary']) ?></p>
    <span class="svc-card__more" aria-hidden="true"><?= e(str_replace('{name}', $sub['name'], $t['explore'])) ?></span>
  </div>
  <span class="svc-card__arrow" aria-hidden="true"><?= icon('arrow-right', 'regular') ?></span>
</article>
