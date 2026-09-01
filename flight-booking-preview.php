<?php
require_once __DIR__ . '/partials.php';
require_once __DIR__ . '/api/duffel.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$offerId = trim($_GET['offer_id'] ?? '');
$error = '';
$offer = [];
$checkout = [];

if ($offerId === '') {
    $error = 'Missing offer ID.';
} elseif (empty($_SESSION['flight_checkout'][$offerId])) {
    $error = 'Passenger details are missing or your session expired.';
} else {
    $checkout = $_SESSION['flight_checkout'][$offerId];
    $api = mt_duffel_get_offer($offerId, true);
    if (!$api['ok']) $error = $api['error'];
    else $offer = $api['data']['data'] ?? [];
}

function bp_time(?string $v): string { $t=$v?strtotime($v):false; return $t?date('H:i',$t):''; }
function bp_date(?string $v): string { $t=$v?strtotime($v):false; return $t?date('D, d M Y',$t):''; }
function bp_mask(string $v): string { $v=trim($v); $l=strlen($v); return $l<=4?str_repeat('*',$l):substr($v,0,2).str_repeat('*',max(2,$l-4)).substr($v,-2); }
function bp_money($v,$c): string { return h((string)$c).' '.number_format((float)$v,2); }
function bp_names(array $offer,array $ids): string {
    $a=[]; foreach(($offer['passengers']??[]) as $i=>$p){ if(!in_array((string)($p['id']??''),$ids,true))continue; $n=trim(($p['given_name']??'').' '.($p['family_name']??'')); $a[]=$n?:'Passenger '.($i+1); } return $a?implode(', ',$a):'Passenger';
}
function bp_route(array $offer,array $ids): string {
    $a=[]; foreach(($offer['slices']??[]) as $s) foreach(($s['segments']??[]) as $g){ if(!in_array((string)($g['id']??''),$ids,true))continue; $a[]=(string)($g['origin']['iata_code']??'').' → '.(string)($g['destination']['iata_code']??''); } return $a?implode(', ',array_unique($a)):'Applicable segment';
}

