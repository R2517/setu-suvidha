<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\SubscriptionPaymentOrder;
use App\Models\VleSubscription;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class SubscriptionController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function index()
    {
        $user = auth()->user();
        
        // If they already have access and are not forced here, they can just go to billing
        $hasAccess = $user->hasBillingAccess();
        $isTrialActive = $user->isBillingTrialActive();
        $trialDaysLeft = max(0, (int) now()->startOfDay()->diffInDays($user->getBillingTrialEndsAt()->copy()->startOfDay(), false));

        $walletBalance = $user->getWalletBalance();
        $razorpayKeyId = config('razorpay.key_id');

        $history = VleSubscription::where('user_id', $user->id)
            ->orderByDesc('start_date')
            ->limit(10)
            ->get();

        return view('subscription.index', compact('hasAccess', 'isTrialActive', 'trialDaysLeft', 'walletBalance', 'history', 'razorpayKeyId'));
    }

    public function activateBilling(Request $request)
    {
        $user = auth()->user();
        $price = 499.00;

        $walletBalance = $user->getWalletBalance();

        if ($walletBalance < $price) {
            return redirect()->route('subscription')->with('error', 'Insufficient wallet balance. Please recharge or pay online via Razorpay.');
        }

        return DB::transaction(function () use ($user, $price) {
            $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();
            $newBalance = $profile->wallet_balance - $price;
            $profile->update(['wallet_balance' => $newBalance]);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $price,
                'balance_after' => $newBalance,
                'description' => 'Yearly Billing License (₹499)',
            ]);

            \Illuminate\Support\Facades\Cache::forget("wallet_balance:{$user->id}");

            // Create 1-year subscription record
            VleSubscription::create([
                'user_id' => $user->id,
                'plan_id' => null,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'status' => 'active',
            ]);

            return redirect()->route('billing.redirect')->with('success', 'Yearly Billing License activated successfully! You now have full access.');
        });
    }

    // Razorpay logic follows...
    public function createPaymentOrder(Request $request)
    {
        try {
            $user = auth()->user();
            $price = 499.00;

            if (empty(config('razorpay.key_id')) || empty(config('razorpay.key_secret'))) {
                return response()->json(['success' => false, 'message' => 'Payment gateway is not configured.'], 500);
            }

            $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

            $orderData = [
                'receipt'         => 'bill_lic_' . Str::random(10),
                'amount'          => (int) ($price * 100), // paise
                'currency'        => 'INR',
                'payment_capture' => 1
            ];

            $razorpayOrder = $api->order->create($orderData);

            if (!$razorpayOrder) {
                return response()->json(['success' => false, 'message' => 'Unable to create payment order.'], 500);
            }

            $paymentOrder = SubscriptionPaymentOrder::create([
                'user_id' => $user->id,
                'plan_id' => null, // No plans anymore
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $price,
                'status' => 'created',
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'amount' => $orderData['amount'],
                'name' => 'Setu Suvidha',
                'description' => 'Yearly Billing License',
                'prefill' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact' => $user->profile->mobile ?? '',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay Billing License Order Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred.'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        try {
            $user = auth()->user();
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $api = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));
            $api->utility->verifyPaymentSignature($attributes);

            $paymentOrder = SubscriptionPaymentOrder::where('razorpay_order_id', $request->razorpay_order_id)
                ->where('status', 'created')
                ->first();

            if (!$paymentOrder) {
                return response()->json(['success' => false, 'message' => 'Payment order not found.'], 404);
            }

            return DB::transaction(function () use ($paymentOrder, $request, $user) {
                $paymentOrder->update([
                    'status' => 'paid',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                ]);

                VleSubscription::create([
                    'user_id' => $user->id,
                    'plan_id' => null,
                    'start_date' => now(),
                    'end_date' => now()->addYear(),
                    'status' => 'active',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment successful! Billing License activated.',
                    'redirect' => route('billing.redirect')
                ]);
            });

        } catch (\Exception $e) {
            Log::error('Razorpay Billing License Verify Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
        }
    }
}
