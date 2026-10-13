<?php
declare(strict_types=1);
/**
 * C23 Site footer (docs/08 §3, docs/13 §1): Navy 900 with the grid-wave art strip on its top edge.
 * Col 1 logo + tagline + description + socials · Col 2 services (categories + subs) · Col 3 sectors · Col 4 contact.
 * Bottom bar: the exact company disclosure + legal links + "Cookie settings" button (P28 consent module listens).
 * Every fact comes from app/config/site.php; copy from app/data/ui.php; lists from app/data/{services,sectors}.php.
 */
$services = services();
$sectors = sectors();
$quoteUrl = url('/contact-us/#quote');
$address = config('address');
$legalLinks = ui('footer.legal_links');
?>
<footer class="site-footer on-dark" data-header-theme="dark">
  <div class="site-footer__art" aria-hidden="true">
    <?= component('grid-wave', ['class' => 'site-footer__wave', 'align' => 'top']) ?>
  </div>

  <div class="container site-footer__inner">
    <div class="site-footer__grid" data-anim="fade-up">
      <div class="site-footer__col site-footer__col--brand">
        <a class="site-footer__logo" href="<?= e(url('/')) ?>" aria-label="<?= e(config('name')) ?> home">
          <span class="logo-text logo-text--light" aria-hidden="true"><span class="logo-text__mark"></span><?= e(config('name')) ?></span>
        </a>
        <p class="site-footer__tagline"><?= e(config('tagline')) ?></p>
        <p class="site-footer__description"><?= e(ui('footer.description')) ?></p>
        <ul class="site-footer__socials" aria-label="Social media">
          <li><a class="btn-icon" href="<?= e(config('socials.linkedin')) ?>" rel="noopener" aria-label="LinkedIn"><?= icon('linkedin-logo', 'regular', ['size' => 24]) ?></a></li>
          <li><a class="btn-icon" href="<?= e(config('socials.instagram')) ?>" rel="noopener" aria-label="Instagram"><?= icon('instagram-logo', 'regular', ['size' => 24]) ?></a></li>
          <li><a class="btn-icon" href="<?= e(config('socials.facebook')) ?>" rel="noopener" aria-label="Facebook"><?= icon('facebook-logo', 'regular', ['size' => 24]) ?></a></li>
        </ul>
      </div>

      <nav class="site-footer__col" aria-labelledby="footer-services">
        <h2 class="site-footer__heading" id="footer-services">Services</h2>
        <ul class="site-footer__services">
          <?php foreach ($services as $category): ?>
          <li>
            <a class="site-footer__link site-footer__link--cat" href="<?= e(url($category['url'])) ?>"><?= e($category['name']) ?></a>
            <ul class="site-footer__subs">
              <?php foreach ($category['subs'] as $sub): ?>
              <li><a class="site-footer__link site-footer__link--sub" href="<?= e(url($sub['url'])) ?>"><?= e($sub['name']) ?></a></li>
              <?php endforeach; ?>
            </ul>
          </li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <nav class="site-footer__col" aria-labelledby="footer-sectors">
        <h2 class="site-footer__heading" id="footer-sectors">Sectors</h2>
        <ul class="site-footer__list">
          <?php foreach ($sectors as $sector): ?>
          <li><a class="site-footer__link" href="<?= e(url($sector['url'])) ?>"><?= e($sector['name']) ?></a></li>
          <?php endforeach; ?>
          <li><a class="site-footer__link site-footer__link--all" href="<?= e(url('/sectors/')) ?>">All sectors</a></li>
        </ul>
      </nav>

      <div class="site-footer__col site-footer__col--contact">
        <h2 class="site-footer__heading" id="footer-contact">Contact</h2>
        <address class="site-footer__address">
          <span class="site-footer__contact-row"><?= icon('map-pin', 'light', ['size' => 20]) ?><span><?= e(config('name')) ?><br><?= e($address['street']) ?><br><?= e($address['locality']) ?> <?= e($address['postcode']) ?><br>United Kingdom</span></span>
          <a class="site-footer__contact-row site-footer__link" href="tel:<?= e(config('phone_e164')) ?>"><?= icon('phone', 'light', ['size' => 20]) ?><span><?= e(config('phone_display')) ?></span></a>
          <a class="site-footer__contact-row site-footer__link" href="<?= e(config('whatsapp')) ?>" rel="noopener"><?= icon('whatsapp-logo', 'light', ['size' => 20]) ?><span>WhatsApp</span></a>
          <a class="site-footer__contact-row site-footer__link" href="mailto:<?= e(config('email')) ?>"><?= icon('envelope-simple', 'light', ['size' => 20]) ?><span><?= e(config('email')) ?></span></a>
          <span class="site-footer__contact-row"><?= icon('clock', 'light', ['size' => 20]) ?><span>Office hours: <?= e(config('hours.summary')) ?></span></span>
        </address>
        <?= component('button', ['label' => 'Get a Free Quote', 'href' => $quoteUrl, 'class' => 'site-footer__cta']) ?>
      </div>
    </div>

    <div class="site-footer__bottom">
      <p class="site-footer__legal">© <?= e(date('Y')) ?> <?= e(config('legal_name')) ?>. Registered in <?= e(config('registered_in')) ?>, company no. <?= e(config('company_number')) ?>. Registered office: <?= e($address['street']) ?>, <?= e($address['locality']) ?> <?= e($address['postcode']) ?>.</p>
      <ul class="site-footer__legal-links" aria-label="Legal">
        <?php foreach ($legalLinks as $i => [$label, $path]): ?>
        <li><a href="<?= e(url($path)) ?>"><?= e($label) ?></a></li>
        <?php if ($i === 1): ?>
        <li><button type="button" data-cookie-settings>Cookie settings</button></li>
        <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</footer>
