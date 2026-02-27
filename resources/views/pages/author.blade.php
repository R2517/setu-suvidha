@extends('layouts.app')

@section('title', 'Founder Profile | SETU Suvidha')
@section('description', 'Profile of Mr. Rajat, founder of SETU Suvidha, with operational background in CSC and Maha e-Seva workflows.')

@push('meta')
<meta name="keywords" content="setu suvidha founder, mr rajat, csc operator profile, maha e seva founder">
<meta property="og:title" content="Founder Profile | SETU Suvidha">
<meta property="og:description" content="Learn the operational background and publishing standards behind SETU Suvidha guides.">
<meta property="og:type" content="profile">
<meta property="og:url" content="{{ route('author') }}">
<link rel="canonical" href="{{ route('author') }}">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => 'Mr. Rajat',
    'jobTitle' => 'CSC and Maha e-Seva Operator',
    'description' => 'Founder of SETU Suvidha and author of service workflow guides.',
    'url' => route('author'),
    'worksFor' => [
        '@type' => 'Organization',
        'name' => 'SETU Suvidha',
        'url' => url('/'),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-16 sm:py-20">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-10 left-10 h-72 w-72 rounded-full bg-amber-300 blur-3xl"></div>
        <div class="absolute bottom-10 right-10 h-80 w-80 rounded-full bg-orange-300 blur-3xl"></div>
    </div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-8">
            <a href="{{ route('home') }}" class="hover:text-amber-600 transition">Home</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="text-gray-700 dark:text-gray-300 font-medium">Founder Profile</span>
        </nav>

        <div class="flex flex-col sm:flex-row items-center gap-8">
            <div class="shrink-0">
                <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-xl shadow-amber-200/50 dark:shadow-amber-900/20">
                    <span class="text-5xl sm:text-6xl font-black text-white">R</span>
                </div>
            </div>

            <div class="text-center sm:text-left">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-2">Mr. Rajat</h1>
                <div class="flex flex-wrap justify-center sm:justify-start gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-3 py-1 rounded-full text-[11px] font-bold">
                        <i data-lucide="building-2" class="w-3 h-3"></i> CSC Operator
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-full text-[11px] font-bold">
                        <i data-lucide="code-2" class="w-3 h-3"></i> Platform Builder
                    </span>
                    <span class="inline-flex items-center gap-1.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-3 py-1 rounded-full text-[11px] font-bold">
                        <i data-lucide="book-open-check" class="w-3 h-3"></i> Guide Author
                    </span>
                </div>
                <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed max-w-xl">
                    SETU Suvidha is built from practical field needs: faster form handling, lower rejection rate, and better day-end accounting for service centers.
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Publishing cadence: weekly updates, monthly review cycle.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-14 bg-white dark:bg-gray-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <article class="rounded-2xl border border-red-200 bg-red-50/50 dark:bg-red-900/10 dark:border-red-900/30 p-6">
                <h2 class="text-xl font-black text-red-700 dark:text-red-300 mb-3">Problems Observed in Daily Operations</h2>
                <ul class="space-y-2 text-sm text-red-800/90 dark:text-red-200/90">
                    <li class="flex gap-2"><i data-lucide="x-circle" class="h-4 w-4 mt-0.5"></i><span>Frequent document mismatch between forms and proofs.</span></li>
                    <li class="flex gap-2"><i data-lucide="x-circle" class="h-4 w-4 mt-0.5"></i><span>Manual registers with low traceability and audit difficulty.</span></li>
                    <li class="flex gap-2"><i data-lucide="x-circle" class="h-4 w-4 mt-0.5"></i><span>Generic templates that are not office-ready.</span></li>
                    <li class="flex gap-2"><i data-lucide="x-circle" class="h-4 w-4 mt-0.5"></i><span>No unified workflow for service + billing + tracking.</span></li>
                </ul>
            </article>

            <article class="rounded-2xl border border-emerald-200 bg-emerald-50/50 dark:bg-emerald-900/10 dark:border-emerald-900/30 p-6">
                <h2 class="text-xl font-black text-emerald-700 dark:text-emerald-300 mb-3">Standards Used in SETU Suvidha</h2>
                <ul class="space-y-2 text-sm text-emerald-800/90 dark:text-emerald-200/90">
                    <li class="flex gap-2"><i data-lucide="check-circle-2" class="h-4 w-4 mt-0.5"></i><span>Template consistency with practical pre-submission checks.</span></li>
                    <li class="flex gap-2"><i data-lucide="check-circle-2" class="h-4 w-4 mt-0.5"></i><span>Bilingual guide writing with implementation-focused format.</span></li>
                    <li class="flex gap-2"><i data-lucide="check-circle-2" class="h-4 w-4 mt-0.5"></i><span>Operational linking between service pages and review guides.</span></li>
                    <li class="flex gap-2"><i data-lucide="check-circle-2" class="h-4 w-4 mt-0.5"></i><span>Content review markers and periodic update cadence.</span></li>
                </ul>
            </article>
        </div>

        <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50/70 dark:border-amber-800/40 dark:bg-amber-900/10 p-6">
            <h2 class="text-xl font-black text-amber-800 dark:text-amber-300 mb-3">Focus Clusters</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 text-sm">
                <a href="{{ route('services.landing.show', ['slug' => 'hamipatra-format-marathi']) }}" class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80">Hamipatra and declarations</a>
                <a href="{{ route('services.landing.show', ['slug' => 'income-certificate-application-maharashtra']) }}" class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80">Income and caste certificates</a>
                <a href="{{ route('services.landing.show', ['slug' => 'satbara-7-12-utara-online-maharashtra']) }}" class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80">Land record support</a>
                <a href="{{ route('reviews.show', ['slug' => 'pm-kisan-status-check-2026']) }}" class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80">PM Kisan and e-KYC</a>
                <a href="{{ route('reviews.show', ['slug' => 'setu-kendra-registration-maharashtra']) }}" class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80">Center setup operations</a>
                <a href="{{ route('reviews.index') }}" class="rounded-lg bg-white dark:bg-gray-900 px-3 py-2 font-semibold text-amber-700 dark:text-amber-300 hover:opacity-80">All published reviews</a>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-white hover:bg-amber-600 transition">
                <i data-lucide="rocket" class="h-4 w-4"></i> Create free account
            </a>
            <a href="{{ route('services') }}" class="inline-flex items-center gap-2 rounded-xl border border-amber-300 px-5 py-2.5 text-sm font-bold text-amber-700 hover:bg-amber-50 dark:border-amber-700 dark:text-amber-300 dark:hover:bg-amber-900/20 transition">
                <i data-lucide="grid-3x3" class="h-4 w-4"></i> Explore service catalog
            </a>
        </div>
    </div>
</section>
@endsection
