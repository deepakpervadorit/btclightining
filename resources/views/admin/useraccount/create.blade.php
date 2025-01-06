@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Create New User Member</h1>
@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session()->has('danger'))
    <div class="alert alert-danger">
        {{ session('danger') }}
    </div>
@endif

 <div class="card">
    <div class="card-header">
        <h5>Create New User Member</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('user.store') }}" method="POST" onsubmit="return confirmSubmission(event);">
    @csrf
    <!-- User Name / Name -->
    <div class="mb-3">
        <label for="username" class="form-label">Name/UserName</label>
        <input type="text" class="form-control" id="username" name="username" value="{{ $staffName }}" readonly>
    </div>
    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email/Phone</label>
        <input type="text" class="form-control" id="email" name="email" value="{{ $staffEmail }}">
    </div>

    <!-- Deposit Option Dropdown -->
    <div class="mb-3">
        <label for="deposit_option" class="form-label">Deposit Options</label>
        <select name="deposit_option" class="form-control mt-1" id="deposit_option" required>
            <option value="">--Select an Option--</option>
            <option value="ZELLE">Zelle</option>
            <option value="CARD">Push To Card</option>
            <option value="VCC">Virtual Card</option>
        </select>
    </div>

    <!-- Card Modal (Address and Card Details) -->
    <div id="cardModal" style="display: none;">
        <!-- Address Line 1 -->
        <div class="mb-3">
            <label for="line_1" class="form-label">Address Line 1</label>
            <input type="text" class="form-control" id="line_1" name="line_1" value="Keeley Flat">
        </div>
        <!-- City -->
        <div class="mb-3">
            <label for="city" class="form-label">City</label>
            <input type="text" class="form-control" id="city" name="city" value="Solonview">
        </div>
        <!-- State -->
        <div class="mb-3">
            <label for="state" class="form-label">State</label>
            <input type="text" class="form-control" id="state" name="state" value="District of Columbia">
        </div>
        <!-- Country -->
        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" class="form-control" id="country" name="country" value="United States">
        </div>
        <!-- Zip Code -->
        <div class="mb-3">
            <label for="zip" class="form-label">Zip Code</label>
            <input type="text" class="form-control" id="zip" name="zip" value="47942">
        </div>
        <!-- Card Number -->
        <div class="mb-3">
            <label for="card_number" class="form-label">Card Number</label>
            <input type="text" class="form-control" id="card_number" name="card_number" value="4222422242224222">
        </div>
        <!-- CVV -->
        <div class="mb-3">
            <label for="cvv" class="form-label">CVV</label>
            <input type="text" class="form-control" id="cvv" name="cvv" value="123">
        </div>
        <!-- Expiration Date -->
        <div class="mb-3">
            <label for="expiration_date" class="form-label">Expiration Date</label>
            <input type="month" class="form-control" id="expiration_date" name="expiration_date" value="2026-12">
        </div>
    </div>
    <button type="submit" class="btn btn-success">Create User Member</button>
</form>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Get the elements
    const depositOption = document.getElementById('deposit_option');
    const cardModal = document.getElementById('cardModal');

    // Listen for changes in the deposit option dropdown
    depositOption.addEventListener('change', function () {
        if (depositOption.value === 'CARD') {
            cardModal.style.display = 'block';  // Show the card modal if 'Push To Card' is selected
        } else {
            cardModal.style.display = 'none';  // Hide the card modal if other option is selected
        }
    });
});
</script>
<script>
    function confirmSubmission(event) {
        // Show confirmation dialog
        const isConfirmed = confirm("Is Your Email or Phone Number Correct?");
        if (!isConfirmed) {
            // If the user clicks "Cancel", prevent the form submission
            event.preventDefault();
            return false;
        }
        return true; // Allow form submission if "OK" is clicked
    }
</script>