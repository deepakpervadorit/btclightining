@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Create New Merchant</h1>

    <div class="card">
        <div class="card-header">
            <h5>Create New Merchant</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.merchant.add') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <h5>Payment gateways</h5>
                <div class="mb-3">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="stripe" id="stripe" name="gateways[]">
                      <label class="form-check-label" for="stripe">
                        Stripe
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="square" id="square" name="gateways[]">
                      <label class="form-check-label" for="square">
                        Square
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="checkbook" id="checkbook" name="gateways[]">
                      <label class="form-check-label" for="checkbook">
                        Checkbook
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="fortunefinex" id="fortunefinex" name="gateways[]">
                      <label class="form-check-label" for="fortunefinex">
                        FortuneFinex
                      </label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="transaction_fees" class="form-label">Transaction Fees (USD)</label>
                    <input type="text" name="transaction_fees" class="form-control" id="transaction_fees" required>
                </div>


                <button type="submit" class="btn btn-success">Create Merchant</button>
            </form>
        </div>
    </div>
</div>
@endsection
