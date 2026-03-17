{{-- Documents block: Required documents list --}}
<div class="my-8">
    @if(!empty($block['title']))
    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">{{ $block['title'] }}</h3>
    @if(!empty($block['title_mr']))
    <p class="text-lg text-gray-600 dark:text-gray-400 font-semibold -mt-4 mb-6">{{ $block['title_mr'] }}</p>
    @endif
    @endif

    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
        <div class="space-y-4">
            @foreach($block['items'] ?? [] as $doc)
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 mt-1">
                    <i data-lucide="file-text" class="w-5 h-5 text-blue-600 pointer-events-none"></i>
                </div>
                <div class="flex-1">
                    <div class="font-bold text-gray-900 dark:text-white">
                        {{ $doc['name'] ?? '' }}
                        @if(!empty($doc['name_mr']))
                        <span class="font-normal text-sm text-gray-600 dark:text-gray-400">({{ $doc['name_mr'] }})</span>
                        @endif
                    </div>
                    @if(!empty($doc['note']))
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $doc['note'] }}</p>
                    @if(!empty($doc['note_mr']))
                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $doc['note_mr'] }}</p>
                    @endif
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
