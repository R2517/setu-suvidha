@extends('layouts.bond-maker')
@section('title', '९० दिवस काम केल्याचे प्रमाणपत्र (बांधकाम कामगार) — SETU Suvidha')

@push('styles')
<style>
    .sig-block { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 40px; }
    .sig-block div { min-width: 180px; }
    .photo-box {
        width: 120px; height: 140px; border: 2px solid #333;
        display: flex; align-items: center; justify-content: center;
        font-size: 11pt; color: #888; text-align: center;
        background: #fff; overflow: hidden; position: relative;
        flex-direction: column;
    }
    .photo-box img { width: 100%; height: 100%; object-fit: cover; }
    .photo-box p { font-size: 9pt; margin-top: 5px; line-height: 1.2; padding: 0 5px; }
    
    table.work-table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 11pt; }
    table.work-table th, table.work-table td { border: 1.5px solid #333; padding: 4px 6px; text-align: left; vertical-align: middle; height: auto;}
    table.work-table th.center { text-align: center; font-weight: bold; }
    table.work-table td.center { text-align: center; }

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
        .bond-page { page-break-after: always; box-sizing: border-box; }
        .bond-page:last-child { page-break-after: auto; }
        .photo-box { border: 2px solid #333 !important; }
        .out-field.filled { font-weight: 700 !important; }
        table.work-table th, table.work-table td { border: 1.5px solid #333 !important; }
        .print-border { border: 3px solid #000 !important; }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">९० दिवस काम प्रमाणपत्र</h2>
                    <p class="text-[10px] text-gray-400">बांधकाम कामगार</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 0: Photo --}}
            <div class="section-card">
                <div class="section-header"><span class="num">📷</span><span class="title">कामगाराचा फोटो</span></div>
                <div class="section-body">
                    <div>
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
                </div>
            </div>

            {{-- SEC 1: कार्यालयीन माहिती --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">कार्यालयीन माहिती</span></div>
                <div class="section-body space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">जावक क्रमांक</label><input type="text" id="inp_outward_no" oninput="sync()" placeholder="क्रमांक" class="form-input"></div>
                        <div><label class="field-label">जावक दिनांक</label><input type="date" id="inp_outward_date" oninput="sync()" class="form-input"></div>
                    </div>
                    <div><label class="field-label">कार्यालयाचे नाव (ग्रामपंचायत/महानगरपालिका)</label><input type="text" id="inp_office_name" oninput="sync()" placeholder="कार्यालयाचे नाव" class="form-input"></div>
                    <div><label class="field-label">अधिकाऱ्याचे नाव</label><input type="text" id="inp_officer_name" oninput="sync()" placeholder="अधिकाऱ्याचे नाव" class="form-input"></div>
                    <div><label class="field-label">पद</label><input type="text" id="inp_designation" oninput="sync()" placeholder="पद" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">तालुका</label><input type="text" id="inp_office_taluka" oninput="sync()" placeholder="तालुका" class="form-input"></div>
                        <div><label class="field-label">पिन कोड</label><input type="text" id="inp_office_pincode" oninput="sync()" placeholder="पिन कोड" class="form-input"></div>
                    </div>
                </div>
            </div>

            {{-- SEC 2: कामगाराची वैयक्तिक माहिती --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">कामगाराची वैयक्तिक माहिती</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">कामगाराचे पूर्ण नाव</label><input type="text" id="inp_worker_name" oninput="sync()" placeholder="पूर्ण नाव" class="form-input"></div>
                    <div><label class="field-label">संपूर्ण पत्ता</label><input type="text" id="inp_worker_addr" oninput="sync()" placeholder="पत्ता" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">वय (वर्षे)</label><input type="number" id="inp_worker_age" oninput="sync()" placeholder="वय" class="form-input"></div>
                        <div><label class="field-label">गाव</label><input type="text" id="inp_worker_village" oninput="sync()" placeholder="गाव" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">तालुका</label><input type="text" id="inp_worker_taluka" oninput="sync()" placeholder="तालुका" class="form-input"></div>
                        <div><label class="field-label">जिल्हा</label><input type="text" id="inp_worker_district" oninput="sync()" placeholder="जिल्हा" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">पिन कोड</label><input type="text" id="inp_worker_pincode" oninput="sync()" placeholder="पिन कोड" class="form-input"></div>
                        <div><label class="field-label">संपर्क क्रमांक</label><input type="text" id="inp_worker_mobile" oninput="sync()" placeholder="मोबाईल" class="form-input"></div>
                    </div>
                </div>
            </div>

            {{-- SEC 3: कामाचा तपशील --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">सध्याच्या कामाचा तपशील</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">कामाचा प्रकार/स्वरूप</label><input type="text" id="inp_work_type" oninput="sync()" placeholder="उदा. मजुरी, गवंडीकाम" class="form-input"></div>
                    <div><label class="field-label">सध्याच्या कामाचे ठिकाण व पत्ता</label><input type="text" id="inp_work_place" oninput="sync()" placeholder="कामाचे ठिकाण" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">गाव</label><input type="text" id="inp_work_village" oninput="sync()" placeholder="गाव" class="form-input"></div>
                        <div><label class="field-label">तालुका</label><input type="text" id="inp_work_taluka" oninput="sync()" placeholder="तालुका" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">जिल्हा</label><input type="text" id="inp_work_district" oninput="sync()" placeholder="जिल्हा" class="form-input"></div>
                        <div><label class="field-label">पिन कोड</label><input type="text" id="inp_work_pincode" oninput="sync()" placeholder="पिन कोड" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">नियुक्ती दिनांक</label><input type="date" id="inp_appt_date" oninput="sync()" class="form-input"></div>
                        <div><label class="field-label">दिवसाचे वेतन (₹)</label><input type="text" id="inp_daily_wage" oninput="sync()" placeholder="उदा. ५००" class="form-input"></div>
                    </div>
                </div>
            </div>

            {{-- SEC 4: ९० दिवस कामाचा तपशील (Table) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">मागील वर्षातील तपशील (90 दिवस)</span></div>
                <div class="section-body space-y-3">
                    @for($i=1; $i<=4; $i++)
                    <div class="p-2 border border-indigo-100 rounded bg-white">
                        <div class="text-[10px] font-bold text-indigo-400 mb-2 uppercase">Record {{ $i }}</div>
                        <div class="space-y-2">
                            <input type="text" id="inp_t_name_{{ $i }}" oninput="sync()" placeholder="नियोक्त्याचे/मालकाचे नाव" class="form-input text-xs">
                            <input type="text" id="inp_t_addr_{{ $i }}" oninput="sync()" placeholder="पत्ता" class="form-input text-xs">
                            <input type="text" id="inp_t_mob_{{ $i }}" oninput="sync()" placeholder="मोबाईल" class="form-input text-xs">
                            <div class="grid grid-cols-2 gap-2">
                                <div><label class="text-[9px] text-gray-500">पासून</label><input type="date" id="inp_t_from_{{ $i }}" oninput="sync()" class="form-input text-xs" title="पासून"></div>
                                <div><label class="text-[9px] text-gray-500">पर्यंत</label><input type="date" id="inp_t_to_{{ $i }}" oninput="sync()" class="form-input text-xs" title="पर्यंत"></div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            {{-- Wallet & Pay Button --}}
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

    {{-- ═══════════════════ RIGHT PANEL — A4 PREVIEW ═══════════════════ --}}
    <div class="flex-1 overflow-y-auto bg-[#555] pt-4 pb-8 px-4 relative preview-area">

        {{-- ══════ A4 PAGE 1 (Single Page) ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl"
             style="height: 1122px; overflow: hidden; font-size: 11.5pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif; line-height: 1.35; box-sizing: border-box;">

            {{-- Border --}}
            <div class="print-border absolute pointer-events-none z-40" style="position: absolute; pointer-events: none; z-index: 40; top: 25px; bottom: 25px; left: 25px; right: 25px; border: 3px solid #000;"></div>

            {{-- Watermark --}}
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            {{-- Editable Content --}}
            <div id="page1_content" class="bond-content" contenteditable="true" spellcheck="false"
                 style="padding: 28px 42px 15px 42px; position: relative; z-index: 10;">

                {{-- Header Title --}}
                <div style="text-align: center; margin-bottom: 8px; margin-top: 6px;">
                    <p class="font-bold" style="font-size:13pt; line-height:1.3;">ग्रामपंचायत/महानगरपालिका/नगरपंचायत/नगरपालिका/नगरपरिषद यांच्या मार्फत बांधकाम कामगारास</p>
                    <p class="font-bold" style="font-size:12pt; line-height:1.3; margin-top:3px;">मागील वर्षात 90 दिवस किंवा अधिक दिवस काम केल्याचे प्रमाणपत्र</p>
                </div>

                {{-- Top Details --}}
                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                    <div style="width: 50%;">
                        जावक क्रमांक : <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_outward_no" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                    <div style="width: 50%; text-align: right;">
                        जावक दिनांक : <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_outward_date" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                </div>

                {{-- Photo Box Float Right (No wrapping) --}}
                <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
                    <div class="photo-box" id="photoBoxPreview" style="width: 105px; height: 130px;">
                        <span>पासपोर्ट साईज<br>फोटो</span>
                        <p style="position: absolute; bottom: 5px; width: 100%; font-weight: bold; font-size: 7.5pt; color: #000; line-height:1.2;">(प्राधिकृत<br>अधिकाऱ्यांमार्फत<br>साक्षांकित)</p>
                    </div>
                </div>

                {{-- Office Details --}}
                <div style="margin-bottom: 6px;">
                    <div style="margin-bottom: 6px;">
                        ग्रामपंचायत/महानगरपालिका/नगरपंचायत/नगरपालिका/नगरपरिषदेचे नाव: 
                        <span style="display:inline-block;min-width:250px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_office_name" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                    <div style="margin-bottom: 6px;">
                        अधिकाऱ्याचे नाव: 
                        <span style="display:inline-block;min-width:350px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_officer_name" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                    <div style="margin-bottom: 6px;">
                        पद: <span style="display:inline-block;min-width:350px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_designation" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                    <div style="margin-bottom: 6px;">
                        तालुका: <span style="display:inline-block;min-width:250px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_office_taluka" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                    <div style="margin-bottom: 6px;">
                        पिन कोड: <span style="display:inline-block;min-width:200px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_office_pincode" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                </div>

                <div style="clear: both;"></div>

                {{-- Paragraph --}}
                <p style="text-align: justify; text-indent: 30px; margin-bottom: 6px; line-height: 1.4; font-size: 10.5pt;">
                    इमारत व इतर बांधकाम कामगार (रोजगार विनियमन व सेवा शर्ती) अधिनियम, 1996 व महाराष्ट्र इमारत व इतर बांधकाम कामगार (रोजगार विनियमन व सेवा शर्ती) नियम 2007 अन्वये बांधकाम कामगार लाभार्थी म्हणून खाली नमूद बांधकाम कामगारास नाका बांधकाम कामगार म्हणून नोंदणी करण्यासाठी 90 दिवस काम केल्याचे प्रमाणपत्र देण्यात येत आहे.
                </p>

                {{-- Worker Details --}}
                <div style="margin-bottom: 5px;">
                    <div style="margin-bottom: 6px;">
                        बांधकाम कामगारांचे नाव: <span style="display:inline-block;min-width:400px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_worker_name" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                    <div style="margin-bottom: 6px;">
                        कामगाराचा पत्ता : <span style="display:inline-block;min-width:380px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_worker_addr" class="out-field empty font-bold">&nbsp;</span></span>
                        वय: <span style="display:inline-block;min-width:40px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_worker_age" class="out-field empty font-bold">&nbsp;</span></span> वर्ष
                    </div>
                    <div style="margin-bottom: 6px; display: flex; flex-wrap: wrap; gap: 8px;">
                        <div>गाव: <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_worker_village" class="out-field empty font-bold">&nbsp;</span></span></div>
                        <div>तालुका: <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_worker_taluka" class="out-field empty font-bold">&nbsp;</span></span></div>
                        <div>जिल्हा: <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_worker_district" class="out-field empty font-bold">&nbsp;</span></span></div>
                    </div>
                    <div style="margin-bottom: 6px; display: flex; flex-wrap: wrap; gap: 8px;">
                        <div>पिन कोड: <span style="display:inline-block;min-width:120px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_worker_pincode" class="out-field empty font-bold">&nbsp;</span></span></div>
                        <div>संपर्क क्रमांक: <span style="display:inline-block;min-width:180px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_worker_mobile" class="out-field empty font-bold">&nbsp;</span></span></div>
                    </div>
                    <div style="margin-bottom: 6px;">
                        कामाचा प्रकार/स्वरूप : <span style="display:inline-block;min-width:350px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_work_type" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                </div>

                {{-- Current Work Details --}}
                <div style="margin-bottom: 5px;">
                    <div style="margin-bottom: 6px; font-weight: bold;">
                        कामगाराच्या सध्याच्या कामाचे ठिकाण व पत्ता :- <span style="display:inline-block;min-width:350px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_work_place" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                    <div style="margin-bottom: 6px; display: flex; flex-wrap: wrap; gap: 8px;">
                        <div>गाव: <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_work_village" class="out-field empty font-bold">&nbsp;</span></span></div>
                        <div>तालुका: <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_work_taluka" class="out-field empty font-bold">&nbsp;</span></span></div>
                        <div>जिल्हा: <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_work_district" class="out-field empty font-bold">&nbsp;</span></span></div>
                    </div>
                    <div style="margin-bottom: 6px;">
                        पिन कोड : <span style="display:inline-block;min-width:120px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_work_pincode" class="out-field empty font-bold">&nbsp;</span></span>
                        नमूद बांधकाम कामगाराची नियुक्ती दि. <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_appt_date" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                    <div style="margin-bottom: 6px;">
                        दिवसाचे वेतन : <span style="display:inline-block;min-width:150px;border-bottom:1.5px dotted #111;text-align:center;"><span id="out_daily_wage" class="out-field empty font-bold">&nbsp;</span></span>
                    </div>
                </div>

                {{-- Table --}}
                <div class="font-bold mb-1 mt-2">मागील वर्षात बांधकाम कामगाराने 90 दिवस किंवा त्यापेक्षा अधिक दिवस काम केल्याचा तपशील :-</div>
                <table class="work-table" style="font-size: 11.5pt;">
                    <thead>
                        <tr>
                            <th class="center" colspan="2" style="width:30%;"></th>
                            <th class="center" style="width:17.5%;">1</th>
                            <th class="center" style="width:17.5%;">2</th>
                            <th class="center" style="width:17.5%;">3</th>
                            <th class="center" style="width:17.5%;">4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="font-bold" colspan="2">नियोक्त्याचे/ठेकेदाराचे/विकासकाचे<br>/मालकाचे नाव</td>
                            <td class="center"><span id="out_t_name_1"></span></td>
                            <td class="center"><span id="out_t_name_2"></span></td>
                            <td class="center"><span id="out_t_name_3"></span></td>
                            <td class="center"><span id="out_t_name_4"></span></td>
                        </tr>
                        <tr>
                            <td class="font-bold" colspan="2">नियोक्त्याचा/ठेकेदाराचा/<br>विकासकाचा/मालकाचा पत्ता</td>
                            <td class="center"><span id="out_t_addr_1"></span></td>
                            <td class="center"><span id="out_t_addr_2"></span></td>
                            <td class="center"><span id="out_t_addr_3"></span></td>
                            <td class="center"><span id="out_t_addr_4"></span></td>
                        </tr>
                        <tr>
                            <td class="font-bold" colspan="2">मोबाईल/भ्रमणध्वनी क्रमांक</td>
                            <td class="center"><span id="out_t_mob_1"></span></td>
                            <td class="center"><span id="out_t_mob_2"></span></td>
                            <td class="center"><span id="out_t_mob_3"></span></td>
                            <td class="center"><span id="out_t_mob_4"></span></td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="font-bold" style="width:12%;">कामाचा<br>कालावधी</td>
                            <td class="font-bold border-l-0" style="width:18%; padding-left:5px;">या तारखेपासून</td>
                            <td class="center"><span id="out_t_from_1"></span></td>
                            <td class="center"><span id="out_t_from_2"></span></td>
                            <td class="center"><span id="out_t_from_3"></span></td>
                            <td class="center"><span id="out_t_from_4"></span></td>
                        </tr>
                        <tr>
                            <td class="font-bold border-l-0" style="padding-left:5px;">तारखेपर्यंत</td>
                            <td class="center"><span id="out_t_to_1"></span></td>
                            <td class="center"><span id="out_t_to_2"></span></td>
                            <td class="center"><span id="out_t_to_3"></span></td>
                            <td class="center"><span id="out_t_to_4"></span></td>
                        </tr>
                        <tr>
                            <td class="font-bold" colspan="2">नियोक्त्याची/ठेकेदाराची/<br>विकासकाची/मालकाची सही</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                {{-- Sign --}}
                <div style="margin-top: 20px; text-align: right; padding-right: 20px;">
                    <div style="width: 250px; float: right; text-align: center;">
                        <p class="font-bold">प्राधिकृत अधिकाऱ्याची सही व शिक्का</p>
                    </div>
                    <div style="clear: both;"></div>
                </div>

            </div>
        </div>

    </div>

</div>

{{-- Print Frame --}}
<iframe id="printFrame" style="display:none;"></iframe>

{{-- Webcam Modal --}}
<div id="webcamModal" class="fixed inset-0 bg-gray-900/90 z-[100] hidden items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-5 shadow-2xl max-w-md w-full mx-4 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 to-white -z-10"></div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 flex items-center gap-2"><i data-lucide="camera" class="w-5 h-5 text-indigo-500"></i> फोटो घ्या</h3>
            <button onclick="closeWebcam()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        <div class="bg-black rounded-xl overflow-hidden aspect-[3/4] relative shadow-inner">
            <video id="webcamVideo" autoplay playsinline class="w-full h-full object-cover scale-x-[-1]"></video>
            <div class="absolute inset-0 border-2 border-white/20 rounded-xl pointer-events-none"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-56 border-2 border-dashed border-white/50 rounded-lg pointer-events-none"></div>
        </div>
        <div class="mt-5 flex gap-3">
            <button onclick="closeWebcam()" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Cancel</button>
            <button onclick="takeSnapshot()" class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition flex items-center justify-center gap-2"><i data-lucide="aperture" class="w-5 h-5"></i> Capture</button>
        </div>
    </div>
</div>

{{-- Guide Modal --}}
<div id="guideModal" class="fixed inset-0 bg-gray-900/80 z-[100] hidden items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-white rounded-2xl p-6 shadow-2xl max-w-sm w-full mx-4 border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-900 flex items-center gap-2"><i data-lucide="help-circle" class="w-5 h-5 text-indigo-500"></i> सूचना</h3>
            <button onclick="closeGuide()" class="p-1 rounded-md hover:bg-gray-100"><i data-lucide="x" class="w-5 h-5 text-gray-500"></i></button>
        </div>
        <div class="space-y-3 text-sm text-gray-600">
            <p class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-500 mt-0.5 shrink-0"></i> <span>डावीकडील फॉर्ममध्ये माहिती भरा.</span></p>
            <p class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-500 mt-0.5 shrink-0"></i> <span>तुम्ही भरलेली माहिती उजवीकडे फॉर्ममध्ये लगेच दिसेल.</span></p>
            <p class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-500 mt-0.5 shrink-0"></i> <span>जर काही बदल हवे असतील, तर उजवीकडील फॉर्ममध्ये थेट क्लिक करून बदलू शकता.</span></p>
            <p class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-500 mt-0.5 shrink-0"></i> <span>Pay & Print वर क्लिक केल्यावर Wallet मधून पैसे वजा होतील आणि प्रिंट निघेल.</span></p>
        </div>
        <button onclick="closeGuide()" class="mt-5 w-full bg-gray-900 text-white font-bold py-2.5 rounded-xl hover:bg-gray-800 transition">समजले</button>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Formatting date helper
    function formatDate(dateString) {
        if (!dateString) return '';
        const d = new Date(dateString);
        if (isNaN(d.getTime())) return dateString;
        return d.getDate().toString().padStart(2, '0') + '/' + 
               (d.getMonth() + 1).toString().padStart(2, '0') + '/' + 
               d.getFullYear();
    }

    // Sync inputs to document
    function sync() {
        const fields = [
            'outward_no', 'outward_date', 'office_name', 'officer_name', 'designation', 'office_taluka', 'office_pincode',
            'worker_name', 'worker_addr', 'worker_age', 'worker_village', 'worker_taluka', 'worker_district', 'worker_pincode', 'worker_mobile',
            'work_type', 'work_place', 'work_village', 'work_taluka', 'work_district', 'work_pincode', 'appt_date', 'daily_wage'
        ];
        
        fields.forEach(f => {
            const el = document.getElementById('inp_' + f);
            const out = document.getElementById('out_' + f);
            if(el && out) {
                let val = el.value.trim();
                if(f.includes('date')) val = formatDate(val);
                
                if(val) {
                    out.innerText = val;
                    out.classList.remove('empty');
                    out.classList.add('filled');
                } else {
                    out.innerHTML = '&nbsp;';
                    out.classList.add('empty');
                    out.classList.remove('filled');
                }
            }
        });

        // Table sync
        for(let i=1; i<=4; i++) {
            ['name', 'addr', 'mob', 'from', 'to'].forEach(f => {
                const el = document.getElementById(`inp_t_${f}_${i}`);
                const out = document.getElementById(`out_t_${f}_${i}`);
                if(el && out) {
                    let val = el.value.trim();
                    if(f === 'from' || f === 'to') val = formatDate(val);
                    out.innerText = val;
                }
            });
        }
    }

    // Photo Handling
    let videoStream = null;
    function handlePhoto(e) {
        const file = e.target.files[0];
        if (file) {
            const r = new FileReader();
            r.onload = e => setPhoto(e.target.result);
            r.readAsDataURL(file);
        }
    }
    function setPhoto(url) {
        document.getElementById('photoBoxPreview').innerHTML = `<img src="${url}">`;
        document.getElementById('photoArea').innerHTML = `<img src="${url}">`;
    }
    
    // Webcam
    function capturePhoto() {
        document.getElementById('webcamModal').style.display = 'flex';
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then(s => { videoStream = s; document.getElementById('webcamVideo').srcObject = s; })
            .catch(e => {
                alert("Camera error: " + e.message);
                closeWebcam();
            });
    }
    function closeWebcam() {
        document.getElementById('webcamModal').style.display = 'none';
        if (videoStream) videoStream.getTracks().forEach(t => t.stop());
    }
    function takeSnapshot() {
        const v = document.getElementById('webcamVideo');
        const c = document.createElement('canvas');
        c.width = v.videoWidth; c.height = v.videoHeight;
        const ctx = c.getContext('2d');
        ctx.translate(c.width, 0); ctx.scale(-1, 1);
        ctx.drawImage(v, 0, 0);
        setPhoto(c.toDataURL('image/jpeg', 0.8));
        closeWebcam();
    }

    // Modals
    function openGuide() { document.getElementById('guideModal').style.display = 'flex'; }
    function closeGuide() { document.getElementById('guideModal').style.display = 'none'; }

    // Print
    function payAndPrint() {
        const bal = parseFloat('{{ $balance }}');
        const fee = parseFloat('{{ $format->fee }}');
        if (bal < fee) {
            Swal.fire({ icon: 'error', title: 'अपुरी रक्कम', text: `तुमच्या वॉलेटमध्ये पुरेसे पैसे नाहीत. आवश्यक: ₹${fee}, उपलब्ध: ₹${bal}`, confirmButtonText: 'OK' });
            return;
        }

        Swal.fire({
            title: 'खात्री करा', text: `तुमच्या वॉलेटमधून ₹${fee} वजा केले जातील आणि प्रिंट सुरू होईल.`,
            icon: 'question', showCancelButton: true, confirmButtonText: 'हो, प्रिंट करा', cancelButtonText: 'रद्द करा'
        }).then((result) => {
            if (result.isConfirmed) {
                // Remove watermarks
                document.getElementById('watermark1').style.display = 'none';
                
                const btn = document.getElementById('payBtn');
                btn.innerHTML = '<i class="lucide-loader animate-spin w-4 h-4 inline"></i> Processing...';
                btn.disabled = true;

                fetch("{{ route('bonds.deductFee') }}", {
                    method: "POST",
                    headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                    body: JSON.stringify({ slug: '{{ $format->slug }}' })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('walletBal').innerText = data.new_balance;
                        
                        // Execute Print
                        const pFrame = document.getElementById('printFrame');
                        const pDoc = pFrame.contentWindow || pFrame.contentDocument.document || pFrame.contentDocument;
                        
                        const styles = `
                            @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;700;800&display=swap');
                            @page { size: A4; margin: 0; }
                            * { box-sizing: border-box; }
                            body { margin: 0; padding: 0; font-family: 'Noto Sans Devanagari', sans-serif; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                            .bond-page { width: 210mm; height: 297mm; max-height: 297mm; background: white; position: relative; margin: 0; padding: 0; overflow: hidden; box-sizing: border-box; page-break-after: always; }
                            .bond-page:last-child { page-break-after: auto; }
                            .bond-watermark { display: none !important; }
                            .photo-box { border: 2px solid #333 !important; display: flex; align-items: center; justify-content: center; font-size: 11pt; color: #888; text-align: center; background: #fff; overflow: hidden; position: relative; flex-direction: column; }
                            .photo-box img { width: 100%; height: 100%; object-fit: cover; }
                            .photo-box p { font-size: 9pt; margin-top: 5px; line-height: 1.2; padding: 0 5px; }
                            .out-field.filled { font-weight: 700; }
                            .font-bold { font-weight: bold !important; }
                            .border-l-0 { border-left: none !important; }
                            .leading-tight { line-height: 1.25 !important; }
                            table.work-table { width: 100%; border-collapse: collapse; margin-top: 4px; font-size: 10.5pt; }
                            table.work-table th, table.work-table td { border: 1.5px solid #333 !important; padding: 3px 5px; text-align: left; vertical-align: middle; }
                            table.work-table th.center { text-align: center; font-weight: bold; }
                            table.work-table td.center { text-align: center; }
                            .print-border { position: absolute !important; pointer-events: none !important; z-index: 40 !important; border: 3px solid #000 !important; }
                            #page1_content { font-size: 11pt; line-height: 1.35; }
                        `;

                        const html = `
                            <!DOCTYPE html><html><head><title>Print</title>
                            <style>${styles}</style></head><body>
                            ${document.getElementById('page1').outerHTML}
                            </body></html>
                        `;

                        pDoc.document.open();
                        pDoc.document.write(html);
                        pDoc.document.close();

                        setTimeout(() => { pFrame.contentWindow.print(); }, 500);

                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message });
                        document.getElementById('watermark1').style.display = 'flex';
                    }
                })
                .catch(e => {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'सर्वर एरर, कृपया पुन्हा प्रयत्न करा.' });
                    document.getElementById('watermark1').style.display = 'flex';
                })
                .finally(() => {
                    btn.innerHTML = '<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';
                    btn.disabled = false;
                });
            }
        });
    }

    // Initialize Lucide icons
    if(typeof lucide !== 'undefined') lucide.createIcons();
</script>
@endpush

