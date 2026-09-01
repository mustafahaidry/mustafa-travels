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

function bp_time(?string $v): string {
    $t = $v ? strtotime($v) : false;
    return $t ? date('H:i', $t) : '';
}
function bp_date(?string $v): string {
    $t = $v ? strtotime($v) : false;
    return $t ? date('D, d M Y', $t) : '';
}
function bp_date_short(?string $v): string {
    $t = $v ? strtotime($v) : false;
    return $t ? date('d M Y', $t) : '';
}
function bp_mask(string $v): string {
    $v = trim($v);
    $l = strlen($v);
    return $l <= 4 ? str_repeat('*', $l) : substr($v,0,2).str_repeat('*', max(2,$l-4)).substr($v,-2);
}
function bp_money($v, $c): string {
    return h((string)$c).' '.number_format((float)$v, 2);
}
function bp_duration(?string $a, ?string $b): string {
    if (!$a || !$b) return '';
    $s = strtotime($a); $e = strtotime($b);
    if (!$s || !$e || $e <= $s) return '';
    $m = (int)round(($e - $s) / 60);
    $h = intdiv($m, 60); $r = $m % 60;
    return ($h ? $h.'h ' : '').($r ? $r.'m' : '');
}
function bp_layover(?string $arrive, ?string $depart): string {
    return bp_duration($arrive, $depart);
}
function bp_airport_name(array $a): string {
    return trim((string)($a['name'] ?? $a['city_name'] ?? ''));
}
function bp_terminal(array $endpoint, string $key = 'terminal'): string {
    $v = trim((string)($endpoint[$key] ?? ''));
    return $v !== '' ? $v : '—';
}
function bp_carrier(array $seg, array $offer): string {
    return (string)($seg['marketing_carrier']['name']
        ?? $seg['operating_carrier']['name']
        ?? $offer['owner']['name']
        ?? 'Airline');
}
function bp_flight_no(array $seg): string {
    $code = (string)($seg['marketing_carrier']['iata_code'] ?? $seg['operating_carrier']['iata_code'] ?? '');
    $num  = (string)($seg['marketing_carrier_flight_number'] ?? $seg['operating_carrier_flight_number'] ?? '');
    return trim($code.' '.$num);
}
function bp_aircraft(array $seg): string {
    return trim((string)($seg['aircraft']['name'] ?? ''));
}
function bp_cabin(array $seg): string {
    $cabins = [];
    foreach (($seg['passengers'] ?? []) as $p) {
        $c = trim((string)($p['cabin_class_marketing_name'] ?? $p['cabin_class'] ?? ''));
        if ($c !== '') $cabins[] = $c;
    }
    $cabins = array_values(array_unique($cabins));
    return $cabins ? implode(', ', $cabins) : '—';
}
function bp_baggage_text(array $bag): string {
    $type = strtolower((string)($bag['type'] ?? ''));
    $qty  = $bag['quantity'] ?? null;
    $w    = $bag['weight'] ?? null;
    $unit = $bag['weight_unit'] ?? 'kg';

    if ($w !== null && $w !== '') {
        $prefix = ($qty !== null && (int)$qty > 1) ? ((int)$qty).' × ' : '';
        return $prefix.((float)$w == (int)$w ? (int)$w : $w).' '.strtoupper((string)$unit);
    }
    if ($qty !== null) {
        $label = $type === 'carry_on' ? 'cabin bag' : 'checked bag';
        return (int)$qty.' × '.$label;
    }
    return '';
}
function bp_segment_baggage(array $seg): array {
    $checked = [];
    $cabin = [];

    foreach (($seg['passengers'] ?? []) as $p) {
        foreach (($p['baggages'] ?? []) as $b) {
            $txt = bp_baggage_text($b);
            if ($txt === '') continue;
            $type = strtolower((string)($b['type'] ?? ''));
            if ($type === 'carry_on') $cabin[] = $txt;
            else $checked[] = $txt;
        }
    }

    $checked = array_values(array_unique($checked));
    $cabin   = array_values(array_unique($cabin));
    return [
        'checked' => $checked ? implode(' / ', $checked) : 'Not specified by airline',
        'cabin'   => $cabin   ? implode(' / ', $cabin)   : 'Not specified by airline',
    ];
}
function bp_names(array $offer, array $ids): string {
    $a = [];
    foreach (($offer['passengers'] ?? []) as $i => $p) {
        if (!in_array((string)($p['id'] ?? ''), $ids, true)) continue;
        $n = trim(($p['given_name'] ?? '').' '.($p['family_name'] ?? ''));
        $a[] = $n ?: 'Passenger '.($i+1);
    }
    return $a ? implode(', ', $a) : 'Passenger';
}
function bp_route(array $offer, array $ids): string {
    $a = [];
    foreach (($offer['slices'] ?? []) as $s) {
        foreach (($s['segments'] ?? []) as $g) {
            if (!in_array((string)($g['id'] ?? ''), $ids, true)) continue;
            $a[] = (string)($g['origin']['iata_code'] ?? '').' → '.(string)($g['destination']['iata_code'] ?? '');
        }
    }
    return $a ? implode(', ', array_unique($a)) : 'Applicable segment';
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

$baseTotal  = (float)($offer['total_amount'] ?? 0);
$grandTotal = $baseTotal + $extraTotal;
$currency   = (string)($offer['total_currency'] ?? 'EUR');
$ownerName  = (string)($offer['owner']['name'] ?? 'Airline');
$ownerLogo  = (string)($offer['owner']['logo_symbol_url'] ?? $offer['owner']['logo_lockup_url'] ?? '');
$previewRef = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $offerId), -10));

