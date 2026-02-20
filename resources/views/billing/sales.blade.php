@extends('layouts.app')
@section('title', 'Sales — SETU Suvidha Billing')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="salesPage()">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('billing.dashboard') }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-emerald-600 mb-1"><i data-lucide="arrow-left" class="w-3 h-3"></i> Dashboard</a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="receipt" class="w-5 h-5 text-emerald-500"></i> Sales / विक्री</h1>
        </div>
        <button @click="showAddModal = true" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5"><i data-lucide="plus" class="w-4 h-4"></i> Add New Sale</button>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, phone, invoice..." class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500">
            <select name="status" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partial</option>
            </select>
            <select name="date_filter" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <option value="">All Dates</option>
                <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ request('date_filter') === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ request('date_filter') === 'month' ? 'selected' : '' }}>This Month</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 rounded-xl text-sm font-medium transition"><i data-lucide="search" class="w-4 h-4 inline"></i> Filter</button>
        </form>
    </div>

    {{-- Sales Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($sales->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Invoice</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 hidden md:table-cell">Services</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 hidden sm:table-cell">Due</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Mode</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @foreach($sales as $sale)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 text-xs font-mono text-emerald-600">{{ $sale->invoice_number }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $sale->sale_date->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-900 dark:text-white">{{ $sale->customer_name ?: 'Walk-in' }}</span>
                            @if($sale->customer_phone)<br><span class="text-xs text-gray-400">{{ $sale->customer_phone }}</span>@endif
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell text-xs text-gray-600">
                            {{ $sale->items->take(2)->pluck('service_name')->join(', ') }}{{ $sale->items->count() > 2 ? ' +' . ($sale->items->count() - 2) : '' }}
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">₹{{ number_format($sale->total_amount, 0) }}</td>
                        <td class="px-4 py-3 text-right hidden sm:table-cell {{ $sale->due_amount > 0 ? 'text-red-500 font-bold' : 'text-gray-400' }}">₹{{ number_format($sale->due_amount, 0) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $sale->payment_mode === 'cash' ? 'bg-green-100 text-green-700' : ($sale->payment_mode === 'upi' ? 'bg-blue-100 text-blue-700' : ($sale->payment_mode === 'online' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700')) }}">{{ ucfirst($sale->payment_mode) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-700' : ($sale->payment_status === 'unpaid' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($sale->payment_status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button @click="viewInvoice({{ $sale->id }})" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 transition" title="View"><i data-lucide="eye" class="w-3.5 h-3.5"></i></button>
                                <button @click="deleteSale({{ $sale->id }})" class="p-1.5 rounded-lg hover:bg-red-50 text-red-400 transition" title="Delete"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">{{ $sales->withQueryString()->links() }}</div>
        @else
        <div class="px-6 py-16 text-center text-gray-400">
            <i data-lucide="receipt" class="w-16 h-16 mx-auto mb-4 opacity-20"></i>
            <p class="text-lg font-medium mb-1">अद्याप कोणतीही विक्री नोंदवलेली नाही</p>
            <p class="text-sm">पहिली विक्री नोंदवण्यासाठी "Add New Sale" बटण दाबा</p>
        </div>
        @endif
    </div>

    {{-- Add Sale Modal --}}
    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-6" @click.stop>
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white"><i data-lucide="plus-circle" class="w-5 h-5 inline text-emerald-500"></i> New Sale</h2>
                <button @click="showAddModal = false" class="p-1 rounded hover:bg-gray-100"><i data-lucide="x" class="w-5 h-5 text-gray-400"></i></button>
            </div>

            {{-- Customer --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                <input x-model="form.customer_name" type="text" placeholder="Customer Name" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <input x-model="form.customer_phone" type="text" placeholder="Mobile Number" maxlength="10" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
            </div>

            {{-- Sale Items --}}
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Services / Items</label>
                    <button type="button" @click="addItem()" class="text-xs text-emerald-500 font-bold">+ Add Item</button>
                </div>
                <template x-for="(item, i) in form.items" :key="i">
                    <div class="flex items-center gap-2 mb-2">
                        <select x-model="item.service_name" @change="autoFillPrice(i)" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                            <option value="">-- Select Service --</option>
                            @foreach($services as $svc)
                            <option value="{{ $svc->name }}" data-price="{{ $svc->default_price }}" data-cost="{{ $svc->cost_price }}">{{ $svc->name }} (₹{{ number_format($svc->default_price, 0) }})</option>
                            @endforeach
                        </select>
                        <input x-model.number="item.quantity" type="number" min="1" class="w-16 px-2 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-center" placeholder="Qty">
                        <input x-model.number="item.unit_price" type="number" min="0" class="w-24 px-2 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm" placeholder="Price">
                        <span class="text-sm font-bold text-gray-700 w-20 text-right" x-text="'₹' + (item.quantity * item.unit_price)"></span>
                        <button type="button" @click="form.items.splice(i, 1)" x-show="form.items.length > 1" class="p-1 text-red-400 hover:text-red-500"><i data-lucide="x" class="w-4 h-4"></i></button>
                    </div>
                </template>
            </div>

            {{-- Totals --}}
            <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 mb-4 space-y-2">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal:</span><span class="font-bold" x-text="'₹' + calcSubtotal()"></span></div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Discount:</span>
                    <input x-model.number="form.discount_amount" type="number" min="0" class="w-24 px-2 py-1 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-right">
                </div>
                <div class="flex justify-between text-sm font-bold border-t border-gray-200 dark:border-gray-700 pt-2"><span>Total:</span><span class="text-emerald-600" x-text="'₹' + calcTotal()"></span></div>
            </div>

            {{-- Payment --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-4">
                <template x-for="mode in ['cash', 'upi', 'online', 'split']" :key="mode">
                    <button type="button" @click="form.payment_mode = mode"
                        :class="form.payment_mode === mode ? 'bg-emerald-500 text-white ring-2 ring-emerald-500/30' : 'bg-gray-100 dark:bg-gray-800 text-gray-600'"
                        class="px-3 py-2 rounded-xl text-sm font-bold transition capitalize" x-text="mode"></button>
                </template>
            </div>

            <div x-show="form.payment_mode === 'split'" class="grid grid-cols-2 gap-3 mb-4">
                <div><label class="text-xs text-gray-500">Cash Amount</label><input x-model.number="form.cash_amount" type="number" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
                <div><label class="text-xs text-gray-500">Online Amount</label><input x-model.number="form.online_amount" type="number" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
            </div>

            <div class="mb-4">
                <label class="text-xs text-gray-500">Received Amount</label>
                <input x-model.number="form.received_amount" type="number" min="0" :placeholder="calcTotal()" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
            </div>

            <textarea x-model="form.remarks" rows="2" placeholder="Remarks (optional)" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm mb-4 resize-none"></textarea>

            <div class="flex gap-3">
                <button @click="submitSale()" :disabled="submitting" class="flex-1 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold transition disabled:opacity-50">
                    <span x-show="!submitting">💾 Save Sale</span>
                    <span x-show="submitting">⏳ Saving...</span>
                </button>
                <button @click="showAddModal = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl font-medium transition">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function salesPage() {
    return {
        showAddModal: false,
        submitting: false,
        form: {
            customer_name: '',
            customer_phone: '',
            items: [{ service_name: '', quantity: 1, unit_price: 0, cost_price: 0, discount_amount: 0 }],
            discount_amount: 0,
            payment_mode: 'cash',
            cash_amount: 0,
            online_amount: 0,
            received_amount: '',
            remarks: '',
        },

        addItem() {
            this.form.items.push({ service_name: '', quantity: 1, unit_price: 0, cost_price: 0, discount_amount: 0 });
        },

        autoFillPrice(i) {
            const select = document.querySelectorAll('select[x-model="item.service_name"]')[i];
            if (!select) return;
            const opt = select.options[select.selectedIndex];
            if (opt) {
                this.form.items[i].unit_price = parseFloat(opt.dataset.price) || 0;
                this.form.items[i].cost_price = parseFloat(opt.dataset.cost) || 0;
            }
        },

        calcSubtotal() {
            return this.form.items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
        },

        calcTotal() {
            return Math.max(0, this.calcSubtotal() - (this.form.discount_amount || 0));
        },

        async submitSale() {
            if (!this.form.items[0]?.service_name) { alert('Please add at least one service'); return; }
            this.submitting = true;
            try {
                const res = await fetch('{{ route("billing.sales.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        ...this.form,
                        received_amount: this.form.received_amount || this.calcTotal(),
                    })
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Error saving sale');
                }
            } catch(e) { alert('Network error'); }
            this.submitting = false;
        },

        async deleteSale(id) {
            if (!confirm('Delete this sale?')) return;
            try {
                await fetch(`/billing/sales/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                });
                window.location.reload();
            } catch(e) {}
        },

        viewInvoice(id) {
            // Future: open invoice preview modal
            alert('Invoice preview coming soon!');
        }
    }
}
</script>
@endpush
