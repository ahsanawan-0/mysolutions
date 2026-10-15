<?php
declare(strict_types=1);

/**
 * About page copy (PROMPTS P18–P19, docs/08 §5 A0–A7). Read via about('story.paras').
 * Source: docs/website_content/about-us-content.md = the client's final copy, VERBATIM. The only change from the
 * client's .docx is the approved one: "over 16 years" in Our Story → "over 10 years" (docs/00 L05c).
 * Each section's eyebrow is the client's section name; its two-voice H2 is the client's sub-heading.
 * Images: the generated editorial set (assets-src/images/README.md) until real team / office photos arrive (Q19);
 * swapping a key here swaps the photo.
 * P18 builds hero, nav, about_us, story; P19 adds approach, why, mission, team.
 */
return [
    'seo' => [
        'title'       => 'About My Solutionz | UK Tender Writing Consultancy',
        'description' => 'Meet My Solutionz: a London-based tender writing and bid management consultancy with over 10 years of business development experience across UK sectors.',
        'og_title'    => 'About My Solutionz',
    ],

    // A0: hero-inner `columns` (C32)
    'hero' => [
        'eyebrow' => 'About Us',
        'title'   => 'About',
        'accent'  => 'My Solutionz',
        'lead'    => 'We help businesses compete for new opportunities through professional tender writing, bid management and business support services.',
        'ctas'    => [['Get a Free Quote', '/contact-us/#quote'], ['Our Services', '/services/']],
        'images'  => [
            ['key' => 'home/tender-consultancy-meeting', 'alt' => 'Tender writing consultant reviewing a bid with a client team'],
            ['key' => 'home/hero-building', 'alt' => '', 'focus' => 'right'],
            ['key' => 'sectors/healthcare-social-care-consultation', 'alt' => ''],
        ],
        'labels'  => [
            ['text' => 'Bid Strategy',         'icon' => 'strategy'],
            ['text' => 'Compliance',           'icon' => 'seal-check'],
            ['text' => 'Evidence-Led Writing', 'icon' => 'pen-nib'],
        ],
    ],

    // In-page sub-nav, in the locked section order (docs/00 L17). Only sections the page renders are listed.
    'nav' => [
        'about-us'         => 'About Us',
        'our-story'        => 'Our Story',
        'our-approach'     => 'Our Approach',
        'why-work-with-us' => 'Why Work With Us',
        'mission-vision'   => 'Mission & Vision',
    ],

    // A1: About Us (about-us-content.md §1, 5 paragraphs)
    'about_us' => [
        'eyebrow' => 'About Us',
        'title'   => 'Your Business Growth Is',
        'accent'  => 'Our Business',
        'paras'   => [
            "At My Solutionz, we help businesses compete for new opportunities through professional tender writing, bid management and business support services. With over 10 years of business development experience behind us, we understand the importance of securing the right contracts and presenting a business's capabilities in a way that gives buyers confidence.",
            'We work with businesses across a wide range of sectors, including healthcare and social care, transport, cleaning, education and training, security, facilities management and building maintenance. From businesses submitting their first tender to established organisations competing for major public and private sector contracts, we provide the expertise and support needed throughout the bidding process.',
            'Our approach is straightforward. We take the time to understand our clients, their services, operational strengths and commercial objectives before developing tender responses that reflect what they can genuinely deliver. We pay close attention to procurement requirements, evaluation criteria and supporting evidence, because a competitive submission needs substance as well as clear writing.',
            'Beyond tender writing, our services extend to digital presence solutions and virtual assistance, allowing us to support businesses with other important aspects of their operations.',
            'At the heart of My Solutionz is a commitment to building lasting client relationships through dependable service, practical advice and a genuine interest in the businesses we work with. We see ourselves not simply as external tender writers, but as a professional partner invested in helping our clients pursue opportunities and achieve their business goals.',
        ],
        'image'   => ['key' => 'sectors/education-adult-training-workshop', 'alt' => 'Consultant leading a planning session with a team at a flipchart'],
        'sectors_label' => 'Sectors we work with',
    ],

    // A2: Our Story (about-us-content.md §2, 8 paragraphs + the closing statement)
    // 'read' => true: the whole paragraph gets read-along; a string: only that opening sentence does.
    // 'image' => index into 'images': the sticky photo that shows while this paragraph is being read.
    'story' => [
        'eyebrow' => 'Our Story',
        'title'   => 'Built on Experience,',
        'accent'  => 'Driven by Business Growth',
        'paras'   => [
            ['image' => 0, 'text' => 'My Solutionz was founded with a clear purpose: to help businesses compete for contracts and create opportunities for long-term growth.'],
            ['image' => 0, 'text' => 'With over 10 years of experience in business development, we understand what it takes to grow a business and the challenges that come with securing new contracts. We have seen capable organisations miss valuable opportunities, not because they lack the expertise to deliver, but because they struggle to present their strengths effectively through competitive tender submissions.'],
            ['image' => 0, 'text' => 'We established My Solutionz to bridge that gap, bringing together our experience in business development, tender writing and bid management to provide businesses with practical, professional support throughout the procurement process.'],
            ['image' => 1, 'text' => 'Over the years, we have worked across a range of industries, including healthcare and social care, transport, cleaning, education and training, security, facilities management and building maintenance. Each sector presents different challenges, from regulatory compliance and workforce planning to service delivery, contract mobilisation and performance management. Understanding these differences is central to how we approach our work.'],
            ['image' => 1, 'text' => "Our experience in healthcare and social care has given us particularly strong insight into complex, highly regulated procurement environments. At the same time, our work across other sectors has reinforced the importance of understanding each client's operations, commercial objectives and the specific expectations of the contracting authority."],
            ['image' => 2, 'read' => 'We have never believed in taking a standard approach to every tender.', 'text' => 'We have never believed in taking a standard approach to every tender. Behind each submission is a business with its own experience, people, resources and ambitions. We take the time to understand those details, identify what makes the organisation competitive and develop responses that accurately reflect its ability to deliver.'],
            ['image' => 2, 'text' => 'Today, My Solutionz supports businesses of different sizes, from organisations entering the tendering market for the first time to established providers competing for larger contracts and framework opportunities. Alongside our core tender writing and bid management services, we offer digital presence solutions and virtual assistance to support wider business needs.'],
            ['image' => 2, 'read' => true, 'text' => 'What began with a commitment to helping businesses secure new opportunities continues to shape the way we work. We value straightforward communication, attention to detail and long-term client relationships built on trust.'],
        ],
        'statement' => 'For us, every tender represents more than a submission deadline. It represents an opportunity for a business to move forward, and we take our role in that process seriously.',
        'images'  => [
            ['key' => 'home/hero-building', 'alt' => 'Contemporary glass office tower at blue hour'],
            ['key' => 'sectors/healthcare-social-care-consultation', 'alt' => 'Care coordinator reviewing a folder with an older resident'],
            ['key' => 'sectors/facilities-manager-plant-room', 'alt' => 'Facilities manager checking plant-room equipment with a tablet'],
        ],
    ],
];
