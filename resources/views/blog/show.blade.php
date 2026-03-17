@extends('layouts.app')
@section('title', $post->getMetaTitleComputed())

@push('meta')
<meta name="description" content="{{ $post->getMetaDescriptionComputed() }}">
<meta name="robots" content="{{ $post->robots }}">
<link rel="canonical" href="{{ $post->canonical }}">

@if($post->hreflang_en_slug)
<link rel="alternate" hreflang="en" href="{{ url('/blog/' . $post->hreflang_en_slug) }}">
@endif
@if($post->hreflang_mr_slug)
<link rel="alternate" hreflang="mr" href="{{ url('/blog/' . $post->hreflang_mr_slug) }}">
@endif

<meta property="og:type" content="article">
<meta property="og:title" content="{{ $post->getMetaTitleComputed() }}">
<meta property="og:description" content="{{ $post->getMetaDescriptionComputed() }}">
<meta property="og:image" content="{{ asset($post->getOgImageComputed()) }}">
<meta property="og:url" content="{{ $post->canonical }}">
<meta property="og:site_name" content="SETU Suvidha">
<meta property="article:published_time" content="{{ $post->published_at?->toW3cString() }}">
<meta property="article:modified_time" content="{{ $post->updated_at?->toW3cString() }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $post->getMetaTitleComputed() }}">
<meta name="twitter:description" content="{{ $post->getMetaDescriptionComputed() }}">
<meta name="twitter:image" content="{{ asset($post->getOgImageComputed()) }}">

@php
$blogSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => $post->canonical,
    ],
    'headline' => $post->meta_title ?? $post->title,
    'description' => $post->getMetaDescriptionComputed(),
    'image' => asset($post->getOgImageComputed()),
    'author' => ['@type' => 'Organization', 'name' => 'SETU Suvidha'],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'SETU Suvidha',
        'logo' => ['@type' => 'ImageObject', 'url' => asset('/images/logo.png')],
    ],
    'datePublished' => $post->published_at?->toW3cString(),
    'dateModified' => $post->updated_at?->toW3cString(),
];
@endphp
<script type="application/ld+json">{!! json_encode($blogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($post->category)
    <div class="mb-4">
        <a href="{{ route('blog.category', $post->category->slug) }}" class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-amber-100 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 text-sm font-bold">
            <i data-lucide="{{ $post->category->icon }}" class="w-4 h-4 pointer-events-none"></i>
            {{ $post->category->name_en }}
        </a>
    </div>
    @endif

    <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">{{ $post->title }}</h1>

    <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400 mb-8">
        <span class="flex items-center gap-1">
            <i data-lucide="calendar" class="w-4 h-4 pointer-events-none"></i>
            {{ $post->published_at->format('d M Y') }}
        </span>
        <span class="flex items-center gap-1">
            <i data-lucide="clock" class="w-4 h-4 pointer-events-none"></i>
            {{ $post->reading_time_minutes }} min read
        </span>
        <span class="flex items-center gap-1">
            <i data-lucide="eye" class="w-4 h-4 pointer-events-none"></i>
            {{ number_format($post->view_count) }} views
        </span>
    </div>

    @if($post->featured_image)
    <figure class="mb-8">
        <img src="{{ asset($post->featured_image) }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full rounded-2xl">
        @if($post->featured_image_caption)
        <figcaption class="text-sm text-gray-500 text-center mt-2">{{ $post->featured_image_caption }}</figcaption>
        @endif
    </figure>
    @endif

    <div class="prose prose-lg dark:prose-invert max-w-none">
        @foreach(($post->content_json['blocks'] ?? $post->content_json) as $index => $block)
            @php
                $blockType = $block['type'] ?? 'unknown';
                $viewName = "blog.blocks.{$blockType}";
            @endphp
            
            @if(view()->exists($viewName))
                @include($viewName, ['block' => $block, 'post' => $post, 'blockIndex' => $index])
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 my-4">
                    <p class="text-yellow-600 text-sm">Block type: {{ $blockType }} (no view found)</p>
                </div>
            @endif
        @endforeach
    </div>

    <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
        <h3 class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-3">Share this article:</h3>
        <div class="flex items-center gap-3">
            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' - ' . $post->url) }}" target="_blank" class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                WhatsApp
            </a>
            <a href="https://www.facebook.com/sharer.php?u={{ urlencode($post->url) }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                Facebook
            </a>
            <a href="https://t.me/share/url?url={{ urlencode($post->url) }}&text={{ urlencode($post->title) }}" target="_blank" class="inline-flex items-center gap-2 bg-sky-500 text-white px-3 py-2 rounded-lg hover:bg-sky-600 transition text-sm">
                Telegram
            </a>
        </div>
    </div>

    @if($post->tags->isNotEmpty())
    <div class="mt-8">
        <h3 class="text-sm font-bold text-gray-600 dark:text-gray-400 mb-3">Tags:</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($post->tags as $tag)
            <a href="{{ route('blog.tag', $tag->slug) }}" class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm hover:bg-amber-100 dark:hover:bg-amber-900/20 transition">
                #{{ $tag->name_en }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if($relatedPosts->isNotEmpty())
    <div class="mt-12 pt-8 border-t border-gray-200 dark:border-gray-800">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-6">Related Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($relatedPosts as $related)
            <a href="{{ route('blog.show', $related->slug) }}" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 hover:shadow-lg transition group">
                <h3 class="font-bold text-gray-900 dark:text-white mb-2 group-hover:text-amber-600 transition">{{ $related->title }}</h3>
                @if($related->excerpt)
                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $related->excerpt }}</p>
                @endif
            </a>
            @endforeach
        </div>
    </div>
    @endif
</article>
@endsection
