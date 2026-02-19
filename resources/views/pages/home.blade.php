@extends('layouts.app')
@section('title', 'SETU Suvidha — सेतू सुविधा | महा ई-सेवा पोर्टल | सर्व सरकारी फॉर्म्स एकाच ठिकाणी')
@section('description', 'सेतु केंद्र, CSC केंद्र, महा ई-सेवा दुकानदारांसाठी — हमीपत्र, उत्पन्नाचा दाखला, जातीचा दाखला, राजपत्र, शेतकरी ओळखपत्र, आधार सेवा, पॅन कार्ड, बांधकाम कामगार नोंदणी. आपले सरकार सेवा सेतू सुविधा केंद्र.')

@push('meta')
<meta name="keywords" content="setu suvidha, सेतू सुविधा, setu kendra, सेतू केंद्र, maha e seva, महा ई-सेवा, CSC center Maharashtra, aaple sarkar seva kendra, सेतू सुविधा केंद्र जवळ, government forms online Maharashtra, हमीपत्र, उत्पन्नाचा दाखला, जातीचा दाखला, राजपत्र नमुना, शेतकरी ओळखपत्र, farmer id card online, income certificate Maharashtra, caste certificate Maharashtra, सरकारी फॉर्म्स, digital seva portal, maha e seva kendra registration">
<meta property="og:title" content="SETU Suvidha — महा ई-सेवा पोर्टल | सर्व सरकारी फॉर्म्स एकाच ठिकाणी">
<meta property="og:description" content="सेतु केंद्र आणि महा ई-सेवा दुकानदारांसाठी — हमीपत्र, उत्पन्नाचा दाखला, जातीचा दाखला, शेतकरी ओळखपत्र आणि बरंच काही!">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/') }}">
<link rel="canonical" href="{{ url('/') }}">
<script type="application/ld+json">
{"@@context":"https://schema.org","@@type":"Organization","name":"SETU Suvidha","alternateName":"सेतू सुविधा","url":"https://setusuvidha.com","description":"महाराष्ट्रातील सेतू केंद्र, CSC केंद्र आणि महा ई-सेवा दुकानदारांसाठी सर्व सरकारी फॉर्म्स, CRM आणि बिलिंग प्लॅटफॉर्म.","areaServed":{"@@type":"State","name":"Maharashtra"}}
</script>
@endpush

