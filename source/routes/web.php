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

Route::get('/products', function () {
    return view('pages/products');
});

Route::get('/cart', function () {
    return view('pages/cart');
});

Route::get('/orders', function () {
    return view('pages/orders');
});

Route::get('/orders-overview', function () {
    return view('pages/orders-overview');
});

Route::get('/profile', function () {
    return view('pages/profile');
});

Route::get('/profile/edit', function () {
    return view('pages/profile-edit');
});

Route::get('/address-payment/add', function () {
    return view('pages/address-payment-add');
});



