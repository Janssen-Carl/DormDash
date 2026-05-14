<?php

use App\Http\Controllers\UserController;
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
});

Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class, 'logout']);

Route::get('/products', function () {
    return view('pages/products');
});

Route::get('/cart', function () {
    return view('pages/cart');
})->middleware('auth');

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

Route::get('/vendor-home', function () {
    return view('vendor-home');
});

Route::get('/vendor-products', function () {
    return view('pages/vendor-products');
});