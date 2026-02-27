@extends('layouts.app')

@php
    $title = $article['title'] ?? 'Review | SETU Suvidha';
    $description = $article['description'] ?? ($article['excerpt'] ?? 'Service review and process guide');
    $faqs = $article['faqs'] ?? [];
    $quickStats = $article['quick_stats'] ?? [];
    $highlights = $article['highlights'] ?? [];
    $steps = $article['practical_steps'] ?? [];
    $documents = $article['documents'] ?? [];
    $mistakes = $article['common_mistakes'] ?? [];
    $expertNotes = $article['expert_notes'] ?? [];
    $relatedLinks = $article['related_links'] ?? [];
    $tags = $article['tags'] ?? [];

    $accent = $article['accent'] ?? '#f59e0b';
    $heroGradient = $article['hero_gradient'] ?? 'linear-gradient(140deg, #0f172a 0%, #1e293b 50%, #334155 100%)';
@endphp

@section('title', $title)
@section('description', $description)

@push('meta')
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ url($article['og_image'] ?? config('seo.default_og_image')) }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ url($article['og_image'] ?? config('seo.default_og_image')) }}">
<link rel="canonical" href="{{ url()->current() }}">
<script type="application/ld+json">
{
  "@@context":"https://schema.org",
  "@@type":"Article",
  "headline":"{{ addslashes($article['title_en'] ?? $title) }}",
  "description":"{{ addslashes($description) }}",
  "datePublished":"{{ $article['date_published'] ?? now()->toDateString() }}",
  "dateModified":"{{ $article['date_modified'] ?? now()->toDateString() }}",
  "author":{"@@type":"Organization","name":"SETU Suvidha","url":"{{ url('/') }}"},
  "publisher":{"@@type":"Organization","name":"SETU Suvidha","url":"{{ url('/') }}"},
  "mainEntityOfPage":{"@@type":"WebPage","@@id":"{{ url()->current() }}"}
}
</script>
<script type="application/ld+json">
{
  "@@context":"https://schema.org",
  "@@type":"BreadcrumbList",
  "itemListElement":[
    {"@@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@@type":"ListItem","position":2,"name":"Reviews","item":"{{ url('/reviews') }}"},
    {"@@type":"ListItem","position":3,"name":"{{ addslashes($article['title_en'] ?? 'Review') }}","item":"{{ url()->current() }}"}
  ]
}
</script>
@if(count($faqs) > 0)
<script type="application/ld+json">
{
  "@@context":"https://schema.org",
  "@@type":"FAQPage",
  "mainEntity":[
    @foreach($faqs as $faq)
    {"@@type":"Question","name":"{{ addslashes($faq['q']) }}","acceptedAnswer":{"@@type":"Answer","text":"{{ addslashes($faq['a']) }}"}}@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endif
@endpush

@push('styles')
<style>
    .review-rich p{line-height:1.85}
    .review-rich h2,.review-rich h3{scroll-margin-top:90px}
    .review-rich details>summary{list-style:none}
    .review-rich details>summary::-webkit-details-marker{display:none}
    .review-toc a{transition:all .2s ease}
    .review-toc a:hover{transform:translateX(3px)}
</style>
@endpush

@section('content')
<section class="relative overflow-hidden pt-14 pb-20" style="background: {{ $heroGradient }};">
    <div class="absolute inset-0 opacity-25 bg-[radial-gradient(circle_at_10%_20%,_rgba(255,255,255,0.16),_transparent_42%),radial-gradient(circle_at_85%_15%,_rgba(255,255,255,0.12),_transparent_40%),radial-gradient(circle_at_80%_80%,_rgba(255,255,255,0.09),_transparent_40%)]"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-white/75 mb-6">
            <a href="{{ url('/') }}" class="hover:text-white transition">मुख्यपृष्ठ</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('reviews.index') }}" class="hover:text-white transition">Reviews</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-white font-semibold">{{ $article['title_en'] ?? 'Guide' }}</span>
        </nav>

        <div class="inline-flex flex-wrap items-center gap-2 mb-5">
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold text-white/95 border border-white/30 bg-white/10 backdrop-blur">
                <i data-lucide="{{ $article['icon'] ?? 'book-open' }}" class="w-3 h-3"></i> {{ $article['category'] ?? 'Guide' }}
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold text-white/90 border border-white/20 bg-white/5">
                <i data-lucide="calendar" class="w-3 h-3"></i> {{ $article['date'] ?? now()->toDateString() }}
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold text-white/90 border border-white/20 bg-white/5">
                <i data-lucide="clock-3" class="w-3 h-3"></i> {{ $article['read_time'] ?? '10 min' }}
            </span>
        </div>

        <h1 class="text-2xl sm:text-3xl lg:text-5xl font-black text-white leading-tight max-w-5xl">
            {{ $article['title_mr'] ?? ($article['title_en'] ?? $title) }}
        </h1>
        <p class="mt-4 text-white/90 text-sm sm:text-base max-w-4xl leading-relaxed">
            {{ $article['excerpt'] ?? '' }}
        </p>

        @if(count($tags) > 0)
        <div class="mt-5 flex flex-wrap gap-2">
            @foreach($tags as $tag)
            <span class="px-3 py-1 rounded-full text-[11px] font-semibold text-white/95 border border-white/20 bg-white/10">{{ $tag }}</span>
            @endforeach
        </div>
        @endif
    </div>
