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
| Content check | `php bin/check-content.php` (from P03; launch gate = zero results) |
| SEO audit | `php bin/seo-audit.php` (from P03/P30), plus `/seo page <url>` and `/seo audit` from the claude-seo plugin (`docs/10` §0) |
| Lint | `php -l <file>` on every changed PHP file (Standard check 1) |

Request flow: `public/index.php` → `app/bootstrap.php` → `App\Router::dispatch()` (normalise → `app/redirects.php` → `app/routes.php` → 404). Page templates set `$page` and print their body; `templates/layouts/base.php` wraps it. On `local`, a planned route without a template shows `templates/pages/_stub.php`.

Template helpers: `e()`, `config('address.postcode')`, `url()`, `asset()`, `vite()`, `icon('name', 'light|regular', ['label' => …])`, `img('key', ['alt' => …, 'sizes' => …, 'priority' => true, 'ratio' => '16/9'])` (placeholder when the key isn't in the image manifest), `heading(2, 'Our Approach to', 'Winning Contracts')`, `component('button'|'eyebrow'|'hex-tile'|'chip'|'avatar-stack', [...])` (parameters documented at the top of each `templates/components/*.php`). CSS: `src/css/tokens.css` → `fonts.css` → `base.css` → `components/*.css` → `pages/*.css`, all imported by `src/css/main.css` with root-relative `/src/css/…` paths.

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
