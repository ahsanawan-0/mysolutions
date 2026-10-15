<?php
declare(strict_types=1);
/**
 * About A5 "Our Mission & Vision" (docs/08 §5 A5, PROMPTS P19): eyebrow + two-voice H2, then two cards side by side,
 * Mission (white, compass) and Vision (Azure 650 → Navy 700 gradient, white text, binoculars), over the azure ribbon
 * loops (public/assets/img/art/ribbon.svg, inlined and drawn on scroll). The closing statement is centred below.
 * Copy: about('mission').
 * @var string $id  anchor id (mission-vision)
 */
$m = about('mission');
$titleId = $id . '-title';
?>
<section class="section about-mission" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="about-mission__art" aria-hidden="true"><?= inline_svg('img/art/ribbon.svg', ['title' => false, 'attrs' => ['data-anim' => 'draw']]) ?></div>
  <div class="container">
    <header class="about-mission__head">
      <?= component('eyebrow', ['text' => $m['eyebrow']]) ?>
      <?= heading(2, $m['title'], $m['accent'], 'about-mission__title', $titleId, ['data-anim' => 'two-voice']) ?>
    </header>

    <div class="about-mission__cards" data-anim="stagger">
      <?php foreach ($m['cards'] as $card): ?>
      <article class="about-mission__card about-mission__card--<?= e($card['key']) ?><?= $card['key'] === 'vision' ? ' on-dark' : '' ?>" aria-labelledby="<?= e($id . '-' . $card['key']) ?>">
        <?= component('hex-tile', ['icon' => $card['icon'], 'size' => 'lg', 'class' => 'about-mission__icon']) ?>
        <h3 class="about-mission__card-title" id="<?= e($id . '-' . $card['key']) ?>"><?= e($card['title']) ?></h3>
        <?php foreach ($card['paras'] as $para): ?>
        <p class="about-mission__text"><?= e($para) ?></p>
        <?php endforeach; ?>
      </article>
      <?php endforeach; ?>
    </div>

    <p class="about-mission__statement" data-anim="fade-up"><?= e($m['statement']) ?></p>
  </div>
</section>
