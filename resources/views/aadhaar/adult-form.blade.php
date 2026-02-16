<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aadhaar Adult Form (18+) — SETU Suvidha</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #475569; min-height: 100vh; display: flex; }

        /* Layout */
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
            padding: 6mm 10mm; margin: 0 auto; position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            font-family: 'Times New Roman', Times, serif; font-size: 9pt; line-height: 1.3;
        }

        /* Form table */
        .form-table { width: 100%; border-collapse: collapse; }
        .form-table td, .form-table th { border: 1px solid #000; padding: 3px 5px; vertical-align: top; font-size: 9pt; }
        .form-table th { text-align: left; font-weight: bold; }
        .no-border td, .no-border th { border: none; }
        .header-row td { border: none; padding: 2px 4px; }

        /* Inputs */
        .flex-input {
            border: none; border-bottom: 1px solid #333; outline: none;
            font-family: inherit; font-size: 9pt; width: 100%;
            background: transparent; text-transform: uppercase; padding: 1px 2px;
        }
        .flex-input:focus { border-bottom-color: #2563eb; background: #eff6ff; }
        .d-box, .pin-box {
            width: 16px; height: 16px; border: 1px solid #000; text-align: center;
            font-size: 10pt; font-family: 'Courier New', monospace;
            display: inline-block; margin: 0 1px; outline: none;
            text-transform: uppercase; padding: 0;
        }
        .d-box:focus, .pin-box:focus { border-color: #2563eb; background: #eff6ff; }
        .digit-group { display: inline-flex; align-items: center; gap: 0; }
        .digit-group .spacer { width: 6px; }
        .cb-box { width: 12px; height: 12px; margin-right: 3px; vertical-align: middle; cursor: pointer; }

        /* Header logos */
        .form-header { text-align: center; margin-bottom: 4px; }
        .form-header-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px; }
        .emblem-img { height: 50px; }
        .aadhaar-logo { height: 36px; }
        .form-title { font-size: 12pt; font-weight: bold; text-align: center; margin: 4px 0 2px; }
        .form-subtitle { font-size: 8pt; text-align: center; margin-bottom: 2px; }
        .form-instruction { font-size: 7.5pt; font-style: italic; text-align: center; margin-bottom: 6px; color: #333; }

        /* Section headers */
        .section-num { font-weight: bold; font-size: 9pt; }
        .red-note { color: #c00; font-size: 7pt; font-style: italic; }

        /* Watermark */
        .watermark-layer { position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 5; }
        .watermark-text {
            position: absolute; font-size: 54px; font-weight: bold;
            color: rgba(255, 0, 0, 0.06); transform: rotate(-35deg);
            white-space: nowrap; pointer-events: none;
        }

        /* Signature boxes */
        .sig-box { border: 1px solid #000; min-height: 40px; margin-top: 4px; }

        /* Print */
        @page { size: A4; margin: 0; }
        @media print {
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
            .action-sidebar { display: none !important; }
            .main-area { padding: 0 !important; background: #fff !important; }
            .a4-page { box-shadow: none !important; margin: 0 !important; width: 210mm; padding: 6mm 10mm; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .watermark-layer { display: none !important; }
            .flex-input { border-bottom-color: transparent !important; }
            .d-box, .pin-box { border-color: #000 !important; }
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

        {{-- Watermark --}}
        <div class="watermark-layer" id="watermark-layer">
            <div class="watermark-text" style="top:5%;left:-5%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:20%;left:15%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:35%;left:-2%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:50%;left:18%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:65%;left:0%;">UNPAID / PREVIEW</div>
            <div class="watermark-text" style="top:80%;left:12%;">UNPAID / PREVIEW</div>
        </div>

        {{-- ═══ FORM HEADER ═══ --}}
        <div class="form-header">
            <div class="form-header-row">
                <div style="text-align:left">
                    <img src="/images/uidai-emblem.png" class="emblem-img" alt="Emblem" onerror="this.style.display='none'">
                </div>
                <div style="text-align:center;flex:1">
                    <div style="font-size:10pt;font-weight:bold;">भारतीय विशिष्ट पहचान प्राधिकरण / भारत सरकार</div>
                    <div style="font-size:9pt;">Unique Identification Authority of India / Government of India</div>
                </div>
                <div style="text-align:right">
                    <img src="/images/aadhaar-logo.png" class="aadhaar-logo" alt="Aadhaar" onerror="this.style.display='none'">
                    <div style="font-size:7pt;color:#e65100;">मेरा आधार, मेरी पहचान</div>
                </div>
            </div>
            <div class="form-title">FORM 1: Aadhaar Enrolment and Update</div>
            <div class="form-subtitle">For (a) Resident Indian, or (b) Non-Resident Indian having Proof of Address in India (aged 18 years and above)</div>
            <div class="form-instruction">Please follow the instructions given below this form and use only upper case (block or capital) letters.</div>
        </div>

        {{-- ═══ SECTION 1 — Purpose ═══ --}}
        <table class="form-table">
            <tr>
                <td style="width:30px;"><span class="section-num">1.</span></td>
                <td>
                    <strong>Purpose:</strong>&nbsp;
                    <label><input type="checkbox" class="cb-box" name="purpose" value="enrolment"> Enrolment</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="cb-box" name="purpose" value="update"> Update</label>
                </td>
            </tr>
        </table>

        {{-- ═══ SECTION 2 — Resident Status ═══ --}}
        <table class="form-table">
            <tr>
                <td style="width:30px;"><span class="section-num">2.</span></td>
                <td>
                    <strong>Resident Status:</strong>&nbsp;
                    <label><input type="checkbox" class="cb-box" name="resident" value="resident"> Resident Indian</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="cb-box" name="resident" value="nri"> Non-Resident Indian (NRI)</label>
                    <br><span style="font-size:7pt;color:#555;">{See paragraph 1(c) of the declaration below this form}</span>
                </td>
            </tr>
        </table>

        {{-- ═══ SECTION 3 — Demographic Information ═══ --}}
        <table class="form-table">
            <tr>
                <td style="width:30px;" rowspan="10"><span class="section-num">3.</span></td>
                <td colspan="3"><strong>Demographic Information</strong></td>
            </tr>
            {{-- (a) Name --}}
            <tr>
                <td colspan="3">
                    <strong>(a) Name:</strong>
                    <input type="text" class="flex-input" id="cust_name" placeholder="FULL NAME AS IN POI DOCUMENT" style="margin-top:2px;">
                    <div class="red-note">(Please fill as given in the document presented in support of the POI, while omitting any titles, honorifics and aliases)</div>
                </td>
            </tr>
            {{-- (b) Gender --}}
            <tr>
                <td colspan="3">
                    <strong>(b) Gender:</strong>&nbsp;
                    <label><input type="checkbox" class="cb-box" name="gender" value="female"> Female</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="cb-box" name="gender" value="male"> Male</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="cb-box" name="gender" value="other"> Third gender / Transgender</label>
                </td>
            </tr>
            {{-- (c) Date of Birth --}}
            <tr>
                <td colspan="3">
                    <strong>(c) Date of Birth:</strong>&nbsp;
                    <span class="digit-group">
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                        <span style="margin:0 2px;">/</span>
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                        <span style="margin:0 2px;">/</span>
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                    </span>
                    &nbsp;&nbsp; OR Age:
                    <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"> years
                    <br>
                    <label><input type="checkbox" class="cb-box"> Verified</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Declared</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Approximate</label>
                    <span style="font-size:7pt;color:#555;">(as per the DoB document presented / self-declared by the resident)</span>
                </td>
            </tr>
            {{-- (d) Email --}}
            <tr>
                <td colspan="3">
                    <strong>(d) Email:</strong>&nbsp;
                    <input type="text" class="flex-input" style="width:80%;" placeholder="">
                </td>
            </tr>
            {{-- (e) Mobile --}}
            <tr>
                <td colspan="3">
                    <strong>(e) Mobile number:</strong>&nbsp;
                    <span class="digit-group">
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                    </span>
                </td>
            </tr>
        </table>

        {{-- ═══ SECTION 4 — Basis of enrolment/update ═══ --}}
        <table class="form-table">
            <tr>
                <td style="width:30px;"><span class="section-num">4.</span></td>
                <td>
                    <strong>Basis of enrolment/update:</strong>&nbsp;
                    <label><input type="checkbox" class="cb-box" name="basis" value="doc"> Document verification</label>&nbsp;&nbsp;
                    <label><input type="checkbox" class="cb-box" name="basis" value="hof"> Confirmation by Head of Family (HoF)</label>
                </td>
            </tr>
        </table>

        {{-- ═══ SECTION 5 — Document-based (Address + Documents) ═══ --}}
        <table class="form-table">
            <tr>
                <td style="width:30px;" rowspan="8"><span class="section-num">5.</span></td>
                <td colspan="4"><strong>Document-based enrolment (Address & Documents)</strong></td>
            </tr>
            {{-- (a) Address --}}
            <tr>
                <td colspan="4"><strong>(a) Address:</strong></td>
            </tr>
            <tr>
                <td style="width:50%;">House no./Building/Flat no.: <input type="text" class="flex-input"></td>
                <td colspan="3">Street: <input type="text" class="flex-input"></td>
            </tr>
            <tr>
                <td>Landmark: <input type="text" class="flex-input"></td>
                <td>Ward no.: <input type="text" class="flex-input" style="width:60px;"></td>
                <td colspan="2">Area/Locality/Sector: <input type="text" class="flex-input"></td>
            </tr>
            <tr>
                <td>Village/Town/City: <input type="text" class="flex-input" id="fill_village"></td>
                <td>Post Office: <input type="text" class="flex-input" id="fill_post"></td>
                <td colspan="2">
                    PIN code:
                    <span class="digit-group">
                        <input type="text" class="pin-box" maxlength="1" id="pin1"><input type="text" class="pin-box" maxlength="1" id="pin2"><input type="text" class="pin-box" maxlength="1" id="pin3"><input type="text" class="pin-box" maxlength="1" id="pin4"><input type="text" class="pin-box" maxlength="1" id="pin5"><input type="text" class="pin-box" maxlength="1" id="pin6">
                    </span>
                </td>
            </tr>
            <tr>
                <td>Sub-district (Taluka): <input type="text" class="flex-input" id="fill_taluka"></td>
                <td>District: <input type="text" class="flex-input" id="fill_district"></td>
                <td colspan="2">State: <input type="text" class="flex-input" id="fill_state"></td>
            </tr>
            {{-- (b) Documents --}}
            <tr>
                <td colspan="4"><strong>(b) Type of documents presented:</strong></td>
            </tr>
            <tr>
                <td colspan="4">
                    (i) Proof of Identity (POI): <input type="text" class="flex-input" style="width:70%;"><br>
                    (ii) Proof of Address (POA): <input type="text" class="flex-input" style="width:70%;"><br>
                    (iii) Proof of Date of Birth (PDB) <em>(optional)</em>: <input type="text" class="flex-input" style="width:55%;">
                </td>
            </tr>
        </table>

        {{-- ═══ SECTION 6 — HoF-based enrolment ═══ --}}
        <table class="form-table">
            <tr>
                <td style="width:30px;" rowspan="6"><span class="section-num">6.</span></td>
                <td colspan="3"><strong>HoF-based enrolment</strong></td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>(a) Details of HoF:</strong><br>
                    (i) Name: <input type="text" class="flex-input" style="width:45%;">
                    &nbsp; Aadhaar no.:
                    <span class="digit-group">
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                        <span class="spacer"></span>
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                        <span class="spacer"></span>
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    (ii) Relationship with applicant:&nbsp;
                    <label><input type="checkbox" class="cb-box"> Mother</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Father</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Legal guardian</label>
                    <br>
                    <span style="font-size:7.5pt;">Other relationship (only for address update):</span>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Spouse</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Child/Ward</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Sibling</label>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>(b)</strong> Type of Proof of Relationship (POR) document presented: <input type="text" class="flex-input" style="width:50%;">
                </td>
            </tr>
            <tr>
                <td colspan="3" style="font-size:7.5pt;font-style:italic;">
                    I consent to the collection of my biometric information (photo, fingerprints and irises) for Aadhaar authentication to establish the HoF's identity for the purpose of confirming the applicant's identity and relationship. I understand that this biometric will not be used for any enrolment or update.
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Signature of HoF:
                    <div class="sig-box"></div>
                </td>
            </tr>
        </table>

        {{-- ═══ SECTION 7 — For update ═══ --}}
        <table class="form-table">
            <tr>
                <td style="width:30px;" rowspan="3"><span class="section-num">7.</span></td>
                <td colspan="3"><strong>For update, additional information:</strong></td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>(a) Aadhaar number of applicant:</strong>&nbsp;
                    <span class="digit-group">
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                        <span class="spacer"></span>
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                        <span class="spacer"></span>
                        <input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1"><input type="text" class="d-box" maxlength="1">
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <strong>(b) Information to be updated:</strong><br>
                    <label><input type="checkbox" class="cb-box"> Biometric (photo, fingerprints and irises)</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Name</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Date of Birth</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Gender</label><br>
                    <label><input type="checkbox" class="cb-box"> Address</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Mobile</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Email</label>&nbsp;
                    <label><input type="checkbox" class="cb-box"> Update of POI and POA documents</label>
                </td>
            </tr>
        </table>

        {{-- ═══ DECLARATION ═══ --}}
        <div style="margin-top:6px; font-size:8pt; border:1px solid #000; padding:4px 6px;">
            <strong>Declaration:</strong>
            <ol style="margin-left:14px; font-size:7.5pt; line-height:1.4;">
                <li>I confirm that the information provided by me in this form is true and correct to the best of my knowledge and belief.</li>
                <li>I am entitled to obtain an Aadhaar number and I have not been issued an Aadhaar number earlier.</li>
                <li>I am a resident in India and have been residing in India for a period of 182 days or more in the twelve months immediately preceding the date of application for enrolment / have resided in India at any time during the period of one year immediately preceding the date of application.</li>
                <li>I understand that my biometric information (photograph, fingerprints, and iris scan) will be captured during the enrolment/update process. I further understand that my personal identity data shall remain confidential.</li>
            </ol>
        </div>

        {{-- ═══ SIGNATURE SECTION ═══ --}}
        <table class="form-table" style="margin-top:4px;">
            <tr>
                <td style="width:50%;">
                    Signature of verifier:<br>
                    <div class="sig-box" style="min-height:30px;"></div>
                    Name of verifier: <input type="text" class="flex-input" id="fill_verifier" style="width:70%;">
                </td>
                <td style="width:50%;">
                    Signature / thumb impression of applicant*:<br>
                    <div class="sig-box" style="min-height:30px;"></div>
                    Date and time: <input type="text" class="flex-input" id="date_field" style="width:70%;" readonly>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-size:6.5pt;text-align:center;">
                    *In case the applicant is disabled and unable to give any of the above, the enrolment operator can confirm the identity of the applicant.
                </td>
            </tr>
        </table>

    </div>{{-- end a4-page --}}
</div>

{{-- ═══ RIGHT SIDEBAR ═══ --}}
<div class="action-sidebar">
    <div class="sb-title">Actions</div>
    <div class="sb-divider"></div>

    <div class="sb-section">
        <div class="sb-label">Address Auto-fill</div>
        <select class="sb-select" id="addressSelector" onchange="fillAddressData()">
            <option value="">-- Select Address --</option>
        </select>
        <a href="{{ route('aadhaar.village-info.index') }}" class="sb-link">+ Add New Address</a>
    </div>

    <div class="sb-divider"></div>

    <div class="sb-section" style="text-align:center;">
        <div class="sb-label">Wallet Deduction</div>
        <div class="sb-cost">₹5.00</div>
    </div>

    <div class="sb-divider"></div>

    <div class="sb-section">
        <button class="sb-btn sb-btn-red" onclick="payAndPrint()">🖨️ Pay & Print</button>
        <a href="{{ route('dashboard') }}" class="sb-btn sb-btn-green" style="display:flex;align-items:center;justify-content:center;gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Dashboard
        </a>
    </div>

    <div class="sb-divider"></div>

    <div class="sb-section">
        <a href="{{ route('aadhaar.minor-form') }}" class="sb-btn sb-btn-blue">Create Minor Form (5-18)</a>
        <a href="{{ route('aadhaar.child-form') }}" class="sb-btn sb-btn-outline">Create Child Form (0-5)</a>
        <a href="{{ route('aadhaar.hub') }}" class="sb-btn" style="background:#1e293b;color:#fff;">← Back to Hub</a>
    </div>

    <div class="sb-spacer"></div>
    <div class="sb-section" style="font-size:10px;color:#94a3b8;text-align:center;">
        Wallet: ₹<span id="walletDisplay">{{ $walletBalance ?? 0 }}</span>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const savedAddresses = @json($addresses ?? []);

// ── Populate address dropdown ──
(function() {
    const sel = document.getElementById('addressSelector');
    savedAddresses.forEach(a => {
        let opt = document.createElement('option');
        opt.value = a.id;
        opt.textContent = a.village + ' — ' + a.post_office + ' (' + a.pincode + ')';
        sel.appendChild(opt);
    });
})();

// ── Auto-fill address fields ──
function fillAddressData() {
    const id = document.getElementById('addressSelector').value;
    if (!id) return;
    const addr = savedAddresses.find(a => a.id == id);
    if (!addr) return;

    document.getElementById('fill_village').value = addr.village;
    document.getElementById('fill_post').value = addr.post_office;
    document.getElementById('fill_taluka').value = addr.taluka;
    document.getElementById('fill_district').value = addr.district;
    document.getElementById('fill_state').value = addr.state;
    if (addr.verifier_name) document.getElementById('fill_verifier').value = addr.verifier_name;

    // Fill PIN code boxes
    const pin = addr.pincode || '';
    for (let i = 0; i < 6; i++) {
        let box = document.getElementById('pin' + (i + 1));
        if (box) box.value = pin[i] || '';
    }
}

// ── Auto-advance for digit boxes ──
document.querySelectorAll('.d-box, .pin-box').forEach((box, index, boxes) => {
    box.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9a-zA-Z]/g, '').slice(0, 1);
        if (this.value.length === 1) {
            // Find next sibling d-box or pin-box
            let next = this.nextElementSibling;
            while (next && !next.classList.contains('d-box') && !next.classList.contains('pin-box')) {
                next = next.nextElementSibling;
            }
            if (next) next.focus();
        }
    });
    box.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value === '') {
            let prev = this.previousElementSibling;
            while (prev && !prev.classList.contains('d-box') && !prev.classList.contains('pin-box')) {
                prev = prev.previousElementSibling;
            }
            if (prev) { prev.focus(); prev.select(); }
        }
    });
});

// ── Auto-fill date/time ──
(function() {
    const now = new Date();
    const d = now.getDate().toString().padStart(2, '0');
    const m = (now.getMonth() + 1).toString().padStart(2, '0');
    const y = now.getFullYear();
    const t = now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });
    document.getElementById('date_field').value = d + '/' + m + '/' + y + ' ' + t;
})();

// ── Pay & Print ──
function payAndPrint() {
    const name = document.getElementById('cust_name').value;
    if (name.trim() === '') {
        alert('Please enter Customer Name first.');
        return;
    }
    if (!confirm('₹5 will be deducted from your wallet. Print now?')) return;

    fetch('/aadhaar/pay', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ form_type: 'adult' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('walletDisplay').textContent = data.new_balance;
            document.getElementById('watermark-layer').style.display = 'none';

            let oldTitle = document.title;
            document.title = name.trim().replace(/\s+/g, '_') + '_Aadhaar_Adult_Form';

            setTimeout(() => {
                window.print();
                setTimeout(() => {
                    document.title = oldTitle;
                    location.reload();
                }, 2000);
            }, 500);
        } else {
            alert(data.message || 'Payment failed.');
        }
    })
    .catch(() => alert('Network error. Please try again.'));
}
</script>
</body>
</html>
