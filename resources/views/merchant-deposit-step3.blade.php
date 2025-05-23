<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FunSweep - Enter Deposit Amount</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      /*background: url("{{asset('assets/img/image.jpg')}}") no-repeat center center fixed;*/
      /*background-size: cover;*/
      /*font-family: 'Poppins', sans-serif;*/
      /*margin: 0;*/
      /*padding: 0;*/
      /*display: flex;*/
      /*justify-content: center;*/
      /*align-items: center;*/
      /*min-height: 100vh;*/
    }
    .overlay {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(240, 240, 240, 0.8));
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      /*height: 100%;*/
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      box-sizing: border-box;
    }
    .deposit-card {
      background: #fff;
      border: none;
      border-radius: 20px;
      padding: 30px;
      width: 100%;
      max-width: 450px;
      max-height: 800px;
      /*overflow-y: auto;*/
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
      position: relative;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .deposit-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    .deposit-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #dc3545, #28a745);
    }
    .logo-section {
      text-align: center;
      margin-bottom: 20px;
    }
    .logo {
      width: 180px;
      transition: transform 0.3s ease;
    }
    .logo:hover {
      transform: scale(1.05);
    }
    .deposit-card label {
      font-weight: 600;
      color: #343a40;
      margin-bottom: 8px;
      display: block;
      text-align: center;
      font-size: 1.1rem;
    }
    .deposit-card .form-control {
      border: 1px solid #ced4da;
      padding: 10px;
      border-radius: 10px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
      font-size: 0.95rem;
    }
    .deposit-card .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 5px rgba(40, 167, 69, 0.3);
      outline: none;
    }
    .deposit-card .amount-display {
      font-weight: 600;
      color: #343a40;
      text-align: center;
      margin-top: 8px;
      font-size: 1rem;
    }
    .deposit-card .btn-group {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin: 10px 0;
    }
    .deposit-card .btn-currency,
    .deposit-card .btn-network {
      background: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 8px;
      padding: 8px 16px;
      font-weight: 500;
      color: #343a40;
      transition: background 0.3s ease, border-color 0.3s ease;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .deposit-card .btn-currency.active,
    .deposit-card .btn-network.active {
      background: linear-gradient(45deg, #dc3545, #ff6f61);
      color: white;
      border-color: transparent;
    }
    .deposit-card .btn-currency:hover,
    .deposit-card .btn-network:hover {
      background: #e9ecef;
    }
    .deposit-card .btn-currency.active .icon-circle,
    .deposit-card .btn-network.active .icon-circle {
      background: rgba(255, 255, 255, 0.2);
    }
    .deposit-card .timer {
      text-align: center;
      color: #dc3545;
      font-weight: 500;
      margin: 10px 0;
      font-size: 0.9rem;
    }
    .deposit-card .payment-link {
      background: #f8f9fa;
      border: 1px solid #ced4da;
      border-radius: 8px;
      padding: 8px;
      text-align: center;
      font-size: 0.85rem;
      word-break: break-all;
      margin-bottom: 10px;
    }
    .deposit-card .action-buttons {
      display: flex;
      gap: 8px;
      justify-content: center;
    }
    .deposit-card .btn-copy,
    .deposit-card .btn-open-wallet {
      border-radius: 8px;
      padding: 8px 16px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      font-size: 0.9rem;
    }
    .deposit-card .btn-copy {
      background: linear-gradient(45deg, #343a40, #4b535b);
      color: #ffc107;
    }
    .deposit-card .btn-copy:hover {
      background: linear-gradient(45deg, #495057, #5c666f);
      color: #ffd700;
      transform: translateY(-2px);
    }
    .deposit-card .btn-open-wallet {
      background: linear-gradient(45deg, #28a745, #34c759);
      color: white;
    }
    .deposit-card .btn-open-wallet:hover {
      background: linear-gradient(45deg, #218838, #2db34a);
      transform: translateY(-2px);
    }
    .hidden {
      display: none;
    }
    .icon-circle {
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .icon {
      width: 14px;
      height: 14px;
    }
    
    .btn-next {
      background: linear-gradient(45deg, #343a40, #4b535b);
      border: none;
      padding: 12px;
      font-weight: 600;
      border-radius: 10px;
      color: #ffc107;
      width: 100%;
      margin-top: 20px;
      transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .btn-next:hover {
      background: linear-gradient(45deg, #495057, #5c666f);
      color: #ffd700;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    }
    .btn-next:active {
      transform: translateY(1px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
  </style>
</head>
<body>
    <div class="position-relative d-flex align-items-center" style="min-height:100vh;background: url({{asset('assets/img/image.jpg')}}) no-repeat center center fixed;background-size: cover;">
    <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>
    <div class="deposit-card m-auto my-5">
      <div class="logo-section">
        <img src="https://logodix.com/logo/773821.png" alt="FunSweep Logo" class="logo">
      </div>
      <form onsubmit="return false;">
        <label for="deposit-amount">Enter Amount</label>
        <input type="number" class="form-control" id="deposit-amount" placeholder="Amount in USD" min="0">
        <button type="button" class="btn btn-next" id="btn-next">Next</button>
        <div class="amount-display hidden" id="amount-display"></div>
        

        <div class="hidden" id="additional-elements">
          <label style="margin-top: 15px;">Deposit Currency</label>
          <div class="btn-group">
            <button type="button" class="btn-currency active" onclick="selectCurrency(this, 'USD')">
              <span class="icon-circle">
                
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#f88906" class="bi bi-currency-bitcoin" viewBox="0 0 16 16">
                    <path d="M5.5 13v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.5v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.084c1.992 0 3.416-1.033 3.416-2.82 0-1.502-1.007-2.323-2.186-2.44v-.088c.97-.242 1.683-.974 1.683-2.19C11.997 3.93 10.847 3 9.092 3H9V1.75a.25.25 0 0 0-.25-.25h-1a.25.25 0 0 0-.25.25V3h-.573V1.75a.25.25 0 0 0-.25-.25H5.75a.25.25 0 0 0-.25.25V3l-1.998.011a.25.25 0 0 0-.25.25v.989c0 .137.11.25.248.25l.755-.005a.75.75 0 0 1 .745.75v5.505a.75.75 0 0 1-.75.75l-.748.011a.25.25 0 0 0-.25.25v1c0 .138.112.25.25.25zm1.427-8.513h1.719c.906 0 1.438.498 1.438 1.312 0 .871-.575 1.362-1.877 1.362h-1.28zm0 4.051h1.84c1.137 0 1.756.58 1.756 1.524 0 .953-.626 1.45-2.158 1.45H6.927z"/>
                </svg>
              </span>
              BTC
            </button>
          </div>

          <label style="margin-top: 15px;">Network</label>
          <div class="btn-group">
            <button type="button" class="btn-network active" onclick="selectNetwork(this, 'Lightning')">
              <span class="icon-circle">
                <svg class="icon" viewBox="0 0 24 24" fill="#FFC107">
                  <path d="M11.726 2L5.5 12.667h5.226l-1.952 9.333L18.5 12.667h-5.226z"/>
                </svg>
              </span>
              Lightning
            </button>
            <!--<button type="button" class="btn-network" onclick="selectNetwork(this, 'Bitcoin')">-->
            <!--  <span class="icon-circle">-->
            <!--    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#f88906" class="bi bi-currency-bitcoin" viewBox="0 0 16 16">-->
            <!--        <path d="M5.5 13v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.5v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.084c1.992 0 3.416-1.033 3.416-2.82 0-1.502-1.007-2.323-2.186-2.44v-.088c.97-.242 1.683-.974 1.683-2.19C11.997 3.93 10.847 3 9.092 3H9V1.75a.25.25 0 0 0-.25-.25h-1a.25.25 0 0 0-.25.25V3h-.573V1.75a.25.25 0 0 0-.25-.25H5.75a.25.25 0 0 0-.25.25V3l-1.998.011a.25.25 0 0 0-.25.25v.989c0 .137.11.25.248.25l.755-.005a.75.75 0 0 1 .745.75v5.505a.75.75 0 0 1-.75.75l-.748.011a.25.25 0 0 0-.25.25v1c0 .138.112.25.25.25zm1.427-8.513h1.719c.906 0 1.438.498 1.438 1.312 0 .871-.575 1.362-1.877 1.362h-1.28zm0 4.051h1.84c1.137 0 1.756.58 1.756 1.524 0 .953-.626 1.45-2.158 1.45H6.927z"/>-->
            <!--    </svg>-->
            <!--  </span>-->
            <!--  Bitcoin-->
            <!--</button>-->
          </div>

          <div class="timer" id="timer">Time Left: 10:00</div>

          <input type="text" class="payment-link col-12" id="payment-link" readonly>
         

          <div class="action-buttons">
            <button type="button" class="btn-copy" onclick="copyLink()">Copy</button>
            <a href="" target="_blank" class="btn-open-wallet" id="openwallet" style="text-decoration:none;">Open Wallet</a>
          </div>
        </div>
      </form>
    </div>
    </div>
  
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const btnnext = document.getElementById('btn-next');
    const amountInput = document.getElementById('deposit-amount');
    const additionalElements = document.getElementById('additional-elements');
    const amountDisplay = document.getElementById('amount-display');
    const timerElement = document.getElementById('timer');

    btnnext.addEventListener('click', function() {
      const famount = amountInput.value;
      if (famount && famount > 0) {
        additionalElements.classList.remove('hidden');
        amountDisplay.classList.remove('hidden');
        amountDisplay.textContent = `${famount} USD`;
        startTimer();
      } else {
        additionalElements.classList.add('hidden');
        amountDisplay.classList.add('hidden');
        stopTimer();
      }
      
      var SPEED_SECRET_KEY = "{{ $speed_scret_key }}";
    var encodedKey = btoa(SPEED_SECRET_KEY);
    var settings = {
        "url": "https://api.tryspeed.com/payments",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "Content-Type": "application/json",
            "Authorization": "Basic " + encodedKey
        },
        "data": JSON.stringify({
            "currency": "USD",
            "amount": famount,
            "success_message":"Your payment request has been completed",
            "payment_methods":["lightning"]
        }),
    };
                // Show the loading indicator
    $('#loading-indicator').css('display', 'block');
    $.ajax(settings).done(function(response) {
        console.log(response);
        var checkoutUrl = response.payment_method_options['lightning']['payment_request'];
        var expires_at = response.expires_at;
        var invoiceid = response.id;
        console.log(checkoutUrl);
        $("#payment-link").val(checkoutUrl);
        $("#openwallet").attr("href","lightning:"+checkoutUrl+"")
        $.ajax({
                url: "{{ url('/merchant/store') }}", // Laravel route
                type: "POST",
                data: {
                    transaction_id: invoiceid, // Replace with dynamic data
                    user_id: '{{$userId}}',
                    amount: famount,
                    currency: "USD",
                    gateway:"Try Speed",
                    status: "Pending",
                    game_id: "{{$gameid}}",
                    // serverlist:server,
                    // gameusername:gameusername,
                    _token: "{{ csrf_token() }}" // CSRF token for security
                },
                success: function (response) {
                    console.log(response);
                    const intervalId = setInterval(() => {
                        $.ajax({
                            url: '/check-payment-status',
                            method: 'GET',
                            data: { invoice_id: invoiceid },
                            success: function(response) {
                                if (response.status === 'completed') {
                                    clearInterval(intervalId);
                                    Swal.fire({
                                        title: "Payment completed",
                                        text: "Your payment has been completed",
                                        icon: "success"
                                    }).then(() => {
                                        @if(isset($redirect_url))
                                        window.location.href = '{{$redirect_url}}';
                                        @else
                                        window.location.href = '/merchant/update_transaction/'+invoiceid+'';
                                        @endif
                                    });
                                }
                            },
                        });
                    }, 5000);
                    // $.ajax({
                    //     url: '/merchant/generate-invoice-qr',
                    //     method: 'POST',
                    //     data: {
                    //         payment_request: checkoutUrl,
                    //         expires_at: expires_at,
                    //         invoiceid:invoiceid,
                    //         user_id: '{{$userId}}'
                    //     },
                    //     success: function(invoice) {
                    //         console.log(invoice); // Debug the raw response
                    //         // window.location.href = "/merchant/deposit/invoice/"+invoice+"";
                           
                    //     },
                    //     error: function(xhr, status, error) {
                    //         console.error("QR generation failed: " + error);
                    //     }
                    // });
                },
                error: function (xhr) {
                    console.log('An error occurred: ' + xhr.responseJSON.message);
                }
        });

        
    }).fail(function(xhr, status, error) {
        console.error("Request failed with status: " + status + ", error: " + error);
        console.error("Response: " + xhr.responseText);
    });
    });

    function selectCurrency(button, currency) {
      document.querySelectorAll('.btn-currency').forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      amountDisplay.textContent = `${amountInput.value} ${currency}`;
    }

    function selectNetwork(button, network) {
      document.querySelectorAll('.btn-network').forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
    }

    function copyLink() {
      const paymentLink = document.getElementById('payment-link').value;
      navigator.clipboard.writeText(paymentLink).then(() => {
        alert('Payment link copied to clipboard!');
      });
    }

    let timerInterval;
    function startTimer() {
      let timeLeft = 600; // 10 minutes in seconds
      timerInterval = setInterval(() => {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `Time Left: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        timeLeft--;
        if (timeLeft < 0) {
          clearInterval(timerInterval);
          timerElement.textContent = 'Time Left: Expired';
          additionalElements.classList.add('hidden');
          amountInput.value = '';
          amountDisplay.classList.add('hidden');
        }
      }, 1000);
    }

    function stopTimer() {
      clearInterval(timerInterval);
      timerElement.textContent = 'Time Left: 10:00';
    }
    
    
    
  </script>
</body>
</html>