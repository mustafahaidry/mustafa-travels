<?php
declare(strict_types=1);
require_once __DIR__ . '/lib/booking.php';
require_once __DIR__ . '/vendor/autoload.php';

use Stripe\Webhook;

$secret = getenv('STRIPE_WEBHOOK_SECRET') ?: '';
$payload = (string)file_get_contents('php://input');
$sig = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

if ($secret === '') {
    http_response_code(500);
    echo 'Webhook secret missing';
    exit;
}

try {
    $event = Webhook::constructEvent($payload, $sig, $secret);
} catch (Throwable $e) {
    http_response_code(400);
    echo 'Invalid webhook';
    exit;
}

if ($event->type === 'payment_intent.succeeded') {
    $pi = $event->data->object;
    $booking = mt_booking_find_by_pi((string)$pi->id);
    if ($booking) {
        $expectedMinor = (int)round((float)$booking['amount'] * 100);
        $expectedCurrency = strtolower((string)$booking['currency']);
        if ((int)$pi->amount_received === $expectedMinor && strtolower((string)$pi->currency) === $expectedCurrency) {
            mt_process_paid_booking($booking);
        } else {
            mt_booking_update((string)$booking['booking_ref'], [
                'status'=>'payment_mismatch',
                'error_message'=>'Stripe amount or currency mismatch.'
            ]);
        }
    }
}

http_response_code(200);
echo 'ok';
