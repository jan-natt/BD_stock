<?php

namespace App\Http\Controllers;

use App\Models\IPOApplication;
use App\Models\IPO;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class IPOApplicationController extends Controller
{
    public function __construct()
    {
        // Apply middleware
        $this->middleware('auth');
        $this->middleware('verified')->except(['index', 'show']);
        $this->middleware('admin')->except(['index', 'show', 'create', 'store', 'myApplications']);
    }

    /**
     * Display a listing of the resource (Admin only).
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', IPOApplication::class);

        $query = IPOApplication::with(['user', 'ipo', 'ipo.issueManager']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('ipo_id') && $request->ipo_id) {
            $query->where('ipo_id', $request->ipo_id);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('ipo', function($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                      ->orWhere('symbol', 'like', "%{$search}%");
                });
            });
        }

        $applications = $query->latest()->paginate(25);
        
        $ipos = IPO::all();
        $users = User::all();
        $statuses = ['pending', 'allocated', 'rejected'];

        return view('ipo-applications.index', compact('applications', 'ipos', 'users', 'statuses'));
    }

    /**
     * Display user's IPO applications.
     */
    public function myApplications(Request $request)
    {
        $query = IPOApplication::with(['ipo', 'ipo.issueManager'])
            ->where('user_id', auth()->id());

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('ipo_id') && $request->ipo_id) {
            $query->where('ipo_id', $request->ipo_id);
        }

        $applications = $query->latest()->paginate(15);
        
        $ipos = IPO::where('status', 'open')->get();
        $statuses = ['pending', 'allocated', 'rejected'];

        return view('ipo-applications.my-applications', compact('applications', 'ipos', 'statuses'));
    }

    /**
     * Show the form for creating a new application.
     */
    public function create(Request $request)
    {
        $ipoId = $request->ipo_id;
        $ipo = null;

        if ($ipoId) {
            $ipo = IPO::findOrFail($ipoId);
        }

        $ipos = IPO::where('status', 'open')
            ->where('ipo_start', '<=', now())
            ->where('ipo_end', '>=', now())
            ->get();

        return view('ipo-applications.create', compact('ipos', 'ipo'));
    }

    /**
     * Store a newly created application in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ipo_id' => 'required|exists:ipos,id',
            'applied_shares' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $ipo = IPO::findOrFail($validated['ipo_id']);
            $user = auth()->user();

            // Validate IPO is open for applications
            if (!$ipo->isActive()) {
                throw new \Exception('IPO is not currently open for applications.');
            }

            // Validate share quantity
            if ($validated['applied_shares'] > $ipo->available_shares) {
                throw new \Exception('Not enough shares available. Only ' . $ipo->available_shares . ' shares remaining.');
            }

            if ($validated['applied_shares'] < $ipo->min_subscription) {
                throw new \Exception("Minimum application is {$ipo->min_subscription} shares.");
            }

            if ($validated['applied_shares'] > $ipo->max_subscription) {
                throw new \Exception("Maximum application is {$ipo->max_subscription} shares.");
            }

            // Check if user already applied
            $existingApplication = IPOApplication::where('ipo_id', $ipo->id)
                ->where('user_id', $user->id)
                ->first();

            if ($existingApplication) {
                throw new \Exception('You have already applied for this IPO.');
            }

            // Calculate total cost
            $totalCost = $ipo->price_per_share * $validated['applied_shares'];

            // Check user balance
            $userWallet = Wallet::where('user_id', $user->id)
                ->where('currency', 'USD') // Assuming USD for IPO applications
                ->first();

            if (!$userWallet || $userWallet->balance < $totalCost) {
                throw new \Exception('Insufficient balance for IPO application.');
            }

            // Lock the funds
            $userWallet->decrement('balance', $totalCost);

            // Create application
            $application = IPOApplication::create([
                'user_id' => $user->id,
                'ipo_id' => $ipo->id,
                'applied_shares' => $validated['applied_shares'],
                'total_cost' => $totalCost,
                'status' => 'pending',
                'applied_at' => now(),
            ]);

            // Update available shares
            $ipo->decrement('available_shares', $validated['applied_shares']);

            // Create transaction record
            Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $userWallet->id,
                'type' => 'ipo_application',
                'amount' => $totalCost,
                'fee' => 0,
                'status' => 'completed',
                'description' => "IPO Application: {$ipo->company_name} ({$ipo->symbol}) - {$validated['applied_shares']} shares",
            ]);

            DB::commit();

            return redirect()->route('ipo-applications.my-applications')
                ->with('success', 'IPO application submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to submit application: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified application.
     */
    public function show(IPOApplication $application)
    {
        $this->authorize('view', $application);

        $application->load(['user', 'ipo', 'ipo.issueManager']);

        return view('ipo-applications.show', compact('application'));
    }

    /**
     * Allocate shares to an application (Admin only).
     */
    public function allocate(Request $request, IPOApplication $application)
    {
        $this->authorize('allocate', $application);

        $validated = $request->validate([
            'allocated_shares' => 'required|integer|min:0|max:' . $application->applied_shares,
        ]);

        try {
            DB::beginTransaction();

            if ($application->status !== 'pending') {
                throw new \Exception('Only pending applications can be allocated.');
            }

            $ipo = $application->ipo;
            $user = $application->user;

            if ($validated['allocated_shares'] > 0) {
                // Allocate shares
                $application->update([
                    'allocated_shares' => $validated['allocated_shares'],
                    'status' => 'allocated'
                ]);

                // Create wallet for the IPO shares if it doesn't exist
                $shareWallet = Wallet::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'currency' => $ipo->symbol
                    ],
                    [
                        'balance' => 0,
                        'is_locked' => false
                    ]
                );

                // Add allocated shares to wallet
                $shareWallet->increment('balance', $validated['allocated_shares']);

                // Refund for unallocated shares
                $unallocatedShares = $application->applied_shares - $validated['allocated_shares'];
                if ($unallocatedShares > 0) {
                    $refundAmount = $unallocatedShares * $ipo->price_per_share;
                    
                    $usdWallet = Wallet::where('user_id', $user->id)
                        ->where('currency', 'USD')
                        ->first();

                    if ($usdWallet) {
                        $usdWallet->increment('balance', $refundAmount);

                        // Create refund transaction
                        Transaction::create([
                            'user_id' => $user->id,
                            'wallet_id' => $usdWallet->id,
                            'type' => 'ipo_refund',
                            'amount' => $refundAmount,
                            'fee' => 0,
                            'status' => 'completed',
                            'description' => "IPO Partial Refund: {$ipo->company_name} - {$validated['allocated_shares']} shares allocated",
                        ]);
                    }
                }

                // Create transaction for share allocation
                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $shareWallet->id,
                    'type' => 'ipo_allocation',
                    'amount' => $validated['allocated_shares'],
                    'fee' => 0,
                    'status' => 'completed',
                    'description' => "IPO Share Allocation: {$ipo->company_name} - {$validated['allocated_shares']} shares",
                ]);

            } else {
                // Reject application
                $this->rejectApplication($application);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Application processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to process application: ' . $e->getMessage());
        }
    }

    /**
     * Reject an application (Admin only).
     */
    public function reject(IPOApplication $application)
    {
        $this->authorize('reject', $application);

        try {
            DB::beginTransaction();

            if ($application->status !== 'pending') {
                throw new \Exception('Only pending applications can be rejected.');
            }

            $this->rejectApplication($application);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Application rejected successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to reject application: ' . $e->getMessage());
        }
    }

    /**
     * Reject application and refund funds.
     */
    protected function rejectApplication(IPOApplication $application)
    {
        $ipo = $application->ipo;
        $user = $application->user;

        // Refund the full amount
        $usdWallet = Wallet::where('user_id', $user->id)
            ->where('currency', 'USD')
            ->first();

        if ($usdWallet) {
            $usdWallet->increment('balance', $application->total_cost);

            // Create refund transaction
            Transaction::create([
                'user_id' => $user->id,
                'wallet_id' => $usdWallet->id,
                'type' => 'ipo_refund',
                'amount' => $application->total_cost,
                'fee' => 0,
                'status' => 'completed',
                'description' => "IPO Application Refund: {$ipo->company_name} - Application rejected",
            ]);
        }

        // Return shares to available pool
        $ipo->increment('available_shares', $application->applied_shares);

        // Update application status
        $application->update(['status' => 'rejected']);
    }

    /**
     * Cancel application (User only).
     */
    public function cancel(IPOApplication $application)
    {
        $this->authorize('cancel', $application);

        try {
            DB::beginTransaction();

            if ($application->status !== 'pending') {
                throw new \Exception('Only pending applications can be cancelled.');
            }

            $ipo = $application->ipo;

            // Refund the full amount
            $usdWallet = Wallet::where('user_id', auth()->id())
                ->where('currency', 'USD')
                ->first();

            if ($usdWallet) {
                $usdWallet->increment('balance', $application->total_cost);

                // Create refund transaction
                Transaction::create([
                    'user_id' => auth()->id(),
                    'wallet_id' => $usdWallet->id,
                    'type' => 'ipo_refund',
                    'amount' => $application->total_cost,
                    'fee' => 0,
                    'status' => 'completed',
                    'description' => "IPO Application Cancellation: {$ipo->company_name}",
                ]);
            }

            // Return shares to available pool
            $ipo->increment('available_shares', $application->applied_shares);

            // Update application status
            $application->update(['status' => 'rejected']); // Mark as rejected for cancellation

            DB::commit();

            return redirect()->route('ipo-applications.my-applications')
                ->with('success', 'Application cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel application: ' . $e->getMessage());
        }
    }

    /**
     * Bulk process applications for an IPO (Admin only).
     */
    public function bulkProcess(Request $request, IPO $ipo)
    {
        $this->authorize('bulkProcess', IPOApplication::class);

        $validated = $request->validate([
            'allocation_method' => ['required', Rule::in(['proportional', 'first_come', 'lottery'])],
            'allocation_percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            if ($ipo->status !== 'open') {
                throw new \Exception('IPO must be open for processing.');
            }

            $pendingApplications = $ipo->applications()->where('status', 'pending')->get();

            if ($pendingApplications->isEmpty()) {
                throw new \Exception('No pending applications to process.');
            }

            $totalAppliedShares = $pendingApplications->sum('applied_shares');
            $availableShares = $ipo->available_shares;

            if ($totalAppliedShares <= $availableShares) {
                // Allocate all applications fully
                foreach ($pendingApplications as $application) {
                    $application->update([
                        'allocated_shares' => $application->applied_shares,
                        'status' => 'allocated'
                    ]);
                    $this->allocateSharesToWallet($application);
                }
            } else {
                // Handle oversubscription
                $this->processOversubscription($ipo, $pendingApplications, $validated['allocation_method'], $validated['allocation_percentage']);
            }

            // Close IPO if all shares are allocated
            if ($ipo->available_shares == 0) {
                $ipo->update(['status' => 'closed']);
            }

            DB::commit();

            return redirect()->route('ipos.show', $ipo)
                ->with('success', 'Applications processed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to process applications: ' . $e->getMessage());
        }
    }

    /**
     * Process oversubscribed IPO applications.
     */
    protected function processOversubscription(IPO $ipo, $applications, $method, $percentage)
    {
        $availableShares = $ipo->available_shares;

        switch ($method) {
            case 'proportional':
                foreach ($applications as $application) {
                    $allocationRatio = $availableShares / $applications->sum('applied_shares');
                    $allocatedShares = floor($application->applied_shares * $allocationRatio);
                    
                    $application->update([
                        'allocated_shares' => $allocatedShares,
                        'status' => 'allocated'
                    ]);
                    $this->allocateSharesToWallet($application);
                }
                break;

            case 'first_come':
                $applications = $applications->sortBy('applied_at');
                foreach ($applications as $application) {
                    if ($availableShares <= 0) break;

                    $allocatedShares = min($application->applied_shares, $availableShares);
                    $application->update([
                        'allocated_shares' => $allocatedShares,
                        'status' => 'allocated'
                    ]);
                    $this->allocateSharesToWallet($application);
                    $availableShares -= $allocatedShares;
                }
                break;

            case 'lottery':
                $applications = $applications->shuffle();
                foreach ($applications as $application) {
                    if ($availableShares <= 0) break;

                    $allocatedShares = min($application->applied_shares, $availableShares);
                    $application->update([
                        'allocated_shares' => $allocatedShares,
                        'status' => 'allocated'
                    ]);
                    $this->allocateSharesToWallet($application);
                    $availableShares -= $allocatedShares;
                }
                break;
        }
    }

    /**
     * Allocate shares to user's wallet.
     */
    protected function allocateSharesToWallet(IPOApplication $application)
    {
        $ipo = $application->ipo;
        $user = $application->user;

        $shareWallet = Wallet::firstOrCreate(
            [
                'user_id' => $user->id,
                'currency' => $ipo->symbol
            ],
            [
                'balance' => 0,
                'is_locked' => false
            ]
        );

        $shareWallet->increment('balance', $application->allocated_shares);

        // Refund unallocated shares
        $unallocatedShares = $application->applied_shares - $application->allocated_shares;
        if ($unallocatedShares > 0) {
            $refundAmount = $unallocatedShares * $ipo->price_per_share;
            
            $usdWallet = Wallet::where('user_id', $user->id)
                ->where('currency', 'USD')
                ->first();

            if ($usdWallet) {
                $usdWallet->increment('balance', $refundAmount);

                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $usdWallet->id,
                    'type' => 'ipo_refund',
                    'amount' => $refundAmount,
                    'fee' => 0,
                    'status' => 'completed',
                    'description' => "IPO Partial Refund: {$ipo->company_name}",
                ]);
            }
        }

        // Create allocation transaction
        Transaction::create([
            'user_id' => $user->id,
            'wallet_id' => $shareWallet->id,
            'type' => 'ipo_allocation',
            'amount' => $application->allocated_shares,
            'fee' => 0,
            'status' => 'completed',
            'description' => "IPO Share Allocation: {$ipo->company_name}",
        ]);
    }

    /**
     * Export applications (Admin only).
     */
    public function export(Request $request)
    {
        $this->authorize('export', IPOApplication::class);

        $validated = $request->validate([
            'ipo_id' => 'required|exists:ipos,id',
            'status' => 'nullable|in:pending,allocated,rejected',
        ]);

        $applications = IPOApplication::with(['user', 'ipo'])
            ->where('ipo_id', $validated['ipo_id'])
            ->when($validated['status'] ?? false, function($query, $status) {
                return $query->where('status', $status);
            })
            ->get();

        $fileName = "ipo-applications-export-" . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Application ID', 'User', 'Email', 'IPO', 'Symbol',
                'Applied Shares', 'Allocated Shares', 'Total Cost', 
                'Status', 'Applied At'
            ]);

            // Add data rows
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->user->name,
                    $application->user->email,
                    $application->ipo->company_name,
                    $application->ipo->symbol,
                    $application->applied_shares,
                    $application->allocated_shares ?? 0,
                    $application->total_cost,
                    $application->status,
                    $application->applied_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get application statistics.
     */
    public function statistics(IPO $ipo)
    {
        $this->authorize('statistics', IPOApplication::class);

        $stats = [
            'total_applications' => $ipo->applications()->count(),
            'pending_applications' => $ipo->applications()->where('status', 'pending')->count(),
            'allocated_applications' => $ipo->applications()->where('status', 'allocated')->count(),
            'rejected_applications' => $ipo->applications()->where('status', 'rejected')->count(),
            'total_applied_shares' => $ipo->applications()->sum('applied_shares'),
            'total_allocated_shares' => $ipo->applications()->sum('allocated_shares'),
            'total_funds_collected' => $ipo->applications()->sum('total_cost'),
            'average_application_size' => $ipo->applications()->avg('applied_shares'),
        ];

        return view('ipo-applications.statistics', compact('ipo', 'stats'));
    }
}