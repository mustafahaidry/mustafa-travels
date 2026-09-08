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

/* =========================================================
   UMRAH PAGE
   ========================================================= */

.umrah-market-section {
    background: #f7f9fc;
    padding: 70px 0;
}

.umrah-market-heading {
    margin-bottom: 32px;
}

.umrah-market-heading h2 {
    margin: 5px 0 8px;
    font-size: 38px;
    color: #0c2947;
}

.umrah-market-heading p {
    color: #718397;
    max-width: 650px;
}


/* =========================================================
   PACKAGE GRID
   ========================================================= */

.umrah-market-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 28px;
}


/* =========================================================
   PACKAGE CARD
   ========================================================= */

.umrah-market-card {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid #e4ebf2;
    box-shadow: 0 15px 40px rgba(13, 47, 78, 0.08);
    transition: all .25s ease;
}

.umrah-market-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 22px 50px rgba(13, 47, 78, 0.14);
}


/* =========================================================
   IMAGE
   ========================================================= */

.umrah-card-image {
    width: 100%;
    height: 320px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(
        135deg,
        #07568b,
        #17a8d4
    );
}

.umrah-card-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
}

.umrah-image-placeholder {
    width: 100%;
    height: 100%;
    display: grid;
    place-items: center;
    font-size: 85px;
}


/* =========================================================
   BADGES
   ========================================================= */

.umrah-featured {
    position: absolute;
    top: 14px;
    left: 14px;
    background: #0682ad;
    color: #fff;
    padding: 6px 11px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 800;
    z-index: 2;
}

.umrah-duration-badge {
    position: absolute;
    right: 14px;
    bottom: 14px;
    background: #f5b400;
    color: #152d45;
    padding: 7px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
}


/* =========================================================
   CONTENT
   ========================================================= */

.umrah-card-body {
    padding: 21px;
}

.umrah-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    border-bottom: 1px dashed #dbe3ea;
    padding-bottom: 12px;
    margin-bottom: 14px;
}

.umrah-stars {
    color: #f6b500;
    letter-spacing: 1px;
    font-size: 14px;
}

.umrah-days {
    color: #77899b;
    font-size: 12px;
    white-space: nowrap;
}

.umrah-location {
    color: #8a9aab;
    font-size: 12px;
    margin-bottom: 6px;
}

.umrah-card-body h3 {
    margin: 0 0 8px;
    font-size: 22px;
    line-height: 1.25;
    color: #112c48;
}

.umrah-airline {
    color: #1286bd;
    font-weight: 800;
    font-size: 14px;
    margin-bottom: 14px;
}


/* =========================================================
   DATES
   ========================================================= */

.umrah-dates {
    background: #f5f8fb;
    border-radius: 10px;
    padding: 10px 12px;
    color: #526a80;
    font-size: 12px;
    margin-bottom: 13px;
}


/* =========================================================
   HOTELS
   ========================================================= */

.umrah-hotels {
    display: grid;
    gap: 10px;
    margin: 14px 0;
}

.umrah-hotel {
    background: #f6f9fc;
    border: 1px solid #e7edf3;
    border-radius: 12px;
    padding: 12px;
}

.umrah-hotel-title {
    color: #0c3358;
    font-weight: 800;
    font-size: 13px;
    margin-bottom: 4px;
}

.umrah-hotel-name {
    color: #263f56;
    font-size: 13px;
    font-weight: 700;
}

.umrah-hotel-detail {
    color: #718599;
    font-size: 11px;
    margin-top: 4px;
}


/* =========================================================
   BAGGAGE
   ========================================================= */

.umrah-baggage {
    color: #657b8f;
    font-size: 12px;
    margin: 12px 0;
}


/* =========================================================
   DESCRIPTION
   ========================================================= */

.umrah-description {
    color: #697e90;
    font-size: 12px;
    line-height: 1.6;
    margin: 10px 0;
}


/* =========================================================
   INCLUDED / NOT INCLUDED
   ========================================================= */

.umrah-included {
    background: #edf9f2;
    color: #256641;
    padding: 10px 12px;
    border-radius: 9px;
    font-size: 11px;
    line-height: 1.6;
    margin-top: 10px;
}

