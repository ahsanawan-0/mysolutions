<?php
declare(strict_types=1);
/**
 * The {{cookie-table}} slot of content/legal/cookie-policy.md (PROMPTS P28, docs/13 §4): every technology in use on
 * this deployment, from app/config/cookies.php via App\Consent, so the policy and the banner never diverge.
 * docs/13's columns in five, so the table fits the 720px text column on desktop (it scrolls sideways on phones):
 * name (+ type, provider) · purpose · category (+ consent exempt or not) · duration · how to object or withdraw.
 */
use App\Consent;

$categories = (array) Consent::config('categories', []);
$banner = Consent::needsBanner();
$howTo = static function (array $t) use ($banner): string {
    if ($t['category'] === 'necessary') {
        return 'Not needed: the site cannot work without it. You can delete it in your browser settings.';
    }
    return $banner ? 'Cookie settings (bottom of every page): switch ' . $t['category'] . ' off.' : 'Delete it in your browser settings.';
};
$exempt = static fn (array $t): string => !$t['exempt']
    ? 'Only with your consent'
    : ($t['category'] === 'necessary' ? 'No consent needed: strictly necessary' : 'No consent needed: statistics (DUAA 2025); you can opt out');
?>
<div class="prose-table cookie-table">
<table>
  <thead>
    <tr><th scope="col">Name and provider</th><th scope="col">Purpose</th><th scope="col">Category</th><th scope="col">Duration</th><th scope="col">How to object or withdraw</th></tr>
  </thead>
  <tbody>
    <?php foreach (Consent::technologies() as $t): ?>
    <tr>
      <th scope="row"><code><?= e($t['name']) ?></code><span class="cookie-table__meta"><?= e($t['type']) ?> · <?= e($t['provider']) ?></span></th>
      <td><?= e($t['purpose']) ?></td>
      <td><?= e((string) ($categories[$t['category']]['label'] ?? $t['category'])) ?><span class="cookie-table__meta"><?= e($exempt($t)) ?></span></td>
      <td><?= e($t['duration']) ?></td>
      <td><?= e($howTo($t)) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
</div>
