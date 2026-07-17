@extends('layouts.app')
@section('title', 'Free Mahasarathi Card Crop & Print Online | SETU Suvidha')
@section('description', 'Crop your Mahasarathi Card PDF to standard CR-80 card size for printing. 100% free, browser-based, no sign-up required. Best for CSC centers.')
@section('keywords', 'free mahasarathi card crop, mahasarathi print online, csc mahasarathi crop, maharashtra divyang card print, setu suvidha')

@push('styles')
<style>
    @media screen {
        .print-area { display: none !important; }
    }
    @media print {
        html, body { background-color: white !important; color: black !important; margin: 0; padding: 0; }
        .dark body, .dark html { background-color: white !important; }
        * { background-color: transparent !important; color: black !important; text-shadow: none !important; box-shadow: none !important; }
        
        .no-print { display: none !important; }
        .print-area { display: block !important; width: 100%; }
        @page { size: A4 portrait; margin: 0; }
        
        .print-card-row {
            display: flex;
            justify-content: center;
            margin-top: 15mm;
            page-break-inside: avoid;
        }
        
        .print-card {
            width: 90.0mm;
            height: 60.0mm;
            box-sizing: border-box;
            border: 1px dashed #ccc; /* Cut line */
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-12 gap-6 no-print">
    
    {{-- Left Ad Column --}}
    <div class="hidden lg:block lg:col-span-2 space-y-4">
        @include('card-generator.partials.left-ad')
    </div>
    
    {{-- Main Tool Column --}}
    <div class="lg:col-span-8 w-full space-y-6" x-data="mahasarathiCropper()">
        
        {{-- Mobile Top Ads --}}
        <div class="block lg:hidden mb-6">
            @include('card-generator.partials.right-ad')
        </div>
    
    {{-- Header --}}
    <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 pb-4">
        <a href="{{ route('card-generator.index') }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="id-card" class="w-6 h-6 text-blue-500"></i>
                Crop Mahasarathi Card Online
            </h1>
            <p class="text-sm text-gray-500">Auto-crop and print perfect CR-80 sized Mahasarathi Cards from PDF.</p>
        </div>
    </div>

    {{-- Upload Zone --}}
    <div x-show="!hasProcessedFile && !isProcessing" 
         class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl p-12 text-center bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-900 transition cursor-pointer relative"
         @dragover.prevent="$el.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20')"
         @dragleave.prevent="$el.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20')"
         @drop.prevent="handleDrop($event); $el.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20')"
         @click="$refs.fileInput.click()">
         
        <input type="file" x-ref="fileInput" @change="handleFileSelect" accept="application/pdf" class="hidden">
        
        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="upload-cloud" class="w-8 h-8"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Upload or Drop Mahasarathi Card PDF here</h3>
        <p class="text-sm text-gray-500 mb-6">Supported format: PDF only</p>
        
        <button class="btn-primary text-sm bg-blue-600 hover:bg-blue-700 border-blue-600">Browse Files</button>
        
        <div class="mt-6 flex items-center justify-center gap-1.5 text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 py-1.5 px-3 rounded-full mx-auto w-max">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
            100% private — processed in your browser, never uploaded
        </div>
    </div>

    {{-- Processing State --}}
    <div x-show="isProcessing" class="text-center py-12">
        <i data-lucide="loader-2" class="w-10 h-10 animate-spin text-blue-500 mx-auto mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white" x-text="processStatus">Processing PDF...</h3>
    </div>

    {{-- Preview Section --}}
    <div x-show="hasProcessedFile" class="space-y-6" style="display: none;">
        
        {{-- Toolbar --}}
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white dark:bg-gray-900 p-4 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="flex gap-2">
                <button @click="reset()" class="btn-outline text-sm">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-1"></i> Upload Another
                </button>
            </div>
            <div class="flex gap-2">
                <button @click="downloadImages()" class="btn-outline text-sm text-green-600 border-green-600 hover:bg-green-50">
                    <i data-lucide="download" class="w-4 h-4 mr-1"></i> Save Images (PNG)
                </button>
                <button @click="window.print()" class="btn-primary text-sm bg-blue-600 hover:bg-blue-700 border-blue-600">
                    <i data-lucide="printer" class="w-4 h-4 mr-1"></i> Print (A4)
                </button>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 p-3 rounded-lg text-sm flex gap-2 items-start">
            <i data-lucide="info" class="w-5 h-5 shrink-0"></i>
            <p><strong>Print Instructions:</strong> When printing, make sure to set <strong>Margins to "None"</strong> and <strong>Scale to "Default" or "100%"</strong> in the print dialog. This ensures the exact 90mm x 60mm size.</p>
        </div>

        {{-- Canvases (Preview) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <h4 class="font-medium text-gray-700 dark:text-gray-300 text-center">Front Side</h4>
                <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden bg-gray-100 flex justify-center p-4">
                    <canvas id="frontCanvas" class="max-w-full h-auto shadow-sm" style="max-height: 250px;"></canvas>
                </div>
            </div>
            
            <div class="space-y-2">
                <h4 class="font-medium text-gray-700 dark:text-gray-300 text-center">Back Side</h4>
                <div class="border border-gray-300 dark:border-gray-700 rounded-lg overflow-hidden bg-gray-100 flex justify-center p-4">
                    <canvas id="backCanvas" class="max-w-full h-auto shadow-sm" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Password Modal --}}
    <div x-show="showPasswordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-xl w-full max-w-md m-4 border border-gray-200 dark:border-gray-800">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">PDF is Password Protected</h3>
            <p class="text-sm text-gray-500 mb-4">
                Enter password to unlock. <br>
                <span class="text-blue-600">Hint: Usually DDMMYYYY (Date of Birth)</span>
            </p>
            <input type="password" x-model="pdfPassword" @keyup.enter="submitPassword" class="form-input w-full mb-4" placeholder="Enter password...">
            <div class="flex justify-end gap-2">
                <button @click="reset" class="btn-outline text-sm">Cancel</button>
                <button @click="submitPassword" class="btn-primary text-sm bg-blue-600">Unlock</button>
            </div>
        </div>
    </div>

    </div>
    
    {{-- Right Ad Column --}}
    <div class="hidden lg:block lg:col-span-2 space-y-4">
        @include('card-generator.partials.right-ad')
    </div>
</div>

{{-- Print Layout Area (Hidden on screen) --}}
<div class="print-area" id="printLayoutContainer">
    {{-- Populated by JS for printing --}}
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
</script>
<script>
    // Inject dynamic crop settings from DB
    window.CropSettings = @json($settings);
</script>
<script src="{{ asset('js/mahasarathi-cropper.js') }}"></script>
@endpush
