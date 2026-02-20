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

    {{-- Floating Billing Button + Modals (auth only, not admin) --}}
    @auth
    @if(!request()->is('admin/*'))
    <div x-data="floatingBilling()" class="fixed z-[80]"
         :style="'bottom:' + posY + 'px; right:' + posX + 'px'"
         @mousedown="startDrag($event)" @touchstart.passive="startDrag($event)">
        <button @click="togglePopup()" class="w-14 h-14 rounded-full shadow-2xl flex items-center justify-center text-white transition-all hover:scale-110 active:scale-95"
                style="background: var(--theme-nav, linear-gradient(135deg,#d97706,#f59e0b));">
            <i data-lucide="receipt" class="w-6 h-6"></i>
        </button>
        {{-- Quick Popup Menu --}}
        <div x-show="showPopup" x-transition @click.away="showPopup = false"
             class="absolute bottom-16 right-0 w-56 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800">
                <p class="text-xs font-bold text-gray-900 dark:text-white">Quick Billing</p>
            </div>
            <div class="p-2 space-y-1">
                <button @click="showPopup=false; openSaleModal()" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-600 transition">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-green-500"></i> नवीन विक्री (Sale)
                </button>
                <button @click="showPopup=false; showExpModal=true" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 transition">
                    <i data-lucide="minus-circle" class="w-4 h-4 text-red-500"></i> खर्च (Expense)
                </button>
                <button @click="showPopup=false; showKskModal=true" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-600 transition">
                    <i data-lucide="landmark" class="w-4 h-4 text-purple-500"></i> किओस्क (Kiosk)
                </button>
                <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                <a href="{{ route('billing.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-amber-50 dark:hover:bg-amber-900/20 hover:text-amber-600 transition">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-amber-500"></i> बिलिंग डॅशबोर्ड
                </a>
                <a href="{{ route('wallet') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 transition">
                    <i data-lucide="wallet" class="w-4 h-4 text-blue-500"></i> वॉलेट
                </a>
            </div>
        </div>

        {{-- ═══ SALE MODAL ═══ --}}
        <div x-show="showSlModal" x-transition.opacity class="fixed inset-0 z-[90] flex items-center justify-center p-4" style="display:none" @click.self="showSlModal=false">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-lg shadow-2xl border border-gray-200 dark:border-gray-800 max-h-[90vh] overflow-y-auto" @click.stop>
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 sticky top-0 bg-white dark:bg-gray-900 rounded-t-2xl z-10 flex items-center justify-between">
                    <h2 class="text-lg font-bold flex items-center gap-2"><i data-lucide="plus-circle" class="w-5 h-5 text-emerald-500"></i> Quick Sale</h2>
                    <button @click="showSlModal=false" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <input x-model="sl.customer_name" type="text" placeholder="Customer Name" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <input x-model="sl.customer_phone" type="text" placeholder="Mobile" maxlength="10" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Services</label>
                        <button type="button" @click="sl.items.push({service_name:'',quantity:1,unit_price:0,cost_price:0})" class="text-xs text-emerald-500 font-bold">+ Add</button>
                    </div>
                    <template x-for="(item, i) in sl.items" :key="i">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex-1 relative" x-data="{ open: false, q: item.service_name }">
                                <input type="text" x-model="q" @focus="open=true" @click.away="open=false" @input="open=true; item.service_name=q"
                                    placeholder="Search service..." class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                                <div x-show="open && q.length >= 1" class="absolute z-[95] left-0 right-0 top-full mt-1 bg-white dark:bg-gray-900 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 max-h-40 overflow-y-auto">
                                    <template x-for="svc in svcList.filter(s => s.name.toLowerCase().includes(q.toLowerCase()) || q === '')" :key="svc.id">
                                        <button type="button" @click="q=svc.name; item.service_name=svc.name; item.unit_price=svc.default_price; item.cost_price=svc.cost_price; open=false"
                                            class="block w-full text-left px-3 py-2 text-xs hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition">
                                            <span class="font-medium text-gray-900 dark:text-white" x-text="svc.name"></span>
                                            <span class="text-gray-400 ml-1" x-text="'₹' + svc.default_price"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <input x-model.number="item.quantity" type="number" min="1" class="w-14 px-2 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-center">
                            <input x-model.number="item.unit_price" type="number" min="0" class="w-20 px-2 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                            <span class="text-xs font-bold text-gray-600 w-16 text-right" x-text="'₹'+(item.quantity*item.unit_price)"></span>
                            <button type="button" @click="sl.items.splice(i,1)" x-show="sl.items.length>1" class="p-1 text-red-400"><i data-lucide="x" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </template>
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-3 mb-4 space-y-1.5 mt-3">
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal:</span><span class="font-bold" x-text="'₹'+slSub()"></span></div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Discount:</span>
                            <input x-model.number="sl.discount_amount" type="number" min="0" class="w-20 px-2 py-1 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm text-right">
                        </div>
                        <div class="flex justify-between text-sm font-bold border-t border-gray-200 dark:border-gray-700 pt-1.5"><span>Total:</span><span class="text-emerald-600" x-text="'₹'+slTotal()"></span></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <template x-for="mode in ['cash','online','split']" :key="mode">
                            <button type="button" @click="sl.payment_mode=mode" :class="sl.payment_mode===mode?'bg-emerald-500 text-white ring-2 ring-emerald-500/30':'bg-gray-100 dark:bg-gray-800 text-gray-600'" class="px-3 py-2 rounded-xl text-sm font-bold transition capitalize" x-text="mode"></button>
                        </template>
                    </div>
                    <div x-show="sl.payment_mode==='split'" class="grid grid-cols-2 gap-3 mb-3">
                        <div><label class="text-[10px] text-gray-500">Cash</label><input x-model.number="sl.cash_amount" type="number" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
                        <div><label class="text-[10px] text-gray-500">Online</label><input x-model.number="sl.online_amount" type="number" class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm"></div>
                    </div>
                    <div class="mb-3"><label class="text-[10px] text-gray-500">Received Amount</label>
                        <input x-model.number="sl.received_amount" type="number" min="0" :placeholder="slTotal()" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                    <div class="flex gap-3">
                        <button @click="submitSl()" :disabled="slBusy" class="flex-1 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold transition disabled:opacity-50">
                            <span x-show="!slBusy">Save Sale</span><span x-show="slBusy">Saving...</span>
                        </button>
                        <button @click="showSlModal=false" class="px-5 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl font-medium">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ EXPENSE MODAL ═══ --}}
        <div x-show="showExpModal" x-transition.opacity class="fixed inset-0 z-[90] flex items-center justify-center p-4" style="display:none" @click.self="showExpModal=false">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md shadow-2xl border border-gray-200 dark:border-gray-800" @click.stop>
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h2 class="text-lg font-bold flex items-center gap-2"><i data-lucide="minus-circle" class="w-5 h-5 text-red-500"></i> Quick Expense</h2>
                    <button @click="showExpModal=false" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
                <div class="p-6 space-y-3">
                    <select x-model="exp.category" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <option value="">Category *</option>
                        <option value="Rent">Rent</option><option value="Salary">Salary</option><option value="Electricity">Electricity</option>
                        <option value="Internet">Internet</option><option value="Phone">Phone</option><option value="Travel">Travel</option>
                        <option value="Food">Food</option><option value="Supplies">Supplies</option><option value="Maintenance">Maintenance</option><option value="Other">Other</option>
                    </select>
                    <textarea x-model="exp.description" rows="2" placeholder="Description" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm resize-none"></textarea>
                    <input x-model.number="exp.amount" type="number" step="0.01" placeholder="Amount ₹ *" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <div class="grid grid-cols-3 gap-2">
                        <template x-for="mode in ['cash','upi','online']" :key="mode">
                            <button type="button" @click="exp.payment_mode=mode" :class="exp.payment_mode===mode?'bg-red-500 text-white':'bg-gray-100 dark:bg-gray-800 text-gray-600'" class="px-3 py-2 rounded-xl text-sm font-bold transition capitalize" x-text="mode"></button>
                        </template>
                    </div>
                    <input x-model="exp.expense_date" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <div class="flex gap-3 pt-2">
                        <button @click="submitExp()" :disabled="expBusy" class="flex-1 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition disabled:opacity-50">
                            <span x-show="!expBusy">Save Expense</span><span x-show="expBusy">Saving...</span>
                        </button>
                        <button @click="showExpModal=false" class="px-5 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl font-medium">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ KIOSK MODAL ═══ --}}
        <div x-show="showKskModal" x-transition.opacity class="fixed inset-0 z-[90] flex items-center justify-center p-4" style="display:none" @click.self="showKskModal=false">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md shadow-2xl border border-gray-200 dark:border-gray-800" @click.stop>
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <h2 class="text-lg font-bold flex items-center gap-2"><i data-lucide="landmark" class="w-5 h-5 text-purple-500"></i> Quick Kiosk Entry</h2>
                    <button @click="showKskModal=false" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800"><i data-lucide="x" class="w-4 h-4"></i></button>
                </div>
                <div class="p-6 space-y-3">
                    <select x-model="ksk.transaction_type" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <option value="withdraw">Withdraw</option><option value="deposit">Deposit</option>
                        <option value="balance">Balance Enquiry</option><option value="mini_statement">Mini Statement</option>
                    </select>
                    <div class="grid grid-cols-2 gap-3">
                        <input x-model="ksk.customer_name" type="text" placeholder="Customer Name" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <input x-model="ksk.customer_mobile" type="text" placeholder="Mobile" maxlength="10" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <input x-model="ksk.aadhaar_last_four" type="text" placeholder="Aadhaar last 4" maxlength="4" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <input x-model="ksk.bank_name" type="text" placeholder="Bank Name" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                    <input x-model.number="ksk.amount" type="number" min="0" placeholder="Amount ₹" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <div class="grid grid-cols-2 gap-3">
                        <input x-model.number="ksk.manual_commission" type="number" min="0" placeholder="Cash Comm ₹" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                        <input x-model.number="ksk.portal_commission" type="number" min="0" placeholder="Portal Comm ₹" class="px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    </div>
                    <input x-model="ksk.transaction_date" type="date" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm">
                    <div class="flex gap-3 pt-2">
                        <button @click="submitKsk()" :disabled="kskBusy" class="flex-1 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-xl font-bold transition disabled:opacity-50">
                            <span x-show="!kskBusy">Save Kiosk</span><span x-show="kskBusy">Saving...</span>
                        </button>
                        <button @click="showKskModal=false" class="px-5 py-3 bg-gray-100 dark:bg-gray-800 text-gray-600 rounded-xl font-medium">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function floatingBilling() {
        return {
            showPopup: false,
            posX: 24, posY: 24,
            dragging: false, dragStartX: 0, dragStartY: 0, startPosX: 0, startPosY: 0, hasMoved: false,

            // Modals
            showSlModal: false, showExpModal: false, showKskModal: false,
            slBusy: false, expBusy: false, kskBusy: false,
            svcList: [],
            svcLoaded: false,

            // Sale form
            sl: { customer_name:'', customer_phone:'', items:[{service_name:'',quantity:1,unit_price:0,cost_price:0}], discount_amount:0, payment_mode:'cash', cash_amount:0, online_amount:0, received_amount:'', remarks:'' },
            // Expense form
            exp: { category:'', description:'', amount:'', payment_mode:'cash', expense_date: new Date().toISOString().slice(0,10) },
            // Kiosk form
            ksk: { transaction_type:'withdraw', customer_name:'', customer_mobile:'', aadhaar_last_four:'', bank_name:'', amount:'', manual_commission:'', portal_commission:'', transaction_date: new Date().toISOString().slice(0,10) },

            togglePopup() { if (!this.hasMoved) this.showPopup = !this.showPopup; this.hasMoved = false; },

            async openSaleModal() {
                if (!this.svcLoaded) {
                    try { this.svcList = await (await fetch('/billing/services-json')).json(); this.svcLoaded = true; } catch(e) { this.svcList = []; }
                }
                this.showSlModal = true;
            },

            slSub() { return this.sl.items.reduce(function(s,i){ return s + (i.quantity * i.unit_price); }, 0); },
            slTotal() { return Math.max(0, this.slSub() - (this.sl.discount_amount || 0)); },

            async submitSl() {
                if (!this.sl.items[0]?.service_name) { alert('कमीत कमी एक सेवा निवडा'); return; }
                this.slBusy = true;
                try {
                    var res = await fetch('/billing/sales', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}, body:JSON.stringify({...this.sl, received_amount: this.sl.received_amount || this.slTotal()}) });
                    var data = await res.json();
                    if (data.success) { this.showSlModal=false; window.location.reload(); } else alert(data.message||'Error');
                } catch(e) { alert('Network error'); }
                this.slBusy = false;
            },

            async submitExp() {
                if (!this.exp.category || !this.exp.amount) { alert('Category आणि Amount भरा'); return; }
                this.expBusy = true;
                try {
                    var res = await fetch('/billing/expenses', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}, body:JSON.stringify(this.exp) });
                    if ((await res.json()).success) { this.showExpModal=false; window.location.reload(); }
                } catch(e) { alert('Error'); }
                this.expBusy = false;
            },

            async submitKsk() {
                this.kskBusy = true;
                try {
                    var res = await fetch('/billing/kiosk-book', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}, body:JSON.stringify(this.ksk) });
                    if ((await res.json()).success) { this.showKskModal=false; window.location.reload(); }
                } catch(e) { alert('Error'); }
                this.kskBusy = false;
            },

            startDrag(e) {
                this.dragging = true; this.hasMoved = false;
                var touch = e.touches ? e.touches[0] : e;
                this.dragStartX = touch.clientX; this.dragStartY = touch.clientY;
                this.startPosX = this.posX; this.startPosY = this.posY;
                var self = this;
                var onMove = function(ev) {
                    var t = ev.touches ? ev.touches[0] : ev;
                    var dx = self.dragStartX - t.clientX; var dy = self.dragStartY - t.clientY;
                    if (Math.abs(dx)>3||Math.abs(dy)>3) self.hasMoved = true;
                    self.posX = Math.max(8, Math.min(window.innerWidth-72, self.startPosX+dx));
                    self.posY = Math.max(8, Math.min(window.innerHeight-72, self.startPosY+dy));
                };
                var onEnd = function() {
                    self.dragging = false;
                    document.removeEventListener('mousemove',onMove); document.removeEventListener('mouseup',onEnd);
                    document.removeEventListener('touchmove',onMove); document.removeEventListener('touchend',onEnd);
                };
                document.addEventListener('mousemove',onMove); document.addEventListener('mouseup',onEnd);
                document.addEventListener('touchmove',onMove,{passive:true}); document.addEventListener('touchend',onEnd);
            }
        }
    }
    </script>
    @endif
    @endauth

    {{-- Subscription Popup Reminder (3x/day for users without active subscription) --}}
    @auth
    @if(!auth()->user()->isAdmin())
    @php
        // Process trial expiry on every page load
        \App\Http\Controllers\SubscriptionController::processTrialExpiry(auth()->user());
        $hasActiveSub = auth()->user()->hasActiveSubscription();
    @endphp
    @if(!$hasActiveSub)
    <div x-data="subReminder()" x-show="showReminder" x-transition.opacity
         class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display:none">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="dismiss()"></div>
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl w-full max-w-sm shadow-2xl border border-gray-200 dark:border-gray-800 overflow-hidden" @click.stop>
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 px-6 py-5 text-white text-center">
                <i data-lucide="crown" class="w-10 h-10 mx-auto mb-2"></i>
                <h2 class="text-lg font-black mb-1">सबस्क्रिप्शन सक्रिय करा</h2>
                <p class="text-xs text-white/80">बिलिंग, CRM आणि DocSlip वापरण्यासाठी प्लॅन आवश्यक आहे</p>
            </div>
            <div class="p-5 space-y-3 text-center">
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-3 text-xs text-amber-700">
                    <i data-lucide="gift" class="w-4 h-4 inline mr-1"></i> 15 दिवसांचा ट्रायल कालावधी — फक्त मेंटेनन्स शुल्क!
                </div>
                <a href="{{ route('subscription') }}" class="block w-full py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl font-bold text-sm hover:opacity-90 transition">
                    प्लॅन पहा आणि सक्रिय करा
                </a>
                <button @click="dismiss()" class="text-xs text-gray-400 hover:text-gray-600 transition">
                    नंतर करा (<span x-text="remaining"></span> रिमाइंडर बाकी)
                </button>
            </div>
        </div>
    </div>
    <script>
    function subReminder() {
        var today = new Date().toISOString().slice(0,10);
        var key = 'sub_reminder_' + today;
        var count = parseInt(localStorage.getItem(key) || '0');
        var maxPerDay = 3;
        return {
            showReminder: count < maxPerDay,
            remaining: Math.max(0, maxPerDay - count - 1),
            dismiss() {
                count++;
                localStorage.setItem(key, count.toString());
                this.showReminder = false;
            }
        };
    }
    </script>
    @endif
    @endif
    @endauth

    <script>lucide.createIcons();</script>
    @stack('scripts')
</body>
</html>
