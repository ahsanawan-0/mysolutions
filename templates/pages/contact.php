<?php
declare(strict_types=1);
/**
 * Contact page /contact-us/ (PROMPTS P23, docs/08 §11). Copy: app/data/contact.php (contact()); facts (phone, email,
 * address, hours, socials): app/config/site.php.
 * Order: hero-inner `simple` → contact cards (Call, WhatsApp, Email, Office → Google Maps, Office hours) → the quote
 * form `#quote` (sections/contact/form.php; POST → App\Controllers\ContactController, which re-renders this template
 * with `$form` = values + errors + notice) → static map image linking to Google Maps (no embed before consent,
 * docs/13 §2) → socials → mini FAQ (faqs.php tag `contact`) → call strip.
 * The page starts the form session (App\Csrf, strictly necessary cookie) for the CSRF token and the time-trap stamp.
 * @var array|null $form  ['values' => …, 'errors' => [field => message], 'notice' => ?string] (set by the controller)
 */
$seo = contact('seo');
$hero = contact('hero');
$page = [
    'title'          => $seo['title'],
    'description'    => $seo['description'],
    'canonical'      => '/contact-us/',
    'status'         => 'published',
    'og_title'       => $seo['og_title'],
    'web_page_type'  => 'ContactPage',
    'breadcrumbs'    => [['Home', '/'], ['Contact Us', null]],
    'body_class'     => 'page-contact',
    'header_theme'   => 'light',
    'header_overlay' => true,
];

$faqItems = array_values(array_filter(faqs('contact'), static fn (array $f): bool => !is_production() || pending_note($f['a']) === null));
$f = contact('faq');
$strip = contact('call_strip');
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => $page['breadcrumbs'],
    'title'       => $hero['title'],
    'accent'      => $hero['accent'],
    'lead'        => $hero['lead'],
]) ?>

<?= render('sections/contact/cards') ?>

<?= render('sections/contact/form', ['form' => $form ?? []]) ?>

<?= render('sections/contact/map') ?>

<?= render('sections/service/faq', ['faq' => ['eyebrow' => $f['eyebrow'], 'heading' => $f['heading'], 'accent' => $f['accent']], 'items' => $faqItems, 'id' => 'contact-faq']) ?>

<div class="container contact-strip" data-header-theme="light"><?= component('micro-cta', [
    'text'  => $strip['text'],
    'label' => str_replace('{phone}', (string) config('phone_display'), $strip['label']),
    'url'   => 'tel:' . config('phone_e164'),
]) ?></div>
