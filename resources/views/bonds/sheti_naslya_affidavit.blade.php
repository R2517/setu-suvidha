@extends('layouts.bond-maker')
@section('title', 'शेती नसल्याबाबत अहवाल व प्रतिज्ञापत्र — SETU Suvidha')

@push('styles')
<style>
    .sig-block{display:flex;justify-content:space-between;align-items:flex-start;margin-top:20px;}
    .sig-block div{min-width:160px;}
    .hr-line{border:none;border-top:1.5px solid #333;margin:10px 0;}
    @media print {
        .bond-page { page-break-after: always; }
        .bond-page:last-child { page-break-after: auto; }
    }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">शेती नसल्याबाबत</h2>
                    <p class="text-[10px] text-gray-400">अहवाल व प्रतिज्ञापत्र</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 1: अर्जदार तपशील --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">अर्जदार तपशील</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">पूर्ण नाव (श्री./श्रीमती.)</label><input type="text" id="inp_name" oninput="sync()" placeholder="श्री. स्वप्नील अग्रवाल बोरकर" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">वय (वर्षे)</label><input type="number" id="inp_age" oninput="sync()" placeholder="37" class="form-input"></div>
                        <div><label class="field-label">धंदा / व्यवसाय</label><input type="text" id="inp_occupation" oninput="sync()" placeholder="मजुरी" class="form-input"></div>
                    </div>
                    <div><label class="field-label">लिंग</label>
                        <select id="inp_gender" onchange="sync()" class="form-select">
                            <option value="करणारा">पुरुष</option>
                            <option value="करणारी">स्त्री</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- SEC 2: पत्ता --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">पत्ता</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">मौजा / गाव</label><input type="text" id="inp_mauza" oninput="sync()" placeholder="काजना" class="form-input"></div>
                    <div><label class="field-label">रा. मु.पो. (पूर्ण पत्ता)</label><input type="text" id="inp_address" oninput="sync()" placeholder="काजना-राजना" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">तालुका</label><input type="text" id="inp_taluka" oninput="sync()" value="नांदगाव खंडेश्वर" class="form-input"></div>
                        <div><label class="field-label">जिल्हा</label><input type="text" id="inp_district" oninput="sync()" value="अमरावती" class="form-input"></div>
                    </div>
                    <div><label class="field-label">राज्य</label><input type="text" id="inp_state" oninput="sync()" value="महाराष्ट्र" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 3: अहवाल तपशील (पान १) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">अहवाल तपशील (पान १)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">संदर्भ पत्र क्रमांक</label><input type="text" id="inp_ref" oninput="sync()" placeholder="अका / प्र.सु.-२ / कावी-च्यु / २०२०" class="form-input"></div>
                    <div><label class="field-label">संदर्भ दिनांक</label><input type="date" id="inp_ref_date" oninput="sync()" class="form-input"></div>
                    <div><label class="field-label">साज्या / गावाचे नाव</label><input type="text" id="inp_sajya" oninput="sync()" placeholder="राजना" class="form-input"></div>
                    <div><label class="field-label">दिनांक</label><input type="date" id="inp_date" oninput="sync()" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 4: प्रतिज्ञापत्र तपशील (पान २) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">प्रतिज्ञापत्र तपशील (पान २)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">ठिकाण</label><input type="text" id="inp_place" oninput="sync()" value="नांदगाव खंडेश्वर" class="form-input"></div>
                    <div><label class="field-label">प्रतिज्ञापत्र दिनांक</label><input type="date" id="inp_aff_date" oninput="sync()" class="form-input"></div>
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

        <div class="ctrl-bar">
            <div class="ctrl-group"><label>Font</label><input type="range" min="8" max="16" value="11" oninput="setFont(this.value);this.nextElementSibling.textContent=this.value+'pt'"><span class="val">11pt</span></div>
            <div class="ctrl-divider"></div>
            <div class="ctrl-group"><label>P1 Gap</label><input type="range" min="0" max="200" value="80" oninput="setGap1(this.value);this.nextElementSibling.textContent=this.value"><span class="val">80</span></div>
            <div class="ctrl-group"><label>P1 Pad L</label><input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-group"><label>P1 Pad R</label><input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-divider"></div>
            <div class="ctrl-group"><label>P2 Gap</label><input type="range" min="0" max="200" value="60" oninput="setGap2(this.value);this.nextElementSibling.textContent=this.value"><span class="val">60</span></div>
            <div class="ctrl-group"><label>P2 Pad L</label><input type="range" min="5" max="80" value="40" oninput="setPad('page2_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-group"><label>P2 Pad R</label><input type="range" min="5" max="80" value="40" oninput="setPad('page2_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
        </div>

        {{-- ══════ A4 PAGE 1 — अहवाल ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 11.5pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            {{-- Watermark --}}
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="gap1" class="bond-gap w-full relative overflow-hidden" style="height:80mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 1 GAP (Adjust Slider for Stamp Paper)</span>
            </div>

            <div id="page1_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                <p class="mb-1">प्रति,</p>
                <p class="mb-0 font-semibold">मा. तहसीलदार साहेब</p>
                <p class="mb-0"><span id="out_taluka" class="out-field filled">नांदगाव खंडेश्वर</span> यांच्या सेवेशी,</p>
                <p class="mb-0 mt-4"><strong>विषय :-</strong> शेती नसल्याबाबत अहवाल</p>
                <p class="mb-4"><strong>संदर्भ :-</strong> आपल्याकडील पत्र क्रमांक <span id="out_ref" class="out-field empty">&nbsp;</span> दि. <span id="out_ref_date" class="out-field empty">&nbsp;</span></p>

                <p class="mb-0">महोदय,</p>

                <p class="leading-8 text-justify mb-3 mt-2" style="text-indent:30px;">
                    वरील विषयान्वये मौजा <span id="out_mauza" class="out-field empty">&nbsp;</span> तलाठी यांच्याकडून अहवाल सादर करण्यात येतो की, <span id="out_mauza2" class="out-field empty">&nbsp;</span> साज्यामध्ये <span id="out_sajya" class="out-field empty">&nbsp;</span> हे एकच गाव असून.
                </p>

                <p class="leading-8 text-justify mb-2" style="text-indent:30px;">
                    अर्जदार :- <span id="out_name" class="out-field empty">&nbsp;</span>
                </p>
                <p class="leading-8 text-justify mb-3">
                    रा. <span id="out_address" class="out-field empty">&nbsp;</span> ता. <span id="out_taluka2" class="out-field filled">नांदगाव खंडे.</span> जि. <span id="out_district" class="out-field filled">अमरावती</span> यांच्या नावाने मौजा <span id="out_sajya2" class="out-field empty">&nbsp;</span> शेती नाही.
                </p>

                <p class="leading-8 text-justify mb-3" style="text-indent:30px;">
                    करिता अहवाल माहितीस सविनय सादर.
                </p>

                {{-- Signature --}}
                <div class="sig-block mt-16">
                    <div>
                        <p class="mb-1">दि. <span id="out_date" class="out-field empty">&nbsp;</span></p>
                        <p class="mb-0">स्थळ :- <span id="out_mauza3" class="out-field empty">&nbsp;</span></p>
                    </div>
                    <div class="text-center">
                        <p class="mb-1">(स्वाक्षरी)</p>
                        <p class="mb-0">तलाठी</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════ A4 PAGE 2 — प्रतिज्ञापत्र ══════ --}}
        <div id="page2" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 11.5pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            {{-- Watermark --}}
            <div id="watermark2" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="gap2" class="bond-gap w-full relative overflow-hidden" style="height:60mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 2 GAP (Adjust Slider)</span>
            </div>

            <div id="page2_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                {{-- Title --}}
                <div class="text-center mb-4">
                    <p class="font-bold text-xl mb-0">प्रतिज्ञापत्र</p>
                    <p class="text-sm mb-3">(शेती नसल्याबाबत प्रतिज्ञापत्र)</p>
                </div>

                {{-- Addressed to --}}
                <p class="mb-0 font-semibold">मा. विद्यमान कार्यकारी दंडाधिकारी साहेब,</p>
                <p class="mb-0">तहसील –<span id="out2_taluka" class="out-field filled">नांदगाव खंडेश्वर</span> जि. <span id="out2_district" class="out-field filled">अमरावती</span></p>
                <p class="mb-3">यांच्या समक्ष,</p>

                <p class="leading-8 text-justify mb-3">
                    मी खाली सही <span id="out_gender" class="out-field filled">करणारा</span>/<span id="out_gender_alt" class="out-field" style="font-size:0.85em;color:#999;">करणारी</span>, सत्यप्रतिज्ञेवर खालीलप्रमाणे प्रतिज्ञापत्र देत आहे —
                </p>

                {{-- Details --}}
                <p class="font-semibold mb-2">प्रतिज्ञार्थीचे तपशील :</p>
                <p class="leading-8 mb-1">
                    नाव : <span id="out2_name" class="out-field empty">&nbsp;</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;वय : <span id="out2_age" class="out-field empty">&nbsp;</span> वर्षे
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;धंदा : <span id="out2_occupation" class="out-field empty">&nbsp;</span>
                </p>
                <p class="leading-8 mb-3">
                    रा. मु.पो. <span id="out2_address" class="out-field empty">&nbsp;</span>
                    ता. <span id="out2_taluka2" class="out-field filled">नांदगाव खंडेश्वर</span>
                    &nbsp;&nbsp;जि.<span id="out2_district2" class="out-field filled">अमरावती</span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;राज्य : <span id="out2_state" class="out-field filled">महाराष्ट्र</span>
                </p>

                <hr class="hr-line">

                <p class="font-semibold mb-2 mt-3">विषय : शेती नसल्याबाबत प्रतिज्ञापत्र</p>

                <p class="leading-8 text-justify mb-3">
                    मी वरीलप्रमाणे सही <span id="out_gender2" class="out-field filled">करणारा</span>/<span id="out_gender_alt2" class="out-field" style="font-size:0.85em;color:#999;">करणारी</span>, सत्यप्रतिज्ञेवर नमूद करतो/करते की —
                </p>

                <p class="leading-8 text-justify mb-3">
                    माझ्या नावे, माझ्या वडील/पती/पत्नीच्या नावे किंवा माझ्या अविवाहित/विवाहित मुला-मुलींच्या नावे कोणतीही शेतजमीन (Agricultural Land) नोंदणीकृत नाही. माझ्या नावे ७/१२ उतारा, ८-अ उतारा, गट क्रमांक, सर्व्हे क्रमांक किंवा शेतीशी संबंधित कोणतीही मालकी हक्काची नोंद अस्तित्वात नाही. मी सध्या शेती व्यवसाय करीत नाही व माझा उदरनिर्वाह मजुरी / नोकरी / व्यवसाय / इतर साधनांवर अवलंबून आहे. सदर प्रतिज्ञापत्र हे मला शासकीय योजना, दाखला, लाभ किंवा अर्ज सादर करण्यासाठी आवश्यक असल्याने मी देत आहे. या प्रतिज्ञापत्रामध्ये दिलेली सर्व माहिती माझ्या माहितीनुसार पूर्णतः खरी, अचूक व कोणत्याही दबावाशिवाय दिलेली आहे.
                </p>

                <hr class="hr-line">

                <p class="font-bold mb-2 mt-3">सत्यापन</p>
                <p class="leading-8 text-justify mb-2">
                    मी वरील प्रतिज्ञापत्रातील मजकूर माझ्या माहितीनुसार पूर्णपणे खरा व अचूक आहे.
                </p>
                <p class="leading-8 text-justify mb-3">
                    जर वरील माहिती खोटी किंवा दिशाभूल करणारी आढळल्यास, मी भारतीय दंड संहिता कलम १९९, १९३(२), २०० नुसार कायदेशीर जबाबदार राहीन, याची मला पूर्ण जाणीव आहे.
                </p>

                {{-- Signature Block --}}
                <div class="sig-block mt-10">
                    <div>
                        <p class="mb-1">ठिकाण : <span id="out2_place" class="out-field filled">नांदगाव खंडेश्वर</span></p>
                        <p class="mb-0">दिनांक : <span id="out_aff_date" class="out-field empty">&nbsp;</span></p>
                    </div>
                    <div class="text-right">
                        <p class="mb-0">प्रतिज्ञार्थीची सही / अंगठा</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Guide Modal --}}
