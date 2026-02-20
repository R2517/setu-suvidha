<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = WalletTransaction::with('user')->orderBy('created_at', 'desc');

        // Filter: only plan purchases + wallet top-ups (not service form deductions)
        if ($filter = $request->get('filter')) {
            if ($filter === 'wallet') {
                $query->where('type', 'credit')->where(function ($q) {
                    $q->where('description', 'like', '%Wallet%')
                      ->orWhere('description', 'like', '%Recharge%')
                      ->orWhere('description', 'like', '%Admin%')
                      ->orWhere('reference_id', 'like', 'pay_%')
                      ->orWhere('reference_id', 'like', 'ADM-%');
                });
            } elseif ($filter === 'plan') {
                $query->where('description', 'like', '%Plan%')
                      ->orWhere('description', 'like', '%Subscription%');
            }
        }

        if ($request->get('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($search = $request->get('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($request->get('from')) {
            $query->whereDate('created_at', '>=', $request->get('from'));
        }
        if ($request->get('to')) {
            $query->whereDate('created_at', '<=', $request->get('to'));
        }

        $transactions = $query->paginate(30)->withQueryString();
        return view('admin.transactions', compact('transactions'));
    }
}
