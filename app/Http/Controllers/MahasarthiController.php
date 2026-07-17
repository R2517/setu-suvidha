<?php

namespace App\Http\Controllers;

use App\Models\MahasarthiApplication;
use Illuminate\Http\Request;

class MahasarthiController extends Controller
{
    use Traits\CrmControllerTrait;

    /**
     * Status labels in Marathi for the view.
     */
    private array $statusLabels = [
        'applied'    => 'Applied',
        'card_otp'   => 'Card OTP',
        'ready_card' => 'Ready Card',
        'delivered'  => 'Delivered',
    ];

    public function index(Request $request)
    {
        $userId = $request->user()->id;

        // Custom status counts for Mahasarthi (different statuses than Pan/Voter)
        $allCount       = MahasarthiApplication::where('user_id', $userId)->count();
        $appliedCount   = MahasarthiApplication::where('user_id', $userId)->where('status', 'applied')->count();
        $cardOtpCount   = MahasarthiApplication::where('user_id', $userId)->where('status', 'card_otp')->count();
        $readyCardCount = MahasarthiApplication::where('user_id', $userId)->where('status', 'ready_card')->count();
        $deliveredCount = MahasarthiApplication::where('user_id', $userId)->where('status', 'delivered')->count();

        $query = MahasarthiApplication::where('user_id', $userId)->orderBy('created_at', 'desc');

        // Apply status filter
        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('status', $request->status_filter);
        }

        // Apply search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('applicant_name', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%")
                  ->orWhere('application_number', 'like', "%{$search}%");
            });
        }

        // Apply payment status filter
        if ($request->filled('pay_status')) {
            $query->whereIn('payment_status', (array) $request->pay_status);
        }

        // Apply payment mode filter
        if ($request->filled('pay_mode')) {
            $query->whereIn('payment_mode', (array) $request->pay_mode);
        }

        $applications = $query->paginate(25)->withQueryString();

        return view('crm.mahasarthi', compact(
            'applications', 'allCount', 'appliedCount', 'cardOtpCount', 'readyCardCount', 'deliveredCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'aadhar_number'  => 'nullable|digits:12',
            'mobile_number'  => 'required|digits:10',
            'amount'         => 'nullable|numeric|min:0',
            'received_amount'=> 'nullable|numeric|min:0',
            'payment_mode'   => 'nullable|in:cash,online,upi,cheque',
            'notes'          => 'nullable|string|max:1000',
        ]);

        try {
            $amt = (float) ($request->amount ?? 0);
            $rcv = (float) ($request->received_amount ?? 0);

            MahasarthiApplication::create([
                'user_id'            => $request->user()->id,
                'applicant_name'     => $request->applicant_name,
                'aadhar_number'      => $request->aadhar_number,
                'mobile_number'      => $request->mobile_number,
                'application_number' => $request->application_number ?: 'MAHA-' . strtoupper(substr(uniqid(), -6)),
                'status'             => 'applied',
                'applied_date'       => now()->toDateString(),
                'amount'             => $amt,
                'received_amount'    => $rcv,
                'payment_mode'       => $request->payment_mode ?? 'cash',
                'payment_status'     => $this->calculatePaymentStatus($amt, $rcv),
                'notes'              => $request->notes,
            ]);

            return redirect()->route('mahasarthi')->with('success', 'महासारथी कार्ड अर्ज जतन झाला! ✅');
        } catch (\Exception $e) {
            \Log::error('Mahasarthi store failed: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('mahasarthi')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $app = MahasarthiApplication::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $request->validate([
            'applicant_name'  => 'nullable|string|max:255',
            'aadhar_number'   => 'nullable|digits:12',
            'mobile_number'   => 'nullable|digits:10',
            'application_number' => 'nullable|string|max:100',
            'status'          => 'nullable|in:applied,card_otp,ready_card,delivered',
            'amount'          => 'nullable|numeric|min:0',
            'received_amount' => 'nullable|numeric|min:0',
            'payment_mode'    => 'nullable|in:cash,online,upi,cheque',
            'notes'           => 'nullable|string|max:1000',
            'applied_date'    => 'nullable|date',
            'card_otp_date'   => 'nullable|date',
            'ready_card_date' => 'nullable|date',
            'delivered_date'  => 'nullable|date',
        ]);

        $data = $request->only([
            'applicant_name', 'aadhar_number', 'mobile_number', 'application_number',
            'status', 'amount', 'received_amount', 'payment_mode', 'notes',
            'applied_date', 'card_otp_date', 'ready_card_date', 'delivered_date',
        ]);

        // Auto-set date when status changes
        if (isset($data['status']) && $data['status'] !== $app->status) {
            $dateCol = MahasarthiApplication::statusDateColumn($data['status']);
            if ($dateCol && empty($data[$dateCol])) {
                $data[$dateCol] = now()->toDateString();
            }
        }

        // Auto-calculate payment status
        $amt = (float) ($data['amount'] ?? $app->amount);
        $rcv = (float) ($data['received_amount'] ?? $app->received_amount);
        $data['payment_status'] = $this->calculatePaymentStatus($amt, $rcv);

        $app->update(array_filter($data, fn($v) => $v !== null));

        return redirect()->route('mahasarthi')->with('success', 'महासारथी अर्ज अपडेट झाला! ✅');
    }

    public function destroy(Request $request, $id)
    {
        MahasarthiApplication::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail()
            ->delete();

        return redirect()->route('mahasarthi')->with('success', 'अर्ज हटवला!');
    }
}
