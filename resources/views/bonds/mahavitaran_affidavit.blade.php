@extends('layouts.bond-maker')
@section('title', 'महावितरण प्रतिज्ञापत्र')

@push('styles')
<style>
    .sig-block {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
</style>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-700 font-sans" id="root">

    {{-- ═══════════════════ LEFT PANEL ═══════════════════ --}}
    <div class="w-[360px] min-w-[360px] bg-[#f8fafc] overflow-y-auto flex flex-col border-r border-gray-200/60 z-10" id="leftPanel">

        {{-- Header --}}
        <div class="flex items-center justify-between px-3 py-2 bg-white border-b border-gray-100 sticky top-0 z-20">
            <div class="flex items-center gap-2">
                <a href="{{ route('bonds.index') }}" class="w-7 h-7 bg-gray-50 hover:bg-gray-100 rounded-lg flex items-center justify-center transition">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5 text-gray-500"></i>
                </a>
                <div>
                    <h2 class="text-[13px] font-bold text-gray-800 leading-tight">महावितरण प्रतिज्ञापत्र</h2>
                    <p class="text-[9px] text-gray-400">Mahavitaran Affidavit</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-6 h-6 bg-indigo-50 text-indigo-500 rounded-md text-[10px] font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-2.5 pb-3 space-y-2 mt-2">

            {{-- MODE TOGGLE --}}
            <div class="section-card p-2 bg-gray-50 flex gap-2 rounded-lg">
                <button id="btn_mode_v" class="flex-1 py-2 text-sm font-bold rounded shadow-sm bg-blue-600 text-white" onclick="setMode('v')">वैयक्तिक</button>
                <button id="btn_mode_s" class="flex-1 py-2 text-sm font-bold rounded hover:bg-gray-200 text-gray-600" onclick="setMode('s')">संस्थेसाठी</button>
            </div>

            {{-- VAYAKTIK FORM --}}
            <div id="form_vayaktik" class="space-y-2 block">
                <div class="section-card">
                    <div class="section-header">
                        <span class="num">१</span><span class="title">अर्जदाराचा तपशील (वैयक्तिक)</span>
                    </div>
                    <div class="section-body space-y-2.5">
                        <div><label class="field-label">प्रतिज्ञार्थी नाव</label><input type="text" id="inp_app_name" oninput="sync()" placeholder="संपूर्ण नाव" class="form-input"></div>
                        <div class="grid grid-cols-2 gap-2">
                            <div><label class="field-label">वय</label><input type="text" id="inp_app_age" oninput="sync()" placeholder="उदा. पस्तीस" class="form-input"></div>
                            <div><label class="field-label">मोबाईल नंबर</label><input type="text" id="inp_app_mobile" oninput="sync()" placeholder="९८७६५४३२१०" class="form-input"></div>
                        </div>
                        <div><label class="field-label">राहणार</label><input type="text" id="inp_app_addr" oninput="sync()" placeholder="गाव/पत्ता" class="form-input"></div>
                        <div class="grid grid-cols-2 gap-2">
                            <div><label class="field-label">तालुका</label><input type="text" id="inp_app_tal" oninput="sync()" placeholder="तालुका" class="form-input"></div>
                            <div><label class="field-label">जिल्हा</label><input type="text" id="inp_app_dist" oninput="sync()" placeholder="जिल्हा" class="form-input"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SANSTHA FORM --}}
            <div id="form_sanstha" class="space-y-2 hidden">
                <div class="section-card">
                    <div class="section-header">
                        <span class="num">१</span><span class="title">प्रतिनिधीचा तपशील</span>
                    </div>
                    <div class="section-body space-y-2.5">
                        <div><label class="field-label">अधिकृत प्रतिनिधीचे नाव</label><input type="text" id="inp_s_rep_name" oninput="sync()" placeholder="प्रतिनिधीचे संपूर्ण नाव" class="form-input"></div>
                        <div class="grid grid-cols-2 gap-2">
                            <div><label class="field-label">वय</label><input type="text" id="inp_s_rep_age" oninput="sync()" placeholder="उदा. पस्तीस" class="form-input"></div>
                            <div><label class="field-label">पद (Designation)</label><input type="text" id="inp_s_rep_desig" oninput="sync()" placeholder="उदा. अध्यक्ष / सचिव" class="form-input"></div>
                        </div>
                        <div><label class="field-label">मोबाईल नंबर</label><input type="text" id="inp_s_mobile" oninput="sync()" placeholder="९८७६५४३२१०" class="form-input"></div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-header">
                        <span class="num">२</span><span class="title">संस्थेचा तपशील</span>
                    </div>
                    <div class="section-body space-y-2.5">
                        <div><label class="field-label">कार्यालय / संस्थेचे नाव</label><input type="text" id="inp_s_org_name" oninput="sync()" placeholder="संस्थेचे नाव" class="form-input"></div>
                        <div><label class="field-label">कार्यालयाचा पत्ता</label><input type="text" id="inp_s_org_addr" oninput="sync()" placeholder="संपूर्ण पत्ता" class="form-input"></div>
                        <div class="grid grid-cols-2 gap-2">
                            <div><label class="field-label">तालुका</label><input type="text" id="inp_s_org_tal" oninput="sync()" placeholder="तालुका" class="form-input"></div>
                            <div><label class="field-label">जिल्हा</label><input type="text" id="inp_s_org_dist" oninput="sync()" placeholder="जिल्हा" class="form-input"></div>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <div class="section-header">
                        <span class="num">३</span><span class="title">वीज जोडणीचा पत्ता</span>
                    </div>
                    <div class="section-body space-y-2.5">
                        <div><label class="field-label">गाव / ठिकाण</label><input type="text" id="inp_s_village" oninput="sync()" placeholder="गाव" class="form-input"></div>
                        <div class="grid grid-cols-2 gap-2">
                            <div><label class="field-label">तालुका</label><input type="text" id="inp_s_tal" oninput="sync()" placeholder="तालुका" class="form-input"></div>
                            <div><label class="field-label">जिल्हा</label><input type="text" id="inp_s_dist" oninput="sync()" placeholder="जिल्हा" class="form-input"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SHARED INPUTS --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">#</span><span class="title">इतर तपशील</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div><label class="field-label">कार्यकारी दंडाधिकारी</label><input type="text" id="inp_magistrate" oninput="sync()" value="नांदगाव खंडेश्वर" class="form-input"></div>
                    <div><label class="field-label">उपविभागीय कार्यालय (महावितरण)</label><input type="text" id="inp_mseb_office" oninput="sync()" placeholder="कार्यालयाचे नाव" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">स्थळ</label><input type="text" id="inp_place" oninput="sync()" placeholder="उदा. अमरावती" class="form-input"></div>
                        <div><label class="field-label">दिनांक</label><input type="date" id="inp_date" oninput="sync()" class="form-input" onchange="sync()"></div>
                    </div>
                </div>
            </div>

            {{-- Wallet --}}
            <div class="flex items-center gap-2 px-2.5 py-2 bg-indigo-50/60 rounded-lg border border-indigo-100 mt-4 mb-2">
                <i data-lucide="wallet" class="w-4 h-4 text-indigo-500"></i>
                <span class="text-xs text-indigo-700 font-bold">Wallet: ₹<span id="walletBal">{{ number_format($balance, 2) }}</span></span>
            </div>

            {{-- Pay Button --}}
            <button onclick="payAndPrint()" id="payBtn"
                    class="w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-semibold py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-green-200 flex items-center justify-center gap-2 mb-2">
                <i data-lucide="printer" class="w-4 h-4"></i>
                Pay & Print (₹{{ number_format($format->fee, 0) }})
            </button>
        </div>
    </div>

    {{-- ═══════════════════ RIGHT PANEL — PREVIEW ═══════════════════ --}}
    <div class="flex-1 overflow-y-auto bg-[#555] pt-0 pb-8 px-4 relative preview-area">

        {{-- Controls Bar --}}
        <div class="ctrl-bar">
            <div class="ctrl-group"><label>Font</label><input type="range" min="8" max="16" value="11" oninput="setFont(this.value);this.nextElementSibling.textContent=this.value+'pt'"><span class="val">11pt</span></div>
            <div class="ctrl-divider"></div>
            <div class="ctrl-group"><label>P1 Gap</label><input type="range" min="0" max="200" value="180" oninput="setGap1(this.value);this.nextElementSibling.textContent=this.value"><span class="val">180</span></div>
            <div class="ctrl-group"><label>P1 Pad L</label><input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-group"><label>P1 Pad R</label><input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-divider"></div>
            <div class="ctrl-group"><label>P2 Gap</label><input type="range" min="0" max="200" value="30" oninput="setGap2(this.value);this.nextElementSibling.textContent=this.value"><span class="val">30</span></div>
            <div class="ctrl-group"><label>P2 Pad L</label><input type="range" min="5" max="80" value="40" oninput="setPad('page2_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-group"><label>P2 Pad R</label><input type="range" min="5" max="80" value="40" oninput="setPad('page2_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
        </div>

        {{-- ══════ A4 PAGE 1 ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-0"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Noto Sans Devanagari', 'Mukta', sans-serif;">
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>
            <div id="gap1" class="bond-gap w-full relative overflow-hidden" style="height:180mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 1 GAP (Adjust Slider for Stamp Paper)</span>
            </div>
            <div id="page1_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                <div class="text-center mb-4 relative">
                    <div class="inline-block bg-[#1a365d] text-white px-8 py-2 border-[3px] border-double border-white shadow-[0_0_0_2px_#1a365d] rounded-sm">
                        <h2 class="font-bold text-lg tracking-wide m-0 leading-none">प्रतिज्ञापत्र</h2>
                    </div>
                </div>

                <div class="bg-[#f9f9f9] border border-gray-400 p-3 mb-4 rounded-md shadow-sm">
                    <p class="font-bold text-center mb-1">विद्यमान कार्यकारी दंडाधिकारी ,</p>
                    <p class="font-bold text-center mb-1"><span id="out_magistrate" class="out-field filled">नांदगाव खंडेश्वर</span> ,</p>
                    <p class="font-bold text-center mb-0">यांचे समक्ष</p>
                </div>

                <div class="bg-[#f9f9f9] border border-gray-400 p-3 mb-4 rounded-md shadow-sm">

                    {{-- DRAFT VAYAKTIK (Top Info) --}}
                    <div id="draft_vayaktik_info" class="block">
                        <p class="mb-2 font-bold">
                            प्रतिज्ञार्थी :- <span id="out_app_name" class="out-field empty min-w-[200px] inline-block border-b border-dotted border-gray-500">&nbsp;</span>
                            वय <span id="out_app_age" class="out-field empty min-w-[50px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span> वर्ष
                        </p>
                        <p class="mb-2">
                            रा. <span id="out_app_addr" class="out-field empty min-w-[150px] inline-block border-b border-dotted border-gray-500">&nbsp;</span>
                            ता. <span id="out_app_tal" class="out-field empty min-w-[100px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>
                            जि. <span id="out_app_dist" class="out-field empty min-w-[100px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>
                            इथे राहत असून माझा मोबाईल नंबर <span id="out_app_mobile" class="out-field empty min-w-[120px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span> असा आहे.
                        </p>
                        <p class="mb-0 font-bold">मी खाली लिहून देतो कि,</p>
                    </div>

                    {{-- DRAFT SANSTHA (Top Info) --}}
                    <div id="draft_sanstha_info" class="hidden">
                        <p class="mb-2 font-bold">
                            प्रतिज्ञार्थी (अधिकृत प्रतिनिधीचे नाव) :- <span id="out_s_rep_name" class="out-field empty min-w-[200px] inline-block border-b border-dotted border-gray-500">&nbsp;</span>
                            वय <span id="out_s_rep_age" class="out-field empty min-w-[50px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span> वर्ष
                        </p>
                        <p class="mb-2">
                            पद (Designation) :- <span id="out_s_rep_desig" class="out-field empty min-w-[200px] inline-block border-b border-dotted border-gray-500">&nbsp;</span>
                        </p>
                        <p class="mb-2">
                            कार्यालय / संस्थेचे नाव :- <span id="out_s_org_name" class="out-field empty min-w-[250px] inline-block border-b border-dotted border-gray-500">&nbsp;</span>
                        </p>
                        <p class="mb-2">
                            कार्यालयाचा पत्ता :- <span id="out_s_org_addr" class="out-field empty min-w-[150px] inline-block border-b border-dotted border-gray-500">&nbsp;</span>
                            ता. <span id="out_s_org_tal" class="out-field empty min-w-[100px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>
                            जि. <span id="out_s_org_dist" class="out-field empty min-w-[100px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>
                        </p>
                        <p class="mb-2">
                            मोबाईल नंबर :- <span id="out_s_mobile" class="out-field empty min-w-[120px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>
                        </p>
                        <p class="mb-0 font-bold">मी कार्यालय / संस्थेचा अधिकृत प्रतिनिधी म्हणून या प्रतिज्ञापत्राद्वारे खाली लिहून देतो की,</p>
                    </div>

                </div>

                {{-- DRAFT VAYAKTIK (Content Page 1) --}}
                <div id="draft_vayaktik_content1" class="block">
                    <p class="leading-8 text-justify mb-2" style="text-indent: 40px;">
                        माझ्या नावाने गाव <span id="out_app_addr2" class="out-field empty min-w-[100px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>,
                        ता. <span id="out_app_tal2" class="out-field empty min-w-[80px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>,
                        जि. <span id="out_app_dist2" class="out-field empty min-w-[80px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span> येथे महावितरण च्या
                        <span id="out_mseb_office" class="out-field empty min-w-[150px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span> उपविभागीय
                        कार्यालयाच्या कार्यक्षेत्रात माझे घर असून मी या जागी घरगुती करिता वीज जोडणीसाठी अर्ज केलेला आहे. या घरावर महावितरणाची कोणतीही थकबाकी नाही व जुनी कोणतीही थकबाकी असल्याचे निदर्शनास आल्यास मी महावितरणाच्या नियमाप्रमाणे थकबाकी भरण्यास तयार आहे.
                    </p>
                </div>

                {{-- DRAFT SANSTHA (Content Page 1) --}}
                <div id="draft_sanstha_content1" class="hidden">
                    <p class="leading-8 text-justify mb-2" style="text-indent: 40px;">
                        आमच्या कार्यालयाच्या / संस्थेच्या नावाने गाव <span id="out_s_village" class="out-field empty min-w-[100px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>,
                        ता. <span id="out_s_tal" class="out-field empty min-w-[80px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span>,
                        जि. <span id="out_s_dist" class="out-field empty min-w-[80px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span> येथे महावितरण च्या
                        <span id="out_mseb_office2" class="out-field empty min-w-[150px] inline-block border-b border-dotted border-gray-500 text-center">&nbsp;</span> उपविभागीय
                        कार्यालयाच्या कार्यक्षेत्रात आमचे कार्यालय/जागा असून, आम्ही या जागी कार्यालयीन / वाणिज्यिक वापराकरिता वीज जोडणीसाठी संस्थेच्या वतीने अर्ज केलेला आहे. या जागेवर महावितरणाची कोणतीही थकबाकी नाही व जुनी कोणतीही थकबाकी असल्याचे निदर्शनास आल्यास मी/संस्था महावितरणाच्या नियमाप्रमाणे थकबाकी भरण्यास तयार आहे.
                    </p>
                </div>

                <div id="redLine" class="w-full border-t-2 border-dashed border-red-400 text-center text-red-400 text-xs py-1 my-2 no-print select-none" contenteditable="false">--- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---</div>
            </div>
        </div>

        {{-- ══════ A4 PAGE 2 ══════ --}}
        <div id="page2" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mt-0"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Noto Sans Devanagari', 'Mukta', sans-serif;">
            <div id="watermark2" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>
            <div id="gap2" class="bond-gap w-full relative overflow-hidden" style="height:30mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 2 GAP (Adjust Slider)</span>
            </div>
            <div id="page2_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                {{-- DRAFT VAYAKTIK (Content Page 2) --}}
                <div id="draft_vayaktik_content2" class="block">
                    <p class="leading-8 text-justify mb-2" style="text-indent: 40px;">
                        तसेच सदर जागे संदर्भात जर न्यायालयीन प्रकरण न्यायप्रविष्ट असेल तर त्या प्रकरणाच्या निर्णयाधीन राहून ही वीज जोडणी असल्याचे मान्य करून अंगीकृत केले आहे. तसेच जागे संदर्भात जर वाद उद्भवल्यास अथवा कायदेशीर हरकत घेतल्यास जर वीज जोडणी करिता विलंब होत असेल तर त्याकरिता सदर अर्जदार सर्वस्वी जबाबदार असून सदर वाद अथवा हरकत मिटवायची/निकाली काढण्याची जबाबदारी माझी असेल व सदर विलंबाकरिता अर्जदार MERC SOP Regulation प्रमाणे महावितरण कंपनीस जबाबदार धरणार नाही, हे मी स्वीकृत करत असून, हे मला मान्य आहे.
                    </p>
                    <p class="leading-8 text-justify mb-4" style="text-indent: 40px;">
                        मी या सामाजिक जबाबदारी बंधपत्राद्वारे लिहून देतो की मला या योजनेत सहभाग घेण्यासाठी असलेल्या अटी शर्ती वाचल्या असून/समजावून सांगितल्या असून त्या मला मान्य आहेत. तसेच या योजनेच्या अनुषंगाने वीज जोडणी दिली गेल्यास महावितरणाच्या सर्व अटी व शर्तींचे पालन करून घरगुती चालू बिल नियमित भरून सामाजिक जबाबदारीचे पूर्णपणे पालन करू. माझ्याकडून वीज बिल/देयक भरले नाही तर वीज जोडणी खंडित करण्यात यावी यावर माझा कोणताही आक्षेप/उजर असणार नाही. वीज जोडणी झाल्यावर विद्युत जोडणीला एल.सी.बी. (E.L.C.B.) बसविणे बंधनकारक राहील याची मला पूर्णपणे जाणीव आहे.
                    </p>
                    <p class="leading-8 text-justify mb-6" style="text-indent: 40px;">
                        सदर प्रतिज्ञालेख मी कोणत्याही दबावाखाली न येता संपूर्ण शुद्धीत लिहून देत आहे.
                    </p>
                </div>

                {{-- DRAFT SANSTHA (Content Page 2) --}}
                <div id="draft_sanstha_content2" class="hidden">
                    <p class="leading-8 text-justify mb-2" style="text-indent: 40px;">
                        तसेच सदर जागे संदर्भात जर न्यायालयीन प्रकरण न्यायप्रविष्ट असेल तर त्या प्रकरणाच्या निर्णयाधीन राहून ही वीज जोडणी असल्याचे मान्य करून अंगीकृत केले आहे. तसेच जागे संदर्भात जर वाद उद्भवल्यास अथवा कायदेशीर हरकत घेतल्यास जर वीज जोडणी करिता विलंब होत असेल तर त्याकरिता सदर अर्जदार (संस्था/कार्यालय) सर्वस्वी जबाबदार असून सदर वाद अथवा हरकत मिटवायची/निकाली काढण्याची जबाबदारी आमची असेल व सदर विलंबाकरिता अर्जदार MERC SOP Regulation प्रमाणे महावितरण कंपनीस जबाबदार धरणार नाही, हे मी संस्थेच्या वतीने स्वीकृत करत असून, हे आम्हाला मान्य आहे.
                    </p>
                    <p class="leading-8 text-justify mb-4" style="text-indent: 40px;">
                        मी या सामाजिक जबाबदारी बंधपत्राद्वारे लिहून देतो की मला या योजनेत सहभाग घेण्यासाठी असलेल्या अटी शर्ती वाचल्या असून/समजावून सांगितल्या असून त्या आम्हाला मान्य आहेत. तसेच या योजनेच्या अनुषंगाने वीज जोडणी दिली गेल्यास महावितरणाच्या सर्व अटी व शर्तींचे पालन करून कार्यालयाचे चालू बिल नियमित भरून सामाजिक जबाबदारीचे पूर्णपणे पालन करू. आमच्याकडून वीज बिल/देयक भरले नाही तर वीज जोडणी खंडित करण्यात यावी यावर आमचा कोणताही आक्षेप/उजर असणार नाही. वीज जोडणी झाल्यावर विद्युत जोडणीला एल.सी.बी. (E.L.C.B.) बसविणे बंधनकारक राहील याची मला/संस्थेला पूर्णपणे जाणीव आहे.
                    </p>
                    <p class="leading-8 text-justify mb-6" style="text-indent: 40px;">
                        सदर प्रतिज्ञालेख मी कोणत्याही दबावाखाली न येता संपूर्ण शुद्धीत कार्यालयाच्या / संस्थेच्या वतीने लिहून देत आहे व सदर प्रतिज्ञालेख कार्यालयावर व पुढील पदाधिकाऱ्यांवर बंधनकारक राहील.
                    </p>
                </div>

                {{-- Signature Block 1 --}}
                <div class="sig-block mt-2 mb-6">
                    <div>
                        <p class="leading-7 mb-1 font-bold">दिनांक :- <span id="out_date" class="out-field empty" style="font-weight:normal; border-bottom: 2px dotted #000; min-width:150px; display:inline-block; text-align:center;">............................</span></p>
                        <p class="leading-7 mb-0 font-bold">ठिकाण :- <span id="out_place" class="out-field empty" style="font-weight:normal; border-bottom: 2px dotted #000; min-width:150px; display:inline-block; text-align:center;">............................</span></p>
                    </div>
                    <div class="text-center pr-4">
                        <p class="mb-8 text-transparent select-none">.</p>
                        <p class="mb-0 font-bold px-4" id="lbl_sig_1">प्रतिज्ञार्थी सही</p>
                        <p class="font-normal text-[14px] mt-1" style="min-width: 150px;"><span id="out_app_name_sig1" class="out-field empty text-center inline-block w-full border-t border-dashed border-gray-400 pt-1 text-gray-700">&nbsp;</span></p>
                    </div>
                </div>

                {{-- Verification Block --}}
                <div class="mt-4">
                    <div class="border-[2px] border-[#333] p-5 rounded-md relative shadow-sm bg-gray-50/50">
                        <h3 class="text-center font-bold mb-3">* सत्यापन *</h3>

                        <p id="draft_vayaktik_ver" class="leading-8 text-justify mt-2 mb-4 block" style="text-indent: 40px;">
                            मी / आम्ही वरील दिलेली सर्व माहिती माझ्या / आमच्या व्यक्तिगत माहिती व समजुती नुसार खरी आहे. सदर माहिती खोटी आढळून आल्यास मी / आम्ही भारतीय न्यायसंहिता कलम २३६, २३७ व २२९ (२) अन्य / संबंधित कायद्यानुसार माझ्यावर / आमच्यावर खटला भरला जाईल त्यानुसार मी / आम्ही शिक्षेस पात्र राहील, याची मला / आम्हाला पूर्ण जाणीव आहे.
                        </p>
                        <p id="draft_sanstha_ver" class="leading-8 text-justify mt-2 mb-4 hidden" style="text-indent: 40px;">
                            मी वरील दिलेली सर्व माहिती माझ्या व्यक्तिगत तसेच कार्यालयीन माहिती व समजुती नुसार खरी आहे. सदर माहिती खोटी आढळून आल्यास मी व माझी संस्था भारतीय न्यायसंहिता कलम २३६, २३७ व २२९ (२) अन्य / संबंधित कायद्यानुसार खटल्यास पात्र राहील व त्यानुसार मी / आम्ही शिक्षेस पात्र राहू, याची मला पूर्ण जाणीव आहे.
                        </p>

                        {{-- Signature Block 2 inside verification box --}}
                        <div class="sig-block mt-1 mb-0">
                            <div>
                                <p class="leading-7 mb-1 font-bold">दिनांक :- <span id="out_date2" class="out-field empty" style="font-weight:normal; border-bottom: 2px dotted #000; min-width:150px; display:inline-block; text-align:center;">............................</span></p>
                                <p class="leading-7 mb-0 font-bold">स्थळ :- <span id="out_place2" class="out-field empty" style="font-weight:normal; border-bottom: 2px dotted #000; min-width:150px; display:inline-block; text-align:center;">............................</span></p>
                            </div>
                            <div class="text-center pr-4">
                                <p class="mb-8 text-transparent select-none">.</p>
                                <p class="mb-0 font-bold px-4" id="lbl_sig_2">प्रतिज्ञार्थी सही</p>
                                <p class="font-normal text-[14px] mt-1" style="min-width: 150px;"><span id="out_app_name_sig2" class="out-field empty text-center inline-block w-full border-t border-dashed border-gray-400 pt-1 text-gray-700">&nbsp;</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ═══════════════════ GUIDE MODAL ═══════════════════ --}}
