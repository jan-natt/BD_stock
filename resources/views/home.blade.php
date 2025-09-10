@extends('welcome')
@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1>Trade Smarter, Invest Better</h1>
                    <p>Join thousands of traders on our advanced platform. Access real-time market data, advanced charting tools, and execute trades with confidence.</p>
                    <div class="hero-btns">
                        <a href="#" class="btn btn-demo">+ Deposit</a>
                        
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('assets/img/banar.png') }}" class="img-fluid hero-image" alt="Trading Platform">
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
    @endsection