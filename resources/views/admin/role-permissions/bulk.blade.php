@extends('layouts.admin')

@section('title', 'Bulk Assign Permissions to Roles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bulk Assign Permissions to Roles</h3>
                    <div class="card-tools">
                        <a href="{{ route('role-permissions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Role Permissions
                        </a>
                    </div>
                </div>
                <form action="{{ route('role-permissions.bulk-assign') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="role_ids">Select Roles</label>
                            <select name="role_ids[]" id="role_ids" class="form-control @error('role_ids') is-invalid @enderror" multiple required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ in_array($role->id, old('role_ids', [])) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple roles.</small>
                        </div>
                        <div class="form-group">
                            <label for="permission_ids">Select Permissions to Assign</label>
                            <select name="permission_ids[]" id="permission_ids" class="form-control @error('permission_ids') is-invalid @enderror" multiple required>
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->id }}" {{ in_array($permission->id, old('permission_ids', [])) ? 'selected' : '' }}>
                                        {{ $permission->permission_name }} - {{ $permission->description }}
                                    </option>
                                @endforeach
                            </select>
                            @error('permission_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple permissions. These will be added to the selected roles without removing existing permissions.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-tasks"></i> Bulk Assign
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
