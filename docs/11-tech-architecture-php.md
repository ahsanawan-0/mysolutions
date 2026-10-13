# 11 — Technical Architecture (PHP)

> **Status:** 🔒 LOCKED: plain PHP, no WordPress; GSAP for animation; one icon library. 🟡 PROPOSED: build tooling, blog storage (§6).

---

## 1. Stack

| Layer | Choice | Notes |
|---|---|---|
| Language | **PHP 8.3+** (8.4/8.5 fine; local dev machine has PHP 8.5.1) | `declare(strict_types=1);` everywhere |
| Server | Apache 2.4 / **LiteSpeed** (both read `.htaccess`; the current host is LiteSpeed + PHP 8.2) **or** Nginx + PHP-FPM | Config for both is kept in `/deploy/`. PHP 8.2 must be upgraded (security support ends 31 Dec 2026) |
| Dependency manager | **Composer** | `phpmailer/phpmailer`, `league/commonmark`, `spatie/yaml-front-matter`, `vlucas/phpdotenv` |
| Front-end build | **Vite** (npm) → hashed, minified JS/CSS + `manifest.json`; PHP helper `vite('main.js')` prints the right tags | Dev: Vite dev server proxied behind PHP's built-in server |
| CSS | Modern CSS (custom properties, `clamp()`, container queries, nesting) compiled by Vite (Lightning CSS). **No Bootstrap / no Tailwind** (we need full control of the Apple look; utility bloat hurts CWV) | One `tokens.css` holds every design token |
| Animation | **GSAP 3.13+** (`gsap`, `ScrollTrigger`, `SplitText`, `DrawSVGPlugin`, `MorphSVGPlugin`, `Flip`, `Draggable`, `InertiaPlugin`, `CustomEase`): all plugins are free to use since GSAP 3.13 | Imported from npm, tree-shaken. No CDN |
| Smooth scroll | **Lenis** (darkroom.engineering, npm `lenis`) synced to `ScrollTrigger` via `lenis.on('scroll', ScrollTrigger.update)` + `gsap.ticker` | Disabled when `prefers-reduced-motion` |
| Sliders | **GSAP Draggable/Inertia custom slider** (hero, team, testimonials). **Fallback:** Swiper (one library only) | Exact versions pinned in `package-lock.json` |
| Icons | **Phosphor Icons** (`@phosphor-icons/core` SVGs) → build step creates `/assets/icons/sprite.svg`; PHP `icon()` helper | No icon font, no CDN |
| Splash animation | Output of **pixel2motion** (see `07-splash-screen-logo-animation.md`): inline SVG + GSAP timeline | |
| Forms | PHPMailer over SMTP (authenticated), CSRF token, honeypot + time-trap, rate-limit; optional **Cloudflare Turnstile** | No reCAPTCHA (cookie/consent burden) |
| Fonts | **Self-hosted WOFF2** (subset Latin), `font-display: swap`, 2 preloads max | No Google Fonts CDN at runtime (GDPR + speed) |
| Analytics | GA4 via GTM **or** a privacy-first tool (Plausible/Fathom). Loaded only after consent with Consent Mode v2 | See 13 |

---

## 2. Folder structure

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

---

## 3. Routing and URLs (🔒)
- Clean, lowercase, hyphenated, **trailing slash** on all pages: `/services/bid-proposal-management/tender-bid-writing/`. One canonical form only; the other form 301s.
- Force **HTTPS** and a single host (**non-www** `https://mysolutionz.co.uk/` unless the current site uses www; then match the current site to preserve equity).
- `index.php` front controller: `routes.php` maps patterns → templates; unknown → real **404** (status 404, helpful page, no redirect to home).
- **301 map** from every old URL (crawled from the current site) lives in `redirects.php` and is checked **before** routing.

## 4. Page rendering contract
Every page template sets a `$page` array **before** the layout renders, e.g.:
```php
$page = [
  'title'       => 'Tender & Bid Writing Services UK | My Solutionz',
  'description' => 'Evidence-led tender writing for UK businesses…',
  'canonical'   => url('/services/bid-proposal-management/tender-bid-writing/'),
  'og_image'    => asset('og/tender-bid-writing.jpg'),
  'robots'      => 'index,follow',
  'breadcrumbs' => [['Home','/'],['Services','/services/'],['Bid & Proposal Management','/services/bid-proposal-management/'],['Tender & Bid Writing',null]],
  'schema'      => [Schema::service($service), Schema::faq($faqs)],
  'body_class'  => 'page-service',
  'js_modules'  => ['hero-inner','process','faq'],
];
```
`Seo.php` refuses to render (throws in dev, logs in prod) if title, description or canonical is missing. This **enforces the "every page has meta" rule**.

## 5. Performance architecture
- **Server-render all content** (critical for SEO; GSAP only animates what's already in HTML). Initial hidden states are set by JS **after** load with `gsap.set`, or by a `.js` class on `<html>`, so no-JS users and crawlers see everything.
- Critical CSS for above-the-fold is inlined (≤ 14KB); the rest loads normally.
- JS: one small entry (~30–40KB gz incl. GSAP core + ScrollTrigger) + per-page dynamic imports.
- Images: `Image.php` outputs `<picture>` with AVIF/WebP/JPEG, `srcset`/`sizes`, `width`/`height`, `loading="lazy"` + `decoding="async"` (except LCP image: `fetchpriority="high"`, no lazy).
- HTTP: Brotli/gzip, `Cache-Control: public, max-age=31536000, immutable` for hashed assets, short cache for HTML, ETag.
- Fonts: WOFF2, subset, preload only the 2 above-the-fold faces.

## 6. Blog / Insights storage: 🟡 decision needed
| Option | Pros | Cons |
|---|---|---|
| **A. Markdown files** in `/content/blog/` (recommended for launch) | Fast, secure (no DB, no admin attack surface), version-controlled, simple hosting | Client needs a developer (or Git) to publish |
| B. MySQL + small custom admin panel | Client can publish themselves | More build time, authentication, security hardening, backups |
| C. Headless CMS (e.g. a hosted CMS API) | Friendly editor | Third-party dependency/cost |

## 7. Security (🔒)
- Web root = `/public` only; `.env`, `app/`, `storage/` unreachable.
- Headers: `Content-Security-Policy` (nonce-based scripts, no `unsafe-inline`), `Strict-Transport-Security: max-age=31536000; includeSubDomains`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, `Permissions-Policy` (camera/mic/geolocation off), `X-Frame-Options: SAMEORIGIN`.
- All output escaped via `e()`; all input validated server-side; CSRF on every POST; PRG pattern after form submit; rate-limit by IP; honeypot + minimum-time check; SMTP credentials in `.env`.
- Errors: `display_errors=Off` in prod; logs in `storage/logs`.

## 8. Environments and deployment
- `local` (PHP built-in server + Vite), `staging` (password-protected **and** `X-Robots-Tag: noindex`), `production`.
- Deploy: `composer install --no-dev -o`, `npm ci && npm run build`, upload/rsync or Git-based deploy. Never deploy `node_modules`.
- Hosting requirements to confirm with client: PHP ≥ 8.3, HTTPS, SSH or Git deploy, SMTP mailbox, ability to edit `.htaccess`/Nginx config.

## 9. Browser support
Last 2 versions of Chrome, Edge, Firefox, Safari (macOS + iOS), Samsung Internet. Glass effects (`backdrop-filter`) degrade to solid translucent fills where unsupported.
