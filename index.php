<?php
// 🔑 Duffel Live Token
$apiKey = 'duffel_live_cvF1_BR5No4SjJTp3tGVSDKkpwWwFU3VgOkN7TtETlB';
$flightResults = '';
$searchPerformed = false;

// ========== SMTP CONFIGURATION (YAHAN ADD KIA HAI) ==========
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
// ============================================================

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
    ['code' => 'TXL', 'name' => 'Berlin Tegel, Germany'],
    
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
    // Brazil
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
    
    // Mexico
    ['code' => 'MEX', 'name' => 'Mexico City Benito Juárez, Mexico'],
    ['code' => 'CUN', 'name' => 'Cancún, Mexico'],
    ['code' => 'GDL', 'name' => 'Guadalajara, Mexico'],
    ['code' => 'MTY', 'name' => 'Monterrey, Mexico'],
    ['code' => 'TIJ', 'name' => 'Tijuana, Mexico'],
    ['code' => 'PVR', 'name' => 'Puerto Vallarta, Mexico'],
    ['code' => 'SJD', 'name' => 'San José del Cabo, Mexico'],
    
    // Argentina
    ['code' => 'EZE', 'name' => 'Buenos Aires Ezeiza, Argentina'],
    ['code' => 'AEP', 'name' => 'Buenos Aires Aeroparque, Argentina'],
    ['code' => 'COR', 'name' => 'Cordoba, Argentina'],
    ['code' => 'MDZ', 'name' => 'Mendoza, Argentina'],
    
    // Colombia
    ['code' => 'BOG', 'name' => 'Bogotá El Dorado, Colombia'],
    ['code' => 'MDE', 'name' => 'Medellín José María Córdova, Colombia'],
    ['code' => 'CLO', 'name' => 'Cali, Colombia'],
    ['code' => 'CTG', 'name' => 'Cartagena, Colombia'],
    ['code' => 'BAQ', 'name' => 'Barranquilla, Colombia'],
    
    // Peru
    ['code' => 'LIM', 'name' => 'Lima Jorge Chávez, Peru'],
    ['code' => 'CUZ', 'name' => 'Cusco, Peru'],
    
    // Chile
    ['code' => 'SCL', 'name' => 'Santiago Arturo Merino Benítez, Chile'],
    
    // Other Latin America
    ['code' => 'UIO', 'name' => 'Quito Mariscal Sucre, Ecuador'],
    ['code' => 'GYE', 'name' => 'Guayaquil José Joaquín de Olmedo, Ecuador'],
    ['code' => 'PTY', 'name' => 'Panama City Tocumen, Panama'],
    ['code' => 'SJO', 'name' => 'San José Juan Santamaría, Costa Rica'],
    ['code' => 'SAL', 'name' => 'San Salvador, El Salvador'],
    ['code' => 'GUA', 'name' => 'Guatemala City La Aurora, Guatemala'],
    ['code' => 'SDQ', 'name' => 'Santo Domingo Las Américas, Dominican Republic'],
    ['code' => 'PUJ', 'name' => 'Punta Cana, Dominican Republic'],
    ['code' => 'Hav', 'name' => 'Havana José Martí, Cuba'],
    ['code' => 'VRA', 'name' => 'Varadero Juan Gualberto Gómez, Cuba'],
    ['code' => 'NAS', 'name' => 'Nassau Lynden Pindling, Bahamas'],
    
    // ========== USA & CANADA ==========
    // USA
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
    ['code' => 'NASH', 'name' => 'Nashville, USA'],
    ['code' => 'STL', 'name' => 'St Louis Lambert, USA'],
    
    // Canada
    ['code' => 'YYZ', 'name' => 'Toronto Pearson, Canada'],
    ['code' => 'YVR', 'name' => 'Vancouver, Canada'],
    ['code' => 'YUL', 'name' => 'Montreal Trudeau, Canada'],
    ['code' => 'YYC', 'name' => 'Calgary, Canada'],
    ['code' => 'YEG', 'name' => 'Edmonton, Canada'],
    ['code' => 'YOW', 'name' => 'Ottawa Macdonald-Cartier, Canada'],
    ['code' => 'YHZ', 'name' => 'Halifax Stanfield, Canada'],
    ['code' => 'YWG', 'name' => 'Winnipeg, Canada'],
    
    // ========== ASIA (Pakistan, India, Bangladesh, UAE, etc.) ==========
    // Pakistan
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
    ['code' => 'Suk', 'name' => 'Sukkur, Pakistan'],
    
    // India
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
    
    // Bangladesh
    ['code' => 'DAC', 'name' => 'Dhaka Hazrat Shahjalal, Bangladesh'],
    ['code' => 'CGP', 'name' => 'Chittagong Shah Amanat, Bangladesh'],
    
    // UAE & Middle East
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
    
    // Southeast Asia
    ['code' => 'SIN', 'name' => 'Singapore Changi, Singapore'],
    ['code' => 'KUL', 'name' => 'Kuala Lumpur, Malaysia'],
    ['code' => 'BKK', 'name' => 'Bangkok Suvarnabhumi, Thailand'],
    ['code' => 'DMK', 'name' => 'Bangkok Don Mueang, Thailand'],
    ['code' => 'CGK', 'name' => 'Jakarta Soekarno-Hatta, Indonesia'],
    ['code' => 'MNL', 'name' => 'Manila Ninoy Aquino, Philippines'],
    ['code' => 'HAN', 'name' => 'Hanoi Noi Bai, Vietnam'],
    ['code' => 'SGN', 'name' => 'Ho Chi Minh City Tan Son Nhat, Vietnam'],
    
    // China & East Asia
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
        // East Africa
    ['code' => 'NBO', 'name' => 'Nairobi Jomo Kenyatta, Kenya'],
    ['code' => 'MBA', 'name' => 'Mombasa Moi, Kenya'],
    ['code' => 'ADD', 'name' => 'Addis Ababa Bole, Ethiopia'],
    ['code' => 'DIR', 'name' => 'Dire Dawa, Ethiopia'],
    ['code' => 'JUB', 'name' => 'Juba, South Sudan'],
    ['code' => 'KRT', 'name' => 'Khartoum, Sudan'],
    ['code' => 'EBB', 'name' => 'Entebbe, Uganda'],
    ['code' => 'KGL', 'name' => 'Kigali, Rwanda'],
    ['code' => 'BJM', 'name' => 'Bujumbura, Burundi'],
    ['code' => 'DAR', 'name' => 'Dar es Salaam Julius Nyerere, Tanzania'],
    ['code' => 'ZNZ', 'name' => 'Zanzibar Abeid Amani Karume, Tanzania'],
    ['code' => 'JRO', 'name' => 'Kilimanjaro, Tanzania'],
    ['code' => 'HRE', 'name' => 'Harare Robert Mugabe, Zimbabwe'],
    ['code' => 'BUQ', 'name' => 'Bulawayo Joshua Mqabuko, Zimbabwe'],
    ['code' => 'LUN', 'name' => 'Lusaka Kenneth Kaunda, Zambia'],
    ['code' => 'LLW', 'name' => 'Lilongwe, Malawi'],
    ['code' => 'BLZ', 'name' => 'Blantyre Chileka, Malawi'],
    ['code' => 'GBE', 'name' => 'Gaborone Sir Seretse Khama, Botswana'],
    ['code' => 'MPM', 'name' => 'Maputo, Mozambique'],
    
    // Southern Africa
    ['code' => 'JNB', 'name' => 'Johannesburg OR Tambo, South Africa'],
    ['code' => 'CPT', 'name' => 'Cape Town, South Africa'],
    ['code' => 'DUR', 'name' => 'Durban King Shaka, South Africa'],
    ['code' => 'PLZ', 'name' => 'Port Elizabeth, South Africa'],
    ['code' => 'GRJ', 'name' => 'George, South Africa'],
    ['code' => 'WDH', 'name' => 'Windhoek Hosea Kutako, Namibia'],
    ['code' => 'VFA', 'name' => 'Victoria Falls, Zimbabwe'],
    ['code' => 'MRU', 'name' => 'Mauritius Sir Seewoosagur Ramgoolam, Mauritius'],
    ['code' => 'SEZ', 'name' => 'Mahé Seychelles, Seychelles'],
    ['code' => 'RUN', 'name' => 'Réunion Roland Garros, Réunion'],
    ['code' => 'TNR', 'name' => 'Antananarivo Ivato, Madagascar'],
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
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Generate HTML Ticket
function generateHTMLTicket($bookingData) {
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
        .flight-info, .passenger-info { background: #f8f9fa; border-radius: 16px; padding: 20px; margin-bottom: 25px; }
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
                <div class="total-price">Total Paid: ' . $bookingData['total_price'] . ' ' . $bookingData['currency'] . '</div>
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
    
    $whatsappMsg = "🎫 NEW BOOKING\nTicket: $ticketNumber\nPassenger: {$bookingData['passenger_name']}\nRoute: {$bookingData['origin']} → {$bookingData['destination']}\nTotal: {$bookingData['total_price']} {$bookingData['currency']}\nEmail sent: " . ($emailSent ? 'YES' : 'NO');
    header("Location: https://wa.me/34611473217?text=" . urlencode($whatsappMsg));
    exit();
}

// Handle flight search (simplified version)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_flights'])) {
    $searchPerformed = true;
    $origin = strtoupper(trim($_POST['origin']));
    $destination = strtoupper(trim($_POST['destination']));
    $date = $_POST['departure_date'];
    $passengers = intval($_POST['passengers']);
    $cabinClass = $_POST['cabin_class'] ?? 'economy';
    
    $searchData = ['data' => ['slices' => [['origin' => $origin, 'destination' => $destination, 'departure_date' => $date]], 'passengers' => array_fill(0, $passengers, ['type' => 'adult']), 'cabin_class' => $cabinClass, 'max_connections' => 1]];
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
            $flightResults = '<div class="success-header">✈️ Found ' . count($offers) . ' flights</div>';
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
                $flightResults .= '<div class="flight-card" onclick="selectFlight(\''.$offer['id'].'\', \''.$offer['total_amount'].'\', \''.$offer['total_currency'].'\', \''.addslashes($offer['owner']['name'] ?? 'Airline').'\', \''.$origin.'\', \''.$destination.'\', \''.$depDate.'\', \''.$depTime.'\', \''.$arrDate.'\', \''.$arrTime.'\', \''.$durText.'\', \''.$stopText.'\', \''.addslashes($cabinClass).'\')">
                    <div class="flight-header"><div class="airline-info"><div class="airline-icon">✈️</div><div><div class="airline-name">'.htmlspecialchars($offer['owner']['name'] ?? 'Airline').'</div></div></div><div class="flight-price">'.$offer['total_amount'].' '.$offer['total_currency'].'</div></div>
                    <div class="flight-route"><div><div class="city-code">'.$origin.'</div><div class="city-name">'.getAirportName($origin).'</div><div class="flight-time">'.$depTime.'</div></div>
                    <div class="flight-duration"><div class="duration-line"></div><div class="duration-text">'.$durText.'</div><div class="stops-text">'.$stopText.'</div></div>
                    <div><div class="city-code">'.$destination.'</div><div class="city-name">'.getAirportName($destination).'</div><div class="flight-time">'.$arrTime.'</div></div></div>
                    <button class="select-flight-btn">Select Flight</button></div>';
            }
        } else {
            $flightResults = '<div class="error">✈️ No flights found</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels | Book Flights</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f0f2f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .elegant-header { background: linear-gradient(135deg, #1a237e, #00695c); padding: 15px 0; position: sticky; top: 0; z-index: 1000; }
        .header-top-bar { display: flex; justify-content: space-between; align-items: center; color: white; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,0.1); flex-wrap: wrap; }
        .contact-info-elegant { display: flex; gap: 30px; flex-wrap: wrap; }
        .contact-info-elegant span { display: flex; align-items: center; gap: 8px; font-size: 14px; }
        .contact-info-elegant i { color: #d4af37; }
        .social-elegant a { color: white; margin-left: 15px; font-size: 16px; }
        .main-header-elegant { padding: 20px 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .logo-elegant { display: flex; align-items: center; gap: 20px; text-decoration: none; }
        .logo-icon-elegant { background: #d4af37; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; color: #1a237e; }
        .logo-main-elegant { font-family: 'Crimson Text', serif; font-size: 28px; font-weight: 700; color: white; }
        .logo-sub-elegant { font-size: 12px; color: #f5e8c8; letter-spacing: 2px; }
        .nav-elegant { display: flex; gap: 30px; flex-wrap: wrap; }
        .nav-elegant a { color: white; text-decoration: none; font-weight: 500; font-size: 15px; }
        .whatsapp-btn-elegant { background: #25D366; color: white; padding: 12px 24px; border-radius: 50px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; }
        .flying-marquee-container { background: linear-gradient(135deg, #1a237e, #0d1440); padding: 10px 0; border-bottom: 2px solid #d4af37; }
        .marquee-track { height: 35px; display: flex; align-items: center; overflow: hidden; position: relative; }
        .marquee-content { display: flex; animation: marqueeScroll 30s linear infinite; white-space: nowrap; }
        .marquee-text { color: white; font-size: 15px; padding: 0 25px; display: flex; align-items: center; }
        .marquee-text:before { content: '•'; color: #d4af37; margin-right: 15px; }
        .flying-plane { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); background: #d4af37; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: planeFloat 3s infinite; }
        .flying-plane i { color: #1a237e; font-size: 20px; transform: rotate(45deg); }
        .luxury-slider { height: 450px; position: relative; overflow: hidden; border-radius: 0 0 12px 12px; }
        .luxury-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-size: cover; background-position: center; opacity: 0; transition: opacity 1.2s; }
        .luxury-slide.active { opacity: 1; }
        .slide-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(26,35,126,0.85), rgba(0,105,92,0.85)); display: flex; align-items: center; }
        .slide-content-luxury { max-width: 600px; padding-left: 60px; color: white; }
        .slide-content-luxury h2 { font-size: 42px; margin-bottom: 20px; }
        .luxury-btn { display: inline-flex; align-items: center; gap: 12px; background: #d4af37; color: #1a237e; padding: 14px 28px; border-radius: 50px; font-weight: 600; text-decoration: none; }
        .slider-controls { position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; }
        .slider-dot-luxury { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.3); cursor: pointer; }
        .slider-dot-luxury.active { background: #d4af37; transform: scale(1.2); }
        .search-luxury { background: white; padding: 35px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); margin-top: -50px; position: relative; z-index: 10; margin-bottom: 40px; border-top: 4px solid #d4af37; }
        .form-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 600; color: #1a237e; margin-bottom: 8px; }
        .form-group select, .form-group input { width: 100%; padding: 14px 16px; border: 2px solid #e0e0e0; border-radius: 12px; font-size: 16px; }
        .search-btn { background: linear-gradient(135deg, #00695c, #1a237e); color: white; padding: 16px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; }
        .flight-card { background: white; border-radius: 16px; padding: 20px; margin-bottom: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); cursor: pointer; border: 1px solid #eee; }
        .flight-card:hover { transform: translateY(-5px); border-left: 4px solid #d4af37; }
        .flight-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 15px; }
        .airline-info { display: flex; align-items: center; gap: 15px; }
        .airline-icon { width: 45px; height: 45px; background: #1a237e; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; }
        .flight-price { font-size: 24px; font-weight: 800; color: #00695c; }
        .flight-route { display: flex; justify-content: space-between; align-items: center; gap: 20px; margin: 15px 0; flex-wrap: wrap; }
        .city-code { font-weight: 800; font-size: 20px; color: #1a237e; }
        .city-name { font-size: 11px; color: #666; }
        .flight-duration { text-align: center; flex: 1; }
        .duration-line { height: 2px; background: #d4af37; width: 100%; }
        .select-flight-btn { background: linear-gradient(135deg, #00695c, #1a237e); color: white; padding: 12px; border: none; border-radius: 50px; cursor: pointer; width: 100%; margin-top: 10px; }
        .error { background: #ffebee; padding: 15px; border-radius: 12px; color: #c62828; }
        .success-header { background: #e8f5e9; padding: 12px; border-radius: 12px; margin-bottom: 20px; color: #2e7d32; }
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); display: flex; align-items: center; justify-content: center; z-index: 10000; opacity: 0; visibility: hidden; transition: 0.3s; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content { background: white; padding: 35px; border-radius: 24px; max-width: 700px; width: 90%; position: relative; max-height: 90vh; overflow-y: auto; }
        .close-modal { position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 24px; cursor: pointer; }
        .booking-summary { background: #f8f9fa; padding: 20px; border-radius: 16px; margin-bottom: 25px; border-left: 4px solid #d4af37; }
        .price-breakdown { background: #f8f9fa; padding: 15px; border-radius: 12px; margin-top: 15px; }
        .price-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .price-row.total { font-weight: 700; color: #00695c; font-size: 18px; margin-top: 10px; padding-top: 10px; border-top: 2px solid #d4af37; }
        .booking-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .booking-form-group { margin-bottom: 15px; }
        .booking-form-group label { display: block; font-weight: 600; color: #1a237e; margin-bottom: 5px; font-size: 13px; }
        .booking-form-group input, .booking-form-group select { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 10px; font-size: 14px; }
        .confirm-btn { background: #25D366; color: white; padding: 16px; border: none; border-radius: 50px; font-size: 18px; font-weight: 600; cursor: pointer; width: 100%; }
        .footer-elegant { background: linear-gradient(135deg, #1a237e, #0d1440); color: white; padding: 40px 0; margin-top: 40px; text-align: center; }
        @keyframes marqueeScroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
        @keyframes planeFloat { 0%,100% { transform: translate(-50%, -50%) translateY(0); } 50% { transform: translate(-50%, -50%) translateY(-8px); } }
        @media (max-width: 768px) { .form-row { grid-template-columns: 1fr; } .booking-form-row { grid-template-columns: 1fr; } .nav-elegant { display: none; } .mobile-menu-toggle { display: block; background: #d4af37; padding: 12px; border-radius: 50px; text-align: center; width: fit-content; margin: 10px auto; cursor: pointer; } .nav-elegant.active { display: flex; flex-direction: column; background: rgba(26,35,126,0.95); padding: 20px; border-radius: 12px; } }
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
                <a href="https://wa.me/34611473217" class="whatsapp-btn-elegant"><i class="fab fa-whatsapp"></i> Book Now</a>
            </nav>
        </div>
    </div>
</header>

<div class="flying-marquee-container">
    <div class="marquee-track">
        <div class="marquee-content">
            <span class="marquee-text">✈️ SPECIAL DEALS ✈️ BCN TO LHE: €580 ✈️ BCN TO ISB: €585 ✈️ CALL +34-632234216 ✈️</span>
            <span class="marquee-text">✈️ SPECIAL DEALS ✈️ BCN TO LHE: €580 ✈️ BCN TO ISB: €585 ✈️ CALL +34-632234216 ✈️</span>
        </div>
        <div class="flying-plane"><i class="fas fa-plane"></i></div>
    </div>
</div>

<section class="luxury-slider" id="home">
    <div class="luxury-slide active" style="background-image: url('https://images.pexels.com/photos/33270402/pexels-photo-33270402.jpeg');">
        <div class="slide-overlay"><div class="slide-content-luxury"><h2>Premium Umrah 2026</h2><p>Luxury packages near Haram</p><a href="#umrah" class="luxury-btn">Explore</a></div></div>
    </div>
    <div class="slider-controls"><div class="slider-dot-luxury active"></div></div>
</section>

<div class="container">
    <div class="search-luxury">
        <h3 style="text-align:center; margin-bottom:20px;">✈️ Search Flights</h3>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group"><label>From</label><select name="origin" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                <div class="form-group"><label>To</label><select name="destination" class="airport-select"><?php foreach($airportsList as $a) echo '<option value="'.$a['code'].'">'.$a['code'].' - '.$a['name'].'</option>'; ?></select></div>
                <div class="form-group"><label>Departure</label><input type="date" name="departure_date" value="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"></div>
                <div class="form-group"><label>Passengers</label><select name="passengers"><option value="1">1 Adult</option><option value="2">2 Adults</option><option value="3">3 Adults</option></select></div>
                <div class="form-group"><label>Cabin</label><select name="cabin_class"><option value="economy">Economy</option><option value="business">Business</option></select></div>
            </div>
            <button type="submit" name="search_flights" class="search-btn">🔍 Search Flights</button>
        </form>
        <?php if ($searchPerformed): ?><div style="margin-top:30px"><?php echo $flightResults; ?></div><?php endif; ?>
    </div>
</div>

<footer class="footer-elegant">
    <div class="container">
        <p>&copy; 2026 Mustafa Travels & Tours | +34-632234216 | mustafatravelstours@gmail.com</p>
    </div>
</footer>

<div class="modal-overlay" id="bookingModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeBookingModal()">&times;</button>
        <h2 style="color: #1a237e;">✈️ Complete Booking</h2>
        <form method="POST" action="">
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
            
            <div class="booking-form-row">
                <div class="booking-form-group"><label>Title</label><select name="passenger_title"><option value="Mr">Mr</option><option value="Mrs">Mrs</option><option value="Ms">Ms</option></select></div>
                <div class="booking-form-group"><label>Given Name</label><input type="text" name="passenger_given_name" required></div>
                <div class="booking-form-group"><label>Family Name</label><input type="text" name="passenger_family_name" required></div>
            </div>
            <div class="booking-form-row">
                <div class="booking-form-group"><label>Date of Birth</label><input type="date" name="passenger_dob" required></div>
                <div class="booking-form-group"><label>Passport Number</label><input type="text" name="passenger_passport" required></div>
            </div>
            <div class="booking-form-row">
                <div class="booking-form-group"><label>Email</label><input type="email" name="contact_email" required></div>
                <div class="booking-form-group"><label>Phone</label><input type="tel" name="contact_phone" required></div>
            </div>
            
            <div class="price-breakdown" id="priceBreakdown"></div>
            <button type="submit" name="confirm_booking" class="confirm-btn">✅ Confirm Booking</button>
        </form>
    </div>
</div>

<script>
$(document).ready(function() { $('.airport-select').select2({ width: '100%' }); });

let currentSlide = 0;
const slides = document.querySelectorAll('.luxury-slide');
const dots = document.querySelectorAll('.slider-dot-luxury');
function showSlide(i) { if(!slides.length) return; slides.forEach(s=>s.classList.remove('active')); dots.forEach(d=>d.classList.remove('active')); slides[i].classList.add('active'); dots[i].classList.add('active'); }
if(slides.length) { setInterval(()=>{ currentSlide = (currentSlide+1)%slides.length; showSlide(currentSlide); }, 5000); dots.forEach((d,i)=>d.addEventListener('click',()=>showSlide(i))); }

document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() { document.querySelector('.nav-elegant')?.classList.toggle('active'); });

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
    
    let fare = Math.round(parseFloat(price) * 0.59);
    let taxes = parseFloat(price) - fare;
    document.getElementById('flightSummary').innerHTML = `<strong>✈️ Flight Details</strong><br>${airline}<br>${origin} → ${destination}<br>Departure: ${depDate} at ${depTime}<br>Arrival: ${arrDate} at ${arrTime}<br>Duration: ${duration}<br>Class: ${cabinClass}`;
    document.getElementById('priceBreakdown').innerHTML = `<div class="price-row"><span>Fare</span><span>${fare.toFixed(2)} ${currency}</span></div><div class="price-row"><span>Taxes</span><span>${taxes.toFixed(2)} ${currency}</span></div><div class="price-row total"><span>Total</span><span>${price} ${currency}</span></div>`;
    document.getElementById('bookingModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeBookingModal() { document.getElementById('bookingModal').classList.remove('active'); document.body.style.overflow = 'auto'; }
document.querySelectorAll('.close-modal, .modal-overlay').forEach(el => { el.addEventListener('click', (e) => { if(e.target === document.getElementById('bookingModal') || e.target.classList.contains('close-modal')) closeBookingModal(); }); });
showSlide(0);
</script>
</body>
</html>
