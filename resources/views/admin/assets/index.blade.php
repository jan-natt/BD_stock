@extends('layouts.admin')

@section('title', 'Assets Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assets Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('assets.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create Asset
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
                                <a href="?type=all" class="btn btn-outline-primary {{ request('type') == 'all' || !request('type') ? 'active' : '' }}">
                                    All Types
                                </a>
                                @foreach($assetTypes as $key => $label)
                                    <a href="?type={{ $key }}" class="btn btn-outline-secondary {{ request('type') == $key ? 'active' : '' }}">
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
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Precision</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assets as $asset)
                                    <tr>
                                        <td>{{ $asset->id }}</td>
                                        <td>{{ $asset->symbol }}</td>
                                        <td>{{ $asset->name }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($asset->type) }}</span>
                                        </td>
                                        <td>{{ $asset->precision }}</td>
                                        <td>
                                            @if($asset->status)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('assets.show', $asset) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="{{ route('assets.edit', $asset) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" style="display:inline;">
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
                                        <td colspan="7" class="text-center">No assets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $assets->links() }}
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
