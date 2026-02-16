<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormPricing;
use Illuminate\Http\Request;

class AdminPricingController extends Controller
{
    public function index()
    {
        $pricing = FormPricing::orderBy('form_type')->get();
        return view('admin.pricing', compact('pricing'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $pricing = FormPricing::findOrFail($id);
        $pricing->update(['price' => $request->price]);

        return redirect()->back()->with('success', 'किंमत अपडेट झाली!');
    }

    public function toggleActive($id)
    {
        $pricing = FormPricing::findOrFail($id);
        $pricing->update(['is_active' => !$pricing->is_active]);

        return redirect()->back()->with('success', 'स्थिती अपडेट झाली!');
    }
}
