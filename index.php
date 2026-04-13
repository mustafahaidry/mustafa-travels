<?php
// index.php - Mustafa Travels & Tours Professional Website
// Inspired by Bukhari Travels - Clean, Decent, Professional

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Mustafa Travels & Tours | Premium Umrah, Flights & Travel Services Barcelona</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Mustafa Travels & Tours - Premium Umrah packages, flight tickets, visa assistance, and travel services from Barcelona. Economy Umrah €999, Luxury packages available.">
    <meta name="keywords" content="Umrah packages, flight tickets Barcelona, travel agency, Umrah 2026, Hajj 2026, visa assistance, eSIM, travel insurance">
    <meta name="author" content="Ghulam Mustafa Haidry">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Mustafa Travels & Tours - Premium Travel Services">
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
        
        /* Hero Section with Animation */
        .hero {
            background: linear-gradient(135deg, #0a4b6e 0%, #1e7e6c 100%);
            color: white;
            padding: 120px 60px;
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
        
        /* Airline Slider - Carousel */
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
            font-size: 50px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .airline-item span {
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
        }
        
        /* Clients Section */
        .clients-section {
            background: white;
            padding: 60px 0;
            text-align: center;
        }
        
        .clients-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 60px;
            flex-wrap: wrap;
            margin-top: 40px;
        }
        
        .client-logo {
            text-align: center;
            padding: 20px 30px;
            background: var(--light);
            border-radius: 16px;
            transition: all 0.3s;
            min-width: 150px;
        }
        
        .client-logo:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .client-logo i {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .client-logo h4 {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
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
        
        .package-badge {
            display: inline-block;
            background: var(--gold);
            color: var(--dark);
            padding: 5px 15px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 15px;
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
        
        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        
        .service-icon {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .service-card h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }
        
        /* Contact Section */
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
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #2a2a4a;
            font-size: 13px;
            color: #777;
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

<!-- Airline Slider - Arab, Asia, Africa, Latin America -->
<div class="airline-slider">
    <div class="slider-container">
        <div class="slider-track">
            <!-- Arab Airlines -->
            <div class="airline-item"><i class="fas fa-plane"></i><span>Emirates</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Etihad Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Qatar Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Saudi Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Flydubai</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Air Arabia</span></div>
            <!-- Asia Airlines -->
            <div class="airline-item"><i class="fas fa-plane"></i><span>Singapore Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Cathay Pacific</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Thai Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Malaysia Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Air India</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>PIA</span></div>
            <!-- Africa Airlines -->
            <div class="airline-item"><i class="fas fa-plane"></i><span>Ethiopian Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Kenya Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>EgyptAir</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Royal Air Maroc</span></div>
            <!-- Latin America Airlines -->
            <div class="airline-item"><i class="fas fa-plane"></i><span>LATAM</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Avianca</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Aeromexico</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Copa Airlines</span></div>
            <!-- Repeat for seamless loop -->
            <div class="airline-item"><i class="fas fa-plane"></i><span>Emirates</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Etihad Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Qatar Airways</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Saudi Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>Singapore Airlines</span></div>
            <div class="airline-item"><i class="fas fa-plane"></i><span>LATAM</span></div>
        </div>
    </div>
</div>

<!-- Our Clients Section -->
<div class="clients-section">
    <div class="container" style="padding: 0;">
        <h2 class="section-title">Some of Our Clients</h2>
        <div class="clients-grid">
            <div class="client-logo"><i class="fas fa-university"></i><h4>faysalbank</h4></div>
            <div class="client-logo"><i class="fas fa-utensils"></i><h4>foodpanda</h4></div>
            <div class="client-logo"><i class="fas fa-bank"></i><h4>HBL</h4></div>
            <div class="client-logo"><i class="fas fa-bank"></i><h4>HABIB BANK</h4></div>
        </div>
    </div>
</div>

<!-- Umrah Packages Preview -->
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
            <span class="emergency-badge" style="background: #dc2626; color: white; display: inline-block;">24/7 Available</span>
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
            <div class="package-header"><div class="package-name">ECONOMY PLUS</div><div class="package-price">€1,299</div><small>per person</small></div>
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
            <div class="package-header luxury-header"><div class="package-name">LUXURY</div><div class="package-price">Contact for Price</div><small>Premium Experience</small></div>
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

<?php elseif ($page == 'services'): ?>
<div class="container">
    <h2 class="section-title">Our Travel Services</h2>
    <p class="section-subtitle">Complete travel solutions under one roof</p>
    
    <div class="services-grid">
        <div class="service-card"><div class="service-icon"><i class="fas fa-plane"></i></div><h3>✈️ Flight Tickets</h3><p>Worldwide flight bookings at best prices</p><a href="https://aviasales.tpo.mx/HEyfp6zU" target="_blank" class="package-btn" style="margin-top: 15px;">Search →</a></div>
        <div class="service-card"><div class="service-icon"><i class="fas fa-hotel"></i></div><h3>🏨 Hotels</h3><p>2.5M+ hotels worldwide</p><a href="https://www.agoda.com/" target="_blank" class="package-btn" style="margin-top: 15px;">Book →</a></div>
        <div class="service-card"><div class="service-icon"><i class="fas fa-shield-alt"></i></div><h3>🛡️ Travel Insurance</h3><p>Medical & COVID coverage</p><a href="https://ektatraveling.tpo.mx/jyKgc1bx" target="_blank" class="package-btn" style="margin-top: 15px;">Get →</a></div>
        <div class="service-card"><div class="service-icon"><i class="fas fa-sim-card"></i></div><h3>📱 eSIM Internet</h3><p>Stay connected worldwide</p><a href="https://yesim.tpo.mx/t36uN2cA" target="_blank" class="package-btn" style="margin-top: 15px;">Buy →</a></div>
        <div class="service-card"><div class="service-icon"><i class="fas fa-ticket-alt"></i></div><h3>🎟️ Attraction Tickets</h3><p>Skip the line tickets</p><a href="https://tiqets.tpo.mx/pqSwPX5S" target="_blank" class="package-btn" style="margin-top: 15px;">Book →</a></div>
        <div class="service-card"><div class="service-icon"><i class="fas fa-kaaba"></i></div><h3>🕋 Umrah & Hajj</h3><p>Premium spiritual journeys</p><a href="?page=umrah" class="package-btn" style="margin-top: 15px;">View Packages →</a></div>
    </div>
</div>

<?php elseif ($page == 'contact'): ?>
<div class="container">
    <h2 class="section-title">Contact Us</h2>
    <div class="contact-grid">
        <div class="contact-card"><i class="fas fa-phone-alt"></i><h3>Emergency</h3><div class="number">+34-632234216</div><span class="emergency-badge" style="background: #dc2626; color: white; display: inline-block;">24/7</span></div>
        <div class="contact-card"><i class="fab fa-whatsapp"></i><h3>WhatsApp</h3><div class="number">+34-611473217</div></div>
        <div class="contact-card"><i class="fas fa-phone"></i><h3>Landline</h3><div class="number">937578907</div><p>Mon-Sat: 10:30-20:30</p></div>
    </div>
    <div style="background: var(--light); padding: 30px; border-radius: 20px; margin-top: 30px;">
        <p><strong>📍 Address:</strong> Rambla Badal 141 Local 1 Bajo, Barcelona 08028, Spain</p>
        <p><strong>📧 Email:</strong> mustafatravelstours@gmail.com</p>
        <p><strong>🔗 LinkedIn:</strong> <a href="https://www.linkedin.com/company/mustafa-travels-tours" target="_blank">Mustafa Travels & Tours</a></p>
    </div>
</div>
<?php endif; ?>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col"><h4>Mustafa Travels</h4><p>Premium travel agency in Barcelona since 2014. Umrah, flights, visas & worldwide travel.</p><div class="social-icons"><a href="https://www.facebook.com/mustafatravelstours"><i class="fab fa-facebook-f"></i></a><a href="https://www.tiktok.com/mustafatravelstarragona"><i class="fab fa-tiktok"></i></a><a href="https://www.instagram.com/mustafatravelstours"><i class="fab fa-instagram"></i></a><a href="https://www.linkedin.com/company/mustafa-travels-tours"><i class="fab fa-linkedin-in"></i></a></div></div>
        <div class="footer-col"><h4>Quick Links</h4><a href="?page=home">Home</a><a href="?page=umrah">Umrah</a><a href="?page=services">Services</a><a href="?page=contact">Contact</a></div>
        <div class="footer-col"><h4>Contact</h4><p>📞 Emergency: +34-632234216</p><p>📞 Office: 937578907</p><p>💬 WhatsApp: +34-611473217</p><p>✉️ mustafatravelstours@gmail.com</p></div>
        <div class="footer-col"><h4>Hours</h4><p>Mon-Sat: 10:30 - 20:30</p><p>Sun: Closed</p><p style="color: var(--gold);">Emergency: 24/7</p></div>
    </div>
    <div class="footer-bottom"><p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Service Charge: €15 per booking</p></div>
</footer>

<script>
    // Affiliate link tracking
    document.querySelectorAll('a[href*=".tpo.mx"], a[href*="aviasales"], a[href*="agoda"]').forEach(link => {
        link.addEventListener('click', function() {
            if(typeof gtag !== 'undefined') gtag('event', 'click', { 'event_category': 'affiliate' });
        });
    });
</script>
</body>
</html>
