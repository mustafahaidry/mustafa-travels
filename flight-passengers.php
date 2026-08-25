<?php
require_once __DIR__ . '/partials.php';
$offerId = trim($_GET['offer_id'] ?? '');
site_header('Passenger Details');
?>
<section style="background:#f4f8fb;min-height:650px;padding:50px 0">
<div class="container">
<div style="background:white;border:1px solid #dce6ef;border-radius:16px;padding:28px;max-width:800px;margin:auto">
<h1 style="margin-top:0">Passenger details</h1>
<p>This is the next build phase. The selected Duffel offer ID is safely carried forward.</p>
<p><strong>Offer:</strong> <?=h($offerId)?></p>
<p>Next we will add Adult / Child / Infant passenger forms, Hold Now and Stripe checkout here.</p>
</div>
</div>
</section>
<?php site_footer(); ?>
