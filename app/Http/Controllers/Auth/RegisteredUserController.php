<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\UserRole;
use App\Models\WalletTransaction;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $signupBonus = 50.00;

        Profile::create([
            'user_id' => $user->id,
            'full_name' => $user->name,
            'email' => $user->email,
            'wallet_balance' => $signupBonus,
            'signup_bonus_given' => true,
            'is_active' => true,
        ]);

        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => $signupBonus,
            'balance_after' => $signupBonus,
            'description' => 'साइन-अप बोनस ₹50',
            'reference_id' => 'SIGNUP-BONUS-' . $user->id,
        ]);

        UserRole::create([
            'user_id' => $user->id,
            'role' => 'vle',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
