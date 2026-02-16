@extends('layouts.app')
@section('title', 'Reports & Analytics — SETU Suvidha')
@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-950">
    <div class="bg-gradient-to-r from-violet-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold flex items-center gap-2"><i data-lucide="bar-chart-3" class="w-5 h-5"></i> Reports & Analytics</h1>
                    <p class="text-xs text-white/70">Business insights and performance tracking</p>
                </div>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('reports') }}" class="flex items-center gap-2">
                        <input type="month" name="month" value="{{ $month }}" class="px-3 py-1.5 rounded-lg text-xs text-gray-900 border-0 focus:ring-2 focus:ring-white/50">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-white/20 hover:bg-white/30 transition">Go</button>
                    </form>
                    <a href="{{ route('dashboard') }}" class="text-xs bg-white/15 hover:bg-white/25 px-3 py-1.5 rounded-lg transition">&larr; Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center"><i data-lucide="credit-card" class="w-5 h-5 text-indigo-600"></i></div>
                    <div><p class="text-[10px] font-bold text-gray-500 uppercase">PAN Card</p></div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $panTotal }}</div>
                <p class="text-xs text-gray-500">+{{ $panMonth }} this month</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-sky-100 dark:bg-sky-900/30 flex items-center justify-center"><i data-lucide="user-check" class="w-5 h-5 text-sky-600"></i></div>
                    <div><p class="text-[10px] font-bold text-gray-500 uppercase">Voter ID</p></div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $voterTotal }}</div>
                <p class="text-xs text-gray-500">+{{ $voterMonth }} this month</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center"><i data-lucide="hard-hat" class="w-5 h-5 text-teal-600"></i></div>
                    <div><p class="text-[10px] font-bold text-gray-500 uppercase">Bandkam</p></div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $bandkamTotal }}</div>
                <p class="text-xs text-gray-500">+{{ $bandkamMonth }} this month</p>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center"><i data-lucide="file-text" class="w-5 h-5 text-amber-600"></i></div>
                    <div><p class="text-[10px] font-bold text-gray-500 uppercase">Forms</p></div>
                </div>
                <div class="text-2xl font-black text-gray-900 dark:text-white">{{ $formsTotal }}</div>
                <p class="text-xs text-gray-500">+{{ $formsMonth }} this month</p>
            </div>
        </div>

        {{-- Revenue & Pending --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2"><i data-lucide="indian-rupee" class="w-4 h-4 text-green-500"></i> Monthly Revenue</h3>
                <div class="text-3xl font-black text-green-600 mb-4">&#8377;{{ number_format($totalRevenue, 0) }}</div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">PAN Card</span>
                        <span class="font-bold text-gray-900 dark:text-white">&#8377;{{ number_format($panRevenue, 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Voter ID</span>
                        <span class="font-bold text-gray-900 dark:text-white">&#8377;{{ number_format($voterRevenue, 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Bandkam Kamgar</span>
                        <span class="font-bold text-gray-900 dark:text-white">&#8377;{{ number_format($bandkamRevenue, 0) }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2"><i data-lucide="alert-triangle" class="w-4 h-4 text-red-500"></i> Total Pending Amount</h3>
                <div class="text-3xl font-black text-red-600 mb-4">&#8377;{{ number_format($totalPending, 0) }}</div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">PAN Card</span>
                        <span class="font-bold text-red-600">&#8377;{{ number_format($panPending, 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Voter ID</span>
                        <span class="font-bold text-red-600">&#8377;{{ number_format($voterPending, 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Bandkam Kamgar</span>
                        <span class="font-bold text-red-600">&#8377;{{ number_format($bandkamPending, 0) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Breakdown --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            {{-- PAN Status --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">PAN Card — Status Breakdown</h3>
                @php $panStatuses = ['pending' => ['Pending', 'bg-amber-500'], 'processing' => ['Processing', 'bg-blue-500'], 'completed' => ['Completed', 'bg-green-500'], 'rejected' => ['Rejected', 'bg-red-500']]; @endphp
                @foreach($panStatuses as $key => [$label, $color])
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-3 h-3 rounded-full {{ $color }}"></div>
                    <span class="text-sm text-gray-600 flex-1">{{ $label }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $panByStatus[$key] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
            {{-- Voter Status --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Voter ID — Status Breakdown</h3>
                @php $voterStatuses = ['pending' => ['Pending', 'bg-amber-500'], 'processing' => ['Processing', 'bg-blue-500'], 'completed' => ['Completed', 'bg-green-500'], 'rejected' => ['Rejected', 'bg-red-500']]; @endphp
                @foreach($voterStatuses as $key => [$label, $color])
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-3 h-3 rounded-full {{ $color }}"></div>
                    <span class="text-sm text-gray-600 flex-1">{{ $label }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $voterByStatus[$key] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
            {{-- Bandkam Status --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Bandkam — Status Breakdown</h3>
                @php $bStatuses = ['pending' => ['Pending', 'bg-amber-500'], 'activated' => ['Activated', 'bg-green-500'], 'expired' => ['Expired', 'bg-red-500']]; @endphp
                @foreach($bStatuses as $key => [$label, $color])
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-3 h-3 rounded-full {{ $color }}"></div>
                    <span class="text-sm text-gray-600 flex-1">{{ $label }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $bandkamByStatus[$key] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Schemes Breakdown & Monthly Trend --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">Bandkam Schemes by Type</h3>
                @php $schemeLabels = ['safety_kit' => 'Safety Kit', 'essential_kit' => 'Essential Kit', 'scholarship' => 'Scholarship', 'pregnancy' => 'Pregnancy', 'marriage' => 'Marriage', 'death' => 'Death']; @endphp
                @forelse($schemesByType as $type => $count)
                <div class="flex items-center justify-between py-1.5 border-b border-gray-100 dark:border-gray-800 last:border-0">
                    <span class="text-sm text-gray-600">{{ $schemeLabels[$type] ?? ucfirst($type) }}</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $count }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">No schemes yet</p>
                @endforelse
            </div>
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4">6-Month Trend</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                <th class="text-left py-2 text-[10px] font-bold text-gray-500 uppercase">Month</th>
                                <th class="text-center py-2 text-[10px] font-bold text-gray-500 uppercase">PAN</th>
                                <th class="text-center py-2 text-[10px] font-bold text-gray-500 uppercase">Voter</th>
                                <th class="text-center py-2 text-[10px] font-bold text-gray-500 uppercase">Bandkam</th>
                                <th class="text-center py-2 text-[10px] font-bold text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trend as $t)
                            <tr class="border-b border-gray-50 dark:border-gray-800/50">
                                <td class="py-2 text-gray-600">{{ $t['label'] }}</td>
                                <td class="py-2 text-center font-bold text-indigo-600">{{ $t['pan'] }}</td>
                                <td class="py-2 text-center font-bold text-sky-600">{{ $t['voter'] }}</td>
                                <td class="py-2 text-center font-bold text-teal-600">{{ $t['bandkam'] }}</td>
                                <td class="py-2 text-center font-black text-gray-900 dark:text-white">{{ $t['pan'] + $t['voter'] + $t['bandkam'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Export Section --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
            <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i> Export Data (CSV)</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('export.pan-card') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition">
                    <i data-lucide="download" class="w-4 h-4"></i> PAN Card Data
                </a>
                <a href="{{ route('export.voter-id') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-sky-700 bg-sky-50 border border-sky-200 hover:bg-sky-100 transition">
                    <i data-lucide="download" class="w-4 h-4"></i> Voter ID Data
                </a>
                <a href="{{ route('export.bandkam') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition">
                    <i data-lucide="download" class="w-4 h-4"></i> Bandkam Data
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
