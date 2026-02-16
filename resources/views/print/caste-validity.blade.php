<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="utf-8">
    <title>जात पडताळणी — प्रिंट</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Noto Sans Devanagari', sans-serif; font-size: 14px; line-height: 2; background: #fff; color: #000; }
        .a4-page { width: 210mm; margin: 0 auto; padding: 20mm 20mm 20mm 25mm; border: 2px solid #000; min-height: 297mm; position: relative; }
        h1 { text-align: center; font-size: 22px; font-weight: 700; margin-bottom: 5px; text-decoration: underline; }
        .subtitle { text-align: center; font-size: 14px; margin-bottom: 25px; }
        .body-text { text-align: justify; font-size: 14px; line-height: 2.2; }
        .field-underline { border-bottom: 1px dotted #333; padding: 0 8px; font-weight: 600; min-width: 120px; display: inline-block; }
        .oath-list { padding-left: 25px; margin-top: 15px; }
        .oath-list li { margin-bottom: 8px; }
        .signature-area { position: absolute; bottom: 30mm; left: 25mm; right: 20mm; display: flex; justify-content: space-between; }
        .sig-box { text-align: center; width: 45%; }
        .sig-line { border-top: 1px solid #000; margin-top: 60px; padding-top: 5px; font-size: 12px; }
        .date-place { margin-top: 40px; font-size: 13px; }
        .print-btn { position: fixed; top: 10px; right: 10px; background: #d97706; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; z-index: 100; font-family: sans-serif; }
        @media print { .print-btn { display: none; } @page { size: A4 portrait; margin: 0; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ प्रिंट करा</button>
    <div class="a4-page">
        <h1>जात पडताळणी शपथपत्र</h1>
        <div class="subtitle">(Caste Validity Affidavit)</div>

        <div class="body-text">
            <p>मी खाली सही करणारा/करणारी <span class="field-underline">{{ $submission->applicant_name }}</span>,
            वय <span class="field-underline">{{ $data['age'] ?? '____' }}</span> वर्षे,
            व्यवसाय <span class="field-underline">{{ $data['occupation'] ?? '____' }}</span>,
            रा. <span class="field-underline">{{ $data['address'] ?? '____' }}</span></p>

            <p style="margin-top: 15px;">
                मी याद्वारे सत्यप्रतिज्ञेवर घोषित करतो/करते की, माझी जात
                <span class="field-underline">{{ $data['caste'] ?? '____' }}</span>
                @if(!empty($data['sub_caste']))
                (उपजात: <span class="field-underline">{{ $data['sub_caste'] }}</span>)
                @endif
                असून ही जात अनुसूचित जाती / अनुसूचित जमाती / विमुक्त जाती / भटक्या जमाती / इतर मागासवर्ग यापैकी _________________ प्रवर्गात समाविष्ट आहे.
            </p>
        </div>

        <div style="margin-top: 20px;">
            <p><strong>मी याद्वारे प्रतिज्ञापूर्वक घोषित करतो/करते की:</strong></p>
            <ol class="oath-list">
                <li>वरील जातीची माहिती सत्य व बरोबर आहे.</li>
                <li>मी खोटे जात प्रमाणपत्र सादर केलेले नाही.</li>
                <li>माझ्या जात प्रमाणपत्राची पडताळणी कोणत्याही सक्षम प्राधिकरणाकडून करता येईल.</li>
                <li>जर सदर माहिती खोटी आढळली तर मी कायदेशीर कारवाईस पात्र राहील.</li>
            </ol>
        </div>

        <div class="date-place">
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
