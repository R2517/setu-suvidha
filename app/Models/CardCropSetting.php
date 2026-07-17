<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardCropSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_type',
        'x_percent',
        'y_percent',
        'width_percent',
        'height_percent',
    ];

    /**
     * Get all settings as a keyed array for JS injection
     */
    public static function getSettingsForJs()
    {
        $settings = self::all();
        $formatted = [];
        
        foreach ($settings as $setting) {
            $formatted[$setting->card_type] = [
                'x' => (float) $setting->x_percent,
                'y' => (float) $setting->y_percent,
                'width' => (float) $setting->width_percent,
                'height' => (float) $setting->height_percent,
            ];
        }
        
        return $formatted;
    }
}
