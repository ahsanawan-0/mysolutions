<?php
declare(strict_types=1);
/**
 * /privacy-policy/ (PROMPTS P28, docs/13): content/legal/privacy-policy.md through App\Legal and sections/legal/document.
 * Draft until the client (or their adviser) approves the text.
 */
$doc = App\Legal::load('privacy-policy');
$page = App\Legal::page($doc);
?>
<?= render('sections/legal/document', ['doc' => $doc]) ?>
