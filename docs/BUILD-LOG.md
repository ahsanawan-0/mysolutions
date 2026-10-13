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

**Follow-ups**
- P06: implement `cta-mark` (S10), `micro-arrow` (S2), `wave` (MorphSVG) and `fade-up` on the footer; switch the back-to-top to `window.lenis.scrollTo` (already wired) and optionally ScrollTrigger for the ring.
- P07: replace the hexagon stand-in in the CTA band and the text logo in the footer with the SVG mark/lockup.
- P28: the consent module listens for `[data-cookie-settings]`. P29 builds `/sitemap/` which the footer already links.
- Client: office hours and the footer description are provisional (`check-content` lists both).
