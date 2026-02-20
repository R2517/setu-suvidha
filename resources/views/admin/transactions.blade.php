@extends('layouts.app')
@section('title', 'व्यवहार लॉग — Admin')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 overflow-x-hidden">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">व्यवहार लॉग</h1>
            <span class="text-xs text-gray-400">{{ $transactions->total() }} records</span>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.transactions') }}" class="mb-5 flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[180px]">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="VLE नाव शोधा..." class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
            </div>
            <select name="filter" class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                <option value="">सर्व व्यवहार</option>
                <option value="wallet" {{ request('filter') === 'wallet' ? 'selected' : '' }}>Wallet Top-ups</option>
                <option value="plan" {{ request('filter') === 'plan' ? 'selected' : '' }}>Plan Purchases</option>
            </select>
            <select name="type" class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                <option value="">सर्व प्रकार</option>
                <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>जमा (Credit)</option>
                <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>खर्च (Debit)</option>
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
            <input type="date" name="to" value="{{ request('to') }}" class="px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
            <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">Filter</button>
            @if(request()->hasAny(['search', 'filter', 'type', 'from', 'to']))
            <a href="{{ route('admin.transactions') }}" class="text-xs text-gray-500 hover:text-gray-700">Reset</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">VLE</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">वर्णन</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">प्रकार</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">रक्कम</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">शिल्लक</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">तारीख</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($transactions as $txn)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $txn->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white font-medium">{{ $txn->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500 max-w-[200px] truncate">{{ $txn->description ?? '-' }}</td>
                            <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $txn->type === 'credit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $txn->type === 'credit' ? 'जमा' : 'खर्च' }}</span></td>
                            <td class="px-4 py-3 text-right font-bold text-xs {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">{{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}</td>
                            <td class="px-4 py-3 text-right text-xs text-gray-500">₹{{ number_format($txn->balance_after, 2) }}</td>
                            <td class="px-4 py-3 text-right text-[11px] text-gray-400">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">कोणतेही व्यवहार सापडले नाहीत</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $transactions->links() }}</div>
        </div>
    </div>
</div>
@endsection
