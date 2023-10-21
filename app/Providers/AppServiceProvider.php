<?php

namespace App\Providers;

use App\Facades\ChatUserMessenger;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
