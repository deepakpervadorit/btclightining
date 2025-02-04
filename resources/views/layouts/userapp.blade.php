@php
    use Illuminate\Support\Facades\Route;
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Pay Cumbo Tech') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    @yield('style')

</head>
<body>
    <div id="app">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-primary">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">Cumbo</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link active" aria-current="page" href="#">Home</a>-->
                            <!--</li>-->
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link" href="#">Link</a>-->
                            <!--</li>-->
                            <!--<li class="nav-item dropdown">-->
                            <!--    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">-->
                            <!--    Dropdown-->
                            <!--    </a>-->
                            <!--    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">-->
                            <!--        <li><a class="dropdown-item" href="#">Action</a></li>-->
                            <!--        <li><a class="dropdown-item" href="#">Another action</a></li>-->
                            <!--        <li>-->
                            <!--            <hr class="dropdown-divider">-->
                            <!--        </li>-->
                            <!--        <li><a class="dropdown-item" href="#">Something else here</a></li>-->
                            <!--    </ul>-->
                            <!--</li>-->
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>-->
                            <!--</li>-->
                        </ul>
                        @guest
                            @if (Route::has('login'))
                                <a class="btn btn-warning btn-sm" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @endif
                             @if (Route::has('register'))
                                <a class="btn btn-outline-warning btn-sm ms-2" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @endguest
                    </div>
                </div>
            </nav>
        </header>

        <main>
            @yield('content')
        </main>
        <img src="{{asset('assets/img/background.png')}}" width="100%" >
        <!--<footer>-->
        <!--    <div class="container-fluid">-->
        <!--        <div class="px-lg-3">-->
        <!--            <div class="row mx-0">-->
        <!--                <div class="col">-->
        <!--                    <h4 class="fs-2"><a class="nav-link" href="{{ url('/') }}">Cumbo</a></h4>-->
        <!--                </div>-->
        <!--                <div class="col">-->
        <!--                    <h4>Hosting</h4>-->
        <!--                    <ul class="nav flex-column">-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0 active" aria-current="page" href="#">VPS Hosting</a>-->
        <!--                        </li>-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="#">Dedicated Business Hosting</a>-->
        <!--                        </li>-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="#">Prepaid Hosting</a>-->
        <!--                        </li>-->
        <!--                    </ul>-->
        <!--                </div>-->
        <!--                <div class="col">-->
        <!--                    <h4>Company</h4>-->
        <!--                    <ul class="nav flex-column">-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="#">Pricing</a>-->
        <!--                        </li>-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="#">FAQs</a>-->
        <!--                        </li>-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="#">About Us</a>-->
        <!--                        </li>-->
        <!--                    </ul>-->
        <!--                </div>-->
        <!--                <div class="col">-->
        <!--                    <h4>Get Help</h4>-->
        <!--                    <ul class="nav flex-column">-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="#">Contact Us</a>-->
        <!--                        </li>-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="#">Privacy policy</a>-->
        <!--                        </li>-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="#">T & C Apply</a>-->
        <!--                        </li>-->
        <!--                    </ul>-->
        <!--                </div>-->
        <!--                <div class="col">-->
        <!--                    <h4>Contact</h4>-->
        <!--                    <ul class="nav flex-column">-->
        <!--                        <li class="nav-item">-->
        <!--                            <a class="nav-link ps-0" href="mailto:support@cumbo.com">support@cumbo.com</a>-->
        <!--                        </li>-->
        <!--                    </ul>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</footer>-->
    </div>

    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include jQuery for simplicity -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @yield('script')
</body>
</html>
