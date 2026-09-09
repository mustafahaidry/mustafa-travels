<?php
declare(strict_types=1);
require_once __DIR__.'/partials.php';
require_once __DIR__.'/lib/booking.php';
$booking=null;$order=[];$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $ref=strtoupper(trim((string)($_POST['booking_ref']??'')));$email=strtolower(trim((string)($_POST['email']??'')));
  if($ref===''||!filter_var($email,FILTER_VALIDATE_EMAIL)) $error='Please enter your booking reference and the same email used for the booking.';
  else { $booking=mt_booking_find_for_customer($ref,$email); if(!$booking)$error='Booking not found. Please check the reference and email.'; else $order=mt_booking_order($booking); }
}
site_header('My Booking');
function mb_e($v){return h((string)$v);} ?>
<style>
.mb-wrap{width:min(980px,calc(100% - 28px));margin:38px auto 70px}.mb-card{background:#fff;border:1px solid #dce7f0;border-radius:16px;padding:24px;box-shadow:0 12px 35px rgba(8,47,95,.06)}.mb-form{display:grid;grid-template-columns:1fr 1fr auto;gap:10px}.mb-form input{padding:13px;border:1px solid #cddbe8;border-radius:9px;font-size:14px}.mb-btn{border:0;border-radius:9px;background:#073568;color:#fff;padding:13px 22px;font-weight:800;cursor:pointer}.mb-error{background:#fff1f1;color:#b42318;padding:12px;border-radius:9px;margin-top:12px}.mb-top{display:flex;justify-content:space-between;gap:15px;align-items:start;margin-top:20px}.mb-pnr{font-size:29px;color:#07834b;font-weight:900}.mb-status{display:inline-block;padding:6px 10px;border-radius:999px;background:#e9f8ef;color:#087443;font-weight:800}.mb-table{width:100%;border-collapse:collapse;margin-top:12px}.mb-table th,.mb-table td{text-align:left;padding:10px;border-bottom:1px solid #e6edf3;font-size:13px}.mb-table th{background:#f3f8fc}.mb-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}.mb-actions a{text-decoration:none}.mb-section{margin-top:24px}.mb-section h3{color:#073568}.mb-bags li{margin:6px 0}@media(max-width:720px){.mb-wrap{margin-top:20px}.mb-card{padding:16px}.mb-form{grid-template-columns:1fr}.mb-top{flex-direction:column}.mb-table{display:block;overflow-x:auto;white-space:nowrap}}
</style>
<div class="mb-wrap"><h1>My Booking</h1><p>Enter your Mustafa Travels booking reference and the email used during checkout.</p><div class="mb-card">
<form method="post" class="mb-form"><input name="booking_ref" placeholder="MT-260909-XXXXXX" value="<?=mb_e($_POST['booking_ref']??'')?>" required><input type="email" name="email" placeholder="Email address" value="<?=mb_e($_POST['email']??'')?>" required><button class="mb-btn">View Booking</button></form>
<?php if($error):?><div class="mb-error"><?=mb_e($error)?></div><?php endif;?>
<?php if($booking): $segments=mt_booking_itinerary($order);$bags=mt_booking_baggage($order);$checkout=mt_booking_checkout($booking); ?>
<div class="mb-top"><div><span class="mb-status"><?=mb_e(ucwords(str_replace('_',' ',(string)$booking['status'])))?></span><h2><?=mb_e($booking['booking_ref'])?></h2><div><?=mb_e($booking['customer_name'])?> · <?=mb_e($booking['customer_email'])?></div></div><div><small>Airline PNR</small><div class="mb-pnr"><?=mb_e($booking['pnr']?:'Pending')?></div><b><?=mb_e($booking['currency'])?> <?=number_format((float)$booking['amount'],2)?></b></div></div>
<div class="mb-section"><h3>Flight itinerary</h3><table class="mb-table"><tr><th>Airline / Flight</th><th>From</th><th>Departure</th><th>To</th><th>Arrival</th></tr><?php if($segments):foreach($segments as $x):?><tr><td><b><?=mb_e($x['airline'])?></b><br><?=mb_e($x['flight'])?></td><td><?=mb_e($x['origin'])?><br><?=mb_e($x['origin_name'])?></td><td><?=mb_e($x['departing_at']?date('d M Y H:i',strtotime($x['departing_at'])):'')?></td><td><?=mb_e($x['destination'])?><br><?=mb_e($x['destination_name'])?></td><td><?=mb_e($x['arriving_at']?date('d M Y H:i',strtotime($x['arriving_at'])):'')?></td></tr><?php endforeach;else:?><tr><td colspan="5">Itinerary is available after airline confirmation.</td></tr><?php endif;?></table></div>
<div class="mb-section"><h3>Passengers</h3><table class="mb-table"><tr><th>Name</th><th>Type</th><th>Date of birth</th></tr><?php foreach(($checkout['passengers']??[]) as $p):?><tr><td><?=mb_e(trim(($p['title']??'').' '.($p['given_name']??'').' '.($p['family_name']??'')))?></td><td><?=mb_e(ucfirst((string)($p['type']??'passenger')))?></td><td><?=mb_e($p['born_on']??'')?></td></tr><?php endforeach;?></table></div>
<div class="mb-section"><h3>Baggage allowance & extras</h3><?php if($bags):?><ul class="mb-bags"><?php foreach($bags as $b):?><li><?=mb_e($b)?></li><?php endforeach;?></ul><?php else:?><p>Please check the airline fare conditions for baggage allowance.</p><?php endif;?></div>
<div class="mb-actions"><?php if(($booking['status']??'')==='confirmed'):?><a class="mb-btn" href="booking-pdf.php?ref=<?=urlencode((string)$booking['booking_ref'])?>&email=<?=urlencode((string)$booking['customer_email'])?>">Download Confirmation PDF</a><?php endif;?></div>
<?php endif;?></div></div>
<?php site_footer(); ?>
