<?php
require_once __DIR__ . '/partials.php';

/*
 * Mustafa Travels & Tours — Flights V3
 * Test page. Keeps the existing duffel-search.php untouched.
 * Requires DUFFEL_API_KEY in Render Environment.
 */

$duffelKey = getenv('DUFFEL_API_KEY') ?: '';
$results = [];
$error = '';
$searched = isset($_GET['search']);

function v3_api(string $endpoint, array $payload): array {
    global $duffelKey;
    if ($duffelKey === '') return ['ok'=>false,'status'=>0,'data'=>[],'error'=>'DUFFEL_API_KEY is missing on the server.'];

    $ch = curl_init('https://api.duffel.com'.$endpoint);
    curl_setopt_array($ch,[
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_POST=>true,
        CURLOPT_HTTPHEADER=>[
            'Authorization: Bearer '.$duffelKey,
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'Content-Type: application/json',
            'Duffel-Version: v2'
        ],
        CURLOPT_POSTFIELDS=>json_encode($payload,JSON_UNESCAPED_SLASHES),
        CURLOPT_ENCODING=>'',
        CURLOPT_CONNECTTIMEOUT=>15,
        CURLOPT_TIMEOUT=>55
    ]);
    $raw=curl_exec($ch);
    $status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);
    $curlError=curl_error($ch);
    curl_close($ch);

    if($raw===false) return ['ok'=>false,'status'=>$status,'data'=>[],'error'=>$curlError ?: 'Could not connect to Duffel.'];
    $json=json_decode((string)$raw,true);
    if(!is_array($json)) $json=[];

    if($status<200 || $status>=300){
        $msg=$json['errors'][0]['message'] ?? $json['errors'][0]['title'] ?? ('Duffel API returned HTTP '.$status);
        return ['ok'=>false,'status'=>$status,'data'=>$json,'error'=>$msg];
    }
    return ['ok'=>true,'status'=>$status,'data'=>$json,'error'=>''];
}

function v3_valid_date(string $v): bool {
    $d=DateTime::createFromFormat('Y-m-d',$v);
    return $d && $d->format('Y-m-d')===$v;
}
function v3_time(?string $v): string { $t=$v?strtotime($v):false; return $t?date('H:i',$t):''; }
function v3_day(?string $v): string { $t=$v?strtotime($v):false; return $t?date('D, d M',$t):''; }
function v3_minutes(?string $iso): int {
    if(!$iso) return 0;
    try { $x=new DateInterval($iso); return ($x->d*1440)+($x->h*60)+$x->i; } catch(Throwable $e){ return 0; }
}
function v3_duration(?string $iso): string {
    $m=v3_minutes($iso); if(!$m) return '';
    $h=intdiv($m,60); $r=$m%60;
    return ($h?$h.'h ':'').($r?$r.'m':'');
}
function v3_total_duration(array $o): int {
    $m=0; foreach(($o['slices']??[]) as $s) $m+=v3_minutes($s['duration']??null); return $m;
}
function v3_max_stops(array $o): int {
    $m=0; foreach(($o['slices']??[]) as $s) $m=max($m,max(0,count($s['segments']??[])-1)); return $m;
}
function v3_airline(array $o): string {
    if(!empty($o['owner']['name'])) return (string)$o['owner']['name'];
    foreach(($o['slices']??[]) as $s) foreach(($s['segments']??[]) as $g)
        if(!empty($g['marketing_carrier']['name'])) return (string)$g['marketing_carrier']['name'];
    return 'Airline';
}
function v3_codes(array $o): array {
    $a=[];
    if(!empty($o['owner']['iata_code'])) $a[]=strtoupper((string)$o['owner']['iata_code']);
    foreach(($o['slices']??[]) as $s) foreach(($s['segments']??[]) as $g){
        if(!empty($g['marketing_carrier']['iata_code'])) $a[]=strtoupper((string)$g['marketing_carrier']['iata_code']);
        if(!empty($g['operating_carrier']['iata_code'])) $a[]=strtoupper((string)$g['operating_carrier']['iata_code']);
    }
    return array_values(array_unique($a));
}
function v3_operating(array $o): array {
    $a=[];
    foreach(($o['slices']??[]) as $s) foreach(($s['segments']??[]) as $g){
        $n=trim((string)($g['operating_carrier']['name']??''));
        if($n!=='') $a[]=$n;
    }
    return array_values(array_unique($a));
}
function v3_checked_bags(array $o): int {
    $q=0;
    foreach(($o['slices']??[]) as $s) foreach(($s['segments']??[]) as $g)
        foreach(($g['passengers']??[]) as $p) foreach(($p['baggages']??[]) as $b)
            if(($b['type']??'')==='checked') $q=max($q,(int)($b['quantity']??0));
    return $q;
}
function v3_hold(array $o): array {
    $p=$o['payment_requirements']??[];
    return [
        'eligible'=>(($p['requires_instant_payment']??true)===false),
        'pay_by'=>$p['payment_required_by']??null
    ];
}
function v3_deadline(?string $v): string { $t=$v?strtotime($v):false; return $t?date('d M H:i',$t):''; }
function v3_stops(array $s): array {
    $g=$s['segments']??[]; $a=[];
    for($i=0;$i<count($g)-1;$i++){ $c=$g[$i]['destination']['iata_code']??''; if($c) $a[]=$c; }
    return $a;
}
function v3_flight_numbers(array $o): string {
    $a=[];
    foreach(($o['slices']??[]) as $s) foreach(($s['segments']??[]) as $g){
        $c=$g['marketing_carrier']['iata_code']??'';
        $n=$g['marketing_carrier_flight_number']??'';
        if($c||$n) $a[]=trim($c.$n);
    }
    return implode(' ',$a);
}

