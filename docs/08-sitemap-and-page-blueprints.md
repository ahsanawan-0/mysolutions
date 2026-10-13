# 08 — Sitemap, URL Structure and Page Blueprints

> **Status:** 🔒 LOCKED: page list, About-page order, home section order, **services structure (3 categories / 12 sub-services, confirmed 13 Oct 2026)**. 🟡 Sector list final once content arrives.
> Every section below names its **reference** (from `04`) and its **motion** (`data-anim` from `06`).

---

## 1. Sitemap and URLs

```
/                                         Home
/about-us/                                About (existing URL kept). Single long page: About Us → Our Story → Our Approach → Why Work With Us → Mission & Vision
/services/                                          Services hub (301 from /our-services/ and /service/)
   /services/bid-proposal-management/               Category 1 landing page
      /services/bid-proposal-management/tender-bid-writing/          (301 from /service/tender-writing/)
      /services/bid-proposal-management/opportunity-identification/  (301 from /service/tender-search/)
      /services/bid-proposal-management/expert-review/               (301 from /service/tender-review/)
   /services/compliance-virtual-assistance/         Category 2 landing page
      /services/compliance-virtual-assistance/cqc-registration/      (301 from /service/cqc-registration/)
      /services/compliance-virtual-assistance/policy-management/
      /services/compliance-virtual-assistance/carbon-reduction-plan/
      /services/compliance-virtual-assistance/data-entry-management/
      /services/compliance-virtual-assistance/care-plans/
   /services/digital-presence/                      Category 3 landing page
      /services/digital-presence/website-development/                (301 from /service/website-development/)
      /services/digital-presence/social-media-management/            (301 from /service/social-media-management/)
      /services/digital-presence/email-marketing/
      /services/digital-presence/seo-analytics/
/sectors/                                 Sectors hub
   /sectors/healthcare-social-care/
   /sectors/transport/
   /sectors/cleaning/
   /sectors/education-training/
   /sectors/security/
   /sectors/facilities-management/
   /sectors/building-maintenance/
/insights/                                Blog listing (paginated: /insights/page/2/)
   /insights/category/<category>/
   /insights/<post-slug>/
/faq/                                     FAQ (existing URL kept)
/contact-us/                              Contact + quote form (existing URL kept; #quote anchor; Quick-Start pre-fill)
/thank-you/                               Form confirmation (noindex)
/privacy-policy/  /cookie-policy/  /terms-and-conditions/  /accessibility/
/sitemap/                                 HTML sitemap
/sitemap.xml  /robots.txt  /llms.txt      Machine files
404                                       Custom not-found page
```
**Rules:** lowercase, hyphens, trailing slash, max 3 levels, keywords in slugs, no dates in blog URLs. Existing URLs `/about-us/`, `/contact-us/`, `/faq/` are **kept**; every other old URL gets a one-hop 301 or a 410 (full map in `10-seo-specification.md` §2).

## 2. Navigation
- **Header:** Logo · Home · About · **Services** (mega menu) · **Sectors** (dropdown) · Insights · Contact · [phone icon +44 7466 063564] · **[Get a Free Quote]**
- **Mega menu (Services):** **3 columns, one per category** (category name links to its landing page, with its colour accent and hex icon), each listing its sub-services (icon + name + one-line description); 4th column = featured card (latest insight or "Free quote") + "View all services →".
- **Category colour coding (🟡 proposed):** Bid & Proposal Management = **Azure** `#009AF0` · Compliance & Virtual Assistance = **Navy** `#003C8C` · Digital Presence = **Sky** `#8DD6FF` (on dark) / Sky 700 (on light). Used for chips, icon tiles, slider cards and category heroes, so visitors always know which family a page belongs to.
- **Mobile:** burger → full-screen navy sheet; accordion for Services/Sectors; phone + email + socials at the bottom; sticky bottom **"Call" / "WhatsApp" / "Get a Quote"** bar on mobile (appears after the hero).

## 3. Footer (🔒 revised by the client 13 Oct 2026: Innovax-style rounded panel, no grid-wave at the top)
A **rounded Navy 900 panel** (radius 32) inset on the canvas, flat colour with one faint azure glow (no heavy gradients), Apple/Airbnb-clean:

| Brand card (glass) | Col 2 | Col 3 | Col 4 |
|---|---|---|---|
| Logo (reversed), 2-line company description, **office hours rows** with hairlines, round white social buttons (LinkedIn, Instagram, Facebook, WhatsApp) | **Services**: 3 category links, each followed by its sub-services in smaller text | **Sectors** (7 links + All sectors) | **Company**: About, Insights, FAQ, Contact, Get a Free Quote |

