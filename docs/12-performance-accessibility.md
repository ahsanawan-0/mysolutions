# 12 — Performance and Accessibility Budget

> **Status:** 🔒 LOCKED. Heavy motion is allowed **only** within these budgets. If an effect breaks a budget, the effect is simplified, not the budget.

---

## 1. Core Web Vitals targets (field data, p75, mobile)
| Metric | "Good" threshold (Google) | Our target |
|---|---|---|
| **LCP** (Largest Contentful Paint) | ≤ 2.5s | **≤ 2.0s** |
| **INP** (Interaction to Next Paint, replaced FID in March 2024) | ≤ 200ms | **≤ 150ms** |
| **CLS** (Cumulative Layout Shift) | ≤ 0.1 | **≤ 0.05** |
| TTFB | — | ≤ 600ms (PHP OPcache on, page cache for static pages) |

Lab gates (Lighthouse, mobile, simulated throttling) on Home, one Service page, one Blog post: **Performance ≥ 95, Accessibility 100, Best Practices 100, SEO 100.**

## 2. Page weight budget (Home, first load, compressed)
| Asset | Budget |
|---|---|
| HTML | ≤ 40KB |
| Critical inline CSS | ≤ 14KB |
| Total CSS | ≤ 60KB |
| JS (initial) | ≤ 90KB (GSAP core + ScrollTrigger + Lenis + app) |
| JS (lazy, per feature) | ≤ 40KB each (SplitText, Draggable/Inertia, MorphSVG …) |
| Fonts | ≤ 4 WOFF2 files, ≤ 120KB total, 2 preloaded |
| Above-the-fold images | ≤ 200KB (hero photo AVIF ≤ 120KB at 1600w) |
| Total page (incl. lazy images) | ≤ 1.5MB |

## 3. Motion performance rules
1. Animate only **`transform`, `opacity`, `clip-path`, `filter` (sparingly)**. Never animate `width/height/top/left/margin/box-shadow` on scroll.
2. Scroll-linked effects use **ScrollTrigger with `scrub`** (one `requestAnimationFrame` via GSAP ticker + Lenis), never `scroll` event listeners that write styles.
3. `will-change` only during an animation, then removed.
4. **`backdrop-filter`** is expensive: max **3 glass surfaces visible at once**; never on elements that move on scroll every frame (static glass over a moving background is fine).
5. Parallax layers: max **4 layers per section**, images pre-sized (no runtime scaling of huge images).
6. Canvas/WebGL effects (node network): pause with `IntersectionObserver` when off-screen and when the tab is hidden; cap at 30fps on mobile; disabled on `Save-Data` / low-end devices (`navigator.hardwareConcurrency <= 4` → static SVG).
7. Long tasks < 50ms: split heavy init with `requestIdleCallback` / dynamic imports.
8. **Mobile:** reduce parallax intensity by 50%, disable pinned horizontal scroll (fallback: vertical stack), disable custom cursor, keep reveals.

## 4. CLS prevention
- Every `<img>`/`<video>`/`<iframe>` has `width` + `height` (or `aspect-ratio`).
- Fonts: `font-display: swap` + **metric-matched fallbacks** (`size-adjust`, `ascent-override`) so swap doesn't shift.
- No content injected above existing content after load (cookie banner is an overlay sheet, not a push-down bar).
- GSAP initial states are applied **before first paint** for above-the-fold elements via the `.js` class + CSS, not after load.

## 5. Accessibility: WCAG 2.2 AA (🔒)
| Area | Requirement |
|---|---|
| Semantics | Landmarks (`header`, `nav`, `main`, `footer`), one `h1`, logical heading order, lists for lists, `<button>` for actions and `<a>` for navigation |
| Keyboard | Everything operable by keyboard; visible `:focus-visible` ring; no keyboard traps (except intentional modal traps with ESC); **Skip to content** link first |
| Focus not obscured (2.4.11, new in 2.2) | Sticky header must not cover focused elements (`scroll-padding-top` = header height) |
| Target size (2.5.8, new in 2.2) | ≥ 24×24px minimum (we use 44×44 per Apple HIG) |
| Motion | `prefers-reduced-motion`: no parallax, no autoplay, no splash, no smooth-scroll hijack, instant reveals (content visible). **Pause/stop control** for any auto-moving content > 5s (sliders, marquees) (2.2.2) |
| Contrast | Text 4.5:1 (large 3:1); UI components and focus indicators 3:1 (palette pre-checked in `02`) |
| Images | Meaningful `alt` (describe purpose, not "image of"); decorative images `alt=""` / CSS backgrounds |
| Forms | Visible labels (floating labels still keep a real `<label>`), `autocomplete` attributes, errors in text + `aria-describedby` + `aria-invalid`, error summary on submit, no CAPTCHA puzzles (3.3.8 Accessible Authentication) |
| Carousels | ARIA carousel pattern, prev/next/pause buttons, slide labels "n of N" |
| Language | `<html lang="en-GB">` |
| Zoom/reflow | Works at 200% zoom and 320px width with no horizontal scroll (1.4.10) |
| Transparency | `prefers-reduced-transparency` → solid surfaces |
| Accessibility statement | `/accessibility/` page with contact for issues |

## 6. Testing checklist
- [ ] Lighthouse CI on every build (mobile + desktop).
- [ ] WebPageTest / PageSpeed Insights on staging (real 4G profile, Moto G-class device).
- [ ] axe DevTools: 0 violations on every template.
- [ ] Keyboard-only walkthrough of every page and the mega menu, mobile menu, sliders, forms, cookie banner.
- [ ] Screen reader smoke test: NVDA + Firefox, VoiceOver + Safari (macOS and iOS).
- [ ] Reduced-motion and reduced-transparency emulation pass.
- [ ] Real devices: iPhone (Safari), mid-range Android (Chrome), iPad, Windows laptop (Edge).
- [ ] Field CWV monitored in Search Console after launch (28-day window).
