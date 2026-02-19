@extends('layouts.app')
@section('title', 'Legal Bond Formats — SETU Suvidha')

@section('content')
<div class="w-full bg-gradient-to-br from-[#1a3a6b] via-[#1e4a8a] to-[#2155a3] py-12 px-4 text-center rounded-b-3xl"
     x-data="{ search: '' }">
    <div class="flex items-center justify-center gap-3 mb-2">
        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
            <i data-lucide="file-text" class="text-white w-6 h-6"></i>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-white">Legal Bond Formats</h1>
    </div>
    <p class="text-white/80 text-base mt-1 mb-8">
        विविध कायदेशीर बॉण्ड व करारनामे सोप्या पद्धतीमध्ये बनवा.
    </p>

    <div class="max-w-2xl mx-auto relative">
        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
        <input type="text" x-model="search"
               placeholder="Search (e.g. भाडे करार, Rent, Vatni...)"
               class="w-full pl-12 pr-4 py-4 rounded-full bg-white text-gray-700 text-base shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300 placeholder-gray-400" />
    </div>

    <div class="max-w-6xl mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 px-2">
        @foreach($formats as $format)
        <div x-show="'{{ strtolower($format->title_en . ' ' . $format->title_mr . ' ' . $format->description_mr) }}'.includes(search.toLowerCase()) || search === ''"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative bg-white rounded-2xl shadow-md p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">

            <span class="absolute top-3 right-3 text-xs font-bold text-green-600">
                Fee: ₹{{ number_format($format->fee, 0) }}
            </span>

            <div class="w-16 h-16 {{ $format->icon_bg_color }} rounded-full flex items-center justify-center shadow-md mt-2">
                <i data-lucide="{{ $format->icon }}" class="text-white w-8 h-8"></i>
            </div>

            <h3 class="text-gray-900 font-bold text-lg mt-4 leading-tight">{{ $format->title_en }}</h3>
            <p class="text-indigo-600 font-semibold text-sm mt-1">{{ $format->title_mr }}</p>
            <p class="text-gray-500 text-sm mt-2 leading-relaxed flex-1">{{ $format->description_mr }}</p>

            <a href="{{ route('bonds.show', $format->slug) }}"
               class="mt-5 w-full border border-gray-300 rounded-full py-2 px-4 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors duration-200 flex items-center justify-center gap-2">
                Create Now
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        @endforeach

        <div x-show="search === ''"
             class="border-2 border-dashed border-gray-300 rounded-2xl p-6 flex flex-col items-center justify-center text-center bg-gray-50/50 min-h-[280px]">
            <div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center mb-3">
                <i data-lucide="clock" class="text-gray-400 w-7 h-7"></i>
            </div>
            <h3 class="font-bold text-gray-700 text-lg">More Formats</h3>
            <p class="text-gray-400 text-sm font-medium mt-1">लवकरच येत आहेत...</p>
            <p class="text-gray-400 text-xs mt-3 leading-relaxed">
                आम्ही आणखी नवीन कायदेशीर फॉर्मॅट्स आणि करारनामे लवकरच अपडेट करत आहोत.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endpush
