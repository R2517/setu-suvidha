document.addEventListener('alpine:init', () => {
    Alpine.data('aadhaarCropper', () => ({
        isProcessing: false,
        hasProcessedFile: false,
        showPasswordModal: false,
        processStatus: '',
        pdfPassword: '',
        currentFile: null,
        
        // Dimensions (300 DPI for 85.6mm x 54mm)
        cardWidth: 1012,
        cardHeight: 638,
        
        init() {
            // Setup Alpine component
        },
        
        handleDrop(e) {
            if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                this.processFile(e.dataTransfer.files[0]);
            }
        },
        
        handleFileSelect(e) {
            if (e.target.files && e.target.files.length > 0) {
                this.processFile(e.target.files[0]);
            }
        },
        
        reset() {
            this.isProcessing = false;
            this.hasProcessedFile = false;
            this.showPasswordModal = false;
            this.pdfPassword = '';
            this.currentFile = null;
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
            
            // Clear canvases
            const frontCanvas = document.getElementById('frontCanvas');
            const backCanvas = document.getElementById('backCanvas');
            if (frontCanvas) frontCanvas.getContext('2d').clearRect(0, 0, frontCanvas.width, frontCanvas.height);
            if (backCanvas) backCanvas.getContext('2d').clearRect(0, 0, backCanvas.width, backCanvas.height);
        },
        
        async processFile(file) {
            if (file.type !== 'application/pdf') {
                alert('Please upload a valid PDF file.');
                return;
            }
            
            this.currentFile = file;
            this.isProcessing = true;
            this.processStatus = 'Reading PDF...';
            this.showPasswordModal = false;
            
            try {
                const arrayBuffer = await file.arrayBuffer();
                await this.loadAndCropPdf(arrayBuffer, this.pdfPassword);
            } catch (error) {
                console.error("PDF load error:", error);
                
                // Check if password protected
                if (error.name === 'PasswordException') {
                    this.isProcessing = false;
                    this.showPasswordModal = true;
                    // Focus password input after alpine renders it
                    setTimeout(() => {
                        const pwdInput = document.querySelector('input[type="password"]');
                        if(pwdInput) pwdInput.focus();
                    }, 100);
                } else {
                    this.isProcessing = false;
                    alert('Error loading PDF: ' + error.message);
                }
            }
        },
        
        submitPassword() {
            if (!this.pdfPassword) return;
            this.processFile(this.currentFile);
        },
        
        async loadAndCropPdf(arrayBuffer, password) {
            this.processStatus = 'Rendering PDF page...';
            
            const loadingTask = pdfjsLib.getDocument({
                data: arrayBuffer,
                password: password
            });
            
            const pdf = await loadingTask.promise;
            const page = await pdf.getPage(1);
            
            // Render at high resolution (approx 300 DPI)
            // A4 is 8.27 x 11.69 inches. 8.27 * 300 = 2481 pixels wide
            // Standard pdf.js scale 1.0 is 72 DPI. So scale = 300/72 = 4.16
            const scale = 4.16;
            const viewport = page.getViewport({ scale: scale });
            
            // Create off-screen canvas to render the whole page
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            
            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            
            await page.render(renderContext).promise;
            
            this.processStatus = 'Cropping cards...';
            
            // --- CROP LOGIC FOR AADHAAR ---
            // The e-Aadhaar PDF has the front card in the top half of the bottom section
            // and the back card in the bottom half.
            // These coordinates are approximate and based on standard e-Aadhaar format.
            // We'll crop a specific region and fit it into 1012x638 (85.6x54mm at 300dpi)
            
            // Find the center of the page horizontally
            const centerX = canvas.width / 2;
            // The cards are usually in the lower half of the A4 page
            const cardStartY = canvas.height * 0.65; // Approx start of the cut-out section
            
            // Card physical aspect ratio is 1.585 (85.6 / 54)
            // We want the output to be exactly 1012x638
            const frontCanvas = document.getElementById('frontCanvas');
            const backCanvas = document.getElementById('backCanvas');
            
            this.extractCard(canvas, frontCanvas, true);  // Front
            this.extractCard(canvas, backCanvas, false); // Back
            
            this.isProcessing = false;
            this.showPasswordModal = false;
            this.hasProcessedFile = true;
            
            // Generate print layout immediately so it's ready for print preview
            this.setupPrintLayout();
        },
        
        extractCard(sourceCanvas, targetCanvas, isFront) {
            targetCanvas.width = this.cardWidth;
            targetCanvas.height = this.cardHeight;
            const ctx = targetCanvas.getContext('2d');
            
            // Fill white background
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, targetCanvas.width, targetCanvas.height);
            
            // Fallback to default if settings not found
            let frontSettings = { x: 5.7, y: 65.6, width: 40.76, height: 18.18 };
            let backSettings = { x: 53.6, y: 65.6, width: 40.76, height: 18.18 };
            
            if (window.CropSettings && window.CropSettings['aadhaar_front']) {
                frontSettings = window.CropSettings['aadhaar_front'];
            }
            if (window.CropSettings && window.CropSettings['aadhaar_back']) {
                backSettings = window.CropSettings['aadhaar_back'];
            }
            
            const activeSettings = isFront ? frontSettings : backSettings;
            
            const sourceX = (activeSettings.x / 100) * sourceCanvas.width;
            const sourceY = (activeSettings.y / 100) * sourceCanvas.height;
            const sourceCardWidth = (activeSettings.width / 100) * sourceCanvas.width;
            const sourceCardHeight = (activeSettings.height / 100) * sourceCanvas.height;
            
            // Draw cropped area to target canvas
            ctx.drawImage(
                sourceCanvas, 
                sourceX, sourceY, sourceCardWidth, sourceCardHeight, // Source rect
                0, 0, targetCanvas.width, targetCanvas.height        // Target rect
            );
        },
        
        downloadImages() {
            // Create a combined canvas to save both front and back as one image
            const frontCanvas = document.getElementById('frontCanvas');
            const backCanvas = document.getElementById('backCanvas');
            
            const combinedCanvas = document.createElement('canvas');
            const ctx = combinedCanvas.getContext('2d');
            
            // Set combined size (Front on top, Back below with 50px gap)
            combinedCanvas.width = this.cardWidth;
            combinedCanvas.height = (this.cardHeight * 2) + 50;
            
            // Fill white background
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, combinedCanvas.width, combinedCanvas.height);
            
            // Draw Front
            ctx.drawImage(frontCanvas, 0, 0);
            
            // Draw Back
            ctx.drawImage(backCanvas, 0, this.cardHeight + 50);
            
            // Download
            const link = document.createElement('a');
            link.download = 'Aadhaar_Card_Combined.png';
            link.href = combinedCanvas.toDataURL('image/png');
            link.click();
        },
        
        downloadCanvas(canvasId, filename) {
            const canvas = document.getElementById(canvasId);
            const link = document.createElement('a');
            link.download = filename;
            link.href = canvas.toDataURL('image/png');
            link.click();
        },
        
        setupPrintLayout() {
            const printContainer = document.getElementById('printLayoutContainer');
            printContainer.innerHTML = ''; // Clear previous
            
            const frontData = document.getElementById('frontCanvas').toDataURL('image/jpeg', 0.9);
            const backData = document.getElementById('backCanvas').toDataURL('image/jpeg', 0.9);
            
            // Create print row
            const row = document.createElement('div');
            row.className = 'print-card-row';
            
            // Front image
            const imgFront = document.createElement('img');
            imgFront.src = frontData;
            imgFront.className = 'print-card';
            
            // Back image
            const imgBack = document.createElement('img');
            imgBack.src = backData;
            imgBack.className = 'print-card';
            
            row.appendChild(imgFront);
            row.appendChild(imgBack);
            printContainer.appendChild(row);
        }
    }));
});
