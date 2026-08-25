<?php
require_once __DIR__ . '/partials.php';

/*
|--------------------------------------------------------------------------
| DUFFEL TEST FLIGHT SEARCH
|--------------------------------------------------------------------------
| API key Render Environment Variable se li jayegi:
| DUFFEL_API_KEY
|--------------------------------------------------------------------------
*/

$duffelKey = getenv('DUFFEL_API_KEY') ?: '';

$results = [];
$error   = '';
$searched = false;


/*
|--------------------------------------------------------------------------
| DUFFEL API REQUEST
|--------------------------------------------------------------------------
*/

function duffel_request(string $endpoint, string $method = 'GET', ?array $body = null): array
{
    global $duffelKey;

    if ($duffelKey === '') {
        return [
            'ok' => false,
            'status' => 0,
            'data' => null,
            'error' => 'DUFFEL_API_KEY is missing from server environment.'
        ];
    }

    $url = 'https://api.duffel.com' . $endpoint;

    $ch = curl_init($url);

    $headers = [
        'Authorization: Bearer ' . $duffelKey,
        'Accept: application/json',
        'Content-Type: application/json',
        'Duffel-Version: v2'
    ];

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_CONNECTTIMEOUT => 15,
    ]);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);

        if ($body !== null) {
            curl_setopt(
                $ch,
                CURLOPT_POSTFIELDS,
                json_encode($body, JSON_UNESCAPED_SLASHES)
            );
        }
    }

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($response === false) {
        return [
            'ok' => false,
            'status' => $status,
            'data' => null,
            'error' => $curlError ?: 'Duffel connection failed.'
        ];
    }

    $json = json_decode($response, true);

    if ($status < 200 || $status >= 300) {

        $message = '';

        if (isset($json['errors'][0]['message'])) {
            $message = $json['errors'][0]['message'];
        } elseif (isset($json['errors'][0]['title'])) {
            $message = $json['errors'][0]['title'];
        } else {
            $message = 'Duffel API returned HTTP ' . $status;
        }

        return [
            'ok' => false,
            'status' => $status,
            'data' => $json,
            'error' => $message
        ];
    }

    return [
        'ok' => true,
        'status' => $status,
        'data' => $json,
        'error' => ''
    ];
}


/*
|--------------------------------------------------------------------------
| FORM VALUES
|--------------------------------------------------------------------------
*/

$origin      = strtoupper(trim($_GET['origin'] ?? 'BCN'));
$destination = strtoupper(trim($_GET['destination'] ?? 'LHE'));
$departure   = trim($_GET['departure'] ?? '');
$returnDate  = trim($_GET['return_date'] ?? '');
$adults      = max(1, min(9, (int)($_GET['adults'] ?? 1)));
$cabin       = trim($_GET['cabin'] ?? 'economy');


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

if (isset($_GET['search'])) {

    $searched = true;

    if (!preg_match('/^[A-Z]{3}$/', $origin)) {
        $error = 'Please enter a valid 3-letter origin airport code.';
    }

    elseif (!preg_match('/^[A-Z]{3}$/', $destination)) {
        $error = 'Please enter a valid 3-letter destination airport code.';
    }

    elseif ($origin === $destination) {
        $error = 'Origin and destination cannot be the same.';
    }

    elseif ($departure === '') {
        $error = 'Please select a departure date.';
    }

    elseif ($departure < date('Y-m-d')) {
        $error = 'Departure date cannot be in the past.';
    }

    elseif ($returnDate !== '' && $returnDate < $departure) {
        $error = 'Return date cannot be before departure date.';
    }

    else {

        $slices = [
            [
                'origin' => $origin,
                'destination' => $destination,
                'departure_date' => $departure
            ]
        ];

        if ($returnDate !== '') {
            $slices[] = [
                'origin' => $destination,
                'destination' => $origin,
                'departure_date' => $returnDate
            ];
        }

        $passengers = [];

        for ($i = 0; $i < $adults; $i++) {
            $passengers[] = [
                'type' => 'adult'
            ];
        }

        $payload = [
    'data' => [
        'slices' => $slices,
        'passengers' => $passengers,
        'cabin_class' => $cabin
    ]

        ];

        $api = duffel_request(
            '/air/offer_requests?return_offers=true&supplier_timeout=20000',
            'POST',
            $payload
        );

        if (!$api['ok']) {

            $error = 'Duffel search error: ' . $api['error'];

            error_log(
                'DUFFEL SEARCH ERROR | HTTP ' .
                $api['status'] .
                ' | ' .
                json_encode($api['data'])
            );

        } else {

            $results = $api['data']['data']['offers'] ?? [];

            /*
             * Cheapest first
             */
            usort($results, function ($a, $b) {

                $priceA = (float)($a['total_amount'] ?? 0);
                $priceB = (float)($b['total_amount'] ?? 0);

                return $priceA <=> $priceB;
            });

            /*
             * Test page: first 20 offers only
             */
            $results = array_slice($results, 0, 20);
        }
    }
}


