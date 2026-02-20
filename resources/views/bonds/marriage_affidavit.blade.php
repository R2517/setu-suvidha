@extends('layouts.bond-maker')
@section('title', 'प्रथम विवाह नोंद प्रतिज्ञापत्र')

@push('styles')
<style>
    #watermark1 { user-select: none; }
</style>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-700 font-sans" id="root">

    {{-- ═══════════════════ LEFT PANEL ═══════════════════ --}}
    <div class="w-[360px] min-w-[360px] bg-[#f8fafc] overflow-y-auto flex flex-col border-r border-gray-200/60 z-10" id="leftPanel">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100 sticky top-0 z-20">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('bonds.index') }}" class="w-8 h-8 bg-gray-50 hover:bg-gray-100 rounded-lg flex items-center justify-center transition">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-gray-500"></i>
                </a>
                <div>
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">विवाह नोंद प्रतिज्ञापत्र</h2>
                    <p class="text-[10px] text-gray-400">Marriage Registration Affidavit</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SECTION 1: तहसील & दिनांक --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">1</span>
                    <span class="title">तहसील & दिनांक / ठिकाण</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">तहसील</label>
                        <input type="text" id="inp_tahsil" value="नांदगाव खंडेश्वर" oninput="sync()" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">जिल्हा</label>
                        <input type="text" id="inp_district" value="अमरावती" oninput="sync()" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">ठिकाण (सही करतानाचे)</label>
                            <input type="text" id="inp_place" value="पापळ" oninput="sync()" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">दिनांक</label>
                            <input type="date" id="inp_date" value="{{ date('Y-m-d') }}" oninput="sync()" class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: पती (Husband) --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">2</span>
                    <span class="title">प्रतिज्ञार्थी (पती)</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">पतीचे पूर्ण नाव (चि./श्री.)</label>
                        <input type="text" id="inp_h_name" oninput="sync()" placeholder="चि. विजय दिनेश चावरे" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">वय (वर्षे)</label>
                            <input type="number" id="inp_h_age" oninput="sync()" placeholder="25" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">जन्म तारीख</label>
                            <input type="date" id="inp_h_dob" oninput="sync()" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">धर्म</label>
                            <input type="text" id="inp_h_religion" value="हिंदू" oninput="sync()" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">जात</label>
                            <input type="text" id="inp_h_caste" oninput="sync()" placeholder="कलाल" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">पत्ता (पूर्ण)</label>
                        <textarea id="inp_h_addr" oninput="sync()" rows="3" class="form-textarea" placeholder="रा. मु. सुकली गुरव, पोस्ट काजना, ग्राम पंचायत : पापळ, ता. नांदगाव खंडेश्वर, जि. अमरावती, महाराष्ट्र राज्य."></textarea>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: पत्नी (Wife) --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">3</span>
                    <span class="title">प्रतिज्ञार्थी (पत्नी)</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">पत्नीचे पूर्ण नाव (कु./सौ.)</label>
                        <input type="text" id="inp_w_name" oninput="sync()" placeholder="कु. श्रद्धा सुरेश शिवने" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">वय (वर्षे)</label>
                            <input type="number" id="inp_w_age" oninput="sync()" placeholder="25" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">जन्म तारीख</label>
                            <input type="date" id="inp_w_dob" oninput="sync()" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">धर्म</label>
                            <input type="text" id="inp_w_religion" value="हिंदू" oninput="sync()" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">जात</label>
                            <input type="text" id="inp_w_caste" oninput="sync()" placeholder="कलाल" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">पत्ता (पूर्ण)</label>
                        <textarea id="inp_w_addr" oninput="sync()" rows="3" class="form-textarea" placeholder="रा. ग्राम रमपुरी, तहसील कुरई, जि. शिवनी, राज्य – मध्यप्रदेश."></textarea>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: विवाह तपशील --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">4</span>
                    <span class="title">विवाह तपशील</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">विवाह दिनांक</label>
                            <input type="date" id="inp_marriage_date" oninput="sync()" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">विवाह वेळ</label>
                            <input type="text" id="inp_marriage_time" oninput="sync()" placeholder="रात्री ८.०० वाजता" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">विवाह ठिकाण (पूर्ण)</label>
                        <textarea id="inp_marriage_place" oninput="sync()" rows="2" class="form-textarea" placeholder="ग्राम रमपुरी, तहसील कुरई, जि. शिवनी, राज्य मध्यप्रदेश"></textarea>
                    </div>
                    <div>
                        <label class="field-label">विवाह स्थळ वर्णन</label>
                        <input type="text" id="inp_marriage_venue_desc" oninput="sync()" placeholder="वधूच्या स्वतःच्या राहत्या घरी" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">विवाह विधी / धर्म परंपरा</label>
                        <input type="text" id="inp_marriage_ritual" value="हिंदू धर्माच्या विधी व परंपरेनुसार" oninput="sync()" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">पुरावे (विवाह पत्रिका, फोटो, इ.)</label>
                        <input type="text" id="inp_evidence" value="विवाह पत्रिका," oninput="sync()" class="form-input">
                    </div>
                </div>
            </div>

            {{-- SECTION 5: नोंद तपशील --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">5</span>
                    <span class="title">नोंद तपशील</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">ग्रामपंचायत (नोंद करण्याची)</label>
                        <input type="text" id="inp_grampanchayat" oninput="sync()" placeholder="पापळ" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">ता. (तालुका)</label>
                        <input type="text" id="inp_nond_taluka" oninput="sync()" placeholder="नांदगाव खंडेश्वर" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">जि. (जिल्हा)</label>
                        <input type="text" id="inp_nond_district" oninput="sync()" placeholder="अमरावती" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">विवाहानंतर किती कालावधी झाला</label>
                        <input type="text" id="inp_time_elapsed" oninput="sync()" placeholder="सुमारे ६ महिने" class="form-input">
                    </div>
                </div>
            </div>

            {{-- Wallet --}}
            <div class="flex items-center gap-2 px-3 py-2.5 bg-indigo-50/60 rounded-xl border border-indigo-100">
                <i data-lucide="wallet" class="w-3.5 h-3.5 text-indigo-400"></i>
                <span class="text-[11px] text-indigo-600 font-medium">Wallet: ₹<span id="walletBal">{{ number_format($balance, 2) }}</span></span>
            </div>

            {{-- Pay Button --}}
            <button onclick="payAndPrint()" id="payBtn"
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
                <input type="range" min="8" max="16" value="11" oninput="setFont(this.value);this.nextElementSibling.textContent=this.value+'pt'">
                <span class="val">11pt</span>
            </div>
            <div class="ctrl-divider"></div>
            <div class="ctrl-group">
                <label>P1 Gap</label>
                <input type="range" min="0" max="200" value="80" oninput="setGap1(this.value);this.nextElementSibling.textContent=this.value">
                <span class="val">80</span>
            </div>
            <div class="ctrl-group">
                <label>P1 Pad L</label>
                <input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value">
                <span class="val">40</span>
            </div>
            <div class="ctrl-group">
                <label>P1 Pad R</label>
                <input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value">
                <span class="val">40</span>
            </div>
        </div>

        {{-- ══════ A4 PAGE 1 ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-0"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Noto Sans Devanagari', 'Mukta', sans-serif;">

            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="gap1" class="bond-gap w-full relative overflow-hidden"
                 style="height: 80mm; background: repeating-linear-gradient(45deg, #e8e8e8, #e8e8e8 2px, #f5f5f5 2px, #f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">
                    PAGE 1 GAP (Adjust Slider for Stamp Paper)
                </span>
            </div>

            <div id="page1_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                {{-- Title --}}
                <h2 class="text-center font-bold text-lg underline mb-1">प्रतिज्ञापत्र (Affidavit Format)</h2>
                <p class="text-center font-semibold text-sm mb-4">(प्रथम विवाह नोंद प्रतिज्ञापत्र)</p>

                {{-- Addressed To --}}
                <p class="leading-8 mb-0">मा. विद्यमान कार्यकारी दंडाधिकारी साहेब,</p>
                <p class="leading-8 mb-0">तहसील – <span id="out_tahsil" class="out-field filled">नांदगाव खंडेश्वर</span>,</p>
                <p class="leading-8 mb-4">जि. <span id="out_district" class="out-field filled">अमरावती</span>.</p>

                <p class="leading-8 text-justify mb-4">
                    मी खाली सही करणारे पती–पत्नी, खालीलप्रमाणे सत्यप्रतिज्ञेवर प्रतिज्ञापत्र देत आहोत —
                </p>

                {{-- Husband & Wife Info Side by Side --}}
                <div class="flex gap-6 mb-4" style="font-size:10.5pt;">
                    <div class="flex-1 border border-gray-400 rounded px-3 py-2">
                        <p class="font-bold underline mb-1">प्रतिज्ञार्थी (पती) :</p>
                        <p class="leading-7 mb-0">नाव : <span id="out_h_name" class="out-field empty">&nbsp;</span></p>
                        <p class="leading-7 mb-0">वय : <span id="out_h_age" class="out-field empty">&nbsp;</span> वर्षे (जन्म तारीख : <span id="out_h_dob" class="out-field empty">&nbsp;</span>)</p>
                        <p class="leading-7 mb-0">धर्म : <span id="out_h_religion" class="out-field filled">हिंदू</span> &nbsp; जात : <span id="out_h_caste" class="out-field empty">&nbsp;</span></p>
                        <p class="leading-7 mb-0">रा. <span id="out_h_addr" class="out-field empty">&nbsp;</span></p>
                    </div>
                    <div class="flex-1 border border-gray-400 rounded px-3 py-2">
                        <p class="font-bold underline mb-1">प्रतिज्ञार्थी (पत्नी) :</p>
                        <p class="leading-7 mb-0">नाव : <span id="out_w_name" class="out-field empty">&nbsp;</span></p>
                        <p class="leading-7 mb-0">वय : <span id="out_w_age" class="out-field empty">&nbsp;</span> वर्षे (जन्म तारीख : <span id="out_w_dob" class="out-field empty">&nbsp;</span>)</p>
                        <p class="leading-7 mb-0">धर्म : <span id="out_w_religion" class="out-field filled">हिंदू</span> &nbsp; जात : <span id="out_w_caste" class="out-field empty">&nbsp;</span></p>
                        <p class="leading-7 mb-0">रा. <span id="out_w_addr" class="out-field empty">&nbsp;</span></p>
                    </div>
                </div>

                <p class="font-bold underline mb-2">विषय : प्रथम विवाह नोंद करण्याबाबत प्रतिज्ञापत्र</p>

                <p class="leading-8 text-justify mb-3">
                    आम्ही दोघे पती–पत्नी सत्यप्रतिज्ञेवर खालीलप्रमाणे निवेदन करीत आहोत की —
                </p>

                {{-- Points --}}
                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">1.</span>&nbsp;आमचा विवाह दिनांक <span id="out_marriage_date" class="out-field empty">&nbsp;</span> रोजी <span id="out_marriage_time" class="out-field empty">&nbsp;</span>,
                    <span id="out_marriage_place" class="out-field empty">&nbsp;</span> येथे
                    (<span id="out_marriage_venue_desc" class="out-field empty">&nbsp;</span>)
                    <span id="out_marriage_ritual" class="out-field filled">हिंदू धर्माच्या विधी व परंपरेनुसार</span> विधिवत पार पडलेला आहे.
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">2.</span>&nbsp;सदर विवाह हा आमचा दोघांचाही प्रथम विवाह असून यापूर्वी आमच्यापैकी कुणाचाही कोणत्याही व्यक्तीशी विवाह झालेला नव्हता.
                </p>

                <p class="leading-8 text-justify mb-1">
                    <span class="font-semibold">3.</span>&nbsp;सदर विवाहाचे पुरावे म्हणून खालील कागदपत्रे उपलब्ध आहेत —
                </p>
                <p class="leading-8 ml-8 mb-2">
                    ○&nbsp;&nbsp;<span id="out_evidence" class="out-field filled">विवाह पत्रिका,</span>
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">4.</span>&nbsp;काही कारणास्तव आमच्या विवाहाची नोंद यापूर्वी कोणत्याही ग्रामपंचायतीत झालेली नव्हती.
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">5.</span>&nbsp;सध्या आम्हाला आमच्या विवाहाची अधिकृत नोंद ग्रामपंचायत <span id="out_grampanchayat" class="out-field empty">&nbsp;</span>,
                    ता. <span id="out_nond_taluka" class="out-field empty">&nbsp;</span>, जि. <span id="out_nond_district" class="out-field empty">&nbsp;</span> येथे करावयाची आहे, म्हणून हे प्रतिज्ञापत्र देत आहोत.
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">6.</span>&nbsp;विवाहानंतर माझ्या पत्नीचे आधार कार्ड, पॅन कार्ड, मतदार ओळखपत्र, रेशन कार्ड ही कागदपत्रे पतीच्या नावाने तयार करण्यात आलेली आहेत.
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">7.</span>&nbsp;आमच्या विवाहास आज दिनांकास <span id="out_time_elapsed" class="out-field empty">&nbsp;</span> पूर्ण झाले असून, आम्ही पती–पत्नी म्हणून एकत्र वास्तव्यास आहोत.
                </p>

                <p class="leading-8 text-justify mb-4">
                    <span class="font-semibold">8.</span>&nbsp;या प्रतिज्ञापत्रात नमूद केलेली सर्व माहिती पूर्णतः खरी, अचूक व कोणत्याही दबावाशिवाय दिलेली आहे.
                </p>

                {{-- Signatures --}}
                <div class="flex justify-between items-start mt-6 mb-4">
                    <div>
                        <p class="leading-7 mb-0">ठिकाण : <span id="out_place_top" class="out-field filled">पापळ</span></p>
                        <p class="leading-7 mb-0">दिनांक : <span id="out_date_top" class="out-field filled">{{ date('d/m/Y') }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="leading-7 mb-1">प्रतिज्ञार्थी (पती) सही : _______________________</p>
                        <p class="leading-7 mb-0">नाव : <span id="out_h_name_sig1" class="out-field empty">&nbsp;</span></p>
                        <p class="leading-7 mt-3 mb-1">प्रतिज्ञार्थी (पत्नी) सही : _____________________</p>
                        <p class="leading-7 mb-0">नाव : <span id="out_w_name_sig1" class="out-field empty">&nbsp;</span></p>
                    </div>
                </div>

                <div id="redLine" class="w-full border-t-2 border-dashed border-red-400 text-center text-red-400 text-xs py-1 my-2 no-print select-none">
                    --- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---
                </div>

                {{-- ═══ सत्यापन (Verification) ═══ --}}
                <h3 class="text-center font-bold text-base underline mt-6 mb-3">सत्यापन</h3>

                <p class="leading-8 text-justify mb-4">
                    आम्ही वरील प्रतिज्ञापत्रातील मजकूर आमच्या माहितीनुसार पूर्णपणे खरा व अचूक आहे, असे सत्यप्रतिज्ञेवर सांगतो/सांगते. जर वरील माहिती खोटी आढळल्यास आम्ही भा.दं.वि. कलम १९९, १९३(२), २०० अन्वये कायदेशीर कारवाईस पात्र राहू, याची आम्हाला पूर्ण जाणीव आहे.
                </p>

                <div class="flex justify-between items-start mt-6 mb-4">
                    <div>
                        <p class="leading-7 mb-0">ठिकाण : <span id="out_place_btm" class="out-field filled">पापळ</span></p>
                        <p class="leading-7 mb-0">दिनांक : <span id="out_date_btm" class="out-field filled">{{ date('d/m/Y') }}</span></p>
                    </div>
                    <div class="text-right">
                        <p class="leading-7 mb-1">प्रतिज्ञार्थी (पती) सही : _______________________</p>
                        <p class="leading-7 mb-0">नाव : <span id="out_h_name_sig2" class="out-field empty">&nbsp;</span></p>
                        <p class="leading-7 mt-3 mb-1">प्रतिज्ञार्थी (पत्नी) सही : _____________________</p>
                        <p class="leading-7 mb-0">नाव : <span id="out_w_name_sig2" class="out-field empty">&nbsp;</span></p>
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
            <h3 class="text-lg font-bold text-gray-800">वापरकर्ता मार्गदर्शक (Guide)</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="text-sm text-gray-700 space-y-3">
            <div>
                <p class="font-bold text-gray-800 mb-1">1. माहिती भरा:</p>
                <p>डाव्या बाजूच्या फॉर्ममध्ये पती–पत्नीची माहिती, विवाह तपशील, नोंद तपशील भरा. उजव्या बाजूस लाईव्ह प्रिव्ह्यू दिसेल.</p>
            </div>
            <div>
                <p class="font-bold text-gray-800 mb-1">2. एडिटिंग:</p>
                <p>प्रिव्ह्यू मधील मजकूर तुम्ही थेट एडिट, Delete, Bold, Underline करू शकता.</p>
            </div>
            <div>
                <p class="font-bold text-gray-800 mb-1">3. लाल रेषा:</p>
                <p>पानाच्या तळाशी लाल रेष आहे. मजकूर त्याखाली गेल्यास Gap Slider कमी करा किंवा "नवीन पान जोडा" बटण वापरा.</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-blue-700 text-xs">
                    PDF डाऊनलोड करण्यासाठी 'Pay & Print' बटण दाबा आणि प्रिंट विंडोमध्ये 'Destination' → 'Save as PDF' निवडा.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openGuide() {
    document.getElementById('guideModal').classList.remove('hidden');
    document.getElementById('guideModal').classList.add('flex');
}
function closeModal() {
    document.getElementById('guideModal').classList.add('hidden');
    document.getElementById('guideModal').classList.remove('flex');
}
function closeGuide(e) {
    if (e.target.id === 'guideModal') closeModal();
}

function setGap1(v){document.getElementById('gap1').style.height=v+'mm';checkOverflow();}
function setFont(v){if(typeof bondSetFontAll==='function')bondSetFontAll(v);checkOverflow();}
function setPad(id,prop,v){var el=document.getElementById(id);if(el)el.style[prop]=v+'px';}
function checkOverflow(){var b=document.getElementById('redLine');if(!b)return;if(b.offsetTop+b.offsetHeight>1123){b.classList.add('text-red-700','font-bold','bg-red-50');b.innerText='सावधान! मजकूर रेषेच्या खाली गेला आहे. Gap कमी करा किंवा मजकूर Cut करा.';}else{b.classList.remove('text-red-700','font-bold','bg-red-50');b.innerText='--- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---';}}

function formatDateDMY(val) {
    if (!val) return '';
    var d = new Date(val);
    return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();
}

var syncMap = {
    'inp_tahsil': ['out_tahsil'],
    'inp_district': ['out_district'],
    'inp_place': ['out_place_top', 'out_place_btm'],
    'inp_date': ['out_date_top', 'out_date_btm'],
    'inp_h_name': ['out_h_name', 'out_h_name_sig1', 'out_h_name_sig2'],
    'inp_h_age': ['out_h_age'],
    'inp_h_dob': ['out_h_dob'],
    'inp_h_religion': ['out_h_religion'],
    'inp_h_caste': ['out_h_caste'],
    'inp_h_addr': ['out_h_addr'],
    'inp_w_name': ['out_w_name', 'out_w_name_sig1', 'out_w_name_sig2'],
    'inp_w_age': ['out_w_age'],
    'inp_w_dob': ['out_w_dob'],
    'inp_w_religion': ['out_w_religion'],
    'inp_w_caste': ['out_w_caste'],
    'inp_w_addr': ['out_w_addr'],
    'inp_marriage_date': ['out_marriage_date'],
    'inp_marriage_time': ['out_marriage_time'],
    'inp_marriage_place': ['out_marriage_place'],
    'inp_marriage_venue_desc': ['out_marriage_venue_desc'],
    'inp_marriage_ritual': ['out_marriage_ritual'],
    'inp_evidence': ['out_evidence'],
    'inp_grampanchayat': ['out_grampanchayat'],
    'inp_nond_taluka': ['out_nond_taluka'],
    'inp_nond_district': ['out_nond_district'],
    'inp_time_elapsed': ['out_time_elapsed'],
};

var dateFields = ['inp_date', 'inp_h_dob', 'inp_w_dob', 'inp_marriage_date'];

function sync() {
    for (var id in syncMap) {
        var el = document.getElementById(id);
        if (!el) continue;
        var val = el.value;
        if (dateFields.indexOf(id) !== -1 && val) {
            val = formatDateDMY(val);
        }
        syncMap[id].forEach(function(outId) {
            var o = document.getElementById(outId);
            if (!o) return;
            o.innerText = val || '\u00A0';
            if (val && val.trim() !== '') { o.classList.remove('empty'); o.classList.add('filled'); }
            else { o.classList.remove('filled'); o.classList.add('empty'); }
        });
    }
    checkOverflow();
}

window.addEventListener('DOMContentLoaded', function() { sync(); lucide.createIcons(); });

var previewArea = document.querySelector('.preview-area');
if (previewArea) {
    previewArea.addEventListener('copy', function(e) { e.preventDefault(); });
    previewArea.addEventListener('paste', function(e) { e.preventDefault(); var text = (e.clipboardData || window.clipboardData).getData('text/plain'); if (text) document.execCommand('insertText', false, text); });
}

function payAndPrint() {
    if (!document.getElementById('inp_h_name').value.trim()) { alert('कृपया पतीचे नाव टाका (Please enter Husband Name)'); return; }
    if (!document.getElementById('inp_w_name').value.trim()) { alert('कृपया पत्नीचे नाव टाका (Please enter Wife Name)'); return; }
    var boundary = document.getElementById('redLine');
    if (boundary && boundary.innerText.includes('सावधान')) { if (!confirm('Warning: मजकूर पानाबाहेर गेला आहे. तरीही प्रिंट करायचे का?')) return; }
    if (!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for Marriage Affidavit?')) return;
    var btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }, body: JSON.stringify({ slug: 'marriage-affidavit' }) })
    .then(function(r) { return r.json(); }).then(function(data) {
        btn.disabled = false; btn.innerHTML = '<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})'; lucide.createIcons();
        if (data.status === 'success') { document.getElementById('walletBal').innerText = data.new_balance; document.querySelectorAll('.bond-watermark').forEach(function(w) { w.style.display = 'none'; }); window.print(); setTimeout(function() { document.querySelectorAll('.bond-watermark').forEach(function(w) { w.style.display = 'flex'; }); }, 2000); }
        else { alert('Transaction Failed: ' + data.message); }
    }).catch(function(err) { btn.disabled = false; btn.innerHTML = '<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})'; lucide.createIcons(); console.error(err); alert('Payment request failed.'); });
}
</script>
@endpush
