<?php
declare(strict_types=1);
/**
 * First-visit splash (docs/07 §4–§5). The vertical lockup (brand/logo-splash.svg) plays the pixel2motion
 * choreography from splash.css over a canvas-coloured overlay; src/js/modules/splash.js then flies the mark,
 * wordmark and tagline onto the header logo (GSAP Flip) and splits the overlay along the 120° brand diagonal.
 * Printed on every page by the base layout, but:
 *  - shown only under html.js (no-JS visitors never see it) and never under prefers-reduced-motion (splash.css);
 *  - once per session: the inline script reads sessionStorage before first paint, so repeat views never flash it;
 *  - aria-hidden, no headings, no focusable content: everything real is server-rendered beneath it;
 *  - a CSS-only failsafe fades it out at 2.6 s, the module removes it by 2.4 s at the latest.
 * Ids are unprefixed on purpose (#logo-root, #mark, #hex-sky …): the choreography targets them and this is the
 * only unprefixed copy of the lockup on a page (header: hl-, CTA band: cta-).
 * P34 (CSP nonces): the inline script below needs a nonce, like the js-class script in head-seo.php.
 */
?>
<script>try{if(sessionStorage.getItem('ms_splash'))document.documentElement.classList.add('no-splash')}catch(e){}</script>
<div id="splash" class="splash" aria-hidden="true" data-splash>
  <div class="splash__panel" data-splash-panel="a"></div>
  <div class="splash__panel" data-splash-panel="b"></div>
  <div id="logo-root" class="splash__logo"><?= inline_svg('brand/logo-splash.svg', ['title' => false]) ?></div>
</div>
