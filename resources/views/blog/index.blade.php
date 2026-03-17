@extends('layouts.app')
@section('title', 'Blog — SETU Suvidha')

@push('meta')
<meta name="description" content="SETU Suvidha Blog — Government schemes, guides, how-to articles and updates for Maharashtra VLEs and citizens.">
<link rel="alternate" type="application/rss+xml" title="SETU Suvidha Blog RSS" href="{{ route('blog.feed') }}">
@endpush

@section('content')
<div class="bg-gradient-to-b from-amber-50 to-white dark:from-gray-950 dark:to-gray-950">

    {{-- Hero Header --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-3">SETU Suvidha Blog</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Government schemes, guides, and updates for Maharashtra</p>
        </div>

        {{-- Search Bar --}}
        <form action="{{ route('blog.search') }}" method="GET" class="max-w-xl mx-auto mt-6">
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"></i>
                <input type="text" name="q" placeholder="Search articles..." class="w-full pl-12 pr-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition">
            </div>
        </form>

        {{-- Category Pills --}}
        <div class="flex flex-wrap justify-center gap-2 mt-6">
            <a href="{{ route('blog.index') }}" class="px-4 py-2 rounded-full text-sm font-bold transition {{ !request('category') ? 'bg-amber-500 text-white shadow-md' : 'bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-amber-300' }}">
                All Posts
            </a>
            @foreach($categories as $cat)
            @if($cat->published_posts_count > 0)
            <a href="{{ route('blog.category', $cat->slug) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:border-amber-300 hover:text-amber-600 transition">
                <i data-lucide="{{ $cat->icon }}" class="w-3.5 h-3.5 pointer-events-none"></i>
                {{ $cat->name_en }}
                <span class="text-xs text-gray-400">({{ $cat->published_posts_count }})</span>
            </a>
            @endif
            @endforeach
        </div>
    </div>

    {{-- Posts Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if($posts->isEmpty())
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-16 text-center max-w-lg mx-auto">
            <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-4 pointer-events-none"></i>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No posts yet</h3>
            <p class="text-gray-500">Check back soon for new content!</p>
        </div>
        @else

        {{-- Featured Post (first post) --}}
        @php $featured = $posts->first(); @endphp
        <a href="{{ route('blog.show', $featured->slug) }}" class="block mb-10 group">
            <article class="bg-white dark:bg-gray-900 rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    @if($featured->featured_image)
                    <div class="relative overflow-hidden">
                        <img src="{{ asset($featured->featured_image) }}" alt="{{ $featured->featured_image_alt ?? $featured->title }}" class="w-full h-64 md:h-80 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold shadow-lg">Featured</span>
                        </div>
                    </div>
                    @endif
                    <div class="p-8 flex flex-col justify-center">
                        @if($featured->category)
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-bold w-fit mb-4">
                            <i data-lucide="{{ $featured->category->icon }}" class="w-3 h-3 pointer-events-none"></i>
                            {{ $featured->category->name_en }}
                        </span>
                        @endif

                        <h2 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white mb-3 group-hover:text-amber-600 transition">
                            {{ $featured->title }}
                        </h2>

                        @if($featured->excerpt)
                        <p class="text-gray-600 dark:text-gray-400 mb-5 line-clamp-3">{{ Str::limit($featured->excerpt, 180) }}</p>
                        @endif

                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-4 h-4 pointer-events-none"></i>
                                {{ $featured->published_at->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-4 h-4 pointer-events-none"></i>
                                {{ $featured->reading_time_minutes }} min read
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="eye" class="w-4 h-4 pointer-events-none"></i>
                                {{ number_format($featured->view_count) }}
                            </span>
                        </div>

                        <div class="mt-5">
                            <span class="inline-flex items-center gap-2 text-amber-600 font-bold group-hover:gap-3 transition-all">
                                Read Full Article
                                <i data-lucide="arrow-right" class="w-4 h-4 pointer-events-none"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </article>
        </a>

        {{-- Remaining Posts Grid --}}
        @if($posts->count() > 1)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts->skip(1) as $post)
            <article class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">
                <a href="{{ route('blog.show', $post->slug) }}" class="block relative overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full h-52 object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-52 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-12 h-12 text-amber-400 pointer-events-none"></i>
                    </div>
                    @endif
                    @if($post->category)
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm text-amber-700 dark:text-amber-400 text-xs font-bold shadow-sm">
                            <i data-lucide="{{ $post->category->icon }}" class="w-3 h-3 pointer-events-none"></i>
                            {{ $post->category->name_en }}
                        </span>
                    </div>
                    @endif
                </a>

                <div class="p-5 flex flex-col flex-1">
                    <h2 class="text-lg font-black text-gray-900 dark:text-white mb-2 group-hover:text-amber-600 transition line-clamp-2">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h2>

                    @if($post->excerpt)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2 flex-1">{{ Str::limit($post->excerpt, 120) }}</p>
                    @endif

                    <div class="flex items-center justify-between text-xs text-gray-500 pt-4 border-t border-gray-100 dark:border-gray-800 mt-auto">
                        <div class="flex items-center gap-3">
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3 h-3 pointer-events-none"></i>
                                {{ $post->published_at->format('d M Y') }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3 pointer-events-none"></i>
                                {{ $post->reading_time_minutes }} min
                            </span>
                        </div>
                        <a href="{{ route('blog.show', $post->slug) }}" class="text-amber-600 font-bold hover:underline">Read →</a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
