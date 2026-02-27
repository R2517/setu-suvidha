<?php

namespace App\Http\Controllers;

use App\Models\PanCardApplication;
use App\Models\VoterIdApplication;
use App\Models\BandkamRegistration;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    private function maskAadhaar(?string $aadhaar): string
    {
        if (empty($aadhaar)) {
            return '';
        }
        if (strlen($aadhaar) >= 8) {
            return substr($aadhaar, 0, 4) . '****' . substr($aadhaar, -4);
        }
        return '****';
    }

    public function panCard(Request $request): StreamedResponse
    {
        $headers = ['ID', 'Type', 'Status', 'Name', 'Mobile', 'Aadhar', 'DOB', 'App No', 'Amount', 'Received', 'Payment Status', 'Payment Mode', 'Date'];

        return new StreamedResponse(function () use ($request, $headers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $headers);

            PanCardApplication::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $r) {
                        fputcsv($handle, [
                            $r->id, ucfirst($r->application_type), ucfirst($r->status), $r->applicant_name, $r->mobile_number,
                            $this->maskAadhaar($r->aadhaar_number), $r->dob?->format('d/m/Y'), $r->application_number,
                            $r->amount, $r->received_amount, ucfirst($r->payment_status), ucfirst($r->payment_mode), $r->created_at->format('d/m/Y')
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="pan-card-export.csv"',
        ]);
    }

    public function voterId(Request $request): StreamedResponse
    {
        $headers = ['ID', 'Type', 'Status', 'Name', 'Mobile', 'Aadhar', 'DOB', 'App No', 'Amount', 'Received', 'Payment Status', 'Payment Mode', 'Date'];

        return new StreamedResponse(function () use ($request, $headers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $headers);

            VoterIdApplication::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $r) {
                        fputcsv($handle, [
                            $r->id, ucfirst($r->application_type), ucfirst($r->status), $r->applicant_name, $r->mobile_number,
                            $this->maskAadhaar($r->aadhaar_number), $r->dob?->format('d/m/Y'), $r->application_number,
                            $r->amount, $r->received_amount, ucfirst($r->payment_status), ucfirst($r->payment_mode), $r->created_at->format('d/m/Y')
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="voter-id-export.csv"',
        ]);
    }

    public function bandkam(Request $request): StreamedResponse
    {
        $headers = ['ID', 'Type', 'Status', 'Name', 'Mobile', 'Aadhar', 'Village', 'Taluka', 'District',
            'App No', 'Form Date', 'Online Date', 'Appointment', 'Activation', 'Expiry',
            'Amount', 'Received', 'Payment Status', 'Payment Mode', 'Schemes Count'];

        return new StreamedResponse(function () use ($request, $headers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $headers);

            BandkamRegistration::where('user_id', $request->user()->id)
                ->with('schemes')
                ->orderBy('created_at', 'desc')
                ->chunk(500, function ($rows) use ($handle) {
                    foreach ($rows as $r) {
                        fputcsv($handle, [
                            $r->id, ucfirst($r->registration_type), ucfirst($r->status), $r->applicant_name, $r->mobile_number,
                            $this->maskAadhaar($r->aadhaar_number), $r->village, $r->taluka, $r->district, $r->application_number,
                            $r->form_date?->format('d/m/Y'), $r->online_date?->format('d/m/Y'), $r->appointment_date?->format('d/m/Y'),
                            $r->activation_date?->format('d/m/Y'), $r->expiry_date?->format('d/m/Y'),
                            $r->amount, $r->received_amount, ucfirst($r->payment_status), ucfirst($r->payment_mode), $r->schemes->count()
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="bandkam-export.csv"',
        ]);
    }
}
