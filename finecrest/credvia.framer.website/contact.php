<?php
$base = '';
$page = 'contact';
$title = 'Contact Us';
$description = 'Contact Fincrest 360. Share your loan requirement and our team will get in touch with you.';
$scripts = ['form'];
$formType = 'loan';
$formTitle = 'Send us your enquiry';
$formIntro = 'Share your requirement and our team will get in touch with you.';
$formLoan = '';
require __DIR__ . '/includes/header.php';
?>

<section class="page-hero page-hero--simple">
  <div class="hero-lines" aria-hidden="true"><i></i><i></i><i></i></div>
  <div class="container">
    <ol class="breadcrumb"><li><a href="<?= e(url('index.php')) ?>">Home</a></li><li aria-current="page">Contact Us</li></ol>
    <h1>Let&rsquo;s talk about <em>your goals.</em></h1>
    <p class="page-lead">Share your requirement and our team will get in touch with you.</p>
  </div>
</section>

<section class="section" id="enquire">
  <div class="container contact-grid">
    <div>
      <div class="contact-card reveal">
        <h3>Reach us</h3>
        <ul>
          <?php if ($CONFIG['phone']): ?><li>Phone: <a href="tel:<?= e(preg_replace('/[^\d+]/', '', $CONFIG['phone'])) ?>"><?= e($CONFIG['phone']) ?></a></li><?php endif; ?>
          <?php if ($CONFIG['email']): ?><li>Email: <a href="mailto:<?= e($CONFIG['email']) ?>"><?= e($CONFIG['email']) ?></a></li><?php endif; ?>
          <?php if ($CONFIG['address']): ?><li>Address: <?= e($CONFIG['address']) ?></li><?php endif; ?>
          <?php if ($CONFIG['hours']): ?><li>Hours: <?= e($CONFIG['hours']) ?></li><?php endif; ?>
          <?php if (!$CONFIG['phone'] && !$CONFIG['email'] && !$CONFIG['address']): ?><li>Use the enquiry form and our team will contact you.</li><?php endif; ?>
        </ul>
      </div>
      <div class="contact-card reveal">
        <h3>Want to partner with us?</h3>
        <p>Interested in becoming a DSA? <a href="<?= e(url('dsa.php')) ?>">Visit the DSA page</a>.</p>
      </div>
    </div>
    <div class="reveal"><?php require __DIR__ . '/includes/enquiry-form.php'; ?></div>
  </div>
</section>

<?php $noCta = true; require __DIR__ . '/includes/footer.php'; ?>
