<?php
require_once __DIR__.'/partials.php';
site_header('Visa Services');
?>
<style>
.visa-page{--navy:#062f61;--blue:#1687f7;--cyan:#38bdf8;--gold:#f3c43f;--ink:#0d2d52;--muted:#6b8096;--line:#d9e5ef;background:#f7fbff;color:var(--ink)}
.visa-hero{padding:82px 0;background:linear-gradient(110deg,#062d5c,#0d65ae);color:#fff}
.visa-wrap{width:min(1160px,calc(100% - 36px));margin:auto}
.visa-hero .tag{display:inline-block;border:1px solid rgba(255,255,255,.35);border-radius:8px;padding:7px 11px;font-size:11px;font-weight:850;color:#dff1ff}
.visa-hero h1{font:900 clamp(40px,5vw,62px)/1.03 Manrope,Inter,sans-serif;margin:17px 0 14px;max-width:760px}
.visa-hero p{max-width:700px;color:#d9e9f7;font-size:17px;line-height:1.7}
.visa-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:22px;padding:70px 0}
.visa-card{background:#fff;border:1px solid var(--line);border-radius:20px;padding:28px;box-shadow:0 12px 30px rgba(9,51,91,.08)}
.visa-icon{width:52px;height:52px;border-radius:14px;background:#e9f5ff;display:grid;place-items:center;font-size:24px;color:var(--blue)}
.visa-card h2{font-size:25px;margin:17px 0 10px}.visa-card p{color:var(--muted);line-height:1.7}
.visa-list{margin:18px 0 0;padding:0;list-style:none}.visa-list li{padding:9px 0;border-top:1px solid #edf2f6;color:#415a71;font-size:14px}
.visa-cta{background:#062f61;color:#fff;border-radius:18px;padding:25px 28px;display:flex;justify-content:space-between;align-items:center;gap:20px;margin-bottom:70px}
.visa-cta p{margin:4px 0 0;color:#cfdfed}.visa-btn{display:inline-flex;padding:13px 18px;border-radius:9px;background:var(--gold);color:#102b47!important;text-decoration:none;font-weight:900;white-space:nowrap}
.visa-note{font-size:12px;color:#71879a;line-height:1.6;margin-top:18px}
@media(max-width:760px){.visa-grid{grid-template-columns:1fr}.visa-cta{display:block}.visa-btn{margin-top:18px}}
</style>

<main class="visa-page">
<section class="visa-hero">
  <div class="visa-wrap">
    <span class="tag">VISA SUPPORT FROM BARCELONA</span>
    <h1>eVisa & Umrah Visa Assistance</h1>
    <p>Clear document guidance, application support and travel preparation for eligible travellers. Visa approval always remains subject to the relevant government or immigration authority.</p>
  </div>
</section>

<div class="visa-wrap">
  <section class="visa-grid">
    <article class="visa-card">
      <div class="visa-icon">▣</div>
      <h2>Tourist & eVisa Support</h2>
      <p>Support with eligible online visa applications for selected destinations, including document review and application guidance.</p>
      <ul class="visa-list">
        <li>Eligibility guidance based on passport and residence status</li>
        <li>Document checklist and application preparation</li>
        <li>Photo and passport-document guidance</li>
        <li>Application-status assistance where available</li>
      </ul>
    </article>

    <article class="visa-card">
      <div class="visa-icon">☾</div>
      <h2>Umrah Visa Guidance</h2>
      <p>Umrah visa support can be arranged as part of selected Umrah journeys, subject to Saudi requirements and the traveller's eligibility.</p>
      <ul class="visa-list">
        <li>Umrah visa document guidance</li>
        <li>Package-linked visa assistance where applicable</li>
        <li>Makkah & Madinah hotel coordination</li>
        <li>Transport options for selected packages</li>
      </ul>
    </article>
  </section>

  <section class="visa-cta">
    <div>
      <strong style="font-size:20px">Not sure which visa route applies to you?</strong>
      <p>Send us your nationality, residence country and destination for an initial eligibility check.</p>
    </div>
    <a class="visa-btn" href="contact.php?service=Visa">Ask About Visa →</a>
  </section>
  <p class="visa-note">Mustafa Travels & Tours provides travel and application assistance only. We do not guarantee visa approval; final decisions are made solely by the relevant government, embassy, consulate or immigration authority.</p>
</div>
</main>

<?php site_footer(); ?>
