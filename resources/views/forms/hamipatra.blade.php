@extends('layouts.app')
@section('title', 'हमीपत्र — SETU Suvidha')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ showForm: false }">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा
    </a>

    {{-- Hero Card --}}
    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-8 text-white mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center">
                <i data-lucide="file-text" class="w-7 h-7"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold">हमीपत्र (Disclaimer)</h1>
                <p class="text-white/80 text-sm mt-1">जामीनदार हमीपत्र — 7 फील्ड्स, A4 प्रिंट | शुल्क: ₹{{ $pricing->price ?? '0' }}</p>
            </div>
        </div>
        <button @click="showForm = !showForm" class="bg-white text-blue-600 font-semibold px-6 py-3 rounded-xl hover:bg-blue-50 transition flex items-center gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i> फॉर्म भरा
        </button>
    </div>

    {{-- Form --}}
    <div x-show="showForm" x-transition class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 mb-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">हमीपत्र फॉर्म भरा</h3>
        <form method="POST" action="/hamipatra" class="space-y-5" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">अर्जदाराचे पूर्ण नाव *</label>
                    <input type="text" name="applicant_name" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">वय *</label>
                    <input type="number" name="age" min="1" max="120" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">व्यवसाय *</label>
                    <input type="text" name="occupation" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">मोबाईल नंबर</label>
                    <input type="text" name="mobile" maxlength="15" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">पत्ता *</label>
                    <textarea name="address" rows="2" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">जामीनदार नाव *</label>
                    <input type="text" name="guarantor_name" required class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">तारीख</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 transition">
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary" :disabled="submitting">
                    <span x-show="!submitting">💾 सेव्ह करा</span>
                    <span x-show="submitting">सेव्ह होत आहे...</span>
                </button>
                <button type="button" @click="showForm = false" class="px-6 py-3 text-sm text-gray-500 hover:text-gray-700 transition">रद्द करा</button>
            </div>
        </form>
    </div>

    {{-- Submissions Table --}}
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
                                <a href="{{ route('forms.print', $s->id) }}" target="_blank" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition" title="प्रिंट">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </a>
                                <form method="POST" action="{{ route('forms.delete', $s->id) }}" onsubmit="return confirm('हा फॉर्म हटवायचा आहे का?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="हटवा">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
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
</div>
@endsection
