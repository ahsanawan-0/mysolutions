<?php
declare(strict_types=1);
/** Gone. Served with status 410 for junk WordPress URLs that were removed on purpose (docs/10 §2). */
$page = [
    'title'      => 'This page has been removed | My Solutionz',
    'robots'     => 'noindex, follow',
    'status'     => 'published',
    'body_class' => 'page-410',
];
?>
<h1>This page has been removed</h1>
<p>The content that used to live here is no longer available.</p>
<ul>
  <li><a href="<?= e(url('/')) ?>">Home</a></li>
  <li><a href="<?= e(url('/services/')) ?>">Services</a></li>
  <li><a href="<?= e(url('/insights/')) ?>">Insights</a></li>
</ul>
