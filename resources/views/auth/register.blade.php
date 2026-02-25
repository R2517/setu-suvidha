@extends('layouts.app')
@section('title', 'नोंदणी — SETU Suvidha')

@section('content')
<div class="min-h-screen flex" x-data="registerForm()">
    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-amber-600 via-orange-600 to-amber-700 text-white flex-col justify-center items-center p-12 overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="absolute top-20 left-20 w-32 h-32 bg-white/5 rounded-full animate-pulse"></div>
        <div class="absolute bottom-32 right-16 w-48 h-48 bg-white/5 rounded-full animate-pulse" style="animation-delay: 1s;"></div>

        <div class="relative z-10 text-center max-w-md">
            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="landmark" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">SETU Suvidha</h1>
            <p class="text-white/80 mb-10">सर्व सरकारी सेवांसाठी जलद नोंदणी करा.</p>

            <div class="space-y-4 text-left">
                <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3">
                    <i data-lucide="map" class="w-5 h-5 text-amber-200"></i>
                    <span class="text-sm">महाराष्ट्र जिल्हा + तालुका निवडा</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3">
                    <i data-lucide="wallet" class="w-5 h-5 text-amber-200"></i>
                    <span class="text-sm">साइनअप बोनस वॉलेटमध्ये जमा</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-200"></i>
                    <span class="text-sm">सुरक्षित नोंदणी</span>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-10 bg-white dark:bg-gray-950">
        <div class="w-full max-w-md">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> मुख्यपृष्ठावर जा
            </a>

            <div class="lg:hidden flex items-center gap-2 mb-6">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                    <i data-lucide="landmark" class="w-5 h-5 text-white"></i>
                </div>
                <span class="text-lg font-bold text-gray-900 dark:text-white">SETU Suvidha</span>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">नवीन खाते तयार करा</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-7 text-sm">कृपया खालील माहिती भरा.</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पूर्ण नाव</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">State</label>
                        <select disabled
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            <option>महाराष्ट्र (Maharashtra)</option>
                        </select>
                        <input type="hidden" name="state" value="महाराष्ट्र (Maharashtra)">
                        @error('state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">District</label>
                        <select id="district" name="district" x-model="selectedDistrict" @change="onDistrictChange" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                            <option value="">जिल्हा निवडा</option>
                            @foreach(($districts ?? []) as $districtName => $talukas)
                                <option value="{{ $districtName }}" @selected(old('district') === $districtName)>{{ $districtName }}</option>
                            @endforeach
                        </select>
                        @error('district') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="taluka" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Taluka</label>
                        <select id="taluka" name="taluka" x-model="selectedTaluka" @change="onTalukaChange" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                            <option value="">तालुका निवडा</option>
                            <template x-for="taluka in talukaList" :key="taluka">
                                <option :value="taluka" x-text="taluka"></option>
                            </template>
                        </select>
                        @error('taluka') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="village" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Village</label>
                        <input id="village" type="text" name="village" x-model="village" list="village-options" value="{{ old('village') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                            placeholder="गाव निवडा / टाका">
                        <datalist id="village-options">
                            <template x-for="v in villageList" :key="v">
                                <option :value="v" x-text="v"></option>
                            </template>
                        </datalist>
                        <p class="text-[11px] text-gray-400 mt-1">जर गाव सूचीमध्ये नसेल तर नाव manually टाका.</p>
                        @error('village') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="address_line1" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address 1</label>
                    <input id="address_line1" type="text" name="address_line1" value="{{ old('address_line1') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('address_line1') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ईमेल</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mobile No</label>
                    <input id="mobile" type="tel" name="mobile" value="{{ old('mobile') }}" required maxlength="10"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition"
                        placeholder="10 अंकी मोबाईल नंबर">
                    @error('mobile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ showPass: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पासवर्ड</label>
                    <div class="relative">
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition pr-12">
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" class="w-5 h-5" x-show="!showPass"></i>
                            <i data-lucide="eye-off" class="w-5 h-5" x-show="showPass"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पासवर्ड पुन्हा टाका</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                </div>

                <div>
                    <label for="promo_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Promo Code (optional)</label>
                    <input id="promo_code" type="text" name="promo_code" value="{{ old('promo_code') }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('promo_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full btn-primary !py-3.5 text-base">नोंदणी करा</button>
            </form>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                आधीच खाते आहे?
                <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold">लॉगिन करा</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function registerForm() {
    return {
        districtMap: @json($districts ?? []),
        villageMap: @json($villageMap ?? []),
        selectedDistrict: @json(old('district', '')),
        selectedTaluka: @json(old('taluka', '')),
        village: @json(old('village', '')),
        talukaList: [],
        villageList: [],

        init() {
            this.onDistrictChange();
            this.onTalukaChange();
        },

        onDistrictChange() {
            this.talukaList = this.districtMap[this.selectedDistrict] || [];
            if (!this.talukaList.includes(this.selectedTaluka)) {
                this.selectedTaluka = '';
            }
            this.onTalukaChange();
        },

        onTalukaChange() {
            var districtVillages = this.villageMap[this.selectedDistrict] || {};
            this.villageList = districtVillages[this.selectedTaluka] || [];
        }
    };
}
</script>
@endpush
