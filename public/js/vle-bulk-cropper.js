document.addEventListener('alpine:init', () => {
    Alpine.data('bulkCropper', () => ({
        cardType: 'aadhaar',
        files: [],
        currentProcessingIndex: 0,
        processedCount: 0,
        processedPairs: [], // Array of { front: dataUrl, back: dataUrl }
        
        isProcessing: false,
        isSaving: false,
        isLoadingQueue: false,
        
        savedQueue: [],
        selectedIds: [],
        filterType: 'all',
        
        // Password handling
        showPasswordInput: false,
        currentLockedFileName: '',
        pdfPassword: '',
        resolvePasswordPromise: null,
        
        // Canvas for hidden processing
        processCanvas: null,

        // Manual Cropping State
        showManualModal: false,
        manualCropStep: 'front', // 'front' or 'back'
        manualCropper: null,
        tempManualFrontDataUrl: null,
        
        init() {
            this.processCanvas = document.createElement('canvas');
            this.loadQueue();
        },
        
        async loadQueue() {
            this.isLoadingQueue = true;
            try {
                const res = await fetch('/vle/card-generator/queue');
                const data = await res.json();
                this.savedQueue = data.cards;
            } catch (e) {
                console.error("Failed to load queue");
            }
            this.isLoadingQueue = false;
        },
        
        get filteredQueue() {
            if (this.filterType === 'all') return this.savedQueue;
            return this.savedQueue.filter(c => c.card_type === this.filterType);
        },
        
        toggleSelectAll(e) {
            if (e.target.checked) {
                this.selectedIds = this.filteredQueue.map(c => String(c.id));
            } else {
                this.selectedIds = [];
            }
        },
        
        async handleFilesSelect(e) {
            if (e.target.files && e.target.files.length > 0) {
                this.files = Array.from(e.target.files);
                
                if (this.cardType === 'custom') {
                    // Start manual process immediately for the first file
                    const file = this.files[0];
                    await this.initManualCropper(file);
                    // Clear file input so they can select same file again if needed
                    if (this.$refs.fileInput) this.$refs.fileInput.value = '';
                    this.files = []; 
                }
            }
        },
        
        removePair(index) {
            this.processedPairs.splice(index, 1);
        },
        
        reset() {
            this.files = [];
            this.processedPairs = [];
            this.processedCount = 0;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
            this.showPasswordInput = false;
        },
        
        async startProcessing() {
            if (this.files.length === 0 || this.cardType === 'custom') return;
            
            this.isProcessing = true;
            this.processedCount = 0;
            
            for (let i = 0; i < this.files.length; i++) {
                this.currentProcessingIndex = i;
                const file = this.files[i];
                
                try {
                    const pair = await this.processFile(file);
                    if (pair) {
                        this.processedPairs.push(pair);
                    }
                } catch (e) {
                    console.error("Failed processing file:", file.name, e);
                }
                
                this.processedCount++;
            }
            
            this.files = []; // Clear current file queue so they don't re-process
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
            
            this.isProcessing = false;
        },
        
        async saveAllToQueue() {
            if (this.processedPairs.length === 0) return;
            
            this.isSaving = true;
            
            for (const pair of this.processedPairs) {
                try {
                    const res = await fetch('/vle/card-generator/queue', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            card_type: pair.custom ? 'custom' : this.cardType,
                            front_image: pair.front,
                            back_image: pair.back
                        })
                    });
                    
                    const data = await res.json();
                    if (data.success) {
                        this.savedQueue.unshift(data.card);
                    }
                } catch (e) {
                    console.error("Failed to save pair to queue");
                }
            }
            
            this.processedPairs = [];
            this.isSaving = false;
        },
        
        async deleteSavedCard(id) {
            if (!confirm('Are you sure you want to delete this saved card?')) return;
            
            try {
                await fetch(`/vle/card-generator/queue/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                this.savedQueue = this.savedQueue.filter(c => c.id !== id);
                this.selectedIds = this.selectedIds.filter(selId => selId !== id);
            } catch (e) {
                console.error("Failed to delete card");
            }
        },

        // MANUAL CROP LOGIC
        async initManualCropper(file, password = '') {
            let dataUrl = null;
            
            if (file.type === 'application/pdf') {
                try {
                    const arrayBuffer = await file.arrayBuffer();
                    const pdf = await pdfjsLib.getDocument({ data: arrayBuffer, password: password }).promise;
                    const page = await pdf.getPage(1);
                    const scale = 3.0; // High res for manual crop
                    const viewport = page.getViewport({ scale: scale });
                    
                    this.processCanvas.width = viewport.width;
                    this.processCanvas.height = viewport.height;
                    const ctx = this.processCanvas.getContext('2d');
                    
                    await page.render({ canvasContext: ctx, viewport: viewport }).promise;
                    dataUrl = this.processCanvas.toDataURL('image/jpeg', 1.0);
                } catch (error) {
                    if (error.name === 'PasswordException') {
                        // Ask user for password
                        this.currentLockedFileName = file.name;
                        this.pdfPassword = '';
                        this.showPasswordInput = true;
                        
                        // Wait for user to input password
                        const newPassword = await new Promise((res) => {
                            this.resolvePasswordPromise = res;
                        });
                        
                        this.showPasswordInput = false;
                        
                        if (newPassword === null) {
                            return; // User cancelled
                        } else {
                            // Retry with password
                            return this.initManualCropper(file, newPassword);
                        }
                    } else {
                        console.error("Could not read PDF for manual crop", error);
                        alert("Failed to read PDF. Try an image instead.");
                        return;
                    }
                }
            } else if (file.type.startsWith('image/')) {
                dataUrl = await new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.onload = (e) => resolve(e.target.result);
                    reader.readAsDataURL(file);
                });
            } else {
                alert("Unsupported file format for custom crop.");
                return;
            }

            if (!dataUrl) return;

            this.manualCropStep = 'front';
            this.tempManualFrontDataUrl = null;
            this.showManualModal = true;
            
            // Wait for alpine x-show to render
            setTimeout(() => {
                const imgElement = document.getElementById('manualCropImage');
                imgElement.src = dataUrl;
                
                if (this.manualCropper) {
                    this.manualCropper.destroy();
                }
                
                this.manualCropper = new Cropper(imgElement, {
                    aspectRatio: NaN, // Free size crop
                    viewMode: 1,
                    autoCropArea: 0.8,
                    background: false,
                });
            }, 100);
        },

        confirmManualCrop() {
            if (!this.manualCropper) return;

            // Extract the cropped canvas and resize to standard 1012x638
            const croppedCanvas = this.manualCropper.getCroppedCanvas({
                width: 1012,
                height: 638,
                fillColor: '#fff',
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });

            const dataUrl = croppedCanvas.toDataURL('image/jpeg', 1.0);

            if (this.manualCropStep === 'front') {
                this.tempManualFrontDataUrl = dataUrl;
                this.manualCropStep = 'back';
            } else {
                // Back step complete
                this.processedPairs.push({
                    front: this.tempManualFrontDataUrl,
                    back: dataUrl,
                    custom: true
                });
                this.closeManualModal();
            }
        },

        closeManualModal() {
            this.showManualModal = false;
            this.manualCropStep = 'front';
            this.tempManualFrontDataUrl = null;
            if (this.manualCropper) {
                this.manualCropper.destroy();
                this.manualCropper = null;
            }
        },
        
        // AUTO CROP LOGIC
        async processFile(file, password = '') {
            return new Promise(async (resolve, reject) => {
                try {
                    const arrayBuffer = await file.arrayBuffer();
                    
                    const loadTask = pdfjsLib.getDocument({
                        data: arrayBuffer,
                        password: password
                    });
                    
                    const pdf = await loadTask.promise;
                    const page = await pdf.getPage(1);
                    
                    // Render high-res
                    const scale = 3.0;
                    const viewport = page.getViewport({ scale: scale });
                    
                    this.processCanvas.width = viewport.width;
                    this.processCanvas.height = viewport.height;
                    
                    const ctx = this.processCanvas.getContext('2d');
                    
                    await page.render({
                        canvasContext: ctx,
                        viewport: viewport
                    }).promise;
                    
                    // Now we have the rendered PDF on processCanvas.
                    // Crop it using Admin settings for current cardType
                    const frontDataUrl = this.cropCardSide(this.processCanvas, true);
                    const backDataUrl = this.cropCardSide(this.processCanvas, false);
                    
                    resolve({ front: frontDataUrl, back: backDataUrl });
                    
                } catch (error) {
                    if (error.name === 'PasswordException') {
                        // Ask user for password
                        this.currentLockedFileName = file.name;
                        this.pdfPassword = '';
                        this.showPasswordInput = true;
                        
                        // Wait for user to input password
                        const newPassword = await new Promise((res) => {
                            this.resolvePasswordPromise = res;
                        });
                        
                        this.showPasswordInput = false;
                        
                        if (newPassword === null) {
                            // User cancelled or skipped
                            resolve(null);
                        } else {
                            // Retry with password
                            try {
                                const result = await this.processFile(file, newPassword);
                                resolve(result);
                            } catch (e) {
                                reject(e);
                            }
                        }
                    } else {
                        console.error('Error processing PDF:', error);
                        reject(error);
                    }
                }
            });
        },
        
        processNextLockedFile() {
            if (this.resolvePasswordPromise) {
                this.resolvePasswordPromise(this.pdfPassword);
                this.resolvePasswordPromise = null;
            }
        },
        
        cropCardSide(sourceCanvas, isFront) {
            // Target output dimensions (300 DPI for 90x60mm approx)
            const cardWidth = 1012; 
            const cardHeight = 638;
            
            const targetCanvas = document.createElement('canvas');
            targetCanvas.width = cardWidth;
            targetCanvas.height = cardHeight;
            const ctx = targetCanvas.getContext('2d');
            
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, cardWidth, cardHeight);
            
            const typeKey = this.cardType + (isFront ? '_front' : '_back');
            
            // Fallback settings just in case
            let settings = { x: 0, y: 0, width: 50, height: 20 };
            
            if (window.CropSettings && window.CropSettings[typeKey]) {
                settings = window.CropSettings[typeKey];
            }
            
            const sourceX = (settings.x / 100) * sourceCanvas.width;
            const sourceY = (settings.y / 100) * sourceCanvas.height;
            const sourceCardWidth = (settings.width / 100) * sourceCanvas.width;
            const sourceCardHeight = (settings.height / 100) * sourceCanvas.height;
            
            ctx.drawImage(
                sourceCanvas, 
                sourceX, sourceY, sourceCardWidth, sourceCardHeight,
                0, 0, cardWidth, cardHeight
            );
            
            return targetCanvas.toDataURL('image/jpeg', 1.0);
        },
        
        setupPrintLayout() {
            const printContainer = document.getElementById('printLayoutContainer');
            printContainer.innerHTML = '';
            
            const grid = document.createElement('div');
            grid.className = 'print-grid';
            
            // Filter savedQueue by selectedIds (casting to string to avoid type mismatch)
            const stringSelectedIds = this.selectedIds.map(String);
            const cardsToPrint = this.savedQueue.filter(c => stringSelectedIds.includes(String(c.id)));
            
            cardsToPrint.forEach(card => {
                const row = document.createElement('div');
                row.className = 'print-card-pair';
                
                const imgFront = document.createElement('img');
                imgFront.src = card.front_url;
                imgFront.className = 'print-card';
                
                const imgBack = document.createElement('img');
                imgBack.src = card.back_url;
                imgBack.className = 'print-card';
                
                row.appendChild(imgFront);
                row.appendChild(imgBack);
                grid.appendChild(row);
            });
            
            printContainer.appendChild(grid);
        },
        
        async printSelected() {
            if (this.selectedIds.length === 0) return;
            
            this.setupPrintLayout();
            
            const stringSelectedIds = this.selectedIds.map(String);
            const firstCard = this.savedQueue.find(c => String(c.id) === stringSelectedIds[0]);
            const cardType = firstCard ? firstCard.card_type : 'mixed';
            
            // Log to database via AJAX
            try {
                await fetch('/vle/card-generator/record', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        card_type: cardType,
                        quantity: stringSelectedIds.length,
                        printed_ids: stringSelectedIds
                    })
                });
                
                // Do NOT delete locally anymore as requested. They will stay for 48 hours.
                this.selectedIds = [];
                
            } catch (e) {
                console.error("Failed to log print record:", e);
            }
            
            // Trigger browser print
            setTimeout(() => {
                window.print();
            }, 500);
        }
    }));
});
