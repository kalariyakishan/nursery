<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NEW VRUNDAVAN NURSERY | Luxury Botanical Experience</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --forest-green: #0D2B1D;
            --earthy-sage: #8B9D83;
            --brushed-gold: #C5A059;
            --warm-copper: #B87333;
            --off-white: #FDFBF7;
            --dark-green: #051A10;
            --transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            --gold-leaf: linear-gradient(135deg, #C5A059 0%, #F5E0A3 50%, #C5A059 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--off-white);
            color: var(--forest-green);
            line-height: 1.6;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .serif {
            font-family: 'Cormorant Garamond', serif;
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--off-white); }
        ::-webkit-scrollbar-thumb { background: var(--brushed-gold); }

        /* --- Utility Classes --- */
        .gold-text {
            background: var(--gold-leaf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }

        .gold-leaf-flourish {
            position: relative;
            display: inline-block;
        }

        .gold-leaf-flourish::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 2px;
            background: var(--gold-leaf);
        }

        /* --- Navigation --- */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 2rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: var(--transition);
        }

        nav.scrolled {
            padding: 1rem 5%;
            background: rgba(253, 251, 247, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: var(--forest-green);
        }

        .logo i { color: var(--brushed-gold); }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            gap: 3rem;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--forest-green);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            transition: var(--transition);
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--brushed-gold);
            transition: var(--transition);
        }

        .nav-link:hover::after { width: 100%; }
        .nav-link:hover { color: var(--brushed-gold); }

        /* --- Hero Section --- */
        .hero {
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/images/redesign/hero.png') center/cover no-repeat;
            transform: scale(1.1);
            animation: zoomOut 20s infinite alternate;
            z-index: -1;
        }

        @keyframes zoomOut {
            from { transform: scale(1.1); }
            to { transform: scale(1); }
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(13, 43, 29, 0.2) 0%, rgba(13, 43, 29, 0.6) 100%);
        }

        .hero-content {
            text-align: center;
            color: white;
            z-index: 10;
            max-width: 1000px;
            padding: 0 5%;
        }

        .hero h1 {
            font-size: clamp(3rem, 10vw, 6rem);
            font-weight: 300;
            line-height: 1;
            margin-bottom: 2rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .hero h1 span {
            display: block;
            font-style: italic;
            font-size: 0.5em;
            letter-spacing: 0.3em;
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .hero-cta {
            margin-top: 3rem;
        }

        .btn-gold {
            background: var(--gold-leaf);
            color: var(--dark-green);
            padding: 1.2rem 3rem;
            border-radius: 0;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            transition: var(--transition);
            display: inline-block;
            border: 1px solid transparent;
        }

        .btn-gold:hover {
            background: transparent;
            color: white;
            border-color: var(--brushed-gold);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(197, 160, 89, 0.3);
        }

        /* --- Sections --- */
        section {
            padding: 10rem 10%;
            position: relative;
        }

        .section-tag {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4em;
            color: var(--brushed-gold);
            display: block;
            margin-bottom: 2rem;
            text-align: center;
        }

        .section-title {
            font-size: 4rem;
            text-align: center;
            margin-bottom: 5rem;
            font-weight: 400;
            line-height: 1.1;
        }

        /* --- Narrative Section --- */
        .narrative {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 8rem;
            align-items: center;
        }

        .narrative-image {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.15);
        }

        .narrative-image img {
            width: 100%;
            display: block;
            transition: var(--transition);
        }

        .narrative-image:hover img { transform: scale(1.05); }

        .narrative-content h2 {
            font-size: 3.5rem;
            margin-bottom: 2rem;
            line-height: 1.1;
        }

        .narrative-content p {
            font-size: 1.2rem;
            color: var(--earthy-sage);
            margin-bottom: 3rem;
            font-weight: 300;
        }

        .narrative-icons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .narrative-icon-item {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .narrative-icon-item i {
            color: var(--brushed-gold);
        }

        .narrative-icon-item h4 {
            font-size: 1.4rem;
            font-weight: 600;
        }

        /* --- Botanical Grid --- */
        .botanical-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            margin-top: 5rem;
        }

        .plant-card {
            position: relative;
            height: 600px;
            overflow: hidden;
            background: var(--dark-green);
            transition: var(--transition);
            cursor: pointer;
        }

        .plant-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.8;
            transition: var(--transition);
        }

        .plant-card:hover img {
            opacity: 0.5;
            transform: scale(1.1);
        }

        .plant-card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 3rem;
            color: white;
            z-index: 2;
        }

        .plant-card h3 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .plant-card p {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--brushed-gold);
            opacity: 0;
            transform: translateY(20px);
            transition: var(--transition);
        }

        .plant-card:hover p {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Linen Banner --- */
        .linen-banner {
            background: url('/images/redesign/linen_texture.png') center/cover;
            padding: 6rem 5%;
            position: relative;
            margin: 5rem 0;
        }

        .linen-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(139, 157, 131, 0.4);
        }

        .linen-content {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .linen-item {
            text-align: center;
            color: var(--forest-green);
        }

        .linen-item i {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--brushed-gold);
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .linen-item h4 {
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: 700;
        }

        /* --- Art Wall --- */
        .art-wall {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: repeat(2, 300px);
            gap: 2rem;
        }

        .art-item {
            position: relative;
            overflow: hidden;
            border-radius: 5px;
        }

        .art-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .art-item:hover img { transform: scale(1.1); }

        .art-item-1 { grid-column: span 8; grid-row: span 2; }
        .art-item-2 { grid-column: span 4; grid-row: span 1; }
        .art-item-3 { grid-column: span 4; grid-row: span 1; }

        /* --- Visit Us --- */
        .visit-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: start;
        }

        .map-box {
            height: 600px;
            background: #e5e5e5;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(197, 160, 89, 0.3);
        }

        .map-placeholder {
            width: 100%;
            height: 100%;
            background: url('https://api.mapbox.com/styles/v1/mapbox/light-v10/static/70.4764,21.0186,12,0/1200x600?access_token=pk.eyJ1IjoiZGVzaWduZXIiLCJhIjoiY2p4eHg0eHh4eHh4eHh4eHh4eHh4In0.placeholder') center/cover;
            filter: grayscale(1) sepia(0.2) contrast(1.1);
        }

        .map-pin {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--brushed-gold);
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translate(-50%, -50%); }
            50% { transform: translate(-50%, -70%); }
        }

        .booking-calendar {
            background: white;
            padding: 4rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .calendar-header {
            margin-bottom: 2rem;
            text-align: center;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            margin-bottom: 2rem;
        }

        .cal-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            cursor: pointer;
            transition: var(--transition);
        }

        .cal-day:hover {
            background: var(--off-white);
            color: var(--brushed-gold);
        }

        .cal-day.active {
            background: var(--brushed-gold);
            color: white;
        }

        .cal-labels {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 1rem;
            color: var(--earthy-sage);
        }

        /* --- Question Card --- */
        .question-card {
            background: var(--dark-green);
            padding: 6rem;
            text-align: center;
            border: 1px solid var(--brushed-gold);
            position: relative;
            margin-top: 5rem;
            overflow: hidden;
        }

        .question-card::before {
            content: '';
            position: absolute;
            inset: 10px;
            border: 1px solid rgba(197, 160, 89, 0.2);
            pointer-events: none;
        }

        .question-card h2 {
            color: white;
            font-size: 3.5rem;
            margin-bottom: 2rem;
        }

        .question-card p {
            color: var(--earthy-sage);
            max-width: 600px;
            margin: 0 auto 3rem;
            font-size: 1.2rem;
        }

        /* --- Footer --- */
        footer {
            background: var(--dark-green);
            color: white;
            padding: 10rem 10% 5rem;
            position: relative;
        }

        .footer-leaf-overlay {
            position: absolute;
            top: 0;
            right: 0;
            width: 400px;
            height: 400px;
            background: url('https://www.transparenttextures.com/patterns/leaf.png');
            opacity: 0.05;
            pointer-events: none;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 5rem;
            margin-bottom: 5rem;
        }

        .footer-logo h3 {
            font-size: 2rem;
            letter-spacing: 0.1em;
            margin-bottom: 1.5rem;
        }

        .footer-logo p {
            color: var(--earthy-sage);
            font-weight: 300;
            margin-bottom: 2rem;
        }

        .footer-col h4 {
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 0.9rem;
            margin-bottom: 2rem;
            color: var(--brushed-gold);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li { margin-bottom: 1rem; }

        .footer-links a {
            text-decoration: none;
            color: white;
            opacity: 0.6;
            transition: var(--transition);
        }

        .footer-links a:hover { opacity: 1; color: var(--brushed-gold); }

        .social-icons {
            display: flex;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .social-icons a {
            color: white;
            opacity: 0.6;
            transition: var(--transition);
        }

        .social-icons a:hover { opacity: 1; color: var(--brushed-gold); transform: translateY(-3px); }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            opacity: 0.4;
            letter-spacing: 0.1em;
        }

        /* --- Animations --- */
        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: var(--transition);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Responsive --- */
        @media (max-width: 1200px) {
            .narrative, .visit-container { grid-template-columns: 1fr; }
            .botanical-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            section { padding: 5rem 5%; }
            .hero h1 { font-size: 3.5rem; }
            .section-title { font-size: 2.5rem; }
            .botanical-grid { grid-template-columns: 1fr; }
            .art-wall { grid-template-columns: 1fr; grid-template-rows: auto; }
            .art-item-1, .art-item-2, .art-item-3 { grid-column: span 1; }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav id="main-nav">
        <a href="#" class="logo">
            <i data-lucide="leaf" size="32"></i>
            <span class="logo-text">Vrundavan</span>
        </a>
        <ul class="nav-links">
            <li><a href="#about" class="nav-link">Our Story</a></li>
            <li><a href="#varieties" class="nav-link">Collections</a></li>
            <li><a href="#farm" class="nav-link">The Farm</a></li>
            <li><a href="#visit" class="nav-link">Visit Us</a></li>
        </ul>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="/login" class="nav-link" style="font-size: 0.7rem; font-weight: 700;">Admin Login</a>
            <a href="#visit" class="btn-gold" style="padding: 0.8rem 1.5rem; font-size: 0.7rem;">Book Visit</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-overlay"></div>
        <div class="hero-content reveal">
            <h1>
                <span>Welcome to</span>
                NEW VRUNDAVAN<br>
                <span class="gold-text gold-leaf-flourish">NURSERY</span>
            </h1>
            <div class="hero-cta">
                <a href="#varieties" class="btn-gold">તમારો છોડ શોધો</a>
            </div>
        </div>
    </section>

    <!-- Narrative Section -->
    <section id="about" class="reveal">
        <span class="section-tag">Since 1995</span>
        <div class="narrative">
            <div class="narrative-image">
                <img src="/images/redesign/greenhouse.png" alt="Our Greenhouse">
            </div>
            <div class="narrative-content">
                <h2>Cultivating Nature's Best with Heart and Science</h2>
                <p>Nestled in the heart of Gadu, Gujarat, our nursery is more than just a garden center. It is a botanical sanctuary where tradition meets modern horticultural excellence.</p>
                <div class="narrative-icons">
                    <div class="narrative-icon-item">
                        <i data-lucide="award" size="32"></i>
                        <h4>Premium Quality</h4>
                    </div>
                    <div class="narrative-icon-item">
                        <i data-lucide="sun" size="32"></i>
                        <h4>Grown Sustainably</h4>
                    </div>
                    <div class="narrative-icon-item">
                        <i data-lucide="heart" size="32"></i>
                        <h4>Expert Care</h4>
                    </div>
                    <div class="narrative-icon-item">
                        <i data-lucide="truck" size="32"></i>
                        <h4>Nationwide Delivery</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Botanical Varieties -->
    <section id="varieties">
        <span class="section-tag">Explore Collections</span>
        <h2 class="section-title">Our Botanical Varieties</h2>
        <div class="botanical-grid">
            <div class="plant-card reveal" data-delay="0">
                <img src="/images/redesign/house_plants.png" alt="House Plants">
                <div class="plant-card-content">
                    <h3>House Plants</h3>
                    <p>Artisanal Curation</p>
                </div>
            </div>
            <div class="plant-card reveal" data-delay="100">
                <img src="/images/redesign/indoor_plants.png" alt="Ornamental & Indoor">
                <div class="plant-card-content">
                    <h3>Ornamental & Indoor</h3>
                    <p>Architectural Beauty</p>
                </div>
            </div>
            <div class="plant-card reveal" data-delay="200">
                <img src="/images/redesign/fruit_plants.png" alt="Fruit Plants">
                <div class="plant-card-content">
                    <h3>Fruit Plants</h3>
                    <p>Nature's Bounty</p>
                </div>
            </div>
            <div class="plant-card reveal" data-delay="300">
                <img src="/images/redesign/medicinal_herbs.png" alt="Medicinal Herbs">
                <div class="plant-card-content">
                    <h3>Medicinal Herbs</h3>
                    <p>Ancient Wisdom</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Linen Banner -->
    <div class="linen-banner">
        <div class="linen-content">
            <div class="linen-item reveal">
                <i data-lucide="sprout"></i>
                <h4>Organic Roots</h4>
            </div>
            <div class="linen-item reveal" data-delay="100">
                <i data-lucide="droplets"></i>
                <h4>Pure Water</h4>
            </div>
            <div class="linen-item reveal" data-delay="200">
                <i data-lucide="shield-check"></i>
                <h4>Disease Free</h4>
            </div>
            <div class="linen-item reveal" data-delay="300">
                <i data-lucide="users"></i>
                <h4>Expert Advice</h4>
            </div>
        </div>
    </div>

    <!-- Art Wall / Snapshots -->
    <section id="farm">
        <span class="section-tag">Our Legacy</span>
        <h2 class="section-title">Snapshots from Our Farm</h2>
        <div class="art-wall">
            <div class="art-item art-item-1 reveal">
                <img src="/images/redesign/farm_1.png" alt="Farm View">
            </div>
            <div class="art-item art-item-2 reveal" data-delay="100">
                <img src="/images/redesign/farm_2.png" alt="Careful Tending">
            </div>
            <div class="art-item art-item-3 reveal" data-delay="200">
                <img src="/images/redesign/greenhouse.png" alt="Greenhouse Details">
            </div>
        </div>
    </section>

    <!-- Visit Us -->
    <section id="visit">
        <span class="section-tag">Get in Touch</span>
        <h2 class="section-title">Plan Your Visit to Our Nursery</h2>
        <div class="visit-container">
            <div class="map-box reveal">
                <iframe 
                    src="https://maps.google.com/maps?q=21.052162,70.286334&z=15&output=embed" 
                    width="100%" 
                    height="100%" 
                    style="border:0; filter: grayscale(1) sepia(0.2) contrast(1.1);" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
            <div class="booking-calendar reveal" data-delay="200">
                <div class="calendar-header">
                    <h3>May 2026</h3>
                </div>
                <div class="cal-labels">
                    <div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div><div>Sun</div>
                </div>
                <div class="calendar-grid">
                    <!-- Placeholder days -->
                    <div class="cal-day">27</div><div class="cal-day">28</div><div class="cal-day">29</div><div class="cal-day">30</div>
                    <div class="cal-day">1</div><div class="cal-day active">2</div><div class="cal-day">3</div><div class="cal-day">4</div>
                    <div class="cal-day">5</div><div class="cal-day">6</div><div class="cal-day">7</div><div class="cal-day">8</div>
                    <div class="cal-day">9</div><div class="cal-day">10</div><div class="cal-day">11</div><div class="cal-day">12</div>
                    <div class="cal-day">13</div><div class="cal-day">14</div><div class="cal-day">15</div><div class="cal-day">16</div>
                    <div class="cal-day">17</div><div class="cal-day">18</div><div class="cal-day">19</div><div class="cal-day">20</div>
                    <div class="cal-day">21</div><div class="cal-day">22</div><div class="cal-day">23</div><div class="cal-day">24</div>
                </div>
                <a href="https://wa.me/919925575862" class="btn-gold" style="width: 100%; text-align: center;">Book Appointment</a>
            </div>
        </div>
    </section>

    <!-- Question Card -->
    <section>
        <div class="question-card reveal">
            <h2>Have Any Questions?</h2>
            <p>Our botanical experts are here to help you choose the perfect plants for your space and lifestyle.</p>
            <div style="display: flex; gap: 2rem; justify-content: center;">
                <a href="tel:+919925575862" class="btn-gold">Call Us</a>
                <a href="https://wa.me/919925575862" class="btn-gold">WhatsApp</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-leaf-overlay"></div>
        <div class="footer-grid">
            <div class="footer-logo">
                <h3>Vrundavan</h3>
                <p>Nurturing nature's beauty since 1995. Premium botanical sanctuary in Gadu, Gujarat.</p>
                <div class="social-icons">
                    <a href="#"><i data-lucide="instagram"></i></a>
                    <a href="#"><i data-lucide="facebook"></i></a>
                    <a href="#"><i data-lucide="twitter"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Sitemap</h4>
                <ul class="footer-links">
                    <li><a href="#about">Our Story</a></li>
                    <li><a href="#varieties">Collections</a></li>
                    <li><a href="#farm">The Farm</a></li>
                    <li><a href="#visit">Visit Us</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Categories</h4>
                <ul class="footer-links">
                    <li><a href="#">House Plants</a></li>
                    <li><a href="#">Ornamental</a></li>
                    <li><a href="#">Fruit Plants</a></li>
                    <li><a href="#">Medicinal</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <p style="opacity: 0.6; margin-bottom: 1rem;">Gadu NH 8-D, Junagadh Road, Gir Somnath, Gujarat</p>
                <p style="opacity: 0.6;">+91 94282 34442</p>
                <p style="opacity: 0.6;">info@newvrundavan.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 NEW VRUNDAVAN NURSERY. ALL RIGHTS RESERVED.</p>
            <p>DESIGNED BY ANTIGRAVITY</p>
        </div>
    </footer>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Scroll animations
        const revealElements = document.querySelectorAll('.reveal');
        const observerOptions = {
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = entry.target.getAttribute('data-delay') || 0;
                    setTimeout(() => {
                        entry.target.classList.add('active');
                    }, delay);
                }
            });
        }, observerOptions);

        revealElements.forEach(el => observer.observe(el));

        // Nav scroll effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('main-nav');
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
