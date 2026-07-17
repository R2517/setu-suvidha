<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Admin bypasses subscription check
        if ($user && $user->isAdmin()) {
            return $next($request);
        }

        if ($user && !$user->hasBillingAccess()) {
            // For AJAX requests, return JSON error
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'billing_license_required',
                    'message' => 'Your 15-day free trial has ended. Please purchase a Yearly Billing License.',
                    'redirect' => route('subscription'),
                ], 403);
            }

            // Store intended URL for after subscription
            session()->put('url.intended', $request->url());

            return redirect()->route('subscription')
                ->with('warning', 'तुमची 15-दिवसांची विनामूल्य चाचणी (Free Trial) संपली आहे. कृपया ₹499 मध्ये वार्षिक परवाना सक्रिय करा.');
        }

        return $next($request);
    }
}
