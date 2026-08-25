<?php
require_once __DIR__ . '/partials.php';

$results = [];
$error   = '';

function duffel_api_request(string $endpoint, array $payload): array
{
    $apiKey = getenv('DUFFEL_API_KEY');

    if (!$apiKey) {
        return [
            'ok' => false,
            'error' => 'DUFFEL_API_KEY is not configured.'
        ];
    }

    $ch = curl_init('https://api.duffel.com' . $endpoint);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $apiKey,
            'Duffel-Version: v2',
            'Accept: application/json',
            'Content-Type: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 45
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);

    curl_close($ch);

    if ($curlError) {
        return [
            'ok' => false,
            'error' => $curlError
        ];
    }

    $data = json_decode($response, true);

    if ($httpCode < 200 || $httpCode >= 300) {

        $message = $data['errors'][0]['message']
            ?? $data['errors'][0]['title']
            ?? 'Duffel API request failed.';

        return [
            'ok' => false,
            'error' => $message,
            'raw' => $data
        ];
    }

    return [
        'ok' => true,
        'data' => $data
    ];
}


/* ---------------------------------------------------------
   SEARCH
--------------------------------------------------------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $origin      = strtoupper(trim($_POST['origin'] ?? ''));
    $destination = strtoupper(trim($_POST['destination'] ?? ''));
    $departure   = trim($_POST['departure'] ?? '');
    $returnDate  = trim($_POST['return_date'] ?? '');

    $adults   = max(1, (int)($_POST['adults'] ?? 1));
    $children = max(0, (int)($_POST['children'] ?? 0));
    $infants  = max(0, (int)($_POST['infants'] ?? 0));

    $cabin = $_POST['cabin'] ?? 'economy';

    $allowedCabins = [
        'economy',
        'premium_economy',
        'business',
        'first'
    ];

    if (!in_array($cabin, $allowedCabins, true)) {
        $cabin = 'economy';
    }

    if (
        strlen($origin) !== 3 ||
        strlen($destination) !== 3 ||
        !$departure
    ) {
        $error = 'Please enter valid airport codes and departure date.';
    } else {

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

        for ($i = 0; $i < $children; $i++) {
            $passengers[] = [
                'type' => 'child'
            ];
        }

        /*
         * Duffel infant_without_seat passenger.
         * Infant should normally be associated with an adult
         * during the later booking/passenger-details stage.
         */
        for ($i = 0; $i < $infants; $i++) {
            $passengers[] = [
                'type' => 'infant_without_seat'
            ];
        }

        $payload = [
            'data' => [
                'slices' => $slices,
                'passengers' => $passengers,
                'cabin_class' => $cabin
            ]
        ];

        $api = duffel_api_request(
            '/air/offer_requests?return_offers=true&supplier_timeout=20000',
            $payload
        );

        if (!$api['ok']) {
            $error = $api['error'];
        } else {
            $results = $api['data']['data']['offers'] ?? [];
        }
    }
}


site_header('Flight Search');
?>

<style>

.flight-v3-page{
    background:#f4f8fc;
    min-height:750px;
    padding:48px 0 80px;
}

.flight-v3-wrap{
    max-width:1180px;
    margin:auto;
}

.flight-v3-title{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    margin-bottom:20px;
}

.flight-v3-title h1{
    margin:0;
    font-size:36px;
    color:#06244b;
}

.flight-v3-title p{
    margin:7px 0 0;
    color:#64748b;
}

.test-badge{
    background:#dff3ff;
    color:#0877bd;
    font-size:11px;
    font-weight:800;
    padding:8px 14px;
    border-radius:50px;
}

.flight-box{
    background:white;
    border:1px solid #dce6f0;
    border-radius:18px;
    padding:20px;
    box-shadow:0 18px 40px rgba(20,50,80,.08);
}

.trip-tabs{
    display:flex;
    gap:8px;
    margin-bottom:16px;
}

.trip-tab{
    border:1px solid #d6e1ec;
    background:#fff;
    padding:10px 18px;
    border-radius:50px;
    font-weight:700;
    cursor:pointer;
}

