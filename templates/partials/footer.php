<?php
declare(strict_types=1);
/**
 * C23 Site footer (docs/08 §3 as revised by the client on 13 Oct 2026, docs/13 §1).
 * A rounded Navy 900 panel inset on the canvas (Innovax footer reference, docs/04 §1): a glass brand card
 * (logo · description · office hours · socials) + Services / Sectors / Company columns, a contact row with
 * icon circles, and a decorative word strip along the bottom edge. The company disclosure + legal links sit
 * below the panel. Every fact comes from app/config/site.php; copy from app/data/ui.php; lists from app/data/.
 */
$services = services();
$sectors = sectors();
$address = config('address');
$hours = config('hours.days') ?? [];
$short = static fn (string $day): string => substr($day, 0, 3);
$legalLinks = ui('footer.legal_links');
$companyLinks = ui('footer.company_links');
$marquee = ui('footer.marquee');
$href = static fn (string $u): string => str_starts_with($u, '/') ? url($u) : $u;
?>
<footer class="site-footer">
  <div class="site-footer__panel on-dark" data-header-theme="dark">
    <div class="site-footer__glow" aria-hidden="true"></div>

    <div class="site-footer__grid" data-anim="fade-up">
      <div class="site-footer__card glass-thin-dark">
        <a class="site-footer__logo" href="<?= e(url('/')) ?>" aria-label="<?= e(config('name')) ?> home"><?= logo('white', ['height' => 44]) ?></a>
        <p class="site-footer__description"><?= e(ui('footer.description')) ?></p>
        <?php if ($hours !== []): ?>
        <dl class="site-footer__hours" aria-label="Office hours">
          <?php foreach ($hours as $row): ?>
          <div class="site-footer__hours-row">
            <dt><?= e($short($row['days'][0])) ?><?= count($row['days']) > 1 ? ' – ' . e($short($row['days'][count($row['days']) - 1])) : '' ?></dt>
            <dd><?= e($row['opens']) ?> – <?= e($row['closes']) ?></dd>
          </div>
          <?php endforeach; ?>
        </dl>
        <?php endif; ?>
        <ul class="site-footer__socials" aria-label="Social media">
          <li><a href="<?= e(config('socials.linkedin')) ?>" rel="noopener" aria-label="LinkedIn"><?= icon('linkedin-logo', 'regular', ['size' => 20]) ?></a></li>
          <li><a href="<?= e(config('socials.instagram')) ?>" rel="noopener" aria-label="Instagram"><?= icon('instagram-logo', 'regular', ['size' => 20]) ?></a></li>
          <li><a href="<?= e(config('socials.facebook')) ?>" rel="noopener" aria-label="Facebook"><?= icon('facebook-logo', 'regular', ['size' => 20]) ?></a></li>
          <li><a href="<?= e(config('whatsapp')) ?>" rel="noopener" aria-label="WhatsApp"><?= icon('whatsapp-logo', 'regular', ['size' => 20]) ?></a></li>
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

      <nav class="site-footer__col" aria-labelledby="footer-company">
        <h2 class="site-footer__heading" id="footer-company">Company</h2>
        <ul class="site-footer__list">
          <?php foreach ($companyLinks as [$label, $path]): ?>
          <li><a class="site-footer__link" href="<?= e($href($path)) ?>"><?= e($label) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>
    </div>

    <address class="site-footer__contact" data-anim="stagger">
      <div class="site-footer__contact-item">
        <span class="site-footer__contact-icon"><?= icon('map-pin', 'regular', ['size' => 22]) ?></span>
        <span class="site-footer__contact-text"><span class="site-footer__contact-label">Visit our office</span><span><?= e($address['street']) ?>, <?= e($address['locality']) ?> <?= e($address['postcode']) ?></span></span>
      </div>
      <div class="site-footer__contact-item">
        <span class="site-footer__contact-icon"><?= icon('envelope-simple', 'regular', ['size' => 22]) ?></span>
        <span class="site-footer__contact-text"><span class="site-footer__contact-label">Email us</span><a href="mailto:<?= e(config('email')) ?>"><?= e(config('email')) ?></a></span>
      </div>
      <div class="site-footer__contact-item">
        <span class="site-footer__contact-icon"><?= icon('phone', 'regular', ['size' => 22]) ?></span>
        <span class="site-footer__contact-text"><span class="site-footer__contact-label">Call or WhatsApp</span><a href="tel:<?= e(config('phone_e164')) ?>"><?= e(config('phone_display')) ?></a></span>
      </div>
    </address>

    <div class="site-footer__strip" aria-hidden="true">
      <div class="site-footer__strip-track">
        <?php for ($i = 0; $i < 2; $i++): ?>
        <ul class="site-footer__strip-list">
          <?php foreach ($marquee as $word): ?>
          <li><?= e($word) ?></li>
          <?php endforeach; ?>
        </ul>
        <?php endfor; ?>
      </div>
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
</footer>
