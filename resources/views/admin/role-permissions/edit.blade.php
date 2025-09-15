@extends('layouts.admin')

@section('title', 'Edit Permissions for Role: {{ $role->name }}')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Permissions for Role: {{ $role->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('role-permissions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Role Permissions
                        </a>
                        <a href="{{ route('roles.show', $role) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View Role
                        </a>
                    </div>
                </div>
                <form action="{{ route('role-permissions.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Current Permissions</label>
                            <div class="mb-3">
                                @if($role->permissions->count() > 0)
                                    @foreach($role->permissions as $perm)
                                        <span class="badge badge-primary mr-1">{{ $perm->permission_name }}</span>
                                    @endforeach
                                @else
                                    <p class="text-muted">No permissions assigned.</p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="permission_ids">Update Permissions</label>
                            <select name="permission_ids[]" id="permission_ids" class="form-control @error('permission_ids') is-invalid @enderror" multiple>
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->id }}" {{ $role->permissions->contains($permission->id) ? 'selected' : '' }}>
                                        {{ $permission->permission_name }} - {{ $permission->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('permission_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple permissions. Leave empty to remove all permissions.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Permissions
                        </button>
                        <a href="{{ route('role-permissions.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select2 if available, or use basic select
        // For now, basic multiple select
    });
</script>
@endsection
