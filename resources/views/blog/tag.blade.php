@extends('layouts.app')
@section('title', '#' . $tag->name_en . ' — Blog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 mb-4">
            <i data-lucide="tag" class="w-5 h-5 pointer-events-none"></i>
            <span class="font-bold">#{{ $tag->name_en }}</span>
        </div>
        <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-4">Posts tagged: {{ $tag->name_en }}</h1>
        @if($tag->name_mr)
        <p class="text-lg text-gray-600 dark:text-gray-400">{{ $tag->name_mr }}</p>
        @endif
    </div>

    @if($posts->isEmpty())
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-12 text-center">
        <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4 pointer-events-none"></i>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No posts with this tag</h3>
        <a href="{{ route('blog.index') }}" class="text-amber-600 hover:underline">View all posts</a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($posts as $post)
        <article class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-lg transition group">
            @if($post->featured_image)
            <a href="{{ route('blog.show', $post->slug) }}">
                <img src="{{ asset($post->featured_image) }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full h-48 object-cover">
            </a>
            @endif
            
            <div class="p-6">
                @if($post->category)
                <div class="mb-3">
                    <a href="{{ route('blog.category', $post->category->slug) }}" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-xs font-bold">
                        <i data-lucide="{{ $post->category->icon }}" class="w-3 h-3 pointer-events-none"></i>
                        {{ $post->category->name_en }}
                    </a>
                </div>
                @endif

                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-2 group-hover:text-amber-600 transition">
                    <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                </h2>

                @if($post->excerpt)
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                @endif

                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-1">
                        <i data-lucide="calendar" class="w-3 h-3 pointer-events-none"></i>
                        {{ $post->published_at->format('d M Y') }}
                    </span>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-amber-600 font-bold hover:underline">
                        Read →
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
