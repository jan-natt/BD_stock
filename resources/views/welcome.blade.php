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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
   
    
    
        
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">TradeX</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About</a>
                    </li>
                      @if (Route::has('login'))
            @auth
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-signup" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
            @else
                <li class="nav-item ms-lg-3 ">
                    <a href="{{ route('login') }}" class="btn btn-signup">Log in</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item ms-lg-3 ">
                        <a href="{{ route('register') }}" class="btn btn-signup">Register</a>
                    </li>
                @endif
            @endauth
        @endif
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <br>
    <br>
    <div>
        @yield('content')
    </div>

    
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