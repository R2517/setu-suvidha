@extends('layouts.app')
@section('title', 'Create Blog Post — Admin')

@section('content')
<div class="flex min-h-screen">
    @include('admin.partials.sidebar')

    <div class="flex-1 p-6 lg:p-8 bg-gray-50 dark:bg-gray-950 overflow-x-hidden">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <i data-lucide="file-plus" class="w-6 h-6 text-amber-500 pointer-events-none"></i> Create Blog Post
                </h1>
                <p class="text-sm text-gray-500 mt-1">Paste your full JSON — everything fills automatically!</p>
            </div>

            {{-- STEP 1: Smart Import --}}
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-2xl border-2 border-amber-300 dark:border-amber-700 p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <i data-lucide="zap" class="w-5 h-5 text-amber-500 pointer-events-none"></i> Smart Import — Paste Full JSON Here
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Paste your complete blog JSON below and click "Auto-Fill". Title, category, tags, SEO, image — sab automatic bhar jayega!</p>
                
                <div class="relative">
                    <textarea id="smartJsonInput" rows="12" class="w-full px-4 py-3 rounded-xl border-2 border-amber-200 dark:border-amber-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white font-mono text-xs focus:border-amber-500 focus:ring-2 focus:ring-amber-200 leading-5" placeholder='Paste your full JSON here...

{
  "title": "...",
  "blocks": [...]
}' oninput="liveValidate()"></textarea>
                    <div id="lineInfo" class="absolute top-2 right-3 text-[10px] text-gray-400 font-mono pointer-events-none">Line: 0 | Col: 0</div>
                </div>

                {{-- Error Location Panel --}}
                <div id="errorPanel" class="hidden mt-3 rounded-xl border-2 border-red-300 bg-red-50 dark:bg-red-900/20 overflow-hidden">
                    <div class="px-4 py-2 bg-red-100 dark:bg-red-900/40 flex items-center justify-between">
                        <span class="text-sm font-bold text-red-700 dark:text-red-400 flex items-center gap-2">
                            <i data-lucide="alert-circle" class="w-4 h-4 pointer-events-none"></i>
                            <span id="errorTitle">JSON Error</span>
                        </span>
                        <button type="button" onclick="jumpToError()" class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition flex items-center gap-1">
                            <i data-lucide="navigation" class="w-3 h-3 pointer-events-none"></i> Jump to Error
                        </button>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-4 text-xs">
                            <span class="px-2 py-1 bg-red-200 dark:bg-red-800 text-red-800 dark:text-red-200 rounded font-bold" id="errorLocation">Line 0, Col 0</span>
                            <span class="text-red-600 dark:text-red-400" id="errorMessage"></span>
                        </div>
                        <div class="bg-gray-900 rounded-lg p-3 font-mono text-xs overflow-x-auto">
                            <div id="errorContext" class="space-y-0.5"></div>
                        </div>
                        <div id="errorSuggestion" class="text-sm text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 rounded-lg p-3 flex items-start gap-2">
                            <i data-lucide="lightbulb" class="w-4 h-4 mt-0.5 flex-shrink-0 pointer-events-none"></i>
                            <span id="errorSuggestionText"></span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-3">
                    <button type="button" onclick="smartImport()" class="px-6 py-2.5 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-4 h-4 pointer-events-none"></i> Auto-Fill Form
                    </button>
                    <button type="button" onclick="validateOnly()" class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center gap-2 text-sm">
                        <i data-lucide="check-circle" class="w-4 h-4 pointer-events-none"></i> Validate JSON
                    </button>
                </div>
                <div id="importStatus" class="mt-3 text-sm font-medium hidden p-4 rounded-xl border max-h-60 overflow-y-auto"></div>
            </div>

            {{-- STEP 2: Form (auto-filled or manual) --}}
            <form action="{{ route('admin.blog.store') }}" method="POST" class="space-y-6" id="blogForm" enctype="multipart/form-data">
                @csrf

                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Basic Information</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                            <input type="text" name="title" id="fTitle" required class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                <select name="blog_category_id" id="fCategory" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    <option value="">None</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}">{{ $cat->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                                <select name="status" id="fStatus" required class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                    <option value="scheduled">Scheduled</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags (comma separated)</label>
                            <input type="text" name="tags_text" id="fTags" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="tag1, tag2, tag3">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Excerpt</label>
                            <textarea name="excerpt" id="fExcerpt" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Short summary (150-160 characters ideal)"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Content (JSON) *</h2>
                    
                    <div>
                        <textarea name="content_json" id="fContentJson" required rows="10" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white font-mono text-xs" placeholder='Auto-filled from Smart Import above'></textarea>
                        <p class="text-xs text-gray-500 mt-2">This gets auto-filled when you use Smart Import. Contains the full JSON with blocks.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">SEO Meta</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta Title (max 60 chars)</label>
                            <input type="text" name="meta_title" id="fMetaTitle" maxlength="60" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meta Description (max 160 chars)</label>
                            <textarea name="meta_description" id="fMetaDesc" rows="2" maxlength="160" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Featured Image</label>
                            <input type="file" name="featured_image_file" id="fImageFile" accept="image/*" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:bg-amber-500 file:text-white file:font-bold file:cursor-pointer" onchange="previewImage(this)">
                            <input type="hidden" name="featured_image" id="fImage">
                            <div id="imagePreview" class="mt-3 hidden">
                                <img id="imagePreviewImg" src="" alt="Preview" class="max-h-48 rounded-xl border border-gray-200 dark:border-gray-700">
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Upload JPG, PNG or WebP. Recommended: 1200x630px for best social sharing.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Featured Image Alt Text</label>
                            <input type="text" name="featured_image_alt" id="fImageAlt" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800" placeholder="Descriptive alt text for SEO">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-6 py-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition flex items-center gap-2">
                        <i data-lucide="check" class="w-5 h-5 pointer-events-none"></i> Create Post
                    </button>
                    <a href="{{ route('admin.blog.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-300 dark:hover:bg-gray-700 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ==========================================
