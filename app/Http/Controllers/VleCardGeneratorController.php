<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardCropSetting;
use App\Models\CardGenerationRecord;
use Carbon\Carbon;

class VleCardGeneratorController extends Controller
{
    /**
     * Display the VLE Bulk Card Generator Dashboard.
     */
    public function index()
    {
        // Get crop settings from DB just like Admin
        $settings = CardCropSetting::getSettingsForJs();
        
        return view('vle.card-generator', compact('settings'));
    }

    public function getQueue(Request $request)
    {
        $cards = \App\Models\VleSavedCard::where('user_id', $request->user()->id)
            ->where('expires_at', '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Map to URLs
        $cards = $cards->map(function ($card) {
            return [
                'id' => $card->id,
                'card_type' => $card->card_type,
                'front_url' => asset('storage/' . $card->front_image_path),
                'back_url' => asset('storage/' . $card->back_image_path),
                'expires_at' => $card->expires_at->format('d M, Y'),
                'created_at' => $card->created_at->diffForHumans()
            ];
        });
            
        return response()->json(['cards' => $cards]);
    }

    public function saveToQueue(Request $request)
    {
        $request->validate([
            'card_type' => 'required|string',
            'front_image' => 'required|string', // base64
            'back_image' => 'required|string', // base64
        ]);

        $userId = $request->user()->id;
        $dir = 'saved_cards/' . $userId;
        
        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($dir)) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($dir);
        }

        $frontData = $this->decodeBase64Image($request->front_image);
        $backData = $this->decodeBase64Image($request->back_image);

        $frontFileName = $dir . '/' . uniqid('front_') . '.jpg';
        $backFileName = $dir . '/' . uniqid('back_') . '.jpg';

        \Illuminate\Support\Facades\Storage::disk('public')->put($frontFileName, $frontData);
        \Illuminate\Support\Facades\Storage::disk('public')->put($backFileName, $backData);

        $card = \App\Models\VleSavedCard::create([
            'user_id' => $userId,
            'card_type' => $request->card_type,
            'front_image_path' => $frontFileName,
            'back_image_path' => $backFileName,
            'expires_at' => Carbon::now()->addHours(48)
        ]);

        return response()->json([
            'success' => true,
            'card' => [
                'id' => $card->id,
                'card_type' => $card->card_type,
                'front_url' => asset('storage/' . $card->front_image_path),
                'back_url' => asset('storage/' . $card->back_image_path),
                'expires_at' => $card->expires_at->format('d M, Y H:i A'),
                'created_at' => $card->created_at->diffForHumans()
            ]
        ]);
    }
    
    public function deleteFromQueue(Request $request, $id)
    {
        $card = \App\Models\VleSavedCard::where('user_id', $request->user()->id)->findOrFail($id);
        
        \Illuminate\Support\Facades\Storage::disk('public')->delete([$card->front_image_path, $card->back_image_path]);
        $card->delete();
        
        return response()->json(['success' => true]);
    }
    
    private function decodeBase64Image($base64String)
    {
        $imageParts = explode(";base64,", $base64String);
        return base64_decode($imageParts[1]);
    }

    /**
     * Store record of generated cards (when actually printed).
     */
    public function storeRecord(Request $request)
    {
        $request->validate([
            'card_type' => 'required|string',
            'quantity' => 'required|integer|min:1|max:50',
            'printed_ids' => 'nullable|array'
        ]);

        CardGenerationRecord::create([
            'user_id' => $request->user()->id,
            'card_type' => $request->card_type,
            'quantity' => $request->quantity,
        ]);

        return response()->json(['success' => true]);
    }
}
