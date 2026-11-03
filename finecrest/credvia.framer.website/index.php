<?php
/**
 * Home page. Styling: assets/css/home-premium.css  Behaviour: assets/js/home-premium.js
 * Text and lists for each section are defined in the arrays below.
 */
$base = '';
$page = 'home';
$title = 'Fincrest 360 | Loans, EMI Calculator & Financial Guidance in India';
$description = 'Explore home, car, bike, business, personal, gold and property loans with Fincrest 360. Estimate your EMI and share your requirement for guidance on suitable financing options.';
$styles = ['home-premium'];
$scripts = ['home-premium'];
$preload = 'hero-1.jpg';
$noCta = true;
require __DIR__ . '/includes/header.php';

$CONTENT = require __DIR__ . '/includes/loan-content.php';

$trust = [
    ['layers', '7', 'Loan categories', 'Home, car, bike, business, personal, property and gold.'],
    ['shield', '2', 'Loan types', 'Secured and unsecured financing options.'],
    ['route', '4', 'Simple steps', 'From your requirement to the lending institution.'],
    ['compass', '360&deg;', 'Guidance', 'Personalised support through your enquiry.'],
];

$why = [
    ['layers', 'Multiple financing options', 'Explore home, car, bike, business, personal, property and gold loans in one place.'],
    ['route', 'Simple enquiry journey', 'Share your requirement in a few easy steps and let us take it from there.'],
    ['eye', 'Transparent communication', 'Clear information at every step of your enquiry, including who decides what.'],
    ['compass', 'Personalised guidance', 'Support shaped around your goals, so you can move forward with clarity.'],
];

$steps = [
    ['edit', 'Share Your Requirement', 'Tell us about the type of loan and amount you are exploring.'],
    ['doc', 'Submit Basic Details', 'Provide the necessary basic details for your enquiry.'],
    ['search', 'Explore Suitable Options', 'Understand the financing options relevant to your requirement.'],
    ['link', 'Connect with the Lending Institution', 'Continue with the lending institution for its application and approval process.'],
];

$terms = [
    ['Principal', 'The amount you borrow.'],
    ['Interest', 'The cost of borrowing, charged on the outstanding balance.'],
    ['Monthly EMI', 'The fixed instalment you pay each month.'],
    ['Total repayment', 'Principal plus all the interest, over the full tenure.'],
];

$shortcuts = [
    ['calc', 'Full EMI calculator', 'Open the dedicated calculator page.', 'calculators.php'],
    ['doc', 'Document checklist', 'What lenders commonly ask for.', 'resources.php#documents'],
    ['help', 'FAQs and guidance', 'Answers to common loan questions.', 'resources.php#faqs'],
];

$faqs = [
    ['What loan solutions can I explore with Fincrest 360?', 'You can explore home loans, loan against property, car loans, bike loans, gold loans, personal loans and business loans.'],
    ['How does the EMI calculator work?', 'It uses the standard reducing-balance formula with the loan amount, annual interest rate and tenure you enter, and shows your estimated monthly EMI, total interest and total repayment.'],
    ['Who determines my final loan interest rate?', 'The relevant lending institution decides the interest rate, tenure and charges based on its own policies and your profile.'],
    ['Does Fincrest 360 directly approve loans?', 'No. Approval decisions rest with the lending institution. Fincrest 360 helps you understand your options and guides you through the enquiry.'],
    ['What documents may be required?', 'Lenders commonly ask for identity proof, address proof, income proof or bank statements and photographs. The exact list depends on the loan type and the lending institution.'],
    ['How do I submit a loan enquiry?', 'Fill in the enquiry form with your requirement. Our team will get in touch with you with the next steps.'],
    ['Can I compare different repayment tenures?', 'Yes. The calculator shows how the EMI and total interest change for different tenures. Which tenures are available depends on the lending institution and the loan product.'],
    ['Are the EMI calculator results guaranteed?', 'No. They are illustrative and based on the inputs you provide. Actual terms, rates, charges and approval are determined by the lending institution.'],
];

