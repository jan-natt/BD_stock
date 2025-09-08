<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Stock Market Header</title>
     <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    /* Header */
    header {
      background: #0d1b2a;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #fff;
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    /* Logo */
    .logo {
      font-size: 22px;
      font-weight: bold;
      color: #00c896;
      text-decoration: none;
    }

    /* Search Bar */
    .search-bar {
      flex: 1;
      margin: 10px 40px;
      display: flex;
      max-width: 400px;
    }

    .search-bar input {
      width: 100%;
      padding: 8px 12px;
      border: none;
      border-radius: 5px 0 0 5px;
      outline: none;
    }

    .search-bar button {
      padding: 8px 15px;
      border: none;
      background: #00c896;
      color: #fff;
      cursor: pointer;
      border-radius: 0 5px 5px 0;
    }

    .search-bar button:hover {
      background: #009e74;
    }

    /* Navigation */
    nav ul {
      list-style: none;
      display: flex;
      gap: 20px;
      margin: 0;
      padding: 0;
      align-items: center;
    }

    nav ul li a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    nav ul li a:hover {
      color: #00c896;
    }

    /* Auth Links */
    .auth-links a {
      margin-left: 15px;
      padding: 6px 12px;
      border: 1px solid #00c896;
      border-radius: 4px;
      color: #00c896;
      text-decoration: none;
      transition: 0.3s;
    }

    .auth-links a:hover {
      background: #00c896;
      color: #fff;
    }
  </style>
</head>
<body>

  <header>
    <!-- Logo -->
    <a href="#" class="logo">StockMarket</a>

    <!-- Search Bar -->
    <div class="search-bar">
      <input type="text" placeholder="Search stocks...">
      <button>Search</button>
    </div>

    <!-- Navigation + Auth -->
    <nav>
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="about">About Us</a></li>
        <li><a href="#">Pricing</a></li>
        <li><a href="#">Contact Us</a></li>

        <!-- Laravel Auth Section -->
        @if (Route::has('login'))
          <div class="auth-links">
              @auth
                  <a href="{{ url('/dashboard') }}">Dashboard</a>
              @else
                  <a href="{{ route('login') }}">Login</a>
                  @if (Route::has('register'))
                      <a href="{{ route('register') }}">Register</a>
                  @endif
              @endauth
          </div>
        @endif
      </ul>
    </nav>
  </header>


