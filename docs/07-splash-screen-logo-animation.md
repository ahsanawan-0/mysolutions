# 07 — Splash Screen and Logo Animation (pixel2motion)

> **Status:** 🔒 LOCKED (tool, rules, choreography intent). 🟡 Exact timings are finalised by the pixel2motion run.
> **Tool:** https://github.com/nolangz/pixel2motion (MIT, v2): an **agent skill** for Claude Code/Codex.

---

## 1. What pixel2motion is (verified from the repo)
- Pipeline: **raster logo → minimal, smooth, motion-ready SVG → choreographed animation → standalone HTML + QA evidence**, applying Disney's 12 animation principles.
- **Input:** a raster logo (PNG/JPG/WebP).
- **Outputs:**
  - `logo.svg`: clean vector with **semantic ids** per part (e.g. `#mark`, `#wordmark`, per-letter elements).
  - `motion.css`: CSS `@keyframes` targeting those ids, using shared tokens `--p2m-duration`, `--p2m-ease-*`.
  - `logo_motion.html`: a **showcase page** with replay/slow-mo controls (for review, **not** for embedding as-is).
  - `motion_spec.md`: the motion brief and timeline.
  - QA images: fit overlays, motion frame strips, continuity probes.
- **Not produced:** Lottie or GSAP. Its output is SVG + CSS keyframes. We embed those directly and use **GSAP only for the hand-off** to the page.
- Prerequisites: Python 3.10+, `pillow`, `numpy`, `playwright` (+ `playwright install chromium`), Chrome/Chromium (`CHROME_BIN` must be set on Windows).

### Installing it for the build (Claude Code)
```bash
git clone https://github.com/nolangz/pixel2motion .claude/skills/pixel2motion
python -m venv .venv
.venv\Scripts\pip install pillow numpy playwright
.venv\Scripts\python -m playwright install chromium
set CHROME_BIN=C:\Program Files\Google\Chrome\Application\chrome.exe
```
The skill triggers on requests like *"animate the logo / build a splash screen / logo reveal"*.

---

## 2. Input and brief (what we give the skill)

| Item | Value |
|---|---|
| Source raster | `logos/Vertical Lockup on White Background.jpg (1).jpeg` (1563×1563, the highest resolution we have). If a vector logo arrives, it replaces the fitting phase |
| Usage context | **splash/intro** (skill band: 1200–2000ms) + a **header** variant (300–800ms) + a **loading** loop (for form submits) |
| Personality words | **engineered · confident · ascending** |
| Preset base | *Trustworthy/Professional* (`--p2m-ease-enter: cubic-bezier(0,0,0.2,1)`, `--p2m-ease-settle: cubic-bezier(0.4,0,0.2,1)`, overshoot 1.0) **blended** with *Elegant/Premium* overshoot **1.02** for a touch of life. Squash = **0** (professional brand) |
| Required SVG part ids | `#mark` › `#hex-sky`, `#bar-azure`, `#bar-navy`; `#wordmark` › one element per letter (`.letter`); `#tagline` |
| Geometry level | Complexity ladder **level 1 (primitives)**: the mark is exactly three polygons (a hexagon + two hexagonal parallelograms). The wordmark must keep exact letterforms (outlined paths, or live text in the matched condensed font locked with `textLength`) |
| Colours | `#8DD6FF`, `#009AF0`, `#003C8C`, wordmark `#0B1220` |

---

## 3. Choreography (target timeline, ≈ 1.6s + 0.6s hand-off)

