<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;

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
Route::post('/login', [AccountController::class, 'login'])->name('login');
Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
Route::post('/register', [AccountController::class, 'register']);
Route::get('/login', [AccountController::class, 'showLogin'])->name('app.login');

Route::middleware(['auth.login'])->group(function () {
    Route::get("/", [HomeController::class, 'showHome'])->name('app.home');
    Route::get("/home", [HomeController::class, 'showHome'])->name('app.home');

    Route::post("/update-lang", [AccountController::class, 'updateLang'])->name('app.update.lang');

    Route::get("/my-profile", [AccountController::class, 'showProfile'])->name('app.my.profile');
    Route::post("/update-avatar", [AccountController::class, 'updateAvatar'])->name('app.update.avatar');

    // route event
    Route::post("/create-event", [EventController::class, 'createEvent'])->name('event.create');
    Route::get("/events", [EventController::class, 'getEvents'])->name('event.get');
    Route::post("/events/{id}", [EventController::class, 'updateEvent'])->name('event.update');

    //route plan
    Route::get("/plan", [PlanController::class, 'showPlan'])->name('plan');
    Route::get("/create-plan", [PlanController::class, 'formCreatePlan'])->name('ui_create_plan');
    Route::post('/create-plan', [PlanController::class, 'createPlan'])->name('create_plan');

    //route task
    Route::get("/to-do", [TodoController::class, 'showTasks'])->name('show_task');
    Route::post("/to-do", [TodoController::class, 'createTask'])->name('create_task');

    /**
     *  Fetch calendar events for a user
     */
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    Route::get('/student-chat', [MessageController::class, 'index'])->name('chat');

    /**
     *  Fetch info for specific id [user/group]
     */
    Route::post('/student-chat/idInfo', [MessageController::class, 'idFetchData']);

    /**
     * Send message route
     */
    Route::post('/student-chat/sendMessage',  [MessageController::class, 'send'])->name('send.message');

    /**
     * Fetch messages
     */
    Route::post('/student-chat/fetchMessages', [MessageController::class, 'fetch'])->name('fetch.messages');

    /**
     * Download attachments route to create a downloadable links
     */
    Route::get('/student-chat/download/{fileName}', [MessageController::class, 'MessagesController'])->name(config('chatify.attachments.download_route_name'));

    /**
     * Authentication for pusher private channels
     */
    Route::post('/student-chat/chat/auth', [MessageController::class, 'pusherAuth'])->name('pusher.auth');

    /**
     * Make messages as seen
     */
    Route::post('/student-chat/makeSeen', [MessageController::class, 'seen'])->name('messages.seen');

    /**
     * Get contacts
     */
    Route::get('/student-chat/getContacts', [MessageController::class, 'getContacts'])->name('contacts.get');

    /**
     * Update contact item data
     */
    Route::post('/student-chat/updateContacts', [MessageController::class, 'updateContactItem'])->name('contacts.update');


    /**
     * Star in favorite list
     */
    Route::post('/student-chat/star', [MessageController::class, 'favorite'])->name('star');

    /**
     * get favorites list
     */
    Route::post('/student-chat/favorites', [MessageController::class, 'getFavorites'])->name('favorites');

    /**
     * Search in messenger
     */
    Route::get('/student-chat/search', [MessageController::class, 'search'])->name('search');

    /**
     * Get shared photos
     */
    Route::post('/student-chat/shared', [MessageController::class, 'sharedPhotos'])->name('shared');

    /**
     * Delete Conversation
     */
    Route::post('/student-chat/deleteConversation', [MessageController::class, 'deleteConversation'])->name('conversation.delete');

    /**
     * Delete Message
     */
    Route::post('/student-chat/deleteMessage', [MessageController::class, 'deleteMessage'])->name('message.delete');

    /**
     * Update setting
     */
    Route::post('/student-chat/updateSettings', [MessageController::class, 'updateSettings'])->name('avatar.update');

    /**
     * Set active status
     */
    Route::post('/student-chat/setActiveStatus', [MessageController::class, 'setActiveStatus'])->name('activeStatus.set');
    Route::get('/student-chat/{id}', [MessageController::class, 'index'])->name('user');
});

//route view
Route::get('/login', [AccountController::class, 'showLogin'])->name('app.login');

Route::get('chat', [ChatController::class, 'index'])->name('app.login');
Route::post('/broadcast', [ChatController::class, 'broadcast']);
Route::post('/receive', [ChatController::class, 'receive']);
