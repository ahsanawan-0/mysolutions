# 06 — Motion, Parallax and Scroll Experience

> **Status:** 🔒 LOCKED (libraries, principles, catalogue). Section-specific choreography is assigned in `08-sitemap-and-page-blueprints.md`.
> **Client intent:** *"Parallax effects, unique sections… like strategicproposals.com: when we scroll, the elements move. The best UX. Use proper packages for icons and GSAP for animations."*

---

## 1. Libraries (🔒)
| Package | Role |
|---|---|
| `gsap` (3.13+) | Core tweening, timelines |
| `gsap/ScrollTrigger` | All scroll-linked animation (reveal, scrub, pin, parallax) |
| `gsap/SplitText` | Line/word/char headline reveals (with `mask: "lines"` and `autoSplit` for responsive re-splitting) |
| `gsap/DrawSVGPlugin` | Line-art backgrounds, process connectors, icon strokes |
| `gsap/MorphSVGPlugin` | Wave dividers, hex ↔ circle icon morphs |
| `gsap/Flip` | Splash → header logo, nav active pill, filter grids (blog categories) |
| `gsap/Draggable` + `InertiaPlugin` | Sliders with momentum |
| `gsap/CustomEase` | Brand easings (`apple`, `out`, `inout`, `spring`) |
| `lenis` | Smooth scrolling (synced with ScrollTrigger) |

> Since GSAP 3.13 (2025) **all GSAP plugins are free**, including SplitText, MorphSVG, DrawSVG and Inertia. They are installed from npm (`gsap`) and imported per module.

### Bootstrapping
```js
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
gsap.registerPlugin(ScrollTrigger);

const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
if (!reduce) {
  const lenis = new Lenis({ lerp: 0.1, smoothWheel: true });   // touch devices keep native scrolling
  lenis.on('scroll', ScrollTrigger.update);
  gsap.ticker.add(t => lenis.raf(t * 1000));
  gsap.ticker.lagSmoothing(0);
}
const mm = gsap.matchMedia();   // every effect is declared inside mm.add() with breakpoints + reduced motion
```

---

## 2. Motion principles (Apple + brand)
1. **Purposeful:** every motion explains hierarchy, continuity or feedback. No motion for its own sake.
2. **Content first:** text is readable within **≤ 700ms** of entering the viewport; reveals never hide content for long.
3. **Physical:** things move with weight. Heavy elements are slower, small elements are quicker, and staggers flow in reading order.
4. **One brand angle:** diagonal moves use the logo's **120°/60°** axis.
5. **Scroll is the timeline:** most section motion is **scrubbed** to scroll (the strategicproposals feel), so users control the pace; entrance reveals play once.
6. **Restraint:** maximum **one "hero moment" per section**; the rest are subtle (fade + 24px rise).

---

## 3. Motion catalogue (reusable, data-attribute driven)
Each effect is a module activated by a `data-anim` attribute in the PHP templates. That keeps templates declarative and the prompts consistent.

| `data-anim` | Effect | Spec |
|---|---|---|
| `fade-up` | Default reveal | y 24 → 0, opacity 0 → 1, 0.7s `out`, start `top 85%`, once. Options (P10): `data-x`, `data-y` (start offset px), `data-delay` (s) |
| `stagger` | Children reveal in sequence | children `fade-up`, stagger 0.08 |
| `split-lines` | Headline line reveal (masked) | SplitText lines, `yPercent 110 → 0`, stagger 0.1, 0.9s `out` |
| `split-chars` | Display word reveal | chars, stagger 0.02, used max once per page |
| `two-voice` | Two-voice heading | line 1 slides in from left 40px, *italic accent* clips in from the right with a slight skewX(−8deg → 0) |
| `parallax` | Layer speed | `data-speed="0.8"` → y offset = (1 − speed) × distance, scrubbed |
| `img-reveal` | Image clip reveal | `clip-path: inset(100% 0 0 0 round 28px)` → `inset(0 round 28px)` + inner image scale 1.2 → 1, scrubbed |
| `img-zoom-out` | Ken-Burns on scroll | inner image scale 1.15 → 1, scrubbed over section |
| `counter` | Number count-up | from 0, 1.6s `out`, formats "10+", "12" |
| `fill-text` | Image-filled numerals pan | `background-position` 0% → 100% scrubbed |
| `draw` | SVG line draw | DrawSVG 0 → 100%, scrubbed |
| `pin-steps` | Pinned process | section pinned for N × 100vh, steps change at progress thresholds, progress line fills |
| `h-scroll` | Horizontal scroll track | pinned, track translates x by (track − viewport), scrubbed; disabled < 1024px |
| `stack-cards` | Sticky stacking cards | each card `position: sticky`; previous cards scale to 0.94 and dim as next covers them |
| `marquee` | Infinite ticker | GSAP `horizontalLoop` helper, direction follows scroll velocity, hover = slow |
| `magnetic` | Magnetic buttons | button follows the pointer within 12px, spring back (pointer devices only) |
| `tilt` | 3D tilt cards | max 6°, glare highlight, pointer devices only |
| `wave` | Section divider morph | MorphSVG between 2 wave paths, scrubbed |
| `nodes` | Network-node background | canvas, slow drift, pointer repulsion, paused off-screen |
| `velocity-skew` | Scroll-velocity skew | images skew up to 4° with scroll velocity, then settle |

---

