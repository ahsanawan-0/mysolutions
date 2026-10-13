# 13 — UK Legal and Compliance

> **Status:** 🔒 LOCKED requirements. ⚠ This is a technical specification, **not legal advice**. The final privacy/cookie/terms text should be reviewed by the client or their adviser.
> Sources checked 13 Oct 2026: ICO "storage and access technologies" guidance (final 29 Apr 2026), Data (Use and Access) Act 2025 (cookie exceptions in force 5 Feb 2026, SI 2026/82), Companies (Trading Disclosures) Regulations 2015 (SI 2015/17) regs 24–25, Electronic Commerce Regulations 2002 reg 6, Google Consent Mode docs, CMA fake-review guidance (CMA208), ASA CAP Code.

---

## 1. Company disclosures (footer on every page) 🔒
Required for a UK **limited company** website (SI 2015/17 reg 24–25; E-Commerce Regs 2002 reg 6):
- Registered company name (exactly as at Companies House)
- Part of the UK where registered (e.g. "Registered in England and Wales")
- Company number
- Registered office address (**label it** "Registered office" if it differs from the trading address, so NAP signals don't conflict)
- Email address (info@mysolutionz.co.uk) and geographic address
- VAT number, if VAT-registered
- Professional body memberships, if any

**Footer text (✅ verified on Companies House, 13 Oct 2026: MY SOLUTIONZ LTD, active private limited company, incorporated 24 April 2024):**
> `© 2026 My Solutionz Ltd. Registered in England and Wales, company no. 15677749. Registered office: Unit A53, 4–6 Greatorex Street, London E1 5NF. Email: info@mysolutionz.co.uk`

Add `VAT no. GB…` only if the company is VAT-registered (🟡 client to confirm). The registered office and trading address are the same, so there is no NAP conflict.

## 2. Cookies and similar technologies (PECR + UK GDPR) 🔒
**Default:** **no non-exempt cookies, pixels, local storage or tags before a positive opt-in.**

| Rule (ICO) | Implementation |
|---|---|
| Consent = clear positive action; silence/scrolling ≠ consent; no pre-ticked boxes; no "by continuing to browse" | Banner with explicit buttons; categories unticked by default |
| **"Reject all" equally prominent to "Accept all"** | Two identical-style buttons on the first layer + "Manage preferences" |
| Granular by purpose; name third parties | Categories: Strictly necessary (always on) · Analytics · Marketing (only if used) |
| Withdrawing as easy as giving | Persistent **"Cookie settings"** link in the footer reopens the panel; withdrawal deletes cookies |
| Records of consent | Store consent choice + timestamp + version (first-party cookie `ms_consent`, 6 months); re-ask after 6 months or when the policy version changes |

**Exemptions (no consent needed):**
- Strictly necessary: consent-state cookie, CSRF/session cookie for forms, security, load balancing.
- **New since 5 Feb 2026 (DUAA):** *statistical/analytics* purposes (aggregate stats to improve our own site, provider acting purely as processor, no profiling/advertising, clear info + simple free way to object) and *appearance/functionality* (e.g. remembering reduced-motion or theme choice). Both still need **clear information and an objection mechanism**.

**Analytics decision (🟡, recommendation):**
- **Option A (recommended):** privacy-first analytics (e.g. **Plausible**, **Fathom**, or self-hosted **Matomo** configured cookieless/as processor). This likely fits the statistical exemption: disclose it in the cookie policy + offer an opt-out toggle.
- **Option B:** **GA4 via GTM only after consent**, using Google **Consent Mode v2 in Basic mode** (tags not loaded at all until consent). The ICO does not confirm GA4 qualifies for the exemption, so treat it as consent-required. Consent Mode default (all `denied`) must run **before** any Google tag; `gtag('consent','update',…)` on choice.
- Marketing pixels (Meta, LinkedIn Insight, Google Ads remarketing): consent-required. Only add if the client runs ads.

**Third-party embeds:** Google Maps, YouTube (use `youtube-nocookie.com`), social widgets → **click-to-load** placeholders (or a static map image linking to Google Maps). **Fonts self-hosted** (no Google Fonts CDN). **No reCAPTCHA** (profiling); use honeypot + time-trap, or Cloudflare Turnstile disclosed as strictly necessary security.

**Banner UX (C24):** non-blocking bottom glass sheet that doesn't cover the hero/LCP area; keyboard accessible; focus moves to it on first load without trapping; respects reduced motion; text in plain English.

**Penalties:** since 5 Feb 2026 maximum PECR fines align with UK GDPR levels (up to £17.5m or 4% of global turnover).

## 3. Privacy notice (`/privacy-policy/`, HTML) 🔒
UK GDPR Art. 13 contents:
- Controller identity + contact details (and the ICO registration number once the data protection fee is paid)
- What data we collect (contact form, quote form, emails, calls, analytics if used) and **purposes + lawful bases** (e.g. legitimate interests / steps prior to contract for enquiries; consent for marketing/analytics where applicable)
- Recipients and processors (hosting provider, email/SMTP provider, analytics provider, CRM if any)
- International transfers + safeguards (e.g. US-based processors)
- Retention periods (e.g. enquiries 24 months if no contract)
- Data subject rights (access, rectification, erasure, restriction, objection, portability, withdraw consent)
- Right to complain to the **ICO** (ico.org.uk) and our **data protection complaints procedure** (DUAA requirement): a simple complaints route (email or form)
- Whether providing data is required; automated decision-making (none)
- Linked from: footer (every page), and **next to every form submit button**.
- The old PDF policy 301-redirects here.

## 4. Cookie policy (`/cookie-policy/`) 🔒
Table of every cookie/technology: **name · provider · purpose · category · duration · exempt? · how to object/withdraw**. Generated from a PHP config array so the banner and the policy never diverge.

## 5. Terms and conditions (`/terms-and-conditions/`) 🟡
Website terms of use (acceptable use, IP, liability, governing law England & Wales). Service terms (engagement, fees, deadlines, client responsibilities, confidentiality, no guarantee of contract award) are recommended as a separate client-agreement document. ❗ Client to supply or approve.

## 6. Accessibility statement (`/accessibility/`)
WCAG 2.2 AA target, known limitations, contact route for accessibility issues, date of last review.

## 7. Marketing claims and testimonials 🔒
- **ASA/CAP Code:** claims must be substantiated. No "guaranteed win", "No.1", or "% win rate" without evidence and a stated period/sample.
- **Testimonials:** genuine, with written permission, attributable (name/role/company or anonymised with consent), no undisclosed incentives (CMA guidance on fake reviews under the DMCC Act, in force since 6 Apr 2025).
- No marking up own testimonials as review stars (also a Google rule, see `10` §6).
- **Confidentiality:** case studies must not disclose client tender content or buyer-confidential information without consent.

## 8. Forms and email
- Server-side validation; data sent only to the company mailbox over authenticated SMTP (TLS).
- If submissions are stored (DB/log), apply the retention period and secure storage.
- Marketing opt-in: **separate, unticked** checkbox. Enquiry processing doesn't require consent, but marketing emails do (PECR soft opt-in rules apply only to existing customers).
- Auto-reply email includes the privacy notice link.

## 9. Pre-launch compliance checklist
- [ ] Company disclosures in the footer (exact Companies House data).
- [ ] DevTools check: **zero** non-exempt cookies/requests before consent (Network + Application tabs).
- [ ] Reject-all and Accept-all equally prominent; footer "Cookie settings" works; withdrawal removes cookies.
- [ ] Privacy notice and cookie policy published as HTML; linked from every form.
- [ ] Maps/YouTube are click-to-load.
- [ ] ICO data protection fee paid (client).
- [ ] All claims and testimonials substantiated and permissioned.
