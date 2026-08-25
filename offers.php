<?php require_once __DIR__.'/partials.php'; $offers=$pdo->query("SELECT * FROM offers WHERE active=1 ORDER BY featured DESC,id DESC")->fetchAll(PDO::FETCH_ASSOC); site_header('Special Offers'); ?>
<section class="page-hero offers-hero"><div class="container"><span class="eyebrow">UPDATED REGULARLY</span><h1>Latest Flight & Travel Offers</h1><p>Contact us to confirm live availability before booking.</p></div></section>
<section class="section"><div class="container"><div class="offers-grid">
<?php if(!$offers): ?><div class="empty-state"><h3>No offers published yet.</h3><p>New deals can be uploaded from the admin panel at any time.</p></div>
<?php else: foreach($offers as $o): ?>
<article class="offer-card"><div class="offer-media" <?php if($o['image']): ?>style="background-image:url('<?=h($o['image'])?>')"<?php endif; ?>><span class="offer-badge"><?=h($o['badge'] ?: 'Special Offer')?></span></div>
<div class="offer-body"><small><?=h($o['airline'])?></small><h3><?=h($o['title'])?></h3><p><?=h($o['subtitle'])?></p><div class="offer-meta"><span><?=h($o['travel_dates'])?></span><span><?=h($o['baggage'])?></span></div><div class="price"><?=h($o['currency'])?> <?=number_format((float)$o['price'],0)?></div><a class="btn btn-dark" href="https://wa.me/<?=WHATSAPP?>?text=<?=urlencode('Please check availability for: '.$o['title'])?>" target="_blank">Check Availability</a></div></article>
<?php endforeach; endif; ?>
</div></div></section>
<?php site_footer(); ?>