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

</style>


<!-- ======================================================
     HERO
======================================================= -->

<section class="page-hero umrah-hero">

    <div class="container">

        <span class="eyebrow">
            UMRAH & HAJJ
        </span>

        <h1>
            A spiritual journey, professionally arranged.
        </h1>

        <p>
            Packages tailored around your dates,
            budget and hotel preferences.
        </p>

    </div>

</section>


<!-- ======================================================
     SERVICES
======================================================= -->

<section class="section">

    <div class="container">

        <div class="section-head centered">

            <div>

                <span class="eyebrow dark">
                    PACKAGE SERVICES
                </span>

                <h2>
                    Build your Umrah package
                </h2>

            </div>

        </div>


        <div class="service-grid">

            <div class="service-card">

                <div class="service-icon">
                    ✈
                </div>

                <h3>
                    Flights
                </h3>

                <p>
                    Flexible airline and routing options.
                </p>

            </div>


            <div class="service-card">

                <div class="service-icon">
                    ▦
                </div>

                <h3>
                    Makkah & Madinah Hotels
                </h3>

                <p>
                    Economy to premium options based on
                    distance and budget.
                </p>

            </div>


            <div class="service-card">

                <div class="service-icon">
                    ✓
                </div>

                <h3>
                    Visa Support
                </h3>

                <p>
                    Umrah visa assistance according to
                    applicable requirements.
                </p>

            </div>


            <div class="service-card">

                <div class="service-icon">
                    🚐
                </div>

                <h3>
                    Transport
                </h3>

                <p>
                    Jeddah–Makkah–Madinah and airport
                    transfer options.
                </p>

            </div>

        </div>


        <div class="center-actions">

            <a
                class="btn btn-primary btn-lg"
                href="contact.php?service=Umrah"
            >
                Request Umrah Quote
            </a>

        </div>

    </div>

</section>


<!-- ======================================================
     CURRENT PACKAGES
======================================================= -->

<section class="umrah-market-section">

    <div class="container">


        <div class="umrah-market-heading">

            <span class="eyebrow dark">
                LIVE PACKAGES
            </span>

            <h2>
                Current Umrah Packages
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


<?php

site_footer();

?>
