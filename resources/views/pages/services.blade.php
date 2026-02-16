@extends('layouts.app')
@section('title', 'सेवा — SETU Suvidha')
@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 py-16 lg:py-20">
    <div class="absolute inset-0 opacity-20 dark:opacity-10" style="background-image: radial-gradient(circle, #f59e0b 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-4 py-1.5 rounded-full text-xs font-semibold mb-5">
            <i data-lucide="grid-3x3" class="w-3.5 h-3.5"></i> 20+ Services Available
        </div>
        <h1 class="text-3xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-4">आमच्या <span class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">सेवा</span></h1>
        <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg">SETU Suvidha वर उपलब्ध सर्व सरकारी फॉर्म्स, आधार सेवा, CRM मॉड्यूल्स आणि बरंच काही — एकाच प्लॅटफॉर्मवर</p>
    </div>
</section>

{{-- ═══ Aadhaar Services Hub ═══ --}}
<section class="py-16 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center">
                <i data-lucide="fingerprint" class="w-5 h-5 text-orange-600"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Aadhaar Services Hub</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">UIDAI प्रमाणित आधार फॉर्म्स — A4 प्रिंट रेडी</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'user', 'title' => 'Adult Form (18+)', 'desc' => 'Form 1 — वयस्क नोंदणी फॉर्म, संपूर्ण 7 सेक्शन', 'price' => '₹5', 'color' => 'orange'],
                ['icon' => 'user-round', 'title' => 'Minor Form (5-18)', 'desc' => 'Form 3 — अल्पवयीन नोंदणी फॉर्म', 'price' => '₹5', 'color' => 'blue'],
                ['icon' => 'baby', 'title' => 'Child Form (0-5)', 'desc' => 'Form 5 — बालक नोंदणी, पालक माहिती', 'price' => '₹5', 'color' => 'pink'],
                ['icon' => 'map-pin', 'title' => 'Update / Address', 'desc' => 'Certificate Form — पत्ता बदल प्रमाणपत्र', 'price' => '₹5', 'color' => 'green'],
            ] as $s)
            <div class="group bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-{{ $s['color'] }}-100 dark:bg-{{ $s['color'] }}-900/30 flex items-center justify-center">
                        <i data-lucide="{{ $s['icon'] }}" class="w-6 h-6 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
                    </div>
                    <span class="text-xs font-bold text-{{ $s['color'] }}-600 bg-{{ $s['color'] }}-100 dark:bg-{{ $s['color'] }}-900/30 px-2.5 py-1 rounded-full">{{ $s['price'] }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $s['title'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ Government Forms ═══ --}}
<section class="py-16 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">सरकारी फॉर्म्स</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">सर्व आवश्यक प्रमाणपत्रे आणि अर्ज — तात्काळ A4 प्रिंट</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'file-text', 'title' => 'हमीपत्र', 'desc' => 'Disclaimer / Guarantee Bond — जामीनदार हमीपत्र', 'price' => '₹2', 'color' => 'blue'],
                ['icon' => 'shield', 'title' => 'स्वयंघोषणापत्र', 'desc' => 'Self-Declaration Form — शपथ टेक्स्टसह', 'price' => '₹2', 'color' => 'green'],
                ['icon' => 'alert-triangle', 'title' => 'तक्रार नोंदणी', 'desc' => 'Grievance Registration — प्रकार + वर्णन', 'price' => '₹2', 'color' => 'yellow'],
                ['icon' => 'file-plus', 'title' => 'नवीन अर्ज', 'desc' => 'New Application — सामान्य अर्ज फॉर्म', 'price' => '₹2', 'color' => 'purple'],
                ['icon' => 'badge-check', 'title' => 'जात पडताळणी', 'desc' => 'Caste Validity — जात + उपजात फॉर्म', 'price' => '₹3', 'color' => 'teal'],
                ['icon' => 'landmark', 'title' => 'उत्पन्न प्रमाणपत्र', 'desc' => 'Income Certificate — 4 प्रिंट फॉरमॅट', 'price' => '₹5', 'color' => 'pink'],
                ['icon' => 'scale', 'title' => 'राजपत्र नमुना', 'desc' => 'Gazette Notice — मराठी, English, 7/12', 'price' => '₹5', 'color' => 'emerald'],
                ['icon' => 'leaf', 'title' => 'शेतकरी ओळखपत्र', 'desc' => 'Farmer ID Card — QR कोडसह', 'price' => '₹3', 'color' => 'lime'],
            ] as $s)
            <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-{{ $s['color'] }}-100 dark:bg-{{ $s['color'] }}-900/30 flex items-center justify-center">
                        <i data-lucide="{{ $s['icon'] }}" class="w-6 h-6 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
                    </div>
                    <span class="text-xs font-bold text-{{ $s['color'] }}-600 bg-{{ $s['color'] }}-100 dark:bg-{{ $s['color'] }}-900/30 px-2.5 py-1 rounded-full">{{ $s['price'] }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $s['title'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ CRM Modules + Other Tools ═══ --}}
