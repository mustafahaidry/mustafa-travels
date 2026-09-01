<?php
require_once __DIR__ . '/partials.php';
site_header('Flights');
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
:root{--navy:#071f46;--blue:#0d3d85;--gold:#e5ae37;--ink:#13223b;--muted:#728198;--line:#e1e8f1;--bg:#f7f9fc}
.mt-flight-page{background:linear-gradient(180deg,#f8fbff 0,#f7f9fc 100%);min-height:760px;padding:32px 0 88px;font-family:Inter,Arial,sans-serif}
.mt-flight-wrap{width:min(1440px,calc(100% - 40px));margin:auto}
.mt-flight-head{display:flex;justify-content:space-between;align-items:end;margin:0 0 16px}
.mt-flight-head h1{margin:0;color:var(--navy);font:800 30px Manrope,Inter,sans-serif}.mt-flight-head p{margin:5px 0 0;color:var(--muted);font-size:13px}
.mt-engine-badge{font-size:10px;font-weight:900;letter-spacing:.8px;color:#0b58a1;background:#eaf4ff;padding:8px 12px;border-radius:999px}
.mt-search-card{background:#fff;border:1px solid #dfe7f0;border-radius:14px;box-shadow:0 10px 30px rgba(15,45,85,.08);overflow:visible}
.mt-tabs{display:flex;gap:22px;padding:14px 22px 0;border-bottom:1px solid #edf1f6}.mt-trip-tab{border:0;background:none;padding:0 1px 12px;font-weight:800;font-size:12px;color:#78869a;cursor:pointer;border-bottom:2px solid transparent}.mt-trip-tab.active{color:var(--navy);border-color:var(--gold)}
.mt-search-row{display:grid;grid-template-columns:1.3fr 42px 1.3fr .92fr .92fr 1.05fr 160px;align-items:stretch;padding:12px;gap:0}
.mt-field{position:relative;padding:5px 16px;border-right:1px solid #e5eaf1;min-width:0}.mt-field label{display:block;color:#7c899b;font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.55px;margin-bottom:3px}.mt-field input,.mt-field select{border:0;outline:0;background:transparent;width:100%;padding:0;color:var(--ink);font:800 14px Inter,Arial,sans-serif;height:24px}.mt-field small{display:block;color:#8a97a8;font-size:9px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.mt-swap{align-self:center;justify-self:center;width:34px;height:34px;border:1px solid #d9e2ed;border-radius:50%;background:#fff;color:var(--navy);font-size:18px;cursor:pointer;box-shadow:0 3px 10px rgba(20,50,90,.06)}
.mt-search-button{margin:3px 2px 3px 12px;border:0;border-radius:9px;background:linear-gradient(135deg,#0b2e69,#071d49);color:#fff;font-weight:900;font-size:12px;cursor:pointer;box-shadow:0 8px 18px rgba(7,31,70,.18)}
.mt-trust{margin-top:12px;background:#f8fbff;border:1px solid #dfe9f6;border-radius:11px;display:grid;grid-template-columns:repeat(4,1fr);padding:12px}.mt-trust div{text-align:center;color:#17335e;font-size:11px;font-weight:800;border-right:1px solid #dfe8f3}.mt-trust div:last-child{border:0}.mt-trust b{color:#1674cf;margin-right:7px}
.mt-traveller-trigger{cursor:pointer}.mt-traveller-trigger strong{display:block;color:var(--ink);font-size:14px}.mt-pax-popup{display:none;position:absolute;right:0;top:58px;width:300px;z-index:120;background:#fff;border:1px solid #dce5ef;border-radius:13px;padding:15px;box-shadow:0 20px 50px rgba(10,40,80,.18)}.mt-pax-popup.open{display:block}.mt-pax-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #edf2f6}.mt-pax-row strong{font-size:12px}.mt-pax-row small{font-size:9px}.mt-step{display:flex;align-items:center;gap:10px}.mt-step button{width:28px;height:28px;border-radius:50%;border:1px solid #ccd8e3;background:#fff;color:var(--navy);font-weight:900;cursor:pointer}.mt-step span{min-width:16px;text-align:center;font-weight:900}.mt-pax-done{width:100%;margin-top:11px;border:0;border-radius:8px;background:var(--navy);color:#fff;padding:10px;font-weight:900;cursor:pointer}
.mt-airport-list{display:none;position:absolute;left:5px;right:5px;top:57px;z-index:130;background:#fff;border:1px solid #dce6ef;border-radius:11px;box-shadow:0 18px 45px rgba(8,47,95,.18);overflow-y:auto;max-height:330px}.mt-airport-list.open{display:block}.mt-airport-item{padding:11px 12px;border-bottom:1px solid #edf2f6;cursor:pointer}.mt-airport-item:hover{background:#f3f8fd}.mt-airport-item strong{display:block;font-size:12px;color:#0b2b51}.mt-airport-item small{display:block;margin-top:2px;font-size:9px;color:#7c90a3}
.flatpickr-calendar{font-family:Inter,sans-serif!important;border-radius:14px!important;box-shadow:0 18px 50px rgba(8,47,95,.2)!important}.flatpickr-day.selected{background:#0c397d!important;border-color:#0c397d!important}
@media(max-width:1100px){.mt-search-row{grid-template-columns:1fr 40px 1fr 1fr}.mt-search-button{min-height:50px}.mt-trust{grid-template-columns:1fr 1fr;gap:10px}.mt-trust div{border:0}}
@media(max-width:720px){.mt-flight-wrap{width:min(100% - 22px,1440px)}.mt-flight-head{align-items:start;gap:10px}.mt-flight-head h1{font-size:25px}.mt-search-row{grid-template-columns:1fr;gap:5px}.mt-field{border-right:0;border-bottom:1px solid #edf1f6;padding:10px}.mt-swap{display:none}.mt-search-button{margin:8px 0 0;height:50px}.mt-trust{grid-template-columns:1fr 1fr}.mt-tabs{padding-left:12px}}
</style>
<section class="mt-flight-page"><div class="mt-flight-wrap">
<div class="mt-flight-head"><div><h1>Find your perfect flight</h1><p>Compare live fares and book your journey with confidence.</p></div><span class="mt-engine-badge">MUSTAFA FLIGHT ENGINE</span></div>
<form class="mt-search-card" method="get" action="flight-results.php" id="flightSearchForm">
<input type="hidden" name="trip_type" id="tripType" value="round"><input type="hidden" name="origin" id="originCode" value="BCN"><input type="hidden" name="destination" id="destinationCode" value=""><input type="hidden" name="adults" id="adultInput" value="1"><input type="hidden" name="children" id="childInput" value="0"><input type="hidden" name="infants" id="infantInput" value="0">
<div class="mt-tabs"><button type="button" class="mt-trip-tab active" data-trip="round">Round trip</button><button type="button" class="mt-trip-tab" data-trip="oneway">One way</button></div>
<div class="mt-search-row">
<div class="mt-field"><label>From</label><input type="text" id="originText" value="Barcelona (BCN)" placeholder="City or airport" autocomplete="off" required><small>Choose departure airport</small><div class="mt-airport-list" id="originList"></div></div>
<button type="button" class="mt-swap" id="swapAirports">⇄</button>
<div class="mt-field"><label>To</label><input type="text" id="destinationText" placeholder="City or airport" autocomplete="off" required><small>Worldwide destinations</small><div class="mt-airport-list" id="destinationList"></div></div>
<div class="mt-field"><label>Departure</label><input type="text" name="departure" id="departureDate" placeholder="Select date" autocomplete="off" required><small>Travel date</small></div>
<div class="mt-field" id="returnBox"><label>Return</label><input type="text" name="return_date" id="returnDate" placeholder="Select date" autocomplete="off" required><small>Return date</small></div>
<div class="mt-field mt-traveller-trigger" id="travellerTrigger"><label>Travellers & class</label><strong id="travellerText">1 Traveller</strong><small id="cabinText">Economy</small>
<div class="mt-pax-popup" id="paxPopup">
<div class="mt-pax-row"><div><strong>Adults</strong><small>12+ years</small></div><div class="mt-step"><button type="button" data-pax="adult" data-delta="-1">−</button><span id="adultCount">1</span><button type="button" data-pax="adult" data-delta="1">+</button></div></div>
<div class="mt-pax-row"><div><strong>Children</strong><small>2–11 years</small></div><div class="mt-step"><button type="button" data-pax="child" data-delta="-1">−</button><span id="childCount">0</span><button type="button" data-pax="child" data-delta="1">+</button></div></div>
<div class="mt-pax-row"><div><strong>Infants</strong><small>Under 2</small></div><div class="mt-step"><button type="button" data-pax="infant" data-delta="-1">−</button><span id="infantCount">0</span><button type="button" data-pax="infant" data-delta="1">+</button></div></div>
<div class="mt-pax-row"><div><strong>Cabin</strong></div><select name="cabin" id="cabinSelect"><option value="economy">Economy</option><option value="premium_economy">Premium Economy</option><option value="business">Business</option><option value="first">First</option></select></div>
<button type="button" class="mt-pax-done" id="paxDone">Done</button></div></div>
<button class="mt-search-button" type="submit">Search Flights</button></div></form>
<div class="mt-trust"><div><b>✓</b>Best Price Guarantee</div><div><b>♢</b>Secure Booking</div><div><b>◉</b>24/7 Customer Support</div><div><b>★</b>Trusted Travel Service</div></div>
</div></section>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded',function(){
let timers={};
function esc(v){return String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));}
function setupAirport(inputId,hiddenId,listId){
 const input=document.getElementById(inputId),hidden=document.getElementById(hiddenId),list=document.getElementById(listId);
 async function search(){
  const q=input.value.trim(); list.innerHTML='';
  if(q.length<2){list.classList.remove('open');return;}
  list.innerHTML='<div class="mt-airport-item"><small>Searching airports…</small></div>';list.classList.add('open');
  try{
   const r=await fetch('api/airports.php?q='+encodeURIComponent(q),{headers:{'Accept':'application/json'}});
   if(!r.ok) throw new Error('Airport search unavailable');
   const j=await r.json(); const rows=Array.isArray(j)?j:(j.data||[]);
   list.innerHTML='';
   if(!rows.length){list.innerHTML='<div class="mt-airport-item"><small>No airport found</small></div>';return;}
   rows.slice(0,12).forEach(a=>{
    const code=a.iata_code||a.iata||a.code||'',city=a.city_name||a.city||a.name||'',name=a.name||a.airport_name||'Airport';
    if(!code)return; const row=document.createElement('div');row.className='mt-airport-item';
    row.innerHTML='<strong>'+esc(city)+' ('+esc(code)+')</strong><small>'+esc(name)+'</small>';
    row.onclick=()=>{input.value=city+' ('+code+')';hidden.value=code;list.classList.remove('open');};list.appendChild(row);
   });
  }catch(e){list.innerHTML='<div class="mt-airport-item"><small>Airport search temporarily unavailable</small></div>';}
 }
 input.addEventListener('input',()=>{hidden.value='';clearTimeout(timers[inputId]);timers[inputId]=setTimeout(search,280);});
 input.addEventListener('focus',()=>{if(input.value.trim().length>=2)search();});
 document.addEventListener('click',e=>{if(!input.parentElement.contains(e.target))list.classList.remove('open');});
}
setupAirport('originText','originCode','originList');setupAirport('destinationText','destinationCode','destinationList');
document.getElementById('swapAirports').onclick=()=>{let ot=document.getElementById('originText'),oc=document.getElementById('originCode'),dt=document.getElementById('destinationText'),dc=document.getElementById('destinationCode');[ot.value,dt.value]=[dt.value,ot.value];[oc.value,dc.value]=[dc.value,oc.value];};
const tripType=document.getElementById('tripType'),returnBox=document.getElementById('returnBox'),returnDate=document.getElementById('returnDate');
function setTrip(type){tripType.value=type;document.querySelectorAll('.mt-trip-tab').forEach(b=>b.classList.toggle('active',b.dataset.trip===type));if(type==='oneway'){returnBox.style.display='none';returnDate.removeAttribute('required');returnDate.value='';}else{returnBox.style.display='';returnDate.setAttribute('required','required');}}
document.querySelectorAll('.mt-trip-tab').forEach(b=>b.onclick=()=>setTrip(b.dataset.trip));
const rp=flatpickr('#returnDate',{dateFormat:'Y-m-d',altInput:true,altFormat:'D, d M Y',minDate:'today',disableMobile:true});
flatpickr('#departureDate',{dateFormat:'Y-m-d',altInput:true,altFormat:'D, d M Y',minDate:'today',disableMobile:true,onChange:(d,s)=>{if(s)rp.set('minDate',s);}});
const trigger=document.getElementById('travellerTrigger'),popup=document.getElementById('paxPopup');trigger.onclick=e=>{if(!e.target.closest('.mt-step')&&!e.target.closest('select')&&e.target.id!=='paxDone')popup.classList.add('open');};document.getElementById('paxDone').onclick=e=>{e.stopPropagation();popup.classList.remove('open');};document.addEventListener('click',e=>{if(!trigger.contains(e.target))popup.classList.remove('open');});
const state={adult:1,child:0,infant:0};function sync(){state.adult=Math.max(1,Math.min(9,state.adult));state.child=Math.max(0,Math.min(8,state.child));state.infant=Math.max(0,Math.min(state.adult,state.infant));adultInput.value=state.adult;childInput.value=state.child;infantInput.value=state.infant;adultCount.textContent=state.adult;childCount.textContent=state.child;infantCount.textContent=state.infant;let t=state.adult+state.child+state.infant;travellerText.textContent=t+(t===1?' Traveller':' Travellers');}
document.querySelectorAll('[data-pax][data-delta]').forEach(b=>b.onclick=e=>{e.stopPropagation();state[b.dataset.pax]+=parseInt(b.dataset.delta);sync();});cabinSelect.onchange=function(){cabinText.textContent=this.options[this.selectedIndex].text;};
flightSearchForm.onsubmit=e=>{if(!originCode.value||!destinationCode.value){e.preventDefault();alert('Please select both airports from the suggestions.');}};setTrip('round');sync();
});
</script>
<?php site_footer(); ?>