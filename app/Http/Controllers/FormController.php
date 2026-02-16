<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\FormPricing;
use App\Services\WalletService;
use Illuminate\Http\Request;
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

    public function store(Request $request, string $formKey)
    {
        $user = $request->user();

        $request->validate([
            'applicant_name' => 'required|string|max:255',
        ]);

        $formData = $request->except(['_token', 'applicant_name', 'photo']);

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
        }

        $submission = FormSubmission::create([
            'user_id' => $user->id,
            'form_type' => $formKey,
            'applicant_name' => $request->applicant_name,
            'form_data' => $formData,
        ]);

        try {
            $this->walletService->deduct($user, $formKey, (string) $submission->id);
        } catch (\Exception $e) {
            // If wallet deduction fails, still keep submission but warn user
        }

        return redirect()->back()->with('success', 'फॉर्म यशस्वीरित्या सेव्ह झाला!');
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
