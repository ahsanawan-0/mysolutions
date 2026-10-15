<?php
declare(strict_types=1);
/**
 * Service block `intro` (PROMPTS P20/P21, docs/08 §7a): the client's paragraphs that continue the hero, centred under
 * it at max 68ch. The first paragraph is set apart by weight and colour (paragraphs stay 14px, docs/00 L11).
 * Paragraphs go through rich() (the client's **bold** and [internal links]). Motion: fade-up in sequence.
 * @var array $block  ['type' => 'intro', 'paras' => string[]]
 */
$paras = $block['paras'] ?? [];
?>
<section class="svc-intro" aria-label="Introduction" data-header-theme="light">
  <div class="container svc-intro__inner" data-anim="stagger">
    <?php foreach ($paras as $i => $para): ?>
    <p class="svc-intro__p<?= $i === 0 ? ' svc-intro__p--first' : '' ?>"><?= rich($para) ?></p>
    <?php endforeach; ?>
  </div>
</section>
