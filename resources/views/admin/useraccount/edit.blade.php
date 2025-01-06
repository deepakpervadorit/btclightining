@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Staff Member</h1>

    <div class="card">
        <div class="card-header">
            <h5>Edit Staff Member</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
 <!-- User Name / Name -->
                <div class="mb-3">
                        <label for="username" class="form-label">Name/UserName</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ $user->user_id }}">
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="line_1" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                    </div>
                    <!-- Address Line 1 -->
                    <div class="mb-3">
                        <label for="line_1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="line_1" name="line_1" value="{{ $user->line_1 }}">
                    </div>
                    <!-- City -->
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" value="{{ $user->city }}">
                    </div>
                    <!-- State -->
                    <div class="mb-3">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="state" name="state" value="{{ $user->state }}">
                    </div>
                    <!-- Country -->
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" class="form-control" id="country" name="country" value="{{ $user->country }}">
                    </div>
                    <!-- Zip Code -->
                    <div class="mb-3">
                        <label for="zip" class="form-label">Zip Code</label>
                        <input type="text" class="form-control" id="zip" name="zip" value="{{ $user->zip_code }}">
                    </div>

                    <!-- Card Number -->
                    <div class="mb-3">
                        <label for="card_number" class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="card_number" name="card_number" value="{{ $user->card_number }}">
                    </div>
                    
                    <!-- CVV -->
                    <div class="mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" value="{{ $user->cvv }}">
                    </div>
                    
                    <!-- Expiration Date -->
                    <div class="mb-3">
                        <label for="expiration_date" class="form-label">Expiration Date</label>
                        <input type="month" class="form-control" id="expiration_date" name="expiration_date" value="{{ $user->expiration_date }}">
                    </div>


                <button type="submit" class="btn btn-success">Update Staff Member</button>
            </form>
        </div>
    </div>
</div>
@endsection
