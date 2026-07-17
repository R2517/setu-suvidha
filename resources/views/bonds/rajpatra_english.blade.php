@extends('layouts.bond-maker')
@section('title', 'Rajpatra English — Name Change Notice')

@push('styles')
<style>
    .name-tbl{width:100%;border-collapse:collapse;margin:8px 0;}
    .name-tbl th,.name-tbl td{border:1.5px solid #333;padding:5px 8px;font-size:0.92em;text-align:center;}
    .name-tbl th{background:#f5f5f5;font-weight:600;}
    .name-tbl .rl{font-weight:600;background:#f8f8f8;width:80px;text-align:center;font-size:0.85em;}
    .addr-tbl{width:55%;margin-top:6px;}
    .addr-tbl td{padding:2px 4px;font-size:0.9em;vertical-align:top;}
    .addr-tbl td:first-child{font-weight:500;white-space:nowrap;width:140px;}
    .addr-tbl td:nth-child(2){width:12px;text-align:center;}
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">Rajpatra English</h2>
                    <p class="text-[10px] text-gray-400">Gazette Name Change — English</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 1: Old Name --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">Old Name</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Name</label><input type="text" id="inp_old_name" oninput="sync()" placeholder="Old Name" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 2: New Name --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">New Name</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Name</label><input type="text" id="inp_new_name" oninput="sync()" placeholder="New Name" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 3: Reason --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">Reason & Details</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Reason for change of Name</label><input type="text" id="inp_reason" oninput="sync()" placeholder="Marriage / Other reason" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 4: Corresponding Address --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">Corresponding Address</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">New Name</label><input type="text" id="inp_corr_name" oninput="sync()" placeholder="New Name" class="form-input"></div>
                    <div><label class="field-label">Address</label><textarea id="inp_corr_addr" oninput="sync()" rows="2" placeholder="Full Address" class="form-textarea"></textarea></div>
                    <div class="grid grid-cols-3 gap-2">
                        <div><label class="field-label">Pincode</label><input type="text" id="inp_corr_pin" oninput="sync()" placeholder="411001" class="form-input"></div>
                        <div><label class="field-label">Tel. No</label><input type="text" id="inp_corr_tel" oninput="sync()" placeholder="0240-XXXXX" class="form-input"></div>
                        <div><label class="field-label">Mobile No.</label><input type="text" id="inp_corr_mob" oninput="sync()" placeholder="98XXXXXXXX" class="form-input"></div>
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
                    <img src="{{ asset('images/mh-logo.png') }}" alt="Maharashtra Emblem" class="emblem-img" onerror="this.onerror=null; this.outerHTML='<div class=\'emblem-img flex items-center justify-center text-center font-bold text-yellow-700 bg-yellow-50 border-2 border-yellow-500 rounded-full text-[10px] leading-tight shrink-0 shadow-sm\' style=\'width:65px;height:65px;\'>GOV<br>MH</div>';">
                    <div class="text-center flex-1">
                        <p class="font-bold text-sm mb-0">GOVERNMENT OF MAHARASHTRA</p>
                        <p class="font-bold text-sm mb-0">DIRECTORATE OF GOVERNMENT PRINTING, STATIONERY AND PUBLICATION</p>
                        <p class="font-bold text-sm mb-0">FORM FOR CHANGE OF NAME</p>
                        <p class="font-bold text-base underline mb-0">NOTICE</p>
                    </div>
                </div>

                {{-- NB Note --}}
                <div class="note-box">
                    <p class="font-bold italic mb-1" style="font-size:0.92em;">N.B —</p>
                    <p>(Instructions may be followed carefully before filling up this form. Only one word should be written in each space printed below. Please fill up this form in English version and in BLOCK LETTERS only)</p>
                </div>

                {{-- Declaration --}}
                <p class="leading-8 text-justify mb-2">
                    It is hereby notified that the undersigned has changed his/her name from
                </p>

                <table class="name-tbl">
                    <thead>
                        <tr>
                            <th style="width:100px;"></th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="rl">Old Name</td>
                            <td><span id="out_old_name" class="out-field empty">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <td class="rl" style="font-size:0.8em;">To</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="rl">New Name</td>
                            <td><span id="out_new_name" class="out-field empty">&nbsp;</span></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Note --}}
                <p class="text-sm leading-6 text-justify mb-3 mt-2">
                    <span class="font-semibold">Note :-</span> Government accepts no responsibility as to the authenticity of the contents of the notice. Since they are based entirely on the application of the concerned persons without verification of documents.
                </p>

                {{-- Signatures --}}
                <div class="flex justify-between items-start mb-2 mt-4">
                    <div>
                        <p class="mb-0 text-sm">Signature of the Guardian</p>
                        <p class="mb-1 text-sm">........................</p>
                        <p class="text-xs text-gray-600 italic">( In case of Minor )</p>
                    </div>
                    <div class="text-left" style="max-width:55%;">
                        <p class="mb-0 text-sm">Signature in Old name/ Thumb Impression with Name and Date</p>
                        <p class="mb-1 text-sm">......................................................</p>
                        <p class="text-xs text-gray-600 italic">(Write down the name of the person in the above space who has signed above)</p>
                    </div>
                </div>

                {{-- To Director --}}
                <p class="mt-6 mb-0">To</p>
                <p class="mb-0 font-bold">THE DIRECTOR,</p>
                <p class="leading-7 text-justify mb-0 ml-6">
                    Government Printing, Stationery and Publications, Maharashtra, Mumbai 400 004<br>
                    Kindly publish the above Notice in the next issue of the Maharashtra Government Gazette, Part II.
                </p>

                {{-- Reason --}}
                <p class="mt-6 mb-0">Reason for change of Name : <span id="out_reason" class="out-field empty">&nbsp;</span></p>

                {{-- New Name Signature --}}
                <div class="text-right mt-12 mb-4">
                    <p class="mb-0">Signature in New Name/Thumb Impression with Name and Date,</p>
                </div>

                {{-- Correspondence Address --}}
                <div class="border-t border-gray-400 pt-3 mt-4">
                    <p class="font-bold text-sm mb-1">FOR CORRESPONDING ADDRESS:</p>
                    <table class="addr-tbl">
                        <tr><td>New Name</td><td>:</td><td><span id="out_corr_name" class="out-field empty">&nbsp;</span></td></tr>
                        <tr><td>Address</td><td>:</td><td><span id="out_corr_addr" class="out-field empty">&nbsp;</span></td></tr>
                        <tr><td>Pincode</td><td>:</td><td><span id="out_corr_pin" class="out-field empty">&nbsp;</span></td></tr>
                        <tr><td>Tel. No</td><td>:</td><td><span id="out_corr_tel" class="out-field empty">&nbsp;</span></td></tr>
                        <tr><td>Mobile No.</td><td>:</td><td><span id="out_corr_mob" class="out-field empty">&nbsp;</span></td></tr>
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
            <h3 class="text-lg font-bold text-gray-800">User Guide</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="text-sm text-gray-700 space-y-3">
            <div><p class="font-bold text-gray-800 mb-1">1. Fill Details:</p><p>Enter old name, new name, reason and address on the left. Live preview appears on right.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. Use BLOCK LETTERS:</p><p>All names should be in CAPITAL LETTERS as per government guidelines.</p></div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3"><p class="text-blue-700 text-xs">For PDF: Click 'Pay & Print' → In print window → Select 'Save as PDF'.</p></div>
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
    if(!document.getElementById('inp_old_name').value.trim()){alert('Please enter Old Name');return;}
    if(!document.getElementById('inp_new_name').value.trim()){alert('Please enter New Name');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for Rajpatra English?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'rajpatra-english'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
