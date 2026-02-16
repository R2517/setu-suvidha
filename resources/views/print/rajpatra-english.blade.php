<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Gazette Notice (English) — Print</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Georgia, serif; font-size: 14px; line-height: 1.8; background: #fff; color: #000; }
        .a4-page { width: 210mm; margin: 0 auto; padding: 20mm 20mm 20mm 25mm; border: 2px solid #000; min-height: 297mm; position: relative; }
        h1 { text-align: center; font-size: 20px; font-weight: 700; margin-bottom: 3px; }
        .gov-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .gov-header p { font-size: 12px; }
        .name-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .name-table th, .name-table td { border: 1px solid #000; padding: 8px 12px; font-size: 13px; text-transform: uppercase; }
        .name-table th { background: #f0f0f0; font-weight: 700; text-align: center; }
        .name-table td { text-align: center; font-weight: 600; }
        .notice-text { text-align: justify; line-height: 2; margin: 20px 0; font-size: 13px; }
        .signature-area { position: absolute; bottom: 30mm; left: 25mm; right: 20mm; display: flex; justify-content: space-between; }
        .sig-box { text-align: center; width: 45%; }
        .sig-line { border-top: 1px solid #000; margin-top: 60px; padding-top: 5px; font-size: 12px; }
        .print-btn { position: fixed; top: 10px; right: 10px; background: #d97706; color: #fff; border: none; padding: 10px 24px; border-radius: 8px; cursor: pointer; font-size: 14px; z-index: 100; font-family: sans-serif; }
        @media print { .print-btn { display: none; } @page { size: A4 portrait; margin: 0; } }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Print</button>
    <div class="a4-page">
        <div class="gov-header">
            <h1>CHANGE OF NAME NOTICE</h1>
            <p>Maharashtra Government Gazette — Part IV-A</p>
            <p>(Name Change Gazette Notice)</p>
        </div>

        <div class="notice-text">
            <p>I, the undersigned, hereby declare that I have changed my name as follows:</p>
        </div>

        <table class="name-table">
            <thead>
                <tr><th colspan="3">OLD NAME</th><th colspan="3">NEW NAME</th></tr>
                <tr><th>Surname</th><th>First Name</th><th>Father's Name</th><th>Surname</th><th>First Name</th><th>Father's Name</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ strtoupper($data['old_surname'] ?? '') }}</td>
                    <td>{{ strtoupper($data['old_first_name'] ?? '') }}</td>
                    <td>{{ strtoupper($data['old_father_name'] ?? '') }}</td>
                    <td>{{ strtoupper($data['new_surname'] ?? '') }}</td>
                    <td>{{ strtoupper($data['new_first_name'] ?? '') }}</td>
                    <td>{{ strtoupper($data['new_father_name'] ?? '') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="notice-text">
            @if(!empty($data['reason']))
            <p><strong>Reason:</strong> {{ $data['reason'] }}</p>
            @endif
            <p>All concerned are hereby informed that my name has been changed as stated above and henceforth I shall be known by my new name.</p>
            <p>Any person having objection to this change of name may communicate the same to the address given below within 14 days from the date of this publication.</p>
        </div>

        <div style="margin-top: 20px; font-size: 13px;">
            <p>Place: ________________</p>
            <p>Date: {{ isset($data['date']) ? \Carbon\Carbon::parse($data['date'])->format('d/m/Y') : $submission->created_at->format('d/m/Y') }}</p>
        </div>

        <div class="signature-area">
            <div class="sig-box"><div class="sig-line">Applicant's Signature<br><small>{{ strtoupper($submission->applicant_name) }}</small></div></div>
            <div class="sig-box"><div class="sig-line">Place & Date</div></div>
        </div>
    </div>
</body>
</html>
