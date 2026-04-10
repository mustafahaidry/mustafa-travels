<?php
// 🔑 Duffel Live Token
$apiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';
$flightResults = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_flights'])) {
    $origin = strtoupper(trim($_POST['origin']));
    $destination = strtoupper(trim($_POST['destination']));
    $date = $_POST['departure_date'];
    $passengers = intval($_POST['passengers']);
    
    $searchData = [
        'data' => [
            'slices' => [[
                'origin' => $origin,
                'destination' => $destination,
                'departure_date' => $date
            ]],
            'passengers' => array_fill(0, $passengers, ['type' => 'adult']),
            'cabin_class' => 'economy',
            'max_connections' => 0
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
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response === false) {
        $flightResults = '<div class="error">❌ Connection error. Please try again.</div>';
    } else {
        $data = json_decode($response, true);
        $offers = $data['data']['offers'] ?? [];
        
        if (count($offers) > 0) {
            $flightResults = '<div style="background:#e8f5e9;padding:12px;border-radius:12px;margin-bottom:20px;">✅ Found ' . count($offers) . ' real flights from ' . $origin . ' to ' . $destination . '</div>';
            foreach (array_slice($offers, 0, 10) as $offer) {
                $segment = $offer['slices'][0]['segments'][0] ?? null;
                $depTime = $segment ? date('H:i', strtotime($segment['departing_at'])) : 'N/A';
                $arrTime = $segment ? date('H:i', strtotime($segment['arriving_at'])) : 'N/A';
                $flightResults .= '
                <div class="flight-card">
                    <div class="airline">✈️ ' . htmlspecialchars($offer['owner']['name'] ?? 'Airline') . '</div>
                    <div class="route"><span class="city">' . $origin . '</span> <span class="arrow">→</span> <span class="city">' . $destination . '</span></div>
                    <div>🕐 Depart: ' . $depTime . ' | 🕒 Arrive: ' . $arrTime . '</div>
                    <div class="price">💰 ' . $offer['total_amount'] . ' ' . $offer['total_currency'] . '</div>
                    <a href="https://wa.me/34611473217?text=I want to book ' . $origin . ' to ' . $destination . ' for ' . $offer['total_amount'] . ' ' . $offer['total_currency'] . '" class="book-btn">📱 Book on WhatsApp</a>
                </div>';
            }
        } else {
            $flightResults = '<div class="error">✈️ No flights found from ' . $origin . ' to ' . $destination . ' on ' . $date . '. Try different date.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels & Tours | Flight Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #1a237e 0%, #00695c 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        
        /* Header Styles */
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: white; font-size: 42px; margin-bottom: 10px; font-family: 'Playfair Display', serif; }
        .header p { color: rgba(255,255,255,0.9); font-size: 18px; }
        .badge {
            background: #ff9800;
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        /* Card Styles */
        .card {
            background: white;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border-top: 5px solid #d4af37;
            margin-bottom: 30px;
        }
        .card h2 { color: #1a237e; margin-bottom: 25px; border-left: 4px solid #d4af37; padding-left: 15px; font-family: 'Playfair Display', serif; }
        
        /* Form Styles */
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: #1a237e; margin-bottom: 8px; }
        input, select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
        }
        input:focus, select:focus { border-color: #d4af37; outline: none; }
        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
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
            transition: all 0.3s;
        }
        .search-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        
        /* Flight Results Styles */
        .flight-card {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s;
            border: 1px solid #eee;
        }
        .flight-card:hover { transform: translateX(5px); border-left: 4px solid #d4af37; }
        .airline { font-weight: 700; font-size: 18px; color: #1a237e; margin-bottom: 12px; }
        .route { display: flex; align-items: center; gap: 20px; margin-bottom: 12px; flex-wrap: wrap; }
        .city { font-weight: 700; font-size: 20px; color: #333; }
        .arrow { color: #d4af37; font-size: 24px; }
        .price { font-size: 28px; font-weight: 700; color: #00695c; margin: 12px 0; }
        .book-btn {
            background: #25D366; color: white; padding: 10px 20px; border-radius: 50px;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
            font-weight: 600; font-size: 14px; transition: 0.3s; border: none; cursor: pointer;
        }
        .book-btn:hover { transform: scale(1.05); }
        .error { background: #ffebee; color: #c62828; padding: 15px; border-radius: 12px; margin-bottom: 15px; }
        .live-badge { background: #4caf50; color: white; padding: 4px 12px; border-radius: 50px; font-size: 12px; margin-left: 10px; }
        
        /* Route Grid Styles */
        .route-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; margin-top: 30px; }
        .route-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-left: 4px solid #d4af37;
        }
        .route-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.15); }
        .route-title { font-weight: 700; font-size: 20px; color: #1a237e; margin-bottom: 10px; }
        .route-detail { color: #666; font-size: 14px; margin: 8px 0; display: flex; align-items: center; gap: 8px; }
        .route-price { font-size: 28px; font-weight: 700; color: #00695c; margin: 15px 0; }
        
        /* Services Grid */
        .services-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; margin-top: 30px; }
        .service-card {
            background: white;
            padding: 30px;
            border-radius: 16px;
            text-align: center;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .service-card:hover { transform: translateY(-5px); }
        .service-icon { width: 70px; height: 70px; background: #f5e8c8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 28px; color: #00695c; }
        
        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1a237e 0%, #0d1440 100%);
            color: white;
            padding: 50px 0 30px;
            border-radius: 24px;
            margin-top: 30px;
        }
        .footer-content { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; margin-bottom: 30px; }
        .footer a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .footer a:hover { color: #d4af37; }
        .footer-bottom { text-align: center; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.1); }
        
        /* Contact Bar */
        .contact-bar {
            background: linear-gradient(135deg, #e3f2fd, #bbdef5);
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            margin-top: 20px;
        }
        .contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #00695c, #1a237e);
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            margin: 0 10px;
            font-weight: 600;
            transition: 0.3s;
        }
        .contact-btn:hover { transform: translateY(-3px); }
        .contact-btn.whatsapp { background: #25D366; }
        
        @media (max-width: 768px) {
            .row, .services-grid, .footer-content { grid-template-columns: 1fr; }
            .route-grid { grid-template-columns: 1fr; }
            .contact-btn { display: block; margin: 10px; }
            .card { padding: 20px; }
            .header h1 { font-size: 32px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <span class="badge">✈️ BEST PRICES GUARANTEED</span>
        <h1>Mustafa Travels & Tours</h1>
        <p>Book Domestic & International Flights | 24/7 Support</p>
    </div>
    
    <!-- Flight Search Section -->
    <div class="card">
        <h2>🔍 Search Real Flights <span class="live-badge">LIVE MODE</span></h2>
        
        <form method="POST" action="">
            <div class="row">
                <div class="form-group">
                    <label>✈️ From (Origin)</label>
                    <input type="text" name="origin" placeholder="BCN, MAD, LHR" value="<?php echo isset($_POST['origin']) ? $_POST['origin'] : 'BCN'; ?>" required>
                    <small style="color:#666;">BCN=Barcelona, MAD=Madrid, AGP=Malaga</small>
                </div>
                <div class="form-group">
                    <label>📍 To (Destination)</label>
                    <input type="text" name="destination" placeholder="MAD, BCN, JFK" value="<?php echo isset($_POST['destination']) ? $_POST['destination'] : 'MAD'; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label>📅 Departure Date</label>
                    <input type="date" name="departure_date" value="<?php echo isset($_POST['departure_date']) ? $_POST['departure_date'] : date('Y-m-d', strtotime('+30 days')); ?>" required>
                </div>
                <div class="form-group">
                    <label>👥 Passengers</label>
                    <select name="passengers">
                        <option value="1">1 Adult</option>
                        <option value="2">2 Adults</option>
                        <option value="3">3 Adults</option>
                        <option value="4">4 Adults</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="search_flights" class="search-btn">🔍 Search Real Flights</button>
        </form>
        
        <div style="margin-top: 30px;">
            <?php echo $flightResults; ?>
        </div>
    </div>
    
    <!-- Domestic Flights Section -->
    <div class="card">
        <h2>🇪🇸 Domestic Flights (Spain)</h2>
        <div class="route-grid">
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Madrid (MAD)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 1h 25m | Direct</div>
                <div class="route-detail"><i class="fas fa-building"></i> Iberia, Vueling, Air Europa</div>
                <div class="route-price">From €59</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to MAD flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Malaga (AGP)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 1h 45m | Direct</div>
                <div class="route-detail"><i class="fas fa-building"></i> Vueling, Ryanair</div>
                <div class="route-price">From €49</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to AGP flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Seville (SVQ)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 1h 50m | Direct</div>
                <div class="route-detail"><i class="fas fa-building"></i> Vueling, Ryanair</div>
                <div class="route-price">From €55</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to SVQ flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Palma (PMI)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 55m | Direct</div>
                <div class="route-detail"><i class="fas fa-building"></i> Vueling, Air Europa</div>
                <div class="route-price">From €35</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to PMI flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
        </div>
    </div>
    
    <!-- International Flights Section -->
    <div class="card">
        <h2>🌍 International Flights</h2>
        <div class="route-grid">
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → London (LHR)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 2h 15m | Direct</div>
                <div class="route-detail"><i class="fas fa-building"></i> British Airways, Vueling</div>
                <div class="route-price">From €79</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to London flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Paris (CDG)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 1h 50m | Direct</div>
                <div class="route-detail"><i class="fas fa-building"></i> Air France, Vueling</div>
                <div class="route-price">From €69</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to Paris flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Rome (FCO)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 1h 40m | Direct</div>
                <div class="route-detail"><i class="fas fa-building"></i> ITA Airways, Ryanair</div>
                <div class="route-price">From €75</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to Rome flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Amsterdam (AMS)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 2h 10m | Direct</div>
                <div class="route-detail"><i class="fas fa-building"></i> KLM, Vueling</div>
                <div class="route-price">From €89</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to Amsterdam flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
        </div>
    </div>
    
    <!-- Pakistan Flights Section -->
    <div class="card">
        <h2>🇵🇰 Pakistan Flights (Special Deals)</h2>
        <div class="route-grid">
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Lahore (LHE)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 1 stop | Etihad, Qatar, Turkish</div>
                <div class="route-detail"><i class="fas fa-suitcase"></i> 40kg Check-in Baggage</div>
                <div class="route-price">€580</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to Lahore flight €580" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Islamabad (ISB)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 1 stop | Etihad, Qatar, Turkish</div>
                <div class="route-detail"><i class="fas fa-suitcase"></i> 40kg Check-in Baggage</div>
                <div class="route-price">€585</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to Islamabad flight €585" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
            <div class="route-card">
                <div class="route-title">Barcelona (BCN) → Karachi (KHI)</div>
                <div class="route-detail"><i class="fas fa-clock"></i> 1 stop | Multiple airlines</div>
                <div class="route-detail"><i class="fas fa-suitcase"></i> 40kg Check-in Baggage</div>
                <div class="route-price">From €599</div>
                <a href="https://wa.me/34611473217?text=I want to book BCN to Karachi flight" class="book-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
            </div>
        </div>
    </div>
    
    <!-- Services Section -->
    <div class="card">
        <h2>⭐ Our Premium Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-passport"></i></div>
                <h3>Visa Processing</h3>
                <p>Expert visa processing for Umrah, Hajj & worldwide travel</p>
            </div>
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-kaaba"></i></div>
                <h3>Umrah Packages</h3>
                <p>Complete spiritual journeys with luxury accommodations</p>
            </div>
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-hotel"></i></div>
                <h3>Luxury Hotels</h3>
                <p>5-star hotel reservations worldwide</p>
            </div>
            <div class="service-card">
                <div class="service-icon"><i class="fas fa-mosque"></i></div>
                <h3>Hajj 2026</h3>
                <p>Phase 2 bookings opening soon</p>
            </div>
        </div>
    </div>
    
    <!-- Contact Section -->
    <div class="contact-bar">
        <h3 style="color: #1a237e; margin-bottom: 15px;">📞 Contact Us for Instant Booking</h3>
        <div>
            <a href="tel:+34632234216" class="contact-btn"><i class="fas fa-phone"></i> Call +34-632234216</a>
            <a href="https://wa.me/34611473217" class="contact-btn whatsapp"><i class="fab fa-whatsapp"></i> WhatsApp +34-611473217</a>
        </div>
        <p style="margin-top: 15px; color: #555;">
            ✈️ Best Price Guarantee | Instant Confirmation | 24/7 Support
        </p>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div class="footer-content">
            <div>
                <h3 style="margin-bottom: 15px;">Mustafa Travels</h3>
                <p>Rambla Badal 141 Local 1 Bajo</p>
                <p>Barcelona 08028, Spain</p>
                <p>+34-632234216</p>
            </div>
            <div>
                <h3 style="margin-bottom: 15px;">Quick Links</h3>
                <p><a href="#">Home</a></p>
                <p><a href="#">Umrah Packages</a></p>
                <p><a href="#">Hajj 2026</a></p>
                <p><a href="#">Flight Deals</a></p>
            </div>
            <div>
                <h3 style="margin-bottom: 15px;">Services</h3>
                <p>Flight Booking</p>
                <p>Visa Services</p>
                <p>Hotel Reservation</p>
                <p>Umrah Packages</p>
            </div>
            <div>
                <h3 style="margin-bottom: 15px;">Business Hours</h3>
                <p>Mon-Thu: 10:30 - 20:30</p>
                <p>Fri: 10:30-13:00 & 15:00-20:30</p>
                <p>Sat: 10:30 - 19:30</p>
                <p>Sun: Closed</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Crafted with ❤️ for spiritual journeys</p>
        </div>
    </div>
</div>
</body>
</html>
