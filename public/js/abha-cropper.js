document.addEventListener('alpine:init', () => {
    Alpine.data('abhaCropper', () => ({
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
                
                if (error.name === 'PasswordException') {
                    this.isProcessing = false;
                    this.showPasswordModal = true;
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
            
            const scale = 4.16; // Approx 300 DPI
            const viewport = page.getViewport({ scale: scale });
            
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
            
            const frontCanvas = document.getElementById('frontCanvas');
            const backCanvas = document.getElementById('backCanvas');
            this.extractCard(canvas, frontCanvas, true);
            this.extractCard(canvas, backCanvas, false);
            
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
            
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, targetCanvas.width, targetCanvas.height);
            
            let frontSettings = { x: 7.5, y: 15.0, width: 85.0, height: 53.6 }; // defaults
            let backSettings = { x: 7.5, y: 15.0, width: 85.0, height: 53.6 }; // defaults
            
            if (window.CropSettings && window.CropSettings['abha_front']) {
                frontSettings = window.CropSettings['abha_front'];
            }
            if (window.CropSettings && window.CropSettings['abha_back']) {
                backSettings = window.CropSettings['abha_back'];
            }
            
            const activeSettings = isFront ? frontSettings : backSettings;
            
            const sourceX = (activeSettings.x / 100) * sourceCanvas.width;
            const sourceY = (activeSettings.y / 100) * sourceCanvas.height;
            const sourceCardWidth = (activeSettings.width / 100) * sourceCanvas.width;
            const sourceCardHeight = (activeSettings.height / 100) * sourceCanvas.height;
            
            ctx.drawImage(
                sourceCanvas, 
                sourceX, sourceY, sourceCardWidth, sourceCardHeight,
                0, 0, targetCanvas.width, targetCanvas.height
            );
        },
        
        downloadImages() {
            const frontCanvas = document.getElementById('frontCanvas');
            const backCanvas = document.getElementById('backCanvas');
            
            const combinedCanvas = document.createElement('canvas');
            const ctx = combinedCanvas.getContext('2d');
            
            combinedCanvas.width = this.cardWidth;
            combinedCanvas.height = (this.cardHeight * 2) + 50;
            
            ctx.fillStyle = '#FFFFFF';
            ctx.fillRect(0, 0, combinedCanvas.width, combinedCanvas.height);
            
            ctx.drawImage(frontCanvas, 0, 0);
            ctx.drawImage(backCanvas, 0, this.cardHeight + 50);
            
            const link = document.createElement('a');
            link.download = 'ABHA_Card_Combined.png';
            link.href = combinedCanvas.toDataURL('image/png');
            link.click();
        },
        
        setupPrintLayout() {
            const printContainer = document.getElementById('printLayoutContainer');
            printContainer.innerHTML = '';
            
            const frontData = document.getElementById('frontCanvas').toDataURL('image/jpeg', 0.9);
            const backData = document.getElementById('backCanvas').toDataURL('image/jpeg', 0.9);
            
            const row = document.createElement('div');
            row.className = 'print-card-row';
            
            const imgFront = document.createElement('img');
            imgFront.src = frontData;
            imgFront.className = 'print-card';
            
            const imgBack = document.createElement('img');
            imgBack.src = backData;
            imgBack.className = 'print-card';
            
            row.appendChild(imgFront);
            row.appendChild(imgBack);
            printContainer.appendChild(row);
        }
    }));
});
