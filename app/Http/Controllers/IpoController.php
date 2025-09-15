<?php

namespace App\Http\Controllers;

use App\Models\IPO;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class IPOController extends Controller
{
    public function __construct()
    {
        // Apply middleware
        $this->middleware('auth');
        $this->middleware('verified')->except(['index', 'show', 'public']);
        $this->middleware('admin')->except(['index', 'show', 'public', 'subscribe', 'mySubscriptions']);
    }

    /**
     * Display a listing of the resource (Admin only).
     */
    public function index(Request $request)
    {
        $query = IPO::with(['issueManager', 'subscriptions.user']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('issue_manager_id') && $request->issue_manager_id) {
            $query->where('issue_manager_id', $request->issue_manager_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('symbol', 'like', "%{$search}%")
                  ->orWhereHas('issueManager', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $ipos = $query->latest()->paginate(20);
        
        $issueManagers = User::where('user_type', 'issue_manager')->get();
        $statuses = ['open', 'closed', 'cancelled'];

        return view('ipos.index', compact('ipos', 'issueManagers', 'statuses'));
    }

    /**
     * Display public IPOs (available to all authenticated users).
     */
    public function public(Request $request)
    {
        $query = IPO::with(['issueManager', 'subscriptions'])
            ->where('status', 'open')
            ->where('ipo_start', '<=', now())
            ->where('ipo_end', '>=', now());

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('company_name', 'like', "%{$request->search}%")
                  ->orWhere('symbol', 'like', "%{$request->search}%");
            });
        }

        $ipos = $query->latest()->paginate(12);

        return view('ipos.public', compact('ipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $issueManagers = User::where('user_type', 'issue_manager')->get();

        return view('ipos.create', compact('issueManagers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'symbol' => 'required|string|max:20|unique:ipos,symbol',
            'issue_manager_id' => 'required|exists:users,id',
            'price_per_share' => 'required|numeric|min:0.01',
            'total_shares' => 'required|integer|min:1',
            'ipo_start' => 'required|date|after:now',
            'ipo_end' => 'required|date|after:ipo_start',
            'description' => 'nullable|string|max:1000',
            'min_subscription' => 'nullable|integer|min:1',
            'max_subscription' => 'nullable|integer|min:1|gt:min_subscription',
        ]);

        try {
            DB::beginTransaction();

            $ipo = IPO::create([
                'company_name' => $validated['company_name'],
                'symbol' => $validated['symbol'],
                'issue_manager_id' => $validated['issue_manager_id'],
                'price_per_share' => $validated['price_per_share'],
                'total_shares' => $validated['total_shares'],
                'available_shares' => $validated['total_shares'],
                'ipo_start' => $validated['ipo_start'],
                'ipo_end' => $validated['ipo_end'],
                'status' => 'open',
                'description' => $validated['description'] ?? null,
                'min_subscription' => $validated['min_subscription'] ?? 1,
                'max_subscription' => $validated['max_subscription'] ?? $validated['total_shares'],
            ]);

            // Create asset for the IPO
            $this->createIPOAsset($ipo);

            DB::commit();

            return redirect()->route('ipos.show', $ipo)
                ->with('success', 'IPO created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create IPO: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Create asset for the IPO.
     */
    protected function createIPOAsset(IPO $ipo)
    {
        // You'll need to create or update your Asset model to handle IPOs
        // This is a simplified example
        /*
        Asset::create([
            'symbol' => $ipo->symbol,
            'name' => $ipo->company_name,
            'type' => 'ipo',
            'precision' => 0, // Whole shares
            'status' => false, // Not active until IPO completes
        ]);
        */
    }

    /**
     * Display the specified resource.
     */
    public function show(IPO $ipo)
    {
        $ipo->load(['issueManager', 'subscriptions.user', 'subscriptions' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $userSubscription = $ipo->subscriptions()
            ->where('user_id', auth()->id())
            ->first();

        $totalSubscriptions = $ipo->subscriptions()->count();
        $totalSubscribedShares = $ipo->subscriptions()->sum('shares_subscribed');
        $subscriptionProgress = $ipo->total_shares > 0 ? ($totalSubscribedShares / $ipo->total_shares) * 100 : 0;

        return view('ipos.show', compact('ipo', 'userSubscription', 'totalSubscriptions', 'totalSubscribedShares', 'subscriptionProgress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IPO $ipo)
    {
        $this->authorize('update', $ipo);

        $issueManagers = User::where('user_type', 'issue_manager')->get();

        return view('ipos.edit', compact('ipo', 'issueManagers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IPO $ipo)
    {
        $this->authorize('update', $ipo);

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'symbol' => [
                'required',
                'string',
                'max:20',
                Rule::unique('ipos', 'symbol')->ignore($ipo->id)
            ],
            'issue_manager_id' => 'required|exists:users,id',
            'price_per_share' => 'required|numeric|min:0.01',
            'ipo_start' => 'required|date',
            'ipo_end' => 'required|date|after:ipo_start',
            'description' => 'nullable|string|max:1000',
            'min_subscription' => 'nullable|integer|min:1',
            'max_subscription' => 'nullable|integer|min:1|gt:min_subscription',
            'status' => ['required', Rule::in(['open', 'closed', 'cancelled'])]
        ]);

        try {
            DB::beginTransaction();

            // Prevent certain updates if IPO has subscriptions
            if ($ipo->subscriptions()->exists() && $ipo->symbol !== $validated['symbol']) {
                throw new \Exception('Cannot change symbol after subscriptions have been made.');
            }

            $ipo->update($validated);

            DB::commit();

            return redirect()->route('ipos.show', $ipo)
                ->with('success', 'IPO updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update IPO: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Subscribe to an IPO.
     */
    public function subscribe(Request $request, IPO $ipo)
    {
        $validated = $request->validate([
            'shares' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Validate IPO is open for subscription
            if ($ipo->status !== 'open') {
                throw new \Exception('IPO is not open for subscription.');
            }

            if (now() < $ipo->ipo_start || now() > $ipo->ipo_end) {
                throw new \Exception('IPO subscription period has not started or has ended.');
            }

            // Validate share quantity
            if ($validated['shares'] > $ipo->available_shares) {
                throw new \Exception('Not enough shares available.');
            }

            if ($validated['shares'] < $ipo->min_subscription) {
                throw new \Exception("Minimum subscription is {$ipo->min_subscription} shares.");
            }

            if ($validated['shares'] > $ipo->max_subscription) {
                throw new \Exception("Maximum subscription is {$ipo->max_subscription} shares.");
            }

            // Check if user already subscribed
            $existingSubscription = $ipo->subscriptions()
                ->where('user_id', auth()->id())
                ->first();

            if ($existingSubscription) {
                throw new \Exception('You have already subscribed to this IPO.');
            }

            // Calculate total cost
            $totalCost = $ipo->price_per_share * $validated['shares'];

            // Check user balance
            $userWallet = Wallet::where('user_id', auth()->id())
                ->where('currency', 'USD') // Assuming USD for IPO subscriptions
                ->first();

            if (!$userWallet || $userWallet->balance < $totalCost) {
                throw new \Exception('Insufficient balance for subscription.');
            }

            // Lock the funds
            $userWallet->decrement('balance', $totalCost);

            // Create subscription
            $subscription = $ipo->subscriptions()->create([
                'user_id' => auth()->id(),
                'shares_subscribed' => $validated['shares'],
                'amount_paid' => $totalCost,
                'status' => 'subscribed',
            ]);

            // Update available shares
            $ipo->decrement('available_shares', $validated['shares']);

            // Create transaction record
            Transaction::create([
                'user_id' => auth()->id(),
                'wallet_id' => $userWallet->id,
                'type' => 'ipo_subscription',
                'amount' => $totalCost,
                'fee' => 0,
                'status' => 'completed',
                'description' => "IPO Subscription: {$ipo->company_name} ({$ipo->symbol}) - {$validated['shares']} shares",
            ]);

            DB::commit();

            return redirect()->route('ipos.show', $ipo)
                ->with('success', 'Successfully subscribed to IPO.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to subscribe: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Cancel subscription.
     */
    public function cancelSubscription(IPO $ipo)
    {
        try {
            DB::beginTransaction();

            $subscription = $ipo->subscriptions()
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if ($subscription->status !== 'subscribed') {
                throw new \Exception('Cannot cancel this subscription.');
            }

            // Return funds
            $userWallet = Wallet::where('user_id', auth()->id())
                ->where('currency', 'USD')
                ->first();

            if ($userWallet) {
                $userWallet->increment('balance', $subscription->amount_paid);
            }

            // Update subscription status
            $subscription->update(['status' => 'cancelled']);

            // Return shares to available pool
            $ipo->increment('available_shares', $subscription->shares_subscribed);

            // Create refund transaction
            Transaction::create([
                'user_id' => auth()->id(),
                'wallet_id' => $userWallet->id,
                'type' => 'ipo_refund',
                'amount' => $subscription->amount_paid,
                'fee' => 0,
                'status' => 'completed',
                'description' => "IPO Subscription Refund: {$ipo->company_name} ({$ipo->symbol})",
            ]);

            DB::commit();

            return redirect()->route('ipos.show', $ipo)
                ->with('success', 'Subscription cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    /**
     * Close IPO and allocate shares.
     */
    public function close(IPO $ipo)
    {
        $this->authorize('close', $ipo);

        try {
            DB::beginTransaction();

            if ($ipo->status !== 'open') {
                throw new \Exception('IPO is not open.');
            }

            // Check if IPO period has ended
            if (now() < $ipo->ipo_end) {
                throw new \Exception('IPO period has not ended yet.');
            }

            // Get all subscriptions
            $subscriptions = $ipo->subscriptions()->where('status', 'subscribed')->get();

            if ($subscriptions->isEmpty()) {
                // No subscriptions, cancel IPO
                $ipo->update(['status' => 'cancelled']);
                
                DB::commit();
                return redirect()->back()
                    ->with('info', 'IPO cancelled due to no subscriptions.');
            }

            // Calculate total subscribed shares
            $totalSubscribed = $subscriptions->sum('shares_subscribed');

            if ($totalSubscribed < $ipo->total_shares) {
                // Under-subscribed - allocate proportionally or use another method
                $this->allocateShares($ipo, $subscriptions, $totalSubscribed);
            } else {
                // Over-subscribed - allocate proportionally
                $this->allocateShares($ipo, $subscriptions, $totalSubscribed);
            }

            // Update IPO status
            $ipo->update(['status' => 'closed']);

            // Activate the asset for trading
            $this->activateIPOAsset($ipo);

            DB::commit();

            return redirect()->route('ipos.show', $ipo)
                ->with('success', 'IPO closed successfully. Shares allocated to investors.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to close IPO: ' . $e->getMessage());
        }
    }

    /**
     * Allocate shares to investors.
     */
    protected function allocateShares(IPO $ipo, $subscriptions, $totalSubscribed)
    {
        foreach ($subscriptions as $subscription) {
            if ($totalSubscribed <= $ipo->total_shares) {
                // No oversubscription, allocate all requested shares
                $allocatedShares = $subscription->shares_subscribed;
            } else {
                // Oversubscription, allocate proportionally
                $allocationRatio = $ipo->total_shares / $totalSubscribed;
                $allocatedShares = floor($subscription->shares_subscribed * $allocationRatio);
            }

            // Update subscription with allocated shares
            $subscription->update([
                'shares_allocated' => $allocatedShares,
                'status' => 'allocated'
            ]);

            // Create wallet for the IPO shares if it doesn't exist
            $shareWallet = Wallet::firstOrCreate(
                [
                    'user_id' => $subscription->user_id,
                    'currency' => $ipo->symbol
                ],
                [
                    'balance' => 0,
                    'is_locked' => false
                ]
            );

            // Add allocated shares to wallet
            $shareWallet->increment('balance', $allocatedShares);

            // Refund for unallocated shares if any
            if ($allocatedShares < $subscription->shares_subscribed) {
                $refundAmount = ($subscription->shares_subscribed - $allocatedShares) * $ipo->price_per_share;
                
                $usdWallet = Wallet::where('user_id', $subscription->user_id)
                    ->where('currency', 'USD')
                    ->first();

                if ($usdWallet) {
                    $usdWallet->increment('balance', $refundAmount);

                    // Create refund transaction
                    Transaction::create([
                        'user_id' => $subscription->user_id,
                        'wallet_id' => $usdWallet->id,
                        'type' => 'ipo_refund',
                        'amount' => $refundAmount,
                        'fee' => 0,
                        'status' => 'completed',
                        'description' => "IPO Partial Refund: {$ipo->company_name} - {$allocatedShares} shares allocated",
                    ]);
                }
            }

            // Create transaction for share allocation
            Transaction::create([
                'user_id' => $subscription->user_id,
                'wallet_id' => $shareWallet->id,
                'type' => 'ipo_allocation',
                'amount' => $allocatedShares,
                'fee' => 0,
                'status' => 'completed',
                'description' => "IPO Share Allocation: {$ipo->company_name} - {$allocatedShares} shares",
            ]);
        }
    }

    /**
     * Activate IPO asset for trading.
     */
    protected function activateIPOAsset(IPO $ipo)
    {
        // Update asset status to active
        /*
        Asset::where('symbol', $ipo->symbol)->update(['status' => true]);
        
        // Create market for the IPO stock
        Market::create([
            'base_asset' => $ipo->symbol,
            'quote_asset' => 'USD',
            'market_type' => 'spot',
            'min_order_size' => 1,
            'max_order_size' => 1000000,
            'fee_rate' => 0.1,
            'status' => true,
        ]);
        */
    }

    /**
     * Cancel IPO and refund all subscriptions.
     */
    public function cancel(IPO $ipo)
    {
        $this->authorize('cancel', $ipo);

        try {
            DB::beginTransaction();

            if ($ipo->status !== 'open') {
                throw new \Exception('Only open IPOs can be cancelled.');
            }

            // Refund all subscriptions
            $subscriptions = $ipo->subscriptions()->where('status', 'subscribed')->get();

            foreach ($subscriptions as $subscription) {
                $userWallet = Wallet::where('user_id', $subscription->user_id)
                    ->where('currency', 'USD')
                    ->first();

                if ($userWallet) {
                    $userWallet->increment('balance', $subscription->amount_paid);

                    // Create refund transaction
                    Transaction::create([
                        'user_id' => $subscription->user_id,
                        'wallet_id' => $userWallet->id,
                        'type' => 'ipo_refund',
                        'amount' => $subscription->amount_paid,
                        'fee' => 0,
                        'status' => 'completed',
                        'description' => "IPO Cancellation Refund: {$ipo->company_name}",
                    ]);
                }

                $subscription->update(['status' => 'refunded']);
            }

            // Update IPO status
            $ipo->update([
                'status' => 'cancelled',
                'available_shares' => $ipo->total_shares
            ]);

            DB::commit();

            return redirect()->route('ipos.show', $ipo)
                ->with('success', 'IPO cancelled successfully. All subscriptions refunded.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel IPO: ' . $e->getMessage());
        }
    }

    /**
     * Display user's IPO subscriptions.
     */
    public function mySubscriptions(Request $request)
    {
        $subscriptions = auth()->user()->ipoSubscriptions()
            ->with(['ipo.issueManager'])
            ->latest()
            ->paginate(15);

        return view('ipos.my-subscriptions', compact('subscriptions'));
    }

    /**
     * Export IPO data (Admin only).
     */
    public function export(IPO $ipo)
    {
        $this->authorize('export', $ipo);

        $subscriptions = $ipo->subscriptions()
            ->with('user')
            ->get();

        $fileName = "ipo-{$ipo->symbol}-subscriptions-" . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($ipo, $subscriptions) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'IPO', 'Company', 'Symbol', 'Price per Share', 
                'User', 'Email', 'Shares Subscribed', 'Shares Allocated',
                'Amount Paid', 'Status', 'Subscription Date'
            ]);

            // Add data rows
            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $ipo->company_name,
                    $ipo->symbol,
                    $ipo->price_per_share,
                    $subscription->user->name,
                    $subscription->user->email,
                    $subscription->shares_subscribed,
                    $subscription->shares_allocated ?? 0,
                    $subscription->amount_paid,
                    $subscription->status,
                    $subscription->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get IPO statistics.
     */
    public function statistics()
    {
        $this->authorize('statistics', IPO::class);

        $stats = [
            'total_ipos' => IPO::count(),
            'open_ipos' => IPO::where('status', 'open')->count(),
            'closed_ipos' => IPO::where('status', 'closed')->count(),
            'cancelled_ipos' => IPO::where('status', 'cancelled')->count(),
            'total_capital_raised' => IPO::where('status', 'closed')
                ->withSum('subscriptions', 'amount_paid')
                ->get()
                ->sum('subscriptions_sum_amount_paid'),
            'upcoming_ipos' => IPO::where('status', 'open')
                ->where('ipo_start', '>', now())
                ->count(),
            'active_ipos' => IPO::where('status', 'open')
                ->where('ipo_start', '<=', now())
                ->where('ipo_end', '>=', now())
                ->count(),
        ];

        return view('ipos.statistics', compact('stats'));
    }
}