<?php
$base = '';
$page = 'calculators';
$title = 'EMI Calculator';
$description = 'Estimate your monthly loan EMI, total interest and total repayment with the Fincrest 360 EMI calculator.';
$scripts = ['calculator'];
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero page-hero--simple">
  <div class="hero-lines" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="container">
    <ol class="breadcrumb"><li><a href="<?= e(url('index.php')) ?>">Home</a></li><li aria-current="page">EMI Calculator</li></ol>
    <h1>Plan smarter. <em>Borrow better.</em></h1>
    <p class="page-lead">See your monthly EMI, total interest and total repayment instantly as you adjust the values.</p>
  </div>
</section>

<section class="section">
  <div class="container reveal"><?php require __DIR__ . '/includes/calculator.php'; ?></div>
</section>

<section class="section section--white">
  <div class="container split split--top">
    <div class="split-copy reveal">
      <span class="eyebrow">Understanding EMI</span>
      <h2>How your EMI is calculated</h2>
      <p>EMI stands for Equated Monthly Instalment. This calculator uses the standard reducing-balance formula:</p>
      <p><strong>EMI = P &times; r &times; (1 + r)<sup>n</sup> &divide; ((1 + r)<sup>n</sup> &minus; 1)</strong></p>
      <p>Here P is the loan amount, r is the monthly interest rate (annual rate &divide; 12 &divide; 100) and n is the number of monthly instalments.</p>
    </div>
    <div class="panel reveal">
      <h3>Good to know</h3>
      <ul class="check-list">
        <li>A longer tenure lowers your EMI but increases the total interest.</li>
        <li>A higher loan amount or interest rate increases your EMI.</li>
        <li>Results are illustrative. The lending institution decides the actual rate, charges and eligibility.</li>
      </ul>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Apply</span><h2>Ready to explore a loan?</h2></div>
    <div class="chip-row">
      <?php foreach ($LOANS as $s => $l): ?><a class="chip" href="<?= e(loan_url($s)) ?>"><span><?= icon($l['icon']) ?></span><?= e($l['title']) ?></a><?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
