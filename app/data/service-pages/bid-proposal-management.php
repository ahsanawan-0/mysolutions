<?php
declare(strict_types=1);

/**
 * Bid & Proposal Management, category landing page /services/bid-proposal-management/ (PROMPTS P20, docs/08 §7a).
 * Copy: docs/website_content/services/01-bid-proposal-management.md = the client's final copy (15 October 2026),
 * verbatim and in its order. Conventions: `**bold**` = the client's own emphasis, `[label](/path/)` = an internal link
 * we added on the client's own words (both rendered by rich()); `accent` = the two-voice heading's blue words;
 * `sub` = the client's tagline under a heading. Experience is always "over 10 years" (docs/00 L05c).
 *
 * `sections` is an ordered list of typed blocks (templates/sections/service/<type>.php, shared with the sub-service
 * pages in P21): intro · text (variant default|mist|band) · steps · list · why · subs. The FAQ (faqs.php by
 * `faq_tag`) and the closing CTA band always close the page. Items marked // PROVISIONAL are ours (gaps in the
 * client copy) and await the client's approval (docs/15 Q06c).
 */
return [
    'status' => 'published',

    'seo' => [
        'title'          => 'Bid & Proposal Management Services UK | My Solutionz',
        'description'    => 'Bid and proposal management for UK businesses. Tender writing, opportunity identification and expert review, from strategy to submission. Get a free quote.',
        'og_title'       => 'Bid & Proposal Management Services',
        'og_description' => 'From finding the right contracts to final submission, we manage your tenders so your team can focus on running the business.',
        'og_image'       => '/assets/og/bid-proposal-management.jpg',   // scripts/og.mjs (photographic 1200×630 crop)
        'og_image_alt'   => 'Bid manager planning a tender response with a client team',
        'service_type'   => 'Bid management',
    ],

    'hero' => [
        'eyebrow' => 'Our Services',
        'title'   => 'Bid &',
        'accent'  => 'Proposal Management',
        'sub'     => 'Strategic Bid Management to Help Your Business Compete and Grow',
        'lead'    => 'Securing valuable contracts requires more than preparing a convincing proposal. It demands careful planning, a clear understanding of procurement requirements and effective coordination throughout the bidding process. Without the right expertise and resources, even experienced businesses can struggle to present their capabilities effectively within demanding submission deadlines.',
        'ctas'    => [
            ['Get a Free Quote', '/contact-us/?service=bid-proposal-management#quote'],
            ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone'],
        ],
        // Assembled from approved facts (docs/00 L05c, client sectors)
        'trust'   => 'Over 10 years of business development experience · Public and private sector bids · Healthcare, transport, cleaning, security, education and facilities management',
        'icon'    => 'kanban',
        'images'  => [
            ['key' => 'home/tender-consultancy-meeting', 'alt' => 'Bid manager planning a tender response with a client team'],
            ['key' => 'home/hero-building', 'alt' => '', 'focus' => 'right'],
            ['key' => 'sectors/education-adult-training-workshop', 'alt' => ''],
        ],
        'labels'  => [
            ['text' => 'Bid Strategy', 'icon' => 'strategy'],
            ['text' => 'Compliance', 'icon' => 'seal-check'],
            ['text' => 'Evidence-Led Writing', 'icon' => 'pen-nib'],
        ],
    ],

    'sections' => [
        [
            'type'  => 'intro',
            'paras' => [
                'At **My Solutionz**, we provide professional **bid and proposal management services** to businesses competing for public and private sector contracts across the UK. We combine commercial understanding, sector knowledge and practical bidding expertise to help organisations prepare well-structured, compliant and competitive submissions.',
                'Whether you are pursuing your first public sector contract, applying for a framework agreement or managing several tender opportunities, we provide the professional support needed to approach each bid with confidence.',
            ],
        ],
        [
            'type'    => 'text',
            'id'      => 'what-is-bid-management',
            'heading' => 'What Is',
            'accent'  => 'Bid & Proposal Management?',
            'sub'     => 'Managing the Complete Bidding Process with Clarity and Control',
            'paras'   => [
                "Bid and proposal management is the structured process of planning, coordinating and overseeing a business's response to a contract opportunity. It involves bringing together the people, information, resources and documentation needed to prepare a competitive proposal within the buyer's specified requirements and timescales.",
                "Effective bid management considers both the technical and commercial aspects of a submission. It requires an understanding of the buyer's objectives, the contract specification, evaluation methodology, financial considerations, compliance obligations and the organisation's capacity to deliver the proposed services.",
                'A proposal may involve contributions from directors, operational managers, finance teams, HR professionals and other specialists. Without proper coordination, important information can be overlooked, responses can become inconsistent and valuable time may be lost resolving issues close to the submission deadline.',
                'Professional bid management brings these activities together under a defined plan, with clear responsibilities, agreed timescales and regular checks on progress.',
                'At My Solutionz, we take a coordinated approach that connects your business knowledge with the requirements of the tender. We help organise the preparation of your submission, address information gaps and bring consistency to the evidence and commitments presented to the buyer.',
                'Our objective is to help you submit a bid that demonstrates not only what your organisation offers, but how it intends to fulfil the contract and deliver the expected outcomes.',
            ],
            'image'   => ['key' => 'sectors/education-adult-training-workshop', 'alt' => 'Consultant talking a small team through a plan on a board'],
            'variant' => 'default',
        ],
        [
            'type'    => 'steps',
            'id'      => 'how-we-manage-your-bids',
            'heading' => 'How We',
            'accent'  => 'Manage Your Bids',
            'sub'     => 'A Structured Process from Initial Assessment to Final Submission',
            'paras'   => [
                'Every procurement opportunity has its own requirements, deadlines and level of complexity. We adapt our bid management process accordingly, while keeping the essential stages of preparation, coordination and quality control consistent. At My Solutionz, we take a structured, collaborative approach to managing tender submissions. We begin by understanding your business and assessing the contract requirements, evaluation criteria and potential challenges.',
                "We then establish a clear bid plan, coordinate supporting information, manage documentation and oversee the preparation of responses. Throughout the process, we monitor deadlines, address information gaps and review the submission against the buyer's requirements.",
                'Our involvement allows your team to focus on operational priorities while we manage the agreed bidding activities, keeping you informed and involved in important decisions.',
            ],
            // Step titles = client copy; the one-line descriptions are ours, built from the client's own sentences
            'steps'   => [
                ['title' => 'Understanding the Opportunity and Its Requirements', 'icon' => 'magnifying-glass', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'home/hero-building',
                 'text' => 'We get to know your business and assess the contract requirements, evaluation criteria and potential challenges.'], // PROVISIONAL
                ['title' => 'Establishing a Clear Bid Plan', 'icon' => 'strategy', 'image' => 'sectors/education-adult-training-workshop', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => 'We agree a bid plan with clear responsibilities, agreed timescales and regular checks on progress.'], // PROVISIONAL
                ['title' => 'Coordinating Information and Supporting Evidence', 'icon' => 'files', 'image' => 'sectors/healthcare-social-care-consultation', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => 'We coordinate the information, documents and evidence your submission needs and address any gaps early.'], // PROVISIONAL
                ['title' => 'Aligning the Proposal with the Contract', 'icon' => 'target', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'sectors/education-adult-training-workshop',
                 'text' => 'We shape each response around the specification, the evaluation criteria and what your organisation can genuinely deliver.'], // PROVISIONAL
                ['title' => 'Managing Compliance, Quality and Deadlines', 'icon' => 'calendar-check', 'image' => 'sectors/facilities-manager-plant-room', 'detail_image' => 'home/hero-building',
                 'text' => "We monitor every deadline and review the submission against the buyer's requirements before it is finalised."], // PROVISIONAL
                ['title' => 'Preparing for Submission and Next Steps', 'icon' => 'paper-plane-tilt', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => 'We bring the final submission together and agree the next steps with you, keeping you involved in important decisions.'], // PROVISIONAL
            ],
        ],
        [
            'type'     => 'subs',
            'id'       => 'our-services',
            'heading'  => 'Our Bid & Proposal',   // PROVISIONAL (the client copy has no section for the sub-service cards)
            'accent'   => 'Management Services',
            'intro'    => 'Choose the support you need, or combine all three for end-to-end bid management.', // PROVISIONAL
            // Card text = the client's tagline on each sub-service page (01a–01c)
            'taglines' => [
                'tender-bid-writing'         => 'Professional Tender Writing That Gives Your Business a Competitive Advantage',
                'opportunity-identification' => 'Find the Right Opportunities. Compete Where Your Business Has the Strongest Potential.',
                'expert-review'              => 'Expert Eyes. Stronger Bids. Better Opportunities to Compete.',
            ],
        ],
        [
            'type'    => 'text',
            'id'      => 'why-professional-bid-management',
            'heading' => 'Why Choose',
            'accent'  => 'Professional Bid Management?',
            'paras'   => [
                'A competitive tender requires more than good writing. It demands consistency, relevant evidence, compliance and a clear understanding of what the buyer expects.',
                "Our **outsourced bid management services** help businesses reduce administrative pressure, improve submission quality and make better use of their internal resources. We identify potential issues early, strengthen coordination and help develop proposals that reflect your organisation's genuine capabilities.",
                'With experience across healthcare, transport, cleaning, education, security and facilities management, we understand the different challenges businesses face when competing for contracts.',
            ],
            'button'  => ['label' => 'More About Our Approach', 'url' => '/about-us/#our-approach'],
            'variant' => 'mist',
        ],
        [
            'type'    => 'text',
            'id'      => 'public-and-private-sector',
            'heading' => 'Bid Management for',
            'accent'  => 'Public and Private Sector Contracts',
            'sub'     => 'Understanding Different Buyers, Industries and Procurement Requirements',
            'paras'   => [
                'Public and private sector procurement can differ considerably in structure, evaluation methods, contractual obligations and submission procedures. An effective bid management process needs to account for these differences while responding to the specific requirements of each buyer.',
                'For public sector tenders, this includes understanding the relevant procurement rules, supplier eligibility requirements, evaluation criteria and applicable submission procedures.',
                "We work with businesses competing under the UK's current procurement arrangements, including opportunities governed by the Procurement Act 2023, as well as procurements subject to other applicable regulations or transitional requirements.",
                "For private sector proposals, we consider the buyer's commercial objectives, service expectations, supplier requirements and the structure of their selection process.",
                'Our experience spans industries including [healthcare and social care](/sectors/healthcare-social-care/), [transport](/sectors/transport/), [cleaning](/sectors/cleaning/), [education and training](/sectors/education-training/), [security](/sectors/security/), [facilities management](/sectors/facilities-management/) and [building maintenance](/sectors/building-maintenance/).',
                'Each sector requires an understanding of its operational and compliance requirements. We bring this awareness into the bid management process while working closely with clients to establish how their own services, people and resources meet the needs of the proposed contract.',
            ],
            'variant' => 'band',
            'updated' => '2026-10-15',   // visible "Last updated": the section refers to current UK procurement law
        ],
    ],

    'faq_tag' => 'bid-proposal-management',
    'faq'     => [
        'eyebrow' => 'FAQs',
        'heading' => 'Bid & Proposal Management',
        'accent'  => 'FAQs',
        'text'    => 'Straight answers to the questions businesses ask most about bid and proposal management.', // PROVISIONAL
    ],

    // Closing band: the client's heading, sub, paragraphs and bold line; button = the site default label
    'cta' => [
        'heading' => "Let's Build a Stronger Approach to",
        'accent'  => 'Your Next Bid',
        'sub'     => 'Your Next Opportunity Deserves the Right Preparation',
        'paras'   => [
            "Every tender represents an investment in your business's future. At My Solutionz, we take that responsibility seriously, providing the expertise, organisation and attention to detail needed to support your bidding activities.",
            'Whether you are preparing for an important contract opportunity or looking to improve how your business manages tenders, we are here to help you take the next step.',
        ],
        'strong'  => 'Speak to our team about your bid management requirements and discover how we can support your business.',
        'label'   => 'Get a Free Quote',
    ],
];
