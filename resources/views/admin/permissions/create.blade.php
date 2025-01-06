@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Create New Permission</h1>

    <div class="card">
        <div class="card-header">
            <h5>Create New Permission</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Create Permission</button>
            </form>
        </div>
    </div>
</div>
@endsection
