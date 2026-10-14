<?php
declare(strict_types=1);
/**
 * C16 Testimonials marquee (docs/08 §4 H9, docs/04 §1 Innovax marquee, PROMPTS P15): quote cards that drift sideways
 * through an edge-fade mask (data-anim="marquee": direction follows the scroll, hover slows, focus pauses, pause
 * button for WCAG 2.2.2). Without JS or under reduced motion the cards sit in a wrapped grid. Renders nothing without
 * items. Testimonials are plain visible quotes: never Review / AggregateRating schema (docs/10 §6, docs/13 §7).
 * @var array       $items   app/data/testimonials.php entries: quote, name, role, company, sector, permission_date
 * @var string|null $label   Accessible name of the group (default 'Client testimonials')
 * @var int|null    $speed   Pixels per second (default 40)
 * @var string|null $class
 */
$items = $items ?? [];
if (!$items) return;
$sectorNames = array_column(sectors(), 'name', 'slug');
$speed = (int) ($speed ?? 40);
?>
<div class="testimonials<?= isset($class) ? ' ' . e($class) : '' ?>" data-anim="marquee" data-speed="<?= $speed ?>" role="group" aria-label="<?= e($label ?? 'Client testimonials') ?>">
  <ul class="testimonials__track" data-marquee-track>
    <?php foreach ($items as $t): ?>
    <li class="testimonials__item">
      <figure class="testimonial">
        <span class="testimonial__mark" aria-hidden="true"><?= icon('quotes', 'light', ['size' => 28]) ?></span>
        <blockquote class="testimonial__quote"><p><?= e($t['quote']) ?></p></blockquote>
        <figcaption class="testimonial__by">
          <span class="testimonial__who">
            <span class="testimonial__name"><?= e($t['name']) ?></span>
            <?php $line = implode(', ', array_filter([$t['role'] ?? null, $t['company'] ?? null])); ?>
            <?php if ($line !== ''): ?><span class="testimonial__role"><?= e($line) ?></span><?php endif; ?>
          </span>
          <?php if (!empty($t['sector']) && isset($sectorNames[$t['sector']])): ?>
          <?= component('chip', ['label' => $sectorNames[$t['sector']], 'variant' => 'outline', 'class' => 'testimonial__sector']) ?>
          <?php endif; ?>
        </figcaption>
      </figure>
    </li>
    <?php endforeach; ?>
  </ul>
  <button class="marquee__pause testimonials__pause" type="button" data-marquee-pause data-label-pause="Pause the moving testimonials" data-label-play="Play the moving testimonials" aria-pressed="false" hidden>
    <?= icon('pause', 'regular', ['size' => 18]) ?><?= icon('play', 'regular', ['size' => 18]) ?>
    <span class="visually-hidden" data-marquee-label>Pause the moving testimonials</span>
  </button>
</div>
