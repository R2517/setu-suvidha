<aside class="w-64 bg-gray-900 text-white min-h-screen flex-shrink-0 no-print">
    <div class="p-6 border-b border-gray-800">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center"><i data-lucide="shield" class="w-4 h-4 text-white"></i></div>
            <span class="font-bold">Admin Panel</span>
        </div>
    </div>
    <nav class="p-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> डॅशबोर्ड
        </a>
        <a href="{{ route('admin.vles') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.vles') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="users" class="w-4 h-4"></i> VLE व्यवस्थापन
        </a>
        <a href="{{ route('admin.pricing') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.pricing') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="indian-rupee" class="w-4 h-4"></i> किंमत व्यवस्थापन
        </a>
        <a href="{{ route('admin.plans') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.plans') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="package" class="w-4 h-4"></i> प्लॅन्स
        </a>
        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.transactions') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="history" class="w-4 h-4"></i> व्यवहार लॉग
        </a>
        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.settings') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="settings" class="w-4 h-4"></i> सेटिंग्ज
        </a>
        <div class="border-t border-gray-800 my-3"></div>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition">
            <i data-lucide="layout-grid" class="w-4 h-4"></i> VLE डॅशबोर्ड
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-400 hover:text-red-400 hover:bg-gray-800 transition">
                <i data-lucide="log-out" class="w-4 h-4"></i> लॉगआउट
            </button>
        </form>
    </nav>
</aside>
