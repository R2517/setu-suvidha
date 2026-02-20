@php
$currentRoute = request()->route()->getName();
$sidebarItems = [
    ['section' => 'Main'],
    ['route' => 'billing.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard', 'color' => 'emerald'],
    ['route' => 'billing.sales', 'icon' => 'receipt', 'label' => 'Sales', 'color' => 'emerald'],
    ['route' => 'billing.expenses', 'icon' => 'wallet', 'label' => 'Expenses', 'color' => 'red'],
    ['route' => 'billing.reports', 'icon' => 'bar-chart-3', 'label' => 'Reports', 'color' => 'blue'],
    ['section' => 'Books'],
    ['route' => 'billing.daily-book', 'icon' => 'book-open', 'label' => 'Daily Book', 'color' => 'purple'],
    ['route' => 'billing.kiosk-book', 'icon' => 'landmark', 'label' => 'Kiosk Book', 'color' => 'indigo'],
    ['section' => 'Management'],
    ['route' => 'billing.services', 'icon' => 'settings', 'label' => 'Services', 'color' => 'amber'],
    ['route' => 'billing.customers', 'icon' => 'users', 'label' => 'Customers', 'color' => 'sky'],
];
@endphp

<aside class="w-[220px] min-w-[220px] bg-white dark:bg-gray-950 border-r border-gray-200/80 dark:border-gray-800 flex flex-col h-full print:hidden">

    {{-- Sidebar Header --}}
    <div class="px-4 py-4 border-b border-gray-100 dark:border-gray-800">
        <a href="{{ route('billing.dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/20">
                <i data-lucide="indian-rupee" class="w-4 h-4 text-white"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-gray-900 dark:text-white leading-tight">Billing</p>
                <p class="text-[10px] text-gray-400 leading-tight">बिलिंग व्यवस्थापन</p>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-0.5">
        @foreach($sidebarItems as $item)
            @if(isset($item['section']))
                <p class="text-[10px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider px-2.5 pt-3 pb-1.5 {{ !$loop->first ? '' : '' }}">{{ $item['section'] }}</p>
            @else
                @php
                    $isActive = $currentRoute === $item['route'] || ($item['route'] === 'billing.customers' && $currentRoute === 'billing.customer-detail');
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[13px] font-medium transition-all duration-150
                          {{ $isActive
                              ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 shadow-sm'
                              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-900 hover:text-gray-900 dark:hover:text-white' }}">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0
                                {{ $isActive
                                    ? 'bg-emerald-500 text-white shadow shadow-emerald-500/30'
                                    : 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 group-hover:text-gray-600' }}">
                        <i data-lucide="{{ $item['icon'] }}" class="w-3.5 h-3.5"></i>
                    </div>
                    <span>{{ $item['label'] }}</span>
                    @if($isActive)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    {{-- Sidebar Footer --}}
    <div class="px-3 py-3 border-t border-gray-100 dark:border-gray-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[12px] font-medium text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Back to Dashboard</span>
        </a>
    </div>
</aside>
