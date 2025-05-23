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
@php
use Illuminate\Support\Facades\DB;
@endphp
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  {{ session('error') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="card shadow-lg">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold leading-6 text-gray-900">
                        <h5 class="">WithDrawals</h5>  
                    </h1>
                    <!--<p class="mt-2 text-sm text-gray-700">Received successful payment, pending staff account load.</p>-->
                </div>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="status_filter" class="form-label">Status:</label>
                        <select id="status_filter" class="form-select">
                            <option value="">All</option>
                            <option value="Paid">Paid</option>
                            <option value="Reject">Rejected</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="filter_button" class="btn btn-primary mt-2">Filter</button>
                        <button id="reset_button" class="btn btn-secondary mt-2 ms-2">Reset</button>
                    </div>
                </div>
            </div>
            <table id="example" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <!--<th>Provider</th>-->
                        <th>Method</th>
                        <!--<th>Username</th>-->
                        <th>Game Id</th>
                        <th>Amount Paid</th>
                        <!--<th>Fee</th>-->
                        <!--<th>Load</th>-->
                        <th>Status</th>
                        <th>Created At</th>
                        <!--<th>Action</th>-->
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $deposit)
                    <tr>
                        <td>{{ $deposit->id }}</td>
                        @php
                        $serverIds = explode(',', $deposit->server);
                        $providers = DB::table('games')->whereIn('id',$serverIds)->get();
                        
                        @endphp
                        {{--<td>
                            @if($providers)
                                @foreach($providers as $provider)
                                    {{$provider->game}},
                                @endforeach
                            @endif
                        </td>--}}
                        @if($deposit->payment_method == "Try Speed")
                        <td>Cashapp Crypto</td>
                        @else
                        <td>{{ $deposit->payment_method }}</td>
                        @endif
                        {{--<td>{{ $deposit->username }}</td>--}}
                        <td>{{ $deposit->game_id }}</td>
                        <td>{{ $deposit->amount }}</td>
                        <!--<td>{{ $deposit->payment_method }}</td>-->
                        <!--<td>{{ $deposit->payment_method }}</td>-->
                   <td class="d-flex">
    @if($deposit->status == 'Paid')
    <button class="btn btn-success" disabled>Paid</button>
@elseif($deposit->status == 'Reject')
<button class="btn btn-danger" onsubmit="return confirmSubmission(event);" disabled>Rejected</button>
@elseif($deposit->status == 'Unpaid')
    @if($roleName == "Merchant")
        @if($deposit->merchant_id != "" || $deposit->merchant_id != null)
        <form method="POST" action="{{ route('merchant.withdrawal.updateStatus', $deposit->id) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-warning me-3">Pay</button>
        </form>
        @else
        <form method="POST" action="{{ route('withdrawal.updateStatus', $deposit->id) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-warning me-3">Pay</button>
        </form>
        @endif
        <form method="POST" action="{{ route('withdrawal.rejectStatus', $deposit->id) }}" style="display:inline;" onsubmit="return confirmSubmission(event);">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-danger" >Reject</button>
        </form>
    @else
        <button class="btn btn-warning" disabled>Pending</button>
    @endif
@endif
</td>
                        <td>{{ $deposit->created_at }}</td>
                        <!--<td>-->
                            <!-- Delete Form -->
                        <!--    <form action="{{ route('withdrawal.destroy', $deposit->id) }}" method="POST" style="display:inline;">-->
                        <!--        @csrf-->
                        <!--        @method('DELETE')-->
                        <!--        <button type="submit" class="btn text-danger btn-sm" onclick="return confirm('Are you sure you want to delete this deposit?')">Delete</button>-->
                        <!--    </form>-->
                        <!--</td>-->
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
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>-->
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
        var table = $('#example').DataTable({
            "order": [[ 0, "desc" ]], // Change '8' to the index of the column you want to sort by (0-based index)\
            dom: 'Bfrtip', // Include Buttons in the DOM
            buttons: [
                {
                    extend: 'csvHtml5',
                    title: 'Withdrawals', // Title for the CSV file
                     // Heading for the table
                    exportOptions: {
                        columns: ':visible' // Specify which columns to export
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Withdrawals', // Title for the Excel file
                     // Heading for the table
                    exportOptions: {
                        columns: ':visible' // Specify which columns to export
                    }
                } // Export as Excel
            ]
        });
        
        $('#status_filter').change(function() {
            table.draw();
        });
    
        // Handle the filter button click
        $('#filter_button').click(function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
    
            // Apply custom filtering to the DataTable
            table.draw();
        });
        
        $('#reset_button').click(function() {
            // Reset the date range and status filter
            $('#start_date').val('');
            $('#end_date').val('');
            $('#status_filter').val('');
    
            // Redraw the table to remove all filters
            table.draw();
        });
    
        // Custom filtering function to filter by date range and status
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var status = $('#status_filter').val();
                var date = data[6]; // Assuming the 'Created At' column is at index 6 (0-based index)
                var rowStatus = data[5]; // Assuming the 'Status' column is at index 5 (0-based index)
    
                // Filter by date range
                if (startDate && endDate) {
                    var start = new Date(startDate);
                    var end = new Date(endDate);
                    var rowDate = new Date(date);
                    start.setHours(0, 0, 0, 0);
                    end.setHours(23, 59, 59, 999);
                    rowDate.setHours(0, 0, 0, 0);
    
                    if (rowDate < start || rowDate > end) {
                        return false;
                    }
                }
    
                // Filter by status
                if (status && rowStatus != status) {
                    return false;
                }
    
                return true; // Show the row if it matches the criteria
            }
        );
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
