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

        if ($user && !$user->hasActiveSubscription()) {
            // For AJAX requests, return JSON error
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'subscription_required',
                    'message' => 'कृपया प्रथम सबस्क्रिप्शन प्लॅन सक्रिय करा.',
                    'redirect' => route('subscription'),
                ], 403);
            }

            // Store intended URL for after subscription
            session()->put('url.intended', $request->url());

            return redirect()->route('subscription')
                ->with('warning', 'या वैशिष्ट्यासाठी सक्रिय सबस्क्रिप्शन आवश्यक आहे. कृपया प्लॅन निवडा.');
        }

        return $next($request);
    }
}
