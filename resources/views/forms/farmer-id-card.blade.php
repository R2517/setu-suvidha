@extends('layouts.app')
@section('title', 'शेतकरी ओळखपत्र — SETU Suvidha')
@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="farmerForm()">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-amber-600 mb-6"><i data-lucide="arrow-left" class="w-4 h-4"></i> डॅशबोर्डवर जा</a>

    {{-- Header --}}
    <div class="bg-gradient-to-br from-lime-600 to-green-700 rounded-2xl p-8 text-white mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center"><i data-lucide="leaf" class="w-7 h-7"></i></div>
            <div><h1 class="text-2xl font-bold">शेतकरी ओळखपत्र (Farmer ID Card)</h1><p class="text-white/80 text-sm mt-1">QR कोडसह ओळखपत्र | शुल्क: ₹{{ $pricing->price ?? '0' }}</p></div>
        </div>
        <button @click="showForm = !showForm" class="bg-white text-green-600 font-semibold px-6 py-3 rounded-xl hover:bg-green-50 transition flex items-center gap-2"><i data-lucide="plus" class="w-5 h-5"></i> <span x-text="showForm ? 'बंद करा' : 'फॉर्म भरा'"></span></button>
    </div>

    {{-- Form --}}
    <div x-show="showForm" x-transition class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 mb-8">
        <form method="POST" action="/farmer-id-card" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="photo_from_pdf" x-model="photoFromPdf">

            {{-- PDF Auto-Fill Banner --}}
            <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800 rounded-xl">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <div class="flex items-center gap-2 text-amber-700 dark:text-amber-400">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span class="text-sm font-bold">PDF ऑटो-फिल</span>
                    </div>
                    <div class="flex-1 text-xs text-amber-600 dark:text-amber-500">Farmer Registry PDF अपलोड करा — नाव, DOB, लिंग, मोबाईल, फोटो, जमीन तपशील आपोआप भरला जाईल</div>
                    <div class="flex items-center gap-2">
                        <input type="file" accept=".pdf" @change="handlePdfUpload($event)" class="text-xs file:mr-2 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-amber-600 file:text-white file:text-xs file:font-semibold file:cursor-pointer hover:file:bg-amber-700">
                        <template x-if="pdfParsing"><span class="text-xs text-amber-600 animate-pulse font-semibold">⏳ Parsing...</span></template>
                    </div>
                </div>
                <template x-if="pdfStatus">
                    <div class="mt-2 text-xs rounded-lg px-3 py-2" :class="pdfStatus.type === 'success' ? 'bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400'" x-text="pdfStatus.msg"></div>
                </template>
                <template x-if="pdfDebugText">
                    <div class="mt-2">
                        <button type="button" @click="showDebug = !showDebug" class="text-xs text-amber-600 underline">Debug: Extracted Text (click to toggle)</button>
                        <pre x-show="showDebug" class="mt-1 text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 p-2 rounded max-h-48 overflow-auto whitespace-pre-wrap break-all" x-text="pdfDebugText"></pre>
                    </div>
                </template>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                {{-- Left: Personal Details --}}
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-green-700 dark:text-green-400 mb-4 flex items-center gap-2 border-b border-green-200 dark:border-green-800 pb-2">१. वैयक्तिक माहिती (Personal Details)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">नाव (मराठी) *</label><input type="text" name="applicant_name" x-model="formData.applicant_name" required placeholder="शेतकऱ्याचे पूर्ण नाव (मराठी)" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                        <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">Name (English) *</label><input type="text" name="name_english" x-model="formData.name_english" required placeholder="Farmer Full Name (English)" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                        <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">जन्म तारीख *</label><input type="date" name="dob" x-model="formData.dob" required class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                        <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">लिंग *</label><select name="gender" x-model="formData.gender" required class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"><option value="">--निवडा--</option><option value="Male">पुरुष (Male)</option><option value="Female">स्त्री (Female)</option><option value="Other">इतर (Other)</option></select></div>
                        <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">मोबाईल (10) *</label><input type="text" name="mobile" x-model="formData.mobile" required maxlength="10" pattern="[0-9]{10}" placeholder="10 अंकी मोबाईल" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                        <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">आधार (12) *</label><input type="text" name="aadhaar" x-model="formData.aadhaar" required maxlength="14" placeholder="xxxx xxxx xxxx" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                        <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">शेतकरी ID (11) *</label><input type="text" name="farmer_id" x-model="formData.farmer_id" required maxlength="14" placeholder="xxxx xxxxx xxx" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                        <div class="flex items-end gap-2">
                            <label class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400"><input type="checkbox" name="lives_at_farm" x-model="livesAtFarm" class="rounded border-gray-300 text-green-600 focus:ring-green-500"> राहत्या गावातच जमीन?</label>
                        </div>
                    </div>

                    {{-- Address Section — hidden when livesAtFarm is checked --}}
                    <div x-show="!livesAtFarm" x-transition class="mt-4 p-4 bg-green-50/50 dark:bg-green-900/10 rounded-lg border border-green-100 dark:border-green-900/30">
                        <p class="text-xs font-bold text-green-700 dark:text-green-400 mb-3">राहण्याचा पत्ता (Residential Address)</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2"><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">पत्ता ओळ १ (Address Line 1) *</label><input type="text" name="address_line1" placeholder="घर / सोसायटी / रस्ता" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                            <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">राज्य (State)</label><input type="text" name="address_state" value="महाराष्ट्र (Maharashtra)" readonly class="w-full px-3 py-2.5 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm cursor-not-allowed"></div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">जिल्हा (District) *</label>
                                <select name="address_district" x-model="addrDistrict" @change="fetchAddrTalukas()" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                    <option value="">--जिल्हा निवडा--</option>
                                    @foreach($districts as $distName => $talukas)
                                        <option value="{{ $distName }}">{{ $distName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">तालुका (Taluka) *</label>
                                <select name="address_taluka" x-model="addrTaluka" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                    <option value="">--तालुका निवडा--</option>
                                    <template x-for="t in addrTalukaList" :key="t"><option :value="t" x-text="t"></option></template>
                                </select>
                            </div>
                            <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">गाव (Village) *</label><input type="text" name="address_village" placeholder="गावाचे नाव" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                            <div><label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">पिनकोड (Pincode)</label><input type="text" name="address_pincode" maxlength="6" pattern="[0-9]{6}" placeholder="6 अंकी पिनकोड" class="w-full px-3 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></div>
                        </div>
                    </div>
                    <div x-show="livesAtFarm" x-transition class="mt-4">
                        <p class="text-xs text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 rounded-lg px-4 py-2.5 border border-green-200 dark:border-green-800">✅ राहत्या गावातच जमीन — पत्ता खालील जमिनीच्या तपशीलातून घेतला जाईल</p>
                    </div>
                </div>

                {{-- Right: Photo --}}
                <div class="w-full lg:w-48 flex flex-col items-center">
                    <h3 class="text-sm font-bold text-green-700 dark:text-green-400 mb-4">शेतकरी फोटो</h3>
                    <div class="w-36 h-44 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg flex items-center justify-center bg-gray-50 dark:bg-gray-800 mb-3 overflow-hidden">
                        <template x-if="photoPreview"><img :src="photoPreview" class="w-full h-full object-cover"></template>
                        <template x-if="!photoPreview"><span class="text-xs text-gray-400 text-center px-2">फोटो निवडा *</span></template>
                    </div>
                    <input type="file" name="photo" accept="image/*" @change="previewPhoto($event)" class="text-xs w-full">
                    <p class="text-[10px] text-red-500 mt-1">(फोटो अनिवार्य आहे)</p>
                </div>
            </div>

            {{-- Land Details Section --}}
            <div class="mt-8">
                <h3 class="text-sm font-bold text-green-700 dark:text-green-400 mb-4 flex items-center gap-2 border-b border-green-200 dark:border-green-800 pb-2">
                    २. जमिनीचा तपशील (Land Details)
                    <label class="ml-4 flex items-center gap-1 text-xs font-normal text-gray-500"><input type="checkbox" name="khate_no_show" class="rounded border-gray-300 text-green-600 focus:ring-green-500"> खाते नं. दाखवा</label>
                    <label class="ml-2 flex items-center gap-1 text-xs font-normal text-gray-500"><input type="checkbox" name="same_village" class="rounded border-gray-300 text-green-600 focus:ring-green-500"> पुढील जमीन त्याच गावात</label>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <thead class="bg-green-50 dark:bg-green-900/20">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">जिल्हा</th>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">तालुका</th>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">गाव</th>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">गट नं.</th>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">खाते नं.</th>
                                <th class="px-3 py-2 text-left text-xs font-bold text-gray-600 dark:text-gray-400">क्षेत्र (हे.)</th>
                                <th class="px-3 py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(land, idx) in lands" :key="idx">
                                <tr class="border-t border-gray-100 dark:border-gray-800">
                                    <td class="px-2 py-1.5">
                                        <select :name="'land['+idx+'][district]'" x-model="land.district" @change="fetchLandTalukas(idx)" class="w-full px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs">
                                            <option value="">--निवडा--</option>
                                            <template x-for="d in districtNames" :key="d"><option :value="d" x-text="d"></option></template>
                                        </select>
                                    </td>
                                    <td class="px-2 py-1.5">
                                        <select :name="'land['+idx+'][taluka]'" x-model="land.taluka" class="w-full px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs">
                                            <option value="">--निवडा--</option>
                                            <template x-for="t in (land._talukas || [])" :key="t"><option :value="t" x-text="t"></option></template>
                                        </select>
                                    </td>
                                    <td class="px-2 py-1.5"><input :name="'land['+idx+'][village]'" x-model="land.village" type="text" placeholder="गावाचे नाव" class="w-full px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs"></td>
                                    <td class="px-2 py-1.5"><input :name="'land['+idx+'][gat_no]'" type="text" class="w-24 px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs"></td>
                                    <td class="px-2 py-1.5"><input :name="'land['+idx+'][khate_no]'" type="text" class="w-24 px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs"></td>
                                    <td class="px-2 py-1.5"><input :name="'land['+idx+'][area]'" type="number" step="0.01" x-model="land.area" placeholder="00.00" class="w-24 px-2 py-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs"></td>
                                    <td class="px-2 py-1.5">
                                        <div class="flex gap-1">
                                            <button type="button" @click="removeLand(idx)" x-show="lands.length > 1" class="w-7 h-7 rounded-full bg-red-500 text-white text-sm flex items-center justify-center hover:bg-red-600">&minus;</button>
                                            <button type="button" @click="addLand()" x-show="idx === lands.length - 1" class="w-7 h-7 rounded-full bg-green-600 text-white text-sm flex items-center justify-center hover:bg-green-700">+</button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex justify-center">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-xl transition flex items-center gap-2 text-sm"><i data-lucide="save" class="w-4 h-4"></i> ओळखपत्र बनवा (Save)</button>
            </div>
        </form>
    </div>

    {{-- ═══ FARMER SUBMISSIONS TABLE — with bulk select, print count, bulk print ═══ --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden" x-data="farmerSubmissions()">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h3 class="font-bold text-gray-900 dark:text-white">सबमिशन्स ({{ $submissions->count() }})</h3>
            <div class="flex items-center gap-2 flex-wrap" x-show="selectedIds.length > 0" x-cloak>
                <span class="text-xs font-semibold text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-3 py-1.5 rounded-lg" x-text="selectedIds.length + ' निवडले'"></span>
                <button type="button" @click="bulkPrint('sidebyside')" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> शेजारी प्रिंट (4/पृष्ठ)
                </button>
                <button type="button" @click="bulkPrint('duplex')" style="background-color:#f97316;color:#ffffff;" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold hover:opacity-90 transition">
                    <i data-lucide="flip-horizontal" class="w-3.5 h-3.5"></i> दोन्ही बाजू प्रिंट (8/शीट)
                </button>
            </div>
        </div>
        @if($submissions->isEmpty())
        <div class="px-6 py-12 text-center text-gray-400">
            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
            <p>अद्याप कोणतेही सबमिशन नाहीत</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left"><input type="checkbox" @change="toggleAll($event)" class="rounded border-gray-300 text-green-600 focus:ring-green-500"></th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">नाव</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500">तारीख</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500">प्रिंट</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500">कृती</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($submissions as $s)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-4 py-3"><input type="checkbox" value="{{ $s->id }}" x-model="selectedIds" class="rounded border-gray-300 text-green-600 focus:ring-green-500"></td>
                        <td class="px-4 py-3 text-gray-500">{{ $s->id }}</td>
                        <td class="px-4 py-3 text-gray-900 dark:text-white font-medium">{{ $s->applicant_name }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $s->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($s->print_count > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                    ✅ {{ $s->print_count }}×
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">
                                    नाही
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('forms.print', $s->id) }}" target="_blank" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition" title="प्रिंट"><i data-lucide="printer" class="w-4 h-4"></i></a>
                                <form method="POST" action="{{ route('forms.delete', $s->id) }}" onsubmit="return confirm('हा फॉर्म हटवायचा आहे का?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition" title="हटवा"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
if (window.pdfjsLib) {
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
}

function farmerForm() {
    return {
        showForm: false,
        livesAtFarm: false,
        photoPreview: null,
        photoFromPdf: '',
        pdfDebugText: '',
        showDebug: false,

        // Form data model for auto-fill
        formData: {
            applicant_name: '', name_english: '', dob: '', gender: '',
            mobile: '', aadhaar: '', farmer_id: ''
        },

        // PDF parsing state
        pdfParsing: false,
        pdfStatus: null,

        // Full district → taluka map from config
        allDistricts: @json($districts),
        get districtNames() { return Object.keys(this.allDistricts); },

        // Address fields
        addrDistrict: '',
        addrTaluka: '',
        addrTalukaList: [],
        fetchAddrTalukas() {
            this.addrTaluka = '';
            this.addrTalukaList = this.addrDistrict ? (this.allDistricts[this.addrDistrict] || []) : [];
        },

        // Land rows
        lands: [{ district: '', taluka: '', village: '', gat_no: '', khate_no: '', area: '', _talukas: [] }],
        addLand() {
            this.lands.push({ district: '', taluka: '', village: '', gat_no: '', khate_no: '', area: '', _talukas: [] });
        },
        removeLand(idx) {
            if (this.lands.length > 1) this.lands.splice(idx, 1);
        },
        fetchLandTalukas(idx) {
            const land = this.lands[idx];
            land.taluka = '';
            land._talukas = land.district ? (this.allDistricts[land.district] || []) : [];
        },

        // Photo preview
        previewPhoto(e) {
            const file = e.target.files[0];
            if (file) { this.photoPreview = URL.createObjectURL(file); this.photoFromPdf = ''; }
        },

        // ═══ PDF AUTO-FILL ═══
        async handlePdfUpload(e) {
            const file = e.target.files[0];
            if (!file || file.type !== 'application/pdf') return;
            this.pdfParsing = true;
            this.pdfStatus = null;
            let filled = [];

            try {
                // 1. Client-side: Extract text with PDF.js
                const textData = await this._extractPdfText(file);

                // DEBUG: Show extracted text so we can see what PDF.js returns
                console.log('=== PDF.js EXTRACTED TEXT ===');
                console.log('Full text:', textData.fullText);
                console.log('Items:', JSON.stringify(textData.items.slice(0, 80), null, 2));
                this.pdfDebugText = 'FULL TEXT:\n' + textData.fullText + '\n\n--- ITEMS ---\n' + textData.items.map((it, i) => i + '. [p' + it.page + ' x' + it.x + ' y' + it.y + '] "' + it.text + '"').join('\n');

                const parsed = this._parseFields(textData);
                console.log('Parsed fields:', JSON.stringify(parsed, null, 2));

                // 2. Fill personal details
                if (parsed.name_english) { this.formData.name_english = parsed.name_english; filled.push('English Name'); }
                if (parsed.applicant_name) { this.formData.applicant_name = parsed.applicant_name; filled.push('मराठी नाव'); }
                if (parsed.dob) { this.formData.dob = parsed.dob; filled.push('DOB'); }
                if (parsed.gender) { this.formData.gender = parsed.gender; filled.push('Gender'); }
                if (parsed.mobile) { this.formData.mobile = parsed.mobile; filled.push('Mobile'); }
                if (parsed.aadhaar) { this.formData.aadhaar = parsed.aadhaar; filled.push('Aadhaar'); }
                if (parsed.farmer_id) { this.formData.farmer_id = parsed.farmer_id; filled.push('Farmer ID'); }

                // 3. Fill land records (with config matching)
                if (parsed.lands && parsed.lands.length > 0) {
                    this.lands = parsed.lands.map(l => {
                        const matchDist = this.districtNames.find(d =>
                            d.toLowerCase().replace(/[\s\-]/g, '') === (l.district || '').toLowerCase().replace(/[\s\-]/g, '')
                        ) || l.district;
                        const talukas = matchDist ? (this.allDistricts[matchDist] || []) : [];
                        const matchTaluka = talukas.find(tk => {
                            const a = tk.toLowerCase().replace(/[\s\-]/g, '');
                            const b = (l.taluka || '').toLowerCase().replace(/[\s\-]/g, '');
                            return a === b || a.includes(b) || b.includes(a);
                        }) || l.taluka;
                        return {
                            district: matchDist, taluka: matchTaluka, village: l.village || '',
                            gat_no: l.gat_no || '', khate_no: '', area: l.area || '',
                            _talukas: talukas
                        };
                    });
                    filled.push('Land (' + parsed.lands.length + ' rows)');
                }

                // 4. Server-side: Extract photo
                await this._extractPhoto(file);
                if (this.photoPreview) filled.push('Photo');

                this.pdfStatus = filled.length > 0
                    ? { type: 'success', msg: '✅ ऑटो-फिल यशस्वी: ' + filled.join(', ') + ' — कृपया तपासा आणि आवश्यक असल्यास संपादित करा' }
                    : { type: 'error', msg: '⚠️ PDF मधून डेटा काढता आला नाही — कृपया मॅन्युअली भरा' };
            } catch (err) {
                console.error('PDF parse error:', err);
                this.pdfStatus = { type: 'error', msg: '⚠️ PDF वाचण्यात त्रुटी: ' + (err.message || 'Unknown error') };
            }
            this.pdfParsing = false;
        },

        async _extractPdfText(file) {
            if (!window.pdfjsLib) throw new Error('PDF.js not loaded');
            const arrayBuffer = await file.arrayBuffer();
            const pdf = await pdfjsLib.getDocument({ data: arrayBuffer }).promise;
            let allItems = [];

            for (let p = 1; p <= pdf.numPages; p++) {
                const page = await pdf.getPage(p);
                const vp = page.getViewport({ scale: 1 });
                const tc = await page.getTextContent();
                for (const item of tc.items) {
                    if (item.str && item.str.trim()) {
                        allItems.push({
                            text: item.str.trim(),
                            x: Math.round(item.transform[4]),
                            y: Math.round(vp.height - item.transform[5]),
                            page: p
                        });
                    }
                }
            }

            // Sort: page → y (top to bottom) → x (left to right)
            allItems.sort((a, b) => {
                if (a.page !== b.page) return a.page - b.page;
                if (Math.abs(a.y - b.y) > 8) return a.y - b.y;
                return a.x - b.x;
            });

            return { fullText: allItems.map(i => i.text).join(' '), items: allItems };
        },

        _parseFields(td) {
            const t = td.fullText;
            const items = td.items;
            const data = { lands: [] };

            // ── English Name ──
            let m = t.match(/Farmer\s*Name\s*(?:as\s*per\s*Aadhaar\s*)?(?:in\s*English)?\s+([A-Z][a-zA-Z\s]+?)(?=\s+Farmer'?s?|\s+Gender)/i);
            if (m) data.name_english = m[1].trim();

            // ── Marathi / Local Name (position-based: collect Devanagari items on same line as label) ──
            const localLangItem = items.find(it => /Local\s*Language/i.test(it.text));
            if (localLangItem) {
                const devItems = items.filter(it =>
                    it.page === localLangItem.page &&
                    Math.abs(it.y - localLangItem.y) <= 3 &&
                    /[\u0900-\u097F]/.test(it.text)
                ).sort((a, b) => a.x - b.x);
                if (devItems.length > 0) {
                    data.applicant_name = devItems.map(it => it.text).join('');
                }
            }

            // ── Gender ──
            m = t.match(/Gender\s+(Male|Female|Other)/i);
            if (m) data.gender = m[1].charAt(0).toUpperCase() + m[1].slice(1).toLowerCase();

            // ── Date of Birth (handle 2-digit year like 01/01/64) ──
            m = t.match(/Date\s*of\s*Birth\s+(\d{1,2}\/\d{1,2}\/\d{2,4})/i);
            if (m) {
                const [d, mo, y_] = m[1].split('/');
                let y = y_;
                if (y.length === 2) y = (parseInt(y) > 30 ? '19' : '20') + y;
                data.dob = y + '-' + mo.padStart(2, '0') + '-' + d.padStart(2, '0');
            }

            // ── Mobile ──
            m = t.match(/Mobile\s*Number\s+(\d{10})/i);
            if (m) data.mobile = m[1];

            // ── Aadhaar (not in enrollment PDF, but try) ──
            m = t.match(/(?:Aadhaar|Aadhar)\s*(?:Number|No)?\s*[:.]?\s*(\d{4}\s?\d{4}\s?\d{4})/i);
            if (m) data.aadhaar = m[1].replace(/\s/g, '');

            // ── Farmer Enrollment Number ──
            m = t.match(/Farmer\s*enrollment\s*number\s*[:.]?\s*([\w_]+)/i);
            if (m) data.farmer_id = m[1].trim();

            // ── Land Records (position-based table parsing) ──
            data.lands = this._parseLandFromItems(items);

            return data;
        },

        _parseLandFromItems(items) {
            const hdr = items.find(it => /Land\s*Ownership/i.test(it.text));
            if (!hdr) return [];
            const pg = hdr.page;
            const below = items.filter(it => it.page === pg && it.y > hdr.y);

            // Find column header x-positions
            const colH = below.filter(it => it.y < hdr.y + 100);
            const stateH = colH.find(it => it.text === 'State');
            const distH  = colH.find(it => it.text === 'District' && it.x > (stateH?.x || 0) + 20 && it.x < 115);
            const subH   = colH.find(it => it.text === 'Sub');
            const villH  = colH.find(it => it.text === 'Village');
            const sH     = colH.find(it => it.text === 'S' && it.x > 190 && it.x < 220);
            const ssH    = colH.find(it => it.text === 'S/S');
            const extH   = colH.find(it => /Extent/i.test(it.text));
            if (!stateH || !villH) return [];

            // Column x-positions
            const cx = {
                st: stateH.x, di: distH?.x || stateH.x + 38,
                sd: subH?.x || (distH?.x || 79) + 41, vi: villH.x,
                sn: sH?.x || villH.x + 39, ss: ssH?.x || (sH?.x || 206) + 28,
                ar: extH?.x || 441
            };

            // Column boundaries (midpoints)
            const bnd = {
                diMin: (cx.st + cx.di) / 2, diMax: (cx.di + cx.sd) / 2,
                sdMin: (cx.di + cx.sd) / 2,  sdMax: (cx.sd + cx.vi) / 2,
                viMin: (cx.sd + cx.vi) / 2,  viMax: (cx.vi + cx.sn) / 2,
                snMin: (cx.vi + cx.sn) / 2,  snMax: (cx.sn + cx.ss) / 2,
                ssMin: (cx.sn + cx.ss) / 2,  ssMax: cx.ss + 20,
                arMin: cx.ar - 10,           arMax: cx.ar + 60
            };

            // Data items: after all headers, before footer
            const lastHY = Math.max(...colH.filter(it => /Unit|Default|Hectare/i.test(it.text)).map(it => it.y), hdr.y + 100);
            const data = below.filter(it => it.y > lastHY && !/about:blank|Annexure|Consent/i.test(it.text));
            if (data.length === 0) return [];

            // Helper: get English text in an x-range
            const col = (mn, mx) => data
                .filter(it => it.x >= mn && it.x < mx && /[A-Za-z0-9.\-]/.test(it.text) && !/[\u0900-\u097F]/.test(it.text))
                .sort((a, b) => a.y - b.y || a.x - b.x)
                .map(it => it.text).join('');

            const district = col(bnd.diMin, bnd.diMax);
            const subDist  = col(bnd.sdMin, bnd.sdMax);
            const village  = col(bnd.viMin, bnd.viMax);
            const sNo  = data.find(it => it.x >= bnd.snMin && it.x < bnd.snMax && /^\d+$/.test(it.text))?.text || '';
            const ssNo = data.find(it => it.x >= bnd.ssMin && it.x < bnd.ssMax && /^\d+$/.test(it.text))?.text || '';
            const areaItem = data.find(it => it.x >= bnd.arMin && it.x < bnd.arMax && /[\d.]/.test(it.text));
            const area = areaItem ? parseFloat(areaItem.text.replace(/,/g, '')) : '';

            return [{
                district, taluka: subDist, village,
                gat_no: ssNo ? sNo + '/' + ssNo : sNo,
                area: area ? area.toString() : ''
            }];
        },

        async _extractPhoto(file) {
            try {
                const fd = new FormData();
                fd.append('pdf', file);
                fd.append('_token', '{{ csrf_token() }}');
                const resp = await fetch('{{ route("api.parse-farmer-pdf") }}', { method: 'POST', body: fd });
                const json = await resp.json();
                if (json.photo_url) {
                    this.photoPreview = json.photo_url;
                    this.photoFromPdf = json.photo_url;
                }
            } catch (err) {
                console.warn('Photo extraction failed:', err);
            }
        }
    }
}

function farmerSubmissions() {
    return {
        selectedIds: [],
        toggleAll(e) {
            if (e.target.checked) {
                this.selectedIds = @json($submissions->pluck('id')->map(fn($id) => (string)$id));
            } else {
                this.selectedIds = [];
            }
        },
        bulkPrint(mode) {
            if (this.selectedIds.length === 0) return;
            const url = '{{ route("farmer.bulk-print") }}?ids=' + this.selectedIds.join(',') + '&mode=' + mode;
            window.open(url, '_blank');
        }
    }
}
</script>
@endsection
