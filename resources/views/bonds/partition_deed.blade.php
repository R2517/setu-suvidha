@extends('layouts.bond-maker')
@section('title', 'Partition Deed — कौटुंबिक वाटणीपत्र')

@push('styles')
<style>
    #watermark1, #watermark2 { user-select: none; }
</style>
@endpush

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-700 font-sans" id="root">

    {{-- ═══════════════════ LEFT PANEL ═══════════════════ --}}
    <div class="w-[360px] min-w-[360px] bg-[#f8fafc] overflow-y-auto flex flex-col border-r border-gray-200/60 z-10" id="leftPanel">

        <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100 sticky top-0 z-20">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('bonds.index') }}" class="w-8 h-8 bg-gray-50 hover:bg-gray-100 rounded-lg flex items-center justify-center transition">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-gray-500"></i>
                </a>
                <div>
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">वाटणीपत्र</h2>
                    <p class="text-[10px] text-gray-400">Partition Deed</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        <div class="px-3 pb-4 space-y-2.5 mt-2.5">
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">दिनांक & ठिकाण</span></div>
                <div class="section-body grid grid-cols-2 gap-2.5">
                    <div><label class="field-label">दिनांक</label><input type="date" id="inp_date" value="{{ date('Y-m-d') }}" oninput="sync()" class="form-input"></div>
                    <div><label class="field-label">ठिकाण</label><input type="text" id="inp_place" value="जालना" oninput="sync()" class="form-input"></div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">मूळ मालक (Original Owner)</span></div>
                <div class="section-body space-y-2.5">
                    <div><label class="field-label">नाव</label><input type="text" id="inp_original_owner" oninput="sync()" placeholder="मूळ मालकाचे नाव" class="form-input"></div>
                    <div><label class="field-label">पत्ता</label><textarea id="inp_original_addr" oninput="sync()" rows="2" placeholder="मूळ मालकाचा पत्ता" class="form-textarea"></textarea></div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">पक्षकार 1 (Party 1)</span></div>
                <div class="section-body space-y-2.5">
                    <div><label class="field-label">नाव</label><input type="text" id="inp_party1" oninput="sync()" placeholder="पूर्ण नाव" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div><label class="field-label">वय</label><input type="number" id="inp_party1_age" oninput="sync()" placeholder="वय" class="form-input"></div>
                        <div><label class="field-label">नाते</label><input type="text" id="inp_party1_rel" value="मुलगा" oninput="sync()" class="form-input"></div>
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">पक्षकार 2 (Party 2)</span></div>
                <div class="section-body space-y-2.5">
                    <div><label class="field-label">नाव</label><input type="text" id="inp_party2" oninput="sync()" placeholder="पूर्ण नाव" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2.5">
                        <div><label class="field-label">वय</label><input type="number" id="inp_party2_age" oninput="sync()" placeholder="वय" class="form-input"></div>
                        <div><label class="field-label">नाते</label><input type="text" id="inp_party2_rel" value="मुलगा" oninput="sync()" class="form-input"></div>
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header"><span class="num">5</span><span class="title">मिळकत तपशील (Property)</span></div>
                <div class="section-body space-y-2.5">
                    <div class="grid grid-cols-2 gap-2.5">
                        <div><label class="field-label">सर्व्हे नं.</label><input type="text" id="inp_survey" oninput="sync()" placeholder="गट/सर्व्हे नं." class="form-input"></div>
                        <div><label class="field-label">एकूण क्षेत्रफळ</label><input type="text" id="inp_total_area" oninput="sync()" placeholder="क्षेत्रफळ" class="form-input"></div>
                    </div>
                    <div><label class="field-label">गाव</label><input type="text" id="inp_village" oninput="sync()" placeholder="मौजे / गाव" class="form-input"></div>
                    <div><label class="field-label">वर्णन</label><textarea id="inp_prop_desc" oninput="sync()" rows="2" placeholder="मिळकतीचे वर्णन" class="form-textarea"></textarea></div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header"><span class="num">6</span><span class="title">वाटणी तपशील (Partition)</span></div>
                <div class="section-body space-y-2.5">
                    <div><label class="field-label">पक्षकार 1 हिस्सा</label><textarea id="inp_share1" oninput="sync()" rows="2" placeholder="पक्षकार 1 चा हिस्सा" class="form-textarea"></textarea></div>
                    <div><label class="field-label">पक्षकार 2 हिस्सा</label><textarea id="inp_share2" oninput="sync()" rows="2" placeholder="पक्षकार 2 चा हिस्सा" class="form-textarea"></textarea></div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header"><span class="num">7</span><span class="title">साक्षीदार (Witness)</span></div>
                <div class="section-body space-y-2.5">
                    <div><label class="field-label">साक्षीदार 1</label><input type="text" id="inp_wit1" oninput="sync()" class="form-input"></div>
                    <div><label class="field-label">साक्षीदार 2</label><input type="text" id="inp_wit2" oninput="sync()" class="form-input"></div>
                </div>
            </div>

            <div class="flex items-center gap-2 px-3 py-2.5 bg-indigo-50/60 rounded-xl border border-indigo-100">
                <i data-lucide="wallet" class="w-3.5 h-3.5 text-indigo-400"></i>
                <span class="text-[11px] text-indigo-600 font-medium">Wallet: ₹<span id="walletBal">{{ number_format($balance, 2) }}</span></span>
            </div>

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

        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-0"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Noto Sans Devanagari', 'Mukta', sans-serif;">
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>
            <div id="gap1" class="bond-gap w-full relative overflow-hidden"
                 style="height: 80mm; background: repeating-linear-gradient(45deg, #e8e8e8, #e8e8e8 2px, #f5f5f5 2px, #f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 1 GAP (Adjust Slider for Stamp Paper)</span>
            </div>

            <div id="page1_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">
                <h2 class="text-center font-bold text-xl underline mb-4">कौटुंबिक वाटणीपत्र</h2>

                <p class="leading-8 text-justify mb-3">
                    आम्ही खालील सही करणार, मूळ मालक
                    <span id="out_original_owner" class="out-field empty">&nbsp;</span>
                    रा.
                    <span id="out_original_addr" class="out-field empty">&nbsp;</span>
                    यांच्या मालकीची / वडिलोपार्जित मिळकत खालीलप्रमाणे विभागणी
                    (Partition) करण्यासाठी हे वाटणीपत्र लिहून देत आहोत.
                </p>

                <div class="border border-gray-800 rounded px-4 py-3 mb-3">
                    <p class="mb-1"><span class="underline font-semibold">पक्षकार 1:</span></p>
                    <p class="ml-6 mb-0 leading-8">
                        <span id="out_party1" class="out-field empty">&nbsp;</span>,
                        वय <span id="out_party1_age" class="out-field empty">&nbsp;</span> वर्षे,
                        नाते: <span id="out_party1_rel" class="out-field filled">मुलगा</span>
                    </p>
                </div>

                <div class="border border-gray-800 rounded px-4 py-3 mb-3">
                    <p class="mb-1"><span class="underline font-semibold">पक्षकार 2:</span></p>
                    <p class="ml-6 mb-0 leading-8">
                        <span id="out_party2" class="out-field empty">&nbsp;</span>,
                        वय <span id="out_party2_age" class="out-field empty">&nbsp;</span> वर्षे,
                        नाते: <span id="out_party2_rel" class="out-field filled">मुलगा</span>
                    </p>
                </div>

                <p class="font-bold underline mb-2">● मिळकतीचा तपशील:</p>
                <p class="leading-8 text-justify mb-3">
                    मौजे
                    <span id="out_village" class="out-field empty">&nbsp;</span>,
                    ता.
                    <span id="out_place_top" class="out-field filled">जालना</span>
                    येथील, गट / सर्व्हे नं.
                    <span id="out_survey" class="out-field empty">&nbsp;</span>,
                    एकूण क्षेत्रफळ
                    <span id="out_total_area" class="out-field empty">&nbsp;</span>.
                    <span id="out_prop_desc" class="out-field empty">&nbsp;</span>
                </p>

                <p class="font-bold underline mb-2">● वाटणी तपशील:</p>
                <p class="leading-8 text-justify mb-2">
                    <span class="font-semibold">पक्षकार 1 चा हिस्सा:</span>
                    <span id="out_share1" class="out-field empty">&nbsp;</span>
                </p>
                <p class="leading-8 text-justify mb-3">
                    <span class="font-semibold">पक्षकार 2 चा हिस्सा:</span>
                    <span id="out_share2" class="out-field empty">&nbsp;</span>
                </p>

                <div id="redLine" class="w-full border-t-2 border-dashed border-red-400 text-center text-red-400 text-xs py-1 my-2 no-print select-none">
                    --- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---
                </div>
            </div>
        </div>

        <div id="page2" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mt-0"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Noto Sans Devanagari', 'Mukta', sans-serif;">
            <div id="watermark2" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>
            <div id="gap2" class="bond-gap w-full relative overflow-hidden"
                 style="height: 60mm; background: repeating-linear-gradient(45deg, #e8e8e8, #e8e8e8 2px, #f5f5f5 2px, #f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 2 GAP (Adjust Slider)</span>
            </div>

            <div id="page2_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">
                <p class="font-bold underline mb-2">● अटी व शर्ती :-</p>

                <p class="leading-8 text-justify mb-2">
                    सदर वाटणी ही आम्हा उभय पक्षकारांनी आपापसातील सामंजस्याने
                    व सहमतीने केली आहे. यापुढे कोणीही या वाटणीबद्दल कोणत्याही
                    प्रकारची तक्रार करणार नाही.
                </p>

                <p class="leading-8 text-justify mb-2">
                    प्रत्येक पक्षकाराने आपापल्या हिश्श्यातील मिळकतीवर स्वतंत्र
                    कब्जा घ्यावा व त्याचा उपभोग करावा. एकमेकांच्या हिश्श्यात
                    कोणताही हस्तक्षेप करता कामा नये.
                </p>

                <p class="leading-8 text-justify mb-2">
                    सदर वाटणीनुसार प्रत्येक पक्षकाराने आपापल्या हिश्श्याचे
                    सर्व प्रकारचे कर, पाणीपट्टी, वीज बिल व इतर शासकीय देणे
                    स्वतः भरावे.
                </p>

                <p class="leading-8 text-justify mb-2">
                    भविष्यात सदर मिळकतीबाबत कोणताही वाद उद्भवल्यास तो
                    <span id="out_place_juris" class="out-field filled">जालना</span>
                    येथील न्यायालयाच्या अधिकारक्षेत्रात राहील.
                </p>

                <p class="leading-8 text-justify mb-6">
                    करीता हे वाटणीपत्र आम्ही उभय पक्षकारांनी आमच्या
                    राजीमर्जीने, अक्कलहुशारीने, कोणत्याही दबावाशिवाय,
                    साक्षीदारांसमक्ष लिहून दिले व घेतले असे.
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
                            <span id="out_party1_sig" class="out-field empty"></span>
                        </div>
                        <p class="text-xs text-gray-600">पक्षकार 1</p>
                    </div>
                    <div class="text-center">
                        <div class="border-t border-gray-700 w-40 mb-1 pt-1">
                            <span id="out_party2_sig" class="out-field empty"></span>
                        </div>
                        <p class="text-xs text-gray-600">पक्षकार 2</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- GUIDE MODAL --}}
