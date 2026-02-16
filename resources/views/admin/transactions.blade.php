@extends('layouts.app')
@section('title', 'व्यवहार लॉग — Admin')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">व्यवहार लॉग</h1>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">VLE</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">वर्णन</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500">प्रकार</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">रक्कम</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">शिल्लक</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">तारीख</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($transactions as $txn)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-3 text-gray-500">{{ $txn->id }}</td>
                            <td class="px-6 py-3 text-gray-900 dark:text-white">{{ $txn->user->name ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $txn->description ?? '-' }}</td>
                            <td class="px-6 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $txn->type === 'credit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $txn->type === 'credit' ? 'जमा' : 'खर्च' }}</span></td>
                            <td class="px-6 py-3 text-right font-semibold {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">{{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}</td>
                            <td class="px-6 py-3 text-right text-gray-500">₹{{ number_format($txn->balance_after, 2) }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $transactions->links() }}</div>
        </div>
    </div>
</div>
@endsection
