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
define('SERVICE_CHARGE', 15); // €15 service charge

// Complete Airport Database - Europe, Latin America, Asia, USA, Canada, Middle East
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
    
    // ========== EUROPE (All Countries) ==========
    // United Kingdom
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
    
    // France
    ['code' => 'CDG', 'name' => 'Paris Charles de Gaulle, France'],
    ['code' => 'ORY', 'name' => 'Paris Orly, France'],
    ['code' => 'NCE', 'name' => 'Nice Côte d\'Azur, France'],
    ['code' => 'MRS', 'name' => 'Marseille Provence, France'],
    ['code' => 'LYS', 'name' => 'Lyon-Saint Exupéry, France'],
    ['code' => 'TLS', 'name' => 'Toulouse-Blagnac, France'],
    ['code' => 'BOD', 'name' => 'Bordeaux-Mérignac, France'],
    ['code' => 'NTE', 'name' => 'Nantes Atlantique, France'],
    
    // Germany
    ['code' => 'FRA', 'name' => 'Frankfurt am Main, Germany'],
    ['code' => 'MUC', 'name' => 'Munich, Germany'],
    ['code' => 'BER', 'name' => 'Berlin Brandenburg, Germany'],
    ['code' => 'HAM', 'name' => 'Hamburg, Germany'],
    ['code' => 'DUS', 'name' => 'Düsseldorf, Germany'],
    ['code' => 'CGN', 'name' => 'Cologne Bonn, Germany'],
    ['code' => 'STR', 'name' => 'Stuttgart, Germany'],
    
    // Italy
    ['code' => 'FCO', 'name' => 'Rome Fiumicino, Italy'],
    ['code' => 'CIA', 'name' => 'Rome Ciampino, Italy'],
    ['code' => 'MXP', 'name' => 'Milan Malpensa, Italy'],
    ['code' => 'LIN', 'name' => 'Milan Linate, Italy'],
    ['code' => 'BGY', 'name' => 'Milan Bergamo, Italy'],
    ['code' => 'VCE', 'name' => 'Venice Marco Polo, Italy'],
    ['code' => 'NAP', 'name' => 'Naples, Italy'],
    ['code' => 'BLQ', 'name' => 'Bologna, Italy'],
    ['code' => 'PSA', 'name' => 'Pisa, Italy'],
    ['code' => 'TRN', 'name' => 'Turin, Italy'],
    
    // Netherlands
    ['code' => 'AMS', 'name' => 'Amsterdam Schiphol, Netherlands'],
    ['code' => 'EIN', 'name' => 'Eindhoven, Netherlands'],
    ['code' => 'RTM', 'name' => 'Rotterdam The Hague, Netherlands'],
    
    // Belgium
    ['code' => 'BRU', 'name' => 'Brussels, Belgium'],
    ['code' => 'CRL', 'name' => 'Brussels South Charleroi, Belgium'],
    ['code' => 'ANR', 'name' => 'Antwerp, Belgium'],
    
    // Switzerland
    ['code' => 'ZRH', 'name' => 'Zurich, Switzerland'],
    ['code' => 'GVA', 'name' => 'Geneva, Switzerland'],
    ['code' => 'BSL', 'name' => 'Basel Mulhouse, Switzerland'],
    
    // Austria
    ['code' => 'VIE', 'name' => 'Vienna, Austria'],
    ['code' => 'SZG', 'name' => 'Salzburg, Austria'],
    ['code' => 'INN', 'name' => 'Innsbruck, Austria'],
    
    // Portugal
    ['code' => 'LIS', 'name' => 'Lisbon Humberto Delgado, Portugal'],
    ['code' => 'OPO', 'name' => 'Porto, Portugal'],
    ['code' => 'FAO', 'name' => 'Faro, Portugal'],
    ['code' => 'FNC', 'name' => 'Funchal Madeira, Portugal'],
    ['code' => 'PDL', 'name' => 'Ponta Delgada Azores, Portugal'],
    
    // Scandinavia
    ['code' => 'CPH', 'name' => 'Copenhagen, Denmark'],
    ['code' => 'OSL', 'name' => 'Oslo Gardermoen, Norway'],
    ['code' => 'BGO', 'name' => 'Bergen, Norway'],
    ['code' => 'SVG', 'name' => 'Stavanger, Norway'],
    ['code' => 'TRD', 'name' => 'Trondheim, Norway'],
    ['code' => 'ARN', 'name' => 'Stockholm Arlanda, Sweden'],
    ['code' => 'GOT', 'name' => 'Gothenburg Landvetter, Sweden'],
    ['code' => 'HEL', 'name' => 'Helsinki Vantaa, Finland'],
    ['code' => 'KEF', 'name' => 'Reykjavik Keflavik, Iceland'],
    
    // Eastern Europe
    ['code' => 'WAW', 'name' => 'Warsaw Chopin, Poland'],
    ['code' => 'KRK', 'name' => 'Krakow John Paul II, Poland'],
    ['code' => 'PRG', 'name' => 'Prague Václav Havel, Czech Republic'],
    ['code' => 'BUD', 'name' => 'Budapest Ferenc Liszt, Hungary'],
    ['code' => 'ATH', 'name' => 'Athens Eleftherios Venizelos, Greece'],
    ['code' => 'SKG', 'name' => 'Thessaloniki, Greece'],
    ['code' => 'IST', 'name' => 'Istanbul Airport, Turkey'],
    ['code' => 'SAW', 'name' => 'Istanbul Sabiha Gökçen, Turkey'],
    ['code' => 'AYT', 'name' => 'Antalya, Turkey'],
    
    // Ireland
    ['code' => 'DUB', 'name' => 'Dublin, Ireland'],
    ['code' => 'SNN', 'name' => 'Shannon, Ireland'],
    ['code' => 'ORK', 'name' => 'Cork, Ireland'],
    
    // ========== LATIN AMERICA ==========
    ['code' => 'GRU', 'name' => 'São Paulo Guarulhos, Brazil'],
    ['code' => 'CGH', 'name' => 'São Paulo Congonhas, Brazil'],
    ['code' => 'VCP', 'name' => 'Campinas Viracopos, Brazil'],
    ['code' => 'GIG', 'name' => 'Rio de Janeiro Galeão, Brazil'],
    ['code' => 'SDU', 'name' => 'Rio de Janeiro Santos Dumont, Brazil'],
    ['code' => 'BSB', 'name' => 'Brasília, Brazil'],
    ['code' => 'CNF', 'name' => 'Belo Horizonte Confins, Brazil'],
    ['code' => 'POA', 'name' => 'Porto Alegre, Brazil'],
    ['code' => 'REC', 'name' => 'Recife, Brazil'],
    ['code' => 'SSA', 'name' => 'Salvador, Brazil'],
    ['code' => 'FOR', 'name' => 'Fortaleza, Brazil'],
    ['code' => 'MEX', 'name' => 'Mexico City Benito Juárez, Mexico'],
    ['code' => 'CUN', 'name' => 'Cancún, Mexico'],
    ['code' => 'GDL', 'name' => 'Guadalajara, Mexico'],
    ['code' => 'MTY', 'name' => 'Monterrey, Mexico'],
    ['code' => 'TIJ', 'name' => 'Tijuana, Mexico'],
    ['code' => 'PVR', 'name' => 'Puerto Vallarta, Mexico'],
    ['code' => 'SJD', 'name' => 'San José del Cabo, Mexico'],
    ['code' => 'EZE', 'name' => 'Buenos Aires Ezeiza, Argentina'],
    ['code' => 'AEP', 'name' => 'Buenos Aires Aeroparque, Argentina'],
    ['code' => 'COR', 'name' => 'Cordoba, Argentina'],
    ['code' => 'MDZ', 'name' => 'Mendoza, Argentina'],
    ['code' => 'BOG', 'name' => 'Bogotá El Dorado, Colombia'],
    ['code' => 'MDE', 'name' => 'Medellín José María Córdova, Colombia'],
    ['code' => 'CLO', 'name' => 'Cali, Colombia'],
    ['code' => 'CTG', 'name' => 'Cartagena, Colombia'],
    ['code' => 'BAQ', 'name' => 'Barranquilla, Colombia'],
    ['code' => 'LIM', 'name' => 'Lima Jorge Chávez, Peru'],
    ['code' => 'CUZ', 'name' => 'Cusco, Peru'],
    ['code' => 'SCL', 'name' => 'Santiago Arturo Merino Benítez, Chile'],
    ['code' => 'UIO', 'name' => 'Quito Mariscal Sucre, Ecuador'],
    ['code' => 'GYE', 'name' => 'Guayaquil José Joaquín de Olmedo, Ecuador'],
    ['code' => 'PTY', 'name' => 'Panama City Tocumen, Panama'],
    ['code' => 'SJO', 'name' => 'San José Juan Santamaría, Costa Rica'],
    ['code' => 'SAL', 'name' => 'San Salvador, El Salvador'],
    ['code' => 'GUA', 'name' => 'Guatemala City La Aurora, Guatemala'],
    ['code' => 'SDQ', 'name' => 'Santo Domingo Las Américas, Dominican Republic'],
    ['code' => 'PUJ', 'name' => 'Punta Cana, Dominican Republic'],
    
    // ========== USA & CANADA ==========
    ['code' => 'JFK', 'name' => 'New York JFK, USA'],
    ['code' => 'EWR', 'name' => 'Newark Liberty, USA'],
    ['code' => 'LGA', 'name' => 'New York LaGuardia, USA'],
    ['code' => 'LAX', 'name' => 'Los Angeles, USA'],
    ['code' => 'SFO', 'name' => 'San Francisco, USA'],
    ['code' => 'ORD', 'name' => 'Chicago O\'Hare, USA'],
    ['code' => 'MDW', 'name' => 'Chicago Midway, USA'],
    ['code' => 'DFW', 'name' => 'Dallas/Fort Worth, USA'],
    ['code' => 'ATL', 'name' => 'Atlanta Hartsfield-Jackson, USA'],
    ['code' => 'MIA', 'name' => 'Miami, USA'],
    ['code' => 'FLL', 'name' => 'Fort Lauderdale, USA'],
    ['code' => 'BOS', 'name' => 'Boston Logan, USA'],
    ['code' => 'SEA', 'name' => 'Seattle-Tacoma, USA'],
    ['code' => 'DEN', 'name' => 'Denver, USA'],
    ['code' => 'PHX', 'name' => 'Phoenix Sky Harbor, USA'],
    ['code' => 'LAS', 'name' => 'Las Vegas McCarran, USA'],
    ['code' => 'IAH', 'name' => 'Houston George Bush, USA'],
    ['code' => 'MCO', 'name' => 'Orlando, USA'],
    ['code' => 'TPA', 'name' => 'Tampa, USA'],
    ['code' => 'SAN', 'name' => 'San Diego, USA'],
    ['code' => 'PDX', 'name' => 'Portland, USA'],
    ['code' => 'SLC', 'name' => 'Salt Lake City, USA'],
    ['code' => 'BWI', 'name' => 'Baltimore/Washington, USA'],
    ['code' => 'DCA', 'name' => 'Washington Reagan, USA'],
    ['code' => 'IAD', 'name' => 'Washington Dulles, USA'],
    ['code' => 'CLT', 'name' => 'Charlotte Douglas, USA'],
    ['code' => 'YYZ', 'name' => 'Toronto Pearson, Canada'],
    ['code' => 'YVR', 'name' => 'Vancouver, Canada'],
    ['code' => 'YUL', 'name' => 'Montreal Trudeau, Canada'],
    ['code' => 'YYC', 'name' => 'Calgary, Canada'],
    ['code' => 'YEG', 'name' => 'Edmonton, Canada'],
    ['code' => 'YOW', 'name' => 'Ottawa Macdonald-Cartier, Canada'],
    ['code' => 'YHZ', 'name' => 'Halifax Stanfield, Canada'],
    ['code' => 'YWG', 'name' => 'Winnipeg, Canada'],
    
    // ========== ASIA (Pakistan, India, Bangladesh, UAE, etc.) ==========
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
    ['code' => 'DEL', 'name' => 'Delhi Indira Gandhi, India'],
    ['code' => 'BOM', 'name' => 'Mumbai Chhatrapati Shivaji, India'],
    ['code' => 'BLR', 'name' => 'Bangalore Kempegowda, India'],
    ['code' => 'MAA', 'name' => 'Chennai, India'],
    ['code' => 'CCU', 'name' => 'Kolkata Netaji Subhash, India'],
    ['code' => 'HYD', 'name' => 'Hyderabad Rajiv Gandhi, India'],
    ['code' => 'AMD', 'name' => 'Ahmedabad, India'],
    ['code' => 'ATQ', 'name' => 'Amritsar Sri Guru Ram Dass Jee, India'],
    ['code' => 'GOI', 'name' => 'Goa Dabolim, India'],
    ['code' => 'JAI', 'name' => 'Jaipur, India'],
    ['code' => 'LKO', 'name' => 'Lucknow, India'],
    ['code' => 'COK', 'name' => 'Kochi, India'],
    ['code' => 'TRV', 'name' => 'Trivandrum, India'],
    ['code' => 'DAC', 'name' => 'Dhaka Hazrat Shahjalal, Bangladesh'],
    ['code' => 'CGP', 'name' => 'Chittagong Shah Amanat, Bangladesh'],
    ['code' => 'DXB', 'name' => 'Dubai International, UAE'],
    ['code' => 'AUH', 'name' => 'Abu Dhabi, UAE'],
    ['code' => 'SHJ', 'name' => 'Sharjah, UAE'],
    ['code' => 'DOH', 'name' => 'Doha Hamad, Qatar'],
    ['code' => 'BAH', 'name' => 'Bahrain, Bahrain'],
    ['code' => 'RUH', 'name' => 'Riyadh King Khalid, Saudi Arabia'],
    ['code' => 'JED', 'name' => 'Jeddah King Abdulaziz, Saudi Arabia'],
    ['code' => 'MED', 'name' => 'Medina Prince Mohammad, Saudi Arabia'],
    ['code' => 'DMM', 'name' => 'Dammam King Fahd, Saudi Arabia'],
    ['code' => 'KWI', 'name' => 'Kuwait, Kuwait'],
    ['code' => 'MCT', 'name' => 'Muscat, Oman'],
    ['code' => 'SIN', 'name' => 'Singapore Changi, Singapore'],
    ['code' => 'KUL', 'name' => 'Kuala Lumpur, Malaysia'],
    ['code' => 'BKK', 'name' => 'Bangkok Suvarnabhumi, Thailand'],
    ['code' => 'DMK', 'name' => 'Bangkok Don Mueang, Thailand'],
    ['code' => 'CGK', 'name' => 'Jakarta Soekarno-Hatta, Indonesia'],
    ['code' => 'MNL', 'name' => 'Manila Ninoy Aquino, Philippines'],
    ['code' => 'HAN', 'name' => 'Hanoi Noi Bai, Vietnam'],
    ['code' => 'SGN', 'name' => 'Ho Chi Minh City Tan Son Nhat, Vietnam'],
    ['code' => 'PEK', 'name' => 'Beijing Capital, China'],
    ['code' => 'PKX', 'name' => 'Beijing Daxing, China'],
    ['code' => 'PVG', 'name' => 'Shanghai Pudong, China'],
    ['code' => 'CAN', 'name' => 'Guangzhou Baiyun, China'],
    ['code' => 'HKG', 'name' => 'Hong Kong, China'],
    ['code' => 'NRT', 'name' => 'Tokyo Narita, Japan'],
    ['code' => 'HND', 'name' => 'Tokyo Haneda, Japan'],
    ['code' => 'KIX', 'name' => 'Osaka Kansai, Japan'],
    ['code' => 'ICN', 'name' => 'Seoul Incheon, South Korea'],
    ['code' => 'GMP', 'name' => 'Seoul Gimpo, South Korea'],
    ['code' => 'TPE', 'name' => 'Taipei Taoyuan, Taiwan'],
    ['code' => 'NBO', 'name' => 'Nairobi Jomo Kenyatta, Kenya'],
    ['code' => 'MBA', 'name' => 'Mombasa Moi, Kenya'],
    ['code' => 'ADD', 'name' => 'Addis Ababa Bole, Ethiopia'],
    ['code' => 'JNB', 'name' => 'Johannesburg OR Tambo, South Africa'],
    ['code' => 'CPT', 'name' => 'Cape Town, South Africa'],
    ['code' => 'DUR', 'name' => 'Durban King Shaka, South Africa'],
    ['code' => 'MRU', 'name' => 'Mauritius Sir Seewoosagur Ramgoolam, Mauritius'],
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
        $mail->SMTPDebug = 0;
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