<section class="py-16 bg-white dark:bg-gray-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                <i data-lucide="database" class="w-5 h-5 text-purple-600"></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">CRM मॉड्यूल्स आणि टूल्स</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">ग्राहक व्यवस्थापन, फोटो मेकर, वॉलेट आणि रिपोर्ट्स</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => 'credit-card', 'title' => 'पॅन कार्ड CRM', 'desc' => 'PAN Card Application — ट्रॅकिंग, स्टेटस, फिल्टर्स', 'price' => 'CRM', 'color' => 'indigo'],
                ['icon' => 'user-check', 'title' => 'वोटर ID CRM', 'desc' => 'Voter ID Application — CRUD + स्टेटस ट्रॅकिंग', 'price' => 'CRM', 'color' => 'sky'],
                ['icon' => 'hard-hat', 'title' => 'बांधकाम कामगार', 'desc' => 'Construction Worker — नोंदणी, किट, स्कॉलरशिप', 'price' => 'CRM', 'color' => 'orange'],
                ['icon' => 'camera', 'title' => 'पासपोर्ट फोटो मेकर', 'desc' => 'Passport Size Photo — ऑटो क्रॉप, A4 शीट', 'price' => '₹5', 'color' => 'rose'],
                ['icon' => 'wallet', 'title' => 'प्रीपेड वॉलेट', 'desc' => 'Razorpay रिचार्ज — ऑटो डिडक्शन प्रणाली', 'price' => 'Free', 'color' => 'emerald'],
                ['icon' => 'bar-chart-3', 'title' => 'रिपोर्ट्स & Analytics', 'desc' => 'Revenue, Usage, CRM — सर्व डेटा एकत्र', 'price' => 'Free', 'color' => 'violet'],
                ['icon' => 'download', 'title' => 'CSV Export', 'desc' => 'PAN, Voter, Bandkam — एक्सपोर्ट तुमचा डेटा', 'price' => 'Free', 'color' => 'cyan'],
                ['icon' => 'settings', 'title' => 'Admin Panel', 'desc' => 'व्यवस्थापन — युजर्स, सेटिंग्स, बिलिंग', 'price' => 'Admin', 'color' => 'gray'],
            ] as $s)
            <div class="group bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-{{ $s['color'] }}-100 dark:bg-{{ $s['color'] }}-900/30 flex items-center justify-center">
                        <i data-lucide="{{ $s['icon'] }}" class="w-6 h-6 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400"></i>
                    </div>
                    <span class="text-xs font-bold text-{{ $s['color'] }}-600 bg-{{ $s['color'] }}-100 dark:bg-{{ $s['color'] }}-900/30 px-2.5 py-1 rounded-full">{{ $s['price'] }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $s['title'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-gradient-to-r from-amber-600 to-orange-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">सर्व सेवा एकाच ठिकाणी!</h2>
        <p class="text-white/80 text-lg mb-8 max-w-2xl mx-auto">मोफत नोंदणी करा आणि आजच वापर सुरू करा — ₹2 पासून शुल्क</p>
        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-white text-amber-600 font-bold px-8 py-4 rounded-xl hover:bg-amber-50 transition text-lg shadow-lg">
            <i data-lucide="rocket" class="w-5 h-5"></i> मोफत नोंदणी करा
        </a>
    </div>
</section>
@endsection
