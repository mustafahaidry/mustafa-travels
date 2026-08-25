<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Mustafa Travels & Tours - Duffel Flight Search API
|--------------------------------------------------------------------------
| TEST BACKEND
| This file receives flight search data from flights-test.php
| and sends it securely to Duffel.
|--------------------------------------------------------------------------
*/

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');


/*
|--------------------------------------------------------------------------
| Only allow POST requests
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'POST request required.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Get Duffel API key from Render Environment
|--------------------------------------------------------------------------
*/

$duffelKey = getenv('DUFFEL_API_KEY') ?: '';

if ($duffelKey === '') {
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'DUFFEL_API_KEY is not configured on the server.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Read incoming JSON
|--------------------------------------------------------------------------
*/

$rawInput = file_get_contents('php://input');

$input = json_decode($rawInput ?: '', true);

if (!is_array($input)) {
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request data.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Get and clean search fields
|--------------------------------------------------------------------------
*/

$origin = strtoupper(trim((string)($input['origin'] ?? '')));
$destination = strtoupper(trim((string)($input['destination'] ?? '')));

$departureDate = trim((string)($input['departure_date'] ?? ''));
$returnDate = trim((string)($input['return_date'] ?? ''));

$adults = (int)($input['adults'] ?? 1);
$children = (int)($input['children'] ?? 0);
$infants = (int)($input['infants'] ?? 0);

$cabinClass = trim((string)($input['cabin_class'] ?? 'economy'));


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if (!preg_match('/^[A-Z]{3}$/', $origin)) {
    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Origin must be a valid 3-letter IATA code, for example BCN.'
    ]);

    exit;
}


if (!preg_match('/^[A-Z]{3}$/', $destination)) {
    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Destination must be a valid 3-letter IATA code, for example LHE.'
    ]);

    exit;
}


if ($origin === $destination) {
    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Origin and destination cannot be the same.'
    ]);

    exit;
}


function valid_date(string $date): bool
{
    if ($date === '') {
        return false;
    }

    $d = DateTime::createFromFormat('Y-m-d', $date);

    return $d && $d->format('Y-m-d') === $date;
}


if (!valid_date($departureDate)) {
    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Please select a valid departure date.'
    ]);

    exit;
}


$today = date('Y-m-d');

if ($departureDate < $today) {
    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Departure date cannot be in the past.'
    ]);

    exit;
}


if ($returnDate !== '') {

    if (!valid_date($returnDate)) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Please select a valid return date.'
        ]);

        exit;
    }

    if ($returnDate < $departureDate) {
        http_response_code(422);

        echo json_encode([
            'success' => false,
            'message' => 'Return date cannot be before departure date.'
        ]);

        exit;
    }
}


if ($adults < 1 || $adults > 9) {
    http_response_code(422);

    echo json_encode([
        'success' => false,
        'message' => 'Adults must be between 1 and 9.'
    ]);

    exit;
}


if ($children < 0 || $children > 8) {
    $children = 0;
}


if ($infants < 0 || $infants > $adults) {
    $infants = 0;
}


$allowedCabins = [
    'economy',
    'premium_economy',
    'business',
    'first'
];

if (!in_array($cabinClass, $allowedCabins, true)) {
    $cabinClass = 'economy';
}


/*
|--------------------------------------------------------------------------
| Build Duffel slices
|--------------------------------------------------------------------------
*/

$slices = [
    [
        'origin' => $origin,
        'destination' => $destination,
        'departure_date' => $departureDate
    ]
];


if ($returnDate !== '') {

    $slices[] = [
        'origin' => $destination,
        'destination' => $origin,
        'departure_date' => $returnDate
    ];
}


/*
|--------------------------------------------------------------------------
| Build passengers
|--------------------------------------------------------------------------
*/

$passengers = [];


/* Adults */

for ($i = 0; $i < $adults; $i++) {

    $passengers[] = [
        'type' => 'adult'
    ];
}


/* Children */

for ($i = 0; $i < $children; $i++) {

    $passengers[] = [
        'age' => 8
    ];
}


/* Infants */

for ($i = 0; $i < $infants; $i++) {

    $passengers[] = [
        'age' => 1
    ];
}


/*
|--------------------------------------------------------------------------
| Duffel request payload
|--------------------------------------------------------------------------
*/

$payload = [
    'data' => [
        'slices' => $slices,
        'passengers' => $passengers,
        'cabin_class' => $cabinClass
    ]
];


/*
|--------------------------------------------------------------------------
| Send request to Duffel
|--------------------------------------------------------------------------
*/

$url = 'https://api.duffel.com/air/offer_requests'
     . '?return_offers=true'
     . '&supplier_timeout=20000';


$ch = curl_init($url);

curl_setopt_array($ch, [

    CURLOPT_RETURNTRANSFER => true,

    CURLOPT_POST => true,

    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $duffelKey,
        'Duffel-Version: v2',
        'Accept: application/json',
        'Accept-Encoding: gzip',
        'Content-Type: application/json'
    ],

    CURLOPT_POSTFIELDS => json_encode(
        $payload,
        JSON_UNESCAPED_SLASHES
    ),

    CURLOPT_ENCODING => '',

    CURLOPT_CONNECTTIMEOUT => 10,

    CURLOPT_TIMEOUT => 35
]);


$response = curl_exec($ch);

$httpCode = (int)curl_getinfo(
    $ch,
    CURLINFO_HTTP_CODE
);

$curlError = curl_error($ch);

curl_close($ch);


/*
|--------------------------------------------------------------------------
| cURL error
|--------------------------------------------------------------------------
*/

if ($response === false) {

    http_response_code(502);

    echo json_encode([
        'success' => false,
        'message' => 'Could not connect to Duffel.',
        'error' => $curlError
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Decode Duffel response
|--------------------------------------------------------------------------
*/

$data = json_decode($response, true);


if (!is_array($data)) {

    http_response_code(502);

    echo json_encode([
        'success' => false,
        'message' => 'Duffel returned an invalid response.'
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Duffel API error
|--------------------------------------------------------------------------
*/

if ($httpCode < 200 || $httpCode >= 300) {

    error_log(
        'DUFFEL SEARCH ERROR HTTP '
        . $httpCode
        . ' BODY: '
        . $response
    );

    http_response_code($httpCode);

    echo json_encode([
        'success' => false,
        'message' => 'Duffel flight search failed.',
        'duffel_error' => $data['errors'] ?? []
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Extract offers
|--------------------------------------------------------------------------
*/

$offerRequest = $data['data'] ?? [];

$offers = $offerRequest['offers'] ?? [];

if (!is_array($offers)) {
    $offers = [];
}


/*
|--------------------------------------------------------------------------
| Sort cheapest first
|--------------------------------------------------------------------------
*/

usort($offers, function ($a, $b) {

    $priceA = (float)($a['total_amount'] ?? 999999999);
    $priceB = (float)($b['total_amount'] ?? 999999999);

    return $priceA <=> $priceB;
});


/*
|--------------------------------------------------------------------------
| Return result to our website
|--------------------------------------------------------------------------
*/

echo json_encode([
    'success' => true,

    'search' => [
        'origin' => $origin,
        'destination' => $destination,
        'departure_date' => $departureDate,
        'return_date' => $returnDate,
        'adults' => $adults,
        'children' => $children,
        'infants' => $infants,
        'cabin_class' => $cabinClass
    ],

    'offer_request_id' => $offerRequest['id'] ?? null,

    'live_mode' => $offerRequest['live_mode'] ?? null,

    'count' => count($offers),

    'offers' => $offers

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
