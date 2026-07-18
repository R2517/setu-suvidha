@extends('layouts.app')
@section('title', 'Free Aadhaar, PAN, ABHA Card Crop & Print Online | SETU Suvidha')
@section('description', 'Free browser-based tools to auto-crop e-Aadhaar, PAN, ABHA, E-Shram, and Mahasarathi PDFs. Perfect for VLEs, CSCs, and Cyber Cafes.')
@section('keywords', 'free aadhaar crop, pvc card print online, csc id, bulk card generator, setu suvidha, e-pan crop, abha crop, mahasarathi print, eshram crop')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 animate-fade-in-up">
    {{-- Hero Section --}}
    <div class="text-center space-y-4 py-8">
        <h1 class="text-3xl md:text-5xl font-bold text-gray-900 dark:text-white leading-tight">
            Free Government <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600">Card Crop & Print</span> Tool
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Crop your e-Aadhaar Card and e-PAN Card from PDF to perfect ISO standard size (85.6mm Ã— 54mm) and print instantly.
        </p>
        
        <div class="flex flex-wrap justify-center gap-3 pt-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm font-medium">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i> 100% Free
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-medium">
                <i data-lucide="user-x" class="w-4 h-4"></i> No Login Required
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-sm font-medium">
                <i data-lucide="infinity" class="w-4 h-4"></i> Unlimited Usage
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-sm font-medium">
                <i data-lucide="shield-check" class="w-4 h-4"></i> Privacy First - Browser Only
            </span>
        </div>
    </div>

    {{-- Promotional Banner --}}
    <div class="bg-gradient-to-r from-amber-600 to-amber-500 rounded-2xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 mb-8 w-full max-w-4xl mx-auto">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-16 -mt-16 transform rotate-45"></div>
        <div class="relative z-10 w-full md:w-2/3">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 rounded-full text-xs font-bold uppercase tracking-wider mb-3">
                <i data-lucide="star" class="w-3 h-3 text-amber-200"></i> For VLEs & Shop Owners
            </div>
            <h2 class="text-2xl md:text-3xl font-bold mb-2">Printing 10+ Cards Daily?</h2>
            <p class="text-amber-100 max-w-xl text-sm md:text-base">
                Unlock our <strong>PRO Bulk Card Generator</strong>. Upload 20+ PDFs at once, auto-crop, save them in your 48-hour queue, and print multiple cards on a single A4 sheet perfectly!
            </p>
        </div>
        <div class="relative z-10 flex-shrink-0 flex flex-col gap-3 w-full md:w-1/3 md:items-end">
            <a href="{{ route('register') }}" class="px-6 py-3 bg-white text-amber-700 hover:bg-gray-50 font-bold rounded-xl shadow-md transition text-center whitespace-nowrap">
                Create Free Account
            </a>
            <a href="{{ route('login') }}" class="text-sm font-medium text-amber-100 hover:text-white text-center underline">
                Already have an account? Login
            </a>
        </div>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        {{-- Aadhaar Card --}}
        <a href="{{ route('card-generator.aadhaar') }}" class="glass-card p-6 rounded-2xl group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                NEW
            </div>
            <div class="w-14 h-14 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="credit-card" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Aadhaar Card Crop</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Upload e-Aadhaar PDF. Auto-crops Front and Back. Print perfectly on A4 or save as high-quality image. Works with password PDFs.
            </p>
            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500 font-semibold text-sm group-hover:gap-3 transition-all">
                Try Aadhaar Crop <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </a>

        {{-- PAN Card --}}
        <a href="{{ route('card-generator.pan-card') }}" class="glass-card p-6 rounded-2xl group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                NEW
            </div>
            <div class="w-14 h-14 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="id-card" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">PAN Card Crop</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Upload e-PAN PDF. Auto-detects and crops the Front and Back sides to exact CR-80 dimensions.
            </p>
            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500 font-semibold text-sm group-hover:gap-3 transition-all">
                Try PAN Card Crop <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </a>

        {{-- ABHA Card --}}
        <a href="{{ route('card-generator.abha') }}" class="glass-card p-6 rounded-2xl group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                NEW
            </div>
            <div class="w-14 h-14 rounded-xl bg-green-100 dark:bg-green-900/40 text-green-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="heart-pulse" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">ABHA Card Crop</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Upload ABHA PDF. Auto-crops Front and Back. Print perfectly on A4 or save as high-quality image.
            </p>
            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500 font-semibold text-sm group-hover:gap-3 transition-all">
                Try ABHA Crop <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </a>

        {{-- E-Shram Card --}}
        <a href="{{ route('card-generator.eshram') }}" class="glass-card p-6 rounded-2xl group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                NEW
            </div>
            <div class="w-14 h-14 rounded-xl bg-orange-100 dark:bg-orange-900/40 text-orange-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="hard-hat" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">E-Shram Card Crop</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Upload E-Shram PDF. Crops the card automatically. Works securely within your browser.
            </p>
            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500 font-semibold text-sm group-hover:gap-3 transition-all">
                Try E-Shram Crop <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </a>

        {{-- Mahasarathi Card --}}
        <a href="{{ route('card-generator.mahasarathi') }}" class="glass-card p-6 rounded-2xl group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                NEW
            </div>
            <div class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/40 text-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="map" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Mahasarathi Card Crop</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Crop your Mahasarathi card perfectly for printing. Fast, free, and private.
            </p>
            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500 font-semibold text-sm group-hover:gap-3 transition-all">
                Try Mahasarathi Crop <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </a>

        {{-- Ayushman Bharat Card --}}
        <a href="{{ route('card-generator.ayushman') }}" class="glass-card p-6 rounded-2xl group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                NEW
            </div>
            <div class="w-14 h-14 rounded-xl bg-teal-100 dark:bg-teal-900/40 text-teal-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="heart" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Ayushman Bharat Crop</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Crop Ayushman Bharat card PDF instantly to standard CR-80 dimensions. Free and secure.
            </p>
            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500 font-semibold text-sm group-hover:gap-3 transition-all">
                Try Ayushman Crop <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </a>

        {{-- Voter ID Card --}}
        <a href="{{ route('card-generator.voter') }}" class="glass-card p-6 rounded-2xl group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-4 right-4 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm animate-pulse">
                NEW
            </div>
            <div class="w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="contact-2" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Voter ID Crop</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                Upload Voter ID PDF. Auto-detects and crops perfectly for A4 printing.
            </p>
            <div class="flex items-center gap-2 text-amber-600 dark:text-amber-500 font-semibold text-sm group-hover:gap-3 transition-all">
                Try Voter ID Crop <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </a>
    </div>

    {{-- SEO Content Section --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 md:p-8 mt-12">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">About SetuSuvidha Card Generator</h2>
        
        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-400 space-y-4">
            <p>Welcome to the ultimate solution for Common Service Center (CSC) operators, Village Level Entrepreneurs (VLEs), and Cyber CafÃ© owners. Our Free Government Card Crop & Print Tool is designed to save you time and money.</p>
            
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mt-6 mb-2">How It Works</h3>
            <ol class="list-decimal list-inside space-y-2">
                <li><strong>Upload:</strong> Select your downloaded e-Aadhaar or e-PAN PDF file.</li>
                <li><strong>Auto-Crop:</strong> Our intelligent browser-based system instantly detects the card boundaries and crops it to the standard ISO CR-80 size (85.6mm Ã— 54mm).</li>
                <li><strong>Print or Save:</strong> Print directly on A4 paper with perfectly aligned cards, or download as a high-resolution 300 DPI image.</li>
            </ol>

            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mt-6 mb-2">100% Privacy Guaranteed</h3>
            <p>Unlike other platforms, we do <strong>NOT</strong> upload your sensitive documents to our servers. All PDF decryption, rendering, and cropping happens entirely within your web browser. This ensures complete privacy and data security for your customers.</p>
        </div>
    </div>
</div>
@endsection
