@extends('layouts.app')
@section('title', 'About the Founder — Mr. Rajat | SETU Suvidha')
@section('description', 'Meet Mr. Rajat — CSC & Maha e-Seva Kendra Sanchalak, Samaj Sevak, and the developer behind SETU Suvidha. Learn why this platform was built and how it helps thousands of CSC operators across Maharashtra.')

@push('meta')
<meta name="keywords" content="setu suvidha founder, rajat setu suvidha, CSC kendra sanchalak, maha e seva kendra, samaj sevak, setu suvidha developer, CSC software developer, सेतू सुविधा संस्थापक">
<meta property="og:title" content="About the Founder — Mr. Rajat | SETU Suvidha">
<meta property="og:description" content="CSC & Maha e-Seva Kendra Sanchalak, Samaj Sevak, Website Developer — the story behind SETU Suvidha.">
<meta property="og:type" content="profile">
<meta property="og:url" content="{{ url('/author') }}">
<link rel="canonical" href="{{ url('/author') }}">
<script type="application/ld+json">
{"@@context":"https://schema.org","@@type":"Person","name":"Mr. Rajat","jobTitle":"CSC & Maha e-Seva Kendra Sanchalak","description":"Founder of SETU Suvidha — a SaaS platform for CSC and Maha e-Seva Kendra operators in Maharashtra.","url":"{{ url('/author') }}","worksFor":{"@@type":"Organization","name":"SETU Suvidha","url":"https://setusuvidha.com"}}
</script>
@endpush

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-16 sm:py-20">
<div class="absolute inset-0 opacity-5">
    <div class="absolute top-10 left-10 w-72 h-72 bg-amber-400 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 right-10 w-96 h-96 bg-orange-400 rounded-full blur-3xl"></div>
</div>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative">
    <nav class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 mb-8">
        <a href="{{ url('/') }}" class="hover:text-amber-600 transition">Home</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-gray-700 dark:text-gray-300 font-medium">About the Founder</span>
    </nav>

    <div class="flex flex-col sm:flex-row items-center gap-8">
        {{-- Avatar --}}
        <div class="shrink-0">
            <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-xl shadow-amber-200/50 dark:shadow-amber-900/20">
                <span class="text-5xl sm:text-6xl font-black text-white">R</span>
            </div>
        </div>

        {{-- Info --}}
        <div class="text-center sm:text-left">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-2">Mr. Rajat</h1>
            <div class="flex flex-wrap justify-center sm:justify-start gap-2 mb-4">
                <span class="inline-flex items-center gap-1.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-3 py-1 rounded-full text-[11px] font-bold">
                    <i data-lucide="building-2" class="w-3 h-3"></i> CSC / Maha e-Seva Sanchalak
                </span>
                <span class="inline-flex items-center gap-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 py-1 rounded-full text-[11px] font-bold">
                    <i data-lucide="code-2" class="w-3 h-3"></i> Website Developer
                </span>
                <span class="inline-flex items-center gap-1.5 bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-400 px-3 py-1 rounded-full text-[11px] font-bold">
                    <i data-lucide="heart-handshake" class="w-3 h-3"></i> Samaj Sevak
                </span>
            </div>
            <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed max-w-lg">
                Building technology to empower every CSC and Setu Kendra operator in Maharashtra — because digital India starts from the grassroots.
            </p>
        </div>
    </div>
</div>
</section>

