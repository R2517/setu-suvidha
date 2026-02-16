@extends('layouts.app')
@section('title', 'सेटिंग्ज — Admin')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">सेटिंग्ज</h1>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 max-w-2xl">
            <p class="text-gray-500 dark:text-gray-400">Admin सेटिंग्ज — लवकरच उपलब्ध होतील.</p>
            <div class="mt-6 space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Razorpay Key ID</span>
                    <span class="text-sm text-gray-400 font-mono">{{ Str::mask(config('razorpay.key_id'), '*', 8) }}</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-700 dark:text-gray-300">App Version</span>
                    <span class="text-sm text-gray-400">v2.0.0</span>
                </div>
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Laravel Version</span>
                    <span class="text-sm text-gray-400">{{ app()->version() }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm text-gray-700 dark:text-gray-300">PHP Version</span>
                    <span class="text-sm text-gray-400">{{ phpversion() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
