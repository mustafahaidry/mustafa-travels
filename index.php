<?php
// 🔑 Duffel Live Token (Starter Plan - Free)
$apiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';
$flightResults = '';
$searchPerformed = false;

// Airport database for worldwide coverage
$airports = [
    // Pakistan
    'LHE' => 'Lahore, Pakistan', 'ISB' => 'Islamabad, Pakistan', 'KHI' => 'Karachi, Pakistan',
    // India
    'DEL' => 'Delhi, India', 'BOM' => 'Mumbai, India', 'BLR' => 'Bangalore, India', 'CCU' => 'Kolkata, India',
    // Bangladesh
    'DAC' => 'Dhaka, Bangladesh', 'CGP' => 'Chittagong, Bangladesh',
    // Latin America
    'GRU' => 'Sao Paulo, Brazil', 'MEX' => 'Mexico City, Mexico', 'EZE' => 'Buenos Aires, Argentina',
    'BOG' => 'Bogota, Colombia', 'SCL' => 'Santiago, Chile', 'LIM' => 'Lima, Peru',
    // USA
    'JFK' => 'New York, USA', 'LAX' => 'Los Angeles, USA', 'ORD' => 'Chicago, USA', 'MIA' => 'Miami, USA',
    'SFO' => 'San Francisco, USA', 'ATL' => 'Atlanta, USA', 'DFW' => 'Dallas, USA', 'BOS' => 'Boston, USA',
    // Canada
    'YYZ' => 'Toronto, Canada', 'YVR' => 'Vancouver, Canada', 'YUL' => 'Montreal, Canada',
    // Europe
    'LHR' => 'London, UK', 'CDG' => 'Paris, France', 'FRA' => 'Frankfurt, Germany', 'AMS' => 'Amsterdam, Netherlands',
    'FCO' => 'Rome, Italy', 'BCN' => 'Barcelona, Spain', 'MAD' => 'Madrid, Spain',
    // Asia
    'SIN' => 'Singapore', 'DXB' => 'Dubai, UAE', 'DOH' => 'Doha, Qatar', 'AUH' => 'Abu Dhabi, UAE',
    'NRT' => 'Tokyo, Japan', 'ICN' => 'Seoul, Korea', 'PEK' => 'Beijing, China'
];

function getAirportName($code) {
    global $airports;
    return $airports[$code] ?? $code;
}

