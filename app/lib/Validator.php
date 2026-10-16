<?php
declare(strict_types=1);

namespace App;

/**
 * Server-side input cleaning and checks for the site's forms (PROMPTS P23, docs/11 §7). Every value is cleaned before
 * it is checked or used: control characters are stripped (single-line fields lose every line break, so nothing can
 * inject an email header), whitespace is trimmed and multi-line text keeps only \n line breaks.
 */
final class Validator
{
    /** A cleaned string from the request (arrays and missing keys become ''). */
    public static function input(array $source, string $key, bool $multiline = false): string
    {
        $value = $source[$key] ?? '';
        return is_string($value) ? self::clean($value, $multiline) : '';
    }

    public static function clean(string $value, bool $multiline = false): string
    {
        $value = str_replace(["\r\n", "\r"], "\n", $value);
        if (!mb_check_encoding($value, 'UTF-8')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        }
        // C0/C1 control characters except tab and (multi-line only) newline
        $value = preg_replace($multiline ? '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F\x{80}-\x{9F}]/u' : '/[\x00-\x1F\x7F\x{80}-\x{9F}]/u', $multiline ? '' : ' ', $value) ?? '';
        if ($multiline) {
            $value = preg_replace("/\n{3,}/", "\n\n", $value) ?? $value;   // no walls of blank lines
        } else {
            $value = preg_replace('/\s+/u', ' ', $value) ?? $value;
        }
        return trim($value);
    }

    public static function length(string $value, int $min, int $max): bool
    {
        $n = mb_strlen($value);
        return $n >= $min && $n <= $max;
    }

    public static function email(string $value): bool
    {
        return mb_strlen($value) <= 254 && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /** UK-friendly phone: digits, spaces, + ( ) - only, 7–20 digits in total. */
    public static function phone(string $value): bool
    {
        $digits = preg_replace('/\D/', '', $value) ?? '';
        return preg_match('/^[0-9 +()\-]+$/', $value) === 1 && strlen($digits) >= 7 && strlen($digits) <= 20;
    }

    /** @param string[] $allowed */
    public static function oneOf(string $value, array $allowed): bool
    {
        return in_array($value, $allowed, true);
    }

    /** A real Y-m-d date; with $notBefore, not earlier than that date. */
    public static function date(string $value, ?string $notBefore = null): bool
    {
        $date = \DateTimeImmutable::createFromFormat('!Y-m-d', $value);
        if ($date === false || $date->format('Y-m-d') !== $value) {
            return false;
        }
        return $notBefore === null || $value >= $notBefore;
    }
}
