@extends('layouts.admin')

@section('title', 'Bulk Assign Roles to Users')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bulk Assign Roles to Users</h3>
                    <div class="card-tools">
                        <a href="{{ route('user-roles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to User Roles
                        </a>
                    </div>
                </div>
                <form action="{{ route('user-roles.bulk-assign') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="user_ids">Select Users</label>
                            <select name="user_ids[]" id="user_ids" class="form-control @error('user_ids') is-invalid @enderror" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('user_ids', [])) ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple users.</small>
                        </div>
                        <div class="form-group">
                            <label for="role_ids">Select Roles to Assign</label>
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
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple roles. These will be added to the selected users without removing existing roles.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-tasks"></i> Bulk Assign
                        </button>
                        <a href="{{ route('user-roles.index') }}" class="btn btn-secondary">
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
