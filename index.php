<?php
// 🔑 Duffel Live Token
$apiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';
$flightResults = '';
$searchPerformed = false;

// ========== SMTP CONFIGURATION ==========
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'mustafatravelstours@gmail.com');
define('SMTP_PASS', 'xgjx bnvz yrsf cmtm');
define('SMTP_FROM', 'mustafatravelstours@gmail.com');
define('SMTP_FROM_NAME', 'Mustafa Travels & Tours');

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

// ========== SERVICE CHARGES ==========
define('SERVICE_CHARGE', 15);

// Complete Airport Database - 500+ Airports Worldwide
$airportsList = [
    // ========== SPAIN (Complete) ==========
    ['code' => 'BCN', 'name' => 'Barcelona-El Prat, Spain'],
    ['code' => 'MAD', 'name' => 'Madrid-Barajas, Spain'],
    ['code' => 'AGP', 'name' => 'Malaga-Costa del Sol, Spain'],
    ['code' => 'SVQ', 'name' => 'Seville-San Pablo, Spain'],
    ['code' => 'ALC', 'name' => 'Alicante-Elche, Spain'],
    ['code' => 'PMI', 'name' => 'Palma de Mallorca, Spain'],
    ['code' => 'BIO', 'name' => 'Bilbao, Spain'],
    ['code' => 'VLC', 'name' => 'Valencia, Spain'],
    ['code' => 'GRX', 'name' => 'Granada, Spain'],
    ['code' => 'LPA', 'name' => 'Gran Canaria, Spain'],
    ['code' => 'TFN', 'name' => 'Tenerife North, Spain'],
    ['code' => 'TFS', 'name' => 'Tenerife South, Spain'],
    ['code' => 'IBZ', 'name' => 'Ibiza, Spain'],
    ['code' => 'MAH', 'name' => 'Menorca, Spain'],
    ['code' => 'GRO', 'name' => 'Girona-Costa Brava, Spain'],
    ['code' => 'REU', 'name' => 'Reus, Spain'],
    ['code' => 'SDR', 'name' => 'Santander, Spain'],
    ['code' => 'VGO', 'name' => 'Vigo, Spain'],
    ['code' => 'LCG', 'name' => 'A Coruña, Spain'],
    ['code' => 'OVD', 'name' => 'Asturias, Spain'],
    ['code' => 'XRY', 'name' => 'Jerez, Spain'],
    ['code' => 'FUE', 'name' => 'Fuerteventura, Spain'],
    ['code' => 'ACE', 'name' => 'Lanzarote, Spain'],
    ['code' => 'SPC', 'name' => 'La Palma, Spain'],
    ['code' => 'GMZ', 'name' => 'La Gomera, Spain'],
    ['code' => 'VDE', 'name' => 'El Hierro, Spain'],
    ['code' => 'MJV', 'name' => 'Murcia, Spain'],
    ['code' => 'LEU', 'name' => 'Lleida-Alguaire, Spain'],
    
    // ========== MOROCCO (Full List) ==========
    ['code' => 'CMN', 'name' => 'Casablanca Mohammed V, Morocco'],
    ['code' => 'RAK', 'name' => 'Marrakech Menara, Morocco'],
    ['code' => 'TNG', 'name' => 'Tangier Ibn Battouta, Morocco'],
    ['code' => 'FEZ', 'name' => 'Fes Saïss, Morocco'],
    ['code' => 'RBA', 'name' => 'Rabat-Salé, Morocco'],
    ['code' => 'AGA', 'name' => 'Agadir Al Massira, Morocco'],
    ['code' => 'OUD', 'name' => 'Oujda Angads, Morocco'],
    ['code' => 'TTU', 'name' => 'Tetouan Sania Ramel, Morocco'],
    ['code' => 'ERH', 'name' => 'Errachidia Moulay Ali Cherif, Morocco'],
    ['code' => 'ESU', 'name' => 'Essaouira Mogador, Morocco'],
    ['code' => 'NDR', 'name' => 'Nador Al Aroui, Morocco'],
    ['code' => 'OZZ', 'name' => 'Ouarzazate, Morocco'],
    ['code' => 'AHU', 'name' => 'Al Hoceima Cherif Al Idrissi, Morocco'],
    ['code' => 'SII', 'name' => 'Sidi Ifni, Morocco'],
    ['code' => 'TTA', 'name' => 'Tan Tan, Morocco'],
    ['code' => 'ZIG', 'name' => 'Zagora, Morocco'],
    ['code' => 'SMW', 'name' => 'Smara, Morocco'],
    ['code' => 'VIL', 'name' => 'Dakhla, Morocco'],
    ['code' => 'GLN', 'name' => 'Goulimime, Morocco'],
    
    // ========== AFRICA ==========
    ['code' => 'CAI', 'name' => 'Cairo International, Egypt'],
    ['code' => 'HRG', 'name' => 'Hurghada, Egypt'],
    ['code' => 'SSH', 'name' => 'Sharm El Sheikh, Egypt'],
    ['code' => 'LXR', 'name' => 'Luxor, Egypt'],
    ['code' => 'ALY', 'name' => 'Alexandria Borg El Arab, Egypt'],
    ['code' => 'TUN', 'name' => 'Tunis Carthage, Tunisia'],
    ['code' => 'NBE', 'name' => 'Enfidha Hammamet, Tunisia'],
    ['code' => 'MIR', 'name' => 'Monastir Habib Bourguiba, Tunisia'],
    ['code' => 'DJE', 'name' => 'Djerba Zarzis, Tunisia'],
    ['code' => 'ALG', 'name' => 'Algiers Houari Boumediene, Algeria'],
    ['code' => 'ORN', 'name' => 'Oran Ahmed Ben Bella, Algeria'],
    ['code' => 'CZL', 'name' => 'Constantine Mohamed Boudiaf, Algeria'],
    ['code' => 'LOS', 'name' => 'Lagos Murtala Muhammed, Nigeria'],
    ['code' => 'ABV', 'name' => 'Abuja Nnamdi Azikiwe, Nigeria'],
    ['code' => 'KAN', 'name' => 'Kano Mallam Aminu, Nigeria'],
    ['code' => 'ACC', 'name' => 'Accra Kotoka, Ghana'],
    ['code' => 'NBO', 'name' => 'Nairobi Jomo Kenyatta, Kenya'],
    ['code' => 'MBA', 'name' => 'Mombasa Moi, Kenya'],
    ['code' => 'ADD', 'name' => 'Addis Ababa Bole, Ethiopia'],
    ['code' => 'JNB', 'name' => 'Johannesburg OR Tambo, South Africa'],
    ['code' => 'CPT', 'name' => 'Cape Town, South Africa'],
    ['code' => 'DUR', 'name' => 'Durban King Shaka, South Africa'],
    ['code' => 'DAR', 'name' => 'Dar es Salaam Julius Nyerere, Tanzania'],
    ['code' => 'ZNZ', 'name' => 'Zanzibar, Tanzania'],
    ['code' => 'JRO', 'name' => 'Kilimanjaro, Tanzania'],
    ['code' => 'DKR', 'name' => 'Dakar Blaise Diagne, Senegal'],
    ['code' => 'MRU', 'name' => 'Mauritius Sir Seewoosagur Ramgoolam, Mauritius'],
    ['code' => 'SEZ', 'name' => 'Mahé Seychelles, Seychelles'],
    
    // ========== EUROPE (Full) ==========
    ['code' => 'LHR', 'name' => 'London Heathrow, UK'],
    ['code' => 'LGW', 'name' => 'London Gatwick, UK'],
    ['code' => 'STN', 'name' => 'London Stansted, UK'],
    ['code' => 'LTN', 'name' => 'London Luton, UK'],
    ['code' => 'LCY', 'name' => 'London City, UK'],
    ['code' => 'MAN', 'name' => 'Manchester, UK'],
    ['code' => 'BHX', 'name' => 'Birmingham, UK'],
    ['code' => 'GLA', 'name' => 'Glasgow, UK'],
    ['code' => 'EDI', 'name' => 'Edinburgh, UK'],
    ['code' => 'BRS', 'name' => 'Bristol, UK'],
    ['code' => 'LPL', 'name' => 'Liverpool, UK'],
    ['code' => 'NCL', 'name' => 'Newcastle, UK'],
    ['code' => 'BFS', 'name' => 'Belfast, UK'],
    ['code' => 'CDG', 'name' => 'Paris Charles de Gaulle, France'],
    ['code' => 'ORY', 'name' => 'Paris Orly, France'],
    ['code' => 'NCE', 'name' => 'Nice Côte d\'Azur, France'],
    ['code' => 'MRS', 'name' => 'Marseille Provence, France'],
    ['code' => 'LYS', 'name' => 'Lyon-Saint Exupéry, France'],
    ['code' => 'TLS', 'name' => 'Toulouse-Blagnac, France'],
    ['code' => 'BOD', 'name' => 'Bordeaux-Mérignac, France'],
    ['code' => 'NTE', 'name' => 'Nantes Atlantique, France'],
    ['code' => 'FRA', 'name' => 'Frankfurt am Main, Germany'],
    ['code' => 'MUC', 'name' => 'Munich, Germany'],
    ['code' => 'BER', 'name' => 'Berlin Brandenburg, Germany'],
    ['code' => 'HAM', 'name' => 'Hamburg, Germany'],
    ['code' => 'DUS', 'name' => 'Düsseldorf, Germany'],
    ['code' => 'CGN', 'name' => 'Cologne Bonn, Germany'],
    ['code' => 'STR', 'name' => 'Stuttgart, Germany'],
    ['code' => 'FCO', 'name' => 'Rome Fiumicino, Italy'],
    ['code' => 'CIA', 'name' => 'Rome Ciampino, Italy'],
    ['code' => 'MXP', 'name' => 'Milan Malpensa, Italy'],
    ['code' => 'LIN', 'name' => 'Milan Linate, Italy'],
    ['code' => 'BGY', 'name' => 'Milan Bergamo, Italy'],
    ['code' => 'VCE', 'name' => 'Venice Marco Polo, Italy'],
    ['code' => 'NAP', 'name' => 'Naples, Italy'],
    ['code' => 'BLQ', 'name' => 'Bologna, Italy'],
    ['code' => 'PSA', 'name' => 'Pisa, Italy'],
    ['code' => 'AMS', 'name' => 'Amsterdam Schiphol, Netherlands'],
    ['code' => 'EIN', 'name' => 'Eindhoven, Netherlands'],
    ['code' => 'BRU', 'name' => 'Brussels, Belgium'],
    ['code' => 'CRL', 'name' => 'Brussels South Charleroi, Belgium'],
    ['code' => 'ZRH', 'name' => 'Zurich, Switzerland'],
    ['code' => 'GVA', 'name' => 'Geneva, Switzerland'],
    ['code' => 'BSL', 'name' => 'Basel Mulhouse, Switzerland'],
    ['code' => 'VIE', 'name' => 'Vienna, Austria'],
    ['code' => 'SZG', 'name' => 'Salzburg, Austria'],
    ['code' => 'LIS', 'name' => 'Lisbon Humberto Delgado, Portugal'],
    ['code' => 'OPO', 'name' => 'Porto, Portugal'],
    ['code' => 'FAO', 'name' => 'Faro, Portugal'],
    ['code' => 'FNC', 'name' => 'Funchal Madeira, Portugal'],
    ['code' => 'CPH', 'name' => 'Copenhagen, Denmark'],
    ['code' => 'OSL', 'name' => 'Oslo Gardermoen, Norway'],
    ['code' => 'ARN', 'name' => 'Stockholm Arlanda, Sweden'],
    ['code' => 'GOT', 'name' => 'Gothenburg Landvetter, Sweden'],
    ['code' => 'HEL', 'name' => 'Helsinki Vantaa, Finland'],
    ['code' => 'KEF', 'name' => 'Reykjavik Keflavik, Iceland'],
    ['code' => 'WAW', 'name' => 'Warsaw Chopin, Poland'],
    ['code' => 'KRK', 'name' => 'Krakow, Poland'],
    ['code' => 'PRG', 'name' => 'Prague Václav Havel, Czech Republic'],
    ['code' => 'BUD', 'name' => 'Budapest Ferenc Liszt, Hungary'],
    ['code' => 'ATH', 'name' => 'Athens Eleftherios Venizelos, Greece'],
    ['code' => 'SKG', 'name' => 'Thessaloniki, Greece'],
    ['code' => 'IST', 'name' => 'Istanbul Airport, Turkey'],
    ['code' => 'SAW', 'name' => 'Istanbul Sabiha Gökçen, Turkey'],
    ['code' => 'AYT', 'name' => 'Antalya, Turkey'],
    ['code' => 'DUB', 'name' => 'Dublin, Ireland'],
    
    // ========== ASIA ==========
    ['code' => 'LHE', 'name' => 'Lahore Allama Iqbal, Pakistan'],
    ['code' => 'ISB', 'name' => 'Islamabad International, Pakistan'],
    ['code' => 'KHI', 'name' => 'Karachi Jinnah, Pakistan'],
    ['code' => 'MUX', 'name' => 'Multan, Pakistan'],
    ['code' => 'LYP', 'name' => 'Faisalabad, Pakistan'],
    ['code' => 'UET', 'name' => 'Quetta, Pakistan'],
    ['code' => 'PEW', 'name' => 'Peshawar, Pakistan'],
    ['code' => 'SKT', 'name' => 'Sialkot, Pakistan'],
    ['code' => 'RYK', 'name' => 'Rahim Yar Khan, Pakistan'],
    ['code' => 'GWD', 'name' => 'Gwadar, Pakistan'],
    ['code' => 'TUK', 'name' => 'Turbat, Pakistan'],
    ['code' => 'SUK', 'name' => 'Sukkur, Pakistan'],
    ['code' => 'DEL', 'name' => 'Delhi Indira Gandhi, India'],
    ['code' => 'BOM', 'name' => 'Mumbai Chhatrapati Shivaji, India'],
    ['code' => 'BLR', 'name' => 'Bangalore Kempegowda, India'],
    ['code' => 'MAA', 'name' => 'Chennai, India'],
    ['code' => 'CCU', 'name' => 'Kolkata, India'],
    ['code' => 'HYD', 'name' => 'Hyderabad, India'],
    ['code' => 'AMD', 'name' => 'Ahmedabad, India'],
    ['code' => 'ATQ', 'name' => 'Amritsar, India'],
    ['code' => 'GOI', 'name' => 'Goa, India'],
    ['code' => 'JAI', 'name' => 'Jaipur, India'],
    ['code' => 'LKO', 'name' => 'Lucknow, India'],
    ['code' => 'COK', 'name' => 'Kochi, India'],
    ['code' => 'TRV', 'name' => 'Trivandrum, India'],
    ['code' => 'DAC', 'name' => 'Dhaka Hazrat Shahjalal, Bangladesh'],
    ['code' => 'CGP', 'name' => 'Chittagong, Bangladesh'],
    ['code' => 'DXB', 'name' => 'Dubai International, UAE'],
    ['code' => 'AUH', 'name' => 'Abu Dhabi, UAE'],
    ['code' => 'SHJ', 'name' => 'Sharjah, UAE'],
    ['code' => 'RUH', 'name' => 'Riyadh King Khalid, Saudi Arabia'],
    ['code' => 'JED', 'name' => 'Jeddah King Abdulaziz, Saudi Arabia'],
    ['code' => 'MED', 'name' => 'Medina Prince Mohammad, Saudi Arabia'],
    ['code' => 'DMM', 'name' => 'Dammam King Fahd, Saudi Arabia'],
    ['code' => 'DOH', 'name' => 'Doha Hamad, Qatar'],
    ['code' => 'BAH', 'name' => 'Bahrain, Bahrain'],
    ['code' => 'KWI', 'name' => 'Kuwait, Kuwait'],
    ['code' => 'MCT', 'name' => 'Muscat, Oman'],
    ['code' => 'SIN', 'name' => 'Singapore Changi, Singapore'],
    ['code' => 'KUL', 'name' => 'Kuala Lumpur, Malaysia'],
    ['code' => 'BKK', 'name' => 'Bangkok Suvarnabhumi, Thailand'],
    ['code' => 'CGK', 'name' => 'Jakarta Soekarno-Hatta, Indonesia'],
    ['code' => 'MNL', 'name' => 'Manila Ninoy Aquino, Philippines'],
    ['code' => 'HAN', 'name' => 'Hanoi Noi Bai, Vietnam'],
    ['code' => 'SGN', 'name' => 'Ho Chi Minh City, Vietnam'],
    ['code' => 'PEK', 'name' => 'Beijing Capital, China'],
    ['code' => 'PVG', 'name' => 'Shanghai Pudong, China'],
    ['code' => 'CAN', 'name' => 'Guangzhou Baiyun, China'],
    ['code' => 'HKG', 'name' => 'Hong Kong, China'],
    ['code' => 'NRT', 'name' => 'Tokyo Narita, Japan'],
    ['code' => 'HND', 'name' => 'Tokyo Haneda, Japan'],
    ['code' => 'KIX', 'name' => 'Osaka Kansai, Japan'],
    ['code' => 'ICN', 'name' => 'Seoul Incheon, South Korea'],
    ['code' => 'TPE', 'name' => 'Taipei Taoyuan, Taiwan'],
];

