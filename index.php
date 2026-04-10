<?php
// 🔑 Duffel Live Token
$apiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';
$flightResults = '';
$searchPerformed = false;

// SEO Meta
$pageTitle = "Mustafa Travels & Tours | Best Umrah, Hajj & Flight Deals from Barcelona";
$pageDescription = "Premium Umrah packages 2026, Hajj bookings, cheap flights from Barcelona to Pakistan, London, Dubai, USA.";
$pageKeywords = "Umrah packages, Hajj 2026, Barcelona travel agency, cheap flights, return flights, multi-city flights";

// Airport database with codes and names
$airportsList = [
    ['code' => 'BCN', 'name' => 'Barcelona-El Prat, Spain', 'country' => 'Spain'],
    ['code' => 'MAD', 'name' => 'Madrid-Barajas, Spain', 'country' => 'Spain'],
    ['code' => 'AGP', 'name' => 'Malaga-Costa del Sol, Spain', 'country' => 'Spain'],
    ['code' => 'SVQ', 'name' => 'Seville-San Pablo, Spain', 'country' => 'Spain'],
    ['code' => 'PMI', 'name' => 'Palma de Mallorca, Spain', 'country' => 'Spain'],
    ['code' => 'LHE', 'name' => 'Allama Iqbal International, Lahore', 'country' => 'Pakistan'],
    ['code' => 'ISB', 'name' => 'Islamabad International, Pakistan', 'country' => 'Pakistan'],
    ['code' => 'KHI', 'name' => 'Jinnah International, Karachi', 'country' => 'Pakistan'],
    ['code' => 'DEL', 'name' => 'Indira Gandhi International, Delhi', 'country' => 'India'],
    ['code' => 'BOM', 'name' => 'Chhatrapati Shivaji, Mumbai', 'country' => 'India'],
    ['code' => 'DAC', 'name' => 'Hazrat Shahjalal, Dhaka', 'country' => 'Bangladesh'],
    ['code' => 'JFK', 'name' => 'John F Kennedy, New York', 'country' => 'USA'],
    ['code' => 'LAX', 'name' => 'Los Angeles International, USA', 'country' => 'USA'],
    ['code' => 'YYZ', 'name' => 'Toronto Pearson, Canada', 'country' => 'Canada'],
    ['code' => 'LHR', 'name' => 'London Heathrow, UK', 'country' => 'United Kingdom'],
    ['code' => 'CDG', 'name' => 'Charles de Gaulle, Paris', 'country' => 'France'],
    ['code' => 'FRA', 'name' => 'Frankfurt am Main, Germany', 'country' => 'Germany'],
    ['code' => 'DXB', 'name' => 'Dubai International, UAE', 'country' => 'UAE'],
    ['code' => 'SIN', 'name' => 'Changi International, Singapore', 'country' => 'Singapore'],
    ['code' => 'IST', 'name' => 'Istanbul Airport, Turkey', 'country' => 'Turkey'],
    ['code' => 'DOH', 'name' => 'Hamad International, Doha', 'country' => 'Qatar'],
    ['code' => 'AUH', 'name' => 'Abu Dhabi International, UAE', 'country' => 'UAE'],
    ['code' => 'FCO', 'name' => 'Leonardo da Vinci, Rome', 'country' => 'Italy'],
    ['code' => 'AMS', 'name' => 'Amsterdam Schiphol, Netherlands', 'country' => 'Netherlands'],
];

function getAirportName($code) {
    global $airportsList;
    foreach ($airportsList as $airport) {
        if ($airport['code'] == $code) {
            return $airport['name'];
        }
    }
    return $code;
}

