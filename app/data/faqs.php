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
 * The five seed items are // PROVISIONAL until the client approves them (C06 replaces this file).
 */
return [
    'groups' => [
        'basics'          => 'The basics',
        'services'        => 'Our services',
        'pricing-process' => 'Pricing and process',
        'sectors'         => 'Sectors',
    ],

    'items' => [
        [ // PROVISIONAL
            'id'    => 'never-tendered-before',
            'q'     => 'Can you help if we have never tendered before?',
            'a'     => 'Yes. We explain each tender document, help you register on the relevant portals, gather the policies and evidence buyers usually ask for, and guide you through your first submission step by step.',
            'group' => 'basics',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing'],
        ],
        [ // PROVISIONAL
            'id'    => 'cost',
            'q'     => 'How much do your services cost?',
            'a'     => 'Every tender is different, so we quote per project based on the number and complexity of the questions, the deadline and the level of support you need. Contact us for a free, no-obligation quote.',
            'group' => 'pricing-process',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'compliance-virtual-assistance', 'digital-presence'],
        ],
        [ // PROVISIONAL
            'id'    => 'confidentiality',
            'q'     => 'How do you keep our information confidential?',
            'a'     => 'We take confidentiality seriously. Access to your documents is limited to the people working on your bid, and we use secure methods to share files.',
            'group' => 'pricing-process',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'compliance-virtual-assistance'],
        ],
        [ // PROVISIONAL
            'id'    => 'after-submission',
            'q'     => 'Can you help after we have submitted a tender?',
            'a'     => 'Yes. We can help you respond to clarification questions from the buyer and review the feedback you receive, so lessons are carried into your next bid.',
            'group' => 'services',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing', 'expert-review'],
        ],
        [ // PROVISIONAL
            'id'    => 'guarantee',
            'q'     => 'Can you guarantee that we will win the contract?',
            'a'     => 'No one can honestly guarantee a contract award: the decision rests with the buyer and depends on price, competition and the service you offer. We make sure your submission is compliant, well evidenced and written to score as strongly as possible.',
            'group' => 'basics',
            'pages' => ['home', 'faq', 'bid-proposal-management', 'tender-bid-writing'],
        ],
    ],
];
