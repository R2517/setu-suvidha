@extends('layouts.app')
@section('title', 'सेटिंग्ज — Admin')
@section('content')
<div class="flex min-h-screen" x-data="{ showLogs: false }">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 overflow-x-hidden">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">सेटिंग्ज</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- System Health --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="activity" class="w-4 h-4 text-green-500"></i> System Health</h2>
                </div>
                <div class="p-6 space-y-3">
                    @foreach([
                        'App Version' => $health['app_version'],
                        'Laravel' => $health['laravel_version'],
                        'PHP' => $health['php_version'],
                        'Database' => $health['db_status'],
                        'Cache Driver' => $health['cache_driver'],
                        'Queue Driver' => $health['queue_driver'],
                        'Disk Free' => $health['disk_free'],
                    ] as $label => $value)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 dark:border-gray-800 last:border-0">
                        <span class="text-xs text-gray-500">{{ $label }}</span>
                        <span class="text-xs font-medium text-gray-900 dark:text-white {{ $label === 'Database' && str_contains($value, 'Error') ? 'text-red-600' : '' }}">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Razorpay Config --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="credit-card" class="w-4 h-4 text-indigo-500"></i> Razorpay Configuration</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Key ID</span>
                        <span class="text-xs font-mono text-gray-700 dark:text-gray-300">{{ $razorpayKeyId ? Str::mask($razorpayKeyId, '*', 8) : 'Not configured' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Status</span>
                        <span class="text-xs font-bold {{ $razorpayKeyId ? 'text-green-600' : 'text-red-600' }}">{{ $razorpayKeyId ? 'Configured' : 'Missing' }}</span>
                    </div>
                    <p class="text-[11px] text-gray-400 bg-gray-50 dark:bg-gray-800 p-3 rounded-lg">Razorpay keys are managed via <code class="font-mono">.env</code> file. Update <code>RAZORPAY_KEY_ID</code> and <code>RAZORPAY_KEY_SECRET</code> in the server environment.</p>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2"><i data-lucide="link" class="w-4 h-4 text-amber-500"></i> Quick Links</h2>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('author') }}" target="_blank" class="flex items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-xs font-medium text-gray-700 dark:text-gray-300">
                        <i data-lucide="user" class="w-4 h-4 text-gray-500"></i> Author Page
                    </a>
                    <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-xs font-medium text-gray-700 dark:text-gray-300">
                        <i data-lucide="globe" class="w-4 h-4 text-gray-500"></i> Home Page
                    </a>
                    <a href="{{ route('admin.contact-requests') }}" class="flex items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-xs font-medium text-gray-700 dark:text-gray-300">
                        <i data-lucide="mail" class="w-4 h-4 text-gray-500"></i> Contact Requests
                    </a>
                    <a href="{{ route('admin.farmer-card-orders') }}" class="flex items-center gap-2 p-3 rounded-xl bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-xs font-medium text-gray-700 dark:text-gray-300">
                        <i data-lucide="leaf" class="w-4 h-4 text-gray-500"></i> Farmer Orders
                    </a>
                </div>
            </div>

            {{-- Cache Actions --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2"><i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i> Maintenance</h2>
                <p class="text-xs text-gray-400 mb-4">Clear caches and optimize the application. Run these commands via SSH on the server.</p>
                <div class="space-y-2">
                    <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg font-mono text-[11px] text-gray-600 dark:text-gray-400">php artisan cache:clear</div>
                    <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg font-mono text-[11px] text-gray-600 dark:text-gray-400">php artisan view:clear</div>
                    <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg font-mono text-[11px] text-gray-600 dark:text-gray-400">php artisan config:cache</div>
                    <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg font-mono text-[11px] text-gray-600 dark:text-gray-400">php artisan optimize</div>
                </div>
            </div>
        </div>

        {{-- Error Logs --}}
        <div class="mt-6 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <button @click="showLogs = !showLogs" class="w-full px-6 py-4 flex items-center justify-between text-sm font-bold text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                <span class="flex items-center gap-2"><i data-lucide="file-warning" class="w-4 h-4 text-red-500"></i> Error Logs (Last 30 lines)</span>
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400" :class="showLogs && 'rotate-180'" style="transition: transform 0.2s"></i>
            </button>
            <div x-show="showLogs" x-transition class="px-6 pb-6 border-t border-gray-100 dark:border-gray-800">
                <div class="mt-4 bg-gray-950 rounded-xl p-4 max-h-96 overflow-y-auto">
                    @forelse($logLines as $line)
                    <div class="text-[10px] font-mono text-gray-400 leading-relaxed {{ str_contains($line, 'ERROR') ? 'text-red-400' : '' }} {{ str_contains($line, 'WARNING') ? 'text-yellow-400' : '' }}">{{ $line }}</div>
                    @empty
                    <p class="text-xs text-gray-500 text-center py-4">No log entries found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
