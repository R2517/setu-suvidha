<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormPricing;
use Illuminate\Http\Request;

class AdminPricingController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'vle');
        $vlePricing = FormPricing::where('audience', 'vle')->orderBy('form_type')->get();
        $publicPricing = FormPricing::where('audience', 'public')->orderBy('form_type')->get();
        return view('admin.pricing', compact('vlePricing', 'publicPricing', 'tab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'form_type' => 'required|string|max:50',
            'form_name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'audience' => 'required|in:vle,public',
        ]);

        FormPricing::create([
            'form_type' => $request->form_type,
            'form_name' => $request->form_name,
            'price' => $request->price,
            'audience' => $request->audience,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'नवीन किंमत जोडली!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $pricing = FormPricing::findOrFail($id);
        $data = ['price' => $request->price];
        if ($request->has('form_name')) {
            $data['form_name'] = $request->form_name;
        }
        $pricing->update($data);

        return redirect()->back()->with('success', 'किंमत अपडेट झाली!');
    }

    public function toggleActive($id)
    {
        $pricing = FormPricing::findOrFail($id);
        $pricing->update(['is_active' => !$pricing->is_active]);

        return redirect()->back()->with('success', 'स्थिती अपडेट झाली!');
    }

    public function destroy($id)
    {
        FormPricing::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'किंमत हटवली!');
    }
}
