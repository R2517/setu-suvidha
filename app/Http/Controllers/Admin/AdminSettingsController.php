<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class AdminSettingsController extends Controller
{
    public function index()
    {
        // Error logs (last 30 lines from laravel.log)
        $logFile = storage_path('logs/laravel.log');
        $logLines = [];
        if (File::exists($logFile)) {
            $lines = explode("\n", File::get($logFile));
            $logLines = array_slice(array_filter($lines), -30);
        }

        // Site health
        $health = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'app_version' => 'v3.0.0',
            'db_status' => 'OK',
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'disk_free' => function_exists('disk_free_space') ? round(disk_free_space('/') / 1073741824, 2) . ' GB' : 'N/A',
        ];

        try {
            DB::connection()->getPdo();
            $health['db_status'] = 'Connected';
        } catch (\Exception $e) {
            $health['db_status'] = 'Error: ' . $e->getMessage();
        }

        // Razorpay config
        $razorpayKeyId = config('razorpay.key_id', env('RAZORPAY_KEY_ID', ''));
        $razorpayKeySecret = config('razorpay.key_secret', env('RAZORPAY_KEY_SECRET', ''));
        $razorpayWebhookSecret = config('razorpay.webhook_secret', env('RAZORPAY_WEBHOOK_SECRET', ''));

        return view('admin.settings', compact('logLines', 'health', 'razorpayKeyId', 'razorpayKeySecret', 'razorpayWebhookSecret'));
    }

    public function updateRazorpay(Request $request)
    {
        $request->validate([
            'razorpay_key_id' => 'nullable|string|max:255',
            'razorpay_key_secret' => 'nullable|string|max:255',
            'razorpay_webhook_secret' => 'nullable|string|max:255',
        ]);

        $envPath = base_path('.env');
        $envContent = File::get($envPath);

        $updates = [
            'RAZORPAY_KEY_ID' => $request->razorpay_key_id ?? '',
            'RAZORPAY_KEY_SECRET' => $request->razorpay_key_secret ?? '',
            'RAZORPAY_WEBHOOK_SECRET' => $request->razorpay_webhook_secret ?? '',
        ];

        foreach ($updates as $key => $value) {
            // Match key= with optional quotes, any value (handles KEY=val, KEY="val", KEY='val')
            if (preg_match("/^{$key}\s*=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$key}\s*=.*/m", "{$key}=\"{$value}\"", $envContent);
            } else {
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }

        File::put($envPath, $envContent);

        // Clear ALL caches so new .env values take effect
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Silently fail if artisan calls have issues
        }

        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        return redirect()->route('admin.settings')->with('success', 'Razorpay config updated! Keys saved to .env');
    }
}
