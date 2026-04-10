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
            $flightResults = '<div style="background:#e8f5e9;padding:12px;border-radius:12px;margin-bottom:20px;">✅ Found ' . count($offers) . ' real flights</div>';
            foreach (array_slice($offers, 0, 10) as $offer) {
                $segment = $offer['slices'][0]['segments'][0] ?? null;
                $depTime = $segment ? date('H:i', strtotime($segment['departing_at'])) : 'N/A';
                $arrTime = $segment ? date('H:i', strtotime($segment['arriving_at'])) : 'N/A';
                $flightResults .= '
                <div class="flight-card">
                    <div class="airline">✈️ ' . htmlspecialchars($offer['owner']['name'] ?? 'Airline') . '</div>
                    <div class="route"><span class="city">' . $origin . '</span> <span class="arrow">→</span> <span class="city">' . $destination . '</span></div>
                    <div>Depart: ' . $depTime . ' | Arrive: ' . $arrTime . '</div>
                    <div class="price">💰 ' . $offer['total_amount'] . ' ' . $offer['total_currency'] . '</div>
                    <a href="https://wa.me/34611473217?text=I want to book ' . $origin . ' to ' . $destination . ' for ' . $offer['total_amount'] . ' ' . $offer['total_currency'] . '" class="book-btn">📱 Book on WhatsApp</a>
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
    <title>Mustafa Travels | Flight Search</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #1a237e 0%, #00695c 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: white; font-size: 36px; margin-bottom: 10px; }
        .header p { color: rgba(255,255,255,0.9); }
        .card {
            background: white;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border-top: 5px solid #d4af37;
            margin-bottom: 30px;
        }
        .card h2 { color: #1a237e; margin-bottom: 25px; border-left: 4px solid #d4af37; padding-left: 15px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 600; color: #1a237e; margin-bottom: 8px; }
        input, select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
        }
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
            transition: 0.3s;
        }
        .search-btn:hover { transform: translateY(-3px); }
        .flight-card {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #d4af37;
        }
        .airline { font-weight: 700; font-size: 18px; color: #1a237e; margin-bottom: 12px; }
        .route { display: flex; align-items: center; gap: 20px; margin-bottom: 12px; flex-wrap: wrap; }
        .city { font-weight: 700; font-size: 20px; }
        .arrow { color: #d4af37; font-size: 24px; }
        .price { font-size: 28px; font-weight: 700; color: #00695c; margin: 12px 0; }
        .book-btn {
            background: #25D366; color: white; padding: 10px 20px; border-radius: 50px;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
            font-weight: 600; transition: 0.3s;
        }
        .book-btn:hover { transform: scale(1.05); }
        .error { background: #ffebee; color: #c62828; padding: 15px; border-radius: 12px; margin-bottom: 15px; }
        .live-badge { background: #4caf50; color: white; padding: 4px 12px; border-radius: 50px; font-size: 12px; margin-left: 10px; }
        @media (max-width: 700px) { .row { grid-template-columns: 1fr; } .card { padding: 20px; } }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>✈️ Mustafa Travels & Tours</h1>
        <p>Search Real Flights - Live Duffel API</p>
    </div>
    
    <div class="card">
        <h2>🔍 Flight Search <span class="live-badge">LIVE MODE</span></h2>
        
        <form method="POST" action="">
            <div class="row">
                <div class="form-group">
                    <label>From (Origin)</label>
                    <input type="text" name="origin" placeholder="BCN" value="<?php echo isset($_POST['origin']) ? $_POST['origin'] : 'BCN'; ?>" required>
                </div>
                <div class="form-group">
                    <label>To (Destination)</label>
                    <input type="text" name="destination" placeholder="MAD" value="<?php echo isset($_POST['destination']) ? $_POST['destination'] : 'MAD'; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label>Departure Date</label>
                    <input type="date" name="departure_date" value="<?php echo isset($_POST['departure_date']) ? $_POST['departure_date'] : '2026-05-20'; ?>" required>
                </div>
                <div class="form-group">
                    <label>Passengers</label>
                    <select name="passengers">
                        <option value="1">1 Adult</option>
                        <option value="2">2 Adults</option>
                        <option value="3">3 Adults</option>
                    </select>
                </div>
            </div>
            <button type="submit" name="search_flights" class="search-btn">🔍 Search Real Flights</button>
        </form>
        
        <div style="margin-top: 30px;">
            <?php echo $flightResults; ?>
        </div>
    </div>
    
    <div class="card">
        <h2>📞 Contact Us</h2>
        <div style="text-align: center;">
            <a href="tel:+34632234216" style="background:#00695c; color:white; padding:12px 25px; border-radius:50px; text-decoration:none; display:inline-block; margin:10px;"><i class="fas fa-phone"></i> Call +34-632234216</a>
            <a href="https://wa.me/34611473217" style="background:#25D366; color:white; padding:12px 25px; border-radius:50px; text-decoration:none; display:inline-block; margin:10px;"><i class="fab fa-whatsapp"></i> WhatsApp +34-611473217</a>
        </div>
    </div>
</div>
</body>
</html>
