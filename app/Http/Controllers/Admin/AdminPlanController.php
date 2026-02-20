<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class AdminPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderByRaw("FIELD(plan_type, 'monthly', 'quarterly', 'half_yearly', 'yearly')")->get();
        $activeSubs = \App\Models\VleSubscription::where('status', 'active')
            ->selectRaw('plan_id, count(*) as count')
            ->groupBy('plan_id')
            ->pluck('count', 'plan_id');
        return view('admin.plans', compact('plans', 'activeSubs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'plan_type' => 'required|in:monthly,quarterly,half_yearly,yearly',
            'price' => 'required|numeric|min:0',
            'maintenance_amount' => 'nullable|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'features' => 'nullable|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'trial_days' => 'nullable|integer|min:0',
            'razorpay_plan_id' => 'nullable|string|max:100',
        ]);

        SubscriptionPlan::create([
            'name' => $request->name,
            'plan_type' => $request->plan_type,
            'price' => $request->price,
            'maintenance_amount' => $request->maintenance_amount ?? 0,
            'duration_days' => $request->duration_days,
            'features' => $request->features ? array_map('trim', explode(',', $request->features)) : [],
            'discount_percent' => $request->discount_percent ?? 0,
            'trial_days' => $request->trial_days ?? 15,
            'razorpay_plan_id' => $request->razorpay_plan_id,
        ]);

        return redirect()->back()->with('success', 'प्लॅन तयार झाला!');
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $data = [
            'name' => $request->name,
            'plan_type' => $request->plan_type,
            'price' => $request->price,
            'maintenance_amount' => $request->maintenance_amount ?? 0,
            'duration_days' => $request->duration_days,
            'features' => $request->features ? array_map('trim', explode(',', $request->features)) : [],
            'discount_percent' => $request->discount_percent ?? 0,
            'trial_days' => $request->trial_days ?? 15,
            'razorpay_plan_id' => $request->razorpay_plan_id,
        ];
        if ($request->has('is_active')) {
            $data['is_active'] = (bool) $request->is_active;
        }
        $plan->update($data);

        return redirect()->back()->with('success', 'प्लॅन अपडेट झाला!');
    }

    public function toggle($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        $plan->update(['is_active' => !$plan->is_active]);
        return redirect()->back()->with('success', 'प्लॅन स्थिती बदलली!');
    }

    public function destroy($id)
    {
        SubscriptionPlan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'प्लॅन हटवला!');
    }
}
