@extends('layouts.app')

@section('content')
<div class="card container mt-4">
    <h1 class="my-4">Roles</h1>
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Create New Role</a>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Permissions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>
                        @php
                            $permissionsArray = $role->permissions->toArray();  // Store permissions as array
                        @endphp
                        <span class="badge bg-info">
                        @foreach ($permissionsArray as $permission)
                            {{ $permission['name'] }}@if (!$loop->last), @endif
                        @endforeach
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
