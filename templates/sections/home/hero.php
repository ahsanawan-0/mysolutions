<?php
declare(strict_types=1);
/**
 * Home hero (docs/05 §1–§3 🔒, PROMPTS P08): the hero_should composition 1:1.
 *   canvas frame → dark rounded panel (photo on the right, faded into the navy) → the header's `hero` variant
 *   inside the panel → left column (3D badge, two-line H1, lead, Quick-Start form, two quick links) → right column
 *   (the services slider: 12 server-rendered cards, prev / active / next visible, the "Our Special Service" tag with
 *   its frame above the active card) → helper text bottom-right → the canvas notch tab (S-curve) → below the panel:
 *   the mini insight card and the sector chips (a marquee of all seven below 1024px).
 * P09: data-module="hero-slider" on the stage (src/js/modules/hero-slider.js: infinite loop, autoplay, drag with
 * inertia, keys, controls, live region), data-module="hero-intro" on the section (src/js/modules/hero-intro.js: the
 * docs/05 §6 entrance after `splash:done` + the scroll parallax), and the glowing .special-frame
 * (src/css/components/special-frame.css). Without JS the static P08 layout stays.
 * Copy: app/data/home.php → hero. Cards: app/data/services.php. Chips: app/data/sectors.php.
 */
$h = home('hero');
// P16: the mini card shows the newest insight (App\Blog); the data's mini entry is the fallback while there are no posts
$latest = App\Blog::latest(1)[0] ?? null;
$mini = $latest !== null
    ? ['eyebrow' => $h['mini']['eyebrow'], 'title' => $latest['title'], 'button' => $h['mini']['button'], 'url' => $latest['url'], 'image' => $latest['image'], 'alt' => $latest['image_alt']]
    : $h['mini'];
$qs = $h['quick_start'];
$services = services();
$sectors = sectors();

$cards = [];
foreach ($services as $category) {
    foreach ($category['subs'] as $sub) {
        $cards[] = ['sub' => $sub, 'category' => $category];
    }
}
$activeIndex = 1;   // static layout: card 2 sits in the centre with card 1 / card 3 faded either side (P09 animates)

$bySlug = array_column($sectors, null, 'slug');
$featured = array_values(array_filter(array_map(static fn (string $slug) => $bySlug[$slug] ?? null, $h['chips'])));
$others = array_values(array_filter($sectors, static fn (array $s) => !in_array($s['slug'], $h['chips'], true)));
$chips = array_merge($featured, $others);

