<?php
declare(strict_types=1);
/**
 * C11 Team cards (docs/04 §1.4 Innovax portrait card, PROMPTS P19): a scroll-snap slider of portrait cards, 4 visible
 * on desktop, with prev/next buttons and dots (src/js/modules/team-cards.js). Each card: the 4/5 portrait, a bottom
 * gradient, the name in white, the role in a white pill, and a share notch at the top-right whose round button opens
 * a small popover with the LinkedIn / e-mail links (a native popover; closed with Esc or an outside click).
 * Only real people with written permission (app/data/team.php). Without JS the track scrolls natively.
 * @var array<int, array{name: string, role: string, image: string, alt?: string, linkedin?: string, email?: string}> $members
 * @var string|null $label  aria-label for the slider region
 * @var string|null $class
 */
$members = $members ?? [];
if ($members === []) {
    return;
}
$uid = 'team-' . substr(md5(implode('|', array_column($members, 'name'))), 0, 6);
?>
<div class="team-cards<?= isset($class) ? ' ' . e($class) : '' ?>" data-module="team-cards" role="region" aria-roledescription="carousel" aria-label="<?= e($label ?? 'Our team') ?>">
  <div class="container team-cards__frame">
    <ul class="team-cards__track" role="list" data-team-track>
      <?php foreach ($members as $i => $m): $pop = $uid . '-' . $i; $hasLinks = !empty($m['linkedin']) || !empty($m['email']); ?>
      <li class="team-card" data-team-card>
        <div class="team-card__media<?= $hasLinks ? ' notch notch--tr' : '' ?>">
          <?= img($m['image'], ['alt' => $m['alt'] ?? ('Portrait of ' . $m['name']), 'ratio' => '4/5', 'sizes' => '(min-width: 1024px) 24vw, (min-width: 640px) 46vw, 90vw', 'cover' => true, 'pattern' => false]) ?>
          <div class="team-card__shade" aria-hidden="true"></div>
        </div>
        <?php if ($hasLinks): ?>
        <button class="team-card__share btn-icon" type="button" popovertarget="<?= e($pop) ?>" aria-label="<?= e('Contact ' . $m['name']) ?>">
          <?= icon('share-network', 'regular', ['size' => 18]) ?>
        </button>
        <div class="team-card__pop" id="<?= e($pop) ?>" popover>
          <?php if (!empty($m['linkedin'])): ?>
          <a class="team-card__pop-link" href="<?= e($m['linkedin']) ?>" rel="noopener" target="_blank"><?= icon('linkedin-logo', 'regular', ['size' => 18]) ?><span>LinkedIn</span></a>
          <?php endif; ?>
          <?php if (!empty($m['email'])): ?>
          <a class="team-card__pop-link" href="mailto:<?= e($m['email']) ?>"><?= icon('envelope-simple', 'regular', ['size' => 18]) ?><span>Email</span></a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="team-card__caption">
          <p class="team-card__name"><?= e($m['name']) ?></p>
          <span class="team-card__role"><?= e($m['role']) ?></span>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <div class="container team-cards__controls">
    <button class="btn-icon team-cards__btn" type="button" data-team-prev aria-label="Previous team members"><?= icon('caret-left', 'regular', ['size' => 20]) ?></button>
    <ol class="team-cards__dots" role="list" data-team-dots></ol>
    <button class="btn-icon team-cards__btn" type="button" data-team-next aria-label="Next team members"><?= icon('caret-right', 'regular', ['size' => 20]) ?></button>
  </div>
</div>
