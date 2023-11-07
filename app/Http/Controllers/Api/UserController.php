<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\IntakeMember;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function studentIntakes(Request $request)
    {
        try {
            $limit = -1;
            $page = -1;
            $skip = -1;
            if (isset($request->limit)) {
                $limit = intval($request->limit);
            }
            if (isset($request->page)) {
                $page = intval($request->page);
            }

            if ($limit > 0 && $page > 0) {
                $skip = $limit * $page - $limit;
            }

            $currentUser = Auth::guard('api')->user();

            $weekDay = $request->weekDay;
            if ($limit > 0 && $page > 0) {
                $intakeMembers = IntakeMember::where('user_id', '=', $currentUser->uuid);
                $intakeMembers = $intakeMembers->skip($skip)
                    ->take($limit)
                    ->whereHas('intake', function ($query) use ($weekDay) {
                        if ($weekDay != null && $weekDay != '') {
                            return $query->where('week_days', 'like', '%' . $weekDay . '%')
                                ->with(['subject']);
                        }
                    })->with(['intake' => function ($query) {
                        return $query->with(['subject']);
                    }])->orderBy('id', 'desc');

                $intakeMembers = $intakeMembers->get();
            } else {
                $intakeMembers = IntakeMember::where('user_id', '=', $currentUser->uuid)
                    ->whereHas('intake', function ($query) use ($weekDay) {
                        if ($weekDay != null && $weekDay != '') {
                            return $query->where('week_days', 'like', '%' . $weekDay . '%')
                                ->with(['subject']);
                        }
                    })->with(['intake' => function ($query) {
                        return $query->with(['subject']);
                    }])->orderBy('id', 'desc')
                    ->get();
            }

            $data = [
                'intakeMembers'  => $intakeMembers,
            ];
            return $this->successWithContent($data);
        } catch (\Exception $e) {
            report($e);
            return $this->failedWithError(Response::HTTP_INTERNAL_SERVER_ERROR, 'Error getting events: ' . $e->getMessage());
        }
    }
}
