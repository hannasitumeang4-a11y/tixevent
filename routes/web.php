<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/events/{id}', [PageController::class, 'detail'])->name('events.detail');
Route::get('/history', [PageController::class, 'history'])->name('history');

// Checkout Flow
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [PageController::class, 'processPayment'])->name('checkout.process');
Route::get('/checkout/success', [PageController::class, 'success'])->name('payment.success');

// AUTH UI
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');

// AUTH PROCESS
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [PageController::class, 'usermanage'])->name('admin.usermanage');
    Route::get('/events', [PageController::class, 'eventmanage'])->name('admin.eventmanage');
});