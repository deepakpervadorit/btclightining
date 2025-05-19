@extends('layouts.userapp')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('assets/home_assets/zstyle.css')}}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  
  <section class="hero-section py-5">
  <div class="container">
    <div class="row align-items-center justify-content-between">
      
      <!-- Left Text Content -->
      <div class="col-lg-6 col-md-12 text-white">
        <span class="highlight-line d-inline-block mb-3">
          <i class="fa fa-rocket"></i> Accept Bitcoin Instantly with BTCLightning
        </span>
        <h2><span>Lightning–Fast Bitcoin</span> Payments for Your Business. 
          <img src="{{asset('assets/home_assets/line.png')}}" alt="" class="line-text">
        </h2>
        <p class="text-light">
          Process Bitcoin transactions in seconds with the Lightning Network, while also supporting standard Bitcoin payments when needed.
        </p>
        <a href="#faq" class="start-btn mt-3 d-inline-block">Start Accepting Lightning Payments</a>
      </div>

      <!-- Right Image Column -->
      <div class="col-lg-5 col-md-12 text-center mt-4 mt-lg-0">
        <div class="d-flex justify-content-center gap-3">
          <img src="{{asset('assets/home_assets/BTC Lighting (2) (1).gif')}}" class="img-fluid" alt="Phone 1">
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Brand Logo Slider -->
<section class="brand-logo-slider py-5 bg-white">
  <div class="container">
    <div class="swiper mySwiper">
      <div class="swiper-wrapper align-items-center">
        <div class="swiper-slide"><img src="{{asset('assets/home_assets/logo1.png')}}" alt="PayPal" /></div>
        <div class="swiper-slide"><img src="{{asset('assets/home_assets/logo2.png')}}" alt="Venmo" /></div>
        <div class="swiper-slide"><img src="{{asset('assets/home_assets/logo3.svg')}}" alt="Kraken" /></div>
        <div class="swiper-slide"><img src="{{asset('assets/home_assets/logo4.svg')}}" alt="CashApp" /></div>
        <div class="swiper-slide"><img src="{{asset('assets/home_assets/logo1.png')}}" alt="Duplicate 1" /></div>
        <div class="swiper-slide"><img src="{{asset('assets/home_assets/logo2.png')}}" alt="Duplicate 2" /></div>
      </div>
    </div>
  </div>
</section>

<!-- Why BTCLightning -->
<section class="why-paylightning py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Why <span class="text-primary">BTCLightning</span>?</h2>
      <p class="text-muted">Fast, secure, and low-cost Bitcoin Lightning payments for vendors and businesses.</p>
    </div>

    <div class="row align-items-center gy-4">
      <!-- Left Features -->
      <div class="col-lg-4">
        <div class="feature-box p-4 rounded shadow-sm bg-white mb-4">
          <div class="icon-wrap mb-3">
            <img src="{{asset('assets/home_assets/1.svg')}}" width="40" alt="Icon">
          </div>
          <h5 class="fw-semibold">Instant Payments, Minimal Fees</h5>
          <p class="text-muted">Instant Bitcoin payments with near-zero fees for seamless transactions.</p>
        </div>
        <div class="feature-box p-4 rounded shadow-sm bg-white">
          <div class="icon-wrap mb-3">
            <img src="{{asset('assets/home_assets/3.svg')}}" width="40" alt="Icon">
          </div>
          <h5 class="fw-semibold">No Chargebacks or Fraud Risks</h5>
          <p class="text-muted">Secure transactions with no chargebacks or fraud risks for merchants.</p>
        </div>
      </div>

      <!-- Center Video or Logo -->
      <div class="col-lg-4 text-center">
        <div class="rounded shadow-sm overflow-hidden">
          <video class="w-100" autoplay muted loop>
            <source src="{{asset('assets/home_assets/why-pay.mp4')}}" type="video/mp4">
          </video>
        </div>
      </div>

      <!-- Right Features -->
      <div class="col-lg-4">
        <div class="feature-box p-4 rounded shadow-sm bg-white mb-4">
          <div class="icon-wrap mb-3">
            <img src="{{asset('assets/home_assets/1.svg')}}" width="40" alt="Icon">
          </div>
          <h5 class="fw-semibold">Bitcoin On-Chain for Large Transactions</h5>
          <p class="text-muted">Reliable Bitcoin on-chain support for secure and high-value transactions.</p>
        </div>
        <div class="feature-box p-4 rounded shadow-sm bg-white">
          <div class="icon-wrap mb-3">
            <img src="{{asset('assets/home_assets/1.svg')}}" width="40" alt="Icon">
          </div>
          <h5 class="fw-semibold">Flexible Network Support</h5>
          <p class="text-muted">Support for both Lightning and on-chain options to match your needs.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Customer Checkout -->
