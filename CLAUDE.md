# CLAUDE.md — My Solutionz website

**My Solutionz** (My Solutionz Ltd, London) is a UK B2B services company offering bid & proposal management, compliance & virtual assistance, and digital presence services. This repository is its new marketing website: a **custom PHP** site (no WordPress, no framework) with **GSAP + Lenis** motion, Phosphor icons, Apple design principles and full SEO. The project is **docs-first**: every decision lives in `docs/`, and the site is built by running the prompts in `PROMPTS.md` in order (P00 → P35); the client's final copy is dropped in later via the content-fill prompts C01–C07.

## Read before any work
1. **`docs/00-LOCKED-DECISIONS.md`** — the client's locked decisions. Never change them. If a task seems to conflict with them, stop and ask.
2. **`docs/BUILD-LOG.md`** (latest entries) — what earlier prompts already built, their decisions and deviations.
3. The docs relevant to the task (each prompt in `PROMPTS.md` lists its own "Read first" docs):

| # | Doc | What's inside |
|---|---|---|
| 00 | [LOCKED DECISIONS](docs/00-LOCKED-DECISIONS.md) | All client instructions, closed and locked + pending items |
| 01 | [Project brief](docs/01-project-brief.md) | Business, NAP, audience, goals, scope, success metrics |
| 02 | [Brand identity](docs/02-brand-identity.md) | Logo anatomy, exact logo colours + full family with contrast, fonts, icons, imagery |
| 03 | [Design system (Apple)](docs/03-design-system-apple.md) | Apple principles → web rules, tokens (spacing, radii, glass, shadows, motion), 30 components |
| 04 | [Reference teardown](docs/04-reference-teardown.md) | Reverse-engineered Innovax (fonts, glass, notches), all inspiration images, strategicproposals.com |
| 05 | [Hero spec](docs/05-hero-spec.md) | The exact hero: services **slider**, **glowing animated border**, Quick-Start bar, choreography |
| 06 | [Motion and scroll](docs/06-motion-and-scroll.md) | GSAP/Lenis setup, motion catalogue (`data-anim`), strategicproposals patterns, SEO-safe motion |
| 07 | [Splash screen](docs/07-splash-screen-logo-animation.md) | pixel2motion workflow, logo choreography, rules, embedding |
| 08 | [Sitemap and page blueprints](docs/08-sitemap-and-page-blueprints.md) | URLs, navigation, footer, section-by-section plans for every page |
| 09 | [Content inventory](docs/09-content-inventory.md) | Content we have, content scraped from the current site, icon mapping, gaps |
| 10 | [SEO specification](docs/10-seo-specification.md) | Current-site audit, 301 map, keywords, per-page rules, schema, head template, GSAP-SEO rules |
| 11 | [Tech architecture (PHP)](docs/11-tech-architecture-php.md) | Stack, folder structure, routing, rendering contract, security, deployment |
| 12 | [Performance and accessibility](docs/12-performance-accessibility.md) | Core Web Vitals and weight budgets, motion performance rules, WCAG 2.2 AA |
| 13 | [UK legal and compliance](docs/13-uk-legal-compliance.md) | Company disclosures, PECR/UK GDPR cookies (DUAA 2026), privacy, claims |
| 14 | [Build roadmap and prompts plan](docs/14-build-roadmap-and-prompts-plan.md) | The 35-prompt sequence and the prompt format |
| 15 | [Open questions](docs/15-open-questions.md) | What we need from the client (❗ = blocking) |

Client-supplied material: `docs/website_content/` (copy, `pages_ref.jpeg`, current-site scrape, `services/` drafts), `inspirations/` (UI references), `logos/` (horizontal + vertical logo JPEG).

## Global rules (apply to every prompt; also stored in CLAUDE.md by P00)
- Read `docs/00-LOCKED-DECISIONS.md` first. **Never change a locked decision.** If a prompt seems to conflict with the docs, stop and ask.
- Stack: **PHP 8.3+**, no framework, no WordPress. **No Bootstrap, Tailwind, jQuery** or other UI frameworks. Modern CSS + **GSAP 3.13+** + **Lenis**. Icons: **Phosphor only** via the sprite helper. **No runtime CDNs** (fonts, icons, scripts all self-hosted).
- **All content is server-rendered** from data files. GSAP only enhances. Initial hidden states exist only under `html.js`.
- **UK English** everywhere (organisation, optimise, programme). Dates like "13 October 2026". Phone `+44 7466 063564` / `tel:+447466063564`.
- **No lorem ipsum, ever.** Use provisional copy from the prompt or `[[PENDING: …]]`.
- Escape all output with `e()`. Validate all input server-side.
- Every page sets a complete `$page` array (title, description, canonical, og, schema, breadcrumbs, status).
- Every `<img>` has descriptive `alt` (or `alt=""` if decorative), `width`, `height`, and lazy-loading below the fold.
- Colours/spacing/radii/motion values come from **tokens only** (`src/css/tokens.css`); never hard-code a hex value in a component.
- Accessibility: WCAG 2.2 AA, 44px touch targets, `prefers-reduced-motion` and `prefers-reduced-transparency` respected.
- Company facts (never invent others): **My Solutionz Ltd**, company no. **15677749**, registered in England and Wales, registered office **Unit A53, 4–6 Greatorex Street, London E1 5NF**, **info@mysolutionz.co.uk**, **"over 10 years of business development experience"** (never "10 years in business").

