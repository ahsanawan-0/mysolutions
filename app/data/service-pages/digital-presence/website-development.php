<?php
declare(strict_types=1);

/**
 * Website Development, sub-service page /services/digital-presence/website-development/ (PROMPTS P21).
 * DRAFT: provisional raw material mapped from the current site's Website Development page
 * (docs/website_content/current-site-content-scrape.md §4.4, old URL /service/website-development/), to be rewritten
 * with the client in C03c. Unsupported claims are removed ("Proven Results", "visually stunning", "flawlessly",
 * "stands out"). Blocks without source copy hold [[PENDING]] markers.
 */
return [
    'status' => 'draft',

    'seo' => [
        'title'        => 'Website Development Services UK | My Solutionz', // PROVISIONAL
        'description'  => '[[PENDING: meta description (140–160 characters) from the client copy]]',
        'service_type' => 'Website development',
    ],

    'hero' => [
        'title'  => 'Website',
        'accent' => 'Development',
        'lead'   => 'We develop custom websites tailored to your business, services and goals, so your online presence reflects your identity and reaches your target audience.', // PROVISIONAL
    ],

    'sections' => [
        [
            'type'  => 'intro',
            'paras' => [
                "At My Solutionz, we understand the importance of a strong digital presence in today's competitive landscape. Our Website Development service is designed to help you create an effective online presence that reflects your business's unique identity and offerings.", // PROVISIONAL
            ],
        ],
        ['type' => 'text', 'id' => 'what-is-website-development', 'heading' => 'What Is', 'accent' => 'Website Development?', 'paras' => ['[[PENDING: "What is …?" section on a business website: what it includes and why it matters for buyers and customers]]']],
        ['type' => 'list', 'id' => 'what-we-cover', 'heading' => 'What We', 'accent' => 'Cover', 'items' => '[[PENDING: 4–6 icon items: design, build, content, accessibility, hosting/maintenance …]]'],
        [
            'type'    => 'steps',
            'id'      => 'how-it-works',
            'heading' => 'How Our Website',
            'accent'  => 'Development Works',
            'steps'   => [ // PROVISIONAL (the current site's six steps, condensed and cleaned)
                ['title' => 'Initial Consultation', 'icon' => 'chats-circle', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => 'We begin by consulting with you to understand your business objectives, target audience and branding requirements.'],
                ['title' => 'Design Concept Development', 'icon' => 'compass', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => 'Our designers create design concepts for your website based on your preferences and branding guidelines.'],
                ['title' => 'Website Development and Implementation', 'icon' => 'browser', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'home/hero-building',
                 'text' => 'Once the design concept is approved, our developers build the website using current web technologies and best practices, making it user-friendly, responsive and optimised for search engines.'],
                ['title' => 'Content Integration and Optimisation', 'icon' => 'files', 'image' => 'sectors/education-adult-training-workshop', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => 'We help you integrate and optimise the content for your website.'],
                ['title' => 'Testing and Quality Assurance', 'icon' => 'list-checks', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'sectors/facilities-manager-plant-room',
                 'text' => 'Before launch, we test your website across different devices and browsers.'],
                ['title' => 'Launch and Ongoing Support', 'icon' => 'rocket-launch', 'image' => 'home/hero-building', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => 'When your website is ready to go live, we support you through the launch and provide ongoing support and maintenance.'],
            ],
        ],
        [
            'type'    => 'why',
            'id'      => 'why-choose-us',
            'heading' => 'Why Choose My Solutionz for',
            'accent'  => 'Website Development?',
            'items'   => [ // PROVISIONAL (headings from the current site; "Proven Results" dropped: no evidence yet)
                ['icon' => 'lightning', 'strong' => 'Innovative solutions', 'text' => 'built with current web technologies and best practices.'],
                ['icon' => 'strategy', 'strong' => 'A strategic approach', 'text' => 'that starts from your business objectives and target audience.'],
                ['icon' => 'chats-circle', 'strong' => 'Responsive communication', 'text' => 'throughout the design, build and launch.'],
            ],
        ],
    ],

    'faq_tag' => 'website-development',

    'cta' => [ // PROVISIONAL (the current site's closing line)
        'heading' => 'Ready to Build Your',
        'accent'  => 'New Website?',
        'paras'   => ['Ready to elevate your online presence with a custom-designed website? Contact us today to learn more about our Website Development services and how we can help you achieve your digital goals.'],
    ],
];
