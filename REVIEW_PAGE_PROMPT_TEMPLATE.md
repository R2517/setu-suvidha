# 🔥 SETU Suvidha — Review/SEO Page Builder Mega Prompt

## How to Use This Prompt

Copy the template below, fill in the `[PLACEHOLDERS]`, and paste it to Cascade. It will build a complete review page with route, controller entry, blade view, SEO meta, schema markup, and interlinks.

---

## ✅ PROMPT TEMPLATE (Copy from here ↓)

```
Build a complete SEO Review/Guide page for setusuvidha.com with the following specs:

## 📋 SERVICE/YOJANA DETAILS

- **Service/Yojana Name (English)**: [e.g. PAN Card Online]
- **Service/Yojana Name (Marathi)**: [e.g. पॅन कार्ड ऑनलाइन]
- **URL Slug**: [e.g. pan-card-online-guide-2026]
- **Category Tag**: [e.g. ओळखपत्र सेवा / शासकीय योजना / महिला कल्याण / शेतकरी सेवा]
- **Lucide Icon**: [e.g. credit-card / heart / leaf / file-text / shield]
- **Theme Color**: [e.g. blue / green / pink / amber / purple]
- **Published Date**: [e.g. २१ फेब्रुवारी २०२६]
- **Read Time**: [e.g. १० मिनिटे]

## 📝 PAGE TYPE (choose one or multiple):

- [ ] **Review/Guide** — संपूर्ण मार्गदर्शक (information + SEO article)
- [ ] **Sale Service** — setusuvidha.com वरून directly बनवता येते (has CTA to /services/[slug])
- [ ] **Download Format** — PDF/form download available on site
- [ ] **Government Yojana** — सरकारी योजना माहिती (eligibility, documents, benefits, apply link)
- [ ] **Just SEO/Review** — only informational, no sale on our site (external links to official site)

## 🏗️ CONTENT SECTIONS TO INCLUDE:

### Mandatory Sections:
1. **Hero Section** — Title (EN + MR), breadcrumb, category badge, date, read time
2. **CTA Banner** — If sale service: direct link to our service page
3. **What is [Service]?** — EN + MR explanation (2 paragraphs)
4. **Why Important?** — Why this service matters (EN + MR)
5. **Key Benefits** — 8-10 benefits with emoji, title, description (grid cards)
6. **Required Documents** — List with emoji icons (divided list)
7. **Step-by-Step Guide** — How to apply/get this service (numbered steps)
8. **FAQ** — 6-8 bilingual Q&A (details/summary accordion)
9. **Final CTA** — Big call-to-action box
10. **Interlinks** — 2-3 related review pages (cards with links)

### Optional Sections (include if relevant):
- [ ] **Eligibility Criteria** — Who is eligible / who is NOT eligible
- [ ] **Fees/Pricing** — Official fees vs our pricing
- [ ] **Government Scheme Details** — Budget, beneficiary count, DBT amount
- [ ] **State-wise Availability** — Grid of Indian states
- [ ] **Comparison Table** — Online vs Offline / Our service vs Government
- [ ] **Video/Image** — Embedded tutorial or sample card preview
- [ ] **Download Sample** — Sample PDF preview
- [ ] **Related Schemes** — Connected government schemes
- [ ] **News/Updates** — Latest updates section
- [ ] **Statistics** — Key numbers in 3-column grid cards

## 🔍 SEO REQUIREMENTS:

1. **Title tag**: "[Service] Online [Year]: [Marathi title] — [English subtitle]"
2. **Meta description**: Bilingual (EN + MR), 150-160 chars, include primary keywords
3. **Meta keywords**: 20+ keywords (EN + MR + Hindi mix)
4. **Open Graph**: og:title, og:description, og:type=article, og:url
5. **Canonical URL**: Self-referencing canonical
6. **Schema Markup (JSON-LD)**:
   - Article schema (headline, datePublished, author=SETU Suvidha)
   - FAQPage schema (all FAQ Q&As)
7. **H1**: Only one, bilingual, keyword-rich
8. **H2s**: Each section has keyword-optimized H2
9. **Internal Links**: Link to related /services/ and /reviews/ pages
10. **Breadcrumb**: मुख्यपृष्ठ > Review > [This Page]

## 🔧 TECHNICAL REQUIREMENTS:

1. **Add to ReviewController articles() array** with slug, title, title_en, excerpt, icon, color, category, date, read_time, view
2. **Create Blade file** at `resources/views/reviews/[name].blade.php`
3. **Extend layouts.app**, use @section('title'), @section('description'), @push('meta'), @push('styles')
4. **Use existing design system**: Tailwind CSS, Lucide icons, dark mode support
5. **Responsive**: Mobile-first, collapsible TOC on mobile, sidebar TOC on desktop
6. **Match exact style** of `reviews/farmer-id-card-guide.blade.php`
7. **All text bilingual**: English + Marathi in every section
8. **CTA buttons**: Link to our /services/ page if sale service, or official government URL if just review
9. **No JavaScript required** — Pure HTML/Blade/Tailwind
10. **Commit and push** after creation

## 📌 SPECIFIC CONTENT (fill this with actual data):

### What is this service? (2 paragraphs, EN + MR):
[Write or say "research and write" — Cascade will generate expert content]

### Key Benefits (8-10):
[List benefits OR say "research and generate relevant benefits"]

### Required Documents:
[List documents OR say "research standard documents needed"]

### Step-by-Step Process:
[List steps OR say "generate based on our existing service flow / official process"]

### FAQ Questions (6-8):
[List Q&As OR say "generate SEO-optimized FAQs with bilingual answers"]

### Related Review Pages to Interlink:
[List 2-3 existing review slugs, e.g. farmer-id-card-online-guide-2026, ladki-bahin-yojana-maharashtra-2026]

---

## IMPORTANT RULES:
- Use @@context, @@type (double @) in JSON-LD schema to avoid Blade conflicts
- All content must be ORIGINAL, not copied
- Marathi + English in EVERY section (not just one language)
- Keywords must target Google India search intent
- Page must score 90+ on Lighthouse SEO audit
- Mobile responsive with proper touch targets
- Exact same UI quality as farmer-id-card-guide.blade.php
- Follow Laravel 11 + Blade + Tailwind conventions
- Add to ReviewController, commit, and push to GitHub
```

