<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>@yield('title') — SETU Suvidha</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    @stack('styles')
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Poppins', 'Noto Sans Devanagari', sans-serif;
        }
        #leftPanel::-webkit-scrollbar { width: 5px; }
        #leftPanel::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .preview-area::-webkit-scrollbar { width: 8px; }
        .preview-area::-webkit-scrollbar-thumb { background: #888; border-radius: 10px; }

        /* ── Shared: Fillable output text ── */
        .out-field { display: inline; border: none !important; font-weight: 700; }
        .out-field.empty { color: #9ca3af; font-style: italic; font-weight: 500; }
        .out-field.filled { color: #111827; font-weight: 700; }

        /* ── Shared: Modern form inputs ── */
        .form-input {
            width: 100%; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 8px 12px; font-size: 13px; transition: all 0.2s;
            background: #fff; color: #1e293b;
        }
        .form-input:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); outline: none; }
        .form-input::placeholder { color: #cbd5e1; }
        .form-select {
            width: 100%; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 8px 12px; font-size: 13px; transition: all 0.2s;
            background: #fff; color: #1e293b; cursor: pointer;
        }
        .form-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); outline: none; }
        .form-textarea {
            width: 100%; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 8px 12px; font-size: 13px; transition: all 0.2s;
            background: #fff; color: #1e293b; resize: none;
        }
        .form-textarea:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); outline: none; }

        /* ── Shared: Section cards ── */
        .section-card { background: #fff; border-radius: 12px; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; }
        .section-header { display: flex; align-items: center; gap: 8px; padding: 10px 14px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
        .section-header .num { width: 22px; height: 22px; border-radius: 6px; background: #6366f1; color: #fff; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .section-header .title { font-size: 12px; font-weight: 600; color: #334155; }
        .section-body { padding: 12px 14px; }
        .field-label { display: block; font-size: 11px; font-weight: 500; color: #64748b; margin-bottom: 4px; letter-spacing: 0.02em; }

        /* ── Shared: Right-panel controls bar ── */
        .ctrl-bar {
            position: sticky; top: 0; z-index: 30;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-bottom: 1px solid #334155;
            padding: 10px 16px;
            display: flex; flex-wrap: wrap; gap: 6px 16px; align-items: center;
            border-radius: 0 0 12px 12px;
            margin: 0 auto 16px; max-width: 820px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .ctrl-bar .ctrl-group {
            display: flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.06); border-radius: 8px; padding: 4px 10px;
        }
        .ctrl-bar label { font-size: 9px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; }
        .ctrl-bar input[type='range'] { width: 80px; accent-color: #818cf8; height: 3px; cursor: pointer; }
        .ctrl-bar .val { font-size: 9px; font-weight: 800; color: #a5b4fc; min-width: 24px; text-align: right; }
        .ctrl-bar .ctrl-divider { width: 1px; height: 20px; background: #334155; margin: 0 4px; }

        @media print {
            html, body { height: auto !important; overflow: visible !important; margin: 0 !important; padding: 0 !important; background: white !important; }
            #leftPanel, .no-print, #redLine, #floatingToolbar, #guideModal, .add-page-wrap, .ctrl-bar, #previewBg, #previewCloseFloat { display: none !important; }
            #root.panel-expanded .preview-area { display: block !important; position: static !important; }
            .bond-watermark { display: none !important; }
            .out-field { color: #000 !important; font-style: normal !important; font-weight: normal !important; border: none !important; }
            #root { display: block !important; overflow: visible !important; height: auto !important; }
            .preview-area { overflow: visible !important; position: static !important; padding: 0 !important; margin: 0 !important; background: white !important; height: auto !important; }
            .bond-page {
                position: relative !important;
                width: 210mm !important;
                min-height: 297mm !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                page-break-after: always;
                page-break-inside: avoid;
            }
            .bond-gap {
                background: white !important;
                border: none !important;
            }
            .bond-gap span, .page-ctrl { display: none !important; }
            @page { size: A4; margin: 0; }
        }

        #floatingToolbar {
            position: fixed;
            display: none;
            z-index: 9999;
            background: #1e293b;
            border-radius: 8px;
            padding: 4px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            gap: 2px;
        }
        #floatingToolbar.show { display: flex !important; }
        #floatingToolbar button {
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border: none; background: transparent; color: #e2e8f0;
            border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 700;
            transition: background 0.15s;
        }
        #floatingToolbar button:hover { background: #475569; }
        #floatingToolbar button.active { background: #3b82f6; color: white; }
        #floatingToolbar .sep { width: 1px; background: #475569; margin: 4px 2px; }

        [contenteditable]:focus { outline: 1px dashed #93c5fd; outline-offset: 2px; }

        .add-page-wrap {
            display: flex;
            justify-content: center;
            padding: 24px 0 40px;
        }
        .add-page-btn {
            display: flex; align-items: center; gap: 8px;
            background: #2563eb; color: white; border: 2px dashed #60a5fa;
            padding: 12px 28px; border-radius: 12px; font-weight: 700;
            font-size: 14px; cursor: pointer; transition: all 0.2s;
        }
        .add-page-btn:hover { background: #1d4ed8; transform: scale(1.05); }
        .page-ctrl {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 12px; background: #1e293b; border-radius: 8px 8px 0 0;
            width: fit-content; margin: 0 auto;
        }
        .page-ctrl label { color: #94a3b8; font-size: 11px; font-weight: 600; white-space: nowrap; }
        .page-ctrl input[type="range"] { width: 120px; accent-color: #a78bfa; }
        .page-ctrl .remove-page-btn {
            background: #ef4444; color: white; border: none; padding: 3px 10px;
            border-radius: 4px; font-size: 11px; font-weight: 600; cursor: pointer;
        }
        .page-ctrl .remove-page-btn:hover { background: #dc2626; }

        /* ── Expandable Left Panel ── */
        #root.panel-expanded #leftPanel {
            width: 100% !important;
            min-width: 100% !important;
        }
        #root.panel-expanded .preview-area {
            display: none !important;
        }
        #root.panel-expanded .preview-area.show-preview {
            display: block !important;
            position: fixed !important;
            inset: 2vh 2vw !important;
            z-index: 500 !important;
            border-radius: 16px !important;
            box-shadow: 0 25px 80px rgba(0,0,0,0.5) !important;
            width: auto !important;
        }
        #previewBg {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 499;
            backdrop-filter: blur(3px);
        }
        #previewBg.show { display: block; }
        #previewCloseFloat {
            display: none;
            position: fixed;
            top: calc(2vh + 10px);
            right: calc(2vw + 14px);
            z-index: 510;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 7px 18px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            gap: 6px;
            align-items: center;
        }
        #previewCloseFloat.show { display: flex; }
        #previewCloseFloat:hover { background: #dc2626; }
    </style>
</head>
<body>
    @yield('content')

    <div id="floatingToolbar">
        <button onclick="fmtCmd('bold')" title="Bold"><b>B</b></button>
        <button onclick="fmtCmd('italic')" title="Italic"><i>I</i></button>
        <button onclick="fmtCmd('underline')" title="Underline"><u>U</u></button>
        <button onclick="fmtCmd('strikeThrough')" title="Strikethrough"><s>S</s></button>
        <div class="sep"></div>
        <button onclick="fmtCmd('removeFormat')" title="Clear Formatting" style="font-size:12px;">✕</button>
    </div>

    <script>
    (function(){
        /* ── Floating Toolbar ── */
        var toolbar = document.getElementById('floatingToolbar');
        if (!toolbar) return;

        document.addEventListener('selectionchange', function() {
            var sel = window.getSelection();
            if (!sel || sel.isCollapsed || !sel.toString().trim()) {
                toolbar.classList.remove('show');
                return;
            }
            var node = sel.anchorNode;
            if (!node) { toolbar.classList.remove('show'); return; }
            var parent = node.nodeType === 3 ? node.parentElement : node;
            if (!parent || !parent.closest('.preview-area')) {
                toolbar.classList.remove('show');
                return;
            }
            var range = sel.getRangeAt(0);
            var rect = range.getBoundingClientRect();
            toolbar.classList.add('show');
            var tx = rect.left + (rect.width / 2) - (toolbar.offsetWidth / 2);
            var ty = rect.top - toolbar.offsetHeight - 8;
            if (ty < 4) ty = rect.bottom + 8;
            if (tx < 4) tx = 4;
            toolbar.style.left = tx + 'px';
            toolbar.style.top = ty + 'px';
            updateToolbarState();
        });

        document.addEventListener('mousedown', function(e) {
            if (toolbar.contains(e.target)) { e.preventDefault(); }
        });

        window.fmtCmd = function(cmd) {
            document.execCommand(cmd, false, null);
            updateToolbarState();
        };

        function updateToolbarState() {
            var btns = toolbar.querySelectorAll('button');
            var cmds = ['bold','italic','underline','strikeThrough'];
            btns.forEach(function(btn, i) {
                if (i < cmds.length) {
                    btn.classList.toggle('active', document.queryCommandState(cmds[i]));
                }
            });
        }

        /* ── Add Page Feature ── */
        var previewArea = document.querySelector('.preview-area');
        if (!previewArea) return;

        var addWrap = document.createElement('div');
        addWrap.className = 'add-page-wrap';
        addWrap.innerHTML = '<button class="add-page-btn" onclick="bondAddPage()">+ नवीन पान जोडा (Add Page)</button>';
        previewArea.appendChild(addWrap);

        var bondPageCount = previewArea.querySelectorAll('.bond-page').length;

        window.bondAddPage = function() {
            bondPageCount++;
            var num = bondPageCount;
            var fs = document.querySelector('.bond-page') ? document.querySelector('.bond-page').style.fontSize : '11pt';

            var page = document.createElement('div');
            page.className = 'bond-page relative w-[794px] mx-auto bg-white shadow-2xl mt-4';
            page.style.cssText = 'min-height:1123px;font-size:' + fs + ';font-family:"Noto Sans Devanagari","Mukta",sans-serif;';

            page.innerHTML =
                '<div class="bond-watermark absolute inset-0 flex items-center justify-center pointer-events-none z-50 overflow-hidden">' +
                    '<span class="text-red-400 text-7xl font-black opacity-20 rotate-[-35deg] select-none whitespace-nowrap tracking-widest">PREVIEW MODE</span>' +
                '</div>' +
                '<div class="page-ctrl">' +
                    '<label>P' + num + ' Gap:</label>' +
                    '<input type="range" min="0" max="200" value="60" oninput="this.closest(\'.bond-page\').querySelector(\'.bond-gap\').style.height=this.value+\'mm\'">' +
                    '<label>Pad L:</label>' +
                    '<input type="range" min="5" max="80" value="40" oninput="this.closest(\'.bond-page\').querySelector(\'.bond-content\').style.paddingLeft=this.value+\'px\'">' +
                    '<label>Pad R:</label>' +
                    '<input type="range" min="5" max="80" value="40" oninput="this.closest(\'.bond-page\').querySelector(\'.bond-content\').style.paddingRight=this.value+\'px\'">' +
                    '<button class="remove-page-btn" onclick="bondRemovePage(this)">Remove</button>' +
                '</div>' +
                '<div class="bond-gap w-full relative overflow-hidden" style="height:60mm;background:repeating-linear-gradient(45deg,#e8e8e8,#e8e8e8 2px,#f5f5f5 2px,#f5f5f5 14px);">' +
                    '<span class="absolute bottom-3 left-1/2 -translate-x-1/2 text-gray-400 text-xs font-medium tracking-wide">PAGE ' + num + ' GAP</span>' +
                '</div>' +
                '<div class="bond-content py-4" contenteditable="true" spellcheck="false" style="min-height:700px;padding-left:40px;padding-right:40px;">' +
                    '<p class="text-gray-400 italic text-center py-8">येथे मजकूर टाइप करा किंवा वरच्या पानावरून Cut करून येथे Paste करा...</p>' +
                '</div>';

            addWrap.parentNode.insertBefore(page, addWrap);
            page.querySelector('.bond-content').focus();
        };

        window.bondRemovePage = function(btn) {
            if (confirm('हे पान काढून टाकायचे आहे का? (Remove this page?)')) {
                btn.closest('.bond-page').remove();
            }
        };

        /* ── Apply font size to all pages (including dynamic) ── */
        window.bondSetFontAll = function(val) {
            document.querySelectorAll('.bond-page').forEach(function(p) {
                p.style.fontSize = val + 'pt';
            });
        };
    })();

    /* ── Expandable Left Panel ── */
    (function(){
        var root = document.getElementById('root');
        var lp = document.getElementById('leftPanel');
        if (!root || !lp) return;
        var pa = root.querySelector('.preview-area');
        if (!pa) return;

        var header = lp.querySelector('.sticky');
        if (!header) return;

        function mkBtn(cls, title, icon) {
            var b = document.createElement('button');
            b.className = 'w-7 h-7 rounded-lg text-xs font-bold transition flex items-center justify-center ' + cls;
            b.title = title;
            b.innerHTML = '<i data-lucide="' + icon + '" class="w-3.5 h-3.5"></i>';
            return b;
        }

        var expBtn = mkBtn('bg-blue-50 text-blue-500 hover:bg-blue-100', 'पॅनेल मोठे करा (Enlarge)', 'maximize-2');
        var prvBtn = mkBtn('bg-green-50 text-green-600 hover:bg-green-100', 'Preview पहा', 'eye');
        prvBtn.style.display = 'none';

        var wrap = document.createElement('div');
        wrap.className = 'flex items-center gap-1.5';
        wrap.appendChild(expBtn);
        wrap.appendChild(prvBtn);

        var guide = header.lastElementChild;
        if (guide && guide.tagName === 'BUTTON') {
            wrap.appendChild(guide);
        }
        header.appendChild(wrap);

        var bg = document.createElement('div');
        bg.id = 'previewBg';
        document.body.appendChild(bg);

        var closeBtn = document.createElement('button');
        closeBtn.id = 'previewCloseFloat';
        closeBtn.innerHTML = '<i data-lucide="x" class="w-4 h-4"></i> बंद करा';
        document.body.appendChild(closeBtn);

        var expanded = false, previewing = false;
        function ic() { if (typeof lucide !== 'undefined') lucide.createIcons(); }

        function setExpand(val) {
            expanded = val;
            root.classList.toggle('panel-expanded', expanded);
            expBtn.innerHTML = '<i data-lucide="' + (expanded ? 'minimize-2' : 'maximize-2') + '" class="w-3.5 h-3.5"></i>';
            expBtn.title = expanded ? 'पॅनेल लहान करा (Collapse)' : 'पॅनेल मोठे करा (Enlarge)';
            prvBtn.style.display = expanded ? 'flex' : 'none';
            if (!expanded) setPreview(false);
            ic();
        }

        function setPreview(val) {
            previewing = val;
            pa.classList.toggle('show-preview', previewing);
            bg.classList.toggle('show', previewing);
            closeBtn.classList.toggle('show', previewing);
            prvBtn.innerHTML = '<i data-lucide="' + (previewing ? 'eye-off' : 'eye') + '" class="w-3.5 h-3.5"></i>';
            prvBtn.title = previewing ? 'Preview बंद करा' : 'Preview पहा';
            ic();
        }

        expBtn.addEventListener('click', function() { setExpand(!expanded); });
        prvBtn.addEventListener('click', function() { setPreview(!previewing); });
        bg.addEventListener('click', function() { setPreview(false); });
        closeBtn.addEventListener('click', function() { setPreview(false); });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (previewing) setPreview(false);
                else if (expanded) setExpand(false);
            }
        });

        ic();
    })();
    </script>

    @stack('scripts')
</body>
</html>
