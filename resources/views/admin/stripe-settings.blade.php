@extends('layouts.app')

@section('title', 'Stripe Settings - Admin Dashboard')

@section('breadcrumb')
    Stripe Settings
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="card">
            <div class="card-header row justify-content-between align-items-center m-0">
                <div class="col-auto p-0">
                    <h2 class="card-title fs-5">Update Stripe Keys</h2>
                </div>
                <form action="{{ route('admin.stripe.update') }}" method="post" class="col-auto p-0">
                    @csrf
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="switch_button" name="switch_button" {{ $status == 1 ? 'checked' : ''; }} onchange="this.form.submit()">
                        <label class="form-check-label" for="switch_button">Disabled/Enabled</label>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.stripe.keys.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="stripe_key" class="form-label">Stripe Key</label>
                        <input type="text" class="form-control @error('stripe_key') is-invalid @enderror" id="stripe_key" name="stripe_key" value="{{ old('stripe_key', $stripeKey) }}" required>
                        @error('stripe_key')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="stripe_secret" class="form-label">Stripe Secret</label>
                        <input type="text" class="form-control @error('stripe_secret') is-invalid @enderror" id="stripe_secret" name="stripe_secret" value="{{ old('stripe_secret', $stripeSecret) }}" required>
                        @error('stripe_secret')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
        
    </div>
@endsection
@section('scripts')
<script>
    @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
    @endif
    @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
    @endif
</script>
@endsection
