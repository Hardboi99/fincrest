<?php
/**
 * Opens the page: <head>, skip link, site header, mobile menu.
 * Variables set by each page before including this file:
 *   $base (string), $title (string), $description (string), $page (string, e.g. 'home'), $ogImage (optional)
 */
require_once __DIR__ . '/data.php';

$siteName = $CONFIG['name'];
$fullTitle = ($page ?? '') === 'home' ? $title : $title . ' | ' . $siteName;
$ogImage = $ogImage ?? 'assets/img/hero-1.jpg';

/** Group loans for the mega menu. */
$groups = [];
foreach ($LOANS as $ls => $lo) {
    $groups[$lo['group']][$ls] = $lo;
}

/** Renders one menu row. */
function menu_item(string $href, string $icon, string $title, string $desc): string
{
    return '<li><a class="menu-item" href="' . e($href) . '"><span class="menu-ico">' . icon($icon) . '</span>'
        . '<span class="menu-text"><strong>' . e($title) . '</strong><span>' . e($desc) . '</span></span>'
        . '<span class="menu-arrow">' . icon('arrow') . '</span></a></li>';
}
function cur(string $name): string
{
    global $page;
    return ($page ?? '') === $name ? ' aria-current="page"' : '';
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($description) ?>">
<meta name="theme-color" content="#07391f">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e($siteName) ?>">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:description" content="<?= e($description) ?>">
<meta property="og:image" content="<?= e(asset('img/' . basename($ogImage))) ?>">
<link rel="icon" type="image/svg+xml" href="<?= e(asset('img/favicon.svg')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Montserrat:wght@600;700;800&amp;display=swap">
<link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/layout.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/components.css')) ?>">
<link rel="stylesheet" href="<?= e(asset('css/pages.css')) ?>">
</head>
<body>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" id="site-header">
  <div class="header-bar">
    <a class="brand" href="<?= e(url('index.php')) ?>" aria-label="<?= e($siteName) ?> home">
      <img src="<?= e(asset('img/fincrest360-logo.png')) ?>" width="171" height="44" alt="<?= e($siteName) ?> — <?= e($CONFIG['tagline']) ?>">
    </a>

    <nav class="main-nav" aria-label="Primary">
      <ul class="nav-list">
        <li class="nav-item"><a class="nav-link" href="<?= e(url('index.php')) ?>"<?= cur('home') ?>>Home</a></li>

        <li class="nav-item" data-menu>
          <button class="nav-link" type="button" aria-expanded="false" aria-controls="menu-loans">Loans <span class="chev"><?= icon('chev') ?></span></button>
          <div class="mega" id="menu-loans">
            <div class="mega-card">
              <?php foreach ($groups as $groupName => $list): ?>
                <div class="mega-col">
                  <p class="mega-title"><?= e($groupName) ?></p>
                  <ul class="menu-list">
                    <?php foreach ($list as $ls => $lo) echo menu_item(loan_url($ls), $lo['icon'], $lo['title'], $lo['desc']); ?>
                  </ul>
                </div>
              <?php endforeach; ?>
              <aside class="mega-promo">
                <small><?= e($siteName) ?></small>
                <h3>Not sure which loan fits you?</h3>
                <p>Share your requirement and we will help you explore suitable options.</p>
                <div class="promo-links"><a href="<?= e(url('contact.php')) ?>">Talk to us</a><a href="<?= e(url('calculators.php')) ?>">Calculate EMI</a></div>
              </aside>
            </div>
          </div>
        </li>

        <li class="nav-item nav-item--rel" data-menu>
          <button class="nav-link" type="button" aria-expanded="false" aria-controls="menu-dsa">DSA <span class="chev"><?= icon('chev') ?></span></button>
          <div class="mega mega--simple" id="menu-dsa"><div class="mega-card"><ul class="menu-list">
            <?php foreach ($DSA_MENU as $m) echo menu_item(url($m['href']), $m['icon'], $m['title'], $m['desc']); ?>
          </ul></div></div>
        </li>

        <li class="nav-item"><a class="nav-link" href="<?= e(url('about.php')) ?>"<?= cur('about') ?>>About Us</a></li>

        <li class="nav-item nav-item--rel" data-menu>
          <button class="nav-link" type="button" aria-expanded="false" aria-controls="menu-res">Resources <span class="chev"><?= icon('chev') ?></span></button>
          <div class="mega mega--simple" id="menu-res"><div class="mega-card"><ul class="menu-list">
            <?php foreach ($RESOURCES_MENU as $m) echo menu_item(url($m['href']), $m['icon'], $m['title'], $m['desc']); ?>
          </ul></div></div>
        </li>

        <li class="nav-item"><a class="nav-link" href="<?= e(url('contact.php')) ?>"<?= cur('contact') ?>>Contact Us</a></li>
      </ul>
    </nav>

    <a class="btn header-cta" href="<?= e(url('contact.php#enquire')) ?>">Apply Now <?= icon('arrow') ?></a>
    <button class="burger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobile-menu"><span></span></button>
  </div>
</header>

<div class="mobile-menu" id="mobile-menu">
  <div class="m-item"><a class="m-link" href="<?= e(url('index.php')) ?>">Home</a></div>
  <div class="m-item">
    <button class="m-link" type="button" aria-expanded="false">Loans <span class="chev"><?= icon('chev') ?></span></button>
    <div class="m-sub"><div>
      <?php foreach ($groups as $groupName => $list): ?>
        <p class="m-group"><?= e($groupName) ?></p>
        <ul class="menu-list"><?php foreach ($list as $ls => $lo) echo menu_item(loan_url($ls), $lo['icon'], $lo['title'], $lo['desc']); ?></ul>
      <?php endforeach; ?>
    </div></div>
  </div>
  <div class="m-item">
    <button class="m-link" type="button" aria-expanded="false">DSA <span class="chev"><?= icon('chev') ?></span></button>
    <div class="m-sub"><div><ul class="menu-list"><?php foreach ($DSA_MENU as $m) echo menu_item(url($m['href']), $m['icon'], $m['title'], $m['desc']); ?></ul></div></div>
  </div>
  <div class="m-item"><a class="m-link" href="<?= e(url('about.php')) ?>">About Us</a></div>
  <div class="m-item">
    <button class="m-link" type="button" aria-expanded="false">Resources <span class="chev"><?= icon('chev') ?></span></button>
    <div class="m-sub"><div><ul class="menu-list"><?php foreach ($RESOURCES_MENU as $m) echo menu_item(url($m['href']), $m['icon'], $m['title'], $m['desc']); ?></ul></div></div>
  </div>
  <div class="m-item"><a class="m-link" href="<?= e(url('contact.php')) ?>">Contact Us</a></div>
  <a class="btn btn--block m-cta" href="<?= e(url('contact.php#enquire')) ?>">Apply Now <?= icon('arrow') ?></a>
</div>

<main id="main">
