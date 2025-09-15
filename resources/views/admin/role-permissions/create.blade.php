@extends('layouts.admin')

@section('title', 'Assign Permissions to Role')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assign Permissions to Role</h3>
                    <div class="card-tools">
                        <a href="{{ route('role-permissions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Role Permissions
                        </a>
                    </div>
                </div>
                <form action="{{ route('role-permissions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="role_id">Select Role</label>
                            <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                                <option value="">Choose a role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="permission_ids">Select Permissions</label>
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
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple permissions.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Assign Permissions
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
