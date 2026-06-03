<?php
// No PHP needed for this page - pure HTML/CSS/JS
// Save as index.php or index.html
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- SEO Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mustafa Travels & Tours | Premium Umrah Packages from Barcelona</title>
    <meta name="description" content="Premium Umrah packages from Barcelona. Economy and Economy Plus packages with hotels near Haram. Best Umrah deals 2026.">
    <meta name="keywords" content="Umrah packages, Barcelona travel agency, Makkah hotels, Madina hotels, Qila Ajyad, Saif Al Majd, Swiss Khalil, Umrah deals">
    <meta name="author" content="Mustafa Travels & Tours">
    
    <!-- Open Graph Tags -->
    <meta property="og:title" content="Mustafa Travels & Tours | Umrah Packages 2026">
    <meta property="og:description" content="Economy and Economy Plus Umrah packages with hotels near Haram. Best deals from Barcelona.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="https://images.pexels.com/photos/33270402/pexels-photo-33270402.jpeg">
    
    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gold: #d4af37;
            --primary-navy: #1a237e;
            --primary-teal: #00695c;
            --light-gold: #f5e8c8;
            --light-bg: #f9f7f2;
            --text-dark: #2c3e50;
            --text-light: #666;
            --white: #ffffff;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            background-color: var(--light-bg);
            line-height: 1.7;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            color: var(--primary-navy);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* ========== UMRAH QUOTATION BUTTON ========== */
        .umrah-quote-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #d4af37, #b8942e);
            color: #1a237e;
            padding: 16px 32px;
            border-radius: 60px;
            font-weight: 700;
            font-size: 18px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }
        
        .umrah-quote-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
        }
        
        /* ========== MODAL STYLES ========== */
        .quotation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }
        
        .quotation-modal.active {
            display: flex;
        }
        
        .quotation-modal-content {
            background: #f0f2f5;
            width: 95%;
            max-width: 1400px;
            height: 90%;
            border-radius: 24px;
            overflow-y: auto;
            position: relative;
            animation: modalFadeIn 0.3s ease;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .modal-close-btn {
            position: sticky;
            top: 15px;
            right: 20px;
            float: right;
            background: #dc3545;
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            font-size: 22px;
            cursor: pointer;
            z-index: 101;
            margin: 15px;
            transition: var(--transition);
        }
        
        .modal-close-btn:hover {
            background: #c82333;
            transform: scale(1.05);
        }
        
        .modal-inner {
            padding: 20px;
            clear: both;
        }
        
        /* Quotation Styles inside Modal */
        .quo-wrapper {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .quo-hotel-panel {
            flex: 2;
            min-width: 320px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .quo-extras-panel {
            flex: 1.2;
            min-width: 300px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .quo-city-tabs {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .quo-city-tab {
            flex: 1;
            padding: 16px;
            text-align: center;
            font-weight: 600;
            cursor: pointer;
            background: transparent;
            border: none;
            font-size: 15px;
            transition: all 0.3s;
            color: #666;
        }
        .quo-city-tab.active {
            background: white;
            color: #1f6e43;
            border-bottom: 3px solid #d4af37;
        }
        .quo-selector {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .quo-selector label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #1a2c1c;
        }
        .quo-dropdown {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 14px;
            cursor: pointer;
        }
        .quo-details {
            padding: 20px;
        }
        .quo-hotel-name {
            font-size: 20px;
            font-weight: 700;
            color: #1a2c1c;
            margin-bottom: 8px;
        }
        .quo-distance {
            display: inline-block;
            background: #e8f5e9;
            color: #1f6e43;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .quo-perbed {
            display: inline-block;
            background: #f5e8c8;
            color: #8B6914;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 12px;
            margin-left: 10px;
        }
        .quo-nights {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 16px;
            margin-bottom: 20px;
        }
        .quo-nights input {
            width: 100px;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            text-align: center;
            font-size: 15px;
            margin-top: 8px;
        }
        .quo-room-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .quo-room {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 14px;
            cursor: pointer;
            border: 2px solid transparent;
            flex-wrap: wrap;
            gap: 10px;
        }
        .quo-room.selected {
            background: #e8f5e9;
            border-color: #1f6e43;
        }
        .quo-room-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .quo-room-badge {
            font-weight: 700;
            background: white;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 12px;
        }
        .quo-rate-input {
            width: 80px;
            padding: 6px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            text-align: center;
        }
        .quo-price {
            font-weight: 700;
            color: #1f6e43;
            font-size: 15px;
        }
        .quo-extras-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .quo-extras-header h3 {
            color: #1a2c1c;
            font-size: 18px;
        }
        .quo-service {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }
        .quo-taxi-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            flex-wrap: wrap;
            gap: 10px;
        }
        .quo-taxi-btn {
            background: #f0f2f5;
            border: none;
            padding: 6px 16px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 13px;
        }
        .quo-taxi-btn.active {
            background: #1f6e43;
            color: white;
        }
        .quo-visa {
            width: 100%;
            padding: 10px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            margin-top: 8px;
        }
        .quo-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
            cursor: pointer;
        }
        .quo-summary {
            background: #1a2c1c;
            color: white;
            padding: 20px;
            margin: 20px;
            border-radius: 18px;
        }
        .quo-total {
            font-size: 32px;
            font-weight: 800;
            background: #d4af37;
            display: inline-block;
            padding: 6px 20px;
            border-radius: 50px;
            color: #1a2c1c;
            margin: 10px 0;
        }
        .quo-whatsapp {
            display: inline-block;
            background: #25D366;
            color: white;
            padding: 12px 20px;
            border-radius: 50px;
            text-decoration: none;
            text-align: center;
            width: 100%;
            margin-top: 12px;
            font-weight: 600;
        }
        .quo-not-included {
            font-size: 11px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .quo-better {
            text-align: center;
            padding: 15px;
            background: #fef3e2;
            margin: 15px;
            border-radius: 14px;
            font-size: 13px;
        }
        .quo-better a {
            color: #1f6e43;
            font-weight: 600;
            text-decoration: none;
        }
        @media (max-width: 768px) {
            .quo-room { flex-direction: column; align-items: stretch; }
            .quo-room-left { justify-content: space-between; }
        }

        /* Elegant Header */
        .elegant-header {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--primary-teal) 100%);
            padding: 15px 0;
            position: relative;
            overflow: hidden;
        }

        .header-top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--white);
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .contact-info-elegant {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .contact-info-elegant span {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 300;
        }

        .contact-info-elegant i {
            color: var(--primary-gold);
            font-size: 16px;
        }

        .social-elegant a {
            color: var(--white);
            margin-left: 15px;
            font-size: 16px;
            transition: var(--transition);
        }

        .social-elegant a:hover {
            color: var(--primary-gold);
            transform: translateY(-2px);
        }

        .main-header-elegant {
            padding: 30px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-elegant {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 20px;
        }

        .logo-icon-elegant {
            background: var(--primary-gold);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: var(--primary-navy);
            box-shadow: var(--shadow);
        }

        .logo-text-elegant {
            display: flex;
            flex-direction: column;
        }

        .logo-main-elegant {
            font-family: 'Crimson Text', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 1px;
        }

        .logo-sub-elegant {
            font-size: 14px;
            color: var(--light-gold);
            font-weight: 300;
            letter-spacing: 2px;
            margin-top: 5px;
        }

        .nav-elegant {
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .nav-elegant a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            letter-spacing: 0.5px;
            position: relative;
            padding: 8px 0;
            transition: var(--transition);
        }

        .nav-elegant a:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-gold);
            transition: var(--transition);
        }

        .nav-elegant a:hover:after,
        .nav-elegant a.active:after {
            width: 100%;
        }

        .nav-elegant a:hover,
        .nav-elegant a.active {
            color: var(--primary-gold);
        }

        .whatsapp-btn-elegant {
            background: linear-gradient(135deg, #25D366 0%, #1da851 100%);
            color: var(--white);
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }

        .whatsapp-btn-elegant:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        }

        /* Luxury Hero Slider */
        .luxury-slider {
            height: 600px;
            position: relative;
            overflow: hidden;
            border-radius: 0 0 var(--radius) var(--radius);
            box-shadow: var(--shadow);
        }
        .luxury-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.2s ease-in-out;
        }
        .luxury-slide.active {
            opacity: 1;
        }
        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(26, 35, 126, 0.85) 0%, rgba(0, 105, 92, 0.85) 100%);
            display: flex;
            align-items: center;
        }
        .slide-content-luxury {
            max-width: 800px;
            padding-left: 100px;
            color: var(--white);
        }
        .slide-content-luxury h2 {
            font-size: 56px;
            color: var(--white);
            margin-bottom: 25px;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .slide-content-luxury p {
            font-size: 20px;
            margin-bottom: 40px;
            color: rgba(255,255,255,0.9);
            max-width: 600px;
        }
        .luxury-btn {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: var(--primary-gold);
            color: var(--primary-navy);
            padding: 18px 36px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            font-size: 16px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }
        .luxury-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
            background: var(--light-gold);
        }
        .slider-controls {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
        }
        .slider-dot-luxury {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            cursor: pointer;
            transition: var(--transition);
            border: 2px solid transparent;
        }
        .slider-dot-luxury.active {
            background: var(--primary-gold);
            transform: scale(1.2);
            border-color: var(--white);
        }
        
        /* Elegant Search Box */
        .search-luxury {
            background: var(--white);
            padding: 40px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-top: -50px;
            position: relative;
            z-index: 10;
            margin-bottom: 80px;
            border-top: 4px solid var(--primary-gold);
        }
        .search-luxury h3 {
            text-align: center;
            margin-bottom: 35px;
            font-size: 28px;
            color: var(--primary-navy);
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }
        .search-luxury h3:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-gold), var(--primary-teal));
        }
        .search-form-luxury {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .form-group-luxury {
            position: relative;
        }
        .form-group-luxury label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-teal);
        }
        .form-group-luxury input,
        .form-group-luxury select {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Montserrat', sans-serif;
            transition: var(--transition);
            background: var(--white);
        }
        .form-group-luxury input:focus,
        .form-group-luxury select:focus {
            border-color: var(--primary-gold);
            outline: none;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
        .search-btn-luxury {
            grid-column: span 1;
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--primary-navy) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            padding: 16px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .search-btn-luxury:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 35, 126, 0.3);
        }
        
        /* Section Header */
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-header h2 {
            font-size: 42px;
            margin-bottom: 20px;
            color: var(--primary-navy);
            position: relative;
            display: inline-block;
        }
        .section-header h2:after {
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
        .section-header p {
            color: var(--text-light);
            max-width: 700px;
            margin: 25px auto 0;
            font-size: 18px;
        }
        
        /* Umrah Packages - Updated with new packages */
        .packages-luxury {
            padding: 80px 0;
            background: var(--white);
        }
        .packages-grid-luxury {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }
        .package-card-luxury {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
        }
        .package-card-luxury:hover {
            transform: translateY(-15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .package-image-luxury {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
            background: linear-gradient(135deg, var(--primary-navy), var(--primary-teal));
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .package-image-luxury i {
            font-size: 60px;
            color: var(--primary-gold);
            opacity: 0.8;
        }
        .package-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--primary-gold);
            color: var(--primary-navy);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(212, 175, 55, 0.3);
        }
        .package-content-luxury {
            padding: 25px;
        }
        .package-content-luxury h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--primary-navy);
        }
        .package-content-luxury .price {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary-teal);
            margin-bottom: 15px;
        }
        .package-content-luxury p {
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.8;
        }
        .package-features-luxury {
            list-style: none;
            margin-bottom: 25px;
        }
        .package-features-luxury li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
        }
        .package-features-luxury li:last-child {
            border-bottom: none;
        }
        .package-features-luxury i {
            color: var(--primary-gold);
            font-size: 14px;
        }
        .hotel-note {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            margin: 15px 0;
            font-size: 12px;
            color: var(--text-light);
        }
        .hotel-note i {
            color: var(--primary-gold);
            margin-right: 5px;
        }
        .package-btn-luxury {
            display: block;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-teal) 0%, var(--primary-navy) 100%);
            color: var(--white);
            padding: 14px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            width: 100%;
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
        }
        .package-btn-luxury:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26, 35, 126, 0.3);
        }
        
        /* Flight Deals Section */
        .flight-deals-section {
            padding: 80px 0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .flight-deals-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .flight-deal-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
            border-left: 5px solid var(--primary-gold);
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }
        .flight-deal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        .flight-airline {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 120px;
        }
        .airline-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            color: var(--white);
            font-size: 24px;
        }
        .airline-name {
            font-weight: 700;
            font-size: 16px;
            color: var(--primary-navy);
            text-align: center;
        }
        .flight-details {
            flex-grow: 1;
        }
        .flight-route {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 600;
        }
        .flight-route .city {
            color: var(--primary-navy);
            font-size: 20px;
        }
        .flight-route i {
            color: var(--primary-gold);
            font-size: 24px;
        }
        .flight-price {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-teal);
            margin-bottom: 10px;
        }
        .luggage-info {
            color: var(--text-light);
            font-size: 15px;
            margin-bottom: 5px;
        }
        .validity {
            color: #666;
            font-size: 14px;
            font-style: italic;
        }
        .flight-book-btn {
            background: linear-gradient(135deg, #25D366 0%, #1da851 100%);
            color: var(--white);
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }
        .flight-book-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
        }
        .poster-contact-info {
            background: var(--white);
            border-radius: var(--radius);
            padding: 30px;
            margin-top: 40px;
            box-shadow: var(--shadow);
            text-align: center;
            border-top: 4px solid var(--primary-gold);
        }
        .poster-contact-info h3 {
            color: var(--primary-navy);
            margin-bottom: 20px;
            font-size: 24px;
        }
        .poster-address {
            color: var(--text-light);
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 1.6;
        }
        .poster-phones {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }
        .phone-number {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-teal);
            font-weight: 600;
            font-size: 16px;
        }
        .poster-websites {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .website-link {
            color: var(--primary-navy);
            text-decoration: none;
            font-size: 15px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .website-link:hover {
            color: var(--primary-gold);
            transform: translateY(-2px);
        }
        
        /* Services Section */
        .services-elegant {
            padding: 80px 0;
            background: var(--white);
        }
        .services-grid-elegant {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
        .service-card-elegant {
            background: var(--white);
            padding: 40px 30px;
            border-radius: var(--radius);
            text-align: center;
            transition: var(--transition);
            border: 1px solid #f0f0f0;
            position: relative;
            overflow: hidden;
        }
        .service-card-elegant:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-gold), var(--primary-teal));
        }
        .service-card-elegant:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
        }
        .service-icon-elegant {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--light-gold) 0%, #fff8e1 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 32px;
            color: var(--primary-teal);
            transition: var(--transition);
        }
        .service-card-elegant:hover .service-icon-elegant {
            transform: scale(1.1);
            background: linear-gradient(135deg, var(--primary-gold) 0%, #e8c154 100%);
            color: var(--white);
        }
        .service-card-elegant h3 {
            font-size: 20px;
            margin-bottom: 15px;
            color: var(--primary-navy);
        }
        .service-card-elegant p {
            color: var(--text-light);
            font-size: 14px;
            line-height: 1.7;
        }
        
        /* About Section */
        .about-elegant {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--light-bg) 0%, #f0f0f0 100%);
        }
        .about-content-elegant {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }
        .about-text-elegant h2 {
            font-size: 42px;
            margin-bottom: 30px;
            color: var(--primary-navy);
        }
        .about-text-elegant p {
            color: var(--text-light);
            margin-bottom: 25px;
            font-size: 16px;
            line-height: 1.8;
        }
        .about-features-elegant {
            list-style: none;
            margin-top: 30px;
        }
        .about-features-elegant li {
            padding: 15px 0;
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 16px;
            color: var(--primary-navy);
            border-bottom: 1px solid #eee;
        }
        .about-features-elegant li:last-child {
            border-bottom: none;
        }
        .about-features-elegant i {
            color: var(--primary-gold);
            font-size: 18px;
            background: var(--light-gold);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .about-image-elegant {
            height: 500px;
            background-size: cover;
            background-position: center;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            position: relative;
        }
        .about-image-elegant:after {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: -20px;
            bottom: -20px;
            border: 2px solid var(--primary-gold);
            border-radius: var(--radius);
            z-index: -1;
        }
        
        /* Footer */
        .footer-elegant {
            background: linear-gradient(135deg, var(--primary-navy) 0%, #0d1440 100%);
            color: var(--white);
            padding: 80px 0 40px;
            position: relative;
        }
        .footer-content-elegant {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 50px;
            margin-bottom: 60px;
        }
        .footer-column-elegant h3 {
            font-size: 22px;
            margin-bottom: 30px;
            color: var(--white);
            position: relative;
            padding-bottom: 15px;
        }
        .footer-column-elegant h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-gold);
        }
        .footer-logo-elegant {
            font-family: 'Crimson Text', serif;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--white);
            letter-spacing: 1px;
        }
        .footer-column-elegant p {
            color: rgba(255,255,255,0.7);
            margin-bottom: 25px;
            line-height: 1.8;
            font-size: 15px;
        }
        .footer-links-elegant {
            list-style: none;
        }
        .footer-links-elegant li {
            margin-bottom: 15px;
        }
        .footer-links-elegant a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: var(--transition);
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .footer-links-elegant a:hover {
            color: var(--primary-gold);
            transform: translateX(5px);
        }
        .contact-list-elegant {
            list-style: none;
        }
        .contact-list-elegant li {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            color: rgba(255,255,255,0.7);
            font-size: 15px;
        }
        .contact-list-elegant i {
            color: var(--primary-gold);
            font-size: 18px;
            margin-top: 3px;
        }
        .footer-bottom-elegant {
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.5);
            font-size: 14px;
        }

        @media (max-width: 1200px) {
            .packages-grid-luxury, .services-grid-elegant, .footer-content-elegant {
                grid-template-columns: repeat(2, 1fr);
            }
            .search-form-luxury {
                grid-template-columns: repeat(2, 1fr);
            }
            .about-content-elegant {
                grid-template-columns: 1fr;
            }
            .slide-content-luxury {
                padding-left: 50px;
            }
            .flight-deal-card {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }
            .flight-airline {
                margin-bottom: 15px;
            }
        }

        @media (max-width: 768px) {
            .header-top-bar {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            .contact-info-elegant {
                flex-direction: column;
                gap: 15px;
            }
            .main-header-elegant {
                flex-direction: column;
                gap: 30px;
                text-align: center;
            }
            .nav-elegant {
                flex-direction: column;
                gap: 20px;
            }
            .packages-grid-luxury, .services-grid-elegant, .footer-content-elegant {
                grid-template-columns: 1fr;
            }
            .search-form-luxury {
                grid-template-columns: 1fr;
            }
            .slide-content-luxury {
                padding: 0 30px;
                text-align: center;
            }
            .slide-content-luxury h2 {
                font-size: 36px;
            }
            .slide-content-luxury p {
                font-size: 18px;
            }
            .section-header h2 {
                font-size: 32px;
            }
            .poster-phones {
                flex-direction: column;
                gap: 15px;
            }
            .poster-websites {
                flex-direction: column;
                gap: 15px;
            }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
    </style>
</head>
<body>
    <!-- Elegant Header -->
    <header class="elegant-header">
        <div class="container">
            <div class="header-top-bar">
                <div class="contact-info-elegant">
                    <span><i class="fas fa-phone"></i> +34-632234216</span>
                    <span><i class="fab fa-whatsapp"></i> +34-611473217</span>
                    <span><i class="fas fa-map-marker-alt"></i> Rambla Badal 141, Barcelona</span>
                </div>
                <div class="social-elegant">
                    <a href="#" aria-label="Facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="main-header-elegant">
                <a href="#" class="logo-elegant">
                    <div class="logo-icon-elegant">
                        <i class="fas fa-kaaba"></i>
                    </div>
                    <div class="logo-text-elegant">
                        <div class="logo-main-elegant">MUSTAFA TRAVELS & TOURS</div>
                        <div class="logo-sub-elegant">PREMIUM TRAVEL EXPERIENCES</div>
                    </div>
                </a>

                <nav class="nav-elegant">
                    <a href="#home" class="active">Home</a>
                    <a href="#umrah">Umrah Packages</a>
                    <a href="#flights">Flight Deals</a>
                    <a href="#services">Services</a>
                    <a href="#about">About Us</a>
                    <a href="#contact">Contact</a>
                    <a href="https://wa.me/34611473217" class="whatsapp-btn-elegant" target="_blank">
                        <i class="fab fa-whatsapp"></i> Book Now
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- UMRAH QUOTATION BUTTON -->
    <div class="container" style="text-align: center; margin-top: 30px;">
        <button class="umrah-quote-btn" onclick="openQuotationModal()">
            <i class="fas fa-calculator"></i> 🕋 Get Custom Umrah Quotation
            <i class="fas fa-arrow-right"></i>
        </button>
    </div>

    <!-- Luxury Hero Slider -->
    <section class="luxury-slider" id="home">
        <div class="luxury-slide active" style="background-image: url('https://images.pexels.com/photos/33270402/pexels-photo-33270402.jpeg');">
            <div class="slide-overlay">
                <div class="slide-content-luxury animate-fade-in-up">
                    <h2>Premium Umrah Packages 2026</h2>
                    <p>Experience spiritual devotion with luxury accommodations near the Holy Mosques. Economy & Economy Plus packages available.</p>
                    <a href="#umrah" class="luxury-btn">Explore Packages <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="luxury-slide" style="background-image: url('https://images.pexels.com/photos/2895295/pexels-photo-2895295.jpeg');">
            <div class="slide-overlay">
                <div class="slide-content-luxury">
                    <h2>Best Umrah Deals 2026</h2>
                    <p>Starting from only €310! Includes hotels near Haram with shuttle service.</p>
                    <a href="#umrah" class="luxury-btn">View Packages <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <div class="luxury-slide" style="background-image: url('https://images.unsplash.com/photo-1591824438703-50d4c4e5d45a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');">
            <div class="slide-overlay">
                <div class="slide-content-luxury">
                    <h2>Luxury Worldwide Travel</h2>
                    <p>Discover exquisite destinations with our curated luxury travel packages for discerning travelers.</p>
                    <a href="#services" class="luxury-btn">Explore Destinations <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <div class="slider-controls">
            <div class="slider-dot-luxury active" data-slide="0"></div>
            <div class="slider-dot-luxury" data-slide="1"></div>
            <div class="slider-dot-luxury" data-slide="2"></div>
        </div>
    </section>

    <!-- Elegant Search Box -->
    <div class="container">
        <div class="search-luxury animate-fade-in-up">
            <h3>Design Your Perfect Journey</h3>
            <form class="search-form-luxury" id="searchForm">
                <div class="form-group-luxury">
                    <label>Destination</label>
                    <select id="destinationSelect">
                        <option value="">Select Destination</option>
                        <option value="makkah">Makkah, Saudi Arabia</option>
                        <option value="madina">Madina, Saudi Arabia</option>
                        <option value="dubai">Dubai, UAE</option>
                        <option value="istanbul">Istanbul, Turkey</option>
                        <option value="london">London, UK</option>
                        <option value="paris">Paris, France</option>
                    </select>
                </div>
                <div class="form-group-luxury">
                    <label>Package Type</label>
                    <select id="packageType">
                        <option value="">Select Package</option>
                        <option value="umrah">Umrah Package</option>
                        <option value="family">Family Vacation</option>
                        <option value="luxury">Luxury Holiday</option>
                        <option value="business">Business Travel</option>
                    </select>
                </div>
                <div class="form-group-luxury">
                    <label>Check In</label>
                    <input type="date" id="checkIn">
                </div>
                <div class="form-group-luxury">
                    <label>Check Out</label>
                    <input type="date" id="checkOut">
                </div>
                <button type="submit" class="search-btn-luxury" id="searchSubmit">
                    <i class="fas fa-search"></i> Search Packages
                </button>
            </form>
        </div>
    </div>

    <!-- Umrah Packages Section - UPDATED FROM POSTER -->
    <section class="packages-luxury" id="umrah">
        <div class="container">
            <div class="section-header">
                <h2>عمره پیکجز</h2>
                <h2 style="font-size: 36px; margin-top: 10px;">UMRAH PACKAGES 2026</h2>
                <p>Economy & Economy Plus packages with hotels near Haram | Valid for June 2026</p>
            </div>

            <div class="packages-grid-luxury">
                <!-- PACKAGE 1 - Economy 8 Nights -->
                <div class="package-card-luxury animate-fade-in-up">
                    <div class="package-image-luxury">
                        <i class="fas fa-hotel"></i>
                        <div class="package-badge">€310</div>
                    </div>
                    <div class="package-content-luxury">
                        <h3>Economy Package No.1</h3>
                        <div class="price">€310</div>
                        <p><strong>8 Nights</strong> | 5 Nights Makkah + 3 Nights Madinah</p>
                        <div class="hotel-note">
                            <i class="fas fa-hotel"></i> <strong>Makkah:</strong> Qila Ajyad (1000m - Shuttle or Walk)<br>
                            <i class="fas fa-hotel"></i> <strong>Madinah:</strong> Dar Ajyad 1 (750m)
                        </div>
                        <ul class="package-features-luxury">
                            <li><i class="fas fa-check-circle"></i> Without Flight Ticket</li>
                            <li><i class="fas fa-check-circle"></i> Sharing Basis</li>
                            <li><i class="fas fa-check-circle"></i> Valid June 2026</li>
                        </ul>
                        <button class="package-btn-luxury view-package-btn" data-package="economy1">Book Now</button>
                    </div>
                </div>

                <!-- PACKAGE 2 - Economy 10 Nights -->
                <div class="package-card-luxury animate-fade-in-up">
                    <div class="package-image-luxury">
                        <i class="fas fa-hotel"></i>
                        <div class="package-badge">€345</div>
                    </div>
                    <div class="package-content-luxury">
                        <h3>Economy Package No.2</h3>
                        <div class="price">€345</div>
                        <p><strong>10 Nights</strong> | 5 Nights Makkah + 5 Nights Madinah</p>
                        <div class="hotel-note">
                            <i class="fas fa-hotel"></i> <strong>Makkah:</strong> Qila Ajyad (1000m - Shuttle or Walk)<br>
                            <i class="fas fa-hotel"></i> <strong>Madinah:</strong> Dar Ajyad 1 (750m)
                        </div>
                        <ul class="package-features-luxury">
                            <li><i class="fas fa-check-circle"></i> Without Flight Ticket</li>
                            <li><i class="fas fa-check-circle"></i> Sharing Basis</li>
                            <li><i class="fas fa-check-circle"></i> Valid June 2026</li>
                        </ul>
                        <button class="package-btn-luxury view-package-btn" data-package="economy2">Book Now</button>
                    </div>
                </div>

                <!-- PACKAGE 3 - Economy Plus 8 Nights -->
                <div class="package-card-luxury animate-fade-in-up">
                    <div class="package-image-luxury">
                        <i class="fas fa-star"></i>
                        <div class="package-badge">€360</div>
                    </div>
                    <div class="package-content-luxury">
                        <h3>Economy Plus No.1</h3>
                        <div class="price">€360</div>
                        <p><strong>8 Nights</strong> | 5 Nights Makkah + 3 Nights Madinah</p>
                        <div class="hotel-note">
                            <i class="fas fa-hotel"></i> <strong>Makkah:</strong> Saif Al Majd (600m)<br>
                            <i class="fas fa-hotel"></i> <strong>Madinah:</strong> Karam Golden (550m)
                        </div>
                        <ul class="package-features-luxury">
                            <li><i class="fas fa-check-circle"></i> Without Flight Ticket</li>
                            <li><i class="fas fa-check-circle"></i> Sharing Basis</li>
                            <li><i class="fas fa-check-circle"></i> Valid June 2026</li>
                        </ul>
                        <button class="package-btn-luxury view-package-btn" data-package="economyplus1">Book Now</button>
                    </div>
                </div>

                <!-- PACKAGE 4 - Economy Plus 10 Nights -->
                <div class="package-card-luxury animate-fade-in-up">
                    <div class="package-image-luxury">
                        <i class="fas fa-star"></i>
                        <div class="package-badge">€390</div>
                    </div>
                    <div class="package-content-luxury">
                        <h3>Economy Plus No.2</h3>
                        <div class="price">€390</div>
                        <p><strong>10 Nights</strong> | 5 Nights Makkah + 5 Nights Madinah</p>
                        <div class="hotel-note">
                            <i class="fas fa-hotel"></i> <strong>Makkah:</strong> Saif Al Majd (600m)<br>
                            <i class="fas fa-hotel"></i> <strong>Madinah:</strong> Karam Golden (550m)
                        </div>
                        <ul class="package-features-luxury">
                            <li><i class="fas fa-check-circle"></i> Without Flight Ticket</li>
                            <li><i class="fas fa-check-circle"></i> Sharing Basis</li>
                            <li><i class="fas fa-check-circle"></i> Valid June 2026</li>
                        </ul>
                        <button class="package-btn-luxury view-package-btn" data-package="economyplus2">Book Now</button>
                    </div>
                </div>

                <!-- PACKAGE 5 - Economy Plus+ (with Visa & Ziyarat) -->
                <div class="package-card-luxury animate-fade-in-up">
                    <div class="package-image-luxury">
                        <i class="fas fa-crown"></i>
                        <div class="package-badge">€390</div>
                    </div>
                    <div class="package-content-luxury">
                        <h3>Economy Plus+ Package</h3>
                        <div class="price">€390</div>
                        <p><strong>8 Nights</strong> | 5 Nights Makkah + 3 Nights Madinah</p>
                        <div class="hotel-note">
                            <i class="fas fa-hotel"></i> <strong>Makkah:</strong> Swiss Khalil/Blora Moazan (350m)<br>
                            <i class="fas fa-hotel"></i> <strong>Madinah:</strong> Widyar Al Madina / Rou Khair (350m)
                        </div>
                        <ul class="package-features-luxury">
                            <li><i class="fas fa-check-circle"></i> Visa (Pakistan National)</li>
                            <li><i class="fas fa-check-circle"></i> Makkah Ziyarat (Bus)</li>
                            <li><i class="fas fa-check-circle"></i> Madinah Ziyarat (Bus)</li>
                            <li><i class="fas fa-check-circle"></i> Sharing Basis</li>
                        </ul>
                        <button class="package-btn-luxury view-package-btn" data-package="premium1">Book Now</button>
                    </div>
                </div>

                <!-- PACKAGE 6 - Near Hotel Package -->
                <div class="package-card-luxury animate-fade-in-up">
                    <div class="package-image-luxury">
                        <i class="fas fa-kaaba"></i>
                        <div class="package-badge">€415</div>
                    </div>
                    <div class="package-content-luxury">
                        <h3>Near Hotel Package</h3>
                        <div class="price">€415</div>
                        <p><strong>8 Nights</strong> | 5 Nights Makkah + 3 Nights Madinah</p>
                        <div class="hotel-note">
                            <i class="fas fa-hotel"></i> <strong>Makkah:</strong> Emar Andulusia (300m)<br>
                            <i class="fas fa-hotel"></i> <strong>Madinah:</strong> Rou Taiba (100m)
                        </div>
                        <ul class="package-features-luxury">
                            <li><i class="fas fa-check-circle"></i> Visa (Pakistan National)</li>
                            <li><i class="fas fa-check-circle"></i> Makkah Ziyarat (Bus)</li>
                            <li><i class="fas fa-check-circle"></i> Madinah Ziyarat (Bus)</li>
                            <li><i class="fas fa-check-circle"></i> Closest Hotels to Haram</li>
                        </ul>
                        <button class="package-btn-luxury view-package-btn" data-package="premium2">Book Now</button>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 40px; padding: 15px; background: #f5f5f5; border-radius: 10px;">
                <p style="font-size: 14px; color: #666;"><i class="fas fa-info-circle"></i> All packages are WITHOUT flight tickets | Sharing basis | Terms & Conditions Apply | Valid for June 2026</p>
            </div>
        </div>
    </section>

    <!-- FLIGHT DEALS SECTION -->
    <section class="flight-deals-section" id="flights">
        <div class="container">
            <div class="section-header">
                <h2>Exclusive Flight Deals</h2>
                <p>Special offers from Barcelona to Pakistan and worldwide destinations</p>
            </div>

            <div class="flight-deals-container">
                <div class="flight-deal-card animate-fade-in-up">
                    <div class="flight-airline">
                        <div class="airline-logo" style="background: #BED742;">
                            <i class="fas fa-plane"></i>
                        </div>
                        <div class="airline-name">ETIHAD AIRWAYS</div>
                    </div>
                    <div class="flight-details">
                        <div class="flight-route">
                            <span class="city">BARCELONA</span>
                            <i class="fas fa-long-arrow-alt-right"></i>
                            <span class="city">LAHORE</span>
                        </div>
                        <div class="flight-price">€ 580 EURO</div>
                        <div class="luggage-info">40KG CHECK-IN | 7KG HAND CARRY</div>
                        <div class="validity">Limited Seats Available</div>
                    </div>
                    <a href="https://wa.me/34611473217?text=I'm interested in BARCELONA to LAHORE flight (€580)" class="flight-book-btn" target="_blank"><i class="fab fa-whatsapp"></i> Book Now</a>
                </div>

                <div class="flight-deal-card animate-fade-in-up">
                    <div class="flight-airline">
                        <div class="airline-logo" style="background: #BED742;">
                            <i class="fas fa-plane"></i>
                        </div>
                        <div class="airline-name">ETIHAD AIRWAYS</div>
                    </div>
                    <div class="flight-details">
                        <div class="flight-route">
                            <span class="city">BARCELONA</span>
                            <i class="fas fa-long-arrow-alt-right"></i>
                            <span class="city">ISLAMABAD</span>
                        </div>
                        <div class="flight-price">€ 585 EURO</div>
                        <div class="luggage-info">40KG CHECK-IN | 7KG HAND CARRY</div>
                        <div class="validity">Limited Seats Available</div>
                    </div>
                    <a href="https://wa.me/34611473217?text=I'm interested in BARCELONA to ISLAMABAD flight (€585)" class="flight-book-btn" target="_blank"><i class="fab fa-whatsapp"></i> Book Now</a>
                </div>

                <div class="poster-contact-info animate-fade-in-up">
                    <h3>Mustafa Travels & Tours</h3>
                    <p class="poster-address">Rambla Badal 141-Local 1 Bajo<br>Barcelona 08028</p>
                    <div class="poster-phones">
                        <div class="phone-number"><i class="fas fa-phone"></i> +34-632234216</div>
                        <div class="phone-number"><i class="fab fa-whatsapp"></i> +34-611473217</div>
                        <div class="phone-number"><i class="fas fa-phone"></i> +34-631984997</div>
                    </div>
                    <div class="poster-websites">
                        <a href="#" class="website-link" target="_blank"><i class="fas fa-globe"></i> www.mustafatravels.com</a>
                        <a href="#" class="website-link" target="_blank"><i class="fas fa-globe"></i> www.mustafatravels.org</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-elegant" id="services">
        <div class="container">
            <div class="section-header">
                <h2>Our Premium Services</h2>
                <p>Comprehensive travel solutions tailored to your spiritual and luxury needs</p>
            </div>
            <div class="services-grid-elegant">
                <div class="service-card-elegant animate-fade-in-up"><div class="service-icon-elegant"><i class="fas fa-passport"></i></div><h3>Visa Processing</h3><p>Expert visa processing for Umrah and worldwide travel with guaranteed approval</p></div>
                <div class="service-card-elegant animate-fade-in-up"><div class="service-icon-elegant"><i class="fas fa-kaaba"></i></div><h3>Umrah Packages</h3><p>Complete spiritual journeys with luxury accommodations near Holy Mosques</p></div>
                <div class="service-card-elegant animate-fade-in-up"><div class="service-icon-elegant"><i class="fas fa-hotel"></i></div><h3>Luxury Hotels</h3><p>5-star hotel reservations worldwide with exclusive amenities and locations</p></div>
                <div class="service-card-elegant animate-fade-in-up"><div class="service-icon-elegant"><i class="fas fa-plane"></i></div><h3>Flight Booking</h3><p>Best deals on flights from Barcelona to worldwide destinations</p></div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-elegant" id="about">
        <div class="container">
            <div class="about-content-elegant">
                <div class="about-text-elegant animate-fade-in-up">
                    <h2>Experience Excellence in Spiritual Travel</h2>
                    <p>Mustafa Travels & Tours has been crafting exceptional travel experiences since 2024. We specialize in premium Umrah journeys, offering unparalleled service and attention to detail.</p>
                    <p>Our commitment to excellence ensures every spiritual journey is memorable, comfortable, and deeply meaningful. We blend traditional values with modern luxury to create unforgettable experiences.</p>
                    <ul class="about-features-elegant">
                        <li><i class="fas fa-check"></i> Expert Travel Consultants</li>
                        <li><i class="fas fa-check"></i> 24/7 Premium Support</li>
                        <li><i class="fas fa-check"></i> Exclusive Hotel Partnerships</li>
                        <li><i class="fas fa-check"></i> Personalized Service</li>
                    </ul>
                </div>
                <div class="about-image-elegant" style="background-image: url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80');"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-elegant" id="contact">
        <div class="container">
            <div class="footer-content-elegant">
                <div class="footer-column-elegant">
                    <div class="footer-logo-elegant">MUSTAFA TRAVELS & TOURS</div>
                    <p>Specialists in premium Umrah & Worldwide Travel experiences. Crafting spiritual journeys with elegance since 2024.</p>
                    <ul class="contact-list-elegant">
                        <li><i class="fas fa-map-marker-alt"></i> Rambla Badal 141 Local 1 Bajo, Barcelona 08028</li>
                        <li><i class="fas fa-phone"></i> +34-632234216</li>
                        <li><i class="fab fa-whatsapp"></i> +34-611473217</li>
                        <li><i class="fas fa-envelope"></i> mustafatravelstours@gmail.com</li>
                    </ul>
                </div>
                <div class="footer-column-elegant">
                    <h3>Quick Links</h3>
                    <ul class="footer-links-elegant">
                        <li><a href="#home"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="#umrah"><i class="fas fa-chevron-right"></i> Umrah Packages</a></li>
                        <li><a href="#flights"><i class="fas fa-chevron-right"></i> Flight Deals</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right"></i> Services</a></li>
                        <li><a href="#about"><i class="fas fa-chevron-right"></i> About Us</a></li>
                        <li><a href="#contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column-elegant">
                    <h3>Our Services</h3>
                    <ul class="footer-links-elegant">
                        <li><a href="#umrah"><i class="fas fa-chevron-right"></i> Umrah Packages</a></li>
                        <li><a href="#flights"><i class="fas fa-chevron-right"></i> Flight Booking</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right"></i> Hotel Reservation</a></li>
                        <li><a href="#services"><i class="fas fa-chevron-right"></i> Visa Services</a></li>
                    </ul>
                </div>
                <div class="footer-column-elegant">
                    <h3>Business Hours</h3>
                    <ul class="footer-links-elegant">
                        <li><i class="far fa-clock"></i> Monday - Thursday: 10:30 AM - 8:30 PM</li>
                        <li><i class="far fa-clock"></i> Friday: 10:30 AM - 1:00 PM & 3:00 PM - 8:30 PM</li>
                        <li><i class="far fa-clock"></i> Saturday: 10:30 AM - 7:30 PM</li>
                        <li><i class="far fa-clock"></i> Sunday: Closed</li>
                        <li><i class="fas fa-phone-alt"></i> 24/7 Emergency Support</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom-elegant">
                <p>&copy; 2024 Mustafa Travels & Tours. All rights reserved. | Crafted with <i class="fas fa-heart" style="color: #d4af37;"></i> for spiritual journeys</p>
                <p style="margin-top: 10px; font-size: 12px;">Terms & Conditions Apply | Valid for June 2026</p>
            </div>
        </div>
    </footer>

    <!-- ========== UMRAH QUOTATION MODAL ========== -->
    <div id="quotationModal" class="quotation-modal">
        <div class="quotation-modal-content">
            <button class="modal-close-btn" onclick="closeQuotationModal()">✕</button>
            <div class="modal-inner">
                <div class="quo-wrapper">
                    <!-- LEFT: HOTEL SECTION -->
                    <div class="quo-hotel-panel">
                        <div class="quo-city-tabs">
                            <button class="quo-city-tab active" data-city="makkah">🕋 MAKKAH HOTELS</button>
                            <button class="quo-city-tab" data-city="madinah">🕌 MADINAH HOTELS</button>
                        </div>
                        
                        <div class="quo-selector">
                            <label>🏨 Select Hotel</label>
                            <select id="quoHotelSelect" class="quo-dropdown"></select>
                        </div>
                        
                        <div id="quoHotelDetails" class="quo-details">
                            <div style="text-align: center; padding: 40px; color: #999;">Loading hotels...</div>
                        </div>
                    </div>

                    <!-- RIGHT: EXTRAS & TOTAL -->
                    <div class="quo-extras-panel">
                        <div class="quo-extras-header">
                            <h3>➕ Additional Services</h3>
                        </div>
                        
                        <div class="quo-service">
                            <label class="quo-checkbox">
                                <input type="checkbox" id="quoMakkahZiyarat"> 🕌 Makkah Ziyarat (by bus) - +7 SAR fee
                            </label>
                            <label class="quo-checkbox">
                                <input type="checkbox" id="quoMadinahZiyarat"> 🕌 Madinah Ziyarat (by bus) - +7 SAR fee
                            </label>
                        </div>
                        
                        <div class="quo-service">
                            <label style="font-weight:600;">🛂 Visa Fee</label>
                            <select id="quoVisaSelect" class="quo-visa">
                                <option value="220">Pakistani Umrah Visa — 220 EUR</option>
                                <option value="120">Spanish Umrah Visa — 120 EUR</option>
                            </select>
                        </div>
                        
                        <div class="quo-service">
                            <label style="font-weight:600; margin-bottom:10px; display:block;">🚖 Private Taxi Transfers</label>
                            <div class="quo-taxi-item">
                                <span>Jeddah Airport → Makkah (350 SAR)</span>
                                <button class="quo-taxi-btn" data-taxi="jeddahMakkah">➕ Select</button>
                            </div>
                            <div class="quo-taxi-item">
                                <span>Makkah → Madinah (400 SAR)</span>
                                <button class="quo-taxi-btn" data-taxi="makkahMadinah">➕ Select</button>
                            </div>
                            <div class="quo-taxi-item">
                                <span>Madinah → Jeddah Airport (350 SAR)</span>
                                <button class="quo-taxi-btn" data-taxi="madinahJeddah">➕ Select</button>
                            </div>
                        </div>
                        
                        <div class="quo-summary">
                            <h4>💰 TOTAL QUOTATION</h4>
                            <div class="quo-total" id="quoGrandTotal">0.00 €</div>
                            <div class="quo-not-included">
                                ✈️ NOT INCLUDED: Airline ticket • Meals • Personal expenses
                            </div>
                            <a href="#" id="quoWhatsappBtn" class="quo-whatsapp">
                                <i class="fab fa-whatsapp"></i> Send Quotation via WhatsApp
                            </a>
                        </div>
                        
                        <div class="quo-better">
                            🌟 Want better rates than these hotels? <a href="#" id="quoBetterRateBtn">Contact our B2B desk →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal Functions
        function openQuotationModal() {
            document.getElementById('quotationModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeQuotationModal() {
            document.getElementById('quotationModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        // Close modal on outside click
        document.getElementById('quotationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeQuotationModal();
            }
        });

        // ========== UMRAH QUOTATION JAVASCRIPT ==========
        (function() {
            const SAR_TO_EUR = 0.245;
            
            const MAKKAH_HOTELS = [
                { name: "Ajwa Zaifa", distance: "Shuttle Service", rates: { sharing: 13, quad: 13, trp: 15, dbl: 18, single: 25 } },
                { name: "Qila Ajyad", distance: "1000 m", rates: { sharing: 17, quad: 17, trp: 20, dbl: 25, single: 35 } },
                { name: "Dyar Matar", distance: "1200 m", rates: { sharing: 19, quad: 19, trp: 23, dbl: 28, single: 40 } },
                { name: "Jada Khalil", distance: "1200 m", rates: { sharing: 21, quad: 21, trp: 25, dbl: 32, single: 45 } },
                { name: "Kiswah Tower", distance: "Shuttle Service", rates: { sharing: 24, quad: 24, trp: 29, dbl: 37, single: 53 } },
                { name: "Multiqa Ibadat", distance: "750-800 m", rates: { sharing: 24, quad: 24, trp: 29, dbl: 37, single: 53 } },
                { name: "Saif Al Majd", distance: "600-650 m", rates: { sharing: 31, quad: 31, trp: 38, dbl: 48, single: 70 } },
                { name: "Jafria", distance: "550-600 m", rates: { sharing: 31, quad: 31, trp: 38, dbl: 48, single: 70 } },
                { name: "Jawarat Bait", distance: "600 m", rates: { sharing: 38, quad: 38, trp: 43, dbl: 55, single: 80 } },
                { name: "Badar Masa", distance: "600 m", rates: { sharing: 57, quad: 57, trp: 70, dbl: 92, single: 135 } },
                { name: "Swiss Khalil", distance: "350-400 m", rates: { sharing: 49, quad: 49, trp: 63, dbl: 93, single: 93 } },
                { name: "Emar Andulusia", distance: "300 m", rates: { sharing: 68, quad: 68, trp: 88, dbl: 130, single: 130 } }
            ];
            
            const MADINAH_HOTELS = [
                { name: "Kinan Madina", distance: "900 m", rates: { sharing: 25, quad: 25, trp: 30, dbl: 38, single: 55 } },
                { name: "Dar Ajyad 1", distance: "750 m", rates: { sharing: 29, quad: 29, trp: 35, dbl: 45, single: 65 } },
                { name: "Abdullah Fouzan", distance: "600 m", rates: { sharing: 35, quad: 35, trp: 43, dbl: 55, single: 80 } },
                { name: "Karam Golden", distance: "550 m", rates: { sharing: 37, quad: 37, trp: 45, dbl: 58, single: 85 } },
                { name: "Ansar Plus", distance: "500 m", rates: { sharing: 38, quad: 38, trp: 46, dbl: 60, single: 88 } },
                { name: "Widyar Al Madina", distance: "350 m", rates: { sharing: 40, quad: 40, trp: 49, dbl: 63, single: 93 } },
                { name: "Rou Taiba", distance: "100 m", rates: { sharing: 55, quad: 55, trp: 63, dbl: 82, single: 120 } }
            ];
            
            let currentCity = 'makkah';
            let currentHotel = null;
            let selectedRoom = 'sharing';
            let nights = 5;
            let roomOverrides = {};
            let taxiSelections = { jeddahMakkah: false, makkahMadinah: false, madinahJeddah: false };
            
            function convertSar(sar) { return (sar * SAR_TO_EUR).toFixed(2); }
            
            function getNightlyEuro(room) {
                if (!currentHotel) return 0;
                let base = currentHotel.rates[room];
                if (roomOverrides[room] !== undefined) base = roomOverrides[room];
                return parseFloat(convertSar(base + 50));
            }
            
            function getHotelTotal() { return getNightlyEuro(selectedRoom) * nights; }
            
            function getZiyaratTotal() {
                let total = 0;
                if (document.getElementById('quoMakkahZiyarat')?.checked) total += parseFloat(convertSar(35 + 7));
                if (document.getElementById('quoMadinahZiyarat')?.checked) total += parseFloat(convertSar(35 + 7));
                return total;
            }
            
            function getTaxiTotal() {
                let total = 0;
                if (taxiSelections.jeddahMakkah) total += parseFloat(convertSar(350));
                if (taxiSelections.makkahMadinah) total += parseFloat(convertSar(400));
                if (taxiSelections.madinahJeddah) total += parseFloat(convertSar(350));
                return total;
            }
            
            function updateTotal() {
                const total = getHotelTotal() + parseFloat(document.getElementById('quoVisaSelect').value) + getTaxiTotal() + getZiyaratTotal();
                document.getElementById('quoGrandTotal').innerHTML = total.toFixed(2) + ' €';
            }
            
            function populateHotels() {
                const hotels = currentCity === 'makkah' ? MAKKAH_HOTELS : MADINAH_HOTELS;
                const select = document.getElementById('quoHotelSelect');
                select.innerHTML = '';
                hotels.forEach((h, i) => { select.innerHTML += `<option value="${i}">${h.name} — ${h.distance}</option>`; });
                loadHotel(0);
            }
            
            function loadHotel(index) {
                const hotels = currentCity === 'makkah' ? MAKKAH_HOTELS : MADINAH_HOTELS;
                currentHotel = hotels[index];
                if (!currentHotel) return;
                const rooms = Object.keys(currentHotel.rates);
                if (!rooms.includes(selectedRoom)) selectedRoom = rooms[0];
                
                let html = `<div class="quo-hotel-name">🏨 ${currentHotel.name}</div>
                    <div><span class="quo-distance">📍 ${currentHotel.distance}</span><span class="quo-perbed">🛏️ Per bed / night</span></div>
                    <div class="quo-nights"><label>📅 Number of Nights: </label><input type="number" id="quoNights" value="${nights}" min="1" max="30"></div>
                    <div class="quo-room-grid">`;
                
                rooms.forEach(room => {
                    const val = roomOverrides[room] !== undefined ? roomOverrides[room] : currentHotel.rates[room];
                    const nightly = getNightlyEuro(room);
                    html += `<div class="quo-room ${selectedRoom === room ? 'selected' : ''}" data-room="${room}">
                        <div class="quo-room-left">
                            <input type="radio" name="quoRoomRadio" value="${room}" ${selectedRoom === room ? 'checked' : ''}>
                            <span class="quo-room-badge">${room.toUpperCase()}</span>
                            <input type="number" class="quo-rate-input" data-room="${room}" value="${val}" step="5" style="width:80px;"> <span>SAR</span>
                        </div>
                        <div><span class="quo-price">€${nightly.toFixed(2)}</span> /night/bed</div>
                    </div>`;
                });
                html += `</div>`;
                document.getElementById('quoHotelDetails').innerHTML = html;
                
                document.getElementById('quoNights').addEventListener('change', (e) => { nights = parseInt(e.target.value) || 1; updateTotal(); });
                document.querySelectorAll('input[name="quoRoomRadio"]').forEach(radio => {
                    radio.addEventListener('change', (e) => { selectedRoom = e.target.value; loadHotel(index); updateTotal(); });
                });
                document.querySelectorAll('.quo-rate-input').forEach(inp => {
                    const room = inp.getAttribute('data-room');
                    inp.addEventListener('input', (e) => {
                        let v = parseFloat(e.target.value);
                        if (!isNaN(v)) roomOverrides[room] = v;
                        else delete roomOverrides[room];
                        loadHotel(index);
                        updateTotal();
                    });
                });
                updateTotal();
            }
            
            document.querySelectorAll('.quo-city-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelectorAll('.quo-city-tab').forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    currentCity = tab.getAttribute('data-city');
                    roomOverrides = {};
                    selectedRoom = 'sharing';
                    nights = 5;
                    populateHotels();
                });
            });
            
            document.querySelectorAll('.quo-taxi-btn').forEach(btn => {
                const taxi = btn.getAttribute('data-taxi');
                btn.addEventListener('click', () => {
                    taxiSelections[taxi] = !taxiSelections[taxi];
                    btn.textContent = taxiSelections[taxi] ? '✓ Selected' : '➕ Select';
                    btn.classList.toggle('active', taxiSelections[taxi]);
                    updateTotal();
                });
            });
            
            document.getElementById('quoMakkahZiyarat').addEventListener('change', () => updateTotal());
            document.getElementById('quoMadinahZiyarat').addEventListener('change', () => updateTotal());
            document.getElementById('quoVisaSelect').addEventListener('change', () => updateTotal());
            document.getElementById('quoHotelSelect').addEventListener('change', (e) => loadHotel(parseInt(e.target.value)));
            
            document.getElementById('quoWhatsappBtn').addEventListener('click', (e) => {
                e.preventDefault();
                const msg = `🕋 UMRAH QUOTATION\nHotel: ${currentHotel?.name}\nRoom: ${selectedRoom.toUpperCase()}\nNights: ${nights}\nTotal: ${document.getElementById('quoGrandTotal').innerHTML}\n\nPlease process my booking.`;
                window.open(`https://wa.me/34611473217?text=${encodeURIComponent(msg)}`, '_blank');
            });
            
            document.getElementById('quoBetterRateBtn').addEventListener('click', (e) => {
                e.preventDefault();
                window.open(`https://wa.me/34611473217?text=${encodeURIComponent('Need better rates for Umrah hotels - B2B partner inquiry')}`, '_blank');
            });
            
            populateHotels();
        })();

        // Slider Functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.luxury-slide');
        const dots = document.querySelectorAll('.slider-dot-luxury');

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            slides[index].classList.add('active');
            dots[index].classList.add('active');
            currentSlide = index;
        }

        setInterval(() => { currentSlide = (currentSlide + 1) % slides.length; showSlide(currentSlide); }, 5000);
        dots.forEach((dot, index) => { dot.addEventListener('click', () => showSlide(index)); });

        // Package Data for Modal
        const packageData = {
            economy1: { title: "Economy Package No.1", price: "€310", description: "5 Nights Makkah + 3 Nights Madinah | Qila Ajyad (Makkah) + Dar Ajyad 1 (Madinah)", features: ["5 Nights Makkah - Qila Ajyad (1000m)", "3 Nights Madinah - Dar Ajyad 1 (750m)", "Without Flight Ticket", "Sharing Basis", "Valid June 2026"] },
            economy2: { title: "Economy Package No.2", price: "€345", description: "5 Nights Makkah + 5 Nights Madinah | Qila Ajyad (Makkah) + Dar Ajyad 1 (Madinah)", features: ["5 Nights Makkah - Qila Ajyad (1000m)", "5 Nights Madinah - Dar Ajyad 1 (750m)", "Without Flight Ticket", "Sharing Basis", "Valid June 2026"] },
            economyplus1: { title: "Economy Plus No.1", price: "€360", description: "5 Nights Makkah + 3 Nights Madinah | Saif Al Majd (Makkah) + Karam Golden (Madinah)", features: ["5 Nights Makkah - Saif Al Majd (600m)", "3 Nights Madinah - Karam Golden (550m)", "Without Flight Ticket", "Sharing Basis", "Valid June 2026"] },
            economyplus2: { title: "Economy Plus No.2", price: "€390", description: "5 Nights Makkah + 5 Nights Madinah | Saif Al Majd (Makkah) + Karam Golden (Madinah)", features: ["5 Nights Makkah - Saif Al Majd (600m)", "5 Nights Madinah - Karam Golden (550m)", "Without Flight Ticket", "Sharing Basis", "Valid June 2026"] },
            premium1: { title: "Economy Plus+ Package", price: "€390", description: "5 Nights Makkah + 3 Nights Madinah | Swiss Khalil + Widyar Al Madina", features: ["Makkah: Swiss Khalil/Blora Moazan (350m)", "Madinah: Widyar Al Madina / Rou Khair (350m)", "Visa (Pakistan National) Included", "Makkah Ziyarat (Bus)", "Madinah Ziyarat (Bus)", "Sharing Basis"] },
            premium2: { title: "Near Hotel Package", price: "€415", description: "5 Nights Makkah + 3 Nights Madinah | Closest hotels to Haram", features: ["Makkah: Emar Andulusia (300m)", "Madinah: Rou Taiba (100m)", "Visa (Pakistan National) Included", "Makkah Ziyarat (Bus)", "Madinah Ziyarat (Bus)", "Closest Hotels to Haram"] }
        };

        document.querySelectorAll('.view-package-btn').forEach(button => {
            button.addEventListener('click', function() {
                const packageType = this.getAttribute('data-package');
                const pkg = packageData[packageType];
                if (pkg) {
                    const modalHTML = `<div class="modal-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);display:flex;align-items:center;justify-content:center;z-index:1000;"><div class="modal-content" style="background:white;padding:40px;border-radius:20px;max-width:550px;width:90%;position:relative;"><button class="close-modal" style="position:absolute;top:15px;right:15px;background:none;border:none;font-size:24px;cursor:pointer;">&times;</button><h2 style="color:#1a237e;margin-bottom:10px;">${pkg.title}</h2><div style="color:#d4af37;font-size:28px;font-weight:bold;margin:15px 0;">${pkg.price}</div><p style="color:#666;margin-bottom:20px;">${pkg.description}</p><h3 style="color:#1a237e;margin-bottom:15px;">Package Includes:</h3><ul style="list-style:none;margin-bottom:30px;">${pkg.features.map(f => `<li style="padding:8px 0;border-bottom:1px solid #eee;display:flex;align-items:center;gap:10px;"><i class="fas fa-check" style="color:#d4af37;"></i> ${f}</li>`).join('')}</ul><a href="https://wa.me/34611473217?text=I'm interested in ${pkg.title} (${pkg.price})" class="whatsapp-btn-elegant" style="display:block;text-align:center;text-decoration:none;"><i class="fab fa-whatsapp"></i> Book Now on WhatsApp</a></div></div>`;
                    document.body.insertAdjacentHTML('beforeend', modalHTML);
                    document.querySelector('.close-modal').addEventListener('click', () => { document.querySelector('.modal-overlay').remove(); });
                    document.querySelector('.modal-overlay').addEventListener('click', (e) => { if (e.target.classList.contains('modal-overlay')) document.querySelector('.modal-overlay').remove(); });
                }
            });
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                const targetElement = document.querySelector(targetId);
                if(targetElement) window.scrollTo({ top: targetElement.offsetTop - 100, behavior: 'smooth' });
            });
        });

        // Search form
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const destination = document.getElementById('destinationSelect').value;
            const packageType = document.getElementById('packageType').value;
            if (!destination || !packageType) { alert('Please select both destination and package type'); return; }
            window.open(`https://wa.me/34611473217?text=${encodeURIComponent(`Hello! I'm looking for ${packageType} packages to ${destination}. Please send me details.`)}`, '_blank');
        });

        const today = new Date().toISOString().split('T')[0];
        document.getElementById('checkIn').min = today;
        document.getElementById('checkOut').min = today;
        document.getElementById('checkIn').addEventListener('change', function() { document.getElementById('checkOut').min = this.value; });
    </script>
</body>
</html>
