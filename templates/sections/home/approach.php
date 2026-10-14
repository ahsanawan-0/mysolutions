<?php
declare(strict_types=1);
/**
 * Home "Our Approach to Winning Contracts" (docs/04 §1.2 Innovax approach section, docs/08 §4 H3, PROMPTS P10).
 *   left  : eyebrow + two-voice heading, then the large image whose top-right corner is cut out (a true CSS mask,
 *           docs/04 §1.5 🔒 rule for notches over images) and holds the Office Hours card (brand gradient).
 *   right : the image-filled "10+" numerals (fill-text pans the photo on scroll, counter counts up), the sector
 *           avatar stack with its uppercase label, the intro paragraph and the "More About Us" split button.
 * Copy: app/data/home.php → approach. Hours: config('hours') (provisional until the client confirms, docs/15 P08).
 * Below 1024 the columns stack and the hours card moves under the image (no notch).
 */
$a = home('approach');
$hours = config('hours');

/** "Monday … Friday" → "Mon–Fri"; a single day stays "Sat". */
$dayRange = static function (array $days): string {
    $short = array_map(static fn (string $d): string => substr($d, 0, 3), $days);
    return count($short) > 1 ? $short[0] . '–' . $short[count($short) - 1] : ($short[0] ?? '');
};
$rows = [];
foreach ($hours['days'] ?? [] as $spec) {
    $rows[] = [$dayRange($spec['days']), $spec['opens'] . '–' . $spec['closes']];
}
if (!empty($hours['closed'])) {
    $rows[] = [$dayRange($hours['closed']), $a['hours']['closed']];
}

$fillSrc = App\Image::src($a['number']['image']['key'], 800);   // null → brand gradient (motion.css .fill-text)
?>
<section class="section approach" id="approach" aria-labelledby="approach-title">
  <div class="container approach__grid">

    <div class="approach__media">
      <?= component('eyebrow', ['text' => $a['eyebrow'], 'class' => 'approach__eyebrow']) ?>
      <?= heading(2, $a['title'], $a['accent'], 'approach__title', 'approach-title', ['data-anim' => 'two-voice']) ?>

      <div class="approach__figure">
        <div class="approach__image" data-anim="img-reveal">
          <?= img($a['image']['key'], ['alt' => $a['image']['alt'], 'ratio' => '5/4', 'sizes' => '(min-width: 1024px) 50vw, 100vw', 'cover' => true, 'pattern' => false]) ?>
        </div>

        <aside class="approach__hours on-dark" data-anim="fade-up" data-x="40" data-y="-40" data-delay="0.35" aria-labelledby="approach-hours-title">
          <h3 class="approach__hours-title" id="approach-hours-title">
            <?= icon('clock', 'regular', ['size' => 20]) ?><?= e($a['hours']['title']) ?>
          </h3>
          <dl class="approach__hours-list">
            <?php foreach ($rows as [$days, $time]): ?>
            <div class="approach__hours-row"><dt><?= e($days) ?></dt><dd><?= e($time) ?></dd></div>
            <?php endforeach; ?>
          </dl>
          <?php if (!empty($hours['email_anytime'])): ?>
          <p class="approach__hours-email">
            <span><?= e($a['hours']['email_lead']) ?></span>
            <a href="mailto:<?= e(config('email')) ?>"><?= e(config('email')) ?></a>
          </p>
          <?php endif; ?>
        </aside>
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
