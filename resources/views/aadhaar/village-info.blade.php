<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Village Info Manager — SETU Suvidha</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #1e293b; min-height: 100vh; }

        .page-wrap { display: flex; min-height: 100vh; }
        .content-area { flex: 1; padding: 0; overflow-y: auto; }
        .right-menu { width: 220px; min-width: 220px; background: #fff; border-left: 1px solid #e2e8f0; padding: 16px; overflow-y: auto; }

        /* Header */
        .page-header {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            padding: 20px 24px; color: #fff;
            display: flex; align-items: center; gap: 12px;
        }
        .page-header h1 { font-size: 20px; font-weight: 800; }
        .page-header svg { width: 24px; height: 24px; }

        /* Form */
        .add-form { padding: 16px 24px; background: #fff; border-bottom: 1px solid #e2e8f0; }
        .form-grid {
            display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end;
        }
        .form-group { display: flex; flex-direction: column; gap: 3px; flex: 1; min-width: 120px; }
        .form-group label { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .form-group input {
            padding: 8px 10px; border: 1.5px solid #e2e8f0; border-radius: 8px;
            font-size: 12px; outline: none; text-transform: uppercase; font-family: inherit;
        }
        .form-group input:focus { border-color: #6366f1; }
        .btn-save {
            padding: 8px 20px; border: none; border-radius: 8px;
            background: linear-gradient(135deg, #16a34a, #15803d); color: #fff;
            font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap;
            transition: opacity 0.15s;
        }
        .btn-save:hover { opacity: 0.9; }

        /* Alert */
        .alert {
            padding: 10px 20px; font-size: 13px; font-weight: 600; margin: 12px 24px;
            border-radius: 8px;
        }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }

        /* Table */
        .table-section { padding: 16px 24px; }
        .table-header {
            background: #1e293b; color: #fff; padding: 10px 16px; border-radius: 10px 10px 0 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .table-header h3 { font-size: 14px; font-weight: 700; }
        .search-box {
            padding: 6px 10px; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px;
            background: rgba(255,255,255,0.1); color: #fff; font-size: 12px; outline: none;
            width: 160px;
        }
        .search-box::placeholder { color: rgba(255,255,255,0.5); }

        table { width: 100%; border-collapse: collapse; background: #fff; }
        table thead th {
            padding: 8px 10px; font-size: 11px; font-weight: 700; color: #64748b;
            text-transform: uppercase; text-align: left; border-bottom: 2px solid #e2e8f0;
            background: #f8fafc;
        }
        table tbody td {
            padding: 8px 10px; font-size: 12px; border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        table tbody tr:hover { background: #f8fafc; }
        .td-primary { font-weight: 600; color: #1e293b; }
        .td-secondary { color: #64748b; font-size: 11px; }

        .btn-action {
            padding: 4px 10px; border: none; border-radius: 6px;
            font-size: 11px; font-weight: 600; cursor: pointer;
            text-decoration: none; display: inline-block; margin-right: 4px;
            transition: opacity 0.15s;
        }
        .btn-action:hover { opacity: 0.85; }
        .btn-edit { background: #dbeafe; color: #1d4ed8; }
        .btn-delete { background: #fee2e2; color: #dc2626; }

        .empty-state { text-align: center; padding: 40px; color: #94a3b8; font-size: 14px; }

        /* Right menu */
        .rm-title { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 10px; }
        .rm-btn {
            display: block; width: 100%; padding: 10px; border: none; border-radius: 8px;
            font-size: 12px; font-weight: 700; cursor: pointer; text-align: center;
            text-decoration: none; margin-bottom: 8px; transition: all 0.15s;
        }
        .rm-btn:hover { opacity: 0.9; }
        .rm-btn-gold { background: #fbbf24; color: #78350f; }
        .rm-btn-green { background: #16a34a; color: #fff; }
        .rm-btn-outline { background: #fff; border: 1.5px solid; }
        .rm-btn-blue { border-color: #2563eb; color: #2563eb; }
        .rm-btn-yellow { border-color: #ca8a04; color: #a16207; }
        .rm-btn-teal { border-color: #0d9488; color: #0d9488; }
        .rm-btn-red { border-color: #dc2626; color: #dc2626; }

        @media (max-width: 768px) {
            .page-wrap { flex-direction: column; }
            .right-menu { width: 100%; min-width: 100%; border-left: none; border-top: 1px solid #e2e8f0; }
            .form-grid { flex-direction: column; }
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <div class="content-area">
        {{-- Header --}}
        <div class="page-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
            <h1>Add Village</h1>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error){{ $error }} @endforeach
            </div>
        @endif

        {{-- Add/Edit Form --}}
        <div class="add-form">
            @if(isset($village))
                <form method="POST" action="{{ route('aadhaar.village-info.update', $village->id) }}">
                    @method('PUT')
            @else
                <form method="POST" action="{{ route('aadhaar.village-info.store') }}">
            @endif
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Pincode</label>
                        <input type="text" name="pincode" maxlength="6" placeholder="PINCODE" value="{{ old('pincode', $village->pincode ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" placeholder="STATE" value="{{ old('state', $village->state ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>District</label>
                        <input type="text" name="district" placeholder="DISTRICT" value="{{ old('district', $village->district ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Taluka</label>
                        <input type="text" name="taluka" placeholder="TALUKA" value="{{ old('taluka', $village->taluka ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Post Office</label>
                        <input type="text" name="post_office" placeholder="POST NAME" value="{{ old('post_office', $village->post_office ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Village / City</label>
                        <input type="text" name="village" placeholder="VILLAGE NAME" value="{{ old('village', $village->village ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Verifier / Footer</label>
                        <input type="text" name="verifier_name" placeholder="E.G. SARPANCH NAME" value="{{ old('verifier_name', $village->verifier_name ?? '') }}">
                    </div>
                    <button type="submit" class="btn-save">{{ isset($village) ? '✏️ Update' : '+ Save' }}</button>
                    @if(isset($village))
                        <a href="{{ route('aadhaar.village-info.index') }}" class="btn-save" style="background:#64748b;text-decoration:none;text-align:center;">Cancel</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Saved Records Table --}}
        <div class="table-section">
            <div class="table-header">
                <h3>Saved Records ({{ $villages->count() }})</h3>
                <input type="text" class="search-box" placeholder="🔍 Search..." id="searchBox" oninput="filterTable()">
            </div>
            @if($villages->count() > 0)
                <table id="villageTable">
                    <thead>
                        <tr>
                            <th>Village</th>
                            <th>Post / Taluka</th>
                            <th>Dist / State</th>
                            <th>Pin / Verifier</th>
                            <th>Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($villages as $v)
                        <tr>
                            <td class="td-primary">{{ $v->village }}</td>
                            <td>
                                <div class="td-primary">{{ $v->post_office }}</div>
                                <div class="td-secondary">{{ $v->taluka }}</div>
                            </td>
                            <td>
                                <div class="td-primary">{{ $v->district }}</div>
                                <div class="td-secondary">{{ $v->state }}</div>
                            </td>
                            <td>
                                <div class="td-primary">{{ $v->pincode }}</div>
                                <div class="td-secondary">{{ $v->verifier_name ?: '—' }}</div>
                            </td>
                            <td>
                                <a href="{{ route('aadhaar.village-info.edit', $v->id) }}" class="btn-action btn-edit">Edit</a>
                                <form method="POST" action="{{ route('aadhaar.village-info.destroy', $v->id) }}" style="display:inline" onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">No village records saved yet. Add one above to get started.</div>
            @endif
        </div>
    </div>

    {{-- Right Menu --}}
    <div class="right-menu">
        <div class="rm-title">Quick Menu</div>
        <a href="{{ route('dashboard') }}" class="rm-btn rm-btn-gold">🏠 Home</a>
        <a href="{{ route('aadhaar.hub') }}" class="rm-btn rm-btn-green">📋 Aadhaar Hub</a>

        <div class="rm-title" style="margin-top:16px;">Form Links</div>
        <a href="{{ route('aadhaar.adult-form') }}" class="rm-btn rm-btn-outline rm-btn-blue">Adult Form (18+)</a>
        <a href="{{ route('aadhaar.minor-form') }}" class="rm-btn rm-btn-outline rm-btn-yellow">Minor Form (5-18)</a>
        <a href="{{ route('aadhaar.child-form') }}" class="rm-btn rm-btn-outline rm-btn-teal">Child Form (0-5)</a>
        <a href="{{ route('aadhaar.update-form') }}" class="rm-btn rm-btn-outline rm-btn-red">Update Form</a>
    </div>
</div>

<script>
function filterTable() {
    const q = document.getElementById('searchBox').value.toLowerCase();
    const rows = document.querySelectorAll('#villageTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>
</body>
</html>
