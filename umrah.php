<?php
require_once __DIR__ . '/partials.php';

$packs = sb_select(
    'umrah_packages',
    'select=*&active=eq.true&order=featured.desc,id.desc'
);

site_header('Umrah & Hajj');
?>

<section class="page-hero umrah-hero">
    <div class="container">
        <span class="eyebrow">UMRAH & HAJJ</span>
        <h1>A spiritual journey, professionally arranged.</h1>
        <p>Packages tailored around your dates, budget and hotel preferences.</p>
    </div>
</section>


<section class="section">
    <div class="container">

        <div class="section-head centered">
            <div>
                <span class="eyebrow dark">PACKAGE SERVICES</span>
                <h2>Build your Umrah package</h2>
            </div>
        </div>

        <div class="service-grid">

            <div class="service-card">
                <div class="service-icon">✈</div>
                <h3>Flights</h3>
                <p>Flexible airline and routing options.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">▦</div>
                <h3>Makkah & Madinah Hotels</h3>
                <p>Economy to premium options based on distance and budget.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">✓</div>
                <h3>Visa Support</h3>
                <p>Umrah visa assistance according to applicable requirements.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🚐</div>
                <h3>Transport</h3>
                <p>Jeddah–Makkah–Madinah and airport transfer options.</p>
            </div>

        </div>

        <div class="center-actions">
            <a class="btn btn-primary btn-lg"
               href="contact.php?service=Umrah">
                Request Umrah Quote
            </a>
        </div>

    </div>
</section>


<?php if (!empty($packs)): ?>

<section class="section section-soft">

    <div class="container">

        <div class="section-head">
            <div>
                <span class="eyebrow dark">LIVE PACKAGES</span>
                <h2>Current Umrah Packages</h2>
            </div>
        </div>


        <div class="offers-grid">

            <?php foreach ($packs as $p): ?>

                <article class="offer-card">


                    <div class="offer-media">

                        <?php if (!empty($p['image_url'])): ?>

                            <img
                                src="<?= h($p['image_url']) ?>"
                                alt="<?= h($p['title'] ?? 'Umrah Package') ?>"
                                style="
                                    width:100%;
                                    height:100%;
                                    object-fit:cover;
                                    display:block;
                                "
                            >

                        <?php endif; ?>


                        <span class="offer-badge">
                            <?= h(
                                !empty($p['duration'])
                                    ? $p['duration']
                                    : 'Umrah Package'
                            ) ?>
                        </span>

                    </div>


                    <div class="offer-body">

                        <?php if (!empty($p['airline'])): ?>
                            <small><?= h($p['airline']) ?></small>
                        <?php endif; ?>


                        <h3>
                            <?= h($p['title'] ?? 'Umrah Package') ?>
                        </h3>


                        <div style="
                            margin:12px 0;
                            line-height:1.7;
                            font-size:14px;
                        ">

                            <div>
                                <strong>🕋 Makkah:</strong>
                                <?= h($p['makkah_hotel'] ?? '') ?>
                            </div>

                            <div>

                                <?php if (!empty($p['makkah_nights'])): ?>
                                    <?= h((string)$p['makkah_nights']) ?>
                                    Nights
                                <?php endif; ?>

                                <?php if (!empty($p['makkah_distance'])): ?>
                                    · <?= h($p['makkah_distance']) ?>
                                <?php endif; ?>

                            </div>


                            <div style="margin-top:8px;">
                                <strong>🕌 Madinah:</strong>
                                <?= h($p['madinah_hotel'] ?? '') ?>
                            </div>

                            <div>

                                <?php if (!empty($p['madinah_nights'])): ?>
                                    <?= h((string)$p['madinah_nights']) ?>
                                    Nights
                                <?php endif; ?>

                                <?php if (!empty($p['madinah_distance'])): ?>
                                    · <?= h($p['madinah_distance']) ?>
                                <?php endif; ?>

                            </div>

                        </div>


                        <div class="offer-meta">

                            <span>

                                <?= h($p['travel_date'] ?? '') ?>

                                <?php if (!empty($p['return_date'])): ?>
                                    → <?= h($p['return_date']) ?>
                                <?php endif; ?>

                            </span>

                            <span>
                                <?= h($p['baggage'] ?? '') ?>
                            </span>

                        </div>


                        <?php if (!empty($p['description'])): ?>

                            <p>
                                <?= nl2br(h($p['description'])) ?>
                            </p>

                        <?php endif; ?>


                        <?php if (!empty($p['included'])): ?>

                            <div style="
                                margin-top:12px;
                                background:#eef9f2;
                                padding:10px 12px;
                                border-radius:8px;
                                font-size:13px;
                            ">

                                <strong>✓ Included</strong>
                                <br>

                                <?= nl2br(h($p['included'])) ?>

                            </div>

                        <?php endif; ?>


                        <?php if (!empty($p['not_included'])): ?>

                            <div style="
                                margin-top:10px;
                                background:#fff1f1;
                                padding:10px 12px;
                                border-radius:8px;
                                font-size:13px;
                            ">

                                <strong>✕ Not Included</strong>
                                <br>

                                <?= nl2br(h($p['not_included'])) ?>

                            </div>

                        <?php endif; ?>


                        <div style="
                            margin-top:15px;
                            line-height:1.8;
                        ">

                            <?php if ((float)($p['quad_price'] ?? 0) > 0): ?>

                                <div>
                                    <strong>Quad:</strong>
                                    <?= h($p['currency'] ?? 'EUR') ?>
                                    <?= number_format(
                                        (float)$p['quad_price'],
                                        0
                                    ) ?>
                                </div>

                            <?php endif; ?>


                            <?php if ((float)($p['triple_price'] ?? 0) > 0): ?>

                                <div>
                                    <strong>Triple:</strong>
                                    <?= h($p['currency'] ?? 'EUR') ?>
                                    <?= number_format(
                                        (float)$p['triple_price'],
                                        0
                                    ) ?>
                                </div>

                            <?php endif; ?>


                            <?php if ((float)($p['double_price'] ?? 0) > 0): ?>

                                <div>
                                    <strong>Double:</strong>
                                    <?= h($p['currency'] ?? 'EUR') ?>
                                    <?= number_format(
                                        (float)$p['double_price'],
                                        0
                                    ) ?>
                                </div>

                            <?php endif; ?>


                            <?php if ((float)($p['single_price'] ?? 0) > 0): ?>

                                <div>
                                    <strong>Single:</strong>
                                    <?= h($p['currency'] ?? 'EUR') ?>
                                    <?= number_format(
                                        (float)$p['single_price'],
                                        0
                                    ) ?>
                                </div>

                            <?php endif; ?>

                        </div>


                        <a
                            class="btn btn-dark"
                            style="margin-top:15px;"
                            href="https://wa.me/<?= WHATSAPP ?>?text=<?= urlencode(
                                'Please send details for Umrah package: ' .
                                ($p['title'] ?? 'Umrah Package')
                            ) ?>"
                            target="_blank"
                        >
                            Ask on WhatsApp
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php endif; ?>


<?php site_footer(); ?>
