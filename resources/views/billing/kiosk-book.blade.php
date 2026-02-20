@extends('layouts.billing')
@section('title', 'Kiosk Book — SETU Suvidha Billing')

@section('billing-content')
<div class="p-6 lg:p-8" x-data="kioskBookPage()">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('billing.dashboard') }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-emerald-600 mb-1"><i data-lucide="arrow-left" class="w-3 h-3"></i> Dashboard</a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="landmark" class="w-5 h-5 text-indigo-500"></i> Kiosk Book</h1>
        </div>
        <button @click="showAddModal = true" class="px-4 py-2.5 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5"><i data-lucide="plus" class="w-4 h-4"></i> Add Transaction</button>
    </div>

    {{-- 4 Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center gap-2 mb-2"><i data-lucide="arrow-up-right" class="w-4 h-4 text-red-500"></i><span class="text-xs text-gray-500">Total Withdraw</span></div>
            <p class="text-xl font-bold text-red-600">₹{{ number_format($summary['withdrawals'], 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center gap-2 mb-2"><i data-lucide="arrow-down-left" class="w-4 h-4 text-green-500"></i><span class="text-xs text-gray-500">Total Deposit</span></div>
            <p class="text-xl font-bold text-green-600">₹{{ number_format($summary['deposits'], 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center gap-2 mb-2"><i data-lucide="banknote" class="w-4 h-4 text-purple-500"></i><span class="text-xs text-gray-500">Cash Commission</span></div>
            <p class="text-xl font-bold text-purple-600">₹{{ number_format($summary['cash_commission'], 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center gap-2 mb-2"><i data-lucide="monitor" class="w-4 h-4 text-indigo-500"></i><span class="text-xs text-gray-500">Portal Commission</span></div>
            <p class="text-xl font-bold text-indigo-600">₹{{ number_format($summary['portal_commission'], 0) }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name/mobile/aadhaar..." class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
        <select name="type" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
            <option value="">All Types</option>
            <option value="withdraw" {{ request('type') === 'withdraw' ? 'selected' : '' }}>Withdraw</option>
            <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
            <option value="balance" {{ request('type') === 'balance' ? 'selected' : '' }}>Balance Enquiry</option>
            <option value="mini_statement" {{ request('type') === 'mini_statement' ? 'selected' : '' }}>Mini Statement</option>
        </select>
        <button type="submit" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium"><i data-lucide="search" class="w-4 h-4 inline"></i></button>
    </form>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($transactions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 hidden sm:table-cell">Bank</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">Cash Comm.</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 hidden sm:table-cell">Portal Comm.</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $txn->transaction_date->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full font-bold
                                {{ $txn->transaction_type === 'withdraw' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $txn->transaction_type === 'deposit' ? 'bg-green-100 text-green-700' : '' }}
                                {{ in_array($txn->transaction_type, ['balance', 'mini_statement']) ? 'bg-gray-100 text-gray-600' : '' }}
                            ">{{ ucfirst(str_replace('_', ' ', $txn->transaction_type)) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $txn->customer_name ?: '—' }}</span>
                            @if($txn->aadhaar_last_four)<br><span class="text-[10px] text-gray-400">XXXX {{ $txn->aadhaar_last_four }}</span>@endif
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell text-xs text-gray-600">{{ $txn->bank_name ?: '—' }}</td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">₹{{ number_format($txn->amount, 0) }}</td>
                        <td class="px-4 py-3 text-right text-purple-600 font-medium">₹{{ number_format($txn->manual_commission, 0) }}</td>
                        <td class="px-4 py-3 text-right hidden sm:table-cell text-indigo-600 font-medium">₹{{ number_format($txn->portal_commission, 0) }}</td>
                        <td class="px-4 py-3 text-center">
                            <button @click="deleteTxn({{ $txn->id }})" class="p-1.5 rounded-lg hover:bg-red-50 text-red-400"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">{{ $transactions->withQueryString()->links() }}</div>
        @else
        <div class="px-6 py-16 text-center text-gray-400">
            <i data-lucide="landmark" class="w-16 h-16 mx-auto mb-4 opacity-20"></i>
            <p class="text-sm">No kiosk transactions yet</p>
        </div>
        @endif
    </div>

    {{-- Add Transaction Modal --}}
    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md shadow-2xl p-6" @click.stop>
            <h2 class="text-lg font-bold mb-4"><i data-lucide="landmark" class="w-5 h-5 inline text-indigo-500"></i> Add Kiosk Transaction</h2>
            <div class="space-y-3">
                <select x-model="txnForm.transaction_type" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <option value="withdraw">Withdraw</option>
                    <option value="deposit">Deposit</option>
                    <option value="balance">Balance Enquiry</option>
                    <option value="mini_statement">Mini Statement</option>
                </select>
                <div class="grid grid-cols-2 gap-3">
                    <input x-model="txnForm.customer_name" type="text" placeholder="Customer Name" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <input x-model="txnForm.customer_mobile" type="text" placeholder="Mobile" maxlength="10" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <input x-model="txnForm.aadhaar_last_four" type="text" placeholder="Aadhaar last 4" maxlength="4" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <input x-model="txnForm.bank_name" type="text" placeholder="Bank Name" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                </div>
                <input x-model.number="txnForm.amount" type="number" min="0" placeholder="Amount ₹" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <div class="grid grid-cols-2 gap-3">
                    <input x-model.number="txnForm.manual_commission" type="number" min="0" placeholder="Cash Commission ₹" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <input x-model.number="txnForm.portal_commission" type="number" min="0" placeholder="Portal Commission ₹" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                </div>
                <input x-model="txnForm.transaction_date" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
            </div>
            <div class="flex gap-3 mt-5">
                <button @click="submitTxn()" :disabled="txnSubmitting" class="flex-1 py-3 bg-indigo-500 hover:bg-indigo-600 text-white rounded-xl font-bold transition disabled:opacity-50">💾 Save</button>
                <button @click="showAddModal = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl font-medium">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function kioskBookPage() {
    return {
        showAddModal: false,
        txnSubmitting: false,
        txnForm: {
            transaction_type: 'withdraw', customer_name: '', customer_mobile: '',
            aadhaar_last_four: '', bank_name: '', amount: '', manual_commission: '',
            portal_commission: '', transaction_date: new Date().toISOString().slice(0, 10),
        },
        async submitTxn() {
            this.txnSubmitting = true;
            try {
                const res = await fetch('{{ route("billing.kiosk.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.txnForm)
                });
                if ((await res.json()).success) window.location.reload();
            } catch(e) { alert('Error'); }
            this.txnSubmitting = false;
        },
        async deleteTxn(id) {
            if (!confirm('Delete?')) return;
            await fetch(`/billing/kiosk-book/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
            window.location.reload();
        }
    }
}
</script>
@endpush
