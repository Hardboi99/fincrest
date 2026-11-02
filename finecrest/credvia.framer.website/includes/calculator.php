<?php
/**
 * EMI calculator block (behaviour in assets/js/calculator.js).
 * Optional overrides before including: $calc = ['amount'=>..., 'min'=>..., 'max'=>..., 'step'=>..., 'rate'=>..., 'years'=>..., 'maxYears'=>...]
 */
$c = array_merge(
    ['amount' => 500000, 'min' => 50000, 'max' => 10000000, 'step' => 10000, 'rate' => 10, 'years' => 5, 'maxYears' => 30],
    $calc ?? []
);
?>
<form class="calc" data-emi novalidate aria-label="EMI calculator">
  <div class="calc-form">
    <div class="calc-field">
      <label for="calc-amount-n">Loan amount <span class="calc-value" data-label-amount></span></label>
      <div class="calc-input"><span aria-hidden="true">&#8377;</span><input id="calc-amount-n" name="amount" type="number" inputmode="numeric" min="<?= (int) $c['min'] ?>" max="<?= (int) $c['max'] ?>" step="<?= (int) $c['step'] ?>" value="<?= (int) $c['amount'] ?>"></div>
      <input name="amount" type="range" min="<?= (int) $c['min'] ?>" max="<?= (int) $c['max'] ?>" step="<?= (int) $c['step'] ?>" value="<?= (int) $c['amount'] ?>" aria-label="Loan amount slider">
      <div class="range-scale"><span>&#8377;<?= number_format($c['min'], 0, '.', ',') ?></span><span>&#8377;<?= number_format($c['max']) ?></span></div>
    </div>
    <div class="calc-field">
      <label for="calc-rate-n">Interest rate (per year) <span class="calc-value" data-label-rate></span></label>
      <div class="calc-input"><input id="calc-rate-n" name="rate" type="number" inputmode="decimal" min="1" max="40" step="0.1" value="<?= e((string) $c['rate']) ?>"><span aria-hidden="true">%</span></div>
      <input name="rate" type="range" min="1" max="40" step="0.1" value="<?= e((string) $c['rate']) ?>" aria-label="Interest rate slider">
    </div>
    <div class="calc-field">
      <label for="calc-years-n">Loan tenure <span class="calc-value" data-label-years></span></label>
      <div class="calc-input"><input id="calc-years-n" name="years" type="number" inputmode="numeric" min="1" max="<?= (int) $c['maxYears'] ?>" step="1" value="<?= (int) $c['years'] ?>"><span aria-hidden="true">years</span></div>
      <input name="years" type="range" min="1" max="<?= (int) $c['maxYears'] ?>" step="1" value="<?= (int) $c['years'] ?>" aria-label="Loan tenure slider">
    </div>
    <p class="calc-error" role="alert"></p>
    <p class="calc-note">Illustrative calculation only. The actual interest rate, charges and eligibility are decided by the lending institution.</p>
  </div>
  <div class="calc-result" aria-live="polite">
    <div class="donut"><div class="donut-label"><small>Monthly EMI</small><strong data-out-donut>&mdash;</strong></div></div>
    <div class="calc-legend"><span><i></i>Principal</span><span><i class="alt"></i>Interest</span></div>
    <div class="calc-rows">
      <div class="calc-row calc-row--main"><span>Monthly EMI</span><b data-out-emi>&mdash;</b></div>
      <div class="calc-row"><span>Principal amount</span><b data-out-principal>&mdash;</b></div>
      <div class="calc-row"><span>Total interest</span><b data-out-interest>&mdash;</b></div>
      <div class="calc-row"><span>Total repayment</span><b data-out-total>&mdash;</b></div>
    </div>
  </div>
</form>
