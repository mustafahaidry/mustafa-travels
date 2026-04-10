<?php
// 🔑 Duffel Live Token
$apiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';
$flightResults = '';
$searchPerformed = false;
$selectedOffer = null;

// Airport database
$airportsList = [
    ['code' => 'BCN', 'name' => 'Barcelona-El Prat, Spain'],
    ['code' => 'MAD', 'name' => 'Madrid-Barajas, Spain'],
    ['code' => 'LHE', 'name' => 'Lahore, Pakistan'],
    ['code' => 'ISB', 'name' => 'Islamabad, Pakistan'],
    ['code' => 'KHI', 'name' => 'Karachi, Pakistan'],
    ['code' => 'JFK', 'name' => 'New York, USA'],
    ['code' => 'LHR', 'name' => 'London, UK'],
    ['code' => 'DXB', 'name' => 'Dubai, UAE'],
    ['code' => 'SIN', 'name' => 'Singapore'],
];

function getAirportName($code) {
    global $airportsList;
    foreach ($airportsList as $a) if ($a['code'] == $code) return $a['name'];
    return $code;
}

// Handle flight search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_flights'])) {
    $searchPerformed = true;
    $origin = strtoupper(trim($_POST['origin']));
    $destination = strtoupper(trim($_POST['destination']));
    $date = $_POST['departure_date'];
    $passengers = intval($_POST['passengers']);
    $cabinClass = $_POST['cabin_class'] ?? 'economy';
    
    $searchData = [
        'data' => [
            'slices' => [[
                'origin' => $origin,
                'destination' => $destination,
                'departure_date' => $date
            ]],
            'passengers' => array_fill(0, $passengers, ['type' => 'adult']),
            'cabin_class' => $cabinClass,
            'max_connections' => 1
        ]
    ];
    
    $ch = curl_init('https://api.duffel.com/air/offer_requests?return_offers=true');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
        'Duffel-Version: v2'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($searchData));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response === false) {
        $flightResults = '<div class="error">❌ Connection error. Please try again.</div>';
    } else {
        $data = json_decode($response, true);
        $offers = $data['data']['offers'] ?? [];
        
        if (count($offers) > 0) {
            $flightResults = '<div class="success-header">✈️ Found ' . count($offers) . ' flights</div>';
            
            foreach (array_slice($offers, 0, 15) as $offer) {
                $slice = $offer['slices'][0];
                $segments = $slice['segments'] ?? [];
                $first = $segments[0] ?? null;
                $last = $segments[count($segments)-1] ?? null;
                
                $depTime = $first ? date('h:i A, M j', strtotime($first['departing_at'])) : 'N/A';
                $arrTime = $last ? date('h:i A, M j', strtotime($last['arriving_at'])) : 'N/A';
                
                $duration = 0;
                foreach ($segments as $s) $duration += intval($s['duration'] ?? 0);
                $durText = floor($duration/60).'h '.($duration%60).'m';
                $stops = count($segments)-1;
                $stopText = $stops == 0 ? 'Direct' : $stops.' stop'.($stops>1?'s':'');
                $airline = $offer['owner']['name'] ?? 'Airline';
                $price = $offer['total_amount'] ?? '0';
                $currency = $offer['total_currency'] ?? 'EUR';
                $offerId = $offer['id'] ?? '';
                
                $flightResults .= '
                <div class="flight-card" onclick="selectFlight(\''.$offerId.'\', \''.$price.'\', \''.$currency.'\', \''.addslashes($airline).'\', \''.$origin.'\', \''.$destination.'\', \''.$depTime.'\', \''.$arrTime.'\', \''.$durText.'\', \''.$stopText.'\')">
                    <div class="flight-header">
                        <div class="airline-info">
                            <div class="airline-icon">✈️</div>
                            <div><div class="airline-name">'.htmlspecialchars($airline).'</div></div>
                        </div>
                        <div class="flight-price">'.$price.' '.$currency.'</div>
                    </div>
                    <div class="flight-route">
                        <div><div class="city-code">'.$origin.'</div><div class="city-name">'.getAirportName($origin).'</div><div class="flight-time">'.$depTime.'</div></div>
                        <div class="flight-duration"><div class="duration-line"></div><div class="duration-text">'.$durText.'</div><div class="stops-text">'.$stopText.'</div></div>
                        <div><div class="city-code">'.$destination.'</div><div class="city-name">'.getAirportName($destination).'</div><div class="flight-time">'.$arrTime.'</div></div>
                    </div>
                    <button class="select-flight-btn">Select Flight</button>
                </div>';
            }
        } else {
            $flightResults = '<div class="error">✈️ No flights found. Try different date.</div>';
        }
    }
}

