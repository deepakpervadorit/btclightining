@extends('layouts.app')

@section('title', 'Square Settings - Admin Dashboard')

@section('breadcrumb')
    Square Settings
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
                    <h2 class="card-title fs-5">Update Square Keys</h2>
                </div>
                <form action="{{ route('admin.square.update') }}" method="post" class="col-auto p-0">
                    @csrf
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="switch_button" name="switch_button" {{ $status == 1 ? 'checked' : ''; }} onchange="this.form.submit()">
                        <label class="form-check-label" for="switch_button">Disabled/Enabled</label>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.square.keys.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="square_token" class="form-label">Square Token</label>
                        <input type="text" class="form-control @error('square_token') is-invalid @enderror" id="square_token" name="square_token" value="{{ old('square_token', $squareAccessToken) }}" required>
                        @error('square_token')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="square_location_id" class="form-label">Square Location Id</label>
                        <input type="text" class="form-control @error('square_location_id') is-invalid @enderror" id="square_location_id" name="square_location_id" value="{{ old('square_location_id', $squareLocationId) }}" required>
                        @error('square_location_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="square_application_id" class="form-label">Square Application Id</label>
                        <input type="text" class="form-control @error('square_application_id') is-invalid @enderror" id="square_application_id" name="square_application_id" value="{{ old('square_application_id', $squareApplicationId) }}" required>
                        @error('square_application_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
        
    </div>
@endsection
