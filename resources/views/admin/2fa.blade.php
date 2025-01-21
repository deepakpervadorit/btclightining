@extends('layouts.app')

@section('title', '2FA - Admin Dashboard')

@section('breadcrumb')
   2FA Authentication
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title fs-5">Two-Factor Authentication</h2>
            </div>
            <div class="card-body">
                @php
                    use Illuminate\Support\Facades\DB;

                    $staffId = session('staff_id');
                    $userId = session('user_id');
                    $model = null;
                    $type = null;

                    if ($staffId) {
                        $model = DB::table('staff')->where('id', $staffId)->first();
                        $type = 'staff';
                    } elseif ($userId) {
                        $model = DB::table('users')->where('id', $userId)->first();
                        $type = 'user';
                    }
                @endphp

                @if($model)
                    @if($model->two_factor_enabled)
                        <p>2FA is currently <strong>enabled</strong>.</p>
                        <form action="{{ route('2fa.disable') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $model->id }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" class="btn btn-danger">Disable 2FA</button>
                        </form>
                    @else
                        <p>2FA is currently <strong>disabled</strong>.</p>
                        <form action="{{ route('2fa.enable') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $model->id }}">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button type="submit" class="btn btn-primary">Enable 2FA</button>
                        </form>
                    @endif
                @else
                    <p class="text-danger">User or Staff not found.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
