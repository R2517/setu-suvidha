{{-- Table block: Data tables --}}
<div class="my-8 overflow-x-auto">
    @if(!empty($block['title']))
    <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">{{ $block['title'] }}</h3>
    @if(!empty($block['title_mr']))
    <p class="text-lg text-gray-600 dark:text-gray-400 font-semibold -mt-4 mb-6">{{ $block['title_mr'] }}</p>
    @endif
    @endif

    <table class="w-full border-collapse bg-white dark:bg-gray-900 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-800">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                @foreach($block['columns'] ?? [] as $col)
                <th class="px-4 py-3 text-left text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700">
                    {{ $col }}
                </th>
                @endforeach
            </tr>
            @if(!empty($block['columns_mr']))
            <tr class="bg-gray-100 dark:bg-gray-800/50">
                @foreach($block['columns_mr'] ?? [] as $colMr)
                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                    {{ $colMr }}
                </th>
                @endforeach
            </tr>
            @endif
        </thead>
        <tbody>
            @foreach($block['rows'] ?? [] as $rowIndex => $row)
            <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/30">
                @foreach($row as $cell)
                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $cell }}</td>
                @endforeach
            </tr>
            @if(!empty($block['rows_mr'][$rowIndex]))
            <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                @foreach($block['rows_mr'][$rowIndex] as $cellMr)
                <td class="px-4 py-2 text-xs text-gray-600 dark:text-gray-400">{{ $cellMr }}</td>
                @endforeach
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>
