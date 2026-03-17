{{-- Hero block: Page banner/intro section --}}
<div class="my-8 p-8 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800">
    @if(!empty($block['badge']))
    <div class="mb-4">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500 text-white text-sm font-bold">
            @if(!empty($block['badge_icon']))
            <i data-lucide="{{ $block['badge_icon'] }}" class="w-4 h-4 pointer-events-none"></i>
            @endif
            {{ $block['badge'] }}
        </span>
    </div>
    @endif

    @if(!empty($block['title']))
    <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-3">{{ $block['title'] }}</h2>
    @if(!empty($block['title_mr']))
    <p class="text-xl text-gray-700 dark:text-gray-300 font-semibold mb-4">{{ $block['title_mr'] }}</p>
    @endif
    @endif

    @if(!empty($block['subtitle']))
    <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">{{ $block['subtitle'] }}</p>
    @if(!empty($block['subtitle_mr']))
    <p class="text-base text-gray-600 dark:text-gray-400">{{ $block['subtitle_mr'] }}</p>
    @endif
    @endif

    @if(!empty($block['highlights']) && is_array($block['highlights']))
    <div class="flex flex-wrap gap-3 mt-4">
        @foreach($block['highlights'] as $highlight)
        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm">
            @if(!empty($highlight['icon']))
            <i data-lucide="{{ $highlight['icon'] }}" class="w-4 h-4 text-amber-500 pointer-events-none"></i>
            @endif
            {{ $highlight['text'] ?? $highlight }}
        </span>
        @endforeach
    </div>
    @endif
</div>