<div id="guideModal" class="fixed inset-0 bg-black/60 z-50 items-center justify-center hidden" onclick="closeGuide(event)">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">मार्गदर्शन</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="text-sm text-gray-700 space-y-3">
            <div><p class="font-bold text-gray-800 mb-1">1. तपशील भरा:</p><p>डावीकडे अर्जदाराचे तपशील भरा. वैयक्तिक किंवा संस्थेसाठी पर्याय निवडा.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. डिझाईन आणि प्रिंट:</p><p>हे प्रतिज्ञापत्र 2 पानांवर डिझाईन केले आहे. P1 Gap = Stamp Paper Gap, P2 Gap = Second page gap.</p></div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3"><p class="text-blue-700 text-xs">PDF साठी: 'Print' वर क्लिक करा → प्रिंट विंडोमध्ये 'Save as PDF' निवडा. मार्जिन 'None' निवडा.</p></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var currentMode = 'v'; // 'v' = vayaktik, 's' = sanstha

function openGuide(){document.getElementById('guideModal').classList.remove('hidden');document.getElementById('guideModal').classList.add('flex');}
function closeModal(){document.getElementById('guideModal').classList.add('hidden');document.getElementById('guideModal').classList.remove('flex');}
function closeGuide(e){if(e.target.id==='guideModal')closeModal();}