Below the columns: a **contact row** with azure icon circles (Visit our office · Email us · Call or WhatsApp). Along the **bottom edge** of the panel: a decorative **word strip** (Tender Writing ⬡ Bid Management ⬡ Compliance ⬡ Digital Presence…) cut by the panel's rounded bottom (Innovax reference), slow marquee, static under reduced motion. The grid-wave art is used on the CTA band only.

Bottom bar (on the canvas, under the panel): `© 2026 My Solutionz Ltd. Registered in England and Wales, company no. 15677749. Registered office: Unit A53, 4–6 Greatorex Street, London E1 5NF.` · Privacy · Cookies · **Cookie settings** (reopens consent) · Terms · Accessibility · Sitemap.

---

## 4. HOME: section order (🔒)
| # | Section | Reference | Motion |
|---|---|---|---|
| H0 | Splash (first visit per session) | pixel2motion | see `07` |
| H1 | **Hero**: 1:1 `hero_should` (services slider + glowing "Our Special Service" frame + Quick-Start bar + sector chips) | `05` | hero choreography + scrub parallax |
| H2 | **"Our *Services*"** (🔒 first section after the hero): 3 **sticky stacking category cards** (Bid & Proposal Management → Compliance & Virtual Assistance → Digital Presence). Each card: category colour, hex icon, 2-line intro, its sub-services as hex-icon links, "Explore [category]" button | Alternate Futures hex + stacking cards | `stack-cards`, hex hover |
| H3 | **"Our Approach to *Winning Contracts*"**: notched image + Office-Hours card, image-filled **"10+"** (years of business development experience), avatar stack + label "STRATEGIC BIDS FOR SUSTAINABLE GROWTH", intro paragraph, split button "More About Us" | Innovax approach section | `two-voice`, `img-reveal`, `fill-text`, `counter` |
| H4 | Micro-CTA row: *"Not sure which service you need? ↓ Let's talk"* | strategicproposals arrow | S2 arrow |
| H5 | **Sectors We *Serve***: pinned **horizontal scroll** of 7 sector cards (photo, icon, challenge line, link); swipe cards on mobile | Innovax sticky panels | `h-scroll` |
| H6 | **Our Process, *Step by Step***: 01–05 (Understand → Analyse & Strategise → Evidence-Led Writing → Quality & Compliance → Submission & Ongoing Support); pinned, progress line, overlapping image swaps + icon chip | Sun Over numbered list | `pin-steps`, `draw` |
| H7 | **Why Work *With Us***: bento grid (7 reasons) with the **logo-mark assembly** as the centrepiece tile + counters | Vencap bento + strategicproposals assembly | S1, `counter`, `stagger` |
| H8 | **"How can we help?" triage row**: 3 glass cards: *Tender deadline soon?* → Call now · *Bidding for the first time?* → Book a free consultation · *Need compliance or digital support?* → Explore services | strategicproposals triage | `stagger`, `tilt` |
| H9 | **Testimonials** (only real, permissioned quotes): marquee with edge-fade mask, pause button | Innovax marquee | `marquee` |
| H10 | **FAQ teaser** (5 questions) + link to /faq/ | — | accordion |
| H11 | **Latest *Insights***: 3 notched blog cards | Innovax blog card | `stagger`, card hover |
| H12 | **Final CTA band**: "Ready to Win Your *Next Contract?*", Get a Free Quote + phone; grid-wave background; rising/rotating faded logo-mark | `WhatsApp 9.34.03` grid-wave + S10 | `parallax`, S10 |
| H13 | Footer | Alternate Futures footer | `fade-up` |

## 5. ABOUT (`/about-us/`): order 🔒 (from `pages_ref.jpeg`)
| # | Section | Content source | Layout / motion |
|---|---|---|---|
| A0 | Inner hero: "About *My Solutionz*" + breadcrumbs | — | Sun Over pill image columns + floating labels (*Bid Strategy*, *Compliance*, *Evidence-Led Writing*), parallax speeds 0.9/1.0/1.1 |
| A1 | **About Us**: "Your Business Growth Is *Our Business*" | docx §About Us | Two-column: text + notched image with Office-Hours card; sector chips row |
| A2 | **Our Story**: "Built on Experience, *Driven by Business Growth*" | docx §Our Story | Sticky image column (images crossfade) + scrolling text; key sentences as **scrubbed read-along** text (chars .2 → 1); pull-quote: *"Every tender represents more than a submission deadline."* |
| A3 | **Our Approach**: "A Practical, Strategic Approach to *Tender Writing and Bid Management*" | docx §Our Approach (5 steps + closing) | `pin-steps` 01–05 (same component as H7, longer copy) + closing statement "Every Bid Deserves a *Well-Considered Approach*" |
| A4 | **Why Work With Us?** "More Than Tender Writers. *A Partner in Your Business Growth.*" | docx §Why Work With Us (7 reasons) | Bento grid or **stacked sticky cards** (7) with icons |
| A5 | **Our Mission & Vision**: "Creating Opportunities, Strengthening Businesses, *Supporting Growth*" | docx §Mission & Vision | Split: Mission card (white) / Vision card (Azure→Navy gradient); azure ribbon SVG draws on scroll behind (`some_section_bg (3)` art) |
| A6 | Team "Behind *Our Expertise*" (only if real photos supplied) | client | Innovax team cards slider |
| A7 | CTA band | — | as H12 |

