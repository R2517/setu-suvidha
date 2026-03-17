{{-- Service box block: Promote SETU Suvidha services --}}
<div class="my-8 p-6 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-500 text-white">
    <div class="flex flex-col md:flex-row items-center gap-6">
        @if(!empty($block['icon']))
        <div class="flex-shrink-0">
            <div class="w-16 h-16 rounded-xl bg-white/20 flex items-center justify-center">
                <i data-lucide="{{ $block['icon'] }}" class="w-8 h-8 pointer-events-none"></i>
            </div>
        </div>
        @endif
        
        <div class="flex-1 text-center md:text-left">
            @if(!empty($block['title']))
            <h3 class="text-2xl font-black mb-2">{{ $block['title'] }}</h3>
            @if(!empty($block['title_mr']))
            <p class="text-lg font-semibold opacity-90 mb-2">{{ $block['title_mr'] }}</p>
            @endif
            @endif

            @if(!empty($block['description']))
            <p class="opacity-90 mb-4">{{ $block['description'] }}</p>
            @if(!empty($block['description_mr']))
            <p class="text-sm opacity-75">{{ $block['description_mr'] }}</p>
            @endif
            @endif
        </div>

        @if(!empty($block['button_link']))
        <div class="flex-shrink-0">
            <a href="{{ $block['button_link'] }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-orange-600 rounded-xl font-bold hover:scale-105 transition shadow-lg">
                {{ $block['button_text'] ?? 'Learn More' }}
                <i data-lucide="arrow-right" class="w-4 h-4 pointer-events-none"></i>
            </a>
        </div>
        @endif
    </div>
</div>