// Handle booking confirmation
$bookingConfirmation = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $offerId = $_POST['offer_id'];
    $price = $_POST['price'];
    $currency = $_POST['currency'];
    $airline = $_POST['airline'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $depTime = $_POST['dep_time'];
    $arrTime = $_POST['arr_time'];
    $duration = $_POST['duration'];
    $stops = $_POST['stops'];
    
    $passengerTitle = $_POST['passenger_title'];
    $passengerGivenName = $_POST['passenger_given_name'];
    $passengerFamilyName = $_POST['passenger_family_name'];
    $passengerDob = $_POST['passenger_dob'];
    $passengerGender = $_POST['passenger_gender'];
    $passengerPassport = $_POST['passenger_passport'];
    $passengerPassportExpiry = $_POST['passenger_passport_expiry'];
    $contactEmail = $_POST['contact_email'];
    $contactPhone = $_POST['contact_phone'];
    
    // Build WhatsApp message
    $whatsappMsg = "🛫 *NEW FLIGHT BOOKING REQUEST* 🛫\n\n";
    $whatsappMsg .= "✈️ *FLIGHT DETAILS*\n";
    $whatsappMsg .= "Airline: $airline\n";
    $whatsappMsg .= "Route: $origin → $destination\n";
    $whatsappMsg .= "Departure: $depTime\n";
    $whatsappMsg .= "Arrival: $arrTime\n";
    $whatsappMsg .= "Duration: $duration | $stops\n";
    $whatsappMsg .= "Total Price: $price $currency\n\n";
    
    $whatsappMsg .= "👤 *PASSENGER DETAILS*\n";
    $whatsappMsg .= "Title: $passengerTitle\n";
    $whatsappMsg .= "Full Name: $passengerGivenName $passengerFamilyName\n";
    $whatsappMsg .= "Date of Birth: $passengerDob\n";
    $whatsappMsg .= "Gender: $passengerGender\n";
    $whatsappMsg .= "Passport No: $passengerPassport\n";
    $whatsappMsg .= "Passport Expiry: $passengerPassportExpiry\n\n";
    
    $whatsappMsg .= "📞 *CONTACT DETAILS*\n";
    $whatsappMsg .= "Email: $contactEmail\n";
    $whatsappMsg .= "Phone: $contactPhone\n\n";
    
    $whatsappMsg .= "⏳ Please confirm availability and send payment link.";
    
    // Redirect to WhatsApp
    header("Location: https://wa.me/34611473217?text=" . urlencode($whatsappMsg));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels | Book Flights Worldwide</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <style>
        :root {
            --primary-gold: #d4af37;
            --primary-navy: #1a237e;
            --primary-teal: #00695c;
            --light-gold: #f5e8c8;
            --light-bg: #f9f7f2;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--light-bg);
            line-height: 1.7;
        }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        
        /* Two Column Layout */
        .two-columns { display: flex; gap: 30px; margin: 40px 0; }
        .main-content { flex: 3; }
        .sidebar { flex: 1; }
        
        /* Header */
        .elegant-header {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-teal) 100%);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .header-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-wrap: wrap;
            gap: 15px;
        }
        .contact-info-elegant { display: flex; gap: 30px; flex-wrap: wrap; }
        .contact-info-elegant span { display: flex; align-items: center; gap: 8px; font-size: 14px; }
        .contact-info-elegant i { color: var(--primary-gold); }
        .social-elegant a { color: white; margin-left: 15px; font-size: 16px; transition: var(--transition); }
        .social-elegant a:hover { color: var(--primary-gold); }
        .main-header-elegant { padding: 20px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .logo-elegant { display: flex; align-items: center; gap: 20px; text-decoration: none; }
        .logo-icon-elegant { background: var(--primary-gold); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; color: var(--primary-navy); box-shadow: var(--shadow); }
        .logo-main-elegant { font-family: 'Crimson Text', serif; font-size: 28px; font-weight: 700; color: white; }
        .logo-sub-elegant { font-size: 12px; color: var(--light-gold); letter-spacing: 2px; }
        .nav-elegant { display: flex; gap: 30px; flex-wrap: wrap; }
        .nav-elegant a { color: white; text-decoration: none; font-weight: 500; font-size: 15px; transition: var(--transition); }
        .nav-elegant a:hover { color: var(--primary-gold); }
        .whatsapp-btn-elegant {
            background: #25D366;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }
        .whatsapp-btn-elegant:hover { transform: translateY(-3px); }
        
        /* Flying Marquee */
        .flying-marquee-container {
            background: linear-gradient(135deg, var(--primary-navy) 0%, #0d1440 100%);
            padding: 10px 0;
            border-bottom: 2px solid var(--primary-gold);
        }
        .marquee-track { height: 35px; display: flex; align-items: center; overflow: hidden; position: relative; }
        .marquee-content { display: flex; animation: marqueeScroll 30s linear infinite; white-space: nowrap; }
        .marquee-text { color: white; font-size: 15px; padding: 0 25px; display: flex; align-items: center; }
        .marquee-text:before { content: '•'; color: var(--primary-gold); margin-right: 15px; }
        .flying-plane {
            position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);
            background: var(--primary-gold); width: 50px; height: 50px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            animation: planeFloat 3s ease-in-out infinite;
        }
        .flying-plane i { color: var(--primary-navy); font-size: 20px; transform: rotate(45deg); }
        
        /* Hero Slider */
        .luxury-slider { height: 450px; position: relative; overflow: hidden; border-radius: 0 0 var(--radius) var(--radius); }
        .luxury-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; opacity: 0; transition: opacity 1.2s; }
        .luxury-slide.active { opacity: 1; }
        .slide-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(26,35,126,0.85), rgba(0,105,92,0.85)); display: flex; align-items: center; }
        .slide-content-luxury { max-width: 600px; padding-left: 60px; color: white; }
        .slide-content-luxury h2 { font-size: 42px; color: white; margin-bottom: 20px; font-family: 'Playfair Display', serif; }
        .slide-content-luxury p { font-size: 18px; margin-bottom: 35px; }
        .luxury-btn { display: inline-flex; align-items: center; gap: 12px; background: var(--primary-gold); color: var(--primary-navy); padding: 14px 28px; border-radius: 50px; font-weight: 600; text-decoration: none; transition: var(--transition); }
        .luxury-btn:hover { transform: translateY(-3px); background: var(--light-gold); }
        .slider-controls { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; }
        .slider-dot-luxury { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; }
        .slider-dot-luxury.active { background: var(--primary-gold); transform: scale(1.2); }
        
        /* Search Card */
        .search-luxury {
            background: white;
            padding: 35px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-top: -50px;
            position: relative;
            z-index: 10;
            margin-bottom: 30px;
            border-top: 4px solid var(--primary-gold);
        }
        .section-header { text-align: center; margin-bottom: 40px; }
        .section-header h2 { font-size: 36px; color: var(--primary-navy); position: relative; display: inline-block; }
        .section-header h2:after { content: ''; position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); width: 80px; height: 4px; background: var(--primary-gold); }
        
        /* Trip Type Toggle */
        .trip-toggle { display: flex; gap: 20px; margin-bottom: 25px; justify-content: center; flex-wrap: wrap; }
        .trip-option { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 20px; border-radius: 50px; transition: var(--transition); }
        .trip-option:hover { background: var(--light-gold); }
        .trip-option input { width: 18px; height: 18px; cursor: pointer; }
        .trip-option label { cursor: pointer; font-weight: 500; }
        
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: var(--primary-navy); margin-bottom: 8px; font-size: 14px; }
        .form-group select, .form-group input { width: 100%; padding: 14px 16px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 16px; transition: var(--transition); }
        .form-group select:focus, .form-group input:focus { border-color: var(--primary-gold); outline: none; }
        .multi-city-row { background: #f8f9fa; padding: 20px; border-radius: 16px; margin-bottom: 20px; }
        .search-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 16px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; transition: var(--transition); }
        .search-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        
        /* Flight Results */
        .flight-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid #eee;
        }
        .flight-card:hover { transform: translateY(-5px); border-left: 4px solid var(--primary-gold); }
        .flight-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 15px; }
        .airline-info { display: flex; align-items: center; gap: 15px; }
        .airline-icon { width: 45px; height: 45px; background: var(--primary-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
        .airline-name { font-weight: 700; font-size: 16px; color: var(--primary-navy); }
        .flight-price { font-size: 24px; font-weight: 800; color: var(--primary-teal); }
        .flight-route { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin: 15px 0; }
        .city-code { font-weight: 800; font-size: 20px; color: var(--primary-navy); }
        .city-name { font-size: 11px; color: #666; }
        .flight-time { font-weight: 600; font-size: 14px; margin-top: 5px; }
        .flight-duration { text-align: center; flex: 1; }
        .duration-line { height: 2px; background: var(--primary-gold); width: 100%; }
        .select-flight-btn {
            background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy));
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: var(--transition);
            margin-top: 10px;
        }
        .select-flight-btn:hover { transform: translateY(-2px); }
        .error { background: #ffebee; padding: 15px; border-radius: 12px; color: #c62828; }
        .success-header { background: #e8f5e9; padding: 12px; border-radius: 12px; margin-bottom: 20px; color: #2e7d32; }
        
        /* Sidebar Widgets */
        .sidebar-widget {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
            border-top: 4px solid var(--primary-gold);
        }
        .sidebar-widget h3 {
            font-size: 20px;
            color: var(--primary-navy);
            margin-bottom: 20px;
            border-left: 3px solid var(--primary-gold);
            padding-left: 12px;
        }
        .flight-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 12px;
            transition: var(--transition);
        }
        .flight-info:hover { transform: translateX(5px); background: #f0f2f5; }
        .flight-time { font-weight: 700; color: var(--primary-navy); font-size: 16px; }
        .flight-route { font-size: 13px; color: #666; margin: 5px 0; }
        .flight-status {
            font-size: 12px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            display: inline-block;
        }
        .status-on-time { background: #e8f5e9; color: #2e7d32; }
        .status-delayed { background: #fff3e0; color: #e65100; }
        .status-departed { background: #e3f2fd; color: #1565c0; }
        
        .airline-tag {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-navy), var(--primary-teal));
            color: white;
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 12px;
            margin: 5px;
            transition: var(--transition);
        }
        .airline-tag:hover { transform: translateY(-2px); background: var(--primary-gold); color: var(--primary-navy); }
        
        /* Booking Modal */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); display: flex; align-items: center; justify-content: center;
            z-index: 10000; opacity: 0; visibility: hidden; transition: 0.3s;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content {
            background: white; padding: 35px; border-radius: 24px;
            max-width: 700px; width: 90%; position: relative;
            max-height: 90vh; overflow-y: auto;
        }
        .close-modal {
            position: absolute; top: 15px; right: 15px;
            background: none; border: none; font-size: 24px; cursor: pointer;
        }
        
        /* Booking Form */
        .booking-section { margin-bottom: 25px; }
        .booking-section h3 {
            color: var(--primary-navy);
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary-gold);
            display: inline-block;
        }
        .booking-summary {
            background: linear-gradient(135deg, #f8f9fa, #fff);
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary-gold);
        }
        .price-breakdown {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 12px;
            margin-top: 15px;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .price-row.total {
            font-weight: 700;
            color: var(--primary-teal);
            font-size: 18px;
            border-bottom: none;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid var(--primary-gold);
        }
        .booking-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .booking-form-group { margin-bottom: 15px; }
        .booking-form-group label { display: block; font-weight: 600; color: var(--primary-navy); margin-bottom: 5px; font-size: 13px; }
        .booking-form-group input, .booking-form-group select {
            width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 14px;
        }
        .booking-form-group input:focus { border-color: var(--primary-gold); outline: none; }
        .confirm-btn {
            background: #25D366; color: white; padding: 16px; border: none;
            border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer;
            width: 100%; transition: var(--transition);
        }
        .confirm-btn:hover { transform: translateY(-3px); }
        
        .packages-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 30px; }
        .package-card { background: white; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); transition: var(--transition); }
        .package-card:hover { transform: translateY(-5px); }
        .package-image { height: 180px; background-size: cover; background-position: center; position: relative; }
        .package-badge { position: absolute; top: 15px; right: 15px; background: var(--primary-gold); padding: 5px 15px; border-radius: 50px; font-weight: 700; }
        .package-content { padding: 20px; }
        .package-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; width: 100%; transition: var(--transition); }
        .package-btn:hover { transform: translateY(-2px); }
        
        .footer-elegant { background: linear-gradient(135deg, var(--primary-navy) 0%, #0d1440 100%); color: white; padding: 60px 0 30px; margin-top: 40px; }
        .footer-content { display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; margin-bottom: 40px; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); }
        
        @keyframes marqueeScroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        @keyframes planeFloat { 0%,100% { transform: translate(-50%, -50%) translateY(0); } 50% { transform: translate(-50%, -50%) translateY(-8px); } }
        
        @media (max-width: 992px) {
            .two-columns { flex-direction: column; }
            .packages-grid { grid-template-columns: 1fr; }
            .footer-content { grid-template-columns: 1fr; }
            .booking-form-row { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .slide-content-luxury { padding: 0 25px; text-align: center; }
            .nav-elegant { display: none; flex-direction: column; background: rgba(26,35,126,0.95); padding: 20px; border-radius: var(--radius); }
            .nav-elegant.active { display: flex; }
            .mobile-menu-toggle { display: block; background: var(--primary-gold); padding: 12px; border-radius: 50px; text-align: center; width: fit-content; margin: 10px auto; cursor: pointer; color: var(--primary-navy); }
        }
        .mobile-menu-toggle { display: none; }
    </style>
</head>
<body>

<header class="elegant-header">
    <div class="container">
        <div class="header-top-bar">
            <div class="contact-info-elegant">
                <span><i class="fas fa-phone"></i> +34-632234216</span>
                <span><i class="fab fa-whatsapp"></i> +34-611473217</span>
                <span><i class="fas fa-map-marker-alt"></i> Rambla Badal 141, Barcelona</span>
            </div>
            <div class="social-elegant">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="https://wa.me/34611473217"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div class="main-header-elegant">
            <a href="#" class="logo-elegant">
                <div class="logo-icon-elegant"><i class="fas fa-kaaba"></i></div>
                <div class="logo-text-elegant">
                    <div class="logo-main-elegant">MUSTAFA TRAVELS & TOURS</div>
                    <div class="logo-sub-elegant">PREMIUM TRAVEL EXPERIENCES</div>
                </div>
            </a>
            <div class="mobile-menu-toggle"><i class="fas fa-bars"></i></div>
            <nav class="nav-elegant">
                <a href="#home">Home</a>
                <a href="#umrah">Umrah</a>
                <a href="#hajj">Hajj 2026</a>
                <a href="#flights">Flight Deals</a>
                <a href="#services">Services</a>
                <a href="https://wa.me/34611473217" class="whatsapp-btn-elegant"><i class="fab fa-whatsapp"></i> Book Now</a>
            </nav>
        </div>
    </div>
</header>

<div class="flying-marquee-container">
    <div class="marquee-track">
        <div class="marquee-content">
            <span class="marquee-text">✈️ SPECIAL FLIGHT DEALS ✈️ BCN TO LHE: €580 ✈️ BCN TO ISB: €585 ✈️ BCN TO LHR: €79 ✈️ 40KG BAGGAGE ✈️ CALL +34-632234216 ✈️</span>
            <span class="marquee-text">✈️ SPECIAL FLIGHT DEALS ✈️ BCN TO LHE: €580 ✈️ BCN TO ISB: €585 ✈️ BCN TO LHR: €79 ✈️ 40KG BAGGAGE ✈️ CALL +34-632234216 ✈️</span>
        </div>
        <div class="flying-plane"><i class="fas fa-plane"></i></div>
    </div>
</div>

<section class="luxury-slider" id="home">
    <div class="luxury-slide active" style="background-image: url('https://images.pexels.com/photos/33270402/pexels-photo-33270402.jpeg');">
        <div class="slide-overlay"><div class="slide-content-luxury"><h2>Premium Umrah 2026</h2><p>Luxury packages near Haram</p><a href="#umrah" class="luxury-btn">Explore</a></div></div>
    </div>
    <div class="luxury-slide" style="background-image: url('https://images.pexels.com/photos/29102586/pexels-photo-29102586.jpeg');">
        <div class="slide-overlay"><div class="slide-content-luxury"><h2>Hajj 2026</h2><p>Coming Soon</p><a href="#hajj" class="luxury-btn">Register</a></div></div>
    </div>
    <div class="slider-controls"><div class="slider-dot-luxury active"></div><div class="slider-dot-luxury"></div></div>
</div>

<div class="container">
    <div class="search-luxury">
        <h3 style="text-align:center; margin-bottom:20px; font-size:24px; color:var(--primary-navy);">✈️ Search Flights</h3>
        <form method="POST" action="" id="searchForm">
            <div class="trip-toggle">
                <label class="trip-option"><input type="radio" name="trip_type" value="oneway" checked onchange="toggleTripType()"> <label>✈️ One Way</label></label>
                <label class="trip-option"><input type="radio" name="trip_type" value="return" onchange="toggleTripType()"> <label>🔄 Return</label></label>
                <label class="trip-option"><input type="radio" name="trip_type" value="multi" onchange="toggleTripType()"> <label>🌍 Multi-City</label></label>
            </div>
            
            <div id="onewayFields">
                <div class="form-row">
                    <div class="form-group"><label>✈️ From</label><select name="origin" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                    <div class="form-group"><label>📍 To</label><select name="destination" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                    <div class="form-group"><label>📅 Departure</label><input type="date" name="departure_date" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"></div>
                </div>
            </div>
            
            <div id="returnFields" style="display:none">
                <div class="form-row">
                    <div class="form-group"><label>✈️ From</label><select name="origin_return" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                    <div class="form-group"><label>📍 To</label><select name="destination_return" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                    <div class="form-group"><label>📅 Departure</label><input type="date" name="departure_date_return" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"></div>
                    <div class="form-group"><label>🔄 Return</label><input type="date" name="return_date" value="<?php echo date('Y-m-d', strtotime('+37 days')); ?>"></div>
                </div>
            </div>
            
            <div id="multiFields" style="display:none">
                <?php for($i=1;$i<=3;$i++){ ?>
                <div class="multi-city-row"><h4 style="margin-bottom:15px">Segment <?php echo $i; ?></h4>
                <div class="form-row">
                    <div class="form-group"><label>From</label><select name="origin_<?php echo $i; ?>" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                    <div class="form-group"><label>To</label><select name="destination_<?php echo $i; ?>" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                    <div class="form-group"><label>Date</label><input type="date" name="date_<?php echo $i; ?>" value="<?php echo date('Y-m-d', strtotime('+'.(30+$i*5).' days')); ?>"></div>
                </div></div>
                <?php } ?>
            </div>
            
            <div class="form-row">
                <div class="form-group"><label>👥 Passengers</label><select name="passengers"><option value="1">1 Adult</option><option value="2">2 Adults</option><option value="3">3 Adults</option><option value="4">4 Adults</option></select></div>
                <div class="form-group"><label>🛋️ Cabin</label><select name="cabin_class"><option value="economy">Economy</option><option value="business">Business</option><option value="first">First</option></select></div>
            </div>
            <button type="submit" name="search_flights" class="search-btn">🔍 Search Flights</button>
        </form>
        <?php if ($searchPerformed): ?><div style="margin-top:30px"><?php echo $flightResults; ?></div><?php endif; ?>
    </div>
    
    <div class="two-columns">
        <div class="main-content">
            <div id="umrah" style="padding:20px 0">
                <div class="section-header"><h2>🕋 Premium Umrah Packages</h2></div>
                <div class="packages-grid">
                    <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/4346403/pexels-photo-4346403.jpeg')"><div class="package-badge">€895</div></div><div class="package-content"><h3>Essence Umrah</h3><button class="package-btn" onclick="bookPackage('Essence Umrah', '€895')">View Details</button></div></div>
                    <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/2895295/pexels-photo-2895295.jpeg')"><div class="package-badge">€999</div></div><div class="package-content"><h3>Enhanced Umrah</h3><button class="package-btn" onclick="bookPackage('Enhanced Umrah', '€999')">View Details</button></div></div>
                    <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/12808985/pexels-photo-12808985.jpeg')"><div class="package-badge">€1,299</div></div><div class="package-content"><h3>Elite Umrah</h3><button class="package-btn" onclick="bookPackage('Elite Umrah', '€1,299')">View Details</button></div></div>
                </div>
            </div>
            
            <div id="hajj" style="background:#f8f9fa; border-radius:20px; padding:40px; text-align:center; margin:30px 0; border:2px dashed var(--primary-gold)">
                <i class="fas fa-clock" style="font-size:48px; color:var(--primary-gold)"></i>
                <h3 style="color:var(--primary-navy); margin:15px 0">Hajj 2026 - CLOSED FOR NOW</h3>
                <p>Phase 2 bookings opening soon</p>
                <a href="https://wa.me/34611473217?text=Interested in Hajj 2026" class="whatsapp-btn-elegant" style="margin-top:15px; display:inline-block">Notify Me</a>
            </div>
            
            <div id="flights" style="margin-bottom:30px">
                <div class="section-header"><h2>⭐ Exclusive Flight Deals</h2></div>
                <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:20px">
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)"><div><strong>✈️ Etihad Airways</strong><br>Barcelona → Lahore</div><div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€580</div><a href="https://wa.me/34611473217?text=BCN to LHE €580" class="whatsapp-btn-elegant" style="display:inline-block; padding:8px 16px; font-size:12px">Book</a></div>
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)"><div><strong>✈️ Etihad Airways</strong><br>Barcelona → Islamabad</div><div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€585</div><a href="https://wa.me/34611473217?text=BCN to ISB €585" class="whatsapp-btn-elegant" style="display:inline-block; padding:8px 16px; font-size:12px">Book</a></div>
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)"><div><strong>✈️ Emirates</strong><br>Barcelona → Dubai</div><div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€299</div><a href="https://wa.me/34611473217?text=BCN to DXB €299" class="whatsapp-btn-elegant" style="display:inline-block; padding:8px 16px; font-size:12px">Book</a></div>
                </div>
            </div>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-widget">
                <h3><i class="fas fa-plane-departure"></i> BCN Departures</h3>
                <div class="flight-info"><div class="flight-time">08:30</div><div class="flight-route">BCN → LHR (British Airways)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">09:15</div><div class="flight-route">BCN → CDG (Air France)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">10:00</div><div class="flight-route">BCN → FCO (ITA Airways)</div><span class="flight-status status-delayed">Delayed 30min</span></div>
                <div class="flight-info"><div class="flight-time">11:20</div><div class="flight-route">BCN → DXB (Emirates)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">12:45</div><div class="flight-route">BCN → JFK (Delta)</div><span class="flight-status status-departed">Departed</span></div>
            </div>
            
            <div class="sidebar-widget">
                <h3><i class="fas fa-plane-arrival"></i> BCN Arrivals</h3>
                <div class="flight-info"><div class="flight-time">09:45</div><div class="flight-route">LHR → BCN (British Airways)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">10:30</div><div class="flight-route">CDG → BCN (Air France)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">11:15</div><div class="flight-route">DXB → BCN (Emirates)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">13:00</div><div class="flight-route">LHE → BCN (Etihad)</div><span class="flight-status status-delayed">Delayed 45min</span></div>
            </div>
            
            <div class="sidebar-widget">
                <h3><i class="fas fa-building"></i> Partner Airlines</h3>
                <div>
                    <span class="airline-tag">Etihad Airways</span>
                    <span class="airline-tag">Emirates</span>
                    <span class="airline-tag">Qatar Airways</span>
                    <span class="airline-tag">British Airways</span>
                    <span class="airline-tag">Air France</span>
                    <span class="airline-tag">Turkish Airlines</span>
                    <span class="airline-tag">Singapore Airlines</span>
                    <span class="airline-tag">Delta Air Lines</span>
                    <span class="airline-tag">Lufthansa</span>
                    <span class="airline-tag">KLM</span>
                    <span class="airline-tag">Iberia</span>
                    <span class="airline-tag">Vueling</span>
                </div>
            </div>
            
            <div class="sidebar-widget">
                <h3><i class="fas fa-headset"></i> 24/7 Support</h3>
                <div style="text-align:center">
                    <a href="tel:+34632234216" style="display:block; background:#00695c; color:white; padding:12px; border-radius:50px; margin-bottom:10px; text-decoration:none;"><i class="fas fa-phone"></i> Call +34-632234216</a>
                    <a href="https://wa.me/34611473217" style="display:block; background:#25D366; color:white; padding:12px; border-radius:50px; text-decoration:none;"><i class="fab fa-whatsapp"></i> WhatsApp +34-611473217</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Section -->
