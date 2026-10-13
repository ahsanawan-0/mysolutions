<?php
declare(strict_types=1);
/**
 * C14 Avatar stack: 3–4 overlapping circles with a white ring.
 * @var array<int, array{image?: string, alt?: string, initials?: string}> $avatars
 *        image = img() manifest key (photo); initials = fallback text when there is no photo yet
 * @var int|null    $more    Extra count shown as "+N"
 * @var string|null $label   Short uppercase label beside the stack
 * @var string|null $class   e.g. 'avatar-stack--lg'
 */
$avatars = array_slice($avatars ?? [], 0, 4);
?>
<div class="avatar-stack<?= isset($class) ? ' ' . e($class) : '' ?>">
  <span class="avatar-stack__list" role="list"<?= isset($label) ? ' aria-label="' . e($label) . '"' : '' ?>>
<?php foreach ($avatars as $a): ?>
    <span class="avatar" role="listitem"<?= isset($a['alt']) && !isset($a['image']) ? ' aria-label="' . e($a['alt']) . '"' : '' ?>>
<?php if (isset($a['image'])): ?>
      <?= img($a['image'], ['alt' => $a['alt'] ?? '', 'decorative' => !isset($a['alt']), 'sizes' => '52px', 'ratio' => '1/1']) ?>
<?php else: ?>
      <span aria-hidden="true"><?= e($a['initials'] ?? '') ?></span>
<?php endif; ?>
    </span>
<?php endforeach; ?>
<?php if (!empty($more)): ?>
    <span class="avatar avatar--more" role="listitem">+<?= (int) $more ?></span>
<?php endif; ?>
  </span>
<?php if (isset($label)): ?>
  <span class="avatar-stack__label" aria-hidden="true"><?= e($label) ?></span>
<?php endif; ?>
</div>