/* Calculator categories, in display order. Ranges come from includes/loan-content.php. */
$calcOrder = ['personal-loan', 'business-loan', 'home-loan', 'car-loan', 'bike-loan', 'gold-loan', 'loan-against-property'];
$cats = [];
foreach ($calcOrder as $slug) {
    $c = $CONTENT[$slug]['calc'];
    $cats[$slug] = [
        'title' => $LOANS[$slug]['title'], 'icon' => $LOANS[$slug]['icon'], 'href' => loan_url($slug),
        'min' => $c['min'], 'max' => $c['max'], 'step' => $c['step'], 'amount' => $c['amount'],
        'years' => $c['years'], 'maxYears' => $c['maxYears'],
    ];
}
$first = $cats[$calcOrder[0]];
$firstAmount = 500000;
$firstRate = 12;
$firstYears = 3;

/* Server-side EMI so the hero preview shows real numbers even before JavaScript runs. */
$r = $firstRate / 1200;
$n = $firstYears * 12;
$firstEmi = (int) round($firstAmount * $r * pow(1 + $r, $n) / (pow(1 + $r, $n) - 1));
function inr(int $v): string
{
    $s = (string) abs($v);
    if (strlen($s) > 3) {
        $s = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', substr($s, 0, -3)) . ',' . substr($s, -3);
    }
    return '&#8377;' . $s;
}
?>

<section class="hp-hero" id="top">
  <div class="hp-orbits" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="hp-gold-line" aria-hidden="true"></div>
  <div class="container hp-hero-grid">
    <div class="hp-hero-copy">
      <span class="hp-eyebrow hp-in">Trust. Finance. Prosperity.</span>
      <h1 class="hp-in">Your financial goals. <em>A clearer way forward.</em></h1>
      <p class="hp-lead hp-in">Explore financing options for your personal, professional and business goals, with guidance and clarity at every step.</p>
      <div class="hp-actions hp-in">
        <a class="btn btn--gold" href="#loans">Explore Loan Solutions <?= icon('arrow') ?></a>
        <a class="btn btn--ghost" href="#emi">Calculate Your EMI</a>
      </div>
      <ul class="hp-points hp-in">
        <li><?= icon('check') ?>7 loan categories</li>
        <li><?= icon('check') ?>Simple 4-step enquiry</li>
        <li><?= icon('check') ?>Personalised guidance</li>
      </ul>
    </div>

    <div class="hp-hero-visual hp-in">
      <div class="hp-frame">
        <img src="<?= e(asset('img/hero-1.jpg')) ?>" width="1672" height="941" alt="A family looking at their new home" fetchpriority="high">
      </div>
      <a class="hp-float hp-float--emi" href="#emi" aria-label="Open the EMI calculator">
        <small>Sample EMI</small>
        <strong data-hp-preview-emi><?= inr($firstEmi) ?></strong>
        <span data-hp-preview-note><?= inr($firstAmount) ?> &middot; <?= $firstRate ?>% &middot; <?= $firstYears ?> years</span>
        <svg class="hp-spark" viewBox="0 0 120 36" aria-hidden="true"><path d="M2 30 C20 28 28 22 44 20 S72 12 88 8 108 4 118 2"/></svg>
      </a>
      <div class="hp-float hp-float--chip"><?= icon('shield') ?><span><b>Secured &amp; unsecured</b>loan options</span></div>
    </div>
  </div>
</section>

<section class="hp-trust" aria-label="Fincrest 360 at a glance">
  <div class="container">
    <ul class="hp-trust-list">
      <?php foreach ($trust as $t): ?>
        <li class="hp-trust-item reveal">
          <span class="hp-ico"><?= icon($t[0]) ?></span>
          <div><strong><?= $t[1] ?></strong><b><?= e($t[2]) ?></b><span><?= e($t[3]) ?></span></div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>

<section class="hp-section" id="loans">
  <div class="container">
    <div class="hp-head reveal">
      <span class="eyebrow">Loan solutions</span>
      <h2>Financing solutions for every important move.</h2>
      <p>Explore financing options designed around different personal and professional goals.</p>
    </div>
    <div class="hp-loans">
      <?php foreach ($LOANS as $slug => $loan): ?>
        <a class="hp-loan reveal" href="<?= e(loan_url($slug)) ?>">
          <span class="hp-loan-media">
            <img src="<?= e(asset('img/photos/' . $loan['img'])) ?>" width="700" height="525" loading="lazy" alt="<?= e($loan['title']) ?>">
            <span class="hp-loan-tag"><?= icon($loan['icon']) ?><?= e($loan['group']) ?></span>
          </span>
          <span class="hp-loan-body">
            <h3><?= e($loan['title']) ?></h3>
            <p><?= e($loan['desc']) ?></p>
            <span class="hp-link">Explore details <?= icon('arrow') ?></span>
          </span>
        </a>
      <?php endforeach; ?>
      <div class="hp-loan hp-loan--promo reveal">
        <span class="hp-loan-body">
          <h3>Not sure which loan fits you?</h3>
          <p>Share your requirement and we will help you explore suitable options.</p>
          <a class="btn btn--gold" href="<?= e(url('contact.php#enquire')) ?>">Talk to us <?= icon('arrow') ?></a>
        </span>
      </div>
    </div>
  </div>
