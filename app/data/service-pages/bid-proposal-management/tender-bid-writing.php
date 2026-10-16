<?php
declare(strict_types=1);

/**
 * Tender & Bid Writing, sub-service page /services/bid-proposal-management/tender-bid-writing/ (PROMPTS P21,
 * docs/08 §7b). Copy: docs/website_content/services/01a-tender-bid-writing.md = the client's final copy
 * (15 October 2026), verbatim and in its order. Conventions as in ../bid-proposal-management.php (`**bold**`,
 * `[label](/path/)` via rich(), `accent`, `sub`). Experience is always "over 10 years" (docs/00 L05c).
 * Blocks: templates/sections/service/<type>.php; related services, FAQ (by `faq_tag`) and the CTA band close the page.
 */
return [
    'status' => 'published',

    'seo' => [
        'title'          => 'Tender & Bid Writing Services UK | My Solutionz',
        'description'    => 'Professional tender and bid writing for UK businesses: quality responses, method statements, social value and mobilisation plans, built on your evidence.',
        'og_title'       => 'Tender & Bid Writing Services',
        'og_description' => 'Well-structured, compliant and evidence-based tender submissions that present a compelling case for your business.',
        'og_image'       => '/assets/og/tender-bid-writing.jpg',
        'og_image_alt'   => 'Tender writer talking a client team through a response plan',
        'service_type'   => 'Tender writing',
    ],

    'hero' => [
        'title'  => 'Tender & Bid Writing',
        'accent' => 'Services',
        'sub'    => 'Professional Tender Writing That Gives Your Business a Competitive Advantage',
        'lead'   => "A successful tender submission requires more than relevant experience and technical expertise. It requires clear, convincing responses that demonstrate your organisation's capabilities and address the contracting authority's expectations.",
        'ctas'   => [
            ['Get a Free Quote', '/contact-us/?service=tender-bid-writing#quote'],
            ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone'],
        ],
        'images' => [
            ['key' => 'home/tender-consultancy-meeting', 'alt' => 'Tender writer drafting a quality response with a client team'],
            ['key' => 'home/hero-building', 'alt' => '', 'focus' => 'right'],
            ['key' => 'sectors/education-adult-training-workshop', 'alt' => ''],
        ],
    ],

    'sections' => [
        [
            'type'  => 'intro',
            'paras' => [
                'At **My Solutionz**, we provide professional tender and bid writing services for businesses competing for public and private sector contracts across the UK. Backed by over **10 years of business development experience**, we combine commercial understanding, industry knowledge and specialist writing expertise to develop well-structured, compliant and evidence-based tender submissions.',
                'Whether you are bidding for your first contract, applying for a framework agreement or competing for a major procurement opportunity, our experienced tender writers help you communicate your strengths effectively and present a compelling case for your business.',
            ],
        ],
        [
            'type'    => 'text',
            'id'      => 'what-is-tender-writing',
            'heading' => 'What Is Professional',
            'accent'  => 'Tender & Bid Writing?',
            'sub'     => 'Turning Your Business Expertise into Competitive Tender Responses',
            'paras'   => [
                'Tender writing involves preparing detailed responses to the technical, operational and quality requirements specified by contracting authorities and private sector buyers. Each response must demonstrate how your organisation meets the requirements of the contract, supported by relevant experience, delivery arrangements and credible evidence.',
                'A capable business can lose valuable evaluation marks if its responses lack detail, overlook important requirements or fail to explain how proposed services will be delivered.',
                'Our professional tender writers bridge this gap by transforming your operational knowledge into persuasive, contract-specific responses. We focus on what evaluators are assessing, ensuring your answers demonstrate not only what your business can offer, but how it intends to fulfil its commitments.',
            ],
            'image'   => ['key' => 'sectors/education-adult-training-workshop', 'alt' => 'Consultant talking a small team through a plan on a board'],
        ],
        [
            'type'    => 'list',
            'id'      => 'our-tender-writing-services',
            'heading' => 'Our Tender &',
            'accent'  => 'Bid Writing Services',
            'sub'     => 'Comprehensive Writing Support for Different Tender Requirements',
            'paras'   => ['We provide specialist writing support across the main elements of public and private sector tender submissions:'],
            'items'   => [
                ['icon' => 'seal-check', 'title' => 'Quality & Technical Responses', 'text' => 'Detailed answers addressing service delivery, quality assurance, workforce management, compliance and performance monitoring.'],
                ['icon' => 'list-numbers', 'title' => 'Method Statements', 'text' => 'Clear explanations of proposed working methods, operational procedures, resources, responsibilities and contract delivery arrangements.'],
                ['icon' => 'hand-heart', 'title' => 'Social Value Responses', 'text' => 'Practical commitments covering local employment, skills development, community benefits, sustainability and measurable social value outcomes.'],
                ['icon' => 'rocket-launch', 'title' => 'Mobilisation & Implementation Plans', 'text' => 'Structured proposals outlining contract commencement, staffing, operational readiness, transition arrangements and implementation milestones.'],
                ['icon' => 'shield-warning', 'title' => 'Risk Management & Business Continuity', 'text' => 'Responses explaining how operational risks, service disruptions and contractual challenges will be managed.'],
                ['icon' => 'folder-open', 'title' => 'Case Studies & Framework Applications', 'text' => 'Evidence-based descriptions of previous contract experience, organisational capabilities and relevant achievements.'],
            ],
            'outro'   => "Each response is developed around your business, the contract specification and the buyer's evaluation criteria.",
        ],
        [
            'type'    => 'steps',
            'id'      => 'our-tender-writing-process',
            'heading' => 'Our Tender',
            'accent'  => 'Writing Process',
            'sub'     => 'A Structured Approach to Producing High-Quality Tender Responses',
            'paras'   => ['We follow a practical writing process that combines thorough preparation, collaboration and quality control.'],
            'variant' => 'full',
            'steps'   => [
                ['title' => 'Tender Analysis & Requirements Review', 'icon' => 'magnifying-glass', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'home/hero-building',
                 'text' => 'We examine the Invitation to Tender (ITT), service specification, evaluation questions and scoring criteria to identify exactly what the buyer expects and the information needed for each response.'],
                ['title' => 'Business Consultation & Evidence Gathering', 'icon' => 'chats-circle', 'image' => 'sectors/education-adult-training-workshop', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => 'We work with your management and operational teams to understand your services, experience, workforce, systems and delivery arrangements. We collect relevant supporting evidence and identify any information requiring further clarification.'],
                ['title' => 'Professional Tender Drafting', 'icon' => 'pen-nib', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'sectors/healthcare-social-care-consultation',
                 'text' => "Our tender writers develop tailored, well-structured responses that address each evaluation requirement. We focus on practical delivery methods, relevant evidence, measurable commitments and clear explanations of your organisation's capabilities."],
                ['title' => 'Quality Review & Refinement', 'icon' => 'list-checks', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => 'We assess the drafted responses against the tender questions, scoring guidance and submission instructions. We review completeness, technical accuracy, supporting evidence, consistency and word limits, refining the content where improvements are needed.'],
                ['title' => 'Client Approval & Finalisation', 'icon' => 'check-circle', 'image' => 'sectors/education-adult-training-workshop', 'detail_image' => 'home/hero-building',
                 'text' => "We incorporate your feedback and finalise the written responses, checking that operational commitments accurately reflect your organisation's capabilities and that the content is ready for inclusion in the tender submission."],
            ],
        ],
        [
            'type'    => 'text',
            'id'      => 'experienced-tender-writers',
            'heading' => 'The Advantage of',
            'accent'  => 'Experienced Tender Writers',
            'sub'     => 'Business Development Experience That Goes Beyond Writing',
            'paras'   => [
                'Professional tender writing requires more than excellent communication skills. It demands commercial awareness, knowledge of procurement requirements and an understanding of how businesses demonstrate their ability to deliver contracts.',
                'With over **10 years of business development experience**, My Solutionz brings practical commercial insight to the tender writing process. Our writers understand how to interpret complex evaluation questions, identify relevant evidence and present technical information in a way that is clear, credible and relevant to the buyer.',
                'We recognise that different industries have different procurement expectations. Our experience across [healthcare and social care](/sectors/healthcare-social-care/), [transport](/sectors/transport/), [cleaning](/sectors/cleaning/), [security](/sectors/security/), [education and training](/sectors/education-training/), [facilities management](/sectors/facilities-management/) and [building maintenance](/sectors/building-maintenance/) allows us to develop responses that reflect sector-specific operational requirements.',
                "Rather than relying on generic templates or exaggerated claims, we focus on producing submissions built around your organisation's genuine experience, resources and strengths.",
            ],
            'variant' => 'mist',
        ],
        [
            'type'    => 'why',
            'id'      => 'why-choose-us',
            'heading' => 'Why Choose My Solutionz',
            'accent'  => 'for Tender Writing?',
            'sub'     => 'Specialist Expertise Without the Pressure of Writing Everything Yourself',
            'paras'   => [
                'Preparing a competitive tender can place considerable demands on business owners and management teams, particularly when submissions involve extensive technical questions and demanding deadlines.',
                'Our outsourced tender writing services provide professional support without the need to employ a permanent in-house bid writer.',
            ],
            'lead_in' => 'We help you:',
            'items'   => [
                ['icon' => 'clock', 'strong' => 'Save valuable time', 'text' => 'by taking responsibility for preparing and refining written responses.'],
                ['icon' => 'trend-up', 'strong' => 'Strengthen submission quality', 'text' => 'through clear explanations, relevant evidence and close attention to evaluation criteria.'],
                ['icon' => 'users-three', 'strong' => 'Access specialist expertise', 'text' => 'supported by commercial understanding and multi-sector experience.'],
                ['icon' => 'sliders-horizontal', 'strong' => 'Receive flexible assistance', 'text' => ', whether you need a complete written submission or support with selected tender questions.'],
            ],
            'outro'   => 'We work closely with your team throughout the assignment, allowing you to concentrate on running your business while we develop the agreed tender responses.',
        ],
    ],

    // Siblings + 1 cross-category link (the doc suggests Policy Management)
    'related' => ['bid-proposal-management/opportunity-identification', 'bid-proposal-management/expert-review', 'compliance-virtual-assistance/policy-management'],

    'faq_tag' => 'tender-bid-writing',
    'faq'     => [
        'eyebrow' => 'FAQs',
        'heading' => 'Tender & Bid Writing',
        'accent'  => 'FAQs',
        'text'    => 'Straight answers to the questions businesses ask most about tender and bid writing.', // PROVISIONAL
    ],

    'cta' => [
        'heading' => "Let's Make Your",
        'accent'  => 'Next Tender Count',
        'sub'     => 'Your Business Has the Capabilities. Let Us Help You Present Them.',
        'paras'   => [
            "Every tender presents an opportunity to demonstrate your expertise and compete for valuable contracts. At My Solutionz, we help businesses make the most of these opportunities through professional, evidence-based tender writing that reflects their strengths and addresses the buyer's requirements.",
            'Whether you are preparing your first tender or pursuing a major contract opportunity, our team is ready to help you develop a stronger submission.',
        ],
        'label'   => 'Get a Free Quote',   // site default: the client gave no CTA label for this page
    ],
];
