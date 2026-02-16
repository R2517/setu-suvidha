<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PassportPro — Passport Photo Maker | SETU Suvidha</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Roboto:wght@400;500;700&family=Oswald:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.2/dist/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.1/dist/umd/lucide.min.js"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #334155; color: #1e293b; min-height: 100vh; overflow: hidden; }
        [x-cloak] { display: none !important; }

        /* ═══ LAYOUT ═══ */
        .app-wrap { display: flex; height: 100vh; }
        .sidebar { width: 320px; min-width: 320px; background: #fff; display: flex; flex-direction: column; overflow-y: auto; box-shadow: 4px 0 24px rgba(0,0,0,0.12); z-index: 20; }
        .workspace { flex: 1; display: flex; align-items: center; justify-content: center; overflow: auto; padding: 24px; position: relative; }

        /* ═══ SIDEBAR ═══ */
        .sb-header { padding: 16px 20px; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; display: flex; align-items: center; justify-content: space-between; }
        .sb-header h1 { font-size: 18px; font-weight: 800; display: flex; align-items: center; gap: 8px; }
        .wallet-pill { background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; backdrop-filter: blur(4px); }
        .sb-section { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; }
        .sb-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .sb-select { width: 100%; padding: 9px 12px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 13px; font-weight: 500; background: #f8fafc; outline: none; transition: border-color 0.2s; cursor: pointer; }
        .sb-select:focus { border-color: #6366f1; }

        /* Person cards */
        .person-item { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #e2e8f0; cursor: pointer; transition: all 0.15s; margin-bottom: 6px; }
        .person-item:hover { border-color: #a5b4fc; background: #f0f0ff; }
        .person-item.filled { border-left: 3px solid #22c55e; }
        .p-thumb { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
        .p-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .p-thumb svg { width: 20px; height: 20px; color: #94a3b8; }
        .p-info { flex: 1; }
        .p-name { font-size: 13px; font-weight: 600; color: #1e293b; }
        .p-status { font-size: 10px; color: #94a3b8; }
        .p-status.ready { color: #22c55e; font-weight: 600; }
        .p-icon { width: 20px; height: 20px; color: #cbd5e1; }
        .p-icon.done { color: #22c55e; }

        /* Range slider */
        .range-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
        .range-label { font-size: 12px; font-weight: 600; color: #475569; min-width: 70px; }
        .range-val { font-size: 11px; font-weight: 700; color: #6366f1; min-width: 30px; text-align: right; }
        input[type="range"] { flex: 1; height: 6px; -webkit-appearance: none; appearance: none; background: #e2e8f0; border-radius: 3px; outline: none; }
        input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; width: 16px; height: 16px; border-radius: 50%; background: #6366f1; cursor: pointer; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }

        /* Toggle */
        .toggle-row { display: flex; align-items: center; justify-content: space-between; }
        .toggle-track { width: 40px; height: 22px; border-radius: 11px; background: #cbd5e1; position: relative; cursor: pointer; transition: background 0.2s; flex-shrink: 0; }
        .toggle-track.active { background: #6366f1; }
        .toggle-knob { width: 18px; height: 18px; border-radius: 50%; background: #fff; position: absolute; top: 2px; left: 2px; transition: transform 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.15); }
        .toggle-track.active .toggle-knob { transform: translateX(18px); }

        /* Text toolbar */
        .text-toolbar { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; margin-top: 10px; margin-bottom: 10px; }
        .tb-btn { width: 32px; height: 32px; border: 1.5px solid #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; font-weight: 700; background: #f8fafc; transition: all 0.15s; }
        .tb-btn:hover { border-color: #6366f1; }
        .tb-btn.active { background: #6366f1; color: #fff; border-color: #6366f1; }
        .tb-size { font-size: 13px; font-weight: 700; color: #1e293b; min-width: 22px; text-align: center; }
        .person-input { margin-bottom: 10px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fafafa; }
        .person-input label { font-size: 11px; font-weight: 600; color: #64748b; display: block; margin-bottom: 3px; }
        .person-input input { width: 100%; padding: 6px 8px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; outline: none; }
        .person-input input:focus { border-color: #6366f1; }

        /* Sticky bottom */
        .sb-bottom { padding: 14px 20px; border-top: 1px solid #e2e8f0; background: #fff; margin-top: auto; }
        .pay-btn { width: 100%; padding: 12px; border: none; border-radius: 12px; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: opacity 0.15s; }
        .pay-btn:hover { opacity: 0.92; }
        .pay-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        /* ═══ PAPER ═══ */
        .paper { background: #fff; position: relative; overflow: hidden; box-shadow: 0 8px 40px rgba(0,0,0,0.3); }
        .photo-slot { position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f1f5f9; }
        .photo-slot.bordered { border: 2px solid #000; }
        .photo-slot.dashed { border: 1px dashed #d1d5db; }
        .photo-slot img.final-img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .name-plate { position: absolute; bottom: 0; left: 0; right: 0; text-align: center; padding: 1px 2px; display: flex; flex-direction: column; line-height: 1.2; z-index: 2; }
        .name-plate span { display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        /* Watermark */
        .watermark-layer { position: absolute; inset: 0; display: flex; flex-wrap: wrap; justify-content: center; align-content: center; pointer-events: none; z-index: 10; overflow: hidden; }
        .wm-text { font: bold 14px 'Inter', sans-serif; color: rgba(100, 100, 255, 0.15); transform: rotate(-35deg); white-space: nowrap; padding: 15px 30px; }

        /* Crop modal */
        .crop-overlay { position: fixed; inset: 0; z-index: 50; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; }
        .crop-card { background: #fff; border-radius: 16px; padding: 20px; max-width: 500px; width: 90%; max-height: 90vh; overflow: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .crop-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 8px; }
        .crop-container { width: 100%; max-height: 400px; background: #1e293b; border-radius: 8px; overflow: hidden; }
        .crop-container img { max-width: 100%; display: block; }
        .crop-actions { display: flex; gap: 10px; margin-top: 14px; justify-content: flex-end; }
        .crop-actions button { padding: 9px 20px; border-radius: 10px; border: none; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s; }
        .btn-cancel { background: #f1f5f9; color: #475569; }
        .btn-cancel:hover { background: #e2e8f0; }
        .btn-apply { background: #4f46e5; color: #fff; }
        .btn-apply:hover { background: #4338ca; }

        /* Back link */
        .back-link { position: absolute; top: 16px; left: 16px; color: rgba(255,255,255,0.6); font-size: 12px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 4px; z-index: 5; }
        .back-link:hover { color: #fff; }

        /* Print - works for both Ctrl+P and JS processAndPrint() */
        @page { margin: 0; size: auto; }
        @media print {
            html, body {
                background: #fff !important; margin: 0 !important; padding: 0 !important;
                overflow: visible !important; width: 100% !important; height: auto !important;
            }
            .sidebar, .back-link, .crop-overlay, .watermark-layer, #fileInput { display: none !important; }
            .app-wrap {
                display: block !important; width: 100% !important;
                background: #fff !important; padding: 0 !important; margin: 0 !important;
                height: auto !important;
            }
            .workspace {
                display: block !important; width: 100% !important;
                background: #fff !important; padding: 0 !important; margin: 0 !important;
                text-align: center !important; overflow: visible !important; height: auto !important;
            }
            #paper {
                display: inline-block !important;
                box-shadow: none !important; margin: 0 auto !important;
                -webkit-print-color-adjust: exact; print-color-adjust: exact;
            }
            /* Print wrapper used by JS processAndPrint() */
            #printWrapper {
                display: block !important; width: 100% !important;
                background: #fff !important; margin: 0 !important; padding: 0 !important;
                text-align: center !important;
            }
            .photo-slot { background: #fff !important; }
            .photo-slot img.final-img { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
        #printWrapper { display: none; }

        /* Responsive */
        @media (max-width: 768px) {
            .app-wrap { flex-direction: column; overflow-y: auto; }
            .sidebar { width: 100%; min-width: 100%; max-height: 50vh; }
            .workspace { min-height: 50vh; padding: 12px; }
        }
    </style>
</head>
<body>
<div class="app-wrap" x-data="passportPro()" x-init="init()">

    {{-- ═══ LEFT SIDEBAR ═══ --}}
    <div class="sidebar">
        <div class="sb-header">
            <h1><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg> PassportPro</h1>
            <div class="wallet-pill">₹<span x-text="walletBalance"></span></div>
        </div>

        {{-- Paper Layout --}}
        <div class="sb-section">
            <div class="sb-label">📋 PAPER LAYOUT</div>
            <select class="sb-select" x-model="layout" @change="handleLayoutChange()">
                <option value="12">12 Photos (4×6 Portrait) [₹2]</option>
                <option value="8">8 Photos (4×6) - Bharti Mode [₹2]</option>
                <option value="48">48 Photos (A4 Size) [₹8]</option>
                <option value="1">1 Photo (Full Page) [₹2]</option>
            </select>
        </div>

        {{-- Persons & Photos --}}
        <div class="sb-section">
            <div class="sb-label">👥 PERSONS & PHOTOS</div>
            <select class="sb-select" x-model="numPersons" @change="handlePersonCountChange()" style="margin-bottom:10px">
                <template x-for="n in getAvailablePersonCounts()" :key="n">
                    <option :value="n" x-text="n + (n===1 ? ' Person' : ' Persons')"></option>
                </template>
            </select>
            <div style="max-height:240px;overflow-y:auto">
                <template x-for="(img, idx) in personImages.slice(0, parseInt(numPersons))" :key="'p'+idx">
                    <div class="person-item" :class="{ filled: personImages[idx] }" @click="triggerUpload(idx)">
                        <div class="p-thumb">
                            <template x-if="personImages[idx]"><img :src="personImages[idx]" alt=""></template>
                            <template x-if="!personImages[idx]"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></template>
                        </div>
                        <div class="p-info">
                            <div class="p-name" x-text="'Person ' + (idx+1)"></div>
                            <div class="p-status" :class="{ ready: personImages[idx] }" x-text="personImages[idx] ? 'Photo Ready' : 'Tap to Upload'"></div>
                        </div>
                        <svg class="p-icon" :class="{ done: personImages[idx] }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><template x-if="!personImages[idx]"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></template><template x-if="personImages[idx]"><path d="M20 6 9 17l-5-5"/></template></svg>
                    </div>
                </template>
            </div>
        </div>

        {{-- Adjustments --}}
        <div class="sb-section">
            <div class="sb-label">⚙️ ADJUSTMENTS</div>
            <div class="range-row">
                <span class="range-label">Brightness</span>
                <input type="range" min="0" max="200" x-model="brightness" @input="applyEffects()">
                <span class="range-val" x-text="(brightness - 100 >= 0 ? '+' : '') + (brightness - 100)"></span>
            </div>
            <div class="range-row">
                <span class="range-label">Contrast</span>
                <input type="range" min="0" max="200" x-model="contrast" @input="applyEffects()">
                <span class="range-val" x-text="(contrast - 100 >= 0 ? '+' : '') + (contrast - 100)"></span>
            </div>
        </div>

        {{-- Name & Date (Bharti Mode) --}}
        <div class="sb-section" x-show="layout === '8'" x-cloak>
            <div class="toggle-row" style="margin-bottom:10px">
                <span class="sb-label" style="margin-bottom:0">✏️ ADD NAME & DATE</span>
                <div class="toggle-track" :class="{ active: enableText }" @click="enableText = !enableText">
                    <div class="toggle-knob"></div>
                </div>
            </div>
            <div x-show="enableText" x-cloak>
                <div class="text-toolbar">
                    <div class="tb-btn" :class="{ active: isBold }" @click="toggleBold()"><b>B</b></div>
                    <div class="tb-btn" @click="changeFontSize(-1)">−</div>
                    <span class="tb-size" x-text="fontSize"></span>
                    <div class="tb-btn" @click="changeFontSize(1)">+</div>
                    <select class="sb-select" style="flex:1;padding:6px 8px;font-size:11px" x-model="bgColor">
                        <option value="white">White BG</option>
                        <option value="black">Black BG</option>
                        <option value="#fde68a">Yellow BG</option>
                    </select>
                </div>
                <select class="sb-select" style="margin-bottom:10px;font-size:11px" x-model="fontFamily">
                    <option value="'Roboto', sans-serif">Roboto</option>
                    <option value="'Inter', sans-serif">Inter</option>
                    <option value="'Oswald', sans-serif">Oswald</option>
                    <option value="Arial, sans-serif">Arial</option>
                </select>
                <template x-for="(_, idx) in personImages.slice(0, parseInt(numPersons))" :key="'txt'+idx">
                    <div class="person-input">
                        <label x-text="'Person ' + (idx+1) + ' Details'"></label>
                        <input type="text" :placeholder="'Full Name'" x-model="personNames[idx]" @input="renderGrid()">
                        <input type="date" x-model="personDates[idx]" @input="renderGrid()" style="margin-top:4px">
                    </div>
                </template>
            </div>
        </div>

        {{-- Cutting Guide --}}
        <div class="sb-section">
            <div class="toggle-row">
                <span style="font-size:12px;font-weight:600;color:#475569">✂️ Show Cutting Guide</span>
                <div class="toggle-track" :class="{ active: showBorder }" @click="showBorder = !showBorder">
                    <div class="toggle-knob"></div>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="sb-bottom">
            <div class="toggle-row" style="margin-bottom:12px">
                <span style="font-size:12px;font-weight:500;color:#475569">💾 Save/Download File</span>
                <div class="toggle-track" :class="{ active: wantDownload }" @click="wantDownload = !wantDownload">
                    <div class="toggle-knob"></div>
                </div>
            </div>
            <button class="pay-btn" @click="payAndPrint()" :disabled="!hasAnyPhoto()">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                Pay ₹<span x-text="currentCost"></span> & Print
            </button>
        </div>
    </div>

    {{-- ═══ RIGHT WORKSPACE ═══ --}}
    <div class="workspace" @contextmenu.prevent>
        <a href="{{ route('dashboard') }}" class="back-link">← Back to Dashboard</a>
        <div class="paper" id="paper" :style="paperStyle()">
            {{-- Grid slots --}}
            <template x-if="layout !== '1'">
                <div :style="gridStyle()">
                    <template x-for="(slot, si) in totalSlots" :key="'s'+si">
                        <div class="photo-slot" :class="showBorder ? 'bordered' : 'dashed'" :style="cellStyle()">
                            <template x-if="getSlotImage(si)">
                                <img class="final-img" :src="getSlotImage(si)" :style="filterStyle()" alt="">
                            </template>
                            <template x-if="getSlotImage(si) && enableText && layout==='8' && getSlotName(si)">
                                <div class="name-plate" :style="namePlateStyle()">
                                    <span x-text="getSlotName(si)"></span>
                                    <span x-text="getSlotDate(si)" style="font-size:0.85em;opacity:0.8"></span>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </template>
            {{-- Single photo --}}
            <template x-if="layout === '1'">
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;padding:0.5cm">
                    <div class="photo-slot" :class="showBorder ? 'bordered' : 'dashed'" style="width:100%;height:100%">
                        <template x-if="personImages[0]">
                            <img class="final-img" :src="personImages[0]" :style="filterStyle()" alt="">
                        </template>
                    </div>
                </div>
            </template>
            {{-- Watermark --}}
            <div class="watermark-layer" id="watermark">
                <template x-for="i in 60" :key="'wm'+i">
                    <div class="wm-text">PREVIEW / PAY TO PRINT</div>
                </template>
            </div>
        </div>
    </div>

    {{-- ═══ CROP MODAL ═══ --}}
    <div class="crop-overlay" x-show="showCropModal" x-cloak @click.self="closeModal()">
        <div class="crop-card">
            <h3>✂️ Crop Photo</h3>
            <div class="crop-container">
                <img id="cropImage" src="" alt="Crop">
            </div>
            <div class="crop-actions">
                <button class="btn-cancel" @click="closeModal()">Cancel</button>
                <button class="btn-apply" @click="saveCrop()">Apply Crop</button>
            </div>
        </div>
    </div>

    {{-- Hidden file input --}}
    <input type="file" id="fileInput" accept="image/*" style="display:none" @change="onFileSelected($event)">
</div>

<script>
function passportPro() {
    return {
        layout: '12',
        numPersons: 1,
        personImages: new Array(48).fill(null),
        personNames: new Array(48).fill(''),
        personDates: new Array(48).fill(new Date().toISOString().split('T')[0]),
        activePersonIndex: 0,
        brightness: 100,
        contrast: 100,
        showBorder: true,
        enableText: false,
        isBold: true,
        fontSize: 9,
        fontFamily: "'Roboto', sans-serif",
        bgColor: 'white',
        wantDownload: true,
        currentCost: 2,
        walletBalance: {{ $walletBalance ?? 0 }},
        cropper: null,
        showCropModal: false,
        totalSlots: 12,

        layouts: {
            '12': { w: '4in', h: '6in', cols: 3, rows: 4, cw: '30mm', ch: '35mm', slots: 12, gap: '0.5mm', pad: '0.2in' },
            '8':  { w: '6in', h: '4in', cols: 4, rows: 2, cw: '35mm', ch: '45mm', slots: 8,  gap: '0.5mm', pad: '0.2in' },
            '48': { w: '210mm', h: '297mm', cols: 6, rows: 8, cw: '30mm', ch: '35mm', slots: 48, gap: '0.5mm', pad: '0.2in' },
            '1':  { w: '4in', h: '6in', cols: 1, rows: 1, cw: '100%', ch: '100%', slots: 1, gap: '0', pad: '0.5cm' },
        },

        init() {
            this.handleLayoutChange();
        },

        handleLayoutChange() {
            let cfg = this.layouts[this.layout];
            this.totalSlots = cfg.slots;
            this.currentCost = this.layout === '48' ? 8 : 2;
            let avail = this.getAvailablePersonCounts();
            if (!avail.includes(parseInt(this.numPersons))) {
                this.numPersons = 1;
            }
        },

        handlePersonCountChange() {
            // Nothing extra needed — Alpine reactivity handles UI
        },

        getAvailablePersonCounts() {
            let total = this.layouts[this.layout].slots;
            let counts = [];
            for (let i = 1; i <= total; i++) {
                if (total % i === 0) counts.push(i);
            }
            return counts;
        },

        paperStyle() {
            let cfg = this.layouts[this.layout];
            return `width:${cfg.w};height:${cfg.h};`;
        },

        gridStyle() {
            let cfg = this.layouts[this.layout];
            return `display:grid;grid-template-columns:repeat(${cfg.cols},${cfg.cw});grid-template-rows:repeat(${cfg.rows},${cfg.ch});gap:${cfg.gap};padding:${cfg.pad};justify-content:center;align-content:center;width:100%;height:100%;`;
        },

        cellStyle() {
            let cfg = this.layouts[this.layout];
            return `width:${cfg.cw};height:${cfg.ch};`;
        },

        filterStyle() {
            return `filter:brightness(${this.brightness}%) contrast(${this.contrast}%);`;
        },

        namePlateStyle() {
            let bg = this.bgColor;
            let color = bg === 'black' ? '#fff' : '#000';
            return `background:${bg};color:${color};font-family:${this.fontFamily};font-size:${this.fontSize}px;font-weight:${this.isBold ? '700' : '400'};`;
        },

        getSlotImage(si) {
            let np = parseInt(this.numPersons);
            let spp = this.totalSlots / np;
            let pi = Math.floor(si / spp);
            return this.personImages[pi] || null;
        },

        getSlotName(si) {
            let np = parseInt(this.numPersons);
            let spp = this.totalSlots / np;
            let pi = Math.floor(si / spp);
            return this.personNames[pi] || '';
        },

        getSlotDate(si) {
            let np = parseInt(this.numPersons);
            let spp = this.totalSlots / np;
            let pi = Math.floor(si / spp);
            return this.personDates[pi] || '';
        },

        hasAnyPhoto() {
            return this.personImages.some(img => img !== null);
        },

        triggerUpload(index) {
            this.activePersonIndex = index;
            document.getElementById('fileInput').click();
        },

        onFileSelected(event) {
            let file = event.target.files[0];
            if (!file) return;
            let reader = new FileReader();
            reader.onload = (e) => {
                let img = document.getElementById('cropImage');
                img.src = e.target.result;
                this.showCropModal = true;
                this.$nextTick(() => {
                    if (this.cropper) { this.cropper.destroy(); this.cropper = null; }
                    this.cropper = new Cropper(img, {
                        aspectRatio: 3.5 / 4.5,
                        viewMode: 1,
                        autoCropArea: 0.95,
                    });
                });
            };
            reader.readAsDataURL(file);
            event.target.value = '';
        },

        saveCrop() {
            if (!this.cropper) return;
            let canvas = this.cropper.getCroppedCanvas({ width: 600, fillColor: '#fff' });
            let dataUrl = canvas.toDataURL('image/jpeg', 0.95);
            this.personImages[this.activePersonIndex] = dataUrl;
            // Force reactivity
            this.personImages = [...this.personImages];
            this.closeModal();
        },

        closeModal() {
            this.showCropModal = false;
            if (this.cropper) { this.cropper.destroy(); this.cropper = null; }
        },

        applyEffects() {
            // Reactive via filterStyle()
        },

        renderGrid() {
            // Reactive via template bindings
        },

        changeFontSize(delta) {
            this.fontSize = Math.max(6, Math.min(20, this.fontSize + delta));
        },

        toggleBold() {
            this.isBold = !this.isBold;
        },

        async payAndPrint() {
            if (!this.hasAnyPhoto()) return;

            let result = await Swal.fire({
                title: 'Payment Confirmation',
                html: `<b>₹${this.currentCost}</b> will be deducted from your wallet to Print & Save.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Pay & Print',
                cancelButtonText: 'Cancel',
            });

            if (!result.isConfirmed) return;

            try {
                let resp = await fetch('{{ route("passport-photo-maker.pay") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ layout_type: this.layout }),
                });

                let data = await resp.json();

                if (data.status !== 'success') {
                    Swal.fire('Error', data.message || 'Payment failed', 'error');
                    return;
                }

                // Update balance
                this.walletBalance = data.new_balance;
                this.processAndPrint();

            } catch (err) {
                Swal.fire('Error', 'Network error. Please try again.', 'error');
            }
        },

        async processAndPrint() {
            let wm = document.getElementById('watermark');
            let paper = document.getElementById('paper');
            let workspace = paper.parentElement;
            let appWrap = document.querySelector('.app-wrap');

            // Hide watermark, remove shadow
            wm.style.display = 'none';
            let origShadow = paper.style.boxShadow;
            paper.style.boxShadow = 'none';

            Swal.fire({ title: 'Processing...', html: 'Creating High Quality File', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            await new Promise(r => setTimeout(r, 800));

            try {
                let canvas = await html2canvas(paper, {
                    scale: 5,
                    useCORS: true,
                    backgroundColor: '#ffffff',
                });

                if (this.wantDownload) {
                    let link = document.createElement('a');
                    link.download = 'Passport_' + Date.now() + '.jpg';
                    link.href = canvas.toDataURL('image/jpeg', 1.0);
                    link.click();
                }

                Swal.close();

                // Move paper to a clean print wrapper for perfect alignment
                let printWrap = document.getElementById('printWrapper');
                if (!printWrap) {
                    printWrap = document.createElement('div');
                    printWrap.id = 'printWrapper';
                    document.body.appendChild(printWrap);
                }
                printWrap.style.display = 'block';
                printWrap.style.cssText = 'display:block;width:100%;text-align:center;background:#fff;padding:0;margin:0;';
                appWrap.style.display = 'none';
                printWrap.appendChild(paper);

                await new Promise(r => setTimeout(r, 300));
                window.print();

                // Restore: move paper back to workspace
                workspace.appendChild(paper);
                printWrap.style.display = 'none';
                appWrap.style.display = '';

            } catch (err) {
                // Restore on error too
                if (!workspace.contains(paper)) {
                    workspace.appendChild(paper);
                }
                let pw = document.getElementById('printWrapper');
                if (pw) pw.style.display = 'none';
                if (appWrap) appWrap.style.display = '';
                Swal.fire('Error', 'Could not generate image. Please try again.', 'error');
            }

            // Restore watermark and shadow
            wm.style.display = '';
            paper.style.boxShadow = origShadow;
        },
    };
}
</script>
</body>
</html>
