<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Bandkam expiry alert — runs daily at 8:00 AM
Schedule::command('bandkam:expiry-alert --days=7')->dailyAt('08:00');
