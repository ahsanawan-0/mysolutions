<?php
declare(strict_types=1);
/**
 * /terms-and-conditions/ (PROMPTS P28, docs/13): content/legal/terms-and-conditions.md through App\Legal and sections/legal/document.
 * Draft until the client (or their adviser) approves the text.
 */
$doc = App\Legal::load('terms-and-conditions');
$page = App\Legal::page($doc);
?>
<?= render('sections/legal/document', ['doc' => $doc]) ?>
