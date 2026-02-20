@extends('layouts.app')
@section('title', 'Reports — SETU Suvidha Billing')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="reportsPage()">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('billing.dashboard') }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-emerald-600 mb-1"><i data-lucide="arrow-left" class="w-3 h-3"></i> Dashboard</a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="bar-chart-3" class="w-5 h-5 text-rose-500"></i> Reports / अहवाल</h1>
        </div>
    </div>

    {{-- Date Range Selector --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['week' => 'Week', 'month' => 'Month', 'quarter' => 'Quarter', 'year' => 'Year'] as $val => $label)
        <a href="?range={{ $val }}" class="px-4 py-2 rounded-xl text-sm font-bold transition {{ $range === $val ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 border border-gray-200 dark:border-gray-800' }}">{{ $label }}</a>
        @endforeach
        <span class="text-xs text-gray-400 self-center ml-2">{{ $startDate->format('d M') }} — {{ $endDate->format('d M Y') }} ({{ $daysInRange }} days)</span>
    </div>

    {{-- 5 Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 border-l-4 border-l-emerald-500">
            <p class="text-[10px] text-gray-500 mb-1">Total Sales</p>
            <p class="text-lg font-bold text-emerald-600">₹{{ number_format($totalSales, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 border-l-4 border-l-red-500">
            <p class="text-[10px] text-gray-500 mb-1">Daily Expenses</p>
            <p class="text-lg font-bold text-red-600">₹{{ number_format($dailyExpenses, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 border-l-4 border-l-amber-500">
            <p class="text-[10px] text-gray-500 mb-1">Monthly Fixed (Prorated)</p>
            <p class="text-lg font-bold text-amber-600">₹{{ number_format($proratedMonthlyFixed, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 border-l-4 border-l-orange-500">
            <p class="text-[10px] text-gray-500 mb-1">Total Expenses</p>
            <p class="text-lg font-bold text-orange-600">₹{{ number_format($totalExpenses, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 border-l-4 border-l-blue-500">
            <p class="text-[10px] text-gray-500 mb-1">Net Profit</p>
            <p class="text-lg font-bold {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">₹{{ number_format($netProfit, 0) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Daily Sales vs Expenses Chart --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">📊 Daily Sales vs Expenses</h3>
            <canvas id="dailyChart" height="250"></canvas>
        </div>

        {{-- Expenses by Category --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">📋 Expenses by Category</h3>
            @if($expensesByCategory->count() > 0)
            <div class="space-y-3">
                @php $maxExp = $expensesByCategory->max('total') ?: 1; @endphp
                @foreach($expensesByCategory as $ec)
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-600 dark:text-gray-400">{{ $ec->category }}</span>
                        <span class="font-bold text-red-600">₹{{ number_format($ec->total, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ ($ec->total / $maxExp) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-8 text-center text-gray-400 text-sm">No expense data for this period</div>
            @endif
        </div>
    </div>

    {{-- Profit Calculation Box --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200 dark:border-blue-800 p-6">
        <h3 class="text-sm font-bold text-blue-700 dark:text-blue-400 mb-3">📐 Profit Calculation</h3>
        <div class="space-y-1 text-sm">
            <div class="flex justify-between"><span class="text-gray-600">Total Sales Revenue:</span><span class="font-bold text-emerald-600">+ ₹{{ number_format($totalSales, 0) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-600">Daily Expenses:</span><span class="font-bold text-red-600">- ₹{{ number_format($dailyExpenses, 0) }}</span></div>
            <div class="flex justify-between"><span class="text-gray-600">Monthly Fixed (prorated {{ $daysInRange }}d):</span><span class="font-bold text-red-600">- ₹{{ number_format($proratedMonthlyFixed, 0) }}</span></div>
            <div class="flex justify-between border-t border-blue-200 dark:border-blue-700 pt-2 mt-2">
                <span class="font-bold text-gray-900 dark:text-white">Net Profit:</span>
                <span class="font-bold text-lg {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">₹{{ number_format($netProfit, 0) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
function reportsPage() {
    return {
        init() {
            this.$nextTick(() => this.renderChart());
        },
        renderChart() {
            const ctx = document.getElementById('dailyChart');
            if (!ctx) return;
            const data = @json($dailyData);
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [
                        { label: 'Sales', data: data.map(d => d.sales), backgroundColor: 'rgba(16, 185, 129, 0.7)', borderRadius: 4 },
                        { label: 'Expenses', data: data.map(d => d.expenses), backgroundColor: 'rgba(239, 68, 68, 0.7)', borderRadius: 4 },
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: v => '₹' + v } },
                        x: { grid: { display: false }, ticks: { font: { size: 9 }, maxRotation: 45 } }
                    }
                }
            });
        }
    }
}
</script>
@endpush
