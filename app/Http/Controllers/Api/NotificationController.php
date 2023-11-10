<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\_CONST;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getuserNotifications(Request $request)
    {
        try {
            $currentUser = Auth::guard('api')->user();

            $limit = 10;
            $page = 1;

            if (isset($request->limit)) {
                $limit = intval($request->limit);
            }
            if (isset($request->page)) {
                $page = intval($request->page);
            }
            $skip = $limit * $page - $limit;

            $notifications = Notification::where('target_user', '=', $currentUser->uuid)
                ->skip($skip)
                ->take($limit)
                ->with(['targetUserInfo', 'originUserInfo', 'event'])
                ->orderBy('id', 'desc')
                ->get();

            $data = [
                'notifications'  => $notifications,
            ];
            return $this->successWithContent($data);
        } catch (\Exception $e) {
            report($e);
            return $this->failedWithErrors(500, 'Error getting user notifications: ' . $e->getMessage());
        }
    }

    public function markNotificationAsRead($id)
    {
        try {
            DB::beginTransaction();

            $user = auth()->user();

            $thisNotification = Notification::findOrFail($id);

            if ($thisNotification == null) {
                return $this->failedWithErrors(404, 'cannot fin notification');
            }

            if ($thisNotification->target_user != $user->uuid) {
                return $this->failedWithErrors(400, 'this is not your notification');
            }


            $newNotification = [
                "read" => true,
            ];

            $thisNotification->fill($newNotification);
            $thisNotification->save();
            DB::commit();

            $data = [
                'notification' => $thisNotification,
            ];
            return $this->successWithContent($data);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->failedWithErrors(500, $exception->getMessage());
        }
    }
}
