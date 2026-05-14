<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/',[AuthController::class,'Showlogin'])->name('login.page');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[AuthController::class,'dashboard'])->name('dashboard');
    Route::get('/users',[AuthController::class,'users'])->name('users');
    Route::post('/create-user',[AuthController::class,'createUser'])->name('create.user');
    Route::put('/users/{id}', [AuthController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser'])->name('users.destroy');
    
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');
    Route::get('/conseillers', function () { 
        return view('conseiller-dashboard');
    })->name('conseillers');

    Route::get('/evaluations',[AuthController::class,'evaluations'])->name('evaluations');
    Route::get('/evaluations/create',[AuthController::class,'evaluationsCreate'])->name('evaluations.create');
    Route::post('/evaluations',[AuthController::class,'storeEvaluation'])->name('evaluations.store');
    Route::get('/export', [AuthController::class, 'export'])->name('export');
    Route::get('/settings', [AuthController::class, 'settings'])->name('settings');
    Route::post('/settings', [AuthController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/password', [AuthController::class, 'updatePassword'])->name('settings.password.update');
});

route::get('/reports', function () {
    return view('reports');
})->name('reports');