.trip-tab.active{
    background:#062b5c;
    color:#fff;
    border-color:#062b5c;
}

.search-grid{
    display:grid;
    grid-template-columns:
        1.2fr
        42px
        1.2fr
        1fr
        1fr
        1.15fr
        145px;
    gap:10px;
    align-items:stretch;
}

.search-field{
    border:1px solid #cad8e6;
    border-radius:12px;
    padding:9px 12px;
    position:relative;
    background:#fff;
}

.search-field label{
    display:block;
    font-size:10px;
    font-weight:800;
    color:#69809b;
    margin-bottom:4px;
}

.search-field input,
.search-field select{
    width:100%;
    border:0;
    outline:0;
    font-size:15px;
    font-weight:700;
    color:#0b2440;
    background:transparent;
}

.swap{
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:20px;
    color:#0b5fa5;
}

.search-btn{
    border:0;
    border-radius:12px;
    background:#0d8ee5;
    color:#fff;
    font-weight:800;
    cursor:pointer;
    font-size:14px;
}

.search-btn:hover{
    background:#067cc9;
}

.passenger-panel{
    margin-top:14px;
    padding-top:14px;
    border-top:1px solid #e8eef5;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:12px;
}

.passenger-field label{
    display:block;
    font-size:12px;
    font-weight:700;
    margin-bottom:5px;
    color:#566b82;
}

.passenger-field select{
    width:100%;
    padding:10px;
    border:1px solid #d4dfeb;
    border-radius:9px;
    background:white;
}

.autocomplete{
    position:absolute;
    top:100%;
    left:0;
    right:0;
    background:#fff;
    border:1px solid #dce5ef;
    border-radius:10px;
    margin-top:5px;
    z-index:100;
    box-shadow:0 15px 35px rgba(0,0,0,.12);
    overflow:hidden;
    display:none;
}

.autocomplete div{
    padding:11px 12px;
    cursor:pointer;
    border-bottom:1px solid #edf2f7;
}

.autocomplete div:hover{
    background:#f2f8ff;
}

.autocomplete strong{
    color:#062b5c;
}

.autocomplete small{
    display:block;
    color:#718096;
    margin-top:2px;
}

.error-box{
    margin-top:22px;
    background:#fff0f0;
    border:1px solid #ffc9c9;
    color:#a61b1b;
    padding:15px;
    border-radius:12px;
}

.results-title{
    margin:35px 0 15px;
    font-size:25px;
    color:#06244b;
}

.flight-result{
    background:white;
    border:1px solid #dce5ef;
    border-radius:16px;
    margin-bottom:15px;
    overflow:hidden;
    box-shadow:0 8px 25px rgba(20,40,60,.05);
}

.result-main{
    display:grid;
    grid-template-columns:1fr 1fr 220px;
    align-items:center;
    padding:20px;
}

.airline{
    display:flex;
    gap:15px;
    align-items:center;
}

.airline-logo{
    width:52px;
    height:52px;
    object-fit:contain;
}

.airline-name{
    font-weight:800;
    color:#0b2440;
}

.route-time{
    font-size:20px;
    font-weight:800;
    color:#081c36;
}

.route-sub{
    color:#66798e;
    font-size:13px;
    margin-top:5px;
}

.price-area{
    text-align:right;
}

.price{
    font-size:26px;
    font-weight:900;
    color:#06244b;
}

.select-flight{
    display:inline-block;
    margin-top:8px;
    background:#081c36;
    color:white;
    padding:10px 20px;
    border-radius:8px;
    text-decoration:none;
    font-weight:800;
}

.offer-info{
    padding:12px 20px;
    border-top:1px solid #edf1f5;
    display:flex;
    gap:25px;
    flex-wrap:wrap;
    color:#586d83;
    font-size:13px;
}

@media(max-width:1000px){

    .search-grid{
        grid-template-columns:1fr 1fr;
    }

    .swap{
        display:none;
    }

    .search-btn{
        min-height:55px;
    }

    .passenger-panel{
        grid-template-columns:1fr 1fr;
    }

    .result-main{
        grid-template-columns:1fr;
        gap:15px;
    }

    .price-area{
        text-align:left;
    }
}

