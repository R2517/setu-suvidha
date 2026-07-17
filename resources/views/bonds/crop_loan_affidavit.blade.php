@extends('layouts.bond-maker')
@section('title', 'पीक कर्ज प्रतिज्ञापत्र — SETU Suvidha')

@push('styles')
<style>
    .sig-block { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 30px; margin-bottom: 20px; }
    .sig-block div { min-width: 180px; }
    .hr-line { border: none; border-top: 1.5px solid #333; margin: 15px 0; }
    table.farm-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
    table.farm-table th, table.farm-table td { border: 1px solid #000; padding: 6px 12px; text-align: center; }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">Crop Loan Affidavit</h2>
                    <p class="text-[10px] text-gray-400">पीक कर्ज प्रतिज्ञापत्र</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 1: प्रतिज्ञार्थी (Applicant) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">प्रतिज्ञार्थी (अर्जदार)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">पूर्ण नाव (श्री/श्रीमती)</label><input type="text" id="inp_app_name" oninput="sync()" placeholder="उदा. रमेश मारोतराव पाटील" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">वय</label><input type="number" id="inp_app_age" oninput="sync()" placeholder="45" class="form-input"></div>
                        <div><label class="field-label">व्यवसाय</label><input type="text" id="inp_app_occ" oninput="sync()" value="शेती" class="form-input"></div>
                    </div>
                    <div><label class="field-label">रा. (गाव / शहर)</label><input type="text" id="inp_app_addr" oninput="sync()" placeholder="उदा. पिंपरी" class="form-input"></div>
                    <div><label class="field-label">तालुका</label><input type="text" id="inp_app_tal" oninput="sync()" value="नांदगाव खंडे" class="form-input"></div>
                    <div><label class="field-label">तहसील कार्यालय</label><input type="text" id="inp_magistrate" oninput="sync()" value="नांदगाव खंडे" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 2: बँकेचा तपशील --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">बँकेचा तपशील</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">बँकेचे नाव</label><input type="text" id="inp_bank_name" oninput="sync()" value="इंडीयन बँक" class="form-input"></div>
                    <div><label class="field-label">शाखा (Branch)</label><input type="text" id="inp_bank_branch" oninput="sync()" placeholder="उदा. नांदगाव खंडे" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 3: शेतीचे तपशील --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">शेतीचे तपशील</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">मौजा</label><input type="text" id="inp_farm_mauza" oninput="sync()" placeholder="उदा. पिंपरी" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">स.नं / गट नं</label><input type="text" id="inp_farm_survey" oninput="sync()" placeholder="१२/१" class="form-input"></div>
                        <div><label class="field-label">क्षेत्रफळ</label><input type="text" id="inp_farm_area" oninput="sync()" placeholder="१.२० हे." class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">आकार</label><input type="text" id="inp_farm_akar" oninput="sync()" placeholder="२.५०" class="form-input"></div>
                        <div><label class="field-label">हक्काचा प्रकार</label><input type="text" id="inp_farm_hakka" oninput="sync()" value="स्वतःची" class="form-input"></div>
                    </div>
                </div>
            </div>

            {{-- SEC 4: ठिकाण व दिनांक --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">ठिकाण व दिनांक</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">ठिकाण (स्थळ)</label><input type="text" id="inp_place" oninput="sync()" value="नांदगाव खंडे" class="form-input"></div>
                    <div><label class="field-label">दिनांक</label><input type="date" id="inp_date" oninput="sync()" class="form-input"></div>
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
            <div class="ctrl-group"><label>Font</label><input type="range" min="8" max="16" value="13" oninput="setFont(this.value);this.nextElementSibling.textContent=this.value+'pt'"><span class="val">13pt</span></div>
            <div class="ctrl-divider"></div>
            <div class="ctrl-group"><label>P1 Gap</label><input type="range" min="0" max="200" value="80" oninput="setGap1(this.value);this.nextElementSibling.textContent=this.value"><span class="val">80</span></div>
            <div class="ctrl-group"><label>P1 Pad L</label><input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-group"><label>P1 Pad R</label><input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-divider"></div>
            <div class="ctrl-group"><label>P2 Gap</label><input type="range" min="0" max="200" value="60" oninput="setGap2(this.value);this.nextElementSibling.textContent=this.value"><span class="val">60</span></div>
            <div class="ctrl-group"><label>P2 Pad L</label><input type="range" min="5" max="80" value="40" oninput="setPad('page2_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-group"><label>P2 Pad R</label><input type="range" min="5" max="80" value="40" oninput="setPad('page2_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
        </div>

        {{-- ══════ A4 PAGE 1 ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 13pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            {{-- Watermark --}}
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="gap1" class="bond-gap w-full relative overflow-hidden" style="height:80mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 1 GAP (Adjust Slider for Stamp Paper)</span>
            </div>

            {{-- Editable Content Area --}}
            <div id="page1_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                {{-- Title --}}
                <div class="text-center mb-6">
                    <p class="font-bold text-[1.6rem] underline mb-1">प्रतिज्ञापत्र</p>
                </div>

                {{-- Addressed To & Applicant Details --}}
                <div class="mb-4">
                    <p class="font-bold mb-1">प्रति,</p>
                    <p class="font-bold mb-1 ml-8">वि. तहसीलदार साहेब</p>
                    <p class="font-bold mb-1 ml-8">तहसील कार्यालय <span id="out_magistrate" class="out-field filled font-normal">नांदगाव खंडे</span>,</p>
                    <p class="font-bold mb-6 ml-24">यांचे समक्ष</p>

                    <p class="mb-2 font-bold leading-8">
                        प्रतिज्ञार्थी :- श्री. <span id="out_app_name" class="out-field empty border-b border-dashed border-gray-400">&nbsp;</span> 
                        वय. <span id="out_app_age" class="out-field empty" style="font-weight:normal;">&nbsp;</span> वर्ष
                        <br>
                        व्यवसाय. <span id="out_app_occ" class="out-field filled font-normal">शेती</span> 
                        रा. <span id="out_app_addr" class="out-field empty font-normal">&nbsp;</span> 
                        ता. <span id="out_app_tal" class="out-field filled font-normal">नांदगाव खंडे</span>
                    </p>
                </div>
                
                <p class="mb-2 font-bold">महोदय,</p>
                
                {{-- Body Paragraph 1 --}}
                <p class="leading-[2.2] text-justify mb-4" style="text-indent: 40px;">
                    मी सत्य प्रतिज्ञापत्र कथन करतो की, मी 
                    <strong><span id="out_bank_name" class="out-field filled">इंडीयन बँक</span></strong>, 
                    शाखा <strong><span id="out_bank_branch" class="out-field empty border-b border-dashed border-gray-400">&nbsp;</span></strong> 
                    कडे पिक कर्ज मागणीचा अर्ज केला आहे. त्याकरिता खालील शेती मी बँकेकडे तारण देत आहे.
                </p>
            </div>
        </div>

        {{-- ══════ A4 PAGE 2 ══════ --}}
        <div id="page2" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 13pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            {{-- Watermark --}}
            <div id="watermark2" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="gap2" class="bond-gap w-full relative overflow-hidden" style="height:60mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 2 GAP (Adjust Slider)</span>
            </div>

            <div id="page2_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">


                {{-- Farm Table --}}
                <table class="farm-table font-semibold">
                    <thead>
                        <tr class="bg-gray-100">
                            <th style="width:25%;">मौजा</th>
                            <th style="width:20%;">स.न.</th>
                            <th style="width:20%;">क्षेत्रफळ</th>
                            <th style="width:15%;">आकार</th>
                            <th style="width:20%;">हक्काचा प्रकार</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span id="out_farm_mauza" class="out-field empty font-normal">&nbsp;</span></td>
                            <td><span id="out_farm_survey" class="out-field empty font-normal">&nbsp;</span></td>
                            <td><span id="out_farm_area" class="out-field empty font-normal">&nbsp;</span></td>
                            <td><span id="out_farm_akar" class="out-field empty font-normal">&nbsp;</span></td>
                            <td><span id="out_farm_hakka" class="out-field filled font-normal">स्वतःची</span></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Body Paragraph 2 --}}
                <p class="leading-[2.2] text-justify mb-4" style="text-indent: 40px;">
                    वरील शेत जमीन मी यापूर्वी कुठल्याही राष्ट्रीयकृत बँक, पत संस्था, किंवा इतर
                    कुठल्याही संस्थेस तारण दिलेली नाही तसेच हि जमीन कुणालाही जामीन म्हणुन
                    लिहून दिलेली नमूद जमीन पूर्णतः बोजा विरहीत आहे.
                </p>

                {{-- Body Paragraph 3 --}}
                <p class="leading-[2.2] text-justify mb-4" style="text-indent: 40px;">
                    त्याच प्रमाणे माझ्याकडे सुद्धा इतर कुठल्याही राष्ट्रीयकृत बँक, पत संस्था, किंवा
                    इतर कुठल्याही संस्थेचे कर्ज नाही. जर असे काही आढळून आल्यास बँक योग्य
                    ती कार्यवाही करण्यास मोकळीक राहील. तरी सदर बँकेकडून मला कर्ज मिळावे
                    म्हणून मी हे प्रतिज्ञापत्र करीत आहे.
                </p>

                <p class="leading-[2.2] text-center font-bold text-[1.1rem] mb-4">
                    करिता समक्ष प्रतिज्ञापत्र करीत आहे.
                </p>

                {{-- Signature Block 1 --}}
                <div class="sig-block text-[1em] font-bold">
                    <div>
                        <p class="mb-2">ठिकाण :- <span id="out_place" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                        <p class="mb-0">दि. :- <span id="out_date" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                    </div>
                    <div class="text-center">
                        <p class="mb-12 text-transparent select-none">.</p>
                        <p class="mb-0 font-bold text-[1.1rem]">सही</p>
                        <p class="mb-0 border-t border-gray-600 pt-1 inline-block px-8 text-transparent">........................</p>
                    </div>
                </div>

                {{-- Verification --}}
                <p class="font-bold text-center text-[1.4rem] mb-3 mt-4">♦ सत्यापन ♦</p>
                <p class="leading-[2.2] text-justify mb-6" style="text-indent: 40px;">
                    मी प्रतिज्ञालेखाद्वारे लिहून दिलेली माहिती सत्य असून खोटी निघाल्यास मी/आम्ही
                    भा.द. वि.कलम १९३/२,१९९,२०० अन्वये गुन्ह्यास पात्र राहील/राहू.
                </p>

                {{-- Signature Block 2 --}}
                <div class="sig-block text-[1em] font-bold">
                    <div>
                        <p class="mb-0 mt-8">दि. :- <span id="out_date2" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                    </div>
                    <div class="text-center">
                        <p class="mb-10 text-transparent select-none">.</p>
                        <p class="mb-0 font-bold text-[1.1rem]">सही</p>
                        <p class="mb-0 border-t border-gray-600 pt-1 inline-block px-8 text-transparent">........................</p>
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
            <div><p class="font-bold text-gray-800 mb-1">1. तपशील भरा:</p><p>डावीकडे प्रतिज्ञार्थी, बँकेचे व शेतीचे तपशील भरा.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. स्टॅम्प पेपर अलाइनमेंट:</p><p>P1 Gap slider वापरून मजकूर खाली घ्या, जेणेकरून स्टॅम्प पेपरच्या डिझाईनवर मजकूर येणार नाही.</p></div>
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

var dateFields=['inp_date'];
function fmtDMY(v){if(!v)return '';var d=new Date(v);return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();}

var syncMap={
    'inp_app_name':['out_app_name'],
    'inp_app_age':['out_app_age'],
    'inp_app_occ':['out_app_occ'],
    'inp_app_addr':['out_app_addr'],
    'inp_app_tal':['out_app_tal'],
    'inp_magistrate':['out_magistrate'],
    'inp_bank_name':['out_bank_name'],
    'inp_bank_branch':['out_bank_branch'],
    'inp_farm_mauza':['out_farm_mauza'],
    'inp_farm_survey':['out_farm_survey'],
    'inp_farm_area':['out_farm_area'],
    'inp_farm_akar':['out_farm_akar'],
    'inp_farm_hakka':['out_farm_hakka'],
    'inp_place':['out_place'],
    'inp_date':['out_date','out_date2'],
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
}

window.addEventListener('DOMContentLoaded',function(){sync();lucide.createIcons();});

var previewArea=document.querySelector('.preview-area');
if(previewArea){
    previewArea.addEventListener('copy',function(e){e.preventDefault();});
    previewArea.addEventListener('paste',function(e){e.preventDefault();var text=(e.clipboardData||window.clipboardData).getData('text/plain');if(text)document.execCommand('insertText',false,text);});
}

function payAndPrint(){
    if(!document.getElementById('inp_app_name').value.trim()){alert('कृपया प्रतिज्ञार्थीचे नाव टाका');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for पीक कर्ज प्रतिज्ञापत्र?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'crop-loan-affidavit'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
