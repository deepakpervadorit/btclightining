<html >
<head>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Checkbook.io script in head -->
    <script src="https://app.checkbook.io/embed/vcc.js"></script>
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
</head>
<body style="background-color:#f3f4f6">
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="#">Cumbo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @php
                        $staffId = session('staff_id');
                    @endphp
                    @if($staffId == '3')
                        <li class="nav-item"><a class="nav-link" href="{{ route('deposit') }}">Deposit</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('withdrawal') }}">Withdrawal</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('staff.index') }}">Staff</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('disputes') }}">Disputes</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('service_provider') }}">Service Provider</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Payment Options
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.stripe.keys') }}">Stripe</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.square.keys') }}">Square</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.checkbook.keys') }}">Checkbook</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">Roles and Permission</a></li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Users
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @if($staffId == '3')
                                <li><a class="dropdown-item" href="{{ route('user.index') }}">Users</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.checkbookusers') }}">Checkbook Users</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('user.virtualcard') }}">Virtual Card</a></li>
                                <li><a class="dropdown-item" href="{{ route('user.checkbook_usersbyid') }}">Checkbook Account</a></li>
                            @endif
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="d-flex">
                <span class="nav-item"><button type="button" class="btn text-success btn-sm">Admin</button></span>
                <span class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn text-danger btn-sm">Logout</button>
                    </form>
                </span>
            </div>
        </div>
    </nav>
    <hr class="my-0" style="border-color: #f3f4f6; opacity: 1;">
    
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>