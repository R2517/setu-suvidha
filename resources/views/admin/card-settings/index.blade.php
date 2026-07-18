@extends('layouts.app')

@section('title', 'Card Cropper Settings')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <style>
        .cropper-container { max-width: 100%; max-height: 70vh; overflow: hidden; background-color: #f3f4f6; }
        #pdfCanvas { max-width: 100%; display: block; }
        
        /* Red Cropper Theme */
        .cropper-view-box { outline: 2px solid #ef4444 !important; outline-color: rgba(239, 68, 68, 0.75) !important; }
        .cropper-line { background-color: #ef4444 !important; }
        .cropper-point { background-color: #ef4444 !important; }
        .cropper-point.point-se { width: 10px; height: 10px; }
        .cropper-center::before, .cropper-center::after { background-color: #ef4444 !important; }
    </style>
@endpush

@section('content')
<div class="py-12" x-data="adminCardSettings()">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-2xl font-bold mb-4">🪪 Card Cropper Settings</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Upload a sample PDF to visually set the exact coordinates for the cropping engine.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Configured Cards Table (Sidebar) -->
                    <div class="space-y-4 md:col-span-1">
                        <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300">Configured Cards</h3>
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                            <table class="w-full text-sm">
                                <tbody>
                                    <template x-for="card in availableCards" :key="card.id">
                                        <tr class="border-b border-gray-100 dark:border-gray-700 cursor-pointer transition"
                                            :class="cardType === card.id ? 'bg-amber-50 dark:bg-amber-900/30' : 'hover:bg-gray-50 dark:hover:bg-gray-700'"
                                            @click="selectCardType(card.id)">
                                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100 flex items-center justify-between">
                                                <span x-text="card.name" class="font-medium"></span>
                                                <div class="flex items-center gap-2">
                                                    <span x-show="isCardConfigured(card.id)" class="text-[10px] px-1.5 py-0.5 rounded bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 font-bold border border-green-200 dark:border-green-800">CONFIGURED</span>
                                                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Controls & Uploader (Middle) -->
                    <div class="space-y-4 md:col-span-1">
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
                            <h3 class="text-sm font-bold mb-3 text-amber-600 dark:text-amber-400">
                                Editing: <span x-text="getCardName(cardType)"></span>
                            </h3>
                            
                            <div x-show="isCardConfigured(cardType) && !forceReCrop" class="text-center py-6">
                                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-3">
                                    <i data-lucide="check" class="w-6 h-6"></i>
                                </div>
                                <h4 class="font-bold text-gray-900 dark:text-white mb-2">Already Configured</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">This card has been set up previously.</p>
                                <button @click="forceReCrop = true" class="w-full bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-md px-3 py-2 text-sm font-medium border border-amber-200 transition">
                                    Re-Crop This Card
                                </button>
                            </div>
                            
                            <div x-show="!isCardConfigured(cardType) || forceReCrop">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Upload Sample PDF</label>
                                <input type="file" accept="application/pdf" x-ref="pdfInput" @change="handlePdfUpload" class="block w-full text-xs text-gray-500 dark:text-gray-400
                                    file:mr-2 file:py-1.5 file:px-3
                                    file:rounded-md file:border-0
                                    file:font-semibold
                                    file:bg-amber-50 file:text-amber-700 dark:file:bg-amber-900 dark:file:text-amber-300
                                    hover:file:bg-amber-100 dark:hover:file:bg-amber-800
                                "/>
                            </div>
                            
                            <div x-show="showPasswordInput" style="display: none;" class="mt-3">
                                <label class="block text-xs font-medium text-red-600 dark:text-red-400 mb-1">PDF Password</label>
                                <input type="password" x-model="pdfPassword" placeholder="Enter password" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white mb-2">
                                <button @click="loadPdf()" class="w-full bg-amber-600 text-white rounded-md px-3 py-1.5 hover:bg-amber-700 text-sm">Unlock</button>
                            </div>
                            
                                <h3 class="text-xs font-bold mb-2">Coordinates</h3>
                                <div class="grid grid-cols-2 gap-1 text-[11px] text-gray-600 dark:text-gray-400 mb-3 bg-gray-50 dark:bg-gray-900 p-2 rounded">
                                    <div>X: <span x-text="cropData.x + '%'"></span></div>
                                    <div>Y: <span x-text="cropData.y + '%'"></span></div>
                                    <div>W: <span x-text="cropData.width + '%'"></span></div>
                                    <div>H: <span x-text="cropData.height + '%'"></span></div>
                                </div>
                                
                                <button @click="saveSettings" :disabled="isSaving" class="w-full bg-green-600 text-white rounded-md px-3 py-2 hover:bg-green-700 disabled:opacity-50 text-sm font-medium">
                                    <span x-show="!isSaving">Save Coordinates</span>
                                    <span x-show="isSaving">Saving...</span>
                                </button>
                                <div x-show="saveMessage" x-text="saveMessage" class="mt-2 text-xs text-green-600 font-medium text-center"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Cropper Canvas -->
                    <div class="md:col-span-2">
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-4 flex justify-center items-center min-h-[400px] bg-gray-50 dark:bg-gray-800">
                            <div x-show="!isPdfLoaded && !isLoading" class="text-gray-400 flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <span>Upload a PDF to start</span>
                            </div>
                            
                            <div x-show="isLoading" class="text-amber-600 flex flex-col items-center">
                                <svg class="animate-spin w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="statusMessage"></span>
                            </div>
                            
                            <div class="cropper-container w-full relative" x-show="isPdfLoaded" id="canvasWrapper" style="display: none;">
                                <!-- Cropper Tools Overlaid on Canvas -->
                                <div class="absolute top-4 right-4 z-10 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm p-1.5 rounded-lg shadow-lg flex gap-1 border border-gray-200 dark:border-gray-700">
                                    <button @click="setMoveMode()" title="Move Tool (Hand)" class="p-2 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">
                                        <i data-lucide="hand" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="setCropMode()" title="Crop Tool (Select)" class="p-2 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">
                                        <i data-lucide="crop" class="w-4 h-4"></i>
                                    </button>
                                    <div class="w-px bg-gray-300 dark:bg-gray-700 my-1 mx-1"></div>
                                    <button @click="zoomIn()" title="Zoom In" class="p-2 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">
                                        <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                    </button>
                                    <button @click="zoomOut()" title="Zoom Out" class="p-2 rounded text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-800">
                                        <i data-lucide="zoom-out" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <canvas id="pdfCanvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    // Setup pdf.js worker
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
    
    // Pass existing settings from backend
    window.savedSettings = @json($settings);
</script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminCardSettings', () => ({
            availableCards: [
                { id: 'aadhaar_front', name: 'Aadhaar (Front)' },
                { id: 'aadhaar_back', name: 'Aadhaar (Back)' },
                { id: 'pan_front', name: 'PAN Card (Front)' },
                { id: 'pan_back', name: 'PAN Card (Back)' },
                { id: 'abha_front', name: 'ABHA Card (Front)' },
                { id: 'abha_back', name: 'ABHA Card (Back)' },
                { id: 'eshram_front', name: 'E-Shram (Front)' },
                { id: 'eshram_back', name: 'E-Shram (Back)' },
                { id: 'mahasarathi_front', name: 'Mahasarathi (Front)' },
                { id: 'mahasarathi_back', name: 'Mahasarathi (Back)' },
                { id: 'ayushman_front', name: 'Ayushman Bharat (Front)' },
                { id: 'ayushman_back', name: 'Ayushman Bharat (Back)' }
            ],
            cardType: 'aadhaar_front',
            currentFile: null,
            pdfPassword: '',
            showPasswordInput: false,
            isPdfLoaded: false,
            isLoading: false,
            isSaving: false,
            forceReCrop: false,
            statusMessage: '',
            saveMessage: '',
            
            pdfCanvas: null,
            cropper: null,
            
            cropData: {
                x: 0,
                y: 0,
                width: 0,
                height: 0
            },
            
            init() {
                this.pdfCanvas = document.getElementById('pdfCanvas');
                this.loadSavedSettings();
            },
            
            getCardName(id) {
                const card = this.availableCards.find(c => c.id === id);
                return card ? card.name : '';
            },
            
            isCardConfigured(id) {
                return window.savedSettings && window.savedSettings[id] && window.savedSettings[id].width_percent > 0;
            },
            
            selectCardType(id) {
                if (this.cardType === id) return;
                
                this.cardType = id;
                this.forceReCrop = false;
                
                // Clear current file & canvas when switching
                this.currentFile = null;
                if (this.$refs.pdfInput) this.$refs.pdfInput.value = '';
                this.isPdfLoaded = false;
                this.showPasswordInput = false;
                this.saveMessage = '';
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
                
                this.loadSavedSettings();
            },
            
            zoomIn() { if (this.cropper) { this.cropper.zoom(0.1); } },
            zoomOut() { if (this.cropper) { this.cropper.zoom(-0.1); } },
            setMoveMode() { if (this.cropper) { this.cropper.setDragMode('move'); } },
            setCropMode() { if (this.cropper) { this.cropper.setDragMode('crop'); } },
            
            loadSavedSettings() {
                if (window.savedSettings && window.savedSettings[this.cardType]) {
                    const saved = window.savedSettings[this.cardType];
                    this.cropData = {
                        x: saved.x_percent,
                        y: saved.y_percent,
                        width: saved.width_percent,
                        height: saved.height_percent
                    };
                    
                    if (this.cropper) {
                        this.applyCropDataToCropper();
                    }
                } else {
                    this.cropData = { x: 0, y: 0, width: 0, height: 0 };
                }
                this.saveMessage = '';
            },
            
            handlePdfUpload(e) {
                if (e.target.files && e.target.files.length > 0) {
                    this.currentFile = e.target.files[0];
                    this.pdfPassword = '';
                    this.loadPdf();
                }
            },
            
            async loadPdf() {
                if (!this.currentFile) return;
                
                this.isLoading = true;
                this.showPasswordInput = false;
                this.isPdfLoaded = false;
                this.statusMessage = 'Reading PDF...';
                
                try {
                    const arrayBuffer = await this.currentFile.arrayBuffer();
                    
                    const loadingTask = pdfjsLib.getDocument({
                        data: arrayBuffer,
                        password: this.pdfPassword
                    });
                    
                    const pdf = await loadingTask.promise;
                    this.statusMessage = 'Rendering Page...';
                    
                    const page = await pdf.getPage(1);
                    
                    // Render at high resolution
                    const scale = 3.0; 
                    const viewport = page.getViewport({ scale: scale });
                    
                    this.pdfCanvas.width = viewport.width;
                    this.pdfCanvas.height = viewport.height;
                    
                    const renderContext = {
                        canvasContext: this.pdfCanvas.getContext('2d'),
                        viewport: viewport
                    };
                    
                    await page.render(renderContext).promise;
                    
                    this.isLoading = false;
                    this.isPdfLoaded = true;
                    
                    // Allow UI to render canvas then init cropper
                    setTimeout(() => {
                        this.initCropper();
                    }, 100);
                    
                } catch (error) {
                    this.isLoading = false;
                    console.error(error);
                    if (error.name === 'PasswordException') {
                        this.showPasswordInput = true;
                    } else {
                        alert('Error loading PDF: ' + error.message);
                    }
                }
            },
            
            initCropper() {
                if (this.cropper) {
                    this.cropper.destroy();
                }
                
                // Removed aspectRatio to allow Free Size Crop
                this.cropper = new Cropper(this.pdfCanvas, {
                    viewMode: 1,
                    autoCropArea: 0.5,
                    ready: () => {
                        this.applyCropDataToCropper();
                    },
                    crop: (event) => {
                        // Calculate percentages
                        const imgData = this.cropper.getImageData();
                        
                        // event.detail gives absolute pixels in natural image size
                        this.cropData.x = ((event.detail.x / imgData.naturalWidth) * 100).toFixed(4);
                        this.cropData.y = ((event.detail.y / imgData.naturalHeight) * 100).toFixed(4);
                        this.cropData.width = ((event.detail.width / imgData.naturalWidth) * 100).toFixed(4);
                        this.cropData.height = ((event.detail.height / imgData.naturalHeight) * 100).toFixed(4);
                    }
                });
            },
            
            applyCropDataToCropper() {
                if (!this.cropper || this.cropData.width == 0) return;
                
                const imgData = this.cropper.getImageData();
                
                // Convert percentages back to pixels
                const x = (this.cropData.x / 100) * imgData.naturalWidth;
                const y = (this.cropData.y / 100) * imgData.naturalHeight;
                const width = (this.cropData.width / 100) * imgData.naturalWidth;
                const height = (this.cropData.height / 100) * imgData.naturalHeight;
                
                this.cropper.setData({ x, y, width, height });
            },
            
            async saveSettings() {
                this.isSaving = true;
                this.saveMessage = '';
                
                try {
                    const response = await fetch('/admin/card-settings', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            card_type: this.cardType,
                            x_percent: this.cropData.x,
                            y_percent: this.cropData.y,
                            width_percent: this.cropData.width,
                            height_percent: this.cropData.height
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.saveMessage = 'Settings saved successfully!';
                        // Update local cache
                        if (!window.savedSettings) window.savedSettings = {};
                        window.savedSettings[this.cardType] = {
                            x_percent: this.cropData.x,
                            y_percent: this.cropData.y,
                            width_percent: this.cropData.width,
                            height_percent: this.cropData.height
                        };
                        
                        setTimeout(() => this.saveMessage = '', 3000);
                    } else {
                        alert('Error saving settings.');
                    }
                } catch (error) {
                    console.error(error);
                    alert('Server error occurred.');
                } finally {
                    this.isSaving = false;
                }
            }
        }));
    });
</script>
@endpush
