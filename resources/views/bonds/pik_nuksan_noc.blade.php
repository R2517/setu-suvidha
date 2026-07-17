@extends('layouts.bond-maker')
@section('title', 'पीक नुकसानी नाहरकत प्रमाणपत्र — SETU Suvidha')

@push('styles')
<style>
    .sig-block { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 40px; }
    .sig-block div { min-width: 180px; }
    .hr-line { border: none; border-top: 1.5px solid #333; margin: 10px 0; }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">Forest NOC</h2>
                    <p class="text-[10px] text-gray-400">Crop Damage NOC</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 1: लिहून देणार (NOC Giver) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">नाहरकत देणारा (वारस)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">पूर्ण नाव (श्री./श्रीमती.)</label><input type="text" id="inp_giver_name" oninput="sync()" placeholder="श्री.प्रमोद अशोक ठाकरे" class="form-input"></div>
                    <div><label class="field-label">रा. (गाव)</label><input type="text" id="inp_giver_village" oninput="sync()" placeholder="पिंपरी निपाणी" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">तालुका</label><input type="text" id="inp_giver_taluka" oninput="sync()" value="नांदगाव खंडेश्वर" class="form-input"></div>
                        <div><label class="field-label">जिल्हा</label><input type="text" id="inp_giver_district" oninput="sync()" value="अमरावती" class="form-input"></div>
                    </div>
                    <div><label class="field-label">भ्रमणध्वनी क्र. (Mobile)</label><input type="text" id="inp_giver_mobile" oninput="sync()" placeholder="७३५०७४९५५८" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 2: अर्जदार (Applicant) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">अर्जदार (NOC घेणारा)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">पूर्ण नाव (श्री./श्रीमती.)</label><input type="text" id="inp_app_name" oninput="sync()" placeholder="श्री.प्रमोद अशोक ठाकरे" class="form-input"></div>
                    <div><label class="field-label">रा. (गाव)</label><input type="text" id="inp_app_village" oninput="sync()" placeholder="पिंपरी निपाणी" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">तालुका</label><input type="text" id="inp_app_taluka" oninput="sync()" value="नांदगाव खंडेश्वर" class="form-input"></div>
                        <div><label class="field-label">जिल्हा</label><input type="text" id="inp_app_district" oninput="sync()" value="अमरावती" class="form-input"></div>
                    </div>
                    <div><label class="field-label">नाते (Relation)</label>
                        <select id="inp_relation" onchange="sync()" class="form-select">
                            <option value="पती">पती</option>
                            <option value="पत्नी">पत्नी</option>
                            <option value="भाऊ">भाऊ</option>
                            <option value="बहीण">बहीण</option>
                            <option value="काका">काका</option>
                            <option value="मुलगा">मुलगा</option>
                            <option value="मुलगी">मुलगी</option>
                            <option value="आई">आई</option>
                            <option value="वडील">वडील</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- SEC 3: शेतीचे तपशील --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">शेतीचे तपशील</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">मौजा</label><input type="text" id="inp_farm_mauza" oninput="sync()" placeholder="खेड पिंपरी" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">सर्वे नं / गट नं</label><input type="text" id="inp_farm_survey" oninput="sync()" placeholder="४२/२/ब" class="form-input"></div>
                        <div><label class="field-label">खाते नं</label><input type="text" id="inp_farm_khate" oninput="sync()" placeholder="२२३३" class="form-input"></div>
                    </div>
                    <div><label class="field-label">नुकसान झालेले पीक</label><input type="text" id="inp_farm_crop" oninput="sync()" placeholder="कापूस / सोयाबीन" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 4: ठिकाण व दिनांक --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">ठिकाण व दिनांक</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">ठिकाण (स्थळ)</label><input type="text" id="inp_place" oninput="sync()" placeholder="पिंपरी निपाणी" class="form-input"></div>
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
            <div class="ctrl-group"><label>Gap</label><input type="range" min="0" max="200" value="80" oninput="setGap1(this.value);this.nextElementSibling.textContent=this.value"><span class="val">80</span></div>
            <div class="ctrl-group"><label>Pad L</label><input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingLeft',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
            <div class="ctrl-group"><label>Pad R</label><input type="range" min="5" max="80" value="40" oninput="setPad('page1_content','paddingRight',this.value);this.nextElementSibling.textContent=this.value"><span class="val">40</span></div>
        </div>

        {{-- ══════ A4 PAGE 1 ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 13.5pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

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
                <div class="text-center mb-10">
                    <p class="font-bold text-[1.1rem] leading-relaxed mb-2">
                        पीक नुकसानी लाभ घेण्यासाठी अर्जदारकडील ७/१२ वर असलेल्या इतर वारसांनी द्यावयाचे
                    </p>
                    <p class="font-bold text-[1.4rem] underline mb-4">नाहरकत प्रमाणपत्र</p>
                </div>

                {{-- Paragraph 1 --}}
                <p class="leading-[2.2] text-justify mb-5" style="text-indent: 40px;">
                    मी <span id="out_giver_name" class="out-field empty">&nbsp;</span>
                    रा. <span id="out_giver_village" class="out-field empty">&nbsp;</span>
                    ता. <span id="out_giver_taluka" class="out-field filled">नांदगाव खंडेश्वर</span>
                    जि. <span id="out_giver_district" class="out-field filled">अमरावती</span> याव्दारे लिहून देतो/देते की,
                    अर्जदार <span id="out_app_name" class="out-field empty">&nbsp;</span>
                    रा. <span id="out_app_village" class="out-field empty">&nbsp;</span>
                    ता. <span id="out_app_taluka" class="out-field filled">नांदगाव खंडेश्वर</span>
                    जि. <span id="out_app_district" class="out-field filled">अमरावती</span> हे माझी
                    <span id="out_relation" class="out-field filled">पती</span> असून आमच्या शेतातील पिकांचे जंगली प्राण्यांनी नुकसान केलेले आहे.
                </p>

                {{-- Paragraph 2 --}}
                <p class="leading-[2.2] text-justify mb-5" style="text-indent: 40px;">
                    सदर नुकसानीसाठी ७/१२ वर नमूद असलेले मौजा :- <span id="out_farm_mauza" class="out-field empty">&nbsp;</span>
                    सर्वे नं:- <span id="out_farm_survey" class="out-field empty">&nbsp;</span>
                    खाते नं:- <span id="out_farm_khate" class="out-field empty">&nbsp;</span>
                    पीक: <span id="out_farm_crop" class="out-field empty">&nbsp;</span>
                    मधील <span id="out_app_name2" class="out-field empty">&nbsp;</span>
                    रा. <span id="out_app_village2" class="out-field empty">&nbsp;</span>
                    ता. <span id="out_app_taluka2" class="out-field filled">नांदगाव खंडेश्वर</span>
                    जि. <span id="out_app_district2" class="out-field filled">अमरावती</span>
                    यांनी वन विभागास अर्ज सादर केला असून, सदर पीक नुकसानीची रक्कम त्यांना (अर्जकर्त्यांस) अदा करण्यास आमची कोणतीही हरकत नाही. करिता नाहरकत प्रमाणपत्र देण्यात येत आहे.
                </p>

                {{-- Signature Block --}}
                <div class="sig-block mt-24 text-[0.95em]">
                    <div>
                        <p class="mb-4">स्थळ:- <span id="out_place" class="out-field empty">&nbsp;</span></p>
                        <p class="mb-0">दिनांक :- <span id="out_date" class="out-field empty">&nbsp;</span></p>
                    </div>
                    <div class="text-right">
                        <p class="mb-2 text-center" style="margin-left:auto; width:max-content;">सही/-</p>
                        <table style="width: auto; margin-left: auto; text-align: left;">
                            <tr>
                                <td style="padding-bottom: 8px; white-space: nowrap;">नाव व गाव :- </td>
                                <td style="padding-bottom: 8px;"><span id="out_giver_name2" class="out-field empty">&nbsp;</span></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="padding-bottom: 8px;">
                                    रा. <span id="out_giver_village2" class="out-field empty">&nbsp;</span>
                                    ता. <span id="out_giver_taluka2" class="out-field filled">नांदगाव खंडेश्वर</span><br>
                                    जि. <span id="out_giver_district2" class="out-field filled">अमरावती</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap;">भ्रमणध्वनी क्र.:- </td>
                                <td><span id="out_giver_mobile" class="out-field empty">&nbsp;</span></td>
                            </tr>
                        </table>
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
            <div><p class="font-bold text-gray-800 mb-1">1. तपशील भरा:</p><p>डावीकडे नाहरकत देणारा (वारस), अर्जदार व शेतीचे तपशील भरा.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. प्रीसेट:</p><p>तालुका व जिल्हा आधीच भरलेले आहेत. गरज असल्यास बदला.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">3. अलाइनमेंट:</p><p>हे प्रमाणपत्र A4 पानावर बॉर्डरसह व्यवस्थित अलाइन केले आहे, त्यामुळे Gap sliders ची गरज नाही.</p></div>
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
function setFont(v){if(typeof bondSetFontAll==='function')bondSetFontAll(v); else document.querySelectorAll('.bond-page').forEach(function(p){p.style.fontSize=v+'pt';});}
function setPad(id,prop,v){var el=document.getElementById(id);if(el)el.style[prop]=v+'px';}

var dateFields=['inp_date'];
function fmtDMY(v){if(!v)return '';var d=new Date(v);return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();}

var syncMap={
    'inp_giver_name':['out_giver_name','out_giver_name2'],
    'inp_giver_village':['out_giver_village','out_giver_village2'],
    'inp_giver_taluka':['out_giver_taluka','out_giver_taluka2'],
    'inp_giver_district':['out_giver_district','out_giver_district2'],
    'inp_giver_mobile':['out_giver_mobile'],
    'inp_app_name':['out_app_name','out_app_name2'],
    'inp_app_village':['out_app_village','out_app_village2'],
    'inp_app_taluka':['out_app_taluka','out_app_taluka2'],
    'inp_app_district':['out_app_district','out_app_district2'],
    'inp_relation':['out_relation'],
    'inp_farm_mauza':['out_farm_mauza'],
    'inp_farm_survey':['out_farm_survey'],
    'inp_farm_khate':['out_farm_khate'],
    'inp_farm_crop':['out_farm_crop'],
    'inp_place':['out_place'],
    'inp_date':['out_date'],
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
    if(!document.getElementById('inp_giver_name').value.trim()){alert('कृपया नाहरकत देणाऱ्याचे नाव टाका');return;}
    if(!document.getElementById('inp_app_name').value.trim()){alert('कृपया अर्जदाराचे नाव टाका');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for पीक नुकसानी नाहरकत प्रमाणपत्र?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'pik-nuksan-noc'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