function setGap1(v){document.getElementById('gap1').style.height=v+'mm';checkOverflow();}
function setGap2(v){document.getElementById('gap2').style.height=v+'mm';}
function setFont(v){if(typeof bondSetFontAll==='function')bondSetFontAll(v);checkOverflow();}
function setPad(id,prop,v){var el=document.getElementById(id);if(el)el.style[prop]=v+'px';}

function setMode(mode) {
    currentMode = mode;
    var btnV = document.getElementById('btn_mode_v');
    var btnS = document.getElementById('btn_mode_s');
    var fV = document.getElementById('form_vayaktik');
    var fS = document.getElementById('form_sanstha');

    if (mode === 'v') {
        btnV.className = "flex-1 py-2 text-sm font-bold rounded shadow-sm bg-blue-600 text-white";
        btnS.className = "flex-1 py-2 text-sm font-bold rounded hover:bg-gray-200 text-gray-600";
        fV.classList.remove('hidden'); fV.classList.add('block');
        fS.classList.add('hidden'); fS.classList.remove('block');

        document.getElementById('draft_vayaktik_info').classList.replace('hidden', 'block');
        document.getElementById('draft_vayaktik_content1').classList.replace('hidden', 'block');
        document.getElementById('draft_vayaktik_content2').classList.replace('hidden', 'block');
        document.getElementById('draft_vayaktik_ver').classList.replace('hidden', 'block');

        document.getElementById('draft_sanstha_info').classList.replace('block', 'hidden');
        document.getElementById('draft_sanstha_content1').classList.replace('block', 'hidden');
        document.getElementById('draft_sanstha_content2').classList.replace('block', 'hidden');
        document.getElementById('draft_sanstha_ver').classList.replace('block', 'hidden');

        document.getElementById('lbl_sig_1').innerText = "प्रतिज्ञार्थी सही";
        document.getElementById('lbl_sig_2').innerText = "प्रतिज्ञार्थी सही";
    } else {
        btnS.className = "flex-1 py-2 text-sm font-bold rounded shadow-sm bg-blue-600 text-white";
        btnV.className = "flex-1 py-2 text-sm font-bold rounded hover:bg-gray-200 text-gray-600";
        fS.classList.remove('hidden'); fS.classList.add('block');
        fV.classList.add('hidden'); fV.classList.remove('block');

        document.getElementById('draft_vayaktik_info').classList.replace('block', 'hidden');
        document.getElementById('draft_vayaktik_content1').classList.replace('block', 'hidden');
        document.getElementById('draft_vayaktik_content2').classList.replace('block', 'hidden');
        document.getElementById('draft_vayaktik_ver').classList.replace('block', 'hidden');

        document.getElementById('draft_sanstha_info').classList.replace('hidden', 'block');
        document.getElementById('draft_sanstha_content1').classList.replace('hidden', 'block');
        document.getElementById('draft_sanstha_content2').classList.replace('hidden', 'block');
        document.getElementById('draft_sanstha_ver').classList.replace('hidden', 'block');

        document.getElementById('lbl_sig_1').innerText = "सही व कार्यालयाचा शिक्का";
        document.getElementById('lbl_sig_2').innerText = "प्रतिज्ञार्थी सही व शिक्का";
    }
    sync();
    checkOverflow();
}

