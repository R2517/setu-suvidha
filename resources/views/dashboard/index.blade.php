@extends('layouts.app')
@section('title', 'डॅशबोर्ड — SETU Suvidha')

@section('content')
<div x-data="dashboardApp()" x-init="initTheme()">
    {{-- Quick Navigation Buttons --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-0">
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="theme-nav-btn group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="layout-grid" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>सेवा</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('wallet') }}"
               class="theme-nav-btn group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="wallet" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>वॉलेट</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('profile') }}"
               class="theme-nav-btn group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="user-circle" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>प्रोफाइल</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('billing.dashboard') }}"
               class="theme-nav-btn group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="receipt" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>बिलिंग</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('management') }}"
               class="theme-nav-btn group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="database" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>CRM</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('docslip.index') }}"
               class="theme-nav-btn group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="clipboard-list" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>DocSlip</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>

            {{-- Theme Picker --}}
            <div class="relative ml-auto">
                <button @click="showThemePicker = !showThemePicker"
                    class="group relative inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm
                           bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700
                           shadow-sm hover:shadow-md hover:-translate-y-0.5 hover:scale-[1.03] hover:border-amber-300 dark:hover:border-amber-600
                           active:scale-[0.98] transition-all duration-300 ease-out">
                    <i data-lucide="palette" class="w-4 h-4 text-amber-500 transition-transform duration-300 group-hover:rotate-12"></i>
                    <span class="hidden sm:inline">थीम</span>
                </button>
                <div x-show="showThemePicker" @click.away="showThemePicker = false" x-transition
                     class="absolute right-0 top-12 w-64 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 p-4 z-50">
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-3">थीम निवडा</p>
                    <div class="grid grid-cols-6 gap-2">
                        <template x-for="(theme, idx) in themes" :key="idx">
                            <button @click="setTheme(idx)" class="w-8 h-8 rounded-full border-2 transition-all hover:scale-110"
                                :style="{ backgroundColor: theme.dot }"
                                :class="selectedThemeIdx === idx ? 'border-gray-900 dark:border-white scale-110 ring-2 ring-offset-2 ring-gray-400' : 'border-transparent'">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Welcome Banner --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">नमस्कार, {{ $user->name }}! 🙏</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">तुमच्या सेवा डॅशबोर्डवर आपले स्वागत आहे</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <span class="inline-flex items-center gap-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-full text-xs font-medium">
                    <i data-lucide="file-text" class="w-3 h-3"></i> {{ $totalServices }} सेवा
                </span>
                <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-full text-xs font-medium">
                    <i data-lucide="check-circle" class="w-3 h-3"></i> {{ $readyServices }} सक्रिय
                </span>
                <span class="inline-flex items-center gap-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-3 py-1 rounded-full text-xs font-medium">
                    <i data-lucide="wallet" class="w-3 h-3"></i> ₹{{ number_format($walletBalance, 2) }}
                </span>
            </div>
        </div>

        {{-- News Ticker --}}
        <div class="theme-ticker rounded-xl px-4 py-2.5 mb-8 overflow-hidden flex items-center gap-3">
            <span class="flex-shrink-0 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded animate-pulse">LIVE</span>
            <div class="overflow-hidden whitespace-nowrap flex-1">
                <div class="animate-ticker-scroll inline-block">
                    <span class="text-white text-xs">
                        📢 SETU Suvidha v2.0 लाँच! नवीन फीचर्स उपलब्ध. &nbsp;&nbsp;|&nbsp;&nbsp;
                        💰 वॉलेट रिचार्ज करा आणि ₹50 बोनस मिळवा! &nbsp;&nbsp;|&nbsp;&nbsp;
                        📋 शेतकरी ओळखपत्र आता QR कोडसह उपलब्ध. &nbsp;&nbsp;|&nbsp;&nbsp;
                        🎨 24 नवीन थीम्स — तुमचा डॅशबोर्ड कस्टमाइज करा! &nbsp;&nbsp;|&nbsp;&nbsp;
                        📢 SETU Suvidha v2.0 लाँच! नवीन फीचर्स उपलब्ध. &nbsp;&nbsp;|&nbsp;&nbsp;
                        💰 वॉलेट रिचार्ज करा आणि ₹50 बोनस मिळवा! &nbsp;&nbsp;|&nbsp;&nbsp;
                        📋 शेतकरी ओळखपत्र आता QR कोडसह उपलब्ध. &nbsp;&nbsp;|&nbsp;&nbsp;
                        🎨 24 नवीन थीम्स — तुमचा डॅशबोर्ड कस्टमाइज करा!
                    </span>
                </div>
            </div>
        </div>

        {{-- Search + Customize --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
            <div class="relative max-w-md w-full sm:flex-1">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" x-model="searchQuery" placeholder="सेवा शोधा..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
            </div>
            <button @click="openCustomize()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm
                       bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700
                       shadow-sm hover:shadow-md hover:border-amber-300 dark:hover:border-amber-600 transition-all">
                <i data-lucide="settings-2" class="w-4 h-4 text-amber-500"></i>
                <span>डॅशबोर्ड कस्टमाइज करा</span>
            </button>
        </div>

        {{-- ═══ LIVE SERVICES ═══ --}}
        <div class="mb-10">
            <div class="flex items-center gap-2 mb-4">
                <span class="inline-flex items-center gap-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-3 py-1 rounded-full text-xs font-bold">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> LIVE सेवा
                </span>
                <span class="text-xs text-gray-400">({{ count($liveCards) }} सक्रिय)</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($liveCards as $index => $card)
                <div x-show="'{{ strtolower($card['title']) }}'.includes(searchQuery.toLowerCase()) || searchQuery === ''"
                     x-transition class="animate-card-enter" style="animation-delay: {{ $index * 50 }}ms">
                    <a href="{{ $card['path'] }}" class="block group">
                        <div class="relative bg-white dark:bg-gray-900 rounded-2xl p-5 border border-gray-100 dark:border-gray-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            @if($card['badge'])
                            <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full
                                @if($card['badgeType'] === 'ready') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                @elseif($card['badgeType'] === 'new') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($card['badgeType'] === 'fast') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                @endif">
                                {{ $card['badge'] }}
                            </span>
                            @endif
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3" style="background: {{ $card['iconBg'] }}">
                                <i data-lucide="{{ $card['icon'] }}" class="w-6 h-6" style="color: {{ $card['iconColor'] }}"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm leading-tight">{{ $card['title'] }}</h3>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ═══ UPCOMING SERVICES ═══ --}}
        @if(count($upcomingCards) > 0)
        <div class="mb-12">
            <div class="flex items-center gap-2 mb-4">
                <span class="inline-flex items-center gap-1.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-3 py-1 rounded-full text-xs font-bold">
                    <i data-lucide="clock" class="w-3 h-3"></i> लवकरच येत आहे
                </span>
                <span class="text-xs text-gray-400">({{ count($upcomingCards) }} आगामी)</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 opacity-75">
                @foreach($upcomingCards as $index => $card)
                <div x-show="'{{ strtolower($card['title']) }}'.includes(searchQuery.toLowerCase()) || searchQuery === ''"
                     x-transition class="animate-card-enter" style="animation-delay: {{ $index * 50 }}ms">
                    <div class="block group cursor-pointer" @click="alert('ही सेवा लवकरच उपलब्ध होईल!')">
                        <div class="relative bg-white dark:bg-gray-900 rounded-2xl p-5 border border-dashed border-gray-200 dark:border-gray-700 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            @if($card['badge'])
                            <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                {{ $card['badge'] ?: 'UPCOMING' }}
                            </span>
                            @else
                            <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-500">
                                UPCOMING
                            </span>
                            @endif
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-3 grayscale-[30%]" style="background: {{ $card['iconBg'] }}">
                                <i data-lucide="{{ $card['icon'] }}" class="w-6 h-6" style="color: {{ $card['iconColor'] }}"></i>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm leading-tight">{{ $card['title'] }}</h3>
                            <p class="text-xs text-gray-400 mt-1">लवकरच येत आहे...</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ═══ CUSTOMIZE MODAL ═══ --}}
    <div x-show="showCustomize" x-transition.opacity class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" @click.self="showCustomize = false">
        <div x-show="showCustomize" x-transition.scale.95 @click.stop
             class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg max-h-[85vh] flex flex-col">
            {{-- Header --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-200 dark:border-gray-800">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">डॅशबोर्ड कस्टमाइज करा</h2>
                    <p class="text-xs text-gray-500 mt-0.5">सेवा Drag & Drop करून क्रम लावा, चेकबॉक्स वापरून Show/Hide करा</p>
                </div>
                <button @click="showCustomize = false" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i data-lucide="x" class="w-5 h-5 text-gray-400"></i>
                </button>
            </div>
            {{-- Sortable List --}}
            <div class="flex-1 overflow-y-auto p-5" id="customizeList">
                @foreach($allCardsWithState as $cItem)
                <div class="sortable-item flex items-center gap-3 p-3 mb-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 transition-all hover:shadow-md"
                     data-id="{{ $cItem['id'] }}" data-visible="{{ $cItem['visible'] ? '1' : '0' }}">
                    <div class="drag-handle flex-shrink-0 text-gray-400 dark:text-gray-500 cursor-grab active:cursor-grabbing">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="12" r="1"/><circle cx="9" cy="5" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="19" r="1"/></svg>
                    </div>
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background: {{ $cItem['iconBg'] }}">
                        <i data-lucide="{{ $cItem['icon'] }}" class="w-4 h-4" style="color: {{ $cItem['iconColor'] }}"></i>
                    </div>
                    <span class="flex-1 text-sm font-medium text-gray-900 dark:text-white truncate">{{ $cItem['title'] }}</span>
                    <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                        <input type="checkbox" class="sr-only peer visibility-toggle" {{ $cItem['visible'] ? 'checked' : '' }}>
                        <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-500 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                </div>
                @endforeach
            </div>
            {{-- Footer --}}
            <div class="flex items-center justify-between p-5 border-t border-gray-200 dark:border-gray-800">
                <button @click="resetConfig()" class="text-sm text-gray-500 hover:text-red-500 transition flex items-center gap-1">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> रीसेट करा
                </button>
                <div class="flex gap-2">
                    <button @click="showCustomize = false" class="px-4 py-2 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">रद्द करा</button>
                    <button @click="saveConfig()" :disabled="saving"
                        class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:shadow-lg transition disabled:opacity-50">
                        <span x-show="!saving">सेव्ह करा</span>
                        <span x-show="saving" class="flex items-center gap-1"><i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> सेव्ह...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
var _sortable = null;

function startSortable() {
    setTimeout(function() {
        var el = document.getElementById('customizeList');
        if (!el) return;
        if (_sortable) { try { _sortable.destroy(); } catch(e){} }
        _sortable = Sortable.create(el, {
            animation: 250,
            delay: 0,
            draggable: '.sortable-item',
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            forceFallback: true,
            fallbackTolerance: 3,
        });
    }, 350);
}

function getConfigFromDOM() {
    var items = document.querySelectorAll('#customizeList .sortable-item');
    var order = [];
    var hidden = [];
    items.forEach(function(item) {
        var id = item.getAttribute('data-id');
        var cb = item.querySelector('.visibility-toggle');
        order.push(id);
        if (cb && !cb.checked) hidden.push(id);
    });
    return { order: order, hidden: hidden };
}

function dashboardApp() {
    return {
        showThemePicker: false,
        showCustomize: false,
        saving: false,
        searchQuery: '',
        selectedThemeIdx: parseInt(localStorage.getItem('setuThemeIdx') || '0'),
        themes: @json(config('themes')),
        get currentTheme() {
            return this.themes[this.selectedThemeIdx] || this.themes[0];
        },
        initTheme() {
            this.selectedThemeIdx = parseInt(localStorage.getItem('setuThemeIdx') || '0');
            this.applyTheme();
        },
        setTheme(idx) {
            this.selectedThemeIdx = idx;
            localStorage.setItem('setuThemeIdx', idx);
            this.applyTheme();
            this.showThemePicker = false;
        },
        applyTheme() {
            var t = this.themes[this.selectedThemeIdx] || this.themes[0];
            var r = document.documentElement;
            r.style.setProperty('--theme-nav', t.nav);
            r.style.setProperty('--theme-primary', t.primary);
            r.style.setProperty('--theme-dark-primary', t.dark_primary);
            r.style.setProperty('--theme-dot', t.dot);
            // Apply to quick nav buttons
            document.querySelectorAll('.theme-nav-btn').forEach(function(btn) {
                btn.style.background = t.nav;
            });
            // Apply to ticker
            var ticker = document.querySelector('.theme-ticker');
            if (ticker) ticker.style.background = t.nav;
            // Apply to navbar logo
            var logo = document.querySelector('.theme-logo');
            if (logo) logo.style.background = t.nav;
        },
        openCustomize() {
            this.showCustomize = true;
            startSortable();
        },
        async saveConfig() {
            this.saving = true;
            var cfg = getConfigFromDOM();
            try {
                var res = await fetch('{{ route("dashboard.save-config") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(cfg)
                });
                if (res.ok) {
                    this.showCustomize = false;
                    window.location.reload();
                } else {
                    alert('सेव्ह करण्यात त्रुटी आली.');
                }
            } catch (e) {
                alert('सेव्ह करण्यात त्रुटी आली. पुन्हा प्रयत्न करा.');
            }
            this.saving = false;
        },
        async resetConfig() {
            this.saving = true;
            try {
                var res = await fetch('{{ route("dashboard.save-config") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ order: [], hidden: [] })
                });
                if (res.ok) {
                    this.showCustomize = false;
                    window.location.reload();
                }
            } catch (e) {
                alert('रीसेट करण्यात त्रुटी आली.');
            }
            this.saving = false;
        }
    }
}
</script>
<style>
.sortable-ghost { opacity: 0.3; }
.sortable-chosen { box-shadow: 0 4px 20px rgba(245,158,11,0.4); border-color: #f59e0b !important; }
.drag-handle { touch-action: none; }
.theme-nav-btn { background: var(--theme-nav, linear-gradient(135deg,#d97706,#f59e0b)); transition: all 0.3s ease; }
.theme-ticker { background: var(--theme-nav, linear-gradient(135deg,#2563eb,#4f46e5)); }
</style>
@endpush
@endsection
