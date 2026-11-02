<?php
/**
 * Enquiry form (validation in assets/js/form.js, handling in submit.php).
 * Set before including: $formType ('loan' or 'dsa'), optional $formLoan (a $LOANS slug to preselect),
 * optional $formTitle / $formIntro.
 */
$formType  = $formType ?? 'loan';
$formLoan  = $formLoan ?? '';
$formTitle = $formTitle ?? 'Share your requirement';
$formIntro = $formIntro ?? 'Fill in a few details and our team will get back to you.';
$formId    = 'enq-' . substr(md5($formType . $formLoan . ($page ?? '')), 0, 6);
?>
<div class="form-card">
  <form data-enquiry action="<?= e(url('submit.php')) ?>" method="post" novalidate id="<?= e($formId) ?>">
    <h3><?= e($formTitle) ?></h3>
    <p><?= e($formIntro) ?></p>
    <input type="hidden" name="type" value="<?= e($formType) ?>">
    <input type="hidden" name="ts" value="<?= time() ?>">
    <div class="hp" aria-hidden="true"><label>Leave empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
    <div class="form-grid">
      <div class="field">
        <label for="<?= e($formId) ?>-name">Full name <i>*</i></label>
        <input id="<?= e($formId) ?>-name" name="name" type="text" autocomplete="name" maxlength="80" required>
        <span class="field-error"></span>
      </div>
      <div class="field">
        <label for="<?= e($formId) ?>-mobile">Mobile number <i>*</i></label>
        <input id="<?= e($formId) ?>-mobile" name="mobile" type="tel" inputmode="numeric" autocomplete="tel" maxlength="16" placeholder="10-digit mobile" required>
        <span class="field-error"></span>
      </div>
      <div class="field">
        <label for="<?= e($formId) ?>-email">Email (optional)</label>
        <input id="<?= e($formId) ?>-email" name="email" type="email" autocomplete="email" maxlength="120">
        <span class="field-error"></span>
      </div>
      <div class="field">
        <label for="<?= e($formId) ?>-city">City <i>*</i></label>
        <input id="<?= e($formId) ?>-city" name="city" type="text" autocomplete="address-level2" maxlength="60" required>
        <span class="field-error"></span>
      </div>
      <?php if ($formType === 'dsa'): ?>
        <input type="hidden" name="loan" value="DSA partnership">
      <?php else: ?>
        <div class="field">
          <label for="<?= e($formId) ?>-loan">Loan type <i>*</i></label>
          <select id="<?= e($formId) ?>-loan" name="loan" required>
            <option value="">Select a loan</option>
            <?php foreach ($LOANS as $slug => $loan): ?>
              <option value="<?= e($loan['title']) ?>"<?= $slug === $formLoan ? ' selected' : '' ?>><?= e($loan['title']) ?></option>
            <?php endforeach; ?>
          </select>
          <span class="field-error"></span>
        </div>
        <div class="field">
          <label for="<?= e($formId) ?>-amount">Loan amount needed (optional)</label>
          <input id="<?= e($formId) ?>-amount" name="amount" type="text" inputmode="numeric" maxlength="20" placeholder="e.g. 5,00,000">
          <span class="field-error"></span>
        </div>
      <?php endif; ?>
      <div class="field field--full">
        <label for="<?= e($formId) ?>-message"><?= $formType === 'dsa' ? 'Tell us about your experience (optional)' : 'Message (optional)' ?></label>
        <textarea id="<?= e($formId) ?>-message" name="message" maxlength="600"></textarea>
      </div>
      <div class="field field--full consent-wrap">
        <label class="consent"><input type="checkbox" name="consent" value="1" required><span>I agree that <?= e($CONFIG['name']) ?> may contact me about my enquiry.</span></label>
        <span class="field-error"></span>
      </div>
    </div>
    <div class="form-status" role="status"></div>
    <button class="btn btn--block" type="submit">Submit enquiry</button>
  </form>
  <div class="form-success" hidden tabindex="-1">
    <?= icon('check') ?>
    <h3>Thank you</h3>
    <p>We have received your enquiry and will get in touch with you soon.</p>
  </div>
</div>
