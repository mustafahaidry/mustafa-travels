<?php
// This is a separate page - visa-services.php
// Include this in your website or create as standalone

// SEO Meta for Visa Page
$pageTitle = "Visa Services | Mustafa Travels - E-Visa, Visa Assistance & ETA";
$pageDescription = "Get visa services for Pakistan, India, Bangladesh, Nepal, Turkey, Malaysia, Morocco, UK, USA, Canada, Japan, Australia. Fast processing, best rates.";
$pageKeywords = "visa services, e-visa, visa assistance, ETA, Pakistan visa, India visa, UK visa, USA visa, Canada visa";

// Common header include (adjust path as needed)
// require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $pageDescription; ?>">
    <meta name="keywords" content="<?php echo $pageKeywords; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gold: #d4af37;
            --primary-navy: #1a237e;
            --primary-teal: #00695c;
            --light-gold: #f5e8c8;
            --light-bg: #f9f7f2;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--light-bg);
            line-height: 1.7;
            color: #2c3e50;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        
        /* Header */
        .elegant-header {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-teal) 100%);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .header-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-wrap: wrap;
            gap: 15px;
        }
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
        .whatsapp-btn-elegant {
            background: #25D366;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
        }
        
        /* Page Banner */
        .visa-banner {
            background: linear-gradient(135deg, #1a237e 0%, #00695c 100%);
            padding: 60px 0;
            text-align: center;
            color: white;
        }
        .visa-banner h1 {
            font-size: 48px;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }
        .visa-banner p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }
        
        /* Category Section */
        .category-section {
            padding: 60px 0;
        }
        .category-title {
            font-size: 32px;
            color: var(--primary-navy);
            margin-bottom: 30px;
            text-align: center;
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .category-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--primary-gold);
            border-radius: 2px;
        }
        .category-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
        }
        
        /* Visa Cards Grid */
        .visa-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }
        .visa-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            cursor: pointer;
            border: 1px solid #eee;
        }
        .visa-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            border-color: var(--primary-gold);
        }
        .visa-card-header {
            background: linear-gradient(135deg, var(--primary-navy), var(--primary-teal));
            padding: 25px;
            text-align: center;
            color: white;
        }
        .visa-card-header i {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .visa-card-header h3 {
            color: white;
            font-size: 22px;
            margin: 0;
        }
        .visa-card-body {
            padding: 20px;
        }
        .visa-detail {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        .visa-detail i {
            color: var(--primary-gold);
            width: 20px;
        }
        .visa-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-teal);
            margin: 15px 0;
            text-align: center;
        }
        .visa-btn {
            display: block;
            background: linear-gradient(135deg, var(--primary-teal), var(--primary-navy));
            color: white;
            text-align: center;
            padding: 12px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            margin-top: 15px;
        }
        .visa-btn:hover {
            transform: translateY(-2px);
            background: var(--primary-gold);
            color: var(--primary-navy);
        }
        
        /* ETA Special Cards */
        .eta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }
        .eta-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            transition: var(--transition);
            border: 1px solid #eee;
        }
        .eta-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-gold);
            box-shadow: var(--shadow);
        }
        .eta-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .eta-card .country-flag {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .eta-card h4 {
            font-size: 20px;
            color: var(--primary-navy);
            margin-bottom: 10px;
        }
        .eta-card p {
            font-size: 13px;
            color: #666;
            margin-bottom: 15px;
        }
        
        /* Info Banner */
        .info-banner {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            margin: 40px 0;
            border-left: 5px solid var(--primary-gold);
        }
        
        /* Footer */
        .footer-elegant {
            background: linear-gradient(135deg, var(--primary-navy) 0%, #0d1440 100%);
            color: white;
            padding: 60px 0 30px;
            margin-top: 40px;
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            margin-bottom: 40px;
        }
        .footer-bottom {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        
        @media (max-width: 768px) {
            .visa-grid, .eta-grid, .footer-content {
                grid-template-columns: 1fr;
            }
            .visa-banner h1 {
                font-size: 32px;
            }
            .category-title {
                font-size: 26px;
            }
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
            <a href="index.php" class="logo-elegant">
                <div class="logo-icon-elegant"><i class="fas fa-kaaba"></i></div>
                <div class="logo-text-elegant">
                    <div class="logo-main-elegant">MUSTAFA TRAVELS & TOURS</div>
                    <div class="logo-sub-elegant">PREMIUM TRAVEL EXPERIENCES</div>
                </div>
            </a>
            <div class="mobile-menu-toggle" style="display:none;"><i class="fas fa-bars"></i></div>
            <nav class="nav-elegant">
                <a href="index.php#home">Home</a>
                <a href="index.php#umrah">Umrah</a>
                <a href="index.php#hajj">Hajj 2026</a>
                <a href="visa-services.php" style="color: var(--primary-gold);">Visa Services</a>
                <a href="index.php#flights">Flight Deals</a>
                <a href="index.php#services">Services</a>
                <a href="https://wa.me/34611473217" class="whatsapp-btn-elegant"><i class="fab fa-whatsapp"></i> Book Now</a>
            </nav>
        </div>
    </div>
</header>

<!-- Banner -->
<section class="visa-banner">
    <div class="container">
        <h1><i class="fas fa-passport"></i> Visa Services</h1>
        <p>Fast, reliable visa processing for travelers worldwide. Get your visa with ease and confidence.</p>
    </div>
</section>

<div class="container">
    <!-- Info Banner -->
    <div class="info-banner">
        <i class="fas fa-clock" style="font-size: 36px; color: var(--primary-gold); margin-bottom: 10px; display: inline-block;"></i>
        <h3 style="color: var(--primary-navy); margin-bottom: 10px;">Quick & Reliable Visa Processing</h3>
        <p>We process visas with 98% approval rate. Service charge: €15 per visa + government fees. <strong>24/7 Support Available</strong></p>
        <a href="https://wa.me/34611473217" class="whatsapp-btn-elegant" style="display: inline-block; margin-top: 15px;"><i class="fab fa-whatsapp"></i> Contact for Visa Inquiry</a>
    </div>
</div>

<!-- ========== E-VISA SECTION ========== -->
<section class="category-section">
    <div class="container">
        <h2 class="category-title">🌍 E-VISA</h2>
        <p class="category-subtitle">Apply online for electronic visa - Fast & Easy process</p>
        
        <div class="visa-grid">
            <!-- Pakistan E-Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need Pakistan E-Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-flag-checkered"></i>
                    <h3>Pakistan</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 7-10 working days</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist & Business Visa</div>
                    <div class="visa-detail"><i class="fas fa-envelope"></i> Online application available</div>
                    <div class="visa-price">From €60 + €15 service fee</div>
                    <div class="visa-btn">Apply Now →</div>
                </div>
            </div>
            
            <!-- India E-Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need India E-Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>India</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 3-5 working days</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> e-Tourist Visa (eTV)</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> e-Business Visa</div>
                    <div class="visa-price">From €25 + €15 service fee</div>
                    <div class="visa-btn">Apply Now →</div>
                </div>
            </div>
            
            <!-- Bangladesh E-Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need Bangladesh E-Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-water"></i>
                    <h3>Bangladesh</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 7-10 working days</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist & Business Visa</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Online application available</div>
                    <div class="visa-price">From €50 + €15 service fee</div>
                    <div class="visa-btn">Apply Now →</div>
                </div>
            </div>
            
            <!-- Nepal E-Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need Nepal E-Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-mountain"></i>
                    <h3>Nepal</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 3-5 working days</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist Visa on Arrival</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> E-Visa available</div>
                    <div class="visa-price">From €30 + €15 service fee</div>
                    <div class="visa-btn">Apply Now →</div>
                </div>
            </div>
            
            <!-- Turkey E-Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need Turkey E-Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-mosque"></i>
                    <h3>Turkey</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 1-3 working days</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist & Business Visa</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Multiple entry available</div>
                    <div class="visa-price">From €50 + €15 service fee</div>
                    <div class="visa-btn">Apply Now →</div>
                </div>
            </div>
            
            <!-- Malaysia E-Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need Malaysia E-Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-city"></i>
                    <h3>Malaysia</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 5-7 working days</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> eNTRI & eVisa available</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist & Business</div>
                    <div class="visa-price">From €40 + €15 service fee</div>
                    <div class="visa-btn">Apply Now →</div>
                </div>
            </div>
            
            <!-- Morocco E-Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need Morocco E-Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-desert"></i>
                    <h3>Morocco</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 5-7 working days</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist & Business Visa</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Online application</div>
                    <div class="visa-price">From €35 + €15 service fee</div>
                    <div class="visa-btn">Apply Now →</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== VISA ASSISTANCE SECTION ========== -->
<section class="category-section" style="background: var(--light-bg);">
    <div class="container">
        <h2 class="category-title">🤝 VISA ASSISTANCE</h2>
        <p class="category-subtitle">Full service visa assistance for complex applications - We handle everything</p>
        
        <div class="visa-grid">
            <!-- UK Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need UK Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-flag-gb"></i>
                    <h3>United Kingdom</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 3-4 weeks</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist & Visitor Visa</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Business & Work Visa</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Student Visa</div>
                    <div class="visa-price">From €150 + €15 service fee</div>
                    <div class="visa-btn">Get Assistance →</div>
                </div>
            </div>
            
            <!-- USA Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need USA Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-flag-usa"></i>
                    <h3>United States</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 4-6 weeks</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> B1/B2 Tourist/Business</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Student Visa (F1)</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Work Visa (H1B/L1)</div>
                    <div class="visa-price">From €185 + €15 service fee</div>
                    <div class="visa-btn">Get Assistance →</div>
                </div>
            </div>
            
            <!-- Canada Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need Canada Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-maple-leaf"></i>
                    <h3>Canada</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 4-6 weeks</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist Visa (TRV)</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Business Visa</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Study Permit</div>
                    <div class="visa-price">From €100 + €15 service fee</div>
                    <div class="visa-btn">Get Assistance →</div>
                </div>
            </div>
            
            <!-- Japan Visa -->
            <div class="visa-card" onclick="window.open('https://wa.me/34611473217?text=I need Japan Visa assistance', '_blank')">
                <div class="visa-card-header">
                    <i class="fas fa-torii-gate"></i>
                    <h3>Japan</h3>
                </div>
                <div class="visa-card-body">
                    <div class="visa-detail"><i class="fas fa-clock"></i> Processing: 5-7 working days</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Tourist Visa</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Business Visa</div>
                    <div class="visa-detail"><i class="fas fa-check-circle"></i> Transit Visa</div>
                    <div class="visa-price">From €45 + €15 service fee</div>
                    <div class="visa-btn">Get Assistance →</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== ETA SECTION ========== -->
<section class="category-section">
    <div class="container">
        <h2 class="category-title">⚡ ETA (Electronic Travel Authorization)</h2>
        <p class="category-subtitle">Fast online travel authorization for visa-exempt travelers</p>
        
        <div class="eta-grid">
            <!-- USA ESTA -->
            <div class="eta-card" onclick="window.open('https://wa.me/34611473217?text=I need USA ESTA assistance', '_blank')">
                <div class="country-flag">🇺🇸</div>
                <h4>USA ESTA</h4>
                <p>Electronic System for Travel Authorization. Valid for 2 years. Multiple entries.</p>
                <div class="visa-price" style="font-size: 20px;">From €21 + €15 service fee</div>
                <div class="visa-btn" style="margin-top: 10px;">Apply ESTA →</div>
            </div>
            
            <!-- UK ETA -->
            <div class="eta-card" onclick="window.open('https://wa.me/34611473217?text=I need UK ETA assistance', '_blank')">
                <div class="country-flag">🇬🇧</div>
                <h4>UK ETA</h4>
                <p>Electronic Travel Authorization for UK. Valid for 2 years. Multiple entries.</p>
                <div class="visa-price" style="font-size: 20px;">From £10 + €15 service fee</div>
                <div class="visa-btn" style="margin-top: 10px;">Apply ETA →</div>
            </div>
            
            <!-- Australia ETA -->
            <div class="eta-card" onclick="window.open('https://wa.me/34611473217?text=I need Australia ETA assistance', '_blank')">
                <div class="country-flag">🇦🇺</div>
                <h4>Australia ETA</h4>
                <p>Electronic Travel Authority. Valid for 12 months. Multiple entries.</p>
                <div class="visa-price" style="font-size: 20px;">From AUD 20 + €15 service fee</div>
                <div class="visa-btn" style="margin-top: 10px;">Apply ETA →</div>
            </div>
            
            <!-- Canada eTA -->
            <div class="eta-card" onclick="window.open('https://wa.me/34611473217?text=I need Canada eTA assistance', '_blank')">
                <div class="country-flag">🇨🇦</div>
                <h4>Canada eTA</h4>
                <p>Electronic Travel Authorization. Valid for 5 years. Multiple entries.</p>
                <div class="visa-price" style="font-size: 20px;">From CAD 7 + €15 service fee</div>
                <div class="visa-btn" style="margin-top: 10px;">Apply eTA →</div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section style="padding: 60px 0; background: var(--light-bg);">
    <div class="container">
        <h2 class="category-title">Why Choose Us?</h2>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; margin-top: 40px;">
            <div style="text-align: center;">
                <i class="fas fa-check-circle" style="font-size: 48px; color: var(--primary-gold);"></i>
                <h3 style="margin: 15px 0;">98% Approval</h3>
                <p>High success rate</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-clock" style="font-size: 48px; color: var(--primary-gold);"></i>
                <h3 style="margin: 15px 0;">Fast Processing</h3>
                <p>Quick turnaround time</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-headset" style="font-size: 48px; color: var(--primary-gold);"></i>
                <h3 style="margin: 15px 0;">24/7 Support</h3>
                <p>Always here to help</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-shield-alt" style="font-size: 48px; color: var(--primary-gold);"></i>
                <h3 style="margin: 15px 0;">Secure & Safe</h3>
                <p>Data protection guaranteed</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section style="padding: 60px 0;">
    <div class="container">
        <div class="info-banner" style="background: linear-gradient(135deg, #e3f2fd, #bbdef5);">
            <i class="fas fa-envelope" style="font-size: 36px; color: var(--primary-navy); margin-bottom: 10px; display: inline-block;"></i>
            <h3 style="color: var(--primary-navy); margin-bottom: 10px;">Need Help with Your Visa?</h3>
            <p>Contact us today for free consultation and quote. Our visa experts will guide you through the process.</p>
            <div style="margin-top: 20px;">
                <a href="https://wa.me/34611473217" class="whatsapp-btn-elegant" style="margin: 0 10px;"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
                <a href="tel:+34632234216" class="whatsapp-btn-elegant" style="background: #00695c; margin: 0 10px;"><i class="fas fa-phone"></i> Call Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer-elegant">
    <div class="container">
        <div class="footer-content">
            <div>
                <h3>Mustafa Travels & Tours</h3>
                <p>Rambla Badal 141 Local 1 Bajo<br>Barcelona 08028, Spain</p>
                <p>📞 +34-632234216<br>💬 +34-611473217<br>✉️ mustafatravelstours@gmail.com</p>
            </div>
            <div>
                <h3>Quick Links</h3>
                <p><a href="index.php#home" style="color: rgba(255,255,255,0.7);">Home</a></p>
                <p><a href="index.php#umrah" style="color: rgba(255,255,255,0.7);">Umrah Packages</a></p>
                <p><a href="index.php#hajj" style="color: rgba(255,255,255,0.7);">Hajj 2026</a></p>
                <p><a href="visa-services.php" style="color: var(--primary-gold);">Visa Services</a></p>
            </div>
            <div>
                <h3>Visa Services</h3>
                <p>E-Visa | Visa Assistance | ETA</p>
                <p>Pakistan | India | UK | USA | Canada</p>
                <p>Turkey | Malaysia | Japan | Australia</p>
            </div>
            <div>
                <h3>Business Hours</h3>
                <p>Mon-Thu: 10:30 - 20:30</p>
                <p>Fri: 10:30-13:00 & 15:00-20:30</p>
                <p>Sat: 10:30 - 19:30</p>
                <p>Sun: Closed</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Mustafa Travels & Tours. All rights reserved. | Service Charge: €15 per visa</p>
        </div>
    </div>
</footer>

<script>
// Mobile menu toggle
document.querySelector('.mobile-menu-toggle')?.addEventListener('click', function() {
    document.querySelector('.nav-elegant')?.classList.toggle('active');
});
</script>
</body>
</html>