@section('content')
{{-- Hero Section --}}
<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-20 lg:py-28">
    <div class="absolute inset-0 opacity-30 dark:opacity-10" style="background-image: radial-gradient(circle, #f59e0b 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="absolute top-10 right-10 w-64 h-64 bg-amber-400/10 rounded-full blur-3xl animate-float"></div>
    <div class="absolute bottom-10 left-10 w-48 h-48 bg-orange-400/10 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-4 py-1.5 rounded-full text-xs font-semibold mb-6">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> महाराष्ट्रातील #1 ई-सेवा प्लॅटफॉर्म
            </div>
            <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                सर्व सरकारी फॉर्म्स<br>
                <span class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">एकाच ठिकाणी</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-10 max-w-2xl mx-auto">
                सेतु केंद्र, CSC केंद्र आणि महा ई-सेवा दुकानदारांसाठी — हमीपत्र, राजपत्र, उत्पन्न प्रमाणपत्र, शेतकरी ओळखपत्र आणि बरंच काही!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="btn-primary text-base !px-8 !py-4">
                    <i data-lucide="rocket" class="w-5 h-5"></i> मोफत सुरुवात करा
                </a>
                <a href="{{ route('services') }}" class="btn-outline text-base !px-8 !py-4">
                    <i data-lucide="grid-3x3" class="w-5 h-5"></i> सेवा पहा
                </a>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-16 max-w-2xl mx-auto">
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur rounded-2xl p-4 text-center">
                    <div class="text-2xl font-bold text-amber-600">20+</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">फॉर्म प्रकार</div>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur rounded-2xl p-4 text-center">
                    <div class="text-2xl font-bold text-amber-600">5,000+</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">VLE केंद्रे</div>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur rounded-2xl p-4 text-center">
                    <div class="text-2xl font-bold text-amber-600">36</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">जिल्हे</div>
                </div>
                <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur rounded-2xl p-4 text-center">
                    <div class="text-2xl font-bold text-amber-600">₹2</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">पासून शुल्क</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services Section --}}
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">आमच्या सेवा</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-xl mx-auto">सर्व आवश्यक सरकारी फॉर्म्स आणि प्रमाणपत्रे एका क्लिकवर उपलब्ध</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'fingerprint', 'title' => 'आधार सेवा Hub', 'desc' => 'Adult, Minor, Child, Update Forms', 'color' => 'orange', 'url' => null],
                ['icon' => 'file-text', 'title' => 'हमीपत्र', 'desc' => 'Disclaimer / Guarantee Bond', 'color' => 'blue', 'url' => null],
                ['icon' => 'shield', 'title' => 'स्वयंघोषणापत्र', 'desc' => 'Self-Declaration Form', 'color' => 'green', 'url' => null],
                ['icon' => 'alert-triangle', 'title' => 'तक्रार नोंदणी', 'desc' => 'Grievance Registration', 'color' => 'yellow', 'url' => null],
                ['icon' => 'file-plus', 'title' => 'नवीन अर्ज', 'desc' => 'New Application', 'color' => 'purple', 'url' => null],
                ['icon' => 'badge-check', 'title' => 'जात पडताळणी', 'desc' => 'Caste Validity', 'color' => 'teal', 'url' => null],
                ['icon' => 'landmark', 'title' => 'उत्पन्न प्रमाणपत्र', 'desc' => 'Income Certificate', 'color' => 'pink', 'url' => null],
                ['icon' => 'scale', 'title' => 'राजपत्र नमुना', 'desc' => 'Gazette Notice (3 formats)', 'color' => 'emerald', 'url' => null],
                ['icon' => 'leaf', 'title' => 'शेतकरी ओळखपत्र', 'desc' => 'Farmer ID Card with QR', 'color' => 'lime', 'url' => route('farmer-card-public')],
                ['icon' => 'camera', 'title' => 'पासपोर्ट फोटो', 'desc' => 'Passport Photo Maker', 'color' => 'rose', 'url' => null],
                ['icon' => 'credit-card', 'title' => 'पॅन कार्ड CRM', 'desc' => 'PAN Card Applications', 'color' => 'indigo', 'url' => null],
                ['icon' => 'hard-hat', 'title' => 'बांधकाम कामगार', 'desc' => 'Construction Worker CRM', 'color' => 'orange', 'url' => null],
            ] as $service)
            <a href="{{ $service['url'] ?? route('services') }}" class="group bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-800 block">
                <div class="w-12 h-12 rounded-xl bg-{{ $service['color'] }}-100 dark:bg-{{ $service['color'] }}-900/30 flex items-center justify-center mb-4">
                    <i data-lucide="{{ $service['icon'] }}" class="w-6 h-6 text-{{ $service['color'] }}-600 dark:text-{{ $service['color'] }}-400"></i>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $service['title'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $service['desc'] }}</p>
                <span class="inline-flex items-center gap-1 text-xs font-semibold text-{{ $service['color'] }}-600 mt-3 group-hover:gap-2 transition-all">{{ $service['url'] ? 'Get Now →' : 'View →' }} <i data-lucide="arrow-right" class="w-3 h-3"></i></span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Benefits Section --}}
