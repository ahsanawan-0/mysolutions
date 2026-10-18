<?php
declare(strict_types=1);
/**
 * The {{cookie-settings}} slot of content/legal/cookie-policy.md (PROMPTS P28): with optional technologies configured,
 * a button that opens the consent dialog (consent.js handles every [data-cookie-settings]); otherwise a line saying
 * there is nothing to switch off.
 */
use App\Consent;
?>
<?php if (Consent::needsBanner()): ?>
<p class="cookie-settings">
  <button class="btn btn--secondary" type="button" data-cookie-settings><?= icon('cookie', 'regular', ['size' => 18]) ?><span>Change cookie settings</span></button>
</p>
<?php else: ?>
<p class="cookie-settings">We only use strictly necessary technologies at the moment, so there is nothing to switch off. If we add analytics or anything else optional, a banner will ask for your choice before it runs, and a "Cookie settings" link at the bottom of every page will let you change it.</p>
<?php endif; ?>
