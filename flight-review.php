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
$savedExtras = false;

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_extras'])) {
            $allowed = [];
            foreach (($offer['available_services'] ?? []) as $service) {
                if (($service['type'] ?? '') !== 'baggage' || empty($service['id'])) {
                    continue;
                }
                $allowed[(string)$service['id']] = max(0, (int)($service['maximum_quantity'] ?? 1));
            }

            $selected = [];
            foreach (($_POST['extra_bags'] ?? []) as $serviceId => $qty) {
                $serviceId = (string)$serviceId;
                if (!array_key_exists($serviceId, $allowed)) {
                    continue;
                }
                $qty = max(0, min((int)$qty, $allowed[$serviceId]));
                if ($qty > 0) {
                    $selected[$serviceId] = $qty;
                }
            }

            $_SESSION['flight_checkout'][$offerId]['selected_services'] = $selected;
            $checkout = $_SESSION['flight_checkout'][$offerId];
            $savedExtras = true;
        }
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

function rv_mask_passport(string $passport): string
{
    $passport = trim($passport);
    $len = strlen($passport);
    if ($len <= 4) return str_repeat('*', $len);
    return substr($passport, 0, 2) . str_repeat('*', max(2, $len - 4)) . substr($passport, -2);
}

function rv_money($amount, $currency): string
{
    return h((string)$currency).' '.number_format((float)$amount, 2);
}

function rv_passenger_names(array $offer, array $ids): string
{
    $names = [];
    foreach (($offer['passengers'] ?? []) as $index => $p) {
        if (!in_array((string)($p['id'] ?? ''), $ids, true)) continue;
        $name = trim((string)($p['given_name'] ?? '').' '.(string)($p['family_name'] ?? ''));
        $names[] = $name !== '' ? $name : 'Passenger '.($index + 1);
    }
    return $names ? implode(', ', $names) : 'Selected passenger';
}

function rv_segment_label(array $offer, array $segmentIds): string
{
    $labels = [];
    foreach (($offer['slices'] ?? []) as $slice) {
        foreach (($slice['segments'] ?? []) as $segment) {
            if (!in_array((string)($segment['id'] ?? ''), $segmentIds, true)) continue;
            $o = (string)($segment['origin']['iata_code'] ?? '');
            $d = (string)($segment['destination']['iata_code'] ?? '');
            if ($o || $d) $labels[] = $o.' → '.$d;
        }
    }
    return $labels ? implode(', ', array_unique($labels)) : 'Applicable flight segment';
}

function rv_included_baggage(array $offer): array
{
    $rows = [];
    foreach (($offer['slices'] ?? []) as $slice) {
        foreach (($slice['segments'] ?? []) as $segment) {
            $route = (string)($segment['origin']['iata_code'] ?? '').' → '.(string)($segment['destination']['iata_code'] ?? '');
            foreach (($segment['passengers'] ?? []) as $sp) {
                $pid = (string)($sp['passenger_id'] ?? '');
                foreach (($sp['baggages'] ?? []) as $bag) {
                    $type = (string)($bag['type'] ?? 'baggage');
                    $qty = (int)($bag['quantity'] ?? 0);
                    if ($qty < 1) continue;
                    $key = $pid.'|'.$route.'|'.$type;
                    $rows[$key] = [
                        'passenger_id' => $pid,
                        'route' => $route,
                        'type' => $type,
                        'quantity' => $qty,
                    ];
                }
            }
        }
    }
    return array_values($rows);
}

$holdEligible = (($offer['payment_requirements']['requires_instant_payment'] ?? true) === false);
$holdDeadline = $offer['payment_requirements']['payment_required_by'] ?? null;
$availableBags = [];
foreach (($offer['available_services'] ?? []) as $service) {
    if (($service['type'] ?? '') === 'baggage') $availableBags[] = $service;
}
$includedBags = rv_included_baggage($offer);
$selectedServices = $checkout['selected_services'] ?? [];

$extraTotal = 0.0;
foreach ($availableBags as $service) {
    $qty = (int)($selectedServices[$service['id'] ?? ''] ?? 0);
    $extraTotal += $qty * (float)($service['total_amount'] ?? 0);
}
$baseTotal = (float)($offer['total_amount'] ?? 0);
$grandTotal = $baseTotal + $extraTotal;

