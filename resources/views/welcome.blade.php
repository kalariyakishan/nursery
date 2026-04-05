<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Vrundavan Nursery | Premium Plants in Gadu, Gujarat 🌱</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        :root {
            --primary: #1B5E20; /* Matching Invoice Green */
            --primary-light: #4CAF50;
            --primary-dark: #0D3D0F;
            --background: #F6FBF6;
            --surface: #FFFFFF;
            --text-primary: #1A1A1A;
            --text-secondary: #6B7280;
            --accent: #E8F5E9;
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .font-poppins {
            font-family: 'Poppins', sans-serif;
        }

        .nursery-title {
            font-family: 'Times New Roman', serif;
            letter-spacing: -0.02em;
        }

        /* --- Custom Scrollbar --- */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: var(--background); }
        ::-webkit-scrollbar-thumb { background: var(--primary-light); border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

        /* --- Navigation --- */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1.25rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: var(--transition);
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            padding: 0.8rem 5%;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(27, 94, 32, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--primary);
        }

        .logo-text {
            font-size: 1.4rem;
            font-weight: 900;
            text-transform: uppercase;
            line-height: 1;
        }

        .logo-tagline {
            display: block;
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            color: var(--text-secondary);
        }

        .nav-links {
            display: flex;
            gap: 2.25rem;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            position: relative;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-text-link {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .nav-text-link:hover {
            color: var(--primary);
        }

        .mr-4 {
            margin-right: 1.5rem;
        }

        .nav-cta {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.2);
        }

        .nav-cta:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(27, 94, 32, 0.3);
        }

        /* --- Hero Section --- */
        .hero {
            height: 100vh;
            min-height: 700px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 5%;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url('{{ asset('nursery_panoramic_view_1775272065443.png') }}');
            background-size: cover;
            background-position: center;
            z-index: -1;
            transition: transform 10s linear;
        }

        .hero:hover .hero-bg {
            transform: scale(1.1);
        }

        .hero-content {
            max-width: 900px;
            z-index: 10;
        }

        .hero h1 {
            font-size: clamp(3rem, 8vw, 5.5rem);
            font-weight: 900;
            line-height: 0.95;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
        }

        .hero p {
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            margin-bottom: 3rem;
            font-weight: 400;
            opacity: 0.95;
            max-width: 700px;
            margin-inline: auto;
        }

        .hero-btns {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1.1rem 2.8rem;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-fill {
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
        }

        .btn-fill:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(27, 94, 32, 0.4);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid white;
            backdrop-filter: blur(5px);
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-5px);
        }

        /* --- Sections General --- */
        section {
            padding: 7rem 10% ;
        }

        .section-header {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 5rem;
        }

        .section-header span {
            color: var(--primary);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-size: 0.85rem;
            display: block;
            margin-bottom: 1rem;
        }

        .section-header h2 {
            font-size: 3rem;
            color: var(--primary-dark);
            font-weight: 800;
            line-height: 1.2;
        }

        .section-header p {
            margin-top: 1.5rem;
            color: var(--text-secondary);
            font-size: 1.15rem;
        }

        /* --- About Section --- */
        .about {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 6rem;
            align-items: center;
        }

        .about-image-container {
            position: relative;
        }

        .about-img {
            width: 100%;
            border-radius: 32px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
            z-index: 2;
            position: relative;
        }

        .about-badge {
            position: absolute;
            bottom: -30px;
            right: -30px;
            background: var(--primary);
            color: white;
            padding: 2.5rem;
            border-radius: 24px;
            text-align: center;
            z-index: 3;
            box-shadow: 0 20px 40px rgba(27, 94, 32, 0.3);
        }

        .about-badge .years {
            display: block;
            font-size: 3rem;
            font-weight: 900;
            line-height: 1;
        }

        .about-badge .text {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .about h2 {
            font-size: 3.5rem;
            margin-bottom: 2rem;
            color: var(--primary-dark);
            line-height: 1.1;
        }

        .about p {
            font-size: 1.15rem;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .check-list {
            list-style: none;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }

        .check-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .check-item i {
            color: var(--primary);
            flex-shrink: 0;
        }

        /* --- Categories --- */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }

        .category-card {
            position: relative;
            height: 480px;
            border-radius: 30px;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
        }

        .category-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .cat-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .category-card:hover .cat-img {
            transform: scale(1.1);
        }

        .cat-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(13, 61, 15, 0.9) 0%, rgba(13, 61, 15, 0.2) 50%, transparent 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2.5rem;
            color: white;
        }

        .cat-overlay h3 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .cat-overlay p {
            font-size: 0.95rem;
            opacity: 0.8;
            font-weight: 500;
        }

        /* --- Gallery --- */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 250px;
            gap: 1.5rem;
        }

        .gallery-item {
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .gallery-item.wide { grid-column: span 2; }
        .gallery-item.tall { grid-row: span 2; }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-overlay {
            position: absolute;
            inset: 0;
            background: rgba(27, 94, 32, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
            color: white;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        /* --- Why Choose Us --- */
        .why-us {
            background-color: var(--accent);
            border-radius: 60px;
            margin: 0 5%;
            padding: 6rem;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4rem;
        }

        .why-card {
            text-align: center;
        }

        .why-icon {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: var(--primary);
            font-size: 2.5rem;
            box-shadow: 0 10px 25px rgba(27, 94, 32, 0.1);
            transition: var(--transition);
        }

        .why-card:hover .why-icon {
            transform: translateY(-10px) rotate(10deg);
            background: var(--primary);
            color: white;
        }

        .why-card h4 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
        }

        .why-card p {
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* --- Visit Us --- */
        .visit {
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 6rem;
            align-items: center;
        }

        .visit-info h2 {
            font-size: 3.5rem;
            color: var(--primary-dark);
            margin-bottom: 2rem;
        }

        .contact-detail {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .contact-icon {
            width: 54px;
            height: 54px;
            background: var(--accent);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            flex-shrink: 0;
        }

        .contact-text h5 {
            font-size: 1.2rem;
            color: var(--primary-dark);
            margin-bottom: 0.25rem;
        }

        .contact-text p {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 1.05rem;
        }

        .map-container {
            height: 500px;
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
            border: 8px solid white;
            position: relative;
        }

        .map-placeholder {
            width: 100%;
            height: 100%;
            background: #e5e5e5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: var(--text-secondary);
        }

        /* --- Contact Section --- */
        .contact-section {
            background: var(--primary-dark);
            color: white;
            border-radius: 60px;
            margin: 0 5% 5% ;
            padding: 6rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
        }

        .contact-form {
            background: rgba(255, 255, 255, 0.05);
            padding: 3.5rem;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-group {
            margin-bottom: 2rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--primary-light);
        }

        .input-group input, .input-group textarea {
            width: 100%;
            padding: 1rem 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid transparent;
            border-radius: 16px;
            color: white;
            font-family: inherit;
            transition: var(--transition);
        }

        .input-group input:focus, .input-group textarea:focus {
            outline: none;
            border-color: var(--primary-light);
            background: rgba(255, 255, 255, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 1.1rem;
            border-radius: 16px;
            background: var(--primary-light);
            color: white;
            border: none;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: var(--transition);
        }

        .btn-submit:hover {
            background: #66bb6a;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(76, 175, 80, 0.3);
        }

        /* --- Footer --- */
        .footer {
            background: var(--background);
            padding: 6rem 10% 3rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 4rem;
            margin-bottom: 6rem;
        }

        .footer-col h4 {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 2rem;
            color: var(--primary-dark);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 1.25rem;
        }

        .footer-links a {
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary);
            padding-left: 8px;
        }

        .footer-logo .logo-text { font-size: 1.8rem; margin-bottom: 0.5rem; }

        .social-bar {
            display: flex;
            gap: 1.25rem;
            margin-top: 2.5rem;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            background: white;
            border: 1px solid #eee;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            transition: var(--transition);
            text-decoration: none;
        }

        .social-btn:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-5px);
        }

        .footer-bottom {
            padding-top: 3rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* --- Animations --- */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 1.2s cubic-bezier(0.16, 1, 0.3, 1), transform 1.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        [data-delay="200"] { transition-delay: 0.2s; }
        [data-delay="400"] { transition-delay: 0.4s; }
        [data-delay="600"] { transition-delay: 0.6s; }

        /* --- Responsive --- */
        @media (max-width: 1280px) {
            .navbar { padding: 1.25rem 5%; }
            .hero h1 { font-size: 4.5rem; }
            .category-grid { grid-template-columns: repeat(2, 1fr); }
            .why-us { grid-template-columns: repeat(2, 1fr); padding: 4rem; }
            .footer-grid { grid-template-columns: 1.5fr 1fr; gap: 3rem; }
        }

        @media (max-width: 768px) {
            .nav-links, .nav-actions .nav-cta { display: none; }
            section { padding: 5rem 5%; }
            .about, .visit, .contact-section { grid-template-columns: 1fr; gap: 4rem; }
            .hero h1 { font-size: 3.2rem; }
            .why-us { grid-template-columns: 1fr; padding: 3rem; }
            .section-header h2 { font-size: 2.2rem; }
            .gallery-grid { grid-template-columns: repeat(2, 1fr); }
            .about h2, .visit-info h2 { font-size: 2.5rem; }
            .contact-section { padding: 3rem 5%; }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <a href="#" class="logo">
            <i data-lucide="leaf" size="32" stroke-width="2.5"></i>
            <div>
                <span class="logo-text nursery-title">New Vrundavan</span>
                <span class="logo-tagline">Nursery & Landscapes</span>
            </div>
        </a>
        
        <ul class="nav-links">
            <li><a href="#" class="nav-link active">Home</a></li>
            <li><a href="#about" class="nav-link">About</a></li>
            <li><a href="#plants" class="nav-link">Our Plants</a></li>
            <li><a href="#gallery" class="nav-link">Gallery</a></li>
            <li><a href="#visit" class="nav-link">Visit Us</a></li>
        </ul>
        
        <div class="nav-actions">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-text-link mr-4">Dashboard</a>
                    <a href="#visit" class="nav-cta">Get Directions</a>
                @else
                    <a href="{{ route('login') }}" class="nav-cta">Log In</a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <h1 class="nursery-title">Welcome to <br>New Vrundavan Nursery</h1>
            <p>Your premium destination for the finest fruit, flower, and ornamental plants. Nestled in Gadu, Gujarat, we bring you the healthiest greens nurtured with decades of expertise.</p>
            <div class="hero-btns">
                <a href="#visit" class="btn btn-fill">Plan a Visit <i data-lucide="map-pin"></i></a>
                <a href="#plants" class="btn btn-outline">Explore Gallery <i data-lucide="camera"></i></a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="reveal">
        <div class="about">
            <div class="about-image-container">
                <img src="{{ asset('nursery_hero_banner_1775270948136.png') }}" alt="Our Nursery" class="about-img">
                <div class="about-badge">
                    <span class="years">30+</span>
                    <span class="text">Years of Excellence</span>
                </div>
            </div>
            <div class="about-content">
                <span>Since 1995</span>
                <h2 class="nursery-title">Cultivating Nature's Best with Heart and Science</h2>
                <p>New Vrundavan Nursery is more than just a place to buy plants. We are a team of dedicated botanists and garden enthusiasts committed to providing Gadu and the surrounding districts with premium botanical quality.</p>
                <p>Whether you're a wholesaler looking for high-volume fruit plants or a homeowner seeking that perfect ornamental piece, our 50-acre facility has everything you need to grow your green world.</p>
                
                <ul class="check-list">
                    <li class="check-item"><i data-lucide="check-circle-2"></i> Premium Root Health</li>
                    <li class="check-item"><i data-lucide="check-circle-2"></i> Disease-Free Saplings</li>
                    <li class="check-item"><i data-lucide="check-circle-2"></i> Expert Soil Mix</li>
                    <li class="check-item"><i data-lucide="check-circle-2"></i> Landscape Consulting</li>
                    <li class="check-item"><i data-lucide="check-circle-2"></i> Retail & Wholesale</li>
                    <li class="check-item"><i data-lucide="check-circle-2"></i> Bulk Orders</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Plant Categories -->
    <section id="plants" class="reveal">
        <div class="section-header">
            <span>Our Collection</span>
            <h2 class="nursery-title">Explore Our Botanical Varieties</h2>
            <p>Discover a wide range of plants carefully selected to thrive in our climate. From exotic flowers to high-yield fruit trees.</p>
        </div>
        
        <div class="category-grid">
            <div class="category-card reveal" data-delay="0">
                <img src="{{ asset('flower_plants_category_1775272097882.png') }}" alt="Flower Plants" class="cat-img">
                <div class="cat-overlay">
                    <h3>Flower Plants</h3>
                    <p>Hibiscus, Rose, Bougainvillea & more</p>
                </div>
            </div>
            <div class="category-card reveal" data-delay="200">
                <img src="{{ asset('indoor_plant_category_1775270994110.png') }}" alt="Indoor Plants" class="cat-img">
                <div class="cat-overlay">
                    <h3>Ornamental & Indoor</h3>
                    <p>Monstera, Palm, Snake Plants</p>
                </div>
            </div>
            <div class="category-card reveal" data-delay="400">
                <img src="{{ asset('nursery_assets_pack_1775271017163.png') }}" style="object-position: 0 0;" alt="Fruit Plants" class="cat-img">
                <div class="cat-overlay">
                    <h3>Fruit Plants</h3>
                    <p>Mango, Chickoo, Coconut & Citrus</p>
                </div>
            </div>
            <div class="category-card reveal" data-delay="600">
                <img src="{{ asset('medicinal_plants_category_1775272123118.png') }}" alt="Medicinal Plants" class="cat-img">
                <div class="cat-overlay">
                    <h3>Medicinal & Herbs</h3>
                    <p>Tulsi, Aloe Vera, Neem & Mint</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="reveal">
        <div class="why-us">
            <div class="why-card reveal" data-delay="0">
                <div class="why-icon"><i data-lucide="heart"></i></div>
                <h4>Healthy Plants</h4>
                <p>Grown in nutrient-rich soil with natural fertilizers.</p>
            </div>
            <div class="why-card reveal" data-delay="200">
                <div class="why-icon"><i data-lucide="layers"></i></div>
                <h4>Wide Variety</h4>
                <p>Over 500+ species of plants available in different sizes.</p>
            </div>
            <div class="why-card reveal" data-delay="400">
                <div class="why-icon"><i data-lucide="graduation-cap"></i></div>
                <h4>Expert Advice</h4>
                <p>Get personalized care routines from our farm experts.</p>
            </div>
            <div class="why-card reveal" data-delay="600">
                <div class="why-icon"><i data-lucide="tag"></i></div>
                <h4>Best Pricing</h4>
                <p>Competitive wholesale and retail rates in the region.</p>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="reveal">
        <div class="section-header">
            <span>The Nursery Life</span>
            <h2 class="nursery-title">Snapshots from Our Farm</h2>
        </div>
        
        <div class="gallery-grid">
            <div class="gallery-item wide tall reveal">
                <img src="{{ asset('nursery_panoramic_view_1775272065443.png') }}" alt="Panorama">
                <div class="gallery-overlay"><i data-lucide="zoom-in"></i></div>
            </div>
            <div class="gallery-item reveal">
                <img src="{{ asset('flower_plants_category_1775272097882.png') }}" alt="Flowers">
                <div class="gallery-overlay"><i data-lucide="zoom-in"></i></div>
            </div>
            <div class="gallery-item reveal">
                <img src="{{ asset('nursery_assets_pack_1775271017163.png') }}" style="object-position: 100% 100%;" alt="Succulents">
                <div class="gallery-overlay"><i data-lucide="zoom-in"></i></div>
            </div>
            <div class="gallery-item wide reveal">
                <img src="{{ asset('nursery_categories_1775270970501.png') }}" style="object-position: center;" alt="Crops">
                <div class="gallery-overlay"><i data-lucide="zoom-in"></i></div>
            </div>
        </div>
    </section>

    <!-- Visit Us Section -->
    <section id="visit" class="reveal">
        <div class="visit">
            <div class="visit-info">
                <span>Get in Touch</span>
                <h2 class="nursery-title">Plan Your Visit to Our Nursery</h2>
                <div class="contact-detail">
                    <div class="contact-icon"><i data-lucide="map-pin"></i></div>
                    <div class="contact-text">
                        <h5>Our Location</h5>
                        <p>Gadu - Chorvad Circle, Porbandar Highway,<br>Gadu (Sherbaug)-362255, Dist : Junagadh</p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-icon"><i data-lucide="phone"></i></div>
                    <div class="contact-text">
                        <h5>Call Us</h5>
                        <p>+91 63551 51302 / +91 99255 75862</p>
                    </div>
                </div>
                <div class="contact-detail">
                    <div class="contact-icon"><i data-lucide="clock"></i></div>
                    <div class="contact-text">
                        <h5>Opening Hours</h5>
                        <p>Every Day: 8:00 AM - 7:00 PM</p>
                    </div>
                </div>
                <a href="https://maps.google.com" target="_blank" class="btn btn-fill">Get Directions on Google Maps</a>
            </div>
            
            <div class="map-container">
                <div class="map-placeholder">
                    <i data-lucide="map" size="48" style="margin-bottom: 1rem; opacity: 0.5;"></i>
                    <p style="font-weight: 600;">Google Maps Integration Placeholder</p>
                    <p style="font-size: 0.9rem; opacity: 0.7;">Gadu - Chorvad Circle, Highway</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact & Inquiry -->
    <section class="reveal">
        <div class="contact-section">
            <div class="contact-text-content">
                <h2 class="nursery-title" style="font-size: 3.5rem; margin-bottom: 2rem;">Have Any <br>Questions?</h2>
                <p style="font-size: 1.25rem; opacity: 0.8; margin-bottom: 3rem;">Whether you're looking for wholesale quotes, plant care advice, or stock availability, we're here to help.</p>
                
                <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                    <a href="tel:6355151302" class="btn btn-outline" style="border-color: var(--primary-light); color: var(--primary-light); padding: 1rem 2rem;">
                        <i data-lucide="phone"></i> Call Now
                    </a>
                    <a href="https://wa.me/916355151302" class="btn btn-outline" style="border-color: #25D366; color: #25D366; padding: 1rem 2rem;">
                        <i data-lucide="message-circle"></i> WhatsApp
                    </a>
                </div>
            </div>
            
            <div class="contact-form">
                <form>
                    <div class="input-group">
                        <label>FULL NAME</label>
                        <input type="text" placeholder="Your name here" required>
                    </div>
                    <div class="input-group">
                        <label>PHONE NUMBER</label>
                        <input type="tel" placeholder="Your phone number" required>
                    </div>
                    <div class="input-group">
                        <label>YOUR MESSAGE</label>
                        <textarea rows="4" placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Send Inquiry</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-grid">
            <div class="footer-col">
                <a href="#" class="logo footer-logo">
                    <i data-lucide="leaf" size="40" stroke-width="2.5"></i>
                    <div>
                        <span class="logo-text nursery-title">New Vrundavan</span>
                        <span class="logo-tagline">Nursery & Landscapes</span>
                    </div>
                </a>
                <p style="margin-top: 1.5rem; color: var(--text-secondary); font-weight: 500;">Retailer & Wholesaler of All Fruit, Flower & Ornamental Plants. Serving Junagadh and beyond since 1995.</p>
                <div class="social-bar">
                    <a href="#" class="social-btn"><i data-lucide="facebook"></i></a>
                    <a href="#" class="social-btn"><i data-lucide="instagram"></i></a>
                    <a href="#" class="social-btn"><i data-lucide="twitter"></i></a>
                </div>
            </div>
            
            <div class="footer-col" style="padding-left: 2rem;">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#plants">Our Plants</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Categories</h4>
                <ul class="footer-links">
                    <li><a href="#">Fruit Plants</a></li>
                    <li><a href="#">Flower Plants</a></li>
                    <li><a href="#">Ornamental</a></li>
                    <li><a href="#">Landscape design</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Contact Us</h4>
                <ul class="footer-links" style="color:var(--text-secondary)">
                    <li style="margin-bottom: 1.5rem;">
                        <span style="display:block; color:var(--primary-dark); font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">Location</span>
                        Gadu - Chorvad Circle, Gadu (Sherbaug)-362255
                    </li>
                    <li style="margin-bottom: 1.5rem;">
                        <span style="display:block; color:var(--primary-dark); font-weight: 800; font-size: 0.8rem; text-transform: uppercase;">Call Us</span>
                        +91 63551 51302 / +91 99255 75862
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2026 New Vrundavan Nursery. All rights reserved.</p>
            <div style="display: flex; gap: 2.5rem;">
                <a href="#" style="color: var(--text-secondary); text-decoration: none;">Privacy Policy</a>
                <a href="#" style="color: var(--text-secondary); text-decoration: none;">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Reveal on Scroll
        const revealElements = document.querySelectorAll('.reveal');
        const revealOnScroll = () => {
            revealElements.forEach(el => {
                const windowHeight = window.innerHeight;
                const elementTop = el.getBoundingClientRect().top;
                const elementPoint = 100;
                
                if (elementTop < windowHeight - elementPoint) {
                    el.classList.add('active');
                }
            });
        };
        
        window.addEventListener('scroll', revealOnScroll);
        window.addEventListener('load', revealOnScroll);

        // Smooth Scroll for Navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
