<?php require_once __DIR__.'/partials.php'; site_header('Services'); ?>

<style>
/* ============================
   MUSTAFA TRAVELS SERVICES V3
   ISOLATED / CONFLICT-SAFE
   ============================ */

/* Stop the existing sticky/fixed header from covering this page */
body .site-header{
  position:relative!important;
  top:auto!important;
  left:auto!important;
  right:auto!important;
  z-index:50!important;
}

#mtx-services{
  --mtx-navy:#052c55;
  --mtx-navy2:#0b4f83;
  --mtx-blue:#1188e8;
  --mtx-gold:#f4bd3c;
  --mtx-ink:#10253d;
  --mtx-muted:#667b90;
  --mtx-line:#d6e3ee;
  --mtx-soft:#f3f8fc;
  --mtx-white:#fff;
  font-family:Inter,Arial,sans-serif!important;
  background:#fff!important;
  color:var(--mtx-ink)!important;
  display:block!important;
  width:100%!important;
  overflow:hidden!important;
}

#mtx-services *{box-sizing:border-box!important}
#mtx-services a{text-decoration:none!important}
#mtx-services .mtx-container{
  width:min(1180px,calc(100% - 40px))!important;
  margin:0 auto!important;
}

/* HERO */
#mtx-services .mtx-hero{
  position:relative!important;
  display:flex!important;
  align-items:center!important;
  min-height:410px!important;
  width:100%!important;
  color:#fff!important;
  background:
    radial-gradient(circle at 82% 30%,rgba(244,189,60,.24),transparent 22%),
    linear-gradient(118deg,#031f3c 0%,#083a68 50%,#1180be 100%)!important;
  isolation:isolate!important;
}
#mtx-services .mtx-hero:after{
  content:""!important;
  position:absolute!important;
  inset:0!important;
  z-index:-1!important;
  opacity:.14!important;
  background-image:
    linear-gradient(30deg,rgba(255,255,255,.28) 12%,transparent 12.5%,transparent 87%,rgba(255,255,255,.28) 87.5%),
    linear-gradient(150deg,rgba(255,255,255,.18) 12%,transparent 12.5%,transparent 87%,rgba(255,255,255,.18) 87.5%)!important;
  background-size:80px 135px!important;
}
#mtx-services .mtx-hero-copy{
  width:100%!important;
  max-width:780px!important;
  padding:72px 0!important;
  display:block!important;
}
#mtx-services .mtx-kicker{
  display:block!important;
  width:auto!important;
  color:var(--mtx-gold)!important;
  font-size:12px!important;
  font-weight:900!important;
  letter-spacing:2.2px!important;
  line-height:1.2!important;
  margin:0 0 14px!important;
}
#mtx-services .mtx-hero h1{
  display:block!important;
  max-width:760px!important;
  color:#fff!important;
  font-family:Manrope,Inter,Arial,sans-serif!important;
  font-size:clamp(42px,5vw,64px)!important;
  font-weight:900!important;
  line-height:1.02!important;
  letter-spacing:-1.6px!important;
  margin:0 0 18px!important;
  padding:0!important;
}
#mtx-services .mtx-hero p{
  display:block!important;
  max-width:680px!important;
  color:#dceaf6!important;
  font-size:18px!important;
  line-height:1.7!important;
  margin:0 0 28px!important;
  padding:0!important;
}
#mtx-services .mtx-actions{
  display:flex!important;
  gap:12px!important;
  flex-wrap:wrap!important;
}
#mtx-services .mtx-btn{
  display:inline-flex!important;
  align-items:center!important;
  justify-content:center!important;
  min-height:46px!important;
  padding:12px 18px!important;
  border-radius:11px!important;
  font-size:14px!important;
  font-weight:900!important;
  line-height:1!important;
}
#mtx-services .mtx-btn.primary{
  background:var(--mtx-gold)!important;
  color:#2c2400!important;
  border:1px solid var(--mtx-gold)!important;
}
#mtx-services .mtx-btn.secondary{
  background:rgba(255,255,255,.09)!important;
  color:#fff!important;
  border:1px solid rgba(255,255,255,.35)!important;
}

/* MAIN */
#mtx-services .mtx-main{
  display:block!important;
  width:100%!important;
  padding:78px 0 68px!important;
  background:linear-gradient(180deg,#f6faff 0%,#ffffff 70%)!important;
}
#mtx-services .mtx-heading{
  display:block!important;
  width:100%!important;
  max-width:760px!important;
  margin:0 auto 40px!important;
  padding:0!important;
  text-align:center!important;
}
#mtx-services .mtx-heading .mtx-kicker{
  color:var(--mtx-blue)!important;
  margin-bottom:10px!important;
}
#mtx-services .mtx-heading h2{
  display:block!important;
  color:var(--mtx-ink)!important;
  font-family:Manrope,Inter,Arial,sans-serif!important;
  font-size:clamp(32px,4vw,44px)!important;
  font-weight:900!important;
  line-height:1.08!important;
  letter-spacing:-.8px!important;
  margin:0 0 12px!important;
  padding:0!important;
  text-align:center!important;
}
#mtx-services .mtx-heading p{
  display:block!important;
  color:var(--mtx-muted)!important;
  font-size:16px!important;
  line-height:1.65!important;
  margin:0 auto!important;
  padding:0!important;
  max-width:680px!important;
  text-align:center!important;
}

