<?php
declare(strict_types=1);
/**
 * C02 Two-voice heading partial (the heading() helper is the usual shortcut).
 * @var int         $level   1–6
 * @var string      $text    Grotesk part
 * @var string|null $accent  Italic serif accent words (max 2–3 words)
 * @var string|null $class
 * @var string|null $id
 */
echo heading((int) $level, $text, $accent ?? null, $class ?? '', $id ?? null), "\n";
