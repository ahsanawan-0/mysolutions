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
