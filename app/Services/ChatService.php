<?php

namespace App\Services;

use App\Events\ChatBroadcast;
use App\Models\ChatMessage;
use App\Models\User;
use App\Repositories\ChatMessageRepository;
use App\Repositories\RoomRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatService
{
    public UserRepository $userRepository;
    public ChatMessageRepository $chatMessageRepository;
    public RoomRepository $roomRepository;

    public function __construct(UserRepository $userRepository, ChatMessageRepository $chatMessageRepository)
    {
        $this->userRepository = $userRepository;
        $this->chatMessageRepository = $chatMessageRepository;

    }


    public function sendMessage(Request $request)
    {
        $sender = $this->userRepository->findOne(['email' => $request->input('sender')]);
        $receiver = $this->userRepository->findOne(['email' => $request->input('receiver')]);
//        $chatMessage = $this->chatMessageRepository->create([
//            'content'     => $request->input('message') ?? '',
//            'type'        => 'text',
//            'channel_id'  => 123,
//            'sender_id'   => 1,
//            'receiver_id' => 2,
//            'id'          => rand(1, 20000000),
//            'uuid'        => Str::uuid(),
//        ]);

        broadcast(new ChatBroadcast($request->input('message')))->toOthers();
    }

    public function getRoomChat(Request $request)
    {
        $room = $this->roomRepository->find(['uuid' => $request->input('info')]);
        if ($room) {
            return $room;
        }

        return [];
    }
}