@extends('layouts.app')

@section('content')
    <style>
       nav.navbar.navbar-expand-lg.navbar-light.bg-primary {
    display: none;
}
footer {
    display: none;
}
nav.navbar.navbar-expand-lg.navbar-light.bg-white {
    display: none;
}
hr.my-0 {
    display: none;
}
        #loader {
            display: none;
        }
        button#card-button {
    text-align: center;
    display: block;
    width: 50%;
    margin: auto;
    margin-bottom: 20px;
}
    </style>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <section class="bg-light">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10">
                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <div class="alert alert-warning border-0 border-start border-5 border-warning rounded-0" role="alert">
                                <h3 class="fs-6">Your Deposit Link</h3>
                                @if($paymentMethod == 'stripe')
                                    <p>Click below on pay to complete your deposit:</p>
                                    <div class="d-flex mt-3">
                                        <form id="deposit-form" method="POST">
                                            @csrf
                                            <input type="hidden" name="server" id="server" value="{{ $server }}">
                                            <input type="hidden" name="paymentMethod" value="Stripe">
                                            <input type="hidden" name="username" id="username" value="{{ $username }}">
                                            <input type="hidden" name="amount" value="{{ $amount }}.09">
                                            <button type="submit" class="btn btn-warning btn-sm" id="checkout-button">Pay Now</button>
                                        </form>
                                        <a href="{{ route('show.deposit.form') }}" class="btn btn-light-warning btn-sm">New Deposit</a>
                                    </div>
                                @elseif($paymentMethod == 'squareup')
                                    <p>Click below on pay after select payment type to complete your deposit:</p>
                                    <form id="payment-form" method="POST">
                                        @csrf
                                        <input type="hidden" name="server" id="server" value="{{ $server }}">
                                        <input type="hidden" name="paymentMethod" value="Square">
                                        <input type="hidden" name="username" id="username" value="{{ $username }}">
                                        <input type="hidden" name="amount" id="amount" value="{{ $amount }}.09">
                                        <input type="hidden" id="payment-method" value="card">
                                        <!--<div class="mb-3 w-100" id="payment-type">-->
                                        <!--    <label class="form-label" for="payment-method">Payment Type</label>-->
                                        <!--    <select class="form-select" id="payment-method">-->
                                        <!--        <option value="card">Debit Card</option>-->
                                        <!--        <option value="cash-app">Cash App</option>-->
                                        <!--        <option value="apple-pay">Apple Pay</option>-->
                                        <!--    </select>-->
                                        <!--</div>-->
                                        <div class="d-flex mb-3">
                                            <button type="submit" class="btn btn-warning btn-sm" id="payment-button">Pay Now</button>
                                            <a href="{{ route('show.deposit.form') }}" class="btn btn-light-warning btn-sm">New Deposit</a>
                                        </div>
                                    </form>
                                    
                                    <div id="loader" class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div id="card-container"></div>
                                    <button type="button" class="btn btn-warning btn-sm" id="card-button" style="display: none;">Pay With Card</button>
                                    <span id="or" style="display:none; text-align:center;">OR</span>
                                    <br>
                                    <div id="cash-app-pay"></div>
                                    <div id="apple-pay-button"></div>
                                    <div id="token-display"></div>
                                @elseif($paymentMethod == 'checkbook')
    <p>Click below to proceed with your Checkbook payment:</p>
    <form id="checkbook-form" method="POST">
    @csrf
    <label class="form-label mt-3">Email</label>
    <input type="text" name="email" class="form-control mt-1">

    <div id="name-container" style="display: none; margin-bottom:15px;">
      <label class="form-label mt-3">Name</label>
      <input type="text" name="name" class="form-control mt-1">
   </div>

    <label class="form-label mt-3">Description</label>
    <textarea name="description" class="form-control mt-1 mb-3"></textarea>
    <div class="mx-3 d-flex mb-3">
    <!-- Radio Button for Mail Deposit -->
    <div class="form-check pe-3">
        <input type="radio" class="form-check-input" id="mail_deposit" name="deposit_type" value="mail_deposit">
        <label class="form-check-label" for="mail_deposit">Mail Deposit</label>
    </div>

    <!-- Radio Button for Individual Deposit -->
    <div class="form-check ps-3">
        <input type="radio" class="form-check-input" id="individual_deposit" name="deposit_type" value="individual_deposit">
        <label class="form-check-label" for="individual_deposit">Individual Deposit</label>
    </div>
</div>

