<?php
// index.php - Mustafa Travels & Tours Professional Website
// Dynamic, Modern, Fully Responsive Travel Agency Website

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Mustafa Travels & Tours | Professional Travel Agency Barcelona</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Mustafa Travels & Tours - Professional travel agency in Barcelona. Flight tickets, hotels, visa assistance, Umrah & Hajj packages. 24/7 support.">
    <meta name="keywords" content="travel agency Barcelona, flight tickets, Umrah packages, Hajj 2026, visa assistance, hotel booking">
    <meta name="author" content="Ghulam Mustafa Haidry">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Mustafa Travels & Tours - Your Trusted Travel Partner">
    <meta property="og:description" content="Professional travel agency in Barcelona. Flight tickets, hotels, visas, Umrah & Hajj.">
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
        
        /* Custom Colors */
        :root {
            --primary: #0066cc;
            --primary-dark: #004d99;
            --secondary: #00b4d8;
            --accent: #ff6b35;
            --dark: #1a1a2e;
            --light: #f8f9fa;
            --gray: #6c757d;
            --gold: #d4af37;
        }
        
        /* Header */
        .top-bar {
            background: var(--dark);
            color: white;
            padding: 10px 40px;
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
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--dark);
        }
        
        .logo p {
            font-size: 11px;
            color: var(--gray);
            letter-spacing: 1px;
        }
        
        .nav a {
            text-decoration: none;
            color: var(--dark);
            margin-left: 30px;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav a:hover, .nav a.active {
            color: var(--primary);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            margin-bottom: 20px;
        }
        
        .hero p {
            font-size: 18px;
            max-width: 600px;
            margin: 0 auto 30px;
            opacity: 0.9;
        }
        
        /* Search Widget */
        .search-container {
            max-width: 1100px;
            margin: -50px auto 0;
            position: relative;
            z-index: 10;
            padding: 0 20px;
        }
        
        .search-widget {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        /* Container */
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 80px 40px;
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
            width: 60px;
            height: 3px;
            background: var(--primary);
            margin: 20px auto 0;
            border-radius: 2px;
        }
        
        /* Service Cards */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .service-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
            border: 1px solid #eef2f6;
        }
        
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .service-icon {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 35px;
            text-align: center;
            font-size: 45px;
        }
        
        .service-content {
            padding: 25px;
            text-align: center;
        }
        
        .service-content h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }
        
        .service-content p {
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
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .service-btn:hover {
            background: var(--primary-dark);
        }
        
        /* Contact Cards */
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }
        
        .contact-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid #eef2f6;
            transition: transform 0.3s;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
        }
        
        .contact-card i {
            font-size: 45px;
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        .contact-card .number {
            font-size: 22px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .emergency-tag {
            background: #fee2e2;
            color: #dc2626;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            display: inline-block;
            margin-top: 10px;
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 60px 40px;
            border-radius: 30px;
            margin: 40px 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 42px;
            font-weight: 800;
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        /* Visa Services */
        .visa-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .visa-card {
            background: var(--light);
            padding: 25px;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s;
        }
        
        .visa-card:hover {
            background: white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        
        .visa-card i {
            font-size: 35px;
            margin-bottom: 10px;
        }
        
        /* CTA Section */
        .cta-section {
            background: var(--light);
            text-align: center;
            padding: 60px;
            border-radius: 30px;
            margin: 40px 0;
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
            padding: 60px 40px 30px;
            margin-top: 60px;
        }
        
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            transition: color 0.3s;
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
        
        /* Page Content */
        .page-content {
            background: white;
            padding: 60px;
            border-radius: 30px;
            margin: 40px auto;
            max-width: 1000px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        
        .profile-img {
            text-align: center;
            margin: 30px 0;
        }
        
        .profile-img img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--gold);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .main-header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            .nav a {
                margin: 0 12px;
            }
            .top-bar {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }
            .hero h1 {
                font-size: 36px;
            }
            .container {
                padding: 50px 20px;
            }
            .section-title {
                font-size: 28px;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .page-content {
                padding: 30px;
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
        <i class="fas fa-phone" style="margin-left: 15px;"></i> 937578907 <span style="font-size: 11px;">(Office)</span>
    </div>
    <div>
        <i class="fas fa-map-marker-alt"></i> Rambla Badal 141, Barcelona, Spain
        <i class="fas fa-envelope" style="margin-left: 15px;"></i> mustafatravelstours@gmail.com
    </div>
</div>

<!-- Main Header -->
<div class="main-header">
    <div class="logo">
        <h1>MUSTAFA TRAVELS & TOURS</h1>
        <p>PREMIUM TRAVEL EXPERIENCES</p>
    </div>
    <div class="nav">
        <a href="?page=home" class="<?php echo $page == 'home' ? 'active' : ''; ?>">Home</a>
        <a href="?page=about" class="<?php echo $page == 'about' ? 'active' : ''; ?>">About</a>
        <a href="?page=services" class="<?php echo $page == 'services' ? 'active' : ''; ?>">Services</a>
        <a href="?page=contact" class="<?php echo $page == 'contact' ? 'active' : ''; ?>">Contact</a>
    </div>
</div>

<?php if ($page == 'home'): ?>

<!-- Hero Section -->
<section class="hero">
    <h1>Your Journey Begins Here</h1>
    <p>Discover the world with Mustafa Travels & Tours - Your trusted travel partner in Barcelona</p>
</section>

<!-- Search Widget -->
<div class="search-container">
    <div class="search-widget">
        <script async src="https://tp.media/content?currency=eur&trs=610290&shmarker=610290&locale=en&powered_by=true&border_radius=12&plain=true&color_button=%23006B3D&color_icons=%23006B3D&color_background=%23ffffff&color_text=%23000000&promo_id=7873"></script>
    </div>
</div>

<!-- Contact Cards -->
<div class="container">
    <h2 class="section-title">Contact Our Experts</h2>
    <p class="section-subtitle">Multiple ways to reach us - We're here 24/7</p>
    
    <div class="contact-grid">
        <div class="contact-card">
            <i class="fas fa-phone-alt"></i>
            <h3>Emergency</h3>
            <div class="number">+34-632234216</div>
            <span class="emergency-tag">24/7 Available</span>
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
            <a href="https://www.linkedin.com/company/mustafa-travels-tours" target="_blank" class="service-btn" style="margin-top: 10px;">Follow →</a>
        </div>
    </div>
</div>

<!-- Services Section -->
<div class="container">
    <h2 class="section-title">Our Premium Services</h2>
    <p class="section-subtitle">Complete travel solutions under one roof</p>
    
    <div class="services-grid">
        <a href="https://aviasales.tpo.mx/HEyfp6zU" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-plane"></i></div>
            <div class="service-content">
                <h3>Flight Tickets</h3>
                <p>Best prices on 100+ airlines</p>
                <span class="service-btn">Search →</span>
            </div>
        </a>
        
        <a href="https://www.agoda.com/?cid=YOUR_AGODA_ID" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-hotel"></i></div>
            <div class="service-content">
                <h3>Hotels</h3>
                <p>2.5M+ hotels worldwide</p>
                <span class="service-btn">Book →</span>
            </div>
        </a>
        
        <a href="https://ektatraveling.tpo.mx/jyKgc1bx" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="service-content">
                <h3>Travel Insurance</h3>
                <p>Medical & COVID coverage</p>
                <span class="service-btn">Get →</span>
            </div>
        </a>
        
        <a href="https://yesim.tpo.mx/t36uN2cA" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-sim-card"></i></div>
            <div class="service-content">
                <h3>eSIM Internet</h3>
                <p>Stay connected worldwide</p>
                <span class="service-btn">Buy →</span>
            </div>
        </a>
        
        <a href="https://tiqets.tpo.mx/pqSwPX5S" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-ticket-alt"></i></div>
            <div class="service-content">
                <h3>Attraction Tickets</h3>
                <p>Skip the line!</p>
                <span class="service-btn">Book →</span>
            </div>
        </a>
        
        <div class="service-card" style="cursor: default;">
            <div class="service-icon" style="background: linear-gradient(135deg, #1a4a7a, #0a2540);"><i class="fas fa-kaaba"></i></div>
            <div class="service-content">
                <h3>Umrah & Hajj</h3>
                <p>Premium spiritual journeys</p>
                <a href="https://wa.me/34611473217" class="service-btn">Inquire →</a>
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="container">
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat">
                <div class="stat-number">500+</div>
                <div class="stat-label">Umrah Clients</div>
            </div>
            <div class="stat">
                <div class="stat-number">4.9/5</div>
                <div class="stat-label">Customer Rating</div>
            </div>
            <div class="stat">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Emergency Support</div>
            </div>
            <div class="stat">
                <div class="stat-number">50+</div>
                <div class="stat-label">Destinations</div>
            </div>
        </div>
    </div>
</div>

<!-- Visa Services -->
<div class="container">
    <h2 class="section-title">Visa Assistance</h2>
    <p class="section-subtitle">We help you with visa applications worldwide</p>
    
    <div class="visa-grid">
        <div class="visa-card"><i class="fas fa-passport"></i><h4>🇬🇧 UK Visa</h4></div>
        <div class="visa-card"><i class="fas fa-passport"></i><h4>🇺🇸 USA Visa</h4></div>
        <div class="visa-card"><i class="fas fa-passport"></i><h4>🇨🇦 Canada Visa</h4></div>
        <div class="visa-card"><i class="fas fa-passport"></i><h4>🇯🇵 Japan Visa</h4></div>
        <div class="visa-card"><i class="fas fa-globe"></i><h4>E-Visa Pakistan</h4></div>
        <div class="visa-card"><i class="fas fa-globe"></i><h4>E-Visa India</h4></div>
        <div class="visa-card"><i class="fas fa-globe"></i><h4>E-Visa Turkey</h4></div>
        <div class="visa-card"><i class="fas fa-ship"></i><h4>Train & Bus Tickets</h4></div>
    </div>
</div>

<!-- CTA Section -->
<div class="container">
    <div class="cta-section">
        <h2>Need Help Planning Your Trip?</h2>
        <p>Contact our travel experts directly on WhatsApp for personalized assistance</p>
        <a href="https://wa.me/34611473217" class="whatsapp-btn" target="_blank">
            <i class="fab fa-whatsapp" style="font-size: 24px;"></i> Chat on WhatsApp
        </a>
        <p style="margin-top: 20px; font-size: 14px;">
            📞 Emergency: +34-632234216 | 🏢 Office: 937578907
        </p>
    </div>
</div>

<?php elseif ($page == 'about'): ?>
<div class="page-content">
    <div class="profile-img">
        <img src="https://i.postimg.cc/sxd9YgRx/mustafa.jpg" alt="Ghulam Mustafa Haidry">
    </div>
    <h1 style="text-align: center;">About Mustafa Travels & Tours</h1>
    
    <h2>Founder's Message</h2>
    <p>My name is <strong>Ghulam Mustafa Haidry</strong>. I started working as a travel agent in <strong>2019</strong> and founded my own travel agency <strong>Mustafa Travels & Tours</strong> in <strong>January 2024</strong>. With over 5 years of experience, I have served more than <strong>500 Umrah clients</strong> with a <strong>4.9/5 rating</strong>.</p>
    
    <h2>Our Services</h2>
    <ul>
        <li>✈️ Airline tickets worldwide</li>
        <li>🏨 Hotel reservations globally</li>
        <li>🚌 Bus, Train, Tram, and Ship tickets</li>
        <li>🛡️ Travel insurance</li>
        <li>🛂 Visa assistance for UK, USA, Canada, Japan</li>
        <li>🌍 E-visa for Pakistan, India, Turkey, Morocco, Malaysia</li>
        <li>🕋 Hajj and Umrah packages</li>
    </ul>
    
    <h2>Our Commitment</h2>
    <p>We are available <strong>24/7 for emergency services</strong>. Our team is dedicated to providing you with the best travel experience at competitive prices.</p>
</div>

<?php elseif ($page == 'services'): ?>
<div class="container">
    <h2 class="section-title">Our Services</h2>
    <p class="section-subtitle">Complete travel solutions under one roof</p>
    
    <div class="services-grid">
        <a href="https://aviasales.tpo.mx/HEyfp6zU" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-plane"></i></div>
            <div class="service-content"><h3>✈️ Flight Tickets</h3><p>Worldwide flight bookings</p><span class="service-btn">Search →</span></div>
        </a>
        <a href="https://www.agoda.com/?cid=YOUR_AGODA_ID" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-hotel"></i></div>
            <div class="service-content"><h3>🏨 Hotels</h3><p>2.5M+ hotels</p><span class="service-btn">Book →</span></div>
        </a>
        <a href="https://ektatraveling.tpo.mx/jyKgc1bx" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-shield-alt"></i></div>
            <div class="service-content"><h3>🛡️ Insurance</h3><p>Travel insurance</p><span class="service-btn">Get →</span></div>
        </a>
        <a href="https://yesim.tpo.mx/t36uN2cA" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-sim-card"></i></div>
            <div class="service-content"><h3>📱 eSIM</h3><p>Internet worldwide</p><span class="service-btn">Buy →</span></div>
        </a>
        <a href="https://tiqets.tpo.mx/pqSwPX5S" target="_blank" class="service-card">
            <div class="service-icon"><i class="fas fa-ticket-alt"></i></div>
            <div class="service-content"><h3>🎟️ Tickets</h3><p>Attraction tickets</p><span class="service-btn">Book →</span></div>
        </a>
        <div class="service-card" style="cursor: default;">
            <div class="service-icon"><i class="fas fa-kaaba"></i></div>
            <div class="service-content"><h3>🕋 Umrah & Hajj</h3><p>Spiritual travel</p><a href="https://wa.me/34611473217" class="service-btn">Inquire →</a></div>
        </div>
    </div>
</div>

<?php elseif ($page == 'contact'): ?>
<div class="page-content">
    <h1>Contact Us</h1>
    
    <div class="contact-grid" style="margin-bottom: 30px;">
        <div class="contact-card">
            <i class="fas fa-phone-alt"></i>
            <h3>Emergency</h3>
            <div class="number">+34-632234216</div>
            <span class="emergency-tag">24/7 Available</span>
        </div>
        <div class="contact-card">
            <i class="fab fa-whatsapp"></i>
            <h3>WhatsApp</h3>
            <div class="number">+34-611473217</div>
        </div>
        <div class="contact-card">
            <i class="fas fa-phone"></i>
            <h3>Landline</h3>
            <div class="number">937578907</div>
            <p>Mon-Sat: 10:30-20:30</p>
        </div>
    </div>
    
    <div class="contact-info-box" style="background: #f0f7ff; padding: 25px; border-radius: 16px;">
        <p><strong>📍 Address:</strong> Rambla Badal 141 Local 1 Bajo, Barcelona 08028, Spain</p>
        <p><strong>📧 Email:</strong> mustafatravelstours@gmail.com</p>
        <p><strong>🔗 LinkedIn:</strong> <a href="https://www.linkedin.com/company/mustafa-travels-tours" target="_blank">Mustafa Travels & Tours</a></p>
    </div>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="https://wa.me/34611473217" class="whatsapp-btn" style="display: inline-flex;">Chat on WhatsApp</a>
    </div>
</div>
<?php endif; ?>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <h4>Mustafa Travels & Tours</h4>
            <p>Professional travel agency in Barcelona since 2014.</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/mustafatravelstours"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.tiktok.com/mustafatravelstarragona"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.instagram.com/mustafatravelstours"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/company/mustafa-travels-tours"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Quick Links</h4>
            <a href="?page=home">Home</a>
            <a href="?page=about">About Us</a>
            <a href="?page=services">Services</a>
            <a href="?page=contact">Contact</a>
        </div>
        <div class="footer-col">
            <h4>Contact</h4>
            <p>📞 Emergency: +34-632234216</p>
            <p>📞 Office: 937578907</p>
            <p>💬 WhatsApp: +34-611473217</p>
            <p>✉️ mustafatravelstours@gmail.com</p>
        </div>
        <div class="footer-col">
            <h4>Hours</h4>
            <p>Mon-Sat: 10:30 - 20:30</p>
            <p>Sun: Closed</p>
            <p style="color: #ff6b35;">Emergency: 24/7</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Service Charge: €15 per booking</p>
    </div>
</footer>

<script>
    document.querySelectorAll('a[href*=".tpo.mx"], a[href*="aviasales"]').forEach(link => {
        link.addEventListener('click', function() {
            if(typeof gtag !== 'undefined') gtag('event', 'click', { 'event_category': 'affiliate' });
        });
    });
</script>
</body>
</html>
