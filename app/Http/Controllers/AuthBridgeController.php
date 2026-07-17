<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthBridgeController extends Controller
{
    /**
     * Redirect authenticated user to billing subdomain with signed token
     */
    public function redirectToBilling(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Please login to access billing system');
        }

        $isTrialActive = $user->isBillingTrialActive();
        $trialDaysLeft = max(0, (int) now()->startOfDay()->diffInDays($user->getBillingTrialEndsAt()->copy()->startOfDay(), false));

        // Create payload with unique JTI for replay protection
        $payload = [
            'user_id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'tenant_slug' => $this->generateTenantSlug($user),
            'is_trial' => $isTrialActive,
            'trial_days_left' => $trialDaysLeft,
            'jti' => bin2hex(random_bytes(16)), // Unique token ID for replay guard
            'iat' => time(),
            'exp' => time() + 300, // 5 minutes expiry
            'iss' => 'setusuvidha.com'
        ];

        // Encode payload
        $payloadJson = json_encode($payload);
        $payloadBase64 = rtrim(strtr(base64_encode($payloadJson), '+/', '-_'), '=');

        // Generate HMAC signature
        $secret = config('services.auth_bridge.secret');
        if (!$secret) {
            abort(500, 'Auth bridge not configured');
        }

        $signature = hash_hmac('sha256', $payloadJson, $secret);

        // Combine into token
        $token = $payloadBase64 . '.' . $signature;

        // Redirect to billing subdomain
        $billingUrl = config('services.auth_bridge.billing_url', 'https://billing.setusuvidha.com');
        
        return redirect($billingUrl . '/auth-bridge?token=' . urlencode($token));
    }

    /**
     * Generate unique tenant slug for user
     */
    private function generateTenantSlug($user): string
    {
        // Create a unique slug based on user name and ID
        $baseName = Str::slug($user->name);
        return $baseName . '-' . $user->id;
    }
}
