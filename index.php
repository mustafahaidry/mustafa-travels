<?php
// index.php - Mustafa Travels & Tours Main Website
// PHP file with dynamic content - No fake data, only real affiliate links

// Page title and meta tags based on query parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels & Tours | Best Travel Agency in Barcelona</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Mustafa Travels & Tours - Professional travel agency in Barcelona. Flight tickets, hotels, visa assistance, Umrah & Hajj packages. 24/7 emergency support.">
    <meta name="keywords" content="travel agency Barcelona, flight tickets, Umrah packages, Hajj 2026, visa assistance, hotel booking, travel insurance, eSIM, cheap flights Barcelona to Lahore">
    <meta name="author" content="Ghulam Mustafa Haidry">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="Mustafa Travels & Tours - Your Trusted Travel Partner">
    <meta property="og:description" content="Flight tickets, hotels, visa assistance, Umrah & Hajj packages. 24/7 emergency support.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://mustafatravelstours.com">
    
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-H7TQLKHP25"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-H7TQLKHP25');
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: #f8fafc;
        }
        
        /* Top Bar */
        .top-bar {
            background: #0a2540;
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
        
        .top-bar a:hover {
            color: #d4af37;
        }
        
        .top-bar i {
            margin-right: 5px;
        }
        
        /* Emergency Badge */
        .emergency-badge {
            background: #dc2626;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 11px;
            margin-left: 10px;
            font-weight: bold;
        }
        
        /* Main Header */
        .main-header {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo h1 {
            color: #0a2540;
            font-size: 22px;
            letter-spacing: 1px;
        }
        
        .logo p {
            font-size: 10px;
            color: #666;
            letter-spacing: 2px;
        }
        
        .nav a {
            text-decoration: none;
            color: #333;
            margin-left: 25px;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav a:hover, .nav a.active {
            color: #0066cc;
        }
        
        /* Hero Slider Section */
        .hero-slider {
            position: relative;
            height: 550px;
            overflow: hidden;
        }
        
        .slider-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        
        .slide.active {
            opacity: 1;
        }
        
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10,37,64,0.85), rgba(26,74,122,0.85));
        }
        
        .slide-content {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 0 20px;
        }
        
        .slide-content h2 {
            font-size: 48px;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .slide-content p {
            font-size: 18px;
            margin-bottom: 30px;
            max-width: 700px;
        }
        
        /* Search Bar Widget */
        .search-widget {
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 85%;
            max-width: 1000px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            padding: 20px;
            z-index: 10;
        }
        
        /* Slider Controls */
        .slider-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 12px;
            z-index: 5;
        }
        
        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .slider-dot.active {
            background: #d4af37;
            transform: scale(1.2);
        }
        
        .slider-prev, .slider-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 5;
            transition: all 0.3s;
            font-size: 20px;
        }
        
        .slider-prev:hover, .slider-next:hover {
            background: #d4af37;
            color: #0a2540;
        }
        
        .slider-prev { left: 20px; }
        .slider-next { right: 20px; }
        
        /* Container */
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 80px 40px 60px;
        }
        
        .section-title {
            text-align: center;
            font-size: 32px;
            margin-bottom: 15px;
            color: #0a2540;
        }
        
        .section-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 50px;
            font-size: 16px;
        }
        
        .section-title:after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: #d4af37;
            margin: 15px auto 0;
        }
        
        /* Service Cards */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .service-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        }
        
        .service-icon {
            background: linear-gradient(135deg, #0a2540, #1a4a7a);
            color: white;
            padding: 30px;
            text-align: center;
            font-size: 42px;
        }
        
        .service-content {
            padding: 25px;
            text-align: center;
        }
        
        .service-content h3 {
            margin-bottom: 10px;
            color: #0a2540;
            font-size: 20px;
        }
        
        .service-content p {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .service-btn {
            display: inline-block;
            background: #0066cc;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .service-btn:hover {
            background: #004d99;
        }
        
        /* Contact Cards */
        .contact-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin: 40px 0;
        }
        
        .contact-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: transform 0.3s;
        }
        
        .contact-card:hover {
            transform: translateY(-5px);
        }
        
        .contact-card i {
            font-size: 40px;
            color: #0066cc;
            margin-bottom: 15px;
        }
        
        .contact-card h3 {
            margin-bottom: 10px;
            color: #0a2540;
        }
        
        .contact-card .number {
            font-size: 22px;
            font-weight: 700;
            color: #0a2540;
            margin: 10px 0;
        }
        
        .emergency-tag {
            background: #fee2e2;
            color: #dc2626;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-top: 10px;
        }
        
        /* Rating Section */
        .rating-section {
            background: linear-gradient(135deg, #0a2540, #1a4a7a);
            color: white;
            padding: 50px;
            border-radius: 20px;
            text-align: center;
            margin: 40px 0;
        }
        
        .rating-stats {
            display: flex;
            justify-content: center;
            gap: 50px;
            flex-wrap: wrap;
            margin-top: 30px;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat .number {
            font-size: 36px;
            font-weight: 700;
            color: #d4af37;
        }
        
        .stars {
            color: #f5b042;
            font-size: 20px;
            letter-spacing: 3px;
            margin: 10px 0;
        }
        
        /* CTA Section */
        .cta-section {
            background: #e8f4f8;
            text-align: center;
            padding: 50px;
            border-radius: 20px;
            margin: 40px 0;
        }
        
        .whatsapp-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #25D366;
            color: white;
            padding: 14px 35px;
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
            background: #0a2540;
            color: white;
            padding: 50px 40px 30px;
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
            background: #d4af37;
        }
        
        .footer-col a, .footer-col p {
            color: #ccc;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            transition: color 0.3s;
        }
        
        .footer-col a:hover {
            color: #d4af37;
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
            font-size: 18px;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background: #d4af37;
            color: #0a2540;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid #1a4a7a;
            font-size: 13px;
            color: #888;
        }
        
        /* Page Content Styles */
        .page-content {
            background: white;
            padding: 50px;
            border-radius: 20px;
            margin: 40px auto;
            max-width: 1000px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        
        .page-content h1 {
            color: #0a2540;
            margin-bottom: 20px;
        }
        
        .page-content h2 {
            margin: 30px 0 15px;
            color: #0a2540;
        }
        
        .profile-img {
            text-align: center;
            margin: 30px 0;
        }
        
        .profile-img img {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #d4af37;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .contact-info-box {
            background: #f0f7ff;
            padding: 25px;
            border-radius: 16px;
            margin: 30px 0;
        }
        
        .contact-method {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .contact-method i {
            width: 35px;
            font-size: 20px;
            color: #0066cc;
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
            .hero-slider {
                height: 450px;
            }
            .slide-content h2 {
                font-size: 28px;
            }
            .search-widget {
                width: 95%;
                bottom: -40px;
            }
            .container {
                padding: 60px 20px 40px;
            }
            .section-title {
                font-size: 28px;
            }
            .contact-cards {
                grid-template-columns: 1fr;
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
        <i class="fas fa-phone" style="margin-left: 15px;"></i> 937578907 <span style="font-size: 11px;">(Office Hours)</span>
        <i class="fas fa-envelope" style="margin-left: 15px;"></i> mustafatravelstours@gmail.com
    </div>
    <div>
        <i class="fas fa-map-marker-alt"></i> Rambla Badal 141, Barcelona, Spain
    </div>
</div>

<!-- Main Header -->
<div class="main-header">
    <div class="logo">
        <h1>MUSTAFA TRAVELS & TOURS</h1>
        <p>PREMIUM TRAVEL EXPERIENCES SINCE 2014</p>
    </div>
    <div class="nav">
        <a href="?page=home" class="<?php echo $page == 'home' ? 'active' : ''; ?>">Home</a>
        <a href="?page=about" class="<?php echo $page == 'about' ? 'active' : ''; ?>">About Us</a>
        <a href="?page=contact" class="<?php echo $page == 'contact' ? 'active' : ''; ?>">Contact</a>
        <a href="?page=privacy" class="<?php echo $page == 'privacy' ? 'active' : ''; ?>">Privacy Policy</a>
    </div>
</div>

<!-- Dynamic Content Area -->
<?php if ($page == 'home'): ?>
<div id="homepage">
    <!-- Hero Slider -->
    <div class="hero-slider">
        <div class="slider-container">
            <div class="slide active" style="background-image: url('https://images.pexels.com/photos/33270402/pexels-photo-33270402.jpeg');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h2>Premium Umrah Experiences 2026</h2>
                    <p>Luxury packages with accommodations near Haram. Personalized spiritual guidance.</p>
                </div>
            </div>
            <div class="slide" style="background-image: url('https://images.pexels.com/photos/29102586/pexels-photo-29102586.jpeg');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h2>Hajj 2026 - Coming Soon</h2>
                    <p>Register your interest for Hajj 2026 packages. Phase 2 bookings opening soon.</p>
                </div>
            </div>
            <div class="slide" style="background-image: url('https://images.pexels.com/photos/161772/world-travel-map-global-161772.jpeg');">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <h2>Worldwide Flight Deals</h2>
                    <p>Best prices from Barcelona to Lahore, Islamabad, Karachi, Dubai & more.</p>
                </div>
            </div>
            
            <div class="slider-prev"><i class="fas fa-chevron-left"></i></div>
            <div class="slider-next"><i class="fas fa-chevron-right"></i></div>
            <div class="slider-controls">
                <div class="slider-dot active"></div>
                <div class="slider-dot"></div>
                <div class="slider-dot"></div>
            </div>
        </div>
        
        <!-- Search Widget -->
        <div class="search-widget">
            <script async src="https://tp.media/content?currency=eur&trs=610290&shmarker=610290&locale=en&powered_by=true&border_radius=12&plain=true&color_button=%230b3d91&color_icons=%230b3d91&color_background=%23ffffff&color_text=%23000000&promo_id=7873"></script>
        </div>
    </div>
    
    <!-- Contact Cards Section -->
    <div class="container">
        <h2 class="section-title">Contact Our Travel Experts</h2>
        <p class="section-subtitle">Multiple ways to reach us - We're here to help 24/7</p>
        
        <div class="contact-cards">
            <div class="contact-card">
                <i class="fas fa-phone-alt"></i>
                <h3>Emergency Number</h3>
                <div class="number">+34-632234216</div>
                <p>Available 24/7 for emergencies</p>
                <span class="emergency-tag"><i class="fas fa-exclamation-triangle"></i> 24/7 Emergency</span>
            </div>
            <div class="contact-card">
                <i class="fab fa-whatsapp"></i>
                <h3>WhatsApp Support</h3>
                <div class="number">+34-611473217</div>
                <p>Quick responses on WhatsApp</p>
            </div>
            <div class="contact-card">
                <i class="fas fa-phone"></i>
                <h3>Landline (Office)</h3>
                <div class="number">937578907</div>
                <p>Monday to Saturday (10:30 - 20:30)</p>
            </div>
            <div class="contact-card">
                <i class="fab fa-linkedin"></i>
                <h3>Follow on LinkedIn</h3>
                <div class="number" style="font-size: 16px;">Mustafa Travels & Tours</div>
                <p>Stay updated with our latest news</p>
                <a href="https://www.linkedin.com/company/mustafa-travels-tours" target="_blank" class="service-btn" style="margin-top: 10px;">Visit LinkedIn →</a>
            </div>
        </div>
    </div>
    
    <!-- Services Section -->
    <div class="container">
        <h2 class="section-title">Our Travel Services</h2>
        <p class="section-subtitle">Complete travel solutions under one roof</p>
        
        <div class="services-grid">
            <a href="https://aviasales.tpo.mx/HEyfp6zU" target="_blank" class="service-card">
                <div class="service-icon"><i class="fas fa-plane"></i></div>
                <div class="service-content">
                    <h3>✈️ Flight Tickets</h3>
                    <p>Worldwide flight bookings at best prices. 100+ airlines compared.</p>
                    <span class="service-btn">Search Flights →</span>
                </div>
            </a>
            
            <a href="https://www.agoda.com/?cid=YOUR_AGODA_ID" target="_blank" class="service-card">
                <div class="service-icon"><i class="fas fa-hotel"></i></div>
                <div class="service-content">
                    <h3>🏨 Hotel Reservation</h3>
                    <p>2.5M+ hotels worldwide. From budget to luxury.</p>
                    <span class="service-btn">Find Hotels →</span>
                </div>
            </a>
            
            <a href="https://ektatraveling.tpo.mx/jyKgc1bx" target="_blank" class="service-card">
                <div class="service-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="service-content">
                    <h3>🛡️ Travel Insurance</h3>
                    <p>Medical coverage, COVID-19, lost baggage, flight delay.</p>
                    <span class="service-btn">Get Insurance →</span>
                </div>
            </a>
            
            <a href="https://yesim.tpo.mx/t36uN2cA" target="_blank" class="service-card">
                <div class="service-icon"><i class="fas fa-sim-card"></i></div>
                <div class="service-content">
                    <h3>📱 eSIM Internet</h3>
                    <p>Stay connected worldwide. No roaming charges. 200+ countries.</p>
                    <span class="service-btn">Buy eSIM →</span>
                </div>
            </a>
            
            <a href="https://tiqets.tpo.mx/pqSwPX5S" target="_blank" class="service-card">
                <div class="service-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="service-content">
                    <h3>🎟️ Attraction Tickets</h3>
                    <p>Skip-the-line tickets for Eiffel Tower, Colosseum & more.</p>
                    <span class="service-btn">Book Tickets →</span>
                </div>
            </a>
            
            <div class="service-card" style="cursor: default;">
                <div class="service-icon" style="background: linear-gradient(135deg, #1a4a7a, #0a2540);"><i class="fas fa-kaaba"></i></div>
                <div class="service-content">
                    <h3>🕋 Umrah & Hajj</h3>
                    <p>Premium Umrah packages. Hajj 2026 coming soon.</p>
                    <a href="https://wa.me/34611473217" class="service-btn">Inquire →</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Rating Section -->
    <div class="container">
        <div class="rating-section">
            <h3>Our Customers Love Us</h3>
            <div class="stars">★★★★★</div>
            <p style="font-size: 24px; font-weight: 700; margin: 10px 0;">4.9 / 5.0</p>
            <p>Based on 500+ reviews from satisfied travelers</p>
            <div class="rating-stats">
                <div class="stat"><div class="number">500+</div><div>Umrah Clients</div></div>
                <div class="stat"><div class="number">24/7</div><div>Emergency Support</div></div>
                <div class="stat"><div class="number">50+</div><div>Destinations</div></div>
                <div class="stat"><div class="number">10+</div><div>Years Experience</div></div>
            </div>
        </div>
    </div>
    
    <!-- Visa Services -->
    <div class="container">
        <h2 class="section-title">Visa Assistance Services</h2>
        <p class="section-subtitle">We help you with visa applications for multiple countries</p>
        <div class="services-grid">
            <div class="service-card" style="cursor: default;">
                <div class="service-icon" style="background: linear-gradient(135deg, #1a4a7a, #0a2540);"><i class="fas fa-passport"></i></div>
                <div class="service-content">
                    <h3>🇬🇧 UK Visa</h3>
                    <p>Complete assistance for UK visit and family visas</p>
                </div>
            </div>
            <div class="service-card" style="cursor: default;">
                <div class="service-icon" style="background: linear-gradient(135deg, #1a4a7a, #0a2540);"><i class="fas fa-passport"></i></div>
                <div class="service-content">
                    <h3>🇺🇸 USA Visa</h3>
                    <p>US tourist and business visa assistance</p>
                </div>
            </div>
            <div class="service-card" style="cursor: default;">
                <div class="service-icon" style="background: linear-gradient(135deg, #1a4a7a, #0a2540);"><i class="fas fa-passport"></i></div>
                <div class="service-content">
                    <h3>🇨🇦 Canada Visa</h3>
                    <p>Canada visit and study visa support</p>
                </div>
            </div>
            <div class="service-card" style="cursor: default;">
                <div class="service-icon" style="background: linear-gradient(135deg, #1a4a7a, #0a2540);"><i class="fas fa-passport"></i></div>
                <div class="service-content">
                    <h3>🇯🇵 Japan Visa</h3>
                    <p>Japan visa filing assistance</p>
                </div>
            </div>
            <div class="service-card" style="cursor: default;">
                <div class="service-icon" style="background: linear-gradient(135deg, #1a4a7a, #0a2540);"><i class="fas fa-globe"></i></div>
                <div class="service-content">
                    <h3>🌍 E-Visa Services</h3>
                    <p>Pakistan, India, Turkey, Morocco, Malaysia</p>
                </div>
            </div>
            <div class="service-card" style="cursor: default;">
                <div class="service-icon" style="background: linear-gradient(135deg, #1a4a7a, #0a2540);"><i class="fas fa-ship"></i></div>
                <div class="service-content">
                    <h3>🚌 Bus & Train Tickets</h3>
                    <p>Bus, train, tram, and ship tickets worldwide</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="container">
        <div class="cta-section">
            <h2>Need Help Finding the Best Deals?</h2>
            <p>Contact our travel experts directly on WhatsApp for personalized assistance</p>
            <a href="https://wa.me/34611473217" class="whatsapp-btn" target="_blank">
                <i class="fab fa-whatsapp" style="font-size: 24px;"></i> Chat on WhatsApp
            </a>
            <p style="margin-top: 20px; font-size: 14px;">
                📞 Emergency: +34-632234216 | 🏢 Office: 937578907 | ✉️ mustafatravelstours@gmail.com
            </p>
        </div>
    </div>
</div>

<?php elseif ($page == 'about'): ?>
<div class="page-content">
    <div class="profile-img">
        <img src="https://i.postimg.cc/sxd9YgRx/mustafa.jpg" alt="Ghulam Mustafa Haidry - Founder">
    </div>
    <h1 style="text-align: center;">About Mustafa Travels & Tours</h1>
    
    <h2>Founder's Message</h2>
    <p>My name is <strong>Ghulam Mustafa Haidry</strong>. I started working as a travel agent in <strong>2019</strong> and founded my own travel agency <strong>Mustafa Travels & Tours</strong> in <strong>January 2024</strong>. With over 5 years of experience in the travel industry, I have served more than <strong>500 Umrah clients</strong> with a <strong>4.9/5 rating</strong>.</p>
    
    <h2>Our Services</h2>
    <ul>
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
    
    <div style="background: #f0f7ff; padding: 20px; border-radius: 12px; margin-top: 30px; text-align: center;">
        <span style="display: inline-block; background: #d4af37; color: #0a2540; padding: 8px 20px; border-radius: 50px; margin: 5px;">⭐ 4.9/5 Rating</span>
        <span style="display: inline-block; background: #d4af37; color: #0a2540; padding: 8px 20px; border-radius: 50px; margin: 5px;">📞 24/7 Support</span>
        <span style="display: inline-block; background: #d4af37; color: #0a2540; padding: 8px 20px; border-radius: 50px; margin: 5px;">🕋 500+ Umrah Clients</span>
    </div>
</div>

<?php elseif ($page == 'contact'): ?>
<div class="page-content">
    <h1>Contact Us</h1>
    
    <div class="contact-cards" style="margin-bottom: 30px;">
        <div class="contact-card">
            <i class="fas fa-phone-alt"></i>
            <h3>Emergency Number</h3>
            <div class="number">+34-632234216</div>
            <p>Available 24/7 for emergencies</p>
        </div>
        <div class="contact-card">
            <i class="fab fa-whatsapp"></i>
            <h3>WhatsApp Support</h3>
            <div class="number">+34-611473217</div>
            <p>Quick responses on WhatsApp</p>
        </div>
        <div class="contact-card">
            <i class="fas fa-phone"></i>
            <h3>Landline (Office)</h3>
            <div class="number">937578907</div>
            <p>Monday to Saturday (10:30 - 20:30)</p>
        </div>
    </div>
    
    <div class="contact-info-box">
        <div class="contact-method"><i class="fas fa-map-marker-alt"></i> <div><strong>Address</strong><br>Rambla Badal 141 Local 1 Bajo, Barcelona 08028, Spain</div></div>
        <div class="contact-method"><i class="fas fa-envelope"></i> <div><strong>Email</strong><br>mustafatravelstours@gmail.com</div></div>
        <div class="contact-method"><i class="fab fa-linkedin"></i> <div><strong>LinkedIn</strong><br><a href="https://www.linkedin.com/company/mustafa-travels-tours" target="_blank">Mustafa Travels & Tours</a></div></div>
    </div>
    
    <h2>Business Hours</h2>
    <ul>
        <li>Monday - Saturday: 10:30 AM - 8:30 PM</li>
        <li>Sunday: Closed</li>
        <li><strong>Emergency Support:</strong> 24/7 Available (Call +34-632234216)</li>
    </ul>
    
    <div style="text-align: center; margin-top: 30px;">
        <a href="https://wa.me/34611473217" class="whatsapp-btn" style="display: inline-flex;">Chat on WhatsApp</a>
    </div>
</div>

<?php elseif ($page == 'privacy'): ?>
<div class="page-content">
    <h1>Privacy Policy</h1>
    <p><strong>Last Updated:</strong> April 2026</p>
    
    <h2>1. Information We Collect</h2>
    <p>Mustafa Travels & Tours collects personal information such as name, email, phone number, passport details, and travel preferences when you book our services or contact us.</p>
    
    <h2>2. How We Use Your Information</h2>
    <p>We use your information to process bookings, provide travel assistance, communicate with you, and improve our services. We do not sell your personal information to third parties.</p>
    
    <h2>3. Third-Party Services</h2>
    <p>We use trusted third-party partners (Aviasales, Agoda, EKTA, Yesim, Tiqets) for flight, hotel, insurance, and ticket bookings. These partners have their own privacy policies.</p>
    
    <h2>4. Data Security</h2>
    <p>We implement appropriate security measures to protect your personal information from unauthorized access or disclosure.</p>
    
    <h2>5. Your Rights</h2>
    <p>You have the right to access, correct, or delete your personal information. Contact us for any privacy-related requests.</p>
    
    <h2>6. Contact Us</h2>
    <p>For privacy concerns, email us at mustafatravelstours@gmail.com or call +34-632234216.</p>
</div>
<?php endif; ?>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div class="footer-col">
            <h4>Mustafa Travels & Tours</h4>
            <p>Professional travel agency based in Barcelona since 2014. Specializing in flights, hotels, visa assistance, and spiritual travel.</p>
            <div class="social-icons">
                <a href="https://www.facebook.com/mustafatravelstours" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.tiktok.com/mustafatravelstarragona" target="_blank"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.instagram.com/mustafatravelstours" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/company/mustafa-travels-tours" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Quick Links</h4>
            <a href="?page=home">Home</a>
            <a href="?page=about">About Us</a>
            <a href="?page=contact">Contact</a>
            <a href="?page=privacy">Privacy Policy</a>
        </div>
        <div class="footer-col">
            <h4>Contact Info</h4>
            <p><i class="fas fa-map-marker-alt"></i> Rambla Badal 141, Barcelona</p>
            <p><i class="fas fa-phone"></i> Emergency: +34-632234216</p>
            <p><i class="fas fa-phone"></i> Office: 937578907</p>
            <p><i class="fab fa-whatsapp"></i> WhatsApp: +34-611473217</p>
            <p><i class="fas fa-envelope"></i> mustafatravelstours@gmail.com</p>
        </div>
        <div class="footer-col">
            <h4>Business Hours</h4>
            <p>Monday - Saturday: 10:30 AM - 8:30 PM</p>
            <p>Sunday: Closed</p>
            <p style="margin-top: 10px;"><strong style="color: #d4af37;">Emergency Support:</strong> 24/7 Available</p>
            <p><i class="fas fa-exclamation-triangle" style="color: #dc2626;"></i> Call +34-632234216 for emergencies</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Service Charge: €15 per booking</p>
        <p style="margin-top: 10px;">Powered by Travelpayouts | Aviasales | Agoda | EKTA | Yesim | Tiqets</p>
    </div>
</footer>

<script>
    // Slider Functionality (only on home page)
    <?php if ($page == 'home'): ?>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    
    function showSlide(index) {
        if (!slides.length) return;
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;
        
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlide = index;
    }
    
    function nextSlide() { showSlide(currentSlide + 1); }
    function prevSlide() { showSlide(currentSlide - 1); }
    
    const nextBtn = document.querySelector('.slider-next');
    const prevBtn = document.querySelector('.slider-prev');
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
    
    dots.forEach((dot, i) => dot.addEventListener('click', () => showSlide(i)));
    
    if (slides.length) setInterval(nextSlide, 5000);
    <?php endif; ?>
    
    // Track affiliate clicks
    document.querySelectorAll('a[href*=".tpo.mx"], a[href*="aviasales"], a[href*="agoda"]').forEach(link => {
        link.addEventListener('click', function() {
            if(typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'affiliate',
                    'event_label': this.href
                });
            }
        });
    });
</script>
</body>
</html>
