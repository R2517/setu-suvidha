<?php

namespace App\Http\Controllers\Traits;

trait CrmControllerTrait
{
    /**
     * C2: Shared payment status calculation — replaces 4+ duplicated blocks.
     */
    protected function calculatePaymentStatus(float $amount, float $received): string
    {
        if ($received > 0 && $received >= $amount) {
            return 'paid';
        }
        if ($received > 0) {
            return 'partial';
        }
        return 'unpaid';
    }

    /**
     * Get status counts for a model scoped to a user.
     */
    protected function getStatusCounts($modelClass, int $userId): array
    {
        $base = $modelClass::where('user_id', $userId);
        return [
            'allCount' => (clone $base)->count(),
            'pendingCount' => (clone $base)->where('status', 'pending')->count(),
            'processingCount' => (clone $base)->where('status', 'processing')->count(),
            'completedCount' => (clone $base)->where('status', 'completed')->count(),
            'rejectedCount' => (clone $base)->where('status', 'rejected')->count(),
        ];
    }

    /**
     * Apply common CRM filters (search, app_type, pay_status, pay_mode).
     */
    protected function applyCrmFilters($query, $request, array $searchColumns = ['applicant_name', 'mobile_number', 'application_number', 'aadhar_number'])
    {
        if ($request->status_filter) {
            $query->where('status', $request->status_filter);
        }

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s, $searchColumns) {
                foreach ($searchColumns as $i => $col) {
                    $method = $i === 0 ? 'where' : 'orWhere';
                    $q->$method($col, 'like', "%$s%");
                }
            });
        }

        if ($request->app_type) {
            $query->whereIn('application_type', (array) $request->app_type);
        }
        if ($request->pay_status) {
            $query->whereIn('payment_status', (array) $request->pay_status);
        }
        if ($request->pay_mode) {
            $query->whereIn('payment_mode', (array) $request->pay_mode);
        }

        return $query;
    }
}
