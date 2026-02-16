<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="utf-8">
    <title>उत्पन्न प्रमाणपत्र — प्रिंट</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Noto Sans Devanagari', sans-serif; font-size: 13px; line-height: 1.8; background: #fff; color: #000; }
        .a4-page { width: 210mm; margin: 0 auto; padding: 15mm 18mm 15mm 22mm; border: 2px solid #000; min-height: 297mm; position: relative; }
        h1 { text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 3px; text-decoration: underline; }
        .subtitle { text-align: center; font-size: 13px; margin-bottom: 20px; }
        .field-underline { border-bottom: 1px dotted #333; padding: 0 6px; font-weight: 600; min-width: 80px; display: inline-block; }
        .body-text { text-align: justify; font-size: 13px; line-height: 2; }
        .income-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .income-table th, .income-table td { border: 1px solid #000; padding: 6px 10px; font-size: 12px; text-align: center; }
        .income-table th { background: #f0f0f0; font-weight: 700; }
        .photo-box { width: 30mm; height: 35mm; border: 1px solid #000; float: right; margin-left: 10px; display: flex; align-items: center; justify-content: center; font-size: 10px; text-align: center; color: #999; }
        .sig-area { margin-top: 40px; display: flex; justify-content: space-between; }
        .sig-box { text-align: center; width: 40%; }
        .sig-line { border-top: 1px solid #000; margin-top: 50px; padding-top: 4px; font-size: 11px; }
        .date-place { margin-top: 30px; font-size: 12px; }
        .print-btn { position: fixed; top: 10px; right: 10px; background: #d97706; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; z-index: 100; font-family: sans-serif; }
        @media print { .print-btn { display: none; } @page { size: A4 portrait; margin: 0; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ प्रिंट करा</button>
    <div class="a4-page">
        <div class="photo-box">फोटो<br>(Photo)</div>
        <h1>उत्पन्नाचे स्वयंघोषणापत्र</h1>
        <div class="subtitle">(Income Certificate Affidavit)</div>

        <div class="body-text">
            <p>मी खाली सही करणारा/करणारी <span class="field-underline">{{ $submission->applicant_name }}</span>,
            वडिलांचे/पतीचे नाव <span class="field-underline">{{ $data['father_name'] ?? '____' }}</span>,
            वय <span class="field-underline">{{ $data['age'] ?? '____' }}</span> वर्षे,
            व्यवसाय <span class="field-underline">{{ $data['occupation'] ?? '____' }}</span></p>

            <p>रा. गाव <span class="field-underline">{{ $data['village'] ?? '____' }}</span>,
            ता. <span class="field-underline">{{ $data['taluka'] ?? '____' }}</span>,
            जि. <span class="field-underline">{{ $data['district'] ?? '____' }}</span></p>

            <p style="margin-top:10px;">
                मी याद्वारे सत्यप्रतिज्ञेवर घोषित करतो/करते की, माझे/माझ्या कुटुंबाचे सर्व मार्गांनी मिळणारे
                एकूण वार्षिक उत्पन्न खालीलप्रमाणे आहे:
            </p>
        </div>

        <table class="income-table">
            <thead>
                <tr>
                    <th>अ.क्र.</th>
                    <th>आर्थिक वर्ष</th>
                    <th>वार्षिक उत्पन्न (₹)</th>
                    <th>अक्षरी</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ date('Y') - 1 }}-{{ date('y') }}</td>
                    <td>₹{{ number_format($data['annual_income'] ?? 0) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="body-text" style="margin-top:10px;">
            <p><strong>कारण:</strong> {{ $data['reason'] ?? '____' }}</p>
            <p style="margin-top:10px;">वरील सर्व माहिती माझ्या जाणिवेनुसार खरी व बरोबर आहे. जर सदर माहिती खोटी आढळली तर मी कायदेशीर कारवाईस पात्र राहील.</p>
        </div>

        <div class="date-place">
            <p>ठिकाण: ________________</p>
            <p>दिनांक: {{ isset($data['date']) ? \Carbon\Carbon::parse($data['date'])->format('d/m/Y') : $submission->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="sig-area">
            <div class="sig-box"><div class="sig-line">अर्जदाराची सही<br><small>{{ $submission->applicant_name }}</small></div></div>
            <div class="sig-box" style="width:25mm; height:25mm; border:1px solid #000; text-align:center; font-size:10px; color:#999; line-height:25mm;">सही/अंगठा</div>
            <div class="sig-box"><div class="sig-line">ठिकाण व तारीख</div></div>
        </div>
    </div>
</body>
</html>
