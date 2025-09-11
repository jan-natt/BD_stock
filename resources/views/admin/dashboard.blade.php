<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Trading Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2962ff;
            --primary-dark: #0039cb;
            --secondary: #ff6d00;
            --dark-bg: #1e2335;
            --dark-card: #252b3f;
            --dark-hover: #2f3651;
            --text-light: #f5f7fb;
            --text-gray: #a2a8c3;
            --success: #00c853;
            --warning: #ffab00;
            --danger: #ff1744;
            --border-radius: 12px;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        /* Reset */
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body { 
            background: var(--dark-bg); 
            color: var(--text-light);
            display: flex;
            min-height: 100vh;
        }

        /* Layout */
        .container {
            display: flex;
            width: 100%;
        }

        /* Sidebar */
        aside {
            width: 280px;
            background: var(--dark-card);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow);
            z-index: 10;
        }

        .sidebar-header {
            padding: 0 25px 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: bold;
            font-size: 20px;
        }

        .brand {
            font-size: 1.4em;
            font-weight: 600;
        }

        .nav {
            flex: 1;
            padding: 0 15px;
        }

        .nav ul {
            list-style: none;
        }

        .nav ul li {
            margin-bottom: 5px;
        }

        .nav ul li a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            text-decoration: none;
            color: var(--text-gray);
            border-radius: 8px;
            transition: all 0.3s;
        }

        .nav ul li a:hover, 
        .nav ul li a.active {
            background: var(--dark-hover);
            color: var(--text-light);
        }

        .nav ul li a i {
            margin-right: 12px;
            font-size: 1.1em;
            width: 24px;
            text-align: center;
        }

        .logout-btn {
            margin: 15px;
            padding: 12px 20px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .logout-btn i {
            margin-right: 8px;
        }

        /* Main Content */
        main {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 1.8em;
            font-weight: 600;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: var(--dark-card);
            padding: 10px 15px;
            border-radius: 8px;
            width: 300px;
        }

        .search-bar input {
            background: transparent;
            border: none;
            color: var(--text-light);
            padding: 5px 10px;
            width: 100%;
            outline: none;
        }

        /* Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--dark-card);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 1em;
            color: var(--text-gray);
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
        }

        .users .card-icon { background: rgba(41, 98, 255, 0.2); color: var(--primary); }
        .trades .card-icon { background: rgba(0, 200, 83, 0.2); color: var(--success); }
        .assets .card-icon { background: rgba(255, 109, 0, 0.2); color: var(--secondary); }
        .revenue .card-icon { background: rgba(171, 71, 255, 0.2); color: #ab47ff; }

        .card-value {
            font-size: 2.2em;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .card-growth {
            font-size: 0.9em;
            display: flex;
            align-items: center;
        }

        .growth-up { color: var(--success); }
        .growth-down { color: var(--danger); }

        /* Tables */
        .table-section {
            background: var(--dark-card);
            border-radius: var(--border-radius);
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.3em;
            font-weight: 600;
        }

        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9em;
            font-weight: 500;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        table th {
            padding: 15px;
            text-align: left;
            color: var(--text-gray);
            font-weight: 500;
        }

        table td {
            padding: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .status-active {
            background: rgba(0, 200, 83, 0.15);
            color: var(--success);
        }

        .status-pending {
            background: rgba(255, 171, 0, 0.15);
            color: var(--warning);
        }

        .status-blocked {
            background: rgba(255, 23, 68, 0.15);
            color: var(--danger);
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-block {
            background: rgba(255, 23, 68, 0.15);
            color: var(--danger);
        }

        .btn-block:hover {
            background: rgba(255, 23, 68, 0.25);
        }

        .btn-unblock {
            background: rgba(0, 200, 83, 0.15);
            color: var(--success);
        }

        .btn-unblock:hover {
            background: rgba(0, 200, 83, 0.25);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 992px) {
            aside {
                width: 80px;
            }
            
            .brand, .nav-text {
                display: none;
            }
            
            .nav ul li a {
                justify-content: center;
                padding: 15px;
            }
            
            .nav ul li a i {
                margin-right: 0;
                font-size: 1.3em;
            }
            
            .logout-btn span {
                display: none;
            }
            
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .cards {
                grid-template-columns: 1fr;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .search-bar {
                width: 100%;
                margin-top: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <aside>
        <div class="sidebar-header">
            <div class="logo">T</div>
            <div class="brand">TradeAdmin</div>
        </div>
        <nav class="nav">
            <ul>
                <li><a href="#" class="active"><i class="fas fa-chart-line"></i> <span class="nav-text">Dashboard</span></a></li>
                <li><a href="#"><i class="fas fa-users"></i> <span class="nav-text">Users</span></a></li>
                <li><a href="#"><i class="fas fa-exchange-alt"></i> <span class="nav-text">Trades</span></a></li>
                <li><a href="#"><i class="fas fa-coins"></i> <span class="nav-text">Assets</span></a></li>
                <li><a href="#"><i class="fas fa-chart-bar"></i> <span class="nav-text">Analytics</span></a></li>
                <li><a href="#"><i class="fas fa-cog"></i> <span class="nav-text">Settings</span></a></li>
            </ul>
        </nav>
        <button class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </button>
    </aside>

    <!-- Main Content -->
    <main>
        <div class="header">
            <h1 class="page-title">Dashboard</h1>
            <div class="search-bar">
                <i class="fas fa-search" style="color: var(--text-gray);"></i>
                <input type="text" placeholder="Search...">
            </div>
        </div>

        <!-- Top Cards -->
        <div class="cards">
            <div class="card users">
                <div class="card-header">
                    <div class="card-title">TOTAL USERS</div>
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="card-value">2,548</div>
                <div class="card-growth growth-up">
                    <i class="fas fa-arrow-up"></i> 12.5% from last month
                </div>
            </div>
            
            <div class="card trades">
                <div class="card-header">
                    <div class="card-title">TOTAL TRADES</div>
                    <div class="card-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                </div>
                <div class="card-value">18,742</div>
                <div class="card-growth growth-up">
                    <i class="fas fa-arrow-up"></i> 8.3% from last month
                </div>
            </div>
            
            <div class="card assets">
                <div class="card-header">
                    <div class="card-title">TOTAL ASSETS</div>
                    <div class="card-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
                <div class="card-value">156</div>
                <div class="card-growth growth-up">
                    <i class="fas fa-arrow-up"></i> 3 new this month
                </div>
            </div>
            
            <div class="card revenue">
                <div class="card-header">
                    <div class="card-title">PLATFORM REVENUE</div>
                    <div class="card-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="card-value">$142.8K</div>
                <div class="card-growth growth-down">
                    <i class="fas fa-arrow-down"></i> 2.1% from last month
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-section">
            <div class="section-header">
                <h2 class="section-title">Recent Users</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="avatar">JD</div>
                                <div>John Doe</div>
                            </div>
                        </td>
                        <td>john@example.com</td>
                        <td>Trader</td>
                        <td><span class="status status-active">Active</span></td>
                        <td><button class="btn-action btn-block">Block</button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="avatar">JS</div>
                                <div>Jane Smith</div>
                            </div>
                        </td>
                        <td>jane@example.com</td>
                        <td>Investor</td>
                        <td><span class="status status-active">Active</span></td>
                        <td><button class="btn-action btn-block">Block</button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="avatar">MB</div>
                                <div>Mike Brown</div>
                            </div>
                        </td>
                        <td>mike@example.com</td>
                        <td>Broker</td>
                        <td><span class="status status-blocked">Blocked</span></td>
                        <td><button class="btn-action btn-unblock">Unblock</button></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-info">
                                <div class="avatar">SR</div>
                                <div>Sara Rodriguez</div>
                            </div>
                        </td>
                        <td>sara@example.com</td>
                        <td>Trader</td>
                        <td><span class="status status-pending">Pending</span></td>
                        <td><button class="btn-action btn-block">Block</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Recent Trades Table -->
        <div class="table-section">
            <div class="section-header">
                <h2 class="section-title">Recent Trades</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Trade ID</th>
                        <th>Seller</th>
                        <th>Buyer</th>
                        <th>Asset</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>#TRD-1023</td>
                        <td>Jane Smith</td>
                        <td>John Doe</td>
                        <td>AAPL</td>
                        <td>$12,450</td>
                        <td><span class="status status-active">Completed</span></td>
                    </tr>
                    <tr>
                        <td>#TRD-1024</td>
                        <td>Mike Brown</td>
                        <td>Jane Smith</td>
                        <td>BTC</td>
                        <td>$16,780</td>
                        <td><span class="status status-active">Completed</span></td>
                    </tr>
                    <tr>
                        <td>#TRD-1025</td>
                        <td>John Doe</td>
                        <td>Sara Rodriguez</td>
                        <td>TSLA</td>
                        <td>$8,540</td>
                        <td><span class="status status-pending">Processing</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>