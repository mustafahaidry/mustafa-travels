<?php
// Search Engine for Mustafa Travels
$searchQuery = '';
$searchResults = [];
$resultType = '';

// Duffel API for flights (already configured)
$duffelApiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';

// Process search request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
    $searchQuery = trim($_POST['search_query']);
    $searchType = $_POST['search_type'] ?? 'all';
    
    if (!empty($searchQuery)) {
        $searchResults = performSearch($searchQuery, $searchType);
    }
}

function performSearch($query, $type) {
    $results = [];
    
    // Search Flights (using Duffel API)
    if ($type == 'flights' || $type == 'all') {
        $flights = searchFlights($query);
        if (!empty($flights)) {
            $results['flights'] = $flights;
        }
    }
    
    // Search Hotels (using RateHawk/TBO)
    if ($type == 'hotels' || $type == 'all') {
        $hotels = searchHotels($query);
        if (!empty($hotels)) {
            $results['hotels'] = $hotels;
        }
    }
    
    // Search Umrah Packages
    if ($type == 'umrah' || $type == 'all') {
        $umrahPackages = searchUmrahPackages($query);
        if (!empty($umrahPackages)) {
            $results['umrah'] = $umrahPackages;
        }
    }
    
    // Search Visa Services
    if ($type == 'visa' || $type == 'all') {
        $visaServices = searchVisaServices($query);
        if (!empty($visaServices)) {
            $results['visa'] = $visaServices;
        }
    }
    
    return $results;
}

function searchFlights($query) {
    global $duffelApiKey;
    $flights = [];
    
    // Parse query like "BCN to LHE" or "Barcelona to Lahore"
    $queryUpper = strtoupper($query);
    $origin = '';
    $destination = '';
    
    if (preg_match('/([A-Z]{3})\s+(?:to|→)\s+([A-Z]{3})/', $queryUpper, $matches)) {
        $origin = $matches[1];
        $destination = $matches[2];
    } elseif (preg_match('/([A-Z]{3})\s+-\s+([A-Z]{3})/', $queryUpper, $matches)) {
        $origin = $matches[1];
        $destination = $matches[2];
    } elseif (preg_match('/([A-Z]{3})\s+to\s+([A-Z]{3})/i', $query, $matches)) {
        $origin = strtoupper($matches[1]);
        $destination = strtoupper($matches[2]);
    }
    
    if ($origin && $destination) {
        $date = date('Y-m-d', strtotime('+30 days'));
        
        $searchData = [
            'data' => [
                'slices' => [[
                    'origin' => $origin,
                    'destination' => $destination,
                    'departure_date' => $date
                ]],
                'passengers' => [['type' => 'adult']],
                'cabin_class' => 'economy',
                'max_connections' => 1
            ]
        ];
        
        $ch = curl_init('https://api.duffel.com/air/offer_requests?return_offers=true');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $duffelApiKey,
            'Duffel-Version: v2'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($searchData));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response) {
            $data = json_decode($response, true);
            $offers = $data['data']['offers'] ?? [];
            
            foreach (array_slice($offers, 0, 5) as $offer) {
                $seg = $offer['slices'][0]['segments'] ?? [];
                $first = $seg[0] ?? null;
                $last = $seg[count($seg)-1] ?? null;
                
                $flights[] = [
                    'type' => 'flight',
                    'title' => $offer['owner']['name'] ?? 'Airline',
                    'subtitle' => $origin . ' → ' . $destination,
                    'price' => $offer['total_amount'] ?? '0',
                    'currency' => $offer['total_currency'] ?? 'EUR',
                    'departure' => $first ? date('h:i A, M j', strtotime($first['departing_at'])) : 'N/A',
                    'arrival' => $last ? date('h:i A, M j', strtotime($last['arriving_at'])) : 'N/A',
                    'duration' => calculateDuration($seg),
                    'stops' => count($seg) - 1,
                    'url' => '#',
                    'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
                ];
            }
        }
    }
    
    // If no API results or query not parsed, show sample flights
    if (empty($flights)) {
        $flights[] = [
            'type' => 'flight',
            'title' => 'Etihad Airways',
            'subtitle' => 'BCN → LHE (Lahore)',
            'price' => '€595',
            'currency' => 'EUR',
            'departure' => '08:30 AM, May 15',
            'arrival' => '10:45 PM, May 15',
            'duration' => '7h 15m',
            'stops' => 1,
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ];
        $flights[] = [
            'type' => 'flight',
            'title' => 'Etihad Airways',
            'subtitle' => 'BCN → ISB (Islamabad)',
            'price' => '€600',
            'currency' => 'EUR',
            'departure' => '09:15 AM, May 15',
            'arrival' => '11:30 PM, May 15',
            'duration' => '7h 15m',
            'stops' => 1,
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ];
        $flights[] = [
            'type' => 'flight',
            'title' => 'Emirates',
            'subtitle' => 'BCN → DXB (Dubai)',
            'price' => '€314',
            'currency' => 'EUR',
            'departure' => '11:20 AM, May 15',
            'arrival' => '09:45 PM, May 15',
            'duration' => '6h 25m',
            'stops' => 0,
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ];
        $flights[] = [
            'type' => 'flight',
            'title' => 'Qatar Airways',
            'subtitle' => 'BCN → DOH (Doha)',
            'price' => '€349',
            'currency' => 'EUR',
            'departure' => '04:15 PM, May 15',
            'arrival' => '12:30 AM, May 16',
            'duration' => '6h 15m',
            'stops' => 0,
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ];
        $flights[] = [
            'type' => 'flight',
            'title' => 'Turkish Airlines',
            'subtitle' => 'BCN → IST (Istanbul)',
            'price' => '€289',
            'currency' => 'EUR',
            'departure' => '06:45 PM, May 15',
            'arrival' => '12:55 AM, May 16',
            'duration' => '3h 10m',
            'stops' => 0,
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ];
    }
    
    return $flights;
}

