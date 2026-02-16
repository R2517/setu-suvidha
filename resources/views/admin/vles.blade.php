@extends('layouts.app')
@section('title', 'VLE व्यवस्थापन — Admin')
@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')
    <div class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">VLE व्यवस्थापन</h1>
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">नाव</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">ईमेल</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500">दुकान</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500">वॉलेट</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500">स्थिती</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($vles as $vle)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-3 text-gray-500">{{ $vle->id }}</td>
                            <td class="px-6 py-3 text-gray-900 dark:text-white font-medium">{{ $vle->full_name }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $vle->email }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $vle->shop_name ?? '-' }}</td>
                            <td class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-white">₹{{ number_format($vle->wallet_balance, 2) }}</td>
                            <td class="px-6 py-3 text-center">
                                <form method="POST" action="{{ route('admin.vles.toggle', $vle->id) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium {{ $vle->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $vle->is_active ? 'सक्रिय' : 'निष्क्रिय' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">{{ $vles->links() }}</div>
        </div>
    </div>
</div>
@endsection