$tripType=(($_GET['trip_type']??'round')==='oneway')?'oneway':'round';
$origin=strtoupper(trim($_GET['origin']??'BCN'));
$destination=strtoupper(trim($_GET['destination']??'LHE'));
$departure=trim($_GET['departure']??'');
$returnDate=trim($_GET['return_date']??'');
$adults=max(1,min(9,(int)($_GET['adults']??1)));
$children=max(0,min(8,(int)($_GET['children']??0)));
$infants=max(0,min($adults,(int)($_GET['infants']??0)));
$cabin=trim($_GET['cabin']??'economy');
$allowedCabins=['economy','premium_economy','business','first'];
if(!in_array($cabin,$allowedCabins,true)) $cabin='economy';

if($searched){
    if(!preg_match('/^[A-Z]{3}$/',$origin)) $error='Enter a valid 3-letter origin airport code.';
    elseif(!preg_match('/^[A-Z]{3}$/',$destination)) $error='Enter a valid 3-letter destination airport code.';
    elseif($origin===$destination) $error='Origin and destination cannot be the same.';
    elseif(!v3_valid_date($departure)) $error='Choose a valid departure date.';
    elseif($departure<date('Y-m-d')) $error='Departure date cannot be in the past.';
    elseif($tripType==='round' && !v3_valid_date($returnDate)) $error='Choose a valid return date.';
    elseif($tripType==='round' && $returnDate<$departure) $error='Return date cannot be before departure.';
    elseif($infants>$adults) $error='Infants cannot exceed adults.';
    else{
        $slices=[['origin'=>$origin,'destination'=>$destination,'departure_date'=>$departure]];
        if($tripType==='round') $slices[]=['origin'=>$destination,'destination'=>$origin,'departure_date'=>$returnDate];

        $passengers=[];
        for($i=0;$i<$adults;$i++) $passengers[]=['age'=>30];
        for($i=0;$i<$children;$i++) $passengers[]=['age'=>8];
        for($i=0;$i<$infants;$i++) $passengers[]=['age'=>1];

        $api=v3_api('/air/offer_requests?return_offers=true&supplier_timeout=20000',[
            'data'=>[
                'slices'=>$slices,
                'passengers'=>$passengers,
                'cabin_class'=>$cabin
            ]
        ]);

        if(!$api['ok']){
            $error='Duffel search error: '.$api['error'];
            error_log('FLIGHTS V3 | HTTP '.$api['status'].' | '.json_encode($api['data']));
        } else {
            $results=$api['data']['data']['offers']??[];
            if(!is_array($results)) $results=[];
            usort($results,fn($a,$b)=>(float)($a['total_amount']??999999)<=>(float)($b['total_amount']??999999));
            $results=array_slice($results,0,50);
        }
    }
}