// Handle flight search (same as before)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_flights'])) {
    $searchPerformed = true;
    $tripType = $_POST['trip_type'] ?? 'oneway';
    $passengers = intval($_POST['passengers']);
    $cabinClass = $_POST['cabin_class'] ?? 'economy';
    
    $slices = [];
    
    if ($tripType == 'oneway') {
        $origin = strtoupper(trim($_POST['origin']));
        $destination = strtoupper(trim($_POST['destination']));
        $date = $_POST['departure_date'];
        $slices[] = ['origin' => $origin, 'destination' => $destination, 'departure_date' => $date];
    }
    
    if ($tripType == 'return') {
        $origin = strtoupper(trim($_POST['origin_return']));
        $destination = strtoupper(trim($_POST['destination_return']));
        $date = $_POST['departure_date_return'];
        $returnDate = $_POST['return_date'];
        $slices[] = ['origin' => $origin, 'destination' => $destination, 'departure_date' => $date];
        $slices[] = ['origin' => $destination, 'destination' => $origin, 'departure_date' => $returnDate];
    }
    
    if ($tripType == 'multi') {
        for ($i = 1; $i <= 3; $i++) {
            $origin = strtoupper(trim($_POST["origin_$i"] ?? ''));
            $destination = strtoupper(trim($_POST["destination_$i"] ?? ''));
            $date = $_POST["date_$i"] ?? '';
            if ($origin && $destination && $date) {
                $slices[] = ['origin' => $origin, 'destination' => $destination, 'departure_date' => $date];
            }
        }
    }
    
    if (count($slices) > 0) {
        $searchData = [
            'data' => [
                'slices' => $slices,
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
                $tripText = $tripType == 'oneway' ? 'One Way' : ($tripType == 'return' ? 'Return' : 'Multi-City');
                $flightResults = '<div class="success-header">✈️ Found ' . count($offers) . ' ' . $tripText . ' flights</div>';
                
                foreach (array_slice($offers, 0, 15) as $offer) {
                    $totalPrice = $offer['total_amount'] ?? '0';
                    $currency = $offer['total_currency'] ?? 'EUR';
                    $airline = $offer['owner']['name'] ?? 'Airline';
                    
                    $routeHtml = '';
                    foreach ($offer['slices'] as $sliceIndex => $slice) {
                        $segments = $slice['segments'] ?? [];
                        $firstSegment = $segments[0] ?? null;
                        $lastSegment = $segments[count($segments) - 1] ?? null;
                        $origin = $firstSegment['origin']['iata_code'] ?? 'N/A';
                        $destination = $lastSegment['destination']['iata_code'] ?? 'N/A';
                        $depTime = $firstSegment ? date('h:i A', strtotime($firstSegment['departing_at'])) : 'N/A';
                        $arrTime = $lastSegment ? date('h:i A', strtotime($lastSegment['arriving_at'])) : 'N/A';
                        
                        $totalDuration = 0;
                        foreach ($segments as $segment) {
                            $totalDuration += intval($segment['duration'] ?? 0);
                        }
                        $durationHours = floor($totalDuration / 60);
                        $durationMins = $totalDuration % 60;
                        $duration = $durationHours . 'h ' . $durationMins . 'm';
                        $stops = count($segments) - 1;
                        $stopText = $stops == 0 ? 'Direct' : $stops . ' stop' . ($stops > 1 ? 's' : '');
                        
                        $routeHtml .= '
                        <div class="route-segment">
                            <div class="segment-header">✈️ Segment ' . ($sliceIndex + 1) . '</div>
                            <div class="segment-route">
                                <div><div class="city-code">' . $origin . '</div><div class="city-name">' . getAirportName($origin) . '</div><div class="flight-time">' . $depTime . '</div></div>
                                <div class="flight-duration"><div class="duration-line"></div><div class="duration-text">' . $duration . '</div><div class="stops-text">' . $stopText . '</div></div>
                                <div><div class="city-code">' . $destination . '</div><div class="city-name">' . getAirportName($destination) . '</div><div class="flight-time">' . $arrTime . '</div></div>
                            </div>
                        </div>';
                    }
                    
                    $flightResults .= '
                    <div class="flight-card">
                        <div class="flight-header">
                            <div class="airline-info">
                                <div class="airline-icon">✈️</div>
                                <div><div class="airline-name">' . htmlspecialchars($airline) . '</div></div>
                            </div>
                            <div class="flight-price">' . $totalPrice . ' ' . $currency . '</div>
                        </div>
                        ' . $routeHtml . '
                        <div class="flight-footer">
                            <a href="https://wa.me/34611473217?text=I want to book this flight for ' . $totalPrice . ' ' . $currency . ' with ' . $airline . '" class="book-btn">📱 Book on WhatsApp</a>
                        </div>
                    </div>';
                }
            } else {
                $flightResults = '<div class="error">✈️ No flights found. Try different dates or destinations.</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="<?php echo $pageKeywords; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Select2 for searchable dropdown -->
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
            --text-dark: #2c3e50;
            --text-light: #666;
            --white: #ffffff;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            background-color: var(--light-bg);
            line-height: 1.7;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--primary-navy);
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Header Styles (same as before) */
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
            color: var(--white);
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-wrap: wrap;
            gap: 15px;
        }
        .contact-info-elegant { display: flex; gap: 30px; align-items: center; flex-wrap: wrap; }
        .contact-info-elegant span { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 300; }
        .contact-info-elegant i { color: var(--primary-gold); }
        .social-elegant a { color: var(--white); margin-left: 15px; font-size: 16px; transition: var(--transition); }
        .social-elegant a:hover { color: var(--primary-gold); transform: translateY(-2px); }
        .main-header-elegant { padding: 20px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .logo-elegant { display: flex; align-items: center; text-decoration: none; gap: 20px; }
        .logo-icon-elegant { background: var(--primary-gold); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; color: var(--primary-navy); box-shadow: var(--shadow); }
        .logo-main-elegant { font-family: 'Crimson Text', serif; font-size: 28px; font-weight: 700; color: var(--white); }
        .logo-sub-elegant { font-size: 12px; color: var(--light-gold); font-weight: 300; letter-spacing: 2px; }
        .nav-elegant { display: flex; gap: 30px; align-items: center; flex-wrap: wrap; }
        .nav-elegant a { color: var(--white); text-decoration: none; font-weight: 500; font-size: 15px; position: relative; padding: 8px 0; transition: var(--transition); }
        .nav-elegant a:after { content: ''; position: absolute; bottom: 0; left: 0; width: 0; height: 2px; background: var(--primary-gold); transition: var(--transition); }
        .nav-elegant a:hover:after, .nav-elegant a.active:after { width: 100%; }
        .nav-elegant a:hover, .nav-elegant a.active { color: var(--primary-gold); }
        .whatsapp-btn-elegant {
            background: linear-gradient(135deg, #25D366 0%, #1da851 100%);
            color: var(--white);
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            font-size: 14px;
        }
        .whatsapp-btn-elegant:hover { transform: translateY(-3px); }
        
        /* Flying Marquee */
        .flying-marquee-container {
            background: linear-gradient(135deg, var(--primary-navy) 0%, #0d1440 100%);
            padding: 10px 0;
            position: relative;
            border-bottom: 2px solid var(--primary-gold);
        }
        .marquee-track { position: relative; height: 35px; display: flex; align-items: center; overflow: hidden; }
        .marquee-content { display: flex; animation: marqueeScroll 30s linear infinite; white-space: nowrap; }
        .marquee-text { color: var(--white); font-size: 15px; font-weight: 500; padding: 0 25px; display: flex; align-items: center; }
        .marquee-text:before { content: '•'; color: var(--primary-gold); margin-right: 15px; font-size: 20px; }
        .flying-plane {
            position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);
            background: var(--primary-gold); width: 50px; height: 50px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            animation: planeFloat 3s ease-in-out infinite;
        }
        .flying-plane i { color: var(--primary-navy); font-size: 20px; transform: rotate(45deg); }
        
        /* Hero Slider */
        .luxury-slider { height: 500px; position: relative; overflow: hidden; border-radius: 0 0 var(--radius) var(--radius); }
        .luxury-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; opacity: 0; transition: opacity 1.2s ease-in-out; }
        .luxury-slide.active { opacity: 1; }
        .slide-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(26,35,126,0.85), rgba(0,105,92,0.85)); display: flex; align-items: center; }
        .slide-content-luxury { max-width: 800px; padding-left: 80px; color: var(--white); }
        .slide-content-luxury h2 { font-size: 42px; color: var(--white); margin-bottom: 20px; }
        .slide-content-luxury p { font-size: 18px; margin-bottom: 35px; }
        .luxury-btn { display: inline-flex; align-items: center; gap: 12px; background: var(--primary-gold); color: var(--primary-navy); padding: 14px 28px; border-radius: 50px; font-weight: 600; text-decoration: none; transition: var(--transition); }
        .luxury-btn:hover { transform: translateY(-3px); background: var(--light-gold); }
        .slider-controls { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; }
        .slider-dot-luxury { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; transition: var(--transition); }
        .slider-dot-luxury.active { background: var(--primary-gold); transform: scale(1.2); }
        
        /* Search Card */
        .search-luxury {
            background: var(--white);
            padding: 35px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-top: -50px;
            position: relative;
            z-index: 10;
            margin-bottom: 40px;
            border-top: 4px solid var(--primary-gold);
        }
        .section-header { text-align: center; margin-bottom: 50px; }
        .section-header h2 { font-size: 42px; margin-bottom: 20px; position: relative; display: inline-block; }
        .section-header h2:after { content: ''; position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); width: 80px; height: 4px; background: var(--primary-gold); }
        .section-header p { color: var(--text-light); max-width: 700px; margin: 20px auto 0; font-size: 18px; }
        
        /* Trip Type Toggle */
        .trip-toggle { display: flex; gap: 20px; margin-bottom: 25px; justify-content: center; flex-wrap: wrap; }
        .trip-option { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 20px; border-radius: 50px; transition: var(--transition); }
        .trip-option:hover { background: var(--light-gold); }
        .trip-option input { width: 18px; height: 18px; cursor: pointer; }
        .trip-option label { cursor: pointer; font-weight: 500; margin: 0; }
        
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: var(--primary-navy); margin-bottom: 8px; font-size: 14px; }
        .form-group select { width: 100%; padding: 14px 16px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 16px; transition: var(--transition); }
        .form-group select:focus { border-color: var(--primary-gold); outline: none; }
        
        /* Select2 Custom Styling */
        .select2-container .select2-selection--single {
            height: 54px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 8px 12px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
            font-size: 16px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px;
        }
        .select2-dropdown {
            border-radius: 12px;
            border-color: #e0e0e0;
        }
        .select2-search__field {
            border-radius: 8px !important;
            padding: 8px !important;
        }
        
        .multi-city-row { background: #f8f9fa; padding: 20px; border-radius: 16px; margin-bottom: 20px; }
        .search-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 16px 30px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; transition: var(--transition); }
        .search-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        
        /* Flight Results */
        .flight-card { background: #f8f9fa; border-radius: 16px; padding: 20px; margin-bottom: 20px; transition: var(--transition); border: 1px solid #eee; }
        .flight-card:hover { transform: translateX(5px); border-left: 4px solid var(--primary-gold); }
        .flight-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 15px; }
        .airline-info { display: flex; align-items: center; gap: 15px; }
        .airline-icon { width: 45px; height: 45px; background: var(--primary-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
        .flight-price { font-size: 24px; font-weight: 800; color: var(--primary-teal); }
        .route-segment { margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #ddd; }
        .route-segment:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .segment-header { font-size: 12px; color: var(--primary-gold); margin-bottom: 10px; font-weight: 600; }
        .segment-route { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .city-code { font-weight: 800; font-size: 18px; color: var(--primary-navy); }
        .city-name { font-size: 11px; color: #666; }
        .flight-time { font-weight: 600; font-size: 14px; margin-top: 5px; }
        .flight-duration { text-align: center; flex: 1; }
        .duration-line { height: 2px; background: var(--primary-gold); width: 100%; }
        .book-btn { background: #25D366; color: white; padding: 10px 20px; border-radius: 50px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; transition: var(--transition); }
        .book-btn:hover { transform: scale(1.05); }
        .error { background: #ffebee; padding: 15px; border-radius: 12px; color: #c62828; }
        .success-header { background: #e8f5e9; padding: 12px; border-radius: 12px; margin-bottom: 20px; color: #2e7d32; }
        
        /* Other sections */
        .packages-luxury { padding: 80px 0; background: var(--white); }
        .packages-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; }
        .package-card { background: var(--white); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); transition: var(--transition); }
        .package-card:hover { transform: translateY(-10px); }
        .package-image { height: 200px; background-size: cover; background-position: center; position: relative; }
        .package-badge { position: absolute; top: 15px; right: 15px; background: var(--primary-gold); color: var(--primary-navy); padding: 6px 18px; border-radius: 50px; font-weight: 700; }
        .package-content { padding: 25px; }
        .package-content h3 { font-size: 22px; margin-bottom: 12px; }
        .package-features { list-style: none; margin-bottom: 25px; }
        .package-features li { padding: 10px 0; border-bottom: 1px solid #f0f0f0; display: flex; align-items: center; gap: 10px; font-size: 14px; }
        .package-features i { color: var(--primary-gold); }
        .package-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 14px; border-radius: 8px; border: none; cursor: pointer; width: 100%; font-weight: 600; transition: var(--transition); }
        .package-btn:hover { transform: translateY(-2px); }
        
        .hajj-section { padding: 80px 0; background: linear-gradient(135deg, var(--light-bg) 0%, #e8f4f3 100%); }
        .hajj-waiting { text-align: center; padding: 60px; background: #f8f9fa; border-radius: var(--radius); border: 2px dashed var(--primary-gold); }
        
        .deals-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .deal-card { background: var(--white); border-radius: var(--radius); padding: 20px; border-left: 4px solid var(--primary-gold); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; box-shadow: var(--shadow); transition: var(--transition); }
        .deal-card:hover { transform: translateY(-5px); }
        .deal-price { font-size: 24px; font-weight: 700; color: var(--primary-teal); }
        
        .services-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; margin-top: 30px; }
        .service-card { background: var(--white); padding: 30px; border-radius: var(--radius); text-align: center; transition: var(--transition); box-shadow: var(--shadow); }
        .service-card:hover { transform: translateY(-8px); }
        .service-icon { width: 70px; height: 70px; background: var(--light-gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px; color: var(--primary-teal); }
        
        .about-content { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
        .about-image { height: 350px; background-size: cover; background-position: center; border-radius: var(--radius); }
        
        .footer-elegant { background: linear-gradient(135deg, var(--primary-navy) 0%, #0d1440 100%); color: var(--white); padding: 60px 0 30px; margin-top: 40px; }
        .footer-content { display: grid; grid-template-columns: repeat(4, 1fr); gap: 40px; margin-bottom: 40px; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); }
        
        @keyframes marqueeScroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        @keyframes planeFloat { 0%,100% { transform: translate(-50%, -50%) translateY(0); } 50% { transform: translate(-50%, -50%) translateY(-8px); } }
        
        @media (max-width: 768px) {
            .packages-grid, .services-grid, .footer-content, .about-content { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .slide-content-luxury { padding: 0 25px; text-align: center; }
            .section-header h2 { font-size: 32px; }
            .nav-elegant { display: none; flex-direction: column; background: rgba(26,35,126,0.95); padding: 20px; border-radius: var(--radius); }
            .nav-elegant.active { display: flex; }
            .mobile-menu-toggle { display: block; background: var(--primary-gold); padding: 12px 20px; border-radius: 50px; text-align: center; width: fit-content; margin: 10px auto; cursor: pointer; }
            .trip-toggle { gap: 10px; }
            .trip-option { padding: 5px 12px; }
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
                <a href="#home" class="active">Home</a>
                <a href="#umrah">Umrah</a>
                <a href="#hajj">Hajj 2026</a>
                <a href="#flights">Flight Deals</a>
                <a href="#services">Services</a>
                <a href="#about">About</a>
                <a href="https://wa.me/34611473217" class="whatsapp-btn-elegant" target="_blank"><i class="fab fa-whatsapp"></i> Book Now</a>
            </nav>
        </div>
    </div>
</header>

<div class="flying-marquee-container">
    <div class="marquee-track">
        <div class="marquee-content">
            <span class="marquee-text">✈️ SPECIAL FLIGHT DEALS ✈️ BARCELONA TO LAHORE: €580 ✈️ BARCELONA TO ISLAMABAD: €585 ✈️ BARCELONA TO LONDON: €79 ✈️ 40KG BAGGAGE INCLUDED ✈️ CALL +34-632234216 ✈️</span>
            <span class="marquee-text">✈️ SPECIAL FLIGHT DEALS ✈️ BARCELONA TO LAHORE: €580 ✈️ BARCELONA TO ISLAMABAD: €585 ✈️ BARCELONA TO LONDON: €79 ✈️ 40KG BAGGAGE INCLUDED ✈️ CALL +34-632234216 ✈️</span>
        </div>
        <div class="flying-plane"><i class="fas fa-plane"></i></div>
    </div>
</div>

<section class="luxury-slider" id="home">
    <div class="luxury-slide active" style="background-image: url('https://images.pexels.com/photos/33270402/pexels-photo-33270402.jpeg');">
        <div class="slide-overlay"><div class="slide-content-luxury"><h2>Premium Umrah Experiences 2026</h2><p>Luxury accommodations near Haram with personalized guidance.</p><a href="#umrah" class="luxury-btn">Explore Packages <i class="fas fa-arrow-right"></i></a></div></div>
    </div>
    <div class="luxury-slide" style="background-image: url('https://images.pexels.com/photos/29102586/pexels-photo-29102586.jpeg');">
        <div class="slide-overlay"><div class="slide-content-luxury"><h2>Hajj 2026 - Coming Soon</h2><p>Register your interest for Hajj 2026 packages.</p><a href="#hajj" class="luxury-btn">Learn More <i class="fas fa-arrow-right"></i></a></div></div>
    </div>
    <div class="luxury-slide" style="background-image: url('https://images.unsplash.com/photo-1591824438703-50d4c4e5d45a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');">
        <div class="slide-overlay"><div class="slide-content-luxury"><h2>One Way, Return & Multi-City</h2><p>Book flights your way. Flexible options for all your travel needs.</p><a href="#search" class="luxury-btn">Search Flights <i class="fas fa-arrow-right"></i></a></div></div>
    </div>
    <div class="slider-controls"><div class="slider-dot-luxury active"></div><div class="slider-dot-luxury"></div><div class="slider-dot-luxury"></div></div>
</section>

<div class="container" id="search">
    <div class="search-luxury">
        <h2 class="section-header" style="margin-bottom: 20px;">✈️ Search Flights</h2>
        
        <form method="POST" action="" id="flightSearchForm">
            <div class="trip-toggle">
                <label class="trip-option"><input type="radio" name="trip_type" value="oneway" checked onchange="toggleTripType()"> <label>✈️ One Way</label></label>
                <label class="trip-option"><input type="radio" name="trip_type" value="return" onchange="toggleTripType()"> <label>🔄 Return</label></label>
                <label class="trip-option"><input type="radio" name="trip_type" value="multi" onchange="toggleTripType()"> <label>🌍 Multi-City</label></label>
            </div>
            
            <!-- One Way Fields with Searchable Dropdown -->
            <div id="onewayFields">
                <div class="form-row">
                    <div class="form-group"><label>✈️ From</label><select name="origin" id="origin_oneway" class="airport-select" style="width:100%"><?php foreach($airportsList as $airport){ echo '<option value="'.$airport['code'].'">'.$airport['code'].' - '.$airport['name'].'</option>'; } ?></select></div>
                    <div class="form-group"><label>📍 To</label><select name="destination" id="destination_oneway" class="airport-select" style="width:100%"><?php foreach($airportsList as $airport){ echo '<option value="'.$airport['code'].'">'.$airport['code'].' - '.$airport['name'].'</option>'; } ?></select></div>
                    <div class="form-group"><label>📅 Departure Date</label><input type="date" name="departure_date" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"></div>
                </div>
            </div>
            
            <!-- Return Fields -->
            <div id="returnFields" style="display: none;">
                <div class="form-row">
                    <div class="form-group"><label>✈️ From</label><select name="origin_return" id="origin_return" class="airport-select" style="width:100%"><?php foreach($airportsList as $airport){ echo '<option value="'.$airport['code'].'">'.$airport['code'].' - '.$airport['name'].'</option>'; } ?></select></div>
                    <div class="form-group"><label>📍 To</label><select name="destination_return" id="destination_return" class="airport-select" style="width:100%"><?php foreach($airportsList as $airport){ echo '<option value="'.$airport['code'].'">'.$airport['code'].' - '.$airport['name'].'</option>'; } ?></select></div>
                    <div class="form-group"><label>📅 Departure Date</label><input type="date" name="departure_date_return" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"></div>
                    <div class="form-group"><label>🔄 Return Date</label><input type="date" name="return_date" value="<?php echo date('Y-m-d', strtotime('+37 days')); ?>"></div>
                </div>
            </div>
            
            <!-- Multi-City Fields -->
            <div id="multiFields" style="display: none;">
                <?php for($i=1; $i<=3; $i++){ ?>
                <div class="multi-city-row" id="segment<?php echo $i; ?>">
                    <h4 style="margin-bottom: 15px; color: var(--primary-navy);">Segment <?php echo $i; ?></h4>
                    <div class="form-row">
                        <div class="form-group"><label>From</label><select name="origin_<?php echo $i; ?>" id="origin_<?php echo $i; ?>" class="airport-select" style="width:100%"><?php foreach($airportsList as $airport){ echo '<option value="'.$airport['code'].'">'.$airport['code'].' - '.$airport['name'].'</option>'; } ?></select></div>
                        <div class="form-group"><label>To</label><select name="destination_<?php echo $i; ?>" id="destination_<?php echo $i; ?>" class="airport-select" style="width:100%"><?php foreach($airportsList as $airport){ echo '<option value="'.$airport['code'].'">'.$airport['code'].' - '.$airport['name'].'</option>'; } ?></select></div>
                        <div class="form-group"><label>Date</label><input type="date" name="date_<?php echo $i; ?>" value="<?php echo date('Y-m-d', strtotime('+'.(30+$i*5).' days')); ?>"></div>
                    </div>
                </div>
                <?php } ?>
            </div>
            
            <div class="form-row">
                <div class="form-group"><label>👥 Passengers</label><select name="passengers"><option value="1">1 Adult</option><option value="2">2 Adults</option><option value="3">3 Adults</option><option value="4">4 Adults</option></select></div>
                <div class="form-group"><label>🛋️ Cabin Class</label><select name="cabin_class"><option value="economy">Economy</option><option value="business">Business</option><option value="first">First</option></select></div>
            </div>
            <button type="submit" name="search_flights" class="search-btn">🔍 Search Flights</button>
        </form>
        
        <?php if ($searchPerformed): ?>
            <div style="margin-top: 30px;"><?php echo $flightResults; ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Umrah Packages Section -->
<section class="packages-luxury" id="umrah">
    <div class="container">
        <div class="section-header"><h2>Premium Umrah Packages 2026</h2><p>Experience spiritual devotion with luxury accommodations near the Holy Mosques</p></div>
        <div class="packages-grid">
            <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/4346403/pexels-photo-4346403.jpeg')"><div class="package-badge">€895</div></div><div class="package-content"><h3>Essence Umrah Package</h3><ul class="package-features"><li><i class="fas fa-check-circle"></i> 7 Days Makkah + 3 Days Madina</li><li><i class="fas fa-check-circle"></i> 3-Star Hotel with Shuttle</li><li><i class="fas fa-check-circle"></i> Economy Flight Tickets</li><li><i class="fas fa-check-circle"></i> Umrah Visa Processing</li></ul><button class="package-btn" onclick="bookPackage('Essence Umrah Package', '€895')">View Details</button></div></div>
            <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/2895295/pexels-photo-2895295.jpeg')"><div class="package-badge">€999</div></div><div class="package-content"><h3>Enhanced Umrah Package</h3><ul class="package-features"><li><i class="fas fa-check-circle"></i> 7 Days Makkah + 3 Days Madina</li><li><i class="fas fa-check-circle"></i> 4-Star Hotel with Shuttle</li><li><i class="fas fa-check-circle"></i> Flight Tickets Included</li><li><i class="fas fa-check-circle"></i> Fast-Track Visa</li></ul><button class="package-btn" onclick="bookPackage('Enhanced Umrah Package', '€999')">View Details</button></div></div>
            <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/12808985/pexels-photo-12808985.jpeg')"><div class="package-badge">€1,299</div></div><div class="package-content"><h3>Elite Umrah Package</h3><ul class="package-features"><li><i class="fas fa-check-circle"></i> 6 Days Makkah + 4 Days Madina</li><li><i class="fas fa-check-circle"></i> 5-Star Premium Hotel</li><li><i class="fas fa-check-circle"></i> Business Class Upgrade</li><li><i class="fas fa-check-circle"></i> VIP Concierge Service</li></ul><button class="package-btn" onclick="bookPackage('Elite Umrah Package', '€1,299')">View Details</button></div></div>
        </div>
    </div>
</section>

<!-- Hajj Section -->
<section class="hajj-section" id="hajj">
    <div class="container">
        <div class="section-header"><h2>Hajj Packages 2026</h2><p>Coming Soon - Register your interest for Hajj 2026</p></div>
        <div class="hajj-waiting"><i class="fas fa-clock" style="font-size: 64px; color: var(--primary-gold); margin-bottom: 20px;"></i><h3 style="color: var(--primary-navy); margin-bottom: 15px;">CLOSED FOR NOW</h3><p style="margin-bottom: 25px;">Hajj 2026 packages are currently in development. Phase 2 bookings will open soon.</p><a href="https://wa.me/34611473217?text=I'm interested in Hajj 2026 packages" class="whatsapp-btn-elegant" target="_blank"><i class="fab fa-whatsapp"></i> Notify Me When Open</a></div>
    </div>
</section>

<!-- Flight Deals Section -->
<section class="packages-luxury" id="flights" style="background: var(--light-bg);">
    <div class="container">
        <div class="section-header"><h2>Exclusive Flight Deals</h2><p>Special offers from Barcelona to worldwide destinations</p></div>
        <div class="deals-grid">
            <div class="deal-card"><div><strong>✈️ ETIHAD AIRWAYS</strong><br>Barcelona (BCN) → Lahore (LHE)</div><div class="deal-price">€580</div><a href="https://wa.me/34611473217?text=Interested in BCN to Lahore €580" class="book-btn">Book Now</a></div>
            <div class="deal-card"><div><strong>✈️ ETIHAD AIRWAYS</strong><br>Barcelona (BCN) → Islamabad (ISB)</div><div class="deal-price">€585</div><a href="https://wa.me/34611473217?text=Interested in BCN to Islamabad €585" class="book-btn">Book Now</a></div>
            <div class="deal-card"><div><strong>✈️ EMIRATES</strong><br>Barcelona (BCN) → Dubai (DXB)</div><div class="deal-price">€299</div><a href="https://wa.me/34611473217?text=Interested in BCN to Dubai €299" class="book-btn">Book Now</a></div>
            <div class="deal-card"><div><strong>✈️ BRITISH AIRWAYS</strong><br>Barcelona (BCN) → London (LHR)</div><div class="deal-price">€79</div><a href="https://wa.me/34611473217?text=Interested in BCN to London €79" class="book-btn">Book Now</a></div>
            <div class="deal-card"><div><strong>✈️ QATAR AIRWAYS</strong><br>Barcelona (BCN) → Doha (DOH)</div><div class="deal-price">€349</div><a href="https://wa.me/34611473217?text=Interested in BCN to Doha €349" class="book-btn">Book Now</a></div>
            <div class="deal-card"><div><strong>✈️ SINGAPORE AIRLINES</strong><br>Barcelona (BCN) → Singapore (SIN)</div><div class="deal-price">€599</div><a href="https://wa.me/34611473217?text=Interested in BCN to Singapore €599" class="book-btn">Book Now</a></div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="packages-luxury" id="services">
    <div class="container">
        <div class="section-header"><h2>Our Premium Services</h2><p>Comprehensive travel solutions tailored to your needs</p></div>
        <div class="services-grid">
            <div class="service-card"><div class="service-icon"><i class="fas fa-passport"></i></div><h3>Visa Processing</h3><p>Expert visa processing for Umrah, Hajj & worldwide travel</p></div>
            <div class="service-card"><div class="service-icon"><i class="fas fa-hotel"></i></div><h3>Luxury Hotels</h3><p>5-star hotel reservations worldwide with best rates</p></div>
            <div class="service-card"><div class="service-icon"><i class="fas fa-car"></i></div><h3>Airport Transfers</h3><p>Private luxury transfers to/from airport</p></div>
            <div class="service-card"><div class="service-icon"><i class="fas fa-headset"></i></div><h3>24/7 Support</h3><p>Round-the-clock customer support for all bookings</p></div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="packages-luxury" id="about" style="background: var(--light-bg);">
    <div class="container">
        <div class="section-header"><h2>About Mustafa Travels</h2><p>Your trusted partner for spiritual and leisure travel since 2024</p></div>
        <div class="about-content">
            <div><p>Mustafa Travels & Tours has been crafting exceptional travel experiences since 2024. We specialize in premium Umrah and Hajj journeys, offering unparalleled service and attention to detail.</p><p style="margin-top: 20px;">Our commitment to excellence ensures every spiritual journey is memorable, comfortable, and deeply meaningful.</p><p style="margin-top: 20px;"><strong>✈️ 500+ Happy Travelers | 🌍 50+ Destinations | ⭐ 4.9/5 Rating</strong></p><div style="margin-top: 25px;"><a href="https://wa.me/34611473217" class="whatsapp-btn-elegant" target="_blank"><i class="fab fa-whatsapp"></i> Chat with us on WhatsApp</a></div></div>
            <div class="about-image" style="background-image:url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')"></div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer-elegant" id="contact">
    <div class="container">
        <div class="footer-content">
            <div><h3 style="color: var(--white); margin-bottom: 20px;">Mustafa Travels & Tours</h3><p>Rambla Badal 141 Local 1 Bajo<br>Barcelona 08028, Spain</p><p style="margin-top: 15px;">📞 <a href="tel:+34632234216" style="color: var(--light-gold);">+34-632234216</a><br>💬 <a href="https://wa.me/34611473217" style="color: var(--light-gold);">+34-611473217</a><br>✉️ <a href="mailto:mustafatravelstours@gmail.com" style="color: var(--light-gold);">mustafatravelstours@gmail.com</a></p></div>
            <div><h3 style="color: var(--white); margin-bottom: 20px;">Quick Links</h3><p><a href="#home" style="color: rgba(255,255,255,0.7);">Home</a></p><p><a href="#umrah" style="color: rgba(255,255,255,0.7);">Umrah Packages</a></p><p><a href="#hajj" style="color: rgba(255,255,255,0.7);">Hajj 2026</a></p><p><a href="#flights" style="color: rgba(255,255,255,0.7);">Flight Deals</a></p><p><a href="#services" style="color: rgba(255,255,255,0.7);">Services</a></p></div>
            <div><h3 style="color: var(--white); margin-bottom: 20px;">Popular Destinations</h3><p>Pakistan | India | Bangladesh</p><p>USA | Canada | UK</p><p>UAE | Saudi Arabia | Turkey</p><p>Singapore | Malaysia | Indonesia</p></div>
            <div><h3 style="color: var(--white); margin-bottom: 20px;">Business Hours</h3><p>Monday - Thursday: 10:30 - 20:30</p><p>Friday: 10:30 - 13:00 & 15:00 - 20:30</p><p>Saturday: 10:30 - 19:30</p><p>Sunday: Closed</p><p style="margin-top: 10px;"><i class="fas fa-phone-alt"></i> 24/7 Emergency Support</p></div>
        </div>
        <div class="footer-bottom"><p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Crafted with <i class="fas fa-heart" style="color: var(--primary-gold);"></i> for spiritual journeys</p></div>
    </div>
</footer>

<script>
// Initialize Select2 for all airport selects
$(document).ready(function() {
    $('.airport-select').select2({
        placeholder: 'Search airport...',
        allowClear: true,
        width: '100%'
    });
});

// Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.luxury-slide');
const dots = document.querySelectorAll('.slider-dot-luxury');
function showSlide(index) {
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[index].classList.add('active');
    dots[index].classList.add('active');
    currentSlide = index;
}
setInterval(() => { currentSlide = (currentSlide + 1) % slides.length; showSlide(currentSlide); }, 5000);
dots.forEach((dot, i) => dot.addEventListener('click', () => showSlide(i)));

// Mobile menu
document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
    document.querySelector('.nav-elegant')?.classList.toggle('active');
});

// Package booking
function bookPackage(packageName, price) {
    window.open(`https://wa.me/34611473217?text=I'm interested in ${packageName} (${price}). Please share details.`, '_blank');
}

// Trip Type Toggle
function toggleTripType() {
    const tripType = document.querySelector('input[name="trip_type"]:checked').value;
    const onewayDiv = document.getElementById('onewayFields');
    const returnDiv = document.getElementById('returnFields');
    const multiDiv = document.getElementById('multiFields');
    
    if (tripType === 'oneway') {
        onewayDiv.style.display = 'block';
        returnDiv.style.display = 'none';
        multiDiv.style.display = 'none';
    } else if (tripType === 'return') {
        onewayDiv.style.display = 'none';
        returnDiv.style.display = 'block';
        multiDiv.style.display = 'none';
    } else {
        onewayDiv.style.display = 'none';
        returnDiv.style.display = 'none';
        multiDiv.style.display = 'block';
    }
}

// Handle form submission to copy values
document.getElementById('flightSearchForm')?.addEventListener('submit', function(e) {
    const tripType = document.querySelector('input[name="trip_type"]:checked').value;
    
    if (tripType === 'oneway') {
        document.querySelector('input[name="origin"]').value = document.querySelector('#origin_oneway').value;
        document.querySelector('input[name="destination"]').value = document.querySelector('#destination_oneway').value;
    } else if (tripType === 'return') {
        document.querySelector('input[name="origin_return"]').value = document.querySelector('#origin_return').value;
        document.querySelector('input[name="destination_return"]').value = document.querySelector('#destination_return').value;
    }
});

// Initialize
toggleTripType();

document.querySelectorAll('.nav-elegant a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            document.querySelector('.nav-elegant')?.classList.remove('active');
        }
    });
});

showSlide(0);
</script>
</body>
</html>