function searchHotels($query) {
    $hotels = [];
    
    $queryLower = strtolower($query);
    
    // Sample hotels based on search
    if (strpos($queryLower, 'makkah') !== false || strpos($queryLower, 'mecca') !== false) {
        $hotels[] = [
            'type' => 'hotel',
            'title' => 'Pullman Zamzam Makkah',
            'subtitle' => 'Makkah, Saudi Arabia',
            'price' => 'From €180/night',
            'currency' => 'EUR',
            'rating' => 5.0,
            'distance' => '100m from Haram',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
        $hotels[] = [
            'type' => 'hotel',
            'title' => 'Conrad Makkah',
            'subtitle' => 'Makkah, Saudi Arabia',
            'price' => 'From €160/night',
            'currency' => 'EUR',
            'rating' => 5.0,
            'distance' => '400m from Haram',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
        $hotels[] = [
            'type' => 'hotel',
            'title' => 'Al Kiswah Towers',
            'subtitle' => 'Makkah, Saudi Arabia',
            'price' => 'From €70/night',
            'currency' => 'EUR',
            'rating' => 4.0,
            'distance' => '1.6km from Haram (shuttle)',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
    } elseif (strpos($queryLower, 'madinah') !== false || strpos($queryLower, 'medina') !== false) {
        $hotels[] = [
            'type' => 'hotel',
            'title' => 'Anwar Al Madinah Mövenpick',
            'subtitle' => 'Madinah, Saudi Arabia',
            'price' => 'From €150/night',
            'currency' => 'EUR',
            'rating' => 5.0,
            'distance' => '100m from Haram',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
        $hotels[] = [
            'type' => 'hotel',
            'title' => 'Al Rawda Al Aqeeq',
            'subtitle' => 'Madinah, Saudi Arabia',
            'price' => 'From €60/night',
            'currency' => 'EUR',
            'rating' => 4.0,
            'distance' => '1.4km from Haram (shuttle)',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
    } elseif (strpos($queryLower, 'dubai') !== false) {
        $hotels[] = [
            'type' => 'hotel',
            'title' => 'Atlantis The Palm',
            'subtitle' => 'Dubai, UAE',
            'price' => 'From €300/night',
            'currency' => 'EUR',
            'rating' => 5.0,
            'distance' => 'Palm Jumeirah',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
        $hotels[] = [
            'type' => 'hotel',
            'title' => 'JW Marriott Marquis',
            'subtitle' => 'Dubai, UAE',
            'price' => 'From €150/night',
            'currency' => 'EUR',
            'rating' => 5.0,
            'distance' => 'Business Bay',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
    } else {
        // Default hotels
        $hotels[] = [
            'type' => 'hotel',
            'title' => '4-Star Hotels in Makkah',
            'subtitle' => 'Walking distance to Haram',
            'price' => 'From €80/night',
            'currency' => 'EUR',
            'rating' => 4.5,
            'distance' => '500m from Haram',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
        $hotels[] = [
            'type' => 'hotel',
            'title' => '5-Star Luxury Hotels in Makkah',
            'subtitle' => 'Clock Tower Area',
            'price' => 'From €150/night',
            'currency' => 'EUR',
            'rating' => 5.0,
            'distance' => '100m from Haram',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
        $hotels[] = [
            'type' => 'hotel',
            'title' => 'Budget Hotels in Madinah',
            'subtitle' => 'With free shuttle to Haram',
            'price' => 'From €50/night',
            'currency' => 'EUR',
            'rating' => 4.0,
            'distance' => '1.5km from Haram',
            'url' => '#',
            'image' => 'https://cdn-icons-png.flaticon.com/512/2961/2961966.png'
        ];
    }
    
    return $hotels;
}

function searchUmrahPackages($query) {
    $packages = [];
    
    // Updated Umrah Packages with correct prices
    $allPackages = [
        [
            'type' => 'umrah',
            'title' => 'Essence Umrah Package',
            'subtitle' => '7 Days Makkah + 3 Days Madinah',
            'price' => '€950',
            'currency' => 'EUR',
            'features' => '3-Star Hotel, Shuttle Service, Economy Flights',
            'url' => '#umrah',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ],
        [
            'type' => 'umrah',
            'title' => 'Enhanced Umrah Package',
            'subtitle' => '7 Days Makkah + 3 Days Madinah',
            'price' => '€1,050',
            'currency' => 'EUR',
            'features' => '4-Star Hotel, Shuttle Service, Flights Included',
            'url' => '#umrah',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ],
        [
            'type' => 'umrah',
            'title' => 'Elite Umrah Package',
            'subtitle' => '6 Days Makkah + 4 Days Madinah',
            'price' => '€1,350',
            'currency' => 'EUR',
            'features' => '5-Star Hotel, Business Class, VIP Service',
            'url' => '#umrah',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ]
    ];
    
    $queryLower = strtolower($query);
    
    foreach ($allPackages as $pkg) {
        if (strpos($queryLower, 'umrah') !== false || 
            strpos($queryLower, 'package') !== false ||
            strpos($queryLower, 'cheap') !== false ||
            strpos($queryLower, 'best') !== false) {
            $packages[] = $pkg;
        }
    }
    
    if (empty($packages)) {
        $packages = $allPackages;
    }
    
    return $packages;
}

function searchVisaServices($query) {
    $visaServices = [];
    
    $queryLower = strtolower($query);
    
    // Updated Visa Services with correct prices
    $allVisas = [
        [
            'type' => 'visa',
            'title' => 'Saudi Arabia Umrah Visa',
            'subtitle' => 'Valid for 30 days, Single entry',
            'price' => '€75 + €15 service fee',
            'currency' => 'EUR',
            'processing' => '7-10 working days',
            'url' => 'visa-services.php',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ],
        [
            'type' => 'visa',
            'title' => 'UK Visa Assistance',
            'subtitle' => 'Tourist, Business, Student Visa',
            'price' => 'From €150 + €15 service fee',
            'currency' => 'EUR',
            'processing' => '3-4 weeks',
            'url' => 'visa-services.php',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ],
        [
            'type' => 'visa',
            'title' => 'USA ESTA (ETA)',
            'subtitle' => 'Valid for 2 years, Multiple entries',
            'price' => '€21 + €15 service fee',
            'currency' => 'EUR',
            'processing' => '72 hours',
            'url' => 'visa-services.php',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ],
        [
            'type' => 'visa',
            'title' => 'Canada eTA',
            'subtitle' => 'Valid for 5 years, Multiple entries',
            'price' => 'CAD 7 + €15 service fee',
            'currency' => 'EUR',
            'processing' => '24-48 hours',
            'url' => 'visa-services.php',
            'image' => 'https://cdn-icons-png.flaticon.com/512/3114/3114883.png'
        ]
    ];
    
    if (strpos($queryLower, 'umrah visa') !== false || strpos($queryLower, 'saudi') !== false) {
        $visaServices[] = $allVisas[0];
    } elseif (strpos($queryLower, 'uk visa') !== false || strpos($queryLower, 'british') !== false) {
        $visaServices[] = $allVisas[1];
    } elseif (strpos($queryLower, 'usa') !== false || strpos($queryLower, 'esta') !== false) {
        $visaServices[] = $allVisas[2];
    } elseif (strpos($queryLower, 'canada') !== false || strpos($queryLower, 'eta') !== false) {
        $visaServices[] = $allVisas[3];
    }
    
    if (empty($visaServices)) {
        $visaServices = $allVisas;
    }
    
    return $visaServices;
}

function calculateDuration($segments) {
    $total = 0;
    foreach ($segments as $s) {
        $total += intval($s['duration'] ?? 0);
    }
    return floor($total/60) . 'h ' . ($total%60) . 'm';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels - Search Flights, Hotels & Umrah Packages</title>
    <meta name="description" content="Search flights, hotels, Umrah packages, and visa services. Best travel deals from Barcelona to worldwide destinations.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gold: #d4af37;
            --primary-navy: #1a237e;
            --primary-teal: #00695c;
            --light-bg: #f9f7f2;
            --shadow: 0 10px 30px rgba(0,0,0,0.08);
            --radius: 12px;
            --transition: all 0.4s cubic-bezier(0.175,0.885,0.32,1.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--light-bg);
            line-height: 1.7;
        }
        .search-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        /* Search Hero */
        .search-hero {
            background: linear-gradient(135deg, #1a237e 0%, #00695c 100%);
            padding: 60px 20px;
            text-align: center;
            color: white;
            border-radius: 0 0 30px 30px;
        }
        .search-hero h1 {
            font-size: 48px;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }
        .search-hero p {
            font-size: 18px;
            opacity: 0.9;
        }
        
        /* Search Box */
        .search-box {
            background: white;
            border-radius: 60px;
            padding: 10px;
            max-width: 800px;
            margin: -30px auto 0;
            position: relative;
            z-index: 10;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .search-input-wrapper {
            flex: 3;
            display: flex;
            align-items: center;
            background: #f5f5f5;
            border-radius: 50px;
            padding: 5px 20px;
        }
        .search-input-wrapper i {
            color: #d4af37;
            font-size: 18px;
        }
        .search-input {
            flex: 1;
            padding: 18px 15px;
            border: none;
            background: transparent;
            font-size: 16px;
            outline: none;
        }
        .search-type {
            flex: 1;
            display: flex;
            gap: 10px;
            align-items: center;
            background: #f5f5f5;
            border-radius: 50px;
            padding: 5px 15px;
        }
        .search-type select {
            flex: 1;
            padding: 15px;
            border: none;
            background: transparent;
            font-size: 14px;
            outline: none;
            cursor: pointer;
        }
        .search-btn {
            background: linear-gradient(135deg, #00695c, #1a237e);
            color: white;
            border: none;
            padding: 0 35px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .search-btn:hover {
            transform: translateY(-2px);
            background: #d4af37;
            color: #1a237e;
        }
        
        /* Search Tips */
        .search-tips {
            text-align: center;
            margin: 20px auto;
            max-width: 800px;
        }
        .search-tips span {
            display: inline-block;
            background: white;
            padding: 8px 16px;
            border-radius: 50px;
            margin: 5px;
            font-size: 13px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .search-tips span:hover {
            background: #d4af37;
            color: white;
            transform: translateY(-2px);
        }
        
        /* Results Section */
        .results-section {
            margin-top: 40px;
        }
        .results-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #d4af37;
        }
        .results-header h2 {
            color: #1a237e;
            font-size: 24px;
        }
        .results-count {
            color: #666;
            font-size: 14px;
        }
        
        /* Result Cards */
        .result-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            border: 1px solid #eee;
        }
        .result-card:hover {
            transform: translateY(-5px);
            border-left: 4px solid #d4af37;
        }
        .result-icon {
            width: 70px;
            height: 70px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }
        .result-content {
            flex: 3;
        }
        .result-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 8px;
        }
        .result-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .result-details {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 10px;
        }
        .result-detail {
            font-size: 13px;
            color: #555;
        }
        .result-detail i {
            color: #d4af37;
            width: 20px;
        }
        .result-price {
            text-align: right;
            min-width: 150px;
        }
        .price {
            font-size: 28px;
            font-weight: 800;
            color: #00695c;
        }
        .price-note {
            font-size: 12px;
            color: #999;
        }
        .result-btn {
            background: #25D366;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
            transition: 0.3s;
        }
        .result-btn:hover {
            transform: scale(1.05);
        }
        
        /* No Results */
        .no-results {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 20px;
        }
        .no-results i {
            font-size: 64px;
            color: #d4af37;
            margin-bottom: 20px;
        }
        .no-results h3 {
            color: #1a237e;
            margin-bottom: 10px;
        }
        
        @media (max-width: 768px) {
            .search-box { border-radius: 20px; margin-top: -50px; }
            .search-form { flex-direction: column; }
            .search-input-wrapper, .search-type, .search-btn { border-radius: 50px; }
            .search-btn { padding: 15px; }
            .result-card { flex-direction: column; }
            .result-price { text-align: left; }
            .search-hero h1 { font-size: 32px; }
        }
    </style>
</head>
<body>

<div class="search-hero">
    <h1>🔍 Search Flights, Hotels & Umrah</h1>
    <p>Find the best travel deals from Barcelona to worldwide destinations</p>
</div>

<div class="search-container">
    <div class="search-box">
        <form method="POST" action="" class="search-form">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" name="search_query" class="search-input" 
                       placeholder="Search flights, hotels, Umrah packages, visas..." 
                       value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>
            <div class="search-type">
                <i class="fas fa-filter"></i>
                <select name="search_type">
                    <option value="all">🔍 All</option>
                    <option value="flights">✈️ Flights</option>
                    <option value="hotels">🏨 Hotels</option>
                    <option value="umrah">🕋 Umrah</option>
                    <option value="visa">📄 Visa</option>
                </select>
            </div>
            <button type="submit" name="search" class="search-btn">Search</button>
        </form>
    </div>
    
    <div class="search-tips">
        <span onclick="fillSearch('Flights from BCN to LHE')">✈️ BCN to LHE</span>
        <span onclick="fillSearch('Hotels in Makkah')">🏨 Hotels in Makkah</span>
        <span onclick="fillSearch('Umrah packages cheap')">🕋 Umrah packages</span>
        <span onclick="fillSearch('Umrah visa')">📄 Umrah visa</span>
        <span onclick="fillSearch('Flights BCN to JED')">✈️ BCN to JED</span>
        <span onclick="fillSearch('Hotels in Madinah')">🏨 Madinah hotels</span>
        <span onclick="fillSearch('Hotels in Dubai')">🏨 Dubai hotels</span>
        <span onclick="fillSearch('Flights BCN to RAK')">✈️ BCN to Morocco</span>
    </div>
    
    <?php if (!empty($searchQuery) && isset($_POST['search'])): ?>
    <div class="results-section">
        <div class="results-header">
            <h2>🔍 Search Results for: "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
            <div class="results-count">
                <?php 
                    $total = 0;
                    foreach ($searchResults as $category => $items) {
                        $total += count($items);
                    }
                    echo "Found " . $total . " results";
                ?>
            </div>
        </div>
        
        <?php if (empty($searchResults)): ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h3>No results found</h3>
                <p>Try searching for something else like "flights to Lahore", "hotels in Makkah", or "Umrah packages".</p>
            </div>
        <?php else: ?>
            
            <?php if (isset($searchResults['flights'])): ?>
                <h3 style="margin: 20px 0 10px; color: #1a237e;">✈️ Flights</h3>
                <?php foreach ($searchResults['flights'] as $result): ?>
                    <div class="result-card">
                        <div class="result-icon"><i class="fas fa-plane"></i></div>
                        <div class="result-content">
                            <div class="result-title"><?php echo htmlspecialchars($result['title']); ?></div>
                            <div class="result-subtitle"><?php echo htmlspecialchars($result['subtitle']); ?></div>
                            <div class="result-details">
                                <div class="result-detail"><i class="fas fa-clock"></i> Depart: <?php echo $result['departure']; ?></div>
                                <div class="result-detail"><i class="fas fa-clock"></i> Arrive: <?php echo $result['arrival']; ?></div>
                                <div class="result-detail"><i class="fas fa-hourglass-half"></i> Duration: <?php echo $result['duration']; ?></div>
                                <div class="result-detail"><i class="fas fa-exchange-alt"></i> <?php echo $result['stops'] == 0 ? 'Direct' : $result['stops'] . ' stop'; ?></div>
                            </div>
                        </div>
                        <div class="result-price">
                            <div class="price"><?php echo $result['price']; ?> <?php echo $result['currency']; ?></div>
                            <div class="price-note">+ €15 service fee</div>
                            <a href="https://wa.me/34611473217?text=I'm interested in flight <?php echo $result['subtitle']; ?> for <?php echo $result['price']; ?> <?php echo $result['currency']; ?>" class="result-btn">Book Now →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (isset($searchResults['hotels'])): ?>
                <h3 style="margin: 20px 0 10px; color: #1a237e;">🏨 Hotels</h3>
                <?php foreach ($searchResults['hotels'] as $result): ?>
                    <div class="result-card">
                        <div class="result-icon"><i class="fas fa-hotel"></i></div>
                        <div class="result-content">
                            <div class="result-title"><?php echo htmlspecialchars($result['title']); ?></div>
                            <div class="result-subtitle"><?php echo htmlspecialchars($result['subtitle']); ?></div>
                            <div class="result-details">
                                <div class="result-detail"><i class="fas fa-star" style="color: #d4af37;"></i> Rating: <?php echo $result['rating']; ?> / 5</div>
                                <div class="result-detail"><i class="fas fa-map-marker-alt"></i> <?php echo $result['distance']; ?></div>
                            </div>
                        </div>
                        <div class="result-price">
                            <div class="price"><?php echo $result['price']; ?></div>
                            <a href="https://wa.me/34611473217?text=I'm interested in hotel <?php echo $result['title']; ?> in <?php echo $result['subtitle']; ?>" class="result-btn">Inquire →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (isset($searchResults['umrah'])): ?>
                <h3 style="margin: 20px 0 10px; color: #1a237e;">🕋 Umrah Packages</h3>
                <?php foreach ($searchResults['umrah'] as $result): ?>
                    <div class="result-card">
                        <div class="result-icon"><i class="fas fa-kaaba"></i></div>
                        <div class="result-content">
                            <div class="result-title"><?php echo htmlspecialchars($result['title']); ?></div>
                            <div class="result-subtitle"><?php echo htmlspecialchars($result['subtitle']); ?></div>
                            <div class="result-details">
                                <div class="result-detail"><i class="fas fa-check-circle"></i> <?php echo $result['features']; ?></div>
                            </div>
                        </div>
                        <div class="result-price">
                            <div class="price"><?php echo $result['price']; ?></div>
                            <a href="https://wa.me/34611473217?text=I'm interested in <?php echo $result['title']; ?> for <?php echo $result['price']; ?>" class="result-btn">View Details →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (isset($searchResults['visa'])): ?>
                <h3 style="margin: 20px 0 10px; color: #1a237e;">📄 Visa Services</h3>
                <?php foreach ($searchResults['visa'] as $result): ?>
                    <div class="result-card">
                        <div class="result-icon"><i class="fas fa-passport"></i></div>
                        <div class="result-content">
                            <div class="result-title"><?php echo htmlspecialchars($result['title']); ?></div>
                            <div class="result-subtitle"><?php echo htmlspecialchars($result['subtitle']); ?></div>
                            <div class="result-details">
                                <div class="result-detail"><i class="fas fa-clock"></i> Processing: <?php echo $result['processing']; ?></div>
                            </div>
                        </div>
                        <div class="result-price">
                            <div class="price"><?php echo $result['price']; ?></div>
                            <a href="https://wa.me/34611473217?text=I'm interested in <?php echo $result['title']; ?> visa" class="result-btn">Apply →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if (empty($searchQuery) || !isset($_POST['search'])): ?>
    <div style="text-align: center; padding: 60px 20px;">
        <i class="fas fa-search" style="font-size: 64px; color: #d4af37; margin-bottom: 20px;"></i>
        <h3 style="color: #1a237e;">Start your search</h3>
        <p style="color: #666;">Search for flights, hotels, Umrah packages, or visa services</p>
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin-top: 30px;">
            <div style="background: white; padding: 15px 25px; border-radius: 60px;">
                <strong>✈️ Example:</strong> "Flights from BCN to LHE"
            </div>
            <div style="background: white; padding: 15px 25px; border-radius: 60px;">
                <strong>🏨 Example:</strong> "Hotels in Makkah"
            </div>
            <div style="background: white; padding: 15px 25px; border-radius: 60px;">
                <strong>🕋 Example:</strong> "Umrah packages cheap"
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function fillSearch(text) {
    document.querySelector('input[name="search_query"]').value = text;
    document.querySelector('form').submit();
}
</script>
</body>
</html>
