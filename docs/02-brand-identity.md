# 02 — Brand Identity (Logo, Colour, Type, Iconography)

> **Status:** 🔒 LOCKED: colours (pixel-sampled from the logo files) and typography (Innovax fonts, as requested).
> **Rule:** Nothing from the current mysolutionz.co.uk colours, fonts or layout is reused. Only its **content** and **icons** may be reused (see `09-content-inventory.md`).

---

## 1. Logo assets on file

| File | Use |
|---|---|
| `logos/My Solution Horizontal.jpg (1).jpeg` | Header, footer, OG image composition, email signature |
| `logos/Vertical Lockup on White Background.jpg (1).jpeg` (1563×1563) | Splash screen, favicon/app-icon source, social avatars |

### Logo anatomy (important for the splash animation and decorative graphics)

The mark is an abstract **"M"** made of **three hexagon-derived shapes**:

1. **Small hexagon** (bottom-left, sky blue `#8DD6FF`), partly behind the first bar.
2. **Tall slanted bar** (middle, azure `#009AF0`), a hexagon stretched diagonally top-left → bottom-right.
3. **Tall slanted bar** (right, navy `#003C8C`), parallel to the first, offset to the right.

Together they read as an **M** rising upward to the right, a visual metaphor for **growth**. The wordmark "My Solutionz" and the tagline **"Solutions Crafted for Success"** are set in a **condensed, squared, rounded-corner sans**: identified in P07 by pixel-fitting the JPEGs as **Khand** (OFL, Indian Type Foundry; Regular for the wordmark, Medium for the tagline, IoU 0.98 at a 1.00 ink-weight ratio; the earlier *Saira Condensed* guess scored 0.32). Khand is used at build time only (`npm run logo` outlines it to paths); it is never loaded on the site.

**Design consequences (locked):**
- **The hexagon is the brand's shape motif.** We use it for icon containers, bullets, loader progress, section dividers, the team-card mask and the parallax background particles. The "Alternate Futures" reference (`inspirations/for_section (2).jpeg`) with hexagon service tiles matches this exactly.
- **The 60° diagonal** of the bars is the brand's motion angle. Diagonal wipes, clip-path reveals and the shine sweep on the "Our Special Service" border travel along this angle (≈ −60° / 120°).

### Logo files (resolved in P07)
- ✅ **Vector logo rebuilt from the JPEGs**: `public/assets/brand/` (`logo-mark.svg`, `logo-mark-white.svg`, `logo-horizontal.svg`, `logo-horizontal-white.svg`, `logo-vertical.svg`, `logo-splash.svg`, `logo-512.png`) by `npm run logo`: the mark as 3 exact polygons, the wordmark and tagline as outlined Khand, each lockup measured from its own JPEG (overlay IoU 0.99). The client's original vector file is still welcome as a cross-check but no longer blocks anything.
- ✅ White/reversed version (`logo-horizontal-white.svg`: white type, navy bar lightened to Navy 400) for dark surfaces; the header inlines one SVG and themes it with CSS.

---

## 2. Colour system

### 2.1 Exact logo colours (pixel-sampled)

| Token | Hex | Where in logo |
|---|---|---|
| `--ms-sky` | **`#8DD6FF`** | Small hexagon |
| `--ms-azure` | **`#009AF0`** | Middle bar |
| `--ms-navy` | **`#003C8C`** | Right bar |
| `--ms-black` | `#000000` | Wordmark + tagline (we use near-black ink instead, see below) |

### 2.2 Full family (tints and shades generated from the logo anchors)

**Azure** (anchor = 500)

| Step | Hex | Contrast vs white | Use |
|---|---|---|---|
| 50 | `#F5FBFE` | 1.04 | Section wash |
| 100 | `#C4E8FC` | 1.29 | Chips, hover fills |
| 200 | `#93D4F9` | 1.61 | Glow, borders on dark |
| 300 | `#62C1F6` | 2.00 | Decorative lines |
| 400 | `#31ADF3` | 2.50 | Glow gradient stop |
| **500** | **`#009AF0`** | **3.05** | Brand accent, large display words, icons, glows. **Not for body text on white.** |
| 600 | `#0080CA` | 4.25 | Large text, focus rings |
| 650* | `#0072BC` | 5.08 | **Primary button background** (white label passes AA) |
| 700 | `#0067A4` | 6.04 | Text links on white |
| 800 | `#004D7F` | 8.87 | Link hover |
| 900 | `#003459` | 12.84 | — |
| 950 | `#001A33` | 17.56 | — |

