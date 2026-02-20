<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::all();
        return view('admin.plans', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'features' => 'nullable|string',
        ]);

        SubscriptionPlan::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'features' => $request->features ? array_map('trim', explode(',', $request->features)) : [],
        ]);

        return redirect()->back()->with('success', 'प्लॅन तयार झाला!');
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration_days' => $request->duration_days,
            'features' => $request->features ? array_map('trim', explode(',', $request->features)) : [],
        ]);

        return redirect()->back()->with('success', 'प्लॅन अपडेट झाला!');
    }

    public function destroy($id)
    {
        SubscriptionPlan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'प्लॅन हटवला!');
    }
}
