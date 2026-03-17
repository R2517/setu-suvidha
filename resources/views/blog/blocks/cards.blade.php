{{-- Cards block: Feature/benefit grid --}}
@php
    $columns = $block['columns'] ?? 3;
    $gridClasses = [
        2 => 'grid-cols-1 md:grid-cols-2',
        3 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
        4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
    ];
@endphp

<div class="my-8">
    @if(!empty($block['title']))
    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">{{ $block['title'] }}</h3>
    @if(!empty($block['title_mr']))
    <p class="text-lg text-gray-600 dark:text-gray-400 font-semibold -mt-4 mb-6">{{ $block['title_mr'] }}</p>
    @endif
    @endif

    <div class="grid {{ $gridClasses[$columns] ?? $gridClasses[3] }} gap-6">
        @foreach($block['items'] ?? [] as $item)
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6 hover:shadow-lg transition">
            @if(!empty($item['icon']))
            <div class="w-12 h-12 rounded-lg bg-amber-100 dark:bg-amber-900/20 flex items-center justify-center mb-4">
                <i data-lucide="{{ $item['icon'] }}" class="w-6 h-6 text-amber-600 pointer-events-none"></i>
            </div>
            @endif

            @if(!empty($item['title']))
            <h4 class="font-bold text-lg text-gray-900 dark:text-white mb-2">{{ $item['title'] }}</h4>
            @if(!empty($item['title_mr']))
            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-2">{{ $item['title_mr'] }}</p>
            @endif
            @endif

            @if(!empty($item['text']))
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item['text'] }}</p>
            @endif
            
            @if(!empty($item['text_mr']))
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ $item['text_mr'] }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
