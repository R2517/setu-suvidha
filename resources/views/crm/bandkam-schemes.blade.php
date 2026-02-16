@extends('layouts.app')
@section('title', 'बांधकाम योजना — SETU Suvidha')
@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showForm: false }">
    <a href="{{ route('bandkam') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6"><i data-lucide="arrow-left" class="w-4 h-4"></i> बांधकाम कामगार</a>

    <div class="bg-gradient-to-br from-amber-600 to-yellow-700 rounded-2xl p-8 text-white mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center"><i data-lucide="gift" class="w-7 h-7"></i></div>
            <div><h1 class="text-2xl font-bold">बांधकाम कामगार योजना</h1><p class="text-white/80 text-sm mt-1">शिक्षण, विवाह, प्रसूती, अपघात, गृहनिर्माण</p></div>
        </div>
        <button @click="showForm = !showForm" class="bg-white text-amber-600 font-semibold px-6 py-3 rounded-xl hover:bg-amber-50 transition flex items-center gap-2"><i data-lucide="plus" class="w-5 h-5"></i> नवीन अर्ज</button>
    </div>

    <div x-show="showForm" x-transition class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 mb-8">
        <form method="POST" action="{{ route('bandkam.schemes.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">कामगाराचे नाव *</label><input type="text" name="applicant_name" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">विद्यार्थ्याचे नाव</label><input type="text" name="student_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">योजना प्रकार *</label>
                    <select name="scheme_type" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                        <option value="">निवडा</option>
                        <option value="education">शिक्षण शिष्यवृत्ती</option>
                        <option value="marriage">विवाह सहाय्य</option>
                        <option value="maternity">प्रसूती सहाय्य</option>
                        <option value="accident">अपघात विमा</option>
                        <option value="housing">गृहनिर्माण सहाय्य</option>
                        <option value="other">इतर</option>
                    </select></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">लाभार्थी नाव</label><input type="text" name="beneficiary_name" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
                <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">रक्कम (₹)</label><input type="number" name="amount" step="0.01" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></div>
            </div>
            <div class="flex gap-3"><button type="submit" class="btn-primary">💾 सेव्ह करा</button><button type="button" @click="showForm = false" class="px-6 py-3 text-sm text-gray-500 hover:text-gray-700">रद्द करा</button></div>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        @if($schemes->isEmpty())
        <div class="px-6 py-12 text-center text-gray-400"><i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i><p>अद्याप कोणतेही योजना अर्ज नाहीत</p></div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">कामगार</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">योजना</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">लाभार्थी</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">रक्कम</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">स्थिती</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">तारीख</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($schemes as $s)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3 text-gray-500">{{ $s->id }}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">{{ $s->applicant_name }}</td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $s->scheme_type }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $s->beneficiary_name ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">₹{{ number_format($s->amount, 2) }}</td>
                        <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $s->status === 'approved' ? 'bg-green-100 text-green-700' : ($s->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $s->status }}</span></td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $s->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $schemes->links() }}</div>
        @endif
    </div>
</div>
@endsection
