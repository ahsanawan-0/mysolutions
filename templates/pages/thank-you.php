<?php
declare(strict_types=1);
/**
 * Thank-you page /thank-you/ (PROMPTS P24, docs/08 §12): where a successful enquiry lands (PRG 303 from
 * App\Controllers\ContactController). `noindex, follow`, left out of every sitemap (its route has no `sitemap` group)
 * and disallowed in robots.txt. A direct visit shows the same page, never an error. Copy: contact('thank_you').
 * Order: hero-inner `simple` with the hex check mark (drawn once by src/js/modules/success-check.js after the splash)
 * → "What happens next" 3-step timeline (the timing is `[[PENDING: response time]]`: a dashed note off production,
 * omitted on production) → links to Insights and Services (contact-card markup) → call strip.
 * Analytics hook: after a real submission the form session holds a one-time flag (slugs only); it is read and cleared
 * here and printed as data-track-event="form_submit", which src/js/core/track.js hands to window.msTrack() (a no-op
 * until analytics consent exists, P28). A visitor without the form session cookie never gets one here.
 */
use App\Csrf;

$t = contact('thank_you');
$page = [
    'title'          => $t['seo']['title'],
    'description'    => $t['seo']['description'],
    'canonical'      => '/thank-you/',
    'robots'         => 'noindex, follow',
    'status'         => 'published',
    'breadcrumbs'    => [['Home', '/'], ['Contact Us', '/contact-us/'], ['Thank You', null]],
    'body_class'     => 'page-thank-you',
    'header_theme'   => 'light',
    'header_overlay' => true,
];

$sent = null;
if (Csrf::resume() && is_array($_SESSION['enquiry_sent'] ?? null)) {
    $sent = $_SESSION['enquiry_sent'];
    unset($_SESSION['enquiry_sent']);   // one-time: a reload or a later visit does not count again
}
$track = $sent !== null ? ['form' => 'contact', 'service' => (string) ($sent['service'] ?? ''), 'sector' => (string) ($sent['sector'] ?? '')] : null;

$hero = $t['hero'];
$next = $t['next'];
$links = $t['links'];
$timing = pending_note($next['timing']);
$phone = '<a href="tel:' . e((string) config('phone_e164')) . '">' . e((string) config('phone_display')) . '</a>';

// Hex check mark (the logo's flat-top hexagon, L10): the fill pops, the ring then the tick draw (success-check.js)
$check = '<div class="ty-check" data-module="success-check">'
    . '<svg class="ty-check__svg" viewBox="0 0 96 96" width="88" height="88" role="img" aria-labelledby="ty-check-title" focusable="false">'
    . '<title id="ty-check-title">' . e($hero['check']) . '</title>'
    . '<polygon class="ty-check__fill" data-check-fill points="92,48 70,86.1 26,86.1 4,48 26,9.9 70,9.9"/>'
    . '<polygon class="ty-check__ring" data-check-ring points="92,48 70,86.1 26,86.1 4,48 26,9.9 70,9.9"/>'
    . '<path class="ty-check__tick" data-check-tick d="M30 49.5 42.5 62 66 36"/>'
    . '</svg></div>';
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => $page['breadcrumbs'],
    'slot'        => $check,
    'title'       => $hero['title'],
    'accent'      => $hero['accent'],
    'lead'        => $hero['lead'],
]) ?>

<section class="ty-next" aria-labelledby="ty-next-title" data-header-theme="light"<?php if ($track !== null): ?> data-track-event="form_submit" data-track-props="<?= e((string) json_encode($track)) ?>"<?php endif; ?>>
  <div class="container">
    <div class="ty-next__head">
      <?= component('eyebrow', ['text' => $next['eyebrow']]) ?>
      <?= heading(2, $next['heading'], $next['accent'], 'ty-next__title', 'ty-next-title', ['data-anim' => 'two-voice']) ?>
    </div>

    <ol class="ty-steps" role="list" data-anim="stagger">
      <?php foreach ($next['steps'] as $i => $step): ?>
      <li class="ty-step">
        <span class="ty-step__marker" aria-hidden="true"><?= icon($step['icon'], 'light', ['size' => 26]) ?></span>
        <span class="ty-step__num" aria-hidden="true"><?= e(sprintf('%02d', $i + 1)) ?></span>
        <h3 class="ty-step__title"><?= e($step['title']) ?></h3>
        <p class="ty-step__text"><?= e($step['text']) ?></p>
      </li>
      <?php endforeach; ?>
    </ol>

    <?php if ($timing !== null && !is_production()): ?>
    <p class="ty-next__pending"><?= icon('clock', 'light', ['size' => 18]) ?><span>Content pending · <?= e($timing) ?></span></p>
    <?php elseif ($timing === null && trim((string) $next['timing']) !== ''): ?>
    <p class="ty-next__timing"><?= icon('clock', 'light', ['size' => 18]) ?><span><?= e($next['timing']) ?></span></p>
    <?php endif; ?>
    <p class="ty-next__urgent"><?= icon('phone', 'light', ['size' => 18]) ?><span><?= strtr(e($next['urgent']), ['{phone}' => $phone]) ?></span></p>
  </div>
</section>

<section class="ty-links" aria-labelledby="ty-links-title" data-header-theme="light">
  <div class="container">
    <div class="ty-links__head">
      <?= component('eyebrow', ['text' => $links['eyebrow']]) ?>
      <?= heading(2, $links['heading'], $links['accent'], 'ty-links__title', 'ty-links-title', ['data-anim' => 'two-voice']) ?>
    </div>
    <ul class="ty-links__grid" role="list" data-anim="stagger">
      <?php foreach ($links['items'] as $item): ?>
      <li class="ty-links__item">
        <div class="contact-card contact-card--link">
          <div class="contact-card__surface notch notch--br">
            <span class="contact-card__icon" aria-hidden="true"><?= icon($item['icon'], 'light', ['size' => 24]) ?></span>
            <span class="contact-card__label"><?= e($item['label']) ?></span>
            <a class="contact-card__value contact-card__link" href="<?= e(url($item['url'])) ?>"><?= e($item['title']) ?></a>
            <span class="contact-card__note"><?= e($item['text']) ?></span>
          </div>
          <span class="contact-card__arrow" aria-hidden="true"><?= icon('arrow-right', 'regular', ['size' => 20]) ?></span>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<?php $strip = contact('call_strip'); ?>
<div class="container contact-strip" data-header-theme="light"><?= component('micro-cta', [
    'text'  => $strip['text'],
    'label' => str_replace('{phone}', (string) config('phone_display'), $strip['label']),
    'url'   => 'tel:' . config('phone_e164'),
]) ?></div>
