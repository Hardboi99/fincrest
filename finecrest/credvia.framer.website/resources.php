<?php
$base = '';
$page = 'resources';
$title = 'Resources, FAQs & Documents';
$description = 'Answers to common loan questions and a checklist of documents lenders commonly ask for, from Fincrest 360.';
require __DIR__ . '/includes/header.php';

$faqs = [
    ['What is an EMI?', 'EMI stands for Equated Monthly Instalment: the fixed amount you pay every month towards your loan, covering part of the principal and the interest.'],
    ['What is the difference between secured and unsecured loans?', 'A secured loan, such as a home loan, car loan, gold loan or loan against property, is backed by an asset. An unsecured loan, such as a personal loan, does not need you to pledge an asset.'],
    ['Who decides whether my loan is approved?', 'The relevant lending institution decides approval, interest rate, tenure and charges based on its own policies and your profile.'],
    ['What does Fincrest 360 do?', 'We help you explore loan options, understand the process and continue with the relevant lending institution.'],
    ['How can I check my EMI before applying?', 'Use the EMI calculator. It uses the standard reducing-balance formula to show an illustrative EMI, total interest and total repayment.'],
    ['What documents do I usually need?', 'Identity proof, address proof, income proof or bank statements and photographs are commonly requested. See the checklist below.'],
    ['Does a longer tenure always cost less?', 'A longer tenure lowers each EMI but usually increases the total interest paid over the loan.'],
    ['How do I become a DSA?', 'Visit the DSA page and fill in the enquiry form. Our team will get in touch with the next steps.'],
];
$docs = [
    ['Identity proof', ['PAN card', 'Aadhaar card', 'Passport', 'Driving Licence', 'Voter ID']],
    ['Address proof', ['Aadhaar card', 'Passport', 'Utility bill', 'Rent agreement', 'Bank statement with address']],
    ['Income proof', ['Recent salary slips', 'Bank statements', 'Income Tax Returns (ITR)', 'Form 16', 'Business financial statements']],
    ['Other documents', ['Passport-size photographs', 'Property documents (secured loans)', 'Dealer quotation (vehicle loans)', 'Gold ornaments for valuation (gold loan)']],
];
?>

<section class="page-hero page-hero--simple">
  <div class="hero-lines" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="container">
    <ol class="breadcrumb"><li><a href="<?= e(url('index.php')) ?>">Home</a></li><li aria-current="page">Resources</li></ol>
    <h1>Answers before <em>you apply.</em></h1>
    <p class="page-lead">Practical information to help you understand loans and prepare your documents.</p>
  </div>
</section>

<section class="section" id="faqs">
  <div class="container split split--top">
    <div class="reveal"><span class="eyebrow">FAQs</span><h2>Frequently asked questions</h2><p class="note">Need a human answer? <a href="<?= e(url('contact.php#enquire')) ?>">Contact us</a>.</p></div>
    <div class="faq reveal">
      <?php foreach ($faqs as $f): ?><details><summary><?= e($f[0]) ?></summary><p><?= e($f[1]) ?></p></details><?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--white" id="documents">
  <div class="container">
    <div class="section-head reveal"><span class="eyebrow">Document checklist</span><h2>Keep these ready</h2><p>Commonly requested documents. The exact list depends on the loan type and the lending institution.</p></div>
    <div class="feature-grid values">
      <?php foreach ($docs as $d): ?>
        <div class="panel reveal"><h3><?= e($d[0]) ?></h3><ul class="check-list"><?php foreach ($d[1] as $x): ?><li><?= e($x) ?></li><?php endforeach; ?></ul></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
