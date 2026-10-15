# 04 — Reference Teardown (Reverse-Engineering)

> **Status:** 🔒 LOCKED as the visual vocabulary of the site.
> **Sources:** the 5 screenshots shared in chat (Innovax theme + hero), the files in `inspirations/`, `docs/website_content/pages_ref.jpeg`, the Innovax ThemeForest demo and https://strategicproposals.com/.
> **What we take:** layout patterns, component shapes, fonts, glass treatment, motion ideas.
> **What we don't take:** their colours (we use the brand palette in `02-brand-identity.md`), their copy, their images.

---

## 1. Innovax theme (primary UI reference)

### 1.0 Verified from the live demo (https://innovax.themeht.com/)
The ThemeForest preview shows a Cloudflare bot check, so the theme's own demo domain was inspected directly. Stack: WordPress + Elementor; theme JS uses **GSAP + ScrollTrigger + Lenis + SplitType**, Swiper, Odometer.

| Item | Innovax value (verified) | Adopted? |
|---|---|---|
| **Heading font** | **Mona Sans** (`--dt-typography-secondary-font-family`), H2 = 60px / 70px, weight 500, letter-spacing −3px | ~~Adopted~~ → replaced by **Figtree** (client, 15 Oct 2026, L11) |
| **Body font** | **Google Sans** (`--dt-typography-body-font-family`) | ~~Adopted~~ → replaced by **Figtree** (L11) |
| **Italic accent** | **Playfair Display italic**, weight 500, same size as heading, primary colour. Markup: `<h2>Our Approach to <span class="title-sub">Business Growth</span></h2>` | ~~Adopted~~ → accent words are upright **Figtree** in the brand blue (client, 15 Oct 2026, L15) (our colour: Azure) |
| Glass nav | `background: rgba(255,255,255,.05); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,.06); border-radius: 8px; padding: 7px 5px;` links 14px/500 UPPERCASE, `padding: 12px 15px`, radius 5px, hover/active = white bg + dark text | ✅ Adopted (values tuned to our tokens) |
| Glass utility | `.bg-glass-effect { background: rgba(255,255,255,.15); backdrop-filter: blur(20px); }` (eyebrow pills) | ✅ |
| Sticky header | after 300px: fixed, dark background, bottom radii 20px, slide-in `translateY(-40px → 0)` 500ms | ✅ Adapted (ours hides on scroll-down, see 06) |
| Card radius | **30px** most common; also 20px, 12px | ✅ Mapped to `--r-lg/xl` |
| Button | `padding: 4px 4px 4px 20px; radius 8px` + 45×45 icon square; text rolls on hover | ✅ = our **split button** |
| "14+" | Mona Sans **200px/200px, 700**, `background-image` + `background-clip:text; -webkit-text-fill-color: transparent` | ✅ |
| Lenis config | `{ duration: 1.2, easing: t => Math.min(1, 1.001 - Math.pow(2, -10 * t)) }`, desktop (>768px) only | ✅ Reference for ours |
| Heading reveal | words split, `clipPath: inset(0 100% 0 0) → inset(0)`, stagger .1, .5s, start `top 85%` | ✅ in catalogue |
| Scrubbed text | chars opacity .2 → 1 scrubbed (`.themeht-anim-text`) | ✅ "read-along" paragraphs |
| BG clip reveal | `clip-path: inset(0 2% round 30px)` scrubbed | ✅ `img-reveal` |
| Testimonial marquee | edge fade `mask-image: linear-gradient(to right, transparent, #fff 12.5%, #fff 87.5%, transparent)` | ✅ |
| Colours | primary `#4A7AF5`, text `#02091D`, dark `#000A1A`, light `#F5F8FE` | ❌ **Not adopted** (we use brand colours) |


