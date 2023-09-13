<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//authentication socialite
Route::get('/auth/redirect', [AccountController::class , 'redirectToGoogle'])->name('login.google');
Route::get('/auth/callback',[AccountController::class , 'handleGoogleCallback']);

Route::get("/", function() {
    return view('front-end.layouts.layout_home');
})->name('app.home');
Route::get("to-do", function() {
    return view('front-end.layouts.layout_todo');
})->name('app.to_do');
Route::get('/login', function() {
   return view('front-end.layouts.login');
})->name('app.login');
