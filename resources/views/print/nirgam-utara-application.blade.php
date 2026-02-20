<!DOCTYPE html>
<html lang="mr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>निर्गम उतारा अर्ज नमुना — SETU Suvidha</title>
<style>
    @page { size: A4; margin: 14mm 16mm 18mm 16mm; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Noto Sans Devanagari', 'Mangal', 'Arial Unicode MS', sans-serif; font-size: 12.5px; color: #1a1a1a; line-height: 1.65; padding: 24px; max-width: 210mm; margin: 0 auto; background: #f8f9fa; }

    /* --- Print Controls --- */
    .no-print { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; padding: 16px 20px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); }
    .print-btn { background: linear-gradient(135deg, #f59e0b, #ea580c); color: #fff; border: none; padding: 10px 28px; font-size: 14px; font-weight: 700; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .print-btn:hover { filter: brightness(1.08); }
    .back-btn { background: #f3f4f6; color: #374151; border: none; padding: 10px 20px; font-size: 13px; font-weight: 600; border-radius: 10px; cursor: pointer; text-decoration: none; }
    .back-btn:hover { background: #e5e7eb; }
    .ctrl-note { font-size: 11px; color: #9ca3af; margin-left: auto; }

    /* --- Page Container --- */
    .page { background: #fff; padding: 36px 32px 28px; border-radius: 4px; box-shadow: 0 1px 4px rgba(0,0,0,.08); margin-bottom: 28px; page-break-after: always; position: relative; }
    .page:last-child { page-break-after: auto; }

    /* --- Page Header Bar --- */
    .page-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid #f59e0b; padding-bottom: 10px; margin-bottom: 22px; }
    .page-header .title-side { }
    .page-header .title-main { font-size: 15px; font-weight: 800; color: #111; }
    .page-header .title-sub { font-size: 10px; color: #888; font-weight: 500; margin-top: 1px; }
    .page-header .page-num { font-size: 10px; color: #aaa; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 3px 10px; font-weight: 700; }

    /* --- Application Styles (Page 1) --- */
    .app-date { text-align: right; margin-bottom: 14px; font-size: 12.5px; }
    .app-to { margin-bottom: 14px; }
    .app-to p { margin-bottom: 2px; }
    .app-subject { font-weight: 700; margin-bottom: 14px; font-size: 12.5px; background: #fffbeb; border-left: 4px solid #f59e0b; padding: 8px 12px; border-radius: 0 6px 6px 0; }
    .app-body { margin-bottom: 11px; text-align: justify; }
    .reason-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 16px; margin-bottom: 14px; font-size: 12px; }
    .reason-grid label { display: flex; align-items: center; gap: 5px; padding: 3px 0; }
    .reason-grid .reason-other { grid-column: 1 / -1; }
    .app-docs { margin-bottom: 14px; padding: 10px 14px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; }
    .app-docs p:first-child { font-weight: 700; font-size: 11.5px; margin-bottom: 6px; }
    .doc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3px 14px; font-size: 11.5px; color: #555; }
    .app-sign { margin-top: 28px; display: flex; justify-content: space-between; align-items: flex-end; }
    .sign-box { text-align: center; }
    .sign-line { display: block; border-top: 1px solid #555; width: 170px; margin: 36px auto 4px; }
    .sign-label { font-size: 10px; color: #777; }
    .blank { display: inline-block; border-bottom: 1.5px dotted #999; min-width: 180px; }
    .blank-sm { min-width: 70px; }
    .blank-lg { min-width: 280px; }

    /* --- Register Format Styles (Page 2) --- */
    .reg-school-header { text-align: center; margin-bottom: 18px; padding: 14px; background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 2px solid #fbbf24; border-radius: 10px; }
    .reg-school-header .sch-name { font-size: 14px; font-weight: 800; color: #92400e; letter-spacing: .3px; }
    .reg-school-header .sch-addr { font-size: 10.5px; color: #a16207; margin-top: 2px; }
    .reg-school-header .sch-line { width: 60px; height: 2px; background: #f59e0b; margin: 8px auto 0; border-radius: 2px; }

    .reg-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 12px; border-radius: 8px; overflow: hidden; border: 1.5px solid #d1d5db; }
    .reg-table tr:nth-child(odd) .reg-label { background: #fefce8; }
    .reg-table tr:nth-child(even) .reg-label { background: #fffbeb; }
    .reg-table td { padding: 9px 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
    .reg-table tr:last-child td { border-bottom: none; }
    .reg-sr { width: 32px; text-align: center; font-weight: 700; color: #92400e; font-size: 11px; background: #fef9c3 !important; border-right: 1px solid #e5e7eb; }
    .reg-label { width: 38%; font-weight: 700; color: #1f2937; border-right: 1px solid #e5e7eb; }
    .reg-label .en { font-size: 9.5px; color: #9ca3af; font-weight: 500; display: block; margin-top: 1px; }
    .reg-value { width: auto; min-height: 24px; color: #374151; }

    .seal-area { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 32px; padding-top: 8px; }
    .seal-box { text-align: center; width: 44%; }
    .seal-box .seal-border { width: 100%; height: 80px; border: 2px dashed #d1d5db; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 6px; }
    .seal-box .seal-border span { font-size: 10px; color: #ccc; }
    .seal-box .seal-title { font-size: 11px; font-weight: 700; color: #374151; }
    .seal-box .seal-sub { font-size: 9.5px; color: #9ca3af; }
    .cert-note { margin-top: 18px; text-align: center; font-size: 10px; color: #9ca3af; font-style: italic; padding: 10px; background: #f9fafb; border-radius: 6px; border: 1px dashed #e5e7eb; }

    /* --- Footer / Branding (both pages) --- */
    .page-footer { position: absolute; bottom: 16px; left: 32px; right: 32px; border-top: 1px solid #f3f4f6; padding-top: 8px; text-align: center; }
    .page-footer .brand { font-size: 10px; color: #aaa; }
    .page-footer .brand a { color: #f59e0b; text-decoration: none; font-weight: 700; }
    .page-footer .brand a:hover { text-decoration: underline; }
    .page-footer .note { font-size: 8.5px; color: #ccc; margin-top: 2px; }

    @media print {
        .no-print { display: none !important; }
        body { padding: 0; background: #fff; }
        .page { box-shadow: none; padding: 28px 24px 24px; margin-bottom: 0; border-radius: 0; }
        .page-footer .brand a { color: #d97706; }
    }
</style>
</head>
<body>

{{-- Print Controls --}}
<div class="no-print">
    <button class="print-btn" onclick="window.print()">🖨️ Print / PDF</button>
    <a href="{{ url('/reviews/nirgam-utara-download') }}" class="back-btn">← Back to Guide</a>
    <span class="ctrl-note">2 pages — Application + Nirgam Utara Format</span>
</div>

{{-- ═══════════════════════════════════════════════ --}}
{{-- PAGE 1 — APPLICATION (अर्ज)                    --}}
{{-- ═══════════════════════════════════════════════ --}}
<div class="page">

<div class="page-header">
    <div class="title-side">
        <div class="title-main">निर्गम उतारा मागणी अर्ज</div>
        <div class="title-sub">Application for Certified Extract from Admission Register</div>
    </div>
    <span class="page-num">Page 1 / 2</span>
</div>

<p class="app-date">दिनांक: ______/______/____________</p>

<div class="app-to">
<p><strong>प्रति,</strong></p>
<p>मा. मुख्याध्यापक / प्राचार्य,</p>
<p><span class="blank blank-lg">&nbsp;</span></p>
<p style="font-size:10px; color:#999;">(शाळेचे / महाविद्यालयाचे नाव व पूर्ण पत्ता)</p>
</div>

<div class="app-subject">विषय: प्रवेश-निर्गम नोंदवहीतील उतारा (निर्गम उतारा / निर्गम दाखला) देण्याबाबत विनंती अर्ज</div>

<p class="app-body">
महोदय / महोदया,
</p>
<p class="app-body">
सविनय विनंती अशी की, मी / माझे पाल्य <span class="blank blank-lg">&nbsp;</span> (विद्यार्थ्याचे पूर्ण नाव) हे/ही आपल्या शाळेत/महाविद्यालयात इयत्ता <span class="blank blank-sm">&nbsp;</span> ते इयत्ता <span class="blank blank-sm">&nbsp;</span> मध्ये शिक्षण घेत होतो/होती.
</p>

<p class="app-body">
प्रवेश क्रमांक (GR No.): <span class="blank">&nbsp;</span> <span style="font-size:10px;color:#999;">(माहीत असल्यास)</span><br>
शाळा सोडण्याचे वर्ष (अंदाजे): <span class="blank blank-sm">&nbsp;</span>
</p>

<p class="app-body" style="font-weight:700; margin-bottom:6px;">निर्गम उतारा आवश्यकतेचे कारण:</p>
<div class="reason-grid">
    <label>☐ TC / LC हरवल्यामुळे</label>
    <label>☐ जात प्रमाणपत्रासाठी</label>
    <label>☐ जात वैधतेसाठी</label>
    <label>☐ नोकरी पडताळणीसाठी</label>
    <label>☐ शिष्यवृत्तीसाठी</label>
    <label>☐ Duplicate TC साठी</label>
    <label>☐ आपले सरकार / सेतू सेवेसाठी</label>
    <label class="reason-other">☐ इतर: <span class="blank">&nbsp;</span></label>
</div>

<p class="app-body">
तरी आपणास विनंती आहे की, प्रवेश-निर्गम नोंदवहीतून (Admission Register / General Register) माझा / माझ्या पाल्याचा निर्गम उतारा (Certified Extract) अधिकृत शिक्का व सही सह देण्यात यावा. ही कृपा होय.
</p>

<div class="app-docs">
<p>सोबत जोडलेली कागदपत्रे:</p>
<div class="doc-grid">
    <label>☐ आधार कार्ड प्रत</label>
    <label>☐ गुणपत्रिका प्रत</label>
    <label>☐ शाळा ओळखपत्र</label>
    <label>☐ पालक विनंतीपत्र</label>
    <label>☐ इतर: ____________</label>
</div>
</div>

<div class="app-sign">
<div>
    <p style="font-size:11px; color:#777;">मोबाईल: <span class="blank blank-sm">&nbsp;</span></p>
    <p style="font-size:11px; color:#777; margin-top:3px;">पत्ता: <span class="blank blank-lg">&nbsp;</span></p>
</div>
<div class="sign-box">
    <span class="sign-line"></span>
    <div class="sign-label">(अर्जदाराचे नाव व सही)</div>
</div>
</div>

{{-- Page 1 Footer --}}
<div class="page-footer">
    <p class="brand">
        Format by <a href="https://setusuvidha.com/reviews/nirgam-utara-download">setusuvidha.com</a> — SETU Suvidha | सेतू सुविधा ई-सेवा पोर्टल
    </p>
    <p class="note">हा अर्ज नमुना मार्गदर्शनासाठी आहे. शाळेनुसार स्वरूपात फरक असू शकतो.</p>
</div>

</div>

{{-- ═══════════════════════════════════════════════ --}}
{{-- PAGE 2 — NIRGAM UTARA FORMAT (निर्गम उतारा)    --}}
{{-- ═══════════════════════════════════════════════ --}}
<div class="page">

<div class="page-header">
    <div class="title-side">
        <div class="title-main">निर्गम उतारा / प्रवेश-निर्गम नोंदवहीतील उतारा</div>
        <div class="title-sub">Certified Extract from Admission Register — School Leaving Record</div>
    </div>
    <span class="page-num">Page 2 / 2</span>
</div>

{{-- School Header --}}
<div class="reg-school-header">
    <div class="sch-name">______________________________________________________</div>
    <div class="sch-addr">(शाळेचे / महाविद्यालयाचे नाव, पत्ता व UDISE क्रमांक)</div>
    <div class="sch-line"></div>
</div>

{{-- Register Table --}}
<table class="reg-table">
<tr>
    <td class="reg-sr">१</td>
    <td class="reg-label">प्रवेश क्रमांक (GR No.) <span class="en">General Register / Admission Number</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">२</td>
    <td class="reg-label">विद्यार्थ्याचे पूर्ण नाव <span class="en">Student Full Name</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">३</td>
    <td class="reg-label">वडिलांचे / पालकांचे नाव <span class="en">Father's / Guardian's Name</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">४</td>
    <td class="reg-label">आईचे नाव <span class="en">Mother's Name</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">५</td>
    <td class="reg-label">जन्मतारीख (अंकी) <span class="en">Date of Birth (Figures)</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">६</td>
    <td class="reg-label">जन्मतारीख (अक्षरी) <span class="en">Date of Birth (Words)</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">७</td>
    <td class="reg-label">जात / धर्म <span class="en">Caste / Religion</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">८</td>
    <td class="reg-label">राष्ट्रीयत्व <span class="en">Nationality</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">९</td>
    <td class="reg-label">मागील शाळा <span class="en">Previous School (if any)</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">१०</td>
    <td class="reg-label">प्रवेश तारीख व वर्ग <span class="en">Date of Admission & Class</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">११</td>
    <td class="reg-label">निर्गम तारीख व वर्ग <span class="en">Date of Leaving & Class</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">१२</td>
    <td class="reg-label">शाळा सोडण्याचे कारण <span class="en">Reason for Leaving</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">१३</td>
    <td class="reg-label">वर्तणूक / शिस्त <span class="en">Conduct & Behaviour</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
<tr>
    <td class="reg-sr">१४</td>
    <td class="reg-label">शेरा <span class="en">Remarks</span></td>
    <td class="reg-value">&nbsp;</td>
</tr>
</table>

{{-- Seal & Signature Area --}}
<div class="seal-area">
    <div class="seal-box">
        <div class="seal-border"><span>शाळेचा शिक्का / School Seal</span></div>
        <div class="seal-title">शाळेचा शिक्का</div>
        <div class="seal-sub">School Seal / Stamp</div>
    </div>
    <div class="seal-box">
        <div class="seal-border"><span>सही / Signature</span></div>
        <div class="seal-title">मुख्याध्यापक / प्राचार्य</div>
        <div class="seal-sub">Principal's Signature & Date</div>
    </div>
</div>

{{-- Certification Note --}}
<div class="cert-note">
    प्रमाणित करण्यात येते की, वरील माहिती या शाळेच्या प्रवेश-निर्गम नोंदवहीतून (Admission Register) घेतलेली आहे व ती योग्य व अचूक आहे.<br>
    <em>Certified that the above information is a true extract from the Admission Register of this school.</em>
</div>

{{-- Page 2 Footer --}}
<div class="page-footer">
    <p class="brand">
        Format by <a href="https://setusuvidha.com/reviews/nirgam-utara-download">setusuvidha.com</a> — SETU Suvidha | सेतू सुविधा ई-सेवा पोर्टल
    </p>
    <p class="note">हा निर्गम उतारा नमुना मार्गदर्शनासाठी आहे. प्रत्यक्ष निर्गम उतारा फक्त शाळेच्या मुख्याध्यापकांकडूनच मिळतो.</p>
</div>

</div>

</body>
</html>
