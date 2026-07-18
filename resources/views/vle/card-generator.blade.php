@extends('layouts.app')
@section('title', 'Bulk Card Generator | SETU Suvidha')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
    /* Print optimizations */
    @media screen {
        .print-area { display: none !important; }
    }
    @media print {
        html, body { background-color: white !important; color: black !important; margin: 0; padding: 0; }
        .dark body, .dark html { background-color: white !important; }
        * { background-color: transparent !important; color: black !important; text-shadow: none !important; box-shadow: none !important; }
        
        .no-print { display: none !important; }
        .print-area { 
            display: block !important;
            width: 100%; 
        }
        
        /* A4 Page dimensions */
        @page { size: A4 portrait; margin: 0; }
        
        .print-grid {
            display: flex;
            flex-direction: column;
            gap: 3mm;
            width: 100%;
            align-items: center;
            padding-top: 5mm; /* Small padding at top of page */
        }
        
        .print-card-pair {
            display: flex;
            justify-content: center;
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
<div class="max-w-6xl mx-auto space-y-6 no-print" x-data="bulkCropper()">
    
    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i data-lucide="printer" class="w-6 h-6 text-amber-500"></i>
                Bulk Card Generator (Print PVC)
            </h1>
            <p class="text-sm text-gray-500">Upload multiple PDFs at once. Auto-crops and arranges them for A4 printing.</p>
        </div>
        <div>
            <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full border border-amber-200">
                PRO TOOL
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Settings Panel --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 h-fit space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">1. Select Card Type</label>
                <select x-model="cardType" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    <option value="aadhaar">Aadhaar Card</option>
                    <option value="pan">PAN Card</option>
                    <option value="abha">ABHA Card</option>
                    <option value="eshram">E-Shram Card</option>
                    <option value="mahasarathi">Mahasarathi Card</option>
                    <option value="ayushman">Ayushman Bharat Card</option>
                    <option value="voter">Voter ID Card</option>
                    <option value="custom">Custom Scan (Manual Crop)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2" x-text="cardType === 'custom' ? '2. Upload Image or PDF (One at a time)' : '2. Upload PDFs (Select Multiple)'"></label>
                <input type="file" :multiple="cardType !== 'custom'" :accept="cardType === 'custom' ? 'image/*,application/pdf' : 'application/pdf'" @change="handleFilesSelect" x-ref="fileInput" class="block w-full text-sm text-gray-500 dark:text-gray-400
                    file:mr-3 file:py-2 file:px-4
                    file:rounded-xl file:border-0
                    file:text-sm file:font-semibold
                    file:bg-amber-50 file:text-amber-700 dark:file:bg-amber-900 dark:file:text-amber-300
                    hover:file:bg-amber-100 dark:hover:file:bg-amber-800
                "/>
            </div>

            <div x-show="showPasswordInput" style="display: none;" class="p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800">
                <label class="block text-sm font-bold text-red-700 dark:text-red-400 mb-2">Password Required for <span x-text="currentLockedFileName"></span></label>
                <input type="password" x-model="pdfPassword" @keyup.enter="processNextLockedFile" placeholder="Enter password..." class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 mb-3">
                <button @click="processNextLockedFile" class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 rounded-lg transition">Unlock File</button>
            </div>
            
            <button @click="startProcessing" x-show="files.length > 0 && !isProcessing && !showPasswordInput && cardType !== 'custom'" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                <i data-lucide="zap" class="w-5 h-5"></i>
                Auto-Crop <span x-text="files.length"></span> File(s)
            </button>
            
            <div x-show="isProcessing" class="text-center py-4 bg-gray-50 dark:bg-gray-900 rounded-xl">
                <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-amber-500 mx-auto mb-2"></i>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Processing <span x-text="processedCount"></span> / <span x-text="files.length"></span>...</p>
            </div>
        </div>

        {{-- Preview & Queue Panel --}}
        <div class="md:col-span-2 space-y-4">
            
            {{-- Current Session (Unsaved) --}}
            <div x-show="processedPairs.length > 0" class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-amber-600 dark:text-amber-500 flex items-center gap-2">
                        <i data-lucide="scissors" class="w-5 h-5"></i>
                        New Cropped Cards (<span x-text="processedPairs.length"></span>)
                    </h3>
                    <button @click="saveAllToQueue" class="px-5 py-2 text-sm font-bold text-white bg-green-600 hover:bg-green-700 shadow-md rounded-lg transition flex items-center gap-2" :disabled="isSaving">
                        <i data-lucide="save" class="w-4 h-4" x-show="!isSaving"></i>
                        <i data-lucide="loader-2" class="w-4 h-4 animate-spin" x-show="isSaving"></i>
                        <span x-text="isSaving ? 'Saving...' : 'Save to Queue'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="(pair, index) in processedPairs" :key="index">
                        <div class="bg-gray-100 dark:bg-gray-900 p-4 rounded-xl text-center space-y-3 relative group">
                            <button @click="removePair(index)" class="absolute top-2 right-2 p-1.5 bg-red-100 text-red-600 rounded-lg opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-red-200">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Unsaved Card <span x-text="index + 1"></span></h4>
                            <img :src="pair.front" class="w-full max-w-[200px] mx-auto rounded shadow-sm border border-gray-300 dark:border-gray-700" alt="Front">
                            <img :src="pair.back" class="w-full max-w-[200px] mx-auto rounded shadow-sm border border-gray-300 dark:border-gray-700" alt="Back">
                        </div>
                    </template>
                </div>
            </div>

            {{-- Saved Queue --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                    <h3 class="font-bold text-blue-600 dark:text-blue-400 flex items-center gap-2">
                        <i data-lucide="archive" class="w-5 h-5"></i>
                        My Print Queue (<span x-text="filteredQueue.length"></span>)
                    </h3>
                    
                    <div class="flex gap-3 items-center">
                        {{-- Filter --}}
                        <select x-model="filterType" class="text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm py-1.5 px-3">
                            <option value="all">All Cards</option>
                            <option value="aadhaar">Aadhaar</option>
                            <option value="pan">PAN</option>
                            <option value="abha">ABHA</option>
                            <option value="eshram">E-Shram</option>
                            <option value="mahasarathi">Mahasarathi</option>
                            <option value="ayushman">Ayushman Bharat</option>
                            <option value="voter">Voter ID</option>
                            <option value="custom">Custom Card</option>
                        </select>
                        
                        <button @click="printSelected" x-show="selectedIds.length > 0" class="px-5 py-2 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md rounded-lg transition flex items-center gap-2">
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            Print Selected (<span x-text="selectedIds.length"></span>)
                        </button>
                    </div>
                </div>

                {{-- Select All Row --}}
                <div x-show="!isLoadingQueue && filteredQueue.length > 0" class="flex items-center gap-2 mb-3 bg-gray-50 dark:bg-gray-900/50 p-2 rounded-lg border border-gray-200 dark:border-gray-700">
                    <input type="checkbox" id="selectAll" @change="toggleSelectAll($event)" :checked="selectedIds.length === filteredQueue.length && filteredQueue.length > 0" class="w-5 h-5 rounded border-gray-300 text-blue-600 cursor-pointer">
                    <label for="selectAll" class="text-sm font-bold text-gray-700 dark:text-gray-300 cursor-pointer">Select All / Deselect All</label>
                </div>

                <div x-show="isLoadingQueue" class="text-center py-8">
                    <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-blue-500 mx-auto"></i>
                </div>

                <div x-show="!isLoadingQueue && filteredQueue.length === 0" class="text-center py-8 text-gray-400">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 opacity-50"></i>
                    <p class="text-sm">Your print queue is empty for this filter.</p>
                </div>

                <div x-show="!isLoadingQueue && filteredQueue.length > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="card in filteredQueue" :key="card.id">
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl text-center space-y-3 relative border-2 transition" :class="selectedIds.includes(card.id) ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-transparent'">
                            
                            <div class="absolute top-2 left-2 z-10">
                                <input type="checkbox" :value="card.id" x-model="selectedIds" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 cursor-pointer">
                            </div>
                            
                            <button @click="deleteSavedCard(card.id)" class="absolute top-2 right-2 p-1.5 bg-white dark:bg-gray-800 text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition shadow-sm border border-gray-200 dark:border-gray-700">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            
                            <div class="pt-4">
                                <span class="inline-block px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-[10px] font-bold rounded uppercase mb-2" x-text="card.card_type"></span>
                                <img :src="card.front_url" class="w-full max-w-[200px] mx-auto rounded shadow-sm border border-gray-300 dark:border-gray-700 mb-2" alt="Front">
                                <img :src="card.back_url" class="w-full max-w-[200px] mx-auto rounded shadow-sm border border-gray-300 dark:border-gray-700" alt="Back">
                            </div>
                            <p class="text-[10px] text-gray-400">Expires: <span x-text="card.expires_at"></span></p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    {{-- Manual Crop Modal --}}
    <div x-show="showManualModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeManualModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title" x-text="manualCropStep === 'front' ? 'Crop Front Side' : 'Crop Back Side'"></h3>
                            <div class="w-full bg-gray-100 dark:bg-gray-900 min-h-[400px] max-h-[60vh] flex items-center justify-center relative overflow-hidden rounded border border-gray-300 dark:border-gray-700">
                                <img id="manualCropImage" class="max-w-full block" style="max-height: 60vh;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="confirmManualCrop" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        <span x-text="manualCropStep === 'front' ? 'Crop Front & Next' : 'Crop Back & Finish'"></span>
                    </button>
                    <button type="button" @click="closeManualModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Print Layout Area (Hidden on screen) --}}
<div class="print-area" id="printLayoutContainer">
    {{-- Populated by JS for printing --}}
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    window.CropSettings = @json($settings);
</script>
<script src="{{ asset('js/vle-bulk-cropper.js') }}?v={{ time() }}"></script>
@endpush
