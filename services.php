<?php require_once __DIR__.'/partials.php'; site_header('Services'); ?>

<style>
/* MUSTAFA SERVICES V2.1 SAFE */
#mt-services{--navy:#06284a;--navy2:#0b4f83;--blue:#0b79d0;--gold:#f7b928;--ink:#08233f;--muted:#61768c;--line:#dce7f1;--soft:#f4f9fd}
#mt-services *{box-sizing:border-box}
#mt-services .sv-container{width:min(1180px,calc(100% - 40px));margin:0 auto}

/* Guaranteed visible hero - no external image dependency */
#mt-services .sv-hero{
  min-height:360px!important;
  display:flex!important;
  align-items:center!important;
  position:relative!important;
  overflow:hidden!important;
  background:
    radial-gradient(circle at 82% 26%,rgba(247,185,40,.22),transparent 22%),
    linear-gradient(120deg,#041f3a 0%,#073b68 52%,#0d6aa1 100%)!important;
  color:#fff!important;
}
#mt-services .sv-hero:before{
  content:"";position:absolute;inset:0;opacity:.18;
  background-image:
    linear-gradient(30deg,rgba(255,255,255,.22) 12%,transparent 12.5%,transparent 87%,rgba(255,255,255,.22) 87.5%),
    linear-gradient(150deg,rgba(255,255,255,.16) 12%,transparent 12.5%,transparent 87%,rgba(255,255,255,.16) 87.5%);
  background-size:70px 120px;
}
#mt-services .sv-hero-inner{position:relative;z-index:2;padding:68px 0!important;max-width:780px}
#mt-services .sv-kicker{display:inline-block;color:#ffc43d;font-size:12px;font-weight:900;letter-spacing:2.2px;margin-bottom:14px}
#mt-services .sv-hero h1{color:#fff!important;font:900 clamp(40px,5vw,62px)/1.02 Manrope,Inter,sans-serif!important;margin:0 0 18px!important;letter-spacing:-1.6px!important;max-width:760px}
#mt-services .sv-hero p{color:#d7e7f4!important;font-size:18px!important;line-height:1.7!important;max-width:680px!important;margin:0 0 26px!important}
#mt-services .hero-actions{display:flex;gap:12px;flex-wrap:wrap}
#mt-services .sv-btn{display:inline-flex;align-items:center;justify-content:center;padding:13px 19px;border-radius:10px;text-decoration:none;font-weight:850;font-size:14px}
#mt-services .sv-btn.gold{background:var(--gold);color:#08233f}
#mt-services .sv-btn.ghost{border:1px solid rgba(255,255,255,.42);color:#fff;background:rgba(255,255,255,.08)}

/* Main section */
#mt-services .sv-main{background:linear-gradient(180deg,#f7fbff 0%,#fff 68%);padding:72px 0 66px}
#mt-services .section-head{text-align:center!important;max-width:760px!important;margin:0 auto 38px!important;display:block!important}
#mt-services .section-head .sv-kicker{color:var(--blue)!important;margin:0 0 10px!important;display:block!important}
#mt-services .section-head h2{display:block!important;font:900 clamp(31px,4vw,44px)/1.08 Manrope,Inter,sans-serif!important;color:var(--ink)!important;margin:0 0 12px!important;letter-spacing:-.8px}
#mt-services .section-head p{display:block!important;color:var(--muted)!important;font-size:16px!important;line-height:1.65!important;margin:0 auto!important;max-width:680px!important}

#mt-services .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
#mt-services .service-card{
  position:relative;min-height:292px;padding:28px;border:1px solid var(--line);
  border-radius:20px;background:#fff;box-shadow:0 12px 34px rgba(10,48,83,.07);
  overflow:hidden;transition:.25s ease
}
#mt-services .service-card:hover{transform:translateY(-5px);box-shadow:0 18px 42px rgba(10,48,83,.13);border-color:#c5daeb}
#mt-services .service-card:before{content:"";position:absolute;right:-44px;top:-54px;width:150px;height:150px;border-radius:50%;background:#edf7ff}
#mt-services .service-card.umrah{background:linear-gradient(145deg,#fff 0%,#fffaf0 100%);border-color:#ead99c}
#mt-services .service-card.umrah:before{background:#ffeab0}
#mt-services .icon{position:relative;width:54px;height:54px;border-radius:15px;background:#eaf5ff;color:var(--blue);display:grid;place-items:center;font-size:25px;margin-bottom:24px}
#mt-services .umrah .icon{background:#fff1c8;color:#9a6a00}
#mt-services .service-card h3{position:relative;color:var(--ink);font:850 21px Manrope,Inter,sans-serif;margin:0 0 10px}
#mt-services .service-card p{position:relative;color:var(--muted);font-size:14.5px;line-height:1.65;margin:0 0 46px}
#mt-services .card-link{position:absolute;left:28px;bottom:25px;color:#0876c9;text-decoration:none;font-weight:850;font-size:14px}

/* Trust */
#mt-services .trust-wrap{padding:0 0 70px;background:#fff}
#mt-services .trust{display:grid;grid-template-columns:repeat(4,1fr);background:linear-gradient(110deg,#073662,#0c5c94);border-radius:20px;padding:27px 20px;box-shadow:0 16px 38px rgba(5,43,77,.14)}
#mt-services .trust-item{text-align:center;color:#fff;padding:6px 18px;border-right:1px solid rgba(255,255,255,.18)}
#mt-services .trust-item:last-child{border-right:0}
#mt-services .trust-item strong{display:block;font:900 25px Manrope;color:#fff;margin-bottom:4px}
#mt-services .trust-item span{font-size:12px;color:#cfe5f7}

