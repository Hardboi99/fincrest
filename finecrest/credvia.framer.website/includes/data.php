<?php
/**
 * FINCREST 360 — shared data and helpers.
 * Single source of truth for the loan list, navigation items and icons.
 */

$CONFIG = require __DIR__ . '/config.php';

/** Escape for HTML output. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/** Inline SVG icon (stroke style). */
function icon(string $name): string
{
    static $paths = [
        'home'     => '<path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>',
        'car'      => '<path d="M3 13l2-6h14l2 6v5h-3v-2H6v2H3z"/><circle cx="7.5" cy="14.5" r="1"/><circle cx="16.5" cy="14.5" r="1"/>',
        'bike'     => '<circle cx="6" cy="16" r="3.5"/><circle cx="18" cy="16" r="3.5"/><path d="M6 16l4-7h5l3 7M10 9L9 6H7M14.5 9L11 16"/>',
        'business' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2M3 13h18"/>',
        'personal' => '<circle cx="12" cy="8" r="4"/><path d="M4 20c1-4 4-6 8-6s7 2 8 6"/>',
        'lap'      => '<path d="M4 21V8l8-5 8 5v13"/><path d="M9 21v-5h6v5M9 11h.01M15 11h.01"/>',
        'gold'     => '<ellipse cx="12" cy="7" rx="7" ry="3"/><path d="M5 7v5c0 1.7 3.1 3 7 3s7-1.3 7-3V7M5 12v5c0 1.7 3.1 3 7 3s7-1.3 7-3v-5"/>',
        'doc'      => '<path d="M7 3h7l4 4v14H7z"/><path d="M14 3v4h4M10 12h5M10 16h5"/>',
        'calc'     => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M8 7h8M8 12h.01M12 12h.01M16 12h.01M8 16h.01M12 16h.01M16 16h.01"/>',
        'help'     => '<circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a2.5 2.5 0 115 .5c0 1.5-2.5 2-2.5 3.5M12 17h.01"/>',
        'handshake'=> '<path d="M3 12l4-4 4 2 3-2 4 3 3-1"/><path d="M7 8l-4 4 6 6 2-1 2 1 4-4"/>',
        'shield'   => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/>',
        'check'    => '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>',
        'chat'     => '<path d="M4 5h16v11H9l-5 4z"/>',
        'pin'      => '<path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        'layers'   => '<path d="M12 3l9 5-9 5-9-5z"/><path d="M3 13l9 5 9-5"/>',
        'route'    => '<circle cx="6" cy="18" r="2.5"/><circle cx="18" cy="6" r="2.5"/><path d="M8.5 18H15a3 3 0 000-6H9a3 3 0 010-6h6.5"/>',
        'eye'      => '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/>',
        'compass'  => '<circle cx="12" cy="12" r="9"/><path d="M15.5 8.5l-2 5-5 2 2-5z"/>',
        'edit'     => '<path d="M4 20h4L19 9l-4-4L4 16z"/><path d="M13 7l4 4"/>',
        'search'   => '<circle cx="11" cy="11" r="6"/><path d="M20 20l-4.5-4.5"/>',
        'link'     => '<path d="M10 14a4 4 0 005.7 0l3-3a4 4 0 00-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 00-5.7 0l-3 3a4 4 0 005.7 5.7l1-1"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'chev'     => '<path d="M6 9l6 6 6-6"/>',
        'left'     => '<path d="M15 6l-6 6 6 6"/>',
        'right'    => '<path d="M9 6l6 6-6 6"/>',
    ];
    $extra = $name === 'arrow' || $name === 'chev' ? ' stroke-width="2.3"' : '';
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"' . $extra
        . ' stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
}

/**
 * Loan products. `group` decides the mega-menu column.
 * Page content for each loan lives in includes/loan-content.php.
 */
$LOANS = [
    'home-loan' => [
        'title' => 'Home Loan', 'group' => 'Secured Loans', 'icon' => 'home', 'img' => 'home.jpg',
        'desc'  => 'Financing solutions for your home ownership journey.',
    ],
    'loan-against-property' => [
        'title' => 'Loan Against Property', 'group' => 'Secured Loans', 'icon' => 'lap', 'img' => 'property.jpg',
        'desc'  => 'Unlock the financial potential of your property.',
    ],
    'car-loan' => [
        'title' => 'Car Loan', 'group' => 'Secured Loans', 'icon' => 'car', 'img' => 'car.jpg',
        'desc'  => 'Make your next vehicle purchase easier.',
    ],
    'bike-loan' => [
        'title' => 'Bike Loan', 'group' => 'Secured Loans', 'icon' => 'bike', 'img' => 'bike.svg',
        'desc'  => 'Ride home your two-wheeler with simple financing.',
    ],
    'gold-loan' => [
        'title' => 'Gold Loan', 'group' => 'Secured Loans', 'icon' => 'gold', 'img' => 'gold.jpg',
        'desc'  => 'Quick financial support against your gold.',
    ],
    'personal-loan' => [
        'title' => 'Personal Loan', 'group' => 'Unsecured Loans', 'icon' => 'personal', 'img' => 'personal.jpg',
        'desc'  => 'Financial support for your personal needs.',
    ],
    'business-loan' => [
        'title' => 'Business Loan', 'group' => 'Unsecured Loans', 'icon' => 'business', 'img' => 'business.jpg',
        'desc'  => 'Finance for business growth and expansion.',
    ],
];

/** Simple dropdown menus. */
$DSA_MENU = [
    ['icon' => 'handshake', 'title' => 'Become a DSA',  'desc' => 'Partner with Fincrest 360 as a Direct Selling Agent.', 'href' => 'dsa.php'],
    ['icon' => 'doc',       'title' => 'How it works',  'desc' => 'The steps from enquiry to onboarding.',                'href' => 'dsa.php#process'],
    ['icon' => 'help',      'title' => 'DSA enquiry',   'desc' => 'Tell us about yourself and we will get in touch.',     'href' => 'dsa.php#enquire'],
];
$RESOURCES_MENU = [
    ['icon' => 'calc', 'title' => 'EMI Calculator', 'desc' => 'Plan your monthly repayments.',        'href' => 'calculators.php'],
    ['icon' => 'help', 'title' => 'FAQs',           'desc' => 'Answers to common loan questions.',     'href' => 'resources.php#faqs'],
    ['icon' => 'doc',  'title' => 'Document checklist', 'desc' => 'What lenders commonly ask for.',   'href' => 'resources.php#documents'],
];

/** URL helper: $base is '' for root pages and '../' for pages in /loans/. */
function url(string $path): string
{
    global $base;
    return ($base ?? '') . $path;
}
function loan_url(string $slug): string
{
    return url('loans/' . $slug . '.php');
}
function asset(string $path): string
{
    return url('assets/' . $path);
}
