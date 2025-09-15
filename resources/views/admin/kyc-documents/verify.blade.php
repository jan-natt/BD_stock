@extends('layouts.admin')

@section('title', 'Verify KYC Document')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Verify KYC Document</h3>
                    <div class="card-tools">
                        <a href="{{ route('kyc-documents.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to KYC Documents
                        </a>
                    </div>
                </div>
                <form action="{{ route('kyc-documents.verify', $document) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="document_type" class="form-label">Document Type</label>
                            <input type="text" id="document_type" class="form-control" value="{{ ucfirst($document->document_type) }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="user_name" class="form-label">User</label>
                            <input type="text" id="user_name" class="form-control" value="{{ $document->user->name }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="document_file" class="form-label">Document File</label>
                            <div>
                                <a href="{{ Storage::url($document->document_file) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="fas fa-file"></i> View Document
                                </a>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Verification Notes (optional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-circle"></i> Verify
                        </button>
                        <a href="{{ route('kyc-documents.reject', $document) }}" class="btn btn-danger">
                            <i class="fas fa-times-circle"></i> Reject
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
