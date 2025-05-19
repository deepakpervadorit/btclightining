@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Staff Members</h1>
    <a href="{{ route('staff.create') }}" class="btn btn-primary mb-3">Create New Staff Member</a>

    <div class="card">
        <div class="card-header">
            <h5>Staff Members List</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered datatable">
                <thead class="table-dark">
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th> <!-- Display Role -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staff as $member)
                        <tr>
                            <td>{{ $member->id }}</td>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->role_name }}</td> <!-- Display role name from roles table -->
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
@section('scripts')
<script>
$('.datatable').DataTable({
            dom: 'Bfrtip', // Add buttons for export, print, etc.
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            responsive: true, // Enable responsive features
            order: [[0, 'asc']], // Default sorting by the first column (ascending)
            pageLength: 10, // Default number of rows per page
            language: {
                search: "Search:", // Customize search label
                paginate: {
                    next: 'Next',
                    previous: 'Previous'
                }
            }
        });
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
