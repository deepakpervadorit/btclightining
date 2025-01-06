@extends('layouts.app')

@section('title', 'Dashboard')

<!-- External CSS links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css" rel="stylesheet">

@section('breadcrumb')
    Staff
@endsection

@section('content')
<div class="card shadow-lg">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="text-base font-semibold leading-6 text-gray-900">
                        <h5 class="">Staff</h5>
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">A list of all the Staff Members.</p>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary float-end my-3" data-bs-toggle="modal" data-bs-target="#addUserModal"> Add Staff </button>
                </div>
            </div>

            <!-- Add Staff Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Add New Staff</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addUserForm" method="post" action="{{route('addstaff')}}">
                                @csrf
                                <!-- Add form fields for user details -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="staff">Staff</option>
                                        <option value="limited_staff">Limited Staff</option>
                                        <option value="manager">Manager</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="addUserForm">Save User</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Staff Modal -->
            <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Edit Staff</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editUserForm" method="post" action="{{route('updatestaff')}}">
                                @csrf
                                @method('PUT')
                                <!-- Hidden field for storing staff ID -->
                                <input type="hidden" id="editStaffId" name="id">
                                <!-- Edit form fields for user details -->
                                <div class="mb-3">
                                    <label for="editName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editRole" class="form-label">Role</label>
                                    <select class="form-select" id="editRole" name="role">
                                        <option value="staff">Staff</option>
                                        <option value="limited_staff">Limited Staff</option>
                                        <option value="manager">Manager</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="editUserForm">Update User</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Table -->
            <table id="example" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($staffs as $staff)
<tr>
    <td>{{ $staff->name }}</td>
    <td>{{ $staff->email }}</td>
    <td>{{ $staff->role }}</td>
    <td>
<button class="btn btn-sm text-primary" onclick="editStaff({{ json_encode($staff) }})">Edit</button>
        <form method="post" action="{{ route('deletestaff', $staff->id) }}" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm text-danger">Delete</button>
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

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(function() {
      $('#example').DataTable();
    });

    function editStaff(staff) {
        // Fill the edit form with the staff's current data
        $('#editStaffId').val(staff.id);
        $('#editName').val(staff.name);
        $('#editEmail').val(staff.email);
        $('#editRole').val(staff.role);

        // Show the edit modal
        $('#editUserModal').modal('show');
    }
</script>
