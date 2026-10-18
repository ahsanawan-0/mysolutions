<?php
declare(strict_types=1);

/**
 * Cookies and similar technologies (PROMPTS P28, docs/13 §2 + §4). One list drives the consent banner, the
 * preferences dialog and the table on /cookie-policy/, so they never diverge. Read through App\Consent.
 *
 * analytics_provider (.env ANALYTICS_PROVIDER): none (default) | plausible | ga4. With `none` and no marketing
 * technologies there is no banner at all; the cookie policy still lists the strictly necessary items.
 * analytics_exempt (.env ANALYTICS_EXEMPT, default false): Plausible only. True relies on the DUAA 2025 statistical
 * exemption: analytics runs without opt-in, the banner becomes an information notice and the preferences dialog
 * offers an objection toggle. GA4 is always consent-first (the ICO does not confirm it qualifies).
 * version: bump it when the technologies or the cookie policy change materially; everyone is asked again.
 *
 * Each technology: name, type, provider, purpose, duration, category (necessary | analytics | marketing),
 * exempt (true | false | null = follows analytics_exempt), when (optional: ga4 | plausible | turnstile, listed only
 * when that is in use). The `{ga4_stream}` token in a name becomes the GA4 measurement ID without its "G-".
 */
$provider = strtolower(trim((string) env('ANALYTICS_PROVIDER', 'none')));

return [
    'version'      => 1,
    'cookie'       => 'ms_consent',
    'max_age_days' => 182,   // 6 months: re-ask after that (docs/13 §2)

    'analytics_provider' => in_array($provider, ['plausible', 'ga4'], true) ? $provider : 'none',
    'analytics_id'       => trim((string) env('ANALYTICS_ID', '')),       // GA4: G-XXXXXXXXXX; Plausible: the site domain
    'analytics_exempt'   => filter_var(env('ANALYTICS_EXEMPT', 'false'), FILTER_VALIDATE_BOOLEAN),
    'plausible_src'      => env('PLAUSIBLE_SRC', 'https://plausible.io/js/script.js'),

    'categories' => [
        'necessary' => [
            'label'       => 'Strictly necessary',
            'description' => "Needed for the site to work and to keep the contact form secure. They can't be switched off and don't track you.",
            'required'    => true,
        ],
        'analytics' => [
            'label'       => 'Analytics',
            'description' => 'Help us understand which pages are useful by counting visits in aggregate. We use them only to improve this website.',
        ],
        'marketing' => [
            'label'       => 'Marketing',
            'description' => 'Used to measure or personalise advertising on other websites.',
        ],
    ],

    'technologies' => [
        [
            'name'     => 'ms_consent',
            'type'     => 'Cookie',
            'provider' => 'My Solutionz (this website)',
            'purpose'  => 'Remembers your cookie choices, so we do not ask on every page.',
            'duration' => '6 months',
            'category' => 'necessary',
            'exempt'   => true,
        ],
        [
            'name'     => 'ms_session',
            'type'     => 'Cookie',
            'provider' => 'My Solutionz (this website)',
            'purpose'  => 'Protects the contact form against forged submissions and confirms your enquiry on the thank-you page. Set only when you open the contact form.',
            'duration' => 'Until you close your browser',
            'category' => 'necessary',
            'exempt'   => true,
        ],
        [
            'name'     => 'ms_splash',
            'type'     => 'Session storage',
            'provider' => 'My Solutionz (this website)',
            'purpose'  => 'Remembers that the opening logo animation has played, so it shows once per visit.',
            'duration' => 'Until you close the tab',
            'category' => 'necessary',
            'exempt'   => true,
        ],
        [
            'name'     => 'Cloudflare Turnstile',
            'type'     => 'Security check',
            'provider' => 'Cloudflare, Inc.',
            'purpose'  => 'Checks that the contact form is sent by a person rather than a bot, without a puzzle.',
            'duration' => 'Not stored on your device',
            'category' => 'necessary',
            'exempt'   => true,
            'when'     => 'turnstile',
        ],
        [
            'name'     => '_ga',
            'type'     => 'Cookie',
            'provider' => 'Google Analytics (Google LLC)',
            'purpose'  => 'Distinguishes visitors so we can count visits and see which pages are used.',
            'duration' => '2 years',
            'category' => 'analytics',
            'exempt'   => false,
            'when'     => 'ga4',
        ],
        [
            'name'     => '_ga_{ga4_stream}',
            'type'     => 'Cookie',
            'provider' => 'Google Analytics (Google LLC)',
            'purpose'  => 'Keeps track of your current visit for Google Analytics.',
            'duration' => '2 years',
            'category' => 'analytics',
            'exempt'   => false,
            'when'     => 'ga4',
        ],
        [
            'name'     => 'Plausible Analytics',
            'type'     => 'Script (no cookies)',
            'provider' => 'Plausible Insights OÜ (EU)',
            'purpose'  => 'Counts page views, referring sites and device types in aggregate. Uses no cookies and does not identify you.',
            'duration' => 'Nothing stored on your device',
            'category' => 'analytics',
            'exempt'   => null,
            'when'     => 'plausible',
        ],
        // Marketing: none. Add LinkedIn Insight, Meta Pixel or Google Ads tags here (category marketing, exempt false)
        // only if the client runs ads, and bump `version`.
    ],
];
