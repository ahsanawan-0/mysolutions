<?php
declare(strict_types=1);
/**
 * C25 Back-to-top: fixed bottom-right square (Azure 650, radius 12, arrow-up) inside a circular
 * scroll-progress ring. Shown after one viewport by src/js/modules/back-to-top.js (hidden without JS).
 * Printed once by templates/layouts/base.php.
 */
?>
<button class="back-to-top" type="button" data-back-to-top aria-label="Back to top">
  <svg class="back-to-top__ring" viewBox="0 0 56 56" aria-hidden="true" focusable="false">
    <circle class="back-to-top__track" cx="28" cy="28" r="26"/>
    <circle class="back-to-top__progress" cx="28" cy="28" r="26"/>
  </svg>
  <span class="back-to-top__square"><?= icon('arrow-up', 'regular', ['size' => 22]) ?></span>
</button>