// Generate HTML Ticket with Service Charges
function generateHTMLTicket($bookingData) {
    $serviceCharge = SERVICE_CHARGE;
    $subtotal = $bookingData['total_price'];
    $totalWithService = $subtotal + $serviceCharge;
    
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
        .service-charge { color: #e65100; font-weight: 600; }
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
                    <div class="info-row"><span class="info-label service-charge">Service Charges (€15):</span><span class="service-charge">+ €15 EUR</span></div>
                    <div class="total-price">Total Paid: ' . ($bookingData['total_price'] + 15) . ' ' . $bookingData['currency'] . '</div>
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
    
    $totalWithService = $bookingData['total_price'] + 15;
    $whatsappMsg = "🎫 NEW BOOKING\n";
    $whatsappMsg .= "Ticket: $ticketNumber\n";
    $whatsappMsg .= "Passenger: {$bookingData['passenger_name']}\n";
    $whatsappMsg .= "Route: {$bookingData['origin']} → {$bookingData['destination']}\n";
    $whatsappMsg .= "Flight Fare: {$bookingData['total_price']} {$bookingData['currency']}\n";
    $whatsappMsg .= "Service Charge: €15\n";
    $whatsappMsg .= "Total: $totalWithService {$bookingData['currency']}\n";
    $whatsappMsg .= "Email sent: " . ($emailSent ? 'YES ✅' : 'NO ❌');
    
    header("Location: https://wa.me/34611473217?text=" . urlencode($whatsappMsg));
    exit();
}

// Handle flight search with One Way/Return/Multi-City and Airline Filter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_flights'])) {
    $searchPerformed = true;
    $tripType = $_POST['trip_type'] ?? 'oneway';
    $passengers = intval($_POST['passengers']);
    $cabinClass = $_POST['cabin_class'] ?? 'economy';
    $preferredAirline = $_POST['preferred_airline'] ?? '';
    
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
            
            // Filter by preferred airline if selected
            if (!empty($preferredAirline) && $preferredAirline != 'any') {
                $filteredOffers = [];
                foreach ($offers as $offer) {
                    $airlineName = strtolower($offer['owner']['name'] ?? '');
                    if (strpos($airlineName, strtolower($preferredAirline)) !== false) {
                        $filteredOffers[] = $offer;
                    }
                }
                $offers = $filteredOffers;
            }
            
            if (count($offers) > 0) {
                $tripText = $tripType == 'oneway' ? 'One Way' : ($tripType == 'return' ? 'Return' : 'Multi-City');
                $airlineFilterText = !empty($preferredAirline) && $preferredAirline != 'any' ? " for $preferredAirline" : "";
                $flightResults = '<div class="success-header">✈️ Found ' . count($offers) . ' ' . $tripText . ' flights' . $airlineFilterText . '</div>';
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
                    $airlineName = addslashes($offer['owner']['name'] ?? 'Airline');
                    
                    $flightResults .= '<div class="flight-card" onclick="selectFlight(\''.$offer['id'].'\', \''.$offer['total_amount'].'\', \''.$offer['total_currency'].'\', \''.$airlineName.'\', \''.$displayOrigin.'\', \''.$displayDest.'\', \''.$depDate.'\', \''.$depTime.'\', \''.$arrDate.'\', \''.$arrTime.'\', \''.$durText.'\', \''.$stopText.'\', \''.addslashes($cabinClass).'\')">
                        <div class="flight-header"><div class="airline-info"><div class="airline-icon">✈️</div><div><div class="airline-name">'.htmlspecialchars($offer['owner']['name'] ?? 'Airline').'</div></div></div><div class="flight-price">'.($offer['total_amount']).' '.$offer['total_currency'].' <small style="font-size:12px; color:#666;">(+ €15 service fee)</small></div></div>
                        <div class="flight-route"><div><div class="city-code">'.$displayOrigin.'</div><div class="city-name">'.getAirportName($displayOrigin).'</div><div class="flight-time">'.$depTime.'</div></div>
                        <div class="flight-duration"><div class="duration-line"></div><div class="duration-text">'.$durText.'</div><div class="stops-text">'.$stopText.'</div></div>
                        <div><div class="city-code">'.$displayDest.'</div><div class="city-name">'.getAirportName($displayDest).'</div><div class="flight-time">'.$arrTime.'</div></div></div>
                        <button class="select-flight-btn">Select Flight</button></div>';
                }
            } else {
                $filterMsg = !empty($preferredAirline) && $preferredAirline != 'any' ? " for $preferredAirline" : "";
                $flightResults = '<div class="error">✈️ No flights found' . $filterMsg . '. Try different date or airline.</div>';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels | Book Flights - Umrah - Hajj</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        :root { --primary-gold: #d4af37; --primary-navy: #1a237e; --primary-teal: #00695c; --light-gold: #f5e8c8; --light-bg: #f9f7f2; --shadow: 0 10px 30px rgba(0,0,0,0.08); --radius: 12px; --transition: all 0.4s cubic-bezier(0.175,0.885,0.32,1.1); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Montserrat', sans-serif; background: var(--light-bg); line-height: 1.7; }
        .container { max-width: 1400px; margin: 0 auto; padding: 0 20px; }
        .two-columns { display: flex; gap: 30px; margin: 40px 0; }
        .main-content { flex: 3; }
        .sidebar { flex: 1; }
        
        .elegant-header { background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-teal) 100%); padding: 15px 0; position: sticky; top: 0; z-index: 1000; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .header-top-bar { display: flex; justify-content: space-between; align-items: center; color: white; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); flex-wrap: wrap; gap: 15px; }
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
        .nav-elegant a { color: white; text-decoration: none; font-weight: 500; font-size: 15px; transition: var(--transition); cursor: pointer; }
        .nav-elegant a:hover { color: var(--primary-gold); }
        .whatsapp-btn-elegant { background: #25D366; color: white; padding: 12px 24px; border-radius: 50px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; transition: var(--transition); }
        
        .flying-marquee-container { background: linear-gradient(135deg, var(--primary-navy) 0%, #0d1440 100%); padding: 10px 0; border-bottom: 2px solid var(--primary-gold); }
        .marquee-track { height: 35px; display: flex; align-items: center; overflow: hidden; position: relative; }
        .marquee-content { display: flex; animation: marqueeScroll 30s linear infinite; white-space: nowrap; }
        .marquee-text { color: white; font-size: 15px; padding: 0 25px; display: flex; align-items: center; }
        .marquee-text:before { content: '•'; color: var(--primary-gold); margin-right: 15px; }
        .flying-plane { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); background: var(--primary-gold); width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: planeFloat 3s ease-in-out infinite; }
        
        .luxury-slider { height: 450px; position: relative; overflow: hidden; border-radius: 0 0 var(--radius) var(--radius); }
        .luxury-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; opacity: 0; transition: opacity 1.2s; }
        .luxury-slide.active { opacity: 1; }
        .slide-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(26,35,126,0.85), rgba(0,105,92,0.85)); display: flex; align-items: center; }
        .slide-content-luxury { max-width: 600px; padding-left: 60px; color: white; }
        .slide-content-luxury h2 { font-size: 42px; color: white; margin-bottom: 20px; }
        .slide-content-luxury p { font-size: 18px; margin-bottom: 35px; }
        .luxury-btn { display: inline-flex; align-items: center; gap: 12px; background: var(--primary-gold); color: var(--primary-navy); padding: 14px 28px; border-radius: 50px; font-weight: 600; text-decoration: none; transition: var(--transition); }
        .slider-controls { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; }
        .slider-dot-luxury { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; }
        .slider-dot-luxury.active { background: var(--primary-gold); transform: scale(1.2); }
        
        .search-luxury { background: white; padding: 35px; border-radius: var(--radius); box-shadow: var(--shadow); margin-top: -50px; position: relative; z-index: 10; margin-bottom: 30px; border-top: 4px solid var(--primary-gold); }
        .section-header { text-align: center; margin-bottom: 40px; }
        .section-header h2 { font-size: 36px; color: var(--primary-navy); position: relative; display: inline-block; }
        .section-header h2:after { content: ''; position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); width: 80px; height: 4px; background: var(--primary-gold); }
        
        .trip-toggle { display: flex; gap: 20px; margin-bottom: 25px; justify-content: center; flex-wrap: wrap; }
        .trip-option { display: flex; align-items: center; gap: 8px; cursor: pointer; padding: 10px 20px; border-radius: 50px; transition: var(--transition); }
        .trip-option:hover { background: var(--light-gold); }
        .trip-option input { width: 18px; height: 18px; cursor: pointer; }
        
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: var(--primary-navy); margin-bottom: 8px; font-size: 14px; }
        .form-group select, .form-group input { width: 100%; padding: 14px 16px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 16px; }
        .form-group select:focus, .form-group input:focus { border-color: var(--primary-gold); outline: none; }
        .multi-city-row { background: #f8f9fa; padding: 20px; border-radius: 16px; margin-bottom: 20px; }
        .search-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 16px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; transition: var(--transition); }
        .search-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        
        .flight-card { background: white; border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: var(--shadow); transition: var(--transition); cursor: pointer; border: 1px solid #eee; }
        .flight-card:hover { transform: translateY(-5px); border-left: 4px solid var(--primary-gold); }
        .flight-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 15px; }
        .airline-info { display: flex; align-items: center; gap: 15px; }
        .airline-icon { width: 45px; height: 45px; background: var(--primary-navy); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
        .airline-name { font-weight: 700; font-size: 16px; color: var(--primary-navy); }
        .flight-price { font-size: 24px; font-weight: 800; color: var(--primary-teal); }
        .flight-route { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin: 15px 0; flex-wrap: wrap; }
        .city-code { font-weight: 800; font-size: 20px; color: var(--primary-navy); }
        .city-name { font-size: 11px; color: #666; }
        .flight-time { font-weight: 600; font-size: 14px; margin-top: 5px; }
        .flight-duration { text-align: center; flex: 1; min-width: 100px; }
        .duration-line { height: 2px; background: var(--primary-gold); width: 100%; }
        .select-flight-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 12px 20px; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; width: 100%; transition: var(--transition); margin-top: 10px; }
        .select-flight-btn:hover { transform: translateY(-2px); }
        .error { background: #ffebee; padding: 15px; border-radius: 12px; color: #c62828; }
        .success-header { background: #e8f5e9; padding: 12px; border-radius: 12px; margin-bottom: 20px; color: #2e7d32; }
        
        .sidebar-widget { background: white; border-radius: 20px; padding: 25px; margin-bottom: 25px; box-shadow: var(--shadow); border-top: 4px solid var(--primary-gold); }
        .sidebar-widget h3 { font-size: 20px; color: var(--primary-navy); margin-bottom: 20px; border-left: 3px solid var(--primary-gold); padding-left: 12px; }
        .flight-info { background: #f8f9fa; border-radius: 12px; padding: 12px; margin-bottom: 12px; transition: var(--transition); }
        .flight-time { font-weight: 700; color: var(--primary-navy); font-size: 16px; }
        .flight-status { font-size: 12px; font-weight: 600; padding: 2px 8px; border-radius: 20px; display: inline-block; }
        .status-on-time { background: #e8f5e9; color: #2e7d32; }
        .airline-tag { display: inline-block; background: linear-gradient(135deg, var(--primary-navy), var(--primary-teal)); color: white; padding: 5px 12px; border-radius: 50px; font-size: 12px; margin: 5px; transition: var(--transition); }
        
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); display: flex; align-items: center; justify-content: center; z-index: 10000; opacity: 0; visibility: hidden; transition: 0.3s; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background: white; padding: 35px; border-radius: 24px; max-width: 700px; width: 90%; position: relative; max-height: 90vh; overflow-y: auto; }
        .close-modal { position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; }
        .booking-section { margin-bottom: 25px; }
        .booking-section h3 { color: var(--primary-navy); margin-bottom: 15px; padding-bottom: 8px; border-bottom: 2px solid var(--primary-gold); display: inline-block; }
        .booking-summary { background: #f8f9fa; padding: 20px; border-radius: 16px; margin-bottom: 25px; border-left: 4px solid var(--primary-gold); }
        .price-breakdown { background: #f8f9fa; padding: 15px; border-radius: 12px; margin-top: 15px; }
        .price-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .price-row.total { font-weight: 700; color: var(--primary-teal); font-size: 18px; margin-top: 10px; padding-top: 10px; border-top: 2px solid var(--primary-gold); }
        .booking-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .booking-form-group { margin-bottom: 15px; }
        .booking-form-group label { display: block; font-weight: 600; color: var(--primary-navy); margin-bottom: 5px; font-size: 13px; }
        .booking-form-group input, .booking-form-group select { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 14px; }
        .confirm-btn { background: #25D366; color: white; padding: 16px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; transition: var(--transition); }
        .confirm-btn:hover { transform: translateY(-3px); }
        
        .packages-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 30px; }
        .package-card { background: white; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); transition: var(--transition); }
        .package-card:hover { transform: translateY(-5px); }
        .package-image { height: 180px; background-size: cover; background-position: center; position: relative; }
        .package-badge { position: absolute; top: 15px; right: 15px; background: var(--primary-gold); padding: 5px 15px; border-radius: 50px; font-weight: 700; }
        .package-content { padding: 20px; }
        .package-btn { background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy)); color: white; padding: 12px; border-radius: 8px; border: none; cursor: pointer; width: 100%; transition: var(--transition); }
        
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
        
        /* Chatbot Styles */
        .chatbot-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25D366;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: 0.3s;
            z-index: 999;
        }
        .chatbot-btn:hover { transform: scale(1.1); }
        .chatbot-btn i { color: white; font-size: 30px; }
        .chatbot-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 1000;
        }
        .chatbot-window.active { display: flex; }
        .chatbot-header {
            background: linear-gradient(135deg, #1a237e, #00695c);
            color: white;
            padding: 15px;
            text-align: center;
        }
        .chatbot-messages {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
        }
        .message {
            margin-bottom: 15px;
            display: flex;
        }
        .message.bot .msg-bubble {
            background: #f0f2f5;
            color: #333;
            border-radius: 18px 18px 18px 4px;
        }
        .message.user { justify-content: flex-end; }
        .message.user .msg-bubble {
            background: #1a237e;
            color: white;
            border-radius: 18px 18px 4px 18px;
        }
        .msg-bubble {
            max-width: 80%;
            padding: 10px 15px;
            font-size: 14px;
        }
        .chatbot-input {
            display: flex;
            padding: 10px;
            border-top: 1px solid #eee;
        }
        .chatbot-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
        }
        .chatbot-input button {
            background: #25D366;
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
            cursor: pointer;
        }
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
        <h3 style="text-align:center; margin-bottom:20px; font-size:24px; color:var(--primary-navy);">✈️ Search Flights</h3>
        <form method="POST" action="" id="searchForm">
            <div class="trip-toggle">
                <label class="trip-option"><input type="radio" name="trip_type" value="oneway" checked onchange="toggleTripType()"> ✈️ One Way</label>
                <label class="trip-option"><input type="radio" name="trip_type" value="return" onchange="toggleTripType()"> 🔄 Return</label>
                <label class="trip-option"><input type="radio" name="trip_type" value="multi" onchange="toggleTripType()"> 🌍 Multi-City</label>
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
                <div class="form-group"><label>✈️ Preferred Airline</label>
                    <select name="preferred_airline">
                        <option value="any">Any Airline</option>
                        <option value="Qatar Airways">🇶🇦 Qatar Airways</option>
                        <option value="Emirates">🇦🇪 Emirates</option>
                        <option value="Etihad">🇦🇪 Etihad Airways</option>
                        <option value="Turkish Airlines">🇹🇷 Turkish Airlines</option>
                        <option value="British Airways">🇬🇧 British Airways</option>
                        <option value="Air France">🇫🇷 Air France</option>
                        <option value="Lufthansa">🇩🇪 Lufthansa</option>
                        <option value="KLM">🇳🇱 KLM</option>
                        <option value="Iberia">🇪🇸 Iberia</option>
                        <option value="Vueling">🇪🇸 Vueling</option>
                    </select>
                </div>
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
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)">
                        <div><strong>✈️ Etihad Airways</strong><br>Barcelona → Lahore</div>
                        <div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€580</div>
                        <div style="font-size:12px; color:#e65100;">+ €15 service charge = €595</div>
                        <a href="https://wa.me/34611473217?text=BCN to LHE €580 + €15 service charge" class="whatsapp-btn-elegant" style="display:inline-block; padding:8px 16px; font-size:12px; margin-top:10px;">Book Now</a>
                    </div>
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)">
                        <div><strong>✈️ Etihad Airways</strong><br>Barcelona → Islamabad</div>
                        <div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€585</div>
                        <div style="font-size:12px; color:#e65100;">+ €15 service charge = €600</div>
                        <a href="https://wa.me/34611473217?text=BCN to ISB €585 + €15 service charge" class="whatsapp-btn-elegant" style="display:inline-block; padding:8px 16px; font-size:12px; margin-top:10px;">Book Now</a>
                    </div>
                    <div style="background:white; border-radius:16px; padding:20px; border-left:4px solid var(--primary-gold)">
                        <div><strong>✈️ Emirates</strong><br>Barcelona → Dubai</div>
                        <div style="font-size:24px; font-weight:700; color:var(--primary-teal); margin:10px 0">€299</div>
                        <div style="font-size:12px; color:#e65100;">+ €15 service charge = €314</div>
                        <a href="https://wa.me/34611473217?text=BCN to DXB €299 + €15 service charge" class="whatsapp-btn-elegant" style="display:inline-block; padding:8px 16px; font-size:12px; margin-top:10px;">Book Now</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="sidebar">
            <div class="sidebar-widget">
                <h3><i class="fas fa-plane-departure"></i> BCN Departures</h3>
                <div class="flight-info"><div class="flight-time">08:30</div><div class="flight-route">BCN → LHR (British Airways)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">09:15</div><div class="flight-route">BCN → CDG (Air France)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">10:00</div><div class="flight-route">BCN → FCO (ITA Airways)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">11:20</div><div class="flight-route">BCN → DXB (Emirates)</div><span class="flight-status status-on-time">On Time</span></div>
            </div>
            
            <div class="sidebar-widget">
                <h3><i class="fas fa-plane-arrival"></i> BCN Arrivals</h3>
                <div class="flight-info"><div class="flight-time">09:45</div><div class="flight-route">LHR → BCN (British Airways)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">10:30</div><div class="flight-route">CDG → BCN (Air France)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">11:15</div><div class="flight-route">DXB → BCN (Emirates)</div><span class="flight-status status-on-time">On Time</span></div>
                <div class="flight-info"><div class="flight-time">13:00</div><div class="flight-route">LHE → BCN (Etihad)</div><span class="flight-status status-on-time">On Time</span></div>
            </div>
            
            <div class="sidebar-widget">
                <h3><i class="fas fa-building"></i> Partner Airlines</h3>
                <div><span class="airline-tag">Etihad Airways</span><span class="airline-tag">Emirates</span><span class="airline-tag">Qatar Airways</span><span class="airline-tag">British Airways</span><span class="airline-tag">Air France</span><span class="airline-tag">Turkish Airlines</span><span class="airline-tag">Singapore Airlines</span><span class="airline-tag">Delta Air Lines</span><span class="airline-tag">Lufthansa</span><span class="airline-tag">KLM</span><span class="airline-tag">Iberia</span><span class="airline-tag">Vueling</span></div>
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

