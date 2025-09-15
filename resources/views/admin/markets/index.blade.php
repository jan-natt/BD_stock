@extends('layouts.admin')

@section('title', 'Markets Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Markets Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('markets.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create Market
                        </a>
                        <div class="input-group input-group-sm ml-2">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="btn-group">
                                <a href="?market_type=all" class="btn btn-outline-primary {{ request('market_type') == 'all' || !request('market_type') ? 'active' : '' }}">
                                    All Types
                                </a>
                                @foreach($marketTypes as $key => $label)
                                    <a href="?market_type={{ $key }}" class="btn btn-outline-secondary {{ request('market_type') == $key ? 'active' : '' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Symbol</th>
                                    <th>Base Asset</th>
                                    <th>Quote Asset</th>
                                    <th>Type</th>
                                    <th>Min Order Size</th>
                                    <th>Max Order Size</th>
                                    <th>Fee Rate</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($markets as $market)
                                    <tr>
                                        <td>{{ $market->id }}</td>
                                        <td>{{ $market->symbol }}</td>
                                        <td>{{ $market->base_asset }}</td>
                                        <td>{{ $market->quote_asset }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($market->market_type) }}</span>
                                        </td>
                                        <td>{{ $market->min_order_size }}</td>
                                        <td>{{ $market->max_order_size ?: 'Unlimited' }}</td>
                                        <td>{{ $market->formatted_fee_rate }}</td>
                                        <td>
                                            @if($market->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('markets.show', $market) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('markets.edit', $market) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('markets.destroy', $market) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No markets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $markets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.querySelector('input[name="table_search"]');
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const searchValue = this.value.trim();
                if (searchValue) {
                    window.location.href = `?search=${encodeURIComponent(searchValue)}`;
                } else {
                    window.location.href = '?';
                }
            }
        });
    });
</script>
@endsection
