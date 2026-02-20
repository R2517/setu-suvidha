@extends('layouts.billing')
@section('title', 'Customers — SETU Suvidha Billing')

@section('billing-content')
<div class="p-6 lg:p-8" x-data="customersPage()">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('billing.dashboard') }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-emerald-600 mb-1"><i data-lucide="arrow-left" class="w-3 h-3"></i> Dashboard</a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="users" class="w-5 h-5 text-blue-500"></i> Customers / ग्राहक</h1>
        </div>
        <button @click="showAddModal = true" class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5"><i data-lucide="user-plus" class="w-4 h-4"></i> Add Customer</button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <p class="text-xs text-gray-500 mb-1">Total Customers</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <p class="text-xs text-gray-500 mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-emerald-600">₹{{ number_format($stats['revenue'], 0) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
            <p class="text-xs text-gray-500 mb-1">Total Visits</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['visits']) }}</p>
        </div>
    </div>

    {{-- Search --}}
    <form method="GET" class="mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or mobile..." class="w-full sm:w-80 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
    </form>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($customers->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">Mobile</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Visits</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">Total Spent</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 hidden sm:table-cell">Due</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
                    @foreach($customers as $cust)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $cust->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $cust->mobile ?: '—' }}</td>
                        <td class="px-4 py-3 text-center"><span class="text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 font-bold">{{ $cust->total_visits }}</span></td>
                        <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">₹{{ number_format($cust->total_spent, 0) }}</td>
                        <td class="px-4 py-3 text-right hidden sm:table-cell {{ $cust->total_due > 0 ? 'text-red-500 font-bold' : 'text-gray-400' }}">₹{{ number_format($cust->total_due, 0) }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('billing.customers.show', $cust->id) }}" class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-500" title="View"><i data-lucide="eye" class="w-3.5 h-3.5"></i></a>
                                <button @click="deleteCustomer({{ $cust->id }})" class="p-1.5 rounded-lg hover:bg-red-50 text-red-400" title="Delete"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">{{ $customers->withQueryString()->links() }}</div>
        @else
        <div class="px-6 py-16 text-center text-gray-400">
            <i data-lucide="users" class="w-16 h-16 mx-auto mb-4 opacity-20"></i>
            <p class="text-sm">No customers yet. They'll appear automatically when you create sales.</p>
        </div>
        @endif
    </div>

    {{-- Add Customer Modal --}}
    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md shadow-2xl p-6" @click.stop>
            <h2 class="text-lg font-bold mb-4"><i data-lucide="user-plus" class="w-5 h-5 inline text-blue-500"></i> Add Customer</h2>
            <div class="space-y-3">
                <input x-model="custForm.name" type="text" placeholder="Customer Name *" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <input x-model="custForm.mobile" type="text" placeholder="Mobile" maxlength="10" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <textarea x-model="custForm.address" rows="2" placeholder="Address" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm resize-none"></textarea>
                <textarea x-model="custForm.notes" rows="2" placeholder="Notes" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm resize-none"></textarea>
            </div>
            <div class="flex gap-3 mt-5">
                <button @click="submitCustomer()" :disabled="custSubmitting" class="flex-1 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-xl font-bold transition disabled:opacity-50">💾 Save</button>
                <button @click="showAddModal = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl font-medium">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function customersPage() {
    return {
        showAddModal: false,
        custSubmitting: false,
        custForm: { name: '', mobile: '', address: '', notes: '' },
        async submitCustomer() {
            if (!this.custForm.name) { alert('Name required'); return; }
            this.custSubmitting = true;
            try {
                const res = await fetch('{{ route("billing.customers.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.custForm)
                });
                if ((await res.json()).success) window.location.reload();
            } catch(e) { alert('Error'); }
            this.custSubmitting = false;
        },
        async deleteCustomer(id) {
            if (!confirm('Delete this customer?')) return;
            await fetch(`/billing/customers/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
            window.location.reload();
        }
    }
}
</script>
@endpush
