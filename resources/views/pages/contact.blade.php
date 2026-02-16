@extends('layouts.app')
@section('title', 'संपर्क — SETU Suvidha')
@section('content')
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">संपर्क करा</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-6">
                <p class="text-gray-600 dark:text-gray-400">काही प्रश्न असल्यास आमच्याशी संपर्क साधा. आम्ही तुम्हाला मदत करण्यास तयार आहोत.</p>
                <div class="flex items-center gap-3"><i data-lucide="mail" class="w-5 h-5 text-amber-600"></i><span class="text-gray-700 dark:text-gray-300">support@setusuvidha.com</span></div>
                <div class="flex items-center gap-3"><i data-lucide="phone" class="w-5 h-5 text-amber-600"></i><span class="text-gray-700 dark:text-gray-300">+91 XXXXX XXXXX</span></div>
                <div class="flex items-center gap-3"><i data-lucide="map-pin" class="w-5 h-5 text-amber-600"></i><span class="text-gray-700 dark:text-gray-300">महाराष्ट्र, भारत</span></div>
                <div class="flex items-center gap-3"><i data-lucide="clock" class="w-5 h-5 text-amber-600"></i><span class="text-gray-700 dark:text-gray-300">सोम - शनि: सकाळी 9 ते संध्याकाळी 6</span></div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">संदेश पाठवा</h3>
                <form class="space-y-4">
                    <input type="text" placeholder="तुमचे नाव" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                    <input type="email" placeholder="ईमेल" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                    <textarea rows="4" placeholder="तुमचा संदेश लिहा..." class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></textarea>
                    <button type="button" class="btn-primary w-full">पाठवा</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
