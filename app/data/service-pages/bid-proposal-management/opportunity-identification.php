<?php
declare(strict_types=1);

/**
 * Opportunity Identification, sub-service page /services/bid-proposal-management/opportunity-identification/
 * (PROMPTS P21, docs/08 §7b). Copy: docs/website_content/services/01b-opportunity-identification.md = the client's
 * final copy (15 October 2026), verbatim and in its order (incl. the extra "Understanding Your Business Comes First"
 * and "Tender Compatibility Analysis" sections). Experience is always "over 10 years" (docs/00 L05c).
 * No FAQ is tagged for this page yet, so the template omits the FAQ block.
 */
return [
    'status' => 'published',

    'seo' => [
        'title'          => 'Tender Opportunity Identification UK | My Solutionz',
        'description'    => 'We find, screen and shortlist UK public and private sector tenders that fit your capabilities, so you can make informed bid/no-bid decisions.',
        'og_title'       => 'Tender Opportunity Identification',
        'og_description' => 'Find the right opportunities and compete where your business has the strongest potential.',
        'og_image'       => '/assets/og/opportunity-identification.jpg',
        'og_image_alt'   => 'Consultant reviewing opportunities on a laptop and a phone',
        'service_type'   => 'Tender search and opportunity identification',
    ],

    'hero' => [
        'title'  => 'Opportunity',
        'accent' => 'Identification',
        'sub'    => 'Find the Right Opportunities. Compete Where Your Business Has the Strongest Potential.',
        'lead'   => 'Not every tender opportunity is worth pursuing. A contract may appear attractive, but if its requirements exceed your resources, fall outside your expertise or do not align with your business objectives, preparing a bid could become an unnecessary investment of time and money.',
        'ctas'   => [
            ['Find My Next Tender Opportunity', '/contact-us/?service=opportunity-identification#quote'],
            ['Call ' . config('phone_display'), 'tel:' . config('phone_e164'), 'glass', 'phone'],
        ],
        'images' => [
            ['key' => 'sectors/education-adult-training-workshop', 'alt' => 'Consultant working through contract opportunities with a client team'],
            ['key' => 'home/hero-building', 'alt' => '', 'focus' => 'right'],
            ['key' => 'home/tender-consultancy-meeting', 'alt' => ''],
        ],
    ],

    'sections' => [
        [
            'type'  => 'intro',
            'paras' => [
                'At **My Solutionz**, we provide professional tender opportunity identification services to help UK businesses discover relevant public and private sector contracts that match their capabilities, experience and growth ambitions.',
                'We go beyond searching for available tenders. Our approach involves understanding your organisation, identifying suitable procurement opportunities and assessing their requirements before recommending which contracts deserve your attention.',
                'With over **10 years of business development experience**, we help you make informed bidding decisions and focus your resources on opportunities where your business has a stronger competitive position.',
            ],
        ],
        [
            'type'    => 'text',
            'id'      => 'understanding-your-business',
            'heading' => 'Understanding Your Business',
            'accent'  => 'Comes First',
            'sub'     => 'The Right Opportunity Starts with Knowing What Your Business Can Deliver',
            'paras'   => [
                'Before searching for contracts, we take time to understand your organisation and what makes it competitive.',
                'We consider your business size, industry experience, geographical coverage, workforce capacity, financial position, accreditations, previous contract performance and operational capabilities. Equally important are your commercial objectives, whether you want to enter the public sector market, expand into new locations, increase contract values or build a more consistent pipeline of opportunities.',
                'This understanding allows us to establish a tailored opportunity profile, identifying the types of contracts that fit your business rather than presenting every tender available within your industry.',
                'Our objective is straightforward: **to find opportunities that make commercial and operational sense for your organisation.**',
            ],
            'pull'    => 3,   // the closing bold sentence as a pull line with read-along (design table)
            'image'   => ['key' => 'home/tender-consultancy-meeting', 'alt' => "Consultant reviewing a client's documents with their team"],
        ],
        [
            'type'    => 'steps',
            'id'      => 'how-it-works',
            'heading' => 'How Our Tender Opportunity',
            'accent'  => 'Identification Works',
            'sub'     => 'A Targeted Search and Assessment Process',
            'paras'   => ['We follow a structured approach to finding, reviewing and shortlisting relevant contract opportunities.'],
            'variant' => 'full',
            'steps'   => [
                ['title' => 'Business Profiling & Opportunity Criteria', 'icon' => 'user-focus', 'image' => 'sectors/education-adult-training-workshop', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => "We begin by reviewing your organisation's services, strengths, resources and growth plans. From this information, we establish suitable contract categories, geographical preferences, contract values, service requirements and other relevant selection criteria."],
                ['title' => 'Targeted Tender Opportunity Searches', 'icon' => 'binoculars', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'home/hero-building',
                 'text' => 'We search relevant UK procurement portals, public sector contract notices, framework opportunities and other appropriate tender sources to identify contracts matching your business profile.',
                 'detail' => ['Our searches focus on opportunities relevant to your industry, delivery capabilities and preferred markets, rather than producing lengthy lists of unrelated tenders.']],
                ['title' => 'Tender Screening & Requirement Analysis', 'icon' => 'file-magnifying-glass', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'sectors/healthcare-social-care-consultation',
                 'text' => 'Finding an opportunity is only the first step. We examine the available tender notice and procurement documentation to understand the contract scope, eligibility conditions, technical requirements, financial thresholds, accreditations, experience criteria, evaluation methodology and submission deadlines.',
                 'detail' => ['This helps us identify requirements that your organisation can meet, potential barriers to participation and any commitments that may affect your decision to bid.']],
                ['title' => 'Compatibility Assessment & Shortlisting', 'icon' => 'list-checks', 'image' => 'sectors/facilities-manager-plant-room', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => "We compare the contract requirements against your organisation's capabilities and commercial objectives.",
                 'detail' => [
                     'Our assessment considers whether your business meets the eligibility requirements, possesses relevant experience, has sufficient delivery capacity and can present a credible competitive offer.',
                     'We then shortlist suitable opportunities, highlighting their strengths, potential challenges and overall relevance to your business.',
                 ]],
                ['title' => 'Opportunity Presentation & Recommendations', 'icon' => 'presentation-chart', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'sectors/education-adult-training-workshop',
                 'text' => 'We present the selected opportunities with a clear summary of the contract, buyer, estimated value where available, location, key requirements, submission deadline and our assessment of suitability.',
                 'detail' => ['You receive practical recommendations on which opportunities are worth exploring further, helping you decide where to invest your bidding resources.']],
            ],
        ],
        [
            'type'    => 'list',
            'id'      => 'tender-compatibility-analysis',
            'heading' => 'Tender',
            'accent'  => 'Compatibility Analysis',
            'sub'     => 'Assessing Your Fit Before You Commit to Bidding',
            'paras'   => [
                'A relevant tender is not necessarily a suitable tender. Understanding the difference can save valuable resources and improve the quality of your bidding decisions.',
                "Our compatibility analysis examines the factors that influence your organisation's ability to compete and deliver:",
            ],
            'items'   => [
                ['icon' => 'seal-check', 'title' => 'Eligibility & Compliance', 'text' => 'Whether your organisation meets mandatory participation requirements, qualifications, accreditations and financial thresholds.'],
                ['icon' => 'briefcase', 'title' => 'Experience & Technical Capability', 'text' => "How closely your previous contracts, industry expertise and service capabilities match the buyer's expectations."],
                ['icon' => 'map-trifold', 'title' => 'Operational & Geographical Fit', 'text' => 'Whether you have the workforce, resources, service coverage and capacity required to fulfil the contract.'],
                ['icon' => 'currency-gbp', 'title' => 'Commercial Suitability', 'text' => 'Whether the contract value, duration, delivery obligations and potential costs align with your business objectives.'],
                ['icon' => 'ranking', 'title' => 'Competitive Position', 'text' => 'How effectively your organisation could demonstrate its strengths against the published evaluation criteria and likely demands of the contract.'],
            ],
            'outro'   => 'Based on these findings, we help you distinguish promising opportunities from those that may be unsuitable, supporting a more informed **Bid/No-Bid decision**.',
            'variant' => 'mist',
        ],
        [
            'type'    => 'why',
            'id'      => 'why-choose-us',
            'heading' => 'Why Choose My Solutionz for',
            'accent'  => 'Opportunity Identification?',
            'sub'     => 'Spend Less Time Searching and More Time Pursuing the Right Contracts',
            'paras'   => ['Searching for tenders can be time-consuming, particularly when opportunities are published across multiple procurement platforms and contain extensive qualification requirements.'],
            'lead_in' => 'Our opportunity identification service helps you:',
            'items'   => [
                ['icon' => 'magnifying-glass', 'strong' => 'Discover relevant contracts', 'text' => 'aligned with your services, experience and business goals.'],
                ['icon' => 'funnel', 'strong' => 'Avoid unsuitable tenders', 'text' => 'by identifying eligibility barriers and operational challenges early.'],
                ['icon' => 'scales', 'strong' => 'Make informed bidding decisions', 'text' => 'through contract screening and compatibility assessments.'],
                ['icon' => 'clock', 'strong' => 'Save management time', 'text' => 'by receiving shortlisted opportunities instead of searching through numerous unrelated notices.'],
                ['icon' => 'trend-up', 'strong' => 'Build a focused tender pipeline', 'text' => "that supports your organisation's commercial growth plans."],
            ],
            'outro'   => 'Whether you are a small business entering public procurement for the first time or an established contractor exploring new markets, we tailor our searches to your capabilities and ambitions.',
        ],
    ],

    // Siblings + 1 cross-category link (the doc suggests Carbon Reduction Plan)
    'related' => ['bid-proposal-management/tender-bid-writing', 'bid-proposal-management/expert-review', 'compliance-virtual-assistance/carbon-reduction-plan'],

    'faq_tag' => 'opportunity-identification',
    'faq'     => [
        'eyebrow' => 'FAQs',
        'heading' => 'Opportunity Identification',
        'accent'  => 'FAQs',
    ],

    'cta' => [
        'heading' => 'Discover Opportunities',
        'accent'  => 'Worth Pursuing',
        'sub'     => 'Let Us Find the Contracts That Fit Your Business',
        'paras'   => [
            'The right contract opportunity can open the door to valuable business growth, but identifying it requires more than a simple tender search.',
            'At My Solutionz, we combine targeted opportunity searches, detailed requirement screening and practical business analysis to help you identify contracts that genuinely suit your organisation.',
        ],
        'strong'  => 'Stop spending valuable time searching through unsuitable tenders. Let us help you find opportunities worth competing for.',
        'label'   => 'Find My Next Tender Opportunity',
    ],
];
