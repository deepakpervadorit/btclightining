@extends('layouts.app')

@section('content')
      <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

<div class="container mt-4">
    <h1 class="mb-4">Accounts</h1>
    @if($usercount == '3')
    <a href="{{ route('user.create') }}" class="btn btn-primary mb-3 disabled" >Create New User Member</a>
    @else
    <a href="{{ route('user.create') }}" class="btn btn-primary mb-3">Create New User Member</a>
    @endif
@if (session()->has('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
    <div class="card">
        <div class="card-header">
            <h5>Account's List</h5>
        </div>
        <div class="card-body">
    <table id="userTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Payment Method</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($user as $member)
                <tr>
                    <td>{{ $member->user_id }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ str_replace('_new', '', $member->payment_method) }}</td>
                
                    <td>
                        @if($member->payment_method == 'CARD')
                        <a href="{{ route('user.edit', $member->id) }}" class="btn btn-warning">Edit</a>
                        @endif
                        <form action="{{ route('user.destroy', $member->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="api_id" value="{{$member->api_id}}">
                            <input type="hidden" name="userid" value="{{$member->userid}}">
                            <input type="hidden" name="payment_method" value="{{ $member->payment_method }}">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a href="{{ route('show.withdrawal.form') }}" class="btn btn-success">Withdraw</a>
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