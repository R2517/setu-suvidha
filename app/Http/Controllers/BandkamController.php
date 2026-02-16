<?php

namespace App\Http\Controllers;

use App\Models\BandkamRegistration;
use App\Models\BandkamScheme;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BandkamController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $now = Carbon::today();

        // Status counts
        $allCount = BandkamRegistration::where('user_id', $userId)->count();
        $pendingCount = BandkamRegistration::where('user_id', $userId)->where('status', 'pending')->count();
        $activatedCount = BandkamRegistration::where('user_id', $userId)->where('status', 'activated')->count();
        $expiringCount = BandkamRegistration::where('user_id', $userId)
            ->where('status', 'activated')
            ->whereNotNull('expiry_date')
            ->whereBetween('expiry_date', [$now, $now->copy()->addDays(7)])
            ->count();
        $expiredCount = BandkamRegistration::where('user_id', $userId)->where('status', 'expired')->count();

        // Build query with filters
        $query = BandkamRegistration::where('user_id', $userId)
            ->with('schemes')
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('applicant_name', 'like', "%{$s}%")
                  ->orWhere('mobile_number', 'like', "%{$s}%")
                  ->orWhere('village', 'like', "%{$s}%");
            });
        }

        // Status card filter
        if ($request->status_filter) {
            if ($request->status_filter === 'expiring') {
                $query->where('status', 'activated')
                      ->whereNotNull('expiry_date')
                      ->whereBetween('expiry_date', [Carbon::today(), Carbon::today()->addDays(7)]);
            } else {
                $query->where('status', $request->status_filter);
            }
        }

        // Sidebar filters
        if ($request->reg_type) {
            $query->whereIn('registration_type', (array) $request->reg_type);
        }
        if ($request->pay_status) {
            $query->whereIn('payment_status', (array) $request->pay_status);
        }
        if ($request->pay_mode) {
            $query->whereIn('payment_mode', (array) $request->pay_mode);
        }
        if ($request->district) {
            $query->where('district', $request->district);
        }
        if ($request->taluka) {
            $query->where('taluka', $request->taluka);
        }
        if ($request->village_filter) {
            $query->where('village', $request->village_filter);
        }

        // Scheme-based filters
        if ($request->scheme_type) {
            $schemeTypes = (array) $request->scheme_type;
            $query->whereHas('schemes', function ($q) use ($schemeTypes) {
                $q->whereIn('scheme_type', $schemeTypes);
            });
        }
        if ($request->scheme_status) {
            $schemeStatuses = (array) $request->scheme_status;
            $query->whereHas('schemes', function ($q) use ($schemeStatuses) {
                $q->whereIn('status', $schemeStatuses);
            });
        }

        $registrations = $query->paginate(25)->withQueryString();

        return view('crm.bandkam', compact(
            'registrations', 'allCount', 'pendingCount', 'activatedCount', 'expiringCount', 'expiredCount'
        ));
    }

    public function store(Request $request)
    {
        $rules = [
            'applicant_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:15',
        ];

        // Already Activated type requires application_number
        if ($request->registration_type === 'activated') {
            $rules['application_number'] = 'required|string|max:50';
        }

        $request->validate($rules);

        $payStatus = 'unpaid';
        $amt = (float) ($request->amount ?? 0);
        $rcv = (float) ($request->received_amount ?? 0);
        if ($rcv > 0 && $rcv >= $amt) $payStatus = 'paid';
        elseif ($rcv > 0) $payStatus = 'partial';

        // If already activated, set status to 'activated' directly
        $regType = $request->registration_type ?? 'new';
        $status = $regType === 'activated' ? 'activated' : 'pending';

        $reg = BandkamRegistration::create([
            'user_id' => $request->user()->id,
            'applicant_name' => $request->applicant_name,
            'mobile_number' => $request->mobile_number,
            'aadhar_number' => $request->aadhar_number,
            'dob' => $request->dob,
            'district' => $request->district,
            'taluka' => $request->taluka,
            'village' => $request->village,
            'registration_type' => $regType,
            'application_number' => $request->application_number,
            'form_date' => $request->form_date ?? now()->toDateString(),
            'amount' => $amt,
            'received_amount' => $rcv,
            'payment_status' => $payStatus,
            'payment_mode' => $request->payment_mode ?? 'cash',
            'status' => $status,
        ]);

        // Auto-create Safety Kit scheme
        if ($request->safety_kit && $request->safety_kit !== 'no') {
            BandkamScheme::create([
                'registration_id' => $reg->id,
                'user_id' => $request->user()->id,
                'scheme_type' => 'safety_kit',
                'applicant_name' => $reg->applicant_name,
                'year' => date('Y'),
                'status' => 'pending',
            ]);
        }

        // Auto-create Essential Kit scheme
        if ($request->essential_kit && $request->essential_kit !== 'no') {
            BandkamScheme::create([
                'registration_id' => $reg->id,
                'user_id' => $request->user()->id,
                'scheme_type' => 'essential_kit',
                'applicant_name' => $reg->applicant_name,
                'year' => date('Y'),
                'status' => 'pending',
            ]);
        }

        // Auto-create Scholarship schemes
        $scholarships = $request->scholarships ?? [];
        foreach ($scholarships as $cat) {
            BandkamScheme::create([
                'registration_id' => $reg->id,
                'user_id' => $request->user()->id,
                'scheme_type' => 'scholarship',
                'applicant_name' => $reg->applicant_name,
                'scholarship_category' => $cat,
                'year' => date('Y'),
                'status' => 'pending',
            ]);
        }

        return redirect()->route('bandkam')->with('success', 'Customer Entry Save झाली! ✅');
    }

    public function show(Request $request, $id)
    {
        $registration = BandkamRegistration::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with('schemes')
            ->firstOrFail();

        return view('crm.bandkam-profile', compact('registration'));
    }

    public function update(Request $request, $id)
    {
        $reg = BandkamRegistration::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $reg->update($request->only([
            'applicant_name', 'mobile_number', 'aadhar_number', 'dob',
            'district', 'taluka', 'village', 'registration_type', 'application_number',
            'amount', 'received_amount', 'payment_mode', 'payment_status', 'status'
        ]));
        return redirect()->back()->with('success', 'नोंदणी अपडेट झाली!');
    }

    public function updateDates(Request $request, $id)
    {
        $reg = BandkamRegistration::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $data = $request->only(['form_date', 'online_date', 'appointment_date', 'activation_date', 'application_number']);

        // Auto-calculate expiry = activation + 1 year
        if ($request->activation_date) {
            $data['expiry_date'] = Carbon::parse($request->activation_date)->addYear()->toDateString();
            $activDate = Carbon::parse($request->activation_date);
            $data['status'] = $activDate->addYear()->isPast() ? 'expired' : 'activated';
        }

        $reg->update($data);
        return redirect()->back()->with('success', 'Dates Updated! ✅');
    }

    public function updatePayment(Request $request, $id)
    {
        $reg = BandkamRegistration::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $amt = (float) ($request->amount ?? $reg->amount);
        $rcv = (float) ($request->received_amount ?? $reg->received_amount);
        $payStatus = 'unpaid';
        if ($rcv > 0 && $rcv >= $amt) $payStatus = 'paid';
        elseif ($rcv > 0) $payStatus = 'partial';

        $reg->update([
            'amount' => $amt,
            'received_amount' => $rcv,
            'payment_status' => $payStatus,
            'payment_mode' => $request->payment_mode ?? $reg->payment_mode,
        ]);
        return redirect()->back()->with('success', 'Payment Updated! ✅');
    }

    public function destroy(Request $request, $id)
    {
        $reg = BandkamRegistration::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $reg->schemes()->delete();
        $reg->delete();
        return redirect()->route('bandkam')->with('success', 'Customer हटवला!');
    }

    public function storeScheme(Request $request, $id)
    {
        $reg = BandkamRegistration::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();

        $request->validate([
            'scheme_type' => 'required|string|max:50',
        ]);

        $commPercent = (float) ($request->commission_percent ?? 0);
        $amount = (float) ($request->amount ?? 0);
        $commAmount = $amount * $commPercent / 100;

        BandkamScheme::create([
            'registration_id' => $reg->id,
            'user_id' => $request->user()->id,
            'scheme_type' => $request->scheme_type,
            'applicant_name' => $reg->applicant_name,
            'beneficiary_name' => $request->beneficiary_name,
            'student_name' => $request->student_name,
            'scholarship_category' => $request->scholarship_category,
            'year' => $request->year ?? date('Y'),
            'amount' => $amount,
            'received_amount' => $request->received_amount ?? 0,
            'commission_percent' => $commPercent,
            'commission_amount' => $commAmount,
            'payment_status' => $request->payment_status ?? 'unpaid',
            'payment_mode' => $request->payment_mode ?? 'cash',
            'status' => $request->status ?? 'pending',
            'apply_date' => $request->apply_date,
            'appointment_date' => $request->appointment_date,
            'delivery_date' => $request->delivery_date,
        ]);

        return redirect()->back()->with('success', 'Scheme/Kit Save झाली! ✅');
    }

    public function updateScheme(Request $request, $schemeId)
    {
        $scheme = BandkamScheme::where('id', $schemeId)->where('user_id', $request->user()->id)->firstOrFail();

        $data = $request->only([
            'scheme_type', 'beneficiary_name', 'student_name', 'scholarship_category',
            'year', 'amount', 'received_amount', 'commission_percent',
            'payment_status', 'payment_mode', 'status',
            'apply_date', 'appointment_date', 'delivery_date',
        ]);

        if (isset($data['commission_percent']) && isset($data['amount'])) {
            $data['commission_amount'] = (float) $data['amount'] * (float) $data['commission_percent'] / 100;
        }

        $scheme->update($data);
        return redirect()->back()->with('success', 'Scheme Updated! ✅');
    }

    public function destroyScheme(Request $request, $schemeId)
    {
        BandkamScheme::where('id', $schemeId)->where('user_id', $request->user()->id)->firstOrFail()->delete();
        return redirect()->back()->with('success', 'Scheme हटवली!');
    }
}
