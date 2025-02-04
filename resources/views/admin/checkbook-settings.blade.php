@extends('layouts.app')

@section('title', 'Checkbook Settings - Admin Dashboard')

@section('breadcrumb')
    Checkbook Settings
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
                    <h2 class="card-title fs-5">Update Checkbook Keys</h2>
                </div>
                <form action="{{ route('admin.checkbook.update') }}" method="post" class="col-auto p-0">
                    @csrf
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="switch_button" name="switch_button" {{ $status == 1 ? 'checked' : ''; }} onchange="this.form.submit()">
                        <label class="form-check-label" for="switch_button">Disabled/Enabled</label>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.checkbook.keys.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="checkbook_production_key" class="form-label">Checkbook production Key</label>
                                        <input type="text" class="form-control @error('checkbook_key') is-invalid @enderror" id="checkbook_production_key" name="checkbook_production_key" value="{{ old('checkbook_production_key', $checkbook_production_key ?? $commented_production_key) }}" required>
                                        @error('checkbook_key')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="checkbook_production_secret" class="form-label">Checkbook production Secret</label>
                                        <input type="text" class="form-control @error('checkbook_secret') is-invalid @enderror" id="checkbook_production_secret" name="checkbook_production_secret" value="{{ old('checkbook_production_secret', $checkbook_production_secret ?? $commented_production_secret) }}" required>
                                        @error('checkbook_secret')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="api_type" id="inlineRadio2" value="production" {{ env('CHECKBOOK_API_KEY') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio2">Production</label>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="checkbook_sandbox_key" class="form-label">Checkbook sandbox Key</label>
                                        <input type="text" class="form-control @error('checkbook_key') is-invalid @enderror" id="checkbook__sandbox_key" name="checkbook_sandbox_key" value="{{ old('checkbook_sandbox_key', $checkbook_sandbox_key ?? $commented_sandbox_key) }}" required>
                                        @error('checkbook_key')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="checkbook_sandbox_secret" class="form-label">Checkbook sandbox Secret</label>
                                        <input type="text" class="form-control @error('checkbook_secret') is-invalid @enderror" id="checkbook_sandbox_secret" name="checkbook_sandbox_secret" value="{{ old('checkbook_sandbox_secret', $checkbook_sandbox_secret ?? $commented_sandbox_secret) }}" required>
                                        @error('checkbook_secret')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="api_type" id="inlineRadio1" value="sandbox" {{ env('CHECKBOOK_SANDBOX_API_KEY') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inlineRadio1">Sandbox</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
            
                    
                    
                    <div class="mb-3">
                        
                        

                    </div>
            
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
        
    </div>
@endsection
