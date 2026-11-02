# Fincrest 360 website

Plain PHP + HTML + CSS + vanilla JS. Needs Apache with PHP (XAMPP is fine). Open `index.php`.

## Where to edit

| To change | Edit |
|---|---|
| Phone, email, address, notification e-mail, social links | `includes/config.php` |
| Loan list, names, descriptions, menu items, icons | `includes/data.php` |
| Text of each loan page (intro, features, eligibility, documents, FAQs, EMI ranges) | `includes/loan-content.php` |
| Header / mega menu / mobile menu | `includes/header.php` |
| Footer and the green call-to-action band | `includes/footer.php` |
| Home page sections and hero slides | `index.php` |
| About, DSA, Contact, Calculator, Resources pages | `about.php`, `dsa.php`, `contact.php`, `calculators.php`, `resources.php` |
| Colours, fonts, spacing | `assets/css/base.css` (`:root` tokens) |
| Header, menu, footer styling | `assets/css/layout.css` |
| Buttons, cards, hero, calculator, forms | `assets/css/components.css` |
| Page-level layouts | `assets/css/pages.css` |
| Menu behaviour | `assets/js/nav.js` |
| Scroll animation, counters, hero carousel | `assets/js/ui.js` |
| EMI calculator | `assets/js/calculator.js` |
| Form validation | `assets/js/form.js` |

## Enquiries

Forms post to `submit.php`, which validates the data and appends it to `storage/enquiries.csv`
(blocked from the web by `storage/.htaccess`). Set `notify_email` in `includes/config.php` to also
receive each enquiry by e-mail (the server must be able to send mail).

## Adding a loan

1. Add an entry to `$LOANS` in `includes/data.php`.
2. Add its content to `includes/loan-content.php`.
3. Copy any file in `loans/` and change the `$slug`.
4. Put a photo in `assets/img/photos/`.
