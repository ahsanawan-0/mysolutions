<?php
declare(strict_types=1);

/**
 * Frequently asked questions (PROMPTS P15, P27), one list feeding the home teaser, /faq/ and the service pages.
 * Read via faqs() (all), faqs('home') (items tagged with that page), faqs(null, 'pricing-process') (one group) and
 * faq_groups() (slug → label, in display order for /faq/).
 *
 * Each item: id (anchor on /faq/: #faq-<id>), q, a, group (a key of 'groups'), pages (tags: 'home', 'faq', a service
 * category or sub-service slug from app/data/services.php, a sector slug …). Items appear in this order within each
 * group. Answers are plain text printed with rich() by the C17 accordion: paragraphs separated by blank lines, a
 * paragraph whose lines all start with "- " is a bullet list, `**bold**`, `[label](/path/)` internal links and
 * `[label](https://…)` external links (rel="noopener").
 * No FAQPage schema by default (config seo.faq_schema; FAQ rich results were retired on 7 May 2026, docs/10 §6); the
 * answers are visible on the page, which is what matters to users and AI answers.
 *
 * Every item is // PROVISIONAL until the client approves it (C06 replaces this file; docs/15 Q06c). The 4 questions
 * from the current site's home page (confidentiality, after submission, pricing, strengths) are rewritten in plain UK
 * English (P27).
 */
