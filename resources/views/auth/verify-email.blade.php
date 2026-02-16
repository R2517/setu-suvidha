@extends('layouts.app')
@section('title', 'ईमेल सत्यापन — SETU Suvidha')
@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12 bg-gray-50 dark:bg-gray-950">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-800 p-8">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                    <i data-lucide="mail" class="w-5 h-5 text-white"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">ईमेल सत्यापन</h2>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">नोंदणीसाठी धन्यवाद! कृपया आम्ही तुम्हाला पाठवलेल्या लिंकवर क्लिक करून तुमचा ईमेल पत्ता सत्यापित करा.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 p-3 rounded-xl bg-green-50 text-green-700 text-sm">नवीन सत्यापन लिंक तुमच्या ईमेलवर पाठवली गेली आहे.</div>
            @endif

            <div class="flex items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-primary !py-2.5">पुन्हा लिंक पाठवा</button>
                </form>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-amber-600 underline">लॉग आउट</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
