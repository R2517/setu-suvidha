<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdSettingController extends Controller
{
    public function index()
    {
        $ads = AdSetting::all()->keyBy('slot_name');
        return view('admin.ads.index', compact('ads'));
    }

    public function update(Request $request)
    {
        $slots = ['left_sidebar', 'right_sidebar', 'top_banner', 'bottom_banner'];

        foreach ($slots as $slot) {
            $prefix = $slot . '_';
            
            $ad = AdSetting::firstOrNew(['slot_name' => $slot]);
            $ad->is_active = $request->has($prefix . 'is_active');
            
            // Only update type and content if active or if changing settings
            $ad->type = $request->input($prefix . 'type', 'image');
            
            if ($ad->type === 'script') {
                $ad->content = $request->input($prefix . 'script_content');
                $ad->target_url = null;
            } else {
                $ad->target_url = $request->input($prefix . 'target_url');
                
                if ($request->hasFile($prefix . 'image_file')) {
                    // Delete old if exists
                    if ($ad->content && Storage::disk('public')->exists(str_replace('/storage/', '', $ad->content))) {
                        Storage::disk('public')->delete(str_replace('/storage/', '', $ad->content));
                    }
                    
                    $file = $request->file($prefix . 'image_file');
                    $path = $file->store('ads', 'public');
                    $ad->content = '/storage/' . $path;
                }
            }
            
            $ad->save();
            \Illuminate\Support\Facades\Cache::forget('ad_slot_' . $slot);
        }

        return redirect()->back()->with('success', 'Ad settings updated successfully!');
    }
}
