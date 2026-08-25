<?php
require_once __DIR__.'/partials.php';
$featured=sb_select('offers','select=*&active=eq.true&featured=eq.true&order=id.desc&limit=8');
site_header('Home');
?>
<section class="hero">
  <div class="hero-slide active" style="background-image:linear-gradient(90deg,rgba(2,24,52,.88),rgba(2,24,52,.28)),url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=2000&q=85')"></div>
  <div class="hero-slide" style="background-image:linear-gradient(90deg,rgba(2,24,52,.88),rgba(2,24,52,.28)),url('https://images.unsplash.com/photo-1518684079-3c830dcef090?auto=format&fit=crop&w=2000&q=85')"></div>
  <div class="hero-slide" style="background-image:linear-gradient(90deg,rgba(2,24,52,.88),rgba(2,24,52,.28)),url('https://images.unsplash.com/photo-1565552645632-d725f8bfc19a?auto=format&fit=crop&w=2000&q=85')"></div>
  <div class="container hero-content">
    <span class="eyebrow">BARCELONA • WORLDWIDE TRAVEL</span>
    <h1>Your Journey.<br><span>Our Priority.</span></h1>
    <p>Flight tickets, hotels, Umrah & Hajj arrangements, visa assistance and reliable 24/7 emergency travel support.</p>
    <div class="hero-actions"><a class="btn btn-primary btn-lg" href="offers.php">Explore Offers</a><a class="btn btn-glass btn-lg" href="contact.php">Request a Quote</a></div>
    <div class="hero-trust"><div><strong>8+</strong><small>Years Travel Experience</small></div><div><strong>10,000+</strong><small>Clients Served</small></div><div><strong>500+</strong><small>Umrah Packages</small></div><div><strong>24/7</strong><small>Emergency Support</small></div></div>
  </div>
  <div class="hero-dots"><button class="active"></button><button></button><button></button></div>
</section>

<section class="search-card-wrap">
 <div class="container">
  <div class="search-card">
    <div class="search-tabs"><button class="active">✈ Flights</button><button>▦ Hotels</button><button>☾ Umrah</button><button>✓ Visa</button></div>
    <form class="quick-search" action="contact.php" method="get">
      <label>From<input name="from" placeholder="Barcelona (BCN)"></label>
      <label>To<input name="to" placeholder="Islamabad / Lahore / Worldwide"></label>
      <label>Departure<input type="date" name="departure"></label>
      <label>Return<input type="date" name="return"></label>
      <button class="btn btn-primary" type="submit">Request Fare</button>
    </form>
  </div>
 </div>
</section>

<section class="section">
 <div class="container">
  <div class="section-head"><div><span class="eyebrow dark">LATEST DEALS</span><h2>Special Offers</h2></div><a href="offers.php">View all offers →</a></div>
  <div class="offers-grid">
  <?php if(!$featured): ?>
    <?php foreach([
      ['Barcelona → Islamabad','From €640','Etihad Airways','40kg + 7kg'],
      ['Barcelona → Lahore','From €655','Qatar Airways','45kg + 7kg'],
      ['Barcelona → Pakistan','From €680','Emirates','Selected Dates']
    ] as $x): ?>
      <article class="offer-card placeholder-offer"><div class="offer-media"><span class="offer-badge">Special Fare</span></div><div class="offer-body"><small><?=h($x[2])?></small><h3><?=h($x[0])?></h3><p><?=h($x[3])?></p><div class="price"><?=h($x[1])?></div><a class="btn btn-dark" href="contact.php">Get Quote</a></div></article>
    <?php endforeach; ?>
  <?php else: foreach($featured as $o): ?>
    <article class="offer-card">
      <div class="offer-media" <?php if($o['image_url']): ?>style="background-image:url('<?=h($o['image_url'])?>')"<?php endif; ?>><span class="offer-badge"><?=h($o['badge'] ?: 'Special Offer')?></span></div>
      <div class="offer-body"><small><?=h($o['airline'])?></small><h3><?=h($o['title'])?></h3><p><?=h($o['travel_dates'])?> · <?=h($o['baggage'])?></p><div class="price"><?=h($o['currency'])?> <?=number_format((float)$o['price'],0)?></div><a class="btn btn-dark" href="https://wa.me/<?=WHATSAPP?>?text=<?=urlencode('I am interested in: '.$o['title'])?>" target="_blank">Book / Ask Now</a></div>
    </article>
  <?php endforeach; endif; ?>
  </div>
 </div>
</section>

<section class="section section-soft">
 <div class="container">
  <div class="section-head centered"><div><span class="eyebrow dark">WHAT WE DO</span><h2>Complete Travel Services</h2><p>One trusted team for your journey from planning to return.</p></div></div>
  <div class="service-grid">
   <a class="service-card" href="services.php"><div class="service-icon">✈</div><h3>Air Tickets</h3><p>Competitive international fares and expert itinerary support.</p><span>Explore →</span></a>
   <a class="service-card" href="hotels.php"><div class="service-icon">▦</div><h3>Hotel Booking</h3><p>Worldwide hotel reservations including Makkah and Madinah.</p><span>Explore →</span></a>
   <a class="service-card" href="umrah.php"><div class="service-icon">☾</div><h3>Umrah & Hajj</h3><p>Visa, flights, hotels, transport and customized religious packages.</p><span>Explore →</span></a>
   <a class="service-card" href="services.php#visa"><div class="service-icon">✓</div><h3>Visa Assistance</h3><p>Travel visa support and document guidance for multiple destinations.</p><span>Explore →</span></a>
  </div>
 </div>
</section>

<section class="story-section">
 <div class="container story-grid">
  <div class="story-image"></div>
  <div class="story-copy"><span class="eyebrow dark">ABOUT MUSTAFA TRAVELS</span><h2>Built on experience, service and trust.</h2>
   <p>My name is <strong>Ghulam Mustafa Haidry</strong>. I have been working in the travel field for more than <strong>8 years</strong>. In January 2024, I established <strong>Mustafa Travels & Tours</strong> with the aim of providing professional, transparent and dependable travel services from Barcelona.</p>
   <p>Since then, our agency has served more than <strong>10,000 clients</strong> and arranged more than <strong>500 Umrah packages</strong>, while continuing to provide 24/7 emergency travel support.</p>
   <a class="btn btn-dark" href="about.php">Our Story</a>
  </div>
 </div>
</section>

<section class="section">
 <div class="container">
  <div class="section-head centered"><div><span class="eyebrow dark">TRAINING & PARTNERS</span><h2>Professional Industry Credentials</h2><p>Certified learning and supplier partnerships supporting better service.</p></div></div>
  <div class="partner-strip"><div>EXPEDIA TAAP</div><div>TBO HOLIDAYS</div><div>UMRAH & HOTEL SUPPLIERS</div><div>GLOBAL AIRLINES</div></div>
  <div class="center-actions"><a class="btn btn-outline" href="certificates.php">View Certificates</a></div>
 </div>
</section>

<section class="cta">
 <div class="container cta-inner"><div><span class="eyebrow">NEED URGENT TRAVEL HELP?</span><h2>We are available 24/7.</h2><p>Emergency airline ticket support, itinerary changes and urgent travel assistance.</p></div><a class="btn btn-white btn-lg" href="https://wa.me/<?=WHATSAPP?>" target="_blank">WhatsApp Now</a></div>
</section>
<?php site_footer(); ?>