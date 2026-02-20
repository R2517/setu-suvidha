@extends('layouts.app')
@section('title', 'प्रोफाइल — SETU Suvidha')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="profilePage()">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा
    </a>

    {{-- Completion Banner --}}
    @if($completion < 100)
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-5 mb-6">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-bold text-amber-700 dark:text-amber-400"><i data-lucide="alert-circle" class="w-4 h-4 inline"></i> प्रोफाइल {{ $completion }}% पूर्ण</span>
            <span class="text-xs text-amber-600">{{ $completion }}/100%</span>
        </div>
        <div class="w-full bg-amber-200/50 dark:bg-amber-800/30 rounded-full h-2">
            <div class="bg-amber-500 h-2 rounded-full transition-all" style="width: {{ $completion }}%"></div>
        </div>
        <p class="text-xs text-amber-600 dark:text-amber-400 mt-2">सर्व फील्ड भरा — पूर्ण प्रोफाइलमुळे ग्राहकांचा विश्वास वाढतो!</p>
    </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-6 text-sm text-green-700 dark:text-green-400">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <button @click="activeTab = 'business'" :class="activeTab === 'business' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-800'" class="px-5 py-2.5 rounded-xl text-sm font-bold transition">
            <i data-lucide="building-2" class="w-4 h-4 inline"></i> व्यवसाय प्रोफाइल
        </button>
        <button @click="activeTab = 'hours'" :class="activeTab === 'hours' ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-800'" class="px-5 py-2.5 rounded-xl text-sm font-bold transition">
            <i data-lucide="clock" class="w-4 h-4 inline"></i> कामाचे तास
        </button>
        <button @click="activeTab = 'kiosk'" :class="activeTab === 'kiosk' ? 'bg-purple-500 text-white shadow-lg shadow-purple-500/25' : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-800'" class="px-5 py-2.5 rounded-xl text-sm font-bold transition">
            <i data-lucide="landmark" class="w-4 h-4 inline"></i> Kiosk Rates
        </button>
    </div>

    {{-- ═══════════ TAB 1: BUSINESS PROFILE ═══════════ --}}
    <div x-show="activeTab === 'business'" x-transition>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <i data-lucide="user-circle" class="w-5 h-5 text-amber-600"></i> व्यवसाय माहिती
            </h2>

            {{-- Logo Upload --}}
            <div class="flex items-center gap-6 mb-8 p-5 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800">
                <div class="relative w-20 h-20 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden bg-white dark:bg-gray-900 shrink-0">
                    @if($profile->logo_url)
                    <img src="{{ asset($profile->logo_url) }}" alt="Logo" class="w-full h-full object-cover" id="logo-preview">
                    @else
                    <i data-lucide="image-plus" class="w-8 h-8 text-gray-300" id="logo-placeholder"></i>
                    <img src="" alt="Logo" class="w-full h-full object-cover hidden" id="logo-preview">
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">दुकानाचा लोगो</p>
                    <p class="text-xs text-gray-400 mb-2">JPG, PNG, WebP — Max 2MB</p>
                    <label class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold cursor-pointer transition">
                        <i data-lucide="upload" class="w-3.5 h-3.5"></i> अपलोड करा
                        <input type="file" accept="image/*" class="hidden" @change="uploadLogo($event)">
                    </label>
                    <span x-show="logoUploading" class="text-xs text-amber-500 ml-2">Uploading...</span>
                    <span x-show="logoMsg" x-text="logoMsg" class="text-xs text-green-500 ml-2"></span>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पूर्ण नाव *</label>
                        <input type="text" name="full_name" value="{{ old('full_name', $profile->full_name ?? '') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                        @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">मोबाईल नंबर</label>
                        <input type="text" name="mobile" value="{{ old('mobile', $profile->mobile ?? '') }}" maxlength="15" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">दुकानाचे नाव</label>
                        <input type="text" name="shop_name" value="{{ old('shop_name', $profile->shop_name ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">दुकान प्रकार</label>
                        <select name="shop_type" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                            <option value="">निवडा</option>
                            <option value="setu" {{ old('shop_type', $profile->shop_type ?? '') === 'setu' ? 'selected' : '' }}>सेतु केंद्र</option>
                            <option value="csc" {{ old('shop_type', $profile->shop_type ?? '') === 'csc' ? 'selected' : '' }}>CSC केंद्र</option>
                            <option value="other" {{ old('shop_type', $profile->shop_type ?? '') === 'other' ? 'selected' : '' }}>इतर</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पत्ता</label>
                        <textarea name="address" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">{{ old('address', $profile->address ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">जिल्हा</label>
                        <select name="district" x-model="selectedDistrict" @change="fetchTalukas()" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                            <option value="">जिल्हा निवडा</option>
                            @foreach($districts as $districtName => $talukas)
                            <option value="{{ $districtName }}" {{ old('district', $profile->district ?? '') === $districtName ? 'selected' : '' }}>{{ $districtName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">तालुका</label>
                        <select name="taluka" x-model="selectedTaluka" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                            <option value="">तालुका निवडा</option>
                            <template x-for="t in talukas" :key="t">
                                <option :value="t" x-text="t" :selected="t === '{{ old('taluka', $profile->taluka ?? '') }}'"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">GST नंबर</label>
                        <input type="text" name="gst_number" value="{{ old('gst_number', $profile->gst_number ?? '') }}" maxlength="20" placeholder="22AAAAA0000A1Z5" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CSC ID</label>
                        <input type="text" name="csc_id" value="{{ old('csc_id', $profile->csc_id ?? '') }}" maxlength="50" placeholder="CSC केंद्र ID" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" class="btn-primary">💾 सेव्ह करा</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════ TAB 2: WORKING HOURS ═══════════ --}}
    <div x-show="activeTab === 'hours'" x-transition>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-blue-500"></i> कामाचे तास
                </h2>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm font-medium" :class="holidayMode ? 'text-red-500' : 'text-gray-400'">🏖️ सुट्टी मोड</span>
                    <div class="relative">
                        <input type="checkbox" x-model="holidayMode" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500 cursor-pointer"></div>
                    </div>
                </label>
            </div>
            <p x-show="holidayMode" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-3 text-sm text-red-600 mb-4">⚠️ सुट्टी मोड चालू — दुकान बंद दाखवले जाईल</p>

            <div class="space-y-3">
                <template x-for="(day, idx) in workingHours" :key="idx">
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <div class="w-20 shrink-0">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300" x-text="dayNames[idx]"></span>
                        </div>
                        <label class="flex items-center gap-2 shrink-0 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" x-model="day.is_open" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-checked:bg-green-500 rounded-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4 cursor-pointer"></div>
                            </div>
                            <span class="text-xs" :class="day.is_open ? 'text-green-600 font-bold' : 'text-red-400'">
                                <span x-show="day.is_open">Open</span><span x-show="!day.is_open">Closed</span>
                            </span>
                        </label>
                        <div x-show="day.is_open" class="flex items-center gap-2 flex-1">
                            <input type="time" x-model="day.start" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white">
                            <span class="text-gray-400 text-sm">to</span>
                            <input type="time" x-model="day.end" class="px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white">
                        </div>
                        <span x-show="!day.is_open" class="text-xs text-gray-400 italic">बंद</span>
                    </div>
                </template>
            </div>
            <div class="mt-6">
                <button type="button" @click="saveWorkingHours()" :disabled="savingHours" class="btn-primary disabled:opacity-50">
                    <span x-show="!savingHours">💾 सेव्ह करा</span>
                    <span x-show="savingHours">⏳ Saving...</span>
                </button>
                <span x-show="hoursMsg" x-text="hoursMsg" class="text-sm text-green-500 ml-3"></span>
            </div>
        </div>
    </div>

    {{-- ═══════════ TAB 3: KIOSK RATES ═══════════ --}}
    <div x-show="activeTab === 'kiosk'" x-transition>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="landmark" class="w-5 h-5 text-purple-500"></i> Kiosk Commission Rates
                </h2>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm font-medium" :class="kioskEnabled ? 'text-purple-600' : 'text-gray-400'">Kiosk सक्रिय</span>
                    <div class="relative">
                        <input type="checkbox" x-model="kioskEnabled" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:ring-2 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500 cursor-pointer"></div>
                    </div>
                </label>
            </div>

            <div x-show="kioskEnabled" x-transition class="space-y-6">
                {{-- Commission Slabs --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">📊 Commission Slabs (Withdraw/Deposit)</h3>
                    <div class="space-y-2">
                        <template x-for="(slab, i) in commissionSlabs" :key="i">
                            <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                                <span class="text-xs text-gray-400 w-6 shrink-0" x-text="(i+1) + '.'"></span>
                                <div class="flex items-center gap-2 flex-1">
                                    <span class="text-xs text-gray-500">₹</span>
                                    <input type="number" x-model="slab.amount_from" placeholder="From" class="w-24 px-2 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                                    <span class="text-xs text-gray-400">—</span>
                                    <span class="text-xs text-gray-500">₹</span>
                                    <input type="number" x-model="slab.amount_to" placeholder="To" class="w-24 px-2 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                                    <span class="text-xs text-gray-400 mx-1">→</span>
                                    <input type="number" x-model="slab.commission_percent" step="0.1" placeholder="%" class="w-20 px-2 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm">
                                    <span class="text-xs text-gray-500">%</span>
                                </div>
                                <button type="button" @click="commissionSlabs.splice(i, 1)" class="p-1 rounded hover:bg-red-50 text-red-400"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="commissionSlabs.push({amount_from: '', amount_to: '', commission_percent: ''})" class="mt-2 text-xs text-purple-500 hover:text-purple-600 font-medium">+ Slab जोडा</button>
                </div>

                {{-- Fixed Rates --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                        <label class="text-sm font-medium text-blue-700 dark:text-blue-400 block mb-2">💳 Balance Enquiry Rate</label>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">₹</span>
                            <input type="number" x-model="balanceEnquiryRate" step="0.5" class="w-full px-3 py-2 rounded-lg border border-blue-200 dark:border-blue-700 bg-white dark:bg-gray-900 text-sm">
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
                        <label class="text-sm font-medium text-green-700 dark:text-green-400 block mb-2">📋 Mini Statement Rate</label>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">₹</span>
                            <input type="number" x-model="miniStatementRate" step="0.5" class="w-full px-3 py-2 rounded-lg border border-green-200 dark:border-green-700 bg-white dark:bg-gray-900 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="!kioskEnabled" class="py-10 text-center text-gray-400">
                <i data-lucide="landmark" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                <p class="text-sm">Kiosk सेवा सक्रिय करा वरील toggle वापरून</p>
            </div>

            <div class="mt-6" x-show="kioskEnabled">
                <button type="button" @click="saveKioskRates()" :disabled="savingKiosk" class="btn-primary disabled:opacity-50">
                    <span x-show="!savingKiosk">💾 सेव्ह करा</span>
                    <span x-show="savingKiosk">⏳ Saving...</span>
                </button>
                <span x-show="kioskMsg" x-text="kioskMsg" class="text-sm text-green-500 ml-3"></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function profilePage() {
    return {
        activeTab: 'business',
        selectedDistrict: '{{ old('district', $profile->district ?? '') }}',
        selectedTaluka: '{{ old('taluka', $profile->taluka ?? '') }}',
        talukas: [],

        // Logo
        logoUploading: false,
        logoMsg: '',

        // Working Hours
        dayNames: ['सोमवार', 'मंगळवार', 'बुधवार', 'गुरुवार', 'शुक्रवार', 'शनिवार', 'रविवार'],
        workingHours: @json($profile->working_hours ?? [
            ['is_open' => true, 'start' => '09:00', 'end' => '20:00'],
            ['is_open' => true, 'start' => '09:00', 'end' => '20:00'],
            ['is_open' => true, 'start' => '09:00', 'end' => '20:00'],
            ['is_open' => true, 'start' => '09:00', 'end' => '20:00'],
            ['is_open' => true, 'start' => '09:00', 'end' => '20:00'],
            ['is_open' => true, 'start' => '09:00', 'end' => '18:00'],
            ['is_open' => false, 'start' => '09:00', 'end' => '13:00'],
        ]),
        holidayMode: {{ ($profile->holiday_mode ?? false) ? 'true' : 'false' }},
        savingHours: false,
        hoursMsg: '',

        // Kiosk
        kioskEnabled: {{ ($profile->kiosk_enabled ?? false) ? 'true' : 'false' }},
        commissionSlabs: @json($commissionSlabs->map(fn($s) => ['amount_from' => $s->amount_from, 'amount_to' => $s->amount_to, 'commission_percent' => $s->commission_percent])->values()->toArray() ?: [['amount_from' => '0', 'amount_to' => '5000', 'commission_percent' => '1'], ['amount_from' => '5001', 'amount_to' => '10000', 'commission_percent' => '0.8']]),
        balanceEnquiryRate: {{ $profile->kiosk_rates['balance_enquiry'] ?? 5 }},
        miniStatementRate: {{ $profile->kiosk_rates['mini_statement'] ?? 5 }},
        savingKiosk: false,
        kioskMsg: '',

        init() {
            if (this.selectedDistrict) this.fetchTalukas();
        },
        async fetchTalukas() {
            if (!this.selectedDistrict) { this.talukas = []; return; }
            try {
                const res = await fetch(`/api/talukas?district=${encodeURIComponent(this.selectedDistrict)}`);
                this.talukas = await res.json();
            } catch(e) { this.talukas = []; }
        },

        async uploadLogo(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.logoUploading = true;
            this.logoMsg = '';
            const fd = new FormData();
            fd.append('logo', file);
            try {
                const res = await fetch('{{ route("profile.logo") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: fd
                });
                const data = await res.json();
                if (data.success) {
                    const img = document.getElementById('logo-preview');
                    const ph = document.getElementById('logo-placeholder');
                    if (ph) ph.classList.add('hidden');
                    img.src = data.url;
                    img.classList.remove('hidden');
                    this.logoMsg = '✅ Logo uploaded!';
                }
            } catch(err) { this.logoMsg = '❌ Upload failed'; }
            this.logoUploading = false;
            setTimeout(() => this.logoMsg = '', 3000);
        },

        async saveWorkingHours() {
            this.savingHours = true;
            this.hoursMsg = '';
            try {
                const res = await fetch('{{ route("profile.working-hours") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ working_hours: this.workingHours, holiday_mode: this.holidayMode })
                });
                const data = await res.json();
                if (data.success) this.hoursMsg = '✅ Saved!';
            } catch(err) { this.hoursMsg = '❌ Error'; }
            this.savingHours = false;
            setTimeout(() => this.hoursMsg = '', 3000);
        },

        async saveKioskRates() {
            this.savingKiosk = true;
            this.kioskMsg = '';
            try {
                const res = await fetch('{{ route("profile.kiosk-rates") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        kiosk_enabled: this.kioskEnabled,
                        slabs: this.commissionSlabs,
                        balance_enquiry_rate: this.balanceEnquiryRate,
                        mini_statement_rate: this.miniStatementRate,
                    })
                });
                const data = await res.json();
                if (data.success) this.kioskMsg = '✅ Saved!';
            } catch(err) { this.kioskMsg = '❌ Error'; }
            this.savingKiosk = false;
            setTimeout(() => this.kioskMsg = '', 3000);
        },
    }
}
</script>
@endpush
@endsection