{{-- Story --}}
<section class="py-14 bg-white dark:bg-gray-950">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
        <span class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 text-lg">📖</span>
        The Story Behind SETU Suvidha
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        {{-- The Problem --}}
        <div>
            <h3 class="text-lg font-bold text-red-600 dark:text-red-400 mb-4 flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i> The Problem — CSC Sanchalaks Face Daily
            </h3>
            <div class="space-y-3">
                @foreach([
                    ['file-x', 'No proper software', 'Government form formats keep changing. Sanchalaks waste hours formatting manually in Word/Excel.'],
                    ['book-x', 'Manual register keeping', 'Customer records, daily sales, expenses — everything written in physical registers. Lost records = lost money.'],
                    ['indian-rupee', 'Overpriced middlemen software', 'Existing tools charge ₹5,000-15,000/year with poor support and no Marathi interface.'],
                    ['languages', 'No bilingual support', 'Most software is English-only. Marathi-speaking sanchalaks struggle with forms that need legal Marathi text.'],
                    ['calculator', 'No profit tracking', 'Sanchalaks don\'t know their actual profit per service. No daily book, no expense tracking.'],
                    ['users-x', 'No customer management', 'Repeat customers come back but there\'s no history. No way to track who paid what, when.'],
                ] as $p)
                <div class="flex gap-3 items-start bg-red-50/50 dark:bg-red-900/5 border border-red-100 dark:border-red-900/20 rounded-xl p-3">
                    <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $p[0] }}" class="w-4 h-4 text-red-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $p[1] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $p[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- The Solution --}}
        <div>
            <h3 class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mb-4 flex items-center gap-2">
                <i data-lucide="lightbulb" class="w-5 h-5"></i> The Solution — SETU Suvidha
            </h3>
            <div class="space-y-3">
                @foreach([
                    ['file-check', '12+ Government Forms', 'Hamipatra, Rajpatra, Income Certificate, Domicile — all auto-formatted for A4 print with Marathi legal text.'],
                    ['receipt', 'Complete Billing System', 'Daily sales, expenses, profit tracking, kiosk book, customer management — all in one dashboard.'],
                    ['credit-card', 'Affordable Pricing', 'Starting free. Premium plans at fraction of market cost. Prepaid wallet with Razorpay.'],
                    ['languages', 'Full Marathi Support', 'Every form, every interface — designed for Marathi-speaking sanchalaks first.'],
                    ['bar-chart-3', 'Business Intelligence', 'Know your profit per service, per day. Track expenses. See which services make money.'],
                    ['shield-check', 'Your Data, Your Control', 'Each user\'s data is completely isolated. No sharing. You own your customer records.'],
                ] as $s)
                <div class="flex gap-3 items-start bg-emerald-50/50 dark:bg-emerald-900/5 border border-emerald-100 dark:border-emerald-900/20 rounded-xl p-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $s[0] }}" class="w-4 h-4 text-emerald-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $s[1] }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $s[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Mission Statement --}}
    <div class="mt-10 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-gray-900 dark:to-gray-900 border border-amber-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                <i data-lucide="target" class="w-6 h-6 text-amber-600"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Our Mission</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    I started SETU Suvidha because I am a CSC sanchalak myself. I know the daily struggles — formatting forms at midnight, 
                    losing customer records, not knowing if the kendra is actually profitable. Every feature in this platform comes from 
                    real problems I faced running my own kendra. My goal is simple: <strong>give every CSC and Setu Kendra operator the tools 
                    they deserve — affordable, in Marathi, and built by someone who understands their work.</strong>
                </p>
            </div>
        </div>
    </div>
</div>
</section>

{{-- What We Offer --}}
<section class="py-14 bg-gray-50 dark:bg-gray-900/50">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
        <span class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 text-lg">🛠️</span>
        What SETU Suvidha Offers
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach([
            ['file-text', 'blue', 'Government Forms', 'Hamipatra, Rajpatra, Income, Domicile, Age, Birth certificates — auto-formatted A4 with Marathi legal text.'],
            ['gavel', 'purple', 'Bond Formats', 'Rent Agreement, Gift Deed, Partition Deed, Release Deed, Marriage Affidavit — ready-to-print legal bonds.'],
            ['receipt', 'emerald', 'Billing & Accounting', 'Daily sales, expenses, services pricing, profit tracking, kiosk transactions — complete business management.'],
            ['users', 'pink', 'Customer CRM', 'PAN Card, Voter ID, Bandkam Kamgar — track applications, status, payments per customer.'],
            ['id-card', 'green', 'Farmer ID Card', 'QR code-enabled Farmer ID cards with online payment — instant PDF download.'],
            ['file-check-2', 'amber', 'Document Slip', 'Track which documents collected from customer. Print receipt slips. Never lose a document again.'],
            ['wallet', 'cyan', 'Prepaid Wallet', 'Recharge via Razorpay UPI/Card. Auto-deduct per form. No monthly commitment.'],
            ['palette', 'rose', '24 Custom Themes', 'Personalize your dashboard with 24 beautiful color themes.'],
            ['map-pin', 'indigo', 'Maharashtra Data', 'All 36 districts, all talukas — pre-loaded for fast form filling.'],
        ] as $f)
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl p-5 hover:shadow-md transition">
            <div class="w-9 h-9 rounded-lg bg-{{ $f[1] }}-100 dark:bg-{{ $f[1] }}-900/30 flex items-center justify-center mb-3">
                <i data-lucide="{{ $f[0] }}" class="w-4.5 h-4.5 text-{{ $f[1] }}-600"></i>
            </div>
            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">{{ $f[2] }}</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $f[3] }}</p>
        </div>
        @endforeach
    </div>
