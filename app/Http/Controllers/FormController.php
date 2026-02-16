<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\FormPricing;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class FormController extends Controller
{
    protected WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function show(Request $request, string $formKey)
    {
        $user = $request->user();
        $submissions = FormSubmission::where('user_id', $user->id)
            ->where('form_type', $formKey)
            ->orderBy('created_at', 'desc')
            ->get();

        $pricing = FormPricing::where('form_type', $formKey)->first();
        $walletBalance = $user->getWalletBalance();

        $viewMap = [
            'hamipatra' => 'forms.hamipatra',
            'self_declaration' => 'forms.self-declaration',
            'grievance' => 'forms.grievance',
            'new_application' => 'forms.new-application',
            'caste_validity' => 'forms.caste-validity',
            'income_cert' => 'forms.income-cert',
            'rajpatra_marathi' => 'forms.rajpatra-marathi',
            'rajpatra_english' => 'forms.rajpatra-english',
            'rajpatra_affidavit_712' => 'forms.rajpatra-712',
            'farmer_id_card' => 'forms.farmer-id-card',
        ];

        $view = $viewMap[$formKey] ?? 'forms.generic';

        $extra = [];
        if ($formKey === 'farmer_id_card') {
            $extra['districts'] = config('maharashtra.districts');
        }

        return view($view, compact('submissions', 'pricing', 'walletBalance', 'formKey', 'user') + $extra);
    }

    // C4: Named show methods (replace inline route closures)
    public function showHamipatra(Request $r) { return $this->show($r, 'hamipatra'); }
    public function showSelfDeclaration(Request $r) { return $this->show($r, 'self_declaration'); }
    public function showGrievance(Request $r) { return $this->show($r, 'grievance'); }
    public function showNewApplication(Request $r) { return $this->show($r, 'new_application'); }
    public function showCasteValidity(Request $r) { return $this->show($r, 'caste_validity'); }
    public function showIncomeCert(Request $r) { return $this->show($r, 'income_cert'); }
    public function showRajpatraMarathi(Request $r) { return $this->show($r, 'rajpatra_marathi'); }
    public function showRajpatraEnglish(Request $r) { return $this->show($r, 'rajpatra_english'); }
    public function showRajpatra712(Request $r) { return $this->show($r, 'rajpatra_affidavit_712'); }
    public function showFarmerIdCard(Request $r) { return $this->show($r, 'farmer_id_card'); }

    // C4: Named store methods (replace inline route closures)
    public function storeHamipatra(Request $r) { return $this->store($r, 'hamipatra'); }
    public function storeSelfDeclaration(Request $r) { return $this->store($r, 'self_declaration'); }
    public function storeGrievance(Request $r) { return $this->store($r, 'grievance'); }
    public function storeNewApplication(Request $r) { return $this->store($r, 'new_application'); }
    public function storeCasteValidity(Request $r) { return $this->store($r, 'caste_validity'); }
    public function storeIncomeCert(Request $r) { return $this->store($r, 'income_cert'); }
    public function storeRajpatraMarathi(Request $r) { return $this->store($r, 'rajpatra_marathi'); }
    public function storeRajpatraEnglish(Request $r) { return $this->store($r, 'rajpatra_english'); }
    public function storeRajpatra712(Request $r) { return $this->store($r, 'rajpatra_affidavit_712'); }
    public function storeFarmerIdCard(Request $r) { return $this->store($r, 'farmer_id_card'); }

    /**
     * Allowed form_data keys per form type to prevent arbitrary data injection.
     */
    private const ALLOWED_FIELDS = [
        'hamipatra' => ['to_name', 'to_designation', 'to_office', 'subject', 'body_text', 'date', 'place'],
        'self_declaration' => ['full_name', 'address', 'subject', 'declaration_text', 'date', 'place'],
        'grievance' => ['to_name', 'to_designation', 'to_office', 'subject', 'grievance_text', 'date', 'place'],
        'new_application' => ['to_name', 'to_designation', 'to_office', 'subject', 'body_text', 'date', 'place', 'attachments'],
        'caste_validity' => ['full_name', 'caste', 'sub_caste', 'address', 'purpose', 'date', 'place'],
        'income_cert' => ['full_name', 'income_amount', 'income_source', 'address', 'purpose', 'date', 'place'],
        'rajpatra_marathi' => ['parties', 'subject', 'body_text', 'date', 'place', 'witness1', 'witness2'],
        'rajpatra_english' => ['parties', 'subject', 'body_text', 'date', 'place', 'witness1', 'witness2'],
        'rajpatra_affidavit_712' => ['parties', 'subject', 'body_text', 'date', 'place', 'gat_no', 'survey_no'],
        'farmer_id_card' => [
            'name_english', 'dob', 'gender', 'mobile', 'aadhaar', 'farmer_id',
            'address_line1', 'address_state', 'address_district', 'address_taluka',
            'address_village', 'address_pincode', 'lives_at_farm', 'photo', 'photo_from_pdf',
            'land',
        ],
    ];

    public function store(Request $request, string $formKey)
    {
        $user = $request->user();

        $rules = ['applicant_name' => 'required|string|max:255'];
        if ($formKey === 'farmer_id_card') {
            $rules['photo'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
        }
        $request->validate($rules);

        // A2: Pre-check wallet balance before saving
        $pricing = FormPricing::where('form_type', $formKey)->where('is_active', true)->first();
        if ($pricing && $pricing->price > 0) {
            $balance = $user->getWalletBalance();
            if ($balance < $pricing->price) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "शिल्लक अपुरी आहे. आवश्यक: ₹{$pricing->price}, उपलब्ध: ₹{$balance}. कृपया वॉलेट रिचार्ज करा.");
            }
        }

        // A4: Filter form_data to allowed fields only
        $rawData = $request->except(['_token', 'applicant_name', 'photo']);
        $allowedKeys = self::ALLOWED_FIELDS[$formKey] ?? [];
        $formData = $allowedKeys
            ? array_intersect_key($rawData, array_flip($allowedKeys))
            : $rawData;

        // Handle photo upload for farmer_id_card
        if ($formKey === 'farmer_id_card') {
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('farmer_photos', 'public');
                $formData['photo'] = $photoPath;
            } elseif (!empty($formData['photo_from_pdf'])) {
                $url = $formData['photo_from_pdf'];
                $prefix = '/storage/';
                $pos = strpos($url, $prefix);
                if ($pos !== false) {
                    $formData['photo'] = substr($url, $pos + strlen($prefix));
                }
            }
            unset($formData['photo_from_pdf']);
        }

        // A1: Wrap in transaction — form + wallet deduction are atomic
        try {
            return DB::transaction(function () use ($user, $formKey, $request, $formData) {
                $submission = FormSubmission::create([
                    'user_id' => $user->id,
                    'form_type' => $formKey,
                    'applicant_name' => $request->applicant_name,
                    'form_data' => $formData,
                ]);

                $this->walletService->deduct($user, $formKey, (string) $submission->id);

                return redirect()->back()->with('success', 'फॉर्म यशस्वीरित्या सेव्ह झाला!');
            });
        } catch (\Exception $e) {
            Log::warning('Form submission failed', [
                'user_id' => $user->id,
                'form_type' => $formKey,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function submissions(Request $request, string $formKey)
    {
        $submissions = FormSubmission::where('user_id', $request->user()->id)
            ->where('form_type', $formKey)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($submissions);
    }

    public function delete(Request $request, $id)
    {
        $submission = FormSubmission::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $submission->delete();

        return redirect()->back()->with('success', 'फॉर्म यशस्वीरित्या हटवला!');
    }

    public function parseFarmerPdf(Request $request)
    {
        $request->validate(['pdf' => 'required|file|mimes:pdf|max:10240']);

        $result = ['photo_url' => null];

        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($request->file('pdf')->getPathname());

            foreach ($pdf->getObjects() as $obj) {
                $details = $obj->getDetails();
                if (($details['Subtype'] ?? '') === 'Image' && ($details['Filter'] ?? '') === 'DCTDecode') {
                    $imgData = $obj->getContent();
                    if (strlen($imgData) > 1000) {
                        $filename = 'farmer_photos/' . uniqid('pdf_') . '.jpg';
                        Storage::disk('public')->put($filename, $imgData);
                        $result['photo_url'] = asset('storage/' . $filename);
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            // Photo extraction failed silently
        }

        return response()->json($result);
    }

    public function print(Request $request, $id)
    {
        $submission = FormSubmission::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Increment print count
        $submission->increment('print_count');
        $submission->update(['last_printed_at' => now()]);

        $viewMap = [
            'hamipatra' => 'print.hamipatra',
            'self_declaration' => 'print.self-declaration',
            'grievance' => 'print.grievance',
            'new_application' => 'print.new-application',
            'caste_validity' => 'print.caste-validity',
            'income_cert' => 'print.income-cert',
            'rajpatra_marathi' => 'print.rajpatra-marathi',
            'rajpatra_english' => 'print.rajpatra-english',
            'rajpatra_affidavit_712' => 'print.rajpatra-712',
            'farmer_id_card' => 'print.farmer-id-card',
        ];

        $view = $viewMap[$submission->form_type] ?? 'print.generic';

        return view($view, ['submission' => $submission, 'data' => $submission->form_data]);
    }

    /**
     * Bulk print farmer ID cards.
     * mode=sidebyside: Front+Back side by side, 4 per page
     * mode=duplex: 8 fronts on page 1, 8 backs on page 2
     */
    public function bulkPrintFarmer(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $mode = $request->query('mode', 'sidebyside');

        $submissions = FormSubmission::where('user_id', $request->user()->id)
            ->where('form_type', 'farmer_id_card')
            ->whereIn('id', $ids)
            ->orderBy('id')
            ->get();

        if ($submissions->isEmpty()) {
            return redirect()->route('farmer-id-card')->with('error', 'कोणतेही कार्ड निवडलेले नाहीत.');
        }

        // Increment print count for all
        FormSubmission::whereIn('id', $submissions->pluck('id'))
            ->where('user_id', $request->user()->id)
            ->increment('print_count');
        FormSubmission::whereIn('id', $submissions->pluck('id'))
            ->where('user_id', $request->user()->id)
            ->update(['last_printed_at' => now()]);

        return view('print.farmer-id-card-bulk', [
            'submissions' => $submissions,
            'mode' => $mode,
        ]);
    }
}