<div id="user-container" style="display: none; margin-bottom:15px;">
    <div class="mb-3">
        <label class="form-label mt-3">Select User</label>
        <select name="username" id="username-select" class="form-control mt-1">
            <option value="">--Select an Option--</option>
            @php
            $user_account = DB::table('user_account')->get();
            @endphp
            @foreach($user_account as $useraccount)
                <option value="{{$useraccount->user_id}}" 
                        data-payment-method="{{$useraccount->payment_method}}"
                        data-api-id="{{$useraccount->api_id}}"
                        data-api-key="{{$useraccount->api_key}}"
                        data-api-secret="{{$useraccount->api_secret}}"
                        data-user-id="{{$useraccount->user_id}}">
                    {{$useraccount->email}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label mt-3">Deposit Option</label>
        <input type="text" class="form-control" name="deposit_option" id="deposit-option-input" readonly>
        <input type="hidden" name="api_id" id="api-id-input">
        <input type="hidden" name="api_key" id="api-key-input">
        <input type="hidden" name="api_secret" id="api-secret-input">
        <input type="hidden" name="user_id" id="user-id-input">
    </div>
</div>

<input type="hidden" name="server" id="server" value="{{ $server }}">
<input type="hidden" name="paymentMethod" value="Checkbook">
<input type="hidden" name="username" id="username" value="{{ $username }}">
<input type="hidden" name="amount" id="amount" value="{{ $amount }}.09">

    <button type="submit" class="btn btn-warning btn-sm" id="checkbook-button">Pay Now with Checkbook</button>
</form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.squareup.com/v2/paymentform"></script>
    <script src="https://web.squarecdn.com/v1/square.js"></script>
    <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
    <script type="text/javascript" src="https://web.squarecdn.com/v1/square.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the radio buttons and the user container
        const mailDepositRadio = document.getElementById('mail_deposit');
        const individualDepositRadio = document.getElementById('individual_deposit');
        const userContainer = document.getElementById('user-container');
        const usernameSelect = document.getElementById('username-select');
        
        // Function to toggle the user container
        function toggleUserContainer() {
            if (individualDepositRadio.checked) {
                userContainer.style.display = 'block';
            } else {
                userContainer.style.display = 'none';
            }
        }

        // Initial check when the page loads
        toggleUserContainer();

        // Add event listeners to the radio buttons
        mailDepositRadio.addEventListener('change', toggleUserContainer);
        individualDepositRadio.addEventListener('change', toggleUserContainer);

        // Listen for changes in the dropdown selection
        usernameSelect.addEventListener('change', function () {
            const selectedOption = usernameSelect.options[usernameSelect.selectedIndex];
            if (selectedOption.value) {
                // Populate the hidden fields with the selected option's data attributes
                document.getElementById('deposit-option-input').value = selectedOption.getAttribute('data-payment-method');
                document.getElementById('api-id-input').value = selectedOption.getAttribute('data-api-id');
                document.getElementById('api-key-input').value = selectedOption.getAttribute('data-api-key');
                document.getElementById('api-secret-input').value = selectedOption.getAttribute('data-api-secret');
                document.getElementById('user-id-input').value = selectedOption.getAttribute('data-user-id');
            } else {
                // Clear the hidden fields if no option is selected
                document.getElementById('deposit-option-input').value = '';
                document.getElementById('api-id-input').value = '';
                document.getElementById('api-key-input').value = '';
                document.getElementById('api-secret-input').value = '';
                document.getElementById('user-id-input').value = '';
            }
        });
    });
