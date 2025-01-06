@extends('layouts.app')

@section('title', 'Dashboard')

<!-- External CSS links -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.1.7/css/dataTables.bootstrap5.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.bootstrap5.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

@section('breadcrumb')
    Payments
@endsection
@section('content')
<div class="container mt-5">
    <h2>Payments</h2>
    <table id="depositsTable" class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Payment Method</th>
                <th scope="col">User Name</th>
                <th scope="col">Amount</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deposits as $deposit)
                <tr>
                    <td>{{ $deposit->id }}</td>
                    <td>{{ $deposit->payment_method }}</td>
                    <td>{{ $deposit->username }}</td>
                    <td>{{ $deposit->amount }}</td>
                    <td>{{ $deposit->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
<script>
    $(document).ready(function () {
        $('#depositsTable').DataTable();
    });
</script>

