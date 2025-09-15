<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        // Apply middleware - only admins can manage payment methods
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::latest()->paginate(10);
        return view('payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $methodTypes = [
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'crypto' => 'Cryptocurrency',
            'paypal' => 'PayPal',
            'skrill' => 'Skrill',
            'neteller' => 'Neteller',
            'perfect_money' => 'Perfect Money',
            'webmoney' => 'WebMoney',
            'mobile_money' => 'Mobile Money'
        ];

        return view('payment-methods.create', compact('methodTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'method_name' => 'required|string|max:255|unique:payment_methods,method_name',
            'method_type' => ['required', Rule::in([
                'bank_transfer', 'credit_card', 'debit_card', 'crypto', 
                'paypal', 'skrill', 'neteller', 'perfect_money', 
                'webmoney', 'mobile_money'
            ])],
            'status' => 'required|boolean',
            'details' => 'nullable|array',
            'details.min_amount' => 'nullable|numeric|min:0',
            'details.max_amount' => 'nullable|numeric|min:0|gt:details.min_amount',
            'details.fee_percentage' => 'nullable|numeric|min:0|max:100',
            'details.fee_fixed' => 'nullable|numeric|min:0',
            'details.currencies' => 'nullable|array',
            'details.currencies.*' => 'string|max:3',
            'details.instructions' => 'nullable|string|max:1000',
            'details.account_number' => 'nullable|string|max:255',
            'details.account_name' => 'nullable|string|max:255',
            'details.bank_name' => 'nullable|string|max:255',
            'details.routing_number' => 'nullable|string|max:255',
            'details.iban' => 'nullable|string|max:255',
            'details.swift_code' => 'nullable|string|max:255',
            'details.wallet_address' => 'nullable|string|max:255',
            'details.api_key' => 'nullable|string|max:255',
            'details.api_secret' => 'nullable|string|max:255',
            'details.merchant_id' => 'nullable|string|max:255',
            'details.callback_url' => 'nullable|url|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Prepare details array
            $details = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'details.') === 0) {
                    $detailKey = str_replace('details.', '', $key);
                    $details[$detailKey] = $value;
                }
            }

            // Clean up empty values
            $details = array_filter($details, function($value) {
                return !is_null($value) && $value !== '';
            });

            PaymentMethod::create([
                'method_name' => $validated['method_name'],
                'method_type' => $validated['method_type'],
                'status' => $validated['status'],
                'details' => !empty($details) ? $details : null,
            ]);

            DB::commit();

            return redirect()->route('payment-methods.index')
                ->with('success', 'Payment method created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create payment method: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        $methodTypes = [
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'crypto' => 'Cryptocurrency',
            'paypal' => 'PayPal',
            'skrill' => 'Skrill',
            'neteller' => 'Neteller',
            'perfect_money' => 'Perfect Money',
            'webmoney' => 'WebMoney',
            'mobile_money' => 'Mobile Money'
        ];

        return view('payment-methods.edit', compact('paymentMethod', 'methodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'method_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_methods', 'method_name')->ignore($paymentMethod->id)
            ],
            'method_type' => ['required', Rule::in([
                'bank_transfer', 'credit_card', 'debit_card', 'crypto', 
                'paypal', 'skrill', 'neteller', 'perfect_money', 
                'webmoney', 'mobile_money'
            ])],
            'status' => 'required|boolean',
            'details' => 'nullable|array',
            'details.min_amount' => 'nullable|numeric|min:0',
            'details.max_amount' => 'nullable|numeric|min:0|gt:details.min_amount',
            'details.fee_percentage' => 'nullable|numeric|min:0|max:100',
            'details.fee_fixed' => 'nullable|numeric|min:0',
            'details.currencies' => 'nullable|array',
            'details.currencies.*' => 'string|max:3',
            'details.instructions' => 'nullable|string|max:1000',
            'details.account_number' => 'nullable|string|max:255',
            'details.account_name' => 'nullable|string|max:255',
            'details.bank_name' => 'nullable|string|max:255',
            'details.routing_number' => 'nullable|string|max:255',
            'details.iban' => 'nullable|string|max:255',
            'details.swift_code' => 'nullable|string|max:255',
            'details.wallet_address' => 'nullable|string|max:255',
            'details.api_key' => 'nullable|string|max:255',
            'details.api_secret' => 'nullable|string|max:255',
            'details.merchant_id' => 'nullable|string|max:255',
            'details.callback_url' => 'nullable|url|max:255',
        ]);

        try {
            DB::beginTransaction();

            // Prepare details array
            $details = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'details.') === 0) {
                    $detailKey = str_replace('details.', '', $key);
                    $details[$detailKey] = $value;
                }
            }

            // Clean up empty values
            $details = array_filter($details, function($value) {
                return !is_null($value) && $value !== '';
            });

            $paymentMethod->update([
                'method_name' => $validated['method_name'],
                'method_type' => $validated['method_type'],
                'status' => $validated['status'],
                'details' => !empty($details) ? $details : null,
            ]);

            DB::commit();

            return redirect()->route('payment-methods.index')
                ->with('success', 'Payment method updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update payment method: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            DB::beginTransaction();

            // Check if payment method is being used in transactions
            if ($this->isPaymentMethodInUse($paymentMethod)) {
                return redirect()->route('payment-methods.index')
                    ->with('error', 'Cannot delete payment method. It is being used in transactions.');
            }

            $paymentMethod->delete();

            DB::commit();

            return redirect()->route('payment-methods.index')
                ->with('success', 'Payment method deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('payment-methods.index')
                ->with('error', 'Failed to delete payment method: ' . $e->getMessage());
        }
    }

    /**
     * Check if payment method is being used in transactions.
     */
    protected function isPaymentMethodInUse(PaymentMethod $paymentMethod): bool
    {
        // You'll need to implement this based on your transaction structure
        // For example, if you have a transactions table with payment_method_id
        // return Transaction::where('payment_method_id', $paymentMethod->id)->exists();
        
        return false; // Placeholder
    }

    /**
     * Toggle payment method status.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        try {
            DB::beginTransaction();

            $paymentMethod->update([
                'status' => !$paymentMethod->status
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Payment method status updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update payment method status: ' . $e->getMessage());
        }
    }

    /**
     * Get active payment methods for API.
     */
    public function getActiveMethods()
    {
        $paymentMethods = PaymentMethod::where('status', true)
            ->get()
            ->map(function($method) {
                return [
                    'id' => $method->id,
                    'name' => $method->method_name,
                    'type' => $method->method_type,
                    'min_amount' => $method->details['min_amount'] ?? 0,
                    'max_amount' => $method->details['max_amount'] ?? null,
                    'fee_percentage' => $method->details['fee_percentage'] ?? 0,
                    'fee_fixed' => $method->details['fee_fixed'] ?? 0,
                    'currencies' => $method->details['currencies'] ?? [],
                ];
            });

        return response()->json($paymentMethods);
    }

    /**
     * Show payment method configuration guide.
     */
    public function showConfigurationGuide($type)
    {
        $guides = [
            'bank_transfer' => [
                'title' => 'Bank Transfer Configuration',
                'fields' => [
                    'account_number' => 'Bank Account Number',
                    'account_name' => 'Account Holder Name',
                    'bank_name' => 'Bank Name',
                    'routing_number' => 'Routing Number (US)',
                    'iban' => 'IBAN (International)',
                    'swift_code' => 'SWIFT/BIC Code',
                    'instructions' => 'Transfer Instructions'
                ]
            ],
            'crypto' => [
                'title' => 'Cryptocurrency Configuration',
                'fields' => [
                    'wallet_address' => 'Wallet Address',
                    'network' => 'Blockchain Network',
                    'min_confirmations' => 'Minimum Confirmations'
                ]
            ],
            'credit_card' => [
                'title' => 'Credit Card Configuration',
                'fields' => [
                    'merchant_id' => 'Merchant ID',
                    'api_key' => 'API Key',
                    'api_secret' => 'API Secret',
                    'callback_url' => 'Callback URL'
                ]
            ],
            // Add more guides for other payment methods
        ];

        if (!array_key_exists($type, $guides)) {
            return redirect()->back()
                ->with('error', 'Configuration guide not available for this payment method.');
        }

        return view('payment-methods.configuration-guide', [
            'guide' => $guides[$type],
            'methodType' => $type
        ]);
    }
}