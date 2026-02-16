<?php

namespace App\Http\Controllers;

use App\Models\PanCardApplication;
use Illuminate\Http\Request;

class PanCardController extends Controller
{
    use Traits\CrmControllerTrait;
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $counts = $this->getStatusCounts(PanCardApplication::class, $userId);

        $query = PanCardApplication::where('user_id', $userId)->orderBy('created_at', 'desc');
        $this->applyCrmFilters($query, $request);
        $applications = $query->paginate(25)->withQueryString();

        return view('crm.pan-card', compact('applications') + $counts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'application_type' => 'required|in:new,correction,reprint',
            'aadhar_number' => 'nullable|digits:12',
            'dob' => 'nullable|date|before:today',
            'amount' => 'nullable|numeric|min:0',
            'received_amount' => 'nullable|numeric|min:0',
            'payment_mode' => 'nullable|in:cash,online,upi,cheque',
        ]);

        $amt = (float) ($request->amount ?? 0);
        $rcv = (float) ($request->received_amount ?? 0);

        PanCardApplication::create([
            'user_id' => $request->user()->id,
            'applicant_name' => $request->applicant_name,
            'mobile_number' => $request->mobile_number,
            'aadhar_number' => $request->aadhar_number,
            'application_type' => $request->application_type,
            'application_number' => $request->application_number ?: 'PAN-' . strtoupper(substr(uniqid(), -6)),
            'dob' => $request->dob,
            'amount' => $amt,
            'received_amount' => $rcv,
            'payment_mode' => $request->payment_mode ?? 'cash',
            'payment_status' => $this->calculatePaymentStatus($amt, $rcv),
            'status' => 'pending',
        ]);

        return redirect()->route('pan-card')->with('success', 'PAN Card अर्ज जतन झाला! ✅');
    }

    public function update(Request $request, $id)
    {
        $app = PanCardApplication::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $request->validate([
            'applicant_name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|digits:10',
            'aadhar_number' => 'nullable|digits:12',
            'application_type' => 'nullable|in:new,correction,reprint',
            'status' => 'nullable|in:pending,processing,completed,rejected',
            'dob' => 'nullable|date|before:today',
            'amount' => 'nullable|numeric|min:0',
            'received_amount' => 'nullable|numeric|min:0',
            'payment_mode' => 'nullable|in:cash,online,upi,cheque',
        ]);

        $data = $request->only([
            'applicant_name', 'mobile_number', 'aadhar_number', 'application_type',
            'application_number', 'status', 'dob', 'amount', 'received_amount', 'payment_mode'
        ]);

        // Auto-calculate payment status
        $amt = (float) ($data['amount'] ?? $app->amount);
        $rcv = (float) ($data['received_amount'] ?? $app->received_amount);
        $data['payment_status'] = $this->calculatePaymentStatus($amt, $rcv);

        $app->update(array_filter($data, fn($v) => $v !== null));

        return redirect()->route('pan-card')->with('success', 'अर्ज अपडेट झाला! ✅');
    }

    public function destroy(Request $request, $id)
    {
        PanCardApplication::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail()->delete();
        return redirect()->route('pan-card')->with('success', 'अर्ज हटवला!');
    }
}
