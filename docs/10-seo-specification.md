# 10 — SEO Specification

> **Status:** 🔒 LOCKED. Every page, template and prompt must satisfy this document.
> **Sources:** Google Search Central documentation (developers.google.com/search/docs, checked 13 Oct 2026), ogp.me, web.dev, ICO; and the two most-used SEO agent skills: **coreyhaines31/marketingskills** (`seo-audit`, `schema`, `ai-seo`, `site-architecture`) and **AgriciDaniel/claude-seo** (`/seo audit`, `/seo page`, `/seo schema`, `/seo images`, `/seo local`).
> Where the skills' "rules of thumb" differ from Google's documentation, this is flagged with ⚖️.

---

## 0. Install the SEO skills for the build (used in prompts P03 and P30)
```bash
# marketingskills (Skills CLI)
npx skills add coreyhaines31/marketingskills --skill seo-audit schema ai-seo site-architecture -a claude-code
# claude-seo (Claude Code plugin)
/plugin marketplace add AgriciDaniel/claude-seo
/plugin install claude-seo@agricidaniel-claude-seo
/seo setup
```
Use `/seo page <url>` on every template on staging and `/seo audit` before launch.

---

## 1. Findings on the current site (must be fixed or migrated)
| Issue (verified 13 Oct 2026) | Fix in the new build |
|---|---|
| WordPress + Yoast; canonical host `https://mysolutionz.co.uk/` (non-www, trailing slash) | **Keep non-www + trailing slash** (no equity risk) |
| `http://www` takes 2 redirect hops | Single-hop 301 to `https://mysolutionz.co.uk/…` |
| `<html lang="en-US">`, `og:locale=en_US` | `lang="en-GB"`, `og:locale=en_GB`, `inLanguage: "en-GB"` |
| OG image 603×722 portrait | 1200×630 per page |
| No Organization/LocalBusiness schema; retired SearchAction present | Full `@graph` (§6); no SearchAction |
| **Two different addresses**: Greatorex Street (London) in header/footer and **68 Goodwin Road, Slough SL2 2EU** in contact section/map | **One NAP everywhere** (London), pending client confirmation (open question) |
| Header `mailto:` goes to **immigway@gmail.com** while showing info@mysolutionz.co.uk | `mailto:info@mysolutionz.co.uk` |
| Phone shown as `00447466063564` | `+44 7466 063564` / `tel:+447466063564` |
| No company number / registered office | Footer disclosure: My Solutionz Ltd, company no. 15677749 (see 13) |
| Privacy policy only as a PDF (`…/Privacy-Poilcy-My-Solutionz-20-7-2026.pdf`) | HTML `/privacy-policy/`; PDF 301 → page |
| Off-topic health post (diabetes) + 16 health `/tag/` pages | **410 Gone** (off-topic YMYL content dilutes topical authority) |
| 37 indexable theme-template URLs `/pxl-template/*` | **410** for the whole prefix |
| Home title: "My Solutionz \| Expert Healthcare Tender Writing Services" | New keyword strategy (§3) keeps healthcare as a priority cluster |

