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

---

## P04 — Header, glass navigation, mega menu, mobile menu + services/sectors data
**Date:** 13 October 2026
**Commit:** `P04: header and navigation`

**Files created / changed**
- `app/data/services.php` (exact P04 content: 3 categories with `slug/name/short/colour/icon/summary/status/url/subs`, 12 subs with `status: draft` + `// PROVISIONAL` summaries; URLs derived), `app/data/sectors.php` (7 sectors, draft), `app/data/navigation.php`. Helpers `services()`, `service_category()`, `service_sub()`, `sectors()`, `navigation()`; `routes.php` and `LlmsController` use them.
- `templates/partials/header.php`: `default` variant (logo · Innovax glass pill nav with a sliding white active pill · phone ≥ 1280px · "Get a Free Quote" glass pill → `/contact-us/#quote` · burger) and `hero` variant (centred links with an Azure active dot; rendered inside the hero by P08 via `render('partials/header', ['variant' => 'hero', 'theme' => 'dark'])`). Services mega menu (3 category columns from the data: coloured hex tile + name → category page, sub-service icon + name + summary; 4th column featured "Have a tender deadline coming up?" + quote button + "View all services"), Sectors dropdown (7), full-screen mobile sheet (28px links, native `<details>` accordions for Services → categories → subs and Sectors, contact block, socials), mobile sticky action bar (Call · WhatsApp · Get a Quote).
- `templates/layouts/base.php`: prints the default header unless `$page['header']` is `hero`/`none`; `header_theme` (`light` default; heroes set `dark`) and `header_overlay` (adds `has-overlay-header`; otherwise `main` gets `padding-top: --header-h-initial`).
- CSS: `components/header.css`, `mega-menu.css`, `mobile-menu.css`, `action-bar.css`; tokens `--header-h` 88 (live), `--header-h-initial`, `--header-h-compact` 68, `--action-bar-h` 64.
- JS: `src/js/core/eases.js` (CustomEase `apple/out/inout/spring`, `reducedMotion()`), `src/js/modules/header.js` (sticky glass after 80px + 88→68 + `--header-h` update, hide on scroll down / reveal on scroll up with `yPercent`, never while focused or a menu/sheet is open; theme watcher for `[data-header-theme]` sections; `gsap.quickTo` active pill; menus with 150ms hover intent, click/Enter/Space, Esc → focus back to the trigger, outside click, focus-out, `autoAlpha` + y 8→0 with 0.04 column stagger; mobile sheet with focus trap, Esc, scroll lock (`html.is-locked` + `window.lenis?.stop()`), stagger-in, close returns focus to the burger; action bar after 0.9 viewport), `main.js` boots it.

