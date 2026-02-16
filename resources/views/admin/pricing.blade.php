@extends('layouts.app')
@section('title', 'किंमत व्यवस्थापन — Admin')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">किंमत व्यवस्थापन</h1>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
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
                        @foreach($pricing as $p)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-3 text-gray-500 font-mono text-xs">{{ $p->form_type }}</td>
                            <td class="px-6 py-3 text-gray-900 dark:text-white font-medium">{{ $p->form_name }}</td>
                            <td class="px-6 py-3 text-right">
                                <form method="POST" action="{{ route('admin.pricing.update', $p->id) }}" class="inline-flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="price" value="{{ $p->price }}" step="0.01" min="0" class="w-20 px-2 py-1 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-right text-sm">
                                    <button type="submit" class="p-1 text-amber-600 hover:text-amber-700"><i data-lucide="save" class="w-4 h-4"></i></button>
                                </form>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <form method="POST" action="{{ route('admin.pricing.toggle', $p->id) }}">
                                    @csrf
                                    <button type="submit" class="px-2 py-0.5 rounded-full text-xs font-medium {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $p->is_active ? 'सक्रिय' : 'निष्क्रिय' }}</button>
                                </form>
                            </td>
                            <td class="px-6 py-3"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
