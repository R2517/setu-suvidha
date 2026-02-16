@extends('layouts.app')
@section('title', 'Admin Dashboard — SETU Suvidha')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Admin Dashboard</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-3"><div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center"><i data-lucide="users" class="w-5 h-5 text-blue-600"></i></div><span class="text-sm text-gray-500">एकूण VLEs</span></div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalVles }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-3"><div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center"><i data-lucide="user-check" class="w-5 h-5 text-green-600"></i></div><span class="text-sm text-gray-500">सक्रिय VLEs</span></div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $activeVles }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-3"><div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center"><i data-lucide="indian-rupee" class="w-5 h-5 text-amber-600"></i></div><span class="text-sm text-gray-500">एकूण महसूल</span></div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">₹{{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                <div class="flex items-center gap-3 mb-3"><div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center"><i data-lucide="file-text" class="w-5 h-5 text-purple-600"></i></div><span class="text-sm text-gray-500">एकूण फॉर्म्स</span></div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalForms }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
