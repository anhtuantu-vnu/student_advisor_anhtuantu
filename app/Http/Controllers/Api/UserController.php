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

    public function studentIntakes()
    {
        try {
            $currentUser = Auth::guard('api')->user();
            $intakeMembers = IntakeMember::where('user_id', '=', $currentUser->uuid)
                ->with(['intake' => function ($query) {
                    return $query->with(['subject']);
                }])->get();
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
