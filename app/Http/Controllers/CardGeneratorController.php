<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardCropSetting;

class CardGeneratorController extends Controller
{
    /**
     * Display the landing page for the Card Generator.
     */
    public function index()
    {
        return view('card-generator.index');
    }

    /**
     * Display the Aadhaar Card Crop page.
     */
    public function aadhaar()
    {
        $settings = CardCropSetting::getSettingsForJs();
        return view('card-generator.aadhaar', compact('settings'));
    }

    /**
     * Display the PAN Card Crop page.
     */
    public function panCard()
    {
        $settings = CardCropSetting::getSettingsForJs();
        return view('card-generator.pan-card', compact('settings'));
    }

    public function abha()
    {
        $settings = CardCropSetting::getSettingsForJs();
        return view('card-generator.abha', compact('settings'));
    }

    public function eshram()
    {
        $settings = CardCropSetting::getSettingsForJs();
        return view('card-generator.eshram', compact('settings'));
    }

    public function mahasarathi()
    {
        $settings = CardCropSetting::getSettingsForJs();
        return view('card-generator.mahasarathi', compact('settings'));
    }
}
