<?php
declare(strict_types=1);
/**
 * Grid-wave background art (docs/04 §6): azure perspective grid-wave, rebuilt as line art in
 * public/assets/img/art/grid-wave.svg (scripts/art.mjs) and referenced with <use>. Decorative only.
 * Colours come from the --gw-h / --gw-v custom properties on .grid-wave (src/css/components/grid-wave.css).
 * @var string|null $class     Extra classes on the <svg> (position it from the parent component)
 * @var bool|null   $animated  Slow horizontal drift (CSS keyframes; off under prefers-reduced-motion)
 * @var string|null $align     preserveAspectRatio alignment: 'top' | 'middle' (default) | 'bottom'
 */
$align = ['top' => 'xMidYMin', 'bottom' => 'xMidYMax'][$align ?? 'middle'] ?? 'xMidYMid';
$classes = 'grid-wave' . (!empty($animated) ? ' grid-wave--animated' : '') . (isset($class) ? ' ' . $class : '');
?>
<svg class="<?= e($classes) ?>" viewBox="0 0 1600 400" preserveAspectRatio="<?= $align ?> slice" aria-hidden="true" focusable="false"><use class="grid-wave__art" href="<?= e(asset('img/art/grid-wave.svg')) ?>#grid-wave"></use></svg>
