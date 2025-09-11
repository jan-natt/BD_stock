<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pocket Broker - Trading Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2962ff;
            --primary-dark: #0039cb;
            --secondary: #00c853;
            --dark-bg: #0f172a;
            --dark-card: #1e293b;
            --dark-border: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-green: #10b981;
            --accent-red: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--dark-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }
        
        /* Sidebar */
        .sidebar {
            width: 280px;
            background-color: var(--dark-card);
            padding: 20px 0;
            border-right: 1px solid var(--dark-border);
            display: flex;
            flex-direction: column;
        }
        
        .logo {
            padding: 0 20px 20px;
            border-bottom: 1px solid var(--dark-border);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo h1 {
            font-size: 24px;
            font-weight: 700;
        }
        
        .logo span {
            color: var(--secondary);
        }
        
        .nav-section {
            padding: 0 15px;
            margin-bottom: 25px;
        }
        
        .section-title {
            color: var(--text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            padding-left: 15px;
        }
        
        .nav-links {
            list-style: none;
        }
        
        .nav-links li {
            margin-bottom: 5px;
        }
        
        .nav-links a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            text-decoration: none;
            color: var(--text-primary);
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .nav-links a:hover, .nav-links a.active {
            background-color: rgba(41, 98, 255, 0.1);
            color: var(--primary);
        }
        
        .nav-links a.active {
            font-weight: 600;
        }
        
        .nav-links i {
            width: 20px;
            text-align: center;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        /* Header */
        .header {
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--dark-card);
            border-bottom: 1px solid var(--dark-border);
        }
        
        .trading-pair {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .crypto-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(45deg, #f7931a, #f2a900);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .pair-info h2 {
            font-size: 18px;
        }
        
        .pair-info .time {
            color: var(--text-secondary);
            font-size: 12px;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .balance {
            text-align: right;
        }
        
        .balance-label {
            color: var(--text-secondary);
            font-size: 12px;
        }
        
        .balance-amount {
            font-weight: 600;
            font-size: 16px;
        }
        
        .btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
        }
        
        /* Dashboard Content */
        .dashboard {
            padding: 20px;
            overflow-y: auto;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .trading-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .chart-container {
            background-color: var(--dark-card);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--dark-border);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 16px;
            font-weight: 600;
        }
        
        .chart-actions {
            display: flex;
            gap: 10px;
        }
        
        .time-filter {
            display: flex;
            background-color: var(--dark-bg);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .time-filter button {
            padding: 6px 12px;
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 12px;
        }
        
        .time-filter button.active {
            background-color: var(--primary);
            color: white;
        }
        
        /* Trading Panel */
        .trading-panel {
            background-color: var(--dark-card);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--dark-border);
            display: flex;
            flex-direction: column;
        }
        
        .trade-type {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .trade-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            background-color: var(--dark-bg);
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .trade-btn.active {
            background-color: var(--primary);
            color: white;
        }
        
        .buy-btn.active {
            background-color: var(--accent-green);
        }
        
        .sell-btn.active {
            background-color: var(--accent-red);
        }
        
        .input-group {
            margin-bottom: 15px;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 6px;
            border: 1px solid var(--dark-border);
            background-color: var(--dark-bg);
            color: var(--text-primary);
            font-size: 16px;
        }
        
        .slider {
            width: 100%;
            margin: 10px 0;
        }
        
        .amount-details {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }
        
        .expiration {
            background-color: var(--dark-bg);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .expiration-title {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .expiration-time {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 2px;
            color: var(--accent-green);
        }
        
        .execute-btn {
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .execute-btn:hover {
            opacity: 0.9;
        }
        
        /* Price Ticker */
        .price-ticker {
            background-color: var(--dark-card);
            border-radius: 12px;
            padding: 15px 20px;
            border: 1px solid var(--dark-border);
            overflow: hidden;
        }
        
        .ticker-title {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 10px;
        }
        
        .ticker-values {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .ticker-value {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid var(--dark-border);
        }
        
        .ticker-value:last-child {
            border-bottom: none;
        }
        
        .value-number {
            font-family: 'Courier New', monospace;
            font-size: 16px;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .trading-container {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }
            
            .logo h1, .nav-links span, .section-title {
                display: none;
            }
            
            .header {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <i class="fas fa-coins" style="color: var(--secondary); font-size: 28px;"></i>
            <h1>TEADX</h1>
        </div>
        
        <div class="nav-section">
            <div class="section-title">Trading</div>
            <ul class="nav-links">
                <li><a href="#" class="active"><i class="fas fa-chart-line"></i> <span>Bitcoin OTC</span></a></li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="section-title">Finance</div>
            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-user"></i> <span>Profile</span></a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> <span>Market</span></a></li>
                <li><a href="#"><i class="fas fa-trophy"></i> <span>Achievements</span></a></li>
                <li><a href="#"><i class="fas fa-comment"></i> <span>Chat</span></a></li>
                <li><a href="#"><i class="fas fa-question-circle"></i> <span>Help</span></a></li>
            </ul>
        </div>
        
        <div class="nav-section">
            <div class="section-title">Pocket Friends</div>
            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-tag"></i> <span>PROMO</span></a></li>
                <li><a href="#"><i class="fas fa-door-open"></i> <span>OPEN</span></a></li>
            </ul>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="trading-pair">
                <div class="crypto-icon">
                    <i class="fab fa-bitcoin"></i>
                </div>
                <div class="pair-info">
                    <h2>Bitcoin OTC</h2>
                    <div class="time">09:40:52 UTC+6</div>
                </div>
            </div>
            
            <div class="header-actions">
                <div class="balance">
                    <div class="balance-label">Balance</div>
                    <div class="balance-amount">$8,451.23</div>
                </div>
                <button class="btn"><i class="fas fa-plus"></i> Deposit</button>
            </div>
        </header>
        
        <!-- Dashboard Content -->
        <section class="dashboard">
            <div class="trading-container">
                <div class="chart-container">
                    <div class="chart-header">
                        <h3 class="chart-title">BTC/USD Chart</h3>
                        <div class="chart-actions">
                            <div class="time-filter">
                                <button class="active">1H</button>
                                <button>1D</button>
                                <button>1W</button>
                                <button>1M</button>
                            </div>
                        </div>
                    </div>
                    <div style="height: 300px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary);">
                        <div style="text-align: center;">
                            <i class="fas fa-chart-line" style="font-size: 48px; margin-bottom: 15px;"></i>
                            <p>Live trading chart would be displayed here</p>
                        </div>
                    </div>
                </div>
                
                <div class="trading-panel">
                    <div class="trade-type">
                        <div class="trade-btn buy-btn active">BUY</div>
                        <div class="trade-btn sell-btn">SELL</div>
                    </div>
                    
                    <div class="input-group">
                        <label for="amount">Investment Amount (USD)</label>
                        <input type="number" id="amount" value="100">
                    </div>
                    
                    <input type="range" min="10" max="1000" value="100" class="slider" id="amountRange">
                    
                    <div class="amount-details">
                        <span>Min: $10</span>
                        <span>Max: $1,000</span>
                    </div>
                    
                    <div class="expiration">
                        <div class="expiration-title">Expiration time</div>
                        <div class="expiration-time">09:41:00</div>
                    </div>
                    
                    <button class="execute-btn">EXECUTE TRADE</button>
                </div>
            </div>
            
            <div class="price-ticker">
                <div class="ticker-title">Live Price Feed</div>
                <div class="ticker-values">
                    <div class="ticker-value">
                        <span class="value-number">111,268,012</span>
                    </div>
                    <div class="ticker-value">
                        <span class="value-number">111,267,999</span>
                    </div>
                    <div class="ticker-value">
                        <span class="value-number">111,267,980</span>
                    </div>
                    <div class="ticker-value">
                        <span class="value-number">111,267,960</span>
                    </div>
                    <div class="ticker-value">
                        <span class="value-number">111,267,940</span>
                    </div>
                    <div class="ticker-value">
                        <span class="value-number">111,267,920</span>
                    </div>
                    <div class="ticker-value">
                        <span class="value-number">111,267,900</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trade type buttons
            const tradeBtns = document.querySelectorAll('.trade-btn');
            tradeBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    tradeBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Amount slider
            const amountSlider = document.getElementById('amountRange');
            const amountInput = document.getElementById('amount');
            
            amountSlider.addEventListener('input', function() {
                amountInput.value = this.value;
            });
            
            amountInput.addEventListener('input', function() {
                amountSlider.value = this.value;
            });
            
            // Update expiration time every second
            function updateExpirationTime() {
                const now = new Date();
                const expirationElement = document.querySelector('.expiration-time');
                
                // Set expiration to next minute
                const expiration = new Date(now);
                expiration.setMinutes(expiration.getMinutes() + 1);
                expiration.setSeconds(0);
                expiration.setMilliseconds(0);
                
                const diff = expiration - now;
                const seconds = Math.floor(diff / 1000);
                
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                
                expirationElement.textContent = 
                    `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}:00`;
            }
            
            updateExpirationTime();
            setInterval(updateExpirationTime, 1000);
            
            // Simulate live price updates
            const tickerValues = document.querySelectorAll('.ticker-value .value-number');
            setInterval(() => {
                tickerValues.forEach(valueElement => {
                    let value = parseInt(valueElement.textContent.replace(/,/g, ''));
                    value += Math.floor(Math.random() * 21) - 10; // Random change between -10 and +10
                    valueElement.textContent = value.toLocaleString('en-US');
                });
            }, 2000);
        });
    </script>
</body>
</html>