return [
    'groups' => [
        'basics'          => 'Tendering basics',
        'services'        => 'Our services',
        'pricing-process' => 'Pricing & process',
        'sectors'         => 'Sectors',
    ],

    'items' => [
        // ---------- Tendering basics ----------
        [ // PROVISIONAL (P27)
            'id'    => 'what-is-a-tender',
            'q'     => 'What is a tender?',
            'a'     => "A tender is a formal, competitive process an organisation uses to buy goods or services. The buyer publishes its requirements and how bids will be scored, suppliers submit written bids by a deadline, and the contract goes to the bid that offers the best combination of quality, price and, often, social value.\n\nPublic bodies such as councils, NHS organisations and government departments usually have to tender contracts above certain values. Private companies run tenders too, often called requests for proposal (RFPs).",
            'group' => 'basics',
            'pages' => ['faq'],
        ],
        [ // PROVISIONAL (P20, docs/website_content/services/01-bid-proposal-management.md §8)
            'id'    => 'bid-writing-vs-bid-management',
            'q'     => 'What is the difference between bid writing and bid management?',
            'a'     => 'Bid writing focuses on the written responses to the tender questions. Bid management covers the whole process around them: qualifying the opportunity, planning, coordinating contributors and evidence, compliance checks, reviews and submission. We provide either service, or both together.',
            'group' => 'basics',
            'pages' => ['faq', 'services', 'bid-proposal-management', 'tender-bid-writing'],
        ],
        [ // PROVISIONAL (P27)
            'id'    => 'procurement-act-2023',
            'q'     => 'What is the Procurement Act 2023 and how does it affect bidders?',
            'a'     => "The Procurement Act 2023 is the law that has governed most public procurement in England, Wales and Northern Ireland since 24 February 2025, replacing the Public Contracts Regulations 2015. For bidders, the main changes are:\n\n- one registration on the **Central Digital Platform**, where you keep your core company details up to date;\n- a shorter **Procurement Specific Questionnaire** for each tender in place of the old Selection Questionnaire;\n- contracts awarded to the **most advantageous tender**, with an assessment summary for every bidder;\n- more notices published across the life of a contract, and stronger rules for excluding suppliers.\n\nProcurements that started before 24 February 2025 can still follow the old rules, and healthcare services arranged by NHS bodies in England follow the separate Provider Selection Regime.",
            'group' => 'basics',
            'pages' => ['faq'],
        ],
        [ // PROVISIONAL (P27)
            'id'    => 'where-are-tenders-published',
            'q'     => 'Where are UK public sector tenders published?',
            'a'     => "Most UK public sector opportunities are advertised on [Find a Tender](https://www.find-tender.service.gov.uk/), the government's Central Digital Platform for notices under the Procurement Act 2023. [Contracts Finder](https://www.contractsfinder.service.gov.uk/) still carries notices for procurements that started under the previous rules. Scotland, Wales and Northern Ireland also run national portals: Public Contracts Scotland, Sell2Wales and eTendersNI.\n\nThe tender documents themselves usually sit on the buyer's own e-tendering portal, and many contracts are awarded through frameworks and dynamic markets. Our [opportunity identification](/services/bid-proposal-management/opportunity-identification/) service searches these sources for contracts that suit you.",
            'group' => 'basics',
            'pages' => ['faq', 'opportunity-identification'],
        ],

        // ---------- Our services ----------
        [ // PROVISIONAL (P27)
            'id'    => 'what-services',
            'q'     => 'What services does My Solutionz offer?',
            'a'     => "We work across three areas:\n\n- **[Bid & Proposal Management](/services/bid-proposal-management/):** tender and bid writing, opportunity identification and expert review of your drafts.\n- **[Compliance & Virtual Assistance](/services/compliance-virtual-assistance/):** CQC registration, policy management, Carbon Reduction Plans, data entry and management, and care plans.\n- **[Digital Presence](/services/digital-presence/):** website development, social media management, email marketing, and SEO and analytics.\n\nYou can use a single service or combine them, for example a Carbon Reduction Plan and a refreshed website alongside a tender.",
            'group' => 'services',
            'pages' => ['faq'],
        ],
        [ // PROVISIONAL
            'id'    => 'never-tendered-before',
            'q'     => 'Can you help if we have never tendered before?',
            'a'     => 'Yes. We explain each tender document, help you register on the relevant portals, gather the policies and evidence buyers usually ask for, and guide you through your first submission step by step.',
            'group' => 'services',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing', 'services'],
        ],
        [ // PROVISIONAL (P20)
            'id'    => 'private-sector',
            'q'     => 'Do you support private sector tenders and proposals as well as public sector bids?',
            'a'     => 'Yes. We support public sector tenders, framework applications and private sector proposals and requests for proposal (RFPs).',
            'group' => 'services',
            'pages' => ['faq', 'bid-proposal-management', 'tender-bid-writing'],
        ],
        [ // PROVISIONAL (P27: current site FAQ 4, rewritten)
            'id'    => 'strengths',
            'q'     => 'How do you make sure our strengths stand out in a tender?',
            'a'     => "We start by learning what makes your organisation different: your track record, your people, your quality systems and the results you achieve for clients. Then we map those strengths to the buyer's specification and scoring criteria, so every answer shows how you meet a stated requirement, backed by evidence such as case examples, data and accreditations.",
            'group' => 'services',
            'pages' => ['faq', 'tender-bid-writing', 'expert-review'],
        ],

        // ---------- Pricing & process ----------
        [ // PROVISIONAL (P27: merged with current site FAQ 3)
            'id'    => 'cost',
            'q'     => 'How much do your services cost?',
            'a'     => 'Every tender is different, so we quote per project. Our pricing depends on the scope and complexity of the tender, the number of questions, the research and preparation needed, the deadline and any extra services you ask for. Contact us for a free, no-obligation quote.',
            'group' => 'pricing-process',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'compliance-virtual-assistance', 'digital-presence', 'services'],
        ],
        [ // PROVISIONAL (P20)
            'id'    => 'notice-before-deadline',
            'q'     => 'How much notice do you need before a tender deadline?',
            'a'     => 'The earlier the better. Ideally, contact us as soon as the tender is published so there is time for analysis, evidence gathering and review. We can sometimes support shorter deadlines depending on the scope, so get in touch as soon as possible.',
            'group' => 'pricing-process',
            'pages' => ['faq', 'bid-proposal-management', 'tender-bid-writing', 'expert-review'],
        ],
        [ // PROVISIONAL (P27: merged with current site FAQ 1)
            'id'    => 'confidentiality',
            'q'     => 'How do you keep our information confidential?',
            'a'     => 'We take confidentiality and data security seriously. Access to your documents is limited to the people working on your bid, and we use secure methods to share files.',
            'group' => 'pricing-process',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'compliance-virtual-assistance', 'services'],
        ],
        [ // PROVISIONAL (P27: merged with current site FAQ 2)
            'id'    => 'after-submission',
            'q'     => 'Can you help after we have submitted a tender?',
            'a'     => 'Yes. We can help you answer clarification questions from the buyer, prepare for any presentation or negotiation stage, and review the feedback you receive, so the lessons carry into your next bid.',
            'group' => 'pricing-process',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing', 'expert-review'],
        ],
        [ // PROVISIONAL
            'id'    => 'guarantee',
            'q'     => 'Can you guarantee that we will win the contract?',
            'a'     => 'No one can honestly guarantee a contract award: the decision rests with the buyer and depends on price, competition and the service you offer. We make sure your submission is compliant, well evidenced and written to score as strongly as possible.',
            'group' => 'pricing-process',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing'],
        ],
        [ // PROVISIONAL (P23: the contact page's mini FAQ; the response time is for the client to confirm)
            'id'    => 'response-time',
            'q'     => 'How quickly will you respond?',
            'a'     => '[[PENDING: response time]]',
            'group' => 'pricing-process',
            'pages' => ['contact', 'faq'],
        ],

        // ---------- Sectors ----------
        [ // PROVISIONAL (P27)
            'id'    => 'which-sectors',
            'q'     => 'Which sectors do you work in?',
            'a'     => "We support businesses bidding in seven sectors: healthcare and social care, transport, cleaning, education and training, security, facilities management and building maintenance. See [the sectors we work in](/sectors/) for the contracts we help with in each.\n\nIf your sector is not listed, get in touch: the principles of a strong bid are the same, and we will tell you honestly whether we are the right fit.",
            'group' => 'sectors',
            'pages' => ['faq'],
        ],
        [ // PROVISIONAL (P27; matches the CQC Registration page's steps)
            'id'    => 'cqc-registration',
            'q'     => 'Do you support CQC registration for new care providers?',
            'a'     => "Yes. We help new health and social care providers in England prepare to register with the Care Quality Commission (CQC). We guide you through the process, help you prepare the documents CQC asks for, review your application forms before you submit them and support you with compliance afterwards. The registration decision rests with CQC.\n\nSee [CQC registration](/services/compliance-virtual-assistance/cqc-registration/) for how it works.",
            'group' => 'sectors',
            'pages' => ['faq', 'cqc-registration'],
        ],
    ],
];
