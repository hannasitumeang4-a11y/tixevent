<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| 🎨 ZONA FRONTEND (Murni UI & Navigasi Utama)
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/events/{id}', [PageController::class, 'detail'])->name('events.detail');
Route::get('/history', [PageController::class, 'history'])->name('history');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');

// Grouping untuk Auth agar rapi
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/register', [PageController::class, 'register'])->name('register');

// Grouping untuk Admin UI
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/users', [PageController::class, 'usermanage'])->name('admin.usermanage');
    Route::get('/events', [PageController::class, 'eventmanage'])->name('admin.eventmanage');
});


/*
|--------------------------------------------------------------------------
| 🧪 ZONA BACKEND / TESTING (Laboratorium Eksperimen)
|--------------------------------------------------------------------------
*/

Route::prefix('dev')->group(function () {
    
    // Testing Home dengan Filter yang rumit
    Route::get('/home', [PageController::class, 'homeTesting'])->name('dev.home');

    // Testing Event (CRUD & Detail)
    Route::prefix('events')->group(function () {
        Route::get('/create', [EventController::class, 'create'])->name('dev.events.create');
        Route::post('/', [EventController::class, 'store'])->name('dev.events.store');
        
        // Testing Detail (Gunakan prefix dev agar tidak tabrakan dengan UI utama)
        Route::get('/{id}', [PageController::class, 'eventDetailTesting'])->name('dev.events.show');
    });

    // Testing Admin Logic
    Route::get('/admin/stats', [AdminController::class, 'dashboard'])->name('dev.admin.stats');
});