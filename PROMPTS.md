# PROMPTS.md: My Solutionz Website Build Prompts

> **What this is:** 36 build prompts (P00–P35) that build the entire My Solutionz website, plus content-fill prompts (C01–C07) that drop in the client's final copy later.
> **How to use:** run the prompts **in order**, one per Claude Code session (or one at a time in the same session), in the project root `D:\mysolutions\New folder`. Paste the whole prompt block. After each prompt, review the result in the browser, then commit.
> **Written:** 13 Oct 2026 · Specs: `docs/` (start with `docs/00-LOCKED-DECISIONS.md`)

---

## Session starters (paste into a NEW chat)

**First session (P00):**
```text
You are building the My Solutionz website (a UK services company; custom PHP + GSAP, no WordPress) in this folder: D:\mysolutions\New folder

Before doing anything:
1. Read docs/README.md and docs/00-LOCKED-DECISIONS.md. These are the client's locked decisions; never change them.
2. Read PROMPTS.md from the top through the end of the "P00" section (Strategy, Status table, Standard checks, Global rules and P00 itself).

Then execute prompt P00 exactly as written in PROMPTS.md:
- Read the docs listed under "Read first" for P00.
- Do every step under "Build" and respect "Do not".
- Verify every "Acceptance" item.
- No application code exists yet, so skip the server/browser parts of the Standard checks. Do append the P00 entry to docs/BUILD-LOG.md and commit "P00: project primer".

Rules for this session:
- Work only on P00. Do not start P01.
- If anything in PROMPTS.md conflicts with docs/00-LOCKED-DECISIONS.md, stop and ask me.
- When finished, give me a short report: files created, anything you couldn't do, and confirmation that it's committed.
```

**Every later session (change `P01` to the next prompt number):**
```text
Continue the My Solutionz website build in D:\mysolutions\New folder.

1. Read CLAUDE.md, docs/00-LOCKED-DECISIONS.md and the latest entries in docs/BUILD-LOG.md to see what's already done.
2. Open PROMPTS.md, read its "Standard checks" and "Global rules", then execute prompt P01 exactly as written: Read first → Build → Acceptance → Standard checks → BUILD-LOG entry → commit "P01: <title>".
3. Only do P01. If something is blocked, unclear, or conflicts with the locked decisions, stop and ask me before guessing.
4. End with a short report: what was built, the local URL(s) to view it, any deviations or follow-ups, and confirmation that it's committed.
```

---

## Strategy: build the structure now, fill content later

The client is still delivering content, so **structure and content are separated**:

1. **All copy lives in data files** (`app/data/*.php`, `content/**/*.md`), never hard-coded in templates.
2. Where final copy doesn't exist yet, the data holds **provisional copy** marked with a `// PROVISIONAL` comment, or a visible marker `[[PENDING: what is needed]]`.
3. Every page has a **`status`**: `published` or `draft`. Draft pages render normally on local/staging, but are forced `noindex`, are left out of `sitemap.xml`, and show a small "Draft" ribbon on non-production environments.
4. `php bin/check-content.php` lists every `PROVISIONAL`, `[[PENDING` and `draft` item, so nothing slips through. **Launch gate: zero results.**
5. When the client's content arrives, the **content-fill prompts (C01–C07)** replace data only. No template rework.

## Status of each prompt

| Status | Meaning |
|---|---|
| ✅ READY | Everything needed is known. Run it now |
| 🟡 READY + PROVISIONAL | Builds the full section/page now with provisional copy; a C-prompt swaps in final copy later |
| ⏳ LATER | Written in full once the client's content arrives |

| # | Prompt | Status |
|---|---|---|
| P00 | Project primer: CLAUDE.md, build log, git | ✅ |
| P01 | Scaffold: folders, Composer, Vite, router, redirects, .htaccess | ✅ |
| P02 | Design tokens, base CSS, fonts, icons, image pipeline, styleguide | ✅ |
| P03 | SEO engine: head, schema, sitemaps, robots, llms.txt, content checker | ✅ |
| P04 | Header, glass nav, mega menu, mobile menu + services/sectors data | ✅ |
| P05 | Footer, CTA band, breadcrumbs, back-to-top, dividers, micro-CTA | ✅ |
| P06 | Motion core: Lenis + GSAP + the `data-anim` catalogue | ✅ |
| P07 | Logo SVGs, favicons, pixel2motion splash screen | ✅ |
| P08 | Home hero: structure 1:1 with `hero_should` | 🟡 |
| P09 | Home hero: services slider, glowing border, choreography | 🟡 |
| P10 | Home: "Our Approach to *Winning Contracts*" | ✅ |
| P11 | Home: "Our Services" (3 stacking category cards) | 🟡 |
| P12 | Home: Sectors (pinned horizontal scroll) | 🟡 |
| P13 | Home: Process 01–05 (pinned steps) | ✅ |
| P14 | Home: Why Work With Us bento + logo assembly | ✅ |
| P15 | Home: triage row, testimonials (hidden until supplied), FAQ teaser | 🟡 |
| P16 | Home: Latest Insights, final CTA, home SEO | 🟡 |
| P17 | Inner-page hero component | ✅ |
| P18 | About page part 1: About Us + Our Story | ✅ |
| P19 | About page part 2: Approach, Why Us, Mission & Vision | ✅ |
| P20 | Services hub + category template + 3 category pages | 🟡 (Category 1 final) |
| P21 | Sub-service template + 12 sub-service pages | 🟡 |
| P22 | Sectors hub + sector template + 7 sector pages | 🟡 |
| P23 | Contact page + form backend | ✅ |
| P24 | Quote pre-fill, thank-you page, form polish | ✅ |
| P25 | Insights (blog) engine | ✅ |
| P26 | Article template + migrate the existing post | 🟡 |
| P27 | FAQ page | 🟡 |
| P28 | Legal pages, cookie consent, analytics | 🟡 (legal text needs client approval) |
| P29 | 404, HTML sitemap, utility pages | ✅ |
| P30 | SEO pass | ✅ (run near the end) |
| P31 | Performance pass | ✅ (run near the end) |
| P32 | Accessibility pass | ✅ (run near the end) |
| P33 | Responsive, cross-browser and device polish | ✅ (run near the end) |
| P34 | Security hardening | ✅ (run near the end) |
| P35 | Launch | ✅ (run at launch) |
| C01–C07 | Content-fill prompts | ⏳ |

**Suggested pace:** P00–P07 first (foundation, ~1–2 days), then P08–P16 (home), then P17–P29 (inner pages). P30–P35 run once content is final.

---

## Standard checks (every prompt ends with these; referred to as "Standard checks")
1. `php -l` on every changed PHP file; `npm run build` completes with no errors or warnings.
2. Run the site (`php -S localhost:8000 -t public public/index.php` + `npm run dev`) and open every page touched. **No PHP notices, no console errors, no 404s for assets** (check the Network tab).
3. Check widths **360, 768, 1024, 1440**: no horizontal scroll, nothing clipped.
4. Keyboard-only pass on anything interactive (Tab, Shift+Tab, Enter, Space, Esc): visible focus everywhere.
5. Emulate `prefers-reduced-motion: reduce`: all content visible and readable, no autoplay.
6. View source: content, headings, links and JSON-LD are in the **server HTML**.
7. `php bin/check-content.php` runs (from P03 onwards) and its report is reviewed.
8. Append an entry to `docs/BUILD-LOG.md` (prompt number, files created/changed, decisions, deviations, follow-ups), then **commit** with the message `P##: <title>`.

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

---

## P00 — Project primer: CLAUDE.md, build log, git ✅

**Read first:** `docs/README.md`, `docs/00-LOCKED-DECISIONS.md`, `docs/11-tech-architecture-php.md`, `docs/14-build-roadmap-and-prompts-plan.md`

**Goal:** Prepare the repository so every future session automatically knows the project rules.

**Build:**
1. Create **`CLAUDE.md`** at the project root (≤ 150 lines) containing:
   - One-paragraph project summary (My Solutionz, UK B2B services, custom PHP + GSAP site, docs-first).
   - "Read before any work": `docs/00-LOCKED-DECISIONS.md`, then the docs relevant to the task (include the doc index table from `docs/README.md`).
   - The **Global rules** and **Standard checks** sections from `PROMPTS.md`, copied verbatim.
   - The "structure now, content later" strategy (data files, `PROVISIONAL`, `[[PENDING: …]]`, `status: draft`, `bin/check-content.php`).
   - Commands section (filled in properly by P01): install, dev servers, build, icons, images, content check, SEO audit.
   - Folder map (from `docs/11` §2).
2. Create **`docs/BUILD-LOG.md`** with a short header explaining the format and a first entry for P00.
3. `git init` (if not already a repo) with `main` as the default branch. Create `.gitignore`: `/vendor/`, `/node_modules/`, `/public/assets/build/`, `/storage/logs/*`, `/storage/cache/*`, `/storage/mail/*` (keep `.gitkeep` files), `.env`, `/.venv/`, `/design/logo-motion/outputs/`, `.DS_Store`, `Thumbs.db`.
4. **Commit** the client's source files too (`docs/website_content/*.docx`, `logos/`, `inspirations/`) so the repo is the single source of truth.
5. First commit: `P00: project primer`.

**Do not:** create any application code yet.

**Acceptance:** `CLAUDE.md`, `docs/BUILD-LOG.md`, `.gitignore` exist; `git log` shows the first commit.

---

## P01 — Scaffold: folders, Composer, Vite, router, redirects, .htaccess ✅

**Read first:** `docs/11-tech-architecture-php.md` (all), `docs/08-sitemap-and-page-blueprints.md` §1, `docs/10-seo-specification.md` §2 and §8

**Goal:** A running skeleton: clean URLs, front controller, redirect map, Vite integration, base layout. Every planned route resolves.

**Build:**
1. **Folder structure** exactly as `docs/11` §2 (`public/`, `app/`, `templates/`, `content/`, `src/`, `storage/`, `deploy/`, `bin/`, `scripts/`, `assets-src/`), with `.gitkeep` in empty folders.
2. **Composer** (`composer.json`): PHP `>=8.3`; require `phpmailer/phpmailer`, `league/commonmark`, `spatie/yaml-front-matter`, `vlucas/phpdotenv` (latest stable). PSR-4 autoload `"App\\": "app/lib/"`; `files` autoload for `app/lib/helpers.php`.
3. **npm** (`package.json`, `"type": "module"`): devDependencies `vite`; dependencies `gsap` (≥ 3.13), `lenis`, `@phosphor-icons/core`. Scripts: `dev` (vite), `build` (vite build), `icons` (node scripts/build-icons.mjs; stub for now), `images` (stub for now).
4. **`vite.config.js`**: `publicDir: false` (**critical**: the PHP `public/` folder must not be copied into the build), `build.outDir: 'public/assets/build'`, `build.emptyOutDir: true`, `build.manifest: 'manifest.json'`, `build.rollupOptions.input: 'src/js/main.js'`, `base: '/assets/build/'`, `server: { port: 5173, strictPort: true, origin: 'http://localhost:5173', cors: true }`.
5. **`src/js/main.js`** imports `../css/main.css`; `src/css/main.css` imports `tokens.css`, `base.css` (empty stubs for now).
6. **`public/index.php`** front controller:
   - When running under PHP's built-in server, `return false` for real files so static assets are served.
   - Require `app/bootstrap.php`, then `App\Router::dispatch()`.
7. **`app/bootstrap.php`**: `declare(strict_types=1)`, Composer autoload, Dotenv (`.env`), `APP_ENV` (`local|staging|production`), error display only on local, logging to `storage/logs/app.log`, timezone `Europe/London`, `mb_internal_encoding('UTF-8')`, and a `send_security_headers()` call (basic headers now; CSP in P34). On non-production send `X-Robots-Tag: noindex, nofollow`.
8. **`app/config/site.php`** (returns an array): `name` "My Solutionz", `legal_name` "My Solutionz Ltd", `company_number` "15677749", `registered_in` "England and Wales", `founded` "2024-04-24", `tagline` "Solutions Crafted for Success", `url` from `.env` `SITE_URL` (production: `https://mysolutionz.co.uk`), `email` info@mysolutionz.co.uk, `phone_display` "+44 7466 063564", `phone_e164` "+447466063564", `whatsapp` "https://wa.me/447466063564", `address` (`street` "Unit A53, 4–6 Greatorex Street", `locality` "London", `postcode` "E1 5NF", `country` "GB"), `hours` `[[PENDING: office hours]]` with provisional Mon–Fri 09:00–17:30 (`// PROVISIONAL`), `socials` (LinkedIn `https://www.linkedin.com/company/mysolutionz/`, Instagram `https://www.instagram.com/mysolutionz/`, Facebook `https://www.facebook.com/profile.php?id=61558654196971`), `locale` "en-GB".
9. **`app/lib/Router.php`**, applied in this order:
   1. Normalise: 301 uppercase paths to lowercase; 301 to a trailing slash for page paths (not for paths with a file extension such as `.xml`, `.txt`).
   2. Check `app/redirects.php` (exact-match map + regex rules) → **one-hop 301** or **410**.
   3. Match routes from `app/routes.php`: exact paths and patterns `/services/{category}/`, `/services/{category}/{sub}/`, `/sectors/{slug}/`, `/insights/{slug}/`, `/insights/category/{slug}/`, `/insights/page/{n}/`.
   4. Otherwise render the 404 template with status **404**.
   - If a route's template file doesn't exist yet: on `local` render a dev stub ("Template not built yet: built by P##", 200, noindex); on production render 404.
10. **`app/routes.php`**: every URL from `docs/08` §1: `/`, `/about-us/`, `/services/`, the 3 category and 12 sub-service paths, `/sectors/` + 7 sectors, `/insights/…`, `/faq/`, `/contact-us/`, `/thank-you/`, `/privacy-policy/`, `/cookie-policy/`, `/terms-and-conditions/`, `/accessibility/`, `/sitemap/`, `/sitemap.xml`, `/robots.txt`, `/llms.txt`, `/insights/feed.xml`. Local-only: `/styleguide/`, `/styleguide/motion/`.
11. **`app/redirects.php`**: the complete map from `docs/10` §2. Exact 301s for `/our-services/`, `/service/`, the 6 `/service/*` pages, `/service-category/*`, the vocabulary blog post, `/category/health-care/`, `/feed/`, `/comments/feed/`, Yoast sitemaps, `/about/`, `/contact/`, `/page/2/`, and the privacy PDF path. 410 rules for `^/pxl-template/`, `^/tag/`, the diabetes post, `/author/developer/`, `/case-studies/`, `/careers/`, the empty test categories, `^/attachment/`, `/wp-admin`, `/wp-login.php`, `/wp-json`. Implement 410 with a minimal "This page has been removed" template.
12. **`app/lib/helpers.php`**: `e()`, `config($key)`, `url($path)` (absolute, trailing slash), `asset($path)` (with `?v=` file-mtime), `vite($entry)`:
    - **local with `VITE_DEV=true`** → print `@vite/client` + the entry from `http://localhost:5173`;
    - **otherwise** → read `public/assets/build/manifest.json` and print hashed `<link rel="stylesheet">` + `<script type="module">`.
    Also `is_active($path)`, `render($template, $vars)` (output-buffered include), and `icon()` (stub until P02).
13. **Templates:** `templates/layouts/base.php` (doctype, `<html lang="en-GB" class="no-js">`, head partial stub, skip link, header/footer partial stubs, `<main id="main">`), `templates/pages/home.php` with an `<h1>` placeholder, `templates/pages/404.php`, `templates/pages/410.php`.
14. **`public/.htaccess`** (works on LiteSpeed and Apache):
    - One-hop redirect to `https://mysolutionz.co.uk%{REQUEST_URI}` when HTTPS is off **or** the host starts with `www.`. **Skip** for `localhost`/`127.0.0.1`/staging hosts.
    - Front-controller rewrite for non-files.
    - `Options -Indexes`.
    - Compression (`mod_deflate` / Brotli if available).
    - `Cache-Control: public, max-age=31536000, immutable` for `/assets/build/` and fonts; short cache for HTML.
    - Basic security headers.
