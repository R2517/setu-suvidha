@extends('layouts.app')

@section('title', 'Government Service Reviews | SETU Suvidha')
@section('description', 'Actionable guides for Maharashtra government services, forms, and digital workflow updates for Setu and CSC operators.')

@push('meta')
<meta property="og:title" content="Government Service Reviews | SETU Suvidha">
<meta property="og:description" content="Practical guides for PM Kisan, Satbara, Hamipatra, Income Certificate, and other high-demand services.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('reviews.index') }}">
<link rel="canonical" href="{{ route('reviews.index') }}">
@endpush

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-white to-orange-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-16 sm:py-24">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style="background-image:url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=%270 0 60 60%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cg fill=%27none%27 fill-rule=%27evenodd%27%3E%3Cg fill=%27%23000%27 fill-opacity=%271%27%3E%3Cpath d=%27M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%27/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <div class="inline-flex items-center gap-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-4 py-1.5 rounded-full text-xs font-bold mb-6">
            <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
            Latest service and scheme guides
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4">
            Reviews and Guides for <span class="bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent">Government Services</span>
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Read practical, process-first articles for high-demand services across Maharashtra Setu and CSC workflows.
        </p>

        <form method="GET" action="{{ route('reviews.index') }}" class="mx-auto mt-8 max-w-xl">
            <label for="review-search" class="sr-only">Search guides</label>
            <div class="flex items-center gap-2 rounded-2xl border border-amber-200 bg-white/80 p-2 shadow-sm dark:border-amber-800/40 dark:bg-gray-900/80">
                <i data-lucide="search" class="ml-2 h-4 w-4 text-amber-500"></i>
                <input
                    id="review-search"
                    name="q"
                    type="search"
                    value="{{ $searchQuery ?? '' }}"
                    placeholder="Search: PM Kisan, satbara, hamipatra..."
                    class="h-10 w-full border-0 bg-transparent px-1 text-sm text-gray-700 placeholder:text-gray-400 focus:ring-0 dark:text-gray-200"
                >
                <button type="submit" class="inline-flex h-10 items-center justify-center rounded-xl bg-amber-500 px-4 text-xs font-bold text-white hover:bg-amber-600 transition">
                    Search
                </button>
            </div>
        </form>
    </div>
</section>

<section class="py-12 sm:py-16 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(count($articles) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($articles as $article)
            <a href="{{ route('reviews.show', ['slug' => $article['slug']]) }}" class="group block">
                <article class="relative bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="h-1.5 bg-gradient-to-r from-{{ $article['color'] }}-400 to-{{ $article['color'] }}-600"></div>
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center gap-1.5 bg-{{ $article['color'] }}-100 dark:bg-{{ $article['color'] }}-900/30 text-{{ $article['color'] }}-700 dark:text-{{ $article['color'] }}-400 px-2.5 py-1 rounded-full text-[11px] font-bold">
                                <i data-lucide="{{ $article['icon'] }}" class="w-3 h-3"></i>
                                {{ $article['category'] }}
                            </span>
                            <span class="text-[11px] text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i> {{ $article['read_time'] }}
                            </span>
                        </div>

                        <h2 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-{{ $article['color'] }}-600 dark:group-hover:text-{{ $article['color'] }}-400 transition-colors leading-snug mb-3">
                            {{ $article['title_en'] ?? $article['title'] }}
                        </h2>
                        <p class="text-[13px] text-gray-500 dark:text-gray-400 mb-1 font-medium">{{ $article['title'] }}</p>

                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mt-3">
                            {{ $article['excerpt'] }}
                        </p>

                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <span class="text-xs text-gray-400">{{ $article['date'] }}</span>
                            <span class="inline-flex items-center gap-1.5 text-{{ $article['color'] }}-600 dark:text-{{ $article['color'] }}-400 text-xs font-bold group-hover:gap-2.5 transition-all">
                                Read full guide <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </span>
                        </div>
                    </div>
                </article>
            </a>
            @endforeach
        </div>
        @else
        <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-8 text-center dark:border-amber-800/40 dark:bg-amber-900/10">
            <h2 class="text-xl font-bold text-amber-800 dark:text-amber-300">No guides found for "{{ $searchQuery }}"</h2>
            <p class="mt-2 text-sm text-amber-700/90 dark:text-amber-200/90">Try another keyword or clear search to see all published articles.</p>
            <a href="{{ route('reviews.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-amber-500 px-4 py-2 text-xs font-bold text-white hover:bg-amber-600 transition">
                <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Clear search
            </a>
        </div>
        @endif

        <div class="mt-16 bg-gradient-to-br from-gray-50 to-amber-50/50 dark:from-gray-900 dark:to-gray-900 rounded-2xl p-8 border border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="link" class="w-5 h-5 text-amber-500"></i>
                High-intent service pages
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <a href="{{ route('farmer-card-public') }}" class="flex items-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:shadow-md transition-all">
                    <i data-lucide="leaf" class="w-4 h-4 text-green-500"></i> Farmer ID Card Online
                </a>
                <a href="{{ route('services.landing.show', ['slug' => 'hamipatra-format-marathi']) }}" class="flex items-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 hover:shadow-md transition-all">
                    <i data-lucide="file-text" class="w-4 h-4 text-amber-500"></i> Hamipatra Format Marathi
                </a>
                <a href="{{ route('services.landing.show', ['slug' => 'income-certificate-application-maharashtra']) }}" class="flex items-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:shadow-md transition-all">
                    <i data-lucide="landmark" class="w-4 h-4 text-blue-500"></i> Income Certificate Application
                </a>
            </div>
        </div>

        <div class="mt-12 bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 sm:p-8">
            <div class="flex items-start gap-5">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shrink-0 shadow-lg shadow-amber-200/30 dark:shadow-amber-900/20">
                    <span class="text-2xl font-black text-white">R</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">Mr. Rajat</h4>
                        <span class="text-[10px] bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-2 py-0.5 rounded-full font-bold">Author</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">CSC / Maha e-Seva Kendra Operator, Platform Builder</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Every guide is written for real counter operations. Focus areas include document validation, rejection prevention, and faster service delivery.
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Last reviewed: {{ now()->toDateString() }}</p>
                    <a href="{{ route('author') }}" class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold text-amber-600 dark:text-amber-400 hover:text-amber-700 transition">
                        <i data-lucide="arrow-right" class="w-3 h-3"></i> View author profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
