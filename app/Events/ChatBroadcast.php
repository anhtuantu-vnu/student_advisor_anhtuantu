<?php

namespace App\Events;

use App\Models\ChatChanel;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public  $message;
//    public User $user;

    public function __construct($message)
    {
        $this->message = $message;
//        $this->user = $user;
    }

    /**
     * Broadcast On
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('public');
    }

    public function broadcastAs(): string
    {
        return 'chat';
    }
}
