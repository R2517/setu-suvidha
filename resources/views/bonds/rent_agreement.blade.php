@extends('layouts.bond-maker')
@section('title', 'Rent Agreement Maker')

@push('styles')
<style>
    #watermark1, #watermark2 { user-select: none; }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">भाडे करारनामा</h2>
                    <p class="text-[10px] text-gray-400">Rent Agreement</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SECTION 1 --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">1</span>
                    <span class="title">दिनांक & ठिकाण</span>
                </div>
                <div class="section-body grid grid-cols-2 gap-2.5">
                    <div>
                        <label class="field-label">दिनांक</label>
                        <input type="date" id="inp_date" value="{{ date('Y-m-d') }}" oninput="sync()" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">ठिकाण</label>
                        <input type="text" id="inp_place" value="जालना" oninput="sync()" placeholder="ठिकाण" class="form-input">
                    </div>
                </div>
            </div>

            {{-- SECTION 2 --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">2</span>
                    <span class="title">मालक (Owner)</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">नाव</label>
                        <input type="text" id="inp_owner" oninput="sync()" placeholder="मालकाचे पूर्ण नाव" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">वय</label>
                            <input type="number" id="inp_owner_age" oninput="sync()" placeholder="वय" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">व्यवसाय</label>
                            <input type="text" id="inp_owner_occu" value="व्यापार" oninput="sync()" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">पत्ता</label>
                        <textarea id="inp_owner_addr" oninput="sync()" rows="2" class="form-textarea"></textarea>
                    </div>
                </div>
            </div>

            {{-- SECTION 3 --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">3</span>
                    <span class="title">भाडेकरू (Tenant)</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">नाव</label>
                        <input type="text" id="inp_tenant" oninput="sync()" placeholder="भाडेकरूचे पूर्ण नाव" class="form-input">
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">वय</label>
                            <input type="number" id="inp_tenant_age" oninput="sync()" placeholder="वय" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">व्यवसाय</label>
                            <input type="text" id="inp_tenant_occu" value="नोकरी" oninput="sync()" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">पत्ता</label>
                        <textarea id="inp_tenant_addr" oninput="sync()" rows="2" class="form-textarea"></textarea>
                    </div>
                </div>
            </div>

            {{-- SECTION 4 --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">4</span>
                    <span class="title">मिळकत (Property)</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">सर्व्हे नं.</label>
                            <input type="text" id="inp_survey_no" value="123/A" oninput="sync()" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">फ्लॅट/घर क्र.</label>
                            <input type="text" id="inp_flat_no" value="B-101" oninput="sync()" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">बिल्डिंग नाव</label>
                        <input type="text" id="inp_bldg_name" value="साई हाईट्स" oninput="sync()" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">परिसर</label>
                        <input type="text" id="inp_area" value="समर्थ नगर" oninput="sync()" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">वर्णन</label>
                        <textarea id="inp_desc" oninput="sync()" rows="2" class="form-textarea">2 BHK (हॉल, किचन, 2 बेडरूम)</textarea>
                    </div>
                </div>
            </div>

            {{-- SECTION 5 --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">5</span>
                    <span class="title">भाडे (Rent Details)</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">सुरुवात दिनांक</label>
                            <input type="text" id="inp_start_date" oninput="sync()" placeholder="DD/MM/YYYY" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">शेवट दिनांक</label>
                            <input type="text" id="inp_end_date" oninput="sync()" placeholder="DD/MM/YYYY" class="form-input">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="field-label">महिने</label>
                            <input type="number" id="inp_months" value="11" oninput="sync()" class="form-input">
                        </div>
                        <div>
                            <label class="field-label">भाडे रक्कम</label>
                            <input type="number" id="inp_rent" value="5000" oninput="sync()" class="form-input">
                        </div>
                    </div>
                    <div>
                        <label class="field-label">अक्षरी</label>
                        <input type="text" id="inp_rent_w" value="पाच हजार" oninput="sync()" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">अनामत (Deposit)</label>
                        <input type="number" id="inp_dep" value="20000" oninput="sync()" class="form-input">
                    </div>
                </div>
            </div>

            {{-- SECTION 6 --}}
            <div class="section-card">
                <div class="section-header">
                    <span class="num">6</span>
                    <span class="title">साक्षीदार (Witness)</span>
                </div>
                <div class="section-body space-y-2.5">
                    <div>
                        <label class="field-label">साक्षीदार 1</label>
                        <input type="text" id="inp_wit1" oninput="sync()" class="form-input">
                    </div>
                    <div>
                        <label class="field-label">साक्षीदार 2</label>
                        <input type="text" id="inp_wit2" oninput="sync()" class="form-input">
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
            <div class="ctrl-divider"></div>
            <div class="ctrl-group">
                <label>P2 Gap</label>
                <input type="range" min="0" max="200" value="60" oninput="setGap2(this.value);this.nextElementSibling.textContent=this.value">
                <span class="val">60</span>
            </div>
            <div class="ctrl-group">
                <label>P2 Pad L</label>
                <input type="range" min="5" max="80" value="40" oninput="setPad('page2_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value">
                <span class="val">40</span>
            </div>
            <div class="ctrl-group">
                <label>P2 Pad R</label>
                <input type="range" min="5" max="80" value="40" oninput="setPad('page2_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value">
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
                <h2 class="text-center font-bold text-xl underline mb-4">भाडे करारनामा</h2>

                <div class="border border-gray-800 rounded px-4 py-3 mb-3">
                    <p class="mb-1"><span class="underline font-semibold">लिहून देणार (मालक) :-</span></p>
                    <p class="ml-6 mb-0 leading-8">
                        <span id="out_owner" class="out-field empty">&nbsp;</span>,
                        वय <span id="out_owner_age" class="out-field empty">&nbsp;</span>
                        वर्षे, धंदा :
                        <span id="out_owner_occu" class="out-field filled">व्यापार</span>
                    </p>
                    <p class="ml-6 mb-0 leading-8">
                        रा. <span id="out_owner_addr" class="out-field empty">&nbsp;</span>
                    </p>
                </div>

                <p class="text-center font-semibold mb-2">।। यांचे हक्कात ।।</p>

                <div class="border border-gray-800 rounded px-4 py-3 mb-3">
                    <p class="mb-1"><span class="underline font-semibold">लिहून घेणार (भाडेकरू) :-</span></p>
                    <p class="ml-6 mb-0 leading-8">
                        <span id="out_tenant" class="out-field empty">&nbsp;</span>,
                        वय <span id="out_tenant_age" class="out-field empty">&nbsp;</span>
                        वर्षे, धंदा :
                        <span id="out_tenant_occu" class="out-field filled">नोकरी</span>
                    </p>
                    <p class="ml-6 mb-0 leading-8">
                        रा. <span id="out_tenant_addr" class="out-field empty">&nbsp;</span>
                    </p>
                </div>

                <p class="leading-8 text-justify mb-3">
                    कारणे सदरील भाडे करारनामा (Leave and License Agreement)
                    लिहून देतो ऐसा जे की,
                    <span id="out_place_top" class="out-field filled">जालना</span>
                    येथील, न.भु.मा.क्र. / सर्व्हे नं.
                    <span id="out_survey_no" class="out-field filled">123/A</span>,
                    फ्लॅट/घर क्र.
                    <span id="out_flat_no" class="out-field filled">B-101</span>,
                    <span id="out_bldg_name" class="out-field filled">साई हाईट्स</span>,
                    <span id="out_area" class="out-field filled">समर्थ नगर</span>.
                    ज्यामध्ये
                    <span id="out_desc" class="out-field filled">2 BHK (हॉल, किचन, 2 बेडरूम)</span>
                    (लाईट व पाण्यासह) ही मिळकत लिहून देणार (मालक) यांच्या
                    स्वकष्टार्जित मालकीची व कबजेवहिवाटीची आहे.
                </p>

                <p class="leading-8 text-justify mb-4">
                    सदरची जागा माझी मला स्वतःला वापरणे नसल्याने, ती जागा मी
                    तुम्हांस (भाडेकरूस) आज दिनांक
                    <span id="out_start_date" class="out-field empty">&nbsp;</span>
                    पासुन ते दिनांक
                    <span id="out_end_date" class="out-field empty">&nbsp;</span>
                    पावेतो अशा एकूण
                    <span id="out_months" class="out-field filled">11</span>
                    महिन्यांच्या मुदतीकरीता खालील अटी व शर्तींच्या आधारे
                    भाडेतत्वावर वापरास देत आहे.
                </p>

                <div id="redLine" class="w-full border-t-2 border-dashed border-red-400 text-center text-red-400 text-xs py-1 my-2 no-print select-none">
                    --- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---
                </div>
            </div>
        </div>

        {{-- ══════ A4 PAGE 2 ══════ --}}
        <div id="page2" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mt-0"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Noto Sans Devanagari', 'Mukta', sans-serif;">

            <div id="watermark2" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="gap2" class="bond-gap w-full relative overflow-hidden"
                 style="height: 60mm; background: repeating-linear-gradient(45deg, #e8e8e8, #e8e8e8 2px, #f5f5f5 2px, #f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">
                    PAGE 2 GAP (Adjust Slider)
                </span>
            </div>

            <p class="text-center text-gray-300 text-xs italic py-2 no-print">
                --- (मागील पानाचा मजकूर येथे पेस्ट करू शकता) ---
            </p>

            <div id="page2_content" class="bond-content py-2" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">
                <p class="font-bold underline mb-2">● अटी व शर्ती :-</p>

                <p class="leading-8 text-justify mb-2">
                    सदर फ्लॅटचे मासिक भाडे दरमहा रुपये
                    <span id="out_rent" class="out-field filled">5000</span>/-
                    (अक्षरी रुपये
                    <span id="out_rent_w" class="out-field filled">पाच हजार</span>
                    फक्त) ठरले असून ते लिहून घेणार यांनी दर महिन्याच्या
                    01 ते 10 तारखेपर्यंत लिहून देणार यांना रोखीने अथवा
                    ऑनलाईन अदा करायचे आहे.
                </p>

                <p class="leading-8 text-justify mb-2">
                    सदर जागेची अनामत रक्कम (Security Deposit) रुपये
                    <span id="out_dep" class="out-field filled">20000</span>/-
                    लिहून घेणार यांनी मालकाकडे जमा केली आहे. करार संपल्यावर
                    लाइट बिल, पाणीपट्टी किंवा फ्लॅटचे काही नुकसान (Damages)
                    झाले असल्यास त्याची रक्कम वजा करून उरलेली रक्कम
                    बिनव्याजी परत मिळेल.
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">घरगुती वापर:</span>
                    सदरचा फ्लॅट हा केवळ राहण्यासाठी (Residential Purpose)
                    दिला असून, तिथे कोणताही व्यापारी व्यवसाय, गोडाऊन
                    किंवा उपद्रवी काम करता येणार नाही.
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">नोटीस:</span>
                    सदर फ्लॅट कराराची मुदत संपण्यापूर्वी खाली करुन घ्यायचा
                    अथवा द्यायचा असल्यास दोन्ही बाजूंनी एकमेकांस एक महिना
                    अगोदर लेखी/तोंडी नोटीस देणे बंधनकारक राहील.
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">पोलीस व्हेरिफिकेशन:</span>
                    स्थानिक पोलीस स्टेशनमध्ये भाडेकरूने स्वतःचे पोलीस
                    व्हेरिफिकेशन (Police Verification) करून त्याची प्रत
                    मालकाकडे जमा करणे बंधनकारक राहील. कोणत्याही बेकायदेशीर
                    कृत्यास भाडेकरू स्वतः जबाबदार राहील.
                </p>

                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">न्यायकक्षा (Jurisdiction):</span>
                    सदर कराराबाबत भविष्यात काही वाद उद्भवल्यास त्याची सर्व
                    न्यायकक्षा
                    <span id="out_place_juris" class="out-field filled">जालना</span>
                    येथील न्यायालयाच्या अधीन राहील.
                </p>

                <p class="leading-8 text-justify mb-4">
                    <span class="font-semibold">ताबा दंड (Penalty):</span>
                    कराराची मुदत संपल्यानंतर किंवा नोटीस दिल्यानंतरही जर
                    भाडेकरूने जागा रिकामी केली नाही, तर त्या दिवसापासून
                    भाडेकरूस प्रतिदिन दुप्पट भाडे दंड म्हणून द्यावे लागेल.
                </p>

                <p class="leading-8 text-justify mb-6">
                    करीता हा भाडे करारनामा आम्ही उभयतांनी, आमचे राजीमर्जीने,
                    अक्कलहुशारीने व कोणत्याही नशापाणी न करता,
                    साक्षीदारांसमक्ष वाचून समजून उमजून लिहून दिला व घेतला असे.
                </p>

                <div class="flex justify-between mb-8 text-sm">
                    <p>दिनांक: <span id="out_date_btm" class="out-field filled">{{ date('d/m/Y') }}</span></p>
                    <p>ठिकाण: <span id="out_place_btm" class="out-field filled">जालना</span></p>
                </div>

                <p class="font-semibold mb-3">साक्षीदार :-</p>
                <div class="flex justify-between mb-10 text-sm">
                    <p>1) <span id="out_wit1" class="out-field empty">&nbsp;</span></p>
                    <p>2) <span id="out_wit2" class="out-field empty">&nbsp;</span></p>
                </div>

                <div class="flex justify-between text-sm mt-8">
                    <div class="text-center">
                        <div class="border-t border-gray-700 w-40 mb-1 pt-1">
                            <span id="out_owner_sig" class="out-field empty"></span>
                        </div>
                        <p class="text-xs text-gray-600">लिहून देणार (मालक)</p>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-700 w-40 mb-1 pt-1">
                            <span id="out_tenant_sig" class="out-field empty"></span>
                        </div>
                        <p class="text-xs text-gray-600">लिहून घेणार (भाडेकरू)</p>
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
                <p class="font-bold text-gray-800 mb-1">1. सुरक्षितता व एडिटिंग नियम:</p>
                <p>Copy: पेजमधील मजकूर बाहेर कॉपी करता येत नाही.</p>
                <p>Cut/Paste: मजकूर त्याच पेजवर Internal Move करता येतो.</p>
                <p>Edit/Type: नको असलेला मजकूर Delete करा किंवा नवीन Type करा.</p>
            </div>
            <div>
                <p class="font-bold text-gray-800 mb-1">2. लाल रेषेचे महत्त्व:</p>
                <p>पहिल्या पानाच्या तळाशी लाल रेष आहे. मजकूर त्याखाली गेल्यास Gap Slider कमी करा किंवा मजकूर Cut करून पुढच्या पानावर Paste करा.</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-blue-700 text-xs">
                    PDF डाऊनलोड करण्यासाठी 'Pay & Download' बटण दाबा आणि प्रिंट विंडोमध्ये 'Destination' → 'Save as PDF' निवडा.
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
function setGap2(v){document.getElementById('gap2').style.height=v+'mm';}
function setFont(v){if(typeof bondSetFontAll==='function')bondSetFontAll(v);checkOverflow();}
function setPad(id,prop,v){var el=document.getElementById(id);if(el)el.style[prop]=v+'px';}
function checkOverflow(){var b=document.getElementById('redLine');if(!b)return;if(b.offsetTop+b.offsetHeight>1123){b.classList.add('text-red-700','font-bold','bg-red-50');b.innerText='सावधान! मजकूर रेषेच्या खाली गेला आहे. Gap कमी करा किंवा मजकूर Cut करा.';}else{b.classList.remove('text-red-700','font-bold','bg-red-50');b.innerText='--- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---';}}

var syncMap={
    'inp_date':['out_date_btm'],
    'inp_place':['out_place_top','out_place_btm','out_place_juris'],
    'inp_owner':['out_owner','out_owner_sig'],
    'inp_owner_age':['out_owner_age'],
    'inp_owner_occu':['out_owner_occu'],
    'inp_owner_addr':['out_owner_addr'],
    'inp_tenant':['out_tenant','out_tenant_sig'],
    'inp_tenant_age':['out_tenant_age'],
    'inp_tenant_occu':['out_tenant_occu'],
    'inp_tenant_addr':['out_tenant_addr'],
    'inp_survey_no':['out_survey_no'],
    'inp_flat_no':['out_flat_no'],
    'inp_bldg_name':['out_bldg_name'],
    'inp_area':['out_area'],
    'inp_desc':['out_desc'],
    'inp_start_date':['out_start_date'],
    'inp_end_date':['out_end_date'],
    'inp_months':['out_months'],
    'inp_rent':['out_rent'],
    'inp_rent_w':['out_rent_w'],
    'inp_dep':['out_dep'],
    'inp_wit1':['out_wit1'],
    'inp_wit2':['out_wit2'],
};

function sync(){
    for(var id in syncMap){
        var el=document.getElementById(id);if(!el)continue;
        var val=el.value;
        if(id==='inp_date'&&val){var d=new Date(val);val=('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();}
        syncMap[id].forEach(function(outId){
            var o=document.getElementById(outId);if(!o)return;
            o.innerText=val||'\u00A0';
            if(val&&val.trim()!==''){o.classList.remove('empty');o.classList.add('filled');}
            else{o.classList.remove('filled');o.classList.add('empty');}
        });
    }
    checkOverflow();
}

window.addEventListener('DOMContentLoaded',function(){sync();lucide.createIcons();});

var previewArea=document.querySelector('.preview-area');
if(previewArea){
    previewArea.addEventListener('copy',function(e){e.preventDefault();});
    previewArea.addEventListener('paste',function(e){e.preventDefault();var text=(e.clipboardData||window.clipboardData).getData('text/plain');if(text)document.execCommand('insertText',false,text);});
}

function payAndPrint(){
    if(!document.getElementById('inp_owner').value.trim()){alert('कृपया मालकाचे नाव टाका (Please enter Owner Name)');return;}
    var boundary=document.getElementById('redLine');
    if(boundary&&boundary.innerText.includes('सावधान')){if(!confirm('Warning: मजकूर पानाबाहेर गेला आहे. तरीही प्रिंट करायचे का?'))return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for Rent Agreement?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'rent-agreement'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