// CURSOR POSITION TRACKER
// ==========================================
const jsonInput = document.getElementById('smartJsonInput');
jsonInput.addEventListener('click', updateCursorInfo);
jsonInput.addEventListener('keyup', updateCursorInfo);

function updateCursorInfo() {
    const ta = document.getElementById('smartJsonInput');
    const pos = ta.selectionStart;
    const text = ta.value.substring(0, pos);
    const lines = text.split('\n');
    const line = lines.length;
    const col = lines[lines.length - 1].length + 1;
    document.getElementById('lineInfo').textContent = `Line: ${line} | Col: ${col} | Pos: ${pos}`;
}

// ==========================================
// LIVE VALIDATION (shows errors as you type)
// ==========================================
let liveTimer = null;
function liveValidate() {
    clearTimeout(liveTimer);
    liveTimer = setTimeout(() => {
        const raw = document.getElementById('smartJsonInput').value.trim();
        if (!raw || raw.length < 3) { hideErrorPanel(); return; }
        try {
            JSON.parse(raw);
            hideErrorPanel();
            document.getElementById('smartJsonInput').style.borderColor = '#22c55e';
        } catch(e) {
            document.getElementById('smartJsonInput').style.borderColor = '#ef4444';
            showErrorPanel(raw, e);
        }
    }, 500);
}

// ==========================================
// VALIDATE ONLY BUTTON
// ==========================================
function validateOnly() {
    const raw = document.getElementById('smartJsonInput').value.trim();
    const statusEl = document.getElementById('importStatus');
    if (!raw) { showStatus(statusEl, 'Paste JSON first!', 'error'); return; }

    try {
        const data = JSON.parse(raw);
        hideErrorPanel();
        document.getElementById('smartJsonInput').style.borderColor = '#22c55e';

        const validation = validateBlogStructure(data);
        let msg = '✅ Valid JSON!';
        if (validation.warnings && validation.warnings.length) {
            msg += '\n\n⚠️ Warnings:\n• ' + validation.warnings.join('\n• ');
        }
        const blockCount = (data.blocks || []).length;
        const blockTypes = [...new Set((data.blocks || []).map(b => b.type || 'unknown'))];
        msg += `\n\n📊 Stats: ${blockCount} blocks | Types: ${blockTypes.join(', ')}`;
        showStatus(statusEl, msg, 'success');
    } catch(e) {
        document.getElementById('smartJsonInput').style.borderColor = '#ef4444';
        showErrorPanel(raw, e);

        // Try auto-fix
        const fixResult = fixJson(raw);
        try {
            JSON.parse(fixResult.text);
            showStatus(statusEl, '⚠️ JSON has errors but can be auto-fixed!\nClick "Auto-Fill Form" to fix and import.', 'warn');
        } catch(e2) {
            showStatus(statusEl, '❌ JSON has errors that need manual fixing. See error panel above.', 'error');
        }
    }
}

