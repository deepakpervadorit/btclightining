@extends('layouts.app')

@section('title', 'Checkbook Settings - Admin Dashboard')

@section('breadcrumb')
    Try Speed Settings
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        {{-- <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="eur-tab" data-bs-toggle="tab" data-bs-target="#eurtab" type="button" role="tab" aria-controls="eurtab" aria-selected="true">Test</button>
            </li>
        </ul> --}}
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="eurtab" role="tabpanel" aria-labelledby="eur-tab">
                <div class="card">
                    <div class="card-header row justify-content-between align-items-center m-0">
                        <div class="col-auto p-0">
                            <h2 class="card-title fs-5">Update TrySpeed Keys</h2>
                        </div>
                        <form action="{{ route('admin.tryspeed.update') }}" method="post" class="col-auto p-0">
                            @csrf
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="switch_button" name="switch_button" {{ $status == 1 ? 'checked' : ''; }} onchange="this.form.submit()">
                                <label class="form-check-label" for="switch_button">Disabled/Enabled</label>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.tryspeed.keys.update') }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label for="secret_key" class="form-label">Secret Key</label>
                                    <input type="text" class="form-control @error('secret_key') is-invalid @enderror" id="secret_key" name="secret_key" value="{{ old('secret_key', $secret_key) }}" required>
                                    @error('secret_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
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
