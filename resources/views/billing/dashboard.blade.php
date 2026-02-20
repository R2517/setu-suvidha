@extends('layouts.billing')
@section('title', 'बिलिंग Dashboard — SETU Suvidha')

@section('billing-content')
<div class="p-6 lg:p-8" x-data="billingDashboard()">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="layout-dashboard" class="w-7 h-7 text-emerald-500"></i> बिलिंग Dashboard
            </h1>
            <p class="text-sm text-gray-500 mt-1">आजचे व्यवसाय सारांश</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('billing.sales') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5"><i data-lucide="plus" class="w-4 h-4"></i> Add Sale</a>
            <a href="{{ route('billing.expenses') }}" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5"><i data-lucide="minus" class="w-4 h-4"></i> Add Expense</a>
            <a href="{{ route('billing.kiosk-book') }}" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5"><i data-lucide="landmark" class="w-4 h-4"></i> Kiosk</a>
            <a href="{{ route('billing.reports') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5"><i data-lucide="bar-chart-3" class="w-4 h-4"></i> Reports</a>
        </div>
    </div>

    {{-- Period Filter Tabs --}}
    <div class="flex gap-2 mb-6">
        <a href="?period=today" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $period === 'today' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 border border-gray-200 dark:border-gray-800' }}">Today</a>
        <a href="?period=week" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $period === 'week' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 border border-gray-200 dark:border-gray-800' }}">This Week</a>
        <a href="?period=month" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $period === 'month' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 border border-gray-200 dark:border-gray-800' }}">This Month</a>
    </div>

    {{-- 4 Metric Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 border-l-4 border-l-emerald-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500">Sales</span>
                <i data-lucide="trending-up" class="w-4 h-4 text-emerald-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($salesTotal, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 border-l-4 border-l-red-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500">Expenses</span>
                <i data-lucide="trending-down" class="w-4 h-4 text-red-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($expensesTotal, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 border-l-4 border-l-amber-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500">Pending</span>
                <i data-lucide="clock" class="w-4 h-4 text-amber-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($pendingPayments, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500">Profit</span>
                <i data-lucide="indian-rupee" class="w-4 h-4 text-blue-500"></i>
            </div>
            <p class="text-2xl font-bold {{ $totalProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">₹{{ number_format($totalProfit, 0) }}</p>
        </div>
    </div>

    {{-- Kiosk Summary (conditional) --}}
    @if($kioskSummary)
    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-2xl border border-purple-200 dark:border-purple-800 p-5 mb-6">
        <h3 class="text-sm font-bold text-purple-700 dark:text-purple-400 mb-3 flex items-center gap-1.5"><i data-lucide="landmark" class="w-4 h-4"></i> Kiosk Summary</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="text-center"><p class="text-xs text-gray-500">Withdrawals</p><p class="text-lg font-bold text-red-600">₹{{ number_format($kioskSummary['withdrawals'], 0) }}</p></div>
            <div class="text-center"><p class="text-xs text-gray-500">Deposits</p><p class="text-lg font-bold text-green-600">₹{{ number_format($kioskSummary['deposits'], 0) }}</p></div>
            <div class="text-center"><p class="text-xs text-gray-500">Cash Commission</p><p class="text-lg font-bold text-purple-600">₹{{ number_format($kioskSummary['cash_commission'], 0) }}</p></div>
            <div class="text-center"><p class="text-xs text-gray-500">Portal Commission</p><p class="text-lg font-bold text-indigo-600">₹{{ number_format($kioskSummary['portal_commission'], 0) }}</p></div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Sales vs Expenses Chart --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">📊 Sales vs Expenses (7 Days)</h3>
            <canvas id="salesExpensesChart" height="200"></canvas>
        </div>

        {{-- Category-wise Breakdown --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">📋 Category-wise Sales</h3>
            @if($categoryData->count() > 0)
            <div class="space-y-2">
                @php $maxCat = $categoryData->max('total') ?: 1; @endphp
                @foreach($categoryData as $cat)
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600 dark:text-gray-400">{{ $cat->service_name }}</span>
                        <span class="font-bold text-gray-900 dark:text-white">₹{{ number_format($cat->total, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ ($cat->total / $maxCat) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-8 text-center text-gray-400 text-sm">No sales data yet</div>
            @endif
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">🧾 Recent Transactions</h3>
            <a href="{{ route('billing.sales') }}" class="text-xs text-emerald-500 hover:text-emerald-600 font-medium">View All →</a>
        </div>
        @if($recentSales->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500">Invoice</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500">Customer</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">Services</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500">Amount</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500">Mode</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @foreach($recentSales as $sale)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 text-xs font-mono text-gray-600">{{ $sale->invoice_number }}</td>
                        <td class="px-4 py-3">
                            <span class="text-gray-900 dark:text-white font-medium text-sm">{{ $sale->customer_name ?: 'Walk-in' }}</span>
                            @if($sale->customer_phone)<br><span class="text-xs text-gray-400">{{ $sale->customer_phone }}</span>@endif
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="text-xs text-gray-600">{{ $sale->items->take(2)->pluck('service_name')->join(', ') }}{{ $sale->items->count() > 2 ? ' +' . ($sale->items->count() - 2) . ' more' : '' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">₹{{ number_format($sale->total_amount, 0) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $sale->payment_mode === 'cash' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $sale->payment_mode === 'upi' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $sale->payment_mode === 'online' ? 'bg-purple-100 text-purple-700' : '' }}
                                {{ $sale->payment_mode === 'split' ? 'bg-amber-100 text-amber-700' : '' }}
                            ">{{ ucfirst($sale->payment_mode) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full font-bold
                                {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $sale->payment_status === 'unpaid' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $sale->payment_status === 'partial' ? 'bg-amber-100 text-amber-700' : '' }}
                            ">{{ ucfirst($sale->payment_status) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-400">
            <i data-lucide="receipt" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
            <p class="text-sm">अद्याप कोणतीही विक्री नाही</p>
            <a href="{{ route('billing.sales') }}" class="mt-3 inline-block text-xs text-emerald-500 font-bold">+ पहिली विक्री नोंदवा</a>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
function billingDashboard() {
    return {
        init() {
            this.$nextTick(() => this.renderChart());
        },
        renderChart() {
            const ctx = document.getElementById('salesExpensesChart');
            if (!ctx) return;
            const data = @json($chartData);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [
                        { label: 'Sales', data: data.map(d => d.sales), backgroundColor: 'rgba(16, 185, 129, 0.7)', borderRadius: 6 },
                        { label: 'Expenses', data: data.map(d => d.expenses), backgroundColor: 'rgba(239, 68, 68, 0.7)', borderRadius: 6 },
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: v => '₹' + v } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }
}
</script>
@endpush
