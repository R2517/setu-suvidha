<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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

        return view('admin.settings', compact('logLines', 'health', 'razorpayKeyId'));
    }
}
