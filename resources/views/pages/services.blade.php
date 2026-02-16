@extends('layouts.app')
@section('title', 'सेवा — SETU Suvidha')
@section('content')
<section class="py-20 bg-white dark:bg-gray-950">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">आमच्या सेवा</h1>
            <p class="text-gray-500 dark:text-gray-400">SETU Suvidha वर उपलब्ध सर्व सरकारी फॉर्म्स आणि सेवा</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['title' => 'हमीपत्र (Disclaimer)', 'desc' => 'जामीनदार हमीपत्र — 7 फील्ड्स, A4 प्रिंट', 'price' => '₹2', 'icon' => 'file-text'],
                ['title' => 'स्वयंघोषणापत्र', 'desc' => 'Self-Declaration फॉर्म — शपथ टेक्स्टसह', 'price' => '₹2', 'icon' => 'shield'],
                ['title' => 'तक्रार नोंदणी', 'desc' => 'Grievance Registration — प्रकार + वर्णन', 'price' => '₹2', 'icon' => 'alert-triangle'],
                ['title' => 'नवीन अर्ज', 'desc' => 'New Application — सामान्य अर्ज फॉर्म', 'price' => '₹2', 'icon' => 'file-plus'],
                ['title' => 'जात पडताळणी', 'desc' => 'Caste Validity — जात + उपजात', 'price' => '₹3', 'icon' => 'badge-check'],
                ['title' => 'उत्पन्न प्रमाणपत्र', 'desc' => 'Income Certificate — 4 प्रिंट फॉरमॅट', 'price' => '₹5', 'icon' => 'landmark'],
                ['title' => 'राजपत्र मराठी', 'desc' => 'Gazette Notice मराठी — नाव बदल', 'price' => '₹5', 'icon' => 'scale'],
                ['title' => 'राजपत्र English', 'desc' => 'Gazette Notice English — Name Change', 'price' => '₹5', 'icon' => 'scale'],
                ['title' => 'राजपत्र ७/१२ शपथपत्र', 'desc' => '7/12 Affidavit — पत्ता बदल', 'price' => '₹5', 'icon' => 'scale'],
                ['title' => 'शेतकरी ओळखपत्र', 'desc' => 'Farmer ID Card — QR कोडसह', 'price' => '₹3', 'icon' => 'leaf'],
                ['title' => 'पॅन कार्ड CRM', 'desc' => 'PAN Card Application Management', 'price' => 'CRM', 'icon' => 'credit-card'],
                ['title' => 'बांधकाम कामगार CRM', 'desc' => 'Construction Worker Registration & Schemes', 'price' => 'CRM', 'icon' => 'hard-hat'],
            ] as $s)
            <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 border border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                        <i data-lucide="{{ $s['icon'] }}" class="w-5 h-5 text-amber-600"></i>
                    </div>
                    <span class="text-xs font-bold text-amber-600 bg-amber-100 dark:bg-amber-900/30 px-2 py-0.5 rounded-full">{{ $s['price'] }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $s['title'] }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('register') }}" class="btn-primary text-base !px-8 !py-4"><i data-lucide="rocket" class="w-5 h-5"></i> मोफत सुरुवात करा</a>
        </div>
    </div>
</section>
@endsection
