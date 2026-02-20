@extends('layouts.app')
@section('title', 'VLE व्यवस्थापन — Admin')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-6 lg:p-8 overflow-x-hidden">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">VLE व्यवस्थापन</h1>
            <span class="text-xs text-gray-400">{{ $vles->total() }} VLEs</span>
        </div>

        @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-sm text-green-700 dark:text-green-400">{{ session('success') }}</div>
        @endif

        {{-- Search & Filters --}}
        <form method="GET" action="{{ route('admin.vles') }}" class="mb-5 flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="नाव, ईमेल, दुकान, मोबाईल शोधा..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
            </div>
            <select name="district" class="px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                <option value="">सर्व जिल्हे</option>
                @foreach($districts as $d)
                <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white">
                <option value="">सर्व स्थिती</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>सक्रिय</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>निष्क्रिय</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition">शोधा</button>
            @if(request()->hasAny(['search', 'district', 'status']))
            <a href="{{ route('admin.vles') }}" class="text-xs text-gray-500 hover:text-gray-700">Reset</a>
            @endif
        </form>

        {{-- VLE Table --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">नाव</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">दुकान</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">जिल्हा</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">वॉलेट</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">स्थिती</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">कृती</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($vles as $vle)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 {{ !$vle->is_active ? 'opacity-50' : '' }}">
                            <td class="px-4 py-3 text-gray-400 text-xs">{{ $vle->id }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.vles.show', $vle->id) }}" class="hover:text-amber-600 transition">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $vle->full_name }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $vle->email }} {{ $vle->mobile ? '· ' . $vle->mobile : '' }}</p>
                                </a>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $vle->shop_name ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $vle->district ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="font-semibold {{ $vle->wallet_balance < 30 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">₹{{ number_format($vle->wallet_balance, 2) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="{{ route('admin.vles.toggle', $vle->id) }}">@csrf
                                    <button type="submit" class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $vle->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">{{ $vle->is_active ? 'सक्रिय' : 'निष्क्रिय' }}</button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('admin.vles.show', $vle->id) }}" class="text-xs text-amber-600 hover:text-amber-700 font-medium">View →</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">कोणतेही VLEs सापडले नाहीत</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $vles->links() }}</div>
        </div>
    </div>
</div>
@endsection
