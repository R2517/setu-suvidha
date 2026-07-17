@extends('layouts.bond-maker')
@section('title', 'Caste Certificate Affidavit — Form 2 & 3')

@push('styles')
<style>
    .addr-tbl{width:100%;margin-top:4px;}
    .addr-tbl td{padding:2px 0;font-size:0.92em;vertical-align:top;}
    .addr-tbl td:first-child{font-weight:500;white-space:nowrap;width:auto;}
    .note-box{border:1px solid #999;border-radius:4px;padding:8px 12px;background:#fafafa;margin-bottom:10px;}
    .note-box p{font-size:0.88em;line-height:1.7;text-align:justify;}
    .dotted-line{border-bottom:1.5px dotted #555;min-width:60px;display:inline-block;min-height:1em;}
    .genealogy-box{border:1.5px dashed #888;border-radius:6px;padding:12px 16px;min-height:180px;background:#fcfcfc;margin:8px 0;}
    .caste-box{border:1.5px dashed #888;border-radius:6px;padding:12px 16px;min-height:100px;background:#fcfcfc;margin:8px 0;}
    .bullet-list{padding-left:28px;margin:8px 0;}
    .bullet-list li{margin-bottom:6px;font-size:0.92em;line-height:1.7;text-align:justify;}
    .sig-block{display:flex;justify-content:space-between;align-items:flex-start;margin-top:30px;}
    .sig-block div{min-width:180px;}
    .out-textarea{white-space:pre-wrap;word-wrap:break-word;min-height:1.5em;}
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">Caste Certificate Affidavit</h2>
                    <p class="text-[10px] text-gray-400">Form 2 & 3 — Rule 4(1)</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 1: Personal Details --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">Personal Details</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Name</label><input type="text" id="inp_name" oninput="sync()" placeholder="Full Name" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">Son / Daughter of</label>
                            <select id="inp_relation" onchange="sync()" class="form-select">
                                <option value="son">son</option>
                                <option value="daughter">daughter</option>
                            </select>
                        </div>
                        <div><label class="field-label">Age (years)</label><input type="number" id="inp_age" oninput="sync()" placeholder="25" class="form-input"></div>
                    </div>
                    <div><label class="field-label">Father's / Husband's Name</label><input type="text" id="inp_father" oninput="sync()" placeholder="Shri/Smt. Name" class="form-input"></div>
                    <div><label class="field-label">Occupation</label><input type="text" id="inp_occupation" oninput="sync()" placeholder="Business / Service / Farming" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 2: Address --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">Present Address</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Residing at</label><input type="text" id="inp_residing" oninput="sync()" placeholder="House No, Area" class="form-input"></div>
                    <div><label class="field-label">Village / Town</label><input type="text" id="inp_village" oninput="sync()" placeholder="Village or Town name" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">Tahsil</label><input type="text" id="inp_tahsil" oninput="sync()" value="Nandgaon Khandeshwar" class="form-input"></div>
                        <div><label class="field-label">District</label><input type="text" id="inp_district" oninput="sync()" value="Amravati" class="form-input"></div>
                    </div>
                    <div><label class="field-label">State</label><input type="text" id="inp_state" oninput="sync()" value="Maharashtra" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 3: Caste Details (Form 2) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">Caste / Tribe Details</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Caste / Tribe Name</label><input type="text" id="inp_caste" oninput="sync()" placeholder="Caste or Tribe name" class="form-input"></div>
                    <div><label class="field-label">Category</label>
                        <select id="inp_category" onchange="sync()" class="form-select">
                            <option value="Scheduled Caste">Scheduled Caste</option>
                            <option value="Scheduled Caste converts to Buddhism">SC converts to Buddhism</option>
                            <option value="De-notified Tribes (Vimukt Jati)">De-notified Tribes (Vimukt Jati)</option>
                            <option value="Nomadic Tribes">Nomadic Tribes</option>
                            <option value="Other Backward Classes">Other Backward Classes</option>
                            <option value="Special Backward Category">Special Backward Category</option>
                        </select>
                    </div>
                    <div><label class="field-label">Religion</label><input type="text" id="inp_religion" oninput="sync()" placeholder="Hindu / Buddhist / etc." class="form-input"></div>
                </div>
            </div>

            {{-- SEC 4: Original Residence --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">Original Residence</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Original place of residence</label><input type="text" id="inp_orig_place" oninput="sync()" placeholder="Original place" class="form-input"></div>
                    <div><label class="field-label">Village / Town</label><input type="text" id="inp_orig_village" oninput="sync()" placeholder="Village or Town" class="form-input"></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">Tahsil</label><input type="text" id="inp_orig_tahsil" oninput="sync()" value="Nandgaon Khandeshwar" class="form-input"></div>
                        <div><label class="field-label">District</label><input type="text" id="inp_orig_district" oninput="sync()" value="Amravati" class="form-input"></div>
                    </div>
                    <div><label class="field-label">State</label><input type="text" id="inp_orig_state" oninput="sync()" value="Maharashtra" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 5: Certificate Status --}}
            <div class="section-card">
                <div class="section-header"><span class="num">5</span><span class="title">Certificate Status</span></div>
                <div class="section-body space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">Applied Status</label>
                            <select id="inp_applied" onchange="sync()" class="form-select">
                                <option value="applied">applied</option>
                                <option value="not applied">not applied</option>
                            </select>
                        </div>
                        <div><label class="field-label">Granted Status</label>
                            <select id="inp_granted" onchange="sync()" class="form-select">
                                <option value="granted">granted</option>
                                <option value="not granted">not granted</option>
                            </select>
                        </div>
                    </div>
                    <div><label class="field-label">Validity Certificate (1)</label><input type="text" id="inp_validity1" oninput="sync()" placeholder="Name / Certificate No." class="form-input"></div>
                    <div><label class="field-label">Validity Certificate (2)</label><input type="text" id="inp_validity2" oninput="sync()" placeholder="Name / Certificate No." class="form-input"></div>
                    <div><label class="field-label">Relation with said person</label><input type="text" id="inp_cert_relation" oninput="sync()" placeholder="Uncle / Brother / etc." class="form-input"></div>
                </div>
            </div>

            {{-- SEC 6: Form 3 — Genealogy (Page 2) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">6</span><span class="title">Genealogy Tree (Form 3)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Family Tree / Genealogy</label><textarea id="inp_genealogy" oninput="sync()" rows="5" placeholder="Enter the genealogy tree of your family and relatives..." class="form-textarea"></textarea></div>
                </div>
            </div>

            {{-- SEC 7: Form 3 — Caste/Tribe Submissions --}}
            <div class="section-card">
                <div class="section-header"><span class="num">7</span><span class="title">Caste/Tribe Submissions (Form 3)</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">Other relevant submissions</label><textarea id="inp_caste_details" oninput="sync()" rows="4" placeholder="Sociology, anthropological, ethnological details, genetical traits of the Caste/Tribes..." class="form-textarea"></textarea></div>
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

        {{-- ══════ A4 PAGE 1 — FORM 2 ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            {{-- Watermark --}}
            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="gap1" class="bond-gap w-full relative overflow-hidden" style="height:80mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">
                <span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE 1 GAP (Adjust Slider for Stamp Paper)</span>
            </div>

            <div id="page1_content" class="bond-content py-4" contenteditable="true" spellcheck="false" style="padding-left:40px;padding-right:40px;">

                {{-- Title --}}
                <div class="text-center mb-4">
                    <p class="font-bold text-lg mb-0">FORM - 2</p>
                    <p class="text-sm mb-3">[Rule 4(1)]</p>
                </div>

                <p class="text-sm text-justify leading-7 mb-4">
                    An Affidavit to be submitted to the Competent Authority with the Application FORM-1 to issue a
                    Scheduled Caste/Scheduled Caste converts to Buddhism/De-notified Tribes (Vimukt Jati)/Nomadic
                    Tribes / Backward Classes or Special Backward Category, Caste Certificate by the Applicant.
                </p>

                {{-- Main Para --}}
                <p class="leading-8 text-justify mb-3" style="text-indent:30px;">
                    I, <span id="out_name" class="out-field empty">&nbsp;</span>
                    <span id="out_relation" class="out-field filled">son</span>/daughter of
                    <span id="out_father" class="out-field empty">&nbsp;</span> aged
                    <span id="out_age" class="out-field empty">&nbsp;</span> years,
                    occupation <span id="out_occupation" class="out-field empty">&nbsp;</span>, residing at
                    <span id="out_residing" class="out-field empty">&nbsp;</span>, village town Tahsil
                    <span id="out_tahsil" class="out-field filled">Nandgaon Khandeshwar</span>,
                    District <span id="out_district" class="out-field filled">Amravati</span>,
                    State of <span id="out_state" class="out-field filled">Maharashtra</span>. Hereby solemnly affirm as under:-
                </p>

                {{-- Caste Belong --}}
                <p class="leading-8 text-justify mb-3" style="text-indent:30px;">
                    I belong to <span id="out_caste" class="out-field empty">&nbsp;</span>
                    Cast /Tribe which is recognized as Scheduled
                    Caste/Scheduled Caste converts to Buddhism/De-notified Tribes (Vimukt Jati)/ Nomadic Tribes/Other
                    Backward Classes/Special Backward Category.
                </p>

                {{-- Religion --}}
                <p class="leading-8 text-justify mb-3" style="text-indent:30px;">
                    To belong to <span id="out_religion" class="out-field empty">&nbsp;</span> religion.
                </p>

                {{-- Original Residence --}}
                <p class="leading-8 text-justify mb-1" style="text-indent:30px;">
                    My original place of residence is <span id="out_orig_place" class="out-field empty">&nbsp;</span>,
                </p>
                <p class="leading-8 mb-1">
                    village/town, <span id="out_orig_village" class="out-field empty">&nbsp;</span>,
                    Tahsil, <span id="out_orig_tahsil" class="out-field filled">Nandgaon Khandeshwar</span>,
                </p>
                <p class="leading-8 mb-3">
                    District, <span id="out_orig_district" class="out-field filled">Amravati</span>, State.
                </p>

                {{-- Applied/Granted --}}
                <p class="leading-8 text-justify mb-3" style="text-indent:30px;">
                    I have <span id="out_applied" class="out-field filled">applied</span>/<s>not applied</s>;
                    <span id="out_granted" class="out-field filled">granted</span>/<s>not granted</s>
                    Scheduled Caste/Scheduled Caste converts to
                    Buddhism/De-notified Tribes (Vimukt Jati)/Nomadic Tribes. Other Backward Classes/Special Backward
                    Category, Certificate to this effect in the State of Maharashtra or any other State.
                </p>

                {{-- Bullet Points --}}
                <ul class="bullet-list">
                    <li>No one from my relatives have been refused Scheduled Caste/Scheduled Caste converts to
                        Buddhism/De-notified Tribes (Vimukt Jati)/Nomadic Tribes/Other Backward Classes/Special
                        Backward Category, Certificate in the State of Maharashtra or any other State.</li>
                    <li>The True copy Validity Certificate received by my relative, viz.<br>
                        &nbsp;&nbsp;&nbsp;&nbsp;(1) <span id="out_validity1" class="out-field empty">&nbsp;</span>,
                        (2) <span id="out_validity2" class="out-field empty">&nbsp;</span>
                    </li>
                </ul>

                <p class="leading-8 text-justify mb-3">
                    Is enclosed with the application. The said person is my<span id="out_cert_relation" class="out-field empty">&nbsp;</span>in relation.
                </p>

                <p class="leading-8 text-justify mb-4">
                    To the best of my knowledge and belief the information given in the Application FORM -1 and in
                    the affidavit is based on facts and is correct.
                </p>

                {{-- Signature Block --}}
                <div class="sig-block mt-10">
                    <div>
                        <p class="mb-6">Place</p>
                        <p>Date</p>
                    </div>
                    <div class="text-right">
                        <p class="mb-1">Signature.......................................</p>
                        <p class="mb-6">.......................................................</p>
                        <p class="text-sm">(Name of the applicant.)</p>
                    </div>
                </div>

                <p class="mt-6 mb-0">........................................................................</p>
                <p class="text-sm font-semibold mt-2">Strike out which is not applicable</p>

            </div>
        </div>

        {{-- ══════ A4 PAGE 2 — FORM 3 ══════ --}}
        <div id="page2" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 11pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

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
                    <p class="font-bold text-lg mb-0">FORM - 3</p>
                    <p class="text-sm italic mb-1">[rule 4 (1)]</p>
                    <p class="font-bold text-base mb-0">AFFIDAVIT OF CLAIMANT/ PARENT(S)</p>
                    <p class="text-sm mb-3">(Rule 4, Order 18 of the Code of Civil Procedure, 1908)</p>
                </div>

                {{-- Main Para --}}
                <p class="leading-8 text-justify mb-3" style="text-indent:30px;">
                    I, <span id="out2_name" class="out-field empty">&nbsp;</span>
                    ............................................................. <span id="out2_relation" class="out-field filled">son</span> / daughter of
                    Shri/Smt. <span id="out2_father" class="out-field empty">&nbsp;</span>
                    ............................................. aged <span id="out2_age" class="out-field empty">&nbsp;</span> years,
                    occupation <span id="out2_occupation" class="out-field empty">&nbsp;</span>
                    ................................., residing at <span id="out2_residing" class="out-field empty">&nbsp;</span> village/
                    Town <span id="out2_village" class="out-field empty">&nbsp;</span>
                    .............Tahsil <span id="out2_tahsil" class="out-field filled">Nandgaon Khandeshwar</span>.......................................,
                    District <span id="out2_district" class="out-field filled">Amravati</span>
                    .........................................................State here by Solemnly
                    affirm as under-
                </p>

                {{-- Point 2: Genealogy --}}
                <p class="leading-8 text-justify mb-2" style="text-indent:30px;">
                    2. I hereby give the genealogy tree of my family and relatives, which is as under: -
                </p>
                <div class="genealogy-box">
                    <span id="out_genealogy" class="out-field out-textarea empty">&nbsp;</span>
                </div>

                {{-- Point 3: Other submissions --}}
                <p class="leading-8 text-justify mb-2" style="text-indent:30px;">
                    3. Other relevant submissions to be made or any essential explanation to be made, in support of Caste
                    /Tribe claim, including the sociology, anthropological and ethnological (anthropological moorings and
                    ethnology kinship), genetical traits, of the Caste/Tribes, if any;
                </p>
                <div class="caste-box">
                    <span id="out_caste_details" class="out-field out-textarea empty">&nbsp;</span>
                </div>

                {{-- Declaration --}}
                <p class="leading-8 text-justify mb-4 mt-6" style="text-indent:30px;">
                    To the best of my knowledge and be lief the information given in application FORM -1 and in this
                    affidavit is based on facts and is correct.
                </p>

                {{-- Signature Block --}}
                <div class="mt-8 mb-2">
                    <p class="mb-6">Place:</p>
                    <p class="mb-8">Date:</p>
                </div>
                <div class="text-center" style="margin-left:40%;">
                    <p class="mb-1">Signature.............................................</p>
                    <p class="mt-4 text-sm">(Name of the applicant/claimant)</p>
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
            <div><p class="font-bold text-gray-800 mb-1">1. Fill Details:</p><p>Enter personal details, caste info, address, genealogy and other submissions on the left. Both Form 2 (Page 1) and Form 3 (Page 2) update live.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. Pre-set Fields:</p><p>District, Tahsil and State are pre-filled. Change them if needed.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">3. Both Pages Print Together:</p><p>Form 2 prints as Page 1 and Form 3 prints as Page 2 in a single print.</p></div>
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

function setGap1(v){document.getElementById('gap1').style.height=v+'mm';}
function setGap2(v){document.getElementById('gap2').style.height=v+'mm';}
function setFont(v){if(typeof bondSetFontAll==='function')bondSetFontAll(v); else document.querySelectorAll('.bond-page').forEach(function(p){p.style.fontSize=v+'pt';});}
function setPad(id,prop,v){var el=document.getElementById(id);if(el)el.style[prop]=v+'px';}

var syncMap={
    'inp_name':['out_name','out2_name'],
    'inp_relation':['out_relation','out2_relation'],
    'inp_father':['out_father','out2_father'],
    'inp_age':['out_age','out2_age'],
    'inp_occupation':['out_occupation','out2_occupation'],
    'inp_residing':['out_residing','out2_residing'],
    'inp_village':['out2_village'],
    'inp_tahsil':['out_tahsil','out2_tahsil'],
    'inp_district':['out_district','out2_district'],
    'inp_state':['out_state'],
    'inp_caste':['out_caste'],
    'inp_religion':['out_religion'],
    'inp_orig_place':['out_orig_place'],
    'inp_orig_village':['out_orig_village'],
    'inp_orig_tahsil':['out_orig_tahsil'],
    'inp_orig_district':['out_orig_district'],
    'inp_orig_state':['out_orig_state'],
    'inp_applied':['out_applied'],
    'inp_granted':['out_granted'],
    'inp_validity1':['out_validity1'],
    'inp_validity2':['out_validity2'],
    'inp_cert_relation':['out_cert_relation'],
    'inp_genealogy':['out_genealogy'],
    'inp_caste_details':['out_caste_details'],
};

function sync(){
    for(var id in syncMap){
        var el=document.getElementById(id);if(!el)continue;
        var val=el.tagName==='SELECT'?el.options[el.selectedIndex].value:el.value;
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
    if(!document.getElementById('inp_name').value.trim()){alert('Please enter Name');return;}
    if(!document.getElementById('inp_father').value.trim()){alert('Please enter Father/Husband Name');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for Caste Certificate Affidavit (Form 2 & 3)?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'caste-form2-form3'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
