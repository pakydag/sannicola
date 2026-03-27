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
        Route::get('/', [\App\Http\Controllers\Admin\AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/archive', [\App\Http\Controllers\Admin\AdminBookingController::class, 'archive'])->name('bookings.archive');
        Route::get('/calendar', [\App\Http\Controllers\Admin\AdminBookingController::class, 'calendar'])->name('calendar');
        Route::get('/block', [\App\Http\Controllers\Admin\AdminBookingController::class, 'block'])->name('bookings.block');
        Route::post('/block', [\App\Http\Controllers\Admin\AdminBookingController::class, 'storeBlock'])->name('bookings.store_block');
        Route::resource('structures', \App\Http\Controllers\Admin\BookingStructureController::class);
        Route::resource('customers', \App\Http\Controllers\Admin\BookingCustomerController::class); // Added this line

        // Booking Services
        Route::get('services', [\App\Http\Controllers\Admin\BookingServiceController::class, 'index'])->name('services.index');
        Route::post('services/categories', [\App\Http\Controllers\Admin\BookingServiceController::class, 'storeCategory'])->name('services.store-category');
        Route::put('services/categories/{category}', [\App\Http\Controllers\Admin\BookingServiceController::class, 'updateCategory'])->name('services.update-category');
        Route::delete('services/categories/{category}', [\App\Http\Controllers\Admin\BookingServiceController::class, 'destroyCategory'])->name('services.destroy-category');
        Route::post('services/categories/{category}/services', [\App\Http\Controllers\Admin\BookingServiceController::class, 'storeService'])->name('services.store-service');
        Route::delete('services/{service}', [\App\Http\Controllers\Admin\BookingServiceController::class, 'destroyService'])->name('services.destroy-service');
        
        // Booking Extras
        Route::resource('extras', \App\Http\Controllers\Admin\BookingExtraController::class)->except(['create', 'edit', 'show']);
        Route::post('/{booking}/mark-as-paid', [\App\Http\Controllers\Admin\AdminBookingController::class, 'markAsPaid'])->name('bookings.mark_as_paid');
        Route::post('/{booking}/cancel', [\App\Http\Controllers\Admin\AdminBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::get('/api/occupied-dates/{id}', [\App\Http\Controllers\Admin\AdminBookingController::class, 'getOccupiedDates'])->name('bookings.occupied_dates');
        Route::get('/{booking}', [\App\Http\Controllers\Admin\AdminBookingController::class, 'show'])->name('bookings.show');
    });

    // Customer Management
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('show');
        Route::post('/{customer}/tags/add', [\App\Http\Controllers\Admin\CustomerController::class, 'addTag'])->name('add-tag');
        Route::delete('/{customer}/tags/remove', [\App\Http\Controllers\Admin\CustomerController::class, 'removeTag'])->name('remove-tag');
        Route::post('/{customer}/toggle-feature', [\App\Http\Controllers\Admin\CustomerController::class, 'toggleFeature'])->name('toggle-feature');
    });

    // Admin User Management (Super Admin only recommended but accessible to all admins for now as per request)
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // AI Agent Management (Vapi.ai)
    Route::get('vapi', [\App\Http\Controllers\Admin\VapiController::class, 'index'])->name('vapi.index');
    Route::patch('vapi', [\App\Http\Controllers\Admin\VapiController::class, 'update'])->name('vapi.update');
    
    // AI Tickets Management
    Route::get('vapi/tickets', [\App\Http\Controllers\Admin\AiTicketController::class, 'index'])->name('vapi.tickets.index');
    Route::get('vapi/tickets/{ticket}', [\App\Http\Controllers\Admin\AiTicketController::class, 'show'])->name('vapi.tickets.show');
    Route::patch('vapi/tickets/{ticket}/close', [\App\Http\Controllers\Admin\AiTicketController::class, 'close'])->name('vapi.tickets.close');
    Route::patch('vapi/tickets/{ticket}/comments', [\App\Http\Controllers\Admin\AiTicketController::class, 'updateComments'])->name('vapi.tickets.update-comments');
    Route::delete('vapi/tickets/{ticket}', [\App\Http\Controllers\Admin\AiTicketController::class, 'destroy'])->name('vapi.tickets.destroy');

    // Modulo B2B Agenti
    Route::prefix('b2b')->name('b2b.')->group(function () {
        Route::resource('brands', \App\Http\Controllers\Admin\B2b\B2bBrandController::class);
        Route::resource('payment-conditions', \App\Http\Controllers\Admin\B2b\B2bPaymentConditionController::class);
        Route::resource('customers', \App\Http\Controllers\Admin\B2b\B2bCustomerController::class);
        Route::resource('agents', \App\Http\Controllers\Admin\B2b\AgentController::class);
        Route::resource('products', \App\Http\Controllers\Admin\B2b\B2bProductController::class);
        Route::resource('orders', \App\Http\Controllers\Admin\B2b\B2bOrderController::class);
        Route::post('orders/{order}/send-copy', [\App\Http\Controllers\Admin\B2b\B2bOrderController::class, 'sendOrderCopy'])->name('orders.send_copy');
        Route::get('dashboard', [\App\Http\Controllers\Admin\B2b\B2bDashboardController::class, 'index'])->name('dashboard');
    });
});

