@extends('layouts.bond-maker')
@section('title', 'Duplicate T.C प्रतिज्ञापत्र — SETU Suvidha')

@push('styles')
<style>
    .sig-block { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 40px; margin-bottom: 20px; }
    .sig-block div { min-width: 180px; }
    .hr-line { border: none; border-top: 1.5px solid #333; margin: 15px 0; }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">Duplicate T.C</h2>
                    <p class="text-[10px] text-gray-400">दुय्यमप्रत टी.सी प्रतिज्ञापत्र</p>
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
                        <div><label class="field-label">धंदा</label><input type="text" id="inp_app_occ" oninput="sync()" placeholder="शिक्षण / शेती" class="form-input"></div>
                    </div>
                    <div><label class="field-label">रा. (गाव / ठिकाण)</label><input type="text" id="inp_app_addr" oninput="sync()" placeholder="उदा. पिंपरी" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">तालुका</label><input type="text" id="inp_app_tal" oninput="sync()" value="नांदगाव खंडेश्वर" class="form-input"></div>
                        <div><label class="field-label">जिल्हा</label><input type="text" id="inp_app_dist" oninput="sync()" value="अमरावती" class="form-input"></div>
                    </div>
                </div>
            </div>

            {{-- SEC 2: शाळा / कॉलेज तपशील --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">शाळा / कॉलेज तपशील</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">शाळा / कॉलेजचे नाव</label><input type="text" id="inp_school_name" oninput="sync()" placeholder="उदा. जि. प. हायस्कूल" class="form-input"></div>
                    <div><label class="field-label">शाळेचे ठिकाण (येथून)</label><input type="text" id="inp_school_place" oninput="sync()" placeholder="उदा. अमरावती" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">वर्ग (कोणत्या वर्गात)</label><input type="text" id="inp_class" oninput="sync()" placeholder="१० वी" class="form-input"></div>
                        <div><label class="field-label">सन / वर्ष</label><input type="text" id="inp_year" oninput="sync()" placeholder="२०१५-१६" class="form-input"></div>
                    </div>
                    <div><label class="field-label">कारण</label><input type="text" id="inp_reason" oninput="sync()" value="हरवली / गहाळ झाल्यामुळे" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 3: ठिकाण व दिनांक --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">ठिकाण व दिनांक</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">ठिकाण (स्थळ)</label><input type="text" id="inp_place" oninput="sync()" value="नांदगाव खंडेश्वर" class="form-input"></div>
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
    <div class="flex-1 overflow-y-auto bg-[#555] pt-4 pb-8 px-4 relative preview-area">

        {{-- ══════ A4 PAGE 1 ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 13.5pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            {{-- 40px Margin Border --}}
            <div class="absolute inset-0 border-[3px] border-gray-900 pointer-events-none z-40" style="margin: 40px;"></div>

            {{-- Watermark --}}
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            {{-- Editable Content Area --}}
            <div id="page1_content" class="bond-content" contenteditable="true" spellcheck="false" style="padding: 80px 60px 60px 60px;">

                {{-- Title --}}
                <div class="text-center mb-6">
                    <p class="font-bold text-[1.4rem] underline mb-1">प्रतिज्ञालेख</p>
                </div>

                {{-- Addressed To & Applicant Details --}}
                <div class="mb-4">
                    <p class="font-bold mb-1">प्रति,</p>
                    <p class="font-bold mb-1">तहसीलदार साहेब,</p>
                    <p class="font-bold mb-1">तहसील कार्यालय नांदगाव खंडेश्वर</p>
                    <p class="font-bold mb-4">यांचे सेवेशी ,</p>

                    <p class="mb-1 font-bold">प्रतिज्ञार्थी :- <span id="out_app_name" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                    <p class="mb-1 font-bold">वय :- <span id="out_app_age" class="out-field empty" style="font-weight:normal;">&nbsp;</span> वर्षे , &nbsp;&nbsp;&nbsp;धंदा :- <span id="out_app_occ" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                    <p class="mb-1 font-bold">रा. :- <span id="out_app_addr" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                    <p class="mb-1 font-bold">ता. <span id="out_app_tal" class="out-field filled font-normal">नांदगाव खंडेश्वर</span> जि. <span id="out_app_dist" class="out-field filled font-normal">अमरावती</span></p>
                </div>
                
                <hr class="hr-line">

                {{-- Subject --}}
                <p class="font-bold text-[1.2rem] mb-4 text-center underline">विषय :- Duplicate T.C मिळणेबाबत (दुय्यमप्रत टी.सी )</p>

                <p class="mb-2 font-bold">महोदय,</p>
                
                {{-- Body Paragraph --}}
                <p class="leading-[2.4] text-justify mb-8" style="text-indent: 40px;">
                    मी खालील सही करणार प्रतिज्ञालेख सादर करतो की, मी, मौजा <span id="out_app_addr2" class="out-field empty font-bold">&nbsp;</span> येथील रहिवासी आहे. माझे शिक्षण 
                    <strong><span id="out_school_name" class="out-field empty border-b border-dashed border-gray-400">&nbsp;</span></strong>, 
                    <span id="out_school_place" class="out-field empty font-bold">&nbsp;</span> येथून झालेले आहे. मी सन <span id="out_year" class="out-field empty font-bold">&nbsp;</span> मध्ये 
                    वर्ग <span id="out_class" class="out-field empty font-bold">&nbsp;</span> मध्ये शिकत होतो. परंतु मिळालेली मूळ टी.सी. (Transfer Certificate) 
                    <span id="out_reason" class="out-field filled font-bold">हरवली / गहाळ झाल्यामुळे</span> मला 
                    संबंधित शाळेतून/कॉलेजमधून वर्ग <span id="out_class2" class="out-field empty font-bold">&nbsp;</span> ची दुय्यमप्रत (Duplicate T.C) मिळावी, या सेवेसी प्रतिज्ञालेख सादर करत आहे.
                </p>

                {{-- Signature Block 1 --}}
                <div class="sig-block text-[1.1em] font-bold">
                    <div>
                        <p class="mb-2">दिनांक :- <span id="out_date" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                        <p class="mb-0">स्थळ :- <span id="out_place" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                    </div>
                    <div class="text-center">
                        <p class="mb-14 text-transparent select-none">.</p>
                        <p class="mb-0 border-t border-gray-600 pt-1 inline-block px-4">प्रतिज्ञार्थीची सही</p>
                    </div>
                </div>

                <hr class="hr-line">

                {{-- Verification --}}
                <p class="font-bold text-center text-[1.2rem] mb-3 underline">सत्यापन</p>
                <p class="leading-[2.2] text-justify mb-6">
                    मी वरील प्रतिज्ञापत्रावर लिहून दिलेली माहिती खरी आहे. खोटी माहिती आढळून आल्यास मी भा.दं.वि. १९९, २००, व १९३(२) नुसार शिक्षेस पात्र राहील. ही कोणास समक्ष मी सही करत आहे.
                </p>

                {{-- Signature Block 2 --}}
                <div class="sig-block text-[1.1em] font-bold">
                    <div>
                        <p class="mb-2">दिनांक :- <span id="out_date2" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                        <p class="mb-0">स्थळ :- <span id="out_place2" class="out-field empty" style="font-weight:normal;">&nbsp;</span></p>
                    </div>
                    <div class="text-center">
                        <p class="mb-14 text-transparent select-none">.</p>
                        <p class="mb-0 border-t border-gray-600 pt-1 inline-block px-4">प्रतिज्ञार्थीची सही</p>
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
            <div><p class="font-bold text-gray-800 mb-1">1. तपशील भरा:</p><p>डावीकडे प्रतिज्ञार्थी व शाळेचे/कॉलेजचे तपशील भरा.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. अलाइनमेंट:</p><p>हे प्रमाणपत्र A4 पानावर बॉर्डरसह व्यवस्थित अलाइन केले आहे, हे साध्या A4 पेपरवर प्रिंट करण्यासाठी डिझाईन केले आहे.</p></div>
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

var dateFields=['inp_date'];
function fmtDMY(v){if(!v)return '';var d=new Date(v);return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();}

var syncMap={
    'inp_app_name':['out_app_name'],
    'inp_app_age':['out_app_age'],
    'inp_app_occ':['out_app_occ'],
    'inp_app_addr':['out_app_addr', 'out_app_addr2'],
    'inp_app_tal':['out_app_tal'],
    'inp_app_dist':['out_app_dist'],
    'inp_school_name':['out_school_name'],
    'inp_school_place':['out_school_place'],
    'inp_class':['out_class', 'out_class2'],
    'inp_year':['out_year'],
    'inp_reason':['out_reason'],
    'inp_place':['out_place','out_place2'],
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
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for Duplicate T.C प्रतिज्ञापत्र?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'duplicate-tc-affidavit'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
