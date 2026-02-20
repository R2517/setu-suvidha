@extends('layouts.billing')
@section('title', 'Services — SETU Suvidha Billing')

@section('billing-content')
<div class="p-6 lg:p-8" x-data="servicesPage()">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-5">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="settings" class="w-5 h-5 text-amber-500"></i> Services / सेवा
            </h1>
            <p class="text-xs text-gray-500 mt-0.5">CSC & Maha e-Seva services — English + मराठी</p>
        </div>
        <button @click="showAddModal = true" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold transition flex items-center gap-1.5">
            <i data-lucide="plus" class="w-4 h-4"></i> Add Service
        </button>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-3">
            <p class="text-[10px] font-medium text-gray-400 uppercase">Total</p>
            <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $services->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-3">
            <p class="text-[10px] font-medium text-gray-400 uppercase">Active</p>
            <p class="text-lg font-bold text-emerald-600">{{ $services->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-3">
            <p class="text-[10px] font-medium text-gray-400 uppercase">Categories</p>
            <p class="text-lg font-bold text-blue-600">{{ $categories->count() }}</p>
        </div>
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-3">
            <p class="text-[10px] font-medium text-gray-400 uppercase">Inactive</p>
            <p class="text-lg font-bold text-red-500">{{ $services->where('is_active', false)->count() }}</p>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-3 mb-5 flex items-center gap-3">
        <i data-lucide="search" class="w-4 h-4 text-gray-400 flex-shrink-0"></i>
        <input x-model="search" type="text" placeholder="Search in English or मराठी..." class="flex-1 bg-transparent text-sm outline-none text-gray-900 dark:text-white placeholder-gray-400">
        <template x-if="search.length > 0">
            <button @click="search = ''" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-4 h-4"></i></button>
        </template>
        <select x-model="filterCat" class="text-xs bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5 text-gray-600 dark:text-gray-400">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
    </div>

    {{-- Services grouped by category --}}
    @foreach($categories as $cat)
    <div class="mb-4" x-show="filterCat === '' || filterCat === '{{ $cat }}'" x-data="{ expanded: true }">
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden"
             x-show="filteredCount('{{ $cat }}') > 0">
            {{-- Category Header --}}
            <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 flex items-center justify-between cursor-pointer border-b border-gray-100 dark:border-gray-800" @click="expanded = !expanded">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $cat }}</span>
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-bold" x-text="filteredCount('{{ $cat }}')"></span>
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" :class="expanded && 'rotate-180'"></i>
            </div>

            {{-- Service Rows --}}
            <div x-show="expanded" x-collapse>
                @foreach($services->where('category', $cat) as $svc)
                <div class="svc-row border-b border-gray-50 dark:border-gray-800/50 last:border-0"
                     data-name="{{ strtolower($svc->name) }}"
                     data-name-mr="{{ $svc->name_mr }}"
                     data-cat="{{ $cat }}"
                     x-show="matchSearch('{{ strtolower(addslashes($svc->name)) }}', '{{ addslashes($svc->name_mr) }}')"
                     x-data="{ editing: false }">

                    {{-- Display Mode --}}
                    <div x-show="!editing" class="px-4 py-2.5 flex items-center gap-3 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                        {{-- Toggle --}}
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" {{ $svc->is_active ? 'checked' : '' }} @change="toggleService({{ $svc->id }}, $event.target.checked)" class="sr-only peer">
                            <div class="w-7 h-3.5 bg-gray-200 peer-checked:bg-emerald-500 rounded-full after:content-[''] after:absolute after:top-[1px] after:left-[1px] after:bg-white after:rounded-full after:h-2.5 after:w-2.5 after:transition-all peer-checked:after:translate-x-3.5"></div>
                        </label>

                        {{-- Names --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate {{ !$svc->is_active ? 'line-through opacity-50' : '' }}">{{ $svc->name }}</p>
                            @if($svc->name_mr)
                            <p class="text-[11px] text-gray-500 truncate">{{ $svc->name_mr }}</p>
                            @endif
                        </div>

                        {{-- Prices --}}
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400">Purchase</p>
                                <p class="text-xs font-bold text-red-500">₹{{ number_format($svc->cost_price, 0) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400">Sale</p>
                                <p class="text-xs font-bold text-emerald-600">₹{{ number_format($svc->default_price, 0) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400">Profit</p>
                                <p class="text-xs font-bold {{ ($svc->default_price - $svc->cost_price) >= 0 ? 'text-blue-600' : 'text-red-600' }}">₹{{ number_format($svc->default_price - $svc->cost_price, 0) }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button @click="editing = true" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400 hover:text-amber-500 transition">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                            </button>
                            <button @click="deleteService({{ $svc->id }})" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-500 transition">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Edit Mode --}}
                    <div x-show="editing" class="px-4 py-3 bg-amber-50/50 dark:bg-amber-900/10">
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            <input id="svc-name-{{ $svc->id }}" value="{{ $svc->name }}" placeholder="English Name" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                            <input id="svc-namemr-{{ $svc->id }}" value="{{ $svc->name_mr }}" placeholder="मराठी नाव" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                        </div>
                        <div class="flex items-center gap-2">
                            <input id="svc-cat-{{ $svc->id }}" value="{{ $svc->category }}" placeholder="Category" class="flex-1 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs">
                            <input id="svc-cost-{{ $svc->id }}" value="{{ $svc->cost_price }}" type="number" placeholder="Purchase ₹" class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs">
                            <input id="svc-price-{{ $svc->id }}" value="{{ $svc->default_price }}" type="number" placeholder="Sale ₹" class="w-24 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs">
                            <button @click="saveService({{ $svc->id }}); editing = false" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition">Save</button>
                            <button @click="editing = false" class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-lg text-xs transition">Cancel</button>
                        </div>
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
        <p class="text-sm">No services yet. Click "Add Service" to get started.</p>
    </div>
    @endif

    {{-- Add Service Modal --}}
    <div x-show="showAddModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md shadow-2xl border border-gray-200 dark:border-gray-800" @click.stop>
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <h2 class="text-lg font-bold flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-5 h-5 text-amber-500"></i> Add Service
                </h2>
            </div>
            <div class="p-6 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">English Name *</label>
                        <input x-model="svcForm.name" type="text" placeholder="e.g. PAN Card" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">मराठी नाव</label>
                        <input x-model="svcForm.name_mr" type="text" placeholder="उदा. पॅन कार्ड" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">Category</label>
                    <input x-model="svcForm.category" type="text" placeholder="e.g. इतर सेवा (Other)" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">Purchase Price ₹</label>
                        <input x-model.number="svcForm.cost_price" type="number" min="0" placeholder="0" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">Sale Price ₹ *</label>
                        <input x-model.number="svcForm.default_price" type="number" min="0" placeholder="50" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex gap-3">
                <button @click="submitService()" :disabled="svcSubmitting" class="flex-1 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-sm transition disabled:opacity-50">Save Service</button>
                <button @click="showAddModal = false" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl text-sm font-medium">Cancel</button>
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
        search: '',
        filterCat: '',
        svcForm: { name: '', name_mr: '', category: '', default_price: '', cost_price: '' },

        matchSearch(nameEn, nameMr) {
            if (!this.search) return true;
            const q = this.search.toLowerCase();
            return nameEn.includes(q) || nameMr.toLowerCase().includes(q);
        },

        filteredCount(cat) {
            let count = 0;
            document.querySelectorAll(`.svc-row[data-cat="${cat}"]`).forEach(el => {
                const nameEn = el.dataset.name;
                const nameMr = el.dataset.nameMr;
                if (this.matchSearch(nameEn, nameMr.toLowerCase())) count++;
            });
            return count;
        },

        async submitService() {
            if (!this.svcForm.name || !this.svcForm.default_price) { alert('Name and sale price required'); return; }
            this.svcSubmitting = true;
            try {
                const res = await fetch('{{ route("billing.services.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.svcForm)
                });
                if ((await res.json()).success) window.location.reload();
            } catch(e) { alert('Error saving service'); }
            this.svcSubmitting = false;
        },

        async saveService(id) {
            const name = document.getElementById('svc-name-' + id).value;
            const name_mr = document.getElementById('svc-namemr-' + id).value;
            const category = document.getElementById('svc-cat-' + id).value;
            const price = document.getElementById('svc-price-' + id).value;
            const cost = document.getElementById('svc-cost-' + id).value;
            await fetch(`/billing/services/${id}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ name, name_mr, category, default_price: price, cost_price: cost })
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
