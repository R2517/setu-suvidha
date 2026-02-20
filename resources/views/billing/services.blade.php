@extends('layouts.billing')
@section('title', 'Services — SETU Suvidha Billing')

@section('billing-content')
<div class="p-6 lg:p-8" x-data="servicesPage()">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('billing.dashboard') }}" class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-emerald-600 mb-1"><i data-lucide="arrow-left" class="w-3 h-3"></i> Dashboard</a>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2"><i data-lucide="settings" class="w-5 h-5 text-amber-500"></i> Services / सेवा</h1>
        </div>
        <button @click="showAddModal = true" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5"><i data-lucide="plus" class="w-4 h-4"></i> Add Service</button>
    </div>

    {{-- Services grouped by category --}}
    @foreach($categories as $cat)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 mb-4 overflow-hidden" x-data="{ expanded: true }">
        <div class="px-5 py-3 bg-gray-50 dark:bg-gray-800 flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $cat }}</span>
                <span class="text-xs px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-medium">{{ $services->where('category', $cat)->count() }}</span>
            </div>
            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="expanded && 'rotate-180'"></i>
        </div>
        <div x-show="expanded" x-collapse>
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @foreach($services->where('category', $cat) as $svc)
                <div class="px-5 py-3 flex items-center justify-between" x-data="{ editing: false }">
                    <div x-show="!editing" class="flex items-center gap-4 flex-1">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $svc->name }}</span>
                        <span class="text-xs text-emerald-600 font-bold">₹{{ number_format($svc->default_price, 0) }}</span>
                        @if($svc->cost_price > 0)<span class="text-xs text-gray-400">Cost: ₹{{ number_format($svc->cost_price, 0) }}</span>@endif
                        <span class="text-xs text-emerald-500 font-medium">Profit: ₹{{ number_format($svc->default_price - $svc->cost_price, 0) }}</span>
                    </div>
                    <div x-show="!editing" class="flex items-center gap-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" {{ $svc->is_active ? 'checked' : '' }} @change="toggleService({{ $svc->id }}, $event.target.checked)" class="sr-only peer">
                            <div class="w-8 h-4 bg-gray-200 peer-checked:bg-emerald-500 rounded-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-3 after:w-3 after:transition-all peer-checked:after:translate-x-4"></div>
                        </label>
                        <button @click="editing = true" class="p-1 rounded hover:bg-gray-100 text-gray-500"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
                        @if(!$svc->is_system_default)
                        <button @click="deleteService({{ $svc->id }})" class="p-1 rounded hover:bg-red-50 text-red-400"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                        @endif
                    </div>

                    {{-- Inline Edit --}}
                    <div x-show="editing" class="flex items-center gap-2 flex-1">
                        <input id="svc-name-{{ $svc->id }}" value="{{ $svc->name }}" class="flex-1 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <input id="svc-price-{{ $svc->id }}" value="{{ $svc->default_price }}" type="number" class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm" placeholder="Price">
                        <input id="svc-cost-{{ $svc->id }}" value="{{ $svc->cost_price }}" type="number" class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm" placeholder="Cost">
                        <button @click="saveService({{ $svc->id }}); editing = false" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold">Save</button>
                        <button @click="editing = false" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs">Cancel</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    @if($services->count() === 0)
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 px-6 py-16 text-center text-gray-400">
        <i data-lucide="settings" class="w-16 h-16 mx-auto mb-4 opacity-20"></i>
        <p class="text-sm">No services added yet. Click "Add Service" to get started.</p>
    </div>
    @endif

    {{-- Add Service Modal --}}
    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/50" @click="showAddModal = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md shadow-2xl p-6" @click.stop>
            <h2 class="text-lg font-bold mb-4"><i data-lucide="plus-circle" class="w-5 h-5 inline text-amber-500"></i> Add Service</h2>
            <div class="space-y-3">
                <input x-model="svcForm.name" type="text" placeholder="Service Name *" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <input x-model="svcForm.category" type="text" placeholder="Category (e.g. General, Printing)" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                <div class="grid grid-cols-2 gap-3">
                    <input x-model.number="svcForm.default_price" type="number" min="0" placeholder="Sell Price ₹ *" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <input x-model.number="svcForm.cost_price" type="number" min="0" placeholder="Cost Price ₹" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                </div>
            </div>
            <div class="flex gap-3 mt-5">
                <button @click="submitService()" :disabled="svcSubmitting" class="flex-1 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold transition disabled:opacity-50">💾 Save</button>
                <button @click="showAddModal = false" class="px-6 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl font-medium">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function servicesPage() {
    return {
        showAddModal: false,
        svcSubmitting: false,
        svcForm: { name: '', category: 'General', default_price: '', cost_price: '' },

        async submitService() {
            if (!this.svcForm.name || !this.svcForm.default_price) { alert('Name and price required'); return; }
            this.svcSubmitting = true;
            try {
                const res = await fetch('{{ route("billing.services.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.svcForm)
                });
                if ((await res.json()).success) window.location.reload();
            } catch(e) { alert('Error'); }
            this.svcSubmitting = false;
        },

        async saveService(id) {
            const name = document.getElementById('svc-name-' + id).value;
            const price = document.getElementById('svc-price-' + id).value;
            const cost = document.getElementById('svc-cost-' + id).value;
            await fetch(`/billing/services/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ name, default_price: price, cost_price: cost })
            });
            window.location.reload();
        },

        async toggleService(id, active) {
            await fetch(`/billing/services/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ is_active: active })
            });
        },

        async deleteService(id) {
            if (!confirm('Delete this service?')) return;
            await fetch(`/billing/services/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
            window.location.reload();
        }
    }
}
</script>
@endpush
