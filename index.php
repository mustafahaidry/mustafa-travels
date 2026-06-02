<?php
// ========== UMRAH QUOTATION BUILDER ==========
// Professional tool for Expedia & Bedsonline partnership
// All prices are per bed per night | 50 SAR commission added internally

// Exchange rate: 1 SAR = 0.245 EUR
define('SAR_TO_EUR', 0.245);

// Hotel Databases - Makkah Hotels with rates (per bed per night in SAR)
$makkahHotels = [
    ["name" => "Ajwa Zaifa", "distance" => "Shuttle Service (2 Star)", "rates" => ["sharing" => 13, "quad" => 13, "trp" => 15, "dbl" => 18, "single" => 25]],
    ["name" => "Qila Ajyad", "distance" => "1000 m (Comp.Shuttle)", "rates" => ["sharing" => 17, "quad" => 17, "trp" => 20, "dbl" => 25, "single" => 35]],
    ["name" => "Dyar Matar", "distance" => "1200 m (Economy)", "rates" => ["sharing" => 19, "quad" => 19, "trp" => 23, "dbl" => 28, "single" => 40]],
    ["name" => "Jada Khalil", "distance" => "1200 m (Star)", "rates" => ["sharing" => 21, "quad" => 21, "trp" => 25, "dbl" => 32, "single" => 45]],
    ["name" => "Kiswah Tower", "distance" => "Shuttle Service", "rates" => ["sharing" => 24, "quad" => 24, "trp" => 29, "dbl" => 37, "single" => 53]],
    ["name" => "Multiqa Ibadat & Tara Jawarat", "distance" => "750-800 m (Star)", "rates" => ["sharing" => 24, "quad" => 24, "trp" => 29, "dbl" => 37, "single" => 53]],
    ["name" => "Saif Al Majd", "distance" => "600-650 m (Star)", "rates" => ["sharing" => 31, "quad" => 31, "trp" => 38, "dbl" => 48, "single" => 70]],
    ["name" => "Jafria (Masar Al Aez 2)", "distance" => "550-600 m (Star)", "rates" => ["sharing" => 31, "quad" => 31, "trp" => 38, "dbl" => 48, "single" => 70]],
    ["name" => "Jawarat Bait (Arafat Zehbi)", "distance" => "600 m (Star)", "rates" => ["sharing" => 38, "quad" => 38, "trp" => 43, "dbl" => 55, "single" => 80]],
    ["name" => "Badar Masa", "distance" => "600 m (Room Basis)", "rates" => ["sharing" => 57, "quad" => 57, "trp" => 70, "dbl" => 92, "single" => 135]],
    ["name" => "Swiss Khalil / Blora Moazan", "distance" => "350-400 m (Star)", "rates" => ["sharing" => 49, "quad" => 49, "trp" => 63, "dbl" => 93, "single" => 93]],
    ["name" => "Emar Andulusia", "distance" => "300 m (Room Basis)", "rates" => ["sharing" => 68, "quad" => 68, "trp" => 88, "dbl" => 130, "single" => 130]]
];

$madinahHotels = [
    ["name" => "Kinan Madina", "distance" => "900 m (Economy Plus)", "rates" => ["sharing" => 25, "quad" => 25, "trp" => 30, "dbl" => 38, "single" => 55]],
    ["name" => "Dar Ajyad 1", "distance" => "750 m (Economy Plus)", "rates" => ["sharing" => 29, "quad" => 29, "trp" => 35, "dbl" => 45, "single" => 65]],
    ["name" => "Abdullah Fouzan (Dyar Hijaz)", "distance" => "600 m (Economy Plus)", "rates" => ["sharing" => 35, "quad" => 35, "trp" => 43, "dbl" => 55, "single" => 80]],
    ["name" => "Karam Golden", "distance" => "550 m (Star)", "rates" => ["sharing" => 37, "quad" => 37, "trp" => 45, "dbl" => 58, "single" => 85]],
    ["name" => "Ansar Plus", "distance" => "500 m (Economy Plus)", "rates" => ["sharing" => 38, "quad" => 38, "trp" => 46, "dbl" => 60, "single" => 88]],
    ["name" => "Widyar Al Madina / Rou Khair", "distance" => "350 m (Star)", "rates" => ["sharing" => 40, "quad" => 40, "trp" => 49, "dbl" => 63, "single" => 93]],
    ["name" => "Rou Taiba", "distance" => "100 m (Star)", "rates" => ["sharing" => 55, "quad" => 55, "trp" => 63, "dbl" => 82, "single" => 120]]
];

