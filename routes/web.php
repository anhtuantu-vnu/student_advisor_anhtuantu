<?php

use App\Http\Controllers\ChatController;
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
Route::get('/auth/redirect', [AccountController::class , 'redirectToGoogle'])->name('login.google');
Route::get('/auth/callback',[AccountController::class , 'handleGoogleCallback']);
Route::post('/login', [AccountController::class , 'login']);
Route::post('/register', [AccountController::class , 'register']);
Route::get('/login', [AccountController::class, 'showLogin'])->name('app.login');

Route::middleware(['auth.login'])->group(function() {
    Route::get("/", [HomeController::class, 'showHome'])->name('app.home');

    //route plan
    Route::get("/plan",[PlanController::class , 'showPlan'])->name('plan');
    Route::get("/create-plan", [PlanController::class , 'formCreatePlan'])->name('ui_create_plan');
    Route::post('/create-plan', [PlanController::class , 'createPlan'])->name('create_plan');

    //route task
    Route::get("/to-do", [TodoController::class, 'showToDo'])->name('app.to_do');

    //route chat
    Route::get('chat', [ChatController::class, 'index'])->name('app.login');
    Route::post('/broadcast', [ChatController::class, 'broadcast']);
    Route::post('/receive', [ChatController::class, 'receive']);
});