<section style="background:var(--light-bg); padding:60px 0">
    <div class="container">
        <div class="section-header"><h2>⭐ Our Services</h2></div>
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:25px">
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-passport"></i></div><h3>Visa Processing</h3></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-hotel"></i></div><h3>Luxury Hotels</h3></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-car"></i></div><h3>Airport Transfers</h3></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-headset"></i></div><h3>24/7 Support</h3></div>
        </div>
    </div>
</section>

<!-- About Section -->
<section style="padding:60px 0">
    <div class="container">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:50px; align-items:center">
            <div><h2 style="font-size:36px; color:var(--primary-navy); margin-bottom:20px">About Mustafa Travels</h2><p>Since 2024, we've been crafting exceptional travel experiences. Specializing in Umrah, Hajj & worldwide flights.</p><p style="margin-top:15px"><strong>500+ Happy Travelers | 50+ Destinations | ⭐ 4.9/5 Rating</strong></p></div>
            <div style="height:350px; background-image:url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'); background-size:cover; border-radius:20px"></div>
        </div>
    </div>
</section>

<footer class="footer-elegant">
    <div class="container">
        <div class="footer-content">
            <div><h3>Mustafa Travels</h3><p>Rambla Badal 141, Barcelona<br>📞 +34-632234216<br>💬 +34-611473217<br>✉️ mustafatravelstours@gmail.com</p></div>
            <div><h3>Quick Links</h3><p><a href="#home" style="color:white">Home</a></p><p><a href="#umrah" style="color:white">Umrah</a></p><p><a href="#hajj" style="color:white">Hajj</a></p></div>
            <div><h3>Destinations</h3><p>Pakistan | India | USA | UK | UAE | Europe</p></div>
            <div><h3>Hours</h3><p>Mon-Thu: 10:30-20:30<br>Fri: 10:30-13:00 & 15:00-20:30<br>Sat: 10:30-19:30</p></div>
        </div>
        <div class="footer-bottom"><p>&copy; 2026 Mustafa Travels & Tours. All rights reserved.</p></div>
    </div>
