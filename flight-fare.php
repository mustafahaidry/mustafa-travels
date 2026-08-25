<?php
require_once __DIR__ . '/partials.php';
require_once __DIR__ . '/api/duffel.php';

$offerId = trim($_GET['offer_id'] ?? '');
$error = '';
$offer = [];

if ($offerId === '') {
    $error = 'Missing offer ID.';
} else {
    $api = mt_duffel_get_offer($offerId, true);
    if (!$api['ok']) {
        $error = $api['error'];
        error_log('FLIGHT FARE DUFFEL | HTTP '.$api['status'].' | '.json_encode($api['data']));
    } else {
        $offer = $api['data']['data'] ?? [];
    }
}

site_header('Fare Options');
?>

<style>
.ff-page{background:#f4f8fb;min-height:700px;padding:42px 0 80px}.ff-wrap{width:min(1000px,calc(100% - 32px));margin:auto}
.ff-card{background:#fff;border:1px solid #dce6ef;border-radius:16px;padding:22px;box-shadow:0 12px 35px rgba(8,47,95,.06)}
.ff-card h1{margin:0 0 8px;color:#10253d}.ff-muted{color:#73879a}.ff-price{font:900 30px Manrope,Inter,sans-serif;color:#082f5f;margin:18px 0}
.ff-service{border-top:1px solid #edf2f6;padding:12px 0}.ff-next{display:inline-flex;background:#082f5f;color:#fff!important;text-decoration:none;padding:12px 18px;border-radius:9px;font-weight:900;font-size:11px}
.ff-error{background:#fff0f2;border:1px solid #ffd0d7;color:#b52d43;padding:20px;border-radius:12px}
</style>

<section class="ff-page">
<div class="ff-wrap">
<?php if($error): ?>
    <div class="ff-error"><strong>Fare error:</strong> <?=h($error)?></div>
<?php else: ?>
    <div class="ff-card">
        <h1>Fare & baggage</h1>
        <div class="ff-muted">Selected fare has been refreshed from Duffel before checkout.</div>

        <div class="ff-price">
            <?=h((string)($offer['total_currency'] ?? 'EUR'))?>
            <?=number_format((float)($offer['total_amount'] ?? 0),2)?>
        </div>

        <?php
        $services = $offer['available_services'] ?? [];
        if (!$services):
        ?>
            <div class="ff-service">No optional paid services were returned for this fare.</div>
        <?php else: ?>
            <?php foreach($services as $service): ?>
                <div class="ff-service">
                    <strong><?=h(ucwords(str_replace('_',' ',(string)($service['type'] ?? 'Service'))))?></strong>
                    <?php if(isset($service['total_amount'])): ?>
                        — <?=h((string)($service['total_currency'] ?? ''))?> <?=h((string)$service['total_amount'])?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <p class="ff-muted">Next phase: passenger details, hold eligibility, Stripe payment and reservation creation.</p>
        <a class="ff-next" href="flight-passengers.php?offer_id=<?=urlencode($offerId)?>">Continue to passengers</a>
    </div>
<?php endif; ?>
</div>
</section>

<?php site_footer(); ?>
