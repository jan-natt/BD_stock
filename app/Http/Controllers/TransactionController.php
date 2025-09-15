<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
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
    public function index(Request $request)
    {
        // Only admins can view all transactions
        $this->authorize('viewAny', Transaction::class);

        $query = Transaction::with(['user', 'wallet']);

        // Apply filters
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('wallet_id') && $request->wallet_id) {
            $query->where('wallet_id', $request->wallet_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('transaction_hash', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->latest()->paginate(25);
        
        $users = User::select('id', 'name', 'email')->get();
        $wallets = Wallet::select('id', 'currency', 'user_id')->with('user')->get();
        
        $transactionTypes = [
            'deposit', 'withdrawal', 'trade', 'fee', 
            'referral_bonus', 'staking_reward'
        ];
        
        $statuses = ['pending', 'completed', 'failed'];

        return view('transactions.index', compact(
            'transactions', 'users', 'wallets', 'transactionTypes', 'statuses'
        ));
    }

    /**
     * Display the user's transactions.
     */
    public function myTransactions(Request $request)
    {
        $query = Transaction::with('wallet')
            ->where('user_id', auth()->id());

        // Apply filters
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('wallet_id') && $request->wallet_id) {
            $query->where('wallet_id', $request->wallet_id);
        }

        $transactions = $query->latest()->paginate(20);
        
        $wallets = Wallet::where('user_id', auth()->id())->get();
        $transactionTypes = [
            'deposit', 'withdrawal', 'trade', 'fee', 
            'referral_bonus', 'staking_reward'
        ];
        
        $statuses = ['pending', 'completed', 'failed'];

        return view('transactions.my-transactions', compact(
            'transactions', 'wallets', 'transactionTypes', 'statuses'
        ));
    }

    /**
     * Show the form for creating a new transaction (Admin only).
     */
    public function create()
    {
        $this->authorize('create', Transaction::class);

        $users = User::select('id', 'name', 'email')->get();
        $wallets = Wallet::select('id', 'currency', 'user_id')->with('user')->get();
        $transactionTypes = [
            'deposit', 'withdrawal', 'trade', 'fee', 
            'referral_bonus', 'staking_reward'
        ];

        return view('transactions.create', compact('users', 'wallets', 'transactionTypes'));
    }

    /**
     * Store a newly created transaction (Admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'wallet_id' => 'required|exists:wallets,id',
            'type' => ['required', Rule::in(['deposit','withdrawal','trade','fee','referral_bonus','staking_reward'])],
            'amount' => 'required|numeric|min:0.00000001',
            'fee' => 'nullable|numeric|min:0',
            'status' => ['required', Rule::in(['pending','completed','failed'])],
            'transaction_hash' => 'nullable|string|max:255|unique:transactions,transaction_hash',
        ]);

        // Verify wallet belongs to user
        $wallet = Wallet::find($validated['wallet_id']);
        if ($wallet->user_id != $validated['user_id']) {
            return redirect()->back()
                ->with('error', 'Selected wallet does not belong to the specified user.');
        }

        try {
            DB::beginTransaction();

            $transaction = Transaction::create($validated);

            // If transaction is completed, update wallet balance
            if ($validated['status'] === 'completed') {
                $this->updateWalletBalance($transaction);
            }

            DB::commit();

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaction created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        $transaction->load(['user', 'wallet']);

        return view('transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the transaction (Admin only).
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $users = User::select('id', 'name', 'email')->get();
        $wallets = Wallet::select('id', 'currency', 'user_id')->with('user')->get();
        $transactionTypes = [
            'deposit', 'withdrawal', 'trade', 'fee', 
            'referral_bonus', 'staking_reward'
        ];
        $statuses = ['pending', 'completed', 'failed'];

        return view('transactions.edit', compact(
            'transaction', 'users', 'wallets', 'transactionTypes', 'statuses'
        ));
    }

    /**
     * Update the specified transaction (Admin only).
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'wallet_id' => 'required|exists:wallets,id',
            'type' => ['required', Rule::in(['deposit','withdrawal','trade','fee','referral_bonus','staking_reward'])],
            'amount' => 'required|numeric|min:0.00000001',
            'fee' => 'nullable|numeric|min:0',
            'status' => ['required', Rule::in(['pending','completed','failed'])],
            'transaction_hash' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('transactions', 'transaction_hash')->ignore($transaction->id)
            ],
        ]);

        // Verify wallet belongs to user
        $wallet = Wallet::find($validated['wallet_id']);
        if ($wallet->user_id != $validated['user_id']) {
            return redirect()->back()
                ->with('error', 'Selected wallet does not belong to the specified user.');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $transaction->status;
            $transaction->update($validated);

            // Handle wallet balance updates based on status changes
            $this->handleStatusChange($transaction, $oldStatus);

            DB::commit();

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaction updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update transaction: ' . $e->getMessage());
        }
    }

    /**
     * Update transaction status (Admin only).
     */
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending','completed','failed'])]
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $transaction->status;
            $transaction->update(['status' => $validated['status']]);

            // Handle wallet balance updates based on status changes
            $this->handleStatusChange($transaction, $oldStatus);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Transaction status updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update transaction status: ' . $e->getMessage());
        }
    }

    /**
     * Handle wallet balance updates when transaction status changes.
     */
    protected function handleStatusChange(Transaction $transaction, $oldStatus)
    {
        $wallet = $transaction->wallet;

        // Revert old transaction effect if it was completed
        if ($oldStatus === 'completed') {
            $this->revertTransactionEffect($transaction);
        }

        // Apply new transaction effect if status is now completed
        if ($transaction->status === 'completed') {
            $this->applyTransactionEffect($transaction);
        }
    }

    /**
     * Apply transaction effect to wallet balance.
     */
    protected function applyTransactionEffect(Transaction $transaction)
    {
        $wallet = $transaction->wallet;

        switch ($transaction->type) {
            case 'deposit':
            case 'referral_bonus':
            case 'staking_reward':
                $wallet->increment('balance', $transaction->amount);
                break;

            case 'withdrawal':
            case 'fee':
                $wallet->decrement('balance', $transaction->amount + $transaction->fee);
                break;

            case 'trade':
                // Trade transactions might need special handling based on your business logic
                break;
        }
    }

    /**
     * Revert transaction effect from wallet balance.
     */
    protected function revertTransactionEffect(Transaction $transaction)
    {
        $wallet = $transaction->wallet;

        switch ($transaction->type) {
            case 'deposit':
            case 'referral_bonus':
            case 'staking_reward':
                $wallet->decrement('balance', $transaction->amount);
                break;

            case 'withdrawal':
            case 'fee':
                $wallet->increment('balance', $transaction->amount + $transaction->fee);
                break;

            case 'trade':
                // Trade transactions might need special handling based on your business logic
                break;
        }
    }

    /**
     * Export transactions (Admin only).
     */
    public function export(Request $request)
    {
        $this->authorize('export', Transaction::class);

        $validated = $request->validate([
            'format' => ['required', Rule::in(['csv', 'json'])],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $query = Transaction::with(['user', 'wallet']);

        if ($request->has('start_date') && $request->start_date) {
            $query->where('created_at', '>=', $validated['start_date']);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('created_at', '<=', $validated['end_date'] . ' 23:59:59');
        }

        $transactions = $query->get();

        if ($validated['format'] === 'csv') {
            return $this->exportToCsv($transactions);
        }

        return $this->exportToJson($transactions);
    }

    /**
     * Export transactions to CSV.
     */
    protected function exportToCsv($transactions)
    {
        $fileName = 'transactions-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'User', 'Wallet', 'Type', 'Amount', 'Fee', 
                'Status', 'Transaction Hash', 'Created At'
            ]);

            // Add data rows
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->user->name,
                    $transaction->wallet->currency,
                    $transaction->type,
                    $transaction->amount,
                    $transaction->fee,
                    $transaction->status,
                    $transaction->transaction_hash,
                    $transaction->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export transactions to JSON.
     */
    protected function exportToJson($transactions)
    {
        $fileName = 'transactions-' . date('Y-m-d') . '.json';
        
        return response()->json($transactions->toArray())
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }

    /**
     * Get transaction statistics (Admin only).
     */
    public function statistics()
    {
        $this->authorize('viewAny', Transaction::class);

        $stats = [
            'total_transactions' => Transaction::count(),
            'total_volume' => Transaction::where('status', 'completed')->sum('amount'),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'failed_transactions' => Transaction::where('status', 'failed')->count(),
        ];

        // Daily volume for last 30 days
        $dailyVolume = Transaction::where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(amount) as volume')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Transaction types distribution
        $typeDistribution = Transaction::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        return view('transactions.statistics', compact('stats', 'dailyVolume', 'typeDistribution'));
    }
}