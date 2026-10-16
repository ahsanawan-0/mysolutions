<?php
declare(strict_types=1);

/**
 * CQC Registration, sub-service page /services/compliance-virtual-assistance/cqc-registration/ (PROMPTS P21).
 * DRAFT: provisional raw material mapped from the current site's CQC Registration page
 * (docs/website_content/current-site-content-scrape.md §4.6, old URL /service/cqc-registration/), to be rewritten
 * with the client in C03b. Unsupported claims are removed ("ensure compliance", "smooth and successful
 * registration", "renewal": CQC registration does not expire). Blocks without source copy hold [[PENDING]] markers.
 */
return [
    'status' => 'draft',

    'seo' => [
        'title'        => 'CQC Registration Support | My Solutionz', // PROVISIONAL
        'description'  => '[[PENDING: meta description (140–160 characters) from the client copy]]',
        'service_type' => 'CQC registration support',
    ],

    'hero' => [
        'title'  => 'CQC',
        'accent' => 'Registration',
        'lead'   => 'Our CQC Registration service is designed to simplify the registration process and help you meet CQC standards, so you can focus on providing high-quality care to your clients.', // PROVISIONAL
    ],

    'sections' => [
        [
            'type'  => 'intro',
            'paras' => [
                'At My Solutionz, we understand the importance of CQC (Care Quality Commission) registration for healthcare providers and care homes.', // PROVISIONAL
                'Whether you are a new provider seeking registration or an existing provider making changes to your registration, we guide you through each stage of the process.', // PROVISIONAL
            ],
        ],
        ['type' => 'text', 'id' => 'what-is-cqc-registration', 'heading' => 'What Is', 'accent' => 'CQC Registration?', 'paras' => ['[[PENDING: "What is CQC registration?" section: who must register, the regulated activities, the registered manager, what the CQC assesses]]']],
        ['type' => 'list', 'id' => 'what-we-cover', 'heading' => 'What We', 'accent' => 'Cover', 'items' => '[[PENDING: 4–6 icon items: the documents and areas the service covers]]'],
        [
            'type'    => 'steps',
            'id'      => 'how-it-works',
            'heading' => 'How Our CQC Registration',
            'accent'  => 'Support Works',
            'steps'   => [ // PROVISIONAL (step titles from the current site; one-line texts condensed and cleaned)
                ['title' => 'Expert Guidance', 'icon' => 'compass', 'image' => 'sectors/healthcare-social-care-consultation', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => 'We guide you through the CQC registration process, from understanding the registration requirements to completing the application forms, and answer your questions along the way.'],
                ['title' => 'Document Preparation', 'icon' => 'files', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'sectors/healthcare-social-care-consultation',
                 'text' => 'We help you prepare the documents your application needs, including policies and procedures, risk assessments and other required documentation.'],
                ['title' => 'Form Review and Feedback', 'icon' => 'list-checks', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'home/hero-building',
                 'text' => 'We review your prepared documents for completeness, accuracy and alignment with CQC requirements, and explain any changes needed.'],
                ['title' => 'Compliance Assistance', 'icon' => 'seal-check', 'image' => 'sectors/healthcare-social-care-consultation', 'detail_image' => 'sectors/education-adult-training-workshop',
                 'text' => "We help you understand the CQC's regulations and fundamental standards and how your service will meet them."],
                ['title' => 'Ongoing Support', 'icon' => 'arrows-clockwise', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'sectors/healthcare-social-care-consultation',
                 'text' => 'After registration, we can support you in maintaining compliance with CQC standards and in dealing with changes to your service.'],
            ],
        ],
        [
            'type'    => 'why',
            'id'      => 'why-choose-us',
            'heading' => 'Why Choose My Solutionz for',
            'accent'  => 'CQC Registration?',
            'lead_in' => 'We offer:', // PROVISIONAL
            'items'   => [ // PROVISIONAL (headings from the current site)
                ['icon' => 'medal', 'strong' => 'Specialised expertise', 'text' => 'in health and social care registration and compliance.'],
                ['icon' => 'files', 'strong' => 'Comprehensive assistance', 'text' => 'from the application to the supporting documents.'],
                ['icon' => 'user-focus', 'strong' => 'Personalised attention', 'text' => 'for your service, its size and the regulated activities you provide.'],
                ['icon' => 'hand-heart', 'strong' => 'Peace of mind', 'text' => ': we handle the details so you can focus on what matters most, providing high-quality care to your clients.'],
            ],
        ],
    ],

    'faq_tag' => 'cqc-registration',

    'cta' => [ // PROVISIONAL (the current site's closing line, cleaned)
        'heading' => 'Ready to Start Your',
        'accent'  => 'CQC Registration?',
        'paras'   => ['Contact us today to learn more about our CQC Registration services and how we can help you achieve your registration goals.'],
    ],
];
