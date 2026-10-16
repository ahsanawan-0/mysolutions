<?php
declare(strict_types=1);

/**
 * Contact page /contact-us/ copy (PROMPTS P23, docs/08 §11): hero, contact cards, the quote form (labels, hints, error
 * messages, notices), map, socials, FAQ heading, the call strip, and the two emails (enquiry to the office,
 * auto-reply to the sender). Read via contact('form.labels.name'). Facts (phone, email, address, hours, socials) come
 * from app/config/site.php. Copy marked // PROVISIONAL is ours and awaits the client's approval.
 */
return [
    'seo' => [
        'title'       => 'Contact My Solutionz | Get a Free Quote',
        'description' => 'Contact My Solutionz for tender writing, bid management, compliance and digital support. Call +44 7466 063564, email us or request a free quote online.',
        'og_title'    => 'Contact My Solutionz',
    ],

    'hero' => [
        'title'  => "Let's Talk About",
        'accent' => 'Your Next Contract',
        'lead'   => "Tell us about your opportunity, your deadline and the support you need. We'll come back to you with clear advice and a no-obligation quote.", // PROVISIONAL
    ],

    // Contact cards (icon tile + label + value + corner-notch arrow); values come from config()
    'cards' => [
        'label'    => 'Ways to reach us',
        'call'     => ['label' => 'Call us', 'icon' => 'phone'],
        'whatsapp' => ['label' => 'WhatsApp', 'value' => 'Message us on WhatsApp', 'icon' => 'whatsapp-logo'],
        'email'    => ['label' => 'Email us', 'icon' => 'envelope-simple'],
        'office'   => ['label' => 'Visit our office', 'icon' => 'map-pin', 'link' => 'Open in Google Maps'],
        'hours'    => ['label' => 'Office hours', 'icon' => 'clock', 'email_note' => 'Email us any time'],
        'new_tab'  => '(opens in a new tab)',
    ],

    'form' => [
        'eyebrow'  => 'Free quote',
        'heading'  => 'Request a',
        'accent'   => 'Free Quote',
        'intro'    => "Share a few details about the opportunity and the support you need. We'll come back to you with clear advice and a no-obligation quote.", // PROVISIONAL
        'required' => 'Fields marked * are required.',
        'direct'   => 'Prefer to talk? Call us on {phone} or email {email}.',

        'labels' => [
            'name'      => 'Name',
            'company'   => 'Company',
            'email'     => 'Email',
            'phone'     => 'Phone',
            'sector'    => 'Sector',
            'service'   => 'Support needed',
            'deadline'  => 'Tender deadline',
            'callback'  => 'Preferred call-back time',
            'message'   => 'Message',
            'marketing' => 'Send me occasional insights and updates by email',
            'website'   => 'Leave this field empty',   // honeypot (hidden from people)
        ],
        'hints' => [
            'phone'    => 'So we can call you back if that is easier.',
            'deadline' => 'If you are responding to a tender, when is it due?',
            'message'  => 'Tell us about the opportunity, the buyer and what you need help with. Up to 2,000 characters.',
        ],
        'options' => [
            'sector_any'   => 'Select a sector',
            'sector_other' => 'Other',
            'service_any'  => 'Not sure yet',
            'callback'     => ['' => 'No preference', 'morning' => 'Morning', 'afternoon' => 'Afternoon', 'any' => 'Any time'],
        ],
        'privacy'  => "We'll use your details to respond to your enquiry. See our [Privacy Policy](/privacy-policy/).",
        'submit'   => 'Send Enquiry',
        'counter'  => '{n} of {max} characters',

        'errors' => [
            'summary'          => 'There is a problem with your enquiry',
            'name_required'    => 'Enter your name',
            'name_length'      => 'Your name must be 100 characters or fewer',
            'company_length'   => 'Your company name must be 150 characters or fewer',
            'email_required'   => 'Enter your email address',
            'email_invalid'    => 'Enter an email address in the correct format, like name@example.co.uk',
            'phone_invalid'    => 'Enter a phone number using digits, spaces, + or brackets',
            'sector_invalid'   => 'Choose a sector from the list',
            'service_invalid'  => 'Choose a service from the list',
            'deadline_invalid' => 'Enter the deadline as a real date',
            'deadline_past'    => 'The tender deadline must be today or later',
            'callback_invalid' => 'Choose a call-back time from the list',
            'message_required' => 'Enter your message',
            'message_short'    => 'Your message must be at least 10 characters',
            'message_long'     => 'Your message must be 2,000 characters or fewer',
            'turnstile'        => 'Please confirm you are not a robot and send your enquiry again',
        ],
        'notices' => [
            'rate_limited' => "You've sent several enquiries in the last hour. Please try again later, or call us on {phone}.",
            'send_failed'  => "Sorry, we couldn't send your enquiry just now. Please try again in a few minutes, or email us at {email}.",
        ],
    ],

    'map' => [
        'heading' => 'Find',
        'accent'  => 'Our Office',
        'image'   => 'contact/office-map',   // static styled map (placeholder until generated); never an embed before consent
        'alt'     => 'Map showing the My Solutionz office at Unit A53, 4–6 Greatorex Street, London E1 5NF',
        'link'    => 'Open in Google Maps',
    ],

    'socials' => ['label' => 'Follow us'],

    'faq' => [
        'eyebrow' => 'FAQs',
        'heading' => 'Before You',
        'accent'  => 'Get in Touch',
    ],

    'call_strip' => [
        'text'  => 'Prefer to talk it through?', // PROVISIONAL
        'label' => 'Call {phone}',
    ],

    // The two emails sent on a successful submission (templates/emails/*.php)
    'emails' => [
        'enquiry' => [
            'subject' => 'New enquiry: {service} – {name}',
            'general' => 'General enquiry',
            'heading' => 'New enquiry from the website',
            'labels'  => ['name' => 'Name', 'company' => 'Company', 'email' => 'Email', 'phone' => 'Phone', 'sector' => 'Sector', 'service' => 'Support needed', 'deadline' => 'Tender deadline', 'callback' => 'Preferred call-back time', 'marketing' => 'Marketing emails', 'message' => 'Message'],
            'marketing' => ['yes' => 'Yes, opted in', 'no' => 'No'],
            'footer'  => 'Sent from the contact form on {site}. Reply to this email to answer the sender directly.',
        ],
        'autoreply' => [
            'subject' => 'Thank you for contacting My Solutionz',
            'heading' => 'Thank you, {name}',
            'paras'   => [ // PROVISIONAL
                "We've received your enquiry and will review it carefully.",
                "Here's what happens next: we look at your requirements, contact you to discuss them, and send you a clear, no-obligation quote.",
                'If your deadline is close, please call us on {phone} so we can prioritise your request.',
            ],
            'summary' => 'Your enquiry',
            'contact' => 'My Solutionz · {phone} · {email} · {address}',
            'privacy' => 'We use your details only to respond to your enquiry. Read our privacy policy: {privacy}',
            'signoff' => 'Kind regards,',
            'team'    => 'The My Solutionz team',
        ],
    ],
];
