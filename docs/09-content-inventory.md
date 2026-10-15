# 09 — Content Inventory, Icon Mapping and Gaps

> **Status:** 🟡 Living document. Updated when the client's content files arrive.
> **Rule (🔒):** from the current site we reuse **content and icons only**. Everything else is new.

---

## 1. Content sources
| Source | File | Status | Use |
|---|---|---|---|
| **Client content docx** | `website_content/My Solutionz - Website content.docx` | ✅ Received (About 13 Oct 2026; **updated 15 Oct 2026** with *Our Services*: Bid & Proposal Management + its 3 sub-services; About text unchanged) | **Final copy** for `/about-us/` (5 sections), the Bid & Proposal category page and its 3 sub-service pages (transcribed into `website_content/services/01*.md`) + reusable lines for Home |
| About-page order sketch | `website_content/pages_ref.jpeg` | ✅ Received | Locks the About order |
| **Current site scrape** | `website_content/current-site-content-scrape.md` | ✅ Captured 13 Oct 2026 | Base material for service pages, FAQ, 1 blog post (to be **rewritten**, not copied as-is) |
| **Service content (written with the client, step by step)** | `website_content/services/*.md` | 🟡 **4 of 15 final** (client copy 15 Oct 2026: `01`, `01a`, `01b`, `01c`) | Category + sub-service landing pages |
| Remaining page content | — | ❗ Awaited | Home, sectors, FAQ, blog |

## 2. What the current site tells us (scrape findings)
- It runs on **"Immigway"**, a visa/immigration WordPress theme, and **demo content is still live** (the `/faq/` page is 100% immigration demo Q&A). None of that is reused.
- Real content: **Home, About Us, Our Services, Contact Us**, **6 service pages** (Tender Writing, Tender Review, Tender Search, Website Development, Social Media Management, **CQC Registration**), and **1 relevant blog post** (health & social care tender vocabulary).
- Positioning today is **heavily healthcare** ("Expert Healthcare Tender Writing"); the new content (docx) is **multi-sector**. The new site keeps healthcare & social care as the **priority sector** but presents the full multi-sector offer.
- **Unsupported claims** on the current site (⚠ must be confirmed with evidence or dropped, per ASA/CAP rules): *150+ satisfied customers*, *250+ projects completed*, *75% success ratio*, *100% quickest response*, *100% customer satisfaction*, *100% satisfaction guaranteed*, *24/7 support centre*, *"testimonials and case studies"* (none exist).
- **Years of experience:** ✅ client confirmed **10+**. Change "over 16 years" in the docx's Our Story to "over 10 years". Wording is always "business development **experience**" (the company was incorporated on 24 April 2024). *15 Oct 2026: the new services copy says "over 16 years" 5 more times (Tender & Bid Writing ×2, Opportunity Identification ×1, Expert Review ×2); all are written as "over 10 years" in `services/01a`–`01c` (L05c).*
- A portrait photo **"Wasim Amjad"** exists in the media library (unused). ❗ Founder/team member? Permission to use?
- Logo on the site: 328×86 PNG (low-res) + 512×512 monogram PNG. ❗ Vector still needed.
- Contact behaviour: the business actively uses **WhatsApp** (`wa.me/447466063564`). → The new site gets a **WhatsApp click-to-chat** link (Phosphor `whatsapp-logo`) in the contact cards, footer and mobile sticky bar. No third-party chat widget.
- The current consultation form asks for **preferred date and time**. The new quote form keeps an optional "Preferred call-back time".

