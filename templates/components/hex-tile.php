<?php
declare(strict_types=1);
/**
 * C07 Hex icon tile: rounded hexagon holding a Phosphor light icon.
 * @var string      $icon      Phosphor icon name (light weight)
 * @var string|null $category  'bid' | 'compliance' | 'digital' → category colour
 * @var string|null $size      'sm' (48px) | 'lg' (88px); default 64px
 * @var string|null $label     Accessible label; omit when a visible label sits next to the tile
 * @var string|null $class
 */
$classes = 'hex-tile';
if (!empty($category)) $classes .= ' hex-tile--' . $category;
if (!empty($size))     $classes .= ' hex-tile--' . $size;
if (!empty($class))    $classes .= ' ' . $class;
?>
<span class="<?= e($classes) ?>"><?= icon($icon, 'light', isset($label) ? ['label' => $label] : []) ?></span>