function checkOverflow(){
    var b=document.getElementById('redLine');
    if(!b)return;
    if(b.offsetTop+b.offsetHeight > 1123){
        b.classList.add('text-red-700','font-bold','bg-red-50');
        b.innerText='सावधान! मजकूर रेषेच्या खाली गेला आहे. Gap कमी करा किंवा Font लहान करा.';
    } else {
        b.classList.remove('text-red-700','font-bold','bg-red-50');
        b.innerText='--- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---';
    }
}

function fmtDMY(v){if(!v)return '';var d=new Date(v);return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();}

var syncMap = {
    'inp_magistrate':['out_magistrate'],
    'inp_mseb_office':['out_mseb_office', 'out_mseb_office2'],
    'inp_app_name':['out_app_name'],
    'inp_app_age':['out_app_age'],
    'inp_app_mobile':['out_app_mobile'],
    'inp_app_addr':['out_app_addr', 'out_app_addr2'],
    'inp_app_tal':['out_app_tal', 'out_app_tal2'],
    'inp_app_dist':['out_app_dist', 'out_app_dist2'],
    'inp_s_rep_name':['out_s_rep_name'],
    'inp_s_rep_age':['out_s_rep_age'],
    'inp_s_rep_desig':['out_s_rep_desig'],
    'inp_s_org_name':['out_s_org_name'],
    'inp_s_org_addr':['out_s_org_addr'],
    'inp_s_org_tal':['out_s_org_tal'],
    'inp_s_org_dist':['out_s_org_dist'],
    'inp_s_mobile':['out_s_mobile'],
    'inp_s_village':['out_s_village'],
    'inp_s_tal':['out_s_tal'],
    'inp_s_dist':['out_s_dist']
};

