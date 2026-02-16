<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>शेतकरी ओळखपत्र — Farmer ID Card</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.js"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', 'Noto Sans Devanagari', sans-serif; background: #E8E8E8; color: #1a1a1a; }

        .action-bar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; gap: 10px; padding: 12px 24px;
            background: linear-gradient(135deg, #7C3AED, #6D28D9);
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
        }
        .action-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px; border-radius: 8px; border: none;
            font-size: 13px; font-weight: 600; cursor: pointer;
            font-family: 'Noto Sans Devanagari', sans-serif;
            transition: all 0.15s ease; text-decoration: none; color: #fff;
        }
        .btn-dashboard { background: #F59E0B; }
        .btn-back { background: #6366F1; }
        .btn-print { background: #10B981; }

        .page-container { max-width: 700px; margin: 80px auto 40px; padding: 0 16px; }

        /* ═══ CARD ROW — Front + Back side by side ═══ */
        .card-row { display: flex; gap: 6mm; justify-content: center; flex-wrap: wrap; }

        .card {
            width: 8.56cm; height: 5.40cm;
            border-radius: 2.5mm; overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.18);
            position: relative; flex-shrink: 0;
        }

        /* ── FRONT ── */
        .card-front { position: relative; padding: 2.5mm 3mm 0; display: flex; flex-direction: column; }
        .card-front::before {
            content: ''; position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 60px 100px at 92% 90%, rgba(180,145,60,0.20) 0%, transparent 70%),
                radial-gradient(ellipse 50px 80px at 8% 90%, rgba(180,145,60,0.15) 0%, transparent 70%),
                linear-gradient(180deg, #4CAF50 0%, #66BB6A 6%, #81C784 12%, #A5D6A7 20%, #C8E6C9 28%, #E8F5E9 38%, #F1F8E9 50%, #FFFDE7 65%, #FFF8E1 78%, #FFE0B2 90%, #FFCC80 100%);
        }
        .card-front > * { position: relative; z-index: 1; }
        .front-hdr {
            display: flex; align-items: center; justify-content: space-between;
            padding-bottom: 1.2mm; margin-bottom: 1.5mm;
            border-bottom: 0.3mm solid rgba(27,94,32,0.25);
        }
        .front-hdr-left { display: flex; align-items: center; gap: 1.5mm; }
        .front-hdr-icon {
            width: 5mm; height: 5mm; background: #1B5E20; border-radius: 1mm;
            display: flex; align-items: center; justify-content: center; font-size: 3mm; line-height: 1;
        }
        .front-hdr-mr { font-size: 7pt; font-weight: 700; color: #1B5E20; font-family: 'Noto Sans Devanagari', sans-serif; }
        .front-hdr-en { font-size: 6pt; font-weight: 800; color: #2E7D32; letter-spacing: 1px; text-transform: uppercase; }
        .front-body { flex: 1; display: flex; gap: 2.5mm; }
        .photo-col { display: flex; flex-direction: column; align-items: center; gap: 1.5mm; flex-shrink: 0; width: 20mm; }
        .photo-box {
            width: 18mm; height: 22mm; border: 0.4mm solid #fff; border-radius: 1.5mm;
            overflow: hidden; background: #e8e8e0; box-shadow: 0 0.5mm 2mm rgba(0,0,0,0.12);
        }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .photo-box .no-photo {
            width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
            font-size: 5pt; color: #999; text-align: center; line-height: 1.4;
        }
        .qr-box {
            width: 14mm; height: 14mm; background: #fff; border-radius: 1mm;
            padding: 0.5mm; box-shadow: 0 0.3mm 1mm rgba(0,0,0,0.1); overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .qr-box svg { width: 100%; height: 100%; display: block; }
        .details-col { flex: 1; display: flex; flex-direction: column; justify-content: flex-start; padding-top: 0.5mm; }
        .name-mr {
            font-family: 'Noto Sans Devanagari', sans-serif;
            font-size: 8pt; font-weight: 700; color: #1B5E20; line-height: 1.3; margin-bottom: 0.3mm;
        }
        .name-en { font-size: 6.5pt; font-weight: 500; color: #444; margin-bottom: 1.5mm; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2mm 2mm; }
        .info-lbl { font-size: 4.5pt; font-weight: 700; color: #777; text-transform: uppercase; letter-spacing: 0.3px; }
        .info-val { font-size: 6pt; font-weight: 600; color: #1a1a1a; }
        .info-full { grid-column: 1 / -1; }
        .info-val-sm { font-size: 5pt; font-weight: 500; color: #333; line-height: 1.4; }
        .id-strip {
            background: linear-gradient(90deg, #1B5E20, #2E7D32, #1B5E20);
            margin: auto -3mm 0; padding: 1.2mm 3mm; text-align: center;
        }
        .id-strip-text { font-size: 6pt; font-weight: 800; color: #fff; letter-spacing: 1.5px; }

        /* ── BACK ── */
        .card-back { position: relative; padding: 2.5mm 3mm; display: flex; flex-direction: column; }
        .card-back::before {
            content: ''; position: absolute; inset: 0; z-index: 0;
            background: linear-gradient(180deg, #E8F5E9 0%, #F1F8E9 25%, #FFFDE7 55%, #FFF8E1 80%, #FFE0B2 100%);
        }
        .card-back > * { position: relative; z-index: 1; }
        .back-hdr {
            font-family: 'Noto Sans Devanagari', sans-serif;
            font-size: 6.5pt; font-weight: 700; color: #1B5E20; text-align: center;
            border-bottom: 0.3mm solid #2E7D32; padding-bottom: 1mm; margin-bottom: 1.5mm;
        }
        .back-table { width: 100%; border-collapse: collapse; }
        .back-table th {
            background: rgba(76,175,80,0.15); padding: 0.8mm 1.2mm;
            font-size: 4.5pt; font-weight: 700; color: #1B5E20; text-align: left;
            border-bottom: 0.3mm solid #81C784;
        }
        .back-table td { padding: 0.8mm 1.2mm; font-size: 5pt; color: #333; border-bottom: 0.15mm solid #E0E0E0; }
        .back-table td.fw { font-weight: 600; }
        .back-table .empty-cell { color: #ccc; }
        .back-total { text-align: right; font-size: 5.5pt; font-weight: 700; color: #1B5E20; padding-top: 1.5mm; margin-top: auto; }
        .back-id { text-align: center; font-size: 5pt; font-weight: 700; color: #2E7D32; margin-top: 1mm; padding-top: 1mm; border-top: 0.2mm solid rgba(0,0,0,0.1); }
        .back-disclaimer { text-align: center; font-size: 3.5pt; color: #999; line-height: 1.5; margin-top: auto; }

        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            body { background: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .action-bar { display: none !important; }
            .page-container { margin: 0; padding: 0; max-width: none; }
            .card { box-shadow: none !important; border: 0.2mm solid #999; page-break-inside: avoid; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    @php
        $d = $data ?? [];
        $farmerId = $d['farmer_id'] ?? str_pad($submission->id, 11, '0', STR_PAD_LEFT);
        $formattedFarmerId = trim(chunk_split($farmerId, 4, ' '));
        $aadhaar = $d['aadhaar'] ?? '';
        $maskedAadhaar = strlen(preg_replace('/\s/', '', $aadhaar)) >= 8
            ? 'XXXX XXXX ' . substr(preg_replace('/\s/', '', $aadhaar), -4)
            : ($aadhaar ?: '—');
        $dob = isset($d['dob']) ? \Carbon\Carbon::parse($d['dob'])->format('d/m/Y') : '—';
        $lands = $d['land'] ?? [];
        $totalArea = 0;
        foreach ($lands as $l) { $totalArea += (float)($l['area'] ?? 0); }

        $photoUrl = null;
        if (!empty($d['photo'])) {
            $photoUrl = str_starts_with($d['photo'], 'http') ? $d['photo'] : asset('storage/' . $d['photo']);
        } elseif (!empty($d['photo_from_pdf'])) {
            $photoUrl = $d['photo_from_pdf'];
        }

        if (!empty($d['lives_at_farm']) && count($lands) > 0) {
            $firstLand = $lands[0];
            $fullAddress = implode(', ', array_filter([$firstLand['village'] ?? '', $firstLand['taluka'] ?? '', $firstLand['district'] ?? '', 'महाराष्ट्र']));
        } elseif (!empty($d['address_line1'])) {
            $fullAddress = implode(', ', array_filter([$d['address_line1'] ?? '', $d['address_village'] ?? '', $d['address_taluka'] ?? '', $d['address_district'] ?? '', $d['address_state'] ?? 'महाराष्ट्र', $d['address_pincode'] ?? '']));
        } else {
            $fullAddress = $d['address'] ?? '—';
        }

        $qrText = "नाव: " . $submission->applicant_name . "\nName: " . ($d['name_english'] ?? $submission->applicant_name) . "\nDOB: " . $dob . "\nGender: " . ($d['gender'] ?? '-') . "\nMobile: " . ($d['mobile'] ?? '-') . "\nAadhaar: " . $maskedAadhaar . "\nFarmer ID: " . $farmerId . "\nAddress: " . $fullAddress;
    @endphp

    <div class="action-bar">
        <a href="{{ route('dashboard') }}" class="action-btn btn-dashboard">🏠 डॅशबोर्ड</a>
        <a href="{{ url()->previous() }}" class="action-btn btn-back">&larr; मागे</a>
        <button class="action-btn btn-print" onclick="window.print()">🖨️ प्रिंट</button>
    </div>

    <div class="page-container">
        <div class="card-row">
            {{-- ═══ FRONT ═══ --}}
            <div class="card card-front">
                <div class="front-hdr">
                    <div class="front-hdr-left">
                        <div class="front-hdr-icon">🌾</div>
                        <div class="front-hdr-mr">शेतकरी ओळखपत्र</div>
                    </div>
                    <div class="front-hdr-en">Farmer ID Card</div>
                </div>
                <div class="front-body">
                    <div class="photo-col">
                        <div class="photo-box">
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="Photo">
                            @else
                                <div class="no-photo">No<br>Photo</div>
                            @endif
                        </div>
                        <div class="qr-box" id="qr_single"></div>
                    </div>
                    <div class="details-col">
                        <div class="name-mr">{{ $submission->applicant_name }}</div>
                        <div class="name-en">{{ $d['name_english'] ?? $submission->applicant_name }}</div>
                        <div class="info-grid">
                            <div><div class="info-lbl">जन्म दिनांक / DOB</div><div class="info-val">{{ $dob }}</div></div>
                            <div><div class="info-lbl">लिंग / Gender</div><div class="info-val">{{ $d['gender'] ?? '—' }}</div></div>
                            <div><div class="info-lbl">मोबाईल / Mobile</div><div class="info-val">{{ $d['mobile'] ?? '—' }}</div></div>
                            <div><div class="info-lbl">आधार नं / Aadhaar</div><div class="info-val">{{ $maskedAadhaar }}</div></div>
                            <div class="info-full"><div class="info-lbl">पत्ता / Address</div><div class="info-val info-val-sm">{{ $fullAddress }}</div></div>
                        </div>
                    </div>
                </div>
                <div class="id-strip"><div class="id-strip-text">FARMER ID: {{ $formattedFarmerId }}</div></div>
            </div>

            {{-- ═══ BACK ═══ --}}
            <div class="card card-back">
                <div class="back-hdr">जमिनीची माहिती / Land Details</div>
                <table class="back-table">
                    <thead><tr><th>जिल्हा</th><th>तालुका</th><th>गाव</th><th>गट नं.</th><th>खाते नं.</th><th style="text-align:right">क्षेत्र (हे.)</th></tr></thead>
                    <tbody>
                        @forelse($lands as $land)
                        <tr>
                            <td class="fw">{{ $land['district'] ?? '—' }}</td>
                            <td>{{ $land['taluka'] ?? '—' }}</td>
                            <td>{{ $land['village'] ?? '—' }}</td>
                            <td>{{ $land['gat_no'] ?? '—' }}</td>
                            <td>{{ $land['khate_no'] ?? '—' }}</td>
                            <td class="fw" style="text-align:right">{{ number_format((float)($land['area'] ?? 0), 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="empty-cell" style="text-align:center;padding:2mm">— माहिती नाही —</td></tr>
                        @endforelse
                        @for($i = count($lands); $i < 3; $i++)
                        <tr><td class="empty-cell">&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr>
                        @endfor
                    </tbody>
                </table>
                <div class="back-total">एकूण / Total: {{ number_format($totalArea, 2) }} हेक्टर</div>
                <div class="back-id">FARMER ID: {{ $formattedFarmerId }}</div>
                <div class="back-disclaimer">वरील माहिती शेतकऱ्याने स्वतः दिलेली आहे. ही शासकीय ओळखपत्र नाही.<br>For personal use only. Not a government-issued ID.</div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function makeQR(el, text) {
            if (!el) return;
            try {
                var qr = qrcode(0, 'M');
                qr.addData(text);
                qr.make();
                el.innerHTML = qr.createSvgTag({ cellSize: 2, margin: 0, scalable: true });
                var svg = el.querySelector('svg');
                if (svg) { svg.setAttribute('width', '100%'); svg.setAttribute('height', '100%'); svg.style.display = 'block'; }
            } catch(e) { console.error('QR error:', e); }
        }
        makeQR(document.getElementById('qr_single'), @json($qrText));
    });
    </script>
</body>
</html>