## 2. Old URL → new URL map (🔒 every URL gets 200, one-hop 301, or 410)
| Old URL | Action | New URL |
|---|---|---|
| `/` | 200 | `/` |
| `/about-us/` | **keep** (200) | `/about-us/` |
| `/contact-us/` | **keep** (200) | `/contact-us/` |
| `/faq/` | **keep** (200) | `/faq/` |
| `/our-services/` | 301 | `/services/` |
| `/service/` | 301 | `/services/` |
| `/service/tender-writing/` | 301 | `/services/bid-proposal-management/tender-bid-writing/` |
| `/service/tender-review/` | 301 | `/services/bid-proposal-management/expert-review/` |
| `/service/tender-search/` | 301 | `/services/bid-proposal-management/opportunity-identification/` |
| `/service/website-development/` | 301 | `/services/digital-presence/website-development/` |
| `/service/social-media-management/` | 301 | `/services/digital-presence/social-media-management/` |
| `/service/cqc-registration/` | 301 | `/services/compliance-virtual-assistance/cqc-registration/` |
| `/service-category/<slug>/` (5) | 301 | the matching sub-service page above |
| `/whats-in-a-health-and-social-care-tender-tender-vocabulary-explained/` | 301 | `/insights/health-and-social-care-tender-vocabulary-explained/` |
| `/diabetes-prevention-and-management-…/` | **410** | — |
| `/category/health-care/` | 301 | `/insights/category/health-social-care/` |
| `/tag/*` (16) | **410** | — |
| `/author/developer/` | 410 | — |
| `/pxl-template/*` (37) | **410** | — |
| `/feed/`, `/comments/feed/` | 301 | `/insights/feed.xml` |
| `/sitemap_index.xml`, `/page-sitemap.xml`, `/post-sitemap.xml`, … | 301 | `/sitemap.xml` |
| `/about/`, `/contact/` (already 301 today) | 301 | `/about-us/`, `/contact-us/` |
| `/faq/` (currently **immigration demo content**) | **keep URL, replace content** | `/faq/` (real FAQs) |
| `/case-studies/`, `/careers/` (empty theme archives returning 200) | 410 | — (or 200 later if real pages are built) |
| `/page/2/` (duplicate of home) | 301 | `/` |
| Empty test categories (`/category/uncategorized/`, `/category/testing-cat/`, `/category/post-schedules/`, `/category/new-testing-cat-from-zprohub/`) and 12 empty test tags | 410 | — |
| `/attachment/*` | 410 (or 301 to the new image) | — |
| `/?s=…`, `/?p=ID`, `/wp-json/…`, `/wp-admin/…`, `/wp-login.php` | 410 / 404 | — |
| `/wp-content/uploads/…` images that were indexed | 301 | the new equivalent image where one exists |
| Privacy PDF | 301 | `/privacy-policy/` |

