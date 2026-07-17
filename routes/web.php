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
use App\Http\Controllers\Admin\AdminFarmerCardController;
use App\Http\Controllers\Admin\AdminContactRequestController;
use App\Http\Controllers\Admin\AdminErrorLogController;
use App\Http\Controllers\VleDirectoryController;
use App\Http\Controllers\PanCardController;
use App\Http\Controllers\VoterIdController;
use App\Http\Controllers\BandkamController;
use App\Http\Controllers\MahasarthiController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PassportPhotoMakerController;
use App\Http\Controllers\AadhaarController;
use App\Http\Controllers\VillageInfoController;
use App\Http\Controllers\FarmerCardPublicController;
use App\Http\Controllers\BondFormatController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\DocslipController;
use App\Http\Controllers\AuthBridgeController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PublicServicePageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Models\BlogRedirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ─── Health Check (for load balancers) ───
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Health check failed: ' . $e->getMessage());
        return response()->json(['status' => 'error', 'message' => 'database_unavailable'], 503);
    }
})->name('health');

// ─── Public Pages ───
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.xml');
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/services/{slug}', [PublicServicePageController::class, 'show'])
    ->whereIn('slug', array_keys(config('service_pages.pages', [])))
    ->name('services.landing.show');
Route::get('/how-it-works', [PageController::class, 'howItWorks'])->name('how-it-works');
Route::get('/benefits', [PageController::class, 'benefits'])->name('benefits');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/refund', [PageController::class, 'refund'])->name('refund');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');
Route::get('/bandkam-kamgar-info', [PageController::class, 'bandkamInfo'])->name('bandkam-info');
Route::get('/author', [AuthorController::class, 'index'])->name('author');
Route::post('/author/submit-request', [AuthorController::class, 'submitRequest'])->name('author.submit-request');
Route::get('/vle-directory', [VleDirectoryController::class, 'index'])->name('vle.directory');
Route::get('/vle/{id}', [VleDirectoryController::class, 'show'])->where('id', '[0-9]+')->name('vle.show');

// ─── Public: Printable Formats ───
Route::get('/print/nirgam-utara-application', function () {
    return view('print.nirgam-utara-application');
})->name('print.nirgam-utara-application');

// ─── 301 Redirects for old /reviews/* URLs (must be BEFORE controller routes) ───
Route::get('/reviews/{slug}', function ($slug) {
    try {
        $redirect = \App\Models\BlogRedirect::where('old_path', "/reviews/{$slug}")->first();
        if ($redirect) return redirect($redirect->new_path, $redirect->status_code);
    } catch (\Exception $e) {
        // Database not available, skip redirect
    }
    return redirect("/blog/{$slug}", 301);
});

// ─── Public: Reviews (SEO Blog) ───
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/reviews/{slug}', [ReviewController::class, 'show'])->name('reviews.show');

// ─── Public: Farmer ID Card Online (Self-Service) ───
Route::get('/services/farmer-id-card-online', [FarmerCardPublicController::class, 'index'])->name('farmer-card-public');
Route::middleware(['throttle:5,1'])->group(function () {
    Route::post('/services/farmer-id-card-online/store', [FarmerCardPublicController::class, 'store'])->name('farmer-card-public.store');
    Route::post('/services/farmer-id-card-online/verify-payment', [FarmerCardPublicController::class, 'verifyPayment'])
        ->name('farmer-card-public.verify');
    Route::post('/services/farmer-id-card-online/lookup', [FarmerCardPublicController::class, 'lookup'])->name('farmer-card-public.lookup');
});
Route::middleware(['throttle:10,1'])->group(function () {
    Route::get('/services/farmer-id-card-online/download/{txn}', [FarmerCardPublicController::class, 'download'])->name('farmer-card-public.download');
});

// 301 Redirect: old URL → new URL
Route::get('/farmer-id-card-online', fn() => redirect('/services/farmer-id-card-online', 301));