<section class="customer-checkout py-5 bg-light-purple text-center">
  <div class="container">
    <div class="checkout-image mx-auto">
      <img src="{{asset('assets/home_assets/banner.png')}}" alt="Customer Checkout" class="img-fluid">
    </div>
  </div>
</section>


<section class="vendor-section py-5 bg-white">
  <div class="container text-center mb-5">
    <h2 class="fw-bold"><span class="text-primary">Step-by-Step </span> Guide for Vendors</h2>
    <p class="text-muted">Easily integrate Bitcoin Lightning for fast, secure, and low-cost transactions.</p>
  </div>

  <div class="container d-flex flex-wrap align-items-center justify-content-center">
    <!-- Left Image -->
    <div class="col-md-6 text-center mb-4 mb-md-0">
      <img src="{{asset('assets/home_assets/BTC Lighting (3).gif')}}" alt="Mobile UI" class="img-fluid w-75">
    </div>

    <!-- Right Step Boxes -->
    <div class="col-md-6 d-flex flex-column gap-3">
      <div class="vendor-step-box">
        <div class="icon-wrap">
          <img src="{{asset('assets/home_assets/8.svg')}}" alt="Step 1">
        </div>
        <span>Contact us to Register as a Vendor</span>
      </div>

      <div class="vendor-step-box">
        <div class="icon-wrap">
          <img src="{{asset('assets/home_assets/7.svg')}}" alt="Step 2">
        </div>
        <span>Onboard New Businesses</span>
      </div>

      <div class="vendor-step-box">
        <div class="icon-wrap">
          <img src="{{asset('assets/home_assets/9.svg')}}" alt="Step 3">
        </div>
        <span>Earn Up to 5% Per Transaction</span>
      </div>

    <a href="#faq" class="btn-distributor-animated">Become A Distributor</a>
    </div>
  </div>
</section>

<section class="section-space bg-light">
  <div class="container">
    <div class="row g-4">
      
      <!-- Lightning Payments -->
      <div class="col-md-6">
        <div class="card shadow rounded h-100">
          <div class="card-header bg-white border-bottom-0">
            <h4 class="fw-bold text-primary">Lightning Payments</h4>
            <p class="text-muted">Fast, secure, and low-cost Bitcoin Lightning payments for businesses globally.</p>
          </div>
          <div class="card-body">
            <ul class="timeline-list">
              <li>
                <span class="dot dot-blue"></span>
                <div>
                  <strong>Instant</strong>
                  <p>Lightning-fast Bitcoin payments with instant transactions and minimal fees worldwide.</p>
                </div>
              </li>
              <li>
                <span class="dot dot-yellow"></span>
                <div>
                  <strong>Cost Effective</strong>
                  <p>Lightning payments offer fast, secure, and ultra-low-cost transactions globally.</p>
                </div>
              </li>
              <li>
                <span class="dot dot-green"></span>
                <div>
                  <strong>Scalable</strong>
                  <p>Lightning payments ensure fast, secure, and scalable transactions for businesses.</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Bitcoin On-Chain -->
      <div class="col-md-6">
        <div class="card shadow rounded h-100">
          <div class="card-header bg-white border-bottom-0">
            <h4 class="fw-bold text-primary">Bitcoin On-Chain</h4>
            <p class="text-muted">Secure, reliable Bitcoin on-chain transactions for large and high-value payments.</p>
          </div>
          <div class="card-body">
            <ul class="timeline-list">
              <li>
                <span class="dot dot-blue"></span>
                <div>
                  <strong>Secure</strong>
                  <p>Bitcoin on-chain ensures secure, transparent, and reliable large transactions.</p>
                </div>
              </li>
              <li>
                <span class="dot dot-yellow"></span>
                <div>
                  <strong>Ideal for Large Payments</strong>
                  <p>Perfect for secure, high-value transactions worldwide.</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="section-space how-it-works bg-white">
  <div class="container text-center">
    <div class="title mb-5">
      <h2 class="fw-bold">Step-by-Step Guide for Businesses</h2>
      <p class="text-muted">Easily integrate Bitcoin Lightning for fast, secure, and low-cost transactions.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-3 col-sm-6">
        <div class="step-box">
          <img src="{{asset('assets/home_assets/10.png')}}" class="icon-img" alt="Step 1">
          <p>Contact us to create your account</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="step-box">
          <img src="{{asset('assets/home_assets/11.png')}}" class="icon-img" alt="Step 2">
          <p>Set up personalized link for customer payments</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="step-box">
          <img src="{{asset('assets/home_assets/12.png')}}" class="icon-img" alt="Step 3">
          <p>Customers pay via Bitcoin Lightning instantly</p>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="step-box">
          <img src="{{asset('assets/home_assets/13.png')}}" class="icon-img" alt="Step 4">
          <p>Access your balance anytime, hassle-free</p>
        </div>
      </div>
    </div>

    <div class="mt-5">
      <a class="btn btn-crypto-cta" href="#faq">Start Accepting Crypto</a>
    </div>
  </div>
