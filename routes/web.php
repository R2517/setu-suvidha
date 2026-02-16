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

    // Forms — Generic Engine
    Route::get('/hamipatra', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'hamipatra'))->name('hamipatra');
    Route::post('/hamipatra', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'hamipatra'));
    Route::get('/self-declaration', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'self_declaration'))->name('self-declaration');
    Route::post('/self-declaration', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'self_declaration'));
    Route::get('/grievance', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'grievance'))->name('grievance');
    Route::post('/grievance', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'grievance'));
    Route::get('/new-application', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'new_application'))->name('new-application');
    Route::post('/new-application', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'new_application'));
    Route::get('/caste-validity', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'caste_validity'))->name('caste-validity');
    Route::post('/caste-validity', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'caste_validity'));
    Route::get('/income-cert', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'income_cert'))->name('income-cert');
    Route::post('/income-cert', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'income_cert'));
    Route::get('/rajpatra', fn() => view('forms.rajpatra-hub'))->name('rajpatra');
    Route::get('/rajpatra-marathi', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'rajpatra_marathi'))->name('rajpatra-marathi');
    Route::post('/rajpatra-marathi', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'rajpatra_marathi'));
    Route::get('/rajpatra-english', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'rajpatra_english'))->name('rajpatra-english');
    Route::post('/rajpatra-english', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'rajpatra_english'));
    Route::get('/rajpatra-affidavit-712', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'rajpatra_affidavit_712'))->name('rajpatra-712');
    Route::post('/rajpatra-affidavit-712', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'rajpatra_affidavit_712'));
    Route::get('/farmer-id-card', fn(\Illuminate\Http\Request $r) => app(FormController::class)->show($r, 'farmer_id_card'))->name('farmer-id-card');
    Route::post('/farmer-id-card', fn(\Illuminate\Http\Request $r) => app(FormController::class)->store($r, 'farmer_id_card'));
    Route::get('/farmer-id-card/bulk-print', [FormController::class, 'bulkPrintFarmer'])->name('farmer.bulk-print');

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

require __DIR__.'/auth.php';
