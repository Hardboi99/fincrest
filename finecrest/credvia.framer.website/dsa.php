<?php
$base = '';
$page = 'dsa';
$title = 'Become a DSA';
$description = 'Partner with Fincrest 360 as a Direct Selling Agent (DSA) and help customers explore home, car, bike, gold, personal, property and business loans.';
$scripts = ['form'];
$formType = 'dsa';
$formTitle = 'DSA partnership enquiry';
$formIntro = 'Tell us a little about yourself and our team will get in touch.';
require __DIR__ . '/includes/header.php';

$benefits = [
    ['A wide loan range', 'Help your customers explore home, car, bike, gold, personal, property and business loans.'],
    ['Clear communication', 'Straightforward information about the process, so you can guide customers with confidence.'],
    ['Customer-focused', 'A partnership built around helping customers find suitable options.'],
    ['Team support', 'Guidance from the Fincrest 360 team as you work with customers.'],
];
$steps = [
    ['Connect with us', 'Fill in the enquiry form below.'],
    ['Share your details', 'Tell us about yourself and your background.'],
    ['Discuss and onboard', 'Our team will explain the process and next steps.'],
    ['Help customers', 'Guide customers towards suitable loan options.'],
];
?>

<section class="page-hero">
  <div class="hero-lines" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="container">
    <ol class="breadcrumb"><li><a href="<?= e(url('index.php')) ?>">Home</a></li><li aria-current="page">DSA</li></ol>
    <div class="page-hero-grid">
      <div>
        <span class="slide-kicker">DSA partnership</span>
        <h1>Partner with Fincrest 360 as a <em>DSA.</em></h1>
        <p class="page-lead">Partner with us as a Direct Selling Agent and help customers explore suitable loan options.</p>
        <div class="page-actions"><a class="btn btn--gold" href="#enquire">Become a DSA <?= icon('arrow') ?></a><a class="btn btn--ghost" href="#process">How it works</a></div>
      </div>
      <div class="page-media"><img src="<?= e(asset('img/photos/advisor.jpg')) ?>" width="700" height="394" alt="Advisors discussing financing with a client" fetchpriority="high"></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Why partner with us</span><h2>Grow with a customer-focused team</h2></div>
    <div class="feature-grid">
      <?php foreach ($benefits as $i => $b): ?><article class="feature reveal"><span class="feature-num">0<?= $i + 1 ?></span><h3><?= e($b[0]) ?></h3><p><?= e($b[1]) ?></p></article><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--white" id="process">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">How it works</span><h2>A clear path to partnership</h2></div>
    <ol class="steps"><?php foreach ($steps as $s): ?><li class="step"><h3><?= e($s[0]) ?></h3><p><?= e($s[1]) ?></p></li><?php endforeach; ?></ol>
  </div>
</section>

<section class="section" id="enquire">
  <div class="container enquiry">
    <div class="enquiry-copy reveal">
      <span class="eyebrow">Start a conversation</span>
      <h2>Let&rsquo;s explore what we can build together</h2>
      <p>Share a few details and our partnerships team will be in touch with the next steps.</p>
    </div>
    <div class="reveal"><?php require __DIR__ . '/includes/enquiry-form.php'; ?></div>
  </div>
</section>

<?php $noCta = true; require __DIR__ . '/includes/footer.php'; ?>
