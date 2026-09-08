<?php
require_once __DIR__.'/partials.php';
site_header('Hajj');
?>
<style>
.hajj-page{--navy:#062c57;--blue:#0d72c5;--gold:#f0be39;--ink:#0b2947;--muted:#6b8195;background:#fff;color:var(--ink)}
.hajj-hero{position:relative;min-height:690px;display:flex;align-items:flex-end;overflow:hidden;background:#062c57}
.hajj-hero-bg{position:absolute;inset:0;background:
 linear-gradient(90deg,rgba(2,24,45,.90) 0%,rgba(3,35,61,.65) 46%,rgba(4,31,54,.25) 100%),
 url('https://commons.wikimedia.org/wiki/Special:Redirect/file/Kaaba%20Masjid%20Haraam%20Makkah.jpg') center 46%/cover no-repeat;transform:scale(1.015)}
.hajj-hero:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.03),rgba(2,25,46,.6))}
.hajj-wrap{width:min(1220px,calc(100% - 36px));margin:auto;position:relative;z-index:2}
.hajj-copy{max-width:760px;padding:90px 0 82px;color:#fff}
.hajj-kicker{display:inline-flex;align-items:center;gap:10px;font-size:12px;font-weight:900;letter-spacing:2px;color:#ffd35c;text-transform:uppercase}
.hajj-kicker:before{content:"";width:34px;height:2px;background:#ffd35c}
.hajj-copy h1{font:900 clamp(50px,6vw,84px)/.98 Manrope,Inter,sans-serif;letter-spacing:-2.5px;margin:18px 0 18px}
.hajj-copy p{max-width:690px;font-size:18px;line-height:1.7;color:#e3edf5;margin:0}
.hajj-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}
.hajj-btn{display:inline-flex;align-items:center;justify-content:center;padding:14px 20px;border-radius:10px;font-weight:900;text-decoration:none}
.hajj-btn.gold{background:var(--gold);color:#102b46}
.hajj-btn.ghost{color:#fff;border:1px solid rgba(255,255,255,.42);background:rgba(255,255,255,.08)}
.hajj-coming{padding:78px 0;background:linear-gradient(180deg,#f7fbff,#fff)}
.hajj-head{text-align:center;max-width:760px;margin:0 auto 36px}
.hajj-head span{font-size:11px;font-weight:900;letter-spacing:2px;color:#b88710}
.hajj-head h2{font:900 42px/1.08 Manrope,Inter,sans-serif;margin:10px 0 12px}
.hajj-head p{color:var(--muted);line-height:1.7}
.hajj-card{max-width:980px;margin:0 auto;border:1px solid #dbe6ee;border-radius:24px;overflow:hidden;background:#fff;box-shadow:0 20px 55px rgba(7,45,80,.10);display:grid;grid-template-columns:1.05fr .95fr}
.hajj-card-img{min-height:390px;background:
 linear-gradient(180deg,rgba(4,30,51,.03),rgba(4,30,51,.25)),
 url('https://commons.wikimedia.org/wiki/Special:Redirect/file/Kaaba%20Masjid%20Haraam%20Makkah.jpg') center/cover no-repeat}
.hajj-card-content{padding:42px;display:flex;flex-direction:column;justify-content:center}
.hajj-badge{display:inline-flex;align-self:flex-start;background:#fff4cf;border:1px solid #eed38a;color:#795a08;border-radius:999px;padding:8px 12px;font-size:11px;font-weight:900}
.hajj-card h3{font:900 34px/1.1 Manrope,Inter,sans-serif;margin:18px 0 12px}
.hajj-card p{color:var(--muted);line-height:1.7;margin:0 0 22px}
.hajj-points{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:24px}
.hajj-point{padding:11px 12px;border-radius:11px;background:#f5f9fc;border:1px solid #e5edf3;font-size:13px;font-weight:800;color:#3a5870}
.hajj-note{padding:56px 0 75px}
.hajj-note-box{max-width:980px;margin:auto;background:linear-gradient(110deg,#062e57,#0b6385);color:#fff;border-radius:20px;padding:28px 30px;display:flex;justify-content:space-between;align-items:center;gap:24px}
.hajj-note-box p{margin:5px 0 0;color:#d2e4ef}
@media(max-width:800px){.hajj-hero{min-height:620px}.hajj-card{grid-template-columns:1fr}.hajj-card-img{min-height:300px}.hajj-note-box{display:block}.hajj-note-box .hajj-btn{margin-top:18px}}
</style>

<main class="hajj-page">
<section class="hajj-hero">
  <div class="hajj-hero-bg"></div>
  <div class="hajj-wrap">
    <div class="hajj-copy">
      <div class="hajj-kicker">Hajj Journey · From Barcelona</div>
      <h1>A sacred journey deserves careful planning.</h1>
      <p>Hajj package information and registration guidance will be published here when our confirmed arrangements become available.</p>
      <div class="hajj-actions">
        <a class="hajj-btn gold" href="#packages">Hajj Packages →</a>
        <a class="hajj-btn ghost" target="_blank" href="https://wa.me/<?= WHATSAPP ?>?text=Hello%20Mustafa%20Travels%2C%20I%20would%20like%20more%20information%20about%20Hajj%20packages%20and%20registration.">Ask on WhatsApp</a>
      </div>
    </div>
  </div>
</section>

<section class="hajj-coming" id="packages">
  <div class="hajj-wrap">
    <div class="hajj-head">
      <span>HAJJ PACKAGES</span>
      <h2>Coming Soon</h2>
      <p>Our Hajj packages will appear here once dates, accommodation, transport and service arrangements are confirmed.</p>
    </div>

    <div class="hajj-card">
      <div class="hajj-card-img"></div>
      <div class="hajj-card-content">
        <span class="hajj-badge">Hajj Packages · Coming Soon</span>
        <h3>Register your interest for Hajj</h3>
        <p>Contact Mustafa Travels with your passenger details so we can keep your enquiry ready for the next confirmed Hajj package release.</p>
        <div class="hajj-points">
          <div class="hajj-point">Barcelona Support</div>
          <div class="hajj-point">Package Guidance</div>
          <div class="hajj-point">Accommodation Information</div>
          <div class="hajj-point">Travel Assistance</div>
        </div>
        <a class="hajj-btn gold" target="_blank" href="https://wa.me/<?= WHATSAPP ?>?text=Hello%20Mustafa%20Travels%2C%20I%20would%20like%20more%20information%20about%20Hajj%20packages%20and%20registration.">Contact on WhatsApp →</a>
      </div>
    </div>
  </div>
</section>

<section class="hajj-note">
  <div class="hajj-wrap">
    <div class="hajj-note-box">
      <div><strong style="font-size:21px">Need more information?</strong><p>Send us a WhatsApp message and our team will guide you with the latest available Hajj information.</p></div>
      <a class="hajj-btn gold" target="_blank" href="https://wa.me/<?= WHATSAPP ?>?text=Hello%20Mustafa%20Travels%2C%20I%20would%20like%20more%20information%20about%20Hajj%20packages%20and%20registration.">WhatsApp Mustafa Travels →</a>
    </div>
  </div>
</section>
</main>

<?php site_footer(); ?>
