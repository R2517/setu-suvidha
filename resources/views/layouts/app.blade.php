<!DOCTYPE html>
<html lang="mr" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('title', 'SETU Suvidha — महा ई-सेवा पोर्टल')</title>
    <meta name="description" content="@yield('description', 'महाराष्ट्रातील सेतु केंद्र, CSC केंद्र आणि ई-सेवा दुकानदारांसाठी — सर्व सरकारी फॉर्म्स, बिलिंग, वॉलेट आणि ग्राहक व्यवस्थापन एकाच ठिकाणी.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Devanagari:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('meta')
    @stack('styles')
</head>
<body class="font-sans antialiased bg-background text-foreground min-h-screen" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
    {{-- Toast Notification --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition class="fixed top-4 right-4 z-[100] bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition class="fixed top-4 right-4 z-[100] bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-5 h-5"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Navbar --}}
    @include('components.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Floating Billing Button (auth only, not admin) --}}
    @auth
    @if(!request()->is('admin/*'))
    <div x-data="floatingBilling()" class="fixed z-[80]"
         :style="'bottom:' + posY + 'px; right:' + posX + 'px'"
         @mousedown="startDrag($event)" @touchstart.passive="startDrag($event)">
        <button @click="togglePopup()" class="w-14 h-14 rounded-full shadow-2xl flex items-center justify-center text-white transition-all hover:scale-110 active:scale-95"
                style="background: var(--theme-nav, linear-gradient(135deg,#d97706,#f59e0b));">
            <i data-lucide="receipt" class="w-6 h-6"></i>
        </button>
        {{-- Quick Popup --}}
        <div x-show="showPopup" x-transition @click.away="showPopup = false"
             class="absolute bottom-16 right-0 w-56 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                <p class="text-xs font-bold text-gray-900 dark:text-white">Quick Billing</p>
            </div>
            <div class="p-2 space-y-1">
                <a href="{{ route('billing.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-600 transition">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-amber-500"></i> बिलिंग डॅशबोर्ड
                </a>
                <a href="{{ route('billing.sales') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-600 transition">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-green-500"></i> नवीन विक्री
                </a>
                <a href="{{ route('billing.kiosk-book') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition">
                    <i data-lucide="book-open" class="w-4 h-4 text-blue-500"></i> किओस्क बुक
                </a>
                <a href="{{ route('wallet') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 transition">
                    <i data-lucide="wallet" class="w-4 h-4 text-purple-500"></i> वॉलेट
                </a>
            </div>
        </div>
    </div>
    <script>
    function floatingBilling() {
        return {
            showPopup: false,
            posX: 24,
            posY: 24,
            dragging: false,
            dragStartX: 0,
            dragStartY: 0,
            startPosX: 0,
            startPosY: 0,
            hasMoved: false,
            togglePopup() {
                if (!this.hasMoved) this.showPopup = !this.showPopup;
                this.hasMoved = false;
            },
            startDrag(e) {
                this.dragging = true;
                this.hasMoved = false;
                var touch = e.touches ? e.touches[0] : e;
                this.dragStartX = touch.clientX;
                this.dragStartY = touch.clientY;
                this.startPosX = this.posX;
                this.startPosY = this.posY;
                var self = this;
                var onMove = function(ev) {
                    var t = ev.touches ? ev.touches[0] : ev;
                    var dx = self.dragStartX - t.clientX;
                    var dy = self.dragStartY - t.clientY;
                    if (Math.abs(dx) > 3 || Math.abs(dy) > 3) self.hasMoved = true;
                    self.posX = Math.max(8, Math.min(window.innerWidth - 72, self.startPosX + dx));
                    self.posY = Math.max(8, Math.min(window.innerHeight - 72, self.startPosY + dy));
                };
                var onEnd = function() {
                    self.dragging = false;
                    document.removeEventListener('mousemove', onMove);
                    document.removeEventListener('mouseup', onEnd);
                    document.removeEventListener('touchmove', onMove);
                    document.removeEventListener('touchend', onEnd);
                };
                document.addEventListener('mousemove', onMove);
                document.addEventListener('mouseup', onEnd);
                document.addEventListener('touchmove', onMove, { passive: true });
                document.addEventListener('touchend', onEnd);
            }
        }
    }
    </script>
    @endif
    @endauth

    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>
