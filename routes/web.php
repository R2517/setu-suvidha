<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VleProfileController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminVleController;
use App\Http\Controllers\Admin\AdminPricingController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\PanCardController;
use App\Http\Controllers\VoterIdController;
use App\Http\Controllers\BandkamController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PassportPhotoMakerController;
use App\Http\Controllers\AadhaarController;
use App\Http\Controllers\VillageInfoController;
use App\Http\Controllers\FarmerCardPublicController;
use Illuminate\Support\Facades\Route;

// ─── Public Pages ───
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');
Route::get('/benefits', [PageController::class, 'benefits'])->name('benefits');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/refund', [PageController::class, 'refund'])->name('refund');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');
Route::get('/bandkam-kamgar-info', [PageController::class, 'bandkamInfo'])->name('bandkam-info');

// ─── Public: Farmer ID Card Online (Self-Service) ───
Route::get('/farmer-id-card-online', [FarmerCardPublicController::class, 'index'])->name('farmer-card-public');
Route::post('/farmer-id-card-online/store', [FarmerCardPublicController::class, 'store'])->name('farmer-card-public.store');
Route::post('/farmer-id-card-online/verify-payment', [FarmerCardPublicController::class, 'verifyPayment'])
    ->name('farmer-card-public.verify')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::post('/farmer-id-card-online/lookup', [FarmerCardPublicController::class, 'lookup'])->name('farmer-card-public.lookup');
Route::get('/farmer-id-card-online/download/{txn}', [FarmerCardPublicController::class, 'download'])->name('farmer-card-public.download');

