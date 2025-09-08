<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Stock Market Company</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; line-height: 1.6; background: #f5f7fa; }
    header { background: #0d1b2a; color: #fff; padding: 20px 30px; text-align: center; }
    header h1 { margin: 0; font-size: 28px; }
    .container { max-width: 1100px; margin: auto; padding: 20px; }
    section { margin-bottom: 40px; }
    h2 { color: #0d1b2a; margin-bottom: 15px; }
    p { color: #333; }

    /* Team Section */
    .team-container { display: flex; flex-wrap: wrap; gap: 20px; }
    .team-member { background: #fff; padding: 20px; border-radius: 8px; flex: 1 1 250px; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .team-member img { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; }
    .team-member h3 { margin: 5px 0; color: #0d1b2a; }
    .team-member p { color: #555; font-size: 14px; }

    /* License Section */
    .license { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .license ul { list-style: disc; margin-left: 20px; color: #333; }

    @media(max-width:768px){
      .team-container { flex-direction: column; }
    }
  </style>
</head>
<body>

<header>
  <h1>About Us</h1>
</header>

<div class="container">

  <!-- Company Mission -->
  <section>
    <h2>Our Mission</h2>
    <p>At StockMarketPro, our mission is to provide accurate, real-time financial data and insights to empower traders and investors worldwide. We aim to simplify the complex world of stock markets and make investing accessible to everyone.</p>
  </section>

  <!-- Team -->
  <section>
    <h2>Our Team</h2>
    <div class="team-container">
      <div class="team-member">
        <img src="https://via.placeholder.com/100" alt="John Doe">
        <h3>John Doe</h3>
        <p>CEO & Founder</p>
      </div>
      <div class="team-member">
        <img src="https://via.placeholder.com/100" alt="Jane Smith">
        <h3>Jane Smith</h3>
        <p>Chief Technical Officer</p>
      </div>
      <div class="team-member">
        <img src="https://via.placeholder.com/100" alt="Mike Johnson">
        <h3>Mike Johnson</h3>
        <p>Head of Product</p>
      </div>
    </div>
  </section>

  <!-- Company Info -->
  <section>
    <h2>Company Info</h2>
    <p><strong>Founded:</strong> 2025</p>
    <p><strong>Location:</strong> Dhaka, Bangladesh</p>
    <p><strong>Email:</strong> info@stockmarketpro.com</p>
    <p><strong>Phone:</strong> +880 1234 567890</p>
    <p><strong>Website:</strong> www.stockmarketpro.com</p>
  </section>

  <!-- License / Certifications -->
  <section>
    <h2>Licenses & Certifications</h2>
    <div class="license">
      <ul>
        <li>Licensed by the Bangladesh Securities and Exchange Commission (BSEC)</li>
        <li>ISO 9001:2022 Certified for Data Management and Security</li>
        <li>Member of Global FinTech Association</li>
      </ul>
    </div>
  </section>

</div>

</body>
</html>
