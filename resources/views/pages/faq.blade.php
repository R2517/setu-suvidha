@extends('layouts.app')
@section('title', 'FAQ — SETU Suvidha')
@section('content')
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center">वारंवार विचारले जाणारे प्रश्न</h1>
        <div class="space-y-4" x-data="{ open: null }">
            @foreach([
                ['q'=>'SETU Suvidha म्हणजे काय?','a'=>'महाराष्ट्रातील VLE, CSC केंद्र आणि ई-सेवा दुकानदारांसाठी सर्व सरकारी फॉर्म्स, CRM आणि बिलिंग एकाच ठिकाणी.'],
                ['q'=>'नोंदणी मोफत आहे का?','a'=>'हो! नोंदणी मोफत. फक्त वापरलेल्या फॉर्म्ससाठी ₹2 पासून शुल्क.'],
                ['q'=>'पेमेंट कसे करायचे?','a'=>'Razorpay — UPI, डेबिट/क्रेडिट कार्ड, नेट बँकिंग सर्व उपलब्ध.'],
                ['q'=>'डेटा सुरक्षित आहे का?','a'=>'हो! SSL, CSRF, bcrypt, Razorpay HMAC-SHA256 — पूर्णपणे सुरक्षित.'],
                ['q'=>'किती फॉर्म प्रकार आहेत?','a'=>'12+ फॉर्म प्रकार + 3 CRM मॉड्युल्स.'],
                ['q'=>'सपोर्ट कसा मिळतो?','a'=>'support@setusuvidha.com वर ईमेल करा किंवा संपर्क पृष्ठ वापरा.'],
            ] as $i => $f)
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                <button @click="open===@js($i)?open=null:open=@js($i)" class="w-full flex items-center justify-between px-6 py-4 text-left">
                    <span class="font-medium text-gray-900 dark:text-white">{{ $f['q'] }}</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 transition-transform" :class="open===@js($i)&&'rotate-180'"></i>
                </button>
                <div x-show="open===@js($i)" x-collapse class="px-6 pb-4 text-sm text-gray-500 dark:text-gray-400">{{ $f['a'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