</footer>

<!-- Booking Modal -->
<div class="modal-overlay" id="bookingModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeBookingModal()">&times;</button>
        <h2 style="color: var(--primary-navy); margin-bottom: 20px;">✈️ Complete Your Booking</h2>
        
        <form method="POST" action="" id="bookingForm">
            <input type="hidden" name="offer_id" id="offer_id">
            <input type="hidden" name="price" id="price">
            <input type="hidden" name="currency" id="currency">
            <input type="hidden" name="airline" id="airline">
            <input type="hidden" name="origin" id="origin">
            <input type="hidden" name="destination" id="destination">
            <input type="hidden" name="dep_time" id="dep_time">
            <input type="hidden" name="arr_time" id="arr_time">
            <input type="hidden" name="duration" id="duration">
            <input type="hidden" name="stops" id="stops">
            
            <div class="booking-summary" id="flightSummary"></div>
            
            <div class="booking-section">
                <h3>👤 Passenger Details</h3>
                <div class="booking-form-row">
                    <div class="booking-form-group"><label>Title *</label><select name="passenger_title" required><option value="Mr">Mr</option><option value="Mrs">Mrs</option><option value="Ms">Ms</option><option value="Dr">Dr</option></select></div>
                    <div class="booking-form-group"><label>Given Name *</label><input type="text" name="passenger_given_name" placeholder="First name" required></div>
                    <div class="booking-form-group"><label>Family Name *</label><input type="text" name="passenger_family_name" placeholder="Last name" required></div>
                </div>
                <div class="booking-form-row">
                    <div class="booking-form-group"><label>Date of Birth *</label><input type="date" name="passenger_dob" required></div>
                    <div class="booking-form-group"><label>Gender *</label><select name="passenger_gender" required><option value="male">Male</option><option value="female">Female</option></select></div>
                </div>
                <div class="booking-form-row">
                    <div class="booking-form-group"><label>Passport Number *</label><input type="text" name="passenger_passport" placeholder="Passport number" required></div>
                    <div class="booking-form-group"><label>Passport Expiry *</label><input type="date" name="passenger_passport_expiry" required></div>
                </div>
            </div>
            
            <div class="booking-section">
                <h3>📞 Contact Details</h3>
                <div class="booking-form-row">
                    <div class="booking-form-group"><label>Email Address *</label><input type="email" name="contact_email" placeholder="your@email.com" required></div>
                    <div class="booking-form-group"><label>Phone Number *</label><input type="tel" name="contact_phone" placeholder="+34 XXXXXXX" required></div>
                </div>
            </div>
            
            <div class="price-breakdown" id="priceBreakdown"></div>
            
            <button type="submit" name="confirm_booking" class="confirm-btn">✅ Confirm & Send to WhatsApp</button>
            <p style="font-size:12px; color:#666; margin-top:15px; text-align:center">Payment will be confirmed via WhatsApp. Our team will contact you shortly.</p>
        </form>
    </div>
