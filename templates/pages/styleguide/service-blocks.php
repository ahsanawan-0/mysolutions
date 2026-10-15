<?php
declare(strict_types=1);
/**
 * Service blocks styleguide (local only, noindex; PROMPTS P20): the typed service blocks that the category pages do
 * not use yet but the sub-service pages will (P21): `list` (icon cards) and `why` (bold-led benefit rows), plus the
 * dashed "Content pending" block. Copy is the client's Tender & Bid Writing copy
 * (docs/website_content/services/01a-tender-bid-writing.md), so the demos double as P21 previews. The other blocks
 * (intro, text default|mist|band, steps, subs, FAQ, closing CTA band) are live on /services/bid-proposal-management/.
 */
$page = [
    'title'       => 'Service blocks styleguide | My Solutionz (dev)',
    'description' => 'Developer styleguide for the My Solutionz service page blocks: icon list cards, why-choose benefit rows and the content pending placeholder.',
    'canonical'   => '/styleguide/service-blocks/',
    'robots'      => 'noindex, nofollow',
    'status'      => 'draft',
    'breadcrumbs' => [['Home', '/'], ['Styleguide', '/styleguide/'], ['Service blocks', null]],
    'body_class'     => 'page-styleguide page-styleguide-service-blocks',
    'header_theme'   => 'light',
    'header_overlay' => true,
];
$ctx = service_category('bid-proposal-management');
$label = static fn (string $name, string $note): string =>
    '<div class="sg-heroes__label" data-header-theme="light"><div class="container">' . component('eyebrow', ['text' => 'Block: ' . $name]) . '<p class="small">' . e($note) . '</p></div></div>';
?>
<?= component('hero-inner', [
    'variant'     => 'simple',
    'breadcrumbs' => $page['breadcrumbs'],
    'title'       => 'Service page',
    'accent'      => 'blocks',
    'lead'        => 'The typed blocks of app/data/service-pages/*.php that the sub-service pages use (P21). Intro, text, steps, sub-service cards, FAQ and the closing band are live on the Bid & Proposal Management page.',
    'ctas'        => [['Open Bid & Proposal Management', '/services/bid-proposal-management/', 'secondary']],
]) ?>

<?= $label('list', 'Icon cards: two-voice H2, Sub line, intro, 3 × 2 cards (stagger + tilt), closing paragraph. Copy: Tender & Bid Writing.') ?>
<?= render('sections/service/list', ['ctx' => $ctx, 'block' => [
    'type'    => 'list',
    'id'      => 'demo-list',
    'heading' => 'Our Tender &',
    'accent'  => 'Bid Writing Services',
    'sub'     => 'Comprehensive Writing Support for Different Tender Requirements',
    'paras'   => ['We provide specialist writing support across the main elements of public and private sector tender submissions:'],
    'items'   => [
        ['icon' => 'seal-check', 'title' => 'Quality & Technical Responses', 'text' => 'Detailed answers addressing service delivery, quality assurance, workforce management, compliance and performance monitoring.'],
        ['icon' => 'list-numbers', 'title' => 'Method Statements', 'text' => 'Clear explanations of proposed working methods, operational procedures, resources, responsibilities and contract delivery arrangements.'],
        ['icon' => 'hand-heart', 'title' => 'Social Value Responses', 'text' => 'Practical commitments covering local employment, skills development, community benefits, sustainability and measurable social value outcomes.'],
        ['icon' => 'rocket-launch', 'title' => 'Mobilisation & Implementation Plans', 'text' => 'Structured proposals outlining contract commencement, staffing, operational readiness, transition arrangements and implementation milestones.'],
        ['icon' => 'shield-warning', 'title' => 'Risk Management & Business Continuity', 'text' => 'Responses explaining how operational risks, service disruptions and contractual challenges will be managed.'],
        ['icon' => 'folder-open', 'title' => 'Case Studies & Framework Applications', 'text' => 'Evidence-based descriptions of previous contract experience, organisational capabilities and relevant achievements.'],
    ],
    'outro'   => "Each response is developed around your business, the contract specification and the buyer's evaluation criteria.",
]]) ?>

<?= $label('why', 'Benefit rows: icon + one paragraph per item, the bold words run into the client\'s sentence. Copy: Tender & Bid Writing.') ?>
<?= render('sections/service/why', ['ctx' => $ctx, 'block' => [
    'type'    => 'why',
    'id'      => 'demo-why',
    'heading' => 'Why Choose My Solutionz',
    'accent'  => 'for Tender Writing?',
    'sub'     => 'Specialist Expertise Without the Pressure of Writing Everything Yourself',
    'paras'   => [
        'Preparing a competitive tender can place considerable demands on business owners and management teams, particularly when submissions involve extensive technical questions and demanding deadlines.',
        'Our outsourced tender writing services provide professional support without the need to employ a permanent in-house bid writer.',
    ],
    'lead_in' => 'We help you:',
    'items'   => [
        ['icon' => 'clock', 'strong' => 'Save valuable time', 'text' => 'by taking responsibility for preparing and refining written responses.'],
        ['icon' => 'trend-up', 'strong' => 'Strengthen submission quality', 'text' => 'through clear explanations, relevant evidence and close attention to evaluation criteria.'],
        ['icon' => 'users-three', 'strong' => 'Access specialist expertise', 'text' => 'supported by commercial understanding and multi-sector experience.'],
        ['icon' => 'sliders-horizontal', 'strong' => 'Receive flexible assistance', 'text' => ', whether you need a complete written submission or support with selected tender questions.'],
    ],
    'outro'   => 'We work closely with your team throughout the assignment, allowing you to concentrate on running your business while we develop the agreed tender responses.',
]]) ?>

<?= $label('pending', 'A block whose data holds a [[PENDING: …]] marker: shown like this off production, omitted on production.') ?>
<?= render('sections/service/pending', [
    'block' => ['type' => 'steps', 'heading' => 'How We', 'accent' => 'Support You'],
    'note'  => '5–6 process steps (title + one line each)',
]) ?>
