<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController; 
use App\Http\Controllers\OrganizerEventController; 
use App\Http\Controllers\AdminController; // SINKRONISASI: Import AdminController
use App\Http\Middleware\IsOrganizer; 

/*
|--------------------------------------------------------------------------
| FRONTEND LAYOUT
|--------------------------------------------------------------------------
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/events/{id}', [PageController::class, 'detail'])->name('events.detail');
Route::get('/history', [PageController::class, 'history'])->middleware('auth')->name('history');
Route::get('/about', [PageController::class, 'about'])->name('about');

Route::get('/review', [ReviewController::class, 'index'])->name('review');
Route::post('/review', [ReviewController::class, 'store'])->middleware('auth')->name('review.store');

/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function(){
    Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [PageController::class, 'processPayment'])->name('checkout.process');
    Route::get('/checkout/success', [PageController::class, 'success'])->name('payment.success');
    Route::get('/invoice/{id}', [PageController::class, 'invoice'])->name('invoice');
});

/*
|--------------------------------------------------------------------------
| CART (KERANJANG BELANJA)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function(){
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

/*
|--------------------------------------------------------------------------
| PROFILE USER
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function(){
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
});

/*
|--------------------------------------------------------------------------
| AUTH GUEST & PROCESS
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function(){
    Route::get('/login', [PageController::class, 'login'])->name('login');
    Route::get('/register', [PageController::class, 'register'])->name('register');
});

Route::post('/login', [AuthController::class, 'loginProcess'])->name('login.process');
Route::post('/register', [AuthController::class, 'registerProcess'])->name('register.process');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD MANAGEMENT (FIXED)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth')->group(function(){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [PageController::class, 'usermanage'])->name('admin.usermanage');
    Route::get('/events', [PageController::class, 'eventmanage'])->name('admin.eventmanage');
});

/*
|--------------------------------------------------------------------------
| ORGANIZER MANAGEMENT (SINKRONISASI TOTAL & UPGRADE AKSI LENGKAP)
|--------------------------------------------------------------------------
*/
Route::prefix('organizer')->middleware(['auth', IsOrganizer::class])->group(function(){
    Route::get('/dashboard', [OrganizerEventController::class, 'dashboard'])->name('organizer.dashboard');
    Route::get('/events/create', [OrganizerEventController::class, 'create'])->name('organizer.events.create');
    Route::post('/events/store', [OrganizerEventController::class, 'store'])->name('organizer.events.store');
    
    // ROUTE UNTUK EXPORT EXCEL
    Route::get('/events/{id}/export-manifest', [OrganizerEventController::class, 'exportManifest'])->name('organizer.events.export');
    
    Route::get('/events/{id}/edit', [OrganizerEventController::class, 'edit'])->name('organizer.events.edit');
    Route::put('/events/{id}', [OrganizerEventController::class, 'update'])->name('organizer.events.update');
    
    // FIXED: Route delete ditambahkan di sini
    Route::delete('/events/{id}', [OrganizerEventController::class, 'destroy'])->name('organizer.events.destroy');
    
    // Route parameter dinamis dasar diletakkan paling bawah
    Route::get('/events/{id}', [OrganizerEventController::class, 'show'])->name('organizer.events.show');
});