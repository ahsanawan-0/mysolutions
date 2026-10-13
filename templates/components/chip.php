<?php
declare(strict_types=1);
/**
 * Chip / pill.
 * @var string      $label
 * @var string|null $href      Renders <a> when set, otherwise <span>
 * @var string|null $variant   'neutral' (default) | 'outline' | 'bid' | 'compliance' | 'digital' | 'solid-bid' | 'solid-compliance' | 'solid-digital'
 * @var string|null $icon      Phosphor icon name (regular weight), shown before the label
 * @var bool|null   $dot       Show a colour dot before the label
 * @var string|null $class
 */
$variant = $variant ?? 'neutral';
$classes = 'chip' . ($variant !== 'neutral' ? ' chip--' . $variant : '') . (isset($class) ? ' ' . $class : '');
$tag = isset($href) ? 'a' : 'span';
?>
<<?= $tag ?> class="<?= e($classes) ?>"<?= isset($href) ? ' href="' . e($href) . '"' : '' ?>><?php
if (!empty($dot)): ?><span class="chip__dot" aria-hidden="true"></span><?php endif;
if (!empty($icon)): echo icon($icon, 'regular'); endif;
?><?= e($label) ?></<?= $tag ?>>
