@extends('layouts.app')
@section('title', 'राजपत्र नमुना — SETU Suvidha')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6"><i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा</a>
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-2"><i data-lucide="scale" class="w-6 h-6 text-amber-600"></i> राजपत्र नमुना नोटीस</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('rajpatra-marathi') }}" class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:shadow-lg hover:-translate-y-1 transition-all">
            <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center mb-4"><i data-lucide="file-text" class="w-6 h-6 text-green-600"></i></div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-1">राजपत्र मराठी</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">मराठी नाव बदल राजपत्र नोटीस</p>
        </a>
        <a href="{{ route('rajpatra-english') }}" class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:shadow-lg hover:-translate-y-1 transition-all">
            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4"><i data-lucide="file-text" class="w-6 h-6 text-blue-600"></i></div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-1">राजपत्र English</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">English Name Change Gazette Notice</p>
        </a>
        <a href="{{ route('rajpatra-712') }}" class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:shadow-lg hover:-translate-y-1 transition-all">
            <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-4"><i data-lucide="file-text" class="w-6 h-6 text-purple-600"></i></div>
            <h3 class="font-bold text-gray-900 dark:text-white mb-1">राजपत्र ७/१२ शपथपत्र</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">7/12 Affidavit — पत्ता बदल</p>
        </a>
    </div>
</div>
@endsection