**Decisions**
- The header is `position: fixed` from the start; pages without an overlaying hero reserve `--header-h-initial` (a constant) so the 88→68 shrink never shifts layout.
- The mega panel is `position: fixed` inside the header (the header's `will-change: transform` makes it the containing block) and the nav list's glass lives on a `::before`, because a `backdrop-filter` on the list would otherwise become the panel's containing block.
- Two header themes (`data-theme="dark|light"`) swap the token set; light = ink text, ink active pill, outlined CTA.
- Native `<details>` accordions on mobile (keyboard/screen-reader support for free); the Services accordion also links to each category overview.
- Featured mega-menu card is the quote CTA now; P16 may switch it to the latest insight.

**Deviations**
- None. The logo is still the temporary text + hexagon mark until P07.

**Checks**
- `php -l` clean; `npm run build` clean (CSS 44 KB / 9.2 KB gz, JS 82 KB / 32 KB gz incl. GSAP core + CustomEase); no hex outside `tokens.css`; `check-content` runs (89 findings: provisional data + unbuilt templates, as expected).
- Browser at 1280: pill sits exactly on the active link; mega menu opens with `aria-expanded`, hover-intent (closed at 60ms, open at 310ms), closes on mouseleave, Esc (focus returns to the trigger), outside click; 3 columns + featured card centred at 1200px; screenshots show the glass-thick-dark panel with the stagger complete.
- Browser at 360: burger visible, desktop nav hidden, no horizontal scroll, body padding 64px for the action bar; sheet opens (scroll lock, focus on Close), Shift+Tab wraps, 3 categories / 15 sub-links / 7 sectors, Esc closes and unlocks.
- Note: the desktop app's Browser pane stopped compositing frames mid-session (`requestAnimationFrame` frozen), so scroll hide/reveal and the sheet's stagger were verified by code review + the synchronous DOM state; re-check visually once the pane is displayed.

**Follow-ups**
- P06 absorbs `core/eases.js` into the motion core and exposes `window.lenis` for the sheet's scroll lock. P07 swaps the text logo for the SVG lockups (white version for `data-theme="dark"`). P08 renders the `hero` variant inside the hero panel and sets `header_theme: 'dark'`, `header_overlay: true`. P17 does the same for inner heroes.

**Fix (13 October 2026, before P05, commit `P04 fix: nav pill offset, caret in pill, mega-menu hover bridge`)**
- The sliding pill used `link.offsetLeft`, which is measured against the link's `position: relative` `<li>`, so the pill always sat at the far left while the hovered link turned white over the light glass ("text disappears on hover"). `header.js` now measures the link against the list with `getBoundingClientRect()`.
- The pill now spans the link **and** its caret toggle, and the caret turns white under the pill (client request); the no-JS fallback joins the caret to the solid active pill.
- An invisible hover bridge (`.has-menu.is-open::after`, half the header height) keeps the Services/Sectors item hovered while the pointer crosses the header strip and the 8px gap to the panel, so the menu no longer closes mid-way.

---

## P05 — Footer, CTA band, breadcrumbs, back-to-top, dividers, micro-CTA
**Date:** 13 October 2026
**Commit:** `P05: footer, CTA band, breadcrumbs, back-to-top, dividers, micro-CTA`

**Files created / changed**
- `templates/partials/footer.php` (replaces the stub): Navy 900 footer with the grid-wave art strip on the top edge (fades out downwards), 4 columns from the data files (brand: text logo + tagline + provisional description + 3 social `btn-icon`s with `aria-label`s · Services: 3 category links each followed by its sub-services in Navy 200 · Sectors: 7 + "All sectors" · Contact: `<address>` with the NAP, `tel:`, WhatsApp, `mailto:`, provisional office hours, "Get a Free Quote" button) and the bottom bar with the exact disclosure sentence built from `config()` + Privacy · Cookies · **Cookie settings** (`<button data-cookie-settings>`) · Terms · Accessibility · Sitemap. Carries `data-header-theme="dark"` so the header switches theme over it, and `data-anim="fade-up"` for P06.
- `templates/components/cta-band.php` + `src/css/components/cta-band.css` (C21): Navy 900 → Navy 800 → Azure 800 gradient at 120°, animated grid-wave layer (masked away from the copy), large faded hexagon mark (`data-anim="cta-mark"`, P06 = docs/06 S10, P07 swaps in the real mark), eyebrow + two-voice heading + text, primary button + glass phone button (+ optional WhatsApp). Params `eyebrow, heading, accent, text, primary, secondary (false hides), show_whatsapp, level, class`.
- `templates/components/breadcrumbs.php` + `breadcrumbs.css` (C22): `<nav aria-label="Breadcrumb"><ol>`, `caret-right` separators, current item `aria-current="page"`, `ink` (default) and `white` variants, 44px link targets. Usage: `component('breadcrumbs', ['items' => $page['breadcrumbs']])`.
- `templates/components/back-to-top.php` + `back-to-top.css` + `src/js/modules/back-to-top.js` (C25): Azure 650 40px square (radius 12, `arrow-up`) inside a 56px progress ring (`stroke-dashoffset` from `--progress`); appears after one viewport; scrolls via `window.lenis.scrollTo` when P06 provides it, else native smooth (instant under reduced motion); moves focus to `#main`; sits above the mobile action bar; hidden without JS. Printed once by `base.php` after the footer.
- `templates/components/divider.php` + `divider.css` (C29): `wave` (path + `data-anim="wave"` + `data-wave-alt` for P06's scrubbed MorphSVG) and `diagonal` (edge rises to the right); `to`/`from` take token names (`navy-900`, `ms-canvas`…) and are validated before becoming `var(--…)`.
- `templates/components/micro-cta.php` + `micro-cta.css`: statement · 56px `arrow-right` (`data-anim="micro-arrow"`, points right until P06's S2 timeline) · split button; hairlines above/below; `dark` variant; stacks and centres < 768px.
- `templates/components/grid-wave.php` + `grid-wave.css` + `scripts/art.mjs` → `public/assets/img/art/grid-wave.svg` (16 KB, cached once): the `inspirations/… 9.34.03 PM.jpeg` perspective grid-wave rebuilt as 11 wave rows × 41 sheet columns of line art, referenced with `<use href="…#grid-wave">`. Colours reach the external tree as `--gw-h` (Sky 300) / `--gw-v` (Azure 400) custom properties with `currentColor` fallback, so no colour lives in the SVG. `grid-wave--animated` drifts one wave period (600 units) over 36s via CSS, off under reduced motion. `npm run art` runs in `prebuild`.
- `app/data/ui.php` + helper `ui()`: default copy for the CTA band, micro-CTA and footer (description `// PROVISIONAL`, legal links), so templates stay copy-free.
- Styleguide: new "Closing" section (breadcrumbs ink/white, wave + diagonal dividers between three bands, micro-CTA light/dark, CTA band with WhatsApp). `main.css` imports the 7 new component files; `main.js` boots `initBackToTop()`; `.gitignore` ignores the preview `storage/*.log` files; `CLAUDE.md` helper list updated.

**Decisions**
- The CTA band, breadcrumbs and divider live in `templates/components/` (called through `component()`), not `partials/` as the docs/11 tree suggested, because they take parameters like the other components; the footer stays a partial.
- The grid-wave is a static external SVG referenced by `<use>` rather than inline markup: two instances per page would have added ~30 KB to every HTML response (docs/12 budget: HTML ≤ 40 KB).
- The 120° "diagonal" divider uses the brand *direction* (low left → high right) at a usable aspect (1440×96); a literal 60°-steep edge would need a 2,500px-tall divider.
- Footer links are 44px tall on touch/mobile and 36px on desktop pointers (`@media (hover: hover) and (min-width: 1024px)`) so the 15-link services column stays compact.
- Scroll motion (mark rise/rotate S10, micro-arrow S2, wave morph, footer fade-up) is declared as `data-anim` hooks only; P06 implements them in the one motion system (no second ScrollTrigger bootstrap here).

**Deviations**
- The footer disclosure follows the prompt's exact sentence (no "Email:" suffix as in docs/13 §1); the email address is in the Contact column, which satisfies the trading-disclosure rule.

**Checks**
- `php -l` clean; `npm run build` clean (CSS 54.7 KB / 11.1 KB gz, JS 82.6 KB / 32.6 KB gz); `check-content` runs (90 findings: +1 for the provisional footer description); no PHP notices in the page or logs; Network: every asset 200 (the one 404 was `grid-wave.svg?v=0` before the art was generated).
- DOM checks at 360 / 768 / 1024 / 1440: `scrollWidth === clientWidth` everywhere; footer grid 1 / 2 / 4 / 4 columns; micro-CTA 3 columns from 768; no footer link under 44px at 360; back-to-top sits 80px up above the action bar on mobile; the disclosure text matches character for character; the legal list is `A, A, BUTTON(Cookie settings), A, A, A`; 36 footer links, none `href="#"`; one `h1`.
- Screenshots at 1440: the footer renders with the grid-wave strip fading from the top edge and the four columns laid out as specified; the CTA band shows the gradient, wave layer and faded mark. The desktop Browser pane froze `requestAnimationFrame` for part of the session (as in P04), so GSAP tweens were verified from their target values rather than mid-animation frames.

**Revision (13 October 2026, client feedback, commit `P05 revision: footer redesign, real logo`)**
- Client: "I don't like this background… use some background at the bottom, not the top… round corners, clean and professional like Dribbble / Airbnb / Apple, some glass, not much gradient" (Innovax footer screenshot as the reference) and "the logo is not the one I gave you".
- Footer rebuilt as a **rounded Navy 900 panel** (radius 32, inset on the canvas, `--shadow-3`) with flat colour and one faint azure glow behind a **glass brand card** (`.glass-thin-dark`: white logo, description, office-hours rows with hairlines, round white social buttons incl. WhatsApp). Columns: Services (categories + subs) · Sectors · **Company** (About, Insights, FAQ, Contact, Get a Free Quote from `ui('footer.company_links')`). Contact moved to a **row of azure icon circles** (Visit our office · Email us · Call or WhatsApp) in an `<address>`. A decorative **word strip** (`ui('footer.marquee')`, hexagon bullets, 40s CSS marquee, pauses on hover, static under reduced motion, `aria-hidden`) runs along the panel's bottom edge and is clipped by it. Disclosure + legal links sit **below the panel on the canvas** in `--text-2`. The grid-wave strip was removed from the footer (still used by the CTA band). `docs/08` §3 updated to the client's revised brief.
- **Real logo now**, ahead of P07: `scripts/logo.mjs` (`npm run logo`, sharp) converts `logos/My Solution Horizontal.jpg (1).jpeg` into `public/assets/img/logo/logo-horizontal.png` (transparent, 449×96), `logo-horizontal-white.png` (wordmark + tagline recoloured white for dark surfaces) and `logo-mark-512.png` (mark on white; the path `config('seo.logo')` already pointed at). New helper `logo('colour'|'white', ['height' => …])` prints the `<img>` with real `width`/`height` (text fallback if the PNGs are missing). Header prints both and CSS swaps them with `data-theme`; the mobile sheet and footer use the white one. P07 replaces the PNGs with SVG lockups through the same helper.
- Checks: no PHP notices; logo PNGs 200; `scrollWidth === clientWidth` at 360 / 768 / 1440; footer links ≥ 44px at 360; header switches to the dark theme over the panel; `npm run build` clean.
- Second round (commit `P05 revision 2: footer accordions, compact card`): the Services column's three categories are native `<details>` accordions (category icon in its colour · name · `caret-down` that flips when open; closed by default, open on the current category) holding "Overview: <short>" + the sub-services, so the column is 3 rows tall instead of 15; the brand card gets `align-self: start` so it is content-height rather than stretched to the tallest column (client: "too much empty space").

**Follow-ups**
- P06: implement `cta-mark` (S10), `micro-arrow` (S2), `wave` (MorphSVG), `fade-up` on the footer grid and `stagger` on the contact row; switch the back-to-top to `window.lenis.scrollTo` (already wired) and optionally ScrollTrigger for the ring.
- P07: replace the logo PNGs with SVG lockups via `logo()`; the hexagon stand-in in the CTA band.
- P07: replace the hexagon stand-in in the CTA band and the text logo in the footer with the SVG mark/lockup.
- P28: the consent module listens for `[data-cookie-settings]`. P29 builds `/sitemap/` which the footer already links.
- Client: office hours and the footer description are provisional (`check-content` lists both).

---

## P06 — Motion core: Lenis + GSAP + the `data-anim` catalogue
**Date:** 13 October 2026
**Commit:** `P06: motion core, data-anim catalogue, nodes module`

**Files created / changed**
- `src/js/core/motion.js` (new): registers ScrollTrigger (+ `CustomEase` eases from `core/eases.js`), creates **Lenis** (`lerp 0.1`, `smoothWheel`, native touch) only when motion is not reduced, syncs it with `ScrollTrigger.update` through `gsap.ticker` (`lagSmoothing(0)`), exposes `window.lenis` / `window.__lenis`, destroys/recreates it when the reduced-motion preference changes. **Hash rule:** with a `#hash` on load the browser jumps natively first and Lenis starts after `load` + one frame; `history.scrollRestoration` is untouched. **Anchor links:** same-page `a[href="#…"]` scroll through `lenis.scrollTo` (or native) and `pushState` the hash, so back/forward keep working; focus moves to the target (temporary `tabindex="-1"`). The header offset comes from `scroll-padding-top: var(--header-h)` (Lenis honours it) plus a 16px margin. **Runner:** collects `[data-anim]`, dynamic-imports only the effects the page uses (`src/js/anim/index.js` map → one small chunk each), then creates everything inside one `gsap.matchMedia()` context with `desktop (≥1024)` / `mobile (≤1023)` / `reduce`; under `reduce` every effect is skipped and the content stays in its server-rendered state. Effects may return a cleanup; the context reverts tweens on breakpoint/preference change. **Module loader:** `[data-module="name"]` → `import('./modules/name.js')` via `import.meta.glob` (header/back-to-top excluded, they are always on). `ScrollTrigger.refresh()` after `document.fonts.ready`, after `load`, and after lazy images inside pinned sections load (`refreshOnLazyImages`).
- `src/js/anim/*.js` (new, 26 effects, `_util.js` shared): `fade-up`, `stagger` (ScrollTrigger.batch, `data-stagger`, `data-stagger-target`), `split-lines` (SplitText `mask: 'lines'`, `autoSplit`), `split-chars`, `two-voice`, `parallax` (`data-speed`, mobile halves), `img-reveal` (clip-path with the wrapper's own radius token + inner scale), `img-zoom-out`, `counter` (`data-to/suffix/prefix/decimals`, UK number format, server text restored on complete), `fill-text`, `draw` (DrawSVG), `pin-steps` (desktop pin N×100vh with `--step`, `.is-active` on `[data-step]`/`[data-step-panel]`, `[data-step-progress="y|x"]`; mobile = stacked with 60% activation), `h-scroll` (desktop pin; swipe row below), `stack-cards` (sticky from CSS, scale .94 + dim from JS), `marquee` (seamless loop with `gsap.utils.wrap`, clones until ≥ viewport + one set, velocity-aware direction/speed, hover slows, focus pauses, **pause/play button** for WCAG 2.2.2), `magnetic`, `tilt` (+ glare), `wave` (MorphSVG), `velocity-skew` (one shared trigger), `read-along` (chars 0.2 → 1, `aria: auto`), `logo-assembly` (S1, `[data-piece]`, `data-from="x,y,rot"`), `micro-arrow` (S2: our `arrow-right` rotates 0 → 90° so it points down, then drops 88px / 44px mobile), `blur-zoom` (S3, desktop), `ring` (S4, `data-value` + `[data-ring]`/`[data-count]`), `rise-rotate` (S10; `cta-mark` is an alias for the CTA band).
- `src/js/modules/nodes.js` (new): canvas network (`--nodes-line` / `--nodes-dot` tokens via `nodes.css`), pointer repulsion, IntersectionObserver + `visibilitychange` pause, 30fps cap on mobile/coarse pointers, DPR ≤ 2, static deterministic SVG when `hardwareConcurrency <= 4`, `Save-Data` or reduced motion (and swaps live when the preference changes).
- `src/css/components/motion.css` (new): the **html.js pre-states** (`fade-up`, `stagger` children, `img-reveal`, `split-lines`/`split-chars`/`two-voice`), inside `@media (prefers-reduced-motion: no-preference)` and only until `.is-done`; each carries a 0s **safety animation** that restores the final state after 2.5s unless the runner has claimed the element (`.is-anim`), so a script failure can never leave content hidden. Structural rules for h-scroll (native swipe row by default, pinned track only on desktop + JS + motion), stack-cards (sticky), tilt glare, `.fill-text`, draw strokes, magnetic/tilt `transition: none`. `marquee.css`, `nodes.css`, `pages/styleguide-motion.css` (new); `main.css` imports them; `base.css` comment points to motion.css.
- `templates/components/marquee.php` (new): `component('marquee', ['items' => […], 'label' => …, 'speed' => 60, 'variant' => 'display', 'dark' => true])`; one set rendered server-side (static wrapped list without JS / under reduced motion), pause button `hidden` until JS reveals it.
- `templates/partials/footer.php` + `footer.css`: the word strip is now `data-anim="marquee"` (JS loop, velocity-aware) with the pause button placed **outside** the `aria-hidden` strip (bottom-right of the panel); the CSS keyframe loop remains the no-JS fallback. The `cta-mark`, `micro-arrow`, `wave`, footer `fade-up` and contact `stagger` hooks from P05 are now live.
- `app/lib/helpers.php`: `heading()` takes a 6th `$attrs` array (e.g. `['data-anim' => 'two-voice']`).
- `templates/pages/styleguide/motion.php` (new, `/styleguide/motion/`, local only, draft/noindex): one labelled demo per effect with real copy from the data files (services, sectors, `ui()`), the nodes module (light + dark), and a link from the main styleguide nav. `src/js/main.js` boots `initMotion()`.

**Decisions**
- Effects are code-split per name (0.2–1 KB each; SplitText 7 KB, MorphSVG 12 KB, DrawSVG 4 KB load only on pages that use them) rather than one catalogue bundle, so the initial JS carries only GSAP core + ScrollTrigger + Lenis + the runner.
- Entrance triggers use `start: 'clamp(top 85%)'` so elements near the end of short pages (e.g. the footer grid) still reveal when the page cannot scroll that far.
- One-shot reveals add `.is-done` and clear GSAP's inline styles when they finish, so hover transforms/transitions defined in CSS work again afterwards; the CSS pre-state stops applying at `.is-done`.
- Lenis already subtracts `scroll-padding-top` for element targets, so the anchor code adds only a 16px margin (first version double-counted the header: fixed before commit).
- The micro-arrow uses the catalogue's `arrow-right` glyph, so S2's "−90° → 0°" becomes "0° → 90°" with the same visual result (points right, turns down, drops).
- `blur-zoom` keeps the S3 spec (`autoAlpha 0`) because it is desktop-only and reserved for the stats heading; every other text effect never goes below opacity 0.2 or uses transform/clip only.
- `read-along` splits to chars (spec) with SplitText `aria: 'auto'` so screen readers get the sentence as one label.

**Deviations**
- None from the prompt. Note for docs/12 budgets: initial JS is 158 KB raw / **60 KB gzip** (budget ≤ 90 KB read as transfer size); CSS 74 KB raw / **14 KB gzip** (≤ 60 KB). The two styleguide stylesheets (~10 KB raw) ship in the main CSS for now; splitting them out is a follow-up if the raw CSS budget is meant literally.

**Checks**
- `php -l` clean; `npm run build` clean, no warnings (the `import.meta.glob` for modules excludes the statically imported header/back-to-top so Rollup no longer warns); `check-content` runs (91 findings: +1 image key from the motion page).
- Browser (app pane, emulated 1440×900): Lenis active (`html.lenis`), 44 `[data-anim]` elements claimed; anchor chip → Lenis scroll, hash pushed, target 16px under the header, **back button** restores the URL and the top of the page; counters count to the server text and finish `is-done`; split-lines/split-chars/two-voice finish and revert to the original markup; parallax layers carry opposite offsets for speeds 0.6/0.85/1.3; **pin-steps** pin-spacer = 5 × 900px, step 2 active at 1.5 viewports, progress line `scaleY(.375)`; **h-scroll** pinned with the track at −356px at the halfway point (distance 712px); stack-cards covered cards at `scale(.94)` / opacity .6; marquee clones (5 + 2 in the footer), track moving, hover/focus handled, **pause button** 44×44, Enter toggles `aria-pressed`, label and icon; wave `d` changes between positions; read-along chars 1 → 0.63 → 0.2 across the viewport with `aria-label`; logo-assembly pieces 0 → 1 opacity between "top bottom" and "top 35%"; micro-arrow `rotate(90deg)` + drop in progress; rings at dash offsets 59/117/0 of 326 (= 82% / 64% / 100%) with the count text; nodes canvases 1278×358 ×2; no 4xx assets, no PHP notices, `scrollWidth === clientWidth`.
- 360×800 (reloaded): no pin-spacers, no overflow, h-track `overflow-x: auto`, no tilt glare (pointer effects off), parallax distances halved, steps activate as they pass 60%, nodes canvases present, marquee + pause button present. 768×1024: no overflow, 2 footer columns, no pins.
- **Reduced motion:** headless Chrome with `--force-prefers-reduced-motion` renders the whole page static with every demo visible (cards, chips, headings, images, counters at their final values, draw strokes complete) — Lenis off, no pre-states.
- **No JS:** under `html.no-js` the marquee tracks wrap, the h-scroll track is a native swipe row, the footer strip uses the CSS keyframe loop, the pause buttons stay hidden, and no pre-state applies (all pre-states are scoped to `html.js`).
- Note: the desktop app's Browser pane throttled `requestAnimationFrame` while hidden (as in P04/P05), so GSAP tweens were advanced by forcing frames with screenshots and verified from their inline styles; the "no long task > 50 ms" check could not be run in that pane (init is one short task plus lazy imports; re-check in DevTools' Performance panel on a normal browser).

**Follow-ups**
- P07: real logo mark pieces for `logo-assembly` (`[data-piece]` × 3) and the CTA band's `cta-mark`; splash hand-off uses `window.lenis`.
- P08+: use the catalogue by attribute only; heroes use `split-lines`/transform entrances (never opacity 0 on the H1/LCP image). Pages with pinned sections give them `min-height: 100vh` content and an `.is-active` style for steps/panels.
- Consider moving the styleguide CSS out of the main bundle before launch (raw CSS budget), and re-run the long-task check in a desktop browser.

---

## P07 — Logo SVGs, favicons, pixel2motion splash screen
**Date:** 14 October 2026
**Commit:** `P07: logo SVGs, favicons, pixel2motion splash`

**Files created / changed**
- `scripts/logo.mjs` (rewritten; `npm run logo`): measures both client JPEGs (mark bbox, text bands, per-glyph columns), fits the wordmark font, outlines every glyph with opentype.js and writes `public/assets/brand/logo-mark.svg`, `logo-mark-white.svg`, `logo-horizontal.svg`, `logo-horizontal-white.svg` (white type, navy bar → Navy 400), `logo-vertical.svg`, `logo-splash.svg` (vertical + the `#streak` staging light, base opacity 0), `logo-512.png`; `public/favicon.ico` (16/32/48, png-to-ico), `icon-192.png`, `icon-512.png`, `apple-touch-icon.png` (mark on white), `icon.svg` (dark-scheme style lightens the navy bar), `site.webmanifest`; plus `design/logo-motion/logo.svg` and `outputs/fit_work/geometry.json` (fit metrics + the bar-wipe clip-path polygons). Colours come from `tokens.css`. Ids: `#mark › #hex-sky #bar-azure #bar-navy` (each `data-piece`), `#wordmark › .letter` (`#letter-1…11`), `#tagline`; classes `logo__*` for CSS.
- `app/lib/helpers.php`: `inline_svg($file, ['prefix', 'class', 'attrs', 'title'])` (namespaces ids/`href="#"`/`url(#)` per copy, decorative by default) and `logo()` rewritten for the SVGs (`<img>` with width/height from the viewBox, or `inline => true`; `lockup` horizontal | vertical | mark).
- Header: one inlined SVG (`hl-` prefix) themed by CSS (`header.css`: dark theme = white type + Navy 400 bar); footer and mobile sheet use `<img src="…logo-horizontal-white.svg">`; CTA band inlines the mark (`cta-` prefix, `.cta-band__mark polygon { fill: var(--sky-300) }`); motion styleguide's `logo-assembly` demo uses the real mark; styleguide gets a **Logo** section (lockups, inline dark + hover re-draw, favicons). `app/config/site.php` `seo.logo` → `/assets/brand/logo-512.png`. `scripts/og.mjs` embeds the white lockup (temporary text mark gone). `public/assets/img/logo/*.png` removed.
- `src/css/components/logo.css` (new): shared motion vocabulary (docs/07 §6): header logo hover/focus → the bars re-draw up their diagonal in 300 ms (same clip-path polygons as the splash), `.is-loading .logo__mark` breathing loop 1 ↔ 1.02 / 3 s.
- Splash: `templates/partials/splash.php` (printed by `base.php` after the skip link; inline sessionStorage check adds `html.no-splash` before first paint; `aria-hidden`, no focusables), `src/css/components/splash.css` (overlay + the choreography scoped to `#logo-root` + a CSS-only failsafe at 2.6 s; `display: none` under reduced motion and without `html.js`), `src/js/modules/splash.js` (once per session, skip on click/tap/Esc/Enter/Space, waits for `#logo-root` animations, GSAP **Flip** (lazy chunk, 24 KB raw) fits `#mark`/`#wordmark`/`#tagline` onto the header's `#hl-*` parts while the two canvas panels slide apart along the 120° diagonal, overlay removed from the DOM, hard cap 2.4 s from first paint, `splash:done` on `window` + `html.splash-done`). `main.js` boots it after the motion core; `motion.js` excludes it from the lazy module glob.
- `design/logo-motion/`: `logo.svg`, `motion.css`, `logo_motion.html` (showcase with replay / slow motion / speed slider / `?t=` / `?static=1`), `motion_spec.md`. QA evidence in `outputs/` (git-ignored, regenerate with the commands in the spec). `.gitignore`: the skill clone `.claude/skills/pixel2motion/`.
- Docs: `docs/02` §1 (font identified, vector logo resolved), `docs/00` pending P04 and `docs/15` Q04 marked resolved. `CLAUDE.md`: Commands (`npm run logo`), helpers (`logo()`, `inline_svg()`), splash notes. `package.json`: dev deps `@fontsource/khand`, `opentype.js`, `png-to-ico`.

**Decisions**
- **The wordmark is Khand, not Saira Condensed.** A pixel fit of candidate fonts (size from the measured cap height, letter-spacing by least squares against the glyph columns, scored by IoU + ink-weight ratio) ranked Khand Regular at IoU 0.980 / weight 1.00 for "My Solutionz" and Khand Medium at 0.983 for the tagline; Saira Condensed scored 0.32. Khand (OFL) is a build-time dependency only.
- Each lockup is reproduced from its own JPEG (the horizontal has a bigger wordmark relative to the mark and tighter tagline spacing than the vertical), so both match the client's files exactly: Chrome overlays IoU 0.992 (vertical) and 0.978 (horizontal).
- One inlined SVG in the header, themed by CSS, rather than two swapped images; the footer and mobile sheet use `<img>` SVGs so the HTML stays small (an inlined lockup is 12.4 KB).
- The staging light streak runs on the 120° diagonal that rises to the right, like the gradients and dividers (the bars themselves lean the other way).
- The splash never uses `overflow: hidden` to hold scrolling: on classic-scrollbar desktops that drops the scrollbar, widens the viewport and re-reports the H1 as a later, larger LCP candidate (measured +200 ms). Lenis stop + wheel/touch prevention on the overlay instead.
- The hard cap counts from the CSS animations' start (first paint), read from `animation.currentTime`, not from module execution.

**Deviations**
- Letter cascade timing: docs/07 §3 says 280 ms letters with a 40 ms stagger inside 1000–1350 ms, which 11 letters cannot fit; the run uses 240 ms / 28 ms (last letter lands at 1530 ms, tagline 1260–1600 ms). Recorded in `motion_spec.md`.
- Lighthouse was not run (not installed; P31). LCP was measured with `PerformanceObserver` on the production build instead: the H1 is the candidate with and without the splash, medians 612 ms vs 508 ms over four alternating runs with overlapping spreads. Re-check with Lighthouse on the host in P31.
- The streak lives only in `logo-splash.svg` (the brand files stay clean); `splash.css` duplicates `motion.css` with `#logo-root` scoping and the `splash-` keyframe prefix (kept in sync by hand).

**Checks**
- pixel2motion QA: overlay inspected (uniform tint, no fringes); easing probe shows eased values at every timestamp (hex scale 0.917 at mid-window vs 0.751 linear; bar drift 4.1 px vs 8.3; letter 20 px vs 62.5); ink sweeps 400–1600 ms without a stall-then-jump; **Final Frame Contract** same-pipeline `?static=1` vs `?t=1600` = 0 differing pixels; reduced motion renders the static logo. Approved by the client in this session before integration.
- Site (Playwright, php :8000 + Vite): fresh session → overlay present with 16 animations from first paint, tagline done at +1.6 s, Flip mid-flight at +1.9 s (mark 256×146 → heading to the header's 70×40), overlay removed at +2.14 s (cap 2.4 s), `splash:done` fired, Lenis restarted; second view → `html.no-splash`, nothing rendered; Esc at 0.3 s and click at 0.2 s skip to the final frame and hand off; `prefers-reduced-motion` → no splash; 360 px plays and is gone by +2.24 s; no console errors; `scrollWidth === clientWidth` at 360 / 768 / 1024 / 1440 during and after the splash. Styleguide: all brand assets and icons 200, no duplicate ids with three inlined copies on one page, header logo 188×40 with the wordmark turning white under `data-theme="dark"`.
- `php -l` clean on every changed PHP file; `npm run build` clean (CSS 78.5 KB / 15.1 KB gz, main JS 160 KB / 61 KB gz unchanged, Flip 24 KB lazy); `check-content` unchanged at 91 findings; `npm run og` rendered the new default image.

**Follow-ups**
- P08/P09: the hero header variant must keep `.site-header__logo-svg` with the `hl-` ids so the Flip target exists; listen for `splash:done` (or check `html.splash-done`) before the hero entrance; `#logo-root` is reserved for the splash.
- P23: add `.is-loading` to a form wrapper that contains a logo mark to get the breathing loop.
- P28/P34: the splash partial's inline sessionStorage script needs a CSP nonce (same as the js-class script).
- P31: Lighthouse LCP with the splash on vs off on the real host; P33: check the splash on iOS Safari (clip-path on SVG children, `touchmove` hold).
- Client: the original vector logo is still welcome as a cross-check of the rebuilt SVGs.

---

## P08 — Home hero: structure 1:1 with `hero_should`
**Date:** 14 October 2026
**Commit:** `P08: home hero structure`

**Files created / changed**
- `app/data/home.php` (new): every home-page string keyed by section (`hero` filled; the other nine keys are empty with a `[[PENDING]]` marker until P10–P16). Hero copy as the prompt specified, all `// PROVISIONAL`; the two alternative headlines are kept in a comment; Quick-Start labels, default option labels ("Any sector" / "Any service", empty values), quick links, helper text with `{phone}` / `{consult}` tokens, tag, mini card, chip slugs, photo key + alt. Helper `home('hero.links')` + a shared `data_get()` in `helpers.php` (also used by `ui()`).
- `templates/sections/home/hero.php` (new): the docs/05 §1 anatomy. Canvas frame → `.hero__panel` (`--ms-grad-hero`, radius 28, `min-height: max(720px, min(100svh − 40px, 860px))`) → the photo layer (`img('home/hero-building', priority, cover)`, right 68 % of the panel, masked into the navy on the left + a navy wash) → the header's **`hero` variant rendered inside the panel** → 5/12 copy column (glass badge with the mark SVG filled by three gradients + drop shadow, two-line `<h1>` with the italic accent, lead in Navy 100, the **Quick-Start `<form method="get" action="/contact-us/#quote">`** with `<label for>`-ed selects for the 7 sectors + Other and `<optgroup>`s per category with the 12 sub-service slugs, "Get Started" submit, two quick links with white chevron circles) → 7/12 stage (`role="region" aria-label="Our services"`; **all 12 cards server-rendered** from `services.php`: category chip in its colour, hex tile, name, summary, link to the sub-service page; card 2 is `.is-active`, neighbours faded/scaled/blurred and masked at the column edges; the "Our Special Service" tag with the static `.special-frame` ring hugging it and extending behind the active card) → helper text bottom-right → `.hero__notch` (canvas tab, `border-top-left-radius` + two radial-gradient fillets = the S-curve) → below the panel: the mini insight card (placeholder thumb, eyebrow, title, Read) and the sector tiles (4 featured at ≥ 1024; all 7 as a marquee below).
- `templates/pages/home.php`: `header: 'hero'`, `header_theme: 'dark'`, `header_overlay: true`, `primary_image`; renders the hero then seven anchored placeholder sections (`#services #approach #sectors #process #why #faq #insights`) so the quick links already scroll.
- `src/css/pages/home.css` (new, imported by `main.css`): the layout above with the docs/05 §3 table as breakpoints (frame 20/16/12/8, radius 28/28/24/22, columns 5/7 → 6/6 → stacked, cards 300×190 → 280×180 → 76vw×180, notch 32/32/24/removed). Stage mask, card states, special frame ring (mask-composite), notch fillets, bottom row, mobile marquee rules, reduced-motion / reduced-transparency fallbacks.
- `src/js/anim/marquee.js`: new option `data-marquee-only="mobile|desktop"` (the effect returns early on the other breakpoint) so the chips loop only below 1024 px. `app/lib/Image.php`: `cover => true` gives the placeholder `preserveAspectRatio="xMidYMid slice"` so it fills a box like `object-fit: cover`. `CLAUDE.md`: `home()` helper + sections folder.

**Decisions**
- Headline size: `--fs-display` (96 px at 1440) cannot fit "Tenders, Crafted" (16 characters) inside the 5/12 column; the hero uses `clamp(3.75rem, 1rem + 3.6vw, 4.5rem)` ≥ 1280 (68 px at 1440, 72 px cap) and the table's 56 / 48 / 40 px below. Recorded as a deviation.
- Sector icons and the Quick-Start label icons come from the **light** sprite set (the category/sector icons only exist in that weight).
- The photo gets its own clipped layer (`overflow: hidden; border-radius: inherit`) so the panel itself can let the mega menu and, later, the frame glow overflow.
- The bottom row is 4/8 rather than 5/7 so the four tiles fit one row at 1440; mobile card width is 76vw (spec 82) so the next card visibly peeks inside the panel.
- The hero header variant keeps `.site-header__logo-svg` with the `hl-` ids, so the splash still has its Flip target on the home page.

**Deviations**
- The prompt lists "splash partial → hero → `<main id="main">`"; the base layout already wraps every page in `<main>` and prints the splash, so the hero renders inside `<main>` (the `<header>` inside it is not a banner landmark; the nav is still a navigation landmark). No template rework.
- Side-by-side check: the reference (`hero_should.jfif`, 736 px) and our 1440 px render match in composition, element placement, proportions and the notch; the vertical rhythm is taller because the real nav row and the 190 px cards take more height than the mock's.

**Checks**
- `php -l` clean; `npm run build` clean; `php bin/seo-audit.php --all` PASS (one `<h1>`); `check-content` +13 findings (the provisional hero copy, the `[[PENDING]]` placeholders, the two new image keys).
- Playwright (dev servers, splash pre-skipped): `scrollWidth === clientWidth` at **360 / 768 / 1024 / 1440**; 12 cards in the HTML; the form action is `/contact-us/#quote` (GET → `?sector=…&service=…#quote`); headline 40 / 48 / 56 / 67.8 px; panel 1153 / 996 / 696 / 860 px; the active card centred in the stage at 1024+ and leading at the left with the next card peeking at 360 / 768; marquee clones 7 below 1024 with the pause button shown, 0 at 1024+ with the extra tiles hidden; no console errors. Tab order: skip link → logo → nav links/toggles → phone → quote → the two selects → submit → quick links → cards, every stop with the 2 px focus ring.
- Browser pane at 1440×1000: nav inside the panel, badge, headline, bar, links, tag + frame + cards, helper text, notch stepping up on the right, mini card + 4 tiles below; 360: stacked, straight bottom, stacked Quick-Start with a full-width button, chips marquee.

**Follow-ups**
- P09: `src/js/modules/hero-slider.js` on `[data-hero-slider]` / `[data-hero-track]` / `[data-hero-card]` (take over `.is-active` and the track offset), animate `.special-frame` (`@property --angle`), listen for `splash:done` for the entrance timeline, parallax on `.hero__photo`.
- P16: make the mini card the latest insight (`home.hero.mini`).
- P23: `/contact-us/` reads `sector` / `service` from the query to pre-select the form.
- Client: real hero photo (`assets-src/images/home/hero-building.jpg`) and the mini-card thumbnail; headline choice (A / B / C) and the free-consultation claim.

---

## P09 — Home hero: services slider, glowing border, choreography
**Date:** 14 October 2026
**Commit:** `P09: hero slider, glowing border, entrance choreography`

**Files created / changed**
- `src/js/modules/hero-slider.js` (new, `data-module="hero-slider"` on `.hero__stage`; lazy chunk 40 KB raw / 16 KB gz with Draggable + InertiaPlugin): infinite loop over the 12 server-rendered cards. Each card is placed by its wrapped distance from a fractional `current` index (prev / active / next at opacity 1 − .55t, scale 1 − .08t, blur t px; cards further than 2.5 slots hidden), so the reference's faded neighbours and the viewport mask stay as in P08. Autoplay every 3.5 s, 700 ms `cubic-bezier(.22,1,.36,1)` (CustomEase `slide`); held while hovered (mouse only), while focus is inside, while the tab is hidden, while dragging and when paused with the button. Drag / swipe through a proxy Draggable (`type: x`, inertia, snap to the nearest card, `allowNativeTouchScrolling` + `touch-action: pan-y`); the click after a drag is swallowed so a card link never opens by accident. ← → inside the region; focusing a neighbour's link centres it. Controls prev · 12 dots (`aria-current`) · next · pause (`aria-pressed`, label swap); `aria-roledescription="carousel"`, cards `role="group" aria-roledescription="slide" aria-label="n of 12"`, far cards `aria-hidden` + `tabindex="-1"`, a polite live region announces user-initiated changes only; `.special` breathes on every change. Reduced motion: no autoplay, instant moves. Adds `html.no-at-property` when `CSS.registerProperty` is missing.
- `src/css/components/special-frame.css` (new): docs/05 §5 as written (`conic-gradient(from var(--angle), …)` ring masked to 1.5 px with `mask-composite`, `ms-orbit` 4 s linear, blurred halo pulsing 2.8 s), with the ring and the halo as `::before` / `::after` so the ring mask does not clip the glow; a third mask layer `conic-gradient(#000 var(--draw), transparent 0)` lets the intro draw the ring (registered `--draw`, inherits so the pseudo-elements follow the element GSAP tweens); the 120° tag shine every 5 s; `.is-breathing`; the `@property`-less fallback (oversized conic square rotating inside the masked ring); reduced motion = static ring with the light at the top edge and a soft halo.
- `src/js/modules/hero-intro.js` (new, `data-module="hero-intro"` on `.hero`): the docs/05 §6 timeline on `splash:done` (or at once when `html.splash-done` is already set): panel `clip-path` opens 0.9 s, photo scale 1.15 → 1 (opacity never touched), nav items stagger, badge `back.out(1.6)`, H1 lines slide up inside SplitText masks (no opacity), lead, Quick-Start bar, the three visible card links from x +80, the frame draws (`--draw` 0 → 100 %) then orbits (`.is-live`), helper + quick links + controls, mini card + chips stagger; `clearProps` at the end, then the scroll parallax (scrub, `top top` → `bottom top`): photo `yPercent` 12, copy `yPercent` −8 + opacity .6, stage `yPercent` −14, panel radius 28 → 40; mobile halves the distances. A 4 s timeout lands the final state if the ticker stalls. Reduced motion: no intro, no parallax, frame live at once.
- `src/css/pages/home.css`: JS-mode stage (`.is-js`: cards absolute, controls, dots, grab cursor), the entrance **pre-states under `html.js`** (panel clip, H1 `clip-path: inset(0 0 100% 0)`, the rest opacity 0; `.is-intro-live` hands over to GSAP; a 0 s animation at 3 s restores everything if the script never takes over; none under reduced motion), controls keep their space from the server render (no shift when they appear). `templates/sections/home/hero.php`: carousel semantics, controls + live region, `data-module`s; `on-dark` moved from the section to the panel (the bottom row is on the canvas). `main.css` imports `special-frame.css`.

**Decisions**
- The panel's opening clip is `inset(5% 4% 5% 4%)` rather than the table's 6 %, so the header logo (the splash's Flip target) is never clipped while the hand-off lands.
- Autoplay and the move tween run on the GSAP ticker, which already pauses in hidden tabs; `visibilitychange` additionally holds the timer.
- The hero header is excluded from the intro except its links / phone / CTA, again so the logo is visible from the first frame.
- CSS pre-states, not JS, hide the intro'd elements at first paint (docs/12 §4); the H1 is clipped, never faded, and the photo is not pre-stated at all.
- Found while verifying: `base.css` colours every `a` inside `.on-dark` sky / white on hover, which the P08 sector tiles, mini-card button and quick links inherited (the tiles' text turned white on hover). Scoping `on-dark` to the panel fixes the whole bottom row; the quick links get an explicit white. The parallax moves the image inside the clipped photo layer, not the layer (moving the layer showed a translucent band under the panel when scrolled).

**Deviations**
- Lighthouse not available (P31). LCP measured with `PerformanceObserver` on the production build with the splash on: with the placeholder SVG the LCP candidate is the lead paragraph at ~3.7 s because an inline SVG cannot be an LCP candidate and the H1 is masked until the intro reveals it; with a temporary high-entropy photo in `assets-src/images/home/` (not committed) the `<img>` is the LCP element at **~0.7 s** on mobile 360 and desktop. The real photo is therefore required for the ≤ 2.5 s target; noted for the client.

**Checks**
- Playwright, production build: CLS 0.011 total (0.003 intro, 0.008 slider boot; the dev server's JS-injected CSS adds a 1.0 shift that does not exist in production); with the splash on, CLS 0.014; `php -l`, `npm run build` clean; `seo-audit --all` PASS; `check-content` unchanged at 100.
- Behaviour (Playwright, 1440): ring `--angle` advances continuously (43° → 100° in 0.5 s); autoplay 1 → 3 in 4.2 s; held under hover, under focus-within, when paused; → moves 3 → 4 and announces "Slide 5 of 12: Policy Management"; dot 5 sets `aria-current`; a 300 px drag moves two cards with inertia and stays on the page; parallax at 400 px: photo +40 px, stage −20 px, copy opacity .84, radius 33 px; reduced motion: intro done at once, no autoplay, no ring animation, → instant. Mobile 360 (touch): swipe 1 → 2, active card at the 32 px slot, no overflow. Sectors dropdown opens under its nav item inside the panel; tiles ink / azure-800 on hover. No console errors.
- Frames: `intro_mid` (headline in, bar fading, cards and frame not yet) and `intro_done` (frame drawn with the light at the top-left, dots under the cards) inspected.

**Follow-ups**
- P10–P16: the sections below the hero; the `#services` quick link already scrolls through Lenis.
- P31: Lighthouse LCP / CLS on the host once the real photo is in; the controls' pause button could gain a visible label on hover.
- P33: Safari check of `mask-composite` with three layers and the `@property` fallback path.
- Client: the hero photo (needed for the LCP target), headline choice, tag wording ("Our Special Service" vs "Our Core Services").

**Fix (14 October 2026, client feedback, commit `P09 fix: notch S-curve, plain hero placeholder`)**
- The notch showed a dark spike where the two curves met: the panel-corner fillet (28 px) and the tab's top-left radius (28 px) overlapped vertically inside a 32 px notch. The S-curve needs `notch ≥ fillet + tab radius`, so the notch is now 60 px at ≥ 1024 (close to the reference's ~7 % of the panel height) and 52 px on tablet; docs/05 §3's "32 px" is superseded by this rule.
- "Remove the background pattern": the hexagon pattern was the branded image placeholder showing through the right 68 % of the panel. `img()` gains `pattern => false` (placeholder without the hex pattern); the hero photo and the mini-card thumbnail use it. The real photo replaces the placeholder entirely.

**Fix 2 (14 October 2026, client feedback, commit `P09 fix: sector tiles inside the notch`)**
- The four sector tiles now sit *inside* the canvas notch tab (as in `hero_should`), not in the row below the panel: `.hero__chips` moved into `.hero__notch` (which therefore loses `aria-hidden`), the notch grows to 88 px (≥ 1024) to hold the 56 px tiles, and `.hero__bottom` keeps only the mini insight card. Because the notch is inside the `.on-dark` panel, the tiles get an explicit ink / Azure 800 colour so `base.css`'s `.on-dark a` rules do not turn them white. Below 1024 the notch is already a plain strip under the helper text, so the marquee of all seven sectors is unchanged there. Checked (Playwright): no horizontal scroll at 360 / 768 / 1024 / 1440, the four tiles on one row inside the tab at 1024 (compact padding) and 1440, no console errors.

---

## P10 — Home: "Our Approach to *Winning Contracts*"
**Date:** 14 October 2026
**Commit:** `P10: home approach section`

**Files created / changed**
- `templates/sections/home/approach.php` (new): `section.approach#approach` → `.container.approach__grid` (11fr / 9fr). Left: eyebrow "About My Solutionz", `heading(2, 'Our Approach to', 'Winning Contracts')` with `data-anim="two-voice"`, then `.approach__figure`: the 5:4 image (`img('home/approach-meeting', …, pattern => false)`, `data-anim="img-reveal"`) and the **Office Hours** card (`aside.approach__hours.on-dark`, `aria-labelledby`, `data-anim="fade-up" data-x="40" data-y="-40" data-delay="0.35"`) with a `<dl>` of day ranges built from `config('hours.days')` + `config('hours.closed')` ("Mon–Fri 09:00–17:30", "Sat–Sun Closed") and "Email us any time → info@mysolutionz.co.uk" (`config('email')`). Right (`data-anim="stagger"`): `.approach__num.fill-text` "10+" (`aria-hidden`, `data-anim="fill-text counter" data-to="10" data-suffix="+"`) next to the visually-hidden "Over 10 years of business development experience" (L05c), the sector avatar stack with the label "Strategic bids for sustainable growth", the paragraph, and the split button "More About Us" → `/about-us/`.
- `src/css/pages/home-approach.css` (new, imported by `main.css` after `home.css`): the notch is a **true CSS mask** on `.approach__image` (docs/04 §1.5 🔒: over an image the cut-out must be real transparency): seven `mask` layers — three plain rectangles around the hole, a fourth under it, two `radial-gradient` fillets (`--nf` = 28 px) where the hole meets the image's top and right edges, and the hole's rounded inner corner (`--nr` = card radius 28 + gap 12 = 40, so the curves are concentric). Geometry in custom properties: `--nw` 360 × `--nh` 248 (≥ 1280), 300 × 236 (1024–1279); P09's rule `notch ≥ fillet + inner radius` holds. The card is absolutely positioned in the hole (`calc(var(--nw) - var(--gap))` wide), Azure 650 → Azure 500 gradient, white text, `shadow-2`. The numerals: Mona Sans 700, `clamp(140px, 16vw, 220px)`, `background-image: var(--fill-image, var(--ms-grad-brand))`, `background-size: 180% auto` so `fill-text` has room to pan; under reduced motion the background sits at 50 %. Below 1024: one column, mask off, the card static under the image, 4:3 image.
- `app/data/home.php`: `approach` section (eyebrow, heading, image key/alt, card labels, number + SR text + fill-image key `home/approach-handshake`, the three avatars `first-aid-kit`/`bus`/`shield-star` in bid/compliance/digital tints with alt text, the paragraph from `about-us-content.md` §1 condensed, the button). `app/config/site.php`: `hours.closed` (Sat, Sun, `// PROVISIONAL`) and `hours.email_anytime`; the comment forbids 24/7 claims.
- `templates/components/avatar-stack.php` + `avatar-stack.css`: avatars accept `icon` (+ `category`) and render a small C07 hex tile on a white circle (no fake people); `.avatar-stack--xl` = 56 px. `src/js/anim/fade-up.js`: optional `data-x`, `data-y`, `data-delay` (defaults unchanged: 0 / 24 / 0). `app/lib/Image.php`: `Image::src($key, $minWidth)` returns one JPEG rendition URL for CSS backgrounds, or null when the key is not in the manifest (the template then emits no inline `--fill-image` and the gradient shows).
- `templates/pages/home.php`: the section list maps `approach` to its template; the other ids stay placeholders in docs/08 §4 order (hero → services → approach → sectors → process → why → faq → insights).

**Decisions**
- Hours are never typed in the template: the day ranges are derived from `config('hours')` (`Monday…Friday` → "Mon–Fri"), so the client's confirmed hours (docs/15 P08 ❗) are a config change only.
- The number is `aria-hidden` and the sentence "Over 10 years of business development experience" is the accessible text, so screen readers never hear "zero plus" during the count-up and the L05c wording is exact.
- The image-filled numerals use a gradient until `home/approach-handshake` exists in the manifest; the inline style carries only the custom property (no CSP in place; `Image::src()` keeps the URL out of the template).
- The hours card uses `fade-up` with the new offset options (slides in from the top-right, 0.35 s after the image) instead of a new effect; the catalogue stays at 26 effects.
- Innovax's "Expert Support: Anytime" line is replaced by "Email us any time" so the page never implies 24/7 support (docs/13).

**Deviations**
- Playwright's `scrollIntoView` / full-page screenshots do not trigger ScrollTrigger reveals under Lenis (the pre-states stay applied); the checks below scroll with the mouse wheel instead. Real users and anchor links are unaffected (P06 verified anchors through Lenis).
- The split button's tile is white on the primary blue (C03 as built in P05); the reference's white square with a dark arrow matches.

**Checks**
- `php -l` on all six PHP files clean; `npm run build` clean, 0 warnings; `seo-audit --all` PASS (/, styleguide, motion); `check-content` 101 findings (100 + the new `PROVISIONAL` closed days).
- Playwright (php :8000 + Vite): `scrollWidth === clientWidth` at **360 / 768 / 1024 / 1440**; no console errors; no 4xx or failed requests on the home page. 1440: image 656 × 525 with the card 348 × 236 in the hole, both fillets and the inner corner drawn, the right column 10+ / stack / text / button; 1024: narrower hole (300 × 236), label on two lines; 768 / 360: stacked, card under the image with no notch.
- Behaviour: counter samples 3+ → 7+ → 9+ → 10+ and ends on the server text "10+"; `fill-text` background-position 86 % → 100 % over 400 px of scroll; `img-reveal` ends at `inset(0 round 28px)`; nothing left hidden after the reveals. Reduced motion: no counter, background at 50 %, no clip, nothing hidden.
- Keyboard: Tab reaches the e-mail link (Sky 2 px ring on the gradient card) then "More About Us" (Azure 2 px ring); Shift+Tab reverses.
- View source: heading, hours, SR sentence, paragraph and button are in the server HTML; no new JSON-LD.

**Follow-ups**
- Client: office hours (docs/15 P08 ❗), the meeting photo `home/approach-meeting` (5:4 or taller) and the handshake photo `home/approach-handshake` for the numerals.
- P11: the Services section above this one (the hero's `#services` quick link currently lands on the placeholder).
- P33: Safari check of the seven-layer `mask` shorthand (the hero's `mask-composite` check covers the same ground).

---

## P11 — Home: "Our Services" (3 stacking category cards)
**Date:** 14 October 2026
**Commit:** `P11: home services stacking cards`

**Files created / changed**
- `templates/sections/home/services.php` (new): `section.services#services` (🔒 first section after the hero, docs/08 §4 H2) → header (eyebrow "Our Services", `heading(2, 'Everything You Need to', 'Compete and Grow')` with `two-voice`, the `.lead` intro with `fade-up`) → `.services__stack[data-anim="stack-cards"]` with one `article.service-card` per category from `services()`: `.service-card__top` (C07 hex tile `lg` in the category colour + the "01/02/03" numeral, `aria-hidden`), H3 (`aria-labelledby` on the article), summary, `ul.service-card__subs` (`aria-label="<category> services"`) of `a.service-card__sub.hex-tile-trigger` links (hex tile `sm` + name + one-line summary + a slide-in arrow) → sub-service URLs, then the split button "Explore <category>" → the category URL. Media column: `img('services/<slug>')` 4:5 in `.notch.notch--bl` with the count chip ("3 services" / "5 services" / "4 services", `chip--<colour>` + `hexagon` icon) in the bottom-left cut-out. Digital Presence renders as `.service-card--dark.on-dark` (navy, Sky accent) with the glass split button.
- `src/css/pages/home-services.css` (new): 7/5 card grid, `min-height: 70vh`, `--r-2xl`, 6 px top band in the category colour (`border-top`), `shadow-2`; the sub-service list in two columns ≥ 1280 (one column 1024–1279, where two are cramped); hover = category ink on the name + underline + the arrow sliding in (the hex tile's gradient fill comes from C07's `.hex-tile-trigger`); `--stack-top` = initial header height + 24 px. Below 1024 and under reduced motion the cards are `position: static` (plain list), the image moves above the body (16:9, 4:3 on phones) and the long "Explore …" label wraps inside a full-width button.
- `src/css/components/notch.css` (new, shared): the true-mask cut-out from P10 extracted as `.notch.notch--tr` / `.notch--bl` (geometry via `--nw`, `--nh`, `--nr`, `--nf`; `.notch--off`), used by the approach image (top-right) and the service-card images (bottom-left). Adjacent mask layers now **overlap by 1 px** (only into fully visible parts of their neighbours): at fractional box sizes two half-covered pixels at a shared edge unioned to < 100 % alpha and showed as hairline seams (seen at 1024). `home-approach.css` drops its own mask block.
- `app/data/home.php`: `services` section copy (eyebrow, heading, intro `// PROVISIONAL`, the "Explore {name}" / "{n} services" patterns, provisional alt text per category image). `src/icons.json`: `hexagon` added to the regular weight (sprite rebuilt, 89 symbols). `templates/pages/home.php`: `services` maps to its template.
- Fix found while checking: the approach section's hours card starts its fade-up 40 px to the right; below 1024 it is full width, so until revealed the document was 40 px wider than the viewport (no visible scrollbar thanks to `html { overflow-x: clip }`, but a real overflow). `.approach__figure { overflow-x: clip }` under 1024.

**Decisions**
- Category order, names, summaries, icons and every URL come from `app/data/services.php`; the section adds no copy of its own beyond the header and label patterns, so P20/P21 and C-prompts change nothing here.
- The count chip's hexagon icon and the hex tiles echo the logo (docs/04 §4); no photos of people.
- Stacking uses the catalogue's `stack-cards` unchanged (sticky from CSS, scale 0.94 / opacity 0.6 scrubbed by JS); the end of the stack needs no special handling because all three cards share the same sticky top and leave together with the container.
- The numeral uses Mona Sans 500 in Navy 100 (white on the dark card at 18 %) so it reads as a watermark, not a heading.

**Deviations**
- None against the prompt. The 6 px band is a `border-top` under the card radius, so it tapers into the corners rather than running square across; it reads cleaner with the 32 px radius than a separate bar would.

**Checks**
- `php -l` clean; `npm run build` clean, 0 warnings; `seo-audit --all` PASS (/, styleguide, motion); `check-content` 104 findings (101 + the intro and alt-text `PROVISIONAL` markers and the new image keys).
- Links: all 16 hrefs in the section (hub, 3 categories, 12 sub-services) return 200 on the local server.
- Playwright (php :8000 + Vite): document width equals the viewport at **360 / 768 / 1024 / 1440** at 0.5, 1.5, 2.5, 4 and 6 s after load (bisected by hiding each section); no console errors; no failed requests. Stacking at 1440 sampled through the section: cards sticky at 112 px, card 1 scrubs to scale 0.94 / opacity 0.6 as card 2 covers it, card 2 to 0.959 / 0.73 under card 3, card 3 never dimmed, no top-position jump > one wheel step at the end of the stack. Reduced motion: `position: static`, scale 1 / opacity 1 throughout, arrows always visible.
- Keyboard: Tab order inside a card is sub-links in order then "Explore …", then the next card; sub-links show the 2 px Azure ring (Sky on the dark card) and the arrow appears on `:focus-visible`; both CTAs ringed; every link ≥ 44 px tall. The hero's "Explore Services" quick link lands with the section 104 px from the top (header offset) through Lenis.
- Renders inspected at 1440 (card 1 with the chip tab and CTA at the bottom, the dark card with the two cards peeking above it), 1024 (one-column subs), 768 and 360 (image above, chip in the cut-out, wrapped button).

**Follow-ups**
- Client: the three category photos `services/<slug>` (portrait, 4:5 or taller).
- P12: Sectors (pinned horizontal scroll) below the approach section.
- P33: Safari check of `overflow-x: clip` on `.approach__figure` and the `.notch` masks.

---

## P12 — Home: Sectors (pinned horizontal scroll)
**Date:** 14 October 2026
**Commit:** `P12: home sectors horizontal scroll`

**Files created / changed**
- `templates/sections/home/sectors.php` (new): `section.sectors#sectors[data-anim="h-scroll"]` → `.sectors__bg.nodes[data-module="nodes"]` (desktop only) + `.sectors__layout`: the **intro column** (eyebrow "Sectors", `heading(2, 'Sectors We', 'Serve')` with `two-voice`, the client-approved Our Story paragraph, link "All sectors" → `/sectors/`) and `.sectors__scroller[data-h-viewport]` holding `.sectors__clip` > `ul.sectors__track[data-h-track]` of seven `li[data-h-item]` cards from `sectors()`, then the progress bar `[data-h-progress]`. Card (`article.sector-card.on-dark`): 3:4 `img('sectors/<slug>')` in `.notch.notch--br[data-anim="velocity-skew"]`, bottom shade, C07 hex tile, H3 whose link is stretched over the whole card (`::after`), summary, and the 56 px arrow tile in the bottom-right cut-out (decorative; the H3 link is the only tab stop per card).
- `src/css/pages/home-sectors.css` (new): intro column 420 px (340 at 1024–1279) + track; cards 320 px (290 / `min(76vw, 300px)`); `--edge` aligns the row with `.container`; below 1024 and under reduced motion the track is the native scroll-snap row from motion.css with `scroll-padding-inline`, a thin Azure/Navy 100 scrollbar and the intro stacked above. Desktop + motion: the section is `min-height: 100vh` and centred, the clip wrapper hides the translated track behind the intro column with a 40 px fade (the track is padded by the same 40 px so the first card starts clear of it), the 3 px progress bar fills under the track, the nodes canvas sits behind at 55 %.
- `src/js/anim/h-scroll.js`: three backwards-compatible options — `[data-h-viewport]` (the visible window for the distance maths, so an untranslated column can sit beside the track), `[data-h-progress]` (scaled 0 → 1 on `onUpdate`), and a `focusin` handler that scrolls the page to the pin position showing the focused `[data-h-item]` (resetting any `scrollLeft` the browser applied to the overflow-hidden clip), so keyboard users never land on a card translated out of view. Cleanup removes the listener on matchMedia rebuild. `docs/06` §3 row updated.
- `src/css/components/notch.css`: `.notch--br` (bottom-right hole), the bottom-left geometry mirrored in x. `app/data/home.php`: `sectors` section copy (eyebrow, heading, the verbatim paragraph, link, provisional alt text per sector); the stale empty `'sectors' => []` placeholder key removed (a duplicate key later in the array literal silently overrode the new one and threw on render). `templates/pages/home.php`: `sectors` maps to its template.

**Decisions**
- The intro stays fixed while only the cards travel (Innovax sticky-panel feel, docs/04 §1.0) rather than scrolling the intro away as the first track item: it keeps the heading on screen for the whole pin and the "All sectors" link reachable at every progress.
- The whole card is clickable through the H3 link's stretched pseudo-element; the arrow tile is visual only. One tab stop per card, with the focus ring drawn on the card (`:has(:focus-visible)`).
- `velocity-skew` goes on the card media (its `overflow: hidden` already matches `.notch`); the skew follows the vertical scroll velocity, which is what drives the pin.
- Under reduced motion the row stays a native swipe row (allowed by the prompt) instead of a stacked grid: all seven cards remain reachable with no motion and no pin.

**Deviations**
- The nodes canvas is hidden below 1024 (prompt: desktop only) by CSS; the module's IntersectionObserver keeps it paused there rather than not loading it (the loader has no media condition).

**Checks**
- `php -l` clean; `npm run build` clean, 0 warnings; `seo-audit --all` PASS; `check-content` 106 findings (104 + the alt-text `PROVISIONAL` and the sector image keys).
- Links: `/sectors/` and the seven sector URLs return 200.
- Playwright 1440: the section pins (`pin-spacer` present), the track translates 0 → −1436 px (= `scrollWidth − viewport`), the progress bar scales 0 → 1, the following section never moves more than one wheel step per tick across the pin end (no jump). Focusing the sixth card's link at the start of the pin scrolls the page so the card sits at x 786–1106 with the 2 px Sky ring on the card. Resize 1440 → 800: pin removed, track `overflow-x: auto`, transform cleared, document width 800; back to 1440: one pin rebuilt, no leftover spacers. Reduced motion: no pin, no transform, bar hidden, static SVG nodes, native row. Document width equals the viewport at 360 / 768 / 1024 / 1440; no console errors.
- Renders: 1440 mid-pin (cards sliding behind the intro's fade, bar at 44 %), 1024 at the pin start (first card clear of the fade), 768 and 360 swipe rows with the intro above.

**Follow-ups**
- Client: the seven sector photos `sectors/<slug>` (portrait 3:4).
- P13: Process 01–05 below this section.
- P33: Safari check of the pin inside Lenis and the `mask-image` fade on the clip wrapper.

---

## P13 — Home: Process 01–05 (pinned steps)
**Date:** 14 October 2026
**Commit:** `P13: process steps component and home section`

**Files created / changed**
- `templates/components/process-steps.php` (new, C15, reusable): params `eyebrow`, `heading`, `accent`, `id`, `steps[]` (`title`, `icon`, `text`, optional `detail[]`, `image`, `detail_image`), `variant` `compact|full` (full prints the detail paragraphs, for the About page in P19), `cta`, `class`, `pin_length`. Markup: `.process-steps[data-anim="pin-steps"][data-pin-length]` → `.container` grid → copy column (eyebrow, two-voice H2 with the given id, `ol.process-steps__list` with the vertical line `[data-step-progress="y"]`, one `li[data-step]` per step with the `aria-hidden` "01" numeral, H3 and text, the link-arrow CTA) + `.process-steps__visual[aria-hidden]` with one `[data-step-panel]` per step: two overlapping decorative images (`process/step-0N` 4:5 and `process/step-0N-detail` 1:1, placeholders) and the icon chip. Step 1 and panel 1 carry `.is-active` from the server so the no-JS / reduced-motion state is complete.
- `src/css/components/process-steps.css` (new): light default + `.on-dark` navy variant through `--ps-*` custom properties; the list's left padding holds the 48 px right-aligned numerals and the 2 px line (fill scales from the top); visual column = a 10 × 10 grid with image A (cols 1–6, rows 1–8), image B (cols 5–10, rows 4–10, 6 px ring in the surface colour, `shadow-2`) and the 64 px Azure chip at the overlap with `shadow-glow`; panels stack in one grid cell and crossfade on `.is-active` (700 ms). ≥ 1024: 7/5 columns; with JS and motion the block is `min-height: 100vh`, centred, and inactive steps sit at `--ps-dim` (.35, never 0) only once the runner has claimed it (`.is-anim`). Below 1024 the images lead (`order: -1`) and every step stays at full opacity; the active numeral still highlights as the step passes 60 % of the viewport (the effect's mobile branch).
- `src/js/anim/pin-steps.js`: `data-pin-length` (scroll length per step as a fraction of the viewport; the prompt's 5 × 60vh) and the documented `data-pin-start`. `docs/06` §3 row updated.
- `templates/sections/home/process.php` (new): `section#process[aria-labelledby="process-title"]` rendering the component (compact, `on-dark`, 0.6). `app/data/home.php`: `process` (eyebrow "How We Work", "Our Process, / Step by Step", the five steps with the client's approved headings, our one-line summaries marked `// PROVISIONAL`, icons `magnifying-glass` / `strategy` / `pen-nib` / `seal-check` / `paper-plane-tilt`, CTA "See our full approach" → `/about-us/#our-approach`); the stale empty `process` key removed. `templates/pages/home.php`: `process` maps to its template. `main.css` imports the component CSS.

**Decisions**
- Navy surface for this section so the home page alternates (dark hero → three light sections → dark process → P14 bento): the two-voice accent turns Sky on dark as base.css already provides.
- The step numerals are `aria-hidden`; the `<ol>` gives screen readers the numbering, and the panels are `aria-hidden` with decorative images because every step's meaning is in the list text (acceptance: readable without JS).
- The chip crossfades with its panel rather than morphing: the icons are sprite `<use>` references, so MorphSVG has nothing to morph; the fade reads the same at 700 ms.
- Found while checking: base.css's `ul[class], ol[class] { padding: 0 }` outranks a single-class selector, so the list's padding (and the numerals) collapsed; the list rule now carries two classes.

**Deviations**
- None against the prompt. The `draw` effect listed for H6 in docs/08 is not used: the progress line is the `pin-steps` fill (one scrubbed transform), which is the same visual with one fewer plugin on the page.

**Checks**
- `php -l` clean on the four PHP files; `npm run build` clean, 0 warnings; `seo-audit --all` PASS; `check-content` 111 findings (106 + the five `PROVISIONAL` step summaries); the About anchor target `/about-us/` returns 200 (the stub until P18/P19).
- Playwright 1440 (900 px viewport): pin-spacer 3600 px = 900 + 5 × 0.6 × 900; steps activate 1 → 5 and the panels follow in step; the line scales 0 → 1; inactive steps at 0.35 (minimum seen); the next section never moves more than one wheel step per tick across the release. 360: no pin, every step at opacity 1, the active numeral and panel follow as the steps pass; reduced motion 1440: no pin, no transform, line full, all steps visible, panel 1 shown. Document width equals the viewport at 360 / 768 / 1024 / 1440; no console errors.
- Renders: 1440 mid-pin (step 03 highlighted, line at 48 %, chip = pen-nib), 1024, 768 and 360 (images above the list, every step readable).
- Keyboard: the CTA link is the only tab stop and shows the Sky ring on navy.

**Follow-ups**
- Client: ten process photos `process/step-0N` (portrait) + `process/step-0N-detail` (square), or five if the detail image should be shared.
- P19: reuse `component('process-steps', [... 'variant' => 'full', 'class' => ''])` with the `detail` paragraphs from about-us-content.md §3.
- P14: Why Work With Us bento below this section.

---

## P14 — Home: Why Work With Us bento + logo assembly
**Date:** 15 October 2026
**Commit:** `P14: home why-us bento and logo assembly`

**Files created / changed**
- `templates/sections/home/why.php` (new): `section.why#why` → header (eyebrow "Why My Solutionz", `heading(2, 'Why Work', 'With Us')` with `two-voice`, the client's heading "More than tender writers. A partner in your business growth." as the lead with `fade-up`) → `.why__grid[data-anim="stagger"]` of seven `article.why__tile.hex-tile-trigger` (C07 hex tile `sm`, H3 title verbatim from about-us-content.md §4, one-line text) with the centre tile inserted after the first: `.why__tile--centre.on-dark[data-assembly-scope]` holding `inline_svg('brand/logo-mark.svg', prefix 'why', data-anim="logo-assembly")` and a `<dl>` of three counters — `10+` (parsed from `config('experience')`), `7` (`count(sectors())`), `12` (the sub-services summed from `services()`) — each `data-anim="counter"` with the final number server-rendered. The seventh tile is the Azure gradient tile.
- `src/css/pages/home-why.css` (new): tiles white with a hairline border, `--r-lg`, lift + `shadow-2` on hover (the hex tile fills through C07's trigger class); centre tile Navy 900 with a soft Azure radial glow behind the mark (so the navy bar still reads), Sky counters; desktop 4 × 3 bento `[1][C][C][2] / [3][C][C][4] / [5][6][7 7]` with the seventh tile laid out horizontally; tablet two columns with the centre and seventh tiles spanning both; mobile one column.
- `app/data/home.php`: `why` (eyebrow, heading, lead, the seven tiles with icons `trend-up` / `squares-four` / `fingerprint` / `target` / `hand-heart` / `sliders-horizontal` / `handshake`, the three counter labels); the stale empty `why` key removed. `templates/pages/home.php`: `why` maps to its template. `main.css` imports `home-why.css`.

**Decisions**
- Counter numbers are never typed: 10 comes from the locked experience string, 7 and 12 from the data files, so they stay true when the client adds a sector or service. The year label uses the L05c wording ("years of business development experience").
- The assembly uses the colour mark (sky hexagon, azure bar, navy bar) so the three brand pieces stay recognisable; the radial glow lifts the navy bar off the navy tile instead of recolouring the logo.
- The tiles are not links (the copy is the About page's Why Us section, which P19 builds); hover is the only interaction, so the section adds no tab stops.
- The assembly keeps the catalogue's defaults (fan-out along the 120° axes, scale 2, scrubbed top-bottom → top 35 %); on mobile the effect already shortens the travel to 60 %.

**Deviations**
- None against the prompt.

**Checks**
- `php -l` clean; `npm run build` clean, 0 warnings; `seo-audit --all` PASS; `check-content` unchanged at 111 (no new markers: all copy is client-approved).
- Playwright: before the section enters, the three pieces sit at their scattered transforms with opacity 0; after the centre tile passes 35 % of the viewport every piece is at `matrix(1, 0, 0, 1, 0, 0)` with opacity 1 (1440 and 360). Counters finish on "10+", "7", "12". All eight tiles at opacity 1 after the stagger. Bento placement at 1440 measured: tile 1 / 3 / 5 in column 1, the centre 628 px wide in columns 2–3 over two rows, tiles 2 / 4 in column 4, tile 7 spanning columns 3–4. Reduced motion: pieces untransformed from the start, counters static, nothing hidden. Document width equals the viewport at 360 / 768 / 1024 / 1440; no console errors.
- Renders inspected at 1440, 1024, 768 and 360.

**Follow-ups**
- P15: triage row, testimonials (hidden until supplied) and the FAQ teaser.
- P19: the About page's Why Us section reuses the seven titles with the full paragraphs.

---

## P15 — Home: triage row, testimonials (hidden until supplied), FAQ teaser
**Date:** 15 October 2026
**Commit:** `P15: home triage, testimonials and FAQ teaser`

**Files created / changed**
- `app/data/faqs.php` (new): `groups` (basics, services, pricing-process, sectors → labels) + `items` (id, q, a, group, pages). Seeded with the five prompt questions, each `// PROVISIONAL`, tagged `home`, `faq` and the service slugs they apply to (`bid-proposal-management`, `tender-bid-writing`, `expert-review`, `compliance-virtual-assistance`, `digital-presence`). Helpers in `app/lib/helpers.php`: `faqs(?page, ?group)` and `faq_groups()`.
- `app/data/testimonials.php` (new): returns `[]` with the commented item structure (quote, name, role, company, sector slug, permission_date) and the permission rule. Helper `testimonials()`.
- `templates/components/accordion.php` (new, C17) + `src/css/components/accordion.css` + `src/js/modules/accordion.js`: native `<details>/<summary>` rows (question as `h3` by default, `level` option), a CSS plus → minus morph on a 40 px circle, GSAP height animation (0.45 s open / 0.32 s close, `apple`), `name` → one open at a time animated by the module (it lifts the native `name` while running and restores it under reduced motion, where the browser's own toggle is used); deep links `#faq-<id>` open their item; `.on-dark` variant.
- `templates/components/testimonials.php` (new, C16) + `src/css/components/testimonials.css`: quote cards (Phosphor `quotes`, blockquote, name / role, company, sector chip from `sectors()`) on the existing `marquee` effect (`data-anim="marquee"`, `[data-marquee-track]`, `.marquee__pause` button with its own labels), edge-fade mask, wrapped grid without JS or under reduced motion. Returns nothing without items.
- `templates/sections/home/triage.php` (new) + `src/css/pages/home-triage.css`: `section.triage.on-dark#help`, Navy 900 with two Azure radial glows (so the glass has something to blur), the eyebrow as the `h2`, `ul[data-anim="stagger"]` of three `article.triage-card.glass-thin-dark[data-anim="tilt" data-tilt="4"]` (hex tile, question, line, one action: primary button with the phone icon → `tel:`, glass split button → `/contact-us/#quote`, link-arrow → `/services/`). Stacked on phones, one row per card on tablets (tile · text · action), three columns on desktop.
- `templates/sections/home/testimonials.php` (new): header (`home('testimonials')`) + the component; `return`s before printing anything while `testimonials()` is empty.
- `templates/sections/home/faq.php` (new) + `src/css/pages/home-faq.css`: `section.faq#faq`, 5/7 grid on desktop with a sticky intro column (eyebrow "FAQ", `heading(2, 'Questions,', 'Answered')`, a line, split button "View all FAQs" → `/faq/`) and the accordion of `faqs('home')` with `name="home-faq"`.
- `templates/sections/home/micro-cta.php` (new): the P05 micro-CTA with its `ui('micro_cta')` copy, placed between Our Approach and Sectors (docs/08 H4).
- `templates/components/eyebrow.php`: optional `id` (and a heading `tag`) so an eyebrow can be a labelled section's heading.
- `app/data/home.php`: `triage` (eyebrow, three cards with icons `clock` / `compass` / `shield-check`, labels, URLs, action styles; the consultation label `// PROVISIONAL`), `testimonials` (header only), `faq` (eyebrow, heading, line `// PROVISIONAL`, button); the stale empty keys removed; the pending comment now covers P16 only.
- `templates/pages/home.php`: `$sections` → micro-cta, triage, testimonials, faq templates; only Insights (P16) is still a placeholder. `src/css/main.css` imports the four new stylesheets.
- `templates/pages/styleguide/index.php`: C17 accordion (light + dark) and a C16 layout sample built from obviously-sample data (local styleguide only; the live data file stays empty). `docs/06` row for the `accordion` module; CLAUDE.md pointers.

**Decisions**
- Testimonials are gated at the data, not with a flag: the section template returns before any output when `testimonials()` is empty, so nothing (no heading, no empty band) reaches the HTML. No `Review` / `AggregateRating` schema anywhere (docs/10 §6, docs/13 §7).
- The accordion keeps the native element as the source of truth: the answers are in the server HTML inside `<details>`, Enter / Space work through the summary's own click event, and `<details name>` gives one-open-at-a-time without JS. The module only adds the height tween and animates sibling closes.
- FAQ items carry `pages` tags instead of per-page copies, so `/faq/` (P27) and the service pages (P20–P22) reuse the same list; group labels live with the data for the `/faq/` headings.
- The triage eyebrow doubles as the section heading (`h2.eyebrow`), as the prompt specifies an eyebrow and cards only; the card questions are `h3`.
- The micro-CTA sits where docs/08 §4 puts it (H4, between Approach and Sectors) with the P05 default copy.

**Deviations**
- None against the prompt. The P15 marquee uses the existing `marquee` effect rather than a new slider, as docs/08 H9 asks for a marquee.

**Checks**
- `php -l` clean on all PHP files; `npm run build` clean, 0 warnings; `seo-audit --all` PASS; `check-content` 118 findings (111 + the five FAQ items, the consultation label and the FAQ line).
- No testimonials section in the HTML while the data is empty (`#testimonials` absent); FAQ answers present in the page source; the styleguide sample marquee builds 4 clones, moves, stops on the pause button and relabels it.
- Playwright 1440: the three cards reveal 0 → 1 in sequence; hovering the first card's corner gives a `matrix3d` tilt and a glare element; the accordion lifts `name`, opens item 1 by click (icon bar rotated 180°, `[open]`), opening item 2 closes item 1, Enter on the focused third summary opens it and Space closes it, inline styles cleared after each tween; the focus ring on the summary is the 2 px solid outline; tab order is the five summaries only. 360: cards stacked at 328 px, the same accordion results. 768: cards as rows 707 × 129 px. 1024: three columns 299 px. Reduced motion 1440: cards visible from the start, no tilt, `name` kept, the native toggle still opens one at a time. Document width equals the viewport at 360 / 768 / 1024 / 1440; no console errors; no failed requests on `/` or `/styleguide/`.
- Renders inspected: triage at 1440 / 768 / 360, FAQ at 1440 / 360, the micro-CTA row, the styleguide testimonials sample and dark accordion.

**Follow-ups**
- Client: confirm the free consultation (triage card 2, hero helper); approve or replace the five FAQ answers; supply permissioned testimonials (quote, name, role, company, sector, permission date) → C04 fills `app/data/testimonials.php` and the section appears.
- P16: Latest Insights + the final CTA band; P27: `/faq/` from `faq_groups()` + `faqs('faq')`; service pages: `faqs($slug)`.

---

## P16 — Home: Latest Insights, final CTA, home SEO, polish pass
**Date:** 15 October 2026
**Commit:** `P16: home insights, final CTA, SEO and polish`

**Files created / changed**
- `app/lib/Blog.php` (new): `all()` (newest first; drafts hidden in production, flagged `draft` elsewhere), `latest($n)`, `find($slug)`, `parse($file)` (spatie/yaml-front-matter: title, slug, description, date, updated, author, category → `category_slug`, image, image_alt, status, body), `displayDate()` ("18 January 2026").
- `content/blog/2026-01-18-health-and-social-care-tender-vocabulary-explained.md` (new): front matter only, `status: draft`, the body is a `[[PENDING]]` marker for P26.
- `templates/components/card-blog.php` (new, C10) + `src/css/components/card-blog.css`: white surface (radius 24) with the image inset (radius 20); the date tab sits in a true-mask hole at the image's bottom-left (`.notch--bl` on the image wrapper, the `<time>` is a sibling so the mask does not hide it); the arrow button sits in a concave hole at the surface's bottom-right (`.notch--br` on the surface, the button a sibling outside the mask); title clamped to two lines; meta row = author uppercase + outlined category chip (+ a "Draft" chip off production); hover = image 1.06, arrow −45° → 0° and Azure fill; one stretched link per card; `soon` mode for the "More insights coming soon" card (LinkedIn hex tile, `rel="noopener"`).
- `templates/sections/home/insights.php` (new) + `src/css/pages/home-insights.css`: `heading(2, 'Latest', 'Insights')`, "View all insights" link-arrow → `/insights/`, `stagger` grid of `App\Blog::latest(3)`; with fewer than three posts the coming-soon card spans the remaining columns (`.insights__soon--span-N`).
- `templates/sections/home/cta.php` (new): the C21 band with the default copy, WhatsApp button and the `cta-mark` rise-rotate; `home('cta')` holds `show_whatsapp` and any C01 overrides.
- `templates/sections/home/hero.php`: the mini card shows the newest post (title, URL, image, alt); the data's mini entry is the fallback while `content/blog` is empty.
- `templates/pages/home.php`: title `Tender Writing & Bid Management Services UK | My Solutionz` (58), the P16 description built on `config('experience')`, `og_title` "Tenders, Crafted for Success" from the hero data, `og_image` → `public/assets/og/home.jpg` when it exists (else the default card), `primary_image`, `web_page_type`, `status: draft`; the section map with four C29 dividers; the placeholder branch is gone (`.home-placeholder` CSS removed).
- `scripts/og.mjs`: also writes `public/assets/og/home.jpg` (1200×630 cover crop, sharp `attention` focus) from `assets-src/images/home/hero-building.*` when the client's photo exists.
- `scripts/art.mjs`: now writes three files: `grid-wave.svg` (unchanged), `skyscraper.svg` (26 converging mullions + 22 floor lines, `<g id="skyscraper">` for `<use>`, colours via `--sk-v/--sk-h`) and `swirl.svg` (nine S-curves, standalone for inlining, `currentColor`).
- `templates/sections/home/approach.php` + `home-approach.css`: the skyscraper wash behind the section at 8 % with `parallax` 0.92 (section `overflow: hidden; isolation: isolate`).
- `templates/components/process-steps.php` + `process-steps.css`: optional `art => 'swirl'` inlines the swirl with `data-anim="draw"` behind the content (Sky 300 at .22 on navy); the home process section uses it.
- **Follow header:** `templates/partials/header.php` gained `follow => true` (prints the `<header>` only, `hf-` logo ids, `data-follow`); `templates/layouts/base.php` prints it on pages whose hero renders the `hero` variant; `src/js/modules/header.js` parks it at `yPercent: −100` until the hero's nav has scrolled out (`.is-following`), then applies the usual compact-glass / hide-on-down / show-on-up rules and the theme watcher; the mobile sheet now accepts several burgers (focus returns to the one that opened it); `src/js/modules/splash.js` flips onto the hero header's logo explicitly; `header.css` keeps the follow header `visibility: hidden` until it follows.
- `data-header-theme` on every home section (light: services, approach, micro-CTA, sectors, why, testimonials, FAQ, insights; dark: process, triage, CTA band in the component itself).
- `app/data/home.php`: `insights` (eyebrow, heading, link, coming-soon copy `// PROVISIONAL`), `cta`; `src/css/main.css` imports; `docs/06` stagger note (children lose inline styles on finish) and the follow-header / C10 pointers in CLAUDE.md.
- **Hero slider controls (P09 bug found by Lighthouse `target-size`):** below ~466px of slider width the prev / next / pause buttons were squeezed to 0–5px (360, 412) or 37px (1024). `home.css`: `.hero__ctrl { flex: none }`, the controls are a `container-type: inline-size` box and `@container (max-width: 465.98px)` folds the dots into a two-row grid between prev and next (`--dot-cols` = half the cards, printed by `hero.php`); spacing unchanged at 768 / 1440, DOM and focus order unchanged.
- **`src/js/anim/two-voice.js`:** the heading gets `overflow-x: clip` until its reveal finishes (cleared by `finish()`): the accent's `x: 24` pre-state pushed the process heading 5px past the viewport at 412 / 414 (sideways scroll). The visible part never passes its final edge, so nothing visible is clipped; reduced motion never sets it.
- Generated photography (entry below) committed with P16: sources + prompts in `assets-src/images/`, renditions + manifest in `public/assets/img/`, `public/assets/og/home.jpg`; sector alt text for transport and facilities management corrected to what the photos show.

**Decisions**
- The home page had no sticky header after the hero (the `hero` nav variant scrolls away with the panel), so "the sticky header adapts" needed one: the follow header reuses the default variant unchanged and only appears once the hero nav is gone, so the hero composition from docs/05 is untouched and inner pages are unaffected.
- The photographic OG crop is generated by `npm run og` only when `assets-src/images/home/hero-building.*` exists, otherwise `og:image` and `primaryImageOfPage` fall back to the brand card. The (generated) hero photo now exists, so both point to `/assets/og/home.jpg`.
- Lighthouse is measured on the production build behind a small local proxy that applies the `.htaccess` Brotli/gzip and `Cache-Control` rules (`php -S` sends neither, which alone cost ~30 points); the proxy is a scratch tool, not part of the repo.
- Divider placement: wave canvas → navy before Process, diagonal navy → canvas after it, flipped diagonal canvas → navy before the triage row, flipped wave navy → white (or canvas when testimonials exist) after it. No divider into the CTA band (its gradient top edge has no single colour).
- The swirl is inlined (DrawSVG needs real paths) while the skyscraper is a `<use>` (static wash); both come from `npm run art`, which already runs before every build.
- The coming-soon card's span is a class, not an inline custom property: the stagger effect clears inline styles when it finishes (documented in docs/06).
- The blog card's two cut-outs are true masks (docs/04 §1.5); because a mask also clips what sits inside the hole, the date tab and the arrow button are siblings of the masked element.

**Deviations**
- **Lighthouse mobile performance ≥ 90 is not reliably met locally** (see Checks). Accessibility 100 and SEO (noindex only) are met. This PC benchmarks below what Lighthouse expects (index 566–966, "slower CPU" warning on every run), so the default 4× CPU slowdown inflates TBT; with the documented calibration (2×) TBT is 120–190ms and the score 76–93. What remains is LCP / Speed Index: the LCP element is now the hero photo and its *element render delay* (240–1,125ms observed) is the splash + hero intro holding the panel back (locked choreography, docs/05 + docs/07). Left for P31 with the real host + PageSpeed Insights, as P07/P09 already noted.

**Checks**
- `php -l` clean on every PHP file; `npm run build` clean, 0 warnings (`npm run art` + `npm run og` ran); `seo-audit --all` PASS for `/`; `check-content` 115 (the only missing image keys are the four styleguide demos; + the coming-soon line and the post body marker).
- HTML: title 58 chars, description 150, `og:title` "Tenders, Crafted for Success", `primaryImageOfPage` in the graph, no PHP notices; the hero mini card links to `/insights/health-and-social-care-tender-vocabulary-explained/`.
- Playwright 1440: header theme reads `light` over services / approach / why / FAQ / insights and `dark` over process / triage / CTA; the follow header is `visibility: hidden` at the top (first tab stop = the hero logo), appears at `top: 0` on scroll-up after the hero and hides at −68 on scroll-down; the blog card hover rotates the arrow to 0° with the Azure fill and scales the image 1.06; the coming-soon card spans two columns (845 px); the swirl paths go from `stroke-dasharray 0` to fully drawn as the process section enters; the approach art sits at opacity .08 with a parallax offset; the CTA mark transform changes with scroll; four dividers in the expected order. 360: cards stacked at 328 px; the follow header's burger opens the sheet (both burgers `aria-expanded`), Esc closes it and focus returns to the follow burger. 768 / 1024: two / three columns. Reduced motion: no tilt, no zoom, swirl fully drawn, art untransformed. Document width equals the viewport at 360 / 768 / 1024 / 1440; no console errors; no failed requests.
- Full-page overview at 1440 (reduced motion) reviewed for rhythm and dividers.
- Hero controls after the fix: buttons 44 × 44 and dots 24 × 44 at 360 / 412 / 768 / 1024 / 1440 (two dot rows at 360, 412, 1024); Tab order prev → 12 dots → next → pause with a visible ring, also under reduced motion. Document width equals the viewport at 360, 375, 390, 412, 414, 430, 768, 1024, 1280, 1440; the process heading's clip is gone (no inline style left) once it has revealed.
- Lighthouse 13.5 (mobile, production build `VITE_DEV=false` + the `.htaccess`-equivalent proxy, splash on, 3 runs each): default throttling: performance 41 / 59 / 47 (an earlier set on the same build gave 78 / 59 / 68: run-to-run CPU noise; without compression: 46), **accessibility 100** (was 97: `target-size`), best practices 100, SEO 69 = `is-crawlable` only (draft noindex, ignored per the prompt). Calibrated CPU 2×: performance 79 / 76 / 93, FCP 1.4–2.2s, LCP 2.4–4.2s (hero photo), TBT 120–190ms, CLS 0.005.

**Follow-ups**
- Client: approval of the home title / description / OG title (C01) and the coming-soon line; confirm the AI-generated photography or supply real / licensed photos (docs/02 §5 asks for real company photos or licensed stock, Q19); replacing a source in `assets-src/images/` + `npm run images` + `npm run og` swaps it everywhere.
- P25/P26: Blog listing, article template, categories, RSS; migrate the post body (originality + Procurement Act 2023 update).
- P30: validate the home graph in the Rich Results Test; P31: performance pass against the Lighthouse targets, on the host: hero LCP render delay under the splash / intro, render-blocking CSS (139KB raw), unused JS (~89KB) and CSS (~58KB), main-bundle start-up after `splash:done` (SplitText, hero slider, Flip).

---

## Homepage generated photography — 15 October 2026

- Generated 10 professional editorial photographs sequentially with the built-in image_gen tool at the client's request. Originals are under assets-src/images/home, services and sectors, with descriptive scene-based filenames. Full prompts and the file-to-section mapping are saved in assets-src/images/GENERATION-PROMPTS.json and README.md.
- Connected the hero, three service cards, approach image, decorative experience numeral, seven sectors, insight thumbnail/card and all ten decorative process panels. Reused relevant images across sections so the set stays at exactly 10 unique originals. Updated alt text to describe the scenes; generated people and buildings are illustrative, not actual staff or premises.
- Responsive pipeline generated 96 AVIF/WebP/JPEG renditions from the 10 originals. Generated the photographic homepage OG image. Kept existing lazy loading, dimensions, srcsets and high-priority hero loading.
- Validation: PHP syntax checks passed; npm run build passed; homepage HTTP 200; all homepage image references exist; 24 picture elements and zero data-pending-image markers; sample WebP for each of the 10 originals served successfully; SEO audit passed for all three current pages.
- Content checker retains unrelated provisional copy/route findings and four missing demo image keys used only in the local styleguides. Browser visual inspection was unavailable because browser access was rejected by the browser security policy; source mappings, generated images and HTTP assets were checked instead.

---

## Client change — Figtree everywhere, 14px paragraphs, hero badge removed
**Date:** 15 October 2026

**Client instruction:** remove the hero badge pill ("UK Bid & Proposal Management"); one simple font everywhere, Figtree, including the italic accent words; paragraphs 14px with the headings scaled to match. Confirmed in chat: badge only (section eyebrows stay), accent words upright Figtree in the brand blue, Figtree downloaded from npm and self-hosted.

**Files created / changed**
- `docs/00-LOCKED-DECISIONS.md`: L11 (Figtree only, 14px paragraphs), L15 (accent words upright, colour only), L18 (no badge) amended with the date; `docs/02` §3 rewritten (family, scale table, rules); `docs/03` C01/C02/C06, `docs/04` §1.0 and `docs/05` (badge row + 0.35 timeline step struck through) marked as superseded; CLAUDE.md `npm run fonts` line.
- `package.json`: + `@fontsource-variable/figtree` 5.3.0 (OFL); − `@fontsource-variable/mona-sans`, `@fontsource-variable/google-sans`, `@fontsource/playfair-display` (Khand stays: logo build only).
- `scripts/fonts.py` → `public/assets/fonts/figtree-var.woff2` (Latin subset, wght 400–700, 18 KB; was 89 KB across three files, now deleted); `src/css/fonts.css` = Figtree + metric-matched Arial fallback (101.52 % / 93.57 % / 24.62 %); `templates/partials/head-seo.php` preloads the one file.
- `src/css/tokens.css`: `--font-sans` (Figtree) with `--font-display` / `--font-body` as aliases, `--font-accent` removed; scale from 14px: display 36→56, h1 32→44, h2 26→34, h3 20→24, h4 16→18, body 14, small 13, eyebrow 12, new `--fs-ui` 14 / `--fs-ui-lg` 16; `--fs-lead` 16 is now UI-only (FAQ questions, large buttons, quotes); headings weight 600, looser line-heights for Figtree.
- `src/css/base.css`: `h* em` = inherit family/weight, `font-style: normal`, Azure 600 (Sky 300 on dark, gradient on `.display` only); `.lead` uses the body size. Paragraph classes on 14px: hero lead + helper, approach text, CTA band text, sector card summaries.
- Hard-coded UI sizes moved onto tokens: nav links, header phone, mega-menu items, mobile menu links (`--fs-h3`), hero card title/chip, Quick-Start labels/button, mini card, chips; the footer wordmark and display marquee scaled down to match (decorative numerals unchanged).
- Hero: badge markup, `badge` data string, its CSS (incl. the gradient defs used only by it) and the 0.35s `back.out` tween removed (`hero.php`, `app/data/home.php`, `home.css`, `hero-intro.js`); `--hero-title-size` = `--fs-display`, breakpoint overrides removed.
- `scripts/og.mjs`: SVG text family → Figtree (renders with the Arial fallback, as before); styleguide type copy + gradient sample.

**Checks**
- Every element on `/` and `/styleguide/` computes to Figtree (the only loaded face: Figtree 400–700); hero H1 56px at 1440 / 36px at 360, accent upright Sky 300 on dark, h2 accent Azure 600 on light; every paragraph 14px except the footer legal line and the hours label (13px captions).
- `php -l` clean; `npm run build` clean; document width = viewport at 360 / 412 / 768 / 1024 / 1440; no console errors or failed requests; `seo-audit --all` PASS; `check-content` 114 (badge marker gone); Lighthouse accessibility 100.

**Follow-ups**
- PROMPTS.md still describes the old fonts / italic accent in later prompts (P17+): L11/L15/L18 in docs/00 take precedence.

---

## Client change — custom dropdowns (C31 Select) for the Quick-Start bar
**Date:** 15 October 2026

**Client instruction:** the native `<select>` popup (browser default list) looks unprofessional; use a custom dropdown.

**Files created / changed**
- `src/js/modules/select.js` (new, lazy `data-module="select"`): WAI-ARIA APG select-only combobox over the native `<select>`: a focusable `role="combobox"` shows the value (`aria-labelledby` = the field label, `aria-activedescendant` for the active option), a `role="listbox"` popup lists the options (optgroups → `role="group"` with an eyebrow heading; `data-icon` → Phosphor light icon from `data-sprite`; `data-colour` → category tint; check icon on the selected option). Keyboard: ↓ ↑ Enter Space open; ↓ ↑ Home End PageUp PageDown move; Enter Space Tab Alt+↑ choose; Esc closes; type-ahead (buffer cleared on close). Mouse: click / hover / outside click / label click. The popup is a manual popover (top layer: the hero panel's clip, overflow and the intro/parallax transforms cannot cut it off; no-popover browsers get a fixed element on `<body>`), opens below the `[data-select-anchor]` field or above when there is more room, max 360px with its own scroll (`data-lenis-prevent`), follows the page scroll and closes when the field leaves the viewport. The native `<select>` stays in the form (visually hidden, `tabindex=-1`, `aria-hidden`) and receives `input`/`change` events; reset, bfcache restore and `invalid` are handled; without JS it is the visible control.
- `src/css/components/select.css` (new, imported by `main.css`): white popup, radius 16, Shadow 3, 8px padding, options 40px (44px on coarse pointers), active = Azure 500 at 8 %, selected = Azure 700 + check, hairlines between groups, `@starting-style` fade/slide in, reduced-motion and forced-colours rules.
- `templates/sections/home/hero.php`: `data-select-anchor` on both fields, `data-module="select"` + `data-sprite` on both controls, `data-icon` on every option (sectors / sub-services from the data, `squares-four` for "Any", `hexagon` for "Other") and `data-icon` + `data-colour` on the service optgroups. `home.css`: the caret turns 180° and Azure while open.
- `docs/03` C31, CLAUDE.md pointer.

**Checks**
- Playwright 1440 + 360: click opens (below at 1440; above at 360 for the service list, where there is more room), hover highlights, click chooses and returns focus; ↓ / End / type-ahead ("s" → Security, "tr" → Transport) / Enter / Esc / Tab behave as in the APG pattern; outside click closes; the label opens it; the form submits `?sector=transport&service=care-plans`; the wheel over the list scrolls only the list, a page scroll moves the list with its field (scroll position kept) and closes it once the field leaves the viewport. No console errors.
- `npm run build` clean (`select` chunk 2.6 KB gzip); Lighthouse accessibility 100.

---

## Client fix — mega menu / sectors panel glass (text behind showing through)
**Date:** 15 October 2026

**Problem:** over the dark process section the Services mega menu showed the page text behind it sharp and readable, clashing with the menu text. Cause: the compact header put its own `backdrop-filter` on `.site-header`, which makes the header the *backdrop root* of everything inside it, so the panels' `backdrop-filter` could only blur the header, never the page; the 72 % navy tint alone let the text through.

**Fix**
- `src/css/components/header.css`: the compact glass bar (dark + light, the no-`backdrop-filter` and reduced-transparency fallbacks) moved from `.site-header` onto `.site-header::before` (absolute, `z-index: -1`, fades in with `.is-scrolled`), the same technique `.glass-nav::before` already used. The header itself has no backdrop-filter any more, so the panels blur the page.
- `src/css/tokens.css`: `--glass-menu-dark-bg` (navy 900 at 90 %) and `--glass-menu-dark-blur` (32px, saturate 170 %). `src/css/components/mega-menu.css`: `.menu.glass-thick-dark` uses them plus a soft white top sheen; solid navy without backdrop-filter support or under `prefers-reduced-transparency`.

**Checks**
- Playwright 1440: follow header over the process section → Services mega menu: the steps and photo behind are blurred to a soft navy wash, all labels readable; over the light Why section → Sectors dropdown frosted and readable; the header's glass is on `::before` (`blur(24px)` dark / `blur(20px)` light), the header element has `backdrop-filter: none`. `npm run build` clean.

---

## Client fix — hero header menus not opening (duplicate ids)
**Date:** 15 October 2026

**Problem:** on the home hero, Services / Sectors flipped their caret (`aria-expanded="true"`) but no panel appeared. Cause (P16 regression): the follow header and the hero header both printed `id="menu-services"` / `id="menu-sectors"`; the follow header comes first in the DOM, so `header.js` (`getElementById(aria-controls)`) opened the follow header's panel, which is hidden while the hero is on screen.

**Fix**
- `templates/partials/header.php`: menu ids and `aria-controls` come from `$menuId()`, which prefixes `hf-` in the follow header (as its logo ids already were); the page has no duplicate ids any more.
- `src/css/components/mega-menu.css`: the menu link colours are set at two-class specificity (`.menu .menu__sub`, `.menu .menu__cat`) so the hero panel's `.on-dark a` (Sky) no longer turns the item names blue inside the hero.

**Checks**
- Playwright 1440: hover on Services / Sectors in the hero header opens the frosted panels (white names, Navy 200 summaries); Enter on the toggle opens, Esc closes and keeps focus on the toggle; the follow header's menus still open over the process section; no duplicate ids on `/`; `npm run build` clean.

---

## Client content — Bid & Proposal Management services copy
**Date:** 15 October 2026

**Client input:** an updated `My Solutionz - Website content.docx`. The About text is unchanged; a new *Our Services* part adds the final copy for **Bid & Proposal Management** (category page) and its 3 sub-services **Tender & Bid Writing**, **Opportunity Identification** and **Expert Review**. None of these pages is built yet (P20/P21), so the copy goes into the content docs and the prompts.

**Files created / changed**
- `docs/website_content/My Solutionz - Website content.docx` replaced by the new version (a superset of the old one).
- `docs/website_content/services/01-bid-proposal-management.md` rewritten from the client copy (replaces our 13 Oct draft: the draft's benefits bento, personas, Procurement Act checklist and 4 "why" points are dropped; its 8 FAQs stay as provisional). New `01a-tender-bid-writing.md`, `01b-opportunity-identification.md`, `01c-expert-review.md`. Each has the client copy verbatim mapped to page blocks, an SEO pack (titles 46–52 chars, descriptions 141–159), schema, internal links, icons, layout/motion table and client notes. A script check confirmed every client sentence is present word for word.
- "Over 16 years" appears 5 times in the new services copy: written as "over 10 years of business development experience" everywhere (L05c).
- Client gaps filled with `PROVISIONAL` copy: one-line descriptions for the 6 "How We Manage Your Bids" steps (headings only in the docx), the sub-service cards heading/intro, the category FAQs; Tender & Bid Writing uses the default CTA label.
- `app/data/services.php`: the 3 Bid & Proposal sub-service one-liners (mega menu, hero slider, home services cards, llms.txt) condensed from the client's page taglines, still `// PROVISIONAL`.
- `PROMPTS.md`: P20 and P21 rewritten around the client's structure (typed `sections[]` blocks shared by the category and sub-service templates, `rich()` helper for the client's bold + internal links, 6-step process, CTA band `sub`/paragraphs, template name `single.php` as in `app/routes.php`, new icons listed); P21 publishes the 3 sub-services, so **C03a is absorbed into P21**; status table + header updated.
- `docs/00` P06, `docs/08` §7a/§7b (dated notes on the client structure), `docs/09` §1–§4 (4 of 15 service pages final), `docs/15` Q03, Q06b ((c) private sector confirmed), new Q06c (provisional gaps for the client).

**Follow-ups**
- Client: approve or replace the Q06c items (step descriptions, subs intro, FAQs, CTA label, one-liners); free quote + portal uploads (Q06b).
- Next service content: Category 2 (Compliance & Virtual Assistance) in the same shape.

---

## P17 — Inner-page hero component
**Date:** 15 October 2026

**Files created**
- `templates/components/hero-inner.php` (C32): one component, 4 variants. Parameters: `variant`, `breadcrumbs`, `eyebrow`, `title`, `accent`, `sub`, `lead`, `ctas`, `trust`, `images` (+ `focus`), `labels`, `icon`, `icon_label`, `colour`, `parent`, `chip`, `meta`, `image`, `id`, `level`, `class`.
  - `columns`: Navy 900 hero-gradient panel, bottom-left radius `--r-section` (40 → 80px), skyscraper line art at 9 %, copy left (white breadcrumbs, eyebrow, two-voice H1, lead, CTAs), 3 image columns right (wide one `--r-section`, two narrow true pills), up to 3 floating labels (icon tile + text) overlapping the edges.
  - `category`: same panel, category glow (`--hi-glow`), category chip eyebrow, large hex tile in the glowing frame (`.special-frame--hug`), sub line + trust line; with `images` the framed tile floats over the wide column; `parent` prints the "Part of …" chip linking up.
  - `simple`: canvas, breadcrumbs + H1 (h1 size) + lead, hairline below. `article`: canvas, chip, H1, lead, meta row (`<time>`), 16:9 featured image (priority).
- `src/css/components/hero-inner.css` (imported after `special-frame.css`): layout, the 3 breakpoints (≥ 1024 two columns · 768–1023 stacked with the columns kept · < 768 one 4:3 image with the labels as a wrapping row below), entrance pre-states under `html.js` + no-preference with a 3s safety.
- `src/js/modules/hero-inner.js` (`data-module="hero-inner"`, 1.15 KB gzip): entrance after `splash:done` (breadcrumbs/eyebrow, wide image 1.12 → 1, narrow pills clip up, H1 lines in SplitText masks, sub/lead/CTAs/trust, framed card rises, frame draws then orbits, labels float in with `back.out`), 4s safety; parallax ≥ 1024 only from each `[data-speed]` (columns 0.9/1/1.1, labels 1.1/1/0.9, framed tile). Reduced motion: no entrance, no parallax, resting frame.
- `templates/pages/styleguide/heroes.php` + route `/styleguide/heroes/` (local only; linked from the styleguide nav): the 4 variants + category-with-columns, using the real About, Bid & Proposal, Expert Review and article copy.

**Files changed**
- `src/css/tokens.css`: `--r-section`. `src/css/components/special-frame.css`: `.special-frame--hug`. `src/icons.json` (+ sprite): regular `calendar-blank`, `user`. `src/css/pages/styleguide.css`: demo label bands. `docs/03` C32 row, CLAUDE.md pointer.

**Decisions / deviations**
- Extra parameters beyond the prompt's list (`sub`, `trust`, `parent`, `icon_label`, `chip`, `meta`, `image`, `level`, `focus`) so P20/P21 (client sub-headings + trust line), P26 (article) and the demo page need no component changes.
- The demo lives on its own local page because full-width heroes need the overlay header; only its first hero is an H1 (`level` 2 for the rest).
- Parallax runs inside the module (≥ 1024 only) from the `data-speed` attributes instead of `data-anim="parallax"`, so the labels do not drift apart in the mobile row.
- The wide column is the LCP image: it is never clipped or faded (scale only); only the narrow pills clip in. Same for the article image.
- Labels and the framed card use a denser navy glass (`navy-900` at 74 %) instead of plain `glass-thin-dark`: white text over light photos was too faint; solid under reduced transparency.

**Checks**
- `php -l` clean; `npm run build` clean (`hero-inner` chunk 2.47 KB); `/styleguide/heroes/` HTTP 200, no PHP notices.
- Playwright at 1440 / 1024 / 768 / 360: no console errors or 4xx, no horizontal scroll, nothing left hidden after the entrance, frames drawn and orbiting, one H1; reduced motion at 1440 + 360: everything visible, no intro; keyboard: every hero link/button reachable with a visible focus ring; parallax measured (columns lag/lead by 0.1 × scroll).
- `seo-audit --all` PASS (4 pages); `check-content` 115 (+1 = the new draft styleguide page).

**Follow-ups**
- P18/P19 (About), P20/P21 (services), P22 (sectors), P26 (articles), P27–P29 call this component; inner pages need real photos for the columns (placeholders until then).

---

## P18 — About page part 1: About Us + Our Story
**Date:** 15 October 2026

**Files created**
- `app/data/about.php` + `about()` helper: SEO pack, hero, sub-nav labels, About Us (5 paragraphs) and Our Story (8 paragraphs + closing statement), verbatim from `about-us-content.md` ("over 10 years", L05c). Each section's eyebrow is the client's section name, the two-voice H2 its sub-heading.
- `templates/pages/about.php`: `$page` (title 50 chars, description 152, `AboutPage` + `BreadcrumbList`, breadcrumbs Home › About Us, **published**, primary image = the hero photo), hero-inner `columns` (labels Bid Strategy / Compliance / Evidence-Led Writing), C34 sub-nav, then the sections by anchor id. P19 appends its sections to the same list.
- `templates/sections/about/intro.php` (A1 `#about-us`): two-voice H2, the 5 paragraphs (the first in ink, medium weight), sticky notched image (`.notch--tr`) with the Office Hours card in the cut-out, sector chips row linking to the 7 sector pages.
- `templates/sections/about/story.php` (A2 `#our-story`): swirl lines drawn behind the header, sticky photo column that crossfades per paragraph (`modules/about-story.js`, ≥ 1024), the 8 paragraphs with `read-along` on "We have never believed in taking a standard approach to every tender." and on the last paragraph, and the closing statement large in Azure 650 with a Phosphor quote mark that draws itself.
- `templates/components/subnav.php` + `components/subnav.css` + `modules/subnav.js` (C34): sticky glass pill bar, scrollspy with `aria-current` + sliding navy pill, horizontal scroll on small screens.
- `templates/components/hours-card.php` + `components/hours-card.css` (C33): the P10 Office Hours card as a component (copy `ui('hours_card')`); the home approach section now uses it (its CSS keeps only the positioning).
- `src/css/pages/about.css`.

**Files changed**
- `src/js/core/motion.js`: `alignHash()`: when a page opens with a `#hash`, re-align on the target after load and after every ScrollTrigger refresh for 3 s (until the reader scrolls). **Fixes a P06 bug**: `/#approach`, `/styleguide/motion/#parallax` and `/about-us/#our-story` used to stay at the top (the native jump happened before the layout settled and ScrollTrigger's refresh reset it).
- `src/js/modules/header.js`: toggles `html.is-header-hidden` so sticky bars can follow the header.
- `templates/sections/home/approach.php`, `src/css/pages/home-approach.css`, `app/data/ui.php`, `app/lib/helpers.php`, `src/css/main.css`, `docs/03` (C33, C34), CLAUDE.md.

**Decisions / deviations**
- L11 over the prompt: paragraphs stay 14px (the prompt's "first paragraph as `--fs-lead`" predates the Figtree change), and the pull quote is upright Figtree in the brand blue, not Playfair italic. The quote is the client's own closing line, so it is a styled statement, not a `<blockquote>`.
- The sub-nav lists only sections the page renders (About Us, Our Story for now), so it never links to a missing anchor; P19's sections appear in it automatically.
- Photos: the generated editorial set is reused (hero: consultancy meeting + building + care; About Us: planning session; Our Story: building → care → facilities) until real team/office photos arrive (Q19).
- Sector chips link to the sector pages, which are still drafts until P22 (the home page already links to them).

**Checks**
- `php -l` clean; `npm run build` clean (`subnav` 0.72 KB gzip, `about-story` 0.27 KB); every About Us + Our Story paragraph and both headings present verbatim in the server HTML, no "16 years"; JSON-LD `AboutPage` + `BreadcrumbList`.
- Playwright 1440 / 1024 / 768 / 360 (+ reduced motion at 1440 / 360): no console errors or 4xx, no horizontal scroll, one H1; while wheel-scrolling the current link follows the sections (About Us → Our Story), the pill sits exactly under it, the bar sticks under the header and slides up with it, the story photo changes 0 → 1 → 2; `/about-us/#our-story` opened from the home page lands just below the bar (148px at 1440). Keyboard: hero links, CTAs and sub-nav links all show the focus ring. Home approach section unchanged.
- `seo-audit --all` PASS (5 pages, incl. `/about-us/`); `check-content` 114 (−1: `/about-us/` has its template now).

**Follow-ups**
- P19: Our Approach, Why Work With Us?, Mission & Vision, Team (hidden while empty), CTA band.
- Real team / office photos for the About hero and sections (Q19); a photographic OG crop for `/about-us/` in P30.

---

## P19 — About page part 2: Approach, Why Us, Mission & Vision
**Date:** 15 October 2026

**Files created**
- `templates/sections/about/approach.php` (A3 `#our-approach`): eyebrow + two-voice H2 + the 2 intro paragraphs; the C15 process component in its `full` variant (5 steps, both client paragraphs each, pinned ≥ 1024 at 0.8 × 60vh per step); closing band on Navy 900 with H3 "Every Bid Deserves a *Well-Considered Approach*" and the 2 closing paragraphs (the aim line in Sky 300).
- `templates/sections/about/why.php` (A4 `#why-work-with-us`): H2 + 2 intro paragraphs; the 7 reasons as `stack-cards` (number 01–07, large hex icon from P14, H3, the full paragraph; white / mist alternating); closing line large and centred with `read-along`.
- `templates/sections/about/mission.php` (A5 `#mission-vision`): H2; Mission card (white, `compass`, 3 paragraphs) and Vision card (Azure 650 → Navy 700, white, `binoculars`) side by side over the azure ribbon loops (`public/assets/img/art/ribbon.svg`, new in `scripts/art.mjs`, inlined and drawn on scroll); closing statement centred in Azure 650.
- `templates/sections/about/team.php` (A6): absent while `team()` is empty. `templates/sections/about/cta.php` (A7): the default CTA band with WhatsApp.
- `templates/components/team-cards.php` + `components/team-cards.css` + `modules/team-cards.js` (C11), `app/data/team.php` (`[]`) + `team()` helper; a placeholder demo on `/styleguide/` (no real people).
- `app/data/about.php`: `approach`, `why`, `mission`, `team` copy, verbatim from `about-us-content.md` §3–§5.

**Files changed**
- `templates/pages/about.php`: the section list is now the full locked order (About Us → Our Story → Our Approach → Why Work With Us? → Our Mission & Vision → Team → CTA); the sub-nav shows the five content sections. `src/css/pages/about.css` (+ P19 sections), `src/css/main.css`, `scripts/art.mjs` (ribbon), `docs/03` C11, CLAUDE.md.

**Decisions / deviations**
- The `full` process is pinned, so while pinned only the active step shows its paragraphs (CSS, desktop + motion only): five full steps would not fit a 1024 × 800 viewport otherwise (the block now measures exactly the viewport height at 1024 and 1440). Under reduced motion, on mobile and without JS every paragraph is visible.
- The stacking cards stay opaque (`opacity: 1 !important` under `html.js`): the shared `stack-cards` effect dims a covered card, and the card beneath showed through a half-dimmed one.
- L11/L15 over the prompt: the closing statement is upright Figtree in the brand blue, not Playfair italic.
- The team share popover is positioned by JS (viewport coordinates) rather than CSS anchor positioning, which this Chromium does not support; it follows the button on scroll and closes once the button leaves the viewport.

**Checks**
- `php -l` clean; `npm run build` clean (`team-cards` chunk 0.93 KB gzip); all 47 lines of `about-us-content.md` §3–§5 present verbatim in the server HTML, no "16 years"; `seo-audit --all` PASS (5 pages); `check-content` 114 (unchanged).
- Playwright 1440 / 1024 / 768 / 360 (+ reduced motion 1440 / 360): no console errors or 4xx, no horizontal scroll, one H1; the sub-nav follows all five sections; the pinned process block is 900 / 800 px tall at 1440 / 1024 (= the viewport), the stacking cards stack at header + sub-nav + 16 px and scale to 0.94; the About hero anchor still lands at 148 px. Team demo at 1440 / 360: next button scrolls one card, dots follow the page, the popover opens under its button, Esc closes it.

**Follow-ups**
- Team members, photos and LinkedIn links from the client (Q08); real About photos (Q19).
- P20 next: services hub + category template + the Bid & Proposal page from the client's copy.

---

## P20 — Services hub + category landing template + 3 category pages
**Date:** 16 October 2026
**Commit:** `P20: services hub, category template and Bid & Proposal page`

**Files created**
- `app/data/service-pages/bid-proposal-management.php`: the client's final copy from `01-bid-proposal-management.md`, verbatim and in its order: SEO pack (title 52, description 155, OG title/description/image + alt, `serviceType` "Bid management"), hero (eyebrow, H1 "Bid &" + *Proposal Management*, sub, lead, CTAs, trust line, kanban icon, 3 photo columns + labels Bid Strategy / Compliance / Evidence-Led Writing), `sections[]` = intro (2 paras) → text "What Is …?" (6 paras + notched photo) → steps "How We Manage Your Bids" (3 paras + 6 steps; client titles, one-line descriptions `// PROVISIONAL`) → subs (heading + intro `// PROVISIONAL`, card text = the client's sub-page taglines) → text `mist` "Why Choose …" (3 paras + split button → `/about-us/#our-approach`) → text `band` "Bid Management for Public and Private Sector Contracts" (6 paras, the 7 sector names linked, `updated` 2026-10-15); FAQ intro; the client's closing CTA (heading, sub, 2 paras, bold line, default "Get a Free Quote" label). **Published.**
- `app/data/service-pages/compliance-virtual-assistance.php`, `digital-presence.php`: the same block sequence, hero lead = the `services.php` summary, sub-service cards from `services.php`, every other block a `[[PENDING: …]]` marker naming the copy needed; titles as the prompt; descriptions `[[PENDING]]`. **Draft.**
- `app/data/service-pages/_hub.php` + `templates/pages/services/index.php` (`/services/`): hero-inner `columns`; the 3 category panels (category colour band, hex tile, number, count chip, summary, sub-service hex links, "Explore …" split button); "How we work" (the five approach steps from `home('process.steps')`, a 5-up strip with a connecting line, link to `/about-us/#our-approach`); sectors chip marquee (links, pause button, static wrapped row under reduced motion / no JS); FAQ (items tagged `services`); default CTA band. Schema: WebPage + BreadcrumbList + ItemList of the categories. Title 52, description 142. **Published.**
- `templates/pages/services/category.php`: one template for the three categories (hero-inner `category` with the category colour → the data's blocks → FAQ by `faq_tag` → CTA band prefilled `?service=<slug>#quote`); OG = the photographic crop when it exists; schema `serviceCategory()` with `hasOfferCatalog` (sub-service name, URL, tagline/summary).
- `templates/sections/service/{intro,text,steps,subs,list,why,faq,pending}.php` + `src/css/pages/service.css` (C35) and `src/css/pages/services-hub.css`.
- `templates/pages/styleguide/service-blocks.php` + route `/styleguide/service-blocks/` (local only, linked from the styleguide nav): `list` and `why` with the client's Tender & Bid Writing copy (P21 preview) and the pending block.

**Files changed**
- `app/lib/helpers.php`: `service_page()`, `pending_note()`, `rich()` (escape first, then `**…**` → `<strong>`, `[label](/path/)` → `<a href="url(path)">`, root-relative paths only).
- `app/data/faqs.php`: the 3 missing Bid & Proposal FAQs (bid writing vs bid management, notice before a deadline, private sector), all `// PROVISIONAL`; items ordered as in the client doc; `services` tag on four general questions for the hub. The home teaser keeps its five items (order now: never tendered, cost, guarantee, confidentiality, after submission).
- `app/lib/Schema.php`: `serviceCategory()` takes `serviceType` and adds `audience` (BusinessAudience).
- `templates/components/cta-band.php` + `cta-band.css`: optional `sub`, `text` as a string or a list of paragraphs, `strong` (bold closing line); `.cta-band--long` widens the copy column; the default band is unchanged.
- `templates/components/process-steps.php`: no eyebrow/H2 when `heading` is empty (the service steps block prints its own heading, sub line and intro above the pinned list).
- `src/css/components/hero-inner.css`: with image columns in the `category` variant, label 3 moves to the bottom-right (the framed tile covered it at the bottom-left).
- `scripts/og.mjs`: a `PHOTO_OG` map writes `home.jpg` and the new `bid-proposal-management.jpg` (1200×630 attention crop of the consultancy photo). `bin/check-content.php`: image keys are also read from `app/data/*/` and from `key` / `detail_image` entries. `src/icons.json` (+ sprite): light `list-numbers`, `shield-warning`, `folder-open` (P21 list icons). `src/css/main.css`, `app/routes.php`, `docs/03` C35, CLAUDE.md.

**Decisions**
- Paragraphs stay 14px (docs/00 L11): the intro's "first paragraph at lead size" and the "What is" definition are set apart by ink colour + medium weight instead (as on the About page).
- The steps block shows its heading, sub line and three intro paragraphs as a normal header and pins only the 01–06 list with the image panels: the pinned block measures exactly the viewport (800 / 900 px at 1024 / 1440), 0.5 × 60vh per step.
- Sector names in the band stay inline links in the client's paragraph (no duplicate chip row), as the prompt's "sector names linked".
- Image-less `default` / `mist` text blocks use heading + button left, paragraphs right on desktop (a single column left half the section empty).
- The sub-service cards are frosted glass over a stronger azure/sky wash: plain light glass on the pale canvas was barely visible.
- A draft's `[[PENDING]]` meta description stays visible off production (check-content, draft ribbon) and is replaced by the category summary on production, so no marker reaches production HTML.
- Photos reuse the generated editorial set (the doc's suggested `bid-strategy-whiteboard` / `bid-management-team-planning` photos do not exist): "What is" = the training-room planning photo with matching alt text.

**Deviations**
- None against the prompt.

**Checks**
- `php -l` clean on every changed PHP file; `npm run build` clean (0 warnings); `npm run og` + `npm run icons` ran.
- Client copy: a script extracts the client copy from `01-bid-proposal-management.md` (headings, subs, lead, trust line, every paragraph, the 6 step titles, the 3 card taglines, the closing CTA) and finds all 48 items in the rendered page text; "over 10 years" present, "16 years" absent. Server HTML: steps 01–06, `Last updated <time datetime="2026-10-15">15 October 2026</time>`, the CTA heading/sub/paras/bold line and the prefilled quote link; one H1; JSON-LD WebPage + BreadcrumbList (Home › Services › Bid & Proposal Management) + Service (`serviceType` Bid management, 3 offers).
- `seo-audit --all` PASS (10 pages incl. the 4 new ones). `check-content` 155: the new `// PROVISIONAL` items (step descriptions, cards intro, FAQs, hub copy), the drafts' `[[PENDING]]` blocks and descriptions (2 route issues), the 4 styleguide image keys.
- Production mode (second server, `APP_ENV=production`): the draft category renders without pending blocks or markers and stays `noindex`; the Bid page and the hub are indexable; no draft ribbon. Locally the drafts show the dashed pending blocks and are `noindex`.
- Internal links in the 4 pages' `<main>` (76) all return 200 locally (sub-service and sector pages are still P21/P22 dev stubs).
- Playwright, motion on, wheel-scrolled to the end at 360 / 768 / 1024 / 1440 on `/services/`, the Bid page, a draft and `/styleguide/service-blocks/`: no horizontal scroll, no console errors or 4xx, nothing left hidden (only the inactive pinned steps at their 0.35 dim); mid-pin the block holds at `top: 0` with step 04 active and the line at 64–66 %. Reduced motion at 360 / 1440: everything visible. Keyboard: card links (ring on the card surface), split buttons, inline sector links, FAQ summaries, hub category / sub-service links, Explore buttons and sector chips, all with a visible ring, in visual order.

**Follow-ups**
- Client (docs/15 Q06c): approve or replace the 6 step descriptions, the cards heading + intro and the 8 FAQs; copy for Categories 2–3 in the same shape (then their blocks fill in and `status` → published).
- P21: the sub-service template reuses these blocks (+ related services); the 3 Bid & Proposal sub-pages publish with the client copy. P22: sector pages (linked from the band). Real photos for the hero columns / "What is" image when available (Q19).

---

## P21 — Sub-service landing template + 12 sub-service pages
**Date:** 16 October 2026
**Commit:** `P21: sub-service template and 12 sub-service pages`

**Files created**
- `templates/pages/services/single.php` (the template `app/routes.php` already named): hero-inner `category` ("Part of <Category>" chip, the sub-service icon in the glowing frame, the client's tagline as the sub line, breadcrumbs Home › Services › Category › Sub-service) → the data's `sections[]` through the P20 block partials (pending blocks dashed off production, omitted on production) → related services → FAQ by `faq_tag` (omitted when nothing is tagged) → CTA band with the page's closing copy and `?service=<sub>#quote`. Schema: WebPage + BreadcrumbList + `service()` (serviceType, description = the meta description, provider, areaServed, BusinessAudience, `isRelatedTo` the category `@id`).
- `templates/sections/service/related.php`: the siblings + 1 cross-category service as the P20 glass cards (a cross-category card names its category); `related` = 'category/sub' paths in the data, default the siblings + the next category's first service; the page itself is never listed and drafts are skipped on production. Copy `ui('related_services')`.
- **Final, published (absorbs C03a):** `app/data/service-pages/bid-proposal-management/{tender-bid-writing,opportunity-identification,expert-review}.php`: all copy from `01a` / `01b` / `01c` verbatim, block by block in the docs' order (hero lead = the client's first paragraph, intro = the following paragraphs; Opportunity Identification's "Understanding Your Business Comes First" and "Tender Compatibility Analysis" need no template change), icons as listed, steps `full` (Opportunity Identification's steps 02–05 carry `detail` paragraphs), CTA labels "Find My Next Tender Opportunity" / "Get Your Tender Expertly Reviewed" (client) and the site default "Get a Free Quote" for Tender & Bid Writing, SEO packs as given, `faq_tag` = the sub slug, cross-category links as the docs suggest (Policy Management / Carbon Reduction Plan / Policy Management). `services.php`: the three are `published`.
- **Provisional, draft:** `compliance-virtual-assistance/cqc-registration.php`, `digital-presence/{website-development,social-media-management}.php`: the current site's copy (scrape §4.4–§4.6) mapped into intro / steps / why / CTA as `// PROVISIONAL` raw material, unsupported claims removed ("ensure compliance", "smooth and successful registration", CQC "renewal", "Proven Results", "visually stunning", "flawlessly", "we've got you covered", "Engagement that Converts"); the "What is" and list blocks are `[[PENDING]]`. `compliance-virtual-assistance/{policy-management,carbon-reduction-plan,data-entry-management,care-plans}.php`, `digital-presence/{email-marketing,seo-analytics}.php`: hero from `services.php`, every block a `[[PENDING: …]]` marker in the client's shape (intro → text → list → steps → why). Titles `<Name> Services UK | My Solutionz` (36–50 chars; CQC = "CQC Registration Support | My Solutionz"), descriptions `[[PENDING]]`.

**Files changed**
- `app/lib/helpers.php`: `service_page()` also loads `category/sub` paths (slug pattern checked).
- `templates/sections/service/text.php`: `pull` (a paragraph index set as a pull line: larger, ink, Azure rule, `read-along`; Opportunity Identification's closing bold sentence) and `read_along` (Expert Review's paragraph 4). `list.php`: count class (`svc-list__grid--5` = a 2 + 3 bento, `--4` = 2 × 2).
- `templates/components/process-steps.php`: step `text` / `detail` through `rich()` (Expert Review step 05 links to Tender & Bid Writing). `templates/components/cta-band.php`: paragraphs through `rich()` (Expert Review's closing **My Solutionz**).
- `src/css/pages/service.css`: pull line, bento grids, `full` steps rule (≥ 1024 + motion: while pinned only the active step shows its paragraphs; mobile / reduced motion / no JS show them all), the related cards' category chip, the client's `<strong>` stays bold inside medium-weight outros. `cta-band.css`: bold in the closing paragraphs.
- `bin/seo-audit.php`: after the page audit, each legacy `/service/<slug>/` URL from `app/redirects.php` must answer 301 to its new page, which must answer 200 itself (one hop).
- `scripts/og.mjs`: `PHOTO_OG` + `tender-bid-writing.jpg`, `opportunity-identification.jpg`, `expert-review.jpg`. `src/icons.json` (+ sprite, 105 symbols): light `file-magnifying-glass`, `presentation-chart`, `map-trifold`, `currency-gbp`, `ranking`, `funnel`, `scales`, `article`, `crosshair`, `chat-centered-text`, `hourglass`. `app/data/ui.php`: `related_services`. `docs/03` C35, CLAUDE.md.

**Decisions**
- Photos: the docs' suggested new photos (`tender-writer-drafting-response`, `tender-opportunity-shortlist`, `bid-reviewer-marking-draft`) do not exist, so the editorial set is reused and the OG crops use the closest photo; their alt texts describe what each photo shows ("Tender writer talking a client team through a response plan", "Consultant reviewing opportunities on a laptop and a phone", "Bid reviewer going through a draft tender response with a client team") instead of the SEO packs' wording for photos we do not have.
- Sector names in "The Advantage of Experienced Tender Writers" stay inline links (as P20's band), not a duplicate chip row.
- Hero image columns are used on every published sub-page (the framed tile floats over the wide column), none on the drafts.
- The three `services.php` one-liners for the published sub-services stay `// PROVISIONAL`: they are our condensed versions of the client's taglines for the menu / slider / cards (docs/15 Q06c), not page copy.

**Deviations**
- Acceptance "check-content no longer lists the 3 final ones, apart from FAQ items": it also still lists the three `services.php` one-liners (above) and each final page's FAQ intro line (FAQ-related).

**Checks**
- `php -l` clean on every changed PHP file; `npm run build` clean; `npm run icons` + `npm run og` ran.
- Client copy: a script extracts every heading, sub line, paragraph, list item, step title/text/detail, bold-led benefit sentence and closing CTA line from `01a` / `01b` / `01c` and finds all of them in the rendered pages (59 / 60 / 59 items, 0 missing); "over 10 years" present, "16 years" absent.
- All 12 routes HTTP 200 without PHP notices; the 3 final pages have no pending blocks, the 3 provisional ones 2 each, the other 6 five each; FAQ present on Tender & Bid Writing and Expert Review, omitted elsewhere (nothing tagged).
- `seo-audit --all` PASS on 22 pages + the 6 legacy redirects (301 in one hop, targets 200). Production mode: `/sitemap-services.xml` = hub, Bid & Proposal Management and its 3 sub-services; a draft sub-page shows no pending blocks or markers and stays `noindex`; related cards on a published page skip the draft cross-category service.
- `check-content` 234: the 9 drafts' `[[PENDING]]` / `// PROVISIONAL` items and pending descriptions (9 route issues), plus the exceptions above.
- Playwright, motion on, wheel-scrolled to the end at 360 / 768 / 1024 / 1440 on all 12 pages: no horizontal scroll, no console errors or 4xx, nothing left hidden (only the inactive pinned steps dimmed). Pinned `full` process on Opportunity Identification at 1024 × 800 and 1440 × 900: the block equals the viewport, the active step shows its paragraphs (step 04: three), the others their titles. Reduced motion at 360 / 1440: everything visible. Keyboard on Expert Review: breadcrumbs, "Part of" chip, CTAs, the inline step link, related cards, FAQ summaries, CTA band, all with a visible ring, in order.

**Follow-ups**
- Client: approve or replace the Q06c items (one-liners, FAQs); copy for the 9 other sub-services (C03b Compliance & VA, C03c Digital Presence) in the same shape; real photos for the sub-service heroes / "What is" images and OG crops (Q19).
- P22: sector pages (linked from the service copy).

---

## P22 — Sectors hub + sector template + 7 sector pages
**Date:** 16 October 2026
**Commit:** `P22: sectors hub, sector template and 7 sector pages`

**Files created**
- `app/data/sector-pages/<slug>.php` × 7 (status `draft`): `seo` (provisional titles 47–51 chars, e.g. "Health & Social Care Tender Writing | My Solutionz"; descriptions `[[PENDING]]`), `hero` (eyebrow, accent "Tender Writing", the 2 narrow column photos, 3 labels `// PROVISIONAL`; the lead is the sector summary), `challenges` `[[PENDING]]` (with the angle to cover), `how_we_help` = the client-approved Our Story sentence ("Each sector presents different challenges …"), Healthcare & Social Care adds the approved "Our experience in healthcare and social care …" sentence and a `// PROVISIONAL` note that NHS healthcare services are commissioned under the Provider Selection Regime, `services` (4 relevant sub-service slugs, `// PROVISIONAL`), `buyers` `[[PENDING]]` (with examples to confirm), `faq_tag`, `cta`.
- `app/data/sector-pages/_hub.php` (draft until the hub copy is approved): hero (H1 "Sectors We" + *Serve*), cards intro `// PROVISIONAL`, "Why Sector Knowledge Matters" = Our Story paragraphs 4–5 verbatim.
- `templates/pages/sectors/single.php`: hero-inner `columns` (the sector photo + 2 narrow columns, labels; H1 "<Sector> Tender Writing") → challenges (the service `list` block; pending → dashed block off production / omitted on production) → `sections/sector/help.php` (two-column text + the relevant sub-services as service cards; drafts skipped on production) → `sections/sector/buyers.php` (chips; pending for now) → FAQ by tag (omitted while none is tagged) → `sections/sector/others.php` (chip links to the other sectors) → CTA band prefilled `/contact-us/?sector=<slug>#quote`. Breadcrumbs Home › Sectors › Sector; schema WebPage (+ `about` the relevant services' `@id`s) + BreadcrumbList.
- `templates/pages/sectors/index.php`: hero-inner `columns` → the 7 sector cards in a centred static grid (1 / 2 / 3 columns, the 7th card centred) → "Why sector knowledge matters" (the service `text` block, mist, button → `/about-us/#our-story`) → CTA band. Schema WebPage + BreadcrumbList + ItemList.
- `templates/components/sector-card.php` (C36) + `src/css/components/sector-card.css`: the P12 card extracted (markup from `sections/home/sectors.php`, CSS from `pages/home-sectors.css`); the home row now uses it (`skew` adds `velocity-skew`). `templates/components/service-card.php` (C37): the glass service card extracted from P20/P21 (`subs.php`, `related.php` now use it). `src/css/pages/sector.css`.

**Files changed**
- `app/data/sectors.php`: each sector carries its photo (`image`, `image_alt`), moved from `app/data/home.php` (`sectors.image_keys` / `images` removed): one source for the home row, the hub and the sector heroes.
- `app/lib/helpers.php`: `sector()`, `sector_page()`, `service_sub_find()`. `app/lib/Schema.php` + `Page.php`: optional `$page['about']` (extra WebPage `about` subjects). `app/data/ui.php`: `sector_page` headings. `templates/sections/service/pending.php`: labels for `challenges` / `buyers`.
- `bin/check-content.php`: **anti-doorway rule** (docs/10 §3): each sector page's body (without the CTA band, the other-sectors row and pending placeholders) as 5-word shingles; a pair sharing > 50 % of the smaller page's shingles is listed.
- `src/css/main.css`, `docs/03` C36 + C37, CLAUDE.md.

**Decisions**
- The hub stays `draft` (its lead and cards intro are ours); it renders and is linked from home, the header and the footer as before.
- Typical buyers and challenges are `[[PENDING]]` with suggested angles in the marker (to confirm with the client, C04) rather than invented lists.
- "Other sectors" chip links were added for hub-and-spoke internal linking (docs/10 §3); they are excluded from the doorway comparison, as is the shared CTA band.

**Deviations**
- None against the prompt.

**Checks**
- `php -l` clean; `npm run build` clean; all 8 routes HTTP 200 without PHP notices (7 sector pages with 2 pending blocks each); `seo-audit --all` PASS (30 pages + 6 legacy redirects).
- `check-content`: the sector pending items and provisional lines are listed; the doorway rule flags 18 of the 21 sector pairs (51–80 % shared) while their copy is pending, as intended.
- Playwright, motion on, wheel-scrolled at 360 / 768 / 1024 / 1440 on `/`, `/sectors/`, Healthcare & Social Care and Building Maintenance: no horizontal scroll, no console errors or 4xx; the home sector row renders the same cards through the component and links to the sector pages.

**Follow-ups**
- C04: per-sector copy (challenges, buyers, sector-specific "how we help", FAQs) until the doorway check is clean; approval of the hub copy, then `status` → published.

---

## P23: Contact page + form backend (16 October 2026)

**Commit:** `P23: contact page and form backend`

**Files created**
- `app/data/contact.php`: SEO (title "Contact My Solutionz | Get a Free Quote", the prompt's description), hero (H1 "Let's Talk About" + *Your Next Contract*, lead `// PROVISIONAL`), card labels, the form copy (labels, hints, options, privacy line, submit "Send Enquiry", counter, every error message, rate-limit / send-failure notices), map, socials, FAQ heading, call strip, and both emails (enquiry subject `New enquiry: {service} – {name}`; auto-reply paragraphs `// PROVISIONAL`).
- `templates/pages/contact.php` (ContactPage, published, breadcrumbs Home › Contact Us) + `templates/sections/contact/cards.php` (Call `tel:`, WhatsApp, Email `mailto:`, Office → Google Maps search in a new tab with `rel="noopener"` and a visually-hidden "(opens in a new tab)", Office hours from `config('hours')` + "Email us any time"; whole-card links with a corner-notch arrow), `form.php` (`#quote`), `map.php` (static map image placeholder `contact/office-map` linking to Google Maps, address, socials). Mini FAQ = the service FAQ block with `faqs('contact')`; call strip = `micro-cta` with `tel:`.
- `templates/pages/400.php`: "Your form session has expired" (noindex, no canonical), links back to the form, phone and email.
- `app/controllers/ContactController.php`: `submit()` = CSRF (400) → honeypot `website` / signed `ts` younger than 3 s or forged (silent 303 to `/thank-you/`, nothing sent, counted by the rate limit) → validation → rate limit 5 / hour / IP (429 + friendly notice) → optional Turnstile → enquiry mail (503 + notice on failure) → auto-reply → PRG 303 to `/thank-you/`. Validation: name required ≤ 100, company ≤ 150, email required + `FILTER_VALIDATE_EMAIL` ≤ 254, phone optional (digits, spaces, + ( ) -, 7–20 digits), sector / service / call-back whitelisted (sector slugs + other; sub-service slugs, plus category slugs for P24), deadline a real date not in the past, message 10–2,000, marketing only `1`. Errors re-render the page with values kept (422).
- `app/lib/Csrf.php` (session started only by form pages: `ms_session`, HttpOnly, SameSite=Lax, Secure on HTTPS, strict mode; token + HMAC-signed render time), `RateLimit.php` (JSON file per bucket + hashed IP in `storage/cache/ratelimit/`, `flock`), `Validator.php` (control characters stripped, single-line fields collapsed, so no header injection), `Mailer.php` (PHPMailer SMTP from `.env`, or `MAIL_DRIVER=log` → `.eml` files in `storage/mail/`; failures to `storage/logs/mail.log` with the recipient's domain only, no bodies).
- `templates/emails/enquiry.php` + `enquiry-text.php` (all fields, Reply-To the sender), `autoreply.php` + `autoreply-text.php` (thanks, what happens next, the chosen service/sector, contact details, privacy link, legal name + company number). Table layout, inline styles, colours from `tokens.css` via `token()`.
- `src/css/components/form.css` (C38 form fields), `src/css/pages/contact.css`, `src/js/modules/form.js` (character counter, focus on the error summary, summary links focus their field or the custom select's combobox).

**Files changed**
- `app/lib/Router.php`: a route may hand POSTs to a controller (`'post' => [Class, 'method']`); `render()` / `redirect()` are public (non-301 redirects send `Cache-Control: no-cache`). `app/routes.php`: `/contact-us/` → `pages/contact` + `ContactController::submit`.
- `app/lib/helpers.php`: `contact()`, `token()` (a hex colour from `tokens.css`, for emails). `app/data/faqs.php`: "How quickly will you respond?" (`[[PENDING: response time]]`, tagged contact + faq; hidden on production while pending).
- `src/js/modules/select.js`: copies `aria-invalid` / `aria-describedby` from the native select to the combobox. `src/js/core/motion.js`: `focusTarget()` leaves focus alone when it is already inside the hash target (the `#quote` re-alignment was stealing focus from the error summary).
- `.env.example`: `TURNSTILE_ENABLED=false`, `TURNSTILE_SITE_KEY`, `TURNSTILE_SECRET_KEY`. `src/css/main.css`, `docs/03` C38, CLAUDE.md.

**Decisions**
- Status codes on re-render: 422 (validation), 429 (rate limit), 503 (send failure). Chrome logs a "Failed to load resource" console line for a 4xx document; that line is expected on those responses only.
- The session cookie is strictly necessary (CSRF) and starts only on pages that render or handle a form, never site-wide (docs/13 §2).
- The time-trap stamp is signed with the session token, so a bot cannot post an old timestamp; a missing or forged stamp is treated like a fast submit.
- The service select lists sub-services only (grouped per category, as in the hero Quick-Start); category slugs are accepted server-side for the P24 pre-fill.
- Inputs use 16px text (iOS does not zoom on focus); labels, hints and errors keep the 14px scale.

**Deviations**
- None against the prompt. `/thank-you/` is still the local stub until P24.

**Checks**
- `php -l` clean on every changed PHP file; `npm run build` clean; `/contact-us/` HTTP 200, no PHP notices.
- Acceptance (curl with a cookie jar): valid submission after 3 s → 303 to `/thank-you/` + 2 `.eml` files (enquiry with Reply-To the sender, auto-reply); invalid → 422 with a 6-link error summary, 6 `aria-invalid` fields and values kept; honeypot filled and a < 3 s submit → silent 303, no mail; missing CSRF → 400 "Your form session has expired"; 6th successful submission within the hour → 429 with the rate-limit notice. Test mail and rate-limit files removed afterwards.
- Browser (Playwright, 360): keyboard order name → company → email → phone → sector / service comboboxes → date → call-back; a sector chosen with the keyboard is posted and kept after an error; focus lands on the error summary; summary links focus the field; counter "2 of 2,000 characters".
- Widths 360 / 768 / 1024 / 1440 with motion and 360 / 1440 reduced: no horizontal scroll, no console errors, no hidden text.
- `seo-audit --all`: 31 pages + 6 legacy redirects, 0 failing. `check-content`: the new PROVISIONAL lines and `[[PENDING: response time]]` are listed.

**Follow-ups**
- P24: pre-fill, inline validation, loading state, thank-you page.
- Client: response time (FAQ + auto-reply), approval of the hero lead, form intro, call strip and auto-reply copy; SMTP credentials for production (`MAIL_DRIVER=smtp`); generate the static office map image (`contact/office-map`).

---

## Client change: clean CTA band background (16 October 2026)

**Request:** remove the wave lines behind the "Ready to Win Your Next Contract?" band and use a clean background.

**Files changed**
- `templates/components/cta-band.php`: the animated `grid-wave` layer is no longer printed (the faded logo mark stays).
- `src/css/components/cta-band.css`: `.cta-band__bg` now paints two soft radial glows (azure top-right, sky bottom-left) over the navy → azure gradient; the `.cta-band__wave` rules are removed.
- `templates/sections/home/cta.php` (docblock), `docs/03` C21, `docs/00` L16 note.

**Notes**
- Applies to every page that closes with the CTA band (home, About, services, sectors, contact). The `grid-wave` component and `grid-wave.svg` stay available (styleguide) but are no longer used by the band.
- Checked at 1440 (home) and 360 (contact): no console errors, no horizontal scroll.

---

## Deployment: GitHub + Vercel preview (16 October 2026)

**Request:** push the code to https://github.com/ahsanawan-0/mysolutions and deploy it on Vercel with the CLI for a live URL.

**Result**
- GitHub: `main` pushed to `origin` (public repo `ahsanawan-0/mysolutions`).
- Vercel: project `mysolutions` (scope `urksrkurksrk-6447s-projects`), live at **https://mysolutions-nu.vercel.app**. Deployed with `vercel deploy --prod --yes`; the Git integration could not be connected (the Vercel GitHub app has no access to `ahsanawan-0`), so pushes do not deploy.

**Files created / changed**
- `vercel.json`: `npm ci` + `npm run build`, static output `public/`, `api/index.php` on `vercel-php@0.9.0` (PHP 8.5; `symfony/yaml` 8.1 in the lock needs PHP 8.4+), `excludeFiles` keeps docs/design/source images out of the function, routes: `/` → PHP (otherwise Vercel served `public/index.php` as a static file), `/index(.php)` → 301 `/`, `/.htaccess` → 404, long cache on `/assets/build|fonts/`, filesystem, then everything else → PHP.
- `api/index.php`: requires `public/index.php`.
- `composer.json`: `scripts.vercel` (run by vercel-php after `composer install`) builds the Vite manifest inside the function too, so `vite()` finds `public/assets/build/manifest.json`; Vite's hashes are deterministic, so they match the static build.
- `.vercelignore`: mirrors `.gitignore` (`.env`, `vendor`, `node_modules`, build output, BIOMEE…); `.gitignore` gains `.vercel` (added by `vercel link`).
- `app/lib/helpers.php`: `storage_path()` (`STORAGE_PATH` env, default `storage/`); `app/bootstrap.php`, `app/lib/Mailer.php`, `app/lib/RateLimit.php` write through it, because the Vercel function's filesystem is read-only except `/tmp`.
- `CLAUDE.md`: "Preview deploy" row in Commands.

**Vercel environment (production):** `APP_ENV=staging` (noindex header + draft ribbon), `SITE_URL=https://mysolutions-nu.vercel.app`, `STORAGE_PATH=/tmp/storage`, `MAIL_DRIVER=log`, `VITE_DEV=false`.

**Checks:** `/`, About, services hub + category, sectors, contact, sitemap, robots, llms.txt → 200; unknown path → 404; `/about-us` → 301 with slash; canonical and internal links use the Vercel domain; home in the browser: no console errors, every asset 200.

**Deviations / follow-ups**
- docs/11 §8 asks for a password-protected staging; this preview is public (noindex only). Turn on Vercel Deployment Protection for production if the client wants it private.
- The contact form is not delivering mail here: `MAIL_DRIVER=log` writes `.eml` files to `/tmp`, which Vercel discards. Set `MAIL_DRIVER=smtp` + `SMTP_*` in Vercel once the client sends SMTP details. The form was not submitted during the checks.
- `/faq/`, `/insights/`, `/thank-you/` return 404 until their prompts (P24+) are built.

---

## Client change: no draft ribbon on the preview, pause buttons hidden until needed (16 October 2026)

**Request:** remove the "Draft · noindex · not in sitemap" ribbon and the play/pause buttons from the live preview.

**Files changed**
- `templates/layouts/base.php`: the draft ribbon also needs `DRAFT_RIBBON` (default `true`); Vercel has `DRAFT_RIBBON=false`. Local still shows it. The preview keeps its `X-Robots-Tag: noindex` header (staging).
- `src/css/components/marquee.css`: every `.marquee__pause` (hero chips, services hub sectors, testimonials, marquee component) is invisible and click-through until the pointer is over its moving row (`[data-anim~="marquee"]:hover`, `hover: hover` devices only), it has keyboard focus, or it is pressed.
- `src/css/components/footer.css`: the footer strip's button sits beside the strip, so `.site-footer__strip:hover ~` reveals it.
- `src/css/pages/home.css`: the hero slider's pause leaves the flow (absolute at the controls' right end) so prev · dots · next stay centred, and follows the same reveal rules. The two-row dots threshold moves from 466px to 488px so the revealed pause never overlaps "next" in the one-row layout.

**Decision:** L30 (WCAG 2.2 AA) is locked, so the buttons were not deleted. The client chose "hide until needed": 2.2.2 still has a pause mechanism (keyboard focus, pointer hover), and `prefers-reduced-motion` still stops all auto-motion. On touch screens the buttons stay hidden.

**Checks:** local 1440: the slider pause and the footer pause are hidden (opacity 0, `pointer-events: none`) and appear on hover; prev · dots · next centred. 360: no horizontal scroll, pause hidden. No console errors; `npm run build` clean.

---

## P24: Quote pre-fill, thank-you page, form polish (18 October 2026)

**Commit:** `P24: quote pre-fill, thank-you page, form polish`

**Files created**
- `templates/pages/thank-you.php`: `/thank-you/` (`noindex, follow`, canonical `/thank-you/`, breadcrumbs Home › Contact Us › Thank You; still not in any sitemap because its route has no `sitemap` group, and robots.txt already disallows it). Hero-inner `simple` with H1 "Thank" + *You* and "Your enquiry has been received." under a hex check mark (the logo's flat-top hexagon); "What happens next" 3-step timeline (review → contact you → no-obligation quote; connecting line down the markers on mobile, across from 768px); the timing `[[PENDING: response time]]` is a dashed note off production and omitted on production; "Deadline close? Call us on …"; two link cards (Insights, Services) in the contact-card markup; call strip. Direct visits show the same page and never start a session.
- `src/css/pages/thank-you.css` (check mark, timeline, link grid; the check's pre-state exists only under `html.js` with motion allowed and has the 2.5 s safety restore).
- `src/js/modules/success-check.js`: after `splash:done`, the pale hex pops in, then DrawSVG draws the ring and the tick (once). Reduced motion: nothing.
- `src/js/core/track.js`: `window.msTrack(name, props)`, a no-op until P28's consent module sets `window.msAnalytics = { send }`. Fires `[data-track-event]` once on load and tracks clicks on `tel:` / `mailto:` / WhatsApp links (`click_phone` / `click_email` / `click_whatsapp`, props `{ page }`). Imported first by `src/js/main.js`.

**Files changed**
- `app/lib/helpers.php`: `quote_url(['sector' => …, 'service' => …])` → `/contact-us/?sector=…&service=…#quote`.
- `app/controllers/ContactController.php`: `prefill($_GET)` (whitelisted sector + sub-service or category slugs; a category selects its first sub-service and the chip reads "Interested in: <category>"; anything else is ignored). The success flag is now `['at', 'service', 'sector']` (slugs only) for the thank-you page's `form_submit` event.
- `app/lib/Csrf.php`: `resume()` starts the session only when the `ms_session` cookie already exists (never in the CLI).
- `app/data/contact.php`: `form.sending`, `form.sending_status`, `form.prefill` (chip copy) and the whole `thank_you` block (SEO, hero, steps, timing, links; provisional copy marked `// PROVISIONAL`).
- `templates/pages/contact.php` + `templates/sections/contact/form.php`: on a plain GET the pre-fill selects the options server-side and prints the "You selected: …" chip above the form; its × is a link to `/contact-us/#quote` (works without JS). The submit button carries the inlined white logo mark for the loading state; `<template>`s hold the server's inline-error and error-summary markup for form.js; the message gets `minlength="10"` (mirrors the server rule; the form stays `novalidate`).
- `src/js/modules/form.js`: inline validation on blur mirroring `ContactController::validate()` (only once a field has been edited or already shows an error; the error clears as soon as the value is fixed; hint + error ids kept in `aria-describedby`); on submit the same checks build the error summary (focused, links focus the field or its C31 combobox) and nothing is sent; a valid submit sets `.is-loading` (the mark breathes and its bars re-draw on a loop, label "Sending…", `aria-disabled`, `aria-busy`, a polite status message) and blocks a second submit (reset on bfcache `pageshow`); arriving on `#quote` focuses the first empty required field after the splash, without scrolling; the chip's × clears the sector/service in place, announces it, cleans the URL and focuses the sector combobox.
- `src/css/components/form.css` (loading state; the button keeps full strength instead of the faded disabled look), `src/css/pages/contact.css` (`.prefill` chip, 44px clear button), `src/css/main.css` (imports thank-you.css).
- Context on every quote CTA: `$page['quote']` on the category (`['service' => category]`), sub-service (`['service' => sub]`) and sector (`['sector' => slug]`) pages; `templates/layouts/base.php` passes it to the header, so the header CTA, mega-menu CTA, mobile sheet and mobile action bar use `quote_url()`; `templates/partials/footer.php` does the same for its "Get a Free Quote" link; the service/sector templates use `quote_url()` instead of hand-built strings.
- `templates/components/hero-inner.php`: optional `slot` (trusted HTML above the H1) for the check mark.
- `src/js/core/motion.js`: `alignHash()` drops a `#hash` with no target from the address bar. Browsers carry the form action's `#quote` through the 303, so the thank-you URL showed `/thank-you/#quote`.
- `CLAUDE.md`, `docs/03` C38.

**Decisions**
- The chip lists items in the form's order (sector, then service). For a category: "Interested in: Digital Presence · Security".
- Clearing the chip means clearing the pre-selection (like a filter chip), not only hiding the chip.
- Client-side errors use the server's exact markup and messages (`data-messages` = `contact('form.errors')`), so JS and no-JS errors look and read the same. The server stays the authority (the e-mail check is an approximation of `FILTER_VALIDATE_EMAIL`).
- The `form_submit` props carry slugs only (form, service, sector), never personal data. The `data-track-event` attribute stays in the HTML so P28 can replay it if the visitor consents on that page.
- Loading-state motion: the docs/07 §6 breathing loop (1 ↔ 1.02) is invisible at button size on its own, so the button also loops the header-hover bar re-draw (same vocabulary). Reduced motion: no animation, the label and the status message carry the state.

**Deviations**
- None against the prompt.

**Checks**
- `php -l` clean on every changed PHP file; `npm run build` clean.
- curl: pre-fill for a sub-service + sector, a category, category + sector, and junk values (`sector=evil`, `service=<script>`, `sector[]=x`) are ignored; canonical stays `/contact-us/`. `/thank-you/` 200, `noindex, follow`, no `Set-Cookie` on a direct visit, not in `sitemap-pages.xml`. End-to-end POST → 303 → thank-you with the `form_submit` hook once; a reload has none. Category, sub-service and sector pages: every quote link (hero, CTA band, header, mega menu, mobile sheet, action bar, footer) carries the page's context.
- Playwright (42/42 + extras): chip text and pre-selected comboboxes; focus on Name after `#quote`; the chip's × (mouse and keyboard) clears the selects, cleans the URL, announces and focuses the sector combobox; inline errors appear on blur only after editing and clear on fix; past deadline and bad phone caught; the client summary lists name/email/message, is focused, its links focus the field, and no POST is sent; a triple click sends one POST; the loading state shows "Sending…" with the mark; thank-you URL clean, check mark drawn; hero Quick-Start → `/contact-us/?sector=healthcare-social-care&service=cqc-registration#quote` with both selected; no console errors, no 4xx/5xx.
- Widths 360 / 768 / 1024 / 1440 (contact with a pre-fill + thank-you): no horizontal scroll; reduced motion at 360: the check mark and steps are visible.
- `seo-audit --all`: 32 pages + 6 legacy redirects, 0 failing. `check-content`: `/thank-you/` ok (title 41, description 134); the new PROVISIONAL lines and `[[PENDING: response time]]` are listed. Test `.eml` and rate-limit files removed afterwards.

**Follow-ups**
- Client: response time (thank-you timing, contact FAQ, auto-reply), approval of the thank-you copy. The mega menu's featured card (P04) says "come back within one working day": an unconfirmed response-time promise; align it once the client confirms the time.
- P25: `/insights/` (linked from the thank-you page) is still a stub / 404 on the preview until the blog engine lands.
- P28: the consent module sets `window.msAnalytics` and replays `[data-track-event]`.
- The Vercel preview runs PHP sessions in `/tmp` per function instance; check the form + thank-you flag there after the next deploy.

---

## P25: Insights (blog) engine (18 October 2026)

**Commit:** `P25: Insights blog engine`

**Files created**
- `app/lib/Markdown.php`: league/commonmark (CommonMark + GFM; tables wrapped in `.prose-table`). Raw HTML in a post is escaped (it stays visible, so nothing is silently lost) and unsafe link schemes are dropped. A `DocumentParsedEvent` listener gives H2/H3 unique ids from their text and returns them as `toc` (P26). External links get `rel="noopener"` (no forced new tab). `render()` returns `html`, `toc` and `words`.
- `app/lib/MarkdownImageRenderer.php`: `![alt](image-key)` → `img()` (responsive, lazy, width/height; branded placeholder while the key is missing). An absolute URL or path renders a visible `[[PENDING IMAGE: unsupported-image-url]]`, because it would have no dimensions.
- `content/authors.php`: `team` (My Solutionz Team, Organization, `/about-us/`, provisional bio using the approved "over 10 years of business development experience" claim) plus a commented Person example. `content/categories.php`: the 5 categories, each with name, the two-voice H1 (title + accent), `seo_title` and a unique description (all provisional).
- `app/data/insights.php` (+ `insights()` helper): listing SEO (the prompt's title and description), hero, the publish threshold (3), chip / status / pagination / empty-state / feed copy.
- `templates/pages/insights/index.php` (`/insights/`, `/insights/page/<n>/`), `templates/pages/insights/category.php` (`/insights/category/<slug>/` + `/page/<n>/`), shared body `templates/sections/insights/listing.php`: hero-inner `simple`, category chips, featured card (page 1), grid of C10 cards, pagination, empty state (LinkedIn follow; on a category also "View all insights"), CTA band. `/page/1/` 301s to page 1; a page past the end or an unknown category is a 404. Every page is self-canonical, and pages 2+ get "(Page n)" titles and "Page n:" descriptions.
- `templates/components/pagination.php` + `src/css/components/pagination.css` (C39).
- `app/controllers/FeedController.php`: `/insights/feed.xml`, RSS 2.0 with the last 20 published posts (never drafts), full description, category, `dc:creator`, `lastBuildDate`, `<atom:link rel="self">`. The `<head>` already links it (P03).
- `src/js/modules/insights-filter.js`: chip click → fetch the chip's URL → swap `[data-insights-results]`, hero breadcrumbs/H1/lead, chips, `<title>`, meta description and canonical → `pushState`. GSAP Flip: leaving cards fade out, shared cards glide, new cards fade up. Back / forward replay it (listing paths only, hash-only entries ignored). Focus stays on the chip and the result count is announced (`role="status"`). Modified clicks and any fetch failure fall back to normal navigation; reduced motion swaps without animating.
- `src/css/pages/insights.css` (C40 chips, results grid 1/2/3 columns, empty state).

**Files changed**
- `app/lib/Blog.php`: rewritten around a cached index (`storage/cache/blog.php`, atomic write, keyed on post mtimes, authors/categories files, the image manifest, the site URL and the renderer files). API: `all()`, `published()`, `latest()`, `find()`, `byCategory()`, `paginate()` (page 1 = featured + 9, later pages 9), `related()` (same category, then shared tags, then newest), `categories()` (with url and count), `category()`, `author()`, `readingTime()` (220 wpm), `displayDate()`. Posts gain `author_key`, `category_slug` / `category_url` (slug or name accepted), `tags`, `takeaways`, `html`, `toc`, `words`, `reading_time`; `author` / `category` stay the display names, so the home section and the hero mini card are unchanged.
- `app/lib/Schema.php`: `Schema::blog($posts)` (Blog node with short BlogPosting entries for the published posts listed); `webPage()` honours `$page['main_entity']` (`app/lib/Page.php` documents it). Listings are CollectionPage → mainEntity `#blog`.
- `app/controllers/SitemapController.php`: the insights group adds, while `/insights/` is indexable, its pages 2+ and every category with published posts (+ their pages). It also lists published posts once `pages/insights/single` exists (P26). lastmod = the newest `updated` on each URL; published posts only.
- `app/routes.php`: category slugs constrained to `content/categories.php`; new `/insights/category/{slug}/page/{n}/`; the feed is a controller route.
- `templates/components/card-blog.php` + `src/css/components/card-blog.css`: `variant => 'featured'` (16:10 image beside the text from 900px, description, reading time) and `data-flip-id` on every post card.
- `content/blog/2026-01-18-…md`: `author: team`, `category: health-social-care` (the new keys; still a draft, body for P26).
- `src/css/main.css`, `CLAUDE.md`, `docs/03` (C10, C39, C40).

**Decisions**
- Category H1s read as the category name in two voices: "Tender Writing *Tips*", "Procurement *Updates*". The names that would leave a single word get *Insights*: "Health & Social Care *Insights*", "Compliance *Insights*", "Digital *Insights*".
- Chips show only categories with visible posts (plus the current one), so a chip never leads to an empty page. Counts are read out as ", 4 insights".
- A category page is `published` only when the listing is (3+ published posts) and it has a published post of its own; otherwise it is a noindex draft.
- `html_input: escape` rather than `strip`: a stray HTML tag in a post shows up visibly instead of disappearing. Heading ids come from our own listener rather than HeadingPermalinkExtension, so no permalink anchors are injected into the headings.
- The feed and sitemap always use published posts only, in every environment; the listing on local/staging also shows drafts (flagged) like the home section.

**Deviations**
- Heading ids use a small custom listener instead of the HeadingPermalink extension (same ids, no injected anchor markup). Otherwise none.

**Checks**
- `php -l` clean on every changed PHP file; `npm run build` clean.
- Smoke test of `App\Markdown`: ids (`whats-in-a-tender`, `-2` for a duplicate), TOC, wrapped table, `rel="noopener"` on external links only, the manifest image as `<picture>`, raw `<script>` escaped, a `javascript:` link dropped.
- curl with 14 temporary fixture posts (13 published, 1 draft; removed afterwards): `/insights/` 200 and `/page/2/` 200, `/page/3/` 404, `/page/1/` 301; categories 200, unknown category 404; feed 13 items, newest first, no draft; `sitemap-insights.xml` = `/insights/`, `/page/2/` and the 5 categories with correct lastmods.
- Playwright 32/32: featured + 9 cards + pagination; the Compliance chip by keyboard swaps in place (no reload), pushes the URL and updates H1, title, canonical, breadcrumbs and chip state, keeps focus, announces the count, and shows only Compliance cards, all visible after the Flip; Back restores `/insights/`; Next goes to page 2 (self-canonical, "(Page 2)" title, no featured card); no horizontal scroll at 360 / 768 / 1024 / 1440; reduced motion filters without animation; with JS off the chips and pagination are plain links. No console errors, no 4xx/5xx (apart from the P26 article stubs).
- After removing the fixtures: the empty category state and the single-draft listing render, and the home Latest Insights section is unchanged.
- `seo-audit --all`: 33 pages + 6 legacy redirects, 0 failing. `check-content`: `/insights/` ok (draft, title 44, description 132); the category titles are 40–53 characters and the descriptions 134–139; the new PROVISIONAL lines are listed. No SEO warnings in the draft ribbons.

**Follow-ups**
- P26: the article template (`pages/insights/single`), which also switches on the posts in the sitemap; `Schema::blogPosting()` should take the author's `type` (the `team` author is an Organization).
- C07: 3+ real published posts flip the listing to `published` (indexable, in the sitemap); the client approves the category descriptions and the author bio.

---

## P26: Article template + migrate the existing post (18 October 2026)

**Commit:** `P26: Article template and migrated post`

**Files created**
- `templates/pages/insights/single.php` (`/insights/{slug}/`; an unknown slug, or a draft on production, is a 404): hero-inner `article` (breadcrumbs Home › Insights › Category › Post, category chip, H1, lead, meta row: author, published date, "Updated" date when it differs, reading time; the featured image is the LCP, `fetchpriority="high"`) → article body → related insights → CTA band pre-filled with the category's `quote` context. SEO: `<title>` = front matter `seo_title`, else title + suffix when it fits in 65 characters, else the title; `og:type=article` + `article:published_time / modified_time / author / section / tag`; the photographic 1200×630 OG crop; WebPage (`primaryImageOfPage` = the 16:9 crop, `mainEntity` → `#article`) + BreadcrumbList + BlogPosting.
- `templates/sections/insights/article.php`: reading-progress bar, Key takeaways (front matter `takeaways`, `rich()`), the contents, the `.prose` body (max 720px), then share links and the author box. DOM order = reading order (takeaways → contents → body → footer); from 1100px the contents sit sticky in a right-hand column that spans every row.
- `templates/sections/insights/related.php`: "Keep Reading", 3 C10 cards from `Blog::related()` (same category, then shared tags, then newest) + "View all insights"; absent when there are no other posts.
- Components: `toc.php` (C41), `author-box.php` (C42), `share-links.php` (C43); CSS `components/prose.css`, `toc.css`, `author-box.css`, `share.css`, `reading-progress.css` (C44), `pages/article.css`; JS `modules/toc.js` (collapse below 1100px, close after a jump, scrollspy), `modules/reading-progress.js`, `modules/article.js` (an overflowing table becomes a focusable, labelled scroll region; lazy images refresh ScrollTrigger).
- `app/lib/MarkdownQuoteRenderer.php`: `> [!NOTE|TIP|IMPORTANT|WARNING|UPDATE] Optional title` → `<aside class="callout callout--…">` with an icon and title; `> [!PULL]` → `<aside class="pull-quote">` (quotes icon, `read-along`); other quotes stay `<blockquote>`.
- Image crops `public/assets/img/sectors/healthcare-social-care-consultation-{16x9,4x3,1x1,og}.jpg`.

**Files changed**
- `content/blog/2026-01-18-health-and-social-care-tender-vocabulary-explained.md`: rewritten (see the originality check), `seo_title` "Health and Social Care Tenders: Key Vocabulary Explained" (56 characters), the live page's meta description kept, `updated: 2026-10-18`, 5 tags, 4 takeaways, `status: draft`.
- `app/lib/Markdown.php`: the callout / pull-quote markers (listener strips the marker line, the renderer prints the box). `app/lib/Blog.php`: front matter `seo_title`; the cache key also covers the quote renderer, the icon sprite and `src/icons.json` (icons are printed into the cached HTML).
- `app/lib/Schema.php`: `blogPosting()` takes the `Blog::author()` entry: a Person node (url, sameAs, jobTitle, worksFor) or, for the `team` Organization, the `#organization` entity itself; adds `isPartOf #blog`, `keywords`, and `image` only when there are images. `app/lib/Page.php` documents `article_author`, `article_section`, `article_tags`; `templates/partials/head-seo.php` prints `article:tag`.
- `app/lib/Image.php`: `Image::crop($key, '16x9'|'4x3'|'1x1'|'og')`. `scripts/images.mjs`: every image a post uses gets the three schema crops (at most 1200px wide, never upscaled; `attention` crop) and a 1200×630 OG crop, recorded as `crops` in the manifest.
- `templates/components/hero-inner.php`: meta items take a `label` printed before the `<time>` ("Updated" 18 October 2026).
- `content/authors.php`: `team` gains `role` (provisional) and `sameAs` (the company LinkedIn). `content/categories.php`: optional `quote` (health-social-care → `?sector=healthcare-social-care`). `app/data/insights.php`: `article` copy.
- `app/controllers/SitemapController.php`: post image entries use the 16:9 crop. `bin/seo-audit.php` audits every article and checks the old blog URL's one-hop 301; `bin/check-content.php` checks every article's title and description.
- `src/icons.json` (+ lightbulb, clock-counter-clockwise, seal-check, x-logo, list-bullets in regular), `src/css/main.css`, `CLAUDE.md`, `docs/03` (C41–C44).

**Originality check (the prompt's gate): failed, so the post was rewritten and stays a draft**
- The old post's body is a copy of healthcarebids.co.uk's article at the same slug (`/whats-in-a-health-and-social-care-tender-tender-vocabulary-explained/`, `datePublished` 6 June 2024 in its schema, retrieved from the Wayback Machine because the live site's TLS certificate is broken). 96.3 % of the old post's 8-word sequences appear verbatim in it. Our copy went up on 18 January 2026, so ours is the copy.
- So the text was not migrated. The post is a new article in our own words under the same headings (ITT, service specification, clarification questions, SQ/PSQ, non-collusion, social value, TUPE, TUPE list, confidential information form, technical and quality questions), each opening with a direct answer, plus an "Old and new terms at a glance" table, the Procurement Act 2023 update callout (with `[[PENDING: client review]]`) and a "How can My Solutionz help?" section with internal links. Against the healthcarebids text, 8-word overlap is 0 % (one shared phrase: the regulations' name) and 6-word overlap 0.4 % (headings only). The outbound healthcarebids link is gone.
- The rewrite also corrects the old post: TUPE eligibility is about assignment to the service, not a 50 % rule; employee liability information is due 28 days before the transfer.
- Facts used (to confirm in C07): Procurement Act 2023 in force 24 February 2025 (England, Wales, Northern Ireland); Central Digital Platform + PSQ; Provider Selection Regime since 1 January 2024; standstill 8 working days (was 10 calendar days); assessment summaries; contracts over £5 million published; FOI Act 2000; Public Services (Social Value) Act 2012; NHS minimum 10 % social value and net zero weighting; TUPE 2006.

**Decisions**
- Kept `status: draft` as the prompt requires after a failed originality check; the client approves the rewrite in C07 before it is published.
- The `team` author is the company entity in schema (name "My Solutionz", `@id #organization`), so the byline and the publisher are one entity; the visible byline stays "My Solutionz Team".
- One `<details>` serves both TOC layouts (open in the HTML; JS collapses it below 1100px), so the no-JS list exists once. Breakpoint 1100px rather than 1024px: below it, the 720px body and a 240px column don't fit side by side.
- Paragraphs stay 14px (L11); the prose uses a 1.75 line height for long-form reading.
- The reading-progress bar is scroll-linked, not animation, so it also runs under reduced motion.
- Related posts are omitted entirely when there are none (only one post exists today).

**Deviations**
- The post's text is a rewrite, not a migration (originality check above).
- The prompt asks for the schema to "validate": the JSON-LD parses and has the docs/10 §6 fields, but the Rich Results Test and validator.schema.org need a public URL, so that run belongs to P30 or the next Vercel preview.

**Checks**
- `php -l` clean on every changed PHP file; `npm run build` clean; `npm run images` wrote the 4 crops.
- Markdown smoke test: callouts with and without titles, pull quote, plain quote, unknown `[!BOGUS]` left as a quote, table wrapper.
- curl: the article 200 (one H1, noindex while draft, `article:*` tags, OG crop), `/insights/no-such-post/` 404, the old URL 301 → the new URL. JSON-LD: WebPage → mainEntity #article, BreadcrumbList (4 items), BlogPosting (3 crops, ISO dates with the Europe/London offset: `2026-10-18T00:00:00+01:00`, Organization author, wordCount 2014 → 10 min read).
- Playwright 36/37 with 3 temporary published fixture posts (removed): hero, takeaways, callouts, table, pull quote; desktop TOC open, keyboard Enter on a TOC link lands the heading under the header, `aria-current` on exactly one link, TOC sticky, progress bar advancing and moving to the top edge when the header hides; share URLs encoded; 3 related cards; CTA `?sector=healthcare-social-care#quote`; no horizontal scroll at 360 / 768 / 1024 / 1440; TOC collapsed below 1100px, opens by keyboard, closes after a jump that lands on the heading; the table becomes a focusable region at 360; reduced motion shows everything and the TOC jumps; no JS: the TOC list is open and anchors work. No console errors, no 4xx/5xx. The one failure was the test itself (in Vite dev mode the CSS is injected by JS, so a no-JS page has no styles); re-run with production assets below. Keyboard pass: every share, author and related link has a visible focus ring. With production assets (`VITE_DEV=false`): no errors; without JS the progress bar is hidden.
- `seo-audit --all`: 37 pages with the fixtures, 34 after removing them (incl. the article) + 7 legacy redirects (incl. the old blog URL), 0 failing. `check-content`: the article 56 / 148 characters, draft; new PROVISIONAL / PENDING lines listed. Sitemap and feed list published posts only (the draft article is in neither).

**Follow-ups**
- C07: the client reviews the rewritten article (facts above) and the Procurement Act note, then sets `status: published`. Until then the old URL 301s to an article that 404s on production, so publish it before launch (or point the 301 at `/insights/category/health-social-care/`).
- C07: real authors (Person entries with permission) give the strongest E-E-A-T signals; the `team` role line is provisional.
- P30: Rich Results Test + validator.schema.org on a public URL.
