<?php
declare(strict_types=1);

require_once __DIR__ . '/partials.php';
require_once __DIR__ . '/lib/booking.php';

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$error = '';
$order = [];
$bookingRef = '';
$deadline = '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $error = 'This page can only be used from the booking review screen.';
} else {
    $offerId = trim((string)($_POST['offer_id'] ?? ''));
    $checkout = $offerId !== '' ? ($_SESSION['flight_checkout'][$offerId] ?? null) : null;

    if ($offerId === '' || !is_array($checkout)) {
        $error = 'Your booking session expired. Please search again and re-enter passenger details.';
    } elseif (!empty(array_filter((array)($checkout['selected_services'] ?? []), fn($q)=>(int)$q > 0))) {
        $error = 'Paid extras cannot currently be included in a hold booking. Please remove extra baggage and try again.';
    } else {
        $reprice = mt_reprice_checkout($offerId, $checkout);
        if (!$reprice['ok']) {
            $error = $reprice['error'] ?: 'Unable to refresh this fare.';
        } else {
            $offer = $reprice['offer'] ?? [];
            $requiresInstant = (bool)($offer['payment_requirements']['requires_instant_payment'] ?? true);
            $deadline = (string)($offer['payment_requirements']['payment_required_by'] ?? '');

            if ($requiresInstant) {
                $error = 'This fare is no longer eligible for a hold. Please use Pay Now or search again.';
            } elseif ($deadline !== '' && strtotime($deadline) !== false && strtotime($deadline) <= time()) {
                $error = 'The airline hold deadline has already passed. Please search again.';
            } else {
                $lead = $checkout['passengers'][0] ?? [];
                $email = trim((string)($lead['email'] ?? ''));
                $name = trim((string)($lead['given_name'] ?? '').' '.(string)($lead['family_name'] ?? ''));
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Lead passenger email is missing or invalid.';
                } else {
                    $bookingRef = mt_booking_ref();
                    $duffelCost = (float)$reprice['amount'];
                    $selling = mt_flight_sell_price($duffelCost);
                    $markup = mt_flight_markup_amount($duffelCost);
                    $checkout['pricing'] = [
                        'duffel_cost'=>number_format($duffelCost, 2, '.', ''),
                        'markup_percent'=>mt_flight_markup_percent(),
                        'markup_amount'=>number_format($markup, 2, '.', ''),
                        'selling_amount'=>number_format($selling, 2, '.', ''),
                        'currency'=>strtoupper((string)$reprice['currency'])
                    ];
                    $checkout['hold'] = [
                        'payment_required_by'=>$deadline,
                        'created_at'=>gmdate('c')
                    ];

                    $row = [
                        'booking_ref'=>$bookingRef,
                        'offer_id'=>$offerId,
                        'status'=>'hold_processing',
                        'amount'=>number_format($selling, 2, '.', ''),
                        'currency'=>strtoupper((string)$reprice['currency']),
                        'customer_email'=>$email,
                        'customer_name'=>$name,
                        'checkout_json'=>$checkout
                    ];
                    $save = mt_booking_insert($row);
                    if (!$save['ok']) {
                        error_log('HOLD DB INSERT FAILED | '.$save['status'].' | '.$save['raw']);
                        $error = 'Unable to prepare the hold booking. Please try again.';
                    } else {
                        $payload = [
                            'data'=>[
                                'type'=>'hold',
                                'selected_offers'=>[$offerId],
                                'passengers'=>mt_duffel_order_passengers($checkout)
                            ]
                        ];
                        $api = mt_duffel_request('/air/orders', 'POST', $payload, [
                            'Idempotency-Key: hold-' . $bookingRef
                        ]);
                        if (!$api['ok']) {
                            $msg = $api['error'] ?: 'Airline hold could not be created.';
                            mt_booking_update($bookingRef, ['status'=>'hold_failed','error_message'=>$msg]);
                            error_log('DUFFEL HOLD FAILED | '.$api['status'].' | '.json_encode($api['data']));
                            $error = $msg;
                        } else {
                            $order = (array)($api['data']['data'] ?? []);
                            $orderId = (string)($order['id'] ?? '');
                            $pnr = (string)($order['booking_reference'] ?? '');
                            $deadline = (string)($order['payment_status']['payment_required_by'] ?? $deadline);
                            mt_booking_update($bookingRef, [
                                'status'=>'held',
                                'duffel_order_id'=>$orderId,
                                'pnr'=>$pnr,
                                'error_message'=>null
                            ]);
                            unset($_SESSION['flight_checkout'][$offerId]);
                        }
                    }
                }
            }
        }
    }
}

site_header($error ? 'Hold Booking' : 'Booking Held');
?>
<style>
.hold-page{background:#f4f8fb;min-height:650px;padding:55px 0}.hold-card{width:min(720px,calc(100% - 32px));margin:auto;background:#fff;border:1px solid #dce6ef;border-radius:18px;padding:30px}.hold-ok{font-size:44px;color:#16865e;line-height:1}.hold-card h1{color:#10253d;margin:8px 0}.hold-ref{background:#f4f7fa;padding:12px 14px;border-radius:10px;margin:14px 0}.hold-pnr{font-size:28px;font-weight:900;color:#16865e}.hold-warning{background:#fff8e8;border:1px solid #efdca4;color:#735719;border-radius:10px;padding:13px 14px;margin:16px 0}.hold-error{background:#fff0f2;border:1px solid #ffd0d7;color:#b52d43;border-radius:10px;padding:14px}.hold-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:20px}.hold-actions a{display:inline-block;text-decoration:none;border-radius:10px;padding:12px 16px;font-weight:900}.hold-primary{background:#082f5f;color:#fff}.hold-secondary{border:1px solid #cfdce7;color:#082f5f}
</style>
<section class="hold-page"><div class="hold-card">
<?php if($error): ?>
    <h1>Hold could not be created</h1>
    <div class="hold-error"><?=h($error)?></div>
    <div class="hold-actions"><a class="hold-primary" href="flights-v3.php">Search again</a><a class="hold-secondary" href="javascript:history.back()">Go back</a></div>
<?php else: ?>
    <div class="hold-ok">✓</div>
    <h1>Flight held successfully</h1>
    <p>No card payment has been taken. The airline has reserved this booking until the payment deadline.</p>
    <div class="hold-ref"><strong>Mustafa Travels reference:</strong> <?=h($bookingRef)?></div>
    <?php if(!empty($order['booking_reference'])): ?><div><strong>Airline PNR</strong><div class="hold-pnr"><?=h((string)$order['booking_reference'])?></div></div><?php endif; ?>
    <div class="hold-warning"><strong>Payment deadline:</strong> <?=h($deadline ?: 'See airline booking conditions')?>.<br>If payment is not completed before the airline deadline, the held space may be released automatically.</div>
    <p>You can view this booking from <strong>My Booking</strong> using the Mustafa Travels reference and the lead passenger email.</p>
    <div class="hold-actions"><a class="hold-primary" href="my-booking.php">My Booking</a><a class="hold-secondary" href="flights-v3.php">Search another flight</a></div>
<?php endif; ?>
</div></section>
<?php site_footer(); ?>
