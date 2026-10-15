# 05 — Home Hero Specification

> **Status:** 🔒 LOCKED (layout and behaviour). Copy is 🟡 placeholder until the content files arrive.
> **Source of truth:** `inspirations/hero_should.jfif` ("Choose Your Best Happy Land" shot).
> **Client instruction (verbatim intent):** *"I want the hero exact same. Where you see the card with 'Comfortable' written on it, this should be a **slider**. The 'Our Special Service' **border is animated, moving, shining, glowing**."*

The hero is rebuilt **1:1 in structure and composition**. Only the content, colours (brand navy/azure/sky instead of charcoal/salmon/purple) and icons change.

---

## 1. Anatomy of the reference (what we copy exactly)

```
┌──────────────────────────── outer white frame (radius 32) ─────────────────────────────┐
│ ┌──────────────────────── dark hero panel (radius 28) ───────────────────────────────┐ │
│ │ [logo]            Home•  Services  About  Sectors  Insights      Call   (Get Quote) │ │
│ │                                                       ┌─────────────────┐            │ │
│ │ ┌────────────┐                                        │ Our Special     │ ← tag      │ │
│ │ │ 3D badge   │                                        │ Service         │            │ │
│ │ └────────────┘                                   ┌────┴─────────────────┴────┐ ← animated │
│ │ Big Two-Line                         ┌────────┐  │  (icon)                    │   glowing  │
│ │ Headline Here                        │ prev   │  │  Comfortable   ← ACTIVE    │   border   │
│ │ Sub line one                         │ card   │  │  Facebook Ads, Google Ads, │ ┌──────┐  │
│ │ Sub line two                         │ (faded)│  │  LinkedIn Ads              │ │ next │  │
│ │ ┌──────────────────────────────────┐ └────────┘  └────────────────────────────┘ │(fade)│  │
│ │ │ ⌂ Type ▾ │ ◇ Price ▾ │ [ Search ]│                                             └──────┘  │
│ │ └──────────────────────────────────┘                  Give us a call … check our plans  │ │
│ │ Buy a home ⌄    Rent a home ⌄          ╭──────────────────────────────────────────────╯ │
│ └────────────────────────────────────────╯  ← S-curve notch: right part of panel ends     │
│  [mini card: photo · meta · price · btn]      [chip] [chip] [chip] [chip]   higher          │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

Elements in the reference, in reading order:
1. **Outer frame:** white (here `--ms-canvas`) page with the hero panel inset ~16–24px, all corners rounded.
2. **Dark hero panel** with a **building photo** on the right half fading into the dark on the left.
3. **Nav inside the panel:** small logo box left, centred links with an **active dot indicator** above "Home", right side "Log in" + outlined **pill** button.
4. **Left column:** ~~a pill badge with a 3D object~~ (removed by the client, 15 October 2026), a **2-line bold headline**, a **2-line sub-headline**, a **white search bar** (two dropdowns + coloured button), and **two quick links with chevron circles** underneath.
5. **Right column:** a **horizontal card carousel** (prev card faded and clipped, **active centre card**, next card faded and clipped). Each card is dark glass with a **round icon chip**, **title**, and a **one-line description**.
6. **"Our Special Service" tag** sitting above the active card, wrapped by a **light outline frame**: a rounded rectangle border that hugs the tag and extends down behind the card. **This border is the one that must be animated (moving shine / glow).**
7. **Bottom-right helper text** with an inline link.
8. **S-curve notch:** the panel's bottom edge is **lower on the left** (under the quick links) and **steps up on the right** via a smooth inverted curve, revealing the canvas underneath.
9. **Below the notch (on canvas):** a **mini listing card** on the left and a **row of 4 logo chips** (light grey rounded tiles) on the right.

---

## 2. My Solutionz mapping

| Ref element | My Solutionz version |
|---|---|
| Logo box | White/reversed horizontal logo (SVG), 140–160px wide |
| Nav links | Home • About • Services (mega menu) • Sectors • Insights (blog) • Contact. Active-page dot indicator in Azure 500 |
| "Log in" + "Sign Up" | **Phone link** `+44 7466 063564` (Phosphor `phone`) + **"Get a Free Quote"** outlined glass pill → `/contact-us/#quote` |
| ~~Purple pill + 3D object~~ | **Removed by the client (15 October 2026).** Was: glass pill containing a **3D-style hexagon "M" mark** (rendered SVG with gradient and depth, slow idle float) + eyebrow text e.g. *"UK Tender Writing & Bid Management"* |
| Headline | 2 lines, e.g. *"Win More Contracts. / **Crafted for Success.**"* (second line is the blue accent in the same upright Figtree, L11/L15). Final copy is TBD |
| Sub-headline | 2 lines summarising: tender writing, bid management, business support for UK businesses |
| Search bar | **"Quick-Start" bar**: white rounded bar with dropdown 1 = **Your sector** (Healthcare & Social Care, Transport, Cleaning, Education & Training, Security, Facilities Management, Building Maintenance, Other), dropdown 2 = **Support needed** (`<optgroup>` per category: *Bid & Proposal Management* › Tender & Bid Writing, Opportunity Identification, Expert Review · *Compliance & Virtual Assistance* › CQC Registration, Policy Management, Carbon Reduction Plan, Data Entry & Management, Care Plans · *Digital Presence* › Website Development, Social Media Management, Email Marketing, SEO & Analytics), button **"Get Started"** (Azure 650) → `/contact-us/?sector=…&service=…#quote` which pre-selects the form fields (the canonical of `/contact-us/` ignores these params). No personal data in the URL. |
| "Buy a home ⌄ / Rent a home ⌄" | **"Explore Services ⌄"** (scrolls to services) and **"Sectors We Serve ⌄"** (scrolls to sectors). Chevron inside a small white circle, exactly like the reference |
| Building photo | London glass-office / skyline photo, cool-graded, with a navy gradient fade to the left. **Parallax:** moves at 0.85× scroll speed and scales 1.08 → 1.0 on load |
| Card carousel | **Services slider** (see §4). Card 1 example: chip *Bid & Proposal* · icon `pen-nib` · **"Tender & Bid Writing"** · *"Evidence-led responses written to score."* |
| "Our Special Service" tag | Pill in Azure 500 → 650 gradient, white text **"Our Special Service"** (or "Our Core Services"; copy TBD). Wrapped by the **animated glowing border frame** (see §5) |
| Bottom-right helper text | *"Give us a call on **+44 7466 063564** and we can set you up, or **book a free consultation**."* (link in Sky 300) |
| Mini listing card | **Mini "Insight" or "Free Tender Readiness Check" card**: thumbnail · eyebrow · title · small Azure button. Proposal: links to the latest blog post |
| 4 logo chips | **Sector chips** (icon + name) in the same light grey rounded tiles: Healthcare, Transport, Security, Facilities Mgmt … as an **infinite marquee** on smaller widths. *We do not show third-party client or portal logos unless the client supplies permission.* |