/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

function flight_time(?string $datetime): string
{
    if (!$datetime) return '';

    $timestamp = strtotime($datetime);

    return $timestamp ? date('H:i', $timestamp) : $datetime;
}

function flight_date(?string $datetime): string
{
    if (!$datetime) return '';

    $timestamp = strtotime($datetime);

    return $timestamp ? date('d M Y', $timestamp) : $datetime;
}

function duration_text(?string $duration): string
{
    if (!$duration) return '';

    $duration = str_replace('PT', '', $duration);

    return strtolower($duration);
}


site_header('Duffel Flight Search');
?>

<style>

.duffel-page{
    background:#f4f7fb;
    padding:55px 0 80px;
    min-height:700px;
}

.duffel-container{
    width:min(1180px,92%);
    margin:auto;
}

.duffel-heading{
    margin-bottom:25px;
}

.duffel-heading h1{
    margin:5px 0 8px;
    font-size:38px;
}

.duffel-heading p{
    color:#64748b;
    margin:0;
}

.duffel-search-box{
    background:white;
    border:1px solid #dbe4ef;
    border-radius:18px;
    padding:25px;
    box-shadow:0 10px 35px rgba(15,23,42,.06);
}

.duffel-form{
    display:grid;
    grid-template-columns:1fr 1fr 1fr 1fr .75fr 1fr;
    gap:12px;
    align-items:end;
}

.duffel-field label{
    display:block;
    font-size:13px;
    font-weight:700;
    margin-bottom:7px;
    color:#334155;
}

.duffel-field input,
.duffel-field select{
    width:100%;
    height:50px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    padding:0 13px;
    font-size:15px;
    background:#fff;
    box-sizing:border-box;
}

.duffel-search-btn{
    height:50px;
    border:0;
    border-radius:10px;
    background:#0b6edc;
    color:#fff;
    font-weight:800;
    font-size:15px;
    cursor:pointer;
    padding:0 18px;
}

.duffel-search-btn:hover{
    background:#075bb8;
}

.duffel-error{
    margin-top:20px;
    background:#fff1f2;
    border:1px solid #fecdd3;
    color:#be123c;
    padding:15px 18px;
    border-radius:10px;
}

.results-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:35px 0 18px;
}

.results-head h2{
    margin:0;
}

.results-head span{
    color:#64748b;
}

.flight-result{
    background:#fff;
    border:1px solid #dbe4ef;
    border-radius:16px;
    padding:22px;
    margin-bottom:15px;
    display:grid;
    grid-template-columns:180px 1fr 180px;
    gap:25px;
    align-items:center;
    box-shadow:0 5px 20px rgba(15,23,42,.04);
}

.airline-name{
    font-weight:800;
    font-size:16px;
}

.airline-small{
    color:#64748b;
    font-size:12px;
    margin-top:5px;
}

.slice{
    padding:7px 0;
}

.slice + .slice{
    border-top:1px solid #eef2f7;
    margin-top:8px;
    padding-top:14px;
}

.route-row{
    display:flex;
    align-items:center;
    gap:15px;
}

.airport{
    font-size:18px;
    font-weight:800;
}

.time{
    font-size:16px;
    font-weight:700;
}

.route-line{
    flex:1;
    height:1px;
    background:#cbd5e1;
    position:relative;
}

.route-line:after{
    content:"✈";
    position:absolute;
    left:50%;
    top:50%;
    transform:translate(-50%,-50%);
    background:#fff;
    padding:0 8px;
    color:#64748b;
}

.slice-info{
    color:#64748b;
    font-size:12px;
    margin-top:7px;
}

.price-box{
    text-align:right;
}

.price-label{
    font-size:12px;
    color:#64748b;
}

.price{
    font-size:28px;
    font-weight:800;
    margin:3px 0 12px;
    color:#0f172a;
}

.select-btn{
    display:inline-block;
    background:#071f3f;
    color:#fff !important;
    padding:11px 18px;
    border-radius:9px;
    text-decoration:none;
    font-weight:700;
    font-size:13px;
}

.no-results{
    background:#fff;
    border:1px solid #dbe4ef;
    padding:30px;
    border-radius:15px;
    text-align:center;
    color:#64748b;
}

.test-badge{
    display:inline-block;
    background:#e0f2fe;
    color:#0369a1;
    padding:6px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:800;
}

@media(max-width:950px){

    .duffel-form{
        grid-template-columns:1fr 1fr;
    }

    .flight-result{
        grid-template-columns:1fr;
    }

    .price-box{
        text-align:left;
    }
}

@media(max-width:600px){

    .duffel-form{
        grid-template-columns:1fr;
    }

    .duffel-heading h1{
        font-size:30px;
    }

}

</style>


<section class="duffel-page">

