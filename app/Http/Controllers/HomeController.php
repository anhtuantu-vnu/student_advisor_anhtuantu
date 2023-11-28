<?php

namespace App\Http\Controllers;

use App\Models\ChMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function showHome()
    {
        $user = auth()->user();
        if ($user == null) {
            return redirect('/login');
        }

        if ($user->role == _CONST::ADMIN_ROLE) {
            return redirect('/admin-dashboard');
        } else {
            return view('front-end.layouts.layout_home');
        }
    }

    /**
     */
    public function getUnreadMessage(): JsonResponse
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $chMessage = ChMessage::where('to_id', $user->id)->where('seen', 0)->get()->toArray();

            return $this->successWithContent(count($chMessage) ?? 0);
        }

        return $this->successWithContent(0);
    }
}
