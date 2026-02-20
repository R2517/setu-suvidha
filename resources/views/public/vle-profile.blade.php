@extends('layouts.app')
@section('title', $profile->full_name . ' — VLE सेवा केंद्र — SETU Suvidha')
@section('description', ($profile->about_center ?? $profile->shop_name ?? 'VLE सेवा केंद्र') . ' — ' . ($profile->district ?? 'Maharashtra'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="{{ route('vle.directory') }}" class="hover:text-amber-600 transition">VLE Directory</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-gray-700 dark:text-gray-300">{{ $profile->full_name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Profile Card --}}
        <div class="lg:col-span-1 space-y-5">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 text-center">
                <div class="w-24 h-24 rounded-2xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mx-auto mb-4">
                    @if($profile->profile_pic)
                    <img src="{{ asset('storage/' . $profile->profile_pic) }}" class="w-24 h-24 rounded-2xl object-cover">
                    @elseif($profile->logo_url)
                    <img src="{{ asset($profile->logo_url) }}" class="w-24 h-24 rounded-2xl object-cover">
                    @else
                    <i data-lucide="user" class="w-10 h-10 text-amber-600"></i>
                    @endif
                </div>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $profile->full_name }}</h1>
                @if($profile->full_name_mr)
                <p class="text-sm text-gray-500">{{ $profile->full_name_mr }}</p>
                @endif
                @if($profile->shop_name)
                <p class="text-xs text-gray-400 mt-1">{{ $profile->shop_name }}</p>
                @endif

                @if($profile->shop_type)
                <span class="inline-block mt-3 text-[10px] font-bold text-amber-600 bg-amber-50 dark:bg-amber-900/20 px-3 py-1 rounded-full uppercase">{{ $profile->shop_type }} केंद्र</span>
                @endif
            </div>

            {{-- Contact --}}
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 space-y-3">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">संपर्क</h3>
                @if($profile->mobile)
                <a href="tel:{{ $profile->mobile }}" class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300 hover:text-amber-600 transition">
                    <i data-lucide="phone" class="w-4 h-4 text-green-500"></i> {{ $profile->mobile }}
                </a>
                @endif
                @if($profile->whatsapp_number)
                <a href="https://wa.me/91{{ $profile->whatsapp_number }}" target="_blank" class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300 hover:text-green-600 transition">
                    <i data-lucide="message-circle" class="w-4 h-4 text-green-500"></i> WhatsApp
                </a>
                @endif
                @if($profile->email)
                <a href="mailto:{{ $profile->email }}" class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300 hover:text-amber-600 transition">
                    <i data-lucide="mail" class="w-4 h-4 text-blue-500"></i> {{ $profile->email }}
                </a>
                @endif
                @if($profile->google_map_link)
                <a href="{{ $profile->google_map_link }}" target="_blank" class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-300 hover:text-amber-600 transition">
                    <i data-lucide="map-pin" class="w-4 h-4 text-red-500"></i> Google Maps वर पहा
                </a>
                @endif
            </div>

            {{-- Location --}}
            @if($profile->district || $profile->address)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 space-y-2">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">स्थान</h3>
                @if($profile->address)
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $profile->address }}</p>
                @endif
                @if($profile->district)
                <p class="text-xs text-gray-500">{{ $profile->taluka ? $profile->taluka . ', ' : '' }}{{ $profile->district }}</p>
                @endif
            </div>
            @endif
        </div>

        {{-- Right: Details --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- About --}}
            @if($profile->about_center)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-amber-500"></i> केंद्राबद्दल
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $profile->about_center }}</p>
            </div>
            @endif

            {{-- Working Hours --}}
            @if($profile->working_hours)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-blue-500"></i> कामाचे तास
                    @if($profile->holiday_mode)
                    <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">सुट्टी</span>
                    @endif
                </h2>
                @php $days = ['सोमवार', 'मंगळवार', 'बुधवार', 'गुरुवार', 'शुक्रवार', 'शनिवार', 'रविवार']; @endphp
                <div class="space-y-2">
                    @foreach($profile->working_hours as $idx => $day)
                    <div class="flex items-center justify-between py-1.5 {{ !$loop->last ? 'border-b border-gray-50 dark:border-gray-800' : '' }}">
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $days[$idx] ?? '' }}</span>
                        @if($day['is_open'] ?? false)
                        <span class="text-xs text-green-600 font-medium">{{ $day['start'] ?? '09:00' }} — {{ $day['end'] ?? '20:00' }}</span>
                        @else
                        <span class="text-xs text-red-400">बंद</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Shop Photo --}}
            @if($profile->shop_pic)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <img src="{{ asset('storage/' . $profile->shop_pic) }}" alt="{{ $profile->shop_name }}" class="w-full h-64 object-cover">
            </div>
            @endif

            {{-- UPI / QR Code --}}
            @if($profile->upi_id || $profile->qr_code_pic)
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4 text-indigo-500"></i> Payment
                </h2>
                @if($profile->upi_id)
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">UPI: <span class="font-mono font-bold text-gray-900 dark:text-white">{{ $profile->upi_id }}</span></p>
                @endif
                @if($profile->qr_code_pic)
                <img src="{{ asset('storage/' . $profile->qr_code_pic) }}" alt="QR Code" class="w-40 h-40 rounded-xl border border-gray-200">
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
