<?php
// 🔑 Duffel Live Token
$apiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';
$flightResults = '';
$searchPerformed = false;
$selectedOffer = null;

// Airport database
$airportsList = [
    ['code' => 'BCN', 'name' => 'Barcelona-El Prat, Spain', 'country' => 'Spain'],
    ['code' => 'MAD', 'name' => 'Madrid-Barajas, Spain', 'country' => 'Spain'],
    ['code' => 'LHE', 'name' => 'Allama Iqbal International, Lahore', 'country' => 'Pakistan'],
    ['code' => 'ISB', 'name' => 'Islamabad International, Pakistan', 'country' => 'Pakistan'],
    ['code' => 'KHI', 'name' => 'Jinnah International, Karachi', 'country' => 'Pakistan'],
    ['code' => 'JFK', 'name' => 'John F Kennedy, New York', 'country' => 'USA'],
    ['code' => 'LHR', 'name' => 'London Heathrow, UK', 'country' => 'United Kingdom'],
    ['code' => 'DXB', 'name' => 'Dubai International, UAE', 'country' => 'UAE'],
    ['code' => 'SIN', 'name' => 'Changi International, Singapore', 'country' => 'Singapore'],
];

function getAirportName($code) {
    global $airportsList;
    foreach ($airportsList as $airport) {
        if ($airport['code'] == $code) return $airport['name'];
    }
    return $code;
}