// Handle flight search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_flights'])) {
    $searchPerformed = true;
    $origin = strtoupper(trim($_POST['origin']));
    $destination = strtoupper(trim($_POST['destination']));
    $date = $_POST['departure_date'];
    $passengers = intval($_POST['passengers']);
    $tripType = $_POST['trip_type'] ?? 'oneway';
    $returnDate = $_POST['return_date'] ?? '';
    $cabinClass = $_POST['cabin_class'] ?? 'economy';
    
    // Search outbound flights
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
            $flightResults = '<div class="success-header">✈️ Found ' . count($offers) . ' flights from ' . getAirportName($origin) . ' to ' . getAirportName($destination) . ' on ' . date('F j, Y', strtotime($date)) . '</div>';
            
            foreach (array_slice($offers, 0, 15) as $offer) {
                $slice = $offer['slices'][0];
                $segments = $slice['segments'] ?? [];
                $firstSegment = $segments[0] ?? null;
                $lastSegment = $segments[count($segments) - 1] ?? null;
                
                $depTime = $firstSegment ? date('h:i A', strtotime($firstSegment['departing_at'])) : 'N/A';
                $arrTime = $lastSegment ? date('h:i A', strtotime($lastSegment['arriving_at'])) : 'N/A';
                
                $totalDuration = 0;
                foreach ($segments as $segment) {
                    $totalDuration += $segment['duration'] ?? 0;
                }
                $durationHours = floor($totalDuration / 60);
                $durationMins = $totalDuration % 60;
                $duration = $durationHours . 'h ' . $durationMins . 'm';
                
                $stops = count($segments) - 1;
                $stopText = $stops == 0 ? 'Direct' : $stops . ' stop' . ($stops > 1 ? 's' : '');
                
                $airline = $offer['owner']['name'] ?? 'Airline';
                $airlineCode = $offer['owner']['iata_code'] ?? '';
                $price = $offer['total_amount'] ?? '0';
                $currency = $offer['total_currency'] ?? 'EUR';
                
                // Duffel Affiliate Link (you earn commission)
                $affiliateLink = 'https://www.travelpayouts.com/affiliate/' . urlencode($origin . ' to ' . $destination);
                
                $flightResults .= '
                <div class="flight-card">
                    <div class="flight-header">
                        <div class="airline-info">
                            <div class="airline-icon">✈️</div>
                            <div>
                                <div class="airline-name">' . htmlspecialchars($airline) . '</div>
                                <div class="airline-code">' . htmlspecialchars($airlineCode) . '</div>
                            </div>
                        </div>
                        <div class="flight-price">' . $price . ' ' . $currency . '</div>
                    </div>
                    <div class="flight-route">
                        <div class="flight-city">
                            <div class="city-code">' . $origin . '</div>
                            <div class="city-name">' . getAirportName($origin) . '</div>
                            <div class="flight-time">' . $depTime . '</div>
                        </div>
                        <div class="flight-duration">
                            <div class="duration-line"></div>
                            <div class="duration-text">' . $duration . '</div>
                            <div class="stops-text">' . $stopText . '</div>
                        </div>
                        <div class="flight-city">
                            <div class="city-code">' . $destination . '</div>
                            <div class="city-name">' . getAirportName($destination) . '</div>
                            <div class="flight-time">' . $arrTime . '</div>
                        </div>
                    </div>
                    <div class="flight-footer">
                        <div class="date-info">📅 ' . date('M j, Y', strtotime($date)) . '</div>
                        <div class="booking-options">
                            <a href="https://wa.me/34611473217?text=I want to book ' . $origin . ' to ' . $destination . ' for ' . $price . ' ' . $currency . ' with ' . $airline . '" class="book-whatsapp">💬 WhatsApp Booking</a>
                            <a href="tel:+34632234216" class="book-call">📞 Call to Book</a>
                        </div>
                    </div>
                </div>';
            }
        } else {
            $flightResults = '<div class="error">✈️ No flights found from ' . getAirportName($origin) . ' to ' . getAirportName($destination) . ' on ' . date('F j, Y', strtotime($date)) . '.<br>💡 Try different date or destination.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels | Book Flights Worldwide - Pakistan, India, USA, Europe</title>
    <meta name="description" content="Book flights from Pakistan, India, USA, Canada, Latin America, Europe. Best prices guaranteed. 24/7 support.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
        }
        
        /* Header */
        .main-header {
            background: linear-gradient(135deg, #1a237e 0%, #00695c 100%);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .logo h1 { color: white; font-size: 24px; font-family: 'Playfair Display', serif; }
        .logo p { color: #d4af37; font-size: 12px; letter-spacing: 2px; }
        .contact-header { display: flex; gap: 15px; }
        .contact-header a { color: white; text-decoration: none; padding: 8px 20px; border-radius: 50px; transition: 0.3s; font-size: 14px; }
        .contact-header a:hover { background: rgba(255,255,255,0.2); }
        .whatsapp-header { background: #25D366; }
        
        /* Hero */
        .hero {
            background: linear-gradient(135deg, #1a237e 0%, #00695c 100%);
            padding: 50px 0 70px;
            text-align: center;
            color: white;
        }
        .hero h1 { font-size: 42px; margin-bottom: 15px; }
        .hero p { font-size: 18px; opacity: 0.9; }
        
        /* Search Card */
        .search-card {
            background: white;
            border-radius: 24px;
            padding: 35px;
            margin-top: -50px;
            margin-bottom: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            position: relative;
            z-index: 10;
        }
        .search-title { font-size: 24px; color: #1a237e; margin-bottom: 25px; border-left: 4px solid #d4af37; padding-left: 15px; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 14px; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: 0.3s;
        }
        .form-group input:focus, .form-group select:focus { border-color: #d4af37; outline: none; }
        .search-btn {
            background: linear-gradient(135deg, #00695c, #1a237e);
            color: white;
            padding: 16px 30px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
            margin-top: 10px;
        }
        .search-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        
        /* Trip Type Toggle */
        .trip-toggle { display: flex; gap: 20px; margin-bottom: 25px; }
        .trip-option { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        .trip-option input { width: 18px; height: 18px; cursor: pointer; }
        .trip-option label { cursor: pointer; margin: 0; }
        
        /* Results */
        .results-card {
            background: white;
            border-radius: 24px;
            padding: 35px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .results-title { font-size: 24px; color: #1a237e; margin-bottom: 25px; border-left: 4px solid #d4af37; padding-left: 15px; }
        .success-header { background: #e8f5e9; padding: 15px; border-radius: 12px; margin-bottom: 20px; color: #2e7d32; font-weight: 500; }
        .error { background: #ffebee; padding: 15px; border-radius: 12px; margin-bottom: 20px; color: #c62828; }
        
        /* Flight Card */
        .flight-card {
            background: #f8f9fa;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            transition: 0.3s;
            border: 1px solid #eee;
        }
        .flight-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-left: 4px solid #d4af37; }
        .flight-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 20px; }
        .airline-info { display: flex; align-items: center; gap: 15px; }
        .airline-icon { width: 50px; height: 50px; background: #1a237e; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; }
        .airline-name { font-weight: 700; font-size: 18px; color: #1a237e; }
        .airline-code { font-size: 12px; color: #666; }
        .flight-price { font-size: 28px; font-weight: 800; color: #00695c; }
        .flight-route { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; margin: 20px 0; }
        .flight-city { text-align: center; min-width: 120px; }
        .city-code { font-weight: 800; font-size: 24px; color: #333; }
        .city-name { font-size: 12px; color: #666; margin-top: 5px; }
        .flight-time { font-weight: 600; font-size: 18px; margin-top: 10px; }
        .flight-duration { text-align: center; flex: 1; min-width: 150px; }
        .duration-line { height: 2px; background: #d4af37; width: 100%; position: relative; }
        .duration-text { font-size: 12px; color: #666; margin-top: 5px; }
        .stops-text { font-size: 11px; color: #999; }
        .flight-footer { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; }
        .date-info { color: #666; font-size: 14px; }
        .booking-options { display: flex; gap: 12px; }
        .book-whatsapp {
            background: #25D366;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .book-call {
            background: #00695c;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }
        .book-whatsapp:hover, .book-call:hover { transform: scale(1.05); }
        
        /* Popular Routes Grid */
        .routes-section { padding: 60px 0; background: #f8f9fa; }
        .section-title { text-align: center; font-size: 32px; color: #1a237e; margin-bottom: 40px; }
        .routes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        .route-item {
            background: white;
            padding: 20px;
            border-radius: 16px;
            text-align: center;
            transition: 0.3s;
            cursor: pointer;
            border: 1px solid #eee;
        }
        .route-item:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-color: #d4af37; }
        .route-pair { font-weight: 700; font-size: 18px; color: #1a237e; }
        .route-price { color: #00695c; font-size: 20px; font-weight: 700; margin: 10px 0; }
        .route-stops { font-size: 12px; color: #666; }
        
        /* Info Banner */
        .info-banner {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            text-align: center;
            border-left: 4px solid #d4af37;
        }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a237e 0%, #0d1440 100%);
            color: white;
            padding: 50px 0 30px;
        }
        .footer-content { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; margin-bottom: 30px; }
        .footer a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .footer a:hover { color: #d4af37; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); }
        
        @media (max-width: 768px) {
            .form-row { grid-template-columns: 1fr; }
            .flight-route { flex-direction: column; }
            .flight-duration { width: 100%; }
            .footer-content { grid-template-columns: 1fr; }
            .hero h1 { font-size: 28px; }
            .search-card { padding: 20px; }
            .booking-options { flex-direction: column; width: 100%; }
            .book-whatsapp, .book-call { text-align: center; justify-content: center; }
        }
    </style>
</head>
<body>

<header class="main-header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <h1>✈️ Mustafa Travels & Tours</h1>
                <p>Premium Travel Experiences Since 2024</p>
            </div>
            <div class="contact-header">
                <a href="tel:+34632234216"><i class="fas fa-phone"></i> +34-632234216</a>
                <a href="https://wa.me/34611473217" class="whatsapp-header"><i class="fab fa-whatsapp"></i> WhatsApp</a>
            </div>
        </div>
    </div>
</header>

<section class="hero">
    <div class="container">
        <h1>Book Flights Worldwide</h1>
        <p>Pakistan | India | Bangladesh | USA | Canada | Latin America | Europe | Singapore</p>
    </div>
</section>

<div class="container">
    <div class="info-banner">
        <i class="fas fa-headset" style="color: #00695c; font-size: 24px; margin-right: 10px;"></i>
        <strong>24/7 Customer Support</strong> | Call or WhatsApp us for instant booking confirmation | Best Price Guarantee
    </div>
    
    <div class="search-card">
        <h2 class="search-title">🔍 Search & Book Flights</h2>
        <form method="POST" action="">
            <div class="trip-toggle">
                <label class="trip-option">
                    <input type="radio" name="trip_type" value="oneway" <?php echo (!isset($_POST['trip_type']) || $_POST['trip_type'] == 'oneway') ? 'checked' : ''; ?> onchange="toggleReturnDate()"> 
                    <span>✈️ One Way</span>
                </label>
                <label class="trip-option">
                    <input type="radio" name="trip_type" value="return" <?php echo (isset($_POST['trip_type']) && $_POST['trip_type'] == 'return') ? 'checked' : ''; ?> onchange="toggleReturnDate()"> 
                    <span>🔄 Return</span>
                </label>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>✈️ From (Origin)</label>
                    <input type="text" name="origin" placeholder="LHE, BCN, JFK, DEL" value="<?php echo isset($_POST['origin']) ? $_POST['origin'] : 'LHE'; ?>" required>
                    <small>Airport code: LHE, ISB, KHI, DEL, BOM, JFK, BCN, etc.</small>
                </div>
                <div class="form-group">
                    <label>📍 To (Destination)</label>
                    <input type="text" name="destination" placeholder="JFK, BCN, LHR, DXB" value="<?php echo isset($_POST['destination']) ? $_POST['destination'] : 'JFK'; ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>📅 Departure Date</label>
                    <input type="date" name="departure_date" value="<?php echo isset($_POST['departure_date']) ? $_POST['departure_date'] : date('Y-m-d', strtotime('+30 days')); ?>" required>
                </div>
                <div class="form-group" id="returnDateGroup" style="display: <?php echo (isset($_POST['trip_type']) && $_POST['trip_type'] == 'return') ? 'block' : 'none'; ?>">
                    <label>🔄 Return Date</label>
                    <input type="date" name="return_date" value="<?php echo isset($_POST['return_date']) ? $_POST['return_date'] : date('Y-m-d', strtotime('+37 days')); ?>">
                </div>
                <div class="form-group">
                    <label>👥 Passengers</label>
                    <select name="passengers">
                        <option value="1" <?php echo (isset($_POST['passengers']) && $_POST['passengers'] == 1) ? 'selected' : ''; ?>>1 Adult</option>
                        <option value="2" <?php echo (isset($_POST['passengers']) && $_POST['passengers'] == 2) ? 'selected' : ''; ?>>2 Adults</option>
                        <option value="3" <?php echo (isset($_POST['passengers']) && $_POST['passengers'] == 3) ? 'selected' : ''; ?>>3 Adults</option>
                        <option value="4" <?php echo (isset($_POST['passengers']) && $_POST['passengers'] == 4) ? 'selected' : ''; ?>>4 Adults</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>🛋️ Cabin Class</label>
                    <select name="cabin_class">
                        <option value="economy" <?php echo (isset($_POST['cabin_class']) && $_POST['cabin_class'] == 'economy') ? 'selected' : ''; ?>>Economy</option>
                        <option value="premium_economy" <?php echo (isset($_POST['cabin_class']) && $_POST['cabin_class'] == 'premium_economy') ? 'selected' : ''; ?>>Premium Economy</option>
                        <option value="business" <?php echo (isset($_POST['cabin_class']) && $_POST['cabin_class'] == 'business') ? 'selected' : ''; ?>>Business</option>
                        <option value="first" <?php echo (isset($_POST['cabin_class']) && $_POST['cabin_class'] == 'first') ? 'selected' : ''; ?>>First Class</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="search_flights" class="search-btn">🔍 Search Flights</button>
        </form>
    </div>

    <?php if ($searchPerformed): ?>
    <div class="results-card">
        <h2 class="results-title">✈️ Flight Results</h2>
        <?php echo $flightResults; ?>
        <div class="info-banner" style="margin-top: 20px;">
            <i class="fas fa-info-circle"></i> Need help booking? Call or WhatsApp us for instant confirmation and best deals!
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Popular Routes -->
<section class="routes-section">
    <div class="container">
        <h2 class="section-title">🌍 Popular Routes</h2>
        <div class="routes-grid">
            <div class="route-item" onclick="fillSearch('LHE', 'JFK')"><div class="route-pair">Lahore → New York</div><div class="route-price">From €799</div><div class="route-stops">1 stop | Multiple airlines</div></div>
            <div class="route-item" onclick="fillSearch('ISB', 'LHR')"><div class="route-pair">Islamabad → London</div><div class="route-price">From €585</div><div class="route-stops">1 stop | Etihad, Qatar</div></div>
            <div class="route-item" onclick="fillSearch('KHI', 'DXB')"><div class="route-pair">Karachi → Dubai</div><div class="route-price">From €299</div><div class="route-stops">Direct | Multiple airlines</div></div>
            <div class="route-item" onclick="fillSearch('DEL', 'JFK')"><div class="route-pair">Delhi → New York</div><div class="route-price">From €699</div><div class="route-stops">1 stop | Multiple airlines</div></div>
            <div class="route-item" onclick="fillSearch('BCN', 'LHE')"><div class="route-pair">Barcelona → Lahore</div><div class="route-price">€580</div><div class="route-stops">1 stop | 40kg baggage</div></div>
            <div class="route-item" onclick="fillSearch('GRU', 'MIA')"><div class="route-pair">Sao Paulo → Miami</div><div class="route-price">From €499</div><div class="route-stops">Direct | LATAM</div></div>
            <div class="route-item" onclick="fillSearch('SIN', 'LHR')"><div class="route-pair">Singapore → London</div><div class="route-price">From €599</div><div class="route-stops">Direct | Singapore Airlines</div></div>
            <div class="route-item" onclick="fillSearch('DAC', 'DXB')"><div class="route-pair">Dhaka → Dubai</div><div class="route-price">From €349</div><div class="route-stops">Direct | Emirates</div></div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div>
                <h3 style="margin-bottom: 15px;">Mustafa Travels</h3>
                <p>Rambla Badal 141 Local 1 Bajo</p>
                <p>Barcelona 08028, Spain</p>
                <p>📞 +34-632234216</p>
                <p>💬 +34-611473217</p>
            </div>
            <div>
                <h3 style="margin-bottom: 15px;">Quick Links</h3>
                <p><a href="#">Home</a></p>
                <p><a href="#">Umrah Packages</a></p>
                <p><a href="#">Hajj 2026</a></p>
                <p><a href="#">Flight Deals</a></p>
            </div>
            <div>
                <h3 style="margin-bottom: 15px;">Destinations</h3>
                <p>Pakistan | India | Bangladesh</p>
                <p>USA | Canada | Latin America</p>
                <p>Europe | Singapore | UAE</p>
            </div>
            <div>
                <h3 style="margin-bottom: 15px;">Hours</h3>
                <p>Mon-Thu: 10:30 - 20:30</p>
                <p>Fri: 10:30-13:00 & 15:00-20:30</p>
                <p>Sat: 10:30 - 19:30</p>
                <p>Sun: Closed</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Best Price Guarantee | 24/7 Support</p>
        </div>
    </div>
</footer>

<script>
function toggleReturnDate() {
    var returnGroup = document.getElementById('returnDateGroup');
    var tripType = document.querySelector('input[name="trip_type"]:checked').value;
    if (tripType === 'return') {
        returnGroup.style.display = 'block';
    } else {
        returnGroup.style.display = 'none';
    }
}

function fillSearch(origin, destination) {
    document.querySelector('input[name="origin"]').value = origin;
    document.querySelector('input[name="destination"]').value = destination;
    document.querySelector('form').submit();
}
</script>
</body>
</html>
