<?php
require_once __DIR__ . '/config.php';

function mt_active(string $target): string {
    $current = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: 'index.php');
    if ($target === 'index.php' && ($current === '' || $current === 'index.php')) return ' active';
    if ($target === 'hotels.php' && $current === 'hotels.php') return ' active';
    if ($target === 'umrah.php' && $current === 'umrah.php') return ' active';
    if ($target === 'visa.php' && $current === 'visa.php') return ' active';
    if ($target === 'offers.php' && $current === 'offers.php') return ' active';
    if ($target === 'contact.php' && $current === 'contact.php') return ' active';
    return '';
}

function site_header(string $title='Home'): void { ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($title) ?> | Mustafa Travels & Tours</title>
<meta name="description" content="Mustafa Travels & Tours Barcelona — flights, hotels, Umrah, Hajj, eVisa support and travel assistance.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">

<style>
/* ===== MUSTAFA PREMIUM PORTAL HEADER ===== */
:root{--mh-blue:#0d77e8;--mh-navy:#073568;--mh-text:#0b2f62;--mh-yellow:#ffdf00;--mh-line:#e8eef5}
body{margin:0}
.mt-premium-header{background:#fff;border-top:3px solid #0e63bd;box-shadow:0 1px 0 var(--mh-line);position:relative;z-index:1000}
.mt-header-inner{width:min(1260px,calc(100% - 34px));margin:auto;min-height:92px;display:flex;align-items:center;gap:34px}
.mt-brand{display:flex;align-items:center;gap:11px;text-decoration:none;min-width:210px}
.mt-brand-box{position:relative;width:48px;height:48px;border-radius:10px;background:linear-gradient(145deg,#0a6ad3,#258bff);display:grid;place-items:center;color:#fff;font:900 27px/1 Manrope,Arial,sans-serif;box-shadow:0 8px 18px rgba(21,118,220,.2)}
.mt-brand-box:after{content:"↗";position:absolute;right:-5px;top:-9px;color:#f0df00;font:900 24px/1 Arial}
.mt-brand-copy strong{display:block;color:#0754ad;font:900 24px/1 Manrope,Arial,sans-serif;letter-spacing:-.5px}
.mt-brand-copy small{display:block;color:#dfc900;font-size:10px;font-weight:800;letter-spacing:1.35px;margin-top:4px}
.mt-main-nav{display:flex;align-items:stretch;gap:24px;margin-left:auto}
.mt-main-nav a{position:relative;display:flex;align-items:center;min-height:92px;color:#062d60;text-decoration:none;font-size:14px;font-weight:800;white-space:nowrap}
.mt-main-nav a:after{content:"";position:absolute;left:0;right:0;bottom:18px;height:3px;border-radius:4px;background:#1688f7;transform:scaleX(0);transition:.2s ease}
.mt-main-nav a:hover:after,.mt-main-nav a.active:after{transform:scaleX(1)}
.mt-main-nav a.active{color:#0c78e8}
.mt-header-tools{display:flex;align-items:center;gap:27px;margin-left:15px;white-space:nowrap}
.mt-phone,.mt-booking{display:flex;align-items:center;gap:8px;color:#062d60!important;text-decoration:none;font-size:13px;font-weight:850}
.mt-phone-icon,.mt-book-icon{color:#073568;font-size:14px}
.mt-menu-btn{display:none;border:0;background:#0d77e8;color:#fff;border-radius:9px;width:43px;height:43px;font-size:21px;cursor:pointer}
@media(max-width:1120px){
 .mt-header-inner{gap:18px}.mt-main-nav{gap:14px}.mt-header-tools{gap:14px;margin-left:0}
 .mt-main-nav a{font-size:12px}.mt-brand{min-width:185px}
}
@media(max-width:900px){
 .mt-header-inner{min-height:76px}.mt-menu-btn{display:block;margin-left:auto}
 .mt-main-nav,.mt-header-tools{display:none}
 body.nav-open .mt-main-nav{display:flex;position:absolute;left:0;right:0;top:76px;background:#fff;flex-direction:column;gap:0;padding:10px 20px 18px;border-top:1px solid #edf2f7;box-shadow:0 15px 30px rgba(10,43,78,.12)}
 body.nav-open .mt-main-nav a{min-height:45px}
 body.nav-open .mt-main-nav a:after{bottom:4px}
 body.nav-open .mt-header-tools{display:flex;position:absolute;left:0;right:0;top:520px;background:#fff;padding:14px 20px 20px;justify-content:space-between;box-shadow:0 15px 30px rgba(10,43,78,.12)}
}
@media(max-width:520px){
 .mt-brand-copy strong{font-size:20px}.mt-brand-copy small{font-size:8px}.mt-brand-box{width:43px;height:43px}
 .mt-header-inner{width:min(100% - 22px,1260px)}
}

.mt-footer-brand{display:flex!important;min-width:0!important;margin-bottom:14px}
.mt-footer-brand .mt-brand-copy strong{color:#fff!important}
.mt-footer-brand .mt-brand-copy small{color:#f0d900!important}
.mt-footer-brand .mt-brand-box{box-shadow:none}

</style>

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

<script async src="https://www.googletagmanager.com/gtag/js?id=G-H7TQLKHP25"></script>
<script>
window.dataLayer=window.dataLayer||[];
function gtag(){dataLayer.push(arguments);}
gtag('js',new Date());
gtag('config','G-H7TQLKHP25');
</script>
</head>
<body>

<header class="mt-premium-header" id="siteHeader">
  <div class="mt-header-inner">
    <a class="mt-brand" href="index.php" aria-label="Mustafa Travels Home">
      <div class="mt-brand-box">M</div>
      <div class="mt-brand-copy">
        <strong>Mustafa</strong>
        <small>TRAVELS & TOURS</small>
      </div>
    </a>

    <button class="mt-menu-btn" onclick="document.body.classList.toggle('nav-open')" aria-label="Menu">☰</button>

    <nav class="mt-main-nav">
      <a class="<?= trim(mt_active('index.php')) ?>" href="index.php">Home</a>
      <a href="index.php#flight-search">Flights</a>
      <a class="<?= trim(mt_active('hotels.php')) ?>" href="hotels.php">Hotels</a>
      <a class="<?= trim(mt_active('umrah.php')) ?>" href="umrah.php">Umrah</a>
      <a href="umrah.php#hajj">Hajj</a>
      <a class="<?= trim(mt_active('visa.php')) ?>" href="visa.php">Visa</a>
      <a class="<?= trim(mt_active('offers.php')) ?>" href="offers.php">Offers</a>
      <a class="<?= trim(mt_active('contact.php')) ?>" href="contact.php">Contact</a>
    </nav>

    <div class="mt-header-tools">
      <a class="mt-phone" href="tel:+34632234216"><span class="mt-phone-icon">☎</span><?= PHONE1 ?></a>
      <a class="mt-booking" href="contact.php?service=Booking"><span class="mt-book-icon">♟</span>My Booking</a>
    </div>
  </div>
</header>
<?php }

function site_footer(): void { ?>
<footer>
  <div class="container footer-grid">
    <div>
      <a class="mt-brand mt-footer-brand" href="index.php" aria-label="Mustafa Travels Home">
        <div class="mt-brand-box">M</div>
        <div class="mt-brand-copy">
          <strong>Mustafa</strong>
          <small>TRAVELS & TOURS</small>
        </div>
      </a>
      <p>Professional travel support from Barcelona for flights, hotels, Umrah, Hajj, visas and complete travel arrangements.</p>
    </div>
    <div>
      <h4>Services</h4>
      <a href="index.php#flight-search">Flight Tickets</a>
      <a href="hotels.php">Hotels</a>
      <a href="umrah.php">Umrah & Hajj</a>
      <a href="visa.php">Visa Services</a>
    </div>
    <div><h4>Contact</h4><p><?= ADDRESS ?></p><p><?= PHONE1 ?><br><?= EMAIL ?></p></div>
    <div><h4>Quick Help</h4><a href="offers.php">Today's Offers</a><a href="contact.php">Request a Quote</a><a href="https://wa.me/<?= WHATSAPP ?>" target="_blank">24/7 WhatsApp</a></div>
  </div>
  <div class="container footer-bottom"><span>© <?= date('Y') ?> Mustafa Travels & Tours. All rights reserved.</span><a href="admin.php">Admin</a></div>
</footer>
<a class="floating-wa" href="https://wa.me/<?= WHATSAPP ?>" target="_blank" aria-label="WhatsApp">◉</a>
<script src="assets/js/main.js"></script>
</body></html>
<?php } ?>