</style>


<main class="flight-v3-page">

<div class="container flight-v3-wrap">

    <div class="flight-v3-title">

        <div>
            <h1>Find your next flight</h1>
            <p>Search live airline offers directly with Mustafa Travels.</p>
        </div>

        <span class="test-badge">V3 TEST ENGINE</span>

    </div>


    <form method="post"
          action="flights-v3.php"
          class="flight-box"
          id="flightSearchForm">

        <div class="trip-tabs">

            <button
                type="button"
                class="trip-tab active"
                id="roundTripBtn">
                Round trip
            </button>

            <button
                type="button"
                class="trip-tab"
                id="oneWayBtn">
                One way
            </button>

        </div>


        <div class="search-grid">

            <div class="search-field">

                <label>FROM</label>

                <input
                    type="text"
                    name="origin"
                    id="origin"
                    maxlength="3"
                    placeholder="BCN"
                    autocomplete="off"
                    value="<?= h($_POST['origin'] ?? 'BCN') ?>"
                    required>

                <div class="autocomplete" id="originSuggestions"></div>

            </div>


            <div class="swap">⇄</div>


            <div class="search-field">

                <label>TO</label>

                <input
                    type="text"
                    name="destination"
                    id="destination"
                    maxlength="3"
                    placeholder="LHE"
                    autocomplete="off"
                    value="<?= h($_POST['destination'] ?? '') ?>"
                    required>

                <div class="autocomplete" id="destinationSuggestions"></div>

            </div>


            <div class="search-field">

                <label>DEPARTURE</label>

                <input
                    type="date"
                    name="departure"
                    min="<?= date('Y-m-d') ?>"
                    value="<?= h($_POST['departure'] ?? '') ?>"
                    required>

            </div>


            <div class="search-field" id="returnField">

                <label>RETURN</label>

                <input
                    type="date"
                    name="return_date"
                    id="returnDate"
                    min="<?= date('Y-m-d') ?>"
                    value="<?= h($_POST['return_date'] ?? '') ?>">

            </div>


            <div class="search-field">

                <label>TRAVELLERS & CABIN</label>

                <select name="cabin">

                    <option value="economy">
                        Economy
                    </option>

                    <option value="premium_economy">
                        Premium Economy
                    </option>

                    <option value="business">
                        Business
                    </option>

                    <option value="first">
                        First
                    </option>

                </select>

            </div>


            <button
                type="submit"
                class="search-btn">

                Search flights

            </button>

        </div>


        <div class="passenger-panel">

            <div class="passenger-field">

                <label>Adults</label>

                <select name="adults">

                    <?php for($i=1;$i<=9;$i++): ?>

                        <option
                            value="<?= $i ?>"
                            <?= ((int)($_POST['adults'] ?? 1) === $i) ? 'selected' : '' ?>>

                            <?= $i ?> Adult<?= $i > 1 ? 's' : '' ?>

                        </option>

                    <?php endfor; ?>

                </select>

            </div>


            <div class="passenger-field">

                <label>Children</label>

                <select name="children">

                    <?php for($i=0;$i<=8;$i++): ?>

                        <option
                            value="<?= $i ?>"
                            <?= ((int)($_POST['children'] ?? 0) === $i) ? 'selected' : '' ?>>

                            <?= $i ?>

                        </option>

                    <?php endfor; ?>

                </select>

            </div>


            <div class="passenger-field">

                <label>Infants</label>

                <select name="infants">

                    <?php for($i=0;$i<=4;$i++): ?>

                        <option
                            value="<?= $i ?>"
                            <?= ((int)($_POST['infants'] ?? 0) === $i) ? 'selected' : '' ?>>

                            <?= $i ?>

                        </option>

                    <?php endfor; ?>

                </select>

            </div>


            <div class="passenger-field">

                <label>Fare preference</label>

                <select name="fare_preference">

                    <option value="all">
                        Show all fares
                    </option>

                    <option value="bag">
                        Checked baggage preferred
                    </option>

                </select>

            </div>

        </div>

    </form>


    <?php if ($error): ?>

        <div class="error-box">
            <strong>Search error:</strong>
            <?= h($error) ?>
        </div>

    <?php endif; ?>


    <?php if ($results): ?>

        <h2 class="results-title">
            Available flights
        </h2>


        <?php foreach ($results as $offer):

            $slice = $offer['slices'][0] ?? [];
            $segments = $slice['segments'] ?? [];

            if (!$segments) continue;

            $first = $segments[0];
            $last  = $segments[count($segments)-1];

            $carrier =
                $first['operating_carrier']['name']
                ?? $first['marketing_carrier']['name']
                ?? 'Airline';

            $logo =
                $first['operating_carrier']['logo_symbol_url']
                ?? $first['marketing_carrier']['logo_symbol_url']
                ?? '';

            $departTime =
                isset($first['departing_at'])
                ? date('H:i', strtotime($first['departing_at']))
                : '';

            $arrivalTime =
                isset($last['arriving_at'])
                ? date('H:i', strtotime($last['arriving_at']))
                : '';

            $originCode =
                $first['origin']['iata_code']
                ?? '';

            $destinationCode =
                $last['destination']['iata_code']
                ?? '';

            $stops = max(0, count($segments)-1);

            $amount =
                $offer['total_amount']
                ?? '0';

            $currency =
                $offer['total_currency']
                ?? 'EUR';

            $offerId =
                $offer['id']
                ?? '';

        ?>


        <article class="flight-result">

            <div class="result-main">

                <div class="airline">

                    <?php if($logo): ?>

                        <img
                            class="airline-logo"
                            src="<?= h($logo) ?>"
                            alt="<?= h($carrier) ?>">

                    <?php endif; ?>

                    <div>

                        <div class="airline-name">
                            <?= h($carrier) ?>
                        </div>

                        <div class="route-sub">
                            <?= h($originCode) ?>
                            →
                            <?= h($destinationCode) ?>
                        </div>

                    </div>

                </div>


                <div>

                    <div class="route-time">

                        <?= h($departTime) ?>
                        →
                        <?= h($arrivalTime) ?>

                    </div>

                    <div class="route-sub">

                        <?php if($stops === 0): ?>

                            Direct flight

                        <?php elseif($stops === 1): ?>

                            1 stop

                        <?php else: ?>

                            <?= $stops ?> stops

                        <?php endif; ?>

                    </div>

                </div>


                <div class="price-area">

                    <div class="price">

                        <?= h($currency) ?>
                        <?= number_format((float)$amount,2) ?>

                    </div>

                    <a
                        class="select-flight"
                        href="flight-details.php?offer_id=<?= urlencode($offerId) ?>">

                        Select

                    </a>

                </div>

            </div>


            <div class="offer-info">

                <span>
                    ✈ Live Duffel fare
                </span>

                <span>
                    <?= count($segments) ?>
                    flight segment<?= count($segments)>1 ? 's' : '' ?>
                </span>

                <span>
                    Price shown for selected travellers
                </span>

            </div>

        </article>


        <?php endforeach; ?>

    <?php endif; ?>


