<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

function h(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hotels | Mustafa Travels & Tours</title>
<meta name="description" content="Search hotel deals worldwide with Mustafa Travels & Tours.">
<link rel="preconnect" href="https://images.unsplash.com">
<style>
:root{
    --navy:#06244d;
    --navy-2:#0a326b;
    --blue:#0d6efd;
    --blue-2:#2f7cff;
    --gold:#d9a328;
    --ink:#12213a;
    --muted:#65728a;
    --line:#dfe6f1;
    --soft:#f5f8fc;
    --white:#fff;
    --shadow:0 18px 55px rgba(13,37,76,.14);
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
    margin:0;
    background:#fff;
    color:var(--ink);
    font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;
}
a{text-decoration:none;color:inherit}
.wrap{max-width:1220px;margin:auto;padding:0 24px}

/* top bar */
.topbar{
    background:#031b3b;color:#fff;
    font-size:13px;
}
.topbar .wrap{
    min-height:34px;
    display:flex;align-items:center;justify-content:space-between;gap:20px;
}
.topbar-left,.topbar-right{display:flex;gap:18px;align-items:center}
.dot{opacity:.45}

/* nav */
.nav{
    background:#fff;border-bottom:1px solid #eef1f5;
    position:sticky;top:0;z-index:80;
}
.nav .wrap{
    height:78px;display:flex;align-items:center;justify-content:space-between;gap:24px;
}
.brand{display:flex;align-items:center;gap:12px;min-width:max-content}
.brand-mark{
    width:46px;height:46px;border-radius:10px;
    background:linear-gradient(135deg,#06244d,#0d6efd);
    color:#fff;display:grid;place-items:center;
    font-size:24px;font-weight:1000;position:relative;
}
.brand-mark:after{
    content:"↗";position:absolute;right:-6px;top:-8px;
    color:var(--gold);font-size:20px;font-weight:900
}
.brand-name{font-size:20px;font-weight:900;line-height:1;color:#103264}
.brand-name small{display:block;color:#e09b19;font-size:10px;letter-spacing:.09em;margin-top:5px}
.menu{display:flex;gap:27px;align-items:center;font-size:14px;font-weight:750;color:#203654}
.menu a{padding:29px 0 24px;position:relative}
.menu a.active{color:#0a2d5f}
.menu a.active:after{
    content:"";position:absolute;height:3px;border-radius:999px;background:#0a2d5f;
    left:0;right:0;bottom:17px
}
.nav-actions{display:flex;align-items:center;gap:18px;font-weight:750;font-size:14px;color:#18345d}
.booking-btn{
    border:1px solid #cad6e8;border-radius:10px;padding:12px 16px;background:#fff;
}

/* hero */
.hero{
    min-height:400px;
    position:relative;
    background:
      linear-gradient(90deg,rgba(3,27,59,.98) 0%,rgba(4,36,77,.88) 44%,rgba(4,36,77,.50) 70%,rgba(3,27,59,.24) 100%),
      url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1800&q=85') center/cover no-repeat;
    color:#fff;
}
.hero .wrap{padding-top:52px;padding-bottom:85px}
.eyebrow{
    display:inline-flex;align-items:center;
    border:1px solid rgba(217,163,40,.72);
    color:#f6bd34;border-radius:7px;
    padding:6px 10px;font-size:11px;font-weight:900;letter-spacing:.03em;
}
.hero h1{
    margin:18px 0 14px;
    font-size:clamp(40px,5.4vw,62px);
    line-height:1.03;max-width:650px;letter-spacing:-.035em
}
.hero-copy{
    max-width:620px;font-size:17px;color:#edf4ff;line-height:1.65;margin-bottom:26px
}
.hero-benefits{display:flex;gap:28px;flex-wrap:wrap}
.hero-benefit{display:flex;gap:11px;align-items:flex-start;max-width:205px}
.hero-icon{
    flex:0 0 44px;width:44px;height:44px;border:1px solid rgba(217,163,40,.58);
    border-radius:50%;display:grid;place-items:center;color:#ffc74d;background:rgba(4,30,66,.55);
    font-size:18px
}
.hero-benefit strong{font-size:13px;display:block;margin-bottom:3px}
.hero-benefit span{font-size:12px;line-height:1.45;color:#d8e4f5}

/* search card */
.search-shell{position:relative;margin-top:-56px;z-index:20}
.search-card{
    background:#fff;border:1px solid #e7ebf1;border-radius:21px;box-shadow:var(--shadow);
    padding:0 22px 18px;
}
.search-tab{
    display:inline-flex;gap:8px;align-items:center;
    padding:17px 5px 11px;
    color:#0b2b59;font-size:15px;font-weight:900;
    border-bottom:3px solid var(--blue)
}
.search-grid{
    padding-top:16px;
    display:grid;grid-template-columns:1.6fr 1fr 1fr 1.15fr 1.2fr auto;
    gap:13px;align-items:end
}
.field{position:relative}
.field label{
    display:block;margin:0 0 7px 2px;
    font-size:11px;color:#61708a;font-weight:900;letter-spacing:.04em
}
.control{
    width:100%;height:54px;border:1px solid #cfdaea;border-radius:10px;
    background:#fff;padding:0 14px;color:#182e4e;font:inherit;outline:0;
}
.control:focus{border-color:#76a6ff;box-shadow:0 0 0 4px rgba(13,110,253,.09)}
.search-button{
    height:54px;border:0;border-radius:9px;padding:0 25px;
    background:linear-gradient(135deg,#1067f3,#2078ff);
    color:#fff;font-weight:900;font-size:15px;cursor:pointer;
    box-shadow:0 10px 22px rgba(13,110,253,.23)
}
.search-button:hover{transform:translateY(-1px)}
.popular-searches{
    display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-top:15px;
    font-size:12px
}
.popular-searches b{margin-right:6px;color:#203858}
.chip{
    border:1px solid #d6dfeb;border-radius:8px;padding:7px 13px;background:#fff;cursor:pointer;
    color:#2a466b;font-weight:700
}
.chip:hover{border-color:#9bb8e5;background:#f5f9ff}
.suggest{
    position:absolute;top:77px;left:0;right:0;z-index:50;
    background:#fff;border:1px solid #dce4ef;border-radius:12px;
    box-shadow:0 18px 40px rgba(20,46,85,.16);
    display:none;max-height:280px;overflow:auto
}
.suggest.show{display:block}
.suggest-item{padding:12px 14px;border-bottom:1px solid #eef2f7;cursor:pointer}
.suggest-item:hover{background:#f7faff}
.suggest-item strong{display:block;font-size:13px}
.suggest-item span{font-size:11px;color:#7a879a}

/* trust strip */
.trust-strip{padding:31px 0 26px;background:#fff}
.trust-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0}
.trust-item{
    display:flex;gap:14px;padding:0 23px;align-items:flex-start;border-right:1px solid #dce5f0
}
.trust-item:first-child{padding-left:0}
.trust-item:last-child{border-right:0}
.trust-icon{
    width:46px;height:46px;border-radius:50%;
    background:#eaf2ff;color:#0d6efd;display:grid;place-items:center;
    font-size:20px;font-weight:1000;flex:0 0 46px
}
.trust-item strong{display:block;font-size:14px;margin:4px 0 5px}
.trust-item p{margin:0;font-size:12px;color:#6e7d92;line-height:1.5}

/* destinations */
.destinations{background:#f8fafc;padding:43px 0 50px;border-top:1px solid #eef1f5}
.section-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:22px}
.section-head h2{font-size:29px;color:#0b2a59;margin:0;letter-spacing:-.02em}
.view-all{color:#0d6efd;font-size:13px;font-weight:800}
.hotel-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:18px}
.hotel-card{
    background:#fff;border:1px solid #dfe7f0;border-radius:9px;overflow:hidden;
    transition:.2s;position:relative
}
.hotel-card:hover{transform:translateY(-4px);box-shadow:0 16px 35px rgba(16,42,78,.11)}
.hotel-photo{height:165px;background-size:cover;background-position:center;position:relative}
.rating-badge{
    position:absolute;left:10px;top:10px;background:#092b5b;color:#fff;border-radius:5px;
    padding:5px 7px;font-size:11px;font-weight:900
}
.heart{
    position:absolute;right:10px;top:10px;width:29px;height:29px;border-radius:50%;
    background:rgba(9,43,91,.67);color:#fff;display:grid;place-items:center;font-size:15px
}
.hotel-body{padding:13px}
.hotel-title{font-size:13px;font-weight:900;color:#16335c;line-height:1.35;min-height:35px}
.stars{color:#f7ad1e;font-size:12px;margin:5px 0}
.hotel-place{color:#64758d;font-size:11px;margin-bottom:9px;min-height:30px}
.hotel-price{font-size:12px;color:#425774}
.hotel-price strong{font-size:16px;color:#0a2d60}
.tag{
    display:inline-block;margin-top:8px;background:#eef5ff;color:#2361bc;
    border-radius:4px;padding:5px 7px;font-size:10px;font-weight:750
}
.partners{
    margin-top:30px;background:#fff;border:1px solid #dfe7f0;border-radius:8px;padding:21px 28px;
    display:flex;align-items:center;justify-content:space-between;gap:25px;flex-wrap:wrap
}
.partners-title{font-size:12px;color:#53647b;max-width:160px}
.partner{font-weight:900;font-size:20px;color:#17345d;opacity:.88}
.partner.tbo{color:#0f376d}.partner.tbo span{color:#e6a522}
.partner.exp{font-size:18px}.partner.exp:before{content:"➤ ";color:#f5bd21}

/* dark features */
.dark{
    background:linear-gradient(135deg,#05234c,#062f66);
    color:#fff;padding:32px 0 36px
}
.dark-grid{display:grid;grid-template-columns:repeat(4,1fr)}
.dark-item{
    display:flex;gap:15px;padding:0 26px;border-right:1px solid rgba(255,255,255,.18)
}
.dark-item:first-child{padding-left:0}.dark-item:last-child{border:0}
.dark-icon{width:45px;height:45px;border-radius:50%;background:#0f4b8c;display:grid;place-items:center;flex:0 0 45px}
.dark-item strong{display:block;font-size:14px;margin:2px 0 5px}
.dark-item p{margin:0;font-size:12px;line-height:1.5;color:#dbe6f5}
.notice{
    margin-top:29px;border:1px solid #ead28b;background:#fff8e8;color:#85621b;
    border-radius:8px;padding:14px 17px;font-size:12px
}
.footer{
    background:#031b3b;color:#d8e2f0;font-size:12px;padding:22px 0
}
.footer .wrap{display:flex;justify-content:space-between;gap:20px;flex-wrap:wrap}

@media(max-width:1080px){
    .menu{gap:17px}
    .search-grid{grid-template-columns:repeat(3,1fr)}
    .search-button{width:100%}
    .hotel-grid{grid-template-columns:repeat(3,1fr)}
}
@media(max-width:820px){
    .menu,.nav-actions .phone{display:none}
    .hero .wrap{padding-top:38px}
    .trust-grid,.dark-grid{grid-template-columns:1fr 1fr;row-gap:25px}
    .trust-item:nth-child(2),.dark-item:nth-child(2){border-right:0}
    .hotel-grid{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:620px){
    .wrap{padding:0 15px}
    .topbar-right{display:none}
    .nav .wrap{height:68px}
    .booking-btn{padding:10px 12px}
    .hero{min-height:430px}
    .hero h1{font-size:39px}
    .hero-benefits{display:none}
    .search-grid{grid-template-columns:1fr}
    .trust-grid,.dark-grid,.hotel-grid{grid-template-columns:1fr}
    .trust-item,.dark-item{border-right:0;padding:0}
    .hotel-photo{height:210px}
}
</style>
</head>
<body>

<div class="topbar">
  <div class="wrap">
    <div class="topbar-left">
      <span>Best hotel deals worldwide ✈</span><span class="dot">|</span>
      <span>Real-time availability</span><span class="dot">|</span><span>24/7 Support</span>
    </div>
    <div class="topbar-right">
      <span>EUR €</span><span>🇪🇸 English</span><span>Help</span>
    </div>
  </div>
</div>

<nav class="nav">
  <div class="wrap">
    <a class="brand" href="/">
      <div class="brand-mark">M</div>
      <div class="brand-name">Mustafa<small>TRAVELS & TOURS</small></div>
    </a>
    <div class="menu">
      <a href="/">Home</a>
      <a href="/flights-v3.php">Flights</a>
      <a href="/hotels.php" class="active">Hotels</a>
      <a href="/umrah.php">Umrah</a>
      <a href="/hajj.php">Hajj</a>
      <a href="/visa.php">Visa</a>
      <a href="/offers.php">Offers</a>
      <a href="/contact.php">Contact</a>
    </div>
    <div class="nav-actions">
      <span class="phone">☎ +34 632 234 216</span>
      <a class="booking-btn" href="/my-booking.php">♙ &nbsp; My Booking</a>
    </div>
  </div>
</nav>

<header class="hero">
  <div class="wrap">
    <div class="eyebrow">BEST HOTEL DEALS WORLDWIDE</div>
    <h1>Find the right hotel,<br>at the right price</h1>
    <div class="hero-copy">Search from thousands of hotels with real-time availability, best rates, and clear pricing.</div>
    <div class="hero-benefits">
      <div class="hero-benefit">
        <div class="hero-icon">▣</div>
        <div><strong>Real-time availability</strong><span>Live inventory from our trusted partners</span></div>
      </div>
      <div class="hero-benefit">
        <div class="hero-icon">↯</div>
        <div><strong>Best price options</strong><span>Competitive rates with clear pricing</span></div>
      </div>
      <div class="hero-benefit">
        <div class="hero-icon">✓</div>
        <div><strong>Flexible options</strong><span>Refundable choices on selected hotels</span></div>
      </div>
    </div>
  </div>
</header>

<section class="search-shell">
  <div class="wrap">
    <div class="search-card">
      <div class="search-tab">▤ &nbsp; Hotels</div>
      <form id="hotelSearchForm" action="/hotel-results.php" method="get">
        <div class="search-grid">
          <div class="field">
            <label for="destination">DESTINATION</label>
            <input class="control" id="destination" name="destination" placeholder="City, hotel name or landmark" autocomplete="off" required>
            <input type="hidden" name="city_code" id="city_code">
            <input type="hidden" name="country_code" id="country_code">
            <div class="suggest" id="suggestions"></div>
          </div>

          <div class="field">
            <label for="checkin">CHECK-IN</label>
            <input class="control" type="date" id="checkin" name="checkin" min="<?=h($today)?>" value="<?=h($today)?>" required>
          </div>

          <div class="field">
            <label for="checkout">CHECK-OUT</label>
            <input class="control" type="date" id="checkout" name="checkout" min="<?=h($tomorrow)?>" value="<?=h($tomorrow)?>" required>
          </div>

          <div class="field">
            <label for="nationality">GUEST NATIONALITY</label>
            <select class="control" id="nationality" name="nationality">
              <option value="PK" selected>Pakistan</option>
              <option value="ES">Spain</option>
              <option value="IN">India</option>
              <option value="BD">Bangladesh</option>
              <option value="SA">Saudi Arabia</option>
              <option value="AE">United Arab Emirates</option>
              <option value="GB">United Kingdom</option>
              <option value="US">United States</option>
            </select>
          </div>

          <div class="field">
            <label for="adults">GUESTS & ROOMS</label>
            <select class="control" id="adults" name="adults">
              <option value="1">1 Adult · 1 Room</option>
              <option value="2" selected>2 Adults · 1 Room</option>
              <option value="3">3 Adults · 1 Room</option>
              <option value="4">4 Adults · 1 Room</option>
            </select>
            <input type="hidden" name="rooms" value="1">
            <input type="hidden" name="children" value="0">
          </div>

          <button type="submit" class="search-button">Search Hotels</button>
        </div>

        <div class="popular-searches">
          <b>Popular searches:</b>
          <button class="chip" type="button" data-city="Makkah/Mecca" data-code="127891" data-country="SA">Makkah</button>
          <button class="chip" type="button" data-city="Madinah" data-code="" data-country="SA">Madinah</button>
          <button class="chip" type="button" data-city="Dubai" data-code="" data-country="AE">Dubai</button>
          <button class="chip" type="button" data-city="Istanbul" data-code="" data-country="TR">Istanbul</button>
          <button class="chip" type="button" data-city="London" data-code="" data-country="GB">London</button>
          <button class="chip" type="button" data-city="Paris" data-code="" data-country="FR">Paris</button>
          <button class="chip" type="button" data-city="Kuala Lumpur" data-code="" data-country="MY">Kuala Lumpur</button>
          <button class="chip" type="button" data-city="New York" data-code="" data-country="US">New York</button>
        </div>
      </form>
    </div>
  </div>
</section>

<section class="trust-strip">
  <div class="wrap">
    <div class="trust-grid">
      <div class="trust-item">
        <div class="trust-icon">✹</div>
        <div><strong>Clear pricing</strong><p>Taxes and fees shown before you book.</p></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon">✓</div>
        <div><strong>Secure booking</strong><p>Your booking information is handled securely.</p></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon">◉</div>
        <div><strong>24/7 support</strong><p>Our team is always here to help you.</p></div>
      </div>
      <div class="trust-item">
        <div class="trust-icon">€</div>
        <div><strong>Multiple payment options</strong><p>Flexible payment methods where supported.</p></div>
      </div>
    </div>
  </div>
</section>

<section class="destinations">
  <div class="wrap">
    <div class="section-head">
      <h2>Popular destinations</h2>
      <a href="#hotelSearchForm" class="view-all">View all destinations →</a>
    </div>

    <div class="hotel-grid">
      <article class="hotel-card">
        <div class="hotel-photo" style="background-image:url('https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=700&q=82')">
          <span class="rating-badge">8.4</span><span class="heart">♡</span>
        </div>
        <div class="hotel-body">
          <div class="hotel-title">Featured Hotels in Makkah</div>
          <div class="stars">★★★★</div>
          <div class="hotel-place">Makkah, Saudi Arabia</div>
          <div class="hotel-price">Search live rates & availability</div>
          <span class="tag">TBO live inventory</span>
        </div>
      </article>

      <article class="hotel-card">
        <div class="hotel-photo" style="background-image:url('https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=700&q=82')">
          <span class="rating-badge">8.6</span><span class="heart">♡</span>
        </div>
        <div class="hotel-body">
          <div class="hotel-title">Featured Hotels in Madinah</div>
          <div class="stars">★★★★</div>
          <div class="hotel-place">Madinah, Saudi Arabia</div>
          <div class="hotel-price">Search live rates & availability</div>
          <span class="tag">Popular for Umrah</span>
        </div>
      </article>

      <article class="hotel-card">
        <div class="hotel-photo" style="background-image:url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=700&q=82')">
          <span class="rating-badge">8.7</span><span class="heart">♡</span>
        </div>
        <div class="hotel-body">
          <div class="hotel-title">Featured Hotels in Dubai</div>
          <div class="stars">★★★★★</div>
          <div class="hotel-place">Dubai, United Arab Emirates</div>
          <div class="hotel-price">Search city & resort deals</div>
          <span class="tag">City & resort stays</span>
        </div>
      </article>

      <article class="hotel-card">
        <div class="hotel-photo" style="background-image:url('https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=700&q=82')">
          <span class="rating-badge">8.5</span><span class="heart">♡</span>
        </div>
        <div class="hotel-body">
          <div class="hotel-title">Featured Hotels in Istanbul</div>
          <div class="stars">★★★★</div>
          <div class="hotel-place">Istanbul, Türkiye</div>
          <div class="hotel-price">Search central hotel deals</div>
          <span class="tag">Breakfast options</span>
        </div>
      </article>

      <article class="hotel-card">
        <div class="hotel-photo" style="background-image:url('https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=700&q=82')">
          <span class="rating-badge">8.3</span><span class="heart">♡</span>
        </div>
        <div class="hotel-body">
          <div class="hotel-title">Featured Hotels in London</div>
          <div class="stars">★★★★</div>
          <div class="hotel-place">London, United Kingdom</div>
          <div class="hotel-price">Search live rates & availability</div>
          <span class="tag">Great locations</span>
        </div>
      </article>
    </div>

    <div class="partners">
      <div class="partners-title">We work with leading global hotel partners</div>
      <div class="partner">agoda</div>
      <div class="partner">Booking.com</div>
      <div class="partner exp">Expedia</div>
      <div class="partner">Hotelbeds</div>
      <div class="partner tbo">TBO<span>HOLIDAYS</span></div>
    </div>
  </div>
</section>

<section class="dark">
  <div class="wrap">
    <div class="dark-grid">
      <div class="dark-item"><div class="dark-icon">◇</div><div><strong>Best hotel deals</strong><p>Exclusive hotel offers available through Mustafa Travels.</p></div></div>
      <div class="dark-item"><div class="dark-icon">✓</div><div><strong>Verified hotels</strong><p>Detailed hotel and room information before booking.</p></div></div>
      <div class="dark-item"><div class="dark-icon">♧</div><div><strong>Easy booking</strong><p>Quick, simple and customer-friendly booking process.</p></div></div>
      <div class="dark-item"><div class="dark-icon">▣</div><div><strong>Manage booking</strong><p>View booking details and request assistance easily.</p></div></div>
    </div>
    <div class="notice">ⓘ &nbsp; Hotel availability and rates are subject to change until final confirmation at the PreBook stage.</div>
  </div>
</section>

<footer class="footer">
  <div class="wrap">
    <span>© <?=date('Y')?> Mustafa Travels & Tours · Barcelona, Spain</span>
    <span>www.mustafatravels.org</span>
  </div>
</footer>

<script>
const destination = document.getElementById('destination');
const cityCode = document.getElementById('city_code');
const countryCode = document.getElementById('country_code');
const suggestions = document.getElementById('suggestions');
const checkin = document.getElementById('checkin');
const checkout = document.getElementById('checkout');

const citySeed = [
  {name:'Makkah/Mecca', code:'127891', country:'SA', sub:'Saudi Arabia'},
  {name:'Madinah', code:'', country:'SA', sub:'Saudi Arabia'},
  {name:'Dubai', code:'', country:'AE', sub:'United Arab Emirates'},
  {name:'Istanbul', code:'', country:'TR', sub:'Türkiye'},
  {name:'London', code:'', country:'GB', sub:'United Kingdom'},
  {name:'Paris', code:'', country:'FR', sub:'France'},
  {name:'Kuala Lumpur', code:'', country:'MY', sub:'Malaysia'},
  {name:'New York', code:'', country:'US', sub:'United States'}
];

function setDestination(name, code, country){
  destination.value = name || '';
  cityCode.value = code || '';
  countryCode.value = country || '';
  suggestions.classList.remove('show');
}

destination.addEventListener('input', () => {
  cityCode.value = '';
  countryCode.value = '';
  const q = destination.value.trim().toLowerCase();
  if(!q){ suggestions.classList.remove('show'); return; }
  const items = citySeed.filter(x => (x.name+' '+x.sub).toLowerCase().includes(q)).slice(0,8);
  if(!items.length){ suggestions.classList.remove('show'); return; }
  suggestions.innerHTML = items.map(x =>
    `<div class="suggest-item" data-name="${x.name}" data-code="${x.code}" data-country="${x.country}">
       <strong>${x.name}</strong><span>${x.sub}</span>
     </div>`).join('');
  suggestions.classList.add('show');
});

suggestions.addEventListener('click', e => {
  const item = e.target.closest('.suggest-item');
  if(!item) return;
  setDestination(item.dataset.name,item.dataset.code,item.dataset.country);
});

document.querySelectorAll('.chip').forEach(btn => {
  btn.addEventListener('click', () => {
    setDestination(btn.dataset.city,btn.dataset.code,btn.dataset.country);
    destination.focus();
  });
});

document.addEventListener('click', e => {
  if(!e.target.closest('.field')) suggestions.classList.remove('show');
});

checkin.addEventListener('change', () => {
  if(!checkin.value) return;
  const d = new Date(checkin.value+'T00:00:00');
  d.setDate(d.getDate()+1);
  const next = d.toISOString().slice(0,10);
  checkout.min = next;
  if(!checkout.value || checkout.value <= checkin.value) checkout.value = next;
});

document.getElementById('hotelSearchForm').addEventListener('submit', e => {
  if(!destination.value.trim()){
    e.preventDefault(); destination.focus(); return;
  }
  if(checkout.value <= checkin.value){
    e.preventDefault();
    alert('Check-out date must be after check-in date.');
  }
});
</script>
</body>
</html>
