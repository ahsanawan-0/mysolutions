<?php
declare(strict_types=1);
/**
 * Enquiry email to the office, plain-text part (PROMPTS P23). Same variables as emails/enquiry.php. Plain text, so
 * nothing is HTML-escaped; the values are already cleaned (no control characters, single-line fields).
 */
$lines = [
    $t['labels']['name']      => $v['name'],
    $t['labels']['company']   => $v['company'],
    $t['labels']['email']     => $v['email'],
    $t['labels']['phone']     => $v['phone'],
    $t['labels']['sector']    => $sector,
    $t['labels']['service']   => $service,
    $t['labels']['deadline']  => $v['deadline'] !== '' ? (new DateTimeImmutable($v['deadline']))->format('j F Y') : '',
    $t['labels']['callback']  => $v['callback'] !== '' ? (string) contact('form.options.callback.' . $v['callback']) : '',
    $t['labels']['marketing'] => $v['marketing'] ? $t['marketing']['yes'] : $t['marketing']['no'],
];
echo $t['heading'], "\n", str_repeat('=', mb_strlen($t['heading'])), "\n\n";
foreach ($lines as $label => $value) {
    if ($value !== '') {
        echo $label, ': ', $value, "\n";
    }
}
echo "\n", $t['labels']['message'], ":\n", $v['message'], "\n\n--\n", strtr($t['footer'], ['{site}' => (string) config('url')]), "\n";