</section>

@if(count($quickStats) > 0)
<section class="-mt-10 relative z-10 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($quickStats as $stat)
            <div class="rounded-xl border border-gray-200/80 dark:border-gray-700 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-4 py-4 shadow-sm">
                <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 font-bold">{{ $stat['label'] }}</p>
                <p class="text-sm sm:text-base font-bold mt-1 text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-10 sm:py-14 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:grid lg:grid-cols-[260px_minmax(0,1fr)] lg:gap-10">
        <aside class="hidden lg:block">
            <div class="sticky top-20">
                <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-3">अनुक्रमणिका (TOC)</h2>
                <nav class="review-toc space-y-1.5 text-sm">
                    <a href="#overview" class="block text-gray-600 dark:text-gray-400 hover:text-[color:var(--accent)]" style="--accent: {{ $accent }};">जलद सारांश</a>
                    <a href="#highlights" class="block text-gray-600 dark:text-gray-400 hover:text-[color:var(--accent)]" style="--accent: {{ $accent }};">मुख्य मुद्दे</a>
                    <a href="#steps" class="block text-gray-600 dark:text-gray-400 hover:text-[color:var(--accent)]" style="--accent: {{ $accent }};">Step-by-Step Process</a>
                    <a href="#documents" class="block text-gray-600 dark:text-gray-400 hover:text-[color:var(--accent)]" style="--accent: {{ $accent }};">Documents Checklist</a>
                    <a href="#mistakes" class="block text-gray-600 dark:text-gray-400 hover:text-[color:var(--accent)]" style="--accent: {{ $accent }};">Common Mistakes</a>
                    <a href="#tips" class="block text-gray-600 dark:text-gray-400 hover:text-[color:var(--accent)]" style="--accent: {{ $accent }};">Operator Tips</a>
                    <a href="#faq" class="block text-gray-600 dark:text-gray-400 hover:text-[color:var(--accent)]" style="--accent: {{ $accent }};">FAQ</a>
                    <a href="#next" class="block text-gray-600 dark:text-gray-400 hover:text-[color:var(--accent)]" style="--accent: {{ $accent }};">Next Action</a>
                </nav>

                <div class="mt-7 rounded-xl border border-gray-200 dark:border-gray-800 p-4 bg-gray-50/70 dark:bg-gray-900">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-2">Last Reviewed</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $article['date_modified'] ?? now()->toDateString() }}</p>
                    <p class="mt-3 text-xs text-gray-600 dark:text-gray-400">हा guide practical counter workflow वर आधारित आहे.</p>
                </div>
            </div>
        </aside>

        <article class="review-rich min-w-0">
            <details class="lg:hidden mb-6 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <summary class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 cursor-pointer flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4"></i> अनुक्रमणिका (Table of Contents)
                </summary>
                <div class="px-4 pb-4 pt-1 space-y-1 text-sm">
                    <a href="#overview" class="block text-gray-600 dark:text-gray-400">जलद सारांश</a>
                    <a href="#highlights" class="block text-gray-600 dark:text-gray-400">मुख्य मुद्दे</a>
                    <a href="#steps" class="block text-gray-600 dark:text-gray-400">Step-by-Step Process</a>
                    <a href="#documents" class="block text-gray-600 dark:text-gray-400">Documents Checklist</a>
                    <a href="#mistakes" class="block text-gray-600 dark:text-gray-400">Common Mistakes</a>
                    <a href="#tips" class="block text-gray-600 dark:text-gray-400">Operator Tips</a>
                    <a href="#faq" class="block text-gray-600 dark:text-gray-400">FAQ</a>
                    <a href="#next" class="block text-gray-600 dark:text-gray-400">Next Action</a>
                </div>
            </details>

            <section id="overview" class="mb-10 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 sm:p-7">
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="layout-dashboard" class="w-5 h-5" style="color: {{ $accent }};"></i> जलद सारांश (Quick Summary)
                </h2>
                <div class="space-y-4 text-[15px] text-gray-700 dark:text-gray-300">
                    @foreach($article['overview'] ?? [] as $paragraph)
                    <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </section>

            @if(count($highlights) > 0)
            <section id="highlights" class="mb-10 rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900 p-6 sm:p-7">
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-5 h-5" style="color: {{ $accent }};"></i> मुख्य मुद्दे (Key Highlights)
                </h2>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach($highlights as $point)
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 bg-white dark:bg-gray-800 text-sm text-gray-700 dark:text-gray-300 flex items-start gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5 shrink-0" style="color: {{ $accent }};"></i>
                        <span>{{ $point }}</span>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif

            @if(count($steps) > 0)
            <section id="steps" class="mb-10 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 sm:p-7">
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                    <i data-lucide="workflow" class="w-5 h-5" style="color: {{ $accent }};"></i> प्रक्रिया (Step-by-Step Process)
                </h2>
                <ol class="space-y-3">
                    @foreach($steps as $step)
                    <li class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-800 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 flex gap-3">
                        <span class="w-7 h-7 shrink-0 rounded-full text-xs font-bold text-white flex items-center justify-center" style="background-color: {{ $accent }};">{{ $loop->iteration }}</span>
                        <span class="pt-1">{{ $step }}</span>
                    </li>
                    @endforeach
                </ol>
            </section>
            @endif

            <div class="grid xl:grid-cols-2 gap-6 mb-10">
                @if(count($documents) > 0)
                <section id="documents" class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
                    <h2 class="text-lg sm:text-xl font-black text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="folder-check" class="w-5 h-5" style="color: {{ $accent }};"></i> कागदपत्रे (Documents Checklist)
                    </h2>
                    <ul class="space-y-2.5 text-sm text-gray-700 dark:text-gray-300">
                        @foreach($documents as $doc)
                        <li class="flex items-start gap-2">
                            <i data-lucide="file-check-2" class="w-4 h-4 mt-0.5 shrink-0" style="color: {{ $accent }};"></i>
                            <span>{{ $doc }}</span>
                        </li>
                        @endforeach
                    </ul>
                </section>
                @endif

                @if(count($mistakes) > 0)
                <section id="mistakes" class="rounded-2xl border border-red-200 dark:border-red-800/40 bg-red-50/70 dark:bg-red-900/10 p-6">
                    <h2 class="text-lg sm:text-xl font-black text-red-800 dark:text-red-300 mb-4 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i> टाळा या चुका (Common Mistakes)
                    </h2>
                    <ul class="space-y-2.5 text-sm text-red-900/90 dark:text-red-200">
                        @foreach($mistakes as $mistake)
                        <li class="flex items-start gap-2">
                            <i data-lucide="x-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                            <span>{{ $mistake }}</span>
                        </li>
                        @endforeach
                    </ul>
                </section>
                @endif
            </div>

            @if(count($expertNotes) > 0)
            <section id="tips" class="mb-10 rounded-2xl border border-emerald-200 dark:border-emerald-800/40 bg-emerald-50/70 dark:bg-emerald-900/10 p-6 sm:p-7">
                <h2 class="text-xl sm:text-2xl font-black text-emerald-800 dark:text-emerald-300 mb-5 flex items-center gap-2">
                    <i data-lucide="lightbulb" class="w-5 h-5"></i> Operator Playbook Tips
                </h2>
                <ul class="grid sm:grid-cols-2 gap-3 text-sm text-emerald-900 dark:text-emerald-200">
                    @foreach($expertNotes as $note)
                    <li class="rounded-xl border border-emerald-200 dark:border-emerald-800/50 bg-white/70 dark:bg-emerald-900/20 px-4 py-3 flex items-start gap-2">
                        <i data-lucide="badge-check" class="w-4 h-4 mt-0.5 shrink-0"></i>
                        <span>{{ $note }}</span>
                    </li>
                    @endforeach
                </ul>
            </section>
            @endif

            @if(count($faqs) > 0)
            <section id="faq" class="mb-10 rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6 sm:p-7">
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-5 h-5" style="color: {{ $accent }};"></i> वारंवार विचारले जाणारे प्रश्न (FAQ)
                </h2>
                <div class="space-y-3">
                    @foreach($faqs as $faq)
                    <details class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-800 px-4 py-3">
                        <summary class="cursor-pointer text-sm font-bold text-gray-900 dark:text-white flex items-center justify-between gap-3">
                            <span>{{ $faq['q'] }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 shrink-0 text-gray-400"></i>
                        </summary>
                        <p class="mt-3 text-sm text-gray-700 dark:text-gray-300">{{ $faq['a'] }}</p>
                    </details>
                    @endforeach
                </div>
            </section>
            @endif

            <section id="next" class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-900 p-6 sm:p-7">
                <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mb-3">Next Action (पुढील पाऊल)</h2>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                    Practical implementation ke liye pehle document quality aur field validation fix rakho. Uske baad submission flow standardize karo, taaki rejection cycle kam ho aur counter efficiency improve ho.
                </p>
                <div class="flex flex-wrap gap-3 mb-5">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold text-white hover:opacity-90 transition" style="background-color: {{ $accent }};">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Register and Activate
                    </a>
                    <a href="{{ route('services') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-300 dark:border-gray-700 px-4 py-2 text-xs font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <i data-lucide="grid-2x2" class="w-3.5 h-3.5"></i> View Services
                    </a>
                </div>

                @if(count($relatedLinks) > 0)
                <div class="grid sm:grid-cols-2 gap-2">
                    @foreach($relatedLinks as $link)
                    <a href="{{ url($link['url']) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:text-[color:var(--accent)] transition" style="--accent: {{ $accent }};">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i> {{ $link['label'] }}
                    </a>
                    @endforeach
                </div>
                @endif
            </section>
        </article>
    </div>
</section>
@endsection
