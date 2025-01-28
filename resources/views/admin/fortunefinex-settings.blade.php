@extends('layouts.app')

@section('title', 'Checkbook Settings - Admin Dashboard')

@section('breadcrumb')
    Fortune Finex Settings
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="eur-tab" data-bs-toggle="tab" data-bs-target="#eurtab" type="button" role="tab" aria-controls="eurtab" aria-selected="true">EUR</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="usdtab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">USD</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="eurtab" role="tabpanel" aria-labelledby="eur-tab">
                <div class="card">
                    <div class="card-header row justify-content-between align-items-center m-0">
                        <div class="col-auto p-0">
                            <h2 class="card-title fs-5">Update FortuneFinex Keys</h2>
                        </div>
                        <form action="{{ route('admin.fortunefinex.update') }}" method="post" class="col-auto p-0">
                            @csrf
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="switch_button" name="switch_button" {{ $status == 1 ? 'checked' : ''; }} onchange="this.form.submit()">
                                <label class="form-check-label" for="switch_button">Disabled/Enabled</label>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.fortunefinex.keys.update') }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="member_id" class="form-label">Member Id</label>
                                    <input type="text" class="form-control @error('member_id') is-invalid @enderror" id="member_id" name="member_id" value="{{ old('member_id', $member_id) }}" required>
                                    @error('member_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="language" class="form-label">Language</label>
                                    <input type="text" class="form-control @error('language') is-invalid @enderror" id="language" name="language" value="{{ old('language', $language) }}" required>
                                    @error('language')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="secret_key" class="form-label">Secret Key</label>
                                    <input type="text" class="form-control @error('secret_key') is-invalid @enderror" id="secret_key" name="secret_key" value="{{ old('secret_key', $secret_key) }}" required>
                                    @error('secret_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="to_type" class="form-label">To Type</label>
                                    <input type="text" class="form-control @error('to_type') is-invalid @enderror" id="to_type" name="to_type" value="{{ old('to_type', $to_type) }}" required>
                                    @error('to_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="redirect_url" class="form-label">Merchant Redirect Url</label>
                                    <input type="text" class="form-control @error('redirect_url') is-invalid @enderror" id="redirect_url" name="redirect_url" value="{{ old('redirect_url', $redirect_url) }}" required>
                                    @error('redirect_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="notification_url" class="form-label">Notification url</label>
                                    <input type="text" class="form-control @error('notification_url') is-invalid @enderror" id="notification_url" name="notification_url" value="{{ old('notification_url', $notification_url) }}" required>
                                    @error('notification_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="terminal_id" class="form-label">Terminal Id</label>
                                    <input type="text" class="form-control @error('terminal_id') is-invalid @enderror" id="terminal_id" name="terminal_id" value="{{ old('terminal_id', $terminal_id) }}" required>
                                    @error('terminal_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label for="partner_id" class="form-label">Partner Id</label>
                                    <input type="text" class="form-control @error('partner_id') is-invalid @enderror" id="partner_id" name="partner_id" value="{{ old('partner_id', $partner_id) }}" required>
                                    @error('partner_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="api_type" id="inlineRadio1" value="sandbox"
                                        {{ $api_type == 'https://sandbox.fortunefinex.com/transaction/Checkout' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio1">Sandbox</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="api_type" id="inlineRadio2" value="production"
                                        {{ $api_type == 'https://secure.fortunefinex.com/transaction/Checkout' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio2">Production</label>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
