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
      <div class="row justify-content-center">
        <div class="col-lg-7 text-center">
          <!--<img src="{{ asset('assets/errors/images/error-404.png') }}" alt="@lang('image')">-->
          <h2><b>@lang('Thank You')</b> @lang('Payment successful')</h2>
            <p>Your amount {{$amount}} ({{$currency_code}}) has been added to your wallet</p>
          <a href="{{ url('dashboard') }}" class="cmn-btn mt-4">@lang('Go to Home')</a>
        </div>
      </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </section>
@endsection
