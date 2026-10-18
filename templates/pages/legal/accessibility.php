<?php
declare(strict_types=1);
/**
 * /accessibility/ (PROMPTS P28, docs/13): content/legal/accessibility.md through App\Legal and sections/legal/document.
 * Draft until the client (or their adviser) approves the text.
 */
$doc = App\Legal::load('accessibility');
$page = App\Legal::page($doc);
?>
<?= render('sections/legal/document', ['doc' => $doc]) ?>
