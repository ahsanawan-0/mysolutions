<?php
declare(strict_types=1);
/**
 * About A4 "Why Work With Us?" (docs/08 §5 A4, PROMPTS P19): eyebrow + two-voice H2 + 2 intro paragraphs, then the
 * 7 reasons as sticky stacking cards (`stack-cards`: each card has its number, a hex icon, H3 and the client's full
 * paragraph; white and mist alternate), and the closing line in large type. Copy: about('why').
 * @var string $id  anchor id (why-work-with-us)
 */
$w = about('why');
$titleId = $id . '-title';
?>
<section class="section about-why" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="container">
    <header class="about-why__head">
      <?= component('eyebrow', ['text' => $w['eyebrow']]) ?>
      <?= heading(2, $w['title'], $w['accent'], 'about-why__title', $titleId, ['data-anim' => 'two-voice']) ?>
      <div class="about-why__paras" data-anim="stagger">
        <?php foreach ($w['paras'] as $para): ?>
        <p><?= e($para) ?></p>
        <?php endforeach; ?>
      </div>
    </header>

    <ol class="about-why__stack" data-anim="stack-cards">
      <?php foreach ($w['reasons'] as $i => $r): ?>
      <li class="about-why__card<?= $i % 2 ? ' about-why__card--mist' : '' ?>">
        <span class="about-why__n" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
        <?= component('hex-tile', ['icon' => $r['icon'], 'size' => 'lg', 'class' => 'about-why__icon']) ?>
        <div class="about-why__body">
          <h3 class="about-why__card-title"><?= e($r['title']) ?></h3>
          <p class="about-why__text"><?= e($r['text']) ?></p>
        </div>
      </li>
      <?php endforeach; ?>
    </ol>

    <p class="about-why__closing" data-anim="read-along"><?= e($w['closing']) ?></p>
  </div>
</section>