// ─── VLE Dashboard (Auth Required) ───
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/save-config', [DashboardController::class, 'saveConfig'])->name('dashboard.save-config');

    // Auth Bridge - Redirect to billing subdomain
    Route::middleware(['subscription'])->group(function () {
        Route::get('/go/billing', [AuthBridgeController::class, 'redirectToBilling'])->name('billing.redirect');
    });

    // Profile
    Route::get('/profile', [VleProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [VleProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/logo', [VleProfileController::class, 'uploadLogo'])->name('profile.logo');
    Route::post('/profile/working-hours', [VleProfileController::class, 'updateWorkingHours'])->name('profile.working-hours');
    Route::post('/profile/kiosk-rates', [VleProfileController::class, 'updateKioskRates'])->name('profile.kiosk-rates');
    Route::get('/api/talukas', [VleProfileController::class, 'getTalukas'])->name('api.talukas');
    Route::post('/api/parse-farmer-pdf', [FormController::class, 'parseFarmerPdf'])->name('api.parse-farmer-pdf');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::middleware(['throttle:10,1'])->group(function () {
        Route::post('/wallet/recharge', [WalletController::class, 'createOrder'])->name('wallet.recharge');
        Route::post('/wallet/verify', [WalletController::class, 'verifyPayment'])->name('wallet.verify');
    });

    // Helpdesk
    Route::get('/helpdesk', [App\Http\Controllers\HelpdeskController::class, 'index'])->name('helpdesk.index');
    Route::get('/helpdesk/{ticket}', [App\Http\Controllers\HelpdeskController::class, 'show'])->name('helpdesk.show');
    Route::post('/helpdesk/submit', [App\Http\Controllers\HelpdeskController::class, 'submit'])->name('helpdesk.submit');
    Route::post('/helpdesk/{ticket}/reply', [App\Http\Controllers\HelpdeskController::class, 'reply'])->name('helpdesk.reply');

    // Subscription / Billing License
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription');
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::post('/subscription/activate-billing', [SubscriptionController::class, 'activateBilling'])->name('subscription.activate-billing');
        Route::post('/subscription/payment-order', [SubscriptionController::class, 'createPaymentOrder'])->name('subscription.payment-order');
        Route::post('/subscription/payment-verify', [SubscriptionController::class, 'verifyPayment'])->name('subscription.payment-verify');
    });

    // ─── Subscription-Protected Features ───
    // ─── Legacy PHP Billing System Removed (Now handled by React VLECODEBASE) ───

    // Forms — Generic Engine (C4: proper controller routing)
    Route::group([], function () {
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
    });

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
    Route::group([], function () {
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

        // CRM: Mahasarthi Card
        Route::get('/mahasarthi', [MahasarthiController::class, 'index'])->name('mahasarthi');
        Route::post('/mahasarthi', [MahasarthiController::class, 'store'])->name('mahasarthi.store');
        Route::put('/mahasarthi/{id}', [MahasarthiController::class, 'update'])->name('mahasarthi.update');
        Route::delete('/mahasarthi/{id}', [MahasarthiController::class, 'destroy'])->name('mahasarthi.destroy');

        // Export CSV
        Route::get('/export/pan-card', [ExportController::class, 'panCard'])->name('export.pan-card');
        Route::get('/export/voter-id', [ExportController::class, 'voterId'])->name('export.voter-id');
        Route::get('/export/bandkam', [ExportController::class, 'bandkam'])->name('export.bandkam');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    });

    // DocSlip — कागदपत्र पावती
    Route::prefix('docslip')->name('docslip.')->group(function () {
        Route::get('/', [DocslipController::class, 'index'])->name('index');
        Route::post('/merge', [DocslipController::class, 'mergeDocuments'])->name('merge');
        Route::post('/print', [DocslipController::class, 'printSlip'])->name('print');
        Route::get('/settings', [DocslipController::class, 'settings'])->name('settings');
        Route::post('/load-defaults', [DocslipController::class, 'loadDefaults'])->name('load-defaults');
        Route::post('/reset', [DocslipController::class, 'reset'])->name('reset');
        Route::post('/services', [DocslipController::class, 'storeService'])->name('services.store');
        Route::put('/services/{id}', [DocslipController::class, 'updateService'])->name('services.update');
        Route::delete('/services/{id}', [DocslipController::class, 'destroyService'])->name('services.destroy');
        Route::post('/documents', [DocslipController::class, 'storeDocument'])->name('documents.store');
        Route::put('/documents/{id}', [DocslipController::class, 'updateDocument'])->name('documents.update');
        Route::delete('/documents/{id}', [DocslipController::class, 'destroyDocument'])->name('documents.destroy');
        Route::post('/services/{id}/documents', [DocslipController::class, 'syncDocuments'])->name('services.documents');
        Route::get('/history', [DocslipController::class, 'history'])->name('history');
        Route::post('/saved-remarks', [DocslipController::class, 'storeSavedRemark'])->name('saved-remarks.store');
        Route::delete('/saved-remarks/{id}', [DocslipController::class, 'destroySavedRemark'])->name('saved-remarks.destroy');
    });

    // Bond Formats
    Route::get('/bond-formats', [BondFormatController::class, 'index'])->name('bonds.index');
    Route::get('/bond-formats/{slug}', [BondFormatController::class, 'show'])->name('bonds.show');
    Route::post('/bond-formats/deduct-fee', [BondFormatController::class, 'deductFee'])->name('bonds.deductFee');
});

