@extends('layouts.app')
@section('title', 'वॉलेट — SETU Suvidha')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="walletApp()">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा
    </a>

    {{-- Low Balance Warning --}}
    @if($walletBalance < 30)
    <div class="mb-4 px-4 py-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl flex items-center gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 shrink-0"></i>
        <div>
            <p class="text-sm font-bold text-red-700 dark:text-red-400">कमी शिल्लक!</p>
            <p class="text-xs text-red-600 dark:text-red-400/80">तुमची शिल्लक ₹30 पेक्षा कमी आहे. सेवा वापरण्यासाठी कृपया रिचार्ज करा. किमान रिचार्ज: ₹50</p>
        </div>
    </div>
    @endif

    {{-- Balance Card --}}
    <div class="theme-wallet-card bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-8 text-white mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/15 flex items-center justify-center">
                <i data-lucide="wallet" class="w-8 h-8"></i>
            </div>
            <div>
                <p class="text-white/80 text-sm">वॉलेट शिल्लक</p>
                <p class="text-4xl font-bold">₹{{ number_format($walletBalance, 2) }}</p>
                @if($walletBalance < 30)
                <p class="text-white/70 text-xs mt-1">⚠ किमान शिल्लक ₹30 ठेवा</p>
                @endif
            </div>
        </div>
        <button @click="showRecharge = true" class="bg-white text-amber-600 font-semibold px-6 py-3 rounded-xl hover:bg-amber-50 transition flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-5 h-5"></i> रिचार्ज करा
        </button>
    </div>

    {{-- Recharge Modal --}}
    <div x-show="showRecharge" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" @click.self="showRecharge = false">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 w-full max-w-md shadow-2xl border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">वॉलेट रिचार्ज</h3>
                <button @click="showRecharge = false" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">रक्कम निवडा किंवा कस्टम रक्कम टाका:</p>
            <div class="grid grid-cols-3 gap-3 mb-4">
                @foreach([100, 200, 500, 1000, 2000, 5000] as $amt)
                <button @click="rechargeAmount = {{ $amt }}" class="py-3 rounded-xl border-2 text-sm font-semibold transition"
                    :class="rechargeAmount === {{ $amt }} ? 'border-amber-500 bg-amber-50 dark:bg-amber-900/20 text-amber-600' : 'border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-amber-300'">
                    ₹{{ number_format($amt) }}
                </button>
                @endforeach
            </div>
            <input type="number" x-model.number="rechargeAmount" min="50" max="50000" placeholder="किमान ₹50"
                class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white mb-2 focus:ring-2 focus:ring-amber-500">
            <p class="text-[11px] text-gray-400 mb-3" x-show="rechargeAmount < 50 && rechargeAmount > 0"><span class="text-red-500 font-bold">किमान रिचार्ज ₹50 आहे</span></p>
            <button @click="initiateRecharge()" :disabled="rechargeLoading || rechargeAmount < 50"
                class="w-full btn-primary !py-3.5 text-base disabled:opacity-50">
                <span x-show="!rechargeLoading">💳 ₹<span x-text="rechargeAmount"></span> रिचार्ज करा</span>
                <span x-show="rechargeLoading">प्रक्रिया सुरू आहे...</span>
            </button>
        </div>
    </div>

    {{-- Transaction History --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-amber-600"></i> व्यवहार इतिहास
            </h3>
        </div>
        @if($transactions->isEmpty())
        <div class="px-6 py-12 text-center text-gray-400">
            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
            <p>अद्याप कोणतेही व्यवहार नाहीत</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">तारीख</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400">वर्णन</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400">प्रकार</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400">रक्कम</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400">शिल्लक</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($transactions as $txn)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-6 py-3 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $txn->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-6 py-3 text-gray-900 dark:text-white">{{ $txn->description ?? '-' }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($txn->type === 'credit')
                                <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 rounded-full text-xs font-medium">जमा</span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 px-2 py-0.5 rounded-full text-xs font-medium">खर्च</span>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-right font-semibold whitespace-nowrap {{ $txn->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                        </td>
                        <td class="px-6 py-3 text-right text-gray-600 dark:text-gray-400 whitespace-nowrap">₹{{ number_format($txn->balance_after, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function walletApp() {
    return {
        showRecharge: false,
        rechargeAmount: 500,
        rechargeLoading: false,
        async initiateRecharge() {
            if (this.rechargeAmount < 50) { alert('किमान रिचार्ज रक्कम ₹50 आहे'); return; }
            this.rechargeLoading = true;
            try {
                const res = await fetch('{{ route("wallet.recharge") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ amount: this.rechargeAmount })
                });
                const data = await res.json();
                if (!data.success) { alert(data.message || 'त्रुटी आली'); this.rechargeLoading = false; return; }

                const options = {
                    key: data.key_id,
                    amount: data.amount,
                    currency: 'INR',
                    name: 'SETU Suvidha',
                    description: 'वॉलेट रिचार्ज — ₹' + this.rechargeAmount,
                    order_id: data.order_id,
                    handler: async (response) => {
                        const verifyRes = await fetch('{{ route("wallet.verify") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature
                            })
                        });
                        const result = await verifyRes.json();
                        if (result.success) { window.location.reload(); }
                        else { alert(result.message || 'सत्यापन अयशस्वी'); }
                    },
                    prefill: { email: '{{ $user->email }}' },
                    theme: { color: '#d97706' },
                    modal: { ondismiss: () => { this.rechargeLoading = false; } }
                };
                const rzp = new Razorpay(options);
                rzp.open();
            } catch(e) {
                alert('त्रुटी आली, कृपया पुन्हा प्रयत्न करा');
                this.rechargeLoading = false;
            }
        }
    }
}
</script>
@endpush
@endsection
