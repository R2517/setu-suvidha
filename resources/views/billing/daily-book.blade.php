@extends('layouts.app')
@section('title', 'Daily Book — SETU Suvidha Billing')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="dailyBookPage()">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('billing.dashboard') }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-emerald-600 mb-1"><i data-lucide="arrow-left" class="w-3 h-3"></i> Dashboard</a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="book-open" class="w-5 h-5 text-purple-500"></i> Daily Book
                @if($dailyBook && $dailyBook->status === 'closed')
                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 text-gray-600 font-medium">Closed v{{ $dailyBook->close_version }}</span>
                @endif
            </h1>
            <p class="text-xs text-gray-500 mt-0.5">{{ now()->format('d M Y, l') }}</p>
        </div>
        @if($dailyBook)
        <div class="flex gap-2">
            <a href="{{ route('billing.sales') }}" class="px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition">+ Sale</a>
            <button @click="showAdjustModal = true" class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition">Adjust Cash</button>
        </div>
        @endif
    </div>

    @if(!$dailyBook)
    {{-- Day Not Started --}}
    <div class="max-w-md mx-auto mt-20 text-center">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-10">
            <i data-lucide="sunrise" class="w-16 h-16 mx-auto mb-4 text-amber-400"></i>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Start Your Business Day</h2>
            <p class="text-sm text-gray-500 mb-6">Opening cash टाकून आजचा दिवस सुरू करा</p>
            @if($prevBook)
            <p class="text-xs text-gray-400 mb-3">Previous closing: ₹{{ number_format($prevBook->closing_cash ?? 0, 0) }}</p>
            @endif
            <div class="flex items-center gap-3 justify-center">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-500">₹</span>
                    <input x-model.number="openingCash" type="number" min="0" placeholder="Opening Cash" class="w-40 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-center font-bold text-lg" value="{{ $prevBook ? $prevBook->closing_cash : '' }}">
                </div>
                <button @click="startDay()" :disabled="starting" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold transition disabled:opacity-50">
                    <span x-show="!starting">🌅 Start Day</span><span x-show="starting">⏳</span>
                </button>
            </div>
        </div>
    </div>
    @else

    {{-- 5 Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
            <p class="text-[10px] text-gray-500 mb-1">Opening Cash</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white">₹{{ number_format($dailyBook->opening_cash, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
            <p class="text-[10px] text-gray-500 mb-1">Cash Sales</p>
            <p class="text-lg font-bold text-emerald-600">₹{{ number_format($cashSales, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
            <p class="text-[10px] text-gray-500 mb-1">Online Sales</p>
            <p class="text-lg font-bold text-blue-600">₹{{ number_format($onlineSales, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
            <p class="text-[10px] text-gray-500 mb-1">Unpaid</p>
            <p class="text-lg font-bold text-red-500">₹{{ number_format($unpaidSales, 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4">
            <p class="text-[10px] text-gray-500 mb-1">Today's Profit</p>
            <p class="text-lg font-bold {{ ($totalSalesAmount - $todayExpenses) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">₹{{ number_format($totalSalesAmount - $todayExpenses, 0) }}</p>
        </div>
    </div>

    {{-- Cash Adjustments Strip --}}
    @if($cashAdded > 0 || $cashRemoved > 0 || $cashExpenses > 0)
    <div class="flex flex-wrap gap-3 mb-4">
        @if($cashAdded > 0)<span class="text-xs px-3 py-1.5 rounded-full bg-green-100 text-green-700 font-medium">+ Cash Added: ₹{{ number_format($cashAdded, 0) }}</span>@endif
        @if($cashRemoved > 0)<span class="text-xs px-3 py-1.5 rounded-full bg-red-100 text-red-700 font-medium">- Cash Removed: ₹{{ number_format($cashRemoved, 0) }}</span>@endif
        @if($cashExpenses > 0)<span class="text-xs px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 font-medium">- Cash Expenses: ₹{{ number_format($cashExpenses, 0) }}</span>@endif
    </div>
    @endif

    {{-- Expected Cash Card --}}
    <div class="bg-gradient-to-r {{ $expectedCash >= 0 ? 'from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-green-200 dark:border-green-800' : 'from-red-50 to-rose-50 dark:from-red-900/20 border-red-200' }} rounded-2xl border p-5 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 mb-1">Expected Cash in Drawer</p>
                <p class="text-2xl font-bold {{ $expectedCash >= 0 ? 'text-emerald-700' : 'text-red-700' }}">₹{{ number_format($expectedCash, 0) }}</p>
                <p class="text-[10px] text-gray-400 mt-1">= Opening({{ number_format($dailyBook->opening_cash, 0) }}) + CashSales({{ number_format($cashSales, 0) }}) + Added({{ number_format($cashAdded, 0) }}) - Removed({{ number_format($cashRemoved, 0) }}) + Kiosk({{ number_format($kioskNet, 0) }}) - Expenses({{ number_format($cashExpenses, 0) }})</p>
            </div>
        </div>
    </div>

    {{-- Today's Sales Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden mb-6">
        <div class="px-5 py-3 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">🧾 Today's Sales ({{ $sales->count() }})</h3>
        </div>
        @if($sales->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">#</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Time</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500">Customer</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">Services</th>
                        <th class="px-3 py-2 text-right text-xs font-semibold text-gray-500">Total</th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500">Mode</th>
                        <th class="px-3 py-2 text-center text-xs font-semibold text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @foreach($sales as $idx => $sale)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-3 py-2 text-xs text-gray-400">{{ $idx + 1 }}</td>
                        <td class="px-3 py-2 text-xs text-gray-500">{{ $sale->created_at->format('h:i A') }}</td>
                        <td class="px-3 py-2 text-sm font-medium text-gray-900 dark:text-white">{{ $sale->customer_name ?: 'Walk-in' }}</td>
                        <td class="px-3 py-2 hidden sm:table-cell text-xs text-gray-600">{{ $sale->items->pluck('service_name')->take(2)->join(', ') }}</td>
                        <td class="px-3 py-2 text-right font-bold text-gray-900 dark:text-white">₹{{ number_format($sale->total_amount, 0) }}</td>
                        <td class="px-3 py-2 text-center"><span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $sale->payment_mode === 'cash' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">{{ ucfirst($sale->payment_mode) }}</span></td>
                        <td class="px-3 py-2 text-center"><span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($sale->payment_status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-8 text-center text-gray-400 text-sm">No sales today yet</div>
        @endif
    </div>

    {{-- Close/Reopen Day --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
        @if($dailyBook->status === 'open')
        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">🔒 Close Day</h3>
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="text-xs text-gray-500 block mb-1">Closing Cash (actual)</label>
                <div class="flex items-center gap-1"><span class="text-sm">₹</span><input x-model.number="closingCash" type="number" min="0" class="w-40 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm font-bold"></div>
            </div>
            <div>
                <label class="text-xs text-gray-500 block mb-1">Notes</label>
                <input x-model="closingNotes" type="text" placeholder="Optional notes" class="w-60 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
            </div>
            <button @click="closeDay()" :disabled="closing" class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-bold transition disabled:opacity-50">🔒 Close Day</button>
        </div>
        @else
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600"><b>Closed</b> — Closing: ₹{{ number_format($dailyBook->closing_cash, 0) }} | Expected: ₹{{ number_format($expectedCash, 0) }} | Diff: <span class="{{ $dailyBook->difference >= 0 ? 'text-green-600' : 'text-red-600' }}">₹{{ number_format($dailyBook->difference, 0) }}</span></p>
            </div>
            <button @click="reopenDay()" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition">🔓 Reopen</button>
        </div>
        @endif
    </div>

    @endif

    {{-- Cash Adjustment Modal --}}
    <div x-show="showAdjustModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/50" @click="showAdjustModal = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-sm shadow-2xl p-6" @click.stop>
            <h2 class="text-lg font-bold mb-4">💰 Cash Adjustment</h2>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <button @click="adjType = 'add'" :class="adjType === 'add' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-600'" class="py-2 rounded-xl text-sm font-bold transition">+ Add Cash</button>
                <button @click="adjType = 'remove'" :class="adjType === 'remove' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-600'" class="py-2 rounded-xl text-sm font-bold transition">- Remove Cash</button>
            </div>
            <input x-model.number="adjAmount" type="number" min="0" placeholder="Amount ₹" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm mb-3">
            <input x-model="adjReason" type="text" placeholder="Reason" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm mb-4">
            <button @click="submitAdjustment()" class="w-full py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-xl font-bold transition">💾 Save</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function dailyBookPage() {
    return {
        openingCash: {{ $prevBook ? $prevBook->closing_cash ?? 0 : 0 }},
        starting: false,
        closingCash: {{ round($expectedCash) }},
        closingNotes: '',
        closing: false,
        showAdjustModal: false,
        adjType: 'add',
        adjAmount: '',
        adjReason: '',

        async startDay() {
            this.starting = true;
            await fetch('{{ route("billing.daily-book.start") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ opening_cash: this.openingCash })
            });
            window.location.reload();
        },

        async closeDay() {
            if (!confirm('Close today\'s book?')) return;
            this.closing = true;
            await fetch('{{ route("billing.daily-book.close") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ closing_cash: this.closingCash, expected_cash: {{ round($expectedCash) }}, closing_notes: this.closingNotes })
            });
            window.location.reload();
        },

        async reopenDay() {
            if (!confirm('Reopen today\'s book?')) return;
            await fetch('{{ route("billing.daily-book.reopen") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            });
            window.location.reload();
        },

        async submitAdjustment() {
            if (!this.adjAmount) return;
            await fetch('{{ route("billing.daily-book.adjust") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ type: this.adjType, amount: this.adjAmount, reason: this.adjReason })
            });
            window.location.reload();
        }
    }
}
</script>
@endpush
