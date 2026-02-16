<?php

namespace App\Http\Controllers;

use App\Models\PanCardApplication;
use App\Models\VoterIdApplication;
use App\Models\BandkamRegistration;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function panCard(Request $request): StreamedResponse
    {
        $rows = PanCardApplication::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();

        return $this->streamCsv('pan-card-export.csv', [
            'ID', 'Type', 'Status', 'Name', 'Mobile', 'Aadhar', 'DOB', 'App No', 'Amount', 'Received', 'Payment Status', 'Payment Mode', 'Date'
        ], $rows->map(fn($r) => [
            $r->id, ucfirst($r->application_type), ucfirst($r->status), $r->applicant_name, $r->mobile_number,
            $r->aadhar_number, $r->dob?->format('d/m/Y'), $r->application_number,
            $r->amount, $r->received_amount, ucfirst($r->payment_status), ucfirst($r->payment_mode), $r->created_at->format('d/m/Y')
        ])->toArray());
    }

    public function voterId(Request $request): StreamedResponse
    {
        $rows = VoterIdApplication::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();

        return $this->streamCsv('voter-id-export.csv', [
            'ID', 'Type', 'Status', 'Name', 'Mobile', 'Aadhar', 'DOB', 'App No', 'Amount', 'Received', 'Payment Status', 'Payment Mode', 'Date'
        ], $rows->map(fn($r) => [
            $r->id, ucfirst($r->application_type), ucfirst($r->status), $r->applicant_name, $r->mobile_number,
            $r->aadhar_number, $r->dob?->format('d/m/Y'), $r->application_number,
            $r->amount, $r->received_amount, ucfirst($r->payment_status), ucfirst($r->payment_mode), $r->created_at->format('d/m/Y')
        ])->toArray());
    }

    public function bandkam(Request $request): StreamedResponse
    {
        $rows = BandkamRegistration::where('user_id', $request->user()->id)->with('schemes')->orderBy('created_at', 'desc')->get();

        return $this->streamCsv('bandkam-export.csv', [
            'ID', 'Type', 'Status', 'Name', 'Mobile', 'Aadhar', 'Village', 'Taluka', 'District',
            'App No', 'Form Date', 'Online Date', 'Appointment', 'Activation', 'Expiry',
            'Amount', 'Received', 'Payment Status', 'Payment Mode', 'Schemes Count'
        ], $rows->map(fn($r) => [
            $r->id, ucfirst($r->registration_type), ucfirst($r->status), $r->applicant_name, $r->mobile_number,
            $r->aadhar_number, $r->village, $r->taluka, $r->district, $r->application_number,
            $r->form_date?->format('d/m/Y'), $r->online_date?->format('d/m/Y'), $r->appointment_date?->format('d/m/Y'),
            $r->activation_date?->format('d/m/Y'), $r->expiry_date?->format('d/m/Y'),
            $r->amount, $r->received_amount, ucfirst($r->payment_status), ucfirst($r->payment_mode), $r->schemes->count()
        ])->toArray());
    }

    private function streamCsv(string $filename, array $headers, array $rows): StreamedResponse
    {
        return new StreamedResponse(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
