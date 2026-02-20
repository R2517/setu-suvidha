@extends('layouts.app')
@section('title', 'VLE सेवा केंद्र शोधा — SETU Suvidha')
@section('description', 'तुमच्या जवळचे SETU सेवा केंद्र, CSC केंद्र शोधा. महाराष्ट्रातील सर्व अधिकृत VLE केंद्रांची यादी.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">VLE सेवा केंद्र शोधा</h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-xl mx-auto">तुमच्या जवळचे अधिकृत SETU / CSC सेवा केंद्र शोधा. सर्व केंद्रे सत्यापित आणि मंजूर आहेत.</p>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" class="mb-8 flex flex-wrap items-center gap-3 justify-center">
        <div class="relative flex-1 min-w-[250px] max-w-md">
            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="नाव, दुकान, जिल्हा शोधा..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500">
        </div>
        <select name="district" class="px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white">
            <option value="">सर्व जिल्हे</option>
            @foreach($districts as $d)
            <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">शोधा</button>
    </form>

    {{-- Results --}}
    @if($profiles->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($profiles as $p)
        <a href="{{ route('vle.show', $p->id) }}" class="group block bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                        @if($p->profile_pic)
                        <img src="{{ asset('storage/' . $p->profile_pic) }}" class="w-14 h-14 rounded-xl object-cover">
                        @else
                        <i data-lucide="user" class="w-6 h-6 text-amber-600"></i>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm truncate group-hover:text-amber-600 transition">{{ $p->full_name }}</h3>
                        @if($p->shop_name)
                        <p class="text-xs text-gray-500 truncate">{{ $p->shop_name }}</p>
                        @endif
                    </div>
                </div>
                @if($p->about_center)
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{{ $p->about_center }}</p>
                @endif
                <div class="flex flex-wrap gap-2">
                    @if($p->district)
                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-gray-500 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-full">
                        <i data-lucide="map-pin" class="w-3 h-3"></i> {{ $p->district }}{{ $p->taluka ? ', ' . $p->taluka : '' }}
                    </span>
                    @endif
                    @if($p->shop_type)
                    <span class="inline-flex items-center text-[10px] font-bold text-amber-600 bg-amber-50 dark:bg-amber-900/20 px-2 py-1 rounded-full uppercase">{{ $p->shop_type }}</span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-8">{{ $profiles->links() }}</div>
    @else
    <div class="text-center py-16 text-gray-400">
        <i data-lucide="search-x" class="w-16 h-16 mx-auto mb-4 opacity-30"></i>
        <p class="text-lg font-medium mb-1">कोणतेही केंद्र सापडले नाही</p>
        <p class="text-sm">वेगळे शोध शब्द वापरून पहा</p>
    </div>
    @endif
</div>
@endsection