<section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">SETU Suvidha का वापरावे?</h2>
            <p class="text-gray-500 dark:text-gray-400">तुमच्या कामकाजासाठी सर्वोत्तम पर्याय</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach([
                ['icon' => 'zap', 'title' => 'तात्काळ प्रिंट', 'desc' => 'फॉर्म भरा आणि तात्काळ A4 प्रिंट घ्या — कोणत्याही विलंबाशिवाय.'],
                ['icon' => 'shield-check', 'title' => '100% सुरक्षित', 'desc' => 'SSL एन्क्रिप्शन, CSRF प्रोटेक्शन आणि सुरक्षित पेमेंट गेटवे.'],
                ['icon' => 'wallet', 'title' => 'प्रीपेड वॉलेट', 'desc' => '₹2 पासून सुरुवात — Razorpay द्वारे रिचार्ज करा, प्रत्येक फॉर्मसाठी ऑटो-डिडक्शन.'],
                ['icon' => 'palette', 'title' => '24 थीम्स', 'desc' => 'तुमच्या आवडीनुसार डॅशबोर्ड रंग बदला — लाइट आणि डार्क मोड.'],
                ['icon' => 'smartphone', 'title' => 'मोबाइल फ्रेंडली', 'desc' => 'सर्व डिव्हाइसवर परिपूर्ण — मोबाइल, टॅबलेट आणि डेस्कटॉप.'],
                ['icon' => 'headphones', 'title' => 'सपोर्ट', 'desc' => 'तुमच्या सर्व प्रश्नांसाठी समर्पित ग्राहक सेवा उपलब्ध.'],
            ] as $benefit)
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mb-4">
                    <i data-lucide="{{ $benefit['icon'] }}" class="w-6 h-6 text-amber-600"></i>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $benefit['title'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $benefit['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Pricing Section --}}
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">सोपी किंमत</h2>
            <p class="text-gray-500 dark:text-gray-400">कोणताही लपलेला खर्च नाही — फक्त फॉर्म शुल्क भरा</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            {{-- Basic --}}
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-8 border border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">बेसिक</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">मोफत सुरुवात</p>
                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-6">₹0 <span class="text-sm font-normal text-gray-500">/महिना</span></div>
                <ul class="space-y-3 mb-8 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> खाते तयार करा</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> सर्व फॉर्म्स वापरा</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> प्रति फॉर्म शुल्क</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> व्यवहार इतिहास</li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full text-center btn-outline">सुरुवात करा</a>
            </div>
            {{-- Pro --}}
            <div class="relative bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-8 text-white shadow-xl scale-105">
                <div class="absolute -top-3 right-6 bg-white text-amber-600 text-xs font-bold px-3 py-1 rounded-full">लोकप्रिय</div>
                <h3 class="text-lg font-bold mb-1">प्रो</h3>
                <p class="text-white/80 text-sm mb-4">व्यावसायिकांसाठी</p>
                <div class="text-4xl font-bold mb-6">₹49 <span class="text-sm font-normal text-white/80">/महिना</span></div>
                <ul class="space-y-3 mb-8 text-sm text-white/90">
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4"></i> सर्व बेसिक फीचर्स</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4"></i> कमी शुल्क दर</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4"></i> प्राधान्य सपोर्ट</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4"></i> बल्क प्रिंट</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4"></i> अॅडव्हान्स रिपोर्ट्स</li>
                </ul>
                <a href="{{ route('register') }}" class="block w-full text-center bg-white text-amber-600 font-semibold py-3 rounded-xl hover:bg-amber-50 transition">प्रो निवडा</a>
            </div>
            {{-- Enterprise --}}
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-8 border border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">एंटरप्राइज</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">मोठ्या संस्थांसाठी</p>
                <div class="text-4xl font-bold text-gray-900 dark:text-white mb-6">संपर्क <span class="text-sm font-normal text-gray-500">करा</span></div>
                <ul class="space-y-3 mb-8 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> सर्व प्रो फीचर्स</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> कस्टम ब्रँडिंग</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> API ऍक्सेस</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> डेडिकेटेड सपोर्ट</li>
                    <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-green-500"></i> मल्टी-लोकेशन</li>
                </ul>
                <a href="{{ route('contact') }}" class="block w-full text-center btn-outline">संपर्क करा</a>
            </div>
        </div>
    </div>
</section>

{{-- Farmer ID Card CTA (SEO) --}}
<section class="py-10 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-900 dark:to-gray-900 border-y border-green-100 dark:border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center gap-6 sm:gap-8">
            <div class="w-16 h-16 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                <i data-lucide="leaf" class="w-8 h-8 text-green-600"></i>
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Farmer ID Card Online — शेतकरी ओळखपत्र मोफत बनवा</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Download <strong>Kisan Identity Card with QR Code</strong> instantly. PM Kisan, crop insurance, KCC loan — सर्व शासकीय योजनांसाठी ओळख पुरावा. Maharashtra, West Bengal, UP, MP & all India.</p>
            </div>
            <a href="{{ url('/services/farmer-id-card-online') }}" class="shrink-0 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-xl transition text-sm shadow-md">
                <i data-lucide="download" class="w-4 h-4"></i> Get Farmer ID Card
            </a>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">वारंवार विचारले जाणारे प्रश्न</h2>
        </div>
        <div class="space-y-4" x-data="{ open: null }">
            @foreach([
                ['q' => 'SETU Suvidha म्हणजे काय?', 'a' => 'SETU Suvidha हे महाराष्ट्रातील सेतु केंद्र, CSC केंद्र आणि ई-सेवा दुकानदारांसाठी एक ऑनलाइन प्लॅटफॉर्म आहे जिथे सर्व सरकारी फॉर्म्स, CRM आणि बिलिंग व्यवस्थापन एकाच ठिकाणी उपलब्ध आहे.'],
                ['q' => 'नोंदणी मोफत आहे का?', 'a' => 'हो! नोंदणी पूर्णपणे मोफत आहे. तुम्ही फक्त वापरलेल्या फॉर्म्ससाठी शुल्क भरता (₹2 पासून).'],
                ['q' => 'पेमेंट कसे करायचे?', 'a' => 'तुम्ही Razorpay द्वारे UPI, डेबिट/क्रेडिट कार्ड किंवा नेट बँकिंग वापरून वॉलेट रिचार्ज करू शकता.'],
                ['q' => 'डेटा सुरक्षित आहे का?', 'a' => 'हो! आम्ही SSL एन्क्रिप्शन, CSRF प्रोटेक्शन आणि सुरक्षित डेटाबेस वापरतो. तुमचा डेटा पूर्णपणे सुरक्षित आहे.'],
                ['q' => 'किती फॉर्म प्रकार उपलब्ध आहेत?', 'a' => '12+ फॉर्म प्रकार उपलब्ध आहेत — हमीपत्र, स्वयंघोषणापत्र, तक्रार, राजपत्र, उत्पन्न प्रमाणपत्र, शेतकरी ओळखपत्र आणि बरंच काही.'],
                ['q' => 'सपोर्ट कसा मिळतो?', 'a' => 'ईमेल: support@setusuvidha.com वर संपर्क करा किंवा संपर्क पृष्ठावरून तुमचा प्रश्न विचारा.'],
            ] as $i => $faq)
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <button @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="w-full flex items-center justify-between px-6 py-4 text-left">
                    <span class="font-medium text-gray-900 dark:text-white">{{ $faq['q'] }}</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform" :class="open === {{ $i }} && 'rotate-180'"></i>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-4 text-sm text-gray-500 dark:text-gray-400">{{ $faq['a'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-gradient-to-r from-amber-600 to-orange-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl lg:text-4xl font-bold mb-4">आजच सुरुवात करा!</h2>
        <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">महाराष्ट्रातील हजारो VLE केंद्रे आधीच SETU Suvidha वापरत आहेत. तुम्हीही सामील व्हा!</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-amber-600 font-bold px-8 py-4 rounded-xl hover:bg-amber-50 transition text-lg shadow-lg">
            <i data-lucide="rocket" class="w-5 h-5"></i> मोफत नोंदणी करा
        </a>
    </div>
</section>
@endsection
