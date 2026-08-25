<?php
require_once __DIR__ . '/partials.php';
require_once __DIR__ . '/api/duffel.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$offerId = trim($_GET['offer_id'] ?? '');
$error = '';
$offer = [];
$checkout = [];

if ($offerId === '') {
    $error = 'Missing offer ID.';
} elseif (empty($_SESSION['flight_checkout'][$offerId])) {
    $error = 'Passenger details are missing or your session expired.';
} else {
    $checkout = $_SESSION['flight_checkout'][$offerId];

    $api = mt_duffel_get_offer($offerId, true);
    if (!$api['ok']) {
        $error = $api['error'];
        error_log('REVIEW PAGE DUFFEL | HTTP '.$api['status'].' | '.json_encode($api['data']));
    } else {
        $offer = $api['data']['data'] ?? [];
    }
}

function rv_airline(array $offer): string
{
    return (string)($offer['owner']['name'] ?? 'Airline');
}

function rv_time(?string $v): string
{
    $t = $v ? strtotime($v) : false;
    return $t ? date('H:i', $t) : '';
}

function rv_date(?string $v): string
{
    $t = $v ? strtotime($v) : false;
    return $t ? date('D, d M Y', $t) : '';
}

$holdEligible = (($offer['payment_requirements']['requires_instant_payment'] ?? true) === false);
$holdDeadline = $offer['payment_requirements']['payment_required_by'] ?? null;

site_header('Review Booking');
?>

<style>
.rv-page{background:#f4f8fb;min-height:720px;padding:38px 0 80px}.rv-wrap{width:min(1050px,calc(100% - 32px));margin:auto}
.rv-head h1{margin:0;color:#10253d}.rv-head p{color:#72869a}
.rv-grid{display:grid;grid-template-columns:1fr 330px;gap:18px}.rv-card{background:#fff;border:1px solid #dce6ef;border-radius:16px;padding:20px;margin-bottom:14px}
.rv-card h2{margin:0 0 14px;font-size:17px}.rv-passenger{padding:12px 0;border-top:1px solid #edf2f6}.rv-passenger:first-of-type{border-top:0}
.rv-passenger strong{color:#10253d}.rv-passenger small{display:block;color:#7d91a4;margin-top:4px}
.rv-summary{background:#fff;border:1px solid #dce6ef;border-radius:16px;padding:18px;align-self:start;position:sticky;top:90px}.rv-summary h3{margin:0 0 12px}
.rv-slice{padding:10px 0;border-top:1px solid #edf2f6}.rv-slice:first-of-type{border-top:0}.rv-route{display:flex;justify-content:space-between;gap:10px}.rv-route small{display:block;color:#8194a6;font-size:9px}
.rv-price{display:flex;justify-content:space-between;align-items:end;border-top:1px solid #edf2f6;padding-top:14px;margin-top:10px}.rv-price strong{font:900 24px Manrope,Inter,sans-serif;color:#082f5f}
.rv-hold{background:#eef0ff;color:#4052b5;padding:10px;border-radius:9px;font-size:10px;margin-top:12px}.rv-actions{display:grid;gap:9px;margin-top:14px}.rv-btn{display:block;text-align:center;text-decoration:none;border-radius:10px;padding:12px;font-size:11px;font-weight:900}.rv-primary{background:#082f5f;color:#fff!important}.rv-secondary{border:1px solid #d5e0e9;color:#536b80!important}
.rv-error{background:#fff0f2;border:1px solid #ffd0d7;color:#b52d43;padding:16px;border-radius:11px}
@media(max-width:800px){.rv-grid{grid-template-columns:1fr}.rv-summary{position:static}}
</style>

<section class="rv-page">
<div class="rv-wrap">

    <div class="rv-head">
        <h1>Review your booking</h1>
        <p>Check passenger details and the refreshed fare before continuing.</p>
    </div>

    <?php if($error): ?>
        <div class="rv-error"><strong>Review error:</strong> <?=h($error)?></div>
    <?php else: ?>

    <div class="rv-grid">

        <div>
            <div class="rv-card">
                <h2>Passengers</h2>

                <?php foreach(($checkout['passengers'] ?? []) as $i => $p): ?>
                    <div class="rv-passenger">
                        <strong><?=h(($p['given_name'] ?? '').' '.($p['family_name'] ?? ''))?></strong>
                        <small>
                            <?=h(ucfirst((string)($p['type'] ?? 'passenger')))?>
                            · DOB <?=h((string)($p['born_on'] ?? ''))?>
                            <?php if(!empty($p['passport_number'])): ?>
                                · Passport <?=h((string)$p['passport_number'])?>
                            <?php endif; ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="rv-card">
                <h2>Next build phase</h2>
                <p>From this screen we will add the two booking actions:</p>
                <p><strong>Hold Now</strong> when the selected fare supports delayed payment, or <strong>Pay Now</strong> through Stripe before creating the Duffel order.</p>
            </div>
        </div>

        <aside class="rv-summary">
            <h3><?=h(rv_airline($offer))?></h3>

            <?php foreach(($offer['slices'] ?? []) as $slice):
                $segments = $slice['segments'] ?? [];
                $first = $segments[0] ?? [];
                $last = $segments ? $segments[count($segments)-1] : [];
            ?>
                <div class="rv-slice">
                    <div class="rv-route">
                        <div>
                            <strong><?=h((string)($first['origin']['iata_code'] ?? ''))?> <?=h(rv_time($first['departing_at'] ?? null))?></strong>
                            <small><?=h(rv_date($first['departing_at'] ?? null))?></small>
                        </div>
                        <div style="text-align:right">
                            <strong><?=h((string)($last['destination']['iata_code'] ?? ''))?> <?=h(rv_time($last['arriving_at'] ?? null))?></strong>
                            <small><?=h(rv_date($last['arriving_at'] ?? null))?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="rv-price">
                <span>Total</span>
                <strong><?=h((string)($offer['total_currency'] ?? 'EUR'))?> <?=number_format((float)($offer['total_amount'] ?? 0),2)?></strong>
            </div>

            <?php if($holdEligible): ?>
                <div class="rv-hold">
                    ✓ This offer reports hold eligibility.
                    <?php if($holdDeadline): ?><br>Current pay-by: <?=h((string)$holdDeadline)?><?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="rv-actions">
                <a class="rv-btn rv-primary" href="#" onclick="alert('Next phase: Hold / Stripe / Create Order');return false;">Continue to payment / hold</a>
                <a class="rv-btn rv-secondary" href="flight-passengers.php?offer_id=<?=urlencode($offerId)?>">Edit passengers</a>
            </div>
        </aside>

    </div>

    <?php endif; ?>

</div>
</section>

<?php site_footer(); ?>
