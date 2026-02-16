<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class VleProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile;
        $districts = config('maharashtra.districts');

        return view('dashboard.profile', compact('user', 'profile', 'districts'));
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
        ]);

        $profile = $request->user()->profile;
        $profile->update($request->only([
            'full_name', 'mobile', 'shop_name', 'shop_type', 'address', 'district', 'taluka'
        ]));

        $request->user()->update(['name' => $request->full_name]);

        return redirect()->back()->with('success', 'प्रोफाइल यशस्वीरित्या अपडेट झालं!');
    }

    public function getTalukas(Request $request)
    {
        $district = $request->query('district');
        $districts = config('maharashtra.districts');
        $talukas = $districts[$district] ?? [];

        return response()->json($talukas);
    }
}
