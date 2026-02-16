<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

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
        $profile->update(['is_active' => !$profile->is_active]);
        $profile->user->update(['is_active' => !$profile->user->is_active]);

        return redirect()->back()->with('success', 'VLE स्थिती अपडेट झाली!');
    }
}
