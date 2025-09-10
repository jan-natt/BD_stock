<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risk Warning - TradeX</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        
        /* Header */
        .warning-header {
            background: #301e633a;
            color: white;
            padding: 80px 0 60px;
            text-align: center;
        }
        
        .warning-header h1 {
            font-weight: 700;
            font-size: 2.8rem;
            margin-bottom: 20px;
        }
        
        .warning-header p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
            opacity: 0.9;
        }
        
        /* Warning Content */
        .warning-content {
            padding: 60px 0;
        }
        
        .warning-card {
            background: #301e633a;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            border-left: 5px solid var(--warning);
        }
        
        .warning-icon {
            font-size: 40px;
            color: var(--warning);
            margin-bottom: 25px;
            text-align: center;
        }
        
        .warning-text {
            font-size: 1.1rem;
            margin-bottom: 25px;
            color: #fffcfcff;
        }
        
        .warning-note {
            background: #301e633a;
            padding: 20px;
            border-radius: 10px;
            margin: 30px 0;
            border-left: 4px solid var(--warning);
        }
        
        /* Company Details */
        .company-details {
            background: #301e633a;
            padding: 40px;
            border-radius: 15px;
            margin: 40px 0;
        }
        
        .company-details h3 {
            color: var(--primary);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--gray);
        }
        
        .detail-item {
            margin-bottom: 20px;
        }
        
        .detail-item h4 {
            color: var(--dark);
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        /* Restricted Countries */
        .restricted-countries {
            margin: 40px 0;
        }
        
        .country-list {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }
        
        .country-item {
            background: #301e633a;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 0.9rem;
            border: 1px solid var(--gray);
        }
        
        /* Footer Links */
        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 40px 0;
            justify-content: center;
        }
        
        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }
        
        /* Copyright */
        .copyright {
            text-align: center;
            padding: 30px 0;
            color: #666;
            border-top: 1px solid var(--gray);
            margin-top: 40px;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .warning-header h1 {
                font-size: 2.2rem;
            }
            
            .warning-header p {
                font-size: 1rem;
            }
            
            .warning-card {
                padding: 25px;
            }
            
            .warning-text {
                font-size: 1rem;
            }
            
            .country-list {
                gap: 8px;
            }
            
            .country-item {
                padding: 8px 16px;
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 576px) {
            .warning-header {
                padding: 60px 0 40px;
            }
            
            .warning-header h1 {
                font-size: 1.8rem;
            }
            
            .footer-links {
                flex-direction: column;
                align-items: center;
                gap: 12px;
            }
        }
    </style>
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
    <!-- Header Section -->
    <header class="warning-header">
        <div class="container">
            <h1><i class="fas fa-exclamation-triangle me-3"></i>Risk Warning</h1>
            <p>Important information regarding the risks associated with financial trading</p>
        </div>
    </header>

    <!-- Main Content -->
    <main class="warning-content">
        <div class="container">
            <div class="warning-card">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                
                <div class="warning-text">
                    <p>Investing in financial products is a wise decision, but it is important to acknowledge the risks involved. The past performance of a financial product does not guarantee future returns, as the market is constantly evolving. The value of financial products can fluctuate depending on several factors, such as market conditions and the value of underlying securities.</p>
                    
                    <p>Any illustrations, forecasts or hypothetical data provided should be taken for illustrative purposes only, and not as a guarantee of future returns. It is important to note that this website is not intended as a solicitation, invitation, or investment recommendation.</p>
                    
                    <div class="warning-note">
                        <p><strong>Important:</strong> Prior to investing in any financial product or fund, we highly encourage investors to seek the advice of specialised financial, legal, and tax professionals. Furthermore, we urge investors to carefully consider whether the investment is suitable for their individual circumstances, risk tolerance, and investment objectives.</p>
                    </div>
                    
                    <p>Investing in financial products is a wise decision, but it is important to acknowledge the risks involved. The past performance of a financial product does not guarantee future returns, as the market is constantly evolving. The value of financial products can fluctuate depending on several factors, such as market conditions and the value of underlying securities.</p>
                    
                    <p>Any illustrations, forecasts or hypothetical data provided should be taken for illustrative purposes only, and not as a guarantee of future returns. It is important to note that this website is not intended as a solicitation, invitation, or investment recommendation. Prior to investing in any financial product or fund, we highly encourage investors to seek the advice of specialised financial, legal, and tax professionals. Furthermore, we urge investors to carefully consider whether the investment is suitable for their individual circumstances, risk tolerance, and investment objectives.</p>
                </div>
            </div>
            
            <!-- Company Details -->
            <div class="company-details">
                <h3><i class="fas fa-building me-2"></i>Company Information</h3>
                
                <div class="detail-item">
                    <h4>Site Operator</h4>
                    <p>This site is operated by FX Trading LLC. Reg. number - 4062001339764.</p>
                    <p>Address: Blue two-story building, diagonal to La Salle High School, Las Vegas neighborhood, Mata Redonda, San José, Costa Rica</p>
                </div>
                
                <div class="detail-item">
                    <h4>Market Maker Service</h4>
                    <p>To maintain liquidity and ensure competitive pricing, our trading platform employs a market maker service. This service is provided by Pocket Broker Ltd, company number 001352024, which is registered at Island Ring Road, TB office, NRU68, Yeren, Nauru.</p>
                </div>
                
                <div class="detail-item">
                    <h4>Promotion Partner</h4>
                    <p>Promotion partner is Pocket Investments LLC. Reg number 4062001308689. Registered at Costa Rica, San Jose Mata Redonda, Neighborhood Las Vegas, Blue Building Diagonal To La Salle Highschool. Registration number 4062001308689.</p>
                </div>
            </div>
            
            <!-- Restricted Countries -->
            <div class="restricted-countries">
                <h3><i class="fas fa-ban me-2"></i>Restricted Countries</h3>
                <p>Access to our services and use of our products is strictly prohibited for citizens and residents of the following countries:</p>
                
                <div class="country-list">
                   
                    <span class="country-item">Democratic Republic of Congo</span>
                    <span class="country-item">Netherlands</span>
                    <span class="country-item">Ivory Coast</span>
                    <span class="country-item">Ireland</span>
                    <span class="country-item">Russia</span>
                    <span class="country-item">Lithuania</span>
                    <span class="country-item">Moldova</span>
                    <span class="country-item">Luxembourg</span>
                    <span class="country-item">Yemen</span>
                    <span class="country-item">Latvia</span>
                    <span class="country-item">Zimbabwe</span>
                    <span class="country-item">Malta</span>
                    <span class="country-item">Cuba</span>
                    <span class="country-item">Germany</span>
                    <span class="country-item">Venezuela</span>
                    <span class="country-item">Poland</span>
                    <span class="country-item">Serbia</span>
                    <span class="country-item">Portugal</span>
                    <span class="country-item">Montenegro</span>
                    <span class="country-item">Romania</span>
                    <span class="country-item">Italy</span>
                    <span class="country-item">Slovakia</span>
                    <span class="country-item">Slovenia</span>
                    <span class="country-item">Hungary</span>
                    <span class="country-item">Philippines</span>
                    <span class="country-item">India</span>
                </div>
            </div>
            
            <!-- Footer Links -->
            <div class="footer-links">
                <a href="#">About us</a>
                <a href="#">Contacts</a>
                <a href="#">Terms and Conditions</a>
                <a href="#">AML and KYC policy</a>
                <a href="#">Payment policy</a>
                <a href="#">Regulatory Environment</a>
                <a href="#">One-Click Payment Policy</a>
            </div>
            
            <!-- Copyright -->
            <div class="copyright">
                <p>Copyright ©2025 Pocket</p>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>