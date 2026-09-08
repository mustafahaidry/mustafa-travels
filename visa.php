<?php
require_once __DIR__.'/partials.php';
site_header('Visa Services');
?>
<style>
.visa-page{--navy:#062f61;--blue:#1687f7;--cyan:#38bdf8;--gold:#f3c43f;--ink:#0d2d52;--muted:#6b8096;--line:#d9e5ef;background:#f7fbff;color:var(--ink)}
.visa-hero{padding:82px 0;background:linear-gradient(110deg,#062d5c,#0d65ae);color:#fff}
.visa-wrap{width:min(1160px,calc(100% - 36px));margin:auto}
.visa-hero .tag{display:inline-block;border:1px solid rgba(255,255,255,.35);border-radius:8px;padding:7px 11px;font-size:11px;font-weight:850;color:#dff1ff}
.visa-hero h1{font:900 clamp(40px,5vw,62px)/1.03 Manrope,Inter,sans-serif;margin:17px 0 14px;max-width:820px}
.visa-hero p{max-width:760px;color:#d9e9f7;font-size:17px;line-height:1.7}
.visa-section{padding:70px 0}
.visa-head{text-align:center;max-width:760px;margin:0 auto 34px}
.visa-head span{display:block;color:#0a7fe0;font-size:11px;font-weight:900;letter-spacing:2px;margin-bottom:8px}
.visa-head h2{font:900 38px/1.1 Manrope,Inter,sans-serif;margin:0 0 10px}
.visa-head p{color:var(--muted);line-height:1.7;margin:0}
.visa-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:22px}
.visa-card{background:#fff;border:1px solid var(--line);border-radius:20px;padding:28px;box-shadow:0 12px 30px rgba(9,51,91,.08)}
.visa-card.saudi{background:linear-gradient(145deg,#fffdf8,#fff6df);border-color:#ecd38c}
.visa-icon{width:52px;height:52px;border-radius:14px;background:#e9f5ff;display:grid;place-items:center;font-size:24px;color:var(--blue)}
.visa-card.saudi .visa-icon{background:#ffebae;color:#946900}
.visa-card h3{font-size:24px;margin:17px 0 10px}
.visa-card p{color:var(--muted);line-height:1.7}
.visa-pills{display:flex;flex-wrap:wrap;gap:9px;margin-top:18px}
.visa-pill{display:inline-flex;align-items:center;padding:9px 12px;border-radius:999px;background:#f4f8fb;border:1px solid #e0e9f0;color:#34526b;font-size:13px;font-weight:800}
.visa-card.saudi .visa-pill{background:#fff8df;border-color:#eedb9b;color:#6e5311}
.visa-info{margin-top:18px;padding-top:15px;border-top:1px solid #edf2f6;color:#526b80;font-size:13px;line-height:1.65}
.visa-cta{background:#062f61;color:#fff;border-radius:18px;padding:25px 28px;display:flex;justify-content:space-between;align-items:center;gap:20px;margin:34px 0 0}
.visa-cta p{margin:4px 0 0;color:#cfdfed}
.visa-btn{display:inline-flex;padding:13px 18px;border-radius:9px;background:var(--gold);color:#102b47!important;text-decoration:none;font-weight:900;white-space:nowrap}
.visa-note{font-size:12px;color:#71879a;line-height:1.6;margin:20px 0 0}
@media(max-width:760px){.visa-grid{grid-template-columns:1fr}.visa-cta{display:block}.visa-btn{margin-top:18px}}
</style>

<main class="visa-page">
<section class="visa-hero">
  <div class="visa-wrap">
    <span class="tag">VISA SUPPORT FROM BARCELONA</span>
    <h1>eVisa, ETA & Visa Assistance</h1>
    <p>Practical travel-document support for selected destinations, including eVisa, ETA and Umrah visa guidance. Eligibility and approval always depend on the traveller's circumstances and the relevant government or immigration authority.</p>
  </div>
</section>

<section class="visa-section">
  <div class="visa-wrap">
    <div class="visa-head">
      <span>OUR VISA SERVICES</span>
      <h2>Visa support for popular destinations</h2>
      <p>Choose the category that matches your trip and contact us with your passport nationality, country of residence and travel dates.</p>
    </div>

    <div class="visa-grid">
      <article class="visa-card">
        <div class="visa-icon">▣</div>
        <h3>eVisa Services</h3>
        <p>Application guidance and document-preparation support for selected electronic visa destinations.</p>
        <div class="visa-pills">
          <span class="visa-pill">Turkey</span>
          <span class="visa-pill">Morocco</span>
          <span class="visa-pill">Pakistan</span>
          <span class="visa-pill">India</span>
          <span class="visa-pill">Bangladesh</span>
        </div>
        <div class="visa-info">Availability of an eVisa depends on passport nationality, residence status and the destination's current rules.</div>
      </article>

      <article class="visa-card">
        <div class="visa-icon">✓</div>
        <h3>ETA / Electronic Travel Authorization</h3>
        <p>Travel authorization guidance for eligible travellers visiting selected destinations.</p>
        <div class="visa-pills">
          <span class="visa-pill">Australia</span>
          <span class="visa-pill">United Kingdom</span>
          <span class="visa-pill">USA</span>
          <span class="visa-pill">Canada</span>
        </div>
        <div class="visa-info">The exact authorization type and eligibility can vary by passport and residence status.</div>
      </article>

      <article class="visa-card">
        <div class="visa-icon">✈</div>
        <h3>Visa Assistance</h3>
        <p>Support with document checklists, application preparation and travel-document guidance for selected visa applications.</p>
        <div class="visa-pills">
          <span class="visa-pill">United Kingdom</span>
          <span class="visa-pill">USA</span>
          <span class="visa-pill">Canada</span>
        </div>
        <div class="visa-info">We assist with preparation and guidance; embassy or immigration decisions remain independent.</div>
      </article>

      <article class="visa-card saudi">
        <div class="visa-icon">☾</div>
        <h3>Saudi Arabia Visa Services</h3>
        <p>Saudi travel visa guidance for eligible travellers and selected journey types.</p>
        <div class="visa-pills">
          <span class="visa-pill">Umrah Visa</span>
          <span class="visa-pill">Visit Visa</span>
        </div>
        <div class="visa-info">Umrah visa support can be linked with selected Umrah packages including Makkah/Madinah hotel and transport arrangements where applicable.</div>
      </article>
    </div>

    <section class="visa-cta">
      <div>
        <strong style="font-size:20px">Not sure which visa route applies to you?</strong>
        <p>Send us your nationality, residence country and destination for an initial eligibility check.</p>
      </div>
      <a class="visa-btn" href="contact.php?service=Visa">Ask About Visa →</a>
    </section>

    <p class="visa-note">Mustafa Travels & Tours provides travel and application assistance only. We do not guarantee visa or travel-authorization approval; final decisions are made solely by the relevant government, embassy, consulate or immigration authority.</p>
  </div>
</section>
</main>

<?php site_footer(); ?>
