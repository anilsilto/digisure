<?php

use App\Http\Controllers\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Customer\DataRequestController as CustomerDataRequestController;
use App\Http\Controllers\Customer\PolicyController as CustomerPolicyController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\QuoteController as CustomerQuoteController;
use App\Http\Controllers\Panel\AuthController as PanelAuthController;
use App\Http\Controllers\Panel\DashboardController as PanelDashboardController;
use App\Http\Controllers\Panel\BlogCategoryController as PanelBlogCategoryController;
use App\Http\Controllers\Panel\ContactMessageController as PanelContactMessageController;
use App\Http\Controllers\Panel\DataRequestController as PanelDataRequestController;
use App\Http\Controllers\Panel\PostController as PanelPostController;
use App\Http\Controllers\Panel\PolicyController as PanelPolicyController;
use App\Http\Controllers\Panel\ProductTypeController as PanelProductTypeController;
use App\Http\Controllers\Panel\QuoteController as PanelQuoteController;
use App\Http\Controllers\Panel\QuoteRequestController as PanelQuoteRequestController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\CalculatorController;
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
    ->where('product', '[a-z-]+')
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
| Hesaplama araçları
|--------------------------------------------------------------------------
*/
Route::get('/hesaplama', [CalculatorController::class, 'index'])->name('calc.index');
Route::match(['get', 'post'], '/hesaplama/mtv', [CalculatorController::class, 'mtv'])->name('calc.mtv');
Route::match(['get', 'post'], '/hesaplama/otv', [CalculatorController::class, 'otv'])->name('calc.otv');
Route::match(['get', 'post'], '/hesaplama/yakit', [CalculatorController::class, 'fuel'])->name('calc.fuel');
Route::match(['get', 'post'], '/hesaplama/kasko-deger', [CalculatorController::class, 'kasko'])->name('calc.kasko');

/*
|--------------------------------------------------------------------------
| Blog
|--------------------------------------------------------------------------
*/
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post}', [BlogController::class, 'show'])->name('blog.show');

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
        Route::post('teklifler/{quoteRequest}/policelestir', [PanelPolicyController::class, 'fromQuote'])->name('quotes.policelestir');
        Route::put('kotasyon/{quote}', [PanelQuoteController::class, 'update'])->name('quotes.update');

        Route::get('policeler', [PanelPolicyController::class, 'index'])->name('policies.index');
        Route::get('policeler/olustur', [PanelPolicyController::class, 'create'])->name('policies.create');
        Route::post('policeler', [PanelPolicyController::class, 'store'])->name('policies.store');
        Route::get('policeler/{policy}', [PanelPolicyController::class, 'show'])->name('policies.show');

        Route::get('iletisim-mesajlari', [PanelContactMessageController::class, 'index'])->name('contact-messages.index');
        Route::post('iletisim-mesajlari/{contactMessage:id}/okundu', [PanelContactMessageController::class, 'markRead'])->name('contact-messages.read');
        Route::delete('iletisim-mesajlari/{contactMessage:id}', [PanelContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
        Route::get('veri-talepleri', [PanelDataRequestController::class, 'index'])->name('data-requests.index');
        Route::post('veri-talepleri/{dataRequest}/isle', [PanelDataRequestController::class, 'markHandled'])->name('data-requests.handle');

        Route::get('blog', [PanelPostController::class, 'index'])->name('posts.index');
        Route::get('blog/olustur', [PanelPostController::class, 'create'])->name('posts.create');
        Route::post('blog', [PanelPostController::class, 'store'])->name('posts.store');
        Route::get('blog/{post:id}/duzenle', [PanelPostController::class, 'edit'])->name('posts.edit');
        Route::put('blog/{post:id}', [PanelPostController::class, 'update'])->name('posts.update');
        Route::delete('blog/{post:id}', [PanelPostController::class, 'destroy'])->name('posts.destroy');
        Route::get('blog-kategorileri', [PanelBlogCategoryController::class, 'index'])->name('blog-categories.index');
        Route::post('blog-kategorileri', [PanelBlogCategoryController::class, 'store'])->name('blog-categories.store');
        Route::put('blog-kategorileri/{blogCategory:id}', [PanelBlogCategoryController::class, 'update'])->name('blog-categories.update');
        Route::delete('blog-kategorileri/{blogCategory:id}', [PanelBlogCategoryController::class, 'destroy'])->name('blog-categories.destroy');

        Route::middleware('can:panel.admin')->group(function () {
            Route::get('urunler', [PanelProductTypeController::class, 'index'])->name('products.index');
            Route::get('urunler/{productType:id}/duzenle', [PanelProductTypeController::class, 'edit'])->name('products.edit');
            Route::put('urunler/{productType:id}', [PanelProductTypeController::class, 'update'])->name('products.update');
        });
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
        Route::get('/', fn () => redirect()->route('customer.quotes.index'))->name('dashboard');

        Route::get('teklifler', [CustomerQuoteController::class, 'index'])->name('quotes.index');
        Route::get('teklifler/{quoteRequest}', [CustomerQuoteController::class, 'show'])->name('quotes.show');
        Route::post('teklifler/{quoteRequest}/sec/{quote}', [CustomerQuoteController::class, 'accept'])->name('quotes.accept');

        Route::get('policeler', [CustomerPolicyController::class, 'index'])->name('policies.index');
        Route::get('policeler/{policy}/dosya', [CustomerPolicyController::class, 'download'])->name('policies.download');

        Route::get('profil', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profil', [CustomerProfileController::class, 'update'])->name('profile.update');

        Route::post('veri-talebi', [CustomerDataRequestController::class, 'store'])->name('data-request.store');
    });
});
