@extends('layouts.app')
@section('title', 'उत्पन्न प्रमाणपत्र — SETU Suvidha')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showForm: false }">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6"><i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा</a>
    <div class="bg-gradient-to-br from-pink-600 to-rose-700 rounded-2xl p-8 text-white mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center"><i data-lucide="landmark" class="w-7 h-7"></i></div>
            <div><h1 class="text-2xl font-bold">उत्पन्नाचे स्वयंघोषणापत्र</h1><p class="text-white/80 text-sm mt-1">Income Certificate — 4 प्रिंट फॉरमॅट | शुल्क: ₹{{ $pricing->price ?? '0' }}</p></div>
        </div>
        <button @click="showForm = !showForm" class="bg-white text-pink-600 font-semibold px-6 py-3 rounded-xl hover:bg-pink-50 transition flex items-center gap-2"><i data-lucide="plus" class="w-5 h-5"></i> फॉर्म भरा</button>
    </div>
    <div x-show="showForm" x-transition class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 mb-8">
        <form method="POST" action="/income-cert" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">अर्जदाराचे नाव *</label><input type="text" name="applicant_name" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">वडिलांचे / पतीचे नाव</label><input type="text" name="father_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">वय</label><input type="number" name="age" min="1" max="120" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">व्यवसाय</label><input type="text" name="occupation" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">जिल्हा</label><input type="text" name="district" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">तालुका</label><input type="text" name="taluka" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">गाव</label><input type="text" name="village" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">वार्षिक उत्पन्न (₹)</label><input type="number" name="annual_income" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पत्ता</label><textarea name="address" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></textarea></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">कारण</label>
                    <select name="reason" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                        <option value="">निवडा</option><option value="शिक्षण">शिक्षण</option><option value="नोकरी">नोकरी</option><option value="शिष्यवृत्ती">शिष्यवृत्ती</option><option value="योजना">योजना</option><option value="इतर">इतर</option>
                    </select></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">तारीख</label><input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
            </div>
            <button type="submit" class="btn-primary">💾 सेव्ह करा</button>
        </form>
    </div>
    @include('forms.partials.submissions-table', ['submissions' => $submissions])
</div>
@endsection
