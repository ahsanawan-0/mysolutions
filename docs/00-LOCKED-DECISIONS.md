# 00 — LOCKED DECISIONS (read this first, every time)

> These are the client's instructions, closed and locked. Any build prompt, developer or AI agent working on this project **must follow them** and **must not change them** without the client's written approval. Detail lives in the numbered docs.
>
> Legend: 🔒 locked · 🟡 proposed, awaiting client confirmation · ❗ blocked on client input

---

## A. Project
| # | Decision | Doc |
|---|---|---|
| L01 🔒 | Website for **My Solutionz**, a **UK** services company (bid & proposal management, compliance & virtual assistance, digital presence). UK English, UK audience | 01 |
| L02 🔒 | Built in **PHP** (custom, no WordPress), clean URLs, server-rendered HTML | 11 |
| L03 🔒 | **Docs first.** No code until the client approves these docs and delivers the content | — |
| L04 🔒 | One **`/PROMPTS.md`** with long prompts that build every section and page to 100%. **Written 13 Oct 2026** (P00–P35); final client copy is dropped in later via content-fill prompts C01–C07 | 14 |
| L05 🔒 | NAP: **My Solutionz, Unit A53, 4–6 Greatorex Street, London E1 5NF, UK · +44 7466 063564 · info@mysolutionz.co.uk**. Socials: LinkedIn, Instagram, Facebook (clean URLs). The Slough address is **not** used | 01 |
| L05b 🔒 | Legal entity (verified on Companies House): **MY SOLUTIONZ LTD**, company no. **15677749**, private limited company registered in England and Wales, incorporated **24 April 2024**, registered office = Unit A53, 4–6 Greatorex Street, London E1 5NF (same as trading address), SIC 70229 | 01, 13 |
| L05c 🔒 | Experience claim = **"over 10 years of business development experience"**. Because the company was incorporated in 2024, never write "10 years in business" or "established 10 years ago". The docx "16 years" is replaced with 10 | 09 |

## B. What may and may not be reused from the current site (mysolutionz.co.uk)
| # | Decision |
|---|---|
| L06 🔒 | **Reuse ONLY its content (text) and its icons** (icons as concepts, redrawn with the new icon library) |
| L07 🔒 | **Do NOT reuse** its colours, fonts, layout, UI, images or styling. Nothing else |
| L08 🔒 | Its existing URLs **must be 301-redirected** (or kept) to protect SEO |

## C. Brand
| # | Decision | Doc |
|---|---|---|
| L09 🔒 | Colours come **from the logo and its family**: Sky `#8DD6FF`, Azure `#009AF0`, Navy `#003C8C` + generated tints/shades + cool neutrals | 02 |
| L10 🔒 | **Hexagon** = brand shape motif; **120°/60° diagonal** = brand motion angle (both from the logo) | 02 |
| L11 🔒 | Fonts **scraped from the Innovax reference**: **Mona Sans** (headings), **Google Sans** (body), **Playfair Display Italic** (accent words). Self-hosted | 02, 04 |
| L12 🔒 | One icon library: **Phosphor Icons** (inline SVG sprite, no CDN) | 02 |

## D. UI/UX
| # | Decision | Doc |
|---|---|---|
| L13 🔒 | **Apple design principles** (clarity, deference, depth, hierarchy, harmony, consistency, simplicity) applied to the web, with concrete tokens | 03 |
| L14 🔒 | **Super UI/UX**: premium, unique sections, glass effects, parallax | 03, 04, 06 |
| L15 🔒 | **Innovax** is the primary UI reference: glass nav pill, two-voice headings (grotesk + italic serif), notched cards, image-filled numbers, team cards, blog cards | 04 |
| L16 🔒 | All images in `inspirations/` are references for section layouts and backgrounds (Sun Over, Alternate Futures hex tiles, Vencap bento, glass skyscraper, node network, ribbon, swirl lines, grid-wave) | 04 |
| L17 🔒 | **About page order:** About Us → Our Story → Our Approach → Why Work With Us? → Our Mission & Vision | 08 |

## E. Hero (non-negotiable)
| # | Decision | Doc |
|---|---|---|
| L18 🔒 | The home hero is **exactly** the `hero_should.jfif` composition (dark rounded panel, building photo, nav inside, badge, 2-line headline, search-style bar, quick links, S-curve notch, bottom mini card + 4 chips) | 05 |
| L19 🔒 | The card where the reference says **"Comfortable" is a SLIDER** (services carousel with faded neighbours) | 05 |
| L20 🔒 | The **"Our Special Service" border is animated**: a light that moves around the frame, shining and glowing | 05 |

