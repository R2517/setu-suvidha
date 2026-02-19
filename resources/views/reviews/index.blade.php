@extends('layouts.app')
@section('title', 'रिव्ह्यू — शासकीय योजना व सेवा माहिती | SETU Suvidha')
@section('description', 'महाराष्ट्रातील शासकीय योजना, विभाग आणि सेवांचे सविस्तर रिव्ह्यू व मार्गदर्शक. बांधकाम कामगार, शेतकरी ओळखपत्र आणि इतर सरकारी सेवांची संपूर्ण माहिती.')

@push('meta')
<meta property="og:title" content="रिव्ह्यू — शासकीय योजना माहिती | SETU Suvidha">
<meta property="og:description" content="महाराष्ट्रातील शासकीय योजनांचे सविस्तर रिव्ह्यू आणि मार्गदर्शक.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/reviews') }}">
<link rel="canonical" href="{{ url('/reviews') }}">
@endpush

@section('content')
{{-- Hero --}}
<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-white to-orange-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-16 sm:py-24">
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style="background-image:url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=%270 0 60 60%27 xmlns=%27http://www.w3.org/2000/svg%27%3E%3Cg fill=%27none%27 fill-rule=%27evenodd%27%3E%3Cg fill=%27%23000%27 fill-opacity=%271%27%3E%3Cpath d=%27M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%27/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
        <div class="inline-flex items-center gap-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-4 py-1.5 rounded-full text-xs font-bold mb-6">
            <i data-lucide="book-open" class="w-3.5 h-3.5"></i>
            शासकीय योजना व सेवा रिव्ह्यू
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4">
            सरकारी योजनांची <span class="bg-gradient-to-r from-amber-500 to-orange-600 bg-clip-text text-transparent">संपूर्ण माहिती</span>
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            महाराष्ट्र शासनाच्या विविध विभागांच्या योजना, नोंदणी प्रक्रिया, पात्रता निकष आणि लाभ — सोप्या भाषेत, सविस्तर माहिती.
        </p>
    </div>
</section>

{{-- Articles Grid --}}
<section class="py-12 sm:py-16 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($articles as $article)
            <a href="{{ url('/reviews/' . $article['slug']) }}" class="group block">
                <article class="relative bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    {{-- Color Bar --}}
                    <div class="h-1.5 bg-gradient-to-r from-{{ $article['color'] }}-400 to-{{ $article['color'] }}-600"></div>
                    <div class="p-6 sm:p-8">
                        {{-- Category + Read Time --}}
                        <div class="flex items-center gap-3 mb-4">
                            <span class="inline-flex items-center gap-1.5 bg-{{ $article['color'] }}-100 dark:bg-{{ $article['color'] }}-900/30 text-{{ $article['color'] }}-700 dark:text-{{ $article['color'] }}-400 px-2.5 py-1 rounded-full text-[11px] font-bold">
                                <i data-lucide="{{ $article['icon'] }}" class="w-3 h-3"></i>
                                {{ $article['category'] }}
                            </span>
                            <span class="text-[11px] text-gray-400 dark:text-gray-500 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i> {{ $article['read_time'] }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-{{ $article['color'] }}-600 dark:group-hover:text-{{ $article['color'] }}-400 transition-colors leading-snug mb-3">
                            {{ $article['title'] }}
                        </h2>
                        <p class="text-[13px] text-gray-500 dark:text-gray-400 mb-1 font-medium">{{ $article['title_en'] }}</p>

                        {{-- Excerpt --}}
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mt-3">
                            {{ $article['excerpt'] }}
                        </p>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <span class="text-xs text-gray-400">{{ $article['date'] }}</span>
                            <span class="inline-flex items-center gap-1.5 text-{{ $article['color'] }}-600 dark:text-{{ $article['color'] }}-400 text-xs font-bold group-hover:gap-2.5 transition-all">
                                संपूर्ण वाचा <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </span>
                        </div>
                    </div>
                </article>
            </a>
            @endforeach
        </div>

        {{-- SEO: Internal Links --}}
        <div class="mt-16 bg-gradient-to-br from-gray-50 to-amber-50/50 dark:from-gray-900 dark:to-gray-900 rounded-2xl p-8 border border-gray-100 dark:border-gray-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <i data-lucide="link" class="w-5 h-5 text-amber-500"></i>
                SETU Suvidha वर उपलब्ध सेवा
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <a href="{{ url('/services/farmer-id-card-online') }}" class="flex items-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:shadow-md transition-all">
                    <i data-lucide="leaf" class="w-4 h-4 text-green-500"></i> शेतकरी ओळखपत्र (Farmer ID)
                </a>
                <a href="{{ url('/services') }}" class="flex items-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 hover:shadow-md transition-all">
                    <i data-lucide="file-text" class="w-4 h-4 text-amber-500"></i> सर्व सरकारी फॉर्म्स
                </a>
                <a href="{{ url('/about') }}" class="flex items-center gap-2 px-4 py-3 bg-white dark:bg-gray-800 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:shadow-md transition-all">
                    <i data-lucide="info" class="w-4 h-4 text-blue-500"></i> SETU Suvidha बद्दल
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
