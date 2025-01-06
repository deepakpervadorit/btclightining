@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Create New Staff Member</h1>

    <div class="card">
        <div class="card-header">
            <h5>Create New Staff Member</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.store') }}" method="POST">
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

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        <option value="">Select a role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Create Staff Member</button>
            </form>
        </div>
    </div>
</div>
@endsection
