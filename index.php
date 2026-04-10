<?php
// 🔑 Duffel Live Token
$apiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';
$flightResults = '';
$searchPerformed = false;

// Airport names
$airports = [
    'LHE' => 'Lahore, Pakistan', 'ISB' => 'Islamabad, Pakistan', 'KHI' => 'Karachi, Pakistan',
    'DEL' => 'Delhi, India', 'BOM' => 'Mumbai, India', 'DAC' => 'Dhaka, Bangladesh',
    'JFK' => 'New York, USA', 'LAX' => 'Los Angeles, USA', 'YYZ' => 'Toronto, Canada',
    'LHR' => 'London, UK', 'CDG' => 'Paris, France', 'FRA' => 'Frankfurt, Germany',
    'DXB' => 'Dubai, UAE', 'SIN' => 'Singapore', 'BCN' => 'Barcelona, Spain', 'MAD' => 'Madrid, Spain'
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
            $flightResults = '<div class="success-header">✈️ Found ' . count($offers) . ' flights from ' . getAirportName($origin) . ' to ' . getAirportName($destination) . '</div>';
            
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
                $price = $offer['total_amount'] ?? '0';
                $currency = $offer['total_currency'] ?? 'EUR';
                
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
                        <div>📅 ' . date('M j, Y', strtotime($date)) . '</div>
                        <a href="https://wa.me/34611473217?text=I want to book ' . $origin . ' to ' . $destination . ' for ' . $price . ' ' . $currency . '" class="book-btn">📱 Book on WhatsApp</a>
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels | Umrah, Hajj & Flight Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f0f2f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Header */
        .main-header { background: linear-gradient(135deg, #1a237e 0%, #00695c 100%); padding: 15px 0; position: sticky; top: 0; z-index: 100; }
        .header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .logo h1 { color: white; font-size: 24px; font-family: 'Playfair Display', serif; }
        .logo p { color: #d4af37; font-size: 12px; letter-spacing: 2px; }
        .contact-header { display: flex; gap: 15px; }
        .contact-header a { color: white; text-decoration: none; padding: 8px 20px; border-radius: 50px; transition: 0.3s; font-size: 14px; }
        .contact-header a:hover { background: rgba(255,255,255,0.2); }
        .whatsapp-header { background: #25D366; }
        
        /* Hero */
        .hero { background: linear-gradient(135deg, #1a237e 0%, #00695c 100%); padding: 50px 0 70px; text-align: center; color: white; }
        .hero h1 { font-size: 42px; margin-bottom: 15px; }
        .hero p { font-size: 18px; opacity: 0.9; }
        
        /* Cards */
        .card { background: white; border-radius: 24px; padding: 35px; margin-bottom: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-top: 5px solid #d4af37; }
        .section-title { font-size: 28px; color: #1a237e; margin-bottom: 25px; border-left: 4px solid #d4af37; padding-left: 15px; }
        
        /* Search Form */
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: #333; margin-bottom: 8px; font-size: 14px; }
        .form-group input, .form-group select { width: 100%; padding: 14px 16px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 16px; }
        .form-group input:focus, .form-group select:focus { border-color: #d4af37; outline: none; }
        .search-btn { background: linear-gradient(135deg, #00695c, #1a237e); color: white; padding: 16px 30px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; transition: 0.3s; }
        .search-btn:hover { transform: translateY(-3px); }
        
        /* Flight Results */
        .flight-card { background: #f8f9fa; border-radius: 20px; padding: 20px; margin-bottom: 15px; border: 1px solid #eee; }
        .flight-card:hover { border-left: 4px solid #d4af37; }
        .flight-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 15px; }
        .airline-info { display: flex; align-items: center; gap: 15px; }
        .airline-icon { width: 45px; height: 45px; background: #1a237e; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
        .airline-name { font-weight: 700; font-size: 16px; color: #1a237e; }
        .flight-price { font-size: 24px; font-weight: 800; color: #00695c; }
        .flight-route { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin: 15px 0; }
        .city-code { font-weight: 800; font-size: 20px; }
        .city-name { font-size: 11px; color: #666; }
        .flight-time { font-weight: 600; font-size: 14px; margin-top: 5px; }
        .flight-duration { text-align: center; flex: 1; }
        .duration-line { height: 2px; background: #d4af37; width: 100%; }
        .book-btn { background: #25D366; color: white; padding: 10px 20px; border-radius: 50px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 600; font-size: 14px; transition: 0.3s; }
        .book-btn:hover { transform: scale(1.05); }
        .error { background: #ffebee; padding: 15px; border-radius: 12px; color: #c62828; }
        .success-header { background: #e8f5e9; padding: 12px; border-radius: 12px; margin-bottom: 20px; color: #2e7d32; }
        
        /* Packages Grid */
        .packages-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 30px; }
        .package-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: 0.3s; border: 1px solid #eee; }
        .package-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
        .package-image { height: 180px; background-size: cover; background-position: center; position: relative; }
        .package-badge { position: absolute; top: 15px; right: 15px; background: #d4af37; color: #1a237e; padding: 5px 15px; border-radius: 50px; font-weight: 700; }
        .package-content { padding: 20px; }
        .package-title { font-size: 20px; color: #1a237e; margin-bottom: 10px; }
        .package-features { list-style: none; margin: 15px 0; }
        .package-features li { padding: 8px 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; font-size: 14px; }
        .package-features i { color: #d4af37; }
        .package-btn { background: linear-gradient(135deg, #00695c, #1a237e); color: white; padding: 12px; border-radius: 50px; border: none; cursor: pointer; width: 100%; font-weight: 600; transition: 0.3s; }
        .package-btn:hover { transform: translateY(-2px); }
        
        /* Flight Deals */
        .deals-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .deal-card { background: #f8f9fa; border-radius: 16px; padding: 20px; border-left: 4px solid #d4af37; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .deal-price { font-size: 24px; font-weight: 700; color: #00695c; }
        
        /* Services */
        .services-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; }
        .service-card { background: white; padding: 30px; border-radius: 20px; text-align: center; transition: 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .service-card:hover { transform: translateY(-5px); }
        .service-icon { width: 70px; height: 70px; background: #f5e8c8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px; color: #00695c; }
        
        /* About */
        .about-content { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center; }
        .about-image { height: 350px; background-size: cover; background-position: center; border-radius: 20px; }
        
        /* Footer */
        .footer { background: linear-gradient(135deg, #1a237e 0%, #0d1440 100%); color: white; padding: 50px 0 30px; margin-top: 40px; }
        .footer-content { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; margin-bottom: 30px; }
        .footer a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .footer a:hover { color: #d4af37; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); }
        
        @media (max-width: 768px) {
            .packages-grid, .services-grid, .footer-content, .about-content { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .hero h1 { font-size: 28px; }
            .card { padding: 20px; }
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
        <h1>Your Trusted Travel Partner</h1>
        <p>Umrah | Hajj | Flights | Visa | Hotels</p>
    </div>
</section>

<div class="container">
    <!-- Flight Search Section -->
    <div class="card">
        <h2 class="section-title">✈️ Search & Book Flights</h2>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group"><label>From</label><input type="text" name="origin" placeholder="LHE, BCN, JFK" value="<?php echo isset($_POST['origin']) ? $_POST['origin'] : 'BCN'; ?>" required></div>
                <div class="form-group"><label>To</label><input type="text" name="destination" placeholder="MAD, LHR, DXB" value="<?php echo isset($_POST['destination']) ? $_POST['destination'] : 'MAD'; ?>" required></div>
                <div class="form-group"><label>Departure Date</label><input type="date" name="departure_date" value="<?php echo isset($_POST['departure_date']) ? $_POST['departure_date'] : date('Y-m-d', strtotime('+30 days')); ?>" required></div>
                <div class="form-group"><label>Passengers</label><select name="passengers"><option value="1">1 Adult</option><option value="2">2 Adults</option><option value="3">3 Adults</option><option value="4">4 Adults</option></select></div>
                <div class="form-group"><label>Cabin</label><select name="cabin_class"><option value="economy">Economy</option><option value="business">Business</option><option value="first">First</option></select></div>
            </div>
            <button type="submit" name="search_flights" class="search-btn">🔍 Search Flights</button>
        </form>
        <?php if ($searchPerformed): ?>
            <div style="margin-top: 30px;"><?php echo $flightResults; ?></div>
        <?php endif; ?>
    </div>

    <!-- Umrah Packages -->
    <div class="card" id="umrah">
        <h2 class="section-title">🕋 Premium Umrah Packages 2026</h2>
        <div class="packages-grid">
            <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/4346403/pexels-photo-4346403.jpeg')"><div class="package-badge">€895</div></div><div class="package-content"><h3 class="package-title">Essence Umrah</h3><ul class="package-features"><li><i class="fas fa-check-circle"></i> 7 Days Makkah + 3 Days Madina</li><li><i class="fas fa-check-circle"></i> 3-Star Hotel with Shuttle</li><li><i class="fas fa-check-circle"></i> Economy Flights</li><li><i class="fas fa-check-circle"></i> Visa Processing</li></ul><button class="package-btn" onclick="bookUmrah('Essence Umrah', '€895')">View Details</button></div></div>
            <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/2895295/pexels-photo-2895295.jpeg')"><div class="package-badge">€999</div></div><div class="package-content"><h3 class="package-title">Enhanced Umrah</h3><ul class="package-features"><li><i class="fas fa-check-circle"></i> 7 Days Makkah + 3 Days Madina</li><li><i class="fas fa-check-circle"></i> 4-Star Hotel with Shuttle</li><li><i class="fas fa-check-circle"></i> Flights Included</li><li><i class="fas fa-check-circle"></i> Fast-Track Visa</li></ul><button class="package-btn" onclick="bookUmrah('Enhanced Umrah', '€999')">View Details</button></div></div>
            <div class="package-card"><div class="package-image" style="background-image:url('https://images.pexels.com/photos/12808985/pexels-photo-12808985.jpeg')"><div class="package-badge">€1,299</div></div><div class="package-content"><h3 class="package-title">Elite Umrah</h3><ul class="package-features"><li><i class="fas fa-check-circle"></i> 6 Days Makkah + 4 Days Madina</li><li><i class="fas fa-check-circle"></i> 5-Star Premium Hotel</li><li><i class="fas fa-check-circle"></i> Business Class Upgrade</li><li><i class="fas fa-check-circle"></i> VIP Concierge</li></ul><button class="package-btn" onclick="bookUmrah('Elite Umrah', '€1,299')">View Details</button></div></div>
        </div>
    </div>

    <!-- Hajj Section -->
    <div class="card" id="hajj">
        <h2 class="section-title">🕋 Hajj Packages 2026</h2>
        <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 20px; border: 2px dashed #d4af37;">
            <i class="fas fa-clock" style="font-size: 48px; color: #d4af37;"></i>
            <h3 style="color: #1a237e; margin: 20px 0;">CLOSED FOR NOW</h3>
            <p style="margin-bottom: 20px;">Hajj 2026 packages coming soon. Phase 2 bookings will open shortly.</p>
            <a href="https://wa.me/34611473217?text=I'm interested in Hajj 2026 packages" class="book-btn" style="background: #1a237e;">Notify Me When Open</a>
        </div>
    </div>

    <!-- Special Flight Deals -->
    <div class="card" id="deals">
        <h2 class="section-title">⭐ Special Flight Deals</h2>
        <div class="deals-grid">
            <div class="deal-card"><div><strong>✈️ Etihad Airways</strong><br>Barcelona → Lahore</div><div class="deal-price">€580</div><a href="https://wa.me/34611473217?text=Interested in BCN to Lahore €580" class="book-btn" style="padding: 8px 16px; font-size: 12px;">Book</a></div>
            <div class="deal-card"><div><strong>✈️ Etihad Airways</strong><br>Barcelona → Islamabad</div><div class="deal-price">€585</div><a href="https://wa.me/34611473217?text=Interested in BCN to Islamabad €585" class="book-btn" style="padding: 8px 16px; font-size: 12px;">Book</a></div>
            <div class="deal-card"><div><strong>✈️ Emirates</strong><br>Barcelona → Dubai</div><div class="deal-price">€299</div><a href="https://wa.me/34611473217?text=Interested in BCN to Dubai €299" class="book-btn" style="padding: 8px 16px; font-size: 12px;">Book</a></div>
            <div class="deal-card"><div><strong>✈️ Qatar Airways</strong><br>Madrid → Doha</div><div class="deal-price">€349</div><a href="https://wa.me/34611473217?text=Interested in MAD to Doha €349" class="book-btn" style="padding: 8px 16px; font-size: 12px;">Book</a></div>
        </div>
    </div>

    <!-- Services -->
    <div class="card" id="services">
        <h2 class="section-title">⭐ Our Premium Services</h2>
        <div class="services-grid">
            <div class="service-card"><div class="service-icon"><i class="fas fa-passport"></i></div><h3>Visa Processing</h3><p>Expert visa services for Umrah, Hajj & worldwide travel</p></div>
            <div class="service-card"><div class="service-icon"><i class="fas fa-hotel"></i></div><h3>Luxury Hotels</h3><p>5-star hotel bookings worldwide with best rates</p></div>
            <div class="service-card"><div class="service-icon"><i class="fas fa-car"></i></div><h3>Airport Transfers</h3><p>Private luxury transfers to/from airport</p></div>
            <div class="service-card"><div class="service-icon"><i class="fas fa-headset"></i></div><h3>24/7 Support</h3><p>Round-the-clock customer support for all bookings</p></div>
        </div>
    </div>

    <!-- About Us -->
    <div class="card" id="about">
        <h2 class="section-title">About Mustafa Travels</h2>
        <div class="about-content">
            <div><p>Mustafa Travels & Tours has been crafting exceptional travel experiences since 2024. We specialize in premium Umrah and Hajj journeys, offering unparalleled service and attention to detail.</p><p style="margin-top: 15px;">Our commitment to excellence ensures every spiritual journey is memorable, comfortable, and deeply meaningful. We blend traditional values with modern luxury to create unforgettable experiences.</p><p style="margin-top: 15px;"><strong>✈️ 500+ Happy Travelers | 🌍 30+ Destinations | ⭐ 4.9 Rating</strong></p></div>
            <div class="about-image" style="background-image:url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80')"></div>
        </div>
    </div>
</div>

<footer class="footer" id="contact">
    <div class="container">
        <div class="footer-content">
            <div><h3 style="margin-bottom:15px;">Mustafa Travels</h3><p>Rambla Badal 141 Local 1 Bajo<br>Barcelona 08028, Spain</p><p>📞 +34-632234216<br>💬 +34-611473217<br>✉️ mustafatravelstours@gmail.com</p></div>
            <div><h3 style="margin-bottom:15px;">Quick Links</h3><p><a href="#">Home</a></p><p><a href="#umrah">Umrah Packages</a></p><p><a href="#hajj">Hajj 2026</a></p><p><a href="#deals">Flight Deals</a></p><p><a href="#services">Services</a></p><p><a href="#about">About</a></p></div>
            <div><h3 style="margin-bottom:15px;">Destinations</h3><p>Pakistan | India | Bangladesh</p><p>USA | Canada | UK</p><p>UAE | Saudi Arabia | Turkey</p><p>Spain | France | Germany</p></div>
            <div><h3 style="margin-bottom:15px;">Business Hours</h3><p>Mon-Thu: 10:30 - 20:30</p><p>Fri: 10:30-13:00 & 15:00-20:30</p><p>Sat: 10:30 - 19:30</p><p>Sun: Closed</p></div>
        </div>
        <div class="footer-bottom"><p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Best Price Guarantee | 24/7 Support</p></div>
    </div>
</footer>

<script>
function bookUmrah(packageName, price) {
    window.open(`https://wa.me/34611473217?text=I'm interested in ${packageName} (${price}). Please share details.`, '_blank');
}
</script>
</body>
</html>
