{{-- Info box: Alert/notice boxes --}}
@php
    $variant = $block['variant'] ?? 'info';
    $colors = [
        'info' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-100',
        'success' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-900 dark:text-green-100',
        'warning' => 'bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-100',
        'danger' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-900 dark:text-red-100',
    ];
@endphp

<div class="my-6 p-6 rounded-xl border-2 {{ $colors[$variant] ?? $colors['info'] }}">
    @if(!empty($block['title']))
    <h4 class="font-bold text-lg mb-2">{{ $block['title'] }}</h4>
    @if(!empty($block['title_mr']))
    <p class="font-semibold text-sm mb-3 opacity-80">{{ $block['title_mr'] }}</p>
    @endif
    @endif

    @if(!empty($block['content']))
    <div class="prose-sm">{{ $block['content'] }}</div>
    @endif
    
    @if(!empty($block['content_mr']))
    <div class="mt-2 text-sm opacity-75">{{ $block['content_mr'] }}</div>
    @endif
</div>