.umrah-excluded {
    background: #fff1f1;
    color: #8f3a3a;
    padding: 10px 12px;
    border-radius: 9px;
    font-size: 11px;
    line-height: 1.6;
    margin-top: 8px;
}


/* =========================================================
   PRICE
   ========================================================= */

.umrah-price-area {
    margin-top: 17px;
    border-top: 1px solid #edf1f4;
    padding-top: 15px;
}

.umrah-from {
    color: #8b9aa8;
    font-size: 11px;
}

.umrah-price {
    font-size: 28px;
    font-weight: 900;
    color: #f47a29;
    margin-top: 2px;
}

.umrah-per-person {
    color: #8998a6;
    font-size: 10px;
}


/* =========================================================
   ROOM PRICES
   ========================================================= */

.umrah-room-prices {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 7px;
    margin-top: 12px;
}

.umrah-room-price {
    background: #f6f9fc;
    padding: 7px 9px;
    border-radius: 7px;
    font-size: 11px;
    color: #536a7d;
}

.umrah-room-price strong {
    color: #173650;
}


/* =========================================================
   BUTTON
   ========================================================= */

.umrah-more-btn {
    display: block;
    text-align: center;
    background: #f47a29;
    color: #ffffff !important;
    text-decoration: none;
    padding: 13px 18px;
    border-radius: 30px;
    font-weight: 800;
    font-size: 13px;
    margin-top: 17px;
    transition: .2s ease;
}

.umrah-more-btn:hover {
    background: #db661c;
}


/* =========================================================
   EMPTY STATE
   ========================================================= */

.umrah-empty {
    background: #ffffff;
    padding: 35px;
    border-radius: 14px;
    text-align: center;
    color: #718397;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 1000px) {

    .umrah-market-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

}

@media (max-width: 650px) {

    .umrah-market-section {
        padding: 45px 0;
    }

    .umrah-market-grid {
        grid-template-columns: 1fr;
    }

    .umrah-card-image {
        height: 300px;
    }

    .umrah-market-heading h2 {
        font-size: 30px;
    }

}



/* =========================================================
   MUSTAFA TRAVELS — PREMIUM UMRAH / HAJJ V1
   Isolated additions; existing package/database logic preserved
   ========================================================= */
