<?php
declare(strict_types=1);

/**
 * /faq/ page copy (PROMPTS P27, docs/08 §10). Read via faq_page('hero.title'). The questions themselves live in
 * app/data/faqs.php (tag `faq`, grouped by `group`). Copy marked // PROVISIONAL awaits the client's approval.
 */
return [
    // Draft until the client approves the answers (P27); C06 sets it to 'published'
    'status' => 'draft',

    'seo' => [
        'title'       => 'Tender Writing & Bid Management FAQs | My Solutionz',
        'description' => 'Answers to common questions about tender writing, bid management, pricing, confidentiality and how My Solutionz supports UK businesses.',
        'og_title'    => 'Tender Writing & Bid Management FAQs',
    ],

    'hero' => [
        'title'  => 'Frequently Asked',
        'accent' => 'Questions',
        'lead'   => 'Straight answers about tendering, our services, pricing and the sectors we work in.', // PROVISIONAL
    ],

    'search' => [
        'label'       => 'Search the FAQs',
        'placeholder' => 'For example: cost or CQC',
        'submit'      => 'Search',
        'clear'       => 'Clear search',
    ],

    // The live result count ({n}, {groups}, {q}); announced politely while typing
    'status' => [
        'all'   => '{n} questions in {groups} topics',
        'match' => '{n} {questions} matching “{q}”',
        'none'  => 'No questions match “{q}”',
        'one'   => 'question',
        'many'  => 'questions',
    ],

    'topics' => 'FAQ topics',

    'empty' => [
        'title'   => 'No matching questions',
        'text'    => 'Try a different word, browse all the questions, or ask us directly.',
        'all'     => 'Show all questions',
        'contact' => 'Ask us a question',
    ],

    'cta' => [
        'eyebrow' => 'Still have a question?',
        'heading' => 'Still Have a',
        'accent'  => 'Question?',
        'text'    => "Tell us what you'd like to know. We'll give you a straight answer, with no obligation.", // PROVISIONAL
        'primary' => ['Ask Your Question', '/contact-us/#quote'],
    ],
];
