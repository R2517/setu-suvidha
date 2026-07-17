@extends('layouts.bond-maker')
@section('title', 'MahaDBT Scholarship Affidavit — SETU Suvidha')

@push('styles')
<style>
    .sig-block { display: flex; justify-content: space-between; align-items: flex-start; margin-top: 30px; margin-bottom: 20px; }
    .sig-block div { min-width: 180px; }
    .hr-line { border: none; border-top: 1px solid #333; margin: 15px 0; }
    ol.custom-list { counter-reset: item; padding-left: 0; list-style-type: none; }
    ol.custom-list > li { display: flex; margin-bottom: 12px; }
    ol.custom-list > li > .num { width: 35px; flex-shrink: 0; font-weight: bold; }
    ol.custom-list > li > .content { flex-grow: 1; }
    .dotted-line { border-bottom: 1px dashed #666; display: inline-block; min-width: 100px; padding: 0 10px; text-align: center; font-weight: bold; }
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
                    <h2 class="text-sm font-bold text-gray-800 leading-tight">MahaDBT Affidavit</h2>
                    <p class="text-[10px] text-gray-400">शिष्यवृत्ती प्रतिज्ञापत्र (४ पाने)</p>
                </div>
            </div>
            <button onclick="openGuide()" class="w-7 h-7 bg-indigo-50 text-indigo-500 rounded-lg text-xs font-bold hover:bg-indigo-100 transition flex items-center justify-center">?</button>
        </div>

        {{-- Form --}}
        <div class="px-3 pb-4 space-y-2.5 mt-2.5">

            {{-- SEC 1: प्रतिज्ञापत्र लिहून देणार (Parent) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">1</span><span class="title">पालक / लिहून देणार</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">पालकाचे पूर्ण नाव</label><input type="text" id="inp_parent_name" oninput="sync()" placeholder="उदा. रमेश मारोतराव पाटील" class="form-input"></div>
                    <div><label class="field-label">कायमचा पत्ता</label><textarea id="inp_parent_addr" oninput="sync()" rows="2" class="form-textarea"></textarea></div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">व्यवसाय / धंदा</label><input type="text" id="inp_parent_occ" oninput="sync()" placeholder="शेती" class="form-input"></div>
                        <div><label class="field-label">वय</label><input type="number" id="inp_parent_age" oninput="sync()" placeholder="45" class="form-input"></div>
                    </div>
                </div>
            </div>

            {{-- SEC 2: विद्यार्थी व शिक्षण (Student & College) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">2</span><span class="title">विद्यार्थी व शिक्षण</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">विद्यार्थी/विद्यार्थीनीचे नाव</label><input type="text" id="inp_student_name" oninput="sync()" placeholder="उदा. सुरेश रमेश पाटील" class="form-input"></div>
                    <div><label class="field-label">शाळा/महाविद्यालय नाव व पत्ता</label><textarea id="inp_college_name" oninput="sync()" rows="2" class="form-textarea"></textarea></div>
                    <div><label class="field-label">अभ्यासक्रमाचे नाव (Course)</label><input type="text" id="inp_course_name" oninput="sync()" placeholder="B.Sc. (Computer Science)" class="form-input"></div>
                    <div><label class="field-label">शैक्षणिक वर्ष</label><input type="text" id="inp_academic_year" oninput="sync()" placeholder="२०२३-२०२४" class="form-input"></div>
                    <div><label class="field-label">शिष्यवृत्ती योजना</label><input type="text" id="inp_scheme_name" oninput="sync()" placeholder="उदा. राजर्षी छत्रपती शाहू महाराज..." class="form-input"></div>
                </div>
            </div>

            {{-- SEC 3: उत्पन्न माहिती (Income Details) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">3</span><span class="title">उत्पन्न माहिती</span></div>
                <div class="section-body space-y-2">
                    <div><label class="field-label">उत्पन्नाचे स्त्रोत</label><input type="text" id="inp_income_source" oninput="sync()" value="शेती / मजुरी" class="form-input"></div>
                    <div><label class="field-label">वार्षिक उत्पन्न वर्ष (उदा. २०२२-२३)</label><input type="text" id="inp_income_year" oninput="sync()" placeholder="२०२२-२३" class="form-input"></div>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">एकूण उत्पन्न (रु.)</label><input type="text" id="inp_income_num" oninput="sync()" placeholder="४५,०००/-" class="form-input"></div>
                        <div><label class="field-label">उत्पन्न अक्षरात</label><input type="text" id="inp_income_words" oninput="sync()" placeholder="पंचेचाळीस हजार" class="form-input"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-2">
                        <div><label class="field-label">जमीन (एकर)</label><input type="text" id="inp_land" oninput="sync()" placeholder="२ एकर" class="form-input"></div>
                        <div><label class="field-label">घर (अंदाजे किंमत)</label><input type="text" id="inp_house_val" oninput="sync()" placeholder="१,००,०००/-" class="form-input"></div>
                    </div>
                    <div><label class="field-label">इतर मालमत्ता (किंमत)</label><input type="text" id="inp_other_val" oninput="sync()" placeholder="नाही" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 4: कुटुंबातील अपत्य (Family Children) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">4</span><span class="title">कुटुंबातील अपत्य</span></div>
                <div class="section-body space-y-2">
                    <div class="grid grid-cols-3 gap-2">
                        <div><label class="field-label">एकूण अपत्य</label><input type="number" id="inp_total_child" oninput="sync()" placeholder="2" class="form-input"></div>
                        <div><label class="field-label">मुले</label><input type="number" id="inp_sons" oninput="sync()" placeholder="1" class="form-input"></div>
                        <div><label class="field-label">मुली</label><input type="number" id="inp_daughters" oninput="sync()" placeholder="1" class="form-input"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div><label class="field-label">विद्यार्थ्याचा क्रमांक</label><input type="text" id="inp_child_no" oninput="sync()" placeholder="प्रथम" class="form-input"></div>
                        <div><label class="field-label">लाभार्थी अपत्य संख्या</label><input type="number" id="inp_bene_child" oninput="sync()" placeholder="0" class="form-input"></div>
                    </div>
                    <div class="text-xs text-gray-500 mt-2 font-medium">लाभ घेतलेल्या इतर अपत्यांची नावे:</div>
                    <input type="text" id="inp_other_child1" oninput="sync()" placeholder="१. नाव" class="form-input">
                    <input type="text" id="inp_other_child2" oninput="sync()" placeholder="२. नाव" class="form-input">
                </div>
            </div>

            {{-- SEC 5: साक्षीदार (Witnesses) --}}
            <div class="section-card">
                <div class="section-header"><span class="num">5</span><span class="title">साक्षीदार</span></div>
                <div class="section-body space-y-2">
                    <div class="text-xs font-bold text-gray-600">साक्षीदार १</div>
                    <div><input type="text" id="inp_wit1_name" oninput="sync()" placeholder="नाव" class="form-input mb-1">
                    <input type="text" id="inp_wit1_addr" oninput="sync()" placeholder="ठिकाण" class="form-input"></div>
                    
                    <div class="text-xs font-bold text-gray-600 mt-2">साक्षीदार २</div>
                    <div><input type="text" id="inp_wit2_name" oninput="sync()" placeholder="नाव" class="form-input mb-1">
                    <input type="text" id="inp_wit2_addr" oninput="sync()" placeholder="ठिकाण" class="form-input"></div>
                </div>
            </div>

            {{-- SEC 6: ठिकाण व दिनांक --}}
            <div class="section-card">
                <div class="section-header"><span class="num">6</span><span class="title">ठिकाण व दिनांक</span></div>
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
        
        <div class="ctrl-bar">
            <div class="ctrl-group"><label>Font</label><input type="range" min="10" max="18" value="14" oninput="setFont(this.value);this.nextElementSibling.textContent=this.value+'pt'"><span class="val">14pt</span></div>
        </div>

        {{-- ══════ PAGE 1 ══════ --}}
        <div id="page1" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 14pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            <div class="absolute inset-0 border-[2px] border-gray-900 pointer-events-none z-40" style="margin: 40px;"></div>

            <div id="watermark1" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="page1_content" class="bond-content" contenteditable="true" spellcheck="false" style="padding: 70px 60px 60px 60px;">

                <div class="text-center mb-10 mt-32">
                    <p class="font-bold text-[1.8rem]">प्रतिज्ञापत्र</p>
                </div>

                <div class="mb-8 leading-10">
                    <p><b>प्रतिज्ञापत्र लिहून देणार :</b> <span id="out_parent_name" class="dotted-line empty w-2/3 text-left">&nbsp;</span></p>
                    <p><b>कायमचा पत्ता :</b> <span id="out_parent_addr" class="dotted-line empty w-3/4 text-left">&nbsp;</span></p>
                    <p><b>व्यवसाय :</b> <span id="out_parent_occ" class="dotted-line empty w-2/3 text-left">&nbsp;</span></p>
                    <p>
                        <b>वय :</b> <span id="out_parent_age" class="dotted-line empty w-24">&nbsp;</span> <b>वर्ष :</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <b>धंदा :</b> <span id="out_parent_occ2" class="dotted-line empty w-48 text-left">&nbsp;</span>
                    </p>
                </div>
                
                <ol class="custom-list leading-10 mb-8">
                    <li>
                        <span class="num">१)</span>
                        <span class="content"><b>विद्यार्थी / विद्यार्थीनीचे नाव :</b> <span id="out_student_name" class="dotted-line empty w-3/5 text-left">&nbsp;</span></span>
                    </li>
                    <li>
                        <span class="num">२)</span>
                        <span class="content"><b>शाळा / महाविद्यालयाचे नाव व पत्ता :</b> <span id="out_college_name" class="dotted-line empty w-full text-left">&nbsp;</span></span>
                    </li>
                    <li>
                        <span class="num">३)</span>
                        <span class="content"><b>प्रवेश घेतलेल्या अभ्यासक्रमाचे नाव :</b> <span id="out_course_name" class="dotted-line empty w-full text-left">&nbsp;</span></span>
                    </li>
                    <li class="pl-8">
                        <span class="content"><b>शैक्षणिक वर्ष / कोणत्या वर्षासाठी :</b> <span id="out_academic_year" class="dotted-line empty w-3/4 text-left">&nbsp;</span></span>
                    </li>
                </ol>

            </div>
        </div>

        {{-- ══════ PAGE 2 ══════ --}}
        <div id="page2" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 14pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            <div class="absolute inset-0 border-[2px] border-gray-900 pointer-events-none z-40" style="margin: 40px;"></div>

            <div id="watermark2" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="page2_content" class="bond-content" contenteditable="true" spellcheck="false" style="padding: 70px 60px 60px 60px;">

                <ol class="custom-list leading-10 mb-6 mt-10">
                    <li>
                        <span class="num">४)</span>
                        <span class="content"><b>विद्यार्थी / विद्यार्थीनीच्या आई-वडील /<br>पालक यांचे नाव :</b> <span id="out_parent_name2" class="dotted-line empty w-3/5 text-left">&nbsp;</span></span>
                    </li>
                    <li>
                        <span class="num">५)</span>
                        <span class="content"><b>राहण्याचा संपूर्ण पत्ता :</b> <span id="out_parent_addr2" class="dotted-line empty w-full text-left">&nbsp;</span></span>
                    </li>
                    <li>
                        <span class="num">६)</span>
                        <span class="content"><b>उत्पन्नाचे स्त्रोत (आई/वडील/पालक/विद्यार्थी/विद्यार्थीनी) :</b> <span id="out_income_source" class="dotted-line empty w-full text-left">&nbsp;</span></span>
                    </li>
                    <li>
                        <span class="num">७)</span>
                        <span class="content"><b>वार्षिक उत्पन्न (सन <span id="out_income_year" class="dotted-line empty w-48">&nbsp;</span>)</b></span>
                    </li>
                </ol>

                <div class="pl-8 leading-10 mb-6">
                    <p><b>अ) जमीन (एकर) :</b> <span id="out_land" class="dotted-line empty w-2/3 text-left">&nbsp;</span></p>
                    <p><b>आ) घर आहे / नाही (अंदाजे किंमत) :</b> <span id="out_house_val" class="dotted-line empty w-1/2 text-left">&nbsp;</span> रु.</p>
                    <p><b>इ) इतर मालमत्ता : आहे / नाही (अंदाजे किंमत) :</b> <span id="out_other_val" class="dotted-line empty w-1/3 text-left">&nbsp;</span> रु.</p>
                    <p><b>ई) व्यवसाय : खाजगी नोकरी / शेती / मजुरी / धंदा :</b> <span id="out_parent_occ3" class="dotted-line empty w-1/2 text-left">&nbsp;</span></p>
                </div>

                <p class="leading-9 mb-6 text-justify">
                    वर्ष <b><span id="out_income_year2" class="dotted-line empty w-32">&nbsp;</span></b> चे माझे व माझ्या कुटुंबाचे सर्व मार्गाने मिळणारे वार्षिक उत्पन्न (आकड्यात) <b><span id="out_income_num" class="dotted-line empty w-48">&nbsp;</span> रु.</b><br>
                    (अक्षरात) रु. <b><span id="out_income_words" class="dotted-line empty w-3/4 text-left">&nbsp;</span></b><br>
                    मी कोणत्याही शासकीय / निमशासकीय कार्यालयात कार्यरत नाही.
                </p>

                <div class="text-center mb-6">
                    <p class="font-bold text-[1.4rem]">प्रतिज्ञापत्र</p>
                </div>

                <p class="leading-[2] mb-12 text-justify" style="text-indent: 40px;">
                    मी असे प्रतिज्ञापत्र नमूद करितो/करिते कि वर नमूद केलेली माहिती बरोबर व सत्य असून ती मला बंधनकारक राहील खोटी आढळून आल्यास मी कायदेशीर शिक्षेस पात्र ठरेन.
                </p>

                <div class="text-right mb-12 font-bold">
                    <p class="mb-14 text-transparent select-none">.</p>
                    <p class="mb-1">स्वाक्षरी</p>
                    <p>( आई / वडील / पालक )</p>
                    <p class="border-t border-gray-600 pt-1 inline-block px-12 text-transparent">........................</p>
                </div>

                <div class="flex justify-between w-full font-bold">
                    <div class="w-[45%]">
                        <p class="mb-4">साक्षीदार १</p>
                        <p class="mb-2">नाव <span id="out_wit1_name" class="dotted-line empty w-4/5">&nbsp;</span></p>
                        <p class="mb-2">ठिकाण <span id="out_wit1_addr" class="dotted-line empty w-3/4">&nbsp;</span></p>
                        <p class="mb-2">स्वाक्षरी <span class="dotted-line empty w-3/4 text-transparent">.</span></p>
                        <p>दिनांक <span class="dotted-line empty w-3/4 text-transparent">.</span></p>
                    </div>
                    <div class="w-[45%]">
                        <p class="mb-4">साक्षीदार २</p>
                        <p class="mb-2">नाव <span id="out_wit2_name" class="dotted-line empty w-4/5">&nbsp;</span></p>
                        <p class="mb-2">ठिकाण <span id="out_wit2_addr" class="dotted-line empty w-3/4">&nbsp;</span></p>
                        <p class="mb-2">स्वाक्षरी <span class="dotted-line empty w-3/4 text-transparent">.</span></p>
                        <p>दिनांक <span class="dotted-line empty w-3/4 text-transparent">.</span></p>
                    </div>
                </div>

            </div>
        </div>

        {{-- ══════ PAGE 3 ══════ --}}
        <div id="page3" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 14pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            <div class="absolute inset-0 border-[2px] border-gray-900 pointer-events-none z-40" style="margin: 40px;"></div>

            <div id="watermark3" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="page3_content" class="bond-content" contenteditable="true" spellcheck="false" style="padding: 100px 60px 60px 60px;">

                <div class="text-center mb-10 mt-10">
                    <p class="font-bold text-[1.4rem]">प्रतिज्ञापत्र</p>
                </div>

                <p class="leading-[2.5] text-justify mb-8" style="text-indent: 40px;">
                    मी श्री. <b><span id="out_parent_name3" class="dotted-line empty w-64">&nbsp;</span></b> 
                    रा. <b><span id="out_parent_addr3" class="dotted-line empty w-32">&nbsp;</span></b> 
                    ता. <b><span id="out_place2" class="dotted-line empty w-32">&nbsp;</span></b> 
                    जि. <b>अमरावती</b> येथील कायम रहिवासी असून मला एकूण <b><span id="out_total_child" class="dotted-line empty w-16">&nbsp;</span></b> अपत्य आहेत. 
                    त्यापैकी <b><span id="out_sons" class="dotted-line empty w-16">&nbsp;</span></b> मुले व <b><span id="out_daughters" class="dotted-line empty w-16">&nbsp;</span></b> मुली आहेत. 
                    <b><span id="out_student_name2" class="dotted-line empty w-64">&nbsp;</span></b> हा 
                    <b><span id="out_child_no" class="dotted-line empty w-24">&nbsp;</span></b> क्रमांकाचा लाभार्थी अपत्य (पुरुष) आहे. तो 
                    <b><span id="out_college_name2" class="dotted-line empty w-full">&nbsp;</span></b> या महाविद्यालय / विद्यालय मध्ये 
                    <b><span id="out_course_name2" class="dotted-line empty w-64">&nbsp;</span></b> या अभ्यासक्रमास शिक्षण घेत असून तो 
                    <b><span id="out_scheme_name" class="dotted-line empty w-full">&nbsp;</span></b> या शिष्यवृत्ती योजनेकरीता अर्ज करीत आहे 
                    यापूर्वी माझ्या कुटुंबातील एकूण <b><span id="out_bene_child" class="dotted-line empty w-24">&nbsp;</span></b> अपत्यांनी (पुरुष) शिष्यवृत्तीचा लाभ घेतलेला आहे. त्यांची नावे
                </p>

                <div class="pl-12 mb-8 leading-9">
                    <p>१) <span id="out_other_child1" class="dotted-line empty w-2/3 text-left">&nbsp;</span></p>
                    <p>२) <span id="out_other_child2" class="dotted-line empty w-2/3 text-left">&nbsp;</span></p>
                    <p>३) <span class="dotted-line empty w-2/3 text-transparent">.</span></p>
                    <p>४) <span class="dotted-line empty w-2/3 text-transparent">.</span></p>
                </div>

                <p class="leading-[2] mb-16 text-justify">
                    हि आहेत. वर दिलेली माहिती ही पूर्णतः खरी असून त्याची सर्वस्वी जबाबदारी माझी आहे त्यामध्ये काही खोटे आढळल्यास माझ्या पाल्याला मिळणारी शिष्यवृत्ती व्याजासह शासनास परत करील अशी हमी देत आहे. तसेच शासननिर्णयानुसार होणाऱ्या कारवाईस मी व्यक्तिश: जबाबदार असेल.
                </p>

                <div class="flex justify-between font-bold mb-8">
                    <div class="text-center">
                        <p class="mb-0">विद्यार्थ्यांची स्वाक्षरी</p>
                    </div>
                    <div class="text-center">
                        <p class="mb-0">पालकांची स्वाक्षरी</p>
                    </div>
                </div>

                <div class="font-bold">
                    <p class="mb-4">दिनांक :- <span id="out_date" class="dotted-line empty w-48">&nbsp;</span></p>
                    <p class="mb-0">ठिकाण :- <span id="out_place" class="dotted-line empty w-48">&nbsp;</span></p>
                </div>

            </div>
        </div>

        {{-- ══════ PAGE 4 ══════ --}}
        <div id="page4" class="bond-page relative w-[794px] mx-auto bg-white shadow-2xl mb-6"
             style="min-height: 1123px; font-size: 13pt; font-family: 'Mukta', 'Noto Sans Devanagari', 'Poppins', sans-serif;">

            <div class="absolute inset-0 border-[2px] border-gray-900 pointer-events-none z-40" style="margin: 40px;"></div>

            <div id="watermark4" class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">
                <span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>
            </div>

            <div id="page4_content" class="bond-content" contenteditable="true" spellcheck="false" style="padding: 70px 60px 60px 60px;">

                <div class="text-center mb-8 mt-10">
                    <p class="font-bold text-[1.1rem] underline">(ब) विद्यार्थी व पालकांनी द्यावयाचे बंधपत्र (Indemnity Bond) :-</p>
                </div>

                <ol class="custom-list leading-[1.8] text-justify mb-8">
                    <li class="mb-4">
                        <span class="num">१)</span>
                        <span class="content">मी / आम्ही खाली सही करणार / करणारे प्रतिज्ञापन करतो की, शिष्यवृत्ती मिळण्याबाबत शासनाने विहीत केलेल्या अटी व शर्ती मला / आम्हाला मान्य आहेत. अर्जात वरीलप्रमाणे नमूद केलेली सर्व माहिती पूर्णपणे सत्य आहे. सदरची माहिती खोटी अथवा अपुरी आढळल्यास भारतीय दंडविधाना प्रमाणे होणाऱ्या दंडास / शिक्षेस मी / आम्ही पात्र आहे / आहोत. अर्जात नमूद केलेल्या माहितीपैकी कोणतीही माहिती वा निवेदन चुकीचे आढळून आल्यास सक्षम प्राधिकाऱ्याने दिलेला निर्णय अंतिम असेल व तो माझ्यावर / आम्हावर बंधनकारक असेल, अशी मी / आम्ही हमी देतो. जर शिष्यवृत्तीची, शिक्षण शुल्क, परीक्षा शुल्काची रक्कम अनुज्ञेय रक्कमेपेक्षा अधिक मिळाली तर मी / आम्ही ती शासनास जमा करु, अशी जास्तीची अथवा अन्य कारणामुळे वसूल करण्यात येणारी रक्कम मी / आम्ही पूर्णपणे शासनास परत करण्याची हमी देतो.</span>
                    </li>
                    <li class="mb-4">
                        <span class="num">२)</span>
                        <span class="content">माझ्या कुटुंबाच्या उत्पन्नाची व जातीची अर्जात नमूद केलेली माहिती खोटी आढळल्यास माझ्या / माझ्या पाल्याच्या विरुध्द होणाऱ्या कारवाईस मी / आम्ही स्वतः जबाबदार राहु.</span>
                    </li>
                    <li class="mb-4">
                        <span class="num">३)</span>
                        <span class="content">मी अर्जासोबत जोडलेली सर्व कागदपत्रे, मी सक्षम अधिकाऱ्याकडून / प्राधिकऱ्याकडून प्राप्त केलेली असून, ती कागदपत्रे खरी असून योग्य मार्गाने मिळविलेली आहेत. त्यामध्ये कोणत्याही प्रकारचा फेरफार / दुरुस्ती / बदल केलेला नाही. सदरील कागदपत्रे, प्रमाणपत्रे खोटी अथवा नकली नाहीत हे मी सत्य प्रतिज्ञेवर लिहून देतो. अर्जासोबत जोडलेली कागदपत्रे, प्रमाणपत्रे खोटे अथवा बनावट आढळल्यास त्यास मी / आम्ही पुर्णतः जबाबदार असून त्यासाठी भारतीय दंड विधान कायदा कलम १९१ व २०० नुसार लागू होणाऱ्या शिक्षेस मी/आम्ही पात्र राहू, याची मला/आम्हाला पुर्ण जाणीव आहे.</span>
                    </li>
                    <li class="mb-4">
                        <span class="num">४)</span>
                        <span class="content">सन <b><span id="out_academic_year2" class="dotted-line empty w-32">&nbsp;</span></b> या वर्षाची शिष्यवृत्ती, शिक्षण शुल्क, परिक्षा शुल्क व इतर शुल्काची रक्कम माझ्या आधार संलग्न बँक खात्यावर जमा होताच, त्यापैकी महाविद्यालयास देय असलेली शिक्षण शुल्क व परीक्षा शुल्काची रक्कम सात दिवसात महाविद्यालयात जमा करुन त्याची रितसर पावती प्राप्त करुन घेण्याची माझी / आमची जबाबदारी असेल. शुल्क महाविद्यालयास जमा न केल्याने भविष्यात उदभवणाऱ्या परिणामास मी / आम्ही वैयक्तीक जबाबदार असु असे प्रतिज्ञापूर्वक हमीपत्र / बंधपत्र मी / आम्ही सादर करीत आहोत.</span>
                    </li>
                </ol>

                <div class="font-bold mb-12">
                    <p class="mb-2">ठिकाण : <span id="out_place3" class="dotted-line empty w-48 text-left">&nbsp;</span></p>
                    <p class="mb-0">दिनांक : <span id="out_date2" class="dotted-line empty w-48 text-left">&nbsp;</span></p>
                </div>

                <div class="flex justify-between font-bold">
                    <div class="text-center w-[45%]">
                        <p class="mb-14 text-transparent select-none">.</p>
                        <p class="mb-0">(अर्जदाराच्या वडीलांची / पालकाची सही व पुर्ण नाव)</p>
                    </div>
                    <div class="text-center w-[45%]">
                        <p class="mb-14 text-transparent select-none">.</p>
                        <p class="mb-0">(अर्जदाराची सही व पूर्ण नाव)</p>
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
            <div><p class="font-bold text-gray-800 mb-1">1. तपशील भरा:</p><p>डावीकडे विद्यार्थी, पालक आणि उत्पन्नाचे सर्व तपशील भरा.</p></div>
            <div><p class="font-bold text-gray-800 mb-1">2. ४ पानांचा फॉर्म:</p><p>हा फॉर्म एकूण ४ पानांचा आहे. यात स्टॅम्प पेपरसाठी Gap/Padding sliders नाहीत कारण हे साध्या A4 कागदावर प्रिंट करायचे असते.</p></div>
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

