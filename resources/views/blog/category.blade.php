@extends('layouts.app')
@section('title', $category->name_en . ' — Blog')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 mb-4">
            <i data-lucide="{{ $category->icon }}" class="w-5 h-5 pointer-events-none"></i>
            <span class="font-bold">{{ $category->name_en }}</span>
        </div>
        <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-4">{{ $category->name_en }}</h1>
        @if($category->description_en)
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">{{ $category->description_en }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <aside class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Categories</h3>
                <div class="space-y-2">
                    @foreach($categories as $cat)
                    <a href="{{ route('blog.category', $cat->slug) }}" class="flex items-center justify-between px-3 py-2 rounded-lg {{ $cat->id === $category->id ? 'bg-amber-50 dark:bg-amber-900/20 text-amber-600' : 'hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300' }} transition">
                        <div class="flex items-center gap-2">
                            <i data-lucide="{{ $cat->icon }}" class="w-4 h-4 pointer-events-none"></i>
                            <span class="text-sm font-medium">{{ $cat->name_en }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $cat->published_posts_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <main class="lg:col-span-3">
            @if($posts->isEmpty())
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-12 text-center">
                <i data-lucide="inbox" class="w-16 h-16 text-gray-400 mx-auto mb-4 pointer-events-none"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No posts in this category yet</h3>
                <a href="{{ route('blog.index') }}" class="text-amber-600 hover:underline">View all posts</a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($posts as $post)
                <article class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:shadow-lg transition group">
                    @if($post->featured_image)
                    <a href="{{ route('blog.show', $post->slug) }}">
                        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full h-48 object-cover">
                    </a>
                    @endif
                    
                    <div class="p-6">
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
                                Read more →
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
        </main>
    </div>
</div>
@endsection
