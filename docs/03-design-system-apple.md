# 03 — Design System (Apple Design Principles for the Web)

> **Status:** 🔒 LOCKED (principles and tokens). Component list is the build checklist.
> **Requested source:** https://github.com/hosseinirtr/apple-design-principles
> **What that repo actually contains (verified):** a single `README.md` (2023, no licence) that is a short article: *follow Apple's Human Interface Guidelines, study Apple's products, keep it simple, focus on user experience*, plus links to the HIG, Apple Design Resources and Design+Code. It has **no tokens, no numbers, no CSS**.
> **Therefore:** the concrete rules below are derived from the sources that repo points to: **Apple Human Interface Guidelines** (https://developer.apple.com/design/human-interface-guidelines/) and the conventions of **apple.com**, translated to the web and to our brand.
> **Optional build-time helper:** the Claude Code plugin `NutshellEngineering/apple-design-skill` (Apache-2.0, an HIG reference mirror) can be installed for design reviews during the build. It targets native platforms, so this document stays the authority for the web.

---

## 1. The principles (and what each one means on this site)

| Apple principle | Meaning | Concrete rule on My Solutionz |
|---|---|---|
| **Clarity** | Content is legible, precise, easy to understand | One idea per section. Body ≥ 16px, line length ≤ 68ch, contrast AA+. Icons are simple line icons with labels. No jargon in headings |
| **Deference** | UI chrome steps back so content leads | Glass nav, hairline borders, generous whitespace. Photography and headlines lead; decoration never competes with copy. Motion explains, never distracts |
| **Depth** | Layers and motion convey hierarchy | Glass materials over imagery, parallax layers at different speeds, elevation via soft shadows, cards that lift on hover |
| **Hierarchy** | Most important things are most prominent | Every section: eyebrow → two-voice heading → lead → content → single primary CTA |
| **Harmony** | Shapes and rhythm feel related | **Concentric corner radii** (inner = outer − padding); one spacing scale; one motion vocabulary; the hexagon motif repeats |
| **Consistency** | Familiar patterns everywhere | Same button styles, same card anatomy, same reveal motion on every page |
| **Simplicity** ("keep it simple", from the repo) | Remove until it breaks | Max 1 primary + 1 secondary CTA per viewport. No carousels inside carousels. No more than 2 font families visible per section |
| **Focus on UX** (from the repo) | Seamless, intuitive interaction | 44px touch targets, visible focus, instant feedback (<100ms) on every interaction, no layout shift |

---

## 2. Design tokens (`src/css/tokens.css`)

### 2.1 Spacing (4-pt base)
```css
--space-1: 4px;  --space-2: 8px;  --space-3: 12px; --space-4: 16px; --space-5: 20px;
--space-6: 24px; --space-8: 32px; --space-10: 40px; --space-12: 48px; --space-16: 64px;
--space-20: 80px; --space-24: 96px; --space-32: 128px; --space-40: 160px;
--section-y: clamp(80px, 10vw, 160px);   /* vertical padding of a section */
--gutter:    clamp(16px, 4vw, 40px);     /* page side padding */
```

### 2.2 Layout
```css
--container: 1280px;  --container-wide: 1440px;  --container-text: 720px;  /* ~68ch */
/* 12-column grid, column gap = var(--space-6) desktop / var(--space-4) mobile */
```
Breakpoints: `480 · 768 · 1024 · 1280 · 1440 · 1920` (mobile-first, `min-width`). Use **container queries** for components.

### 2.3 Corner radii (concentric system)
```css
--r-xs: 8px;    /* chips, tags, active nav pill  */
--r-sm: 12px;   /* inputs, nav bar, small buttons */
--r-md: 16px;   /* icon tiles, small cards        */
--r-lg: 24px;   /* cards, blog/team cards         */
--r-xl: 28px;   /* large media, hero panel        */
--r-2xl: 32px;  /* outer frames                   */
--r-pill: 999px;/* pill buttons, badges           */
```
**Rule:** a child's radius = parent radius − parent padding (e.g. a card `--r-lg` 24 with 8px padding → image radius 16).

### 2.4 Elevation and shadows (soft, layered, blue-tinted, never grey-black)
```css
--shadow-1: 0 1px 2px rgba(1,27,67,.04), 0 2px 8px rgba(1,27,67,.04);           /* resting card */
--shadow-2: 0 2px 4px rgba(1,27,67,.04), 0 12px 32px rgba(1,27,67,.08);         /* hover card   */
--shadow-3: 0 8px 16px rgba(1,27,67,.06), 0 32px 64px rgba(1,27,67,.14);        /* modal, mega menu */
--shadow-glow: 0 0 0 1px rgba(0,154,240,.25), 0 12px 40px rgba(0,154,240,.28);  /* focus/featured */
```

### 2.5 Materials (glass), Apple "materials" translated to CSS
| Token | On | CSS |
|---|---|---|
| `--glass-ultrathin-dark` | photos/dark | `background: rgba(255,255,255,.06); backdrop-filter: blur(12px) saturate(140%);` |
| `--glass-thin-dark` | hero cards, nav on hero | `rgba(255,255,255,.10)` + `blur(16px) saturate(150%)` + border `1px solid rgba(255,255,255,.14)` + `inset 0 1px 0 rgba(255,255,255,.18)` highlight |
| `--glass-regular-light` | sticky nav on light pages (apple.com style) | `rgba(245,247,251,.72)` + `blur(20px) saturate(180%)` + bottom hairline `rgba(11,18,32,.08)` |
| `--glass-thick-dark` | sticky nav over mixed content, mega menu | `rgba(1,27,67,.72)` + `blur(24px) saturate(160%)` |

Rules: glass only over imagery or colour (glass over flat white is invisible and pointless). **`@media (prefers-reduced-transparency: reduce)`** → replace with solid fills (`#011B43` / `#FFFFFF`). If `backdrop-filter` is unsupported → use the same rgba with alpha +0.25.

### 2.6 Motion tokens (shared by CSS and GSAP)
```css
--dur-micro: 150ms;   /* press, toggle            */
--dur-hover: 250ms;   /* hover lift, colour       */
--dur-ui:    400ms;   /* menus, accordions        */
--dur-reveal:700ms;   /* section element reveals  */
--dur-hero: 1200ms;   /* hero entrance segments   */
--ease-apple:  cubic-bezier(0.28, 0.11, 0.32, 1);  /* apple.com-style standard ease */
--ease-out:    cubic-bezier(0.16, 1, 0.3, 1);      /* expo-out: entrances, reveals    */
--ease-inout:  cubic-bezier(0.65, 0, 0.35, 1);     /* scrubbed / long moves           */
--ease-spring: cubic-bezier(0.34, 1.56, 0.64, 1);  /* small overshoot: badges, chips  */
```
GSAP equivalents are registered once with `CustomEase.create('apple', '0.28,0.11,0.32,1')`, etc. Never use the default `ease` keyword. (See `06-motion-and-scroll.md`.)

### 2.7 Interaction states
| State | Spec |
|---|---|
| Hover (pointer devices only, `@media (hover:hover)`) | lift `translateY(-2px)`, `--shadow-2`, 250ms `--ease-apple` |
| Active/press | `scale(.98)`, 150ms |
| Focus | `outline: 2px solid var(--ms-focus); outline-offset: 2px;` (never removed; `:focus-visible` only) |
| Disabled | opacity .45, `cursor:not-allowed`, no hover |
| Loading (buttons) | label stays, spinner = mini logo-mark "breathing" loop |

### 2.8 Accessibility minimums
- Touch targets **≥ 44×44px** (HIG), spacing ≥ 8px between targets.
- Text contrast **≥ 4.5:1** (≥ 3:1 for ≥ 24px or ≥ 18.66px bold, and for UI component borders/icons).
- Respect `prefers-reduced-motion`, `prefers-reduced-transparency`, `prefers-contrast: more` (thicker borders, solid fills).
- Never convey meaning by colour alone.

---

## 3. Components (build checklist)

| # | Component | Key specs |
|---|---|---|
| C01 | **Eyebrow** | 12px Figtree uppercase, +0.1em, Azure 700, with a small hexagon bullet |
| C02 | **Two-voice heading** | Line 1 ink + line 2 the same upright Figtree in Azure 600 (Sky 300 on dark; gradient on the display size). `<h2>` is one element; the accent is a `<em>` inside it. *No italic serif since 15 October 2026 (L11/L15).* |
| C03 | **Buttons** | Primary pill (Azure 650 → hover Navy 700, 48px high, 24px x-padding); Secondary glass/outlined pill; **Split button** (label + white square arrow tile, from Innovax); Text link with arrow that slides 4px on hover |
| C04 | **Glass nav bar** | see `04` §1.1; sticky; hide-on-scroll-down/show-on-up; active pill slides (GSAP Flip) |
| C05 | **Mega menu (Services)** | Glass-thick panel, 2 columns of services with icons + 1 featured card; opens on hover-intent (150ms) and click; full keyboard support; ESC closes |
| C06 | **Mobile menu** | Full-screen navy sheet, items stagger in, large 20–24px links (`--fs-h3`), contact block at bottom; focus trap; body scroll lock |
| C07 | **Hex icon tile** | Rounded hexagon (SVG clip), 64px, Azure 50 fill, Azure 600 Phosphor-light icon; hover: brand gradient fill + white icon |
| C08 | **Service card** | Glass (on dark) or white (on light), icon, title, 2-line text, arrow link; notch variant |
| C09 | **Notched media** | Image with a cut-out corner holding a card/button (inverted-corner CSS) |
| C10 | **Blog card** | Innovax notched card (date tab + corner arrow notch). P25: `variant => 'featured'` (image beside the text from 900px, description + reading time) for the Insights listing; every post card carries `data-flip-id` for the category filter |
| C11 | **Team card** | Innovax portrait card (share notch, gradient, name + role pill). Built in P19: `component('team-cards', ['members' => team()])` (`templates/components/team-cards.php`, `components/team-cards.css`, `modules/team-cards.js`): scroll-snap slider (4 / 2 / 1.15 cards), prev/next + dots, the share button in a true-mask notch opening a native popover with LinkedIn / e-mail; the About section is absent while `app/data/team.php` is empty (real people with permission only). Demo on `/styleguide/` |
| C12 | **Image-filled stat** | Giant numerals with `background-clip:text` + label; count-up on enter |
| C13 | **Counter stat** | Number counts up (GSAP), suffix "+", label below |
| C14 | **Avatar stack** | 3–4 overlapping circles, 3px white ring |
| C15 | **Process steps** | 01–05 numbered list with progress line; pinned scroll variant |
| C16 | **Testimonial slider** | Quote, name, role/company, sector tag; GSAP slider; pause control |
| C17 | **Accordion (FAQ)** | `<details>/<summary>` base + GSAP height animation; plus/minus icon morph. P27: answers through `rich_blocks()` (paragraphs, lists, bold, internal and external links), `hidden` items for search, `#faq-<id>` deep links open the answer. The `/faq/` page adds C45 |
| C45 | **FAQ search** | `sections/faq/browser.php` + `pages/faq.css` + `modules/faq-search.js` (P27): a GET search form (works without JS, `?q=`), a `role="status"` result count, topic chips (pill links with count badges, navy `aria-current` by scrollspy; a vertical sticky column from 1024px), live filtering as you type (every word, case- and accent-insensitive), matched words marked, empty topics and chips hidden, an empty state with "Show all questions" and a contact button; Esc clears |
| C18 | **Tabs** | Sector/service switcher, ARIA tabs pattern, sliding indicator |
| C19 | **Form fields** | 52px inputs, floating labels, inline validation messages, success state with check-draw animation |
| C20 | **Quick-Start bar** | Hero bar (sector + service selects + button) |
| C21 | **CTA band** | Full-width navy → azure gradient with two soft glows and the faded logo mark (no line art: the client removed the grid-wave on 16 October 2026), two-voice heading, primary + phone CTA |
| C22 | **Breadcrumbs** | Small, ink-2, chevrons; schema-backed |
| C23 | **Footer** | see `08` §Footer |
| C24 | **Cookie banner** | Bottom glass sheet; Accept all / Reject all / Manage, all **equal prominence**. Built in P28: `templates/partials/cookie-banner.php` + `components/consent.css` + `modules/consent.js`; a near-white glass sheet bottom-left (max 560px, above the mobile action bar on phones) with three identical navy pill buttons, focus moved to it on arrival without a trap; a native modal `<dialog>` (centred, blurred navy backdrop) lists each category with a 44px switch (`role="switch"`) and its technologies, with sticky Reject all / Accept all / Save preferences; notice mode (exempt statistics only) shows OK / Opt out / Manage preferences. Printed only when something non-essential is configured |
| C25 | **Back-to-top** | Square Azure button with circular scroll-progress ring |
| C26 | **Marquee** | Infinite sector/keyword ticker, pauses on hover, reduced-motion = static list |
| C27 | **Toast/alert** | Form feedback, `role="status"` |
| C28 | **Splash** | see `07` |
| C29 | **Section dividers** | SVG wave / diagonal (120°) dividers |
| C30 | **Custom cursor (optional, desktop only)** | Small azure dot + ring that grows over links/cards; disabled on touch & reduced motion |
| C31 | **Select (custom listbox)** | Enhances a native `<select>` (`data-module="select"`, `src/js/modules/select.js` + `components/select.css`): APG select-only combobox, white popup (radius 16, Shadow 3) in the top layer, option icons from `data-icon`, optgroups as eyebrow-style groups tinted by `data-colour`, check on the selected option, type-ahead; the native control stays in the form (no-JS fallback). Added 15 October 2026 at the client's request |
| C32 | **Inner-page hero** | `component('hero-inner', [...])` (`templates/components/hero-inner.php`, `components/hero-inner.css`, `modules/hero-inner.js`; P17). Variants `columns` (Navy 900 panel, 80px bottom-left radius `--r-section`, faint skyscraper art, 3 pill image columns + up to 3 floating navy-glass labels, parallax 0.9/1/1.1 ≥ 1024), `category` (category chip + glow, large hex tile in the glowing `.special-frame--hug`, optional columns, "Part of …" chip), `simple` (light, breadcrumbs + H1 + lead), `article` (light, chip, H1, meta row, featured image). Entrance after `splash:done`: H1 lines in SplitText masks, narrow pills clip up, labels float in, frame draws then orbits; the wide image (LCP) and the article image are never hidden. Mobile: one wide image, labels below. Demo: `/styleguide/heroes/` |
| C33 | **Office Hours card** | `component('hours-card', ['id', 'class', 'attrs'])` (`templates/components/hours-card.php`, `components/hours-card.css`; extracted in P18 from the P10 home approach card): Azure 650 → 500 gradient, white text, hours from `config('hours')`, "Email us any time" line, never a 24/7 claim; copy in `ui('hours_card')`. The page positions it (in a `.notch--tr` cut-out on home and About) |
| C34 | **In-page sub-nav** | `component('subnav', ['items' => [id => label], 'label'])` (`templates/components/subnav.php`, `components/subnav.css`, `modules/subnav.js`; P18): glass pill bar that straddles the hero edge, sticks under the header (slides up with it when it hides, `html.is-header-hidden`), scrollspy sets `aria-current` and slides a navy pill under the current link; horizontal-scroll chips on small screens; `html:has(.subnav)` adds its height to `scroll-padding-top` |
| C35 | **Service page blocks** | Typed blocks rendered from `app/data/service-pages/<slug>.php` `sections[]` by `templates/sections/service/<type>.php` (P20, reused by P21): `intro` (paragraphs under the hero, first set apart), `text` (two-voice H2 + the client's Sub line as a styled paragraph; `default`/`mist` with an optional sticky notched photo holding the category hex tile, `band` = Navy 900, two columns, visible "Last updated", `read-along`), `steps` (header + the C15 list pinned without its own header, any count 01–0N), `subs` (frosted glass sub-service cards, hex tile, corner-notch arrow, `tilt`), `list` (icon cards), `why` (icon + bold-led sentence rows), `faq` (C17 by tag); a block holding `[[PENDING: …]]` renders as a dashed "Content pending" box off production and is omitted on production. Paragraphs go through `rich()` (`**bold**`, `[label](/path/)`). P21 adds `related` (sibling + cross-category glass cards), text `pull` (a paragraph as a pull line with read-along) and `read_along`, `list` 5 = a 2 + 3 bento, steps `full` (only the active step's paragraphs while pinned). Demo: `/styleguide/service-blocks/` |
| C36 | **Sector card** | `component('sector-card', ['sector' => …, 'skew' => bool])` (`components/sector-card.css`, P22): the P12 3:4 photo card (bottom shade, hex icon, title link over the card, summary, arrow tile in a bottom-right true-mask notch); home sectors row + `/sectors/` hub |
| C37 | **Service card** | `component('service-card', ['sub', 'category', 'text', 'cross'])` (P22, extracted from P20/P21): frosted glass card, category hex tile, title link over the card, "Part of …" chip for cross-category cards, corner-notch arrow, `tilt`; category sub-service cards, related services, sector "How we help" |
| C38 | **Form fields** | `.form` / `.field` (`components/form.css`, P23): label above, visible `*` (aria-hidden) + `required`, 52px inputs with 16px text (no iOS zoom), hint and error text under the control (`aria-describedby`, `aria-invalid`, icon + text, never colour alone), selects = native `<select>` in `data-module="select"` (C31), 24px checkbox on a 44px label row, error summary (`role="alert"`, focused, links to the fields) and notice; `data-module="form"` adds the character counter (`data-char-counter`); P24: inline validation on blur mirroring the server rules (error markup from `<template data-error-template>`), the same error summary on submit (`<template data-summary-template>`), the submit button's loading state (white logo mark breathing + bars re-drawing, `.is-loading`, "Sending…" announced in `[data-form-status]`), double-submit prevention, and the dismissible `.prefill` chip ("You selected: …" / "Interested in: …") above a pre-filled form |
| C39 | **Pagination** | `component('pagination', ['page', 'pages', 'base', 'copy', 'class'])` (`components/pagination.css`, P25): real links (page 1 = `base`, page n = `base/page/n/`), 44px pills, Previous / Next with aria-labels (icon only below 480px), "Page n" labels, `aria-current="page"` filled Azure, a gap (…) past 7 pages; prints nothing for one page |
| C40 | **Filter chips** | `.insights-filter` (`pages/insights.css`, P25): pill links with a count badge, the active one navy with `aria-current`; `data-module="insights-filter"` swaps the results in place with a GSAP Flip and pushes the URL (every state is a crawlable URL) |
| C41 | **Table of contents** | `component('toc', ['items' => $post['toc']])` (`components/toc.css`, `modules/toc.js`, P26): white panel with a `<details>` (open in the HTML, so a plain anchor list without JS), H3s nested under their H2; sticky beside the article body from 1100px (follows the header up when it hides), collapsed below 1100px and closed again after a jump; scrollspy marks the current link `aria-current="true"` (ink text + azure bar on the list's rule) |
| C42 | **Author box** | `component('author-box', ['author' => Blog::author($key)])` (`components/author-box.css`, P26): avatar (photo, or the logo mark for the team), "About the author" eyebrow heading, name, role, bio, "Follow on LinkedIn" (new tab, announced) + "About us"; data in `content/authors.php` |
| C43 | **Share links** | `component('share-links', ['url', 'title'])` (`components/share.css`, P26): LinkedIn, Facebook, X, Email as plain share URLs (no third-party scripts), 44px round icon buttons with accessible names |
| C44 | **Reading progress** | `.reading-progress` (`components/reading-progress.css`, `modules/reading-progress.js`, P26): 3px brand-gradient bar fixed under the header (top edge while the header hides), scaled with the scroll through the article body; decorative, absent without JS. Article prose (`components/prose.css`): 14px paragraphs at 1.75 line height, azure list markers, navy-headed tables that scroll sideways, quotations, pull quotes (`> [!PULL]`, read-along) and callouts (`> [!NOTE|TIP|IMPORTANT|WARNING|UPDATE] Title`) |

---

## 4. Writing style (Apple-like voice)
- Short, confident, concrete. Lead with the benefit; follow with proof.
- Headlines ≤ 8 words; leads ≤ 30 words; paragraphs ≤ 4 lines on desktop.
- UK English (organisation, tendering, programme, licence (noun)).
- Buttons: verb-first, Title Case: "Get a Free Quote", "Explore Services", "Book a Consultation".
- No hype words without proof ("best", "No.1") (also an ASA/CAP code risk in the UK).
