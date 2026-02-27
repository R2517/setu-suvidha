@extends('layouts.app')

@section('title', $page['title'] ?? 'Service Guide | SETU Suvidha')
@section('description', $page['description'] ?? 'Detailed service guide for Maharashtra users.')

@push('meta')
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ url($page['og_image'] ?? config('seo.default_og_image')) }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="{{ url($page['og_image'] ?? config('seo.default_og_image')) }}">
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    {"@@type":"ListItem","position":1,"name":"Home","item":"{{ url('/') }}"},
    {"@@type":"ListItem","position":2,"name":"Services","item":"{{ url('/services') }}"},
    {"@@type":"ListItem","position":3,"name":"{{ addslashes($page['h1'] ?? 'Service Guide') }}","item":"{{ url()->current() }}"}
  ]
}
</script>
<script type="application/ld+json">
{
  "@@context":"https://schema.org",
  "@@type":"FAQPage",
  "mainEntity":[
    @foreach(($page['faqs'] ?? []) as $faq)
    {
      "@@type":"Question",
      "name":"{{ addslashes($faq['q']) }}",
      "acceptedAnswer":{"@@type":"Answer","text":"{{ addslashes($faq['a']) }}"}
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endpush

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-16">
    <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top_right,_rgba(251,146,60,0.45),_transparent_45%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.25),_transparent_45%)]"></div>
    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/20 border border-orange-300/40 text-orange-100 text-xs font-semibold uppercase tracking-wide mb-4">
            <i data-lucide="book-open-check" class="w-3.5 h-3.5"></i> {{ $page['hero_kicker'] ?? 'Service Guide' }}
        </p>
        <h1 class="text-3xl lg:text-4xl font-black text-white leading-tight">{{ $page['h1'] ?? 'Service Guide' }}</h1>
        <p class="mt-4 text-sm sm:text-base text-slate-200 max-w-3xl mx-auto">{{ $page['description'] ?? '' }}</p>
        <div class="mt-6 flex flex-wrap gap-2 justify-center">
            <span class="px-3 py-1 rounded-full bg-white/10 text-slate-100 text-xs font-semibold">Updated: {{ $page['updated_at'] ?? now()->toDateString() }}</span>
            <span class="px-3 py-1 rounded-full bg-white/10 text-slate-100 text-xs font-semibold">{{ $page['category'] ?? 'Government Service' }}</span>
        </div>
    </div>
</section>

<section class="py-14 bg-white dark:bg-gray-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose max-w-none dark:prose-invert prose-slate">
            @foreach(($page['intro'] ?? []) as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
            <p>For operators, the biggest SEO and service advantage comes from consistent process quality. When every applicant gets a clean and complete output, your center gets repeat traffic, positive referrals, and lower rejection loops from authority-level desks.</p>
            <p>This page is intentionally written in practical bilingual style so both operator and end-customer can understand what is needed before submission. Use this as your internal SOP for daily operations.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-10">
            <article class="rounded-2xl border border-emerald-200 bg-emerald-50/60 dark:bg-emerald-900/20 dark:border-emerald-800 p-6">
                <h2 class="text-lg font-black text-emerald-800 dark:text-emerald-300 mb-4 flex items-center gap-2">
                    <i data-lucide="badge-check" class="w-5 h-5"></i> Why This Service Matters
                </h2>
                <ul class="space-y-2 text-sm text-emerald-900 dark:text-emerald-200">
                    @foreach(($page['why_important'] ?? []) as $item)
                    <li class="flex items-start gap-2">
                        <i data-lucide="check-circle-2" class="w-4 h-4 mt-0.5 shrink-0"></i>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </article>

            <article class="rounded-2xl border border-blue-200 bg-blue-50/60 dark:bg-blue-900/20 dark:border-blue-800 p-6">
                <h2 class="text-lg font-black text-blue-800 dark:text-blue-300 mb-4 flex items-center gap-2">
                    <i data-lucide="list-checks" class="w-5 h-5"></i> Step-by-Step Workflow
                </h2>
                <ol class="space-y-2 text-sm text-blue-900 dark:text-blue-200 list-decimal pl-5">
                    @foreach(($page['steps'] ?? []) as $step)
                    <li>{{ $step }}</li>
                    @endforeach
                </ol>
            </article>
        </div>

        <div class="mt-8 rounded-2xl border border-amber-200 bg-amber-50/60 dark:bg-amber-900/20 dark:border-amber-800 p-6">
            <h2 class="text-lg font-black text-amber-800 dark:text-amber-300 mb-4 flex items-center gap-2">
                <i data-lucide="folder-open" class="w-5 h-5"></i> Required Documents Checklist
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-amber-900 dark:text-amber-200">
                @foreach(($page['required_documents'] ?? []) as $doc)
                <div class="flex items-center gap-2 rounded-lg bg-white/70 dark:bg-gray-900/40 border border-amber-100 dark:border-amber-900/40 px-3 py-2">
                    <i data-lucide="file-check" class="w-4 h-4 text-amber-600"></i>
                    <span>{{ $doc }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-red-200 bg-red-50/60 dark:bg-red-900/20 dark:border-red-800 p-6">
            <h2 class="text-lg font-black text-red-800 dark:text-red-300 mb-4 flex items-center gap-2">
                <i data-lucide="shield-alert" class="w-5 h-5"></i> Common Mistakes to Avoid
            </h2>
            <ul class="space-y-2 text-sm text-red-900 dark:text-red-200">
                @foreach(($page['common_mistakes'] ?? []) as $mistake)
                <li class="flex items-start gap-2">
                    <i data-lucide="x-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                    <span>{{ $mistake }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

<section class="py-14 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-6">Frequently Asked Questions</h2>
        <div class="space-y-3" x-data="{open: null}">
            @foreach(($page['faqs'] ?? []) as $i => $faq)
            <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 overflow-hidden">
                <button class="w-full flex items-center justify-between px-5 py-4 text-left" @click="open === {{ $i }} ? open = null : open = {{ $i }}">
                    <span class="font-semibold text-sm text-gray-900 dark:text-white">{{ $faq['q'] }}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-5 pb-4 text-sm text-gray-600 dark:text-gray-300">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10 rounded-2xl bg-gradient-to-r from-orange-500 to-amber-500 p-6 text-white">
            <h3 class="text-xl font-black mb-2">Need fast execution for this service?</h3>
            <p class="text-sm text-orange-50 mb-5">Create your account and start processing forms with standardized templates, wallet workflow, and audit-ready service records.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-orange-600 font-bold text-sm hover:bg-orange-50 transition">
                    <i data-lucide="rocket" class="w-4 h-4"></i> Activate and Register
                </a>
                <a href="{{ route('services') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600/40 border border-orange-200/50 text-white font-bold text-sm hover:bg-orange-600/60 transition">
                    <i data-lucide="grid-3x3" class="w-4 h-4"></i> Explore All Services
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

