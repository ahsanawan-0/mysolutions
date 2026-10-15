<?php
declare(strict_types=1);
/**
 * C33 Office Hours card (docs/04 §1.2 Innovax "Business Hours" card; P10 home approach, P18 About Us): Azure
 * gradient card with the opening hours from config('hours') (provisional until the client confirms, docs/15 P08) and
 * the "email us any time" line. Never claims 24/7 support (docs/13 §6). The card only styles itself: the page
 * positions it (e.g. in the cut-out corner of a .notch image). Default copy: app/data/ui.php → hours_card.
 * @var string|null $title       Card title (default ui('hours_card.title'))
 * @var string|null $closed      Label for closed days
 * @var string|null $email_lead  Text above the e-mail address
 * @var string|null $id          id of the title (the card's aria-labelledby); default 'hours-title'
 * @var string|null $class       Extra classes (page positioning)
 * @var array|null  $attrs       Extra attributes, e.g. ['data-anim' => 'fade-up', 'data-x' => '40']
 */
$d = ui('hours_card');
$title = $title ?? $d['title'];
$closed = $closed ?? $d['closed'];
$emailLead = $email_lead ?? $d['email_lead'];
$id = $id ?? 'hours-title';
$hours = config('hours');

/** "Monday … Friday" → "Mon–Fri"; a single day stays "Sat". */
$dayRange = static function (array $days): string {
    $short = array_map(static fn (string $day): string => substr($day, 0, 3), $days);
    return count($short) > 1 ? $short[0] . '–' . $short[count($short) - 1] : ($short[0] ?? '');
};
$rows = [];
foreach ($hours['days'] ?? [] as $spec) {
    $rows[] = [$dayRange($spec['days']), $spec['opens'] . '–' . $spec['closes']];
}
if (!empty($hours['closed'])) {
    $rows[] = [$dayRange($hours['closed']), $closed];
}
$extra = '';
foreach ($attrs ?? [] as $name => $value) {
    if (preg_match('/^[a-zA-Z][\w-]*$/', (string) $name) === 1) {
        $extra .= ' ' . $name . '="' . e((string) $value) . '"';
    }
}
?>
<aside class="hours-card on-dark<?= isset($class) ? ' ' . e($class) : '' ?>" aria-labelledby="<?= e($id) ?>"<?= $extra ?>>
  <h3 class="hours-card__title" id="<?= e($id) ?>">
    <?= icon('clock', 'regular', ['size' => 20]) ?><?= e($title) ?>
  </h3>
  <dl class="hours-card__list">
    <?php foreach ($rows as [$days, $time]): ?>
    <div class="hours-card__row"><dt><?= e($days) ?></dt><dd><?= e($time) ?></dd></div>
    <?php endforeach; ?>
  </dl>
  <?php if (!empty($hours['email_anytime'])): ?>
  <p class="hours-card__email">
    <span><?= e($emailLead) ?></span>
    <a href="mailto:<?= e(config('email')) ?>"><?= e(config('email')) ?></a>
  </p>
  <?php endif; ?>
</aside>
