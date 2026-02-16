<div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h3 class="font-bold text-gray-900 dark:text-white">सबमिशन्स ({{ $submissions->count() }})</h3>
    </div>
    @if($submissions->isEmpty())
    <div class="px-6 py-12 text-center text-gray-400">
        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
        <p>अद्याप कोणतेही सबमिशन नाहीत</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">नाव</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">तारीख</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">कृती</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($submissions as $s)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="px-6 py-3 text-gray-500">{{ $s->id }}</td>
                    <td class="px-6 py-3 text-gray-900 dark:text-white font-medium">{{ $s->applicant_name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $s->created_at->format('d M Y, h:i A') }}</td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('forms.print', $s->id) }}" target="_blank" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition" title="प्रिंट"><i data-lucide="printer" class="w-4 h-4"></i></a>
                            <form method="POST" action="{{ route('forms.delete', $s->id) }}" onsubmit="return confirm('हा फॉर्म हटवायचा आहे का?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="हटवा"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
