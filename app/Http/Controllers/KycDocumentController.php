<?php

namespace App\Http\Controllers;

use App\Models\KYCDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KYCDocumentController extends Controller
{
    public function __construct()
    {
        // Apply middleware
        $this->middleware('auth');
        $this->middleware('verified.kyc')->only(['create', 'store']);
        $this->middleware('admin')->only(['verify', 'reject', 'index']);
    }

    /**
     * Display a listing of the resource (Admin only).
     */
    public function index()
    {
        $documents = KYCDocument::with(['user', 'verifier'])
            ->latest()
            ->paginate(10);
            
        return view('kyc_documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $documentTypes = ['passport', 'id_card', 'driver_license', 'utility_bill', 'bank_statement'];
        
        return view('kyc_documents.create', compact('documentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => ['required', Rule::in(['passport', 'id_card', 'driver_license', 'utility_bill', 'bank_statement'])],
            'document_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Store the file
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('kyc_documents', $fileName, 'public');
            
            $validated['document_file'] = $filePath;
        }

        // Create KYC document record
        $validated['user_id'] = auth()->id();
        
        KYCDocument::create($validated);

        return redirect()->route('profile.index')
            ->with('success', 'KYC document uploaded successfully. Waiting for verification.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KYCDocument $document)
    {
        // Authorization - users can only view their own documents
        if (auth()->id() !== $document->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('kyc_documents.show', compact('document'));
    }

    /**
     * Verify a KYC document (Admin only).
     */
    public function verify(Request $request, KYCDocument $document)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $document->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'notes' => $request->notes,
        ]);

        // Update user's KYC status if this is their first verified document
        $user = $document->user;
        if ($user->kyc_status !== 'verified') {
            $user->update(['kyc_status' => 'verified']);
        }

        return redirect()->route('kyc-documents.index')
            ->with('success', 'KYC document verified successfully.');
    }

    /**
     * Reject a KYC document (Admin only).
     */
    public function reject(Request $request, KYCDocument $document)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $document->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'notes' => $request->rejection_reason,
        ]);

        // Update user's KYC status to rejected
        $document->user->update(['kyc_status' => 'rejected']);

        return redirect()->route('kyc-documents.index')
            ->with('success', 'KYC document rejected successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KYCDocument $document)
    {
        // Authorization - users can only delete their own pending documents
        if (auth()->id() !== $document->user_id || $document->status !== 'pending') {
            abort(403, 'Unauthorized action.');
        }

        // Delete the file
        Storage::disk('public')->delete($document->document_file);
        
        $document->delete();

        return redirect()->route('profile.index')
            ->with('success', 'KYC document deleted successfully.');
    }
}