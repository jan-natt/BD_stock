@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Details: {{ $user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>User Type</th>
                                <td>{{ ucfirst($user->user_type) }}</td>
                            </tr>
                            <tr>
                                <th>KYC Status</th>
                                <td>
                                    @if($user->kyc_status === 'verified')
                                        <span class="badge badge-success">Verified</span>
                                    @elseif($user->kyc_status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif($user->kyc_status === 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-secondary">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Referral Code</th>
                                <td>{{ $user->referral_code ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Referred By</th>
                                <td>{{ $user->referredBy ? $user->referredBy->name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Two-Factor Authentication</th>
                                <td>{{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
