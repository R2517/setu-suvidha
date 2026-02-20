<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\VleSubscription;
use App\Models\WalletTransaction;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderByRaw("FIELD(plan_type, 'monthly', 'quarterly', 'half_yearly', 'yearly')")
            ->get();

        $currentSub = $user->activeSubscription();
        $walletBalance = $user->getWalletBalance();

        // Subscription history
        $history = VleSubscription::where('user_id', $user->id)
            ->with('plan')
            ->orderByDesc('start_date')
            ->limit(10)
            ->get();

        return view('subscription', compact('plans', 'currentSub', 'walletBalance', 'history'));
    }

    public function activate(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = auth()->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Check if user already has an active subscription
        $existingSub = $user->activeSubscription();
        if ($existingSub) {
            return redirect()->route('subscription')->with('error', 'तुमचे आधीच सक्रिय सबस्क्रिप्शन आहे. प्लॅन बदलण्यासाठी "प्लॅन बदला" वापरा.');
        }

        // Maintenance amount is charged during trial
        $maintenanceAmount = (float) $plan->maintenance_amount;
        $walletBalance = $user->getWalletBalance();

        if ($maintenanceAmount > 0 && $walletBalance < $maintenanceAmount) {
            return redirect()->route('subscription')->with('error', 'अपुरी वॉलेट शिल्लक! मेंटेनन्स शुल्क: ₹' . number_format($maintenanceAmount, 2) . ', उपलब्ध: ₹' . number_format($walletBalance, 2));
        }

        return DB::transaction(function () use ($user, $plan, $maintenanceAmount) {
            $trialDays = $plan->trial_days ?? 15;
            $trialEndsAt = now()->addDays($trialDays);

            // Create subscription in trial mode
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

            // Debit maintenance amount from wallet
            if ($maintenanceAmount > 0) {
                $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();
                $newBalance = $profile->wallet_balance - $maintenanceAmount;
                $profile->update(['wallet_balance' => $newBalance]);

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $maintenanceAmount,
                    'balance_after' => $newBalance,
                    'description' => "सबस्क्रिप्शन मेंटेनन्स शुल्क - {$plan->name}",
                    'reference_id' => 'SUB-MAINT-' . $sub->id,
                ]);
            }

            return redirect()->route('subscription')->with('success', "🎉 {$plan->name} प्लॅन सक्रिय झाला! {$trialDays} दिवसांचा ट्रायल कालावधी सुरू.");
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
            return redirect()->route('subscription')->with('error', 'कोणतेही सक्रिय सबस्क्रिप्शन नाही. प्रथम प्लॅन सक्रिय करा.');
        }

        if ($currentSub->plan_id == $newPlan->id) {
            return redirect()->route('subscription')->with('error', 'तुम्ही आधीच या प्लॅनवर आहात.');
        }

        return DB::transaction(function () use ($user, $newPlan, $currentSub) {
            // Cancel current subscription
            $currentSub->update(['status' => 'cancelled']);

            $trialDays = $newPlan->trial_days ?? 15;
            $isInTrial = $currentSub->is_trial && $currentSub->trial_ends_at && $currentSub->trial_ends_at->isFuture();

            // If still in trial, new plan also gets remaining trial
            if ($isInTrial) {
                $trialEndsAt = $currentSub->trial_ends_at;
                $sub = VleSubscription::create([
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
                // Not in trial — charge full plan amount
                $planPrice = (float) $newPlan->price;
                $walletBalance = $user->getWalletBalance();

                if ($planPrice > 0 && $walletBalance < $planPrice) {
                    // Rollback cancel
                    $currentSub->update(['status' => 'active']);
                    return redirect()->route('subscription')->with('error', 'अपुरी शिल्लक! प्लॅन किंमत: ₹' . number_format($planPrice, 2) . ', उपलब्ध: ₹' . number_format($walletBalance, 2));
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
                        'description' => "प्लॅन बदल - {$newPlan->name}",
                        'reference_id' => 'SUB-CHANGE-' . $sub->id,
                    ]);
                }
            }

            return redirect()->route('subscription')->with('success', "✅ प्लॅन बदलला! आता {$newPlan->name} सक्रिय आहे.");
        });
    }

    /**
     * Check and process trial expiry — called on each authenticated page load via middleware/service.
     * If trial has ended and plan amount not yet paid, debit from wallet.
     */
    public static function processTrialExpiry($user)
    {
        $sub = $user->activeSubscription();
        if (!$sub || !$sub->is_trial || !$sub->trial_ends_at) {
            return;
        }

        // Trial not yet expired
        if ($sub->trial_ends_at->isFuture()) {
            return;
        }

        // Trial expired — need to charge full plan amount
        $plan = $sub->plan;
        if (!$plan) {
            $sub->update(['status' => 'expired']);
            return;
        }

        $planPrice = (float) $plan->price;
        $walletBalance = $user->getWalletBalance();

        DB::transaction(function () use ($user, $sub, $plan, $planPrice, $walletBalance) {
            if ($planPrice > 0 && $walletBalance >= $planPrice) {
                // Sufficient balance — charge and activate
                $profile = Profile::where('user_id', $user->id)->lockForUpdate()->first();
                $newBalance = $profile->wallet_balance - $planPrice;
                $profile->update(['wallet_balance' => $newBalance]);

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'debit',
                    'amount' => $planPrice,
                    'balance_after' => $newBalance,
                    'description' => "सबस्क्रिप्शन शुल्क - {$plan->name} (ट्रायल संपला)",
                    'reference_id' => 'SUB-ACTIVATE-' . $sub->id,
                ]);

                $sub->update([
                    'status' => 'active',
                    'is_trial' => false,
                    'plan_amount_paid' => true,
                ]);
            } elseif ($planPrice <= 0) {
                // Free plan
                $sub->update([
                    'status' => 'active',
                    'is_trial' => false,
                    'plan_amount_paid' => true,
                ]);
            } else {
                // Insufficient balance — expire subscription
                $sub->update(['status' => 'expired']);
            }
        });
    }
}
