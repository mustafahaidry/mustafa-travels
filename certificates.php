<?php require_once __DIR__.'/partials.php'; $certs=$pdo->query("SELECT * FROM certificates WHERE active=1 ORDER BY sort_order,id")->fetchAll(PDO::FETCH_ASSOC); site_header('Certificates'); ?>
<section class="page-hero"><div class="container"><span class="eyebrow">PROFESSIONAL DEVELOPMENT</span><h1>Certificates & Industry Credentials</h1><p>Training and supplier credentials that support our professional service.</p></div></section>
<section class="section"><div class="container"><div class="cert-grid">
<?php if(!$certs): ?>
<div class="cert-card text-cert"><div class="cert-icon">E</div><h3>Expedia TAAP</h3><p>Registered agency credential / certificate can be uploaded from Admin.</p></div>
<div class="cert-card text-cert"><div class="cert-icon">T</div><h3>TBO Holidays</h3><p>Training / academy certificates can be uploaded from Admin.</p></div>
<?php else: foreach($certs as $c): ?><div class="cert-card"><?php if($c['image']): ?><img src="<?=h($c['image'])?>" alt="<?=h($c['title'])?>"><?php endif; ?><h3><?=h($c['title'])?></h3><p><?=h($c['issuer'])?></p></div><?php endforeach; endif; ?>
</div></div></section>
<?php site_footer(); ?>