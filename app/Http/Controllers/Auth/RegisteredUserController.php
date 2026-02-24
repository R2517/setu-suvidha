<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserRole;
use App\Models\VleSubscription;
use App\Models\WalletTransaction;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $signupBonus = 50.00;

        $user = DB::transaction(function () use ($request, $signupBonus) {
            $createdUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Profile::create([
                'user_id' => $createdUser->id,
                'full_name' => $createdUser->name,
                'email' => $createdUser->email,
                'wallet_balance' => $signupBonus,
                'signup_bonus_given' => true,
                'is_active' => true,
            ]);

            WalletTransaction::create([
                'user_id' => $createdUser->id,
                'type' => 'credit',
                'amount' => $signupBonus,
                'balance_after' => $signupBonus,
                'description' => 'Signup bonus Rs 50',
                'reference_id' => 'SIGNUP-BONUS-' . $createdUser->id,
            ]);

            UserRole::create([
                'user_id' => $createdUser->id,
                'role' => 'vle',
            ]);

            $planQuery = SubscriptionPlan::where('is_active', true);
            if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
                $planQuery->orderByRaw("FIELD(plan_type, 'monthly', 'quarterly', 'half_yearly', 'yearly')");
            } else {
                $planQuery->orderBy('duration_days');
            }

            $defaultPlan = $planQuery->first();

            if ($defaultPlan) {
                $trialDays = max(1, (int) ($defaultPlan->trial_days ?? 15));
                $trialEndsAt = now()->addDays($trialDays);

                VleSubscription::create([
                    'user_id' => $createdUser->id,
                    'plan_id' => $defaultPlan->id,
                    'start_date' => now(),
                    'end_date' => $trialEndsAt->copy()->addDays($defaultPlan->duration_days),
                    'status' => 'trial',
                    'is_trial' => true,
                    'maintenance_paid' => ((float) $defaultPlan->maintenance_amount) <= 0,
                    'plan_amount_paid' => false,
                    'trial_ends_at' => $trialEndsAt,
                    'auto_renew' => true,
                ]);
            }

            return $createdUser;
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
