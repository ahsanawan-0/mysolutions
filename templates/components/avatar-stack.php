<?php
declare(strict_types=1);
/**
 * C14 Avatar stack: 3–4 overlapping circles with a white ring.
 * @var array<int, array{image?: string, alt?: string, initials?: string, icon?: string, category?: string}> $avatars
 *        image = img() manifest key (photo); initials = fallback text when there is no photo yet;
 *        icon (+ optional category 'bid'|'compliance'|'digital') = a small hex tile (C07) in the circle, e.g. a
 *        sector icon where no people photos exist (P10 approach section). alt names the item for screen readers.
 * @var int|null    $more    Extra count shown as "+N"
 * @var string|null $label   Short uppercase label beside the stack
 * @var string|null $class   e.g. 'avatar-stack--lg' | 'avatar-stack--xl' (56px)
 */
$avatars = array_slice($avatars ?? [], 0, 4);
?>
<div class="avatar-stack<?= isset($class) ? ' ' . e($class) : '' ?>">
  <span class="avatar-stack__list" role="list"<?= isset($label) ? ' aria-label="' . e($label) . '"' : '' ?>>
<?php foreach ($avatars as $a): ?>
    <span class="avatar<?= isset($a['icon']) ? ' avatar--icon' : '' ?>" role="listitem"<?= isset($a['alt']) && !isset($a['image']) ? ' aria-label="' . e($a['alt']) . '"' : '' ?>>
<?php if (isset($a['image'])): ?>
      <?= img($a['image'], ['alt' => $a['alt'] ?? '', 'decorative' => !isset($a['alt']), 'sizes' => '52px', 'ratio' => '1/1']) ?>
<?php elseif (isset($a['icon'])): ?>
      <?= component('hex-tile', ['icon' => $a['icon'], 'category' => $a['category'] ?? null]) ?>
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
