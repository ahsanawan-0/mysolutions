# Build log

> One entry per prompt from `PROMPTS.md`, in the order they were run (newest at the bottom). Each entry is appended as the last Standard check, just before the commit `P##: <title>`, and records:
> **Date** · **Files created/changed** · **Decisions** (choices made where the prompt left room) · **Deviations** (anything done differently from the prompt, and why) · **Follow-ups** (loose ends for a later prompt or for the client).
> Future sessions read the latest entries here first to see what already exists.

---

## P00 — Project primer: CLAUDE.md, build log, git
**Date:** 13 October 2026
**Commit:** `P00: project primer`

**Files created**
- `CLAUDE.md` — project summary, "read before any work" (doc index from `docs/README.md`), the Global rules and Standard checks copied verbatim from `PROMPTS.md`, the "structure now, content later" strategy, a provisional Commands section, and the folder map from `docs/11` §2.
- `docs/BUILD-LOG.md` — this file.
- `.gitignore` — `/vendor/`, `/node_modules/`, `/public/assets/build/`, `/storage/{logs,cache,mail}/*` (with `.gitkeep` kept), `.env`, `/.venv/`, `/design/logo-motion/outputs/`, `.DS_Store`, `Thumbs.db`.

**Repository**
- `git init` with `main` as the default branch.
- The client's source material is committed so the repo is the single source of truth: `docs/website_content/` (the `.docx`, `about-us-content.md`, `current-site-content-scrape.md`, `pages_ref.jpeg`, `services/01-bid-proposal-management.md`), `logos/` (2 JPEGs) and `inspirations/` (9 reference images). `docs/*.md` and `PROMPTS.md` are committed too.

**Decisions**
- The Commands table in `CLAUDE.md` lists the *intended* commands from `docs/11` and the Standard checks (`php -S …`, `npm run dev/build`, `php bin/check-content.php`, plus placeholder `npm run icons` / `npm run images` / `php bin/seo-audit.php` script names). P01 replaces it with the real, verified commands.
- `CLAUDE.md` is 128 lines (limit: 150), leaving room for P01's command updates.

**Deviations**
- None. Standard checks 1–7 were skipped as instructed because no application code exists yet; check 8 (this entry + commit) applies.

**Follow-ups**
- P01: fill in the Commands section of `CLAUDE.md` with real scripts; create the `storage/*/.gitkeep` files that `.gitignore` already preserves.
- Consider a `.gitattributes` (`* text=auto`) in P01 so line endings are consistent between Windows and the Linux host.

---

## P01 — Scaffold: folders, Composer, Vite, router, redirects, .htaccess
**Date:** 13 October 2026
**Commit:** `P01: scaffold`