## Standard checks (every prompt ends with these; referred to as "Standard checks")
1. `php -l` on every changed PHP file; `npm run build` completes with no errors or warnings.
2. Run the site (`php -S localhost:8000 -t public public/index.php` + `npm run dev`) and open every page touched. **No PHP notices, no console errors, no 404s for assets** (check the Network tab).
3. Check widths **360, 768, 1024, 1440**: no horizontal scroll, nothing clipped.
4. Keyboard-only pass on anything interactive (Tab, Shift+Tab, Enter, Space, Esc): visible focus everywhere.
5. Emulate `prefers-reduced-motion: reduce`: all content visible and readable, no autoplay.
6. View source: content, headings, links and JSON-LD are in the **server HTML**.
7. `php bin/check-content.php` runs (from P03 onwards) and its report is reviewed.
8. Append an entry to `docs/BUILD-LOG.md` (prompt number, files created/changed, decisions, deviations, follow-ups), then **commit** with the message `P##: <title>`.

## Strategy: build the structure now, fill content later
The client is still delivering content, so structure and content are separated:
1. **All copy lives in data files** (`app/data/*.php`, `content/**/*.md`), never hard-coded in templates.
2. Where final copy doesn't exist yet, the data holds **provisional copy** marked with a `// PROVISIONAL` comment, or a visible marker `[[PENDING: what is needed]]`.
3. Every page has a **`status`**: `published` or `draft`. Draft pages render normally on local/staging, but are forced `noindex`, are left out of `sitemap.xml`, and show a small "Draft" ribbon on non-production environments.
4. `php bin/check-content.php` lists every `PROVISIONAL`, `[[PENDING` and `draft` item, so nothing slips through. **Launch gate: zero results.**
5. When the client's content arrives, the **content-fill prompts (C01–C07)** replace data only. No template rework.

## Commands (run from the project root; set up by P01)
| Task | Command |
|---|---|
| Install | `composer install` then `npm install`; copy `.env.example` → `.env` (`APP_ENV=local`, `VITE_DEV=true`) |
| Dev servers | `php -S localhost:8000 -t public public/index.php` **and** `npm run dev` (Vite on :5173). Both are in `.claude/launch.json` (`php`, `vite`) for the desktop preview |
| Production build | `npm run build` → hashed JS/CSS + `public/assets/build/manifest.json`; set `VITE_DEV=false` to load them locally |
| Icons | `npm run icons` → `scripts/build-icons.mjs` builds `public/assets/icons/sprite.svg` from `src/icons.json` (runs automatically before `build`) |
| Images | `npm run images` → `scripts/images.mjs` (sharp) turns `assets-src/images/**` into AVIF/WebP/JPEG at 400–1920w in `public/assets/img/` + `manifest.json` |
| Fonts | `npm run fonts` → `scripts/fonts.py` (fontTools) re-subsets Mona Sans / Google Sans / Playfair into `public/assets/fonts/` and prints the fallback metrics for `src/css/fonts.css` |
| Styleguide | http://localhost:8000/styleguide/ (local only): colours + contrast, type, buttons, every icon, components, glass, image placeholder |
| Content check | `php bin/check-content.php` (`--strict` exits 1): markers, route title/description lengths + duplicates, missing images, missing breadcrumbs. Launch gate = zero findings |
| SEO audit | `php bin/seo-audit.php [--base=http://localhost:8000] [--all]` against the running server: one h1, head tags, JSON-LD parses, img alt/width/height, no `href="#"`. Plus `/seo page <url>` and `/seo audit` from the claude-seo plugin (`docs/10` §0) |
| Logo + favicons | `npm run logo` → `scripts/logo.mjs` measures the client JPEGs in `logos/`, fits the wordmark (Khand, build-time only) and writes `public/assets/brand/*.svg` + `logo-512.png`, `public/favicon.ico`, `icon-*.png`, `apple-touch-icon.png`, `icon.svg`, `site.webmanifest`, the splash input `design/logo-motion/logo.svg` and the wipe geometry in `design/logo-motion/outputs/fit_work/geometry.json` |
| OG image | `npm run og` → `scripts/og.mjs` renders `public/assets/og/default.jpg` (1200×630) from the tokens + the white lockup |
| Lint | `php -l <file>` on every changed PHP file (Standard check 1) |

