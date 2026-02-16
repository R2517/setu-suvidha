@extends('layouts.app')
@section('title', 'Rajpatra English — SETU Suvidha')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showForm: false }">
    <a href="{{ route('rajpatra') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6"><i data-lucide="arrow-left" class="w-4 h-4"></i> राजपत्र नमुना</a>
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-8 text-white mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center"><i data-lucide="scale" class="w-7 h-7"></i></div>
            <div><h1 class="text-2xl font-bold">Rajpatra English (Gazette)</h1><p class="text-white/80 text-sm mt-1">Name Change Gazette | Fee: ₹{{ $pricing->price ?? '0' }}</p></div>
        </div>
        <button @click="showForm = !showForm" class="bg-white text-blue-600 font-semibold px-6 py-3 rounded-xl hover:bg-blue-50 transition flex items-center gap-2"><i data-lucide="plus" class="w-5 h-5"></i> Fill Form</button>
    </div>
    <div x-show="showForm" x-transition class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 mb-8">
        <form method="POST" action="/rajpatra-english" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Applicant Name *</label><input type="text" name="applicant_name" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition" style="text-transform:uppercase"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Old Surname *</label><input type="text" name="old_surname" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition" style="text-transform:uppercase"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Old First Name</label><input type="text" name="old_first_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition" style="text-transform:uppercase"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Old Father's Name</label><input type="text" name="old_father_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition" style="text-transform:uppercase"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Surname *</label><input type="text" name="new_surname" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition" style="text-transform:uppercase"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New First Name</label><input type="text" name="new_first_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition" style="text-transform:uppercase"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Father's Name</label><input type="text" name="new_father_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition" style="text-transform:uppercase"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reason</label><input type="text" name="reason" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date</label><input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
            </div>
            <button type="submit" class="btn-primary">💾 Save</button>
        </form>
    </div>
    @include('forms.partials.submissions-table', ['submissions' => $submissions])
</div>
@endsection
