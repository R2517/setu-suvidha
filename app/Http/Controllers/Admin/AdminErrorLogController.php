<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\Http\Request;

class AdminErrorLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ErrorLog::query()->latest('created_at');

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('status')) {
            $query->where('is_resolved', $request->status === 'resolved');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhere('file', 'like', "%{$search}%")
                  ->orWhere('url', 'like', "%{$search}%");
            });
        }

        $logs = $query->with('user:id,name,email')->paginate(25)->withQueryString();

        $stats = [
            'total' => ErrorLog::count(),
            'today' => ErrorLog::whereDate('created_at', today())->count(),
            'unresolved' => ErrorLog::where('is_resolved', false)->count(),
            'critical' => ErrorLog::whereIn('level', ['critical', 'emergency'])->where('is_resolved', false)->count(),
        ];

        return view('admin.error-logs', compact('logs', 'stats'));
    }

    public function resolve(Request $request, $id)
    {
        $log = ErrorLog::findOrFail($id);
        $log->update(['is_resolved' => !$log->is_resolved]);

        return redirect()->back()->with('success', $log->is_resolved ? 'Resolved' : 'Reopened');
    }

    public function destroy($id)
    {
        ErrorLog::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Error log deleted.');
    }

    public function clearResolved()
    {
        $count = ErrorLog::where('is_resolved', true)->delete();
        return redirect()->back()->with('success', "{$count} resolved logs cleared.");
    }
}