// ==========================================
// ERROR PANEL — show exact error location
// ==========================================
let currentErrorPos = -1;
let currentErrorLine = -1;

function showErrorPanel(raw, error) {
    const panel = document.getElementById('errorPanel');
    const lines = raw.split('\n');

    // Extract position from error
    const posMatch = error.message.match(/position\s+(\d+)/i);
    const lineMatch = error.message.match(/line\s+(\d+)/i);
    const colMatch = error.message.match(/column\s+(\d+)/i);

    let errorPos = posMatch ? parseInt(posMatch[1]) : -1;
    let errorLine = lineMatch ? parseInt(lineMatch[1]) : -1;
    let errorCol = colMatch ? parseInt(colMatch[1]) : -1;

    // Calculate line/col from position if not provided
    if (errorPos >= 0 && errorLine === -1) {
        let charCount = 0;
        for (let i = 0; i < lines.length; i++) {
            if (charCount + lines[i].length + 1 > errorPos) {
                errorLine = i + 1;
                errorCol = errorPos - charCount + 1;
                break;
            }
            charCount += lines[i].length + 1;
        }
    }

    currentErrorPos = errorPos;
    currentErrorLine = errorLine;

    // Error location badge
    document.getElementById('errorLocation').textContent = `Line ${errorLine}, Col ${errorCol}`;
    document.getElementById('errorTitle').textContent = 'JSON Error Found';

    // Clean error message
    let cleanMsg = error.message.replace(/in JSON at position.*$/i, '').trim();
    document.getElementById('errorMessage').textContent = cleanMsg;

    // Build context view (3 lines before, error line, 3 lines after)
    const contextEl = document.getElementById('errorContext');
    contextEl.innerHTML = '';
    const startLine = Math.max(0, errorLine - 4);
    const endLine = Math.min(lines.length - 1, errorLine + 2);

    for (let i = startLine; i <= endLine; i++) {
        const lineNum = i + 1;
        const isError = lineNum === errorLine;
        const lineContent = lines[i] || '';

        // Truncate long lines for display
        let displayContent = lineContent.length > 120 ? lineContent.substring(0, 120) + '...' : lineContent;

        // If error line, highlight the error column
        if (isError && errorCol > 0) {
            const before = escapeHtml(displayContent.substring(0, errorCol - 1));
            const errorChar = escapeHtml(displayContent[errorCol - 1] || ' ');
            const after = escapeHtml(displayContent.substring(errorCol));
            displayContent = `${before}<span class="bg-red-500 text-white px-0.5 rounded animate-pulse">${errorChar}</span>${after}`;
        } else {
            displayContent = escapeHtml(displayContent);
        }

        const div = document.createElement('div');
        div.className = isError
            ? 'flex bg-red-900/50 rounded px-2 py-0.5 -mx-2'
            : 'flex text-gray-500 px-2 py-0.5 -mx-2';
        div.innerHTML = `<span class="w-10 text-right pr-3 select-none ${isError ? 'text-red-400 font-bold' : 'text-gray-600'}">${lineNum}</span><span class="flex-1 ${isError ? 'text-red-200' : 'text-gray-400'} whitespace-pre overflow-hidden">${displayContent}</span>`;
        contextEl.appendChild(div);
    }

    // Smart suggestion based on error type
    const suggestion = getSuggestion(error.message, lines, errorLine, errorCol);
    document.getElementById('errorSuggestionText').innerHTML = suggestion;

    panel.classList.remove('hidden');

    // Re-init lucide icons in the panel
    if (window.lucide) lucide.createIcons();
}