## 6. SERVICES HUB (`/services/`)
Inner hero → intro (two-voice) → **3 category panels** (each: colour, hex icon, summary, sub-service hex tiles, "Explore" link) → "How we work" (compact 5-step strip) → sectors chips marquee → FAQ (general service questions) → CTA band.

## 7a. CATEGORY LANDING TEMPLATE (`/services/<category>/`) 🔒
Generic overview of the category: what it is, how it works, what we do, how it helps businesses. Reference implementation: **`website_content/services/01-bid-proposal-management.md`**.
1. Hero: eyebrow (category), H1 = category + keyword, direct-answer lead (40–60 words), CTAs, trust line, breadcrumbs.
2. **"What is …?"** direct-answer section (written to be quoted by search/AI answers).
3. **How it works**: 5 numbered steps (pinned process).
4. **The sub-services**: one large card per sub-service → its landing page.
5. **How it helps your business**: 6 benefit tiles.
6. **Who we support**: personas + sector links.
7. **Current standards / regulation** section relevant to the category (e.g. Procurement Act 2023; CQC; PPN 006 carbon reduction plans; UK GDPR/PECR for email marketing).
8. **Why My Solutionz**: 4 points.
9. **FAQ** (6–8 Q&As).
10. CTA band (pre-fills the category in the quote form).

## 7b. SUB-SERVICE LANDING TEMPLATE (`/services/<category>/<sub-service>/`): data-driven from `app/data/services.php`
1. Hero: H1 = sub-service + keyword (e.g. "Tender & Bid Writing Services"), lead, CTAs, breadcrumbs (Home › Services › Category › Sub-service), sub-service icon in the glowing frame, "Part of [Category]" chip.
2. **The problem we solve** (short, empathic).
3. **What's included**: checklist cards (icons).
4. **How it works**: 3–6 steps specific to the sub-service.
5. **Who it's for**: sector chips + business types.
6. **What you receive**: deliverables list.
7. **Why My Solutionz**: 3–4 proof points.
8. **Related services**: the sibling sub-services + 1 cross-category link.
9. **FAQ** (sub-service-specific, 5–8 Q&As).
10. CTA band (pre-fills the sub-service in the contact form).

## 8. SECTORS HUB and SECTOR PAGE TEMPLATE (`/sectors/<slug>/`)
Hub: inner hero → sector cards (photo + icon) → "Why sector knowledge matters" → CTA.
Sector page: hero (H1 e.g. "Healthcare & Social Care Tender Writing") → sector challenges (regulation, workforce, mobilisation, performance) → how we help → relevant services → typical buyers/contract types (TBC with client) → FAQ → CTA.

## 9. INSIGHTS
- **Listing:** featured post (large notched card) + category filter chips (GSAP Flip filtering) + grid of notched cards + pagination (real links, crawlable).
- **Article:** H1, author + date + updated date + reading time, hero image, table of contents (sticky on desktop), body (max 720px), pull quotes, key-takeaways box, author bio (E-E-A-T), share buttons (no third-party scripts, plain share URLs), related posts, CTA. Reading-progress bar.

## 10. FAQ (`/faq/`)
Grouped accordions (Tendering basics · Our services · Pricing & process · Sectors). Server-rendered answers (visible without JS).

## 11. CONTACT (`/contact-us/`)
Inner hero → **contact cards** (Phone, **WhatsApp**, Email, Address, Office Hours) with notch arrows → **form** (Name*, Company, Email*, Phone, Sector (select), Service (select), Tender deadline (date), Preferred call-back time (optional), Message*, privacy notice link, optional marketing opt-in unticked) → map (**click-to-load** embed, or a static map image linking to Google Maps, so no third-party cookies before consent) → socials → FAQ mini.

## 12. Utility pages
- **404:** playful logo-mark animation, search-like quick links (Services, Sectors, Contact), real 404 status.
- **Thank-you:** confirmation, what happens next (timeline), links to insights; `noindex`.
- **Legal:** clean long-form template with TOC.
- **HTML sitemap:** all pages grouped.
