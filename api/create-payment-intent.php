<?php
declare(strict_types=1);

require_once __DIR__ . '/../lib/booking.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Stripe\StripeClient;

header('Content-Type: application/json; charset=utf-8');

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok'=>false,'error'=>'Method not allowed']);
    exit;
}

$input = json_decode((string)file_get_contents('php://input'), true);
$offerId = trim((string)($input['offer_id'] ?? ''));
if ($offerId === '' || empty($_SESSION['flight_checkout'][$offerId])) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Booking session expired. Please re-enter passenger details.']);
    exit;
}

$secret = getenv('STRIPE_SECRET_KEY') ?: '';
$publishable = getenv('STRIPE_PUBLISHABLE_KEY') ?: '';
if ($secret === '' || $publishable === '') {
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Stripe test keys are not configured on the server.']);
    exit;
}

$checkout = $_SESSION['flight_checkout'][$offerId];
$reprice = mt_reprice_checkout($offerId, $checkout);
if (!$reprice['ok']) {
    http_response_code(409);
    echo json_encode(['ok'=>false,'error'=>$reprice['error']]);
    exit;
}

$lead = $checkout['passengers'][0] ?? [];
$email = trim((string)($lead['email'] ?? ''));
$name = trim((string)($lead['given_name'] ?? '').' '.(string)($lead['family_name'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Lead passenger email is missing or invalid.']);
    exit;
}

$ref = mt_booking_ref();
$amount = (float)$reprice['amount'];
$currency = strtolower((string)$reprice['currency']);
$minor = (int)round($amount * 100);

try {
    $stripe = new StripeClient($secret);
    $pi = $stripe->paymentIntents->create([
        'amount'=>$minor,
        'currency'=>$currency,
        'automatic_payment_methods'=>['enabled'=>true],
        'description'=>'Mustafa Travels flight booking '.$ref,
        'metadata'=>[
            'booking_ref'=>$ref,
            'offer_id'=>$offerId,
            'site'=>'mustafatravels.org'
        ]
    ]);

    $row = [
        'booking_ref'=>$ref,
        'offer_id'=>$offerId,
        'payment_intent_id'=>$pi->id,
        'status'=>'payment_pending',
        'amount'=>number_format($amount, 2, '.', ''),
        'currency'=>strtoupper($currency),
        'customer_email'=>$email,
        'customer_name'=>$name,
        'checkout_json'=>$checkout
    ];
    $save = mt_booking_insert($row);
    if (!$save['ok']) {
        try { $stripe->paymentIntents->cancel($pi->id); } catch (Throwable $e) {}
        error_log('BOOKING DB INSERT FAILED | '.$save['status'].' | '.$save['raw']);
        http_response_code(500);
        echo json_encode(['ok'=>false,'error'=>'Unable to prepare booking record. Please try again.']);
        exit;
    }

    echo json_encode([
        'ok'=>true,
        'client_secret'=>$pi->client_secret,
        'publishable_key'=>$publishable,
        'booking_ref'=>$ref,
        'amount'=>number_format($amount, 2, '.', ''),
        'currency'=>strtoupper($currency)
    ]);
} catch (Throwable $e) {
    error_log('STRIPE PI ERROR: '.$e->getMessage());
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>'Unable to start secure card payment.']);
}
