@extends('layouts.billing')
@section('title', $customer->name . ' — Customer Detail')

@section('billing-content')
<div class="p-6 lg:p-8">
    <a href="{{ route('billing.customers') }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-blue-600 mb-4"><i data-lucide="arrow-left" class="w-3 h-3"></i> Customers</a>

    {{-- Customer Info Card --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-blue-500"></i> {{ $customer->name }}
                </h1>
                @if($customer->mobile)<p class="text-sm text-gray-500 mt-1">📞 {{ $customer->mobile }}</p>@endif
                @if($customer->address)<p class="text-xs text-gray-400 mt-1">📍 {{ $customer->address }}</p>@endif
                @if($customer->notes)<p class="text-xs text-gray-400 mt-1 italic">{{ $customer->notes }}</p>@endif
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4 mt-5">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500">Total Visits</p>
                <p class="text-xl font-bold text-blue-600">{{ $customer->total_visits }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500">Total Spent</p>
                <p class="text-xl font-bold text-emerald-600">₹{{ number_format($customer->total_spent, 0) }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 text-center">
                <p class="text-xs text-gray-500">Total Due</p>
                <p class="text-xl font-bold {{ $customer->total_due > 0 ? 'text-red-600' : 'text-gray-400' }}">₹{{ number_format($customer->total_due, 0) }}</p>
            </div>
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">🧾 Transaction History</h3>
        </div>
        @if($sales->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Invoice</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">Services</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">Due</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 text-xs font-mono text-emerald-600">{{ $sale->invoice_number }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $sale->sale_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 hidden sm:table-cell text-xs text-gray-600">{{ $sale->items->pluck('service_name')->join(', ') }}</td>
                        <td class="px-4 py-3 text-right font-bold">₹{{ number_format($sale->total_amount, 0) }}</td>
                        <td class="px-4 py-3 text-right {{ $sale->due_amount > 0 ? 'text-red-500 font-bold' : 'text-gray-400' }}">₹{{ number_format($sale->due_amount, 0) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($sale->payment_status === 'unpaid' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($sale->payment_status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">{{ $sales->links() }}</div>
        @else
        <div class="px-6 py-12 text-center text-gray-400 text-sm">No transactions yet</div>
        @endif
    </div>
</div>
@endsection
