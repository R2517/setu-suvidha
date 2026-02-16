<?php

namespace App\Http\Controllers;

use App\Models\PanCardApplication;
use App\Models\VoterIdApplication;
use App\Models\BandkamRegistration;
use App\Models\BandkamScheme;
use App\Models\FormSubmission;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $month = $request->month ?? now()->format('Y-m');
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();

        // Overall counts
        $panTotal = PanCardApplication::where('user_id', $userId)->count();
        $panMonth = PanCardApplication::where('user_id', $userId)->whereBetween('created_at', [$startDate, $endDate])->count();
        $voterTotal = VoterIdApplication::where('user_id', $userId)->count();
        $voterMonth = VoterIdApplication::where('user_id', $userId)->whereBetween('created_at', [$startDate, $endDate])->count();
        $bandkamTotal = BandkamRegistration::where('user_id', $userId)->count();
        $bandkamMonth = BandkamRegistration::where('user_id', $userId)->whereBetween('created_at', [$startDate, $endDate])->count();
        $formsTotal = FormSubmission::where('user_id', $userId)->count();
        $formsMonth = FormSubmission::where('user_id', $userId)->whereBetween('created_at', [$startDate, $endDate])->count();

        // Revenue
        $panRevenue = PanCardApplication::where('user_id', $userId)->whereBetween('created_at', [$startDate, $endDate])->sum('received_amount');
        $voterRevenue = VoterIdApplication::where('user_id', $userId)->whereBetween('created_at', [$startDate, $endDate])->sum('received_amount');
        $bandkamRevenue = BandkamRegistration::where('user_id', $userId)->whereBetween('created_at', [$startDate, $endDate])->sum('received_amount');
        $totalRevenue = $panRevenue + $voterRevenue + $bandkamRevenue;

        // Pending amounts
        $panPending = PanCardApplication::where('user_id', $userId)->whereIn('payment_status', ['unpaid', 'partial'])->selectRaw('SUM(amount - received_amount) as pending')->value('pending') ?? 0;
        $voterPending = VoterIdApplication::where('user_id', $userId)->whereIn('payment_status', ['unpaid', 'partial'])->selectRaw('SUM(amount - received_amount) as pending')->value('pending') ?? 0;
        $bandkamPending = BandkamRegistration::where('user_id', $userId)->whereIn('payment_status', ['unpaid', 'partial'])->selectRaw('SUM(amount - received_amount) as pending')->value('pending') ?? 0;
        $totalPending = $panPending + $voterPending + $bandkamPending;

        // PAN status breakdown
        $panByStatus = PanCardApplication::where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')->pluck('cnt', 'status')->toArray();

        // Voter status breakdown
        $voterByStatus = VoterIdApplication::where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')->pluck('cnt', 'status')->toArray();

        // Bandkam status breakdown
        $bandkamByStatus = BandkamRegistration::where('user_id', $userId)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')->pluck('cnt', 'status')->toArray();

        // Bandkam schemes breakdown
        $schemesByType = BandkamScheme::whereHas('registration', fn($q) => $q->where('user_id', $userId))
            ->selectRaw('scheme_type, COUNT(*) as cnt')
            ->groupBy('scheme_type')->pluck('cnt', 'scheme_type')->toArray();

        // Monthly trend (last 6 months)
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $s = (clone $m)->startOfMonth();
            $e = (clone $m)->endOfMonth();
            $trend[] = [
                'label' => $m->format('M Y'),
                'pan' => PanCardApplication::where('user_id', $userId)->whereBetween('created_at', [$s, $e])->count(),
                'voter' => VoterIdApplication::where('user_id', $userId)->whereBetween('created_at', [$s, $e])->count(),
                'bandkam' => BandkamRegistration::where('user_id', $userId)->whereBetween('created_at', [$s, $e])->count(),
            ];
        }

        return view('dashboard.reports', compact(
            'month', 'panTotal', 'panMonth', 'voterTotal', 'voterMonth', 'bandkamTotal', 'bandkamMonth',
            'formsTotal', 'formsMonth', 'totalRevenue', 'panRevenue', 'voterRevenue', 'bandkamRevenue',
            'totalPending', 'panPending', 'voterPending', 'bandkamPending',
            'panByStatus', 'voterByStatus', 'bandkamByStatus', 'schemesByType', 'trend'
        ));
    }
}
