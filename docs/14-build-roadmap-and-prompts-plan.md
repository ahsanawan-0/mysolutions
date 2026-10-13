# 14 — Build Roadmap and Prompts Plan

> **Status:** ✅ **`/PROMPTS.md` written (13 Oct 2026)**: 36 build prompts (P00–P35) using a "structure now, content later" strategy (provisional copy + draft pages), plus content-fill prompts C01–C07 that are written in full as the client's content arrives. This file keeps the original plan for reference; `PROMPTS.md` is the source of truth for building.

---

## 1. Prompt format (every prompt in `PROMPTS.md` will follow this)
```
### P## — <Title>
**Read first:** docs/00-LOCKED-DECISIONS.md + the specific docs for this step (listed)
**Goal:** one sentence
**Context:** what already exists from earlier prompts (files/components)
**Build:** numbered, exhaustive list of files, markup, styles, scripts, content
**Content:** exact copy for this step (pasted from the content files), image list with alt text
**SEO:** title, meta description, canonical, OG, JSON-LD for any page touched
**Motion:** data-anim effects + any bespoke GSAP timeline (with timings)
**Responsive:** behaviour at 360 / 768 / 1024 / 1440
**Accessibility:** specific ARIA/keyboard needs
**Acceptance criteria:** checklist the result must pass before moving on
**Do not:** explicit list of things to avoid (e.g. no Bootstrap, no CDN icons, no lorem ipsum)
```

## 2. Planned prompt sequence (35)

### Phase 0 — Foundation
| # | Prompt | Depends on docs |
|---|---|---|
| P01 | Project scaffold: folders, Composer, Vite, front controller, routes, `.htaccess`/Nginx, `.env`, helpers | 11 |
| P02 | Design tokens, base CSS, self-hosted fonts with metric fallbacks, Phosphor icon sprite + `icon()` helper | 02, 03 |
| P03 | SEO engine: `Seo.php`, `Schema.php`, head partial, XML sitemaps, `robots.txt`, `llms.txt`, 301 redirect map, enforcement | 10 |
| P04 | Header: glass nav, active pill, mega menu (Services), mobile menu, hide/show on scroll | 03, 04, 06 |
| P05 | Footer, CTA band, breadcrumbs, back-to-top progress ring, section dividers | 03, 08 |
| P06 | Motion core: Lenis + GSAP bootstrap, `gsap.matchMedia`, the full `data-anim` module catalogue, reduced-motion paths | 06 |
| P07 | Splash: run pixel2motion on the logo, review, integrate SVG + CSS, GSAP Flip hand-off, failsafes | 07 |

### Phase 1 — Home page
| # | Prompt |
|---|---|
| P08 | Hero structure 1:1 with `hero_should`: panel, notch, nav placement, badge, headline, Quick-Start bar, quick links, bottom tab |
| P09 | Hero services **slider** + **animated glowing "Our Special Service" border** + entrance choreography + scroll parallax |
| P10 | "Our Approach to *Business Growth*" intro: notched image + hours card, image-filled "10+", avatar stack, split button |
| P11 | "Our Services" (first section after the hero): 3 sticky stacking category cards with sub-service hex links |
| P12 | Sectors We Serve: pinned horizontal scroll (desktop) / swipe cards (mobile) |
| P13 | Our Process 01–05: pinned steps with progress line and image swaps |
| P14 | Why Work With Us: bento grid + counters + floating labels |
| P15 | Testimonials / trust strip / FAQ teaser (depends on client content) |
| P16 | Latest Insights (notched blog cards) + final CTA band with grid-wave background |

### Phase 2 — Inner pages
| # | Prompt |
|---|---|
| P17 | Inner-page hero component (Sun Over style pill columns + floating labels) |
| P18 | About page part 1: About Us + Our Story |
| P19 | About page part 2: Our Approach + Why Work With Us + Mission & Vision (+ Team if supplied) |
| P20 | Services hub + **category landing template** + the 3 category pages |
| P21 | **Sub-service landing template** (data-driven) + all 12 sub-service pages populated |
| P22 | Sectors hub + sector single template + all sector pages populated |
| P23 | Contact page + form backend (PHPMailer, CSRF, validation, honeypot, rate-limit, PRG) |
| P24 | Free Quote / Consultation page + Quick-Start pre-fill |
| P25 | Blog engine: Markdown loader, listing, categories, pagination, Flip filter |
| P26 | Blog article template: TOC, reading progress, author box (E-E-A-T), related posts, share, schema |
| P27 | FAQ page (accordion + FAQPage markup policy) |
| P28 | Legal pages + cookie consent (equal-prominence) + Consent Mode v2 + analytics after consent |
| P29 | 404 page (logo-mark animation), HTML sitemap, thank-you page |

### Phase 3 — Quality and launch
| # | Prompt |
|---|---|
| P30 | SEO pass: every page's meta audited, schema validated (Rich Results Test), OG images generated, internal links, redirect map tested |
| P31 | Performance pass: image pipeline (AVIF/WebP/srcset), critical CSS, font subsetting, Lighthouse CI budgets |
| P32 | Accessibility pass: axe, keyboard, screen readers, reduced motion/transparency |
| P33 | Responsive + cross-browser + real-device polish |
| P34 | Security hardening: CSP with nonces, headers, staging protection/noindex, error handling |
| P35 | Launch: deploy, Search Console + Bing Webmaster, sitemap submission, Google Business Profile check, redirect monitoring, uptime |

## 3. What we need from the client before `PROMPTS.md` is written
See `15-open-questions.md`. ✅ Resolved: services structure, years of experience, company details, address. Still blocking: **content for the remaining pages** (2 categories, 12 sub-services, sectors, Home, FAQ, blog), **vector logo**, and **testimonials/team/case studies (or a decision to omit them)**.
