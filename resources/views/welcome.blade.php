@extends('layouts.main')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stock Market Homepage</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f5f7fa;
      color: #333;
    }

    /* Navbar */
    nav {
      background: #0d1b2a;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #fff;
     
      top: 0;
      z-index: 1000;
    }

    .logo {
      font-size: 22px;
      font-weight: bold;
      color: #00c896;
      text-decoration: none;
    }

    .nav-links {
      display: flex;
      gap: 25px;
      align-items: center;
    }

    .nav-item {
      position: relative;
    }

    .nav-item > a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      padding: 6px 10px;
      transition: 0.3s;
    }

    .nav-item > a:hover {
      color: #00c896;
    }

    /* Dropdown */
    .dropdown {
      display: none;
      position: absolute;
      top: 35px;
      left: 0;
      background: #fff;
      min-width: 160px;
      box-shadow: 0 4px 6px rgba(0,0,0,0.2);
      border-radius: 6px;
      overflow: hidden;

    }

    .dropdown a {
      display: block;
      padding: 10px;
      text-decoration: none;
      color: #333;
      transition: 0.3s;
    }

    .dropdown a:hover {
      background: #f1f1f1;
      color: #00c896;
    }

    .nav-item:hover .dropdown {
      display: block;
    }

    /* Page Layout */
    .container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .section {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      margin-bottom: 25px;
    }

    .section h2 {
      margin-bottom: 15px;
      color: #0d1b2a;
    }

    /* Two-column layout */
    .grid-2 {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    /* Table style */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th, table td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background: #0d1b2a;
      color: #fff;
    }

    /* News */
    .news-item {
      margin-bottom: 10px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 10px;
    }

    .news-item a {
      text-decoration: none;
      color: #0d1b2a;
      font-weight: bold;
    }

    .news-item a:hover {
      color: #00c896;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav>
    <a href="#" class="logo"></a>
    <div class="nav-links">
      <div class="nav-item">
        <a href="#">Market Overview ▾</a>
        <div class="dropdown">
          <a href="#">Live Market</a>
          <a href="#">Indices</a>
          <a href="#">Top Gainers</a>
          <a href="#">Top Losers</a>
        </div>
      </div>
      <div class="nav-item">
        <a href="#">IPO Setting ▾</a>
        <div class="dropdown">
          <a href="#">Current IPOs</a>
          <a href="#">Upcoming IPOs</a>
          <a href="#">Apply Now</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container">
    
    <!-- Live Stock Summary -->
     <div class="section">
    <h2>📊 Live Stock Market Chart</h2>
    <canvas id="stockChart" height="100"></canvas>
  </div>

    <!-- Top Gainers & Losers -->
    <div class="grid-2">
      <div class="section">
        <h2>🚀 Top Gainers</h2>
        <table>
          <tr><th>Stock</th><th>Price</th><th>Change</th></tr>
          <tr><td>ABC Ltd</td><td>$120</td><td style="color:green;">+5.6%</td></tr>
          <tr><td>XYZ Corp</td><td>$75</td><td style="color:green;">+4.2%</td></tr>
        </table>
      </div>

      <div class="section">
        <h2>📉 Top Losers</h2>
        <table>
          <tr><th>Stock</th><th>Price</th><th>Change</th></tr>
          <tr><td>PQR Ltd</td><td>$60</td><td style="color:red;">-3.1%</td></tr>
          <tr><td>LMN Inc</td><td>$88</td><td style="color:red;">-2.5%</td></tr>
        </table>
      </div>
    </div>

    <!-- Market Indices -->
    <div class="section">
      <h2>📈 Market Indices</h2>
      <table>
        <tr><th>Index</th><th>Value</th><th>Change</th></tr>
        <tr><td>DSEX</td><td>6,450</td><td style="color:green;">+0.85%</td></tr>
        <tr><td>DSES</td><td>1,390</td><td style="color:green;">+0.65%</td></tr>
        <tr><td>DS30</td><td>2,310</td><td style="color:red;">-0.12%</td></tr>
      </table>
    </div>

    <!-- News Highlights -->
    <div class="section">
      <h2>📰 News Highlights</h2>
      <div class="news-item">
        <a href="#">Stock Market closes higher for 3rd straight day</a>
        <p>Investors gained confidence after policy updates...</p>
      </div>
      <div class="news-item">
        <a href="#">Upcoming IPO: ABC Tech Ltd files draft papers</a>
        <p>Company expects to raise $200M from the issue...</p>
      </div>
      <div class="news-item">
        <a href="#">Central Bank adjusts repo rate to control inflation</a>
        <p>Market analysts expect a mixed impact on stocks...</p>
      </div>
    </div>

  </div>

  
 <script>
    const ctx = document.getElementById('stockChart').getContext('2d');

    // Initial Chart
    const stockChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [],
        datasets: [{
          label: "Stock Price (USD)",
          data: [],
          borderColor: "#00c896",
          backgroundColor: "rgba(0,200,150,0.2)",
          fill: true,
          tension: 0.3
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' }
        },
        scales: {
          x: { title: { display: true, text: 'Time' }},
          y: { title: { display: true, text: 'Price (Tk)' }}
        }
      }
    });

    // 🔄 Fetch data from Finnhub API every 5 seconds
    async function fetchStockData() {
      try {
        // Finnhub API - change symbol if needed
        let response = await fetch("https://finnhub.io/api/v1/quote?symbol=AAPL&token=d2v7nc9r01qq994ihpkgd2v7nc9r01qq994ihpl0");
        let data = await response.json();

        // Finnhub current price = data.c
        let price = data.c;
        let time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});

        // Add new point to chart
        stockChart.data.labels.push(time);
        stockChart.data.datasets[0].data.push(price);

        // Keep only last 15 points
        if (stockChart.data.labels.length > 15) {
          stockChart.data.labels.shift();
          stockChart.data.datasets[0].data.shift();
        }

        stockChart.update();
      } catch (error) {
        console.error("API Fetch Error:", error);
      }
    }

    // Initial fetch
    fetchStockData();

    // Auto-update every 5 seconds
    setInterval(fetchStockData, 5000);
  </script>

</body>
</html>


     @endsection     