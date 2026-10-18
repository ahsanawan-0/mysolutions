<?php
declare(strict_types=1);

/**
 * Blog authors (PROMPTS P25, docs/10 §6 + §10 E-E-A-T). A post's front matter names its author by key
 * (`author: team`); App\Blog::author() falls back to `team` for a missing or unknown key.
 * Keys: name, type (Organization | Person), url (site path), jobTitle, bio, photo (image-manifest key), sameAs (URLs,
 * e.g. LinkedIn). Add a person only with their permission and real details (docs/13 §7).
 */
return [
    'team' => [
        'name' => 'My Solutionz Team',
        'type' => 'Organization',
        'url'  => '/about-us/',
        'bio'  => 'The My Solutionz team brings over 10 years of business development experience to tender writing, bid management, compliance and digital support for UK businesses.', // PROVISIONAL
    ],

    // Person example (copy, fill in with real, permissioned details, then use `author: <key>` in a post):
    // 'first-last' => [
    //     'name'     => 'First Last',
    //     'type'     => 'Person',
    //     'jobTitle' => 'Bid Manager',
    //     'bio'      => 'One or two sentences on their experience and the sectors they write for.',
    //     'photo'    => 'team/first-last',
    //     'url'      => '/about-us/',
    //     'sameAs'   => ['https://www.linkedin.com/in/first-last/'],
    // ],
];
