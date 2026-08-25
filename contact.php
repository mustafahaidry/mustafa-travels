<?php
require_once __DIR__.'/partials.php';
$sent=false;
if($_SERVER['REQUEST_METHOD']==='POST'){
 $sent=sb_insert('inquiries',[
   'name'=>$_POST['name']??'',
   'phone'=>$_POST['phone']??'',
   'email'=>$_POST['email']??'',
   'service'=>$_POST['service']??'',
   'origin'=>$_POST['origin']??'',
   'destination'=>$_POST['destination']??'',
   'departure_date'=>$_POST['departure_date']??'',
   'return_date'=>$_POST['return_date']??'',
   'passengers'=>(int)($_POST['passengers']??1),
   'message'=>$_POST['message']??'',
   'status'=>'New'
 ]);
}
site_header('Contact');
?>
<section class="page-hero"><div class="container"><span class="eyebrow">CONTACT US</span><h1>Request a quote or travel assistance.</h1></div></section>
<section class="section"><div class="container contact-grid">
<div><h2>Mustafa Travels & Tours</h2><p><?=ADDRESS?></p><div class="contact-list"><a href="tel:+34632234216">☎ <?=PHONE1?></a><a href="mailto:<?=EMAIL?>">✉ <?=EMAIL?></a><a href="https://wa.me/<?=WHATSAPP?>" target="_blank">◉ WhatsApp 24/7 — <?=PHONE2?></a></div><div class="emergency-box"><strong>24/7 Emergency Travel Service</strong><p>For urgent ticketing or travel assistance, contact us directly by WhatsApp.</p></div></div>
<div class="form-card"><?php if($sent): ?><div class="success">Thank you. Your inquiry has been received.</div><?php endif; ?><form method="post">
<label>Name<input name="name" required></label><label>Phone / WhatsApp<input name="phone" required></label><label>Email<input name="email" type="email"></label>
<label>Service<select name="service"><option>Flight Ticket</option><option>Hotel</option><option>Umrah / Hajj</option><option>Visa Assistance</option><option>Other</option></select></label>
<div class="form-2"><label>From<input name="origin"></label><label>To<input name="destination"></label></div>
<div class="form-2"><label>Departure<input name="departure_date" type="date"></label><label>Return<input name="return_date" type="date"></label></div>
<label>Passengers<input name="passengers" type="number" value="1" min="1"></label>
<label>Message<textarea name="message" rows="5" placeholder="Tell us your route, dates and number of passengers..."></textarea></label>
<button class="btn btn-primary btn-lg" type="submit">Send Request</button></form></div>
</div></section>
<?php site_footer(); ?>