<?php
declare(strict_types=1);
/** Auto-reply to the sender, plain-text part (PROMPTS P23). Same variables as emails/autoreply.php. */
$fill = static fn (string $s): string => strtr($s, [
    '{name}'    => strtok($v['name'], ' ') ?: $v['name'],
    '{phone}'   => (string) config('phone_display'),
    '{email}'   => (string) config('email'),
    '{address}' => config('address.street') . ', ' . config('address.locality') . ' ' . config('address.postcode'),
    '{privacy}' => url('/privacy-policy/'),
]);
echo $fill($t['heading']), "\n\n";
foreach ($t['paras'] as $para) {
    echo $fill($para), "\n\n";
}
if ($service !== '' || $sector !== '') {
    echo $t['summary'], ': ', implode(' · ', array_filter([$service, $sector])), "\n\n";
}
echo $t['signoff'], "\n", $t['team'], "\n\n--\n", $fill($t['contact']), "\n", $fill($t['privacy']), "\n";
echo config('legal_name'), ' · Company no. ', config('company_number'), "\n";
