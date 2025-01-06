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
                                <h3 class="fs-6">User's Registeration Deposit</h3>
                                <p>Please fill out the form below to deposit to a server For Registeration.</p>
                            </div>
                    
                            <div class="alert alert-warning border-0 border-start border-5 border-warning rounded-0" role="alert">
                                <h3 class="fs-6">Quick Payment Options</h3>
                                <p> 
                                    <img src="{{ asset('assets/img/apple-pay.svg') }}" height="35" class="me-1" alt="Apple Pay" />
                                    <img src="{{ asset('assets/img/cash-app.png') }}" height="40" class="me-1 py-2" alt="Cash App" />
                                    is supported through the Debit/Credit card option.
                                </p>
                            </div>
                            @php
                            $userId = Auth();
                            print_r($userId);
                            exit;
                            @endphp
                            
                            <form method="post" class="p-3" action="{{ route('deposit.link') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="server" class="form-label">Server Provider</label>
                                    <input class="form-select" id="server" name="server" value="register-deposit" readonly>
                                </div>
                    
                                <div class="mb-3">
                                    <label for="payment-method" class="form-label">Payment Method</label>
                                    <select class="form-select" id="payment-method" name="payment_method">
                                        <option value="" selected disabled>Select a payment method</option>
                                        <option value="stripe">CashApp Debit Card</option>
                                        <option value="squareup">Cashapp Qr</option>
                                        <!--<option value="squareup-qr"> CashApp</option>-->
                                    </select>
                                </div>
                    
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" required>
                                </div>
                            
                    
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="10" readonly>
                                </div>
                                
                                <div class="d-grid gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg">Deposit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
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
</script>
@endsection