/* CARDS */
#mtx-services .mtx-grid{
  display:grid!important;
  grid-template-columns:repeat(3,minmax(0,1fr))!important;
  gap:22px!important;
  width:100%!important;
}
#mtx-services .mtx-card{
  position:relative!important;
  display:block!important;
  min-height:310px!important;
  padding:28px 28px 66px!important;
  background:linear-gradient(180deg,#ffffff 0%,#f8fbfe 100%)!important;
  border:1px solid #cfddea!important;
  border-radius:22px!important;
  box-shadow:0 12px 30px rgba(5,44,85,.10)!important;
  overflow:hidden!important;
}
#mtx-services .mtx-card:before{
  content:""!important;
  position:absolute!important;
  right:-42px!important;
  top:-48px!important;
  width:145px!important;
  height:145px!important;
  border-radius:50%!important;
  background:#e8f5ff!important;
}
#mtx-services .mtx-card.umrah{
  background:linear-gradient(180deg,#fffdfa 0%,#fff6df 100%)!important;
  border-color:#e8d28b!important;
}
#mtx-services .mtx-card.umrah:before{background:#ffe9a4!important}

#mtx-services .mtx-icon{
  position:relative!important;
  display:grid!important;
  place-items:center!important;
  width:56px!important;
  height:56px!important;
  border-radius:16px!important;
  background:#e7f4ff!important;
  color:var(--mtx-blue)!important;
  font-size:26px!important;
  margin:0 0 24px!important;
}
#mtx-services .mtx-card.umrah .mtx-icon{
  background:#ffefbc!important;
  color:#8a6200!important;
}
#mtx-services .mtx-card h3{
  position:relative!important;
  display:block!important;
  color:var(--mtx-ink)!important;
  font-family:Manrope,Inter,Arial,sans-serif!important;
  font-size:21px!important;
  font-weight:900!important;
  line-height:1.2!important;
  margin:0 0 10px!important;
  padding:0!important;
}
#mtx-services .mtx-card p{
  position:relative!important;
  display:block!important;
  color:#5f7489!important;
  font-size:14.5px!important;
  line-height:1.68!important;
  margin:0!important;
  padding:0!important;
}
#mtx-services .mtx-link{
  position:absolute!important;
  left:28px!important;
  bottom:26px!important;
  color:#087aca!important;
  font-size:14px!important;
  font-weight:900!important;
}

/* TRUST */
#mtx-services .mtx-trust-section{
  display:block!important;
  padding:0 0 72px!important;
  background:#fff!important;
}
#mtx-services .mtx-trust{
  display:grid!important;
  grid-template-columns:repeat(4,1fr)!important;
  gap:0!important;
  width:100%!important;
  padding:26px 20px!important;
  background:linear-gradient(110deg,#073662,#0d5b94)!important;
  border-radius:20px!important;
  box-shadow:0 15px 36px rgba(5,43,77,.15)!important;
}
#mtx-services .mtx-trust div{
  text-align:center!important;
  padding:7px 18px!important;
  border-right:1px solid rgba(255,255,255,.2)!important;
}
#mtx-services .mtx-trust div:last-child{border-right:0!important}
#mtx-services .mtx-trust strong{
  display:block!important;
  color:#fff!important;
  font-family:Manrope,Inter,Arial,sans-serif!important;
  font-size:26px!important;
  font-weight:900!important;
  margin:0 0 4px!important;
}
#mtx-services .mtx-trust span{
  color:#d5e7f5!important;
  font-size:12px!important;
}

/* CTA */
#mtx-services .mtx-cta{
  display:block!important;
  width:100%!important;
  padding:46px 0!important;
  background:#052c55!important;
}
#mtx-services .mtx-cta-inner{
  display:flex!important;
  align-items:center!important;
  justify-content:space-between!important;
  gap:28px!important;
}
#mtx-services .mtx-cta h2{
  display:block!important;
  color:#fff!important;
  font-family:Manrope,Inter,Arial,sans-serif!important;
  font-size:28px!important;
  font-weight:900!important;
  margin:0 0 7px!important;
}
#mtx-services .mtx-cta p{
  color:#d4e4f2!important;
  font-size:15px!important;
  margin:0!important;
}
#mtx-services .mtx-cta .mtx-btn{
  background:var(--mtx-gold)!important;
  color:#2c2400!important;
  border:1px solid var(--mtx-gold)!important;
  white-space:nowrap!important;
}