---

## 3. Layout and sizing

| Property | Desktop ≥1280 | Laptop 1024–1279 | Tablet 768–1023 | Mobile <768 |
|---|---|---|---|---|
| Outer frame padding | 20px | 16px | 12px | 8px |
| Panel radius | 28px | 28px | 24px | 22px |
| Panel height | `min(100svh − 40px, 860px)`, min 720px | min 680px | auto | auto |
| Columns | 5/12 left · 7/12 right | 6/6 | stacked | stacked |
| Headline size | `--fs-display` | 56px | 48px | 40px |
| Card size | 300×190 | 280×180 | 280×180 | 82vw × 180 |
| Visible cards | prev (40% clipped) · active · next (40% clipped) | same | active + next peek | active + next peek |
| Quick-Start bar | inline, 520px | inline | full width | stacked fields, full-width button |
| Notch depth | 32px | 32px | 24px | removed (straight bottom) |

**Notch technique:** the panel is a normal rounded rectangle. The canvas-coloured **bottom-right tab** (which holds the mini card + chips) overlaps the panel's bottom with `border-top-left-radius: 28px`. Two **inverted-corner pseudo-elements** (`radial-gradient(circle at 0 0, transparent 27.5px, var(--ms-canvas) 28px)`) create the smooth S-curve, the same CSS technique used for the Innovax notched cards (see `04-reference-teardown.md`). No clip-path, so it stays responsive and crisp.

---

## 4. Services card slider (🔒 must be a slider)

- **Library:** GSAP-driven custom slider (`gsap` + `Draggable` + `InertiaPlugin`; all GSAP plugins are free since v3.13). **Swiper 11** (`effect: 'creative'`, `centeredSlides`, `loop`) is the acceptable fallback. Choose one in the tech doc, never both.
- **Cards (12 sub-services, grouped by category in this order):** Tender & Bid Writing · Opportunity Identification · Expert Review → CQC Registration · Policy Management · Carbon Reduction Plan · Data Entry & Management · Care Plans → Website Development · Social Media Management · Email Marketing · SEO & Analytics. Each card shows a small **category chip** in its category colour (Azure / Navy / Sky) above the title. The card links to its sub-service page.
- **States:**
  - *Active:* opacity 1, scale 1, glass `rgba(255,255,255,.08)` + `backdrop-filter: blur(16px) saturate(140%)`, 1px border `rgba(255,255,255,.14)`, icon chip filled Azure 500.
  - *Neighbours:* opacity .45, scale .92, blur(1px), clipped by the column edge with a gradient mask (`mask-image: linear-gradient(90deg, transparent, #000 30%, #000 70%, transparent)`) so they fade out exactly like the reference.
