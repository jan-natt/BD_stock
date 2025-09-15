@extends('layouts.admin')

@section('title', 'Role Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Role Details: {{ $role->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Roles
                        </a>
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Role
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $role->id }}</td>
                            </tr>
                            <tr>
                                <th>Role Name</th>
                                <td>{{ $role->name }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $role->description ?: 'No description' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $role->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $role->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
