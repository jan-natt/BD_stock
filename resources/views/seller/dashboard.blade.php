<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard | StockMarket</title>
    <style>
        /* Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            color: #333;
        }

        /* Layout */
        .container {
            display: flex;
            min-height: 100vh;
        }

        aside {
            width: 250px;
            background: #fff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        main {
            flex: 1;
            padding: 20px;
        }

        /* Sidebar */
        .sidebar-header {
            font-size: 1.5em;
            font-weight: bold;
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }

        .nav ul {
            list-style: none;
            margin-top: 20px;
        }

        .nav ul li {
            margin-bottom: 10px;
        }

        .nav ul li a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .nav ul li a:hover {
            background: #f0f0f0;
        }

        .nav form button {
            width: 100%;
            padding: 12px 20px;
            border: none;
            background: #e74c3c;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .nav form button:hover {
            background: #c0392b;
        }

        /* Cards */
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            padding: 20px;
            flex: 1;
            min-width: 200px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .card h2 {
            font-size: 0.9em;
            color: #888;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1.8em;
            font-weight: bold;
        }

        .wallet { color: #27ae60; }
        .active-orders { color: #2980b9; }
        .completed { color: #8e44ad; }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        table thead {
            background: #f8f8f8;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            font-weight: bold;
            color: #555;
        }

        .status-active {
            color: #27ae60;
            font-weight: bold;
        }

        .btn-cancel {
            padding: 6px 12px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-cancel:hover {
            background: #c0392b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .cards {
                flex-direction: column;
            }

            aside {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- Sidebar -->
    <aside>
        <div class="sidebar-header">Seller Dashboard</div>
        <nav class="nav">
            <ul>
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">My Assets</a></li>
                <li><a href="#">Add Sell Order</a></li>
                <li><a href="#">Trade History</a></li>
            </ul>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        <!-- Top Cards -->
        <div class="cards">
            <div class="card wallet">
                <h2>Wallet Balance</h2>
                <p>$12,450</p>
            </div>
            <div class="card active-orders">
                <h2>Active Sell Orders</h2>
                <p>5 Orders</p>
            </div>
            <div class="card completed">
                <h2>Completed Trades</h2>
                <p>120 Trades</p>
            </div>
        </div>

        <!-- Active Orders Table -->
        <h2 style="margin-bottom: 10px;">Your Active Sell Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Asset</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>AAPL</td>
                    <td>10</td>
                    <td>$150</td>
                    <td class="status-active">Active</td>
                    <td><button class="btn-cancel">Cancel</button></td>
                </tr>
                <tr>
                    <td>BTC</td>
                    <td>0.5</td>
                    <td>$28,500</td>
                    <td class="status-active">Active</td>
                    <td><button class="btn-cancel">Cancel</button></td>
                </tr>
            </tbody>
        </table>
    </main>

</div>

</body>
</html>
