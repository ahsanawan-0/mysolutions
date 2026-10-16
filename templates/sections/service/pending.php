<?php
declare(strict_types=1);
/**
 * A service block whose data still holds a [[PENDING: …]] marker (PROMPTS P20 template rule): off production it
 * renders as a dashed "Content pending" block naming the block and what is needed; on production the category /
 * sub-service template omits the block entirely (it never calls this partial there).
 * @var array  $block  The block data (type, heading?, accent?)
 * @var string $note   The marker's note (pending_note())
 */
$labels = ['intro' => 'Introduction', 'text' => 'Text section', 'steps' => 'Process steps', 'list' => 'Icon list', 'why' => 'Why choose us', 'subs' => 'Sub-service cards', 'challenges' => 'Sector challenges', 'buyers' => 'Typical buyers and contract types'];
$title = trim(($block['heading'] ?? '') . ' ' . ($block['accent'] ?? ''));
?>
<section class="svc-pending" aria-label="Content pending" data-header-theme="light">
  <div class="container">
    <div class="svc-pending__box">
      <p class="svc-pending__label"><?= icon('clock', 'light', ['size' => 18]) ?><span>Content pending · <?= e($labels[$block['type']] ?? $block['type']) ?></span></p>
      <?php if ($title !== ''): ?><p class="svc-pending__title"><?= e($title) ?></p><?php endif; ?>
      <p class="svc-pending__note"><?= e($note) ?></p>
    </div>
  </div>
</section>
