<?php
declare(strict_types=1);
/**
 * C03 Button / link.
 * @var string      $label     Button text (required)
 * @var string|null $href      Renders <a> when set, otherwise <button>
 * @var string|null $variant   'primary' (default) | 'secondary' | 'glass' | 'split' | 'link'
 * @var string|null $icon      Phosphor icon name (regular weight). Default for split/link: 'arrow-up-right' / 'arrow-right'
 * @var string|null $size      'sm' | 'lg'
 * @var string|null $class     Extra classes (e.g. 'btn--secondary' with variant 'split' for a dark tile)
 * @var string|null $type      For <button>: 'button' (default) | 'submit'
 * @var array|null  $attrs     Extra attributes, e.g. ['aria-expanded' => 'false', 'data-anim' => 'fade-up']
 * @var bool|null   $disabled
 */
$variant = $variant ?? 'primary';
$attrs = $attrs ?? [];
$tag = isset($href) ? 'a' : 'button';

if ($variant === 'link') {
    $classes = 'link-arrow' . (isset($class) ? ' ' . $class : '');
    $iconName = $icon ?? 'arrow-right';
} else {
    $classes = 'btn';
    if ($variant === 'secondary') $classes .= ' btn--secondary';
    if ($variant === 'glass')     $classes .= ' btn--glass';
    if ($variant === 'split')     $classes .= ' btn--split';
    if (isset($size))             $classes .= ' btn--' . $size;
    if (isset($class))            $classes .= ' ' . $class;
    $iconName = $icon ?? ($variant === 'split' ? 'arrow-up-right' : null);
}

$attrString = ' class="' . e($classes) . '"';
if ($tag === 'a') {
    $attrString .= ' href="' . e($href) . '"';
} else {
    $attrString .= ' type="' . e($type ?? 'button') . '"';
    if (!empty($disabled)) $attrString .= ' disabled';
}
if ($tag === 'a' && !empty($disabled)) $attrString .= ' aria-disabled="true"';
foreach ($attrs as $name => $value) {
    $attrString .= ' ' . e($name) . '="' . e($value) . '"';
}
?>
<<?= $tag . $attrString ?>><?php
if ($variant === 'link'): ?><span class="link-arrow__label"><?= e($label) ?></span><?= icon($iconName, 'regular') ?><?php
elseif ($variant === 'split'): ?><span><?= e($label) ?></span><span class="btn__tile"><?= icon($iconName, 'regular') ?></span><?php
else: ?><?= e($label) ?><?= $iconName !== null ? icon($iconName, 'regular') : '' ?><?php
endif; ?></<?= $tag ?>>
