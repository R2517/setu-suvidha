@extends('layouts.bond-maker')
@section('title', 'Birth Date Change Notice — SETU Suvidha')

@push('styles')
<style>
    .dob-tbl { width: 100%; border-collapse: collapse; margin: 10px 0; }
    .dob-tbl td { border: 1.5px solid #222; padding: 6px 10px; font-size: 0.93em; vertical-align: middle; }
    .dob-tbl td.lbl { font-weight: 700; background: #f5f5f5; width: 110px; text-align: center; }
    .emblem-wrap { display: flex; align-items: center; gap: 18px; margin-bottom: 10px; }
    .emblem-img { width: 72px; height: 72px; object-fit: contain; flex-shrink: 0; }
    .addr-block { margin-top: 10px; }
    .addr-block table { width: 60%; }
    .addr-block td { padding: 2px 5px; font-size: 0.92em; vertical-align: top; }
    .addr-block td:first-child { font-weight: 600; white-space: nowrap; width: 120px; }
    .addr-block td:nth-child(2) { width: 12px; }
    .note-box { border: 1px solid #999; border-radius: 3px; padding: 7px 12px; background: #fafafa; margin-bottom: 10px; }
    .note-box p { font-size: 0.87em; line-height: 1.65; text-align: justify; }
    .sig-row { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 18px; }

    /* Outer + Inner double border like real Gazette */
    .outer-border {
        position: absolute;
        top: 24px; bottom: 24px; left: 24px; right: 24px;
        border: 3px solid #111;
        pointer-events: none; z-index: 40;
    }
    .inner-border {
        position: absolute;
        top: 31px; bottom: 31px; left: 31px; right: 31px;
        border: 1px solid #444;
        pointer-events: none; z-index: 41;
    }

    @media print {
        @page { size: A4; margin: 0; }
        body { margin: 0; }
        .bond-page { width: 210mm !important; height: 297mm !important; overflow: hidden !important; }
        .bond-watermark { display: none !important; }
        .outer-border { border: 3px solid #000 !important; }
        .inner-border { border: 1px solid #333 !important; }
        .dob-tbl td { border: 1.5px solid #222 !important; }
        .font-bold { font-weight: bold !important; }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">Birth Date Change Notice</h2>
                    <p class="text-[10px] text-gray-400">जन्म दिनांक बदल — राजपत्र</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 1: Old DOB --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">Old Date of Birth (FROM)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Select Old DOB</label><input type="date" id="inp_old_dob_date" onchange="convertDobToWords('inp_old_dob_date','inp_old_dob')" class="form-input"></div>
                    <div><label class="field-label">DOB in Words & Figures (auto-filled)</label><input type="text" id="inp_old_dob" oninput="sync()" placeholder="Auto-generated from date above" class="form-input" style="background:#f0f9ff; font-weight:600;"></div>
                </div>
            </div>

            {{-- SEC 2: New DOB --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">New Date of Birth (TO)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Select New DOB</label><input type="date" id="inp_new_dob_date" onchange="convertDobToWords('inp_new_dob_date','inp_new_dob')" class="form-input"></div>
                    <div><label class="field-label">DOB in Words & Figures (auto-filled)</label><input type="text" id="inp_new_dob" oninput="sync()" placeholder="Auto-generated from date above" class="form-input" style="background:#f0f9ff; font-weight:600;"></div>
                </div>
            </div>


            {{-- SEC 3: Reason --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">Reason for Change</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Reason</label><input type="text" id="inp_reason" oninput="sync()" placeholder="e.g. Correction in School Certificate" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 4: Applicant Details --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">Applicant Details</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Full Name</label><input type="text" id="inp_full_name" oninput="sync()" placeholder="Full Name (BLOCK LETTERS)" class="form-input"></div>
                    <div><label class="field-label">Address</label><textarea id="inp_address" oninput="sync()" rows="2" placeholder="Full Address" class="form-textarea"></textarea></div>
                    <div><label class="field-label">Pin Code</label><input type="text" id="inp_pincode" oninput="sync()" placeholder="411001" class="form-input"></div>
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

    {{-- ═══════════════════ RIGHT PANEL — A4 PREVIEW ═══════════════════ --}}
    <div class="flex-1 overflow-y-auto bg-[#555] pt-4 pb-8 px-4 relative preview-area">

        {{-- A4 PAGE --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl"
             style="height: 1122px; overflow: hidden; font-size: 11pt; font-family: 'Times New Roman', 'Noto Serif', serif; line-height: 1.5; box-sizing: border-box;">

            {{-- Double Border (Outer thick + Inner thin) --}}
            <div class="outer-border"></div>
            <div class="inner-border"></div>

            {{-- Watermark --}}
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            {{-- Content --}}
            <div id="page1_content" class="bond-content" contenteditable="true" spellcheck="false"
                 style="padding: 52px 58px 30px 58px; position: relative; z-index: 10;">

                {{-- Header: Emblem + Title --}}
                <div class="emblem-wrap">
                    <img src="{{ asset('images/mh-logo.png') }}" alt="Maharashtra Emblem" class="emblem-img"
                         onerror="this.onerror=null; this.outerHTML='<div style=\'width:72px;height:72px;border:2px solid #bbb;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:bold;text-align:center;color:#555;flex-shrink:0;\'>GOV<br>MH</div>';">
                    <div style="text-align: center; flex: 1;">
                        <p style="font-weight: 700; font-size: 11.5pt; margin-bottom: 1px;">GOVERNMENT OF MAHARASHTRA</p>
                        <p style="font-weight: 700; font-size: 11pt; margin-bottom: 1px;">DIRECTORATE OF GOVERNMENT PRINTING, STATIONERY AND PUBLICATION</p>
                        <p style="font-weight: 700; font-size: 11.5pt; margin-bottom: 1px;">FORM FOR CHANGE OF BIRTH DATE</p>
                        <p style="font-weight: 800; font-size: 12pt; text-decoration: underline; margin-bottom: 0;">NOTICE</p>
                    </div>
                </div>

                {{-- NB Note --}}
                <div class="note-box">
                    <p><span style="font-style:italic; font-weight:700;">(N.B-</span> Date, Month and Year should be written in words only in each space printed below. Please fill up this form in English version and in (BLOCK LETTERS only)</p>
                </div>

                {{-- Declaration --}}
                <p style="margin-bottom: 8px;">It is hereby notified that the undersigned has changed his/her birth date</p>

                {{-- DOB Table --}}
                <table class="dob-tbl">
                    <tbody>
                        <tr>
                            <td class="lbl">From</td>
                            <td><span id="out_old_dob" class="out-field empty">&nbsp;</span></td>
                        </tr>
                        <tr>
                            <td class="lbl">To</td>
                            <td><span id="out_new_dob" class="out-field empty">&nbsp;</span></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Government Note --}}
                <p style="font-size: 0.9em; text-align: justify; margin-top: 10px; margin-bottom: 14px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;<strong>Note :-</strong> Government accepts no responsibility as to the authenticity of the contents of the notice. Since they are based entirely on the application of the concerned persons without varification of document.
                </p>

                {{-- Signatures Row --}}
                <div class="sig-row">
                    <div>
                        <p style="margin-bottom: 3px;">Date</p>
                        <p style="margin-bottom: 3px;">Signature of the Guardian ..........................</p>
                        <p style="font-size: 0.88em; color: #555; font-style: italic;">( In case of Minor )</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin-bottom: 2px;">Full Name : <span id="out_full_name" class="out-field empty">&nbsp;</span></p>
                        <p style="margin-bottom: 2px;">Address : <span id="out_address" class="out-field empty">&nbsp;</span></p>
                        <p style="margin-bottom: 10px;">PinCode : <span id="out_pincode" class="out-field empty">&nbsp;</span></p>
                        <p style="margin-bottom: 1px;">------------------------------</p>
                        <p style="font-size: 0.92em;">Signature / Thumb Impression</p>
                    </div>
                </div>

                {{-- To Director --}}
                <p style="margin-top: 16px; margin-bottom: 0;">To</p>
                <p style="font-weight: 800; margin-bottom: 0;">THE DIRECTOR,</p>
                <p style="margin-bottom: 0;">Government Printing, Stationery and Publications, Maharashtra, Mumbai 400 004.</p>

                {{-- Reason --}}
                <p style="margin-top: 14px; font-weight: 700;">Reason for change of Birth Date</p>
                <p style="min-height: 22px; border-bottom: 1px solid #aaa; margin-bottom: 4px;"><span id="out_reason" class="out-field empty">&nbsp;</span></p>

                {{-- Bottom Signature --}}
                <div style="text-align: right; margin-top: 55px;">
                    <p style="font-weight: 700; margin-bottom: 1px;">(Signature)</p>
                    <p style="font-size: 0.88em; color: #444;">Left hand thumb Impression in case of illiterate person</p>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Guide Modal --}}
<div id="guideModal" class="fixed inset-0 bg-black/60 z-50 items-center justify-center hidden" onclick="closeGuide(event)">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">सूचना / Instructions</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="text-sm text-gray-700 space-y-3">
            <div><p class="font-bold text-gray-800 mb-1">1. Fill DOB in Words:</p><p>Write date in words e.g. "FIRST JANUARY NINETEEN NINETY" and in figures "01/01/1990"</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. Use BLOCK LETTERS only.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">3. Reason for change:</p><p>Write the exact reason e.g. "Correction as per School Leaving Certificate"</p></div>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3"><p class="text-blue-700 text-xs">For PDF: Click 'Pay & Print' → In print window → Select 'Save as PDF'.</p></div>
        </div>
    </div>
</div>
{{-- Print Frame --}}
<iframe id="printFrame" style="display:none;"></iframe>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function openGuide(){ document.getElementById('guideModal').classList.remove('hidden'); document.getElementById('guideModal').classList.add('flex'); }
function closeModal(){ document.getElementById('guideModal').classList.add('hidden'); document.getElementById('guideModal').classList.remove('flex'); }
function closeGuide(e){ if(e.target.id==='guideModal') closeModal(); }

var syncMap = {
    'inp_old_dob':   ['out_old_dob'],
    'inp_new_dob':   ['out_new_dob'],
    'inp_reason':    ['out_reason'],
    'inp_full_name': ['out_full_name'],
    'inp_address':   ['out_address'],
    'inp_pincode':   ['out_pincode'],
};

function sync(){
    for(var id in syncMap){
        var el = document.getElementById(id); if(!el) continue;
        var val = el.value;
        syncMap[id].forEach(function(outId){
            var o = document.getElementById(outId); if(!o) return;
            o.innerText = val || '\u00A0';
            if(val && val.trim() !== ''){ o.classList.remove('empty'); o.classList.add('filled'); }
            else { o.classList.remove('filled'); o.classList.add('empty'); }
        });
    }
}

window.addEventListener('DOMContentLoaded', function(){ sync(); lucide.createIcons(); });

// ═══════════════ DATE → ENGLISH WORDS CONVERTER ═══════════════
var dayWords = ['','FIRST','SECOND','THIRD','FOURTH','FIFTH','SIXTH','SEVENTH','EIGHTH','NINTH','TENTH',
    'ELEVENTH','TWELFTH','THIRTEENTH','FOURTEENTH','FIFTEENTH','SIXTEENTH','SEVENTEENTH','EIGHTEENTH','NINETEENTH','TWENTIETH',
    'TWENTY-FIRST','TWENTY-SECOND','TWENTY-THIRD','TWENTY-FOURTH','TWENTY-FIFTH','TWENTY-SIXTH','TWENTY-SEVENTH','TWENTY-EIGHTH','TWENTY-NINTH','THIRTIETH','THIRTY-FIRST'];

var monthWords = ['','JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER'];

var onesWords  = ['','ONE','TWO','THREE','FOUR','FIVE','SIX','SEVEN','EIGHT','NINE','TEN','ELEVEN','TWELVE','THIRTEEN','FOURTEEN','FIFTEEN','SIXTEEN','SEVENTEEN','EIGHTEEN','NINETEEN'];
var tensWords  = ['','','TWENTY','THIRTY','FORTY','FIFTY','SIXTY','SEVENTY','EIGHTY','NINETY'];

function yearToWords(y) {
    // Split year into two halves for natural reading: 1990 → NINETEEN NINETY, 2005 → TWO THOUSAND FIVE
    if (y >= 2000 && y <= 2009) {
        var o = y - 2000;
        return 'TWO THOUSAND' + (o > 0 ? ' ' + onesWords[o] : '');
    }
    if (y >= 2010 && y <= 2019) {
        return 'TWO THOUSAND ' + onesWords[y - 2000];
    }
    if (y >= 2020 && y <= 2099) {
        var secondHalf20 = y % 100;
        var t2 = Math.floor(secondHalf20 / 10);
        var o2 = secondHalf20 % 10;
        return 'TWENTY ' + tensWords[t2] + (o2 > 0 ? ' ' + onesWords[o2] : '');
    }
    // For years like 1900-1999: NINETEEN + second half
    var firstHalf  = Math.floor(y / 100);
    var secondHalf = y % 100;
    var firstStr = '';
    if (firstHalf < 20) { firstStr = onesWords[firstHalf]; }
    else { firstStr = tensWords[Math.floor(firstHalf/10)] + (firstHalf%10 > 0 ? ' ' + onesWords[firstHalf%10] : ''); }

    var secondStr = '';
    if (secondHalf === 0) { secondStr = 'HUNDRED'; }
    else if (secondHalf < 20) { secondStr = onesWords[secondHalf]; }
    else { secondStr = tensWords[Math.floor(secondHalf/10)] + (secondHalf%10 > 0 ? ' ' + onesWords[secondHalf%10] : ''); }

    return firstStr + ' ' + secondStr;
}

function convertDobToWords(dateInputId, textInputId) {
    var dateEl = document.getElementById(dateInputId);
    var textEl = document.getElementById(textInputId);
    if (!dateEl || !textEl || !dateEl.value) return;

    var parts = dateEl.value.split('-'); // YYYY-MM-DD
    var year  = parseInt(parts[0], 10);
    var month = parseInt(parts[1], 10);
    var day   = parseInt(parts[2], 10);

    var dayW   = dayWords[day]   || day;
    var monthW = monthWords[month] || month;
    var yearW  = yearToWords(year);

    var dd = String(day).padStart(2, '0');
    var mm = String(month).padStart(2, '0');

    textEl.value = dayW + ' ' + monthW + ' ' + yearW + ' / ' + dd + '/' + mm + '/' + year;
    sync();
}
// ═══════════════════════════════════════════════════════════════

var previewArea = document.querySelector('.preview-area');
if(previewArea){
    previewArea.addEventListener('copy',  function(e){ e.preventDefault(); });
    previewArea.addEventListener('paste', function(e){
        e.preventDefault();
        var text = (e.clipboardData || window.clipboardData).getData('text/plain');
        if(text) document.execCommand('insertText', false, text);
    });
}

function payAndPrint(){
    if(!document.getElementById('inp_old_dob').value.trim()){ Swal.fire({icon:'warning',title:'Required',text:'Please enter Old Date of Birth'}); return; }
    if(!document.getElementById('inp_new_dob').value.trim()){ Swal.fire({icon:'warning',title:'Required',text:'Please enter New Date of Birth'}); return; }

    const bal  = parseFloat('{{ $balance }}');
    const fee  = parseFloat('{{ $format->fee }}');
    if(bal < fee){ Swal.fire({icon:'error',title:'Insufficient Balance',text:`Wallet: ₹${bal} | Required: ₹${fee}`}); return; }

    Swal.fire({
        title: 'Confirm Payment',
        text: `₹${fee} will be deducted from wallet and document will be printed.`,
        icon: 'question', showCancelButton: true,
        confirmButtonText: 'Yes, Print!', cancelButtonText: 'Cancel'
    }).then(function(result){
        if(!result.isConfirmed) return;

        document.getElementById('watermark1').style.display = 'none';
        var btn = document.getElementById('payBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin w-4 h-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';

        fetch('{{ route("bonds.deductFee") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ slug: '{{ $format->slug }}' })
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';
            lucide.createIcons();

            if(data.status === 'success'){
                document.getElementById('walletBal').innerText = data.new_balance;

                // Inject into iframe for clean print
                var pFrame = document.getElementById('printFrame');
                var pDoc   = pFrame.contentWindow || pFrame.contentDocument.document || pFrame.contentDocument;

                var styles = `
                    @import url('https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&display=swap');
                    @page { size: A4; margin: 0; }
                    * { box-sizing: border-box; }
                    body { margin:0; padding:0; font-family:'Times New Roman','Noto Serif',serif; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
                    .bond-page { width:210mm; height:297mm; overflow:hidden; position:relative; margin:0; padding:0; background:white; }
                    .bond-watermark { display:none !important; }
                    .outer-border { position:absolute !important; top:24px; bottom:24px; left:24px; right:24px; border:3px solid #000 !important; pointer-events:none; z-index:40; }
                    .inner-border { position:absolute !important; top:31px; bottom:31px; left:31px; right:31px; border:1px solid #333 !important; pointer-events:none; z-index:41; }
                    .bond-content { position:relative; z-index:10; }
                    .emblem-wrap { display:flex; align-items:center; gap:18px; margin-bottom:10px; }
                    .emblem-img { width:72px; height:72px; object-fit:contain; flex-shrink:0; }
                    .dob-tbl { width:100%; border-collapse:collapse; margin:10px 0; }
                    .dob-tbl td { border:1.5px solid #222 !important; padding:6px 10px; font-size:0.93em; vertical-align:middle; }
                    .dob-tbl td.lbl { font-weight:700; background:#f5f5f5; width:110px; text-align:center; }
                    .note-box { border:1px solid #999; border-radius:3px; padding:7px 12px; background:#fafafa; margin-bottom:10px; }
                    .note-box p { font-size:0.87em; line-height:1.65; text-align:justify; }
                    .sig-row { display:flex; justify-content:space-between; align-items:flex-start; margin-top:18px; }
                    .font-bold, strong { font-weight:bold !important; }
                    .out-field.filled { font-weight:700; }
                `;

                var html = `<!DOCTYPE html><html><head><title>Birth Date Change Notice</title><style>${styles}</style></head><body>${document.getElementById('page1').outerHTML}</body></html>`;

                pDoc.document.open();
                pDoc.document.write(html);
                pDoc.document.close();
                setTimeout(function(){ pFrame.contentWindow.print(); }, 700);

            } else {
                Swal.fire({icon:'error', title:'Error', text: data.message});
                document.getElementById('watermark1').style.display = 'flex';
            }
        })
        .catch(function(err){
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';
            lucide.createIcons();
            Swal.fire({icon:'error', title:'Error', text:'Server error. Please try again.'});
            document.getElementById('watermark1').style.display = 'flex';
        });
    });
}
</script>
@endpush
