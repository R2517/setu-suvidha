<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aadhaar Update / Address Form — SETU Suvidha</title>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #475569; min-height: 100vh; display: flex; }
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

        /* ═══════════════════ A4 PAGE ═══════════════════ */
        .a4-page {
            width: 210mm; min-height: 297mm; background: #fff;
            padding: 5mm 7mm; margin: 0 auto; position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            font-family: Arial, Helvetica, sans-serif; font-size: 7.5pt; line-height: 1.3;
            color: #000;
        }

        /* Header */
        .uf-header { text-align: center; margin-bottom: 2px; }
        .uf-header-row { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 2px; padding: 6px 0; }
        .uf-header img.logo { height: 40px; }
        .uf-header-text-wrap { text-align: left; }
        .uf-header-text-wrap .orange-text { font-size: 8pt; font-weight: bold; color: #e65100; font-style: italic; }
        .uf-header-text-wrap .main-text { font-size: 11pt; font-weight: bold; color: #333; }

        /* Tricolor bar */
        .tricolor-bar { height: 5px; background: linear-gradient(to right, #FF9933 33.33%, #FFFFFF 33.33%, #FFFFFF 66.66%, #138808 66.66%); margin: 2px 0; }

        /* Red banner */
        .red-banner { background: #c0392b; color: #fff; padding: 4px 8px; text-align: center; font-weight: bold; font-size: 7.5pt; letter-spacing: 0.3px; margin: 3px 0; }

        /* Instructions */
        .instruction-row { display: flex; justify-content: space-between; align-items: center; font-size: 6.5pt; margin: 2px 0; }
        .instruction-row .blue { color: #2563eb; font-weight: 600; font-style: italic; }
        .instruction-row .red { color: #c0392b; font-weight: 600; font-style: italic; }
        .date-row { display: flex; justify-content: space-between; align-items: center; font-size: 6.5pt; margin: 2px 0 4px; }

        /* Date digit boxes */
        .d-box {
            width: 16px; height: 16px; border: 1px solid #555; text-align: center;
            font-size: 9pt; font-family: 'Courier New', monospace;
            display: inline-block; margin: 0 0.5px; outline: none; padding: 0;
        }
        .d-box:focus { border-color: #2563eb; background: #eff6ff; }

        /* Section header bars */
        .section-bar { padding: 3px 8px; font-weight: bold; font-size: 7.5pt; color: #fff; margin: 4px 0 3px; letter-spacing: 0.3px; }
        .section-bar.green { background: #2e7d32; }
        .section-bar.orange { background: #bf360c; }

        /* Checkbox */
        .cb-box { width: 12px; height: 12px; margin-right: 3px; vertical-align: middle; cursor: pointer; accent-color: #333; }

        /* Status row */
        .status-row { display: flex; flex-wrap: wrap; gap: 8px; margin: 4px 0 6px; font-size: 7pt; align-items: center; padding: 0 2px; }
        .status-row label { display: inline-flex; align-items: center; gap: 2px; }

        /* ═══ FORM TABLE LAYOUT ═══ */
        .form-tbl { width: 100%; border-collapse: collapse; }
        .form-tbl td { padding: 1px 2px; vertical-align: top; }
        .form-tbl .lbl {
            width: 95px; font-size: 7pt; font-weight: 600; color: #222;
            padding-top: 3px; line-height: 1.25;
        }
        .form-tbl .boxes-cell { padding: 1px 0; }

        /* Character box grid */
        .char-grid-wrap { position: relative; }
        .hidden-input {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0; font-size: 13px; z-index: 2; cursor: text;
            text-transform: uppercase; border: none; outline: none; background: transparent;
        }
        .char-grid { display: flex; flex-wrap: wrap; gap: 0; }
        .char-box {
            width: 17.2px; height: 19px; border: 1px solid #666;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700; font-family: 'Courier New', monospace;
            text-transform: uppercase; flex-shrink: 0; background: #fff;
        }
        .char-box.active-box { border-color: #2563eb; background: #eff6ff; box-shadow: 0 0 2px rgba(37,99,235,0.4); }

        /* Row spacer */
        .row-gap td { height: 6px; }

        /* Bottom address section: District/State/PIN + Photo side by side */
        .bottom-addr-wrap { display: flex; gap: 4px; margin-top: 1px; }
        .bottom-addr-left { flex: 1; }
        .bottom-addr-left .form-tbl { width: 100%; }
        .bottom-addr-right { width: 148px; flex-shrink: 0; }
        .photo-cell-block {
            border: 1.5px solid #555; height: 100%;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; font-size: 6pt; color: #444; padding: 6px 4px;
            line-height: 1.3;
        }
        .photo-cell-block .photo-text { margin-bottom: 6px; }
        .photo-cell-block .red-text { color: #c00; font-weight: 600; font-size: 5.5pt; margin-bottom: 4px; }
        .photo-cell-block .nb-text { font-size: 5pt; font-weight: bold; }

        /* Signature box */
        .sig-box-wrap { margin: 4px 0 2px 97px; width: 210px; }
        .sig-box { border: 1px solid #333; min-height: 45px; width: 100%; }
        .sig-box-label { font-size: 5.5pt; color: #333; text-align: center; margin-top: 1px; line-height: 1.3; }

        /* Certifier bottom section */
        .cert-bottom { display: flex; gap: 8px; margin-top: 4px; }
        .cert-left { flex: 1; font-size: 6.5pt; line-height: 1.5; }
        .cert-left .cert-heading { font-weight: bold; font-size: 7pt; margin-bottom: 3px; }
        .cert-left label { display: block; margin-bottom: 1px; }
        .cert-right { width: 200px; flex-shrink: 0; }
        .checklist-box { border: 1px solid #555; padding: 4px 6px; font-size: 6.5pt; margin-bottom: 4px; }
        .checklist-box .ck-title { font-weight: bold; font-size: 7pt; text-align: center; margin-bottom: 3px; border-bottom: 1px solid #ccc; padding-bottom: 2px; }
        .checklist-box label { display: inline-flex; align-items: center; gap: 2px; margin-right: 4px; margin-bottom: 2px; }
        .stamp-box { border: 1.5px solid #555; min-height: 55px; text-align: center; font-size: 6.5pt; color: #555; padding: 4px; display: flex; align-items: flex-end; justify-content: center; font-style: italic; }

        /* Footer */
        .footer-note { font-size: 5.5pt; color: #555; margin-top: 4px; text-align: center; font-style: italic; }
        .uf-footer { background: linear-gradient(to right, #FF9933 30%, #e67e22 100%); padding: 4px; text-align: center; font-weight: bold; font-size: 8pt; color: #fff; margin-top: 4px; letter-spacing: 0.5px; }

        /* Watermark */
        .watermark-layer { position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 5; }
        .watermark-text { position: absolute; font-size: 50px; font-weight: bold; color: rgba(255,0,0,0.05); transform: rotate(-35deg); white-space: nowrap; pointer-events: none; }

        /* Print */
        @page { size: A4; margin: 0; }
        @media print {
            html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
            .action-sidebar { display: none !important; }
            .main-area { padding: 0 !important; background: #fff !important; }
            .a4-page { box-shadow: none !important; margin: 0 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .watermark-layer { display: none !important; }
            .hidden-input { display: none !important; }
            .char-box.active-box { border-color: #666 !important; background: #fff !important; box-shadow: none !important; }
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

        {{-- ═══════════════ HEADER ═══════════════ --}}
        <div class="uf-header">
            <div class="uf-header-row">
                <img src="/images/aadhaar-logo.png" class="logo" alt="Aadhaar" onerror="this.style.display='none'">
                <div class="uf-header-text-wrap">
                    <div class="orange-text">Mera Aadhaar</div>
                    <div class="orange-text">Meri Pehchaan</div>
                </div>
                <div style="width:2px;height:36px;background:#ccc;margin:0 6px;"></div>
                <div style="text-align:left;">
                    <div class="main-text" style="font-size:11pt;font-weight:bold;">Unique Identification</div>
                    <div class="main-text" style="font-size:11pt;font-weight:bold;">Authority of India</div>
                </div>
            </div>
        </div>

        <div class="tricolor-bar"></div>

        <div class="red-banner">CERTIFICATE FOR AADHAAR ENROLMENT/ UPDATE (TO BE USED ONLY AS PROOF OF ADDRESS*)</div>

        <div class="instruction-row">
            <span class="blue"><em>Instructions:</em> All details to be filled in Block Letters</span>
            <span class="red"><em>(To be valid for 3 months from date of issue)</em></span>
        </div>

        <div class="date-row">
            <span style="font-size:6pt;">To be printed on plain A4 paper size;&nbsp;&nbsp;&nbsp;&nbsp;Not required to be printed on letter head;</span>
            <span>
                <input type="text" class="d-box" maxlength="1" id="dd1"><input type="text" class="d-box" maxlength="1" id="dd2">
                &nbsp;
                <input type="text" class="d-box" maxlength="1" id="mm1"><input type="text" class="d-box" maxlength="1" id="mm2">
                &nbsp;
                <input type="text" class="d-box" maxlength="1" id="yy1"><input type="text" class="d-box" maxlength="1" id="yy2"><input type="text" class="d-box" maxlength="1" id="yy3"><input type="text" class="d-box" maxlength="1" id="yy4">
            </span>
        </div>

        {{-- ═══════════════ INDIVIDUAL DETAILS ═══════════════ --}}
        <div class="section-bar green">INDIVIDUAL SEEKING TO ENROL / AADHAAR NUMBER HOLDER DETAILS</div>

        <div class="status-row">
            <label><input type="checkbox" class="cb-box"> Resident</label>
            <label><input type="checkbox" class="cb-box"> Non-Resident Indian (NRI)</label>
            <label><input type="checkbox" class="cb-box"> OCI / LTV/ Nepal / Bhutan National / Foreign National</label>
            <label><input type="checkbox" class="cb-box"> New Enrolment</label>
            <label><input type="checkbox" class="cb-box"> Update Request</label>
        </div>

        {{-- Main form table --}}
        <table class="form-tbl">
            {{-- Aadhaar Number --}}
            <tr>
                <td class="lbl">Aadhaar Number:<br><span style="font-size:5.5pt;color:#888;">(For update only)</span></td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_aadhaar" maxlength="12" oninput="fillNum(this,'grid_aadhaar')" onfocus="showCursor(this,'grid_aadhaar')" onblur="hideCursor('grid_aadhaar')">
                        <div id="grid_aadhaar" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Full Name (2 rows) --}}
            <tr>
                <td class="lbl">Full Name:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_name" maxlength="62" oninput="fill(this,'grid_name')" onfocus="showCursor(this,'grid_name')" onblur="hideCursor('grid_name')">
                        <div id="grid_name" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Gap --}}
            <tr class="row-gap"><td colspan="2"></td></tr>
            {{-- House No --}}
            <tr>
                <td class="lbl">House No./ Bldg./ Apt:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_house" maxlength="31" oninput="fill(this,'grid_house')" onfocus="showCursor(this,'grid_house')" onblur="hideCursor('grid_house')">
                        <div id="grid_house" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Street --}}
            <tr>
                <td class="lbl">Street/ Road/ Lane:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_street" maxlength="31" oninput="fill(this,'grid_street')" onfocus="showCursor(this,'grid_street')" onblur="hideCursor('grid_street')">
                        <div id="grid_street" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Landmark (2 rows) --}}
            <tr>
                <td class="lbl">Landmark:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_landmark" maxlength="62" oninput="fill(this,'grid_landmark')" onfocus="showCursor(this,'grid_landmark')" onblur="hideCursor('grid_landmark')">
                        <div id="grid_landmark" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Area/Locality (2 rows) --}}
            <tr>
                <td class="lbl">Area/ Locality/ Sector:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_area" maxlength="62" oninput="fill(this,'grid_area')" onfocus="showCursor(this,'grid_area')" onblur="hideCursor('grid_area')">
                        <div id="grid_area" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Village (2 rows) --}}
            <tr>
                <td class="lbl">Village/ Town/ City:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_village" maxlength="62" oninput="fill(this,'grid_village')" onfocus="showCursor(this,'grid_village')" onblur="hideCursor('grid_village')">
                        <div id="grid_village" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Post Office (2 rows) --}}
            <tr>
                <td class="lbl">Post Office:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_post" maxlength="62" oninput="fill(this,'grid_post')" onfocus="showCursor(this,'grid_post')" onblur="hideCursor('grid_post')">
                        <div id="grid_post" class="char-grid"></div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- District / State / PIN + Photo section (side by side) --}}
        <div class="bottom-addr-wrap">
            <div class="bottom-addr-left">
                <table class="form-tbl">
                    {{-- District (2 rows) --}}
                    <tr>
                        <td class="lbl">District:</td>
                        <td class="boxes-cell">
                            <div class="char-grid-wrap">
                                <input type="text" class="hidden-input" id="inp_district" maxlength="40" oninput="fill(this,'grid_district')" onfocus="showCursor(this,'grid_district')" onblur="hideCursor('grid_district')">
                                <div id="grid_district" class="char-grid"></div>
                            </div>
                        </td>
                    </tr>
                    {{-- State (2 rows) --}}
                    <tr>
                        <td class="lbl">State:</td>
                        <td class="boxes-cell">
                            <div class="char-grid-wrap">
                                <input type="text" class="hidden-input" id="inp_state" maxlength="40" oninput="fill(this,'grid_state')" onfocus="showCursor(this,'grid_state')" onblur="hideCursor('grid_state')">
                                <div id="grid_state" class="char-grid"></div>
                            </div>
                        </td>
                    </tr>
                    {{-- PIN Code --}}
                    <tr>
                        <td class="lbl">PIN Code:</td>
                        <td class="boxes-cell">
                            <div class="char-grid-wrap">
                                <input type="text" class="hidden-input" id="inp_pin" maxlength="6" oninput="fillNum(this,'grid_pin')" onfocus="showCursor(this,'grid_pin')" onblur="hideCursor('grid_pin')">
                                <div id="grid_pin" class="char-grid"></div>
                            </div>
                        </td>
                    </tr>
                </table>
                {{-- Signature box below PIN --}}
                <div class="sig-box-wrap">
                    <div class="sig-box"></div>
                    <div class="sig-box-label">Signature/ Thumb/ Finger Impression of<br>Individual Seeking to Enrol/ Aadhaar Number Holder</div>
                </div>
            </div>
            <div class="bottom-addr-right">
                <div class="photo-cell-block">
                    <div class="photo-text">Individual Seeking to Enrol/<br>Aadhaar Number Holder<br>Recent Colour Passport Size<br>Photograph.</div>
                    <div class="red-text"><em>Cross Signed and<br>Cross Stamped<br>by the Certifier.</em></div>
                    <div class="nb-text"><strong>NB:</strong> DO NOT OVERLAP<br>WITH TEXT BOXES.</div>
                </div>
            </div>
        </div>

        {{-- ═══════════════ CERTIFIER'S DETAILS ═══════════════ --}}
        <div class="section-bar orange">CERTIFIER'S DETAILS (TO BE FILLED BY THE CERTIFIER ONLY)</div>

        <table class="form-tbl">
            {{-- Name of Certifier --}}
            <tr>
                <td class="lbl">Name of the Certifier:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_certifier" maxlength="31" oninput="fill(this,'grid_certifier')" onfocus="showCursor(this,'grid_certifier')" onblur="hideCursor('grid_certifier')">
                        <div id="grid_certifier" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Designation --}}
            <tr>
                <td class="lbl">Designation:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_designation" maxlength="31" oninput="fill(this,'grid_designation')" onfocus="showCursor(this,'grid_designation')" onblur="hideCursor('grid_designation')">
                        <div id="grid_designation" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Office Address (2 rows) --}}
            <tr>
                <td class="lbl">Office Address:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_office" maxlength="62" oninput="fill(this,'grid_office')" onfocus="showCursor(this,'grid_office')" onblur="hideCursor('grid_office')">
                        <div id="grid_office" class="char-grid"></div>
                    </div>
                </td>
            </tr>
            {{-- Contact Number --}}
            <tr>
                <td class="lbl">Contact Number:</td>
                <td class="boxes-cell">
                    <div class="char-grid-wrap">
                        <input type="text" class="hidden-input" id="inp_contact" maxlength="10" oninput="fillNum(this,'grid_contact')" onfocus="showCursor(this,'grid_contact')" onblur="hideCursor('grid_contact')">
                        <div id="grid_contact" class="char-grid"></div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- ═══════════════ CERTIFICATION + CHECKLIST ═══════════════ --}}
        <div class="cert-bottom">
            <div class="cert-left">
                <div class="cert-heading">I hereby certify above mentioned details of the Individual seeking to enrol /Aadhaar number holder and I am a.... (Tick appropriate box below)</div>
                <label><input type="checkbox" class="cb-box"> MP / MLA / MLC / Municipal Councillor</label>
                <label><input type="checkbox" class="cb-box"> Gazetted Officer Group 'A'/ Employees Provident Fund Organisation (EPFO) Officer</label>
                <label><input type="checkbox" class="cb-box"> Tehsildar/ Gazetted Officer Group 'B'</label>
                <label><input type="checkbox" class="cb-box"> Gazetted Officer at National AIDS Control Organisation (NACO)/State Health Department / Project Director of the State AIDS Control Society or his nominee</label>
                <label><input type="checkbox" class="cb-box"> Head of recognised educational institution (only for the institute students concerned)</label>
                <label><input type="checkbox" class="cb-box"> Village Panchayat Head/ President or Mukhiya/ Gaon Bura/ equivalent authority (for rural areas)/ Village Panchayat Secretary/ Village Revenue Officer or equivalent (for rural areas)</label>
            </div>
            <div class="cert-right">
                <div class="checklist-box">
                    <div class="ck-title">CHECKLIST FOR CERTIFIER</div>
                    <label><input type="checkbox" class="cb-box"> No overwriting</label>
                    <label><input type="checkbox" class="cb-box"> Issue date is filled</label>
                    <label><input type="checkbox" class="cb-box"> Resident's signature</label>
                    <label><input type="checkbox" class="cb-box"> Certifier's details</label>
                    <div style="margin-top:2px;"><label><input type="checkbox" class="cb-box"> Resident's Photo is cross signed and cross stamped (paper to photo or photo to paper)</label></div>
                </div>
                <div class="stamp-box">Signature &amp; Stamp of the Certifier</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer-note">*To be used as Proof of Identity (PoI) only in specific cases as mentioned in the list of applicable supporting documents.</div>
        <div class="uf-footer"><em>Mera Aadhaar,</em> Meri Pehchaan</div>

    </div>{{-- end a4-page --}}
</div>

{{-- ═══════════════ RIGHT SIDEBAR ═══════════════ --}}
<div class="action-sidebar">
    <div class="sb-title">Address Fill</div>
    <div class="sb-divider"></div>
    <div class="sb-section">
        <div class="sb-label">Address Auto-fill</div>
        <select class="sb-select" id="addressSelector" onchange="fillAddressData()"><option value="">-- Select Address --</option></select>
        <a href="{{ route('aadhaar.village-info.index') }}" class="sb-link">+ Add New</a>
        <label style="display:flex;align-items:center;gap:6px;margin-top:10px;font-size:11px;font-weight:600;color:#333;cursor:pointer;">
            <input type="checkbox" id="certifierToggle" checked style="width:15px;height:15px;accent-color:#bf360c;cursor:pointer;">
            <span>Fill Certifier's Details</span>
        </label>
        <div id="certifierStatus" style="font-size:10px;color:#16a34a;margin-top:3px;font-weight:600;">✓ Certifier data will be filled</div>
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

// ═══ Grid definitions: { id: boxCount } ═══
const gridDefs = {
    grid_aadhaar: 12,
    grid_name: 62,
    grid_house: 31,
    grid_street: 31,
    grid_landmark: 62,
    grid_area: 62,
    grid_village: 62,
    grid_post: 62,
    grid_district: 40,
    grid_state: 40,
    grid_pin: 6,
    grid_certifier: 31,
    grid_designation: 31,
    grid_office: 62,
    grid_contact: 10
};

// ═══ Create character boxes ═══
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
function showCursor(input, gridId) { updateCursorPos(input, gridId); }
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

// ═══ Auto-fill date header ═══
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

// ═══ Populate address dropdown ═══
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

    const setAndFill = (inputId, gridId, val, isNum) => {
        let inp = document.getElementById(inputId);
        if (!inp) return;
        inp.value = isNum ? val.replace(/[^0-9]/g, '') : val.toUpperCase();
        renderBoxes(inp.value, gridId);
    };

    setAndFill('inp_village', 'grid_village', addr.village, false);
    setAndFill('inp_post', 'grid_post', addr.post_office, false);
    setAndFill('inp_district', 'grid_district', addr.district, false);
    setAndFill('inp_state', 'grid_state', addr.state, false);
    setAndFill('inp_pin', 'grid_pin', addr.pincode, true);

    // Certifier details — only fill if checkbox is checked
    const fillCert = document.getElementById('certifierToggle').checked;
    if (fillCert) {
        if (addr.certifier_name) setAndFill('inp_certifier', 'grid_certifier', addr.certifier_name, false);
        if (addr.certifier_designation) setAndFill('inp_designation', 'grid_designation', addr.certifier_designation, false);
        if (addr.certifier_contact) setAndFill('inp_contact', 'grid_contact', addr.certifier_contact, true);
        if (addr.certifier_office_address) setAndFill('inp_office', 'grid_office', addr.certifier_office_address, false);
    }
}

// ═══ Certifier toggle status text ═══
document.getElementById('certifierToggle').addEventListener('change', function() {
    const status = document.getElementById('certifierStatus');
    if (this.checked) {
        status.textContent = '✓ Certifier data will be filled';
        status.style.color = '#16a34a';
    } else {
        status.textContent = '✗ Certifier data will NOT be filled';
        status.style.color = '#dc2626';
    }
});

// ═══ Auto-advance for date d-boxes ═══
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

// ═══ Pay & Print ═══
function payAndPrint() {
    const nameInp = document.getElementById('inp_name');
    if (!nameInp || nameInp.value.trim() === '') { alert('Please enter Full Name first.'); return; }
    if (!confirm('₹5 will be deducted from your wallet. Print now?')) return;

    fetch('/aadhaar/pay', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ form_type: 'update' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            document.getElementById('walletDisplay').textContent = data.new_balance;
            document.getElementById('watermark-layer').style.display = 'none';
            let name = nameInp.value.trim();
            let oldTitle = document.title;
            document.title = name.replace(/\s+/g,'_') + '_Aadhaar_Update_Form';
            setTimeout(() => {
                window.print();
                setTimeout(() => { document.title = oldTitle; location.reload(); }, 2000);
            }, 500);
        } else { alert(data.message || 'Payment failed.'); }
    })
    .catch(() => alert('Network error. Please try again.'));
}
</script>
</body>
</html>
