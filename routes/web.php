<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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
Route::get('/auth/redirect', [LoginController::class , 'redirectToGoogle']);
Route::get('/auth/callback',[LoginController::class , 'handleGoogleCallback']);

Route::get("/", function() {
    return view('front-end.layouts.layout_home');
});
Route::get("to-do", function() {
    return view('front-end.layouts.layout_todo');
});
Route::get('/login', function() {
   return view('front-end.layouts.login');
});
