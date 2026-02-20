@extends('layouts.billing')
@section('title', 'Expenses — SETU Suvidha Billing')

@section('billing-content')
<div class="p-6 lg:p-8" x-data="expensesPage()">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('billing.dashboard') }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-emerald-600 mb-1"><i data-lucide="arrow-left" class="w-3 h-3"></i> Dashboard</a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="wallet" class="w-5 h-5 text-red-500"></i> Expenses / खर्च</h1>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-2 mb-6">
        <button @click="activeTab = 'monthly'" :class="activeTab === 'monthly' ? 'bg-red-500 text-white shadow-lg shadow-red-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 border border-gray-200 dark:border-gray-800'" class="px-5 py-2.5 rounded-xl text-sm font-bold transition">Monthly Fixed</button>
        <button @click="activeTab = 'daily'" :class="activeTab === 'daily' ? 'bg-red-500 text-white shadow-lg shadow-red-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 border border-gray-200 dark:border-gray-800'" class="px-5 py-2.5 rounded-xl text-sm font-bold transition">Daily Expenses</button>
    </div>

    {{-- TAB 1: Monthly Fixed --}}
    <div x-show="activeTab === 'monthly'" x-transition>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 mb-6">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">📋 Monthly Fixed Expenses</h3>

            {{-- Add Form --}}
            <form method="POST" action="{{ route('billing.monthly.store') }}" class="flex flex-wrap gap-3 mb-5 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                @csrf
                <select name="category" required class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                    <option value="">Category</option>
                    @foreach(['Rent', 'Salary', 'Electricity', 'Internet', 'Phone', 'Insurance', 'Software', 'Other'] as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <input name="amount" type="number" step="0.01" required placeholder="Amount ₹" class="w-28 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                <select name="expense_type" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                    <option value="monthly">Monthly</option>
                    <option value="one_time">One-time</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-bold transition">+ Add</button>
            </form>

            {{-- List --}}
            @if($monthlyExpenses->count() > 0)
            <div class="space-y-2">
                @foreach($monthlyExpenses as $me)
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $me->category }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $me->expense_type === 'monthly' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">{{ $me->expense_type }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-bold text-red-600">₹{{ number_format($me->amount, 0) }}</span>
                        <button @click="deleteMonthly({{ $me->id }})" class="p-1 rounded hover:bg-red-50 text-red-400"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl flex justify-between text-sm">
                <span class="text-red-600 font-medium">Total Monthly Overhead:</span>
                <span class="text-red-600 font-bold">₹{{ number_format($monthlyExpenses->where('expense_type', 'monthly')->sum('amount'), 0) }}</span>
            </div>
            @else
            <p class="text-center text-gray-400 text-sm py-6">No monthly expenses added yet</p>
            @endif
        </div>
    </div>

    {{-- TAB 2: Daily Expenses --}}
    <div x-show="activeTab === 'daily'" x-transition>
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-xs text-gray-500 mb-1">This Month Expenses</p>
                <p class="text-xl font-bold text-red-600">₹{{ number_format($monthDailyExpenses, 0) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-xs text-gray-500 mb-1">This Month Sales</p>
                <p class="text-xl font-bold text-emerald-600">₹{{ number_format($monthSales, 0) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                <p class="text-xs text-gray-500 mb-1">Net (Sales - Expenses)</p>
                <p class="text-xl font-bold {{ ($monthSales - $monthDailyExpenses) >= 0 ? 'text-emerald-600' : 'text-red-600' }}">₹{{ number_format($monthSales - $monthDailyExpenses, 0) }}</p>
            </div>
        </div>

        {{-- Add Expense Button + Filter --}}
        <div class="flex flex-wrap gap-3 mb-6">
            <button @click="showExpenseModal = true" class="px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-bold transition"><i data-lucide="plus" class="w-4 h-4 inline"></i> Add Expense</button>
            <form method="GET" class="flex gap-2 flex-1">
                <input type="hidden" name="" value="">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="flex-1 min-w-[150px] px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                <select name="category" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                    <option value="">All Categories</option>
                    @foreach(['Rent', 'Salary', 'Electricity', 'Internet', 'Phone', 'Travel', 'Food', 'Supplies', 'Other'] as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium"><i data-lucide="search" class="w-4 h-4 inline"></i></button>
            </form>
        </div>

        {{-- Expenses Table --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            @if($expenses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">Description</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Mode</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">Amount</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                        @foreach($expenses as $exp)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $exp->expense_date->format('d M Y') }}</td>
                            <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-medium">{{ $exp->category }}</span></td>
                            <td class="px-4 py-3 hidden sm:table-cell text-xs text-gray-600">{{ Str::limit($exp->description, 40) }}</td>
                            <td class="px-4 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $exp->payment_mode === 'cash' ? 'bg-green-100 text-green-700' : ($exp->payment_mode === 'upi' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">{{ ucfirst($exp->payment_mode) }}</span></td>
                            <td class="px-4 py-3 text-right font-bold text-red-600">₹{{ number_format($exp->amount, 0) }}</td>
                            <td class="px-4 py-3 text-center">
                                <button @click="deleteExpense({{ $exp->id }})" class="p-1.5 rounded-lg hover:bg-red-50 text-red-400"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">{{ $expenses->withQueryString()->links() }}</div>
            @else
            <div class="px-6 py-12 text-center text-gray-400"><p class="text-sm">No daily expenses recorded</p></div>
            @endif
        </div>
    </div>

    {{-- Add Expense Modal --}}
    <div x-show="showExpenseModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/50" @click="showExpenseModal = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 w-full max-w-md shadow-2xl p-6" @click.stop>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4"><i data-lucide="minus-circle" class="w-5 h-5 inline text-red-500"></i> Add Expense</h2>
            <div class="space-y-3">
                <select x-model="expForm.category" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <option value="">Category *</option>
                    @foreach(['Rent', 'Salary', 'Electricity', 'Internet', 'Phone', 'Travel', 'Food', 'Supplies', 'Maintenance', 'Other'] as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                <textarea x-model="expForm.description" rows="2" placeholder="Description" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm resize-none"></textarea>
                <input x-model.number="expForm.amount" type="number" step="0.01" placeholder="Amount ₹ *" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <div class="grid grid-cols-3 gap-2">
                    <template x-for="mode in ['cash', 'upi', 'online']" :key="mode">
                        <button type="button" @click="expForm.payment_mode = mode"
                            :class="expForm.payment_mode === mode ? 'bg-red-500 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600'"
                            class="px-3 py-2 rounded-xl text-sm font-bold transition capitalize" x-text="mode"></button>
                    </template>
                </div>
                <input x-model="expForm.expense_date" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
            </div>
            <div class="flex gap-3 mt-5">
                <button @click="submitExpense()" :disabled="expSubmitting" class="flex-1 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition disabled:opacity-50">
                    <span x-show="!expSubmitting">💾 Save</span><span x-show="expSubmitting">⏳</span>
                </button>
                <button @click="showExpenseModal = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl font-medium">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function expensesPage() {
    return {
        activeTab: 'daily',
        showExpenseModal: false,
        expSubmitting: false,
        expForm: { category: '', description: '', amount: '', payment_mode: 'cash', expense_date: new Date().toISOString().slice(0, 10) },

        async submitExpense() {
            if (!this.expForm.category || !this.expForm.amount) { alert('Fill required fields'); return; }
            this.expSubmitting = true;
            try {
                const res = await fetch('{{ route("billing.expenses.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.expForm)
                });
                const data = await res.json();
                if (data.success) window.location.reload();
            } catch(e) { alert('Error'); }
            this.expSubmitting = false;
        },

        async deleteExpense(id) {
            if (!confirm('Delete this expense?')) return;
            await fetch(`/billing/expenses/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
            window.location.reload();
        },

        async deleteMonthly(id) {
            if (!confirm('Delete this monthly expense?')) return;
            await fetch(`/billing/monthly-expenses/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
            window.location.reload();
        }
    }
}
</script>
@endpush
