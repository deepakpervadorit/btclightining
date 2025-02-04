@extends('layouts.app')

@section('content')

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <!-- Add Checkbook.io script in head -->

    @if(env('CHECKBOOK_API_KEY'))
    <script src="https://app.checkbook.io/embed/vcc.js"></script>
    @else
    <script src="https://sandbox.checkbook.io/embed/vcc.js"></script>
    @endif
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <style>
        /* Custom styles for better card presentation */
        body {
            margin: 0px;
            color: rgb(15, 15, 15);
            font-family: Inter, Helvetica, Arial, sans-serif;
            font-weight: 400;
            font-size: 1rem;
            line-height: 1.5;
            background-color: transparent !important;
        }
        .card-container {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin: 2rem auto;
            max-width: 800px;
        }

        .card-wrapper {
            background: linear-gradient(145deg, #ffffff, #f3f4f6);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        #container {
            width: 100%;
            max-width: 600px;
            margin: auto;
            height: 400px; /* Fixed height for the card container */
            overflow: hidden; /* Hide overflow content */
        }

        #container iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: transparent;
        }

        .page-title {
            color: #1f2937;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .card-header-custom {
            background: transparent;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
    </style>


    <!-- Virtual Card Container -->
     <section class="bg-light py-5">
        <div class="container">
                <div id="container" style="width: 600px; margin: auto"></div>
                <h3 class="text-center">Total Payment: {{ $balance }}</h3>
                <div class="card border-0 shadow">
                        <div class="card-body">
                <table id="transactionTable" class="table">
    <thead>
        <tr>
            <th>Amount</th>
            <th>Type</th>
            <th>Payment At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
        <tr>
            <td>{{ $transaction['amount'] }}</td>
            <td>{{ $transaction['type'] }}</td>
            <td>{{ $transaction['created_ts'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
                        </div>
                </div>
            </div>
        </div>

    <!-- Add Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script>
    $(document).ready(function() {
        $('#transactionTable').DataTable();
    });
</script>
    <!-- Checkbook Integration Script -->
    <script type="text/javascript">
        // document.addEventListener('DOMContentLoaded', function() {
            checkbook.account.ViewVcc({
                env: 'PROD',
                card_id: '{{$vccid}}',
                signature: '{{$jwt}}'
            }).render('#container');
        // });
    </script>
@endsection
