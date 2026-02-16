<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aadhaar Update / Address Form — SETU Suvidha</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #475569; min-height: 100vh; display: flex; }
        .main-area { flex: 1; overflow-y: auto; padding: 20px; display: flex; justify-content: center; align-items: flex-start; }
        .action-sidebar {
            width: 260px; min-width: 260px; background: #fff;
            border-left: 1px solid #e2e8f0; padding: 0; overflow-y: auto;
            display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0;
        }
        .sb-title { text-align: center; font-size: 16px; font-weight: 800; color: #dc2626; padding: 14px 16px 10px; }
        .sb-divider { height: 1px; background: #e2e8f0; margin: 0 16px; }
        .sb-section { padding: 12px 16px; }
        .sb-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 6px; }
        .sb-select { width: 100%; padding: 8px 10px; border: 1.5px solid #e2e8f0; border-radius: 8px; font-size: 12px; background: #f8fafc; outline: none; cursor: pointer; }
        .sb-select:focus { border-color: #6366f1; }
        .sb-link { font-size: 11px; color: #2563eb; text-decoration: none; display: block; text-align: right; margin-top: 4px; font-weight: 600; }
        .sb-link:hover { text-decoration: underline; }
        .sb-cost { font-size: 28px; font-weight: 800; color: #dc2626; text-align: center; }
        .sb-btn {
            display: block; width: 100%; padding: 11px; border: none; border-radius: 10px;
            font-size: 13px; font-weight: 700; cursor: pointer; text-align: center;
            text-decoration: none; margin-bottom: 8px; transition: opacity 0.15s;
        }
        .sb-btn:hover { opacity: 0.9; }
        .sb-btn-red { background: #dc2626; color: #fff; }
        .sb-btn-green { background: #16a34a; color: #fff; }
        .sb-btn-blue { background: #2563eb; color: #fff; }
        .sb-btn-outline { background: #fff; color: #2563eb; border: 1.5px solid #2563eb; }
        .sb-spacer { flex: 1; }

        /* A4 Page */
        .a4-page {
            width: 210mm; min-height: 297mm; background: #fff;
            padding: 5mm 8mm; margin: 0 auto; position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            font-family: Arial, Helvetica, sans-serif; font-size: 8pt; line-height: 1.35;
        }

        /* Header */
        .uf-header { text-align: center; margin-bottom: 4px; }
        .uf-header-row { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 3px; }
        .uf-header img { height: 32px; }
        .uf-header-text { font-weight: bold; font-size: 10pt; }
        .tricolor-bar { height: 4px; background: linear-gradient(to right, #FF9933 33%, #FFF 33%, #FFF 66%, #138808 66%); margin: 3px 0; }
        .red-banner { background: #c0392b; color: #fff; padding: 4px 8px; text-align: center; font-weight: bold; font-size: 8.5pt; margin: 3px 0; }
        .instruction-row { display: flex; justify-content: space-between; font-size: 7pt; margin: 2px 0 6px; }
        .instruction-row .blue { color: #2563eb; font-weight: 600; }
        .instruction-row .red { color: #c0392b; font-weight: 600; }

        /* Date boxes in header */
        .date-boxes { display: inline-flex; align-items: center; gap: 2px; }
        .date-boxes .d-box { width: 14px; height: 14px; font-size: 8pt; }

        /* Section headers */
        .section-bar { padding: 3px 8px; font-weight: bold; font-size: 8pt; color: #fff; margin: 6px 0 4px; }
        .section-bar.purple { background: #2c3e6d; }
        .section-bar.orange { background: #b45309; }

        /* Status checkboxes row */
        .status-row { display: flex; flex-wrap: wrap; gap: 10px; margin: 4px 0; font-size: 7.5pt; align-items: center; }
        .cb-box { width: 11px; height: 11px; margin-right: 2px; vertical-align: middle; cursor: pointer; }

        /* Character box grid */
        .form-row { display: flex; align-items: flex-start; margin-bottom: 3px; position: relative; }
        .form-row label { font-size: 7.5pt; font-weight: 600; min-width: 110px; padding-top: 4px; color: #333; flex-shrink: 0; }
        .char-grid-wrap { position: relative; flex: 1; }
        .hidden-input {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0; font-size: 13px; z-index: 2; cursor: text;
            text-transform: uppercase; border: none; outline: none; background: transparent;
        }
        .char-grid { display: flex; flex-wrap: wrap; gap: 0; }
        .char-box {
            width: 18px; height: 20px; border: 1px solid #555;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; font-family: 'Courier New', monospace;
            text-transform: uppercase; flex-shrink: 0;
        }
        .char-box.active-box { border-color: #2563eb; background: #eff6ff; box-shadow: 0 0 3px rgba(37,99,235,0.5); }

        /* Photo box */
        .photo-section {
            border: 1.5px solid #333; width: 130px; min-height: 160px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            font-size: 6.5pt; text-align: center; padding: 4px; color: #555;
            position: absolute; right: 8px; top: 0;
        }
        .photo-section .sig-area { border-top: 1px solid #333; width: 100%; min-height: 40px; margin-top: auto; font-size: 6pt; padding-top: 2px; }

        /* Certifier checkboxes */
        .cert-list { font-size: 7pt; line-height: 1.6; }
        .cert-list label { display: block; min-width: auto; }

        /* Checklist box */
        .checklist-box { border: 1px solid #333; padding: 6px; font-size: 7pt; }
        .checklist-box label { display: block; min-width: auto; margin-bottom: 2px; }

        /* Signature/Stamp box */
        .stamp-box { border: 1.5px solid #333; min-height: 60px; padding: 4px; text-align: center; font-size: 7pt; color: #555; }

        /* Footer */
        .uf-footer { background: linear-gradient(to right, #FF9933, #e67e22); padding: 4px; text-align: center; font-weight: bold; font-size: 9pt; color: #fff; margin-top: 6px; }

        /* Watermark */
        .watermark-layer { position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 5; }
        .watermark-text { position: absolute; font-size: 54px; font-weight: bold; color: rgba(255,0,0,0.06); transform: rotate(-35deg); white-space: nowrap; pointer-events: none; }

        /* Print */
        @page { size: A4; margin: 0; }
        @media print {
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
            .action-sidebar { display: none !important; }
            .main-area { padding: 0 !important; background: #fff !important; }
            .a4-page { box-shadow: none !important; margin: 0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .watermark-layer { display: none !important; }
            .hidden-input { display: none !important; }
        }
        @media (max-width: 900px) {
            .action-sidebar { display: none; }
            body::after { content: 'कृपया डेस्कटॉपवर फॉर्म प्रिंट करा'; position: fixed; bottom: 0; left: 0; right: 0; background: #fbbf24; color: #000; text-align: center; padding: 8px; font-size: 12px; font-weight: 600; z-index: 999; }
        }
    </style>
</head>
<body>

<div class="main-area">
    <div class="a4-page" id="formPage">

        <div class="watermark-layer" id="watermark-layer">
            <div class="watermark-text" style="top:5%;left:-5%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:25%;left:15%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:45%;left:-2%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:65%;left:18%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:85%;left:5%;">UNPAID / PREVIEW</div>
        </div>

        {{-- ═══ HEADER ═══ --}}
        <div class="uf-header">
            <div class="uf-header-row">
                <img src="/images/aadhaar-logo.png" alt="Aadhaar" onerror="this.style.display='none'">
                <div>
                    <div style="font-size:7pt;color:#e65100;font-weight:bold;">मेरा आधार मेरी पहचान</div>
                    <div class="uf-header-text">Unique Identification Authority of India</div>
                </div>
                <img src="/images/uidai-emblem.png" alt="Emblem" style="height:36px;" onerror="this.style.display='none'">
            </div>
            <div class="tricolor-bar"></div>
            <div class="red-banner">CERTIFICATE FOR AADHAAR ENROLMENT / UPDATE (TO BE USED ONLY AS PROOF OF ADDRESS*)</div>
            <div class="instruction-row">
                <span class="blue">All details to be filled in Block Letters</span>
                <span class="red">To be valid for 3 months from date of issue</span>
            </div>
            <div class="instruction-row">
                <span style="font-size:6.5pt;">To be printed on plain A4 paper size | Not required to be printed on letter head</span>
                <span>Date:
                    <span class="date-boxes">
                        <input type="text" class="d-box" maxlength="1" style="width:14px;height:14px;font-size:8pt;" id="dd1"><input type="text" class="d-box" maxlength="1" style="width:14px;height:14px;font-size:8pt;" id="dd2">
                        <span>/</span>
                        <input type="text" class="d-box" maxlength="1" style="width:14px;height:14px;font-size:8pt;" id="mm1"><input type="text" class="d-box" maxlength="1" style="width:14px;height:14px;font-size:8pt;" id="mm2">
                        <span>/</span>
                        <input type="text" class="d-box" maxlength="1" style="width:14px;height:14px;font-size:8pt;" id="yy1"><input type="text" class="d-box" maxlength="1" style="width:14px;height:14px;font-size:8pt;" id="yy2"><input type="text" class="d-box" maxlength="1" style="width:14px;height:14px;font-size:8pt;" id="yy3"><input type="text" class="d-box" maxlength="1" style="width:14px;height:14px;font-size:8pt;" id="yy4">
                    </span>
                </span>
            </div>
        </div>

        {{-- ═══ INDIVIDUAL DETAILS SECTION ═══ --}}
        <div class="section-bar purple">INDIVIDUAL SEEKING TO ENROL / AADHAAR NUMBER HOLDER DETAILS</div>

        <div class="status-row">
            <label><input type="checkbox" class="cb-box"> Resident</label>
            <label><input type="checkbox" class="cb-box"> Non-Resident Indian (NRI)</label>
            <label><input type="checkbox" class="cb-box"> OCI/LTV/Nepal/Bhutan/Foreign National</label>
            <label><input type="checkbox" class="cb-box"> New Enrolment</label>
            <label><input type="checkbox" class="cb-box"> Update Request</label>
        </div>

        <div style="position:relative; padding-right: 140px;">
            {{-- Photo box --}}
            <div class="photo-section">
                <div style="margin-bottom:6px;">Individual Seeking to Enrol / Aadhaar Number Holder Recent Colour Passport-Size Photograph.</div>
                <div style="font-size:5.5pt;color:#c00;">Cross Signed and Cross Stamped by the Certifier.</div>
                <div style="font-size:5pt;margin-top:4px;">NB: DO NOT OVERLAP WITH TEXT BOXES.</div>
                <div class="sig-area">Signature / Thumb / Finger Impression</div>
            </div>

            {{-- Aadhaar Number --}}
            <div class="form-row">
                <label>Aadhaar Number<br><span style="font-size:6pt;color:#888;">(For update only)</span></label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_aadhaar" maxlength="12" oninput="fillNum(this,'grid_aadhaar')" onfocus="showCursor(this,'grid_aadhaar')" onblur="hideCursor('grid_aadhaar')">
                    <div id="grid_aadhaar" class="char-grid"></div>
                </div>
            </div>

            {{-- Full Name (2 rows) --}}
            <div class="form-row">
                <label>Full Name</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_name" maxlength="62" oninput="fill(this,'grid_name')" onfocus="showCursor(this,'grid_name')" onblur="hideCursor('grid_name')">
                    <div id="grid_name" class="char-grid"></div>
                </div>
            </div>

            {{-- House No --}}
            <div class="form-row">
                <label>House No./Bldg./Apt</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_house" maxlength="31" oninput="fill(this,'grid_house')" onfocus="showCursor(this,'grid_house')" onblur="hideCursor('grid_house')">
                    <div id="grid_house" class="char-grid"></div>
                </div>
            </div>

            {{-- Street --}}
            <div class="form-row">
                <label>Street/Road/Lane</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_street" maxlength="31" oninput="fill(this,'grid_street')" onfocus="showCursor(this,'grid_street')" onblur="hideCursor('grid_street')">
                    <div id="grid_street" class="char-grid"></div>
                </div>
            </div>

            {{-- Landmark (2 rows) --}}
            <div class="form-row">
                <label>Landmark</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_landmark" maxlength="62" oninput="fill(this,'grid_landmark')" onfocus="showCursor(this,'grid_landmark')" onblur="hideCursor('grid_landmark')">
                    <div id="grid_landmark" class="char-grid"></div>
                </div>
            </div>

            {{-- Area/Locality (2 rows) --}}
            <div class="form-row">
                <label>Area/Locality/Sector</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_area" maxlength="62" oninput="fill(this,'grid_area')" onfocus="showCursor(this,'grid_area')" onblur="hideCursor('grid_area')">
                    <div id="grid_area" class="char-grid"></div>
                </div>
            </div>

            {{-- Village --}}
            <div class="form-row">
                <label>Village/Town/City</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_village" maxlength="31" oninput="fill(this,'grid_village')" onfocus="showCursor(this,'grid_village')" onblur="hideCursor('grid_village')">
                    <div id="grid_village" class="char-grid"></div>
                </div>
            </div>

            {{-- Post Office --}}
            <div class="form-row">
                <label>Post Office</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_post" maxlength="23" oninput="fill(this,'grid_post')" onfocus="showCursor(this,'grid_post')" onblur="hideCursor('grid_post')">
                    <div id="grid_post" class="char-grid"></div>
                </div>
            </div>

            {{-- Sub-district / Taluka --}}
            <div class="form-row">
                <label>Sub-district (Taluka)</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_taluka" maxlength="23" oninput="fill(this,'grid_taluka')" onfocus="showCursor(this,'grid_taluka')" onblur="hideCursor('grid_taluka')">
                    <div id="grid_taluka" class="char-grid"></div>
                </div>
            </div>

            {{-- District --}}
            <div class="form-row">
                <label>District</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_district" maxlength="23" oninput="fill(this,'grid_district')" onfocus="showCursor(this,'grid_district')" onblur="hideCursor('grid_district')">
                    <div id="grid_district" class="char-grid"></div>
                </div>
            </div>

            {{-- State (2 rows) --}}
            <div class="form-row">
                <label>State</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_state" maxlength="46" oninput="fill(this,'grid_state')" onfocus="showCursor(this,'grid_state')" onblur="hideCursor('grid_state')">
                    <div id="grid_state" class="char-grid"></div>
                </div>
            </div>

            {{-- PIN Code --}}
            <div class="form-row">
                <label>PIN Code</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_pin" maxlength="6" oninput="fillNum(this,'grid_pin')" onfocus="showCursor(this,'grid_pin')" onblur="hideCursor('grid_pin')">
                    <div id="grid_pin" class="char-grid"></div>
                </div>
            </div>

            {{-- Mobile --}}
            <div class="form-row">
                <label>Mobile</label>
                <div class="char-grid-wrap">
                    <input type="text" class="hidden-input" id="inp_mobile" maxlength="10" oninput="fillNum(this,'grid_mobile')" onfocus="showCursor(this,'grid_mobile')" onblur="hideCursor('grid_mobile')">
                    <div id="grid_mobile" class="char-grid"></div>
                </div>
            </div>
        </div>

        {{-- ═══ CERTIFIER'S DETAILS ═══ --}}
        <div class="section-bar orange">CERTIFIER'S DETAILS (TO BE FILLED BY THE CERTIFIER ONLY)</div>

        <div style="display:flex; gap:12px;">
            <div style="flex:1;">
                {{-- Certifier Name --}}
                <div class="form-row">
                    <label>Name of Certifier</label>
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_certifier" maxlength="31" oninput="fill(this,'grid_certifier')" onfocus="showCursor(this,'grid_certifier')" onblur="hideCursor('grid_certifier')">
                        <div id="grid_certifier" class="char-grid"></div>
                    </div>
                </div>

                {{-- Designation --}}
                <div class="form-row">
                    <label>Designation</label>
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_designation" maxlength="31" oninput="fill(this,'grid_designation')" onfocus="showCursor(this,'grid_designation')" onblur="hideCursor('grid_designation')">
                        <div id="grid_designation" class="char-grid"></div>
                    </div>
                </div>

                {{-- Office Address (2 rows) --}}
                <div class="form-row">
                    <label>Office Address</label>
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_office" maxlength="62" oninput="fill(this,'grid_office')" onfocus="showCursor(this,'grid_office')" onblur="hideCursor('grid_office')">
                        <div id="grid_office" class="char-grid"></div>
                    </div>
                </div>

                {{-- Contact Number --}}
                <div class="form-row">
                    <label>Contact Number</label>
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_contact" maxlength="10" oninput="fillNum(this,'grid_contact')" onfocus="showCursor(this,'grid_contact')" onblur="hideCursor('grid_contact')">
                        <div id="grid_contact" class="char-grid"></div>
                    </div>
                </div>

                <div style="font-size:7pt;margin:6px 0 2px;font-weight:600;">I hereby certify above mentioned details are correct and I am a:</div>
                <div class="cert-list">
                    <label><input type="checkbox" class="cb-box"> MP / MLA / MLC / Municipal Councillor</label>
                    <label><input type="checkbox" class="cb-box"> Gazetted Officer Group 'A' / EPFO Officer</label>
                    <label><input type="checkbox" class="cb-box"> Tehsildar / Gazetted Officer Group 'B'</label>
                    <label><input type="checkbox" class="cb-box"> Gazetted Officer at NACO / State Health Department</label>
                    <label><input type="checkbox" class="cb-box"> Head of recognised educational institution</label>
                    <label><input type="checkbox" class="cb-box"> Village Panchayat Head / President or Mukhiya / Gaon Bura</label>
                </div>
            </div>

            <div style="width:160px;flex-shrink:0;">
                <div class="checklist-box" style="margin-bottom:6px;">
                    <div style="font-weight:bold;font-size:7pt;margin-bottom:4px;text-align:center;">Checklist for Certifier</div>
                    <label><input type="checkbox" class="cb-box"> No overwriting</label>
                    <label><input type="checkbox" class="cb-box"> Issue date is filled</label>
                    <label><input type="checkbox" class="cb-box"> Resident's signature</label>
                    <label><input type="checkbox" class="cb-box"> Certifier's details complete</label>
                    <label><input type="checkbox" class="cb-box"> Photo is cross signed & stamped</label>
                </div>
                <div class="stamp-box">
                    Signature & Stamp<br>of the Certifier
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="uf-footer">मेरा आधार, मेरी पहचान — Mera Aadhaar, Meri Pehchaan</div>

    </div>{{-- end a4-page --}}
</div>

{{-- ═══ SIDEBAR ═══ --}}
<div class="action-sidebar">
    <div class="sb-title">Address Fill</div>
    <div class="sb-divider"></div>
    <div class="sb-section">
        <div class="sb-label">Address Auto-fill</div>
        <select class="sb-select" id="addressSelector" onchange="fillAddressData()"><option value="">-- Select Address --</option></select>
        <a href="{{ route('aadhaar.village-info.index') }}" class="sb-link">+ Add New</a>
    </div>
    <div class="sb-divider"></div>
    <div class="sb-section" style="text-align:center;"><div class="sb-label">Wallet Deduction</div><div class="sb-cost">₹5.00</div></div>
    <div class="sb-divider"></div>
    <div class="sb-section">
        <button class="sb-btn sb-btn-red" onclick="payAndPrint()">🖨️ Pay & Print (₹5)</button>
        <a href="{{ route('dashboard') }}" class="sb-btn sb-btn-green">🏠 Dashboard</a>
        <a href="{{ route('aadhaar.hub') }}" class="sb-btn" style="background:#1e293b;color:#fff;">← Back to Hub</a>
    </div>
    <div class="sb-divider"></div>
    <div class="sb-section">
        <div class="sb-label">Create Other Forms</div>
        <a href="{{ route('aadhaar.adult-form') }}" class="sb-btn sb-btn-blue">Adult Form (18+)</a>
        <a href="{{ route('aadhaar.minor-form') }}" class="sb-btn sb-btn-outline">Minor Form (5-18)</a>
        <a href="{{ route('aadhaar.child-form') }}" class="sb-btn sb-btn-outline">Child Form (0-5)</a>
    </div>
    <div class="sb-spacer"></div>
    <div class="sb-section" style="font-size:10px;color:#94a3b8;text-align:center;">Wallet: ₹<span id="walletDisplay">{{ $walletBalance ?? 0 }}</span></div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const savedAddresses = @json($addresses ?? []);

// ── Grid definitions: { id: boxCount } ──
const gridDefs = {
    grid_aadhaar: 12, grid_name: 62, grid_house: 31, grid_street: 31,
    grid_landmark: 62, grid_area: 62, grid_village: 31, grid_post: 23,
    grid_taluka: 23, grid_district: 23, grid_state: 46, grid_pin: 6, grid_mobile: 10,
    grid_certifier: 31, grid_designation: 31, grid_office: 62, grid_contact: 10
};

// ── Create character boxes ──
Object.entries(gridDefs).forEach(([id, count]) => {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = '';
    for (let i = 0; i < count; i++) {
        let box = document.createElement('div');
        box.className = 'char-box';
        el.appendChild(box);
    }
});

function fill(input, gridId) {
    let val = input.value.toUpperCase();
    input.value = val;
    renderBoxes(val, gridId);
    updateCursorPos(input, gridId);
}
function fillNum(input, gridId) {
    let val = input.value.replace(/[^0-9]/g, '');
    input.value = val;
    renderBoxes(val, gridId);
    updateCursorPos(input, gridId);
}
function renderBoxes(val, gridId) {
    let boxes = document.getElementById(gridId).children;
    for (let i = 0; i < boxes.length; i++) {
        boxes[i].textContent = val[i] || '';
    }
}
function showCursor(input, gridId) {
    updateCursorPos(input, gridId);
}
function hideCursor(gridId) {
    let boxes = document.getElementById(gridId).children;
    for (let i = 0; i < boxes.length; i++) boxes[i].classList.remove('active-box');
}
function updateCursorPos(input, gridId) {
    let boxes = document.getElementById(gridId).children;
    for (let i = 0; i < boxes.length; i++) boxes[i].classList.remove('active-box');
    let pos = input.value.length;
    if (pos < boxes.length) boxes[pos].classList.add('active-box');
}

// ── Auto-fill date header ──
(function() {
    const now = new Date();
    const d = now.getDate().toString().padStart(2,'0');
    const m = (now.getMonth()+1).toString().padStart(2,'0');
    const y = now.getFullYear().toString();
    const el = (id) => document.getElementById(id);
    if (el('dd1')) el('dd1').value = d[0];
    if (el('dd2')) el('dd2').value = d[1];
    if (el('mm1')) el('mm1').value = m[0];
    if (el('mm2')) el('mm2').value = m[1];
    if (el('yy1')) el('yy1').value = y[0];
    if (el('yy2')) el('yy2').value = y[1];
    if (el('yy3')) el('yy3').value = y[2];
    if (el('yy4')) el('yy4').value = y[3];
})();

// ── Populate address dropdown ──
(function() {
    const sel = document.getElementById('addressSelector');
    savedAddresses.forEach(a => {
        let o = document.createElement('option');
        o.value = a.id;
        o.textContent = a.village + ' — ' + a.post_office + ' (' + a.pincode + ')';
        sel.appendChild(o);
    });
})();

function fillAddressData() {
    const id = document.getElementById('addressSelector').value;
    if (!id) return;
    const addr = savedAddresses.find(a => a.id == id);
    if (!addr) return;

    // Fill character grid inputs
    const setAndFill = (inputId, gridId, val, isNum) => {
        let inp = document.getElementById(inputId);
        if (!inp) return;
        inp.value = isNum ? val.replace(/[^0-9]/g, '') : val.toUpperCase();
        renderBoxes(inp.value, gridId);
    };

    setAndFill('inp_village', 'grid_village', addr.village, false);
    setAndFill('inp_post', 'grid_post', addr.post_office, false);
    setAndFill('inp_taluka', 'grid_taluka', addr.taluka, false);
    setAndFill('inp_district', 'grid_district', addr.district, false);
    setAndFill('inp_state', 'grid_state', addr.state, false);
    setAndFill('inp_pin', 'grid_pin', addr.pincode, true);
    if (addr.verifier_name) setAndFill('inp_certifier', 'grid_certifier', addr.verifier_name, false);
}

// ── Auto-advance for date d-boxes ──
document.querySelectorAll('.d-box').forEach((box) => {
    box.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0,1);
        if (this.value.length === 1) {
            let n = this.nextElementSibling;
            while (n && !n.classList.contains('d-box')) n = n.nextElementSibling;
            if (n) n.focus();
        }
    });
    box.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value === '') {
            let p = this.previousElementSibling;
            while (p && !p.classList.contains('d-box')) p = p.previousElementSibling;
            if (p) { p.focus(); p.select(); }
        }
    });
});

// ── Pay & Print ──
function payAndPrint() {
    const nameInp = document.getElementById('inp_name');
    if (!nameInp || nameInp.value.trim() === '') { alert('Please enter Full Name first.'); return; }
    if (!confirm('₹5 will be deducted from your wallet. Print now?')) return;

    fetch('/aadhaar/pay', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }, body: JSON.stringify({ form_type: 'update' }) })
    .then(r => r.json()).then(data => {
        if (data.status === 'success') {
            document.getElementById('walletDisplay').textContent = data.new_balance;
            document.getElementById('watermark-layer').style.display = 'none';
            let name = nameInp.value.trim();
            let oldTitle = document.title; document.title = name.replace(/\s+/g,'_') + '_Aadhaar_Update_Form';
            setTimeout(() => { window.print(); setTimeout(() => { document.title = oldTitle; location.reload(); }, 2000); }, 500);
        } else { alert(data.message || 'Payment failed.'); }
    }).catch(() => alert('Network error. Please try again.'));
}
</script>
</body>
</html>
