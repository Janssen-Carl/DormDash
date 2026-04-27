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