</section>

<section class="hp-why">
  <div class="container hp-why-grid">
    <div class="hp-why-intro reveal">
      <span class="eyebrow">Why Fincrest 360</span>
      <h2>More clarity. Better financial decisions.</h2>
      <p>We combine personalised guidance with a range of financing options, so you can move forward with confidence.</p>
      <a class="btn btn--gold" href="<?= e(url('about.php')) ?>">About Fincrest 360 <?= icon('arrow') ?></a>
    </div>
    <div class="hp-why-cards">
      <?php foreach ($why as $w): ?>
        <article class="hp-why-card reveal">
          <span class="hp-ico"><?= icon($w[0]) ?></span>
          <h3><?= e($w[1]) ?></h3>
          <p><?= e($w[2]) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="hp-section hp-section--white" id="how">
  <div class="container">
    <div class="hp-head hp-head--center reveal">
      <span class="eyebrow">How it works</span>
      <h2>Your loan journey in four simple steps.</h2>
      <p>A guided path from your requirement to the relevant lending institution.</p>
    </div>
    <ol class="hp-timeline" data-hp-timeline>
      <?php foreach ($steps as $i => $s): ?>
        <li class="hp-step">
          <span class="hp-step-dot"><b>0<?= $i + 1 ?></b></span>
          <span class="hp-step-ico"><?= icon($s[0]) ?></span>
          <h3><?= e($s[1]) ?></h3>
          <p><?= e($s[2]) ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
    <p class="hp-fineprint">Loan approval, interest rates and terms are decided by the lending institution.</p>
  </div>
</section>

