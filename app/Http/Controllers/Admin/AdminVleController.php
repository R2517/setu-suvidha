<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminVleController extends Controller
{
    public function index()
    {
        $vles = Profile::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.vles', compact('vles'));
    }

    public function toggleActive(Request $request, $id)
    {
        $profile = Profile::findOrFail($id);
        $oldStatus = $profile->is_active;
        $profile->update(['is_active' => !$profile->is_active]);
        $profile->user->update(['is_active' => !$profile->user->is_active]);

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
}
