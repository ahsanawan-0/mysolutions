<?php
declare(strict_types=1);

namespace App;

/**
 * JSON-LD builders (docs/10 §6). One @graph per page with stable @ids:
 *   {site}/#organization, {site}/#website, {canonical}#webpage, {canonical}#breadcrumbs, …
 * Home = organization + website + webPage; every other page = webPage + breadcrumbs + its own nodes,
 * all referencing #organization / #website by @id.
 */
final class Schema
{
    public static function organizationId(): string
    {
        return url('/') . '#organization';
    }

    public static function websiteId(): string
    {
        return url('/') . '#website';
    }

    public static function organization(): array
    {
        $address = config('address');
        $node = [
            '@type'       => (string) config('seo.organization_type', 'Organization'),
            '@id'         => self::organizationId(),
            'name'        => config('name'),
            'legalName'   => config('legal_name'),
            'alternateName' => config('alternate_names'),
            'url'         => url('/'),
            'logo'        => [
                '@type'  => 'ImageObject',
                '@id'    => url('/') . '#logo',
                'url'    => Page::absolute((string) config('seo.logo')),
                'contentUrl' => Page::absolute((string) config('seo.logo')),
                'width'  => 512,
                'height' => 512,
                'caption' => config('name'),
            ],
            'image'       => ['@id' => url('/') . '#logo'],
            'description' => config('description'),
            'slogan'      => config('tagline'),
            'email'       => config('email'),
            'telephone'   => config('phone_e164'),
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $address['street'],
                'addressLocality' => $address['locality'],
                'postalCode'      => $address['postcode'],
                'addressCountry'  => $address['country'],
            ],
            'areaServed'  => ['@type' => 'Country', 'name' => 'United Kingdom', 'identifier' => config('area_served')],
            'contactPoint' => [
                '@type'             => 'ContactPoint',
                'contactType'       => 'sales',
                'telephone'         => config('phone_e164'),
                'email'             => config('email'),
                'url'               => url('/contact-us/'),
                'areaServed'        => config('area_served'),
                'availableLanguage' => 'en-GB',
            ],
            'sameAs'      => array_values(config('socials')),
            'foundingDate' => config('founded'),
            'identifier'  => [
                '@type'        => 'PropertyValue',
                'propertyID'   => 'Companies House',
                'name'         => 'Company number',
                'value'        => config('company_number'),
            ],
            'knowsAbout'  => config('seo.knows_about'),
        ];

