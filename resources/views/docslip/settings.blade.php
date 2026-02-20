@extends('layouts.app')
@section('title', 'DocSlip सेटिंग्ज — SETU Suvidha')

@section('content')
<div x-data="docslipSettings()" class="min-h-screen bg-gray-50 dark:bg-gray-950">

    {{-- Page Header --}}
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('docslip.index') }}" class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <i data-lucide="arrow-left" class="w-4 h-4 text-gray-600 dark:text-gray-400"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">DocSlip सेटिंग्ज</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">सेवा, कागदपत्रे आणि मॅपिंग्ज व्यवस्थापित करा</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('docslip.load-defaults') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-purple-600 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> डिफॉल्ट लोड करा
                        </button>
                    </form>
                    <form method="POST" action="{{ route('docslip.reset') }}" onsubmit="return confirm('सर्व DocSlip डेटा हटवला जाईल! पक्के आहात?')" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-red-600 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> रीसेट
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 rounded-xl px-4 py-3 text-sm text-green-700 dark:text-green-400 flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Tabs --}}
        <div class="flex border-b border-gray-200 dark:border-gray-800 mb-6 gap-1">
            <button @click="activeTab = 'services'" :class="activeTab === 'services' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition flex items-center gap-1.5">
                <i data-lucide="layers" class="w-3.5 h-3.5"></i> सेवा <span class="text-xs bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded-full ml-1">{{ count($services) }}</span>
            </button>
            <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition flex items-center gap-1.5">
                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> कागदपत्रे <span class="text-xs bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded-full ml-1">{{ count($documents) }}</span>
            </button>
            <button @click="activeTab = 'mappings'" :class="activeTab === 'mappings' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="px-4 py-2.5 text-sm font-medium border-b-2 transition flex items-center gap-1.5">
                <i data-lucide="link" class="w-3.5 h-3.5"></i> मॅपिंग्ज
            </button>
        </div>

        {{-- ═══════════ TAB 1: SERVICES ═══════════ --}}
        <div x-show="activeTab === 'services'" x-transition>
            {{-- Add Form --}}
            <form method="POST" action="{{ route('docslip.services.store') }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 mb-5">
                @csrf
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-1.5"><i data-lucide="plus-circle" class="w-4 h-4 text-green-500"></i> नवीन सेवा जोडा</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <input name="name_mr" required placeholder="मराठी नाव *" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <input name="name_en" required placeholder="English Name *" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <div class="flex gap-2">
                        <input name="icon" placeholder="Icon (lucide)" class="flex-1 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-bold transition shrink-0">जोडा</button>
                    </div>
                </div>
            </form>

            {{-- Services List --}}
            <div class="space-y-2">
                @forelse($services as $svc)
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 px-5 py-3" x-data="{ editing: false }">
                    {{-- View Mode --}}
                    <div x-show="!editing" class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                <i data-lucide="{{ $svc->icon ?? 'file-text' }}" class="w-4 h-4 text-gray-500"></i>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $svc->name_mr }}</span>
                                <span class="text-xs text-gray-400 ml-1">({{ $svc->name_en }})</span>
                            </div>
                            @if(!$svc->is_active)
                            <span class="text-[10px] bg-red-100 dark:bg-red-900/30 text-red-600 px-2 py-0.5 rounded-full">Inactive</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[10px] text-gray-400 mr-2">{{ $svc->documents->count() }} docs</span>
                            <button @click="editing = true" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"><i data-lucide="pencil" class="w-3.5 h-3.5 text-gray-400"></i></button>
                            <form method="POST" action="{{ route('docslip.services.destroy', $svc->id) }}" onsubmit="return confirm('हटवायचे?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 text-red-400"></i></button>
                            </form>
                        </div>
                    </div>
                    {{-- Edit Mode --}}
                    <form x-show="editing" method="POST" action="{{ route('docslip.services.update', $svc->id) }}" class="flex flex-wrap items-end gap-3">
                        @csrf @method('PUT')
                        <div class="flex-1 min-w-[150px]"><label class="text-[10px] text-gray-400">मराठी</label><input name="name_mr" value="{{ $svc->name_mr }}" required class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
                        <div class="flex-1 min-w-[150px]"><label class="text-[10px] text-gray-400">English</label><input name="name_en" value="{{ $svc->name_en }}" required class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
                        <div class="w-24"><label class="text-[10px] text-gray-400">Icon</label><input name="icon" value="{{ $svc->icon }}" class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
                        <label class="flex items-center gap-1.5 text-xs"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" {{ $svc->is_active ? 'checked' : '' }} class="rounded border-gray-300"> Active</label>
                        <button type="submit" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-bold transition">Save</button>
                        <button type="button" @click="editing = false" class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-medium transition">Cancel</button>
                    </form>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400 text-sm">कोणतीही सेवा नाही. "डिफॉल्ट लोड करा" बटणावर क्लिक करा.</div>
                @endforelse
            </div>
        </div>

        {{-- ═══════════ TAB 2: DOCUMENTS ═══════════ --}}
        <div x-show="activeTab === 'documents'" x-transition>
            {{-- Add Form --}}
            <form method="POST" action="{{ route('docslip.documents.store') }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 mb-5">
                @csrf
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-1.5"><i data-lucide="plus-circle" class="w-4 h-4 text-green-500"></i> नवीन कागदपत्र जोडा</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <input name="name_mr" required placeholder="मराठी नाव *" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <input name="name_en" required placeholder="English Name *" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-bold transition">जोडा</button>
                </div>
            </form>

            {{-- Documents List --}}
            <div class="space-y-2">
                @forelse($documents as $doc)
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 px-5 py-3" x-data="{ editing: false }">
                    <div x-show="!editing" class="flex items-center justify-between gap-3">
                        <div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $doc->name_mr }}</span>
                            <span class="text-xs text-gray-400 ml-1">({{ $doc->name_en }})</span>
                            @if(!$doc->is_active)
                            <span class="text-[10px] bg-red-100 dark:bg-red-900/30 text-red-600 px-2 py-0.5 rounded-full ml-1">Inactive</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button @click="editing = true" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"><i data-lucide="pencil" class="w-3.5 h-3.5 text-gray-400"></i></button>
                            <form method="POST" action="{{ route('docslip.documents.destroy', $doc->id) }}" onsubmit="return confirm('हटवायचे?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition"><i data-lucide="trash-2" class="w-3.5 h-3.5 text-red-400"></i></button>
                            </form>
                        </div>
                    </div>
                    <form x-show="editing" method="POST" action="{{ route('docslip.documents.update', $doc->id) }}" class="flex flex-wrap items-end gap-3">
                        @csrf @method('PUT')
                        <div class="flex-1 min-w-[150px]"><label class="text-[10px] text-gray-400">मराठी</label><input name="name_mr" value="{{ $doc->name_mr }}" required class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
                        <div class="flex-1 min-w-[150px]"><label class="text-[10px] text-gray-400">English</label><input name="name_en" value="{{ $doc->name_en }}" required class="w-full px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
                        <label class="flex items-center gap-1.5 text-xs"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" {{ $doc->is_active ? 'checked' : '' }} class="rounded border-gray-300"> Active</label>
                        <button type="submit" class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-bold transition">Save</button>
                        <button type="button" @click="editing = false" class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-medium transition">Cancel</button>
                    </form>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400 text-sm">कोणतेही कागदपत्र नाही.</div>
                @endforelse
            </div>
        </div>

        {{-- ═══════════ TAB 3: MAPPINGS ═══════════ --}}
        <div x-show="activeTab === 'mappings'" x-transition>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5 mb-5">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1">सेवा → कागदपत्रे मॅपिंग</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">प्रत्येक सेवेसाठी आवश्यक कागदपत्रे निवडा. बदल स्वयंचलितपणे सेव्ह होतात.</p>

                <div class="space-y-4">
                    @foreach($services as $svc)
                    <div class="border border-gray-100 dark:border-gray-800 rounded-xl p-4" x-data="mappingRow({{ $svc->id }}, {{ json_encode($mappings[$svc->id] ?? []) }})">
                        <div class="flex items-center justify-between mb-3 cursor-pointer" @click="expanded = !expanded">
                            <div class="flex items-center gap-2">
                                <i data-lucide="{{ $svc->icon ?? 'file-text' }}" class="w-4 h-4 text-orange-500"></i>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $svc->name_mr }}</span>
                                <span class="text-xs text-gray-400">({{ $svc->name_en }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full" :class="selectedDocs.length > 0 ? 'bg-green-100 dark:bg-green-900/30 text-green-600' : 'bg-gray-100 dark:bg-gray-800 text-gray-400'" x-text="selectedDocs.length + ' docs'"></span>
                                <span x-show="saving" class="text-xs text-orange-500">Saving...</span>
                                <span x-show="saved" class="text-xs text-green-500">✓</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="expanded && 'rotate-180'"></i>
                            </div>
                        </div>
                        <div x-show="expanded" x-collapse class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                            @foreach($documents as $doc)
                            <label class="flex items-center gap-2 px-3 py-2 rounded-lg border cursor-pointer transition text-sm"
                                :class="selectedDocs.includes({{ $doc->id }}) ? 'border-orange-300 bg-orange-50 dark:border-orange-700 dark:bg-orange-900/20' : 'border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800'">
                                <input type="checkbox" value="{{ $doc->id }}"
                                    :checked="selectedDocs.includes({{ $doc->id }})"
                                    @change="toggleDoc({{ $doc->id }})"
                                    class="rounded border-gray-300 text-orange-500 focus:ring-orange-500/20">
                                <span class="text-gray-700 dark:text-gray-300">{{ $doc->name_mr }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function docslipSettings() {
    return {
        activeTab: 'services',
    }
}

function mappingRow(serviceId, initialDocs) {
    return {
        expanded: false,
        selectedDocs: [...initialDocs],
        saving: false,
        saved: false,

        async toggleDoc(docId) {
            const idx = this.selectedDocs.indexOf(docId);
            if (idx > -1) {
                this.selectedDocs.splice(idx, 1);
            } else {
                this.selectedDocs.push(docId);
            }
            await this.save();
        },

        async save() {
            this.saving = true;
            this.saved = false;
            try {
                await fetch(`/docslip/services/${serviceId}/documents`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ document_ids: this.selectedDocs })
                });
                this.saved = true;
                setTimeout(() => this.saved = false, 2000);
            } catch (e) {
                console.error('Save mapping error:', e);
            }
            this.saving = false;
        }
    }
}
</script>
@endpush
