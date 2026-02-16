@extends('layouts.app')
@section('title', 'प्रोफाइल — SETU Suvidha')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा
    </a>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i data-lucide="user-circle" class="w-6 h-6 text-amber-600"></i> प्रोफाइल माहिती
        </h2>

        <form method="POST" action="{{ route('profile.update') }}" x-data="profileForm()">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Full Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पूर्ण नाव *</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $profile->full_name ?? '') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Mobile --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">मोबाईल नंबर</label>
                    <input type="text" name="mobile" value="{{ old('mobile', $profile->mobile ?? '') }}" maxlength="15"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                </div>
                {{-- Shop Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">दुकानाचे नाव</label>
                    <input type="text" name="shop_name" value="{{ old('shop_name', $profile->shop_name ?? '') }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                </div>
                {{-- Shop Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">दुकान प्रकार</label>
                    <select name="shop_type" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                        <option value="">निवडा</option>
                        <option value="setu" {{ old('shop_type', $profile->shop_type ?? '') === 'setu' ? 'selected' : '' }}>सेतु केंद्र</option>
                        <option value="csc" {{ old('shop_type', $profile->shop_type ?? '') === 'csc' ? 'selected' : '' }}>CSC केंद्र</option>
                        <option value="other" {{ old('shop_type', $profile->shop_type ?? '') === 'other' ? 'selected' : '' }}>इतर</option>
                    </select>
                </div>
                {{-- Address --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पत्ता</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">{{ old('address', $profile->address ?? '') }}</textarea>
                </div>
                {{-- District --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">जिल्हा</label>
                    <select name="district" x-model="selectedDistrict" @change="fetchTalukas()"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                        <option value="">जिल्हा निवडा</option>
                        @foreach($districts as $districtName => $talukas)
                            <option value="{{ $districtName }}" {{ old('district', $profile->district ?? '') === $districtName ? 'selected' : '' }}>{{ $districtName }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Taluka --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">तालुका</label>
                    <select name="taluka" x-model="selectedTaluka"
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                        <option value="">तालुका निवडा</option>
                        <template x-for="t in talukas" :key="t">
                            <option :value="t" x-text="t" :selected="t === '{{ old('taluka', $profile->taluka ?? '') }}'"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="btn-primary" x-data="{ loading: false }" @click="loading = true" :disabled="loading">
                    <span x-show="!loading">💾 सेव्ह करा</span>
                    <span x-show="loading" class="flex items-center gap-2"><svg class="animate-spin h-4 w-4" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg> सेव्ह होत आहे...</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function profileForm() {
    return {
        selectedDistrict: '{{ old('district', $profile->district ?? '') }}',
        selectedTaluka: '{{ old('taluka', $profile->taluka ?? '') }}',
        talukas: [],
        init() {
            if (this.selectedDistrict) this.fetchTalukas();
        },
        async fetchTalukas() {
            if (!this.selectedDistrict) { this.talukas = []; return; }
            try {
                const res = await fetch(`/api/talukas?district=${encodeURIComponent(this.selectedDistrict)}`);
                this.talukas = await res.json();
            } catch(e) { this.talukas = []; }
        }
    }
}
</script>
@endpush
@endsection
