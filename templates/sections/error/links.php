<?php
declare(strict_types=1);
/**
 * Helpful links on the 404 and 410 pages (PROMPTS P29, docs/08 §12): Home, Services, the 3 service categories,
 * Sectors and Contact as contact-card link tiles (the thank-you page's markup, components/contact.css), then the
 * phone line. Copy: ui('errors').
 */
$copy = ui('errors');
$l = $copy['links'];
$items = [
    $l['home'] + ['url' => '/'],
    $l['services'] + ['url' => '/services/'],
];
foreach (services() as $category) {
    $items[] = ['label' => $l['category']['label'], 'title' => $category['name'], 'text' => $category['summary'], 'icon' => $category['icon'], 'url' => $category['url']];
}
$items[] = $l['sectors'] + ['url' => '/sectors/'];
$items[] = $l['contact'] + ['url' => '/contact-us/'];
$phone = '<a href="tel:' . e((string) config('phone_e164')) . '">' . e((string) config('phone_display')) . '</a>';
?>
<section class="nf-links" aria-labelledby="nf-links-title" data-header-theme="light">
  <div class="container">
    <h2 class="visually-hidden" id="nf-links-title"><?= e($copy['links_title']) ?></h2>
    <ul class="nf-links__grid" role="list" data-anim="stagger">
      <?php foreach ($items as $item): ?>
      <li class="nf-links__item">
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
    <p class="nf-links__phone"><?= icon('phone', 'light', ['size' => 18]) ?><span><?= strtr(e($copy['phone']), ['{phone}' => $phone]) ?></span></p>
  </div>
</section>
