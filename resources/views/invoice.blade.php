<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<center>
    <img src="{{asset('assets/img/checkmark.svg')}}" alt="logo" style="width: 150px;">
    <h1 class="mb-4">
        <span class="text-secondary fw-light">Invoice</span>
        <br>
        Generated!
    </h1>
    <p class="text-secondary">Welcome to CumboTech, Please click on below 'Copy Now' or 'Open Wallet' button to copy the Bitcoin Transaction Invoice and pay through it.</p>
    <div class="row">
        <div class="col-12 text-center mb-3">
            <p>Invoice ID</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-center mb-3 text-wrap">
            <b><h3>{{$invoice->expires_at}}</h3></b>
        </div>
    </div>
    <img src="{{$invoice->qrcode_url}}" alt="logo" style="width: 200px;">
    <div class="row">
        <div class="col-12 text-center mb-3">
            <!--<b><h5 style="color:red;" >Please note that your transaction has yet to be completed, please click 'Open Wallet' to finalize your transaction.</h5></b>-->
            <!--<b><h5 style="color:red;" >Please note that your transaction has yet to be completed, please click 'Copy Invoice' and paste it into your crypto wallet to proceed further.</h5></b>-->
            <b><h5 style="color:red;">Your transaction is still pending. Please click 'Copy Invoice' or 'Open Wallet' to send payment via your crypto wallet and complete the process.</h5></b>
        </div>
    </div>
    <div class="col-12 text-center mb-3">
        <input class="form-control" type="text" value="{{$invoice->checkout_url}}" id="invoicelink" hidden>
        <button id="btnCopyBtcInvoice" class="btn btn-sm btn-outline-info me-2" type="button" onclick="copyToClipboard('invoicelink')">
             Copy Invoice
        </button>
        <a href="lightning:{{$invoice->checkout_url}}" class="btn btn-sm btn-outline-danger" target="_blank">
             Open Wallet
        </a>
    </div>
    <div id="aftercount" style="">You can go back to home for balance update!</div>
    <div class="col-11 col-sm-11 mt-auto mx-auto py-4">
        <div class="row ">
            <div class="col-12 d-grid">

                <a href="javascript:void(0);" onclick="javascript:goToHomeOnClose();" class="btn btn-primary btn-lg shadow-sm">Back to Home</a>

            </div>
        </div>
    </div>

</center>
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<!-- Load Bootstrap and DataTables JS files that depend on jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Load Pusher -->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script type="text/javascript">
    function copyToClipboard(elementId) {
      const copyText = document.getElementById(elementId);
      if (navigator.clipboard) {
          navigator.clipboard.writeText(copyText.value)
              .then(() => alert('Link copied to clipboard!'))
              .catch(err => console.error('Failed to copy: ', err));
      } else {
          copyText.select();
          document.execCommand("copy");
          alert("Link copied to clipboard!");
      }
  }
  const intervalId = setInterval(() => {
    $.ajax({
        url: '/check-payment-status',
        method: 'GET',
        data: { invoice_id: '{{$invoice->invoice_id}}' },
        success: function(response) {
            if (response.status === 'completed') {
                clearInterval(intervalId);
                Swal.fire({
                    title: "Payment completed",
                    text: "Your payment has been completed",
                    icon: "success"
                }).then(() => {
                    window.location.href = '/update_transaction/{{$invoice->invoice_id}}';
                });
            }
        },
    });
}, 5000);

    // ✅ Pusher Integration for Real-Time Updates
    // Pusher.logToConsole = true; // Remove this in production

    // var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    //     cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    //     encrypted: true
    // });

    // var channel = pusher.subscribe('payment-channel');

    // channel.bind('payment-success', function(data) {
    //     clearInterval(intervalId); // Stop polling since we got real-time event
    //     Swal.fire({
    //         title: "Payment completed",
    //         text: "Your payment has been successfully received!",
    //         icon: "success"
    //     }).then(() => {
    //         window.location.href = "/update_transaction/{{ $invoice->invoice_id }}";
    //     });
    // });
    
</script>

