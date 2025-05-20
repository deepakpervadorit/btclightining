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
    <style>
      @keyframes blinkHighlight {
        0%, 100% {
        background-color: #ffcc00;
        box-shadow: 0 0 10px #ffcc00;
        }
        50% {
        background-color: #ffe680;
        box-shadow: 0 0 20px #ffe680;
        }
      }

      .animated-highlight {
        animation: blinkHighlight 1.5s infinite;
      }
      @keyframes moveLogo {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(10px);
            }
        }

        .animated-logo img {
            animation: moveLogo 2s ease-in-out infinite;
        }

    </style>
</head>
<body>
    <div id="app">
        <header>
  <nav class="navbar">
    <a class="navbar-brand animated-logo" href="#">
        <img src="{{asset('assets/home_assets/Logo (1) (1).png')}}" height="100" style="width: 116px; max-height: 100px;">
    </a>
    <ul class="navbar-nav">
      <li><a href="#home">Home</a></li>
      <li><a href="#about-us">About</a></li>
      <li><a href="#faq">FAQ</a></li>
      <li><a href="#contact">Contact</a></li>
      <li>
      @guest
                            @if (Route::has('login'))
                                <a class="btn-login" href="{{ route('login') }}">{{ __('Login') }}</a>
                            @endif
                             @if (Route::has('register'))
                                <a class="btn btn-outline-warning btn-md ms-2" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        @endguest
                        </li>
    </ul>
  </nav>
</header>

        <main>
            @yield('content')
        </main>
        @if(Route::is('home'))
            <!--<img src="{{ asset('assets/img/background.png') }}" width="100%">-->
        @endif
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