$firstSlice = $offer['slices'][0] ?? [];
$lastSlice  = $offer['slices'] ? $offer['slices'][count($offer['slices']) - 1] : [];
$firstSegs  = $firstSlice['segments'] ?? [];
$lastSegs   = $lastSlice['segments'] ?? [];
$journeyFrom = $firstSegs[0]['origin']['iata_code'] ?? '';
$journeyTo = $lastSegs ? ($lastSegs[count($lastSegs)-1]['destination']['iata_code'] ?? '') : '';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Premium Booking Preview - Mustafa Travels & Tours</title>
<style>
:root{
    --navy:#062b55;
    --navy2:#0b3d78;
    --gold:#c8a24a;
    --ink:#12263b;
    --muted:#6f8295;
    --line:#dbe4ec;
    --soft:#f5f8fb;
    --soft2:#eef4f8;
    --success:#13805d;
}
*{box-sizing:border-box}
body{
    margin:0;background:#eaf0f5;color:var(--ink);
    font-family:Inter,Arial,Helvetica,sans-serif;
}
.tools{
    width:210mm;margin:16px auto;display:flex;justify-content:flex-end;gap:10px
}
.tools button{
    border-radius:10px;padding:11px 18px;font-weight:800;cursor:pointer;font-size:13px
}
.print{background:var(--navy);border:1px solid var(--navy);color:#fff}
.back{background:#fff;border:1px solid #cfd9e2;color:#27455f}
.page{
    width:210mm;min-height:297mm;margin:0 auto 24px;background:#fff;
    box-shadow:0 10px 35px rgba(23,49,72,.15)
}
.hero{
    background:linear-gradient(135deg,#062b55 0%,#0a427e 100%);
    color:#fff;padding:15mm 14mm 10mm;position:relative;overflow:hidden
}
.hero:after{
    content:"";position:absolute;right:-36mm;top:-28mm;width:90mm;height:90mm;
    border:1px solid rgba(255,255,255,.12);border-radius:50%
}
.heroTop{display:flex;justify-content:space-between;gap:20px;align-items:flex-start}
.brandName{font-size:26px;font-weight:900;letter-spacing:.2px}
.brandTag{font-size:10px;opacity:.78;margin-top:5px}
.docBox{text-align:right}
.docLabel{font-size:10px;letter-spacing:1.8px;opacity:.78}
.docTitle{font-size:20px;font-weight:900;margin-top:4px}
.ref{font-size:10px;opacity:.82;margin-top:4px}
.routeHero{
    margin-top:14mm;display:flex;align-items:center;justify-content:space-between;gap:16px
}
.airportCode{font-size:31px;font-weight:900;line-height:1}
.airportName{font-size:10px;opacity:.8;margin-top:5px;max-width:62mm}
.routeArrow{flex:1;display:flex;align-items:center;gap:8px;justify-content:center}
.routeArrow .line{height:1px;background:rgba(255,255,255,.45);flex:1}
.routeArrow .plane{font-size:18px}
.goldbar{height:4px;background:var(--gold)}
.content{padding:10mm 12mm 13mm}
.topMeta{
    display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-top:-17mm;
    position:relative;z-index:2
}
.metaCard{
    background:#fff;border:1px solid var(--line);border-radius:12px;padding:10px 12px;
    box-shadow:0 5px 18px rgba(23,49,72,.08)
}
.metaLabel{font-size:8px;color:var(--muted);text-transform:uppercase;letter-spacing:.8px}
.metaValue{font-size:11px;font-weight:900;color:var(--navy);margin-top:4px}
.section{margin-top:18px}
.sectionTitle{
    display:flex;align-items:center;justify-content:space-between;gap:10px;
    color:var(--navy);font-size:14px;font-weight:900;margin-bottom:8px
}
.sectionTitle:after{content:"";height:1px;background:var(--line);flex:1}
.slice{
    border:1px solid var(--line);border-radius:14px;overflow:hidden;margin-bottom:12px
}
.sliceHead{
    background:var(--soft);padding:9px 11px;display:flex;justify-content:space-between;gap:10px;
    align-items:center;border-bottom:1px solid var(--line)
}
.sliceHead strong{color:var(--navy);font-size:12px}
.sliceHead span{font-size:9px;color:var(--muted)}
.segmentCard{padding:12px 13px}
.segmentTop{display:flex;justify-content:space-between;gap:16px;align-items:center}
.airline{display:flex;gap:9px;align-items:center}
.logo{
    width:34px;height:34px;border-radius:9px;border:1px solid var(--line);
    display:flex;align-items:center;justify-content:center;background:#fff;overflow:hidden
}
.logo img{max-width:26px;max-height:26px}
.airlineName{font-size:11px;font-weight:900;color:var(--navy)}
.flightNo{font-size:9px;color:var(--muted);margin-top:2px}
.segmentGrid{
    display:grid;grid-template-columns:1fr 58px 1fr;gap:10px;align-items:center;margin-top:12px
}
.pointCode{font-size:21px;font-weight:900;color:var(--navy)}
.pointTime{font-size:17px;font-weight:900;margin-top:2px}
.pointDate{font-size:8px;color:var(--muted);margin-top:3px}
.pointName{font-size:9px;color:#4f6477;margin-top:4px}
.point.right{text-align:right}
.flightPath{text-align:center;color:var(--muted)}
.flightPath .dur{font-size:8px;margin-bottom:4px}
.flightPath .miniLine{height:1px;background:#aebdca;position:relative}
.flightPath .miniLine:after{
    content:"✈";position:absolute;left:50%;top:50%;transform:translate(-50%,-55%);
    background:#fff;padding:0 4px;color:var(--navy);font-size:11px
}
.details{
    display:grid;grid-template-columns:repeat(4,1fr);gap:7px;margin-top:12px;
    background:var(--soft);border-radius:10px;padding:8px
}
.detail .k{font-size:7px;color:var(--muted);text-transform:uppercase}
.detail .v{font-size:9px;font-weight:800;color:#2a4158;margin-top:2px}
.bagRow{
    display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px
}
.bag{
    border:1px dashed #cfdbe5;border-radius:9px;padding:7px 8px;background:#fff
}
.bag .k{font-size:7px;color:var(--muted);text-transform:uppercase}
.bag .v{font-size:9px;font-weight:900;color:var(--navy);margin-top:2px}
.layover{
    margin:3px 13px 10px;padding:7px 9px;border-radius:9px;background:#fff8e9;
    color:#72571a;font-size:9px;border:1px solid #f0dfb0;text-align:center
}
.passengers{
    border:1px solid var(--line);border-radius:12px;overflow:hidden
}
.passHead,.passRow{
    display:grid;grid-template-columns:1.6fr .7fr .8fr .8fr;gap:8px;padding:8px 10px;align-items:center
}
.passHead{background:var(--soft);font-size:8px;text-transform:uppercase;color:var(--muted);font-weight:800}
.passRow{font-size:9px;border-top:1px solid var(--line)}
.passRow strong{color:var(--navy)}
.extra{
    border:1px solid var(--line);border-radius:12px;padding:10px 12px;background:#fff
}
.extraItem{
    display:flex;justify-content:space-between;gap:14px;padding:7px 0;border-top:1px solid #edf1f4
}
.extraItem:first-child{border-top:0}
.extraItem strong{font-size:10px;color:var(--navy)}
.extraItem small{display:block;color:var(--muted);font-size:8px;margin-top:3px}
.extraEmpty{font-size:9px;color:var(--muted)}
.bottomGrid{
    display:grid;grid-template-columns:1.2fr .8fr;gap:14px;margin-top:16px
}
.notice{
    border:1px solid #d7e6f0;background:#f5fbff;border-radius:12px;padding:11px 12px
}
.notice strong{color:var(--navy);font-size:10px}
.notice p{font-size:8px;color:#657a8d;line-height:1.55;margin:5px 0 0}
.money{
    border:1px solid var(--line);border-radius:12px;padding:10px 12px;background:#fff
}
.moneyLine{display:flex;justify-content:space-between;gap:10px;padding:5px 0;font-size:9px;color:#586f83}
.moneyLine strong{color:var(--ink)}
.moneyTotal{
    border-top:2px solid var(--navy);margin-top:5px;padding-top:8px;
    display:flex;justify-content:space-between;font-size:16px;font-weight:900;color:var(--navy)
}
.verify{
    margin-top:14px;display:grid;grid-template-columns:1fr 120px;gap:10px;align-items:center;
    border-top:1px solid var(--line);padding-top:10px
}
.verifyText{font-size:8px;color:var(--muted);line-height:1.5}
.verifyBox{
    border:1px solid var(--line);border-radius:10px;padding:8px;text-align:center;background:var(--soft)
}
.verifyBox strong{display:block;color:var(--navy);font-size:9px}
.verifyBox span{display:block;color:var(--muted);font-size:7px;margin-top:2px}
.footer{
    margin-top:11px;background:var(--navy);color:#fff;border-radius:10px;padding:9px 11px;
    display:flex;justify-content:space-between;gap:12px;font-size:8px
}
.footer .mutedWhite{opacity:.75}
.alert{
    padding:12px;border:1px solid #f3c4cb;background:#fff2f4;color:#ad2840;
    border-radius:8px;margin-top:18px
}
@media(max-width:860px){
    .page,.tools{width:100%;margin-left:0;margin-right:0}
    .page{min-height:auto}
    .hero{padding-left:24px;padding-right:24px}
    .content{padding-left:20px;padding-right:20px}
    .topMeta{grid-template-columns:1fr 1fr}
    .segmentGrid{grid-template-columns:1fr 38px 1fr}
    .details{grid-template-columns:1fr 1fr}
    .passHead,.passRow{grid-template-columns:1.3fr .7fr .8fr}
    .passHead div:nth-child(4),.passRow div:nth-child(4){display:none}
    .bottomGrid{grid-template-columns:1fr}
}
@media print{
    body{background:#fff}
    .tools{display:none}
    .page{margin:0;box-shadow:none;width:auto;min-height:auto}
    @page{size:A4;margin:0}
}
</style>
</head>
<body>

<div class="tools">
    <button class="back" onclick="window.close()">Close Preview</button>
    <button class="print" onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="page">
    <div class="hero">
        <div class="heroTop">
            <div>
                <div class="brandName">Mustafa Travels & Tours</div>
                <div class="brandTag">Barcelona, Spain · www.mustafatravels.org</div>
            </div>
            <div class="docBox">
                <div class="docLabel">TRAVEL DOCUMENT</div>
                <div class="docTitle">BOOKING PREVIEW</div>
                <div class="ref">Preview Ref: <?=h($previewRef ?: '—')?></div>
            </div>
        </div>

        <div class="routeHero">
            <div>
                <div class="airportCode"><?=h((string)$journeyFrom)?></div>
                <div class="airportName">Journey origin</div>
            </div>
            <div class="routeArrow">
                <div class="line"></div><div class="plane">✈</div><div class="line"></div>
            </div>
            <div style="text-align:right">
                <div class="airportCode"><?=h((string)$journeyTo)?></div>
                <div class="airportName">Final destination</div>
            </div>
        </div>
    </div>
    <div class="goldbar"></div>

    <div class="content">
        <?php if($error): ?>
            <div class="alert"><?=h($error)?></div>
        <?php else: ?>

        <div class="topMeta">
            <div class="metaCard">
                <div class="metaLabel">Airline</div>
                <div class="metaValue"><?=h($ownerName)?></div>
            </div>
            <div class="metaCard">
                <div class="metaLabel">Passengers</div>
                <div class="metaValue"><?=count($checkout['passengers'] ?? [])?></div>
            </div>
            <div class="metaCard">
                <div class="metaLabel">Travel Date</div>
                <div class="metaValue"><?php
                    $d = $firstSegs[0]['departing_at'] ?? null;
                    echo h(bp_date_short($d));
                ?></div>
            </div>
            <div class="metaCard">
                <div class="metaLabel">Document Status</div>
                <div class="metaValue" style="color:var(--success)">PREVIEW / NOT TICKETED</div>
            </div>
        </div>

        <div class="section">
            <div class="sectionTitle">Flight Itinerary</div>

            <?php foreach(($offer['slices'] ?? []) as $sliceIndex => $slice):
                $segments = $slice['segments'] ?? [];
                $sliceFirst = $segments[0] ?? [];
                $sliceLast = $segments ? $segments[count($segments)-1] : [];
                $sliceFrom = (string)($sliceFirst['origin']['iata_code'] ?? '');
                $sliceTo   = (string)($sliceLast['destination']['iata_code'] ?? '');
                $sliceDur  = bp_duration($sliceFirst['departing_at'] ?? null, $sliceLast['arriving_at'] ?? null);
            ?>
            <div class="slice">
                <div class="sliceHead">
                    <strong><?=($sliceIndex===0?'OUTBOUND':'JOURNEY '.($sliceIndex+1))?> · <?=h($sliceFrom)?> → <?=h($sliceTo)?></strong>
                    <span><?=h($sliceDur ?: 'Duration not supplied')?></span>
                </div>

                <?php foreach($segments as $segIndex => $seg):
                    $origin = $seg['origin'] ?? [];
                    $dest   = $seg['destination'] ?? [];
                    $bags   = bp_segment_baggage($seg);
                    $carrierName = bp_carrier($seg,$offer);
                    $flightNo = bp_flight_no($seg);
                    $aircraft = bp_aircraft($seg);
                    $cabin = bp_cabin($seg);
                    $duration = bp_duration($seg['departing_at'] ?? null, $seg['arriving_at'] ?? null);
                ?>
                <div class="segmentCard">
                    <div class="segmentTop">
                        <div class="airline">
                            <div class="logo">
                                <?php if($ownerLogo): ?>
                                    <img src="<?=h($ownerLogo)?>" alt="">
                                <?php else: ?>
                                    <span style="font-size:16px">✈</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div class="airlineName"><?=h($carrierName)?></div>
                                <div class="flightNo"><?=h($flightNo ?: 'Flight number not supplied')?></div>
                            </div>
                        </div>
                        <div style="font-size:8px;color:var(--muted);text-align:right">
                            Segment <?=($segIndex+1)?> of <?=count($segments)?>
                        </div>
                    </div>

                    <div class="segmentGrid">
                        <div class="point">
                            <div class="pointCode"><?=h((string)($origin['iata_code'] ?? ''))?></div>
                            <div class="pointTime"><?=h(bp_time($seg['departing_at'] ?? null))?></div>
                            <div class="pointDate"><?=h(bp_date($seg['departing_at'] ?? null))?></div>
                            <div class="pointName"><?=h(bp_airport_name($origin))?></div>
                        </div>

                        <div class="flightPath">
                            <div class="dur"><?=h($duration ?: '—')?></div>
                            <div class="miniLine"></div>
                        </div>

                        <div class="point right">
                            <div class="pointCode"><?=h((string)($dest['iata_code'] ?? ''))?></div>
                            <div class="pointTime"><?=h(bp_time($seg['arriving_at'] ?? null))?></div>
                            <div class="pointDate"><?=h(bp_date($seg['arriving_at'] ?? null))?></div>
                            <div class="pointName"><?=h(bp_airport_name($dest))?></div>
                        </div>
                    </div>

                    <div class="details">
                        <div class="detail">
                            <div class="k">Cabin</div>
                            <div class="v"><?=h($cabin)?></div>
                        </div>
                        <div class="detail">
                            <div class="k">Aircraft</div>
                            <div class="v"><?=h($aircraft ?: '—')?></div>
                        </div>
                        <div class="detail">
                            <div class="k">Departure Terminal</div>
                            <div class="v"><?=h(bp_terminal($origin))?></div>
                        </div>
                        <div class="detail">
                            <div class="k">Arrival Terminal</div>
                            <div class="v"><?=h(bp_terminal($dest))?></div>
                        </div>
                    </div>

                    <div class="bagRow">
                        <div class="bag">
                            <div class="k">Checked Baggage</div>
                            <div class="v"><?=h($bags['checked'])?></div>
                        </div>
                        <div class="bag">
                            <div class="k">Cabin Baggage</div>
                            <div class="v"><?=h($bags['cabin'])?></div>
                        </div>
                    </div>
                </div>

                <?php if(isset($segments[$segIndex+1])):
                    $next = $segments[$segIndex+1];
                    $lay = bp_layover($seg['arriving_at'] ?? null, $next['departing_at'] ?? null);
                    $layCode = $dest['iata_code'] ?? '';
                ?>
                    <div class="layover">
                        Transit in <?=h((string)$layCode)?><?= $lay ? ' · '.h($lay) : '' ?>
                    </div>
                <?php endif; ?>

                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="section">
            <div class="sectionTitle">Passengers</div>
            <div class="passengers">
                <div class="passHead">
                    <div>Passenger Name</div><div>Type</div><div>Date of Birth</div><div>Passport</div>
                </div>
                <?php foreach(($checkout['passengers'] ?? []) as $p): ?>
                <div class="passRow">
                    <div><strong><?=h(trim(($p['given_name']??'').' '.($p['family_name']??'')))?></strong></div>
                    <div><?=h(ucfirst((string)($p['type']??'passenger')))?></div>
                    <div><?=h((string)($p['born_on']??'—'))?></div>
                    <div><?=!empty($p['passport_number']) ? h(bp_mask((string)$p['passport_number'])) : '—'?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section">
            <div class="sectionTitle">Extra Baggage & Services</div>
            <div class="extra">
                <?php if($services): ?>
                    <?php foreach($services as $s): ?>
                        <div class="extraItem">
                            <div>
                                <strong><?=$s['_qty']?> × Extra checked bag</strong>
                                <small><?=h(bp_names($offer,$s['passenger_ids']??[]))?> · <?=h(bp_route($offer,$s['segment_ids']??[]))?></small>
                            </div>
                            <strong><?=bp_money(((float)($s['total_amount']??0))*$s['_qty'],$s['total_currency']??$currency)?></strong>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="extraEmpty">No extra checked baggage selected for this preview.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bottomGrid">
            <div class="notice">
                <strong>Important booking notice</strong>
                <p>
                    This document is a booking preview / quotation only and is not an issued airline ticket.
                    Fare, seat availability, baggage services, payment deadline and airline rules remain subject
                    to final airline confirmation until the booking/order is created.
                </p>
            </div>

            <div class="money">
                <div class="moneyLine"><span>Flight fare</span><strong><?=bp_money($baseTotal,$currency)?></strong></div>
                <div class="moneyLine"><span>Extra baggage</span><strong><?=bp_money($extraTotal,$currency)?></strong></div>
                <div class="moneyTotal"><span>Total</span><span><?=bp_money($grandTotal,$currency)?></span></div>
            </div>
        </div>

        <div class="verify">
            <div class="verifyText">
                Prepared by Mustafa Travels & Tours. Passenger passport numbers are masked for privacy.
                Airline PNR / e-ticket number will be shown only after a confirmed booking is created.
            </div>
            <div class="verifyBox">
                <strong>PREVIEW REF</strong>
                <span><?=h($previewRef ?: '—')?></span>
            </div>
        </div>

        <div class="footer">
            <div>
                <strong>Mustafa Travels & Tours</strong><br>
                <span class="mutedWhite">Barcelona, Spain · www.mustafatravels.org</span>
            </div>
            <div style="text-align:right">
                <strong>Generated <?=h(date('d M Y · H:i'))?></strong><br>
                <span class="mutedWhite">Please verify flight timings before travel.</span>
            </div>
        </div>

        <?php endif; ?>
    </div>
</div>
</body>
</html>
