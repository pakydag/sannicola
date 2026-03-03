<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\GlobalWidgetController;
use App\Http\Controllers\ContactRequestController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// Rotte Pubbliche (Homepage)
Route::get('/', [PublicController::class, 'home'])->name('public.home');

// Rotte Autenticate / Amministrazione

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Area Clienti B2C (Pubblica)
    Route::get('/account', [\App\Http\Controllers\CustomerAreaController::class, 'dashboard'])->name('public.account.dashboard');
    Route::get('/account/profile', [\App\Http\Controllers\CustomerAreaController::class, 'profile'])->name('public.account.profile');
    Route::post('/account/profile', [\App\Http\Controllers\CustomerAreaController::class, 'updateProfile'])->name('public.account.updateProfile');
});

Route::middleware(['auth', 'admin'])->prefix('amministrazione')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('articoli', ArticleController::class);
    Route::resource('sezioni', SectionController::class);
    Route::resource('global-widgets', GlobalWidgetController::class);
    Route::resource('contatti', ContactRequestController::class)->only(['index', 'show', 'destroy']);
    
    // Ordinamento
    Route::post('sezioni/reorder', [SectionController::class, 'reorder'])->name('sezioni.reorder');
    Route::post('articoli/reorder', [ArticleController::class, 'reorder'])->name('articoli.reorder');

    // Gestione Home Page
    Route::get('/home-page', [AdminHomeController::class, 'edit'])->name('home.edit');
    Route::post('/home-page', [AdminHomeController::class, 'update'])->name('home.update');
    Route::post('/home-page/seo', [AdminHomeController::class, 'updateSeo'])->name('home.updateSeo');
    Route::get('/home-page/preview', [AdminHomeController::class, 'preview'])->name('home.preview');
    Route::get('/file-manager', function () {
        return view('admin.filemanager');
    })->name('filemanager');

    // Configurazione Sito
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Widget Routes
    Route::post('/articoli/{articolo}/widgets', [WidgetController::class, 'store'])->name('widgets.store');
    Route::delete('/widgets/{widget}', [WidgetController::class, 'destroy'])->name('widgets.destroy');
    Route::post('/widgets/reorder', [WidgetController::class, 'reorder'])->name('widgets.reorder');

    // Shop B2B (E-commerce)
    Route::prefix('shop')->name('shop.')->group(function () {
        Route::resource('categorie', \App\Http\Controllers\ShopCategoryController::class)->parameters(['categorie' => 'categoria']);
        Route::resource('collezioni', \App\Http\Controllers\ShopCollectionController::class)->parameters(['collezioni' => 'collezione']);
        Route::resource('prodotti', \App\Http\Controllers\ShopProductController::class)->parameters(['prodotti' => 'prodotto']);
        Route::resource('clienti', \App\Http\Controllers\CustomerController::class)->parameters(['clienti' => 'cliente']);
        Route::resource('ordini', \App\Http\Controllers\ShopOrderController::class)->parameters(['ordini' => 'ordine']);
        Route::resource('tags', \App\Http\Controllers\TagController::class);
    });

    // Booking System
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::resource('structures', \App\Http\Controllers\Admin\BookingStructureController::class);
        Route::get('/calendar', [\App\Http\Controllers\Admin\AdminBookingController::class, 'calendar'])->name('calendar');
        Route::get('/bookings', [\App\Http\Controllers\Admin\AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [\App\Http\Controllers\Admin\AdminBookingController::class, 'show'])->name('bookings.show');
    });
});

require __DIR__.'/auth.php';

// Form Contatti Pubblico
Route::post('/contatti/invia', [PublicController::class, 'submitContactForm'])->name('public.contact.submit');

// Shop Pubblico
Route::get('/shop', [\App\Http\Controllers\PublicShopController::class, 'index'])->name('public.shop.index');
Route::get('/shop/cart', [\App\Http\Controllers\PublicShopCartController::class, 'index'])->name('public.shop.cart.index');
Route::post('/shop/cart/add', [\App\Http\Controllers\PublicShopCartController::class, 'add'])->name('public.shop.cart.add');
Route::delete('/shop/cart/remove/{variant_id}', [\App\Http\Controllers\PublicShopCartController::class, 'remove'])->name('public.shop.cart.remove');

Route::get('/shop/checkout', [\App\Http\Controllers\PublicShopCartController::class, 'checkout'])->name('public.shop.cart.checkout');
Route::post('/shop/checkout/process', [\App\Http\Controllers\PublicShopCartController::class, 'processCheckout'])->name('public.shop.cart.process');
Route::get('/shop/checkout/success', [\App\Http\Controllers\PublicShopCartController::class, 'success'])->name('public.shop.cart.success');
Route::get('/shop/checkout/stripe/success', [\App\Http\Controllers\PublicShopCartController::class, 'stripeSuccess'])->name('public.shop.cart.stripe.success');
Route::get('/shop/checkout/stripe/cancel', [\App\Http\Controllers\PublicShopCartController::class, 'stripeCancel'])->name('public.shop.cart.stripe.cancel');
Route::get('/shop/checkout/paypal/success', [\App\Http\Controllers\PublicShopCartController::class, 'paypalSuccess'])->name('public.shop.cart.paypal.success');
Route::get('/shop/checkout/paypal/cancel', [\App\Http\Controllers\PublicShopCartController::class, 'paypalCancel'])->name('public.shop.cart.paypal.cancel');

Route::get('/shop/{slug}', [\App\Http\Controllers\PublicShopController::class, 'collezione'])->name('public.shop.collezione');
Route::get('/shop/{collezione_slug}/{prodotto_slug}', [\App\Http\Controllers\PublicShopController::class, 'prodotto'])->name('public.shop.prodotto');

// Booking Pubblico
Route::get('/booking', [\App\Http\Controllers\PublicBookingController::class, 'index'])->name('public.booking.index');
Route::get('/booking/checkout', [\App\Http\Controllers\PublicBookingController::class, 'checkout'])->name('public.booking.checkout');
Route::post('/booking/process-checkout', [\App\Http\Controllers\PublicBookingController::class, 'processCheckout'])->name('public.booking.process_checkout');
Route::get('/booking/success/{id}', [\App\Http\Controllers\PublicBookingController::class, 'success'])->name('public.booking.success');
Route::get('/booking/stripe/success', [\App\Http\Controllers\PublicBookingController::class, 'stripeSuccess'])->name('public.booking.stripe.success');
Route::get('/booking/paypal/success', [\App\Http\Controllers\PublicBookingController::class, 'paypalSuccess'])->name('public.booking.paypal.success');
Route::get('/booking/{id}', [\App\Http\Controllers\PublicBookingController::class, 'show'])->name('public.booking.show');
Route::post('/booking/check-availability', [\App\Http\Controllers\PublicBookingController::class, 'checkAvailability'])->name('public.booking.check');
Route::post('/booking/reserve', [\App\Http\Controllers\PublicBookingController::class, 'reserve'])->name('public.booking.reserve');

// Catch-all Routes (Devono stare in fondo per non sovrascrivere /login, /amministrazione, ecc.)
Route::get('/{slug}', [PublicController::class, 'sezione'])
    ->name('public.sezione');

Route::get('/{sezione_slug}/{articolo_slug}', [PublicController::class, 'articolo'])
    ->name('public.articolo');

