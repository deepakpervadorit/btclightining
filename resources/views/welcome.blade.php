@extends('layouts.userapp')

@section('content')
    <!--<section id="hero">-->
    <!--    <div class="contianer">-->
    <!--        <h1 class="text-center pt-3 pb-5">Secure business Cloud Hosting</h1>-->
    <!--        <img src="{{ asset('public/assets/img/banner.png') }}" class="img-fluid">-->
    <!--    </div>-->
    <!--</section>-->
    <!--<section style="margin-top: -75px;">-->
    <!--    <div class="container-fluid">-->
    <!--        <div class="card bg-light border-0">-->
    <!--            <div class="card-body">-->
    <!--                <div class="row mx-0 py-3">-->
    <!--                    <div class="col-md-4">-->
    <!--                        <h2>Benefits of Cloud Hosting</h2>-->
    <!--                        <p class="text-muted">Are you ready to take your online presence to the next level?</p>-->
    <!--                        <p class="text-muted">Benefit from dedicated resources, enhanced security, and expand resources as you grow.</p>-->
    <!--                        <p class="text-muted">It’s straightforward, efficient hosting tailored for you.</p>-->
    <!--                    </div>-->
    <!--                    <div class="col-md-4">-->
    <!--                        <div class="d-flex flex-column justify-content-evenly h-100">-->
    <!--                            <div class="d-flex align-items-center">-->
    <!--                                <div class="flex-shrink-0">-->
    <!--                                    <img src="{{ asset('public/assets/img/Tick-Square.svg') }}" alt="...">-->
    <!--                                </div>-->
    <!--                                <div class="flex-grow-1 ms-3">-->
    <!--                                    <strong class="fs-5">Simple and Reliable</strong>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                            <div class="d-flex align-items-center">-->
    <!--                                <div class="flex-shrink-0">-->
    <!--                                    <img src="{{ asset('public/assets/img/Tick-Square.svg') }}" alt="...">-->
    <!--                                </div>-->
    <!--                                <div class="flex-grow-1 ms-3">-->
    <!--                                    <strong class="fs-5">Growth Performance</strong>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                    <div class="col-md-4">-->
    <!--                        <div class="d-flex flex-column justify-content-evenly h-100">-->
    <!--                            <div class="d-flex align-items-center mb-3">-->
    <!--                                <div class="flex-shrink-0">-->
    <!--                                    <img src="{{ asset('public/assets/img/Tick-Square.svg') }}" alt="...">-->
    <!--                                </div>-->
    <!--                                <div class="flex-grow-1 ms-3">-->
    <!--                                    <strong class="fs-5">Scalability on Demand</strong>-->
    <!--                                </div>-->
    <!--                            </div>                        -->
    <!--                            <div class="d-flex align-items-center mb-3">-->
    <!--                                <div class="flex-shrink-0">-->
    <!--                                    <img src="{{ asset('public/assets/img/Tick-Square.svg') }}" alt="...">-->
    <!--                                </div>-->
    <!--                                <div class="flex-grow-1 ms-3">-->
    <!--                                    <strong class="fs-5">Fortified Security Measures</strong>-->
    <!--                                </div>-->
    <!--                            </div>                        -->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->

    <!--<section>-->
    <!--    <div class="container-fluid">-->
    <!--        <div class="row mx-0 align-items-center">-->
    <!--            <div class="col-lg-5">-->
    <!--                <img src="{{ asset('public/assets/img/home-about.webp') }}" class="img-fluid">-->
    <!--            </div>-->
    <!--            <div class="col-lg-7">-->
    <!--                <h2 class="fs-4 bg-warning rounded d-inline px-2 py-1 mb-2">About Us</h2>-->
    <!--                <h3 class="fs-2">Your Trust Partner in Cloud Hosting and VPS Solutions</h3>-->
    <!--                <p>At Kumeo, we are not just a hosting provider, we are your partners in success.</p>-->
    <!--                <p class="mb-0">Embrace the power of the cloud and VPS with us.</p>-->
    <!--                <p>We take pride in being more than just another hosting provider. Here is why we stand out:</p>-->
    <!--                <div class="row">-->
    <!--                    <div class="col">-->
    <!--                        <ul class="nav flex-column border-bottom pb-2">-->
    <!--                            <li class="nav-item py-2">-->
    <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.0003 17.7081C14.257 17.7081 17.7087 14.2573 17.7087 9.9998C17.7087 5.74313 14.257 2.29146 10.0003 2.29146C5.74366 2.29146 2.29199 5.74313 2.29199 9.9998C2.29199 14.2573 5.74366 17.7081 10.0003 17.7081Z" stroke="#285BD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.79883 12.8925L11.7038 9.99996L8.79883 7.10746" stroke="#285BD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>-->
    <!--                                Innovative Solutions-->
    <!--                            </li>-->
    <!--                            <li class="nav-item py-2">-->
    <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.0003 17.7081C14.257 17.7081 17.7087 14.2573 17.7087 9.9998C17.7087 5.74313 14.257 2.29146 10.0003 2.29146C5.74366 2.29146 2.29199 5.74313 2.29199 9.9998C2.29199 14.2573 5.74366 17.7081 10.0003 17.7081Z" stroke="#285BD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.79883 12.8925L11.7038 9.99996L8.79883 7.10746" stroke="#285BD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>-->
    <!--                                Reliability-->
    <!--                            </li>-->
    <!--                            <li class="nav-item py-2">-->
    <!--                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.0003 17.7081C14.257 17.7081 17.7087 14.2573 17.7087 9.9998C17.7087 5.74313 14.257 2.29146 10.0003 2.29146C5.74366 2.29146 2.29199 5.74313 2.29199 9.9998C2.29199 14.2573 5.74366 17.7081 10.0003 17.7081Z" stroke="#285BD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M8.79883 12.8925L11.7038 9.99996L8.79883 7.10746" stroke="#285BD4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>-->
    <!--                                Exceptional Support-->
    <!--                            </li>-->
    <!--                        </ul>-->
    <!--                    </div>-->
    <!--                    <div class="col">-->
    <!--                        <div class="card border-primary border-4 bg-light">-->
    <!--                            <div class="card-body">-->
    <!--                                <div>-->
    <!--                                    <h4 class="text-primary">Unlimited Scalability</h4>-->
    <!--                                    <p class="fs-4">Tailored Resources to Fuel Your Growth</p>-->
    <!--                                    <p class="text-muted">Our cloud hosting and VPS solutions are designed to grow with your business. Experience limitless scalability to match your evolving needs and seize opportunities for expansion.</p>-->
    <!--                                </div>-->
    <!--                            </div>-->
    <!--                        </div>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->

    <!--<section>-->
    <!--    <div class="container-fluid">-->
    <!--        <div class="row mx-0 align-items-center">-->
    <!--            <div class="col-md-6">-->
    <!--                <div class="pe-5">-->
    <!--                    <p class="text-primary text-decoration-underline">worldwide location</p>-->
    <!--                <h2>Develop locally and Deploy globally</h2>-->
    <!--                <p class="text-muted">Unlock the potential of local development and global deployment. With our platform, you have the tools and infrastructure needed to bring your creations to the world stage. Take control of your development process and watch your projects flourish on a global scale.</p>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="col-md-6">-->
    <!--                <img src="{{ asset('public/assets/img/map-2.png') }}" class="img-fluid">-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
@endsection