Request flow: `public/index.php` → `app/bootstrap.php` → `App\Router::dispatch()` (normalise → `app/redirects.php` → `app/routes.php` → 404). Page templates set `$page` and print their body; `templates/layouts/base.php` wraps it. On `local`, a planned route without a template shows `templates/pages/_stub.php`.

Page contract (`docs/11` §4, `app/lib/Page.php`): every template sets `$page` with `title` (30–65 chars), `description` (70–170), `canonical` (path; `false` only for error pages), `status` (`published|draft`), `breadcrumbs` (`[[label, path|null], …]`, required except home), optional `og_*`, `og_type`, `robots`, `schema` (extra JSON-LD nodes from `App\Schema::service()/serviceCategory()/blogPosting()`), `published`/`modified`, `primary_image`, `web_page_type`, `body_class`, `js_modules`. `App\Seo::prepare()` validates it: published pages throw on local; draft pages show warnings in the draft ribbon. Machine files: `/sitemap.xml` (+ `-pages/-services/-sectors/-insights`, `/legacy-sitemap.xml`), `/robots.txt`, `/llms.txt` are controllers in `app/controllers/` driven by `app/routes.php` (`sitemap` group) and `app/data/{services,sectors}.php`.

Navigation (P04): `app/data/{services,sectors,navigation}.php` via `services()`, `service_category()`, `service_sub()`, `sectors()`, `navigation()`. `templates/partials/header.php` has `default` and `hero` variants; pages with a hero set `header_theme: 'dark'` + `header_overlay: true` (and `header: 'hero'` on home); sections can carry `data-header-theme="light|dark"`. Behaviour lives in `src/js/modules/header.js` (eases from `src/js/core/eases.js`).

Motion (P06, `src/js/core/motion.js`): templates only add attributes. `data-anim="fade-up|stagger|split-lines|split-chars|two-voice|parallax|img-reveal|img-zoom-out|counter|fill-text|draw|pin-steps|h-scroll|stack-cards|marquee|magnetic|tilt|wave|velocity-skew|read-along|logo-assembly|micro-arrow|blur-zoom|ring|rise-rotate|cta-mark"` (several space-separated; options such as `data-speed`, `data-to`, `data-stagger` are documented at the top of each `src/js/anim/<name>.js`) and `data-module="nodes"` (lazy `src/js/modules/<name>.js`). Effects load only when used, run inside one `gsap.matchMedia()` (desktop ≥ 1024 / mobile / reduce) and are skipped entirely under reduced motion; pre-states live in `src/css/components/motion.css` under `html.js` only. Lenis is `window.lenis`; same-page `#anchors` scroll through it with the header offset. Demo of every effect: http://localhost:8000/styleguide/motion/ (local only). `heading(…, $attrs)` adds attributes to a two-voice heading; `component('marquee', ['items' => […]])` renders a ticker with a pause button.

Template helpers: `e()`, `config('address.postcode')`, `url()`, `asset()`, `vite()`, `icon('name', 'light|regular', ['label' => …])`, `img('key', ['alt' => …, 'sizes' => …, 'priority' => true, 'ratio' => '16/9'])` (placeholder when the key isn't in the image manifest), `heading(2, 'Our Approach to', 'Winning Contracts')`, `component('button'|'eyebrow'|'hex-tile'|'chip'|'avatar-stack'|'breadcrumbs'|'cta-band'|'divider'|'micro-cta'|'grid-wave'|'marquee', [...])` (parameters documented at the top of each `templates/components/*.php`), `ui('cta_band')` for default component copy (`app/data/ui.php`), `home('hero')` for home-page copy (`app/data/home.php`, sections hero/services/approach/sectors/process/why/triage/faq/insights/cta; page sections live in `templates/sections/home/*.php` with their CSS in `src/css/pages/home-<section>.css` and are rendered by `templates/pages/home.php`; an image with a cut-out corner gets `class="notch notch--tr|notch--bl"` (`src/css/components/notch.css`, a true CSS `mask` driven by `--nw/--nh/--nr/--nf`; used by the approach image and the service cards); the approach section (P10) reads hours from `config('hours')`; the services section (P11) renders `services()` as `stack-cards`; the sectors section (P12) is an `h-scroll` pin with a fixed intro column (`[data-h-viewport]`, `[data-h-progress]`, `[data-h-item]`); `App\Image::src('key')` gives one JPEG URL for CSS backgrounds or null; the hero's slider is `src/js/modules/hero-slider.js`, its entrance + parallax `hero-intro.js` (plays on `splash:done`), the glowing border `src/css/components/special-frame.css`), `logo('colour'|'white', ['height' => 40, 'lockup' => 'horizontal'|'vertical'|'mark', 'inline' => true, 'prefix' => 'hl'])` (SVG `<img>`, or the inlined SVG whose parts CSS can theme), `inline_svg('brand/logo-mark.svg', ['prefix' => …, 'class' => …, 'attrs' => […]])` (ids namespaced per copy). Splash (P07): `partials/splash.php` is printed by the base layout, `src/css/components/splash.css` holds the choreography (= `design/logo-motion/motion.css`, the approved pixel2motion run; keep in sync), `src/js/modules/splash.js` does the once-per-session rule, skip keys and the GSAP Flip hand-off onto the header logo and dispatches `splash:done`; `logo.css` has the header hover re-draw and the `.is-loading` breathing loop. Footer (`partials/footer.php`) and back-to-top are printed by the base layout; `npm run art` regenerates the grid-wave SVG. CSS: `src/css/tokens.css` → `fonts.css` → `base.css` → `components/*.css` → `pages/*.css`, all imported by `src/css/main.css` with root-relative `/src/css/…` paths.

