<?php
declare(strict_types=1);

/**
 * Expert Review, sub-service page /services/bid-proposal-management/expert-review/ (PROMPTS P21, docs/08 §7b).
 * Copy: docs/website_content/services/01c-expert-review.md = the client's final copy (15 October 2026), verbatim and
 * in its order. Experience is always "over 10 years" (docs/00 L05c).
 */
return [
    'status' => 'published',

    'seo' => [
        'title'          => 'Expert Tender Review Service UK | My Solutionz',
        'description'    => 'An independent expert review of your tender before you submit. We assess every response against the scoring criteria and give clear, practical recommendations.',
        'og_title'       => 'Expert Tender Review',
        'og_description' => "Expert eyes. Stronger bids. An independent assessment of your tender against the buyer's scoring criteria before you submit.",
        'og_image'       => '/assets/og/expert-review.jpg',
        'og_image_alt'   => 'Bid reviewer going through a draft tender response with a client team',
        'service_type'   => 'Tender and bid review',
    ],

    'hero' => [
        'title'  => 'Expert',
        'accent' => 'Review',
        'sub'    => 'Expert Eyes. Stronger Bids. Better Opportunities to Compete.',
        'lead'   => 'Even a well-written tender can lose valuable marks if it overlooks evaluation requirements, lacks supporting evidence or fails to demonstrate the level of detail expected by the buyer. Before submitting an important bid, having an experienced professional review your responses can make a significant difference to their overall quality.',
        'ctas'   => [
            ['Get Your Tender Expertly Reviewed', '/contact-us/?service=expert-review#quote'],
            ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone'],
        ],
        'images' => [
            ['key' => 'services/digital-presence-web-designer', 'alt' => 'Reviewer working through a draft on a laptop and a phone'],
            ['key' => 'home/hero-building', 'alt' => '', 'focus' => 'right'],
            ['key' => 'sectors/facilities-manager-plant-room', 'alt' => ''],
        ],
    ],

    'sections' => [
        [
            'type'  => 'intro',
            'paras' => [
                'At **My Solutionz**, we provide expert tender and bid review services to businesses competing for public and private sector contracts across the UK. Our specialists carefully assess your prepared submissions against the tender requirements, evaluation criteria and scoring methodology, identifying weaknesses and recommending practical improvements.',
                'With over **10 years of business development experience**, we bring commercial understanding, procurement knowledge and a fresh perspective to your submissions. Our objective is to help you present stronger, clearer and more competitive bids before they reach the evaluator.',
            ],
        ],
        [
            'type'    => 'text',
            'id'      => 'why-review-matters',
            'heading' => 'Why Is an Expert',
            'accent'  => 'Tender Review Important?',
            'sub'     => 'Identify Weaknesses Before the Evaluator Does',
            'paras'   => [
                "Preparing a tender requires considerable effort, but completing every question does not necessarily mean the responses meet the buyer's expectations.",
                'A submission may contain strong operational information yet overlook specific scoring requirements, provide insufficient evidence or fail to explain how proposed commitments will be delivered. These gaps can be difficult to recognise when you have been closely involved in writing the bid.',
                "Our expert tender review service provides an independent assessment of your submission, examining how effectively each response addresses the question and demonstrates your organisation's capabilities.",
                'We look beyond grammar and presentation to assess the quality, relevance and credibility of your answers, helping you identify where additional detail, stronger evidence or a different approach could improve your submission.',
            ],
            'image'   => ['key' => 'home/tender-consultancy-meeting', 'alt' => 'Reviewer discussing a draft tender response with a client team'],
        ],
        [
            'type'    => 'list',
            'id'      => 'what-the-review-covers',
            'heading' => 'What Does Our Expert',
            'accent'  => 'Tender Review Cover?',
            'sub'     => "A Detailed Assessment Against the Buyer's Requirements",
            'paras'   => ['Our review focuses on the areas that influence how your tender is understood and evaluated.'],
            'items'   => [
                ['icon' => 'target', 'title' => 'Evaluation Criteria & Scoring Alignment', 'text' => 'We examine each response against the published evaluation criteria and scoring descriptors, identifying missing elements and opportunities to strengthen the answer.'],
                ['icon' => 'seal-check', 'title' => 'Technical & Quality Compliance', 'text' => 'We assess whether your responses address the relevant service specifications, contractual expectations and technical requirements.'],
                ['icon' => 'files', 'title' => 'Evidence & Supporting Information', 'text' => 'We identify unsupported claims, weak explanations and areas where relevant experience, operational examples or measurable commitments could strengthen credibility.'],
                ['icon' => 'article', 'title' => 'Clarity, Structure & Relevance', 'text' => 'We review how clearly your proposed approach is communicated, identifying unnecessary information, repetition and sections that need greater focus.'],
                ['icon' => 'list-checks', 'title' => 'Consistency & Submission Requirements', 'text' => 'We check for conflicting statements, terminology issues, word-limit compliance and relevant formatting requirements within the agreed review scope.'],
            ],
            'outro'   => "Our recommendations are tailored to the individual tender, helping you concentrate on improvements that are most relevant to the buyer's expectations.",
        ],
        [
            'type'    => 'steps',
            'id'      => 'how-the-review-works',
            'heading' => 'How Our Expert Tender Review',
            'accent'  => 'Process Works',
            'sub'     => 'A Professional Assessment with Clear, Actionable Recommendations',
            'variant' => 'full',
            'steps'   => [
                ['title' => 'Understanding the Tender Requirements', 'icon' => 'magnifying-glass', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'home/hero-building',
                 'text' => 'We begin by examining the Invitation to Tender (ITT), service specification, evaluation questions and scoring methodology to establish what the contracting authority expects from bidders.'],
                ['title' => 'Reviewing Your Prepared Responses', 'icon' => 'file-magnifying-glass', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => 'Our specialists assess your draft answers individually, comparing them with the question requirements, available evaluation guidance and supporting information. We identify strengths, omissions, inconsistencies and areas requiring greater operational detail.'],
                ['title' => 'Identifying Scoring Gaps & Improvement Areas', 'icon' => 'crosshair', 'image' => 'sectors/facilities-manager-plant-room', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => 'We consider how effectively each response demonstrates the required capabilities and where weaknesses could affect its evaluation. Particular attention is given to incomplete explanations, unsupported commitments and opportunities to present stronger evidence.'],
                ['title' => 'Providing Expert Recommendations', 'icon' => 'chat-centered-text', 'image' => 'sectors/education-adult-training-workshop', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => 'We provide clear, practical feedback explaining what needs improvement, why it matters and how the response could be strengthened. Recommendations may include restructuring content, expanding technical explanations, adding relevant evidence or clarifying delivery commitments.'],
                ['title' => 'Supporting Final Improvements', 'icon' => 'check-circle', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'sectors/education-adult-training-workshop',
                 'text' => 'We discuss our findings with your team and help you prioritise the recommended amendments before submission. Where additional assistance is required, our professional [Tender & Bid Writing](/services/bid-proposal-management/tender-bid-writing/) service can support the revision of selected responses.'],
            ],
        ],
        [
            'type'       => 'text',
            'id'         => 'experienced-tender-reviewers',
            'heading'    => 'The Value of Experienced',
            'accent'     => 'Tender Reviewers',
            'sub'        => "Procurement Knowledge That Helps You See Your Bid Through the Evaluator's Eyes",
            'paras'      => [
                'An effective tender review requires more than strong editing skills. It demands an understanding of procurement practices, buyer expectations, evaluation methodologies and the level of evidence needed to demonstrate contractual capability.',
                'At My Solutionz, our approach is backed by over **10 years of business development experience** and practical knowledge of procurement across multiple industries.',
                'Our familiarity with tender requirements commonly used by local authorities, NHS organisations, government departments, housing associations and other contracting bodies helps us recognise recurring evaluation priorities and common weaknesses in bid responses.',
                'We understand that buyers assess tenders differently depending on the contract, sector and procurement procedure. Our reviewers therefore work from the specific published scoring criteria rather than assuming that every contracting authority applies the same standards.',
                'This combination of experience and contract-specific assessment allows us to provide informed recommendations that go beyond surface-level editing and concentrate on the substance of your submission.',
            ],
            'read_along' => 3,   // paragraph 4 (design table)
            'variant'    => 'mist',
        ],
        [
            'type'    => 'why',
            'id'      => 'why-choose-us',
            'heading' => 'Why Choose My Solutionz for',
            'accent'  => 'Expert Tender Review?',
            'sub'     => 'An Independent Perspective Before You Make Your Final Submission',
            'paras'   => ['Our expert review service is particularly valuable for organisations with in-house bid writers, management teams preparing their own tenders or businesses seeking additional professional assurance before an important submission.'],
            'lead_in' => 'Working with My Solutionz allows you to:',
            'items'   => [
                ['icon' => 'eye', 'strong' => 'Identify overlooked requirements', 'text' => 'before your tender is submitted.'],
                ['icon' => 'trend-up', 'strong' => 'Strengthen weaker responses', 'text' => 'through targeted, practical recommendations.'],
                ['icon' => 'target', 'strong' => 'Improve alignment with scoring criteria', 'text' => 'by addressing the information evaluators are looking for.'],
                ['icon' => 'users-three', 'strong' => 'Benefit from experienced reviewers', 'text' => 'with commercial insight and multi-sector procurement knowledge.'],
                ['icon' => 'hourglass', 'strong' => 'Make better use of your remaining preparation time', 'text' => 'by concentrating on the amendments that matter most.'],
            ],
            'outro'   => 'We offer flexible support, whether you need selected quality questions reviewed or a comprehensive assessment of your written tender submission.',
        ],
    ],

    // Siblings + 1 cross-category link (the doc suggests Policy Management)
    'related' => ['bid-proposal-management/tender-bid-writing', 'bid-proposal-management/opportunity-identification', 'compliance-virtual-assistance/policy-management'],

    'faq_tag' => 'expert-review',
    'faq'     => [
        'eyebrow' => 'FAQs',
        'heading' => 'Expert Review',
        'accent'  => 'FAQs',
        'text'    => 'Straight answers to the questions businesses ask most about an expert tender review.', // PROVISIONAL
    ],

    'cta' => [
        'heading' => 'Give Your Tender a',
        'accent'  => 'Professional Second Opinion',
        'sub'     => 'Before You Submit, Make Every Response Count.',
        'paras'   => [
            'Your team may have invested considerable time and resources in preparing a tender. An expert review offers an opportunity to identify weaknesses, improve the quality of your answers and approach the final submission with greater confidence.',
            'At **My Solutionz**, we help businesses strengthen their bids through detailed evaluation, experienced professional judgement and practical recommendations tailored to the contract.',
            'Whether your tender is approaching its deadline or you want an independent assessment before finalising your responses, our experts are ready to help.',
        ],
        'label'   => 'Get Your Tender Expertly Reviewed',
    ],
];
