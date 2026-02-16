@extends('layouts.app')
@section('title', 'नोंदणी — SETU Suvidha')

@section('content')
<div class="min-h-screen flex">
    {{-- Left Panel — Branding --}}
    <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-amber-600 via-orange-600 to-amber-700 text-white flex-col justify-center items-center p-12 overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="absolute top-20 left-20 w-32 h-32 bg-white/5 rounded-full animate-pulse"></div>
        <div class="absolute bottom-32 right-16 w-48 h-48 bg-white/5 rounded-full animate-pulse" style="animation-delay: 1s;"></div>

        <div class="relative z-10 text-center max-w-md">
            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="landmark" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">SETU Suvidha</h1>
            <p class="text-white/80 mb-10">तुमचं सरकारी कामकाज सोपं करा — आजच मोफत नोंदणी करा!</p>

            <div class="space-y-4 text-left">
                <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3">
                    <i data-lucide="zap" class="w-5 h-5 text-amber-200"></i>
                    <span class="text-sm">तात्काळ फॉर्म प्रिंट, जलद सेवा</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3">
                    <i data-lucide="wallet" class="w-5 h-5 text-amber-200"></i>
                    <span class="text-sm">प्रीपेड वॉलेट — ₹2 पासून सुरुवात</span>
                </div>
                <div class="flex items-center gap-3 bg-white/10 rounded-xl px-4 py-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-200"></i>
                    <span class="text-sm">100% सुरक्षित, SSL एन्क्रिप्टेड</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Panel — Form --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center px-6 py-12 bg-white dark:bg-gray-950">
        <div class="w-full max-w-md">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-8">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> मुख्यपृष्ठावर जा
            </a>

            <div class="lg:hidden flex items-center gap-2 mb-6">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                    <i data-lucide="landmark" class="w-5 h-5 text-white"></i>
                </div>
                <span class="text-lg font-bold text-gray-900 dark:text-white">SETU Suvidha</span>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">मोफत नोंदणी करा</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-8 text-sm">नवीन खातं तयार करा आणि सेवा वापरायला सुरुवात करा</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पूर्ण नाव</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ईमेल</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ showPass: false }">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पासवर्ड</label>
                    <div class="relative">
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition pr-12">
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i data-lucide="eye" class="w-5 h-5" x-show="!showPass"></i>
                            <i data-lucide="eye-off" class="w-5 h-5" x-show="showPass"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पासवर्ड पुन्हा टाका</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition">
                </div>

                <button type="submit" class="w-full btn-primary !py-3.5 text-base">नोंदणी करा</button>
            </form>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                आधीच खातं आहे? <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold">लॉगिन करा</a>
            </p>
        </div>
    </div>
</div>
@endsection
