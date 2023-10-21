<?php

namespace App\Http\Controllers;

use App\Events\ChatBroadcast;
use App\Models\User;
use App\Services\ChatService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * @param Request $request
     * @param $roomUUID
     * @return View
     */
    public function index(Request $request): View
    {
        $roomChat = $this->chatService->getRoomChat($request);
        return view('front-end.layouts.chat.index', compact($roomChat));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function broadcast(Request $request): View
    {
        $this->chatService->sendMessage($request);

        return view('front-end.layouts.chat.broadcast', ['message' => $request->get('message')]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function receive(Request $request): View
    {
        return view('front-end.layouts.chat.receive', ['message' => $request->get('message')]);
    }

    /**
     * @return View
     */
    public function chatRoom(): View
    {
        return view('front-end.layouts.chat.list');
    }
}
