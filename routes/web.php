<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/',[AuthController::class,'Showlogin'])->name('login.page');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[AuthController::class,'dashboard'])->name('dashboard');
    Route::get('/users',[AuthController::class,'users'])->name('users');
    Route::post('/create-user',[AuthController::class,'createUser'])->name('create.user');
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser'])->name('users.destroy');
    Route::get('/evaluations',[AuthController::class,'evaluations'])->name('evaluations');
});

route::get('/settings', function () {
    return view('settings');
})->name('settings');


route::get('/settings', function () {
    return view('settings');
})->name('settings');