/* MOBILE */
@media(max-width:900px){
  #mtx-services .mtx-grid{grid-template-columns:repeat(2,minmax(0,1fr))!important}
  #mtx-services .mtx-trust{grid-template-columns:repeat(2,1fr)!important;row-gap:20px!important}
  #mtx-services .mtx-trust div:nth-child(2){border-right:0!important}
}
@media(max-width:620px){
  #mtx-services .mtx-container{width:min(100% - 28px,1180px)!important}
  #mtx-services .mtx-hero{min-height:470px!important}
  #mtx-services .mtx-hero-copy{padding:58px 0!important}
  #mtx-services .mtx-hero h1{font-size:42px!important}
  #mtx-services .mtx-hero p{font-size:16px!important}
  #mtx-services .mtx-grid{grid-template-columns:1fr!important}
  #mtx-services .mtx-card{min-height:285px!important}
  #mtx-services .mtx-trust{grid-template-columns:1fr!important}
  #mtx-services .mtx-trust div{
    border-right:0!important;
    border-bottom:1px solid rgba(255,255,255,.16)!important;
    padding:13px!important;
  }
  #mtx-services .mtx-trust div:last-child{border-bottom:0!important}
  #mtx-services .mtx-cta-inner{display:block!important}
  #mtx-services .mtx-cta .mtx-btn{margin-top:22px!important}
}
</style>

<main id="mtx-services">

  <section class="mtx-hero">
    <div class="mtx-container">
      <div class="mtx-hero-copy">
        <span class="mtx-kicker">MUSTAFA TRAVELS • BARCELONA</span>
        <h1>Everything you need for a smoother journey.</h1>
        <p>Flights, hotels, Umrah &amp; Hajj arrangements, visa assistance and transport — with personal support before, during and after your trip.</p>
        <div class="mtx-actions">
          <a class="mtx-btn primary" href="contact.php">Request a Quote →</a>
          <a class="mtx-btn secondary" href="offers.php">View Latest Offers</a>
        </div>
      </div>
    </div>
  </section>

  <section class="mtx-main">
    <div class="mtx-container">
      <div class="mtx-heading">
        <span class="mtx-kicker">WHAT WE DO</span>
        <h2>Complete travel support, in one place.</h2>
        <p>From your first search to your return journey, our services are designed to make travel planning clearer and easier.</p>
      </div>

      <div class="mtx-grid">
        <article class="mtx-card">
          <div class="mtx-icon">✈</div>
          <h3>Airline Tickets</h3>
          <p>International and multi-city flight reservations, family bookings, baggage guidance, schedule support and emergency ticket assistance.</p>
          <a class="mtx-link" href="contact.php">Get a flight quote →</a>
        </article>

        <article class="mtx-card">
          <div class="mtx-icon">▦</div>
          <h3>Hotel Booking</h3>
          <p>Worldwide hotel reservations, including Makkah and Madinah stays, from practical economy options to premium properties.</p>
          <a class="mtx-link" href="hotels.php">Explore hotels →</a>
        </article>

        <article class="mtx-card umrah">
          <div class="mtx-icon">☾</div>
          <h3>Umrah &amp; Hajj</h3>
          <p>Customized religious travel arrangements including flights, visa guidance, hotels, transfers and selected journey services.</p>
          <a class="mtx-link" href="umrah.php">Explore Umrah &amp; Hajj →</a>
        </article>

        <article class="mtx-card" id="visa">
          <div class="mtx-icon">✓</div>
          <h3>Visa Assistance</h3>
          <p>Travel visa application support, documentation guidance and appointment preparation for eligible destinations and travellers.</p>
          <a class="mtx-link" href="contact.php">Ask about a visa →</a>
        </article>

        <article class="mtx-card">
          <div class="mtx-icon">🚐</div>
          <h3>Transport</h3>
          <p>Airport transfers and private or shared transport for selected destinations, including transport options for Umrah journeys.</p>
          <a class="mtx-link" href="contact.php">Request transport →</a>
        </article>

        <article class="mtx-card">
          <div class="mtx-icon">☎</div>
          <h3>24/7 Emergency Support</h3>
          <p>Urgent airline ticketing, itinerary support and travel assistance when you need help outside normal office hours.</p>
          <a class="mtx-link" href="contact.php">Contact us →</a>
        </article>
      </div>
    </div>
  </section>

  <section class="mtx-trust-section">
    <div class="mtx-container">
      <div class="mtx-trust">
        <div><strong>8+</strong><span>Years Travel Experience</span></div>
        <div><strong>10,000+</strong><span>Clients Served</span></div>
        <div><strong>50+</strong><span>Destinations</span></div>
        <div><strong>24/7</strong><span>Travel Support</span></div>
      </div>
    </div>
  </section>

  <section class="mtx-cta">
    <div class="mtx-container mtx-cta-inner">
      <div>
        <h2>Ready to plan your next journey?</h2>
        <p>Tell us where you want to travel and we’ll help you with the right options.</p>
      </div>
      <a class="mtx-btn" href="contact.php">Get Personal Assistance →</a>
    </div>
  </section>

</main>

<?php site_footer(); ?>
