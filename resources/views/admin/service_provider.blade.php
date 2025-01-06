@extends('layouts.app')

@section('title', 'Service Provider')

<!-- External CSS links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css" rel="stylesheet">

@section('breadcrumb')
    Service Provider
@endsection

@section('content')
<div class="card shadow-lg">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="text-base font-semibold leading-6 text-gray-900">
                        <h5 class="">Games</h5>
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">A list of all the available games.</p>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary float-end my-3" data-bs-toggle="modal" data-bs-target="#addGameModal"> Add Game </button>
                </div>
            </div>

            <!-- Add Game Modal -->
            <div class="modal fade" id="addGameModal" tabindex="-1" aria-labelledby="addGameModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addGameModalLabel">Add New Game</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="addGameForm" method="post" action="{{route('addgame')}}">
                                @csrf
                                <!-- Add form fields for game details -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Game Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="addGameForm">Save Game</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Game Modal -->
            <div class="modal fade" id="editGameModal" tabindex="-1" aria-labelledby="editGameModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editGameModalLabel">Edit Game</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editGameForm" method="post" action="{{route('updategame')}}">
                                @csrf
                                @method('PUT')
                                <!-- Hidden field for storing game ID -->
                                <input type="hidden" id="editGameId" name="id">
                                <!-- Edit form fields for game details -->
                                <div class="mb-3">
                                    <label for="editName" class="form-label">Game Name</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editStatus" class="form-label">Status</label>
                                    <select class="form-select" id="editStatus" name="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" form="editGameForm">Update Game</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Games Table -->
            <table id="example" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($games as $game)
<tr>
    <td>{{ $game->game }}</td>
    <td>{{ $game->status }}</td>
    <td>
        <button class="btn btn-sm text-primary" onclick="editGame({{ json_encode($game) }})">Edit</button>
        <form method="post" action="{{ route('deletegame', $game->id) }}" style="display:inline;">
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

    function editGame(game) {
        // Fill the edit form with the game's current data
        $('#editGameId').val(game.id);
        $('#editName').val(game.name);
        $('#editStatus').val(game.status);

        // Show the edit modal
        $('#editGameModal').modal('show');
    }
</script>
