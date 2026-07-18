<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CardCropSetting;

class AdminCardSettingController extends Controller
{
    /**
     * Display the settings UI.
     */
    public function index()
    {
        $settings = CardCropSetting::all()->keyBy('card_type');
        return view('admin.card-settings.index', compact('settings'));
    }

    /**
     * Update crop settings.
     */
    public function store(Request $request)
    {
        $request->validate([
            'card_type' => 'required|string|in:aadhaar_front,aadhaar_back,pan_front,pan_back,abha_front,abha_back,eshram_front,eshram_back,mahasarathi_front,mahasarathi_back,ayushman_front,ayushman_back,voter_front,voter_back',
            'x_percent' => 'required|numeric|min:0|max:100',
            'y_percent' => 'required|numeric|min:0|max:100',
            'width_percent' => 'required|numeric|min:0|max:100',
            'height_percent' => 'required|numeric|min:0|max:100',
        ]);

        CardCropSetting::updateOrCreate(
            ['card_type' => $request->card_type],
            [
                'x_percent' => $request->x_percent,
                'y_percent' => $request->y_percent,
                'width_percent' => $request->width_percent,
                'height_percent' => $request->height_percent,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Settings saved successfully.']);
    }
}
