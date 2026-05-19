<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| FRONTEND
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [PageController::class,'home']
)->name('home');


Route::get(
    '/events/{id}',
    [PageController::class,'detail']
)->name('events.detail');


Route::get(
    '/history',
    [PageController::class,'history']
)->middleware('auth')
->name('history');



/*
|--------------------------------------------------------------------------
| CHECKOUT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function(){

    Route::get(
        '/checkout',
        [PageController::class,'checkout']
    )->name('checkout');


    Route::post(
        '/checkout/process',
        [PageController::class,'processPayment']
    )->name('checkout.process');


    Route::get(
        '/checkout/success',
        [PageController::class,'success']
    )->name('payment.success');


    Route::get(
        '/invoice/{id}',
        [PageController::class,'invoice']
    )->name('invoice');

});

/*
|--------------------------------------------------------------------------
| CART
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
->group(function(){

Route::get(
'/cart',
[CartController::class,'index']
)->name('cart');


Route::post(
'/cart/add',
[CartController::class,'add']
)->name('cart.add');


Route::get(
'/cart/remove/{id}',
[CartController::class,'remove']
)->name('cart.remove');

});


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function(){

    Route::get(
        '/profile',
        [PageController::class,'profile']
    )->name('profile');

});



/*
|--------------------------------------------------------------------------
| AUTH UI
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function(){

    Route::get(
        '/login',
        [PageController::class,'login']
    )->name('login');


    Route::get(
        '/register',
        [PageController::class,'register']
    )->name('register');

});



/*
|--------------------------------------------------------------------------
| AUTH PROCESS
|--------------------------------------------------------------------------
*/

Route::post(
    '/login',
    [AuthController::class,'loginProcess']
)->name('login.process');


Route::post(
    '/register',
    [AuthController::class,'registerProcess']
)->name('register.process');


Route::post(
    '/logout',
    [AuthController::class,'logout']
)->middleware('auth')
->name('logout');



/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
->middleware('auth')
->group(function(){

    Route::get(
        '/dashboard',
        [PageController::class,'dashboard']
    )->name('admin.dashboard');


    Route::get(
        '/users',
        [PageController::class,'usermanage']
    )->name('admin.usermanage');


    Route::get(
        '/events',
        [PageController::class,'eventmanage']
    )->name('admin.eventmanage');

});