<?php
declare(strict_types=1);
/**
 * About A3 "Our Approach" (docs/08 §5 A3, PROMPTS P19): eyebrow + two-voice H2 and the 2 intro paragraphs, then the
 * C15 process component in its `full` variant (the 5 steps with both client paragraphs each, pinned on desktop),
 * then the closing statement on Navy 900. Copy: about('approach').
 * @var string $id  anchor id (our-approach)
 */
$a = about('approach');
$titleId = $id . '-title';
?>
<section class="section about-approach" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="container about-approach__intro">
    <?= component('eyebrow', ['text' => $a['eyebrow']]) ?>
    <?= heading(2, $a['title'], $a['accent'], 'about-approach__title', $titleId, ['data-anim' => 'two-voice']) ?>
    <div class="about-approach__paras" data-anim="stagger">
      <?php foreach ($a['paras'] as $para): ?>
      <p><?= e($para) ?></p>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="about-approach__steps">
    <?= component('process-steps', [
        'eyebrow'    => $a['steps_eyebrow'],
        'heading'    => $a['steps_title'],
        'accent'     => $a['steps_accent'],
        'id'         => $id . '-steps-title',
        'steps'      => $a['steps'],
        'variant'    => 'full',
        'pin_length' => 0.8,
    ]) ?>
  </div>

  <div class="about-approach__closing on-dark" data-header-theme="dark">
    <div class="container about-approach__closing-inner">
      <?= heading(3, $a['closing']['title'], $a['closing']['accent'], 'about-approach__closing-title', null, ['data-anim' => 'two-voice']) ?>
      <div class="about-approach__closing-paras" data-anim="stagger">
        <?php foreach ($a['closing']['paras'] as $i => $para): ?>
        <p<?= $i === count($a['closing']['paras']) - 1 ? ' class="about-approach__aim"' : '' ?>><?= e($para) ?></p>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