<div class="duffel-container">

    <div class="duffel-heading">

        <span class="test-badge">DUFFEL TEST SEARCH</span>

        <h1>Search Live Flights</h1>

        <p>
            Test live flight availability directly from the Duffel API.
        </p>

    </div>


    <div class="duffel-search-box">

        <form method="get" class="duffel-form">

            <div class="duffel-field">

                <label>From</label>

                <input
                    type="text"
                    name="origin"
                    maxlength="3"
                    placeholder="BCN"
                    value="<?=h($origin)?>"
                    required
                >

            </div>


            <div class="duffel-field">

                <label>To</label>

                <input
                    type="text"
                    name="destination"
                    maxlength="3"
                    placeholder="LHE"
                    value="<?=h($destination)?>"
                    required
                >

            </div>


            <div class="duffel-field">

                <label>Departure</label>

                <input
                    type="date"
                    name="departure"
                    min="<?=date('Y-m-d')?>"
                    value="<?=h($departure)?>"
                    required
                >

            </div>


            <div class="duffel-field">

                <label>Return</label>

                <input
                    type="date"
                    name="return_date"
                    min="<?=date('Y-m-d')?>"
                    value="<?=h($returnDate)?>"
                >

            </div>


            <div class="duffel-field">

                <label>Adults</label>

                <select name="adults">

                    <?php for($i=1;$i<=9;$i++): ?>

                        <option
                            value="<?=$i?>"
                            <?=$adults === $i ? 'selected' : ''?>
                        >
                            <?=$i?>
                        </option>

                    <?php endfor; ?>

                </select>

            </div>


            <div>

                <button
                    class="duffel-search-btn"
                    type="submit"
                    name="search"
                    value="1"
                >
                    Search Flights
                </button>

            </div>

        </form>

    </div>


    <?php if($error): ?>

        <div class="duffel-error">
            <strong>Search failed:</strong>
            <?=h($error)?>
        </div>

    <?php endif; ?>


    <?php if($searched && !$error): ?>

        <div class="results-head">

            <h2>Available Flights</h2>

            <span>
                <?=count($results)?> results shown
            </span>

        </div>


        <?php if(!$results): ?>

            <div class="no-results">

                No flights were returned for this search.

            </div>

        <?php endif; ?>


        <?php foreach($results as $offer): ?>

            <?php

            $owner =
                $offer['owner']['name']
                ?? 'Airline';

            $currency =
                $offer['total_currency']
                ?? 'EUR';

            $amount =
                $offer['total_amount']
                ?? '0';

            $slices =
                $offer['slices']
                ?? [];

            $offerId =
                $offer['id']
                ?? '';

            ?>


            <article class="flight-result">


                <div>

                    <div class="airline-name">
                        <?=h($owner)?>
                    </div>

                    <div class="airline-small">
                        Duffel Live Offer
                    </div>

                </div>


                <div>


                <?php foreach($slices as $slice): ?>

                    <?php

                    $segments =
                        $slice['segments']
                        ?? [];

                    $first =
                        $segments[0]
                        ?? [];

                    $last =
                        $segments
                        ? $segments[count($segments)-1]
                        : [];

                    $from =
                        $first['origin']['iata_code']
                        ?? '';

                    $to =
                        $last['destination']['iata_code']
                        ?? '';

                    $departTime =
                        $first['departing_at']
                        ?? '';

                    $arrivalTime =
                        $last['arriving_at']
                        ?? '';

                    $stops =
                        max(0,count($segments)-1);

                    ?>


                    <div class="slice">


                        <div class="route-row">


                            <div>

                                <div class="airport">
                                    <?=h($from)?>
                                </div>

                                <div class="time">
                                    <?=h(flight_time($departTime))?>
                                </div>

                            </div>


                            <div class="route-line"></div>


                            <div>

                                <div class="airport">
                                    <?=h($to)?>
                                </div>

                                <div class="time">
                                    <?=h(flight_time($arrivalTime))?>
                                </div>

                            </div>


                        </div>


                        <div class="slice-info">

                            <?=h(flight_date($departTime))?>

                            ·

                            <?php if($stops === 0): ?>

                                Direct flight

                            <?php elseif($stops === 1): ?>

                                1 stop

                            <?php else: ?>

                                <?=$stops?> stops

                            <?php endif; ?>

                        </div>


                    </div>


                <?php endforeach; ?>


                </div>


                <div class="price-box">

                    <div class="price-label">
                        Total price
                    </div>

                    <div class="price">

                        <?=h($currency)?>

                        <?=number_format((float)$amount,2)?>

                    </div>


                    <a
                        class="select-btn"
                        href="https://wa.me/<?=WHATSAPP?>?text=<?=urlencode(
                            'Hello Mustafa Travels, I found a flight on your website. Route: ' .
                            $origin . '-' .
                            $destination .
                            ', Price: ' .
                            $currency . ' ' .
                            $amount .
                            ', Duffel Offer ID: ' .
                            $offerId
                        )?>"
                        target="_blank"
                    >
                        Select Flight
                    </a>

                </div>


            </article>


        <?php endforeach; ?>


    <?php endif; ?>


</div>

</section>


<?php site_footer(); ?>
