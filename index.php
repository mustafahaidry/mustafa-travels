<?php
require_once __DIR__.'/partials.php';
$featured=sb_select('offers','select=*&active=eq.true&featured=eq.true&order=id.desc&limit=6');
site_header('Home');
?>
<style>
/* MUSTAFA HOME V4 - SELF CONTAINED STYLES */
:root{--v4-navy:#052c55;--v4-navy2:#0b477d;--v4-gold:#f4bd3c;--v4-blue:#1686e8;--v4-ink:#10253d;--v4-muted:#667b90;--v4-soft:#f4f8fc;--v4-line:#dfe8f1;--v4-white:#fff;--v4-shadow:0 18px 55px rgba(7,47,95,.13)}
body{background:#fff}.v4-wrap{overflow:hidden}.v4-container{width:min(1180px,calc(100% - 40px));margin:auto}
.v4-hero{position:relative;min-height:610px;color:#fff;background:linear-gradient(90deg,rgba(2,24,52,.94) 0%,rgba(2,24,52,.72) 38%,rgba(2,24,52,.18) 72%),url('https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?auto=format&fit=crop&w=2000&q=88') center/cover no-repeat}
.v4-hero-inner{position:relative;z-index:2;padding:74px 0 150px}.v4-kicker{font-size:12px;font-weight:800;letter-spacing:2.4px;color:var(--v4-gold);margin-bottom:15px}.v4-hero h1{font:800 clamp(46px,5.3vw,76px)/.98 Manrope,Inter,sans-serif;margin:0 0 20px;max-width:690px;letter-spacing:-2px}.v4-hero h1 span{color:var(--v4-gold)}.v4-hero p{max-width:620px;font-size:18px;line-height:1.7;color:#e3edf7;margin:0}
.v4-stats{display:flex;gap:12px;margin-top:30px;flex-wrap:wrap}.v4-stat{display:flex;align-items:center;gap:12px;background:rgba(5,44,85,.78);border:1px solid rgba(255,255,255,.12);padding:12px 16px;border-radius:14px;backdrop-filter:blur(7px)}.v4-stat i{font-style:normal;font-size:24px}.v4-stat strong{display:block;font:800 19px Manrope}.v4-stat small{display:block;color:#d1deea;font-size:11px;margin-top:2px}
.v4-badge{position:absolute;right:7%;top:62px;background:rgba(5,44,85,.83);border:1px solid rgba(255,255,255,.16);border-radius:18px;padding:16px 20px;font-weight:700;line-height:1.35;box-shadow:var(--v4-shadow)}
.v4-search-wrap{position:relative;z-index:5;margin-top:-92px}.v4-search{background:#fff;border-radius:22px;box-shadow:0 24px 65px rgba(3,31,62,.16);border:1px solid #e7edf4;padding:0 22px 20px}.v4-tabs{display:flex;gap:8px;border-bottom:1px solid var(--v4-line);overflow:auto}.v4-tabs a{white-space:nowrap;padding:16px 18px 13px;font-weight:800;font-size:13px;color:#36516c}.v4-tabs a.active{color:var(--v4-blue);border-bottom:3px solid var(--v4-blue)}.v4-live{padding-top:14px}.v4-assurances{display:flex;justify-content:center;gap:28px;flex-wrap:wrap;font-size:11px;color:#49637e;padding-top:10px}.v4-assurances span:before{content:'●';color:var(--v4-blue);margin-right:7px}
.v4-banner-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px}.v4-banner{min-height:150px;border-radius:18px;overflow:hidden;position:relative;background-size:cover;background-position:center;display:block}.v4-banner.umrah{background-image:linear-gradient(90deg,rgba(3,31,62,.94),rgba(3,31,62,.28)),url('https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?auto=format&fit=crop&w=1200&q=84')}.v4-banner.hajj{background-image:linear-gradient(90deg,rgba(3,31,62,.32),rgba(3,31,62,.95)),url('https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=1200&q=84')}.v4-banner-copy{position:absolute;inset:0;padding:26px 28px;color:#fff;display:flex;flex-direction:column;justify-content:center}.v4-banner.hajj .v4-banner-copy{align-items:flex-end;text-align:left}.v4-banner h3{font:800 27px/1.05 Manrope;margin:0 0 7px}.v4-banner h3 span{color:var(--v4-gold)}.v4-banner p{margin:0 0 13px;color:#e3edf7}.v4-pill{display:inline-flex;width:max-content;padding:10px 15px;border-radius:10px;background:var(--v4-gold);color:#3d2b00;font-weight:900;font-size:12px}
.v4-section{padding:72px 0}.v4-section.soft{background:linear-gradient(180deg,#f7fafc,#eef5fa)}.v4-head{text-align:center;margin-bottom:30px}.v4-head .v4-kicker{color:var(--v4-blue);margin-bottom:8px}.v4-head h2{font:800 34px Manrope;margin:0 0 8px;color:var(--v4-ink)}.v4-head p{margin:0;color:var(--v4-muted)}
.v4-services{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}.v4-service{background:#fff;border:1px solid var(--v4-line);border-radius:18px;padding:24px;box-shadow:0 8px 24px rgba(7,47,95,.05);transition:.2s}.v4-service:hover{transform:translateY(-4px);box-shadow:var(--v4-shadow)}.v4-icon{width:48px;height:48px;border-radius:14px;background:#eaf5ff;display:grid;place-items:center;color:var(--v4-blue);font-size:23px}.v4-service h3{font:800 18px Manrope;margin:15px 0 6px}.v4-service p{margin:0 0 10px;color:var(--v4-muted);line-height:1.6;font-size:13px}.v4-service span{color:var(--v4-blue);font-size:12px;font-weight:800}
.v4-about{display:grid;grid-template-columns:320px 1fr 250px;gap:28px;align-items:stretch}.v4-owner{border-radius:22px;overflow:hidden;min-height:360px;background:#ddd}.v4-owner img{width:100%;height:100%;object-fit:cover;display:block}.v4-about-copy{padding:10px 0}.v4-about-copy h2{font:800 34px Manrope;margin:0 0 12px}.v4-about-copy p{color:var(--v4-muted);line-height:1.8}.v4-about-copy .btn{margin-top:8px;background:var(--v4-gold);color:#3a2a00}.v4-trustbox{background:#fff;border:1px solid var(--v4-line);border-radius:18px;padding:24px;display:grid;gap:16px;box-shadow:0 10px 28px rgba(7,47,95,.06)}.v4-trustbox div{display:flex;gap:11px;align-items:flex-start;font-size:13px;font-weight:700;color:#203b56}.v4-trustbox b{color:var(--v4-gold);font-size:18px}
.v4-offers-shell{position:relative}.v4-offers-shell:before{content:'';position:absolute;left:-80px;top:-40px;width:240px;height:240px;border-radius:50%;background:radial-gradient(circle,rgba(244,189,60,.15),transparent 68%);pointer-events:none}.v4-offers-top{display:flex;justify-content:space-between;align-items:flex-end;gap:24px;margin-bottom:28px}.v4-offers-top .v4-head{text-align:left;margin:0}.v4-offers-top .v4-head h2{font-size:38px}.v4-offers-top .v4-head p{max-width:560px}.v4-view-all{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border:1px solid #cfddea;border-radius:10px;color:var(--v4-navy);font-size:12px;font-weight:900;background:#fff}.v4-deals{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:22px}.v4-deal{position:relative;border:1px solid #dbe5ee;border-radius:22px;overflow:hidden;background:#fff;box-shadow:0 14px 38px rgba(7,47,95,.09);transition:transform .22s ease,box-shadow .22s ease}.v4-deal:hover{transform:translateY(-6px);box-shadow:0 22px 52px rgba(7,47,95,.15)}.v4-deal-media{height:220px;background:linear-gradient(135deg,#0a568f,#17a1dc);background-size:cover;background-position:center;position:relative}.v4-deal-media:after{content:'';position:absolute;inset:0;background:linear-gradient(180deg,rgba(2,24,52,.06) 35%,rgba(2,24,52,.78) 100%)}.v4-fallback-isb{background-image:url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1100&q=84')}.v4-fallback-lhe{background-image:url('https://images.unsplash.com/photo-1529074963764-98f45c47344b?auto=format&fit=crop&w=1100&q=84')}.v4-fallback-world{background-image:url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1100&q=84')}.v4-deal-badge{position:absolute;left:14px;top:14px;z-index:2;background:var(--v4-gold);color:#352600;border-radius:999px;padding:8px 12px;font-size:10px;font-weight:900;box-shadow:0 6px 16px rgba(0,0,0,.12)}.v4-deal-route{position:absolute;left:16px;right:16px;bottom:16px;z-index:2;color:#fff}.v4-deal-route small{display:block;color:#d9e7f5;font-size:10px;font-weight:800;letter-spacing:1.2px;margin-bottom:5px}.v4-deal-route strong{display:block;font:800 20px/1.15 Manrope}.v4-deal-body{padding:19px 20px 20px}.v4-deal-airline{font-size:11px;font-weight:900;color:var(--v4-blue);letter-spacing:.5px;text-transform:uppercase}.v4-deal-body h3{font:800 20px/1.25 Manrope;margin:8px 0 11px;color:var(--v4-ink)}.v4-deal-chips{display:flex;flex-wrap:wrap;gap:7px;margin:0 0 17px}.v4-deal-chip{padding:6px 9px;border-radius:999px;background:#f1f6fa;border:1px solid #e0e8ef;color:#4e647a;font-size:10px;font-weight:800}.v4-deal-bottom{display:flex;align-items:flex-end;justify-content:space-between;gap:12px;padding-top:15px;border-top:1px solid #edf1f5}.v4-price-wrap small{display:block;font-size:9px;color:#7a8ca0;font-weight:800;letter-spacing:1px}.v4-price{font:800 29px/1 Manrope;color:var(--v4-navy);margin:4px 0 0}.v4-deal-cta{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 15px;border-radius:10px;background:var(--v4-navy);color:#fff!important;font-size:11px;font-weight:900;white-space:nowrap}.v4-deal-cta:hover{background:var(--v4-blue)}.v4-offer-note{margin-top:18px;text-align:center;color:#7890a6;font-size:10px}
.v4-strip{background:linear-gradient(120deg,#07315d,#0b4e84);color:#fff;border-radius:20px;padding:23px 26px;display:grid;grid-template-columns:repeat(4,1fr);gap:10px}.v4-strip div{text-align:center;border-right:1px solid rgba(255,255,255,.2)}.v4-strip div:last-child{border-right:0}.v4-strip strong{display:block;font:800 26px Manrope;color:#fff}.v4-strip span{font-size:11px;color:#d4e2ef}
.v4-cta{background:#052c55;color:#fff;padding:34px 0}.v4-cta-in{display:flex;justify-content:space-between;align-items:center;gap:20px}.v4-cta h3{font:800 24px Manrope;margin:0 0 5px}.v4-cta p{margin:0;color:#d9e7f5}.v4-cta .btn{background:var(--v4-gold);color:#3a2a00}
@media(max-width:1000px){.v4-badge{display:none}.v4-offers-top{align-items:flex-start}.v4-deals{grid-template-columns:1fr 1fr}.v4-services{grid-template-columns:1fr 1fr}.v4-about{grid-template-columns:280px 1fr}.v4-trustbox{grid-column:1/-1;grid-template-columns:1fr 1fr}.v4-deals{grid-template-columns:1fr 1fr}.v4-strip{grid-template-columns:1fr 1fr}.v4-strip div:nth-child(2){border-right:0}}
@media(max-width:700px){.v4-offers-top{flex-direction:column;align-items:flex-start;margin-bottom:22px}.v4-offers-top .v4-head h2{font-size:32px}.v4-view-all{width:100%;justify-content:center}.v4-deal-media{height:215px}.v4-deal-bottom{align-items:center}.v4-container{width:min(100% - 24px,1180px)}.v4-hero{min-height:620px;background-position:58% center}.v4-hero-inner{padding:58px 0 165px}.v4-hero h1{font-size:46px}.v4-hero p{font-size:16px}.v4-search-wrap{margin-top:-110px}.v4-search{padding:0 13px 15px;border-radius:18px}.v4-tabs a{padding:13px 12px;font-size:12px}.v4-banner-grid,.v4-services,.v4-about,.v4-deals,.v4-strip{grid-template-columns:1fr}.v4-banner{min-height:190px}.v4-about{gap:20px}.v4-owner{min-height:340px}.v4-trustbox{grid-template-columns:1fr}.v4-strip div{border-right:0;border-bottom:1px solid rgba(255,255,255,.16);padding-bottom:10px}.v4-strip div:last-child{border-bottom:0}.v4-cta-in{flex-direction:column;align-items:flex-start}.v4-section{padding:55px 0}}
</style>

<div class="v4-wrap">
<section class="v4-hero">
  <div class="v4-container v4-hero-inner">
    <div class="v4-kicker">SPIRITUAL JOURNEYS • GLOBAL DESTINATIONS</div>
    <h1>Umrah & Hajj<br>Made <span>Easier</span></h1>
    <p>Trusted travel services from Barcelona for your spiritual and worldwide journeys.</p>
    <div class="v4-stats">
      <div class="v4-stat"><i>👥</i><div><strong>10,000+</strong><small>Happy Clients</small></div></div>
      <div class="v4-stat"><i>🛡️</i><div><strong>8+</strong><small>Years Experience</small></div></div>
      <div class="v4-stat"><i>🎧</i><div><strong>24/7</strong><small>Travel Support</small></div></div>
    </div>
  </div>
  <div class="v4-badge">🕋 &nbsp; Your Spiritual Journey<br><strong>Our Responsibility</strong></div>
</section>

<section class="v4-search-wrap">
  <div class="v4-container">
    <div class="v4-search">
      <div class="v4-tabs">
        <a class="active" href="#">✈ Flights</a><a href="hotels.php">▦ Hotels</a><a href="umrah.php">☾ Umrah Packages</a><a href="umrah.php#hajj">♜ Hajj 2027</a><a href="services.php#visa">▣ Visa Services</a>
      </div>
      <div class="v4-live"><div id="tpwl-search"></div><div id="tpwl-tickets" class="tpwl-results"></div></div>
      <div class="v4-assurances"><span>Compare 100+ Airlines</span><span>Best Price Options</span><span>Secure Search</span></div>
    </div>
    <div class="v4-banner-grid">
      <a class="v4-banner umrah" href="umrah.php"><div class="v4-banner-copy"><h3>Umrah Packages<br><span>from Barcelona</span></h3><p>Flights, Hotels, Visa & Transport</p><span class="v4-pill">View Umrah Packages →</span></div></a>
      <a class="v4-banner hajj" href="umrah.php#hajj"><div class="v4-banner-copy"><h3>Hajj 2027</h3><p>Early Registration & Guidance</p><span class="v4-pill">More Information →</span></div></a>
    </div>
  </div>
</section>

<section class="v4-section soft">
  <div class="v4-container">
    <div class="v4-head"><div class="v4-kicker">WHAT WE DO</div><h2>Complete Travel Services</h2><p>One trusted team for your journey from planning to return.</p></div>
    <div class="v4-services">
      <a class="v4-service" href="services.php"><div class="v4-icon">✈</div><h3>Air Tickets</h3><p>Competitive international fares and expert itinerary support.</p><span>Explore →</span></a>
      <a class="v4-service" href="hotels.php"><div class="v4-icon">▦</div><h3>Hotel Booking</h3><p>Worldwide hotel reservations including Makkah and Madinah.</p><span>Explore →</span></a>
      <a class="v4-service" href="umrah.php"><div class="v4-icon">☾</div><h3>Umrah & Hajj</h3><p>Visa, flights, hotels, transport and customized religious packages.</p><span>Explore →</span></a>
      <a class="v4-service" href="services.php#visa"><div class="v4-icon">✓</div><h3>Visa Assistance</h3><p>Travel visa support and document guidance for multiple destinations.</p><span>Explore →</span></a>
    </div>
  </div>
</section>

<section class="v4-section">
  <div class="v4-container">
    <div class="v4-about">
      <div class="v4-owner"><img src="assets/images/owner-original.jpg" alt="Ghulam Mustafa - Mustafa Travels & Tours" loading="lazy"></div>
      <div class="v4-about-copy"><div class="v4-kicker" style="color:var(--v4-blue)">ABOUT MUSTAFA TRAVELS</div><h2>Built on experience, service and trust.</h2><p>My name is <strong>Ghulam Mustafa Haidry</strong>. I have been working in the travel field for more than 8 years. In January 2024, I established <strong>Mustafa Travels & Tours</strong> with the aim of providing professional, transparent and dependable travel services from Barcelona.</p><p>We support clients with flights, hotels, Umrah & Hajj arrangements, visa guidance and urgent travel assistance.</p><a class="btn" href="about.php">Read My Story →</a></div>
      <div class="v4-trustbox"><div><b>◇</b><span>Trusted & Reliable</span></div><div><b>♟</b><span>Personalized Service</span></div><div><b>◉</b><span>24/7 Customer Support</span></div><div><b>◆</b><span>Your Journey, Our Responsibility</span></div></div>
    </div>
  </div>
</section>

<section class="v4-section soft">
  <div class="v4-container v4-offers-shell">
    <div class="v4-offers-top">
      <div class="v4-head"><div class="v4-kicker">LATEST DEALS</div><h2>Special Offers from Barcelona</h2><p>Handpicked fares and travel deals. Ask us for live availability before booking.</p></div>
      <a class="v4-view-all" href="offers.php">View All Offers <span>→</span></a>
    </div>
    <div class="v4-deals">
    <?php if(!$featured): ?>
      <?php foreach([
        ['Barcelona → Islamabad','From €640','Special Fare','40kg + 7kg','Selected Dates','v4-fallback-isb'],
        ['Barcelona → Lahore','From €655','Special Fare','23kg + 7kg','Selected Dates','v4-fallback-lhe'],
        ['Barcelona → Worldwide','From €680','Limited Offer','Ask for baggage','Best Dates','v4-fallback-world']
      ] as $x): ?>
      <article class="v4-deal">
        <div class="v4-deal-media <?=h($x[5])?>">
          <span class="v4-deal-badge"><?=h($x[2])?></span>
          <div class="v4-deal-route"><small>DEPARTING FROM BARCELONA</small><strong><?=h($x[0])?></strong></div>
        </div>
        <div class="v4-deal-body">
          <div class="v4-deal-airline">Mustafa Travels Special</div>
          <h3><?=h($x[0])?></h3>
          <div class="v4-deal-chips"><span class="v4-deal-chip"><?=h($x[3])?></span><span class="v4-deal-chip"><?=h($x[4])?></span></div>
          <div class="v4-deal-bottom"><div class="v4-price-wrap"><small>STARTING FROM</small><div class="v4-price"><?=h($x[1])?></div></div><a class="v4-deal-cta" href="contact.php">Check Availability →</a></div>
        </div>
      </article>
      <?php endforeach; ?>
    <?php else: foreach($featured as $o): ?>
      <article class="v4-deal">
        <div class="v4-deal-media" <?php if(!empty($o['image_url'])): ?>style="background-image:url('<?=h($o['image_url'])?>')"<?php endif; ?>>
          <span class="v4-deal-badge"><?=h($o['badge'] ?: 'Special Offer')?></span>
          <div class="v4-deal-route"><small><?=h($o['airline'] ?: 'MUSTAFA TRAVELS')?></small><strong><?=h($o['title'])?></strong></div>
        </div>
        <div class="v4-deal-body">
          <div class="v4-deal-airline"><?=h($o['airline'] ?: 'Mustafa Travels Special')?></div>
          <h3><?=h($o['title'])?></h3>
          <div class="v4-deal-chips">
            <?php if(!empty($o['travel_dates'])): ?><span class="v4-deal-chip"><?=h($o['travel_dates'])?></span><?php endif; ?>
            <?php if(!empty($o['baggage'])): ?><span class="v4-deal-chip"><?=h($o['baggage'])?></span><?php endif; ?>
          </div>
          <div class="v4-deal-bottom"><div class="v4-price-wrap"><small>STARTING FROM</small><div class="v4-price"><?=h($o['currency'])?> <?=number_format((float)$o['price'],0)?></div></div><a class="v4-deal-cta" href="https://wa.me/<?=WHATSAPP?>?text=<?=urlencode('I am interested in: '.$o['title'])?>" target="_blank">Check Availability →</a></div>
        </div>
      </article>
    <?php endforeach; endif; ?>
    </div>
    <div class="v4-offer-note">Prices and availability are subject to change until final confirmation.</div>
  </div>
</section>

<section class="v4-section" style="padding-top:20px">
  <div class="v4-container"><div class="v4-strip"><div><strong>10,000+</strong><span>Happy Clients</span></div><div><strong>8+</strong><span>Years of Experience</span></div><div><strong>50+</strong><span>Destinations</span></div><div><strong>24/7</strong><span>Travel Support</span></div></div></div>
</section>

<section class="v4-cta"><div class="v4-container v4-cta-in"><div><h3>Ready to plan your next journey?</h3><p>Get in touch for travel deals and personal guidance.</p></div><a class="btn" href="https://wa.me/<?=WHATSAPP?>" target="_blank">Chat on WhatsApp →</a></div></section>
</div>
<?php site_footer(); ?>
