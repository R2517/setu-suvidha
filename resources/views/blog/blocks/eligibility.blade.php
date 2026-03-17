{{-- Eligibility block: Criteria checklist --}}
<div class="my-8">
    @if(!empty($block['title']))
    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">{{ $block['title'] }}</h3>
    @if(!empty($block['title_mr']))
    <p class="text-lg text-gray-600 dark:text-gray-400 font-semibold -mt-4 mb-6">{{ $block['title_mr'] }}</p>
    @endif
    @endif

    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800 p-6">
        <ul class="space-y-3">
            @foreach($block['items'] ?? [] as $item)
            <li class="flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5 pointer-events-none"></i>
                <div>
                    <span class="text-gray-900 dark:text-white">{{ $item['text'] ?? '' }}</span>
                    @if(!empty($item['text_mr']))
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $item['text_mr'] }}</p>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