// Handle direct booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_flight'])) {
    $offerId = $_POST['offer_id'];
    $passengerName = $_POST['passenger_name'];
    $passengerEmail = $_POST['passenger_email'];
    $passengerPhone = $_POST['passenger_phone'];
    
    // Send WhatsApp message to admin
    $whatsappMsg = "🛫 NEW BOOKING REQUEST 🛫\n\n";
    $whatsappMsg .= "Offer ID: " . $offerId . "\n";
    $whatsappMsg .= "Passenger: " . $passengerName . "\n";
    $whatsappMsg .= "Email: " . $passengerEmail . "\n";
    $whatsappMsg .= "Phone: " . $passengerPhone . "\n";
    $whatsappMsg .= "Amount: Pending confirmation\n\n";
    $whatsappMsg .= "Please confirm availability and send payment link.";
    
    // Redirect to WhatsApp
    header("Location: https://wa.me/34611473217?text=" . urlencode($whatsappMsg));
    exit();
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
            
            foreach (array_slice($offers, 0, 10) as $offer) {
                $slice = $offer['slices'][0];
                $segments = $slice['segments'] ?? [];
                $firstSegment = $segments[0] ?? null;
                $lastSegment = $segments[count($segments) - 1] ?? null;
                
                $depTime = $firstSegment ? date('h:i A', strtotime($firstSegment['departing_at'])) : 'N/A';
                $arrTime = $lastSegment ? date('h:i A', strtotime($lastSegment['arriving_at'])) : 'N/A';
                
                $totalDuration = 0;
                foreach ($segments as $segment) {
                    $totalDuration += intval($segment['duration'] ?? 0);
                }
                $duration = floor($totalDuration / 60) . 'h ' . ($totalDuration % 60) . 'm';
                $stops = count($segments) - 1;
                $stopText = $stops == 0 ? 'Direct' : $stops . ' stop' . ($stops > 1 ? 's' : '');
                
                $airline = $offer['owner']['name'] ?? 'Airline';
                $price = $offer['total_amount'] ?? '0';
                $currency = $offer['total_currency'] ?? 'EUR';
                $offerId = $offer['id'] ?? '';
                
                $flightResults .= '
                <div class="flight-card">
                    <div class="flight-header">
                        <div class="airline-info">
                            <div class="airline-icon">✈️</div>
                            <div><div class="airline-name">' . htmlspecialchars($airline) . '</div></div>
                        </div>
                        <div class="flight-price">' . $price . ' ' . $currency . '</div>
                    </div>
                    <div class="flight-route">
                        <div><div class="city-code">' . $origin . '</div><div class="city-name">' . getAirportName($origin) . '</div><div class="flight-time">' . $depTime . '</div></div>
                        <div class="flight-duration"><div class="duration-line"></div><div class="duration-text">' . $duration . '</div><div class="stops-text">' . $stopText . '</div></div>
                        <div><div class="city-code">' . $destination . '</div><div class="city-name">' . getAirportName($destination) . '</div><div class="flight-time">' . $arrTime . '</div></div>
                    </div>
                    <div class="flight-footer">
                        <button class="book-now-btn" onclick="openBookingModal(\'' . $offerId . '\', \'' . $price . '\', \'' . $currency . '\', \'' . addslashes($airline) . '\', \'' . $origin . '\', \'' . $destination . '\', \'' . $depTime . '\')">📝 Book Now</button>
                    </div>
                </div>';
            }
        } else {
            $flightResults = '<div class="error">✈️ No flights found. Try different date.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Mustafa Travels | Book Flights - Umrah - Hajj</title>
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
        .two-columns {
            display: flex;
            gap: 30px;
            margin: 40px 0;
        }
        .main-content { flex: 3; }
        .sidebar { flex: 1; }
        
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
        
        /* Header & Other Styles (same as before) */
        .elegant-header {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-teal) 100%);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
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
        .social-elegant a { color: white; margin-left: 15px; font-size: 16px; }
        .main-header-elegant { padding: 20px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .logo-elegant { display: flex; align-items: center; gap: 20px; text-decoration: none; }
        .logo-icon-elegant { background: var(--primary-gold); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; color: var(--primary-navy); }
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
        }
        
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
        
        .luxury-slider { height: 450px; position: relative; overflow: hidden; border-radius: 0 0 var(--radius) var(--radius); }
        .luxury-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; opacity: 0; transition: opacity 1.2s; }
        .luxury-slide.active { opacity: 1; }
        .slide-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(26,35,126,0.85), rgba(0,105,92,0.85)); display: flex; align-items: center; }
        .slide-content-luxury { max-width: 600px; padding-left: 60px; color: white; }
        .slide-content-luxury h2 { font-size: 42px; color: white; margin-bottom: 20px; }
        .slide-content-luxury p { font-size: 18px; margin-bottom: 35px; }
        .luxury-btn { display: inline-flex; align-items: center; gap: 12px; background: var(--primary-gold); color: var(--primary-navy); padding: 14px 28px; border-radius: 50px; font-weight: 600; text-decoration: none; }
        .slider-controls { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; }
        .slider-dot-luxury { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; }
        .slider-dot-luxury.active { background: var(--primary-gold); transform: scale(1.2); }
        
        .search-luxury {
            background: white;
            padding: 30px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-top: -50px;
            position: relative;
            z-index: 10;
            margin-bottom: 30px;
            border-top: 4px solid var(--primary-gold);
        }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: var(--primary-navy); margin-bottom: 8px; font-size: 14px; }
        .form-group select, .form-group input { width: 100%; padding: 14px 16px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 16px; }
        .search-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 16px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; }
        
        .flight-card { background: white; border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: var(--shadow); transition: var(--transition); }
        .flight-card:hover { transform: translateX(5px); border-left: 4px solid var(--primary-gold); }
        .flight-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 15px; }
        .airline-info { display: flex; align-items: center; gap: 15px; }
        .airline-icon { width: 45px; height: 45px; background: var(--primary-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
        .flight-price { font-size: 24px; font-weight: 800; color: var(--primary-teal); }
        .flight-route { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin: 15px 0; }
        .city-code { font-weight: 800; font-size: 20px; color: var(--primary-navy); }
        .flight-duration { text-align: center; flex: 1; }
        .duration-line { height: 2px; background: var(--primary-gold); width: 100%; }
        .book-now-btn { background: #25D366; color: white; padding: 10px 20px; border-radius: 50px; border: none; cursor: pointer; font-weight: 600; transition: var(--transition); }
        .book-now-btn:hover { transform: scale(1.05); }
        .error { background: #ffebee; padding: 15px; border-radius: 12px; color: #c62828; }
        .success-header { background: #e8f5e9; padding: 12px; border-radius: 12px; margin-bottom: 20px; color: #2e7d32; }
        
        /* Modal */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8);
            display: flex; align-items: center; justify-content: center; z-index: 10000; opacity: 0; visibility: hidden; transition: 0.3s;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content {
            background: white; padding: 35px; border-radius: 24px; max-width: 500px; width: 90%;
            position: relative; max-height: 90vh; overflow-y: auto;
        }
        .close-modal { position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; }
        .booking-form input { width: 100%; padding: 14px; border: 2px solid #e0e0e0; border-radius: 12px; margin-bottom: 15px; font-size: 16px; }
        .booking-form button { background: #25D366; color: white; padding: 14px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; }
        
        .packages-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 30px; }
        .package-card { background: white; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); }
        .package-image { height: 180px; background-size: cover; background-position: center; position: relative; }
        .package-badge { position: absolute; top: 15px; right: 15px; background: var(--primary-gold); padding: 5px 15px; border-radius: 50px; font-weight: 700; }
        .package-content { padding: 20px; }
        .package-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; width: 100%; }
        
        .footer-elegant { background: linear-gradient(135deg, var(--primary-navy) 0%, #0d1440 100%); color: white; padding: 50px 0 30px; margin-top: 40px; }
        .footer-content { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; margin-bottom: 30px; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); }
        
        @keyframes marqueeScroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        @keyframes planeFloat { 0%,100% { transform: translate(-50%, -50%) translateY(0); } 50% { transform: translate(-50%, -50%) translateY(-8px); } }
        
        @media (max-width: 992px) {
            .two-columns { flex-direction: column; }
            .packages-grid { grid-template-columns: 1fr; }
            .footer-content { grid-template-columns: 1fr; }
            .slide-content-luxury { padding: 0 25px; text-align: center; }
            .form-row { grid-template-columns: 1fr; }
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
</section>

<div class="container">
    <div class="search-luxury">
        <h3 style="text-align:center; margin-bottom:20px;">✈️ Search Flights</h3>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group"><label>From</label><select name="origin" class="airport-select"><?php foreach($airportsList as $a){ echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; } ?></select></div>
                <div class="form-group"><label>To</label><select name="destination" class="airport-select"><?php foreach($airportsList as $a){ echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; } ?></select></div>
                <div class="form-group"><label>Departure Date</label><input type="date" name="departure_date" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"></div>
                <div class="form-group"><label>Passengers</label><select name="passengers"><option value="1">1 Adult</option><option value="2">2 Adults</option><option value="3">3 Adults</option></select></div>
                <div class="form-group"><label>Cabin</label><select name="cabin_class"><option value="economy">Economy</option><option value="business">Business</option></select></div>
            </div>
            <button type="submit" name="search_flights" class="search-btn">🔍 Search Flights</button>
        </form>
        <?php if ($searchPerformed): ?><div style="margin-top: 30px;"><?php echo $flightResults; ?></div><?php endif; ?>
    </div>
    
    <!-- Two Column Layout -->
    <div class="two-columns">
        <div class="main-content">
            <!-- Umrah Packages -->
            <div class="packages-luxury" id="umrah" style="padding:0 0 40px 0">
                <h2 class="section-header" style="font-size:32px; text-align:center; margin-bottom:30px;">🕋 Premium Umrah Packages</h2>
                <div class="packages-grid">
                    <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/4346403/pexels-photo-4346403.jpeg')"><div class="package-badge">€895</div></div><div class="package-content"><h3>Essence Umrah</h3><button class="package-btn" onclick="bookPackage('Essence Umrah', '€895')">View Details</button></div></div>
                    <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/2895295/pexels-photo-2895295.jpeg')"><div class="package-badge">€999</div></div><div class="package-content"><h3>Enhanced Umrah</h3><button class="package-btn" onclick="bookPackage('Enhanced Umrah', '€999')">View Details</button></div></div>
                    <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/12808985/pexels-photo-12808985.jpeg')"><div class="package-badge">€1,299</div></div><div class="package-content"><h3>Elite Umrah</h3><button class="package-btn" onclick="bookPackage('Elite Umrah', '€1,299')">View Details</button></div></div>
                </div>
            </div>
            
            <!-- Hajj Section -->
            <div id="hajj" style="background:#f8f9fa; border-radius:20px; padding:40px; text-align:center; margin-bottom:40px; border:2px dashed var(--primary-gold)">
                <i class="fas fa-clock" style="font-size:48px; color:var(--primary-gold)"></i>
                <h3 style="color:var(--primary-navy); margin:15px 0">Hajj 2026 - CLOSED</h3>
                <p>Phase 2 bookings opening soon</p>
                <a href="https://wa.me/34611473217?text=Interested in Hajj 2026" class="whatsapp-btn-elegant" style="margin-top:15px; display:inline-block">Notify Me</a>
            </div>
            
            <!-- Flight Deals -->
            <div id="flights" style="margin-bottom:40px">
                <h2 class="section-header" style="font-size:32px; text-align:center; margin-bottom:30px;">⭐ Exclusive Flight Deals</h2>
                <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(280px,1fr)); gap:20px">
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)"><div><strong>✈️ Etihad Airways</strong><br>Barcelona → Lahore</div><div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€580</div><a href="https://wa.me/34611473217?text=BCN to LHE €580" class="book-now-btn" style="display:inline-block">Book</a></div>
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)"><div><strong>✈️ Etihad Airways</strong><br>Barcelona → Islamabad</div><div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€585</div><a href="https://wa.me/34611473217?text=BCN to ISB €585" class="book-now-btn" style="display:inline-block">Book</a></div>
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)"><div><strong>✈️ Emirates</strong><br>Barcelona → Dubai</div><div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€299</div><a href="https://wa.me/34611473217?text=BCN to DXB €299" class="book-now-btn" style="display:inline-block">Book</a></div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Widgets -->
        <div class="sidebar">
            <!-- Widget 1: Barcelona Airport Live Status -->
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
            
            <!-- Widget 2: Famous Airlines -->
            <div class="sidebar-widget">
                <h3><i class="fas fa-building"></i> Our Partner Airlines</h3>
                <div>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Etihad Airways</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Emirates</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Qatar Airways</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> British Airways</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Air France</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Turkish Airlines</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Singapore Airlines</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Delta Air Lines</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Lufthansa</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> KLM</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Iberia</span>
                    <span class="airline-tag"><i class="fas fa-plane"></i> Vueling</span>
                </div>
            </div>
            
            <!-- Widget 3: Quick Contact -->
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
        <h2 class="section-header" style="text-align:center; font-size:32px; margin-bottom:40px;">⭐ Our Services</h2>
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:25px">
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-passport"></i></div><h3>Visa Processing</h3></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-hotel"></i></div><h3>Luxury Hotels</h3></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-car"></i></div><h3>Airport Transfers</h3></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-headset"></i></div><h3>24/7 Support</h3></div>
        </div>
    </div>
</section>

<!-- About -->
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
        <h2 style="color: var(--primary-navy); margin-bottom: 20px;">✈️ Complete Booking</h2>
        <form method="POST" action="" class="booking-form">
            <input type="hidden" name="offer_id" id="booking_offer_id">
            <input type="text" name="passenger_name" placeholder="Full Name" required>
            <input type="email" name="passenger_email" placeholder="Email Address" required>
            <input type="tel" name="passenger_phone" placeholder="Phone Number" required>
            <div id="booking_details" style="background:#f8f9fa; padding:15px; border-radius:12px; margin-bottom:15px; font-size:14px;"></div>
            <button type="submit" name="book_flight">📱 Confirm & Pay on WhatsApp</button>
        </form>
        <p style="font-size:12px; color:#666; margin-top:15px; text-align:center">Payment will be confirmed via WhatsApp. Our team will contact you.</p>
    </div>
</div>

<script>
// Select2
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

function openBookingModal(offerId, price, currency, airline, origin, destination, depTime) {
    document.getElementById('booking_offer_id').value = offerId;
    document.getElementById('booking_details').innerHTML = `
        <strong>Flight Details:</strong><br>
        ✈️ ${airline}<br>
        ${origin} → ${destination}<br>
        Departure: ${depTime}<br>
        💰 Total: ${price} ${currency}
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

showSlide(0);
</script>
</body>
</html>
