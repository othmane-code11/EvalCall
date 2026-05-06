<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/',[AuthController::class,'Showlogin'])->name('login.page');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/users', function () {
    return view('users');
})->name('users');
