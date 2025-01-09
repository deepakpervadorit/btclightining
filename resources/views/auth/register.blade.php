@section('title', 'Dashboard')

<!-- External CSS links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css" rel="stylesheet">

<style>
    .col-form-label {
    padding-top: calc(.375rem + var(--bs-border-width));
    padding-bottom: calc(.375rem + var(--bs-border-width));
    margin-bottom: 0;
    font-size: inherit;
    line-height: 1.5;
    color: white !important;
    font-size: 19px;
}
button.btn.btn-white {
    background: white !important;
    font-size: 15px;
}
</style>
<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center" style="background: url(https://pay.cumbo.tech/public/assets/img/banner.png);
    background-size: cover;
    background-repeat: no-repeat;
    background-position-x: center;
}">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="w-50 px-4 pt-4 rounded" style="background: rgb(28 33 94 / 75%);
border-radius: 16px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(12px);
-webkit-backdrop-filter: blur(0px);
border: 1px solid rgba(255, 255, 255, 0.3);
padding: 35px 75px !important;">
        <div class="py-2">
        <a href="/" style="text-decoration:none;">
<h2 class="text-center text-white" >C U M B O P A Y</h2>
        </a>
    </div>
                    <form method="POST" action="{{ route('merchant.register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-form-label ">{{ __('User Name') }}</label>

<input id="name"
       type="text"
       class="form-control"
       name="name"
       required
       pattern="^[A-Za-z0-9]*$">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-form-label ">{{ __('Email Address') }}</label>

                            <input type="text"  name="merchantid" value="{{$merchantid}}" hidden>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-form-label ">{{ __('Password') }}</label>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-form-label ">{{ __('Confirm Password') }}</label>

                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-2 offset-md-10">
                                <button type="submit" class="btn btn-white">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                 </div>
</div>



<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<!-- Load Bootstrap and DataTables JS files that depend on jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.colVis.min.js"></script>

<!-- DataTable Initialization -->
<script>
    $(document).ready(function() {
       $('#example').DataTable();
    });
</script>