15. **`deploy/nginx.conf.example`** with the equivalent rules.
16. **`.env.example`**: `APP_ENV=local`, `SITE_URL=http://localhost:8000`, `VITE_DEV=true`, `MAIL_DRIVER=log`, SMTP placeholders, `ANALYTICS_PROVIDER=`.
17. **`.claude/launch.json`** for the Claude desktop preview:
    ```json
    { "version": "0.0.1", "configurations": [
      { "name": "php", "runtimeExecutable": "php", "runtimeArgs": ["-S","localhost:8000","-t","public","public/index.php"], "port": 8000 },
      { "name": "vite", "runtimeExecutable": "npm", "runtimeArgs": ["run","dev"], "port": 5173 } ] }
    ```
18. Fill in the **Commands** section of `CLAUDE.md`.

**Acceptance:**
- `composer install` and `npm install` succeed. Both servers run and `/` renders the base layout with a Vite-loaded stylesheet.
- `/About-Us` → 301 to `/about-us/`. `/about-us` → 301 to `/about-us/`. `/service/tender-writing/` → 301 to `/services/bid-proposal-management/tender-bid-writing/` (one hop).
- `/pxl-template/header/` → 410. `/nope/` → 404 with status 404.
- Each planned route returns the dev stub. `npm run build` produces `public/assets/build/manifest.json`, and with `VITE_DEV=false` the hashed assets load.
- Standard checks.

**Do not:** add CSS styling beyond stubs, or install any UI framework.

---

## P02 — Design tokens, base CSS, fonts, icons, image pipeline, styleguide ✅

**Read first:** `docs/02-brand-identity.md` (all), `docs/03-design-system-apple.md` (all), `docs/12-performance-accessibility.md` §2 and §4

**Goal:** The complete visual foundation: tokens, typography, fonts, icon sprite, image helper and foundation components, all visible on a local-only styleguide page.

**Build:**
1. **`src/css/tokens.css`** contains every token from `docs/02` §2 and `docs/03` §2, as CSS custom properties on `:root`:
   - Colour scales: Azure 50–950 incl. **650 `#0072BC`**, Navy 50–950, Sky 50–950.
   - Neutrals (`--ms-canvas #F5F7FB`, `--ms-ink #0B1220`, `--ms-ink-2 #5B6474` …), semantic colours, signature gradients (incl. the `@property --angle` registration for the glow border).
   - Category colours: `--cat-bid: var(--azure-500)`, `--cat-compliance: var(--navy-700)`, `--cat-digital: var(--sky-300)` plus an on-light variant `--cat-digital-ink: var(--sky-700)`.
   - Spacing, layout, radii, shadows, glass materials, motion durations/easings, and the fluid type scale (`clamp()` values from `docs/02` §3).
2. **`src/css/base.css`**:
   - Modern reset; `html { scroll-padding-top: var(--header-h) }`; `body { background: var(--ms-canvas); color: var(--ms-ink) }`.
   - Typography: headings use Mona Sans with tracking per the scale; body uses Google Sans; **`h1 em, h2 em, h3 em`** use Playfair Display Italic 500, Azure 500, gradient text at ≥ 48px (the two-voice heading).
   - Links: Azure 700; `:focus-visible` ring per tokens; `::selection` in Azure 100.
   - Utilities: `.container`, `.container--wide`, `.container--text`, `.section` (padding `--section-y`), `.visually-hidden`, `.skip-link`.
   - Media queries: `prefers-reduced-motion` (kill transitions), `prefers-reduced-transparency` (solid surfaces), `prefers-contrast: more` (stronger borders).
3. **Fonts** (self-hosted, OFL):
   - **Mona Sans** variable (weights 500–700).
   - **Google Sans** (400, 500; italic 400 optional).
   - **Playfair Display** Italic 500 only.
   - Obtain them from Fontsource npm packages if they exist (check with `npm view <package>` first) or download WOFF2 from Google Fonts / the official GitHub release. Subset to Latin (`pyftsubset` or `glyphhanger`), and save to `public/assets/fonts/` with clear names (`mona-sans-var.woff2`, `google-sans-var.woff2`, `playfair-display-italic-500.woff2`).
   - `src/css/fonts.css` declares `@font-face` with `font-display: swap`, plus **metric-matched fallback faces** (`size-adjust`, `ascent-override`, `descent-override`, `line-gap-override`) computed for Arial/Segoe UI, so the swap causes no layout shift.
   - Preload Mona Sans + Google Sans in the head (P03 head partial).
4. **Icons**:
   - `src/icons.json` lists every icon name + weight used on the site: every icon in `docs/09` §4, §5 and §6, plus UI icons (`caret-down`, `caret-right`, `list`, `x`, `arrow-right`, `arrow-up-right`, `arrow-up`, `plus`, `minus`, `phone`, `whatsapp-logo`, `envelope-simple`, `map-pin`, `clock`, `share-network`, `linkedin-logo`, `instagram-logo`, `facebook-logo`, `check-circle`, `magnifying-glass`, `play`, `pause`).
   - `scripts/build-icons.mjs` reads the SVGs from `node_modules/@phosphor-icons/core/assets/<weight>/` (**inspect the real file naming first**), converts each to a `<symbol id="<name>-<weight>" viewBox="0 0 256 256">`, and writes `public/assets/icons/sprite.svg` (minified).
   - Run it via `npm run icons`, and also automatically before `build`.
   - PHP `icon(string $name, string $weight = 'light', array $attrs = [])` prints `<svg class="icon" width="24" height="24" aria-hidden="true" focusable="false"><use href="/assets/icons/sprite.svg?v=…#name-weight"></use></svg>`. If `$attrs['label']` is set, use `role="img"` + `<title>` instead of `aria-hidden`.
   - Unknown icon on local → throw.
5. **Image pipeline**:
   - `scripts/images.mjs` (dependency `sharp`) reads `assets-src/images/**/*.{jpg,png,webp}` and outputs to `public/assets/img/<same path>/` AVIF + WebP + JPEG at widths `[400, 800, 1200, 1600, 1920]` (never upscaling).
   - It writes `public/assets/img/manifest.json` (intrinsic width/height + generated widths).
   - `app/lib/Image.php` → helper `img(string $key, array $o)` prints a `<picture>` (AVIF → WebP → JPEG `<img>`) with `srcset`, `sizes` (default `100vw`), `width`/`height`, `alt` (**required**: throw on local if missing; `''` allowed only with `decorative => true`), `loading="lazy" decoding="async"` unless `priority => true` (then `fetchpriority="high"`, no lazy).
   - **If the key isn't in the manifest**, print a **branded placeholder** instead: an inline SVG at the requested aspect ratio (`ratio => '16/9'`) using a navy→azure gradient, a faint hexagon pattern and a centred small label `[[PENDING IMAGE: <key>]]`. This lets every layout be built before real photos exist.
6. **Foundation components** (`templates/components/` + `src/css/components/`), each a small PHP partial with documented parameters:
   - **C01 eyebrow** (hex bullet + uppercase 13px, +0.12em).
   - **C02 two-voice heading** `heading($level, $text, $accent)` → `<h2>Our Approach to <em>Business Growth</em></h2>`.
   - **C03 buttons**: primary (Azure 650 → Navy 700 hover), secondary outline, glass (for dark backgrounds), **split button** (label + 44×44 white tile with `arrow-up-right`), text link with sliding arrow. All 48px high with 44px minimum targets.
   - **C07 hex icon tile**: 64px rounded hexagon via `mask-image` of an inline SVG rounded-hexagon data URI; Azure 50 fill, Azure 600 icon; `--tile-colour` variable for category colouring; hover fills with the brand gradient and the icon turns white.
   - **Chips/pills**: neutral, outline, category-coloured.
   - **C14 avatar stack**.
   - **Glass utilities**: `.glass-thin-dark`, `.glass-regular-light`, `.glass-thick-dark`, each with reduced-transparency fallbacks.
7. **Styleguide** at `/styleguide/` (registered only when `APP_ENV=local`; `noindex`) showing:
   - all colour swatches with their hex values and contrast vs white/ink;
   - the type scale and a two-voice heading demo;
   - all buttons in all states;
   - every icon in the sprite (grid with names);
   - hex tiles in the 3 category colours, chips, avatar stack, glass panels on a dark gradient;
   - an image placeholder example.

**Acceptance:**
- The styleguide renders with the correct fonts loaded from `/assets/fonts/`. **Zero third-party requests** in the Network tab.
- Contrast: body text ≥ 4.5:1; Azure 500 is not used for small text anywhere.
- `npm run icons` regenerates the sprite. `img()` placeholder works.
- Standard checks.

**Do not:** use any icon font, Google Fonts URL, or hard-coded hex outside `tokens.css`.

---

## P03 — SEO engine: head, schema, sitemaps, robots, llms.txt, content checker ✅

**Read first:** `docs/10-seo-specification.md` (all), `docs/13-uk-legal-compliance.md` §2, `docs/11` §4

**Goal:** Every page automatically gets a complete, validated `<head>` and JSON-LD; machine files are generated from the route/data tables; a checker enforces the rules.

**Build:**
1. **Page contract.** Each template sets `$page` with:
   - `title`, `description`, `canonical` (path), `og_title?`, `og_description?`, `og_image?`, `og_image_alt?`, `og_type` (`website|article`);
   - `robots?`, `status` (`published|draft`), `breadcrumbs` (array of `[label, path|null]`), `schema` (array of graph nodes);
   - `published?`, `modified?`, `body_class`, `js_modules`.
   - `app/lib/Page.php` normalises it and applies defaults from `config/site.php`.
2. **`app/lib/Seo.php`**:
   - Validates the contract on local. For **published** pages, throw if title/description/canonical is missing, title is outside 30–65 chars, description is outside 70–170 chars, or the canonical isn't absolute https with a trailing slash in production. For **draft** pages, log the same problems as warnings (shown in the draft ribbon) instead of throwing.
   - Renders `templates/partials/head-seo.php` in the exact order of `docs/10` §7.
   - Draft pages and non-production environments are forced to `noindex, follow`.
   - Canonical is built from `SITE_URL` + path, with the query string stripped.
3. **`app/lib/Schema.php`** builds one `@graph` per page with stable `@id`s:
   - `organization()`: `#organization`, name, legalName, url, logo (512px PNG, created in P07, path from config), description, email, telephone, address (`PostalAddress`, GB), areaServed GB, contactPoint, sameAs (3 socials), foundingDate `2024-04-24`, `identifier` (PropertyValue "Companies House" 15677749), knowsAbout. Emitted as `Organization` (switch to `LocalBusiness` via config if the office is confirmed as staffed).
   - `website()`: name, alternateName ["MySolutionz", "mysolutionz.co.uk"], publisher, inLanguage `en-GB`. **No SearchAction.**
   - `webPage($type = 'WebPage')` (also `AboutPage`, `ContactPage`, `CollectionPage`), with isPartOf, about, `primaryImageOfPage` when set, inLanguage.
   - `breadcrumbs()`; `service()`; `serviceCategory()` with `hasOfferCatalog`; `blogPosting()`; `faqPage()` (optional, off by default).
   - Output with `json_encode(..., JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)`.
   - The home page includes organization + website + webPage; every other page includes webPage + breadcrumbs + its type, referencing `#organization` by `@id`.
4. **Machine files** (controllers in `app/controllers/`):
   - `/sitemap.xml`: sitemap index → `/sitemap-pages.xml`, `/sitemap-services.xml`, `/sitemap-sectors.xml`, `/sitemap-insights.xml`. Only **published, indexable, 200** URLs. `lastmod` = the page's `modified` date from data or the template/data file mtime (accurate, never "now"). Optional `<image:image><image:loc>` for each page's primary image.
   - `/legacy-sitemap.xml`: all old URLs from `app/redirects.php` (exact-match keys), enabled by config flag `legacy_sitemap` (default on until 6 weeks after launch).
   - `/robots.txt`: production per `docs/10` §8 (allow all, disallow `/thank-you/`, sitemap line; AI crawler block lines present but commented, with a config switch `block_ai_training`); non-production → `Disallow: /`.
   - `/llms.txt`: generated from config + services data (company summary, the 3 service categories with URLs, contact page).
5. **`bin/check-content.php`** (CLI) prints a table of:
   - every `[[PENDING`, `// PROVISIONAL` and `status => 'draft'` occurrence (file + line);
   - every route's title/description lengths and duplicates;
   - images missing from the image manifest;
   - pages without breadcrumbs (except home).

   Exit code 1 if `--strict` is passed and anything is found (used at launch).
6. **`bin/seo-audit.php`** (CLI) requests every published route from the local server and checks:
   - exactly one `<h1>`;
   - title/description/canonical/og:image/og:image:alt/twitter:card are present;
   - JSON-LD parses as valid JSON;
   - every `<img>` has `alt`, `width` and `height`;
   - no `href="#"` dummy links.
7. **Default OG image:** `public/assets/og/default.jpg`, 1200×630. Build it from the brand gradient + logo mark + tagline using `sharp` in `scripts/og.mjs` (generalised in P30). The logo mark SVG comes from P07; if P07 isn't done yet, use a temporary text-only version and leave a note in BUILD-LOG.
8. Apply the head partial to the base layout. Add `<meta name="theme-color" content="#011B43">` and the favicon links (files created in P07).

**Acceptance:**
- View-source of `/` shows the full head in the correct order with valid JSON-LD (paste into validator.schema.org: no errors).
- `/sitemap.xml`, `/robots.txt`, `/llms.txt` and `/legacy-sitemap.xml` respond with correct content types.
- Draft pages show `noindex`. `php bin/check-content.php` and `php bin/seo-audit.php` run.
- Standard checks.

---

## P04 — Header, glass navigation, mega menu, mobile menu + services/sectors data ✅

**Read first:** `docs/08` §2, `docs/04` §1.0–1.1, `docs/05` §2 (nav row), `docs/03` C04–C06, `docs/06` §4, `docs/12` §5

**Goal:** The complete navigation system, driven by data, fully accessible, with the Innovax glass look.

**Build:**
1. **`app/data/services.php`** is the single source of truth for services. It returns an ordered array of 3 categories. Each category has `slug`, `name`, `short`, `colour` (`bid|compliance|digital`), `icon`, `summary`, `status`, `url` and `subs` (ordered); each sub has `slug`, `name`, `icon`, `summary`, `status`, `url`. Use exactly this content:

   | Category (colour, icon) | Summary | Status |
   |---|---|---|
   | **Bid & Proposal Management** (`bid`, `kanban`) | "From finding the right contracts to final submission, we manage your tenders so your team can focus on running the business." | published |
   | **Compliance & Virtual Assistance** (`compliance`, `seal-check`) | "Practical compliance and administrative support, from CQC registration and policies to care plans and data management." `// PROVISIONAL` | draft |
   | **Digital Presence** (`digital`, `devices`) | "Websites, social media, email marketing and search that help buyers and customers find and trust your business." `// PROVISIONAL` | draft |

   Sub-services (all `draft` with `// PROVISIONAL` summaries until their content prompt runs):

   | Category | Sub-service (slug, icon) | Summary |
   |---|---|---|
   | Bid | Tender & Bid Writing (`tender-bid-writing`, `pen-nib`) | "Evidence-led responses written to score." |
   | Bid | Opportunity Identification (`opportunity-identification`, `binoculars`) | "The right contracts, found and qualified." |
   | Bid | Expert Review (`expert-review`, `list-checks`) | "An evaluator's-eye review before you submit." |
   | Compliance | CQC Registration (`cqc-registration`, `shield-check`) | "Guidance and documents for a smooth registration." |
   | Compliance | Policy Management (`policy-management`, `files`) | "Clear, current policies that stand up to scrutiny." |
   | Compliance | Carbon Reduction Plan (`carbon-reduction-plan`, `leaf`) | "Carbon Reduction Plans for public sector bids." |
   | Compliance | Data Entry & Management (`data-entry-management`, `database`) | "Accurate records, organised and up to date." |
   | Compliance | Care Plans (`care-plans`, `clipboard-text`) | "Person-centred care plans, professionally written." |
   | Digital | Website Development (`website-development`, `browser`) | "Fast, modern websites that build trust." |
   | Digital | Social Media Management (`social-media-management`, `share-network`) | "A consistent, professional social presence." |
   | Digital | Email Marketing (`email-marketing`, `envelope-open`) | "Campaigns that keep you front of mind." |
   | Digital | SEO & Analytics (`seo-analytics`, `chart-line-up`) | "Be found online and measure what matters." |

   URLs: `/services/<category>/` and `/services/<category>/<sub>/`. Add helper functions `services()`, `service_category($slug)`, `service_sub($cat, $sub)`.
