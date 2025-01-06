@extends('layouts.app')

@section('content')
    <section class="bg-light">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10">
                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <form id="payment-form">
                                <div id="card-container"></div>
                                <button id="card-button" type="button">Pay</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript" src="https://sandbox.web.squarecdn.com/v1/square.js"></script>

    <script type="text/javascript">
        const paymentForm = new window.Square.paymentForm({
            applicationId: "{{ env('SQUARE_APPLICATION_ID') }}",
            locationId: "{{ env('SQUARE_LOCATION_ID') }}",
            card: {
                elementId: 'card-container'
            }
        });

        const cardButton = document.getElementById('card-button');

        cardButton.addEventListener('click', async function() {
            const result = await paymentForm.cardTokenize();

            if (result.status === 'OK') {
                fetch('/process-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        nonce: result.token,
                        amount: 10 // Replace with the actual amount
                    })
                }).then(response => response.json())
                  .then(data => console.log(data))
                  .catch(error => console.error(error));
            } else {
                console.error('Tokenization failed');
            }
        });
    </script>
@endsection
