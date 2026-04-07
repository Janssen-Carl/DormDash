<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [UserController::class, 'register']);

Route::get('/login', function () {
    return view('slave');
});

Route::get('/catalog', function () {
    return view('catalog');
});
