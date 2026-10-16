<?php
declare(strict_types=1);

namespace App;

/**
 * Simple sliding-window rate limit with a file store (PROMPTS P23: 5 enquiries / hour / IP). One JSON file per key in
 * storage/cache/ratelimit/ (the key is hashed, so no IP address is stored in clear). File locking keeps concurrent
 * requests consistent.
 *
 *   RateLimit::tooMany('contact', $ip, 5, 3600)  → true when the key already has 5 hits in the last hour
 *   RateLimit::hit('contact', $ip, 3600)         → record one hit
 */
final class RateLimit
{
    public static function tooMany(string $bucket, string $key, int $max, int $window): bool
    {
        return count(self::read($bucket, $key, $window)) >= $max;
    }

    public static function hit(string $bucket, string $key, int $window): void
    {
        $file = self::file($bucket, $key);
        $handle = fopen($file, 'c+');
        if ($handle === false) {
            return;
        }
        flock($handle, LOCK_EX);
        $hits = self::decode((string) stream_get_contents($handle), $window);
        $hits[] = time();
        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, (string) json_encode($hits));
        flock($handle, LOCK_UN);
        fclose($handle);
    }

    /** @return int[] hit timestamps inside the window */
    private static function read(string $bucket, string $key, int $window): array
    {
        $file = self::file($bucket, $key);
        return is_file($file) ? self::decode((string) file_get_contents($file), $window) : [];
    }

    /** @return int[] */
    private static function decode(string $json, int $window): array
    {
        $hits = json_decode($json, true);
        $since = time() - $window;
        return is_array($hits) ? array_values(array_filter($hits, static fn ($t): bool => is_int($t) && $t > $since)) : [];
    }

    private static function file(string $bucket, string $key): string
    {
        $dir = storage_path('cache/ratelimit');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        return $dir . '/' . preg_replace('/[^a-z0-9_-]/', '', $bucket) . '-' . hash('sha256', $key) . '.json';
    }
}