<div id="guideModal" class="fixed inset-0 bg-black/60 z-50 items-center justify-center hidden" onclick="closeGuide(event)">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">वापरकर्ता मार्गदर्शक</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="text-sm text-gray-700 space-y-3">
            <p>डाव्या बाजूला फॉर्म भरा → उजव्या बाजूला Preview बघा → Pay & Print करा.</p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-blue-700 text-xs">PDF डाऊनलोड: 'Pay & Download' → Print → 'Save as PDF'</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openGuide(){document.getElementById('guideModal').classList.remove('hidden');document.getElementById('guideModal').classList.add('flex');}
function closeModal(){document.getElementById('guideModal').classList.add('hidden');document.getElementById('guideModal').classList.remove('flex');}
function closeGuide(e){if(e.target.id==='guideModal')closeModal();}
function setGap1(v){document.getElementById('gap1').style.height=v+'mm';checkOverflow();}
function setGap2(v){document.getElementById('gap2').style.height=v+'mm';}
function setFont(v){if(typeof bondSetFontAll==='function')bondSetFontAll(v);checkOverflow();}
function setPad(id,prop,v){var el=document.getElementById(id);if(el)el.style[prop]=v+'px';}
function checkOverflow(){var b=document.getElementById('redLine');if(!b)return;if(b.offsetTop+b.offsetHeight>1123){b.classList.add('text-red-700','font-bold','bg-red-50');b.innerText='सावधान! मजकूर रेषेच्या खाली गेला आहे.';}else{b.classList.remove('text-red-700','font-bold','bg-red-50');b.innerText='--- येथे पान संपले (ही लाईन प्रिंटमध्ये येणार नाही) ---';}}

