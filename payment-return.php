<?php
declare(strict_types=1);
require_once __DIR__ . '/partials.php';
require_once __DIR__ . '/lib/booking.php';
require_once __DIR__ . '/vendor/autoload.php';

use Stripe\StripeClient;

$piId = trim((string)($_GET['payment_intent'] ?? ''));
$ref = trim((string)($_GET['booking_ref'] ?? ''));
$error = '';
$booking = null;

if ($piId === '' || $ref === '') {
    $error = 'Missing payment confirmation details.';
} else {
    $booking = mt_booking_find_by_ref($ref);
    if (!$booking || (string)$booking['payment_intent_id'] !== $piId) {
        $error = 'Booking reference could not be verified.';
    } else {
        try {
            $stripe = new StripeClient(getenv('STRIPE_SECRET_KEY') ?: '');
            $pi = $stripe->paymentIntents->retrieve($piId, []);
            $expectedMinor = (int)round((float)$booking['amount'] * 100);
            if ($pi->status !== 'succeeded') {
                $error = 'Payment has not completed yet. Current status: '.$pi->status;
            } elseif ((int)$pi->amount_received !== $expectedMinor || strtolower((string)$pi->currency) !== strtolower((string)$booking['currency'])) {
                $error = 'Payment amount could not be verified. Please contact Mustafa Travels.';
                mt_booking_update($ref, ['status'=>'payment_mismatch','error_message'=>$error]);
            } else {
                $result = mt_process_paid_booking($booking);
                $booking = mt_booking_find_by_ref($ref) ?: $booking;
                if (!$result['ok'] && ($booking['status'] ?? '') !== 'confirmed') {
                    $error = (string)($result['error'] ?? 'Payment received, but airline booking requires manual review.');
                }
            }
        } catch (Throwable $e) {
            error_log('PAYMENT RETURN ERROR: '.$e->getMessage());
            $error = 'We could not complete the automatic confirmation. Your payment record is safely recorded and our team can review it.';
        }
    }
}

site_header('Booking Status');
?>
<style>
.pr-page{background:#f4f8fb;min-height:650px;padding:55px 16px}.pr-card{max-width:720px;margin:auto;background:#fff;border:1px solid #dce6ef;border-radius:18px;padding:30px;box-shadow:0 10px 30px rgba(8,47,95,.06)}.pr-ok{font-size:42px}.pr-card h1{color:#082f5f}.pr-ref{background:#f5f8fb;border-radius:10px;padding:12px;margin:16px 0}.pr-pnr{font-size:28px;font-weight:900;color:#0d7a55}.pr-warn{background:#fff4df;color:#805d17;padding:14px;border-radius:10px}.pr-btn{display:inline-block;margin-top:16px;background:#082f5f;color:#fff!important;text-decoration:none;padding:12px 18px;border-radius:10px;font-weight:900}
</style>
<section class="pr-page"><div class="pr-card">
<?php if($booking && ($booking['status'] ?? '') === 'confirmed'): ?>
<div class="pr-ok">✓</div><h1>Booking confirmed</h1>
<p>Your payment was received and the airline booking has been created successfully.</p>
<div class="pr-ref"><b>Mustafa Travels reference:</b> <?=h((string)$booking['booking_ref'])?></div>
<div>Airline PNR</div><div class="pr-pnr"><?=h((string)($booking['pnr'] ?? ''))?></div>
<p>We have sent the final confirmation email with PDF attachment to <b><?=h((string)$booking['customer_email'])?></b>.</p>
<?php elseif($booking && in_array(($booking['status'] ?? ''), ['payment_received','booking_processing','booking_failed','payment_mismatch'], true)): ?>
<h1>Payment received</h1><div class="pr-ref"><b>Reference:</b> <?=h((string)$booking['booking_ref'])?></div>
<div class="pr-warn">Your payment has been recorded, but the airline booking has not been confirmed automatically yet. Mustafa Travels will review it before any final confirmation is sent.</div>
<?php if($error): ?><p><?=h($error)?></p><?php endif; ?>
<?php else: ?>
<h1>Payment status</h1><div class="pr-warn"><?=h($error ?: 'Unable to verify this payment at the moment.')?></div>
<?php endif; ?>
<a class="pr-btn" href="index.php">Return to Mustafa Travels</a>
</div></section>
<?php site_footer(); ?>
