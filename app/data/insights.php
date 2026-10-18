<?php
declare(strict_types=1);

/**
 * Insights listing copy (PROMPTS P25, docs/08 §9): /insights/, /insights/page/<n>/, /insights/category/<slug>/ and
 * the RSS feed. Read via insights('hero.title'). Category names, H1s and descriptions live in content/categories.php.
 * Copy marked // PROVISIONAL is ours and awaits the client's approval.
 */
return [
    'seo' => [
        'title'       => 'Tender & Bid Writing Insights | My Solutionz',
        'description' => 'Practical guidance on tender writing, bid management, UK public procurement and the Procurement Act 2023 from the My Solutionz team.',
        'og_title'    => 'Tender & Bid Writing Insights',
        'page_title'  => '{title} (Page {n}) | My Solutionz',   // pages 2+: unique titles and descriptions
        'page_desc'   => 'Page {n}: {description}',
    ],

    'hero' => [
        'title'  => 'Tender & Bid Writing',
        'accent' => 'Insights',
        'lead'   => 'Practical guidance on tendering, UK public procurement and growing your business.',
    ],

    // A listing is `published` (indexable, in the sitemap) once at least this many posts are published
    'min_published' => 3,

    'filter' => [
        'label'   => 'Filter insights by category',
        'all'     => 'All insights',
        'status'  => 'Showing {n} {insights} in {category}.',   // announced after an in-place filter (JS)
        'status_all' => 'Showing all {n} {insights}.',
        'one'     => 'insight',
        'many'    => 'insights',
    ],

    'featured' => 'Featured insight',          // visually hidden headings that give the cards their outline
    'more'     => 'More insights',
    'read'     => '{n} min read',

    'pagination' => [
        'label'    => 'Insights pages',
        'previous' => 'Previous',
        'next'     => 'Next',
        'page'     => 'Page {n}',
        'previous_label' => 'Previous page',
        'next_label'     => 'Next page',
    ],

    'empty' => [
        'eyebrow' => 'Coming soon',
        'title'   => 'Our First Insights Are',
        'accent'  => 'On Their Way',
        'text'    => "We're preparing practical guides on tender writing, UK public procurement and compliance. Follow us on LinkedIn to see them as soon as they're published.", // PROVISIONAL
        'button'  => 'Follow us on LinkedIn',
        'category_title'  => 'Nothing Here',
        'category_accent' => 'Yet',
        'category_text'   => "We haven't published any insights in this category yet. Browse all our insights, or follow us on LinkedIn for new guides.", // PROVISIONAL
        'all'     => 'View all insights',
    ],

    'feed' => [
        'title'       => 'My Solutionz Insights',
        'description' => 'Practical guidance on tender writing, bid management, UK public procurement and compliance from the My Solutionz team.',
    ],

    'blog_name' => 'My Solutionz Insights',   // schema.org Blog name

    // The article page (P26, templates/pages/insights/single.php)
    'article' => [
        'by'          => 'By {name}',
        'published'   => 'Published {date}',
        'updated'     => 'Updated {date}',
        'toc'         => 'In this article',
        'toc_label'   => 'Table of contents',
        'takeaways'   => 'Key takeaways',
        'progress'    => 'Reading progress',
        'share'       => 'Share this article',
        'share_on'    => 'Share on {network}',
        'share_email' => 'Share by email',
        'new_tab'     => '(opens in a new tab)',
        'email_body'  => "I thought you might find this useful:\n\n{title}\n{url}",
        'author'      => 'About the author',
        'linkedin'    => 'Follow on LinkedIn',
        'about'       => 'About us',
        'related'     => ['title' => 'Keep', 'accent' => 'Reading'],
        'related_sub' => 'More practical guidance from the My Solutionz team.', // PROVISIONAL
        'all'         => 'View all insights',
    ],
];
