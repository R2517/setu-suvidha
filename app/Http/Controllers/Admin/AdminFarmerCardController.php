<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FarmerCardOrder;
use Illuminate\Http\Request;

class AdminFarmerCardController extends Controller
{
    public function index(Request $request)
    {
        $query = FarmerCardOrder::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('transaction_no', 'like', "%{$s}%")
                  ->orWhere('applicant_name', 'like', "%{$s}%")
                  ->orWhere('name_english', 'like', "%{$s}%")
                  ->orWhere('mobile', 'like', "%{$s}%");
            });
        }

        $orders = $query->paginate(25)->appends($request->query());

        $stats = [
            'total'   => FarmerCardOrder::count(),
            'paid'    => FarmerCardOrder::where('status', 'paid')->count(),
            'pending' => FarmerCardOrder::where('status', 'pending')->count(),
            'purged'  => FarmerCardOrder::where('data_purged', true)->count(),
            'revenue' => FarmerCardOrder::where('status', 'paid')->sum('amount') / 100,
        ];

        return view('admin.farmer-card-orders', compact('orders', 'stats'));
    }
}