function hideErrorPanel() {
    document.getElementById('errorPanel').classList.add('hidden');
    document.getElementById('smartJsonInput').style.borderColor = '';
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getSuggestion(errorMsg, lines, errorLine, errorCol) {
    const line = lines[errorLine - 1] || '';
    const char = line[errorCol - 1] || '';
    const before = line.substring(Math.max(0, errorCol - 20), errorCol - 1);

    if (errorMsg.includes("Expected ','") || errorMsg.includes("Expected '}'")) {
        if (before.match(/[a-z-]+=$/i)) {
            return `<b>Unescaped quote in HTML!</b> The <code>"</code> at column ${errorCol} is inside an HTML attribute like <code>href="..."</code>. In JSON, these quotes must be escaped as <code>\\"</code>.<br><br>Fix: Change <code>href="value"</code> to <code>href=\\"value\\"</code>`;
        }
        return `<b>Missing comma!</b> Add a <code>,</code> after the value on line ${errorLine - 1} or before line ${errorLine}. JSON requires commas between all properties.`;
    }

    if (errorMsg.includes('Unexpected token')) {
        if (char === "'") return `<b>Wrong quote type!</b> JSON only allows double quotes <code>"</code>, not single quotes <code>'</code>. Replace <code>'</code> with <code>"</code>.`;
        if (char === ',') return `<b>Extra comma!</b> Remove the trailing comma <code>,</code> before <code>}</code> or <code>]</code>. JSON doesn't allow trailing commas.`;
        return `<b>Unexpected character <code>${escapeHtml(char)}</code>!</b> This character is not valid at this position in JSON.`;
    }

    if (errorMsg.includes('Unterminated string')) {
        return `<b>String not closed!</b> A <code>"</code> is missing to close the string. Check for unescaped quotes inside the string value. Use <code>\\"</code> for quotes inside strings.`;
    }

    if (errorMsg.includes('end of file') || errorMsg.includes('Unexpected end')) {
        return `<b>JSON incomplete!</b> Missing closing <code>}</code> or <code>]</code> at the end. Make sure all brackets and braces are properly closed.`;
    }

    return `<b>JSON syntax error near line ${errorLine}.</b> Check for missing commas, extra commas, unescaped quotes, or mismatched brackets.`;
}

// ==========================================
// JUMP TO ERROR — cursor goes to error position
// ==========================================
function jumpToError() {
    if (currentErrorPos === -1) return;
    const ta = document.getElementById('smartJsonInput');
    ta.focus();
    ta.setSelectionRange(currentErrorPos, currentErrorPos + 1);

    // Scroll textarea to show the error line
    const text = ta.value.substring(0, currentErrorPos);
    const linesBefore = text.split('\n').length;
    const lineHeight = 20; // approximate
    ta.scrollTop = Math.max(0, (linesBefore - 5) * lineHeight);

    // Flash the textarea border
    ta.style.borderColor = '#ef4444';
    ta.style.boxShadow = '0 0 0 4px rgba(239,68,68,0.3)';
    setTimeout(() => { ta.style.boxShadow = ''; }, 1500);
}

function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const img = document.getElementById('imagePreviewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) { img.src = e.target.result; preview.classList.remove('hidden'); };
        reader.readAsDataURL(input.files[0]);
        document.getElementById('fImage').value = '';
    }
}