### 1.1 Glass navigation pill (chat screenshot 1)
- A **single translucent rounded bar** (radius ≈ 12px) floating over the hero photo, inset from the page edge.
- Glass: `background: rgba(255,255,255,.08–.12)`, `border: 1px solid rgba(255,255,255,.14)`, `backdrop-filter: blur(16–20px) saturate(140%)`.
- Items: **UPPERCASE, 15–16px, semibold, white**, each with a thin chevron for dropdowns.
- **Active item = solid white pill** (radius 8px) with dark text; inactive = white text. The white pill **slides** between items on hover (GSAP Flip or a moving `::before`).
- **Our use:** desktop nav for inner pages (inner-page heroes are photo heroes). On the home hero, the nav follows the `hero_should` layout (centred links + active dot), styled with the same glass rules.
- **On scroll:** the bar shrinks (padding 12 → 8), turns into a stronger glass (`rgba(1,27,67,.72)` + blur 20) once over light sections, **hides on scroll-down and reveals on scroll-up** (see 06).

### 1.2 "Our Approach to *Business Growth*" section (chat screenshot 2)
- **Two-line heading:** line 1 in the grotesk (ink), line 2 in **italic serif, Azure 500**, at the same large size. This **two-voice heading** is used site-wide for section titles: "Behind *Our Expertise*", "Our Approach to *Winning Tenders*", etc.
- **Large image** (radius ≈ 28px) with a **notched top-right corner**: a solid **brand-blue info card** ("Business Hours: Mon–Fri 9:00–18:00 …") sits in the cut-out, separated by a canvas-coloured gap with **inverted rounded corners** on both joins.
  - **Our use:** About / Contact: *"Office Hours"* card (Mon–Fri, Sat–Sun, *Expert Support: Anytime*; hours TBD by client).
- **Image-filled giant number "14+"**: numerals ~200px, filled with a photo using `background-clip: text` (`-webkit-background-clip: text; color: transparent`).
  - **Our use:** **"10+"** with the label "Years of business development experience" (confirmed by the client). The photo inside the numerals slowly pans on scroll (background-position scrub).
- **Avatar stack** (3 overlapping 56px circles, 3px white ring) + **uppercase bold 2-line label** + paragraph + **split button** ("More About Us" + white square with arrow `→`).
- **Back-to-top** square button bottom-right (Azure, with up chevron) with a scroll-progress ring.

### 1.3 Blog card with notches (chat screenshot 3)
- White card, radius ≈ 24px, image top (radius 20).
- **Date tab notch:** the date label ("SEPTEMBER 8, 2026", uppercase 13px) sits in a **white tab cut into the bottom-left of the image** with inverted corners on both sides.
- Title (22–26px, grotesk, 2 lines), meta row: author (uppercase) + **outlined category pill**.
- **Corner action notch:** a **circular arrow button** sits in a **concave cut-out at the card's bottom-right**, with the canvas showing around it.
- Hover: image zooms 1.06, arrow rotates −45° → 0° and fills Azure.
- **Our use:** blog/insights listing, "Latest Insights" on home, related posts.

### 1.4 Team cards (chat screenshot 4)
- Heading "Behind *Our Expertise*" (two-voice).
- Portrait cards (radius ≈ 24px), **top-right notch with a round share button** (opens a small social popover).
- Bottom **dark gradient overlay**, name in white (24px), **role in a white pill** (uppercase 12px, ink text).
- Presented as a **slider with dots** (4 visible on desktop).
- **Our use:** "Meet the Team" (About page), only if the client supplies real team photos. Otherwise this section is omitted (no fake people; E-E-A-T).

### 1.5 Notch / inverted-corner technique (used by 1.2, 1.3, 1.4 and the hero)
**How Innovax does it (verified):** all three of its techniques are *fake* cut-outs painted in the section's background colour:
1. **SVG corner piece** (service/feature cards with an arrow button in the corner): a 111×111 SVG at top-right filled with the section background. Path: `M0 0C19.33 0 35 15.67 35 35V41C35 60.33 50.67 76 70 76H76C95.33 76 111 91.67 111 111V0H0Z`; rotated for other corners.
2. **Box-shadow fillets** (blog date tab): side boxes with `box-shadow: 0 20px 0 0 #fff` + `border-bottom-right-radius: 20px`.
3. **Radial-gradient fillets** ("Business Hours" card): 30×30 corners with `radial-gradient(at left bottom, transparent 70%, <bg> 72%)`.

