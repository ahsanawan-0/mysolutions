<?php
declare(strict_types=1);
/**
 * Home "Our Approach to Winning Contracts" (docs/04 §1.2 Innovax approach section, docs/08 §4 H3, PROMPTS P10).
 *   left  : eyebrow + two-voice heading, then the large image whose top-right corner is cut out (a true CSS mask,
 *           docs/04 §1.5 🔒 rule for notches over images) and holds the Office Hours card (brand gradient).
 *   right : the image-filled "10+" numerals (fill-text pans the photo on scroll, counter counts up), the sector
 *           avatar stack with its uppercase label, the intro paragraph and the "More About Us" split button.
 * Copy: app/data/home.php → approach. The Office Hours card is C33 (components/hours-card.php, config('hours')).
 * Below 1024 the columns stack and the hours card moves under the image (no notch).
 */
$a = home('approach');
$fillSrc = App\Image::src($a['number']['image']['key'], 800);   // null → brand gradient (motion.css .fill-text)
?>
<section class="section approach" id="approach" aria-labelledby="approach-title" data-header-theme="light">
  <svg class="approach__art" aria-hidden="true" focusable="false" data-anim="parallax" data-speed="0.92"><use href="<?= e(asset('img/art/skyscraper.svg')) ?>#skyscraper"></use></svg>
  <div class="container approach__grid">

    <div class="approach__media">
      <?= component('eyebrow', ['text' => $a['eyebrow'], 'class' => 'approach__eyebrow']) ?>
      <?= heading(2, $a['title'], $a['accent'], 'approach__title', 'approach-title', ['data-anim' => 'two-voice']) ?>

      <div class="approach__figure">
        <div class="approach__image notch notch--tr" data-anim="img-reveal">
          <?= img($a['image']['key'], ['alt' => $a['image']['alt'], 'ratio' => '5/4', 'sizes' => '(min-width: 1024px) 50vw, 100vw', 'cover' => true, 'pattern' => false]) ?>
        </div>

        <?= component('hours-card', [
            'title'      => $a['hours']['title'],
            'closed'     => $a['hours']['closed'],
            'email_lead' => $a['hours']['email_lead'],
            'id'         => 'approach-hours-title',
            'class'      => 'approach__hours',
            'attrs'      => ['data-anim' => 'fade-up', 'data-x' => '40', 'data-y' => '-40', 'data-delay' => '0.35'],
        ]) ?>
      </div>
    </div>

    <div class="approach__body" data-anim="stagger">
      <p class="approach__years">
        <span class="approach__num fill-text" aria-hidden="true" data-anim="fill-text counter" data-to="<?= (int) $a['number']['value'] ?>" data-suffix="<?= e($a['number']['suffix']) ?>"<?= $fillSrc !== null ? ' style="--fill-image: url(' . e($fillSrc) . ')"' : '' ?>><?= (int) $a['number']['value'] ?><?= e($a['number']['suffix']) ?></span>
        <span class="visually-hidden"><?= e($a['number']['sr']) ?></span>
      </p>

      <?= component('avatar-stack', ['avatars' => $a['stack']['avatars'], 'label' => $a['stack']['label'], 'class' => 'avatar-stack--xl approach__stack']) ?>

      <p class="approach__text"><?= e($a['text']) ?></p>

      <?= component('button', ['label' => $a['button']['label'], 'href' => url($a['button']['url']), 'variant' => 'split', 'class' => 'approach__cta']) ?>
    </div>

  </div>
</section>
