@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Merchants</h1>
    <a href="{{ route('admin.merchant.create') }}" class="btn btn-primary mb-3">Create New Merchant</a>

    <div class="card">
        <div class="card-header">
            <h5>Merchant List</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th> <!-- Display Role -->
                        <th>Deposits</th>
                        <th>Withdrawals</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->role_name }}</td> <!-- Display role name from roles table -->
                            <td><a href="{{ route('admin.merchant.deposits', $member->id) }}" class="btn btn-success">Deposits</a></td>
                            <td><a href="{{ route('admin.merchant.withdrawals', $member->id) }}" class="btn btn-success">Withdrawals</a></td>
                            <td>
                                <a href="{{ route('staff.edit', $member->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('staff.destroy', $member->id) }}" method="POST" style="display:inline;">
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
    </div>
</div>
@endsection
