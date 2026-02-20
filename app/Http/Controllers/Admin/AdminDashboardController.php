<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\FormSubmission;
use App\Models\WalletTransaction;
use App\Models\VleSubscription;
use App\Models\SubscriptionPlan;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Row 1 stats
        $totalVles = Profile::count();
        $activeVles = Profile::where('is_active', true)->count();
        $totalRevenue = WalletTransaction::where('type', 'credit')->sum('amount');
        $totalForms = FormSubmission::count();

        // Row 2 stats
        $activePlans = VleSubscription::where('status', 'active')->count();
        $pendingApprovals = Profile::where('is_public_approved', false)
            ->whereNotNull('about_center')
            ->count();
        $todayTransactions = WalletTransaction::whereDate('created_at', today())->count();
        $lowBalanceVles = Profile::where('wallet_balance', '<', 30)->where('is_active', true)->count();

        // Recent activity (last 10 transactions)
        $recentActivity = WalletTransaction::with('user')
            ->latest('created_at')
            ->take(10)
            ->get();

        // Plan breakdown
        $planBreakdown = VleSubscription::where('status', 'active')
            ->selectRaw('plan_id, count(*) as count')
            ->groupBy('plan_id')
            ->with('plan')
            ->get();

        return view('admin.dashboard', compact(
            'totalVles', 'activeVles', 'totalRevenue', 'totalForms',
            'activePlans', 'pendingApprovals', 'todayTransactions', 'lowBalanceVles',
            'recentActivity', 'planBreakdown'
        ));
    }
}
