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
            <div class="card-header">
                <h2 class="card-title fs-5">Update Checkbook Keys</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.checkbook.keys.update') }}">
                    @csrf
            
                    <div class="mb-3">
                        <label for="checkbook_key" class="form-label">Checkbook Key</label>
                        <input type="text" class="form-control @error('checkbook_key') is-invalid @enderror" id="checkbook_key" name="checkbook_key" value="{{ old('checkbook_key', $checkbookKey) }}" required>
                        @error('checkbook_key')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
            
                    <div class="mb-3">
                        <label for="checkbook_secret" class="form-label">Checkbook Secret</label>
                        <input type="text" class="form-control @error('checkbook_secret') is-invalid @enderror" id="checkbook_secret" name="checkbook_secret" value="{{ old('checkbook_secret', $checkbookSecret) }}" required>
                        @error('checkbook_secret')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="api_type" id="inlineRadio1" value="sandbox"
        {{ $api_type == 'https://sandbox.checkbook.io/v3/' ? 'checked' : '' }}>
    <label class="form-check-label" for="inlineRadio1">Sandbox</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input" type="radio" name="api_type" id="inlineRadio2" value="production"
        {{ $api_type == 'https://api.checkbook.io/v3/' ? 'checked' : '' }}>
    <label class="form-check-label" for="inlineRadio2">Production</label>
</div>

                    </div>
            
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
        
    </div>
@endsection
