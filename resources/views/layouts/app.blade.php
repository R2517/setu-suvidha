<!DOCTYPE html>
<html lang="mr" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    @php
        $routeName = request()->route()?->getName();
        $indexableRoutes = config('seo.indexable_routes', []);
        $isIndexableRoute = $routeName && in_array($routeName, $indexableRoutes, true);

        $seoTitle = trim($__env->yieldContent('title', config('app.name', 'SETU Suvidha')));
        $seoDescription = trim($__env->yieldContent('description', 'SETU Suvidha public services and government form platform.'));
        $seoCanonical = trim($__env->yieldContent('canonical'));
        $seoRobots = trim($__env->yieldContent('robots'));
        if ($seoCanonical === '') {
            $seoCanonical = url()->current();
        }
        if ($seoRobots === '') {
            $seoRobots = $isIndexableRoute ? 'index, follow' : 'noindex, nofollow';
        }

        $seoOgImage = trim($__env->yieldContent('og_image'));
        if ($seoOgImage === '' && $routeName === 'reviews.show') {
            $slug = (string) request()->route('slug');
            $seoOgImage = (string) data_get(config('reviews.articles', []), $slug . '.og_image', '');
        }
        if ($seoOgImage === '' && $routeName === 'services.landing.show') {
            $slug = (string) request()->route('slug');
            $seoOgImage = (string) data_get(config('service_pages.pages', []), $slug . '.og_image', '');
        }
        if ($seoOgImage === '' && $routeName !== null) {
            $seoOgImage = (string) data_get(config('seo.route_og_images', []), $routeName, '');
        }
        if ($seoOgImage === '') {
            $seoOgImage = (string) config('seo.default_og_image', '/images/og/home.png');
        }
        if (!str_starts_with($seoOgImage, 'http')) {
            $seoOgImage = url($seoOgImage);
        }

        $seoSocialUrls = array_values(array_filter((array) config('seo.social', [])));
        $gtmContainerId = trim((string) config('seo.gtm_container_id', ''));
        $ga4MeasurementId = trim((string) config('seo.ga4_measurement_id', ''));
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:image" content="{{ $seoOgImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoOgImage }}">
    <link rel="canonical" href="{{ $seoCanonical }}">

    @if(config('seo.gsc_verification'))
    <meta name="google-site-verification" content="{{ config('seo.gsc_verification') }}">
    @endif
    @if(config('seo.bing_verification'))
    <meta name="msvalidate.01" content="{{ config('seo.bing_verification') }}">
    @endif

    @if($isIndexableRoute)
    <link rel="alternate" hreflang="mr-IN" href="{{ $seoCanonical }}">
    <link rel="alternate" hreflang="en-IN" href="{{ $seoCanonical }}">
    <link rel="alternate" hreflang="x-default" href="{{ $seoCanonical }}">
    @endif

    @if($gtmContainerId !== '')
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ $gtmContainerId }}');
    </script>
    @elseif($ga4MeasurementId !== '')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4MeasurementId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $ga4MeasurementId }}');
    </script>
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('meta')
    @stack('styles')

    @if($routeName === 'home')
        @php
            $websiteSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => 'SETU Suvidha',
                'url' => url('/'),
                'inLanguage' => ['mr-IN', 'en-IN'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => url('/reviews?q={search_term_string}'),
                    'query-input' => 'required name=search_term_string',
                ],
            ];
        @endphp
    <script type="application/ld+json">{!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @if($routeName === 'services')
        @php
            $servicesBreadcrumbSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Services', 'item' => url('/services')],
                ],
            ];
        @endphp
    <script type="application/ld+json">{!! json_encode($servicesBreadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    @if($routeName === 'reviews.show')
        @php
            $reviewsBreadcrumbSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Reviews', 'item' => url('/reviews')],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => (string) data_get(config('reviews.articles', []), request()->route('slug') . '.title_en', 'Review'),
                        'item' => $seoCanonical,
                    ],
                ],
            ];
        @endphp
    <script type="application/ld+json">{!! json_encode($reviewsBreadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
</head>
<body class="font-sans antialiased bg-background text-foreground min-h-screen" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
    @if($gtmContainerId !== '')
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmContainerId }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    {{-- Toast Notification --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition class="fixed top-4 right-4 z-[100] bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition class="fixed top-4 right-4 z-[100] bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Navbar --}}
    @include('components.navbar')


    {{-- Main Content & Ads Layout --}}
    <div class="mx-auto w-full px-2 sm:px-4 lg:px-8 py-6">
        <div class="mb-4">
            @include('components.ad-slot', ['slotName' => 'top_banner', 'class' => 'w-full'])
        </div>
        
        <div class="flex flex-col xl:flex-row gap-6">
            {{-- Left Ad Sidebar (Visible on extra large screens) --}}
            <aside class="hidden xl:block w-[160px] 2xl:w-[200px] shrink-0 sticky top-24 h-[calc(100vh-8rem)]">
                @include('components.ad-slot', ['slotName' => 'left_sidebar', 'class' => 'h-full'])
            </aside>
            
            {{-- Main Content Area --}}
            <main class="flex-1 min-w-0">
                @yield('content')
            </main>
            
            {{-- Right Ad Sidebar (Visible on extra large screens) --}}
            <aside class="hidden xl:block w-[160px] 2xl:w-[200px] shrink-0 sticky top-24 h-[calc(100vh-8rem)]">
                @include('components.ad-slot', ['slotName' => 'right_sidebar', 'class' => 'h-full'])
            </aside>
        </div>

        <div class="mt-6">
            @include('components.ad-slot', ['slotName' => 'bottom_banner', 'class' => 'w-full'])
        </div>
    </div>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Floating Helpdesk Button (auth only, not admin) --}}
    @auth
    @if(!request()->is('admin/*'))
        @include('components.helpdesk-widget')
    @endif
    @endauth



    @stack('scripts')
</body>
</html>
