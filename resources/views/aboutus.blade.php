@extends('welcome')
@section('content')

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
@endsection