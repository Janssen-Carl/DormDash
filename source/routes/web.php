<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/home', fn () => redirect('/'));

Route::get('/register', fn () => view('auth/register'));
Route::post('/register', [UserController::class, 'register']);

Route::get('/login', fn () => view('auth/login'))->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout']);

/* -------------------- AUTH COMMON -------------------- */

Route::middleware('auth')->group(function () {

    Route::get('/products', fn () => view('pages/products'));
    Route::get('/orders', fn () => view('pages/orders'));
    Route::get('/orders-overview', fn () => view('pages/orders-overview'));
    Route::get('/profile', fn () => view('pages/profile'));
    Route::get('/profile/edit', fn () => view('pages/profile-edit'));
    Route::get('/address-payment/add', fn () => view('pages/address-payment-add'));
});

/* -------------------- CUSTOMER ONLY -------------------- */

Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/cart', fn () => view('pages/cart'));

    Route::get('/customer/home', [HomeController::class, 'index'])
        ->name('customer.home');
});

/* -------------------- VENDOR ONLY -------------------- */

Route::middleware(['auth', 'role:vendor'])->group(function () {

    Route::get('/vendor/home', fn () => view('vendor-home'))
        ->name('vendor.home');

    Route::get('/vendor-products', fn () => view('pages/vendor-products'));

    Route::get('/vendor-profile', fn () => view('pages/vendor-profile'));

    Route::get('/vendor-profile/vendor-profile-edit', fn () => view('pages/vendor-profile-edit'));

    Route::get('/vendor-profile/vendor-address-add', fn () => view('pages/vendor-address-add'));
});

Route::get('/vendor-profile/vendor-product-edit', function () {
    return view('pages/vendor-product-edit');
});

Route::get('/vendor-profile/vendor-product-add', function () {
    return view('pages/vendor-product-add');
});