<div id="guideModal" class="fixed inset-0 bg-black/60 z-50 items-center justify-center hidden" onclick="closeGuide(event)">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">मार्गदर्शन</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="text-sm text-gray-700 space-y-3">
            <div><p class="font-bold text-gray-800 mb-1">1. तपशील भरा:</p><p>डावीकडे अर्जदाराचे नाव, पत्ता, वय, व्यवसाय इत्यादी भरा. दोन्ही पानांवर लाइव्ह प्रिव्ह्यू दिसेल.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. प्रीसेट:</p><p>तालुका, जिल्हा व राज्य आधीच भरलेले आहेत. गरज असल्यास बदला.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">3. प्रिंट:</p><p>पान १ = अहवाल, पान २ = प्रतिज्ञापत्र — एकत्र प्रिंट होतील.</p></div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3"><p class="text-blue-700 text-xs">PDF साठी: 'Pay & Print' → प्रिंट विंडो → 'Save as PDF' निवडा.</p></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openGuide(){document.getElementById('guideModal').classList.remove('hidden');document.getElementById('guideModal').classList.add('flex');}
function closeModal(){document.getElementById('guideModal').classList.add('hidden');document.getElementById('guideModal').classList.remove('flex');}
function closeGuide(e){if(e.target.id==='guideModal')closeModal();}

