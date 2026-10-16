<?php
declare(strict_types=1);
/**
 * Sector "How we help" (PROMPTS P22, docs/08 §8): two-voice H2 and the paragraphs (rich(); the client-approved Our
 * Story text, plus sector-specific copy as it arrives) on the left of a two-column header, then the relevant
 * sub-services as C37 service cards (from the sector data's `services` slugs; drafts are skipped on production).
 * @var array  $help    ['heading', 'accent'?, 'paras' => string[]]
 * @var array  $items   [['sub' => …, 'category' => …], …] (already resolved and filtered by the template)
 * @var string $sector  Sector name (for the cards' list label)
 */
$id = 'how-we-help';
$titleId = $id . '-title';
?>
<section class="svc-subs sector-help" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="svc-subs__bg" aria-hidden="true"></div>
  <div class="container">
    <header class="sector-help__head">
      <?= heading(2, (string) $help['heading'], $help['accent'] ?? null, 'svc-subs__title sector-help__title', $titleId, ['data-anim' => 'two-voice']) ?>
      <div class="sector-help__copy" data-anim="stagger">
        <?php foreach ($help['paras'] ?? [] as $i => $para): ?>
        <p<?= $i === 0 ? ' class="sector-help__first"' : '' ?>><?= rich($para) ?></p>
        <?php endforeach; ?>
      </div>
    </header>

    <?php if (($items ?? []) !== []): ?>
    <h3 class="sector-help__label" id="<?= e($id) ?>-services"><?= e(ui('sector_page.services')) ?></h3>
    <ul class="svc-subs__grid svc-subs__grid--<?= count($items) ?>" role="list" aria-labelledby="<?= e($id) ?>-services" data-anim="stagger">
      <?php foreach ($items as ['sub' => $sub, 'category' => $category]): ?>
      <li class="svc-subs__item">
        <?= component('service-card', ['sub' => $sub, 'category' => $category, 'level' => 4]) ?>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
  </div>
</section>
