@props(['slotName', 'class' => ''])

@php
    // We fetch the ad from DB. In production, this should be cached!
    $ad = \Illuminate\Support\Facades\Cache::remember('ad_slot_' . $slotName, 3600, function () use ($slotName) {
        return \App\Models\AdSetting::where('slot_name', $slotName)->where('is_active', true)->first();
    });
@endphp

@if($ad)
    <div class="ad-container relative bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700 p-1 flex items-center justify-center min-h-[100px] {{ $class }}">
        <span class="absolute top-1 right-2 text-[9px] uppercase tracking-widest text-gray-400 bg-white/80 dark:bg-gray-900/80 px-1 rounded backdrop-blur">Advertisement</span>
        @if($ad->type === 'image' && $ad->content)
            @if($ad->target_url)
                <a href="{{ $ad->target_url }}" target="_blank" rel="noopener noreferrer" class="block w-full h-full">
                    <img src="{{ asset($ad->content) }}" alt="Advertisement" class="w-full h-full object-cover rounded-lg hover:opacity-90 transition">
                </a>
            @else
                <div class="block w-full h-full">
                    <img src="{{ asset($ad->content) }}" alt="Advertisement" class="w-full h-full object-cover rounded-lg">
                </div>
            @endif
        @elseif($ad->type === 'script' && $ad->content)
            <div class="w-full h-full overflow-hidden flex justify-center items-center">
                {!! $ad->content !!}
            </div>
        @endif
    </div>
@endif