</div>

</main>


<script>

/* --------------------------------------------------------
   ONE WAY / ROUND TRIP
--------------------------------------------------------- */

const roundBtn =
    document.getElementById('roundTripBtn');

const oneBtn =
    document.getElementById('oneWayBtn');

const returnField =
    document.getElementById('returnField');

const returnDate =
    document.getElementById('returnDate');


roundBtn.addEventListener('click', function(){

    roundBtn.classList.add('active');
    oneBtn.classList.remove('active');

    returnField.style.display = 'block';

});


oneBtn.addEventListener('click', function(){

    oneBtn.classList.add('active');
    roundBtn.classList.remove('active');

    returnField.style.display = 'none';

    returnDate.value = '';

});


/* --------------------------------------------------------
   SWAP AIRPORTS
--------------------------------------------------------- */

document.querySelector('.swap')
.addEventListener('click', function(){

    const from =
        document.getElementById('origin');

    const to =
        document.getElementById('destination');

    const old = from.value;

    from.value = to.value;
    to.value = old;

});


/* --------------------------------------------------------
   BASIC AIRPORT AUTOCOMPLETE

   We start with important Mustafa Travels markets.
   Later we can replace this with full global airport API.
--------------------------------------------------------- */

const airports = [

    ['BCN','Barcelona','Barcelona El Prat'],
    ['MAD','Madrid','Adolfo Suárez Madrid'],
    ['LHE','Lahore','Allama Iqbal International'],
    ['ISB','Islamabad','Islamabad International'],
    ['KHI','Karachi','Jinnah International'],
    ['SKT','Sialkot','Sialkot International'],

    ['JED','Jeddah','King Abdulaziz International'],
    ['MED','Madinah','Prince Mohammad Bin Abdulaziz'],

    ['DXB','Dubai','Dubai International'],
    ['AUH','Abu Dhabi','Zayed International'],
    ['DOH','Doha','Hamad International'],

    ['IST','Istanbul','Istanbul Airport'],

    ['LHR','London','Heathrow'],
    ['LGW','London','Gatwick'],

    ['CDG','Paris','Charles de Gaulle'],
    ['ORY','Paris','Orly'],

    ['FCO','Rome','Fiumicino'],
    ['MXP','Milan','Malpensa'],

    ['LIS','Lisbon','Humberto Delgado'],
    ['OPO','Porto','Francisco Sá Carneiro'],

    ['BOG','Bogotá','El Dorado International'],

    ['DAC','Dhaka','Hazrat Shahjalal International'],

    ['DEL','Delhi','Indira Gandhi International'],
    ['BOM','Mumbai','Chhatrapati Shivaji Maharaj'],

    ['CMB','Colombo','Bandaranaike International'],

    ['MNL','Manila','Ninoy Aquino International'],

    ['BKK','Bangkok','Suvarnabhumi'],

    ['KUL','Kuala Lumpur','Kuala Lumpur International'],

    ['SIN','Singapore','Changi'],

    ['JFK','New York','John F. Kennedy International'],
    ['EWR','New York','Newark Liberty'],

    ['YYZ','Toronto','Toronto Pearson']

];