// ─── VLE Dashboard (Auth Required) ───
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [VleProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [VleProfileController::class, 'update'])->name('profile.update');
    Route::get('/api/talukas', [VleProfileController::class, 'getTalukas'])->name('api.talukas');
    Route::post('/api/parse-farmer-pdf', [FormController::class, 'parseFarmerPdf'])->name('api.parse-farmer-pdf');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::post('/wallet/recharge', [WalletController::class, 'createOrder'])->name('wallet.recharge');
    Route::post('/wallet/verify', [WalletController::class, 'verifyPayment'])->name('wallet.verify');

    // Billing
    Route::get('/billing', [PageController::class, 'billing'])->name('billing');

    // Forms — Generic Engine (C4: proper controller routing)
    Route::get('/hamipatra', [FormController::class, 'showHamipatra'])->name('hamipatra');
    Route::get('/self-declaration', [FormController::class, 'showSelfDeclaration'])->name('self-declaration');
    Route::get('/grievance', [FormController::class, 'showGrievance'])->name('grievance');
    Route::get('/new-application', [FormController::class, 'showNewApplication'])->name('new-application');
    Route::get('/caste-validity', [FormController::class, 'showCasteValidity'])->name('caste-validity');
    Route::get('/income-cert', [FormController::class, 'showIncomeCert'])->name('income-cert');
    Route::get('/rajpatra', fn() => view('forms.rajpatra-hub'))->name('rajpatra');
    Route::get('/rajpatra-marathi', [FormController::class, 'showRajpatraMarathi'])->name('rajpatra-marathi');
    Route::get('/rajpatra-english', [FormController::class, 'showRajpatraEnglish'])->name('rajpatra-english');
    Route::get('/rajpatra-affidavit-712', [FormController::class, 'showRajpatra712'])->name('rajpatra-712');
    Route::get('/farmer-id-card', [FormController::class, 'showFarmerIdCard'])->name('farmer-id-card');

    // B4: Rate-limited form POST routes (30 per minute per user)
    Route::middleware(['throttle:30,1'])->group(function () {
        Route::post('/hamipatra', [FormController::class, 'storeHamipatra']);
        Route::post('/self-declaration', [FormController::class, 'storeSelfDeclaration']);
        Route::post('/grievance', [FormController::class, 'storeGrievance']);
        Route::post('/new-application', [FormController::class, 'storeNewApplication']);
        Route::post('/caste-validity', [FormController::class, 'storeCasteValidity']);
        Route::post('/income-cert', [FormController::class, 'storeIncomeCert']);
        Route::post('/rajpatra-marathi', [FormController::class, 'storeRajpatraMarathi']);
        Route::post('/rajpatra-english', [FormController::class, 'storeRajpatraEnglish']);
        Route::post('/rajpatra-affidavit-712', [FormController::class, 'storeRajpatra712']);
        Route::post('/farmer-id-card', [FormController::class, 'storeFarmerIdCard']);
    });
    Route::get('/farmer-id-card/bulk-print', [FormController::class, 'bulkPrintFarmer'])->name('farmer.bulk-print');

    // PassportPro — Passport Photo Maker
    Route::get('/passport-photo-maker', [PassportPhotoMakerController::class, 'index'])->name('passport-photo-maker');
    Route::post('/passport-photo-maker/pay', [PassportPhotoMakerController::class, 'processPayment'])->name('passport-photo-maker.pay');

    // Aadhaar Services Hub
    Route::get('/aadhaar-hub', [AadhaarController::class, 'hub'])->name('aadhaar.hub');
    Route::prefix('aadhaar')->name('aadhaar.')->group(function () {
        Route::get('/adult-form', [AadhaarController::class, 'adultForm'])->name('adult-form');
        Route::get('/minor-form', [AadhaarController::class, 'minorForm'])->name('minor-form');
        Route::get('/child-form', [AadhaarController::class, 'childForm'])->name('child-form');
        Route::get('/update-form', [AadhaarController::class, 'updateForm'])->name('update-form');
        Route::post('/pay', [AadhaarController::class, 'processPayment'])->name('pay');
        Route::resource('village-info', VillageInfoController::class)->except(['create', 'show']);
        Route::get('/addresses-json', [VillageInfoController::class, 'getAddresses'])->name('addresses-json');
    });

    // Form actions
    Route::delete('/forms/{id}', [FormController::class, 'delete'])->name('forms.delete');
    Route::get('/forms/{id}/print', [FormController::class, 'print'])->name('forms.print');

    // Management / CRM Hub
    Route::get('/management', fn() => view('dashboard.management'))->name('management');

    // CRM: PAN Card
    Route::get('/pan-card', [PanCardController::class, 'index'])->name('pan-card');
    Route::post('/pan-card', [PanCardController::class, 'store'])->name('pan-card.store');
    Route::put('/pan-card/{id}', [PanCardController::class, 'update'])->name('pan-card.update');
    Route::delete('/pan-card/{id}', [PanCardController::class, 'destroy'])->name('pan-card.destroy');

    // CRM: Voter ID
    Route::get('/voter-id', [VoterIdController::class, 'index'])->name('voter-id');
    Route::post('/voter-id', [VoterIdController::class, 'store'])->name('voter-id.store');
    Route::put('/voter-id/{id}', [VoterIdController::class, 'update'])->name('voter-id.update');
    Route::delete('/voter-id/{id}', [VoterIdController::class, 'destroy'])->name('voter-id.destroy');

    // CRM: Bandkam Kamgar
    Route::get('/bandkam', [BandkamController::class, 'index'])->name('bandkam');
    Route::post('/bandkam', [BandkamController::class, 'store'])->name('bandkam.store');
    Route::get('/bandkam/{id}', [BandkamController::class, 'show'])->name('bandkam.show');
    Route::put('/bandkam/{id}', [BandkamController::class, 'update'])->name('bandkam.update');
    Route::put('/bandkam/{id}/dates', [BandkamController::class, 'updateDates'])->name('bandkam.dates');
    Route::put('/bandkam/{id}/payment', [BandkamController::class, 'updatePayment'])->name('bandkam.payment');
    Route::delete('/bandkam/{id}', [BandkamController::class, 'destroy'])->name('bandkam.destroy');
    Route::post('/bandkam/{id}/schemes', [BandkamController::class, 'storeScheme'])->name('bandkam.schemes.store');
    Route::put('/bandkam/schemes/{schemeId}', [BandkamController::class, 'updateScheme'])->name('bandkam.schemes.update');
    Route::delete('/bandkam/schemes/{schemeId}', [BandkamController::class, 'destroyScheme'])->name('bandkam.schemes.destroy');

    // Export CSV
    Route::get('/export/pan-card', [ExportController::class, 'panCard'])->name('export.pan-card');
    Route::get('/export/voter-id', [ExportController::class, 'voterId'])->name('export.voter-id');
    Route::get('/export/bandkam', [ExportController::class, 'bandkam'])->name('export.bandkam');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
});

// ─── Admin Panel ───
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/vles', [AdminVleController::class, 'index'])->name('vles');
    Route::post('/vles/{id}/toggle', [AdminVleController::class, 'toggleActive'])->name('vles.toggle');
    Route::get('/pricing', [AdminPricingController::class, 'index'])->name('pricing');
    Route::post('/pricing/{id}', [AdminPricingController::class, 'update'])->name('pricing.update');
    Route::post('/pricing/{id}/toggle', [AdminPricingController::class, 'toggleActive'])->name('pricing.toggle');
    Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans');
    Route::post('/plans', [AdminPlanController::class, 'store'])->name('plans.store');
    Route::put('/plans/{id}', [AdminPlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{id}', [AdminPlanController::class, 'destroy'])->name('plans.destroy');
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
});

// ─── Razorpay Webhook (no auth, no CSRF) ───
Route::post('/webhook/razorpay', [WalletController::class, 'webhook'])
    ->name('webhook.razorpay')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';
