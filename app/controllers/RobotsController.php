<?php
declare(strict_types=1);

namespace App\Controllers;

/**
 * /robots.txt (docs/10 §8). Production: allow all, disallow /thank-you/, sitemap line, AI-training
 * crawler block switchable via config seo.block_ai_training. Non-production: Disallow: /.
 */
final class RobotsController
{
    private const AI_TRAINING_BOTS = ['GPTBot', 'ClaudeBot', 'CCBot', 'Google-Extended', 'Applebot-Extended', 'Bytespider'];
    private const AI_SEARCH_BOTS = ['OAI-SearchBot', 'Claude-SearchBot', 'PerplexityBot'];

    public function show(): void
    {
        header('Content-Type: text/plain; charset=UTF-8');
        header('Cache-Control: public, max-age=3600');

        if (!is_production()) {
            echo "# Non-production environment: nothing here should be indexed\n";
            echo "User-agent: *\nDisallow: /\n";
            return;
        }

        $lines = [
            '# My Solutionz (https://mysolutionz.co.uk/)',
            'User-agent: *',
            'Allow: /',
            'Disallow: /thank-you/',
            '',
            '# AI search crawlers are welcome (they cite sources)',
        ];
        foreach (self::AI_SEARCH_BOTS as $bot) {
            $lines[] = "User-agent: {$bot}";
            $lines[] = 'Allow: /';
            $lines[] = '';
        }

        $block = (bool) config('seo.block_ai_training', false);
        $lines[] = '# AI training crawlers (business decision; set seo.block_ai_training in app/config/site.php).';
        $lines[] = '# Blocking them does not affect Google Search ranking.';
        foreach (self::AI_TRAINING_BOTS as $bot) {
            $prefix = $block ? '' : '# ';
            $lines[] = "{$prefix}User-agent: {$bot}";
            $lines[] = "{$prefix}Disallow: /";
            $lines[] = '';
        }

        $lines[] = 'Sitemap: ' . url('/sitemap.xml');
        if (config('seo.legacy_sitemap', false)) {
            $lines[] = 'Sitemap: ' . url('/legacy-sitemap.xml');
        }
        echo implode("\n", $lines), "\n";
    }
}
