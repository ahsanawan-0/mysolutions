# 15 — Open Questions for the Client

> Answer these (inline is fine) together with the content files. Items marked ❗ **block** writing `PROMPTS.md`. Everything else has a sensible default (shown) that we'll use if you don't specify.

---

## A. Blocking ❗
| # | Question | Why it matters | Default if unanswered |
|---|---|---|---|
| Q01 ✅ | ~~Years of experience~~ → **10 years** (answered 13 Oct 2026) | | |
| Q02 ✅ | ~~Final services list~~ → **3 categories / 12 sub-services** (answered 13 Oct 2026, see `00` §K) | | |
| Q03 ❗ | **Content** for: 2 remaining service categories + 12 sub-services (being written step by step), Home, sector pages, FAQ, blog posts | Prompts paste exact copy | — |
| Q04 ❗ | **Vector logo** (SVG/AI/EPS)? | Splash animation, crisp header, favicon | We redraw the mark as SVG from the JPEG (3 polygons) and outline the wordmark |
| Q05 ✅ | ~~Company details~~ → **My Solutionz Ltd, no. 15677749** (verified on Companies House). Only open point: **VAT number, if VAT-registered** | Legal footer | No VAT line |
| Q06 🟡 | ~~Which address~~ → **London confirmed**. Still open: is Unit A53, 4–6 Greatorex Street a **staffed office** customers can visit, or a virtual/mail address? | Google Business Profile eligibility; LocalBusiness vs Organization schema | Organization schema, no GBP address until confirmed |
| Q06b | Bid & Proposal page: confirm (a) a **free quote** is offered, (b) you upload submissions to buyer portals when agreed, (c) you support **private sector proposals/RFPs** | Claims in the copy must be true | Kept as written |

## B. Content and trust
| # | Question | Default |
|---|---|---|
| Q07 | Testimonials with written permission (name, role, company, sector)? | Section hidden until supplied |
| Q08 | Team members to show (names, roles, photos, LinkedIn)? | Team section omitted |
| Q09 | Case studies / contract wins you can mention (anonymised OK)? | Not shown |
| Q10 | Accreditations, memberships (e.g. APMP), insurance, awards? | Not shown |
| Q11 | **Office / support hours** (for the "Business Hours" card and schema). The current site says "Support Centre available 24/7"; is that accurate? | Mon–Fri 9:00–17:30; no 24/7 claim |
| Q12 | Any real statistics we can publish? The current site claims **150+ satisfied customers, 250+ projects, 75% success ratio, 100% satisfaction, 24/7 support**. Can each be evidenced? (UK advertising rules require it) | Only years of experience + number of sectors/services |
| Q12b | A photo named **"Wasim Amjad"** is in the current site's media library. Founder/team member? May we use it (with name and role)? | Not used |
| Q13 | Author(s) for blog posts (name, credentials, photo, LinkedIn)? | "My Solutionz Team" organisation author |
| Q14 | Do the 2 existing blog posts stay? (Health & social care tender vocabulary = keep and improve; diabetes post = remove) | As stated |
| Q15 | Hero headline + sub-headline copy (or shall we write options)? | We propose 3 options |

## C. Brand and design
| # | Question | Default |
|---|---|---|
| Q16 | Approve the colour family generated from the logo (`02` §2) | Approved as written |
| Q17 | Approve fonts: Mona Sans + Google Sans + Playfair Display Italic (from Innovax) | Approved as written |
| Q18 | Hero label "Our Special Service" exactly, or "Our Core Services"? | "Our Special Service" |
| Q19 | Real photos of your team/office available? Otherwise licensed stock | Licensed stock, cool-graded |
| Q20 | Splash screen on **first visit only** (recommended) or every home-page visit? | First visit per session |

## D. Technical
| # | Question | Default |
|---|---|---|
| Q21 | **Hosting:** stay on the current host (it runs **LiteSpeed + PHP 8.2**; LiteSpeed reads `.htaccess`, so that's compatible) or move? PHP 8.2 security support ends 31 Dec 2026, so we need **PHP ≥ 8.3**. SSH/Git access available? | Same host, upgraded to PHP 8.3+ |
| Q22 | **Blog publishing:** Markdown files (developer publishes; recommended) or a simple admin panel so you can publish yourselves? | Markdown files |
| Q23 | Email for form submissions (info@mysolutionz.co.uk?) and SMTP provider (Microsoft 365 / Google Workspace / host) | info@ via host SMTP |
| Q24 | **Analytics:** privacy-first (Plausible/Fathom/Matomo, recommended) or Google Analytics 4 (consent required)? Any ad pixels? | Privacy-first analytics, no ad pixels |
| Q25 | AI crawlers: allow AI training bots (GPTBot, ClaudeBot, Google-Extended) or only AI search bots? | Allow search bots; allow training bots (maximises AI visibility) |
| Q26 | Access to Google Search Console / Analytics for the current site (to check which pages get traffic before migrating)? | We migrate everything per the redirect map |
| Q27 | Do you want a London **020** landline in addition to the mobile number? | Mobile only |
| Q28 | Single contact page with quote form (recommended) or a separate "Get a Quote" page? | Single `/contact-us/` with `#quote` |
| Q29 | Lead magnet (e.g. free "Tender Readiness Checklist" PDF) and newsletter? | Not at launch |
| Q30 | Live chat (e.g. WhatsApp click-to-chat)? | WhatsApp link only (no third-party widget) |
