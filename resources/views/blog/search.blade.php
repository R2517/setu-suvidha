@extends('layouts.app')
@section('title', 'Search: ' . $search . ' — Blog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-4">Search Results</h1>
        <form action="{{ route('blog.search') }}" method="GET" class="max-w-2xl">
            <div class="flex gap-3">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search blog posts..." class="flex-1 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold transition">
                    Search
                </button>
            </div>
        </form>
    </div>

    @if($search)
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        Found {{ $posts->total() }} {{ Str::plural('result', $posts->total()) }} for <strong>"{{ $search }}"</strong>
    </p>
    @endif

    @if($posts->isEmpty())
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-12 text-center">
        <i data-lucide="search-x" class="w-16 h-16 text-gray-400 mx-auto mb-4 pointer-events-none"></i>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No results found</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">Try different keywords or browse all posts</p>
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition">
            View All Posts
        </a>
    </div>
    @else
    <div class="space-y-6">
        @foreach($posts as $post)
        <article class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 hover:shadow-lg transition">
            <div class="flex gap-6">
                @if($post->featured_image)
                <a href="{{ route('blog.show', $post->slug) }}" class="flex-shrink-0">
                    <img src="{{ asset($post->featured_image) }}" alt="{{ $post->title }}" class="w-48 h-32 object-cover rounded-lg">
                </a>
                @endif
                
                <div class="flex-1">
                    @if($post->category)
                    <a href="{{ route('blog.category', $post->category->slug) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-bold mb-2">
                        <i data-lucide="{{ $post->category->icon }}" class="w-3 h-3 pointer-events-none"></i>
                        {{ $post->category->name_en }}
                    </a>
                    @endif

                    <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2 hover:text-amber-600 transition">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h2>

                    @if($post->excerpt)
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $post->excerpt }}</p>
                    @endif

                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <span class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-3 h-3 pointer-events-none"></i>
                            {{ $post->published_at->format('d M Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3 pointer-events-none"></i>
                            {{ $post->reading_time_minutes }} min
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="eye" class="w-3 h-3 pointer-events-none"></i>
                            {{ number_format($post->view_count) }}
                        </span>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $posts->appends(['q' => $search])->links() }}
    </div>
    @endif
</div>
@endsection
