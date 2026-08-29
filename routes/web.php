<?php

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