## F. Motion
| # | Decision | Doc |
|---|---|---|
| L21 🔒 | **GSAP** (+ ScrollTrigger, SplitText, DrawSVG, MorphSVG, Flip, Draggable/Inertia) for all animation; **Lenis** for smooth scroll | 06 |
| L22 🔒 | **strategicproposals.com feel**: elements keep moving as you scroll (scrubbed scroll animations), parallax, logo-shape assembly, animated arrows | 06 |
| L23 🔒 | Splash-screen **logo animation made with the pixel2motion skill** | 07 |
| L24 🔒 | Motion never hides content from crawlers, respects reduced-motion, stays within performance budgets | 06, 12 |

## G. SEO
| # | Decision | Doc |
|---|---|---|
| L25 🔒 | **Every page** has a unique `<title>`, meta description, **canonical**, robots, **Open Graph + Twitter** tags, and JSON-LD | 10 |
| L26 🔒 | **Every image** has meaningful `alt` text, descriptive file names, width/height, modern formats | 10 |
| L27 🔒 | SEO rules follow **Google Search Central documentation** + established SEO agent skills | 10 |
| L28 🔒 | Blog (Insights) is part of the site | 08, 10 |

## H. Quality
| # | Decision | Doc |
|---|---|---|
| L29 🔒 | Core Web Vitals "Good" (LCP < 2.5s, INP < 200ms, CLS < 0.1); Lighthouse 95+/100/100/100 | 12 |
| L30 🔒 | WCAG 2.2 AA accessibility | 12 |
| L31 🔒 | UK legal compliance: UK GDPR, PECR cookie consent, company disclosures | 13 |

## I. Pending (🟡 / ❗)
| # | Item |
|---|---|
| P01 🟡 | Blog storage: Markdown files (recommended) vs. MySQL admin panel (`11` §6) |
| P02 🟡 | Single `/contact-us/` page with quote form (recommended) vs. separate quote page |
| ~~P03~~ ✅ | Years of experience → **10+** (resolved, see L05c) |
| P04 ❗ | Vector (SVG) logo |
| P05 🟡 | VAT number (only if VAT-registered). Company number and registered office ✅ resolved (L05b) |
| P06 ❗ | Remaining content: 2 more category pages, 12 sub-service pages, sectors, Home, FAQs, blog posts |
| P07 ❗ | Testimonials, team photos, case studies, accreditations (or decision to omit) |
| P08 ❗ | Office hours |
| ~~P09~~ ✅ | Services list → resolved (section K) |
| P10 🟡 | Address ✅ London confirmed. Still open: is it a **staffed office** customers can visit (Google Business Profile eligibility)? |

## K. Services structure (🔒, from the client, 13 Oct 2026)
| # | Decision |
|---|---|
| L35 🔒 | **3 service categories, 12 sub-services.** Each category has its own **landing page** (generic overview: how it works, what we do, how it helps) and each sub-service has its **own detailed landing page** |
| L36 🔒 | **1. Bid & Proposal Management** → Tender & Bid Writing · Opportunity Identification · Expert Review |
| L37 🔒 | **2. Compliance & Virtual Assistance** → CQC Registration · Policy Management · Carbon Reduction Plan · Data Entry & Management · Care Plans |
| L38 🔒 | **3. Digital Presence** → Website Development · Social Media Management · Email Marketing · SEO & Analytics |
| L39 🔒 | On the Home page, **"Our Services" is the first section after the hero**, showing the 3 categories with their sub-services |
| L40 🔒 | URLs: `/services/` (hub) → `/services/<category>/` → `/services/<category>/<sub-service>/` (see `08` §1) |
| L41 🔒 | Content is written category-first, then sub-categories, **one step at a time**, SEO-healthy and current (UK procurement rules as of 2026). Drafts live in `docs/website_content/services/` |

## J. URL decisions (🔒, from the SEO migration analysis)
| # | Decision |
|---|---|
| L32 🔒 | Keep **non-www, HTTPS, trailing slash** (as on the current site) |
| L33 🔒 | Keep existing URLs **`/about-us/`, `/contact-us/`, `/faq/`**; services move to **`/services/<category>/<sub-service>/`** with one-hop 301s from `/service/<slug>/` and `/our-services/` (map in `10` §2) |
| L34 🔒 | Junk WordPress URLs (`/pxl-template/*`, `/tag/*`, off-topic diabetes post) return **410** |

Full list: `15-open-questions.md`.
