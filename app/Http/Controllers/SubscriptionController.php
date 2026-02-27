<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\SubscriptionPaymentOrder;
use App\Models\SubscriptionPlan;
use App\Models\VleSubscription;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plansQuery = SubscriptionPlan::where('is_active', true);

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            $plansQuery->orderByRaw("FIELD(plan_type, 'monthly', 'quarterly', 'half_yearly', 'yearly')");
        } else {
            $plansQuery->orderBy('duration_days');
        }

        $plans = $plansQuery->get();
        $currentSub = $user->activeSubscription();
        $walletBalance = $user->getWalletBalance();
        $razorpayKeyId = config('razorpay.key_id');

        $history = VleSubscription::where('user_id', $user->id)
            ->with('plan')
            ->orderByDesc('start_date')
            ->limit(10)
            ->get();

        return view('subscription', compact('plans', 'currentSub', 'walletBalance', 'history', 'razorpayKeyId'));
    }

    public function activate(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = auth()->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        $maintenanceAmount = (float) $plan->maintenance_amount;
        $walletBalance = $user->getWalletBalance();

        if ($maintenanceAmount > 0 && $walletBalance < $maintenanceAmount) {
            return redirect()->route('subscription')->with('error', 'Insufficient wallet balance for maintenance charge.');
        }

        return DB::transaction(function () use ($user, $plan, $maintenanceAmount) {
            $existingSub = VleSubscription::where('user_id', $user->id)
                ->whereIn('status', ['active', 'trial'])
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>', now());
                })
                ->lockForUpdate()
                ->first();

            if ($existingSub) {
                throw new \Exception('You already have an active subscription. Use Change Plan instead.');
            }

            $trialDays = (int) ($plan->trial_days ?? 15);
            $trialEndsAt = now()->addDays($trialDays);

            $sub = VleSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'start_date' => now(),
                'end_date' => $trialEndsAt->copy()->addDays($plan->duration_days),
                'status' => 'trial',
                'is_trial' => true,
                'maintenance_paid' => $maintenanceAmount > 0,
                'plan_amount_paid' => false,
                'trial_ends_at' => $trialEndsAt,
                'auto_renew' => true,
            ]);

            if ($maintenanceAmount > 0) {
                $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();
                $newBalance = $profile->wallet_balance - $maintenanceAmount;
                $profile->update(['wallet_balance' => $newBalance]);

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $maintenanceAmount,
                    'balance_after' => $newBalance,
                    'description' => "Subscription maintenance - {$plan->name}",
                    'reference_id' => 'SUB-MAINT-' . $sub->id,
                ]);

                Cache::forget("wallet_balance:{$user->id}");
            }

            return redirect()->route('subscription')->with('success', "{$plan->name} trial started for {$trialDays} days.");
        });
    }

    public function activateNow(Request $request)
    {
        $request->validate([
            'plan_id' => 'nullable|exists:subscription_plans,id',
        ]);

        $user = auth()->user();
        $currentSub = $user->activeSubscription();

        if (!$currentSub || !$currentSub->is_trial) {
            return redirect()->route('subscription')->with('error', 'Activate Now is only available during trial.');
        }

        $plan = $currentSub->plan;
        if (!$plan) {
            return redirect()->route('subscription')->with('error', 'Subscription plan not found.');
        }

        if ($request->filled('plan_id') && (int) $request->plan_id !== (int) $plan->id) {
            return redirect()->route('subscription')->with('error', 'Plan mismatch for this trial.');
        }

        $planPrice = (float) $plan->price;
        $walletBalance = $user->getWalletBalance();

        if ($planPrice > 0 && $walletBalance < $planPrice) {
            return redirect()->route('subscription')->with('error', 'Insufficient wallet balance. Please recharge or pay online.');
        }

        return DB::transaction(function () use ($user, $currentSub, $plan, $planPrice) {
            if ($planPrice > 0) {
                $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();
                $newBalance = $profile->wallet_balance - $planPrice;
                $profile->update(['wallet_balance' => $newBalance]);

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $planPrice,
                    'balance_after' => $newBalance,
                    'description' => "Subscription activation - {$plan->name}",
                    'reference_id' => 'SUB-ACTIVATE-NOW-' . $currentSub->id,
                ]);

                Cache::forget("wallet_balance:{$user->id}");
            }

            $currentSub->update([
                'status' => 'active',
                'is_trial' => false,
                'plan_amount_paid' => true,
                'maintenance_paid' => true,
            ]);

            return redirect()->route('subscription')->with('success', "{$plan->name} is now active.");
        });
    }

    public function changePlan(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = auth()->user();
        $newPlan = SubscriptionPlan::findOrFail($request->plan_id);
        $currentSub = $user->activeSubscription();

        if (!$currentSub) {
            return redirect()->route('subscription')->with('error', 'No active subscription found.');
        }

        if ((int) $currentSub->plan_id === (int) $newPlan->id) {
            return redirect()->route('subscription')->with('error', 'You are already on this plan.');
        }

        return DB::transaction(function () use ($user, $newPlan, $currentSub) {
            $currentSub->update(['status' => 'cancelled']);
            $isInTrial = $this->isRunningTrial($currentSub);

            if ($isInTrial) {
                $trialEndsAt = $currentSub->trial_ends_at;

                VleSubscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $newPlan->id,
                    'start_date' => now(),
                    'end_date' => $trialEndsAt->copy()->addDays($newPlan->duration_days),
                    'status' => 'trial',
                    'is_trial' => true,
                    'maintenance_paid' => $currentSub->maintenance_paid,
                    'plan_amount_paid' => false,
                    'trial_ends_at' => $trialEndsAt,
                    'auto_renew' => true,
                ]);
            } else {
                $planPrice = (float) $newPlan->price;
                $walletBalance = $user->getWalletBalance();

                if ($planPrice > 0 && $walletBalance < $planPrice) {
                    $currentSub->update(['status' => 'active']);
                    return redirect()->route('subscription')->with('error', 'Insufficient wallet balance for plan change.');
                }

                $sub = VleSubscription::create([
                    'user_id' => $user->id,
                    'plan_id' => $newPlan->id,
                    'start_date' => now(),
                    'end_date' => now()->addDays($newPlan->duration_days),
                    'status' => 'active',
                    'is_trial' => false,
                    'maintenance_paid' => true,
                    'plan_amount_paid' => $planPrice > 0,
                    'trial_ends_at' => null,
                    'auto_renew' => true,
                ]);

                if ($planPrice > 0) {
                    $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();
                    $newBalance = $profile->wallet_balance - $planPrice;
                    $profile->update(['wallet_balance' => $newBalance]);

                    WalletTransaction::create([
                        'user_id' => $user->id,
                        'type' => 'debit',
                        'amount' => $planPrice,
                        'balance_after' => $newBalance,
                        'description' => "Plan change - {$newPlan->name}",
                        'reference_id' => 'SUB-CHANGE-' . $sub->id,
                    ]);
                }
            }

            return redirect()->route('subscription')->with('success', "Plan changed to {$newPlan->name}.");
        });
    }

    public function createPaymentOrder(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'action' => 'required|in:activate,change,activate_now',
        ]);

        $user = $request->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $action = (string) $request->input('action');
        $currentSub = $user->activeSubscription();

        $currentSubscriptionId = null;
        $amount = 0.0;

        if ($action === 'activate') {
            if ($currentSub) {
                return response()->json(['success' => false, 'message' => 'Active subscription already exists.'], 422);
            }
            $amount = (float) $plan->maintenance_amount;
        }

        if ($action === 'change') {
            if (!$currentSub) {
                return response()->json(['success' => false, 'message' => 'No active subscription to change.'], 422);
            }
            if ((int) $currentSub->plan_id === (int) $plan->id) {
                return response()->json(['success' => false, 'message' => 'Already on this plan.'], 422);
            }
            $currentSubscriptionId = $currentSub->id;
            $amount = $this->isRunningTrial($currentSub) ? 0.0 : (float) $plan->price;
        }

        if ($action === 'activate_now') {
            if (!$currentSub || !$currentSub->is_trial) {
                return response()->json(['success' => false, 'message' => 'Activate Now is available only during trial.'], 422);
            }
            if ((int) $currentSub->plan_id !== (int) $plan->id) {
                return response()->json(['success' => false, 'message' => 'Plan mismatch for Activate Now.'], 422);
            }
            $currentSubscriptionId = $currentSub->id;
            $amount = (float) $plan->price;
        }

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Online payment is not required for this action.'], 422);
        }

        $amountPaise = (int) round($amount * 100);
        $keyId = (string) config('razorpay.key_id');
        $keySecret = (string) config('razorpay.key_secret');

        if ($keyId === '' || $keySecret === '') {
            return response()->json(['success' => false, 'message' => 'Payment gateway is not configured.'], 500);
        }

        try {
            $api = new Api($keyId, $keySecret);
            $gatewayOrder = $api->order->create([
                'receipt' => 'sub_' . Str::uuid()->toString(),
                'amount' => $amountPaise,
                'currency' => 'INR',
                'notes' => [
                    'type' => 'subscription_payment',
                    'user_id' => (string) $user->id,
                    'action' => $action,
                    'plan_id' => (string) $plan->id,
                ],
            ]);

            SubscriptionPaymentOrder::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'current_subscription_id' => $currentSubscriptionId,
                'action' => $action,
                'amount_paise' => $amountPaise,
                'status' => 'pending',
                'razorpay_order_id' => $gatewayOrder['id'],
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $gatewayOrder['id'],
                'key_id' => $keyId,
                'amount' => $amountPaise,
                'action' => $action,
                'plan_id' => (int) $plan->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('Subscription Razorpay order creation failed', [
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'action' => $action,
                'amount_paise' => $amountPaise,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => 'Unable to create payment order.'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $user = $request->user();
        $order = SubscriptionPaymentOrder::where('user_id', $user->id)
            ->where('razorpay_order_id', $request->razorpay_order_id)
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Subscription payment order not found.'], 404);
        }

        if ($order->status === 'paid') {
            return response()->json([
                'success' => true,
                'message' => 'Payment already verified.',
                'redirect' => route('subscription'),
            ]);
        }

        $expectedSignature = hash_hmac(
            'sha256',
            $request->razorpay_order_id . '|' . $request->razorpay_payment_id,
            (string) config('razorpay.key_secret')
        );

        if (!hash_equals($expectedSignature, $request->razorpay_signature)) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
        }

        try {
            $api = new Api((string) config('razorpay.key_id'), (string) config('razorpay.key_secret'));
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if (($payment['order_id'] ?? null) !== $order->razorpay_order_id) {
                return response()->json(['success' => false, 'message' => 'Order mismatch.'], 400);
            }

            if ((int) ($payment['amount'] ?? 0) !== (int) $order->amount_paise) {
                return response()->json(['success' => false, 'message' => 'Amount mismatch.'], 400);
            }

            if (strtolower((string) ($payment['status'] ?? '')) !== 'captured') {
                return response()->json(['success' => false, 'message' => 'Payment not captured.'], 400);
            }

            DB::transaction(function () use ($user, $order, $request) {
                $lockedOrder = SubscriptionPaymentOrder::where('id', $order->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedOrder->status === 'paid') {
                    return;
                }

                $this->applyPaidSubscriptionAction($user, $lockedOrder, $request->razorpay_payment_id);

                $lockedOrder->update([
                    'status' => 'paid',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Payment successful. Subscription updated.',
                'redirect' => route('subscription'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Subscription payment verification failed', [
                'user_id' => $user->id,
                'order_id' => $request->razorpay_order_id,
                'payment_id' => $request->razorpay_payment_id,
                'error' => $e->getMessage(),
            ]);

            if ($order->status === 'pending') {
                $order->update(['status' => 'failed']);
            }

            return response()->json(['success' => false, 'message' => 'Unable to complete subscription payment.'], 500);
        }
    }

    private function applyPaidSubscriptionAction($user, SubscriptionPaymentOrder $order, string $paymentId): void
    {
        $plan = $order->plan;
        if (!$plan) {
            throw new \RuntimeException('Subscription plan not found for payment order.');
        }

        if ($order->action === 'activate') {
            $this->applyGatewayActivate($user, $plan, $paymentId);
            return;
        }

        if ($order->action === 'change') {
            $this->applyGatewayChange($user, $plan, $order->current_subscription_id, $paymentId);
            return;
        }

        if ($order->action === 'activate_now') {
            $this->applyGatewayActivateNow($user, $plan, $order->current_subscription_id, $paymentId);
            return;
        }

        throw new \RuntimeException('Unsupported subscription payment action.');
    }

    private function applyGatewayActivate($user, SubscriptionPlan $plan, string $paymentId): void
    {
        $existingSub = $user->fresh()->activeSubscription();
        if ($existingSub) {
            if ((int) $existingSub->plan_id === (int) $plan->id && $existingSub->is_trial) {
                $existingSub->update(['maintenance_paid' => true]);
                return;
            }

            throw new \RuntimeException('Cannot activate. An active subscription already exists.');
        }

        $trialDays = (int) ($plan->trial_days ?? 15);
        $trialEndsAt = now()->addDays($trialDays);

        VleSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => $trialEndsAt->copy()->addDays($plan->duration_days),
            'status' => 'trial',
            'is_trial' => true,
            'maintenance_paid' => true,
            'plan_amount_paid' => false,
            'trial_ends_at' => $trialEndsAt,
            'auto_renew' => true,
            'razorpay_payment_id' => $paymentId,
        ]);
    }

    private function applyGatewayChange($user, SubscriptionPlan $newPlan, ?int $expectedCurrentSubId, string $paymentId): void
    {
        $currentSub = $user->fresh()->activeSubscription();

        if (!$currentSub && $expectedCurrentSubId) {
            $currentSub = VleSubscription::where('id', $expectedCurrentSubId)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$currentSub) {
            throw new \RuntimeException('No active subscription found to change.');
        }

        if ((int) $currentSub->plan_id === (int) $newPlan->id) {
            return;
        }

        $isInTrial = $this->isRunningTrial($currentSub);
        $trialEndsAt = $currentSub->trial_ends_at;
        $currentSub->update(['status' => 'cancelled']);

        if ($isInTrial) {
            VleSubscription::create([
                'user_id' => $user->id,
                'plan_id' => $newPlan->id,
                'start_date' => now(),
                'end_date' => $trialEndsAt->copy()->addDays($newPlan->duration_days),
                'status' => 'trial',
                'is_trial' => true,
                'maintenance_paid' => $currentSub->maintenance_paid,
                'plan_amount_paid' => false,
                'trial_ends_at' => $trialEndsAt,
                'auto_renew' => true,
            ]);
            return;
        }

        VleSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $newPlan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($newPlan->duration_days),
            'status' => 'active',
            'is_trial' => false,
            'maintenance_paid' => true,
            'plan_amount_paid' => true,
            'trial_ends_at' => null,
            'auto_renew' => true,
            'razorpay_payment_id' => $paymentId,
        ]);
    }

    private function applyGatewayActivateNow($user, SubscriptionPlan $plan, ?int $expectedCurrentSubId, string $paymentId): void
    {
        $currentSub = $user->fresh()->activeSubscription();

        if (!$currentSub && $expectedCurrentSubId) {
            $currentSub = VleSubscription::where('id', $expectedCurrentSubId)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$currentSub || !$currentSub->is_trial) {
            throw new \RuntimeException('Activate Now requires an active trial subscription.');
        }

        if ((int) $currentSub->plan_id !== (int) $plan->id) {
            throw new \RuntimeException('Trial plan mismatch for Activate Now.');
        }

        $currentSub->update([
            'status' => 'active',
            'is_trial' => false,
            'maintenance_paid' => true,
            'plan_amount_paid' => true,
            'razorpay_payment_id' => $paymentId,
        ]);
    }

    private function isRunningTrial(?VleSubscription $sub): bool
    {
        return (bool) (
            $sub
            && $sub->is_trial
            && $sub->trial_ends_at
            && $sub->trial_ends_at->isFuture()
        );
    }

    /**
     * Check and process trial expiry on authenticated page loads.
     * If trial has ended and plan amount is unpaid, debit from wallet.
     */
    public static function processTrialExpiry($user)
    {
        $sub = $user->fresh()->activeSubscription();
        if (!$sub || !$sub->is_trial || !$sub->trial_ends_at) {
            return;
        }

        if ($sub->trial_ends_at->isFuture()) {
            return;
        }

        $plan = $sub->plan;
        if (!$plan) {
            $sub->update(['status' => 'expired']);
            return;
        }

        $planPrice = (float) $plan->price;

        DB::transaction(function () use ($user, $sub, $plan, $planPrice) {
            $lockedSub = VleSubscription::where('id', $sub->id)->lockForUpdate()->first();

            if (
                !$lockedSub
                || !$lockedSub->is_trial
                || !$lockedSub->trial_ends_at
                || $lockedSub->trial_ends_at->isFuture()
            ) {
                return;
            }

            if ($planPrice <= 0) {
                $lockedSub->update([
                    'status' => 'active',
                    'is_trial' => false,
                    'plan_amount_paid' => true,
                ]);
                return;
            }

            $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();
            $walletBalance = (float) ($profile->wallet_balance ?? 0);

            if ($walletBalance >= $planPrice) {
                $newBalance = $walletBalance - $planPrice;
                $profile->update(['wallet_balance' => $newBalance]);
                Cache::forget("wallet_balance:{$user->id}");

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $planPrice,
                    'balance_after' => $newBalance,
                    'description' => "Subscription charge - {$plan->name} (trial ended)",
                    'reference_id' => 'SUB-ACTIVATE-' . $lockedSub->id,
                ]);

                $lockedSub->update([
                    'status' => 'active',
                    'is_trial' => false,
                    'plan_amount_paid' => true,
                ]);
                return;
            }

            $lockedSub->update(['status' => 'expired']);
        });
    }
}
