@extends('layouts.app')

@section('content')
@php
use Illuminate\Support\Facades\DB;
@endphp
<div class="container mt-4">
    <h1 class="mb-4">Create New Role</h1>

    <div class="card">
        <div class="card-header">
            <h5>Create New Role</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="permissions" class="form-label">Assign Permissions</label>
                    <div>
                        @php
                        $permissions = DB::table('permissions')->get();
                        @endphp
                        @foreach ($permissions as $permission)
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]" class="form-check-input" value="{{ $permission->id }}">
                                <label class="form-check-label">{{ $permission->name }}</label>
                            </div>
                        @endforeach
                        
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Create Role</button>
            </form>
        </div>
    </div>
</div>
@endsection
