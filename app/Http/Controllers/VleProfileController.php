<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\BillingCommissionSlab;
use Illuminate\Http\Request;

class VleProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;
        $districts = config('maharashtra.districts');
        $completion = $this->calculateCompletion($profile);
        $commissionSlabs = BillingCommissionSlab::where('user_id', $user->id)->orderBy('amount_from')->get();

        return view('dashboard.profile', compact('user', 'profile', 'districts', 'completion', 'commissionSlabs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:15',
            'shop_name' => 'nullable|string|max:255',
            'shop_type' => 'nullable|in:setu,csc,other',
            'address' => 'nullable|string',
            'district' => 'nullable|string|max:100',
            'taluka' => 'nullable|string|max:100',
            'gst_number' => 'nullable|string|max:20',
            'csc_id' => 'nullable|string|max:50',
        ]);

        $profile = $request->user()->profile;
        $profile->update($request->only([
            'full_name', 'mobile', 'shop_name', 'shop_type', 'address', 'district', 'taluka',
            'gst_number', 'csc_id',
        ]));

        $request->user()->update(['name' => $request->full_name]);

        return redirect()->back()->with('success', 'प्रोफाइल यशस्वीरित्या अपडेट झालं!');
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profile = $request->user()->profile;

        // Delete old logo
        if ($profile->logo_url && file_exists(public_path($profile->logo_url))) {
            unlink(public_path($profile->logo_url));
        }

        $file = $request->file('logo');
        $filename = 'logo_' . $request->user()->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/logos'), $filename);

        $profile->update(['logo_url' => 'uploads/logos/' . $filename]);

        return response()->json(['success' => true, 'url' => asset('uploads/logos/' . $filename)]);
    }

    public function updateWorkingHours(Request $request)
    {
        $request->validate([
            'working_hours' => 'required|array',
            'holiday_mode' => 'nullable|boolean',
        ]);

        $profile = $request->user()->profile;
        $profile->update([
            'working_hours' => $request->working_hours,
            'holiday_mode' => $request->boolean('holiday_mode'),
        ]);

        return response()->json(['success' => true]);
    }

    public function updateKioskRates(Request $request)
    {
        $request->validate([
            'kiosk_enabled' => 'nullable|boolean',
            'slabs' => 'nullable|array',
            'balance_enquiry_rate' => 'nullable|numeric|min:0',
            'mini_statement_rate' => 'nullable|numeric|min:0',
        ]);

        $userId = $request->user()->id;
        $profile = $request->user()->profile;
        $profile->update(['kiosk_enabled' => $request->boolean('kiosk_enabled')]);

        // Save kiosk fixed rates
        $profile->update([
            'kiosk_rates' => [
                'balance_enquiry' => (float) ($request->balance_enquiry_rate ?? 0),
                'mini_statement' => (float) ($request->mini_statement_rate ?? 0),
            ],
        ]);

        // Update commission slabs
        BillingCommissionSlab::where('user_id', $userId)->delete();
        if ($request->slabs) {
            foreach ($request->slabs as $slab) {
                if (!empty($slab['amount_from']) || !empty($slab['amount_to'])) {
                    BillingCommissionSlab::create([
                        'user_id' => $userId,
                        'amount_from' => $slab['amount_from'] ?? 0,
                        'amount_to' => $slab['amount_to'] ?? 0,
                        'commission_percent' => $slab['commission_percent'] ?? 0,
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
    }

    public function getTalukas(Request $request)
    {
        $district = $request->query('district');
        $districts = config('maharashtra.districts');
        $talukas = $districts[$district] ?? [];

        return response()->json($talukas);
    }

    private function calculateCompletion(?Profile $profile): int
    {
        if (!$profile) return 0;
        $fields = ['full_name', 'mobile', 'shop_name', 'shop_type', 'address', 'district', 'taluka', 'gst_number', 'csc_id', 'logo_url'];
        $filled = 0;
        foreach ($fields as $f) {
            if (!empty($profile->$f)) $filled++;
        }
        return (int) round(($filled / count($fields)) * 100);
    }
}
