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
                    @if ($payment_gateways->where('name', 'stripe')->first()?->status == 1)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="stripe" id="stripe" name="gateways[]">
                      <label class="form-check-label" for="stripe">
                        Stripe
                      </label>
                    </div>
                    @endif
                    @if ($payment_gateways->where('name', 'square')->first()?->status == 1)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="square" id="square" name="gateways[]">
                      <label class="form-check-label" for="square">
                        Square
                      </label>
                    </div>
                    @endif
                    @if ($payment_gateways->where('name', 'checkbook')->first()?->status == 1)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="checkbook" id="checkbook" name="gateways[]">
                      <label class="form-check-label" for="checkbook">
                        Checkbook
                      </label>
                    </div>
                    @endif
                    @if ($payment_gateways->where('name', 'fortuneFinex')->first()?->status == 1)
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="fortunefinex" id="fortunefinex" name="gateways[]">
                      <label class="form-check-label" for="fortunefinex">
                        FortuneFinex
                      </label>
                    </div>
                    @endif
                </div>
                
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <label for="deposit_fees" class="form-label">Deposit Fees (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="deposit_fees" class="form-control" id="deposit_fees" required>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label for="withdraw_fees" class="form-label">Withdraw Fees (%)</label>
                        <input type="number" step="0.01" min="0" max="100" name="withdraw_fees" class="form-control" id="withdraw_fees" required>
                    </div>
                </div>


                <button type="submit" class="btn btn-success">Create Merchant</button>
            </form>
        </div>
    </div>
</div>
@endsection