</section>

<!-- FAQ -->
<section class="frequently-asked-questions section-space playlightning-demo-section layout bg-white" id="faq">
    <div class="container-fluid" bis_skin_checked="1">
        <div class="title" bis_skin_checked="1">
            <h2>Who Can Use BTCLightning?</h2>
            <p>Businesses, vendors, and individuals seeking fast, secure Bitcoin Lightning payments.</p>
        </div>
    </div>
    <div class="container" bis_skin_checked="1">
        <div class="row" bis_skin_checked="1">
            <div class="col-lg-6" bis_skin_checked="1">
                <div class="card rounded faq-section shadow height-equal" style="min-height: 539.281px;" bis_skin_checked="1">
                    <div class="card-header faq-section" bis_skin_checked="1">
                        <h4>Contact Us </h4>
                        <p class="f-m-light mt-1">Fill up your details and proceed next steps.</p>
                    </div>
                    <div class="card-body" bis_skin_checked="1">
                        <div id="msform" class="landing-page-form" bis_skin_checked="1">
                         
                              <form class="row g-3 custom-input" method="POST" action="https://paylightning.io/add-contact">
                                <input type="hidden" name="_token" value="h2JF2JFsjRFG4fJb4ArE35xMlvTVPwEViJhFadpq" autocomplete="off">                                
                                <div class="col-12" bis_skin_checked="1">
                                    <label class="form-label" for="firstnamewizard">Name<span class="txt-danger">*</span></label>
                                     <input type="text" class="form-control " id="name" name="name" placeholder="Enter your name" value="" required="">
                                                                    </div>
                                <div class="col-12" bis_skin_checked="1">
                                    <label class="form-label" for="email-basic-wizard"> Phone<span class="txt-danger">*</span></label>
                                    <input type="number" class="form-control " id="telephone" name="telephone" placeholder="Enter your WhatsApp Phone number" value="" required="">
                                                                    </div>
                                <div class="col-12" bis_skin_checked="1">
                                    <label class="col-sm-12 form-label" for="phonewizard">Email <span class="txt-danger">*</span></label>
                                    <input type="email" class="form-control " id="email" name="email" aria-describedby="emailHelp" placeholder="Enter your email" value="" required="">
                           
                                                                    </div>

                                <div class="col-12" bis_skin_checked="1">
                                    <label class="col-sm-12 form-label" for="msgwizard">Message<span class="txt-danger">*</span></label>
                                    <textarea class="form-control " id="message" name="message" rows="4" placeholder="Enter your message" required=""></textarea>
                                                                    </div>
                                
                                <div class="wizard-footer d-flex gap-2 justify-content-end mt-3" bis_skin_checked="1">
                                    <button class="btn btn-primary btn-lg">Submit</button>
                                </div>
                            </form>
                        </div>
                      
                    </div>
                </div>
			</div>
            <div class="col-lg-6 mb-4" bis_skin_checked="1">
                <div class="accordion dark-accordion shadow height-equal" id="outlineaccordion" style="min-height: 539.281px;" bis_skin_checked="1">
                    <div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordionone">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseOne" aria-expanded="false" aria-controls="left-collapseOne">
                            What is BTCLightning?
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseOne" aria-labelledby="outlineaccordionone" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>BTCLightning is a crypto payment platform that lets businesses accept Bitcoin Lightning and Bitcoin (on-chain) payments instantly—no chargebacks, no holds, and no bank interference.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordiontwo">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseTwo" aria-expanded="false" aria-controls="left-collapseTwo">
                            How fast are transactions?
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseTwo" aria-labelledby="outlineaccordiontwo" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>Bitcoin Lightning payments are instant. On-chain Bitcoin payments depend on network congestion but typically take 10–30 minutes to confirm.</p>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordionfour">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseFour" aria-expanded="false" aria-controls="left-collapseFour">
                            Are there chargebacks or payment holds?
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseFour" aria-labelledby="outlineaccordionfour" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>No. Since BTCLightning uses crypto, you keep full control of your funds. No chargebacks, no reserves, no payment freezes—ever.</p>
                            </div>
                        </div>
                    </div>
        			<div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordionfive">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseFive" aria-expanded="false" aria-controls="left-collapseFour">
                            Do I need to verify my identity or link a bank account?
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseFive" aria-labelledby="outlineaccordionfive" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>No. You only need a crypto wallet to start receiving payments. No banking integration is required.</p>
                            </div>
                        </div>
                    </div>
        			<div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordionsix">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseSix" aria-expanded="false" aria-controls="left-collapseFour">
                            How do I withdraw my funds?
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseSix" aria-labelledby="outlineaccordionsix" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>You can withdraw your balance instantly to your personal crypto wallet—no approval needed.</p>
                            </div>
                        </div>
                    </div>
        			<div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordionseven">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseSeven" aria-expanded="false" aria-controls="left-collapseFour">
                            Can I use BTCLightning on my existing website?
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseSeven" aria-labelledby="outlineaccordionseven" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>Yes! You can add your payment QR code or link to any website, or get a custom one-page site from us if you don’t have one.</p>
                            </div>
                        </div>
                    </div>
        			<div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordioneight">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseEight" aria-expanded="false" aria-controls="left-collapseFour">
                            Is BTCLightning secure?
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseEight" aria-labelledby="outlineaccordioneight" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>Absolutely. We use trusted crypto infrastructure and secure connections to keep your funds safe at all times.</p>
                            </div>
                        </div>
                    </div>
        			<div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordionnine">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseNine" aria-expanded="false" aria-controls="left-collapseFour">
                            What types of businesses can use BTCLightning?
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseNine" aria-labelledby="outlineaccordionnine" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>Any business that wants to accept crypto—online services, gaming platforms, creators, retailers, and more.</p>
                            </div>
                        </div>
                    </div>
        			<div class="accordion-item accordion-wrapper" bis_skin_checked="1">
                        <h2 class="accordion-header" id="outlineaccordionten">
                            <button class="accordion-button accordion-light-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#left-collapseTen" aria-expanded="false" aria-controls="left-collapseFour">
                            How do I get started?
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down svg-color"><polyline points="6 9 12 15 18 9"></polyline></svg></button>
                        </h2>
                        <div class="accordion-collapse collapse" id="left-collapseTen" aria-labelledby="outlineaccordionten" data-bs-parent="#outlineaccordion" style="" bis_skin_checked="1">
                            <div class="accordion-body" bis_skin_checked="1">
                                <p>Click <a href="https://paylightning.io/#contactUs" bis_skin_checked="1">Contact Us</a> and fill out a quick onboarding form. We’ll get you all setup in no time.</p>
                            </div>
                        </div>
                    </div>
		        </div>
		    </div>
		    
		</div>
    </div>
