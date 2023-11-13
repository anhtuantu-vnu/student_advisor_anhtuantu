<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\_CONST;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\EventMember;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getUserEvents(Request $request)
    {
        try {
            $currentUser = Auth::guard('api')->user();

            $events = Event::whereHas('eventMembers', function ($query) use ($currentUser) {
                return $query->where([
                    ['user_id', '=', $currentUser->uuid],
                    ['status', '=', EventMember::STATUS_GOING],
                ]);
            });
            if (isset($request->end_date)) {
                $events = $events->whereDate('end_date', '<=', $request->end_date);
            }
            if (isset($request->start_date)) {
                $events = $events->whereDate('start_date', '>=', $request->start_date);
            }
            $events = $events->with(['createdByUser', 'updatedByUser', 'eventMembers'])->get();

            $existedEventIds = [];
            foreach ($events as $event) {
                array_push($existedEventIds, $event->id);
            }

            $events2 = Event::where('created_by', '=', $currentUser->uuid)
                ->whereNotIn('created_by', $existedEventIds)
                ->with(['createdByUser', 'updatedByUser', 'eventMembers'])
                ->get();

            $res = $events->merge($events2);

            $data = [
                'events'  => $res,
            ];
            return $this->successWithContent($data);
        } catch (\Exception $e) {
            report($e);
            return $this->failedWithErrors(500, 'Error getting user events: ' . $e->getMessage());
        }
    }
}
