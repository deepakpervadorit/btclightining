@extends('layouts.app')

@section('title', 'Dashboard')

<!-- External CSS links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

@section('breadcrumb')
    Payments
@endsection
@section('content')
 <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h5>Complete A Payment</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('check.send') }}">
                            @csrf <!-- This generates a CSRF token for security -->
                            <input type="hidden" class="form-control" id="recipient" name="recipient" value="shaheerzahid6@gmail.com">
                            <input type="hidden" class="form-control" id="description" name="description" value="asdfasdf">
                            
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount:</label>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" value="{{ $amount }}.09" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Send Check</button>
                        </form>

                        @if(session('success'))
                            <div class="alert alert-success mt-3">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection