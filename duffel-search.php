<?php
require_once __DIR__ . '/partials.php';

$duffelKey = getenv('DUFFEL_API_KEY') ?: '';
$results = [];
$error = '';
$searched = false;

function duffel_request(string $endpoint, string $method = 'GET', ?array $body = null): array
{
    global $duffelKey;

    if ($duffelKey === '') {
        return ['ok'=>false,'status'=>0,'data'=>null,'error'=>'DUFFEL_API_KEY is missing from the Render Environment.'];
    }

    $ch = curl_init('https://api.duffel.com' . $endpoint);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $duffelKey,
            'Accept: application/json',
            'Accept-Encoding: gzip',
            'Content-Type: application/json',
            'Duffel-Version: v2'
        ],
        CURLOPT_ENCODING => '',
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_TIMEOUT => 50,
    ]);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_SLASHES));
        }
    }

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        return ['ok'=>false,'status'=>$status,'data'=>null,'error'=>$curlError ?: 'Unable to connect to Duffel.'];
    }

    $json = json_decode($response, true);

    if ($status < 200 || $status >= 300) {
        $message = 'Duffel API returned HTTP ' . $status;
        if (isset($json['errors'][0]['message'])) $message = $json['errors'][0]['message'];
        elseif (isset($json['errors'][0]['title'])) $message = $json['errors'][0]['title'];

        return ['ok'=>false,'status'=>$status,'data'=>$json,'error'=>$message];
    }

    return ['ok'=>true,'status'=>$status,'data'=>$json,'error'=>''];
}