2. **`app/data/sectors.php`**: 7 sectors (`slug`, `name`, `icon`, `summary // PROVISIONAL`, `status: draft`):

   | Sector (slug, icon) | Summary |
   |---|---|
   | Healthcare & Social Care (`healthcare-social-care`, `first-aid-kit`) | "Tenders for domiciliary care, supported living, residential and NHS-commissioned services." |
   | Transport (`transport`, `bus`) | "Bids for passenger, patient and logistics transport contracts." |
   | Cleaning (`cleaning`, `broom`) | "Commercial, healthcare and education cleaning contracts." |
   | Education & Training (`education-training`, `graduation-cap`) | "Training provision, apprenticeships and education support services." |
   | Security (`security`, `shield-star`) | "Manned guarding, mobile patrols and event security contracts." |
   | Facilities Management (`facilities-management`, `buildings`) | "Integrated and soft FM contracts across public and private estates." |
   | Building Maintenance (`building-maintenance`, `wrench`) | "Planned and reactive maintenance and repair contracts." |

3. **`app/data/navigation.php`**: Home, About (`/about-us/`), Services (mega), Sectors (dropdown), Insights (`/insights/`), Contact (`/contact-us/`).
4. **Header partial** `templates/partials/header.php` with two variants:
   - **`hero`** (home only, rendered *inside* the hero panel by P08): logo left (white version; temporary text logo until P07), centred links with an **active dot** above the current item (Azure 500), right side phone link (`phone` icon + "+44 7466 063564", hidden < 1024px) + **"Get a Free Quote"** glass pill → `/contact-us/#quote`.
   - **`default`** (all inner pages): logo left; **Innovax glass pill nav** (container `background: rgba(255,255,255,.05)`, `backdrop-filter: blur(15px)`, `border: 1px solid rgba(255,255,255,.06)` → use the matching tokens; radius 8–12px; links 14–15px/500 UPPERCASE, `padding: 12px 15px`), with a **single white "active pill"** element that sits behind the current link and **slides** to the hovered/focused link (`gsap.quickTo` on x/width, 250ms `--ease-apple`); CTA on the right.
5. **Sticky behaviour** (`src/js/modules/header.js`):
   - After 80px of scroll the header becomes `glass-thick-dark` (or `glass-regular-light` with dark text when `data-header-theme="light"` sections are under it).
   - Shrinks 88 → 68px.
   - **Hides on scroll down, reveals on scroll up** (`yPercent: -100 ↔ 0`, 0.4s); never hides while focus is inside it or a menu is open.
   - Updates `--header-h`.
6. **Mega menu (Services)**:
   - Trigger is a `<button aria-expanded aria-controls>` next to the Services link (the link itself goes to `/services/`).
   - Panel uses `glass-thick-dark` and `--shadow-3`, max-width `--container`.
   - **3 columns** (one per category: coloured hex tile + category name → category page, then sub-service links with icon + name + summary), plus a **4th column featured card**: "Have a tender deadline coming up?" + "Get a Free Quote" button. Later P16 can switch this to the latest insight.
   - Opens on hover-intent (150ms delay) **and** on click/Enter/Space; closes on Esc (focus returns to the trigger), outside click, and focus leaving the panel.
   - Animation: panel `autoAlpha` + y 8 → 0 (250ms); columns stagger 0.04.
7. **Sectors dropdown**: same pattern, a single column of the 7 sectors.
8. **Mobile (< 1024px)**:
   - Burger button (`list` icon, 44×44, `aria-label="Open menu"`) opens a **full-screen navy sheet**.
   - Contents: big 28px links; accordions for Services (categories → subs) and Sectors; contact block (phone, WhatsApp, email); socials.
   - Behaviour: items stagger in; focus trap; Esc closes; body scroll locked (`lenis.stop()` if Lenis is running); close button returns focus to the burger.
9. **Mobile sticky action bar** (< 768px): Call · WhatsApp · Get a Quote, fixed at the bottom. Appears after scrolling past the first viewport (hero) and respects the safe-area inset.
10. **Skip link** "Skip to main content" as the first focusable element.

**Acceptance:**
- All menus are fully operable by keyboard and screen reader (`aria-expanded` toggles, focus never lost).
- No layout shift when the header changes state. Glass falls back to solid with reduced transparency.
- Mega menu content comes 100% from `app/data/services.php`.
- At 360px the mobile sheet works and the action bar doesn't cover content (page bottom padding added).
- Standard checks.

---

## P05 — Footer, CTA band, breadcrumbs, back-to-top, dividers, micro-CTA ✅

**Read first:** `docs/08` §3, `docs/03` C21–C25, C29, `docs/04` §6, `docs/06` §6 (S2, S10), `docs/13` §1

**Goal:** All site-wide closing components.

**Build:**
1. **Footer** `templates/partials/footer.php` on Navy 900, with a grid-wave art strip on the top edge (SVG recreation of `inspirations/WhatsApp Image 2026-10-12 at 9.34.03 PM.jpeg` in azure/sky tints, low contrast):
   - Col 1: logo (reversed; text fallback until P07), tagline "Solutions Crafted for Success", 2-line description "Tender writing, bid management, compliance and digital support for UK businesses." `// PROVISIONAL`, social icon buttons (44px, with labels).
   - Col 2: **Services**: each category link followed by its sub-services (smaller, Navy 200), from `services.php`.
   - Col 3: **Sectors** (7 links).
   - Col 4: **Contact**: `<address>` with the full address, phone (`tel:`), WhatsApp, email (`mailto:info@mysolutionz.co.uk`), office hours (from config; shows provisional hours), "Get a Free Quote" button.
   - **Bottom bar:** `© {current year} My Solutionz Ltd. Registered in England and Wales, company no. 15677749. Registered office: Unit A53, 4–6 Greatorex Street, London E1 5NF.` + links Privacy · Cookies · **Cookie settings** (a `<button data-cookie-settings>`; the consent module in P28 listens for it) · Terms · Accessibility · Sitemap.
2. **CTA band** component `cta-band.php`:
   - Params: `eyebrow`, `heading`, `accent`, `text`, `primary` [label, url], `secondary` [label, url] (default phone), `show_whatsapp`.
   - Visual: Navy 900 → Azure 800 gradient background with an animated grid-wave layer, plus a large faded **logo mark** at the right that rises and rotates on scroll (S10; uses the mark SVG from P07, with a simple hexagon as a temporary stand-in).
   - Default copy: eyebrow "Let's talk", heading "Ready to Win Your" + accent "Next Contract?", text "Tell us about the opportunity and your deadline. We'll explain how we can help and give you a clear, no-obligation quote.", buttons "Get a Free Quote" + "Call +44 7466 063564".
3. **Breadcrumbs** component: reads `$page['breadcrumbs']`, `<nav aria-label="Breadcrumb"><ol>`, current page `aria-current="page"`, `caret-right` separators, white or ink variants. Schema comes from P03.
4. **Back-to-top**: fixed bottom-right square button (Azure 650, radius 12, `arrow-up` icon) with a circular **scroll-progress ring** (SVG `stroke-dashoffset`). Appears after 1 viewport and scrolls via Lenis/native smooth.
5. **Section dividers** `divider.php`:
   - variants `wave` (SVG wave, fill = next section colour) and `diagonal` (120° brand angle);
   - `data-anim="wave"` hook for P06.
6. **Micro-CTA row** `micro-cta.php`:
   - Text left ("Not sure which service you need?"), a large arrow icon in the middle (`data-anim="micro-arrow"`; it starts pointing right), button right ("Let's talk" → contact).
   - Thin hairlines above and below.

**Acceptance:**
- The footer disclosure text matches exactly. Every footer link resolves (stubs are fine).
- On mobile the footer stacks cleanly and links are 44px-tappable.
- Standard checks.

---

## P06 — Motion core: Lenis + GSAP + the `data-anim` catalogue ✅

**Read first:** `docs/06-motion-and-scroll.md` (all, especially §1, §3, §5, §5b, §6), `docs/03` §2.6, `docs/12` §3, `docs/10` §9

**Goal:** One motion system for the whole site. Templates only add `data-anim="…"` / `data-module="…"` attributes; JS does the rest. Reduced-motion and SEO-safe by design.

**Build:**
1. **`src/js/main.js`** boot sequence:
   1. `document.documentElement.classList` already has `js` (inline script from P03).
   2. Import `gsap`, `ScrollTrigger`, `CustomEase`; register plugins.
   3. Create eases from tokens: `apple` `0.28,0.11,0.32,1` · `out` `0.16,1,0.3,1` · `inout` `0.65,0,0.35,1` · `spring` `0.34,1.56,0.64,1`.
   4. `const reduce = matchMedia('(prefers-reduced-motion: reduce)')`.
   5. **Lenis** (only if not reduced): `new Lenis({ lerp: 0.1, smoothWheel: true })` (native touch scrolling); `lenis.on('scroll', ScrollTrigger.update)`; `gsap.ticker.add(t => lenis.raf(t * 1000))`; `gsap.ticker.lagSmoothing(0)`; import `lenis/dist/lenis.css`. **Hash rule:** if `location.hash` exists on load, let the browser jump natively first, then init Lenis. Anchor links use `lenis.scrollTo(target, { offset: -headerHeight })` **and** update the hash via `history.pushState` (never break back/forward; no scroll hijack on load; `history.scrollRestoration` stays `auto`). Expose `window.__lenis` for other modules.
   6. **Module loader:** find `[data-module]` elements and dynamic-`import()` `./modules/<name>.js` (code-split); find `[data-anim]` and initialise effects from `./anim/*.js` inside one `gsap.matchMedia()` context with conditions `{ desktop: '(min-width: 1024px)', mobile: '(max-width: 1023px)', reduce: '(prefers-reduced-motion: reduce)' }`.
   7. `document.fonts.ready.then(() => ScrollTrigger.refresh())`; refresh again after lazy images inside pinned sections load.
2. **Effects catalogue** in `src/js/anim/`. Implement **every** effect in `docs/06` §3 with the exact specs listed there: `fade-up`, `stagger`, `split-lines`, `split-chars`, `two-voice`, `parallax` (`data-speed`), `img-reveal`, `img-zoom-out`, `counter` (`data-to`, `data-suffix`), `fill-text`, `draw`, `pin-steps`, `h-scroll`, `stack-cards`, `marquee` (seamless horizontal loop helper, velocity-aware, pause on hover/focus, **plus a pause button for WCAG 2.2.2**), `magnetic`, `tilt`, `wave`, `velocity-skew`, `read-along` (chars opacity **0.2 → 1** scrubbed; **never from 0**).

   Also the strategicproposals patterns from `docs/06` §6:
   - `logo-assembly` (S1: 3 SVG pieces fly in and assemble, scrubbed `start: "top bottom", end: "top 35%"`);
   - `micro-arrow` (S2: rotate −90° → 0° at 50–60% progress, then drop 88px);
   - `blur-zoom` (S3, desktop only);
   - `ring` (S4: SVG ring + count-up);
   - `rise-rotate` (S10).
   - Use `SplitText` with `mask: 'lines'` and `autoSplit: true` (GSAP 3.13+) for line reveals.
3. **SEO-safe rules (mandatory):**
   - Entrance reveals use `once: true` and start no lower than `top 85%`.
   - Hidden initial states are set **only** via JS (`gsap.set`) or `html.js` CSS, so no-JS shows everything.
   - Never animate the hero H1 or LCP image from `opacity: 0`.
   - Reduced motion: **skip all effects** and leave content in its final state (no pinning, no h-scroll: stacked layout instead; marquee becomes a static wrapped list).
4. **Performance:** animate only transform/opacity/clip-path/filter; `will-change` only during tweens; `ScrollTrigger.batch` for card grids. Pinned sections must create their pin-spacers on init (no CLS). Mobile halves parallax distances and disables `tilt`, `magnetic`, `velocity-skew` and `h-scroll` (use a swipe row instead).
5. **`nodes` module** (`src/js/modules/nodes.js`): canvas network-node background (navy-200 lines, azure nodes, slow drift, gentle pointer repulsion). Pauses via IntersectionObserver and `visibilitychange`, caps at 30fps on mobile, and falls back to a static SVG when `navigator.hardwareConcurrency <= 4`, `Save-Data` is on, or motion is reduced.
6. **Motion styleguide** at `/styleguide/motion/` (local only): one labelled demo block per effect (with real-looking placeholder content, no lorem), so each can be tested in isolation.

**Acceptance:**
- Every effect works on the motion styleguide at 1440 and 360.
- With reduced motion, everything is static and fully visible.
- With JS disabled, every page is fully readable.
- No long task > 50ms on init (Performance panel).
- Hash links (`/about-us/#our-story`) land correctly and the back button works.
- Standard checks.

---

## P07 — Logo SVGs, favicons, pixel2motion splash screen ✅

**Read first:** `docs/07-splash-screen-logo-animation.md` (all), `docs/02` §1, `docs/05` §6

