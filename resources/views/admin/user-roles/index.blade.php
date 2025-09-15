@extends('layouts.admin')

@section('title', 'User Roles Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Roles Management</h3>
                    <div class="card-tools">
                        <a href="{{ route('user-roles.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Assign Roles
                        </a>
                        <a href="{{ route('user-roles.bulk') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-tasks"></i> Bulk Assign
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
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userRoles as $ur)
                                    <tr>
                                        <td>{{ $ur->id }}</td>
                                        <td>
                                            <a href="{{ route('users.show', $ur->user) }}">{{ $ur->user->name }} ({{ $ur->user->email }})</a>
                                        </td>
                                        <td>
                                            <a href="{{ route('roles.show', $ur->role) }}">{{ $ur->role->name }}</a>
                                        </td>
                                        <td>{{ $ur->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <form action="{{ route('user-roles.destroy', [$ur->user_id, $ur->role_id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i> Remove
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No user roles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $userRoles->links() }}
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