function mt_valid_date(string $date): bool
{
    if ($date === '') return false;
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function mt_time(?string $datetime): string
{
    if (!$datetime) return '';
    $ts = strtotime($datetime);
    return $ts ? date('H:i', $ts) : $datetime;
}

function mt_date(?string $datetime): string
{
    if (!$datetime) return '';
    $ts = strtotime($datetime);
    return $ts ? date('d M Y', $ts) : $datetime;
}

function mt_duration(?string $duration): string
{
    if (!$duration) return '';
    if (preg_match('/^PT(?:(\d+)H)?(?:(\d+)M)?$/', $duration, $m)) {
        $hours = isset($m[1]) && $m[1] !== '' ? (int)$m[1] : 0;
        $mins = isset($m[2]) && $m[2] !== '' ? (int)$m[2] : 0;
        $parts = [];
        if ($hours > 0) $parts[] = $hours . 'h';
        if ($mins > 0) $parts[] = $mins . 'm';
        return implode(' ', $parts);
    }
    return str_replace('PT', '', $duration);
}

function mt_max_stops(array $offer): int
{
    $max = 0;
    foreach (($offer['slices'] ?? []) as $slice) {
        $stops = max(0, count($slice['segments'] ?? []) - 1);
        if ($stops > $max) $max = $stops;
    }
    return $max;
}

function mt_checked_baggage_quantity(array $offer): int
{
    $best = 0;
    foreach (($offer['slices'] ?? []) as $slice) {
        foreach (($slice['segments'] ?? []) as $segment) {
            foreach (($segment['passengers'] ?? []) as $passenger) {
                foreach (($passenger['baggages'] ?? []) as $bag) {
                    if (($bag['type'] ?? '') === 'checked') {
                        $best = max($best, (int)($bag['quantity'] ?? 0));
                    }
                }
            }
        }
    }
    return $best;
}

function mt_airline_name(array $offer): string
{
    if (!empty($offer['owner']['name'])) return (string)$offer['owner']['name'];

    foreach (($offer['slices'] ?? []) as $slice) {
        foreach (($slice['segments'] ?? []) as $segment) {
            if (!empty($segment['marketing_carrier']['name'])) {
                return (string)$segment['marketing_carrier']['name'];
            }
        }
    }
    return 'Airline';
}

function mt_airline_codes(array $offer): array
{
    $codes = [];
    if (!empty($offer['owner']['iata_code'])) $codes[] = strtoupper((string)$offer['owner']['iata_code']);

    foreach (($offer['slices'] ?? []) as $slice) {
        foreach (($slice['segments'] ?? []) as $segment) {
            if (!empty($segment['marketing_carrier']['iata_code'])) $codes[] = strtoupper((string)$segment['marketing_carrier']['iata_code']);
            if (!empty($segment['operating_carrier']['iata_code'])) $codes[] = strtoupper((string)$segment['operating_carrier']['iata_code']);
        }
    }
    return array_values(array_unique($codes));
}

function mt_matches_airline(array $offer, string $filter): bool
{
    $filter = trim($filter);
    if ($filter === '') return true;

    $needle = strtolower($filter);
    if (str_contains(strtolower(mt_airline_name($offer)), $needle)) return true;

    foreach (mt_airline_codes($offer) as $code) {
        if (strtolower($code) === $needle) return true;
    }
    return false;
}

function mt_hold_info(array $offer): array
{
    $req = $offer['payment_requirements'] ?? [];
    return [
        'eligible' => (($req['requires_instant_payment'] ?? true) === false),
        'payment_required_by' => $req['payment_required_by'] ?? null,
        'price_guarantee_expires_at' => $req['price_guarantee_expires_at'] ?? null
    ];
}

function mt_format_deadline(?string $datetime): string
{
    if (!$datetime) return '';
    $ts = strtotime($datetime);
    return $ts ? date('d M Y H:i', $ts) : $datetime;
}

$origin = strtoupper(trim($_GET['origin'] ?? 'BCN'));
$destination = strtoupper(trim($_GET['destination'] ?? 'LHE'));
$departure = trim($_GET['departure'] ?? '');
$returnDate = trim($_GET['return_date'] ?? '');
$adults = max(1, min(9, (int)($_GET['adults'] ?? 1)));
$children = max(0, min(8, (int)($_GET['children'] ?? 0)));
$infants = max(0, min($adults, (int)($_GET['infants'] ?? 0)));
$cabin = trim($_GET['cabin'] ?? 'economy');
$airlineFilter = trim($_GET['airline'] ?? '');
$baggageFilter = trim($_GET['baggage'] ?? 'any');
$stopsFilter = trim($_GET['stops'] ?? 'any');

$allowedCabins = ['economy','premium_economy','business','first'];
if (!in_array($cabin, $allowedCabins, true)) $cabin = 'economy';

if (isset($_GET['search'])) {
    $searched = true;

    if (!preg_match('/^[A-Z]{3}$/', $origin)) $error = 'Please enter a valid 3-letter origin IATA code.';
    elseif (!preg_match('/^[A-Z]{3}$/', $destination)) $error = 'Please enter a valid 3-letter destination IATA code.';
    elseif ($origin === $destination) $error = 'Origin and destination cannot be the same.';
    elseif (!mt_valid_date($departure)) $error = 'Please select a valid departure date.';
    elseif ($departure < date('Y-m-d')) $error = 'Departure date cannot be in the past.';
    elseif ($returnDate !== '' && !mt_valid_date($returnDate)) $error = 'Please select a valid return date.';
    elseif ($returnDate !== '' && $returnDate < $departure) $error = 'Return date cannot be before departure date.';
    elseif ($infants > $adults) $error = 'Infants cannot exceed the number of adults.';
    else {
        $slices = [[
            'origin' => $origin,
            'destination' => $destination,
            'departure_date' => $departure
        ]];

        if ($returnDate !== '') {
            $slices[] = [
                'origin' => $destination,
                'destination' => $origin,
                'departure_date' => $returnDate
            ];
        }

        $passengers = [];
        for ($i=0;$i<$adults;$i++) $passengers[] = ['age'=>30];
        for ($i=0;$i<$children;$i++) $passengers[] = ['age'=>8];
        for ($i=0;$i<$infants;$i++) $passengers[] = ['age'=>1];

        $payloadData = [
            'slices' => $slices,
            'passengers' => $passengers,
            'cabin_class' => $cabin
        ];

        if (in_array($stopsFilter, ['0','1','2'], true)) {
            $payloadData['max_connections'] = (int)$stopsFilter;
        }

        $api = duffel_request(
            '/air/offer_requests?return_offers=true&supplier_timeout=20000',
            'POST',
            ['data'=>$payloadData]
        );

        if (!$api['ok']) {
            $error = 'Duffel search error: ' . $api['error'];
            error_log('DUFFEL SEARCH V2 ERROR | HTTP '.$api['status'].' | '.json_encode($api['data']));
        } else {
            $results = $api['data']['data']['offers'] ?? [];
            if (!is_array($results)) $results = [];

            $results = array_values(array_filter($results, function($offer) use ($airlineFilter,$baggageFilter,$stopsFilter) {
                if (!mt_matches_airline($offer, $airlineFilter)) return false;

                $checkedQty = mt_checked_baggage_quantity($offer);
                if ($baggageFilter === 'with_checked' && $checkedQty < 1) return false;
                if ($baggageFilter === 'without_checked' && $checkedQty > 0) return false;

                if (in_array($stopsFilter, ['0','1','2'], true) && mt_max_stops($offer) > (int)$stopsFilter) return false;

                return true;
            }));

            usort($results, fn($a,$b) => (float)($a['total_amount'] ?? 9999999) <=> (float)($b['total_amount'] ?? 9999999));
            $results = array_slice($results, 0, 30);
        }
    }
}

site_header('Duffel Flight Search V2');
?>

<style>
.mt-duffel-page{background:#f3f7fb;padding:48px 0 80px;min-height:720px}
.mt-duffel-wrap{width:min(1220px,calc(100% - 34px));margin:auto}
.mt-test-pill{display:inline-flex;padding:7px 12px;border-radius:30px;background:#e6f4ff;color:#0769a8;font-size:11px;font-weight:800;letter-spacing:.7px}
.mt-duffel-title h1{margin:10px 0 6px;font:800 38px Manrope,Arial,sans-serif;color:#10253d}
.mt-duffel-title p{margin:0 0 24px;color:#667b90}
.mt-search-card{background:#fff;border:1px solid #dce6ef;border-radius:18px;padding:22px;box-shadow:0 14px 40px rgba(10,53,94,.08)}
.mt-search-grid{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px}
.mt-field label{display:block;font-size:11px;font-weight:800;color:#5d7288;margin-bottom:6px}
.mt-field input,.mt-field select{width:100%;height:46px;padding:0 11px;border:1px solid #cfdbe6;border-radius:9px;background:#fff;color:#10253d;font:600 13px Inter,Arial,sans-serif;box-sizing:border-box}
.mt-search-actions{display:flex;align-items:end}
.mt-search-button{width:100%;height:46px;border:0;border-radius:9px;background:linear-gradient(135deg,#1195e8,#0c72c8);color:#fff;font-weight:800;cursor:pointer}
.mt-error{margin-top:18px;padding:14px 16px;border:1px solid #fecaca;border-radius:10px;background:#fff1f2;color:#b42332}
.mt-results-title{display:flex;justify-content:space-between;align-items:center;gap:20px;margin:32px 0 14px}
.mt-results-title h2{margin:0}.mt-results-title span{color:#6b7f92;font-size:13px}
.mt-offer{display:grid;grid-template-columns:160px 1fr 210px;gap:22px;align-items:center;background:#fff;border:1px solid #dce6ef;border-radius:15px;padding:20px;margin-bottom:13px;box-shadow:0 6px 20px rgba(10,53,94,.045)}
.mt-airline{font-weight:800;font-size:15px;color:#10253d}.mt-airline-code{margin-top:5px;color:#7890a6;font-size:11px}
.mt-slice{padding:5px 0}.mt-slice+.mt-slice{border-top:1px solid #edf2f7;margin-top:10px;padding-top:13px}
.mt-route{display:flex;align-items:center;gap:12px}.mt-point{min-width:86px}.mt-airport{font-weight:900;font-size:18px;color:#10253d}.mt-clock{font-weight:700;font-size:14px}
.mt-line{flex:1;position:relative;height:1px;background:#c9d6e1}.mt-line:after{content:"✈";position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);background:#fff;padding:0 7px;color:#7790a8}
.mt-arrival{text-align:right}.mt-meta{margin-top:7px;display:flex;gap:8px;flex-wrap:wrap;color:#657b91;font-size:11px}
.mt-badge{display:inline-flex;align-items:center;padding:5px 8px;border-radius:20px;background:#eef4f8;color:#49657d;font-size:10px;font-weight:800}
.mt-badge.bag{background:#e8f8f1;color:#087a55}.mt-badge.no-bag{background:#fff4e5;color:#a45a00}.mt-badge.hold{background:#eef0ff;color:#3949ab}
.mt-price-box{text-align:right}.mt-price-label{color:#70869a;font-size:11px}.mt-price{margin:3px 0 10px;font:800 27px Manrope,Arial,sans-serif;color:#062f5f}
.mt-select{display:inline-flex;justify-content:center;padding:11px 17px;border-radius:9px;background:#062f5f;color:#fff!important;text-decoration:none;font-size:12px;font-weight:800}
.mt-hold-note{margin-top:8px;font-size:10px;color:#667b90}.mt-empty{background:#fff;border:1px solid #dce6ef;border-radius:14px;padding:35px;text-align:center;color:#657b91}
.mt-summary{display:flex;gap:9px;flex-wrap:wrap;margin-top:15px}
@media(max-width:1050px){.mt-search-grid{grid-template-columns:repeat(3,1fr)}.mt-offer{grid-template-columns:1fr}.mt-price-box{text-align:left}}
@media(max-width:700px){.mt-search-grid{grid-template-columns:1fr 1fr}}
@media(max-width:520px){.mt-search-grid{grid-template-columns:1fr}.mt-route{gap:8px}.mt-point{min-width:67px}}
</style>

<section class="mt-duffel-page">
<div class="mt-duffel-wrap">

<div class="mt-duffel-title">
    <span class="mt-test-pill">DUFFEL V2 TEST ENGINE</span>
    <h1>Search Flights Directly</h1>
    <p>Adults, children, infants, cabin, airline, stops, baggage and hold eligibility.</p>
</div>

<div class="mt-search-card">
<form method="get">
<div class="mt-search-grid">

<div class="mt-field"><label>FROM</label><input name="origin" maxlength="3" value="<?=h($origin)?>" placeholder="BCN" required></div>
<div class="mt-field"><label>TO</label><input name="destination" maxlength="3" value="<?=h($destination)?>" placeholder="LHE" required></div>
<div class="mt-field"><label>DEPARTURE</label><input type="date" name="departure" min="<?=date('Y-m-d')?>" value="<?=h($departure)?>" required></div>
<div class="mt-field"><label>RETURN</label><input type="date" name="return_date" min="<?=date('Y-m-d')?>" value="<?=h($returnDate)?>"></div>

<div class="mt-field"><label>CABIN</label><select name="cabin">
<option value="economy" <?=$cabin==='economy'?'selected':''?>>Economy</option>
<option value="premium_economy" <?=$cabin==='premium_economy'?'selected':''?>>Premium Economy</option>
<option value="business" <?=$cabin==='business'?'selected':''?>>Business</option>
<option value="first" <?=$cabin==='first'?'selected':''?>>First</option>
</select></div>

<div class="mt-field"><label>ADULTS</label><select name="adults"><?php for($i=1;$i<=9;$i++): ?><option value="<?=$i?>" <?=$adults===$i?'selected':''?>><?=$i?></option><?php endfor; ?></select></div>
<div class="mt-field"><label>CHILDREN</label><select name="children"><?php for($i=0;$i<=8;$i++): ?><option value="<?=$i?>" <?=$children===$i?'selected':''?>><?=$i?></option><?php endfor; ?></select></div>
<div class="mt-field"><label>INFANTS</label><select name="infants"><?php for($i=0;$i<=9;$i++): ?><option value="<?=$i?>" <?=$infants===$i?'selected':''?>><?=$i?></option><?php endfor; ?></select></div>

<div class="mt-field"><label>AIRLINE FILTER</label><input name="airline" value="<?=h($airlineFilter)?>" placeholder="Any / QR / Qatar"></div>

<div class="mt-field"><label>STOPS</label><select name="stops">
<option value="any" <?=$stopsFilter==='any'?'selected':''?>>Any</option>
<option value="0" <?=$stopsFilter==='0'?'selected':''?>>Direct only</option>
<option value="1" <?=$stopsFilter==='1'?'selected':''?>>Max 1 stop</option>
<option value="2" <?=$stopsFilter==='2'?'selected':''?>>Max 2 stops</option>
</select></div>

<div class="mt-field"><label>CHECKED BAG</label><select name="baggage">
<option value="any" <?=$baggageFilter==='any'?'selected':''?>>Any fare</option>
<option value="with_checked" <?=$baggageFilter==='with_checked'?'selected':''?>>With checked bag</option>
<option value="without_checked" <?=$baggageFilter==='without_checked'?'selected':''?>>Without checked bag</option>
</select></div>

<div class="mt-search-actions"><button class="mt-search-button" name="search" value="1" type="submit">Search Flights</button></div>

</div>
</form>

<div class="mt-summary">
<span class="mt-badge">Adults: <?=$adults?></span>
<span class="mt-badge">Children: <?=$children?></span>
<span class="mt-badge">Infants: <?=$infants?></span>
<span class="mt-badge"><?=h(ucwords(str_replace('_',' ',$cabin)))?></span>
</div>
</div>

<?php if($error): ?><div class="mt-error"><strong>Search failed:</strong> <?=h($error)?></div><?php endif; ?>

<?php if($searched && !$error): ?>
<div class="mt-results-title"><h2>Available Flights</h2><span><?=count($results)?> matching offers shown</span></div>

<?php if(!$results): ?><div class="mt-empty">No matching offers were found. Try removing airline, baggage or stops filters.</div><?php endif; ?>

<?php foreach($results as $offer): ?>
<?php
$owner = mt_airline_name($offer);
$codes = mt_airline_codes($offer);
$currency = $offer['total_currency'] ?? 'EUR';
$amount = $offer['total_amount'] ?? '0';
$offerId = $offer['id'] ?? '';
$checkedQty = mt_checked_baggage_quantity($offer);
$hold = mt_hold_info($offer);
?>
<article class="mt-offer">

<div>
<div class="mt-airline"><?=h($owner)?></div>
<div class="mt-airline-code"><?=h(implode(' / ', $codes))?></div>
<div class="mt-meta">
<?php if($checkedQty > 0): ?><span class="mt-badge bag">✓ <?=h((string)$checkedQty)?> checked bag</span>
<?php else: ?><span class="mt-badge no-bag">No checked bag shown</span><?php endif; ?>
<?php if($hold['eligible']): ?><span class="mt-badge hold">HOLD AVAILABLE</span><?php endif; ?>
</div>
</div>

<div>
<?php foreach(($offer['slices'] ?? []) as $slice): ?>
<?php
$segments = $slice['segments'] ?? [];
$first = $segments[0] ?? [];
$last = $segments ? $segments[count($segments)-1] : [];
$from = $first['origin']['iata_code'] ?? '';
$to = $last['destination']['iata_code'] ?? '';
$departing = $first['departing_at'] ?? '';
$arriving = $last['arriving_at'] ?? '';
$sliceStops = max(0,count($segments)-1);
$duration = $slice['duration'] ?? '';
?>
<div class="mt-slice">
<div class="mt-route">
<div class="mt-point"><div class="mt-airport"><?=h($from)?></div><div class="mt-clock"><?=h(mt_time($departing))?></div></div>
<div class="mt-line"></div>
<div class="mt-point mt-arrival"><div class="mt-airport"><?=h($to)?></div><div class="mt-clock"><?=h(mt_time($arriving))?></div></div>
</div>
<div class="mt-meta">
<span><?=h(mt_date($departing))?></span>
<?php if($duration): ?><span>• <?=h(mt_duration($duration))?></span><?php endif; ?>
<span>• <?php if($sliceStops===0): ?>Direct<?php elseif($sliceStops===1): ?>1 stop<?php else: ?><?=$sliceStops?> stops<?php endif; ?></span>
</div>
</div>
<?php endforeach; ?>
</div>

<div class="mt-price-box">
<div class="mt-price-label">Total fare</div>
<div class="mt-price"><?=h($currency)?> <?=number_format((float)$amount,2)?></div>

<a class="mt-select"
href="https://wa.me/<?=WHATSAPP?>?text=<?=urlencode('Hello Mustafa Travels, I selected this Duffel flight. '.$origin.'-'.$destination.', Fare '.$currency.' '.$amount.', Offer ID '.$offerId)?>"
target="_blank">Select Flight</a>

<?php if($hold['eligible']): ?>
<div class="mt-hold-note">
Hold supported
<?php if($hold['payment_required_by']): ?> · Pay by <?=h(mt_format_deadline($hold['payment_required_by']))?><?php endif; ?>
</div>
<?php endif; ?>
</div>

</article>
<?php endforeach; ?>
<?php endif; ?>

</div>
</section>

<?php site_footer(); ?>
