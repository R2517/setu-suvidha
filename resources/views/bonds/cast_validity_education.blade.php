@extends('layouts.bond-maker')
@section('title', 'जात वैधता')

@push('styles')
<style>
    .wm{user-select:none}
    .vtree{text-align:center;padding:8px 0}
    .vtree ul{display:flex;justify-content:center;padding-top:20px;position:relative;margin:0;padding-left:0;list-style:none}
    .vtree ul::before{content:'';position:absolute;top:0;left:50%;border-left:1.5px solid #333;height:20px}
    .vtree>ul::before{display:none}
    .vtree li{display:flex;flex-direction:column;align-items:center;position:relative;padding:0 5px}
    .vtree li::before,.vtree li::after{content:'';position:absolute;top:0;border-top:1.5px solid #333;width:50%}
    .vtree li::before{right:50%}
    .vtree li::after{left:50%}
    .vtree li:first-child::before,.vtree li:last-child::after{display:none}
    .vtree li:only-child::before,.vtree li:only-child::after{display:none}
    .vtree .nd{border:1px solid #444;padding:2px 7px;font-size:0.8em;white-space:nowrap;margin-top:20px;background:#fff;position:relative}
    .vtree .nd.rt{font-weight:bold;border-width:2px}
    .vtree .nd.lb{border:2px solid #16a34a;background:#f0fdf4;font-weight:bold}
    .tree-inp{border-left:2px solid #c7d2fe;padding-left:10px;margin-left:6px;margin-top:4px}
    .tree-inp .tree-inp{border-color:#ddd6fe}
    .tree-inp .tree-inp .tree-inp{border-color:#fde68a}
    .tbtn{font-size:10px;padding:2px 8px;border-radius:6px;cursor:pointer;border:1px solid #e2e8f0;background:#f8fafc;color:#6366f1;font-weight:600}
    .tbtn:hover{background:#eef2ff}
    .tbtn.del{color:#ef4444;border-color:#fecaca}
    .tbtn.del:hover{background:#fef2f2}
    @media print{.vtree .nd.lb{border-color:#16a34a !important;background:#f0fdf4 !important}}
</style>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-700 font-sans" id="root" x-data="cv()" x-effect="$nextTick(()=>lucide.createIcons())">

    {{-- ═══════════════════ LEFT PANEL ═══════════════════ --}}
    <div class="w-[360px] min-w-[360px] bg-[#f8fafc] overflow-y-auto flex flex-col border-r border-gray-200/60 z-10" id="leftPanel">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100 sticky top-0 z-20">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('bonds.index') }}" class="w-8 h-8 bg-gray-50 hover:bg-gray-100 rounded-lg flex items-center justify-center transition">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-gray-500"></i>
                </a>
                <div>
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">जात वैधता</h2>
                    <p class="text-[10px] text-gray-400">नमुना-३ + <span x-text="tc.n2t"></span></p>
                </div>
            </div>
            <button @click="guideOpen=true" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- TYPE & GENDER --}}
            <div class="section-card">
                <div class="section-header"><span class="num">⚙</span><span class="title">प्रकार & लिंग</span></div>
                <div class="section-body grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="field-label">प्रकार</label>
                        <select x-model="typ" class="form-select">
                            <option value="education">शिक्षण (नमुना १७)</option>
                            <option value="election">निवडणूक (नमुना २१)</option>
                            <option value="services">सेवा (नमुना १९)</option>
                            <option value="other">इतर (नमुना २३)</option>
                        </select>
                    </div>
                    <div>
                        <label class="field-label">लिंग</label>
                        <select x-model="gender" class="form-select">
                            <option value="male">पुरुष</option>
                            <option value="female">स्त्री</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- SECTION 1 — दिनांक & ठिकाण --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">दिनांक & ठिकाण</span></div>
                <div class="section-body grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="field-label">दिनांक</label>
                        <input type="date" x-model="dt" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">ठिकाण</label>
                        <input type="text" x-model="place" placeholder="ठिकाण" class="form-input">
                    </div>
                </div>
            </div>

            {{-- SECTION 2 — अर्जदार माहिती --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">अर्जदार माहिती</span></div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">अर्जदाराचे पूर्ण नाव</label>
                        <input type="text" x-model="name" placeholder="पूर्ण नाव" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">वडिलाचे नाव</label>
                        <input type="text" x-model="father" placeholder="वडिलाचे नाव" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">वय</label>
                            <input type="number" x-model="age" placeholder="वय" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">व्यवसाय</label>
                            <select x-model="occ" class="form-select">
                                <option value="शेती">शेती</option>
                                <option value="धंदा">धंदा</option>
                                <option value="नोकरी">नोकरी</option>
                                <option value="शासकीय अधिकारी">शासकीय अधिकारी</option>
                                <option value="मजूर">मजूर</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="field-label">जात</label>
                        <input type="text" x-model="caste" placeholder="जात" class="form-input">
                    </div>
                </div>
            </div>

            {{-- SECTION 3 — पत्ता --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">पत्ता</span></div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">रा. (गाव / शहर)</label>
                        <input type="text" x-model="village" placeholder="गाव / शहर" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">ता. (तालुका)</label>
                            <input type="text" x-model="taluka" placeholder="तालुका" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">जिल्हा</label>
                            <input type="text" x-model="dist" placeholder="जिल्हा" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">राज्य</label>
                        <input type="text" x-model="state" placeholder="राज्य" class="form-input">
                    </div>
                </div>
            </div>

            {{-- SECTION 4 — Education only --}}
            <div class="section-card" x-show="typ==='education'" x-transition>
                <div class="section-header"><span class="num">4</span><span class="title">विद्यार्थी माहिती</span></div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">विद्यार्थ्याचे नाव</label>
                        <input type="text" x-model="student" placeholder="विद्यार्थ्याचे नाव" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">नाते</label>
                            <select x-model="stuRel" class="form-select">
                                <option value="मुलगी">मुलगी</option>
                                <option value="मुलगा">मुलगा</option>
                            </select>
                        </div>
                        <div>
                            <label class="field-label">तहसील</label>
                            <input type="text" x-model="tahsil" placeholder="तहसील" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 5 — प्रमाणपत्र तपशील (Page 1-2) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">5</span><span class="title">प्रमाणपत्र तपशील (नमुना ३)</span></div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">अधिकारी</label>
                        <select x-model="officer" class="form-select">
                            <option value="कार्यकारी जिल्हा दंडाधिकारी">कार्यकारी जिल्हा दंडाधिकारी</option>
                            <option value="उपजिल्हाधिकारी">उपजिल्हाधिकारी</option>
                            <option value="उपविभागीय अधिकारी">उपविभागीय अधिकारी</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">ऑफिस</label>
                            <input type="text" x-model="office" placeholder="कार्यालय" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">ऑफिस जिल्हा</label>
                            <input type="text" x-model="officeDist" placeholder="जिल्हा" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">प्रमाणपत्र दिनांक</label>
                            <input type="date" x-model="certDt" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">दाखला क्रमांक</label>
                            <input type="text" x-model="certNo" placeholder="क्रमांक" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 6 — वंशावळ Tree Builder --}}
            <div class="section-card">
                <div class="section-header"><span class="num">6</span><span class="title">वंशावळ (कुटुंब वृक्ष)</span></div>
                <div class="section-body space-y-2">
                    <div>
                        <label class="field-label">मूळ पुरुष (Root)</label>
                        <input type="text" x-model="root" placeholder="आजोबा / पणजोबा नाव" class="form-input">
                    </div>
                    <button type="button" class="tbtn" @click="addG2()">+ पिढी २ जोडा</button>

                    {{-- Gen 2 --}}
                    <template x-for="(c2,i2) in g2" :key="c2.id">
                        <div class="tree-inp">
                            <div class="flex items-center gap-1 mb-1">
                                <input type="text" x-model="c2.name" placeholder="पिढी २ नाव" class="form-input flex-1 !py-1 !text-xs">
                                <label class="flex items-center gap-0.5 text-[10px] text-green-600 cursor-pointer" :title="'लाभार्थी निवडा'">
                                    <input type="radio" :value="'2-'+c2.id" x-model="lbId" class="accent-green-600 w-3 h-3"> ✓
                                </label>
                                <button type="button" class="tbtn del" @click="g2.splice(i2,1)">✕</button>
                            </div>
                            <button type="button" class="tbtn mb-1" @click="addG3(i2)">+ पिढी ३</button>

                            {{-- Gen 3 --}}
                            <template x-for="(c3,i3) in c2.children" :key="c3.id">
                                <div class="tree-inp">
                                    <div class="flex items-center gap-1 mb-1">
                                        <input type="text" x-model="c3.name" placeholder="पिढी ३ नाव" class="form-input flex-1 !py-1 !text-xs">
                                        <label class="flex items-center gap-0.5 text-[10px] text-green-600 cursor-pointer">
                                            <input type="radio" :value="'3-'+c3.id" x-model="lbId" class="accent-green-600 w-3 h-3"> ✓
                                        </label>
                                        <button type="button" class="tbtn del" @click="c2.children.splice(i3,1)">✕</button>
                                    </div>
                                    <button type="button" class="tbtn mb-1" @click="addG4(i2,i3)">+ पिढी ४</button>

                                    {{-- Gen 4 --}}
                                    <template x-for="(c4,i4) in c3.children" :key="c4.id">
                                        <div class="tree-inp">
                                            <div class="flex items-center gap-1">
                                                <input type="text" x-model="c4.name" placeholder="पिढी ४ नाव" class="form-input flex-1 !py-1 !text-xs">
                                                <label class="flex items-center gap-0.5 text-[10px] text-green-600 cursor-pointer">
                                                    <input type="radio" :value="'4-'+c4.id" x-model="lbId" class="accent-green-600 w-3 h-3"> ✓
                                                </label>
                                                <button type="button" class="tbtn del" @click="c3.children.splice(i4,1)">✕</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>
                    <p class="text-[9px] text-gray-400 mt-1">✓ = लाभार्थी (हिरव्या बॉर्डरमध्ये दिसेल)</p>
                </div>
            </div>

            {{-- SECTION 7 — Page 3-4 fields --}}
            <div class="section-card">
                <div class="section-header"><span class="num">7</span><span class="title">नमुना <span x-text="tc.n2"></span> तपशील</span></div>
                <div class="section-body space-y-2.5">
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">अधिकारी (पृष्ठ ३)</label>
                            <input type="text" x-model="off2" placeholder="सक्षम अधिकारी" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">जिल्हा (पृष्ठ ३)</label>
                            <input type="text" x-model="dist2" placeholder="जिल्हा" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">दाखला क्र. (पृष्ठ ३)</label>
                            <input type="text" x-model="certNo2" placeholder="क्रमांक" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">दाखला दिनांक (पृष्ठ ३)</label>
                            <input type="date" x-model="certDt2" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wallet --}}
            <div class="flex items-center gap-2 px-3 py-2.5 bg-indigo-50/60 rounded-xl border border-indigo-100">
                <i data-lucide="wallet" class="w-3.5 h-3.5 text-indigo-400"></i>
                <span class="text-[11px] text-indigo-600 font-medium">Wallet: ₹<span id="walletBal" x-text="walletBal">{{ number_format($balance, 2) }}</span></span>
            </div>

            {{-- Pay Button --}}
            <button @click="payAndPrint()" id="payBtn"
                    class="w-full bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white font-semibold py-3 rounded-xl text-sm transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 mb-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Pay & Print (₹{{ number_format($format->fee, 0) }})
            </button>
        </div>
    </div>

    {{-- ═══════════════════ RIGHT PANEL — PREVIEW ═══════════════════ --}}
    <div class="flex-1 overflow-y-auto bg-[#555] pt-0 pb-8 px-4 relative preview-area">

        {{-- Controls Bar --}}
        <div class="ctrl-bar">
            <div class="ctrl-group">
                <label>Font</label>
                <input type="range" min="8" max="16" value="11" oninput="bondSetFontAll&&bondSetFontAll(this.value);this.nextElementSibling.textContent=this.value+'pt'">
                <span class="val">11pt</span>
            </div>
            <template x-for="p in [1,2,3,4]" :key="p">
                <div class="flex items-center gap-0">
                    <div class="ctrl-divider"></div>
                    <div class="ctrl-group">
                        <label x-text="'P'+p+' Gap'"></label>
                        <input type="range" min="0" max="200" :value="p<=2?80:60" @input="$refs['gap'+p].style.height=$event.target.value+'mm';$event.target.nextElementSibling.textContent=$event.target.value">
                        <span class="val" x-text="p<=2?'80':'60'"></span>
                    </div>
                    <div class="ctrl-group">
                        <label x-text="'P'+p+' PdL'"></label>
                        <input type="range" min="5" max="80" value="40" @input="$refs['pc'+p].style.paddingLeft=$event.target.value+'px';$event.target.nextElementSibling.textContent=$event.target.value">
                        <span class="val">40</span>
                    </div>
                    <div class="ctrl-group">
                        <label x-text="'P'+p+' PdR'"></label>
                        <input type="range" min="5" max="80" value="40" @input="$refs['pc'+p].style.paddingRight=$event.target.value+'px';$event.target.nextElementSibling.textContent=$event.target.value">
                        <span class="val">40</span>
                    </div>
                </div>
            </template>
        </div>

        {{-- ══════ A4 PAGE 1 — नमुना ३ (Title + Main Para) ══════ --}}
        <div class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-0" style="min-height:1123px;font-size:11pt;font-family:'Noto Sans Devanagari','Mukta',sans-serif;">
            <div class="wm bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>
            <div x-ref="gap1" class="bond-gap w-full relative overflow-hidden" style="height:80mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 1 GAP</span>
            </div>
            <div x-ref="pc1" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                {{-- Title Box --}}
                <div class="border border-gray-800 rounded px-4 py-3 mb-4 text-center">
                    <p class="font-bold text-base mb-1">प्रतिज्ञापत्र</p>
                    <p class="font-bold text-base mb-1">नमुना – ३</p>
                    <p class="font-bold text-sm mb-1" x-text="tc.rule"></p>
                    <p class="font-bold text-sm mb-1" x-text="tc.subtitle"></p>
                    <p class="font-bold text-sm mb-1" x-show="tc.purpose" x-text="tc.purpose"></p>
                    <p class="font-bold text-sm">(दिवाणी प्रक्रिया संहिता १९०८, आदेश १८ नियम ४)</p>
                </div>

                <div class="ml-10 mb-3">
                    <p class="font-bold leading-8">विद्यामान कार्यकारी दंडाधिकारी,</p>
                    <p class="font-bold leading-8" x-text="place||'___________'"></p>
                    <p class="font-bold leading-8">याचे समक्ष.</p>
                </div>

                {{-- Education paragraph --}}
                <div x-show="typ==='education'">
                    <p class="leading-8 text-justify mb-3">
                        मी. <span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span>
                        व्यवसाय :- <span class="out-field filled" x-text="occ"></span>
                        वय <span class="out-field" :class="age?'filled':'empty'" x-text="age||'\u00A0'"></span> वर्ष
                        रा. <span class="out-field" :class="village?'filled':'empty'" x-text="village||'\u00A0'"></span>
                        ता. <span class="out-field" :class="taluka?'filled':'empty'" x-text="taluka||'\u00A0'"></span>
                        जि. <span class="out-field" :class="dist?'filled':'empty'" x-text="dist||'\u00A0'"></span>
                        येथील रहिवासी असून, आज दिनांक: <span class="out-field" :class="fDt?'filled':'empty'" x-text="fDt||'\u00A0'"></span>
                        रोजी मी सत्य प्रतिज्ञेवर कथन <span x-text="g.karto"></span> कि, <span x-text="g.mazhi"></span>
                        <span x-text="stuRel"></span> नामे:- <span class="out-field" :class="student?'filled':'empty'" x-text="student||'\u00A0'"></span>
                        ह्यास तहसील <span class="out-field" :class="tahsil?'filled':'empty'" x-text="tahsil||'\u00A0'"></span>
                        तहसील मधून <span class="out-field filled" x-text="officer"></span>
                        <span class="out-field" :class="office?'filled':'empty'" x-text="office||'\u00A0'"></span>
                        जिल्हा:. <span class="out-field" :class="officeDist?'filled':'empty'" x-text="officeDist||'\u00A0'"></span>
                        यांनी दिनांक :. <span class="out-field" :class="fCertDt?'filled':'empty'" x-text="fCertDt||'\u00A0'"></span>
                        रोजी दिलेल्या
                        <span class="out-field" :class="caste?'filled':'empty'" x-text="caste||'\u00A0'"></span>
                        जातीचा दाखला, क्रमांक:. <span class="out-field" :class="certNo?'filled':'empty'" x-text="certNo||'\u00A0'"></span>
                        च्या पडताळणीसाठी अर्ज करीत आहे.
                    </p>
                </div>

                {{-- Election paragraph --}}
                <div x-show="typ==='election'">
                    <p class="leading-8 text-justify mb-3">
                        मी <span x-text="g.shri"></span> <span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span>
                        व्यवसाय <span class="out-field filled" x-text="occ"></span>
                        वय <span class="out-field" :class="age?'filled':'empty'" x-text="age||'\u00A0'"></span> वर्षे
                        रा. <span class="out-field" :class="village?'filled':'empty'" x-text="village||'\u00A0'"></span>
                        ता. <span class="out-field" :class="taluka?'filled':'empty'" x-text="taluka||'\u00A0'"></span>
                        जि. <span class="out-field" :class="dist?'filled':'empty'" x-text="dist||'\u00A0'"></span>
                        राज्य <span class="out-field" :class="state?'filled':'empty'" x-text="state||'\u00A0'"></span>
                        येथील रहिवासी असून पुढीलप्रमाणे सत्य प्रतिज्ञेवर नमूद <span x-text="g.karto"></span> की —
                        माझी जात <span class="out-field" :class="caste?'filled':'empty'" x-text="caste||'\u00A0'"></span> आहे.
                        <span x-text="g.shri"></span> <span class="out-field" :class="father?'filled':'empty'" x-text="father||'\u00A0'"></span>
                        <span x-text="g.yancha"></span> <span x-text="g.mulga"></span> असून
                        <span class="out-field filled" x-text="officer"></span>,
                        <span class="out-field" :class="office?'filled':'empty'" x-text="office||'\u00A0'"></span>
                        जिल्हा <span class="out-field" :class="officeDist?'filled':'empty'" x-text="officeDist||'\u00A0'"></span>
                        यांनी दिनांक <span class="out-field" :class="fCertDt?'filled':'empty'" x-text="fCertDt||'\u00A0'"></span>
                        रोजी दिलेल्या जात प्रमाणपत्र क्र. <span class="out-field" :class="certNo?'filled':'empty'" x-text="certNo||'\u00A0'"></span>
                        च्या पडताळणीसाठी अर्ज करीत आहे.
                    </p>
                </div>

                {{-- Services paragraph --}}
                <div x-show="typ==='services'">
                    <p class="leading-8 text-justify mb-3">
                        मी <span x-text="g.shri"></span> <span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span>
                        <span x-text="g.shri"></span> <span class="out-field" :class="father?'filled':'empty'" x-text="father||'\u00A0'"></span>
                        <span x-text="g.yancha"></span> <span x-text="g.mulga"></span>,
                        वय <span class="out-field" :class="age?'filled':'empty'" x-text="age||'\u00A0'"></span> वर्षे,
                        व्यवसाय <span class="out-field filled" x-text="occ"></span>,
                        रा. <span class="out-field" :class="village?'filled':'empty'" x-text="village||'\u00A0'"></span>
                        ता. <span class="out-field" :class="taluka?'filled':'empty'" x-text="taluka||'\u00A0'"></span>
                        जिल्हा <span class="out-field" :class="dist?'filled':'empty'" x-text="dist||'\u00A0'"></span>
                        राज्य <span class="out-field" :class="state?'filled':'empty'" x-text="state||'\u00A0'"></span>
                        येथील रहिवासी असून, सत्य प्रतिज्ञेवर पुढीलप्रमाणे नमूद <span x-text="g.karto"></span> —
                        माझी जात <span class="out-field" :class="caste?'filled':'empty'" x-text="caste||'\u00A0'"></span> आहे.
                        <span class="out-field filled" x-text="officer"></span>,
                        <span class="out-field" :class="office?'filled':'empty'" x-text="office||'\u00A0'"></span>
                        जिल्हा <span class="out-field" :class="officeDist?'filled':'empty'" x-text="officeDist||'\u00A0'"></span>
                        यांनी दिनांक <span class="out-field" :class="fCertDt?'filled':'empty'" x-text="fCertDt||'\u00A0'"></span>
                        रोजी दिलेल्या जात प्रमाणपत्र क्र. <span class="out-field" :class="certNo?'filled':'empty'" x-text="certNo||'\u00A0'"></span>
                        च्या पडताळणीसाठी अर्ज करीत आहे.
                    </p>
                </div>

                {{-- Other paragraph --}}
                <div x-show="typ==='other'">
                    <p class="leading-8 text-justify mb-3">
                        मी, <span x-text="g.shri"></span> <span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span>
                        <span x-text="g.shri"></span> <span class="out-field" :class="father?'filled':'empty'" x-text="father||'\u00A0'"></span>
                        <span x-text="g.yancha"></span> <span x-text="g.mulga"></span>,
                        वय <span class="out-field" :class="age?'filled':'empty'" x-text="age||'\u00A0'"></span> वर्षे,
                        व्यवसाय <span class="out-field filled" x-text="occ"></span>,
                        रा. <span class="out-field" :class="village?'filled':'empty'" x-text="village||'\u00A0'"></span>
                        ता. <span class="out-field" :class="taluka?'filled':'empty'" x-text="taluka||'\u00A0'"></span>
                        जिल्हा <span class="out-field" :class="dist?'filled':'empty'" x-text="dist||'\u00A0'"></span>
                        राज्य <span class="out-field" :class="state?'filled':'empty'" x-text="state||'\u00A0'"></span>
                        येथील रहिवासी असून, आज दिनांक <span class="out-field" :class="fDt?'filled':'empty'" x-text="fDt||'\u00A0'"></span>
                        रोजी सत्य प्रतिज्ञेवर पुढीलप्रमाणे नमूद <span x-text="g.karto"></span> की —
                        माझी जात <span class="out-field" :class="caste?'filled':'empty'" x-text="caste||'\u00A0'"></span> आहे.
                        <span class="out-field filled" x-text="officer"></span>,
                        <span class="out-field" :class="office?'filled':'empty'" x-text="office||'\u00A0'"></span>
                        जिल्हा <span class="out-field" :class="officeDist?'filled':'empty'" x-text="officeDist||'\u00A0'"></span>
                        यांनी दिनांक <span class="out-field" :class="fCertDt?'filled':'empty'" x-text="fCertDt||'\u00A0'"></span>
                        रोजी दिलेल्या जात प्रमाणपत्र क्र. <span class="out-field" :class="certNo?'filled':'empty'" x-text="certNo||'\u00A0'"></span>
                        च्या पडताळणीसाठी अर्ज करीत आहे.
                    </p>
                </div>

                <div id="redLine" class="w-full border-t-2 border-dashed border-red-400 text-center text-red-400 text-xs py-1 my-2 no-print select-none">
                    --- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---
                </div>
            </div>
        </div>

        {{-- ══════ A4 PAGE 2 — नमुना ३ (Declarations + वंशावळ + Signature + सत्यापन) ══════ --}}
        <div class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mt-0" style="min-height:1123px;font-size:11pt;font-family:'Noto Sans Devanagari','Mukta',sans-serif;">
            <div class="wm bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>
            <div x-ref="gap2" class="bond-gap w-full relative overflow-hidden" style="height:80mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 2 GAP</span>
            </div>
            <div x-ref="pc2" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                <p class="leading-8 text-justify mb-3">
                    अर्जासोबत जोडलेली सर्व कागदपत्रे व पुरावे मी समक्ष प्राधीकार्‍याकडून प्राप्त केलेली असून, ती कागदपत्रे खरी आणि योग्य मार्गाने मिळवलेली आहेत. त्यामध्ये कोणत्याही प्रकारचा फेरफार / दुरुस्ती / बदल केलेला नाही. ती खोटी अथवा नकली नाहीत हे मी सत्य प्रतिज्ञेवर लिहून <span x-text="g.deto"></span>.
                </p>

                <ul class="list-disc ml-6 leading-8 text-justify mb-3">
                    <li class="mb-2">माझ्या कोणत्याही नातीवाईकांचे जातीचे प्रमाणपत्र तपासणी समिती कडून कधीही, अवैध ठरलेले नाहीत,</li>
                    <li class="mb-2">तसेच मी यापूर्वी महाराष्ट्रातील कोणत्याही जात पडताळणी समितीकडे अर्ज केला होता व माझे जात प्रमाणपत्र अवैध झाले आहे अशी बाब नाही.</li>
                </ul>

                <p class="leading-8 text-justify mb-4">
                    अर्जासोबत जोडलेली कागदपत्रे आणि पुरावे खोटे अथवा बनावट आढळल्यास मी त्यास जबाबदार असून त्यासाठी भारतीय दंड विधान कायद्यानुसार लागू होणार्‍या शिक्षेस मी पात्र <span x-text="g.rahin"></span>, याची मला पूर्ण जाणीव आहे.
                </p>

                {{-- वंशावळ Tree Chart --}}
                <div class="border border-gray-300 rounded p-3 mb-4">
                    <h4 class="font-bold text-center underline mb-2 text-sm">वंशावळ</h4>
                    <div class="vtree">
                        <div class="nd rt" x-text="root||'________'"></div>
                        <ul x-show="g2.length>0">
                            <template x-for="c2 in g2" :key="c2.id">
                                <li>
                                    <div class="nd" :class="{'lb':lbId==='2-'+c2.id}" x-text="c2.name||'________'"></div>
                                    <ul x-show="c2.children.length>0">
                                        <template x-for="c3 in c2.children" :key="c3.id">
                                            <li>
                                                <div class="nd" :class="{'lb':lbId==='3-'+c3.id}" x-text="c3.name||'________'"></div>
                                                <ul x-show="c3.children&&c3.children.length>0">
                                                    <template x-for="c4 in c3.children" :key="c4.id">
                                                        <li>
                                                            <div class="nd" :class="{'lb':lbId==='4-'+c4.id}" x-text="c4.name||'________'"></div>
                                                        </li>
                                                    </template>
                                                </ul>
                                            </li>
                                        </template>
                                    </ul>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>

                <p class="leading-8 text-justify mb-6">
                    मी वर नमूद केलेली माहिती सत्य व खरी आहे म्हणून हे शपथपत्र करीत आहे.
                </p>

                <div class="flex justify-between items-start mb-8 mt-8">
                    <div>
                        <p class="leading-7">ठिकाण :- <span class="out-field" :class="place?'filled':'empty'" x-text="place||'\u00A0'"></span></p>
                        <p class="leading-7">दि. <span class="out-field" :class="fDt?'filled':'empty'" x-text="fDt||'\u00A0'"></span></p>
                    </div>
                    <div class="text-right">
                        <p class="leading-7 mb-1">सही</p>
                        <p class="leading-7"><span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span></p>
                    </div>
                </div>

                <div class="border-t border-gray-400 pt-4 mt-6">
                    <h3 class="font-bold text-center underline mb-4">सत्यापन</h3>
                    <p class="leading-8 text-justify mb-6">
                        मी विद्यामान न्यायालय सत्यप्रतिज्ञेवर <span x-text="g.kalvito"></span> कि, शपथपत्रातील सर्व मजकूर माझे/आमचे माहिती प्रमाणे पूर्णत: खरा व सत्य आहे. अगर हा मजकूर खोटा निघाल्यास मी/आम्ही कायद्यातील भा.द.वि.कलम १९३/२, १९९, २०० अन्वये गुन्ह्यास पात्र <span x-text="g.rahin"></span>/राहू करिता आज दिनांक :- <span class="out-field" :class="fDt?'filled':'empty'" x-text="fDt||'\u00A0'"></span> रोजी समक्ष स्वाक्षरी करीत आहे.
                    </p>
                    <div class="flex justify-between items-start mt-8">
                        <div>
                            <p class="leading-7">दि:- <span class="out-field" :class="fDt?'filled':'empty'" x-text="fDt||'\u00A0'"></span></p>
                            <p class="leading-7">ठिकाण:- <span class="out-field" :class="place?'filled':'empty'" x-text="place||'\u00A0'"></span></p>
                        </div>
                        <div class="text-right">
                            <p class="leading-7 mb-1">सही</p>
                            <p class="leading-7 mb-1">( प्रतिज्ञक )</p>
                            <p class="leading-7"><span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════ A4 PAGE 3 — Type-specific नमुना (17/21/19/23) Title + Main Para ══════ --}}
        <div class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mt-0" style="min-height:1123px;font-size:11pt;font-family:'Noto Sans Devanagari','Mukta',sans-serif;">
            <div class="wm bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>
            <div x-ref="gap3" class="bond-gap w-full relative overflow-hidden" style="height:60mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 3 GAP</span>
            </div>
            <div x-ref="pc3" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                <div class="border border-gray-800 rounded px-4 py-3 mb-4 text-center">
                    <p class="font-bold text-sm leading-6 mb-1">४६ महाराष्ट्र शासन राजपत्र असाधारण भाग चार -ब, सप्टेंबर ३, २०१२/भाद्रा १२, शके १९३४</p>
                    <p class="font-bold text-base mb-1">नमुना–<span x-text="tc.n2"></span> <span x-text="tc.n2rule"></span></p>
                    <p class="font-bold text-sm mb-1" x-text="tc.n2for"></p>
                    <p class="font-bold text-sm leading-6" x-text="tc.n2title"></p>
                </div>

                <div class="ml-10 mb-3">
                    <p class="font-bold leading-8">विद्यामान कार्यकारी दंडाधिकारी,</p>
                    <p class="font-bold leading-8" x-text="place||'___________'"></p>
                    <p class="font-bold leading-8">याचे समक्ष.</p>
                </div>

                <p class="leading-8 text-justify mb-3">
                    मी <span x-text="g.shri"></span> <span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span>
                    <span x-text="g.shri"></span> <span class="out-field" :class="father?'filled':'empty'" x-text="father||'\u00A0'"></span>
                    <span x-text="g.yancha"></span> <span x-text="g.mulga"></span>,
                    वय <span class="out-field" :class="age?'filled':'empty'" x-text="age||'\u00A0'"></span> वर्षे,
                    व्यवसाय <span class="out-field filled" x-text="occ"></span>,
                    रा. <span class="out-field" :class="village?'filled':'empty'" x-text="village||'\u00A0'"></span>
                    ता. <span class="out-field" :class="taluka?'filled':'empty'" x-text="taluka||'\u00A0'"></span>
                    जिल्हा <span class="out-field" :class="dist?'filled':'empty'" x-text="dist||'\u00A0'"></span>
                    राज्य <span class="out-field" :class="state?'filled':'empty'" x-text="state||'\u00A0'"></span>
                    येथील रहिवासी असून, सत्य प्रतिज्ञेवर पुढीलप्रमाणे नमूद <span x-text="g.karto"></span> —
                </p>

                <ol class="list-decimal ml-6 leading-8 text-justify mb-3">
                    <li class="mb-2">माझी जात <span class="out-field" :class="caste?'filled':'empty'" x-text="caste||'\u00A0'"></span> असून ती अनुसूचित जाती/अनुसूचित जमाती/विमुक्त जाती/भटक्या जमाती/इतर मागासवर्ग या प्रवर्गात मोडते.</li>
                    <li class="mb-2"><span class="out-field" :class="off2?'filled':'empty'" x-text="off2||'\u00A0'"></span>,
                        जिल्हा <span class="out-field" :class="dist2?'filled':'empty'" x-text="dist2||'\u00A0'"></span>
                        यांनी मला/माझ्या पालकांना दिनांक <span class="out-field" :class="fCertDt2?'filled':'empty'" x-text="fCertDt2||'\u00A0'"></span>
                        रोजी जात प्रमाणपत्र क्र. <span class="out-field" :class="certNo2?'filled':'empty'" x-text="certNo2||'\u00A0'"></span> दिले आहे.
                    </li>
                    <li class="mb-2">सदर जात प्रमाणपत्राच्या पडताळणीसाठी मी जात पडताळणी समितीकडे अर्ज करीत आहे.</li>
                </ol>

                <div class="w-full border-t-2 border-dashed border-red-400 text-center text-red-400 text-xs py-1 my-2 no-print select-none">
                    --- येथे पान संपले ---
                </div>
            </div>
        </div>

        {{-- ══════ A4 PAGE 4 — Type-specific Continuation + Signature + सत्यापन ══════ --}}
        <div class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mt-0" style="min-height:1123px;font-size:11pt;font-family:'Noto Sans Devanagari','Mukta',sans-serif;">
            <div class="wm bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>
            <div x-ref="gap4" class="bond-gap w-full relative overflow-hidden" style="height:60mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 4 GAP</span>
            </div>
            <div x-ref="pc4" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                <ol class="list-decimal ml-6 leading-8 text-justify mb-3" start="4">
                    <li class="mb-2">अर्जासोबत जोडलेली सर्व कागदपत्रे व पुरावे मी समक्ष प्राधीकार्‍याकडून प्राप्त केलेली असून, ती कागदपत्रे खरी आणि योग्य मार्गाने मिळवलेली आहेत.</li>
                    <li class="mb-2">माझ्या कोणत्याही नातीवाईकांचे जातीचे प्रमाणपत्र तपासणी समिती कडून कधीही अवैध ठरलेले नाही.</li>
                    <li class="mb-2">मी यापूर्वी महाराष्ट्रातील कोणत्याही जात पडताळणी समितीकडे अर्ज केलेला नाही व माझे जात प्रमाणपत्र अवैध झालेले नाही.</li>
                    <li class="mb-2">अर्जासोबत जोडलेली कागदपत्रे खोटे अथवा बनावट आढळल्यास मी त्यास जबाबदार असून भारतीय दंड विधान कायद्यानुसार शिक्षेस पात्र <span x-text="g.rahin"></span>.</li>
                </ol>

                <p class="leading-8 text-justify mb-6">
                    मी वर नमूद केलेली माहिती सत्य व खरी आहे म्हणून हे शपथपत्र करीत आहे.
                </p>

                <div class="flex justify-between items-start mb-8 mt-8">
                    <div>
                        <p class="leading-7">ठिकाण :- <span class="out-field" :class="place?'filled':'empty'" x-text="place||'\u00A0'"></span></p>
                        <p class="leading-7">दि. <span class="out-field" :class="fDt?'filled':'empty'" x-text="fDt||'\u00A0'"></span></p>
                    </div>
                    <div class="text-right">
                        <p class="leading-7 mb-1">सही</p>
                        <p class="leading-7"><span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span></p>
                    </div>
                </div>

                <div class="border-t border-gray-400 pt-4 mt-6">
                    <h3 class="font-bold text-center underline mb-4">सत्यापन</h3>
                    <p class="leading-8 text-justify mb-6">
                        मी विद्यामान न्यायालय सत्यप्रतिज्ञेवर <span x-text="g.kalvito"></span> कि, शपथपत्रातील सर्व मजकूर माझे/आमचे माहिती प्रमाणे पूर्णत: खरा व सत्य आहे. अगर हा मजकूर खोटा निघाल्यास मी/आम्ही कायद्यातील भा.द.वि.कलम १९३/२, १९९, २०० अन्वये गुन्ह्यास पात्र <span x-text="g.rahin"></span>/राहू करिता आज दिनांक :- <span class="out-field" :class="fDt?'filled':'empty'" x-text="fDt||'\u00A0'"></span> रोजी समक्ष स्वाक्षरी करीत आहे.
                    </p>
                    <div class="flex justify-between items-start mt-8">
                        <div>
                            <p class="leading-7">दि:- <span class="out-field" :class="fDt?'filled':'empty'" x-text="fDt||'\u00A0'"></span></p>
                            <p class="leading-7">ठिकाण:- <span class="out-field" :class="place?'filled':'empty'" x-text="place||'\u00A0'"></span></p>
                        </div>
                        <div class="text-right">
                            <p class="leading-7 mb-1">सही</p>
                            <p class="leading-7 mb-1">( प्रतिज्ञक )</p>
                            <p class="leading-7"><span class="out-field" :class="name?'filled':'empty'" x-text="name||'\u00A0'"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Guide Modal --}}
    <div x-show="guideOpen" x-transition class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" @click.self="guideOpen=false">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold mb-3 text-gray-800">कसे वापरावे?</h3>
            <ol class="text-sm text-gray-600 space-y-2 list-decimal pl-4">
                <li>प्रकार निवडा (शिक्षण/निवडणूक/सेवा/इतर).</li>
                <li>लिंग निवडा (पुरुष/स्त्री) — मजकूर आपोआप बदलेल.</li>
                <li>डाव्या बाजूला सर्व माहिती भरा.</li>
                <li>वंशावळ — मूळ पुरुष + पिढ्या जोडा, ✓ लाभार्थी निवडा.</li>
                <li>Page Gap/Pad स्लाइडर्सने स्टॅम्पपेपर adjust करा.</li>
                <li>"Pay & Print" बटणावर क्लिक करा.</li>
            </ol>
            <button @click="guideOpen=false" class="mt-4 w-full bg-indigo-500 text-white py-2 rounded-lg font-semibold hover:bg-indigo-600 transition">समजले!</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cv(){
    return {
        typ:'education', gender:'male', guideOpen:false,
        dt:'{{ date("Y-m-d") }}', place:'',
        name:'', father:'', age:'', occ:'शेती', caste:'',
        village:'', taluka:'', dist:'', state:'महाराष्ट्र',
        student:'', stuRel:'मुलगी', tahsil:'',
        officer:'कार्यकारी जिल्हा दंडाधिकारी', office:'', officeDist:'',
        certDt:'', certNo:'',
        off2:'', dist2:'', certNo2:'', certDt2:'',
        root:'', g2:[], lbId:null, _nid:1,
        walletBal:'{{ number_format($balance, 2) }}',

        addG2(){this.g2.push({id:this._nid++,name:'',children:[]})},
        addG3(i){this.g2[i].children.push({id:this._nid++,name:'',children:[]})},
        addG4(i2,i3){this.g2[i2].children[i3].children.push({id:this._nid++,name:''})},

        _fd(v){if(!v)return '';var d=new Date(v);return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();},
        get fDt(){return this._fd(this.dt)},
        get fCertDt(){return this._fd(this.certDt)},
        get fCertDt2(){return this._fd(this.certDt2)},

        get g(){
            var m=this.gender==='male';
            return {
                karto:m?'करतो':'करते', mazha:m?'माझा':'माझी', mazhi:'माझी',
                deto:m?'देतो':'देते', kalvito:m?'कळवितो':'कळविते',
                mulga:m?'मुलगा':'मुलगी', yancha:m?'यांचा':'यांची',
                shri:m?'श्री.':'सौ.', rahin:'राहीन'
            };
        },

        get tc(){
            var c={
                education:{rule:'नियम ४ (1) व (१४)',subtitle:'दावाकार्त्याचे / पालकाचे शपथपत्र',purpose:'',n2:'१७',n2t:'नमुना-१७',n2rule:'नियम १४',n2for:'-विद्यार्थ्याकरिता-',n2title:'जात प्रमाणपत्राच्या पडताळणीसाठी केलेल्या अर्जासोबत सादर करावयाचे शपथपत्र'},
                election:{rule:'नियम ४ (१) व (१४)',subtitle:'दावेदाराचे / पालकाचे शपथपत्र',purpose:'(निवडणूक कारणासाठी)',n2:'२१',n2t:'नमुना-२१',n2rule:'नियम १४',n2for:'(निवडणूक कारणासाठी)',n2title:'जात प्रमाणपत्र पडताळणी अर्जासोबत सादर करावयाचे शपथपत्र'},
                services:{rule:'नियम ४ (१)',subtitle:'दावेदाराचे / पालकाचे शपथपत्र',purpose:'(शासकीय / निमशासकीय सेवेसाठी)',n2:'१९',n2t:'नमुना-१९',n2rule:'नियम १४',n2for:'(शासकीय / निमशासकीय सेवेसाठी)',n2title:'जात प्रमाणपत्र पडताळणी अर्जासोबत सादर करावयाचे शपथपत्र'},
                other:{rule:'नियम ४ (१)',subtitle:'दावेदाराचे / पालकाचे शपथपत्र',purpose:'(शिक्षण, सेवा व निवडणूक व्यतिरिक्त इतर कारणासाठी)',n2:'२३',n2t:'नमुना-२३',n2rule:'नियम १४',n2for:'(शिक्षण, सेवा व निवडणूक व्यतिरिक्त इतर कारणासाठी)',n2title:'जात प्रमाणपत्र पडताळणी अर्जासोबत सादर करावयाचे शपथपत्र'}
            };
            return c[this.typ];
        },

        payAndPrint(){
            if(!this.name.trim()){alert('कृपया अर्जदाराचे नाव टाका');return;}
            if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for Cast Validity?'))return;
            var btn=document.getElementById('payBtn');
            btn.disabled=true;
            btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
            var self=this;
            fetch('{{ route("bonds.deductFee") }}',{
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
                body:JSON.stringify({slug:'cast-validity-education'}),
            })
            .then(function(r){return r.json();})
            .then(function(data){
                btn.disabled=false;
                btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';
                lucide.createIcons();
                if(data.status==='success'){
                    self.walletBal=data.new_balance;
                    document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});
                    window.print();
                    setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);
                }else{alert('Failed: '+data.message);}
            })
            .catch(function(){
                btn.disabled=false;
                btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';
                lucide.createIcons();
                alert('Payment failed.');
            });
        }
    };
}

document.addEventListener('DOMContentLoaded',function(){lucide.createIcons();});

var pa=document.querySelector('.preview-area');
if(pa){
    pa.addEventListener('copy',function(e){e.preventDefault();});
    pa.addEventListener('paste',function(e){e.preventDefault();var t=(e.clipboardData||window.clipboardData).getData('text/plain');if(t)document.execCommand('insertText',false,t);});
}
</script>
@endpush
