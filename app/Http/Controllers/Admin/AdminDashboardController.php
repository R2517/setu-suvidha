<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\FormSubmission;
use App\Models\WalletTransaction;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalVles = Profile::count();
        $activeVles = Profile::where('is_active', true)->count();
        $totalRevenue = WalletTransaction::where('type', 'credit')->sum('amount');
        $totalForms = FormSubmission::count();

        return view('admin.dashboard', compact('totalVles', 'activeVles', 'totalRevenue', 'totalForms'));
    }
}