<section class="hp-section hp-studio" id="emi">
  <div class="container">
    <div class="hp-head reveal">
      <span class="eyebrow">EMI calculator studio</span>
      <h2>Plan it. Price it. Understand it.</h2>
      <p>Explore your estimated monthly repayments and understand the overall cost of borrowing.</p>
    </div>

    <div class="hp-calc" data-hp-calc data-cats="<?= e(json_encode($cats, JSON_UNESCAPED_SLASHES)) ?>" data-default="<?= e($calcOrder[0]) ?>">
      <div class="hp-modes" role="group" aria-label="Calculator mode">
        <button type="button" class="is-on" data-mode="emi" aria-pressed="true">Calculate EMI</button>
        <button type="button" data-mode="budget" aria-pressed="false">Estimate Your EMI Budget</button>
      </div>

      <div class="hp-calc-grid" data-panel="emi">
        <div class="hp-cats">
          <p class="hp-cats-title" id="hp-cats-label">Loan category</p>
          <div class="hp-cats-list" role="group" aria-labelledby="hp-cats-label">
            <?php foreach ($cats as $slug => $c): ?>
              <button type="button" class="hp-cat<?= $slug === $calcOrder[0] ? ' is-on' : '' ?>" data-cat="<?= e($slug) ?>" aria-pressed="<?= $slug === $calcOrder[0] ? 'true' : 'false' ?>"><?= icon($c['icon']) ?><span><?= e($c['title']) ?></span></button>
            <?php endforeach; ?>
          </div>
        </div>

        <form class="hp-controls" novalidate aria-label="EMI calculator inputs">
          <div class="hp-field">
            <label for="hp-amount">Loan amount <small data-out="amount-short"></small></label>
            <div class="hp-input"><span aria-hidden="true">&#8377;</span><input id="hp-amount" data-in="amount" type="text" inputmode="numeric" autocomplete="off" value="<?= $firstAmount ?>"></div>
            <input class="hp-range" data-rng="amount" type="range" aria-label="Loan amount slider" min="<?= $first['min'] ?>" max="<?= $first['max'] ?>" step="<?= $first['step'] ?>" value="<?= $firstAmount ?>">
            <div class="hp-scale"><span data-out="amount-min"></span><span data-out="amount-max"></span></div>
          </div>
          <div class="hp-field">
            <label for="hp-rate">Annual interest rate <small>illustrative, entered by you</small></label>
            <div class="hp-input"><input id="hp-rate" data-in="rate" type="text" inputmode="decimal" autocomplete="off" value="<?= $firstRate ?>"><span aria-hidden="true">% p.a.</span></div>
            <input class="hp-range" data-rng="rate" type="range" aria-label="Interest rate slider" min="0" max="40" step="0.1" value="<?= $firstRate ?>">
            <div class="hp-scale"><span>0%</span><span>40%</span></div>
          </div>
          <div class="hp-field">
            <label for="hp-tenure">Loan tenure <small data-out="tenure-text"></small></label>
            <div class="hp-input">
              <input id="hp-tenure" data-in="tenure" type="text" inputmode="numeric" autocomplete="off" value="<?= $firstYears ?>">
              <div class="hp-unit" role="group" aria-label="Tenure unit">
                <button type="button" class="is-on" data-unit="years" aria-pressed="true">Years</button>
                <button type="button" data-unit="months" aria-pressed="false">Months</button>
              </div>
            </div>
            <input class="hp-range" data-rng="tenure" type="range" aria-label="Tenure slider" min="1" max="<?= $first['maxYears'] ?>" step="1" value="<?= $firstYears ?>">
            <div class="hp-scale"><span data-out="tenure-min"></span><span data-out="tenure-max"></span></div>
          </div>
          <p class="hp-error" role="alert" data-error></p>
          <a class="hp-explore" data-explore href="<?= e($first['href']) ?>"><span data-out="explore">Explore <?= e($first['title']) ?></span> <?= icon('arrow') ?></a>
        </form>

        <div class="hp-results" aria-live="polite">
          <div class="hp-emi">
            <small>Estimated monthly EMI</small>
            <strong data-out="emi">&mdash;</strong>
          </div>
          <div class="hp-donut-wrap">
            <div class="hp-donut" data-donut role="img" aria-label="Principal versus interest chart"><div><small>Interest share</small><b data-out="interest-pct">&mdash;</b></div></div>
            <ul class="hp-legend">
              <li><i></i>Principal <b data-out="principal">&mdash;</b></li>
              <li><i class="alt"></i>Interest <b data-out="interest">&mdash;</b></li>
            </ul>
          </div>
          <div class="hp-total"><span>Total repayment</span><b data-out="total">&mdash;</b></div>
          <p class="hp-disclaimer">These calculations are illustrative and based on the inputs provided. Actual loan terms, applicable interest rates, charges and approval decisions are determined by the relevant lending institution. Processing fees, taxes and other charges are excluded unless specifically included in the calculation. The rate you enter is not an APR, which also reflects applicable associated charges.</p>
        </div>
      </div>

      <div class="hp-extras" data-panel="emi">
        <details class="hp-acc" data-compare>
          <summary>Compare repayment tenures</summary>
          <div class="hp-table-wrap"><table class="hp-table">
            <caption class="hp-sr">EMI for different tenures at the same amount and rate</caption>
            <thead><tr><th scope="col">Tenure</th><th scope="col">Monthly EMI</th><th scope="col">Total interest</th><th scope="col">Total repayment</th></tr></thead>
            <tbody data-compare-body></tbody>
          </table></div>
          <p class="hp-fineprint hp-fineprint--left">Illustrative only. Available tenures differ by lending institution and loan product.</p>
        </details>
        <details class="hp-acc" data-schedule>
          <summary>Repayment schedule <span data-out="schedule-count"></span></summary>
          <div class="hp-table-wrap hp-table-wrap--scroll"><table class="hp-table hp-table--num">
            <caption class="hp-sr">Month by month repayment schedule</caption>
            <thead><tr><th scope="col">Month</th><th scope="col">Opening balance</th><th scope="col">EMI</th><th scope="col">Principal</th><th scope="col">Interest</th><th scope="col">Closing balance</th></tr></thead>
            <tbody data-schedule-body></tbody>
          </table></div>
        </details>
      </div>

      <div class="hp-budget" data-panel="budget" hidden>
        <form class="hp-budget-form" novalidate aria-label="EMI budget inputs">
          <div class="hp-field"><label for="hp-income">Monthly take-home income</label>
            <div class="hp-input"><span aria-hidden="true">&#8377;</span><input id="hp-income" data-b="income" type="text" inputmode="numeric" autocomplete="off" value="60000"></div></div>
          <div class="hp-field"><label for="hp-existing">Existing monthly loan EMIs</label>
            <div class="hp-input"><span aria-hidden="true">&#8377;</span><input id="hp-existing" data-b="existing" type="text" inputmode="numeric" autocomplete="off" value="0"></div></div>
          <div class="hp-field"><label for="hp-pct">EMI budget share of income <small data-out="pct-text">40%</small></label>
            <input id="hp-pct" class="hp-range" data-b="pct" type="range" min="10" max="60" step="1" value="40">
            <p class="hp-hint">A planning assumption you can adjust. It is not a lender&rsquo;s eligibility rule.</p></div>
          <div class="hp-field"><label for="hp-brate">Annual interest rate <small>illustrative</small></label>
            <div class="hp-input"><input id="hp-brate" data-b="rate" type="text" inputmode="decimal" autocomplete="off" value="12"><span aria-hidden="true">% p.a.</span></div></div>
          <div class="hp-field"><label for="hp-byears">Tenure (years)</label>
            <div class="hp-input"><input id="hp-byears" data-b="years" type="text" inputmode="numeric" autocomplete="off" value="5"><span aria-hidden="true">years</span></div></div>
          <p class="hp-error" role="alert" data-berror></p>
        </form>
        <div class="hp-results hp-results--budget" aria-live="polite">
          <div class="hp-emi"><small>Indicative monthly EMI budget</small><strong data-out="b-emi">&mdash;</strong></div>
          <div class="hp-total"><span>Approximate borrowing amount</span><b data-out="b-amount">&mdash;</b></div>
          <button type="button" class="btn btn--gold" data-use-budget>Use in EMI calculator <?= icon('arrow') ?></button>
          <p class="hp-disclaimer">This is a planning estimate, not an eligibility check, credit assessment or loan approval. The lending institution decides eligibility, rates, charges and the amount, if any, it will lend.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="hp-section hp-clarity">
  <div class="container hp-clarity-grid">
    <div class="reveal">
      <span class="eyebrow">Borrow with clarity</span>
      <h2>Know what you will pay before you borrow.</h2>
      <p class="hp-sub">Understanding EMI, tenure and total interest helps you choose a repayment plan that suits your budget. A longer tenure usually lowers the monthly EMI but increases the total interest you pay.</p>
      <dl class="hp-terms">
        <?php foreach ($terms as $t): ?><div><dt><?= e($t[0]) ?></dt><dd><?= e($t[1]) ?></dd></div><?php endforeach; ?>
      </dl>
    </div>
    <div class="hp-shortcuts">
      <?php foreach ($shortcuts as $s): ?>
        <a class="hp-shortcut reveal" href="<?= e(url($s[3])) ?>">
          <span class="hp-ico"><?= icon($s[0]) ?></span>
          <span><strong><?= e($s[1]) ?></strong><span><?= e($s[2]) ?></span></span>
          <?= icon('arrow') ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="hp-section hp-section--white" id="faqs">
  <div class="container hp-faq-grid">
    <div class="reveal">
      <span class="eyebrow">FAQs</span>
      <h2>Common questions.</h2>
      <p class="hp-sub">Clear answers before you calculate or continue with a lending institution.</p>
      <a class="btn btn--outline mt-lg" href="<?= e(url('resources.php#faqs')) ?>">More questions</a>
    </div>
    <div class="hp-faq reveal" data-hp-faq>
      <?php foreach ($faqs as $f): ?>
        <details><summary><?= e($f[0]) ?></summary><div><p><?= e($f[1]) ?></p></div></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="hp-final">
  <div class="hp-orbits" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="container reveal">
    <h2>Your next financial move starts with clarity.</h2>
    <p>Explore financing possibilities and take the next step towards your financial goals with <?= e($CONFIG['name']) ?>.</p>
    <div class="hp-actions hp-actions--center">
      <a class="btn btn--gold" href="#loans">Explore Loan Solutions <?= icon('arrow') ?></a>
      <a class="btn btn--ghost" href="<?= e(url('contact.php#enquire')) ?>">Talk to Our Team</a>
      <a class="btn btn--ghost" href="#emi">Calculate Your EMI</a>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