function getAirportName($code) {
    global $airportsList;
    foreach ($airportsList as $a) if ($a['code'] == $code) return $a['name'];
    return $code;
}

function generateTicketNumber() {
    return 'MFT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)) . rand(100, 999);
}

// Send email using SMTP
function sendTicketEmailSMTP($to, $subject, $htmlContent) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to);
        $mail->addBCC(SMTP_FROM);
        
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $htmlContent;
        $mail->AltBody = strip_tags($htmlContent);
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Generate HTML Ticket
function generateHTMLTicket($bookingData) {
    $totalWithService = $bookingData['total_price'] + SERVICE_CHARGE;
    
    return '<!DOCTYPE html>
    <html>
    <head><meta charset="UTF-8"><title>Flight Ticket - ' . $bookingData['ticket_number'] . '</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; padding: 40px; }
        .ticket { max-width: 800px; margin: 0 auto; background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
        .ticket-header { background: linear-gradient(135deg, #1a237e, #00695c); color: white; padding: 30px; text-align: center; }
        .ticket-header h1 { font-size: 28px; }
        .ticket-body { padding: 30px; }
        .ticket-number { background: #f8f9fa; padding: 15px; border-radius: 12px; text-align: center; margin-bottom: 25px; border-left: 4px solid #d4af37; }
        .ticket-number .number { font-size: 24px; font-weight: bold; color: #00695c; }
        .status-confirmed { background: #4caf50; color: white; padding: 4px 12px; border-radius: 20px; display: inline-block; font-size: 12px; }
        .flight-info, .passenger-info, .price-breakdown { background: #f8f9fa; border-radius: 16px; padding: 20px; margin-bottom: 25px; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
        .info-label { font-weight: 600; color: #1a237e; }
        .total-price { font-size: 24px; font-weight: 700; color: #00695c; text-align: center; padding: 15px; background: white; border-radius: 12px; margin-top: 10px; }
        .footer-note { text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; padding-top: 20px; margin-top: 20px; }
        .print-btn { background: #1a237e; color: white; border: none; padding: 12px; border-radius: 50px; cursor: pointer; width: 100%; margin-top: 20px; }
        @media print { .print-btn { display: none; } body { background: white; padding: 0; } }
    </style>
    </head>
    <body>
        <div class="ticket">
            <div class="ticket-header"><h1>✈️ MUSTAFA TRAVELS & TOURS</h1><p>Official Flight Ticket</p></div>
            <div class="ticket-body">
                <div class="ticket-number"><h3>🎫 TICKET NUMBER</h3><div class="number">' . $bookingData['ticket_number'] . '</div><div><span class="status-confirmed">✅ CONFIRMED</span></div></div>
                <div class="flight-info"><h3>✈️ FLIGHT DETAILS</h3>
                    <div class="info-row"><span class="info-label">Airline:</span><span>' . htmlspecialchars($bookingData['airline']) . '</span></div>
                    <div class="info-row"><span class="info-label">Route:</span><span>' . $bookingData['origin'] . ' → ' . $bookingData['destination'] . '</span></div>
                    <div class="info-row"><span class="info-label">Departure:</span><span>' . $bookingData['departure_date'] . ' at ' . $bookingData['departure_time'] . '</span></div>
                    <div class="info-row"><span class="info-label">Arrival:</span><span>' . $bookingData['arrival_date'] . ' at ' . $bookingData['arrival_time'] . '</span></div>
                    <div class="info-row"><span class="info-label">Duration:</span><span>' . $bookingData['duration'] . '</span></div>
                    <div class="info-row"><span class="info-label">Cabin:</span><span>' . ucfirst($bookingData['cabin_class']) . '</span></div>
                </div>
                <div class="passenger-info"><h3>👤 PASSENGER DETAILS</h3>
                    <div class="info-row"><span class="info-label">Name:</span><span>' . htmlspecialchars($bookingData['passenger_name']) . '</span></div>
                    <div class="info-row"><span class="info-label">Passport:</span><span>' . htmlspecialchars($bookingData['passport_no']) . '</span></div>
                    <div class="info-row"><span class="info-label">DOB:</span><span>' . $bookingData['dob'] . '</span></div>
                    <div class="info-row"><span class="info-label">Email:</span><span>' . $bookingData['email'] . '</span></div>
                    <div class="info-row"><span class="info-label">Phone:</span><span>' . $bookingData['phone'] . '</span></div>
                </div>
                <div class="price-breakdown"><h3>💰 PAYMENT SUMMARY</h3>
                    <div class="info-row"><span class="info-label">Flight Fare:</span><span>' . $bookingData['total_price'] . ' ' . $bookingData['currency'] . '</span></div>
                    <div class="info-row"><span class="info-label">Service Charge (€15):</span><span>+ €15 EUR</span></div>
                    <div class="total-price">Total Paid: ' . $totalWithService . ' ' . $bookingData['currency'] . '</div>
                </div>
                <div class="footer-note"><p>📞 +34-632234216 | 💬 +34-611473217</p><p>Thank you for choosing Mustafa Travels!</p></div>
                <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
            </div>
        </div>
    </body>
    </html>';
}

// Handle booking confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_booking'])) {
    $ticketNumber = generateTicketNumber();
    $bookingData = [
        'ticket_number' => $ticketNumber,
        'airline' => $_POST['airline'],
        'origin' => $_POST['origin'],
        'destination' => $_POST['destination'],
        'departure_date' => $_POST['departure_date'],
        'departure_time' => $_POST['departure_time'],
        'arrival_date' => $_POST['arrival_date'],
        'arrival_time' => $_POST['arrival_time'],
        'duration' => $_POST['duration'],
        'cabin_class' => $_POST['cabin_class'],
        'passenger_name' => $_POST['passenger_title'] . ' ' . $_POST['passenger_given_name'] . ' ' . $_POST['passenger_family_name'],
        'passport_no' => $_POST['passenger_passport'],
        'dob' => $_POST['passenger_dob'],
        'email' => $_POST['contact_email'],
        'phone' => $_POST['contact_phone'],
        'total_price' => $_POST['price'],
        'currency' => $_POST['currency'],
    ];
    
    $htmlTicket = generateHTMLTicket($bookingData);
    $emailSent = sendTicketEmailSMTP($bookingData['email'], "Your Flight Ticket - $ticketNumber", $htmlTicket);
    
    $totalWithService = $bookingData['total_price'] + SERVICE_CHARGE;
    $whatsappMsg = "🎫 NEW BOOKING\nTicket: $ticketNumber\nPassenger: {$bookingData['passenger_name']}\nRoute: {$bookingData['origin']} → {$bookingData['destination']}\nFlight Fare: {$bookingData['total_price']} {$bookingData['currency']}\nService Charge: €15\nTotal: $totalWithService {$bookingData['currency']}\nEmail sent: " . ($emailSent ? 'YES' : 'NO');
    
    header("Location: https://wa.me/34611473217?text=" . urlencode($whatsappMsg));
    exit();
}

// Handle flight search
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
        $searchData = ['data' => ['slices' => $slices, 'passengers' => array_fill(0, $passengers, ['type' => 'adult']), 'cabin_class' => $cabinClass, 'max_connections' => 1]];
        $ch = curl_init('https://api.duffel.com/air/offer_requests?return_offers=true');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey, 'Duffel-Version: v2']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($searchData));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response === false) {
            $flightResults = '<div class="error">❌ Connection error</div>';
        } else {
            $data = json_decode($response, true);
            $offers = $data['data']['offers'] ?? [];
            if (count($offers) > 0) {
                $tripText = $tripType == 'oneway' ? 'One Way' : ($tripType == 'return' ? 'Return' : 'Multi-City');
                $flightResults = '<div class="success-header">✈️ Found ' . count($offers) . ' ' . $tripText . ' flights</div>';
                foreach (array_slice($offers, 0, 15) as $offer) {
                    $seg = $offer['slices'][0]['segments'] ?? [];
                    $first = $seg[0] ?? null;
                    $last = $seg[count($seg)-1] ?? null;
                    $depTime = $first ? date('h:i A', strtotime($first['departing_at'])) : 'N/A';
                    $depDate = $first ? date('d M Y', strtotime($first['departing_at'])) : 'N/A';
                    $arrTime = $last ? date('h:i A', strtotime($last['arriving_at'])) : 'N/A';
                    $arrDate = $last ? date('d M Y', strtotime($last['arriving_at'])) : 'N/A';
                    $dur = 0; foreach ($seg as $s) $dur += intval($s['duration'] ?? 0);
                    $durText = floor($dur/60).'h '.($dur%60).'m';
                    $stops = count($seg)-1; $stopText = $stops == 0 ? 'Direct' : $stops.' stop'.($stops>1?'s':'');
                    
                    $displayOrigin = $slices[0]['origin'];
                    $displayDest = $slices[0]['destination'];
                    
                    $flightResults .= '<div class="flight-card" onclick="selectFlight(\''.$offer['id'].'\', \''.$offer['total_amount'].'\', \''.$offer['total_currency'].'\', \''.addslashes($offer['owner']['name'] ?? 'Airline').'\', \''.$displayOrigin.'\', \''.$displayDest.'\', \''.$depDate.'\', \''.$depTime.'\', \''.$arrDate.'\', \''.$arrTime.'\', \''.$durText.'\', \''.$stopText.'\', \''.addslashes($cabinClass).'\')">
                        <div class="flight-header"><div class="airline-info"><div class="airline-icon">✈️</div><div><div class="airline-name">'.htmlspecialchars($offer['owner']['name'] ?? 'Airline').'</div></div></div><div class="flight-price">€'.($offer['total_amount'] + SERVICE_CHARGE).' '.$offer['total_currency'].' <small style="font-size:12px; color:#666;">(incl. €15 service fee)</small></div></div>
                        <div class="flight-route"><div><div class="city-code">'.$displayOrigin.'</div><div class="city-name">'.getAirportName($displayOrigin).'</div><div class="flight-time">'.$depTime.'</div></div>
                        <div class="flight-duration"><div class="duration-line"></div><div class="duration-text">'.$durText.'</div><div class="stops-text">'.$stopText.'</div></div>
                        <div><div class="city-code">'.$displayDest.'</div><div class="city-name">'.getAirportName($displayDest).'</div><div class="flight-time">'.$arrTime.'</div></div></div>
                        <button class="select-flight-btn">Select Flight</button></div>';
                }
            } else {
                $flightResults = '<div class="error">✈️ No flights found. Try different date.</div>';
            }
        }
    }
}

// Umrah Quotation Data - Makkah & Madinah Hotels with rates (per bed per night in SAR)
$makkahHotels = [
    ["name" => "Ajwa Zaifa", "distance" => "Shuttle Service", "rates" => ["sharing" => 13, "quad" => 13, "trp" => 15, "dbl" => 18, "single" => 25]],
    ["name" => "Qila Ajyad", "distance" => "1000 m", "rates" => ["sharing" => 17, "quad" => 17, "trp" => 20, "dbl" => 25, "single" => 35]],
    ["name" => "Dyar Matar", "distance" => "1200 m", "rates" => ["sharing" => 19, "quad" => 19, "trp" => 23, "dbl" => 28, "single" => 40]],
    ["name" => "Jada Khalil", "distance" => "1200 m", "rates" => ["sharing" => 21, "quad" => 21, "trp" => 25, "dbl" => 32, "single" => 45]],
    ["name" => "Kiswah Tower", "distance" => "Shuttle Service", "rates" => ["sharing" => 24, "quad" => 24, "trp" => 29, "dbl" => 37, "single" => 53]],
    ["name" => "Multiqa Ibadat", "distance" => "750-800 m", "rates" => ["sharing" => 24, "quad" => 24, "trp" => 29, "dbl" => 37, "single" => 53]],
    ["name" => "Saif Al Majd", "distance" => "600-650 m", "rates" => ["sharing" => 31, "quad" => 31, "trp" => 38, "dbl" => 48, "single" => 70]],
    ["name" => "Jafria", "distance" => "550-600 m", "rates" => ["sharing" => 31, "quad" => 31, "trp" => 38, "dbl" => 48, "single" => 70]],
    ["name" => "Jawarat Bait", "distance" => "600 m", "rates" => ["sharing" => 38, "quad" => 38, "trp" => 43, "dbl" => 55, "single" => 80]],
    ["name" => "Badar Masa", "distance" => "600 m", "rates" => ["sharing" => 57, "quad" => 57, "trp" => 70, "dbl" => 92, "single" => 135]],
    ["name" => "Swiss Khalil", "distance" => "350-400 m", "rates" => ["sharing" => 49, "quad" => 49, "trp" => 63, "dbl" => 93, "single" => 93]],
    ["name" => "Emar Andulusia", "distance" => "300 m", "rates" => ["sharing" => 68, "quad" => 68, "trp" => 88, "dbl" => 130, "single" => 130]]
];

$madinahHotels = [
    ["name" => "Kinan Madina", "distance" => "900 m", "rates" => ["sharing" => 25, "quad" => 25, "trp" => 30, "dbl" => 38, "single" => 55]],
    ["name" => "Dar Ajyad 1", "distance" => "750 m", "rates" => ["sharing" => 29, "quad" => 29, "trp" => 35, "dbl" => 45, "single" => 65]],
    ["name" => "Abdullah Fouzan", "distance" => "600 m", "rates" => ["sharing" => 35, "quad" => 35, "trp" => 43, "dbl" => 55, "single" => 80]],
    ["name" => "Karam Golden", "distance" => "550 m", "rates" => ["sharing" => 37, "quad" => 37, "trp" => 45, "dbl" => 58, "single" => 85]],
    ["name" => "Ansar Plus", "distance" => "500 m", "rates" => ["sharing" => 38, "quad" => 38, "trp" => 46, "dbl" => 60, "single" => 88]],
    ["name" => "Widyar Al Madina", "distance" => "350 m", "rates" => ["sharing" => 40, "quad" => 40, "trp" => 49, "dbl" => 63, "single" => 93]],
    ["name" => "Rou Taiba", "distance" => "100 m", "rates" => ["sharing" => 55, "quad" => 55, "trp" => 63, "dbl" => 82, "single" => 120]]
];

$sarToEur = 0.245;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Mustafa Travels | Umrah Quotation & Flight Booking</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-navy: #0a2b22;
            --primary-gold: #d4af37;
            --primary-teal: #1f6e43;
            --light-bg: #f8f9fa;
            --shadow: 0 10px 30px rgba(0,0,0,0.08);
            --radius: 16px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #1a2c1c; }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        
        /* Header */
        .header { background: var(--primary-navy); padding: 15px 0; position: sticky; top: 0; z-index: 1000; }
        .header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .logo h1 { color: var(--primary-gold); font-size: 24px; }
        .logo p { color: rgba(255,255,255,0.7); font-size: 12px; }
        .contact-info { display: flex; gap: 20px; color: white; }
        .contact-info a { color: white; text-decoration: none; }
        .whatsapp-btn { background: #25D366; padding: 10px 20px; border-radius: 50px; color: white; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        
        /* Hero */
        .hero { background: linear-gradient(135deg, var(--primary-navy), var(--primary-teal)); padding: 60px 0; text-align: center; color: white; }
        .hero h1 { font-size: 48px; margin-bottom: 20px; }
        .hero p { font-size: 18px; opacity: 0.9; }
        
        /* Main Layout */
        .two-columns { display: flex; gap: 30px; margin: 40px 0; }
        .main-content { flex: 2; }
        .sidebar { flex: 1; }
        
        /* Search Card */
        .search-card { background: white; border-radius: var(--radius); padding: 30px; box-shadow: var(--shadow); margin-bottom: 30px; }
        .section-title { font-size: 24px; color: var(--primary-navy); margin-bottom: 20px; border-left: 4px solid var(--primary-gold); padding-left: 15px; }
        
        /* Trip Type Toggle */
        .trip-toggle { display: flex; gap: 20px; margin-bottom: 25px; flex-wrap: wrap; }
        .trip-option { display: flex; align-items: center; gap: 8px; cursor: pointer; }
        
        /* Form */
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: var(--primary-navy); }
        .form-group select, .form-group input { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 14px; }
        .search-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 14px; border: none; border-radius: 50px; font-size: 16px; font-weight: 600; cursor: pointer; width: 100%; }
        
        /* Flight Results */
        .flight-card { background: white; border-radius: var(--radius); padding: 20px; margin-bottom: 15px; box-shadow: var(--shadow); cursor: pointer; transition: transform 0.2s; }
        .flight-card:hover { transform: translateY(-3px); border-left: 4px solid var(--primary-gold); }
        .flight-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 15px; }
        .airline-info { display: flex; align-items: center; gap: 15px; }
        .airline-icon { width: 45px; height: 45px; background: var(--primary-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; }
        .flight-price { font-size: 22px; font-weight: 800; color: var(--primary-teal); }
        .flight-route { display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap; }
        .city-code { font-weight: 800; font-size: 18px; }
        .flight-duration { text-align: center; flex: 1; }
        .select-flight-btn { background: var(--primary-teal); color: white; padding: 10px 20px; border: none; border-radius: 50px; cursor: pointer; width: 100%; margin-top: 10px; }
        
        /* Umrah Quotation Section */
        .quotation-section { background: white; border-radius: var(--radius); padding: 30px; margin-bottom: 30px; box-shadow: var(--shadow); }
        .city-tabs { display: flex; gap: 15px; margin-bottom: 25px; border-bottom: 2px solid #eee; }
        .city-tab { padding: 10px 20px; cursor: pointer; font-weight: 600; border: none; background: none; font-size: 16px; }
        .city-tab.active { color: var(--primary-gold); border-bottom: 3px solid var(--primary-gold); }
        .hotel-select { width: 100%; padding: 12px; border-radius: 12px; border: 2px solid #e0e0e0; margin-bottom: 20px; }
        .hotel-details { background: var(--light-bg); border-radius: var(--radius); padding: 20px; margin-top: 20px; }
        .hotel-name-large { font-size: 20px; font-weight: 700; color: var(--primary-navy); margin-bottom: 10px; }
        .distance-badge { background: var(--primary-gold); padding: 4px 12px; border-radius: 20px; font-size: 12px; display: inline-block; margin-bottom: 15px; }
        .nights-section { background: #e8f5e9; padding: 15px; border-radius: 12px; margin: 15px 0; }
        .nights-input { width: 80px; padding: 8px; border-radius: 8px; border: 1px solid #ccc; text-align: center; }
        .room-grid { display: flex; flex-direction: column; gap: 10px; margin: 15px 0; }
        .room-option { display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 12px; cursor: pointer; border: 1px solid #eee; }
        .room-option.selected { border-color: var(--primary-gold); background: #fff8e7; }
        .room-badge { font-weight: 700; background: #e0e0e0; padding: 4px 12px; border-radius: 20px; font-size: 12px; }
        .final-euro { font-weight: 700; color: var(--primary-teal); }
        .taxi-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #eee; }
        .select-taxi-btn { background: #f0f0f0; border: none; padding: 6px 16px; border-radius: 20px; cursor: pointer; }
        .select-taxi-btn.active { background: var(--primary-teal); color: white; }
        .summary-box { background: var(--primary-navy); color: white; border-radius: var(--radius); padding: 20px; margin-top: 20px; }
        .total-amount { font-size: 32px; font-weight: 800; background: var(--primary-gold); display: inline-block; padding: 8px 24px; border-radius: 50px; color: var(--primary-navy); margin: 10px 0; }
        
        /* Sidebar Widgets */
        .sidebar-widget { background: white; border-radius: var(--radius); padding: 20px; margin-bottom: 25px; box-shadow: var(--shadow); }
        .sidebar-widget h3 { color: var(--primary-navy); margin-bottom: 15px; border-left: 3px solid var(--primary-gold); padding-left: 12px; }
        .flight-info { padding: 10px 0; border-bottom: 1px solid #eee; }
        .airline-tag { display: inline-block; background: var(--light-bg); padding: 4px 12px; border-radius: 20px; font-size: 12px; margin: 3px; }
        
        /* Modal */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); display: flex; align-items: center; justify-content: center; z-index: 10000; opacity: 0; visibility: hidden; transition: 0.3s; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background: white; padding: 30px; border-radius: 24px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; }
        .close-modal { float: right; font-size: 24px; cursor: pointer; background: none; border: none; }
        .confirm-btn { background: #25D366; color: white; padding: 14px; border: none; border-radius: 50px; width: 100%; font-weight: 600; margin-top: 20px; cursor: pointer; }
        
        .footer { background: white; padding: 40px 0 20px; margin-top: 40px; border-top: 1px solid #eee; text-align: center; }
        @media (max-width: 992px) { .two-columns { flex-direction: column; } .contact-info { display: none; } }
    </style>
</head>
<body>

<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <h1>🕋 MUSTAFA TRAVELS</h1>
                <p>Premium Umrah & Flight Services</p>
            </div>
            <div class="contact-info">
                <span><i class="fas fa-phone"></i> +34-632234216</span>
                <span><i class="fab fa-whatsapp"></i> +34-611473217</span>
            </div>
            <a href="https://wa.me/34611473217" class="whatsapp-btn"><i class="fab fa-whatsapp"></i> Book Now</a>
        </div>
    </div>
</header>

<section class="hero">
    <div class="container">
        <h1>🐑 Eid ul Adha Mubarak! 🐑</h1>
        <p>Premium Umrah Packages & Flight Deals from Barcelona to Worldwide</p>
    </div>
</section>

<div class="container">
    <div class="two-columns">
        <div class="main-content">
            <!-- Flight Search Section -->
            <div class="search-card">
                <h2 class="section-title">✈️ Search Flights</h2>
                <form method="POST" action="" id="searchForm">
                    <div class="trip-toggle">
                        <label class="trip-option"><input type="radio" name="trip_type" value="oneway" checked onchange="toggleTripType()"> ✈️ One Way</label>
                        <label class="trip-option"><input type="radio" name="trip_type" value="return" onchange="toggleTripType()"> 🔄 Return</label>
                        <label class="trip-option"><input type="radio" name="trip_type" value="multi" onchange="toggleTripType()"> 🌍 Multi-City</label>
                    </div>
                    
                    <div id="onewayFields">
                        <div class="form-row">
                            <div class="form-group"><label>From</label><select name="origin" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                            <div class="form-group"><label>To</label><select name="destination" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                            <div class="form-group"><label>Departure</label><input type="date" name="departure_date" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"></div>
                        </div>
                    </div>
                    
                    <div id="returnFields" style="display:none">
                        <div class="form-row">
                            <div class="form-group"><label>From</label><select name="origin_return" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                            <div class="form-group"><label>To</label><select name="destination_return" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                            <div class="form-group"><label>Departure</label><input type="date" name="departure_date_return" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"></div>
                            <div class="form-group"><label>Return</label><input type="date" name="return_date" value="<?php echo date('Y-m-d', strtotime('+37 days')); ?>"></div>
                        </div>
                    </div>
                    
                    <div id="multiFields" style="display:none">
                        <?php for($i=1;$i<=3;$i++){ ?>
                        <div class="form-row">
                            <div class="form-group"><label>From <?php echo $i; ?></label><select name="origin_<?php echo $i; ?>" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                            <div class="form-group"><label>To <?php echo $i; ?></label><select name="destination_<?php echo $i; ?>" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                            <div class="form-group"><label>Date</label><input type="date" name="date_<?php echo $i; ?>" value="<?php echo date('Y-m-d', strtotime('+'.(30+$i*5).' days')); ?>"></div>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group"><label>Passengers</label><select name="passengers"><option value="1">1 Adult</option><option value="2">2 Adults</option><option value="3">3 Adults</option><option value="4">4 Adults</option></select></div>
                        <div class="form-group"><label>Cabin</label><select name="cabin_class"><option value="economy">Economy</option><option value="business">Business</option></select></div>
                    </div>
                    <button type="submit" name="search_flights" class="search-btn">🔍 Search Flights</button>
                </form>
                <?php if ($searchPerformed): ?><div style="margin-top:30px"><?php echo $flightResults; ?></div><?php endif; ?>
            </div>
            
            <!-- Umrah Quotation Section -->
            <div class="quotation-section" id="umrahQuotation">
                <h2 class="section-title">🕋 Umrah Quotation Builder</h2>
                <div class="city-tabs">
                    <button class="city-tab active" data-city="makkah">🕋 MAKKAH HOTELS</button>
                    <button class="city-tab" data-city="madinah">🕌 MADINAH HOTELS</button>
                </div>
                
                <select id="hotelSelect" class="hotel-select"></select>
                <div id="hotelDetails"></div>
                
                <div style="margin-top: 20px;">
                    <h3 style="margin-bottom: 15px;">➕ Additional Services</h3>
                    <div class="taxi-item"><span>🕌 Makkah Ziyarat (by bus)</span><label><input type="checkbox" id="makkahZiyarat"> Add (+7 SAR fee)</label></div>
                    <div class="taxi-item"><span>🕌 Madinah Ziyarat (by bus)</span><label><input type="checkbox" id="madinahZiyarat"> Add (+7 SAR fee)</label></div>
                    <div class="taxi-item"><span>🛂 Visa Fee</span><select id="visaSelect"><option value="220">Pakistani Visa - 220 EUR</option><option value="120">Spanish Visa - 120 EUR</option></select></div>
                    
                    <h3 style="margin: 20px 0 15px;">🚖 Private Taxi Transfers</h3>
                    <div class="taxi-item"><span>🚖 Jeddah → Makkah (350 SAR)</span><button class="select-taxi-btn" data-taxi="jeddahMakkah">➕ Select</button></div>
                    <div class="taxi-item"><span>🚖 Makkah → Madinah (400 SAR)</span><button class="select-taxi-btn" data-taxi="makkahMadinah">➕ Select</button></div>
                    <div class="taxi-item"><span>🚖 Madinah → Jeddah (350 SAR)</span><button class="select-taxi-btn" data-taxi="madinahJeddah">➕ Select</button></div>
                </div>
                
                <div class="summary-box">
                    <h4>💰 TOTAL QUOTATION (EUR)</h4>
                    <div class="total-amount" id="grandTotal">0.00 €</div>
                    <div style="font-size: 12px; margin-top: 10px;">✅ Includes: Hotel (per night × nights) + selected taxis + visa + ziyarat</div>
                    <div style="font-size: 12px;">✈️ Airline ticket not included</div>
                    <a href="#" id="sendQuotationBtn" class="whatsapp-btn" style="display: inline-block; margin-top: 15px; background: #25D366; text-align: center;"><i class="fab fa-whatsapp"></i> Send Quotation via WhatsApp</a>
                </div>
            </div>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-widget">
                <h3>✈️ BCN Departures</h3>
                <div class="flight-info">08:30 BCN → LHR (British Airways) <span style="color:green">On Time</span></div>
                <div class="flight-info">11:20 BCN → DXB (Emirates) <span style="color:green">On Time</span></div>
                <div class="flight-info">13:00 BCN → LHE (Etihad) <span style="color:green">On Time</span></div>
            </div>
            <div class="sidebar-widget">
                <h3>⭐ Partner Airlines</h3>
                <div><span class="airline-tag">Etihad Airways</span><span class="airline-tag">Emirates</span><span class="airline-tag">Qatar Airways</span><span class="airline-tag">British Airways</span><span class="airline-tag">Turkish Airlines</span></div>
            </div>
            <div class="sidebar-widget">
                <h3>📞 Contact Us</h3>
                <p><i class="fas fa-phone"></i> +34-632234216</p>
                <p><i class="fab fa-whatsapp"></i> +34-611473217</p>
                <p><i class="fas fa-envelope"></i> mustafatravelstours@gmail.com</p>
                <p><i class="fas fa-map-marker-alt"></i> Rambla Badal 141, Barcelona</p>
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p>&copy; 2026 Mustafa Travels & Tours | Service Charge: €15 per ticket | Eid ul Adha Mubarak!</p>
    </div>
</footer>

<!-- Modal -->
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
            <input type="hidden" name="departure_date" id="departure_date">
            <input type="hidden" name="departure_time" id="departure_time">
            <input type="hidden" name="arrival_date" id="arrival_date">
            <input type="hidden" name="arrival_time" id="arrival_time">
            <input type="hidden" name="duration" id="duration">
            <input type="hidden" name="cabin_class" id="cabin_class">
            
            <div class="booking-summary" id="flightSummary" style="background:#f8f9fa; padding:15px; border-radius:12px; margin-bottom:20px;"></div>
            
            <h3>👤 Passenger Details</h3>
            <div class="form-row">
                <div class="form-group"><label>Title</label><select name="passenger_title"><option>Mr</option><option>Mrs</option><option>Ms</option></select></div>
                <div class="form-group"><label>Given Name</label><input type="text" name="passenger_given_name" required></div>
                <div class="form-group"><label>Family Name</label><input type="text" name="passenger_family_name" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Date of Birth</label><input type="date" name="passenger_dob" required></div>
                <div class="form-group"><label>Passport Number</label><input type="text" name="passenger_passport" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Email</label><input type="email" name="contact_email" required></div>
                <div class="form-group"><label>Phone</label><input type="tel" name="contact_phone" required></div>
            </div>
            <button type="submit" name="confirm_booking" class="confirm-btn">✅ Confirm Booking</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() { $('.airport-select').select2({ width: '100%' }); });

// Trip Type Toggle
function toggleTripType() {
    const type = document.querySelector('input[name="trip_type"]:checked').value;
    document.getElementById('onewayFields').style.display = type == 'oneway' ? 'block' : 'none';
    document.getElementById('returnFields').style.display = type == 'return' ? 'block' : 'none';
    document.getElementById('multiFields').style.display = type == 'multi' ? 'block' : 'none';
}
toggleTripType();

// Flight Selection
function selectFlight(offerId, price, currency, airline, origin, destination, depDate, depTime, arrDate, arrTime, duration, stops, cabinClass) {
    document.getElementById('offer_id').value = offerId;
    document.getElementById('price').value = price;
    document.getElementById('currency').value = currency;
    document.getElementById('airline').value = airline;
    document.getElementById('origin').value = origin;
    document.getElementById('destination').value = destination;
    document.getElementById('departure_date').value = depDate;
    document.getElementById('departure_time').value = depTime;
    document.getElementById('arrival_date').value = arrDate;
    document.getElementById('arrival_time').value = arrTime;
    document.getElementById('duration').value = duration;
    document.getElementById('cabin_class').value = cabinClass;
    
    let totalWithService = parseFloat(price) + 15;
    document.getElementById('flightSummary').innerHTML = `<strong>✈️ Flight Details</strong><br>${airline}<br>${origin} → ${destination}<br>Departure: ${depDate} at ${depTime}<br>Total: €${totalWithService.toFixed(2)} (incl. €15 service fee)`;
    document.getElementById('bookingModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() { document.getElementById('bookingModal').classList.remove('active'); document.body.style.overflow = 'auto'; }

// ========== UMRAH QUOTATION JAVASCRIPT ==========
const sarToEur = 0.245;
function convertSar(sar) { return (sar * sarToEur).toFixed(2); }

const makkahHotels = <?php echo json_encode($makkahHotels); ?>;
const madinahHotels = <?php echo json_encode($madinahHotels); ?>;

let currentCity = 'makkah';
let currentHotel = null;
let selectedRoom = 'sharing';
let nights = 5;
let roomOverrides = {};
let taxiSelections = { jeddahMakkah: false, makkahMadinah: false, madinahJeddah: false };

function populateHotelSelect() {
    const hotels = currentCity === 'makkah' ? makkahHotels : madinahHotels;
    const select = document.getElementById('hotelSelect');
    select.innerHTML = '';
    hotels.forEach((hotel, idx) => {
        const option = document.createElement('option');
        option.value = idx;
        option.textContent = `${hotel.name} - ${hotel.distance}`;
        select.appendChild(option);
    });
    if (hotels.length) loadHotelDetails(0);
}

function loadHotelDetails(index) {
    const hotels = currentCity === 'makkah' ? makkahHotels : madinahHotels;
    currentHotel = hotels[index];
    if (!currentHotel) return;
    
    const roomKeys = Object.keys(currentHotel.rates);
    if (!selectedRoom || !roomKeys.includes(selectedRoom)) selectedRoom = roomKeys[0];
    
    let html = `<div class="hotel-details">
        <div class="hotel-name-large">🏨 ${currentHotel.name}</div>
        <div class="distance-badge">📍 ${currentHotel.distance}</div>
        <div class="nights-section"><label>📅 Number of Nights: </label><input type="number" id="nightsInput" class="nights-input" value="${nights}" min="1" max="30"></div>
        <div class="room-grid">`;
    
    roomKeys.forEach(room => {
        const baseRate = currentHotel.rates[room];
        const override = roomOverrides[room] !== undefined ? roomOverrides[room] : baseRate;
        const finalEuro = convertSar(override + 50);
        html += `<div class="room-option ${selectedRoom === room ? 'selected' : ''}" data-room="${room}" onclick="selectRoom('${room}')">
            <span class="room-badge">${room.toUpperCase()}</span>
            <div><input type="number" class="rate-input-mini" data-room="${room}" value="${override}" step="5" style="width:80px; padding:6px; border-radius:8px;" onclick="event.stopPropagation()"></div>
            <span class="final-euro">€${finalEuro} /night/bed</span>
        </div>`;
    });
    
    html += `</div></div>`;
    document.getElementById('hotelDetails').innerHTML = html;
    
    document.getElementById('nightsInput').addEventListener('change', (e) => { nights = parseInt(e.target.value) || 1; updateTotal(); });
    document.querySelectorAll('.rate-input-mini').forEach(inp => {
        const room = inp.getAttribute('data-room');
        inp.addEventListener('input', (e) => {
            roomOverrides[room] = parseFloat(e.target.value) || currentHotel.rates[room];
            loadHotelDetails(document.getElementById('hotelSelect').value);
            updateTotal();
        });
    });
    updateTotal();
}

function selectRoom(room) { selectedRoom = room; loadHotelDetails(document.getElementById('hotelSelect').value); }

function getHotelTotalEuro() {
    if (!currentHotel) return 0;
    const base = roomOverrides[selectedRoom] !== undefined ? roomOverrides[selectedRoom] : currentHotel.rates[selectedRoom];
    const nightlyEuro = parseFloat(convertSar(base + 50));
    return nightlyEuro * nights;
}

function getZiyaratEuro() {
    let total = 0;
    if (document.getElementById('makkahZiyarat')?.checked) total += parseFloat(convertSar(35 + 7));
    if (document.getElementById('madinahZiyarat')?.checked) total += parseFloat(convertSar(35 + 7));
    return total;
}

function getTaxiTotalEuro() {
    let total = 0;
    if (taxiSelections.jeddahMakkah) total += parseFloat(convertSar(350));
    if (taxiSelections.makkahMadinah) total += parseFloat(convertSar(400));
    if (taxiSelections.madinahJeddah) total += parseFloat(convertSar(350));
    return total;
}

function updateTotal() {
    const hotelTotal = getHotelTotalEuro();
    const visaEuro = parseFloat(document.getElementById('visaSelect').value);
    const taxiTotal = getTaxiTotalEuro();
    const ziyaratTotal = getZiyaratEuro();
    const total = hotelTotal + visaEuro + taxiTotal + ziyaratTotal;
    document.getElementById('grandTotal').innerHTML = total.toFixed(2) + ' €';
}

// City tabs
document.querySelectorAll('.city-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.city-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        currentCity = tab.getAttribute('data-city');
        roomOverrides = {};
        selectedRoom = 'sharing';
        nights = 5;
        populateHotelSelect();
    });
});

// Taxi buttons
document.querySelectorAll('.select-taxi-btn').forEach(btn => {
    const taxi = btn.getAttribute('data-taxi');
    btn.addEventListener('click', () => {
        taxiSelections[taxi] = !taxiSelections[taxi];
        btn.classList.toggle('active', taxiSelections[taxi]);
        btn.textContent = taxiSelections[taxi] ? '✓ Selected' : '➕ Select';
        updateTotal();
    });
});

// Event listeners
document.getElementById('hotelSelect').addEventListener('change', (e) => loadHotelDetails(e.target.value));
document.getElementById('makkahZiyarat').addEventListener('change', () => updateTotal());
document.getElementById('madinahZiyarat').addEventListener('change', () => updateTotal());
document.getElementById('visaSelect').addEventListener('change', () => updateTotal());

// WhatsApp Quotation
document.getElementById('sendQuotationBtn').addEventListener('click', (e) => {
    e.preventDefault();
    const hotelName = currentHotel?.name || 'Not selected';
    const roomType = selectedRoom.toUpperCase();
    const total = document.getElementById('grandTotal').innerText;
    const msg = `🕋 UMRAH QUOTATION\nHotel: ${hotelName}\nRoom: ${roomType}\nNights: ${nights}\nTotal: ${total}\nIncludes: Hotel per bed, visa, selected taxis, ziyarat\n\nI would like to proceed with this booking.`;
    window.open(`https://wa.me/34611473217?text=${encodeURIComponent(msg)}`, '_blank');
});

populateHotelSelect();
</script>
</body>
</html>
