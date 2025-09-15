<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WalletController extends Controller
{
    public function __construct()
    {
        // Apply middleware
        $this->middleware('auth');
        $this->middleware('verified')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource (Admin only).
     */
    public function index()
    {
        // Only admins can view all wallets
        $this->authorize('viewAny', Wallet::class);

        $wallets = Wallet::with('user')
            ->latest()
            ->paginate(20);
            
        return view('wallets.index', compact('wallets'));
    }

    /**
     * Display the user's wallets.
     */
    public function myWallets()
    {
        $wallets = Wallet::where('user_id', auth()->id())
            ->latest()
            ->get();
            
        $supportedCurrencies = ['USD', 'EUR', 'GBP', 'BTC', 'ETH', 'LTC', 'BNB'];
        
        return view('wallets.my-wallets', compact('wallets', 'supportedCurrencies'));
    }

    /**
     * Show the form for creating a new wallet.
     */
    public function create()
    {
        $supportedCurrencies = ['USD', 'EUR', 'GBP', 'BTC', 'ETH', 'LTC', 'BNB'];
        
        return view('wallets.create', compact('supportedCurrencies'));
    }

    /**
     * Store a newly created wallet in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency' => [
                'required',
                'string',
                'max:10',
                Rule::in(['USD', 'EUR', 'GBP', 'BTC', 'ETH', 'LTC', 'BNB']),
                Rule::unique('wallets')->where(function ($query) {
                    return $query->where('user_id', auth()->id());
                })
            ],
        ]);

        try {
            DB::beginTransaction();

            // Check if user already has a wallet in this currency
            $existingWallet = Wallet::where('user_id', auth()->id())
                ->where('currency', $validated['currency'])
                ->first();

            if ($existingWallet) {
                return redirect()->back()
                    ->with('error', 'You already have a wallet in this currency.');
            }

            Wallet::create([
                'user_id' => auth()->id(),
                'currency' => $validated['currency'],
                'balance' => 0,
                'is_locked' => false,
            ]);

            DB::commit();

            return redirect()->route('wallets.my-wallets')
                ->with('success', 'Wallet created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create wallet: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified wallet.
     */
    public function show(Wallet $wallet)
    {
        $this->authorize('view', $wallet);

        // Load wallet with transactions and user
        $wallet->load(['user', 'transactions' => function($query) {
            $query->latest()->limit(20);
        }]);

        return view('wallets.show', compact('wallet'));
    }

    /**
     * Show the form for editing the wallet (Admin only).
     */
    public function edit(Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        return view('wallets.edit', compact('wallet'));
    }

    /**
     * Update the specified wallet in storage (Admin only).
     */
    public function update(Request $request, Wallet $wallet)
    {
        $this->authorize('update', $wallet);

        $validated = $request->validate([
            'balance' => 'required|numeric|min:0',
            'is_locked' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            $wallet->update($validated);

            DB::commit();

            return redirect()->route('wallets.show', $wallet)
                ->with('success', 'Wallet updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update wallet: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified wallet from storage.
     */
    public function destroy(Wallet $wallet)
    {
        $this->authorize('delete', $wallet);

        // Cannot delete wallet with balance
        if ($wallet->balance > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete wallet with balance. Please withdraw funds first.');
        }

        try {
            DB::beginTransaction();

            $wallet->delete();

            DB::commit();

            return redirect()->route('wallets.my-wallets')
                ->with('success', 'Wallet deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete wallet: ' . $e->getMessage());
        }
    }

    /**
     * Lock a wallet (Admin only).
     */
    public function lock(Wallet $wallet)
    {
        $this->authorize('adminActions', $wallet);

        try {
            DB::beginTransaction();

            $wallet->update(['is_locked' => true]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Wallet locked successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to lock wallet: ' . $e->getMessage());
        }
    }

    /**
     * Unlock a wallet (Admin only).
     */
    public function unlock(Wallet $wallet)
    {
        $this->authorize('adminActions', $wallet);

        try {
            DB::beginTransaction();

            $wallet->update(['is_locked' => false]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Wallet unlocked successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to unlock wallet: ' . $e->getMessage());
        }
    }

    /**
     * Get wallet balance.
     */
    public function getBalance(Wallet $wallet)
    {
        $this->authorize('view', $wallet);

        return response()->json([
            'balance' => $wallet->balance,
            'currency' => $wallet->currency,
            'formatted_balance' => number_format($wallet->balance, 8)
        ]);
    }

    /**
     * Transfer funds between wallets.
     */
    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'from_wallet_id' => 'required|exists:wallets,id',
            'to_wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.00000001',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $fromWallet = Wallet::findOrFail($validated['from_wallet_id']);
            $toWallet = Wallet::findOrFail($validated['to_wallet_id']);

            // Authorization check
            $this->authorize('transfer', $fromWallet);

            // Check if wallets are locked
            if ($fromWallet->is_locked) {
                throw new \Exception('Source wallet is locked.');
            }

            if ($toWallet->is_locked) {
                throw new \Exception('Destination wallet is locked.');
            }

            // Check sufficient balance
            if ($fromWallet->balance < $validated['amount']) {
                throw new \Exception('Insufficient balance.');
            }

            // Perform transfer
            $fromWallet->decrement('balance', $validated['amount']);
            $toWallet->increment('balance', $validated['amount']);

            // Record transaction (you'll need to create a Transaction model)
            // Transaction::createTransfer($fromWallet, $toWallet, $validated['amount'], $validated['description']);

            DB::commit();

            return redirect()->route('wallets.my-wallets')
                ->with('success', 'Transfer completed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Transfer failed: ' . $e->getMessage());
        }
    }

    /**
     * Show transfer form.
     */
    public function showTransferForm()
    {
        $wallets = Wallet::where('user_id', auth()->id())
            ->where('is_locked', false)
            ->get();
            
        return view('wallets.transfer', compact('wallets'));
    }

    /**
     * Get wallets for a specific user (Admin only).
     */
    public function getUserWallets($userId)
    {
        $this->authorize('viewAny', Wallet::class);

        $user = User::with('wallets')->findOrFail($userId);
        
        return view('wallets.user-wallets', compact('user'));
    }
}