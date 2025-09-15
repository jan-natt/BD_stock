@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Admin Dashboard</h1>
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_users']) }}</h3>
                            <p>Total Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_trades']) }}</h3>
                            <p>Total Trades</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <a href="{{ route('trades.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>${{ number_format($stats['revenue'], 2) }}</h3>
                            <p>Total Revenue</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <a href="{{ route('transactions.index') }}" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ number_format($stats['total_users']) }}</h3>
                            <p>Unverified Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <a href="{{ route('users.index') }}?status=unverified" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-block">
                                        <i class="fas fa-user-plus"></i> Add User
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('assets.create') }}" class="btn btn-success btn-block">
                                        <i class="fas fa-plus-circle"></i> Add Asset
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('markets.create') }}" class="btn btn-info btn-block">
                                        <i class="fas fa-chart-line"></i> Add Market
                                    </a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{ route('system-settings.index') }}" class="btn btn-warning btn-block">
                                        <i class="fas fa-cogs"></i> System Settings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Users</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $user->name }}</strong><br>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                            <div>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">View All Users</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Recent Trades</h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @foreach(\App\Models\Trade::with(['buyer', 'seller'])->latest()->take(5)->get() as $trade)
                                    <li class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $trade->symbol }}</strong><br>
                                                <small class="text-muted">{{ $trade->buyer->name }} → {{ $trade->seller->name }}</small>
                                            </div>
                                            <div>
                                                <small class="text-muted">{{ $trade->trade_time->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('trades.index') }}" class="btn btn-sm btn-primary">View All Trades</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