**Our rule (🔒):** use painted fillets (below) **only on solid backgrounds**. Where the notch sits over an image, gradient or glass, use a **true CSS `mask`** (a `radial-gradient` hole for the corner combined with linear-gradients) or an SVG `mask-image` generated by a PHP helper, so the cut-out is real transparency.
```css
/* a cut-out tab that sits on top of an image */
.notch-tab { position:absolute; left:0; bottom:0; background:var(--ms-white);
  border-top-right-radius:20px; padding:10px 18px; }
.notch-tab::before, .notch-tab::after { content:""; position:absolute; width:20px; height:20px;
  background: radial-gradient(circle at 100% 0, transparent 19.5px, var(--ms-white) 20px); }
.notch-tab::before { left:0;   top:-20px; }   /* inverted corner above */
.notch-tab::after  { right:-20px; bottom:0; } /* inverted corner to the right */
```
Alternative for complex shapes: a single SVG `mask` per card generated by a PHP helper.

---

## 2. Hero reference (`inspirations/hero_should.jfif`)
Fully specified in **`05-hero-spec.md`** (locked).

---

## 3. "Sun Over" layout (`inspirations/for_section.jpeg`)
| Pattern | Description | Our use |
|---|---|---|
| **Pill image columns** | 3 tall rounded image columns of different widths, side by side | About hero / "Our Story" |
| **Floating labels** | Dark chips with an icon tile ("Método", "Consultoria", "Gestão Otimizada") overlapping image edges | Labels like *"Bid Strategy"*, *"Compliance"*, *"Evidence-Led Writing"*. They float with independent parallax speeds |
| **Big rounded section bottom** | Hero section ends with a large bottom-left radius (~80px) | Inner-page heroes |
| **Statement + 3 feature columns** | Heading with arrow button left; 3 icon-tile features right, separated by a vertical hairline | "Why Work With Us" summary on home |
| **Numbered list with accent line** | "01 / 02" numerals, bold lead line, body text, a vertical bar on the left | "Our Approach" 5-step process (01–05) |
| **Overlapping images + icon chip** | Two photos overlapping, a small icon chip bridging them | Beside the process list |

## 4. "Alternate Futures" layout (`inspirations/for_section (2).jpeg`)
| Pattern | Our use |
|---|---|
| **Hexagon service tiles** with icon + label | **Brand-perfect.** Services overview / sectors grid uses hexagon tiles (echoing the logo). On hover the hexagon fills with the brand gradient and lifts |
| **Curved wave section dividers** (blue bands with white arcs) | SVG wave dividers between major sections, subtly morphing on scroll (scrubbed path) |
| **Hexagon-masked portraits** | Optional team variant |
| **Client logo row** | Only with permission; else sector/accreditation row |
| **Dark footer** with recent posts, contacts, newsletter, social | Footer structure (see `08-sitemap-and-page-blueprints.md`) |

## 5. "Vencap" layout (`inspirations/for_section (3).jpeg`)
| Pattern | Our use |
|---|---|
| **Bento grid**: heading + stat card with avatar stack, image tiles with overlay cards, a blue card with arrow button, small stat tile ("120+") | "Why Work With Us" bento (7 reasons as tiles of different sizes) |
| **Image collage + feature list cards** (icon + title + line) | Sectors / "Expertise across industries" |
| **Dark feature card**: photo with floating tag chips ("Generate Growth", "Building the industries") + text + logos + outlined button | "Tender Search & Ongoing Bid Support" highlight |
| **Blue statement card + image** | Mission & Vision split |

## 6. Background art (`inspirations/`)
| File | Visual | Treatment on our site |
|---|---|---|
| `some_section_bg.jpeg` | Light-blue glass skyscraper, upward perspective, white frame lines | Inner-page hero backgrounds / CTA band. Slow vertical parallax; the thin frame lines draw in (DrawSVG) |
| `some_section_bg (2).jpeg` | Grey network-node sphere | "Tender Search" / "Our Approach" section. Rebuilt as a light **animated SVG/canvas node network** in navy-200 lines + azure nodes, drifting slowly; pauses off-screen |
| `some_section_bg (3).jpeg` | Painted azure ribbon curves | Mission & Vision. Rebuilt as an SVG ribbon in azure that **draws on scroll** |
| `WhatsApp … 9.34.00 PM (1).jpeg` | Thin azure/navy speed-lines swirl | Section transitions. SVG paths drawn with DrawSVG while scrubbing; signature "momentum" motif |
| `WhatsApp … 9.34.03 PM.jpeg` | Azure perspective grid-wave | Final CTA band / footer top. Rebuilt in CSS/SVG with a slow wave distortion |

