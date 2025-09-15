@extends('layouts.admin')

@section('title', 'Edit Roles for User: {{ $user->name }}')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Roles for User: {{ $user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('user-roles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to User Roles
                        </a>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View User
                        </a>
                    </div>
                </div>
                <form action="{{ route('user-roles.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Current Roles</label>
                            <div class="mb-3">
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge badge-primary mr-1">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <p class="text-muted">No roles assigned.</p>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role_ids">Update Roles</label>
                            <select name="role_ids[]" id="role_ids" class="form-control @error('role_ids') is-invalid @enderror" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple roles. Leave empty to remove all roles.</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Roles
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
