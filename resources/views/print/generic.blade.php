<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="utf-8">
    <title>{{ $submission->form_type }} — प्रिंट</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Noto Sans Devanagari', sans-serif; font-size: 13px; line-height: 1.8; background: #fff; }
        .a4-page { width: 92%; max-width: 210mm; margin: 10mm auto; padding: 20mm 20mm 20mm 25mm; border: 2px solid #000; min-height: 270mm; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .field { margin-bottom: 8px; }
        .field-label { font-weight: 700; display: inline; }
        .field-value { border-bottom: 1px dotted #333; padding: 0 5px; display: inline; }
        .signature-area { margin-top: 60px; display: flex; justify-content: space-between; }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { border-top: 1px solid #000; margin-top: 50px; padding-top: 5px; }
        .disclaimer { margin-top: 30px; padding: 10px; border: 1px solid #ccc; font-size: 11px; line-height: 1.6; }
        .print-btn { position: fixed; top: 10px; right: 10px; background: #d97706; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-size: 14px; z-index: 100; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ प्रिंट करा</button>
    <div class="a4-page">
        <h1>{{ str_replace('_', ' ', strtoupper($submission->form_type)) }}</h1>
        <div style="text-align:center; margin-bottom:15px; font-size:12px; color:#666;">
            फॉर्म क्र.: {{ $submission->id }} | तारीख: {{ $submission->created_at->format('d/m/Y') }}
        </div>

        <div class="field">
            <span class="field-label">अर्जदाराचे नाव:</span>
            <span class="field-value">{{ $submission->applicant_name }}</span>
        </div>

        @if(is_array($data))
            @foreach($data as $key => $value)
                @if(!in_array($key, ['_token', '_method']) && $value)
                <div class="field">
                    <span class="field-label">{{ str_replace('_', ' ', ucfirst($key)) }}:</span>
                    <span class="field-value">{{ is_array($value) ? json_encode($value) : $value }}</span>
                </div>
                @endif
            @endforeach
        @endif

        <div class="disclaimer">
            <strong>टीप:</strong> हे प्रमाणपत्र / अर्ज SETU Suvidha प्लॅटफॉर्मवर तयार केले आहे. वरील माहिती अर्जदाराने स्वतः दिली आहे. या माहितीच्या अचूकतेसाठी अर्जदार जबाबदार आहे.
        </div>

        <div class="signature-area">
            <div class="signature-box">
                <div class="signature-line">अर्जदाराची सही</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">ठिकाण व तारीख</div>
            </div>
        </div>
    </div>
</body>
</html>