/* CTA */
#mt-services .cta{background:#062f57;padding:44px 0}
#mt-services .cta-inner{display:flex;align-items:center;justify-content:space-between;gap:30px}
#mt-services .cta h2{color:#fff!important;margin:0 0 7px!important;font:850 28px Manrope}
#mt-services .cta p{color:#cfe0ef!important;margin:0!important}
#mt-services .cta .sv-btn{background:var(--gold);color:#08233f;white-space:nowrap}

@media(max-width:900px){
  #mt-services .cards{grid-template-columns:repeat(2,1fr)}
  #mt-services .trust{grid-template-columns:repeat(2,1fr);row-gap:20px}
  #mt-services .trust-item:nth-child(2){border-right:0}
}
@media(max-width:620px){
  #mt-services .sv-container{width:min(100% - 28px,1180px)}
  #mt-services .sv-hero{min-height:430px!important}
  #mt-services .sv-hero-inner{padding:56px 0!important}
  #mt-services .sv-hero h1{font-size:42px!important}
  #mt-services .cards{grid-template-columns:1fr}
  #mt-services .service-card{min-height:265px}
  #mt-services .trust{grid-template-columns:1fr}
  #mt-services .trust-item{border-right:0;border-bottom:1px solid rgba(255,255,255,.16);padding:12px}
  #mt-services .trust-item:last-child{border-bottom:0}
  #mt-services .cta-inner{display:block}
  #mt-services .cta .sv-btn{margin-top:22px}
}
</style>

<main id="mt-services">
  <section class="sv-hero">
    <div class="sv-container sv-hero-inner">
      <span class="sv-kicker">MUSTAFA TRAVELS • BARCELONA</span>
      <h1>Everything you need for a smoother journey.</h1>
      <p>Flights, hotels, Umrah &amp; Hajj arrangements, visa assistance and transport — with personal support before, during and after your trip.</p>
      <div class="hero-actions">
        <a class="sv-btn gold" href="contact.php">Request a Quote →</a>
        <a class="sv-btn ghost" href="offers.php">View Latest Offers</a>
      </div>
    </div>
  </section>

  <section class="sv-main">
    <div class="sv-container">
      <div class="section-head">
        <span class="sv-kicker">WHAT WE DO</span>
        <h2>Complete travel support, in one place.</h2>
        <p>From your first search to your return journey, our services are designed to make travel planning clearer and easier.</p>
      </div>

      <div class="cards">
        <article class="service-card">
          <div class="icon">✈</div>
          <h3>Airline Tickets</h3>
          <p>International and multi-city flight reservations, family bookings, baggage guidance, schedule support and emergency ticket assistance.</p>
          <a class="card-link" href="contact.php">Get a flight quote →</a>
        </article>

        <article class="service-card">
          <div class="icon">▦</div>
          <h3>Hotel Booking</h3>
          <p>Worldwide hotel reservations, including Makkah and Madinah stays, from practical economy options to premium properties.</p>
          <a class="card-link" href="hotels.php">Explore hotels →</a>
        </article>

        <article class="service-card umrah">
          <div class="icon">☾</div>
          <h3>Umrah &amp; Hajj</h3>
          <p>Customized religious travel arrangements including flights, visa guidance, hotels, transfers and selected journey services.</p>
          <a class="card-link" href="umrah.php">Explore Umrah &amp; Hajj →</a>
        </article>

        <article class="service-card" id="visa">
          <div class="icon">✓</div>
          <h3>Visa Assistance</h3>
          <p>Travel visa application support, documentation guidance and appointment preparation for eligible destinations and travellers.</p>
          <a class="card-link" href="contact.php">Ask about a visa →</a>
        </article>

        <article class="service-card">
          <div class="icon">🚐</div>
          <h3>Transport</h3>
          <p>Airport transfers and private or shared transport for selected destinations, including transport options for Umrah journeys.</p>
          <a class="card-link" href="contact.php">Request transport →</a>
        </article>

        <article class="service-card">
          <div class="icon">☎</div>
          <h3>24/7 Emergency Support</h3>
          <p>Urgent airline ticketing, itinerary support and travel assistance when you need help outside normal office hours.</p>
          <a class="card-link" href="contact.php">Contact us →</a>
        </article>
      </div>
    </div>
  </section>

  <section class="trust-wrap">
    <div class="sv-container">
      <div class="trust">
        <div class="trust-item"><strong>8+</strong><span>Years Travel Experience</span></div>
        <div class="trust-item"><strong>10,000+</strong><span>Clients Served</span></div>
        <div class="trust-item"><strong>50+</strong><span>Destinations</span></div>
        <div class="trust-item"><strong>24/7</strong><span>Travel Support</span></div>
      </div>
    </div>
  </section>

  <section class="cta">
    <div class="sv-container cta-inner">
      <div>
        <h2>Ready to plan your next journey?</h2>
        <p>Tell us where you want to travel and we’ll help you with the right options.</p>
      </div>
      <a class="sv-btn" href="contact.php">Get Personal Assistance →</a>
    </div>
  </section>
</main>

<?php site_footer(); ?>
