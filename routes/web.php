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

Route::get('/', function () {
    return view('welcome');
});

//authentication socialite
Route::get('/auth/redirect', [LoginController::class , 'redirectToGoogle']);

Route::get('/auth/callback',[LoginController::class , 'handleGoogleCallback']);

Route::get("test", function() {
    return view('font-end.layout.layout_home');
});