$helper = str_replace(
    ['{phone}', '{consult}'],
    [
        '<a class="hero__helper-link" href="tel:' . e(config('phone_e164')) . '"><strong>' . e(config('phone_display')) . '</strong></a>',
        '<a class="hero__helper-link" href="' . e(url($h['helper']['consult_url'])) . '">' . e($h['helper']['consult_label']) . '</a>',
    ],
    e($h['helper']['text'])
);
?>
<section class="hero" data-header-theme="dark" data-hero data-module="hero-intro">
  <div class="hero__panel on-dark">
    <div class="hero__photo">
      <?= img($h['photo']['key'], ['alt' => $h['photo']['alt'], 'priority' => true, 'sizes' => '(min-width: 1024px) 60vw, 100vw', 'ratio' => '4/3', 'cover' => true, 'pattern' => false, 'class' => 'hero__photo-img']) ?>
    </div>

    <?= render('partials/header', ['variant' => 'hero', 'theme' => 'dark']) ?>

    <div class="hero__grid">
      <div class="hero__copy">
        <p class="hero__badge glass-thin-dark">
          <span class="hero__badge-mark" aria-hidden="true">
            <svg class="hero__badge-defs" width="0" height="0" focusable="false" aria-hidden="true">
              <defs>
                <linearGradient id="hero-grad-sky" x1="0" y1="0" x2="1" y2="1"><stop offset="0" style="stop-color: var(--sky-100)"/><stop offset="1" style="stop-color: var(--sky-300)"/></linearGradient>
                <linearGradient id="hero-grad-azure" x1="0" y1="0" x2="1" y2="1"><stop offset="0" style="stop-color: var(--azure-300)"/><stop offset="1" style="stop-color: var(--azure-650)"/></linearGradient>
                <linearGradient id="hero-grad-navy" x1="0" y1="0" x2="1" y2="1"><stop offset="0" style="stop-color: var(--navy-400)"/><stop offset="1" style="stop-color: var(--navy-800)"/></linearGradient>
              </defs>
            </svg>
            <?= inline_svg('brand/logo-mark.svg', ['prefix' => 'badge', 'title' => false, 'class' => 'hero__badge-svg']) ?>
          </span>
          <span class="hero__badge-text"><?= e($h['badge']) ?></span>
        </p>

        <h1 class="hero__title"><?= e($h['title']) ?><br><em><?= e($h['accent']) ?></em></h1>
        <p class="hero__lead"><?= e($h['lead']) ?></p>

        <form class="quick-start" action="<?= e(url($qs['action'])) ?>" method="get" aria-label="Quick start">
          <div class="quick-start__field">
            <label class="quick-start__label" for="qs-sector"><?= icon($qs['sector_icon'], 'light', ['size' => 16]) ?><?= e($qs['sector_label']) ?></label>
            <span class="quick-start__control">
              <select class="quick-start__select" id="qs-sector" name="sector">
                <option value=""><?= e($qs['sector_any']) ?></option>
                <?php foreach ($sectors as $sector): ?>
                <option value="<?= e($sector['slug']) ?>"><?= e($sector['name']) ?></option>
                <?php endforeach; ?>
                <option value="other"><?= e($qs['sector_other']) ?></option>
              </select>
              <?= icon('caret-down', 'regular', ['size' => 14, 'class' => 'quick-start__caret']) ?>
            </span>
          </div>
          <div class="quick-start__field">
            <label class="quick-start__label" for="qs-service"><?= icon($qs['service_icon'], 'light', ['size' => 16]) ?><?= e($qs['service_label']) ?></label>
            <span class="quick-start__control">
              <select class="quick-start__select" id="qs-service" name="service">
                <option value=""><?= e($qs['service_any']) ?></option>
                <?php foreach ($services as $category): ?>
                <optgroup label="<?= e($category['name']) ?>">
                  <?php foreach ($category['subs'] as $sub): ?>
                  <option value="<?= e($sub['slug']) ?>"><?= e($sub['name']) ?></option>
                  <?php endforeach; ?>
                </optgroup>
                <?php endforeach; ?>
              </select>
              <?= icon('caret-down', 'regular', ['size' => 14, 'class' => 'quick-start__caret']) ?>
            </span>
          </div>
          <button class="btn quick-start__submit" type="submit"><?= e($qs['submit']) ?></button>
        </form>

        <ul class="hero__links" role="list">
          <?php foreach ($h['links'] as [$label, $href]): ?>
          <li><a class="hero__link" href="<?= e($href) ?>"><?= e($label) ?><span class="hero__chev" aria-hidden="true"><?= icon('caret-down', 'regular', ['size' => 12]) ?></span></a></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div class="hero__stage" role="region" aria-roledescription="carousel" aria-label="Our services" data-hero-slider data-module="hero-slider">
        <div class="special" data-special>
          <span class="special-frame" aria-hidden="true"></span>
          <span class="special__tag"><?= e($h['tag']) ?></span>
        </div>
        <div class="hero__viewport" data-hero-viewport>
          <div class="hero__cards" data-hero-track>
            <?php foreach ($cards as $i => ['sub' => $sub, 'category' => $category]): ?>
            <div class="hero__card<?= $i === $activeIndex ? ' is-active' : '' ?>" role="group" aria-roledescription="slide" aria-label="<?= $i + 1 ?> of <?= count($cards) ?>" data-hero-card>
              <a class="hero__card-link glass-thin-dark" href="<?= e(url($sub['url'])) ?>">
                <?= component('chip', ['label' => $category['short'], 'variant' => $category['colour'], 'class' => 'hero__card-chip']) ?>
                <?= component('hex-tile', ['icon' => $sub['icon'], 'category' => $category['colour'], 'size' => 'sm', 'class' => 'hero__card-icon']) ?>
                <span class="hero__card-title"><?= e($sub['name']) ?></span>
                <span class="hero__card-text"><?= e($sub['summary']) ?></span>
              </a>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- Slider controls (subtle; revealed by hero-slider.js) + the live region that announces user-initiated changes -->
        <div class="hero__controls" data-hero-controls>
          <button class="hero__ctrl" type="button" data-hero-prev aria-label="Previous service"><?= icon('caret-left', 'regular', ['size' => 16]) ?></button>
          <ol class="hero__dots" role="list" style="--dot-cols: <?= (int) ceil(count($cards) / 2) ?>">
            <?php foreach ($cards as $i => ['sub' => $sub]): ?>
            <li><button class="hero__dot" type="button" data-hero-dot="<?= $i ?>" aria-label="Go to <?= e($sub['name']) ?> (<?= $i + 1 ?> of <?= count($cards) ?>)"<?= $i === $activeIndex ? ' aria-current="true"' : '' ?>></button></li>
            <?php endforeach; ?>
          </ol>
          <button class="hero__ctrl" type="button" data-hero-next aria-label="Next service"><?= icon('caret-right', 'regular', ['size' => 16]) ?></button>
          <button class="hero__ctrl hero__ctrl--pause" type="button" data-hero-pause aria-pressed="false">
            <?= icon('pause', 'regular', ['size' => 16]) ?><?= icon('play', 'regular', ['size' => 16]) ?>
            <span class="visually-hidden" data-hero-pause-label>Pause the services slider</span>
          </button>
        </div>
        <p class="visually-hidden" aria-live="polite" aria-atomic="true" data-hero-live></p>
      </div>

      <p class="hero__helper"><?= $helper ?></p>
    </div>

    <!-- The canvas notch tab (S-curve) holds the sector tiles; below 1024px it becomes a plain strip under the panel -->
    <div class="hero__notch">
      <div class="hero__chips" id="hero-chips" data-anim="marquee" data-marquee-only="mobile" data-speed="40" role="group" aria-label="Sectors we serve">
      <ul class="hero__chip-list" role="list" data-marquee-track>
        <?php foreach ($chips as $i => $sector): ?>
        <li class="hero__chip-item<?= $i >= count($featured) ? ' hero__chip-item--extra' : '' ?>">
          <a class="hero__chip" href="<?= e(url($sector['url'])) ?>"><?= icon($sector['icon'], 'light', ['size' => 20]) ?><span><?= e($sector['name']) ?></span></a>
        </li>
        <?php endforeach; ?>
      </ul>
      <button class="marquee__pause hero__chips-pause" type="button" data-marquee-pause aria-pressed="false" hidden>
        <?= icon('pause', 'regular', ['size' => 18]) ?><?= icon('play', 'regular', ['size' => 18]) ?>
        <span class="visually-hidden" data-marquee-label>Pause the moving sectors</span>
      </button>
      </div>
    </div>
  </div>

  <div class="hero__bottom">
    <article class="hero__mini">
      <div class="hero__mini-thumb"><?= img($mini['image'], ['alt' => $mini['alt'], 'ratio' => '1/1', 'sizes' => '96px', 'cover' => true, 'pattern' => false]) ?></div>
      <div class="hero__mini-body">
        <?= component('eyebrow', ['text' => $mini['eyebrow'], 'tag' => 'span', 'class' => 'hero__mini-eyebrow']) ?>
        <p class="hero__mini-title"><?= e($mini['title']) ?></p>
        <?= component('button', ['label' => $mini['button'], 'href' => url($mini['url']), 'size' => 'sm', 'class' => 'hero__mini-btn', 'attrs' => ['aria-label' => $mini['button'] . ': ' . $mini['title']]]) ?>
      </div>
    </article>
  </div>
</section>