$airlines=[];
foreach($results as $o){ $n=v3_airline($o); if($n!=='') $airlines[]=$n; }
$airlines=array_values(array_unique($airlines)); sort($airlines);

site_header('Flight Search');
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
:root{--v3navy:#082f5f;--v3blue:#0f8ee9;--v3ink:#10253d;--v3muted:#708498;--v3line:#dce6ef;--v3bg:#f4f8fb}
.v3-page{background:var(--v3bg);min-height:800px;padding:36px 0 80px}.v3-wrap{width:min(1240px,calc(100% - 34px));margin:auto}
.v3-head{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:18px}.v3-head h1{margin:0;font:800 35px Manrope,Inter,sans-serif;color:var(--v3ink)}.v3-head p{margin:6px 0 0;color:var(--v3muted)}.v3-test{padding:7px 11px;border-radius:30px;background:#e5f4ff;color:#0876ba;font-size:10px;font-weight:900;letter-spacing:.6px}
.v3-search{background:#fff;border:1px solid var(--v3line);border-radius:18px;padding:18px;box-shadow:0 15px 40px rgba(8,47,95,.08)}.v3-tabs{display:flex;gap:8px;margin-bottom:14px}.v3-tab{border:1px solid var(--v3line);background:#fff;border-radius:30px;padding:8px 14px;font-size:11px;font-weight:900;color:#526a80;cursor:pointer}.v3-tab.active{background:var(--v3navy);border-color:var(--v3navy);color:#fff}
.v3-searchrow{display:grid;grid-template-columns:1.15fr 40px 1.15fr 1fr 1fr 1.1fr 145px;gap:9px}.v3-field{position:relative;border:1px solid #cedae5;border-radius:11px;padding:8px 11px;background:#fff;min-width:0}.v3-field label{display:block;font-size:9px;font-weight:900;letter-spacing:.5px;color:#7b8fa2}.v3-field input,.v3-field select{width:100%;height:26px;border:0;outline:0;background:transparent;color:var(--v3ink);font:800 13px Inter,sans-serif}.v3-swap{align-self:center;width:36px;height:36px;border-radius:50%;border:1px solid var(--v3line);background:#fff;color:var(--v3navy);font-weight:900;cursor:pointer}.v3-go{border:0;border-radius:11px;background:linear-gradient(135deg,#1195e8,#0873c7);color:#fff;font-weight:900;cursor:pointer}
.v3-trig{cursor:pointer}.v3-trig strong{display:block;font-size:13px;color:var(--v3ink)}.v3-trig small{font-size:9px;color:#8193a4}.v3-pax{display:none;position:absolute;right:0;top:58px;width:285px;z-index:100;background:#fff;border:1px solid var(--v3line);border-radius:14px;padding:15px;box-shadow:0 18px 50px rgba(8,47,95,.2)}.v3-pax.open{display:block}.v3-paxrow{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #edf2f6}.v3-paxrow strong{font-size:12px}.v3-paxrow small{display:block;font-size:9px;color:#8395a6}.v3-step{display:flex;align-items:center;gap:10px}.v3-step button{width:28px;height:28px;border-radius:50%;border:1px solid #ccd8e3;background:#fff;font-weight:900;color:var(--v3navy);cursor:pointer}.v3-step span{min-width:15px;text-align:center;font-weight:900}.v3-done{width:100%;border:0;border-radius:9px;background:var(--v3navy);color:#fff;padding:10px;margin-top:11px;font-weight:900;cursor:pointer}
.v3-error{margin-top:17px;padding:14px 16px;border-radius:11px;background:#fff0f2;border:1px solid #ffd0d7;color:#b52d43}
.v3-layout{display:grid;grid-template-columns:245px 1fr;gap:18px;margin-top:24px}.v3-side{background:#fff;border:1px solid var(--v3line);border-radius:15px;padding:17px;align-self:start;position:sticky;top:90px}.v3-sidehead{display:flex;justify-content:space-between;align-items:center}.v3-reset{border:0;background:transparent;color:#0c80cf;font-size:10px;font-weight:900;cursor:pointer}.v3-filter{padding:15px 0;border-top:1px solid #edf2f6}.v3-filter h4{margin:0 0 10px;font-size:11px}.v3-opt{display:flex;align-items:center;gap:8px;margin:8px 0;font-size:11px;color:#536b80}.v3-filter select,.v3-filter input[type=text]{width:100%;height:38px;border:1px solid #d5e0e9;border-radius:8px;padding:0 9px;background:#fff;font-size:11px}
.v3-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}.v3-top h2{margin:0;font-size:18px}.v3-top span{font-size:11px;color:#768b9e}.v3-sorts{display:flex;gap:7px;margin-bottom:11px}.v3-sort{border:1px solid var(--v3line);background:#fff;border-radius:9px;padding:9px 13px;font-size:10px;font-weight:900;color:#526b81;cursor:pointer}.v3-sort.active{background:var(--v3navy);border-color:var(--v3navy);color:#fff}
.v3-card{background:#fff;border:1px solid var(--v3line);border-radius:15px;margin-bottom:11px;overflow:hidden;box-shadow:0 5px 20px rgba(8,47,95,.035)}.v3-cardmain{display:grid;grid-template-columns:160px 1fr 190px;gap:18px;align-items:center;padding:18px}.v3-airline{font-size:13px;font-weight:900;color:var(--v3ink)}.v3-oper{font-size:9px;color:#7d91a4;margin-top:4px}.v3-codes{font-size:9px;color:#96a5b4;margin-top:4px}.v3-badges{display:flex;gap:5px;flex-wrap:wrap;margin-top:8px}.v3-badge{font-size:8px;font-weight:900;border-radius:20px;padding:5px 7px;background:#edf3f7;color:#577087}.v3-badge.bag{background:#e7f8f0;color:#087b56}.v3-badge.hold{background:#eceeff;color:#4052b5}
.v3-slice+.v3-slice{border-top:1px solid #edf2f6;margin-top:11px;padding-top:12px}.v3-route{display:grid;grid-template-columns:92px 1fr 92px;gap:10px;align-items:center}.v3-point strong{display:block;font-size:17px;color:var(--v3ink)}.v3-point span{font-size:11px;font-weight:900}.v3-point small{display:block;font-size:8px;color:#8295a6}.v3-point.end{text-align:right}.v3-line{height:1px;background:#cbd8e3;position:relative}.v3-line:after{content:"✈";position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);background:#fff;padding:0 7px;color:#8094a8}.v3-meta{text-align:center;margin-top:5px;color:#71869a;font-size:9px}.v3-stop{color:#a96400;font-weight:900}
.v3-price{text-align:right}.v3-price small{font-size:9px;color:#8092a3}.v3-price strong{display:block;font:900 24px Manrope,Inter,sans-serif;color:var(--v3navy);margin:2px 0 9px}.v3-select{display:inline-flex;justify-content:center;min-width:118px;padding:10px 14px;border-radius:9px;background:#071f3e;color:#fff!important;text-decoration:none;font-size:10px;font-weight:900}.v3-exp{font-size:8px;color:#8999a8;margin-top:7px}.v3-details{border-top:1px solid #edf2f6;background:#fbfdff;padding:10px 18px}.v3-details summary{font-size:10px;font-weight:900;color:#4e687e;cursor:pointer}.v3-seg{display:flex;justify-content:space-between;gap:10px;padding:7px 0;border-top:1px dashed #e2eaf1;font-size:9px;color:#5d7489}.v3-empty{background:#fff;border:1px solid var(--v3line);border-radius:14px;padding:35px;text-align:center;color:#6f8497}
.flatpickr-calendar{font-family:Inter,sans-serif!important;border-radius:14px!important;box-shadow:0 18px 50px rgba(8,47,95,.2)!important}.flatpickr-day.selected{background:#0f82d3!important;border-color:#0f82d3!important}
@media(max-width:1100px){.v3-searchrow{grid-template-columns:1fr 40px 1fr 1fr 1fr}.v3-go{height:48px}.v3-cardmain{grid-template-columns:140px 1fr 160px}}
@media(max-width:850px){.v3-layout{grid-template-columns:1fr}.v3-side{position:static}.v3-cardmain{grid-template-columns:1fr}.v3-price{text-align:left}}
@media(max-width:650px){.v3-searchrow{grid-template-columns:1fr}.v3-swap{display:none}.v3-head{flex-direction:column;align-items:start}.v3-route{grid-template-columns:70px 1fr 70px}}
</style>

<section class="v3-page"><div class="v3-wrap">
<div class="v3-head"><div><h1>Find your next flight</h1><p>Search live airline offers directly with Mustafa Travels.</p></div><span class="v3-test">V3 TEST ENGINE</span></div>

<div class="v3-search">
<form method="get" id="v3Form">
<input type="hidden" name="trip_type" id="tripType" value="<?=h($tripType)?>">
<input type="hidden" name="adults" id="adultInput" value="<?=$adults?>">
<input type="hidden" name="children" id="childInput" value="<?=$children?>">
<input type="hidden" name="infants" id="infantInput" value="<?=$infants?>">

<div class="v3-tabs">
<button type="button" class="v3-tab <?=$tripType==='round'?'active':''?>" data-trip="round">Round trip</button>
<button type="button" class="v3-tab <?=$tripType==='oneway'?'active':''?>" data-trip="oneway">One way</button>
</div>

<div class="v3-searchrow">
<div class="v3-field"><label>FROM</label><input name="origin" id="origin" maxlength="3" value="<?=h($origin)?>" placeholder="BCN" required></div>
<button type="button" class="v3-swap" id="swap">⇄</button>
<div class="v3-field"><label>TO</label><input name="destination" id="destination" maxlength="3" value="<?=h($destination)?>" placeholder="LHE" required></div>
<div class="v3-field"><label>DEPARTURE</label><input type="text" name="departure" id="depart" value="<?=h($departure)?>" placeholder="Select date" required autocomplete="off"></div>
<div class="v3-field" id="returnBox"><label>RETURN</label><input type="text" name="return_date" id="returnDate" value="<?=h($returnDate)?>" placeholder="Select date" autocomplete="off"></div>

<div class="v3-field v3-trig" id="paxTrigger">
<label>TRAVELLERS & CABIN</label>
<strong id="travText"><?=$adults+$children+$infants?> traveller<?=$adults+$children+$infants===1?'':'s'?></strong>
<small><?=h(ucwords(str_replace('_',' ',$cabin)))?></small>
<div class="v3-pax" id="paxPop">
<div class="v3-paxrow"><div><strong>Adults</strong><small>12+ years</small></div><div class="v3-step"><button type="button" data-pax="adult" data-d="-1">−</button><span id="adultCount"><?=$adults?></span><button type="button" data-pax="adult" data-d="1">+</button></div></div>
<div class="v3-paxrow"><div><strong>Children</strong><small>2–11 years</small></div><div class="v3-step"><button type="button" data-pax="child" data-d="-1">−</button><span id="childCount"><?=$children?></span><button type="button" data-pax="child" data-d="1">+</button></div></div>
<div class="v3-paxrow"><div><strong>Infants</strong><small>Under 2</small></div><div class="v3-step"><button type="button" data-pax="infant" data-d="-1">−</button><span id="infantCount"><?=$infants?></span><button type="button" data-pax="infant" data-d="1">+</button></div></div>
<div class="v3-paxrow"><div><strong>Cabin</strong></div><select name="cabin">
<option value="economy" <?=$cabin==='economy'?'selected':''?>>Economy</option>
<option value="premium_economy" <?=$cabin==='premium_economy'?'selected':''?>>Premium Economy</option>
<option value="business" <?=$cabin==='business'?'selected':''?>>Business</option>
<option value="first" <?=$cabin==='first'?'selected':''?>>First</option>
</select></div>
<button type="button" class="v3-done" id="paxDone">Done</button>
</div>
</div>
<button class="v3-go" type="submit" name="search" value="1">Search flights</button>
</div>
</form>
</div>

<?php if($error): ?><div class="v3-error"><strong>Search failed:</strong> <?=h($error)?></div><?php endif; ?>

<?php if($searched && !$error): ?>
<div class="v3-layout">
<aside class="v3-side">
<div class="v3-sidehead"><strong>Filters</strong><button class="v3-reset" id="resetFilters">Reset</button></div>
<div class="v3-filter"><h4>Stops</h4>
<label class="v3-opt"><input type="radio" name="stopFilter" value="any" checked> Any stops</label>
<label class="v3-opt"><input type="radio" name="stopFilter" value="0"> Direct only</label>
<label class="v3-opt"><input type="radio" name="stopFilter" value="1"> Max 1 stop</label>
<label class="v3-opt"><input type="radio" name="stopFilter" value="2"> Max 2 stops</label>
</div>
<div class="v3-filter"><h4>Airlines</h4><select id="airlineFilter"><option value="">All airlines</option><?php foreach($airlines as $a): ?><option value="<?=h(strtolower($a))?>"><?=h($a)?></option><?php endforeach; ?></select></div>
<div class="v3-filter"><h4>Baggage & booking</h4><label class="v3-opt"><input type="checkbox" id="bagFilter"> Checked bag included</label><label class="v3-opt"><input type="checkbox" id="holdFilter"> Hold available</label></div>
<div class="v3-filter"><h4>Flight / airline</h4><input type="text" id="textFilter" placeholder="QR, TK1854, Qatar..."></div>
</aside>

<main>
<div class="v3-top"><h2><?=h($origin)?> → <?=h($destination)?></h2><span><b id="visibleCount"><?=count($results)?></b> of <?=count($results)?> offers</span></div>
<div class="v3-sorts"><button class="v3-sort active" data-sort="best">Best</button><button class="v3-sort" data-sort="price">Cheapest</button><button class="v3-sort" data-sort="duration">Fastest</button></div>
<div id="cards">

<?php if(!$results): ?><div class="v3-empty">No offers returned. Try another route or date.</div><?php endif; ?>

<?php foreach($results as $o):
$airline=v3_airline($o); $oper=v3_operating($o); $codes=v3_codes($o);
$currency=$o['total_currency']??'EUR'; $amount=(float)($o['total_amount']??0);
$offerId=$o['id']??''; $dur=v3_total_duration($o); $stops=v3_max_stops($o);
$bags=v3_checked_bags($o); $hold=v3_hold($o); $expires=$o['expires_at']??null;
$searchText=strtolower($airline.' '.implode(' ',$oper).' '.implode(' ',$codes).' '.v3_flight_numbers($o));
$best=$amount+($dur*.10)+($stops*35);
?>
<article class="v3-card" data-price="<?=$amount?>" data-duration="<?=$dur?>" data-stops="<?=$stops?>" data-airline="<?=h(strtolower($airline))?>" data-bag="<?=$bags>0?'1':'0'?>" data-hold="<?=$hold['eligible']?'1':'0'?>" data-search="<?=h($searchText)?>" data-best="<?=$best?>">
<div class="v3-cardmain">
<div><div class="v3-airline"><?=h($airline)?></div><?php if($oper): ?><div class="v3-oper">Operated by <?=h(implode(', ',$oper))?></div><?php endif; ?><div class="v3-codes"><?=h(implode(' / ',$codes))?></div><div class="v3-badges"><?php if($bags>0): ?><span class="v3-badge bag">✓ <?=$bags?> checked bag</span><?php else: ?><span class="v3-badge">No checked bag shown</span><?php endif; ?><?php if($hold['eligible']): ?><span class="v3-badge hold">Hold available</span><?php endif; ?></div></div>

<div>
<?php foreach(($o['slices']??[]) as $s):
$g=$s['segments']??[]; $first=$g[0]??[]; $last=$g?$g[count($g)-1]:[];
$from=$first['origin']['iata_code']??''; $to=$last['destination']['iata_code']??'';
$dep=$first['departing_at']??''; $arr=$last['arriving_at']??''; $sc=max(0,count($g)-1); $sa=v3_stops($s);
?>
<div class="v3-slice"><div class="v3-route">
<div class="v3-point"><strong><?=h(v3_time($dep))?></strong><span><?=h($from)?></span><small><?=h(v3_day($dep))?></small></div>
<div><div class="v3-line"></div><div class="v3-meta"><?=h(v3_duration($s['duration']??null))?> · <?=$sc===0?'Direct':($sc===1?'1 stop':$sc.' stops')?><?php if($sa): ?> · <span class="v3-stop"><?=h(implode(', ',$sa))?></span><?php endif; ?></div></div>
<div class="v3-point end"><strong><?=h(v3_time($arr))?></strong><span><?=h($to)?></span><small><?=h(v3_day($arr))?></small></div>
</div></div>
<?php endforeach; ?>
</div>

<div class="v3-price"><small>Total fare</small><strong><?=h($currency)?> <?=number_format($amount,2)?></strong>
<a class="v3-select" href="flight-checkout.php?offer_id=<?=urlencode($offerId)?>">Select</a>
<?php if($hold['eligible'] && $hold['pay_by']): ?><div class="v3-exp">Hold pay-by: <?=h(v3_deadline($hold['pay_by']))?></div><?php elseif($expires): ?><div class="v3-exp">Offer expires: <?=h(v3_deadline($expires))?></div><?php endif; ?>
</div>
</div>

<details class="v3-details"><summary>Flight details & operating carriers</summary>
<?php foreach(($o['slices']??[]) as $s) foreach(($s['segments']??[]) as $g): ?>
<div class="v3-seg"><span><b><?=h(($g['origin']['iata_code']??'').' → '.($g['destination']['iata_code']??''))?></b> · <?=h(($g['marketing_carrier']['iata_code']??'').($g['marketing_carrier_flight_number']??''))?></span><span>Operating carrier: <b><?=h($g['operating_carrier']['name']??'Airline')?></b></span></div>
<?php endforeach; ?>
</details>
</article>
<?php endforeach; ?>
</div>
</main>
</div>
<?php endif; ?>
</div></section>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded',()=>{
const trip=document.getElementById('tripType'), rb=document.getElementById('returnBox'), rf=document.getElementById('returnDate');
function setTrip(t){trip.value=t;document.querySelectorAll('.v3-tab').forEach(b=>b.classList.toggle('active',b.dataset.trip===t));if(t==='oneway'){rb.style.display='none';rf.removeAttribute('required');rf.value=''}else{rb.style.display='';rf.setAttribute('required','required')}}
document.querySelectorAll('.v3-tab').forEach(b=>b.onclick=()=>setTrip(b.dataset.trip));setTrip(trip.value);

let ret=flatpickr('#returnDate',{dateFormat:'Y-m-d',minDate:'today',altInput:true,altFormat:'D, d M Y',disableMobile:true});
flatpickr('#depart',{dateFormat:'Y-m-d',minDate:'today',altInput:true,altFormat:'D, d M Y',disableMobile:true,onChange:(d,s)=>{if(s)ret.set('minDate',s)}});

document.getElementById('swap').onclick=()=>{let a=document.getElementById('origin'),b=document.getElementById('destination'),x=a.value;a.value=b.value;b.value=x};

const trigger=document.getElementById('paxTrigger'),pop=document.getElementById('paxPop');
trigger.onclick=e=>{if(!e.target.closest('.v3-step')&&!e.target.closest('select')&&e.target.id!=='paxDone')pop.classList.add('open')};
document.getElementById('paxDone').onclick=e=>{e.stopPropagation();pop.classList.remove('open')};
document.addEventListener('click',e=>{if(!trigger.contains(e.target))pop.classList.remove('open')});

let state={adult:+document.getElementById('adultInput').value,child:+document.getElementById('childInput').value,infant:+document.getElementById('infantInput').value};
function sync(){state.adult=Math.max(1,Math.min(9,state.adult));state.child=Math.max(0,Math.min(8,state.child));state.infant=Math.max(0,Math.min(state.adult,state.infant));adultInput.value=state.adult;childInput.value=state.child;infantInput.value=state.infant;adultCount.textContent=state.adult;childCount.textContent=state.child;infantCount.textContent=state.infant;let n=state.adult+state.child+state.infant;travText.textContent=n+(n===1?' traveller':' travellers')}
document.querySelectorAll('[data-pax]').forEach(b=>b.onclick=e=>{e.stopPropagation();state[b.dataset.pax]+=+b.dataset.d;sync()});sync();

const wrap=document.getElementById('cards');if(!wrap)return;
const cards=[...wrap.querySelectorAll('.v3-card')], count=document.getElementById('visibleCount');
function filter(){let st=document.querySelector('input[name="stopFilter"]:checked')?.value||'any',al=airlineFilter.value,bag=bagFilter.checked,hold=holdFilter.checked,txt=textFilter.value.trim().toLowerCase(),n=0;cards.forEach(c=>{let ok=true,s=+c.dataset.stops;if(st!=='any'&&s>+st)ok=false;if(al&&c.dataset.airline!==al)ok=false;if(bag&&c.dataset.bag!=='1')ok=false;if(hold&&c.dataset.hold!=='1')ok=false;if(txt&&!c.dataset.search.includes(txt))ok=false;c.style.display=ok?'':'none';if(ok)n++});count.textContent=n}
document.querySelectorAll('input[name="stopFilter"]').forEach(x=>x.onchange=filter);airlineFilter.onchange=filter;bagFilter.onchange=filter;holdFilter.onchange=filter;textFilter.oninput=filter;
resetFilters.onclick=()=>{document.querySelector('input[name="stopFilter"][value="any"]').checked=true;airlineFilter.value='';bagFilter.checked=false;holdFilter.checked=false;textFilter.value='';filter()};
function sort(t){[...cards].sort((a,b)=>+(a.dataset[t==='price'?'price':t==='duration'?'duration':'best'])-+(b.dataset[t==='price'?'price':t==='duration'?'duration':'best'])).forEach(c=>wrap.appendChild(c));document.querySelectorAll('.v3-sort').forEach(b=>b.classList.toggle('active',b.dataset.sort===t))}
document.querySelectorAll('.v3-sort').forEach(b=>b.onclick=()=>sort(b.dataset.sort));sort('best');
});
</script>
<?php site_footer(); ?>

