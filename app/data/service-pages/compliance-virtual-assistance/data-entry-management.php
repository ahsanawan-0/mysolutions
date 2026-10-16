<?php
declare(strict_types=1);

/**
 * Data Entry & Management, sub-service page /services/compliance-virtual-assistance/data-entry-management/ (PROMPTS P21).
 * DRAFT until the client's copy arrives (content-fill C03b/C03c): the hero comes from app/data/services.php and every
 * block holds a [[PENDING: …]] marker in the client's sub-service shape (intro → text → list → steps → why), so it
 * renders as a dashed "Content pending" block off production and is omitted on production.
 * See ../bid-proposal-management/tender-bid-writing.php for the finished format.
 */
return [
    'status' => 'draft',

    'seo' => [
        'title'       => 'Data Entry & Management Services UK | My Solutionz', // PROVISIONAL
        'description' => '[[PENDING: meta description (140–160 characters) from the client copy]]',
    ],

    'hero' => [
        'title'  => 'Data Entry &',
        'accent' => 'Management',
    ],

    'sections' => [
        ['type' => 'intro', 'paras' => ['[[PENDING: hero tagline, opening paragraph and 1–2 intro paragraphs on data entry and records management]]']],
        ['type' => 'text', 'id' => 'what-is', 'heading' => 'What Is', 'accent' => 'Data Entry & Management?', 'paras' => ['[[PENDING: "What is …?" section: a quotable first-paragraph definition + 2–3 paragraphs]]']],
        ['type' => 'list', 'id' => 'what-we-cover', 'heading' => 'What We', 'accent' => 'Cover', 'items' => '[[PENDING: 4–6 icon items (title + one sentence each)]]'],
        ['type' => 'steps', 'id' => 'how-it-works', 'heading' => 'How It', 'accent' => 'Works', 'steps' => '[[PENDING: 5 process steps (title + one paragraph each)]]'],
        ['type' => 'why', 'id' => 'why-choose-us', 'heading' => 'Why Choose', 'accent' => 'My Solutionz?', 'items' => '[[PENDING: "Why choose" paragraphs + 4–5 bold-led benefit sentences]]'],
    ],

    'faq_tag' => 'data-entry-management',
    'cta'     => [],   // the default CTA band copy until the client's closing copy arrives
];
