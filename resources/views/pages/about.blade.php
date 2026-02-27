@extends('layouts.app')

@section('title', 'About SETU Suvidha | Maharashtra CSC Platform')
@section('description', 'SETU Suvidha is a practical SaaS platform for Setu and CSC operators in Maharashtra, built for government forms, billing workflow, and service-center growth.')

@push('meta')
<meta name="keywords" content="setu suvidha about, csc software maharashtra, setu kendra platform, government forms workflow, maha e seva software">
<meta property="og:title" content="About SETU Suvidha | Maharashtra CSC Platform">
<meta property="og:description" content="Know how SETU Suvidha helps service operators run forms, billing, and daily operations with better accuracy.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('about') }}">
<link rel="canonical" href="{{ route('about') }}">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'SETU Suvidha',
    'url' => url('/'),
    'description' => 'SaaS platform for Maharashtra Setu and CSC operators.',
    'sameAs' => array_values(array_filter(config('seo.social', []))),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-16 sm:py-20">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-8 right-10 h-52 w-52 rounded-full bg-amber-300 blur-3xl"></div>
        <div class="absolute bottom-0 left-10 h-52 w-52 rounded-full bg-orange-400 blur-3xl"></div>
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
            <i data-lucide="shield-check" class="h-3.5 w-3.5"></i>
            About the platform
        </div>
        <h1 class="mt-4 text-3xl sm:text-4xl font-black text-gray-900 dark:text-white">SETU Suvidha: Built for Real Service-Center Operations</h1>
        <p class="mt-4 max-w-3xl text-sm sm:text-base text-gray-600 dark:text-gray-300">
            SETU Suvidha is designed for operators who handle high-volume public-service workflows every day. The platform focuses on document accuracy, repeatable process quality, and faster customer turnaround.
        </p>
        <div class="mt-6 flex flex-wrap gap-2 text-xs">
            <span class="rounded-full border border-amber-200 bg-white/70 px-3 py-1 font-semibold text-amber-700 dark:border-amber-800 dark:bg-gray-900/50 dark:text-amber-300">Coverage: Maharashtra</span>
            <span class="rounded-full border border-amber-200 bg-white/70 px-3 py-1 font-semibold text-amber-700 dark:border-amber-800 dark:bg-gray-900/50 dark:text-amber-300">Audience: Setu / CSC operators</span>
            <span class="rounded-full border border-amber-200 bg-white/70 px-3 py-1 font-semibold text-amber-700 dark:border-amber-800 dark:bg-gray-900/50 dark:text-amber-300">Last reviewed: {{ now()->toDateString() }}</span>
        </div>
    </div>
</section>

<section class="py-14 bg-white dark:bg-gray-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <article class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/40 p-6">
                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-3">What We Solve</h2>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <li class="flex gap-2"><i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-500 mt-0.5"></i><span>Form drafting consistency across repeated citizen requests.</span></li>
                    <li class="flex gap-2"><i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-500 mt-0.5"></i><span>Billing and wallet workflows for daily service accounting.</span></li>
                    <li class="flex gap-2"><i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-500 mt-0.5"></i><span>Marathi-first practicality with structured templates.</span></li>
                    <li class="flex gap-2"><i data-lucide="check-circle-2" class="h-4 w-4 text-emerald-500 mt-0.5"></i><span>Service pages and guides to reduce application rejection.</span></li>
                </ul>
            </article>

            <article class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-900/40 p-6">
                <h2 class="text-xl font-black text-gray-900 dark:text-white mb-3">Operational Credibility</h2>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <li class="flex gap-2"><i data-lucide="badge-check" class="h-4 w-4 text-blue-500 mt-0.5"></i><span>Platform content is authored from field operations perspective.</span></li>
                    <li class="flex gap-2"><i data-lucide="badge-check" class="h-4 w-4 text-blue-500 mt-0.5"></i><span>Guides are maintained with practical checklists and FAQ blocks.</span></li>
                    <li class="flex gap-2"><i data-lucide="badge-check" class="h-4 w-4 text-blue-500 mt-0.5"></i><span>Updates are published in live pages and reflected in sitemap.</span></li>
                    <li class="flex gap-2"><i data-lucide="badge-check" class="h-4 w-4 text-blue-500 mt-0.5"></i><span>Cross-links connect service pages and review clusters for clarity.</span></li>
                </ul>
            </article>
        </div>

        <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50/70 dark:border-amber-800/40 dark:bg-amber-900/10 p-6">
            <h2 class="text-xl font-black text-amber-800 dark:text-amber-300 mb-3">High-Authority Content Clusters</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 text-sm">
                <a class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80" href="{{ route('services.landing.show', ['slug' => 'hamipatra-format-marathi']) }}">Hamipatra Format</a>
                <a class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80" href="{{ route('services.landing.show', ['slug' => 'income-certificate-application-maharashtra']) }}">Income Certificate</a>
                <a class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80" href="{{ route('services.landing.show', ['slug' => 'caste-certificate-form-maharashtra']) }}">Caste Certificate</a>
                <a class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80" href="{{ route('services.landing.show', ['slug' => 'rajpatra-gazette-format-marathi']) }}">Rajpatra Guide</a>
                <a class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80" href="{{ route('services.landing.show', ['slug' => 'satbara-7-12-utara-online-maharashtra']) }}">Satbara 7/12 Guide</a>
                <a class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80" href="{{ route('reviews.index') }}">All Reviews</a>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-white hover:bg-amber-600 transition">
                <i data-lucide="rocket" class="h-4 w-4"></i> Start free registration
            </a>
            <a href="{{ route('author') }}" class="inline-flex items-center gap-2 rounded-xl border border-amber-300 px-5 py-2.5 text-sm font-bold text-amber-700 hover:bg-amber-50 dark:border-amber-700 dark:text-amber-300 dark:hover:bg-amber-900/20 transition">
                <i data-lucide="user-circle" class="h-4 w-4"></i> View founder profile
            </a>
        </div>
    </div>
</section>
@endsection
