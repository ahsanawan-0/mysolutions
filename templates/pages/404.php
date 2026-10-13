<?php
declare(strict_types=1);
/** Not found. Real 404 status (set by the Router); the designed page arrives in P29. */
$page = [
    'title'      => 'Page not found | My Solutionz',
    'robots'     => 'noindex, follow',
    'status'     => 'published',
    'body_class' => 'page-404',
];
?>
<h1>Page not found</h1>
<p>The page you were looking for doesn't exist or has moved.</p>
<ul>
  <li><a href="<?= e(url('/')) ?>">Home</a></li>
  <li><a href="<?= e(url('/services/')) ?>">Services</a></li>
  <li><a href="<?= e(url('/sectors/')) ?>">Sectors</a></li>
  <li><a href="<?= e(url('/contact-us/')) ?>">Contact us</a></li>
</ul>