**Files created**
- Folder structure from `docs/11` §2 (`public/`, `app/`, `templates/`, `content/`, `src/`, `storage/`, `deploy/`, `bin/`, `scripts/`, `assets-src/`) with `.gitkeep` in empty folders.
- `composer.json` / `composer.lock`: PHP ≥ 8.3; phpmailer/phpmailer 7.1.1, league/commonmark 2.10.3, spatie/yaml-front-matter 2.1.1, vlucas/phpdotenv 5.7.0; PSR-4 `App\` → `app/lib/`, files autoload `app/lib/helpers.php`.
- `package.json` / `package-lock.json` (`"type": "module"`): vite 8.3.4 (dev); gsap 3.15.0, lenis 1.3.26, @phosphor-icons/core 2.1.1. Scripts `dev`, `build`, `icons`, `images` (the last two are stubs in `scripts/`).
- `vite.config.js` (publicDir false, outDir `public/assets/build`, manifest, base `/assets/build/`, dev server 5173). `src/js/main.js`, `src/css/main.css` → `tokens.css` + `base.css` (stubs).
- `public/index.php` (front controller; serves real files under the built-in server), `public/.htaccess`, `deploy/nginx.conf.example`.
- `app/bootstrap.php` (autoload, Dotenv, `APP_ENV`, error display on local only, `storage/logs/app.log`, Europe/London, UTF-8, `send_security_headers()` incl. `X-Robots-Tag: noindex` off production).
- `app/config/site.php` (NAP, company facts, socials, locale, provisional hours marked `// PROVISIONAL` + `[[PENDING: office hours]]`).
- `app/lib/helpers.php`: `base_path()`, `public_path()`, `templates_path()`, `e()`, `env()`, `app_env()`, `is_local()`, `config()` (dot access), `url()`, `asset()` (`?v=` mtime), `vite()`, `current_path()`, `is_active()`, `render()`, `icon()` (stub until P02).
- `app/lib/Router.php`: normalise (lowercase + trailing slash, computed in memory first so legacy URLs redirect in **one hop**) → `app/redirects.php` (exact map + regex rules, 301/410) → `app/routes.php` (exact paths and `{param}` patterns with `where` constraints) → 404. Missing template: dev stub on local, 404 elsewhere; `local_only` routes 404 off local.
- `app/routes.php`: every URL from `docs/08` §1 (3 categories + 12 sub-services + 7 sectors generated from the locked lists; insights patterns; machine files; local-only styleguide). `app/redirects.php`: the full `docs/10` §2 map, slugs verified against the live Yoast sitemaps.
- Templates: `layouts/base.php`, `partials/head-seo.php` (stub head: charset, viewport, title, description, canonical, robots, js class, `vite()`), `partials/header.php`, `partials/footer.php` (stubs), `pages/home.php`, `pages/404.php`, `pages/410.php`, `pages/_stub.php`.
- `.env.example`, `.claude/launch.json` (`php` + `vite`), `CLAUDE.md` Commands section filled in, `.gitignore` (+ `.claude/settings.local.json`).

**Decisions**
- Legacy redirects are matched on the normalised path *and* the raw path, so `/Service/Tender-Writing/` goes straight to the new URL in one hop instead of lowercase-then-redirect.
- Draft pages (`status` ≠ `published`) are forced to `noindex, nofollow` by the base layout already (P03 adds the sitemap exclusion and ribbon).
- `.htaccess` HTTPS detection also honours `X-Forwarded-Proto` (LiteSpeed/Cloudflare); HSTS only when `HTTPS` env is set. Cache rules use `FilesMatch` by extension rather than `<If>` for LiteSpeed compatibility. Dynamic PHP HTML gets no explicit `Cache-Control` yet (P31).
- The `vite()` dev-mode URLs include the `/assets/build/` base because Vite serves its dev files under `base` too.
- `public/assets/build/` has no `.gitkeep` (it is git-ignored and emptied by every build).
- Old privacy PDF: the upload folder is unknown, so a regex rule matches any `/wp-content/uploads/**/Privacy-Poilcy*.pdf` → `/privacy-policy/`.

**Deviations**
- None from the prompt. `/sitemap.xsl` and `/xmlrpc.php` were added to the redirect map (both exist on WordPress sites) beyond the listed items.

**Checks**
- `php -l` clean on all PHP files; `npm run build` clean (manifest + hashed CSS/JS). Both servers run; `/` loads the stylesheet via Vite dev, and with `VITE_DEV=false` the hashed assets return 200.
- curl: `/About-Us` → 301 `/about-us/`; `/about-us` → 301 `/about-us/`; `/service/tender-writing/` → 301 one hop to the new URL; `/service-category/tender-search/` → 301; `/pxl-template/header/` and `/wp-login.php` → 410; `/nope/` and `/services/foo/` → 404; every planned route returns 200 (stub); `/sitemap_index.xml`, `/feed/`, `/page/2/` → 301.
- Browser: no console errors after the `vite()` fix, no 404 assets, `html.js` set, no horizontal scroll at 375 px, Tab reaches the skip link with `:focus-visible`. No PHP notices in the server log or `storage/logs/app.log`.