function setGap1(v){document.getElementById('gap1').style.height=v+'mm';}
function setGap2(v){document.getElementById('gap2').style.height=v+'mm';}
function setFont(v){if(typeof bondSetFontAll==='function')bondSetFontAll(v); else document.querySelectorAll('.bond-page').forEach(function(p){p.style.fontSize=v+'pt';});}
function setPad(id,prop,v){var el=document.getElementById(id);if(el)el.style[prop]=v+'px';}

var dateFields=['inp_ref_date','inp_date','inp_aff_date'];
function fmtDMY(v){if(!v)return '';var d=new Date(v);return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();}

var syncMap={
    'inp_name':['out_name','out2_name'],
    'inp_age':['out2_age'],
    'inp_occupation':['out2_occupation'],
    'inp_mauza':['out_mauza','out_mauza2','out_mauza3'],
    'inp_address':['out_address','out2_address'],
    'inp_taluka':['out_taluka','out_taluka2','out2_taluka','out2_taluka2'],
    'inp_district':['out_district','out2_district','out2_district2'],
    'inp_state':['out2_state'],
    'inp_ref':['out_ref'],
    'inp_ref_date':['out_ref_date'],
    'inp_sajya':['out_sajya','out_sajya2'],
    'inp_date':['out_date'],
    'inp_place':['out2_place'],
    'inp_aff_date':['out_aff_date'],
};

