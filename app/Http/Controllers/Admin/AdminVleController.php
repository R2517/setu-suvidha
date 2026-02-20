<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\FormSubmission;
use App\Models\VleSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminVleController extends Controller
{
    public function index(Request $request)
    {
        $query = Profile::with('user');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('shop_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }
        if ($district = $request->get('district')) {
            $query->where('district', $district);
        }
        if ($status = $request->get('status')) {
            $query->where('is_active', $status === 'active');
        }

        $vles = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        $districts = Profile::whereNotNull('district')->distinct()->pluck('district')->sort();

        return view('admin.vles', compact('vles', 'districts'));
    }

    public function show($id)
    {
        $profile = Profile::with('user')->findOrFail($id);
        $transactions = WalletTransaction::where('user_id', $profile->user_id)
            ->latest('created_at')->take(20)->get();
        $formCount = FormSubmission::where('user_id', $profile->user_id)->count();
        $subscription = VleSubscription::where('user_id', $profile->user_id)
            ->where('status', 'active')->with('plan')->first();
        $salesCount = DB::table('billing_sales')->where('user_id', $profile->user_id)->count();
        $salesTotal = DB::table('billing_sales')->where('user_id', $profile->user_id)->sum('total_amount');

        return view('admin.vle-profile', compact(
            'profile', 'transactions', 'formCount', 'subscription', 'salesCount', 'salesTotal'
        ));
    }

    public function adjustBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:add,reduce',
            'reason' => 'required|string|max:255',
        ]);

        $profile = Profile::findOrFail($id);
        $amount = (float) $request->amount;
        $type = $request->type;

        if ($type === 'reduce' && $profile->wallet_balance < $amount) {
            return redirect()->back()->with('error', 'अपुरी शिल्लक! सध्याची शिल्लक: ₹' . number_format($profile->wallet_balance, 2));
        }

        $newBalance = $type === 'add'
            ? $profile->wallet_balance + $amount
            : $profile->wallet_balance - $amount;

        $profile->update(['wallet_balance' => $newBalance]);

        WalletTransaction::create([
            'user_id' => $profile->user_id,
            'amount' => $amount,
            'type' => $type === 'add' ? 'credit' : 'debit',
            'balance_after' => $newBalance,
            'description' => 'Admin: ' . $request->reason,
            'reference_id' => 'ADM-' . $request->user()->id . '-' . time(),
        ]);

        Log::info('Admin: VLE balance adjusted', [
            'admin_id' => $request->user()->id,
            'vle_user_id' => $profile->user_id,
            'vle_name' => $profile->full_name,
            'type' => $type,
            'amount' => $amount,
            'new_balance' => $newBalance,
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', ($type === 'add' ? '₹' . $amount . ' जोडले!' : '₹' . $amount . ' कमी केले!'));
    }

    public function toggleActive(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);
        $oldStatus = $profile->is_active;
        $profile->update(['is_active' => !$profile->is_active]);

        Log::info('Admin: VLE status toggled', [
            'admin_id' => $request->user()->id,
            'admin_name' => $request->user()->name,
            'vle_user_id' => $profile->user_id,
            'vle_name' => $profile->full_name,
            'old_status' => $oldStatus ? 'active' : 'inactive',
            'new_status' => !$oldStatus ? 'active' : 'inactive',
        ]);

        return redirect()->back()->with('success', 'VLE स्थिती अपडेट झाली!');
    }

    public function toggleApproval($id)
    {
        $profile = Profile::findOrFail($id);
        $profile->update(['is_public_approved' => !$profile->is_public_approved]);
        return redirect()->back()->with('success', 'Public profile approval updated!');
    }
}
