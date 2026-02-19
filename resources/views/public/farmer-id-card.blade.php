@extends('layouts.app')
@section('title', 'Farmer ID Card Online - शेतकरी ओळखपत्र डाउनलोड | SETU Suvidha')

@push('meta')
<meta name="description" content="Get your Farmer ID Card online instantly. Download Kisan Identity Card with QR code. शेतकरी ओळखपत्र ऑनलाइन बनवा. PM Kisan, CSC Farmer Registration, Agriculture ID Card.">
<meta name="keywords" content="farmer id card, farmer id card download, शेतकरी ओळखपत्र, kisan id card, farmer identity card online, farmer registration, pm kisan card, csc farmer id, agriculture id card, farmer card maharashtra, kisan card online, farmer id card apply, farmer id card status, शेतकरी ओळखपत्र ऑनलाइन, शेतकरी नोंदणी, farmer card download pdf, farmer id card qr code, PM Kisan Maharashtra status, नमो शेतकरी योजना, शेतकरी सन्मान निधी, kisan samman nidhi maharashtra, farmer card free download, शेतकरी ओळखपत्र मोफत">
<meta property="og:title" content="Farmer ID Card Online Free - शेतकरी ओळखपत्र मोफत डाउनलोड">
<meta property="og:description" content="Get your Farmer ID Card with QR code instantly — FREE. शेतकरी ओळखपत्र मोफत ऑनलाइन बनवा. No registration required.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/services/farmer-id-card-online') }}">
<link rel="canonical" href="{{ url('/services/farmer-id-card-online') }}">
<script type="application/ld+json">
{"@@context":"https://schema.org","@@type":"FAQPage","mainEntity":[{"@@type":"Question","name":"शेतकरी ओळखपत्र कसे बनवायचे?","acceptedAnswer":{"@@type":"Answer","text":"SETU Suvidha वर शेतकरी ओळखपत्र मोफत बनवा. नाव, पत्ता, आधार क्रमांक आणि फोटो भरा — QR कोडसह ID Card डाउनलोड करा."}},{"@@type":"Question","name":"Farmer ID Card online free कसे मिळवायचे?","acceptedAnswer":{"@@type":"Answer","text":"setusuvidha.com/services/farmer-id-card-online वर जा, माहिती भरा आणि Download बटण दाबा. कोणतेही शुल्क नाही."}},{"@@type":"Question","name":"शेतकरी ओळखपत्राचे फायदे काय?","acceptedAnswer":{"@@type":"Answer","text":"शेतकरी ओळखपत्र PM Kisan, Crop Insurance, बँक कर्ज आणि शासकीय योजनांसाठी ओळख पुरावा म्हणून वापरता येते."}}]}
</script>
@endpush

