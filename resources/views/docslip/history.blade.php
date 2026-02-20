@extends('layouts.app')
@section('title', 'DocSlip इतिहास — SETU Suvidha')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950">

    {{-- Page Header --}}
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('docslip.index') }}" class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <i data-lucide="arrow-left" class="w-4 h-4 text-gray-600 dark:text-gray-400"></i>
                    </a>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">प्रिंट इतिहास</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">DocSlip — मागील प्रिंट्सची यादी</p>
                    </div>
                </div>
                {{-- Search --}}
                <form method="GET" class="flex items-center gap-2">
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="नाव / मोबाईल शोधा..."
                            class="pl-9 pr-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 w-56">
                    </div>
                    <button type="submit" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold transition">शोधा</button>
                    @if(request('search'))
                    <a href="{{ route('docslip.history') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg text-xs font-medium transition hover:bg-gray-300">Clear</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        @if($prints->count() > 0)
        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center">
                <p class="text-2xl font-extrabold text-purple-600">{{ $prints->total() }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">एकूण प्रिंट्स</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center">
                <p class="text-2xl font-extrabold text-orange-600">{{ $prints->where('created_at', '>=', now()->startOfDay())->count() }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">आज</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center">
                <p class="text-2xl font-extrabold text-green-600">{{ $prints->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">या आठवड्यात</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center">
                <p class="text-2xl font-extrabold text-blue-600">{{ $prints->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">या महिन्यात</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            {{-- Desktop Table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">#</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">तारीख</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">ग्राहक</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">सेवा</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">कागदपत्रे</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">टीप</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($prints as $print)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-4 py-3 text-xs text-gray-400">{{ $print->id }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-gray-700 dark:text-gray-300">{{ $print->created_at->format('d/m/Y') }}</span>
                                <span class="text-[10px] text-gray-400 block">{{ $print->created_at->format('h:i A') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($print->customer_name || $print->customer_mobile)
                                <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $print->customer_name ?: '—' }}</span>
                                @if($print->customer_mobile)
                                <span class="text-[10px] text-gray-400 block">{{ $print->customer_mobile }}</span>
                                @endif
                                @else
                                <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(($print->services_selected ?? []) as $s)
                                    <span class="text-[10px] bg-orange-50 dark:bg-orange-900/20 text-orange-600 px-2 py-0.5 rounded-full">{{ $s['name'] ?? '—' }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-bold text-green-600">{{ count($print->documents_merged ?? []) }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 max-w-[200px] truncate">{{ $print->remark ?: '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Cards --}}
            <div class="sm:hidden divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($prints as $print)
                <div class="px-4 py-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-gray-400">{{ $print->created_at->format('d/m/Y h:i A') }}</span>
                        <span class="text-xs font-bold text-green-600">{{ count($print->documents_merged ?? []) }} docs</span>
                    </div>
                    @if($print->customer_name || $print->customer_mobile)
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $print->customer_name }} <span class="text-xs text-gray-400">{{ $print->customer_mobile }}</span></p>
                    @endif
                    <div class="flex flex-wrap gap-1 mt-1.5">
                        @foreach(($print->services_selected ?? []) as $s)
                        <span class="text-[10px] bg-orange-50 dark:bg-orange-900/20 text-orange-600 px-2 py-0.5 rounded-full">{{ $s['name'] ?? '—' }}</span>
                        @endforeach
                    </div>
                    @if($print->remark)
                    <p class="text-[11px] text-gray-500 mt-1 italic">{{ $print->remark }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $prints->links() }}
        </div>

        @else
        {{-- Empty State --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-10 text-center">
            <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="printer" class="w-8 h-8 text-gray-300 dark:text-gray-600"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">अजून कोणतेही प्रिंट्स नाहीत</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">DocSlip मधून प्रिंट केल्यानंतर इतिहास येथे दिसेल.</p>
            <a href="{{ route('docslip.index') }}" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold px-5 py-2.5 rounded-xl transition text-sm">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i> DocSlip उघडा
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