function setupAirportAutocomplete(
    inputId,
    suggestionId
){

    const input =
        document.getElementById(inputId);

    const box =
        document.getElementById(suggestionId);


    input.addEventListener(
        'input',
        function(){

            let query =
                input.value
                .trim()
                .toLowerCase();

            box.innerHTML = '';

            if(query.length < 2){

                box.style.display = 'none';
                return;

            }


            let matches =
                airports.filter(a => {

                    return (
                        a[0].toLowerCase().includes(query) ||
                        a[1].toLowerCase().includes(query) ||
                        a[2].toLowerCase().includes(query)
                    );

                }).slice(0,8);


            if(!matches.length){

                box.style.display = 'none';
                return;

            }


            matches.forEach(a => {

                let row =
                    document.createElement('div');

                row.innerHTML =
                    '<strong>' +
                    a[0] +
                    ' — ' +
                    a[1] +
                    '</strong>' +
                    '<small>' +
                    a[2] +
                    '</small>';


                row.addEventListener(
                    'click',
                    function(){

                        input.value = a[0];

                        box.style.display =
                            'none';

                    }
                );


                box.appendChild(row);

            });


            box.style.display =
                'block';

        }
    );


    input.addEventListener(
        'blur',
        function(){

            setTimeout(
                () => {
                    box.style.display =
                        'none';
                },
                200
            );

        }
    );

}


setupAirportAutocomplete(
    'origin',
    'originSuggestions'
);

setupAirportAutocomplete(
    'destination',
    'destinationSuggestions'
);


/* uppercase airport codes */

document
.querySelectorAll('#origin,#destination')
.forEach(function(el){

    el.addEventListener(
        'input',
        function(){

            if(this.value.length <= 3){
                this.value =
                    this.value.toUpperCase();
            }

        }
    );

});

</script>


<?php site_footer(); ?>