// Portale Agente B2B
Route::middleware(['auth', 'agent'])->prefix('agenti')->name('agent.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Agent\AgentPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/catalogo', [\App\Http\Controllers\Agent\AgentPortalController::class, 'catalog'])->name('catalog');
    Route::get('/prodotto/{product}', [\App\Http\Controllers\Agent\AgentPortalController::class, 'product'])->name('product');
    Route::get('/carrello', [\App\Http\Controllers\Agent\AgentPortalController::class, 'cart'])->name('cart');
    Route::post('/carrello/add', [\App\Http\Controllers\Agent\AgentPortalController::class, 'addToCart'])->name('cart.add');
    Route::post('/carrello/update', [\App\Http\Controllers\Agent\AgentPortalController::class, 'updateCart'])->name('cart.update');
    Route::delete('/carrello/remove/{index}', [\App\Http\Controllers\Agent\AgentPortalController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [\App\Http\Controllers\Agent\AgentPortalController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [\App\Http\Controllers\Agent\AgentPortalController::class, 'processCheckout'])->name('process_checkout');
    Route::get('/ordini', [\App\Http\Controllers\Agent\AgentPortalController::class, 'orders'])->name('orders');
    Route::get('/ordini/{order}', [\App\Http\Controllers\Agent\AgentPortalController::class, 'orderDetail'])->name('order_detail');
    Route::get('/profilo', [\App\Http\Controllers\Agent\AgentPortalController::class, 'profile'])->name('profile');
});

require __DIR__.'/auth.php';

// Form Contatti Pubblico
Route::post('/contatti/invia', [PublicController::class, 'submitContactForm'])->name('public.contact.submit');

// Spoki Webhook
Route::post('/webhook/spoki', [\App\Http\Controllers\SpokiWebhookController::class, 'handle']);
Route::get('/webhook/spoki', function () {
    return response()->json(['status' => 'active', 'message' => 'Spoki Webhook is ready for POST requests.']);
});

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

Route::get('/shop/collezione/{slug}', [\App\Http\Controllers\PublicShopController::class, 'collezione'])->name('public.shop.collezione');
Route::get('/shop/categoria/{slug}', [\App\Http\Controllers\PublicShopController::class, 'categoria'])->name('public.shop.categoria');
Route::get('/shop/{collezione_slug}/{prodotto_slug}', [\App\Http\Controllers\PublicShopController::class, 'prodotto'])->name('public.shop.prodotto');

// Booking Pubblico
Route::get('/booking', [\App\Http\Controllers\PublicBookingController::class, 'index'])->name('public.booking.index');
Route::get('/booking/checkout', [\App\Http\Controllers\PublicBookingController::class, 'checkout'])->name('public.booking.checkout');
    // Booking Checkout & Payment
    Route::post('/booking/login', [\App\Http\Controllers\PublicBookingController::class, 'loginCheckout'])->name('public.booking.login');
    Route::post('/booking/process-checkout', [\App\Http\Controllers\PublicBookingController::class, 'processCheckout'])->name('public.booking.process_checkout');
    Route::get('/booking/success/{id}', [\App\Http\Controllers\PublicBookingController::class, 'success'])->name('public.booking.success');

    // Booking Customer Dashboard
    Route::middleware('auth:booking_customer')->prefix('booking/dashboard')->name('public.booking.dashboard.')->group(function() {
        Route::get('/', [\App\Http\Controllers\BookingCustomerDashboardController::class, 'index'])->name('index');
        Route::get('/profile', [\App\Http\Controllers\BookingCustomerDashboardController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\BookingCustomerDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::post('/logout', [\App\Http\Controllers\BookingCustomerDashboardController::class, 'logout'])->name('logout');
    });
Route::get('/booking/stripe/success', [\App\Http\Controllers\PublicBookingController::class, 'stripeSuccess'])->name('public.booking.stripe.success');
Route::get('/booking/paypal/success', [\App\Http\Controllers\PublicBookingController::class, 'paypalSuccess'])->name('public.booking.paypal.success');
Route::get('/booking/search', [\App\Http\Controllers\PublicBookingController::class, 'search'])->name('public.booking.search');
Route::get('/booking/{id}', [\App\Http\Controllers\PublicBookingController::class, 'show'])->name('public.booking.show');
Route::post('/booking/check-availability', [\App\Http\Controllers\PublicBookingController::class, 'checkAvailability'])->name('public.booking.check');
Route::post('/booking/reserve', [\App\Http\Controllers\PublicBookingController::class, 'reserve'])->name('public.booking.reserve');

// Catch-all Routes (Devono stare in fondo per non sovrascrivere /login, /amministrazione, ecc.)
Route::get('/{slug}', [PublicController::class, 'sezione'])
    ->name('public.sezione');

Route::get('/{sezione_slug}/{articolo_slug}', [PublicController::class, 'articolo'])
    ->name('public.articolo');