<section id="services" style="background:var(--light-bg); padding:60px 0">
    <div class="container">
        <div class="section-header"><h2>⭐ Our Services</h2></div>
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:25px">
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-passport"></i></div><h3>Visa Processing</h3><p>+ €15 service fee</p></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-hotel"></i></div><h3>Luxury Hotels</h3><p>+ €15 service fee</p></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-car"></i></div><h3>Airport Transfers</h3><p>+ €15 service fee</p></div>
            <div style="background:white; padding:30px; border-radius:20px; text-align:center"><div style="width:70px; height:70px; background:#f5e8c8; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:28px; color:#00695c"><i class="fas fa-headset"></i></div><h3>24/7 Support</h3><p>Free</p></div>
        </div>
    </div>
</div>

<section id="about" style="padding:60px 0">
    <div class="container">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:50px; align-items:center">
            <div><h2 style="font-size:36px; color:var(--primary-navy); margin-bottom:20px">About Mustafa Travels</h2><p>Since 2024, we've been crafting exceptional travel experiences. Specializing in Umrah, Hajj & worldwide flights.</p><p style="margin-top:15px"><strong>500+ Happy Travelers | 50+ Destinations | ⭐ 4.9/5 Rating</strong></p><p style="margin-top:15px"><strong>💰 Service Charge: €15 per ticket</strong> - For booking assistance, ticket delivery, and 24/7 support</p></div>
            <div style="height:350px; background-image:url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'); background-size:cover; border-radius:20px"></div>
        </div>
    </div>
