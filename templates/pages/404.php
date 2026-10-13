<?php
declare(strict_types=1);
/** Not found. Real 404 status (set by the Router); no canonical, no structured data. The designed page arrives in P29. */
$page = [
    'title'      => 'Sorry, that page was not found | My Solutionz',
    'canonical'  => false,
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