## Folder map (from `docs/11-tech-architecture-php.md` §2)
```
/                                 ← project root (NOT web root)
├── public/                       ← web root (document root points here)
│   ├── index.php                 ← front controller
│   ├── .htaccess                 ← rewrites, redirects, headers, caching
│   ├── robots.txt
│   ├── llms.txt                  ← AI-crawler summary (see 10)
│   ├── favicon.ico, icon.svg, apple-touch-icon.png, site.webmanifest
│   ├── assets/
│   │   ├── build/                ← Vite output (hashed)  [git-ignored]
│   │   ├── img/                  ← optimised AVIF/WebP/JPG (+ responsive sizes)
│   │   ├── fonts/                ← self-hosted WOFF2
│   │   ├── icons/sprite.svg
│   │   └── og/                   ← 1200×630 Open Graph images per page
│   └── uploads/blog/             ← blog images
├── app/
│   ├── bootstrap.php             ← env, error handling, autoload, security headers
│   ├── routes.php                ← URL → controller/template map
│   ├── redirects.php             ← 301 map from old site (generated from crawl, see 10)
│   ├── config/site.php           ← NAP, socials, hours, default SEO
│   ├── data/
│   │   ├── services.php          ← array of services (single source of truth)
│   │   ├── sectors.php
│   │   ├── faqs.php
│   │   └── navigation.php
│   ├── lib/
│   │   ├── Seo.php               ← builds <head> tags + JSON-LD per page
│   │   ├── Schema.php            ← Organization, LocalBusiness, Service, BlogPosting, Breadcrumb…
│   │   ├── Blog.php              ← reads /content/blog/*.md
│   │   ├── Mailer.php, Csrf.php, RateLimit.php, Validator.php
│   │   ├── Image.php             ← <picture> + srcset + width/height + lazy helper
│   │   └── helpers.php           ← e(), icon(), vite(), url(), asset(), active()
│   └── controllers/              ← ContactController.php (POST), SitemapController.php …
├── templates/
│   ├── layouts/base.php          ← <html lang="en-GB">, head, splash, header, footer
│   ├── partials/                 ← head-seo.php, header.php, footer.php, cookie-banner.php,
│   │                               splash.php, breadcrumbs.php, cta-band.php, …
│   ├── components/               ← card-service.php, card-blog.php, hex-tile.php,
│   │                               two-voice-heading.php, notch-image.php, slider.php, …
│   └── pages/                    ← home.php, about.php, services/index.php, services/single.php,
│                                   sectors/…, insights/…, contact.php, legal/…, 404.php
├── content/blog/                 ← YYYY-MM-DD-slug.md (front-matter: title, description, date,
│                                   updated, author, category, image, image_alt)
├── src/                          ← Vite sources
│   ├── js/main.js                ← boots Lenis, GSAP, modules (lazy-imported per page)
│   ├── js/modules/               ← splash.js, hero-slider.js, parallax.js, reveal.js,
│   │                               pinned-process.js, counters.js, nav.js, cursor.js, …
│   └── css/                      ← tokens.css, base.css, components/*.css, pages/*.css
├── storage/logs/, storage/cache/ ← writable, outside web root
├── deploy/                       ← nginx.conf sample, apache vhost, deploy notes
├── composer.json, package.json, vite.config.js, .env.example
└── docs/                         ← these specs
```
