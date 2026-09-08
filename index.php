<?php
require_once __DIR__.'/partials.php';
$featured=sb_select('offers','select=*&active=eq.true&featured=eq.true&order=id.desc&limit=6');
site_header('Home');
?>
<main class="v3-home">
<section class="v3-hero">
 <div class="container v3-hero-inner">
  <div class="v3-hero-copy">
   <span class="v3-kicker">SPIRITUAL JOURNEYS • GLOBAL DESTINATIONS</span>
   <h1>Umrah &amp; Hajj<br>Made <em>Easier</em></h1>
   <p>Trusted travel services from Barcelona for your spiritual and worldwide journeys.</p>
   <div class="v3-stats"><div><b>10,000+</b><span>Happy Clients</span></div><div><b>8+</b><span>Years Experience</span></div><div><b>24/7</b><span>Travel Support</span></div></div>
  </div>
  <a class="v3-spiritual" href="umrah.php"><span>☪</span><b>Your Spiritual Journey<br>Our Responsibility</b><i>→</i></a>
 </div>
</section>

<section class="v3-search-overlap"><div class="container"><div class="v3-search-card">
 <div class="v3-search-tabs"><span class="active">✈ Flights</span><a href="hotels.php">▥ Hotels</a><a href="umrah.php">♙ Umrah Packages</a><a href="umrah.php#hajj">♟ Hajj 2027</a><a href="services.php#visa">▣ Visa Services</a></div>
 <div id="tpwl-search"></div><div id="tpwl-tickets" class="tpwl-results"></div>
 <div class="v3-search-trust"><span>● Compare 100+ Airlines</span><span>● Best Price Options</span><span>● Secure Search</span></div>
</div></div></section>

<section class="v3-religious"><div class="container v3-banner-grid">
 <a class="v3-banner umrah" href="umrah.php"><div><h2>Umrah Packages<br><em>from Barcelona</em></h2><p>Flights, Hotels, Visa &amp; Transport</p><span>View Umrah Packages →</span></div></a>
 <a class="v3-banner hajj" id="hajj" href="umrah.php#hajj"><div><h2>Hajj 2027</h2><p>Early Registration &amp; Guidance</p><span>More Information →</span></div></a>
</div></section>

<?php if($featured): ?>
<section class="v3-offers"><div class="container"><div class="v3-title"><small>LATEST DEALS</small><h2>Special Offers</h2></div><div class="offers-grid">
<?php foreach($featured as $o): ?><article class="offer-card"><div class="offer-media" <?php if(!empty($o['image_url'])): ?>style="background-image:url('<?=h($o['image_url'])?>')"<?php endif;?>><span class="offer-badge"><?=h($o['badge'] ?: 'Special Offer')?></span></div><div class="offer-body"><small><?=h($o['airline'])?></small><h3><?=h($o['title'])?></h3><p><?=h($o['travel_dates'])?> · <?=h($o['baggage'])?></p><div class="price"><?=h($o['currency'])?> <?=number_format((float)$o['price'],0)?></div><a class="btn btn-dark" href="https://wa.me/<?=WHATSAPP?>?text=<?=urlencode('I am interested in: '.$o['title'])?>" target="_blank">Book / Ask Now</a></div></article><?php endforeach;?>
</div></div></section><?php endif;?>

<section class="v3-services"><div class="container"><div class="v3-title"><small>WHAT WE DO</small><h2>Complete Travel Services</h2><p>One trusted team for your journey from planning to return.</p></div>
<div class="v3-service-grid"><a href="services.php"><i>✈</i><h3>Air Tickets</h3><p>Competitive international fares and expert itinerary support.</p><b>Explore →</b></a><a href="hotels.php"><i>▦</i><h3>Hotel Booking</h3><p>Worldwide hotel reservations including Makkah and Madinah.</p><b>Explore →</b></a><a href="umrah.php"><i>☾</i><h3>Umrah &amp; Hajj</h3><p>Visa, flights, hotels, transport and customized religious packages.</p><b>Explore →</b></a><a href="services.php#visa"><i>✓</i><h3>Visa Assistance</h3><p>Travel visa support and document guidance for multiple destinations.</p><b>Explore →</b></a></div></div></section>

<section class="v3-about"><div class="container v3-about-grid">
 <div class="v3-owner"><img src="assets/images/ghulam-mustafa-original.png" alt="Ghulam Mustafa - Mustafa Travels & Tours"></div>
 <div class="v3-about-copy"><small>ABOUT MUSTAFA TRAVELS</small><h2>Built on experience, service<br>and trust.</h2><p>My name is <strong>Ghulam Mustafa Haidry</strong>. I have been working in the travel field for more than <strong>8 years</strong>. In January 2024, I established <strong>Mustafa Travels &amp; Tours</strong> with the aim of providing professional, transparent and dependable travel services from Barcelona.</p><a class="v3-gold-btn" href="about.php">Read My Story →</a></div>
 <div class="v3-trust-card"><p>◇ <b>Trusted &amp; Reliable</b></p><p>♟ <b>Personalized Service</b></p><p>◉ <b>24/7 Customer Support</b></p><p>◆ <b>Your Journey, Our Responsibility</b></p></div>
</div></section>

<section class="v3-metrics"><div class="container v3-metrics-grid"><div>✈ <b>10,000+</b><span>Happy Clients</span></div><div>◆ <b>8+</b><span>Years of Experience</span></div><div>◎ <b>50+</b><span>Destinations</span></div><div>◉ <b>24/7</b><span>Travel Support</span></div></div></section>
<section class="v3-cta"><div class="container"><div><h2>Ready to plan your next journey?</h2><p>Get in touch with us today for travel deals and expert guidance.</p></div><a href="https://wa.me/<?=WHATSAPP?>" target="_blank">◉ Chat on WhatsApp →</a></div></section>
</main>
<?php site_footer(); ?>