- **Behaviour:** auto-advance every **3.5s**; transition **700ms** `cubic-bezier(.22,1,.36,1)`; pause on hover, focus and while the tab is hidden; drag/swipe with inertia; keyboard ← → when focused; prev/next buttons + dots that are visually subtle but present for accessibility.
- **Accessibility:** `role="region"` `aria-roledescription="carousel"` `aria-label="Our services"`; each card `role="group"` `aria-roledescription="slide"` `aria-label="2 of 12"`; a **pause button** (WCAG 2.2.2); with `prefers-reduced-motion` no autoplay and instant transitions.
- **Click:** each card links to its service page.
- **SEO:** all 12 cards are in the server-rendered HTML (not injected by JS), so their text and links are crawlable.

---

## 5. The animated "Our Special Service" border (🔒 must shine/glow and move)

**Effect:** a light streak continuously **travels around the rounded-rectangle frame** (like light running along a glass edge), with a soft outer glow that pulses gently. The tag pill also gets a subtle **shine sweep** along the 120° brand diagonal every few seconds.

**Implementation (CSS-first, GPU-cheap):**
```css
@property --angle { syntax: '<angle>'; inherits: false; initial-value: 0deg; }

.special-frame {
  position: absolute; inset: -14px -14px auto -14px; /* hugs tag, extends behind card */
  border-radius: 22px;
  padding: 1.5px;                              /* border thickness */
  background: conic-gradient(from var(--angle),
      rgba(255,255,255,.10) 0deg 250deg,
      #8DD6FF 290deg, #FFFFFF 310deg, #009AF0 330deg,
      rgba(255,255,255,.10) 360deg);
  -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
  -webkit-mask-composite: xor; mask-composite: exclude;   /* keep only the ring */
  animation: ms-orbit 4s linear infinite;
}
.special-frame::after {                       /* soft glow halo */
  content: ""; position: absolute; inset: -6px; border-radius: inherit;
  background: inherit; filter: blur(14px); opacity: .55;
  animation: ms-pulse 2.8s ease-in-out infinite;
}
@keyframes ms-orbit { to { --angle: 360deg; } }
@keyframes ms-pulse { 50% { opacity: .9; } }
@media (prefers-reduced-motion: reduce) {
  .special-frame, .special-frame::after { animation: none; }
}
```
- Fallback for browsers without `@property`: rotate an oversized conic-gradient pseudo-element with `transform: rotate()` inside an `overflow:hidden` ring.
- When the slider changes card, the frame does a quick **"breathe"** (scale 1 → 1.015 → 1, 400ms) so it feels connected to the active card.

---

## 6. Entrance choreography (after the splash screen hands off)

Timeline (GSAP, total ≈ 1.6s, starts when the splash logo morphs into the nav logo, see `07-splash-screen-logo-animation.md`):

| t (s) | Element | Animation |
|---|---|---|
| 0.00 | Panel | `clip-path: inset(6% 6% 6% 6% round 28px)` → `inset(0 round 28px)`, 0.9s `expo.out` |
| 0.10 | Background photo | scale 1.15 → 1.0, 1.4s. **Opacity stays 1 from first paint** (it is the LCP element; opacity-0 elements are not LCP candidates) |
| 0.30 | Nav items | y −12 → 0, opacity, stagger 0.05 |
| ~~0.35~~ | ~~Badge~~ | removed with the badge (15 October 2026) |
| 0.45 | Headline | **SplitText lines**, y 110% → 0 with mask, stagger 0.08, 0.9s `expo.out` |
| 0.70 | Sub-headline | opacity + y 16 → 0 |
| 0.80 | Quick-Start bar | y 24 → 0, opacity |
| 0.90 | Card slider | cards slide in from x +80, stagger 0.08; the frame border **draws itself** (conic from 0 → full) then starts orbiting |
| 1.10 | Helper text + quick links | fade up |
| 1.20 | Bottom tab (mini card + chips) | rises from y 40, chips stagger 0.06 |

**Hero on scroll (ScrollTrigger, scrub):** background photo `yPercent: 12` (parallax), left copy `yPercent: −8` and fades to 0.6, card slider `yPercent: −14` (moves faster, adds depth), panel radius grows 28 → 40 as it leaves the viewport.

---

## 7. Hero acceptance checklist
- [ ] Visually matches `hero_should.jfif` composition at 1440×900 (side-by-side check).
- [ ] Card carousel is a real slider (autoplay, drag, keys, pause).
- [ ] "Our Special Service" border visibly shines, moves and glows continuously.
- [ ] LCP element (headline or hero photo) renders **< 2.5s** on 4G; the photo is `fetchpriority="high"` and preloaded; the splash does **not** delay LCP (see 07).
- [ ] H1 is the headline and is present in server HTML.
- [ ] Works at 360px width with no horizontal scroll.
- [ ] Reduced-motion users get a static, fully readable hero.
