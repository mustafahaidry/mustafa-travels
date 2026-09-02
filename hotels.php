<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hotels | Mustafa Travels & Tours</title>
<meta name="description" content="Search hotels worldwide with Mustafa Travels & Tours.">
<link rel="preconnect" href="https://images.unsplash.com">
<style>
:root{
  --navy:#071d44;
  --navy2:#0b2f68;
  --blue:#0b63f6;
  --blue2:#1f76ff;
  --gold:#f0aa16;
  --ink:#102446;
  --muted:#68758a;
  --line:#dde5ef;
  --soft:#f6f8fb;
  --white:#fff;
  --danger:#ff3b30;
  --shadow:0 18px 48px rgba(15,39,77,.14);
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  margin:0;
  font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;
  color:var(--ink);
  background:#fff;
}
a{text-decoration:none;color:inherit}
button,input,select{font:inherit}
.wrap{max-width:1240px;margin:0 auto;padding:0 24px}

/* utility */
.utility{
  background:#031938;
  color:#fff;
  font-size:13px;
}
.utility .wrap{
  min-height:32px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:20px;
}
.utility-left,.utility-right{display:flex;gap:18px;align-items:center}
.sep{opacity:.35}

/* nav */
.nav{
  background:#fff;
  border-bottom:1px solid #eef2f6;
  position:sticky;
  top:0;
  z-index:100;
}
.nav .wrap{
  height:76px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:20px;
}
.brand{
  display:flex;
  align-items:center;
  gap:12px;
  min-width:max-content;
}
.brandmark{
  width:48px;height:48px;
  border-radius:10px;
  background:linear-gradient(135deg,var(--navy),var(--blue));
  color:#fff;
  display:grid;
  place-items:center;
  font-size:26px;
  font-weight:1000;
  position:relative;
}
.brandmark:after{
  content:"↗";
  position:absolute;
  right:-7px;
  top:-8px;
  color:var(--gold);
  font-size:21px;
}
.brandtext{
  font-weight:950;
  color:#0a2d63;
  font-size:22px;
  line-height:1;
}
.brandtext small{
  display:block;
  margin-top:5px;
  color:#e39a12;
  font-size:10px;
  letter-spacing:.08em;
}
.menu{
  display:flex;
  gap:26px;
  align-items:center;
  font-size:14px;
  font-weight:760;
}
.menu a{padding:28px 0 22px;position:relative}
.menu a.active{color:#0b4fae}
.menu a.active:after{
  content:"";
  position:absolute;
  left:0;right:0;bottom:14px;
  height:3px;border-radius:999px;background:#0b4fae;
}
.nav-right{
  display:flex;
  align-items:center;
  gap:18px;
  font-size:14px;
  font-weight:760;
}
.mybooking{
  border:1px solid #ccd8e7;
  border-radius:10px;
  padding:11px 16px;
  background:#fff;
}

/* hero */
.hero{
  position:relative;
  color:#fff;
  background:
    linear-gradient(90deg,rgba(3,24,55,.98) 0%,rgba(5,38,83,.88) 42%,rgba(5,38,83,.46) 73%,rgba(3,24,55,.18) 100%),
    url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1900&q=88') center/cover no-repeat;
}
.hero .wrap{
  min-height:340px;
  padding-top:22px;
  padding-bottom:74px;
  display:flex;
  align-items:center;
}
.hero-copy{max-width:670px}
.eyebrow{
  display:inline-flex;
  border:1px solid rgba(240,170,22,.75);
  border-radius:7px;
  padding:6px 10px;
  color:#ffc342;
  font-size:11px;
  font-weight:900;
  letter-spacing:.03em;
}
.hero h1{
  margin:10px 0 10px;
  font-size:clamp(42px,5vw,62px);
  line-height:1.02;
  letter-spacing:-.03em;
}
.hero p{
  max-width:590px;
  margin:0;
  color:#eef5ff;
  font-size:17px;
  line-height:1.55;
}
.hero-benefits{
  margin-top:22px;
  display:flex;
  gap:30px;
  flex-wrap:wrap;
}
.hero-benefit{
  display:flex;
  gap:12px;
  align-items:center;
  max-width:230px;
}
.hero-benefit i{
  width:43px;height:43px;border-radius:50%;
  border:1px solid rgba(240,170,22,.65);
  color:#ffc342;
  display:grid;place-items:center;
  background:rgba(4,31,70,.48);
  font-style:normal;
  font-weight:900;
}
.hero-benefit strong{display:block;font-size:13px}
.hero-benefit span{display:block;font-size:12px;color:#dce8f8;line-height:1.4;margin-top:2px}

/* search box */
.search-wrap{
  margin-top:-48px;
  position:relative;
  z-index:30;
}
.search-card{
  background:#fff;
  border:1px solid #e3e9f1;
  border-radius:18px;
  box-shadow:var(--shadow);
  padding:0 18px 18px;
}
.mode-tabs{
  display:flex;
  align-items:center;
  gap:28px;
  border-bottom:1px solid #edf1f5;
}
.mode-tab{
  padding:17px 8px 13px;
  font-size:14px;
  font-weight:850;
  color:#53627b;
  cursor:pointer;
  border:none;
  background:transparent;
  position:relative;
}
.mode-tab.active{color:#0a2c61}
.mode-tab.active:after{
  content:"";
  position:absolute;
  left:0;right:0;bottom:-1px;
  height:3px;border-radius:999px;background:var(--blue);
}
.new{
  margin-left:6px;
  font-size:10px;
  padding:3px 6px;
  border-radius:5px;
  background:#dff3ff;
  color:#0878bc;
}
.search-grid{
  display:grid;
  grid-template-columns:1.55fr 1.15fr 1.15fr 1fr 1fr auto;
  gap:12px;
  align-items:end;
  padding-top:14px;
}
.field{position:relative}
.field label{
  display:block;
  margin:0 0 6px 2px;
  font-size:10px;
  color:#5e6e88;
  font-weight:900;
  letter-spacing:.04em;
}
.control{
  width:100%;
  height:52px;
  border:1px solid #cfd9e8;
  border-radius:9px;
  background:#fff;
  color:#173259;
  padding:0 13px;
  outline:none;
}
.control:focus{
  border-color:#83adff;
  box-shadow:0 0 0 4px rgba(11,99,246,.08);
}
.search-btn{
  height:52px;
  border:0;
  border-radius:9px;
  background:linear-gradient(135deg,var(--blue),var(--blue2));
  color:#fff;
  font-weight:900;
  padding:0 26px;
  cursor:pointer;
  box-shadow:0 10px 22px rgba(11,99,246,.24);
}
.popular{
  display:flex;
  gap:8px;
  align-items:center;
  flex-wrap:wrap;
  margin-top:13px;
  font-size:12px;
}
.popular strong{margin-right:4px}
.chip{
  border:1px solid #d7e0eb;
  border-radius:8px;
  padding:7px 12px;
  background:#fff;
  color:#21446f;
  font-weight:760;
  cursor:pointer;
}
.suggest{
  position:absolute;
  top:73px;left:0;right:0;
  background:#fff;
  border:1px solid #dce4ef;
  border-radius:11px;
  box-shadow:0 18px 42px rgba(17,42,78,.16);
  z-index:90;
  display:none;
  max-height:260px;
  overflow:auto;
}
.suggest.show{display:block}
.suggest-item{
  padding:11px 13px;
  border-bottom:1px solid #eff2f6;
  cursor:pointer;
}
.suggest-item:hover{background:#f6f9ff}
.suggest-item strong{display:block;font-size:13px}
.suggest-item small{color:#7a8798}

/* multi city */
.multicity{
  display:none;
  padding:16px 0 0;
}
.multicity.show{display:block}
.stay-row{
  display:grid;
  grid-template-columns:1.4fr 1fr 1fr auto;
  gap:10px;
  align-items:end;
  margin-bottom:10px;
}
.remove-stay{
  height:48px;border:1px solid #e4b7b4;background:#fff5f4;color:#b52c25;border-radius:9px;padding:0 14px;cursor:pointer
}
.add-stay{
  margin-top:4px;border:1px solid #b8c9e3;background:#f8fbff;color:#0b4fae;border-radius:8px;padding:9px 13px;font-weight:800;cursor:pointer
}

/* section headers */
.section{
  padding:32px 0 0;
}
.section-head{
  display:flex;justify-content:space-between;align-items:center;gap:20px;margin-bottom:14px;
}
.section-head h2{margin:0;font-size:26px;color:#0a2c61}
.viewall{font-size:12px;color:#0b63f6;font-weight:850}

/* destination cards */
.destinations{
  display:grid;
  grid-template-columns:repeat(5,1fr);
  gap:14px;
}
.destination-card{
  border:1px solid var(--line);
  border-radius:9px;
  overflow:hidden;
  background:#fff;
}
.destination-photo{
  height:126px;
  background-size:cover;
  background-position:center;
}
.destination-body{padding:10px 12px 11px}
.destination-title-row{
  display:flex;justify-content:space-between;gap:8px;align-items:center
}
.destination-title{
  font-size:13px;
  font-weight:900;
  color:#153661;
}
.destination-rating{
  color:#f4a916;
  font-size:11px;
  font-weight:900;
}
.destination-loc{
  font-size:11px;
  color:#2b67c2;
  margin-top:3px;
}
.destination-desc{
  font-size:11px;
  color:#5f6f86;
  line-height:1.4;
  margin-top:6px;
  min-height:31px;
}
.destination-link{
  display:inline-block;
  margin-top:7px;
  font-size:11px;
  color:#0b63f6;
  font-weight:850;
}

/* deals */
.deals{
  display:grid;
  grid-template-columns:repeat(5,1fr);
  gap:14px;
}
.deal-card{
  display:grid;
  grid-template-columns:90px 1fr;
  border:1px solid var(--line);
  border-radius:9px;
  overflow:hidden;
  background:#fff;
  min-height:132px;
  position:relative;
}
.deal-photo{
  background-size:cover;
  background-position:center;
}
.deal-body{padding:10px}
.deal-badge{
  position:absolute;
  top:6px;left:6px;
  background:#ff3838;
  color:#fff;
  border-radius:4px;
  font-size:9px;
  font-weight:900;
  padding:4px 6px;
}
.deal-title{font-size:11px;font-weight:900;color:#17355d;line-height:1.35}
.deal-stars{font-size:10px;color:#f2a916;margin-top:3px}
.deal-meta{font-size:10px;color:#687890;margin-top:4px;line-height:1.35}
.deal-price{margin-top:5px;font-size:17px;font-weight:950;color:#14335c}
.deal-old{font-size:10px;color:#8a95a5;text-decoration:line-through;margin-left:4px}
.deal-btn{
  margin-top:5px;
  border:1px solid #b8c9e3;
  background:#fff;
  color:#0b63f6;
  border-radius:5px;
  font-size:9px;
  font-weight:900;
  padding:5px 7px;
}

/* partners */
.partners{
  margin:22px 0 0;
  border:1px solid var(--line);
  border-radius:8px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:20px;
  padding:18px 24px;
  flex-wrap:wrap;
}
.partners-label{font-size:11px;color:#52647d;max-width:150px}
.partner{font-weight:950;font-size:18px;color:#17355d}
.partner.tbo span{color:#e9a51e}
.partner.exp:before{content:"➤ ";color:#f0b51d}

/* notice */
.notice{
  margin:12px 0 22px;
  border:1px solid #efcf79;
  background:#fff8e7;
  color:#7e5b12;
  border-radius:8px;
  padding:12px 14px;
  font-size:11px;
}

/* footer */
.footer{
  background:#031938;
  color:#dce5f2;
  font-size:12px;
  padding:20px 0;
}
.footer .wrap{
  display:flex;
  justify-content:space-between;
  gap:20px;
  flex-wrap:wrap;
}

/* responsive */
@media(max-width:1100px){
  .menu{gap:16px}
  .search-grid{grid-template-columns:repeat(3,1fr)}
  .search-btn{width:100%}
  .destinations,.deals{grid-template-columns:repeat(3,1fr)}
}
@media(max-width:820px){
  .menu,.nav-right .phone{display:none}
  .hero .wrap{min-height:320px}
  .hero-benefits{gap:16px}
  .destinations,.deals{grid-template-columns:repeat(2,1fr)}
  .stay-row{grid-template-columns:1fr 1fr}
  .stay-row .remove-stay{width:100%}
}
@media(max-width:620px){
  .wrap{padding:0 14px}
  .utility-right{display:none}
  .nav .wrap{height:66px}
  .brandtext{font-size:18px}
  .hero h1{font-size:39px}
  .hero-benefits{display:none}
  .search-grid{grid-template-columns:1fr}
  .mode-tabs{gap:12px;overflow-x:auto}
  .destinations,.deals{grid-template-columns:1fr}
  .deal-card{grid-template-columns:120px 1fr}
  .stay-row{grid-template-columns:1fr}
}
</style>
</head>
<body>

<div class="utility">
  <div class="wrap">
    <div class="utility-left">
      <span>▣ Best hotel deals worldwide</span><span class="sep">|</span>
      <span>▣ Real-time availability</span><span class="sep">|</span>
      <span>◷ 24/7 Support</span>
    </div>
    <div class="utility-right">
      <span>EUR €</span><span>🇬🇧 English⌄</span>
    </div>
  </div>
</div>

<nav class="nav">
  <div class="wrap">
    <a class="brand" href="/">
      <div class="brandmark">M</div>
      <div class="brandtext">Mustafa<small>TRAVELS & TOURS</small></div>
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

    <div class="nav-right">
      <span class="phone">☎ +34 632 234 216</span>
      <a href="/my-booking.php" class="mybooking">♙ &nbsp; My Booking</a>
    </div>
  </div>
</nav>

<header class="hero">
  <div class="wrap">
    <div class="hero-copy">
      <div class="eyebrow">BEST HOTEL DEALS WORLDWIDE</div>
      <h1>Find the right hotel,<br>at the right price</h1>
      <p>Search from thousands of hotels with real-time availability, best rates, and clear pricing.</p>

      <div class="hero-benefits">
        <div class="hero-benefit">
          <i>▣</i>
          <div><strong>Real-time availability</strong><span>Live inventory from our trusted partners</span></div>
        </div>
        <div class="hero-benefit">
          <i>◇</i>
          <div><strong>Best price guarantee</strong><span>Competitive rates with no hidden charges</span></div>
        </div>
        <div class="hero-benefit">
          <i>✓</i>
          <div><strong>Flexible options</strong><span>Free cancellation on selected hotels</span></div>
        </div>
      </div>
    </div>
  </div>
</header>

<section class="search-wrap">
  <div class="wrap">
    <div class="search-card">
      <div class="mode-tabs">
        <button type="button" class="mode-tab active" data-mode="single">Single Stay</button>
        <button type="button" class="mode-tab" data-mode="multi">Multi-City / Umrah Trip <span class="new">New</span></button>
      </div>

      <form action="/hotel-results.php" method="get" id="hotelSearchForm">
        <div id="singleMode">
          <div class="search-grid">
            <div class="field">
              <label for="destination">DESTINATION</label>
              <input class="control" id="destination" name="destination" placeholder="City, hotel name or landmark" autocomplete="off" required>
              <input type="hidden" id="city_code" name="city_code">
              <input type="hidden" id="country_code" name="country_code">
              <div class="suggest" id="suggestions"></div>
            </div>

            <div class="field">
              <label for="checkin">CHECK-IN</label>
              <input class="control" type="date" id="checkin" name="checkin" value="<?=h($today)?>" min="<?=h($today)?>" required>
            </div>

            <div class="field">
              <label for="checkout">CHECK-OUT</label>
              <input class="control" type="date" id="checkout" name="checkout" value="<?=h($tomorrow)?>" min="<?=h($tomorrow)?>" required>
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

            <button class="search-btn" type="submit">Search Hotels</button>
          </div>

          <div class="popular">
            <strong>Popular searches:</strong>
            <button type="button" class="chip" data-city="Makkah/Mecca" data-code="127891" data-country="SA">Makkah</button>
            <button type="button" class="chip" data-city="Madinah" data-code="" data-country="SA">Madinah</button>
            <button type="button" class="chip" data-city="Dubai" data-code="" data-country="AE">Dubai</button>
            <button type="button" class="chip" data-city="Istanbul" data-code="" data-country="TR">Istanbul</button>
            <button type="button" class="chip" data-city="London" data-code="" data-country="GB">London</button>
            <button type="button" class="chip" data-city="Paris" data-code="" data-country="FR">Paris</button>
            <button type="button" class="chip" data-city="Kuala Lumpur" data-code="" data-country="MY">Kuala Lumpur</button>
            <button type="button" class="chip" data-city="New York" data-code="" data-country="US">New York</button>
          </div>
        </div>

        <div class="multicity" id="multiMode">
          <div id="stayRows"></div>
          <button type="button" class="add-stay" id="addStay">+ Add another stay</button>

          <div style="display:grid;grid-template-columns:1fr 1fr auto;gap:12px;align-items:end;margin-top:14px">
            <div class="field">
              <label>GUEST NATIONALITY</label>
              <select class="control" name="multi_nationality">
                <option value="PK" selected>Pakistan</option>
                <option value="ES">Spain</option>
                <option value="IN">India</option>
                <option value="BD">Bangladesh</option>
                <option value="SA">Saudi Arabia</option>
              </select>
            </div>
            <div class="field">
              <label>GUESTS & ROOMS</label>
              <select class="control" name="multi_adults">
                <option value="2" selected>2 Adults · 1 Room</option>
                <option value="3">3 Adults · 1 Room</option>
                <option value="4">4 Adults · 1 Room</option>
              </select>
            </div>
            <button class="search-btn" type="submit">Search Trip</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<main class="wrap">

  <section class="section">
    <div class="section-head">
      <h2>Popular destinations</h2>
      <a href="#hotelSearchForm" class="viewall">View all destinations →</a>
    </div>

    <div class="destinations">
      <article class="destination-card">
        <div class="destination-photo" style="background-image:url('https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?auto=format&fit=crop&w=900&q=85')"></div>
        <div class="destination-body">
          <div class="destination-title-row"><div class="destination-title">Makkah</div><div class="destination-rating">★ 4.8</div></div>
          <div class="destination-loc">⌖ Makkah, Saudi Arabia</div>
          <div class="destination-desc">Hotels near Masjid Al Haram & surrounding areas</div>
          <a href="#hotelSearchForm" class="destination-link" data-city="Makkah/Mecca" data-code="127891" data-country="SA">Search hotels →</a>
        </div>
      </article>

      <article class="destination-card">
        <div class="destination-photo" style="background-image:url('https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?auto=format&fit=crop&w=900&q=85')"></div>
        <div class="destination-body">
          <div class="destination-title-row"><div class="destination-title">Madinah</div><div class="destination-rating">★ 4.8</div></div>
          <div class="destination-loc">⌖ Madinah, Saudi Arabia</div>
          <div class="destination-desc">Hotels near Masjid An Nabawi & Central Madinah</div>
          <a href="#hotelSearchForm" class="destination-link" data-city="Madinah" data-code="" data-country="SA">Search hotels →</a>
        </div>
      </article>

      <article class="destination-card">
        <div class="destination-photo" style="background-image:url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=900&q=85')"></div>
        <div class="destination-body">
          <div class="destination-title-row"><div class="destination-title">Dubai</div><div class="destination-rating">★ 4.7</div></div>
          <div class="destination-loc">⌖ Dubai, United Arab Emirates</div>
          <div class="destination-desc">City hotels, resorts & luxury apartments</div>
          <a href="#hotelSearchForm" class="destination-link" data-city="Dubai" data-code="" data-country="AE">Search hotels →</a>
        </div>
      </article>

      <article class="destination-card">
        <div class="destination-photo" style="background-image:url('https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=900&q=85')"></div>
        <div class="destination-body">
          <div class="destination-title-row"><div class="destination-title">Istanbul</div><div class="destination-rating">★ 4.6</div></div>
          <div class="destination-loc">⌖ Istanbul, Türkiye</div>
          <div class="destination-desc">Central, historic & family-friendly stays</div>
          <a href="#hotelSearchForm" class="destination-link" data-city="Istanbul" data-code="" data-country="TR">Search hotels →</a>
        </div>
      </article>

      <article class="destination-card">
        <div class="destination-photo" style="background-image:url('https://images.unsplash.com/photo-1564507592333-c60657eea523?auto=format&fit=crop&w=900&q=85')"></div>
        <div class="destination-body">
          <div class="destination-title-row"><div class="destination-title">India</div><div class="destination-rating">★ 4.6</div></div>
          <div class="destination-loc">India</div>
          <div class="destination-desc">Explore top cities & iconic travel experiences</div>
          <a href="#hotelSearchForm" class="destination-link" data-city="Agra" data-code="" data-country="IN">Search hotels →</a>
        </div>
      </article>
    </div>
  </section>

  <section class="section">
    <div class="section-head">
      <h2>Exclusive hotel deals</h2>
      <a href="/hotel-deals.php" class="viewall">View all deals →</a>
    </div>

    <div class="deals" id="dealGrid">
      <article class="deal-card">
        <div class="deal-photo" style="background-image:url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=500&q=80')"></div>
        <span class="deal-badge">HOT DEAL</span>
        <div class="deal-body">
          <div class="deal-title">Featured Makkah Hotel</div>
          <div class="deal-stars">★★★★</div>
          <div class="deal-meta">Makkah, Saudi Arabia<br>Shuttle · Breakfast</div>
          <div class="deal-price">€145<span class="deal-old">€175</span></div>
          <button class="deal-btn" type="button">View Deal</button>
        </div>
      </article>

      <article class="deal-card">
        <div class="deal-photo" style="background-image:url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=500&q=80')"></div>
        <span class="deal-badge">20% OFF</span>
        <div class="deal-body">
          <div class="deal-title">Featured Madinah Hotel</div>
          <div class="deal-stars">★★★★</div>
          <div class="deal-meta">Madinah, Saudi Arabia<br>Breakfast · Free WiFi</div>
          <div class="deal-price">€110<span class="deal-old">€138</span></div>
          <button class="deal-btn" type="button">View Deal</button>
        </div>
      </article>

      <article class="deal-card">
        <div class="deal-photo" style="background-image:url('https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=500&q=80')"></div>
        <span class="deal-badge">15% OFF</span>
        <div class="deal-body">
          <div class="deal-title">Featured Dubai Hotel</div>
          <div class="deal-stars">★★★★★</div>
          <div class="deal-meta">Downtown Dubai<br>Breakfast · Pool</div>
          <div class="deal-price">€210<span class="deal-old">€248</span></div>
          <button class="deal-btn" type="button">View Deal</button>
        </div>
      </article>

      <article class="deal-card">
        <div class="deal-photo" style="background-image:url('https://images.unsplash.com/photo-1524231757912-21f4fe3a7200?auto=format&fit=crop&w=500&q=80')"></div>
        <span class="deal-badge">10% OFF</span>
        <div class="deal-body">
          <div class="deal-title">Featured Istanbul Hotel</div>
          <div class="deal-stars">★★★★</div>
          <div class="deal-meta">Istanbul, Türkiye<br>Breakfast · Free WiFi</div>
          <div class="deal-price">€95<span class="deal-old">€105</span></div>
          <button class="deal-btn" type="button">View Deal</button>
        </div>
      </article>

      <article class="deal-card">
        <div class="deal-photo" style="background-image:url('https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=500&q=80')"></div>
        <span class="deal-badge">HOT DEAL</span>
        <div class="deal-body">
          <div class="deal-title">Featured London Hotel</div>
          <div class="deal-stars">★★★★</div>
          <div class="deal-meta">London, United Kingdom<br>Breakfast · Free WiFi</div>
          <div class="deal-price">€120<span class="deal-old">€150</span></div>
          <button class="deal-btn" type="button">View Deal</button>
        </div>
      </article>
    </div>

    <div class="partners">
      <div class="partners-label">We work with leading global hotel partners</div>
      <div class="partner">agoda</div>
      <div class="partner">Booking.com</div>
      <div class="partner exp">Expedia</div>
      <div class="partner">Hotelbeds</div>
      <div class="partner tbo">TBO<span>HOLIDAYS</span></div>
    </div>

    <div class="notice">ⓘ &nbsp; Hotel availability and rates are subject to change until final confirmation at the PreBook stage.</div>
  </section>
</main>

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
const singleMode = document.getElementById('singleMode');
const multiMode = document.getElementById('multiMode');
const stayRows = document.getElementById('stayRows');

const cities = [
  {name:'Makkah/Mecca',code:'127891',country:'SA',sub:'Saudi Arabia'},
  {name:'Madinah',code:'',country:'SA',sub:'Saudi Arabia'},
  {name:'Dubai',code:'',country:'AE',sub:'United Arab Emirates'},
  {name:'Istanbul',code:'',country:'TR',sub:'Türkiye'},
  {name:'London',code:'',country:'GB',sub:'United Kingdom'},
  {name:'Paris',code:'',country:'FR',sub:'France'},
  {name:'Kuala Lumpur',code:'',country:'MY',sub:'Malaysia'},
  {name:'New York',code:'',country:'US',sub:'United States'},
  {name:'Agra',code:'',country:'IN',sub:'India'}
];

function setDestination(name,code,country){
  destination.value=name||'';
  cityCode.value=code||'';
  countryCode.value=country||'';
  suggestions.classList.remove('show');
}

destination.addEventListener('input',()=>{
  const q=destination.value.trim().toLowerCase();
  cityCode.value='';countryCode.value='';
  if(!q){suggestions.classList.remove('show');return}
  const matches=cities.filter(x=>(x.name+' '+x.sub).toLowerCase().includes(q)).slice(0,8);
  if(!matches.length){suggestions.classList.remove('show');return}
  suggestions.innerHTML=matches.map(x=>`
    <div class="suggest-item" data-name="${x.name}" data-code="${x.code}" data-country="${x.country}">
      <strong>${x.name}</strong><small>${x.sub}</small>
    </div>`).join('');
  suggestions.classList.add('show');
});

suggestions.addEventListener('click',e=>{
  const item=e.target.closest('.suggest-item');
  if(!item)return;
  setDestination(item.dataset.name,item.dataset.code,item.dataset.country);
});

document.querySelectorAll('.chip,.destination-link').forEach(el=>{
  el.addEventListener('click',e=>{
    if(el.classList.contains('destination-link')) e.preventDefault();
    setDestination(el.dataset.city,el.dataset.code,el.dataset.country);
    document.getElementById('hotelSearchForm').scrollIntoView({behavior:'smooth',block:'center'});
    destination.focus();
  });
});

checkin.addEventListener('change',()=>{
  if(!checkin.value)return;
  const d=new Date(checkin.value+'T00:00:00');
  d.setDate(d.getDate()+1);
  const next=d.toISOString().slice(0,10);
  checkout.min=next;
  if(!checkout.value || checkout.value<=checkin.value) checkout.value=next;
});

document.querySelectorAll('.mode-tab').forEach(tab=>{
  tab.addEventListener('click',()=>{
    document.querySelectorAll('.mode-tab').forEach(x=>x.classList.remove('active'));
    tab.classList.add('active');
    const mode=tab.dataset.mode;
    if(mode==='single'){
      singleMode.style.display='block';
      multiMode.classList.remove('show');
      destination.required=true;checkin.required=true;checkout.required=true;
    }else{
      singleMode.style.display='none';
      multiMode.classList.add('show');
      destination.required=false;checkin.required=false;checkout.required=false;
      if(!stayRows.children.length){
        addStayRow('Makkah/Mecca','127891','SA');
        addStayRow('Madinah','','SA');
        addStayRow('Makkah/Mecca','127891','SA');
      }
    }
  })
});

let stayIndex=0;
function addStayRow(city='',code='',country='SA'){
  stayIndex++;
  const row=document.createElement('div');
  row.className='stay-row';
  row.innerHTML=`
    <div class="field">
      <label>STAY ${stayIndex} DESTINATION</label>
      <input class="control" name="stays[${stayIndex}][destination]" value="${city}" placeholder="City" required>
      <input type="hidden" name="stays[${stayIndex}][city_code]" value="${code}">
      <input type="hidden" name="stays[${stayIndex}][country_code]" value="${country}">
    </div>
    <div class="field">
      <label>CHECK-IN</label>
      <input class="control" type="date" name="stays[${stayIndex}][checkin]" required>
    </div>
    <div class="field">
      <label>CHECK-OUT</label>
      <input class="control" type="date" name="stays[${stayIndex}][checkout]" required>
    </div>
    <button type="button" class="remove-stay">Remove</button>
  `;
  row.querySelector('.remove-stay').addEventListener('click',()=>row.remove());
  stayRows.appendChild(row);
}
document.getElementById('addStay').addEventListener('click',()=>addStayRow());

document.getElementById('hotelSearchForm').addEventListener('submit',e=>{
  if(singleMode.style.display!=='none'){
    if(!destination.value.trim()){e.preventDefault();destination.focus();return}
    if(checkout.value<=checkin.value){
      e.preventDefault();
      alert('Check-out date must be after check-in date.');
    }
  }
});
</script>
</body>
</html>
