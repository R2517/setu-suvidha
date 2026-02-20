<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class VleDirectoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Profile::where('is_public_approved', true)
            ->where('is_active', true)
            ->whereNotNull('about_center');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('shop_name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('taluka', 'like', "%{$search}%");
            });
        }
        if ($district = $request->get('district')) {
            $query->where('district', $district);
        }

        $profiles = $query->orderBy('full_name')->paginate(12)->withQueryString();
        $districts = Profile::where('is_public_approved', true)
            ->where('is_active', true)
            ->whereNotNull('district')
            ->distinct()->pluck('district')->sort();

        return view('public.vle-directory', compact('profiles', 'districts'));
    }

    public function show($id)
    {
        $profile = Profile::where('is_public_approved', true)
            ->where('is_active', true)
            ->findOrFail($id);

        return view('public.vle-profile', compact('profile'));
    }
}