**Goal:** Crisp vector logo assets now (no need to wait for the client's vector), all favicons, and the first-visit splash animation made with pixel2motion.

**Build, part A: logo SVGs**
1. **Logo mark** `public/assets/brand/logo-mark.svg`. These coordinates were measured from the client's 1563px logo (≈ 97–99% pixel overlap):
   ```svg
   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 366">
     <polygon id="hex-sky"   fill="#8DD6FF" points="0,274 53,182 160,182 213,274 160,366 53,366"/>
     <polygon id="bar-azure" fill="#009AF0" points="106,89 158,0 268,0 426,275 374,366 265,366"/>
     <polygon id="bar-navy"  fill="#003C8C" points="320,89 371,0 481,0 640,275 587,366 478,366"/>
   </svg>
   ```
   (Paint order matters: sky, then azure, then navy.)
2. **Wordmark.** The logo text "My Solutionz" and tagline "Solutions Crafted for Success" are set in a condensed, squared typeface that is very likely **Saira Condensed** (OFL, Google Fonts):
   - Download it for **build-time use only** (it is not loaded on the website).
   - Match weight and letter-spacing against `logos/My Solution Horizontal.jpg (1).jpeg` by rendering overlays until they visually match.
   - Convert the text to **outlined paths** (e.g. a small Node script with `opentype.js`).
3. Produce:
   - `logo-horizontal.svg` (mark left, wordmark + tagline right, as in the horizontal JPEG);
   - `logo-horizontal-white.svg` (wordmark white; navy bar lightened to Navy 400 so it stays visible on dark);
   - `logo-vertical.svg`;
   - `logo-512.png` (mark + wordmark on white, for schema);
   - `logo-mark-white.svg`.

   All have a `<title>My Solutionz</title>` and semantic ids (`#mark`, `#hex-sky`, `#bar-azure`, `#bar-navy`, `#wordmark`, one element per letter with class `letter`, and `#tagline`).
4. Replace the temporary text logos in the header, footer and CTA band with the SVGs (inline via `inline_svg()` helper so the parts can animate).

**Build, part B: favicons** (`docs/10` §7, Google-compatible)
5. Generate from the mark:
   - `public/favicon.ico` (16/32/48, e.g. `png-to-ico`);
   - `icon-192.png`, `icon-512.png`, `apple-touch-icon.png` (180×180, mark on white with padding);
   - `icon.svg` (with a `prefers-color-scheme: dark` style block);
   - `site.webmanifest` (name, short_name "My Solutionz", icons, `theme_color #011B43`, `background_color #F5F7FB`).

**Build, part C: splash via pixel2motion**
6. Install the skill into this project:
   - `git clone https://github.com/nolangz/pixel2motion .claude/skills/pixel2motion`
   - Python venv in `.venv` with `pillow numpy playwright`, then `python -m playwright install chromium`.
   - Set `CHROME_BIN` to the local Chrome path on Windows.
   - Read the skill's `SKILL.md` and `references/` fully before starting.
7. Run the pixel2motion workflow with:
   - **source:** `logos/Vertical Lockup on White Background.jpg (1).jpeg`;
   - **usage context:** splash/intro;
   - **personality words:** engineered · confident · ascending;
   - **preset:** Trustworthy/Professional blended with Elegant overshoot 1.02, squash 0;
   - **geometry:** reuse the SVG from part A (complexity ladder level 1, with the ids above) instead of re-tracing the mark;
   - **choreography target:** the timeline table in `docs/07` §3 (≈ 1.6s).

   Outputs go to `design/logo-motion/` (`logo.svg`, `motion.css`, `logo_motion.html`, `motion_spec.md`, QA strips). Complete the skill's QA, including the `?static=1` vs `?t=<end>` zero-diff Final Frame Contract.
8. ⛔ **STOP and ask the user to open `design/logo-motion/logo_motion.html` and approve the animation** before integrating. Apply any requested changes.
9. Integrate (`docs/07` §4–§5):
   - `templates/partials/splash.php`: `#splash` overlay on `--ms-canvas`, `aria-hidden="true"`, rendered only when `html.js` and not reduced motion;
   - `src/css/splash.css`: the skill's `motion.css` scoped to `#logo-root` (literal cubic-beziers inside keyframes), wrapped in `@media (prefers-reduced-motion: no-preference)`, plus a **CSS-only failsafe** that fades the overlay out at 2.6s;
   - `src/js/modules/splash.js`:
     - plays **once per session** (`sessionStorage['ms_splash']`);
     - is skippable with click, tap, Esc, Enter or Space;
     - waits for `getAnimations({ subtree: true })` to finish, then runs the **GSAP Flip hand-off** (mark + wordmark fly to the header logo) and splits the overlay along the **120° diagonal** into two panels sliding apart;
     - dispatches a `splash:done` event (the hero entrance in P09 listens for it, or starts immediately when there's no splash);
     - removes the overlay from the DOM;
     - hard cap: gone by 2.4s.
10. Reuse (`docs/07` §6): the header logo hover re-draw (300ms) and a `loading` breathing loop on the mark (class `.is-loading`, used by forms in P23).

**Acceptance:**
- The checklist in `docs/07` §7 passes.
- Lighthouse LCP with the splash on vs off differs by ≤ 100ms.
- The second page view in the same session shows no splash.
- With JS disabled there's no splash and no blank screen.
- Standard checks.

---

## P08 — Home hero: structure 1:1 with `hero_should` 🟡

**Read first:** `docs/05-hero-spec.md` §1–§3 (**locked**), `inspirations/hero_should.jfif` (open the image and study it), `docs/04` §1.5 (notch technique), `docs/02` §2

**Goal:** Build the home hero's **static structure and layout**, matching the reference composition exactly at 1440×900. The slider behaviour, glowing border and animations come in P09.

**Build:**
1. **`app/data/home.php`** (all home copy lives here, sections keyed `hero`, `services`, `approach`, `sectors`, `process`, `why`, `triage`, `faq`, `insights`, `cta`). Hero copy (all `// PROVISIONAL` until C01):
   - Badge eyebrow: "UK Bid & Proposal Management"
   - **H1** (2 lines): line 1 "Tenders, Crafted", line 2 `<em>for Success.</em>`. Two alternatives are kept in a comment for the client: B "Compete With / *Confidence.*", C "Your Next Contract / *Starts Here.*"
   - Sub-headline: "Expert tender writing, bid management, compliance and digital support for UK businesses, from your first tender to major frameworks."
   - Quick links: "Explore Services" (→ `#services`), "Sectors We Serve" (→ `#sectors`)
   - Helper text: "Give us a call on **+44 7466 063564** and we can set you up, or **book a free consultation**." (link → `/contact-us/#quote`; `// PROVISIONAL`: client to confirm the free consultation)
   - Tag above the slider: "Our Special Service"
   - Mini card (bottom-left): eyebrow "Latest insight", title "Health & Social Care Tender Vocabulary, Explained", button "Read" → `/insights/` (P16 makes it dynamic)
   - Chips (bottom-right): Healthcare & Social Care, Transport, Security, Facilities Management (from `sectors.php`, each linking to its sector page)
2. **`templates/sections/home/hero.php`** reproduces the anatomy in `docs/05` §1:
   - **Outer frame:** `--ms-canvas` page; hero panel inset `20px` (16/12/8 at smaller breakpoints), radius `--r-xl`.
   - **Dark panel:** `--ms-grad-hero` background. The **building photo** sits on the right half as a real `<img>` via `img('home/hero-building', ['priority' => true, 'alt' => 'Glass office building in the City of London at dusk', 'sizes' => '(min-width: 1024px) 60vw, 100vw'])`; it shows a placeholder until the real photo arrives. A navy gradient mask fades it into the panel on the left.
   - **The header `hero` variant (from P04) renders inside the panel** at the top.
   - **Left column (5/12):**
     - **badge**: glass pill with the logo mark rendered as a small 3D-styled hexagon "M" (the mark SVG with a subtle gradient, inner highlight and soft shadow) + eyebrow text;
     - **H1** at `--fs-display` (2 lines, max-width 12ch per line);
     - **sub-headline** at `--fs-lead`, Navy 100 colour;
     - **Quick-Start bar**: a `<form action="/contact-us/" method="get">` styled as a white rounded bar with 2 selects and a button:
       - `sector`: the 7 sectors + "Other";
       - `service`: `<optgroup>` per category with its sub-services, values = sub-service slugs;
       - submit button "Get Started" (Azure 650), with `#quote` appended via a hidden-field-free approach (form `action="/contact-us/#quote"`);
       - each select has a visible small label (icon + "Your sector" / "Support needed") like the reference;
     - **two quick links** with a chevron inside a small white circle.
   - **Right column (7/12):** the slider container (static row of the first 3 cards for now: previous (faded, clipped), active, next (faded, clipped)), all **12 cards server-rendered** from `services.php` (each card: category chip in its colour, hex icon chip, title, one-line summary, link). The **"Our Special Service" tag** sits above the active card, wrapped by the `.special-frame` element (static border for now).
   - **Helper text** bottom-right inside the panel.
   - **S-curve notch:** the canvas-coloured bottom tab overlaps the panel bottom-right with `border-top-left-radius: var(--r-xl)` and inverted-corner pseudo-elements (`docs/05` §3), so the panel is deeper on the left under the quick links. On mobile the notch is removed.
   - **Bottom tab content:** mini insight card (thumbnail placeholder, eyebrow, title, small button) + **4 sector chips** as light-grey rounded tiles with icon + name. Below 1024px they become an infinite marquee of all 7 sectors (static wrap list until P06's marquee is applied).
3. **Responsive** exactly per the table in `docs/05` §3: stacked on tablet/mobile; the Quick-Start bar's fields stack with a full-width button on mobile; the slider becomes full-width with the active card plus a peek of the next.
4. The home template `templates/pages/home.php` now renders: splash partial (P07) → hero section → `<main id="main">` placeholders for the next sections (anchors `#services`, `#approach`, `#sectors`, `#process`, `#why`, `#faq`, `#insights`).

**Accessibility:**
- One `<h1>` (the headline).
- The Quick-Start selects have proper `<label for>`.
- The slider region is `aria-label="Our services"` (full carousel semantics in P09).
- Colour contrast on the dark panel is checked (sub-headline ≥ 4.5:1).

**Acceptance:**
- **Side-by-side visual comparison** with `inspirations/hero_should.jfif` at 1440×900: same composition, proportions, notch and element placement (screenshot both and compare).
- The Quick-Start form submits to `/contact-us/?sector=…&service=…#quote`.
- All 12 service cards are in the HTML source.
- Works at 360px with no horizontal scroll.
- Standard checks.

**Do not:** add animation yet; use an opacity-0 initial state on the H1 or photo.

---

## P09 — Home hero: services slider, glowing border, choreography 🟡

**Read first:** `docs/05` §4–§7 (**locked**), `docs/06` §3, §5b, `docs/12` §3

**Goal:** Bring the hero to life: the services **slider**, the **moving, shining, glowing border** around "Our Special Service", the entrance timeline after the splash, and scroll parallax.

**Build:**
1. **Slider** `src/js/modules/hero-slider.js` (GSAP `Draggable` + `InertiaPlugin`; no Swiper):
   - Infinite loop over the 12 server-rendered cards.
   - Active card: opacity 1, scale 1, glass-thin-dark, icon chip filled with its category colour.
   - Neighbours: opacity .45, scale .92, `blur(1px)`, edge-faded by a `mask-image` gradient on the track.
   - **Autoplay every 3.5s**; transition 700ms `cubic-bezier(.22,1,.36,1)`.
   - Pauses on hover, on focus-within, while the tab is hidden, and while dragging. Drag/swipe has inertia and snaps to the nearest card.
   - Keyboard ← → when focused.
   - Visually subtle prev/next buttons + dots + a **pause/play button** (WCAG 2.2.2).
   - Carousel semantics: `role="region" aria-roledescription="carousel"`; each card `role="group" aria-roledescription="slide" aria-label="n of 12"`; a live region announces the slide only when the user changes it (not on autoplay).
   - Reduced motion: no autoplay; instant transitions.
2. **Glowing border** `.special-frame` (`src/css/components/special-frame.css`). Implement **exactly** the CSS in `docs/05` §5:
   - `@property --angle` + conic-gradient ring masked to a 1.5px border, `ms-orbit` 4s linear infinite, plus the blurred `::after` halo pulsing 2.8s.
   - The ring is in Sky/white/Azure so the light visibly **travels around the frame**.
   - Fallback for browsers without `@property`: rotate an oversized conic pseudo-element inside an `overflow: hidden` ring.
   - The tag pill gets a diagonal (120°) **shine sweep** every 5s.
   - When the slider changes card, the frame "breathes" (scale 1 → 1.015 → 1, 400ms).
   - Reduced motion: static border with a soft glow.
3. **Entrance choreography** `src/js/modules/hero-intro.js`:
   - Starts on `splash:done`, or immediately if there's no splash.
   - Follows the timeline table in `docs/05` §6 (panel clip-path open, nav stagger, badge pop, **SplitText line reveal of the H1 using a mask with no opacity fade**, sub-headline, Quick-Start bar, cards slide in, the frame border "draws itself" then starts orbiting, helper text, bottom tab and chips stagger).
   - **The hero photo stays at opacity 1 throughout** (scale 1.15 → 1 only).
4. **Scroll parallax** (ScrollTrigger scrub, `docs/05` §6 last paragraph):
   - photo `yPercent: 12`;
   - left copy `yPercent: -8` and fades to 0.6;
   - slider `yPercent: -14`;
   - panel radius grows 28 → 40 as the hero leaves.
5. **Sector chips marquee** below 1024px using P06's `marquee` (with a pause button).

**Acceptance:**
- All items in `docs/05` §7 are ticked.
- The light visibly moves around the border continuously and glows.
- Lighthouse mobile LCP ≤ 2.5s with the splash on.
- No CLS from the intro (CLS < 0.05).
- Autoplay pauses correctly in all four cases.
- Standard checks.

---

## P10 — Home: "Our Approach to *Winning Contracts*" ✅

**Read first:** `docs/04` §1.2 (Innovax approach section), `docs/08` §4 row H3, `docs/website_content/about-us-content.md` §1

**Goal:** The Innovax-style intro section with a notched image, office-hours card, image-filled "10+", sector avatar stack and split button.

**Build** `templates/sections/home/approach.php`:
1. **Two-voice heading** (left, top): "Our Approach to" + `<em>Winning Contracts</em>`. Eyebrow "About My Solutionz".
2. **Large image** (left, ~55% width, radius `--r-xl`): `img('home/approach-meeting', alt 'Tender writing consultant reviewing a bid with a client team')` (placeholder until the photo arrives). It has a **notched top-right corner** holding the **Office Hours card** (Azure 650 → Azure 500 gradient, white text, radius `--r-xl`, inverted-corner joins per `docs/04` §1.5). Because it sits over an image, use the **true CSS mask** technique. Card content comes from `config('hours')` (provisional):
   - "Office Hours" (h3 styled small)
   - Mon–Fri 09:00–17:30
   - Sat–Sun Closed
   - Email us any time → `info@mysolutionz.co.uk`

   All `// PROVISIONAL` until the client confirms hours. **Do not** claim 24/7 support.
3. **Right column:**
   - **Image-filled number "10+"** (Mona Sans 700, `clamp(140px, 16vw, 220px)`, `background-clip: text` with a handshake/meeting photo, which uses a gradient placeholder until the photo arrives), with `data-anim="fill-text"` (the background pans on scroll) + `counter`. Visually hidden text for screen readers: "Over 10 years of business development experience".
   - **Sector avatar stack:** 3 overlapping 56px circles with a 3px white ring, each a hex icon (`first-aid-kit`, `bus`, `shield-star`) in category tints (no fake people), next to the uppercase bold label "STRATEGIC BIDS FOR SUSTAINABLE GROWTH".
   - **Paragraph:** "At My Solutionz, we help businesses compete for new opportunities through professional tender writing, bid management and business support services. We take the time to understand your services, strengths and objectives, then build responses that reflect what you can genuinely deliver."
   - **Split button** "More About Us" → `/about-us/`.
4. **Motion:** `two-voice` on the heading, `img-reveal` on the image, card slides in from the top-right with a slight delay, `fill-text` + `counter` on the number, `stagger` for the right column.
5. **Responsive:** stacked on < 1024px; the hours card moves below the image (no notch) on mobile.

**Acceptance:**
- Matches the structure of the Innovax screenshot (chat screenshot 2 / `docs/04` §1.2).
- The number is readable by screen readers.
- No hard-coded hours in the template.
- Standard checks.

---

## P11 — Home: "Our Services" (3 stacking category cards) 🟡

**Read first:** `docs/08` §4 row H2 (**locked: first section after the hero**), `docs/04` §4 (hex tiles), `docs/06` §3 `stack-cards`, `docs/website_content/services/01-bid-proposal-management.md` (tone)

**Goal:** The services showcase directly under the hero: the 3 categories as large sticky stacking cards, each with its sub-services.

**Build** `templates/sections/home/services.php` (anchor `#services`):
1. **Header:** eyebrow "Our Services"; two-voice H2 "Everything You Need to" + `<em>Compete and Grow</em>`; intro "Three specialist service areas, one dependable partner. Choose the support you need, from a single tender to ongoing compliance and digital support." (`// PROVISIONAL`, in `home.php`).
2. **3 category cards**, from `services.php` in order. Each is a large card (min-height 70vh on desktop, radius `--r-2xl`, white with a 6px top band in the category colour; Digital uses Sky on a navy card variant for contrast variety):
   - Left: hex icon tile (category colour), category number "01/02/03" in Mona Sans, **H3 category name**, summary, then the **sub-services as a list of hex-icon links** (icon + name + short summary, 2 columns on desktop), then button "Explore Bid & Proposal Management" (etc.) → category URL.
   - Right: image `img('services/<category-slug>')` with a notched bottom-left corner holding the count chip ("3 services" / "5 services" / "4 services").
3. **Stacking behaviour (desktop):** cards are `position: sticky; top: calc(var(--header-h) + 24px)`. As the next card scrolls over, the previous one scales to 0.94 and darkens slightly (`stack-cards`). On mobile/reduced motion it's a normal vertical list.
4. Each sub-service link uses the category colour on hover (hex fills, arrow slides).

**Acceptance:**
- Section order on the home page is hero → services.
- All links resolve to the correct category/sub-service routes.
- Stacking is smooth with no jump at the end of the section.
- Standard checks.

---

## P12 — Home: Sectors (pinned horizontal scroll) 🟡

**Read first:** `docs/08` §4 row H5, `docs/04` §1.0 (Innovax sticky panels), `docs/06` §3 `h-scroll`, `docs/website_content/about-us-content.md` §2

**Goal:** "Sectors We *Serve*" as a pinned horizontal-scroll gallery on desktop and a swipe row on mobile.

**Build** `templates/sections/home/sectors.php` (anchor `#sectors`):
1. **Intro panel** (first item in the track, fixed width ~420px):
   - eyebrow "Sectors";
   - two-voice H2 "Sectors We" + `<em>Serve</em>`;
   - text (client-approved, from the docx): "Each sector presents different challenges, from regulatory compliance and workforce planning to service delivery, contract mobilisation and performance management. Understanding these differences is central to how we approach our work.";
   - link "All sectors" → `/sectors/`.
2. **7 sector cards** from `sectors.php`. Each card: portrait (3:4) radius `--r-lg`, photo placeholder `img('sectors/<slug>')`, bottom dark gradient, hex icon chip, sector name (H3), summary (provisional), and a corner-notch arrow button (Innovax SVG-corner technique) linking to `/sectors/<slug>/`.
3. **Desktop (≥ 1024px):** the section pins; the track translates horizontally by (track width − viewport) with `scrub: 1`; pin length = track overflow. Cards get a slight `velocity-skew`. A thin progress bar under the track shows progress.
4. **Mobile / reduced motion:** horizontal scroll-snap row (`overflow-x: auto; scroll-snap-type: x mandatory`) with visible scrollbar styling, or a stacked grid with reduced motion.
5. Background: the faint `nodes` network (P06) behind the section on desktop.

**Acceptance:**
- No vertical jump when the pin ends; works after resize (matchMedia rebuild).
- Every card is a real link.
- Standard checks.

---

## P13 — Home: Process 01–05 (pinned steps) ✅

**Read first:** `docs/08` §4 row H6, `docs/04` §3 (Sun Over numbered list), `docs/06` §3 `pin-steps`/`draw`, `docs/website_content/about-us-content.md` §3, `docs/09` §6 (step icons)

**Goal:** "Our Process, *Step by Step*": a pinned section that walks through the 5 steps, used again on the About page in P19 (build it as a reusable component).

**Build:**
1. **Component** `templates/components/process-steps.php` (params: `heading`, `accent`, `eyebrow`, `steps[]`, `variant` `compact|full`, `cta`).
2. **Home data** (`home.php` → `process`), condensed from the client's approved Our Approach copy:

   | # | Step (icon) | Text |
   |---|---|---|
   | 01 | Understanding Your Business (`magnifying-glass`) | "We learn your services, operations, workforce and objectives to identify your competitive strengths." |
   | 02 | Tender Analysis & Bid Strategy (`strategy`) | "We review the ITT, specification and evaluation criteria, then plan a clear bid strategy." |
   | 03 | Evidence-Led Tender Writing (`pen-nib`) | "Our writers build specific, persuasive responses around your evidence and the marking scheme." |
   | 04 | Quality Review & Compliance Checks (`seal-check`) | "Every answer is checked against the specification, scoring criteria, word limits and required documents." |
   | 05 | Final Submission & Ongoing Support (`paper-plane-tilt`) | "We support your submission and stay with you for clarifications, feedback and your next opportunity." |

   Plus the link "See our full approach" → `/about-us/#our-approach`.
3. **Layout (Sun Over style):**
   - Left: eyebrow "How We Work", two-voice H2 "Our Process," + `<em>Step by Step</em>`, then the numbered list (large "01" numerals in Mona Sans, bold step title, text) with a **vertical progress line** on the left that fills as you progress.
   - Right: 2 overlapping rounded images (placeholders) with a **small icon chip** bridging them; the chip icon and images **crossfade per active step**.
4. **Desktop motion:** pin for ~5 × 60vh; the active step highlights (others at 0.35 opacity, never 0); the progress line fills via scrub; the icon chip morphs between step icons. **Mobile/reduced motion:** a simple vertical list, all steps fully visible.

**Acceptance:**
- Every step's text is present and readable in the HTML without JS.
- The pin releases cleanly.
- The component is reusable (P19 uses the `full` variant).
- Standard checks.

---

## P14 — Home: Why Work With Us bento + logo assembly ✅

**Read first:** `docs/08` §4 row H7, `docs/04` §5 (Vencap bento) and §8 (logo assembly), `docs/06` §6 S1, `docs/website_content/about-us-content.md` §4, `docs/09` §6

**Goal:** A bento grid of the 7 reasons with the signature **logo-mark assembly** as its centrepiece.

**Build** `templates/sections/home/why.php` (anchor `#why`):
1. **Header:** eyebrow "Why My Solutionz"; two-voice H2 "Why Work" + `<em>With Us</em>`; lead (client-approved): "More than tender writers. A partner in your business growth."
2. **7 tiles** (titles verbatim from the client's copy; one-liners condensed from it):

   | Title (icon) | One-liner |
   |---|---|
   | Business Development Experience That Matters (`trend-up`) | "Over 10 years of business development experience behind every bid." |
   | Expertise Across Multiple Industries (`squares-four`) | "Healthcare, transport, cleaning, security, education and facilities management." |
   | Your Business, Not a Generic Template (`fingerprint`) | "Every response is built around your experience, people and evidence." |
   | Focused on What Buyers Actually Evaluate (`target`) | "We write to the specification, the evaluation criteria and the marking scheme." |
   | Professional Support Without the Extra Pressure (`hand-heart`) | "We manage the agreed writing and bid activities so your team can keep running the business." |
   | Flexible Support That Works Around You (`sliders-horizontal`) | "From a single tender to ongoing bid management." |
   | Committed to Your Long-Term Success (`handshake`) | "Lasting partnerships built on consistent quality and reliable communication." |

3. **Bento layout** (desktop 4-column grid, mixed tile sizes, radius `--r-lg`):
   - **Centre tile (2×2, Navy 900):** the **logo mark assembly**. The 3 SVG polygons from `logo-mark.svg` (separate elements) start scattered (x ±300–450px, rotation ±120°, scale 2, opacity 0) and **assemble into the "M" as you scroll** (`logo-assembly`, scrubbed `start: "top bottom", end: "top 35%"`). Below the mark: counters "**10+** years' experience", "**7** sectors", "**12** services" (`counter`).
   - One tile in Azure gradient, the rest white with hairline borders.
   - Hover: lift + the icon tile fills.
4. **Mobile:** single column; the assembly plays as a simple `fade-up` (or completes immediately) on small screens.
5. Tiles `stagger` in with `ScrollTrigger.batch`.

**Acceptance:**
- The assembly is smooth and ends exactly on the correct logo geometry.
- Counters show only true numbers.
- Standard checks.

---

## P15 — Home: triage row, testimonials (hidden until supplied), FAQ teaser 🟡

**Read first:** `docs/08` §4 rows H8–H10, `docs/04` §8 (triage), `docs/10` §6 (no review markup), `docs/13` §7, `docs/website_content/current-site-content-scrape.md` §2 (FAQ)

**Goal:** Three conversion helpers. Testimonials are fully built but only render when real, permissioned testimonials exist.

**Build:**
1. **Triage row** `home/triage.php`: eyebrow "How Can We Help?" + 3 glass cards on a navy band with tilt:
   - **"Tender deadline coming up?"** "Talk to us today about the opportunity and your deadline." → "Call now" (`tel:`)
   - **"Bidding for the first time?"** "We'll guide you through every document and step." → "Book a free consultation" (`/contact-us/#quote`; `// PROVISIONAL`: free consultation to be confirmed)
   - **"Need compliance or digital support?"** "From CQC registration to websites and SEO." → "Explore services" (`/services/`)
2. **Micro-CTA** (P05 component) between sections: "Not sure which service you need?" → "Let's talk".
3. **Testimonials:**
   - `app/data/testimonials.php` returns `[]` with a commented example structure (`quote`, `name`, `role`, `company`, `sector`, `permission_date`).
   - Component `testimonials.php`: an Innovax-style **marquee** of quote cards with edge-fade mask, a pause button, and sector chips.
   - **If the array is empty the section doesn't render at all.**
   - No `Review`/`AggregateRating` schema, ever.
4. **FAQ data** `app/data/faqs.php`: a list of Q&As with `groups` (`basics`, `services`, `pricing-process`, `sectors`) and `pages` tags (`home`, `faq`, `bid-proposal-management`, …), so the same items feed the home teaser, `/faq/` and service pages. Seed with these (`// PROVISIONAL` until approved):
   - "Can you help if we have never tendered before?" → "Yes. We explain each tender document, help you register on the relevant portals, gather the policies and evidence buyers usually ask for, and guide you through your first submission step by step."
   - "How much do your services cost?" → "Every tender is different, so we quote per project based on the number and complexity of the questions, the deadline and the level of support you need. Contact us for a free, no-obligation quote."
   - "How do you keep our information confidential?" → "We take confidentiality seriously. Access to your documents is limited to the people working on your bid, and we use secure methods to share files."
   - "Can you help after we have submitted a tender?" → "Yes. We can help you respond to clarification questions from the buyer and review the feedback you receive, so lessons are carried into your next bid."
   - "Can you guarantee that we will win the contract?" → "No one can honestly guarantee a contract award: the decision rests with the buyer and depends on price, competition and the service you offer. We make sure your submission is compliant, well evidenced and written to score as strongly as possible."
5. **FAQ teaser** `home/faq.php` (anchor `#faq`):
   - Left: two-voice H2 "Questions," + `<em>Answered</em>`, a short line, and a "View all FAQs" button → `/faq/`.
   - Right: an accordion of the `home`-tagged FAQs (component C17: `<details>/<summary>` base, so answers are in the HTML; GSAP height animation; plus → minus morph; one open at a time optional).

**Acceptance:**
- The testimonials section is absent when the data is empty.
- The accordion works with keyboard and without JS (native `<details>`).
- FAQ answers are visible in the page source.
- Standard checks.

---

## P16 — Home: Latest Insights, final CTA, home SEO 🟡

**Read first:** `docs/04` §1.3 (blog card), `docs/08` §4 rows H11–H13, `docs/08` §9, `docs/10` §4–§7

**Goal:** Finish the home page: insights cards, final CTA band, complete SEO, and a full-page polish pass.

**Build:**
1. **Minimal blog reader** `app/lib/Blog.php` (P25 extends it):
   - reads `content/blog/*.md` with YAML front matter (`title`, `slug`, `description`, `date`, `updated`, `author`, `category`, `image`, `image_alt`, `status`);
   - `latest(int $n)` returns published posts, newest first; on non-production it may include drafts, marked with a "Draft" chip.
   - Create `content/blog/2026-01-18-health-and-social-care-tender-vocabulary-explained.md` with **front matter only**, `status: draft`, and title "What's in a Health and Social Care Tender? Tender Vocabulary Explained" (the body is migrated in P26).
2. **Blog card component** `card-blog.php`, the **Innovax notched card** (`docs/04` §1.3):
   - white card radius 24, image radius 20;
   - **date tab notch** bottom-left of the image (UK date format, e.g. "18 JANUARY 2026");
   - title (2 lines, clamp), meta row (author uppercase + outlined category pill);
   - **circular arrow button in a concave bottom-right notch** (SVG corner technique);
   - hover: image zoom 1.06, arrow rotates −45° → 0 and fills Azure.
3. **Home insights** (anchor `#insights`): two-voice H2 "Latest" + `<em>Insights</em>`, "View all insights" link, and 3 cards. If fewer than 3 posts exist, show what exists plus a card "More insights coming soon: follow us on LinkedIn" (links to LinkedIn). **The hero mini card (P08) now pulls the latest post too.**
4. **Final CTA band** (P05 component) with default copy + the `rise-rotate` logo mark.
5. **Home SEO** in `home.php` (`// PROVISIONAL` until C01):
   - title: `Tender Writing & Bid Management Services UK | My Solutionz` (58 chars);
   - description: `Expert tender writing, bid management, compliance and digital support for UK businesses, backed by over 10 years of business development experience.`;
   - og:title "Tenders, Crafted for Success"; og:image = the photographic hero image (1200×630 crop) with alt;
   - schema: organization + website + webPage with `primaryImageOfPage`;
   - status `draft` until the client approves the home copy (C01).
6. **Polish pass on the whole home page:**
   - section spacing rhythm;
   - `data-header-theme` on light/dark sections so the sticky header adapts;
   - wave/diagonal dividers between the dark and light sections;
   - the background art layers from `docs/04` §6 where specified (glass skyscraper wash behind the approach section at 8% opacity; azure swirl lines drawn with `draw` near the process section).

**Acceptance:**
- The full home page scrolls smoothly from splash to footer.
- `php bin/seo-audit.php` passes for `/`.
- Lighthouse mobile ≥ 90 performance (≥ 95 after P31) / 100 accessibility / 100 SEO (SEO shows noindex warnings on local; ignore those only).
- Standard checks.

---

## P17 — Inner-page hero component ✅

**Read first:** `docs/04` §3 (Sun Over), §6 (glass skyscraper background), `docs/08` §5 row A0 and §7a–§7b, `docs/05` §5 (glowing frame, reused)

**Goal:** One flexible hero for every inner page, consistent with the home hero's quality.

**Build** `templates/components/hero-inner.php`. Parameters: `variant`, `eyebrow`, `title`, `accent`, `lead`, `ctas[]`, `images[]`, `labels[]`, `icon`, `colour`, `breadcrumbs`.

The inner pages use the **default header** (glass pill nav, P04) over the hero. Variants:
1. **`columns`** (About, hubs):
   - Dark Navy 900 panel with a **large bottom-left radius (~80px)**.
   - Left: breadcrumbs, eyebrow, **H1** (two-voice allowed), lead, CTAs.
   - Right: **3 tall pill-shaped image columns** of different widths (Sun Over), with **floating dark labels** (icon tile + text, e.g. *Bid Strategy*, *Compliance*, *Evidence-Led Writing*) overlapping the column edges. Each column and label has its own `data-speed` (0.9 / 1.0 / 1.1) for parallax.
   - Faint glass-skyscraper art wash (SVG/WebP recreation, ≤ 10% opacity) behind.
2. **`category`** (service categories and sub-services):
   - Same panel, using the category colour (`--cat-*`) for the eyebrow chip and glow.
   - Right: a large hex icon tile inside the **animated glowing frame** (reuse `.special-frame`), plus optional image columns.
   - Sub-service pages also show a "Part of [Category]" chip linking up.
3. **`simple`** (legal, FAQ, utility): compact light hero (canvas background) with breadcrumbs, H1 and lead only.
4. **`article`**: used by P26 (category chip, H1, meta row, featured image).

**Motion:** `split-lines` on the H1 (mask, no opacity fade), columns `img-reveal` + `parallax`, labels float in with stagger, glowing frame draws then orbits. Reduced motion: static.

**Accessibility:** breadcrumbs nav, one H1, decorative images `alt=""`, labels as plain text.

**Acceptance:**
- Demonstrated on the styleguide with all 4 variants.
- Mobile: columns collapse to a single wide image with labels below.
- Standard checks.

---

## P18 — About page part 1: About Us + Our Story ✅

**Read first:** `docs/website_content/about-us-content.md` (**final client copy; use verbatim**), `docs/08` §5 rows A0–A2, `docs/04` §1.2

**Goal:** The first half of `/about-us/` with final content.

**Build** `templates/pages/about.php` + `app/data/about.php`. Copy goes into the data file verbatim from `about-us-content.md`.
1. **`$page`** from the SEO pack at the bottom of `about-us-content.md`:
   - title `About My Solutionz | UK Tender Writing Consultancy`;
   - description as given;
   - `AboutPage` schema;
   - breadcrumbs Home › About Us;
   - **status: published**.
2. **A0 hero:** `hero-inner` `columns` variant:
   - eyebrow "About Us";
   - H1 "About" + `<em>My Solutionz</em>`;
   - lead "We help businesses compete for new opportunities through professional tender writing, bid management and business support services.";
   - CTAs "Get a Free Quote" / "Our Services";
   - labels *Bid Strategy*, *Compliance*, *Evidence-Led Writing*.
3. **In-page sub-nav** (sticky under the header on desktop, horizontal scroll chips on mobile): About Us · Our Story · Our Approach · Why Work With Us · Mission & Vision → anchors `#about-us` `#our-story` `#our-approach` `#why-work-with-us` `#mission-vision`. A scrollspy highlights the current section (active pill slides). Anchor links follow the P06 hash rules.
4. **A1 About Us** (`#about-us`):
   - Two-voice H2 "Your Business Growth Is" + `<em>Our Business</em>`.
   - Two columns: the 5 paragraphs (first paragraph as `--fs-lead`), and a notched image + office-hours card (reuse the P10 pattern).
   - Below: a **sector chips row** linking to sector pages.
5. **A2 Our Story** (`#our-story`):
   - Two-voice H2 "Built on Experience," + `<em>Driven by Business Growth</em>`.
   - Layout: a **sticky image column** on the left (2–3 images crossfading as the text progresses; placeholders until photos) and the 8 paragraphs on the right.
   - The sentence "We have never believed in taking a standard approach to every tender." and the final paragraph get the **`read-along`** treatment (chars 0.2 → 1).
   - **Pull quote** (large Playfair italic, Azure, with an animated SVG quote mark): "For us, every tender represents more than a submission deadline. It represents an opportunity for a business to move forward, and we take our role in that process seriously."
6. Background: the azure swirl-lines art drawing in (`draw`) behind the Our Story header.

**Acceptance:**
- Text matches `about-us-content.md` exactly (including "over 10 years").
- Anchors work from other pages (`/about-us/#our-story`).
- `php bin/seo-audit.php` passes for `/about-us/`.
- Standard checks.

---

## P19 — About page part 2: Approach, Why Us, Mission & Vision ✅

**Read first:** `docs/website_content/about-us-content.md` §3–§5 (**final copy, verbatim**), `docs/08` §5 rows A3–A7, `docs/04` §5–§6

**Goal:** Complete `/about-us/`.

**Build** (continue `templates/pages/about.php` / `app/data/about.php`):
1. **A3 Our Approach** (`#our-approach`):
   - Two-voice H2 "A Practical, Strategic Approach to" + `<em>Tender Writing and Bid Management</em>`.
   - The 2 intro paragraphs.
   - The **process component from P13 in the `full` variant** with the 5 steps and their **full** two-paragraph texts (icons as in P13).
   - Then a closing statement block on Navy 900: H3 "Every Bid Deserves a" + `<em>Well-Considered Approach</em>` + the 2 closing paragraphs.
2. **A4 Why Work With Us?** (`#why-work-with-us`):
   - Two-voice H2 "More Than Tender Writers." + `<em>A Partner in Your Business Growth.</em>` + the 2 intro paragraphs.
   - The **7 reasons as sticky stacking cards** (`stack-cards`): each card has an icon (from P14), H3 title and the full paragraph; alternate white/mist backgrounds; number "01–07".
   - Closing line in large type: "Whether you're pursuing your first public sector contract or preparing a major competitive bid, we're here to help you approach the opportunity with confidence."
3. **A5 Our Mission & Vision** (`#mission-vision`):
   - Two-voice H2 "Creating Opportunities, Strengthening Businesses," + `<em>Supporting Growth</em>`.
   - Two large cards side by side: **Mission** (white, `compass` icon, 3 paragraphs) and **Vision** (Azure 650 → Navy 700 gradient, white text, `binoculars` icon, 1 paragraph).
   - Behind them, an **azure ribbon SVG** (recreation of `inspirations/some_section_bg (3).jpeg`) that draws on scroll.
   - Closing statement centred, Playfair italic: "Ultimately, our purpose is to help businesses turn their capabilities into competitive opportunities and those opportunities into lasting growth."
4. **A6 Team:** component `team-cards.php` (the Innovax portrait card: share notch with a popover of LinkedIn links, bottom gradient, name, role pill; slider with dots). Data `app/data/team.php` returns `[]`. **The section doesn't render while empty.**
5. **A7 CTA band** (default copy).

**Acceptance:**
- All About copy is present verbatim; the section order is exactly About Us → Our Story → Our Approach → Why Work With Us? → Our Mission & Vision.
- The page reads well with JS off.
- Standard checks.

---

## P20 — Services hub + category landing template + 3 category pages 🟡 (Category 1 final)

**Read first:** `docs/08` §6 and §7a, `docs/website_content/services/01-bid-proposal-management.md` (**full content + SEO pack + design notes for Category 1**), `docs/10` §6 (Service + OfferCatalog schema)

**Goal:** `/services/` and a reusable category landing template; Category 1 complete with final content; Categories 2–3 as structured drafts.

**Build:**
1. **Data:** `app/data/service-pages/<category-slug>.php` per category, with keys `seo`, `hero`, `what_is`, `steps[]`, `subs_intro`, `benefits[]`, `who` (`personas[]`, `sectors_text`), `regulation` (`heading`, `intro`, `items[]`, `outro`, `updated`), `why[]`, `faq_tag`, `cta`.
2. **`bid-proposal-management.php`:** transfer **all** copy from `01-bid-proposal-management.md` exactly:
   - hero, "What is bid and proposal management?", 5 steps, 3 sub-service cards, 6 benefits, who we support, the Procurement Act 2023 section with `updated: 2026-10-13`, 4 why points, CTA;
   - add its 8 FAQs to `faqs.php` tagged `bid-proposal-management`;
   - SEO pack exactly as given;
   - status **published** (client approval pending only for the 3 notes at the end of that file, which stay as `// PROVISIONAL` comments on those sentences).
3. **`compliance-virtual-assistance.php` and `digital-presence.php`:** same structure.
   - Hero uses the category summary from `services.php`.
   - The sub-service cards are generated from `services.php`.
   - All other sections contain `[[PENDING: …]]` markers describing the content needed.
   - Provisional SEO: titles `Compliance & Virtual Assistance Services UK | My Solutionz` and `Digital Presence Services for UK Businesses | My Solutionz`, descriptions `[[PENDING]]`.
   - Status **draft**.
   - Template rule: sections whose data is `[[PENDING…]]` render as a dashed "Content pending: …" block on non-production and are **omitted** on production.
4. **Template** `templates/pages/services/category.php`, per `docs/08` §7a and the design table at the end of `01-bid-proposal-management.md`:
   - `hero-inner` `category` variant;
   - "What is" (text + notched image, `img-reveal`);
   - steps (process component `compact`, pinned on desktop);
   - sub-service cards (large glass cards, hex icons, corner-notch arrows, `tilt`);
   - benefits bento (6 tiles);
   - who we support (persona chips + sector links marquee);
   - **regulation band** (Navy 900, icon checklist, visible "Last updated 13 October 2026", `read-along` intro);
   - why (two-voice + 4 points + split button "More About Our Approach" → `/about-us/#our-approach`);
   - FAQ accordion (from `faqs.php` by tag);
   - CTA band pre-filling the category (`/contact-us/?service=<category-slug>#quote`).
5. **Schema:** webPage + breadcrumbs (Home › Services › Category) + `serviceCategory()` with `hasOfferCatalog` listing the sub-services (name + URL).
6. **Services hub** `/services/` (`templates/pages/services/index.php`, `app/data/service-pages/_hub.php`):
   - `hero-inner` `columns`: eyebrow "Our Services", H1 "Our" + `<em>Services</em>`, lead "Bid and proposal management, compliance and virtual assistance, and digital presence support for UK businesses." `// PROVISIONAL`.
   - **3 category panels** (colour band, hex icon, summary, sub-service hex tiles, "Explore" button).
   - Compact "How we work" 5-step strip, sectors chip marquee, FAQ (`services` tag), CTA.
   - SEO: title `Bid, Compliance & Digital Services UK | My Solutionz`, description "Explore My Solutionz services: bid and proposal management, compliance and virtual assistance, and digital presence support for UK businesses."
   - Status published.

**Acceptance:**
- `/services/bid-proposal-management/` contains every word of the approved content and passes `bin/seo-audit.php`; schema validates.
- Draft categories show pending blocks locally and are noindex.
- Hub links all resolve.
- Standard checks.

---

## P21 — Sub-service landing template + 12 sub-service pages 🟡

**Read first:** `docs/08` §7b, `docs/website_content/current-site-content-scrape.md` §4 (raw material), `docs/10` §3 (keywords) and §6, `docs/09` §4

**Goal:** One data-driven template for all 12 sub-services. Pages render now with provisional/pending content; the C03 prompts fill final copy later.

**Build:**
1. **Data:** `app/data/service-pages/<category>/<sub>.php` with keys `seo`, `hero` (`title`, `accent`, `lead`), `problem`, `included[]` (icon + title + text), `steps[]`, `who` (sector slugs + business types), `deliverables[]`, `why[]`, `related[]` (auto: siblings + 1 cross-category), `faq_tag`, `cta`, `status`.
2. **Provisional content** (all `status: draft`, everything `// PROVISIONAL`):
   - For the 6 sub-services that exist on the current site, map the scraped copy into the structure as provisional raw material (it will be rewritten in C03):
     - Tender & Bid Writing ← Tender Writing;
     - Expert Review ← Tender Review;
     - Opportunity Identification ← Tender Search;
     - CQC Registration;
     - Website Development;
     - Social Media Management.

     Remove unsupported claims while doing so ("star tender writer", "lucrative", "proven track record" without evidence).
   - For the other 6 (Policy Management, Carbon Reduction Plan, Data Entry & Management, Care Plans, Email Marketing, SEO & Analytics): hero from `services.php`, every other section `[[PENDING: …]]`.
   - Provisional SEO titles: `<Name> Services UK | My Solutionz` (shorten where needed to ≤ 60 chars, e.g. `CQC Registration Support | My Solutionz`); descriptions `[[PENDING]]`.
3. **Template** `templates/pages/services/sub.php`, per `docs/08` §7b:
   - `hero-inner` `category` variant with a "Part of [Category]" chip, breadcrumbs Home › Services › Category › Sub-service;
   - problem (short, empathic: large lead text + supporting image);
   - what's included (icon checklist cards, `stagger`);
   - how it works (process component `compact`);
   - who it's for (sector chips + types);
   - what you receive (deliverables list with check icons on a mist background);
   - why My Solutionz (3–4 points);
   - related services (cards);
   - FAQ (by tag);
   - CTA band pre-filling the sub-service.
   - Sections with pending data follow the P20 rule (dashed block locally, omitted in production).
4. **Schema:** webPage + breadcrumbs + `service()` (`serviceType`, `provider #organization`, `areaServed GB`, `audience BusinessAudience`, `isRelatedTo` the category `@id`).
5. Add each sub-service's **old URL redirect check** to `bin/seo-audit.php` (the 6 old `/service/*` URLs must 301 in one hop to these pages).

**Acceptance:**
- All 12 routes render without errors at all breakpoints.
- `bin/check-content.php` lists their pending items.
- The 6 legacy redirects land on the right pages.
- Standard checks.

---

## P22 — Sectors hub + sector template + 7 sector pages 🟡

**Read first:** `docs/08` §8, `docs/10` §3 (sector keywords) and §10, `docs/website_content/about-us-content.md` §2 (approved sector sentences)

**Goal:** `/sectors/` and a reusable sector template; content provisional until C04.

**Build:**
1. **Data:** `app/data/sector-pages/<slug>.php` with keys `seo`, `hero`, `challenges[]` (icon + title + text), `how_we_help`, `services[]` (sub-service slugs), `buyers` (typical buyers/contract types), `faq_tag`, `cta`, `status: draft`.
   - Provisional: hero lead = the sector summary from `sectors.php`.
   - `how_we_help` uses the client-approved text from the docx: "Each sector presents different challenges, from regulatory compliance and workforce planning to service delivery, contract mobilisation and performance management. Understanding these differences is central to how we approach our work."
   - **Healthcare & Social Care** also gets (approved): "Our experience in healthcare and social care has given us particularly strong insight into complex, highly regulated procurement environments.", plus a provisional note that NHS healthcare services are commissioned under the Provider Selection Regime.
   - Everything else `[[PENDING: …]]`.
   - Provisional SEO titles, e.g. `Health & Social Care Tender Writing | My Solutionz`, `Cleaning Tender Writing Services | My Solutionz`.
2. **Template** `templates/pages/sectors/single.php`:
   - `hero-inner` `columns` (sector photo columns + labels);
   - "The challenges in this sector" (icon cards);
   - "How we help" (text + relevant sub-service cards from `services.php`);
   - typical buyers/contract types (chips);
   - FAQ (by tag);
   - CTA pre-filling `?sector=<slug>`.
   - Breadcrumbs Home › Sectors › Sector.
   - Schema webPage + breadcrumbs (+ `about` the relevant services).
3. **Hub** `/sectors/`:
   - `hero-inner` `columns`: H1 "Sectors We" + `<em>Serve</em>`;
   - grid of 7 large sector cards (same card as P12, static grid);
   - "Why sector knowledge matters" (approved docx sentences);
   - CTA.
   - Status published once at least the hub copy is approved (provisional for now).
4. **Anti-doorway rule** (`docs/10` §3): each sector page must end up with substantially unique content. `bin/check-content.php` flags sector pages whose body text is > 50% identical to another sector page.

**Acceptance:**
- All 8 routes render.
- Home sector cards (P12) link here.
- Pending items are listed by the checker.
- Standard checks.

---

## P23 — Contact page + form backend ✅

**Read first:** `docs/08` §11, `docs/13` §2 (third-party embeds), §3, §8, `docs/11` §7, `docs/12` §5 (forms), `docs/website_content/current-site-content-scrape.md` §5

**Goal:** `/contact-us/` with a secure, accessible enquiry/quote form that actually delivers email.

**Build:**
1. **Page** `templates/pages/contact.php` + `app/data/contact.php`:
   - `$page`: title `Contact My Solutionz | Get a Free Quote`; description "Contact My Solutionz for tender writing, bid management, compliance and digital support. Call +44 7466 063564, email us or request a free quote online."; `ContactPage` schema; breadcrumbs.
   - **Hero:** `hero-inner` `simple` variant. H1 "Let's Talk About" + `<em>Your Next Contract</em>`; lead "Tell us about your opportunity, your deadline and the support you need. We'll come back to you with clear advice and a no-obligation quote." (`// PROVISIONAL`).
   - **Contact cards row** (white cards with icon tile + label + value + corner-notch arrow):
     - Call `+44 7466 063564` (`tel:`)
     - WhatsApp (`https://wa.me/447466063564`)
     - Email `info@mysolutionz.co.uk` (`mailto:`)
     - Office "Unit A53, 4–6 Greatorex Street, London E1 5NF" → Google Maps search link (new tab with `rel="noopener"` and a visually-hidden "(opens in a new tab)")
     - Office hours (from config, provisional)
   - **Form section `#quote`:** heading "Request a" + `<em>Free Quote</em>`. Fields:

     | Field | Required | Notes |
     |---|---|---|
     | Name | ✓ | `autocomplete="name"` |
     | Company | | `organization` |
     | Email | ✓ | `email` |
     | Phone | | `tel` |
     | Sector | | select: 7 sectors + Other |
     | Support needed | | select with optgroups per category; values = slugs |
     | Tender deadline | | `type="date"` |
     | Preferred call-back time | | select: Morning / Afternoon / Any time |
     | Message | ✓ | textarea, 2000 char limit with counter |
     | Marketing opt-in | | **unticked** checkbox: "Send me occasional insights and updates by email" |
     | Hidden fields | | honeypot `website`, `ts` (render time), CSRF token |

     Above the submit button: "We'll use your details to respond to your enquiry. See our [Privacy Policy](/privacy-policy/)." Submit button "Send Enquiry".
   - **Map:** a static, styled map image (placeholder until generated) linking to Google Maps. **No embedded map before consent.**
   - Socials row + mini FAQ (`faqs.php` tag `contact`; seed "How quickly will you respond?" with answer `[[PENDING: response time]]`) + CTA strip with the phone number.
2. **Backend** `app/controllers/ContactController.php` (`POST /contact-us/`):
   - Session started only here (secure, httponly, SameSite=Lax cookies).
   - CSRF check; honeypot must be empty; `ts` ≥ 3 seconds old; **rate limit 5 submissions / hour / IP** (file store in `storage/cache/ratelimit/`); server-side validation (lengths, email filter, allowed select values only, no header injection, strip control chars).
   - **On error:** re-render with values preserved, an **error summary** at the top of the form (focused, links to fields), and inline messages (`aria-invalid`, `aria-describedby`).
   - **On success:**
     - send the enquiry to `info@mysolutionz.co.uk` (PHPMailer SMTP from `.env`; `MAIL_DRIVER=log` writes `.eml` files to `storage/mail/` on local), with a `Reply-To` of the sender and a clear subject `New enquiry: <service> – <name>`;
     - send an **auto-reply** to the sender (branded simple HTML + text version: thanks, what happens next, contact details, privacy link);
     - **PRG redirect** to `/thank-you/` (P24).
   - Log failures to `storage/logs/mail.log` without logging message bodies.
3. **No reCAPTCHA.** Optional Cloudflare Turnstile behind a config flag (off by default).

**Accessibility:** real `<label>`s, required fields marked visibly and with `required`, field-level errors in text (not colour alone), 52px inputs, the error summary pattern, works fully without JS.

**Acceptance:**
- Valid submission → `.eml` files created locally (enquiry + auto-reply) → redirect to thank-you.
- Invalid → helpful errors.
- Honeypot or fast submit → silently rejected.
- 6th submission in an hour → friendly rate-limit message.
- CSRF missing → 400.
- Standard checks.

---

## P24 — Quote pre-fill, thank-you page, form polish ✅

**Read first:** `docs/05` §2 (Quick-Start), `docs/07` §6 (loading loop), `docs/08` §12, `docs/13` §2

**Goal:** Connect every "Get a Free Quote"/Quick-Start entry point to the form, and polish the experience.

**Build:**
1. **Pre-fill:**
   - `/contact-us/?sector=<slug>&service=<slug>#quote`: server-side, pre-select the matching options (whitelisted values only). `service` may be a category slug (select the optgroup's first option and show "Interested in: Bid & Proposal Management").
   - Show a small dismissible "You selected: …" chip above the form.
   - With `#quote`, focus moves to the first empty required field after load (respecting the P06 hash rule).
   - The canonical stays `/contact-us/`.
2. **Every CTA site-wide** passes context: category/sub-service pages pass their slug, sector pages pass the sector.
3. **Progressive enhancement** (`src/js/modules/form.js`):
   - inline validation on blur, mirroring the server rules;
   - character counter;
   - submit button loading state using the **logo-mark breathing loop** (`.is-loading`, from P07);
   - double-submit prevention.
   - The form keeps working with JS off.
4. **Thank-you page** `/thank-you/` (`noindex, follow`; excluded from the sitemap; disallowed in robots):
   - `hero-inner` `simple`: H1 "Thank" + `<em>You</em>`, "Your enquiry has been received.";
   - a 3-step "What happens next" timeline (We review your enquiry → We contact you to discuss your requirements → You receive a clear, no-obligation quote), timing `[[PENDING: response time]]`;
   - links to Insights and Services;
   - a check-mark draw animation.
   - Direct visits without a submission still show the page (no error).
5. **Event hooks** (fire only when analytics consent exists; the consent module comes in P28): `form_submit` on the thank-you page (via a one-time session flag), plus clicks on `tel:`, `mailto:` and WhatsApp links → `window.msTrack(name, props)`, a no-op until analytics is enabled.

**Acceptance:**
- The hero Quick-Start, every service/sector CTA and the mega-menu CTA land on the form with the correct pre-selection.
- Thank-you is noindex.
- Standard checks.

---

## P25 — Insights (blog) engine ✅

**Read first:** `docs/08` §9, `docs/10` §6 (BlogPosting, CollectionPage) and §8 (pagination, feed), `docs/11` §6 (Markdown option, chosen)

**Goal:** A fast, file-based blog: listing, categories, pagination, RSS and related posts.

**Build:**
1. **Extend `app/lib/Blog.php`:**
   - Parse `content/blog/*.md` with `spatie/yaml-front-matter`. Front matter: `title`, `slug`, `description`, `date`, `updated`, `author` (key from `content/authors.php`), `category`, `tags[]`, `image`, `image_alt`, `takeaways[]`, `status`.
   - Render bodies with **league/commonmark** (CommonMark + GFM tables + heading permalinks for ids) via a custom renderer:
     - H2/H3 get ids (for the TOC);
     - external links get `rel="noopener"` (no forced new tab);
     - `![alt](key)` images render through `img()` (responsive `<picture>`, lazy).
   - Reading time at 220 wpm.
   - API: `all()`, `published()`, `find($slug)`, `byCategory($slug)`, `paginate($n = 9, $page)`, `related($post, 3)`, `categories()`.
   - Cache the parsed index in `storage/cache/blog.php`, invalidated by file mtimes.
2. **Authors:** `content/authors.php` with the default author `team` (name "My Solutionz Team", type Organization, url `/about-us/`), plus a commented Person example (name, jobTitle, bio, photo, LinkedIn for `sameAs`).
3. **Categories** (`content/categories.php`): Tender Writing Tips (`tender-writing-tips`), Procurement Updates (`procurement-updates`), Health & Social Care (`health-social-care`), Compliance (`compliance`), Digital (`digital`).
4. **Listing** `/insights/`:
   - `hero-inner` `simple`: H1 "Tender & Bid Writing" + `<em>Insights</em>`, lead "Practical guidance on tendering, UK public procurement and growing your business.";
   - **featured post** (large notched card);
   - **category filter chips**: real links to `/insights/category/<slug>/`. A GSAP **Flip** filters the grid in place when JS is on and pushes the URL, but every state is a crawlable URL;
   - grid of notched cards (P16 component);
   - **pagination** `/insights/page/2/` with real links, `aria-label`s, and each page self-canonical.
   - SEO: title `Tender & Bid Writing Insights | My Solutionz`, description "Practical guidance on tender writing, bid management, UK public procurement and the Procurement Act 2023 from the My Solutionz team." Schema `CollectionPage` (+ `Blog`). Status published once ≥ 3 posts are published; draft until then.
5. **Category pages:** same layout, H1 = category name, unique description per category (`content/categories.php`), self-canonical.
6. **RSS** `/insights/feed.xml` (last 20 published posts, full description, `<atom:link rel="self">`).
7. **Sitemap integration:** `sitemap-insights.xml` lists published posts + listing pages, with accurate `lastmod` (`updated` or `date`).
8. **Empty state:** if there are no published posts, show a friendly message + LinkedIn follow link.

**Acceptance:**
- Adding a new `.md` file with `status: published` makes it appear in the listing, its category, the feed and the sitemap with no code change.
- Pagination works with JS off.
- Standard checks.

---

## P26 — Article template + migrate the existing post 🟡

**Read first:** `docs/08` §9, `docs/04` §1.3, `docs/10` §5, §6, §10 (E-E-A-T), `docs/website_content/current-site-content-scrape.md` §6

**Goal:** A premium reading experience with full E-E-A-T signals, plus migration of the one relevant existing post.

**Build:**
1. **Template** `templates/pages/insights/single.php`:
   - `hero-inner` `article` variant: category chip, **H1**, meta row (author, published date, "Updated" date if different, reading time), featured image (`priority` = LCP);
   - **sticky table of contents** on desktop (H2/H3 links, scrollspy) and a collapsible TOC on mobile;
   - body max-width 720px with typography styles for lists, tables, blockquotes, pull quotes and callouts;
   - **Key takeaways** box (from front matter) at the top;
   - **reading-progress bar** under the header;
   - **author box** (name, role, short bio, LinkedIn; organisation fallback);
   - share links (LinkedIn, Facebook, X, Email) as plain share URLs (no third-party scripts);
   - related posts (3 notched cards);
   - CTA band;
   - breadcrumbs Home › Insights › Category › Post.
   - SEO: `og:type=article` + `article:*` tags; `BlogPosting` schema with `datePublished`/`dateModified` (ISO 8601 with `Europe/London` offset), `author`, `publisher #organization`, `image` (16:9, 4:3, 1:1 crops generated by the image pipeline), `mainEntityOfPage`.
2. **Migrate** "What's in a health and social care tender? Tender vocabulary explained" (originally published 18 January 2026):
   - fetch the live page `https://mysolutionz.co.uk/whats-in-a-health-and-social-care-tender-tender-vocabulary-explained/` and convert the article body to Markdown in the file created in P16 (keep the headings structure: ITT, Service Specification, Clarification Questions, Selection Questionnaire, Non-Collusion, Social Value, TUPE, TUPE list, Confidential Information Form, Technical/Quality Questions);
   - fix heading levels (H2 for each term);
   - new slug `health-and-social-care-tender-vocabulary-explained` (the 301 already exists from P01);
   - title tag "Health and Social Care Tenders: Key Vocabulary Explained"; keep the existing meta description; category `health-social-care`; author `team`.
   - **Originality check:** the old post links to healthcarebids.co.uk. Compare a few distinctive paragraphs with that site (search the web for exact phrases). If substantially similar, **keep `status: draft`** and log it in BUILD-LOG as needing a rewrite (C07). Otherwise keep it as provisional.
   - Add an "Updated for the Procurement Act 2023" note `[[PENDING: client review]]`: since 24 February 2025 most public tenders use the Procurement Specific Questionnaire (PSQ) and the Central Digital Platform, while NHS healthcare services fall under the Provider Selection Regime.
   - Remove the old outbound link if the content is rewritten.

**Acceptance:**
- The article page passes `bin/seo-audit.php` and the schema validates.
- The TOC works with keyboard and without JS (plain anchor list).
- The old URL 301s to the new one.
- Standard checks.

---

## P27 — FAQ page 🟡

**Read first:** `docs/08` §10, `docs/10` §6 (FAQPage status), `docs/website_content/current-site-content-scrape.md` §2 (FAQ Q1–Q4)

**Goal:** `/faq/` (an existing URL whose current content is immigration-theme demo text) becomes a genuine, well-structured FAQ.

**Build:**
1. Extend `app/data/faqs.php` with groups:

   | Group | Questions |
   |---|---|
   | **Tendering basics** | What is a tender? · What is the difference between bid writing and bid management? · What is the Procurement Act 2023 and how does it affect bidders? · Where are UK public sector tenders published? |
   | **Our services** | the 3 categories explained · "Can you help if we have never tendered before?" · "Do you support private sector proposals?" |
   | **Pricing & process** | cost · notice before a deadline · confidentiality · after submission · guarantee |
   | **Sectors** | Which sectors do you work in? · Do you support CQC registration for new care providers? |

   - Reuse the existing provisional answers where available, and the 4 real FAQs from the current site (rewritten in plain UK English).
   - New answers are `// PROVISIONAL`, factual and conservative. For "Where are UK public sector tenders published?" mention **Find a Tender** (the Central Digital Platform) and Contracts Finder.
2. **Page:**
   - `hero-inner` `simple`: H1 "Frequently Asked" + `<em>Questions</em>`;
   - a **search/filter input** (enhancement: filters questions live, with an "n results" live region);
   - group chips (anchor links);
   - grouped accordions (`<details>`, answers in HTML);
   - "Still have a question?" CTA → contact.
3. **SEO:** title `Tender Writing & Bid Management FAQs | My Solutionz`, description "Answers to common questions about tender writing, bid management, pricing, confidentiality and how My Solutionz supports UK businesses." `FAQPage` markup **off** by default (config flag; no rich result since May 2026). Status draft until the client approves the answers.

**Acceptance:**
- `/faq/` shows no trace of the old demo content.
- Works without JS; the filter works with the keyboard.
- Standard checks.

---

## P28 — Legal pages, cookie consent, analytics 🟡 (legal text needs client approval)

**Read first:** `docs/13-uk-legal-compliance.md` (all), `docs/10` §7 (consent mode), `docs/03` C24

**Goal:** UK-compliant consent and legal pages. **No non-essential cookies or tags before consent.**

**Build:**
1. **Cookie/consent config** `app/config/cookies.php`: categories `necessary` (always on: `ms_consent`, session cookie on the contact form), `analytics`, `marketing`. Each technology has name, provider, purpose, duration, category and whether it's exempt. `analytics_provider` comes from `.env` (`none` default | `plausible` | `ga4`); `marketing` is empty.
2. **Consent module** (`templates/partials/cookie-banner.php` + `src/js/modules/consent.js`):
   - **Non-blocking bottom glass sheet** that doesn't cover the hero LCP area on desktop (max-width 560px, bottom-left), with plain-English text and **three equally prominent buttons: "Accept all", "Reject all", "Manage preferences"**.
   - The preferences dialog (accessible modal: focus trap, Esc, labelled toggles; categories unticked by default except necessary) shows each category with its technologies listed from the config.
   - Stores `ms_consent` (first-party, 6 months, `SameSite=Lax`, `Secure` in production) as JSON `{v, ts, analytics, marketing}`. The policy version bumps re-ask.
   - Footer **"Cookie settings"** buttons (`[data-cookie-settings]`) reopen the dialog. Withdrawing deletes analytics cookies and reloads scripts accordingly.
   - Only shown when at least one non-essential technology is configured. When `analytics_provider = none`, no banner is needed: the cookie policy still lists necessary cookies.
   - **Analytics loading:**
     - `ga4` → print the Consent Mode v2 default (all denied, `wait_for_update: 500`) **before** any Google tag, and inject the gtag loader **only after** analytics consent (Basic mode), then `gtag('consent','update',…)`;
     - `plausible` → load the script only after consent unless the client decides to rely on the DUAA statistical exemption (config flag `analytics_exempt`, default false; if true, show an info notice + an objection toggle in preferences).
   - Connect `window.msTrack` from P24.
3. **Legal pages** (`hero-inner` `simple` + a long-form template with a sticky TOC; all `status: draft` with `[[PENDING: client legal review]]` at the top):
   - `/privacy-policy/`: written per `docs/13` §3 using the company facts (controller My Solutionz Ltd, 15677749, registered office, email); data collected via the forms; purposes and lawful bases; processors (hosting provider `[[PENDING]]`, email/SMTP provider `[[PENDING]]`, analytics if any); retention (enquiries 24 months unless a contract follows `[[PENDING: confirm]]`); rights; ICO complaint (ico.org.uk); complaints procedure; cookies link; last updated date.
   - `/cookie-policy/`: explanation + a **table generated from `cookies.php`** + how to change settings (button).
   - `/terms-and-conditions/`: website terms of use (`docs/13` §5), governing law England and Wales.
   - `/accessibility/`: WCAG 2.2 AA statement, known limitations, contact route, review date.

   Each page has its own SEO title/description and breadcrumbs.

**Acceptance:**
- With `analytics_provider=ga4` and a test ID: DevTools shows **zero** Google requests and zero non-essential cookies before consent; Accept loads them; Reject keeps them off; Cookie settings reopens; withdrawal works.
- With `none`: no banner, and the cookie policy is still correct.
- The banner is fully keyboard accessible.
- Standard checks.

---

## P29 — 404, HTML sitemap, utility pages ✅

**Read first:** `docs/08` §12, `docs/07` §6 (404 animation), `docs/10` §8

**Goal:** Helpful error and utility pages.

**Build:**
1. **404** (`http_response_code(404)`, `noindex`, no canonical):
   - `hero-inner` `simple` styling;
   - the **logo mark** where the navy bar "falls over" and re-assembles when clicked (GSAP, reduced-motion = static);
   - H1 "Page" + `<em>Not Found</em>`, text "The page you're looking for may have moved. These links might help:";
   - quick links (Home, Services, the 3 categories, Sectors, Contact) + phone.
2. **410 page** (from P01) styled consistently: "This page has been removed".
3. **HTML sitemap** `/sitemap/`: all **published** pages grouped (Main, Services by category, Sectors, Insights, Legal), generated from routes + data. Drafts are shown only on non-production, marked "Draft".
4. Make sure `/thank-you/`, `/styleguide/*` and drafts never appear in `sitemap.xml` or the HTML sitemap on production.

**Acceptance:**
- A random URL returns 404 status with the helpful page.
- The HTML sitemap lists everything published, with correct links.
- Standard checks.

---

## P30 — SEO pass ✅ (run when most content is final)

**Read first:** `docs/10-seo-specification.md` (all), especially §2, §4–§6, §9 and §12

**Goal:** Prove every SEO requirement is met site-wide before launch.

**Build / do:**
1. **Install the SEO skills** (`docs/10` §0): marketingskills (`seo-audit`, `schema`, `ai-seo`, `site-architecture`) and the claude-seo plugin. Run `/seo audit http://localhost:8000` and `/seo page` on: home, a category, a sub-service, a sector, About, Contact, an article, the FAQ. Fix every issue, or document why it's intentionally ignored (e.g. noindex on local).
2. **`bin/seo-audit.php`** across all published routes: one H1; unique titles (30–65 chars) and descriptions (70–170 chars); canonical absolute, self-referencing and with a trailing slash; OG/Twitter complete; JSON-LD valid; every `<img>` has alt/width/height; no `href="#"`; internal links resolve (200, no redirect chains).
3. **Redirect test** `bin/test-redirects.php`: request every old URL from `app/redirects.php` + the full old inventory (`docs/10` §2) and assert exactly **one hop** to the right target (or a 410). Also `http://`, `www.` and missing-trailing-slash variants (production config simulated via headers).
4. **Structured data:** paste each template's JSON-LD into validator.schema.org and Google's Rich Results Test (via a temporary public staging URL if needed). No errors; warnings reviewed.
5. **OG images** `scripts/og.mjs` (sharp): per page, generate `public/assets/og/<slug>.jpg` at 1200×630 from the page's primary photo (cover crop), a subtle navy gradient at the bottom and a small logo mark in the corner. Text-light, because Google prefers photographic preferred images (`docs/10` §5). Set `og:image:alt` per page.
6. **Internal linking:** crawl the local site and report orphan pages and click depth (every page ≤ 3 clicks from home). Add contextual links: service ↔ sector ↔ insights (hub and spoke).
7. **UK English check:** grep templates/data/content for common US spellings (organize, optimize, color, center, behavior, analyze, program (not "programme"), license as a noun) and fix.
8. **Content gate:** `php bin/check-content.php --strict` must report **zero** `[[PENDING`, zero `PROVISIONAL` and zero drafts **for pages that will launch**.
9. Confirm `robots.txt` (production mode), `sitemap.xml` (only published pages, accurate `lastmod`), `legacy-sitemap.xml`, `llms.txt`, RSS.

**Acceptance:**
- All scripts pass; `/seo audit` has no critical issues.
- A short SEO report is added to `docs/BUILD-LOG.md`.
- Standard checks.

---

## P31 — Performance pass ✅ (run near the end)

**Read first:** `docs/12-performance-accessibility.md` §1–§4, `docs/10` §5

**Goal:** Hit the budgets: Lighthouse mobile ≥ 95 performance on home, a sub-service page and an article; CWV targets LCP ≤ 2.0s, INP ≤ 150ms, CLS ≤ 0.05.

**Do:**
1. **Images:** run `npm run images` on all real photos in `assets-src/images/`; verify AVIF/WebP/JPEG + srcset everywhere, the hero ≤ 200KB, content ≤ 100KB, thumbnails ≤ 50KB. Exactly one `fetchpriority="high"` per page (the LCP image); everything below the fold lazy.
2. **Critical CSS:** extract above-the-fold CSS for the home, category/sub-service and article templates (e.g. the `critical` npm package at build time) and inline it (≤ 14KB) with a `nonce`. Load the rest normally.
3. **JS:** add a bundle visualiser; the initial JS must be ≤ 90KB gz and per-feature chunks ≤ 40KB gz. Confirm heavy modules (hero-slider, h-scroll, splash, nodes, SplitText users) are dynamic imports. Remove unused GSAP plugins.
4. **Fonts:** confirm subsets, only 2 preloads, metric fallbacks (CLS from fonts = 0).
5. **Runtime:** max 3 simultaneous `backdrop-filter` surfaces; no `scroll` listeners writing styles; INP: profile the mega menu, slider, accordion and form interactions (each < 100ms); break long tasks.
6. **Server:** OPcache on; Brotli/gzip; cache headers verified (`immutable` on hashed assets); a simple **HTML output cache** for published static pages (`storage/cache/pages/`, invalidated by data/template mtime) with ETag/304 support; TTFB ≤ 600ms.
7. **Lighthouse CI** (`@lhci/cli`): `lighthouserc.json` with budgets from `docs/12` §2 and assertions (perf ≥ 0.95, a11y = 1, best-practices = 1, seo = 1 with noindex ignored on local); `npm run lhci`.
8. Test on a throttled mid-range Android profile and a real phone if available.

**Acceptance:**
- Lighthouse CI passes.
- WebPageTest/PageSpeed on staging is within budgets.
- Findings and before/after numbers logged in BUILD-LOG.

---

## P32 — Accessibility pass ✅ (run near the end)

**Read first:** `docs/12` §5–§6, `docs/03` §2.8

**Goal:** WCAG 2.2 AA across every template.

**Do:**
1. Automated: `scripts/a11y.mjs` (Playwright + `@axe-core/playwright`) visits every published route at 360 and 1440 → **0 violations**.
2. Keyboard walkthrough of: header, mega menu, sectors dropdown, mobile menu, hero slider (incl. pause), Quick-Start, sub-nav scrollspy, accordions, FAQ filter, forms (error summary flow), cookie banner + dialog, back-to-top, carousels, footer. Check focus order, visibility, no traps, and that the sticky header never obscures focus (2.4.11).
3. Screen readers: NVDA + Firefox (Windows) and VoiceOver + Safari (macOS/iOS if available): landmarks, headings outline, carousel announcements, form errors, live regions, image alts, link purpose ("Explore Bid & Proposal Management", not "Read more" alone; add visually-hidden context where needed).
4. Preferences: reduced motion (no autoplay, no pinning, content visible), reduced transparency (solid surfaces), `prefers-contrast: more`, **200% zoom** and **320px reflow** with no horizontal scroll, text spacing override (1.4.12).
5. Targets ≥ 24px (we aim for 44px); contrast of all text over images/glass verified with real images in place.
6. Update `/accessibility/` with the test date and any known limitations.

**Acceptance:** zero axe violations; the manual checklist is logged in BUILD-LOG with pass/fail per item; all fails fixed.

---

## P33 — Responsive, cross-browser and device polish ✅ (run near the end)

**Read first:** `docs/11` §9, `docs/05` §3, `docs/04` (all references)

**Goal:** Pixel-polish on every target browser/device and a final visual comparison against the references.

**Do:**
1. Browsers: latest 2 of Chrome, Edge, Firefox, Safari (macOS + iOS), Samsung Internet. Known Safari items to check:
   - `-webkit-backdrop-filter`, `-webkit-mask-composite` for the glowing ring;
   - `@property` support (fallback path from P09);
   - `100svh` on iOS;
   - `position: sticky` inside overflow containers;
   - SplitText line masks;
   - date input styling.
2. Devices/viewports: 360×740, 390×844, 414×896, 768×1024 (portrait + landscape), 1024×768, 1280×800, 1440×900, 1920×1080, 2560×1440 (`--container-wide` behaviour). Landscape phones: hero and pinned sections must still work, or fall back.
3. Visual comparisons, side by side, with:
   - `hero_should` (home hero);
   - the Innovax screenshots (approach section, blog card, team cards, glass nav);
   - the Sun Over/Vencap/Alternate Futures layouts where used.

   Fix spacing, radii, type and alignment deviations.
4. Motion smoothness: 60fps on desktop; no jank on a mid-range Android (reduce effects via `gsap.matchMedia` if needed); pins don't jump; Lenis feels natural with trackpad and mouse wheel.
5. A print stylesheet for articles and legal pages (no nav/footer chrome, readable links).
6. Dark-mode note: the site is light/dark by design sections, not a theme switch. Ensure OS dark mode doesn't break form controls (`color-scheme: light`).

**Acceptance:** screenshot matrix saved to `docs/qa/` (key pages × key viewports); all issues fixed or logged.

---

## P34 — Security hardening ✅ (run near the end)

**Read first:** `docs/11` §7, `docs/13` §2, §8

**Goal:** A production-grade security posture.

**Do:**
1. **CSP** with per-request nonces (generated in bootstrap, available as `csp_nonce()`):
   - `default-src 'self'; script-src 'self' 'nonce-…'; style-src 'self' 'nonce-…'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self'; object-src 'none'; upgrade-insecure-requests`.
   - Add analytics domains **only** when that provider is enabled, and Turnstile only when enabled.
   - Prefer no inline `style=""` attributes in templates (move to classes or CSS variables set via JS). If unavoidable, allow `style-src-attr 'unsafe-inline'` only.
   - Relax only for the Vite dev server on local.
   - Test every page for CSP violations in the console.
2. Headers: HSTS (`max-age=31536000; includeSubDomains`), `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy` (camera/microphone/geolocation/payment off), `X-Frame-Options: SAMEORIGIN`, `Cross-Origin-Opener-Policy: same-origin`. Remove `X-Powered-By`; `expose_php=Off`.
3. Files: document root = `public/` only; `.env`, `app/`, `storage/`, `vendor/`, `content/`, `docs/` unreachable over HTTP (test with curl); deny dotfiles; no directory listing.
4. Forms: re-test CSRF, honeypot, time-trap, rate limit, header injection, oversize input and non-whitelisted select values; sessions `Secure; HttpOnly; SameSite=Lax`.
5. Dependencies: `composer audit`, `npm audit --omit=dev`; pin versions (lockfiles committed).
6. Errors: production shows the branded 500 page; logs in `storage/logs` with rotation; no stack traces leaked.
7. **Staging:** HTTP Basic Auth + `X-Robots-Tag: noindex` + robots `Disallow: /`.
8. Backups: document the backup approach for `content/` and `storage/` (and the old WordPress site's full backup before go-live).

**Acceptance:** securityheaders.com grade A (on staging), zero CSP console violations, the curl tests for forbidden paths return 403/404, audits clean.

---

## P35 — Launch ✅ (run at launch)

**Read first:** `docs/10` §2 and §12, `docs/13` §9, `docs/11` §8, `docs/15-open-questions.md`

**Goal:** A safe migration from the WordPress site to the new site with zero SEO loss.

**Do:**
1. **Pre-flight** (all must pass):
   - `php bin/check-content.php --strict` = 0;
   - P30–P34 acceptance met;
   - the client has signed off content, legal pages and hours;
   - analytics choice is configured;
   - `.env` production values set (`APP_ENV=production`, `SITE_URL=https://mysolutionz.co.uk`, `VITE_DEV=false`, SMTP credentials tested);
   - `legacy_sitemap` on.
2. **Back up** the current WordPress site (files + database) and keep it offline (not publicly reachable).
3. **Build & deploy:**
   - Run `composer install --no-dev -o` and `npm ci && npm run build` (locally or in CI).
   - Upload via SSH/Git (never upload `node_modules`).
   - Point the **document root to `/public`**. If the LiteSpeed host can't change the document root, use the documented fallback: the project outside the web root, with `public/` contents deployed to `public_html` and paths adjusted in `public/index.php`.
   - Enable OPcache.
   - PHP ≥ 8.3 confirmed on the host.
4. **Go-live checks (first hour):**
   - Run `bin/test-redirects.php` against **production**.
   - Spot-check the http/www/trailing-slash variants (one hop).
   - Submit a test form (real email received + auto-reply).
   - Check the cookie banner and analytics behaviour.
   - Run the URL Inspection live test on home, a category, a sub-service and an article: the **rendered screenshot shows all text**.
5. **Search engines:**
   - Search Console **Domain property** (DNS TXT); submit `/sitemap.xml` **and** `/legacy-sitemap.xml`; request indexing for key pages.
   - Bing Webmaster Tools (import from Search Console).
   - Update the website link on LinkedIn, Facebook and Instagram, and on the Google Business Profile if eligible.
6. **Monitoring (8 weeks):**
   - Weekly: Page Indexing report, 404s (add missed redirects), Core Web Vitals, Performance.
   - Uptime monitor and error-log review.
   - After ~6 weeks: turn off `legacy_sitemap`. Keep the redirects permanently.
7. Write the launch record in BUILD-LOG (date, versions, checks, follow-ups).

---

## Content-fill prompts (⏳ written when the client's content arrives)

The workflow for each piece of content:
1. The client's copy is turned into an SEO-ready content document in `docs/website_content/` (like `services/01-bid-proposal-management.md`), reviewed and approved.
2. The matching C-prompt below is written in full and run. It only edits data/content files, sets `status: published` and removes `PROVISIONAL`/`[[PENDING` markers.

| # | Content-fill prompt | Source doc (to be created) | Updates |
|---|---|---|---|
| C01 | **Home final copy**: hero headline choice, sub-headline, section intros, home SEO | `website_content/home-content.md` | `app/data/home.php` → published |
| C02 | **Service categories 2 & 3**: Compliance & Virtual Assistance, Digital Presence | `website_content/services/02-…md`, `03-…md` | `app/data/service-pages/…` → published |
| C03a | **Bid & Proposal sub-services** (3) | `services/01a-…`, `01b-…`, `01c-…` | sub-service data → published |
| C03b | **Compliance & VA sub-services** (5) | `services/02a–02e` | sub-service data → published |
| C03c | **Digital Presence sub-services** (4) | `services/03a–03d` | sub-service data → published |
| C04 | **Sectors** (hub + 7 pages, each substantially unique) | `website_content/sectors/*.md` | sector data → published |
| C05 | **FAQ + confirmations**: final FAQ answers, response time, office hours, free quote/consultation wording, portal uploads, private-sector proposals | `website_content/faq-and-facts.md` | `faqs.php`, `config/site.php`, contact/thank-you data |
| C06 | **Trust & imagery**: testimonials (with permission), team, case studies, accreditations, real photos through the image pipeline | client assets | `testimonials.php`, `team.php`, `assets-src/images/` |
| C07 | **Insights**: 3–6 launch articles + rewrite of the migrated post if needed; publish the Insights hub | `content/blog/*.md` | blog files → published |

> **Tip:** you can run P30–P34 early on the pages that are already final (About, Category 1, Contact) to catch issues sooner, then re-run them at the end.

