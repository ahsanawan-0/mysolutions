# 01 — Project Brief

> **Status:** 🔒 LOCKED (business facts and scope) · 🟡 items marked "TBD" await client input.

## 1. The business

| Field | Value |
|---|---|
| Trading name | **My Solutionz** |
| Tagline | **Solutions Crafted for Success** |
| What they do (🔒 3 categories, 13 Oct 2026) | **1. Bid & Proposal Management:** Tender/Bid Writing · Opportunity Identification · Expert Review. **2. Compliance & Virtual Assistance:** CQC Registration · Policy Management · Carbon Reduction Plan · Data Entry & Management · Care Plans. **3. Digital Presence:** Website Development · Social Media Management · Email Marketing · SEO & Analytics |
| Sectors served | Healthcare & Social Care · Transport · Cleaning · Education & Training · Security · Facilities Management · Building Maintenance |
| Clients | From first-time bidders to established providers chasing large contracts and frameworks; public **and** private sector |
| Experience | ✅ **"Over 10 years of business development experience"** (confirmed by client). The docx "16 years" in Our Story is changed to 10. Never "10 years in business" (company incorporated 2024) |
| Legal entity | ✅ **MY SOLUTIONZ LTD**, company no. **15677749**, England and Wales, incorporated 24 April 2024, registered office Unit A53 4-6 Greatorex Street, London E1 5NF (verified on Companies House) |
| Market | United Kingdom (en-GB spelling, GBP, UK date format `13 October 2026`) |
| Current site | https://mysolutionz.co.uk/ (being replaced; only its **content and icons** are reusable) |

### NAP (Name, Address, Phone): must be identical everywhere (footer, contact page, schema, Google Business Profile)

```
My Solutionz
Unit A53, 4–6 Greatorex Street
London E1 5NF
United Kingdom

Phone:  +44 7466 063564      (tel:+447466063564)   [given as 00447466063564]
WhatsApp: https://wa.me/447466063564
Email:  info@mysolutionz.co.uk
```
> ✅ Client confirmed the London address. The Slough address on the current site (68 Goodwin Road) is **not** used anywhere on the new site.

### Social profiles (used in footer + `sameAs` schema)
- LinkedIn: https://www.linkedin.com/company/mysolutionz/
- Instagram: https://www.instagram.com/mysolutionz (the `?igsh=` share parameter is **dropped** in links)
- Facebook: https://www.facebook.com/people/My-Solutionz/61558654196971/ (the `?mibextid=` parameter is **dropped**)

### Still needed from the client (TBD)
- ~~Companies House number and registered office~~ ✅ received (15677749).
- VAT number (only if VAT-registered).
- Office/support hours (for the "Business Hours" card and `openingHoursSpecification`).
- Real team names, roles and photos (or decision to omit the team section).
- Testimonials (with written permission), case studies / wins, accreditations or memberships.
- Final services list and service-page copy, blog posts.

## 2. Goals

1. **Generate qualified enquiries** (quote requests, consultation bookings, calls) from UK businesses preparing tenders.
2. **Rank in the UK** for "tender writing services", "bid writing services UK", "bid management consultancy", sector variants ("care tender writing", "cleaning tender writing", …) and London local intent.
3. **Look premium and trustworthy.** Apple-level polish, distinctive motion, clearly ahead of competitors in the sector.
4. **Preserve existing SEO equity** of the current domain (301-redirect every old URL).
5. **Be fast and accessible.** Core Web Vitals "Good", WCAG 2.2 AA.

## 3. Audience
| Persona | Need | Key message |
|---|---|---|
| First-time bidder (SME owner in care, cleaning, security …) | Doesn't know where to start; fears compliance mistakes | "We guide you from ITT to submission." |
| Established provider / bid team lead | Capacity overflow, wants higher scores on big contracts and frameworks | "Evidence-led, sector-specific responses that score." |
| Operations director / MD | Wants contract growth with minimal management time | "We take the pressure off your team." |
| Small business needing ongoing support | Tender search, alerts, digital presence, admin help | "Flexible support that works around you." |

## 4. Scope

**In scope**
- Custom **PHP 8.x** website (no WordPress), hand-built templates, clean URLs.
- Pages: Home, About (single long page with the 5 sub-sections), Services hub + one page per service, Sectors hub + one page per sector, Insights/Blog (listing, category, article), Contact, Free Quote/Consultation, FAQ (optional), Privacy Policy, Cookie Policy, Terms, Accessibility Statement, 404, HTML sitemap. See `08-sitemap-and-page-blueprints.md`.
- Splash screen with logo animation (pixel2motion).
- Full technical + on-page SEO (every page has unique title, description, canonical, OG/Twitter, JSON-LD), XML sitemaps, robots.txt, 301 map.
- GSAP motion system (parallax, scroll reveals, pinned sections, sliders).
- Contact/quote forms with server-side validation, spam protection and email delivery.
- UK cookie consent (PECR/UK GDPR), analytics loaded only after consent.

**Out of scope (unless requested)**
- E-commerce/payments, client login portal, multilingual site.
- A full CMS. Blog management approach is an open decision (see `11-tech-architecture-php.md` §6).

## 5. Success metrics (first 90 days after launch)
- Lighthouse ≥ 95 Performance / 100 Accessibility / 100 Best Practices / 100 SEO on Home, a Service page and a Blog post (mobile).
- CWV (field): LCP < 2.5s, INP < 200ms, CLS < 0.1 at p75.
- 0 crawl errors from old URLs (all redirected), all pages indexed in Search Console.
- Enquiry conversion rate tracked (form submit + tel/mailto click events, post-consent).