**Navy** (anchor = 700)

| Step | Hex | Contrast vs white | Use |
|---|---|---|---|
| 50 | `#F5F7FA` | 1.07 | Page background alt |
| 100 | `#D2DCEB` | 1.38 | Dividers |
| 200 | `#AFC2DB` | 1.82 | Muted text on dark |
| 300 | `#8CA7CB` | 2.47 | — |
| 400 | `#698CBB` | 3.46 | — |
| 500 | `#4671AC` | 4.98 | Secondary text on light |
| 600 | `#23579C` | 7.20 | — |
| **700** | **`#003C8C`** | **10.37** | Headlines accent, button hover, dark cards |
| 800 | `#012C68` | 13.45 | Dark sections |
| 900 | `#011B43` | 16.95 | **Hero / footer base** |
| 950 | `#020B1F` | 19.62 | Deepest shadow, splash background |

**Sky** (anchor = 300)

| Step | Hex | Use |
|---|---|---|
| 50 | `#FAFDFF` | — |
| 100 | `#D6F0FF` | Light tag fills |
| 200 | `#B1E3FF` | Highlight on dark |
| **300** | **`#8DD6FF`** | Accent on dark backgrounds (11.17:1 on `#04163A`), glow highlight |
| 700 | `#427A9B` | — |

\* `650` is an in-between step added specifically so white button labels pass WCAG AA.

### 2.3 Neutrals (Apple-style, cool-tinted to sit with the blues)

| Token | Hex | Use |
|---|---|---|
| `--ms-white` | `#FFFFFF` | Cards, surfaces |
| `--ms-canvas` | `#F5F7FB` | Default page background (cool off-white, like the Innovax light sections) |
| `--ms-mist` | `#EEF2F8` | Alternate section background |
| `--ms-line` | `#E3E8F0` | Hairline borders (1px) |
| `--ms-ink` | `#0B1220` | Primary text (18.72:1 on white) |
| `--ms-ink-2` | `#5B6474` | Secondary text (5.97:1 on white) |
| `--ms-ink-3` | `#8A93A3` | Placeholder, captions (decorative only, not for essential text) |
| `--ms-midnight` | `#04163A` | Dark glass surfaces in hero |

### 2.4 Semantic colours

| Token | Hex | Use |
|---|---|---|
| `--ms-success` | `#16A34A` | Form success |
| `--ms-warning` | `#D97706` | Notices |
| `--ms-danger` | `#DC2626` | Form errors |
| `--ms-focus` | `#0080CA` | Focus ring (2px + 2px offset) |

### 2.5 Signature gradients

```css
--ms-grad-brand:  linear-gradient(120deg, #8DD6FF 0%, #009AF0 45%, #003C8C 100%); /* logo order, 120deg = logo diagonal */
--ms-grad-glow:   conic-gradient(from var(--angle), transparent 0 70%, #8DD6FF 80%, #009AF0 88%, transparent 100%); /* animated border */
--ms-grad-hero:   radial-gradient(1200px 600px at 75% 20%, rgba(0,154,240,.28), transparent 60%),
                  radial-gradient(900px 500px at 10% 90%, rgba(141,214,255,.14), transparent 60%),
                  #011B43;
--ms-grad-text:   linear-gradient(100deg, #009AF0, #003C8C); /* accent words on the display size only */
```

### 2.6 Colour usage rules (locked)
1. **60 / 30 / 10.** 60% neutrals (canvas/white), 30% navy (dark sections, text accents), 10% azure + sky (CTAs, glows, highlights).
2. **Azure `#009AF0` never carries small text on white.** Use Azure 700 `#0067A4` for links and Azure 650 `#0072BC` for buttons.
3. **Dark sections use Navy 900 `#011B43`, never pure black.**
4. **Sky `#8DD6FF` is for dark backgrounds only** (it disappears on white).
5. The three logo colours appear **together in logo order (sky → azure → navy)** wherever we show a multi-colour sequence (process steps, charts, gradient).

---

## 3. Typography (🔒 LOCKED: Figtree everywhere, 14px paragraphs; client decision, 15 October 2026)

