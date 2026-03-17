{{-- Table of Contents block --}}
<nav class="my-8 p-6 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
        <i data-lucide="list" class="w-5 h-5 text-amber-500 pointer-events-none"></i>
        {{ $block['title'] ?? 'Table of Contents' }}
        @if(!empty($block['title_mr']))
        <span class="text-sm font-normal text-gray-500">({{ $block['title_mr'] }})</span>
        @endif
    </h3>
    
    @if(!empty($block['items']) && is_array($block['items']))
    <ol class="space-y-2 list-decimal list-inside">
        @foreach($block['items'] as $item)
        <li>
            <a href="#{{ $item['id'] ?? Str::slug($item['text'] ?? '') }}" class="text-amber-600 hover:text-amber-700 hover:underline font-medium">
                {{ $item['text'] ?? '' }}
            </a>
            @if(!empty($item['text_mr']))
            <span class="text-sm text-gray-500 ml-1">({{ $item['text_mr'] }})</span>
            @endif
        </li>
        @endforeach
    </ol>
    @endif
</nav>
