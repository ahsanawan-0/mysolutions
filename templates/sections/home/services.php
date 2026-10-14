<?php
declare(strict_types=1);
/**
 * Home "Our Services" (docs/08 §4 H2 🔒 first section after the hero, docs/04 §4 hex tiles, PROMPTS P11):
 * header (eyebrow, two-voice H2, intro) then the three service categories as large sticky stacking cards
 * (`data-anim="stack-cards"`: motion.css makes each card sticky; stack-cards.js scales and dims a card to 0.94 as
 * the next one slides over it; below 1024 and under reduced motion the cards are a plain vertical list).
 * Each card: hex tile + category number, H3, summary, the sub-services as hex-icon links (2 columns ≥ 1024),
 * "Explore <category>" split button; on the right the category image with the count chip in a bottom-left
 * cut-out (`.notch.notch--bl`, a true mask). Digital Presence is the navy variant (Sky accent) for contrast variety.
 * Copy: app/data/home.php → services. Categories, sub-services and URLs: app/data/services.php.
 */
$s = home('services');
$categories = services();
$countLabel = static function (int $n) use ($s): string {
    return str_replace('{n}', (string) $n, $n === 1 ? $s['count']['one'] : $s['count']['other']);
};
?>
<section class="section services" id="services" aria-labelledby="services-title">
  <div class="container">
    <header class="services__head">
      <?= component('eyebrow', ['text' => $s['eyebrow']]) ?>
      <?= heading(2, $s['title'], $s['accent'], 'services__title', 'services-title', ['data-anim' => 'two-voice']) ?>
      <p class="services__intro lead" data-anim="fade-up"><?= e($s['intro']) ?></p>
    </header>

    <div class="services__stack" data-anim="stack-cards">
      <?php foreach ($categories as $i => $category): ?>
      <?php
        $n = count($category['subs']);
        $dark = $category['colour'] === 'digital';
        $titleId = 'services-' . $category['slug'];
      ?>
      <article class="service-card service-card--<?= e($category['colour']) ?><?= $dark ? ' service-card--dark on-dark' : '' ?>" aria-labelledby="<?= e($titleId) ?>">
        <div class="service-card__body">
          <div class="service-card__top">
            <?= component('hex-tile', ['icon' => $category['icon'], 'category' => $category['colour'], 'size' => 'lg']) ?>
            <span class="service-card__num" aria-hidden="true"><?= sprintf('%02d', $i + 1) ?></span>
          </div>
          <h3 class="service-card__title" id="<?= e($titleId) ?>"><?= e($category['name']) ?></h3>
          <p class="service-card__summary"><?= e($category['summary']) ?></p>

          <ul class="service-card__subs" role="list" aria-label="<?= e($category['name']) ?> services">
            <?php foreach ($category['subs'] as $sub): ?>
            <li>
              <a class="service-card__sub hex-tile-trigger" href="<?= e(url($sub['url'])) ?>">
                <?= component('hex-tile', ['icon' => $sub['icon'], 'category' => $category['colour'], 'size' => 'sm']) ?>
                <span class="service-card__sub-text">
                  <span class="service-card__sub-name"><?= e($sub['name']) ?></span>
                  <span class="service-card__sub-summary"><?= e($sub['summary']) ?></span>
                </span>
                <?= icon('arrow-right', 'regular', ['size' => 20, 'class' => 'service-card__sub-arrow']) ?>
              </a>
            </li>
            <?php endforeach; ?>
          </ul>

          <?= component('button', [
              'label'   => str_replace('{name}', $category['name'], $s['explore']),
              'href'    => url($category['url']),
              'variant' => 'split',
              'class'   => 'service-card__cta' . ($dark ? ' btn--glass' : ''),
          ]) ?>
        </div>

        <div class="service-card__media">
          <div class="service-card__image notch notch--bl">
            <?= img('services/' . $category['slug'], ['alt' => $s['images'][$category['slug']] ?? $category['name'], 'ratio' => '4/5', 'sizes' => '(min-width: 1024px) 40vw, 100vw', 'cover' => true, 'pattern' => false]) ?>
          </div>
          <div class="service-card__tab">
            <?= component('chip', ['label' => $countLabel($n), 'variant' => $category['colour'], 'icon' => 'hexagon']) ?>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