</div>
</section>

{{-- Contact & Request Form --}}
<section class="py-14 bg-white dark:bg-gray-950">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
        <span class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-600 text-lg">📬</span>
        Get in Touch / Submit a Request
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

        {{-- Contact Info (left) --}}
        <div class="lg:col-span-2 space-y-4">
            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                Have a question, suggestion, or need help? Submit your request below. I personally review every message.
            </p>

            @php
                $authorEmail = config('site.author_email', '');
                $authorPhone = config('site.author_phone', '');
            @endphp

            @if($authorEmail)
            <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-900 rounded-xl p-3 border border-gray-100 dark:border-gray-800">
                <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                    <i data-lucide="mail" class="w-4 h-4 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-medium">Email</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $authorEmail }}</p>
                </div>
            </div>
            @endif

            @if($authorPhone)
            <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-900 rounded-xl p-3 border border-gray-100 dark:border-gray-800">
                <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                    <i data-lucide="phone" class="w-4 h-4 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-medium">Phone</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $authorPhone }}</p>
                </div>
            </div>
            @endif

            <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-900 rounded-xl p-3 border border-gray-100 dark:border-gray-800">
                <div class="w-9 h-9 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                    <i data-lucide="map-pin" class="w-4 h-4 text-green-600"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-medium">Location</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Maharashtra, India</p>
                </div>
            </div>

            <div class="bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/30 rounded-xl p-4 mt-4">
                <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                    <strong>Note:</strong> Please do not include any links/URLs in your message. 
                    Attachments are limited to PDF and image files (JPG, PNG) only, max 2MB.
                </p>
            </div>
        </div>

        {{-- Request Form (right) --}}
        <div class="lg:col-span-3">
            @if(session('success'))
            <div class="mb-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30 rounded-xl p-4 flex items-start gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600 shrink-0 mt-0.5"></i>
                <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-xl p-4 flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
                <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
            @endif

            <form action="{{ route('author.submit-request') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">Your Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="e.g. Rajesh Patil" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-amber-500 transition @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required maxlength="150" placeholder="your@email.com" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-amber-500 transition @error('email') border-red-400 @enderror">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">Subject *</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="200" placeholder="e.g. Feature request, Bug report, Help needed" class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-amber-500 transition @error('subject') border-red-400 @enderror">
                    @error('subject') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">Message * <span class="text-gray-400 normal-case">(no links allowed)</span></label>
                    <textarea name="message" rows="5" required maxlength="2000" placeholder="Describe your request, question, or suggestion..." class="w-full px-3 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-amber-500 transition resize-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                    @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-medium text-gray-500 uppercase mb-1 block">Attachment <span class="text-gray-400 normal-case">(optional — PDF, JPG, PNG only, max 2MB)</span></label>
                    <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-gray-600 dark:text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-amber-100 file:text-amber-700 dark:file:bg-amber-900/30 dark:file:text-amber-400 hover:file:bg-amber-200 transition @error('attachment') border-red-400 @enderror">
                    @error('attachment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-sm transition flex items-center justify-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i> Submit Request
                </button>
            </form>
        </div>
    </div>
</div>
</section>

{{-- CTA --}}
<section class="py-10 bg-gradient-to-r from-amber-500 to-orange-500">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h3 class="text-xl font-bold text-white mb-2">Ready to transform your CSC Kendra?</h3>
    <p class="text-amber-100 text-sm mb-5">Join thousands of sanchalaks already using SETU Suvidha.</p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ url('/register') }}" class="bg-white text-amber-700 px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-amber-50 transition">Get Started Free</a>
        <a href="{{ url('/services') }}" class="bg-amber-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-amber-700 transition border border-amber-400">Explore Services</a>
    </div>
</div>
</section>

@endsection
