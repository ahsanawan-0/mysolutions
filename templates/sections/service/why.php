<?php
declare(strict_types=1);
/**
 * Service block `why` (PROMPTS P20 for P21, e.g. "Why Choose My Solutionz for Tender Writing?"): two-voice H2, "Sub"
 * line, paragraphs, a lead-in line ("We help you:"), the benefit rows (icon + one paragraph: the client's bold words
 * as <strong> running straight into the rest of their sentence), closing paragraph. Motion: `stagger`.
 * Not used by the category pages; shown on /styleguide/service-blocks/.
 * @var array      $block  ['type' => 'why', 'id', 'heading', 'accent'?, 'sub'?, 'paras'? => string[], 'lead_in'?,
 *                         'items' => [['icon', 'strong', 'text'], …], 'outro'?]
 * @var array|null $ctx    The service category (unused; kept for a uniform block signature)
 */
$id = (string) $block['id'];
$titleId = $id . '-title';
// The bold words run into the sentence: no space before a comma/full stop that continues it
$joined = static fn (array $item): string => '<strong>' . e($item['strong']) . '</strong>'
    . (preg_match('/^[,.;:]/', (string) $item['text']) === 1 ? '' : ' ') . rich((string) $item['text']);
?>
<section class="svc-why" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="container svc-why__grid">
    <header class="svc-why__head">
      <?= heading(2, (string) $block['heading'], $block['accent'] ?? null, 'svc-why__title', $titleId, ['data-anim' => 'two-voice']) ?>
      <?php if (!empty($block['sub'])): ?>
      <p class="svc-sub" data-anim="fade-up"><?= e($block['sub']) ?></p>
      <?php endif; ?>
      <?php foreach ($block['paras'] ?? [] as $para): ?>
      <p class="svc-why__intro" data-anim="fade-up"><?= rich($para) ?></p>
      <?php endforeach; ?>
    </header>

    <div class="svc-why__body">
      <?php if (!empty($block['lead_in'])): ?>
      <p class="svc-why__lead-in"><?= e($block['lead_in']) ?></p>
      <?php endif; ?>
      <ul class="svc-why__list" role="list" data-anim="stagger">
        <?php foreach ($block['items'] ?? [] as $item): ?>
        <li class="svc-why__item">
          <span class="svc-why__icon" aria-hidden="true"><?= icon($item['icon'], 'light', ['size' => 24]) ?></span>
          <p class="svc-why__text"><?= $joined($item) ?></p>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php if (!empty($block['outro'])): ?>
      <p class="svc-why__outro" data-anim="fade-up"><?= rich($block['outro']) ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>
