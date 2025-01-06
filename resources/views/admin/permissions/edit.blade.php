@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Edit Permission</h1>

    <div class="card">
        <div class="card-header">
            <h5>Edit Permission</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $permission->name }}" required>
                </div>

                <button type="submit" class="btn btn-success">Update Permission</button>
            </form>
        </div>
    </div>
</div>
@endsection
