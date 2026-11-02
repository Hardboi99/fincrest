</main>

<?php if (empty($noCta)): ?>
<section class="cta-band">
  <div class="hero-lines" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="container reveal">
    <h2>Ready to take the next step?</h2>
    <p>Share your requirement and explore the right financing options with <?= e($CONFIG['name']) ?>.</p>
    <div class="actions">
      <a class="btn btn--gold" href="<?= e(url('contact.php#enquire')) ?>">Apply Now <?= icon('arrow') ?></a>
      <a class="btn btn--ghost" href="<?= e(url('calculators.php')) ?>">Calculate EMI</a>
    </div>
  </div>
</section>
<?php endif; ?>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <img src="<?= e(asset('img/fincrest360-logo.png')) ?>" width="171" height="44" alt="<?= e($CONFIG['name']) ?>">
        <p>Discover financial solutions designed around your ambitions. <?= e($CONFIG['tagline']) ?></p>
      </div>
      <div class="footer-col">
        <h3>Loans</h3>
        <ul><?php foreach ($LOANS as $ls => $lo): ?><li><a href="<?= e(loan_url($ls)) ?>"><?= e($lo['title']) ?></a></li><?php endforeach; ?></ul>
      </div>
      <div class="footer-col">
        <h3>Company</h3>
        <ul>
          <li><a href="<?= e(url('about.php')) ?>">About Us</a></li>
          <li><a href="<?= e(url('dsa.php')) ?>">Become a DSA</a></li>
          <li><a href="<?= e(url('calculators.php')) ?>">EMI Calculator</a></li>
          <li><a href="<?= e(url('resources.php')) ?>">Resources &amp; FAQs</a></li>
          <li><a href="<?= e(url('contact.php')) ?>">Contact Us</a></li>
        </ul>
      </div>
      <div class="footer-col footer-contact">
        <h3>Get in touch</h3>
        <ul>
          <?php if ($CONFIG['phone']): ?><li><a href="tel:<?= e(preg_replace('/[^\d+]/', '', $CONFIG['phone'])) ?>"><?= e($CONFIG['phone']) ?></a></li><?php endif; ?>
          <?php if ($CONFIG['email']): ?><li><a href="mailto:<?= e($CONFIG['email']) ?>"><?= e($CONFIG['email']) ?></a></li><?php endif; ?>
          <?php if ($CONFIG['address']): ?><li><?= e($CONFIG['address']) ?></li><?php endif; ?>
          <li><a href="<?= e(url('contact.php#enquire')) ?>">Send an enquiry</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> <?= e($CONFIG['name']) ?>. All rights reserved. Loan approval, interest rates and terms are decided by the relevant lending institution.</p>
      <p><?= e($CONFIG['tagline']) ?></p>
    </div>
  </div>
</footer>

<script src="<?= e(asset('js/nav.js')) ?>" defer></script>
<script src="<?= e(asset('js/ui.js')) ?>" defer></script>
<?php if (!empty($scripts)) foreach ($scripts as $s): ?>
<script src="<?= e(asset('js/' . $s . '.js')) ?>" defer></script>
<?php endforeach; ?>
</body>
</html>
