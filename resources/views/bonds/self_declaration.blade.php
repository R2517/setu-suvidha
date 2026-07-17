@extends('layouts.bond-maker')
@section('title', 'स्वयंघोषणापत्र (प्रपत्र–अ) — SETU Suvidha')

@push('styles')
<style>
    .sig-block { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 40px; }
    .sig-block div { min-width: 180px; }
    .photo-box {
        width: 120px; height: 140px; border: 2px solid #333;
        display: flex; align-items: center; justify-content: center;
        font-size: 11pt; color: #888; text-align: center;
        background: #fff; overflow: hidden; position: relative;
    }
    .photo-box img { width: 100%; height: 100%; object-fit: cover; }
    .sign-box {
        width: 180px; height: 60px; border-bottom: 1.5px solid #333;
        display: flex; align-items: flex-end; justify-content: center;
        overflow: hidden; position: relative;
    }
    .sign-box img { max-width: 100%; max-height: 55px; object-fit: contain; }

    /* Upload buttons */
    .upload-area {
        border: 2px dashed #c7d2fe; border-radius: 8px; padding: 8px;
        text-align: center; cursor: pointer; transition: all 0.2s;
        background: #f8f9ff;
    }
    .upload-area:hover { border-color: #6366f1; background: #eef2ff; }
    .upload-area img { max-width: 100%; max-height: 80px; object-fit: contain; border-radius: 4px; }
    .upload-btns { display: flex; gap: 4px; margin-top: 4px; }
    .upload-btns button {
        flex: 1; padding: 4px 6px; font-size: 10px; font-weight: 600;
        border: 1px solid #ddd; border-radius: 4px; cursor: pointer;
        background: #fff; color: #333; transition: all 0.15s;
    }
    .upload-btns button:hover { background: #4f46e5; color: #fff; border-color: #4f46e5; }

    @media print {
        .bond-page { page-break-after: always; }
        .bond-page:last-child { page-break-after: auto; }
        .photo-box { border: 2px solid #333 !important; }
        .out-field.filled { font-weight: 700 !important; }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">स्वयंघोषणापत्र</h2>
                    <p class="text-[10px] text-gray-400">प्रपत्र–अ</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 0: Photo & Sign Upload --}}
            <div class="section-card">
                <div class="section-header"><span class="num">📷</span><span class="title">फोटो व सही</span></div>
                <div class="section-body grid grid-cols-2 gap-2.5">
                    {{-- Photo --}}
                    <div>
                        <label class="field-label">अर्जदाराचा फोटो</label>
                        <div class="upload-area" id="photoArea" onclick="document.getElementById('photoInput').click()">
                            <div id="photoPreviewSmall" style="font-size:10px;color:#999;">
                                <i data-lucide="camera" class="w-5 h-5 mx-auto mb-1 text-indigo-400"></i>
                                <div>फोटो निवडा</div>
                            </div>
                        </div>
                        <input type="file" id="photoInput" accept="image/*" class="hidden" onchange="handlePhoto(event)">
                        <div class="upload-btns">
                            <button type="button" onclick="document.getElementById('photoInput').click()">📁 Upload</button>
                            <button type="button" onclick="capturePhoto()">📸 Capture</button>
                        </div>
                    </div>
                    {{-- Signature --}}
                    <div>
                        <label class="field-label">अर्जदाराची सही</label>
                        <div class="upload-area" id="signArea" onclick="document.getElementById('signInput').click()">
                            <div id="signPreviewSmall" style="font-size:10px;color:#999;">
                                <i data-lucide="pen-tool" class="w-5 h-5 mx-auto mb-1 text-indigo-400"></i>
                                <div>सही निवडा</div>
                            </div>
                        </div>
                        <input type="file" id="signInput" accept="image/*" class="hidden" onchange="handleSign(event)">
                        <div class="upload-btns">
                            <button type="button" onclick="document.getElementById('signInput').click()">📁 Upload</button>
                            <button type="button" onclick="clearSign()">🗑️ Clear</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEC 1: अर्जदार माहिती --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">अर्जदार माहिती</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">पूर्ण नाव</label><input type="text" id="inp_name" oninput="sync()" placeholder="पूर्ण नाव" class="form-input"></div>
                    <div><label class="field-label">श्री./श्रीमती (वडिलांचे/पतीचे नाव)</label><input type="text" id="inp_father" oninput="sync()" placeholder="वडिलांचे नाव" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">वय</label><input type="text" id="inp_age" oninput="sync()" placeholder="वय" class="form-input"></div>
                        <div><label class="field-label">मुलगा / मुलगी</label>
                            <select id="inp_relation" onchange="sync()" class="form-select">
                                <option value="यांचा मुलगा">यांचा मुलगा</option>
                                <option value="यांची मुलगी">यांची मुलगी</option>
                                <option value="यांची पत्नी">यांची पत्नी</option>
                            </select>
                        </div>
                    </div>
                    <div><label class="field-label">आधार क्रमांक (असल्यास)</label><input type="text" id="inp_aadhar" oninput="sync()" placeholder="आधार क्रमांक" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">व्यवसाय</label><input type="text" id="inp_occ" oninput="sync()" placeholder="व्यवसाय" class="form-input"></div>
                        <div><label class="field-label">राहणार (पत्ता)</label><input type="text" id="inp_addr" oninput="sync()" placeholder="पत्ता" class="form-input"></div>
                    </div>
                </div>
            </div>

            {{-- SEC 2: ठिकाण व दिनांक --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">ठिकाण व दिनांक</span></div>
                <div class="section-body grid grid-cols-2 gap-2">
                    <div><label class="field-label">ठिकाण</label><input type="text" id="inp_place" oninput="sync()" placeholder="ठिकाण" class="form-input"></div>
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

    {{-- ═══════════════════ RIGHT PANEL — A4 PREVIEW ═══════════════════ --}}
    <div class="flex-1 overflow-y-auto bg-[#555] pt-4 pb-8 px-4 relative preview-area">

        {{-- ══════ A4 PAGE 1 (Single Page) ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl"
             style="min-height: 1123px; font-size: 13pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif; line-height: 2;">

            {{-- Border --}}
            <div class="absolute inset-0 border-[3px] border-gray-900 pointer-events-none z-40" style="margin: 35px;"></div>

            {{-- Watermark --}}
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            {{-- Editable Content --}}
            <div id="page1_content" class="bond-content" contenteditable="true" spellcheck="false"
                 style="padding: 55px 55px 50px 55px;">

                {{-- GR Number --}}
                <p class="text-[11pt] mb-1" style="line-height:1.4;">
                    <u>शासन निर्णय क्रमांक: प्रसुधा १६.१४/३४५/प्र.क्र.०१/१८–अ</u>
                </p>

                {{-- Title + Photo --}}
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 15px; margin-bottom: 10px;">
                    <div style="flex: 1; text-align: center; padding-top: 10px;">
                        <p class="font-bold text-[1.3rem] mb-1">प्रपत्र–अ</p>
                        <p class="font-bold text-[1.4rem] underline">स्वयंघोषणापत्र</p>
                    </div>
                    <div class="photo-box" id="photoBoxPreview">
                        <span style="font-size:10pt;">अर्जदाराचा<br>फोटो</span>
                    </div>
                </div>

                {{-- Main Body --}}
                <p style="line-height: 2.4; text-align: justify; margin-top: 20px;">
                    मी<span style="display:inline-block;min-width:200px;border-bottom:1.5px dashed #555;text-align:center;"><span id="out_name" class="out-field empty font-bold">&nbsp;</span></span>श्री.
                    <span style="display:inline-block;min-width:200px;border-bottom:1.5px dashed #555;text-align:center;"><span id="out_father" class="out-field empty font-bold">&nbsp;</span></span><span id="out_relation" class="out-field filled font-bold">यांचा मुलगा</span> / मुलगी वय<span style="display:inline-block;min-width:40px;border-bottom:1.5px dashed #555;text-align:center;"><span id="out_age" class="out-field empty font-bold">&nbsp;</span></span>वर्ष, आधार क्रमांक (असल्यास)
                    <span style="display:inline-block;min-width:250px;border-bottom:1.5px dashed #555;text-align:center;"><span id="out_aadhar" class="out-field empty font-bold">&nbsp;</span></span>व्यवसाय<span style="display:inline-block;min-width:120px;border-bottom:1.5px dashed #555;text-align:center;"><span id="out_occ" class="out-field empty font-bold">&nbsp;</span></span>
                </p>

                <p style="line-height: 2.4; text-align: justify;">
                    राहणार<span style="display:inline-block;min-width:300px;border-bottom:1.5px dashed #555;text-align:center;"><span id="out_addr" class="out-field empty font-bold">&nbsp;</span></span> याद्वारे घोषित करतो / करते की, वरील सर्व माहिती माझ्या व्यक्तीगत माहिती व समजूतीनुसार खरी आहे. सदर माहिती खोटी आढळून आल्यास, भारतीय दंड संहिता अन्वये आणि / किंवा संबंधित कायदयानुसार माझ्यावर खटला भरला जाईल व त्यानुसार मी शिक्षेस पात्र राहीन याची मला पूर्ण जाणीव आहे.
                </p>

                {{-- Signature Block --}}
                <div class="sig-block" style="margin-top: 60px;">
                    <div>
                        <p class="font-bold mb-3">ठिकाण :-<span style="display:inline-block;min-width:120px;border-bottom:1px solid #555;margin-left:5px;"><span id="out_place" class="out-field empty" style="font-weight:normal;">&nbsp;</span></span></p>
                        <p class="font-bold">दिनांक :-<span style="display:inline-block;min-width:120px;border-bottom:1px solid #555;margin-left:5px;"><span id="out_date" class="out-field empty" style="font-weight:normal;">&nbsp;</span></span></p>
                    </div>
                    <div class="text-center">
                        <div class="sign-box" id="signBoxPreview" style="margin: 0 auto;">
                            {{-- Sign image will appear here --}}
                        </div>
                        <p class="font-bold mt-1">अर्जदाराची सही</p>
                        <p style="border-top:1px solid #555;display:inline-block;padding-top:3px;min-width:180px;margin-top:25px;">
                            <span class="font-bold">अर्जदाराचे नाव :-</span><span id="out_name2" class="out-field empty" style="font-weight:normal;">&nbsp;</span>
                        </p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Camera Modal --}}
<div id="cameraModal" class="fixed inset-0 bg-black/70 z-50 items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-4">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-sm font-bold text-gray-800">📸 फोटो कॅप्चर करा</h3>
            <button onclick="closeCameraModal()" class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>
        <video id="cameraVideo" autoplay playsinline class="w-full rounded-lg bg-black" style="max-height:300px;"></video>
        <p id="camStatus" class="text-center text-sm text-indigo-600 font-bold mt-2" style="display:none;"></p>
        <div class="flex gap-2 mt-3">
            <button onclick="snapPhoto()" class="flex-1 bg-indigo-600 text-white py-2 rounded-lg text-sm font-bold hover:bg-indigo-700">📸 कॅप्चर</button>
            <button onclick="closeCameraModal()" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg text-sm font-bold hover:bg-gray-300">रद्द करा</button>
        </div>
        <canvas id="cameraCanvas" class="hidden"></canvas>
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
            <div><p class="font-bold text-gray-800 mb-1">1. फोटो अपलोड:</p><p>अर्जदाराचा फोटो अपलोड करा किंवा कॅमेऱ्याने कॅप्चर करा.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. सही अपलोड:</p><p>अर्जदाराची सही अपलोड करा (PNG/JPG).</p></div>
            <div><p class="font-bold text-gray-800 mb-1">3. माहिती भरा:</p><p>डावीकडील फॉर्ममध्ये सर्व माहिती भरा, उजवीकडे live preview दिसेल.</p></div>
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
    'inp_name':['out_name','out_name2'],
    'inp_father':['out_father'],
    'inp_relation':['out_relation'],
    'inp_age':['out_age'],
    'inp_aadhar':['out_aadhar'],
    'inp_occ':['out_occ'],
    'inp_addr':['out_addr'],
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

/* ── Photo Upload/Capture ── */
function handlePhoto(e){
    var file=e.target.files[0];if(!file)return;
    var reader=new FileReader();
    reader.onload=function(ev){
        setPhotoImage(ev.target.result);
    };
    reader.readAsDataURL(file);
}

function setPhotoImage(src){
    // Small preview in form
    document.getElementById('photoPreviewSmall').innerHTML='<img src="'+src+'" style="max-height:80px;border-radius:4px;">';
    // Preview in A4
    document.getElementById('photoBoxPreview').innerHTML='<img src="'+src+'">';
}

// Camera capture — always try live camera first
var cameraStream=null;
function capturePhoto(){
    if(!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia){
        // Browser doesn't support camera API — use file picker
        openCameraFilePicker();
        return;
    }
    // Open modal first, then request camera
    var modal=document.getElementById('cameraModal');
    modal.classList.remove('hidden');modal.classList.add('flex');
    document.getElementById('camStatus').style.display='block';
    document.getElementById('camStatus').innerText='कॅमेरा सुरू होत आहे...';
    var video=document.getElementById('cameraVideo');

    navigator.mediaDevices.getUserMedia({video:{facingMode:'user',width:{ideal:640},height:{ideal:480}}})
    .then(function(stream){
        cameraStream=stream;
        video.srcObject=stream;
        document.getElementById('camStatus').style.display='none';
    })
    .catch(function(err){
        closeCameraModal();
        if(err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError'){
            var msg = '⚠️ कॅमेरा परवानगी नाकारली गेली!\n\n';
            msg += '🔧 Fix करण्यासाठी:\n';
            msg += '1. Address bar मध्ये 🔒 (lock icon) वर क्लिक करा\n';
            msg += '2. "Site settings" वर क्लिक करा\n';
            msg += '3. Camera → "Allow" निवडा\n';
            msg += '4. Page refresh करा (Ctrl+Shift+R)\n\n';
            msg += 'किंवा OK दाबा → फोटो file मधून निवडा.';
            if(confirm(msg)){ openCameraFilePicker(); }
        } else if(err.name === 'NotFoundError'){
            alert('❌ कॅमेरा सापडला नाही!\n\nतुमच्या device ला कॅमेरा नाही.\n"Upload" बटण वापरा.');
        } else {
            alert('कॅमेरा त्रुटी: '+err.message+'\n\nकृपया "Upload" बटण वापरा.');
        }
    });
}

function openCameraFilePicker(){
    var inp = document.createElement('input');
    inp.type='file'; inp.accept='image/*';
    inp.setAttribute('capture','user');
    inp.onchange=function(e){ handlePhoto(e); };
    inp.click();
}

function snapPhoto(){
    var video=document.getElementById('cameraVideo');
    var canvas=document.getElementById('cameraCanvas');
    canvas.width=video.videoWidth;canvas.height=video.videoHeight;
    canvas.getContext('2d').drawImage(video,0,0);
    var dataUrl=canvas.toDataURL('image/jpeg',0.85);
    setPhotoImage(dataUrl);
    closeCameraModal();
}

function closeCameraModal(){
    var modal=document.getElementById('cameraModal');
    modal.classList.add('hidden');modal.classList.remove('flex');
    if(cameraStream){cameraStream.getTracks().forEach(function(t){t.stop();});cameraStream=null;}
}

/* ── Signature Upload ── */
function handleSign(e){
    var file=e.target.files[0];if(!file)return;
    var reader=new FileReader();
    reader.onload=function(ev){
        document.getElementById('signPreviewSmall').innerHTML='<img src="'+ev.target.result+'" style="max-height:50px;">';
        document.getElementById('signBoxPreview').innerHTML='<img src="'+ev.target.result+'">';
    };
    reader.readAsDataURL(file);
}

function clearSign(){
    document.getElementById('signPreviewSmall').innerHTML='<i data-lucide="pen-tool" class="w-5 h-5 mx-auto mb-1 text-indigo-400"></i><div>सही निवडा</div>';
    document.getElementById('signBoxPreview').innerHTML='';
    document.getElementById('signInput').value='';
    lucide.createIcons();
}

/* ── Init ── */
window.addEventListener('DOMContentLoaded',function(){
    sync();
    lucide.createIcons();
    // Set today's date
    var today=new Date();var dd=String(today.getDate()).padStart(2,'0');var mm=String(today.getMonth()+1).padStart(2,'0');var yy=today.getFullYear();
    document.getElementById('inp_date').value=yy+'-'+mm+'-'+dd;
    sync();
});

var previewArea=document.querySelector('.preview-area');
if(previewArea){
    previewArea.addEventListener('copy',function(e){e.preventDefault();});
    previewArea.addEventListener('paste',function(e){e.preventDefault();var text=(e.clipboardData||window.clipboardData).getData('text/plain');if(text)document.execCommand('insertText',false,text);});
}

function payAndPrint(){
    if(!document.getElementById('inp_name').value.trim()){alert('कृपया अर्जदाराचे नाव टाका');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for स्वयंघोषणापत्र?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'self-declaration'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
