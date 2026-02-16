<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aadhaar Services Hub — SETU Suvidha</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.1/dist/umd/lucide.min.js"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #1e293b; min-height: 100vh; }

        /* Header */
        .hub-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 50%, #1e40af 100%);
            padding: 40px 24px 48px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hub-header::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.08) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.06) 0%, transparent 50%);
        }
        .header-content { position: relative; z-index: 2; max-width: 800px; margin: 0 auto; }
        .header-icon {
            width: 64px; height: 64px; border-radius: 50%;
            background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);
            display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px;
        }
        .header-icon svg { width: 32px; height: 32px; color: #fff; }
        .hub-header h1 { font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .hub-header p { font-size: 15px; color: rgba(255,255,255,0.8); }

        .header-actions {
            position: absolute; top: 20px; right: 24px; z-index: 3;
            display: flex; gap: 10px;
        }
        .btn-outline {
            padding: 8px 18px; border-radius: 8px; font-size: 13px; font-weight: 600;
            text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.2s; cursor: pointer; border: none;
        }
        .btn-outline-white {
            background: transparent; border: 1.5px solid rgba(255,255,255,0.4); color: #fff;
        }
        .btn-outline-white:hover { background: rgba(255,255,255,0.1); border-color: #fff; }
        .btn-whatsapp {
            background: #25D366; color: #fff;
        }
        .btn-whatsapp:hover { background: #1da851; }

        /* Cards Grid */
        .cards-container { max-width: 1000px; margin: -32px auto 0; padding: 0 24px; position: relative; z-index: 5; }
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        @media (max-width: 900px) { .cards-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 540px) { .cards-grid { grid-template-columns: 1fr; } }

        .form-card {
            background: #fff; border-radius: 16px; padding: 28px 20px;
            text-align: center; text-decoration: none; color: inherit;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
            border-bottom: 4px solid transparent;
            display: block;
        }
        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
        }
        .form-card.blue { border-bottom-color: #2563eb; }
        .form-card.yellow { border-bottom-color: #eab308; }
        .form-card.green { border-bottom-color: #16a34a; }
        .form-card.red { border-bottom-color: #dc2626; }

        .card-icon {
            width: 56px; height: 56px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 14px;
        }
        .card-icon svg { width: 26px; height: 26px; }
        .card-icon.blue { background: #dbeafe; color: #2563eb; }
        .card-icon.yellow { background: #fef9c3; color: #a16207; }
        .card-icon.green { background: #dcfce7; color: #16a34a; }
        .card-icon.red { background: #fee2e2; color: #dc2626; }

        .form-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 6px; }
        .card-badge {
            display: inline-block; padding: 2px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 700; margin-bottom: 10px;
        }
        .card-badge.blue { background: #dbeafe; color: #1d4ed8; }
        .card-badge.yellow { background: #fef9c3; color: #92400e; }
        .card-badge.green { background: #dcfce7; color: #166534; }
        .card-badge.red { background: #fee2e2; color: #991b1b; }

        .form-card p { font-size: 12px; color: #64748b; line-height: 1.5; }

        /* Footer */
        .hub-footer { text-align: center; padding: 40px 24px 32px; }
        .btn-dashboard {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 28px; border-radius: 10px;
            background: #1e293b; color: #fff; font-size: 14px; font-weight: 600;
            text-decoration: none; transition: background 0.2s;
        }
        .btn-dashboard:hover { background: #0f172a; }
        .btn-dashboard svg { width: 18px; height: 18px; }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="hub-header">
        <div class="header-actions">
            <a href="{{ route('aadhaar.village-info.index') }}" class="btn-outline btn-outline-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                Add Village Info
            </a>
            <a href="https://wa.me/919999999999?text=Aadhaar%20Hub%20Help" target="_blank" class="btn-outline btn-whatsapp">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Help / Demo
            </a>
        </div>

        <div class="header-content">
            <div class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12C2 6.5 6.5 2 12 2a10 10 0 0 1 8 4"/><path d="M5 19.5C5.5 18 6 15 6 12c0-.7.12-1.37.34-2"/><path d="M17.29 21.02c.12-.6.43-2.3.5-3.02"/><path d="M12 10a2 2 0 0 0-2 2c0 1.02-.1 2.51-.26 4"/><path d="M8.65 22c.21-.66.45-1.32.57-2"/><path d="M14 13.12c0 2.38 0 6.38-1 8.88"/><path d="M2 16h.01"/><path d="M21.8 16c.2-2 .131-5.354 0-6"/><path d="M9 6.8a6 6 0 0 1 9 5.2v2"/></svg>
            </div>
            <h1>Aadhaar Services Hub</h1>
            <p>Select the type of Aadhaar form you want to generate</p>
        </div>
    </div>

    {{-- Form Type Cards --}}
    <div class="cards-container">
        <div class="cards-grid">
            {{-- Adult Form --}}
            <a href="{{ route('aadhaar.adult-form') }}" class="form-card blue">
                <div class="card-icon blue">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <h3>Adult Form</h3>
                <div class="card-badge blue">18+ Years</div>
                <p>Generate New Enrolment & Update forms for Adults instantly.</p>
            </a>

            {{-- Minor Form --}}
            <a href="{{ route('aadhaar.minor-form') }}" class="form-card yellow">
                <div class="card-icon yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 6 3 12 0v-5"/></svg>
                </div>
                <h3>Minor Form</h3>
                <div class="card-badge yellow">5 – 18 Years</div>
                <p>Special form designed for school-going minors.</p>
            </a>

            {{-- Child Form --}}
            <a href="{{ route('aadhaar.child-form') }}" class="form-card green">
                <div class="card-icon green">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/></svg>
                </div>
                <h3>Child Form</h3>
                <div class="card-badge green">0 – 5 Years</div>
                <p>Create Blue Aadhaar (Bal Aadhaar) forms for infants.</p>
            </a>

            {{-- Update Form --}}
            <a href="{{ route('aadhaar.update-form') }}" class="form-card red">
                <div class="card-icon red">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                </div>
                <h3>Update Form</h3>
                <div class="card-badge red">Correction Only</div>
                <p>Form for Address change and other details correction.</p>
            </a>
        </div>
    </div>

    {{-- Footer --}}
    <div class="hub-footer">
        <a href="{{ route('dashboard') }}" class="btn-dashboard">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Back to Main Dashboard
        </a>
    </div>
</body>
</html>