</section>

<footer class="footer-elegant" id="contact">
    <div class="container">
        <div class="footer-content">
            <div><h3>Mustafa Travels</h3><p>Rambla Badal 141, Barcelona<br>📞 +34-632234216<br>💬 +34-611473217<br>✉️ mustafatravelstours@gmail.com</p></div>
            <div><h3>Quick Links</h3><p><a href="#home" style="color:white">Home</a></p><p><a href="#umrah" style="color:white">Umrah</a></p><p><a href="#hajj" style="color:white">Hajj</a></p></div>
            <div><h3>Destinations</h3><p>Pakistan | India | USA | UK | UAE | Europe | Africa | Latin America</p></div>
            <div><h3>Hours</h3><p>Mon-Thu: 10:30-20:30<br>Fri: 10:30-13:00 & 15:00-20:30<br>Sat: 10:30-19:30</p></div>
        </div>
        <div class="footer-bottom"><p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Service Charge: €15 per ticket</p></div>
    </div>
</footer>

<!-- Chatbot -->
<div class="chatbot-btn" onclick="toggleChatbot()">
    <i class="fab fa-whatsapp"></i>
</div>
<div class="chatbot-window" id="chatbotWindow">
    <div class="chatbot-header">
        <h4>🤖 Mustafa Travels Assistant</h4>
        <small>Ask me anything about flights, Umrah, or booking!</small>
    </div>
    <div class="chatbot-messages" id="chatMessages">
        <div class="message bot">
            <div class="msg-bubble">👋 Hello! Welcome to Mustafa Travels! How can I help you today?<br><br>You can ask me:<br>• Flight prices to Lahore<br>• Umrah packages<br>• Booking assistance<br>• Or just say "help"</div>
        </div>
    </div>
    <div class="chatbot-input">
        <input type="text" id="chatInput" placeholder="Type your message..." onkeypress="if(event.key==='Enter') sendChatMessage()">
        <button onclick="sendChatMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<div class="modal-overlay" id="bookingModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeBookingModal()">&times;</button>
        <h2 style="color: var(--primary-navy); margin-bottom: 20px;">✈️ Complete Your Booking</h2>
        <form method="POST" action="" id="bookingForm">
            <input type="hidden" name="confirm_booking" value="1">
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
            
            <div class="booking-summary" id="flightSummary"></div>
            
            <div class="booking-section">
                <h3>👤 Passenger Details</h3>
                <div class="booking-form-row">
                    <div class="booking-form-group"><label>Title *</label><select name="passenger_title"><option value="Mr">Mr</option><option value="Mrs">Mrs</option><option value="Ms">Ms</option><option value="Dr">Dr</option></select></div>
                    <div class="booking-form-group"><label>Given Name *</label><input type="text" name="passenger_given_name" required></div>
                    <div class="booking-form-group"><label>Family Name *</label><input type="text" name="passenger_family_name" required></div>
                </div>
                <div class="booking-form-row">
                    <div class="booking-form-group"><label>Date of Birth *</label><input type="date" name="passenger_dob" required></div>
                    <div class="booking-form-group"><label>Gender *</label><select name="passenger_gender"><option value="male">Male</option><option value="female">Female</option></select></div>
                </div>
                <div class="booking-form-row">
                    <div class="booking-form-group"><label>Passport Number *</label><input type="text" name="passenger_passport" required></div>
                    <div class="booking-form-group"><label>Passport Expiry *</label><input type="date" name="passenger_passport_expiry" required></div>
                </div>
            </div>
            
            <div class="booking-section">
                <h3>📞 Contact Details</h3>
                <div class="booking-form-row">
                    <div class="booking-form-group"><label>Email Address *</label><input type="email" name="contact_email" required></div>
                    <div class="booking-form-group"><label>Phone Number *</label><input type="tel" name="contact_phone" required></div>
                </div>
            </div>
            
            <div class="price-breakdown" id="priceBreakdown"></div>
            <p style="font-size:12px; color:#e65100; margin:10px 0; text-align:center;">💰 Service Charge: €15 included in total</p>
            <button type="submit" class="confirm-btn">✅ Confirm Booking</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() { 
    $('.airport-select').select2({ 
        width: '100%',
        placeholder: 'Search airport by code or city name...',
        allowClear: true
    }); 
});

