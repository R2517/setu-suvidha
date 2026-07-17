@extends('layouts.bond-maker')
@section('title', 'राजपत्र मराठी — नाव बदल नोटीस')

@push('styles')
<style>
    .name-tbl{width:100%;border-collapse:collapse;margin:8px 0;}
    .name-tbl th,.name-tbl td{border:1.5px solid #333;padding:5px 8px;font-size:0.92em;text-align:center;}
    .name-tbl th{background:#f5f5f5;font-weight:600;}
    .name-tbl .rl{font-weight:600;background:#f8f8f8;width:80px;text-align:center;font-size:0.85em;}
    .addr-tbl{width:55%;margin-top:6px;}
    .addr-tbl td{padding:2px 4px;font-size:0.9em;vertical-align:top;}
    .addr-tbl td:first-child{font-weight:500;white-space:nowrap;width:160px;}
    .addr-tbl td:nth-child(2){width:12px;text-align:center;}
    .dot-line{border-bottom:1px dotted #555;display:inline-block;min-width:180px;}
    .emblem-wrap{display:flex;align-items:flex-start;gap:16px;margin-bottom:6px;}
    .emblem-img{width:65px;height:65px;object-fit:contain;flex-shrink:0;}
    .note-box{border:1px solid #999;border-radius:4px;padding:8px 12px;background:#fafafa;margin-bottom:12px;}
    .note-box p{font-size:0.88em;line-height:1.7;text-align:justify;}
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">राजपत्र मराठी</h2>
                    <p class="text-[10px] text-gray-400">Gazette Name Change — Marathi</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 1: जुने नाव --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">जुने नाव (Old Name)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">नाव</label><input type="text" id="inp_old_name" oninput="sync()" placeholder="जुने नाव" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 2: नवीन नाव --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">नवीन नाव (New Name)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">नाव</label><input type="text" id="inp_new_name" oninput="sync()" placeholder="नवीन नाव" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 3: कारण --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">कारण व तपशील</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">नाव बदलण्याचे कारण</label><input type="text" id="inp_reason" oninput="sync()" placeholder="विवाहामुळे / इतर कारण" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 4: पत्रव्यवहार पत्ता --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">पत्रव्यवहाराचा पत्ता</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">अर्जदाराचे नवीन नाव</label><input type="text" id="inp_corr_name" oninput="sync()" placeholder="पूर्ण नवीन नाव" class="form-input"></div>
                    <div><label class="field-label">संपूर्ण पत्ता</label><textarea id="inp_corr_addr" oninput="sync()" rows="2" placeholder="संपूर्ण पत्ता" class="form-textarea"></textarea></div>
                    <div class="grid grid-cols-3 gap-2">
                        <div><label class="field-label">पिन क्र.</label><input type="text" id="inp_corr_pin" oninput="sync()" placeholder="411001" class="form-input"></div>
                        <div><label class="field-label">दूरध्वनी क्र</label><input type="text" id="inp_corr_tel" oninput="sync()" placeholder="0240-XXXXX" class="form-input"></div>
                        <div><label class="field-label">भ्रमणध्वनी क्र.</label><input type="text" id="inp_corr_mob" oninput="sync()" placeholder="98XXXXXXXX" class="form-input"></div>
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

        {{-- ══════ A4 PAGE 1 ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-0"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Poppins', 'Noto Sans Devanagari', sans-serif;">

            {{-- Page Border --}}
            <div class="absolute inset-0 border-[3px] border-gray-900 pointer-events-none z-40" style="margin: 40px;"></div>

            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="page1_content" class="bond-content pt-16 pb-8" contenteditable="true" spellcheck="false" style="padding-left:60px;padding-right:60px;">

                {{-- Header with Emblem --}}
                <div class="emblem-wrap">
                    <img src="{{ asset('images/mh-logo.png') }}" alt="Maharashtra Emblem" class="emblem-img" onerror="this.onerror=null; this.outerHTML='<div class=\'emblem-img flex items-center justify-center text-center font-bold text-yellow-700 bg-yellow-50 border-2 border-yellow-500 rounded-full text-[8px] leading-tight shrink-0 shadow-sm\' style=\'width:65px;height:65px;\'>म.रा.<br>शासन</div>';">
                    <div class="text-center flex-1">
                        <p class="font-bold text-base mb-0">महाराष्ट्र शासन</p>
                        <p class="font-bold text-sm mb-0">शासन मुद्रण, लेखनसामग्री व प्रकाशन संचालनालय</p>
                        <p class="font-bold text-sm mb-0">नाव बदलण्याचा नमुना</p>
                        <p class="font-bold text-base underline mb-0">नोटीस</p>
                    </div>
                </div>

                {{-- विशेष सूचना --}}
                <div class="note-box">
                    <p class="font-bold mb-1" style="font-size:0.92em;">विशेष सूचना :-</p>
                    <p>या नमुना भरण्यापूर्वी मागील बाजूस दिलेल्या सूचना काळजीपूर्वक अनुसरल्या पाहिजेत. वाळी लागणेल्या प्रत्येक मोकळ्या जागी फक्त एकच शब्द लिहिला पाहिजे. कोणतीही पदवाळणी न करता अर्जदारांनी अर्जात सदर केलेल्या माहितीवर आधारित सदर जाहिरात असल्यापुळे जाहिरातील असलेल्या मजकुराबद्दलच्या सत्यतेविषयी शासन कुठलीही जबाबदारी स्वीकारणार नाही. खालील नोटीस फक्त मराठीत लिहावी.</p>
                </div>

                {{-- Declaration --}}
                <p class="leading-8 text-justify mb-2">
                    यावरून असे जाहेर करण्यात येत आहे की, खाली सही करणाऱ्याने / करणारीने आपले जुने नाव —
                </p>

                {{-- Name Change Table --}}
                <table class="name-tbl">
                    <thead>
                        <tr>
                            <th style="width:120px;"></th>
                            <th>नाव</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="rl">जुने नाव</td>
                            <td><span id="out_old_name" class="out-field empty">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <td class="rl">नवीन नाव</td>
                            <td><span id="out_new_name" class="out-field empty">&nbsp;</span></td>
                        </tr>
                    </tbody>
                </table>

                <p class="leading-8 mb-3">हे नवीन नाव कारण केले आहे.</p>

                {{-- Signatures --}}
                <div class="flex justify-between items-start mb-2 mt-4">
                    <div>
                        <p class="mb-0 text-sm">आई / वडिलांचे अथवा पालकांची सही ....................</p>
                        <p class="text-xs text-gray-600 italic">( फक्त अल्पवयीन इसमाच्या बाबतीत )</p>
                    </div>
                    <div class="text-right">
                        <p class="mb-0 text-sm">..........................................................</p>
                        <p class="text-sm">जुन्या नावाप्रमाणे सही व तारीख</p>
                    </div>
                </div>

                {{-- प्रति संचालक --}}
                <p class="mt-6 mb-0 font-semibold">प्रति</p>
                <p class="mb-0 font-semibold">संचालक,</p>
                <p class="leading-7 text-justify mb-0 ml-6">
                    शासन मुद्रण, लेखनसामग्री व प्रकाशन संचालनालय, महाराष्ट्र राज्य, नेताजी सुभाष रोड, मुंबई ४०००००४, यांस-<br>
                    महाराष्ट्र शासन राजपत्र, भाग दोन याच्या पुढील अंकात वरील नोटीस प्रसिद्ध करावी.
                </p>

                {{-- Reason --}}
                <p class="mt-6 mb-0">नाव बदलण्याचे कारण : <span id="out_reason" class="out-field empty">&nbsp;</span></p>

                {{-- Applicant Signature --}}
                <div class="text-right mt-12 mb-4">
                    <p class="mb-0">आपला/आपली विश्वासु,</p>
                    <p class="mb-0">अर्जदाराची सही ——————————</p>
                </div>

                {{-- Correspondence Address --}}
                <div class="border-t border-gray-400 pt-3 mt-4">
                    <p class="font-semibold text-sm mb-1">पत्रव्यवहाराचा पत्ता:</p>
                    <table class="addr-tbl">
                        <tr><td>अर्जदाराचे नवीन नाव</td><td>:</td><td><span id="out_corr_name" class="out-field empty">&nbsp;</span></td></tr>
                        <tr><td>संपूर्ण पत्ता</td><td>:</td><td><span id="out_corr_addr" class="out-field empty">&nbsp;</span></td></tr>
                        <tr><td>पिन क्र.</td><td>:</td><td><span id="out_corr_pin" class="out-field empty">&nbsp;</span></td></tr>
                        <tr><td>दूरध्वनी क्र</td><td>:</td><td><span id="out_corr_tel" class="out-field empty">&nbsp;</span></td></tr>
                        <tr><td>भ्रमणध्वनी क्र.</td><td>:</td><td><span id="out_corr_mob" class="out-field empty">&nbsp;</span></td></tr>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Guide Modal --}}
<div id="guideModal" class="fixed inset-0 bg-black/60 z-50 items-center justify-center hidden" onclick="closeGuide(event)">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">वापरकर्ता मार्गदर्शक (Guide)</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="text-sm text-gray-700 space-y-3">
            <div><p class="font-bold text-gray-800 mb-1">1. माहिती भरा:</p><p>डाव्या फॉर्ममध्ये जुने नाव, नवीन नाव, कारण व पत्ता भरा. उजव्या बाजूस लाईव्ह प्रिव्ह्यू दिसेल.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. Gap Adjust:</p><p>वरच्या स्लाइडर्सने Gap व Padding adjust करा.</p></div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3"><p class="text-blue-700 text-xs">PDF साठी 'Pay & Print' → प्रिंट विंडो → 'Save as PDF' निवडा.</p></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openGuide(){document.getElementById('guideModal').classList.remove('hidden');document.getElementById('guideModal').classList.add('flex');}
function closeModal(){document.getElementById('guideModal').classList.add('hidden');document.getElementById('guideModal').classList.remove('flex');}
function closeGuide(e){if(e.target.id==='guideModal')closeModal();}



var syncMap={
    'inp_old_name':['out_old_name'],
    'inp_new_name':['out_new_name'],
    'inp_reason':['out_reason'],
    'inp_corr_name':['out_corr_name'],'inp_corr_addr':['out_corr_addr'],
    'inp_corr_pin':['out_corr_pin'],'inp_corr_tel':['out_corr_tel'],'inp_corr_mob':['out_corr_mob'],
};

function sync(){
    for(var id in syncMap){
        var el=document.getElementById(id);if(!el)continue;
        var val=el.value;
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
    if(!document.getElementById('inp_old_name').value.trim()){alert('कृपया जुने नाव टाका');return;}
    if(!document.getElementById('inp_new_name').value.trim()){alert('कृपया नवीन नाव टाका');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for राजपत्र मराठी?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'rajpatra-marathi'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
