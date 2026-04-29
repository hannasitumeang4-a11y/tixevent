<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/events/{id}', [PageController::class, 'detail'])->name('events.detail');
Route::get('/history', [PageController::class, 'history'])->name('history');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');

// AUTH UI
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');

// AUTH PROCESS
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/users', [PageController::class, 'usermanage'])->name('admin.usermanage');

    Route::get('/events', [PageController::class, 'eventmanage'])->name('admin.eventmanage');
});

/*
|--------------------------------------------------------------------------
| TESTING
|--------------------------------------------------------------------------
*/

Route::prefix('dev')->group(function () {
    
    Route::get('/home', [PageController::class, 'homeTesting'])->name('dev.home');

    Route::prefix('events')->group(function () {
        Route::get('/create', [EventController::class, 'create'])->name('dev.events.create');
        Route::post('/', [EventController::class, 'store'])->name('dev.events.store');
        Route::get('/{id}', [PageController::class, 'eventDetailTesting'])->name('dev.events.show');
    });

    Route::get('/admin/stats', [AdminController::class, 'dashboard'])->name('dev.admin.stats');
});