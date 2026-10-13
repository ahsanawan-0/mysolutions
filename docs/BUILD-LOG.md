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