function sync(){
    for(var inpId in syncMap){
        var el_inp = document.getElementById(inpId);
        if(!el_inp) continue;
        var val = el_inp.value;
        syncMap[inpId].forEach(function(outId){
            var el = document.getElementById(outId);
            if(el){
                el.innerText=val||'\u00A0';
                if(val){ el.classList.remove('empty'); el.classList.add('filled'); }
                else{ el.classList.add('empty'); el.classList.remove('filled'); }
            }
        });
    }

    var repName = currentMode === 'v' ? document.getElementById('inp_app_name').value : document.getElementById('inp_s_rep_name').value;
    ['out_app_name_sig1', 'out_app_name_sig2'].forEach(function(id){
        var el = document.getElementById(id);
        if(el) {
            el.innerText = repName || '\u00A0';
            if(repName){ el.classList.remove('empty'); el.classList.add('filled'); }
            else{ el.classList.add('empty'); el.classList.remove('filled'); }
        }
    });

    var rawDate = document.getElementById('inp_date').value;
    var outDateVal = rawDate ? fmtDMY(rawDate) : '\u00A0';
    ['out_date', 'out_date2'].forEach(function(id){
        var el=document.getElementById(id);
        if(el){
            el.innerText=outDateVal;
            if(rawDate){el.classList.remove('empty'); el.classList.add('filled');}
            else{el.classList.add('empty'); el.classList.remove('filled');}
        }
    });

    ['out_place', 'out_place2'].forEach(function(id){
        var el=document.getElementById(id);
        var val=document.getElementById('inp_place').value;
        if(el){
            el.innerText=val||'\u00A0';
            if(val){el.classList.remove('empty'); el.classList.add('filled');}
            else{el.classList.add('empty'); el.classList.remove('filled');}
        }
    });

    checkOverflow();
}

function payAndPrint(){
    if(currentMode === 'v') {
        if(!document.getElementById('inp_app_name').value.trim()){alert('कृपया अर्जदाराचे नाव टाका');return;}
    } else {
        if(!document.getElementById('inp_s_rep_name').value.trim()){alert('कृपया प्रतिनिधीचे नाव टाका');return;}
    }

    var boundary=document.getElementById('redLine');
    if(boundary&&boundary.innerText.includes('सावधान')){
        if(!confirm('Warning: मजकूर पानाबाहेर गेला आहे. तरीही प्रिंट करायचे का?'))return;
    }
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for Mahavitaran Affidavit?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'mahavitaran-affidavit'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}

        window.onload=function(){sync();if(typeof lucide!=='undefined')lucide.createIcons();};
</script>
@endpush