// ─── Admin Panel ───
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/vles', [AdminVleController::class, 'index'])->name('vles');
    Route::get('/vles/{id}', [AdminVleController::class, 'show'])->name('vles.show');
    Route::post('/vles/{id}/toggle', [AdminVleController::class, 'toggleActive'])->name('vles.toggle');
    Route::post('/vles/{id}/balance', [AdminVleController::class, 'adjustBalance'])->name('vles.balance');
    Route::post('/vles/{id}/approval', [AdminVleController::class, 'toggleApproval'])->name('vles.approval');
    Route::get('/pricing', [AdminPricingController::class, 'index'])->name('pricing');
    Route::post('/pricing', [AdminPricingController::class, 'store'])->name('pricing.store');
    Route::post('/pricing/{id}', [AdminPricingController::class, 'update'])->name('pricing.update');
    Route::post('/pricing/{id}/toggle', [AdminPricingController::class, 'toggleActive'])->name('pricing.toggle');
    Route::delete('/pricing/{id}', [AdminPricingController::class, 'destroy'])->name('pricing.destroy');
    Route::get('/plans', [AdminPlanController::class, 'index'])->name('plans');
    Route::post('/plans', [AdminPlanController::class, 'store'])->name('plans.store');
    Route::put('/plans/{id}', [AdminPlanController::class, 'update'])->name('plans.update');
    Route::post('/plans/{id}/toggle', [AdminPlanController::class, 'toggle'])->name('plans.toggle');
    Route::delete('/plans/{id}', [AdminPlanController::class, 'destroy'])->name('plans.destroy');
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions');
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
    Route::post('/settings/razorpay', [AdminSettingsController::class, 'updateRazorpay'])->name('settings.razorpay');
    Route::get('/farmer-card-orders', [AdminFarmerCardController::class, 'index'])->name('farmer-card-orders');
    Route::get('/contact-requests', [AdminContactRequestController::class, 'index'])->name('contact-requests');
    Route::post('/contact-requests/{id}/status', [AdminContactRequestController::class, 'updateStatus'])->name('contact-requests.status');
    Route::delete('/contact-requests/{id}', [AdminContactRequestController::class, 'destroy'])->name('contact-requests.destroy');
    Route::get('/error-logs', [AdminErrorLogController::class, 'index'])->name('error-logs');
    Route::patch('/error-logs/{id}/resolve', [AdminErrorLogController::class, 'resolve'])->name('error-logs.resolve');
    Route::delete('/error-logs/{id}', [AdminErrorLogController::class, 'destroy'])->name('error-logs.destroy');
    Route::delete('/error-logs-clear', [AdminErrorLogController::class, 'clearResolved'])->name('error-logs.clear-resolved');

    // Ads Management
    Route::get('/ads', [\App\Http\Controllers\Admin\AdSettingController::class, 'index'])->name('ads.index');
    Route::post('/ads', [\App\Http\Controllers\Admin\AdSettingController::class, 'update'])->name('ads.update');

    // Admin Helpdesk
    Route::get('/helpdesk', [\App\Http\Controllers\Admin\HelpdeskController::class, 'index'])->name('helpdesk.index');
    Route::get('/helpdesk/{ticket}', [\App\Http\Controllers\Admin\HelpdeskController::class, 'show'])->name('helpdesk.show');
    Route::post('/helpdesk/{ticket}/reply', [\App\Http\Controllers\Admin\HelpdeskController::class, 'reply'])->name('helpdesk.reply');
    
    // Blog Management
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [AdminBlogController::class, 'index'])->name('index');
        Route::get('/create', [AdminBlogController::class, 'create'])->name('create');
        Route::post('/', [AdminBlogController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminBlogController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminBlogController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminBlogController::class, 'destroy'])->name('destroy');
        Route::post('/upload-json', [AdminBlogController::class, 'uploadJson'])->name('upload-json');
        Route::post('/validate-json', [AdminBlogController::class, 'validateJson'])->name('validate-json');
        Route::post('/upload-image', [AdminBlogController::class, 'uploadImage'])->name('upload-image');
        Route::get('/images', [AdminBlogController::class, 'imageLibrary'])->name('images');
        Route::delete('/images/{id}', [AdminBlogController::class, 'deleteImage'])->name('images.delete');
    });
});

// ─── Blog — Public Routes ───
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{slug}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/search', [BlogController::class, 'search'])->name('search');
    Route::get('/feed', [BlogController::class, 'rssFeed'])->name('feed');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// ─── Razorpay Webhook (no auth, no CSRF) ───
// CSRF exemption: Razorpay sends webhook requests from their servers which cannot include Laravel's CSRF token.
// The webhook handler validates the request using Razorpay's signature verification instead.
Route::post('/webhook/razorpay', [WalletController::class, 'webhook'])
    ->name('webhook.razorpay')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

require __DIR__.'/auth.php';
