@extends('layouts.app')
@section('title', 'फायदे — SETU Suvidha')
@section('content')
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">SETU Suvidha चे फायदे</h1>
            <p class="text-gray-500 dark:text-gray-400">तुमच्या ई-सेवा कामकाजासाठी सर्वोत्तम प्लॅटफॉर्म</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach([
                ['icon' => 'zap', 'title' => 'तात्काळ फॉर्म प्रिंट', 'desc' => 'फॉर्म भरा आणि लगेच A4 प्रिंट घ्या. कोणत्याही विलंबाशिवाय तुमच्या ग्राहकांना सेवा द्या.'],
                ['icon' => 'shield-check', 'title' => '100% सुरक्षित डेटा', 'desc' => 'SSL एन्क्रिप्शन, CSRF प्रोटेक्शन, bcrypt पासवर्ड हॅशिंग आणि Razorpay HMAC-SHA256 सिग्नेचर.'],
                ['icon' => 'wallet', 'title' => 'प्रीपेड वॉलेट', 'desc' => 'एटॉमिक ट्रान्झॅक्शन सिस्टम — Razorpay UPI/कार्ड/नेट बँकिंग द्वारे रिचार्ज.'],
                ['icon' => 'palette', 'title' => '24 कस्टम थीम्स', 'desc' => 'डॅशबोर्डचा रंग तुमच्या आवडीनुसार बदला — डार्क/लाइट मोड सपोर्ट.'],
                ['icon' => 'smartphone', 'title' => 'मोबाइल-फर्स्ट डिझाइन', 'desc' => 'प्रत्येक पृष्ठ सर्व डिव्हाइसवर परफेक्ट — Responsive + Glassmorphism UI.'],
                ['icon' => 'users', 'title' => 'CRM व्यवस्थापन', 'desc' => 'पॅन कार्ड, वोटर ID आणि बांधकाम कामगार — सर्व ग्राहक डेटा एकाच ठिकाणी.'],
            ] as $b)
            <div class="flex gap-4 bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800">
                <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $b['icon'] }}" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $b['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $b['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