</section>

<!-- Testimonials -->
<!-- Testimonials -->
<section class="section-space testimonials-section overflow-hidden bg-white bg-gradient-color" id="about-us">
    <div class="container-fluid" bis_skin_checked="1">

    </div>
    <div class="container" bis_skin_checked="1">
        <div class="row" bis_skin_checked="1">
            <div class="col-lg-8" bis_skin_checked="1">
                        <div class="title" bis_skin_checked="1">
                            <h2 class="text-white">Testimonials &amp; Success Stories</h2>
                            <p class="text-white">Real success stories showcasing seamless Bitcoin Lightning payments for businesses.</p>
                        </div>
                <div class="swiper testimonials-swiper swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden" bis_skin_checked="1">
                    <div class="swiper-wrapper" id="swiper-wrapper-1ec2a2198d22cd31" aria-live="off" style="transition-duration: 2000ms; transform: translate3d(-1522px, 0px, 0px);" bis_skin_checked="1"><div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active" data-swiper-slide-index="2" role="group" aria-label="3 / 4" style="width: 355.5px; margin-right: 25px;" bis_skin_checked="1">
                            <div class="card shadow rounded faq-section" bis_skin_checked="1">
                                <div class="card-body" bis_skin_checked="1">
                                    <p>“We switched to BTCLightning and saved thousands in fees!”</p>
                                    <span class="f-light">Business Owner</span>
                                </div>
                            </div>
                    </div><div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-next" data-swiper-slide-index="3" role="group" aria-label="4 / 4" style="width: 355.5px; margin-right: 25px;" bis_skin_checked="1">
                            <div class="card shadow rounded faq-section" bis_skin_checked="1">
                                <div class="card-body" bis_skin_checked="1">
                                    <p>“With BTCLightning, I never have to deal with chargebacks, payment holds, or reserves from banks. It’s fast, reliable, and gives me full control over my money. Easily one of the best payment processors out there!”</p>
                                    <span class="f-light">Business Owner</span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide" data-swiper-slide-index="0" role="group" aria-label="1 / 4" style="width: 355.5px; margin-right: 25px;" bis_skin_checked="1">
                            <div class="card shadow rounded faq-section" bis_skin_checked="1">
                                <div class="card-body" bis_skin_checked="1">
                                    <p>“We switched to BTCLightning and saved thousands in fees!”</p>
                                    <span class="f-light">Business Owner</span>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide swiper-slide-prev" data-swiper-slide-index="1" role="group" aria-label="2 / 4" style="width: 355.5px; margin-right: 25px;" bis_skin_checked="1">
                            <div class="card shadow rounded faq-section" bis_skin_checked="1">
                                <div class="card-body" bis_skin_checked="1">
                                    <p>“As a vendor, I onboarded 10 businesses and now earn passive crypto daily!”</p>
                                    <span class="f-light">Business Owner</span>
                                </div>
                            </div>
                    </div>
                        <div class="swiper-slide swiper-slide-active" data-swiper-slide-index="2" role="group" aria-label="3 / 4" style="width: 355.5px; margin-right: 25px;" bis_skin_checked="1">
                            <div class="card shadow rounded faq-section" bis_skin_checked="1">
                                <div class="card-body" bis_skin_checked="1">
                                    <p>“We switched to BTCLightning and saved thousands in fees!”</p>
                                    <span class="f-light">Business Owner</span>
                                </div>
                            </div>
                    </div>
    					<div class="swiper-slide swiper-slide-next" data-swiper-slide-index="3" role="group" aria-label="4 / 4" style="width: 355.5px; margin-right: 25px;" bis_skin_checked="1">
                            <div class="card shadow rounded faq-section" bis_skin_checked="1">
                                <div class="card-body" bis_skin_checked="1">
                                    <p>“With BTCLightning, I never have to deal with chargebacks, payment holds, or reserves from banks. It’s fast, reliable, and gives me full control over my money. Easily one of the best payment processors out there!”</p>
                                    <span class="f-light">Business Owner</span>
                                </div>
                            </div>
                        </div>
    				<div class="swiper-slide swiper-slide-duplicate" data-swiper-slide-index="0" role="group" aria-label="1 / 4" style="width: 355.5px; margin-right: 25px;" bis_skin_checked="1">
                            <div class="card shadow rounded faq-section" bis_skin_checked="1">
                                <div class="card-body" bis_skin_checked="1">
                                    <p>“We switched to BTCLightning and saved thousands in fees!”</p>
                                    <span class="f-light">Business Owner</span>
                                </div>
                            </div>
                        </div><div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-prev" data-swiper-slide-index="1" role="group" aria-label="2 / 4" style="width: 355.5px; margin-right: 25px;" bis_skin_checked="1">
                            <div class="card shadow rounded faq-section" bis_skin_checked="1">
                                <div class="card-body" bis_skin_checked="1">
                                    <p>“As a vendor, I onboarded 10 businesses and now earn passive crypto daily!”</p>
                                    <span class="f-light">Business Owner</span>
                                </div>
                            </div>
                    </div></div>
                    <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal" bis_skin_checked="1"><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 1"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 3" aria-current="true"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 4"></span></div>
                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
            </div>
            <div class="col-lg-4" bis_skin_checked="1">
                <div class="testimonial-image" bis_skin_checked="1">
                    <img src="{{asset('assets/home_assets/testimonials.webp')}}" class="w-100 shadow rounded" alt="Testimonials">
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer text-center py-4 bg-light mt-5">
  <p class="mb-0 text-muted">Copyright &copy; 2025 © XYZ</p>
</footer>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<script>
  const swiper = new Swiper(".mySwiper", {
    loop: true,
    slidesPerView: 2,
    spaceBetween: 30,
    autoplay: {
      delay: 2000,
      disableOnInteraction: false,
    },
    breakpoints: {
      576: { slidesPerView: 3 },
      768: { slidesPerView: 4 },
      992: { slidesPerView: 5 }
    }
  });
</script>
<!-- ✅ Include Swiper CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

<!-- ✅ Initialize Swiper (place before </body>) -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    new Swiper(".testimonials-swiper", {
      loop: true,
      spaceBetween: 25,
      slidesPerView: 1,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true
      },
      breakpoints: {
        768: { slidesPerView: 2 },
        1200: { slidesPerView: 2 }
      }
    });
  });
</script>
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