@extends('layouts.app')
@section('title', 'डॅशबोर्ड — SETU Suvidha')

@section('content')
<div x-data="dashboardApp()" x-init="initTheme()">
    {{-- Quick Navigation Buttons --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-0">
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-500/25
                      hover:shadow-xl hover:shadow-amber-500/40 hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="layout-grid" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>सेवा</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('wallet') }}"
               class="group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-lg shadow-emerald-500/25
                      hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="wallet" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>वॉलेट</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('profile') }}"
               class="group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      bg-gradient-to-r from-blue-500 to-indigo-500 text-white shadow-lg shadow-blue-500/25
                      hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="user-circle" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>प्रोफाइल</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('billing') }}"
               class="group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      bg-gradient-to-r from-purple-500 to-violet-500 text-white shadow-lg shadow-purple-500/25
                      hover:shadow-xl hover:shadow-purple-500/40 hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="receipt" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>बिलिंग</span>
                <span class="absolute inset-0 rounded-xl bg-white/0 group-hover:bg-white/10 transition-all duration-300"></span>
            </a>
            <a href="{{ route('management') }}"
               class="group relative inline-flex items-center gap-2.5 px-5 py-2.5 rounded-xl font-semibold text-sm
                      bg-gradient-to-r from-rose-500 to-pink-500 text-white shadow-lg shadow-rose-500/25
                      hover:shadow-xl hover:shadow-rose-500/40 hover:-translate-y-0.5 hover:scale-[1.03]
                      active:scale-[0.98] transition-all duration-300 ease-out">
                <i data-lucide="database" class="w-4 h-4 transition-transform duration-300 group-hover:rotate-12"></i>
                <span>CRM</span>
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
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl px-4 py-2.5 mb-8 overflow-hidden flex items-center gap-3">
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

        {{-- Search Filter --}}
        <div class="mb-6">
            <div class="relative max-w-md">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" x-model="searchQuery" placeholder="सेवा शोधा..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
            </div>
        </div>

        {{-- Service Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 pb-12">
            @foreach($serviceCards as $index => $card)
            <div x-show="'{{ strtolower($card['title']) }}'.includes(searchQuery.toLowerCase()) || searchQuery === ''"
                 x-transition
                 class="animate-card-enter"
                 style="animation-delay: {{ $index * 50 }}ms">
                @if($card['ready'])
                <a href="{{ $card['path'] }}" class="block group">
                @else
                <div class="block group cursor-pointer" @click="alert('ही सेवा लवकरच उपलब्ध होईल!')">
                @endif
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
                        @if(!$card['ready'])
                        <p class="text-xs text-gray-400 mt-1">लवकरच येत आहे...</p>
                        @endif
                    </div>
                @if($card['ready'])
                </a>
                @else
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
function dashboardApp() {
    return {
        showThemePicker: false,
        searchQuery: '',
        selectedThemeIdx: parseInt(localStorage.getItem('setuThemeIdx') || '0'),
        themes: @json(config('themes')),
        get currentTheme() {
            return this.themes[this.selectedThemeIdx] || this.themes[0];
        },
        initTheme() {
            this.selectedThemeIdx = parseInt(localStorage.getItem('setuThemeIdx') || '0');
        },
        setTheme(idx) {
            this.selectedThemeIdx = idx;
            localStorage.setItem('setuThemeIdx', idx);
            this.showThemePicker = false;
        }
    }
}
</script>
@endpush
@endsection
