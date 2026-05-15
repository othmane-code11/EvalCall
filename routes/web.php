<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/',[AuthController::class,'Showlogin'])->name('login.page');
Route::post('/login',[AuthController::class,'login'])->name('login');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',[AuthController::class,'dashboard'])->middleware('role:admin,manager')->name('dashboard');
    Route::get('/conseiller-dashboard',[AuthController::class,'conseillerDashboard'])->middleware('role:conseiller')->name('conseiller.dashboard');
    Route::get('/users',[AuthController::class,'users'])->middleware('role:admin,manager')->name('users');
    Route::post('/create-user',[AuthController::class,'createUser'])->middleware('role:admin,manager')->name('create.user');
    Route::put('/users/{id}', [AuthController::class, 'updateUser'])->middleware('role:admin,manager')->name('users.update');
    Route::delete('/users/{id}', [AuthController::class, 'deleteUser'])->middleware('role:admin,manager')->name('users.destroy');
    
    Route::post('/logout',[AuthController::class,'logout'])->name('logout');

    Route::get('/evaluations',[AuthController::class,'evaluations'])->middleware('role:admin,manager')->name('evaluations');
    Route::get('/evaluations/create',[AuthController::class,'evaluationsCreate'])->middleware('role:admin,manager')->name('evaluations.create');
    Route::post('/evaluations',[AuthController::class,'storeEvaluation'])->middleware('role:admin,manager')->name('evaluations.store');
    Route::get('/export', [AuthController::class, 'export'])->middleware('role:admin,manager')->name('export');
    Route::get('/settings', [AuthController::class, 'settings'])->name('settings');
    Route::post('/settings', [AuthController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/password', [AuthController::class, 'updatePassword'])->name('settings.password.update');
});

route::get('/reports', function () {
    return view('reports');
})->middleware(['auth', 'role:admin,manager'])->name('reports');


