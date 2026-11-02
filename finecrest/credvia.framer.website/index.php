<?php
$base = '';
$page = 'home';
$title = 'Fincrest 360 | Trust. Finance. Prosperity.';
$description = 'Explore home, car, bike, business, personal, gold and property loans with Fincrest 360. Share your requirement and get guidance on suitable financing options.';
$scripts = ['calculator'];
require __DIR__ . '/includes/header.php';

$slides = [
    [
        'img' => 'hero-1.jpg', 'kicker' => 'Trust. Finance. Prosperity.',
        'h1' => 'Your Financial Goals. <em>Our Commitment.</em>',
        'text' => 'Discover financial solutions designed around your ambitions. From personal requirements to business expansion, explore the right financing options with Fincrest 360.',
        'cta' => ['Explore Loan Solutions', '#loans'], 'alt' => ['Calculate EMI', '#emi'],
    ],
    [
        'img' => 'hero-2.jpg', 'kicker' => 'Gold Loan',
        'h1' => 'Make your gold <em>work for you.</em>',
        'text' => 'Quick financial support against your gold. Share your requirement and explore suitable options with Fincrest 360.',
        'cta' => ['Explore Gold Loan', loan_url('gold-loan')], 'alt' => ['Calculate EMI', '#emi'],
    ],
    [
        'img' => 'hero-3.jpg', 'kicker' => 'Car Loan',
        'h1' => 'Drive home your <em>new car.</em>',
        'text' => 'Explore vehicle financing, estimate your monthly payment and take a clear path to your next car.',
        'cta' => ['Explore Car Loan', loan_url('car-loan')], 'alt' => ['Calculate EMI', '#emi'],
    ],
];
$stats = [
    [7, '', 'Loan solutions', 'Home, car, bike, business, personal, property and gold'],
    [2, '', 'Loan types', 'Secured and unsecured financing options'],
    [4, '', 'Simple steps', 'From your requirement to the lending institution'],
    [360, '&deg;', 'Customer focus', 'Guidance and communication at every step'],
];
$why = [
    ['Multiple financing options', 'Explore home, car, bike, business, personal, property and gold loans in one place.'],
    ['Simple enquiry journey', 'Share your requirement in a few easy steps.'],
    ['Transparent communication', 'Clear information at every step of your enquiry.'],
    ['Personalised guidance', 'Support shaped around your goals and financial journey.'],
];
$steps = [
    ['Share Your Requirement', 'Tell us the type of loan and the amount you are looking for.'],
    ['Submit Basic Details', 'Provide your basic details so we can understand your needs.'],
    ['Explore Suitable Loan Options', 'Review financing options that fit your requirement.'],
    ['Proceed with the Lending Institution', 'Continue with the relevant lending institution to complete the process.'],
];
$faqs = [
    ['Which loans can I explore with Fincrest 360?', 'You can explore home loans, loan against property, car loans, bike loans, gold loans, personal loans and business loans.'],
    ['Who decides the interest rate, tenure and approval?', 'The relevant lending institution decides these based on its own policies and your profile. Fincrest 360 helps you understand your options and guides you through the process.'],
    ['How do I get started?', 'Fill in the enquiry form with your requirement. Our team will get in touch with you with the next steps.'],
    ['What documents are commonly required?', 'Lenders commonly ask for identity proof, address proof, income proof or bank statements and photographs. The exact list depends on the loan type and the lending institution.'],
    ['How is the EMI calculated?', 'The calculator on this page uses the standard reducing-balance formula. It is an illustration only; your actual EMI depends on the rate and terms offered by the lender.'],
    ['What is a DSA?', 'A DSA (Direct Selling Agent) is a partner who helps customers explore loan options. If you would like to partner with Fincrest 360, see the DSA page.'],
];
?>

