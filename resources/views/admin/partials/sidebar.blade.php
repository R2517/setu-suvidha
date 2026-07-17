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
        <a href="{{ route('admin.helpdesk.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.helpdesk.*') ? 'bg-blue-500/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="life-buoy" class="w-4 h-4"></i> Helpdesk
            @php try { $openTickets = \App\Models\HelpdeskTicket::where('status', 'open')->count(); } catch (\Throwable $e) { $openTickets = 0; } @endphp
            @if($openTickets > 0)
            <span class="ml-auto bg-blue-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $openTickets > 99 ? '99+' : $openTickets }}</span>
            @endif
        </a>
        <a href="{{ route('admin.error-logs') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.error-logs') ? 'bg-red-500/20 text-red-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="bug" class="w-4 h-4"></i> Error Logs
            @php try { $unresolvedCount = \App\Models\ErrorLog::where('is_resolved', false)->count(); } catch (\Throwable $e) { $unresolvedCount = 0; } @endphp
            @if($unresolvedCount > 0)
            <span class="ml-auto bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $unresolvedCount > 99 ? '99+' : $unresolvedCount }}</span>
            @endif
        </a>
        <div class="border-t border-gray-800 my-3"></div>
        <p class="px-3 text-[10px] uppercase tracking-wider text-gray-600 mb-1">Blog</p>
        <a href="{{ route('admin.blog.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.blog.index') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="newspaper" class="w-4 h-4"></i> All Posts
        </a>
        <a href="{{ route('admin.blog.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.blog.create') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Create Post
        </a>

        <div class="h-px bg-gray-800 my-2"></div>
        
        <p class="px-3 text-[10px] uppercase tracking-wider text-gray-600 mb-1">Marketing</p>
        <a href="{{ route('admin.ads.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.ads.*') ? 'bg-blue-500/20 text-blue-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="megaphone" class="w-4 h-4"></i> Ads Management
        </a>
        <div class="border-t border-gray-800 my-3"></div>
        <p class="px-3 text-[10px] uppercase tracking-wider text-gray-600 mb-1">Public Services</p>
        <a href="{{ route('admin.farmer-card-orders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.farmer-card-orders') ? 'bg-green-500/20 text-green-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="leaf" class="w-4 h-4"></i> Farmer Card Orders
        </a>
        <a href="{{ route('admin.card-settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm {{ request()->routeIs('admin.card-settings') ? 'bg-amber-500/20 text-amber-400' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} transition">
            <i data-lucide="crop" class="w-4 h-4"></i> Card Cropper Settings
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