site_header('Review Booking');
?>

<style>
.rv-page{background:#f4f8fb;min-height:720px;padding:38px 0 80px}.rv-wrap{width:min(1120px,calc(100% - 32px));margin:auto}
.rv-head{display:flex;justify-content:space-between;gap:18px;align-items:flex-end;margin-bottom:16px}.rv-head h1{margin:0;color:#10253d}.rv-head p{color:#72869a;margin:5px 0 0}.rv-preview{display:inline-flex;align-items:center;gap:7px;border:1px solid #ccd9e4;background:#fff;color:#082f5f!important;text-decoration:none;border-radius:10px;padding:11px 14px;font-weight:900;font-size:11px;white-space:nowrap}
.rv-grid{display:grid;grid-template-columns:1fr 350px;gap:18px}.rv-card{background:#fff;border:1px solid #dce6ef;border-radius:16px;padding:20px;margin-bottom:14px}.rv-card h2{margin:0 0 14px;font-size:17px;color:#10253d}.rv-card p{color:#61788c}
.rv-passenger{padding:12px 0;border-top:1px solid #edf2f6}.rv-passenger:first-of-type{border-top:0}.rv-passenger strong{color:#10253d}.rv-passenger small{display:block;color:#7d91a4;margin-top:4px}
.rv-bag-note{background:#f7fafc;border:1px solid #e3ebf1;color:#60788c;border-radius:10px;padding:11px 12px;font-size:11px;margin-bottom:12px}.rv-included{display:grid;gap:8px;margin-bottom:18px}.rv-included-row{display:flex;justify-content:space-between;gap:15px;border:1px solid #e5edf3;border-radius:10px;padding:11px 12px}.rv-included-row small{display:block;color:#7890a4;margin-top:3px}.rv-bag-ok{font-weight:900;color:#17835d;white-space:nowrap}
.rv-extra-list{display:grid;gap:10px}.rv-extra{display:grid;grid-template-columns:1fr auto auto;align-items:center;gap:12px;border:1px solid #dce6ef;border-radius:12px;padding:13px}.rv-extra strong{color:#10253d}.rv-extra small{display:block;color:#7b90a2;margin-top:3px}.rv-extra-price{font-weight:900;color:#082f5f;white-space:nowrap}.rv-extra select{border:1px solid #cfdbe5;border-radius:9px;padding:8px 10px;background:#fff;color:#15344f}.rv-save{border:0;background:#0f6bcb;color:#fff;border-radius:10px;padding:11px 15px;font-weight:900;cursor:pointer;margin-top:13px}.rv-saved{background:#eaf8f2;color:#14724f;padding:9px 11px;border-radius:9px;font-size:11px;margin-bottom:12px}
.rv-summary{background:#fff;border:1px solid #dce6ef;border-radius:16px;padding:18px;align-self:start;position:sticky;top:90px}.rv-summary h3{margin:0 0 12px}.rv-slice{padding:10px 0;border-top:1px solid #edf2f6}.rv-slice:first-of-type{border-top:0}.rv-route{display:flex;justify-content:space-between;gap:10px}.rv-route small{display:block;color:#8194a6;font-size:9px}
.rv-fare-line{display:flex;justify-content:space-between;gap:12px;color:#667d90;font-size:11px;padding:5px 0}.rv-price{display:flex;justify-content:space-between;align-items:end;border-top:1px solid #edf2f6;padding-top:14px;margin-top:8px}.rv-price strong{font:900 24px Manrope,Inter,sans-serif;color:#082f5f}
.rv-hold{background:#eef0ff;color:#4052b5;padding:10px;border-radius:9px;font-size:10px;margin-top:12px}.rv-actions{display:grid;gap:9px;margin-top:14px}.rv-btn{display:block;text-align:center;text-decoration:none;border-radius:10px;padding:12px;font-size:11px;font-weight:900}.rv-primary{background:#082f5f;color:#fff!important}.rv-secondary{border:1px solid #d5e0e9;color:#536b80!important}.rv-pdf{border:1px solid #c8d8e5;color:#082f5f!important;background:#f8fbfd}
.rv-payment-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.rv-pay-option{border:1px solid #dce6ef;border-radius:12px;padding:14px;background:#fbfdff}.rv-pay-option.active{border-color:#9ec1e8;background:#f2f8ff}.rv-pay-option strong{display:block;color:#10253d;margin-bottom:5px}.rv-pay-option small{display:block;color:#73899d;line-height:1.45}.rv-pay-status{display:inline-block;margin-top:9px;padding:5px 8px;border-radius:999px;background:#eaf8f2;color:#14724f;font-size:9px;font-weight:900}.rv-pay-status.wait{background:#fff4df;color:#8b6418}.rv-payment-note{margin-top:12px;background:#fff8e8;border:1px solid #f0dfb0;color:#72571a;border-radius:10px;padding:10px 12px;font-size:10px;line-height:1.5}.rv-error{background:#fff0f2;border:1px solid #ffd0d7;color:#b52d43;padding:16px;border-radius:11px}
@media(max-width:800px){.rv-grid{grid-template-columns:1fr}.rv-summary{position:static}.rv-head{display:block}.rv-preview{margin-top:12px}.rv-extra{grid-template-columns:1fr}.rv-extra-price{white-space:normal}}
</style>

<section class="rv-page">
<div class="rv-wrap">

    <div class="rv-head">
        <div>
            <h1>Review your booking</h1>
            <p>Check passenger details, baggage and the refreshed fare before continuing.</p>
        </div>
        <?php if(!$error): ?>
            <a class="rv-preview" href="flight-booking-preview.php?offer_id=<?=urlencode($offerId)?>">▣ PDF Booking Preview</a>
        <?php endif; ?>
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
                        <strong><?=h(trim(($p['given_name'] ?? '').' '.($p['family_name'] ?? '')))?></strong>
                        <small>
                            <?=h(ucfirst((string)($p['type'] ?? 'passenger')))?>
                            · DOB <?=h((string)($p['born_on'] ?? ''))?>
                            <?php if(!empty($p['passport_number'])): ?>
                                · Passport <?=h(rv_mask_passport((string)$p['passport_number']))?>
                            <?php endif; ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="post" class="rv-card" id="baggage-form">
                <h2>Baggage & Extras</h2>
                <div class="rv-bag-note">Only baggage returned by the airline/Duffel is shown. Weight is not invented when the airline does not provide it.</div>

                <?php if($savedExtras): ?><div class="rv-saved">✓ Extra baggage selection saved for this booking.</div><?php endif; ?>

                <strong style="display:block;margin-bottom:9px;color:#10253d">Included baggage</strong>
                <div class="rv-included">
                    <?php if($includedBags): ?>
                        <?php foreach($includedBags as $bag): ?>
                            <div class="rv-included-row">
                                <div>
                                    <strong><?=h(ucwords(str_replace('_',' ',(string)$bag['type'])))?></strong>
                                    <small><?=h(rv_passenger_names($offer, [$bag['passenger_id']]))?> · <?=h($bag['route'])?></small>
                                </div>
                                <div class="rv-bag-ok">✓ <?=h((string)$bag['quantity'])?> included</div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="rv-included-row"><div><strong>No confirmed included baggage shown</strong><small>The refreshed airline offer did not return an included baggage quantity.</small></div></div>
                    <?php endif; ?>
                </div>

                <strong style="display:block;margin-bottom:9px;color:#10253d">Add checked baggage</strong>
                <?php if($availableBags): ?>
                    <div class="rv-extra-list">
                        <?php foreach($availableBags as $service):
                            $sid = (string)($service['id'] ?? '');
                            $maxQty = max(1, (int)($service['maximum_quantity'] ?? 1));
                            $selectedQty = max(0, min($maxQty, (int)($selectedServices[$sid] ?? 0)));
                        ?>
                            <div class="rv-extra">
                                <div>
                                    <strong>Extra checked bag</strong>
                                    <small><?=h(rv_passenger_names($offer, $service['passenger_ids'] ?? []))?> · <?=h(rv_segment_label($offer, $service['segment_ids'] ?? []))?></small>
                                </div>
                                <div class="rv-extra-price"><?=rv_money($service['total_amount'] ?? 0, $service['total_currency'] ?? $offer['total_currency'] ?? 'EUR')?> / bag</div>
                                <select name="extra_bags[<?=h($sid)?>]" class="extra-bag-select" data-price="<?=h((string)($service['total_amount'] ?? '0'))?>">
                                    <?php for($q=0;$q<=$maxQty;$q++): ?>
                                        <option value="<?=$q?>" <?=$q===$selectedQty?'selected':''?>><?=$q===0?'No extra bag':$q.' bag'.($q>1?'s':'')?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="rv-save" type="submit" name="save_extras" value="1">Save baggage selection</button>
                <?php else: ?>
                    <div class="rv-included-row"><div><strong>Extra checked baggage is not offered on this fare</strong><small>Duffel did not return any additional baggage service for this refreshed offer.</small></div></div>
                <?php endif; ?>
            </form>

            <div class="rv-card" id="payment-section">
                <h2>Payment & Hold</h2>
                <div class="rv-payment-grid">
                    <div class="rv-pay-option <?=$holdEligible?'active':''?>">
                        <strong>Hold Booking</strong>
                        <small>Available only when the airline allows delayed payment for this refreshed fare.</small>
                        <?php if($holdEligible): ?>
                            <span class="rv-pay-status">AVAILABLE</span>
                            <?php if($holdDeadline): ?><small style="margin-top:7px">Pay-by: <?=h((string)$holdDeadline)?></small><?php endif; ?>
                        <?php else: ?>
                            <span class="rv-pay-status wait">NOT AVAILABLE ON THIS FARE</span>
                        <?php endif; ?>
                    </div>
                    <div class="rv-pay-option <?=!$holdEligible?'active':''?>">
                        <strong>Pay Now</strong>
                        <small>Use secure payment before creating the final Duffel airline order when instant payment is required.</small>
                        <span class="rv-pay-status <?=!$holdEligible?'':'wait'?>"><?=!$holdEligible?'REQUIRED / READY FOR NEXT STEP':'OPTIONAL AFTER SELECTION'?></span>
                    </div>
                </div>
                <div class="rv-payment-note">
                    Payment collection and final Duffel order creation are not activated yet. This section is now part of the booking flow so we can connect the secure payment/hold action without changing the customer layout again.
                </div>
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

            <div class="rv-fare-line"><span>Flight fare</span><strong><?=rv_money($baseTotal, $offer['total_currency'] ?? 'EUR')?></strong></div>
            <div class="rv-fare-line"><span>Extra baggage</span><strong id="extra-total"><?=rv_money($extraTotal, $offer['total_currency'] ?? 'EUR')?></strong></div>

            <div class="rv-price">
                <span>Total</span>
                <strong id="grand-total"><?=rv_money($grandTotal, $offer['total_currency'] ?? 'EUR')?></strong>
            </div>

            <?php if($holdEligible): ?>
                <div class="rv-hold">
                    ✓ This offer reports hold eligibility.
                    <?php if($holdDeadline): ?><br>Current pay-by: <?=h((string)$holdDeadline)?><?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="rv-actions">
                <a class="rv-btn rv-pdf" href="flight-booking-preview.php?offer_id=<?=urlencode($offerId)?>">PDF Booking Preview</a>
                <a class="rv-btn rv-primary" href="#payment-section">Continue to payment / hold</a>
                <a class="rv-btn rv-secondary" href="flight-passengers.php?offer_id=<?=urlencode($offerId)?>">Edit passengers</a>
            </div>
        </aside>

    </div>

    <?php endif; ?>

</div>
</section>

<script>
(function(){
    const base = <?=json_encode($baseTotal)?>;
    const currency = <?=json_encode((string)($offer['total_currency'] ?? 'EUR'))?>;
    const selects = document.querySelectorAll('.extra-bag-select');
    const extraEl = document.getElementById('extra-total');
    const grandEl = document.getElementById('grand-total');
    function money(v){ return currency + ' ' + Number(v).toFixed(2); }
    function recalc(){
        let extra = 0;
        selects.forEach(s => extra += Number(s.dataset.price || 0) * Number(s.value || 0));
        if(extraEl) extraEl.textContent = money(extra);
        if(grandEl) grandEl.textContent = money(base + extra);
    }
    selects.forEach(s => s.addEventListener('change', recalc));
    recalc();
})();
</script>

<?php site_footer(); ?>
