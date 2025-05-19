@extends('layouts.userapp')

@section('content')
<style>
    nav.navbar.navbar-expand-lg.navbar-light.bg-primary {
    display: none;
}
footer {
    display: none;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <section class="bg-light py-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </div>
                    @endif

                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <div class="alert alert-info border-0 border-start border-5 border-info rounded-0" role="alert">
                                <h3 class="fs-6">Deposit</h3>
                                <p>Please fill out the form below to deposit to a server.</p>
                                <ol>
                                    <li>Select a server provider from the dropdown.</li>
                                    <li>Enter your username for the server provider.</li>
                                    <li>Enter the amount you would like to load.</li>
                                </ol>
                            </div>

                            <div class="alert alert-warning border-0 border-start border-5 border-warning rounded-0" role="alert">
                                <h3 class="fs-6">Quick Payment Options</h3>
                                <p>
                                    <!--<img src="{{ asset('public/assets/img/apple-pay.svg') }}" height="35" class="me-1" alt="Apple Pay" />-->
                                    <img src="{{ asset('public/assets/img/cash-app.png') }}" height="40" class="me-1 py-2" alt="Cash App" />
                                    is supported through the Debit/Credit card option.
                                </p>
                            </div>
                            <div class="mb-3">
                                    <label for="server" class="form-label">Server Provider</label>
                                    <select class="form-select" id="server" name="server" required>
                                        <option value="" selected disabled hidden>Select a server provider</option>
                                        
                                        @foreach($games as $key => $game)
                                        @if(in_array($game->id, $merchant_game))
                                            <option value="{{ $game->id }}">{{ $game->game }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label mt-3">Email/Phone</label>
                                <input type="text" name="email" class="form-control mt-1" value="{{session('staff_email')}}" id="email" readonly>
                                </div>
                            <div class="mb-3">
                                    <label for="username" class="form-label">Game Username</label>
                                    <input type="text" class="form-control" id="gameusername" name="gameusername" value="">
                                    <input type="hidden" class="form-control" id="username" name="username" value="{{session('staff_name')}}">
                            </div>
                            <div class="mb-3">
                            <label for="payment-method" class="form-label">Select Payment Method:</label>
                            <select id="payment-method" class="form-select">
                                <option value="">Choose...</option>
                                @if ($merchant_details && strpos($merchant_details->gateways, 'tryspeed') !== false)
                                <option value="tryspeed">Cashapp Crypto</option>
                                @endif
                                @if ($merchant_details && strpos($merchant_details->gateways, 'fortunefinex') !== false)
                                <option value="visa">Visa</option>
                                <option value="mastercard">Mastercard</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3" id="tryspeedamount" style="display:none;">
                            <label for="payment-method" class="form-label">Amount (USD):</label>
                            <input class="form-control" type="text" value="" id="tryspeedamountInput"/>
                            </div>
                        <a href="javascript:void(0);" id="tryspeedbtn" class="btn btn-primary" style="display:none;">Create Invoice</a>
                        <form id="visa-form" class="hidden" method="POST" action="{{ env('URL') }}" style="display:none">
                            @php
                                $merchantTransactionId = bin2hex(random_bytes(6));
                            @endphp
                            <input type="hidden" name="user_id" value="{{session('staff_id')}}">
                            <input type="hidden" name="memberId" value="{{ env('MEMBER_ID') }}" />
                            <input type="hidden" name="language" value="{{ env('LANGUAGE') }}" />
                            <input type="hidden" name="checksum" value="" />
                            <input type="hidden" name="totype" value="{{ env('TOTYPE') }}" />
                            <input type="hidden" name="merchantTransactionId" value="{{ $merchantTransactionId }}" />
                            <input type="hidden" name="orderDescription" value="Test Transaction" />
                            <input type="hidden" name="currency" value="EUR" />
                            <input type="hidden" name="merchantRedirectUrl" value="{{ env('MERCHANT_REDIRECT_URL') }}" />
                            <input type="hidden" name="notificationUrl" value="{{ env('NOTIFICATION_URL') }}" />
                            <div class="mb-3">
                            <label for="payment-method" class="form-label">Amount (EUR):</label>
                            <input class="form-control" type="text" name="amount" value="" id="vamountInput"/>
                            </div>
                            <input type="hidden" id="vsecret-key" name="vsecret-key" value="{{ env('SECRET_KEY') }}">
                                <button type="submit" id="visabtn" class="btn btn-primary">Submit</button>

                        </form>

                        <!-- Mastercard Form -->
                        <form id="mastercard-form" style="display:none" method="POST" action="{{ env('URL') }}">
                            @php
                                $merchantTransactionId = bin2hex(random_bytes(6));
                            @endphp
                            <input type="hidden" name="user_id" value="{{session('staff_id')}}">
                            <input type="hidden" name="memberId" value="{{ env('MEMBER_ID') }}" />
                            <input type="hidden" name="language" value="{{ env('LANGUAGE') }}" />
                            <input type="hidden" name="checksum" value="" />
                            <input type="hidden" name="totype" value="{{ env('TOTYPE') }}" />
                            <input type="hidden" name="merchantTransactionId" value="{{ $merchantTransactionId }}" />
                            <input type="hidden" name="orderDescription" value="Test Transaction" />
                            <input type="hidden" name="currency" value="USD" />
                            <input type="hidden" name="merchantRedirectUrl" value="{{ env('MERCHANT_REDIRECT_URL') }}" />
                            <input type="hidden" name="notificationUrl" value="{{ env('NOTIFICATION_URL') }}" />
                            {{--<input type="hidden" name="terminalid" value="{{ env('TERMINAL_ID') }}" />
                            <input type="hidden" name="paymentMode" value="{{ env('PAYMENT_MODE') }}" />
                            <input type="hidden" name="paymentBrand" value="{{ env('PAYMENT_BRAND') }}" /> --}}
                            <div class="mb-3">
                            <label for="payment-method" class="form-label">Amount (EUR):</label>
                            <input class="form-control" type="text" name="amount" value="" id="mamountInput"/>
                            </div>
                            <input type="hidden" id="msecret-key" name="msecret-key" value="{{ env('SECRET_KEY') }}">
                            <button type="submit" id="mastercardbtn" class="btn btn-primary">Submit</button>

                        </form>
                        <div id="loading-indicator" class="text-center" style="display: none;">Loading...</div>
                        <img id="payment-qr" alt="Payment QR Code" style="display: none;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    var $j = jQuery.noConflict();
</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>


<script>
    $j(document).ready(function() {
    $j('.js-example-basic-single').select2();
});
</script>
    <script>
    document.getElementById('vamountInput').addEventListener('blur', function() {
    // Get the input value
    let value = parseFloat(this.value);

    // If the value is a number and not NaN
    if (!isNaN(value)) {
        // Format the number to always show 2 decimal places
        this.value = value.toFixed(2);
        $('#vamountInput').val(this.value);
    }
});

// Format value to two decimal places as the user types
document.getElementById('vamountInput').addEventListener('input', function() {
    // Prevent invalid input by allowing only numeric input
    let value = this.value.replace(/[^0-9.]/g, ''); // Allow numbers and decimal point

    // If there's a valid numeric value, format it
    if (value && !isNaN(value)) {
        // Parse and reformat to 2 decimal places
        this.value = parseFloat(value).toFixed(2);

        // Set the value using jQuery
        $('#vamountInput').val(this.value);

        // Log the value to check if it's set correctly
        console.log('Input Value: ', this.value);  // Log the value of 'this.value'
        console.log('jQuery Value: ', $('#vamountInput').val());  // Log the value using jQuery

    }
});

document.getElementById('mamountInput').addEventListener('blur', function() {
    // Get the input value
    let value = parseFloat(this.value);

    // If the value is a number and not NaN
    if (!isNaN(value)) {
        // Format the number to always show 2 decimal places
        this.value = value.toFixed(2);
        $('#mamountInput').val(this.value);
    }
});

// Format value to two decimal places as the user types
document.getElementById('mamountInput').addEventListener('input', function() {
    // Prevent invalid input by allowing only numeric input
    let value = this.value.replace(/[^0-9.]/g, ''); // Allow numbers and decimal point

    // If there's a valid numeric value, format it
    if (value && !isNaN(value)) {
        // Parse and reformat to 2 decimal places
        this.value = parseFloat(value).toFixed(2);

        // Set the value using jQuery
        $('#mamountInput').val(this.value);

        // Log the value to check if it's set correctly
        console.log('Input Value: ', this.value);  // Log the value of 'this.value'
        console.log('jQuery Value: ', $('#mamountInput').val());  // Log the value using jQuery

    }
});
    // Function to shuffle options
    function shuffleOptions(selectElement) {
        const options = Array.from(selectElement.options); // Convert the options to an array
        const firstOption = options.shift(); // Remove the first option (default one)

        // Shuffle the rest of the options
        for (let i = options.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [options[i], options[j]] = [options[j], options[i]]; // Swap the elements
        }

        // Clear the select element and add the shuffled options
        selectElement.innerHTML = '';
        selectElement.appendChild(firstOption); // Re-add the first default option
        options.forEach(option => selectElement.appendChild(option)); // Add the shuffled options
    }

    // Shuffle the options when the page loads
    window.onload = function() {
        const paymentMethodSelect = document.getElementById('payment-method');
        shuffleOptions(paymentMethodSelect);
    };
     function md5(string) {
    return CryptoJS.MD5(string).toString(CryptoJS.enc.Hex);
}
</script>
<script>
    $("#mastercard-form").on("submit", function(e){
        e.preventDefault();
        const server = $("#server").val();
        const gameusername = $("gameusername").val();
        const visaForm = document.getElementById('visa-form');
        const mastercardForm = document.getElementById('mastercard-form');
        const visaAmount = mastercardForm.querySelector('input[name="amount"]').value;
        const merchantTransactionId = mastercardForm.querySelector('input[name="merchantTransactionId"]').value;
        const secretKey = mastercardForm.querySelector('input[name="msecret-key"]').value;
        const memberId = mastercardForm.querySelector('input[name="memberId"]').value;
        const user_id = visaForm.querySelector('input[name="user_id"]').value;
        const totype = mastercardForm.querySelector('input[name="totype"]').value;
        const famount = visaAmount;
        const currency = mastercardForm.querySelector('input[name="currency"]').value;
        const merchantRedirectUrl = mastercardForm.querySelector('input[name="merchantRedirectUrl"]').value;
        const checksumString = `${memberId}|${totype}|${famount}|${merchantTransactionId}|${merchantRedirectUrl}|${secretKey}`;
        const md5checksumstring = md5(checksumString);
        mastercardForm.querySelector('input[name="checksum"]').value = md5checksumstring;
        console.log(md5checksumstring);
        $.ajax({
        url: "{{ url('/store') }}", // Laravel route
        type: "POST",
        data: {
            transaction_id: merchantTransactionId, // Replace with dynamic data
            user_id: '{{$userId}}',
            amount: famount,
            currency: currency,
            gateway:"Fortune Finex",
            status: "Pending",
            serverlist:server,
            gameusername:gameusername,
            _token: "{{ csrf_token() }}" // CSRF token for security
        },
        success: function (response) {
            console.log(response.message); // Success message
            $("#mastercard-form").submit();
        },
        error: function (xhr) {
            console.log('An error occurred: ' + xhr.responseJSON.message);
        }
        });
    });
    $("#visa-form").on("submit", function (e) {
        e.preventDefault();
        const server = $("#server").val();
        const gameusername = $("gameusername").val();
        var paymentMethodSelect = $('payment-method').val();
        const visaForm = document.getElementById('visa-form');

        const visaAmount = visaForm.querySelector('input[name="amount"]').value;
        const merchantTransactionId = visaForm.querySelector('input[name="merchantTransactionId"]').value;
        const secretKey = visaForm.querySelector('input[name="vsecret-key"]').value;
        const user_id = visaForm.querySelector('input[name="user_id"]').value;
        const memberId = visaForm.querySelector('input[name="memberId"]').value;
        const totype = visaForm.querySelector('input[name="totype"]').value;
        const merchantRedirectUrl = visaForm.querySelector('input[name="merchantRedirectUrl"]').value;
        const famount = visaAmount;
        const currency = visaForm.querySelector('input[name="currency"]').value;
        const checksumString = `${memberId}|${totype}|${famount}|${merchantTransactionId}|${merchantRedirectUrl}|${secretKey}`;
        const checksumInputInVisaForm = visaForm.querySelector('input[name="checksum"]').value;
        const md5checksumstring = md5(checksumString);
        visaForm.querySelector('input[name="checksum"]').value = md5checksumstring;
                console.log(md5checksumstring);
                $.ajax({
                url: "{{ url('/store') }}", // Laravel route
                type: "POST",
                data: {
                    transaction_id: merchantTransactionId, // Replace with dynamic data
                    user_id: '{{$userId}}',
                    amount: famount,
                    currency: currency,
                    gateway:"Fortune Finex",
                    status: "Pending",
                    serverlist:server,
                    gameusername:gameusername,
                    _token: "{{ csrf_token() }}" // CSRF token for security
                },
                success: function (response) {
                    console.log(response.message); // Success message
                    $("#visa-form").submit();
                },
                error: function (xhr) {
                    console.log('An error occurred: ' + xhr.responseJSON.message);
                }
        });
    });

$("#tryspeedbtn").on('click',function(e){
    // Get the input values
    var server = $("#server").val();
    var gameusername = $("#gameusername").val();
    var famount = $('#tryspeedamountInput').val();

    // Validate the inputs
    var isValid = true;

    if (!server) {
        isValid = false;
        $("#server").addClass('is-invalid'); // Add Bootstrap's invalid class
        $("#server-error").text("Server is required.").show(); // Show error message
    } else {
        $("#server").removeClass('is-invalid');
        $("#server-error").hide(); // Hide error message if valid
    }

    if (!gameusername) {
        isValid = false;
        $("#gameusername").addClass('is-invalid');
        $("#gameusername-error").text("Game username is required.").show();
    } else {
        $("#gameusername").removeClass('is-invalid');
        $("#gameusername-error").hide();
    }

    if (!famount) {
        isValid = false;
        $("#tryspeedamountInput").addClass('is-invalid');
        $("#famount-error").text("Amount is required.").show();
    } else {
        $("#tryspeedamountInput").removeClass('is-invalid');
        $("#famount-error").hide();
    }

    // If any input is invalid, stop further execution
    if (!isValid) {
        return;
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
        $.ajax({
                url: "{{ url('/store') }}", // Laravel route
                type: "POST",
                data: {
                    transaction_id: invoiceid, // Replace with dynamic data
                    user_id: '{{$userId}}',
                    amount: famount,
                    currency: "USD",
                    gateway:"Try Speed",
                    status: "Pending",
                    serverlist:server,
                    gameusername:gameusername,
                    _token: "{{ csrf_token() }}" // CSRF token for security
                },
                success: function (response) {
                    console.log(response);
                    $.ajax({
                        url: '/generate-invoice-qr',
                        method: 'POST',
                        data: {
                            payment_request: checkoutUrl,
                            expires_at: expires_at,
                            invoiceid:invoiceid
                        },
                        success: function(invoice) {
                            console.log(invoice); // Debug the raw response
                            window.location.href = "/deposit/invoice/"+invoice+"";
                            // Ensure it's a string before calling `replace`
                            // if (typeof qrCodeUrl === "string") {
                            //     qrCodeUrl = qrCodeUrl.replace(/^<\?xml[^>]*\?>/, '');
                            //     var svgDataUrl = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(qrCodeUrl);
                            //     $('#loading-indicator').css('display', 'none');
                            //     // $('#payment-qr').css('display', 'block');
                            //     // $('#payment-qr').attr('src', svgDataUrl);
                            //     var form = document.createElement('form');
                            //     form.method = 'POST';
                            //     form.action = "";
            
                            //     // Create a hidden input to hold the svgDataUrl
                            //     var input = document.createElement('input');
                            //     input.type = 'hidden';
                            //     input.name = 'svgDataUrl';
                            //     input.value = svgDataUrl;
                            //     form.appendChild(input);
            
                            //     var input2 = document.createElement('input');
                            //     input2.type = 'hidden';
                            //     input2.name = 'checkoutUrl';
                            //     input2.value = checkoutUrl;
                            //     form.appendChild(input2);
            
                            //     var input3 = document.createElement('input');
                            //     input3.type = 'hidden';
                            //     input3.name = 'id';
                            //     input3.value = id;
                            //     form.appendChild(input3);
            
                            //     var input4 = document.createElement('input');
                            //     input4.type = 'hidden';
                            //     input4.name = 'invoiceid';
                            //     input4.value = invoiceid;
                            //     form.appendChild(input4);
            
                            //     var inputCsrf = document.createElement('input');
                            //     inputCsrf.type = 'hidden';
                            //     inputCsrf.name = '_token';
                            //     inputCsrf.value = '{{ csrf_token() }}'; // Laravel's CSRF token helper
                            //     form.appendChild(inputCsrf);
            
                            //     // Append the form to the body and submit it
                            //     document.body.appendChild(form);
                            //     form.submit();
                            // } else {
                            //     console.error("Invalid response type. Expected a string.");
                            // }
                        },
                        error: function(xhr, status, error) {
                            console.error("QR generation failed: " + error);
                        }
                    });
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
     // Prevent default button behavior
    // Toggle form visibility based on payment method selection
    document.getElementById('payment-method').addEventListener('change', function () {
        const visaForm = document.getElementById('visa-form');
        const mastercardForm = document.getElementById('mastercard-form');

        if(this.value === 'tryspeed') {
            $('#tryspeedbtn').css('display', 'block');
            $('#tryspeedamount').css('display', 'block');
            visaForm.style.display = 'none';
            mastercardForm.style.display = 'none';

        }

        // Show the selected form
        if (this.value === 'visa') {
            console.log('visa');
        visaForm.style.display = 'block';
        mastercardForm.style.display = 'none';
        $('#tryspeedbtn').css('display', 'none');
        $('#tryspeedamount').css('display', 'none');

        } else if (this.value === 'mastercard') {
        console.log('mc');
        mastercardForm.style.display = 'block';
        visaForm.style.display = 'none';
        $('#tryspeedbtn').css('display', 'none');
        $('#tryspeedamount').css('display', 'none');

        }
    });
</script>
<script>
$(document).ready(function () {
    $("#submit-button").on("click", function (e) {

        const Gateway = document.getElementById('method_code');
        const selectedGateway = Gateway.options[Gateway.selectedIndex].text;
        const subGateway = document.getElementById('sub-gateway-select');
        const subselectedGateway = subGateway.options[subGateway.selectedIndex].text;
        if (selectedGateway != 'Paypal') {
            if(subselectedGateway === "Visa")
            {
                e.preventDefault(); // Prevent default button behavior

                // Serialize form data
                var formData = {
                    user_id: $("#visa-form input[name='user_id']").val(),
                    memberId: $("#visa-form input[name='memberId']").val(),
                    language: $("#visa-form input[name='language']").val(),
                    checksum: $("#visa-form input[name='checksum']").val(),
                    totype: $("#visa-form input[name='totype']").val(),
                    merchantTransactionId: $("#visa-form input[name='merchantTransactionId']").val(),
                    amount: $("#visa-form input[name='amount']").val() + ".00",
                    orderDescription: $("#visa-form input[name='orderDescription']").val(),
                    merchantRedirectUrl: $("#visa-form input[name='merchantRedirectUrl']").val(),
                    currency: $("#visa-form input[name='currency']").val(),
                };
            }
            else if(subselectedGateway === "MasterCard")
            {
                e.preventDefault(); // Prevent default button behavior

                // Serialize form data
                var formData = {
                    user_id: $("#visa-form input[name='user_id']").val(),
                    memberId: $("#mastercard-form input[name='memberId']").val(),
                    language: $("#mastercard-form input[name='language']").val(),
                    checksum: $("#mastercard-form input[name='checksum']").val(),
                    totype: $("#mastercard-form input[name='totype']").val(),
                    merchantTransactionId: $("#mastercard-form input[name='merchantTransactionId']").val(),
                    amount: $("#mastercard-form input[name='amount']").val() + ".00",
                    orderDescription: $("#mastercard-form input[name='orderDescription']").val(),
                    merchantRedirectUrl: $("#mastercard-form input[name='merchantRedirectUrl']").val(),
                    currency: $("#mastercard-form input[name='currency']").val(),
                };
            }

            $.ajax({
                url: "{{ url('/store') }}", // Laravel route
                type: "POST",
                data: {
                    transaction_id: formData.merchantTransactionId, // Replace with dynamic data
                    user_id: '{{$userId}}',
                    status: "Pending",
                    _token: "{{ csrf_token() }}" // CSRF token for security
                },
                success: function (response) {
                    console.log(response.message); // Success message
                },
                error: function (xhr) {
                    console.log('An error occurred: ' + xhr.responseJSON.message);
                }
        });

        console.log('formData',formData);



            if (subselectedGateway === 'Visa') {
        const mainForm = document.getElementById('form');

        disableForm("main-form");
        // Submit Visa form
        document.getElementById('visa-form').submit();
        } else if (subselectedGateway === 'MasterCard') {
            const mainForm = document.getElementById('form');
            disableForm("main-form");
            // Submit MasterCard form
            document.getElementById('mastercard-form').submit();
        } else {
            alert('Please select a valid gateway.');
        }

        // Send AJAX request
        //  $.ajax({
        //             url: "https://sandbox.fortunefinex.com/transaction/Checkout", // Target URL
        //             type: "POST",
        //             data: formData,
        //             success: function (response) {
        //                 console.log("Response:", response);

        //                 // Redirect after successful response
        //                 // If the server provides a redirect URL, use it
        //                 var redirectUrl = "https://sandbox.fortunefinex.com/transaction/Checkout";
        //                 redirectWithForm(formData, redirectUrl);
        //             },
        //             error: function (xhr, status, error) {
        //                 console.error("Error:", xhr.responseText);
        //                 alert("An error occurred while processing your request.");
        //             }
        //         });

                    }
                    else if(selectedGateway === 'Paypal'){
                        console.log('the selected gateway is Paypal');
                        document.getElementById('main-form').submit();


                    }
    });
});
        'use strict';
        (function($) {

            var wallet = null;

            $('#wallet').on('change', function() {

                if ($(this).find('option:selected').val() == '') {
                    return false
                }

                wallet  =  $(this);

                var gateways = $(this).find('option:selected').data('gateways');
                var sym = $(this).find('option:selected').data('sym')
                var code = $(this).find('option:selected').data('code')
                var rate = $(this).find('option:selected').data('rate')

                $('.curr_code').text(code)
                $('.sym').text(sym)
                $('input[name=currency]').val(code)
                $('input[name=currency_id]').val($(this).find('option:selected').data('currency'))

                $('.gateway').removeAttr('disabled')
                $('.gateway').children().remove()
                var html = `<option value="">@lang('Select Gateway')</option>`;

                if (gateways.length > 0) {
                    console.log('gateways',gateways)
                    $.each(gateways, function(i, val) {
                        console.log('val is ',val)
                        html +=
                            ` <option data-max="${val.max_amount}" data-min="${val.min_amount}" data-fixcharge = "${val.fixed_charge}" data-percent="${val.percent_charge}" data-rate="${rate}" value="${val.method_code}">${val.name}</option>`
                    });
                    // html += `
                    //     <option data-max="10000" data-min="100" data-fixcharge="5" data-percent="1.5" data-rate="1.0" value="999">
                    //         Visa
                    //     </option>
                    //     <option data-max="20000" data-min="500" data-fixcharge="10" data-percent="2.0" data-rate="1.0" value="998">
                    //     MasterCard
                    //     </option>
                    // `;
                    $('.gateway').append(html)
                    $('.gateway-msg').text('')

                } else {
                    $('.gateway').attr('disabled', true)
                    $('.gateway').append(html)
                    $('.gateway-msg').text('No gateway found with this currency.')
                }

            })

            $('.gateway').on('change', function() {

                if ($('.gateway option:selected').val() == '') {
                    $('.amount').attr('disabled', true)
                    $('.charge').text('0.00')
                    $('.payable').text(parseFloat($('.amount').val()))
                    $('.limit').text('limit : 0.00 USD')
                    return false
                }

                $('.amount').removeAttr('disabled')
                var amount = $('.amount').val() ? parseFloat($('.amount').val()) : 0;
                var code = $(wallet).find('option:selected').data('code')

                var type = $(wallet).find('option:selected').data('type')

                var rate = parseFloat($('.gateway option:selected').data('rate'))
                var min = parseFloat($('.gateway option:selected').data('min'))
                var max = parseFloat($('.gateway option:selected').data('max'))

                min = min/rate;
                max = max/rate;

                var fixed = parseFloat($('.gateway option:selected').data('fixcharge'))
                var pCharge = parseFloat($('.gateway option:selected').data('percent'))
                var percent = (amount * parseFloat($('.gateway option:selected').data('percent'))) / 100

                var totalCharge = fixed + percent
                var totalAmount = amount + totalCharge
                var precesion = 0;

                if (type == 1) {
                    precesion = 2;
                } else {
                    precesion = 8;
                }

                $('.charge').text(totalCharge.toFixed(precesion))
                $('.payable').text(totalAmount.toFixed(precesion))
                $('.limit').text('limit : ' + min.toFixed(precesion) + ' ~ ' + max.toFixed(precesion) + ' ' + code)

                $('.f_charge').text(fixed)
                $('.p_charge').text(pCharge)

            })

            $('.amount').on('keyup', function() {
                var amount = parseFloat($(this).val())

                var type = $(wallet).find('option:selected').data('type')
                var code = $(wallet).find('option:selected').data('code')
                var fixed = parseFloat($('.gateway option:selected').data('fixcharge'))

                var percent = (amount * parseFloat($('.gateway option:selected').data('percent'))) / 100
                var totalCharge = fixed + percent
                var totalAmount = amount + totalCharge
                var precesion = 0;

                if (type == 1) {
                    precesion = 2;
                } else {
                    precesion = 8;
                }

                if (!isNaN(amount)) {
                    $('.show-amount').text(amount.toFixed(precesion))
                    $('.charge').text(totalCharge.toFixed(precesion))
                    $('.payable').text(totalAmount.toFixed(precesion))
                } else {
                    $('.show-amount').text('0.00')
                    $('.charge').text('0.00')
                    $('.payable').text('0.00')

                }

                const amountField = $('.payable').text();
                const subGateway = document.getElementById('sub-gateway-select');
                const subselectedGateway = subGateway.options[subGateway.selectedIndex].text;
                if(subselectedGateway === "Visa")
                {
                    var visaForm = document.querySelector('#visa-form');
                    var amountInputInVisaForm = visaForm.querySelector('input[name="amount"]');
                    var checksumInputInVisaForm = visaForm.querySelector('input[name="checksum"]');
                    var merchantTransactionIdInputInVisaForm = visaForm.querySelector('input[name="merchantTransactionId"]');
                    var secretKey = document.querySelector('#vsecret-key').value;
                }
                else if(subselectedGateway === "MasterCard")
                {
                    var masterForm = document.querySelector('#mastercard-form');
                    var amountInputInVisaForm = masterForm.querySelector('input[name="amount"]');
                    var checksumInputInVisaForm = masterForm.querySelector('input[name="checksum"]');
                    var merchantTransactionIdInputInVisaForm = masterForm.querySelector('input[name="merchantTransactionId"]');
                    var secretKey = document.querySelector('#msecret-key').value;
                }
                let famount = parseFloat(amountField);
                if (!isNaN(famount)) {
                    famount = famount.toFixed(2); // Format amount to two decimal places
                } else {
                    famount = '0.00'; // In case of invalid input, set to 0.00
                }

                // Update the amount field in the Visa form
                amountInputInVisaForm.value = famount;
                // Get the merchantTransactionId value already set by the server
                const merchantTransactionId = merchantTransactionIdInputInVisaForm.value;

                // Prepare the checksum string dynamically
                const checksumString = `${document.querySelector('input[name="memberId"]').value}|${document.querySelector('input[name="totype"]').value}|${famount}|${merchantTransactionId}|${document.querySelector('input[name="merchantRedirectUrl"]').value}|${secretKey}`;

                // Update the checksum field in the Visa form
                checksumInputInVisaForm.value = md5(checksumString);
                console.log(md5(checksumString));
            })

            $('.req_confirm').on('click', function() {
                if ($('.amount').val() == '' || $('.gateway option:selected').val() == '' || $(wallet).find('option:selected').val() == '') {
                    notify('error', 'All fields are required')
                    return false
                }
                $('#form').submit()
                $(this).attr('disabled', true)
            })

        })(jQuery);

        document.getElementById('gateway-select').addEventListener('change', function () {
    const selectedGateway = this.options[this.selectedIndex].text;
    const subGateway = document.getElementById('fortunefinix_gateway');
    console.log(selectedGateway);
    if(selectedGateway === 'Fortune Finex (EUR)' || selectedGateway === 'Fortune Finex (USD)'){
            subGateway.style.display = "block";
            console.log('selectedGateway selected'+selectedGateway+ 'with subgateway'+subGateway);
    }
    if (selectedGateway === 'Visa') {

        const mainForm = document.getElementById('form');
        disableForm("main-form");
        // Submit Visa form
        // document.getElementById('visa-form').submit();
    } else if (selectedGateway === 'MasterCard') {
        const mainForm = document.getElementById('form');
        disableForm("main-form");
        // Submit MasterCard form
        // document.getElementById('mastercard-form').submit();
    }
});
// document.addEventListener("DOMContentLoaded", function () {


//     // if (sourceInput && hiddenInput) {
//         // "change" event listener
//         const sourceInput = document.querySelector(".amount");

//         sourceInput.addEventListener("change", function () {
//         const Gateway = document.getElementById('fortunefinix_gateway');
//         const selectedGateway = Gateway.options[Gateway.selectedIndex].text;
//         console.log("selectedGateway",selectedGateway)


//         let hiddenInput;
//             if (selectedGateway === 'Visa') {
//             hiddenInput = document.getElementsByClassName("amount")[0];
//             }
//             if (selectedGateway === 'MasterCard') {
//             hiddenInput = document.getElementsByClassName("amount")[0];
//             }

//             console.log("selectedGateway",selectedGateway)

//             console.log('onchange triggered, value:', this.value);
//             hiddenInput.value = this.value; // Update hidden input
//             // document.getElementById("hiddenValueDisplay").textContent = this.value;
//         });
//     // } else {
//     //     console.error("One or more elements not found in the DOM.");
//     // }
// });
function disableForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevents the form from being submitted
            console.warn(`Form with id "${formId}" submission is disabled.`);
        });
    } else {
        console.warn(`Form with id "${formId}" not found.`);
    }
}

    function updateVisaAmount(input) {
        const Gateway = document.getElementById('sub-gateway-select');
        const selectedGateway = Gateway.options[Gateway.selectedIndex].text;
      // Get the value from the 'amount' input
      if(selectedGateway == 'Visa'){
          const amountValue = parseFloat(input.value).toFixed(2);
          let baseAmount = {!! json_encode($amount ?? '') !!};
          console.log('Base Amount:', baseAmount);

      // Find the 'visaamount' input and set its value
      const visaAmountInput = document.querySelector(".VisaAmount");
    //   console.log(visaAmountInput);
      visaAmountInput.value = amountValue;
      console.log('visaAmountInput has been updated')
    //   document.getElementById('amountForm').submit();
      } else if(selectedGateway == 'MasterCard') {
          const amountValue = parseFloat(input.value).toFixed(2);
          let baseAmount = {!! json_encode($amount ?? '') !!};
          console.log('Base Amount:', baseAmount);


      // Find the 'visaamount' input and set its value
      const visaAmountInput = document.querySelector(".MasterAmount");
      visaAmountInput.value = amountValue;
      console.log('MasterAmountinput has been updated');

    //   document.getElementById('amountForm').submit();
      }
      else{
          console.log('no fortunefinix gatways selected');
      }
    }

    function md5(string) {
    return CryptoJS.MD5(string).toString(CryptoJS.enc.Hex);
}
  </script>
@endsection
