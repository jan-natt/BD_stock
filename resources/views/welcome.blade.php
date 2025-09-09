<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeX - Modern Online Trading Platform</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  @livewireStyles 
    <style>
        :root {
            --primary: #2962ff;
            --secondary: #0039cb;
            --accent: #00c853;
            --dark: #121826;
            --light: #f8f9fa;
            --gray: #e0e0e0;
             --white: #e107f5ff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            overflow-x: hidden;
            color: var(--light);
            background-color: #120042ff;
        }
        
        /* Navbar */
        .navbar {
            padding: 15px 0;
            background-color: #120042ff !important;
            box-shadow: 0 2px 10px rgba(61, 68, 131, 0.1);
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white;
            font-size: 1.8rem;
        }
        
        .nav-link {
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s;
            color:white;
        }
        
        .nav-link:hover {
            color: var(--white) !important;
        }
        
        
        .btn-signup {
            background-color:#652483ff ;
            color: white;
            border-radius: 30px;
            padding: 8px 20px;
            transition: all 0.3s;
        }
        
        .btn-signup:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #0c0036ff 0%, #652483ff 100%);
            color: white;
            padding: 100px 0 80px;
            border-radius: 0 0 30px 30px;
            overflow: hidden;
            position: relative;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-weight: 700;
            font-size: 3.5rem;
            margin-bottom: 20px;
            animation: fadeInUp 1s ease;
        }
        
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            animation: fadeInUp 1.2s ease;
        }
        
        .hero-btns {
            animation: fadeInUp 1.4s ease;
        }
        
        .btn-demo {
            background-color: white;
            color: var(--primary);
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            margin-right: 15px;
            transition: all 0.3s;
        }
        
        .btn-demo:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        .btn-learn {
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: white;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-learn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
        }
        
        .hero-image {
            animation: float 4s ease-in-out infinite;
        }
        
        /* Features Section */
        .features {
            padding: 100px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        
        .section-title h2 {
            font-weight: 700;
            color: var(--dark);
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: var(--primary);
        }
        
        .feature-card {
            background: #b796e233 ;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: rgba(41, 98, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 25px;
            color: var(--primary);
            font-size: 28px;
        }
        
        .feature-card h3 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        /* Market Data Section */
        .market-data {
            background: #b796e233 ;
            color: white;
            padding: 80px 0;
            border-radius: 30px;
        }
        
        .market-ticker {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .ticker-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .ticker-item:last-child {
            border-bottom: none;
        }
        
        .price-up {
            color: var(--accent);
        }
        
        .price-down {
            color: #ff5252;
        }
        
        /* Trading Tools */
        .trading-tools {
            padding: 100px 0;
            background:  #b796e233 ;
            border-radius: 30px;
        }
        
        .tool-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .tool-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .tool-img {
            height: 200px;
            background: linear-gradient(45deg, #2962ff3f, #ae75c5ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }
        
        .tool-content {
            padding: 25px;
        }
        
        /* How to Trade Section */
        .how-to-trade {
            padding: 100px 0;
            background:  #b796e233 ;
        }
        
        .steps-container {
            position: relative;
            padding: 40px 0;
        }
        
        .progress-bar {
            position: absolute;
            height: 70%;
            width: 4px;
            background: #bb78da60;
            left: 35px;
            top: 15%;
            z-index: 1;
        }
        
        .progress-bar-fill {
            height: 0%;
            width: 100%;
            background: #bb78da60;
            transition: height 1s ease;
        }
        
        .step {
            display: flex;
            margin-bottom: 60px;
            position: relative;
            z-index: 2;
        }
        
        .step-icon {
            width: 70px;
            height: 70px;
            background: white;
            border: 4px solid var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-right: 30px;
            flex-shrink: 0;
        }
        
        .step-content {
            background: #8778da60
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .step-content h3 {
            color: var(--primary);
            margin-bottom: 15px;
        }
        
        /* Licensing Section */
        .licensing {
            padding: 100px 0;
            background:  #b796e233 ;
        }
        
        .license-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .license-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .license-icon {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .license-card h3 {
            color: var(--dark);
            margin-bottom: 15px;
        }
        
        .compliance-badge {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 15px;
        }
        
        /* Testimonials */
        .testimonials {
            padding: 100px 0;
        }
        
        .testimonial-card {
            background:  #b796e233 ;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin: 20px 0;
            position: relative;
        }
        
        .testimonial-card:after {
            content: '\201D';
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 60px;
            color: rgba(41, 98, 255, 0.1);
            font-family: sans-serif;
        }
        
        .testimonial-text {
            margin-bottom: 20px;
            font-style: italic;
        }
        
        .client-info {
            display: flex;
            align-items: center;
        }
        
        .client-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
            background: #2962ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        /* CTA Section */
        .cta-section {
            background:  #b796e233 ;
            color: white;
            padding: 80px 0;
            border-radius: 30px;
            text-align: center;
        }
        
        .cta-section h2 {
            font-weight: 700;
            margin-bottom: 30px;
        }
        
        /* Footer */
        footer {
            background: #b796e233 ;
            color: white;
            padding: 70px 0 30px;
             border-radius: 30px;
        }
        
        .footer-links h5 {
            font-weight: 600;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-links h5:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 30px;
            height: 2px;
            background: var(--primary);
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-icons a {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            color: white;
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s ease;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 2.8rem;
            }
            
            .progress-bar {
                left: 30px;
            }
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.3rem;
            }
            
            .hero p {
                font-size: 1rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
            
            .step {
                flex-direction: column;
            }
            
            .step-icon {
                margin-right: 0;
                margin-bottom: 20px;
            }
            
            .progress-bar {
                left: 35px;
                height: 75%;
            }
        }
        
        @media (max-width: 576px) {
            .hero {
                padding: 80px 0 60px;
            }
            
            .hero h1 {
                font-size: 2rem;
            }
            
            .btn-demo, .btn-learn {
                display: block;
                width: 100%;
                margin: 10px 0;
            }
            
            .progress-bar {
                display: none;
            }
        }

         .section-title {
            text-align: center;
            margin-bottom: 60px;
            padding-top: 40px;
        }
        
        .section-title h2 {
            font-weight: 700;
            color: var(--dark);
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: -10px;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: var(--primary);
        }
        
        /* Financial Partners Section */
        .financial-partners {
            padding: 80px 0;
            background:  #b796e233 ;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin: 40px 0;
        }
        
        .partners-slider {
            padding: 30px 0;
            position: relative;
        }
        
        .partners-slider .item {
            padding: 20px;
        }
        
        .partner-logo {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            border: 1px solid #f0f0f0;
            filter: grayscale(100%);
            opacity: 0.7;
        }
        
        .partner-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(41, 98, 255, 0.15);
            border-color: var(--primary);
        }
        
        .partner-logo img {
            max-width: 100%;
            max-height: 70px;
            width: auto;
            transition: all 0.4s ease;
        }
        
        .partner-name {
            text-align: center;
            margin-top: 15px;
            font-weight: 500;
            color: var(--dark);
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .item:hover .partner-name {
            opacity: 1;
            color: var(--primary);
        }
        
        /* Slider Navigation */
        .slider-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            pointer-events: none;
            z-index: 10;
        }
        
        .slider-nav button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 18px;
            transition: all 0.3s ease;
            pointer-events: auto;
            margin: 0 15px;
        }
        
        .slider-nav button:hover {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }
        
        /* Dots Indicator */
        .slider-dots {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        
        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--gray);
            margin: 0 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .dot.active {
            background: var(--primary);
            transform: scale(1.2);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .partner-logo {
                height: 100px;
                padding: 15px;
            }
            
            .partner-logo img {
                max-height: 50px;
            }
            
            .slider-nav button {
                width: 40px;
                height: 40px;
                margin: 0 5px;
            }
        }
        
        @media (max-width: 576px) {
            .partner-logo {
                height: 80px;
                padding: 10px;
            }
            
            .partner-logo img {
                max-height: 40px;
            }
            
            .partner-name {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">TradeX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-signup" href="{{route('register')}}">Sign Up</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <br>
    <br>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>Trade Smarter, Invest Better</h1>
                    <p>Join thousands of traders on our advanced platform. Access real-time market data, advanced charting tools, and execute trades with confidence.</p>
                    <div class="hero-btns">
                        <a href="#" class="btn btn-demo">Get Started</a>
                        <a href="#" class="btn btn-learn">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAwIiBoZWlnaHQ9IjQwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZmZmZmZmMTAiLz48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSg1MCw1MCkiPjxwYXRoIGQ9Ik0zMDAsMCBMNDAwLDEwMCBMMzAwLDIwMCBMMCwyMDAgTDEwMCwxMDAgTDAsMCBMMzAwLDAgWiIgZmlsbD0iIzI5NjJmZjQwIi8+PGNpcmNsZSBjeD0iMTUwIiBjeT0iMTAwIiByPSI4MCIgZmlsbD0iIzAwYzg1MzQwIi8+PGNpcmNsZSBjeD0iMjUwIiBjeT0iMTAwIiByPSI1MCIgZmlsbD0iI2ZmNTI1MjQwIi8+PC9nPjx0ZXh0IHg9IjUwJSIgeT0iODAlIiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMjQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiNmZmYiPlRyYWRpbmcgUGxhdGZvcm08L3RleHQ+PC9zdmc+" class="img-fluid hero-image" alt="Trading Platform">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose TradeX</h2>
                <p>Discover the features that make our platform stand out</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Advanced Charts</h3>
                        <p>Powerful charting tools with over 100 technical indicators and drawing tools for in-depth market analysis.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3>Fast Execution</h3>
                        <p>Execute trades in milliseconds with our high-speed order processing and low latency infrastructure.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Secure Platform</h3>
                        <p>Bank-level security with two-factor authentication and encryption to keep your funds and data safe.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>Mobile Trading</h3>
                        <p>Trade on the go with our award-winning mobile app, available for iOS and Android devices.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Learning Resources</h3>
                        <p>Access comprehensive educational materials, webinars, and tutorials to improve your trading skills.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Our dedicated support team is available around the clock to assist you with any questions or issues.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How to Trade Section -->
    <section class="how-to-trade">
        <div class="container">
            <div class="section-title">
                <h2>How to Start Trading</h2>
                <p>Follow these simple steps to begin your trading journey</p>
            </div>
            
            <div class="steps-container">
                <div class="progress-bar">
                    <div class="progress-bar-fill"></div>
                </div>
                
                <div class="step animate-on-scroll">
                    <div class="step-icon">1</div>
                    <div class="step-content">
                        <h3>Create Your Account</h3>
                        <p>Sign up in just a few minutes with our simple registration process. Provide your basic information and verify your identity to get started.</p>
                    </div>
                </div>
                
                <div class="step animate-on-scroll">
                    <div class="step-icon">2</div>
                    <div class="step-content">
                        <h3>Fund Your Account</h3>
                        <p>Deposit funds using various payment methods including bank transfer, credit card, or digital payment systems. Start with as little as $100.</p>
                    </div>
                </div>
                
                <div class="step animate-on-scroll">
                    <div class="step-icon">3</div>
                    <div class="step-content">
                        <h3>Learn the Platform</h3>
                        <p>Explore our educational resources, take advantage of demo accounts, and familiarize yourself with our trading tools and interface.</p>
                    </div>
                </div>
                
                <div class="step animate-on-scroll">
                    <div class="step-icon">4</div>
                    <div class="step-content">
                        <h3>Start Trading</h3>
                        <p>Execute your first trade! Monitor markets, analyze trends, and make informed decisions using our advanced trading tools.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Licensing Section -->
    <section class="licensing">
        <div class="container">
            <div class="section-title">
                <h2>Regulatory Compliance & Licensing</h2>
                <p>Trade with confidence knowing we're fully regulated and compliant</p>
            </div>
            
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="license-card">
                        <div class="license-icon">
                            <i class="fas fa-shield-check"></i>
                        </div>
                        <h3>Financial Authority Regulation</h3>
                        <p>TradeX is regulated by the International Financial Commission (IFC) under license no. T2021689. We adhere to strict financial standards and regular audits.</p>
                        <span class="compliance-badge">Fully Regulated</span>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="license-card">
                        <div class="license-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3>Client Fund Protection</h3>
                        <p>All client funds are held in segregated accounts at top-tier banks, ensuring complete separation from company funds and maximum security for your investments.</p>
                        <span class="compliance-badge">Segregated Accounts</span>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="license-card">
                        <div class="license-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h3>Transparent Trading</h3>
                        <p>We provide complete transparency in our operations with clear pricing, no hidden fees, and detailed reporting of all transactions for your peace of mind.</p>
                        <span class="compliance-badge">No Hidden Fees</span>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5 animate-on-scroll">
                <p class="mb-4">For more information about our regulatory status and compliance measures, please visit our Legal Documentation page.</p>
                <a href="#" class="btn btn-primary">View Legal Documents</a>
            </div>
        </div>
    </section>

    <!-- Market Data Section -->
    <section class="market-data">
        <div class="container">
            <div class="section-title">
                <h2 style="color: white;">Real-Time Market Data</h2>
                <p>Track the markets with our live data feed</p>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4 animate-on-scroll">
                    <div class="market-ticker">
                        <h4 class="mb-4">Stock Indexes</h4>
                        <div class="ticker-item">
                            <span>S&P 500</span>
                            <span class="price-up">4,891.23 <i class="fas fa-arrow-up"></i></span>
                        </div>
                        <div class="ticker-item">
                            <span>NASDAQ</span>
                            <span class="price-up">15,628.34 <i class="fas fa-arrow-up"></i></span>
                        </div>
                        <div class="ticker-item">
                            <span>DOW JONES</span>
                            <span class="price-down">38,621.09 <i class="fas fa-arrow-down"></i></span>
                        </div>
                        <div class="ticker-item">
                            <span>FTSE 100</span>
                            <span class="price-up">7,682.45 <i class="fas fa-arrow-up"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4 animate-on-scroll">
                    <div class="market-ticker">
                        <h4 class="mb-4">Cryptocurrencies</h4>
                        <div class="ticker-item">
                            <span>Bitcoin (BTC)</span>
                            <span class="price-up">$61,423.78 <i class="fas fa-arrow-up"></i></span>
                        </div>
                        <div class="ticker-item">
                            <span>Ethereum (ETH)</span>
                            <span class="price-up">$3,412.56 <i class="fas fa-arrow-up"></i></span>
                        </div>
                        <div class="ticker-item">
                            <span>Cardano (ADA)</span>
                            <span class="price-down">$0.52 <i class="fas fa-arrow-down"></i></span>
                        </div>
                        <div class="ticker-item">
                            <span>Solana (SOL)</span>
                            <span class="price-up">$128.93 <i class="fas fa-arrow-up"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Financial Partners Section -->
    <section class="financial-partners">
        <div class="container">
            <div class="section-title">
                <h2>Our Financial Partners</h2>
                <p>Trusted by leading financial institutions worldwide</p>
            </div>
            
            <div class="partners-slider">
                <div class="slider-nav">
                    <button id="prev-partner"><i class="fas fa-chevron-left"></i></button>
                    <button id="next-partner"><i class="fas fa-chevron-right"></i></button>
                </div>
                
                <div class="row" id="partners-slider">
                    <!-- Partner 1 -->
                    <div class="col-md-3 col-6 item">
                        <div class="partner-logo">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCIgc3R5bGU9InN0b3AtY29sb3I6IzI5NjJmZiIvPjxzdG9wIG9mZnNldD0iMSIgc3R5bGU9InN0b3AtY29sb3I6IzAwMzljYiIvPjwvbGluZWFyR3JhZGllbnQ+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjIwMCIgaGVpZ2h0PSI4MCIgZmlsbD0idXJsKCNhKSIgcng9IjEwIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxOCIgZm9udC13ZWlnaHQ9ImJvbGQiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiIGZpbGw9IiNmZmYiPkdvbGQgU2FjcyBCYW5rPC90ZXh0Pjwvc3ZnPg==" alt="Gold Sachs Bank">
                        </div>
                        <div class="partner-name">Gold Sachs Bank</div>
                    </div>
                    
                    <!-- Partner 2 -->
                    <div class="col-md-3 col-6 item">
                        <div class="partner-logo">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCIgc3R5bGU9InN0b3AtY29sb3I6IzAwYzg1MzsiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiMwMDhhNDA7Ii8+PC9saW5lYXJHcmFkaWVudD48cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiBmaWxsPSJ1cmwoI2EpIiByeD0iMTAiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE4IiBmb250LXdlaWdodD0iYm9sZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgZmlsbD0iI2ZmZiI+Q2l0aSBHcm91cCBHbG9iYWw8L3RleHQ+PC9zdmc+" alt="Citi Group Global">
                        </div>
                        <div class="partner-name">Citi Group Global</div>
                    </div>
                    
                    <!-- Partner 3 -->
                    <div class="col-md-3 col-6 item">
                        <div class="partner-logo">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCIgc3R5bGU9InN0b3AtY29sb3I6I2ZmNTI1MjsiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiNjzjExYjE7Ii8+PC9saW5lYXJHcmFkaWVudD48cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiBmaWxsPSJ1cmwoI2EpIiByeD0iMTAiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE4IiBmb250LXdlaWdodD0iYm9sZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgZmlsbD0iI2ZmZiI+SlAgTW9yZ2FuIENoYXNlPC90ZXh0Pjwvc3ZnPg==" alt="JP Morgan Chase">
                        </div>
                        <div class="partner-name">JP Morgan Chase</div>
                    </div>
                    
                    <!-- Partner 4 -->
                    <div class="col-md-3 col-6 item">
                        <div class="partner-logo">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCIgc3R5bGU9InN0b3AtY29sb3I6IzAwOTZiNjsiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiMwMDY0ODc7Ii8+PC9saW5lYXJHcmFkaWVudD48cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiBmaWxsPSJ1cmwoI2EpIiByeD0iMTAiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE4IiBmb250LXdlaWdodD0iYm9sZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgZmlsbD0iI2ZmZiI+QmFyY2xheXMgQ2FwaXRhbDwvdGV4dD48L3N2Zz4=" alt="Barclays Capital">
                        </div>
                        <div class="partner-name">Barclays Capital</div>
                    </div>
                    
                    <!-- Partner 5 -->
                    <div class="col-md-3 col-6 item">
                        <div class="partner-logo">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCIgc3R5bGU9InN0b3AtY29sb3I6IzE3MTcxNzsiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiMwMDAwMDA7Ii8+PC9saW5lYXJHcmFkaWVudD48cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiBmaWxsPSJ1cmwoI2EpIiByeD0iMTAiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE4IiBmb250LXdlaWdodD0iYm9sZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1iZGRsZSIgZmlsbD0iI2ZmZiI+SFNCQyBIb2xkaW5nczwvdGV4dD48L3N2Zz4=" alt="HSBC Holdings">
                        </div>
                        <div class="partner-name">HSBC Holdings</div>
                    </div>
                    
                    <!-- Partner 6 -->
                    <div class="col-md-3 col-6 item">
                        <div class="partner-logo">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgZ3JhZGllbnRVbml0cz0idXNlclNpY2VPbk9zZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+PHN0b3Agb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojZmY3ZjAwOyIvPjxzdG9wIG9mZnNldD0iMSIgc3R5bGU9InN0b3AtY29sb3I6I2ZmNTIwMDsiLz48L2xpbmVhckdyYWRpZW50PjxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIyMDAiIGhlaWdodD0iODAiIGZpbGw9InVybCgjYSkiIHJ4PSIxMCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTgiIGZvbnQtd2VpZ2h0PSJib2xkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiBmaWxsPSIjZmZmIj5CYW5rIG9mIEFtZXJpY2E8L3RleHQ+PC9zdmc+" alt="Bank of America">
                        </div>
                        <div class="partner-name">Bank of America</div>
                    </div>
                    
                    <!-- Partner 7 -->
                    <div class="col-md-3 col-6 item">
                        <div class="partner-logo">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCIgc3R5bGU9InN0b3AtY29sb3I6IzAwNzJiOyIvPjxzdG9wIG9mZnNldD0iMSIgc3R5bGU9InN0b3AtY29sb3I6IzAwNGZhYTsiLz48L2xpbmVhckdyYWRpZW50PjxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIyMDAiIGhlaWdodD0iODAiIGZpbGw9InVybCgjYSkiIHJ4PSIxMCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTgiIGZvbnQtd2VpZ2h0PSJib2xkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkb21pbmFudC1iYXNlbGluZT0ibWlkZGxlIiBmaWxsPSIjZmZmIj5EZXV0c2NoZSBCYW5rPC90ZXh0Pjwvc3ZnPg==" alt="Deutsche Bank">
                        </div>
                        <div class="partner-name">Deutsche Bank</div>
                    </div>
                    
                    <!-- Partner 8 -->
                    <div class="col-md-3 col-6 item">
                        <div class="partner-logo">
                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxsaW5lYXJHcmFkaWVudCBpZD0iYSIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiIHgxPSIwJSIgeTE9IjAlIiB4Mj0iMTAwJSIgeTI9IjEwMCUiPjxzdG9wIG9mZnNldD0iMCIgc3R5bGU9InN0b3AtY29sb3I6IzIyMjIyMjsiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiMwMDAwMDA7Ii8+PC9saW5lYXJHcmFkaWVudD48cmVjdCB4PSIwIiB5PSIwIiB3aWR0aD0iMjAwIiBoZWlnaHQ9IjgwIiBmaWxsPSJ1cmwoI2EpIiByeD0iMTAiLz48dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE4IiBmb250LXdlaWdodD0iYm9sZCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9Im1pZGRsZSIgZmlsbD0iI2ZmZiI+TW9yZ2FuIFN0YW5sZXk8L3RleHQ+PC9zdmc+" alt="Morgan Stanley">
                        </div>
                        <div class="partner-name">Morgan Stanley</div>
                    </div>
                </div>
                
                <div class="slider-dots" id="slider-dots">
                    <span class="dot active" data-index="0"></span>
                    <span class="dot" data-index="1"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('partners-slider');
            const items = slider.querySelectorAll('.item');
            const dots = document.querySelectorAll('.dot');
            const prevBtn = document.getElementById('prev-partner');
            const nextBtn = document.getElementById('next-partner');
            
            let currentIndex = 0;
            const itemsPerPage = 4;
            const totalItems = items.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            
            // Initialize dots
            function initDots() {
                const dotsContainer = document.getElementById('slider-dots');
                dotsContainer.innerHTML = '';
                
                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement('span');
                    dot.className = 'dot' + (i === 0 ? ' active' : '');
                    dot.setAttribute('data-index', i);
                    dot.addEventListener('click', function() {
                        goToPage(parseInt(this.getAttribute('data-index')));
                    });
                    dotsContainer.appendChild(dot);
                }
            }
            
            // Update dots
            function updateDots() {
                document.querySelectorAll('.dot').forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }
            
            // Go to specific page
            function goToPage(pageIndex) {
                if (pageIndex < 0 || pageIndex >= totalPages) return;
                
                currentIndex = pageIndex;
                const translateX = -currentIndex * 100;
                slider.style.transform = `translateX(${translateX}%)`;
                updateDots();
            }
            
            // Next page
            function nextPage() {
                if (currentIndex < totalPages - 1) {
                    goToPage(currentIndex + 1);
                } else {
                    goToPage(0);
                }
            }
            
            // Previous page
            function prevPage() {
                if (currentIndex > 0) {
                    goToPage(currentIndex - 1);
                } else {
                    goToPage(totalPages - 1);
                }
            }
            
            // Event listeners
            prevBtn.addEventListener('click', prevPage);
            nextBtn.addEventListener('click', nextPage);
            
            // Initialize slider
            function initSlider() {
                // Set the width of the slider
                slider.style.width = `${totalPages * 100}%`;
                slider.style.display = 'flex';
                slider.style.transition = 'transform 0.5s ease';
                
                // Set the width of each item
                items.forEach(item => {
                    item.style.width = `${100 / (totalPages * itemsPerPage)}%`;
                    item.style.flexShrink = '0';
                });
                
                initDots();
            }
            
            // Auto slide
            let autoSlideInterval = setInterval(nextPage, 5000);
            
            // Pause auto slide on hover
            const partnersSection = document.querySelector('.partners-slider');
            partnersSection.addEventListener('mouseenter', function() {
                clearInterval(autoSlideInterval);
            });
            
            partnersSection.addEventListener('mouseleave', function() {
                autoSlideInterval = setInterval(nextPage, 5000);
            });
            
            // Initialize on window load
            window.addEventListener('load', initSlider);
            
            // Also initialize when DOM is ready
            if (document.readyState === 'complete') {
                initSlider();
            }
        });
    </script>

    <!-- Trading Tools Section -->
    <section class="trading-tools">
        <div class="container">
            <div class="section-title">
                <h2>Advanced Trading Tools</h2>
                <p>Powerful features designed for serious traders</p>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="tool-card">
                        <div class="tool-img">
                            <i class="fas fa-chart-candlestick"></i>
                        </div>
                        <div class="tool-content">
                            <h3>Technical Analysis</h3>
                            <p>Advanced charting with over 100 technical indicators and drawing tools.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="tool-card">
                        <div class="tool-img">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="tool-content">
                            <h3>Price Alerts</h3>
                            <p>Set custom price alerts and never miss a trading opportunity.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 animate-on-scroll">
                    <div class="tool-card">
                        <div class="tool-img">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="tool-content">
                            <h3>Automated Trading</h3>
                            <p>Create and deploy automated trading strategies without coding.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <div class="section-title">
                <h2>What Our Traders Say</h2>
                <p>Hear from our successful community of traders</p>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4 animate-on-scroll">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <p>"TradeX has completely transformed my trading experience. The tools and resources available are exceptional."</p>
                        </div>
                        <div class="client-info">
                            <div class="client-img">JD</div>
                            <div>
                                <h5>John Doe</h5>
                                <p>Professional Trader</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 animate-on-scroll">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <p>"The mobile app is fantastic! I can monitor my positions and execute trades from anywhere with ease."</p>
                        </div>
                        <div class="client-info">
                            <div class="client-img">SM</div>
                            <div>
                                <h5>Sarah Miller</h5>
                                <p>Swing Trader</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 animate-on-scroll">
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <p>"As a beginner, I found the educational resources incredibly helpful. I went from novice to profitable in just 3 months!"</p>
                        </div>
                        <div class="client-info">
                            <div class="client-img">RJ</div>
                            <div>
                                <h5>Robert Johnson</h5>
                                <p>Beginner Trader</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Start Your Trading Journey?</h2>
            <p>Join thousands of successful traders on our platform today</p>
            <a href="#" class="btn btn-demo">Open Free Account</a>
        </div>
    </section>
    
     <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h3 class="text-white mb-4">TradeX</h3>
                    <p>Advanced trading platform for modern investors. Trade stocks, cryptocurrencies, and more with powerful tools and low fees.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <div class="footer-links">
                        <h5>Platform</h5>
                        <ul>
                            <li><a href="#">Features</a></li>
                            <li><a href="#">Pricing</a></li>
                            <li><a href="#">Download</a></li>
                            <li><a href="#">Demo Account</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <div class="footer-links">
                        <h5>Company</h5>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Press</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <div class="footer-links">
                        <h5>Support</h5>
                        <ul>
                            <li><a href="#">Help Center</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">API Documentation</a></li>
                            <li><a href="#">System Status</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <div class="footer-links">
                        <h5>Legal</h5>
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Cookie Policy</a></li>
                            <li><a href="#">Risk Disclosure</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 pt-4 border-top border-secondary">
                <p>© 2023 TradeX. All rights reserved. TradeX is a registered trademark. Trading involves risk.</p>
            </div>
        </div>
    </footer>

  
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        
                        // Animate progress bar for how-to-trade section
                        if (entry.target.closest('.how-to-trade')) {
                            const progressBar = document.querySelector('.progress-bar-fill');
                            if (progressBar) {
                                progressBar.style.height = '70%';
                            }
                        }
                    }
                });
            }, {
                threshold: 0.1
            });
            
            animatedElements.forEach(element => {
                observer.observe(element);
            });
            
            // Simple market data animation
            const tickerItems = document.querySelectorAll('.ticker-item');
            tickerItems.forEach((item, index) => {
                item.style.transition = 'all 0.5s ease';
                item.style.transitionDelay = (index * 0.1) + 's';
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            });
            
            // Navbar scroll effect
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (window.scrollY > 50) {
                    navbar.style.padding = '10px 0';
                    navbar.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
                } else {
                    navbar.style.padding = '15px 0';
                    navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
                }
            });
        });
    </script>

     @livewireScripts 
     
</body>
</html>