        if ($node['@type'] === 'LocalBusiness') {
            $node['priceRange'] = '££';
            $node['openingHoursSpecification'] = array_map(static fn (array $d): array => [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => $d['days'],
                'opens'     => $d['opens'],
                'closes'    => $d['closes'],
            ], (array) config('hours.days'));
        }
        return $node;
    }

    public static function website(): array
    {
        return [
            '@type'         => 'WebSite',
            '@id'           => self::websiteId(),
            'url'           => url('/'),
            'name'          => config('name'),
            'alternateName' => config('alternate_names'),
            'description'   => config('description'),
            'publisher'     => ['@id' => self::organizationId()],
            'inLanguage'    => config('locale'),
            // No SearchAction (sitelinks search box was retired in November 2024)
        ];
    }

    public static function webPage(array $page, string $type = 'WebPage'): array
    {
        $canonical = (string) $page['canonical'];
        $node = [
            '@type'       => $type,
            '@id'         => $canonical . '#webpage',
            'url'         => $canonical,
            'name'        => $page['og_title'] ?: $page['title'],
            'description' => $page['description'],
            'isPartOf'    => ['@id' => self::websiteId()],
            'about'       => ['@id' => self::organizationId()],
            'inLanguage'  => config('locale'),
        ];
        // Extra `about` subjects (P22 sector pages: the relevant services, as @id references)
        if (!empty($page['about'])) {
            $node['about'] = array_merge([['@id' => self::organizationId()]], array_values($page['about']));
        }
        if (!empty($page['primary_image'])) {
            $node['primaryImageOfPage'] = ['@type' => 'ImageObject', 'url' => $page['primary_image']];
        }
        if (!empty($page['published'])) {
            $node['datePublished'] = $page['published'];
        }
        if (!empty($page['modified'])) {
            $node['dateModified'] = $page['modified'];
        }
        if (!empty($page['breadcrumbs'])) {
            $node['breadcrumb'] = ['@id' => $canonical . '#breadcrumbs'];
        }
        return $node;
    }

    /** @param array<int, array{0:string,1:?string}> $crumbs */
    public static function breadcrumbs(array $crumbs, string $canonical): array
    {
        $items = [];
        foreach (array_values($crumbs) as $i => [$label, $path]) {
            $item = ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $label];
            if ($path !== null) {
                $item['item'] = Page::absolute($path);
            }
            $items[] = $item;
        }
        return [
            '@type'           => 'BreadcrumbList',
            '@id'             => $canonical . '#breadcrumbs',
            'itemListElement' => $items,
        ];
    }

    /**
     * Sub-service page. $service keys: name, description, url (path), category (['name','slug']), serviceType?.
     */
    public static function service(array $service): array
    {
        $url = Page::absolute($service['url']);
        $node = [
            '@type'       => 'Service',
            '@id'         => $url . '#service',
            'name'        => $service['name'],
            'serviceType' => $service['serviceType'] ?? $service['name'],
            'description' => $service['description'] ?? '',
            'url'         => $url,
            'provider'    => ['@id' => self::organizationId()],
            'areaServed'  => ['@type' => 'Country', 'name' => 'United Kingdom'],
            'audience'    => ['@type' => 'BusinessAudience', 'name' => 'UK businesses and organisations bidding for contracts'],
        ];
        if (!empty($service['category']['slug'])) {
            $node['isRelatedTo'] = ['@id' => url('/services/' . $service['category']['slug'] . '/') . '#service'];
        }
        return $node;
    }

    /**
     * Category landing page: Service + hasOfferCatalog of its sub-services (docs/10 §6, P20).
     * $category keys: name, description, url (path), serviceType?, subs[] (name, url, description?).
     */
    public static function serviceCategory(array $category): array
    {
        $url = Page::absolute($category['url']);
        return [
            '@type'       => 'Service',
            '@id'         => $url . '#service',
            'name'        => $category['name'],
            'serviceType' => $category['serviceType'] ?? $category['name'],
            'description' => $category['description'] ?? '',
            'url'         => $url,
            'provider'    => ['@id' => self::organizationId()],
            'areaServed'  => ['@type' => 'Country', 'name' => 'United Kingdom'],
            'audience'    => ['@type' => 'BusinessAudience', 'name' => 'UK businesses and organisations bidding for contracts'],
            'hasOfferCatalog' => [
                '@type'           => 'OfferCatalog',
                'name'            => $category['name'],
                'itemListElement' => array_map(static fn (array $sub): array => [
                    '@type'       => 'Offer',
                    'itemOffered' => [
                        '@type'       => 'Service',
                        'name'        => $sub['name'],
                        'url'         => Page::absolute($sub['url']),
                        'description' => $sub['description'] ?? '',
                    ],
                ], $category['subs'] ?? []),
            ],
        ];
    }

    /**
     * Blog article. $post keys: title, description, url, published, modified, author (name, url?, sameAs?, jobTitle?),
     * images (string[] absolute: 16:9, 4:3, 1:1), section?, wordCount?
     */
    public static function blogPosting(array $post): array
    {
        $url = Page::absolute($post['url']);
        $author = $post['author'] ?? ['name' => config('name')];
        $node = [
            '@type'            => 'BlogPosting',
            '@id'              => $url . '#article',
            'headline'         => $post['title'],
            'description'      => $post['description'] ?? '',
            'url'              => $url,
            'mainEntityOfPage' => ['@id' => $url . '#webpage'],
            'datePublished'    => Page::date($post['published'] ?? null),
            'dateModified'     => Page::date($post['modified'] ?? ($post['published'] ?? null)),
            'author'           => array_filter([
                '@type'    => 'Person',
                'name'     => $author['name'],
                'url'      => isset($author['url']) ? Page::absolute($author['url']) : null,
                'sameAs'   => $author['sameAs'] ?? null,
                'jobTitle' => $author['jobTitle'] ?? null,
            ], static fn ($v) => $v !== null),
            'publisher'        => ['@id' => self::organizationId()],
            'image'            => array_values($post['images'] ?? []),
            'inLanguage'       => config('locale'),
        ];
        if (!empty($post['section'])) {
            $node['articleSection'] = $post['section'];
        }
        if (!empty($post['wordCount'])) {
            $node['wordCount'] = (int) $post['wordCount'];
        }
        return $node;
    }

    /** Optional (off by default: config seo.faq_schema). $faqs: [['q' => …, 'a' => …], …] */
    public static function faqPage(array $faqs, string $canonical): ?array
    {
        if (!config('seo.faq_schema', false) || $faqs === []) {
            return null;
        }
        return [
            '@type'      => 'FAQPage',
            '@id'        => $canonical . '#faq',
            'mainEntity' => array_map(static fn (array $f): array => [
                '@type'          => 'Question',
                'name'           => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
            ], $faqs),
        ];
    }

    /** Assemble the page's @graph. */
    public static function graph(array $page): array
    {
        if (($page['canonical'] ?? false) === false) {
            return [];   // error pages carry no structured data
        }
        $nodes = [];
        if ($page['path'] === '/') {
            $nodes[] = self::organization();
            $nodes[] = self::website();
        }
        $nodes[] = self::webPage($page, $page['web_page_type'] ?? 'WebPage');
        if (!empty($page['breadcrumbs'])) {
            $nodes[] = self::breadcrumbs($page['breadcrumbs'], (string) $page['canonical']);
        }
        foreach ($page['schema'] ?? [] as $node) {
            if (is_array($node) && $node !== []) {
                $nodes[] = $node;
            }
        }
        return ['@context' => 'https://schema.org', '@graph' => $nodes];
    }

    public static function json(array $graph): string
    {
        if ($graph === []) {
            return '';
        }
        return (string) json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_PRETTY_PRINT);
    }
}