</div>

<script>
$(document).ready(function() { $('.airport-select').select2({ width: '100%' }); });

// Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.luxury-slide');
const dots = document.querySelectorAll('.slider-dot-luxury');
function showSlide(i) { slides.forEach(s=>s.classList.remove('active')); dots.forEach(d=>d.classList.remove('active')); slides[i].classList.add('active'); dots[i].classList.add('active'); currentSlide=i; }
setInterval(()=>{ currentSlide = (currentSlide+1)%slides.length; showSlide(currentSlide); }, 5000);
dots.forEach((d,i)=>d.addEventListener('click',()=>showSlide(i)));

// Mobile menu
document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() { document.querySelector('.nav-elegant')?.classList.toggle('active'); });

function bookPackage(name, price) { window.open(`https://wa.me/34611473217?text=I'm interested in ${name} (${price})`, '_blank'); }

function toggleTripType() {
    const type = document.querySelector('input[name="trip_type"]:checked').value;
    document.getElementById('onewayFields').style.display = type == 'oneway' ? 'block' : 'none';
    document.getElementById('returnFields').style.display = type == 'return' ? 'block' : 'none';
    document.getElementById('multiFields').style.display = type == 'multi' ? 'block' : 'none';
}

function selectFlight(offerId, price, currency, airline, origin, destination, depTime, arrTime, duration, stops) {
    document.getElementById('offer_id').value = offerId;
    document.getElementById('price').value = price;
    document.getElementById('currency').value = currency;
    document.getElementById('airline').value = airline;
    document.getElementById('origin').value = origin;
    document.getElementById('destination').value = destination;
    document.getElementById('dep_time').value = depTime;
    document.getElementById('arr_time').value = arrTime;
    document.getElementById('duration').value = duration;
    document.getElementById('stops').value = stops;
    
    // Calculate fare breakdown
    let fare = Math.round(price * 0.59);
    let taxes = price - fare;
    
    document.getElementById('flightSummary').innerHTML = `
        <strong>✈️ Flight Details</strong><br>
        ${airline}<br>
        ${origin} → ${destination}<br>
        Departure: ${depTime}<br>
        Arrival: ${arrTime}<br>
        Duration: ${duration} | ${stops}
    `;
    
    document.getElementById('priceBreakdown').innerHTML = `
        <div class="price-row"><span>Fare</span><span>${fare.toFixed(2)} ${currency}</span></div>
        <div class="price-row"><span>Taxes & Fees</span><span>${taxes.toFixed(2)} ${currency}</span></div>
        <div class="price-row total"><span>Total</span><span>${price} ${currency}</span></div>
    `;
    
    document.getElementById('bookingModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() {
    document.getElementById('bookingModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

document.querySelectorAll('.close-modal, .modal-overlay').forEach(el => {
    el.addEventListener('click', (e) => { if(e.target === document.getElementById('bookingModal') || e.target.classList.contains('close-modal')) closeBookingModal(); });
});

toggleTripType();
showSlide(0);
</script>
</body>
</html>
