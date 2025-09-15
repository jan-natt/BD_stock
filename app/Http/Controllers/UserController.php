<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Apply middleware to specific methods
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('admin')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userTypes = ['admin', 'buyer', 'seller', 'broker', 'investor', 'bank', 'issue_manager'];
        $kycStatuses = ['pending', 'verified', 'rejected'];
        
        return view('users.create', compact('userTypes', 'kycStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => ['required', Rule::in(['admin','buyer','seller','broker','investor','bank','issue_manager'])],
            'kyc_status' => ['nullable', Rule::in(['pending','verified','rejected'])],
            'referral_code' => 'nullable|string',
            'referred_by' => 'nullable|exists:users,id',
            'two_factor_enabled' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $userTypes = ['admin', 'buyer', 'seller', 'broker', 'investor', 'bank', 'issue_manager'];
        $kycStatuses = ['pending', 'verified', 'rejected'];
        
        return view('users.edit', compact('user', 'userTypes', 'kycStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'user_type' => ['required', Rule::in(['admin','buyer','seller','broker','investor','bank','issue_manager'])],
            'kyc_status' => ['required', Rule::in(['pending','verified','rejected'])],
            'referral_code' => 'nullable|string',
            'referred_by' => 'nullable|exists:users,id',
            'two_factor_enabled' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}