function setFont(v){if(typeof bondSetFontAll==='function')bondSetFontAll(v); else document.querySelectorAll('.bond-page').forEach(function(p){p.style.fontSize=v+'pt';});}

var dateFields=['inp_date'];
function fmtDMY(v){if(!v)return '';var d=new Date(v);return ('0'+d.getDate()).slice(-2)+'/'+('0'+(d.getMonth()+1)).slice(-2)+'/'+d.getFullYear();}

var syncMap={
    'inp_parent_name':['out_parent_name', 'out_parent_name2', 'out_parent_name3'],
    'inp_parent_addr':['out_parent_addr', 'out_parent_addr2', 'out_parent_addr3'],
    'inp_parent_occ':['out_parent_occ', 'out_parent_occ2', 'out_parent_occ3'],
    'inp_parent_age':['out_parent_age'],
    'inp_student_name':['out_student_name', 'out_student_name2'],
    'inp_college_name':['out_college_name', 'out_college_name2'],
    'inp_course_name':['out_course_name', 'out_course_name2'],
    'inp_academic_year':['out_academic_year', 'out_academic_year2'],
    'inp_scheme_name':['out_scheme_name'],
    'inp_income_source':['out_income_source'],
    'inp_income_year':['out_income_year', 'out_income_year2'],
    'inp_income_num':['out_income_num'],
    'inp_income_words':['out_income_words'],
    'inp_land':['out_land'],
    'inp_house_val':['out_house_val'],
    'inp_other_val':['out_other_val'],
    'inp_total_child':['out_total_child'],
    'inp_sons':['out_sons'],
    'inp_daughters':['out_daughters'],
    'inp_child_no':['out_child_no'],
    'inp_bene_child':['out_bene_child'],
    'inp_other_child1':['out_other_child1'],
    'inp_other_child2':['out_other_child2'],
    'inp_wit1_name':['out_wit1_name'],
    'inp_wit1_addr':['out_wit1_addr'],
    'inp_wit2_name':['out_wit2_name'],
    'inp_wit2_addr':['out_wit2_addr'],
    'inp_place':['out_place', 'out_place2', 'out_place3'],
    'inp_date':['out_date', 'out_date2'],
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
    if(!document.getElementById('inp_parent_name').value.trim()){alert('कृपया पालकाचे नाव टाका');return;}
    if(!confirm('Confirm Payment of ₹{{ number_format($format->fee, 0) }} for MahaDBT Scholarship Affidavit?'))return;
    var btn=document.getElementById('payBtn');btn.disabled=true;
    btn.innerHTML='<svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Processing...';
    fetch('{{ route("bonds.deductFee") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({slug:'mahadbt-scholarship-affidavit'})})
    .then(function(r){return r.json();}).then(function(data){
        btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();
        if(data.status==='success'){document.getElementById('walletBal').innerText=data.new_balance;document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='none';});window.print();setTimeout(function(){document.querySelectorAll('.bond-watermark').forEach(function(w){w.style.display='flex';});},2000);}
        else{alert('Transaction Failed: '+data.message);}
    }).catch(function(err){btn.disabled=false;btn.innerHTML='<i data-lucide="printer" class="w-4 h-4"></i> Pay & Print (₹{{ number_format($format->fee, 0) }})';lucide.createIcons();console.error(err);alert('Payment request failed.');});
}
</script>
@endpush