// Function to convert SAR to EUR with internal 50 SAR commission
function getFinalEuroPrice($sarRate) {
    $withCommission = $sarRate + 50; // 50 SAR commission added internally
    return ($withCommission * SAR_TO_EUR);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UmrahQuo Pro | Professional Umrah Quotation System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f2f5;
            font-family: 'Inter', sans-serif;
            padding: 40px 20px;
            color: #1a2c1c;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 32px;
            color: #0a2b22;
            font-weight: 700;
        }
        .header p {
            color: #666;
            margin-top: 10px;
        }
        .badge {
            display: inline-block;
            background: #d4af37;
            color: #0a2b22;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        /* Main Layout */
        .quotation-wrapper {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .hotel-panel {
            flex: 2;
            min-width: 320px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .extras-panel {
            flex: 1.2;
            min-width: 300px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* City Tabs */
        .city-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .city-tab {
            flex: 1;
            padding: 18px;
            text-align: center;
            font-weight: 600;
            cursor: pointer;
            background: transparent;
            border: none;
            font-size: 16px;
            transition: all 0.3s;
            color: #666;
        }
        .city-tab.active {
            background: white;
            color: #1f6e43;
            border-bottom: 3px solid #d4af37;
        }

        /* Hotel Selector */
        .hotel-selector {
            padding: 25px;
            border-bottom: 1px solid #eee;
        }
        .hotel-selector label {
            font-weight: 600;
            display: block;
            margin-bottom: 10px;
            color: #0a2b22;
        }
        .hotel-dropdown {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            background: white;
        }
        .hotel-dropdown:focus {
            outline: none;
            border-color: #d4af37;
        }

        /* Hotel Details */
        .hotel-details {
            padding: 25px;
        }
        .hotel-name {
            font-size: 22px;
            font-weight: 700;
            color: #0a2b22;
            margin-bottom: 8px;
        }
        .distance {
            display: inline-block;
            background: #e8f5e9;
            color: #1f6e43;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .per-bed-note {
            display: inline-block;
            background: #f5e8c8;
            color: #8B6914;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }

        /* Nights Section */
        .nights-section {
            background: #f8f9fa;
            padding: 18px;
            border-radius: 16px;
            margin-bottom: 25px;
        }
        .nights-label {
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }
        .nights-input {
            width: 100px;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }
        .total-nights {
            margin-top: 10px;
            font-size: 13px;
            color: #1f6e43;
            font-weight: 600;
        }

        /* Room Options */
        .room-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 10px;
        }
        .room-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .room-option:hover {
            background: #f0f2f5;
        }
        .room-option.selected {
            background: #e8f5e9;
            border-color: #1f6e43;
        }
        .room-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .radio-custom {
            width: 20px;
            height: 20px;
            accent-color: #1f6e43;
        }
        .room-badge {
            font-weight: 700;
            background: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 13px;
            color: #0a2b22;
        }
        .rate-editable {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .rate-input {
            width: 80px;
            padding: 8px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
        }
        .final-price {
            font-weight: 700;
            color: #1f6e43;
            font-size: 16px;
        }
        .price-note {
            font-size: 11px;
            color: #999;
        }

        /* Extras Panel */
        .extras-header {
            padding: 25px;
            border-bottom: 1px solid #eee;
        }
        .extras-header h3 {
            color: #0a2b22;
            font-size: 20px;
        }
        .service-item {
            padding: 18px 25px;
            border-bottom: 1px solid #eee;
        }
        .service-title {
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }
        .taxi-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
        }
        .taxi-item:last-child {
            border-bottom: none;
        }
        .taxi-btn {
            background: #f0f2f5;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }
        .taxi-btn.active {
            background: #1f6e43;
            color: white;
        }
        .visa-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 14px;
            margin-top: 8px;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        .checkbox-label input {
            width: 18px;
            height: 18px;
        }

        /* Summary Box */
        .summary-box {
            background: #0a2b22;
            color: white;
            padding: 25px;
            margin: 0 25px 25px 25px;
            border-radius: 20px;
        }
        .summary-box h4 {
            margin-bottom: 15px;
            font-size: 18px;
        }
        .total-amount {
            font-size: 36px;
            font-weight: 800;
            background: #d4af37;
            display: inline-block;
            padding: 8px 24px;
            border-radius: 60px;
            color: #0a2b22;
            margin: 15px 0;
        }
        .whatsapp-btn {
            display: inline-block;
            background: #25D366;
            color: white;
            padding: 14px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 15px;
            width: 100%;
            text-align: center;
            transition: all 0.2s;
        }
        .whatsapp-btn:hover {
            background: #20b859;
        }
        .not-included {
            font-size: 12px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .better-rate {
            text-align: center;
            padding: 20px;
            background: #fef3e2;
            margin: 20px;
            border-radius: 16px;
        }
        .better-rate a {
            color: #1f6e43;
            font-weight: 600;
            text-decoration: none;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #888;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            body { padding: 20px; }
            .room-option { flex-direction: column; gap: 10px; align-items: stretch; }
            .room-left { justify-content: space-between; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🕋 UmrahQuo Pro</h1>
        <p>Professional Umrah Quotation System | Partner Rates</p>
        <div class="badge">✨ Expedia & Bedsonline Partner ✨</div>
    </div>

    <div class="quotation-wrapper">
        <!-- LEFT PANEL - HOTEL SELECTION -->
        <div class="hotel-panel">
            <div class="city-tabs">
                <button class="city-tab active" data-city="makkah">🕋 MAKKAH HOTELS</button>
                <button class="city-tab" data-city="madinah">🕌 MADINAH HOTELS</button>
            </div>
            
            <div class="hotel-selector">
                <label>🏨 Select Hotel</label>
                <select id="hotelSelect" class="hotel-dropdown"></select>
            </div>
            
            <div id="hotelDetails" class="hotel-details">
                <!-- Dynamic content loaded via JS -->
                <div style="text-align: center; padding: 40px; color: #999;">Loading hotels...</div>
            </div>
        </div>

        <!-- RIGHT PANEL - EXTRAS & TOTAL -->
        <div class="extras-panel">
            <div class="extras-header">
                <h3>➕ Additional Services</h3>
            </div>
            
            <div class="service-item">
                <span class="service-title">🕌 Ziyarat Tours</span>
                <label class="checkbox-label">
                    <input type="checkbox" id="makkahZiyarat"> Makkah Ziyarat (by bus) - +7 SAR service fee
                </label>
                <label class="checkbox-label" style="margin-top: 10px;">
                    <input type="checkbox" id="madinahZiyarat"> Madinah Ziyarat (by bus) - +7 SAR service fee
                </label>
            </div>
            
            <div class="service-item">
                <span class="service-title">🛂 Visa Fee</span>
                <select id="visaSelect" class="visa-select">
                    <option value="220">Pakistani Umrah Visa — 220 EUR</option>
                    <option value="120">Spanish Umrah Visa — 120 EUR</option>
                </select>
            </div>
            
            <div class="service-item">
                <span class="service-title">🚖 Private Taxi Transfers</span>
                <div class="taxi-item">
                    <span>Jeddah (JED) → Makkah</span>
                    <button class="taxi-btn" data-taxi="jeddahMakkah">➕ Select (350 SAR)</button>
                </div>
                <div class="taxi-item">
                    <span>Makkah → Madinah</span>
                    <button class="taxi-btn" data-taxi="makkahMadinah">➕ Select (400 SAR)</button>
                </div>
                <div class="taxi-item">
                    <span>Madinah → Jeddah</span>
                    <button class="taxi-btn" data-taxi="madinahJeddah">➕ Select (350 SAR)</button>
                </div>
            </div>
            
            <div class="summary-box">
                <h4>💰 TOTAL QUOTATION</h4>
                <div class="total-amount" id="grandTotal">0.00 €</div>
                <div class="not-included">
                    <strong>✈️ NOT INCLUDED:</strong><br>
                    Airline ticket • Meals • Personal expenses
                </div>
                <a href="#" id="whatsappQuotationBtn" class="whatsapp-btn">
                    <i class="fab fa-whatsapp"></i> Send Quotation via WhatsApp
                </a>
            </div>
            
            <div class="better-rate">
                🌟 Want better rates than these hotels?<br>
                <a href="#" id="betterRateBtn">📱 Contact our B2B desk on WhatsApp →</a>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>All prices are per bed per night. Final EUR price includes partner adjustments. Distances in meters.</p>
        <p>© 2026 Mustafa Travels & Tours | Partner with Expedia & Bedsonline</p>
    </div>
</div>

<script>
// ========== CONFIGURATION ==========
const SAR_TO_EUR = 0.245;

function convertSarToEur(sar) {
    return (sar * SAR_TO_EUR).toFixed(2);
}

// Hotel Data from PHP
const makkahHotels = <?php echo json_encode($makkahHotels); ?>;
const madinahHotels = <?php echo json_encode($madinahHotels); ?>;

// State Variables
let currentCity = 'makkah';
let currentHotel = null;
let selectedRoom = 'sharing';
let numberOfNights = 5;
let roomOverrides = {};
let taxiSelections = {
    jeddahMakkah: false,
    makkahMadinah: false,
    madinahJeddah: false
};

// Helper: Get final nightly EUR for a room (includes internal 50 SAR commission)
function getNightlyEuroForRoom(roomKey) {
    if (!currentHotel) return 0;
    let baseRate = currentHotel.rates[roomKey];
    if (roomOverrides[roomKey] !== undefined) {
        baseRate = roomOverrides[roomKey];
    }
    const withCommission = baseRate + 50; // 50 SAR commission added internally (hidden from user)
    return parseFloat(convertSarToEur(withCommission));
}

// Helper: Get total hotel cost (nightly × nights)
function getTotalHotelEuro() {
    if (!currentHotel || !selectedRoom) return 0;
    const nightly = getNightlyEuroForRoom(selectedRoom);
    return nightly * numberOfNights;
}

// Helper: Get Ziyarat total EUR
function getZiyaratEuro() {
    let total = 0;
    const makkahChecked = document.getElementById('makkahZiyarat')?.checked;
    const madinahChecked = document.getElementById('madinahZiyarat')?.checked;
    // Base ziyarat cost 35 SAR + our 7 SAR fee = 42 SAR total
    if (makkahChecked) total += parseFloat(convertSarToEur(35 + 7));
    if (madinahChecked) total += parseFloat(convertSarToEur(35 + 7));
    return total;
}

// Helper: Get selected taxi total EUR
function getTaxiTotalEuro() {
    let total = 0;
    if (taxiSelections.jeddahMakkah) total += parseFloat(convertSarToEur(350));
    if (taxiSelections.makkahMadinah) total += parseFloat(convertSarToEur(400));
    if (taxiSelections.madinahJeddah) total += parseFloat(convertSarToEur(350));
    return total;
}

// Update grand total
function updateGrandTotal() {
    const hotelTotal = getTotalHotelEuro();
    const visaEuro = parseFloat(document.getElementById('visaSelect').value);
    const taxiTotal = getTaxiTotalEuro();
    const ziyaratTotal = getZiyaratEuro();
    const total = hotelTotal + visaEuro + taxiTotal + ziyaratTotal;
    document.getElementById('grandTotal').innerHTML = total.toFixed(2) + ' €';
}

// Populate hotel dropdown
function populateHotelSelect() {
    const hotels = currentCity === 'makkah' ? makkahHotels : madinahHotels;
    const select = document.getElementById('hotelSelect');
    select.innerHTML = '';
    hotels.forEach((hotel, index) => {
        const option = document.createElement('option');
        option.value = index;
        option.textContent = `${hotel.name} — ${hotel.distance}`;
        select.appendChild(option);
    });
    if (hotels.length) {
        loadHotelDetails(0);
    }
}

// Load hotel details and render rooms
function loadHotelDetails(index) {
    const hotels = currentCity === 'makkah' ? makkahHotels : madinahHotels;
    if (!hotels[index]) return;
    currentHotel = hotels[index];
    
    const roomKeys = Object.keys(currentHotel.rates);
    if (!selectedRoom || !roomKeys.includes(selectedRoom)) {
        selectedRoom = roomKeys[0];
    }
    
    let html = `
        <div class="hotel-name">🏨 ${currentHotel.name}</div>
        <div>
            <span class="distance">📍 ${currentHotel.distance}</span>
            <span class="per-bed-note">🛏️ Per bed / per night</span>
        </div>
        
        <div class="nights-section">
            <label class="nights-label">📅 Number of Nights</label>
            <input type="number" id="nightsInput" class="nights-input" value="${numberOfNights}" min="1" max="30">
            <div class="total-nights" id="totalNightsDisplay">Total: ${(getNightlyEuroForRoom(selectedRoom) * numberOfNights).toFixed(2)} EUR for ${numberOfNights} nights</div>
        </div>
        
        <div class="room-grid">
    `;
    
    roomKeys.forEach(room => {
        const baseRate = currentHotel.rates[room];
        const currentOverride = roomOverrides[room] !== undefined ? roomOverrides[room] : baseRate;
        const nightlyEuro = getNightlyEuroForRoom(room);
        const isSelected = (selectedRoom === room);
        
        html += `
            <div class="room-option ${isSelected ? 'selected' : ''}" data-room="${room}" onclick="selectRoom('${room}')">
                <div class="room-left">
                    <input type="radio" name="roomRadio" class="radio-custom" value="${room}" ${isSelected ? 'checked' : ''} onclick="event.stopPropagation(); selectRoom('${room}')">
                    <span class="room-badge">${room.toUpperCase()}</span>
                    <div class="rate-editable" onclick="event.stopPropagation()">
                        <input type="number" class="rate-input" data-room="${room}" value="${currentOverride}" step="5">
                        <span style="font-size:12px;">SAR</span>
                    </div>
                </div>
                <div>
                    <span class="final-price">€${nightlyEuro.toFixed(2)}</span>
                    <span class="price-note"> /night/bed</span>
                </div>
            </div>
        `;
    });
    
    html += `</div>`;
    document.getElementById('hotelDetails').innerHTML = html;
    
    // Attach event listeners
    const nightsInput = document.getElementById('nightsInput');
    if (nightsInput) {
        nightsInput.addEventListener('change', (e) => {
            numberOfNights = parseInt(e.target.value) || 1;
            updateTotalNightsDisplay();
            updateGrandTotal();
        });
    }
    
    // Attach rate input listeners
    document.querySelectorAll('.rate-input').forEach(input => {
        const room = input.getAttribute('data-room');
        input.addEventListener('input', (e) => {
            const newVal = parseFloat(e.target.value);
            if (!isNaN(newVal) && newVal >= 0) {
                roomOverrides[room] = newVal;
            } else {
                delete roomOverrides[room];
            }
            updateRoomDisplay(room);
            if (selectedRoom === room) {
                updateTotalNightsDisplay();
                updateGrandTotal();
            }
        });
    });
    
    updateTotalNightsDisplay();
    updateGrandTotal();
}

function updateTotalNightsDisplay() {
    const nightly = getNightlyEuroForRoom(selectedRoom);
    const total = nightly * numberOfNights;
    const displayDiv = document.getElementById('totalNightsDisplay');
    if (displayDiv) {
        displayDiv.innerHTML = `Total: ${total.toFixed(2)} EUR for ${numberOfNights} nights`;
    }
}

function updateRoomDisplay(room) {
    const nightlyEuro = getNightlyEuroForRoom(room);
    const roomDiv = document.querySelector(`.room-option[data-room="${room}"]`);
    if (roomDiv) {
        const priceSpan = roomDiv.querySelector('.final-price');
        if (priceSpan) {
            priceSpan.innerHTML = `€${nightlyEuro.toFixed(2)}`;
        }
    }
}

function selectRoom(room) {
    selectedRoom = room;
    // Update UI
    document.querySelectorAll('.room-option').forEach(opt => {
        opt.classList.remove('selected');
    });
    const selectedDiv = document.querySelector(`.room-option[data-room="${room}"]`);
    if (selectedDiv) {
        selectedDiv.classList.add('selected');
    }
    const radio = document.querySelector(`input[name="roomRadio"][value="${room}"]`);
    if (radio) radio.checked = true;
    
    updateTotalNightsDisplay();
    updateGrandTotal();
}

// City tab switching
function initCityTabs() {
    const tabs = document.querySelectorAll('.city-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            currentCity = tab.getAttribute('data-city');
            roomOverrides = {};
            selectedRoom = 'sharing';
            numberOfNights = 5;
            populateHotelSelect();
        });
    });
}

// Taxi button handlers
function initTaxiButtons() {
    const taxiBtns = document.querySelectorAll('.taxi-btn');
    taxiBtns.forEach(btn => {
        const taxiKey = btn.getAttribute('data-taxi');
        btn.addEventListener('click', () => {
            taxiSelections[taxiKey] = !taxiSelections[taxiKey];
            if (taxiSelections[taxiKey]) {
                btn.textContent = `✓ Selected (${btn.textContent.match(/\d+/)?.[0] || ''} SAR)`;
                btn.classList.add('active');
            } else {
                const originalText = btn.getAttribute('data-original') || btn.textContent.replace('✓ Selected', '➕ Select');
                btn.textContent = originalText;
                btn.classList.remove('active');
            }
            updateGrandTotal();
        });
        // Store original text
        btn.setAttribute('data-original', btn.textContent);
    });
}

// WhatsApp handlers
function initWhatsAppButtons() {
    const quotationBtn = document.getElementById('whatsappQuotationBtn');
    const betterRateBtn = document.getElementById('betterRateBtn');
    const whatsappNumber = '34611473217'; // Without + for URL
    
    quotationBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const hotelName = currentHotel?.name || 'Not selected';
        const roomType = selectedRoom.toUpperCase();
        const total = document.getElementById('grandTotal').innerText;
        const nights = numberOfNights;
        const taxis = Object.entries(taxiSelections).filter(([,v]) => v).map(([k]) => k.replace(/([A-Z])/g, ' $1').trim()).join(', ') || 'None';
        const msg = `🕋 *UMRAH QUOTATION*\n\n🏨 Hotel: ${hotelName}\n🛏️ Room: ${roomType}\n🌙 Nights: ${nights}\n💰 Total: ${total}\n\n🚖 Selected Taxis: ${taxis}\n\n✅ Includes: Hotel (per bed/night), visa fee, selected taxis, ziyarat\n❌ Not included: Airline ticket, meals, personal expenses\n\n📞 Please process my booking.`;
        window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(msg)}`, '_blank');
    });
    
    betterRateBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const msg = `Hello, I'm interested in better rates for Umrah hotels. I'm a partner with Expedia/Bedsonline. Please share your best offers.`;
        window.open(`https://wa.me/${whatsappNumber}?text=${encodeURIComponent(msg)}`, '_blank');
    });
}

// Event listeners for extras
function initExtrasListeners() {
    document.getElementById('makkahZiyarat').addEventListener('change', () => updateGrandTotal());
    document.getElementById('madinahZiyarat').addEventListener('change', () => updateGrandTotal());
    document.getElementById('visaSelect').addEventListener('change', () => updateGrandTotal());
}

// Hotel select change
document.getElementById('hotelSelect').addEventListener('change', (e) => {
    loadHotelDetails(parseInt(e.target.value));
});

// Initialize
function init() {
    initCityTabs();
    populateHotelSelect();
    initTaxiButtons();
    initExtrasListeners();
    initWhatsAppButtons();
}
init();
</script>
</body>
</html>
