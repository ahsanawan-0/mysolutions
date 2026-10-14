<?php
declare(strict_types=1);

/**
 * Client testimonials (PROMPTS P15, docs/13 §7). Read via testimonials().
 *
 * EMPTY ON PURPOSE: the home testimonials marquee (templates/sections/home/testimonials.php) renders only when this
 * array has items. Add a testimonial only when it is genuine, attributable and the client holds the author's written
 * permission (CMA fake-reviews guidance under the DMCC Act, in force since 6 April 2025). Never mark testimonials up
 * as Review / AggregateRating schema (docs/10 §6): they are plain visible quotes.
 *
 * Each item (C04 fills this file):
 *   [
 *       'quote'           => 'One or two sentences in the client's own words.',
 *       'name'            => 'Jane Smith',                 // or 'Registered Manager' when anonymised with consent
 *       'role'            => 'Registered Manager',
 *       'company'         => 'Example Care Ltd',           // or null when anonymised
 *       'sector'          => 'healthcare-social-care',     // slug from app/data/sectors.php (shown as a chip)
 *       'permission_date' => '2026-10-15',                 // ISO date the written permission was received
 *   ],
 */
return [];
