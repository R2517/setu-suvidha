@extends('layouts.app')
@section('title', 'प्लॅन्स — Admin')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">सबस्क्रिप्शन प्लॅन्स</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($plans as $plan)
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $plan->name }}</h3>
                <p class="text-2xl font-bold text-amber-600 mb-3">₹{{ number_format($plan->price, 2) }} <span class="text-sm text-gray-500 font-normal">/ {{ $plan->duration_days }} दिवस</span></p>
                <ul class="space-y-1 mb-4">
                    @foreach(is_array($plan->features) ? $plan->features : (is_string($plan->features) ? json_decode($plan->features, true) ?? [] : []) as $f)
                    <li class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-1"><i data-lucide="check" class="w-3 h-3 text-green-500"></i> {{ $f }}</li>
                    @endforeach
                </ul>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('admin.plans.destroy', $plan->id) }}" onsubmit="return confirm('हा प्लॅन हटवायचा?')">@csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700">हटवा</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
