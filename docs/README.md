# My Solutionz: Website Specification Docs

> New website for **My Solutionz** (UK tender writing, bid management and business support).
> Stack: **custom PHP** · **GSAP + Lenis** · **Phosphor Icons** · Apple design principles · full SEO.
> Phase: **🔨 Build can start.** Run the prompts in [`/PROMPTS.md`](../PROMPTS.md) in order (P00 → P35). Client content is added via the content-fill prompts (C01–C07) as it arrives.

## How to use these docs
1. **Start with [`00-LOCKED-DECISIONS.md`](00-LOCKED-DECISIONS.md).** It is the contract: every locked instruction in one page.
2. Each numbered doc is the detailed spec for one area. Docs reference each other by number.
3. Answer [`15-open-questions.md`](15-open-questions.md) when sending the content files.
4. Legend used everywhere: 🔒 locked · 🟡 proposed (awaiting confirmation) · ❗ blocked on client input · ⚖️ convention that differs from Google's official guidance.

## Index
| # | Doc | What's inside |
|---|---|---|
| 00 | [LOCKED DECISIONS](00-LOCKED-DECISIONS.md) | All client instructions, closed and locked + pending items |
| 01 | [Project brief](01-project-brief.md) | Business, NAP, audience, goals, scope, success metrics |
| 02 | [Brand identity](02-brand-identity.md) | Logo anatomy, exact logo colours + full family with contrast, fonts, icons, imagery |
| 03 | [Design system (Apple)](03-design-system-apple.md) | Apple principles → web rules, tokens (spacing, radii, glass, shadows, motion), 30 components |
| 04 | [Reference teardown](04-reference-teardown.md) | Reverse-engineered Innovax (fonts, glass, notches), all inspiration images, strategicproposals.com |
| 05 | [Hero spec](05-hero-spec.md) | The exact hero: services **slider**, **glowing animated border**, Quick-Start bar, choreography |
| 06 | [Motion and scroll](06-motion-and-scroll.md) | GSAP/Lenis setup, motion catalogue (`data-anim`), strategicproposals patterns, SEO-safe motion |
| 07 | [Splash screen](07-splash-screen-logo-animation.md) | pixel2motion workflow, logo choreography, rules, embedding |
| 08 | [Sitemap and page blueprints](08-sitemap-and-page-blueprints.md) | URLs, navigation, footer, section-by-section plans for every page |
| 09 | [Content inventory](09-content-inventory.md) | Content we have, content scraped from the current site, icon mapping, gaps |
| 10 | [SEO specification](10-seo-specification.md) | Current-site audit, 301 map, keywords, per-page rules, schema, head template, GSAP-SEO rules |
| 11 | [Tech architecture (PHP)](11-tech-architecture-php.md) | Stack, folder structure, routing, rendering contract, security, deployment |
| 12 | [Performance and accessibility](12-performance-accessibility.md) | Core Web Vitals and weight budgets, motion performance rules, WCAG 2.2 AA |
| 13 | [UK legal and compliance](13-uk-legal-compliance.md) | Company disclosures, PECR/UK GDPR cookies (DUAA 2026), privacy, claims |
| 14 | [Build roadmap and prompts plan](14-build-roadmap-and-prompts-plan.md) | The 35-prompt sequence and the prompt format |
| 15 | [Open questions](15-open-questions.md) | What we need from the client (❗ = blocking) |

## Project folders
```
/docs/                     ← these specs (+ website_content/ supplied by client)
/docs/website_content/     ← "My Solutionz - Website content.docx" (About pages copy), pages_ref.jpeg,
                             current-site-content-scrape.md
/docs/website_content/services/  ← service page content, written step by step (category first, then sub-services)
/inspirations/             ← UI references (hero_should, section layouts, background art)
/logos/                    ← horizontal + vertical logo (JPEG)
```

## Key facts at a glance
- **Logo colours:** Sky `#8DD6FF` · Azure `#009AF0` · Navy `#003C8C`
- **Fonts:** Mona Sans (headings) · Google Sans (body) · Playfair Display Italic (accent words)
- **NAP:** My Solutionz, Unit A53, 4–6 Greatorex Street, London E1 5NF · +44 7466 063564 · info@mysolutionz.co.uk
- **Company:** My Solutionz Ltd · England and Wales · no. 15677749 · "over 10 years of business development experience"
- **Services:** 1. Bid & Proposal Management (Tender & Bid Writing · Opportunity Identification · Expert Review) · 2. Compliance & Virtual Assistance (CQC Registration · Policy Management · Carbon Reduction Plan · Data Entry & Management · Care Plans) · 3. Digital Presence (Website Development · Social Media Management · Email Marketing · SEO & Analytics)
- **Domain:** https://mysolutionz.co.uk/ (non-www, trailing slash, existing `/about-us/`, `/contact-us/`, `/faq/` kept)

## External references
- Apple design principles repo: https://github.com/hosseinirtr/apple-design-principles (→ Apple HIG)
- pixel2motion: https://github.com/nolangz/pixel2motion
- Innovax demo: https://innovax.themeht.com/ (ThemeForest item 65108152)
- Motion reference: https://strategicproposals.com/
- Current site (content and icons only): https://mysolutionz.co.uk/
- SEO skills: https://github.com/coreyhaines31/marketingskills · https://github.com/AgriciDaniel/claude-seo
- Google Search Central: https://developers.google.com/search/docs
