<?php
declare(strict_types=1);

/**
 * Vercel entry point (vercel-php runtime, see vercel.json). Vercel serves the files in public/ itself and
 * rewrites every other request here, so this only hands over to the normal front controller.
 */

require dirname(__DIR__) . '/public/index.php';
