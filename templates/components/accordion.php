<?php
declare(strict_types=1);
/**
 * C17 Accordion (docs/03 §3 C17, PROMPTS P15): native <details>/<summary>, so every answer is in the server HTML and
 * the control works without JS and with the keyboard (Tab to the summary, Enter/Space toggles). With JS,
 * src/js/modules/accordion.js animates the panel height (GSAP) and closes the others with the same animation when a
 * group name is set; the plus → minus morph is CSS. Under reduced motion the browser's native toggle runs and
 * <details name> keeps "one open at a time" native where supported.
 * @var array       $items      [['id' => 'cost', 'q' => 'Question', 'a' => 'Answer text'], …] (a is plain text
 *                              printed with rich_blocks(): paragraphs separated by blank lines, "- " lines = a list,
 *                              **bold**, [label](/path/) and [label](https://…) links; P27). An item with
 *                              'hidden' => true is printed with the `hidden` attribute (the /faq/ search, P27)
 * @var string|null $name       Group name → one item open at a time (omit for independent items)
 * @var bool|null   $open       Open the first item on load (default false)
 * @var int|null    $level      Heading level for each question (default 3; 0 = plain text, e.g. inside a sidebar)
 * @var string|null $id_prefix  Prefix for the item ids (default 'faq')
 * @var string|null $class
 */
$items = $items ?? [];
$level = $level ?? 3;
$prefix = $id_prefix ?? 'faq';
$qTag = $level > 0 ? 'h' . $level : 'span';
?>
<div class="accordion<?= isset($class) ? ' ' . e($class) : '' ?>" data-module="accordion">
  <?php foreach ($items as $i => $item): ?>
  <details class="accordion__item" id="<?= e($prefix . '-' . ($item['id'] ?? $i)) ?>"<?= isset($name) ? ' name="' . e($name) . '"' : '' ?><?= !empty($open) && $i === 0 ? ' open' : '' ?><?= !empty($item['hidden']) ? ' hidden' : '' ?>>
    <summary class="accordion__summary">
      <<?= $qTag ?> class="accordion__q"><?= e($item['q']) ?></<?= $qTag ?>>
      <span class="accordion__icon" aria-hidden="true"></span>
    </summary>
    <div class="accordion__panel">
      <div class="accordion__body">
        <?= rich_blocks((string) $item['a']) ?>
      </div>
    </div>
  </details>
  <?php endforeach; ?>
</div>