> ⚠ This **updates `08-sitemap-and-page-blueprints.md`**: About = **`/about-us/`**, Contact = **`/contact-us/`** (existing URLs are kept, per Google's site-move guidance: "prefer keeping URLs unchanged").
> Migration: also submit a temporary `/legacy-sitemap.xml` of old URLs for ~4–6 weeks so Google recrawls the redirects. Keep redirects **at least 1 year** (permanently, in practice).

## 3. Keyword strategy (🟡 to validate with keyword data at build time)
| Cluster | Primary page | Example target queries |
|---|---|---|
| **Bid & proposal management** (category) | `/services/bid-proposal-management/` | bid management services, bid and proposal management, bid management consultancy UK, tender management services |
| Tender & bid writing (core money page) | `…/tender-bid-writing/` + Home | tender writing services UK, bid writing services, professional tender writers, framework application support |
| Opportunity identification | `…/opportunity-identification/` | tender search service, find public sector tenders, tender alerts UK, bid/no-bid |
| Expert review | `…/expert-review/` | tender review service, bid review, independent bid review |
| **Compliance & virtual assistance** (category) | `/services/compliance-virtual-assistance/` | compliance support for care providers, virtual assistant services UK |
| CQC registration | `…/cqc-registration/` | CQC registration support, CQC registration consultant |
| Policy management | `…/policy-management/` | policies and procedures for care providers, policy management service |
| Carbon reduction plan | `…/carbon-reduction-plan/` | carbon reduction plan PPN 006, carbon reduction plan for tenders |
| Data entry & management | `…/data-entry-management/` | data entry services UK, outsourced data management |
| Care plans | `…/care-plans/` | care plan writing, care plan templates support |
| **Digital presence** (category) | `/services/digital-presence/` | digital presence services for small businesses |
| Website development / social media / email marketing / SEO & analytics | `…/<sub-service>/` | website development for SMEs, social media management UK, email marketing services, SEO services for small businesses (secondary priority) |
| Healthcare & social care (priority sector) | `/sectors/healthcare-social-care/` | care tender writing, health and social care tender writing, NHS tender writing |
| Other sectors | `/sectors/<slug>/` | cleaning tender writing, security tender writing, transport tender writing, facilities management tenders |
| Informational (blog) | `/insights/…` | how to write a tender, Procurement Act 2023 explained, Find a Tender vs Contracts Finder, social value in tenders, quality questions examples |
| Local | Home + Contact | tender writing London, bid writer London |

Rules: one primary query per page (no cannibalisation); hub-and-spoke links (service ↔ sector ↔ insights); **no doorway pages** (no thin city/borough pages; spam policy).

## 4. Per-page on-page rules (🔒)
| Element | Rule |
|---|---|
| `<title>` | Unique; primary query first; brand last: `Tender Writing Services UK \| My Solutionz`. Aim ~50–60 chars ⚖️ (Google sets no limit, but truncates by width) |
| Meta description | Unique, written by hand, value + proof + CTA, ~120–160 chars ⚖️ (no limit per Google; often rewritten from page content). Not a restatement of the title |
| H1 | Exactly one, visible, server-rendered, matches the title topic (accessibility convention ⚖️; Google says heading order doesn't affect ranking) |
| Headings | Logical H2/H3 outline; question-style H2s where natural (helps AI answers) |
| First 100 words | Contain the primary query naturally |
| Canonical | Absolute, self-referencing, `https://mysolutionz.co.uk/…/` with trailing slash, no params; never changed by JS |
| Robots | `index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1` by default; `noindex, follow` for thank-you and any search/filter results; staging = HTTP auth + `noindex` |
| Open Graph | `og:locale=en_GB`, `og:type`, `og:site_name`, `og:title` (no brand suffix), `og:description`, `og:url` (= canonical), `og:image` 1200×630 + `:width`, `:height`, `:alt`, `:type`; blog: `article:published_time`, `modified_time`, `author`, `section` |
| X / Twitter | `twitter:card=summary_large_image`, `twitter:image:alt`; title/description fall back to OG. No `twitter:site` (no X account) |
| Internal links | Descriptive anchors; every page ≤ 3 clicks from Home; no orphans; 2–5 contextual links per 1,000 words in articles |
| URL | lowercase, hyphenated, 2–5 words, trailing slash |
| Content | People-first, first-hand expertise, specific evidence; **published + updated dates** on articles; author bios with credentials |
| Meta keywords | **Never** (ignored by Google) |

## 5. Images (🔒)
- Every content image is an `<img>` (CSS backgrounds are **not indexed**). Decorative images: `alt=""` or CSS.
- **Alt text:** describes the image in context, ~10–125 chars, keywords only where natural. Example: `alt="Bid writer reviewing a public sector tender response with a client"`.
- **File names:** descriptive, lowercase, hyphenated: `tender-writing-team-meeting-london.avif` (never `IMG_1234.jpg`).
- `<picture>`: AVIF → WebP → JPEG `<img>` fallback; `srcset` 400/800/1200/1600/1920w + `sizes`; same aspect ratio across candidates.
- `width` + `height` on every image. `loading="lazy"` + `decoding="async"` **below the fold only**. **LCP image: never lazy, `fetchpriority="high"`**, present in HTML.
- Weight targets: thumbnails < 50KB, content < 100KB, hero < 200KB.
- **Preferred image** per page (Google, Mar 2026): set `primaryImageOfPage` in JSON-LD + a **photographic** `og:image` (avoid text-heavy cards for the preferred image).
- Optional image entries in the XML sitemap (`<image:image><image:loc>` only).

## 6. Structured data (JSON-LD, server-rendered, one `@graph` per page, linked by `@id`)
| Page type | Types |
|---|---|
| **All pages** | `WebPage` (or `AboutPage` / `ContactPage` / `CollectionPage`) + `BreadcrumbList` (except Home) |
| **Home** | `Organization` (→ `LocalBusiness` **only if** the London office is staffed and visitable) with `name: "My Solutionz"`, `legalName: "My Solutionz Ltd"`, `foundingDate: "2024-04-24"`, `identifier` (PropertyValue, Companies House no. 15677749, optional), `url`, `logo` (≥112px PNG, works on white), `description`, `email`, `telephone`, `address` (GB), `areaServed`, `contactPoint`, `sameAs` [LinkedIn, Instagram, Facebook], `foundingDate`, `vatID` (if any), `knowsAbout`; + `WebSite` (`name`, `alternateName`, `publisher`, `inLanguage: "en-GB"`), **no SearchAction** (retired Nov 2024) |
| **Service category page** | `Service` for the category + `hasOfferCatalog` (`OfferCatalog` → each sub-service as `Offer` › `itemOffered: Service` with its URL) |
| **Sub-service page** | `Service` (`name`, `serviceType`, `description`, `provider` → `#organization`, `areaServed: United Kingdom`, `audience: BusinessAudience`, `url`, `isRelatedTo` → category `@id`) |
| **Sector page** | `WebPage` + `Service` list (`hasOfferCatalog`) or `about` |
| **Blog post** | `BlogPosting` (`headline`, `description`, `datePublished`/`dateModified` ISO 8601 with timezone, `author` Person with `url` + `sameAs` LinkedIn + `jobTitle`, `publisher` → `#organization`, `image` 16:9 + 4:3 + 1:1, `mainEntityOfPage`) |
| **Blog listing** | `CollectionPage` (+ `Blog`) |
| **FAQ** | Visible Q&A. `FAQPage` markup is **optional**: Google stopped showing FAQ rich results for all sites on 7 May 2026. Keep the content (it helps users and AI answers) |
| ❌ Never | `HowTo` (removed 2023); `AggregateRating`/`Review` for our own business (self-serving reviews are ineligible); `SearchAction`; markup for content that isn't visible |
| ⚖️ `ProfessionalService` | schema.org marks it deprecated; use `Organization` or `LocalBusiness` |

Validate every template with the **Rich Results Test** and **validator.schema.org** (prompt P30). JSON-LD is printed with `json_encode(…, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG)`.

## 7. The `<head>` template (🔒, `templates/partials/head-seo.php`)
Order matters: charset first, then viewport, title, description, canonical, robots; then consent-mode default (before any Google tag); preloads; CSS; deferred JS; OG/Twitter; icons; manifest; JSON-LD.
```html
<!doctype html>
<html lang="en-GB" class="no-js">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- never disable zoom -->
  <title>{title}</title>
  <meta name="description" content="{description}">
  <link rel="canonical" href="{canonical}">
  <meta name="robots" content="{robots}">
  <script>document.documentElement.classList.replace('no-js','js')</script>
  <!-- Consent Mode v2 default = denied (only if Google tags are used); loader injected after consent -->
  <link rel="preload" href="/assets/fonts/mona-sans-var.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/assets/fonts/google-sans-var.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="stylesheet" href="/assets/build/main.[hash].css">
  <script type="module" src="/assets/build/main.[hash].js"></script>
  <meta property="og:locale" content="en_GB">
  <meta property="og:type" content="website|article">
  <meta property="og:site_name" content="My Solutionz">
  <meta property="og:title" content="{og_title}">
  <meta property="og:description" content="{og_description}">
  <meta property="og:url" content="{canonical}">
  <meta property="og:image" content="{og_image_1200x630}">
  <meta property="og:image:width" content="1200"><meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="{og_image_alt}">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:image:alt" content="{og_image_alt}">
  <link rel="icon" href="/favicon.ico" sizes="48x48">                 <!-- Google: ICO/PNG (SVG not in its list) -->
  <link rel="icon" type="image/png" sizes="192x192" href="/icon-192.png">
  <link rel="icon" type="image/svg+xml" href="/icon.svg">              <!-- browsers -->
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">           <!-- 180×180 -->
  <link rel="manifest" href="/site.webmanifest">
  <meta name="theme-color" content="#011B43">
  <link rel="alternate" type="application/rss+xml" title="My Solutionz Insights" href="https://mysolutionz.co.uk/insights/feed.xml">
  <script type="application/ld+json">{json_ld_graph}</script>
</head>
```
- **hreflang:** not needed (single en-GB site). Optional self-referencing `en-GB` + `x-default` only.
- Search Console + Bing verification via **DNS TXT** (no meta tags needed).

## 8. Technical SEO (🔒)
| Item | Spec |
|---|---|
| Host/protocol | One-hop 301 → `https://mysolutionz.co.uk/` (non-www) + HSTS |
| Trailing slash | Enforced (301), consistent in canonicals, sitemap and internal links |
| Uppercase URLs | 301 → lowercase |
| Tracking params | Canonical strips `utm_*`, `gclid`, `fbclid` |
| XML sitemap | PHP-generated `/sitemap.xml` (index → pages, services, sectors, insights); canonical 200 URLs only; **accurate `lastmod`** (priority/changefreq ignored by Google) |
| robots.txt | `Allow: /`, `Disallow: /thank-you/`, `Sitemap:` line. AI crawlers: allow search bots (`OAI-SearchBot`, `Claude-SearchBot`, `PerplexityBot`); training bots (`GPTBot`, `ClaudeBot`, `CCBot`, `Google-Extended`) = **client's business decision** (blocking doesn't affect Google Search) |
| llms.txt | Optional: harmless, **not used by Google Search** ⚖️. Provide a short one (company summary + key URLs) |
| 404 | Real 404 status, helpful page, no canonical; 410 for removed junk |
| Pagination | `/insights/page/2/`, crawlable `<a href>`, each page self-canonical |
| HTML size | Keep each HTML response < 2MB uncompressed (Googlebot limit since Feb 2026); watch inline SVGs |
| Feeds | `/insights/feed.xml` (RSS) |
| Mobile-first | Same content, headings, meta, schema and alt text on mobile and desktop |
| Core Web Vitals | See `12` (LCP ≤ 2.5s, INP ≤ 200ms, CLS ≤ 0.1 at p75) |

## 9. JavaScript, GSAP and SEO (🔒: critical because the site is animation-heavy)
1. **All content is in the server HTML** (text, links, `<img src>`, JSON-LD). GSAP only enhances.
2. Never hide content by default in CSS. Initial animation states exist only under `html.js`; if JS fails, everything is visible.
3. **Googlebot doesn't scroll or click.** Scroll-triggered reveals must not leave text invisible in the rendered DOM: reveals use `once: true`; scrubbed "read-along" text starts at **opacity 0.2 (never 0)**; **launch gate:** URL Inspection → rendered screenshot/HTML must show all text on Home, a service page and an article.
4. **LCP:** never fade the hero H1 or hero photo from opacity 0 (opacity-0 elements aren't LCP candidates until visible). Animate transform/clip only; the hero photo is visible from first paint (see the correction in `05` §6).
5. **Splash:** brief (≤ 1.6s + hand-off), first visit only, skippable, page painted underneath; it must not behave as an intrusive interstitial.
6. **Smooth scroll (Lenis)** must not break `#hash` deep links, the back button or Google's "read more" deep links (keep content visible, don't force scroll position on load, preserve the hash). **No back-button hijacking** (spam policy, 2026).
7. Accordions/tabs: content in the DOM (hidden-but-expandable content is fine).
8. Real `<a href>` links for all navigation (no JS-only links, no `#` routing).

## 10. E-E-A-T and AI search (GEO)
- "Optimising for generative AI search is still SEO" (Google, 2026). No separate "AI versions" of pages.
- Lead key sections with a **direct 40–60-word answer**, then detail. Use question-style H2s on informational pages.
- Named authors with bios, credentials and LinkedIn; first-hand case examples (with permission); sourced, dated statistics; visible "Last updated".
- Consistent entity data: About page = LinkedIn = Google Business Profile = schema.
- Trust pages: About, Contact, Privacy, Terms, Accessibility, company details in the footer.
- **Claims compliance (ASA/CAP code):** no unverified "win rate" or "No.1" claims; any success statistic needs evidence and a date range.

## 11. Local SEO (London / UK)
- NAP identical across site, schema, Google Business Profile, Bing Places, Apple Business Connect, LinkedIn, Facebook, directories.
- **Google Business Profile eligibility:** requires in-person contact at the address during stated hours; **virtual offices are not eligible**. ❗ Confirm whether Unit A53, 4–6 Greatorex Street is a staffed office. If not, use a service-area profile (address hidden) or skip GBP and rely on Organization schema.
- A London 020 number is optional (Google prefers local numbers where possible).
- Citations: Bing Places, Apple Business Connect, Yell, Thomson Local, FreeIndex, Clutch/Bark; memberships (e.g. APMP UK, FSB, London Chamber) only if real.
- Reviews: ask all clients neutrally, never gate, never fake (CMA/DMCC Act guidance).

## 12. Launch and monitoring checklist
- [ ] Every old URL returns 200, single-hop 301 or 410 (scripted check against the inventory).
- [ ] Every page: unique title/description, canonical, OG/Twitter, JSON-LD, one H1 (scripted crawl, e.g. Screaming Frog or `/seo audit`).
- [ ] Rich Results Test + schema validator pass on each template.
- [ ] URL Inspection rendered screenshot shows full text (GSAP pages).
- [ ] No cookies/pixels before consent (DevTools).
- [ ] Share previews checked (Facebook Sharing Debugger, LinkedIn Post Inspector).
- [ ] Search Console **Domain property** (DNS) + Bing Webmaster Tools; submit `/sitemap.xml` + temporary `/legacy-sitemap.xml`.
- [ ] Monitor Page Indexing, 404s, CWV and Performance weekly for 8 weeks.
