@php
    use Illuminate\Support\Facades\DB;
@endphp
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
                        <h5 class="">Deposits</h5>
                    </h1>
                    <p class="mt-2 text-sm text-gray-700">Received successful payments</p>
                </div>
            </div>
            <div class="mb-3">
                <div class="row">
                    <div class="col-md-5">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="date" id="start_date" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="date" id="end_date" class="form-control">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="filter_button" class="btn btn-primary mt-2">Filter</button>
                    </div>
                </div>
            </div>
            <table id="example" class="table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Method</th>
                        <!--<th>Username</th>-->
                        <th>Game Id</th>
                        <!--<th>Provider</th>-->
                        <th>Amount Paid</th>
                        <th>Currency</th>
                        <th>Status</th>
                        <th>Created At</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposits as $deposit)
                    <tr>
                        @php
                        $user = DB::table('staff')->where('id', $deposit->user_id)->first();
                        if(!$user && $deposit->merchant_id) {
                            $user = DB::table('staff')->where('id', $deposit->merchant_id)->first();
                        }
                        if(!$user && $deposit->user_id) {
                            $user = DB::table('users')->where('id', $deposit->user_id)->first();
                        }
                        
                        $displayName = $user->name ?? 'merchant';
                        @endphp
                        
                        <td>{{ $deposit->id }}</td>
                        
                        @if($deposit->payment_method == "Try Speed")
                        <td>Cashapp Crypto</td>
                        @else
                        <td>{{ $deposit->payment_method }}</td>
                        @endif
                        
                        {{--<td>{{ $displayName }}</td>--}}
                        <td>{{ $deposit->game_id ?? '' }}</td>
                        
                        @php
                        $providers = collect();
                        if(!empty($deposit->server)) {
                            $serverIds = explode(',', $deposit->server);
                            $providers = DB::table('games')->whereIn('id', $serverIds)->get();
                        }
                        @endphp
                        
                        {{--<td>
                            @foreach($providers as $provider)
                                {{ $provider->game }}@if(!$loop->last), @endif
                            @endforeach
                        </td>--}}
                        
                        <td>{{ $deposit->amount }}</td>
                        <td>{{ $deposit->currency }}</td>
                        <td>
                            @if($deposit->status == 'Completed' || $deposit->status == 'Y')
                                <button class="btn btn-success">Paid</button>
                            @else
                                <button class="btn btn-danger">UnPaid</button>
                            @endif
                        </td>
                        <td>{{ $deposit->created_at }}</td>
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
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> --}}
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
            "order": [[ 0, "asc" ]], // Change '8' to the index of the column you want to sort by (0-based index)
            dom: 'Bfrtip', // Include Buttons in the DOM
            buttons: [
                {
                    extend: 'csvHtml5',
                    title: 'Deposits', // Title for the CSV file
                     // Heading for the table
                    exportOptions: {
                        columns: ':visible' // Specify which columns to export
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: 'Deposits', // Title for the Excel file
                     // Heading for the table
                    exportOptions: {
                        columns: ':visible' // Specify which columns to export
                    }
                } // Export as Excel
            ]
        });

        $('#filter_button').click(function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            // Apply custom filtering to the DataTable
            table.draw();
        });

        // Custom filtering function to filter by date range
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                var date = data[6]; // Assuming the 'Created At' column is at index 6 (0-based index)

                if (startDate && endDate) {
                    // Convert to date format
                    var start = new Date(startDate);
                    var end = new Date(endDate);
                    var rowDate = new Date(date);
                    start.setHours(0, 0, 0, 0);
                    end.setHours(23, 59, 59, 999);
                    rowDate.setHours(0, 0, 0, 0);
                    console.log(rowDate);
                    console.log(start);
                    console.log(end);
                    // Check if the date is within the range
                    if (rowDate >= start && rowDate <= end) {
                        return true;
                    }
                    return false;
                }
                return true; // If no date range is selected, show all records
            }
        );
    });
</script>
