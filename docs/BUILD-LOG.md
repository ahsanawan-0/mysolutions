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
