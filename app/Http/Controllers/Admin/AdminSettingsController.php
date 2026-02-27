<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $diskProbePath = DIRECTORY_SEPARATOR === '\\' ? base_path() : '/';

        // Error logs (last 30 lines from laravel.log) - use tail to avoid loading full file
        $logFile = storage_path('logs/laravel.log');
        $logLines = [];
        if (File::exists($logFile)) {
            if (DIRECTORY_SEPARATOR === '\\') {
                // Windows: read last 4KB and extract lines
                $content = File::get($logFile);
                $lines = explode("\n", $content);
                $logLines = array_slice(array_filter($lines), -30);
            } else {
                // Unix/Linux/Mac: use tail command for efficiency
                $logLines = [];
                $output = [];
                $returnVar = 0;
                exec("tail -30 " . escapeshellarg($logFile) . " 2>/dev/null", $output, $returnVar);
                if ($returnVar === 0) {
                    $logLines = array_filter($output);
                }
            }
        }

        // Site health
        $health = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'app_version' => 'v3.0.0',
            'db_status' => 'OK',
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'disk_free' => function_exists('disk_free_space')
                ? round(disk_free_space($diskProbePath) / 1073741824, 2) . ' GB'
                : 'N/A',
        ];

        try {
            DB::connection()->getPdo();
            $health['db_status'] = 'Connected';
        } catch (\Exception $e) {
            $health['db_status'] = 'Error: ' . $e->getMessage();
        }

        // Razorpay config - mask secrets for display
        $razorpayKeyId = config('razorpay.key_id', '');
        $razorpayKeySecret = config('razorpay.key_secret', '');
        $razorpayWebhookSecret = config('razorpay.webhook_secret', '');

        $razorpayKeySecretMasked = !empty($razorpayKeySecret)
            ? substr($razorpayKeySecret, 0, 4) . '****' . substr($razorpayKeySecret, -4)
            : '';
        $razorpayWebhookSecretMasked = !empty($razorpayWebhookSecret)
            ? substr($razorpayWebhookSecret, 0, 4) . '****' . substr($razorpayWebhookSecret, -4)
            : '';

        return view('admin.settings', compact(
            'logLines', 'health',
            'razorpayKeyId', 'razorpayKeySecret', 'razorpayWebhookSecret',
            'razorpayKeySecretMasked', 'razorpayWebhookSecretMasked'
        ));
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

        $updates = [];

        if ($request->filled('razorpay_key_id')) {
            $updates['RAZORPAY_KEY_ID'] = $request->razorpay_key_id;
        }

        if ($request->filled('razorpay_key_secret')) {
            $updates['RAZORPAY_KEY_SECRET'] = $request->razorpay_key_secret;
        }

        if ($request->filled('razorpay_webhook_secret')) {
            $updates['RAZORPAY_WEBHOOK_SECRET'] = $request->razorpay_webhook_secret;
        }

        foreach ($updates as $key => $value) {
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