Splash background: **`--ms-canvas` (#F5F7FB)**, matching the official "on white" lockup, so all three logo colours read correctly. (On navy, the navy bar would disappear.)

| Phase | Time | Part | Motion | Pattern (skill library) |
|---|---|---|---|---|
| Staging | 0–150ms | — | Empty canvas, a faint azure 120° light streak fades in | Staging hold |
| Anticipation | 150–350ms | `#hex-sky` | Appears at scale .6, coils to .55 | Scale-Pop (coil) |
| Action | 350–850ms | `#hex-sky` | Pops to 1.02 → settles 1.0 | Scale-Pop with overshoot |
| Action | 420–1000ms | `#bar-azure` | **Grows up its own 60° diagonal** (clip-path wipe bottom→top + 24px drift along the axis) | Mask Wipe |
| Action | 540–1120ms | `#bar-navy` | Same, staggered +120ms (heavier part, slightly slower) | Mask Wipe |
| Follow-through | 1000–1350ms | `.letter` × 12 | Letter cascade: translateY .6em → 0, 280ms each, **40ms stagger** | Letter Cascade |
| Follow-through | 1250–1600ms | `#tagline` | Left→right mask wipe | Mask Wipe |
| Settle | 1600ms | all | Exactly the static logo (**Final Frame Contract**) | — |

Ratios follow the skill's rule **Anticipation : Action : Follow-through ≈ 20 : 50 : 30**, staggers 10–20% of part duration, and no two parts start or stop on the same frame.

### Hand-off to the page (GSAP, 1600–2200ms)
1. **GSAP Flip:** `#mark` + `#wordmark` shrink and fly to the header logo's position (the header logo is the same SVG, horizontal layout).
2. The canvas overlay **splits along the 120° brand diagonal** into two panels that slide apart, revealing the dark hero.
3. The hero entrance timeline (`05-hero-spec.md` §6) starts **overlapping** at 1800ms.

---

## 4. Rules (🔒)
1. **Once per session.** `sessionStorage['ms_splash'] = 1`. Never on internal navigation (the skill itself calls a splash that replays on every route change "a bug"). Return visitors in the same session go straight to the page.
2. **Skippable:** click, tap, `Esc`, `Enter` or `Space` jumps to the final frame and runs the hand-off immediately.
3. **Hard cap:** the overlay is gone by **2.4s** at the latest, even if something fails. A **CSS-only failsafe** animation fades it out at 2.6s, so a JS error can never trap users behind the splash.
4. **No content hiding for SEO:** all page HTML is server-rendered **beneath** the overlay. The overlay is `aria-hidden="true"`, contains no headings, and is excluded from the accessibility tree. Screen readers get a "Skip to content" link as the first focusable element.
5. **LCP protection:** the hero **photo** is the intended LCP element. It is preloaded (`fetchpriority="high"`) and painted under the overlay from first paint. The splash must not contain large text or images that could become the LCP candidate.
6. **Reduced motion:** `prefers-reduced-motion: reduce` → no splash at all (or the static logo for ≤ 300ms then fade). The skill's `motion.css` is wrapped in `@media (prefers-reduced-motion: no-preference)` by design.
7. **No-JS:** the splash partial is only rendered if `<html>` gets the `.js` class (set by a tiny inline script in `<head>`); otherwise it's `display:none`.
8. **Easing rule from the skill:** literal `cubic-bezier()` values **inside `@keyframes`** (Chromium drops `var()` easing inside keyframes, which caused a measured speed cliff). Tokens are fine in `animation:` shorthands.
9. Animate only `transform`, `opacity`, `clip-path`, `stroke-dashoffset` (and `filter` sparingly). Remove `will-change` after the reveal.

---

## 5. Embedding (PHP)
```
templates/partials/splash.php    ← <div id="splash" class="splash" aria-hidden="true">
                                       <div id="logo-root"> <?php inline_svg('brand/logo-vertical.svg') ?> </div>
                                     </div>
src/css/splash.css               ← pixel2motion motion.css (scoped to #logo-root) + overlay styles + failsafe
src/js/modules/splash.js         ← session check, skip handlers, waits for
                                     Promise.all(logoRoot.getAnimations({subtree:true}).map(a => a.finished))
                                     then runs the GSAP Flip + diagonal split + hero timeline
```
Only `logo.svg` and `motion.css` are taken from the skill output. `logo_motion.html` stays in `/design/logo-motion/` for client review and sign-off.

---

## 6. Reuse of the same motion system (one vocabulary everywhere)
| Where | Variant |
|---|---|
| Header logo hover | Bars re-draw at 2× speed (300ms), ease-out only; `:focus-visible` parity |
| Form submit / page loading | **Breathing loop** on the mark only: scale 1 ↔ 1.02, 3s, stops after completion |
| 404 page | Mark assembles, then the navy bar "falls over" playfully and re-assembles on click |
| Favicon / app icon | Static mark: **`favicon.ico` (16/32/48) + `icon-192.png` + `icon-512.png`** (Google Search only lists ICO/PNG/etc., **not SVG**) + optional `icon.svg` for browsers + `apple-touch-icon.png` 180×180 on white |
| Section dividers | The 120° diagonal light streak from the staging phase |

---

## 7. Acceptance checklist
- [ ] pixel2motion QA passed: geometry overlay reviewed, smoothness gate passed, motion strip reviewed, `?static=1` equals `?t=<end>` (0 diff).
- [ ] `logo_motion.html` approved by the client before integration.
- [ ] Splash plays once per session, skippable, gone by 2.4s, failsafe works with JS disabled mid-run.
- [ ] Lighthouse LCP unaffected compared with the splash switched off (±100ms).
- [ ] Reduced-motion: no splash.
