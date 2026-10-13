<?php
declare(strict_types=1);

namespace App\Controllers;

/**
 * /llms.txt: short Markdown summary for AI crawlers (docs/10 §8), generated from config + services data.
 * Not used by Google Search; harmless and cheap to keep accurate.
 */
final class LlmsController
{
    public function show(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');

        $services = services();
        $sectors = sectors();
        $address = config('address');

        $out = [];
        $out[] = '# ' . config('name');
        $out[] = '';
        $out[] = '> ' . config('tagline') . '. ' . config('description');
        $out[] = '';
        $out[] = config('legal_name') . ' (company no. ' . config('company_number') . ', registered in ' . config('registered_in') . ') is based at '
            . $address['street'] . ', ' . $address['locality'] . ' ' . $address['postcode'] . ', United Kingdom, with ' . config('experience') . '. Services are delivered UK-wide.';
        $out[] = '';
        $out[] = '## Services';
        $out[] = '';
        foreach ($services as $category) {
            $out[] = '- [' . $category['name'] . '](' . url('/services/' . $category['slug'] . '/') . '): ' . $category['summary'];
            foreach ($category['subs'] as $sub) {
                $out[] = '  - [' . $sub['name'] . '](' . url('/services/' . $category['slug'] . '/' . $sub['slug'] . '/') . '): ' . $sub['summary'];
            }
        }
        $out[] = '';
        $out[] = '## Sectors';
        $out[] = '';
        foreach ($sectors as $sector) {
            $out[] = '- [' . $sector['name'] . '](' . url('/sectors/' . $sector['slug'] . '/') . ')';
        }
        $out[] = '';
        $out[] = '## Key pages';
        $out[] = '';
        $out[] = '- [About](' . url('/about-us/') . ')';
        $out[] = '- [Insights](' . url('/insights/') . ')';
        $out[] = '- [FAQ](' . url('/faq/') . ')';
        $out[] = '- [Contact and free quote](' . url('/contact-us/') . '): ' . config('email') . ' · ' . config('phone_display');
        $out[] = '';
        $out[] = '## Optional';
        $out[] = '';
        $out[] = '- [Sitemap](' . url('/sitemap.xml') . ')';
        $out[] = '- [Privacy policy](' . url('/privacy-policy/') . ')';

        echo implode("\n", $out), "\n";
    }
}
