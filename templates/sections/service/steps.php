<?php
declare(strict_types=1);
/**
 * Service block `steps` (PROMPTS P20/P21, docs/08 §7a "How it works"): the two-voice H2, "Sub" line and intro
 * paragraphs as a normal header, then the C15 process component (`compact`, navy) without its own header: the
 * numbered list 01–0N with the progress line and the image panels, pinned ≥ 1024 (pin length and line scale with the
 * number of steps; 6 steps on the Bid & Proposal page). Mobile / reduced motion / no JS: a static readable list.
 * @var array      $block  ['type' => 'steps', 'id', 'heading', 'accent'?, 'sub'?, 'paras'? => string[], 'steps' =>
 *                         [['title', 'icon', 'text', 'detail'? => string[], 'image'?, 'detail_image'?], …],
 *                         'variant'? => 'compact'|'full']
 * @var array|null $ctx    The service category (unused; kept for a uniform block signature)
 */
$id = (string) $block['id'];
$titleId = $id . '-title';
$steps = $block['steps'] ?? [];
$variant = ($block['variant'] ?? 'compact') === 'full' ? 'full' : 'compact';
?>
<section class="svc-steps on-dark" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="dark">
  <div class="container svc-steps__head">
    <div class="svc-steps__heading">
      <?= heading(2, (string) $block['heading'], $block['accent'] ?? null, 'svc-steps__title', $titleId, ['data-anim' => 'two-voice']) ?>
      <?php if (!empty($block['sub'])): ?>
      <p class="svc-sub" data-anim="fade-up"><?= e($block['sub']) ?></p>
      <?php endif; ?>
    </div>
    <?php if (!empty($block['paras'])): ?>
    <div class="svc-steps__intro" data-anim="stagger">
      <?php foreach ($block['paras'] as $para): ?>
      <p><?= rich($para) ?></p>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>

  <?= component('process-steps', [
      'steps'      => $steps,
      'variant'    => $variant,
      'class'      => 'on-dark svc-steps__process process-steps--n' . count($steps),
      'pin_length' => 0.5,
  ]) ?>
</section>