## 3. Page-by-page content map
| Page / section | Source | Status |
|---|---|---|
| Home hero (headline, sub, slider cards) | New (we propose options) + service names | ❗ Client approval |
| Home triage row | New | 🟡 We draft |
| Home "Our Approach" intro | docx (About Us para 1–3, condensed) | ✅ |
| Home services cards | Service one-liners (new, from scrape + docx) | 🟡 We draft |
| Home sectors | docx sector list (7) + short challenge lines | 🟡 We draft |
| Home process 01–05 | docx "Our Approach" steps (condensed) | ✅ |
| Home Why Us bento | docx "Why Work With Us" (7 reasons, condensed) | ✅ |
| Home testimonials | Client | ❗ (hidden until supplied) |
| Home FAQ teaser | Scrape FAQ Q1–Q4 (rewritten) + new | 🟡 |
| About Us (5 sections) | **docx verbatim** (light edit for web scannability; keep meaning) | ✅ |
| Service pages (3 categories + 12 sub-services) | Written step by step in `website_content/services/`. Bid & Proposal category + Tender & Bid Writing, Opportunity Identification, Expert Review = **client copy** (15 Oct 2026); current-site copy remains raw material for CQC, Website, Social Media | 🟡 4 of 15 final |
| Sector pages (7) | New | ❗ Client input on real experience per sector |
| FAQ page | Scrape Q1–Q4 + new questions (process, pricing, timelines, confidentiality, Procurement Act 2023, first-time bidders, frameworks, CQC) | 🟡 |
| Insights | 1 migrated post (originality check + update) + new posts | ❗ |
| Contact | Scrape (cards + form, improved) | ✅ |
| Legal pages | New (rewrite of the PDF privacy policy) | 🟡 We draft, client approves |

## 4. Services (🔒 confirmed by client, 13 Oct 2026): 3 categories, 12 sub-services
Content is produced **step by step: category landing page first, then its sub-services.** Drafts live in `website_content/services/`.

| Category / sub-service | Slug | Phosphor icon (light) | Content status |
|---|---|---|---|
| **1. Bid & Proposal Management** (Azure) | `bid-proposal-management` | `kanban` | ✅ Final client copy (15 Oct): `services/01-bid-proposal-management.md` (6 step descriptions, subs intro and FAQs still provisional) |
| 1.1 Tender & Bid Writing | `tender-bid-writing` | `pen-nib` | ✅ Final client copy: `services/01a-tender-bid-writing.md` |
| 1.2 Opportunity Identification | `opportunity-identification` | `binoculars` | ✅ Final client copy: `services/01b-opportunity-identification.md` |
| 1.3 Expert Review | `expert-review` | `list-checks` | ✅ Final client copy: `services/01c-expert-review.md` |
| **2. Compliance & Virtual Assistance** (Navy) | `compliance-virtual-assistance` | `seal-check` | ⏳ Next |
| 2.1 CQC Registration | `cqc-registration` | `shield-check` | ⏳ (current-site copy available) |
| 2.2 Policy Management | `policy-management` | `files` | ⏳ |
| 2.3 Carbon Reduction Plan | `carbon-reduction-plan` | `leaf` | ⏳ |
| 2.4 Data Entry & Management | `data-entry-management` | `database` | ⏳ |
| 2.5 Care Plans | `care-plans` | `clipboard-text` | ⏳ |
| **3. Digital Presence** (Sky) | `digital-presence` | `devices` | ⏳ |
| 3.1 Website Development | `website-development` | `browser` | ⏳ (current-site copy available) |
| 3.2 Social Media Management | `social-media-management` | `share-network` | ⏳ (current-site copy available) |
| 3.3 Email Marketing | `email-marketing` | `envelope-open` | ⏳ |
| 3.4 SEO & Analytics | `seo-analytics` | `chart-line-up` | ⏳ |

> "Framework applications" (from the docx) is covered inside **Tender & Bid Writing** rather than as a separate page. Virtual-assistance tasks sit under category 2.

## 5. Sectors (from docx)
| Sector | Phosphor icon (light) | Slug |
|---|---|---|
| Healthcare & Social Care | `first-aid-kit` / `heartbeat` | `healthcare-social-care` |
| Transport | `bus` / `truck` | `transport` |
| Cleaning | `broom` | `cleaning` |
| Education & Training | `graduation-cap` | `education-training` |
| Security | `shield-star` | `security` |
| Facilities Management | `buildings` | `facilities-management` |
| Building Maintenance | `wrench` | `building-maintenance` |

## 6. Icon mapping: current site → Phosphor (🔒 Phosphor only)
The current site uses **Font Awesome Pro 5** (licensed to the theme, so not reusable), a Flaticon font and a theme icon font. Concepts are mapped to Phosphor:

