<?php
declare(strict_types=1);
/**
 * About A1 "About Us" (docs/08 §5 A1, PROMPTS P18): eyebrow + two-voice H2, the client's 5 paragraphs (the first
 * one set apart as the intro), and the P10 pattern on the right: an image whose top-right corner is cut out by a
 * true CSS mask (.notch--tr) holding the C33 Office Hours card. Below: the sector chips, each linking to its
 * sector page. Copy: about('about_us'). Below 1024 the columns stack and the card sits under the image.
 * @var string $id  anchor id (about-us)
 */
$a = about('about_us');
$titleId = $id . '-title';
?>
<section class="section about-intro" id="<?= e($id) ?>" aria-labelledby="<?= e($titleId) ?>" data-header-theme="light">
  <div class="container about-intro__grid">
    <div class="about-intro__text">
      <?= component('eyebrow', ['text' => $a['eyebrow']]) ?>
      <?= heading(2, $a['title'], $a['accent'], 'about-intro__title', $titleId, ['data-anim' => 'two-voice']) ?>
      <div class="about-intro__copy" data-anim="stagger">
        <?php foreach ($a['paras'] as $i => $para): ?>
        <p<?= $i === 0 ? ' class="about-intro__first"' : '' ?>><?= e($para) ?></p>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="about-intro__figure">
      <div class="about-intro__image notch notch--tr" data-anim="img-reveal">
        <?= img($a['image']['key'], ['alt' => $a['image']['alt'], 'ratio' => '4/5', 'sizes' => '(min-width: 1024px) 44vw, 100vw', 'cover' => true, 'pattern' => false]) ?>
      </div>
      <?= component('hours-card', [
          'id'    => 'about-hours-title',
          'class' => 'about-intro__hours',
          'attrs' => ['data-anim' => 'fade-up', 'data-x' => '40', 'data-y' => '-40', 'data-delay' => '0.35'],
      ]) ?>
    </div>
  </div>

  <div class="container about-intro__sectors">
    <h3 class="about-intro__sectors-title" id="<?= e($id) ?>-sectors"><?= e($a['sectors_label']) ?></h3>
    <ul class="about-intro__chips" role="list" aria-labelledby="<?= e($id) ?>-sectors" data-anim="stagger">
      <?php foreach (sectors() as $sector): ?>
      <li>
        <a class="chip chip--outline about-intro__chip" href="<?= e(url($sector['url'])) ?>">
          <?= icon($sector['icon'], 'light', ['size' => 18]) ?><span><?= e($sector['name']) ?></span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