@section('content')
<div x-data="farmerPublic()" class="min-h-screen bg-gray-50 dark:bg-gray-950">

    {{-- ═══════════ HERO SECTION ═══════════ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-green-50 via-lime-50 to-emerald-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-16 lg:py-24">
        <div class="absolute inset-0 opacity-20 dark:opacity-10" style="background-image: radial-gradient(circle, #22c55e 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="absolute top-10 right-10 w-72 h-72 bg-green-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-56 h-56 bg-lime-400/10 rounded-full blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-1.5 rounded-full text-xs font-semibold mb-6">
                        <i data-lucide="leaf" class="w-3.5 h-3.5"></i> Farmer ID Card Online — शेतकरी ओळखपत्र
                    </div>
                    <h1 class="text-3xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                        Farmer ID Card<br>
                        <span class="bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Download Form</span><br>
                        <span class="text-2xl lg:text-3xl">शेतकरी ओळखपत्र ऑनलाइन बनवा</span>
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        Get your <strong>Farmer ID Card with QR Code</strong> instantly! Fill your details, make payment, and download your <strong>Kisan Identity Card</strong> — ready to print. No CSC center visit required.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#form-section" class="inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-4 rounded-xl transition text-lg shadow-lg">
                            <i data-lucide="download" class="w-5 h-5"></i> Get Your Card Now
                        </a>
                        <a href="#lookup-section" class="inline-flex items-center justify-center gap-2 bg-white dark:bg-gray-800 border-2 border-green-600 text-green-700 dark:text-green-400 font-bold px-8 py-4 rounded-xl hover:bg-green-50 dark:hover:bg-gray-700 transition text-lg">
                            <i data-lucide="search" class="w-5 h-5"></i> Re-Download Card
                        </a>
                    </div>
                    <div class="flex items-center gap-6 mt-8 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center gap-1.5"><i data-lucide="shield-check" class="w-4 h-4 text-green-500"></i> Secure Payment</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="zap" class="w-4 h-4 text-green-500"></i> Instant Download</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="qr-code" class="w-4 h-4 text-green-500"></i> QR Code Included</span>
                    </div>
                </div>
                <div class="hidden lg:flex justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-emerald-500 rounded-3xl rotate-3 opacity-20 blur-xl"></div>
                        <div class="relative bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 border border-green-100 dark:border-green-900/50 max-w-sm">
                            <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl p-4 text-white mb-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xl">🌾</span>
                                    <span class="font-bold text-sm">शेतकरी ओळखपत्र</span>
                                </div>
                                <div class="text-xs opacity-80">Farmer ID Card</div>
                            </div>
                            <div class="flex gap-4 mb-4">
                                <div class="w-20 h-24 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center text-gray-400 text-xs">Photo</div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                                    <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded w-1/2"></div>
                                    <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded w-2/3"></div>
                                    <div class="h-2 bg-gray-100 dark:bg-gray-800 rounded w-1/2"></div>
                                </div>
                            </div>
                            <div class="flex justify-between items-end">
                                <div class="text-[10px] text-gray-400">FARMER ID: XXXX XXXX XXX</div>
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-800 rounded flex items-center justify-center text-[8px] text-gray-400">QR</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ BENEFITS SECTION ═══════════ --}}
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-3">Why Get Farmer ID Card?</h2>
                <p class="text-gray-500 dark:text-gray-400">शेतकरी ओळखपत्राचे फायदे — Benefits of Kisan Identity Card</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['icon' => 'badge-check', 'title' => 'Official Identity', 'desc' => 'Use as farmer identity proof for government schemes, subsidies & PM Kisan benefits', 'color' => 'green'],
                    ['icon' => 'qr-code', 'title' => 'QR Code Verification', 'desc' => 'Built-in QR code with all your details for instant verification at any center', 'color' => 'blue'],
                    ['icon' => 'zap', 'title' => 'Instant Download', 'desc' => 'Fill form, pay online & download immediately — no waiting, no CSC center visit', 'color' => 'amber'],
                    ['icon' => 'refresh-cw', 'title' => 'Re-Download Anytime', 'desc' => 'Use your transaction number to download your card unlimited times for 7 days', 'color' => 'purple'],
                ] as $b)
                <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 rounded-xl bg-{{ $b['color'] }}-100 dark:bg-{{ $b['color'] }}-900/30 flex items-center justify-center mb-4">
                        <i data-lucide="{{ $b['icon'] }}" class="w-6 h-6 text-{{ $b['color'] }}-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $b['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $b['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════ HOW IT WORKS ═══════════ --}}
    <section class="py-16 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-3">How to Get Farmer ID Card Online?</h2>
                <p class="text-gray-500 dark:text-gray-400">शेतकरी ओळखपत्र कसे बनवायचे — 3 Simple Steps</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['step' => '1', 'title' => 'Fill Your Details', 'desc' => 'Enter name, Aadhaar, Farmer ID, address, land details & upload photo', 'icon' => 'clipboard-edit'],
                    ['step' => '2', 'title' => 'Make Payment', 'desc' => 'Secure online payment via UPI, Card or Net Banking through Razorpay', 'icon' => 'credit-card'],
                    ['step' => '3', 'title' => 'Download Card', 'desc' => 'Get your Farmer ID Card with QR code — print-ready PDF format', 'icon' => 'download'],
                ] as $s)
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-green-600">{{ $s['step'] }}</span>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">{{ $s['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $s['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════ RE-DOWNLOAD / LOOKUP SECTION ═══════════ --}}
    <section id="lookup-section" class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-emerald-50 to-green-50 dark:from-gray-800 dark:to-gray-800 rounded-2xl p-8 border border-green-200 dark:border-green-900/50">
                <div class="text-center mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="search" class="w-7 h-7 text-green-600"></i>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Already Paid? Re-Download Your Card</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Enter your Transaction Number to download your Farmer ID Card again</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" x-model="lookupTxn" placeholder="Enter Transaction Number (e.g. FIC-20260217-XXXXXX)" class="flex-1 px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <button @click="lookupCard()" :disabled="lookupLoading" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2 disabled:opacity-50">
                        <i data-lucide="search" class="w-4 h-4"></i>
                        <span x-text="lookupLoading ? 'Searching...' : 'Find Card'"></span>
                    </button>
                </div>
                {{-- Lookup Result --}}
                <template x-if="lookupResult">
                    <div class="mt-4 rounded-xl p-4" :class="lookupResult.found && lookupResult.paid && !lookupResult.purged ? 'bg-green-100 dark:bg-green-900/20 border border-green-300 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800'">
                        <template x-if="lookupResult.found && lookupResult.paid && !lookupResult.purged">
                            <div>
                                <p class="font-semibold text-green-800 dark:text-green-400 mb-2">✅ Card Found!</p>
                                <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1 mb-3">
                                    <p><strong>Name:</strong> <span x-text="lookupResult.name"></span></p>
                                    <p><strong>Created:</strong> <span x-text="lookupResult.created_at"></span></p>
                                    <p><strong>Downloads:</strong> <span x-text="lookupResult.downloads"></span></p>
                                </div>
                                <a :href="lookupResult.download_url" target="_blank" class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-green-700 transition text-sm">
                                    <i data-lucide="download" class="w-4 h-4"></i> Download Card
                                </a>
                            </div>
                        </template>
                        <template x-if="!(lookupResult.found && lookupResult.paid && !lookupResult.purged)">
                            <p class="text-red-700 dark:text-red-400 text-sm" x-text="lookupResult.message"></p>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </section>

    {{-- ═══════════ FORM SECTION ═══════════ --}}
    <section id="form-section" class="py-16 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mb-3">Create Your Farmer ID Card</h2>
                <p class="text-gray-500 dark:text-gray-400">शेतकरी ओळखपत्र बनवा — Fill the form below to generate your card</p>
            </div>

            {{-- Success State --}}
            <template x-if="orderSuccess">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border-2 border-green-500 p-8 text-center">
                    <div class="w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="check-circle" class="w-10 h-10 text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Payment Successful! 🎉</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Your Farmer ID Card is ready to download.</p>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 mb-6 inline-block">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Your Transaction Number:</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-400 font-mono" x-text="successTxn"></p>
                        <p class="text-xs text-gray-400 mt-1">Save this number to re-download your card anytime (valid for 7 days)</p>
                    </div>
                    <div>
                        <a :href="'/services/farmer-id-card-online/download/' + successTxn" target="_blank" class="inline-flex items-center gap-2 bg-green-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-green-700 transition shadow-lg">
                            <i data-lucide="download" class="w-5 h-5"></i> Download Your Farmer ID Card
                        </a>
                    </div>
                </div>
            </template>

            {{-- Form --}}
            <template x-if="!orderSuccess">
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-5 text-white">
                        <h3 class="text-lg font-bold flex items-center gap-2"><i data-lucide="user" class="w-5 h-5"></i> Farmer Details — शेतकरी तपशील</h3>
                    </div>
                    <form @submit.prevent="submitForm()" class="p-8">
                        {{-- Step indicator --}}
                        <div class="flex items-center justify-center gap-2 mb-8">
                            <template x-for="(s, i) in ['Personal Info', 'Land Details', 'Review & Pay']" :key="i">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-all"
                                             :class="step > i+1 ? 'bg-green-500 text-white' : (step === i+1 ? 'bg-green-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500')">
                                            <span x-show="step <= i+1" x-text="i+1"></span>
                                            <i data-lucide="check" class="w-4 h-4" x-show="step > i+1"></i>
                                        </div>
                                        <span class="text-xs font-medium hidden sm:inline" :class="step === i+1 ? 'text-green-700 dark:text-green-400' : 'text-gray-400'" x-text="s"></span>
                                    </div>
                                    <div x-show="i < 2" class="w-8 h-px bg-gray-300 dark:bg-gray-700"></div>
                                </div>
                            </template>
                        </div>

                        {{-- ═══ STEP 1: Personal Info ═══ --}}
                        <div x-show="step === 1" x-transition>
                            <div class="flex flex-col lg:flex-row gap-8">
                                <div class="flex-1">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">नाव (मराठी) <span class="text-red-500">*</span></label>
                                            <input type="text" x-model="form.applicant_name" required placeholder="शेतकऱ्याचे पूर्ण नाव" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">Name (English) <span class="text-red-500">*</span></label>
                                            <input type="text" x-model="form.name_english" required placeholder="Full Name in English" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">जन्म तारीख / DOB</label>
                                            <input type="date" x-model="form.dob" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">लिंग / Gender</label>
                                            <select x-model="form.gender" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                <option value="">-- Select --</option>
                                                <option value="Male">पुरुष (Male)</option>
                                                <option value="Female">स्त्री (Female)</option>
                                                <option value="Other">इतर (Other)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">मोबाईल नंबर <span class="text-red-500">*</span></label>
                                            <input type="text" x-model="form.mobile" required maxlength="10" pattern="[0-9]{10}" placeholder="10 digit mobile" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">आधार नंबर (12 digits)</label>
                                            <input type="text" x-model="form.aadhaar" maxlength="14" placeholder="xxxx xxxx xxxx" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">शेतकरी ID / Farmer ID</label>
                                            <input type="text" x-model="form.farmer_id" maxlength="14" placeholder="Farmer Enrollment No." class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        </div>
                                    </div>

                                    {{-- Address --}}
                                    <div class="mt-6 p-4 bg-green-50/50 dark:bg-green-900/10 rounded-lg border border-green-100 dark:border-green-900/30">
                                        <p class="text-xs font-bold text-green-700 dark:text-green-400 mb-3">पत्ता / Address</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">जिल्हा / District</label>
                                                <select x-model="form.address_district" @change="updateTalukas()" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                    <option value="">-- Select District --</option>
                                                    <template x-for="d in districtNames" :key="d"><option :value="d" x-text="d"></option></template>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">तालुका / Taluka</label>
                                                <select x-model="form.address_taluka" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                                    <option value="">-- Select Taluka --</option>
                                                    <template x-for="t in talukaList" :key="t"><option :value="t" x-text="t"></option></template>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">गाव / Village</label>
                                                <input type="text" x-model="form.address_village" placeholder="Village name" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">पिनकोड / Pincode</label>
                                                <input type="text" x-model="form.address_pincode" maxlength="6" pattern="[0-9]{6}" placeholder="6 digit pincode" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Photo Upload --}}
                                <div class="w-full lg:w-48 flex flex-col items-center">
                                    <label class="text-xs font-bold text-gray-600 dark:text-gray-400 mb-2">शेतकरी फोटो <span class="text-red-500">*</span></label>
                                    <div class="w-36 h-44 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center bg-gray-50 dark:bg-gray-800 mb-3 overflow-hidden cursor-pointer" @click="$refs.photoInput.click()">
                                        <template x-if="photoPreview"><img :src="photoPreview" class="w-full h-full object-cover"></template>
                                        <template x-if="!photoPreview">
                                            <div class="text-center px-2">
                                                <i data-lucide="camera" class="w-8 h-8 text-gray-300 mx-auto mb-1"></i>
                                                <span class="text-xs text-gray-400">Click to upload photo</span>
                                            </div>
                                        </template>
                                    </div>
                                    <input type="file" x-ref="photoInput" accept="image/*" @change="previewPhoto($event)" class="hidden">
                                    <p class="text-[10px] text-gray-400">JPG/PNG, Max 2MB</p>
                                </div>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="button" @click="goStep(2)" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition flex items-center gap-2">
                                    Next: Land Details <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ═══ STEP 2: Land Details ═══ --}}
                        <div x-show="step === 2" x-transition>
                            <h3 class="text-sm font-bold text-green-700 dark:text-green-400 mb-4 flex items-center gap-2 border-b border-green-200 dark:border-green-800 pb-2">
                                <i data-lucide="map" class="w-4 h-4"></i> जमिनीचा तपशील / Land Details
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <thead class="bg-green-50 dark:bg-green-900/20">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">जिल्हा</th>
                                            <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">तालुका</th>
                                            <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">गाव</th>
                                            <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">गट नं.</th>
                                            <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">खाते नं.</th>
                                            <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">क्षेत्र (हे.)</th>
                                            <th class="px-3 py-2 w-10"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(land, idx) in lands" :key="idx">
                                            <tr class="border-t border-gray-100 dark:border-gray-800">
                                                <td class="px-2 py-1.5">
                                                    <select x-model="land.district" @change="fetchLandTalukas(idx)" class="w-full px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs">
                                                        <option value="">--</option>
                                                        <template x-for="d in districtNames" :key="d"><option :value="d" x-text="d"></option></template>
                                                    </select>
                                                </td>
                                                <td class="px-2 py-1.5">
                                                    <select x-model="land.taluka" class="w-full px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs">
                                                        <option value="">--</option>
                                                        <template x-for="t in (land._talukas || [])" :key="t"><option :value="t" x-text="t"></option></template>
                                                    </select>
                                                </td>
                                                <td class="px-2 py-1.5"><input x-model="land.village" type="text" placeholder="गाव" class="w-full px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs"></td>
                                                <td class="px-2 py-1.5"><input x-model="land.gat_no" type="text" class="w-24 px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs"></td>
                                                <td class="px-2 py-1.5"><input x-model="land.khate_no" type="text" class="w-24 px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs"></td>
                                                <td class="px-2 py-1.5"><input x-model="land.area" type="number" step="0.01" placeholder="0.00" class="w-24 px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs"></td>
                                                <td class="px-2 py-1.5">
                                                    <div class="flex gap-1">
                                                        <button type="button" @click="removeLand(idx)" x-show="lands.length > 1" class="w-7 h-7 rounded-full bg-red-500 text-white text-sm flex items-center justify-center hover:bg-red-600">&minus;</button>
                                                        <button type="button" @click="addLand()" x-show="idx === lands.length - 1" class="w-7 h-7 rounded-full bg-green-600 text-white text-sm flex items-center justify-center hover:bg-green-700">+</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex justify-between mt-6">
                                <button type="button" @click="goStep(1)" class="text-gray-500 hover:text-gray-700 font-semibold px-4 py-3 rounded-xl transition flex items-center gap-2">
                                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                                </button>
                                <button type="button" @click="goStep(3)" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition flex items-center gap-2">
                                    <span x-text="isFree ? 'Next: Review & Download' : 'Next: Review & Pay'"></span> <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        {{-- ═══ STEP 3: Review & Pay ═══ --}}
                        <div x-show="step === 3" x-transition>
                            <div class="bg-green-50 dark:bg-green-900/10 rounded-xl p-6 mb-6 border border-green-200 dark:border-green-800">
                                <h3 class="font-bold text-green-800 dark:text-green-400 mb-4 flex items-center gap-2"><i data-lucide="eye" class="w-4 h-4"></i> Review Your Details</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                    <div><strong class="text-gray-500">नाव:</strong> <span x-text="form.applicant_name" class="text-gray-900 dark:text-white"></span></div>
                                    <div><strong class="text-gray-500">Name:</strong> <span x-text="form.name_english" class="text-gray-900 dark:text-white"></span></div>
                                    <div><strong class="text-gray-500">DOB:</strong> <span x-text="form.dob || '—'" class="text-gray-900 dark:text-white"></span></div>
                                    <div><strong class="text-gray-500">Gender:</strong> <span x-text="form.gender || '—'" class="text-gray-900 dark:text-white"></span></div>
                                    <div><strong class="text-gray-500">Mobile:</strong> <span x-text="form.mobile" class="text-gray-900 dark:text-white"></span></div>
                                    <div><strong class="text-gray-500">Farmer ID:</strong> <span x-text="form.farmer_id || '—'" class="text-gray-900 dark:text-white"></span></div>
                                    <div class="sm:col-span-2"><strong class="text-gray-500">Address:</strong> <span x-text="[form.address_village, form.address_taluka, form.address_district].filter(Boolean).join(', ') || '—'" class="text-gray-900 dark:text-white"></span></div>
                                    <div><strong class="text-gray-500">Land Plots:</strong> <span x-text="lands.filter(l => l.village || l.district).length" class="text-gray-900 dark:text-white"></span></div>
                                </div>
                            </div>

                            <div class="bg-amber-50 dark:bg-amber-900/10 rounded-xl p-4 mb-6 border border-amber-200 dark:border-amber-800 text-center">
                                <template x-if="isFree">
                                    <p class="text-sm text-green-700 dark:text-green-400 font-semibold"><i data-lucide="gift" class="w-4 h-4 inline"></i> This service is currently <strong>FREE</strong>! Click below to generate & download your card instantly. You'll get a Transaction Number for re-downloads.</p>
                                </template>
                                <template x-if="!isFree">
                                    <p class="text-sm text-amber-700 dark:text-amber-400 font-semibold"><i data-lucide="info" class="w-4 h-4 inline"></i> After payment, you will receive a <strong>Transaction Number</strong>. Use it to re-download your card anytime within 7 days.</p>
                                </template>
                            </div>

                            <div class="flex justify-between mt-6">
                                <button type="button" @click="goStep(2)" class="text-gray-500 hover:text-gray-700 font-semibold px-4 py-3 rounded-xl transition flex items-center gap-2">
                                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                                </button>
                                <button type="submit" :disabled="submitting" class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold px-8 py-4 rounded-xl transition flex items-center gap-2 shadow-lg text-lg disabled:opacity-50">
                                    <i :data-lucide="isFree ? 'download' : 'credit-card'" class="w-5 h-5"></i>
                                    <span x-text="submitting ? 'Processing...' : (isFree ? 'Generate & Download' : 'Generate & Pay')"></span>
                                </button>
                            </div>
                        </div>

                        {{-- Error display --}}
                        <template x-if="formError">
                            <div class="mt-4 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-xl px-4 py-3 text-sm text-red-700 dark:text-red-400" x-text="formError"></div>
                        </template>
                    </form>
                </div>
            </template>
        </div>
    </section>

    {{-- ═══════════ SEO CONTENT / BLOG SECTION ═══════════ --}}
    <section class="py-16 bg-white dark:bg-gray-900">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-green dark:prose-invert max-w-none">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Farmer ID Card — Complete Guide (शेतकरी ओळखपत्र संपूर्ण माहिती)</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">What is Farmer ID Card?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">A Farmer ID Card (शेतकरी ओळखपत्र / Kisan ID Card) is an identity document that helps farmers identify themselves for government schemes, subsidies, and agricultural benefits. It contains the farmer's personal details, land information, and a QR code for quick verification.</p>

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-6">Benefits of Farmer ID Card</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>✅ PM Kisan Samman Nidhi Yojana registration</li>
                        <li>✅ Government subsidy on seeds, fertilizers & equipment</li>
                        <li>✅ Crop insurance (Pradhan Mantri Fasal Bima Yojana)</li>
                        <li>✅ Bank KCC (Kisan Credit Card) application</li>
                        <li>✅ Soil Health Card scheme eligibility</li>
                        <li>✅ State government agricultural benefits</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">शेतकरी ओळखपत्र म्हणजे काय?</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">शेतकरी ओळखपत्र हे एक ओळख दस्तऐवज आहे जे शेतकऱ्यांना सरकारी योजना, अनुदान आणि कृषी लाभांसाठी ओळख सिद्ध करण्यास मदत करते. यामध्ये शेतकऱ्याची वैयक्तिक माहिती, जमिनीचा तपशील आणि QR कोड असतो.</p>

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-6">Required Documents</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>📋 Aadhaar Card (आधार कार्ड)</li>
                        <li>📋 7/12 Extract (सातबारा उतारा)</li>
                        <li>📋 8A / Ferfar (फेरफार)</li>
                        <li>📋 Passport size photo</li>
                        <li>📋 Mobile number linked with Aadhaar</li>
                        <li>📋 Farmer Registration Number (if available)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ FAQ SECTION ═══════════ --}}
    <section class="py-16 bg-gray-50 dark:bg-gray-950">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Frequently Asked Questions</h2>
                <p class="text-gray-500 dark:text-gray-400">शेतकरी ओळखपत्र — सामान्य प्रश्न</p>
            </div>
            <div class="space-y-3" x-data="{ faqOpen: null }">
                @foreach([
                    ['q' => 'How to download Farmer ID Card online?', 'a' => 'Fill the form above with your personal details, land information, and photo. After payment, you can instantly download your Farmer ID Card with QR code.'],
                    ['q' => 'शेतकरी ओळखपत्र कसे डाउनलोड करायचे?', 'a' => 'वरील फॉर्ममध्ये तुमची वैयक्तिक माहिती, जमिनीचा तपशील आणि फोटो भरा. पेमेंट केल्यानंतर तुम्ही तात्काळ QR कोडसह शेतकरी ओळखपत्र डाउनलोड करू शकता.'],
                    ['q' => 'Can I re-download my Farmer ID Card?', 'a' => 'Yes! After payment, you receive a unique Transaction Number. Use it to re-download your card unlimited times within 7 days.'],
                    ['q' => 'What payment methods are accepted?', 'a' => 'We accept UPI (Google Pay, PhonePe, Paytm), Debit/Credit Cards, and Net Banking through Razorpay secure payment gateway.'],
                    ['q' => 'Is this an official government Farmer ID Card?', 'a' => 'This is a farmer identity card for personal use based on self-declared information. It is not a government-issued document but can be used as supplementary identity proof.'],
                    ['q' => 'How long is my data stored?', 'a' => 'Your personal data is securely stored for 7 days for re-download purposes. After that, personal details are automatically purged for privacy.'],
                ] as $i => $faq)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <button @click="faqOpen === {{ $i }} ? faqOpen = null : faqOpen = {{ $i }}" class="w-full flex items-center justify-between px-5 py-4 text-left">
                        <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $faq['q'] }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="faqOpen === {{ $i }} && 'rotate-180'"></i>
                    </button>
                    <div x-show="faqOpen === {{ $i }}" x-collapse class="px-5 pb-4 text-sm text-gray-500 dark:text-gray-400">{{ $faq['a'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

{{-- Razorpay SDK --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function farmerPublic() {
    return {
        step: 1,
        submitting: false,
        formError: null,
        orderSuccess: false,
        successTxn: '',
        photoFile: null,
        photoPreview: null,
        lookupTxn: '',
        lookupLoading: false,
        lookupResult: null,
        isFree: {{ $servicePrice == 0 ? 'true' : 'false' }},

        form: {
            applicant_name: '', name_english: '', dob: '', gender: '',
            mobile: '', aadhaar: '', farmer_id: '',
            address_district: '', address_taluka: '', address_village: '', address_pincode: '',
        },

        allDistricts: @json($districts ?? []),
        get districtNames() { return Object.keys(this.allDistricts); },
        talukaList: [],
        updateTalukas() {
            this.form.address_taluka = '';
            this.talukaList = this.form.address_district ? (this.allDistricts[this.form.address_district] || []) : [];
        },

        lands: [{ district: '', taluka: '', village: '', gat_no: '', khate_no: '', area: '', _talukas: [] }],
        addLand() { this.lands.push({ district: '', taluka: '', village: '', gat_no: '', khate_no: '', area: '', _talukas: [] }); },
        removeLand(idx) { if (this.lands.length > 1) this.lands.splice(idx, 1); },
        fetchLandTalukas(idx) {
            const land = this.lands[idx];
            land.taluka = '';
            land._talukas = land.district ? (this.allDistricts[land.district] || []) : [];
        },

        previewPhoto(e) {
            const file = e.target.files[0];
            if (file) {
                this.photoFile = file;
                this.photoPreview = URL.createObjectURL(file);
            }
        },

        goStep(s) {
            if (s === 2 && (!this.form.applicant_name || !this.form.name_english || !this.form.mobile)) {
                this.formError = 'Please fill required fields: नाव (मराठी), Name (English), and मोबाईल नंबर.';
                return;
            }
            if (s === 2 && this.form.mobile.length !== 10) {
                this.formError = 'Mobile number must be exactly 10 digits.';
                return;
            }
            this.formError = null;
            this.step = s;
            window.scrollTo({ top: document.getElementById('form-section').offsetTop - 80, behavior: 'smooth' });
        },

        async submitForm() {
            this.submitting = true;
            this.formError = null;

            try {
                const fd = new FormData();
                Object.entries(this.form).forEach(([k, v]) => { if (v) fd.append(k, v); });
                if (this.photoFile) fd.append('photo', this.photoFile);

                this.lands.forEach((l, i) => {
                    ['district', 'taluka', 'village', 'gat_no', 'khate_no', 'area'].forEach(f => {
                        fd.append(`land[${i}][${f}]`, l[f] || '');
                    });
                });

                const resp = await fetch('{{ route("farmer-card-public.store") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: fd,
                });
                const data = await resp.json();

                if (!resp.ok) {
                    this.formError = data.message || 'Form submission failed. Please check your details.';
                    this.submitting = false;
                    return;
                }

                // FREE mode: direct download
                if (data.free) {
                    this.orderSuccess = true;
                    this.successTxn = data.transaction_no;
                    window.scrollTo({ top: document.getElementById('form-section').offsetTop - 80, behavior: 'smooth' });
                }
                // Paid mode: open Razorpay
                else if (data.razorpay_order_id && data.razorpay_key) {
                    this.openRazorpay(data);
                } else {
                    // Razorpay not configured fallback
                    this.orderSuccess = true;
                    this.successTxn = data.transaction_no;
                }
            } catch (err) {
                this.formError = 'Network error. Please try again.';
                console.error(err);
            }
            this.submitting = false;
        },

        openRazorpay(data) {
            const self = this;
            const options = {
                key: data.razorpay_key,
                amount: data.amount,
                currency: 'INR',
                name: 'SETU Suvidha',
                description: 'Farmer ID Card',
                order_id: data.razorpay_order_id,
                prefill: { contact: data.mobile },
                theme: { color: '#16a34a' },
                handler: async function(response) {
                    try {
                        const vResp = await fetch('{{ route("farmer-card-public.verify") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                transaction_no: data.transaction_no,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                            }),
                        });
                        const vData = await vResp.json();
                        if (vData.success) {
                            self.orderSuccess = true;
                            self.successTxn = vData.transaction_no;
                            window.scrollTo({ top: document.getElementById('form-section').offsetTop - 80, behavior: 'smooth' });
                        } else {
                            self.formError = vData.message || 'Payment verification failed.';
                        }
                    } catch (err) {
                        self.formError = 'Payment verification error. Please contact support with your Transaction Number: ' + data.transaction_no;
                    }
                },
                modal: {
                    ondismiss: function() {
                        self.formError = 'Payment cancelled. Your order has been saved. Transaction: ' + data.transaction_no;
                        self.submitting = false;
                    }
                }
            };
            const rzp = new Razorpay(options);
            rzp.open();
        },

        async lookupCard() {
            if (!this.lookupTxn.trim()) return;
            this.lookupLoading = true;
            this.lookupResult = null;
            try {
                const resp = await fetch('{{ route("farmer-card-public.lookup") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ transaction_no: this.lookupTxn.trim() }),
                });
                this.lookupResult = await resp.json();
            } catch (err) {
                this.lookupResult = { found: false, message: 'Network error. Please try again.' };
            }
            this.lookupLoading = false;
        },
    }
}
</script>
@endsection
