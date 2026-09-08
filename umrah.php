<?php

require_once __DIR__ . '/partials.php';

/*
|--------------------------------------------------------------------------
| LOAD ACTIVE UMRAH PACKAGES
|--------------------------------------------------------------------------
*/

$packs = sb_select(
    'umrah_packages',
    'select=*&active=eq.true&order=featured.desc,id.desc'
);

site_header('Umrah & Hajj');

?>



<style>
:root{
  --u-navy:#062b4d; --u-navy2:#0a4168; --u-teal:#0d7692;
  --u-gold:#d9a62e; --u-gold2:#f3c85a; --u-ink:#0b2946;
  --u-muted:#687f93; --u-line:#dce6ee; --u-cream:#fffaf0;
}
.umrah-premium-hero{
  position:relative; overflow:hidden; min-height:520px; display:flex; align-items:center;
  color:#fff; background:
  radial-gradient(circle at 84% 26%,rgba(243,200,90,.18),transparent 25%),
  linear-gradient(110deg,#032744 0%,#06426b 58%,#0b7b91 100%);
}
.umrah-premium-hero:before{
  content:""; position:absolute; inset:0; opacity:.18;
  background-image:linear-gradient(45deg,transparent 46%,rgba(255,255,255,.35) 47%,transparent 48%),
                   linear-gradient(-45deg,transparent 46%,rgba(255,255,255,.22) 47%,transparent 48%);
  background-size:54px 54px;
}
.umrah-premium-hero:after{
  content:""; position:absolute; width:470px; height:470px; border:1px solid rgba(243,200,90,.25);
  border-radius:50%; right:8%; top:20px; box-shadow:0 0 0 46px rgba(255,255,255,.025),0 0 0 92px rgba(255,255,255,.018);
}
.umrah-premium-hero .container{position:relative;z-index:2}
.umrah-hero-grid{display:grid;grid-template-columns:1.15fr .85fr;gap:70px;align-items:center}
.umrah-kicker{display:inline-flex;align-items:center;gap:10px;color:var(--u-gold2);font-size:12px;font-weight:900;letter-spacing:2px;text-transform:uppercase}
.umrah-kicker:before{content:"";width:32px;height:1px;background:var(--u-gold2)}
.umrah-premium-hero h1{font-size:58px;line-height:1.02;max-width:720px;margin:18px 0 18px;letter-spacing:-1.8px}
.umrah-premium-hero p{font-size:18px;line-height:1.7;max-width:650px;color:#dcebf5}
.umrah-hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}
.u-btn{display:inline-flex;align-items:center;justify-content:center;padding:14px 20px;border-radius:9px;font-weight:850;text-decoration:none}
.u-btn-gold{background:linear-gradient(135deg,var(--u-gold2),#e5ad29);color:#082b47}
.u-btn-ghost{border:1px solid rgba(255,255,255,.4);color:#fff;background:rgba(255,255,255,.06)}
.umrah-trust-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:28px}
.umrah-trust-row span{border:1px solid rgba(255,255,255,.22);background:rgba(2,30,53,.32);padding:8px 12px;border-radius:999px;font-size:11px;font-weight:750}
.umrah-hero-art{position:relative;min-height:350px;display:flex;align-items:center;justify-content:center}
.umrah-photo-frame{position:relative;width:100%;max-width:520px;height:340px;border-radius:26px;overflow:hidden;border:1px solid rgba(243,200,90,.38);box-shadow:0 32px 80px rgba(0,20,40,.38)}
.umrah-photo-frame img{width:100%;height:100%;object-fit:cover;object-position:center;display:block}
.umrah-photo-frame:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(3,30,52,.02) 35%,rgba(3,30,52,.72) 100%)}
.umrah-photo-caption{position:absolute;z-index:2;left:22px;right:22px;bottom:20px;display:flex;justify-content:space-between;align-items:end;gap:14px;color:#fff}
.umrah-photo-caption strong{display:block;font-size:17px}
.umrah-photo-caption span{display:block;margin-top:4px;color:#f2d47f;font-size:10px;font-weight:900;letter-spacing:1.6px;text-transform:uppercase}
.umrah-photo-badge{background:rgba(4,38,65,.78);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.22);padding:9px 12px;border-radius:999px;font-size:10px;font-weight:800;white-space:nowrap}
.umrah-intro{padding:78px 0 70px;background:#fff}
.umrah-section-head{text-align:center;max-width:820px;margin:0 auto 38px}
.umrah-section-head .eyebrow{color:#b78312}
.umrah-section-head h2{font-size:42px;line-height:1.08;color:var(--u-ink);margin:10px 0 12px}
.umrah-section-head p{color:var(--u-muted);font-size:16px;line-height:1.7}
.umrah-service-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
.umrah-service-card{position:relative;background:#fff;border:1px solid var(--u-line);border-radius:18px;padding:24px;min-height:180px;box-shadow:0 14px 35px rgba(13,47,78,.06);overflow:hidden}
.umrah-service-card:nth-child(2),.umrah-service-card:nth-child(3){background:linear-gradient(145deg,#fffdf8,#fff7df);border-color:#edd38c}
.umrah-service-card:after{content:"";position:absolute;width:88px;height:88px;border-radius:50%;right:-28px;top:-28px;background:#edf7ff}
.umrah-service-card:nth-child(2):after,.umrah-service-card:nth-child(3):after{background:#ffe9a8}
.umrah-service-icon{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;background:#eaf6ff;color:#087bb3;font-size:19px;margin-bottom:18px}
.umrah-service-card:nth-child(2) .umrah-service-icon,.umrah-service-card:nth-child(3) .umrah-service-icon{background:#ffe9a8;color:#8d6200}
.umrah-service-card h3{margin:0 0 8px;color:var(--u-ink);font-size:17px}
.umrah-service-card p{margin:0;color:var(--u-muted);font-size:13px;line-height:1.65}
.umrah-quote-band{margin-top:28px;border-radius:18px;padding:22px 26px;background:linear-gradient(105deg,#052f53,#0b6689);color:#fff;display:flex;justify-content:space-between;align-items:center;gap:20px}
.umrah-quote-band strong{font-size:18px}.umrah-quote-band span{display:block;color:#cfe5ef;font-size:13px;margin-top:3px}
.umrah-market-section{background:linear-gradient(180deg,#f4f8fb,#edf4f8);padding:82px 0}
.umrah-market-heading{text-align:center;max-width:760px;margin:0 auto 38px}
.umrah-market-heading h2{margin:7px 0 10px;font-size:42px;line-height:1.08;color:var(--u-ink)}
.umrah-market-heading p{color:var(--u-muted);margin:0 auto;line-height:1.7}
.umrah-market-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:24px;align-items:start}
.umrah-market-card{background:#fff;border-radius:22px;overflow:hidden;border:1px solid #dce7ef;box-shadow:0 18px 48px rgba(10,45,75,.09);transition:.25s ease}
.umrah-market-card:hover{transform:translateY(-6px);box-shadow:0 28px 60px rgba(10,45,75,.15)}
.umrah-card-image{width:100%;height:250px;position:relative;overflow:hidden;background:linear-gradient(135deg,#073c63,#0d91ad)}
.umrah-card-image:after{content:"";position:absolute;inset:45% 0 0;background:linear-gradient(transparent,rgba(3,30,50,.72))}
.umrah-card-image img{width:100%;height:100%;display:block;object-fit:cover;transition:transform .4s ease}
.umrah-market-card:hover .umrah-card-image img{transform:scale(1.035)}
.umrah-image-placeholder{width:100%;height:100%;position:relative;background-size:cover;background-position:center;background-image:linear-gradient(180deg,rgba(4,40,67,.02),rgba(4,40,67,.18)),url("https://images.unsplash.com/photo-1720549973451-018d3623b55a?auto=format&fit=crop&fm=jpg&q=82&w=1600")}.umrah-image-placeholder:after{content:"Makkah · Umrah Journey";position:absolute;left:16px;bottom:16px;z-index:2;color:#fff;font-size:12px;font-weight:850;text-shadow:0 2px 12px rgba(0,0,0,.45)}
.umrah-featured{position:absolute;z-index:3;top:14px;left:14px;background:var(--u-gold2);color:#17324b;padding:7px 11px;border-radius:999px;font-size:11px;font-weight:900}
.umrah-duration-badge{position:absolute;z-index:3;right:14px;bottom:14px;background:rgba(255,255,255,.94);color:#173650;padding:7px 11px;border-radius:999px;font-size:11px;font-weight:850}
.umrah-card-body{padding:22px}
.umrah-card-top{display:flex;justify-content:space-between;align-items:center;gap:10px;border-bottom:1px solid #edf1f4;padding-bottom:12px;margin-bottom:14px}
.umrah-stars{color:#e4ad26;letter-spacing:1px;font-size:12px}.umrah-days{color:#75889a;font-size:11px}
.umrah-location{color:#7b8d9d;font-size:11px;margin-bottom:7px;text-transform:uppercase;letter-spacing:.4px}
.umrah-card-body h3{margin:0 0 8px;font-size:22px;line-height:1.25;color:var(--u-ink)}
.umrah-airline{color:#087eb7;font-weight:850;font-size:13px;margin-bottom:13px}
.umrah-dates{background:#f4f8fb;border:1px solid #e5edf3;border-radius:10px;padding:10px 12px;color:#526a80;font-size:12px;margin-bottom:13px}
.umrah-hotels{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin:13px 0}
.umrah-hotel{background:#fbfcfd;border:1px solid #e4ebf1;border-radius:12px;padding:11px}
.umrah-hotel-title{color:#9a6b05;font-weight:850;font-size:11px;margin-bottom:5px}
.umrah-hotel-name{color:#263f56;font-size:12px;font-weight:800}.umrah-hotel-detail{color:#718599;font-size:10px;margin-top:4px}
.umrah-baggage,.umrah-description{color:#657b8f;font-size:11px;line-height:1.55;margin:11px 0}
.umrah-included,.umrah-excluded{padding:9px 11px;border-radius:9px;font-size:10px;line-height:1.55;margin-top:8px}
.umrah-included{background:#edf9f2;color:#256641}.umrah-excluded{background:#fff2f2;color:#8f3a3a}
.umrah-room-prices{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-top:12px}
.umrah-room-price{background:#f5f8fa;border:1px solid #e8eef2;padding:7px 9px;border-radius:8px;font-size:10px;color:#536a7d}.umrah-room-price strong{color:#173650}
.umrah-price-area{margin-top:16px;border-top:1px solid #edf1f4;padding-top:14px}
.umrah-from{color:#8b9aa8;font-size:10px;text-transform:uppercase;letter-spacing:.8px}.umrah-price{font-size:30px;font-weight:950;color:var(--u-ink);margin-top:2px}.umrah-per-person{color:#8998a6;font-size:10px}
.umrah-more-btn{display:block;text-align:center;background:linear-gradient(135deg,#0a3b63,#075986);color:#fff!important;text-decoration:none;padding:13px 18px;border-radius:10px;font-weight:850;font-size:12px;margin-top:16px}.umrah-more-btn:hover{background:#052f53}
.umrah-empty{background:#fff;padding:40px;border-radius:18px;text-align:center;color:#718397;border:1px solid var(--u-line)}
@media(max-width:1000px){.umrah-hero-grid{grid-template-columns:1fr}.umrah-hero-art{display:none}.umrah-service-grid{grid-template-columns:repeat(2,1fr)}.umrah-market-grid{grid-template-columns:repeat(2,1fr)}}
@media(max-width:650px){.umrah-premium-hero{min-height:500px}.umrah-premium-hero h1{font-size:42px}.umrah-intro,.umrah-market-section{padding:55px 0}.umrah-section-head h2,.umrah-market-heading h2{font-size:32px}.umrah-service-grid,.umrah-market-grid{grid-template-columns:1fr}.umrah-quote-band{align-items:flex-start;flex-direction:column}.umrah-card-image{height:270px}.umrah-hotels{grid-template-columns:1fr}}
</style>




<!-- PREMIUM UMRAH HERO -->
<section class="umrah-premium-hero">
  <div class="container">
    <div class="umrah-hero-grid">
      <div>
        <div class="umrah-kicker">Umrah & Hajj · From Barcelona</div>
        <h1>Your sacred journey,<br>arranged with care.</h1>
        <p>Thoughtfully planned Umrah journeys with flights, Makkah & Madinah hotels, visa guidance and transport options — with personal support from Barcelona.</p>
        <div class="umrah-hero-actions">
          <a class="u-btn u-btn-gold" href="#umrah-packages">View Umrah Packages →</a>
          <a class="u-btn u-btn-ghost" href="contact.php?service=Umrah">Request a Custom Quote</a>
        </div>
        <div class="umrah-trust-row">
          <span>Barcelona-based support</span>
          <span>Makkah & Madinah stays</span>
          <span>Visa & transport guidance</span>
          <span>Personal assistance</span>
        </div>
      </div>
      <div class="umrah-hero-art" aria-hidden="true">
        <div class="umrah-photo-frame">
          <img src="https://images.unsplash.com/photo-1720549973451-018d3623b55a?auto=format&fit=crop&fm=jpg&q=82&w=1600" alt="Masjid al-Haram and the Kaaba in Makkah">
          <div class="umrah-photo-caption">
            <div><strong>Makkah Al-Mukarramah</strong><span>Umrah journeys from Barcelona</span></div>
            <div class="umrah-photo-badge">Personal Support</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PREMIUM JOURNEY SERVICES -->
<section class="umrah-intro">
  <div class="container">
    <div class="umrah-section-head">
      <span class="eyebrow dark">YOUR JOURNEY, OUR RESPONSIBILITY</span>
      <h2>Everything needed for a smoother Umrah journey.</h2>
      <p>Choose a complete package or let us build one around your preferred dates, budget, hotel distance and travel requirements.</p>
    </div>
    <div class="umrah-service-grid">
      <div class="umrah-service-card"><div class="umrah-service-icon">✈</div><h3>Flights from Barcelona</h3><p>Airline and routing options selected around your dates and baggage needs.</p></div>
      <div class="umrah-service-card"><div class="umrah-service-icon">🕋</div><h3>Makkah Hotels</h3><p>Walking-distance and shuttle options, from economy stays to premium properties.</p></div>
      <div class="umrah-service-card"><div class="umrah-service-icon">☾</div><h3>Madinah Hotels</h3><p>Carefully selected stays with clear distance and accommodation information.</p></div>
      <div class="umrah-service-card"><div class="umrah-service-icon">✓</div><h3>Visa & Transport</h3><p>Visa guidance plus airport and intercity transport options where applicable.</p></div>
    </div>
    <div class="umrah-quote-band">
      <div><strong>Need a package built around your dates?</strong><span>Tell us your passengers, travel dates and preferred hotel distance.</span></div>
      <a class="u-btn u-btn-gold" href="contact.php?service=Umrah">Build My Umrah Quote →</a>
    </div>
  </div>
</section>

<!-- CURRENT PACKAGES -->
<section class="umrah-market-section" id="umrah-packages">
  <div class="container">
    <div class="umrah-market-heading">
      <span class="eyebrow dark">LIVE UMRAH PACKAGES</span>
      <h2>Featured Umrah Packages from Barcelona</h2>
      <p>Compare current journeys including flights, Makkah and Madinah hotels, baggage, room options and package inclusions.</p>
    </div>


        <?php if (!empty($packs)): ?>


        <div class="umrah-market-grid">


            <?php foreach ($packs as $p): ?>


            <?php

            /*
            |--------------------------------------------------------------------------
            | PRICE CALCULATION
            |--------------------------------------------------------------------------
            */

            $startingPrice = 0;

            if (
                isset($p['quad_price']) &&
                (float)$p['quad_price'] > 0
            ) {

                $startingPrice =
                    (float)$p['quad_price'];

            } elseif (
                isset($p['triple_price']) &&
                (float)$p['triple_price'] > 0
            ) {

                $startingPrice =
                    (float)$p['triple_price'];

            } elseif (
                isset($p['double_price']) &&
                (float)$p['double_price'] > 0
            ) {

                $startingPrice =
                    (float)$p['double_price'];

            } elseif (
                isset($p['single_price']) &&
                (float)$p['single_price'] > 0
            ) {

                $startingPrice =
                    (float)$p['single_price'];

            }


            /*
            |--------------------------------------------------------------------------
            | WHATSAPP MESSAGE
            |--------------------------------------------------------------------------
            */

            $whatsappMessage =
                'Hello Mustafa Travels, '
                . 'I need more information about this Umrah package: '
                . ($p['title'] ?? 'Umrah Package');

            ?>


            <article class="umrah-market-card">


                <!-- IMAGE -->

                <div class="umrah-card-image">


                    <?php if (!empty($p['image_url'])): ?>


                    <img
                        src="<?= h($p['image_url']) ?>"
                        alt="<?= h(
                            $p['title']
                            ?? 'Umrah Package'
                        ) ?>"
                    >


                    <?php else: ?>


                    <div class="umrah-image-placeholder" role="img" aria-label="Masjid al-Haram in Makkah"></div>


                    <?php endif; ?>


                    <?php if (!empty($p['featured'])): ?>


                    <span class="umrah-featured">
                        Featured
                    </span>


                    <?php endif; ?>


                    <?php if (!empty($p['duration'])): ?>


                    <span class="umrah-duration-badge">

                        <?= h($p['duration']) ?>

                    </span>


                    <?php endif; ?>


                </div>


                <!-- CONTENT -->

                <div class="umrah-card-body">


                    <div class="umrah-card-top">


                        <span class="umrah-stars">
                            ★★★★★
                        </span>


                        <span class="umrah-days">

                            ◷ <?= h(
                                $p['duration']
                                ?? ''
                            ) ?>

                        </span>


                    </div>


                    <!-- LOCATION -->

                    <div class="umrah-location">

                        📍
                        <?= h(
                            $p['departure_city']
                            ?? 'Barcelona'
                        ) ?>

                        → Makkah / Madinah

                    </div>


                    <!-- TITLE -->

                    <h3>

                        <?= h(
                            $p['title']
                            ?? 'Umrah Package'
                        ) ?>

                    </h3>


                    <!-- AIRLINE -->

                    <?php if (!empty($p['airline'])): ?>


                    <div class="umrah-airline">

                        ✈ <?= h($p['airline']) ?>

                    </div>


                    <?php endif; ?>


                    <!-- DATES -->

                    <?php if (
                        !empty($p['travel_date']) ||
                        !empty($p['return_date'])
                    ): ?>


                    <div class="umrah-dates">

                        📅

                        <?= h(
                            $p['travel_date']
                            ?? ''
                        ) ?>


                        <?php if (
                            !empty($p['return_date'])
                        ): ?>

                            →

                            <?= h(
                                $p['return_date']
                            ) ?>

                        <?php endif; ?>


                    </div>


                    <?php endif; ?>


                    <!-- HOTELS -->

                    <div class="umrah-hotels">


                        <!-- MAKKAH -->

                        <div class="umrah-hotel">


                            <div class="umrah-hotel-title">

                                🕋 Makkah Hotel

                            </div>


                            <div class="umrah-hotel-name">

                                <?= h(
                                    $p['makkah_hotel']
                                    ?? 'Hotel TBA'
                                ) ?>

                            </div>


                            <div class="umrah-hotel-detail">


                                <?php if (
                                    !empty($p['makkah_nights'])
                                ): ?>


                                    <?= h(
                                        (string)
                                        $p['makkah_nights']
                                    ) ?>

                                    Nights


                                <?php endif; ?>


                                <?php if (
                                    !empty($p['makkah_distance'])
                                ): ?>


                                    ·

                                    <?= h(
                                        $p['makkah_distance']
                                    ) ?>


                                <?php endif; ?>


                            </div>


                        </div>


                        <!-- MADINAH -->

                        <div class="umrah-hotel">


                            <div class="umrah-hotel-title">

                                🕌 Madinah Hotel

                            </div>


                            <div class="umrah-hotel-name">

                                <?= h(
                                    $p['madinah_hotel']
                                    ?? 'Hotel TBA'
                                ) ?>

                            </div>


                            <div class="umrah-hotel-detail">


                                <?php if (
                                    !empty($p['madinah_nights'])
                                ): ?>


                                    <?= h(
                                        (string)
                                        $p['madinah_nights']
                                    ) ?>

                                    Nights


                                <?php endif; ?>


                                <?php if (
                                    !empty($p['madinah_distance'])
                                ): ?>


                                    ·

                                    <?= h(
                                        $p['madinah_distance']
                                    ) ?>


                                <?php endif; ?>


                            </div>


                        </div>


                    </div>


                    <!-- BAGGAGE -->

                    <?php if (!empty($p['baggage'])): ?>


                    <div class="umrah-baggage">

                        🧳 <?= h($p['baggage']) ?>

                    </div>


                    <?php endif; ?>


                    <!-- DESCRIPTION -->

                    <?php if (!empty($p['description'])): ?>


                    <div class="umrah-description">

                        <?= nl2br(
                            h($p['description'])
                        ) ?>

                    </div>


                    <?php endif; ?>


                    <!-- INCLUDED -->

                    <?php if (!empty($p['included'])): ?>


                    <div class="umrah-included">

                        <strong>
                            ✓ Included
                        </strong>

                        <br>

                        <?= nl2br(
                            h($p['included'])
                        ) ?>

                    </div>


                    <?php endif; ?>


                    <!-- NOT INCLUDED -->

                    <?php if (
                        !empty($p['not_included'])
                    ): ?>


                    <div class="umrah-excluded">

                        <strong>
                            ✕ Not Included
                        </strong>

                        <br>

                        <?= nl2br(
                            h($p['not_included'])
                        ) ?>

                    </div>


                    <?php endif; ?>


                    <!-- ROOM PRICES -->

                    <div class="umrah-room-prices">


                        <?php if (
                            (float)(
                                $p['quad_price']
                                ?? 0
                            ) > 0
                        ): ?>


                        <div class="umrah-room-price">

                            <strong>Quad</strong>

                            <?= h(
                                $p['currency']
                                ?? 'EUR'
                            ) ?>

                            <?= number_format(
                                (float)
                                $p['quad_price'],
                                0
                            ) ?>

                        </div>


                        <?php endif; ?>


                        <?php if (
                            (float)(
                                $p['triple_price']
                                ?? 0
                            ) > 0
                        ): ?>


                        <div class="umrah-room-price">

                            <strong>Triple</strong>

                            <?= h(
                                $p['currency']
                                ?? 'EUR'
                            ) ?>

                            <?= number_format(
                                (float)
                                $p['triple_price'],
                                0
                            ) ?>

                        </div>


                        <?php endif; ?>


                        <?php if (
                            (float)(
                                $p['double_price']
                                ?? 0
                            ) > 0
                        ): ?>


                        <div class="umrah-room-price">

                            <strong>Double</strong>

                            <?= h(
                                $p['currency']
                                ?? 'EUR'
                            ) ?>

                            <?= number_format(
                                (float)
                                $p['double_price'],
                                0
                            ) ?>

                        </div>


                        <?php endif; ?>


                        <?php if (
                            (float)(
                                $p['single_price']
                                ?? 0
                            ) > 0
                        ): ?>


                        <div class="umrah-room-price">

                            <strong>Single</strong>

                            <?= h(
                                $p['currency']
                                ?? 'EUR'
                            ) ?>

                            <?= number_format(
                                (float)
                                $p['single_price'],
                                0
                            ) ?>

                        </div>


                        <?php endif; ?>


                    </div>


                    <!-- STARTING PRICE -->

                    <?php if (
                        $startingPrice > 0
                    ): ?>


                    <div class="umrah-price-area">


                        <div class="umrah-from">
                            From
                        </div>


                        <div class="umrah-price">

                            <?= h(
                                $p['currency']
                                ?? 'EUR'
                            ) ?>

                            <?= number_format(
                                $startingPrice,
                                0
                            ) ?>

                        </div>


                        <div class="umrah-per-person">
                            per person
                        </div>


                    </div>


                    <?php endif; ?>


                    <!-- BUTTON -->

                    <a
                        class="umrah-more-btn"
                        href="https://wa.me/<?= WHATSAPP ?>?text=<?= urlencode(
                            $whatsappMessage
                        ) ?>"
                        target="_blank"
                    >

                        More Information →

                    </a>


                </div>


            </article>


            <?php endforeach; ?>


        </div>


        <?php else: ?>


        <div class="umrah-empty">

            <h3>
                New Umrah packages coming soon
            </h3>

            <p>
                Contact Mustafa Travels for a
                customised Umrah quotation.
            </p>

        </div>


        <?php endif; ?>


    </div>

</section>


<?php

site_footer();

?>
