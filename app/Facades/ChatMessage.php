<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ChatMessage extends Facade
{
    /**
     * Make facade
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ChatUserMessenger';
    }
}