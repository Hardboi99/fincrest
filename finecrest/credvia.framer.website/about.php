<?php
$base = '';
$page = 'about';
$title = 'About Us';
$description = 'Fincrest 360 helps individuals and businesses explore the right financing options with clarity and care. Trust. Finance. Prosperity.';
require __DIR__ . '/includes/header.php';

$values = [
    ['Integrity', 'We communicate honestly and keep our guidance clear.'],
    ['Customer first', 'Your requirement shapes the options we help you explore.'],
    ['Transparency', 'Plain-language information at every step of your enquiry.'],
    ['Support', 'Guidance from your first question to the lending institution.'],
];
$does = [
    ['Understand', 'We start with your goal, whether it is a home, a vehicle, gold, a business need or a personal requirement.'],
    ['Guide', 'We help you understand loan types, estimate your EMI and prepare the details lenders commonly ask for.'],
    ['Connect', 'We help you continue with the relevant lending institution, which makes the final decision on approval and terms.'],
];
?>

<section class="page-hero page-hero--simple">
  <div class="hero-lines" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="container">
    <ol class="breadcrumb"><li><a href="<?= e(url('index.php')) ?>">Home</a></li><li aria-current="page">About Us</li></ol>
    <h1>Financial guidance for a <em>brighter tomorrow.</em></h1>
    <p class="page-lead">Fincrest 360 helps individuals and businesses explore the right financing options with clarity and care.</p>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="split-media reveal"><img src="<?= e(asset('img/photos/team.jpg')) ?>" width="1400" height="1050" loading="lazy" alt="A team collaborating in an office"></div>
    <div class="split-copy reveal">
      <span class="eyebrow">Our story</span>
      <h2>Trust. Finance. Prosperity.</h2>
      <p>Finance should open doors, not create barriers. At Fincrest 360, we help people and businesses explore financing with clarity.</p>
      <p>From home and car loans to gold, personal and business financing, our aim is to make the journey simpler to understand and easier to follow.</p>
      <a class="btn mt-lg" href="<?= e(url('index.php#loans')) ?>">Explore our loans <?= icon('arrow') ?></a>
    </div>
  </div>
</section>

<section class="section section--white">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">What we do</span><h2>Our approach</h2></div>
    <div class="steps">
      <?php foreach ($does as $d): ?><div class="step"><h3><?= e($d[0]) ?></h3><p><?= e($d[1]) ?></p></div><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Our values</span><h2>Principles that guide us</h2></div>
    <div class="feature-grid values">
      <?php foreach ($values as $i => $v): ?>
        <article class="feature reveal"><span class="feature-num">0<?= $i + 1 ?></span><h3><?= e($v[0]) ?></h3><p><?= e($v[1]) ?></p></article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