// ==========================================
// ADVANCED JSON AUTO-FIX ENGINE
// ==========================================
function fixJson(raw) {
    const fixes = [];
    let text = raw;

    // 1. Remove BOM and invisible characters
    if (text.charCodeAt(0) === 0xFEFF) { text = text.slice(1); fixes.push('Removed BOM character'); }

    // 2. Remove JavaScript/C-style comments
    const beforeComments = text;
    text = text.replace(/\/\/.*$/gm, '');
    text = text.replace(/\/\*[\s\S]*?\*\//g, '');
    if (text !== beforeComments) fixes.push('Removed comments');

    // 3. Fix single quotes to double quotes (smart - skip inside strings)
    const beforeQuotes = text;
    text = fixQuotes(text);
    if (text !== beforeQuotes) fixes.push('Fixed single quotes to double quotes');

    // 4. Fix unquoted keys: { key: "value" } → { "key": "value" }
    const beforeKeys = text;
    text = text.replace(/([{,]\s*)([a-zA-Z_$][\w$]*)\s*:/g, '$1"$2":');
    if (text !== beforeKeys) fixes.push('Added quotes to unquoted keys');

    // 5. Fix trailing commas: [1,2,] → [1,2] and {a:1,} → {a:1}
    const beforeTrailing = text;
    text = text.replace(/,\s*([\]}])/g, '$1');
    if (text !== beforeTrailing) fixes.push('Removed trailing commas');

    // 6. Fix missing commas between properties: "a": 1\n "b": 2 → "a": 1,\n "b": 2
    const beforeMissing = text;
    text = text.replace(/(["}\]\d])\s*\n(\s*")/g, '$1,\n$2');
    text = text.replace(/(["\d])\s*\n(\s*\{)/g, '$1,\n$2');
    text = text.replace(/(["\d])\s*\n(\s*\[)/g, '$1,\n$2');
    if (text !== beforeMissing) fixes.push('Added missing commas between properties');

    // 7. Fix extra/double commas: {,, "a": 1} or {"a": 1,, "b": 2}
    const beforeDouble = text;
    text = text.replace(/,\s*,/g, ',');
    text = text.replace(/([\[{])\s*,/g, '$1');
    if (text !== beforeDouble) fixes.push('Removed extra commas');

    // 8. Fix unescaped newlines/tabs in strings
    const beforeEscape = text;
    text = fixUnescapedInStrings(text);
    if (text !== beforeEscape) fixes.push('Fixed unescaped characters in strings');

    // 9. Balance brackets and braces
    const bracketFix = balanceBrackets(text);
    if (bracketFix.fixed) {
        text = bracketFix.text;
        fixes.push(...bracketFix.messages);
    }

    // 10. Fix true/false/null case issues: True → true, False → false, None → null
    const beforeBool = text;
    text = text.replace(/:\s*True\b/g, ': true');
    text = text.replace(/:\s*False\b/g, ': false');
    text = text.replace(/:\s*None\b/g, ': null');
    text = text.replace(/:\s*undefined\b/g, ': null');
    if (text !== beforeBool) fixes.push('Fixed boolean/null values');

    return { text: text.trim(), fixes };
}

function fixQuotes(text) {
    let result = '';
    let inDouble = false, inSingle = false, escaped = false;
    for (let i = 0; i < text.length; i++) {
        const ch = text[i];
        if (escaped) { result += ch; escaped = false; continue; }
        if (ch === '\\') { result += ch; escaped = true; continue; }
        if (ch === '"' && !inSingle) { inDouble = !inDouble; result += ch; continue; }
        if (ch === "'" && !inDouble) {
            if (!inSingle) { inSingle = true; result += '"'; continue; }
            else { inSingle = false; result += '"'; continue; }
        }
        result += ch;
    }
    return result;
}

function fixUnescapedInStrings(text) {
    return text.replace(/"([^"\\]*(?:\\.[^"\\]*)*)"/g, function(match) {
        return match.replace(/\t/g, '\\t').replace(/\r/g, '\\r');
    });
}

function balanceBrackets(text) {
    let opens = 0, closes = 0, sqOpens = 0, sqCloses = 0;
    let inStr = false, escaped = false;
    const messages = [];
    for (let i = 0; i < text.length; i++) {
        const ch = text[i];
        if (escaped) { escaped = false; continue; }
        if (ch === '\\') { escaped = true; continue; }
        if (ch === '"') { inStr = !inStr; continue; }
        if (inStr) continue;
        if (ch === '{') opens++;
        if (ch === '}') closes++;
        if (ch === '[') sqOpens++;
        if (ch === ']') sqCloses++;
    }
    let fixed = false;
    while (opens > closes) { text += '}'; closes++; fixed = true; messages.push('Added missing closing }'); }
    while (sqOpens > sqCloses) { text += ']'; sqCloses++; fixed = true; messages.push('Added missing closing ]'); }
    while (closes > opens) { text = '{' + text; opens++; fixed = true; messages.push('Added missing opening {'); }
    while (sqCloses > sqOpens) { text = '[' + text; sqOpens++; fixed = true; messages.push('Added missing opening ['); }
    return { text, fixed, messages };
}

// ==========================================
// STRUCTURE VALIDATOR
// ==========================================
function validateBlogStructure(data) {
    const warnings = [];
    const autoFixes = [];

    // Must be an object
    if (typeof data !== 'object' || Array.isArray(data)) {
        return { valid: false, error: 'JSON must be an object {}' };
    }

    // Auto-fix: if no title, try to get from meta or first heading block
    if (!data.title) {
        if (data.meta && data.meta.title) {
            data.title = data.meta.title.replace(/\s*\|.*$/, '');
            autoFixes.push('Title auto-set from meta.title');
        } else if (data.blocks) {
            const heading = data.blocks.find(b => b.type === 'heading' || b.type === 'hero');
            if (heading && heading.title) { data.title = heading.title; autoFixes.push('Title auto-set from first heading block'); }
        }
        if (!data.title) warnings.push('Missing "title" field — please fill manually');
    }

    // Auto-fix: if no slug, generate from title
    if (!data.slug && data.title) {
        data.slug = data.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '').substring(0, 80);
        autoFixes.push('Slug auto-generated from title');
    }

    // Auto-fix: ensure blocks array exists
    if (!data.blocks) {
        if (Array.isArray(data)) {
            data = { blocks: data };
            autoFixes.push('Wrapped array into { blocks: [...] }');
        } else {
            warnings.push('No "blocks" array found — content will be empty');
        }
    }

    // Auto-fix: if blocks is not array
    if (data.blocks && !Array.isArray(data.blocks)) {
        warnings.push('"blocks" should be an array');
    }

    // Validate each block
    if (data.blocks && Array.isArray(data.blocks)) {
        data.blocks.forEach((block, i) => {
            if (!block.type) {
                block.type = 'paragraph';
                autoFixes.push(`Block ${i}: missing type, set to "paragraph"`);
            }
        });
    }

    // Auto-fix: if no excerpt, generate from first paragraph
    if (!data.excerpt && data.blocks) {
        const para = data.blocks.find(b => b.type === 'paragraph' && b.content);
        if (para) {
            const text = para.content.replace(/<[^>]+>/g, '');
            data.excerpt = text.substring(0, 160);
            autoFixes.push('Excerpt auto-generated from first paragraph');
        }
    }

    // Auto-fix: ensure tags is array
    if (data.tags && !Array.isArray(data.tags)) {
        if (typeof data.tags === 'string') {
            data.tags = data.tags.split(',').map(t => t.trim()).filter(Boolean);
            autoFixes.push('Tags converted from string to array');
        }
    }

    // Auto-fix: meta object
    if (data.meta && typeof data.meta !== 'object') {
        data.meta = {};
        autoFixes.push('Invalid meta field reset to empty object');
    }

    return { valid: true, data, warnings, autoFixes };
}

// ==========================================
// SMART IMPORT (Main Function)
// ==========================================
function smartImport() {
    const raw = document.getElementById('smartJsonInput').value.trim();
    const statusEl = document.getElementById('importStatus');

    if (!raw) { showStatus(statusEl, 'Please paste JSON first!', 'error'); return; }

    let data = null;
    let allFixes = [];

    // Step 1: Try normal parse
    try {
        data = JSON.parse(raw);
        allFixes.push('JSON parsed successfully (no fixes needed)');
    } catch(firstError) {
        // Step 2: Auto-fix and retry
        const fixResult = fixJson(raw);
        allFixes = [...fixResult.fixes];

        try {
            data = JSON.parse(fixResult.text);
            allFixes.push('JSON fixed and parsed successfully!');
        } catch(secondError) {
            // Step 3: Aggressive fix — try to extract valid JSON substring
            const aggressive = aggressiveFix(fixResult.text);
            if (aggressive) {
                try {
                    data = JSON.parse(aggressive.text);
                    allFixes.push(...aggressive.fixes);
                    allFixes.push('JSON recovered using aggressive repair');
                } catch(e) {
                    showStatus(statusEl,
                        'Could not auto-fix this JSON. Error: ' + secondError.message +
                        '\n\nFixes attempted:\n• ' + allFixes.join('\n• '),
                        'error'
                    );
                    return;
                }
            } else {
                showStatus(statusEl,
                    'Could not auto-fix this JSON. Error: ' + secondError.message +
                    '\n\nFixes attempted:\n• ' + allFixes.join('\n• '),
                    'error'
                );
                return;
            }
        }
    }

    // Step 4: Validate and auto-fix structure
    const validation = validateBlogStructure(data);
    if (!validation.valid) {
        showStatus(statusEl, 'Invalid structure: ' + validation.error, 'error');
        return;
    }
    data = validation.data;
    allFixes.push(...(validation.autoFixes || []));

    // Step 5: Update the textarea with clean JSON
    const cleanJson = JSON.stringify(data, null, 2);
    document.getElementById('smartJsonInput').value = cleanJson;

    // Step 6: Fill form fields
    fillForm(data);

    // Step 7: Show report
    let report = '✅ Import successful!';
    if (allFixes.length > 1 || (allFixes.length === 1 && !allFixes[0].includes('no fixes needed'))) {
        report += '\n\nAuto-fixes applied:\n• ' + allFixes.filter(f => !f.includes('no fixes needed')).join('\n• ');
    }
    if (validation.warnings && validation.warnings.length > 0) {
        report += '\n\n⚠️ Warnings:\n• ' + validation.warnings.join('\n• ');
    }
    showStatus(statusEl, report, 'success');
    document.getElementById('blogForm').scrollIntoView({ behavior: 'smooth' });
}

function aggressiveFix(text) {
    const fixes = [];

    // Strategy 1: Extract between first { and last }
    const firstBrace = text.indexOf('{');
    const lastBrace = text.lastIndexOf('}');
    if (firstBrace !== -1 && lastBrace !== -1 && firstBrace < lastBrace) {
        let extracted = text.substring(firstBrace, lastBrace + 1);
        const refix = fixJson(extracted);
        fixes.push('Extracted JSON between first { and last }');
        fixes.push(...refix.fixes);

        try {
            JSON.parse(refix.text);
            return { text: refix.text, fixes };
        } catch(e) {}

        // Strategy 2: Position-targeted repair loop
        let current = refix.text;
        let maxAttempts = 100;
        while (maxAttempts-- > 0) {
            try {
                JSON.parse(current);
                fixes.push('Position-targeted repairs applied');
                return { text: current, fixes };
            } catch(err) {
                const pos = extractErrorPosition(err.message);
                if (pos === -1 || pos >= current.length) break;
                const fixResult = fixAtPosition(current, pos, err.message);
                if (!fixResult.changed) break;
                current = fixResult.text;
            }
        }
    }

    // Strategy 3: Rebuild by escaping all HTML content values
    try {
        const rebuilt = rebuildJsonStrings(text);
        fixes.push('Rebuilt JSON with re-escaped string values');
        JSON.parse(rebuilt);
        return { text: rebuilt, fixes };
    } catch(e) {}

    return null;
}

function extractErrorPosition(errorMsg) {
    // "at position 3287" or "at position 3287 (line 60 column 357)"
    const match = errorMsg.match(/position\s+(\d+)/i);
    return match ? parseInt(match[1]) : -1;
}

function fixAtPosition(text, pos, errorMsg) {
    // Find what context we're in at this position
    let inString = false, escaped = false, strStart = -1;
    for (let i = 0; i < pos && i < text.length; i++) {
        if (escaped) { escaped = false; continue; }
        if (text[i] === '\\') { escaped = true; continue; }
        if (text[i] === '"') {
            inString = !inString;
            if (inString) strStart = i;
        }
    }

    const ch = text[pos];
    const before = text.substring(Math.max(0, pos - 5), pos);
    const after = text.substring(pos + 1, Math.min(text.length, pos + 6));

    // Fix 1: Unescaped quote inside a string (most common with HTML)
    if (inString && ch === '"') {
        // Check if this looks like it's inside HTML: href="..." or class="..."
        const lookback = text.substring(Math.max(0, pos - 30), pos);
        const lookahead = text.substring(pos, Math.min(text.length, pos + 30));

        // If it looks like HTML attribute: =" or " followed by HTML
        if (lookback.match(/[a-z-]+=$/i) || lookahead.match(/^"[^"]*"[^"]*>/)) {
            return { text: text.substring(0, pos) + '\\"' + text.substring(pos + 1), changed: true };
        }
        // Generic: escape the quote
        return { text: text.substring(0, pos) + '\\"' + text.substring(pos + 1), changed: true };
    }

    // Fix 2: Expected comma — insert comma before this position
    if (errorMsg.includes("Expected ','") || errorMsg.includes("Expected '}'")) {
        // Look backwards for a good insertion point
        let insertAt = pos;
        for (let i = pos - 1; i >= 0; i--) {
            if (text[i] === '"' || text[i] === '}' || text[i] === ']' || /\d/.test(text[i]) ||
                text[i] === 'e' || text[i] === 'l') {
                insertAt = i + 1;
                break;
            }
        }
        if (!inString) {
            return { text: text.substring(0, insertAt) + ',' + text.substring(insertAt), changed: true };
        }
    }

    // Fix 3: Unexpected character — try removing it
    if (errorMsg.includes('Unexpected') && !inString) {
        return { text: text.substring(0, pos) + text.substring(pos + 1), changed: true };
    }

    // Fix 4: If inside string and there's a newline, escape it
    if (inString && (ch === '\n' || ch === '\r')) {
        return { text: text.substring(0, pos) + '\\n' + text.substring(pos + 1), changed: true };
    }

    return { changed: false };
}

function rebuildJsonStrings(raw) {
    // Find the outermost braces
    const first = raw.indexOf('{');
    const last = raw.lastIndexOf('}');
    if (first === -1 || last === -1) return raw;
    let text = raw.substring(first, last + 1);

    // Re-escape all string values that contain HTML
    text = text.replace(/"(content|content_mr|description|description_mr|text|text_mr|answer|answer_mr)"\s*:\s*"((?:[^"\\]|\\.)*)"/g,
        function(match, key, value) {
            // Un-escape everything first, then re-escape properly
            let clean = value
                .replace(/\\"/g, '"')  // un-escape
                .replace(/\\\\/g, '\\'); // un-escape backslashes
            // Now re-escape
            clean = clean
                .replace(/\\/g, '\\\\')
                .replace(/"/g, '\\"')
                .replace(/\n/g, '\\n')
                .replace(/\r/g, '\\r')
                .replace(/\t/g, '\\t');
            return `"${key}": "${clean}"`;
        }
    );
    return text;
}

function fillForm(data) {
    if (data.title) document.getElementById('fTitle').value = data.title;
    if (data.excerpt) document.getElementById('fExcerpt').value = data.excerpt;
    if (data.tags && Array.isArray(data.tags)) document.getElementById('fTags').value = data.tags.join(', ');

    if (data.category) {
        const catSelect = document.getElementById('fCategory');
        for (let opt of catSelect.options) {
            if (opt.dataset.slug === data.category) { catSelect.value = opt.value; break; }
        }
    }

    if (data.meta) {
        if (data.meta.title) document.getElementById('fMetaTitle').value = data.meta.title;
        if (data.meta.description) document.getElementById('fMetaDesc').value = data.meta.description;
    }

    if (data.featured_image) {
        if (typeof data.featured_image === 'string') {
            document.getElementById('fImage').value = data.featured_image;
        } else if (typeof data.featured_image === 'object') {
            if (data.featured_image.path) document.getElementById('fImage').value = data.featured_image.path;
            if (data.featured_image.alt) document.getElementById('fImageAlt').value = data.featured_image.alt;
        }
    }

    document.getElementById('fContentJson').value = JSON.stringify(data, null, 2);
    document.getElementById('fStatus').value = 'published';
}

function showStatus(el, message, type) {
    el.classList.remove('hidden', 'text-green-700', 'text-red-700', 'text-amber-700',
        'bg-green-50', 'bg-red-50', 'bg-amber-50', 'border-green-200', 'border-red-200', 'border-amber-200',
        'dark:bg-green-900/20', 'dark:bg-red-900/20', 'dark:bg-amber-900/20');
    el.style.whiteSpace = 'pre-wrap';
    if (type === 'success') {
        el.classList.add('text-green-700', 'bg-green-50', 'border-green-200', 'dark:bg-green-900/20');
    } else if (type === 'error') {
        el.classList.add('text-red-700', 'bg-red-50', 'border-red-200', 'dark:bg-red-900/20');
    } else {
        el.classList.add('text-amber-700', 'bg-amber-50', 'border-amber-200', 'dark:bg-amber-900/20');
    }
    el.textContent = message;
}
</script>
@endsection