| Where used | Current icon | Phosphor replacement |
|---|---|---|
| Email | `fa-envelope` / `flaticon-email` | `envelope-simple` |
| Address | `fa-map-marker-alt` / `flaticon-residential` | `map-pin` (address), `buildings` (office card) |
| Phone | `flaticon-call` / `flaticon-telephone-call` | `phone` |
| WhatsApp | `flaticon-chat` | `whatsapp-logo` |
| LinkedIn / Instagram / Facebook | `fab fa-*` | `linkedin-logo` / `instagram-logo` / `facebook-logo` |
| Dropdown caret | `caseicon-angle-arrow-down` | `caret-down` |
| Menu toggle | 9-dot icon | `list` (burger) |
| Search | `caseicon-search` | `magnifying-glass` |
| Scroll to top | arrow | `arrow-up` |
| Footer bullets | `fa-chevron-right` | `caret-right` |
| Read more / View more | `flaticon-right-arrow-2` | `arrow-right` / `arrow-up-right` (card corner button) |
| Proven Track Record | `fa-check` | `trophy` |
| Professional & Expert Team | `fa-check` | `users-three` |
| Satisfaction | `fa-check` | `seal-check` |
| Healthcare Tenders | `fa-books-medical` | `first-aid-kit` |
| Digital Services | `fa-people-arrows` | `devices` |
| Checklists | `flaticon-check-mark` | `check-circle` |
| Agile Methodology | `flaticon-global-economy` | `arrows-clockwise` |
| Collaborative Partnership | `fa-handshake-alt` | `handshake` |
| Continuous Improvement | `flaticon-profit` | `chart-line-up` |
| Transparent Communication | `flaticon-technical-support` | `chats-circle` |
| Dedication to Excellence | `flaticon-bank-card` (mismatch) | `medal` |
| Customer-Centric Focus | `flaticon-personal-branding` | `user-focus` |
| Stats (years / projects / success) | PNG badges | `calendar-check` / `briefcase` / `trophy` (rebuilt as HTML text) |
| Process steps 01–03 | PNG badges | `calendar-check` / `chats-circle` / `rocket-launch` |
| Quickest response | — | `lightning` |
| FAQ accordion | `fa-angle-down` / `plus` | `plus` ↔ `minus` (morph) |
| Privacy PDF | `fa-file-pdf` | not needed (HTML page) |

**New icons for the new content (docx):**
| Concept | Phosphor |
|---|---|
| 01 Understanding Your Business | `magnifying-glass` / `buildings` |
| 02 Tender Analysis & Bid Strategy | `strategy` |
| 03 Evidence-Led Tender Writing | `pen-nib` |
| 04 Quality Review & Compliance Checks | `seal-check` |
| 05 Final Submission & Ongoing Support | `paper-plane-tilt` |
| Business Development Experience | `trend-up` |
| Expertise Across Multiple Industries | `squares-four` |
| Your Business, Not a Generic Template | `fingerprint` |
| Focused on What Buyers Actually Evaluate | `target` |
| Professional Support Without the Extra Pressure | `hand-heart` |
| Flexible Support That Works Around You | `sliders-horizontal` |
| Committed to Your Long-Term Success | `handshake` |
| Mission | `compass` |
| Vision | `binoculars` / `eye` |

## 7. Writing guidance for the rewrite (🔒)
- UK English; plain, confident, specific (see `03` §4).
- Replace generic claims ("unmatched expertise", "sparkle", "lucrative") with **specifics**: what we do, how, for whom, what the client receives.
- Use current UK procurement vocabulary: **Procurement Act 2023**, Find a Tender, Contracts Finder, ITT, SQ/PSQ (Procurement Specific Questionnaire), quality/technical questions, social value, TUPE, mobilisation, frameworks/DPS (Dynamic Markets under the new regime).
- Every claim either has evidence or is removed.
- Each service/sector page has unique content (no swapped-word templates; spam-policy and SEO requirement).

## 8. Image needs (❗ client or licensed stock)
| Need | Qty | Notes |
|---|---|---|
| Hero building/London photo | 1 | Dark, cool, room on the left for copy |
| Team/office photos | 5–10 | Real if possible (E-E-A-T) |
| Service images | 8 | One per service, people + documents |
| Sector images | 7 | Recognisable sector context |
| Image-filled number photo | 1 | Handshake/meeting texture |
| Blog featured images | per post | 1200×675 (16:9) + crops 4:3 / 1:1 |
| OG images | per page | 1200×630, photographic, generated per page |
| Avatars (avatar stack) | 3 | Real team only, else a different visual |
