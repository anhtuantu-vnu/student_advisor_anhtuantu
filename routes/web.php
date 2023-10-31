<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Str;
use App\Http\Controllers\PlanController;

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
Route::get('/auth/redirect', [AccountController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/callback', [AccountController::class, 'handleGoogleCallback']);
Route::post('/login', [AccountController::class, 'login']);
Route::post('/register', [AccountController::class, 'register']);

Route::middleware(['auth.login'])->group(function () {
    Route::get("/plan", [PlanController::class, 'showPlan'])->name('app.plan');
    Route::get("/create-plan", [PlanController::class, 'formCreatePlan'])->name('ui_create_plan');
    Route::post('/create-plan', [PlanController::class, 'createPlan'])->name('create_plan');

    //route view
    Route::get("/home", function () {
        return view('front-end.layouts.layout_home');
    })->name('app.home');

    Route::post('register', 'App\Http\Controllers\AccountController@register')->name('register');

    Route::get("plan", function () {
        return view('front-end.layouts.layout_plan');
    })->name('app.plan');

    Route::get("/to-do", [TodoController::class, 'showToDo'])->name('app.to_do');
});
//route view
Route::get("/", [HomeController::class, 'showHome'])->name('app.home');
Route::get('/login', [AccountController::class, 'showLogin'])->name('app.login');
