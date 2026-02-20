<div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ $label }} किंमत सूची</h2>
        <span class="text-xs text-gray-400">{{ $pricing->count() }} forms</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">फॉर्म प्रकार</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">फॉर्म नाव</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">किंमत (₹)</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500">स्थिती</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">कृती</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($pricing as $p)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ !$p->is_active ? 'opacity-50' : '' }}">
                    <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $p->form_type }}</td>
                    <td class="px-6 py-3 text-gray-900 dark:text-white font-medium">{{ $p->form_name }}</td>
                    <td class="px-6 py-3 text-right">
                        <form method="POST" action="{{ route('admin.pricing.update', $p->id) }}" class="inline-flex items-center gap-2">
                            @csrf
                            <input type="number" name="price" value="{{ $p->price }}" step="0.01" min="0" class="w-24 px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right text-sm">
                            <button type="submit" class="p-1 text-amber-600 hover:text-amber-700"><i data-lucide="save" class="w-4 h-4"></i></button>
                        </form>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <form method="POST" action="{{ route('admin.pricing.toggle', $p->id) }}">
                            @csrf
                            <button type="submit" class="px-2 py-0.5 rounded-full text-xs font-medium {{ $p->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">{{ $p->is_active ? 'सक्रिय' : 'निष्क्रिय' }}</button>
                        </form>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <form method="POST" action="{{ route('admin.pricing.destroy', $p->id) }}" onsubmit="return confirm('ही किंमत हटवायची?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-400">अद्याप कोणतीही {{ $label }} किंमत नाही</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
