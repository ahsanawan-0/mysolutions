<?php
declare(strict_types=1);
/**
 * Marquee (docs/06 §3): a seamless ticker driven by src/js/anim/marquee.js (direction follows the scroll velocity,
 * hover slows, focus pauses, pause/play button for WCAG 2.2.2). Without JS or under reduced motion it renders as a
 * static wrapped list. The server prints ONE set of items; JS clones them for the loop.
 * @var string[]    $items      Words/phrases (escaped here)
 * @var string|null $label      Accessible name of the group (default "Highlights")
 * @var int|null    $speed      Pixels per second (default 60)
 * @var string|null $variant    'display' (large uppercase words) | null
 * @var bool|null   $dark       On a dark surface
 * @var string|null $class
 */
$items = $items ?? [];
$speed = (int) ($speed ?? 60);
$classes = 'marquee' . (($variant ?? null) === 'display' ? ' marquee--display' : '') . (!empty($dark) ? ' marquee--dark' : '') . (isset($class) ? ' ' . $class : '');
?>
<div class="<?= e($classes) ?>" data-anim="marquee" data-speed="<?= $speed ?>" role="group" aria-label="<?= e($label ?? 'Highlights') ?>">
  <ul class="marquee__track" data-marquee-track>
    <?php foreach ($items as $item): ?>
    <li><?= e($item) ?></li>
    <?php endforeach; ?>
  </ul>
  <button class="marquee__pause" type="button" data-marquee-pause aria-pressed="false" hidden>
    <?= icon('pause', 'regular', ['size' => 18]) ?><?= icon('play', 'regular', ['size' => 18]) ?>
    <span class="visually-hidden" data-marquee-label>Pause the moving text</span>
  </button>
</div>
