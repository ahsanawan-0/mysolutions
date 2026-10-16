<?php
declare(strict_types=1);
/**
 * Bad request. Served with status 400 by App\Controllers\ContactController when a form arrives without a valid CSRF
 * token (expired session, cookies blocked, or a forged post). Nothing was sent; the visitor is pointed back to the form.
 */
$page = [
    'title'      => 'Your form session has expired | My Solutionz',
    'canonical'  => false,
    'robots'     => 'noindex, follow',
    'status'     => 'published',
    'body_class' => 'page-400',
];
?>
<div class="container error-page">
  <h1>Your form session has expired</h1>
  <p>For your security we could not accept that form. Nothing has been sent. Please go back to the form and try again; make sure cookies are allowed for this site.</p>
  <ul>
    <li><a href="<?= e(url('/contact-us/')) ?>#quote">Back to the enquiry form</a></li>
    <li><a href="tel:<?= e((string) config('phone_e164')) ?>">Call us on <?= e((string) config('phone_display')) ?></a></li>
    <li><a href="mailto:<?= e((string) config('email')) ?>">Email <?= e((string) config('email')) ?></a></li>
  </ul>
</div>
