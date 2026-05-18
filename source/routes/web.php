<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\VendorProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/* -------------------- PUBLIC -------------------- */

Route::get('/', function () {

    if (!auth()->check()) {
        return app(HomeController::class)->index();
    }

    return match (auth()->user()->role) {
        'vendor' => redirect()->route('vendor.home'),
        'customer' => redirect()->route('customer.home'),
        default => app(HomeController::class)->index(),
    };
});

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/home', fn () => redirect('/'));

Route::get('/register', fn () => view('auth/register'));
Route::post('/register', [UserController::class, 'register']);

Route::get('/login', fn () => view('auth/login'))->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout']);

/* -------------------- AUTH COMMON -------------------- */

Route::get('/test-login/{id}', function ($id) {

    Auth::loginUsingId($id);

    return redirect('/');

});

Route::middleware('auth')->group(function () {

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/profile', fn () => view('pages/profile'));
    Route::get('/profile/edit', fn () => view('pages/profile-edit'));
    Route::get('/address-payment/add', fn () => view('pages/address-payment-add'));
});

/* -------------------- CUSTOMER ONLY -------------------- */

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{item_id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item_id}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/orders-overview', [OrderController::class, 'overview'])->name('orders.overview');
    Route::get('/analytics', [OrderController::class, 'analytics'])->name('orders.analytics');
    Route::get('/track/{tracking}', fn() => view('pages.track'))->name('orders.track');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/customer/home', [HomeController::class, 'index'])
        ->name('customer.home');
});

/* -------------------- VENDOR ONLY -------------------- */

Route::middleware(['auth', 'role:vendor'])->group(function () {

    Route::get('/vendor-home', [\App\Http\Controllers\VendorHomeController::class, 'index'])
        ->name('vendor.home');

    Route::get('/vendor-products', fn () => view('pages/vendor-products'));

    Route::get('/vendor-profile', fn () => view('pages/vendor-profile'));

    Route::get('/vendor-profile/vendor-profile-edit', fn () => view('pages/vendor-profile-edit'));

    Route::get('/vendor-profile/vendor-address-add', fn () => view('pages/vendor-address-add'));
});

Route::get('/vendor-profile/vendor-product-edit', function () {
    return view('pages/vendor-product-edit');
})->name('vendor.products.edit');

Route::get('/vendor-profile/vendor-product-add', function () {
    return view('pages/vendor-product-add');
})->name('vendor.products.add');

Route::get('/vendor-profile/vendor-product-add-bundle', function () {
    return view('pages/vendor-product-add-bundle');
});
Route::prefix('vendor')->name('vendor.')->group(function () {

    Route::get('/items/create', [VendorProductController::class, 'create'])
        ->name('items.create');

    Route::post('/items', [VendorProductController::class, 'store'])
        ->name('items.store');
});
