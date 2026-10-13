<?php
declare(strict_types=1);
/**
 * C01 Eyebrow: hex bullet + uppercase label.
 * @var string      $text   Label text (required)
 * @var string|null $class  Extra classes, e.g. 'eyebrow--muted'
 * @var string|null $tag    Element: 'p' (default) or 'span'
 */
$tag = $tag ?? 'p';
?>
<<?= $tag ?> class="eyebrow<?= isset($class) ? ' ' . e($class) : '' ?>"><?= e($text) ?></<?= $tag ?>>