<section class="hero" data-carousel role="region" aria-roledescription="carousel" aria-label="Featured loan solutions">
  <?php foreach ($slides as $i => $s): ?>
    <article class="slide<?= $i === 0 ? ' is-active' : '' ?>" aria-roledescription="slide" aria-label="<?= $i + 1 ?> of <?= count($slides) ?>">
      <img class="slide-bg" src="<?= e(asset('img/' . $s['img'])) ?>" width="1672" height="941" alt=""<?= $i === 0 ? ' fetchpriority="high"' : ' loading="lazy"' ?>>
      <div class="container">
        <div class="slide-copy">
          <span class="slide-kicker"><?= e($s['kicker']) ?></span>
          <h1><?= $s['h1'] ?></h1>
          <p><?= e($s['text']) ?></p>
          <div class="slide-actions">
            <a class="btn btn--gold" href="<?= e($s['cta'][1]) ?>"><?= e($s['cta'][0]) ?> <?= icon('arrow') ?></a>
            <a class="btn btn--ghost" href="<?= e($s['alt'][1]) ?>"><?= e($s['alt'][0]) ?></a>
          </div>
        </div>
      </div>
    </article>
  <?php endforeach; ?>
  <div class="hero-lines" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="hero-note"><small>Why Fincrest 360</small><strong>Guidance at every step</strong><span>Share your requirement and explore suitable options.</span></div>
  <div class="hero-controls">
    <button class="hero-btn" type="button" data-prev aria-label="Previous slide"><?= icon('left') ?></button>
    <button class="hero-btn" type="button" data-next aria-label="Next slide"><?= icon('right') ?></button>
    <div class="hero-dots"><?php foreach ($slides as $i => $s): ?><button class="hero-dot<?= $i === 0 ? ' is-active' : '' ?>" type="button" aria-label="Go to slide <?= $i + 1 ?>"></button><?php endforeach; ?></div>
  </div>
</section>

<section class="stats" aria-label="Fincrest 360 at a glance">
  <div class="container">
    <div class="stats-grid">
      <?php foreach ($stats as $i => $s): ?>
        <div class="stat reveal">
          <strong data-count="<?= $s[0] ?>" data-suffix="<?= e(html_entity_decode($s[1])) ?>">0<?= $s[1] ?></strong>
          <b><?= e($s[2]) ?></b><span><?= e($s[3]) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section" id="loans">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Loan solutions</span>
      <h2>Financing for what matters most</h2>
      <p>Choose the loan that fits your goal and explore it in detail.</p>
    </div>
    <div class="loan-grid">
      <?php $n = 0; foreach ($LOANS as $slug => $loan): ?>
        <a class="loan-card reveal" href="<?= e(loan_url($slug)) ?>">
          <div class="loan-media">
            <img src="<?= e(asset('img/photos/' . $loan['img'])) ?>" width="700" height="525" loading="lazy" alt="<?= e($loan['title']) ?>">
            <span class="loan-badge"><?= icon($loan['icon']) ?></span>
          </div>
          <div class="loan-body">
            <h3><?= e($loan['title']) ?></h3>
            <p><?= e($loan['desc']) ?></p>
            <span class="link-arrow">Explore More <?= icon('arrow') ?></span>
          </div>
        </a>
      <?php endforeach; ?>
      <div class="loan-card loan-card--promo reveal">
        <h3>Not sure which loan fits you?</h3>
        <p>Share your requirement and we will help you explore suitable options.</p>
        <a class="btn btn--gold" href="<?= e(url('contact.php#enquire')) ?>">Talk to us <?= icon('arrow') ?></a>
      </div>
    </div>
  </div>
</section>

<section class="section section--green">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Why Fincrest 360</span>
      <h2>Financial guidance you can trust</h2>
      <p>We combine personalised guidance with a range of financing options to help you move forward with clarity and confidence.</p>
    </div>
    <div class="feature-grid">
      <?php foreach ($why as $i => $w): ?>
        <article class="feature reveal"><span class="feature-num">0<?= $i + 1 ?></span><h3><?= e($w[0]) ?></h3><p><?= e($w[1]) ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">How it works</span>
      <h2>Your loan journey in four simple steps</h2>
      <p>A simple, guided path from your requirement to the right lending institution.</p>
    </div>
    <ol class="steps">
      <?php foreach ($steps as $s): ?>
        <li class="step"><h3><?= e($s[0]) ?></h3><p><?= e($s[1]) ?></p></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<section class="section section--white" id="emi">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">EMI calculator</span>
      <h2>Plan it. Price it. Compare it.</h2>
      <p>Adjust the amount, rate and tenure to see your monthly EMI instantly.</p>
    </div>
    <div class="reveal"><?php require __DIR__ . '/includes/calculator.php'; ?></div>
  </div>
</section>

<section class="section">
  <div class="container split split--top">
    <div class="reveal">
      <span class="eyebrow">FAQs</span>
      <h2>Common questions</h2>
      <p class="note">Clear answers before you calculate or continue with a lending institution.</p>
      <a class="btn btn--outline mt-lg" href="<?= e(url('resources.php#faqs')) ?>">More questions</a>
    </div>
    <div class="faq reveal">
      <?php foreach ($faqs as $f): ?>
        <details><summary><?= e($f[0]) ?></summary><p><?= e($f[1]) ?></p></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
