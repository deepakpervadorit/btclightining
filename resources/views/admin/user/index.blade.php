@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">CUsers Member</h1>
    <!--<a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Create New User Member</a>-->

    <div class="card">
        <div class="card-header">
            <h5>User Member's List</h5>
        </div>
        <div class="card-body">
            <table id="userTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Verify</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->created_at }}</td>
                            <th>
                                <!-- Display the verification status and toggle button -->
                                <form action="{{ route('user.toggleVerify', $member->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    @if ($member->verify == 1)
    <span class="text-success">Verified</span>
@else
    <button type="submit" class="btn btn-sm btn-warning">
        Verify
    </button>
@endif
                                </form>
                                
                        </th>
                            <td>
                                <a href="{{ route('user.edit', $member->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('user.destroy', $member->id) }}" method="POST" style="display:inline;">
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userTable').DataTable({
            "paging": true, // Enable pagination
            "lengthChange": true, // Allow changing the number of records per page
            "searching": true, // Enable search functionality
            "ordering": true, // Enable column ordering
            "info": true, // Display table information
            "autoWidth": false, // Disable automatic column width adjustment
            "order": [[ 2, "desc" ]] // Order by the first column (index 0) in descending order
        });
    });
</script>
@endsection
@section('scripts')
<script>
    @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
    @endif
    @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
    @endif
</script>
@endsection