.umrah-hero{position:relative;overflow:hidden;background:linear-gradient(115deg,#041d35 0%,#073d62 55%,#0c6f87 100%);padding:92px 0 112px;color:#fff}
.umrah-hero:before{content:"";position:absolute;inset:0;opacity:.18;background-image:linear-gradient(30deg,transparent 48%,rgba(255,205,88,.25) 49%,rgba(255,205,88,.25) 51%,transparent 52%),linear-gradient(150deg,transparent 48%,rgba(255,255,255,.12) 49%,rgba(255,255,255,.12) 51%,transparent 52%);background-size:72px 42px}
.umrah-hero .container{position:relative;z-index:2}
.umrah-hero .eyebrow{display:inline-block;color:#f8c64d;font-weight:900;letter-spacing:.18em;font-size:12px;margin-bottom:16px}
.umrah-hero h1{max-width:820px;margin:0;font-size:clamp(42px,5.4vw,72px);line-height:1.02;letter-spacing:-.04em;color:#fff}
.umrah-hero p{max-width:700px;margin:22px 0 0;color:#d9e8f2;font-size:18px;line-height:1.7}
.umrah-hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:30px}
.umrah-hero-btn{display:inline-flex;align-items:center;justify-content:center;padding:14px 22px;border-radius:10px;font-weight:900;text-decoration:none!important}
.umrah-hero-btn.gold{background:#f5bd35;color:#092743!important}.umrah-hero-btn.ghost{border:1px solid rgba(255,255,255,.45);color:#fff!important;background:rgba(255,255,255,.08)}
.umrah-trust-row{display:flex;gap:10px;flex-wrap:wrap;margin-top:34px}.umrah-trust-pill{padding:9px 13px;border:1px solid rgba(255,255,255,.18);border-radius:999px;background:rgba(0,0,0,.12);font-size:12px;font-weight:800;color:#eaf5fb}

.umrah-intro{background:#fff;padding:76px 0}.umrah-intro-head{text-align:center;max-width:760px;margin:0 auto 34px}.umrah-intro-head .kicker,.umrah-market-heading .kicker{font-size:11px;letter-spacing:.2em;font-weight:900;color:#b88718;text-transform:uppercase}.umrah-intro-head h2{font-size:clamp(30px,4vw,46px);color:#082b4b;margin:8px 0 10px}.umrah-intro-head p{color:#6a7f91;line-height:1.7}
.umrah-premium-services{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}.umrah-premium-service{position:relative;padding:24px 20px;border:1px solid #e2eaf0;border-radius:18px;background:linear-gradient(180deg,#fff,#f9fbfd);box-shadow:0 12px 30px rgba(6,42,73,.06)}.umrah-premium-service.gold{border-color:#efd58c;background:linear-gradient(180deg,#fffdf7,#fff7df)}.umrah-premium-icon{width:44px;height:44px;display:grid;place-items:center;border-radius:13px;background:#e9f5fb;color:#087ca8;font-size:20px;margin-bottom:16px}.umrah-premium-service.gold .umrah-premium-icon{background:#ffedb4;color:#8a6300}.umrah-premium-service h3{margin:0 0 8px;color:#0b2c49;font-size:17px}.umrah-premium-service p{margin:0;color:#6e8294;font-size:13px;line-height:1.6}

.umrah-market-section{background:linear-gradient(180deg,#f5f8fb 0%,#eef4f8 100%);padding:82px 0}.umrah-market-heading{text-align:center;max-width:760px;margin:0 auto 38px}.umrah-market-heading h2{font-size:clamp(32px,4vw,46px);letter-spacing:-.025em}.umrah-market-heading p{margin:0 auto;line-height:1.7}
.umrah-market-card{border-radius:22px;border:1px solid #dce7ee;box-shadow:0 18px 45px rgba(8,44,75,.09)}.umrah-card-image:after{content:"";position:absolute;inset:45% 0 0;background:linear-gradient(transparent,rgba(3,25,45,.62));pointer-events:none}.umrah-featured{background:#f4bd38;color:#0b2945;border-radius:999px}.umrah-duration-badge{z-index:2}.umrah-card-body{padding:23px}.umrah-card-body h3{font-size:23px;letter-spacing:-.015em}.umrah-price{color:#0b4772}.umrah-more-btn{background:#0a4d78;border-radius:10px}.umrah-more-btn:hover{background:#073a5c}.umrah-included{border-left:3px solid #43a66d}.umrah-excluded{border-left:3px solid #cf6666}

.umrah-hajj-band{background:#062a49;color:#fff;padding:74px 0;position:relative;overflow:hidden}.umrah-hajj-band:after{content:"";position:absolute;width:420px;height:420px;border:1px solid rgba(245,189,53,.18);border-radius:50%;right:-120px;top:-180px;box-shadow:0 0 0 55px rgba(245,189,53,.035),0 0 0 110px rgba(245,189,53,.025)}.umrah-hajj-grid{position:relative;z-index:2;display:grid;grid-template-columns:1.15fr .85fr;gap:42px;align-items:center}.umrah-hajj-band .kicker{color:#f5bd35;font-size:11px;letter-spacing:.2em;font-weight:900}.umrah-hajj-band h2{font-size:clamp(32px,4vw,48px);margin:8px 0 14px;color:#fff}.umrah-hajj-band p{color:#d3e2ec;line-height:1.75;max-width:690px}.umrah-hajj-points{display:grid;grid-template-columns:1fr 1fr;gap:12px}.umrah-hajj-point{padding:15px;border:1px solid rgba(255,255,255,.13);background:rgba(255,255,255,.06);border-radius:14px;font-size:13px;font-weight:800}.umrah-hajj-card{background:#fff;color:#0b2b48;border-radius:20px;padding:26px;box-shadow:0 20px 55px rgba(0,0,0,.2)}.umrah-hajj-card strong{display:block;font-size:21px;margin-bottom:9px}.umrah-hajj-card p{color:#6b8091;margin:0 0 18px}.umrah-hajj-card a{display:inline-flex;background:#f5bd35;color:#092743!important;text-decoration:none;padding:12px 17px;border-radius:9px;font-weight:900}

@media(max-width:900px){.umrah-premium-services{grid-template-columns:repeat(2,1fr)}.umrah-hajj-grid{grid-template-columns:1fr}.umrah-hero{padding:70px 0 86px}}
@media(max-width:600px){.umrah-premium-services{grid-template-columns:1fr}.umrah-hero{padding:58px 0 70px}.umrah-hero p{font-size:16px}.umrah-hajj-points{grid-template-columns:1fr}}

</style>


<!-- ======================================================
     HERO
======================================================= -->

<section class="page-hero umrah-hero">
  <div class="container">
    <span class="eyebrow">UMRAH &amp; HAJJ • FROM BARCELONA</span>
    <h1>Your sacred journey, arranged with care.</h1>
    <p>Thoughtfully planned Umrah journeys with flights, Makkah &amp; Madinah hotels, visa guidance and transport options — supported personally from Barcelona.</p>
    <div class="umrah-hero-actions">
      <a class="umrah-hero-btn gold" href="#current-umrah-packages">View Umrah Packages →</a>
      <a class="umrah-hero-btn ghost" href="contact.php?service=Umrah">Request a Custom Quote</a>
    </div>
    <div class="umrah-trust-row">
      <span class="umrah-trust-pill">Barcelona-based support</span>
      <span class="umrah-trust-pill">Makkah &amp; Madinah stays</span>
      <span class="umrah-trust-pill">Visa &amp; transport guidance</span>
      <span class="umrah-trust-pill">Personal assistance</span>
    </div>
  </div>
</section>


<!-- ======================================================
     PREMIUM JOURNEY SERVICES
======================================================= -->
<section class="umrah-intro">
  <div class="container">
    <div class="umrah-intro-head">
      <span class="kicker">YOUR JOURNEY, OUR RESPONSIBILITY</span>
      <h2>Everything needed for a smoother Umrah journey.</h2>
      <p>Choose a complete package or let us build one around your preferred dates, budget, hotel distance and travel requirements.</p>
    </div>
    <div class="umrah-premium-services">
      <div class="umrah-premium-service"><div class="umrah-premium-icon">✈</div><h3>Flights from Barcelona</h3><p>Airline and routing options selected around your dates and baggage needs.</p></div>
      <div class="umrah-premium-service gold"><div class="umrah-premium-icon">🕋</div><h3>Makkah Hotels</h3><p>Walking-distance and shuttle options, from economy stays to premium properties.</p></div>
      <div class="umrah-premium-service gold"><div class="umrah-premium-icon">☾</div><h3>Madinah Hotels</h3><p>Carefully selected stays with clear distance and accommodation information.</p></div>
      <div class="umrah-premium-service"><div class="umrah-premium-icon">✓</div><h3>Visa &amp; Transport</h3><p>Visa guidance plus airport and intercity transport options where applicable.</p></div>
    </div>
  </div>
</section>

<!-- ======================================================
     CURRENT PACKAGES
======================================================= -->

<section class="umrah-market-section" id="current-umrah-packages">

    <div class="container">


        <div class="umrah-market-heading">

            <span class="kicker">LIVE UMRAH PACKAGES</span>

            <h2>
                Featured Umrah Packages from Barcelona
            </h2>

            <p>
                Compare our latest Umrah packages from
                Barcelona including flights, hotels,
                baggage and accommodation details.
            </p>

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


                    <div class="umrah-image-placeholder">
                        🕋
                    </div>


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


<section class="umrah-hajj-band">
  <div class="container umrah-hajj-grid">
    <div>
      <span class="kicker">HAJJ JOURNEY PLANNING</span>
      <h2>Planning for Hajj 2027?</h2>
      <p>Register your interest with Mustafa Travels for guidance as official arrangements, availability and applicable requirements become available.</p>
      <div class="umrah-hajj-points">
        <div class="umrah-hajj-point">✓ Barcelona-based assistance</div>
        <div class="umrah-hajj-point">✓ Journey planning support</div>
        <div class="umrah-hajj-point">✓ Documentation guidance</div>
        <div class="umrah-hajj-point">✓ Personal communication</div>
      </div>
    </div>
    <div class="umrah-hajj-card">
      <strong>Register your Hajj interest</strong>
      <p>Tell us your travel requirements and we will keep your enquiry ready for the next planning stage.</p>
      <a href="contact.php?service=Hajj">Hajj 2027 Information →</a>
    </div>
  </div>
</section>


<?php

site_footer();

?>
