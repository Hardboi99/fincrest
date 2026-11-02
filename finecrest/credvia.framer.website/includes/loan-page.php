<?php
/**
 * Layout for every loan page. A page file only sets $slug and includes this file.
 * Text comes from includes/loan-content.php, names/icons from includes/data.php.
 */
$base = '../';
require_once __DIR__ . '/data.php';
$content = (require __DIR__ . '/loan-content.php')[$slug];
$loan = $LOANS[$slug];

$page = 'loan';
$title = $loan['title'];
$description = strip_tags($content['lead']) . ' Explore ' . strtolower($loan['title']) . ' options with ' . $CONFIG['name'] . '.';
$ogImage = 'img/hero-1.jpg';
$scripts = ['calculator', 'form'];
$calc = $content['calc'];
$formType = 'loan';
$formLoan = $slug;
$formTitle = 'Enquire about a ' . $loan['title'];
$formIntro = 'Share your requirement and our team will get in touch with you.';
require __DIR__ . '/header.php';
$steps = [
    ['Share Your Requirement', 'Tell us the loan type and amount.'],
    ['Submit Basic Details', 'Provide your basic information.'],
    ['Explore Suitable Options', 'Review options that fit your need.'],
    ['Proceed with the Lender', 'Continue with the relevant lending institution.'],
];
?>

<section class="page-hero">
  <div class="hero-lines" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="container">
    <ol class="breadcrumb">
      <li><a href="<?= e(url('index.php')) ?>">Home</a></li>
      <li><a href="<?= e(url('index.php#loans')) ?>">Loans</a></li>
      <li aria-current="page"><?= e($loan['title']) ?></li>
    </ol>
    <div class="page-hero-grid">
      <div>
        <span class="slide-kicker"><?= e($loan['group']) ?></span>
        <h1><?= $content['h1'] ?></h1>
        <p class="page-lead"><?= e($content['lead']) ?></p>
        <div class="page-actions">
          <a class="btn btn--gold" href="#enquire">Enquire Now <?= icon('arrow') ?></a>
          <a class="btn btn--ghost" href="#emi">Calculate EMI</a>
        </div>
      </div>
      <div class="page-media"><img src="<?= e(asset('img/photos/' . $content['img'])) ?>" width="1200" height="900" alt="<?= e($loan['title']) ?>" fetchpriority="high"></div>
    </div>
  </div>
</section>

<section class="highlights">
  <div class="container"><div class="highlight-row">
    <?php foreach ($content['highlights'] as $h): ?>
      <div class="highlight reveal"><?= icon('check') ?><?= e($h) ?></div>
    <?php endforeach; ?>
  </div></div>
</section>

<section class="section">
  <div class="container split">
    <div class="reveal">
      <span class="eyebrow">About this loan</span>
      <h2>What is a <?= e(strtolower($loan['title'])) ?>?</h2>
    </div>
    <div class="split-copy reveal">
      <?php foreach ($content['intro'] as $p): ?><p><?= e($p) ?></p><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Features</span><h2>How <?= e($CONFIG['name']) ?> helps</h2></div>
    <div class="feature-grid">
      <?php foreach ($content['features'] as $i => $f): ?>
        <article class="feature reveal"><span class="feature-num">0<?= $i + 1 ?></span><h3><?= e($f[0]) ?></h3><p><?= e($f[1]) ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container two-col">
    <div class="panel reveal">
      <h3>Eligibility</h3>
      <ul class="check-list"><?php foreach ($content['eligibility'] as $x): ?><li><?= e($x) ?></li><?php endforeach; ?></ul>
      <p class="note">Indicative only. Final eligibility is decided by the lending institution.</p>
    </div>
    <div class="panel reveal">
      <h3>Documents commonly required</h3>
      <ul class="check-list"><?php foreach ($content['documents'] as $x): ?><li><?= e($x) ?></li><?php endforeach; ?></ul>
      <p class="note">The exact list may vary by lending institution.</p>
    </div>
  </div>
</section>

<section class="section section--white" id="emi">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">EMI calculator</span><h2>Plan your monthly instalment</h2><p>Adjust the amount, rate and tenure to see an illustrative EMI.</p></div>
    <div class="reveal"><?php require __DIR__ . '/calculator.php'; ?></div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">How it works</span><h2>Four simple steps</h2></div>
    <ol class="steps"><?php foreach ($steps as $s): ?><li class="step"><h3><?= e($s[0]) ?></h3><p><?= e($s[1]) ?></p></li><?php endforeach; ?></ol>
  </div>
</section>

<section class="section section--white">
  <div class="container split split--top">
    <div class="reveal"><span class="eyebrow">FAQs</span><h2>Common questions</h2></div>
    <div class="faq reveal">
      <?php foreach ($content['faqs'] as $f): ?><details><summary><?= e($f[0]) ?></summary><p><?= e($f[1]) ?></p></details><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="enquire">
  <div class="container enquiry">
    <div class="enquiry-copy reveal">
      <span class="eyebrow">Apply now</span>
      <h2>Ready for your <?= e(strtolower($loan['title'])) ?>?</h2>
      <p>Share your requirement and explore the right financing options with <?= e($CONFIG['name']) ?>.</p>
      <ul class="check-list"><li>Simple enquiry, no lengthy forms</li><li>Guidance from enquiry to the lending institution</li><li>Clear communication at every step</li></ul>
    </div>
    <div class="reveal"><?php require __DIR__ . '/enquiry-form.php'; ?></div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Explore other loans</span><h2>Looking for something else?</h2></div>
    <div class="chip-row">
      <?php foreach ($LOANS as $s => $l): if ($s === $slug) continue; ?>
        <a class="chip" href="<?= e(loan_url($s)) ?>"><span><?= icon($l['icon']) ?></span><?= e($l['title']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php $noCta = false; require __DIR__ . '/footer.php'; ?>