## 4. Global behaviours
| Behaviour | Spec |
|---|---|
| **Header** | Transparent over hero → glass after 80px; **hides on scroll down, reveals on scroll up** (y −100% ↔ 0, 0.4s); shrinks 88 → 68px |
| **Scroll progress** | 2px azure bar under the header (blog posts only) + back-to-top ring (all pages) |
| **Page transitions** | **No SPA/Barba.** Full page loads (best for SEO and simplicity). Optional: a quick 300ms diagonal wipe on exit via the View Transitions API (`@view-transition { navigation: auto; }`) where supported |
| **Anchor links** | Lenis `scrollTo` with header offset |
| **Hover** | Cards lift −4px + shadow; images zoom 1.05; arrows slide 4px; buttons fill from the left in 250ms |
| **Cursor** | Optional custom cursor (C30), desktop only |

---

## 5. Reduced motion and fallbacks (🔒)
- `prefers-reduced-motion: reduce` → Lenis off, no parallax, no pin/h-scroll (stacked layout), no autoplay, no splash, reveals become instant (content visible), marquee becomes a static wrap list.
- **No-JS / crawler:** all content visible by default; hidden initial states exist only under `html.js`. GSAP never removes content from the DOM.
- `gsap.matchMedia()` cleans up and rebuilds effects on breakpoint change (no broken pins after resize).
- `ScrollTrigger.refresh()` after fonts load (`document.fonts.ready`) and after lazy images in pinned sections load.

## 5b. SEO-safe motion rules (🔒, from `10-seo-specification.md` §9)
- **Googlebot doesn't scroll or click:** reveals use `once: true`; scrubbed "read-along" text starts at **opacity 0.2, never 0**; the rendered screenshot in Search Console's URL Inspection must show all text (launch gate).
- **Never fade the hero H1 or hero photo from opacity 0** (LCP). Use transform/clip-path entrances.
- **Lenis must not break** `#hash` deep links, browser history or the back button: on load with a hash, let the browser jump natively, then initialise Lenis; never force scroll position on load; `history.scrollRestoration` stays `auto`. No back-button hijacking (Google spam policy).
- Pinned sections: ScrollTrigger pin-spacers are created before first paint of that section (no CLS).

---

## 6. strategicproposals.com patterns to adopt (verified, see `04` §8)
That site has **no GSAP**. Its "elements move as you scroll" effect is a builder engine that maps each element's keyframes to its **progress through the viewport** (0% = top of element at bottom of viewport, 100% = bottom of element leaves the top). This is exactly a scrubbed ScrollTrigger: `start: "top bottom", end: "bottom top", scrub: 0.3–0.6`, with a timeline of duration 1 and each tween placed at `keyframe/100`.

| # | Pattern | GSAP implementation |
|---|---|---|
| S1 | **Logo-mark assembly** (signature) | 3 SVG pieces of our mark, `tl.from(piece, {x, y, rotation: ±120, scale: 2, autoAlpha: 0, ease: "none"})` at offsets 0 / .05 / .1; `scrollTrigger: { start: "top bottom", end: "top 35%", scrub: 0.6 }`. Optional pin `end: "+=120%"` so it finishes assembling before moving on. Used **once** (Why Us or Sectors section) |
| S2 | **Micro-CTA arrow** | `scrub: 0.3`; `.fromTo(arrow, {rotation: -90}, {rotation: 0, duration: .1}, .5)` then `.to(arrow, {y: 88, duration: .25}, .6)`. Rows such as *"Ready to win your next tender? ↓ Let's talk"* between major sections |
| S3 | **Blur-zoom heading** | `fromTo(h, {scale: 2, filter: "blur(15px)", autoAlpha: 0}, {scale: 1, filter: "blur(0px)", autoAlpha: 1, scrollTrigger: {start: "top bottom", end: "center center", scrub: true}})`. Desktop only; stats heading only |
| S4 | **Stat rings + count-up** | SVG circle `strokeDashoffset` + number tween, `toggleActions: "play none none none"` |
| S5 | **Brand-shape parallax background** | Small hexagons in an absolute layer, each with its own `data-speed`; never `background-attachment: fixed` (broken on iOS) |
| S6 | **Batch staggered entrances** | `ScrollTrigger.batch('.card', { onEnter: b => gsap.from(b, {y: 40, autoAlpha: 0, stagger: .1, duration: .8, ease: 'power3.out'}), once: true })` |
| S7 | **Card tilt** | `gsap.quickTo` on `rotationX/Y` (max 6°), `transformPerspective: 1000`, pointer devices only via `gsap.matchMedia` |
| S8 | **Animated key-word underline** | Hand-drawn SVG underline under one word per page (e.g. "*win*"), DrawSVG on enter |
| S9 | **Rotating words** | "Compliant / Compelling / Competitive" in a masked line, `yPercent` swaps on a `repeat: -1` timeline (pausable) |
| S10 | **Rising/rotating shape behind final CTA** | Large faded logo-mark, scrub `y` + `rotation` |
| S11 | **Curved dividers** | SVG waves, optional scrub morph (MorphSVG) |

**Improvements over strategicproposals (🔒):** Lenis smooth scroll; glass header that hides on scroll-down (theirs is transparent and shows content through it); pinned process section; real clip-path image reveals; full structured data (Organization, Service, BlogPosting, Breadcrumbs); everything gated by `prefers-reduced-motion`; a far lighter page (they ship ~640KB HTML plus many trackers).

**Innovax motion reference (verified):** Lenis `{duration: 1.2, easing: t => Math.min(1, 1.001 - Math.pow(2, -10 * t))}` on > 768px only; word-split headings with `clipPath inset(0 100% 0 0) → inset(0)`; scrubbed char opacity .2 → 1 for statement paragraphs; pinned split-image crossfades and horizontal sticky panels with `containerAnimation`. All of these are in our catalogue (§3).