The client replaced the three Innovax fonts (Mona Sans headings, Google Sans body, Playfair Display Italic accent words) with **one family: Figtree** (SIL OFL), **self-hosted as a subset variable WOFF2** (`public/assets/fonts/figtree-var.woff2`, weights 400–700, ~18 KB; no Google Fonts CDN at runtime, for GDPR and speed). Built by `npm run fonts` from `@fontsource-variable/figtree`.

| Role | Family | Weights | Purpose |
|---|---|---|---|
| **Headings, display, nav, buttons** | **Figtree** | 600 (headings), 500 (UI), 700 (big numbers) | All headings, nav, buttons, card titles, image-filled "10+" |
| **Accent words** | **Figtree**, upright | same as the heading | 1–3 accent words per heading, set apart by **colour only** (Azure 600 on light, Sky 300 on dark, the brand gradient on the display size). No italic, no serif |
| **Body / UI** | **Figtree** | 400, 500 | Paragraphs, lists, forms, captions |

- **Fallback stack (metric-matched to prevent CLS):** `"Figtree", "Figtree Fallback", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`. The fallback `@font-face` (Arial) uses `size-adjust` / `ascent-override` so the swap doesn't shift layout. CSS keeps `--font-display` / `--font-body` as aliases of `--font-sans`.
- **Logo wordmark** stays **outlined SVG** (Khand, build-time only in `scripts/logo.mjs`; never loaded as a web font).

### Type scale (from a 14px paragraph; headings step up by ~1.25, fluid 360 → 1440px)

| Token | Size (min → max) | Line-height | Tracking | Weight |
|---|---|---|---|---|
| `--fs-display` | 36 → 56px | 1.1 | −0.025em | 600 |
| `--fs-h1` | 32 → 44px | 1.12 | −0.02em | 600 |
| `--fs-h2` | 26 → 34px | 1.18 | −0.015em | 600 |
| `--fs-h3` | 20 → 24px | 1.25 | −0.01em | 600 |
| `--fs-h4` | 16 → 18px | 1.3 | −0.005em | 600 |
| `--fs-lead` | 16px (UI: FAQ questions, large buttons, quotes; not paragraphs) | 1.55 | 0 | 400 |
| `--fs-body` | **14px** | 1.6 | 0 | 400 |
| `--fs-ui` / `--fs-ui-lg` | 14px / 16px | — | — | 500 |
| `--fs-small` | 13px | 1.5 | 0 | 400 |
| `--fs-eyebrow` | 12px | 1.3 | +0.1em, UPPERCASE | 600 |

Rules: one H1 per page; accent words max **2–3** per heading, colour only; every paragraph is 14px; nothing readable below 12px (eyebrow labels), captions and legal 13px.

---

## 4. Iconography

- **Library (locked): [Phosphor Icons](https://phosphoricons.com/)** (MIT) using the **"light"** weight for content icons and **"regular"** for UI controls. Reasons: 1,500+ icons, six weights (good for the Apple-style thin aesthetic), and it ships as SVG sprite / web font / individual SVGs, which suits plain PHP.
  - Alternative if preferred: **Lucide** (ISC). Pick one; never mix libraries.
- **Delivery:** inline SVG via a PHP helper `icon('handshake', 'light')` that reads from a local `/assets/icons/` sprite. No icon CDN (privacy, performance, no FOUC).
- **Content icons from the current site** may be reused **as concepts**: each old icon is mapped to its Phosphor equivalent in `09-content-inventory.md`.
- **Containers:** content icons sit in a **rounded hexagon** (brand motif) or a 56px rounded-square glass tile; stroke colour Azure 600, tile fill Azure 50.
- Social icons: Phosphor `linkedin-logo`, `instagram-logo`, `facebook-logo`.

---

## 5. Imagery direction
- **People-first, UK-office photography**: real teams in meetings, tablets, documents (as in the Innovax/Vencap/Sun Over references). Prefer real company photos when available; otherwise licensed stock (Unsplash/Pexels licence, or paid).
- **Colour grade:** cool, slightly desaturated with blue shadows so photos sit inside the navy/azure palette.
- **Section backgrounds** (from `inspirations/`): glass-skyscraper wash, network-node sphere, flowing azure line art, and the azure grid-wave. These are used as **low-opacity parallax layers**, never behind body text at full strength. Recreate as SVG/WebP in brand colours; don't ship the Pinterest JPEGs (licence and quality).
- **Image formats:** AVIF + WebP with JPEG fallback, `srcset`, explicit `width/height`.
