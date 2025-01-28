@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
.widget-two {
    padding: 15px 15px;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
    align-items: center;
    height: 100%;
}
.widget-two.style--two {
    z-index: 1;
}
.bg--primary {
    background-color: #4634ff !important;
}
.bg--1 {
    background-color: #127681 !important;
}
.bg--17 {
    background-color: #035aa6 !important;
}
.bg--19 {
    background-color: #342ead !important;
}
</style>
<div class="row mb-4">
    <div class="col">
        <h2>Dashboard</h2>
    </div>
</div>
@if(Illuminate\Support\Facades\Session::get('staff_role') != 'Superadmin')
<div class="row mb-2">
    <div class="col-lg-6">
        <div class="card">
           <div class="card-body">
                <h5 class="card-title">Deposits</h5>
                <input class="form-control" type="text" value="{{ route('show.deposit.form') }}" id="depositLink" readonly>
                <a class="btn btn-primary mt-2" href="{{ route('show.deposit.form') }}">Deposit</a>
           </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Withdrawals</h5>
                <input class="form-control" type="text" value="{{ route('show.withdrawal.form') }}" id="withdrawalLink" readonly>
                    <a class="btn btn-primary mt-2" href="{{ route('show.withdrawal.form') }}">Withdrawal</a>
            </div>
        </div>
    </div>
</div>
@endif
@if($userId != '3')

<div class="col-12">
<div class="row py-4">

                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-wallet"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">${{ $usddeposit }}</h3>
                            <p class="text-white">Deposits</p>
                        </div>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--1">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">${{$usdwithdrawal}}</h3>
                            <p class="text-white">Withdrawals</p>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-wallet"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">€{{$eurdeposit}}</h3>
                            <p class="text-white">Deposits</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--1">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">€{{$eurwithdrawal}}</h3>
                            <p class="text-white">Withdrawals</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-exchange-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{$transactions}}</h3>
                            <p class="text-white">Transactions</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-money-bill-wave-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">${{$moneyout}}</h3>
                            <p class="text-white">Total Money Out</p>
                        </div>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                
                <!-- dashboard-w1 end -->


                <!-- dashboard-w1 end -->

            </div>
</div>
@endif
<!--<div class="row mt-4">-->
<!--    <div class="col-lg-12">-->
<!--        <div class="card">-->
<!--            <div class="card-body">-->
<!--                <h5 class="card-title">Overall Statistics</h5>-->
<!--                <div class="row">-->
<!--                    <div class="row g-3">-->
                        <!-- Total Deposits -->
<!--                        <div class="col-12 col-sm-6 col-md-4 col-lg-2" style="width:20% !important;">-->
<!--                            <div class="card shadow-sm">-->
<!--                                <div class="card-body">-->
<!--                                    <h6 class="text-muted">Total Deposits</h6>-->
<!--                                    <div class="d-flex align-items-center">-->
<!--                                        <h3 class="text-success">${{$totalDeposit}}</h3>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <!-- Total Withdrawals -->
<!--                        <div class="col-12 col-sm-6 col-md-4 col-lg-2" style="width:20% !important;">-->
<!--                            <div class="card shadow-sm">-->
<!--                                <div class="card-body">-->
<!--                                    <h6 class="text-muted">Total Withdrawals</h6>-->
<!--                                    <div class="d-flex align-items-center">-->
<!--                                        <h3 class="text-danger">$220.00</h3>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <!-- Fees Owed -->
<!--                        <div class="col-12 col-sm-6 col-md-4 col-lg-2" style="width:20% !important;">-->
<!--                            <div class="card shadow-sm">-->
<!--                                <div class="card-body">-->
<!--                                    <h6 class="text-muted">Fees Owed</h6>-->
<!--                                    <div class="d-flex align-items-center">-->
<!--                                        <h3 class="text-danger">$0.00</h3>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <!-- Disputes -->
<!--                        <div class="col-12 col-sm-6 col-md-4 col-lg-2" style="width:20% !important;">-->
<!--                            <div class="card shadow-sm">-->
<!--                                <div class="card-body">-->
<!--                                    <h6 class="text-muted">Disputes</h6>-->
<!--                                    <div class="d-flex align-items-center">-->
<!--                                        <h3 class="text-danger">$0.00</h3>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->

                        <!-- Cash Flow -->
<!--                        <div class="col-12 col-sm-6 col-md-4 col-lg-2" style="width:20% !important;">-->
<!--                            <div class="card shadow-sm">-->
<!--                                <div class="card-body">-->
<!--                                    <h6 class="text-muted">Cash Flow</h6>-->
<!--                                    <div class="d-flex align-items-center">-->
<!--                                        <h3 class="text-success">$6,216.00</h3>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
<canvas id="dashboardChart"></canvas>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var chartElement = document.getElementById('dashboardChart');

    if (chartElement) {
        var ctx = chartElement.getContext('2d');
        var dashboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!}, // Week labels
                datasets: [
                    {
                        label: 'Deposits',
                        data: {!! json_encode($depositData) !!}, // Deposit data for each week
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // Light blue
                        borderColor: 'rgba(54, 162, 235, 1)', // Darker blue for borders
                        borderWidth: 1
                    },
                    {
                        label: 'Withdrawals',
                        data: {!! json_encode($withdrawData) !!}, // Withdrawal data for each week
                        backgroundColor: 'rgba(255, 99, 132, 0.6)', // Light red
                        borderColor: 'rgba(255, 99, 132, 1)', // Darker red for borders
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true // Start the y-axis from 0
                    }
                }
            }
        });
    } else {
        console.error('Canvas element with id "dashboardChart" not found');
    }
</script>

<script>

  function copyToClipboard(elementId) {
      const copyText = document.getElementById(elementId);
      if (navigator.clipboard) {
          navigator.clipboard.writeText(copyText.value)
              .then(() => alert('Link copied to clipboard!'))
              .catch(err => console.error('Failed to copy: ', err));
      } else {
          copyText.select();
          document.execCommand("copy");
          alert("Link copied to clipboard!");
      }
  }
</script>

@endsection