var syncMap={
    'inp_date':['out_date_btm'],
    'inp_place':['out_place_top','out_place_btm','out_place_juris'],
    'inp_original_owner':['out_original_owner'],
    'inp_original_addr':['out_original_addr'],
    'inp_party1':['out_party1','out_party1_sig'],
    'inp_party1_age':['out_party1_age'],
    'inp_party1_rel':['out_party1_rel'],
    'inp_party2':['out_party2','out_party2_sig'],
    'inp_party2_age':['out_party2_age'],
    'inp_party2_rel':['out_party2_rel'],
    'inp_survey':['out_survey'],
    'inp_total_area':['out_total_area'],
    'inp_village':['out_village'],
    'inp_prop_desc':['out_prop_desc'],
    'inp_share1':['out_share1'],
    'inp_share2':['out_share2'],
    'inp_wit1':['out_wit1'],
    'inp_wit2':['out_wit2'],
};

function sync(){for(var id in syncMap){var el=document.getElementById(id);if(!el)continue;var val=el.value;if(id==='inp_date'&&val){var d=new Date(val);val=('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();}syncMap[id].forEach(function(outId){var o=document.getElementById(outId);if(!o)return;o.innerText=val||'\u00A0';if(val&&val.trim()!==''){o.classList.remove('empty');o.classList.add('filled');}else{o.classList.remove('filled');o.classList.add('empty');}});}checkOverflow();}

window.addEventListener('DOMContentLoaded',function(){sync();lucide.createIcons();});

function payAndPrint(){
    if(!document.getElementById('inp_party1').value.trim()){alert('कृपया पक्षकार 1 चे नाव टाका');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for Partition Deed?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'partition-deed'})})
    .then(function(r){return r.json();}).then(function(data){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}else{alert('Failed: '+data.message);}}).catch(function(e){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();alert('Payment failed.');});
}
</script>
@endpush
