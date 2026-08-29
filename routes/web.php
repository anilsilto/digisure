<?php

use App\Http\Controllers\Panel\AuthController as PanelAuthController;
use App\Http\Controllers\Panel\DashboardController as PanelDashboardController;
use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Panel\QuoteController as PanelQuoteController;
use App\Http\Controllers\Panel\QuoteRequestController as PanelQuoteRequestController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\QuoteRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Genel site (auth yok)
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/hakkimizda', [PageController::class, 'about'])->name('hakkimizda');
Route::get('/kvkk-aydinlatma', [PageController::class, 'kvkk'])->name('kvkk');

Route::get('/iletisim', [ContactController::class, 'show'])->name('iletisim.show');
Route::post('/iletisim', [ContactController::class, 'send'])
    ->middleware('throttle:5,1')
    ->name('iletisim.send');

Route::get('/{product}-sigortasi', [PageController::class, 'product'])
    ->where('product', 'trafik|kasko|saglik')
    ->name('urun.show');

/*
|--------------------------------------------------------------------------
| Teklif talebi
|--------------------------------------------------------------------------
*/
Route::get('/teklif', [QuoteRequestController::class, 'form'])->name('teklif.form');
Route::post('/teklif', [QuoteRequestController::class, 'store'])
    ->middleware('throttle:6,1')
    ->name('teklif.store');
Route::get('/teklif/alindi', [QuoteRequestController::class, 'received'])->name('teklif.received');

/*
|--------------------------------------------------------------------------
| Acente paneli
|--------------------------------------------------------------------------
*/
Route::prefix('panel')->name('panel.')->group(function () {
    Route::middleware('guest:panel')->group(function () {
        Route::get('giris', [PanelAuthController::class, 'show'])->name('login');
        Route::post('giris', [PanelAuthController::class, 'login'])
            ->middleware('throttle:10,1')
            ->name('login.attempt');
    });

    Route::middleware('auth:panel')->group(function () {
        Route::post('cikis', [PanelAuthController::class, 'logout'])->name('logout');
        Route::get('/', [PanelDashboardController::class, 'index'])->name('dashboard');

        Route::get('teklifler', [PanelQuoteRequestController::class, 'index'])->name('quotes.index');
        Route::get('teklifler/{quoteRequest}', [PanelQuoteRequestController::class, 'show'])->name('quotes.show');
        Route::post('teklifler/{quoteRequest}/ata', [PanelQuoteRequestController::class, 'assign'])->name('quotes.assign');
        Route::post('teklifler/{quoteRequest}/hazir', [PanelQuoteRequestController::class, 'markReady'])->name('quotes.ready');
        Route::put('kotasyon/{quote}', [PanelQuoteController::class, 'update'])->name('quotes.update');
    });
});

/*
|--------------------------------------------------------------------------
| Müşteri portalı
|--------------------------------------------------------------------------
*/
Route::prefix('hesabim')->name('customer.')->group(function () {
    Route::middleware('guest:customer')->group(function () {
        Route::get('giris', [CustomerAuthController::class, 'showStart'])->name('login');
        Route::post('giris', [CustomerAuthController::class, 'start'])
            ->middleware('throttle:10,1')->name('login.start');
        Route::get('dogrula', [CustomerAuthController::class, 'showVerify'])->name('verify');
        Route::post('dogrula', [CustomerAuthController::class, 'verify'])
            ->middleware('throttle:10,1')->name('verify.attempt');
    });

    Route::middleware('auth:customer')->group(function () {
        Route::post('cikis', [CustomerAuthController::class, 'logout'])->name('logout');
        Route::get('/', fn () => view('customer.placeholder'))->name('dashboard'); // Task 9 değiştirecek
    });
});
