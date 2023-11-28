<?php

namespace App\Http\Controllers;

use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserSystemNotificationController extends Controller
{
    public function index()
    {
        return view('front-end.layouts.systemNotification.index');
    }

    public function getNotifications(Request $request)
    {
        try {
            $limit = 10;
            $page = 1;
            $active = true;

            if (isset($request->limit)) {
                $limit = intval($request->limit);
            }

            if (isset($request->page)) {
                $page = intval($request->page);
            }

            if (isset($request->active)) {
                $active = $request->active == "1";
            }

            $skip = $limit * $page - $limit;

            $systemNotifications = SystemNotification::where('active', '=', $active)
                ->take($limit)->skip($skip)->get();

            return $this->successWithContent([
                'systemNotifications' => $systemNotifications,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return $this->failedWithErrors(500, $e->getMessage());
        }
    }
}
