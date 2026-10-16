<?php
declare(strict_types=1);
/**
 * Contact cards row (PROMPTS P23, docs/08 §11): white cards with an icon tile, label and value; the whole card is the
 * link, with a round arrow in a bottom-right cut-out (`.notch--br` on the surface, the arrow a sibling outside the
 * mask). Call (tel:), WhatsApp, Email (mailto:), Office → Google Maps search (new tab, rel="noopener", visually-hidden
 * "(opens in a new tab)"), Office hours (from config('hours'), no link). Copy: contact('cards').
 */
$c = contact('cards');
$address = config('address.street') . ', ' . config('address.locality') . ' ' . config('address.postcode');
$maps = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode('My Solutionz, ' . $address);
$cards = [
    ['key' => 'call', 'value' => (string) config('phone_display'), 'href' => 'tel:' . config('phone_e164')],
    ['key' => 'whatsapp', 'value' => $c['whatsapp']['value'], 'href' => (string) config('whatsapp'), 'external' => true],
    ['key' => 'email', 'value' => (string) config('email'), 'href' => 'mailto:' . config('email')],
    ['key' => 'office', 'value' => $address, 'href' => $maps, 'external' => true],
    ['key' => 'hours', 'value' => (string) config('hours.summary'), 'note' => config('hours.email_anytime') ? $c['hours']['email_note'] : null],
];
?>
<section class="contact-cards" aria-label="<?= e($c['label']) ?>" data-header-theme="light">
  <div class="container">
    <ul class="contact-cards__grid" role="list" data-anim="stagger">
      <?php foreach ($cards as $card): ?>
      <?php $copy = $c[$card['key']]; $linked = isset($card['href']); ?>
      <li class="contact-cards__item">
        <div class="contact-card contact-card--<?= e($card['key']) ?><?= $linked ? ' contact-card--link' : '' ?>">
          <div class="contact-card__surface<?= $linked ? ' notch notch--br' : '' ?>">
            <span class="contact-card__icon" aria-hidden="true"><?= icon($copy['icon'], 'light', ['size' => 24]) ?></span>
            <span class="contact-card__label"><?= e($copy['label']) ?></span>
            <?php if ($linked): ?>
            <a class="contact-card__value contact-card__link" href="<?= e($card['href']) ?>"<?= !empty($card['external']) ? ' target="_blank" rel="noopener"' : '' ?>><?= e($card['value']) ?><?php if (!empty($card['external'])): ?><span class="visually-hidden"> <?= e($c['new_tab']) ?></span><?php endif; ?></a>
            <?php else: ?>
            <span class="contact-card__value"><?= e($card['value']) ?></span>
            <?php endif; ?>
            <?php if (!empty($card['note'])): ?><span class="contact-card__note"><?= e($card['note']) ?></span><?php endif; ?>
          </div>
          <?php if ($linked): ?>
          <span class="contact-card__arrow" aria-hidden="true"><?= icon(!empty($card['external']) ? 'arrow-up-right' : 'arrow-right', 'regular', ['size' => 20]) ?></span>
          <?php endif; ?>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
