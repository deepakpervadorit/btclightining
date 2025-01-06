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
<div class="row mb-2">
    <div class="col-lg-12">
        <div class="card">
           <div class="card-body">
                <h5 class="card-title">User Registeration Link</h5>
                <input class="form-control" type="text" value="https://pay.cumbo.tech/{{session('staff_name')}}/register" id="depositLink" readonly>
                <a class="btn btn-primary mt-2" href="{{ route('show.deposit.form') }}">Deposit</a>
           </div>
        </div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


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

