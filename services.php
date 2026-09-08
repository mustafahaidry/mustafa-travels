<?php require_once __DIR__.'/partials.php'; site_header('Services'); ?>

<style>
.services-v2{--navy:#06284a;--blue:#0b79d0;--gold:#f7b928;--ink:#08233f;--muted:#61768c;--line:#dce7f1;--soft:#f4f9fd}
.services-v2 *{box-sizing:border-box}
.services-v2 .sv-container{width:min(1180px,calc(100% - 40px));margin:auto}

/* HERO */
.services-v2 .sv-hero{
  position:relative;min-height:390px;display:flex;align-items:center;overflow:hidden;
  background:
    linear-gradient(90deg,rgba(3,29,55,.96) 0%,rgba(4,44,78,.88) 45%,rgba(4,55,94,.58) 100%),
    url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=2000&q=86') center/cover no-repeat;
}
.services-v2 .sv-hero:after{
  content:"";position:absolute;inset:0;
  background:radial-gradient(circle at 80% 28%,rgba(247,185,40,.18),transparent 30%);
}
.services-v2 .sv-hero-inner{position:relative;z-index:2;max-width:760px;padding:72px 0}
.services-v2 .eyebrow{display:inline-block;color:#ffc43d;font-size:12px;font-weight:900;letter-spacing:2.2px;margin-bottom:15px}
.services-v2 h1{color:#fff;font-size:clamp(38px,5vw,62px);line-height:1.02;margin:0 0 18px;font-weight:900;letter-spacing:-1.5px}
.services-v2 .hero-copy{color:#d8e7f4;font-size:18px;line-height:1.7;max-width:650px;margin:0 0 28px}
.services-v2 .hero-actions{display:flex;gap:12px;flex-wrap:wrap}
.services-v2 .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:13px 19px;border-radius:10px;text-decoration:none;font-weight:800;font-size:14px}
.services-v2 .btn-gold{background:var(--gold);color:#08233f}
.services-v2 .btn-ghost{border:1px solid rgba(255,255,255,.38);color:#fff;background:rgba(255,255,255,.08);backdrop-filter:blur(5px)}

/* SERVICES */
.services-v2 .sv-main{background:linear-gradient(180deg,#f7fbff 0,#fff 65%);padding:76px 0 68px}
.services-v2 .section-head{text-align:center;max-width:760px;margin:0 auto 38px}
.services-v2 .section-head .eyebrow{color:var(--blue);margin-bottom:10px}
.services-v2 .section-head h2{font-size:clamp(30px,4vw,44px);line-height:1.1;color:var(--ink);margin:0 0 12px;font-weight:900}
.services-v2 .section-head p{color:var(--muted);font-size:16px;line-height:1.65;margin:0}

.services-v2 .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:20px}
.services-v2 .service-card{
  position:relative;min-height:295px;padding:28px;border:1px solid var(--line);border-radius:20px;
  background:#fff;box-shadow:0 12px 34px rgba(10,48,83,.07);overflow:hidden;transition:.25s ease
}
.services-v2 .service-card:hover{transform:translateY(-5px);box-shadow:0 18px 42px rgba(10,48,83,.13);border-color:#c5daeb}
.services-v2 .service-card:before{
  content:"";position:absolute;right:-45px;top:-55px;width:150px;height:150px;border-radius:50%;background:#edf7ff
}
.services-v2 .service-card.umrah{background:linear-gradient(145deg,#fff 0%,#fffaf0 100%);border-color:#eedda5}
.services-v2 .service-card.umrah:before{background:#fff0be}
.services-v2 .icon{
  position:relative;width:54px;height:54px;border-radius:15px;background:#eaf5ff;color:var(--blue);
  display:grid;place-items:center;font-size:25px;margin-bottom:25px
}
.services-v2 .umrah .icon{background:#fff1c8;color:#9a6a00}
.services-v2 .service-card h3{position:relative;color:var(--ink);font-size:21px;margin:0 0 10px;font-weight:850}
.services-v2 .service-card p{position:relative;color:var(--muted);font-size:14.5px;line-height:1.65;margin:0 0 44px}
.services-v2 .card-link{position:absolute;left:28px;bottom:25px;color:#0876c9;text-decoration:none;font-weight:850;font-size:14px}

/* TRUST STRIP */
.services-v2 .trust-wrap{padding:0 0 72px;background:#fff}
.services-v2 .trust{
  display:grid;grid-template-columns:repeat(4,1fr);
  background:linear-gradient(110deg,#073662,#0c5c94);border-radius:20px;padding:27px 20px;
  box-shadow:0 16px 38px rgba(5,43,77,.14)
}
.services-v2 .trust-item{text-align:center;color:#fff;padding:6px 18px;border-right:1px solid rgba(255,255,255,.18)}
.services-v2 .trust-item:last-child{border-right:0}
.services-v2 .trust-item strong{display:block;font-size:24px;margin-bottom:4px}
.services-v2 .trust-item span{font-size:12px;color:#cfe5f7}

/* CTA */
.services-v2 .cta{background:#062f57;padding:45px 0}
.services-v2 .cta-inner{display:flex;align-items:center;justify-content:space-between;gap:30px}
.services-v2 .cta h2{color:#fff;margin:0 0 7px;font-size:28px}
.services-v2 .cta p{color:#cfe0ef;margin:0}
.services-v2 .cta .btn{background:var(--gold);color:#08233f;white-space:nowrap}

@media(max-width:900px){
  .services-v2 .cards{grid-template-columns:repeat(2,1fr)}
  .services-v2 .trust{grid-template-columns:repeat(2,1fr);row-gap:20px}
  .services-v2 .trust-item:nth-child(2){border-right:0}
}
@media(max-width:620px){
  .services-v2 .sv-container{width:min(100% - 28px,1180px)}
  .services-v2 .sv-hero{min-height:440px}
  .services-v2 .sv-hero-inner{padding:58px 0}
  .services-v2 .cards{grid-template-columns:1fr}
  .services-v2 .service-card{min-height:265px}
  .services-v2 .trust{grid-template-columns:1fr}
  .services-v2 .trust-item{border-right:0;border-bottom:1px solid rgba(255,255,255,.16);padding:12px}
  .services-v2 .trust-item:last-child{border-bottom:0}
  .services-v2 .cta-inner{display:block}
  .services-v2 .cta .btn{margin-top:22px}
}
</style>

<main class="services-v2">
  <section class="sv-hero">
    <div class="sv-container sv-hero-inner">
      <span class="eyebrow">MUSTAFA TRAVELS • BARCELONA</span>
      <h1>Travel services built around your journey.</h1>
      <p class="hero-copy">Flights, hotels, Umrah &amp; Hajj arrangements, visa assistance and transport — with personal support before, during and after your trip.</p>
      <div class="hero-actions">
        <a class="btn btn-gold" href="contact.php">Request a Quote →</a>
        <a class="btn btn-ghost" href="offers.php">View Latest Offers</a>
      </div>
    </div>
  </section>

  <section class="sv-main">
    <div class="sv-container">
      <div class="section-head">
        <span class="eyebrow">WHAT WE DO</span>
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
      <a class="btn" href="contact.php">Get Personal Assistance →</a>
    </div>
  </section>
</main>

<?php site_footer(); ?>
