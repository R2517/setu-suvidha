<?php

namespace App\Http\Controllers;

use App\Models\DocslipService;
use App\Models\DocslipDocument;
use App\Models\DocslipPrint;
use App\Models\DocslipSavedRemark;
use Illuminate\Http\Request;

class DocslipController extends Controller
{
    // ==================== MAIN SLIP PAGE ====================

    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $services = DocslipService::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $hasData = DocslipService::where('user_id', $userId)->exists();
        $profile = $request->user()->profile;
        $savedRemarks = DocslipSavedRemark::where('user_id', $userId)->orderBy('sort_order')->get();

        return view('docslip.index', compact('services', 'hasData', 'profile', 'savedRemarks'));
    }

    public function mergeDocuments(Request $request)
    {
        $request->validate([
            'service_ids' => 'required|array',
            'service_ids.*' => 'integer',
        ]);

        $userId = $request->user()->id;
        $serviceIds = $request->service_ids;

        // Get all documents for selected services, scoped to this user
        $documents = DocslipDocument::where('user_id', $userId)
            ->where('is_active', true)
            ->whereHas('services', function ($q) use ($serviceIds) {
                $q->whereIn('docslip_services.id', $serviceIds);
            })
            ->orderBy('sort_order')
            ->get(['id', 'name_mr', 'name_en', 'remark']);

        // Already unique by query (each doc appears once)
        return response()->json(['documents' => $documents]);
    }

    public function printSlip(Request $request)
    {
        $request->validate([
            'service_ids' => 'required|array',
            'documents' => 'required|array',
        ]);

        $userId = $request->user()->id;

        // Get service names for the log
        $serviceNames = DocslipService::where('user_id', $userId)
            ->whereIn('id', $request->service_ids)
            ->pluck('name_mr', 'id')
            ->toArray();

        DocslipPrint::create([
            'user_id' => $userId,
            'customer_name' => $request->customer_name,
            'customer_mobile' => $request->customer_mobile,
            'services_selected' => array_map(fn($id) => [
                'id' => $id,
                'name' => $serviceNames[$id] ?? '',
            ], $request->service_ids),
            'documents_merged' => $request->documents,
            'remark' => $request->remark,
        ]);

        return response()->json(['success' => true]);
    }

    // ==================== SETTINGS ====================

    public function settings(Request $request)
    {
        $userId = $request->user()->id;
        $services = DocslipService::where('user_id', $userId)->orderBy('sort_order')->get();
        $documents = DocslipDocument::where('user_id', $userId)->orderBy('sort_order')->get();

        // Load pivot data: service_id => [doc_id, doc_id, ...]
        $mappings = [];
        foreach ($services as $svc) {
            $mappings[$svc->id] = $svc->documents()->pluck('docslip_documents.id')->toArray();
        }

        return view('docslip.settings', compact('services', 'documents', 'mappings'));
    }

    // --- Services CRUD ---

    public function storeService(Request $request)
    {
        $request->validate([
            'name_mr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $maxSort = DocslipService::where('user_id', $request->user()->id)->max('sort_order') ?? 0;

        DocslipService::create([
            'user_id' => $request->user()->id,
            'name_mr' => $request->name_mr,
            'name_en' => $request->name_en,
            'icon' => $request->icon,
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'सेवा जोडली!');
    }

    public function updateService(Request $request, $id)
    {
        $request->validate([
            'name_mr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $svc = DocslipService::where('user_id', $request->user()->id)->findOrFail($id);
        $svc->update($request->only('name_mr', 'name_en', 'icon', 'is_active'));

        return back()->with('success', 'सेवा अपडेट केली!');
    }

    public function destroyService(Request $request, $id)
    {
        DocslipService::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return back()->with('success', 'सेवा हटवली!');
    }

    // --- Documents CRUD ---

    public function storeDocument(Request $request)
    {
        $request->validate([
            'name_mr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $maxSort = DocslipDocument::where('user_id', $request->user()->id)->max('sort_order') ?? 0;

        DocslipDocument::create([
            'user_id' => $request->user()->id,
            'name_mr' => $request->name_mr,
            'name_en' => $request->name_en,
            'sort_order' => $maxSort + 1,
        ]);

        return back()->with('success', 'कागदपत्र जोडले!');
    }

    public function updateDocument(Request $request, $id)
    {
        $request->validate([
            'name_mr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
        ]);

        $doc = DocslipDocument::where('user_id', $request->user()->id)->findOrFail($id);
        $doc->update($request->only('name_mr', 'name_en', 'remark', 'is_active'));

        return back()->with('success', 'कागदपत्र अपडेट केले!');
    }

    public function destroyDocument(Request $request, $id)
    {
        DocslipDocument::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return back()->with('success', 'कागदपत्र हटवले!');
    }

    // --- Mappings ---

    public function syncDocuments(Request $request, $id)
    {
        $request->validate([
            'document_ids' => 'present|array',
            'document_ids.*' => 'integer',
        ]);

        $svc = DocslipService::where('user_id', $request->user()->id)->findOrFail($id);
        $svc->documents()->sync($request->document_ids ?? []);

        return response()->json(['success' => true]);
    }

    // --- Load Defaults ---

    public function loadDefaults(Request $request)
    {
        $userId = $request->user()->id;

        // Default services
        $defaultServices = [
            ['name_mr' => 'जात प्रमाणपत्र', 'name_en' => 'Caste Certificate', 'icon' => 'users'],
            ['name_mr' => 'अधिवास प्रमाणपत्र', 'name_en' => 'Domicile Certificate', 'icon' => 'home'],
            ['name_mr' => 'उत्पन्न प्रमाणपत्र', 'name_en' => 'Income Certificate', 'icon' => 'landmark'],
            ['name_mr' => 'जन्म दाखला', 'name_en' => 'Birth Certificate', 'icon' => 'baby'],
            ['name_mr' => 'मृत्यू दाखला', 'name_en' => 'Death Certificate', 'icon' => 'file-minus'],
            ['name_mr' => 'नॉन-क्रिमिलेअर', 'name_en' => 'Non-Creamy Layer', 'icon' => 'graduation-cap'],
            ['name_mr' => 'वय-राष्ट्रीयत्व-अधिवास', 'name_en' => 'Age-Nationality-Domicile', 'icon' => 'file-check'],
            ['name_mr' => 'शेतकरी प्रमाणपत्र', 'name_en' => 'Farmer Certificate', 'icon' => 'leaf'],
            ['name_mr' => 'EWS प्रमाणपत्र', 'name_en' => 'EWS Certificate', 'icon' => 'book-open'],
            ['name_mr' => 'विवाह नोंदणी', 'name_en' => 'Marriage Registration', 'icon' => 'heart'],
            ['name_mr' => 'पॅन कार्ड', 'name_en' => 'PAN Card', 'icon' => 'credit-card'],
            ['name_mr' => 'वोटर आयडी', 'name_en' => 'Voter ID', 'icon' => 'vote'],
            ['name_mr' => 'पासपोर्ट', 'name_en' => 'Passport', 'icon' => 'plane'],
            ['name_mr' => 'आयुष्मान कार्ड', 'name_en' => 'Ayushman Card', 'icon' => 'heart-pulse'],
            ['name_mr' => 'ई-श्रम कार्ड', 'name_en' => 'E-Shram Card', 'icon' => 'hard-hat'],
            ['name_mr' => 'राशन कार्ड', 'name_en' => 'Ration Card', 'icon' => 'wheat'],
            ['name_mr' => 'दुकान परवाना', 'name_en' => 'Shop License / Gumasta', 'icon' => 'store'],
            ['name_mr' => 'बांधकाम कामगार नोंदणी', 'name_en' => 'Construction Worker Registration', 'icon' => 'hard-hat'],
        ];

        // Default documents
        $defaultDocuments = [
            ['name_mr' => 'आधार कार्ड', 'name_en' => 'Aadhar Card'],
            ['name_mr' => 'रेशन कार्ड', 'name_en' => 'Ration Card'],
            ['name_mr' => 'पासपोर्ट साइज फोटो', 'name_en' => 'Passport Size Photo'],
            ['name_mr' => 'शाळा सोडल्याचा दाखला / LC', 'name_en' => 'School Leaving Certificate'],
            ['name_mr' => 'जन्म दाखला', 'name_en' => 'Birth Certificate'],
            ['name_mr' => 'उत्पन्नाचा दाखला', 'name_en' => 'Income Certificate'],
            ['name_mr' => 'रहिवासी पुरावा', 'name_en' => 'Address Proof'],
            ['name_mr' => 'वडिलांचा जातीचा दाखला', 'name_en' => "Father's Caste Certificate"],
            ['name_mr' => 'स्वयंघोषणापत्र', 'name_en' => 'Self Declaration / Affidavit'],
            ['name_mr' => 'लाईट बिल / वीज बिल', 'name_en' => 'Electricity Bill'],
            ['name_mr' => '७/१२ उतारा', 'name_en' => '7/12 Extract'],
            ['name_mr' => '८-अ उतारा', 'name_en' => '8-A Extract'],
            ['name_mr' => 'मतदान ओळखपत्र', 'name_en' => 'Voter ID Card'],
            ['name_mr' => 'पॅन कार्ड', 'name_en' => 'PAN Card'],
            ['name_mr' => 'बँक पासबुक', 'name_en' => 'Bank Passbook'],
            ['name_mr' => 'मोबाईल नंबर (आधार लिंक)', 'name_en' => 'Aadhar-linked Mobile'],
            ['name_mr' => 'ई-मेल आयडी', 'name_en' => 'Email ID'],
            ['name_mr' => 'विद्यापीठ प्रमाणपत्र', 'name_en' => 'University Certificate'],
            ['name_mr' => 'नोकरीचा पुरावा / सेवा प्रमाणपत्र', 'name_en' => 'Employment Proof'],
            ['name_mr' => 'वैद्यकीय प्रमाणपत्र', 'name_en' => 'Medical Certificate'],
            ['name_mr' => 'विवाह प्रमाणपत्र', 'name_en' => 'Marriage Certificate'],
            ['name_mr' => 'मृत्यू प्रमाणपत्र', 'name_en' => 'Death Certificate'],
            ['name_mr' => 'शपथपत्र', 'name_en' => 'Affidavit'],
            ['name_mr' => 'पोलीस व्हेरिफिकेशन', 'name_en' => 'Police Verification'],
            ['name_mr' => '15 वर्षे वास्तव्याचा पुरावा', 'name_en' => '15 Year Residence Proof'],
        ];

        // Create services
        $serviceMap = []; // name_mr => id
        foreach ($defaultServices as $i => $s) {
            $svc = DocslipService::firstOrCreate(
                ['user_id' => $userId, 'name_mr' => $s['name_mr']],
                ['name_en' => $s['name_en'], 'icon' => $s['icon'], 'sort_order' => $i + 1]
            );
            $serviceMap[$s['name_mr']] = $svc->id;
        }

        // Create documents
        $docMap = []; // name_mr => id
        foreach ($defaultDocuments as $i => $d) {
            $doc = DocslipDocument::firstOrCreate(
                ['user_id' => $userId, 'name_mr' => $d['name_mr']],
                ['name_en' => $d['name_en'], 'sort_order' => $i + 1]
            );
            $docMap[$d['name_mr']] = $doc->id;
        }

        // Default mappings
        $defaultMappings = [
            'जात प्रमाणपत्र' => ['आधार कार्ड', 'रेशन कार्ड', 'शाळा सोडल्याचा दाखला / LC', 'वडिलांचा जातीचा दाखला', 'स्वयंघोषणापत्र', 'पासपोर्ट साइज फोटो'],
            'अधिवास प्रमाणपत्र' => ['आधार कार्ड', 'रेशन कार्ड', 'शाळा सोडल्याचा दाखला / LC', 'लाईट बिल / वीज बिल', '15 वर्षे वास्तव्याचा पुरावा', 'पासपोर्ट साइज फोटो'],
            'उत्पन्न प्रमाणपत्र' => ['आधार कार्ड', 'रेशन कार्ड', 'शाळा सोडल्याचा दाखला / LC', 'स्वयंघोषणापत्र', '७/१२ उतारा', 'पासपोर्ट साइज फोटो'],
            'नॉन-क्रिमिलेअर' => ['आधार कार्ड', 'रेशन कार्ड', 'उत्पन्नाचा दाखला', 'वडिलांचा जातीचा दाखला', 'शाळा सोडल्याचा दाखला / LC', 'स्वयंघोषणापत्र'],
            'पॅन कार्ड' => ['आधार कार्ड', 'पासपोर्ट साइज फोटो', 'मोबाईल नंबर (आधार लिंक)', 'ई-मेल आयडी'],
            'वोटर आयडी' => ['आधार कार्ड', 'पासपोर्ट साइज फोटो', 'रहिवासी पुरावा', 'जन्म दाखला'],
            'EWS प्रमाणपत्र' => ['आधार कार्ड', 'रेशन कार्ड', 'उत्पन्नाचा दाखला', 'स्वयंघोषणापत्र', 'बँक पासबुक'],
            'जन्म दाखला' => ['आधार कार्ड', 'रहिवासी पुरावा', 'विवाह प्रमाणपत्र'],
            'विवाह नोंदणी' => ['आधार कार्ड', 'पासपोर्ट साइज फोटो', 'जन्म दाखला', 'रहिवासी पुरावा'],
            'पासपोर्ट' => ['आधार कार्ड', 'पॅन कार्ड', 'पासपोर्ट साइज फोटो', 'मतदान ओळखपत्र', 'जन्म दाखला', 'रहिवासी पुरावा', 'लाईट बिल / वीज बिल'],
            'ई-श्रम कार्ड' => ['आधार कार्ड', 'बँक पासबुक', 'मोबाईल नंबर (आधार लिंक)', 'पासपोर्ट साइज फोटो'],
            'आयुष्मान कार्ड' => ['आधार कार्ड', 'रेशन कार्ड', 'मोबाईल नंबर (आधार लिंक)', 'पासपोर्ट साइज फोटो'],
            'राशन कार्ड' => ['आधार कार्ड', 'रहिवासी पुरावा', 'उत्पन्नाचा दाखला', 'पासपोर्ट साइज फोटो'],
            'बांधकाम कामगार नोंदणी' => ['आधार कार्ड', 'रेशन कार्ड', 'बँक पासबुक', 'पासपोर्ट साइज फोटो', 'नोकरीचा पुरावा / सेवा प्रमाणपत्र'],
            'शेतकरी प्रमाणपत्र' => ['आधार कार्ड', '७/१२ उतारा', '८-अ उतारा', 'पासपोर्ट साइज फोटो', 'बँक पासबुक'],
            'दुकान परवाना' => ['आधार कार्ड', 'पॅन कार्ड', 'पासपोर्ट साइज फोटो', 'रहिवासी पुरावा'],
            'वय-राष्ट्रीयत्व-अधिवास' => ['आधार कार्ड', 'शाळा सोडल्याचा दाखला / LC', 'जन्म दाखला', 'रहिवासी पुरावा', 'पासपोर्ट साइज फोटो'],
            'मृत्यू दाखला' => ['आधार कार्ड', 'वैद्यकीय प्रमाणपत्र', 'रहिवासी पुरावा'],
        ];

        // Sync pivot
        foreach ($defaultMappings as $serviceName => $docNames) {
            if (!isset($serviceMap[$serviceName])) continue;
            $svcId = $serviceMap[$serviceName];
            $docIds = array_filter(array_map(fn($n) => $docMap[$n] ?? null, $docNames));
            if (!empty($docIds)) {
                DocslipService::find($svcId)?->documents()->syncWithoutDetaching($docIds);
            }
        }

        return back()->with('success', 'डिफॉल्ट सेवा आणि कागदपत्रे लोड केली!');
    }

    // --- Reset ---

    public function reset(Request $request)
    {
        $userId = $request->user()->id;
        DocslipService::where('user_id', $userId)->delete();
        DocslipDocument::where('user_id', $userId)->delete();
        DocslipPrint::where('user_id', $userId)->delete();

        return back()->with('success', 'सर्व DocSlip डेटा रीसेट केला!');
    }

    // ==================== SAVED REMARKS ====================

    public function storeSavedRemark(Request $request)
    {
        $request->validate(['text' => 'required|string|max:255']);
        $userId = $request->user()->id;

        // Max 25 saved remarks
        $count = DocslipSavedRemark::where('user_id', $userId)->count();
        if ($count >= 25) {
            return response()->json(['error' => 'जास्तीत जास्त 25 remarks सेव्ह करता येतात!'], 422);
        }

        // Don't duplicate
        $exists = DocslipSavedRemark::where('user_id', $userId)->where('text', $request->text)->exists();
        if ($exists) {
            return response()->json(['error' => 'हा remark आधीच सेव्ह आहे!'], 422);
        }

        $maxSort = DocslipSavedRemark::where('user_id', $userId)->max('sort_order') ?? 0;
        $remark = DocslipSavedRemark::create([
            'user_id' => $userId,
            'text' => $request->text,
            'sort_order' => $maxSort + 1,
        ]);

        return response()->json(['success' => true, 'remark' => $remark]);
    }

    public function destroySavedRemark(Request $request, $id)
    {
        DocslipSavedRemark::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ==================== HISTORY ====================

    public function history(Request $request)
    {
        $userId = $request->user()->id;
        $query = DocslipPrint::where('user_id', $userId)->orderBy('created_at', 'desc');

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_mobile', 'like', "%{$s}%");
            });
        }

        $prints = $query->paginate(15)->appends($request->query());

        return view('docslip.history', compact('prints'));
    }
}
