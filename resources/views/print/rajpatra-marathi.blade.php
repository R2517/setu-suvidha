<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="utf-8">
    <title>राजपत्र मराठी — प्रिंट</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Noto Sans Devanagari', sans-serif; font-size: 14px; line-height: 1.8; background: #fff; color: #000; }
        .a4-page { width: 210mm; margin: 0 auto; padding: 20mm 20mm 20mm 25mm; border: 2px solid #000; min-height: 297mm; position: relative; }
        h1 { text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 3px; }
        .gov-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .gov-header p { font-size: 12px; }
        .name-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .name-table th, .name-table td { border: 1px solid #000; padding: 8px 12px; font-size: 13px; }
        .name-table th { background: #f0f0f0; font-weight: 700; text-align: center; }
        .name-table td { text-align: center; }
        .notice-text { text-align: justify; line-height: 2; margin: 20px 0; font-size: 13px; }
        .signature-area { position: absolute; bottom: 30mm; left: 25mm; right: 20mm; display: flex; justify-content: space-between; }
        .sig-box { text-align: center; width: 45%; }
        .sig-line { border-top: 1px solid #000; margin-top: 60px; padding-top: 5px; font-size: 12px; }
        .print-btn { position: fixed; top: 10px; right: 10px; background: #d97706; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; z-index: 100; font-family: sans-serif; }
        @media print { .print-btn { display: none; } @page { size: A4 portrait; margin: 0; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ प्रिंट करा</button>
    <div class="a4-page">
        <div class="gov-header">
            <h1>नाव बदलण्याचा नमुना</h1>
            <p>महाराष्ट्र शासन राजपत्र — भाग ४-अ</p>
            <p>(Name Change Gazette Notice — Maharashtra Government)</p>
        </div>

        <div class="notice-text">
            <p>मी, खाली सही करणारा/करणारी, याद्वारे जाहीर करतो/करते की, मी माझे नाव खालीलप्रमाणे बदलले आहे:</p>
        </div>

        <table class="name-table">
            <thead>
                <tr><th colspan="3">जुने नाव (Old Name)</th><th colspan="3">नवीन नाव (New Name)</th></tr>
                <tr><th>आडनाव</th><th>स्वत:चे नाव</th><th>वडिलांचे नाव</th><th>आडनाव</th><th>स्वत:चे नाव</th><th>वडिलांचे नाव</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data['old_surname'] ?? '' }}</td>
                    <td>{{ $data['old_first_name'] ?? '' }}</td>
                    <td>{{ $data['old_father_name'] ?? '' }}</td>
                    <td>{{ $data['new_surname'] ?? '' }}</td>
                    <td>{{ $data['new_first_name'] ?? '' }}</td>
                    <td>{{ $data['new_father_name'] ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="notice-text">
            @if(!empty($data['reason']))
            <p><strong>कारण:</strong> {{ $data['reason'] }}</p>
            @endif
            <p>सबब, सर्वांना याद्वारे कळविण्यात येते की, माझे नाव वरीलप्रमाणे बदलण्यात आले असून यापुढे मला नवीन नावाने ओळखण्यात यावे.</p>
            <p>जर कोणास या नाव बदलास हरकत असेल तर त्यांनी या प्रसिद्धीच्या तारखेपासून 14 दिवसांच्या आत खालील पत्त्यावर कळवावे.</p>
        </div>

        <div style="margin-top: 20px; font-size: 13px;">
            <p>ठिकाण: ________________</p>
            <p>दिनांक: {{ isset($data['date']) ? \Carbon\Carbon::parse($data['date'])->format('d/m/Y') : $submission->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="signature-area">
            <div class="sig-box"><div class="sig-line">अर्जदाराची सही<br><small>{{ $submission->applicant_name }}</small></div></div>
            <div class="sig-box"><div class="sig-line">ठिकाण व तारीख</div></div>
        </div>
    </div>
</body>
</html>
