<?php
// index.php - Mustafa Travels & Tours Professional Website
// Complete with Working Contact Form (No Google Form needed)

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Contact Form Submission Handling
$contact_success = '';
$contact_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $contact_error = 'Please fill in all required fields.';
    } else {
        // Send email (using mail() function - works on most servers)
        $to = 'mustafatravelstours@gmail.com';
        $subject = "New Contact Message from $name";
        $body = "Name: $name\n";
        $body .= "Email: $email\n";
        $body .= "Phone: $phone\n";
        $body .= "Message:\n$message\n";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        
        if (mail($to, $subject, $body, $headers)) {
            $contact_success = 'Thank you! We will get back to you soon.';
        } else {
            $contact_error = 'Unable to send message. Please call us directly.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Mustafa Travels & Tours | Premium Umrah, Flights & Travel Services Barcelona</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Mustafa Travels & Tours - Premium travel agency in Barcelona since 2024. Umrah packages from €999, flight tickets, visa assistance, and worldwide travel services. 24/7 support.">
    <meta name="keywords" content="Umrah packages, flight tickets Barcelona, travel agency, Umrah 2026, Hajj 2026, visa assistance, eSIM, travel insurance, hotel booking">
    <meta name="author" content="Ghulam Mustafa Haidry">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Mustafa Travels & Tours - Premium Travel Services Barcelona">
    <meta property="og:description" content="Umrah packages from €999 | Flight tickets | Visa assistance | 24/7 support">
    <meta property="og:type" content="website">
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-H7TQLKHP25"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-H7TQLKHP25');
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.5;
            color: #1a1a2e;
            background: #ffffff;
        }
        
        :root {
            --primary: #0a4b6e;
            --primary-dark: #063552;
            --secondary: #1e7e6c;
            --accent: #d4af37;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --gray: #6c757d;
            --gold: #d4af37;
            --teal: #1e7e6c;
        }
        
        /* Top Bar */
        .top-bar {
            background: var(--dark);
            color: white;
            padding: 10px 60px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            font-size: 13px;
        }
        
        .top-bar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            transition: color 0.3s;
        }
        
        .top-bar a:hover { color: var(--gold); }
        .emergency-badge {
            background: #dc2626;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 10px;
            margin-left: 8px;
        }
        
        /* Main Navigation */
        .main-header {
            background: white;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: -0.5px;
        }
        
        .logo p {
            font-size: 11px;
            color: var(--gray);
            letter-spacing: 2px;
        }
        
        .nav a {
            text-decoration: none;
            color: var(--dark);
            margin-left: 35px;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 15px;
        }
        
        .nav a:hover, .nav a.active {
            color: var(--primary);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #0a4b6e 0%, #1e7e6c 100%);
            color: white;
            padding: 100px 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }
        
        .hero h1 {
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 20px;
            position: relative;
        }
        
        .hero p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto 30px;
            opacity: 0.9;
            position: relative;
        }
        
        /* Search Widget */
        .search-container {
            max-width: 1100px;
            margin: -60px auto 0;
            position: relative;
            z-index: 10;
            padding: 0 20px;
        }
        
        .search-widget {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        /* Container */
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 80px 60px;
        }
        
        .section-title {
            text-align: center;
            font-size: 36px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .section-subtitle {
            text-align: center;
            color: var(--gray);
            margin-bottom: 50px;
            font-size: 18px;
        }
        
        .section-title:after {
            content: '';
            display: block;
            width: 70px;
            height: 3px;
            background: var(--gold);
            margin: 20px auto 0;
            border-radius: 2px;
        }
        
        /* Airline Slider */
        .airline-slider {
            background: var(--light);
            padding: 50px 0;
            overflow: hidden;
            position: relative;
        }
        
        .slider-container {
            overflow: hidden;
            width: 100%;
            position: relative;
        }
        
        .slider-track {
            display: flex;
            animation: scroll 25s linear infinite;
            width: fit-content;
        }
        
        .slider-track:hover {
            animation-play-state: paused;
        }
        
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .airline-item {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            margin: 0 30px;
            min-width: 100px;
        }
        
        .airline-item i {
            font-size: 45px;
            margin-bottom: 10px;
        }
        
        .airline-item span {
            font-size: 13px;
            font-weight: 500;
            color: var(--dark);
        }
        
        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .service-card {
            background: white;
            border-radius: 20px;
            padding: 35px 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border: 1px solid #eef2f6;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .service-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .service-card h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }
        
        .service-card p {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .service-btn {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 10px;
        }
        
        /* Umrah Packages */
        .packages-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 20px;
        }
        
        .package-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #eef2f6;
        }
        
        .package-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.12);
        }
        
        .package-card.luxury {
            border-top: 4px solid var(--gold);
        }
        
        .package-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .package-header.luxury-header {
            background: linear-gradient(135deg, #b8860b, var(--gold));
        }
        
        .package-name {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .package-price {
            font-size: 32px;
            font-weight: 800;
        }
        
        .package-price small {
            font-size: 14px;
            font-weight: 400;
        }
        
        .package-content {
            padding: 25px;
        }
        
        .feature-list {
            list-style: none;
            margin-bottom: 25px;
        }
        
        .feature-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eef2f6;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }
        
        .feature-list li i {
            width: 20px;
            color: var(--gold);
        }
        
        .package-btn {
            display: block;
            width: 100%;
            background: var(--primary);
            color: white;
            text-align: center;
            padding: 14px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .package-btn:hover {
            background: var(--primary-dark);
        }
        
        /* Contact Cards */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin: 40px 0;
        }
        
        .contact-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid #eef2f6;
        }
        
        .contact-card i {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .contact-card .number {
            font-size: 20px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        /* Google Map */
        .google-map {
            border-radius: 20px;
            overflow: hidden;
            margin: 30px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .google-map iframe {
            width: 100%;
            height: 350px;
            border: 0;
        }
        
        /* Working Contact Form */
        .contact-form-section {
            background: var(--light);
            padding: 40px;
            border-radius: 24px;
            margin: 30px 0;
        }
        
        .contact-form {
            max-width: 700px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .submit-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
        }
        
        .submit-btn:hover {
            background: var(--primary-dark);
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        /* CTA */
        .cta-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            text-align: center;
            padding: 60px;
            border-radius: 24px;
            margin: 40px 0;
            color: white;
        }
        
        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #25D366;
            color: white;
            padding: 14px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: transform 0.3s;
        }
        
        .whatsapp-btn:hover {
            transform: scale(1.05);
        }
        
        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 60px 60px 30px;
            margin-top: 60px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-col h4 {
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-col h4:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--gold);
        }
        
        .footer-col a, .footer-col p {
            color: #aaa;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .footer-col a:hover {
            color: var(--gold);
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .social-icons a {
            background: rgba(255,255,255,0.1);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background: var(--gold);
            color: var(--dark);
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #2a2a4a;
            font-size: 13px;
            color: #777;
        }
        
        /* About Page */
        .about-content {
            background: white;
            border-radius: 24px;
            padding: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .profile-img {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .profile-img img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--gold);
        }
        
        .stats-row {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            margin: 30px 0;
        }
        
        .stat-box {
            text-align: center;
            padding: 20px;
            background: var(--light);
            border-radius: 16px;
            min-width: 150px;
        }
        
        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
        }
        
        @media (max-width: 992px) {
            .packages-grid, .contact-grid, .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .main-header {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
            .nav a {
                margin: 0 15px;
            }
            .top-bar {
                padding: 10px 20px;
                text-align: center;
            }
            .hero {
                padding: 80px 20px;
            }
            .hero h1 {
                font-size: 32px;
            }
            .container {
                padding: 50px 20px;
            }
            .packages-grid, .contact-grid, .footer-grid {
                grid-template-columns: 1fr;
            }
            .section-title {
                font-size: 28px;
            }
            .contact-form-section {
                padding: 25px;
            }
        }
    </style>
</head>
<body>

<!-- Top Bar -->
<div class="top-bar">
    <div>
        <i class="fas fa-phone"></i> +34-632234216 <span class="emergency-badge">Emergency</span>
        <i class="fab fa-whatsapp" style="margin-left: 15px;"></i> +34-611473217
        <i class="fas fa-phone" style="margin-left: 15px;"></i> 937578907
    </div>
    <div>
        <i class="fas fa-map-marker-alt"></i> Rambla Badal 141, Barcelona, Spain
        <i class="fas fa-envelope" style="margin-left: 15px;"></i> mustafatravelstours@gmail.com
    </div>
</div>

<!-- Main Header -->
<div class="main-header">
    <div class="logo">
        <h1>MUSTAFA TRAVELS</h1>
        <p>PREMIUM TRAVEL EXPERIENCES</p>
    </div>
    <div class="nav">
        <a href="?page=home" class="<?php echo $page == 'home' ? 'active' : ''; ?>">Home</a>
        <a href="?page=umrah" class="<?php echo $page == 'umrah' ? 'active' : ''; ?>">Umrah</a>
        <a href="?page=services" class="<?php echo $page == 'services' ? 'active' : ''; ?>">Services</a>
        <a href="?page=about" class="<?php echo $page == 'about' ? 'active' : ''; ?>">About</a>
        <a href="?page=contact" class="<?php echo $page == 'contact' ? 'active' : ''; ?>">Contact</a>
    </div>
</div>

<?php if ($page == 'home'): ?>

<!-- Hero Section -->
<section class="hero">
    <h1>Your Spiritual Journey Begins Here</h1>
    <p>Premium Umrah Packages 2026 | Economy from €999 | Luxury Packages Available | 24/7 Support</p>
</section>

<!-- Search Widget -->
<div class="search-container">
    <div class="search-widget">
        <script async src="https://tp.media/content?currency=eur&trs=610290&shmarker=610290&locale=en&powered_by=true&border_radius=12&plain=true&color_button=%230a4b6e&color_icons=%230a4b6e&color_background=%23ffffff&color_text=%23000000&promo_id=7873"></script>
    </div>
</div>

<!-- Airline Slider -->
<div class="airline-slider">
    <div class="slider-container">
        <div class="slider-track">
            <div class="airline-item"><i class="fas fa-plane" style="color: #0a4b6e;"></i><span>Emirates</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #1e7e6c;"></i><span>Etihad Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #8B0000;"></i><span>Qatar Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #006633;"></i><span>Saudi Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #FF6600;"></i><span>Flydubai</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #003399;"></i><span>Air Arabia</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #FF0000;"></i><span>Singapore Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #990033;"></i><span>Cathay Pacific</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #800000;"></i><span>Thai Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #003399;"></i><span>Malaysia Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #FF9933;"></i><span>Air India</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #006600;"></i><span>PIA</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #0066CC;"></i><span>Ethiopian Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #009900;"></i><span>Kenya Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #CC0000;"></i><span>EgyptAir</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #006633;"></i><span>Royal Air Maroc</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #003399;"></i><span>LATAM</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #FF6600;"></i><span>Avianca</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #CC0000;"></i><span>Aeromexico</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #003399;"></i><span>Copa Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #0a4b6e;"></i><span>Emirates</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #1e7e6c;"></i><span>Etihad Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #8B0000;"></i><span>Qatar Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #006633;"></i><span>Saudi Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #FF0000;"></i><span>Singapore Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane" style="color: #003399;"></i><span>LATAM</span></div>
        </div>
    </div>
</div>

<!-- Services Section -->
<div class="container">
    <h2 class="section-title">Our Travel Services</h2>
    <p class="section-subtitle">Book with our trusted partners - Best prices guaranteed</p>
    
    <div class="services-grid">
        <a href="https://aviasales.tpo.mx/HEyfp6zU" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-plane" style="color: var(--primary);"></i></div>
            <h3>✈️ Flight Tickets</h3>
            <p>Compare 100+ airlines. Best prices worldwide</p>
            <span class="service-btn">Search Flights →</span>
        </a>
        
        <a href="https://www.agoda.com/?cid=YOUR_AGODA_ID" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-hotel" style="color: var(--primary);"></i></div>
            <h3>🏨 Hotel Booking</h3>
            <p>2.5M+ hotels worldwide. Best price guarantee</p>
            <span class="service-btn">Find Hotels →</span>
        </a>
        
        <a href="https://ektatraveling.tpo.mx/jyKgc1bx" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-shield-alt" style="color: var(--primary);"></i></div>
            <h3>🛡️ Travel Insurance</h3>
            <p>Medical coverage, COVID-19, lost baggage</p>
            <span class="service-btn">Get Insurance →</span>
        </a>
        
        <a href="https://yesim.tpo.mx/t36uN2cA" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-sim-card" style="color: var(--primary);"></i></div>
            <h3>📱 eSIM Internet</h3>
            <p>Stay connected worldwide. 200+ countries</p>
            <span class="service-btn">Buy eSIM →</span>
        </a>
        
        <a href="https://tiqets.tpo.mx/pqSwPX5S" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-ticket-alt" style="color: var(--primary);"></i></div>
            <h3>🎟️ Attraction Tickets</h3>
            <p>Skip-the-line tickets for top attractions</p>
            <span class="service-btn">Book Tickets →</span>
        </a>
        
        <a href="https://compensair.tpo.mx/lc5t7EhC" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-gavel" style="color: var(--primary);"></i></div>
            <h3>⚖️ Flight Compensation</h3>
            <p>Get up to €600 for delayed flights</p>
            <span class="service-btn">Check Claim →</span>
        </a>
    </div>
</div>

<!-- Umrah Packages -->
<div class="container">
    <h2 class="section-title">🕋 Premium Umrah Packages 2026</h2>
    <p class="section-subtitle">Experience spirituality with comfort near Haram</p>
    
    <div class="packages-grid">
        <div class="package-card">
            <div class="package-header">
                <div class="package-name">ECONOMY</div>
                <div class="package-price">€999</div>
                <small>per person</small>
            </div>
            <div class="package-content">
                <ul class="feature-list">
                    <li><i class="fas fa-star"></i> ⭐ 3* Hotel with Shuttle</li>
                    <li><i class="fas fa-kaaba"></i> 🕋 5 Days Makkah</li>
                    <li><i class="fas fa-mosque"></i> 🕌 3 Days Madina</li>
                    <li><i class="fas fa-plane"></i> ✈️ Air Ticket + Visa</li>
                </ul>
                <a href="https://wa.me/34611473217?text=I'm%20interested%20in%20ECONOMY%20Umrah%20Package%20(€999)" class="package-btn">Book Now →</a>
            </div>
        </div>
        
        <div class="package-card">
            <div class="package-header">
                <div class="package-name">ECONOMY PLUS</div>
                <div class="package-price">€1,299</div>
                <small>per person</small>
            </div>
            <div class="package-content">
                <ul class="feature-list">
                    <li><i class="fas fa-star"></i><i class="fas fa-star"></i> ⭐⭐ 4* Hotel</li>
                    <li><i class="fas fa-kaaba"></i> 🕋 5 Days Makkah</li>
                    <li><i class="fas fa-mosque"></i> 🕌 3 Days Madina</li>
                    <li><i class="fas fa-plane"></i> ✈️ Air Ticket + Visa</li>
                    <li><i class="fas fa-taxi"></i> 🚖 Airport Pickup</li>
                </ul>
                <a href="https://wa.me/34611473217?text=I'm%20interested%20in%20ECONOMY%20PLUS%20Umrah%20Package%20(€1299)" class="package-btn">Book Now →</a>
            </div>
        </div>
        
        <div class="package-card luxury">
            <div class="package-header luxury-header">
                <div class="package-name">LUXURY</div>
                <div class="package-price">Contact for Price</div>
                <small>Premium Experience</small>
            </div>
            <div class="package-content">
                <ul class="feature-list">
                    <li><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> ⭐⭐⭐⭐ 4* & 5* Hotels</li>
                    <li><i class="fas fa-kaaba"></i> 🕋 5 Days Makkah</li>
                    <li><i class="fas fa-mosque"></i> 🕌 3 Days Madina</li>
                    <li><i class="fas fa-plane"></i> ✈️ Air Ticket + Visa</li>
                    <li><i class="fas fa-utensils"></i> 🍳 Breakfast Included</li>
                    <li><i class="fas fa-car"></i> 🚖 Airport + City Transport</li>
                </ul>
                <a href="https://wa.me/34611473217?text=I'm%20interested%20in%20LUXURY%20Umrah%20Package" class="package-btn">Contact for Price →</a>
            </div>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="container">
    <h2 class="section-title">Contact Our Travel Experts</h2>
    <p class="section-subtitle">We're here 24/7 to assist you</p>
    
    <div class="contact-grid">
        <div class="contact-card">
            <i class="fas fa-phone-alt"></i>
            <h3>Emergency</h3>
            <div class="number">+34-632234216</div>
            <span style="background: #dc2626; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px; display: inline-block;">24/7 Available</span>
        </div>
        <div class="contact-card">
            <i class="fab fa-whatsapp"></i>
            <h3>WhatsApp</h3>
            <div class="number">+34-611473217</div>
            <p>Quick responses</p>
        </div>
        <div class="contact-card">
            <i class="fas fa-phone"></i>
            <h3>Landline</h3>
            <div class="number">937578907</div>
            <p>Mon-Sat: 10:30-20:30</p>
        </div>
        <div class="contact-card">
            <i class="fab fa-linkedin"></i>
            <h3>LinkedIn</h3>
            <div class="number" style="font-size: 16px;">Mustafa Travels</div>
            <a href="https://www.linkedin.com/company/mustafa-travels-tours" target="_blank" style="color: var(--primary);">Follow →</a>
        </div>
    </div>
</div>

<!-- CTA -->
<div class="container">
    <div class="cta-section">
        <h2>Need Help Planning Your Trip?</h2>
        <p>Contact our travel experts for personalized assistance</p>
        <a href="https://wa.me/34611473217" class="whatsapp-btn" target="_blank">
            <i class="fab fa-whatsapp" style="font-size: 24px;"></i> Chat on WhatsApp
        </a>
    </div>
</div>

<?php elseif ($page == 'umrah'): ?>
<div class="container">
    <h2 class="section-title">🕋 Premium Umrah Packages 2026</h2>
    <p class="section-subtitle">Choose the perfect package for your spiritual journey</p>
    
    <div class="packages-grid">
        <div class="package-card">
            <div class="package-header"><div class="package-name">ECONOMY</div><div class="package-price">€999</div><small>per person</small></div>
            <div class="package-content">
                <ul class="feature-list"><li><i class="fas fa-star"></i> ⭐ 3* Hotel with Shuttle</li><li><i class="fas fa-kaaba"></i> 🕋 5 Days Makkah</li><li><i class="fas fa-mosque"></i> 🕌 3 Days Madina</li><li><i class="fas fa-plane"></i> ✈️ Air Ticket + Visa</li></ul>
                <a href="https://wa.me/34611473217?text=I'm%20interested%20in%20ECONOMY%20Umrah%20Package%20(€999)" class="package-btn">Book Now →</a>
            </div>
        </div>
        <div class="package-card">
            <div class="package-header"><div class="package-name">ECONOMY PLUS</div><div class="package-price">€1,299</div><small>per person</small></div>
            <div class="package-content">
                <ul class="feature-list"><li><i class="fas fa-star"></i><i class="fas fa-star"></i> ⭐⭐ 4* Hotel</li><li><i class="fas fa-kaaba"></i> 🕋 5 Days Makkah</li><li><i class="fas fa-mosque"></i> 🕌 3 Days Madina</li><li><i class="fas fa-plane"></i> ✈️ Air Ticket + Visa</li><li><i class="fas fa-taxi"></i> 🚖 Airport Pickup</li></ul>
                <a href="https://wa.me/34611473217?text=I'm%20interested%20in%20ECONOMY%20PLUS%20Umrah%20Package%20(€1299)" class="package-btn">Book Now →</a>
            </div>
        </div>
        <div class="package-card luxury">
            <div class="package-header luxury-header"><div class="package-name">LUXURY</div><div class="package-price">Contact for Price</div><small>Premium Experience</small></div>
            <div class="package-content">
                <ul class="feature-list"><li><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i> ⭐⭐⭐⭐ 4* & 5* Hotels</li><li><i class="fas fa-kaaba"></i> 🕋 5 Days Makkah</li><li><i class="fas fa-mosque"></i> 🕌 3 Days Madina</li><li><i class="fas fa-plane"></i> ✈️ Air Ticket + Visa</li><li><i class="fas fa-utensils"></i> 🍳 Breakfast Included</li><li><i class="fas fa-car"></i> 🚖 Airport + City Transport</li></ul>
                <a href="https://wa.me/34611473217?text=I'm%20interested%20in%20LUXURY%20Umrah%20Package" class="package-btn">Contact for Price →</a>
            </div>
        </div>
    </div>
</div>

<?php elseif ($page == 'services'): ?>
<div class="container">
    <h2 class="section-title">Our Travel Services</h2>
    <p class="section-subtitle">Complete travel solutions under one roof</p>
    
    <div class="services-grid">
        <a href="https://aviasales.tpo.mx/HEyfp6zU" target="_blank" class="service-card"><div class="service-icon"><i class="fas fa-plane"></i></div><h3>✈️ Flight Tickets</h3><p>Worldwide flight bookings</p><span class="service-btn">Search →</span></a>
        <a href="https://www.agoda.com/?cid=YOUR_AGODA_ID" target="_blank" class="service-card"><div class="service-icon"><i class="fas fa-hotel"></i></div><h3>🏨 Hotels</h3><p>2.5M+ hotels worldwide</p><span class="service-btn">Book →</span></a>
        <a href="https://ektatraveling.tpo.mx/jyKgc1bx" target="_blank" class="service-card"><div class="service-icon"><i class="fas fa-shield-alt"></i></div><h3>🛡️ Travel Insurance</h3><p>Medical & COVID coverage</p><span class="service-btn">Get →</span></a>
        <a href="https://yesim.tpo.mx/t36uN2cA" target="_blank" class="service-card"><div class="service-icon"><i class="fas fa-sim-card"></i></div><h3>📱 eSIM Internet</h3><p>Stay connected worldwide</p><span class="service-btn">Buy →</span></a>
        <a href="https://tiqets.tpo.mx/pqSwPX5S" target="_blank" class="service-card"><div class="service-icon"><i class="fas fa-ticket-alt"></i></div><h3>🎟️ Attraction Tickets</h3><p>Skip the line tickets</p><span class="service-btn">Book →</span></a>
        <a href="https://compensair.tpo.mx/lc5t7EhC" target="_blank" class="service-card"><div class="service-icon"><i class="fas fa-gavel"></i></div><h3>⚖️ Flight Compensation</h3><p>Get up to €600</p><span class="service-btn">Check →</span></a>
    </div>
</div>

<?php elseif ($page == 'about'): ?>
<div class="container">
    <div class="about-content">
        <div class="profile-img">
            <img src="https://i.postimg.cc/sxd9YgRx/mustafa.jpg" alt="Ghulam Mustafa Haidry">
        </div>
        <h1 style="text-align: center; margin-bottom: 20px;">About Mustafa Travels & Tours</h1>
        
        <p style="text-align: center; font-size: 18px; margin-bottom: 30px;">Premium travel agency based in Barcelona since 2024</p>
        
        <h2>Founder's Message</h2>
        <p>My name is <strong>Ghulam Mustafa Haidry</strong>. I started working as a travel agent in <strong>2019</strong> and founded my own travel agency <strong>Mustafa Travels & Tours</strong> in <strong>January 2024</strong>. With over 5 years of experience in the travel industry, I have served more than <strong>500 Umrah clients</strong> with a <strong>4.9/5 rating</strong>.</p>
        
        <h2>Our Services</h2>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li>✈️ Airline tickets worldwide</li>
            <li>🏨 Hotel reservations globally</li>
            <li>🚌 Bus, Train, Tram, and Ship tickets</li>
            <li>🛡️ Travel insurance</li>
            <li>🛂 Visa assistance for UK, USA, Canada, Japan Files</li>
            <li>🌍 E-visa for Pakistan, India, Turkey, Morocco, Malaysia</li>
            <li>🕋 Hajj and Umrah packages</li>
        </ul>
        
        <h2>Our Commitment</h2>
        <p>We are available <strong>24/7 for emergency services</strong>. Our team is dedicated to providing you with the best travel experience at competitive prices.</p>
        
        <div class="stats-row">
            <div class="stat-box"><div class="stat-number">500+</div><div>Umrah Clients</div></div>
            <div class="stat-box"><div class="stat-number">4.9/5</div><div>Customer Rating</div></div>
            <div class="stat-box"><div class="stat-number">24/7</div><div>Emergency Support</div></div>
            <div class="stat-box"><div class="stat-number">10+</div><div>Years Experience</div></div>
        </div>
    </div>
</div>

<?php elseif ($page == 'contact'): ?>
<div class="container">
    <h2 class="section-title">Contact Us</h2>
    <p class="section-subtitle">Get in touch with our travel experts</p>
    
    <div class="contact-grid">
        <div class="contact-card"><i class="fas fa-phone-alt"></i><h3>Emergency</h3><div class="number">+34-632234216</div><span style="background: #dc2626; color: white; padding: 4px 12px; border-radius: 20px; font-size: 11px;">24/7</span></div>
        <div class="contact-card"><i class="fab fa-whatsapp"></i><h3>WhatsApp</h3><div class="number">+34-611473217</div></div>
        <div class="contact-card"><i class="fas fa-phone"></i><h3>Landline</h3><div class="number">937578907</div><p>Mon-Sat: 10:30-20:30</p></div>
        <div class="contact-card"><i class="fas fa-envelope"></i><h3>Email</h3><div class="number" style="font-size: 14px;">mustafatravelstours@gmail.com</div></div>
    </div>
    
    <!-- Google Map -->
    <div class="google-map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2992.456789012345!2d2.123456789012345!3d41.37812345678901!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12a4a2e0e0e0e0e0%3A0x1234567890abcdef!2sRambla%20Badal%20141%2C%20Barcelona!5e0!3m2!1sen!2ses!4v1234567890123!5m2!1sen!2ses" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    
    <!-- Working Contact Form (Alternative to Google Form) -->
    <div class="contact-form-section">
        <h3 style="text-align: center; margin-bottom: 30px; color: var(--dark);">Send us a Message</h3>
        
        <?php if ($contact_success): ?>
            <div class="alert-success"><?php echo htmlspecialchars($contact_success); ?></div>
        <?php endif; ?>
        
        <?php if ($contact_error): ?>
            <div class="alert-error"><?php echo htmlspecialchars($contact_error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="contact-form">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" required placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" required placeholder="Enter your email address">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" placeholder="Enter your phone number">
            </div>
            <div class="form-group">
                <label>Message *</label>
                <textarea name="message" required placeholder="Tell us about your travel plans..."></textarea>
            </div>
            <button type="submit" name="contact_submit" class="submit-btn">Send Message →</button>
        </form>
        <p style="text-align: center; margin-top: 20px; font-size: 13px; color: var(--gray);">Or call us directly: +34-632234216 (24/7 Emergency)</p>
    </div>
</div>
<?php endif; ?>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col"><h4>Mustafa Travels</h4><p>Premium travel agency in Barcelona since 2024. Umrah, flights, visas & worldwide travel.</p><div class="social-icons"><a href="https://www.facebook.com/mustafatravelstours"><i class="fab fa-facebook-f"></i></a><a href="https://www.tiktok.com/mustafatravelstarragona"><i class="fab fa-tiktok"></i></a><a href="https://www.instagram.com/mustafatravelstours"><i class="fab fa-instagram"></i></a><a href="https://www.linkedin.com/company/mustafa-travels-tours"><i class="fab fa-linkedin-in"></i></a></div></div>
        <div class="footer-col"><h4>Quick Links</h4><a href="?page=home">Home</a><a href="?page=umrah">Umrah Packages</a><a href="?page=services">Services</a><a href="?page=about">About</a><a href="?page=contact">Contact</a></div>
        <div class="footer-col"><h4>Contact</h4><p>📞 Emergency: +34-632234216</p><p>📞 Office: 937578907</p><p>💬 WhatsApp: +34-611473217</p><p>✉️ mustafatravelstours@gmail.com</p></div>
        <div class="footer-col"><h4>Hours</h4><p>Mon-Sat: 10:30 - 20:30</p><p>Sun: Closed</p><p style="color: var(--gold);">Emergency: 24/7</p></div>
    </div>
    <div class="footer-bottom"><p>&copy; 2024 Mustafa Travels & Tours. All rights reserved. | Service Charge: €15 per booking</p></div>
</footer>

<script>
    document.querySelectorAll('a[href*=".tpo.mx"], a[href*="aviasales"], a[href*="agoda"]').forEach(link => {
        link.addEventListener('click', function() {
            if(typeof gtag !== 'undefined') gtag('event', 'click', { 'event_category': 'affiliate' });
        });
    });
</script>
</body>
</html>
