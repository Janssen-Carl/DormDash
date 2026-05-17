<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/register', function () {
    return view('auth/register');
});

Route::post('/register', [UserController::class, 'register']);

Route::get('/login', function () {
    return view('auth/login');
})->name('login');

Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout']);

Route::get('/products', function () {
    return view('pages/products');
});


Route::get('/test-session', function () {
    session(['test' => 'working']);

    return session('test');
});

Route::get('/auth-check', function () {
    return [
        'logged_in' => auth()->check(),
        'user' => auth()->user(),
    ];
});

Route::get('/test-login', function () {

    auth()->loginUsingId(1);

    return [
        'logged_in' => auth()->check(),
        'user' => auth()->user(),
    ];
});
/* Customer Routes */

Route::get('/cart', function () {
    return view('pages/cart');
})->middleware('user');

/*
Route::get('/cart', function () {
    return view('pages/cart');});
*/
Route::get('/orders', function () {
    return view('pages/orders');
})->middleware('auth');

Route::get('/orders-overview', function () {
    return view('pages/orders-overview');
})->middleware('auth');

Route::get('/profile', function () {
    return view('pages/profile');
})->middleware('auth');

Route::get('/profile/edit', function () {
    return view('pages/profile-edit');
})->middleware('auth');

Route::get('/address-payment/add', function () {
    return view('pages/address-payment-add');
})->middleware('auth');


/* Vendor Routes */
Route::get('/vendor-home', function () {
    return view('vendor-home');
});

Route::get('/vendor-products', function () {
    return view('pages/vendor-products');
});

Route::get('/vendor-profile', function () {
    return view('pages/vendor-profile');
});

Route::get('/vendor-profile/vendor-profile-edit', function () {
    return view('pages/vendor-profile-edit');
});

Route::get('/vendor-profile/vendor-address-add', function () {
    return view('pages/vendor-address-add');
});

Route::get('/vendor-product-add', function () {
    return view('pages/vendor-product-add');
});

Route::get('/vendor-product-edit', function () {
    return view('pages/vendor-product-edit');
});


Route::get('/vendor-product-add-bundle', function () {
    return view('pages/vendor-product-add-bundle');
});