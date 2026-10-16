<?php
declare(strict_types=1);
/**
 * Map + socials (PROMPTS P23, docs/08 §11, docs/13 §2): a static, styled map image (placeholder until generated) that
 * links to Google Maps in a new tab. Never an embedded map before consent (third-party cookies). Beside it the office
 * address and the socials row. Copy: contact('map'), contact('socials'); facts from config().
 */
$m = contact('map');
$address = config('address.street') . ', ' . config('address.locality') . ' ' . config('address.postcode');
$maps = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('My Solutionz, ' . $address);
$newTab = contact('cards.new_tab');
$socials = [
    ['LinkedIn', config('socials.linkedin'), 'linkedin-logo'],
    ['Instagram', config('socials.instagram'), 'instagram-logo'],
    ['Facebook', config('socials.facebook'), 'facebook-logo'],
];
?>
<section class="contact-map" aria-labelledby="map-title" data-header-theme="light">
  <div class="container contact-map__inner">
    <div class="contact-map__text">
      <?= heading(2, $m['heading'], $m['accent'], 'contact-map__title', 'map-title', ['data-anim' => 'two-voice']) ?>
      <address class="contact-map__address">
        <?= e((string) config('legal_name')) ?><br>
        <?= e((string) config('address.street')) ?><br>
        <?= e(config('address.locality') . ' ' . config('address.postcode')) ?>
      </address>
      <?= component('button', ['label' => $m['link'], 'href' => $maps, 'variant' => 'link', 'icon' => 'arrow-up-right', 'attrs' => ['target' => '_blank', 'rel' => 'noopener', 'aria-label' => $m['link'] . ' ' . $newTab]]) ?>
      <div class="contact-map__socials">
        <p class="contact-map__socials-label" id="socials-label"><?= e(contact('socials.label')) ?></p>
        <ul class="contact-map__socials-list" role="list" aria-labelledby="socials-label">
          <?php foreach ($socials as [$name, $href, $glyph]): ?>
          <li><a class="contact-map__social" href="<?= e((string) $href) ?>" target="_blank" rel="noopener" aria-label="<?= e($name . ' ' . $newTab) ?>"><?= icon($glyph, 'regular', ['size' => 20]) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <a class="contact-map__media" href="<?= e($maps) ?>" target="_blank" rel="noopener" data-anim="img-reveal">
      <?= img($m['image'], ['alt' => $m['alt'] . ' ' . $newTab, 'ratio' => '16/10', 'sizes' => '(min-width: 1024px) 58vw, 100vw']) ?>
      <span class="contact-map__pin" aria-hidden="true"><?= icon('map-pin', 'regular', ['size' => 24]) ?></span>
    </a>
  </div>
</section>