$selected = $checkout['selected_services'] ?? [];
$services = [];
$extraTotal = 0.0;
foreach (($offer['available_services'] ?? []) as $s) {
    if (($s['type'] ?? '') !== 'baggage' || empty($s['id'])) continue;
    $qty = (int)($selected[$s['id']] ?? 0);
    if ($qty < 1) continue;
    $s['_qty'] = $qty;
    $services[] = $s;
    $extraTotal += $qty * (float)($s['total_amount'] ?? 0);
}
$baseTotal = (float)($offer['total_amount'] ?? 0);
$grandTotal = $baseTotal + $extraTotal;
$currency = (string)($offer['total_currency'] ?? 'EUR');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Booking Preview - Mustafa Travels & Tours</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#eef3f7;color:#152b40;font-family:Arial,Helvetica,sans-serif}.page{width:210mm;min-height:297mm;margin:18px auto;background:#fff;padding:15mm;box-shadow:0 8px 30px rgba(24,55,80,.12)}
.top{display:flex;justify-content:space-between;gap:30px;padding-bottom:16px;border-bottom:3px solid #0b3568}.brand h1{margin:0;color:#0b3568;font-size:25px}.brand p{margin:4px 0;color:#70859a;font-size:11px}.doc{text-align:right}.doc strong{font-size:18px;color:#0b3568}.doc small{display:block;color:#7a8ea0;margin-top:4px}
.alert{padding:12px;border:1px solid #f3c4cb;background:#fff2f4;color:#ad2840;border-radius:8px;margin-top:18px}.section{margin-top:22px}.section h2{font-size:14px;margin:0 0 10px;color:#0b3568;border-bottom:1px solid #dfe7ed;padding-bottom:7px}.box{border:1px solid #dfe7ed;border-radius:10px;padding:12px;margin-bottom:9px}.grid2{display:grid;grid-template-columns:1fr 1fr;gap:12px}.row{display:flex;justify-content:space-between;gap:18px}.muted{color:#768b9d;font-size:10px}.route{font-size:16px;font-weight:800}.segment{padding:12px 0;border-bottom:1px solid #e9eef2}.segment:last-child{border:0}.pass{padding:9px 0;border-bottom:1px solid #edf1f4}.pass:last-child{border:0}.money{margin-left:auto;width:310px;margin-top:20px}.money .line{display:flex;justify-content:space-between;padding:7px 0;color:#607488}.money .total{border-top:2px solid #0b3568;margin-top:5px;padding-top:10px;font-size:19px;color:#0b3568;font-weight:900}.foot{margin-top:30px;padding-top:12px;border-top:1px solid #dfe7ed;font-size:9px;line-height:1.5;color:#74899a;text-align:center}.tools{width:210mm;margin:15px auto;display:flex;justify-content:flex-end;gap:8px}.tools button{border:0;border-radius:8px;padding:10px 15px;font-weight:800;cursor:pointer}.print{background:#0b3568;color:#fff}.back{background:#fff;border:1px solid #ccd8e1!important;color:#27455f}
@media print{body{background:#fff}.page{margin:0;box-shadow:none;width:auto;min-height:auto;padding:12mm}.tools{display:none}@page{size:A4;margin:0}}
</style>
</head>
<body>
<div class="tools"><button class="back" onclick="window.close()">Close Preview</button><button class="print" onclick="window.print()">Print / Save as PDF</button></div>
<div class="page">
    <div class="top">
        <div class="brand"><h1>Mustafa Travels & Tours</h1><p>Barcelona, Spain · www.mustafatravels.org</p></div>
        <div class="doc"><strong>BOOKING PREVIEW</strong><small>Offer <?=h(substr($offerId,0,18))?>...</small><small><?=h(date('d M Y H:i'))?></small></div>
    </div>

    <?php if($error): ?>
        <div class="alert"><?=h($error)?></div>
    <?php else: ?>
        <div class="section">
            <h2>Flight itinerary</h2>
            <div class="box">
                <div class="row"><strong><?=h((string)($offer['owner']['name'] ?? 'Airline'))?></strong><span class="muted">Fare refreshed from airline</span></div>
                <?php foreach(($offer['slices'] ?? []) as $slice): $seg=$slice['segments']??[];$first=$seg[0]??[];$last=$seg?end($seg):[]; ?>
                    <div class="segment">
                        <div class="row"><span class="route"><?=h((string)($first['origin']['iata_code']??''))?> <?=h(bp_time($first['departing_at']??null))?></span><span class="route"><?=h((string)($last['destination']['iata_code']??''))?> <?=h(bp_time($last['arriving_at']??null))?></span></div>
                        <div class="row muted"><span><?=h(bp_date($first['departing_at']??null))?></span><span><?=h(bp_date($last['arriving_at']??null))?></span></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section"><h2>Passengers</h2><div class="box">
            <?php foreach(($checkout['passengers'] ?? []) as $p): ?>
                <div class="pass"><strong><?=h(trim(($p['given_name']??'').' '.($p['family_name']??'')))?></strong><div class="muted"><?=h(ucfirst((string)($p['type']??'passenger')))?> · DOB <?=h((string)($p['born_on']??''))?><?php if(!empty($p['passport_number'])): ?> · Passport <?=h(bp_mask((string)$p['passport_number']))?><?php endif; ?></div></div>
            <?php endforeach; ?>
        </div></div>

        <div class="section"><h2>Selected extra baggage</h2>
            <?php if($services): ?>
                <?php foreach($services as $s): ?><div class="box row"><div><strong><?=$s['_qty']?> × Extra checked bag</strong><div class="muted"><?=h(bp_names($offer,$s['passenger_ids']??[]))?> · <?=h(bp_route($offer,$s['segment_ids']??[]))?></div></div><strong><?=bp_money(((float)($s['total_amount']??0))*$s['_qty'],$s['total_currency']??$currency)?></strong></div><?php endforeach; ?>
            <?php else: ?><div class="box"><span class="muted">No extra checked baggage selected.</span></div><?php endif; ?>
        </div>

        <div class="money">
            <div class="line"><span>Flight fare</span><strong><?=bp_money($baseTotal,$currency)?></strong></div>
            <div class="line"><span>Extra baggage</span><strong><?=bp_money($extraTotal,$currency)?></strong></div>
            <div class="line total"><span>Total</span><span><?=bp_money($grandTotal,$currency)?></span></div>
        </div>

        <div class="foot">This is a booking preview/quotation and is not an issued airline ticket. Fare, availability, baggage services and payment deadline remain subject to airline confirmation until the booking/order is created. Passport numbers are masked for privacy.</div>
    <?php endif; ?>
</div>
</body>
</html>
