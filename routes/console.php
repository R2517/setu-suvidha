<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Bandkam expiry alert — runs daily at 8:00 AM
Schedule::command('bandkam:expiry-alert --days=7')->dailyAt('08:00');

// Purge farmer card public orders — runs daily at 2:00 AM
Schedule::command('farmer-cards:purge')->dailyAt('02:00');

// Auto-resolve old error logs — runs every hour
Schedule::command('errors:auto-resolve')->hourly();

// Auto-publish scheduled blog posts — runs every 5 minutes
Schedule::call(function () {
    \App\Models\BlogPost::where('status', 'scheduled')
        ->where('scheduled_at', '<=', now())
        ->update(['status' => 'published', 'published_at' => now()]);
})->everyFiveMinutes();
