<?php
require_once __DIR__ . '/partials.php';

site_header('Flights');
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
.mt-flight-page{background:#f4f8fc;min-height:760px;padding:46px 0 90px}
.mt-flight-wrap{width:min(1180px,calc(100% - 32px));margin:auto}
.mt-flight-head{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:20px}
.mt-flight-head h1{margin:0;font:800 38px Manrope,Inter,sans-serif;color:#08294f}
.mt-flight-head p{margin:7px 0 0;color:#6d8094}
.mt-engine-badge{background:#e2f4ff;color:#0877bd;border-radius:30px;padding:8px 12px;font-size:10px;font-weight:900;letter-spacing:.6px}
.mt-search-card{background:#fff;border:1px solid #dce6ef;border-radius:18px;padding:20px;box-shadow:0 18px 45px rgba(8,47,95,.08)}
.mt-trip-tabs{display:flex;gap:8px;margin-bottom:16px}
.mt-trip-tab{border:1px solid #d7e1eb;background:#fff;color:#526a80;border-radius:30px;padding:9px 15px;font-size:11px;font-weight:900;cursor:pointer}
.mt-trip-tab.active{background:#082f5f;border-color:#082f5f;color:#fff}
.mt-search-row{display:grid;grid-template-columns:1.2fr 42px 1.2fr 1fr 1fr 1.15fr 150px;gap:9px;align-items:stretch}
.mt-field{position:relative;border:1px solid #cedae5;border-radius:11px;padding:8px 11px;background:#fff;min-width:0}
.mt-field label{display:block;font-size:9px;font-weight:900;letter-spacing:.55px;color:#7a8da0;margin-bottom:2px}
.mt-field input,.mt-field select{border:0;outline:0;background:transparent;width:100%;height:27px;color:#10253d;font:800 13px Inter,sans-serif}
.mt-swap{align-self:center;width:36px;height:36px;border:1px solid #dbe5ee;background:#fff;color:#082f5f;border-radius:50%;font-weight:900;cursor:pointer}
.mt-search-button{border:0;border-radius:11px;background:linear-gradient(135deg,#1096e9,#0875ca);color:#fff;font-weight:900;cursor:pointer}
.mt-search-button:hover{filter:brightness(.97)}
.mt-traveller-trigger{cursor:pointer}
.mt-traveller-trigger strong{display:block;font-size:13px;color:#10253d;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.mt-traveller-trigger small{display:block;font-size:9px;color:#8193a4;margin-top:2px}
.mt-pax-popup{display:none;position:absolute;right:0;top:58px;width:290px;z-index:120;background:#fff;border:1px solid #dce6ef;border-radius:14px;padding:15px;box-shadow:0 18px 50px rgba(8,47,95,.2)}
.mt-pax-popup.open{display:block}
.mt-pax-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #edf2f6}
.mt-pax-row:last-of-type{border-bottom:0}
.mt-pax-row strong{font-size:12px}.mt-pax-row small{display:block;font-size:9px;color:#8496a7}
.mt-step{display:flex;align-items:center;gap:10px}
.mt-step button{width:28px;height:28px;border-radius:50%;border:1px solid #ccd8e3;background:#fff;color:#082f5f;font-weight:900;cursor:pointer}
.mt-step span{min-width:16px;text-align:center;font-weight:900}
.mt-pax-done{width:100%;margin-top:11px;border:0;border-radius:9px;background:#082f5f;color:#fff;padding:10px;font-weight:900;cursor:pointer}
.mt-airport-list{display:none;position:absolute;left:0;right:0;top:58px;z-index:110;background:#fff;border:1px solid #dce6ef;border-radius:12px;box-shadow:0 16px 45px rgba(8,47,95,.18);overflow:hidden;max-height:310px;overflow-y:auto}
.mt-airport-list.open{display:block}
.mt-airport-item{padding:11px 12px;border-bottom:1px solid #edf2f6;cursor:pointer}
.mt-airport-item:last-child{border-bottom:0}
.mt-airport-item:hover{background:#f3f8fd}
.mt-airport-item strong{display:block;font-size:12px;color:#0b2b51}
.mt-airport-item small{display:block;margin-top:2px;font-size:9px;color:#7c90a3}
.mt-note{margin-top:13px;color:#71869a;font-size:10px}
.flatpickr-calendar{font-family:Inter,sans-serif!important;border-radius:14px!important;box-shadow:0 18px 50px rgba(8,47,95,.2)!important}
.flatpickr-day.selected{background:#0f82d3!important;border-color:#0f82d3!important}
@media(max-width:1100px){.mt-search-row{grid-template-columns:1fr 42px 1fr 1fr 1fr}.mt-search-button{min-height:48px}}
@media(max-width:700px){.mt-flight-head{flex-direction:column;align-items:start}.mt-search-row{grid-template-columns:1fr}.mt-swap{display:none}.mt-search-button{height:50px}}
</style>

<section class="mt-flight-page">
<div class="mt-flight-wrap">

    <div class="mt-flight-head">
        <div>
            <h1>Search flights</h1>
            <p>Live fares powered by our flight booking provider.</p>
        </div>
        <span class="mt-engine-badge">MUSTAFA FLIGHT ENGINE</span>
    </div>

    <form class="mt-search-card" method="get" action="flight-results.php" id="flightSearchForm">

        <input type="hidden" name="trip_type" id="tripType" value="round">
        <input type="hidden" name="origin" id="originCode" value="BCN">
        <input type="hidden" name="destination" id="destinationCode" value="">
        <input type="hidden" name="adults" id="adultInput" value="1">
        <input type="hidden" name="children" id="childInput" value="0">
        <input type="hidden" name="infants" id="infantInput" value="0">

        <div class="mt-trip-tabs">
            <button type="button" class="mt-trip-tab active" data-trip="round">Round trip</button>
            <button type="button" class="mt-trip-tab" data-trip="oneway">One way</button>
        </div>

        <div class="mt-search-row">

            <div class="mt-field">
                <label>FROM</label>
                <input type="text" id="originText" value="Barcelona (BCN)" placeholder="City or airport" autocomplete="off" required>
                <div class="mt-airport-list" id="originList"></div>
            </div>

            <button type="button" class="mt-swap" id="swapAirports">⇄</button>

            <div class="mt-field">
                <label>TO</label>
                <input type="text" id="destinationText" placeholder="City or airport" autocomplete="off" required>
                <div class="mt-airport-list" id="destinationList"></div>
            </div>

            <div class="mt-field">
                <label>DEPARTURE</label>
                <input type="text" name="departure" id="departureDate" placeholder="Select date" autocomplete="off" required>
            </div>

            <div class="mt-field" id="returnBox">
                <label>RETURN</label>
                <input type="text" name="return_date" id="returnDate" placeholder="Select date" autocomplete="off" required>
            </div>

            <div class="mt-field mt-traveller-trigger" id="travellerTrigger">
                <label>TRAVELLERS & CABIN</label>
                <strong id="travellerText">1 traveller</strong>
                <small id="cabinText">Economy</small>

                <div class="mt-pax-popup" id="paxPopup">
                    <div class="mt-pax-row">
                        <div><strong>Adults</strong><small>12+ years</small></div>
                        <div class="mt-step"><button type="button" data-pax="adult" data-delta="-1">−</button><span id="adultCount">1</span><button type="button" data-pax="adult" data-delta="1">+</button></div>
                    </div>
                    <div class="mt-pax-row">
                        <div><strong>Children</strong><small>2–11 years</small></div>
                        <div class="mt-step"><button type="button" data-pax="child" data-delta="-1">−</button><span id="childCount">0</span><button type="button" data-pax="child" data-delta="1">+</button></div>
                    </div>
                    <div class="mt-pax-row">
                        <div><strong>Infants</strong><small>Under 2</small></div>
                        <div class="mt-step"><button type="button" data-pax="infant" data-delta="-1">−</button><span id="infantCount">0</span><button type="button" data-pax="infant" data-delta="1">+</button></div>
                    </div>
                    <div class="mt-pax-row">
                        <div><strong>Cabin</strong></div>
                        <select name="cabin" id="cabinSelect">
                            <option value="economy">Economy</option>
                            <option value="premium_economy">Premium Economy</option>
                            <option value="business">Business</option>
                            <option value="first">First</option>
                        </select>
                    </div>
                    <button type="button" class="mt-pax-done" id="paxDone">Done</button>
                </div>
            </div>

            <button class="mt-search-button" type="submit">Search flights</button>

        </div>

        <div class="mt-note">Start typing a city or airport, e.g. Barcelona, Lahore, Islamabad, Jeddah, Bogotá or Dubai.</div>
    </form>

</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){

    const airports = [
        ['BCN','Barcelona','Barcelona–El Prat Airport'],
        ['MAD','Madrid','Adolfo Suárez Madrid–Barajas Airport'],
        ['LHE','Lahore','Allama Iqbal International Airport'],
        ['ISB','Islamabad','Islamabad International Airport'],
        ['KHI','Karachi','Jinnah International Airport'],
        ['SKT','Sialkot','Sialkot International Airport'],
        ['JED','Jeddah','King Abdulaziz International Airport'],
        ['MED','Madinah','Prince Mohammad bin Abdulaziz Airport'],
        ['DXB','Dubai','Dubai International Airport'],
        ['AUH','Abu Dhabi','Zayed International Airport'],
        ['DOH','Doha','Hamad International Airport'],
        ['IST','Istanbul','Istanbul Airport'],
        ['LHR','London','Heathrow Airport'],
        ['LGW','London','Gatwick Airport'],
        ['CDG','Paris','Charles de Gaulle Airport'],
        ['ORY','Paris','Orly Airport'],
        ['FCO','Rome','Fiumicino Airport'],
        ['MXP','Milan','Malpensa Airport'],
        ['LIS','Lisbon','Humberto Delgado Airport'],
        ['OPO','Porto','Francisco Sá Carneiro Airport'],
        ['BOG','Bogotá','El Dorado International Airport'],
        ['DAC','Dhaka','Hazrat Shahjalal International Airport'],
        ['DEL','Delhi','Indira Gandhi International Airport'],
        ['BOM','Mumbai','Chhatrapati Shivaji Maharaj Airport'],
        ['CMB','Colombo','Bandaranaike International Airport'],
        ['MNL','Manila','Ninoy Aquino International Airport'],
        ['BKK','Bangkok','Suvarnabhumi Airport'],
        ['KUL','Kuala Lumpur','Kuala Lumpur International Airport'],
        ['SIN','Singapore','Changi Airport'],
        ['JFK','New York','John F. Kennedy International Airport'],
        ['EWR','New York','Newark Liberty International Airport'],
        ['YYZ','Toronto','Toronto Pearson International Airport']
    ];

    function setupAirport(inputId, hiddenId, listId){
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        const list = document.getElementById(listId);

        function render(){
            const q = input.value.trim().toLowerCase();
            list.innerHTML = '';

            if(q.length < 2){
                list.classList.remove('open');
                return;
            }

            const matches = airports.filter(a =>
                a[0].toLowerCase().includes(q) ||
                a[1].toLowerCase().includes(q) ||
                a[2].toLowerCase().includes(q)
            ).slice(0,10);

            if(!matches.length){
                list.classList.remove('open');
                return;
            }

            matches.forEach(a => {
                const row = document.createElement('div');
                row.className = 'mt-airport-item';
                row.innerHTML = '<strong>'+a[1]+' ('+a[0]+')</strong><small>'+a[2]+'</small>';
                row.addEventListener('click', function(){
                    input.value = a[1]+' ('+a[0]+')';
                    hidden.value = a[0];
                    list.classList.remove('open');
                });
                list.appendChild(row);
            });

            list.classList.add('open');
        }

        input.addEventListener('input', function(){
            hidden.value = '';
            render();
        });

        input.addEventListener('focus', render);

        document.addEventListener('click', function(e){
            if(!input.parentElement.contains(e.target)) list.classList.remove('open');
        });
    }

    setupAirport('originText','originCode','originList');
    setupAirport('destinationText','destinationCode','destinationList');

    document.getElementById('swapAirports').addEventListener('click', function(){
        const ot = document.getElementById('originText');
        const oc = document.getElementById('originCode');
        const dt = document.getElementById('destinationText');
        const dc = document.getElementById('destinationCode');

        [ot.value,dt.value] = [dt.value,ot.value];
        [oc.value,dc.value] = [dc.value,oc.value];
    });

    const tripType = document.getElementById('tripType');
    const returnBox = document.getElementById('returnBox');
    const returnDate = document.getElementById('returnDate');

    function setTrip(type){
        tripType.value = type;
        document.querySelectorAll('.mt-trip-tab').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.trip === type);
        });

        if(type === 'oneway'){
            returnBox.style.display = 'none';
            returnDate.removeAttribute('required');
            returnDate.value = '';
        } else {
            returnBox.style.display = '';
            returnDate.setAttribute('required','required');
        }
    }

    document.querySelectorAll('.mt-trip-tab').forEach(btn => {
        btn.addEventListener('click', () => setTrip(btn.dataset.trip));
    });

    const returnPicker = flatpickr('#returnDate',{
        dateFormat:'Y-m-d',
        altInput:true,
        altFormat:'D, d M Y',
        minDate:'today',
        disableMobile:true
    });

    flatpickr('#departureDate',{
        dateFormat:'Y-m-d',
        altInput:true,
        altFormat:'D, d M Y',
        minDate:'today',
        disableMobile:true,
        onChange:function(d,dateStr){
            if(dateStr) returnPicker.set('minDate',dateStr);
        }
    });

    const trigger = document.getElementById('travellerTrigger');
    const popup = document.getElementById('paxPopup');

    trigger.addEventListener('click', function(e){
        if(!e.target.closest('.mt-step') && !e.target.closest('select') && e.target.id !== 'paxDone'){
            popup.classList.add('open');
        }
    });

    document.getElementById('paxDone').addEventListener('click', function(e){
        e.stopPropagation();
        popup.classList.remove('open');
    });

    document.addEventListener('click', function(e){
        if(!trigger.contains(e.target)) popup.classList.remove('open');
    });

    const state = {adult:1,child:0,infant:0};

    function syncPax(){
        state.adult = Math.max(1,Math.min(9,state.adult));
        state.child = Math.max(0,Math.min(8,state.child));
        state.infant = Math.max(0,Math.min(state.adult,state.infant));

        document.getElementById('adultInput').value = state.adult;
        document.getElementById('childInput').value = state.child;
        document.getElementById('infantInput').value = state.infant;

        document.getElementById('adultCount').textContent = state.adult;
        document.getElementById('childCount').textContent = state.child;
        document.getElementById('infantCount').textContent = state.infant;

        const total = state.adult + state.child + state.infant;
        document.getElementById('travellerText').textContent = total + (total===1?' traveller':' travellers');
    }

    document.querySelectorAll('[data-pax][data-delta]').forEach(btn => {
        btn.addEventListener('click', function(e){
            e.stopPropagation();
            state[btn.dataset.pax] += parseInt(btn.dataset.delta,10);
            syncPax();
        });
    });

    document.getElementById('cabinSelect').addEventListener('change', function(){
        document.getElementById('cabinText').textContent =
            this.options[this.selectedIndex].text;
    });

    document.getElementById('flightSearchForm').addEventListener('submit', function(e){
        if(!document.getElementById('originCode').value || !document.getElementById('destinationCode').value){
            e.preventDefault();
            alert('Please select both airports from the suggestions.');
        }
    });

    setTrip('round');
    syncPax();
});
</script>

<?php site_footer(); ?>
