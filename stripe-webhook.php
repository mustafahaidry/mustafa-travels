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
    error_log('STRIPE WEBHOOK INVALID | '.$e->getMessage());
    http_response_code(400);
    echo 'Invalid webhook';
    exit;
}

try {
    if (in_array($event->type, ['payment_intent.amount_capturable_updated', 'payment_intent.succeeded'], true)) {
        $pi = $event->data->object;
        $booking = mt_booking_find_by_pi((string)$pi->id);
        if (!$booking) {
            error_log('STRIPE WEBHOOK BOOKING NOT FOUND | PI '.(string)$pi->id.' | EVENT '.$event->type);
        } else {
            $expectedMinor = (int)round((float)$booking['amount'] * 100);
            $expectedCurrency = strtolower((string)$booking['currency']);
            $piCurrency = strtolower((string)$pi->currency);
            $piAmount = $event->type === 'payment_intent.amount_capturable_updated'
                ? (int)($pi->amount_capturable ?? 0)
                : (int)($pi->amount_received ?? 0);

            if ($piAmount !== $expectedMinor || $piCurrency !== $expectedCurrency) {
                mt_booking_update((string)$booking['booking_ref'], [
                    'status'=>'payment_mismatch',
                    'error_message'=>'Stripe amount or currency mismatch.'
                ]);
                error_log('STRIPE WEBHOOK AMOUNT MISMATCH | REF '.(string)$booking['booking_ref'].' | EVENT '.$event->type);
            } elseif ($event->type === 'payment_intent.amount_capturable_updated') {
                mt_process_authorized_booking($booking, (string)$pi->id);
            } else {
                // Final capture confirmation. For the new flow the Duffel order is already created.
                $fresh = mt_booking_find_by_ref((string)$booking['booking_ref']) ?: $booking;
                if (!empty($fresh['duffel_order_id'])) {
                    mt_finalize_captured_booking($fresh);
                } else {
                    // Backward compatibility for PaymentIntents created before manual-capture flow.
                    mt_process_paid_booking($fresh);
                }
            }
        }
    }
} catch (Throwable $e) {
    // Return 200 to prevent Stripe retry storms, but keep a detailed server log for manual recovery.
    error_log('STRIPE WEBHOOK PROCESSING ERROR | EVENT '.$event->type.' | '.$e->getMessage());
}

http_response_code(200);
echo 'ok';
