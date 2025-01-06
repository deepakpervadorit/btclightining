@extends('layouts.app')

@section('title', 'Dashboard')

<!-- External CSS links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css" rel="stylesheet">
@section('breadcrumb')
    Withdrawal
@endsection
@section('content')

<style>
    
    tbody tr td {
    text-align: center;
}

thead tr th {
    text-align: center !important;
    font-size: 14px;
}

</style>
<div class="card shadow-lg">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold leading-6 text-gray-900">
                        <h5 class="">WithDrawals</h5>  
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">Received successful payment, pending staff account load.</p>
                </div>
            </div>
            <table id="example" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Provider</th>
                        <th>Method</th>
                        <th>Username</th>
                        <th>Amount Paid</th>
                        <!--<th>Fee</th>-->
                        <!--<th>Load</th>-->
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $deposit)
                    <tr>
                        <td>{{ $deposit->id }}</td>
                        @php
                        $provider = DB::table('games')->where('id',$deposit->server)->first();
                        
                        @endphp
                        <td>@php if (isset($provider->game)) {
    $game = $provider->game;
    echo $game;
} else {
    echo "Game property does not exist.";
} @endphp</td>
                        <td>{{ $deposit->payment_method }}</td>
                        <td>{{ $deposit->username }}</td>
                        <td>{{ $deposit->amount }}</td>
                        <!--<td>{{ $deposit->payment_method }}</td>-->
                        <!--<td>{{ $deposit->payment_method }}</td>-->
                   <td class="d-flex">
    @if($deposit->status == 'Paid')
    <button class="btn btn-success" disabled>Paid</button>
@elseif($deposit->status == 'Reject')
<button class="btn btn-danger" onsubmit="return confirmSubmission(event);" disabled>Decline</button>
@else
    <form method="POST" action="{{ route('withdrawal.updateStatus', $deposit->id) }}" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-warning me-3">Pay</button>
    </form>
    <form method="POST" action="{{ route('withdrawal.rejectStatus', $deposit->id) }}" style="display:inline;" onsubmit="return confirmSubmission(event);">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-danger" >Reject</button>
    </form>
@endif
</td>
                        <td>{{ $deposit->created_at }}</td>
                        <td>
                            <!-- Delete Form -->
                            <form action="{{ route('withdrawal.destroy', $deposit->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn text-danger btn-sm" onclick="return confirm('Are you sure you want to delete this deposit?')">Delete</button>
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

<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<!-- Load Bootstrap and DataTables JS files that depend on jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.colVis.min.js"></script>

<!-- DataTable Initialization -->
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "order": [[ 0, "desc" ]] // Change '8' to the index of the column you want to sort by (0-based index)
        });
    });
</script>
<script>
    function confirmSubmission(event) {
        // Show confirmation dialog
        const isConfirmed = confirm("Do You Really Want To Reject The Payment?");
        if (!isConfirmed) {
            // If the user clicks "Cancel", prevent the form submission
            event.preventDefault();
            return false;
        }
        return true; // Allow form submission if "OK" is clicked
    }
</script>