// Slider
let currentSlide = 0;
const slides = document.querySelectorAll('.luxury-slide');
const dots = document.querySelectorAll('.slider-dot-luxury');
function showSlide(i) { if(!slides.length) return; slides.forEach(s=>s.classList.remove('active')); dots.forEach(d=>d.classList.remove('active')); slides[i].classList.add('active'); dots[i].classList.add('active'); currentSlide=i; }
if(slides.length) { setInterval(()=>{ currentSlide = (currentSlide+1)%slides.length; showSlide(currentSlide); }, 5000); dots.forEach((d,i)=>d.addEventListener('click',()=>showSlide(i))); }

// Mobile menu
document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() { document.querySelector('.nav-elegant')?.classList.toggle('active'); });

// Smooth scroll
document.querySelectorAll('.nav-elegant a, .logo-elegant, .luxury-btn').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if(href && href.startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(href);
            if(target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

function bookPackage(name, price) { window.open(`https://wa.me/34611473217?text=I'm interested in ${name} (${price})`, '_blank'); }

function toggleTripType() {
    const type = document.querySelector('input[name="trip_type"]:checked').value;
    document.getElementById('onewayFields').style.display = type == 'oneway' ? 'block' : 'none';
    document.getElementById('returnFields').style.display = type == 'return' ? 'block' : 'none';
    document.getElementById('multiFields').style.display = type == 'multi' ? 'block' : 'none';
}

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
    
    document.getElementById('flightSummary').innerHTML = `<strong>✈️ Flight Details</strong><br>${airline}<br>${origin} → ${destination}<br>Departure: ${depDate} at ${depTime}<br>Arrival: ${arrDate} at ${arrTime}<br>Duration: ${duration}<br>Class: ${cabinClass}`;
    document.getElementById('priceBreakdown').innerHTML = `
        <div class="price-row"><span>Flight Fare</span><span>${price} ${currency}</span></div>
        <div class="price-row"><span>Service Charge</span><span>+ €15 EUR</span></div>
        <div class="price-row total"><span>Total Amount</span><span>${totalWithService.toFixed(2)} ${currency}</span></div>`;
    document.getElementById('bookingModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() { document.getElementById('bookingModal').classList.remove('active'); document.body.style.overflow = 'auto'; }
document.querySelectorAll('.close-modal, .modal-overlay').forEach(el => { el.addEventListener('click', (e) => { if(e.target === document.getElementById('bookingModal') || e.target.classList.contains('close-modal')) closeBookingModal(); }); });

// Chatbot Functions
function toggleChatbot() {
    const window = document.getElementById('chatbotWindow');
    window.classList.toggle('active');
}

function sendChatMessage() {
    const input = document.getElementById('chatInput');
    const message = input.value.trim();
    if(!message) return;
    
    const messagesDiv = document.getElementById('chatMessages');
    
    messagesDiv.innerHTML += `<div class="message user"><div class="msg-bubble">${escapeHtml(message)}</div></div>`;
    input.value = '';
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
    
    setTimeout(() => {
        let response = getBotResponse(message.toLowerCase());
        messagesDiv.innerHTML += `<div class="message bot"><div class="msg-bubble">${response}</div></div>`;
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }, 500);
}

function getBotResponse(msg) {
    if(msg.includes('hello') || msg.includes('hi') || msg.includes('salam')) {
        return '👋 Assalamu Alaikum! Welcome to Mustafa Travels! How can I help you today?';
    }
    if(msg.includes('flight') || msg.includes('ticket')) {
        return '✈️ You can search flights using the form above! Just enter your origin, destination, and date. You can also filter by your favorite airline like Qatar Airways, Emirates, or Etihad. Our service charge is €15 per ticket.';
    }
    if(msg.includes('qatar')) {
        return '🇶🇦 Yes! You can search for Qatar Airways flights by selecting "Qatar Airways" from the "Preferred Airline" dropdown in the search form above.';
    }
    if(msg.includes('emirates')) {
        return '🇦🇪 Yes! You can search for Emirates flights by selecting "Emirates" from the "Preferred Airline" dropdown in the search form above.';
    }
    if(msg.includes('etihad')) {
        return '🇦🇪 Yes! You can search for Etihad Airways flights by selecting "Etihad" from the "Preferred Airline" dropdown in the search form above.';
    }
    if(msg.includes('umrah')) {
        return '🕋 We have 3 Umrah packages starting from €895. Includes flights, hotel near Haram, and visa processing. Would you like me to share the details?';
    }
    if(msg.includes('hajj')) {
        return '🕋 Hajj 2026 packages are currently in development. Phase 2 bookings will open soon. Click "Notify Me" on our website to get updates!';
    }
    if(msg.includes('contact') || msg.includes('phone') || msg.includes('whatsapp')) {
        return '📞 You can reach us at:<br>📱 WhatsApp: +34-611473217<br>📞 Call: +34-632234216<br>✉️ Email: mustafatravelstours@gmail.com';
    }
    if(msg.includes('service charge') || msg.includes('fee')) {
        return '💰 We charge a €15 service fee per ticket for booking assistance, ticket delivery, and 24/7 customer support.';
    }
    if(msg.includes('help')) {
        return '🤖 I can help you with:<br>• Flight search (with airline filter!)<br>• Umrah package details<br>• Hajj 2026 information<br>• Contact information<br>• Service charges<br><br>Just type what you need!';
    }
    if(msg.includes('lhr') || msg.includes('london')) {
        return '✈️ Flights from Barcelona to London start from €79 + €15 service charge = €94. Try searching with British Airways or Vueling!';
    }
    if(msg.includes('lhe') || msg.includes('lahore')) {
        return '✈️ Flights from Barcelona to Lahore start from €580 + €15 service charge = €595. Try selecting "Etihad" from the airline filter!';
    }
    if(msg.includes('isb') || msg.includes('islamabad')) {
        return '✈️ Flights from Barcelona to Islamabad start from €585 + €15 service charge = €600. Try selecting "Etihad" from the airline filter!';
    }
    return 'Thank you for your message! For specific flight requests, please use the search form above and select your preferred airline. For urgent inquiries, contact us on WhatsApp: +34-611473217';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

toggleTripType();
if(slides.length) showSlide(0);
</script>
</body>
</html>