**Follow-ups**
- P02: real `tokens.css`/`base.css`, the Phosphor sprite builder and the image pipeline (`scripts/*.mjs` are stubs).
- P03: `partials/head-seo.php` becomes the full `docs/10` §7 head via `App\Seo`; `machine/*` templates (`sitemap.xml`, `robots.txt`, `llms.txt`), `bin/check-content.php`.
- Composer on this machine is a development build over 60 days old (`composer self-update` recommended); not a project issue.

---

## P02 — Design tokens, base CSS, fonts, icons, image pipeline, styleguide
**Date:** 13 October 2026
**Commit:** `P02: design tokens, base CSS, fonts, icons, image pipeline, styleguide`

**Files created / changed**
- `src/css/tokens.css`: every token from `docs/02` §2–§3 and `docs/03` §2 (Azure 50–950 incl. 650, Navy 50–950, Sky 50/100/200/300/700, neutrals, semantic, text roles, category colours incl. `--cat-digital-ink`, gradients + `@property --angle`, RGB triplets, spacing, layout, `--header-h`, radii, shadows, glass material values, motion, font stacks, fluid type scale with line-heights/tracking/weights, control sizes).
- `src/css/base.css`: reset, `scroll-padding-top`, typography, two-voice `em` (Playfair Italic 500, Azure 500; gradient text at ≥ 48px via the breakpoints where the clamp reaches 48px; Sky on `.on-dark`), links Azure 700, `:focus-visible` ring, `::selection`, `.container*`, `.section`, `.stack`, `.cluster`, `.visually-hidden`, `.skip-link`, reduced-motion / reduced-transparency / contrast-more / print media queries.
- `src/css/fonts.css` + `public/assets/fonts/`: Mona Sans variable 500–700 (35 KB), Google Sans variable 400–500 (32 KB), Playfair Display Italic 500 (22 KB) = **89 KB total** (budget ≤ 120 KB). Latin subsets produced by `scripts/fonts.py` (fontTools instancer + subsetter) from the Fontsource packages (`@fontsource-variable/mona-sans`, `@fontsource-variable/google-sans`, `@fontsource/playfair-display`); metric-matched fallbacks computed against Arial / Georgia (`size-adjust`, `ascent/descent/line-gap-override`).
- `src/icons.json` (57 light + 31 regular) and `scripts/build-icons.mjs` → `public/assets/icons/sprite.svg` (88 symbols, 41 KB). `npm run icons` also runs as `prebuild`. `icon($name, $weight, $attrs)` rewritten: `<use href="/assets/icons/sprite.svg?v=…#name-weight">`, `label` → `role="img"` + `<title>`, unknown icon throws on local.
- `scripts/images.mjs` (sharp) + `app/lib/Image.php` + `img()` helper: AVIF/WebP/JPEG at 400/800/1200/1600/1920 (no upscaling), `public/assets/img/manifest.json`, `<picture>` with srcset/sizes/width/height/lazy or `fetchpriority="high"`, alt required on local (or `decorative`), branded SVG placeholder (`[[PENDING IMAGE: key]]`, navy→azure gradient, hex pattern, requested ratio) for unknown keys.
- Components: `templates/components/{eyebrow,heading,button,hex-tile,chip,avatar-stack}.php` + `src/css/components/{icon,eyebrow,buttons,hex-tile,chips,avatar-stack,glass,image}.css`; helpers `component()` and `heading()`.
- `templates/pages/styleguide/index.php` + `src/css/pages/styleguide.css`: swatches with hex + contrast vs white/ink (values parsed from `tokens.css`, so no hex is duplicated), gradients, type scale with live px readout, two-voice demos (light + dark), buttons (default/disabled/on-dark/sizes/icon buttons), every sprite icon, hex tiles ×3 categories (+ dark), chips, avatar stacks, 4 glass panels on a dark stage, 3 placeholder images.
- `vite.config.js`: `/assets/(fonts|icons|img)/` marked external so CSS `url()`s to PHP-served files build without warnings. `src/css/main.css` imports use root-relative `/src/css/…` paths (Vite's postcss-import resolved `./components/…` against the project root).
- `package.json`: scripts `prebuild`, `images` → `scripts/images.mjs`, `fonts`; dev deps sharp + the three Fontsource packages. `CLAUDE.md` Commands + helper notes updated.

**Decisions**
- Fonts come from Fontsource (OFL) and are re-subset locally rather than shipping Fontsource's files, so the weight axes are trimmed (Mona 500–700, Google Sans 400–500) and the fallback metrics are computed from the exact shipped files.
- Digital Presence chips/tiles on light use `--cat-digital-ink` (Sky 700); Sky 300 itself only appears on dark surfaces (docs/02 §2.6 rule 4). No component sets small text in Azure 500.
- Hex tile and eyebrow bullet use a CSS `mask-image` data-URI hexagon with `stroke-linejoin: round` for the rounded corners; `--tile-colour` / `--tile-fill` drive category colouring.
- The placeholder SVG uses `style="fill:var(--…)"` so even inline SVG carries no hex.
- Dynamic HTML caching, preloads and the full `<head>` remain for P03 as planned.

**Deviations**
- `scripts/images.mjs` is the file name from the prompt; the P01 stub `scripts/build-images.mjs` was removed. Added a `fonts` npm script (Python) because fonts need re-subsetting whenever the character set changes.

**Checks**
- Styleguide: all six `@font-face`s report `loaded` from `/assets/fonts/`; **zero third-party requests**; 88/88 icons render; no console errors after the import fix; no horizontal overflow at 360 or 1280; buttons are 48px high with a visible `:focus-visible` ring; reduced-motion and reduced-transparency rules present. `php -l` clean; `npm run build` clean (CSS 28 KB / 6.8 KB gz); `npm run icons` regenerates the sprite; hex scan outside `tokens.css` returns nothing.

**Follow-ups**
- P03: preload `mona-sans-var.woff2` + `google-sans-var.woff2` in the head partial; `bin/check-content.php` should also report `data-pending-image` placeholders.
- Real photos: drop files into `assets-src/images/<section>/<name>.jpg` and run `npm run images`; the `img()` keys used so far are `hero/london-office`, `about/team-meeting`, `services/tender-writing`, `team/member-1..3`.

---

## P03 — SEO engine: head, schema, sitemaps, robots, llms.txt, content checker
**Date:** 13 October 2026
**Commit:** `P03: SEO engine`

**Files created / changed**
- `app/lib/Page.php`: the page contract (defaults from config, canonical built from `SITE_URL` + path with the query stripped, OG defaults, `og_title` = title without the brand suffix, `indexable` flag computed from the page's own status/robots, environment override to `noindex, follow` for drafts and non-production, ISO dates) + `Page::probe()` (loads a template only to read its `$page`; used by sitemaps and the CLI tools).
- `app/lib/Seo.php`: validation (title 30–65, description 70–170, canonical absolute https + trailing slash in production, `og_image_alt`, breadcrumbs except home, no `[[PENDING` in title/description; error pages with `canonical => false` + noindex are exempt). Published pages throw on local; drafts log + show warnings in the ribbon. `Seo::head()` renders the partial.
- `app/lib/Schema.php`: `organization()` (Organization, switchable to LocalBusiness via `seo.organization_type`, logo ImageObject, PostalAddress GB, areaServed, contactPoint, sameAs, foundingDate, Companies House identifier, knowsAbout), `website()` (no SearchAction), `webPage()` (+ AboutPage/ContactPage/CollectionPage, primaryImageOfPage, dates, breadcrumb link), `breadcrumbs()`, `service()`, `serviceCategory()` with `hasOfferCatalog`, `blogPosting()`, `faqPage()` (off by default), `graph()` + `json()` with `JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG`.
- `templates/partials/head-seo.php` in the exact `docs/10` §7 order (charset → viewport → title → description → canonical → robots → js class → consent-mode placeholder → 2 font preloads → Vite → OG incl. `article:*` → Twitter → icons/manifest (printed only when the P07 files exist) → theme-color → RSS → JSON-LD). `templates/partials/draft-ribbon.php` + `src/css/components/draft-ribbon.css` (non-production only). `templates/layouts/base.php` uses `Seo::head()`.
- Controllers (`app/controllers/`, new PSR-4 namespace `App\Controllers`): `SitemapController` (index → pages/services/sectors/insights; only published, indexable, 200 URLs; `lastmod` from `modified` or the template mtime; optional `<image:image>`; `legacy()` from the exact redirect keys on the production host, gated by `seo.legacy_sitemap`), `RobotsController` (production rules with AI-search bots allowed and training bots commented unless `seo.block_ai_training`; non-production = `Disallow: /`), `LlmsController` (from config + services/sectors data). `Router` now supports `'controller' => [class, method]` routes and runs `Seo::prepare()` before the layout.
- `app/data/services.php` (3 categories / 12 sub-services with icons + `// PROVISIONAL` summaries) and `app/data/sectors.php` (7 sectors): `app/routes.php` now derives its service/sector routes from them and tags routes with a `sitemap` group. P04 extends these files.
- `app/config/site.php`: `description`, `alternate_names`, `production_url`, `area_served`, and a `seo` block (title suffix, default robots, default OG image + alt, logo path for P07, theme colour, organization type, legacy sitemap, AI-training block, FAQ schema, knowsAbout).
- `bin/check-content.php` (markers in config/data/templates/content/src, route title/description lengths + duplicates + "no template yet", missing image keys, missing breadcrumbs; `--strict`) and `bin/seo-audit.php` (`--base`, `--all`; one h1, head tags, JSON-LD parse, img alt/width/height, dummy links).
- `scripts/og.mjs` (`npm run og`) → `public/assets/og/default.jpg` 1200×630 (82 KB): gradient + hex pattern + a temporary three-shape mark and text, colours read from `tokens.css`.
- Templates: home gets a provisional description; 404/410/stub use `canonical => false` (no canonical, no JSON-LD); styleguide gets description + breadcrumbs and no `href="#"` links. `composer.json` autoload + `CLAUDE.md` updated.

**Decisions**
- Indexability for sitemaps is computed from the page's own status/robots (what it would be in production), separately from the environment override, so the sitemap can be verified locally.
- `theme-color` lives in config (`seo.theme_color`, documented as `--navy-900`) because browser-UI colour can't come from CSS.
- Favicon/manifest links are conditional on the files existing, so the Network tab stays free of 404s until P07 creates them.
- The legacy sitemap always uses `production_url`, since the old URLs only exist on the live host.

**Deviations**
- The default OG image is the temporary text-only version (P07 hasn't produced the logo SVG yet), rendered with Arial because librsvg can't load the self-hosted WOFF2; P07/P30 replace it.
- `validator.schema.org` wasn't run from this session (it would mean submitting a form on a third-party site); the graph was verified to parse and to follow `docs/10` §6. Paste the `/` JSON-LD into the validator before launch (P30).

**Checks**
- `/` head is in the §7 order with valid JSON-LD (Organization + WebSite + WebPage); `/sitemap.xml`, `/sitemap-*.xml`, `/legacy-sitemap.xml` → `application/xml`; `/robots.txt`, `/llms.txt` → `text/plain`. With home temporarily published, the index and pages sitemap list `/` with an accurate `lastmod`. Drafts render `noindex, follow` + the ribbon. `/nope/` 404, `/pxl-template/x/` 410. `php bin/check-content.php` runs (69 findings, all expected at this stage); `php bin/seo-audit.php --all` → 2 pages PASS. `php -l` clean; no console errors, no failed or third-party requests.

**Follow-ups**
- P04: extend `app/data/services.php` / `sectors.php` with menu copy; P07: favicon set, `site.webmanifest`, `logo-mark-512.png`, replace the OG mark; P25: add blog posts/categories to the insights sitemap via `App\Blog`; P30: per-page OG images, validator run, `legacy_sitemap` off ~6 weeks after launch.
