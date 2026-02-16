<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="utf-8">
    <title>नवीन अर्ज — प्रिंट</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Noto Sans Devanagari', sans-serif; font-size: 14px; line-height: 1.8; background: #fff; color: #000; }
        .a4-page { width: 210mm; margin: 0 auto; padding: 20mm 20mm 20mm 25mm; border: 2px solid #000; min-height: 297mm; position: relative; }
        h1 { text-align: center; font-size: 22px; font-weight: 700; margin-bottom: 5px; text-decoration: underline; }
        .subtitle { text-align: center; font-size: 13px; margin-bottom: 25px; }
        .to-section { margin-bottom: 20px; font-size: 14px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 6px 10px; border: 1px solid #ccc; font-size: 13px; }
        .info-table td:first-child { font-weight: 600; width: 35%; background: #f9f9f9; }
        .body-text { text-align: justify; line-height: 2; margin-top: 15px; }
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
        <h1>नवीन अर्ज</h1>
        <div class="subtitle">(New Application)</div>

        <div class="to-section">
            <p>प्रति,</p>
            <p>मा. _______________________</p>
            <p>_______________________</p>
        </div>

        <p style="font-weight:600;">विषय: {{ $data['application_type'] ?? 'सामान्य अर्ज' }}</p>

        <table class="info-table" style="margin-top:15px;">
            <tr><td>अर्जदाराचे नाव</td><td>{{ $submission->applicant_name }}</td></tr>
            <tr><td>वय</td><td>{{ $data['age'] ?? '-' }}</td></tr>
            <tr><td>व्यवसाय</td><td>{{ $data['occupation'] ?? '-' }}</td></tr>
            <tr><td>मोबाईल</td><td>{{ $data['mobile'] ?? '-' }}</td></tr>
            <tr><td>पत्ता</td><td>{{ $data['address'] ?? '-' }}</td></tr>
            <tr><td>तारीख</td><td>{{ isset($data['date']) ? \Carbon\Carbon::parse($data['date'])->format('d/m/Y') : $submission->created_at->format('d/m/Y') }}</td></tr>
        </table>

        @if(!empty($data['description']))
        <div class="body-text">
            <p><strong>वर्णन:</strong></p>
            <p>{{ $data['description'] }}</p>
        </div>
        @endif

        <div class="signature-area">
            <div class="sig-box"><div class="sig-line">अर्जदाराची सही<br><small>{{ $submission->applicant_name }}</small></div></div>
            <div class="sig-box"><div class="sig-line">ठिकाण व तारीख</div></div>
        </div>
    </div>
</body>
</html>
