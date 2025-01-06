@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Staff Member</h1>

    <div class="card">
        <div class="card-header">
            <h5>Edit Staff Member</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('staff.update', $staff->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $staff->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $staff->email }}" required>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select name="role_id" class="form-select" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ $staffRole == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update Staff Member</button>
            </form>
        </div>
    </div>
</div>
@endsection
