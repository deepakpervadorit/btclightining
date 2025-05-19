<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FunSweep</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background: url("{{asset('assets/img/image.jpg')}}") no-repeat center center fixed;
      background-size: cover;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }
    .overlay {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(240, 240, 240, 0.8));
      min-height: 100vh;
      padding: 30px 15px;
    }
    .container {
      max-width: 1200px;
    }

    /* Logo Section */
    .logo-section {
      text-align: center;
      margin-bottom: 30px;
    }
    .logo {
      width: 250px;
      transition: transform 0.3s ease;
    }
    .logo:hover {
      transform: scale(1.05);
    }

    /* 3D Button Base Style */
    .btn-custom {
      border-radius: 50px;
      padding: 12px 30px;
      font-weight: 600;
      margin: 8px;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
      position: relative;
      border: none;
    }
    .btn-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }
    .btn-custom:active {
      transform: translateY(1px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* Deposit Button */
    .btn-deposit {
      background: linear-gradient(45deg, #28a745, #34c759);
      color: white;
    }
    .btn-deposit:hover {
      background: linear-gradient(45deg, #218838, #2db34a);
      color: white;
    }

    /* Redeem Button */
    .btn-redeem {
      background: linear-gradient(45deg, #dc3545, #ff6f61);
      color: white;
    }
    .btn-redeem:hover {
      background: linear-gradient(45deg, #c82333, #ff5a4a);
      color: white;
    }

    /* Game Link Buttons */
    .btn-game {
      background: linear-gradient(45deg, #343a40, #4b535b);
      color: #ffc107;
      font-weight: 600;
      margin: 10px;
      border-radius: 25px;
      padding: 12px 25px;
      transition: all 0.3s ease;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
      position: relative;
      border: none;
    }
    .btn-game:hover {
      background: linear-gradient(45deg, #495057, #5c666f);
      color: #ffd700;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }
    .btn-game:active {
      transform: translateY(1px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* Section Styling */
    .section {
      padding: 50px 0;
      text-align: center;
    }
    .section h2 {
      font-weight: 700;
      color: #343a40;
      margin-bottom: 30px;
      position: relative;
      display: inline-block;
    }
    .section h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 50%;
      height: 4px;
      background: linear-gradient(90deg, #dc3545, #28a745);
      border-radius: 2px;
    }

    /* Redeem Card */
    .redeem-card {
      background: #fff;
      border: none;
      border-radius: 20px;
      padding: 30px;
      max-width: 450px;
      margin: 0 auto;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
      position: relative;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .redeem-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    .redeem-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #dc3545, #28a745);
    }
    .redeem-card h2 {
      color: #343a40;
      font-weight: 600;
      margin-bottom: 20px;
    }
    .redeem-card .form-control,
    .redeem-card .form-select {
      border: 1px solid #ced4da;
      padding: 12px;
      border-radius: 10px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .redeem-card .form-control:focus,
    .redeem-card .form-select:focus {
      border-color: #28a745;
      box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
      outline: none;
    }
    .redeem-card .btn-primary {
      background: linear-gradient(45deg, #dc3545, #ff6f61);
      border: none;
      padding: 12px;
      font-weight: bold;
      border-radius: 10px;
      transition: background 0.3s ease;
    }
    .redeem-card .btn-primary:hover {
      background: linear-gradient(45deg, #c82333, #ff5a4a);
    }

    /* Game Links Section */
    .game-links {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
    }

    /* FAQ Section */
    .faq-section {
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      margin-top: 30px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .faq-section:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    .faq-title {
      color: #343a40;
      font-weight: 700;
      margin-bottom: 30px;
      position: relative;
      display: inline-block;
    }
    .faq-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 50%;
      height: 4px;
      background: linear-gradient(90deg, #dc3545, #28a745);
      border-radius: 2px;
    }
    .faq-section h5 {
      color: #dc3545;
      font-weight: 600;
      margin-bottom: 15px;
    }
    .faq-section p {
      color: #555;
      line-height: 1.6;
    }

    /* Footer */
    footer {
      margin-top: 60px;
      padding: 25px;
      background: linear-gradient(45deg, #343a40, #4b535b);
      color: #fff;
      text-align: center;
      border-radius: 15px 15px 0 0;
      box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.2);
    }
    footer p {
      margin: 0;
      font-size: 14px;
      letter-spacing: 1px;
    }
  </style>
</head>
<body>

<div class="overlay">
  <div class="container text-center">
    <!-- Logo -->
    <div class="logo-section">
      <img src="https://logodix.com/logo/773821.png" alt="FunSweep Logo" class="logo">
    </div>

    <!-- Top Buttons -->
    <div class="my-4">
      <a href="{{route('show.merchant.deposit.step2',$merchantid)}}" class="btn btn-custom btn-deposit">Deposit</a>
      <button class="btn btn-custom btn-redeem">Redeem</button>
    </div>

    <!-- Game Links -->
    <div class="my-4">
      <button class="btn btn-game">View Game Links</button>
      <button class="btn btn-game">How to?</button>
    </div>

    <!-- Redeem Form -->
    <div class="section">
      <div class="redeem-card">
        <h2>Redeem Form</h2>
        <form id="withdrawalform">
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Enter your Game ID" name="gameid" id="gameid">
          </div>
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Enter your BTC (lightning) Address" name="invoice" id="invoice">
          </div>
          <div class="mb-3">
            <input type="number" class="form-control" placeholder="Enter amount to redeem" name="amount" id="amount" step="0.1">
          </div>
          <!--<div class="mb-3">-->
          <!--  <select class="form-select">-->
          <!--    <option selected>-- Select Tip Amount --</option>-->
          <!--    <option value="0">No Tip</option>-->
          <!--    <option value="5">5%</option>-->
          <!--    <option value="10">10%</option>-->
          <!--  </select>-->
          <!--</div>-->
          <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
      </div>
    </div>

    <!-- Game Links Section -->
    <div class="section">
      <h2>Game Links</h2>
      <div class="game-links">
        <button class="btn btn-game">Gamevault</button>
        <button class="btn btn-game">Golden Dragon</button>
        <button class="btn btn-game">Vblink</button>
        <button class="btn btn-game">Riversweeps</button>
        <button class="btn btn-game">Orion Stars</button>
      </div>
    </div>

    <!-- FAQ Section -->
    <div class="section">
      <h2 class="faq-title">FAQ</h2>

      <div class="faq-section text-start mx-auto" style="max-width:600px;">
        <h5>Deposit</h5>
        <p><strong>How do I make a deposit?</strong><br>Click on "Deposit" and follow the instructions to add funds using Bitcoin Lightning.</p>
        <p><strong>Minimum deposit amount?</strong><br>$20 minimum deposit.</p>
        <p><strong>How long does it take?</strong><br>Usually instant. Some cases may take a few minutes.</p>
      </div>

      <div class="faq-section text-start mx-auto" style="max-width:600px;">
        <h5>Redeem</h5>
        <p><strong>How do I redeem?</strong><br>Click "Redeem" and fill out the form.</p>
        <p><strong>Minimum & Maximum redeem?</strong><br>Minimum $20, maximum $400/day.</p>
        <p><strong>When are redemptions processed?</strong><br>Between 12 PM - 3 PM EST daily.</p>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <p>© 2025 FunSweep. All Rights Reserved.</p>
  </footer>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
    $('#withdrawalform').on('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting normally

        var invoice = $("#invoice").val();
        var amount = $("#amount").val();
        var gameid = $("#gameid").val();
        var settings = {
            "url": "{{route('merchant.withdraw')}}",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json"
            },
            "data": JSON.stringify({
                "currency": "USD",
                "amount": amount,
                "payment_method":"Try Speed",
                "invoice":invoice,
                "gameid":gameid,
                "merchant_id":"{{$merchantid}}",
                "_token":"{{csrf_token()}}"
            }),
        };
    $.ajax(settings).done(function(response) {
        console.log(response);
        toastr.success('Your withdraw request will be approved by admin');
        // window.location.href="{{url('/login')}}";
    }).fail(function(xhr, status, error) {
        console.error("Request failed with status: " + status + ", error: " + error);
        console.error("Response: " + xhr.responseText);
    });
        

});

});
</script>
</html>