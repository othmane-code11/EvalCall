<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/',[AuthController::class,'Showlogin'])->name('login.page');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::get('/dashboard',[AuthController::class,'dashboard'])->name('dashboard');
Route::get('/users',[AuthController::class,'users'])->name('users');
Route::get('/evaluations',[AuthController::class,'evaluations'])->name('evaluations');
Route::post('/create-user',[AuthController::class,'createUser'])->name('create.user');


route::get('/settings', function () {
    return view('settings');
})->name('settings');
route::get('/dashboard1', function () {
    return view('dashboard1');
})->name('dashboard1');

route::get('/d2', function () {
    return view('d2');
})->name('d2');