---

## 🎯 EXAMPLE USAGE

### Example 1: PAN Card Guide (Sale + Review)
```
Build a complete SEO Review/Guide page for setusuvidha.com:

- Service Name (EN): PAN Card Online Application
- Service Name (MR): पॅन कार्ड ऑनलाइन अर्ज
- URL Slug: pan-card-online-guide-2026
- Category: ओळखपत्र सेवा
- Icon: credit-card
- Color: blue
- Date: २१ फेब्रुवारी २०२६
- Read Time: १० मिनिटे
- Page Type: Review/Guide + Sale Service
- Sale Link: /services/pan-card (if we have it)
- Content: Research and write all sections
- Include: Eligibility, Fees, Comparison (Online vs Offline), FAQ
- Interlinks: farmer-id-card-online-guide-2026, ladki-bahin-yojana-maharashtra-2026
```

### Example 2: Ladki Bahin Yojana (Just Review/SEO)
```
Build a complete SEO Review/Guide page:

- Service Name (EN): Ladki Bahin Yojana Maharashtra
- Service Name (MR): मुख्यमंत्री माझी लाडकी बहीण योजना
- URL Slug: ladki-bahin-yojana-maharashtra-2026
- Category: महिला कल्याण योजना
- Icon: heart
- Color: pink
- Page Type: Government Yojana + Just SEO/Review
- Include: Eligibility, Statistics (₹1500/month, beneficiary count), State-wise data, News/Updates
- External Link: https://ladkibahin.maharashtra.gov.in
```

### Example 3: Ration Card (Download Format + Review)
```
Build a complete SEO Review/Guide page:

- Service Name (EN): Ration Card Online Application Maharashtra
- Service Name (MR): रेशन कार्ड ऑनलाइन अर्ज महाराष्ट्र
- URL Slug: ration-card-online-maharashtra-2026
- Category: शासकीय सेवा
- Icon: file-text
- Color: orange
- Page Type: Review/Guide + Download Format
- Include: Eligibility, Fees, Types (APL/BPL/AAY), Documents, Comparison
- Download: Application form PDF sample
```

---

## 📂 FILES THAT GET CREATED/MODIFIED:

| File | Action |
|------|--------|
| `app/Http/Controllers/ReviewController.php` | Add article entry to `articles()` array |
| `resources/views/reviews/[slug].blade.php` | New review page blade file |
| `routes/web.php` | No change needed (dynamic `{slug}` route already exists) |

## 🏛️ ARCHITECTURE REFERENCE:

- **Route**: `GET /reviews/{slug}` → `ReviewController@show`
- **Controller**: `ReviewController` has `articles()` array, `show()` renders matching blade
- **Views**: `resources/views/reviews/*.blade.php`
- **Layout**: Extends `layouts.app`
- **Design**: Tailwind CSS + Lucide icons + dark mode
- **Pattern**: Static blade files (no database), registered in controller array
