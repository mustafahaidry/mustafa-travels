<?php
require_once __DIR__ . '/config.php';
function site_header(string $title='Home'): void { ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($title) ?> | Mustafa Travels & Tours</title>
<meta name="description" content="Mustafa Travels & Tours Barcelona — flights, hotels, Umrah, Hajj, visa support and 24/7 emergency travel assistance.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">

<!-- Travelpayouts White Label -->
<script nowprocket data-noptimize="1" data-cfasync="false" data-wpfc-render="false" seraph-accel-crit="1" data-no-defer="1">
(function () {
  var script = document.createElement("script");
  script.async = 1;
  script.type = "module";
  script.src = "https://tpwdg.com/wl_web/main.js?wl_id=16109";
  document.head.appendChild(script);
})();
</script>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-H7TQLKHP25"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-H7TQLKHP25');
</script>
</head>
<body>
<div class="topbar">
  <div class="container topbar-inner">
    <span>📍 Barcelona, Spain</span>
    <div><a href="tel:+34632234216"><?= PHONE1 ?></a><span class="sep">•</span><a href="mailto:<?= EMAIL ?>"><?= EMAIL ?></a><span class="sep">•</span><b>24/7 Emergency Service</b></div>
  </div>
</div>
<header class="site-header" id="siteHeader">
  <div class="container nav-wrap">
    <a class="brand" href="index.php">
      <div class="brand-mark">✈</div>
      <div><strong>MUSTAFA</strong><small>TRAVELS & TOURS</small></div>
    </a>
    <button class="menu-toggle" onclick="document.body.classList.toggle('nav-open')">☰</button>
    <nav>
      <a href="index.php">Home</a><a href="about.php">About</a><a href="services.php">Services</a>
      <a href="umrah.php">Umrah & Hajj</a><a href="hotels.php">Hotels</a><a href="offers.php">Offers</a>
      <a href="certificates.php">Certificates</a><a href="contact.php">Contact</a>
    </nav>
    <a class="btn btn-primary nav-cta" href="https://wa.me/<?= WHATSAPP ?>" target="_blank">24/7 WhatsApp</a>
  </div>
</header>
<?php }
function site_footer(): void { ?>
<footer>
  <div class="container footer-grid">
    <div>
      <div class="brand brand-footer"><div class="brand-mark">✈</div><div><strong>MUSTAFA</strong><small>TRAVELS & TOURS</small></div></div>
      <p>Professional travel support from Barcelona for flights, hotels, Umrah, Hajj, visas and complete travel arrangements.</p>
    </div>
    <div><h4>Services</h4><a href="services.php">Flight Tickets</a><a href="hotels.php">Hotels</a><a href="umrah.php">Umrah & Hajj</a><a href="services.php#visa">Visa Assistance</a></div>
    <div><h4>Contact</h4><p><?= ADDRESS ?></p><p><?= PHONE1 ?><br><?= EMAIL ?></p></div>
    <div><h4>Quick Help</h4><a href="offers.php">Today's Offers</a><a href="contact.php">Request a Quote</a><a href="https://wa.me/<?= WHATSAPP ?>" target="_blank">24/7 WhatsApp</a></div>
  </div>
  <div class="container footer-bottom"><span>© <?= date('Y') ?> Mustafa Travels & Tours. All rights reserved.</span><a href="admin.php">Admin</a></div>
</footer>
<a class="floating-wa" href="https://wa.me/<?= WHATSAPP ?>" target="_blank" aria-label="WhatsApp">◉</a>
<script src="assets/js/main.js"></script>
</body></html>
<?php } ?>
