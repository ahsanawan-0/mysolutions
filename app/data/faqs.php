<?php
declare(strict_types=1);

/**
 * Frequently asked questions (PROMPTS P15), one list feeding the home teaser, /faq/ (P27) and the service pages.
 * Read via faqs() (all), faqs('home') (items tagged with that page), faqs(null, 'pricing-process') (one group) and
 * faq_groups() (slug → label, in display order for /faq/).
 *
 * Each item: id (anchor on /faq/), q, a (plain text; the templates escape it), group (a key of 'groups'), pages
 * (tags: 'home', 'faq', a service category or sub-service slug from app/data/services.php, a sector slug …).
 * No FAQPage schema is required (docs/10 §6: FAQ rich results were retired on 7 May 2026); the answers are visible
 * on the page, which is what matters to users and AI answers.
 *
 * Every item is // PROVISIONAL until the client approves it (C06 replaces this file; docs/15 Q06c). The order follows
 * the Bid & Proposal Management FAQ in the client doc (P20); `services` tags the general questions on /services/.
 */
return [
    'groups' => [
        'basics'          => 'The basics',
        'services'        => 'Our services',
        'pricing-process' => 'Pricing and process',
        'sectors'         => 'Sectors',
    ],

    'items' => [
        [ // PROVISIONAL (P20, docs/website_content/services/01-bid-proposal-management.md §8)
            'id'    => 'bid-writing-vs-bid-management',
            'q'     => 'What is the difference between bid writing and bid management?',
            'a'     => 'Bid writing focuses on the written responses to the tender questions. Bid management covers the whole process around them: qualifying the opportunity, planning, coordinating contributors and evidence, compliance checks, reviews and submission. We provide either service, or both together.',
            'group' => 'services',
            'pages' => ['faq', 'services', 'bid-proposal-management', 'tender-bid-writing'],
        ],
        [ // PROVISIONAL
            'id'    => 'never-tendered-before',
            'q'     => 'Can you help if we have never tendered before?',
            'a'     => 'Yes. We explain each tender document, help you register on the relevant portals, gather the policies and evidence buyers usually ask for, and guide you through your first submission step by step.',
            'group' => 'basics',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing', 'services'],
        ],
        [ // PROVISIONAL
            'id'    => 'cost',
            'q'     => 'How much do your services cost?',
            'a'     => 'Every tender is different, so we quote per project based on the number and complexity of the questions, the deadline and the level of support you need. Contact us for a free, no-obligation quote.',
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
        [ // PROVISIONAL
            'id'    => 'guarantee',
            'q'     => 'Can you guarantee that we will win the contract?',
            'a'     => 'No one can honestly guarantee a contract award: the decision rests with the buyer and depends on price, competition and the service you offer. We make sure your submission is compliant, well evidenced and written to score as strongly as possible.',
            'group' => 'basics',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing'],
        ],
        [ // PROVISIONAL (P20)
            'id'    => 'private-sector',
            'q'     => 'Do you support private sector tenders and proposals as well as public sector bids?',
            'a'     => 'Yes. We support public sector tenders, framework applications and private sector proposals and requests for proposal (RFPs).',
            'group' => 'services',
            'pages' => ['faq', 'bid-proposal-management', 'tender-bid-writing'],
        ],
        [ // PROVISIONAL
            'id'    => 'confidentiality',
            'q'     => 'How do you keep our information confidential?',
            'a'     => 'We take confidentiality seriously. Access to your documents is limited to the people working on your bid, and we use secure methods to share files.',
            'group' => 'pricing-process',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'compliance-virtual-assistance', 'services'],
        ],
        [ // PROVISIONAL
            'id'    => 'after-submission',
            'q'     => 'Can you help after we have submitted a tender?',
            'a'     => 'Yes. We can help you respond to clarification questions from the buyer and review the feedback you receive, so lessons are carried into your next bid.',
            'group' => 'services',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing', 'expert-review'],
        ],
        [ // PROVISIONAL (P23: the contact page's mini FAQ; the response time is for the client to confirm)
            'id'    => 'response-time',
            'q'     => 'How quickly will you respond?',
            'a'     => '[[PENDING: response time]]',
            'group' => 'basics',
            'pages' => ['contact', 'faq'],
        ],
    ],
];