function sync(){
    for(var id in syncMap){
        var el=document.getElementById(id);if(!el)continue;
        var val=el.tagName==='SELECT'?el.options[el.selectedIndex].value:el.value;
        if(dateFields.indexOf(id)!==-1&&val)val=fmtDMY(val);
        syncMap[id].forEach(function(outId){
            var o=document.getElementById(outId);if(!o)return;
            o.innerText=val||'\u00A0';
            if(val&&val.trim()!==''){o.classList.remove('empty');o.classList.add('filled');}
            else{o.classList.remove('filled');o.classList.add('empty');}
        });
    }
    // Gender sync
    var g=document.getElementById('inp_gender');
    if(g){
        var gv=g.value;
        var altv=gv==='करणारा'?'करणारी':'करणारा';
        ['out_gender','out_gender2'].forEach(function(oid){var o=document.getElementById(oid);if(o){o.innerText=gv;o.classList.remove('empty');o.classList.add('filled');}});
        ['out_gender_alt','out_gender_alt2'].forEach(function(oid){var o=document.getElementById(oid);if(o){o.innerText=altv;}});
    }
}

window.addEventListener('DOMContentLoaded',function(){sync();lucide.createIcons();});

var previewArea=document.querySelector('.preview-area');
if(previewArea){
    previewArea.addEventListener('copy',function(e){e.preventDefault();});
    previewArea.addEventListener('paste',function(e){e.preventDefault();var text=(e.clipboardData||window.clipboardData).getData('text/plain');if(text)document.execCommand('insertText',false,text);});
}

function payAndPrint(){
    if(!document.getElementById('inp_name').value.trim()){alert('कृपया अर्जदाराचे नाव टाका');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for शेती नसल्याबाबत अहवाल व प्रतिज्ञापत्र?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'sheti-naslya-affidavit'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
