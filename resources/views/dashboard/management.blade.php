@extends('layouts.app')
@section('title', 'CRM व्यवस्थापन — SETU Suvidha')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा
    </a>

    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-2">
        <i data-lucide="briefcase" class="w-6 h-6 text-amber-600"></i> CRM व्यवस्थापन केंद्र
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('pan-card') }}" class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:shadow-lg hover:-translate-y-1 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i data-lucide="credit-card" class="w-7 h-7 text-indigo-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">पॅन कार्ड सेवा</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">नवीन, दुरुस्ती, रिप्रिंट — सर्व PAN अर्ज व्यवस्थापित करा</p>
            <span class="text-xs font-semibold text-indigo-600 flex items-center gap-1">व्यवस्थापन करा <i data-lucide="arrow-right" class="w-3 h-3"></i></span>
        </a>
        <a href="{{ route('voter-id') }}" class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:shadow-lg hover:-translate-y-1 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i data-lucide="user-check" class="w-7 h-7 text-sky-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">वोटर ID सेवा</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">नवीन, दुरुस्ती, ट्रान्सफर, डुप्लिकेट — Voter ID अर्ज</p>
            <span class="text-xs font-semibold text-sky-600 flex items-center gap-1">व्यवस्थापन करा <i data-lucide="arrow-right" class="w-3 h-3"></i></span>
        </a>
        <a href="{{ route('bandkam') }}" class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800 hover:shadow-lg hover:-translate-y-1 transition-all group">
            <div class="w-14 h-14 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                <i data-lucide="hard-hat" class="w-7 h-7 text-orange-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">बांधकाम कामगार</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">नोंदणी, योजना, स्थिती — बांधकाम कामगार CRM</p>
            <span class="text-xs font-semibold text-orange-600 flex items-center gap-1">व्यवस्थापन करा <i data-lucide="arrow-right" class="w-3 h-3"></i></span>
        </a>
    </div>
</div>
@endsection
