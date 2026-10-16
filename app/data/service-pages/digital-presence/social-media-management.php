<?php
declare(strict_types=1);

/**
 * Social Media Management, sub-service page /services/digital-presence/social-media-management/ (PROMPTS P21).
 * DRAFT: provisional raw material mapped from the current site's Social Media Management page
 * (docs/website_content/current-site-content-scrape.md §4.5, old URL /service/social-media-management/), to be
 * rewritten with the client in C03c. Claims are toned down ("drive meaningful results", "we've got you covered",
 * "Engagement that Converts" dropped). Blocks without source copy hold [[PENDING]] markers.
 */
return [
    'status' => 'draft',

    'seo' => [
        'title'        => 'Social Media Management Services UK | My Solutionz', // PROVISIONAL
        'description'  => '[[PENDING: meta description (140–160 characters) from the client copy]]',
        'service_type' => 'Social media management',
    ],

    'hero' => [
        'title'  => 'Social Media',
        'accent' => 'Management',
        'lead'   => 'We help you use social media platforms to strengthen your brand presence, engage your audience and support your marketing goals.', // PROVISIONAL
    ],

    'sections' => [
        [
            'type'  => 'intro',
            'paras' => [
                "At My Solutionz, we understand the power of social media in today's digital landscape. Whether you need help crafting clear, concise messages, developing slogans or creating engaging content, we can support you.", // PROVISIONAL
            ],
        ],
        ['type' => 'text', 'id' => 'what-is-social-media-management', 'heading' => 'What Is', 'accent' => 'Social Media Management?', 'paras' => ['[[PENDING: "What is …?" section: what is managed, on which platforms, and what it means for a B2B business]]']],
        ['type' => 'list', 'id' => 'what-we-cover', 'heading' => 'What We', 'accent' => 'Cover', 'items' => '[[PENDING: 4–6 icon items: strategy, content, scheduling, community, reporting …]]'],
        [
            'type'    => 'steps',
            'id'      => 'how-it-works',
            'heading' => 'How Our Social Media',
            'accent'  => 'Management Works',
            'steps'   => [ // PROVISIONAL (step titles from the current site; one-line texts ours)
                ['title' => 'Strategy Development', 'icon' => 'strategy', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => 'We agree a social media strategy around your brand, audience and business goals.'],
                ['title' => 'Content Creation and Curation', 'icon' => 'pen-nib', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'home/tender-consultancy-meeting',
                 'text' => 'We create and curate content that reflects your brand and speaks to your audience.'],
                ['title' => 'Posting and Engagement', 'icon' => 'chats-circle', 'image' => 'sectors/education-adult-training-workshop', 'detail_image' => 'services/digital-presence-web-designer',
                 'text' => "We publish your posts and respond to your audience's comments and messages."],
                ['title' => 'Performance Monitoring and Optimisation', 'icon' => 'chart-line-up', 'image' => 'services/digital-presence-web-designer', 'detail_image' => 'home/hero-building',
                 'text' => 'We monitor how your content performs and adjust the plan to improve results.'],
                ['title' => 'Reporting and Analysis', 'icon' => 'presentation-chart', 'image' => 'home/tender-consultancy-meeting', 'detail_image' => 'sectors/education-adult-training-workshop',
                 'text' => 'We report on the results and explain what they mean for your next steps.'],
            ],
        ],
        [
            'type'    => 'why',
            'id'      => 'why-choose-us',
            'heading' => 'Why Choose My Solutionz for',
            'accent'  => 'Social Media Management?',
            'items'   => [ // PROVISIONAL (headings from the current site)
                ['icon' => 'lightning', 'strong' => 'Innovative solutions', 'text' => 'for content that suits your brand and your audience.'],
                ['icon' => 'user-focus', 'strong' => 'Personalised attention', 'text' => 'from a team that gets to know your business.'],
                ['icon' => 'chart-line-up', 'strong' => 'Data-driven decisions', 'text' => 'based on how your content actually performs.'],
            ],
        ],
    ],

    'faq_tag' => 'social-media-management',

    'cta' => [ // PROVISIONAL (the current site's closing line)
        'heading' => 'Ready to Grow Your',
        'accent'  => 'Social Media Presence?',
        'paras'   => ['Ready to take your social media presence to the next level? Contact us today to learn more about our Social Media Management services and how we can help you achieve your digital marketing goals.'],
    ],
];
