<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthContoller as ApiAuthContoller;
use App\Http\Controllers\Api\UserController as UserController;
use App\Http\Controllers\Api\DepartmentController as DepartmentController;
use App\Http\Controllers\Api\IntakeController as IntakeController;
use App\Http\Controllers\Api\EventController as EventController;
use App\Http\Controllers\Api\NotificationController as NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ApiAuthContoller::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::get('/student-intakes', [UserController::class, 'studentIntakes']);
Route::get('/student-intakes/{uuid}/teacher-info', [IntakeController::class, 'getIntakeTeacherInfo']);
Route::get('/departments', [DepartmentController::class, 'actionDepartments']);

Route::get('/user-events', [EventController::class, 'getUserEvents']);
Route::get("/user-notifications", [NotificationController::class, 'getuserNotifications']);
Route::post("/notifications/{id}/read", [NotificationController::class, 'markNotificationAsRead']);
