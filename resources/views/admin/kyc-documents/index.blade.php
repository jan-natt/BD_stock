@extends('layouts.admin')

@section('title', 'KYC Documents Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">KYC Documents Management</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
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
                                <a href="?status=all" class="btn btn-outline-primary {{ request('status') == 'all' || !request('status') ? 'active' : '' }}">
                                    All Documents
                                </a>
                                <a href="?status=pending" class="btn btn-outline-warning {{ request('status') == 'pending' ? 'active' : '' }}">
                                    Pending Review
                                </a>
                                <a href="?status=approved" class="btn btn-outline-success {{ request('status') == 'approved' ? 'active' : '' }}">
                                    Approved
                                </a>
                                <a href="?status=rejected" class="btn btn-outline-danger {{ request('status') == 'rejected' ? 'active' : '' }}">
                                    Rejected
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Document Type</th>
                                    <th>Status</th>
                                    <th>Submitted At</th>
                                    <th>Reviewed At</th>
                                    <th>Reviewed By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                    <tr>
                                        <td>{{ $document->id }}</td>
                                        <td>
                                            <a href="{{ route('users.show', $document->user) }}">
                                                {{ $document->user->name }}
                                            </a>
                                            <br>
                                            <small class="text-muted">{{ $document->user->email }}</small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($document->document_type) }}</span>
                                        </td>
                                        <td>
                                            @if($document->status === 'pending')
                                                <span class="badge badge-warning">Pending Review</span>
                                            @elseif($document->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @elseif($document->status === 'rejected')
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $document->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($document->reviewed_at)
                                                {{ $document->reviewed_at->format('M d, Y H:i') }}
                                            @else
                                                <span class="text-muted">Not reviewed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($document->reviewedBy)
                                                {{ $document->reviewedBy->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('kyc-documents.show', $document) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            @if($document->status === 'pending')
                                                <a href="{{ route('kyc-documents.verify', $document) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-check-circle"></i> Review
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No KYC documents found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $documents->links() }}
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-file-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Documents</span>
                                    <span class="info-box-number">{{ $stats['total'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pending Review</span>
                                    <span class="info-box-number">{{ $stats['pending'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Approved</span>
                                    <span class="info-box-number">{{ $stats['approved'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rejected</span>
                                    <span class="info-box-number">{{ $stats['rejected'] }}</span>
                                </div>
                            </div>
                        </div>
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