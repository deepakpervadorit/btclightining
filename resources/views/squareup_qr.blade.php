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
    <section class="bg-light">
        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">
                <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10">
                    <div class="card border-0 shadow">
                        <div class="card-body">
                           <img src="{{ $qrCodeUrl }}" alt="QR Code">
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
@endsection