<?php

namespace App\Http\Controllers;

use App\Models\VillageInfo;
use Illuminate\Http\Request;

class VillageInfoController extends Controller
{
    public function index(Request $request)
    {
        $villages = VillageInfo::where('user_id', $request->user()->id)
            ->orderBy('village')
            ->get();

        return view('aadhaar.village-info', compact('villages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pincode'     => 'required|digits:6',
            'state'       => 'required|string|max:100',
            'district'    => 'required|string|max:100',
            'taluka'      => 'required|string|max:100',
            'post_office' => 'required|string|max:100',
            'village'     => 'required|string|max:100',
            'verifier_name' => 'nullable|string|max:150',
            'certifier_name' => 'nullable|string|max:150',
            'certifier_designation' => 'nullable|string|max:150',
            'certifier_contact' => 'nullable|string|max:15',
            'certifier_office_address' => 'nullable|string|max:255',
        ]);

        VillageInfo::create([
            'user_id'       => $request->user()->id,
            'pincode'       => $request->pincode,
            'state'         => strtoupper($request->state),
            'district'      => strtoupper($request->district),
            'taluka'        => strtoupper($request->taluka),
            'post_office'   => strtoupper($request->post_office),
            'village'       => strtoupper($request->village),
            'verifier_name' => $request->verifier_name ? strtoupper($request->verifier_name) : null,
            'certifier_name' => $request->certifier_name ? strtoupper($request->certifier_name) : null,
            'certifier_designation' => $request->certifier_designation ? strtoupper($request->certifier_designation) : null,
            'certifier_contact' => $request->certifier_contact,
            'certifier_office_address' => $request->certifier_office_address ? strtoupper($request->certifier_office_address) : null,
        ]);

        return redirect()->route('aadhaar.village-info.index')
            ->with('success', 'Village record saved successfully!');
    }

    public function edit($id)
    {
        $village = VillageInfo::where('user_id', auth()->id())->findOrFail($id);
        $villages = VillageInfo::where('user_id', auth()->id())->orderBy('village')->get();

        return view('aadhaar.village-info', compact('villages', 'village'));
    }

    public function update(Request $request, $id)
    {
        $village = VillageInfo::where('user_id', $request->user()->id)->findOrFail($id);

        $request->validate([
            'pincode'     => 'required|digits:6',
            'state'       => 'required|string|max:100',
            'district'    => 'required|string|max:100',
            'taluka'      => 'required|string|max:100',
            'post_office' => 'required|string|max:100',
            'village'     => 'required|string|max:100',
            'verifier_name' => 'nullable|string|max:150',
            'certifier_name' => 'nullable|string|max:150',
            'certifier_designation' => 'nullable|string|max:150',
            'certifier_contact' => 'nullable|string|max:15',
            'certifier_office_address' => 'nullable|string|max:255',
        ]);

        $village->update([
            'pincode'       => $request->pincode,
            'state'         => strtoupper($request->state),
            'district'      => strtoupper($request->district),
            'taluka'        => strtoupper($request->taluka),
            'post_office'   => strtoupper($request->post_office),
            'village'       => strtoupper($request->village),
            'verifier_name' => $request->verifier_name ? strtoupper($request->verifier_name) : null,
            'certifier_name' => $request->certifier_name ? strtoupper($request->certifier_name) : null,
            'certifier_designation' => $request->certifier_designation ? strtoupper($request->certifier_designation) : null,
            'certifier_contact' => $request->certifier_contact,
            'certifier_office_address' => $request->certifier_office_address ? strtoupper($request->certifier_office_address) : null,
        ]);

        return redirect()->route('aadhaar.village-info.index')
            ->with('success', 'Village record updated successfully!');
    }

    public function destroy($id)
    {
        $village = VillageInfo::where('user_id', auth()->id())->findOrFail($id);
        $village->delete();

        return redirect()->route('aadhaar.village-info.index')
            ->with('success', 'Village record deleted.');
    }

    public function getAddresses(Request $request)
    {
        $addresses = VillageInfo::where('user_id', $request->user()->id)
            ->orderBy('village')
            ->get();

        return response()->json($addresses);
    }
}
