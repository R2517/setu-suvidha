@extends('layouts.app')
@section('title', 'Unlock Billing — SETU Suvidha')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 via-white to-gray-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-10 px-4">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="max-w-3xl mx-auto mb-6">
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-3xl mx-auto mb-6">
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ session('error') }}
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="max-w-3xl mx-auto mb-6">
        <div class="bg-amber-50 border border-amber-200 text-amber-700 px-5 py-3.5 rounded-2xl text-sm font-medium flex items-center gap-2 shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i> {{ session('warning') }}
        </div>
    </div>
    @endif

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-800">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-center text-white">
            <h1 class="text-3xl font-black mb-2">Billing Module License</h1>
            <p class="text-blue-100 text-sm">Manage Sales, Expenses, Customers & Daily Book</p>
        </div>

        <div class="p-8">
            @if($hasAccess)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-6 text-center mb-6">
                    <i data-lucide="check-circle-2" class="w-12 h-12 text-green-500 mx-auto mb-3"></i>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">You have active access to the Billing Module</h2>
                    
                    @if($isTrialActive)
                        <p class="text-green-700 dark:text-green-400 font-medium">Your 15-day free trial is active. You have {{ $trialDaysLeft }} days remaining.</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">You can purchase the yearly license anytime below to continue access after the trial ends.</p>
                    @else
                        <p class="text-green-700 dark:text-green-400 font-medium">Your Yearly Billing License is active!</p>
                    @endif
                </div>

                <div class="text-center">
                    <a href="{{ route('billing.redirect') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 dark:shadow-none transition-all">
                        Go to Billing Dashboard <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            @endif

            @if(!$hasAccess || $isTrialActive)
                <div class="text-center my-8">
                    <div class="inline-flex items-end justify-center mb-4 text-gray-900 dark:text-white">
                        <span class="text-5xl font-black tracking-tight">₹499</span>
                        <span class="text-xl font-medium text-gray-500 dark:text-gray-400 ml-1 mb-1">/ year</span>
                    </div>
                    
                    <div class="flex flex-col gap-3 max-w-sm mx-auto">
                        <form action="{{ route('subscription.activate-billing') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-xl flex items-center justify-center gap-2 transition-all">
                                <i data-lucide="wallet" class="w-5 h-5"></i>
                                Pay via Wallet (₹{{ number_format($walletBalance, 2) }} available)
                            </button>
                        </form>
                        
                        <button id="rzp-button" type="button" class="w-full bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 border-2 border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-400 font-bold py-3 px-6 rounded-xl flex items-center justify-center gap-2 transition-all">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            Pay Online (Razorpay)
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rzpButton = document.getElementById('rzp-button');
    if (!rzpButton) return;

    rzpButton.onclick = function (e) {
        e.preventDefault();
        rzpButton.disabled = true;
        rzpButton.innerHTML = '<i class="lucide-loader animate-spin w-5 h-5"></i> Loading...';

        fetch('{{ route("subscription.payment-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
                window.location.reload();
                return;
            }

            var options = {
                "key": "{{ $razorpayKeyId }}",
                "amount": data.amount,
                "currency": "INR",
                "name": data.name,
                "description": data.description,
                "order_id": data.order_id,
                "prefill": data.prefill,
                "theme": {
                    "color": "#4f46e5"
                },
                "handler": function (response) {
                    fetch('{{ route("subscription.payment-verify") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature
                        })
                    })
                    .then(r => r.json())
                    .then(verifyData => {
                        if (verifyData.success) {
                            window.location.href = verifyData.redirect;
                        } else {
                            alert(verifyData.message);
                            window.location.reload();
                        }
                    });
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        })
        .catch(err => {
            console.error(err);
            alert("Payment initiation failed.");
            window.location.reload();
        });
    };
});
</script>
@endsection
