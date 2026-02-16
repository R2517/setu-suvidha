<nav class="navbar no-print sticky top-0 z-50 backdrop-blur-xl bg-white/80 dark:bg-gray-950/80 border-b border-gray-200/50 dark:border-gray-800/50"
     x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                    <i data-lucide="landmark" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">SETU Suvidha</span>
                    <span class="hidden sm:block text-[10px] text-gray-500 dark:text-gray-400 -mt-1">सेतू सुविधा — ई-सेवा पोर्टल</span>
                </div>
            </a>

            @guest
            {{-- Guest: Public page links --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">मुख्यपृष्ठ</a>
                <a href="{{ route('services') }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">सेवा</a>
                <a href="{{ route('benefits') }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">फायदे</a>
                <a href="{{ route('farmer-card-public') }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">Farmer ID Card</a>
                <a href="{{ url('/faq') }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">FAQ</a>
                <a href="{{ route('contact') }}" class="px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition">संपर्क</a>
            </div>

            {{-- Guest: Right side --}}
            <div class="flex items-center gap-2">
                <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <i data-lucide="sun" class="w-5 h-5" x-show="darkMode"></i>
                    <i data-lucide="moon" class="w-5 h-5" x-show="!darkMode"></i>
                </button>
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-600 transition">लॉगिन</a>
                <a href="{{ route('register') }}" class="btn-primary text-xs !px-4 !py-2">नोंदणी</a>
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800">
                    <i data-lucide="menu" class="w-5 h-5" x-show="!mobileOpen"></i>
                    <i data-lucide="x" class="w-5 h-5" x-show="mobileOpen"></i>
                </button>
            </div>
            @else
            {{-- Auth: Dashboard controls --}}
            <div class="flex items-center gap-1.5 sm:gap-2">
                {{-- Wallet Badge --}}
                <a href="{{ route('wallet') }}" class="flex items-center gap-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm hover:shadow-md hover:scale-[1.03] transition-all duration-200">
                    <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                    <span>₹{{ number_format(auth()->user()->getWalletBalance(), 2) }}</span>
                </a>
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-600 dark:hover:text-amber-400 transition" title="डॅशबोर्ड">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                </a>
                {{-- Profile --}}
                <a href="{{ route('profile') }}" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition" title="प्रोफाइल">
                    <i data-lucide="user-circle" class="w-5 h-5"></i>
                </a>
                {{-- Admin --}}
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 dark:hover:text-purple-400 transition" title="Admin Panel">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                </a>
                @endif
                {{-- Dark Mode --}}
                <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition" title="डार्क मोड">
                    <i data-lucide="sun" class="w-5 h-5" x-show="darkMode"></i>
                    <i data-lucide="moon" class="w-5 h-5" x-show="!darkMode"></i>
                </button>
                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-500 dark:hover:text-red-400 transition" title="लॉगआउट">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
            @endguest
        </div>
    </div>

    {{-- Mobile Menu (only for guests) --}}
    @guest
    <div x-show="mobileOpen" x-transition class="md:hidden bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800 px-4 py-3 space-y-1">
        <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">मुख्यपृष्ठ</a>
        <a href="{{ route('services') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">सेवा</a>
        <a href="{{ route('benefits') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">फायदे</a>
        <a href="{{ route('farmer-card-public') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">Farmer ID Card</a>
        <a href="{{ url('/faq') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">FAQ</a>
        <a href="{{ route('contact') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">संपर्क</a>
        <a href="{{ route('about') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">आमच्याबद्दल</a>
    </div>
    @endguest
</nav>
