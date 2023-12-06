<?php

namespace App\Providers;

use App\Facades\ChatUserMessenger;
use App\Facades\FileUpload as UploadFileStudent;
use App\Models\Notification;
use App\Observers\NotificationObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind('ChatUserMessenger', function () {
            return new ChatUserMessenger();
        });
        app()->bind('UploadFileStudent', function () {
            return new UploadFileStudent();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Notification::observe(NotificationObserver::class);
    }
}