</script>

    <script>
    
    var base_url = "{{ url('/') }}";
    let card;
    

    // Function to send payment data to the server
    async function get_data(amount, payment_method, token) {
        var username = document.getElementById('username').value;
    var server = document.getElementById('server').value;
        const res = await fetch("{{ route('square.process.deposit', ['id' => $uniqueId]) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
                
                amount: amount,
                payment_method: payment_method,
                token: token,
                username: username,
                server:server,
            })
        });
        const data = await res.json();
        console.log(data);
        // Optionally handle successful payment completion
    }
    // Function to initialize payment options
    async function initializePayment(amount, payment_method) {
        try {
            document.getElementById('loader').style.display = 'block';
            document.getElementById('payment-button').style.display = 'none';

            const payments = Square.payments('sq0idp-miDyGCKt5bS4HW-AWmr2hQ', 'LHBBJPXAFGQXM');
            const paymentRequest = payments.paymentRequest({
                countryCode: 'US',
                currencyCode: 'USD',
                total: {
                    amount: amount.toFixed(2),
                    label: 'Total',
                },
            });

            const options = {
                redirectURL: window.location.href,
                referenceId: 'my-distinct-reference-id',
            };
console.log(payment_method);
            // Handling Cash App Payment
            if (payment_method === 'cash-app') {
                console.log('cashapp');

            // Handling Apple Pay Payment
            } else if (payment_method === 'apple-pay') {
                

            // Handling Card Payment
            } else if (payment_method === 'card') {
               // Initialize card globally
card = await payments.card(); 
await card.attach('#card-container'); // Attach the card input form to the container

document.getElementById('card-container').style.display = 'block';
document.getElementById('card-button').textContent = `Pay $${amount}`;
document.getElementById('card-button').style.display = 'block';

const cashAppPay = await payments.cashAppPay(paymentRequest, options);
cashAppPay.addEventListener('ontokenization', (event) => {
    const { tokenResult } = event.detail;
    if (tokenResult.status === 'OK') {
        // Get hidden input values
        const payment_method = 'Square';
        const amount = document.getElementById('amount').value;
        // Send all data to get_data function
        get_data(amount, payment_method, tokenResult.token);
    } else {
        console.error(tokenResult);
    }
});

                document.getElementById('cash-app-pay').style.display = 'block';
                await cashAppPay.attach('#cash-app-pay', { shape: 'semiround', width: 'full' });
                
                // const applePay = await payments.applePay(paymentRequest, options);
                // applePay.addEventListener('ontokenization', (event) => {
                //     const { tokenResult } = event.detail;
                //     if (tokenResult.status === 'OK') {
                //         get_data(amount, payment_method, tokenResult.token);
                //     } else {
                //         console.error(tokenResult);
                //     }
                // });
                // document.getElementById('apple-pay-button').style.display = 'block';
                // await applePay.attach('#apple-pay-button', { shape: 'semiround', width: 'full' });
            }

            document.getElementById('loader').style.display = 'none';
        } catch (error) {
            console.error('Error initializing payment:', error);
        }
    }

    // Handling Form Submit for Square Payment
    document.getElementById('payment-form').addEventListener('submit', (event) => {
        event.preventDefault();
        const amount = parseFloat(document.getElementById('amount').value);
        const payment_method = document.getElementById('payment-method').value;
        initializePayment(amount, payment_method);
    });

    // Handling Card Payment Submit Button
    document.getElementById('card-button').addEventListener('click', async (event) => {
        event.preventDefault();
        const amount = parseFloat(document.getElementById('amount').value);
        const payment_method = 'card';

        try {
            if (!card) {
                throw new Error('Card payment method not initialized'); // Ensure card is initialized
            }

            const result = await card.tokenize(); // Tokenize the card
            if (result.status === 'OK') {
                get_data(amount, payment_method, result.token); // Process the payment
                alert('Payment completed and data inserted successfully!');
                
            } else {
                console.error(result.errors);
            }
        } catch (error) {
            console.error('Error during card payment:', error);
        }
    });
</script>

<script>
    // jQuery to handle Stripe Payment Form
    $(document).ready(function() {
        $('#deposit-form').on('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting normally

            // Serialize the form data
            var formData = $(this).serialize();

            // Perform AJAX request to process the deposit via Stripe
            $.ajax({
                url: "{{ route('process.deposit', ['id' => $uniqueId]) }}", // Laravel route for processing
                method: 'POST',
                data: formData, // Send serialized form data
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is sent
                },
                success: function(response) {
                    // Redirect to Stripe's checkout page
                    if (response.url) {
                        window.location.href = response.url;
                    } else {
                        alert('Failed to create Stripe session');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('There was an error processing the payment. Please try again.');
                }
            });
        });
    });
    $(document).ready(function() {
    $('#checkbook-form').on('submit', function(event) {
    event.preventDefault(); // Prevent the form from submitting normally

    // Serialize the form data
    var formData = $(this).serialize();

    // Perform AJAX request to process the deposit via Checkbook
    $.ajax({
        url: "{{ route('process.checkbook', ['id' => $uniqueId]) }}", // Define a new route for Checkbook processing
        method: 'POST',
        data: formData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is sent
        },
        success: function(response) {
            console.log(response);
            if (response) {
    // Display a success message using toastr
    toastr.success('Payment is successful! Check has been sent to the user\'s email.');

    // Redirect to the deposit page
    window.location.href = 'https://pay.cumbo.tech/deposit';
} else {
    // If the response is not success, display an error toast
    toastr.error('An error occurred while processing the payment. Please try again.', 'Error');
}
        },
        error: function(xhr, status, error) {
            // Log the error for debugging
            console.error('Error:', error);

            // Show error toast for client-side AJAX request failure
            toastr.error('There was an issue with the request. Please try again later.', 'Payment Failed');
        }
    });
});

});

</script>

@endsection