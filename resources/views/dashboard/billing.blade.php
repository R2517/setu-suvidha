@extends('layouts.app')
@section('title', 'बिलिंग — SETU Suvidha')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा
    </a>

    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        <i data-lucide="receipt" class="w-6 h-6 text-amber-600"></i> बिलिंग / फॉर्म इतिहास
    </h2>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($submissions->isEmpty())
        <div class="px-6 py-12 text-center text-gray-400">
            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
            <p>अद्याप कोणतेही फॉर्म सबमिट केलेले नाहीत</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">तारीख</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">फॉर्म प्रकार</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">अर्जदाराचे नाव</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($submissions as $s)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-3 text-gray-500">{{ $s->id }}</td>
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-400">{{ $s->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-6 py-3 text-gray-900 dark:text-white font-medium">{{ $s->form_type }}</td>
                        <td class="px-6 py-3 text-gray-900 dark:text-white">{{ $s->applicant_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $submissions->links() }}</div>
        @endif
    </div>
</div>
@endsection