> All backgrounds are **re-created as brand-coloured SVG or WebP**, not shipped as the Pinterest JPEGs (licence + quality).

## 7. Page-order reference (`docs/website_content/pages_ref.jpeg`)
Hand-drawn About page flow (🔒 locked):
**About Us → Our Story → Our Approach → Why Work With Us? → Our Mission & Vision.** The right side of the photo shows a second column starting "Bid…" (cropped), presumably the services list; to confirm with the client.

## 8. strategicproposals.com (motion reference)
**Verified stack:** Joomla + SP Page Builder; **no GSAP/Lenis**. Effects come from the builder's "Interaction" engine (scroll-scrubbed transforms), WOW.js + animate.css entrance reveals, vanilla-tilt, fixed-background parallax and easy-pie-chart counters. Font: Montserrat; purple/blue/pink palette. **We copy the feeling, not the implementation:** ours is GSAP + ScrollTrigger + Lenis, faster and smoother.

**Why "the elements move when you scroll":** every decorative element has keyframes mapped to its **scroll progress through the viewport** (0% = element top meets the viewport bottom, 100% = element bottom leaves the top), i.e. exactly ScrollTrigger `start: "top bottom", end: "bottom top", scrub`. Elements translate, rotate, scale and un-blur **continuously as you scroll**, not just once.

| Their pattern (verified) | What it does | Our version |
|---|---|---|
| **Logo-piece assembly** | 4 brand shapes fly in from off-screen (x ±350–500px, rotate ±74–137°, scale 2 → 1, opacity 0 → 1) and assemble as you scroll | **Our 3 logo shapes** (sky hexagon, azure bar, navy bar) assemble into the "M" in the Sectors/Why-Us section, scrubbed. **Signature moment** |
| **Rotating-dropping arrow** in micro-CTA rows ("Want to know more? → YES!") | Arrow points right, rotates to point down at 50–60% progress, then drops 88px | Micro-CTA rows between sections: *"Ready to win your next tender? → Let's talk"* |
| **Blur-zoom heading** | scale 2 + blur 15px → scale 1 + blur 0, scrubbed | Stats heading ("Our results in numbers") |
| **Stat rings** | SVG pie rings + % count-up when in view | Counters / rings for experience, sectors, response turnaround (real numbers only) |
| **Fixed confetti background** | Brand shapes stay still while content scrolls over | **True parallax layer** of small brand hexagons (not `background-attachment: fixed`, which breaks on iOS) |
| **Rising, spinning footer shape** | Large crescent rises and rotates behind the newsletter | Large faded logo-mark rotates/rises behind the final CTA band |
| **Curved SVG dividers** | Wave bottoms between sections | Our wave/diagonal dividers (scrub-morph) |
| **Tilt on cards** | vanilla-tilt max 15°, desktop only | `tilt` effect, max 6° (subtler, more Apple) |
| **Triage cards** ("Need urgent help on a bid?" → Call us now) | Question-led cards with intent-coloured CTAs | **"How can we help?" triage row**: *Bid deadline soon?* → Call now · *First tender?* → Book a consultation · *Losing bids?* → Get a tender review |

**Things to avoid from that site (verified problems):** transparent sticky header that shows content through it, 640KB HTML, heavy trackers and slow frames, pie-chart stats without sources, no structured data (an SEO gap we will fill), inconsistent claims (e.g. different "years" in different places, which our content also has, see open questions).

**Content ideas worth adapting for a bid consultancy:** triage-style entry points, free resources (tender readiness checklist, bid/no-bid template) as lead magnets, sector pages, a full-lifecycle FAQ, accreditations shown as proof (only those the client really holds).
