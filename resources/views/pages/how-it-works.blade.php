@extends('layouts.app')
@section('title', 'कसे काम करते — SETU Suvidha')
@section('content')
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-12">SETU Suvidha कसे काम करते?</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['step'=>'1','icon'=>'user-plus','title'=>'नोंदणी करा','desc'=>'मोफत खाते तयार करा — फक्त नाव, ईमेल आणि पासवर्ड.'],
                ['step'=>'2','icon'=>'wallet','title'=>'वॉलेट रिचार्ज','desc'=>'Razorpay द्वारे UPI/कार्ड/नेट बँकिंग ने ₹100 पासून रिचार्ज.'],
                ['step'=>'3','icon'=>'printer','title'=>'फॉर्म भरा व प्रिंट','desc'=>'फॉर्म निवडा, माहिती भरा, ऑटो-डिडक्शन, तात्काळ A4 प्रिंट.'],
            ] as $s)
            <div class="relative">
                <div class="w-14 h-14 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-amber-600">{{ $s['step'] }}</div>
                <i data-lucide="{{ $s['icon'] }}" class="w-8 h-8 text-amber-600 mx-auto mb-3"></i>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $s['title'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
