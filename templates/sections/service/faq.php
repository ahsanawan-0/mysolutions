<?php
declare(strict_types=1);
/**
 * Service FAQ (PROMPTS P20/P21, docs/08 §7a item 9): intro column (eyebrow, two-voice H2, a line, optional button)
 * beside the C17 accordion of the questions tagged for the page in app/data/faqs.php. Native <details>, so the
 * answers are in the HTML and work without JS. Renders nothing when no question is tagged.
 * @var array       $faq    ['eyebrow', 'heading', 'accent'?, 'text'?, 'button'? => ['label', 'url']]
 * @var array       $items  faqs($tag)
 * @var string|null $id     Section id (default 'faq')
 */
if (($items ?? []) === []) {
    return;
}
$id = $id ?? 'faq';
$titleId = $id . '-title';
?>
<section class="svc-faq" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="container svc-faq__layout">
    <div class="svc-faq__intro">
      <?php if (!empty($faq['eyebrow'])): ?><?= component('eyebrow', ['text' => $faq['eyebrow']]) ?><?php endif; ?>
      <?= heading(2, (string) $faq['heading'], $faq['accent'] ?? null, 'svc-faq__title', $titleId, ['data-anim' => 'two-voice']) ?>
      <?php if (!empty($faq['text'])): ?>
      <p class="svc-faq__text" data-anim="fade-up"><?= e($faq['text']) ?></p>
      <?php endif; ?>
      <?php if (!empty($faq['button'])): ?>
      <div data-anim="fade-up" data-delay="0.1">
        <?= component('button', ['label' => $faq['button']['label'], 'href' => url($faq['button']['url']), 'variant' => 'split', 'class' => 'btn--secondary']) ?>
      </div>
      <?php endif; ?>
    </div>
    <div class="svc-faq__list" data-anim="fade-up">
      <?= component('accordion', ['items' => $items, 'name' => $id . '-list', 'level' => 3, 'id_prefix' => $id]) ?>
    </div>
  </div>
</section>
