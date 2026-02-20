@extends('layouts.app')
@section('title', 'कागदपत्र पावती (DocSlip) — SETU Suvidha')

@section('content')
<div x-data="docslipApp()" class="min-h-screen bg-gray-50 dark:bg-gray-950">

    {{-- Page Header --}}
    <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #F3E8FF, #E9D5FF);">
                        <i data-lucide="clipboard-list" class="w-5 h-5" style="color: #7C3AED;"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white">कागदपत्र पावती <span class="text-sm font-normal text-gray-400">(DocSlip)</span></h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">सेवा निवडा → कागदपत्रांची यादी → प्रिंट करा</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('docslip.history') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <i data-lucide="history" class="w-3.5 h-3.5"></i> इतिहास
                    </a>
                    <a href="{{ route('docslip.settings') }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <i data-lucide="settings" class="w-3.5 h-3.5"></i> सेटिंग्ज
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- No Data State --}}
        @if(!$hasData)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 text-center">
            <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="package-open" class="w-8 h-8 text-purple-500"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">डिफॉल्ट सेवा लोड करा</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5 max-w-md mx-auto">तुमच्याकडे अजून कोणतीही सेवा किंवा कागदपत्रे सेट नाहीत. 18 सेवा + 25 कागदपत्रे + मॅपिंग्ज एका क्लिकवर लोड करा.</p>
            <form method="POST" action="{{ route('docslip.load-defaults') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold px-6 py-3 rounded-xl transition text-sm shadow-lg shadow-purple-500/25">
                    <i data-lucide="download" class="w-4 h-4"></i> डिफॉल्ट लोड करा
                </button>
            </form>
        </div>
        @else

        {{-- Section 1: Shop Info Bar --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 px-5 py-3 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3 text-sm">
                <i data-lucide="store" class="w-4 h-4 text-orange-500 shrink-0"></i>
                <div>
                    <span class="font-semibold text-gray-900 dark:text-white" x-text="shopName"></span>
                    <span class="text-gray-400 mx-1.5">|</span>
                    <span class="text-gray-500 dark:text-gray-400" x-text="shopAddress || 'पत्ता सेट नाही'"></span>
                    <span class="text-gray-400 mx-1.5">|</span>
                    <span class="text-gray-500 dark:text-gray-400"><i data-lucide="phone" class="w-3 h-3 inline"></i> <span x-text="shopMobile || '—'"></span></span>
                </div>
            </div>
            <a href="{{ route('profile') }}" class="text-xs text-purple-600 hover:text-purple-700 font-medium flex items-center gap-1">
                <i data-lucide="pencil" class="w-3 h-3"></i> Edit
            </a>
        </div>

        {{-- Section 2: Customer Info --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
            <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <i data-lucide="user" class="w-3.5 h-3.5"></i> ग्राहक माहिती (ऐच्छिक)
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input x-model="customerName" type="text" placeholder="ग्राहकाचे नाव" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
                <input x-model="customerMobile" type="tel" placeholder="मोबाईल नंबर" maxlength="10" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition">
            </div>
        </div>

        {{-- Section 3: Service Selection Grid --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="layers" class="w-3.5 h-3.5"></i> सेवा निवडा
                </h3>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full" :class="selectedServices.length > 0 ? 'bg-orange-100 dark:bg-orange-900/30 text-orange-600' : 'bg-gray-100 dark:bg-gray-800 text-gray-400'">
                    <span x-text="selectedServices.length"></span> निवडलेल्या
                </span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($services as $svc)
                <button type="button"
                    @click="toggleService({{ $svc->id }})"
                    :class="isSelected({{ $svc->id }}) ? 'ring-2 ring-orange-500 bg-orange-50 dark:bg-orange-900/20 border-orange-300 dark:border-orange-700 scale-[1.02]' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800'"
                    class="relative flex flex-col items-center gap-2 p-4 rounded-xl border transition-all duration-200 text-center group cursor-pointer">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center"
                         :class="isSelected({{ $svc->id }}) ? 'bg-orange-100 dark:bg-orange-900/40' : 'bg-gray-100 dark:bg-gray-800'">
                        <i data-lucide="{{ $svc->icon ?? 'file-text' }}" class="w-4 h-4"
                           :class="isSelected({{ $svc->id }}) ? 'text-orange-600' : 'text-gray-400'"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-900 dark:text-white leading-tight">{{ $svc->name_mr }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $svc->name_en }}</p>
                    </div>
                    <div x-show="isSelected({{ $svc->id }})" x-transition class="absolute top-1.5 right-1.5">
                        <span class="w-5 h-5 bg-orange-500 rounded-full flex items-center justify-center">
                            <i data-lucide="check" class="w-3 h-3 text-white"></i>
                        </span>
                    </div>
                </button>
                @endforeach
            </div>
        </div>

        {{-- Section 4: Merged Documents Preview --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5" x-show="selectedServices.length > 0" x-transition>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i> आवश्यक कागदपत्रे
                </h3>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-600">
                    एकूण <span x-text="mergedDocuments.length"></span> कागदपत्रे
                </span>
            </div>

            {{-- Loading --}}
            <div x-show="isLoading" class="flex items-center justify-center py-8">
                <svg class="animate-spin h-6 w-6 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            </div>

            {{-- Documents List --}}
            <div x-show="!isLoading" class="space-y-1.5">
                <template x-for="(doc, idx) in mergedDocuments" :key="doc.id">
                    <div class="flex items-start gap-3 px-4 py-2.5 rounded-lg bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                        <span class="w-6 h-6 rounded-md bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center text-[10px] font-bold text-gray-500 shrink-0 mt-0.5" x-text="idx + 1"></span>
                        <div class="flex-1 min-w-0">
                            <span class="text-sm font-medium text-gray-900 dark:text-white" x-text="doc.name_mr"></span>
                            <span class="text-xs text-gray-400 ml-2" x-text="'(' + doc.name_en + ')'"></span>
                            <template x-if="doc.remark">
                                <span class="block text-[10px] text-purple-500 mt-0.5" x-text="'📝 ' + doc.remark"></span>
                            </template>
                        </div>
                        <span class="text-gray-300 dark:text-gray-600 text-lg shrink-0">☐</span>
                    </div>
                </template>
            </div>

            <p x-show="!isLoading && mergedDocuments.length > 0" class="text-[10px] text-gray-400 mt-3 text-center italic">* डुप्लिकेट कागदपत्रे स्वयंचलितपणे काढली आहेत (Duplicates removed automatically)</p>

            {{-- Manual Doc Add --}}
            <div x-show="!isLoading && mergedDocuments.length > 0" class="mt-3 flex items-center gap-2">
                <input x-model="manualDocName" type="text" placeholder="+ अतिरिक्त कागदपत्र जोडा (manually)..." @keydown.enter.prevent="addManualDoc()" class="flex-1 px-3 py-2 rounded-lg border border-dashed border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition">
                <button type="button" @click="addManualDoc()" class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-bold transition shrink-0"><i data-lucide="plus" class="w-3.5 h-3.5 inline"></i> जोडा</button>
            </div>
        </div>

        {{-- Section 5: Remark with Save System --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5" x-show="selectedServices.length > 0" x-transition>
            <h3 class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                <i data-lucide="message-square" class="w-3.5 h-3.5"></i> टीप / Remark
            </h3>
            <div class="flex items-start gap-2">
                <textarea x-model="remark" rows="2" placeholder="ग्राहकासाठी अतिरिक्त सूचना लिहा..." class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition resize-none"></textarea>
                <button type="button" @click="saveRemark()" x-show="remark.trim().length > 0" x-transition :disabled="isSavingRemark"
                    class="px-3 py-2.5 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-xs font-bold transition shrink-0 disabled:opacity-50" title="Save for future use">
                    <i data-lucide="bookmark-plus" class="w-4 h-4"></i>
                </button>
            </div>
            <div x-show="remarkMsg" x-transition class="text-[11px] mt-1.5" :class="remarkMsgType === 'error' ? 'text-red-500' : 'text-green-500'" x-text="remarkMsg"></div>

            {{-- Saved + Default Remarks --}}
            <div class="flex flex-wrap gap-2 mt-3">
                {{-- Default 3 --}}
                <button type="button" @click="remark = 'सर्व कागदपत्रे Original + 2 Xerox copies आणा'" class="text-[11px] px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition font-medium">Original + 2 Xerox</button>
                <button type="button" @click="remark = 'सर्व कागदपत्रांच्या Original प्रती आणा'" class="text-[11px] px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition font-medium">सर्व Original</button>
                <button type="button" @click="remark = 'फोटोकॉपी (Xerox) पुरेसे आहेत'" class="text-[11px] px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition font-medium">फोटोकॉपी पुरेसे</button>

                {{-- User-saved remarks --}}
                <template x-for="sr in savedRemarks" :key="sr.id">
                    <span class="inline-flex items-center gap-1">
                        <button type="button" @click="remark = sr.text" class="text-[11px] px-3 py-1.5 rounded-lg bg-purple-50 dark:bg-purple-900/20 text-purple-600 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition font-medium" x-text="sr.text"></button>
                        <button type="button" @click="deleteRemark(sr.id)" class="p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="हटवा">
                            <i data-lucide="x" class="w-3 h-3 text-red-400"></i>
                        </button>
                    </span>
                </template>

                {{-- Clear --}}
                <button type="button" @click="remark = ''" class="text-[11px] px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 transition font-medium">Clear</button>
            </div>
            <p class="text-[10px] text-gray-400 mt-2" x-show="savedRemarks.length > 0" x-text="savedRemarks.length + '/25 saved remarks'"></p>
        </div>

        {{-- Section 6: Print Actions --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5" x-show="selectedServices.length > 0" x-transition>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i data-lucide="printer" class="w-4 h-4"></i>
                    <span>Paper: </span>
                    <select x-model="paperWidth" class="text-xs border border-gray-200 dark:border-gray-700 rounded-md px-2 py-1 bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <option value="80mm">80mm (Standard)</option>
                        <option value="58mm">58mm (Small)</option>
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" @click="clearAll()" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i> रीसेट
                    </button>
                    <button type="button" @click="printSlip()" :disabled="isPrinting"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-bold px-6 py-2.5 rounded-xl transition text-sm shadow-lg shadow-orange-500/25 disabled:opacity-50">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                        <span x-text="isPrinting ? 'प्रिंटिंग...' : 'प्रिंट करा'"></span>
                    </button>
                </div>
            </div>
        </div>

        @endif
    </div>
</div>

{{-- Hidden Print Frame --}}
<iframe id="docslip-print-frame" style="position:fixed;top:-9999px;left:-9999px;width:80mm;height:0;border:0;"></iframe>

@endsection

@push('scripts')
<script>
function docslipApp() {
    return {
        selectedServices: [],
        mergedDocuments: [],
        customerName: '',
        customerMobile: '',
        remark: '',
        manualDocName: '',
        isLoading: false,
        isPrinting: false,
        isSavingRemark: false,
        remarkMsg: '',
        remarkMsgType: '',
        paperWidth: localStorage.getItem('docslip_paper_width') || '80mm',
        savedRemarks: @json($savedRemarks),

        shopName: @json($profile->shop_name ?? auth()->user()->name ?? 'SETU Suvidha'),
        shopAddress: @json(trim(($profile->address ?? '') . ' ' . ($profile->district ?? ''))),
        shopMobile: @json($profile->mobile ?? ''),

        allServices: @json($services),

        toggleService(id) {
            const idx = this.selectedServices.indexOf(id);
            if (idx > -1) {
                this.selectedServices.splice(idx, 1);
            } else {
                this.selectedServices.push(id);
            }
            this.fetchMergedDocs();
        },

        isSelected(id) {
            return this.selectedServices.includes(id);
        },

        async fetchMergedDocs() {
            if (this.selectedServices.length === 0) {
                this.mergedDocuments = [];
                return;
            }
            this.isLoading = true;
            try {
                const res = await fetch('{{ route("docslip.merge") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ service_ids: this.selectedServices })
                });
                const data = await res.json();
                this.mergedDocuments = data.documents || [];
            } catch (e) {
                console.error('Merge error:', e);
            }
            this.isLoading = false;
        },

        async printSlip() {
            if (this.selectedServices.length === 0) return;
            this.isPrinting = true;

            // Save to history
            try {
                await fetch('{{ route("docslip.print") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        service_ids: this.selectedServices,
                        customer_name: this.customerName,
                        customer_mobile: this.customerMobile,
                        documents: this.mergedDocuments,
                        remark: this.remark,
                    })
                });
            } catch (e) {
                console.error('Save print log error:', e);
            }

            // Build service names for print
            const selectedSvcData = this.allServices.filter(s => this.selectedServices.includes(s.id));

            const now = new Date();
            const dateStr = now.toLocaleDateString('mr-IN', { day: '2-digit', month: '2-digit', year: 'numeric' });
            const timeStr = now.toLocaleTimeString('mr-IN', { hour: '2-digit', minute: '2-digit', hour12: true });

            const pw = this.paperWidth;
            localStorage.setItem('docslip_paper_width', pw);

            const html = this.buildSlipHTML({
                shopName: this.shopName,
                shopAddress: this.shopAddress,
                shopMobile: this.shopMobile,
                customerName: this.customerName,
                customerMobile: this.customerMobile,
                services: selectedSvcData,
                documents: this.mergedDocuments,
                remark: this.remark,
                date: dateStr,
                time: timeStr,
                paperWidth: pw,
            });

            const frame = document.getElementById('docslip-print-frame');
            frame.style.width = pw;
            const doc = frame.contentDocument || frame.contentWindow.document;
            doc.open();
            doc.write(html);
            doc.close();

            setTimeout(() => {
                frame.contentWindow.focus();
                frame.contentWindow.print();
                this.isPrinting = false;
            }, 400);
        },

        buildSlipHTML(d) {
            const servicesHTML = d.services.map((s, i) =>
                `<div class="svc-item">${i + 1}. ${s.name_mr} <span style="color:#888;font-size:10px">(${s.name_en})</span></div>`
            ).join('');

            const docsHTML = d.documents.map((doc, i) =>
                `<div class="doc-item"><span class="doc-num">${i + 1}.</span> ${doc.name_mr}${doc.remark ? '<div style="font-size:9px;color:#555;padding-left:14px;font-style:italic">→ ' + doc.remark + '</div>' : ''}</div>`
            ).join('');

            const customerHTML = (d.customerName || d.customerMobile) ? `
            <div class="cust-info">
                ${d.customerName ? '<b>' + d.customerName + '</b>' : ''}
                ${d.customerMobile ? ' | ' + d.customerMobile : ''}
            </div>
            <div class="divider"></div>` : '';

            const remarkHTML = d.remark ? `
            <div class="divider"></div>
            <div class="section-title">📝 टीप:</div>
            <div class="remark-box">${d.remark}</div>` : '';

            return `<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
@page{size:${d.paperWidth} auto;margin:0}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Noto Sans Devanagari','Segoe UI',sans-serif;font-size:12px;line-height:1.5;width:${d.paperWidth};padding:3mm;color:#000;background:#fff}
.header{text-align:center;border-bottom:2px dashed #000;padding-bottom:6px;margin-bottom:6px}
.shop-name{font-size:15px;font-weight:bold;letter-spacing:0.5px}
.shop-addr{font-size:9px;margin-top:1px;color:#444}
.shop-mobile{font-size:10px;font-weight:bold;margin-top:2px}
.divider{border-top:1px dashed #000;margin:5px 0}
.divider-double{border-top:2px dashed #000;margin:6px 0}
.date-line{font-size:9px;text-align:right;margin-bottom:4px;color:#555}
.cust-info{font-size:11px;margin-bottom:3px}
.section-title{font-size:12px;font-weight:bold;margin-bottom:3px;text-decoration:underline}
.svc-item{font-size:11px;padding:1px 0 1px 8px}
.doc-item{font-size:11px;padding:2px 0 2px 14px;position:relative;border-bottom:1px dotted #ddd}
.doc-item::before{content:'☐';position:absolute;left:0;font-size:11px}
.doc-num{font-size:9px;color:#666;margin-right:2px}
.total-docs{font-size:11px;font-weight:bold;text-align:right;margin-top:4px;padding:2px 4px;background:#f0f0f0}
.remark-box{border:1px solid #000;padding:3px 4px;font-size:10px;font-style:italic;margin-top:2px}
.footer{text-align:center;border-top:2px dashed #000;padding-top:5px;margin-top:8px;font-size:9px}
.footer .brand{font-weight:bold;font-size:10px;margin-top:2px}
@media print{body{width:${d.paperWidth}}.no-print{display:none!important}}
</style></head><body>
<div class="header">
<div class="shop-name">${d.shopName}</div>
<div class="shop-addr">${d.shopAddress}</div>
<div class="shop-mobile">📞 ${d.shopMobile}</div>
</div>
<div class="date-line">📅 ${d.date} | ${d.time}</div>
${customerHTML}
<div class="section-title">📋 सेवा / Services:</div>
${servicesHTML}
<div class="divider-double"></div>
<div class="section-title">📄 आवश्यक कागदपत्रे:</div>
${docsHTML}
<div class="total-docs">एकूण कागदपत्रे: ${d.documents.length}</div>
${remarkHTML}
<div class="footer">
<div>हे कागदपत्रे घेऊन या</div>
<div style="font-size:8px;color:#777">(Please bring these documents)</div>
<div class="brand">🏛️ SETU Suvidha</div>
<div style="font-size:8px">www.setusuvidha.com</div>
</div>
<div style="page-break-after:always"></div>
</body></html>`;
        },

        addManualDoc() {
            const name = this.manualDocName.trim();
            if (!name) return;
            const manualId = 'manual_' + Date.now();
            this.mergedDocuments.push({ id: manualId, name_mr: name, name_en: '', remark: null });
            this.manualDocName = '';
        },

        async saveRemark() {
            const text = this.remark.trim();
            if (!text) return;
            this.isSavingRemark = true;
            this.remarkMsg = '';
            try {
                const res = await fetch('{{ route("docslip.saved-remarks.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ text })
                });
                const data = await res.json();
                if (data.success) {
                    this.savedRemarks.push(data.remark);
                    this.remarkMsg = 'Remark saved!';
                    this.remarkMsgType = 'success';
                } else {
                    this.remarkMsg = data.error || 'Error saving';
                    this.remarkMsgType = 'error';
                }
            } catch (e) {
                this.remarkMsg = 'Network error';
                this.remarkMsgType = 'error';
            }
            this.isSavingRemark = false;
            setTimeout(() => this.remarkMsg = '', 3000);
        },

        async deleteRemark(id) {
            if (!confirm('हा remark हटवायचा?')) return;
            try {
                await fetch('/docslip/saved-remarks/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });
                this.savedRemarks = this.savedRemarks.filter(r => r.id !== id);
            } catch (e) {
                console.error('Delete remark error:', e);
            }
        },

        clearAll() {
            this.selectedServices = [];
            this.mergedDocuments = [];
            this.customerName = '';
            this.customerMobile = '';
            this.remark = '';
            this.manualDocName = '';
        }
    }
}
</script>
@endpush
