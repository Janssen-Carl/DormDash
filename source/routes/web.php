<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});


Route::get('/register', function () {
    return view('register');
});


Route::get('/login', function () {
    return view('login');
});

Route::post('/register', [UserController::class, 'register']);
